<?php

class AbandonedBookings {

    protected $db;

    public function __construct() {
        $this->db = new Db;
    }

    public function getTestBookings() {
        $sql = '

        SELECT

        ab.firstname AS first_name,
        ab.lastname AS last_name,
        ab.email AS abandon_email,
        ab.datecreated AS abandon_datecreated,
        b.datecreated AS booking_datecreated,

        b.address1 AS address,
        b.address2 AS address_2,
        b.city,
        b.state,
        b.zipcode AS postal_code,
        ab.phone,
        b.country,
        p.property_name AS property_name,
        p.id AS property_id,
        ab.arrive AS arrive_date,
        ab.depart AS depart_date,
        ab.promocode

        FROM abandonedbookings ab

        LEFT JOIN bookings b on ( b.email = ab.email )
        LEFT JOIN properties p on ( p.id = ab.properties_id )

        WHERE ab.email="meder.omuraliev@sabre.com"

        LIMIT 1
        ';

        $this->db->query($sql);

        $users = array();
        while ( $row = $this->db->fetchAssoc() ) {
            $users[] = $row;
        }

        return $users;
    }

    public function getBookings($today, $day_before, $twelve) {

        $sql = '

        SELECT

        ab.id AS abandon_id,
        ab.firstname AS first_name,
        ab.lastname AS last_name,
        ab.email AS abandon_email,
        ab.datecreated AS abandon_datecreated,
        b.datecreated AS booking_datecreated,

        b.address1 AS address,
        b.address2 AS address_2,
        b.city,
        b.state,
        b.zipcode AS postal_code,
        ab.phone,
        b.country,
        p.property_name AS property_name,
        p.id AS property_id,
        ab.arrive AS arrive_date,
        ab.depart AS depart_date,
        ab.promocode

        FROM abandonedbookings ab

        LEFT JOIN bookings b on ( b.email = ab.email AND b.datecreated <= "' . $twelve . '" AND b.datecreated > "' . $day_before . '"  )
        LEFT JOIN properties p on ( p.id = ab.properties_id )

        WHERE 

        ab.datecreated > "' . $day_before . '" AND ab.datecreated <= "' . $today . '"
        AND ab.cron_pulled IS NULL

        AND b.datecreated IS NULL';
        
        self::log($sql);

        $this->db->query($sql);

        $users = array();
        while ( $row = $this->db->fetchAssoc() ) {
            $users[] = $row;
        }

        return $users;
    }

    public static function log($message) {
        $file = '/var/www/html/palmettodunes_booking/logs/cron/cron.log';
        if ( file_exists($file) && is_writable($file) ) {
            file_put_contents($file, $message . "\n", FILE_APPEND );
        }
    }
}
