<?php
namespace Application\Block\ShsPushMarketing;

use Concrete\Core\Block\BlockController;
use Core;
use \File;
use Concrete\Core\Editor\LinkAbstractor;
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
        return t("Handles the push marketing blocks");
    }

    public function getBlockTypeName()
    {
        return t("Push Marketing");
    }

    function getLinkURL()
    {
        if (!empty($this->externalLink)) {
            return $this->externalLink;
        } elseif (!empty($this->linkedFileID)) {
            return $this->linkedFileID;
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

    public function getFileID() {return $this->linkedFileID;}
    
    public function getFileObject() {
        return File::getByID($this->linkedFileID);
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
        $this->set('paragraph', LinkAbstractor::translateFrom($this->paragraph));
        $this->set('file', $this->fID ? \File::getByID($this->fID) : null);
    }

    public function edit()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
        $this->requireAsset('redactor');
        $this->set('paragraph', LinkAbstractor::translateFromEditMode($this->paragraph));
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
                $args['linkedFileID'] = '';
                break;
            case 2:
                $args['internalLinkCID'] = 0;
                $args['linkedFileID'] = '';
                break;
            case 3:
                $args['externalLink'] = '';
                $args['internalLinkCID'] = 0;
                break;
            default:
                $args['externalLink'] = '';
                $args['internalLinkCID'] = 0;
                $args['linkedFileID'] = '';
                break;
        }
        $args["paragraph"] = LinkAbstractor::translateTo($args["paragraph"]);
        unset($args['linkType']);
        parent::save($args);
    }

}
