<?php
namespace Application\Src\NavisELM;

class NavisELM
{
	
	/**
	 * WSDL URL
	 */
	protected $WSDL     = 'http://www.navistechnologies.info/webservices/Narrowcast/Narrowcast.asmx?wsdl';
	
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
	public function addEnhancedWebContact( $data )
    {
        
		$AddEnhancedWebContact = new AddEnhancedWebContact;

		$AddEnhancedWebContact->setAccount( $this->Account )
			->setPassword( $this->Password )
			->setResAccount( $data['res_account'] )
			->setFirstName( $data['first_name'] )
			->setLastName( $data['last_name'] )
			->setAddress1( $data['address'] )
			->setAddress2( $data['address_2'] )
			->setCity( $data['city'] )
			->setState( $data['state'] )
			->setZipCode( $data['zip_code'] )
			->setCountry( $data['country'] )
			->setHomePhone( $data['phone'] )
			->setWorkPhone( $data['work_phone'] )
			->setCellPhone( $data['mobile_phone'] )
			->setEmailAddress( $data['email_address'] )
			->setSubject( $data['subject'] )
			->setMessage( $data['message'] )
			->setCheckinDate( $data['check_in'] )
			->setCheckoutDate( $data['check_out'] )
			->setAdults( isset($data['adults']) ? $data['adults'] : " " )
			->setChildren( isset($data['children']) ? $data['children'] : " " )
			->setNights( isset($data['nights']) ? $data['nights'] : $AddEnhancedWebContact->calculateNights() )
			->setUnit( $data['unit'] )
			->setReasonForStay( $data['reason_for_stay'] )
			->setCampaign( $data['campaign'] )
			->setOptin( $data['opt_in'] )
			->setKeyword( $data['keyword'] )
			->setAttachmentPath( $data['attachment_path'] );	

		// Create SoapClient
		$client = new SabreSoapClient($this->WSDL, array('trace' => 1, 'encoding'=>'UTF-8'));

		// Invoke webservice method
		$response = $client->__soapCall("AddEnhancedWebContact", array($AddEnhancedWebContact));

		return $response;
    }
}

/**
 * We are only using this request so just adding the class here.
 */
