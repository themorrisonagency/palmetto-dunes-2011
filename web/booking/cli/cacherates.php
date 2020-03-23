<?php
	require_once('esite/config/conf.php');
	require_once('../conf/conf.php');
	//require_once(INC_PATH.'includes/checklogin.php');
	require_once('esite/classes/GenericErrors.class.php');
	require_once('esite/classes/WebServiceCache.class.php');
	require_once('esite/classes/Db.Class.php');
	require_once('../classes/SoapOWS.Class.php');
	require_once('../classes/OWSResponse.Class.php');
	require_once('../classes/FetchCalendarResponse.Class.php');
        
        require_once('esite/classes/Mailer.Class.php');
        
        
	$runstart=date('U');
	$totaloperatime=0;
	$extrateid=0;
	$extpropertyid=0;
	$extrateerror=0;
	if(isset($argv[1]))
		if (is_numeric($argv[1]))
			$extrateid=$argv[1];
		else if ($argv[1] === 'cachefailed')
			$extrateerror=1;
	if(isset($argv[2]) && is_numeric($argv[2]))
		$extpropertyid=$argv[2];
	//$extstartdate=$argv[2];
	//$extenddate=$argv[3];
	
	$ows=new OWS();
	$db=new Db();
        
	$sql='
		select id, ows_id 
		from properties 
		where is_deleted=0 
			and is_active=1';
	if($extpropertyid>0)
		$sql.=' and id='.$extpropertyid.' ';
	if($extrateerror===1)
		$sql.=' and lastrateerror=1 ';
	$sql.=' order by lastraterefresh';
	$db->query($sql);
	$properties=array();
	while($temp=$db->fetchAssoc())
		$properties[$temp['id']]=$temp['ows_id'];
	$sql='
		select id, code
		from rateplans ';
	if($extrateid>0)
		$sql.=' where id='.$extrateid.' ';
	else
		$sql.=' where is_active=1  ';
	$sql.=' order by id';
	$db->Query($sql);
	$rateplans=array();
	$pulledrateplans=array();
	while($temp=$db->fetchAssoc())
	{
		$rateplans[$temp['id']]=$temp;
	}
	foreach($rateplans as $rid=>$r)
	{
		$db->Query('select setrateplansemaphore('.$rid.',\''.date('Y-m-d H:i:s').'\') as semaphore from dual');
		$temp=$db->fetchAssoc();
		$rateplans[$rid]['semaphore']=$temp['semaphore'];
		if(strlen($temp['semaphore'])==0)
			$pulledrateplans[]=$rid;
	}
	$tempdate=gmdate('U');
	$startdate=unixtojd(strtotime(gmdate('Y-m-d',$tempdate)))+1;
	//$startdate=unixtojd(strtotime(gmdate('Y-m-d',$tempdate))); //used to test previous stay recovery
	///if(date('H',$tempdate)<12)
	//	$startdate++;
	//echo date('H',$tempdate);
	//die();
	//$startdate=unixtojd(strtotime('2015-09-30'));
	//$enddate=$startdate+545;
        $owserror = array('"PID", "PCODE", "Errors", "HTTP CODE", "UTC DateTime"');
	$enddate=$startdate+440;
	$maxperpull=90;
	$insertstatement='insert into rates(properties_id, thedate, rateplans_id, amount, minstay, maxstay, noarrival, nodeparture) values';
	foreach($properties as $propertyid=>$propertycode)
	{
		$ratetables=array();
		foreach($rateplans as $rateplanid=>$rateplancode)
		{
			try
			{
				$singleratetime=0;
				if($rateplancode['semaphore'])
					Throw new Exception('SEMAPHORE');
				$substart=$startdate;
				$subend=$startdate;
				while($subend<$enddate)
				{
                                        $ran[] = $propertyid;
					$substart=$subend;
					$subend+=$maxperpull;
					if($subend>$enddate)
						$subend=$enddate;
					try
					{
						$temp=false;
						$tempstarttime=date('U');
						if($availability=$ows->fetchcalendar(date('Y-m-d',jdtounix($substart)), date('Y-m-d',jdtounix($subend)), $propertycode,$rateplancode['code']))
						{
							$totaloperatime+=date('U')-$tempstarttime;
							$singleratetime+=date('U')-$tempstarttime;
							
							$pre=array();
							if(isset($ratetables[$rateplanid]))
								$pre=$ratetables[$rateplanid];
							$temp=$availability->getratetable($pre);
						}
						else
						{
							$totaloperatime+=date('U')-$tempstarttime;
							$singleratetime+=date('U')-$tempstarttime;
                            if (($msg = $ows->geterrormessages()) !== FALSE)
							{
								$debuginfo = $ows->getdebuginfo('AvailabilityBinding');
								$http_code = trim(current(explode("\n", $debuginfo['responseheaders'])));
								unset($debuginfo);
								$errors = implode("\n", $msg);
							}
							else
							{
								$errors = 'UNKOWN ERROR WHEN FETCHING THE CALENDAR';
							}
							
							$errordate=new DateTime();
							$tz=new DateTimezone('UTC');
							$errordate->settimezone($tz);

							$owserror[$propertyid] = '"' . addslashes($propertyid) . '", "' . 
								addslashes($propertycode) . '", "' . 
								addslashes($errors) . '", "'. 
								$http_code . '", "'.
								addslashes($errordate->format('Y-m-d H:i:s')).'"';

							print_r($ows);
                                                        
							break;
						}
						if(is_array($temp))
						{
							if(!isset($ratetables[$rateplanid]) || !is_array($ratetables[$rateplanid]))
								$ratetables[$rateplanid]=array();
							$ratetables[$rateplanid]=array_merge($ratetables[$rateplanid],$temp);
						}
					}
					catch(Exception $e)
					{
						switch($e->getmessage())
						{
							case 'INVALID_ROOM_CATEGORY':
								//echo 'INVALID_ROOM_CATEGORY '.$propertycode."\n";
								break;
							case 'INVALID_RATE_CODE':
								echo 'Invalid Rate code '.$rateplancode['code'].' for '.$propertycode."\n";
								break;
							case 'PRIOR_STAY':
								echo 'Prior Stay '.date('Y-m-d',jdtounix($substart))."\n";
								//start date is earlier than earliest possible date.  attempt to recover
								$subend-=$maxperpull;
								$subend++;
								if($subend>$enddate)  //possible if the end date is in the past.  shouldn't happen under normal circumstances
									throw $e; //continuing after this error can cause data loss
								break;
							default:
								echo $e->getmessage()."\n";
						}
					}
					
				}
			}
			catch(Exception $e)
			{
				switch($e->getmessage())
				{
					case 'SEMAPHORE':
						echo 'semaphore already set for '.$rateplancode['code'].' '.$rateplancode['semaphore']."\n";
						break;
					case 'PRIOR_STAY':
						throw $e;
						break;
					default:
						echo $e->getmessage()."\n";
				}
			}
                        if (isset($owserror[$propertyid])) {
                              break;
                        }
			echo 'Rate '.$rateplancode['code'].' time '.($singleratetime)."\n";
		}
		if (isset($owserror[$propertyid]))
		{
			$sql='update properties set lastrateerror=1 where id='.$propertyid;
			$db->Query($sql);
		}
		else if(count($pulledrateplans))
		{
			$db->Query('start transaction');
			$sql='
				delete 
				from rates 
				where properties_id='.$propertyid.'
				and rateplans_id in ('.implode(',',$pulledrateplans).')';
			if($extrateid>0)
				$sql.=' and rateplans_id='.$extrateid;
			$db->Query($sql);
			$insertrates=array();
			$dailymin='null';
			$weeklymin='null';
			foreach($ratetables as $rateplanid=>$ratetable)
			{
				foreach($ratetable as $thedate=>$rate)
				{
					if($rateplanid==1) //1 is the non promo daily rate
					{
						if(!is_numeric($dailymin)||$dailymin>$rate['dayrate'])
							$dailymin=$rate['dayrate'];
					}
					elseif($rateplanid==2) //2 is the non promo weekly rate
					{
						if(!is_numeric($weeklymin)||$weeklymin>$rate['dayrate'])
							$weeklymin=$rate['dayrate'];
					}
					$insertrates[]='('.$propertyid.',\''.$thedate.'\','.$rateplanid.','.$rate['dayrate'].','.$rate['minstay'].','.$rate['maxstay'].','.$rate['noarrival'].','.$rate['nodeparture'].')';
					if(count($insertrates)>100)
					{
						$db->Query($insertstatement.implode(',',$insertrates));
						$insertrates=array();
					}
				}
				if(count($insertrates))
					$db->Query($insertstatement.implode(',',$insertrates));
				$insertrates=array();
			}
			$db->Query('commit');
			$sql='update properties set 
				lastraterefresh=\''.gmdate('Y-m-d H:i:s').'\', 
				latestrate=\''.date('Y-m-d',jdtounix($enddate)).'\',
                                lastrateerror=0,';
				if(in_array(1, $pulledrateplans))
					$sql.=' dailyminrate='.$dailymin.',';
				if(in_array(2,$pulledrateplans))
					$sql.='	weeklyminrate='.$weeklymin.',';
				$sql=rtrim($sql,',');
				$sql.=' where id='.$propertyid;
			$db->Query($sql);
		}
		if (empty($ratetables))
		{
			echo 'NO RATES RETURNED FOR PROPRETY: '.$propertyid."\n";
		}
	}
	$db->Query('call reloadminrates()');
	foreach($rateplans as $r)
	{
		if(strlen($r['semaphore'])==0)
		{
			$db->Query('update rateplans set lastimported=\''.gmdate('Y-m-d H:i:s').'\' where id='.$r['id']);
			$db->Query('select clearrateplansemaphore('.$r['id'].') from dual');
		}
	}
        
	/// send email if owserror has values
	$emailaddresses = array('esheets@palmettodunes.com', 'amber.yothers@sabre.com', 'bobby.anderson@sabre.com');
	//$emailaddresses = array('bobby.anderson@sabre.com');
	$developeremail = 'noreply@sabre.com';
	$fromname = 'Palmetto Dunes System';
	$subject = 'PalmettoDunes Booking Rate Sync Error: Properties encountered an error during rate update';
	if (count($owserror) > 1)
	{
		$message = implode("\n", $owserror);
		echo $subject . "\n" . $message."\n";
		if (is_array($emailaddresses) && !empty($emailaddresses)) {
			$mail = new Mailer(array_shift($emailaddresses), $developeremail, $fromname, $subject, $message, FALSE, FALSE);
			foreach ($emailaddresses as $emailaddress)
			{
				$mail->AddAddress($emailaddress);
			}
			$mail->sendMail();
		}
		else
		{
			echo "\nNO EMAIL ADDRESS SET FOR ABORTED PROPERTY RATE CACHING\n";
		}
	}
	
	echo 'Opera time: '.$totaloperatime."\n";
	echo 'Total runtime: '.(date('U')-$runstart)."\n";
	//$availability=$ows->fetchcalendar('2014-02-04','2014-03-01','2OVVM7');
	//print_r($ows->getdebuginfo());
	//print_r($availability->getratetable());
?>
