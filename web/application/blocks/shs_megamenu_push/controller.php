<?php 
namespace Application\Block\ShsMegamenuPush;
defined("C5_EXECUTE") or die("Access Denied.");
use Concrete\Core\Block\BlockController;
 use Core;
 use Loader;
 use \File;
 use Page;
 use URL;
 use \Concrete\Core\Editor\Snippet;
 use Sunra\PhpSimple\HtmlDomParser;
 use \Concrete\Core\Editor\LinkAbstractor;

class Controller extends BlockController
{
    public $helpers = array (
  0 => 'form',
);
    public $btFieldsRequired = array (
  0 => 'pushimg',
  1 => 'title',
  2 => 'photolink',
);
    protected $btExportFileColumns = array (
  0 => 'pushimg',
);
    protected $btTable = 'btShsMegamenuPush';
    protected $btInterfaceWidth = 520;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime = 0;

    public function getBlockTypeDescription()
    {
        return t("Create the two special push elements within the Mega Menu");
    }

    public function getBlockTypeName()
    {
        return t("MegaMenu Push");
    }

    public function getSearchableContent()
    {
    	$content = array();
		$content[] = $this->description;
		$content[] = $this->title;
		$content[] = $this->text;
		return implode(" ", $content);
	}

    public function getLinkURL()
    {
        if (!empty($this->externalLink)) {
            return $this->externalLink;
        } elseif (!empty($this->fID)) {
            return $this->fID;
        } else {
            if (!empty($this->internalLinkCID)) {
                $linkToC = Page::getByID($this->internalLinkCID);
                return (empty($linkToC) || $linkToC->error) ? '' : \Core::make('helper/navigation')->getLinkToCollection(
                    $linkToC
                );
            } else {
                return '';
            }
        }
    }

    public function getFileID() {return $this->fID;}
    
    public function getFileObject() {
        return File::getByID($this->fID);
    }

    public function view()
    {
        
        if ($this->pushimg) {
            $f = \File::getByID($this->pushimg);
            if (is_object($f)) {
                $this->set("pushimg", $f);
            }
            else {
                $this->set("pushimg", false);
            }
        }
        $c = Page::getByID($this->photolink);
        if ($this->photolinktext == '') {
            $this->set('photolinktitle', $c->getCollectionName());
        } else {
            $this->set('photolinktitle', $this->photolinktext);
        }
        $this->set('photolink', \Core::make('helper/navigation')->getLinkToCollection($c));
        $this->set('description', LinkAbstractor::translateFrom($this->description));
        $this->set('text', LinkAbstractor::translateFrom($this->text));
        $this->set('linkURL', $this->getLinkURL());
    }

    public function add()
    {
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
    }

    public function edit()
    {
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
        $c = Page::getByID($this->photolink);
        if ($this->photolinktext == '') {
            $this->set('photolinktitle', $c->getCollectionName());
        } else {
            $this->set('photolinktitle', $this->photolinktext);
        }
        $this->set('description', LinkAbstractor::translateFromEditMode($this->description));
    }

    public function save($args)
    {
        $args['description'] = LinkAbstractor::translateTo($args['description']);
        $args['text'] = LinkAbstractor::translateTo($args['text']);

        switch (intval($args['linkType'])) {
            case 1:
                $args['externalLink'] = '';
                $args['fID'] = '';
                break;
            case 2:
                $args['internalLinkCID'] = 0;
                $args['fID'] = '';
                break;
            case 3:
                $args['internalLinkCID'] = 0;
                $args['externalLink'] = '';
                break;
            default:
                $args['externalLink'] = '';
                $args['internalLinkCID'] = 0;
                $args['fID'] = '';
                break;
        }
        unset($args['linkType']);

        parent::save($args);
    }

    public function validate($args)
    {
        $e = Loader::helper("validation/error");
        if(in_array("pushimg",$this->btFieldsRequired) && (trim($args["pushimg"]) == "" || !is_object(\File::getByID($args["pushimg"])))){
            $e->add(t("The %s field is required.", "Push Image"));
        }
        if(in_array("photolink",$this->btFieldsRequired) && (trim($args["photolink"]) == "" || $args["photolink"] == "0" || (($page = Page::getByID($args["photolink"])) && $page->error !== false))){
            $e->add(t("The %s field is required.", "Link to Page"));
        }
if(in_array("title",$this->btFieldsRequired) && trim($args["title"]) == ""){
            $e->add(t("The %s field is required.", "Title"));
        }
        return $e;
    }
    
}