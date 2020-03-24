<?
	defined('C5_EXECUTE') or die("Access Denied.");
	$c = Page::getCurrentPage();
?>

<div id="HTMLBlock<?=intval($bID)?>" class="HTMLBlock">
<?
if ($c->isEditMode()) { ?>
	<div class="ccm-edit-mode-disabled-item">
        <div style="padding: 40px 0px 40px 0px"><? echo t('Survey Gizmo form disabled in edit mode.')?></div>
    </div>
<?
} else {
	echo $content;
} ?>
</div>