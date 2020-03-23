<?php


require_once('esite/classes/Db.Class.php');
require_once('esite/classes/GenericAdmin.Class.php');

require_once DIR . '/conf/v12_load.php';

require_once('classes/PropertyList.Class.php');
require_once('classes/Property.Class.php');
require_once('classes/AmenityList.Class.php');
require_once('classes/ViewList.Class.php');
require_once('classes/ImageList.Class.php');
require_once('classes/LocationList.Class.php');
require_once('classes/Content.Class.php');

require_once('esite/classes/GenericErrors.class.php');
require_once('esite/classes/WebServiceCache.class.php');
require_once('classes/OWSResponse.Class.php');
require_once('classes/SoapOWS.Class.php');
require_once('classes/FetchCalendarResponse.Class.php');
require_once('classes/CreateBookingResponse.Class.php');
require_once('classes/AvailabilityResponse.Class.php');
require_once('classes/BookingCart.Class.php');
require_once('classes/NavisReach.Class.php');
require_once('esite/classes/Mailer.Class.php');

/*
 * New v12 classes
 */


use weblink\Weblink;
use weblink\Logger;
use weblink\ReservationQueryResponse;
use weblink\TokenGenerator;

class Reservations extends ModuleInstance
{
	
	private $perpage=5;
	
	private $property=null;
	
	private $cartvars=array(
		'bookingdata'=>array(
			'id', 'arrive', 'depart', 'promocode'
			),
		'userdata'=>array(
				'firstname', 'lastname', 'email', 'phone', 'adults', 'children', 'carpasses', 'vacationassurance', 'requests'
				),
		'addressdata'=>array(
			'address1', 'address2', 'city', 'state', 'zipcode', 'country'
			)
		);
	
	function __construct()
	{
		$this->db=new Db();
		parent::__construct();
	}
	
