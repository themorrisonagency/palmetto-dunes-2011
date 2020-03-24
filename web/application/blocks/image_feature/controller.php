<?php
namespace Application\Block\ImageFeature;

use Concrete\Core\Block\BlockController;
use Core;
use Less_Parser;
use Less_Tree_Rule;
use Page;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{

    public $helpers = array('form');

    protected $btInterfaceWidth = 600;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btExportPageColumns = array('internalLinkCID');
    protected $btInterfaceHeight = 520;
    protected $btTable = 'btImageFeature';

    public function getBlockTypeDescription()
    {
        return t("Displays an image, a title, and a short paragraph description.");
    }

    public function getBlockTypeName()
    {
        return t("Image Feature");
    }

    function getLinkURL()
    {
        if (!empty($this->externalLink)) {
            return $this->externalLink;
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

    public function registerViewAssets()
    {
        $this->requireAsset('css', 'font-awesome');
        if (is_object($this->block) && $this->block->getBlockFilename() == 'hover_description') {
            // this isn't great but it's the only way to do this and still make block
            // output caching available to this block.
            $this->requireAsset('javascript', 'bootstrap/tooltip');
            $this->requireAsset('css', 'bootstrap/tooltip');
        }
    }

    public function add()
    {
        $this->edit();
    }

    public function view()
    {
        $this->set('linkURL', $this->getLinkURL());
        $this->set('file', $this->fID ? \File::getByID($this->fID) : null);
    }

    public function edit()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
        $this->requireAsset('redactor');
    }

    public function getSearchableContent()
    {
        return $this->title . ' ' . $this->paragraph;
    }

    public function save($args)
    {
        $args['fID'] = intval($args['fID'], 10);
        switch (intval($args['linkType'])) {
            case 1:
                $args['externalLink'] = '';
                break;
            case 2:
                $args['internalLinkCID'] = 0;
                break;
            default:
                $args['externalLink'] = '';
                $args['internalLinkCID'] = 0;
                break;
        }
        unset($args['linkType']);
        parent::save($args);
    }

}
