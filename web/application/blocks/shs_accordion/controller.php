<?php  
namespace Application\Block\ShsAccordion;
use Loader;
use Page;
use \Concrete\Core\Block\BlockController;

class Controller extends BlockController {

    public function getBlockTypeName()
    {
        return t("Accordion");
    }

	public function getBlockTypeDescription() {
		return t("Accordion");
	}

	public function view(){
        $db = Loader::db();
        $rows = $db->GetAll('SELECT * from btShsAccordion WHERE bID = ? ORDER BY sortOrder', array($this->bID));        
        $this->set('rows', $rows);	
	}
	
	public function add(){
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
        $this->requireAsset('redactor');		
	}	
	
	public function edit(){
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
        $this->requireAsset('redactor');
        $db = Loader::db();
        $query = $db->GetAll('SELECT * from btShsAccordion WHERE bID = ? ORDER BY sortOrder', array($this->bID));
        $this->set('rows', $query);	
	}	

	public function save($args){
        $db = Loader::db();
        $db->execute('DELETE from btShsAccordion WHERE bID = ?', array($this->bID));
        $count = count($args['sortOrder']);
        $i = 0;
        parent::save($args);
		
		while ($i < $count) {            
            $db->execute('INSERT INTO btShsAccordion (bID, title, description, sortOrder) values(?,?,?,?)',
                array(
                    $this->bID,                    
                    $args['title'][$i],
                    $args['description'][$i],
                    $args['sortOrder'][$i]                    
                )
            );
            $i++;
		}
	}		
}