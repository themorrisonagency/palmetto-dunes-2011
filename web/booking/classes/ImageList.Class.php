<?php
class ImageList extends GenericAdmin
{

	var $cachetime=60;
	var $cachedir=false;
	var $failcache=false;
	var $status='normal';

	function __construct($id='',$db=false)
	{
		if($db)
		{
			$this->db=$db;
			$this->id=$id;
		}
		else
			parent::__construct();

		if(defined('DB_CACHE_FAIL'))
			$this->failcache=DB_CACHE_FAIL;
		if(defined('DB_CACHE_DIR'))
			$this->cachedir=DB_CACHE_DIR;
		elseif(defined('GLOBAL_DB_CACHE_DIR'))
			$this->cachdir='GLOBAL_DB_CACHE_DIR'.$this->getgenericcache();
		else
			$this->cachedir='/var/www/cache/'.$this->getgenericcache();
		if(!is_dir($this->cachedir))
				mkdir($this->cachedir);
		if(defined('DB_CACHE_TTL'))
			$this->cachettl=DB_CACHE_TTL;
	}


	function getlist()
	{
		$this->data=array();
		if(!$this->checkcache())
		{
			$sql='select property, id, file_name as filename, caption from property_images where is_active=1 and image_hash IS NOT NULL order by property, order_id, filename';
			$dbtest=$this->db->Query($sql);
			if(!$dbtest&&$this->failcache)
			{
				if(!$this->checkcache(true))  //query failed must use cache if available
				{
					throw new Exception('Could not retrieve image list.  Database and caching failed.');
				}
			}
			elseif($dbtest)
			{
				while($temp=$this->db->fetchAssoc())
				{
					if(!is_array($this->data[$temp['property']]))
						$this->data[$temp['property']]=array();
					$this->data[$temp['property']][]=array('id'=>$temp['id'],'filename'=>$temp['filename'], 'caption'=>$temp['caption']);
				}
				$this->cachedata($this->data);
			}
			else
				throw new Exception('Could not retrieve image list.  Database connection failed.');
		}
		return $this->data;
	}

	function getgenericcache()
	{
		//this may need to be made more specific
		$dir=$_SERVER['SERVER_NAME'];
		return $dir;
	}

	function checkcache($force=false)
	{
		//add check for forced clear cache here
		if(!is_numeric($this->cachetime)&&!$force)
			return false;
		$fname=$this->cachedir.'/'.$this->getcachefilename().'.csh';
		if(!file_exists($fname))
			return false;
		if((filemtime($fname)+$this->cachetime<date('U'))&&!$force)
			return false;
		if($force)
			$this->status='forced';
		else
			$this->status='cached';
		$temp=file_get_contents($fname);
		$this->data=unserialize($temp);
		return true;
	}

	function getcachefilename()
	{
		return 'imagelist';
	}

	function cachedata($data)
	{
		if(!$this->cachedir)
			return false;
		$f=fopen($this->cachedir.'/'.$this->getcachefilename().'.csh', 'w');
		fwrite($f, serialize($data));
		fclose($f);
	}

	function setcachetime($time)
	{
		$this->cachetime=$time;
	}
}
?>
