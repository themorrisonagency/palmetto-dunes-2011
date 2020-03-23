<?php

/***
Last updated 2016-07-12

0.3 - fixed issue where calendar would break if page was referenced by id

0.4 - fixed issue where selecting a new month would break if page was referenced by id
**/
namespace Concrete\Package\ShsEventsSeriesTwo;

use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Package\Package;
use DoctrineProxies\__CG__\Concrete\Core\Block\BlockType\BlockType;

class Controller extends Package
{

    protected $pkgHandle = 'shs_events_series_two';
    protected $appVersionRequired = '5.7.4';
    protected $pkgVersion = '0.3';

    public function getPackageDescription()
    {
        return t("Adds events series two functionality to your site.");
    }

    public function getPackageName()
    {
        return t("SHS Events Series Two");
    }

    public function install()
    {
        $pre_pkg = Package::getByHandle('shs_events');
        if (!is_object($pre_pkg)){
            throw new \Exception(t('SHS Events Series Two requires the SHS Events package to be installed and available.'));
            exit;
        }
        $pkg = parent::install();
        $cif = new ContentImporter();
        $cif->importContentFile($pkg->getPackagePath() . '/content.xml');
    }

    public function uninstall()
    {
        parent::uninstall();
    }
}
