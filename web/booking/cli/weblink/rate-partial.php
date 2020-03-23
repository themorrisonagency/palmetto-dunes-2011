<?php

/* 
 * This cron does a call to getChangeLogInfo and checks if there were any PRICING updates
 * Any pricing updates triggers the code to call getPropertyDesc
 *
 */

    $root = dirname(dirname(dirname(__FILE__)));

    $runstart=date('U');

    require_once('esite/config/conf.php');
    require_once( $root . '/conf/conf.php');
    require_once( $root . '/conf/v12_load.php');

    use weblink\Weblink;
    use weblink\Logger;

    $service = new Weblink;
    $owserror=array();

    $log_options = array(

        'dir' => LOG_DIR . '/rate/partial/',
        'log' => true,
        'output' => 'log',

    );

    $logger = new Logger($log_options);
    $pull = new Pull( SYNCID_RATE_PARTIAL );
    $rateObj = new Rates;

    $type = 'PRICING';

    // Find the number of minutes since the last run (using mysql stored time)
    $minutes_since = $pull->getLastPull()+2; //add some time to accommodate run time
    $pull->start();

    $logger->log( $minutes_since . ' minutes since last run. Running' );

    $rates_updated = 0;

    try {
    	
    	$changeLog = $service->fetchchangeloginfo('PROPERTY', $minutes_since);

        $updates = $changeLog->getupdates('PROPERTY');

        $changeLog = $service->fetchchangeloginfo($type, $minutes_since);

        $updates = array_merge($updates,$changeLog->getupdates($type));

    } catch ( Exception $e ) {

        // Empty results are caught here
        
        $pull->end();

        $logger->log($e->getMessage());
        $logger->log( print_r($service->getdebuginfo(), true), 'end');
        $owserror[]=$e->getMessage();

    }

    // If none have updated availability, exit
    if ( !isset($updates) || !$updates || empty($updates) ) {
        $pull->end(true);
        $logger->logtoscreen(print_r($service->getdebuginfo(), true));
        $logger->log('No updates found.');

        // generate min rates anyway
        $rateObj->generateminrates();
        exit;
    }

    $all_errors = array();

    $db = new Db;
    $db->throwExceptionsOnError = true;

    $insert_into_changelog=true;

    // Record all updates into the table

    $i = 0;
    $count = count($updates);
    if ( $insert_into_changelog ) {
        $table = 'v12_changeloginfo';
        $insert = 'insert into ' . $table . ' ( properties_id, startdate, enddate, changedate, changelogtype,  date_created) values ';

        $i = 0;
        foreach ($updates as $row ) {

            $property_id = Property::getInternalPropertyId($row['strPropId']);

            if ( !$property_id ) {
                $logger->log('Unable to get internal property id for ' . $row['strPropId'] . ', skipping insert into ' . $table);
                $count--;
                continue;
            }

            $insert.= '("' . $property_id. '", ';

            $startdate = new DateTime($row['dtStartdate']);
            $enddate = new DateTime($row['dtEndDate']);
            $changedate = new DateTime($row['dtChangedOn']);

            $insert.= '"' . $startdate->format('Y-m-d H:i:s') . '",';
            $insert.= '"' . $enddate->format('Y-m-d H:i:s') . '",';
            $insert.= '"' . $changedate->format('Y-m-d H:i:s') . '",';

            $insert.= '"' . $row['strChangeLog'] . '", ';
            $insert.= '"' . date('Y-m-d H:i:s') . '" ';

            $insert.= ')';

            // Dont put a comma on the last value
            if ( $i+1 != $count) {
                $insert.= ', ';
            }

            $i++;
        }

        $insert.= 'ON DUPLICATE KEY UPDATE properties_id=VALUES(properties_id), startdate=VALUES(startdate),  enddate=VALUES(enddate), changedate=VALUES(changedate), changelogtype=VALUES(changelogtype), lastupdated_cron = "' . date('Y-m-d H:i:s') . '"';

        if ( $i > 0 ) {

            $db->query('START TRANSACTION');

            // Delete all property info before pulling
            //$db->query('DELETE FROM ' . $table . '  WHERE properties_id = "' . $property_id. '" AND changelogtype="Pricing"');

            $rowcount = $db->query($insert);

            $db->query('COMMIT');

            $logger->log('Inserted ' . $rowcount . ' rows into `' . $table . '`');

        }
    }

    foreach ( $updates as $update ) 
    {
    	//if($update['strPropId']!='QG771')
    	//	continue; //this is a hack for debugging.  never use this.  ever.

        try {

            $table = 'v12_rates';

            $unit_number = $update['strPropId'];
            $logger->log( 'Processing update (for availability) for property ' . $unit_number);

            $property_id = Property::getInternalPropertyId($unit_number);

            // If not a valid property id thats stored, skip
            if ( !$property_id ) {
                $logger->log('Not a valid property code ' . $unit_number);
                continue;
            }

            $propertyDesc = $service->fetchpropertydesc($update['strPropId']);

            $rawrates = $propertyDesc->getrawrates();

            $cutoffdate = null;

            if ( $rawrates ) {
                $insert = 'INSERT INTO ' . $table . ' ( properties_id, amount, rateplan_name, startdate, enddate, date_created ) VALUES ';
                $i = 0;
                foreach ($rawrates as $rate) {

                    if ( $cutoffdate === null || strtotime($rate['dtEndDate']) > strtotime($cutoffdate) ) {
                        $cutoffdate = date('Y-m-d', strtotime($rate['dtEndDate']));
                    }

                    $insert.= ' ( ';
                    $insert.= '  ' . $property_id. ', ';
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

                $insert.= 'ON DUPLICATE KEY UPDATE properties_id=VALUES(properties_id), startdate=VALUES(startdate),  enddate=VALUES(enddate), rateplan_name=VALUES(rateplan_name), amount=VALUES(amount), date_created= VALUES(date_created);';

                // Make this a transaction so we don't end up with empty property data if any insertion errors
                $db->query('START TRANSACTION');

                // Delete all property info before pulling
                //$db->query('DELETE FROM ' . $table . ' WHERE properties_id= "' . $property_id. '"');

                $inserted = $db->query($insert);

                $db->query('COMMIT');

                $logger->log('Inserted rates into `' . $table . '` for property ' . $unit_number);
            }

            $rawnights = $propertyDesc->getrawnights();

            if ( !empty($rawnights) ) {
                $table = 'v12_minnights';
                $insert = 'INSERT INTO ' . $table . ' ( properties_id,  startdate, enddate, minnights, stayincrement, date_created ) VALUES ';
                $i = 0;
                foreach ($rawnights as $night) {

                    $insert.= ' ( ';
                    $insert.= '  ' . $property_id. ', ';

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

                $logger->log('Inserted rates into `' . $table . '` for property ' . $unit_number);

            }

            $rates = $propertyDesc->getrates();

            if ( $rates ) {

                $i = 0;

                $dailymin='null';
                $weeklymin='null';

                $dates = array();
                foreach ($rates as $rate ) {
                    $dates[] = $rate['thedate'];
                }

                $availability = array();
                /*
                $query = $db->query($sql='SELECT thedate, available FROM availability WHERE property_id = ' . $property_id. ' AND thedate IN ( "' . implode('","', $dates) . '" )');
                while ($temp=$db->fetchassoc()) {
                    $availability[$temp['thedate']] = $temp['available'];
                }
                 */

                $insert = 'INSERT INTO rates ( properties_id, thedate, amount, rateplans_id, minstay, maxstay, noarrival, nodeparture, last_updated ) VALUES ';
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

                    $row = '';

                    $row.= ' ( ';
                    $row.= '  ' . $property_id. ', ';
                    $row.= '"' . $rate['thedate'] . '",';
                    $row.= '"' . $rate['amount'] . '",';
                    $row.= '"' . $rate['rateplans_id'] . '",';

                    $minstay = Util::getMinDays($rate['rateplans_id']);
                    // minstay
                    $row.= '"' . $minstay. '",';

                    $maxstay = Util::getMaxDays($rate['rateplans_id']);
                    // maxstay
                    $row.= '"' . $maxstay. '",';
                    
                    $row.='"'.$rate['noarrival'].'","'.$rate['nodeparture'].'",';

                    $row.= '"' . date('Y-m-d H:i:s') . '" ';
                    $row.= ' ) ';

                    // Dont put a comma on the last value
                    if ( $i+1 != count($rates) ) {
                        $row.= ', ';
                    }

                    //$logger->log($row);

                    $insert.= $row;

                    $i++;
                }

                $insert.= 'ON DUPLICATE KEY UPDATE properties_id=VALUES(properties_id), amount=VALUES(amount), rateplans_id=VALUES(rateplans_id), 
                   minstay=VALUES(minstay), maxstay=VALUES(maxstay),noarrival=values(noarrival), nodeparture=values(nodeparture), last_updated = VALUES(last_updated);';

                // Make this a transaction so we don't end up with empty property data if any insertion errors
               // $db->query('START TRANSACTION');

                // Update, not delete
                
                // $db->query('DELETE FROM rates WHERE properties_id= "' . $property_id. '"');

                $inserted = $db->query($insert);
                
                //replicated from availaibility-partial.php - update the availability in case we import new rates after the availability was imported.
                $db->Query('start transaction');
                $sql='update rates set available=1 where properties_id='.$property_id;
                $db->Query($sql);
                $sql='update rates r 
                	inner join v12_nonavaildates v on
                		(
                		r.properties_id='.$property_id.'
                		and v.properties_id=r.properties_id 
                		and r.thedate>=v.startdate 
                		and r.thedate<v.enddate
                		and v.staytype!=\'x\')
                	set available=0';
				$db->Query($sql);
                $db->Query('commit');

                //$db->query('COMMIT');

                // Mark this as run - any further availability checks will be done via partial cron
                Rates::setRateRun($db, date('Y-m-d H:i:s'), $property_id);

                if ( $cutoffdate ) {
                    Rates::setCutoffDate($db, $cutoffdate, $property_id);
                }

                if ( $dailymin > 0 ) {
                    // Mark this as run - any further availability checks will be done via partial cron
                    $db->query('UPDATE properties SET dailyminrate="' . $dailymin. '" WHERE id="' . $property_id . '"');
                }

                if ( $weeklymin > 0 ) {
                    // Mark this as run - any further availability checks will be done via partial cron
                    $db->query('UPDATE properties SET weeklyminrate="' . $weeklymin. '" WHERE id="' . $property_id . '"');
                }

                $logger->log('Inserted rates into `rates` for property ' . $unit_number);

                if ( is_array($rawnights) ) {

                    foreach ($rawnights as $rawnight) {
                        $startdate = new DateTime($rawnight['dtBeginDate']);
                        $enddate = new DateTime($rawnight['dtEndDate']);

                        $start = $startdate->format('Y-m-d H:i:s');
                        $end = $enddate->format('Y-m-d H:i:s');
                        $minstay = $rawnight['intMinNights'];
                        $stayincrement = $rawnight['intStayIncrement'];

                        // update daily rates with new min
                        $daily='UPDATE rates SET minstay=' . $minstay . ' WHERE thedate>="' . $start . '" AND thedate<="' . $end . '" AND rateplans_id=' . RATEPLAN_ID_DAILY . ' AND properties_id = ' . $property_id;

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
                        $sql='UPDATE rates SET minstay = ' . $newmin . ' WHERE thedate>="' . $start . '" AND thedate<="' . $end . '" AND rateplans_id=' . RATEPLAN_ID_WEEKLY . ' AND properties_id = ' . $property_id;

                        $logger->log($sql);
                        $db->query($sql);

                        $logger->log('UPDATED minstay to ' . $minstay . ' for weekly rates');

                    }
                }
            }

        } catch ( Exception $e ) {
            $pull->end( true );
            $logger->log( 'exception thrown');
            $logger->log( print_r($service->getdebuginfo(), true), 'end');
            $logger->log( 'skipping to the next update');
            $owserror[]=$e->getMessage();
        }

    }

    // Run this regardless if any pulled or not, because otherwise newly inserted promo codes wont have minrates
    $rateObj->generateminrates();

    $pull->end();

    $logger->log('Script finished.  Rates updated: ' . $rates_updated , 'end');

    /// send email if owserror has values
    $emailaddresses = array('meder.omuraliev@sabre.com');
    $developeremail = 'noreply@sabre.com';

    $fromname = 'Palmetto Dunes System';
    $subject = 'PD V12: Rate Partial Import';
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
    
    echo 'Total runtime: '.(date('U')-$runstart)."\n";
