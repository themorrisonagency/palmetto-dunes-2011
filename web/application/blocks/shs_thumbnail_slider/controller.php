<?php
namespace Application\Block\ShsThumbnailSlider;

use Concrete\Core\Block\BlockController;
use Loader;
use Page;

class Controller extends BlockController
{
    protected $btTable = 'btShsThumbnailSlider';
    protected $btExportTables = array('btShsThumbnailSlider', 'btShsThumbnailSliderEntries');
    protected $btInterfaceWidth = "600";
    protected $btWrapperClass = 'ccm-ui';
    protected $btInterfaceHeight = "465";
    protected $btCacheBlockRecord = true;
    protected $btExportFileColumns = array(
        0 => 'fID',
        1 => 'tID'
    );
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btIgnorePageThemeGridFrameworkContainer = true;

    public function getBlockTypeDescription()
    {
        return t("Display a slider with thumbnail navigation.");
    }

    public function getBlockTypeName()
    {
        return t("Thumbnail Slider");
    }

    public function getSearchableContent()
    {
        $content = '';
        $db = Loader::db();
        $v = array($this->bID);
        $q = 'select * from btShsThumbnailSliderEntries where bID = ?';
        $r = $db->query($q, $v);
        foreach($r as $row) {
           $content.= $row['title'].' ';
           $content.= $row['description'].' ';
        }
        return $content;
    }

    public function add()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
        $this->requireAsset('redactor');
    }

    public function edit()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
        $this->requireAsset('redactor');
        $db = Loader::db();
        $query = $db->GetAll('SELECT * from btShsThumbnailSliderEntries WHERE bID = ? ORDER BY sortOrder', array($this->bID));
        $this->set('rows', $query);
    }

    public function registerViewAssets()
    {
        $this->requireAsset('javascript', 'jquery');
    }

    public function view()
    {
        $db = Loader::db();
        $r = $db->GetAll('SELECT * from btShsThumbnailSliderEntries WHERE bID = ? ORDER BY sortOrder', array($this->bID));
        // in view mode, linkURL takes us to where we need to go whether it's on our site or elsewhere
        $rows = array();
        foreach($r as $q) {
            if (!$q['linkURL'] && $q['internalLinkCID']) {
                $c = Page::getByID($q['internalLinkCID'], 'ACTIVE');
                $q['linkURL'] = $c->getCollectionLink();
            }
            $rows[] = $q;
        }
        $this->set('rows', $rows);
    }

    public function duplicate($newBID) {
        parent::duplicate($newBID);
        $db = Loader::db();
        $v = array($this->bID);
        $q = 'select * from btShsThumbnailSliderEntries where bID = ?';
        $r = $db->query($q, $v);
        while ($row = $r->FetchRow()) {
            $db->execute('INSERT INTO btShsThumbnailSliderEntries (bID, fID, tID, linkURL, title, description, buttonText, thumbnailCaption, sortOrder, autoRotate) values(?,?,?,?,?,?,?,?,?,?)',
                array(
                    $newBID,
                    $row['fID'],
                    $row['tID'],
                    $row['linkURL'],
                    $row['title'],
                    $row['description'],
                    $row['buttonText'],
                    $row['thumbnailCaption'],
                    $row['sortOrder'],
                    $row['autoRotate']
                )
            );
        }
    }

    public function delete()
    {
        $db = Loader::db();
        $db->delete('btShsThumbnailSliderEntries', array('bID' => $this->bID));
        parent::delete();
    }

    public function save($args)
    {
        $db = Loader::db();
        $db->execute('DELETE from btShsThumbnailSliderEntries WHERE bID = ?', array($this->bID));
        $count = count($args['sortOrder']);
        $i = 0;
        parent::save($args);

        while ($i < $count) {
            $linkURL = $args['linkURL'][$i];
            $internalLinkCID = $args['internalLinkCID'][$i];
            switch (intval($args['linkType'][$i])) {
                case 1:
                    $linkURL = '';
                    break;
                case 2:
                    $internalLinkCID = 0;
                    break;
                default:
                    $linkURL = '';
                    $internalLinkCID = 0;
                    break;
            }

            $db->execute('INSERT INTO btShsThumbnailSliderEntries (bID, fID, tID, title, description, sortOrder, linkURL, internalLinkCID, buttonText, thumbnailCaption, autoRotate) values(?,?,?,?,?,?,?,?,?,?,?)',
                array(
                    $this->bID,
                    intval($args['fID'][$i]),
                    intval($args['tID'][$i]),
                    $args['title'][$i],
                    $args['description'][$i],
                    $args['sortOrder'][$i],
                    $linkURL,
                    $internalLinkCID,
                    $args['buttonText'][$i],
                    $args['thumbnailCaption'][$i],
                    $args['autoRotate'] = ($args['autoRotate']) ? '1' : '0'
                )
            );
            $i++;
        }
    }

}