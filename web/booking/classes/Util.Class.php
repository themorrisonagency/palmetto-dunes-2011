<?php

class Util {

    const RATEPLANID_DAILY = 1;
    const RATEPLANID_WEEKLY = 2 ;

    /*
     * Generate a n-length random string to tack onto a filename
     * @param int $len
     * @return string
     */
    public static function generate_auth($len) {
       $valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
       $length = $len;
       $password="";

       while($length>0) {
           $password .= $valid_chars[rand(0,strlen($valid_chars)-1)];
           $length--;
       }

       return $password;
    }


    /*
     * Generate a non conflicting filename
     * @param string $filename
     * @param int $propertyid
     * @return string
     */
    public static function fixName($filename, $propertyid) {
        $cleaned = preg_replace('/[^a-zA-Z0-9_\.-]/', '', $filename);
        if (file_exists(PROPERTY_PATH.$propertyid.'/images/'.$cleaned)) {
            $ret = self::generate_auth(4).$cleaned;
        } else {
            $ret = $cleaned;
        }
        return $ret;
    }

    //const RATEPLANID_MONTHLY = 3;

    /*
     * Get the min days for a give rate plan
     * @param int $rateplans_id
     * @return int
     */
    public static function getMinDays($rateplans_id) {
        $days = 0;
        $rateplans_id = (int)$rateplans_id;

        switch ($rateplans_id) {
            case self::RATEPLANID_DAILY:
                $days = 3;
                break;
            case self::RATEPLANID_WEEKLY:
                $days = 7;
                break;
        }

        return $days;
    }

    /*
     * Get the min days for a give rate plan
     * @param int $rateplans_id
     * @return int
     */
    public static function getMaxDays($rateplans_id) {
        $days = 0;
        $rateplans_id = (int)$rateplans_id;
        switch ($rateplans_id) {
            case self::RATEPLANID_DAILY:
                $days = 6;
            break;
            case self::RATEPLANID_WEEKLY:
                $days = 27;
            break;
        }

        return $days;
    }

    /*
     * Call curl and make a GET request
     * @param string $url
     * @param array $params
     * @param int $timeout
     * @param bool $ssl
     * @return array
     */
    public static function post( $url, $params=array(), $timeout = 20, $ssl=false )
    {
        if ( !$timeout ) {
            $timeout = 20;
        }
        
        $param_string =http_build_query($params);

        $curl = curl_init();
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_POST, 1 );
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($curl, CURLOPT_POSTFIELDS, $param_string );
        curl_setopt ($curl, CURLOPT_TIMEOUT, $timeout ); 
        curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, $ssl);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, $ssl);

        $result = curl_exec ($curl);

        $info = curl_getinfo($curl);


        $error_number= curl_errno($curl);
        $error = curl_error($curl);

        curl_close($curl);

        // Only return true if service replies with 200 OK
        if ( !$error_number ) {
            return array(
                'success' => 1,
                'result' => $result,
                'info' => $info,
            );
        }

        return array(
            'success' => 0,
            'error' => $error,
        );

    }


    /*
     * Dump Ajax immediately
     * @param array $response
     * @return void
     */
    public static function ajax($response) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        exit;
    }

    /*
     * Output a horizontal rule in the cmd
     * @return void
     */
    public static function hr() {
        echo '------------------------------------------------------------' . PHP_EOL;
    }

    /*
     * Output a line in the cmd with PHP EOL
     * @param string $message
     * @return void
     */
    public static function line($message) {
        echo $message . PHP_EOL;
    }

    /*
     * Check if an object is empty
     * @param mixed/object $data
     * @return bool
     */
    public static function isEmptyObject($data) {
        // Check for an empty object
        $check = (array)$data;

        return empty($check);
    }

    /*
     * Get the number of nights between a start and end date
     * @param string $startdate
     * @param string $enddate
     * @return int
     */
    public static function getNumberOfNightsBetween($startdate, $enddate) {
        $number = 0;

        $start = strtotime($startdate);
        $end = strtotime($enddate);

        $difference = abs($end-$start);

        return floor($difference/(60*60*24)); 
    }

    /*
     * Attempt to get the state code from a state name after lowercasing/trimming it
     * @param string $state
     * @return mixed
     */
    public static function getStateCode($state) {

        $states = array(
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'Washington DC',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'PR' => 'Puerto Rico',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VI' => 'Virgin Islands',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming',
        );

        foreach ($states as $code => $statename ) {
            if ( strtolower($state) == strtolower($statename) ) {
                return $code;
            }
        }

        return false;
    }

    public static function getBedRoomTypeId($name) {
        $db = new \Db;
        $db->query('SELECT id FROM bedroomtypes WHERE name = "' . addslashes($name) . '"');
        if ($temp = $db->fetchAssoc() ) {
            return $temp['id'];
        }

        return false;
    }

    public static function getBedTypeId($name) {
        $db = new \Db;
        $db->query('SELECT id FROM bedtypes WHERE name = "' . addslashes($name) . '"');
        if ($temp = $db->fetchAssoc() ) {
            return $temp['id'];
        }

        return false;
    }
	
    /*
    public static function normalizebedtypes($type) {
        switch($type) {
            case 'Queens':
                $type= 'Queen';
            break;
        }

        return $type;
    }
     */

    // calculate the promo code based on the new fields/v12
    public static function calculatepromo($baserate, $discount) {

        $num = $baserate - ($baserate * ( $discount / 100 ));

        return number_format($num,2);

    }


}
