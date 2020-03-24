<?php
namespace Application\Src\NavisReach;

class NavisReach
{
    
	/**
	 * WSDL URL
	 */
	protected $WSDL     = 'https://www.thenavisway.com/webservices/reach/reach.asmx?wsdl';
	
	/**
	 * ELM web service account id
	 */
	protected $Account  = '15521';

	/**
	 * ELM web service password
	 */
	protected $Password = 'dol695m3flzxwiklrts9';


	/**
	 * Add or updates a subscriber
	 * @param array $data
	 */
	public function addUpdateSubscriber( $data )
    {
        
		$AddUpdateSubscriber = new AddUpdateSubscriber;

		$AddUpdateSubscriber->setAccount( $this->Account )
			->setPassword( $this->Password )
			->setResAccount( $data['res_account'] )
			->setEmailAddress( $data['email_address'] )
			->setFirstName( $data['first_name'] )
			->setLastName( $data['last_name'] )
			->setAddress1( $data['address'] )
			->setAddress2( $data['address_2'] )
			->setCity( $data['city'] )
			->setState( $data['state'] )
			->setZip( $data['postal_code'] )
			->setPhone( $data['phone'] )
			->setSubscriptionLists( $data['subscription_lists'] )
			->setUnsubScribeLists( $data['unsubscribe_lists'] )
			->setGlobalUnsubscribeParm( $data['unsubscribe_param'] )
			->setCountry( $data['country'] );
		
		
		// Create SoapClient
		$client = new ReachSoapClient($this->WSDL, array('trace' => 1, 'encoding'=>'UTF-8'));

		// Invoke webservice method
		$response = $client->__soapCall("AddUpdateSubscriber", array($AddUpdateSubscriber));

		return $response;
    }
    
    public function extendedAddUpdateSubscriber($data, $extendedfields)
    {
    	$AddUpdateSubscriber = new AddUpdateSubscriber;

		$AddUpdateSubscriber->setAccount( $this->Account )
			->setPassword( $this->Password )
			->setResAccount( $data['res_account'] )
			->setEmailAddress( $data['email_address'] )
			->setFirstName( $data['first_name'] )
			->setLastName( $data['last_name'] )
			->setAddress1( $data['address'] )
			->setAddress2( $data['address_2'] )
			->setCity( $data['city'] )
			->setState( $data['state'] )
			->setZip( $data['postal_code'] )
			->setPhone( $data['phone'] )
			->setSubscriptionLists( $data['subscription_lists'] )
			->setUnsubScribeLists( $data['unsubscribe_lists'] )
			->setGlobalUnsubscribeParm( $data['unsubscribe_param'] )
			->setCountry( $data['country'] );
			
		// Create SoapClient
		$client = new ReachSoapClient($this->WSDL, array('trace' => 1, 'encoding'=>'UTF-8'));
		
		$addresponse= new AddUpdateCRMContactResponse($client->__soapCall("AddUpdateCRMContact", array($AddUpdateSubscriber)));
		
		// Invoke webservice method
		$response = $client->__soapCall("AddUpdateSubscriber", array($AddUpdateSubscriber));
		
		$attributearray=array(
			'Account'=>$this->Account,
			'Password'=>$this->Password,
			'ParentID'=>$addresponse->id,
			'ParentType'=>'Contact',
			'AttributeName'=>'',
			'AttributeValue'=>''
			);
		
		foreach($extendedfields as $exfield)
		{
			$attributearray['AttributeName']=$exfield;
			$attributearray['AttributeValue']=$data[$exfield];
			$updateresponse=new AddUpdateAttributeResponse($client->__soapCall("AddUpdateAttribute", array($attributearray)));
		}

		return $response;
    }
}

/**
 * We are only using this request so just adding the class here.
 */
class AddUpdateSubscriber
{

    /**
     * @var string $Account
     */
    protected $Account = null;

    /**
     * @var string $Password
     */
    protected $Password = null;

    /**
     * @var string $ResAccount
     */
    protected $ResAccount = null;

    /**
     * @var string $EmailAddress
     */
    protected $EmailAddress = null;

    /**
     * @var string $FirstName
     */
    protected $FirstName = null;

    /**
     * @var string $LastName
     */
    protected $LastName = null;

    /**
     * @var string $Address1
     */
    protected $Address1 = null;

    /**
     * @var string $Address2
     */
    protected $Address2 = null;

    /**
     * @var string $City
     */
    protected $City = null;

    /**
     * @var string $State
     */
    protected $State = null;

    /**
     * @var string $Zip
     */
    protected $Zip = null;

    /**
     * @var string $Phone
     */
    protected $Phone = null;

    /**
     * @var string $SubscriptionLists
     */
    protected $SubscriptionLists = null;

    /**
     * @var string $UnsubScribeLists
     */
    protected $UnsubScribeLists = null;

    /**
     * @var string $GlobalUnsubscribeParm
     */
    protected $GlobalUnsubscribeParm = null;

    /**
     * @var string $Country
     */
    protected $Country = null;

    /**
     * @param string $Account
     * @param string $Password
     * @param string $ResAccount
     * @param string $EmailAddress
     * @param string $FirstName
     * @param string $LastName
     * @param string $Address1
     * @param string $Address2
     * @param string $City
     * @param string $State
     * @param string $Zip
     * @param string $Phone
     * @param string $SubscriptionLists
     * @param string $UnsubScribeLists
     * @param string $GlobalUnsubscribeParm
     * @param string $Country
     */

