<?php

class OWS extends WebServiceCache
{
	protected $version='1.0';
	var $ret;
	var $client;
	var $startdate;
	var $enddate;
	var $type='';
	var $rooms=1;
	var $occupancy=2;
	var $adults=2;
	var $kids=0;
	var $roomtype='Room';
	var $headers = array();
	var $ratecode='DAILY';
	var $summaryonly='true';
	var $error='';
	var $errorcode='';
	var $errormsg='';
	protected $hotelcode='PDR';
	protected $chaincode='CHA';
	protected $originentity='PDROWS';
	protected $originsystem='WEB';
	protected $destinationentity='PDR';
	protected $destinationsystem='PMS';
	
	function __construct($reinit=false, $usecache=false, $cachedir=false)
	{
		//$rooturl='70.61.192.5';  //test ip
		//$rooturl='itinerary.palmettodunes.com';		//live ip
		$this->wsdls['AvailabilityBinding']='http://webservices.micros.com/ows/5.1/Availability.wsdl';
		$this->wsdls['ReservationBinding']='http://webservices.micros.com/ows/5.1/Reservation.wsdl';
		$this->endpoints['AvailabilityBinding']='https://reservations.palmettodunes.com/OWS_WS_51/Availability.asmx';
		$this->endpoints['ReservationBinding']='https://reservations.palmettodunes.com/OWS_WS_51/Reservation.asmx';
		$this->headers[]=array('ns'=>'http://webservices.micros.com/og/4.3/Core/', 
			'header'=>'OGHeader',
			'params'=>array(
				'_'=>'',
				'transactionID'=>'000032',
				'primaryLangID'=>'E',
				'timeStamp'=>date('Y-m-d',strtotime('now')).'T'.date('H:i:s',strtotime('now')).'.3618750-05:00',
				'Origin'=>array(
					'_'=>'',
					'entityID'=>$this->originentity,
					'systemType'=>$this->originsystem
					),
				'Destination'=>array(
					'_'=>'',
					'entityID'=>$this->destinationentity,
					'systemType'=>$this->destinationsystem
					)
				)
			);
		$start=date('U');
		parent::__construct($reinit, $usecache, $cachedir);
		//echo (date('U')-$start),' seconds';
	}

	function fetchcalendar($startdate, $enddate, $roomcode, $rateplan, $promotioncode='')
	{
                if ($this->checkerror())
			$this->clearerrors();

		$startdate=strtotime($startdate);
		$enddate=strtotime($enddate);
		$startdate=date('Y-m-d', $startdate);
		$enddate=date('Y-m-d',$enddate);
		$container=array(
			'HotelReference'=>array(
				'_'=>'',
				'chainCode'=>$this->chaincode,
				'hotelCode'=>$this->hotelcode
				),
			'StayDateRange'=>array(
				'StartDate'=>$startdate.'T00:00:00',
				'EndDate'=>$enddate.'T00:00:00'
				)
			);
		$container['RatePlanCode']=$rateplan;
		$container['RoomTypeCode']=$roomcode;
		if(strlen(trim($promotioncode)))
			$container['PromotionCode']=$promotioncode;
		
		if ($temp = $this->callservice('AvailabilityBinding', 'FetchCalendar', $container, $result))
		{
			if (get_class($result) != 'SoapFault')
			{
				$temp = new FetchCalendarResponse($result, $rateplan);
				return $temp;
			}
			else
			{
				$this->adderror($result->getMessage());
				return false;
			}
		} 
		else
			return false;
	}
	
