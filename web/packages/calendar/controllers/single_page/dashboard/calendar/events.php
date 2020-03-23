<?php
namespace Concrete\Package\Calendar\Controller\SinglePage\Dashboard\Calendar;

use Concrete\Core\Attribute\Key\CollectionKey;
use Concrete\Core\Tree\Node\Node;
use Concrete\Core\Tree\Node\Type\Topic;
use Concrete\Core\Tree\Node\Type\TopicCategory;
use Concrete\Package\Calendar\Src\Attribute\Key\EventKey;
use Concrete\Package\Calendar\Src\Calendar;
use \Concrete\Core\Page\Controller\DashboardPageController;
use URL;

class Events extends DashboardPageController
{

    public function view($caID = null, $year = null, $month = null, $msg = null)
    {
        $calendars = Calendar::getList();
        if (count($calendars) == 0) {
            $this->redirect('/dashboard/calendar/add');
        }
        $defaultCalendar = $calendars[0];
        if ($caID) {
            $calendar = Calendar::getByID(intval($caID));
        }
        if (!$calendar) {
            $calendar = $defaultCalendar;
        }

        $this->set('calendars', $calendars);
        $this->set('calendar', $calendar);

        if (!$year) {
            $year = date('Y');
        }
        if (!$month) {
            $month = date('m');
        }

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

        $session = \Core::make('session');
        $topic_id = $this->request->get('topic_id', $session->get('dashboard_calendar_events_topic_list', null));

        $nextLink = URL::to('/dashboard/calendar/events/view', $calendar->getID(), $nextLinkYear, $nextLinkMonth);
        $previousLink = URL::to('/dashboard/calendar/events/view', $calendar->getID(), $previousLinkYear, $previousLinkMonth);
        $todayLink = URL::to('/dashboard/calendar/events/view', $calendar->getID());


        if ($topic_id) {
            $topic_id = intval($topic_id, 10);
            $topic = Node::getByID($topic_id);

            if ($topic instanceof Topic) {
                $this->set('topic', $topic);


                $query = "?topic_id={$topic_id}";
                $nextLink .= $query;
                $previousLink .= $query;
                $todayLink .= $query;

                $session->set('dashboard_calendar_events_topic_list', $topic_id);
            }
        } elseif (is_numeric($topic_id) && $topic_id == '0') {
            $session->remove('dashboard_calendar_events_topic_list');
        }


        $this->set('monthText', date('F', $monthYearTimestamp));
        $this->set('month', $month);
        $this->set('year', $year);
        $this->set('daysInMonth', date('t',$monthYearTimestamp));
        $this->set('firstDayInMonthNum', $firstDayInMonthNum);
        $this->set('nextLink', $nextLink);
        $this->set('previousLink', $previousLink);
        $this->set('todayLink', $todayLink);

        switch($msg) {
            case 'event_added':
                $this->set('success', t('Event added successfully.'));
                break;
            case 'event_deleted':
                $this->set('success', t('Event deleted successfully.'));
                break;
            case 'event_occurrence_deleted':
                $this->set('success', t('Event occurrence deleted successfully.'));
                break;
            case 'event_delete_failed':
                $this->error->add(t('Unable to delete event.'));
                break;
            case 'occurrence_cancelled':
                $this->set('success', t('Event occurrence successfully cancelled.'));
                break;
        }


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
            $this->set('topics', $filterTopics);
        }
    }

    public function calendar_deleted()
    {
        $this->set('success', t('Calendar deleted successfully.'));
        $this->view();
    }

    public function delete_calendar()
    {
        $caID = $this->request->request->get('caID');
        if (\Core::make("helper/validation/numbers")->integer($caID)) {
            if ($caID > 0) {
                $calendar = Calendar::getByID($caID);
            }
        }

        if (!is_object($calendar)) {
            $this->error->add(t('Invalid calendar.'));
        }
        if (!$this->token->validate('delete_calendar')) {
            $this->error->add($this->token->getErrorMessage());
        }

        if (!$this->error->has()) {
            $calendar->delete();
            $this->redirect('/dashboard/calendar/events', 'calendar_deleted');
        }
    }

}
