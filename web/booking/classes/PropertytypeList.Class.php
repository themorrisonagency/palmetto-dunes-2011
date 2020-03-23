<?php
class PropertytypeList extends GenericAdmin
{
        protected $idmap= null;
	
	protected function getsimplelist()
	{
		if(is_array($this->idmap))
			return true;
		$this->idmap=array();
		$sql='select id, property_type as name from property_types order by id';
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
			$sql='insert into property_types(property_type) values(\''.addslashes($name).'\')';
			$this->db->Query($sql);
			$newid=$this->db->getlastId();
			$this->namemap[$name]=$newid;
			$this->idmap[$newid]=$name;
			return $newid;
			//throw new Exception('No matching type for '.$name);
		}
	}
}
?>
