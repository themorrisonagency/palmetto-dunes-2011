<?php
$c = Page::getCurrentPage();
if($c instanceof Page) {
	$cID = $c->getCollectionID();
}
if ($linkURL != '' && !empty($linkedFileID)) {
    $linkURL = View::url('/download_file', $controller->getFileID(),$cID);
}
?>
<div class="interior-push">
	<div class="int-push-wrapper">
		<?php if ($linkURL != ''){ ?>
		<div class="int-push-link">
			<a target="_blank" class="orange-btn" href="<?= $linkURL ?>"><?= $buttonText ?: t('Learn More') ?></a>
		</div>
		<?php } ?>
		<div class="push-inset">
			<?php if ($file instanceof \File) { ?>
			<img alt="<?= $title ?>" src="<?= $file->getRelativePathFromID($file->getFileID()) ?>">
			<?php } ?>
		</div>
		<div class="int-push-description">
			<?= $title ?>
		</div>
	</div>
</div>