<?php
class FetchCalendarResponse extends OWSResponse
{
	protected $data=null;
	private $cachedratetable=false;
	private $rateplan;
	
	function __construct($result, $rateplan)
	{
		parent::__construct($result);
		$this->rateplan=$rateplan;
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
?>
