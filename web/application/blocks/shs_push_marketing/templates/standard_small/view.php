<?php
$c = Page::getCurrentPage();
if($c instanceof Page) {
	$cID = $c->getCollectionID();
}
if ($linkURL != '' && !empty($linkedFileID)) {
    $linkURL = View::url('/download_file', $controller->getFileID(),$cID);
}
?>
<div class="crosspromo-push">
	<div class="crosspromo-wrapper">
		<div class="crosspromo-title">
			<?= $title ?>
		</div>
		<div class="crosspromo-inset">	
			<a href="<?= $linkURL ?>">
				<?php
					if ($file instanceof \File) {
				?>
				<img src="<?= $file->getRelativePathFromID($file->getFileID()) ?>" alt="<?= $title ?>" width="160" height="160" />
				<?php
					}
				?>
			</a>		
		</div>
		<div class="crosspromo-inner-content">
			<div class="crosspromo-description">
				<?= $paragraph ?>
			</div>
			<?php if ($linkURL != ''){ ?>
			<div class="crosspromo-link">
				<a class="blue-btn" href="<?= $linkURL ?>"><?= $buttonText ?: t('Learn More') ?></a>
			</div>
			<?php } ?>
		</div>
	</div>
</div>