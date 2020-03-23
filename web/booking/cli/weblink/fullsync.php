<?php

/*
 *
 * This cron attempts to insert availability for properties that haven't had availability set
 * Interval: 1x a week
 *
 * Step 1: Get all `properties` with avail_run that is NULL
 * Step 2: Call getPropertyAvailabilityInfo to grab bookings (if any)
 * Step 3: Insert any non available dates into `v12_nonavaildates`
 * Step 4: Take the non avail dates (if any) and merge with +90 days from today, marking availability=0 for those booked and insert into `availability`
 *
 * Non avail dates inserted into v12_nonavaildates
 */

    $root = dirname(dirname(dirname(__FILE__)));

    $runstart=date('U');
    $starttime = new DateTime;
    $owserror=array();

    $single = false;

    // Single property for testing
    if ( isset( $argv[1]) ) {
        $single = $argv[1];
    }

    require_once('esite/config/conf.php');
    require_once 'esite/classes/class.phpmailer.php';
    require_once('esite/classes/Mailer.Class.php');
    require_once( $root . '/conf/conf.php');

    use weblink\Weblink;
    use weblink\Logger;
    use weblink\Exceptions;
    use weblink\Import;

    # Load v12/weblink classes
    require_once $root . '/conf/v12_load.php';

    // Reset this temporarily
    //Pull::setFinished( SYNCID_FULL );

    $service1 = new Weblink;

    $log_options = array(

        'dir' => LOG_DIR . '/fullsync/',
        'output' => 'log',

    );

    $logger1 = new Logger($log_options);
    $pull = new Pull( SYNCID_FULL );
    $pull->start();

    $logger1->log('Starting fullsync run', 'start');

    try {
        $import = new Import;
        $propertyindexes=$service1->fetchpropertyindexes();
        $propertyids=$propertyindexes->getpropertyids();

        if ( $single ) { 
            $propertyids = array($single);
            $import->propertyimport('partial', $service1, $logger1, $pull, $propertyids, $owserror);
        } else {
            $import->propertyimport('full', $service1, $logger1, $pull, $propertyids, $owserror);
        }


        echo 'Total runtime for property indexes: '.(date('U')-$runstart)." seconds. \n";
    } catch ( Exception $e ) {
        echo $e->getMessage() . PHP_EOL;
        exit;
    }

    $service = new Weblink;
    $pull = new Pull( SYNCID_FULL );
    $rateObj = new Rates;

    $log_options = array(

        'dir' => LOG_DIR . '/fullsync/',
        'output' => 'log',

    );

    $logger = new Logger($log_options);

    $logger->log('Starting fullsync run', 'start');

    $filters = array(
        #'avail_run' => NULL,
        #'unit_number' => 'VM1202',
    );

    // if single, specify
    if ( $single ) {
        $filters['unit_number'] = $single;
    }

    $properties = Property::getProperties($filters);

    if ( count( $properties ) == 0 ) {
        $logger->log('No properties found, exiting', 'end');
        $pull->end();
        exit;
    }

    $rates_updated = 0;
    $avail_updated = 0;

    $db = new Db;
    $db->throwExceptionsOnError = true;

    foreach ($properties as $property) {

        if ( $single && $property['unit_number'] != $single ) {
            continue;
        }

        $logger->log( 'Processing initial rates+ availability for property ' . $property['unit_number'] );

        $non_available_dates = array();

        try {

            // Pull Availability
            $oAvailability = $service->getpropertyavailability($property['unit_number']);

            $errors = $service->geterrormessages();

            // reset these
            $service->clearerrors();

            if ( !$oAvailability || (is_array($errors) && !empty($errors)) ) {

                $error = $errors[0];

                throw new Exception($error);

            }

            $logger->log( print_r($service->getdebuginfo(), true), 'end');

            // If raw bookings found insert them into availability_12
            $non_available_dates = $oAvailability->getnonavailabledatesraw();

            if ( $non_available_dates && count( $non_available_dates) > 0 ) {

                $table = 'v12_nonavaildates';

                $i = 0;
                $insert = 'insert into ' . $table . ' ( properties_id, startdate, enddate, confnum, staytype, date_created ) values ';
                foreach ($non_available_dates as $row ) {

                    $insert.= '("' . $property['id'] . '", ';

                    $st = new DateTime($row['dtFromDate']);
                    $ed = new DateTime($row['dtToDate']);

                    $insert.= '"' . $st->format('Y-m-d H:i:s') . '",';
                    $insert.= '"' . $ed->format('Y-m-d H:i:s') . '",';

                    // Confirmation number
                    if ( isset( $row['intQuoteNum'] ) ) {
                        $insert.= '"' . $row['intQuoteNum'] . '", ';
                    } else {
                        $insert.= 'NULL,';
                    }
                    #$insert.= '"' . (isset($row['quotenum'])?$row['quotenum']: 'null') . '", ';

                    /*
                     * Flag indicating the type of reservation that makes this date unavailable to
                     * the guest. Possible Values: 'O'-Reserved by Property Owner, 'G'-Reserved
                     * String
                     * for Guest of Owner, 'P'-Regular guest Reservation confirmed with
                     * payment, 'U'-Regular guest reservation not yet confirmed with payment,
                     * 'W'-Maintenance, 'R'- Regular guest reservation
                     */
                    if ( isset( $row['strStayType'] ) ) {
                        $insert.= '"' . $row['strStayType'] . '", ';
                    } else {
                        $insert.= 'NULL,';
                    }

                    $insert.= '"' . date('Y-m-d H:i:s') . '" ';

                    $insert.= ')';

                    // Dont put a comma on the last value
                    if ( $i+1 != count($non_available_dates) ) {
                        $insert.= ', ';
                    }

                    $i++;
                }

                $insert.= 'ON DUPLICATE KEY UPDATE properties_id=VALUES(properties_id), startdate=VALUES(startdate),  enddate=VALUES(enddate), confnum=VALUES(confnum), staytype=VALUES(staytype), lastupdated_cron = "' . date('Y-m-d H:i:s') . '"';

                $insert.= ';';

                // Delete all property info before pulling
                //$db->query('DELETE FROM v12_nonavaildates WHERE properties_id= "' . $property['id'] . '"');

                $rowcount = $db->query($insert);

                $logger->log('Inserted ' . $rowcount . ' rows into `' . $table . '` for property ' . $property['unit_number']);

            }

            // get if cached from the full property import
            $oPropertyDesc = $import->getpropertydesc($property['unit_number']);

            if ( $oPropertyDesc == null ) {
                // Pull Rates
                $oPropertyDesc = $service->fetchpropertydesc($property['unit_number']);
            }

            $rawrates = $oPropertyDesc->getrawrates();

            $cutoffdate = null;

            if ( !$oPropertyDesc || ($oPropertyDesc && $oPropertyDesc->isEmptyResult()) ) {
                $errors = $service->geterrormessages();

                // reset these
                $service->clearerrors();

                if ( !$oPropertyDesc || (is_array($errors) && !empty($errors)) ) {

                    $error = $errors[0];

                    throw new Exception($error);

                }
            }

            if ( !empty($rawrates) ) {
                $table = 'v12_rates';
                $insert = 'INSERT INTO ' . $table . ' ( properties_id, amount, rateplan_name, startdate, enddate, date_created ) VALUES ';
                $i = 0;
                foreach ($rawrates as $rate) {

                    if ( $cutoffdate === null || strtotime($rate['dtEndDate']) > strtotime($cutoffdate) ) {
                        $cutoffdate = date('Y-m-d', strtotime($rate['dtEndDate']));
                    }

                    $insert.= ' ( ';
                    $insert.= '  ' . $property['id'] . ', ';
                    $insert.= '"' . $rate['dblRate'] . '",';
                    $insert.= '"' . $rate['strChargeBasis'] . '",';

                    $startdate = new DateTime($rate['dtBeginDate']);
                    $enddate = new DateTime($rate['dtEndDate']);

                    $insert.= '"' . $startdate->format('Y-m-d H:i:s') . '",';
                    $insert.= '"' . $enddate->format('Y-m-d H:i:s') . '",';
                    $insert.= '"' . date('Y-m-d H:i:s') . '" ';
                    $insert.= ' ) ';

                    // Dont put a comma on the last value
                    if ( $i+1 != count($rawrates) ) {
                        $insert.= ', ';
                    }

                    $i++;
                }

                $insert.= 'ON DUPLICATE KEY UPDATE properties_id=VALUES(properties_id), amount=VALUES(amount), rateplan_name=VALUES(rateplan_name),  startdate=VALUES(startdate), enddate=VALUES(enddate),  date_created=VALUES(date_created), lastupdated_cron = "' . date('Y-m-d H:i:s') . '"';

                // Delete all property info before pulling
                //$db->query('DELETE FROM ' . $table . ' WHERE properties_id= "' . $property['id'] . '"');

                $inserted = $db->query($insert);

                $logger->log('Inserted rates into `' . $table . '` for property ' . $property['unit_number']);

            }

            $rawnights = $oPropertyDesc->getrawnights();

            if ( !empty($rawnights) ) {
                $table = 'v12_minnights';
                $insert = 'INSERT INTO ' . $table . ' ( properties_id,  startdate, enddate, minnights, stayincrement, date_created ) VALUES ';
                $i = 0;
                foreach ($rawnights as $night) {

                    $insert.= ' ( ';
                    $insert.= '  ' . $property['id'] . ', ';

                    $startdate = new DateTime($night['dtBeginDate']);
                    $enddate = new DateTime($night['dtEndDate']);

                    $insert.= '"' . $startdate->format('Y-m-d H:i:s') . '",';
                    $insert.= '"' . $enddate->format('Y-m-d H:i:s') . '",';
                    $insert.= '"' . $night['intMinNights']. '",';
                    $insert.= '"' . $night['intStayIncrement']. '",';
                    $insert.= '"' . date('Y-m-d H:i:s') . '" ';
                    $insert.= ' ) ';

                    // Dont put a comma on the last value
                    if ( $i+1 != count($rawnights) ) {
                        $insert.= ', ';
                    }

                    $i++;
                }

                $insert.= 'ON DUPLICATE KEY UPDATE properties_id=VALUES(properties_id), startdate=VALUES(startdate), enddate=VALUES(enddate), minnights=VALUES(minnights), stayincrement=VALUES(stayincrement),  date_created=VALUES(date_created), lastupdated_cron = "' . date('Y-m-d H:i:s') . '"';

                $inserted = $db->query($insert);

                $logger->log('Inserted rates into `' . $table . '` for property ' . $property['unit_number']);

            }

            $rates = $oPropertyDesc->getrates();
            $rateplan_ids = $oPropertyDesc->getrateplanids();

            $dailymin='null';
            $weeklymin='null';

            // Do the inserting into rates 
            // and the updating of rates availability
            if ( !empty($rates) && !empty($rateplan_ids) ) {

                $db->query('START TRANSACTION');

                $insert = 'INSERT INTO rates ( properties_id, thedate, amount, rateplans_id, available, minstay, maxstay, noarrival, nodeparture, last_updated ) VALUES ';

                $i = 0;

                $dates = array();
                foreach ($rates as $rate ) {
                    $dates[] = $rate['thedate'];
                }

                $availability = array();

                foreach ($rates as $rate ) {

                    $rateplanid = $rate['rateplans_id'];

                    if($rateplanid==1) //1 is the non promo daily rate
                    {
                        if(!is_numeric($dailymin)||$dailymin>$rate['amount'])
                            $dailymin=$rate['amount'];
                    }
                    elseif($rateplanid==2) //2 is the non promo weekly rate
                    {
                        if(!is_numeric($weeklymin)||$weeklymin>$rate['amount'])
                            $weeklymin=$rate['amount'];
                    }

                    $rates_updated++;
                    $available = isset($availability[$rate['thedate']]) ? $availability[$rate['thedate']] : 1;

                    $insert.= ' ( ';
                    $insert.= '  ' . $property['id'] . ', ';
                    $insert.= '"' . $rate['thedate'] . '",';
                    $insert.= '"' . $rate['amount'] . '",';
                    $insert.= '' . $rate['rateplans_id'] . ',';
                    $insert.= '' . $available. ',';

                    $minstay = Util::getMinDays($rate['rateplans_id']);
                    // minstay
                    $insert.= '"' . $minstay. '",';

                    $maxstay = Util::getMaxDays($rate['rateplans_id']);
                    // maxstay
                    $insert.= '"' . $maxstay. '",';
                    
                    $insert.='"'.$rate['noarrival'].'","'.$rate['nodeparture'].'",';

                    $insert.= '"' . date('Y-m-d H:i:s') . '" ';
                    $insert.= ' ) ';

                    // Dont put a comma on the last value
                    if ( $i+1 != count($rates) ) {
                        $insert.= ', ';
                    }

                    $i++;
                }

                $sql=' delete from rates where properties_id='.$property['id'].' and rateplans_id in ('.implode(',',$rateplan_ids).')';
                $deleted = $db->query($sql);

                $logger->log('Deleted ' . $deleted . ' rows from rates for ' . $property['unit_number'], 'id=' . $property['id']);

                $inserted = $db->query($insert);

                echo 'really inserted?' . PHP_EOL;
                var_dump($inserted);

                $logger->log( $insert );

                // Mark this as run - any further availability checks will be done via partial cron
                Rates::setRateRun($db, date('Y-m-d H:i:s'), $property['id']);

                if ( $cutoffdate ) {
                    Rates::setCutoffDate($db, $cutoffdate, $property['id']);
                }

                if ( $dailymin > 0 ) {
                    // Mark this as run - any further availability checks will be done via partial cron
                    $db->query($sql='UPDATE properties SET dailyminrate="' . $dailymin. '" WHERE id="' . $property['id'] . '"');
                    echo $sql . PHP_EOL;
                }

                if ( $weeklymin > 0 ) {
                    // Mark this as run - any further availability checks will be done via partial cron
                    $db->query($sql='UPDATE properties SET weeklyminrate="' . $weeklymin. '" WHERE id="' . $property['id'] . '"');
                    echo $sql . PHP_EOL;
                }

                $logger->log('Finished inserting rates into `rates` for ' . $property['unit_number']);

                $non_available_dates = $oAvailability->getnonavailabledates();

                if ( !empty ( $non_available_dates ) ) {

                    $dates = array();

                    foreach ($non_available_dates as $date ) {
                        $dates[] = $date['date'];

                    }

                    $dates_string = implode('","', $dates);

                    $updateSQL = 'UPDATE `rates` SET available=0 WHERE thedate IN ("' . $dates_string . '") AND properties_id=' . $property['id'];
                    $logger->log($updateSQL);
                    #$logger->log('Updated rates for ' . $property['unit_number'] . ' and set available=0 for "' . $dates_string. '"');
                    $updated = $db->query($updateSQL);

                    // Mark this as run - any further availability checks will be done via partial cron
                    $db->query('UPDATE properties SET avail_run="' . date('Y-m-d H:i:s') . '" WHERE id="' . $property['id'] . '"');

                    $avail_updated++;

                }

                if ( is_array($rawnights) ) {

                    foreach ($rawnights as $rawnight) {
                        $startdate = new DateTime($rawnight['dtBeginDate']);
                        $enddate = new DateTime($rawnight['dtEndDate']);

                        $start = $startdate->format('Y-m-d H:i:s');
                        $end = $enddate->format('Y-m-d H:i:s');
                        $minstay = $rawnight['intMinNights'];
                        $stayincrement = $rawnight['intStayIncrement'];

                        // update daily rates with new min
                        $daily='UPDATE rates SET minstay=' . $minstay . ' WHERE thedate>="' . $start . '" AND thedate<="' . $end . '" AND rateplans_id=' . RATEPLAN_ID_DAILY . ' AND properties_id = ' . $property['id'];

                        $logger->log($daily);

                        $db->query($daily);

                        $logger->log('UPDATED minstay to ' . $minstay . ' for daily rates');

                        $default_weekly_min = Util::getMinDays(RATEPLAN_ID_WEEKLY);

                        // set to the default
                        $newmin = $default_weekly_min;

                        if ( $minstay > $default_weekly_min ) {
                            $newmin = $minstay;
                        } 

                        // update weekly rates if the current min stay (7) is less than the new min stay
                        $sql='UPDATE rates SET minstay = ' . $newmin . ' WHERE thedate>="' . $start . '" AND thedate<="' . $end . '" AND rateplans_id=' . RATEPLAN_ID_WEEKLY . ' AND properties_id = ' . $property['id'];

                        echo $sql;
                        $db->query($sql);

                        $logger->log('UPDATED minstay to ' . $minstay . ' for weekly rates');

                    }
                }

                $db->query('COMMIT');
                echo 'committed!';
            }



        } catch ( Exception $e ) {

            // Skip if property doesnt exist
            if ( $e->getMessage() == 'Error:Property does not exist in ISILink' ) {
                $logger->log( 'Property does not exist in ISILink. Skipping ' . $property['unit_number'] );
            } else { 

                // If the exception is being thrown from DB Class
                if ( $e->getCode() == Exceptions::QueryError ) {
                    $db_error = $db->error;

                    // We need to reset the error because query() only calls mysql_query if there's no error
                    $db->error = false;

                    // Rollback the current transaction
                    $db->query('ROLLBACK');

                    #echo $e->getMessage();
                    #exit;
                }
            }

            $logger->log($e->getMessage());
            $logger->logtofile( print_r($service->getdebuginfo(), true), 'end');
            $owserror[]=$e->getMessage();

        } 

    }

    // Run this regardless if any pulled or not, because otherwise newly inserted promo codes wont have minrates
    $rateObj->generateminrates();

    // Set the cron as finished
    $pull->end();

    $logger->log('Script finished. Avail updated: ' . $avail_updated. '. Rates updated: ' . $rates_updated, 'end');

    /// send email if owserror has values
    $emailaddresses = array('meder.omuraliev@sabre.com');
    $developeremail = 'noreply@sabre.com';

    $fromname = 'Palmetto Dunes System';
    $subject = 'PD V12: Avail/Rate Full Import';
    if (count($owserror) > 1)
    {
            #$message = implode("\n", $owserror);
            $message = implode("\n", $logger->getLogs());
            echo $subject . "\n" . $message."\n";
            if (is_array($emailaddresses) && !empty($emailaddresses)) {
                    $mail = new Mailer(array_shift($emailaddresses), $developeremail, $fromname, $subject, $message, FALSE, FALSE);
                    foreach ($emailaddresses as $emailaddress)
                    {
                            $mail->AddAddress($emailaddress);
                    }
                    $mail->sendMail();
            }
            else
            {
                    echo "\nNO EMAIL ADDRESS SET FOR ABORTED PROPERTY RATE CACHING\n";
            }
    }

    try {
        $rates = new Rates;
        //$rates->setSingleProperty(647);
        $rates->generatepropertyrates();

        $rates->generateminrates();
    } catch ( Exception $e ) {
        echo $e->getMessage();
    }
    
    echo 'Total runtime: '.(date('U')-$runstart)."\n";
