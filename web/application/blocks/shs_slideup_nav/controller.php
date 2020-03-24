<?php
namespace Application\Block\ShsSlideupNav;

use Concrete\Core\Block\BlockController;
use Loader;
use Page;

class Controller extends BlockController
{
    protected $btTable = 'btShsSlideupNav';
    protected $btExportTables = array('btShsSlideupNav', 'btShsSlideupNavEntries');
    protected $btInterfaceWidth = "600";
    protected $btWrapperClass = 'ccm-ui';
    protected $btInterfaceHeight = "465";
    protected $btCacheBlockRecord = true;
    protected $btExportFileColumns = array('img');
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btIgnorePageThemeGridFrameworkContainer = true;

    public function getBlockTypeDescription()
    {
        return t("Display nav items that slide up in front of a masthead.");
    }

    public function getBlockTypeName()
    {
        return t("Slide-Up Navigation");
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
        $query = $db->GetAll('SELECT * FROM btShsSlideupNavEntries WHERE bID = ?', array($this->bID));
        $slides = array();
        foreach($query as $q) {
            $c = Page::getByID($q['contentID'], 'ACTIVE');
            $q['link'] = $c->getCollectionLink();
            if (isset($q['customTitle']) && $q['customTitle'] != '') {
                $q['title'] = $q['customTitle'];
            } else {
                $q['title'] = $c->getCollectionName();
            }
            $slides[] = $q;
        }

        $this->set('slides', $slides);
    }

    public function registerViewAssets()
    {
        $this->requireAsset('javascript', 'jquery');
    }

    public function view()
    {
        $db = Loader::db();     
        $query = $db->GetAll('SELECT * from btShsSlideupNavEntries WHERE bID = ? ORDER BY sortOrder', array($this->bID));
        /* Take page using contentID, grab Title and Link, and add it to the results */
        $slides = array();
        foreach($query as $q) {
            $c = Page::getByID($q['contentID'], 'ACTIVE');
            $q['link'] = $c->getCollectionLink();
            if (isset($q['customTitle']) && $q['customTitle'] != '') {
                $q['title'] = $q['customTitle'];
            } else {
                $q['title'] = $c->getCollectionName();
            }
            $slides[] = $q;
        }

        $this->set('slides', $slides);
    }

    public function duplicate($newBID) {
        parent::duplicate($newBID);
        $db = Loader::db();
        $v = array($this->bID);
        $q = 'select * from btShsSlideupNavEntries where bID = ?';
        $r = $db->query($q, $v);
        while ($row = $r->FetchRow()) {
            $db->execute('INSERT INTO btShsSlideupNavEntries (bID, sortOrder, contentID, customTitle, img, descr) values(?,?,?,?,?,?)',
                array(
                    $newBID,
                    $row['sortOrder'][$i],
                    $row['contentID'][$i],
                    $row['customTitle'][$i],
                    $row['img'][$i],
                    $row['descr'][$i]
                )
            );
        }
    }

    public function delete()
    {
        $db = Loader::db();
        $db->delete('btShsSlideupNavEntries', array('bID' => $this->bID));
        parent::delete();
    }

    public function save($args)
    {
        $db = Loader::db();
        $db->execute('DELETE from btShsSlideupNavEntries WHERE bID = ?', array($this->bID));
        $count = count($args['sortOrder']);
        $i = 0;
        parent::save($args);

        while ($i < $count) {

            $db->execute('INSERT INTO btShsSlideupNavEntries (bID, sortOrder, contentID, customTitle, img, descr) values(?,?,?,?,?,?)',
                array(
                    $this->bID,
                    $args['sortOrder'][$i],
                    $args['contentID'][$i],
                    $args['customTitle'][$i],
                    $args['img'][$i],
                    $args['descr'][$i]
                )
            );
            $i++;
        }
        
    }

}