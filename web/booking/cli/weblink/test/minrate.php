<?php

/*
 *
 * This cron attempts to create a booking
 *
 */

    $root = dirname(dirname(dirname(dirname(__FILE__))));

    require_once('esite/config/conf.php');
    require_once( $root . '/conf/conf.php');

    use weblink\Weblink;
    use weblink\Logger;
    use weblink\ReservationQueryResponse;
    use weblink\BookingResponse;

    # Load v12/weblink classes
    require_once $root . '/conf/v12_load.php';

    $rateObj = new Rates;
    $rateObj->generateminrates();
