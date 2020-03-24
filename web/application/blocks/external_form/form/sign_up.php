<?
use Concrete\Package\EcRecaptcha\Src\Captcha\RecaptchaController;

$form = Loader::helper('form');
defined('C5_EXECUTE') or die("Access Denied.");
if (isset($response)) { ?>
	<div class="alert alert-info"><?=$response?></div>
<? } ?>

<?php
    $c = \Page::getCurrentPage();
    $th = Loader::helper('text');
    $pageName = $th->sanitizeFileSystem($c->getCollectionName(), $leaveSlashes=false);
?>

<section class="form-rfp">
    <form method="post" action="<?=$view->action('process_navis');?>" id="sign-up-form" data-parsley-validate>
        <p><?=$message?></p>
        <fieldset>
            <div class="column small-12 medium-4 field">
                <label for="first_name" style="display: block; text-align: left;"><?=t('First Name');?><span class="alert">*</span>:</label>
                <?=$form->text('first_name', array('required' => 'required','data-parsley-trigger'=>'change', 'maxlength' => '100'));?>
            </div>
            <div class="column small-12 medium-4 field">
                <label for="last_name"  style="display: block; text-align: left;"><?=t('Last Name:');?><span class="alert">*</span>:</label>
                <?=$form->text('last_name', array('required' => 'required','data-parsley-trigger'=>'change', 'maxlength' => '100'));?>
            </div>
            <div class="column small-12 medium-6 field">
                <label  for="promo"   style="display: block; text-align: left;"><?=t('Zip Code');?><span class="alert">*</span>:</label>
                <?=$form->text('postal_code', array('required' => 'required','data-parsley-trigger'=>'change', 'maxlength' => '5'));?>
            </div>
            <div class="column small-12 field">
                <label for="email"  style="display: block; text-align: left;"><?=t('Email Address');?><span class="alert">*</span>:</label>
                <?=$form->email('email', array('required' => 'required','data-parsley-trigger'=>'change', 'maxlength' => '100'));?>
            </div>

            <div class="column small-12 field">
                <?php RecaptchaController::showInputV3(); ?>
            </div>

            <div class="column small-12 field last">
                <div class="sg-button-bar">
                    <input type="submit" class="sg-button sg-next-button" name="submit" value="Sign Up" />
                </div>
            </div>
        </fieldset>
    <?php
if ( $pageName === 'hilton-head-outfitters-adventure-club-signup' ) { ?>
        <input type="hidden" name="adventure" value="1" />
    <?php } ?>
    </form>
</section>

<script>
    $(function() {
        var $signUpForm = $('#sign-up-form');
        var parsleyForm = $signUpForm.parsley();

        $signUpForm.submit(function(e){
            if (parsleyForm.isValid()) {
                $("input[type='submit']", this)
                    .val("Processing...")
                    .attr('disabled', 'disabled');
                return true;
            }
            e.preventDefault();
        });
    });
</script>

