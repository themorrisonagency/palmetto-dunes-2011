<?php

namespace Application\Block\DateNavigation;
defined('C5_EXECUTE') or die("Access Denied.");
use Concrete\Core\Block\BlockController;
use Concrete\Core\Page\PageList;
use Concrete\Core\Page\Type\Type;
use Core;
use Loader;

class Controller extends BlockController
{

    public $helpers = array('form');

    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 450;
    protected $btExportPageColumns = array('cParentID', 'cTargetID');
    protected $btExportPageTypeColumns = array('ptID');
    protected $btTable = 'btDateNavigation';

    public function getBlockTypeDescription()
    {
        return t("Displays a list of months to filter a page list by.");
    }

    public function getBlockTypeName()
    {
        return t("Date Navigation");
    }

    public function add()
    {
        $this->edit();
        $this->set('maxResults', 3);
        $this->set('title', t('Archives'));
    }

    public function edit()
    {
        $types = Type::getList();
        $this->set('pagetypes', $types);
    }

    public function getDateLink($dateArray = null)
    {
        if ($this->cTargetID) {
            $c = \Page::getByID($this->cTargetID);
        } else {
            $c = \Page::getCurrentPage();
        }
        if ($dateArray) {
            return \URL::page($c, $dateArray['year'], $dateArray['month']);
        } else {
            return \URL::page($c);
        }
    }

    public function getDateLabel($dateArray)
    {
        $date = strtotime($dateArray['year'] . '-' . $dateArray['month'] . '-02'); //adjust for timezone discrepancies
        $srv = Core::make('helper/date');
        return $srv->date('F Y', $date);
    }

    public function getPassThruActionAndParameters($parameters)
    {
        if (Loader::helper("validation/numbers")->integer($parameters[0])) {
            // then we're going to treat this as a year.
            $method = 'action_filter_by_date';
            $parameters[0] = intval($parameters[0]);
            if (isset($parameters[1])) {
                $parameters[1] = intval($parameters[1]);
            }
        }
        return array($method, $parameters);
    }

    public function action_filter_by_date($year = false, $month = false)
    {
        $this->selectedYear = $year;
        $this->selectedMonth = $month;
        $this->view();
    }

    public function isSelectedDate($dateArray)
    {
        if (isset($this->selectedYear) && isset($this->selectedMonth)) {
            return $dateArray['year'] == $this->selectedYear && $dateArray['month'] == $this->selectedMonth;
        }

    }

    public function view()
    {
        $pl = new PageList();
        if ($this->ptID) {
            $pl->filterByPageTypeID($this->ptID);
        }
        if ($this->cParentID) {
            $pl->filterByParentID($this->cParentID);
        }
        $query = $pl->deliverQueryObject();
        $query->select('date_format(cv.cvDatePublic, "%Y") as navYear, date_format(cv.cvDatePublic, "%m") as navMonth');
        $query->groupBy('navYear, navMonth');
        $query->orderBy('cvDatePublic', 'desc');
        $r = $query->execute();
        $dates = array();
        while ($row = $r->fetch()) {
            $dates[] = array('year' => $row['navYear'], 'month' => $row['navMonth']);
        }
        $this->set('dates', $dates);
    }

    public function save($data)
    {
        if ($data['redirectToResults']) {
            $data['cTargetID'] = intval($data['cTargetID']);
        } else {
            $data['cTargetID'] = 0;
        }
        if ($data['filterByParent']) {
            $data['cParentID'] = intval($data['cParentID']);
        } else {
            $data['cParentID'] = 0;
        }
        $data['ptID'] = intval($data['ptID']);
        parent::save($data);
    }
}