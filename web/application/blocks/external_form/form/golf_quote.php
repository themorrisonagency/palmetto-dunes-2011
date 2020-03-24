<?
use Concrete\Package\EcRecaptcha\Src\Captcha\RecaptchaController;

$form = Loader::helper('form');
defined('C5_EXECUTE') or die("Access Denied.");
if (isset($response)) { ?>
    <div class="alert alert-info"><?=$response?></div>
<? } ?>

<section class="form-rfp">
    <form method="post" action="<?=$view->action('process_navis');?>" id="golf-quote-form" data-parsley-validate>
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
            <div class="column small-12 medium-6 field num-players">
                <label for="num_players"><span class="alert">*</span><?=t('Approximate Number of Players:');?></label>
                <select name="num_players" id="num-players" required="required">
                    <option value="">-- Please Select --</option>             
                    <option value="play1">Fewer than 9 Players</option>
                    <option value="play2">9-24 Players</option>
                    <option value="play3">25-48 Players</option>
                    <option value="play4">49+ Players</option>
                </select>
            </div>
            <div class="column small-12 medium-6 field less-players">
                <label for="less_players"><span class="alert">*</span><?=t('Number of Players:');?></label>
                <?=$form->number('less_players', array('max' => '9', 'min'=>'0', 'required' => 'required'));?>
            </div>

            <div class="column small-12 field">
                <label for="num_rounds"><span class="alert">*</span><?=t('Number of Golf Rounds:');?></label>
                <?=$form->number('num_rounds', array('max' => '72', 'min'=>'0', 'required' => 'required'));?>
            </div>

            <div class="column small-12 medium-6 field">
                <div class="small-12 label-switch rental-toggle">
                    <input type="checkbox" name="home_villa" value="home_villa" id="home_villa" />
                    <label for="home_villa"><?=t('Home or Villa Rentals');?></label>
                </div>
                
                <div class="field view-type" style="display:none;">
                    <label for="view_type"><?=t('View:');?></label>
                    <select name="view_type">
                        <option value="">-- Please Select --</option>
                        <option value="view1">Any View</option>
                        <option value="view2">Coastal Waterway View</option>
                        <option value="view3">Courtyard View</option>
                        <option value="view4">Golf Course View</option>
                        <option value="view5">Lagoon View</option>
                        <option value="view6">Marina View</option>
                        <option value="view7">Near Ocean</option>
                        <option value="view8">Ocean View</option>
                        <option value="view9">Oceanfront</option>
                        <option value="view10">Pool View</option>
                        <option value="view11">Resort View</option>
                    </select>
                </div>
                <div class="field num-bedrooms" style="display:none;">
                    <label for="num_bedrooms"><?=t('Number of Bedrooms:');?></label>
                    <select name="num_bedrooms">
                        <option value="">-- Please Select --</option>             
                        <option value="bedrooms1">1</option>
                        <option value="bedrooms2">2</option>
                        <option value="bedrooms3">3</option>
                        <option value="bedrooms4">4</option>
                        <option value="bedrooms5">5</option>
                        <option value="bedrooms6">6</option>
                        <option value="bedrooms7">7</option>
                        <option value="bedrooms8">8</option>
                        <option value="bedrooms9">9</option>
                        <option value="bedrooms10">10</option>
                    </select>
                </div>
            
                <div class="small-12 label-switch">
                    <input type="checkbox" name="meals" value="meals" id="meals" />
                    <label for="meals">Meals</label>
                </div>
                <div class="small-12 label-switch">
                    <input type="checkbox" name="golf_lessons" value="golf_lessons" id="golf_lessons" />
                    <label for="golf_lessons">Golf Lessons/Instruction</label>
                </div>
            </div>
            <div class="column small-12 field">
                <label for="event_details"><?=t('Additional Details:');?></label>
                <?=$form->textarea('event_details', array('rows' => '10'));?>
            </div>
            <div class="column small-12 label-switch field email-check">
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
    <script language="javascript" type="text/javascript">NavisSetHiddenKeywordFieldD("hidNavisKeyword", "Email Reach");</script>
    
    <div class="this-box">
        <div class="this-inner-box">
            <p align="center" style="color:#407cad;">
                Get a quicker quote by searching tee times online.
                <br>
                <br>
                <a href="https://bookit.activegolf.com/book-palmetto-dunes-public-tee-times/69?utm_source=RFPform&amp;utm_medium=form&amp;utm_campaign=newpop-up" style="color:#407cad;">Click here</a> to start your search.
            </p>
            <div class="close-inner-box"><span>No Thank You</span></div>
        </div>
    </div>
</section>

<script>
    $(function() {
        $("input[type='checkbox']#home_villa").click(function(){
            if(this.checked){
                $('.view-type, .num-bedrooms').toggle();
            } else {
                $('.view-type, .num-bedrooms').toggle();
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

        var $contact = $('#golf-quote-form');
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
                    // Grab the selected Arrival Date
                    var selectedDate = navis_arrive_picker.get('select');
                    // And today's date
                    var now = new Date();
                    // But reset the hours so we compare full days
                    now = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                    
                    // Test the selected Arrival Date to see if it falls
                    // within the next 72 hours
                    if (selectedDate.obj === now || ((selectedDate.obj - now)/(1000 * 60 * 60 * 24)) < 3) {
                        //console.log('Display the lightbox');
                        $('.this-box').show();
                    }

                    // Set the Min Date of the Departure picker
                    navis_depart_picker.set('min', selectedDate);
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

        $('.close-inner-box').click(function() {
            $('.this-box').hide();
            //$('.navis-cal input').val("");
        });

        $('.this-inner-box').css({
            'position' : 'absolute',
            'left' : '50%',
            'top' : '50%',
            'margin-left' : -$('.this-inner-box').outerWidth()/2,
            'margin-top' : -$('.this-inner-box').outerHeight()/2
        });

    });
</script>
