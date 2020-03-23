<?php

namespace weblink;

class PropertyDescResponse extends WeblinkResponse
{
	protected $data=null;
	protected $amenities=false;
	protected $images=false;
	protected $selectedamenities=false;
	protected $view=false;
	protected $stories=false;
	protected $location=false;
	protected $bedding=false;

	protected $rawrates = array();
	protected $rates=array();
	
	protected $checkincheckout=array();

	protected $rawnights = array();
	protected $nights = array();

	protected $rawplanids=array();

	public $possibleamenities=array();
	private $cachedratetable=false;

	protected $internalpropid;
	protected $amenitylist;

	const resultnode = 'getPropertyDescResult';

	protected $property_id = false;

	/*
	 * @param object $result
	 * @param int $property_id
	 */
	public function __construct($result, $property_id)
	{
		parent::__construct($result, self::resultnode);
		$this->property_id = $property_id;
		$this->db = new \Db;
		$this->amenitylist = new \AmenityList;
		$this->extractpossibleamenities();
	}

        /*
         * Find the rateplan based on the code
         * @param string $name the name of the rateplan
         * @return mixed
         */
        private function findRatePlan($name) {

            // Code is UNIQUE so case doesnt matter
            $this->db->query('SELECT id FROM rateplans WHERE code="' . $name . '"');

            $rateplan = false;

            if ( $temp = $this->db->fetchassoc() ) {
                $rateplan = $temp['id'];
            }

            return $rateplan;
        }

        /*
         * Parse the array of rates and return them
         * @return mixed
         */
        public function getrawrates() {

            if ( $this->hasRates() ) {

                $rates = $this->data['clsProperty']['arrSeasonRates']['clsSeasonRates'];

                $this->rawrates = $rates;
            }

            return $this->rawrates;

        }

        /*
         * Parse the array of min nights and return them
         * @return mixed
         */
        public function getrawnights() {

            if ( $this->hasNights() ) {

                $nights = $this->data['clsProperty']['arrMinNightsInfo']['clsMinNightsInfo'];

                // normalize
                if ( !isset($nights[0]) ) {
                    $temp = array();
                    $temp[] = $nights;
                    $nights = $temp;
                }

                $this->rawnights = $nights;
            }

            return $this->rawnights;
        }

        /*
         * Parse the array of rates and return them
         * @return mixed
         */
        public function getrateplanids() {

            if ( $this->hasRates() == false ) {
                return $this->rateplanids;
            }

            $rates = $this->data['clsProperty']['arrSeasonRates']['clsSeasonRates'];

            $rateplan_ids = array();

            foreach ($rates as $rate) {
                $rateplan_ids[] = $this->findRatePlan($rate['strChargeBasis']);
            }

            $this->rateplanids = array_unique($rateplan_ids);

            return $this->rateplanids;
        }

        /*
         * Parse the array of nights and return them
         * @return mixed
         */
        public function getnights() {

            if ( $this->hasNights() == false ) {
                return $this->nights;
            }

            $nights = $this->getrawnights();

            $finalnights = array();

            foreach ($nights as $night) {
                $startDate = new \DateTime($rate['dtBeginDate']);
                $endDate = new \DateTime($rate['dtEndDate']);
                $endDate->add(new \DateInterval('P1D'));

                $periodInterval = new \DateInterval('P1D');
                $period = new \DatePeriod( $startDate, $periodInterval, $endDate );

                $min = $rate['intMinNights'];
            }
        }


        /*
         * Parse the array of rates and return them
         * @return mixed
         */
        public function getrates() {

            if ( $this->hasRates() == false ) {
                return $this->rates;
            }

            $rates = $this->data['clsProperty']['arrSeasonRates']['clsSeasonRates'];

            $finalrates = array();
            $turndays=$this->getcheckincheckoutonly();

            foreach ($rates as $rate) {

                $chargebasis = strtoupper($rate['strChargeBasis']);

                $rateplan_id = $this->findRatePlan($chargebasis);

                // Couldn't find rateplan id
                // Not doing monthly for now
                if ( !$rateplan_id ) {
                    throw new \Exception('Cant find rateplan id for ' . $chargebasis);
                    #continue;
                }

                // Example 2017-01-01T00:00:00
                $startDate = new \DateTime($rate['dtBeginDate']);
                $endDate = new \DateTime($rate['dtEndDate']);
                $endDate->add(new \DateInterval('P1D'));

                $periodInterval = new \DateInterval('P1D');
                $period = new \DatePeriod( $startDate, $periodInterval, $endDate );

                switch ($chargebasis) {
                    case 'WEEKLY':
                        $amount = $rate['dblRate'];
                        
                        // divide by 7 days 
                        $amount = $amount / 7;
                    break;

                    case 'MONTHLY':
                        $amount = $rate['dblRate'];

                        // divide by 28 days
                        $amount = $amount / 28;
                        break;

                    case 'DAILY':
                        $amount = $rate['dblRate'];
                    break;

                    default:
                        $amount = $rate['dblRate'];
                    break;
                }

                foreach ($period as $date ) 
                {
                	$nocheckin=0;
                	$nocheckout=0;
                	if(isset($turndays[$date->format('Y-m-d')]))
                	{
                		$nocheckin=$turndays[$date->format('Y-m-d')]['nocheckin'];
                		$nocheckout=$turndays[$date->format('Y-m-d')]['nocheckout'];
                	}
                    $finalrates[] = array(
                        'thedate' => $date->format('Y-m-d'),
                        'properties_id' => $this->property_id,
                        'amount' => $this->parseRate($amount),
                        'rateplans_id' => $rateplan_id,
                        'noarrival'=>$nocheckin,
                        'nodeparture'=>$nocheckout
                    );
                }
            }
            
            //print_r($finalrates);
            //die('test');

            $this->rates = $finalrates;

            return $finalrates;
        }
        
