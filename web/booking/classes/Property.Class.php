<?php

class Property extends GenericAdmin
{
	var $propertyid;

	protected $baserateplans=array(1,2);

	var $totals=array(
		'subtotal'=>array('id'=>1, 'label'=>'Subtotal','amount'=>0,'duetoday'=>0, 'tax'=>0),
		'discount'=>array('id'=>2, 'label'=>'Discount', 'amount'=>0,'duetoday'=>0, 'tax'=>0),
		'resortfee'=>array('id'=>4, 'label'=>'Resort Fee','amount'=>0,'duetoday'=>0, 'tax'=>0),
		//'carpass'=>array('id'=>5, 'label'=>'Car Passes', 'amount'=>0,'duetoday'=>0),
		'assurance'=>array('id'=>6, 'label'=>'Vacation Assurance', 'amount'=>0,'duetoday'=>0, 'tax'=>0),
		'tax'=>array('id'=>7, 'label'=>'Tax','amount'=>0,'duetoday'=>0)
		);
	
	var $assuranceviews=array(
		4=>35,
		5=>35,
		13=>35
		);

	function getpropertybyid($id, $params = array())
	{
		if(!is_numeric($id))
		{
			Throw new Exception('Invalid property id.',9999);
			return false;
		}
		$this->propertyid=$id;
		$sql='select *, NULL AS dailyminrate, r.amount AS dailyminrate from properties p INNER JOIN rates r ON r.properties_id = p.id where p.id='.$id;
		$this->db->Query($sql);
		$this->data=$this->db->fetchAssoc();
		if (strlen($params['arrive']) > 0 && strlen($params['depart']) > 0) {
			$arrive=date('Y-m-d', strtotime($params['arrive']));
			$depart=date('Y-m-d', strtotime($params['depart']));
			$sql='select min(d.amount) as dailymin, d.properties_id as properties_id
				from rates d
				where
					d.rateplans_id='.RATEPLAN_ID_DAILY.'
					and d.thedate>=\''.$arrive.'\'
					and d.thedate<=\''.$depart.'\'
					and d.properties_id = ' . $id .  '
				group by d.properties_id';
			$this->db->Query($sql);
			while ($temp = $this->db->fetchAssoc())
				$this->data['dailyminrate'] = !is_null($temp['dailymin']) ? $temp['dailymin'] : $this->data['dailyminrate'];
			$sql='select min(w.amount) as weeklymin, w.properties_id as properties_id
				from rates w
				where
					w.rateplans_id='.RATEPLAN_ID_WEEKLY.'
					and w.thedate>=\''.$arrive.'\'
					and w.thedate<=\''.$depart.'\'
					and w.properties_id = ' . $id .' 
				group by w.properties_id';
			$this->db->Query($sql);
			while ($temp = $this->db->fetchAssoc())
				$this->data['weeklyminrate'] = !is_null($temp['weeklymin']) ? $temp['weeklymin'] : $this->data['weeklyminrate'];
		} else {
			$now = date('Y-m-d');
			$sql='select min(d.amount) as dailymin, d.properties_id as properties_id
				from rates d
				where
					d.rateplans_id='.RATEPLAN_ID_DAILY.'
					and d.thedate>=\''.$now.'\'
					and d.thedate<=\''.$now.'\'
					and d.properties_id = ' . $id .  '
				group by d.properties_id';
			$this->db->Query($sql);
			while ($temp = $this->db->fetchAssoc())
				$this->data['dailyminrate'] = !is_null($temp['dailymin']) ? $temp['dailymin'] : $this->data['dailyminrate'];
			$sql='select min(w.amount) as weeklymin, w.properties_id as properties_id
				from rates w
				where
					w.rateplans_id='.RATEPLAN_ID_WEEKLY.'
					and w.thedate>=\''.$now.'\'
					and w.thedate<=\''.$now.'\'
					and w.properties_id = ' . $id .' 
				group by w.properties_id';
			$this->db->Query($sql);
			while ($temp = $this->db->fetchAssoc())
				$this->data['weeklyminrate'] = !is_null($temp['weeklymin']) ? $temp['weeklymin'] : $this->data['weeklyminrate'];
		}
		// NEXT: Apply promo to the rates. 
		$sql='select p.id as promocodeid, pp.property_id as properties_id,
				promotion_name, long_desc, p.promocode, staybegindate, stayenddate,
                    p.discountamount_daily AS daily_discount,
                    p.discountamount_weekly AS weekly_discount,
                    p.discountamount_monthly AS monthly_discount,
                    p.nthdayfree AS promo_nthdayfree
			from promotions p
			inner join promotions_properties pp on ( p.id = pp.promotion_id and p.promocode=\''.addslashes($params['promocode']) .'\' ) WHERE pp.property_id = ' . $id;
		$this->db->Query($sql);
		while ($temp = $this->db->fetchAssoc()) {
			$this->data['promoweeklyminrate'] = Util::calculatepromo($this->data['weeklyminrate'], $temp['weekly_discount']);
			$this->data['promodailyminrate'] = Util::calculatepromo($this->data['dailyminrate'], $temp['daily_discount']);
		}
		if(!is_array($this->data))
		{
			throw new Exception('Property not found.',9999);
		}
		$this->data['typeid']=$this->data['property_type'];
		$this->data['locationid']=$this->data['location'];
		$this->data['amenities']=array();
		$sql='select ap.amenities_id 
			from amenities_properties ap
			inner join amenities a on
				(ap.properties_id='.$this->data['id'].'
				and a.id=ap.amenities_id
				and a.is_active=1)
			left join amenitiesmap m on
				(m.toid=ap.amenities_id)
			where m.toid is null';
		$this->db->Query($sql);
		while($temp=$this->db->fetchAssoc())
			$this->data['amenities'][]=$temp['amenities_id'];
		$imagelist=new imageList($this->db);
		$temp=$imagelist->getlist();
		$this->data['images']=$temp[$id];
		if(!is_array($this->data['images']))
			$this->data['images']=array();
		$this->data['bedcount']=array();
		$sql='select r.name as bedroomname, b.name as bedname, r.id as bedroomid, b.id as bedid, p.beds
			from bedroomtypes_properties p
			inner join bedroomtypes r on
				(p.properties_id='.$id.'
				and p.bedroomtypes_id=r.id)
			inner join bedtypes b on
				(p.bedtypes_id=b.id)
			order by r.orderid, b.orderid';
		$this->db->Query($sql);
		while($temp=$this->db->fetchAssoc())
		{
			if(!is_array($this->data['bedcount'][$temp['bedroomid']]))
				$this->data['bedcount'][$temp['bedroomid']]=array('name'=>$temp['bedroomname'], 'beds'=>array());
			$this->data['bedcount'][$temp['bedroomid']]['beds'][$temp['bedid']]=array('name'=>$temp['bedname'], 'count'=>$temp['beds']);
		}
		$sql='select startdate, enddate, dailyrate, weeklyrate, minstay, sattosat
			from property_rates
			where property='.$id.'
				and is_active=1
				and is_deleted=0
			order by startdate';
		$this->db->Query($sql);
		$this->data['ratetable']=array();
		while($temp=$this->db->fetchAssoc())
			$this->data['ratetable'][]=$temp;
		$sql='select minrates.*
			from minrates
			where minrates.properties_id='.$id.'
			group by minrates.promotions_id';
		$this->db->Query($sql);
		$this->data['minrates']=array();
		while($temp=$this->db->fetchAssoc())
			$this->data['minrates'][$temp['promotions_id']]=$temp;
		return true;
	}
	
	function setid($id)
	{
		if(!is_numeric($id))
		{
			Throw new Exception('Invalid property id.',9999);
			return false;
		}
		$this->propertyid=$id;
		return true;
	}

	function getrates($from, $to, $promo=null, $ignoreavailability=false)
	{
		if(!is_numeric($this->propertyid))
		{
			Throw new Exception('Invalid property id.',9999);
			return false;
		}
		$from=date('Y-m-d', strtotime($from));
		$to=date('Y-m-d', strtotime($to));
		$sql='select p.nthdayfree,p.bookbegindate, p.bookenddate,p.staybegindate, p.stayenddate, p.discountamount_daily AS daily_discount, p.discountamount_weekly AS weekly_discount, p.discountamount_monthly AS monthly_discount ';
		if($promo)
			$sql.=', pp.property_id as propertyid';
		$sql.='
                        from promotions p ';
		if($promo)
		{
			$sql.='left join promotions_properties pp on
				(pp.promotion_id=p.id
				and pp.property_id='.$this->propertyid.')';
			$sql.='where p.is_active=1 and p.promocode=\''.addslashes(trim($promo)).'\'';
		}
		else
			$sql.=' where 1=1';
		$this->db->Query($sql);
		$basicids=array();
		while($temp=$this->db->fetchAssoc())
		{
			$basicids[]=$temp['id'];
			$promodata=$temp;
		}

                $basicids = $this->baserateplans;
		if(count($basicids)==0)
			Throw new Exception('We\'re sorry, the promo code you have entered is not recognized. Please check the promo code you have entered is correct and try again or call us at <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span> for assistance.',9994); 
		if($promo)//there should only be 1 promo and it should still be in $temp
		{
			$d=date('U');
			if(!(is_numeric($promodata['propertyid'])))
				Throw new Exception('The promo code you entered is not valid for this property.', 9989);
			if($d>strtotime($promodata['bookenddate']))
				Throw new Exception('The promo code you entered has expired.',9990);
			if($d<=strtotime($promodata['bookbegindate']))
				Throw new Exception('The promo code you entered is not yet valid.', 9991);
		}
		$sql='select thedate, amount, minstay, maxstay, rateplans_id, available
			from rates
			where properties_id='.$this->propertyid.'
				and thedate>=\''.$from.'\' ';
				if(!$ignoreavailability)
					$sql.=' and available=1 ';
				$sql.=' and thedate<\''.$to.'\'
				and rateplans_id in('.implode(',',$basicids).')
			order by thedate, minstay, rateplans_id';
		$this->db->Query($sql);
		$calendar=array();
		$temp=$this->db->fetchAssoc();
		if($promo)
		{
			$promofrom=unixtojd(strtotime($promodata['staybegindate']))+1;
			$promoto=unixtojd(strtotime($promodata['stayenddate']))+1;
		}

		$from=unixtojd(strtotime($from))+1;
		$to=unixtojd(strtotime($to))+1;
		for($i=$from; $i<$to; $i++)
		{
			$date=date('Y-m-d', jdtounix($i));
			$calendar[$date]=array();
			while(is_array($temp)&&$temp['thedate']==$date)
			{

                            if ( $promo ) {
                                switch( $temp['rateplans_id'] ) {
                                    case 2:
                                        $temp['amount'] = Util::calculatepromo($temp['amount'], $promodata['weekly_discount']);
                                    break;

                                    default:
                                        $temp['amount'] = Util::calculatepromo($temp['amount'], $promodata['daily_discount']);
                                    break;
                                        break;
                                }
                            }

				if(!strlen($promo)||($i>=$promofrom && $i<$promoto))
					$calendar[$date][]=$temp;
				$temp=$this->db->fetchAssoc();
			}
		}
		return $calendar;
	}

	function getpromocode($code='')
	{
		if(!is_numeric($this->propertyid))
			Throw new Exception('Invalid property id',9999);
		if(strlen(trim($code)))
		{
			$sql='select p.* from promotions p
                                inner join promotions_properties pp on ( pp.promotion_id = p.id )
				inner join rates r on
					(r.properties_id='.$this->propertyid.'
					and r.rateplans_id in (' . implode(',',$this->baserateplans).'))
                                where  p.is_active=1 and p.promocode=\''.addslashes(trim($code)).'\'
				group by p.id';
		}
		else
		{
			return array();
			/*
			$sql='select * from promotions p
				inner join promotions_rateplans pr on
					(pr.promotions_id=p.id)
				inner join rates r on
					(r.properties_id='.$this->propertyid.'
					and r.rateplans_id=pr.rateplans_id)
				group by p.id';
				*/ //I don't know why this was here.
		}

		$this->db->Query($sql);
		$temp=$this->db->fetchAssoc();
		return $temp;
	}
	
        /*
         * Get nthday free - only promotions have these (with V12)
         * nthdayfree on rates are deprecated (Opera)
         */
	function getnightsfree($promo, $span, $from, $to)
	{

            if ( !$promo ) {
                return 0;
            }
			$sql='select p.nthdayfree 
				from promotions p
				inner join promotions_properties pp on
					(pp.promotion_id=p.id
					and p.promocode=\''.addslashes($promo).'\')
				group by p.id';
/*
				inner join rates on
					(rates.rateplans_id in ( ' . implode(',', $this->baserateplans) . ' )
					and rates.thedate>=\''.$from.'\'
					and rates.thedate<\''.$to.'\'
					and rates.minstay<='.$span.'
					and rates.maxstay>='.$span.')
 */


		$this->db->Query($sql);
		if($temp=$this->db->fetchAssoc()) {
			return $temp['nthdayfree'];
                }
		return 0;
	}

	function getratetotals($from, $to, $bookdate, $promo='',$override_minimums = false, $ignoreavailability=false)
	{
		$totals=array();
		$fromts=strtotime($from);
		$to=strtotime($to);
		$span=unixtojd($to)-unixtojd($fromts);
		$from=date('Y-m-d', $fromts);
		$to=date('Y-m-d', $to);
		$rates=$this->getrates($from, $to, '', $ignoreavailability); //if there is a promo code this shouldn't have it
		$nfree=$this->getnightsfree(false, $span, $from, $to);
		$subtotal=0;
		$totals=$this->totals;
		$totals['subtotal']['total_amount'] = 0;
		if ($override_minimums == false)
			if($span<3)
				Throw new exception('Please select new dates. There is a minimum night stay for all units. View Details for the unit for more information or please call <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span> for questions and assistance. Error #9995.', 9993);

		if(defined('ALLOW_OVERMONTHSTAY') && ALLOW_OVERMONTHSTAY == false && $span>28) {
			Throw new exception('The dates you have selected are not available for online booking at this time. Please call us at <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span> to reserve an extended stay.', 9992);
                }

                if ( strlen($promo) == 0 && $span == WEEKLY_NTHDAY ) {
                    $nfree = WEEKLY_NTHDAY;
                } else if ( strlen($promo ) > 0 ) {
                    $nfree=$this->getnightsfree($promo, $span, $from, $to);
                }

		$n=0;

		$weeks = floor($span / 7);
		$day_count = 0;
		foreach($rates as $rate)
		{
			$day_count++;
			$norate=true;
			$n++;
			$daily_rate = $rate[0];
			$weekly_rate = $rate[1];
			foreach($rate as $ratecode)
				if($ratecode['minstay']<=$span && $ratecode['maxstay']>=$span)
				{
					if (($nfree<2 || $n!=$nfree || $span != $nfree)) {
						if($day_count <= (7 * $weeks)) {
							$subtotal+=ceil($weekly_rate['amount']);
						} else {
							$subtotal+= ceil($daily_rate['amount']);
						}
					}
					$norate=false;
				}
			if($norate && $override_minimums == false)
				throw new Exception('Please select new dates. A minimum number of nights may be required. See Rates & Availability below. Error #9996', 9996);
		}
		$totals['subtotal']['amount']=$subtotal;
		$promo=trim($promo);
		$discount=0;
		if(strlen($promo))
		{
			$promosubtotal=0;
			$promodata=$this->getpromocode($promo);
			$promorates=$this->getrates($from, $to, $promo, $ignoreavailability);
			$n=0;
			$day_count = 0;
			$nfree=$this->getnightsfree($promo, $span, $from, $to);
			foreach($promorates as $rate)
			{
				$day_count++;
				$norate=true;
				$n++;
				foreach($rate as $ratecode)
				{
					$daily_rate = $rate[0];
					$weekly_rate = $rate[1];
					if($ratecode['minstay']<=$span && $ratecode['maxstay']>=$span)
					{
						if($nfree<2 || $n!=$nfree || $span != $nfree) {
							if ($day_count <= (7 * $weeks)) {
								$promosubtotal += str_replace(',','', ceil($weekly_rate['amount']));
							} else {
								$promosubtotal += str_replace(',','', ceil($daily_rate['amount']));
							}
						}
						$norate=false;
					}
				}
				if($n>=$nfree)
					$n=0;
				if($norate)
					throw new Exception('Oops. The dates you selected or other filters may not apply to this promo code. Please check the promotion details for more information or remove the promo code and click apply to see availability. You can call us for help at <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span>.', 9995);
			}
			$totals['discount']['amount']=$promosubtotal-$subtotal;
		}
		else
			$promosubtotal=$subtotal;

		$totals=$this->recalculatetax($totals);		
		return $totals;
	}
	
	function getminrate($startdate, $enddate, $rateid)
	{
		if(!is_numeric($rateid))
			throw new Exception('Invalid rate id', 9985);
		$sql='select min(d.amount) as minrate
				from rates d
				where
					d.rateplans_id='.$rateid.'
					and d.thedate>=\''.$startdate.'\'
					and d.thedate<=\''.$enddate.'\'
					and d.properties_id='.$this->propertyid.'
				group by d.properties_id';
		$this->db->Query($sql);
		$temp=$this->db->fetchAssoc();
		return $temp['minrate'];
	}

	// add duetoday values to a totals array
	//totals: the array of subtotals
	// from: the start date of the reservation
	function addratedue($totals, $from, $key = null)
	{
		//throw new Exception('call to obsolete function addratedue',9989); //this is no longer obsolete
            // TODO: Make this pull from property_types db
		if(!in_array($this->data['property_type'], array('1', '2', '3'), TRUE))
			Throw new Exception('Invalid property type.',9987);
		if (array_keys($totals) !== array_keys($this->totals))
			Throw new Exception('System Error.',9988);
		if (($key !== null && !isset($totals[$key])))
			Throw new Exception('Invalid data key '.$key,9986);
		
		$seconds = array(
			1 => 5184000, /// 60 days in seconds (86400*60) for a home
			2 => 2592000 /// 30 days in seconds (86400*30) for a villa
		);
		// if the first day of the rental is greater in time than today plus the number of seconds from $seconds 
		// for a house or a villa then make the duetoday values half of the amount.
		$divisor = ((mktime(0, 0, 0)+$seconds[$this->data['property_type']]) <= strtotime($from) ? 2 : 1);
		
		if ($key !== null)
			$tvalues = array($key);
		else
			$tvalues = array_keys($this->totals);
		foreach ($tvalues as $k)
			$totals[$k]['duetoday'] = $totals[$k]['amount']/$divisor;
		
		$totals = $this->recalculatetax($totals);
		$totals['tax']['duetoday'] = $totals['tax']['amount']/$divisor;
		unset($seconds, $k, $tvalues, $divisor);
		
		return $totals;
	}

	function addcarpass($totals, $number, $arrive)
	{
		return $totals; //we no longer add costs for these and this function doesn't handle storing the number selected
		if(unixtojd(strtotime($arrive))-unixtojd(strtotime(date('Y-m-d')))>7)
			$totals['carpass']['amount']=$number*CARPASS_PRICE_EARLY;
		else
			$totals['carpass']['amount']=$number*CARPASS_PRICE_REGULAR;
		$totals=$this->recalculatetax($totals);
		$totals=$this->addratedue($totals, $arrive);
		return $totals;
	}

	function addvacationassurance($totals, $reservationquery)
	{
		$totals['assurance']['amount']=$this->getvacationassurance();
		$totals['assurance']['tax']=$totals['assurance']['amount']*.10;
		$totals=$this->recalculatetax($totals);
		return $totals;
	}
	
	function getvacationassurance()
	{
		if($this->data['typeid']==1) //home
			return 50;
		if(isset($this->assuranceviews[$this->data['theview']]))
			return $this->assuranceviews[$this->data['theview']];
		return 25;
	}
	
	function recalculatetax($totals)
	{
		$totals['tax']['amount']=
			$totals['subtotal']['tax']+
			$totals['resortfee']['tax']+
			$totals['discount']['tax']+
			$totals['assurance']['tax'];

		return $totals;
	}


	function getvalue($name)
	{
		return $this->data[$name];
	}

        /*
         * Remove availability for a given property
         * @param string $arrive The arrival date
         * @param string $depart The departure date
         * @return bool
         */
	function removeavailability($arrive, $depart)
	{
		if(!is_numeric($this->propertyid))
			Throw new Exception('Invalid property id');
		$tarrive=strtotime($arrive);
		$tdepart=strtotime($depart);
		if($tarrive==0||$tdepart==0)
			Throw new Exception('Invalid date range:'.$arrive.'-'.$depart);
                $sql='update rates
                        set available=0
			where properties_id='.$this->propertyid.'
			and thedate>=\''.date('Y-m-d', $tarrive).'\'
			and thedate<\''.date('Y-m-d', $tdepart).'\'';
		$this->db->Query($sql);
		return true;
	}

        /*
         * Get the unit number for a property
         * @param int $property_id
         * @return bool
         */
        public static function getUnitNumber($property_id) {
            $db = new Db;
            $db->query($sql='SELECT p.unit_number FROM properties p WHERE p.is_active=1 AND p.is_deleted=0 AND p.id ="' . $property_id . '"');
            if ( $temp = $db->fetchAssoc() ) {
                return $temp['unit_number'];
            }

            return false;
        }

        /*
         * Validate a property based on the ows id
         * @param int $property_id
         * @return bool
         */
        public static function getInternalPropertyId($property_id) {
            $db = new Db;
            $db->query($sql='SELECT id FROM properties p WHERE p.is_active=1 AND p.is_deleted=0 AND p.unit_number = "' . $property_id . '"');
            if ( $temp = $db->fetchAssoc() ) {
                return $temp['id'];
            }

            return false;
        }

        /*
         * Get all the active properties
         * @param array $filters A list of filters
         * @return array
         */
        public static function getProperties($filters=array()) {

            $is_active = 1;
            $is_deleted = 0;

            $is_active_key = array_search('is_active', $filters);

            if ( $is_active_key ) {
                $is_active = $filters[$is_active_key];
                unset($filters[$is_active_key]);
            }

            $is_deleted_key = array_search('is_deleted', $filters);

            if ( $is_deleted_key ) {
                $is_deleted = $filters[$is_deleted_key];
                unset($filters[$is_deleted_key]);
            }

            $sql = 'SELECT * FROM properties WHERE is_active=' . $is_active . ' and is_deleted=' . $is_deleted;

            if ( count ( $filters ) > 0 ) {
                foreach ( $filters as $key => $value ) {
                    if ( is_int( $value ) ) {
                        $sql.= ' AND `' . $key . '`=' . $value;
                    } else if ( $value === null ) {
                        $sql.= ' AND `' . $key . '` IS NULL';
                    } else {
                        $sql.= ' AND `' . $key . '`="' . $value . '"';
                    }
                }
            }

            $db = new Db;
            $db->query($sql);

            $properties = array();
            while($temp=$db->fetchAssoc()) {
                $properties[] = $temp;
            }

            return $properties;
        }
}
