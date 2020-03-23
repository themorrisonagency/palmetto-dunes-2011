<?php

namespace Concrete\Package\PalmettoDunesSpecialOffers\Controller\SinglePage\Dashboard;
use \Concrete\Core\Page\Controller\DashboardPageController;

class SpecialOffers extends DashboardPageController
{

	public function view() {
		$this->redirect('/dashboard/special_offers/schedule');
		exit;
	}

}
