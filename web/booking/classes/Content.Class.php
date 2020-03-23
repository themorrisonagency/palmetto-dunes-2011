<?php
class Content extends GenericAdmin
{
	
	function getcontentbyid($id)
	{
		if(!is_numeric($id))
			Throw new Exception('Invalid content id');
		$this->id=$id;
		$sql='select id, content
		from content
		where id='.$this->id;
		$this->db->Query($sql);
		if($temp=$this->db->fetchAssoc())
		{
			$this->data=$temp;
		}
		else
			Throw new Exception('Invalid content id');
	}

}
?>
