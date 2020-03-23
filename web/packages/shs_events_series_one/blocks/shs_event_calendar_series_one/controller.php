<?php
namespace Concrete\Package\ShsEventsSeriesOne\Block\ShsEventCalendarSeriesOne;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Tree\Node\Node;
use Concrete\Core\Tree\Node\Type\TopicCategory;
use Concrete\Package\Calendar\Src\Attribute\Key\EventKey;
use Concrete\Package\Calendar\Attribute\Value\EventValue;
use PortlandLabs\Calendar\Calendar;
use PortlandLabs\Calendar\Event\EventOccurrence;
use PortlandLabs\Calendar\Event\EventOccurrenceList;
use Core;
use Symfony\Component\HttpFoundation\JsonResponse;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{

    public $helpers = array('form');

    protected $btInterfaceWidth = 500;
    protected $btInterfaceHeight = 340;
    protected $btTable = 'btShsEventCalendarSeriesOne';

    protected $bID;
    protected $caID;
    protected $filterByTopicAttributeKeyID;
    protected $filterByTopicID;
    protected $eventStreamTitle;
    protected $filterByTopicIDSelectedInUI;
    protected $occurrence;
    protected $singleitem=false;
    
    protected $month;
    protected $year;

    protected $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    /**
     * Integer value to set the day of the week the calendar should start with (0 for sunday, 1 for monday, etc)
     */
    public $startDay = 0;
    public $startMonth = 1;
    var $dayNames = array("S", "M", "T", "W", "T", "F", "S");
    var $monthNames = array("January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December");

    public function getBlockTypeDescription()
    {
        return t("Displays a listing of events from a calendar.");
    }
    
    public function setsingleitem($on)
    {
    	$this->singleitem=$on;
    }

    public function getBlockTypeName()
    {
        return t("Event Calendar Series One");
    }
    
    public function setbid($bid) //this is almost definitely a hack
    {
    	if(!is_numeric($bid))
    		throw new Exception('Invalid block id');
    	$this->bID=$bid;
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
        $this->occurrence=$occurrence;
        $occurrences = array();
        $occurrences[] = $this->occurrenceToArray($occurrence);

        $this->createCaledar($occurrence->getStart());
        $this->set('items', $occurrences);
        $this->set('permalink', true);
        $this->set('calName', $this->getCalendar());
    }
    

    public function get_occurrences()
    {
        $request = \Request::getInstance();
        $result = array('error' => null, 'params' => null);

        $block = \Block::getByID($request->get('bID', 0));
        if ($block && !$block->isError()) {
            $controller = $block->getController();

            if ($controller instanceof \Concrete\Package\ShsEventsSeriesOne\Block\ShsEventCalendarSeriesOne\Controller) {
                $occurrence = EventOccurrence::getByID(intval($request->get('occurrence_id', 0), 10));
                $amount = intval($request->get('amount', 20), 10);
                $direction = strtolower($request->get('direction', 'asc')) == 'asc' ? 'asc' : 'desc';
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
    
    public function setcaid($value)
    {
    	if(is_numeric($value))
    		$this->caID=$value;
    }

    protected function occupiedDaysBetween($start, $end)
    {
        $db = \Database::connection();
        $res = $db->query('SELECT ceo.* FROM CalendarEventOccurrences ceo LEFT JOIN CalendarEvents ce ON ce.eventID = ceo.eventID WHERE ceo.startTime < ? && ceo.startTime > ? && ce.caID = ?;', array(
            $end,
            $start,
            $this->caID
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
                } elseif ($this->filterByTopicID) {
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
            if ($occurrence_id = \Request::getInstance()->get('shs_stream_ev')) {
                $occurrence = EventOccurrence::getByID(intval($occurrence_id, 10));
            }
        } else {
            $now = $occurrence->getStart();
            $expanded = $occurrence->getID();
        }

        // get limit - for pagination use
        $limit = null;
        if (\Request::getInstance()->get('shs_stream_limit')) {
            $limit = \Request::getInstance()->get('shs_stream_limit');
        }

        // Should we be viewing the full month of events or the single day's events
        $single_day = true;
        if (\Request::getInstance()->get('shs_stream_monthview')) {
            $single_day = false;
        }

        // Get the selected date
        $dh = \Core::make('helper/date');
        if ($start_time = $dh->toDateTime(\Request::getInstance()->get('shs_stream_date'))) {
            $start_time = $now = $start_time->getTimestamp();
            $this->set('date', date('m/d/Y', $now));
        } else {
            $single_day = false;
        }

        $selected_date = date('Y-m-d', time());
        if ($single_day && $start_time) {
            $selected_date = date('Y-m-d', $now);
        }

        $now = $dh->toDateTime($now)->modify('midnight');

        $occurrences = array();

        if(!$this->singleitem)
        {
			$items = $this->getItems($now->getTimestamp(), $single_day);
			foreach ($items as $item) {
				$occurrences[] = $this->occurrenceToArray($item);
			}
		}
		else
			$occurrences[]=$this->occurrenceToArray($occurrence);
        
        $date = new \DateTime('');
        if ($start_time) {
            $date->setTimestamp($start_time);
        }
        $date->modify('-1 year');
        $date->modify('january 1st');

        $start_epoch = $date->getTimestamp();
        $date->modify('+3 years');

        $end_epoch = $date->getTimestamp();
        
        $this->createCaledar($now, $start_time);
        $calendar=$this->getCalendar();
        $this->set('filterTopics', $filterTopics);
        $this->set('expanded', $expanded);
        $this->set('items', $occurrences);
        $this->set('calName', $calendar);
        $this->set('bID', $this->bID);
    }


    // Start Calendar specific methods for the view
    /**
     * Generate the needed items for the calendar
     * @param timestamp $now
     * @param timestamp $start_time
     * @return void 
     */    
    protected function createCaledar($now, $start_time=null)
    {
 
       // Should we be viewing the full month of events or the single day's events
        $single_day = true;
        if (\Request::getInstance()->get('shs_stream_monthview')) {
            $single_day = false;
        }

        // Get the selected date
        $dh = \Core::make('helper/date');
        if ($start_time = $dh->toDateTime(\Request::getInstance()->get('shs_stream_date'))) {
            $start_time = $now = $start_time->getTimestamp();
        } else {
            $single_day = false;
        }
        
        $selected_date = date('Y-m-d', time());
        if ($single_day && $start_time) {
            $selected_date = date('Y-m-d', $now);
        }
        $now = $dh->toDateTime($now)->modify('midnight');

        $this->month = date('m', $now->getTimestamp());
        $this->year = date('Y', $now->getTimestamp());

        $date = new \DateTime('');
        if ($start_time) {
            $date->setTimestamp($start_time);
        }
        $date->modify('-1 year');
        $date->modify('january 1st');

        $start_epoch = $date->getTimestamp();
        $date->modify('+3 years');

        $end_epoch = $date->getTimestamp();

        $date = new \DateTime('');
        if ($start_time) {
            $date->setTimestamp($start_time);
        }
        $date->modify('-1 month');
        $previous_month =  $date->format('m/01/Y');
        
        $date->modify('+2 month');
        $next_month =  $date->format('m/01/Y');
        $occupied_days = $this->occupiedDaysBetween($start_epoch, $end_epoch);

        $this->set('thisMonth', $this->month); 
        $this->set('thisYear', $this->year);
        $this->set('nextMonth',$next_month);
        $this->set('previousMonth',$previous_month);
        $this->set('monthDropDown', $this->occupiedDaysToDropdown($occupied_days));
        $this->set('dayNames', $this->getDayNames());
        $this->set('firstDayOffset', $this->getFirstDayOffset());
        $this->set('daysInMonth', $this->getDaysInMonth());
        $this->set('selectedDay', $selected_date);   
        $this->set('occupied', $occupied_days);     
    }
    /**
     * Creates an array to be used in the calendar dropdown
     * @param array $occupied An array returned from occupiedDaysBetween
     * 
     * @return array 
     */
    protected function occupiedDaysToDropdown($occupied)
    {
        $tmp = array();
        foreach ($occupied as $year => $months) {
            foreach ($months as $month => $days) {
                $tmp[$month.'/01/'.$year] = date('F Y', strtotime($year.'-'.$month.'-01'));
            }
        }
        return $tmp;
    }
    
    /**
     * Gets the number of days in a month taking into account leap year
     * @return int
     */
    private function getDaysInMonth() {
        if ($this->month < 1 || $this->month > 12) {
            return 0;
        }
        $d = $this->daysInMonth[$this->month - 1];

        if ($this->month == 2) {
            if ($this->year%4 == 0) {
                if ($this->year%100 == 0) {
                    if ($this->year%400 == 0) {
                        $d = 29;
                    }
                } else {
                    $d = 29;
                }
            }
        }
        return $d;
    }
    
    /**
     * Gets the day names for the table header in order of set startDay
     * @return array
     */
    private function getDayNames()
    {
        $tmp = array();
        foreach (range(0,6) as $i) {
            $tmp[] = $this->dayNames[($this->startDay + $i) % 7]; 
        }
        return $tmp;
    }
    
    /**
     * Figures out the numberof days to skip to put the 1st on the correct day of the week
     * @return int
     */
    private function getFirstDayOffset()
    {
        $date = getdate(mktime(12, 0, 0, $this->month, 1, $this->year));

        $first = $date["wday"];
        $d = $this->startDay + 1 - $first;
        while ($d > 1) {
            $d -= 7;
        }
        return $d;        
    }
    // End Calendar specific methods for the view

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

        $start_time = $dh->toDateTime($occurrence->getStart(), 'user');
        list($year, $month, $day, $hour, $minute, $meridiem) = explode(' ', $start_time->format('Y F d g i A'));

        // Don't group by "midnight datetime"
        $day_time = (int)$start_time->format('Ymd');
        $start_time->modify('midnight');

        $start = array(
            'time'     => $start_time->getTimestamp(),
            'day_time' => $day_time,
            'year'     => $year,
            'month'    => $month,
            'day'      => $day,
            'hour'     => $hour,
            'minute'   => $minute,
            'meridiem' => $meridiem);

        if ($occurrence->getEnd()) {
            $end_time = $dh->toDateTime($occurrence->getEnd(), 'user');
            list($year, $month, $day, $hour, $minute, $meridiem) = explode(' ', $end_time->format('Y F d g i A'));

            // Don't group by "midnight datetime"
            $day_time = (int)$end_time->format('Ymd');
            $end_time->modify('midnight');

            $end = array(
                'time'     => $end_time->getTimestamp(),
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

        $link = "";

        if ($link_value = $attributes->getAttribute('event_add_link')) {
            $link = (string) $link_value;
        }

        $link_text = "";

        if ($link_text_value = $attributes->getAttribute('event_link_text')) {
            $link_text = (string) $link_text_value;
        }

        $link_url = "";

        if ($link_url_value = $attributes->getAttribute('event_link_url')) {
            $link_url = (string) $link_url_value;
        }

        $date_format = "";

        if ($date_format_value = $attributes->getAttribute('event_date_format')) {
            $date_format = (string) $date_format_value;
        }

        return array(
            'name'        => $event->getName(),
            'eventIntro'  => $event->getEventIntro(),
            'description' => $event->getDescription(),
            'eventImage'  => $event->getEventImage(),
            'eventImageAlt' => $event->getEventImageAlt(),
            'eventAddLink'     => $event->getEventAddLink(),
            'eventLinkText'    => $event->getEventLinkText(),
            'eventLinkUrl'     => $event->getEventLinkUrl(),
            'eventDateFormat'  => $event->getEventDateFormat(),
            'start'       => $start,
            'end'         => $end,
            'date_format' => $date_format,
            'extra'       => $extra,
            'image'       => $image,
            'topic'       => $topic,
            'location'    => $location,
            'id'          => $occurrence->getID(),
            'link'        => $link,
            'link_text'   => $link_text,
            'link_url'    => $link_url
        );
    }

    /**
     * @param int    $start     The time int for to start
     * @param boolean $ingle_day True to return results for a single day or false to return the entire month that day falls in
     * @return EventOccurrence[]
     */
    protected function getItems($start, $single_day=true)
    {
        $list = $this->buildList();

        if ($single_day) {
            $list->filterByDate(date('Y-m-d',$start));
        } else {
            $list->filterByEndTimeAfter($start);
            $list->filterByStartTimeBefore(strtotime(date("Y-m-t", $start) . ' 23:59:59'));
        }

        $list->sortBy('startTime', 'asc');
        $showPagination = false;
        if ($this->num > 0) {
            $list->setItemsPerPage($this->num);
            $list->getQueryObject()->setMaxResults($this->num);
            $pagination = $list->getPagination();
            $pages = $pagination->getCurrentPageResults();
            if ($pagination->getTotalPages() > 1 && $this->paginate) {
                $options = array(
                    'prev_message'        => '&#10094; Previous',
                    'next_message'        => 'Next &#10095;',
                    'active_suffix'       => ''
                );
                $showPagination = true;
                $pagination = $pagination->renderDefaultView($options);
                $this->set('pagination', $pagination);
            }
        } else {
            $pages = $list->getResults();
        }
        
        $this->set('showPagination', $showPagination);
        $this->set('list', $list);
        return $pages;
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
