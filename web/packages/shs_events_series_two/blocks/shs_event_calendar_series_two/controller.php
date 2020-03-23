<?php
namespace Concrete\Package\ShsEventsSeriesTwo\Block\ShsEventCalendarSeriesTwo;

use stdClass;
use Loader;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Tree\Node\Node;
use Concrete\Core\Tree\Node\Type\TopicCategory;
use Concrete\Package\Calendar\Src\Attribute\Key\EventKey;
use Concrete\Package\Calendar\Attribute\Value\EventValue;
use Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Calendar;
use Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Event\EventOccurrence;
use Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Event\EventOccurrenceList;
use Core;
use BlockType;
use File;
use Symfony\Component\HttpFoundation\JsonResponse;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{

    public $helpers = array('form');

    protected $btInterfaceWidth = 500;
    protected $btInterfaceHeight = 340;
    protected $btTable = 'btShsEventCalendarSeriesTwo';

    protected $bID;
    protected $caID;
    protected $filterByTopicAttributeKeyID;
    protected $filterByTopicID;
    protected $filterByTopic;
    protected $eventStreamTitle;
    protected $filterByTopicIDSelectedInUI;
    protected $occurrence;
    protected $monthsInfo;
    
    protected $month;
    protected $year;

    protected $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    
    protected $eventAttributes = null;

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
        return t("Event Calendar Series Two");
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

        if(!$occurrence || $occurrence->getEnd() < time() ) {
            $c = \Page::getCurrentPage();
            header('Location:' . \URL::page($c));
            //exit();
        }
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

            if ($controller instanceof \Concrete\Package\ShsEvents\Block\ShsEventCalendar\Controller) {
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
        $ak = EventKey::getByHandle('topics');

        $al = \AssetList::getInstance();
        $bt = BlockType::getByHandle('shs_event_calendar_series_two');
        $bPath = Core::make('helper/concrete/urls')->getBlockTypeAssetsURL($bt);

        $al->register('javascript', 'handlebars.min-v4.0.4', $bPath.'/calendar_js/handlebars.min-v4.0.4.js', array('local' => false));
        $al->register('javascript', 'imagesloaded.pkgd.min', $bPath.'/calendar_js/imagesloaded.pkgd.min.js', array('local' => false));
        $al->register('javascript', 'masonry.pkgd.min', $bPath.'/calendar_js/masonry.pkgd.min.js', array('local' => false));
        $al->register('javascript', 'js.cookie', $bPath.'/calendar_js/js.cookie.js', array('local' => false));
        $al->register('javascript', 'moment.min', $bPath.'/calendar_js/moment.min.js', array('local' => false));
        $al->register('javascript', 'bootstrap.min', $bPath.'/calendar_js/bootstrap.min.js', array('local' => false));

        $this->requireAsset('javascript', 'handlebars.min-v4.0.4');
        $this->requireAsset('javascript', 'imagesloaded.pkgd.min');
        $this->requireAsset('javascript', 'masonry.pkgd.min');
        $this->requireAsset('javascript', 'js.cookie');
        $this->requireAsset('javascript', 'moment.min');
        $this->requireAsset('javascript', 'bootstrap.min');

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
        {
            // Temp approach to pulling permalink $items:
            $_SERVER['REQUEST_URI_PATH'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $segments = explode('/', $_SERVER['REQUEST_URI_PATH']);
            $itemID = $segments[3];
            $occurrence = EventOccurrence::getByID(intval($itemID, 10));
            $occurrences[]=$this->occurrenceToArray($occurrence);
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
        
        $this->createCaledar($now, $start_time);
        $this->set('filterTopics', $filterTopics);
        $this->set('expanded', $expanded);
        $this->set('items', $occurrences);
        $this->set('calName', $this->getCalendar());

        $layoutType = '';

        $db = Loader::db();
        $v = array($this->bID);
        $q = 'SELECT * FROM btShsEventCalendarSeriesTwo WHERE bID = ?';
        $r = $db->query($q, $v);
        foreach($r as $row) {
           $layoutType = $row['layoutType'];
        }
        
        if($layoutType != '1') {
            $layoutType = 'grid';
        } else {
            $layoutType = 'calendar';
        }

    }

    protected function getmonths($shs_stream_date=false) {
        $return_object = new stdClass();
        $now = time();

        //removing legacy Concrete5 date class and moving to builtin DateTime
        //$dh = \Core::make('helper/date');
        //if ($start_time = $dh->toDateTime($shs_stream_date)) {
        if (strtotime($shs_stream_date)) {
            //$start_time = $now = $start_time->modify('+1 day')->getTimestamp();
            $start_time = $now = new \DateTime($shs_stream_date);
            $return_object->start_time = $start_time->getTimestamp();
        }

        //$now = $dh->toDateTime($now)->modify('midnight');

        $return_object->today = $now->getTimestamp();
        //$this->month = $return_object->month = (int)date('m', $now->getTimestamp());
        //$this->year = $return_object->year = (int)date('Y', $now->getTimestamp());
        $this->month = $return_object->month = (int)$now->format('m');
        $this->year = $return_object->year = (int)$now->format('Y');
        
        $return_object->dateToday = date('Y-m-d');

        $date = new \DateTime('');
        if ($start_time) {
            //$date->setTimestamp($start_time);
            $date = clone $start_time;
        }
        $date->modify('-0 year');
        $date->modify('january 1st');

        $return_object->start_epoch = $date->getTimestamp();
        $date->modify('+2 years');

        $return_object->end_epoch = $date->getTimestamp();

        $date = new \DateTime('');
        if ($start_time) {
            //$date->setTimestamp($start_time);
            //$return_object->start_time = $start_time;
            $date = clone $start_time;
        }

        $return_object->this_month =  $date->format('m/01/Y');
        $return_object->selectedDate = $date->format('Y-m-d');
        
        $date->modify('-1 month');
        $return_object->previous_month =  $date->format('m/01/Y');
        
        $date->modify('+2 month');
        $return_object->next_month =  $date->format('m/01/Y');
        $occupied = $this->occupiedDaysBetween(strtotime($return_object->previous_month), $return_object->end_epoch);     
        $return_object->occupied = $occupied;
        $return_object->monthDropDown = $this->occupiedDaysToDropdown($occupied, $return_object->previous_month);

        return $return_object;
    }

    // returns json for months info
    public function action_getmonths($shs_stream_date=false) {
        $this->monthsInfo = $this->getmonths($shs_stream_date);
        header('Content-Type: application/json');
        echo json_encode($this->monthsInfo);
        exit;
    }

    public function action_getevents($grid=false, $shs_stream_monthview=false, $shs_stream_date=false, $shs_stream_topic=false)
    {
        $return_object = new stdClass();
        $now = time();
        $filterTopics = array();
        $ak = EventKey::getByHandle('topics');
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
        if ($shs_stream_topic) {
            $this->set('filterByTopic', intval($shs_stream_topic));
            $this->filterByTopic = intval($shs_stream_topic);
        }
        if ($this->filterByTopicID) {
            $node = Node::getByID($this->filterByTopicID);
            if ($node instanceof TopicCategory) 
            {
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

        $expanded = 0;
        $occurrence = $this->occurrence;
        if (!$occurrence) {
            if ($occurrence_id = \Request::getInstance()->get('shs_stream_ev')) 
            {
                $occurrence = EventOccurrence::getByID(intval($occurrence_id, 10));
            }
        } else {
            $now = $occurrence->getStart();
            $expanded = $occurrence->getID();
        }        
        // Should we be viewing the full month of events or the single day's events
        $return_object->single_day = true;
        if ($shs_stream_monthview) {
            $return_object->single_day = false;
        }
        // Get the selected date
        //Removing legacy c5 date helper which is timezone agnostic, moving to native DateTime
        //Cameron McFee - per http://skynet.esiteportal.com/tasks.php?op=view&data[id]=518067
        //$dh = \Core::make('helper/date');
        //if ($start_time = $dh->toDateTime($shs_stream_date)) {
        if ($start_time = new \DateTime($shs_stream_date)) {
            $start_time = $now = $start_time->getTimestamp();
        } else {
            //$return_object->single_day = false;
        }
        //$selected_date = date('Y-m-d', time());

        $selected_day = (int)date('d', $now);
        $selected_date = date('Y-m-d', $now);
        $now = new \DateTime();

        if ($this->monthsInfo == null) {
            $this->monthsInfo = $this->getmonths($shs_stream_date);
        }

        // $return_object->bID = $this->bID;
        // $return_object->filterByTopicID = $this->filterByTopicID;
        $return_object->dateToday = $this->monthsInfo->dateToday;

        $return_object->month = $this->monthsInfo->month;
        $return_object->year = $this->monthsInfo->year;
        $return_object->this_month =  $this->monthsInfo->this_month;
        
        $return_object->previous_month =  $this->monthsInfo->previous_month;
        $return_object->next_month =  $this->monthsInfo->next_month;
        $return_object->monthDropDown = $this->monthsInfo->monthDropDown;

        if (date_default_timezone_get()) {
            $return_object->defaultTimeZone = date_default_timezone_get();
        }
        if (!$grid) {
            $return_object->occupied = $this->monthsInfo->occupied;
            $return_object->dayNames = $this->getDayNames();
            $return_object->firstDayOffset = $this->getFirstDayOffset();
            $return_object->daysInMonth = $this->getDaysInMonth();
            $return_object->selectedDay = $selected_day;
            if ($this->monthsInfo->selectedDate !== null) {
                $return_object->selectedDate = $this->monthsInfo->selectedDate;
            } else {
                $return_object->selectedDate = $selected_date;
            }
        }

        $item_start_date = $now->getTimestamp();
        if (!$grid && !$return_object->single_day) {
            $item_start_date = strtotime($return_object->this_month);
        }
        if($return_object->selectedDate == date('Y-m-d')) {
            $item_start_date = strtotime(date('Y-m-d'));
        }
        $items = $this->getItems($item_start_date, $return_object->single_day);

        foreach ($items as $item) 
            $occurrences[] = $this->occurrenceToArray($item);
        $this->createCaledar($now->getTimestamp(), $start_time);
        $return_object->filterTopics = $filterTopics;
        $return_object->expanded = $expanded;
        $return_object->items = $occurrences;
        $return_object->calName = $this->getCalendar();

        header('Content-Type: application/json');
        echo json_encode($return_object);
        exit;
        //return new \Symfony\Component\HttpFoundation\JsonResponse($return_object);   
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
        //$date->modify('-1 year'); //backend help
        $date->modify('-0 year');
        $date->modify('january 1st');

        $start_epoch = $date->getTimestamp();
        //$date->modify('+3 years'); //backend help
        $date->modify('+2 years');

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
    protected function occupiedDaysToDropdown($occupied, $start_month=false)
    {
        $tmp = array();
        foreach ($occupied as $year => $months) {
            foreach ($months as $month => $days) {
                if (!$start_month || (strtotime($year.'-'.$month.'-01') >= strtotime($start_month))) {
                    $tmp[$month.'/01/'.$year]['month'] = date('M', strtotime($year.'-'.$month.'-01'));
                    $tmp[$month.'/01/'.$year]['year'] = $year;
                }
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
        $attributes = $this->_getAttributes($event->getId());
        //$location = 'No Location';
        $location = '';
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
            if($file_value->getFileID() !== null) {
                $image = (string) \File::getRelativePathFromID($file_value->getFileID());
            }
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
            $topic = $event->getAttribute($key);
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

	// Applying the all day if the times are equal
	if (($start['hour'].':'.$start['minute'].' '.$start['meridiem']) == ($end['hour'].':'.$end['minute'].' '.$end['meridiem'])) {
		$total = "All Day";
	} else {
		$total = $start['hour'].':'.$start['minute'].' '.$start['meridiem'].' - '.$end['hour'].':'.$end['minute'].' '.$end['meridiem'];
	}
        return array(
            'name'        => $event->getName(),
            'eventIntro'  => $event->getEventIntro(),
            'description' => $event->getDescription(),
            'eventImageID'  => $event->getEventImage(),
            'eventImage'  => $image,//File::getRelativePathFromID($event->getEventImage()),
            'eventImageAlt' => $event->getEventImageAlt(),
            'eventAddLink'     => $event->getEventAddLink(),
            'eventLinkText'    => $event->getEventLinkText(),
            'eventLinkUrl'     => $event->getEventLinkUrl(),
            'eventDateFormat'  => $event->getEventDateFormat(),
            'start'       => $start,
            'end'         => $end,
            'total'	  => $total,
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
     * @param boolean $single_day True to return results for a single day or false to return the entire month that day falls in
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

        // here
        if ($this->filterByTopic) {
            $ak = EventKey::getByID($this->filterByTopicAttributeKeyID);
            if ($ak = EventKey::getByID($this->filterByTopicAttributeKeyID)) {
                $list->filterByAttribute($ak->getAttributeKeyHandle(), $this->filterByTopic);
            }
        }

        $showPagination = false;
        if ($this->num > 0) {
            $list->setItemsPerPage($this->num);
            $list->getQueryObject()->setMaxResults($this->num);
            $pagination = $list->getPagination();
            $pages = $pagination->getCurrentPageResults();
            if ($pagination->getTotalPages() > 1 && $this->paginate) {
                $showPagination = true;
                $pagination = $pagination->renderDefaultView();
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
    
    /** implementing limited in-memory caching for events to avoid repeated db calls for one event
     * @param Int event ID
     * @return AttributeList
     */
    protected function _getAttributes($eventId) {
        if (!is_array($this->eventAttributes)) {
            $this->eventAttributes = array();
        }
        if (!array_key_exists($eventId, $this->eventAttributes)) {
            $this->eventAttributes[$eventId] = EventKey::getAttributes($eventId);
        }
        return $this->eventAttributes[$eventId];
    }

}
