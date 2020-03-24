<?php
	defined('C5_EXECUTE') or die("Access Denied.");
	$c = Page::getCurrentPage();
	if (!$content && is_object($c) && $c->isEditMode()) { ?>
		<div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Content Block.')?></div> 
	<?php } else { ?>
		<div class="parallax-section" data-type="background" data-speed="20">
			<div class="parallax-inner">
	<?php
		print $content; ?>
			</div>
		</div>
	<?php
	}