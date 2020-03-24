<?php
namespace Application\Block\ShsBookingConsole;
use \Concrete\Core\Block\BlockController,
BlockType,
Loader;
defined('C5_EXECUTE') or die(_("Access Denied."));

	class Controller extends BlockController {

		var $pobj;

		protected $btTable = 'btShsBookingConsole';
		protected $btExportTables = array('btShsBookingConsole');

		public function getBlockTypeDescription() {
			return t("Create global booking console.");
		}
		public function getBlockTypeName() {
			return t("Booking Console");
		}

		public function getSearchableContent()
	    {
	        $content = '';
	        $db = Loader::db();
	        $v = array($this->bID);
	        $q = 'select * from btShsBookingConsole where bID = ?';
	        $r = $db->query($q, $v);
	        foreach($r as $row) {
	           $content.= $row['vacationRentalsTab'].' ';
	           $content.= $row['teeTimesTab'].' ';
	           $content.= $row['bikeRentalsTab'].' ';
	           $content.= $row['fareharborTab'].' ';
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
	        $query = $db->GetAll('SELECT * from btShsBookingConsole WHERE bID = ?', array($this->bID));
	        $this->set('rows', $query);
	    }


        public function view()
        {
            $db = Loader::db();
	        $r = $db->GetAll('SELECT * from btShsBookingConsole WHERE bID = ?', array($this->bID));
	        // in view mode, linkURL takes us to where we need to go whether it's on our site or elsewhere
	        $rows = array();
	        $this->set('rows', $rows);
        }


		public function composer() {
			$this->requireAsset('redactor');
            $this->requireAsset('core/file-manager');
		}
	}

?>