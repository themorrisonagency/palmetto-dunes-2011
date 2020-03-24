<?php
namespace Application\Block\ShsPushGalleryLaunch;
defined("C5_EXECUTE") or die("Access Denied.");
use Concrete\Core\Block\BlockController;
 use Core;
 use Loader;
 use \File;

class Controller extends BlockController
{
    public $helpers = array (
  0 => 'form',
);
    public $btFieldsRequired = array (
);
    protected $btExportFileColumns = array (
  0 => 'image',
);
    protected $btTable = 'btShsPushGalleryLaunch';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime = 0;

    public function getBlockTypeDescription()
    {
        return t("Push marketing to launch specific gallery.");
    }

    public function getBlockTypeName()
    {
        return t("Push Marketing Gallery Launch");
    }

    public function getSearchableContent()
    {
    	$content = array();
		$content[] = $this->title;
		$content[] = $this->ctaIcon;
		return implode(" ", $content);
	}

    public function view()
    {

        if ($this->image) {
            $f = \File::getByID($this->image);
            if (is_object($f)) {
                $this->set("image", $f);
            }
            else {
                $this->set("image", false);
            }
        }
    }

    public function add()
    {
        $this->set('btFieldsRequired', $this->btFieldsRequired);

    }

    public function edit()
    {
        $this->set('btFieldsRequired', $this->btFieldsRequired);

    }

    public function save($args)
    {

        parent::save($args);
    }

    public function validate($args)
    {
        $e = Loader::helper("validation/error");
        if(in_array("image",$this->btFieldsRequired) && (trim($args["image"]) == "" || !is_object(\File::getByID($args["image"])))){
            $e->add(t("The %s field is required.", "Image"));
        }
        if(in_array("title",$this->btFieldsRequired) && trim($args["title"]) == ""){
            $e->add(t("The %s field is required.", "Title"));
        }
        if(in_array("ctaIcon",$this->btFieldsRequired) && trim($args["ctaIcon"]) == ""){
            $e->add(t("The %s field is required.", "CTA Icon"));
        }
        if(in_array("pinterestBtn",$this->btFieldsRequired)){
            if(!in_array($args["pinterestBtn"], array("no", "yes"))){
                $e->add(t("The %s field has an invalid value.", "Pinterest Button"));
            }
        }
        if(((!in_array("linkUrl",$this->btFieldsRequired) && trim($args["linkUrl"]) != "") || (in_array("linkUrl",$this->btFieldsRequired))) && !filter_var($args["linkUrl"], FILTER_VALIDATE_URL)){
            $e->add(t("The %s field does not have a valid URL.", "Link"));
        }
        return $e;
    }

}