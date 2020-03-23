<?php
class LocationareaList extends GenericAdmin
{

        protected $idmap = null;
	
	protected function getsimplelist()
	{
		if(is_array($this->idmap))
			return true;
		$this->idmap=array();
		$sql='select id, name from location_areas order by id';
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
			throw new Exception('No matching area for '.$name);
	}
}
?>
