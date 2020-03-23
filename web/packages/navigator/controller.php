<?php       

namespace Concrete\Package\Navigator;
use Package;
use BlockType;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package
{

	protected $pkgHandle = 'navigator';
	protected $appVersionRequired = '5.7.1';
	protected $pkgVersion = '0.9.1';
	
	
	
	public function getPackageDescription()
	{
		return t("Add Navigation to Internal/External Links");
	}

	public function getPackageName()
	{
		return t("Navigator");
	}
	
	public function install()
	{
		$pkg = parent::install();
        BlockType::installBlockTypeFromPackage('navigator', $pkg); 
        
	}
}
?>