	function book($params)
	{
		$cardtypes=array('VI','MC','AX','DS');
		if(!in_array($params['cardtype'], $cardtypes))
			Throw new Exception('Invalid card type '.$params['cardtype'], 9987);
		$guestcount=array();
		if(is_numeric($params['adults']))
			$guestcount[]=array(
				'ageQualifyingCode'=>'ADULT',
				'count'=>$params['adults'],
				'_'=>''
				);
		if(is_numeric($params['children']))
			$guestcount[]=array(
				'ageQualifyingCode'=>'CHILD',
				'count'=>$params['children'],
				'_'=>''
				);
		$container=array(
			'HotelReservation'=>array(
			'RoomStays'=>array(
				'RoomStay'=>array(
					'RatePlans'=>array(
						'RatePlan'=>array(
							'ratePlanCode'=>$params['rateplan'],
							'promotionCode'=>$params['promocode'],
							'_'=>''
							)
						),
					'RoomTypes'=>array(
						'RoomType'=>array(
							'roomTypeCode'=>$params['ows_id'],
							'numberOfUnits'=>1,
							'_'=>''
							)
						),
					'RoomRates'=>array(
						'RoomRate'=>array(
							'roomTypeCode'=>$params['ows_id'],
							'ratePlanCode'=>$params['rateplan'],
								'Rates'=>array(
									'Rate'=>array(
										'Base'=>array(
											'currencyCode'=>'USD',
											'_'=>round($params['nightavg'],2)
											)
										)
									)
							)
						),
					'GuestCounts'=>array(
						'GuestCount'=>$guestcount
						),
					'TimeSpan'=>array(
						'StartDate'=>date('Y-m-d',strtotime($params['arrive'])).'T00:00:00.000',
						'EndDate'=>date('Y-m-d',strtotime($params['depart'])).'T00:00:00.000'
						),
					'Guarantee'=>array(
						'guaranteeType'=>'CC',
							'GuaranteesAccepted'=>array(
								'GuaranteeAccepted'=>array(
									'GuaranteeCreditCard'=>array(
										'cardCode'=>$params['cardtype'],
										'cardHolderName'=>$params['cardname'],
										'cardNumber'=>$params['cardnumber'],
										'expirationDate'=>$params['cardexp']
										)
									)
								)
						),
					'HotelReference'=>array(
						'chainCode'=>'CHA',
						'hotelCode'=>'PDR'
						)
					)
				),
			'ResGuests'=>array(
				'ResGuest'=>array(
					'resGuestRPH'=>0,
						'Profiles'=>array(
							'Profile'=>array(
								'Customer'=>array(
									'PersonName'=>array(
										'firstName'=>$params['firstname'],
										'lastName'=>$params['lastname']
										)
									),
								'Addresses'=>array(
									'NameAddress'=>array(
										'AddressLine'=>array(
											$params['address1'],
											$params['address2']
											),
										'cityName'=>$params['city'],
										'stateProv'=>$params['state'],
										'countryCode'=>$params['country'],
										'postalCode'=>$params['zipcode']
										)
									),
								'Phones'=>array(
									'NamePhone'=>array(
										array(
											'phoneRole'=>'PHONE',
											'phoneType'=>'HOME',
												'PhoneNumber'=>$params['phonenumber']
											),
										array(
											'phoneRole'=>'EMAIL',
											'phoneType'=>'EMAIL',
												'PhoneNumber'=>$params['email']
											)
										)
									)
								)
							),
						'SpecialRequests'=>array(
							'SpecialRequest'=>array(
								'Text'=>$params['requests']
								)
							)
					)
				),
			'WrittenConfInst'=>array(
				'Email'=>$params['email']
				)
			)
		);
		if(strlen($params['country']==0))
			unset($container['HotelReservation']['ResGuests']['ResGuest']['resGuestRPH']['Profiles']['Profile']['Addresses']['NameAddress']['countryCode']);
		if(count($guestcount)==0)
			unset($container['HotelReservation']['RoomStays']['RoomStay']['GuestCounts']);
		if(strlen($params['promocode'])==0)
			unset($container['HotelReservation']['RoomStays']['RatePlans']['RatePlan']['promotionCode']);
		if($temp=$this->callservice('ReservationBinding', 'CreateBooking', $container, $result))
    	{
			$date = new DateTime();
			if(is_object($result) && empty($result->confirmation) || is_array($result) && empty($result['confirmation'])){
				$log = fopen("/var/www/html/palmettodunes_booking/logs/missingConfirmation/".$date->format('U = Y-m-d H:i:s').".json", "w");
				fwrite($log, json_encode($result));
			}
    		$temp=new CreateBookingResponse($result);
    		return $temp;
    	}
    	else
    		return false;
        }
	
