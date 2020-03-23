<?php

require_once('esite/config/conf.php');

$dir = dirname(dirname(dirname(__FILE__)));

#var_dump( $dir . '/classes/SabreCache.Class.php' );
#var_dump( file_exists( $dir . '/classes/SabreCache.Class.php' ) );
#var_dump( is_readable( $dir . '/classes/SabreCache.Class.php' ) );
#exit;

require_once($dir.'/classes/SabreCache.Class.php');

class amaxus extends ModuleInstance
{
	function _getpage()
	{
		//$url='http://www.palmettodunes.com/site-map/?showXml';
		$url=ModuleParameter::getParameter('url',$this);
		$fallback=ModuleParameter::getParameterIfSet('fallback', false, $this);
		$cache=new SabreCache($url,'amaxus');
		$cache->setcachetime(1800);
		$htmlstring='';
		if($cache->getstatus()!='cached')
		{
			try
			{
				ini_set('default_socket_timeout', 3);
				$htmlstring=file_get_contents($url, false, stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ),
                )));
                if ($htmlstring === false) {
                    $this->returnValue['file_get_contents_failure'] = 'true';
                }
				$htmlstring=preg_replace('/.*\<article\>/s','',$htmlstring);
				$htmlstring=preg_replace('/\<\/article\>.*/s', '', $htmlstring);
				// replace image paths to Amaxus upload folder
				//$htmlstring=preg_replace('/src=\"http:\/\/www.palmettodunes.com\/upload\/img\//','src="/amaxus-upload/',$htmlstring);
				// replace images paths to Amaxus PD custom folder
				//$htmlstring=preg_replace('/src=\"http:\/\/www.palmettodunes.com\/custom\/a4_palmettodunes\/img\//','src="/amaxus-custom/',$htmlstring);
				// modify src attributes to pull via SSL
				$htmlstring=preg_replace('/src=\"http:\/\//','src="https://',$htmlstring);

				if(strlen($htmlstring))
				{
					$cache->writecache($htmlstring);
				}
				elseif($cache->getstatus()=='expired')
					$htmlstring=$cache->getcache();
				else
					$htmlstring=$this->getfallbacknav($fallback);
			}
			catch(Exception $e)
			{
				if($cache->getstatus()!='none')
					$htmlstring=$cache->getcache();
				else
					$htmlstring=$this->getfallback($fallback);
				$this->returnValue['#html']=$htmlstring;
				return $this->returnValue;
			}
		}
		else
		{
			$htmlstring=$cache->getcache();
		}
		$this->returnValue['#html']=$htmlstring;
		return $this->returnValue;
	}
	
	function getfallbacknav($fallback)
	{
		return $fallback;
	}
}
?>
