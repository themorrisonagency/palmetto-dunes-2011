<?php
namespace Application\Block\ShsPushSlider;

use Concrete\Core\Block\BlockController;
use Loader;
use Page;

class Controller extends BlockController
{
    protected $btTable = 'btImageSlider';
    protected $btExportTables = array('btImageSlider', 'btShsPushSlider');
    protected $btInterfaceWidth = "600";
    protected $btWrapperClass = 'ccm-ui';
    protected $btInterfaceHeight = "465";
    protected $btCacheBlockRecord = true;
    protected $btExportFileColumns = array('fID');
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btIgnorePageThemeGridFrameworkContainer = true;

    public function getBlockTypeDescription()
    {
        return t("SHS Push Slider");
    }

    public function getBlockTypeName()
    {
        return t("SHS Push Slider");
    }

    public function getSearchableContent()
    {
		/*
        $content = '';
        $db = Loader::db();
        $v = array($this->bID);
        $q = 'select * from btShsPushSlider where bID = ?';
        $r = $db->query($q, $v);
        foreach($r as $row) {
           $content.= $row['title'].' ';
           $content.= $row['description'].' ';
        }
        return $content;
		*/
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
        $query = $db->GetAll('SELECT * from btShsPushSlider WHERE bID = ? ORDER BY sort_order', array($this->bID));
        $this->set('rows', $query);
		
    }

    public function registerViewAssets()
    {
        $this->requireAsset('javascript', 'jquery');
    }

    public function view()
    {
		
		$db = Loader::db();
		$rows = $db->GetAll('SELECT * from btShsPushSlider WHERE bID = ? ORDER BY sort_order', array($this->bID));
		$this->set('rows', $rows);

    }



    public function delete()
    {
		/*
        $db = Loader::db();
        $db->delete('btShsPushSlider', array('bID' => $this->bID));
        parent::delete();
		*/
    }

    public function save($args)
    {
		$db = Loader::db();
		$db->execute('DELETE from btShsPushSlider WHERE bID = ?', array($this->bID));
		
		$count = count($args['sort_order']);
		$i = 0;
		
		
		
		while ($i < $count) {
			$db->execute('INSERT INTO btShsPushSlider (img, descr, guest_name, date, sort_order, bID) values(?,?,?,?,?,?)',
					array(
						$args['img'][$i],
						$args['descr'][$i],
						$args['guest_name'][$i],
						$args['date'][$i],
						$args['sort_order'][$i],
						$this->bID
					)
				);
			$i++;
		}
		

    }

}