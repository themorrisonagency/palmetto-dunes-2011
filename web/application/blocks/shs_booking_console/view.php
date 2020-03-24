<?php     defined('C5_EXECUTE') or die(_("Access Denied."));
$html = Loader::helper('html');
Loader::model('attribute/categories/collection');
$c = Page::getCurrentPage();
?>

<!-- BEGIN BOOKING CONSOLE MODULE -->
<div class="book-wrapper closed">
	<div class="book-box">

      <h2>Check Availability</h2>
        <div class="check-avail-wrapper">
            <div class="booking-tab-wrapper">
                <div class="lava"></div>
                <span class="tab-item tab-vacation"><a href="#vacation">Vacation Rentals</a></span><!--
                --><span class="tab-item tab-golf"><a href="#golf">Tee Times</a></span><!--
                --><span class="tab-item tab-bike"><a href="#bikes">Bike Rentals &amp; More</a></span><!--
                --><span class="tab-item tab-fareharbor"><a href="#fareharbor">Fishing &amp; Water Sports</a></span><!--
            --></div>
            <div class="booking-console">
                <div class="booking-console-inner">
                    <div id="vacation" class="tab-content content-vacation">
                      	<div class="content-title vacation">Vacation Rentals <span class="toggle"></span></div>
                      	<div class="cont-wrap">
                        	<form name="reservations:console" id="reservations-console" action="https://rentals.palmettodunes.com/vacation-rentals/" class="track validate inline console reservations-console auto-set-dates console-tent">
                          		<h3 class="header-reservations">Vacation Rentals</h3>
                          		<fieldset>
                            		<div class="field">
                              			<label for="arrival" class="ie-icon-required">Arrival:</label>
                              			<input type="text" name="arrive" id="arrival" maxlength="10" class="date-picker textfield date-begin hasDatepicker required" placeholder="" value="" />
                                		<img id="button-arrive" class="ui-datepicker-trigger date-picker" src="/application/themes/theme_palmetto/images/calendar.svg" alt="mm/dd/yyyy" title="mm/dd/yyyy" width="28" height="26" />
                            		</div>
                            		<div class="field">
                              			<label for="departure" class="ie-icon-required">Departure:</label>
                              			<input type="text" name="depart" id="departure" maxlength="10" class="date-picker textfield date-end hasDatepicker required" placeholder="" value="" />
                              			<img id="button-depart" class="ui-datepicker-trigger date-picker" src="/application/themes/theme_palmetto/images/calendar.svg" alt="mm/dd/yyyy" title="mm/dd/yyyy" width="28" height="26" />
                            		</div>
                                  	<div class="dropdown">
                                      <div class="field">
                                          <label for="view">View:</label>
                                          <select id="view" name="viewid" class='view-all'>
                                            <option value="">Any</option>
                                            <option value="Courtyard View">Courtyard View</option>
                                            <option value="Golf Course View">Golf Course View</option>
                                            <option value="Lagoon View<">Lagoon View</option>
                                            <option value="Marina View">Marina View</option>
                                            <option value="Near Ocean">Near Ocean</option>
                                            <option value="Ocean Front">Ocean Front</option>
                                            <option value="Ocean View">Ocean View</option>
                                            <option value="Pool View">Pool View</option>
                                            <option value="Resort View">Resort View</option>
                                          </select>
                                          <select id="view" class='view-homes'>
                                            <option value="">Any</option>
                                            <option value="Courtyard View">Courtyard View</option>
                                            <option value="Golf Course View">Golf Course View</option>
                                            <option value="Lagoon View">Lagoon View</option>
                                            <option value="Near Ocean">Near Ocean</option>
                                            <option value="Pool View">Pool View</option>
                                            <option value="Resort View">Resort View</option>
                                          </select>
                                      </div>

                                      <div class="field field-promo">
                                          <label for="promo-code">Promo Code:</label>
                                          <input type="text" name="promocode" id="promo-code" placeholder="" value="" class="medium textfield" />
                                      </div>
                                    </div>
                                    <div class="cf">
                                      <div class="field">
                                          <label for="location">Location:</label>
                                          <div class="dropdown-all">
                                            <select id="location" name="locationid" class="location-all">
                                              <option value="">Any</option>
                                              <option value="Anchorage">Anchorage</option>
                                              <option value="Armada">Armada</option>
                                              <option value="Barrington Arms">Barrington Arms</option>
                                              <option value="Barrington Court">Barrington Court</option>
                                              <option value="Barrington Park">Barrington Park</option>
                                              <option value="Beach Villas">Beach Villas</option>
                                              <option value="Captains Cove">Captains Cove</option>
                                              <option value="Captains Walk">Captains Walk</option>
                                              <option value="Cartgate">Cartgate</option>
                                              <option value="Centre Court Villas">Centre Court Villas</option>
                                              <option value="">Cockle Court</option>
                                               <option value="Down Wind">Down Wind</option>
                                                <option value="Fazio Villas">Fazio Villas</option>
                                                <option value="Flagg">Flagg</option>
                                                <option value="Full Sweep">Full Sweep</option>
                                                <option value="Hampton Place Villas">Hampton Place Villas</option>
                                                <option value="Harbourside">Harbourside</option>
                                              <option value="Heath Drive">Heath Drive</option>
                                              <option value="Hickory Cove">Hickory Cove</option>
                                              <option value="Huntington Villas">Huntington Villas</option>
                                              <option value="Inverness Village">Inverness Village</option>
                                              <option value="Mainsail">Mainsail</option>
                                              <option value="Midstream">Midstream</option>
                                              <!--<option value="87">Midstream</option>-->
                                              <option value="">Mooring Buoy</option>
                                              <option value="">Night Harbour</option>
                                              <option value="Ocean Cove">Ocean Cove</option>
                                              <option value="">Off Shore</option>
                                              <option value="">Port Tack</option>
											                        <option value="Promontory">Promontory</option>
                                              <option value="Queens Grant">Queens Grant</option>
                                             <option value="Rum Row">Rum Row</option>
                                              <!-- <option value="Shelley Court">Shelley Court</option> -->
                                              <option value="Slack Tide">Slack Tide</option>
                                              <option value="St. Andrews Commons">St. Andrews Commons</option>
                                              <option value="Starboard Tack">Starboard Tack</option>
                                              <option value="Sutherland Court">Sutherland Court</option>
                                              <!-- <option value="99">The Mooring Villas</option> -->
                                              <option value="The Moorings">The Moorings</option>
                                                <option value="Tradewinds Trace">Tradewinds Trace</option>
                                              <option value="Troon">Troon</option>
                                              <option value="Turnberry Village">Turnberry Village</option>
                                              <option value="Villamare">Villamare</option>
                                                <option value="Water Oak">Water Oak</option>
                                                <option value="Wendover Dunes">Wendover Dunes</option>
                                                <option value="Windsor Court">Windsor Court</option>
                                                <option value="Windsor Place">Windsor Place</option>
											                        <option value="Yacht Club">Yacht Club</option>
                                            </select>
                                          </div>

                                          <div class="dropdown-villas">
                                            <select id="location" class="location-villas">
                                                <option value="" selected="selected">Any Location</option>
                                                <option value="Anchorage">Anchorage</option>
                                                <option value="Armada">Armada</option>
                                                <option value="Barrington Arms">Barrington Arms</option>
                                                <option value="Barrington Court">Barrington Court</option>
                                                <option value="Barrington Park">Barrington Park</option>
                                                <option value="Beach Villas">Beach Villas</option>
                                                <option value="Captains Cove">Captains Cove</option>
                                                <option value="Captains Walk">Captains Walk</option>
                                                <option value="Centre Court Villas">Centre Court Villas</option>
                                                <option value="Down Wind">Down Wind</option>
                                                <option value="Fazio Villas">Fazio Villas</option>
                                                <option value="Hampton Place Villas">Hampton Place Villas</option>
                                                <option value="Harbourside">Harbourside</option>
                                                <option value="Hickory Cove">Hickory Cove</option>
                                                <option value="Huntington Villas">Huntington Villas</option>
                                                <option value="Inverness Village">Inverness Village</option>
                                                <option value="Mainsail">Mainsail</option>
                                                <option value="Ocean Cove">Ocean Cove</option>
                                                <option value="Queens Grant">Queens Grant</option>
                                                <option value="St. Andrews Commons">St. Andrews Commons</option>
                                                <option value="The Moorings">The Moorings</option>
                                                <option value="Tradewinds Trace">Tradewinds Trace</option>
                                                <option value="Turnberry Village">Turnberry Village</option>
                                                <option value="Villamare">Villamare</option>
                                                <option value="Water Oak">Water Oak</option>
                                                <option value="Wendover Dunes">Wendover Dunes</option>
                                                <option value="Windsor Court">Windsor Court</option>
                                                <option value="Windsor Place">Windsor Place</option>
                                            </select>
                                          </div>

                                          <div class="dropdown-homes">
                                            <select id="location" class="location-homes">
                                                <option value="">Any</option>
                                                <option value="Cartgate">Cartgate</option>
                                                <option value="Flagg">Flagg</option>
                                                <option value="Full Sweep">Full Sweep</option>
                                                <option value="Heath">Heath</option>
                                                <!--<option value="87">Midstream</option>-->
                                                <option value="Mooring Buoy">Mooring Buoy</option>
                                                <option value="Night Harbour">Night Harbour</option>
                                                <option value="Off Shore">Off Shore</option>
                                                <option value="Port Tack">Port Tack</option>
                                                <option value="Rum Row">Rum Row</option>
                                                <option value="Slack Tide">Slack Tide</option>
                                                <option value="Starboard Tack">Starboard Tack</option>
                                                <option value="Troon">Troon</option>
                                            </select>
                                          </div>
                                        </div>
                                    </div>
                                  	<div class="promo-flyout">
                                    	<label for="promo" class="promo-code"><a id="promo-trigger" href="#promo-code">More Filters</a></label>
                                    	<div class="flyout-wrapper">
                                      		<div class="field">
                                        		<label for="sleeps">Sleeps:</label>
                                        		<select id="sleeps" name="sleeps">
                                          			<option value="">Any</option>
                                          			<option value="1">1+</option>
                                          			<option value="2">2+</option>
                                          			<option value="3">3+</option>
                                          			<option value="4">4+</option>
                                          			<option value="5">5+</option>
                                          			<option value="6">6+</option>
                                          			<option value="7">7+</option>
                                          			<option value="8">8+</option>
                                          			<option value="9">9+</option>
                                          			<option value="10">10+</option>
                                          			<option value="11">11+</option>
                                          			<option value="12">12+</option>
                                        		</select>
                                      		</div>

                                      		<div class="field field-bedrooms">
                                        		<label for="bedrooms">Bedrooms:</label>
                                        		<select id="bedrooms" name="bedrooms">
                                          			<option value="">Any</option>
                                          			<option value="1">1</option>
                                          			<option value="2">2</option>
                                          			<option value="3">3</option>
                                          			<option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                        		</select>
                                      		</div>

                                      		<div class="field">
                                        		<label for="type">Type:</label>
                                        		<select id="type" name="typeid">
                                          			<option value="">Any</option>
                                          			<option name="villas" value="Villa">Villas</option>
                                          			<option name="homes" value="Home">Homes</option>
                                        		</select>
                                      		</div>
                                    	</div>
                                  	</div>
                                  	<div class="buttons">
                                    	<input type="submit" class="submit btn btn-primary orange-btn" value="Check Availability" />
                                      <script>
                                        $('.console-tent').submit(function(e) { 
                                          e.preventDefault();
                                          let newLocation = '';
                                          let tentURL = 'https://rentals.palmettodunes.com/vacation-rentals/#';

                                          let typeVal = $('select[name="typeid"]').val();
                                          if(typeVal) {
                                            let tP1 = '{!tag=RiotSolrWidget,RiotSolrFacetList-ss_field_type$name}ss_field_type$name:"'+typeVal+'"';
                                            tentURL += 'fq='+encodeURIComponent(tP1);
                                          }

                                          let viewVal = $('select[name="viewid"]').val();
                                          if(viewVal) {
                                            let tP2 = '{!tag=RiotSolrWidget,RiotSolrFacetList-sm_nid$rc_core_term_views$name}sm_nid$rc_core_term_views$name:"'+viewVal+'"';
                                            tentURL += '&fq='+encodeURIComponent(tP2);
                                          }

                                          let locationVal = $('[name="locationid"]').val();
                                          if(locationVal) {
                                            let tP3  = '{!tag=RiotSolrWidget,RiotSolrFacetList-sm_nid$rc_core_term_location$name}sm_nid$rc_core_term_location$name:'+locationVal+'';
                                            tentURL += '&fq='+encodeURIComponent(tP3);
                                          }

                                          let guestsVal = $('[name="sleeps"]').val();
                                          if(guestsVal) {
                                            let tP4  = '{!tag=RiotSolrWidget,is_rc_core_lodging_product$occ_total}is_rc_core_lodging_product$occ_total:['+guestsVal+ ' TO 14]';
                                            tentURL += '&fq='+encodeURIComponent(tP4);
                                          } 

                                          let bedroomsVal = $('[name="bedrooms"]').val();
                                          if(bedroomsVal) {
                                            let tP5  = '{!tag=RiotSolrWidget,fs_rc_core_lodging_product$beds}fs_rc_core_lodging_product$beds:'+bedroomsVal+'.0';
                                            tentURL += '&fq='+encodeURIComponent(tP5);
                                          } 

                                          let beginDate = '';
                                          beginDate = $('input[name="arrive_submit"]').val();

                                          let endDate = '';
                                          endDate = $('input[name="depart_submit"]').val();

                                          let promoParam = '';
                                          let promoCode = $('input[name="promocode"]').val();
                                          if(promoCode)
                                            promoParam = ', "coupon":"' + promoCode + '"';

                                          let tentParamsRCAG = '{"rcav":{"begin":"' + beginDate + '","end":"' + endDate + '"' + promoParam + '}}';
                                          tentParamsRCAG = '&rcav='+encodeURIComponent(tentParamsRCAG);
                                          tentURL += tentParamsRCAG;
                                          //console.log(tentURL);
                                          window.location = tentURL;
                                        });
                                      </script>
                                  	</div>
                                </fieldset>
                            </form>
                            <div class="vacation-benefits rental-info">
                                <?php echo $vacationRentalsTab; ?>
                            </div>
                        </div>
                    </div>

                    <div id="golf" class="tab-content content-golf">
                    	<div class="content-title golf">Tee Times <span class="toggle"></span></div>
                      	<div class="cont-wrap">
                        	<form name="reservations:console" id="reservations-console-golf" action="https://bookit.activegolf.com/book-palmetto-dunes-public-tee-times/69" class="track validate inline console reservations-console auto-set-dates" target="_blank">
                          		<input type="hidden" name="c" value="7734af81af83b113" />
                          		<h3 class="header-reservations">Tee Times</h3>
                          		<fieldset>
                            		<div class="field dropdown">
                              			<label for="bc-select-course">Select a Course:</label>
                              			<select id="bc-select-course" name="CourseId" style="width: 160px !important;">
                                			<option name="Robert Trent Jones" value="91">Robert Trent Jones</option>

                                			<option name="George Fazio" value="89"<?php if ($c->getCollectionID() == '187') echo " selected" ?>>George Fazio</option>
                                			<option name="Arthur Hills" value="88"<?php if ($c->getCollectionID() == '192') echo " selected" ?>>Arthur Hills</option>
                              			</select>
                            		</div>
                            		<div class="field">
                              			<label for="bc-arrival-golf" class="ie-icon-required">Date of Play:</label>
                              			<input type="text" name="Date" id="bc-arrival-golf" maxlength="10" class="date-picker textfield required golf-date-begin hasDatepicker" placeholder="" value="" />
                              			<img id="golf-button-arrive" class="ui-datepicker-trigger date-picker" src="/application/themes/theme_palmetto/images/calendar.svg" alt="mm/dd/yyyy" title="mm/dd/yyyy" width="28" height="26" />
                            		</div>
                            		<div class="field">
                              			<label for="bc-promo-golf">Promo Code:</label>
                              			<input type="text" name="promo" id="bc-promo-golf" placeholder="" value="" class="medium textfield" />
                            		</div>
                                <div class="field dropdown">
                                  <label for="bc-players-golf">Players:</label>
								                  <select name="players" id="bc-players-golf">
						                        <option value="1">1</option>
				                            <option value="2" selected="selected">2</option>
						                        <option value="3">3</option>
						                        <option value="4">4</option>
                                  </select>
                                </div>
                            		<div class="buttons">
                              			<input type="submit" class="submit btn btn-primary orange-btn" value="Check Tee Times" />
                            		</div>

                            		<div class="mobile-buttons">
                              			<a href="http://palmettodunes.mteetimes.com/?clubId=104" class="btn btn-primary orange-btn" target="_blank">Check Tee Times</a>
                            		</div>
                          		</fieldset>
                        	</form>
                        	<div class="golf-benefits rental-info">
                          		<?php echo $teeTimesTab; ?>
                        	</div>
                      	</div>
                    </div>
                    <div id="bikes" class="tab-content content-bike">
                      	<div class="content-title bikes">Bike Rentals &amp; More <span class="toggle"></span></div>
                      	<div class="cont-wrap">
                        	<form name="reservations:console" id="reservations-console-bikes" class="track validate inline console reservations-console auto-set-dates" target="_blank">
                          		<h3 class="header-reservations">Bike/Beach Rentals &amp; Lagoon Activities</h3>
                          		<fieldset>
                            		<div class="field dropdown">
                              			<label for="category" class="ie-icon-required">Rental Type:</label>
                              			<select id="category" name="category">
                                			<option name="Bikes" value="bikes">Bikes</option>
                                			<option name="Kayaks Lagoon" value="kayaks-lagoon">Kayaks – Lagoon</option>
                                      <option name="Canoes Lagoon" value="canoes-lagoon">Canoes – Lagoon</option>
                                      <option name="Kayaks Ocean" value="kayaks–ocean">Kayaks – Ocean</option>
                                			<!--<option name="Paddleboards Ocean" value="paddleboards–ocean">Paddleboards – Ocean</option>-->
                                      <option name="Surfboards" value="surfboards">Surfboards</option>
                                      <option name="Beach Chairs/Umbrellas" value="beach-chair-umbrellas">Beach Chairs/Umbrellas</option>
                                			<option name="Jogging Strollers" value="jogging-strollers">Jogging Strollers</option>
                                			<option name="Fishing Equipment" value="fishing-equipment">Fishing Equipment</option>
                              			</select>
                            		</div>
                            		<div class="field dropdown">
                              			<label for="daysBikes">Rental Length Type:</label>
                              			<select id="daysBikes" name="period">
                                      <!-- <option name="Choose Type" value="" selected="selected">Choose Type</option> -->
                                      <option name="Half Day" value="halfday">Half Day</option>
                                      <option name="Full Day" value="fullday">Full Day</option>
                                      <option name="3-Day" value="threeday">3-Day</option>
                                      <option name="4-Day" value="fourday">4-Day</option>
                                      <option name="5-Day" value="fiveday">5-Day</option>
                                      <option name="6-Day" value="sixday">6-Day</option>
                                      <option name="7-Day" value="sevenday" selected="selected">7-Day</option>
                                      <option name="8-Day" value="eightday">8-Day </option>
                                      <option name="9-Day" value="nineday">9 Day</option>
                                      <option name="10-Day" value="tenday">10 Day</option>
                                      <option name="11-Day" value="elevenday">11 Day</option>
                                      <option name="12-Day" value="twelveday">12 Day</option>
                                      <option name="13-Day" value="thirteenday">13 Day</option>
                                      <option name="2-weeks" value="twoweeks">2 Weeks</option>
                                      <option name="1 Month" value="month">1 Month</option>

                                      <option name="1 Hour" value="onehour">1 Hour</option>
                                      <option name="1.5 Hour" value="oneandhalfhour">1.5 Hour</option>
                                      <option name="2 Hour" value="twohour">2 Hour</option>
                                      <option name="3 Hour" value="threehour">3 Hour</option>
                                      <option name="Full Day" value="secondfullday">Full Day</option>
                                      <option name="2 Day" value="secondtwoday">2 Day</option>
                                      <option name="3 Day" value="secondthreeday">3 Day</option>
                                      <option name="1 week" value="week">1 week</option>

                                      <option name="Half Day - 1 chair/1 umbrella" value="halfday1chair">Half Day - 1 chair/1 umbrella</option>
                                      <option name="Full Day - 1 chair/1 umbrella" value="fullday1chair">Full Day - 1 chair/1 umbrella</option>
                                      <option name="1-week - 1 chair/1 umbrella" value="week1chair">1-week - 1 chair/1 umbrella</option>
                                      <option name="Half Day - 2 chair/1 umbrella" value="halfday2chair">Half Day - 2 chair/1 umbrella</option>
                                      <option name="Full Day - 2 chair/1 umbrella" value="fullday2chair">Full Day - 2 chair/1 umbrella</option>
                                      <option name="1-week - 2 chair/1 umbrella" value="week2chair">1-week - 2 chair/1 umbrella</option>

                                      <option name="Half Day- Single" value="halfdaysingle">Half Day- Single</option>
                                      <option name="Full Day - Single" value="fulldaysingle">Full Day - Single</option>
                                      <option name="3-Day - Single" value="threedaysingle">3-Day - Single</option>
                                      <option name="1-Week - Single" value="weeksingle">1-Week - Single</option>
                                      <option name="Half Day - Double" value="halfdaydouble">Half Day - Double</option>
                                      <option name="Full Day - Double" value="fulldaydouble">Full Day - Double</option>
                                      <option name="3-Day - Double" value="threedaydouble">3-Day - Double</option>
                                      <option name="1-Week - Double" value="weekdouble">1-Week - Double</option>

                                      <option name="Half Day - Light Tackle" value="halfdaylight">Half Day - Light Tackle</option>
                                      <option name="Full Day - Light Tackle" value="fulldaylight">Full Day - Light Tackle</option>
                                      <option name="1-Week - Light Tackle" value="weeklight">1-Week - Light Tackle</option>
                                      <option name="Half Day - Surf Rod" value="halfdaysurf">Half Day - Surf Rod</option>
                                      <option name="Full Day - Surf Rod" value="fulldaysurf">Full Day - Surf Rod</option>
                                      <option name="1-Week - Surf Rod" value="weeksurf">1-Week - Surf Rod</option>
                              			</select>
                            		</div>
                            		<div class="buttons">
                              			<a href="" class="submit btn btn-primary orange-btn">Search</a>
                            		</div>
                          		</fieldset>
                        	</form>
                        	<div class="bike-benefits rental-info">
                          		<?php echo $bikeRentalsTab; ?>
                        	</div>
                      	</div>
                    </div>

                    <div id="fareharbor" class="tab-content content-fareharbor">
                      <div class="content-title fareharbor">Fishing &amp; Water Sports <span class="toggle"></span></div>
                      <div class="cont-wrap">
                        <form name="reservations:console" id="reservations-console-fareharbor" class="track validate inline console reservations-console auto-set-dates">
                            <h3 class="header-reservations">Marina Fishing &amp; Water Sports</h3>
                            <fieldset>
                              <div class="row">
                                <div class="columns small-12 medium-6">
                                  <div class="field dropdown">
                                    <label for="activity-type" class="ie-icon-required">Type:</label>
                                   <select name="activity-type" id="activity-type" class="required ie-icon-required">
                                    <option value="" selected="selected">Choose Type</option>
                                    <option value="water-sports">Water Sports</option>
                                    <option value="fishing">Fishing</option>
                                    <option value="dolphin-cruises">Dolphin Tours</option>
                                       <option value="nature-and-adventure-cruises">Nature & Adventure Cruises</option>
                                    <option value="sailing">Sailing</option>
                                    <option value="fireworks-cruises">Fireworks Cruises</option>
                                   </select>
                                  </div>
                                </div>
                                <div class="columns small-12 medium-6">
                                  <div class="field dropdown">
                                    <label for="activity" class="ie-icon-required">Activity:</label>
                                    <select name="activity" id="activity" class="required ie-icon-required">
                                     <option value="" selected="selected">Choose Activity</option>
                                      <option value="backwatercatadventure">Two-person Catamaran Tours</option>
                                      <option value="fireworkscruisecapthook">Fireworks Cruise Capt Hook</option>
                                      <option value="july4thfireworkscapthook">July 4th Fireworks Capt Hook</option>

                                        <option value="fireworkscruisecraigcat">Fireworks Cruise Craigcat</option>
                                        <option value="fireworkssailsaltmarsh">Fireworks Sail Salt Marsh</option>
                                        <option value="fireworkssailflyingcircus">Fireworks Sail Flying Circus</option>
                                        <option value="fireworkspadle">Fireworks Paddle</option>


                                      <option value="fireworkscruiseseafari">Fireworks Cruise Seafari</option>
                                      <option value="hiltonheadoutfitters">Surf Lessons</option>
                                      <option value="kayaksmarina">Kayaks – Marina</option>
                                      <option value="paddleboardsmarina">Paddleboards – Marina</option>
                                      <option value="boatrentalmarina">Boat Rental – Marina</option>
                                      <option value="captmarksdolphincruises">Dolphin Cruises Holiday</option>
                                      <option value="dolphinseafari">Dolphin Cruises Seafari</option>
                                        <option value="outsidehhfishing">Outside Hilton Head Fishing</option>
                                       <option value="partyfishingboat">Party Fishing Boat</option>
                                       <option value="inshorefishonfishing">Inshore Fish-On Fishing</option>
                                      <!-- <option value="saltysanta">Offshore Black Skimmer Fishing</option>-->
                                       <option value="kayakcanoeslagoon">Kayak/Canoes - Lagoon</option>
                                      <option value="dolphinsailingcruiseflyingcircus">Dolphin Sailing Cruise Flying Circus</option>
                                        <option value="dolphinecotour">Dolphin Eco Tour</option>
                                        <option value="slatmarshdolphinsail">Salt Marsh Dolphin Sail</option>

                                        <option value="sportcrabbingcrabberj">Sport Crabbing Crabber J</option>
                                        <option value="daufuskieislandtour">Daufuskie Island Tour</option>
                                        <option value="beachcombingcruise">Beachcombing Cruise</option>

                                      <option value="piratekidzofhiltonhead">Shannon Tanner Pirate Cruise</option>
                                      <option value="fireworks-cruises">Fireworks Cruise Holiday</option>
                                      <option value="bayrunnercharters">Inshore Bayrunner Fishing</option>
                                      <option value="palmettolagooncharters">Inshore Maverick Fishing</option>
                                      <option value="mightymako">Inshore Mighty Mako Fishing</option>
                                      <option value="hiltonheadislandcharterfishing">Offshore Gullah Gal Fishing</option>
                                      <option value="palmettolagoonfishing">Lagoon Fishing</option>
                                      <option value="hiltonheadislandsailing">Sailing Flying Circus Charter</option>
                                      <option value="hiltonheadsailingcharters">Sailing Special K Charter</option>
                                        <option value="saltmarshsailing">Salt Marsh Sailing</option>

                                    </select>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="columns small-12 medium-6">
                                  <div class="field dropdown">
                                    <label for="num-people"># of people:</label>
                                    <select name="num-people" id="num-people">
                                      <option value="Up to 4">Up to 4</option>
                                      <option value="Up to 6">Up to 6</option>
                                      <option value="Up to 12">Up to 12</option>
                                       <option value="Up to 20">Up to 20</option>
                                      <option value="Up to 40">Up to 40</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="columns small-12 medium-6">
                                  <div class="buttons">
                                      <a href="#" class="submit btn btn-primary orange-btn">Search</a>
                                  </div>
                                </div>
                              </div>
                            </fieldset>
                        </form>
                        <div class="fareharbor-benefits rental-info">
                            <?php echo $fareharborTab; ?>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END BOOKING CONSOLE MODULE -->
