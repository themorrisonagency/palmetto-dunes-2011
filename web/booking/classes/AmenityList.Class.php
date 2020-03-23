<?php
class AmenityList extends GenericAdmin
{
        protected $idmap= null;

	function getlist()
	{
		$sql='select id, amenity as name
			from amenities
			where is_active=1
			order by order_id, amenity';
		$this->db->Query($sql);
		$return=array();
		while($temp=$this->db->fetchAssoc())
			$return[]=$temp;
		return $return;
	}

	protected function getsimplelist()
	{
                $this->db->throwExceptionsOnError = true;
		if(is_array($this->idmap))
			return true;
		$this->idmap=array();
		$sql='select id, amenity as name
			from amenities
			order by order_id, amenity';
		$this->db->Query($sql);
		while($temp=$this->db->fetchAssoc())
		{
			$this->idmap[$temp['id']]=$temp['name'];
			$this->namemap[$temp['name']]=$temp['id'];
		}
		return true;
	}

	function getid($name)
	{
                $this->db->throwExceptionsOnError = true;
		if(!is_array($this->idmap))
			$this->getsimplelist();
		
		if(isset($this->namemap[$name]))
			return $this->namemap[$name];
		else
		{
			$sql='insert into amenities(amenity, is_active) values(\''.addslashes($name).'\', 1)';
			$query = $this->db->Query($sql);
			$newid=$this->db->getlastId();
			$this->namemap[$name]=$newid;
			$this->idmap[$newid]=$name;
			return (int)$newid;
		}
	}
}
?>
