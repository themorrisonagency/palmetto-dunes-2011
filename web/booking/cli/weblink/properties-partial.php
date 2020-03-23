<?php

/*
 * This cron updates properties which have been updated recently
 */

    $root = dirname(dirname(dirname(__FILE__)));

    $runstart=date('U');

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
    use weblink\Import;

    # Load v12/weblink classes
    require_once $root . '/conf/v12_load.php';
    require_once($root.'/classes/LocationList.Class.php');
    require_once($root.'/classes/ViewList.Class.php');
    require_once($root.'/classes/LocationareaList.Class.php');
    require_once($root.'/classes/PropertytypeList.Class.php');
    require_once($root.'/classes/weblink/Import.Class.php');

    $service = new Weblink;
    $import = new Import;
    $pull = new Pull( SYNCID_PROPERTY_PARTIAL );
    $owserror=array();

    $type = 'PROPERTY';


    $log_options = array(

        'dir' => LOG_DIR . '/property/partial/',
        'output' => 'log',

    );
   

    $logger = new Logger($log_options);

    // Find the number of minutes since the last run (using mysql stored time)
    $minutes_since = $pull->getLastPull()+2;
    $pull->start();

    $logger->log( $minutes_since . ' minutes since last run. Running' );

    try {

        $changeLog = $service->fetchchangeloginfo($type, $minutes_since);

        $updates = $changeLog->getupdates($type);

        $logger->log('Updates:');

        $logger->log( print_r($service->getdebuginfo(), true));

    } catch ( Exception $e ) {

        // Empty results are caught here
        
        $pull->end();

        $logger->log($e->getMessage());
        $logger->log( print_r($service->getdebuginfo(), true), 'end');
        $owserror[]=$e->getMessage();

    }

    // If none have updated property, exit
    if ( !isset($updates) || !$updates || empty($updates) ) {
        $pull->end(true);
        $logger->logtoscreen(print_r($service->getdebuginfo(), true));
        $logger->log('No updates found.');
        exit;
    }

    $propertyindexes=$service->fetchpropertyindexes();

    // get all property ids
    foreach ($updates as $row ) {
        $propertyids[] = $row['strPropId'];
    }

    $logger->log('Property ids to update:'. PHP_EOL);

    $logger->log( print_r($propertyids, true) );

    if ( $single ) {
        $propertyids = array($single);
    }
    
    try {
        $import->propertyimport('partial', $service, $logger, $pull, $propertyids, $owserror);
    } catch ( Exception $e ) {
        $logger->log( $e->getMessage() . PHP_EOL );
    }
    
    echo 'Total runtime: '.(date('U')-$runstart)." seconds.\n";
