<?php

/* 
 * This cron does a call to getChangeLogInfo and checks if there were any PRICING updates
 * Any pricing updates triggers the code to call getPropertyDesc
 *
 */

    ini_set("soap.wsdl_cache_enabled", 0);

    $root = dirname(dirname(dirname(dirname(__FILE__))));

    require_once('esite/config/conf.php');
    require_once( $root . '/conf/conf.php');

    require_once('esite/classes/GenericErrors.class.php');
    require_once('esite/classes/WebServiceCache.class.php');
    require_once('esite/classes/Db.Class.php');
    require_once('esite/classes/Mailer.Class.php');

    # Load v12/weblink classes
    require_once $root . '/conf/v12_load.php';

    use weblink\Weblink;
    $service = new Weblink;

    $unit_number = 'WP2119';
    $unit_number = 'CC7805';
    $unit_number = '19HTH';
    $unit_number = '44MB';
    $unit_number = 'QG658';

    try {
        #$reservationquery = $service->confirmavailability('CC7805', '2016-12-15', 3);
        $reservationquery = $service->confirmavailability($unit_number, date('Y-m-d'), 6);
    } catch ( Exception $e) {
        echo $e->getMessage();
        exit;
    }

    var_dump($service->getdebuginfo());

    #$result = $reservationquery->getresult();

    var_dump($reservationquery);
