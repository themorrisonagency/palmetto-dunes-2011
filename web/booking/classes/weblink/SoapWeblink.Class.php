<?php

namespace weblink;

/*
 * Class to interface with the new Weblink API that PD will be replacing Opera with
 * @author <meder.omuraliev@sabre.com>
 */

class Weblink extends \WebServiceCache
{
	protected $version='1.0';
	public $ret;
	public $client;
	public $startdate;
	public $enddate;
	public $type='';
	public $rooms=1;
	public $occupancy=2;
	public $adults=2;
	public $kids=0;
	public $roomtype='Room';
	public $headers = array();
	public $ratecode='DAILY';
	public $summaryonly='true';
	public $error='';
	public $errorcode='';
        public $errormsg='';
        
        /*
         * Default span of minutes 
         */
        const defaultMinutes = '20160';

	public function __construct($reinit=false, $usecache=false, $cachedir=false)
	{

            // Reset this because amaxus sets it to 3
            ini_set('default_socket_timeout', SOCKET_TIMEOUT);

            $this->wsdls['MainBinding'] = 'https://secure.instantsoftwareonline.com/StayUSA/ChannelPartners/wsWeblinkPlusAPI.asmx?wsdl';

            /*
		$this->wsdls['AvailabilityBinding']='http://webservices.micros.com/ows/5.1/Availability.wsdl';
		$this->wsdls['ReservationBinding']='http://webservices.micros.com/ows/5.1/Reservation.wsdl';
		$this->endpoints['AvailabilityBinding']='https://reservations.palmettodunes.com/OWS_WS_51/Availability.asmx';
		$this->endpoints['ReservationBinding']='https://reservations.palmettodunes.com/OWS_WS_51/Reservation.asmx';
             */
		$this->headers[]=array('ns'=>'http://www.instantsoftware.com/SecureWeblinkPlusAPI', 
			'header'=>'clsPartnerAuthentication',
			'params'=>array(
				//'_'=>'',
				//'timeStamp'=>date('Y-m-d',strtotime('now')).'T'.date('H:i:s',strtotime('now')).'.3618750-05:00',
                                'strUserID'=>WEBLINK_USERID,
                                'strPassword'=>WEBLINK_PASSWORD,
			));
		$start=date('U');
		parent::__construct($reinit, $usecache, $cachedir);
		//echo (date('U')-$start),' seconds';
	}

        /*
         * Get the base container parameters which include userid, password and companyid
         * @param $extra_parameters Array a list of extra parameters
         * @return array
         */
        private function buildContainer($params) {

            $baseParams = array(
                'strUserId'=>WEBLINK_USERID,
                'strPassword'=>WEBLINK_PASSWORD,

                // most methods use this 
                'strCOID' => WEBLINK_COMPANYID,

                // getChangeLogInfo is cased differently....
                'strCoId' => WEBLINK_COMPANYID,
            );

            $mergedParams = array_merge( $params, $baseParams );

            return $mergedParams;
        }

        /*
         * Fetch updated properties
         * @param string $type Type of changes, possible values: 'ALL', 'AVAILABILITY', 'PRICING', 'PROPERTY', 'REVIEW'
         * @param int $minutes_since The number of minutes since
         * @return mixed
         */
        public function fetchchangeloginfo($type='ALL', $minutes_since='20160') {

            // $minutes_since can be false/null
            if ( !$minutes_since ) {
                $minutes_since = self::defaultMinutes;
            }

            $params = array(
                'strChangeLogOption' => $type,
                'intMinutes' => (string)$minutes_since,
            );

            $container = $this->buildContainer($params);

            if ($temp = $this->callservice('MainBinding', 'getChangeLogInfo', $container, $result))
            {

                if (get_class($result) != 'SoapFault')
                {
                    $temp = new ChangeLogResponse($result);
                    return $temp;
                }
                else
                {
                    $this->adderror($result->getMessage());
                    return false;
                }
            } 

            return false;
        }

        /*
         * Fetch property data which includes rates and descriptions
         * @param $property_id string 
         * @param $startdate object The start date
         * @param $enddate object The end date
         * @return mixed
         */
        public function fetchpropertydesc($property_id, DateTime $startdate=null, DateTime $enddate=null) {

            if ( $property_id == '' ) {
                $this->adderror('required parameter property id required');
                return false;
            }

            $params = array(
                'strPropId' => $property_id,

                // include non available info
                'blnSendNonAvail' => 0,
            );

            if ( $startdate && $enddate ) {
                $params['strCheckInDate'] = $startdate->format('Y-m-d');
                $params['strCheckOutDate'] = $enddate->format('Y-m-d');
            }

            $container = $this->buildContainer($params);

            if ($temp = $this->callservice('MainBinding', 'getPropertyDesc', $container, $result))
            {

                if (get_class($result) != 'SoapFault')
                {
                    $temp = new PropertyDescResponse($result, $property_id);

                    return $temp;
                }
                else
                {
                    $this->adderror($result->getMessage());
                    return false;
                }
            } 

            return false;
        }

