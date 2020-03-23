<?php

class CreateBookingResponse extends OWSResponse
{
	protected $data=null;
	
	function __construct($data)  //this entire function is here to temporarily work around opera booking failure
	{
		try
		{
			parent::__construct($data);
		}
		catch(Exception $e)
		{
			throw $e;
			//fake success
			$this->data=$data; 
			$this->data->HotelReservation=new stdClass();
			$this->data->HotelReservation->UniqueIDList=new stdClass();
			$temp=new stdClass();
			$temp->_='TESTCONF';
			$this->data->HotelReservation->UniqueIDList->UniqueID=array($temp);
			$this->data->Result->resultStatusFlag='SUCCESS';
		}
	}
	
	function getvalue($name)
	{
		switch($name)
		{
		case 'confirmation':
			return $this->data->HotelReservation->UniqueIDList->UniqueID[0]->_;
			break;
		case 'status':
			return $this->data->Result->resultStatusFlag;
			break;
		default:
			Throw new Exception('Unknown booking response value: '.$name, 9996);
		}
	}
	
	function successful()
	{
		if($this->getvalue('status')=='SUCCESS')
			return true;
		else
			return false;
	}
	
}
