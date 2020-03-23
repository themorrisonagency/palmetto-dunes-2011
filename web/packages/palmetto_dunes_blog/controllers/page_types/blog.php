<?php

namespace Concrete\Package\PalmettoDunesBlog\Controller\PageType;

use Concrete\Core\Attribute\Key\CollectionKey;
use Concrete\Core\Page\Controller\PageTypeController;
use Concrete\Core\Tree\Node\Node;
use Concrete\Core\Tree\Node\Type\Topic;
use Concrete\Core\Tree\Node\Type\TopicCategory;
use Concrete\Package\PalmettoDunesBlog\Src\BlogEntryList;

class Blog extends PageTypeController
{

    protected $blog;
    protected $list;

    public function on_start()
    {
        // register the proper blog
        if (!isset($this->blog)) {
            $this->blog = $this->getPageObject();
        }
    }

    public function view()
    {
        // load stylesheet
        $theme = $this->getPageObject()->getCollectionThemeObject();
        $stylesheet = $theme->getThemeURL() . '/css/conversations.custom.css';
        $this->addHeaderItem(sprintf('<link rel="stylesheet" type="text/css" href="%s">', $stylesheet));

        // load recent posts
        $this->loadRecentPosts();
        $this->loadTags();
        $this->loadCategories();

        // load body blogs on blog listing page
        $this->loadBlogListing();
    }

    public function tag($tag = null)
    {
        $this->view();
        if ($tag) {
            $this->list->filterByBlogEntryTags(h($tag));
            $this->list->filterByArchived(null);
        }
    }

    public function archives($year = null, $month = null)
    {
        $this->view();
        $this->list->filterByArchived(true);
        if ($year) {
            if ($month) {
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);
                $lastDayInMonth = date('t', strtotime("$year-$month-01"));
                $start = "$year-$month-01 00:00:00";
                $end = "$year-$month-$lastDayInMonth 23:59:59";
            } else {
                $start = "$year-01-01 00:00:00";
                $end = "$year-12-31 23:59:59";
            }
            $this->list->filterByPublicDate($start, '>=');
            $this->list->filterByPublicDate($end, '<=');
        }
        $this->archives = true;
    }

    public function search()
    {
        $this->view();
        $keywords = $this->request->get('search');
        if ($keywords) {
            $this->list->filterByKeywords($keywords);
            $this->list->filterByArchived(null);
        }
    }

    /**
     * @param string $tag
     */
    public function category($category = null)
    {
        $this->view();
        if ($category) {
            $node = Topic::getNodeByName($category);
            if (is_object($node)) {
                $this->list->filterByBlogEntryTopics($node);
                $this->list->filterByArchived(null);
            }
        }
    }

    public function on_before_render()
    {
        $pagination = $this->list->getPagination();
        $results = $pagination->getCurrentPageResults();
        $this->set('entries', $results);
        $this->set('pagination', $pagination);
        $this->set('blog', $this->blog);
    }

    protected function loadBlogListing()
    {
        $this->list = new BlogEntryList($this->blog);
        $this->list->ignorePermissions();
        $this->list->setItemsPerPage(10);
        $this->list->filterByArchived(false);

    }

    public function rss()
    {

        //header('Content-Type: text/xml');
        $list = new BlogEntryList($this->blog);
        $list->filterByArchived(false);
        $results = $list->getResults();

        $writer = new \Zend\Feed\Writer\Feed();
        $writer->setTitle($this->blog->getCollectionName() . ' – RSS Feed');
        $writer->setDescription($this->blog->getCollectionDescription() . ' – RSS Feed');
        $writer->setLink($this->blog->getCollectionLink(true));
        foreach($results as $p) {
            $entry = $writer->createEntry();
            $entry->setTitle($p->getCollectionName());
            $entry->setDateCreated(strtotime($p->getCollectionDatePublic()));
            if ($p->getCollectionDescription()) {
                $entry->setDescription($p->getCollectionDescription());
            } else {
                $entry->setDescription(t('No description.'));
            }
            $entry->setLink($p->getCollectionLink(true));
            $writer->addEntry($entry);
        }

        print $writer->export('rss');
        exit;
    }

    protected function loadRecentPosts()
    {
        $list = new BlogEntryList($this->blog);
        $list->filterByArchived(false);
        $results = $list->getResults();
        $this->set('recentPosts', $results);
    }

    protected function loadTags()
    {
        $ak = CollectionKey::getByHandle('blog_entry_tags');
        $akc = $ak->getController();
        $usageArray = $akc->getOptionUsageArray($this->blog);

        $tags = array();
        foreach($usageArray as $t) {
            $count = $t->getSelectAttributeOptionUsageCount();
            $tag = new \stdClass;
            $tag->displayValue = $t->getSelectAttributeOptionDisplayValue();
            $tag->value = $t->getSelectAttributeOptionValue();
            $tag->count = $count;
            $tag->fontSize = $this->getTagFontSize($count - 1 / count($usageArray));
            $tag->searchValue = strtolower(\Core::make("helper/text")->encodePath($tag->value));
            $tags[] = $tag;
        }

        usort($tags, function($tag1, $tag2) {
            return strnatcasecmp($tag1->value, $tag2->value);
        });

        $this->set('tags', $tags);
    }

    protected function loadCategories()
    {
        $ak = CollectionKey::getByHandle('blog_entry_topics');
        $akc = $ak->getController();
        $node = Node::getByID($akc->getTopicParentNode());
        if ($node instanceof TopicCategory) {
            $node->populateChildren();
            $this->set('categories', $node->getChildNodes());
        }
    }

    protected function getTagFontSize($weight)
    {
        $tagMinFontPx = '12';
        $tagMaxFontPx = '50';
        $weight = $weight * 0.01;
        $em = ($weight * ($tagMaxFontPx - $tagMinFontPx)) + $tagMinFontPx;
        $em = round($em);
        return $em;
    }
}