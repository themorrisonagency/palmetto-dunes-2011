<?php     

namespace Concrete\Package\ShsVideoMastheadSlider;
use Package;
use BlockType;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package {

	protected $pkgHandle = 'shs_video_masthead_slider';
	protected $appVersionRequired = '5.7.4';
	protected $pkgVersion = '1.0';
	
	public function getPackageDescription() {
		return t('Adds video masthead slider to your site.');
	}
	
	public function getPackageName() {
		return t('SHS Video Masthead Slider');
	}
	
	public function install() {
		$pkg = parent::install();

		BlockType::installBlockTypeFromPackage('shs_video_slider', $pkg);
	}

}