        /*
         * Fetch the partial non available dates for specific properties
         * @param int $property_id The property ID
         * @param int $minutes_since The number of minutes since
         * @return mixed
         */
        public function fetchpartialavailability($property_id, $minutes_since=20160) {

            if ( !$minutes_since ) {
                $minutes_since = self::defaultMinutes;
            }

            if ( $property_id == '' ) {
                $this->adderror('required parameter property id required');
                return false;
            }

            $container = $this->buildContainer(array(
                'strPropID' => $property_id,
                'intMinutes' => (string)$minutes_since,

                // PHP-SOAP is requiring these, so specify these as blank
                'intLogID' => '',
                'intMaxRows' => '',

                // This "Needs" to be ALL
                'strOptions' => 'ALL',
            ));

            $method = 'GetReservationChangeLog';

            if ($temp = $this->callservice('MainBinding', $method, $container, $result))
            {

                if (get_class($result) != 'SoapFault')
                {
                    $temp = new ReservationChangeResponse($result, $property_id);
                    return $temp;
                }
                else
                {
                    $this->adderror($method . ' error: ' . $result->getMessage());
                    return false;
                }
            } 

            return false;
        }

        /*
         * Create a booking
         * @param array $data
         * @param array $avail_info
         * @return mixed
         */
        public function createbooking($data, $avail_info, $wholesale=false) {

// Type of credit card, possible values: 'VISA' (Visa), 'MC' (Mastercard), 'DISC' (Discover), 'AMEX' (American Express)

            $cardtypes=array('VISA', 'MC', 'DISC', 'AMEX');
            if(!in_array($data['cardtype'], $cardtypes))
                Throw new \Exception('Invalid card type '.$data['cardtype'], 9987);

            #$data['promocode'] = 'TEST';

            $container = array(
                'strProperty' => $data['unit_number'],
                'intNights' => $data['number_nights'],
                'dtCheckIn' => $data['arrive'],
                'intAdults' => $data['adults'],
                'intChildren' => $data['children'],
                'strCOID' => WEBLINK_COMPANYID,


                // If blnISILinkCSAOffered returned from getStartupInfo is true, then a true value here indicates that the user has accepted CSA trip insurance coverage offered by ISILink. 
                'blnAcceptCSA' => $data['vacationassurance'],

                // Required. This value must be set to the dblDues value of the clsResQuery object returned from a prior getReservationQuery call.

                'dblCCAmount' => $avail_info['dblDues'],

                //If passed to getBookingWithRentOverride, this will override the base rent value of the reservation. This field is ignored in the other methods.
                'dblForcedRent' => 0,

                // Optional and only applicable to getOwnerBooking.
                'blnCleaning' => false,

                // Optional
                //'strTourOperator' => '', Optional. Tour operator Code provided by the management company
                //'strResType' => '', Optional and only applicable to getOwnerBooking. The type of the booking, possible values: 'O' - Owner booking, 'G' - Guest of owner. Defaults to Owner Booking.
                //'strMarketID' Optional. Reservation source marketting ID.
                //'strMarketingCategory' Marketing Category. Possible marketing categories are returned in getMarketingCategories.Max length: 50.
                //'strMarketingSource' Marketing Source. Possible marketing categories and its sources are returned in getMarketingCategories.Max length: 50.

                'strComments' => $data['requests'],

            );

            if ( $data['promocode'] != '' ) {
                $container['strPromotionCode'] = $data['promocode'];
            }

            if ( $data['travelagentcode'] != '' ) {
                $container['strTravelAgentCode'] = $data['travelagentcode'];
            }

            $container['objGuestDetails'] = array(
                'strEmail' => $data['email'],
                'strFirstName' => $data['first_name'],
                'strLastName' => $data['last_name'],
                'strMiddleInitial' => $data['middle_initial'],
                'objAddress' => array(
                    'strAddress1' => $data['address1'],
                    'strAddress2' => $data['address2'],
                    'strCity' => $data['city'],
                    'strState' => $data['state'],
                    'strCountry' => $data['country'],
                    'strProvince' => $data['province'] ? $data['province'] : '',
                    'strZip' => $data['zip'],
                    'strHomePhone' => $data['phone_home'],
                ),
            );

            if ( $data['phone_work'] != '' ) {
                $container['objGuestDetails']['objAddress']['strWorkPhone'] = $data['phone_work'];
            }

            $container['objCreditCardDetails'] = array(
                'intExpMonth' => $data['exp_month'],
                'intExpYear' => $data['exp_year'],
                'strName' => $data['cardname'],
                'strCCType' => $data['cardtype'],
                'strEmail' => $data['email'],

                'objBillingAddress' => array(
                    'strAddress1' => $data['address1'],
                    'strAddress2' => $data['address2'],
                    'strCity' => $data['city'],
                    'strState' => $data['state'],
                    'strProvince' => $data['province'] ? $data['province'] : '',
                    'strZip' => $data['zip'],
                    'strCountry' => $data['country'],
                    'strHomePhone' => $data['phone_home'],
                    'strWorkPhone' => '',
                ),
            );

            if ( defined('ENABLE_CCTOKENS')  && ENABLE_CCTOKENS ) {
                if ( !$data['cc_token'] ) {
                    throw new \Exception('Invalid Credit card.');
                }
                $container['objCreditCardDetails']['strToken'] = $data['cc_token'];
            }

            $container['objInsuranceRequest'] = array(
                'blnAcceptDamageInsurance' => 'false',
                'blnAcceptTravelInsurance' => 'false',
            );

/*
            <sec:objInsuranceRequest>
               <!--Optional:-->
               <sec:blnAcceptDamageInsurance>false</sec:blnAcceptDamageInsurance>
               <!--Optional:-->
               <sec:blnAcceptTravelInsurance>false</sec:blnAcceptTravelInsurance>
            </sec:objInsuranceRequest>
*/

            $container = array(
                'objBookingRequest' => $container,
            );

            if ($temp = $this->callservice('MainBinding', 'createBooking', $container, $result))
            {

                $dir = get_class($result) != 'SoapFault' ? 'bookings' : 'failedbookings';

                $log_options = array(
                    'dir' => LOG_DIR . '/' . $dir,
                    'output' => 'log',
                );
                $logger = new Logger($log_options);
                $logger->logtofile(serialize($this->getdebuginfo()));

                if (get_class($result) != 'SoapFault')
                {
                    $temp = new BookingResponse($result);
                    return $temp;
                }
                else
                {
                    $this->adderror($result->getMessage());
                    return false;
                }
            } 

            return false;

        }

