<?php
$c = Page::getCurrentPage();
if($c instanceof Page) {
	$cID = $c->getCollectionID();
}
if ($linkURL != '' && !empty($linkedFileID)) {
    $linkURL = View::url('/download_file', $controller->getFileID(),$cID);
}
?>
<div class="amax-pushmarketinginterior push-interior-right video-push">
	<div class="amax-module-body">
		<div class="interior-push">
			<div class="int-push-wrapper">
				<div class="push-inset">
					<?php if ($linkURL != ''){ ?> <a href="<?= $linkURL ?>" class="fancybox-media"> <?php } ?>
						<?php if ($file instanceof \File) { ?>
						<img width="582" height="328" alt="<?= $title ?>" src="<?= $file->getRelativePathFromID($file->getFileID()) ?>" />
						<?php } ?>
					<?php if ($linkURL != ''){ ?> </a> <?php } ?>

					<div class="descr-wrapper">
						<?= $paragraph ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>