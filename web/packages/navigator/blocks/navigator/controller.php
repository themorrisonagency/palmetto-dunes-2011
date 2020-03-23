<?php 
namespace Concrete\Package\Navigator\Block\Navigator;
use \Concrete\Core\Block\BlockController;
use Loader;

defined('C5_EXECUTE') or die("Access Denied.");
class Controller extends BlockController
{
    protected $btTable = 'btNavigator';
    protected $btInterfaceWidth = "650";
    protected $btWrapperClass = 'ccm-ui';
    protected $btInterfaceHeight = "465";

    public function getBlockTypeDescription()
    {
        return t("Add Navigation to Internal/External Links");
    }

    public function getBlockTypeName()
    {
        return t("Navigator");
    }

    public function add()
    {
        $this->requireAsset('core/sitemap');
    }

    public function edit()
    {
        $this->requireAsset('core/sitemap');          
        $db = Loader::db();
        $items = $db->GetAll('SELECT * from btNavigatorItem WHERE bID = ? ORDER BY sort', array($this->bID));
        $this->set('items', $items);
    }

    public function view()
    {
        $db = Loader::db();
        $items = $db->GetAll('SELECT * from btNavigatorItem WHERE bID = ? ORDER BY sort', array($this->bID));
        $this->set('items', $items);
    }

    public function duplicate($newBID) {
        parent::duplicate($newBID);
        $db = Loader::db();
        $v = array($this->bID);
        $q = 'select * from btNavigatorItem where bID = ?';
        $r = $db->query($q, $v);
        while ($row = $r->FetchRow()) {
            $vals = array($newBID,$args['linkType'][$i],$args['pageID'][$i],$args['url'][$i],$args['linkName'][$i],$args['sort'][$i]);  
            $db->execute('INSERT INTO btNavigatorItem (bID, linkType, pageID, url, linkName, sort) values(?,?,?,?,?,?)', $vals);
        }
    }

    public function delete()
    {
        $db = Loader::db();
        $db->delete('btNavigatorItem', array('bID' => $this->bID));
        parent::delete();
    }

    public function save($args)
    {
        $db = Loader::db();
        $db->execute('DELETE from btNavigatorItem WHERE bID = ?', array($this->bID));
        $count = count($args['sort']);
        $i = 0;
        parent::save($args);
        while ($i < $count) {
            $vals = array($this->bID,$args['linkType'][$i],$args['pageID'][$i],$args['url'][$i],$args['linkName'][$i],$args['sort'][$i]);      
            $db->execute('INSERT INTO btNavigatorItem (bID, linkType, pageID, url, linkName, sort) values(?,?,?,?,?,?)', $vals);
            $i++;
        }
    }
    

}