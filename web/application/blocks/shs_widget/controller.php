<?php
namespace Application\Block\ShsWidget;

use Loader;
use \Concrete\Core\Block\BlockController;

class Controller extends BlockController
{
    protected $btTable = 'btShsWidget';
    protected $btInterfaceWidth = "600";
    protected $btWrapperClass = 'ccm-ui';
    protected $btInterfaceHeight = "500";
    protected $btCacheBlockRecord = false;
    protected $btCacheBlockOutput = false;
    protected $btCacheBlockOutputOnPost = false;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btIgnorePageThemeGridFrameworkContainer = true;

    public $content = "";

    public function getBlockTypeDescription()
    {
        return t("Allows you to add 3rd party widgets to your site.");
    }

    public function getBlockTypeName()
    {
        return t("Widget");
    }

    public function view()
    {
        $this->set('content', $this->content);
    }

    public function add()
    {
        $this->edit();
    }

    public function edit()
    {
        $this->requireAsset('ace');
    }

    public function getSearchableContent()
    {
        return $this->content;
    }

    public function save($data)
    {
        $args['content'] = isset($data['content']) ? $data['content'] : '';
        parent::save($args);
    }

    public static function xml_highlight($s)
    {
        $s = htmlspecialchars($s);
        $s = preg_replace(
            "#&lt;([/]*?)(.*)([\s]*?)&gt;#sU",
            "<font color=\"#0000FF\">&lt;\\1\\2\\3&gt;</font>",
            $s
        );
        $s = preg_replace(
            "#&lt;([\?])(.*)([\?])&gt;#sU",
            "<font color=\"#800000\">&lt;\\1\\2\\3&gt;</font>",
            $s
        );
        $s = preg_replace(
            "#&lt;([^\s\?/=])(.*)([\[\s/]|&gt;)#iU",
            "&lt;<font color=\"#808000\">\\1\\2</font>\\3",
            $s
        );
        $s = preg_replace(
            "#&lt;([/])([^\s]*?)([\s\]]*?)&gt;#iU",
            "&lt;\\1<font color=\"#808000\">\\2</font>\\3&gt;",
            $s
        );
        $s = preg_replace(
            "#([^\s]*?)\=(&quot;|')(.*)(&quot;|')#isU",
            "<font color=\"#800080\">\\1</font>=<font color=\"#FF00FF\">\\2\\3\\4</font>",
            $s
        );
        $s = preg_replace(
            "#&lt;(.*)(\[)(.*)(\])&gt;#isU",
            "&lt;\\1<font color=\"#800080\">\\2\\3\\4</font>&gt;",
            $s
        );

        return nl2br($s);
    }
}
