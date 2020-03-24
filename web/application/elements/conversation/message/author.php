<?
defined('C5_EXECUTE') or die("Access Denied.");
$u = new User();
?>
<? if ($u->isRegistered()) { ?>
	<div class="ccm-conversation-avatar"><? print Loader::helper('concrete/avatar')->outputUserAvatar($ui)?></div>
<? } else {
	// non-logged-in posting. ?>
	<div class="form-group">
		<label class="control-label" for="cnvMessageAuthorName"><?=t('Full Name')?>:</label>
		<input type="text" class="form-control" name="cnvMessageAuthorName" maxlength="70" />
	</div>
	<div class="form-group">
		<label class="control-label" for="cnvMessageAuthorEmail"><?=t('Email Address')?>:</label>
		<input type="text" class="form-control" name="cnvMessageAuthorEmail" maxlength="255" />
	</div>
	<div class="form-group">
		<label class="control-label" for="cnvMessageAuthorWebsite"><?=t('Website')?>:</label>
		<input type="text" class="form-control" name="cnvMessageAuthorWebsite" />
	</div>
	<?
	$captcha = Core::make('captcha');
	?>
	<div class="form-group captchaRow">
		<? $captcha->label()?>
		<? $captcha->showInput();?>
		<? $captcha->display();?>
	</div>
<? } ?>
