<?php
namespace Concrete\Package\PalmettoDunesSpecialOffers\Block\SpecialOffersTopicList;
use Concrete\Core\Attribute\Key\CollectionKey;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Tree\Node\Node;
use Concrete\Core\Tree\Node\Type\Topic;
use Concrete\Core\Tree\Node\Type\TopicCategory;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends BlockController
{

    public function getBlockTypeDescription()
    {
        return t("Filter special offers on palmettodunes.com.");
    }

    public function getBlockTypeName()
    {
        return t("Special Offers topic list");
    }

    public function getTopicLink(Node $topic)
    {
        $view = \View::getInstance();
        $node_name = preg_replace(
            '/_{2,}/', '_',
            str_replace(
                ' ', '_',
                preg_replace(
                    '/[^a-z0-9 ]/', '',
                    strtolower($topic->getTreeNodeDisplayName()))));
        return $view->action('topic', $topic->getTreeNodeID(), $node_name);
    }

    public function view()
    {
        $filterTopics = array();
        $ak = CollectionKey::getByHandle('special_offer_topics');
        if (is_object($ak)) {
            $node = Node::getByID($ak->getController()->getTopicParentNode());
            if ($node instanceof TopicCategory) {
                $node->populateChildren();
                $filterTopics = $node->getChildNodes();
                $validIDs = array_map(function(Node $node) {
                    return $node->getTreeNodeID();
                }, $filterTopics);
            }
        }
        $this->set('filterTopics', $filterTopics);
    }

    public function action_topic($topic_id=0, $topic_name=null)
    {
        $this->view();
        $this->set('active_topic', $topic_id);
    }

}
