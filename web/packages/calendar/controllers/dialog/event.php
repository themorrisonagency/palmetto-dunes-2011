<?
namespace Concrete\Package\Calendar\Controller\Dialog;

use Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use Concrete\Package\Calendar\Src\Attribute\Key\EventKey;
use Concrete\Package\Calendar\Src\Calendar;
use Concrete\Package\Calendar\Src\Event\EditResponse;
use Concrete\Package\Calendar\Src\Event\Event as CalendarEvent;
use Concrete\Package\Calendar\Src\Event\EventOccurrence;
use Concrete\Package\Calendar\Src\Event\EventOccurrenceList;
use Concrete\Package\Calendar\Src\Event\EventRepetition;
use Concrete\Core\Form\Service\Widget\DateTime;
use Core;
use RedirectResponse;

class Event extends BackendInterfaceController
{

    protected $viewPath = '/dialogs/event/form';

    public function add($caID)
    {
        $this->requireAsset('redactor');
        $calendar = Calendar::getByID($caID);
        if (!is_object($calendar)) {
            throw new \Exception(t('Invalid calendar.'));
        }
    }

    public function edit($occurrence_id = 0)
    {
        $this->requireAsset('redactor');
        if ($this->canAccess(false)) {

            $occurrence = EventOccurrence::getByID($occurrence_id);
            if (!$occurrence) {
                throw new \Exception(t('Invalid occurrence.'));
            }

            $this->set('occurrence', $occurrence);
        } else {
            die('Access Denied.');
        }
    }

    protected function canAccess()
    {
        $c = \Page::getByPath('/dashboard/calendar/events');
        $cp = new \Permissions($c);
        return $cp->canViewPage();
    }

    public function cancel($occurrence_id)
    {
        if ($this->canAccess()) {

            $occurrence = EventOccurrence::getByID($occurrence_id);
            if (!$occurrence) {
                throw new \Exception(t('Invalid occurrence.'));
            }

            $occurrence->cancel();
            $occurrence->save();

            $year = date('Y', $occurrence->getStart());
            $month = date('m', $occurrence->getStart());
            $response = new \RedirectResponse(
                \URL::to(
                    '/dashboard/calendar/events/',
                    'view',
                    $occurrence->getEvent()->getCalendar()->getID(),
                    $year,
                    $month,
                    'occurrence_cancelled'
                ));
            $response->send();
        } else {
            die('Access denied');
        }
    }

