<?
use Concrete\Package\EcRecaptcha\Src\Captcha\RecaptchaController;

$form = Loader::helper('form');
defined('C5_EXECUTE') or die("Access Denied.");
if (isset($response)) { ?>
    <div class="alert alert-info"><?=$response?></div>
<? } ?>

<section class="form-rfp">
    <form method="post" action="<?=$view->action('process_navis');?>" id="tennis-quote-form" data-parsley-validate>
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
                <label for="phone"><span class="alert">*</span><?=t('Phone:');?></label>
                <?=$form->telephone('phone', array('maxlength' => '20','required' => 'required','data-parsley-trigger'=>'change'));?>
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
            <div class="column small-12 medium-6 field">
                <label for="promo"><?=t('PROMO CODE:');?></label>
                <?=$form->text('promo', array('maxlength' => '100'));?>
            </div>

            <div class="column small-12 field">
                <label for="num_children"><?=t('Number of Youth (age 17 or younger):');?></label>
                <?=$form->number('num_children', array('max' => '100', 'min'=>'0'));?>
            </div>
            <div class="column small-12 field">
                <label for="num_adults"><?=t('Number of Adults (age 18 or older):');?></label>
                <?=$form->number('num_adults', array('max' => '100', 'min'=>'0'));?>
            </div>

            <div class="column small-12 medium-6 field">
                <label for="player_level"><?=t('Level of Player(s):');?></label>
                <select name="player_level">
                    <option value="">-- Please Select --</option>
                    <option value="level1">Beginner</option>
                    <option value="level2">Intermediate</option>
                    <option value="level3">Advanced</option>
                </select>
            </div>
            <div class="column small-12 medium-6 field">
                <label for="instruct_type"><?=t('Type of Instruction:');?></label>
                <select name="instruct_type">
                    <option value="">-- Please Select --</option>
                    <option value="instruct1">Customized Group Clinic</option>
                    <option value="instruct2">Scheduled Clinics</option>
                    <option value="instruct3">Private Lessons</option>
                </select>
            </div>

            <div class="column small-12 medium-6 field">
                <label for="accom_type"><?=t('Accommodation type:');?></label>
                <select name="accom_type" id="accom-type">
                    <option value="">-- Please Select --</option>
                    <option value="home">Home</option>
                    <option value="villa">Villa</option>
                </select>
            </div>

            <div class="column small-12 medium-6 field sel-num-rooms">
                <label for="num_rooms"><?=t('Number of Bedrooms:');?></label>
                <select name="num_rooms" id="num-rooms">
                    <option value="" selected="selected">-- Please Select --</option>
                    <option value="">Any View</option>
                    <option value="rooms1">1</option>
                    <option value="rooms2">2</option>
                    <option value="rooms3">3</option>
                    <option value="rooms4">4</option>
                    <option value="rooms5">5</option>
                    <option value="rooms6">6</option>
                    <option value="rooms7">7</option>
                    <option value="rooms8">8</option>
                    <option value="rooms9">9</option>
                    <option value="rooms10">10</option>
                </select>
            </div>
            <div class="column small-12 medium-6 field sel-home-view">
                <label for="home_view"><?=t('Home View:');?></label>
                <select name="home_view" id="home-view">
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
                <label for="other_activities"><?=t('Additional Activities/Services:');?></label>
                <div class="small-12 label-switch golf-toggle">
                    <input type="checkbox" name="golf" value="golf" id="golf" />
                    <label for="golf">Golf</label>
                </div>
                <div class="small-12 label-switch">
                    <input type="checkbox" name="canoes_kayaking" value="canoes_kayaking" id="canoes_kayaking" />
                    <label for="canoes_kayaking">Canoes &amp; Kayaking</label>
                </div>
                <div class="small-12 label-switch">
                    <input type="checkbox" name="bike_rental" value="bike_rental" id="bike_rental" />
                    <label for="bike_rental">Bike Rental</label>
                </div>
                <div class="small-12 label-switch">
                    <input type="checkbox" name="boat_rentals" value="boat_rentals" id="boat_rentals" />
                    <label for="boat_rentals">Boat Rentals &amp; Fishing</label>
                </div>
                <div class="small-12 label-switch">
                    <input type="checkbox" name="kids_activities" value="kids_activities" id="kids_activities" />
                    <label for="kids_activities">Kids Activities &amp; Camps</label>
                </div>
            </div>
            <div class="column small-12 field">
                <label for="event_details"><?=t('Special Requests');?></label>
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

        $('#accom-type').change(function() {
            if($(this).val() == 'home'){
                $('.sel-home-view').show();
                $('.sel-num-rooms').show();
                $('.sel-villa-view').hide();
            } else if ($(this).val() == 'villa') {
                $('.sel-villa-view').show();
                $('.sel-num-rooms').show();
                $('.sel-home-view').hide();
            } else if ($(this).val() == '') {
                $('.sel-villa-view, .sel-home-view, .sel-num-rooms').hide();
            }

            $("#num-rooms").val($("#num-rooms option:first").val());
            $("#home-view").val($("#home-view option:first").val());
            $("#villa-view").val($("#villa-view option:first").val());
        });

        var $contact = $('#tennis-quote-form');
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