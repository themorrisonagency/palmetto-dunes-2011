<?php
namespace Application\Block\EventStream;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Tree\Node\Node;
use Concrete\Core\Tree\Node\Type\TopicCategory;
use Concrete\Package\Calendar\Src\Attribute\Key\EventKey;
use Concrete\Package\Calendar\Src\Attribute\Value\EventValue;
use Concrete\Package\Calendar\Src\Calendar;
use Concrete\Package\Calendar\Src\Event\EventOccurrence;
use Concrete\Package\Calendar\Src\Event\EventOccurrenceList;
use Core;
use Symfony\Component\HttpFoundation\JsonResponse;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{

    public $helpers = array('form');

    protected $btInterfaceWidth = 500;
    protected $btInterfaceHeight = 340;
    protected $btTable = 'btEventStream';

    protected $bID;
    protected $caID;
    protected $filterByTopicAttributeKeyID;
    protected $filterByTopicID;
    protected $filterByTopic;
    protected $eventStreamTitle;
    protected $filterByTopicIDSelectedInUI;
    protected $occurrence;

    public function getBlockTypeDescription()
    {
        return t("Displays a stream of events from a calendar.");
    }

    public function getBlockTypeName()
    {
        return t("Event Stream");
    }

    public function add()
    {
        $this->edit();
        $this->set('buttonLinkText', t('View Full Calendar'));
    }

    public function action_topic($topic = false)
    {
        $db = \Database::get();
        $treeNodeID = $db->GetOne('select treeNodeID from TreeTopicNodes where treeNodeTopicName = ?', array($topic));
        if ($treeNodeID) {
            $this->set('filterByTopicIDSelectedInUI', intval($treeNodeID));
            $this->filterByTopicIDSelectedInUI = intval($treeNodeID);
        }
        $this->view();
    }

    public function action_event($block_id, $occurrence_id = null, $title = null)
    {
        $occurrence = EventOccurrence::getByID(intval($occurrence_id, 10));
        if ($occurrence) 
        {
        	$date=new \DateTime(date('Y-m-d'));
        	$ocdate=new \DateTime(date('Y-m-d', $occurrence->getStart()));
        	if(!($date>$ocdate))
        		$this->occurrence = $occurrence;
        }

        $this->view();
    }

    public function get_occurrences()
    {
        $request = \Request::getInstance();
        $result = array('error' => null, 'params' => null);

        $block = \Block::getByID($request->get('bID', 0));
        if ($block && !$block->isError()) {
            $controller = $block->getController();

            if ($controller instanceof \Application\Block\EventStream\Controller) {
                $occurrence = EventOccurrence::getByID(intval($request->get('occurrence_id', 0), 10));
                $amount = intval($request->get('amount', 20), 10);
                $direction = strtolower($request->get('direction', 'asc')) == 'asc' ? 'asc' : 'desc';
                $topic = intval($request->get('topic', 0), 10);

                if ($topic > 0) {
                    $controller->set('filterByTopic', $topic);
                    $controller->filterByTopic = $topic;
                }

                if ($occurrence) {
                    $items = $controller->getItems($occurrence->getStart(), $direction, $amount);
                    $occurrences = array();
                    foreach ($items as $item) {
                        $occurrences[] = $controller->occurrenceToArray($item);
                    }

                    $result['params'] = $occurrences;
                } else {
                    $result['error'] = 'Invalid occurrence.';
                }
            } else {
                $result['error'] = 'Invalid block';
            }
        } else {
            $result['error'] = 'Invalid block';
        }

        id(new JsonResponse($result))->send();
        \Core::shutdown();
    }

    protected function occupiedDaysBetween($start, $end)
    {
        $db = \Database::connection();
        $res = $db->query('SELECT * FROM CalendarEventOccurrences WHERE startTime < ? && startTime > ?;', array(
            $end,
            $start
        ));

        $days = array();

        while ($row = $res->fetch()) {
            $start = $row['startTime'];
            $end = $row['endTime'];

            $start_date = new \DateTime();
            $start_date->setTimestamp($start);
            $start_date->modify('midnight');

            $date = clone $start_date;
            while ($date->getTimestamp() < $end) {
                $string = $date->format('Y.n.j');

                $amount = array_get($days, $string, 0);
                array_set($days, $string, $amount + 1);

                $date->modify('+1 day');
            }
        }

        return $days;
    }

    public function get_occupied_dates()
    {
        $request = \Request::getInstance();
        $result = array('error' => null, 'params' => null);
        $year = intval($request->get('year'), 10);

        if ($year) {
            $date = new \DateTime("{$year}/01/01");
            $start = $date->getTimestamp();
            $date->modify('+1 year');
            $end = $date->getTimestamp();
            $days = $this->occupiedDaysBetween($start, $end);

            $result['params'] = (object)$days;
        } else{
            $result['error'] = 'Invalid request.';
        }

        id(new JsonResponse($result))->send();
        \Core::shutdown();
    }

    protected function getCalendar()
    {
        return Calendar::getByID($this->caID);
    }

    protected function buildList()
    {
        $calender = $this->getCalendar();

        $list = new EventOccurrenceList();
        $list->filterByEndTimeAfter(time());

        if (is_object($calender)) {
            $list->filterByCalendar($calender);
        }

        if ($this->filterByTopicAttributeKeyID) {
            $ak = EventKey::getByID($this->filterByTopicAttributeKeyID);
            if (is_object($ak)) {
                if (isset($this->filterByTopicIDSelectedInUI)) {
                    $list->filterByAttribute($ak->getAttributeKeyHandle(), $this->filterByTopicIDSelectedInUI);
                } else {
                    $list->filterByAttribute($ak->getAttributeKeyHandle(), $this->filterByTopicID);
                }
            }
        }

        return $list;
    }

    public function getTopicLink(\Concrete\Core\Tree\Node\Node $topic = null)
    {
        $c = \Page::getCurrentPage();
        if ($topic) {
            $nodeName = $topic->getTreeNodeName();
            $nodeName = strtolower($nodeName); // convert to lowercase
            $nodeName = \Core::make('helper/text')->encodePath($nodeName); // urlencode
            return \URL::page($c, 'topic', $nodeName);
        } else {
            return \URL::page($c);
        }
    }


    public function view()
    {

        $filterTopics = array();
        if ($this->filterByTopicID) {
            $node = Node::getByID($this->filterByTopicID);
            if ($node instanceof TopicCategory) {
                $node->populateChildren();
                $filterTopics = $node->getChildNodes();
                $validIDs = array_map(function($node) {
                    return $node->getTreeNodeID();
                }, $filterTopics);
                if (isset($this->filterByTopicIDSelectedInUI) && !in_array($this->filterByTopicIDSelectedInUI, $validIDs)) {
                    unset($this->filterByTopicIDSelectedInUI);
                }
            }
        }

        $this->requireAsset('javascript', 'underscore');

        $now = time();
        $expanded = 0;

        $occurrence = $this->occurrence;
        if (!$occurrence) {
            if ($occurrence_id = \Request::getInstance()->get('pd_stream' . $this->bID . '_ev')) {
                $occurrence = EventOccurrence::getByID(intval($occurrence_id, 10));
            } elseif ($occurrence_id = \Request::getInstance()->get('pd_stream_ev')) {
                $occurrence = EventOccurrence::getByID(intval($occurrence_id, 10));
            }
        } else {
            $now = $occurrence->getStart();
            $expanded = $occurrence->getID();
        }


        $dh = \Core::make('helper/date');
        if ($start_time = $dh->toDateTime(\Request::getInstance()->get('pd_stream' . $this->bID . '_date'), 'user', 'user')) {
            $now = $start_time->getTimestamp();
        } else if ($start_time = $dh->toDateTime(\Request::getInstance()->get('pd_stream_date'), 'user', 'user')) {
            $now = $start_time->getTimestamp();
        }

        $now = $dh->toDateTime($now, 'user', 'user')->modify('midnight');

        $occurrences = array();
        $after_items = $this->getItems($now->getTimestamp(), 'asc', 25);
        foreach ($after_items as $item) {
            $occurrences[] = $this->occurrenceToArray($item);
        }

        $before_items = $this->getItems($now->getTimestamp(), 'desc', 25);
        foreach ($before_items as $item) {
            $occurrences[] = $this->occurrenceToArray($item);
        }

        if ($start_time = strtotime(\Request::getInstance()->get('pd_stream' . $this->bID . '_date'))) {
            $this->set('date', date('m/d/Y', $start_time));
        }

        $date = new \DateTime('');
        if ($start_time) {
            $date->setTimestamp($start_time);
        }
        $date->modify('-1 year');
        $date->modify('january 1st');

        $start_epoch = $date->getTimestamp();
        $date->modify('+3 years');

        $end_epoch = $date->getTimestamp();

        $occupied_days = $this->occupiedDaysBetween($start_epoch, $end_epoch);
        $this->set('occupied', $this->occupiedDaysBetween($start_epoch, $end_epoch));
        $this->set('filterTopics', $filterTopics);
        $this->set('expanded', $expanded);
        $this->set('items', $occurrences);
    }

    protected function occurrenceToArray(EventOccurrence $occurrence)
    {
        $event = $occurrence->getEvent();

        /** @var EventKey $location_key */
        $attributes = EventKey::getAttributes($event->getID());

        $location = 'No Location';
        $extra = "";
        $image = "";

        if ($location_value = $attributes->getAttribute('event_location')) {
            $location = (string) $location_value;
        }

        if ($extra_value = $attributes->getAttribute('event_extra_info')) {
            $extra = (string) $extra_value;
        }

        /** @var \File $file_value */
        if ($file_value = $attributes->getAttribute('event_image')) {
            $image = (string) \File::getRelativePathFromID($file_value->getFileID());
        }

        $dh = \Core::make('helper/date');

        $start_time = $dh->toDateTime($occurrence->getStart(), 'user', 'user');
        list($year, $month, $day, $hour, $minute, $meridiem) = explode(' ', $start_time->format('Y F d g i A'));

        // Don't group by "midnight datetime"
        $day_time = (int)$start_time->format('Ymd');
        $start_time->modify('midnight');

        $start = array(
            'time'     => $start_time->getTimestamp(),
            // 'day_time' => $start_time->modify('midnight')->getTimestamp(),
            'day_time' => $day_time,
            'year'     => $year,
            'month'    => $month,
            'day'      => $day,
            'hour'     => $hour,
            'minute'   => $minute,
            'meridiem' => $meridiem);

        if ($occurrence->getEnd()) {
            $end_time = $dh->toDateTime($occurrence->getEnd(), 'user', 'user');
            list($year, $month, $day, $hour, $minute, $meridiem) = explode(' ', $end_time->format('Y F d g i A'));

            // Don't group by "midnight datetime"
            $day_time = (int)$end_time->format('Ymd');
            $end_time->modify('midnight');

            $end = array(
                'time'     => $end_time->getTimestamp(),
                // 'day_time' => $end_time->modify('midnight')->getTimestamp(),
                'day_time' => $day_time,
                'year'     => $year,
                'month'    => $month,
                'day'      => $day,
                'hour'     => $hour,
                'minute'   => $minute,
                'meridiem' => $meridiem);
        } else {
            $end = $start;
        }

        $keys = EventKey::getList(array('atHandle' => 'topics'));
        foreach ($keys as $key) {
            if ($key->getAttributeTypeHandle() == 'topics') {
                break;
            }
        }
        $topic = "";

        if ($key->getAttributeTypeHandle() == 'topics') {
            $topic = $event->getAttribute($key, 'displaySanitized');
        }


        return array(
            'name'        => $event->getName(),
            'description' => $event->getDescription(),
            'start'       => $start,
            'end'         => $end,
            'extra'       => $extra,
            'image'       => $image,
            'topic'       => $topic,
            'location'    => $location,
            'id'          => $occurrence->getID()
        );
    }

    /**
     * @param int    $start     The time int for to start
     * @param string $direction The direction string, usually DESC and ASC
     * @param int    $number    The amount to get.
     * @return EventOccurrence[]
     */
    protected function getItems($start, $direction, $number)
    {
        $list = $this->buildList();

        if (strtolower($direction) == 'asc') {
            $list->filterByStartTimeAfter($start);
        } else {
            $query = $list->getQueryObject();
            $query->andWhere('eo.startTime <= :startTimeBefore');
            $query->setParameter('startTimeBefore', $start);
        }

        if ($this->filterByTopic) {
            $ak = EventKey::getByID(24); // 24 is the key for Topics
            $list->filterByAttribute($ak->getAttributeKeyHandle(), $this->filterByTopic);
        }

        $list->sortBy('startTime', $direction);
        $list->setItemsPerPage($number);
        $list->getQueryObject()->setMaxResults($number);

        $occurrences = array();
        foreach ($list->executeGetResults() as $row) {
            $occurrences[] = $list->getResult($row);
        }

        return $occurrences;
    }

    public function edit()
    {
        $this->requireAsset('core/topics');
        $calendars = Calendar::getList();
        $calendarSelect = array();
        foreach ($calendars as $calendar) {
            $calendarSelect[$calendar->getID()] = $calendar->getName();
        }
        $this->set('calendars', $calendarSelect);
        $this->set('pageSelector', Core::make("helper/form/page_selector"));
        $this->loadKeys();
    }

    public function save($args)
    {
        $args['caID'] = intval($args['caID']);
        if (!$args['filterByTopicAttributeKeyID']) {
            $args['filterByTopicID'] = 0;
        }
        parent::save($args);
    }

    protected function loadKeys()
    {
        $keys = EventKey::getList(array('atHandle' => 'topics'));
        $this->set(
            'attributeKeys',
            array_filter(
                $keys,
                function ($ak) {
                    return $ak->getAttributeTypeHandle() == 'topics';
                }));
    }

}
