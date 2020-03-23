<?php
class ViewList extends GenericAdmin
{

        protected $idmap = null;

	function getlist()
	{
		$sql='select v.id as id, v.theview as name, 
				count(homes.id) as homes, count(villas.id) as villas 
			from theviews v
			left join properties homes on
				(homes.property_type=1
				and homes.theview=v.id
				and homes.is_active=1
				and homes.is_deleted=0)
			left join properties villas on
				(villas.property_type=2
				and villas.theview=v.id
				and villas.is_active=1
				and villas.is_deleted=0)
			where v.is_active=1 AND length(v.theview) > 0 
			group by v.id
			order by order_id, v.theview';
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
		$sql='select id, theview as name from theviews order by id';
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
			$sql='insert into theviews(theview, is_active, date_created) values(\''.addslashes($name).'\', 1, \''.date('Y-m-d H:i:s').'\')';
			$this->db->Query($sql);
			$newid=$this->db->getlastId();
			$this->namemap[$name]=$newid;
			$this->idmap[$newid]=$name;
			return $newid;
			//throw new Exception('No matching view');
		}
	}
}
?>
