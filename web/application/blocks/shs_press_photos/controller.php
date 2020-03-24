<?php
namespace Application\Block\ShsPressPhotos;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Attribute\Key\FileKey;
use Core;
use File;
use Loader;
use Concrete\Core\Tree\Tree;
use Concrete\Core\Tree\Type\Topic as TopicTree;
use Concrete\Core\Tree\Node\Node;
use Concrete\Core\Tree\Node\Type\TopicCategory;
use Concrete\Core\File\Set\Set as FileSet;
use FileList;


defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{

    public $helpers = array('form');

    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 400;
    protected $btTable = 'btTopicList';

    public function getBlockTypeDescription()
    {
        return t("Displays a list of your press photos with category filter.");
    }

    public function getBlockTypeName()
    {
        return t("Press Photos");
    }

    private function getFileSetID() {
        Loader::model('file_set');
        $fileSet = FileSet::getByName('Press Photos');
        return $fileSet->fsID;
    }

    public function view($topic = null)
    {
        $filterTopics = array();
        $ak = FileKey::getByHandle('press_photo_category');
        if (is_object($ak)) {
            $node = Node::getByID($ak->getController()->getTopicParentNode());
            if ($node instanceof TopicCategory) {
                $node->populateChildren();
                $filterTopics = $node->getChildNodes();
                $validIDs = array_map(function(Node $node) {
                    return $node->getTreeNodeID();
                }, $filterTopics);
            }
        }
        $this->set('filterTopics', $filterTopics);
        if (is_null($topic)) {
            // if no topic is set, use the first topic within the topic tree
            $topic = $filterTopics[0]->getTreeNodeID();
        }

        $files = $this->getFilesetImages($this->getFileSetID());
        $images = $this->processImageFiles($files, $topic);
        $this->set('images', $images);

    }

    public function action_topic($treeNodeID = false, $topic = false)
    {
        $this->set('active_topic', intval($treeNodeID));
        $this->view(intval($treeNodeID));
    }


    public function getTopicLink(\Concrete\Core\Tree\Node\Node $topic)
    {
        $view = \View::getInstance();
        if ($topic) {
            $nodeName = $topic->getTreeNodeName();
            $nodeName = strtolower($nodeName); // convert to lowercase
            $nodeName = Core::make('helper/text')->encodePath($nodeName); // urlencode
            return $view->action('topic', $topic->getTreeNodeID(), $nodeName);
        }
    }

    static function getFilesetImages($fsID) {
        Loader::model('file_set');
        //Loader::model('file_list');

        $fs = FileSet::getByID($fsID);
        $files = $fs->getFiles();
        return $files;
    }

    private function processImageFiles($imageFiles, $active_topic) {
        $ih = Loader::helper('image');
        $nh = Loader::helper('navigation');

        $image = array();
        $images = array();

        foreach ($imageFiles as $fID) {
            if ($fID) {
                $file = File::getByID($fID);

                if (is_object($file)) {
                    $topics = $file->getAttribute('press_photo_category');
                    $show = false;
                    if (is_array($topics)) {
                        foreach($topics as $topic) {
                            if ($active_topic == $topic->getTreeNodeID()) {
                                $show = true;
                                break;
                            }
                        }
                    }

                    if ($show) {
                        $image['fileName'] = $file->getFileName();
                        $image['fID'] = $fID;
                        $image['fullFilePath'] = $file->getRelativePath();
                        $image['imgHeight'] = $file->getAttribute("height");
                        $image['title'] = $file->getTitle();
                        $image['description'] = $file->getDescription();
                        $image['thumbnail'] = $ih->getThumbnail($file, 190, 108);
                        
                        if ($maxHeight == 0 || $image['imgHeight'] > $maxHeight) {
                            $maxHeight = $image['imgHeight'];
                        }
                        $images[] = $image;
                    }
                }
            }
        }
        $this->images = $images;

        return $images;
    }
}
