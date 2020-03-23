<?php
class BookingCart extends GenericAdmin
{
	protected $baserateplans=array(1,2);
	private $property;
        protected $availability;
	private $abandonmentid=0;
	private $cartversionid=0;
	private $nototaldue=array('IMISSYOU2', 'FALL4PD', '199DOWN','2OFFERSN1', '2OFFERS4YOU'); //now we're using an array for the hack because the client is adding more codes but we haven't done anything reasonable to accommodate them yet
	
	function __construct(&$session)
	{
		parent::__construct();
		$this->session=&$session;
		$this->property=new Property();
		$this->formdata=array(
			'carpasses'=>1
			);
	}
	
	function setproperty($propertyid, $checkin, $checkout, $promocode='')
	{
		$this->property->getpropertybyid($propertyid);
		$checkin=strtotime($checkin);
		$checkout=strtotime($checkout);
		$this->data['propertyid']=$propertyid;
		$this->data['arrive']=date('Y-m-d',$checkin);
		$this->data['depart']=date('Y-m-d', $checkout);
		$this->data['stayspan']=unixtojd($checkout)-unixtojd($checkin);
		$this->data['promocode']=strtoupper(trim($promocode));
		
		if($this->data['stayspan']<3)
			Throw new exception('Please select new dates. There is a minimum night stay for all units. View Details for the unit for more information or please call <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span> for questions and assistance. Error #9993.', 9993);


                /*
                 * Allowing month+ long bookings in V12
                 * This wasn't possible in Opera
                 */
		if(defined('ALLOW_OVERMONTHSTAY') && ALLOW_OVERMONTHSTAY == false && $this->data['stayspan'] > 28 ) {
			Throw new exception('The dates you have selected are not available for online booking at this time. Please call us at <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span> to reserve an extended stay.', 9992);
                }
			
		$sql='select p.code';
		if(strlen($this->data['promocode']))
		{
			$sql.=', promo.is_wholesale';
		}
		$sql.='
			from rates r
			inner join rateplans p on
				(r.thedate>=\''.$this->data['arrive'].'\'
				and r.thedate<=\''.$this->data['depart'].'\'
				and r.minstay<='.$this->data['stayspan'].'
				and r.maxstay>='.$this->data['stayspan'].'
                                and p.id in ( ' . implode(',', $this->baserateplans) . ' )
				and p.id=r.rateplans_id)';
		if(strlen($this->data['promocode']))
		{
			$sql.=' inner join promotions promo on
					(promo.promocode=\''.addslashes($this->data['promocode']).'\')';
		}
		$sql.=' group by p.id';

		$this->db->Query($sql);
		if($temp=$this->db->fetchAssoc())
			$this->data['rateplan']=$temp['code'];
		try
		{
			$this->data['ratetotals']=$this->property->getratetotals($this->data['arrive'], $this->data['depart'], date('Y-m-d'), $this->data['promocode']);
			if(in_array($this->data['promocode'], $this->nototaldue)) //temporary hack.  this can be removed when the db driven amount due is finished
				$this->data['ratetotals']['duetoday']=0;
		}
		catch (Exception $e)
		{
			throw $e;
		}

		if(is_numeric($temp['is_wholesale']))
			$this->data['wholesale']=$temp['is_wholesale'];
		else
			$this->data['wholesale']=0;
		return true;
	}
	
	function setavailability($availability)
	{

		$this->availability=$availability;
		$this->data['ratetotals']['subtotal']['amount']=$availability->getvalue('subtotal');
		$this->data['ratetotals']['subtotal']['tax']=$availability->getvalue('subtotaltax');
		$this->data['ratetotals']['resortfee']['amount']=$availability->getvalue('resortfee');
		$this->data['ratetotals']['resortfee']['tax']=$availability->getvalue('resortfeetax');
		//$this->data['ratetotals']['subtotal']['duetoday']=$availability->getvalue('duetoday');
		try
		{
			if(in_array($this->data['promocode'], $this->nototaldue)) //temporary hack.  this can be removed when the db driven amount due is finished
				$this->data['ratetotals']['subtotal']['duetoday']=0;
		}
		catch(Exception $e)
		{
			throw $e;
		}
		if(is_numeric($this->data['ratetotals']['discount']['amount']))
		{
			$this->data['ratetotals']['subtotal']['amount']-=$this->data['ratetotals']['discount']['amount']; //want this to be subtotal before discount is applied.
			if(!in_array($this->data['promocode'], $this->nototaldue)) //temporary hack.  this can be removed when the db driven amount due is finished
				$this->data['ratetotals']['subtotal']['duetoday']-=$this->data['ratetotals']['discount']['duetoday'];
		}
		$this->data['ratetotals']=$this->property->recalculatetax($this->data['ratetotals']);
		$this->data['ratetotals']=$this->property->addratedue($this->data['ratetotals'], $this->data['arrive']);
		return true;
	}
	
	function getdata($name)
	{
		switch($name)
		{
		case 'arrive':
		case 'depart':
		case 'promocode':
		case 'rateplan':
		case 'firstname':
		case 'lastname':
		case 'email':
		case 'email_confirm':
		case 'phone':
		case 'address1':
		case 'address2':
		case 'city':
		case 'zipcode':
		case 'country':
		case 'state':
		case 'adults':
		case 'children':
		case 'carpasses':
		case 'vacationassurance':
		case 'requests':
		case 'ratetotals':
		case 'confirmation':
		case 'wholesale':
			return parent::getdata($name);
			break;
		case 'propertyaddress':
			return $this->property->getdata('address');
			break;
		case 'propertyvacationassurance':
			return $this->property->getvacationassurance();
			break;
		case 'propertyowsid':
			return $this->property->getdata('ows_id');
			break;
		case 'stayspan':
			return unixtojd(strtotime(parent::getdata('depart')))-unixtojd(strtotime(parent::getdata('arrive')));
			break;
		case 'displaytotals':
			return $this->getdisplaytotals($this->data['ratetotals']);
			break;
		default:
			return $this->property->getdata($name);
			
		}
	}
	
	function getformdata($name)
	{
		return $this->formdata[$name];
	}
	
	function getdisplaytotals($totals)
	{
		$disp=array(
			'package'=>array(
				'label'=>'Package Amount',
				'use'=>array('subtotal','discount')
				),
			'fees'=>array(
				'label'=>'Fees',
				'use'=>array('resortfee','assurance')
				),
			'tax'=>array(
				'label'=>'Taxes',
				'use'=>array('tax')
				)
			);
		foreach($disp as $type=>$data)
		{
			$temp=0;
			foreach($data['use'] as $item)
				$temp+=$totals[$item]['amount'];
			$disp[$type]['amount']=$temp;
		}
		return $disp;
	}
	
	function adddata($values, $addvalues)
	{
		foreach($addvalues as $name)
		{
			$this->formdata[$name]=$values[$name];
			switch($name)
			{
				case 'firstname':
				case 'lastname':
				case 'phone':
				case 'address1':
				case 'city':
				case 'zipcode':
				case 'state':
				case 'confirmation':
				case 'promocode':
					if($this->checkstring($values[$name])) 
					{
						$this->data[$name]=trim($values[$name]);
					}
					else if ($name != 'promocode')
						$this->adderror($name.' is required');
					break;
				case 'carpasses':
					if(is_numeric($values[$name])&&$values[$name]>=0)
					{
						$this->data[$name]=$values[$name];
						$this->data['ratetotals']=$this->property->addcarpass($this->data['ratetotals'], $values['carpasses'],$this->data['arrive']);
						if(in_array($this->data['promocode'], $this->nototaldue)) //temporary hack.  this can be removed when the db driven amount due is finished
							$this->data['ratetotals']['subtotal']['amountdue']=0;
						else
							$this->data['ratetotals']=$this->property->addcarpass($this->data['ratetotals'], $values['carpasses'],$this->data['arrive']);
					}
					else
						$this->adderror($name.' must be a number.');
					break;
				case 'vacationassurance':
					if(is_numeric($values[$name]))
					{
						$this->data[$name]=$values[$name];
						/*
						if(!in_array($this->data['promocode'], $this->nototaldue)) //temporary hack.  this can be removed when the db driven amount due is finished
							$this->data['ratetotals']=$this->property->addratedue($this->property->addvacationassurance($this->data['ratetotals']), $this->data['arrive'], 'assurance');
						else
						*/
						$this->data['ratetotals']=$this->property->addvacationassurance($this->data['ratetotals'], $this->availability);
						$this->data['ratetotals']=$this->property->addratedue($this->data['ratetotals'], $this->data['arrive']);
					}
					else
						$this->data[$name]=0;
					break;
				case 'address2':
				case 'country':
				case 'requests':
					$this->data[$name]=trim($values[$name]);
					break;
				case 'adults':
					if(is_numeric($values[$name])&&$values[$name]>=0)
					{
						$this->data[$name]=$values[$name];
					}
					else
						$this->adderror($name.' is required');
					break;
				case 'children':
					if(is_numeric($values[$name])&&$values[$name]>=0)
					{
						$this->data[$name]=$values[$name];
					}
					break;
				case 'email_confirm':
					if($values[$name] == 'notrequired')
					{
						$this->data[$name] = $values[$name];
						break;
					}
				case 'email':
					$email = filter_var($values[$name], FILTER_VALIDATE_EMAIL);
					if ($email !== FALSE)
						$this->data[$name] = $email;
					else
						$this->adderror(str_replace('_', ' ', $name).' is invalid');
					unset($email);
					break;
				default:
					$this->adderror('Invalid data name');
			}
		}
		
		if ($this->data['email_confirm'] != 'notrequired' && isset($this->data['email']) && $this->data['email'] !== $this->data['email_confirm'])
			$this->adderror('email and email confirm do not match');
		
		if(!$this->error)
			return true;
		else
			return false;
	}
	
	function checkstring($str)
	{
		if(strlen(trim($str))>0)
			return true;
		return false;
	}
	
	
	function savecart()
	{
		$this->session['cartdata']=serialize($this->data);
		$this->session['cartavailability']=serialize($this->availability);
		$this->session['cartformdata']=serialize($this->formdata);
		return true;
	}
	
	function saveformdata()
	{
		$this->session['cartformdata']=serialize($this->formdata);
		return true;
	}

        public function getavailability() {
            return $this->availability;
        }
	
	function loadcart()
	{
		$this->data=unserialize($this->session['cartdata']);
		$this->availability=unserialize($this->session['cartavailability']);
		$this->formdata=unserialize($this->session['cartformdata']);
		$this->property->getpropertybyid($this->data['propertyid']);
		return true;
	}
	
	function cleardata()
	{
		$this->data=array();
		return true;
	}
	
	function updateavailability()
	{
		return $this->property->removeavailability($this->data['arrive'], $this->data['depart']);
	}
	
	function loadfromdb($email, $confirmation)
	{
		$sql='select cartdata, cartavailability 
			from bookings_cartdata bc 
			inner join bookings b on
			(bc.bookings_id=b.id 
				and b.email=\''.addslashes($email).'\' 
				and b.confirmation=\''.addslashes($confirmation).'\'
			)';
		$this->db->Query($sql);
		if($temp=$this->db->fetchassoc())
		{
			$this->data=unserialize($temp['cartdata']);
			$this->data['confirmation']=$confirmation;
			$this->availability=unserialize($temp['cartavailability']);
			$this->property->getpropertybyid($this->data['propertyid']);
			return true;
		}
		else
		{
			throw new Exception('Booking not found');
		}
	}
	
	function savetoabandonment()
	{
		$datecreated=gmdate('Y-m-d H:i:s');
		$temp=$this->data;
		unset($temp['cartdata']);
		unset($temp['cartavailability']);
		$temp['cartdata']=serialize($this->data);
		$temp['cartavailability']=serialize($this->availability);
		$temp['properties_id']=$temp['propertyid'];
                $temp['is_v12'] = defined('USE_V12') && USE_V12 ? 1 : 0;
		if(is_numeric($this->data['abandonmentid']) && $this->data['abandonmentid']>0)
		{
			unset($temp['datecreated']);
			$this->db->AutoUpdate('abandonedbookings', 'where id='.$this->data['abandonmentid'], $temp);
		}
		else
		{
			$temp['datecreated']=$datecreated;
			$this->db->AutoInsert('abandonedbookings', $temp);
			$this->data['abandonmentid']=$this->db->getlastid();
		}
	}
	
	function savetodb()
	{
		$temp=$this->data;
		$temp['properties_id']=$temp['propertyid'];
		$temp['dateupdated']=gmdate('Y-m-d H:i:s');
                $temp['is_v12'] = defined('USE_V12') && USE_V12 ? 1 : 0;
		$temp['total']=0;
		if(strlen($temp['confirmation']))
			$temp['confirmed']=1;
		foreach($temp['ratetotals'] as $t)
			if(is_numeric($t['amount']))
				$temp['total']+=$t['amount'];
		if(!is_numeric($this->data['id']))
		{
			$temp['datecreated']=$temp['dateupdated'];
			$this->db->autoinsert('bookings', $temp);
			$this->data['id']=$this->db->getlastid();
			$fees=array();
			foreach($temp['ratetotals'] as $fee)
				if(is_numeric($fee['amount']) && is_numeric($fee['id']))
					$fees[]='('.$this->data['id'].','.$fee['id'].','.$fee['amount'].')';
			if(count($fees))
			{
				$sql='insert into bookingfees(bookings_id, bookingfeetypes_id, amount) values '.implode(',',$fees);
				$this->db->Query($sql);
			}
			$sql='insert into bookings_cartdata(bookings_id, cartversions_id, cartdata, cartavailability)
				values('.$this->data['id'].', '.$this->cartversionid.', \''.addslashes(serialize($this->data)).'\', \''.addslashes(serialize($this->availability)).'\')';
			$this->db->Query($sql);
			if(is_numeric($this->data['abandonmentid']))
			{
				$sql='delete from abandonedbookings where id='.$this->data['abandonmentid'];
				$this->db->query($sql);
			}
		}
		else
		{
			$this->db->autoupdate('bookings', 'where id='.$this->data['id'], $temp);
		}
		return true;
		//actually save all cart params to db and store internal id, or update if an id exists
	}

}
