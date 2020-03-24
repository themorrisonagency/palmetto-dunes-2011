<?php
namespace Application\Src\CDC;
use Loader;

class CDC
{
	
	protected $site_id = '70';	
	
	
	/******************************************************
		CDC ( Taken from forms module, slightly modified )
	******************************************************/

	public function sendCDC( $dataToSend, $ds_id, $form_id ) {

		if (!isset($this->site_id))
			return false;

		// Get the form ID
		if (!isset($form_id))
			$form_id = 0;

		// Get the Datasource ID
		if (!isset($ds_id))
			$ds_id = 0;

		// Send and receive the data (true / false / array(errors))
		$result = $this->sendReceiveCDC($this->site_id, $form_id, $ds_id, $dataToSend);

		// Check for errors
		if (is_array($result) && (sizeof($result)>0))
		{
			if (is_array($result))
				\Log::addEntry('CDC: ' . implode('<br />', $result),'CDC');
			else
				\Log::addEntry('CDC: We were unable to process your form at this time.','CDC');
		}
	}




	// Handle the sending and receiving to/from CDC
	private function sendReceiveCDC($site_id, $form_id, $ds_id, $dataToSend)
	{
		
		define("APP_CLASSES", "cdc/classes/"); 
	
		// Need some general classes
		require_once('esite/classes/class.phpmailer.php');
		require_once('esite/classes/Mailer.Class.php');
		require_once('esite/classes/Db.Class.php');
		require_once('esite/classes/RPCClient.Class.php');
		require_once('cdc/classes/CDCRPCClient.Class.php');
		require_once('cdc/classes/CDCClient.Class.php');

		// CDC client object
		$cdc = new \CDCClient('cdc.esiteportal.com', '/rpcserver/index.php', '80' );
		//$cdc->rpc->client->debug=true; // Turn on debug mode
		$data = $cdc->fixslashes( $dataToSend );

		if (!isset($data['date_created']))
			$data['date_created'] = date('Y-m-d H:i:s');

		// Set the datasource
		$data['ds_id'] = $ds_id;

		// Make connection and send data
		return $cdc->savedata($data, $site_id, $form_id);
	}
	
	
	
}