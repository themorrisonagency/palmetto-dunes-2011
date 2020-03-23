<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<ul class="nav navigator-shell">
<?php  
	$nav = Loader::helper("navigation");
	foreach($items as $item){ 
		if($item['pageID']){
			//if set, grab the page object.
			$page = Page::getByID($item['pageID']);
            if(is_object($page)){
    			$pageName = $page->getCollectionName();
    			$theLink = $nav->getLinkToCollection($page);
            }
		}
        else{
            $theLink = $item['url'];
            $pageName = $item['linkName'];
        }
?>
    
    <li><a href="<?php echo $theLink?>"><?php echo $pageName?></a></li>
    
<?php  } ?>
</ul>