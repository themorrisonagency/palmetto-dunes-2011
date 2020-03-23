<?php

namespace Concrete\Package\PalmettoDunesBlog;

use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Package\Package;
use DoctrineProxies\__CG__\Concrete\Core\Block\BlockType\BlockType;

class Controller extends Package
{

    protected $pkgHandle = 'palmetto_dunes_blog';
    protected $appVersionRequired = '5.7.3.2b1';
    protected $pkgVersion = '0.5';

    public function getPackageDescription()
    {
        return t("Adds blog functionality to palmettodunes.com.");
    }

    public function getPackageName()
    {
        return t("Blog");
    }

    public function uninstall()
    {
        parent::uninstall();
        $c = \Page::getByPath('/blog');
        if (is_object($c) && !$c->isError()) {
            $c->moveToTrash();
        }
    }

    public function install()
    {
        $pkg = parent::install();
        $cif = new ContentImporter();
        $cif->importContentFile($pkg->getPackagePath() . '/content.xml');
    }
}
