<xsl:template name="map">
<!-- MSN Map -->
<div id="map-lightbox">
	<a href="#" id="map-close"><img src="/templates/desktablet/images/layout/close-x.png" alt="" border="0" /></a>
	<div id="map-container">
		<h2>Interactive Map</h2>
		<div id="places-wrapper">
			<h3>Locations</h3>
		</div>
		<div id="route">
			<h3>Route Planner</h3>
			<ul id="route-list">
			</ul>
			<a href="#" id="get-route">Plan Route</a>
			<a href="#" id="clear-route">Clear Route</a>
		</div>
		<div id="map-wrapper">
			<div id="msnmap" class="map"></div>
			<div id="custom-locations">
				<form action="" class="validate inline" id="add-custom">
					<fieldset>
						<legend>Find Driving Directions &#160;|&#160; <a href="#" id="cstm-reset">Reset</a></legend>
						<!--<legend>Find Driving Directions</legend> use for simple map - comment out and uncomment legend above for category map -->
						<div class="field">
							<label for="cstm-name">Name</label>
							<input type="text" name="cstm-name" id="cstm-name" class="textfield required" />
						</div>
						<div class="field">
							<label for="cstm-address">Address</label>
							<input type="text" name="cstm-address" id="cstm-address" class="textfield required" />
						</div>
						<div class="buttons">
							<a href="#" id="cstm-submit" class="submit">Add Location</a>
						</div>
						<!--<div class="buttons"> use for simple map - comment out and uncomment div above for category map
						<a href="#" id="cstm-submit" class="submit">Get Directions</a> | <a href="#" id="cstm-reverse">Reverse Trip</a> | <a href="#" id="cstm-reset">Reset Trip</a>
						</div>-->
					</fieldset>
				</form>
			</div>
		</div>
		<div id="context-menu">
			<div id="context-title"></div>
			<ul>
				<li id="context-add"> <a href="#">Add To Route Planner"</a>
				<div>
					<form action="" class="validate inline" id="context-add-form">
						<label for="name">Point Name</label>
						<input type="text" size="15" class="required" name="name" id="name" />
						<input type="submit" name="add" id="add" class="submit" value="Add" />
					</form>
				</div>
			</li>
			<li>
				<hr />
			</li>
			<li><a href="#" class="context-zoom" rel="11">Zoom to City Level</a></li>
			<li><a href="#" class="context-zoom" rel="16">Zoom to Street Level</a></li>
			<li><a href="#" class="context-center">Center Map</a></li>
		</ul>
	</div>
</div>
</div>
</xsl:template>