<?php

namespace Concrete\Package\Calendar;

use Concrete\Core\Attribute\Key\Category;
use Concrete\Core\Attribute\Type;
use Concrete\Core\Package\Package;
use Core;
use Page;
use SinglePage;

class Controller extends Package
{

    protected $pkgHandle = 'calendar';
    protected $appVersionRequired = '5.7.3.2b1';
    protected $pkgVersion = '0.8';

    protected $singlePages = array(
        '/dashboard/calendar',
        '/dashboard/calendar/events',
        '/dashboard/calendar/add',
        '/dashboard/calendar/attributes'
    );

    protected $singlePagesToExclude = array(
        '/dashboard/calendar/add'
    );

    protected $singlePageTitles = array(
        '/dashboard/calendar' => 'Calendar & Events',
        '/dashboard/calendar/events' => 'View Calendar',
        '/dashboard/calendar/add' => 'Add Calendar'
    );

    public function getPackageDescription()
    {
        return t("Adds public calendar functionality to your Concrete5 website.");
    }

    public function getPackageName()
    {
        return t("Calendar");
    }

    protected function installSinglePages($pkg)
    {
        foreach($this->singlePages as $path) {
            if (Page::getByPath($path)->getCollectionID() <= 0) {
                SinglePage::add($path, $pkg);
            }

            $pp = Page::getByPath($path);
            if (in_array($path, $this->singlePagesToExclude)) {
                if (is_object($pp) && !$pp->isError()) {
                    $pp->setAttribute('exclude_nav', true);
                    $pp->setAttribute('exclude_search_index', true);
                }
            }

            if (isset($this->singlePageTitles[$path])) {
                $pp->update(array('cName'=> $this->singlePageTitles[$path]));
            }
        }
    }

    protected function installAttributes($pkg)
    {
        $category = Category::getByHandle('event');
        if (!is_object($category)) {
            $category = Category::add('event', true, $pkg);
        }
        foreach(array('text', 'textarea', 'number', 'boolean', 'rating', 'select', 'image_file', 'date_time', 'topics') as $atHandle) {
            $type = Type::getByHandle($atHandle);
            if (is_object($type)) {
                $category->associateAttributeKeyType($type);
            }
        }
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installSinglePages($pkg);
        $this->installAttributes($pkg);
    }

    public function on_start()
    {
        $provider = new \Concrete\Package\Calendar\Src\RouteServiceProvider(\Core::getFacadeRoot());
        $provider->register();

        $provider = new \Concrete\Package\Calendar\Src\Event\EventServiceProvider(\Core::getFacadeRoot());
        $provider->register();

    }


}