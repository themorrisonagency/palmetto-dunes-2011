<?php

/* 
 * Class to house rate related functionality
 */

class Rates {

    protected $db;
    protected $singleproperty;

    public function setSingleProperty($id) {
        $this->singleproperty = $id;
    }

    public function __construct() {
        $this->db = new \Db;
        $this->db->throwExceptionsOnError = true;
    }

    /*
     * Get (available) rates for a given rateplan
     * @param int $rateplan_id
     * @return array
     */
    private function getrates($rateplan_id) {

        switch ($rateplan_id ) {
            case RATEPLAN_ID_DAILY:
                $field = 'dailyminrate';
                $promofield = 'discountamount_daily';
                $updatefield = 'dailyminrate=values(dailyminrate)';
                break;
            case RATEPLAN_ID_WEEKLY:
                $field = 'weeklyminrate';
                $promofield = 'discountamount_weekly';
                $updatefield = 'weeklyminrate=values(weeklyminrate)';
                break;
            case RATEPLAN_ID_MONTHLY:
                $field = 'monthlyminrate';
                $promofield = 'discountamount_monthly';
                $updatefield = 'monthlyminrate=values(monthlyminrate)';
                break;
        }

        $sql = '
                select r.properties_id as properties_id, min(amount) as ' . $field . '
                from rates r
                inner join rateplans rp on
                    (rp.id=r.rateplans_id
                    and r.rateplans_id = ' . $rateplan_id. '
';

        switch ($rateplan_id ) {
            case 1:
                $sql.= ' and r.maxstay<7 ';
            break;

            case 2:
                $sql.= ' and r.maxstay>= 7 ';
            break;
        }
                    

        if ( $this->singleproperty ) {
            $sql.= ' and r.properties_id = ' . $this->singleproperty . ' ';
        }

        $sql.=  ' and r.available=1)
            group by properties_id
';

        $this->db->query($sql);

        $dailyrates = array();
        while ($temp = $this->db->fetchassoc()) {
            $dailyrates[] = $temp;
        }

        return $dailyrates;
    }

    /*
     * Insert rates
     * @param array $rates
     * @param array $promos
     * @param int $rateplan_id
     * @return void
     */
    private function insertminrates($rates, $promos, $rateplan_id) {

        $inserts = array();
        $updatefield = '';

        switch ($rateplan_id ) {
            case RATEPLAN_ID_DAILY:
                $field = 'dailyminrate';
                $promofield = 'discountamount_daily';
                $updatefield = 'dailyminrate=values(dailyminrate)';
                $datefield = 'dailydate';
                break;
            case RATEPLAN_ID_WEEKLY:
                $field = 'weeklyminrate';
                $promofield = 'discountamount_weekly';
                $updatefield = 'weeklyminrate=values(weeklyminrate)';
                $datefield = 'weeklydate';
                break;
            case RATEPLAN_ID_MONTHLY:
                $field = 'monthlyminrate';
                $promofield = 'discountamount_monthly';
                $updatefield = 'monthlyminrate=values(monthlyminrate)';
                $datefield = 'monthlydate';
                break;
        }

        foreach ($promos as $promo ) {

            $properties = explode(',', $promo['properties']);

            if ( $promo[$promofield] != '' ) {

                foreach ($rates as $rate) {
                
                    // if the property was selected for the respective promo only
                    // prevents useless rows in minrates
                    if ( in_array( $rate['properties_id'], $properties ) ) {
                        $promorate = Util::calculatepromo( $rate[$field], $promo[$promofield] );

                        $inserts[] = array(
                            'properties_id' => $rate['properties_id'],
                            'promotions_id' => $promo['id'],
                            $field => $promorate,
                        );
                    }
                }
            }
        }

        $onduplicate = 'on duplicate key update '.$field.'=values(' .$field .')';

        $inserted = $this->db->autoInsertMultiple('minrates', $inserts, $onduplicate);

        // update the date
        $sql = '
                select p.id, p.' . $field . ', 
                min( r.thedate) AS thedate 
                from properties p
                inner join rates r on (
                    p.id=r.properties_id and 
                    p.' . $field . '=r.amount and 
                    r.available=1 and 
        ';

        if ( $this->singleproperty ) {
            $sql.= ' r.properties_id = ' . $this->singleproperty . ' and ';
        }

        $sql.= ' r.rateplans_id= ' . $rateplan_id. ')';


        $this->db->query($sql);
        $temp = $this->db->fetchassoc();

        $earliest_date = $temp['thedate'];

        if ( $earliest_date != '' ) {
            $update = 'UPDATE minrates SET ' . $datefield . '="' . $earliest_date . '"';
            $this->db->query($update);
        }
    }

    /*
     * Generate property rates from the rates in v12
     * @return void
     */
    public function generatepropertyrates() {

        // making this static per http://skynet.esiteportal.com/tasks.php?op=view&data[id]=478491
        exit;

        try {
            $this->db->query('START TRANSACTION');

            $delete = 'DELETE FROM property_rates';

            if ( $this->singleproperty ) {
                $delete.= ' where property= ' . $this->singleproperty . ' ';
            }

            $this->db->query($delete);

            $minNightsQuery =
                'SELECT
                properties_id, startdate, enddate, minnights, stayincrement
                FROM v12_minnights
            ';

            $this->db->query($minNightsQuery);
            $rawnights = array();
            while ($temp = $this->db->fetchassoc()) {
                $property_id = $temp['properties_id'];
                $rawnights[$property_id][] = $temp;
            }

                /*
                $property_id = $temp['properties_id'];
                if ( !isset( $rawnights[$property_id] ) ) {
                    $rawnights[$property_id] = array();
                    $rawnights[$property_id]['raw'] = array();
                    $rawnights[$property_id]['dates'] = array();
                }

                 */

            /*
            foreach ($rawnights as $propid => $arr) {
                foreach ($arr['raw'] as $daterange) {

                    $startDate = new \DateTime($daterange['startdate']);
                    $endDate = new \DateTime($daterange['enddate']);
                    $endDate->add(new \DateInterval('P1D'));

                    $periodInterval = new \DateInterval('P1D');
                    $period = new \DatePeriod( $startDate, $periodInterval, $endDate );

                    foreach ($period as $date ) {
                        $rawnights[$propid]['dates'][$date->format('Y-m-d')] = array(
                            'min' => $daterange['minnights'],
                            'stayincrement' => $daterange['stayincrement'],
                        );
                    }
                }
            }
             */

            $select = '
            select
            properties_id AS property,
            startdate AS startdate,
            enddate AS enddate,
            amount,
            rateplan_name AS rateplan,
            date_created
    
            from v12_rates
            where 1=1
            ';

            if ( $this->singleproperty ) {
                $select.= ' and properties_id = ' . $this->singleproperty . ' ';
            }

            $this->db->query($select);

            $inserts = array();
            while($temp=$this->db->fetchassoc()) {

                $startdate = date('Y-m-d', strtotime($temp['startdate']));
                $enddate = date('Y-m-d', strtotime($temp['enddate']));

                $startdatet = strtotime($temp['startdate']);
                $enddatet = strtotime($temp['enddate']);

                $date = $startdate . '_' . $enddate;

                $minstay = 3;

                if ( isset( $rawnights[$temp['property']] ) ) {

                    foreach ($rawnights[$temp['property']] as $nightrange) {
                        $nightstart = strtotime($nightrange['startdate']);
                        $nightend = strtotime($nightrange['enddate']);

                        if ( $startdatet >= $nightstart && ($startdatet < $nightend) && ($enddatet <= $nightend) && ($enddatet > $nightstart) ) {
                            $minstay = $nightrange['minnights'];
                        }
                    }
                }

                if ( !isset( $inserts[$temp['property']][$date] ) ) {

                    $inserts[$temp['property']][$date] = array(
                        'property' => $temp['property'],
                        'is_active' => 1,
                        'is_deleted' => 0,
                        'date_created' => $temp['date_created'],
                        # dont need this'ratedesc' => $temp['rateplan'],
                        'startdate' => $startdate,
                        'enddate' => $enddate,
                        'minstay' => $minstay,
                    );

                }

                $array = $inserts[$temp['property']][$date];

                $rateplan = strtolower($temp['rateplan']);

                switch($rateplan) {
                    case 'weekly':
                        $inserts[$temp['property']][$date]['weeklyrate'] = $temp['amount'];
                        break;
                    case 'monthly':
                        $inserts[$temp['property']][$date]['monthlyrate'] = $temp['amount'];
                        break;
                    case 'daily':
                        $inserts[$temp['property']][$date]['dailyrate'] = $temp['amount'];
                        break;
                    default:
                        break;
                }
            }

            foreach($inserts as $propid => $array) {
                foreach ($array as $date => $row) {
                    $inserts[$propid]['inserts'][] = $row;
                }

            }

            $count = 0;

            foreach ($inserts as $propid=>$array) {
                $ins = $array['inserts'];
                $inserted = $this->db->autoInsertMultiple('property_rates', $ins);
                if ( $inserted > 0 ) {
                    $count+= $inserted;
                }
            }

            $this->db->query('COMMIT');
            echo $count . ' rows inserted into property_rates' . PHP_EOL;

        } catch ( Exception $e ) {

            $db_error = $this->db->error;

            // We need to reset the error because query() only calls mysql_query if there's no error
            $this->db->error = false;

            echo $db_error;

            $this->db->query('ROLLBACK');
            echo $e->getMessage();
            exit;
        }
    }


    /* 
     * This is currently a stored procedure on the live db "reloadminrates". 
     * @return void
     */
    public function generateminrates() {

        try {

            $this->db->query('START TRANSACTION');

            $this->db->query('DELETE FROM minrates');

            // Get all promos
            $this->db->query('

SELECT
p.id, p.discountamount_weekly, p.discountamount_daily, p.discountamount_monthly,
group_concat(pp.property_id) AS properties

FROM
promotions p

LEFT JOIN promotions_properties pp on ( p.id = pp.promotion_id )

where
p.is_active=1 and p.is_deleted=0

group by p.id');

            $promos = array();
            while ($temp=$this->db->fetchassoc()) {
                $promos[] = $temp;
            }

            $dailyrates = $this->getrates(RATEPLAN_ID_DAILY);

            $this->insertminrates($dailyrates, $promos, RATEPLAN_ID_DAILY);

            $weeklyrates = $this->getrates(RATEPLAN_ID_WEEKLY);

            $this->insertminrates($weeklyrates, $promos, RATEPLAN_ID_WEEKLY);

            /*
            $db->query('
            insert into minrates(properties_id, promotions_id, weeklyminrate)
                select r.properties_id as properties_id, pr.promotions_id as promotions_id, min(amount) as weeklyminrate
                from rates r
                inner join rateplans rp on
                    (rp.id=r.rateplans_id
                and r.available = 1
                    and r.maxstay>=7)
               left join promotions_rateplans pr on
                (pr.rateplans_id=r.rateplans_id)
                group by properties_id, promotions_id
            on duplicate key update weeklyminrate=values(weeklyminrate);');

            $db->query('
            update minrates m
            left join promotions_rateplans pr 
                on (pr.promotions_id=m.promotions_id)
            inner join rates r 
                on (r.rateplans_id=pr.rateplans_id
                    and r.properties_id=m.properties_id
            and r.available = 1
                    and r.minstay<7
                    and r.amount=m.dailyminrate)
            set dailydate=if(m.dailydate is null or r.thedate<m.dailydate, r.thedate, m.dailydate);');

            $db->query('
            update minrates m
            inner join promotions_rateplans pr 
                on (pr.promotions_id=m.promotions_id)
            inner join rates r 
                on (r.rateplans_id=pr.rateplans_id
                    and r.properties_id=m.properties_id
            and r.available = 1
                    and r.minstay=7
                    and r.amount=m.weeklyminrate)
            set weeklydate=if(m.weeklydate is null or r.thedate<m.weeklydate, r.thedate, m.weeklydate);');
            */

            $this->db->query('COMMIT');


        } catch ( Exception $e ) {
            $db_error = $this->db->error;

            // We need to reset the error because query() only calls mysql_query if there's no error
            $this->db->error = false;

            $this->db->query('ROLLBACK');

            #echo 'rolled back';
        }

    }

    /*
     * Set the last valid rate for a given property
     * @param object $db
     * @param string $date
     * @param int $id
     */
    public static function setCutoffDate(Db $db, $date, $id) {

        $db->query('SELECT lastvalidrate FROM properties WHERE id=' . $id );

        $last = '0000-00-00';
        if ( $temp = $db->fetchassoc() ) {
            $last= strtotime($temp['lastvalidrate']);
        }

        // only set if older than the existing value.
        if ( strtotime($date) && (strtotime($date) > $last) && $id && $date != '0000-00-00' && $date!= '1970-01-01' ) {
            $db->query('UPDATE properties SET lastvalidrate="'. $date . '" WHERE id=' . $id);
        }
    }

    /*
     * Set the last rate run for a given property
     * @param object $db
     * @param string $date
     * @param int $id
     */
    public static function setRateRun(Db $db, $date, $id) {
        if ( strtotime($date) && $id ) {
            $db->query('UPDATE properties SET rate_run="' . $date . '" WHERE id="' . $id. '"');
        }
    }

}
