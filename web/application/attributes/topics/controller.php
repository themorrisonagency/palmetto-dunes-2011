<?php

namespace Application\Attribute\Topics;
//use Concrete\Attribute\Topics\Controller;

use Concrete\Core\Search\ItemList\Database\AttributedItemList;
use Concrete\Core\Tree\Node\Node;
use Concrete\Core\Tree\Type\Topic as TopicTree;
use Concrete\Core\Tree\Tree;
use Concrete\Core\Tree\Node\Node as TreeNode;
use Concrete\Core\Attribute\Controller as AttributeTypeController;
use Core;
use Database;

class Controller extends \Concrete\Attribute\Topics\Controller
{
	
	public function filterByAttribute(AttributedItemList $list, $value, $comparison = '=')
	{
		if (is_array($value)) {
			$topics = $value;
		} else {
			$topics = array($value);
		}
		
		$i = 0;
		$expressions = array();
		$qb = $list->getQueryObject();
		foreach($topics as $value) {
			if ($value instanceof TreeNode) {
				$topic = $value;
			} else {
				$topic = Node::getByID(intval($value));
			}
			if (is_object($topic) && $topic instanceof \Concrete\Core\Tree\Node\Type\Topic) {
				$column = 'ak_' . $this->attributeKey->getAttributeKeyHandle();
				$expressions[] = $qb->expr()->like($column, ':topicPath' . $i);
				$qb->setParameter('topicPath' . $i, "%||" . $topic->getTreeNodeDisplayPath() . '%||');
				$i++;
			}
			
		}

		if($i>0)
		{
			$expr = $qb->expr();
			$qb->andWhere(call_user_func_array(array($expr, 'orX'), $expressions));
		}
	}
}
