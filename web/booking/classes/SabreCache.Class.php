<?php
class SabreCache
{
	var $hash;
	var $cachedir;
	var $status=false;
	
	function __construct($id, $subdir)
	{
		$this->hash=md5($id);
		
		if(defined('CACHE_DIR'))
			$this->cachedir=CACHE_DIR.'/'.$subdir;
		elseif(defined('GLOBAL_CACHE_DIR'))
			$this->cachedir=GLOBAL_CACHE_DIR.'/'.$this->getgenericcache().'/'.$subdir;
		else
			$this->cachedir='/var/www/cache/'.$this->getgenericcache().'/'.$subdir;
		if(!is_dir($this->cachedir))
				mkdir($this->cachedir, 0770, true);
	}
	
	function getgenericcache()
	{
		//this may need to be made more specific
		$dir=$_SERVER['SERVER_NAME'];
		return $dir;
	}
	
	//returns cache status
	function checkcache()
	{
		//add check for forced clear cache here
		$fname=$this->cachedir.'/'.$this->hash.'.cache';
		if(!file_exists($fname))
		{
			$this->status='none';
			return false;
		}
		if((filemtime($fname)+$this->cachetime<date('U')))
		{
			$this->status='expired';
			return false;
		}
		$this->status='cached';
		return $this->status;
	}
	
	//returns content of cache or false if not present
	function getcache()
	{
		$fname=$this->cachedir.'/'.$this->hash.'.cache';
		$temp=@file_get_contents($fname);
		return $temp;
	}
	
	function getstatus()
	{
		if(!$this->status)
			$this->checkcache();
		return $this->status;
	}
	
	//writes new cache value, returns true on success
	function writecache($data)
	{
		if(!(strlen($this->cachedir)>0))
			return false;
		$f=fopen($this->cachedir.'/'.$this->hash.'.cache', 'w');
		fwrite($f, $data);
		fclose($f);
	}
	
	function setlocation($dir)
	{
		$this->cachedir=$dir;
	}
	
	function setcachetime($time)
	{
		$this->cachetime=$time;
	}
}
?>
