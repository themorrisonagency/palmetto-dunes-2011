<?php
class BedtypeList extends GenericAdmin
{
        protected $idmap= null;
	
	protected function getsimplelist()
	{
                $this->db->throwExceptionsOnError = true;
		if(is_array($this->idmap))
			return true;
		$this->idmap=array();
		$sql='select id, name from bedtypes order by id';
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
			$sql='insert into bedtypes(name) values(\''.addslashes($name).'\')';
			$this->db->Query($sql);
			$newid=$this->db->getlastId();
			$this->namemap[$name]=$newid;
			$this->idmap[$newid]=$name;
			return $newid;
			//throw new Exception('No matching type for '.$name);
		}
	}
}
