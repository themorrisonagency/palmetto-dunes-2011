<?php

 /***
                sql to be run to fix a table before this will work.  can be removed after everything is live.
                
                create table tempv12resdelete;
				SELECT properties_id, confnum, min(date_created) as date_created FROM 
				palmettodunes.v12_nonavaildates group by properties_id, confnum having count(*)>1;
				
				alter table v12_nonavaildates
				add deletethis tinyint default '0';
				
				update v12_nonavaildates v 
				inner join tempv12resdelete t on 
				(v.properties_id=t.properties_id
				and v.confnum=t.confnum
				and v.date_created=t.date_created)
				set deletethis=1;
				
				delete from v12_nonavaildates where deletethis=1;
				
				alter table v12_nonavaildates
				drop primary key;
				
				alter table v12_nonavaildates
				add primary key (properties_id, confnum);
				
				alter table v12_nonavaildates
				drop column deletethis;
				
				drop table tempv12resdelete;
				
				*/
				

/*
 * This cron should be called 1-4x an hour to check for availability changes
 * Interval: 1-4x an hour
 *
 * Step 1. Call getChangeLogInfo and get all the changes
 * Step 2. Insert all changes into `v12_changeloginfo`
 * Step 2. Parse changes and filter for changes with a type of "AVAILABILITY"
 * Step 3. Call getReservationChangeLog for that respective update's property 
 * Step 4. Parse the changes and set availability
 */

    $root = dirname(dirname(dirname(__FILE__)));

    $runstart=date('U');
    $starttime = new DateTime;

    $single = false;

    // Single property for testing
    if ( isset( $argv[1]) ) {
        $single = $argv[1];
    }

    require_once('esite/config/conf.php');
    require_once 'esite/classes/class.phpmailer.php';
    require_once('esite/classes/Mailer.Class.php');
    require_once( $root . '/conf/conf.php');
    require_once( $root . '/conf/v12_load.php');

    use weblink\Weblink;
    use weblink\Logger;

    $service = new Weblink;
    $owserror=array();
    $pull = new Pull( SYNCID_AVAIL_PARTIAL );

    $log_options = array(

        'dir' => LOG_DIR . '/availability/partial/',
        'output' => 'log',

    );

    $logger = new Logger($log_options);

    $logger->log('Starting availability partial run', 'start');

    /*
    if ( Pull::isRunning( SYNCID_AVAIL_PARTIAL )) {
        $logger->log('This script is already running, exiting.', 'end');
        exit;
    }
     */

    $db = new Db;
    $db->throwExceptionsOnError = true;

    $type = 'AVAILABILITY';

    // Find the number of minutes since the last run (using mysql stored time)
    $minutes_since = $pull->getLastPull()+2;
    //convoluted way to get actual last pull date because this isn't being run in a time bubble.
    $lastpulldate=new \DateTime;
    $span=new \DateInterval('PT'.$minutes_since.'M');
    $span->invert=1;
    $lastpulldate->add($span);
    $pull->start();

    //$minutes_since = 3860;

    //$minutes_since = 5000;

    $logger->log($minutes_since . ' minutes since last run. Running' );

    try {

        $changeLog = $service->fetchchangeloginfo($type, $minutes_since);

        $logger->log( print_r($service->getdebuginfo(), true), 'end');


    } catch ( Exception $e ) {

        $pull->end();

        $logger->log($e->getMessage());
        $logger->log( print_r($service->getdebuginfo(), true), 'end');
        $owserror[]=$e->getMessage();

    }

    $updates = $changeLog->getupdates($type);

    // If none have updated availability, exit
    if ( !isset($updates) || !$updates || empty($updates) ) {
        $pull->end();
        $logger->log('No updates');
        exit;
    }

    $all_errors = array();

    $table = 'v12_changeloginfo';
    $insert = 'insert into ' . $table . ' ( properties_id, startdate, enddate, changedate, changelogtype, date_created ) values ';

    $i = 0;
    $count = count($updates);
    foreach ($updates as $row ) {

        $property_id = Property::getInternalPropertyId($row['strPropId']);

        if ( $single && $row['strPropId'] != $single ) {
            continue;
        }

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

    // For single tests, remove the trailing comma
    if ( $i == 1 ) {
        $insert = rtrim($insert, ', ');
    }

    $insert.= ' ON DUPLICATE KEY UPDATE properties_id=VALUES(properties_id), startdate=VALUES(startdate),  enddate=VALUES(enddate), changedate=VALUES(changedate), changelogtype=VALUES(changelogtype), date_created=VALUES(date_created), lastupdated_cron = "' . date('Y-m-d H:i:s') . '"';

    $insert.= ';';

    if ( $i > 0 ) {

        $db->query('START TRANSACTION');

        $rowcount = $db->query($insert);

        $db->query('COMMIT');

        $logger->log('Inserted ' . $rowcount . ' rows into `' . $table . '`');

    }


    foreach ( $updates as $update ) {

        try {

            if ( $single && $update['strPropId'] != $single ) {
                continue;
            }

            $unit_number = $update['strPropId'];

            $logger->log( 'Processing update (for availability) for property ' . $unit_number);

            #if ( $unit_number != 'SA1852' ) {
                #continue;
            #}

            $property_id = Property::getInternalPropertyId($unit_number);

            // If not a valid property id thats stored, skip
            if ( !$property_id ) {
                $logger->log('Invalid property code for ' . $unit_number);
                continue;
            }
            $tempdate=new \DateTime();
            $interval=$lastpulldate->diff($tempdate, true);
            $currentminutes=$interval->days*24*60+$interval->h*60+$interval->i+2;
            $availability = $service->fetchpartialavailability($update['strPropId'], $currentminutes);

            $errors = $service->geterrormessages();


            // Skip if not available
            // But push errors beforehand
            if ( !$availability || (is_array($errors) && !empty($errors)) ) {

                // reset these
                $service->clearerrors();

                $error = $errors[0];

                $all_errors[] = array(
                    'errors' => $errors,
                    'property' => $update['strPropId'],
                );

                var_dump($errors);
                exit;

                #continue;
            }

            // If raw bookings are found, insert them into availability_v12
            $reservations = $availability->getreservationsraw();

            $count = 0;

            $table = 'v12_reschangeloginfo';

            if ( $reservations && count( $reservations) > 0 ) {

                $i = 0;
                $insertbegin = 'insert into ' . $table . ' ( property_id, startdate, enddate, changedate, confnum, statusflag, logid, date_created ) values ';
                $insert='';
                $nonavaildatesinsert='';
                foreach ($reservations as $row ) {

                    $insert.= '("' . $property_id. '", ';
                    $nonavaildatesinsert.='("' . $property_id. '", ';

                    $startdate = new DateTime($row['dtStartdate']);
                    $enddate = new DateTime($row['dtEndDate']);
                    $changedate = new DateTime($row['dtChangedOn']);

                    $insert.= '"' . $startdate->format('Y-m-d H:i:s') . '",';
                    $insert.= '"' . $enddate->format('Y-m-d H:i:s') . '",';
                    $insert.= '"' . $changedate->format('Y-m-d H:i:s') . '",';
                    
                    $nonavaildatesinsert.= '"' . $startdate->format('Y-m-d H:i:s') . '",';
                    $nonavaildatesinsert.= '"' . $enddate->format('Y-m-d H:i:s') . '",';
                    $nonavaildatesinsert.= '"' . $changedate->format('Y-m-d H:i:s') . '",';

                    // Confirmation number
                    if ( isset( $row['intQuoteNum'] ) ) 
                    {
                        $insert.= '"' . $row['intQuoteNum'] . '", ';
                        $nonavaildatesinsert.= '"' . $row['intQuoteNum'] . '", ';
                    } else {
                        $insert.= 'NULL,'; //how would this happen?
                    }
                    #$insert.= '"' . (isset($row['quotenum'])?$row['quotenum']: 'null') . '", ';

                    /*
                     * Possible values: 'X' - Cancelled. Any other value is a valid reservation.
                     * strStatusFlag String
                     * maxlength: 1
                     */
                    if ( isset( $row['strStatusFlag'] ) ) {
                        $insert.= '"' . $row['strStatusFlag'] . '", ';
                        $nonavaildatesinsert.= '"' . $row['strStatusFlag'] . '", ';
                    } else {
                        $insert.= 'NULL,';
                    }

                    if ( isset( $row['intLogID'] ) ) {
                        $insert.= '"' . $row['intLogID'] . '", ';
                    } else {
                        $insert.= 'NULL,';
                    }

                    $insert.= '"' . date('Y-m-d H:i:s') . '" ';
                    $nonavaildatesinsert.= '"' . date('Y-m-d H:i:s') . '" ';

                    $insert.= ')';
                    $nonavaildatesinsert.= ')';

                    // Dont put a comma on the last value
                    if ( $i+1 != count($reservations) ) 
                    {
                        $insert.= ', ';
                        $nonavaildatesinsert.= ', ';
                    }

                    $i++;
                }

                $insertend= ' ON DUPLICATE KEY UPDATE property_id=VALUES(property_id), startdate=VALUES(startdate),  enddate=VALUES(enddate), changedate=VALUES(changedate), confnum=VALUES(confnum), statusflag=VALUES(statusflag), logid=VALUES(logid),  lastupdated_cron = "' . date('Y-m-d H:i:s') . '"';

               // $insert.= ';';

                // Delete all property info before pulling
                //$db->query('DELETE FROM ' . $table . '  WHERE property_id = "' . $property_id. '"');

                $inserted = $db->query($insertbegin.$insert.$insertend);
                
                //need to re-do or use alternate insert string
                
                $insertbegin='insert into v12_nonavaildates ( properties_id, startdate, enddate, changedate, confnum, staytype, lastupdated_cron ) values ';
                $insertend= ' ON DUPLICATE KEY UPDATE properties_id=VALUES(properties_id), startdate=VALUES(startdate),  enddate=VALUES(enddate), changedate=VALUES(changedate), 
                	staytype=VALUES(staytype), lastupdated_cron = "' . date('Y-m-d H:i:s') . '"';
                $inserted = $db->query($insertbegin.$nonavaildatesinsert.$insertend);
              /*
                if($property_id==923)
                {
                	echo $insertbegin.$nonavaildatesinsert.$insertend;
                	die();
                }
                */
                
               // $sql='delete from v12_nonavaildates where statusflag=\'x\'';
                //$db->Query($sql);
                
                
                //we should have classes for this stuff but we don't.  This code is replicated in rate-partial.php
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

                if ( $inserted ) {
                    $count++;
                }

                $logger->log('Inserted '. $inserted . ' out of ' .  count($reservations) . ' reservations into ' . $table . '.', 'end');

                /*
                $dates = $availability->getdates();

                if ( count( $dates ) > 0 ) {
                    foreach ( $dates as $date ) {
                        $sql = 'UPDATE rates SET available = ' . $date['available'] . ' WHERE properties_id = ' . $property_id . ' AND thedate="' . $date['date'] . '" ';
                        $updated = $db->query($sql);

                        $logger->log('Updated ' . $updated . ' rows for ' . $unit_number . ' in `rates` for ' . $date['date']);
                    }
                }
                */

            }

            // Mark this as run - any further availability checks will be done via partial cron
            //$db->query('UPDATE properties SET avail_run="' . date('Y-m-d H:i:s') . '" WHERE id="' . $property_id. '"');


        } catch ( Exception $e ) {
        	//print_r($e->getmessage());
        	//die('test');
            $pull->end();
            $logger->log( 'exception thrown');
            $logger->log( print_r($service->getdebuginfo(), true), 'end');
            $logger->log( 'skipping to the next update');
            $owserror[]=$e->getMessage();
        }

    }

    $pull->end();

    $logger->log('Script finished');

    /// send email if owserror has values
    $emailaddresses = array('meder.omuraliev@sabre.com');
    $developeremail = 'noreply@sabre.com';

    $fromname = 'Palmetto Dunes System';
    $subject = 'PD V12: Avail partial';
    if (count($owserror) > 1)
    {
            #$message = implode("\n", $owserror);
            $message = implode("\n", $logger->getLogs());
            #$message = 'This is a test';
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
