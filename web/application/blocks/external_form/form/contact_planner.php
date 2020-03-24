<?
use Concrete\Package\EcRecaptcha\Src\Captcha\RecaptchaController;

$form = Loader::helper('form');
defined('C5_EXECUTE') or die("Access Denied.");
if (isset($response)) { ?>
    <div class="alert alert-info"><?=$response?></div>
<? } ?>

<section class="form-rfp">
    <form method="post" action="<?=$view->action('process_navis');?>" id="contact-planner-form" data-parsley-validate>
        
        <p><?=$message?></p>
        <fieldset>
            <legend><?=t('Contact Information');?></legend>
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
                <label for="phone"><span class="alert">*</span><?=t('Best Phone #:');?></label>
                <?=$form->telephone('phone', array('maxlength' => '20','required' => 'required','data-parsley-trigger'=>'change'));?>
            </div>
            <div class="column small-12 medium-6 field">
                <label for="promo"><?=t('PROMO CODE:');?></label>
                <?=$form->text('promo', array('maxlength' => '100'));?>
            </div>
            <!--  Desired Date -->
            <div class="column small-12 medium-6 field navis-cal">
                <label for="arrival" class="ie-icon-required"><span class="alert">*</span>Arrival:</label>
                <input type="text" name="arrive" id="arrival" maxlength="10" class="required date-picker textfield navis-date-begin hasDatepicker" placeholder="" value="" required="required" data-parsley-trigger="change"/>
                <img id="navis-button-arrive" class="ui-datepicker-trigger date-picker" src="/application/themes/theme_palmetto/images/calendar.svg" alt="mm/dd/yyyy" title="mm/dd/yyyy" width="28" height="26" />
            </div>
            <div class="column small-12 medium-6 field navis-cal">
                <label for="departure" class="ie-icon-required"><span class="alert">*</span>Departure:</label>
                <input type="text" name="depart" id="departure" maxlength="10" class="required date-picker textfield navis-date-end hasDatepicker" placeholder="" value="" required="required" data-parsley-trigger="change"/>
                <img id="navis-button-depart" class="ui-datepicker-trigger date-picker" src="/application/themes/theme_palmetto/images/calendar.svg" alt="mm/dd/yyyy" title="mm/dd/yyyy" width="28" height="26" />
            </div>
            <div class="column small-12 field">
                <label for="num_adults"><?=t('Number of Adults:');?></label>
                <?=$form->number('num_adults', array('max' => '10', 'min'=>'0'));?>
            </div>
            <div class="column small-12 field">
                <label for="num_children"><?=t('Number of Children:');?></label>
                <?=$form->number('num_children', array('max' => '10', 'min'=>'0'));?>
            </div>
            <div class="column small-12 field">
                <label for="num_bedrooms"><?=t('Number of Bedrooms:');?></label>
                <?=$form->number('num_bedrooms', array('max' => '10', 'min'=>'0'));?>
            </div>
            <div class="column small-12 medium-6 field">
                <label for="accom_type"><?=t('Accommodation Type:');?></label>
                <select name="accom_type" id="accom-type">
                    <option value="">-- Please Select --</option>             
                    <option value="home">Home</option>
                    <option value="villa">Villa</option>
                </select>
            </div>
                <div class="column small-12 medium-6 field sel-home-view">
                    <label for="view_type"><?=t('Home View:');?></label>
                    <select name="view_type" id="home-view">
                        <option value="" selected="selected">-- Please Select --</option>             
                        <option value="10889">Any View</option>
                        <option value="11043">Courtyard View</option>
                        <option value="10893">Lagoon View</option>
                        <option value="10895">Near Ocean</option>
                        <option value="10898">Pool View</option>
                    </select>
                </div>
                <div class="column small-12 medium-6 field sel-villa-view">
                    <label for="villa_view"><?=t('Villa View:');?></label>
                    <select name="villa_view" id="villa-view">
                        <option value="NoAnswer">-- Please Select --</option>
                        <option value="10805" selected="selected">Any View</option>
                        <option value="10814">Courtyard View</option>
                        <option value="10815">Golf Course View</option>
                        <option value="10816">Lagoon View</option>
                        <option value="11042">Marina View</option>
                        <option value="10818">Near Ocean</option>
                        <option value="10819">Ocean View</option>
                        <option value="10820">Oceanfront</option>
                        <option value="10821">Pool View</option>
                        <option value="10822">Resort View</option>
                    </select>
                </div>

            <div class="column small-12 medium-6 field">
                <label for="other_activities"><?=t('Other activities/services:');?></label>
                <div class="small-12 label-switch golf-toggle">
                    <input type="checkbox" name="golf" value="golf" id="golf" />
                    <label for="golf">Golf</label>
                </div>
                <div class="column small-12 medium-6 field num-players" style="display:none;">
                    <label for="num_players"><span class="alert">*</span><?=t('Approximate Number of Players:');?></label>
                    <select name="num_players" id="num-players">
                        <option value="">-- Please Select --</option>             
                        <option value="play1">Fewer than 9 Players</option>
                        <option value="play2">9-24 Players</option>
                        <option value="play3">25-48 Players</option>
                        <option value="play4">49+ Players</option>
                    </select>
                </div>
                <div class="column small-12 medium-6 field less-players">
                    <label for="less_players"><span class="alert">*</span><?=t('Number of Players:');?></label>
                    <?=$form->number('less_players', array('max' => '9', 'min'=>'0'));?>
                </div>
                <div class="small-12 label-switch">
                    <input type="checkbox" name="meals" value="meals" id="meals" />
                    <label for="meals">Meals</label>
                </div>
                <div class="small-12 label-switch">
                    <input type="checkbox" name="tennis" value="tennis" id="tennis" />
                    <label for="tennis">Tennis</label>
                </div>
                <div class="small-12 label-switch">
                    <input type="checkbox" name="canoes_kayaking" value="canoes_kayaking" id="canoes_kayaking" />
                    <label for="canoes_kayaking">Canoes &amp; Kayaking</label>
                </div>
                <div class="small-12 label-switch">
                    <input type="checkbox" name="boat_rentals" value="boat_rentals" id="boat_rentals" />
                    <label for="boat_rentals">Boat Rentals &amp; Fishing</label>
                </div>
                <div class="small-12 label-switch">
                    <input type="checkbox" name="kids_activities" value="kids_activities" id="kids_activities" />
                    <label for="kids_activities">Kids Activities &amp; Camps</label>
                </div>
                <div class="small-12 label-switch last">
                    <input type="checkbox" name="event_occasion" value="event_occasion" id="event_occasion" />
                    <label for="event_occasion">Is this for a special event/occasion?</label>
                </div>
            </div>
            <div class="column small-12 field">
                <label for="event_details"><?=t('Additional Details:');?></label>
                <?=$form->textarea('event_details', array('rows' => '10'));?>
            </div>
            <div class="column small-12 label-switch field">
                <input type="checkbox" name="yes_email" value="yes_email" id="yes_email" />
                <label for="yes_email">Check here to receive exclusive email savings!</label>
            </div>
            <div class="column small-12 field">
                <?php RecaptchaController::showInputV3(); ?>
            </div>

            <div class="column small-12 label-switch field">
                <div class="sg-button-bar">
                    <input type="hidden" name="keyword" id="hidNavisKeyword" />
                    <input type="submit" class="sg-button sg-next-button" name="submit" value="Get Quote" />
                </div>
            </div>
        </fieldset>
    </form>