	function _searchproperties()
	{
		try
		{
			$list= $this->searchpropertiesint($_REQUEST);
			if(count($list)==0 && strlen(trim($_REQUEST['promocode']))>0)
				throw new Exception('Oops. The dates you selected or other filters may not apply to this promo code. Please check the promotion details for more information or remove the promo code and click apply to see availability. You can call us for help at <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span>.', 9989);
			return $list;
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
	}
	
	function _featuredproperties()
	{
		try
		{
			$params=array('featured'=>1);
			$exclude=null;
			if(is_numeric($_REQUEST['propertyid']))
				$exclude=$_REQUEST['propertyid'];
			return $this->searchpropertiesint($params, $exclude);
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
	}
	
	function _similarproperties()
	{
		try
		{
			$params=array(
				'typeid'=>$_REQUEST['typeid'],
				'bedrooms'=>$_REQUEST['bedrooms'],
				'locationid'=>$_REQUEST['locationid'],
				'arrive'=>$_REQUEST['arrive'],
				'depart'=>$_REQUEST['depart']
				);
			
			return $this->searchpropertiesint($params);
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
	}
	
	function _similarpropertiesfromid()
	{
		$this->returnValue=array();
		try
		{
			$property=$this->property; //try to use last pulled property first to avoid pulling the same data twice
			if(!is_object($property))
			{
				$property=new Property();
				$property->getpropertybyid($_REQUEST['propertyid']);
			}
			$params=array(
				'typeid'=>$property->getvalue('typeid'),
				'bedrooms'=>$property->getvalue('num_bedrooms'),
				'locationid'=>$property->getvalue('locationid'),
				'arrive'=>$_REQUEST['arrive'],
				'depart'=>$_REQUEST['depart']
				);
			return $this->searchpropertiesint($params, $property->getvalue('id'));
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
		return $this->returnValue;
	}
	
	function searchpropertiesint($parameters, $excludeids=null, $page=1)
	{
		$timerstart=microtime(true);
		if(!is_numeric($page)||$page<0)
			$page=1;
		$pagestart=($page-1)*$this->perpage;
		$pageend=$pagestart+$this->perpage-1;
		if(!is_array($excludeids))
			$excludeids=array($excludeids);
		$list=new PropertyList();
		$list->setsearch($parameters);
		$this->returnValue=array();
		$showprices=$list->checkdates();
		$items=$list->getlist();
		$current=0;
		foreach($items as $item)
		{
			if(!in_array($item['id'], $excludeids)) // && $current>=$pagestart && $current<=$pageend)
			{	
				$p = new Property($item['id']);
				$p->getpropertybyid($item['id']);
				$rate_totals = $p->getratetotals($parameters['arrive'], $parameters['depart'], date('Y-m-d'), '', true);
				$temp=array(
					'@id'=>$item['id'],
					'#name'=>$item['property_name'],
					'#shortdescription'=>$item['short_description'],
					'#tagline'=>$item['tagline'],
					'@viewid'=>$item['theview'],
					'@bedrooms'=>$item['num_bedrooms'],
					'@bathrooms'=>$item['num_bathrooms'],
					'@sleeps'=>$item['num_sleeps'],
					'@owsid'=>$item['ows_id'],
					'@unit'=>$item['unit_number'],
					'@typeid'=>$item['typeid'],
					'@locationid'=>$item['locationid'],
					'@latitude'=>$item['latitude'],
					'@longitude'=>$item['longitude'],
                    '@dailyminrate' => $item['dailyminrate']
					);
				if($showprices)
				{
					$temp['@minrate']=$item['minrate'];
					$temp['@maxrate']=$item['maxrate'];
					$temp['@dailyrate']=round($item['avgrate'],2);
					if($list->getstayspan()>6)
						$temp['@weeklyrate']=$temp['@dailyrate']*5;
					else
						$temp['@weeklyrate']=$temp['@dailyrate']*7;
					$span = unixtojd(strtotime($parameters['depart']))-unixtojd(strtotime($parameters['arrive']));
					//$temp['@totalrate']=$this->calculateTotalRate($temp,$span); //this is completely broken
					$temp['@totalrate']=$item['total'];
					$temp['@slashthroughtotalrate']=$rate_totals['subtotal']['amount'];
					$temp['@slashthroughdailyrate']=$rate_totals['subtotal']['amount'] / $span;
					$temp['@sortbyrate']=$item['minrate'];
					$temp['@rateplanid']=$item['rateplans_id'];
					//$temp['@dailyrate'] = $temp['@totalrate'] / $span;
					// To deal with 6th night free
					if(isset($item['promodailyminrate']) && $span == 6)
					{
						$temp['@dailyminrate'] = $item['minrate'] * (5/6);
						$temp['@totalrate'] =  $item['minrate'] * 5;
					}
					elseif(isset($item['promodailyminrate'])) {
						$temp['@dailyminrate'] = $item['minrate'];
						$temp['@totalrate'] =  $item['minrate'] * $span;
						$temp['@minrate'] = 0;
					}
				}
				else
				{
					$temp['@dailyminrate']=$item['dailyminrate'];
					$temp['@sortbyrate']=$item['dailyminrate'];
					if(is_numeric($item['weeklyminrate']))
						$temp['@weeklyminrate']=$item['weeklyminrate']*7;
					if(isset($item['promodailyminrate']))
						$temp['@promodailyminrate']=$item['promodailyminrate'];
					if(is_numeric($item['weeklyminrate']) && is_numeric($item['promoweeklyminrate']))
						$temp['@promoweeklyminrate']=$item['promoweeklyminrate']*7;
				}
				$temp['images']=array();
				$temp['images']=array(array('@id'=>$item['images'][0]['id'],
						'#filename'=>$item['images'][0]['filename'],
						'#caption'=>$item['images'][0]['caption']));
				
				/*
				foreach($item['images'] as $image)
				{
					$temp['images'][]=array(
						'@id'=>$image['id'],
						'#filename'=>$image['filename'],
						'#caption'=>$image['caption']
						);
				}
				*/
				
				if(isset($item['promocodes']))
				{
					$temp['promotions']=array();
					foreach($item['promocodes'] as $promo)
					{
						$promotemp=array(
							'@id'=>$promo['promocodeid'],
							'@wholesale'=>$promo['is_wholesale'],
							'#name'=>$promo['promotion_name'],
							'#shortdescription'=>$promo['long_desc'],
							'#promocode'=>$promo['promocode']
							);
						if(isset($promo['totaldiscount']))
							$promotemp['@totaldiscount']=$promo['totaldiscount'];
						$temp['promotions'][]=$promotemp;
					}
				}
				if(strlen($item['discountname']))
					$temp['featuredpromo']=array(
						'@minstay'=>$item['discountminstay'],
						'#name'=>$item['discountname'],
						'#shortdescription'=>$item['discountlong'],
						'#promocode'=>$item['promocode']
						);
					
				$this->returnValue[]=$temp;
			}
			if(!in_array($item['id'], $excludeids))
				$current++;
		}
		if(count($this->returnValue)) //don't create record if there were no matches.
			$this->returnValue[0]['@runtime']=(microtime(true)-$timerstart);
		
		return $this->returnValue;
	}
	
	function _getpropertydetails()
	{
		try
		{
			$this->returnValue=array();
			$params=array(
				'propertyid'=>$_REQUEST['propertyid'],
				'arrive'=>$_REQUEST['arrive'], 
				'depart'=>$_REQUEST['depart'],
				'promocode'=>$_REQUEST['promocode']
				);
			$this->returnValue=$this->getpropertydetailsinternal($params);
			return $this->returnValue;
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
			$loc=ModuleParameter::getParameterifset('location_failure','/vacation-rentals/hilton-head-vacation-rentals',$this);
			header('Location:/booking/'.$loc.'?arrive='.$_REQUEST['arrive'].'&depart='.$_REQUEST['depart'].'&promocode='.$_REQUEST['promocode']);
			exit();
		}
	}
	protected function calculateTotalRate($rates, $span) {
		$weeks = floor($span / 7);
		$return_total = 0;
		for ($i = 1; $i < $span + 1; $i++) {
			if ($i > $weeks * 7) {
				$return_total += ceil($rates['@dailyminrate']);
			} else {
				$return_total += ceil($rates['@dailyrate']);
			}
		}
		return $return_total;
	}

	function getpropertydetailsinternal($params)
	{
		$returnvalue=array();
		try
		{
			$p=new Property('',$this->db);
			if($p->getpropertybyid($params['propertyid'], $params) && $p->getvalue('is_active') && !$p->getvalue('is_deleted'))
			{
				$this->property=$p;
				$returnvalue=array(
					'@id'=>$p->getvalue('id'),
					'#name'=>$p->getvalue('property_name'),
					'#shortdescription'=>$p->getvalue('short_description'),
					'#description'=>$p->getvalue('description'),
					'@viewid'=>$p->getvalue('theview'),
					'@bedrooms'=>$p->getvalue('num_bedrooms'),
					'@bathrooms'=>$p->getvalue('num_bathrooms'),
					'@sleeps'=>$p->getvalue('num_sleeps'),
					'@owsid'=>$p->getvalue('ows_id'),
					'@unit'=>$p->getvalue('unit_number'),
					'@typeid'=>$p->getvalue('typeid'),
					'@locationid'=>$p->getvalue('locationid'),
					'@latitude'=>$p->getvalue('latitude'),
					'@longitude'=>$p->getvalue('longitude'),
					'@vacationassurance'=>$p->getvacationassurance(),
					'@tour_url'=> $p->getvalue('tour_url')
					);
				$amenities=$p->getvalue('amenities');
				$temp=array();
				foreach($amenities as $a)
					$temp[]=array('@id'=>$a);
				$returnvalue['amenities']=$temp;
				$returnvalue['@dailyminrate']=$p->getvalue('dailyminrate');
					if(is_numeric($p->getvalue('weeklyminrate')))
						$returnvalue['@weeklyminrate']=$p->getvalue('weeklyminrate');
					elseif(is_numeric($returnvalue['@dailyminrate']))
						$returnvalue['@weeklyminrate']=$returnvalue['@dailyminrate'];
				$promo=$p->getpromocode($params['promocode']);
				$minrates=$p->getvalue('minrates');				
				if(strtotime($params['arrive'])&&strtotime($params['depart']))
				{
					$totals=$p->getratetotals($params['arrive'], $params['depart'], date('Y-m-d'), $params['promocode']);
					$returnvalue['@totalrate']=$totals['subtotal']['amount']+$totals['discount']['amount'];
					$returnvalue['@slashthroughtotalrate']=$totals['subtotal']['amount'];
					$from=strtotime($params['arrive']);
					$to=strtotime($params['depart']);
					$span=unixtojd($to)-unixtojd($from);
					$returnvalue['@dailyrate']=round($returnvalue['@totalrate']/$span,2);
					$returnvalue['@slashthroughdailyrate']=round($returnvalue['@slashthroughtotalrate']/$span,2);
				}
				
				$temp=array();
				$images=$p->getvalue('images');
				foreach($images as $image)
				{
					$temp[]=array(
						'@id'=>$image['id'],
						'#filename'=>$image['filename'],
						'#caption'=>$image['caption']
						);
				}
				$returnvalue['images']=$temp;
				$returnvalue['bedcount']=array();
				$bedrooms=$p->getvalue('bedcount');

                                $pattern = '/Bedding (\d+)/';

                                foreach ($bedrooms as $id => $bedroom ) {
                                    if ( preg_match( $pattern, $bedroom['name'], $matches) ) {
                                        $bedrooms[$id]['name'] = 'Bedroom ' . $matches[1];
                                    }
                                }

				foreach($bedrooms as $bedroomid=>$bedroom)
				{
					$temp=array('@id'=>$bedroomid, '#name'=>$bedroom['name'], 'beds'=>array());
					foreach($bedroom['beds'] as $bedid=>$bed)
					{
						$temp['beds'][]=array('@id'=>$bedid, '@count'=>$bed['count'], '#name'=>$bed['name']);
					}
					$returnvalue['bedcount'][]=$temp;
				}
				$returnvalue['ratetable']=array();
				$ratetable=$p->getvalue('ratetable');
				$minstayline=array(
						'@startdate'=>$ratetable[0]['startdate'], 
						'@enddate'=>$ratetable[0]['enddate'],
						//'@dailyrate'=>$returnvalue['@dailyminrate'],
						//'@weeklyrate'=>$returnvalue['@weeklyminrate']*7,
						'@minstay'=>$ratetable[0]['minstay'],
						'@sattosat'=>$ratetable[0]['sattosat']
						);
				$arrivetest=strtotime($params['arrive']);
				if($arrivetest==0)
					$arrivetest=strtotime(date('Y-m-d'));
				$returnvalue['@promoweeklyminrate'] = $p->getvalue('promoweeklyminrate');
				$returnvalue['@promodailyminrate'] = $p->getvalue('promodailyminrate');
				foreach($ratetable as $rateline)
				{
					$temp=array(
						'@startdate'=>$rateline['startdate'], 
						'@enddate'=>$rateline['enddate'],
						'@dailyrate'=>$rateline['dailyrate'],
						'@weeklyrate'=>$rateline['weeklyrate'],
						'@minstay'=>$rateline['minstay'],
						'@sattosat'=>$rateline['sattosat']
						);
					if($arrivetest>0 && $arrivetest>=strtotime($rateline['startdate'])&&$arrivetest<=strtotime($rateline['enddate']))
						$minstayline=$temp;
					$returnvalue['ratetable'][]=$temp;
					$returnvalue['displayrateline']=$minstayline;
				}
				
				
					
				$temp=array
				(
					'#promocode'=>$promo['promocode'],
					'@type'=>$promo['discounttypes_id'],
					'@amount'=>$promo['discountamount'],
					'#name'=>$promo['promotion_name'],
					'#short'=>$promo['short_desc']
					);
				$returnvalue['promo']=$temp;
			}else{
				throw new Exception('Property not found.',9999);
			}
		}
		catch(Exception $e)
		{
			//add error handling
			throw $e;
		}
		return $returnvalue;
	}
	
	function _getpropertyimages()
	{
		try
		{
			$returnvalue=array();
			$imagelist=new ImageList($_REQUEST['propertyid'], $this->db);
			$images=$imagelist->getlist();
			$images=$images[$_REQUEST['propertyid']];
			$temp=array();
			foreach($images as $image)
			{
				$temp[]=array(
					'@id'=>$image['id'],
					'#filename'=>$image['filename'],
					'#caption'=>$image['caption']
					);
			}
			$returnvalue['images']=$temp;
			$returnvalue['status']='SUCCESS';
			$this->returnValue=$returnvalue;
			return $this->returnValue;
		}
		catch(Exception $e)
		{
			$returnvalue['status']='ERROR';
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
		
	}
	
	function _getpropertycalendar()
	{
		try
		{
			$property=new Property('',$this->db);
			$property->getpropertybyid($_REQUEST['propertyid']);
			$start=date('U');
			//$end=mktime(0,0,0,date('m',$start)+6, 1, date('Y', $start));
			$end=strtotime($property->getdata('lastvalidrate'));
			$end=mktime(0,0,0,date('m', $end), date('d', $end)+2, date('Y', $end));
			//$end=mktime(0,0,0,date('m',$start)+7,1,date('Y', $start));
			$promo=trim($_REQUEST['promocode']);
			$rates=$property->getrates(date('Y-m-01', $start), date('Y-m-d', $end), $promo);
			$this->returnValue=array();
			$lastdate=0;

			foreach($rates as $ratedate=>$rate)
			{
				$checkout=0;
				$checkin=0;
				
				$temp=array();
				foreach($rate as $ratetype)
					$temp[]=array(
						'@rateplanid'=>$ratetype['rateplans_id'],
						'@minstay'=>$ratetype['minstay'],
						'@maxstay'=>$ratetype['maxstay'],
						'@amount'=>$ratetype['amount']
						);
				$jd=unixtojd(strtotime($ratedate));
				if(count($temp)>0 && $jd-$lastdate>1) //first available date after a gap
					$checkin=1;
				if(count($temp)==0 && $lastdate>0 && ($jd-$lastdate)==1)
				{
					$checkout=1;
				}
				$this->returnValue[]=array(
					'@date'=>$ratedate,
					'@checkoutonly'=>$checkout,
					'@checkinonly'=>$checkin,
					'rates'=>$temp
					);
				if(count($temp))
					$lastdate=$jd;
			}
			return $this->returnValue;
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
	}
	
	function _getviews()
	{
		//home and villa counts are not accurate beyond 1.  converting to 1 or 0 to avoid confusion
		try
		{
			$viewlist=new ViewList('',$this->db);
			$values=$viewlist->getlist();
			$this->returnValue=array();
			foreach($values as $value)
			{
				if($value['homes']>0 || $value['villas']>0) 
					$this->returnValue[]=array(
						'@id'=>$value['id'],
						'@homes'=>($value['homes']>0?1:0),
						'@villas'=>($value['villas']>0?1:0),
						'#name'=>$value['name']
						);
			}
			return $this->returnValue;
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
	}
	
	function _getamenities()
	{
		try
		{
			$list=new AmenityList('',$this->db);
			$values=$list->getlist();
			$this->returnValue=array();
			foreach($values as $value)
			{
				$this->returnValue[]=array(
					'@id'=>$value['id'],
					'#name'=>$value['name']
					);
			}
			return $this->returnValue;
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
	}
	
	function _getlocations()
	{
		try
		{
			$list=new LocationList('',$this->db);
			$values=$list->getlist();
			$this->returnValue=array();
			foreach($values as $value)
			{
				$this->returnValue[]=array(
					'@id'=>$value['id'],
					'@homes'=>$value['homes'],
					'@villas'=>$value['villas'],
					'#name'=>$value['name']
					);
			}
			return $this->returnValue;
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
	}
	
	/***
	save the selected propertyid, check in, check out and optional promo code
	return success if the property is available or failure+error message in generic location if it is not.
	keeping this a separate function in case we ever allow multiple bookings/checkout
	***/
	function _addbookingtocart()
	{
		try
		{
			$this->returnValue=array();
			if(isset($_REQUEST['propertyid']))
			{

				$_SESSION[SESS_NAME]['bookingcart']=array('carpasses'=>1);
				$cart=new BookingCart($_SESSION[SESS_NAME]['bookingcart']);
				$cart->setproperty($_REQUEST['propertyid'], $_REQUEST['arrive'], $_REQUEST['depart'], $_REQUEST['promocode']);

				$service = new Weblink;

				$unit_number = Property::getUnitNumber( $_REQUEST['propertyid'] );

				$arrive = $cart->getdata('arrive');
				$depart = $cart->getdata('depart');

				$number_nights = Util::getNumberOfNightsBetween($arrive, $depart);
				$adults = 2;
				$kids = 0;

				$promo = $cart->getdata('promocode');

			/*
				$ows=new OWS();
				$params=array(
						'arrive'=>$cart->getdata('arrive'), 
						'depart'=>$cart->getdata('depart'), 
						'ows_id'=>$cart->getdata('ows_id'),
						'rateplan'=>$cart->getdata('rateplan'),
						'promocode'=>$cart->getdata('promocode'));
			 */
				$availability = $service->confirmavailability($unit_number, $arrive, $number_nights, $adults, $kids, $promo); 

				#var_dump($service->getdebuginfo());
				#exit;

				$errors = $service->geterrormessages();

				if ( !$availability || (is_array($errors) && !empty($errors)) ) {

					$error = $errors[0];
					throw new Exception($error, 9995);
				}

				if($availability->isAvailable())
				{
					$cart->setavailability($availability);

                                        $cart->adddata(array(
                                            'promocode' => $promo,
                                        ), array('promocode'));

					$cart->savecart();
					$this->returnValue=array('status'=>'SUCCESS');
				}
				else
				{
                                    /*
					if($availability->getvalue('errorcode')=='CTA_RESTRICTION_ON_IN_DATE') //attempt to single out availability errors due to sat-sat requirement.
						Throw new Exception('A Saturday to Saturday stay is required during this season. Please check your dates and try again', 9986);
					else
					*/
					throw new Exception('Unit is not available for the selected dates', 9995);
					$this->returnValue=array('status'=>'FAILURE');
				}
			}
			return $this->returnValue;
		}
		catch(Exception $e)
		{
                            #var_dump($service->getdebuginfo());
                            #exit;
                    /*
                     * New possible V12 exceptions
                     *
                     *
                     * Required parameters strUserId,strPassword are missing.
                     * Required parameter strCOID is missing.
                     * Login failure.
                     * Requested company is not authorised.
                     * No permission to access this function.
                     * This property is not authorised.
                     * Property is not available for booking.
                     * Required Parameter Property ID is missing.
                     * Required Parameter check in date is missing.
                     * Required Parameter num of adults is missing.
                     * Invalid Checkin Date.
                     * Required Parameter number of nights missing.
                     * The total number of guests exceeds maximum property occupancy.Please choose another
                     * property.
                     * There was an error processing your request. Communication with the reservation system could
                     * not be established. We have your information and will contact you shortly.
                     * There was an error processing your request.We have your information and will contact you
                     * shortly.
                     * Property is not available for the requested dates in ISILink.
                     * The property you requested was just reserved. Please choose another property.
                     */
			switch($e->getmessage())
			{
			case 'PRIOR_STAY':
				$message='Unit is not available for the selected dates';
				break;
                        case 'Error Fetching http headers':
                            $message = 'Sorry, online booking is currently experiencing problems. We\'d love to help you over the phone. Please give us a call at <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span>. Our call center hours are 7 a.m. to 7 p.m. 7 days a week.';
                            break;
			default:
				$message=$e->getmessage();
			}
			if(get_class($e)=='SoapFault')
				$message='Booking service error: '.$message;
			$number=$e->getCode();
			$this->addGlobalMessage($message,$number,'error');
			$loc=ModuleParameter::getParameterIfSet('location_failure','error',$this);
			header('Location:/booking/'.$loc.'?propertyid='.$_REQUEST['propertyid'].'&arrive='.$_REQUEST['arrive'].'&depart='.$_REQUEST['depart'].'&promocode='.$_REQUEST['promocode']);
			exit();
		}
	}
	
	/***
	return property info, user info, check in, check out, promo code, etc.
	***/
	function _getbookingcart()
	{
		try
		{
			if(isset($_REQUEST['confirmation']))
			{
				$fakesession=array();
				$cart=$this->retrievebookinginternal($_REQUEST['email'], $_REQUEST['confirmation'], $fakesession);
			}
			else
			{
				$cart=new BookingCart($_SESSION[SESS_NAME]['bookingcart']);
				$cart->loadcart();
			}

			$images=$cart->getdata('images');
			$this->returnValue=array(
                                'promocode' => $cart->getdata('promocode'),
				'property'=>array(
					'@propertyid'=>$cart->getdata('id'),
					'#name'=>$cart->getdata('property_name'),
					'@typeid'=>$cart->getdata('typeid'),
					'@vacationassurance'=>$cart->getdata('propertyvacationassurance'),
					'@wholesale'=>$cart->getdata('wholesale'),
					'#address'=>$cart->getdata('propertyaddress'),
					'#owsid'=>$cart->getdata('propertyowsid'),
					'#image'=>$images[0]['filename']
					),
				'booking'=>array(
					'@propertyid'=>$cart->getdata('id'),
					'@arrive'=>$cart->getdata('arrive'),
					'@depart'=>$cart->getdata('depart'),
					'@adults'=>$cart->getdata('adults'),
					'@children'=>$cart->getdata('children'),
					'@carpasses'=>$cart->getdata('carpasses'),
					'@vacationassurance'=>$cart->getdata('vacationassurance'),
					'#requests'=>$cart->getdata('requests'),
					'#confirmation'=>$cart->getdata('confirmation'),
					'#rateplan'=>$cart->getdata('rateplan'),
					'rate'=>array()
					),
				'user'=>array(
					'#firstname'=>$cart->getdata('firstname'),
					'#lastname'=>$cart->getdata('lastname'),
					'#phone'=>$cart->getdata('phone'),
					'#email'=>$cart->getdata('email')
					),
				'address'=>array(
					'#address1'=>$cart->getdata('address1'),
					'#address2'=>$cart->getdata('address2'),
					'#city'=>$cart->getdata('city'),
					'#state'=>$cart->getdata('state'),
					'#zipcode'=>$cart->getdata('zipcode'),
					'#country'=>$cart->getdata('country'),
					),
				'formdata'=>array(
					'#firstname'=>$cart->getformdata('firstname'),
					'#lastname'=>$cart->getformdata('lastname'),
					'#phone'=>$cart->getformdata('phone'),
					'#email'=>$cart->getformdata('email'),
					'#email_confirm'=>$cart->getformdata('email_confirm'),
					'#address1'=>$cart->getformdata('address1'),
					'#address2'=>$cart->getformdata('address2'),
					'#city'=>$cart->getformdata('city'),
					'#state'=>$cart->getformdata('state'),
					'#zipcode'=>$cart->getformdata('zipcode'),
					'#country'=>$cart->getformdata('country'),
					'#adults'=>$cart->getformdata('adults'),
					'#children'=>$cart->getformdata('children'),
					'#carpasses'=>$cart->getformdata('carpasses'),
					'#vacationassurance'=>$cart->getformdata('vacationassurance'),
					'#requests'=>$cart->getformdata('requests'),
					)
				);
			$temp=array();
			$images=$cart->getdata('images');
			foreach($images as $image)
			{
				$temp[]=array(
					'@id'=>$image['id'],
					'#filename'=>$image['filename'],
					'#caption'=>$image['caption']
					);
			}
			$this->returnValue['booking']['images']=$temp;
			$ratebreakdown=$cart->getdata('ratetotals');
			$p = new Property();
			$p->getpropertybyid($cart->getdata('id'));
			$total_rates = $p->getratetotals($cart->getdata('arrive'), $cart->getdata('depart'), date('Y-m-d'),'', false, true);
			$ratebreakdown['subtotal']['amount'] = $total_rates['subtotal']['amount'];
			foreach($ratebreakdown as $name=>$rate)
			{
				$this->returnValue['booking']['rate'][]=array(
					'@id'=>$rate['id'],
					'#name'=>$rate['label'],
					'#amount'=>$rate['amount'],
					'#duetoday'=>(isset($rate['duetoday']) ? $rate['duetoday'] : 0)
					);
			}
			$this->returnValue['booking']['@dailyrate']=round(($ratebreakdown['subtotal']['amount']+$ratebreakdown['discount']['amount'])/$cart->getdata('stayspan'),2);
			return $this->returnValue;
		}
		catch(Exception $e)
		{
			$this->addGlobalmessage('Could not retrieve cart',3,'error');
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
			$loc=ModuleParameter::getParameterIfSet('location_failure','error',$this);
			//print_r($_SESSION);
			//print_r($e);
			//die();
			header('Location: /booking/'.$loc.'?propertyid='.$_REQUEST['propertyid'].'&arrive='.$_REQUEST['arrive'].'&depart='.$_REQUEST['depart'].'&promocode='.$_REQUEST['promocode']);
			exit();
		}
	}
	
	/**
	add user name, email, etc to cart data
	**/
	function _adduserdata()
	{
        if ($_SERVER['REMOTE_ADDR'] == '174.126.37.114') {
            echo '<pre>';
            var_dump($_SESSION[SESS_NAME]);
            exit;
        }

		try
		{
			$this->returnValue=array();
			if(isset($_REQUEST['firstname']))
			{
				$addvalues=array('promocode', 'firstname', 'lastname', 'email', 'email_confirm', 'phone', 'adults', 'children', 'carpasses', 'vacationassurance', 'requests');
				$cart=new BookingCart($_SESSION[SESS_NAME]['bookingcart']);
				$cart->loadcart();			
				if($cart->adddata($_REQUEST, $addvalues))
				{
					$cart->savetoabandonment();
					$cart->savecart();
					$this->returnValue=array('status'=>'SUCCESS');
				}
				else
				{
					$cart->saveformdata();
					$i=1;
					foreach($cart->geterrors() as $error)
					{
						$this->addGlobalMessage($error,$i,'error'); //error numbers can't match because shannon
						$i++;
					}
					Throw new Exception('Could not save user data', 0);
					//$this->returnValue=array('status'=>'FAILURE', 'messages'=>$cart->geterrors());
				}
			}
			return $this->returnValue;
		}
		catch(Exception $e)
		{
			$message=$e->getmessage();
			$number=$e->getCode();
			$this->addGlobalMessage($message,$number,'error');
			$loc=ModuleParameter::getParameterIfSet('location_failure','guestinfo',$this);
			header('Location:/booking/'.$loc);
			exit();
		}
	}
	
	function _addaddressdata()
	{
		try
		{
			$addvalues=array('address1', 'address2', 'city', 'state', 'zipcode', 'country');
			$cart=new BookingCart($_SESSION[SESS_NAME]['bookingcart']);
			$cart->loadcart();
			if($cart->adddata($_REQUEST, $addvalues))
			{
				$cart->savecart();
				$this->returnValue=array('status'=>'SUCCESS');
			}
			else
			{
				$cart->saveformdata();
				$i=1;
				foreach($cart->geterrors() as $error)
				{
					$this->addGlobalMessage($error,0,'error');
					$i;
				}
				Throw new Exception('Could not save address', 0);
			}
			return $this->returnValue;
		}
		catch(Exception $e)
		{
			$number=$e->getCode();
			$message=$e->getMessage();
			$this->addGlobalMessage($message,$number,'error');
			$loc=ModuleParameter::getParameterIfSet('location_failure','payment',$this);
			header('Location:/booking/'.$loc);
			exit();
		}
	}
	
	function _createcartfromrequest()
	{
		try
		{
			$cart=new BookingCart($_SESSION[SESS_NAME]['bookingcart']);
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
		
	}
	
	/**
	return all data that needs to be passed to the next page so booking is not cookie/session dependent
	**/
	function _getbookingfields()
	{
		try
		{
			$cart=new BookingCart($_SESSION[SESS_NAME]['bookingcart']);
			$cart->loadcart();
			$this->returnValue=array();
			$fields=array();
			if(is_numeric($cart->getdata('id')))
				$fields=array_merge($fields, $this->cartvars['bookingdata']);
			if(strlen($cart->getdata('firstname')))
				$fields=array_merge($fields, $this->cartvars['userdata']);
			if(strlen($cart->getdata('address1')))
				$fields=array_merge($fields, $this->cartvars['addressdata']);
	
			foreach($fields as $field)
			{
				$temp=array('#name'=>'booking['.$field.']', '#value'=>$cart->getdata($field));
				$this->returnValue[]=$temp;
			}
			return $this->returnValue;
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
	}
	
	function _getcontent()
	{
		try
		{
			$this->returnValue=array();
			$content=new Content();
			$content->getcontentbyid(ModuleParameter::getParameter('contentid',$this));
			$this->returnValue['#content']=$content->getdata('content');
			return $this->returnValue;
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
	}
	
	/**
	clear all data in cart.  either user selected a different property or booking was completed
	**/
	function _clearbookingcart()
	{
		try
		{
			$cart=new BookingCart($_SESSION[SESS_NAME]['bookingcart']);
			$cart->cleardata();
			$cart->savecart();
		}
		catch(Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
		}
	}
	
	function checkcaptcha()
	{
		$captchatype = ModuleParameter::getParameterIfSet('captcha', 'none', $this);
		switch($captchatype)
		{
			case 'none':
				return true;
				break;
			case 'freecap':
				if($_SESSION['freecap_word_hash']!=(md5($_POST['the_word'])) || $_SESSION['freecap_word_hash'] == false)
					return false;
				break;
			case 'recaptcha':
			default:
				require_once(CDE_PATH.'/classes/Recaptcha.Class.php');
				if (!isset($_POST['g-recaptcha-response']))
					return false;
				elseif(!recaptcha::check($_POST, true))
					return false;
				break;
		}
		return true;
	}
	

	
	function _emailbooking()
	{
		try
		{
			if(!$this->checkcaptcha())
				throw new Exception('Please enter the correct captcha text.');
			$fakesession=array();
			$cart=$this->retrievebookinginternal($_REQUEST['email'], $_REQUEST['confirmation'], $fakesession);
			$this->emailcart($cart, $_REQUEST['emailto'], false);
			$this->addGlobalMessage('Your reservation has been emailed.',0,'message');
			$loc=ModuleParameter::getParameterIfSet('location_success',$loc,$this);
			header('Location:/booking/'.$loc.'?email='.$_REQUEST['email'].'&confirmation='.$_REQUEST['confirmation']);
			exit();
		}
		catch( Exception $e)
		{
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
			$loc=ModuleParameter::getParameterIfSet('location_failure',$loc,$this);
			header('Location:/booking/'.$loc.'?email='.$_REQUEST['email'].'&confirmation='.$_REQUEST['confirmation']);
			exit();
		}
	}
	
	function retrievebookinginternal($email, $confirmation, &$session)
	{
		$cart=new BookingCart($session);
		$cart->loadfromdb($email, $confirmation);
		return $cart;
	}
	
	function emailcart($cart, $to, $cc=true)
	{
		$message=$this->generateemailfromcart($cart);
		$mail=new Mailer($to, 'noreply@palmettodunes.com', 'noreply@palmettodunes.com', 'Your reservation for '.$cart->getdata('property_name'), $message);
		if($cc)
		{
			#$mail->addBCC('KMorris@PalmettoDunes.com', 'Karen Morris');
			//$mail->addBCC('bobby.anderson@sabre.com', 'Bobby Anderson');
			//$mail->addBCC('michael.alaimo@sabre.com', 'Michael Alaimo');
 			//$mail->addBCC('dpuryear@palmettodunes.com', 'Devon Puryear');
			//$mail->addBCC('Mroyer@palmettodunes.com', 'Michael Royer');
			
			$mail->addBCC('dhausher@palmettodunes.com', 'Dhausher');
			$mail->addBCC('vcastaneda@palmettodunes.com', 'vcastaneda@palmettodunes.com');
			$mail->addBCC('esheets@palmettodunes.com', 'Erica Hood Sheets');
			$mail->addBCC('15521@navismarketing.com', '15521@navismarketing.com');
			$mail->addBCC('jdelsandro@palmettodunes.com', 'jdelsandro@palmettodunes.com');
			
		}
		if(!$mail->SendMail())
		{
			try
			{
				$file = new SplFileObject(APP_ERROR_LOG.'/emailcart-'.date('Y-m-d').'.log', 'a');
				if ($file->isWritable())
					$file->fwrite('Confirmation #: '.$cart->getdata('confirmation').', Email: '.$cart->getdata('email').', OWS ID: '.$cart->getdata('propertyowsid').', Error: '.$mail->ErrorInfo.PHP_EOL);
			}
			catch(RuntimeException $e)
			{
				
			}
			Throw new Exception('Unable to send email');
		}
		else
		{
			if($cc)
			{
				try
				{
					$mail=new Mailer('15521@navismarketing.com', $to, $to, 'Your reservation for '.$cart->getdata('property_name'), $message);
					if(!$mail->SendMail())
					{
						$file = new SplFileObject(APP_ERROR_LOG.'/navisemailcart-'.date('Y-m-d').'.log', 'a');
						if ($file->isWritable())
							$file->fwrite('Confirmation #: '.$cart->getdata('confirmation').', Email: '.$cart->getdata('email').', OWS ID: '.$cart->getdata('propertyowsid').', Error: '.$mail->ErrorInfo.PHP_EOL);
					}
				}
				catch(Exception $e)
				{
					//do not break checkout because we couldn't email navis
				}
			}
		}
		return true;
	}
	
	function generateemailfromcart($cart)
	{
		if($cart->getdata('wholesale')==1)
		{
			$introid=10;
			$footerid=11;
			$message=file_get_contents('templates/email/confirmation.html');
		}
		else
		{
			$introid=8;
			$footerid=9;
			$message=file_get_contents('templates/email/confirmation.html');
		}
		$images=$cart->getdata('images');
		$prices=$cart->getdata('ratetotals');
		$promocode=$cart->getdata('promocode');
		//$prices=$cart->getdata('displaytotals');
		$pricearray=array();
		$total=0;
		$duetoday=0;
		foreach($prices as $pname=>$price)
		{
			if(is_numeric($price['amount']) && $price['amount']!=0)
			{
				$c='';
				if($price['amount']<0)
					$c=' color: #b6450e;';
				$pricearray[]='<tr><td style="font-family: \'Trebuchet MS\', Arial, Helvetica, sans-serif; font-weight: bold; border-bottom: 5px solid #fff; '.$c.'" width="130">'.
					$price['label'].'</td><td style="'.$c.'">'.number_format($price['amount'], 2).'</td></tr>';
				$total+=$price['amount'];
				if (isset($price['duetoday']))
					if($promocode == 'CYBERMONDAY16' || $promocode == 'BLACKFRIDAY16' || $promocode == 'BFF16' || $promocode == 'CYBERMONDAY17' || $promocode == 'BLACKFRIDAY17' || $promocode == 'BFF17'){
						$duetoday =99;
					}elseif($promocode == '199DOWNPD' || $promocode == 'PDSPRING' || $promocode == 'SPRING17' || $promocode == 'PD199DOWN'){
						$duetoday =199;
					}elseif($promocode == 'ILOVEPD17'){
						$duetoday =250;
					}else{
						$duetoday += $price['duetoday'];
					}
			}
		}
		$pricearray[]='<tr><td style="font-family: \'Trebuchet MS\', Arial, Helvetica, sans-serif;font-weight: bold; border-bottom: 5px solid #fff; color: #003f72; text-transform: uppercase;" width="130">Total:</td><td style="color: #003f72;"><strong>USD '.
			number_format($total,2).'</strong></td></tr>';
		if($duetoday>0)
			$pricearray[]='<tr><td style="font-family: \'Trebuchet MS\', Arial, Helvetica, sans-serif;font-weight: bold; border-bottom: 5px solid #fff; color: #003f72; text-transform: uppercase;" width="130">Due Today:</td><td style="color: #003f72;"><strong>USD '.
				number_format($duetoday,2).'</strong></td></tr>';
		$price='<table cellpadding="0" cellspacing="0" border="0">'.implode($pricearray).'</table>';
		switch($cart->getdata('typeid'))
			{
			case 1:
				$type='Home';
				break;
			case 2:
				$type='Villa';
				break;
			}
		$arrive=strtotime($cart->getdata('arrive'));
		$depart=strtotime($cart->getdata('depart'));
		$span=unixtojd($depart)-unixtojd($arrive);
		$content=new Content(1,$this->db);
		$content->getcontentbyid($introid);
		$intro=$content->getdata('content');
		$content->getcontentbyid($footerid);
		$footer=$content->getdata('content');
		$replace=array(
			'id'=>$cart->getdata('id'),
			'image'=>PROPERTY_IMAGE_URL.'/properties/'.$cart->getdata('id').'/images/'.$images[0]['filename'],
			'name'=>$cart->getdata('property_name'),
			'type'=>$type,
			'arrive'=>date('m/d/Y', $arrive),
			'depart'=>date('m/d/Y', $depart),
			'nights'=>$span,
			'prices'=>$price,
			'firstname'=>$cart->getdata('firstname'),
			'lastname'=>$cart->getdata('lastname'),
			'email'=>$cart->getdata('email'),
			'phone'=>$cart->getdata('phone'),
			'adults'=>$cart->getdata('adults'),
			'children'=>$cart->getdata('children'),
			'carpasses'=>$cart->getdata('carpasses'),
			'requests'=>$cart->getdata('requests'),
			'confirmation'=>$cart->getdata('confirmation'),
			'intro'=>$intro,
			'footer'=>$footer,
			'promocode'=>$promocode
			);
		
		foreach($replace as $token=>$value)
				$message=str_replace('{%'.$token.'%}', $value, $message);
		return $message;
	}
	
	function _emailunit()
	{
		try
		{
			if(!$this->checkcaptcha())
			{
				Throw new Exception('Captcha Failed', 1);
			}
			$contentid=7;
			$property=new Property();
			$property->getpropertybyid($_REQUEST['propertyid']);
			$images=$property->getvalue('images');
			switch($property->getvalue('property_type'))
			{
			case 1:
				$type='Home';
				break;
			case 2:
				$type='Villa';
				break;
			}
			$bookdate=date('Y-m-d');
			$arrive=strtotime($_REQUEST['arrive']);
			$depart=strtotime($_REQUEST['depart']);
			
			/*forcing these to 0 until someone removes the incorrect dates that keep getting submitted to this function*/
			//$arrive=0;
			//$depart=0;
			
			$promocode=trim($_REQUEST['promocode']);
			$span=unixtojd($depart)-unixtojd($arrive);

			if($arrive>0)
			{
				$prices=$property->getratetotals(date('m/d/Y', $arrive), date('m/d/Y', $depart), $bookdate, $_REQUEST['promocode']);
				$pricearray=array();
				$total=0;
				foreach($prices as $pname=>$price)
				{
					if(is_numeric($price['amount']) && $price['amount']!=0)
					{
						$c='';
						if($price['amount']<0)
							$c=' color: #b6450e;';
						$pricearray[]='<tr><td style="font-weight: bold; border-bottom: 5px solid #fff;'.$c.'" width="130">'.
							$price['label'].'</td><td style="'.$c.'">'.number_format($price['amount'], 2).'</td></tr>';
						$total+=$price['amount'];
					}
				}
			}
			$ratetable=$property->getvalue('ratetable');
			if($arrive>0)
				$ratedate=$arrive;
			else
				$ratedate=date('U');
				$dailymin='Unavailable';
				$weeklymin='Unavailable';
			foreach($ratetable as $rateline)
			{
				if(strtotime($rateline['startdate'])<=$ratedate && strtotime($rateline['enddate'])>=$ratedate)
				{
					$dailymin=$rateline['dailyrate'];
					$weeklymin=$rateline['weeklyrate'];
				}
				$returnvalue['ratetable'][]=$temp;
			}
			if($arrive>0)
			{
				$pricearray[]='<tr><td style="font-weight: bold; border-bottom: 5px solid #fff; color: #003f72; text-transform: uppercase;" width="120">Total:</td><td style="color: #003f72;"><strong>USD '.
					number_format($total,2).'</strong></td></tr>';
			}
			else
				$pricearray[]='<tr><td>No dates were selected.</td></tr>';
			$price='<table cellpadding="0" cellspacing="0" border="0">'.implode($pricearray).'</table>';
			$content=new Content();
			$content->getcontentbyid($contentid);
			$replace=array(
				'id'=>$property->getvalue('id'),
				'image'=>PROPERTY_IMAGE_URL.'/properties/'.$property->getvalue('id').'/images/'.$images[0]['filename'],
				'name'=>$property->getvalue('property_name'),
				'type'=>$type,
				'arrive'=>($arrive>0?date('m/d/Y', $arrive):'Not Selected'),
				'depart'=>($depart>0?date('m/d/Y', $depart):'Not Selected'),
				'nights'=>$span,
				'description'=>$property->getvalue('short_description'),
				'prices'=>$price,
				'dailymin'=>$dailymin,
				'weeklymin'=>$weeklymin,
				'propertylink'=>BOOKING_URL.'/vacation-rentals/hilton-head-rental-units?propertyid='.
					$property->getvalue('id').'&arrive='.date('m/d/Y', $arrive).'&depart='.date('m/d/Y', $depart).'&promocode='.urlencode($promocode),
				'intro'=>$content->getdata('content'),
				'promocode'=>$promocode
				);
			$message=file_get_contents('templates/email/emailunit.html');
			foreach($replace as $token=>$value)
				$message=str_replace('{%'.$token.'%}', $value, $message);
			$mail=new Mailer($_REQUEST['email'], 'noreply@palmettodunes.com', 'noreply@palmettodunes.com', 'Palmetto Dunes property info -'.$property->getdata('property_name'), $message);
			if($mail->SendMail())
			{
				$this->addGlobalmessage('Thank you.  Your email has been sent.',0, 'message');
				$loc='/vacation-rentals/hilton-head-rental-units';
				$loc=ModuleParameter::getParameterIfSet('location_success',$loc,$this).'?propertyid='.$_REQUEST['propertyid'].'&arrive='.$_REQUEST['arrive'].'&depart='.$_REQUEST['depart'].'&promocode='.$_REQUEST['promocode'];
				header('Location:/booking/'.$loc);
			}
			else
			{
				$loc='/vacation-rentals/hilton-head-rental-units';
				$loc=ModuleParameter::getParameterIfSet('location_failure',$loc,$this).'?propertyid='.$_REQUEST['propertyid'].'&arrive='.$_REQUEST['arrive'].'&depart='.$_REQUEST['depart'].'&promocode='.$_REQUEST['promocode'];
				header('Location:/booking/'.$loc);
			}
			
			// Send to Navis Reach
			if($_REQUEST['optin'] == '1') 
			{

				if(isset($_REQUEST['fromemail']))
					$form_data['email_address'] = $_REQUEST['fromemail'];
				
				$form_data['subscription_lists']  = 'Resort Special Offers';
				
				$reach = new NavisReach();
				$response = $reach->addUpdateSubscriber( $form_data );			
			}
			
			exit();
		}
		catch(Exception $e)
		{
			$loc='/vacation-rentals/hilton-head-rental-units';
			$this->addGlobalmessage($e->getMessage(), $e->getCode(), 'error');
			$loc=ModuleParameter::getParameterIfSet('location_failure',$loc,$this).'?propertyid='.$_REQUEST['propertyid'].'&arrive='.$_REQUEST['arrive'].'&depart='.$_REQUEST['depart'].'&promocode='.$_REQUEST['promocode'];
			header('Location:/booking/'.$loc);
			exit();
		}
	}
	
	function _starttimer()
	{
		$_SESSION['test']=microtime(true);
		return array();
	}
	
	function _endtimer()
	{
		$this->returnValue=array('@timer'=>(microtime(true)-$_SESSION['test']));
		return $this->returnValue;
	}


        /*
         * New v12 methods
         *
         */

        /*
         * Validate the booking parameters
         * @param array $data An array of data
         * @return void
         */
        private function validateBookingFields($data) {
            $fields = array('country', 'city', 'first_name', 'last_name', 'address1', 'zip', 'phonenumber', 'email', 'phone_home', 'exp_month', 'exp_year');
            foreach ($fields as $field) {
                if ( !isset( $data[$field] ) || $data[$field] == '' ) {
                    throw new Exception('Missing value for required field: '. $field, 9997);
                }
            }
        }

	/**
	create booking and add conf id to cart or generate error message.  redirect.
	expecting cc info to be passed directly to this
	**/
	public function _book()
	{
		try
		{
			$errormsg='';
			
			$cart=new BookingCart($_SESSION[SESS_NAME]['bookingcart']);
			$cart->loadcart();
			if($_REQUEST['cancellationpolicy']!=1)
				$errormsg.='You must indicate that you have understood the cancellation policy.<br />';

                        $country = $cart->getdata('country');
                        $city = $cart->getdata('city');
                        $state = $cart->getdata('state');
                        $address1 = $cart->getdata('address1');
                        $address2 = $cart->getdata('address2');
                        $zip = $cart->getdata('zipcode');

                        $exp_month = $_REQUEST['cardexpmonth'];
                        $exp_year = $_REQUEST['cardexpyear'];

			if(!$cart->getdata('wholesale'))
			{
				//if($_REQUEST['rentalagreement']!=1)
				//	$errormsg.='You must indicate that you agree to the Guest Vacation Rental Agreement.<br />';
	
				// Verify CC expiration date
				$m = round($_REQUEST['cardexpmonth']);
				$exp = round(round($_REQUEST['cardexpyear']) . ($m < 10 ? '0' : '') . $m);
				$today = round(date('Ym'));
				if ($exp < $today) {
					$errormsg.='Credit Card Expiration Date must be a future date.<br />';
				}

                        // Hardcode wholesale stuff like it was done for Opera
                        } else if ( $cart->getdata('wholesale') == 1 ) {

                            $_REQUEST['cardnumber'] = '4111111111111111';
                            $_REQUEST['cardtype'] = 'VISA';
                            $_REQUEST['cardname'] = 'Wholesale Fake Card';
                            $country = 'US';
                            $city = 'Hilton Head Island';
                            $state = 'SC';
                            $address1 = 'Wholesale Booking';
                            $address2 = '';
                            $zip = '29928';
                            $exp_month= date('m');
                            $exp_year= date('Y');

                        }

			if(strlen($errormsg))
				Throw new Exception($errormsg, 9988);


			$errors=array();

			try
			{
				if($cart->savetodb())
					$cart->savecart();
			}
			catch(Exception $e)
			{
				//don't break the system trying to log data
			}

                        // Use a CC token generator if enabled

                        if ( defined('ENABLE_CCTOKENS')  && ENABLE_CCTOKENS ) {
                            $token_generator = new TokenGenerator;
                            $cc_token = $token_generator->generateToken($_REQUEST['cardnumber']);

                            if ( !$cc_token ) {
                                // internal errors
                                //$errors = $token->getErrors();
                                throw new Exception('Please verify the credit card number is valid');
                            }

                        }

                        $availability = $cart->getavailability();

                        // This should be a stored object and we need this
                        if ( !is_object( $availability ) ) {
                            throw new Exception('Could not complete booking.');
                        }


                        // Get the array
                        $avail = $availability->getrawresult();

                        // Override the dbldues

                        $nights = Util::getNumberOfNightsBetween($cart->getdata('arrive'), $cart->getdata('depart'));

                        $children = $cart->getdata('children');

                        // default to 0
                        if ( $children == null ) {
                            $children = 0;
                        }

                        // Tack the # of car passes into requests/comments
                        $requests = $cart->getdata('requests');

                        $requests.= "Car Passes:" . $cart->getdata('carpasses') . 'x.';

			$rates=$cart->getdata('ratetotals');

                        $total = 0;
                        foreach($rates as $t)
                            if(is_numeric($t['amount']))
                                $total+=$t['amount'];


                        // Test
                        if ( $total > 0 ) {
                            $avail['dblDues'] = $total;
                        }

			$data=array(
				'rateplan'=>$cart->getdata('rateplan'),
				'promocode'=>$cart->getdata('promocode'),
				'nightavg'=>($rates['subtotal']['amount']/$cart->getdata('stayspan')),
				'requests'=>$requests,
				'cardtype'=>$_REQUEST['cardtype'],
				'cardname'=>$_REQUEST['cardname'],
				'cancellationpolicy'=>$_REQUEST['cancellationpolicy'],
				'arrive'=>$cart->getdata('arrive'),

                                #'startdate' => $cart->getdata('arrive'),
				#'depart'=>$cart->getdata('depart'),
				#'ows_id'=>$cart->getdata('ows_id'),
                                
                                'unit_number' => $cart->getdata('unit_number'),
                                'adults'=>$cart->getdata('adults'),
                                'children'=>$children,
                                'number_nights' => $nights,

                                'first_name'=>$cart->getdata('firstname'),
                                'last_name'=>$cart->getdata('lastname'),
                                'address1'=>$address1,
                                'address2'=>$address2,
                                'city'=>$city,
                                'country'=>$country,
                                'zip'=>$zip,
                                'phonenumber'=>$cart->getdata('phone'),
                                'email'=>$cart->getdata('email'),
                                'middle_initial' => '',
                                'phone_home'=>$cart->getdata('phone'),
                                'phone_work'=>'',
                                'exp_month' => $exp_month,
                                'exp_year' => $exp_year,
                                'vacationassurance' => !!($cart->getdata('vacationassurance')),
                        );


                        // Pass the cc token if enabled
                        if ( defined('ENABLE_CCTOKENS')  && ENABLE_CCTOKENS ) {
                                $data['cc_token'] = $cc_token;
                        }

                        $this->validateBookingFields($data);

                        // V12 accepts just a 2 letter state code
                        $state_code = strlen($state) > 2 ? Util::getStateCode($state) : $state;

                        if ( !$state_code ) {
                            $province = $state;
                        }

                        if ( $state_code != '' ) {
                            $data['state'] = $state_code;
                            $data['province'] = '';
                        } else {
                            $data['province'] = $province;
                            $data['state'] = '';
                        }

                        try {
                            $service = new Weblink;

                            if($cart->getdata('wholesale'))
                                $booking = $service->createbooking($data, $avail, true);
                            else
                                $booking = $service->createbooking($data, $avail);

                            $errors = $service->geterrormessages();
                            if ( !$booking || (is_array($errors) && !empty($errors)) ) {

                                $error = $errors[0];
                                throw new Exception($error);
                            }

                        } catch ( Exception $e ) {
                            // Example 
                            // Error:You have already booked this property. Please contact the Property Management Company at lwatkins@homeaway.com, kha@homeaway.com for your reservation details.
                            throw new Exception('Could not complete booking: ' . $e->getMessage());	
                        }

			if($booking->successful())
			{

				$cart->adddata(array('confirmation'=>$booking->getvalue('confirmation')), array('confirmation'));
				$cart->savecart();
				try
				{
					$cart->savetodb();
					$cart->updateavailability();
				}
				catch(Exception $e)
				{
					//again don't break checkout trying to log data
				}
				try
				{
					$this->emailcart($cart, $cart->getdata('email'));
				}
				catch(Exception $e)
				{
					$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
				}
				$loc=ModuleParameter::getParameter('location_success',$this);
				header('Location:/booking/'.$loc);
				exit();
			}
			else
			{
				Throw new Exception('Could not complete booking');	
			}
		} catch(Exception $e) {
			$this->addGlobalMessage($e->getmessage(),$e->getCode(),'error');
			$loc=ModuleParameter::getParameterifSet('location_failure','payment',$this);
			header('Location:/booking/'.$loc);
			exit();
		}
	}

        /*
         * Public method to validate the pricing
         * @return void
         */
        public function _ajaxValidatePricing() {
            $result = $this->validatePricing();

            Util::ajax($result);
        }


        protected function validatePricing() {
            $params = $this->getReservationQueryParams();

            if ( count( $params['missing'] ) > 0 ) {
                foreach ($params['missing'] as $missingFieldName) {
                    echo $missingFieldName . ' is missing';
                    exit;
                }
            }

            $number_nights = Util::getNumberOfNightsBetween($params['values']['startdate'], $params['values']['enddate']);

            if ( $number_nights == 0 ) {
                echo 'Cannot be the same date';
                exit;
            }

            $weblink = new Weblink;

            // TODO: Add more error checking

            try {
                $reservationquery = $weblink->confirmavailability($params['values']['unit_number'], $params['values']['startdate'], $number_nights);

                if ( $reservationquery ) {

                    $result = array(
                        'success' => 1,
                        'info' => $reservationquery->getresult(),
                    );

                } else {
                    $result = array(
                        'success' => 0,
                    );
                }
            } catch ( Exception $e ) {
                    $result = array(
                        'success' => 0,
                    );
            }

            return $result;
        }


        /*
         * Get the params to do a reservation query
         * @param array @data
         * @return array
         */
        private function getReservationQueryParams($data=array()) {
            if ( empty ( $data ) ) {
                $data = $_REQUEST;
            }

            $required = array('unit_number', 'startdate', 'enddate');

            $missing = array();

            $values = array();

            foreach ($required as $field) {
                if ( !isset( $data[$field] ) ) {
                    $missing[] = $field;
                    continue;
                }

                // Date validation
                if ( $field == 'startdate' || $field == 'enddate' ) {
                    $date = strtotime($data[$field]);
                    if ( $date === false ) {
                        $missing[] = $field;
                        continue;
                    }
                }

                $values[$field] = $data[$field];
            }

            return array(
                'values' => $values,
                'missing' => $missing,
            );
        }

	
}