	function bookwholesale($params)
	{
		$guestcount=array();
		if(is_numeric($params['adults']))
			$guestcount[]=array(
				'ageQualifyingCode'=>'ADULT',
				'count'=>$params['adults'],
				'_'=>''
				);
		if(is_numeric($params['children']))
			$guestcount[]=array(
				'ageQualifyingCode'=>'CHILD',
				'count'=>$params['children'],
				'_'=>''
				);
		$container=array(
			'HotelReservation'=>array(
			'RoomStays'=>array(
				'RoomStay'=>array(
					'RatePlans'=>array(
						'RatePlan'=>array(
							'ratePlanCode'=>$params['rateplan'],
							'promotionCode'=>$params['promocode'],
							'_'=>''
							)
						),
					'RoomTypes'=>array(
						'RoomType'=>array(
							'roomTypeCode'=>$params['ows_id'],
							'numberOfUnits'=>1,
							'_'=>''
							)
						),
					'RoomRates'=>array(
						'RoomRate'=>array(
							'roomTypeCode'=>$params['ows_id'],
							'ratePlanCode'=>$params['rateplan'],
								'Rates'=>array(
									'Rate'=>array(
										'Base'=>array(
											'currencyCode'=>'USD',
											'_'=>round($params['nightavg'],2)
											)
										)
									)
							)
						),
					'GuestCounts'=>array(
						'GuestCount'=>$guestcount
						),
					'TimeSpan'=>array(
						'StartDate'=>date('Y-m-d',strtotime($params['arrive'])).'T00:00:00.000',
						'EndDate'=>date('Y-m-d',strtotime($params['depart'])).'T00:00:00.000'
						),
					'Guarantee'=>array(
						'guaranteeType'=>'CC',
							'GuaranteesAccepted'=>array(
								'GuaranteeAccepted'=>array(
									'GuaranteeCreditCard'=>array(
										'cardCode'=>'VI',
										'cardHolderName'=>'Fake Card',
										'cardNumber'=>'4111111111111111',
										'expirationDate'=>date('Y-m-t')
										)
									)
								)
						),
					'HotelReference'=>array(
						'chainCode'=>'CHA',
						'hotelCode'=>'PDR'
						)
					)
				),
			'ResGuests'=>array(
				'ResGuest'=>array(
					'resGuestRPH'=>0,
						'Profiles'=>array(
							'Profile'=>array(
								'Customer'=>array(
									'PersonName'=>array(
										'firstName'=>$params['firstname'],
										'lastName'=>$params['lastname']
										)
									),
								'Addresses'=>array(
									'NameAddress'=>array(
										'AddressLine'=>array(
											$params['address1'],
											$params['address2']
											),
										'cityName'=>$params['city'],
										'stateProv'=>$params['state'],
										'countryCode'=>$params['country'],
										'postalCode'=>$params['zipcode']
										)
									),
								'Phones'=>array(
									'NamePhone'=>array(
										array(
											'phoneRole'=>'PHONE',
											'phoneType'=>'HOME',
												'PhoneNumber'=>$params['phonenumber']
											),
										array(
											'phoneRole'=>'EMAIL',
											'phoneType'=>'EMAIL',
												'PhoneNumber'=>$params['email']
											)
										)
									)
								)
							),
						'SpecialRequests'=>array(
							'SpecialRequest'=>array(
								'Text'=>$params['requests']
								)
							)
					)
				),
			'WrittenConfInst'=>array(
				'Email'=>$params['email']
				)
			)
		);
		if(strlen($params['country']==0))
			unset($container['HotelReservation']['ResGuests']['ResGuest']['resGuestRPH']['Profiles']['Profile']['Addresses']['NameAddress']['countryCode']);
		if(count($guestcount)==0)
			unset($container['HotelReservation']['RoomStays']['RoomStay']['GuestCounts']);
		if(strlen($params['promocode'])==0)
			unset($container['HotelReservation']['RoomStays']['RatePlans']['RatePlan']['promotionCode']);
		if($temp=$this->callservice('ReservationBinding', 'CreateBooking', $container, $result))
    	{
			$date = new DateTime();
			if(is_object($result) && empty($result->confirmation) || is_array($result) && empty($result['confirmation'])){
				$log = fopen("/var/www/html/palmettodunes_booking/logs/missingConfirmation/".$date->format('U = Y-m-d H:i:s').".json", "w");
				fwrite($log, json_encode($result));
			}
    		$temp=new CreateBookingResponse($result);
    		return $temp;
    	}
    	else
    		return false;
	}
	
