<?
	use Concrete\Package\Calendar\Src\Attribute\Key\EventKey;
	use Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Event\EventOccurrence;
	$th = Loader::helper('text');
	$page = Page::getCurrentPage();
	$parent = Page::getByID($c->getCollectionParentID());
	$event_id = basename(dirname($_SERVER['REQUEST_URI']));
	$occurrence = EventOccurrence::getByID($event_id);
	
	if ($occurrence) {
		// Event permalink
		// Should to be better defined so not to rely on URL structure
		
		$event = $occurrence->getEvent();

		$name = $event->getName();

		if (!$name) {
		    $name = $page->getCollectionAttributeValue('meta_title');
		    if (!$name) {
		        $name = $page->getCollectionName();
		        if($page->isSystemPage()) {
		            $name = t($pageTitle);
		        }
		    }
		}
		
		$description = $event->getDescription();
		if (!$description) {
		    $description = $page->getCollectionAttributeValue('short_description');
		    if (!$description) {
		        $description = $page->getCollectionDescription();
		    }
		}
		$description = $th->shortenTextWord($description, 200, '');

		$eventImage = $event->getEventImage();
		$eventImage = File::getByID($eventImage);

		if ($eventImage instanceof File && !$eventImage->isError()) {
		    $size = $eventImage->getFullSize();
		    $thumb = Loader::helper('image')->getThumbnail($eventImage,1200,630,true);
		    if (\Config::get('concrete.cache.shs.use_cdn')) {
		    	// CDN is enabled
		    	$og_image_url = $thumb->src;
		    } else {
		    	$og_image_url = BASE_URL . $thumb->src;
		    }
		}

		echo '<meta property="og:title" content="' . $th->entities($name) . '" />
		';
		echo '<meta property="og:description" content="' . $th->entities($description) . '" />
		';
		echo '<meta property="og:url" content="' . BASE_URL . $_SERVER['REQUEST_URI'] . '" />
		';
		if ( isset($og_image_url) ) {
		    echo '<meta property="og:image" content="' .  $og_image_url . '" />
		    ';
		    echo '<meta property="og:image:width" content="1200" />
		    ';
		    echo '<meta property="og:image:height" content="630" />
		    ';
		}
		
	} else {
		$pageTitle = $page->getCollectionAttributeValue('og_title');
		if (!$pageTitle) {
		    $pageTitle = $page->getCollectionAttributeValue('meta_title');
		    if (!$pageTitle) {
		        $pageTitle = $page->getCollectionName();
		        if($page->isSystemPage()) {
		            $pageTitle = t($pageTitle);
		        }
		    }
		}

		$pageDescription = $page->getCollectionAttributeValue('meta_description');
		if (!$pageDescription) {
		    $pageDescription = $page->getCollectionAttributeValue('short_description');
		    if (!$pageDescription) {
		        $pageDescription = $page->getCollectionDescription();
		    }
		}
		$pageDescription = $th->shortenTextWord($pageDescription, 200, '');

		$og_image = $page->getAttribute('special_offer_image');
		if (!$og_image instanceof File) {
		    $og_image = $page->getAttribute('page_thumbnail');
		    if (!$og_image instanceof File && !empty($thumbnailID)) {
		        $og_image = File::getByID($thumbnailID);
		    }
		}

		if ($og_image instanceof File && !$og_image->isError()) {
		    $size = $og_image->getFullSize();
		    $thumb = Loader::helper('image')->getThumbnail($og_image,1200,630,true);
		    if (\Config::get('concrete.cache.shs.use_cdn')) {
		    	// CDN is enabled
		    	$og_image_url = $thumb->src;
		    } else {
		    	$og_image_url = BASE_URL . $thumb->src;
		    }
		}

		echo '<meta property="og:title" content="' . $th->entities($pageTitle) . '" />
		';
		echo '<meta property="og:description" content="' . $th->entities($pageDescription) . '" />
		';
		echo '<meta property="og:url" content="' . BASE_URL . $page->getCollectionPath() . '" />
		';
		if ( isset($og_image_url) ) {
		    echo '<meta property="og:image" content="' .  $og_image_url . '" />
		    ';
		    echo '<meta property="og:image:width" content="1200" />
		    ';
		    echo '<meta property="og:image:height" content="630" />
		    ';
		}
	}
?>
