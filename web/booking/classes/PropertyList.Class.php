<?php
class PropertyList extends GenericAdmin
{
	
	protected $startdate=false;
	protected $enddate=false;
	protected $stayspan=false;
	protected $searchterms=array(
		'typeids'=>array(),
		'viewids'=>array(),
		'amenityids'=>array()
		);
	
	protected $baserateplans=array(1,2);
	
	public function getlist()
	{
		$fields=array(
			'p.id',
			'p.property_name',
			'p.theview', 
			'p.num_bedrooms', 
			'p.num_bathrooms',
			'p.num_sleeps',
			'p.short_description',
			'p.unit_number',
			'p.ows_id',
			'p.property_type as typeid',
			'p.location as locationid',
			'p.latitude',
			'p.longitude',
			'd.promotion_name as discountname',
			'd.long_desc as discountlong',
			'd.promocode as promocode',
			'p.dailyminrate',
			'p.weeklyminrate',
			'p.tagline'
			);
		$sql=array();
		$join=array();
		$where=array();
		$having=array();
		$order=array('p.order_num','p.property_name');
		$bookdate=date('Y-m-d');
		$sql='select p.id as promotionid, r.id as id, p.nthdayfree as nthdayfree, p.staybegindate,
				p.stayenddate, bookbegindate, bookenddate,
                    p.discountamount_daily,
                    p.discountamount_weekly,
                    p.discountamount_monthly
			from rateplans r
                ';

		if(is_numeric($this->searchterms['stayspan'])) {
				$sql.=' inner join rates rt on 
					(rt.rateplans_id=r.id 
						and rt.minstay<='.$this->searchterms['stayspan'].' 
						and rt.maxstay>='.$this->searchterms['stayspan'].'
                                                and rt.available=1
						and rt.thedate>=\''.$this->searchterms['arrive'].'\'
						and rt.thedate<\''.$this->searchterms['depart'].'\')';
                }

                $promocode = addslashes($this->searchterms['promocode']);


		if(strlen($this->searchterms['promocode']))
		{
			
			$sql.=' inner join promotions p on ( p.promocode="'.$promocode.'" )';
		}
		else {
                        $sql.= ' left join promotions p on ( p.id IS NULL ) ';
                }

                $sql.= ' where r.is_active=1';

		$ratecodes=array();  //this will only contain more than 1 value if the standard ratecodes are used.
		$nthdayfree=0;
		$this->db->Query($sql);

                // Define the promotion id and nth day free
		while($temp=$this->db->fetchAssoc())
		{

			$promotionsid=$temp['promotionid'];
			$ratecodes[]=$temp['id'];
			$nthdayfree=$temp['nthdayfree'];
			if(strlen($this->searchterms['promocode']))
			{
				$d=date('U');
				if(strtotime($temp['bookbegindate'])>$d)
					Throw new Exception('The promo code you entered is not yet valid.',9991);
				if(strtotime($temp['bookenddate'])<$d)
					Throw new Exception('The promo code you entered has expired.',9990);
			}
		}

		if(count($ratecodes)==0)
			$ratecodes[]=0;
		if(!is_numeric($promotionsid))
			$promotionsid=0;

                $ratecodes = array_unique($ratecodes);

                // Per request from PD/v12, 6th day free to make it a weekly rate for 6 days
                if(strlen($this->searchterms['promocode']) == 0 && $this->searchterms['stayspan'] == WEEKLY_NTHDAY ) {
                    $nthdayfree = WEEKLY_NTHDAY;
                }

                // Validate the promo
		if(strlen($this->searchterms['promocode']))//check to make sure promo code is valid
		{
			$currentdate=date('Y-m-d');
			$sql='select p.id as promocodeid, pp.property_id as properties_id,
					promotion_name, long_desc, p.promocode, staybegindate, stayenddate,
                        p.discountamount_daily AS daily_discount,
                        p.discountamount_weekly AS weekly_discount,
                        p.discountamount_monthly AS monthly_discount,
                        p.nthdayfree AS promo_nthdayfree
				from promotions p
				inner join promotions_properties pp on ( p.id = pp.promotion_id and p.promocode=\''.addslashes($this->searchterms['promocode']) .'\' )';
			$testsql=$sql.' where p.is_active=1 limit 1';
			$this->db->Query($testsql);

			if(!$promoinfo=$this->db->fetchAssoc())  //check to make sure promo code exists
				Throw new Exception('We\'re sorry, the promo code you have entered is not recognized. Please check the promo code you have entered is correct and try again or call us at <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span> for assistance.', 9994);
				/*Throw new Exception('Promotional codes are not currently working as expected on our website. Please call <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span> for booking assistance.',9994);*/
		}

		//$sql='select p.* '.$extrafields.' from properties p ';
		if($this->searchterms['arrive'])
		{
			if($this->searchterms['stayspan']<3)
				Throw new exception('Please select new dates. There is a minimum night stay for all units. View Details for the unit for more information or please call <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span> for questions and assistance. Error #9994', 9993);
			if($this->searchterms['stayspan']>28)
				Throw new exception('The dates you have selected are not available for online booking at this time. Please call us at <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span> to reserve an extended stay.', 9992);
			$departminus=strtotime('-1 day', strtotime($this->searchterms['depart']));
			$temp=' inner join rates r on
				r.properties_id=p.id
				and r.thedate>=\''.$this->searchterms['arrive'].'\'
                                and r.available=1
				and r.thedate<\''.$this->searchterms['depart'].'\'
				and r.minstay<='.$this->searchterms['stayspan'].'
				and r.maxstay>='.$this->searchterms['stayspan'].' 
				and r.rateplans_id in ('.implode(',',$ratecodes).') ';
			if(strlen($this->searchterms['promocode']))
			{
				$temp.=' and r.thedate<=\''.$promoinfo['stayenddate'].'\'
					and r.thedate>=\''.$promoinfo['staybegindate'].'\'';
			}
			$join[]=$temp;
			$fields[]='max(amount) as maxrate';
			$fields[]='min(amount) as minrate';
			$fields[]='avg(amount) as avgrate';
			$fields[]='sum(amount) as total';
			$fields[]='group_concat(amount) as ratelist';
			$fields[]='rateplans_id';
			$having[]='count(r.amount)='.$this->searchterms['stayspan'];
		}
		
		//add other stuff for search build
		$where[]='p.is_active=1';
		$where[]='p.is_deleted=0';
		if(is_numeric($this->searchterms['bedrooms']))
			$where[]='p.num_bedrooms>='.$this->searchterms['bedrooms'];
		if(is_numeric($this->searchterms['sleeps']))
			$where[]='p.num_sleeps>='.$this->searchterms['sleeps'];
		if(count($this->searchterms['typeids']))
			$where[]='p.property_type in ('.implode(',',$this->searchterms['typeids']).')';
		if(count($this->searchterms['viewids']))
			$where[]='p.theview in ('.implode(',',$this->searchterms['viewids']).')';
		if(count($this->searchterms['locationids']))
			$where[]='p.location in ('.implode(',',$this->searchterms['locationids']).')';
		if(is_numeric($this->searchterms['featured']))
			$where[]='p.is_featured='.$this->searchterms['featured'];
		
		if(count($this->searchterms['amenityids']))
		{

			$sqlstr='drop temporary table if exists tempamenities_properties';
			$this->db->Query($sqlstr);
			$sqlstr='create temporary table tempamenities_properties
				select properties_id, count(*) as total 
				from amenities_properties a
				where a.amenities_id in ('.implode(',',$this->searchterms['amenityids']).')
				group by properties_id';
			$this->db->Query($sqlstr);
			
			$join[]='inner join tempamenities_properties ap
			on total='.count($this->searchterms['amenityids']).'
			and ap.properties_id=p.id';
		}
		
		//review this entire section
		//pull promo code stuff separately
		$promoproperty=array();
		if(strlen($this->searchterms['promocode']))
		{
			$fields[]='m.dailyminrate as promodailyminrate';
			$fields[]='m.weeklyminrate as promoweeklyminrate';
			$join[]='inner join minrates m on 
				(m.properties_id=p.id and m.promotions_id='.$promotionsid.')';
			$join[]='inner join promotions_properties pp on 
				(pp.property_id=p.id and pp.promotion_id='.$promotionsid.')';

				$sql='select p.id as promocodeid, r.properties_id as properties_id,
					promotion_name, long_desc, p.promocode, staybegindate, stayenddate, is_wholesale
				from promotions p
				inner join rates r on (r.rateplans_id in ( ' . implode(',',$ratecodes).') ';
					
			if($this->searchterms['arrive'])
			{
				if(strtotime($this->searchterms['arrive'])>strtotime($promoinfo['stayenddate'])
					||strtotime($this->searchterms['depart'])>strtotime($promoinfo['stayenddate'])
					||strtotime($this->searchterms['arrive'])<strtotime($promoinfo['staybegindate'])
					||strtotime($this->searchterms['depart'])<strtotime($promoinfo['staybegindate']))
				{
					Throw new Exception('Your search dates are outside of the valid dates for the entered promotion code.',9990);
				}
				$sql.=' and r.thedate>=\''.$this->searchterms['arrive'].'\'
					and r.thedate<=\''.$this->searchterms['depart'].'\'
					and r.minstay<='.$this->searchterms['stayspan'].'
                                        and r.available=1
					and r.maxstay>='.$this->searchterms['stayspan'].'
					and r.thedate<=\''.$promoinfo['stayenddate'].'\'
					and r.thedate>=\''.$promoinfo['staybegindate'].'\'';

			}
			else //pull regular rates based on dates of promo min rates //removed - this was causing problems
			{
				/*
				$join[]='left join rates normaldailyrates on
					(normaldailyrates.properties_id=p.id
						and normaldailyrates.rateplans_id in ('.implode(',',$this->baserateplans).')
						and normaldailyrates.minstay<7
						and normaldailyrates.thedate=m.dailydate)';
				$join[]='left join rates normalweeklyrates on
					(normalweeklyrates.properties_id=p.id
						and normalweeklyrates.rateplans_id in ('.implode(',',$this->baserateplans).')
						and normalweeklyrates.minstay>=7
						and normalweeklyrates.thedate=m.weeklydate)';
				$fields[]='normaldailyrates.amount as dailyminrate';
				$fields[]='normalweeklyrates.amount as weeklyminrate';
				*/
				$fields[]='p.dailyminrate as dailyminrate';
				$fields[]='p.weeklyminrate as weeklyminrate';
			}
			$sql.=') where p.promocode=\''.addslashes($this->searchterms['promocode']).'\'
				group by p.id, r.properties_id';


			$this->db->Query($sql);
			while($temp=$this->db->fetchAssoc())
			{
				if(!is_array($promoproperty[$temp['properties_id']]))
					$promoproperty[$temp['properties_id']]=array();
				$promoproperty[$temp['properties_id']][]=$temp;
			}
		}
		else
		{
			$fields[]='p.dailyminrate as dailyminrate';
			$fields[]='p.weeklyminrate as weeklyminrate';
			$where[]='p.dailyminrate is not null';
		}
		$baserates=array();
		if(strlen($this->searchterms['promocode']) && $this->searchterms['arrive'])
		{
			$sql='select r.properties_id as properties_id, group_concat(r.amount) as baseamounts, sum(r.amount) AS baseamount
				from rates r 
				where 
				 	r.thedate>=\''.$this->searchterms['arrive'].'\'
					and r.thedate<\''.$this->searchterms['depart'].'\'
					and r.minstay<='.$this->searchterms['stayspan'].'
                                        and r.available=1
					and r.maxstay>='.$this->searchterms['stayspan'].'
					and r.rateplans_id in ('.implode(',',$this->baserateplans).')
			group by r.properties_id';
			$this->db->Query($sql);
			while($temp=$this->db->fetchAssoc())
			{
				//if(!is_array($baserates[$temp['properties_id']]))
				//	$baserates[$temp['properties_id']]=array();

                                if ( $nthdayfree > 2 ) {
                                    $baseamounts = explode(',', $temp['baseamounts']);

                                    $baserate = 0;
                                    $n = 0;
                                    foreach ($baseamounts as $amount) {
                                        $n++;
                                        if ( $nthdayfree != $n ) {
                                            $baserate+= $amount;
                                        }
                                    }

                                    if ( $baserate > 0 ) {
                                        // format it the same as sum() in MySQL, so no thousands separator
                                        $temp['baseamount_promo'] = number_format($baserate,2, '.', '');
                                    }
                                }

				$baserates[$temp['properties_id']]=$temp;
			}
		}

		//promo code data for display
		$join[]=' left join promotions d 
			on (d.id=p.promotions_id
			and d.is_active=1
			and d.is_deleted=0)';
			
		//get daily/weekly min base rates for promo
		$baseminrates=array();
		if(strlen($this->searchterms['promocode']) && !$this->searchterms['arrive'])
		{
			$sql='select min(d.amount) as dailymin, d.properties_id as properties_id
				from rates d
				where
					d.rateplans_id='.RATEPLAN_ID_DAILY.'
					and d.thedate>=\''.$promoinfo['staybegindate'].'\'
					and d.thedate<=\''.$promoinfo['stayenddate'].'\'
				group by d.properties_id';
			$this->db->Query($sql);
			while($temp=$this->db->fetchAssoc())
			{
				$baseminrates[$temp['properties_id']]=$temp;
			}
			$sql='select min(w.amount) as weeklymin, w.properties_id as properties_id
				from rates w
				where
					w.rateplans_id='.RATEPLAN_ID_WEEKLY.'
					and w.thedate>=\''.$promoinfo['staybegindate'].'\'
					and w.thedate<=\''.$promoinfo['stayenddate'].'\'
				group by w.properties_id';
			$this->db->Query($sql);
			while($temp=$this->db->fetchAssoc())
			{
				if(!is_array($baseminrates[$temp['properties_id']]))
					$baseminrates[$temp['properties_id']]=$temp;
				else
					$baseminrates[$temp['properties_id']]['weeklymin']=$temp['weeklymin'];
			}
		}

		$sql='select '.implode(',',$fields).' 
			from properties p 
			'.implode(" \r\n ", $join).'
			where '.implode(' and ', $where).'
			group by p.id ';
		if(count($having))
			$sql.=' having '.implode(' and ',$having);
		$sql.=' order by '.implode(',',$order);


		$images=$this->getimages();

		$this->db->Query($sql);

		$this->results=array();
		$count=0;
		
		while($temp=$this->db->fetchAssoc())
		{
            // apply the discount to each value in $temp['ratelist']
            $this->discountpromo($temp, $promoinfo);

			if(is_array($images[$temp['id']]))
				$temp['images']=$images[$temp['id']];
			else
				$temp['images']=array();
				
			if(strlen($this->searchterms['promocode']))
			{

				if(isset($promoproperty[$temp['id']]))
				{
					if($this->searchterms['arrive']) //monetary discounts can only be calculated if we have rates
					{
						//calcuate discounted rates for nth day free
						$this->nthdayfree($nthdayfree, $temp);
						foreach($promoproperty[$temp['id']] as $promoid=>$promo) 
							$promoproperty[$temp['id']][$promoid]['totaldiscount']=$baserates[$temp['id']]['baseamount']-$temp['total'];
					}
					$temp['promocodes']=$promoproperty[$temp['id']];
				}
				if($this->searchterms['arrive'])
				{
					$temp['baserate']=$baserates[$temp['id']]['baseamount'];

					$temp['avgbaserate']=$temp['baserate']/$this->searchterms['stayspan'];

                                        #$temp['avgrate'] = $this->calculatepromo($temp['avgrate'], $promoinfo['daily_discount']);
                                        #$temp['total'] = $this->calculatepromo($temp['total'], count($temp['ratelist'])*$promoinfo['daily_discount']);

                                        //$temp['avgbaserate']=$this->calculatepromo($temp['avgbaserate'], $promoinfo['daily_discount']);
                                        #$temp['dailyrate'] = $this->calculatepromo($temp['dailyrate'], $promoinfo['daily_discount']);

                                        //  Leave this one alone$temp['baserate'] = '90000';
                                        // $temp['avgbaserate'] = '592';
                                        //$temp['dailyrate'] = '1111';
				}
				elseif(strlen($this->searchterms['promocode']) ) //replace min base rates with those confined by promo code dates
				{
					$temp['dailyminrate']=$baseminrates[$temp['id']]['dailymin'];
					$temp['weeklyminrate']=$baseminrates[$temp['id']]['weeklymin'];

					$temp['promodailyminrate']=$this->calculatepromo($baseminrates[$temp['id']]['dailymin'], $promoinfo['daily_discount']);
					$temp['promoweeklyminrate']=$this->calculatepromo($baseminrates[$temp['id']]['weeklymin'], $promoinfo['weekly_discount']);

                                        //$temp['promodailyminrate'] = $this->calculatepromo($temp['promodailyminrate'], $promoinfo['daily_discount']);
                                        //$temp['promoweeklyminrate'] = $this->calculatepromo($temp['promoweeklyminrate'], $promoinfo['weekly_discount']);
				}
			}
			elseif($this->searchterms['arrive']) //apparently normal rates can now have an nth day free
				$this->nthdayfree($nthdayfree, $temp);
			if(strlen($this->searchterms['promocode'])==0 || $this->searchterms['arrive'] || isset($baseminrates[$temp['id']]))
				$this->results[]=$temp;
		}
		return $this->results;
	}

        // calculate the promo code based on the new fields/v12
        protected function calculatepromo($baserate, $discount) {
            return $baserate - ($baserate * ( $discount / 100 ));

        }

        // apply the discount to each value in the ratelist
        protected function discountpromo(&$rates, $promoinfo) {

            $newtotal=0;
            $newmin=0;
            $newmax=0;
            $ratelist=explode(',',$rates['ratelist']);
            foreach($ratelist as $k => $newrate)
            {
                $newrate = $this->calculatepromo($newrate, $promoinfo['daily_discount']);

                $newtotal+=$newrate;
                if($newrate>$newmax)
                    $newmax=$newrate;
                if($newrate<$newmin || $newmin==0)
                    $newmin=$newrate;

                $ratelist[$k] = (string)$newrate;
            }
            $rates['ratelist'] = implode(',', $ratelist);
            $rates['minrate']=(string)$newmin;
            $rates['maxrate']=(string)$newmax;
            $rates['total']=(string)$newtotal;
            $rates['avgrate']=(string)round($newtotal/count($ratelist),2);
        }
	
	//modifies the rate totals to accommodate nth day free.  returns number of free days.
	function nthdayfree($nfree, &$rates)
	{
		if($nfree<2)
			return 0;
		$total=0;
		$freecount=0;
		$n=0;
		$newtotal=0;
		$newmin=0;
		$newmax=0;
		$ratelist=explode(',',$rates['ratelist']);
		foreach($ratelist as $newrate)
		{
			$n++;
			if($n==$nfree)
			{
				$n=0;
				$freecount++;
			}
			else
			{
				$newtotal+=$newrate;
				if($newrate>$newmax)
					$newmax=$newrate;
				if($newrate<$newmin || $newmin==0)
					$newmin=$newrate;
			}
		}
		$rates['total']=$newtotal;
		$rates['minrate']=$newmin;
		$rates['maxrate']=$newmax;
		$rates['avgrate']=round($newtotal/count($ratelist),2);
		return $freecount;
	}
	
	function getimages()
	{
		if(is_null($this->imagelist))
			$this->imagelist=new ImageList($this->db);
		return $this->imagelist->getlist();
	}
	
	function setsearch($searchterms)
	{
		if(is_numeric($searchterms['bedrooms']))
			$this->searchterms['bedrooms']=$searchterms['bedrooms'];
		if(is_numeric($searchterms['sleeps']))
			$this->searchterms['sleeps']=$searchterms['sleeps'];
		$this->searchterms['typeids']=$this->numericarray($searchterms['typeid']);
		$this->searchterms['viewids']=$this->numericarray($searchterms['viewid']);
		$this->searchterms['amenityids']=$this->numericarray($searchterms['amenityids']);
		$this->searchterms['locationids']=$this->numericarray($searchterms['locationid']);
		$this->setdates($searchterms['arrive'], $searchterms['depart']);
		if(strlen(trim($searchterms['promocode'])))
			$this->searchterms['promocode']=trim($searchterms['promocode']);
		if(isset($searchterms['featured']))
			$this->searchterms['featured']=1;
		return true;
	}
	
	function numericarray($values)
	{
		$return=array();
		if(!is_array($values))
			$values=explode(',', $values);
		foreach($values as $value)
		{
			if(is_numeric($value))
				$return[]=$value;
			/*  this doesn't work as intended.  each checkbox would have to be an or. this only does and
			else //support multiple checkboxes with arrays or comma delimited lists of values
			{
				if(!is_array($value))
					$value=explode(',',$value);
				foreach($value as $subvalue)
					if(is_numeric($subvalue))
						$return[]=$subvalue;
			}
			*/
		}
		return $return;
	}
	
	function checkdates()
	{
		return isset($this->searchterms['arrive']);
	}
	
	function setdates($startdate=false, $enddate=false)
	{
		if(!$startdate||!$enddate)
		{
			unset($this->searchterms['arrive']);
			unset($this->searchterms['depart']);
			unset($this->searchterms['stayspan']);
			return true;
		}
		$this->searchterms['arrive']=date('Y-m-d', strtotime($startdate));
		$this->searchterms['depart']=date('Y-m-d', strtotime($enddate));
		$this->searchterms['stayspan']=unixtojd(strtotime($this->searchterms['depart']))-unixtojd(strtotime($this->searchterms['arrive']));
		return true;
	}
	
	function getstayspan()
	{
		return $this->stayspan;
	}
	
}
