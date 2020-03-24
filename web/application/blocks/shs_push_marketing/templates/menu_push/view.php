<?php
$c = Page::getCurrentPage();
if($c instanceof Page) {
	$cID = $c->getCollectionID();
}
if ($linkURL != '' && !empty($linkedFileID)) {
    $linkURL = View::url('/download_file', $controller->getFileID(),$cID);
}
?>
<div class="golf-course-wrapper push-Unit Details">
	<div class="push-inset">
		<?php if ($linkURL != ''){ ?> <a href="<?= $linkURL ?>"> <?php } ?>
			<?php if ($file instanceof \File) { ?>
			<img width="330" height="285" alt="<?= $title ?>" src="<?= $file->getRelativePathFromID($file->getFileID()) ?>" />
			<?php } ?>
		<?php if ($linkURL != ''){ ?> </a> <?php } ?>
		<?php if ($linkURL != ''){ ?> <a href="<?= $linkURL ?>"> <?php } ?>
			<div class="overlay" style="display: none;"></div>
		<?php if ($linkURL != ''){ ?> </a> <?php } ?>
	</div>
	<div class="push-title">
		<?php if ($linkURL != ''){ ?>
			<a href="<?= $linkURL ?>"><?= $title ?></a>
		<?php } else { ?>
			<?= $title ?>
		<?php } ?>
	</div>
	<div class="descr-wrapper">
		<?= $paragraph ?>
	</div>
</div>