<?php
$c = Page::getCurrentPage();
if($c instanceof Page) {
	$cID = $c->getCollectionID();
}
if ($linkURL != '' && !empty($linkedFileID)) {
    $linkURL = View::url('/download_file', $controller->getFileID(),$cID);
}
?>
<div class="photo-desc-cta">
	<div class="push-inset">
		<?php if ($file instanceof \File) { ?>
			<?php if ($linkURL != ''){ ?><a href="<?= $linkURL ?>"><?php } ?>
				<img alt="<?= $title ?>" src="<?= $file->getRelativePathFromID($file->getFileID()) ?>" />
			<?php if ($linkURL != ''){ ?></a><?php } ?>
		<?php } ?>
	</div>
	<div class="push-description">
		<?= $paragraph ?>
	</div>
	<?php if ($linkURL != ''){ ?>
		<div class="int-push-link"><a class="blue-btn" href="<?= $linkURL ?>"><?= $buttonText ?: t('Learn More') ?></a></div>
	<?php } ?>
</div>