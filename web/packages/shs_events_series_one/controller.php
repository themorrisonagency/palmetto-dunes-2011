<?php

namespace Concrete\Package\ShsEventsSeriesOne;

use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Package\Package;
use DoctrineProxies\__CG__\Concrete\Core\Block\BlockType\BlockType;

class Controller extends Package
{

    protected $pkgHandle = 'shs_events_series_one';
    protected $appVersionRequired = '5.7.4';
    protected $pkgVersion = '0.3';

    public function getPackageDescription()
    {
        return t("Adds events series one functionality to your site.");
    }

    public function getPackageName()
    {
        return t("SHS Events Series One");
    }

    public function install()
    {
        $pre_pkg = Package::getByHandle('shs_events');
        if (!is_object($pre_pkg)){
            throw new \Exception(t('SHS Events Series One requires the SHS Events package to be installed and available.'));
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