        public function getcheckincheckoutonly()
        {
        	$turndays=$this->data['clsProperty']['arrTurnDayInfo']['clsTurnDay'];
        	if(!isset($turndays[0]))
        		$turndays=array($turndays);
        	$finalturndays=array();
        	$periodInterval = new \DateInterval('P1D');
        	
        	foreach($turndays as $turnday)
        	{
        		$turnday['arrTurnDays']=$this->normalizeturndays($turnday['arrTurnDays']);
        		if(!isset($turnday['arrTurnDays'][0]))
        			$turnday['arrTurnDays']=array($turnday['arrTurnDays']);
        		$nocheckin=array();
        		for($i=1; $i<=7; $i++)
        		{
        			$nocheckin[$i]=in_array($i, $turnday['arrTurnDays'])?0:1;
        		}
        		$startDate = new \DateTime($turnday['dtBeginDate']);
                $endDate = new \DateTime($turnday['dtEndDate']);
                $endDate->add(new \DateInterval('P1D'));

                $period = new \DatePeriod( $startDate, $periodInterval, $endDate );
                foreach($period as $date)
                {
                	$finalturndays[$date->format('Y-m-d')]=array(
                		'nocheckin'=>$nocheckin[$date->format('N')],
                		'nocheckout'=>$nocheckin[$date->format('N')]
                		);
                }
        	}
        	//die();
        	return $finalturndays;
        }
        
        protected function normalizeturndays($turndays)
        {
        	
        	//print_r($turndays)
        	//if(!isset($turndays[0]))
        	//	$turndays=array($turndays);
        	if(!is_array($turndays))
        		return array();
        	$final=array();
        	if(!is_array($turndays['int']))
        		$turndays['int']=array($turndays['int']);
        	return $turndays['int'];
        	foreach($turndays['int'] as $turnday)
        		$final[]=$turnday['int'];
        	return $final;
        }

        /*
         * Parse the rate, incase we need to do any currency swapping
         * @param int $amount
         * @return string
         */
        private function parseRate($amount) {
            return $amount;
            return number_format($amount, 2);
        }

        /*
         * Merge a list of non available dates with a start/end
         * @param DateTime $startDate
         * @param DateTime $endDate
         * @return array
         */
        public function getfullavailability($nonavailable_dates, DateTime $startDate, DateTime $endDate) {

            // Add one to the end
            $endDate->add(new \DateInterval('P1D'));

            $available_dates = array();

            $periodInterval = new \DateInterval('P1D');
            $period = new \DatePeriod( $startDate, $periodInterval, $endDate );

            foreach ($period as $date ) {
                $available_dates[] = array(
                    'date' => $date->format('Y-m-d'),
                    'available' => 1,
                    'property_id' => $this->property_id,
                );
            }

            foreach ($nonavailable_dates as $nonavailabledate) {
                foreach ($available_dates as $k => $availabledate) {

                    if ( $nonavailabledate['date'] == $availabledate['date'] ) {
                        // unset non available days
                        unset($available_dates[$k]);
                    }
                }

            }

            $available_dates = array_merge( $available_dates, $nonavailable_dates);

            // Sort by date
            uasort( $available_dates, function ($a, $b) {
                if ($a['date'] == $b['date']) {
                    return 0;
                }
                return ($a['date'] < $b['date']) ? -1 : 1;
            });

            return $available_dates;
        }

        /*
         * Public method for has rates
         * @return bool
         */
        public function hasRates() {
            return $this->_hasRates();
        }

