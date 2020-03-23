<?php

namespace Concrete\Package\PalmettoDunesBlog\Src;

use Concrete\Core\Page\Page;
use Concrete\Core\Page\PageList;

class BlogEntryList extends PageList
{

    /** @var \Concrete\Core\Page\Page */
    protected $blog;

    /** @var boolean */
    protected $filterByArchived;

    public function __construct(Page $blog = null)
    {
        parent::__construct();

        $this->blog = $blog;

        $this->ignorePermissions();
        if ($this->blog) {
            $this->filterByParentID($this->blog->getCollectionID());
        }
        $this->filterByPageTypeHandle('blog_entry');
        $this->setItemsPerPage(5);
        $this->sortByPublicDateDescending();
    }

    public function deliverQueryObject()
    {
        if (isset($this->filterByArchived)) {
            $this->filterByBlogEntryIsArchived($this->filterByArchived);
        }
        return parent::deliverQueryObject();
    }

    public function filterByArchived($boolean)
    {
        $this->filterByArchived = $boolean;
    }
}