<?php
namespace Concrete\Package\PalmettoDunesSpecialOffers\Src;

use Concrete\Core\Foundation\Service\Provider;

class RouteServiceProvider extends Provider
{

    /**
     * Registers the services provided by this provider.
     *
     * @return void
     */
    public function register()
    {
        // Add
        \Route::register('/ccm/special_offers/dialogs/add/{ptID}/{cParentID}',
                         '\Concrete\Package\PalmettoDunesSpecialOffers\Controller\Dialog\Add::view');
        \Route::register('/ccm/special_offers/dialogs/add/publish',
                         '\Concrete\Package\PalmettoDunesSpecialOffers\Controller\Dialog\Add::publish');

        // Edit
        \Route::register('/ccm/special_offers/dialogs/edit',
            '\Concrete\Package\PalmettoDunesSpecialOffers\Controller\Dialog\Edit::view');
        \Route::register('/ccm/special_offers/dialogs/edit/publish',
            '\Concrete\Package\PalmettoDunesSpecialOffers\Controller\Dialog\Edit::publish');
    }
}
