<?php
namespace Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Event;

use Concrete\Package\Calendar\Src\Attribute\Key\EventKey;
use Concrete\Package\Calendar\Src\Attribute\Value\EventValue;
use Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Calendar;
use Concrete\Core\Foundation\Repetition\RepetitionInterface;
use Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Event\EventRepetition;
/**
 * Generic Event class
 *
 * @package Concrete\Core\Calendar
 */
class Event implements EventInterface
{

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $eventIntro;

    /** @var int */
    protected $eventImage;

    /** @var string */
    protected $eventImageAlt;

    /** @var string */
    protected $description;

    /** @var int */
    protected $eventAddLink;

    /** @var string */
    protected $eventLinkText;

    /** @var string */
    protected $eventLinkUrl;

    /** @var int */
    protected $eventDateFormat;

    /** @var RepetitionInterface */
    protected $repetition;

    /** @var \PortlandLabs\Calendar\Calendar */
    protected $calendar;

    /**
     * @param string              $name
     * @param string              $description
     * @param RepetitionInterface $repetition
     */
    function __construct($name, $eventIntro, $description, $eventImage, $eventImageAlt, $eventAddLink, $eventLinkText, $eventLinkUrl, $eventDateFormat,  $repetition)
    {
        $this->name = $name;
        $this->eventIntro = $eventIntro;
        $this->description = $description;
        $this->eventImage = $eventImage;
        $this->eventImageAlt = $eventImageAlt;
        $this->eventAddLink = $eventAddLink;
        $this->eventLinkText = $eventLinkText;
        $this->eventLinkUrl = $eventLinkUrl;
        $this->eventDateFormat = $eventDateFormat;
        $this->repetition = $repetition;
    }

    /**
     * @param $id
     * @return Event|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function getByID($id)
    {
        $id = intval($id, 10);

        $connection = \Database::connection();
        $query = $connection->query('SELECT * FROM CalendarEvents WHERE eventID=' . $id);
        foreach ($query as $result) {
            if (intval(array_get($result, 'eventID')) === $id) {
                $repetition = EventRepetition::getByID(array_get($result, 'repetitionID', null));
                $event = new Event(
                    array_get($result, 'name'),
                    array_get($result, 'eventIntro'),
                    array_get($result, 'description'),
                    array_get($result, 'eventImage'),
                    array_get($result, 'eventImageAlt'),
                    array_get($result, 'eventAddLink'),
                    array_get($result, 'eventLinkText'),
                    array_get($result, 'eventLinkUrl'),
                    array_get($result, 'eventDateFormat'),
                    $repetition);
                $event->id = $id;
                $calendar = Calendar::getByID($result['caID']);
                if (is_object($calendar)) {
                    $event->setCalendar($calendar);
                }
                return $event;
            }
        }

        return null;
    }

    /**
     * return |Concrete\Core\Calendar\EventOccurrenceList
     */
    public function getOccurrenceList()
    {
        $ev = new EventOccurrenceList();
        $ev->filterByEvent($this);
        return $ev;
    }

    /**
     * return \Concrete\Core\Calendar\EventOccurrence[]
     */
    public function getOccurrences()
    {
        $list = $this->getOccurrenceList();
        return $list->getResults();
    }

