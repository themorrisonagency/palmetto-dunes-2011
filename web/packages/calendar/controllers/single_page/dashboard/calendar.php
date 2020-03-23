<?

namespace Concrete\Package\Calendar\Controller\SinglePage\Dashboard;
use \Concrete\Core\Page\Controller\DashboardPageController;

class Calendar extends DashboardPageController
{

	public function view() {
		$this->redirect('/dashboard/calendar/events');
	}

}