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

    $service = new Weblink;

    $log_options = array(

        'dir' => LOG_DIR . '/availability/initial/',
        'output' => 'screen',

    );

    $logger = new Logger($log_options);

    $unit_number = 'CC7805';
    $startdate = new \DateTime('2016-12-18');
    $enddate = new \DateTime('2016-12-23');
    $number_nights = Util::getNumberOfNightsBetween( $startdate->format('Y-m-d'), $enddate->format('Y-m-d'));
    
    try {

        $reservationquery = $service->confirmavailability($unit_number, $startdate->format('Y-m-d'), $number_nights); 

        $errors = $service->geterrormessages();

/* 
 *
 * This is necessary
    // string(409) "<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><soap:Fault><faultcode>soap:Client</faultcode><faultstring>Error:Property is not available for the requested dates in ISILink.</faultstring><detail /></soap:Fault></soap:Body></soap:Envelope>"
*/

        if ( !$reservationquery || (is_array($errors) && !empty($errors)) ) {

            $error = $errors[0];
            throw new Exception($error);
        }


    } catch (Exception $e) {
        // Examples
        // "Error:Invalid Checkin Date.Should book property 1 days in advance."
        // "Error:Property is not available for the requested dates in ISILink."
        echo 'Exception 1' . "\n";
        echo $e->getCode();
        echo $e->getMessage();
        exit;
    }

    //$avail = $reservationquery->getrawresult();
    $avail = $reservationquery->getresult();

    if ( !$avail ) {
        echo 'Could not validate price/availabilify';
        exit;
    }

    $data = array(
        'startdate' => $startdate->format('Y-m-d'),
        'unit_number' => $unit_number,
        'accept_csa' => $avail['hascsa'],
        'number_nights' => $number_nights,
        'forced_rent' => 0,
        'cleaning' => false,
        'adults' => 1,
        'children' => 0,
    );

    $guestinfo = array(
        'first_name' => 'Sabre',
        'last_name' => 'Tester',
        'email' => 'meder.omuraliev@sabre.com',
        'middle_initial' => 'M',
        'address1' => '4035 Cordell Ave',
        'address2' => 'E',
        'province' => '',
        'city' => 'Bethesda',
        'state' => 'MD',
        'country' => 'USA',
        'zip' => '20814',
        'phone_work' => '12223334444',
        'phone_home' => '12223334444',
    );

    $_REQUEST['cardexpmonth'] = '03';
    $_REQUEST['cardexpyear'] = '2017';

    $billing = array(
        'strToken' => '2039232ABC',
        'exp_month' => $_REQUEST['cardexpmonth'],
        'exp_year' => $_REQUEST['cardexpyear'],
    );

    try {

        $booking = $service->createbooking($data, $avail, $guestinfo, $billing);
        if ( !$booking ) {
            $errors = $service->geterrormessages();
            var_dump($errors);
            exit;
        }

    } catch ( Exception $e ) {
        echo 'Exception 2' . "\n";
        echo $e->getMessage();
        exit;
    }

    var_dump($booking);
    var_dump($booking->isSuccessful());
    var_dump($booking->getvalue('confirmation'));
