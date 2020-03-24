<?php
defined('C5_EXECUTE') or die("Access Denied.");
$th = Loader::helper('text');
$c = Page::getCurrentPage();
?>

<div class="more-to-explore-wrapper">
	<div class="section-title">
		<p>
		<?php if ($pageListTitle){ ?>
			More to Explore in <span><?php echo $pageListTitle?></span>
		<?php } ?>
		</p>
	</div>

	<ul class="tertiary">
	<?php foreach($pages as $page){
		if ($c->cPath == $page->cPath) {
			if (is_object($page->getAttribute('masthead'))) {
				$thumbnail = $page->getAttribute('masthead');
			} else {
				$thumbnail = $page->getAttribute('thumbnail');
			}
		}
		$url = $nh->getLinkToCollection($page);
		$title = $th->entities($page->getCollectionName());
	?>	
		<li <?php if ($c->cPath == $page->cPath) { echo ' class="current" '; } ?>>
			<a href="<?php echo $url; ?>"><?php echo $title; ?></a>
		</li>	
	<?php } ?>
	</ul>

	<?php 
		if (is_object($thumbnail)){
			$img = Core::make('html/image', array($thumbnail));
			$tag = $img->getTag();		
			print $tag;
		} elseif (is_object($c->getAttribute('masthead'))) {
			$thumbnail = $c->getAttribute('masthead');
			$img = Core::make('html/image', array($thumbnail));
			$tag = $img->getTag();
			print $tag;
		} else {
			foreach($pages as $page){
				if (is_object($page->getAttribute('masthead'))) {
					$thumbnail = $page->getAttribute('masthead');
				} else {
					$thumbnail = $page->getAttribute('thumbnail');
				}
				if (is_object($thumbnail)) {
					$img = Core::make('html/image', array($thumbnail));
					$tag = $img->getTag();
					print $tag;			
					break;
				}
			}
		}
	?>
</div>