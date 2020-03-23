<?php

/*
 * This cron is to generate property rates 
 * Inserts into property_rates from v12_rates
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

    $pull = new Pull( SYNCID_PROPERTYRATES );

    try {
        $rates = new Rates;
        //$rates->setSingleProperty(647);
        $rates->generatepropertyrates();

        $rates->generateminrates();
    } catch ( Exception $e ) {
        echo $e->getMessage();
    }

    $pull->end();
