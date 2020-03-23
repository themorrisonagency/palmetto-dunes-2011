<?php

namespace weblink;

class PropertyIndexesResponse extends WeblinkResponse
{
	protected $data=null;
	protected $propertyids=false;
	

	const resultname = 'getPropertyIndexesResult';

	
	public function __construct($result)
	{
		parent::__construct($result, self::resultname);
	}
	
	function getpropertyids()
	{
		if(!$this->propertyids)
		{
			$this->propertyids=array();
			foreach($this->data['clsPropIndex'] as $p)
			{
				$this->propertyids[]=$p['strId'];
			}
		}
		return $this->propertyids;
	}

}
