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

    require_once('esite/config/conf.php');
    require_once 'esite/classes/class.phpmailer.php';
    require_once('esite/classes/Mailer.Class.php');
    require_once( $root . '/conf/conf.php');

    use weblink\Weblink;
    use weblink\Logger;
    use weblink\Import;

    # Load v12/weblink classes
    require_once $root . '/conf/v12_load.php';
    require_once($root.'/classes/weblink/Import.Class.php');
    require_once($root.'/classes/LocationList.Class.php');
    require_once($root.'/classes/ViewList.Class.php');
    require_once($root.'/classes/LocationareaList.Class.php');
    require_once($root.'/classes/PropertytypeList.Class.php');

    // Reset this temporarily
    //Pull::setFinished( SYNCID_PROPERTY_INITIAL );

    $service = new Weblink;
    $import = new Import;
    $pull = new Pull( SYNCID_PROPERTY_INITIAL );

    $log_options = array(

        'dir' => LOG_DIR . '/availability/initial/',
        'output' => 'log',

    );
   

    $logger = new Logger($log_options);

    $propertyindexes=$service->fetchpropertyindexes();
    $propertyids=$propertyindexes->getpropertyids();

    #$propertyids=array('165MB');
    
    $import->propertyimport('full', $service, $logger, $pull, $propertyids);
    
    echo 'Total runtime: '.(date('U')-$runstart)." seconds. \n";
