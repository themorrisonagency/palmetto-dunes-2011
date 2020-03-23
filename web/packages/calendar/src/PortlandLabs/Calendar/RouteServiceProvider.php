<?php
namespace Concrete\Package\Calendar\Src\PortlandLabs\Calendar;

use Concrete\Core\Foundation\Service\Provider;
use PortlandLabs\Calendar\Event\EventServiceProvider;

class RouteServiceProvider extends Provider
{

    /**
     * Registers the services provided by this provider.
     *
     * @return void
     */
    public function register()
    {
        \Route::register('/ccm/calendar/dialogs/event/edit/{occurrence_id}',
            '\Concrete\Package\Calendar\Controller\Dialog\Event::edit');
        \Route::register('/ccm/calendar/dialogs/event/edit/{occurrence_id}/submit',
            '\Concrete\Package\Calendar\Controller\Dialog\Event::save');
        \Route::register('/ccm/calendar/dialogs/event/edit/{occurrence_id}/delete',
            '\Concrete\Package\Calendar\Controller\Dialog\Event::delete');
        \Route::register('/ccm/calendar/dialogs/event/edit/{occurrence_id}/delete_local',
            '\Concrete\Package\Calendar\Controller\Dialog\Event::delete_local');
        \Route::register('/ccm/calendar/dialogs/event/edit/{occurrence_id}/cancel',
            '\Concrete\Package\Calendar\Controller\Dialog\Event::cancel');
        \Route::register('/ccm/calendar/dialogs/event/add/{caID}',
            '\Concrete\Package\Calendar\Controller\Dialog\Event::add');
        \Route::register('/ccm/calendar/dialogs/event/add/{caID}/submit',
            '\Concrete\Package\Calendar\Controller\Dialog\Event::submit');
    }
}