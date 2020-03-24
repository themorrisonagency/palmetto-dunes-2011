<?php
$c = Page::getCurrentPage();
if($c instanceof Page) {
	$cID = $c->getCollectionID();
}
if ($linkURL != '' && !empty($linkedFileID)) {
    $linkURL = View::url('/download_file', $controller->getFileID(),$cID);
}
?>
<div class="push-interior-right img-top">
	<?php if ($linkURL != ''){ ?> <a href="<?= $linkURL ?>"> <?php } ?>
		<div class="img-wrap">
			<?php if ($file instanceof \File) { ?>
			<img width="330" height="137" alt="<?= $title ?>" src="<?= $file->getRelativePathFromID($file->getFileID()) ?>" />
			<?php } ?>
			<div class="img-title"><?= $title ?></div>			
		</div>
	<?php if ($linkURL != ''){ ?> </a> <?php } ?>
</div>