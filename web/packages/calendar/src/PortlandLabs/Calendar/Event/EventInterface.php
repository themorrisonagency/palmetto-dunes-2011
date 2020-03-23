<?php
namespace Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Event;

use Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Calendar;
use Concrete\Core\Foundation\Repetition\RepetitionInterface;

/**
 * A simplified interface for Calendar Events
 *
 * @package Concrete\Core\Calendar
 */
interface EventInterface
{

    /**
     * @return int
     */
    public function getCalendarID();

    /**
     * @return Calendar
     */
    public function getCalendar();

    /**
     * @param Calendar $calendar
     * @return void
     */
    public function setCalendar(Calendar $calendar);

    /**
     * The identifier, null for unsaved
     *
     * @return string|int|null
     */
    public function getID();

    /**
     * The repetition object
     *
     * @return RepetitionInterface
     */
    public function getRepetition();

    /**
     * Get the event name
     *
     * @return string
     */
    public function getName();

    /**
     * Set the event name
     *
     * @param string $name
     */
    public function setName($name);

    public function getEventIntro();
    public function setEventIntro($eventIntro);

    /**
     * Get the event description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set the event description
     *
     * @param string $description
     */
    public function setDescription($description);

    public function getEventImage();
    public function setEventImage($eventImage);

    public function getEventAddLink();
    public function setEventAddLink($eventAddLink);

    public function getEventLinkText();
    public function setEventLinkText($eventLinkText);

    public function getEventLinkUrl();
    public function setEventLinkUrl($eventLinkUrl);

    public function getEventDateFormat();
    public function setEventDateFormat($eventDateFormat);

    /**
     * Set the new repetition object
     *
     * @param RepetitionInterface $repetition
     */
    public function setRepetition(RepetitionInterface $repetition);

    /**
     * Save this event
     *
     * @return bool Success / failure
     */
    public function save();

}