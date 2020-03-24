

<?
$form = Loader::helper('form');
defined('C5_EXECUTE') or die("Access Denied.");
if (isset($response)) { ?>
    <div class="alert alert-info"><?=$response?></div>
<? }

use Concrete\Package\EcRecaptcha\Src\Captcha\RecaptchaController; ?>

<style>
    .real-estate-form form fieldset .field label {
        display: inline-block;
        margin: 0;
        width: 20%;
        height: 36px;
        line-height: 36px;
        float: left;
        text-align: left;
        font-weight: 400;
        font-size: 15px;
        padding-right: 2%;
        color:#fff;
    }

    .real-estate-form form fieldset .field input, .real-estate-form form fieldset .field select, .real-estate-form form fieldset .field textarea {
        border: 1px solid #999999;
        border-radius: 1px;
        font-family: Verdana;
        padding: 3px 8px;
        width: 70%;
        height: 36px;
        line-height: 1.467;
        font-size: 16px;
        box-sizing: border-box;
    }

    .real-estate-form form fieldset .label-switch label {
        text-align: left;
        float: left;
        width: auto;
        width: 70% !important;
        padding-left: 1%;
    }
    .real-estate-form form fieldset .label-switch {
        margin-left: 7%;
        /*width: 65%;*/
        display: inline-block;
    }
    .real-estate-form-header {color: #fff;
        line-height: 22px;
        margin: 0 0 10px 0;
        font-family: "Cabin", Arial, sans-serif;
        font-size: 18px;
        font-weight: 700;}

    .real-estate-form{
        background: #407cad;
        border: 1px solid #a0bbd1;
        outline: 2px solid #407cad;
        color: white;
        padding: 30px 25px;
        margin: 0 auto;
        width: 50%;  }

    .form-rfp form fieldset .sg-button-bar {
        margin-left: 0;
        padding: 3px 8px;
        padding-left: 26%;
    }
    @media only screen and (max-width: 1235px){
        .real-estate-form{ width: 100%;  }
    }

    @media only screen and (max-width: 764px){
        .real-estate-form-header {

            margin: 0 10% 10px 0;

        }
        .form-rfp form fieldset .field input, .form-rfp form fieldset .field select, .form-rfp form fieldset .field textarea {
            width: 90%;
            margin-right: 10%;
        }
        #yes_email{ margin-right: 0%;}
        .form-rfp form fieldset .label-switch input {

            margin-left: 0px;
        }
        .real-estate-form form fieldset .label-switch {
            margin-left:0%;

        }

        .form-rfp form fieldset .sg-button-bar {

            margin-top: 50px;
        }

        .real-estate-form form fieldset .field label {

            width: 60%;

        }

    }
    .signup-content {

        border-top: none;

    }

    .real-estate-form form fieldset .label-switch label {
        font-size: 13px;
        width: 80% !important;

    }

    .sg-button, .sg-button:hover{

        -moz-box-shadow: none;
        -webkit-box-shadow: none;
        box-shadow: none;

    }




</style>


<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false&amp;libraries=places"></script>
<script>

    function postal_code() {
        var input = document.getElementById('field-postal');
        var options = {
            types: ['(regions)'],
            componentRestrictions: {
                country: "usa"
            }
        }
        var autocomplete = new google.maps.places.Autocomplete(input, options);
    }

    google.maps.event.addDomListener(window, 'load', postal_code);
</script>


<section class="form-rfp real-estate-form">
    <form method="post" action="<?=$view->action('process_navis');?>" id="real_estate_form" data-parsley-validate>

        <p><?=$message?></p>
        <fieldset>
            <legend><?=t('Contact Information');?></legend>
            <h3 class="real-estate-form-header">Need to contact us? We look forward for helping you.</h3><br/>
            <p>Fields marked <em>(<span class="alert">*</span>) <?=t('are required.');?></em></p>

            <div class="column small-12 medium-4 field">
                <label for="first_name"><span class="alert">*</span><?=t('First Name:');?></label>
                <?=$form->text('first_name', array('required' => 'required','data-parsley-trigger'=>'change', 'maxlength' => '100'));?>
            </div>
            <div class="column small-12 medium-4 field">
                <label for="last_name"><span class="alert">*</span><?=t('Last Name:');?></label>
                <?=$form->text('last_name', array('required' => 'required','data-parsley-trigger'=>'change', 'maxlength' => '100'));?>
            </div>

            <div class="column small-12 field">
                <label for="email"><span class="alert">*</span><?=t('Email Address:');?></label>
                <?=$form->email('email', array('required' => 'required','data-parsley-trigger'=>'change', 'maxlength' => '100'));?>
            </div>
            <div class="column small-12 medium-6 field">
                <label for="phone"> <?=t('Phone #:');?></label>
                <?=$form->telephone('phone', array('maxlength' => '20',' ' => ' ','data-parsley-trigger'=>'change'));?>
            </div>
            <div class="column small-12 medium-6 field">
                <label  for="promo"  ><?=t('Zipcode:');?></label>
                <input type="text" id="field-postal" name="field-postal" />

            </div>


            <div class="column small-12 label-switch field">
                <input type="checkbox" name="yes_email" value="yes_email" id="yes_email" />
                <label for="yes_email">Opt-in for Hilton Head Real Estate Info</label>
            </div>
            <div class="column small-12 field">
                <?php RecaptchaController::showInputV3(); ?>
            </div>

            <div class="column small-12 field last">
                <div class="sg-button-bar">
                    <input type="submit" class="sg-button sg-next-button" name="submit" value="Submit" />
                </div>
            </div>



        </fieldset>
    </form>
</section>

<script>
    $(function() {
        var $sidebarSignUp = $('#real_estate_form');
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



 