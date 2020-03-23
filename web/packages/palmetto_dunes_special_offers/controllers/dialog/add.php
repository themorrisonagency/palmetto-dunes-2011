<?php
namespace Concrete\Package\PalmettoDunesSpecialOffers\Controller\Dialog;

use Concrete\Core\Application\EditResponse;
use Concrete\Core\View\DialogView;

class Add extends \Concrete\Controller\Dialog\Page\Add\Compose
{

    protected $viewPath = '/dialogs/add';

    public function view($ptID, $cParentID)
    {
        list($e, $pagetype, $parent) = $this->checkPermissions($ptID, $cParentID);
        if (!$e->has()) {
            $this->view = new DialogView($this->viewPath);
            $this->view->setPackageHandle('palmetto_dunes_special_offers');
            $this->set('parent', $parent);
            $this->set('pagetype', $pagetype);
        } else {
            $pr = new EditResponse();
            $pr->setError($e);
            $pr->outputJSON();
        }

        if (!$this->view) {
            throw new \Exception(t('Access Denied.'));
        }
    }

}
