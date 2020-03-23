<?php
class LocationList extends GenericAdmin
{
	protected $idmap=false;
	protected $namemap=false;
	
	function getlist()
	{
		$sql='select l.id as id, l.location as name,
				count(homes.id) as homes, count(villas.id) as villas
			from locations l
			left join properties homes on
				(homes.property_type=1
				and homes.location=l.id
				and homes.is_active=1
				and homes.is_deleted=0)
			left join properties villas on
				(villas.property_type=2
				and villas.location=l.id
				and villas.is_active=1
				and villas.is_deleted=0)
			where l.active=1
			group by l.id
			order by l.location';
		$this->db->Query($sql);
		$return=array();
		while($temp=$this->db->fetchAssoc())
			$return[]=$temp;
		return $return;
	}
	
	protected function getsimplelist()
	{
		if(is_array($this->idmap))
			return true;
		$this->idmap=array();
		$sql='select id, location as name from locations order by id';
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
		if(!is_array($this->idmap))
			$this->getsimplelist();
		
		if(isset($this->namemap[$name]))
			return $this->namemap[$name];
		else
		{
			$sql='insert into locations(location, active) values(\''.addslashes($name).'\', 1)';
			$this->db->Query($sql);
			$newid=$this->db->getlastId();
			$this->namemap[$name]=$newid;
			$this->idmap[$newid]=$name;
			return $newid;
		}
	}
}
?>
