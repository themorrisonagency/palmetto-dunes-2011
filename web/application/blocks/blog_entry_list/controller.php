<?php
namespace Application\Block\BlogEntryList;

use Concrete\Core\Attribute\Key\CollectionKey;
use Concrete\Core\Block\BlockController;
use Concrete\Package\PalmettoDunesBlog\Src\BlogEntryList;
use Core;
use Page;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{

    public $helpers = array('form');

    protected $btInterfaceWidth = 500;
    protected $btInterfaceHeight = 340;
    protected $btTable = 'btBlogEntryList';
    protected $btCacheBlockRecord = false;

    public function getBlockTypeDescription()
    {
        return t("Displays a list of blog entries.");
    }

    public function getBlockTypeName()
    {
        return t("Blog Entry List");
    }

    public function add()
    {
        $this->edit();
        $this->set('buttonLinkText', t('View Blog'));
        $this->set('blogEntryListTitle', t('Blog'));
        $this->set('totalToRetrieve', 5);
    }

    public function view()
    {
        $list = new BlogEntryList();
        if ($this->filterByFeatured) {
            $list->filterByAttribute('is_featured', true);
        }
        if ($this->filterByTopicAttributeKeyID) {
            $ak = CollectionKey::getByID($this->filterByTopicAttributeKeyID);
            if (is_object($ak)) {
                $list->filterByAttribute($ak->getAttributeKeyHandle(), $this->filterByTopicID);
            }
        }
        $pagination = $list->getPagination();
        $pagination->setMaxPerPage($this->totalToRetrieve);
        $results = $pagination->getCurrentPageResults();
        $this->set('entries', $results);
        if ($this->internalLinkCID) {
            $blogPage = \Page::getByID($this->internalLinkCID);
            if (is_object($blogPage) && !$blogPage->isError()) {
                $this->set('blogPage', $blogPage);
            }
        }
        $this->loadKeys();
    }

    public function edit()
    {
        $this->requireAsset('core/topics');
        $this->set('pageSelector', Core::make("helper/form/page_selector"));
        $this->set('featuredAttribute', CollectionKey::getByHandle('is_featured'));
        $this->loadKeys();
    }

    protected function loadKeys()
    {
        $keys = CollectionKey::getList(array('atHandle' => 'topics'));
        $this->set('attributeKeys', array_filter($keys, function($ak) {
            return $ak->getAttributeTypeHandle() == 'topics';
        }));
    }

    public function save($args)
    {
        $args['filterByFeatured'] = intval($args['filterByFeatured']);
        if (!$args['filterByTopicAttributeKeyID']) {
            $args['filterByTopicID'] = 0;
        }
        parent::save($args);
    }

}
