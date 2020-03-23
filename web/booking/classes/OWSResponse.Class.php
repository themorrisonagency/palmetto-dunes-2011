<?php
class OWSResponse
{
	protected $error;
	protected $errormessages=array();
	
	function __construct($data)
	{
		if($data->Result->resultStatusFlag=='FAIL')
		{
			$this->error=true;
			$this->errormessages[]=$data->Result->GDSError->_;
			throw new Exception($data->Result->GDSError->_.$data->Result->OperaErrorCode);
		}
		$this->data=$data;
	}
}
?>