    /**
     * @return bool
     */
    public function save()
    {
        $connection = \Database::connection();
        if ($this->id) {
            if ($connection->update(
                'CalendarEvents',
                array(
                    'name'         => $this->getName(),
                    'eventIntro'  => $this->getEventIntro(),
                    'description'  => $this->getDescription(),
                    'eventImage'  => $this->getEventImage(),
                    'eventImageAlt' => $this->getEventImageAlt(),
                    'eventAddLink'  => $this->getEventAddLink(),
                    'eventLinkText'  => $this->getEventLinkText(),
                    'eventLinkUrl'  => $this->getEventLinkUrl(),
                    'eventDateFormat'  => $this->getEventDateFormat(),
                    'caID'         => $this->getCalendarID(),
                    'repetitionID' => $this->getRepetition()->getID()
                ),
                array(
                    'eventID' => $this->getID()
                ))
            ) {
                return true;
            }
        } else {
            if ($connection->insert(
                'CalendarEvents',
                array(
                    'name'         => $this->getName(),
                    'eventIntro'  => $this->getEventIntro(),
                    'description'  => $this->getDescription(),
                    'eventImage'  => $this->getEventImage(),
                    'eventImageAlt' => $this->getEventImageAlt(),
                    'eventAddLink'  => $this->getEventAddLink(),
                    'eventLinkText'  => $this->getEventLinkText(),
                    'eventLinkUrl'  => $this->getEventLinkUrl(),
                    'eventDateFormat'  => $this->getEventDateFormat(),
                    'caID'         => $this->getCalendarID(),
                    'repetitionID' => $this->getRepetition()->getID()
                ))
            ) {
                $this->id = intval($connection->lastInsertId(), 10);
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $start_time The earliest possible time for an event to occur
     * @param int $end_time The latest possible time for an event to occur
     * @return EventOccurrence[]
     */
    public function generateOccurrences($start_time, $end_time)
    {

        /** @var \Concrete\Core\Localization\Service\Date $dh */
        $dh = \Core::make('date');

        // Convert times
        $start_time = $dh->toDateTime($start_time)->getTimestamp();
        $end_time = $dh->toDateTime($end_time)->getTimestamp();

        /** @var EventOccurrenceFactory $factory */
        $factory = \Core::make('calendar/event/occurrence/factory');
        $occurrences = $this->getRepetition()->activeRangesBetween($start_time, $end_time);

        $initial_occurrence_time = $dh->toDateTime($this->repetition->getStartDate())->getTimestamp();
        if ($this->repetition->getEndDate()) {
            $initial_occurrence_time_end = $dh->toDateTime($this->repetition->getEndDate())->getTimestamp();
        } else {
            $initial_occurrence_time_end = $initial_occurrence_time;
        }
        $initial_occurrence = $factory->createEventOccurrence(
            $this,
            $initial_occurrence_time,
            $initial_occurrence_time_end
        );

        if ($initial_occurrence_time >= $start_time && $initial_occurrence_time <= $end_time) {
            $initial_occurrence->save();
        }

        $all_occurrences = array();
        foreach ($occurrences as $occurrence) {
            if ($occurrence[0] === $initial_occurrence->getStart()) {
                continue;
            }
            $all_occurrences[] = $factory->createEventOccurrence($this, $occurrence[0], $occurrence[1])->save();
        }
        return $all_occurrences;
    }

    /**
     * Reindex the attributes on this Event.
     * @return void
     */
    public function reindex()
    {
        $attribs = EventKey::getAttributes(
            $this->getID(),
            'getSearchIndexValue'
        );
        $db = \Database::connection();

        $db->Execute('delete from CalendarEventSearchIndexAttributes where eventID = ?', array($this->getID()));
        $searchableAttributes = array('eventID' => $this->getID());

        $key = new EventKey();
        $key->reindex('CalendarEventSearchIndexAttributes', $searchableAttributes, $attribs);
    }

    public function delete()
    {
        if ($this->getID() > 0) {
            $db = \Database::connection();
            if ($db->delete('CalendarEvents', array('eventID' => intval($this->getID())))) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \PortlandLabs\Calendar\Calendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param \PortlandLabs\Calendar\Calendar $calendar
     */
    public function setCalendar(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }

    public function getCalendarID()
    {
        if (isset($this->calendar)) {
            return $this->calendar->getID();
        }
        return 0;
    }

    public function getEventIntro()
    {
        return $this->eventIntro;
    }

    public function setEventIntro($eventIntro)
    {
        $this->eventIntro = $eventIntro;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getEventImage()
    {
        return $this->eventImage;
    }

    public function setEventImage($eventImage)
    {
        $this->eventImage = $eventImage;
    }

    public function getEventImageAlt()
    {
        return $this->eventImageAlt;
    }

    public function setEventImageAlt($eventImageAlt)
    {
        $this->eventImageAlt = $eventImageAlt;
    }

    public function getEventAddLink()
    {
        return $this->eventAddLink;
    }

    public function setEventAddLink($eventAddLink)
    {
        $this->eventAddLink = $eventAddLink;
    }

    public function getEventLinkText()
    {
        return $this->eventLinkText;
    }

    public function setEventLinkText($eventLinkText)
    {
        $this->eventLinkText = $eventLinkText;
    }

    public function getEventLinkUrl()
    {
        return $this->eventLinkUrl;
    }

    public function setEventLinkUrl($eventLinkUrl)
    {
        $this->eventLinkUrl = $eventLinkUrl;
    }

    public function getEventDateFormat()
    {
        return $this->eventDateFormat;
    }

    public function setEventDateFormat($eventDateFormat)
    {
        $this->eventDateFormat = $eventDateFormat;
    }

    /**
     * @return RepetitionInterface
     */
    public function getRepetition()
    {
        return $this->repetition;
    }

    public function setRepetition(RepetitionInterface $repetition)
    {
        $this->repetition = $repetition;
    }

    /**
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Helper method for retrieving attribute values against this event object
     *
     * @param EventKey $key
     * @param bool     $create_on_miss
     * @return EventValue|null
     */
    public function getAttributeValueObject(EventKey $key, $create_on_miss = false)
    {
        return EventValue::getAttributeValueObject($this, $key, !!$create_on_miss);
    }

    /**
     * Gets the value of the attribute for the Event
     */
    public function getAttribute($ak, $displayMode = false)
    {
        if (!is_object($ak)) {
            $ak = EventKey::getByHandle($ak);
        }
        if (is_object($ak)) {
            $av = $this->getAttributeValueObject($ak);
            if (is_object($av)) {
                if (func_num_args() > 2) {
                    $args = func_get_args();
                    array_shift($args);
                    return call_user_func_array(array($av, 'getValue'), $args);
                } else {
                    return $av->getValue($displayMode);
                }
            }
        }
    }

    /**
     * @return \stdClass
     */
    public function getJSONObject()
    {
        $o = new \stdClass;
        $o->id = $this->getID();
        $o->name = $this->getName();
        $o->eventIntro = $this->getEventIntro();
        $o->description = $this->getDescription();
        $o->eventImage = $this->getEventImage();
        $o->eventImageAlt = $this->getEventImageAlt();
        $o->eventAddLink = $this->getEventAddLink();
        $o->eventLinkText = $this->getEventLinkText();
        $o->eventLinkUrl = $this->getEventLinkUrl();
        $o->eventDateFormat = $this->getEventDateFormat();
        return $o;
    }
}