	function availability($params)
	{
		$arrive=strtotime($params['arrive']);
		$depart=strtotime($params['depart']);
		$container=array(
			'summaryOnly'=>false,
			'AvailRequestSegment'=>array(
				'availReqType'=>'Room',
				'numberOfRooms'=>1,
				'roomOccupancy'=>2,
				'totalNumberOfAdults'=>2,
				'totalNumberOfChildren'=>0,
				'StayDateRange'=>array(
					'StartDate'=>date('Y-m-d', $arrive).'T00:00:00',
					'EndDate'=>date('Y-m-d', $depart).'T00:00:00'
					),
				'RatePlanCandidates'=>array(
					'RatePlanCandidate'=>array(
						'ratePlanCode'=>$params['rateplan'],
						'promotionCode'=>trim($params['promocode'])
						)
					),
				'RoomStayCandidates'=>array(
					'RoomStayCandidate'=>array(
						'roomTypeCode'=>$params['ows_id']
						)
					),
				'HotelSearchCriteria'=>array(
					'Criterion'=>array(
						'HotelRef'=>array(
							'chainCode'=>'CHA',
							'hotelCode'=>'PDR'
							)
						)
					)
				)
			);
		if(strlen(trim($params['promocode']))==0)
			unset($container['AvailRequestSegment']['RatePlanCandidates']['RatePlanCandidate']['promotionCode']);
		if($temp=$this->callservice('AvailabilityBinding', 'Availability', $container, $result))
    	{
    		if(get_class($result)=='SoapFault')
    		{
    			Throw $result;
    		}

			$temp=new AvailabilityResponse($result);
			return $temp;
    	}
    	else
    		Throw new Exception('No response from booking service.');
	}
	

	function checkdates($startdate) {
		if($startdate==0) {
			$_SESSION[SESS_NAME]['msg'][] = 'No Start date set.';
            $_SESSION[SESS_NAME]['errors'] = TRUE;
		} else {
			$start = strtotime($startdate);
			$this->startdate = date('Y-m-d',strtotime($startdate));
		}
	}

	function get_enddate($days) {
		//$days = $days-1;
		$start = strtotime($this->startdate);
		$this->enddate = date('Y-m-d', mktime(0, 0, 0, date('m',$start),date('d',$start)+$days,date('Y',$start)));
	}
	
	function setdetailed() {
		$this->summaryonly='false';
	}
		
	function setaction() {
		$this->action='Availability';
	}
	
	function set_origin($ent='PDROWS',$sys='WEB') {
		$this->origin_entity = $ent;
		$this->origin_system = $sys;
	}

	function set_dest($ent='PDR',$sys='PMS') {
		$this->dest_entity = $ent;
		$this->dest_system = $sys;
	}

	function set_hotelref($chain='CHA',$hotel='PDR') {
		$this->chaincode = $chain;
		$this->hotelcode = $hotel;
	}
	
	function set_ratecode($v='') {
		$this->ratecode = $v;
	}	
	
	function set_roomtypecode($v='') {
		$this->type = $v;
	}
	

	

			


	
	function get_error($clean) {
		if($clean['resultStatusFlag']=='FAIL') {
			$this->error=TRUE;
			$this->errorcode=$clean['hc:GDSError']['errorCode'];
			$this->errormsg=$clean['hc:GDSError']['_content'];
		}
	}

	
}
?>
