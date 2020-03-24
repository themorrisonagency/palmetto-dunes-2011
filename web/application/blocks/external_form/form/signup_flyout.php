<?
use Concrete\Package\EcRecaptcha\Src\Captcha\RecaptchaController;

$form = Loader::helper('form');
defined('C5_EXECUTE') or die("Access Denied.");
if (isset($response)) { ?>
	<div class="alert alert-info"><?=$response?></div>
<? } ?>

<?php
    $c = \Page::getCurrentPage();
?>
<? if (!$c->getAttribute('hide_signup')) { ?>
<div id="sg-signup-widget" class="sg-signup-widget">
    <div id="signup-tab" class="signup-tab destroy-signup">
        <a href="#"><span>Sign Up Offer</span></a>
    </div>
    <div id="signup-widget" class="signup-widget">
        <div class="top">
            <div class="close"><a href="#"><em class="alt">Close</em></a></div>
        </div>

        <div class="signup">
            <div class="sb-text">
                <span id="special-text1" class="special-text1">Secret Sales </span><span id="special-text2" class="special-text2">are only announced to our subscribers!</span>
            </div>
            <div class="masthead-signup">
                <form method="post" action="<?=$view->action('process_navis');?>" id="flyout-form">
                    <?php if($message) {?><p><?=$message?></p><?php }?>
                    <fieldset>
                        <div class="column small-12 field">
                            <label for="email"><span class="alert">*</span><?=t('Email Address:');?></label>
                            <?=$form->email('email', array('required' => 'required','data-parsley-trigger'=>'change', 'maxlength' => '100', 'placeholder' => 'Email'));?>
                        </div>
                        <div class="column small-12 field middle">
                            <?php RecaptchaController::showInputV3(); ?>
                        </div>
                        <div class="column small-12 field last">
                            <div class="sg-button-bar">
                                <input type="submit" class="sg-button sg-next-button" name="submit" value="Sign Up" />
                            </div>
                        </div>
                        <input type="hidden" name="green" value="1" />
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<? } ?>

<script>
    $(function() {
        var $flyoutForm = $('#flyout-form');
        var parsleyForm = $flyoutForm.parsley();

        $flyoutForm.submit(function(e){
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
