<?php
defined('C5_EXECUTE') or die("Access Denied.");
$th = Loader::helper('text');
$c = Page::getCurrentPage();

?>

<div class="press-list">

	<div class="press-listing-header row">
		<div class="column publish-date">
			<h2>Date</h2>
		</div>
		<div class="column press-title">
			<h2>News Title</h2>
			<div class="extras">

				<?php if ($rssUrl): ?>
					<div class="feedLink">
						<a href="<?php echo $rssUrl ?>" target="_blank" class="ccm-block-page-list-rss-feed"><em class="alt">RSS Feed</em></a>
					</div>
			    <?php endif; ?>
			</div>
		</div>
	</div>

	<ol class="press-items">
		<?php
        	foreach ($pages as $page):

        		$pressDateStamp = strtotime($page->getAttribute('release_date'));
        		$pressDate = date('M jS, Y',$pressDateStamp);
        		$newsLink = $page->getAttribute('news_external_link');
        		$releaseLink=$nh->getLinkToCollection($page);

        		$pageType = $c->getCollectionName();
        ?>

        		<li class="row">
        			<div class="column publish-date"><?php echo $pressDate; ?></div>
					<div class="column press-title">

						<?php
							if ($pageType == 'Recent News') {
						?>
								<a href="<?php echo $newsLink; ?>" target="_blank"><?php echo $page->getCollectionName(); ?></a>
						<?php
							} else {
						?>
								<a href="<?php echo $releaseLink; ?>"><?php echo $page->getCollectionName(); ?></a>
						<?php

							}
						?>

					</div>

        		</li>
        <?php
        	endforeach;
        ?>
	</ol>

</div>



<?php if ( $c->isEditMode() && $controller->isBlockEmpty()): ?>
    <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Page List Block.')?></div>
<?php endif; ?>