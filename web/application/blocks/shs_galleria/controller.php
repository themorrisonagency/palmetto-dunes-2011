<?php
/**
 * Sabre's custom galleria block.
 */

namespace Application\Block\ShsGalleria;

use Concrete\Core\Block\BlockController;
use File;
use Loader;

class Controller extends BlockController {

    /**
     * @var array Helpers to register.
     */
    public $helpers = array('form');

    /**
     * @var string The interface height when adding/editing this block type.
     */
    protected $btInterfaceHeight = "465";

    /**
     * @var string The interface width when adding/editing this block type.
     */
    protected $btInterfaceWidth = "600";

    /**
     * @var string The primary table used by this block.
     */
    protected $btTable = 'btShsGalleria';

    /**
     * Actions to take when the block is added.
     */
    public function add()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('redactor');

        $this->_loadPickableCategories();
        $this->set('categories',$this->categories);
    }

    /**
     * Deletes the instance of this block type and all associated btShsGalleriaImages and btShsGalleriaVideos.
     */
    public function delete()
    {
        $db = Loader::db();
        $db->delete('btShsGalleriaImages', array('bID' => $this->bID));
        $db->delete('btShsGalleriaVideos', array('bID' => $this->bID));
        parent::delete();
    }

    /**
     * Edit action for this block.
     */
    public function edit()
    {
        $db = Loader::db();
        $this->requireAsset('core/file-manager');
        $this->requireAsset('redactor');
        
        $this->_loadGalleriaInformation();

        $block = $db->GetAll('SELECT * from btShsGalleriaImages WHERE bID = ? ORDER BY sortOrder', array($this->bID));
        $this->set('rows', $block);

        $this->_loadVideos();
        $this->set('videos',$this->videos);

        $this->_loadPickableCategories();
        $this->set('categories',$this->categories);
    }

    /**
     * Returns the block description.
     * @return string
     */
    public function getBlockTypeDescription()
    {
        return t("Display your images and captions in an attractive gallery format.");
    }

    /**
     * Returns the block type name.
     * @return string
     */
    public function getBlockTypeName()
    {
        return t("Galleria");
    }

    /**
     * Items to include when viewing this block type.
     */
    public function registerViewAssets()
    {
        $this->requireAsset('javascript', 'jquery');
    }

    /**
     * Save the extended galleria.
     * @param array $data The submitted galleria data.
     */
    public function save($data)
    {
        $db = Loader::db();

        // First we delete all the previous existing images belonging to this block instance.
        // Even if we aren't making changes, rebuilding our data is easier than keeping track
        // of sortOrder values associated with an ID.
        $db->execute('DELETE from btShsGalleriaImages WHERE bID = ?', array($this->bID));
        $db->execute('DELETE from btShsGalleriaVideos WHERE bID = ?', array($this->bID));
        $db->execute('DELETE from btShsGalleriaItemsCategories WHERE bID = ?', array($this->bID));

        if ($data['galleriaType'] == 'fileset' && $data['fsID'] > 0)
        {
            if (isset($data['filesetAttributes']) && is_array($data['filesetAttributes']))
            {
                $allowedAttributes = array('title','description');
                foreach ($data['filesetAttributes'] as $k => $attribute)
                {
                    if (!in_array($attribute,$allowedAttributes))
                    {
                        unset($data['filesetAttributes'][$k]);
                    }
                }
                $data['shownAttributes'] = implode(',',$data['filesetAttributes']);
            }
            parent::save($data);
        } else {
            $data['fsID'] = 0;
            $data['shownAttributes'] = '';
            parent::save($data);

            $count = count($data['sortOrder']);
            $i = 0;
            while ($i < $count) {
                if (ctype_digit((string)$data['fID'][$i]) && $data['fID'][$i] > 0)
                {
                    $db->execute('INSERT INTO btShsGalleriaImages (bID, fID, title, description, sortOrder) values(?,?,?,?,?)',
                        array(
                            $this->bID,
                            intval($data['fID'][$i]),
                            $data['title'][$i],
                            $data['description'][$i],
                            $data['sortOrder'][$i],
                        )
                    );
                }
                $i++;
            }
        }

        parent::save($data);

        // Save videos

        $block = $db->GetAll('SELECT treeNodeID, treeNodeTopicName from TreeTopicNodes');
        $myCats = array();
        foreach ($block as $i => $row) {
            $myCats[$row['treeNodeID']] = $row['treeNodeTopicName'];
        }

        if (isset($data['video']) && is_array($data['video'])) {
            foreach ($data['video'] as $video)
            {
                // Add video to
                $db->execute('INSERT INTO btShsGalleriaVideos (bID, videoURL, title, description, sortOrder) values(?,?,?,?,?)',
                    array(
                        $this->bID,
                        $video['videoURL'],
                        $video['title'],
                        $video['description'],
                        $video['sortOrder']
                    )
                );
                $videoid = $db->lastInsertId();

                // Add categories
                if (isset($video['categories']) && is_array($video['categories'])) {
                    foreach ($video['categories'] as $categoriesid => $one) 
                    {
                        $cat = $myCats[$categoriesid];
                        $db->execute('INSERT INTO btShsGalleriaItemsCategories (bID, galleriaVideoID, treeTopicNodesID) values(?,?,?)',
                            array(
                                $this->bID,
                                $videoid,
                                $categoriesid
                            )
                        );
                    }
                }
            }
        }

    }

    /**
     * View the instance of this block type.
     */
    public function view()
    {
        $this->_loadGalleriaInformation();
    }

    /**
     * Load the information on the instance of the galleria block.
     */
    protected function _loadGalleriaInformation()
    {
        if ($this->fsID == 0) {
			$this->_loadImages();
		} else {
			$this->_loadFileSet();
		}
        $galleriaType = ($this->fsID > 0) ? 'fileset' : 'custom';
		$this->set('galleriaType', $galleriaType);
        $this->set('images',$this->images);

        $this->_loadVideos();
        $this->set('videos',$this->videos);
    }

    /**
     * Load the file set associated with this galleria block.
     * @return boolean True if successful, false otherwise.
     */
    protected function _loadFileSet()
    {
		$db = Loader::db();
		if (!ctype_digit((string)$this->fsID) || $this->fsID < 0)
        {
            $this->images = array();
	        return false;
		}

        $fids = $db->GetCol('SELECT fsf.fID FROM FileSetFiles fsf WHERE fsf.fsID = ? ORDER BY fsDisplayOrder', array($this->fsID));

		$image = array();
		$image['groupSet'] = 0;
		$image['url'] = '';
		$images = array();

		foreach ($fids as $fID) {
			$file = File::getByID($fID);
            if (is_object($file)) {
    			$image['fileName'] = $file->getFileName();
    			$image['fID'] = $fID;
    			$image['fullFilePath'] = $file->getRelativePath();
    			$image['imgHeight'] = $file->getAttribute("height");
                $image['title'] = $file->getTitle();
                $image['description'] = $file->getDescription();

                if ($file->getAttribute('gallery_category')) {
                    $image['category'] = $file->getAttribute('gallery_category');
                }
                
    			if ($maxHeight == 0 || $image['imgHeight'] > $maxHeight) {
    				$maxHeight = $image['imgHeight'];
    			}
    			$images[] = $image;
            }
		}
		$this->images = $images;
        return true;
	}

    /**
     * Load the images associated with this galleria block.
     * @return boolean True if successful, false otherwise.
     */
    protected function _loadImages() {
		$db = Loader::db();

		if(!ctype_digit((string)$this->bID) || $this->bID < 0)
        {
            return false;
            $this->images = array();
        }

		$getImages = $db->getAll("SELECT * FROM btShsGalleriaImages WHERE bID = ? ORDER BY sortOrder",array($this->bID));

        $images = array();
        foreach ($getImages as $img)
        {
            $file = File::getByID($img['fID']);

            $image['fileName'] = $file->getFileName();
			$image['fID'] = $img['fID'];
			$image['fullFilePath'] = $file->getRelativePath();
			$image['imgHeight'] = $file->getAttribute("height");
            $image['title'] = $img['title'];
            $image['description'] = $img['description'];
			if ($maxHeight == 0 || $image['imgHeight'] > $maxHeight) {
				$maxHeight = $image['imgHeight'];
			}
            if ($file->getAttribute('gallery_category')) {
                $image['category'] = $file->getAttribute('gallery_category');
            }
			$images[] = $image;
        }
        $this->images = $images;
	}

    protected function _loadVideos() {
        $db = Loader::db();

        if(!ctype_digit((string)$this->bID) || $this->bID < 0)
        {
            return false;
            $this->videos = array();
        }

        // Get categories
        $getVideoCategories = $db->getAll(
            "SELECT c.*, n.treeNodeTopicName
             FROM btShsGalleriaItemsCategories c
             LEFT JOIN TreeTopicNodes n on n.treeNodeID = c.treeTopicNodesID
             WHERE c.bID = ? 
             AND c.galleriaVideoID IS NOT NULL",array($this->bID));
        $categories_by_videoid = array();
       
        foreach ($getVideoCategories as $item) {
            $categories_by_videoid[$item['galleriaVideoID']][$item['treeTopicNodesID']] = $item['treeNodeTopicName'];
        }

        // Get videos
        $getVideos = $db->getAll("SELECT * FROM btShsGalleriaVideos WHERE bID = ? ORDER BY sortOrder",array($this->bID));

        // output the value as a variable by setting the 2nd parameter to true
        foreach ($getVideos as $k => $vid)
        {
            $video['galleriaVideoID'] = $vid['galleriaVideoID'];
            $video['videoURL'] = $vid['videoURL'];
            $video['title'] = $vid['title'];
            $video['description'] = $vid['description'];
            $video['categoryids'] = (isset($categories_by_videoid[$vid['galleriaVideoID']]) ? implode(',',array_keys($categories_by_videoid[$vid['galleriaVideoID']])) : '');
            $video['categorynames'] = (isset($categories_by_videoid[$vid['galleriaVideoID']]) ? implode(',',$categories_by_videoid[$vid['galleriaVideoID']]) : '');

            // Add to array of videos
            $videos[] = $video;
        }
        $this->videos = $videos;
    }

    protected function _loadPickableCategories() {
        $db = Loader::db();

        $getCategories = $db->getAll("SELECT TreeTopicNodes.treeNodeID, TreeTopicNodes.treeNodeTopicName
            FROM TreeTopicNodes
            INNER JOIN TreeNodes ON TreeTopicNodes.treeNodeID = TreeNodes.treeNodeID 
            INNER JOIN TopicTrees ON TopicTrees.treeID = TreeNodes.treeID
            WHERE TopicTrees.topicTreeName = 'Gallery Categories'");

        $categories = array();
        foreach ($getCategories as $category)
        {
            $categories[] = $category;
        }
        $this->categories = $categories;
    }
}