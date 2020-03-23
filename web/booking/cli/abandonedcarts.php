<?php
require_once('esite/config/conf.php');

$dir = dirname(dirname(__FILE__)) . '/';
require_once($dir . 'conf/conf.php');

require_once('esite/classes/GenericErrors.class.php');
require_once('esite/classes/Db.Class.php');
require_once('esite/classes/Mailer.Class.php');

require_once $dir.'classes/NavisReach.Class.php';
require_once $dir.'classes/AbandonedBookings.Class.php';

AbandonedBookings::log('-------------------------------BEGIN CRON-------------------------------------');

$db = new Db;

$dt = new DateTime;
$dt->setTimeZone(new DateTimeZone('America/New_York'));

$today = gmdate('Y-m-d H:i:s');


AbandonedBookings::log($dt->format('Y-m-d H:i:s') . ' EST time');
AbandonedBookings::log($today . ' GM time');

$twelve = date('Y-m-d H:i:s', strtotime('-12 hours', strtotime($today)));
$day_before = date('Y-m-d H:i:s', strtotime('-24 hours', strtotime($today)));

$ab = new AbandonedBookings;

$users = $ab->getBookings($today, $day_before, $twelve);

$reach = new NavisReach();

if ( count( $users ) > 0 ) {
    AbandonedBookings::log('found ' . count($users) . ' users');
    #var_dump($users);
    #exit;
    foreach ($users as $user) {

        $response = $reach->addUpdateCRMContact(array(
            'email_address' => $user['abandon_email'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'address' => $user['address'],
            'address_2' => $user['address_2'],
            'city' => $user['city'],
            'state' => $user['state'],
            'postal_code' => $user['postal_code'],
            'phone' => $user['phone'],
            'country' => $user['country'],
        ));

        $contactId = '';
        if ( is_object( $response ) && $response->AddUpdateCRMContactResult != '' ) {
            $contactId = $response->AddUpdateCRMContactResult;
        }

        AbandonedBookings::log('addUpdateCRMContactResult returned with a contactId, ' . $contactId);

        /*
        Custom - Abandoned Property (this is the full name of the property the user abandoned and is a “string” type field)
        Custom - Abandoned Property ID (this is the 3-digit number of the property ID that the user abandoned and is a “number” type field)
        Custom - Abandoned Date (this is the date the abandoned cart was created and is a “date” type field)
        Custom - Abandoned Check in Date (this is the date of check in selected by the user and is a “date” type field)
        Custom - Abandoned Check out Date (this is the date of check out selected by the user and is a “date” type field)
        Custom - Abandoned Promo Code (this is the promo code if used by the user and is a “string” type field)
         */

        $attributes = array(
            'Custom - Abandoned Property' => $user['property_name'],
            'Custom - Abandoned Property ID' => $user['property_id'],
            'Custom - Abandoned Date' => date('Y-m-d H:i:s', strtotime($user['abandon_datecreated'])),
            'Custom - Abandoned Check in Date' => date('Y-m-d', strtotime($user['arrive_date'])),
            'Custom - Abandoned Check out Date' => date('Y-m-d', strtotime($user['depart_date'])),
            'Custom - Abandoned Promo Code' => $user['promocode'] ? $user['promocode'] : 'ISLANDSTAY16',
        );

        if ( $contactId ) {
            $responses = $reach->addUpdateAttributes( $contactId, 'contact', $attributes );
            if ( count( $responses ) > 0 && $user['abandon_id'] ) {
                $dt = new DateTime;
                $dt->setTimeZone(new DateTimeZone('America/New_York'));
                $sql = 'UPDATE abandonedbookings SET cron_pulled ="' . $dt->format('Y-m-d H:i:s') . '" WHERE id = ' . $user['abandon_id'];
                $db = new Db;
                $db->query($sql);
                AbandonedBookings::log('UPDATE called');
            }
        }
    }
}

AbandonedBookings::log('--------------------___END CRON____----------------------------------');
AbandonedBookings::log("\n\n\n");
