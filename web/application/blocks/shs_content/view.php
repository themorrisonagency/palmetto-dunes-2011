<?php
	defined('C5_EXECUTE') or die("Access Denied.");
	$c = Page::getCurrentPage();
	if (!$content && is_object($c) && $c->isEditMode()) { ?>
		<div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Content Block.')?></div> 
	<?php } else { ?>
		<style type="text/css">
			.more-to-explore-wrapper img {			
				margin-left: 20px;
			}
			.more-to-explore-wrapper .tertiary {
				float: left;
				clear: left;
			}
			.more-to-explore-wrapper ul.tertiary li {
				text-align: center;
			}
			.more-to-explore-wrapper {
				width: 1030px;
				margin: 0px auto;
				padding-top: 20px;
			}
			.more-to-explore-wrapper .section-title {
				width: 280px;
				float: left;
				color: #727272;
				font-family: "Cabin",Arial,sans-serif;
				font-size: 14px;
				font-weight: 700;
				line-height: 26px;
				margin: 0 0 15px;
				text-transform: uppercase;				
				text-align: center;
			}
			.more-to-explore-wrapper .section-title span {
				color: #5a5a5a;
				display: block;
				font-size: 24px;			
			}
		</style>
		<div class="section-title">
		<?php print $content; ?>
		</div>
	<?php } ?>