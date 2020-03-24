<?php
namespace Application\Block\ShsPushGrid;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Loader;
use Page;

class Controller extends BlockController
{
    protected $btTable = 'btShsPushGrid';
    protected $btExportTables = array('btShsPushGrid', 'btShsPushGridEntries');
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
        return t("Displays a grid of 8 push elements");
    }

    public function getBlockTypeName()
    {
        return t("Push Grid");
    }

    public function getSearchableContent()
    {
        $content = '';
        $db = Loader::db();
        $v = array($this->bID);
        $q = 'select * from btShsPushGridEntries where bID = ?';
        $r = $db->query($q, $v);
        foreach($r as $row) {
           $content.= $row['title'].' ';
           $content.= $row['description'].' ';
        }
        return $content;
    }

    function getContent() {
        return LinkAbstractor::translateFrom($this->content);
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
		$res_grid = $db->GetAll('SELECT * FROM btShsPushGridEntries WHERE bID = ? ORDER BY sort ASC', array($this->bID));
        $i = 0;
        foreach ($res_grid as $grid) {
            $res_grid[$i]['descr'] = LinkAbstractor::translateFromEditMode($grid['descr']);
            $i++;
        }
		$this->set('res_grid', $res_grid);
		
    }

    public function registerViewAssets()
    {
        $this->requireAsset('javascript', 'jquery');
    }

    public function view()
    {
		$db = Loader::db();
		$res_grid = $db->GetAll('SELECT * FROM btShsPushGridEntries WHERE bID = ? ORDER BY sort ASC', array($this->bID));
        $i = 0;
        foreach ($res_grid as $grid) {
            $res_grid[$i]['descr'] = LinkAbstractor::translateFrom($grid['descr']);
            $i++;
        }
		$this->set('res_grid', $res_grid);
    }

    public function delete()
    {
        $db = Loader::db();
        $db->delete('btShsPushGridEntries', array('bID' => $this->bID));
        parent::delete();
    }

    public function save($args)
    {
		$db = Loader::db();
		$db->execute('DELETE from btShsPushGridEntries WHERE bID = ?',$this->bID);
		
		
		for ($i=1;$i<=8;$i++){
			switch (intval($args["linkType$i"])) {
				case 1:
					$args["link_url$i"] = '';
					break;
				case 2:
					$args["link_id$i"] = 0;
					break;
				default:
					$args["link_id$i"] = 0;
					$args["link_url$i"] = '';
					break;
			}		
			$db->execute('INSERT INTO btShsPushGridEntries (title, img_title, img, descr, link_id, link_url, link_text, sort, bID) values(?,?,?,?,?,?,?,?,?)',
				array(
					$args["title$i"],
					$args["img_title$i"],
					$args["img$i"],
					LinkAbstractor::translateTo($args["descr$i"]),
					$args["link_id$i"],
                    $args["link_url$i"],
					$args["link_text$i"],
					$args["order$i"],
					$this->bID
				)
			);
		}
		
    }

}