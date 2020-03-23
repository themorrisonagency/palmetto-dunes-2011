<?php

/*
 *
 * This cron grabs all properties that haven't had a rate script run
 * and calls the getPropertyDesc method to grab all rate info per each
 * in order to grab the rate info. 
 *
 * Step 1. Get properties who have not had rate_run set
 * Step 2. Fetch getPropertyDesc for respective property
 *
 * Raw rates inserted into `v12_rates`
 * Update `rates` with the rate
 */

    $root = dirname(dirname(dirname(__FILE__)));

    require_once('esite/config/conf.php');
    require_once( $root . '/conf/conf.php');
    require_once( $root . '/conf/v12_load.php');

    use weblink\Weblink;
    use weblink\Logger;

    Pull::setFinished( SYNCID_RATE_INITIAL );

    $service = new Weblink;

    $log_options = array(

        'dir' => LOG_DIR . '/rate/initial/',
        'output' => 'screen',

    );

    $logger = new Logger($log_options);

    $startdate = new DateTime;
    $enddate = new DateTime;

    // Dont run if already running
    if ( Pull::isRunning( SYNCID_RATE_INITIAL )) {
        $logger->log('This script is already running, exiting.', 'end');
        exit;
    }

    Pull::setRunning( SYNCID_RATE_INITIAL );

    // window of availability lookup
    $availabilityWindow = new DateInterval('P90D');

    $enddate->add($availabilityWindow);

    $filters = array(
        'rate_run' => NULL,
        #'unit_number' => 'CC7805',
        #'unit_number' => 'WP2119',
    );

    $properties = Property::getProperties($filters);

    $properties_updated = 0;

    if ( count( $properties ) == 0 ) {
        $logger->log('No properties found. Exiting');
        Pull::setFinished( SYNCID_RATE_INITIAL );
        exit;
    }

    $db = new Db;

    $properties_inserted = array();

    foreach ($properties as $property) {

        $logger->log( 'Processing rates for property ' . $property['unit_number'], 'end' );

        try {

            $table = 'v12_rates';

            // startdate & enddate are optional
            $oPropertyDesc = $service->fetchpropertydesc($property['unit_number']);

            #if ( $property['unit_number'] != 'WC3504' ) {
                #var_dump($service->getdebuginfo());
                #exit;
            #}
            
            $logger->log( print_r($service->getdebuginfo(), true), 'end');

            if ( !$oPropertyDesc ) {
                $logger->log('Could not create instance, skipping property');
                continue;
            }

            $rawrates = $oPropertyDesc->getrawrates();

            #var_dump($rawrates);
            #exit;

            if ( $rawrates ) {
                $insert = 'INSERT INTO ' . $table . ' ( properties_id, amount, rateplan_name, startdate, enddate, date_created ) VALUES ';
                $i = 0;
                foreach ($rawrates as $rate) {
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

                $insert.= 'ON DUPLICATE KEY UPDATE properties_id=VALUES(properties_id), rateplan_name=VALUES(rateplan_name),  startdate=VALUES(startdate), enddate=VALUES(enddate),  date_created=VALUES(date_created), lastupdated_cron = "' . date('Y-m-d H:i:s') . '"';

                // Make this a transaction so we don't end up with empty property data if any insertion errors
                $db->query('START TRANSACTION');

                // Delete all property info before pulling
                //$db->query('DELETE FROM ' . $table . ' WHERE properties_id= "' . $property['id'] . '"');

                $inserted = $db->query($insert);

                $db->query('COMMIT');

                $logger->log('Inserted rates into `' . $table . '` for property ' . $property['unit_number']);
            }

            $rates = $oPropertyDesc->getrates();

            if ( $rates ) {
                $insert = 'INSERT INTO rates ( properties_id, thedate, amount, rateplans_id, available, minstay, maxstay, last_updated ) VALUES ';

                $i = 0;

                $dates = array();
                foreach ($rates as $rate ) {
                    $dates[] = $rate['thedate'];
                }

                $availability = array();

                foreach ($rates as $rate ) {
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

                    $insert.= '"' . date('Y-m-d H:i:s') . '" ';
                    $insert.= ' ) ';

                    // Dont put a comma on the last value
                    if ( $i+1 != count($rates) ) {
                        $insert.= ', ';
                    }

                    $i++;
                }

                // Make this a transaction so we don't end up with empty property data if any insertion errors
                $db->query('START TRANSACTION');

                // Delete all property info before pulling
                //$db->query('DELETE FROM rates WHERE properties_id= "' . $property['id'] . '"');

                $inserted = $db->query($insert);

                $db->query('COMMIT');

                // Mark this as run - any further availability checks will be done via partial cron
                $db->query('UPDATE properties SET rate_run="' . date('Y-m-d H:i:s') . '" WHERE id="' . $property['id'] . '"');

                $properties_updated++;

                $logger->log('Finished inserting rates into `rates` for ' . $property['unit_number']);
            }

        } catch ( Exception $e ) {
            $logger->log('Exception: ' . $e->getMessage());
            $logger->log( print_r($service->getdebuginfo(), true), 'end');
        }
    }

    // Set the cron as finished
    Pull::setFinished( SYNCID_RATE_INITIAL );

    $logger->log('Script finished. Updated ' . $properties_updated . ' out of ' . count($properties) . ' properties.');
