<?php
$form = Loader::helper('form');
defined('C5_EXECUTE') or die("Access Denied.");
if (isset($response)) { ?>
    <div class="alert alert-info"><?=$response?></div>
<? } ?>

<section class="form-rfp">
    <form method="post" action="<?=$view->action('process_navis');?>" id="contact-planner-form" data-parsley-validate>
        
        <p><?=$message?></p>
        <fieldset>
            <legend><?=t('Contest Entry');?></legend>
            <p>Fields marked <em>(<span class="alert">*</span>) <?=t('are required.');?></em></p>
            
            <div class="column small-12 medium-6 field">
                <label for="first_name"><span class="alert">*</span><?=t('First Name:');?></label>
                <?=$form->text('first_name', array('required' => 'required','data-parsley-trigger'=>'change', 'maxlength' => '100'));?>
            </div>
            <div class="column small-12 medium-6 field">
                <label for="last_name"><span class="alert">*</span><?=t('Last Name:');?></label>
                <?=$form->text('last_name', array('required' => 'required','data-parsley-trigger'=>'change', 'maxlength' => '100'));?>
            </div>
            <div class="column small-12 medium-6 field">
                <label for="email"><span class="alert">*</span><?=t('Email Address:');?></label>
                <?=$form->email('email', array('required' => 'required','data-parsley-trigger'=>'change', 'maxlength' => '100'));?>
            </div>

            <div class="column small-12 medium-6 field age">
                <label for="age"><span class="alert">*</span><?=t('Age:');?></label>
                <select name="Age" id="age">
                    <option value="">-- Please Select --</option>             
                    <option value="age18-24">18-24</option>
                    <option value="age25-34">25-34</option>
                    <option value="age35-44">35-44</option>
                    <option value="age45-54">45-54</option>
                    <option value="age55-64">55-64</option>
                    <option value="age65-74">65-74</option>
                    <option value="age75+">Over 75</option>
                </select>
            </div>

            <div class="column small-12 medium-6 field">
                <label for="state"><span class="alert">*</span><?=t('State of Residence:');?></label>
                <select class="state" name="state" id="state">
                    <option value="">-- Please Select --</option>
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AB">Alberta</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="BC">British Columbia</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MB">Manitoba</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NL">Newfoundland</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="NT">Northwest Territories</option>
                    <option value="NS">Nova Scotia</option>
                    <option value="NU">Nunavut</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="ON">Ontario</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="PE">Prince Edward Island</option>
                    <option value="PR">Puerto Rico</option>
                    <option value="QC">Quebec</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SK">Saskatchewan</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                    <option value="YT">Yukon</option>
                </select>
            </div>            

            <div class="column small-12 medium-6 field">
                <label for="getaway"><span class="alert">*</span><?=t('My ideal Palmetto Dunes getaway:');?></label>
                <select name="ContestIdealGetaway" id="getaway">
                    <option value="">-- Please Select --</option>             
                    <option value="Romance">Romance</option>
                    <option value="Family">Family</option>
                    <option value="Sport">Sport</option>
                </select>
            </div>

            <div class="column small-12 field">
                <label for="subscribe" style="color:#60973c;font-weight:700 !important;"><span class="alert">*</span><?=t('Receive instant special offers &amp; more when you subscribe:');?></label>
                <div class="12 label-switch">
                    <div class="radio">
                        <input name="subscribe" id="subscribe_yes" type="radio" value="Yes" required="required" data-parsley-multiple="subscribe" style="width:20px; margin-left: 45px;">
                        <label for="subscribe_yes">Yes</label>
                    </div>
                    <div class="radio">
                        <input name="subscribe" id="subscribe_no" type="radio" value="No" data-parsley-multiple="subscribe" style="width:20px; margin-left: 45px;">
                        <label for="subscribe_no">No</label>
                    </div>
                </div>
            </div>

            <div class="column small-12 label-switch field">
                <div class="sg-button-bar">
                    <input type="submit" class="sg-button sg-next-button" name="submit" value="Submit" />
                </div>
            </div>
        </fieldset>
    </form>
</section>