        /*
         * Public method for has nights
         * @return bool
         */
        public function hasNights() {
            return $this->_hasNights();
        }
        
        public function hasTurndays()
        {
        	 if ( 
				!isset( $this->data['clsProperty'] ) || 
				!isset( $this->data['clsProperty']['arrTurnDayInfo'] ) || 
				!isset( $this->data['clsProperty']['arrTurnDayInfo']['clsTurnDay'] ) ||
				(empty( $this->data['clsProperty']['arrTurnDayInfo']['clsTurnDay'] ) )
                ) 
             {
                return false;
            }

            return true;
        }

        /*
         * Check if the data set has empty rates
         * @return bool
         */
        private function _hasNights() {
            if ( 
                !isset( $this->data['clsProperty'] ) || 
                !isset( $this->data['clsProperty']['arrMinNightsInfo'] ) || 
                !isset( $this->data['clsProperty']['arrMinNightsInfo']['clsMinNightsInfo'] ) ||
                (isset( $this->data['clsProperty']['arrMinNightsInfo']['clsMinNightsInfo'] ) && empty ( $this->data['clsProperty']['arrMinNightsInfo']['clsMinNightsInfo'] ) )
            ) {
                return false;
            }

            return true;
        }

        /*
         * Check if the data set has empty rates
         * @return bool
         */
        private function _hasRates() {
            if ( 
                !isset( $this->data['clsProperty'] ) || 
                !isset( $this->data['clsProperty']['arrSeasonRates'] ) || 
                !isset( $this->data['clsProperty']['arrSeasonRates']['clsSeasonRates'] ) ||
                (isset( $this->data['clsProperty']['arrSeasonRates']['clsSeasonRates'] ) && empty ( $this->data['clsProperty']['arrSeasonRates']['clsSeasonRates'] ) )
            ) {
                return false;
            }

            return true;
        }

        protected function extractpossibleamenities() {
            if ($this->possibleamenities)
                return true;

            $this->possibleamenities = $this->amenitylist->getlist();
        }