    public function save($occurrence_id)
    {
        $e = \Core::make('error');
        $dh = \Core::make('date');

        if (!$this->canAccess()) {
            $e->add('Access denied.');
            $r = new EditResponse($e);
            $r->outputJSON();
            exit;
        }

        $repetition = null;
        $edit_type = $this->request->request->get('edit_type');
        if ($edit_type != 'local') {
            $repetition = EventRepetition::translateFromRequest($this->request, $edit_type == 'forward');
            if (!is_object($repetition)) {
                $e->add(t('You must specify a valid date for this event.'));
            }
        }

        $occurrence = EventOccurrence::getByID($occurrence_id);
        if (!$occurrence) {
            throw new \Exception(t('Invalid occurrence.'));
        }

        $datetime = \Core::make('helper/form/date_time');
        $starttime = strtotime($datetime->translate('pdStartDate'));
        $endtime = strtotime($datetime->translate('pdEndDate'));

        if ($endtime && ($starttime > $endtime || $starttime == 0)) {
            $e->add(t('You cannot have a start date after an end date.'));
        }

        $calendar = $occurrence->getEvent()->getCalendar();
        $r = new EditResponse($e);
        if (!$e->has()) {
            if (!$occurrence->getEvent()->getRepetition()->repeats()) {
                $repetition = EventRepetition::translateFromRequest($this->request);
                $repetition->save();

                /** @var \Concrete\Package\Calendar\Src\Event\Event $ev */
                $ev = $occurrence->getEvent();
                $ev->setName($this->request->request->get('name'));
                $ev->setDescription($this->request->request->get('description'));
                $ev->setEventIntro($this->request->request->get('eventIntro'));
		$ev->setRepetition($repetition);
                $ev->save();


                $db = \Database::connection();
                $db->query(
                    'DELETE FROM CalendarEventOccurrences WHERE eventID=?',
                    array($ev->getID()));

                $initial_occurrence_time = $dh->toDateTime($repetition->getStartDate())->getTimestamp();
                if ($repetition->getEndDate()) {
                    $initial_occurrence_time_end = $dh->toDateTime($repetition->getEndDate())->getTimestamp();
                } else {
                    $initial_occurrence_time_end = $initial_occurrence_time;
                }

                $occurrence = new EventOccurrence(
                    $ev,
                    $initial_occurrence_time,
                    $initial_occurrence_time_end);
                $occurrence->save();

                $attributes = EventKey::getList();
                foreach ($attributes as $ak) {
                    $ak->saveAttributeForm($ev);
                }

            } elseif ($edit_type == 'local') {
                /** @var DateTime $datetime */
                $repetition = new EventRepetition();

                $start = $datetime->translate('pdOccurrenceStartDate');
                $end = $datetime->translate('pdOccurrenceEndDate');

                if (!$start || !$end) {
                    $e->add('A valid start date must be provided.');
                } else {
                    $repetition->setStartDate($start);
                    $repetition->setEndDate($end);
                    $repetition->setRepeatPeriod($repetition::REPEAT_NONE);
                    $repetition->save();

                    $ev = new \Concrete\Package\Calendar\Src\Event\Event(
                        $this->request->request->get('name'),
                        $this->request->request->get('description'),
                        $repetition,
			$this->request->request->get('eventIntro')
		    );
                    $ev->setCalendar($occurrence->getEvent()->getCalendar());
                    $ev->save();

                    $now = Core::make('date')->toDateTime($repetition->getStartDate())->getTimestamp();
                    $ev->generateOccurrences($now, strtotime('+5 years', $now));

                    $occurrence->delete();
                }
            } elseif ($edit_type === 'forward') {
                $repetition->save();

                $ev = new \Concrete\Package\Calendar\Src\Event\Event(
                    $this->request->request->get('name'),
                    $this->request->request->get('description'),
                    $repetition,
		    $this->request->request->get('eventIntro')
		);

                $db = \Database::connection();
                $db->query(
                    'DELETE FROM CalendarEventOccurrences WHERE startTime>=? AND eventID=?',
                    array(
                        $occurrence->getStart(),
                        $occurrence->getEvent()->getID()));

                $ev->setCalendar($occurrence->getEvent()->getCalendar());
                $ev->save();

                $now = Core::make('date')->toDateTime($repetition->getStartDate())->getTimestamp();
                $ev->generateOccurrences($now, strtotime('+5 years', $now));

                $occurrence->delete();
            } else {
                $repetition->save();
                /** @var \Concrete\Package\Calendar\Src\Event\Event $ev */
                $ev = $occurrence->getEvent();
                $ev->setName($this->request->request->get('name'));
                $ev->setDescription($this->request->request->get('description'));
                $rep = $ev->getRepetition();
                $ev->setRepetition($repetition);
                $ev->setEventIntro($this->request->request->get('eventIntro'));
                $ev->save();
                $now = Core::make('date')->toDateTime($repetition->getStartDate())->getTimestamp() - 1;
                $db = \Database::connection();
                $db->query(
                    'DELETE FROM CalendarEventOccurrences WHERE startTime>=? AND eventID=?',
                    array(
                        $now,
                        $ev->getID()));

                $ev->generateOccurrences($now, strtotime('+5 years', $now));

                $attributes = EventKey::getList();
                foreach ($attributes as $ak) {
                    $ak->saveAttributeForm($ev);
                }
            }

            // Commenting this out until we can do ajax style calendar updating. In the meantime
            // we're just going to refresh to the date of the start of the event and call it good.
            //$occurrences = $ev->getOccurrences();
            //$r->setOccurrences($occurrences);
            //$r->setMessage(t('Event added successfully.'));
            $year = date('Y', strtotime($repetition->getStartDate()));
            $month = date('m', strtotime($repetition->getStartDate()));
            $r->setRedirectURL(
                \URL::to(
                    '/dashboard/calendar/events/',
                    'view',
                    $calendar->getID(),
                    $year,
                    $month,
                    'event_saved'
                ));
        }

        $r->outputJSON();
    }