    /**
     * @return string
     */
    public function getAccount()
    {
      return $this->Account;
    }

    /**
     * @param string $Account
     * @return AddUpdateSubscriber
     */
    public function setAccount($Account)
    {
      $this->Account = $Account;
      return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
      return $this->Password;
    }

    /**
     * @param string $Password
     * @return AddUpdateSubscriber
     */
    public function setPassword($Password)
    {
      $this->Password = $Password;
      return $this;
    }

    /**
     * @return string
     */
    public function getResAccount()
    {
      return $this->ResAccount;
    }

    /**
     * @param string $ResAccount
     * @return AddUpdateSubscriber
     */
    public function setResAccount($ResAccount)
    {
      $this->ResAccount = $ResAccount;
      return $this;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
      return $this->EmailAddress;
    }

    /**
     * @param string $EmailAddress
     * @return AddUpdateSubscriber
     */
    public function setEmailAddress($EmailAddress)
    {
      $this->EmailAddress = $EmailAddress;
      return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
      return $this->FirstName;
    }

    /**
     * @param string $FirstName
     * @return AddUpdateSubscriber
     */
    public function setFirstName($FirstName)
    {
      $this->FirstName = $FirstName;
      return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
      return $this->LastName;
    }

    /**
     * @param string $LastName
     * @return AddUpdateSubscriber
     */
    public function setLastName($LastName)
    {
      $this->LastName = $LastName;
      return $this;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
      return $this->Address1;
    }

    /**
     * @param string $Address1
     * @return AddUpdateSubscriber
     */
    public function setAddress1($Address1)
    {
      $this->Address1 = $Address1;
      return $this;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
      return $this->Address2;
    }

    /**
     * @param string $Address2
     * @return AddUpdateSubscriber
     */
    public function setAddress2($Address2)
    {
      $this->Address2 = $Address2;
      return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
      return $this->City;
    }

    /**
     * @param string $City
     * @return AddUpdateSubscriber
     */
    public function setCity($City)
    {
      $this->City = $City;
      return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
      return $this->State;
    }

    /**
     * @param string $State
     * @return AddUpdateSubscriber
     */
    public function setState($State)
    {
      $this->State = $State;
      return $this;
    }

    /**
     * @return string
     */
    public function getZip()
    {
      return $this->Zip;
    }

    /**
     * @param string $Zip
     * @return AddUpdateSubscriber
     */
    public function setZip($Zip)
    {
      $this->Zip = $Zip;
      return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
      return $this->Phone;
    }

    /**
     * @param string $Phone
     * @return AddUpdateSubscriber
     */
    public function setPhone($Phone)
    {
      $this->Phone = $Phone;
      return $this;
    }

    /**
     * @return string
     */
    public function getSubscriptionLists()
    {
      return $this->SubscriptionLists;
    }

    /**
     * @param string $SubscriptionLists
     * @return AddUpdateSubscriber
     */
    public function setSubscriptionLists($SubscriptionLists)
    {
      $this->SubscriptionLists = $SubscriptionLists;
      return $this;
    }

    /**
     * @return string
     */
    public function getUnsubScribeLists()
    {
      return $this->UnsubScribeLists;
    }

    /**
     * @param string $UnsubScribeLists
     * @return AddUpdateSubscriber
     */
    public function setUnsubScribeLists($UnsubScribeLists)
    {
      $this->UnsubScribeLists = $UnsubScribeLists;
      return $this;
    }

    /**
     * @return string
     */
    public function getGlobalUnsubscribeParm()
    {
      return $this->GlobalUnsubscribeParm;
    }

    /**
     * @param string $GlobalUnsubscribeParm
     * @return AddUpdateSubscriber
     */
    public function setGlobalUnsubscribeParm($GlobalUnsubscribeParm)
    {
      $this->GlobalUnsubscribeParm = $GlobalUnsubscribeParm;
      return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
      return $this->Country;
    }

    /**
     * @param string $Country
     * @return AddUpdateSubscriber
     */
    public function setCountry($Country)
    {
      $this->Country = $Country;
      return $this;
    }

}

class AddUpdateCRMContactResponse
{
	protected $data=array();
	function __construct($data)
	{
		$this->data=$data;
	}
	
	public function __get($name)
	{
		switch($name)
		{
		case 'id':
			return $this->data->{'AddUpdateCRMContactResult'};
			break;
		default:
			return $this->data->{$name};
		}
	}
}

class AddUpdateAttributeResponse
{
	protected $data=array();
	function __construct($data)
	{
		$this->data=$data;
	}
	
	public function __get($name)
	{
		switch($name)
		{
		default:
			return $this->data->{$name};
		}
	}
}

/**
 * Sabre Soap Client Class
 * Inserts any extra data needed
 */
class ReachSoapClient extends \SoapClient {
        
    function __construct($wsdl, $options) {

        parent::__construct($wsdl, $options);
    }
    
    function __doRequest($request, $location, $action, $version, $one_way = 0) {

        // Make request
        $ret = parent::__doRequest($request, $location, $action, $version, $one_way);
        
        $date = new \DateTime();
        //file_put_contents("/var/www/logs/palmetto_navis/" . $date->getTimestamp() . "-reach-request.xml", $request);
        //file_put_contents("/var/www/logs/palmetto_navis/" . $date->getTimestamp() . "-reach-response.xml", $ret);
               
        // Update the last request so it reports correctly
        $this->__last_request = $request;

        return $ret;
    }
}

