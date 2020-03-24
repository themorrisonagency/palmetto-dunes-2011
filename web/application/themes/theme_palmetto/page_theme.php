<?php
namespace Application\Theme\ThemePalmetto;

use Concrete\Core\Page\Theme\Theme;
use Concrete\Core\Area\Layout\Preset\Provider\ThemeProviderInterface;

class PageTheme extends Theme implements ThemeProviderInterface {

    protected $pThemeGridFrameworkHandle = 'foundation';

    public function registerAssets() {
        $this->requireAsset('javascript', 'jquery');
    }

    public function getThemeAreaLayoutPresets()
    {
        $presets = array(
            array(
                'handle' => 'content_right_sidebar',
                'name' => 'Content with Right Sidebar',
                'container' => '<div class="ccm-layout-column-wrapper"></div>',
                'columns' => array(
                    '<div class="content-left"></div>',
                    '<div class="push-interior-right"></div>'
                ),
            )
        );
        return $presets;
    }

    public function getThemeAreaClasses()
    {
        return array(
            'Main' => array('content-full','content-left','border-top','border-right','push-interior-right','inset-right','content-centered','push-half','push-centered-thirds'),
            'Cross-Promo Content' => array('promo-left','promo-right','single-push'),
            'Secondary Content' => array('push-half','push-centered-thirds')
        );
    }

    public function getThemeBlockClasses()
    {
        return array(
            'shs_push_marketing' => array(
                'three-col'
            ),
            'page_title' => array(
                'gray'
            )
        );
    }

    public function getThemeEditorClasses() // this is for the redactor editor.
    {
        return array(
            array('title' => t('Orange Button'), 'menuClass' => '', 'spanClass' => 'orange-btn'),
            array('title' => t('Blue Button'), 'menuClass' => '', 'spanClass' => 'blue-btn'),
            array('title' => t('White Button'), 'menuClass' => '', 'spanClass' => 'blue-btn'),
            array('title' => t('Tan Button'), 'menuClass' => '', 'spanClass' => 'tan-btn'),
            array('title' => t('Green Button'), 'menuClass' => '', 'spanClass' => 'green-btn'),
			array('title' => t('Green Button with PDF'), 'menuClass' => '', 'spanClass' => 'green-btn pdf-link')
        );
    }

}

?>