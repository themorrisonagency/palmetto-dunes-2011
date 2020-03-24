<?php
namespace Application\Block\ShsRestaurantPush;

use Concrete\Core\Block\BlockController;
use Loader;
use Page;

class Controller extends BlockController
{
	protected $btDescription = 'Displays push content';
	protected $btName = 'Restaurant Push Content';
    protected $btInterfaceWidth = "600";
    protected $btWrapperClass = 'ccm-ui';
    protected $btInterfaceHeight = "465";
	protected $btExportPageColumns = array('internalLinkCID');
	protected $btTable = 'btShsRestaurantPush';

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
	
	public function edit(){
		$this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
        $this->requireAsset('redactor');
		$db = Loader::db();
		$query = $db->GetAll('SELECT * from btShsRestaurantPush LIMIT 1');
		$this->set('rows', $query);
	}
	
	public function add(){
		$this->edit();
	}
	
	public function save($args){
		$db = Loader::db();		
		$db->execute('DELETE FROM btShsRestaurantPush WHERE bID = ' . $this->bID);
		
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
		
		$db->execute('INSERT INTO btShsRestaurantPush (push_img, push_logo, push_text, externalLink, internalLinkCID, bID) values(?,?,?,?,?,?)',
			array(
				$args['push-img'],
				$args['push-logo-img'],
				$args['push-text'],				
				$args['externalLink'],
				$args['internalLinkCID'],
				$this->bID
			)
		);		
	}
	
	public function view(){		
		$db = Loader::db();
        $query = $db->GetAll('SELECT * FROM btShsRestaurantPush WHERE bID = ' . $this->bID);
        $this->set('rows', $query);	
		$this->set('linkURL', $this->getLinkURL());
	}

}