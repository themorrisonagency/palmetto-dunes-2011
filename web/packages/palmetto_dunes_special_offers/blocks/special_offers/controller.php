<?php
namespace Concrete\Package\PalmettoDunesSpecialOffers\Block\SpecialOffers;

use \Concrete\Core\Block\BlockController;
use Concrete\Core\Tree\Node\Node;
use Concrete\Core\Tree\Node\Type\Topic;
use Concrete\Package\PalmettoDunesSpecialOffers\Src\SpecialOfferList;
use Loader;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends BlockController
{

    protected $btInterfaceWidth = 500;
    protected $btInterfaceHeight = 340;
    protected $btTable = 'btSpecialOfferList';

    public function getBlockTypeDescription()
    {
        return t("Display a list of special offers");
    }

    public function getBlockTypeName()
    {
        return t("Special Offers");
    }

    public function view()
    {
        $ol = new SpecialOfferList();
        $ol->filterByActive(time());


        // Filter by
        if ($this->filterByTopicID > 0 || $this->filterBy == 'topic'){ // Topic

            $node = Node::getByID($this->filterByTopicID);
            if ($node) {
                $ol->filterBySpecialOfferTopics($node);
            }

        } else if ($this->filterBy == 'selected') { // Selected Offers
            $db = Loader::db();
            $items = $db->GetAll('SELECT pageID from btSpecialOfferListItems WHERE bID = ? ORDER BY sort', array($this->bID));
            
            $ids = array();
            foreach ($items as $item) {
                $ids[] = $item['pageID'];
            }
            $ol->filterByPageIDs($ids, $this->bID);

        }

        $this->set('results', $ol->getResults());
        $this->set('list', $ol);
    }

    public function getAttributeLink($c, $akHandle)
    {
        $n = $c->getAttribute($akHandle);
        if (\Core::make("helper/validation/numbers")->integer($n)) {
            $p = \Page::getByID($n, 'ACTIVE');
            return $p->getCollectionLink();
        } else {
            return $n;
        }
    }

    public function add()
    {
        $this->edit();
    }

    public function edit()
    {
        $this->requireAsset('core/topics');
        $this->requireAsset('core/sitemap');

        $db = Loader::db();
        $items = $db->GetAll('SELECT * from btSpecialOfferListItems WHERE bID = ? ORDER BY sort', array($this->bID));
        $this->set('items', $items);
    }

    public function save($data)
    {
        if ($data['filterBy'] != 'topic') {
            $data['filterByTopicID'] = 0;
        }
        $db = Loader::db();
        $db->execute('DELETE from btSpecialOfferListItems WHERE bID = ?', array($this->bID));
        $count = count($data['sort']);
        $i = 0;
        parent::save($data);
        while ($i < $count) {
            $vals = array($this->bID,$data['pageID'][$i],$data['sort'][$i]);
            $p = \Page::getByID($data['pageID'][$i]);
            $pt = $p->getPageTypeHandle();
            if ($pt === "special_offer") {
                $db->execute('INSERT INTO btSpecialOfferListItems (bID, pageID, sort) values(?,?,?)', $vals);
            }
            $i++;
        }

    }

    public function validate($data) {
        $e = Loader::helper('validation/error');
        $count = count($data['sort']);

        if ($data['filterBy'] == 'topic' && $data['filterByTopicID'] == 0) {
            $e->add(t('Please select an offer category'));
        } elseif ($data['filterBy'] == 'selected' && $count == null) {
            $e->add(t('Please add an offer.'));
        } elseif ($data['filterBy'] == 'selected') {
            $i = 0;
            while ($i < $count) {
                $p = \Page::getByID($data['pageID'][$i]);
                $pt = $p->getPageTypeHandle();
                if (!$data['pageID'][$i]) {
                    $e->add(t('Please select a page.'));
                } elseif ($pt != "special_offer") {
                    $e->add(t('Please select a special offer. ').$p->getCollectionName().t(' is not a special offer.'));
                }
                $i++;
            }
        }

        return $e;
    }

    public function delete()
    {
        $db = Loader::db();
        $db->delete('btSpecialOfferListItems', array('bID' => $this->bID));
        parent::delete();
    }

    public function action_offer($offer_id=0)
    {
        $offer_id = intval($offer_id, 10);
        $page = \Page::getByID($offer_id);

        if ($page && is_object($page) && !$page->isError()) {
            $this->set('offer_id', $offer_id);
        }

        $this->view();
    }

    public function action_topic($topic_id=0, $topic_name=null)
    {
        $node = Node::getByID(intval($topic_id, 10));
        if ($node) { $ol = new SpecialOfferList();
            $ol->filterBySpecialOfferTopics($node);
            $this->set('results', $ol->getresults());
            $this->set('list', $ol);
        } else {
            $this->view();
        }
    }

}
