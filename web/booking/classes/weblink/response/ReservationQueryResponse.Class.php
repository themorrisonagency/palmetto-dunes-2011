<?php

namespace weblink;

class ReservationQueryResponse extends WeblinkResponse
{
	protected $data=null;
	private $cachedratetable=false;

	const resultname = 'getReservationQueryResult';
	protected $property_id = false;
	
	protected $clschargepointers=array();
	
	protected $vacationassurancestrings=array(
		'Vacation Assurance - Homes',
		'Vacation Assurance- Ocean',
		'Vacation Assurance-Villa'
		);
	
	public function __construct($result, $property_id='')
	{
		parent::__construct($result, self::resultname);
		$this->property_id = $property_id;
		if($this->isAvailable())
			if(!isset($this->data['arrCharges']['clsCharge'][0]))
				$this->data['arrCharges']['clsCharge']=array('0'=>$this->data['arrCharges']['clsCharge']);

		foreach($this->data['arrCharges']['clsCharge'] as $chargeid=>$charge)
		{
			$this->clschargepointers[$charge['enumType']]=&$this->data['arrCharges']['clsCharge'][$chargeid];
		}
	}

	/*
	 * We should only really need to make sure the data is a non empty array, but doing a check on totalgoods and totalcost for good measure
	 * @return bool
	 */
	public function isAvailable() {
		return is_array($this->data) && !empty($this->data) && $this->data['dblTotalGoods'] > 0 && $this->data['dblTotalCost'] > 0;
	}

	/*
	 * Get the raw result
	 * @return array
	 */
	public function getrawresult() {
		return $this->data;
	}

	/*
	 * Get the full result
	 * @return array
	 */
	public function getresult() 
	{

		$info = array(
			'totalgoods' => $this->data['dblTotalGoods'],
			'totaltax' => $this->data['dblTotalTax'],
			'totalcost' => $this->data['dblTotalCost'],
			'hascsa' => $this->data['blnHasCSA'] ? 'true' : 'false',
			'csaamount' => $this->data['dblCSAAmount'],
			'dues' => $this->data['dblDues'],
		);

		$info['charges'] = array(
			$this->data['arrCharges']['clsCharge'],
		);

		$info['payments'] = array(
			$this->data['arrPayments']['clsPayment'],
		);

		$info['insurancedetails'] = $this->data['objInsuranceDetails'];

		return $info;

	}

        /*
         * Normalize the dates
         * @param array $dates the dates
         * @return array
         */
	private function normalize($dates) {

		if ( isset($dates['dtFromDate']) != '' ) {
			$temp = array();
			$temp[] = $dates;
			$dates = $temp;
		}

		return $dates;
	}

	public function getvalue($name)
	{
		switch($name)
		{
			case 'subtotal':
				return (float)$this->clschargepointers['Rent']['dblAmount'];
				#return $this->data->AvailResponseSegments->AvailResponseSegment->RoomStayList->RoomStay->ExpectedCharges->TotalRoomRateAndPackages;
				break;
			case 'tax':
				return (float)$this->data['dblTotalTax'];
				#return $this->data->AvailResponseSegments->AvailResponseSegment->RoomStayList->RoomStay->ExpectedCharges->TotalTaxesAndFees;
				break;
			case 'resortfee':
				$fee=0;
				foreach($this->data['arrCharges']['clsCharge'] as $charge)
				{
					if($charge['enumType']!='Rent' && !in_array($charge['strDesc'], $this->vacationassurancestrings))
						$fee+=(float)$charge['dblAmount'];
				}
				return $fee;
				break;
			case 'duetoday':
				$temp=$this->data['arrPayments']['clsPayment'];
				if(!isset($temp[0]))
					$temp[0]=$temp;
				$date=new \DateTime();
				$compdate=new \DateTime($temp[0]['dtDuedate']);
				$compoffset=new \DateInterval('P1D'); //moving this back so if it's tomorrow it triggers as same day
				$compdate->sub($compoffset);

				if($date>=$compdate) //due date is on or before now.
					return (float)$temp[0]['dblAmount'];
				else
					return 0;
				break;
			case 'vacationassurance':
				return 27.0; //temp value for testing until they actually add this
				break;
			case 'resortfeetax':
				$fee=0;
				foreach($this->data['arrCharges']['clsCharge'] as $charge)
				{
					if($charge['enumType']!='Rent' && !in_array($charge['strDesc'], $this->vacationassurancestrings))
						$fee+=(float)$charge['dblTax'];
				}
				return $fee;
				break;
			case 'subtotaltax':
				return (float)$this->clschargepointers['Rent']['dblTax'];
				break;
			case 'vacationassurancetax':
				return 2.7;
				break;
			default:
				Throw new Exception('Unknown availability response value: '.$name, 9994);
		}
	}
	
}
