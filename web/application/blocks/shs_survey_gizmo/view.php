<?
	defined('C5_EXECUTE') or die("Access Denied.");
	$c = Page::getCurrentPage();
	$p = new Permissions(Page::getCurrentPage());
    $config = Package::getByHandle('ec_recaptcha')->getConfig();

use Concrete\Package\EcRecaptcha\Src\Captcha\RecaptchaController; ?>

<div id="gizmo<?=intval($bID)?>" class="gizmo-container">
<?
if ($c->isEditMode() || $p->canViewToolbar()) { ?>
	<div class="ccm-edit-mode-disabled-item">
        <div style="padding: 40px 0px 40px 0px"><? echo t('Survey Gizmo block disabled in Admin Mode.')?></div>
    </div>
<?
} else {
	echo $content;

} ?>
    <div class="column small-12 field">
        <?php RecaptchaController::showInputV3(); ?>
    </div>

</div>