    public function delete($occurrence_id)
    {
        if ($this->canAccess()) {
            $occurrence = EventOccurrence::getByID($occurrence_id);

            if ($occurrence) {
                /** @var \Concrete\Package\Calendar\Src\Event\Event $event */
                $event = $occurrence->getEvent();

                $occurrence_list = new EventOccurrenceList();
                $occurrence_list->filterByEvent($event);
                foreach ($occurrence_list->getResults() as $occurrence) {
                    $occurrence->delete();
                }

                $event->delete();

                $r = new RedirectResponse(
                    \URL::to(
                        '/dashboard/calendar/events/',
                        'view',
                        $event->getCalendar()->getID(),
                        date('Y', $occurrence->getStart()),
                        date('m', $occurrence->getStart()),
                        'event_deleted'
                    ));
                $r->send();
            } else {
                $r = new RedirectResponse(
                    \URL::to(
                        '/dashboard/calendar/events/',
                        'view',
                        null,
                        null,
                        null,
                        'event_delete_failed'
                    ));
                $r->send();
            }
        }

    }

    public function delete_local($occurrence_id)
    {
        if ($this->canAccess()) {
            $occurrence = EventOccurrence::getByID($occurrence_id);

            if ($occurrence) {
                $occurrence->delete();

                $r = new RedirectResponse(
                    \URL::to(
                        '/dashboard/calendar/events/',
                        'view',
                        $occurrence->getEvent()->getCalendar()->getID(),
                        date('Y', $occurrence->getStart()),
                        date('m', $occurrence->getStart()),
                        'event_occurrence_deleted'
                    ));
                $r->send();
            } else {
                $r = new RedirectResponse(
                    \URL::to(
                        '/dashboard/calendar/events/',
                        'view',
                        null,
                        null,
                        null,
                        'event_delete_failed'
                    ));
                $r->send();
            }
        }

    }

    public function submit($caID)
    {
        if ($this->canAccess()) {
            $repetition = EventRepetition::translateFromRequest($this->request);
            $datetime = \Core::make('helper/form/date_time');
            $e = \Core::make('error');
            if (!is_object($repetition)) {
                $e->add(t('You must specify a valid date for this event.'));
            }

            $calendar = Calendar::getByID($caID);
            if (!is_object($calendar)) {
                $e->add(t('Invalid calendar.'));
            }

            $starttime = strtotime($datetime->translate('pdStartDate'));
            $endtime = strtotime($datetime->translate('pdEndDate'));

            if ($endtime && ($starttime > $endtime || $starttime == 0)) {
                $e->add(t('You cannot have a start date after an end date.'));
            }

            $r = new EditResponse($e);

            if (!$e->has()) {
                $repetition->save();
                $ev = new CalendarEvent(
                    $this->request->request->get('name'),
                    $this->request->request->get('description'),
                    $repetition,
		    $this->request->request->get('eventIntro')
                );

                $repetition_start = Core::make('date')->toDateTime($repetition->getStartDate())->getTimestamp();

                $ev->setCalendar($calendar);
                $ev->save();

                $ev->generateOccurrences($repetition_start - 1, strtotime('+5 years', $repetition_start));

                $attributes = EventKey::getList();
                foreach ($attributes as $ak) {
                    $ak->saveAttributeForm($ev);
                }

                // Commenting this out until we can do ajax style calendar updating. In the meantime
                // we're just going to refresh to the date of the start of the event and call it good.
                //$occurrences = $ev->getOccurrences();
                //$r->setOccurrences($occurrences);
                //$r->setMessage(t('Event added successfully.'));
                $year = date('Y', strtotime($repetition->getStartDate()));
                $month = date('m', strtotime($repetition->getStartDate()));
                $r->setRedirectURL(
                    \URL::to(
                        '/dashboard/calendar/events/',
                        'view',
                        $calendar->getID(),
                        $year,
                        $month,
                        'event_added'
                    ));
            }

            $r->outputJSON();
        }
    }

}