        /*
         * Confirm availability and get a real quote
         * @param string $unit_number
         * @param string $startdate
         * @param int $number_nights
         * @param int $adults
         * @param int $kids
         * @return mixed
         */
        public function confirmavailability($unit_number, $startdate, $number_nights, $adults=DEFAULT_ADULTS, $kids=DEFAULT_KIDS, $promo = '') {

            $container = $this->buildContainer(array(
                'strProperty' => $unit_number,
                'intNights' => $number_nights,
                'strCheckIn' => $startdate,
                'intAdults' => $adults,
                'intChildren' => $kids,
            ));

            if ( $promo != '' ) {
                $container['strPromotionCode'] = $promo;
            }

            $container = array(
                'objResQueryInfo' => $container,
            );

            if ($temp = $this->callservice('MainBinding', 'GetReservationQuery', $container, $result))
            {

                if (get_class($result) != 'SoapFault')
                {
                    $temp = new ReservationQueryResponse($result);
                    return $temp;
                }
                else
                {
                    $this->adderror($result->getMessage());
                    return false;
                }
            } 

            return false;

        }

        /*
         * Fetch the non available dates for specific properties
         * @param $startdate string
         * @param $enddate string
         * @param $companyid string
         * @param $property_id string
         * @return mixed
         */
        public function getpropertyavailability($property_id) {

            if ( $property_id == '' ) {
                $this->adderror('required parameter property id required');
                return false;
            }

            $container = $this->buildContainer(array(
                'strPropID' => $property_id,
            ));

            if ($temp = $this->callservice('MainBinding', 'GetPropertyAvailabilityInfo', $container, $result))
            {

                if (get_class($result) != 'SoapFault')
                {
                    $temp = new AvailabilityResponse($result, $property_id);
                    return $temp;
                }
                else
                {
                    $this->adderror($result->getMessage());
                    return false;
                }
            } 

            return false;
        }
        
        public function fetchpropertyindexes()
        {
        	$container = $this->buildContainer(array());
        	if ($temp = $this->callservice('MainBinding', 'GetPropertyIndexes', $container, $result))
            {

                if (get_class($result) != 'SoapFault')
                {
                    $temp = new PropertyIndexesResponse($result);
                    return $temp;
                }
                else
                {
                    $this->adderror($result->getMessage());
                    return false;
                }
            } 

            return false;
        }

}
