<?php

namespace weblink;

class AvailabilityResponse extends WeblinkResponse
{
	protected $data=null;
	private $cachedratetable=false;

        const resultnode = 'getPropertyAvailabilityInfoResult';
        const resultinnernode = 'clsNonAvailDates';

        protected $property_id = false;
	
	public function __construct($result, $property_id)
	{
		parent::__construct($result, self::resultnode);
                $this->property_id = $property_id;

                /*
                if ( $this->data == null ) {
                    throw new \Exception('getPropertyAvailabilityInfoResult is empty.', Exceptions::AvailabilityInfoResultEmpty);
                }

                if ( is_array($this->data) && $this->data[self::resultinnernode] == null ) {
                    throw new \Exception('getPropertyAvailabilityInfoResult has no ' . self::resultinnernode . ' node', Exceptions::AvailabilityInfoResult_NonAvailDates_Empty);
                }
                 */
	}

        /*
         * Merge a list of non available dates with a start/end
         * @param array $nonavailable_dates 
         * @param object $startDate
         * @param object $endDate
         * @return array
         */
        public function getfullavailability($nonavailable_dates=array(), \DateTime $startDate, \DateTime $endDate) {

            // Add one to the end
            // $endDate->add(new \DateInterval('P1D'));

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

            foreach ($available_dates as $k => $availabledate) {

                if ( count ( $nonavailable_dates ) > 0 ) {
                    foreach ($nonavailable_dates as $nonavailabledate) {
                        if ( $nonavailabledate['date'] == $availabledate['date'] ) {
                            // unset non available days
                            unset($available_dates[$k]);
                        }
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
         * Normalize the dates
         * @param array $dates the dates
         * @return array
         */
        private function normalize($dates) {

            // for a "single" result, push onto array and return it
            // otherwise just return the array
            if ( isset($dates['dtFromDate']) && $dates['dtFromDate'] != '' ) {
                $temp = array();
                $temp[] = $dates;
                $dates = $temp;
            }

            return $dates;
        }

        /*
         * Get the raw non available dates / bookings
         * @return array
         */
        public function getnonavailabledatesraw() {

            if ( self::isEmptyResult() ) {
                return array();
            }

            $non_available = $this->data[self::resultinnernode];

            $non_available = $this->normalize($non_available);

            return $non_available;
        }

        /*
         * Get a list of non available dates
         * @return array
         */
        public function getnonavailabledates() {

            if ( self::isEmptyResult() ) {
                return array();
            }

            $dates = array();

            $non_available = $this->data[self::resultinnernode];

            $non_available = $this->normalize($non_available);

            foreach ($non_available as $dateObj ) {
                $startDate = new \DateTime($dateObj['dtFromDate']);
                $endDate = new \DateTime($dateObj['dtToDate']);

                $periodInterval = new \DateInterval('P1D');
                $period = new \DatePeriod( $startDate, $periodInterval, $endDate );

                foreach ($period as $date ) {
                    $dates[] = array(
                        'date' => $date->format('Y-m-d'),
                        'available' => 0,
                        'staytype' => $dateObj['strStayType'],
                        'property_id' => $this->property_id,

                        // Confirmation number
                        'quotenum' => $dateObj['intQuoteNum'],
                    );
                }

            }

            return $dates;
        }
	
	//previous table is used to preserve continuity of rates for sat-sat stays.
	function getratetable($previoustable=false)
	{
		if(!$previoustable)
			$previoustable=array();
		if(is_array($this->cachedratetable))
		{
			return $this->cachedratetable;
		}
		$rates=array();
		foreach($this->data->Calendar->CalendarDay as $cday)
		{
			$closed=false;
			$minstay=0;
			$maxstay=365;
			$closedarrival=0;
			$closeddeparture=0;
			$restrictionlist=$cday->Rates->RestrictionList->Restriction;
			if(!is_array($restrictionlist))
				$restrictionlist=array($restrictionlist);
			foreach($restrictionlist as $restriction)
			{
				if(true || $restriction->rateCode==$this->rateplan)  //opera sometimes sends us rates other than those requested -- disabling this check for now.  may end up being permanent.
				{
					switch($restriction->restrictionType)
					{
					case 'CLOSED':
						$closed=true;
						break;
					case 'CLOSED_FOR_ARRIVAL':
						$closedarrival=1;
						break;
					case 'CLOSED_FOR_DEPARTURE':
						$closeddeparture=1;
						break;
					case 'MINIMUM_STAY_THROUGH':
						$minstay=$restriction->numberOfDays;
						break;
					case 'MAXIMUM_STAY_THROUGH':
						$maxstay=$restriction->numberOfDays;
						break;
					default:
						break;
					}
				}
				else
				{
					$closed=true;
				}
			}
			if(!$closed && isset($cday->Rates->RateList) && $minstay<=$maxstay)
			{
				$rates[$cday->Date]=array(
					'dayrate'=>$cday->Rates->RateList->Rate->Rates->Rate->Base->_,
					'currency'=>$cday->Rates->RateList->Rate->Rates->Rate->Base->currencyCode,
					'minstay'=>$minstay,
					'maxstay'=>$maxstay,
					'noarrival'=>$closedarrival,
					'nodeparture'=>$closeddeparture
					);
				$previoustable[$cday->Date]=$rates[$cday->Date];
			}
			elseif(!$closed && $minstay<=$maxstay && ($closedarrival==1 || $closeddeparture==1)) //date should be between sat-sat days
			{
				$pdate=date('Y-m-d',jdtounix(unixtojd(strtotime($cday->Date)))); //this will subtract a day on its own(12 am unix date time is the previous julian date)
				if(isset($previoustable[$pdate]))
				{
					$rates[$cday->Date]=array(
						'dayrate'=>$previoustable[$pdate]['dayrate'],
						'currency'=>$previoustable[$pdate]['currency'],
						'minstay'=>$minstay,
						'maxstay'=>$maxstay,
						'noarrival'=>$closedarrival,
						'nodeparture'=>$closeddeparture
						);
					$previoustable[$cday->Date]=$rates[$cday->Date];
				}
			}
		}
		$this->cachedratetable=$rates;
		return $rates;
	}
	
	function confirmavailable($startdate, $enddate)
	{
		$startdate=unixtojd(strtotime($startdate))+1;
		$enddate=unixtojd(strtotime($enddate))+1;
		$span=$enddate-$startdate;
		$rates=$this->getratetable();
		$available=true;
		for($i=$startdate; $i<$enddate; $i++)
		{
			$testdate=date('Y-m-d', jdtounix($i));
			//echo $testdate;
			if(!isset($rates[$testdate])||$rates[$testdate]['minstay']>$span||$rates[$testdate]['maxstay']<$span)
				$available=false;
		}
		return $available;
	}

	
}
