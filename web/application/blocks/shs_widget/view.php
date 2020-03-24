<?
	defined('C5_EXECUTE') or die("Access Denied.");
	$c = Page::getCurrentPage();
	$p = new Permissions(Page::getCurrentPage());
?>

<div id="widget<?=intval($bID)?>" class="widget-container">
<?
if ($c->isEditMode() || $p->canViewToolbar()) { ?>
	<div class="ccm-edit-mode-disabled-item">
        <div style="padding: 40px 0px 40px 0px"><? echo t('Widget block display is disabled in Admin Mode.')?></div>
    </div>
<?
} else {
	echo $content;
} ?>
</div>