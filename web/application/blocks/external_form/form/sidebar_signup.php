<?
use Concrete\Package\EcRecaptcha\Src\Captcha\RecaptchaController;

$form = Loader::helper('form');
defined('C5_EXECUTE') or die("Access Denied.");
if (isset($response)) { ?>
	<div class="alert alert-info"><?=$response?></div>
<? } ?>

<section class="form-rfp">
    <div class="offers-signup">
        <div class="signup-wrap">
            <div class="sb-text">
                <h2>Receive INSTANT special offer &amp; more! </h2>
            </div>
            <form method="post" action="<?=$view->action('process_navis');?>" id="sidebar-signup-form" data-parsley-validate>
                <fieldset>
                    <div class="column small-12 field">
                        <label for="email"><span class="alert">*</span><?=t('Email Address:');?></label>
                        <?=$form->email('email', array('required' => 'required','data-parsley-trigger'=>'change', 'maxlength' => '100', 'placeholder' => 'Email'));?>
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
        </div>
    </div>
</section>
 
<script>
    $(function() {
        var $sidebarSignUp = $('#sidebar-signup-form');
        var parsleyForm = $sidebarSignUp.parsley();

        $sidebarSignUp.submit(function(e){
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