</section>
<script language="javascript" type="text/javascript">NavisSetHiddenKeywordFieldD("hidNavisKeyword", "Email Reach");</script>
<script>
    $(function() {
        $("input[type='checkbox']#golf").click(function(){
            if(this.checked){
                $('.num-players, .less-players').show();
                $('.num-players select, input#less_players').attr('required', 'required');
            } else {
                $('.num-players, .less-players').hide();
                $('.num-players select, input#less_players').removeAttr('required', 'required');
            }
        });

        $('#num-players').change(function() {
            if($(this).val() == 'play1'){ 
                $('input#less_players').attr({
                    "max" : 8,
                    "min" : 1
                });
            } else if($(this).val() == 'play2'){ 
                $('input#less_players').attr({
                    "max" : 24,
                    "min" : 9
                });
            } else if($(this).val() == 'play3'){ 
                $('input#less_players').attr({
                    "max" : 48,
                    "min" : 25
                });
            } else if($(this).val() == 'play4'){ 
                $('input#less_players').attr({
                    "max" : 100,
                    "min" : 49
                });
            } else {
                $('input#less_players').attr({
                    "max" : 100,
                    "min" : 0
                });
            }
        });

        $('#accom-type').change(function() {
            if($(this).val() == 'home'){ 
                $('.sel-home-view').show();
                $('.sel-villa-view').hide();
            } else if ($(this).val() == 'villa') { 
                $('.sel-villa-view').show();
                $('.sel-home-view').hide();
            } else if ($(this).val() == '') { 
                $('.sel-villa-view, .sel-home-view').hide();
            }

            $("#home-view").val($("#home-view option:first").val());
            $("#villa-view").val($("#villa-view option:first").val());
        });

        var $contact = $('#contact-planner-form');
        var parsleyForm = $contact.parsley();

        $contact.submit(function(e){
            if (parsleyForm.isValid()) {
                $("input[type='submit']", this)
                    .val("Processing...")
                    .attr('disabled', 'disabled');
                return true;
            }
            e.preventDefault();
        });

        var nextDay;

        var navis_button_arrive = $('#navis-button-arrive'), navisArrive = $('.navis-date-begin').pickadate({
                format: 'mm/dd/yyyy',
                formatSubmit: 'mm-dd-yyyy',
                min: true,
                today: 'Today',
                clear: 'Close' //hijacking clear to close and not clear -- see line 150 of picker.js
            }),

            navis_button_depart = $('#navis-button-depart'), navisDepart = $('.navis-date-end').pickadate({
                format: 'mm/dd/yyyy',
                formatSubmit: 'mm/dd/yyyy',
                today: '',
                clear: 'Clear' //hijacking clear to close and not clear -- see line 150 of picker.js
            });

        var navis_arrive_picker = navisArrive.pickadate('picker'),
            navis_depart_picker = navisDepart.pickadate('picker');      
            
        if(navis_arrive_picker && navis_depart_picker) {
            if ( navis_arrive_picker.get('value') ) {
                navis_depart_picker.set('min', navis_arrive_picker.get('select'));
            }

            // When something is selected, update the “from” and “to” limits.
            navis_arrive_picker.on('set', function(event) {
                if ( event.select ) {
                    navis_depart_picker.set('min', navis_arrive_picker.get('select'));
                }
            });

            navis_depart_picker.on('set', function(event) {
                if ( event.select ) {
                    navis_arrive_picker.set('max', navis_depart_picker.get('select'));
                }
            });

            navis_arrive_picker.on('click', function(e) {
                e.preventDefault();
                navis_arrive_picker.open();
                e.stopPropagation();
            });

            navis_depart_picker.on('click', function(e) {
                e.preventDefault();
                navis_depart_picker.open();
                e.stopPropagation();
            });

            navis_button_arrive.on( 'click', function(event) {
                //console.log('golf calendar clicked');
                if (navis_arrive_picker.get('open')) {
                    navis_arrive_picker.close()
                }
                else {
                    navis_arrive_picker.open()
                }
                event.stopPropagation()
            });

            navis_button_depart.on( 'click', function(event) {
                if (navis_depart_picker.get('open')) {
                    navis_depart_picker.close()
                }
                else {
                    navis_depart_picker.open()
                }
                event.stopPropagation()
            });
        }
    });
</script>
