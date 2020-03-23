<?php
namespace Concrete\Package\PalmettoDunesSpecialOffers\Controller\SinglePage\Dashboard\SpecialOffers;

use Concrete\Core\Attribute\Key\CollectionKey;
use Concrete\Core\Tree\Node\Node;
use Concrete\Core\Tree\Node\Type\Topic;
use Concrete\Core\Tree\Node\Type\TopicCategory;
use Concrete\Package\Calendar\Src\Calendar;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Package\PalmettoDunesSpecialOffers\Src\SpecialOfferList;
use URL;

class Schedule extends DashboardPageController
{

    public function view($year = null, $month = null, $msg = null)
    {

        if (!$year) {
            $year = date('Y');
        }
        if (!$month) {
            $month = date('m');
        }

        $year = intval($year, 10);
        $month = intval($month, 10);


        $offer_list = new SpecialOfferList();
        $offer_list->includeInactive();
        $start = strtotime("{$year}-{$month}-01 00:00:00");
        $end = strtotime('+1 month', $start) - 1;
        $offer_list->filterByActive(
            strtotime("{$year}-{$month}-01 00:00:00"),
            $end);

        if (isset($_REQUEST['topic_id'])) {
            $topic_id = intval($_REQUEST['topic_id'], 10);
            $topic = Node::getByID($topic_id);

            if ($topic instanceof Topic) {
                $this->set('topic', $topic);
                $offer_list->filterByAttribute('special_offer_topics', $topic->getTreeNodeID());
            }
        }


        $dates = array();
        $offers = array();

        /** @var \Page $offer */
        foreach ($offer_list->get() as $offer) {
            $offers[$offer->getCollectionID()] = $offer;
            $start_day = strtotime('midnight', strtotime($offer->getCollectionDatePublic()));
            $end_day = strtotime('midnight', strtotime($offer->getAttribute('special_offer_end_date')));

            $today = $start_day;
            while ($today <= $end_day) {
                list($offerYear, $offerMonth, $day) = array_map(function($i) {
                    return intval($i);
                }, explode(' ', date('Y m d', $today)));

                if (!isset($dates[$offerYear])) $dates[$offerYear] = array();
                if (!isset($dates[$offerYear][$offerMonth])) $dates[$offerYear][$offerMonth] = array();
                if (!isset($dates[$offerYear][$offerMonth][$day])) $dates[$offerYear][$offerMonth][$day] = array();

                $dates[$offerYear][$offerMonth][$day][] = $offer->getCollectionID();

                $today = strtotime('midnight tomorrow', $today);
            }
        }

        $this->set('dates', $dates);
        $this->set('offers', $offers);

        $monthYearTimestamp = strtotime($year . '-' . $month . '-01');
        $firstDayInMonthNum = date('N', $monthYearTimestamp);
        if($firstDayInMonthNum == 7) {
            $firstDayInMonthNum = 0;
        }
        $nextLinkYear = $year;
        $previousLinkYear = $year;
        $nextLinkMonth = $month + 1;
        $previousLinkMonth = $month - 1;
        if ($month == 12) {
            $nextLinkMonth = 01;
        }
        if ($month == 01) {
            $previousLinkMonth = 12;
        }
        if ($month == 12) {
            $nextLinkYear = $year + 1;
        }
        if ($month == 01) {
            $previousLinkYear = $year - 1;
        }

        $nextLink = URL::to('/dashboard/special_offers/schedule', $nextLinkYear, $nextLinkMonth) .
            '?topic_id=' . $this->request->get('topic_id');
        $previousLink = URL::to('/dashboard/special_offers/schedule', $previousLinkYear, $previousLinkMonth) .
            '?topic_id=' . $this->request->get('topic_id');
        $todayLink = URL::to('/dashboard/special_offers/schedule') .
            '?topic_id=' . $this->request->get('topic_id');
        $this->set('monthText', date('F', $monthYearTimestamp));
        $this->set('month', $month);
        $this->set('year', $year);
        $this->set('daysInMonth', date('t',$monthYearTimestamp));
        $this->set('firstDayInMonthNum', $firstDayInMonthNum);
        $this->set('nextLink', $nextLink);
        $this->set('previousLink', $previousLink);
        $this->set('todayLink', $todayLink);




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
        $this->set('topics', $filterTopics);
    }

}
