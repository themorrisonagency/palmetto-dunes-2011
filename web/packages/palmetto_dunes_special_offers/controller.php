<?php

namespace Concrete\Package\PalmettoDunesSpecialOffers;

use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Package\Package;

class Controller extends Package
{

    protected $pkgHandle = 'palmetto_dunes_special_offers';
    protected $appVersionRequired = '5.7.3.1';
    protected $pkgVersion = '0.7';

    public function getPackageDescription()
    {
        return t("Adds special offers functionality to palmettodunes.com.");
    }

    public function getPackageName()
    {
        return t("Special Offers");
    }

    public function uninstall()
    {
        parent::uninstall();
        $c = \Page::getByPath('/special-offers');
        if (is_object($c) && !$c->isError()) {
            $c->moveToTrash();
        }
    }

    public function upgrade()
    {
        parent::upgrade();
        $bt = \BlockType::getByHandle('special_offers');
        if (is_object($bt)) {
            $bt->refresh();
        }
    }

    public function install()
    {
        $selector = \Package::getByHandle('page_selector_attribute');
        if (!is_object($selector)) {
            throw new \Exception(t('This package requires the page selector attribute type.'));
        }

        $pkg = parent::install();
        $cif = new ContentImporter();
        $cif->importContentFile($pkg->getPackagePath() . '/content.xml');

        $pages = array(
            '/dashboard/special_offers',
            '/dashboard/special_offers/schedule');

        $package = \Package::getByHandle($this->pkgHandle);
        foreach ($pages as $path) {
            $sp1 = \Page::getByPath($path);
            if (!$sp1 || $sp1->isError()) {
                \SinglePage::add($path, $package);
            }
        }
    }

    public function on_start()
    {
        $provider = new \Concrete\Package\PalmettoDunesSpecialOffers\Src\RouteServiceProvider(\Core::getFacadeRoot());
        $provider->register();

        $manager = \Core::make('manager/page_type/validator');
        $manager->extend('special_offer', function($app) {
            return new \Concrete\Package\PalmettoDunesSpecialOffers\Src\Page\Type\Validator\SpecialOfferValidator();
        });

    }
}