class AddEnhancedWebContact
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
     * @var string $ZipCode
     */
    protected $ZipCode = null;

    /**
     * @var string $Country
     */
    protected $Country = null;

    /**
     * @var string $HomePhone
     */
    protected $HomePhone = null;

    /**
     * @var string $WorkPhone
     */
    protected $WorkPhone = null;

    /**
     * @var string $CellPhone
     */
    protected $CellPhone = null;

    /**
     * @var string $EmailAddress
     */
    protected $EmailAddress = null;

    /**
     * @var string $Subject
     */
    protected $Subject = null;

    /**
     * @var string $Message
     */
    protected $Message = null;

    /**
     * @var string $CheckinDate
     */
    protected $CheckinDate = null;

    /**
     * @var string $CheckoutDate
     */
    protected $CheckoutDate = null;

    /**
     * @var int $Adults
     */
    protected $Adults = null;

    /**
     * @var int $Children
     */
    protected $Children = null;

    /**
     * @var int $Nights
     */
    protected $Nights = null;

    /**
     * @var string $Unit
     */
    protected $Unit = null;

    /**
     * @var string $ReasonForStay
     */
    protected $ReasonForStay = null;

    /**
     * @var string $Campaign
     */
    protected $Campaign = null;

    /**
     * @var boolean $Optin
     */
    protected $Optin = null;

    /**
     * @var string $Keyword
     */
    protected $Keyword = null;

    /**
     * @var string $attachmentPath
     */
    protected $attachmentPath = null;


    /**
     * @return string
     */
    public function getAccount()
    {
      return $this->Account;
    }

    /**
     * @param string $Account
     * @return AddEnhancedWebContact
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
     * @return AddEnhancedWebContact
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
     * @return AddEnhancedWebContact
     */
    public function setResAccount($ResAccount)
    {
      $this->ResAccount = $ResAccount;
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
     * @return AddEnhancedWebContact
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
     * @return AddEnhancedWebContact
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
     * @return AddEnhancedWebContact
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
     * @return AddEnhancedWebContact
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
     * @return AddEnhancedWebContact
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
     * @return AddEnhancedWebContact
     */
    public function setState($State)
    {
      $this->State = $State;
      return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
      return $this->ZipCode;
    }

    /**
     * @param string $ZipCode
     * @return AddEnhancedWebContact
     */
    public function setZipCode($ZipCode)
    {
      $this->ZipCode = $ZipCode;
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
     * @return AddEnhancedWebContact
     */
    public function setCountry($Country)
    {
      $this->Country = $Country;
      return $this;
    }

    /**
     * @return string
     */
    public function getHomePhone()
    {
      return $this->HomePhone;
    }

    /**
     * @param string $HomePhone
     * @return AddEnhancedWebContact
     */
    public function setHomePhone($HomePhone)
    {
      $this->HomePhone = $HomePhone;
      return $this;
    }

    /**
     * @return string
     */
    public function getWorkPhone()
    {
      return $this->WorkPhone;
    }

    /**
     * @param string $WorkPhone
     * @return AddEnhancedWebContact
     */
    public function setWorkPhone($WorkPhone)
    {
      $this->WorkPhone = $WorkPhone;
      return $this;
    }

    /**
     * @return string
     */
    public function getCellPhone()
    {
      return $this->CellPhone;
    }

    /**
     * @param string $CellPhone
     * @return AddEnhancedWebContact
     */
    public function setCellPhone($CellPhone)
    {
      $this->CellPhone = $CellPhone;
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
     * @return AddEnhancedWebContact
     */
    public function setEmailAddress($EmailAddress)
    {
      $this->EmailAddress = $EmailAddress;
      return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
      return $this->Subject;
    }

    /**
     * @param string $Subject
     * @return AddEnhancedWebContact
     */
    public function setSubject($Subject)
    {
      $this->Subject = $Subject;
      return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
      return $this->Message;
    }

    /**
     * @param string $Message
     * @return AddEnhancedWebContact
     */
    public function setMessage($Message)
    {
      $this->Message = $Message;
      return $this;
    }

    /**
     * @return string
     */
    public function getCheckinDate()
    {
      return $this->CheckinDate;
    }

    /**
     * @param string $CheckinDate
     * @return AddEnhancedWebContact
     */
    public function setCheckinDate($CheckinDate)
    {
      $this->CheckinDate = $CheckinDate;
      return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutDate()
    {
      return $this->CheckoutDate;
    }

    /**
     * @param string $CheckoutDate
     * @return AddEnhancedWebContact
     */
    public function setCheckoutDate($CheckoutDate)
    {
      $this->CheckoutDate = $CheckoutDate;
      return $this;
    }

    /**
     * @return int
     */
    public function getAdults()
    {
      return $this->Adults;
    }

    /**
     * @param int $Adults
     * @return AddEnhancedWebContact
     */
    public function setAdults($Adults)
    {
      $this->Adults = $Adults;
      return $this;
    }

    /**
     * @return int
     */
    public function getChildren()
    {
      return $this->Children;
    }

    /**
     * @param int $Children
     * @return AddEnhancedWebContact
     */
    public function setChildren($Children)
    {
      $this->Children = $Children;
      return $this;
    }

    /**
     * @return int
     */
    public function getNights()
    {
      return $this->Nights;
    }

    /**
     * @param int $Nights
     * @return AddEnhancedWebContact
     */
    public function setNights($Nights)
    {
      $this->Nights = $Nights;
      return $this;
    }

    /**
     * @return int
     */
    public function calculateNights()
    {
      
        if(isset($this->CheckinDate) && isset($this->CheckoutDate)) {
            
            if ((strtotime($this->CheckinDate)) !== false && strtotime($this->CheckoutDate) !== false) 
            {
                $checkin = new \DateTime($this->CheckinDate);
                $checkout  = new \DateTime($this->CheckoutDate);
                $date_diff = $checkin->diff($checkout);

                return $date_diff->days;
            } else
                return '0';
        } else
            return '0';
    }

    /**
     * @return string
     */
    public function getUnit()
    {
      return $this->Unit;
    }

    /**
     * @param string $Unit
     * @return AddEnhancedWebContact
     */
    public function setUnit($Unit)
    {
      $this->Unit = $Unit;
      return $this;
    }

    /**
     * @return string
     */
    public function getReasonForStay()
    {
      return $this->ReasonForStay;
    }

    /**
     * @param string $ReasonForStay
     * @return AddEnhancedWebContact
     */
    public function setReasonForStay($ReasonForStay)
    {
      $this->ReasonForStay = $ReasonForStay;
      return $this;
    }

    /**
     * @return string
     */
    public function getCampaign()
    {
      return $this->Campaign;
    }

    /**
     * @param string $Campaign
     * @return AddEnhancedWebContact
     */
    public function setCampaign($Campaign)
    {
      $this->Campaign = $Campaign;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getOptin()
    {
      return $this->Optin;
    }

    /**
     * @param boolean $Optin
     * @return AddEnhancedWebContact
     */
    public function setOptin($Optin)
    {
      $this->Optin = $Optin;
      return $this;
    }

    /**
     * @return string
     */
    public function getKeyword()
    {
      return $this->Keyword;
    }

    /**
     * @param string $Keyword
     * @return AddEnhancedWebContact
     */
    public function setKeyword($Keyword)
    {
      $this->Keyword = $Keyword;
      return $this;
    }

    /**
     * @return string
     */
    public function getAttachmentPath()
    {
      return $this->attachmentPath;
    }

    /**
     * @param string $attachmentPath
     * @return AddEnhancedWebContact
     */
    public function setAttachmentPath($attachmentPath)
    {
      $this->attachmentPath = $attachmentPath;
      return $this;
    }

}

/**
 * Sabre Soap Client Class
 * Inserts any extra data needed
 */
class SabreSoapClient extends \SoapClient {
        
    function __construct($wsdl, $options) {

        parent::__construct($wsdl, $options);
    }
    
    function __doRequest($request, $location, $action, $version, $one_way = 0) {

        // Make request
        $ret = parent::__doRequest($request, $location, $action, $version, $one_way);
        
        $date = new \DateTime();         
        file_put_contents("/var/www/logs/palmetto_navis/" . $date->getTimestamp() . "-elm-request.xml", $request);
        file_put_contents("/var/www/logs/palmetto_navis/" . $date->getTimestamp() . "-elm-response.xml", $ret);
               
        // Update the last request so it reports correctly
        $this->__last_request = $request;

        return $ret;
    }
}

