<?
use Concrete\Package\EcRecaptcha\Src\Captcha\RecaptchaController;

$form = Loader::helper('form');
defined('C5_EXECUTE') or die("Access Denied.");
if (isset($response)) { ?>
	<div class="alert alert-info"><?=$response?></div>
<? } ?>

<section class="form-rfp">
    <div class="signup-inner">
        <div class="signup-wrap">
            <div class="sb-text">
                <span class="email-icon"><img src="https://surveygizmolibrary.s3.amazonaws.com/library/28013/signupicon_03.gif" alt=""/></span>
                <span id="special-text1" class="special-text1">Receive INSTANT special offer &amp; more! </span>
            </div>
            <form method="post" action="<?=$view->action('process_navis');?>" id="signup-form" data-parsley-validate>
                <p><?=$message?></p>
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
                </fieldset>
            </form>
        </div>
    </div>
</section>

<script>
    $(function() {
        var $blueSignUpForm = $('#signup-form');
        var parsleyForm = $blueSignUpForm.parsley();

        $blueSignUpForm.submit(function(e){
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