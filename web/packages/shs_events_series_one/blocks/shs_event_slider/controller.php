<?php
namespace Concrete\Package\ShsEventsSeriesOne\Block\ShsEventSlider;

use Concrete\Package\Calendar\Src\Attribute\Key\EventKey;
use Concrete\Core\Block\BlockController;
use Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Calendar;
use Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Event\EventOccurrence;
use Concrete\Core\Tree\Type\Topic;
use Core;
use Less_Parser;
use Less_Tree_Rule;
use Page;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{

    public $helpers = array('form');

    protected $btInterfaceWidth = 500;
    protected $btInterfaceHeight = 340;
    protected $btTable = 'btShsEventSlider';

    public function getBlockTypeDescription()
    {
        return t("Displays a slider list of events from a calendar.");
    }

    public function getBlockTypeName()
    {
        return t("Event Slider");
    }

    public function add()
    {
        $this->edit();
        $this->set('buttonLinkText', t('View Full Calendar'));
        $this->set('eventListTitle', t('Featured Events'));
        $this->set('totalToRetrieve', 9);
        $this->set('totalPerPage', 3);
    }

    protected function getCalendar()
    {
        return Calendar::getByID($this->caID);
    }

    public function view()
    {
        $list = new EventOccurrenceList();
        $calendar = $this->getCalendar();
        if (is_object($calendar)) {
            $date = Core::make('date')->date('Y-m-d');
            $time = Core::make('date')->toDateTime($date . ' 00:00:00')->getTimestamp();
            $list->filterByStartTimeAfter($time);
            $list->filterByCalendar($this->getCalendar());
            if ($this->filterByFeatured) {
                $list->filterByAttribute('is_featured', true);
            }
            if ($this->filterByTopicAttributeKeyID) {
                $ak = EventKey::getByID($this->filterByTopicAttributeKeyID);
                if (is_object($ak)) {
                    $list->filterByAttribute($ak->getAttributeKeyHandle(), $this->filterByTopicID);
                }
            }
            $pagination = $list->getPagination();
            $pagination->setMaxPerPage($this->totalToRetrieve);
            $results = $pagination->getCurrentPageResults();
            $this->set('calendar', $calendar);
            $this->set('events', $results);
            if ($this->internalLinkCID) {
                $calendarPage = \Page::getByID($this->internalLinkCID);
                if (is_object($calendarPage) && !$calendarPage->isError()) {
                    $this->set('calendarPage', $calendarPage);
                }
            }
            $this->loadKeys();
        }
    }

    public function edit()
    {
        $this->requireAsset('core/topics');
        $calendars = Calendar::getList();
        $calendarSelect = array();
        foreach($calendars as $calendar) {
            $calendarSelect[$calendar->getID()] = $calendar->getName();
        }
        $this->set('calendars', $calendarSelect);
        $this->set('featuredAttribute', EventKey::getByHandle('is_featured'));
        $this->set('pageSelector', Core::make("helper/form/page_selector"));
        $this->loadKeys();
    }

    protected function loadKeys()
    {
        $keys = EventKey::getList(array('atHandle' => 'topics'));
        $this->set('attributeKeys', array_filter($keys, function($ak) {
            return $ak->getAttributeTypeHandle() == 'topics';
        }));
    }

    public function save($args)
    {
        $args['caID'] = intval($args['caID']);
        $args['filterByFeatured'] = intval($args['filterByFeatured']);
        if (!$args['filterByTopicAttributeKeyID']) {
            $args['filterByTopicID'] = 0;
        }
        parent::save($args);
    }

}