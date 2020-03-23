<!-- Booking Console -->
<xsl:template name="booking-console">

<div id="check-avail-wrapper">
    <form name="reservations:console" id="reservations-console" action="https://gc.synxis.com/rez.aspx" class="validate inline console">
		<input type="hidden" name="hotel" value="00000" />
        	<input type="hidden" name="start" value="availresults" />
            <fieldset>
                <div class="field">
                    <label for="arrival">Arrival</label>
                    <input type="text" name="arrive" id="arrival" maxlength="10" class="date-picker date-begin textfield required" value="mm/dd/yyyy" />
                </div>
                <div class="field">
                    <label for="departure">Departure</label>
                    <input type="text" name="depart" id="departure" maxlength="10" class="date-picker date-end textfield required" value="mm/dd/yyyy" />
                </div>
                <div class="field">
                    <label for="nights">Nights</label>
                    <select id="nights" name="nights">
                    	<option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="field">
                    <label for="adults">Adults</label>
                    <select id="adults" name="adult">
                    	<option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="field">
                    <label for="child">Kids</label>
                    <select id="child" name="child">
						<option value="0">0</option>
                    	<option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="field">
                    <label for="rooms">Rooms</label>
                    <select id="rooms" name="rooms">
                    	<option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
                <div class="field">
                    <label for="group-code">Group Code</label>
                    <input type="text" name="group" id="group-code" maxlength="10" class="medium textfield" />
                </div>
                <div class="field">
                    <label for="promo-code">Promo Code</label>
                    <input type="text" name="promo" id="promo-code" class="medium textfield" />
                </div>
                <div class="field">
                    <label for="iata-number">IATA Number</label>
                    <input type="text" name="iata" id="iata-number" maxlength="10" class="medium textfield" />
                </div>
                <div class="buttons">
                    <input type="submit" class="submit" value="Submit" />
                </div>
            </fieldset>
        </form>
</div>

</xsl:template>
