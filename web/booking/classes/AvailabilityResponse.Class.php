<?php
class AvailabilityResponse extends OWSResponse
{
	protected $data=null;
	
	
	function getvalue($name)
	{
		switch($name)
		{
		case 'status':
			return $this->data->Result->resultStatusFlag;
			break;
		case 'errorcode':
			return $this->data->Result->OperaErrorCode;
			break;
		case 'subtotal':
			return $this->data->AvailResponseSegments->AvailResponseSegment->RoomStayList->RoomStay->ExpectedCharges->TotalRoomRateAndPackages;
			break;
		case 'tax':
			return $this->data->AvailResponseSegments->AvailResponseSegment->RoomStayList->RoomStay->ExpectedCharges->TotalTaxesAndFees;
			break;
		default:
			Throw new Exception('Unknown availability response value: '.$name, 9994);
		}
	}
	
	function successful()
	{
		if($this->getvalue('status')=='SUCCESS')
			return true;
		else
			return false;
	}
	
	function confirmavailable()
	{
		return $this->successful();
	}
	
}
?>
