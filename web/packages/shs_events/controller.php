<?php

namespace Concrete\Package\ShsEvents;

use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Package\Package;
use DoctrineProxies\__CG__\Concrete\Core\Block\BlockType\BlockType;
use PortlandLabs\Calendar\Event\EventOccurrence;
use Route;
use Request;
use \Doctrine\Common\Annotations\AnnotationRegistry;
use Loader;
use SinglePage;
use Page;

class Controller extends Package
{

    protected $pkgHandle = 'shs_events';
    protected $appVersionRequired = '5.7.4';
    protected $pkgVersion = '0.3';

    /**
     * On-Start method (auto-called)
     */
    public function on_start()
    {
        AnnotationRegistry::registerLoader('class_exists');
    }

    public function getPackageDescription()
    {
        return t("Adds events functionality to your site.");
    }

    public function getPackageName()
    {
        return t("SHS Events");
    }

    public function install()
    {
        $pre_pkg = Package::getByHandle('calendar');
        if (!is_object($pre_pkg)){
            throw new \Exception(t('SHS Events requires the Calendar package to be installed and available.'));
            exit;
        }
        $pkg = parent::install();
        $eventpage=SinglePage::add('/event',$pkg);
        $eventpage->setAttribute("exclude_nav", 1);
        $eventpage->update(array('cDescription'=>'Single Event Display Page.'));
        //$cif = new ContentImporter();
        //$cif->importContentFile($pkg->getPackagePath() . '/content.xml');
    }

    public function uninstall()
    {
        $eventpage = page::getByPath('/event');
        if(is_object($eventpage))
            $eventpage->delete();
        parent::uninstall();
        //$c = \Page::getByPath('/accommodations');
        //if (is_object($c) && !$c->isError()) {
        //    $c->moveToTrash();
        //}
    }
}
