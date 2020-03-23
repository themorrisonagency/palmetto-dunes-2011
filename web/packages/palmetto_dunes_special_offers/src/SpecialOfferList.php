<?php

namespace Concrete\Package\PalmettoDunesSpecialOffers\Src;

use Concrete\Core\Page\PageList;

class SpecialOfferList extends PageList
{

    /** @var boolean */
    protected $includeInactive = false;

    public function __construct()
    {
        parent::__construct();

        $this->ignorePermissions();
        $this->filterByPageTypeHandle('special_offer');
        $this->setItemsPerPage(10);
        $this->sortByPublicDateDescending();
    }

    public function deliverQueryObject()
    {
        if (!$this->includeInactive) {
            $this->filterBySpecialOfferIsDeactivated(false);
        }
        return parent::deliverQueryObject();
    }

    public function includeInactive()
    {
        $this->includeInactive = true;
    }

    public function filterByActive($start, $end=null)
    {
        if (!$end) {
            $end = $start;
        }
        $this->filterByPublicDate(date('Y-m-d H:i:s', $end), "<=");
        $this->filterByAttribute('special_offer_end_date', date('Y-m-d H:i:s', $start), ">=");
    }

    public function filterByPageIDs($pageIds=array(), $bID)
    {
        $db = \Database::get();
        $this->query->join('p', 'btSpecialOfferListItems', 'ssoi', 'ssoi.pageID = p.cID');
        $this->query->join('p', 'btSpecialOfferList', 'ssol', 'ssol.bID = ssoi.bID');
        $this->query->andWhere('ssol.bID = :bID');
        $this->query->setParameter('bID', $bID);
        $this->query->andWhere(
            $this->query->expr()->in('p.cID', array_map(array($db, 'quote'), $pageIds))
        );
        $this->query->orderBy('ssoi.sort', 'asc');
    }

}
