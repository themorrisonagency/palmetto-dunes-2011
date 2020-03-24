<?php
namespace Application\Block\ShsTestimonialSlider;

use Concrete\Core\Block\BlockController;
use Loader;
use Page;

class Controller extends BlockController
{
    protected $btTable = 'btShsTestimonialSlider';
    protected $btExportTables = array('btShsTestimonialSlider', 'btShsTestimonialSliderItems');
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
        return t("Display animated list of testimonials or reviews.");
    }

    public function getBlockTypeName()
    {
        return t("Testimonial Slider");
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
		$query = $db->GetAll('SELECT * from btShsTestimonialSliderItems WHERE bID = ?', array($this->bID));
        $this->set('rows', $query);
    }

    public function registerViewAssets()
    {
        $this->requireAsset('javascript', 'jquery');
    }

    public function view()
    {

		$db = Loader::db();
		$query = $db->GetAll('SELECT * from btShsTestimonialSliderItems WHERE bID = ?', array($this->bID));
        $this->set('rows', $query);
    }

    public function save($args)
    {
		parent::save($args);
        $db = Loader::db();
		$db->execute('DELETE FROM btShsTestimonialSliderItems WHERE bID = ' . $this->bID);

		$count = count($args['sortOrder']);
		$i = 0;

		while ($i < $count) {
			$db->execute('INSERT INTO btShsTestimonialSliderItems (bID, img, descr, title, articleDate, sortOrder, readMoreLink, linkText) values(?,?,?,?,?,?,?,?)',
					array(
						$this->bID,
                        $args['img'][$i],
						$args['descr'][$i],
						$args['title'][$i],
						$args['articleDate'][$i],
						$args['sortOrder'][$i],
						$args['readMoreLink'][$i],
                        $args['linkText'][$i]
					)
				);
			$i++;
		}
    }

    public function delete()
    {
        $db = Loader::db();
        $db->delete('btShsTestimonialSliderItems', array('bID' => $this->bID));
        parent::delete();
    }

    public function duplicate($newBID) {
        parent::duplicate($newBID);
        $db = Loader::db();
        $v = array($this->bID);
        $q = 'select * from btShsTestimonialSliderItems where bID = ?';
        $r = $db->query($q, $v);
        while ($row = $r->FetchRow()) {
            $db->execute('INSERT INTO btShsTestimonialSliderItems (bID, img, descr, title, articleDate, sortOrder, readMoreLink, linkText) values(?,?,?,?,?,?,?,?)',
                array(
                    $newBID,
                    $row['img'],
                    $row['descr'],
                    $row['title'],
                    $row['articleDate'],
                    $row['sortOrder'],
                    $row['readMoreLink'],
                    $row['linkText']
                )
            );
        }
    }

}