<?php
namespace Application\Block\ShsVideoSlider;

use Concrete\Core\Block\BlockController;
use Loader;
use Page;
use File;

class Controller extends BlockController
{
    protected $btTable = 'btShsVideoSlider';
    protected $btExportTables = array('btShsVideoSlider', 'btShsVideoSliderEntries');
    protected $btInterfaceWidth = "600";
    protected $btWrapperClass = 'ccm-ui';
    protected $btInterfaceHeight = "465";
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btIgnorePageThemeGridFrameworkContainer = true;

    public function getBlockTypeDescription()
    {
        return t("Display a video slider on your site.");
    }

    public function getBlockTypeName()
    {
        return t("Video Slider");
    }

    public function boolString($bValue = false) {
      return ($bValue ? 'true' : 'false');
    }

    public function getFileObject($fID)
    {
        return File::getByID($fID);
    }

    public function getSearchableContent()
    {
        $content = '';
        $db = Loader::db();
        $v = array($this->bID);
        $q = 'select * from btShsVideoSliderEntries where bID = ?';
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

        // set default slider settings
        $this->set('autoplay','1');
        $this->set('arrows','1');
        $this->set('speed','600');
        $this->set('autoplaySpeed','10000');
        $this->set('muteSound','1');
        $this->set('loopVideo','1');
    }

    public function edit()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
        $this->requireAsset('redactor');
        $db = Loader::db();
        $query = $db->GetAll('SELECT * from btShsVideoSliderEntries WHERE bID = ? ORDER BY sortOrder', array($this->bID));
        $this->set('rows', $query);
    }

    public function registerViewAssets()
    {
        $this->requireAsset('javascript', 'jquery');
        //$this->requireAsset('javascript', 'slick');
    }

    public function view()
    {
        $videoImage = ($this->videoImage) ? File::getByID($this->videoImage)->getRelativePath() : 0;
        $this->set('videoImage', $videoImage);
        $videoImageMobile = ($this->videoImageMobile) ? File::getByID($this->videoImageMobile)->getRelativePath() : 0;
        $this->set('videoImageMobile', $videoImageMobile);		

        $db = Loader::db();
        $r = $db->GetAll('SELECT * from btShsVideoSliderEntries WHERE bID = ? ORDER BY sortOrder', array($this->bID));
        // in view mode, linkURL takes us to where we need to go whether it's on our site or elsewhere
        $rows = array();
        foreach($r as $q) {
            if (!$q['externalLinkURL'] && $q['internalLinkCID']) {
                $c = Page::getByID($q['internalLinkCID'], 'ACTIVE');
                $q['linkURL'] = $c->getCollectionLink();
            } elseif (!$q['externalLinkURL'] && $q['linkedFileID']) {
                $q['linkURL'] = \URL::to('/download_file', $q['linkedFileID'],1);
            } else {
                $q['linkURL'] = $q['externalLinkURL'];
            }
            $rows[] = $q;
        }
        $this->set('rows', $rows);
    }

    public function duplicate($newBID) {
        parent::duplicate($newBID);
        $db = Loader::db();
        $v = array($this->bID);
        $q = 'select * from btShsVideoSliderEntries where bID = ?';
        $r = $db->query($q, $v);
        while ($row = $r->FetchRow()) {
            $db->execute('INSERT INTO btShsVideoSliderEntries (bID, video, videoImage, videoImageMobile, promoTitle, promoDescription, promoButtonText, externalLinkURL, internalLinkCID, linkedFileID, sortOrder) values(?,?,?,?,?,?,?,?,?,?,?)',
                array(
                    $newBID,
                    $row['video'],
                    $row['videoImage'],
					$row['videoImageMobile'],
                    $row['promoTitle'],
                    $row['promoDescription'],
                    $row['promoButtonText'],
                    $row['internalLinkCID'],
                    $row['externalLinkURL'],
                    $row['linkedFileID'],
                    $row['sortOrder']
                )
            );
        }
    }

    public function delete()
    {
        $db = Loader::db();
        $db->delete('btShsVideoSliderEntries', array('bID' => $this->bID));
        parent::delete();
    }

    public function save($args)
    {
        $args['loopVideo'] = ($args['loopVideo']) ? 1 : 0;
        $args['muteSound'] = ($args['muteSound']) ? 1 : 0;

        $db = Loader::db();
        $db->execute('DELETE from btShsVideoSliderEntries WHERE bID = ?', array($this->bID));
        $count = count($args['sortOrder']);
        $i = 0;
        parent::save($args);

        while ($i < $count) {
            $externalLinkURL = $args['externalLinkURL'][$i];
            $internalLinkCID = $args['internalLinkCID'][$i];
            $linkedFileID = $args['linkedFileID'][$i];
            switch (intval($args['linkType'][$i])) {
                case 1:
                    $externalLinkURL = '';
                    $linkedFileID = 0;
                    break;
                case 2:
                    $internalLinkCID = 0;
                    $linkedFileID = 0;
                    break;
                case 3:
                    $externalLinkURL = '';
                    $internalLinkCID = 0;
                    break;
                default:
                    $externalLinkURL = '';
                    $internalLinkCID = 0;
                    $linkedFileID = 0;
                    break;
            }

            $db->execute('INSERT INTO btShsVideoSliderEntries (bID, video, videoImage, videoImageMobile, promoTitle, promoDescription, promoButtonText, externalLinkURL, internalLinkCID, linkedFileID, sortOrder) values(?,?,?,?,?,?,?,?,?,?,?)',
                array(
                    $this->bID,
                    $args['video'][$i],
                    $args['videoImage'][$i],
					$args['videoImageMobile'][$i],
                    $args['promoTitle'][$i],
                    $args['promoDescription'][$i],
                    $args['promoButtonText'][$i],
                    $externalLinkURL,
                    $internalLinkCID,
                    $linkedFileID,
                    $args['sortOrder'][$i]
                )
            );
            $i++;
        }
    }

}
