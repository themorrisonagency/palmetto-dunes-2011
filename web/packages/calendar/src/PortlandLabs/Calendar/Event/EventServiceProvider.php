<?php
namespace Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Event;

use Concrete\Core\Foundation\Service\Provider;

class EventServiceProvider extends Provider
{

    public function register()
    {
        \Core::bind('calendar/event/occurrence/factory', '\\PortlandLabs\\Calendar\\Event\\EventOccurrenceFactory');
    }

}