	protected function extractimages()
	{
		if($this->images)
			return true;
		$this->images=array();

                if ( !isset($this->data['clsProperty']['arrPicList'] ) || !isset( $this->data['clsProperty']['arrPicList']['clsPictureInfo'] )) {
                    return false;
                }

		foreach($this->data['clsProperty']['arrPicList']['clsPictureInfo'] as $image) {
                    $this->images[] = $image;
                }

                return true;
        }

	
	protected function extractamenities()
	{
                if ($this->selectedamenities) {
                    return true;
                }

                $this->selectedamenities=array();
		$this->bedding=array();

		foreach($this->data['clsProperty']['arrPropertyAmenities']['clsPropertyAmenity'] as $amenity)
		{
			$strname = $amenity['strName'];
			$strvalue = $amenity['strValue'];
			

			switch($strname)
			{

                                /* BEGIN NON-AMENITIES */
				case 'View':
					$this->view=$amenity['strValue'];
					break;
				case 'Complex':
					$this->location=$amenity['strValue'];
                                        break;
				case 'Stories':
					$this->stories=$amenity['strValue'];
                                        break;

				// Ignore these per PD
				case 'Rating':
				case 'Golf':
				case 'Air conditioning':
				case 'Air Conditioning':
				case 'Laundry':
				case 'Dishwasher':
				case 'Location':
					break;

				// BEDDING
				case 'Bedding 1':
				case 'Bedding 2':
				case 'Bedding 3':
				case 'Bedding 4':
				case 'Bedding 5':
				case 'Bedding 6':
				case 'Bedding 7':
				case 'Bedding 8':
				case 'Extra Bedding':
					$this->bedding[] = array(
						'bedroomtype' => $strname,
						'bedtype' => $strvalue,
					);
					break;

				/* END NON-AMENITIES */

				/* BEGIN AMENITIES */
				default:

					$special_cases = array('Parking', 'Pool', 'Grill/BBQ', 'Pets Allowed', 'Handicap', 'Deck', 'TV', 'Balcony', 'Tennis', 'Porch', 'Jetted Tub');

					if ( strtolower($amenity['strValue']) == 'yes' || strtolower($amenity['strValue']) == 'no' ) {

						// remap some to avoid duplicates
						$map = array(
							'Washer Dryer' => 'Washer/Dryer',
							'Indoor Comm Pool' => 'Indoor Community Pool',
							'Full Kitchen' => 'Fully-Equipped Kitchen',
							'Childrens Pool' => 'Children\'s Pool',
						);

						$strname = array_key_exists( $strname, $map ) ? $map[$strname] : $strname;

						if ( $strname == 'Indoor Community Pool' && $strvalue == 'Yes' ) {
							$this->selectedamenities[] = '29';
						}

						$id = $this->amenitylist->getid($strname);
						$this->selectedamenities[] = $id;
						#echo 'appending1';

					} 
					elseif ( in_array( $strname, $special_cases ) ) 
					{

						// special cases where the amenity name is the value..   Why are these each hard-coded?
						switch ($strname) {
							case 'Jetted Tub':

								if ( $strvalue == 'Yes' ) {
									$this->selectedamenities[] = 90;
								}

							break;
							case 'Tennis':

								if ( $strvalue == 'Free Tennis' ) {
									$this->selectedamenities[] = 35;
								}

							break;
							case 'Pets Allowed':

								if ( $strvalue == 'Pets Considered') {
									$this->selectedamenities[] = 96;
								} 

							break;
							case 'Balcony':
								
								if ( $strvalue == 'Multiple Balconies' ) {
									$this->selectedamenities[] = 101;
								} else if ( $strvalue == 'Private Balcony' ) {
									$this->selectedamenities[] = 102;
								}

							break;
							case 'Porch':

								if ( $strvalue == 'Screened Porch') {
									$this->selectedamenities[] = 10;
								}
								break;
							case 'Deck':
								if ( $strvalue == 'Private Deck' ) {
									$this->selectedamenities[] = 42;
								}
							break;
							case 'TV':
								if ( $strvalue == 'Flat Screen TV' ) {
									$this->selectedamenities[] = 75;
								}
							break;
							case 'Handicap':

								if ( $strvalue == 'Handicap Accessible' ) {
									$this->selectedamenities[] = 36;
								}

							break;
							case 'Grill/BBQ':

								// Marking as "Grill"
								$this->selectedamenities[] = '87';

								$map = array(
									'Electric' => 'Electric Grill',
									'Gas and Charcoal' => 'Gas Grill',
									'Charcoal' => 'Charcoal Grill (charcoal not provided)',
									'Gas' => 'Gas Grill',
								);

								if ( $strvalue == 'Gas and Charcoal' ) {
									$this->selectedamenities[] = 98;
									$this->selectedamenities[] = 91;
								} else {
									$strvalue = array_key_exists( $strvalue, $map ) ? $map[$strvalue] : $strvalue;

									$id = $this->amenitylist->getid($strvalue);
									$this->selectedamenities[] = $id;
								}

							break;
							case 'Parking':
								$id = $this->amenitylist->getid($strvalue);
								$this->selectedamenities[] = $id;
							break;
							case 'Pool':
								switch ($strvalue) {
									case 'Private':
										$strvalue = 'Private Pool';
										$this->selectedamenities[] = '29';
									break;

									case 'Private Indoor Pool':
										$this->selectedamenities[] = '29';
										break;
									case 'Heated Comm Pool Spa':
										$this->selectedamenities[] = '29';
										break;
								}
								$id = $this->amenitylist->getid($strvalue);
								$this->selectedamenities[] = $id;
							break;
						}
					} else {

						/* Testing extra amenities
						$file = '/home/meder/amenities.txt';
						$lines = preg_split('/\r\n|\n|\r/', trim(file_get_contents($file)));
						$val = $strname . ':' . $strvalue;

						if ( !in_array($val, $lines ) ) {
							file_put_contents($file, $val . PHP_EOL, FILE_APPEND);
						}
						*/
					}

				break;
			}

		}

                // enforce uniqueness
                $this->selectedamenities = array_unique($this->selectedamenities);

		return true;
	}
	
        /*
	//something on initialization is writing to something that's causing problems here
	public function __set($name,$value)
	{
		$success=false;
		switch($name)
		{
		case 'possibleamenities':
			if(is_array($value))
			{
				$this->possibleamenities=$value;
				$success=true;
			}
			break;
		default:
			throw new \Exception('Cannot update value in PropertyDescResponse');
		}
		return $success;
	}
         */
	
	public function __get($name)
	{
		//print_r($this->data);
		//die();
		$value=false;
		switch($name)
		{
		case 'view':
		case 'stories':
                case 'amenities':
		case 'location':
                case 'selectedamenities':
			$this->extractamenities();
			$value= $this->{$name};
			break;
                case 'possibleamenities':
                        $this->extractpossibleamenities();
			$value= $this->{$name};
                    break;
                case 'bedding':
			$this->extractamenities();
			$value= $this->{$name};
                    break;
                case 'images':
                        $this->extractimages();
			$value= $this->{$name};
                break;
		default:
			$value=$this->data['clsProperty'][$name];
		}
		return $value;
	}

        public function getdata() {
            return $this->data;
        }

        public function setInternalId($id) {
            $this->internalpropid = $id;
        }

        public function getInternalId() {
            return $this->internalpropid;
        }
	
}
