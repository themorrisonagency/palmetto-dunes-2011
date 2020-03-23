<?php
namespace Concrete\Package\Calendar\Src\Event;

use Concrete\Core\Foundation\Service\Provider;

class EventServiceProvider extends Provider
{

    public function register()
    {
        \Core::bind('calendar/event/occurrence/factory', '\\Concrete\\Package\\Calendar\\Src\\Event\\EventOccurrenceFactory');
    }

}
