<xsl:template name="promo-guestcontact">
	<div class="guest-contact">
		<div class="book-section-heading">
			<div class="cart-heading">Provide Your Stay Details</div>
		</div>
                <form action="/booking/wholesaleprocess" method="post" id="guest-info" name="guest-info" class="">
            <input type="hidden" id="arrive" name="arrive" value="{//module/bookingcart/data/booking/@arrive}" />
            <input type="hidden" id="depart" name="depart" value="{//module/bookingcart/data/booking/@depart}" />
            <input type="hidden" id="propertyid" name="propertyid" value="{//module/bookingcart/data/booking/@propertyid}" />
            <input type="hidden" id="email_confirm" name="email_confirm" value="notrequired" />
			<div class="book-block">
				<div class="block-heading">Guest Information</div>
				<div class="book-block-content">
					<div class="field-wrap">
						<div class="field">
							<label for="firstname">First Name:</label>
							<input type="text" name="firstname" id="firstname" maxlength="100" class="textfield required" value="{//module/bookingcart/data/formdata/firstname}" />
						</div>
						<div class="field">
							<label for="lastname">Last Name:</label>
							<input type="text" name="lastname" id="lastname" maxlength="100" class="textfield required" value="{//module/bookingcart/data/formdata/lastname}" />
						</div>
					</div>
					<div class="h-line"></div>
					<div>Number of Guests:</div>
					<div class="field-select">
		                <div class="field">
		                    <label for="adults">Adults</label>
		                    <select id="adults" name="adults" class="required {/output/module/bookingcart/data/formdata/adults}">
		                    	<option value="">Please Select</option>
		                    	<option value="1">1</option>
		                        <option value="2">2</option>
		                        <option value="3">3</option>
		                        <option value="4">4</option>
		                        <option value="5">5</option>
		                        <option value="6">6</option>
		                        <option value="7">7</option>
		                        <option value="8">8</option>
		                        <option value="9">9</option>
		                        <option value="10">10</option>
		                        <option value="11">11</option>
		                        <option value="12">12</option>
		                    </select>
		                </div>
		                <div class="field">
		                    <label for="children">Children</label>
		                    <select id="children" name="children" class="{/output/module/bookingcart/data/formdata/children}">
		                    	<option value="">Please Select</option>
								<option value="0">0</option>
		                    	<option value="1">1</option>
		                        <option value="2">2</option>
		                        <option value="3">3</option>
		                        <option value="4">4</option>
		                        <option value="5">5</option>
		                        <option value="6">6</option>
		                        <option value="7">7</option>
		                        <option value="8">8</option>
		                        <option value="9">9</option>
		                        <option value="10">10</option>
		                    </select>
		                </div>
					</div>
					<div class="h-line"></div>
					<div>Number of Car Passes Needed:</div>
	                <div class="field field-passes">
	                    <select id="carpasses" name="carpasses" class="{/output/module/bookingcart/data/formdata/carpasses}">
	                    	<option value="1">1</option>
	                        <option value="2">2</option>
	                        <option value="3">3</option>
	                        <option value="4">4</option>
	                    </select>
	                </div>
                    <div class="carpasses-copy">
                         <xsl:value-of select="//module/carpassdescr/data/content" />
<!--
                    	<p>Palmetto Dunes Property Owners Association requires a $12 per-car, per-week ($15 within 7 days) Parking Pass fee for all vacation home and villa rentals. “Purchase Now to Save” verbiage goes here.</p>
                    	<p>The Parking Pass Fee will be added to your pricing details within the next step, once you click the “Proceed to Payment” button below.</p>
-->
                    </div>
				</div>
			</div>

            <div class="book-block book-request">
				<div class="block-heading">Reservationist</div>
				<div class="book-block-content">
					<div class="field-wrap">
                    	<div class="field">
                       		<label for="email">Email Address:</label>
                     		<input type="text" name="email" id="email" maxlength="100" class="large email textfield required" value="{//module/bookingcart/data/formdata/email}" />
                    	</div>
                  		<div class="field">
                   			<label for="phone">Primary Phone Number:</label>
                      		<input type="text" name="phone" id="phone" maxlength="15" class="medium textfield required" value="{//module/bookingcart/data/formdata/phone}" />
                   		</div>
                	</div>
                </div>

                <div class="h-line h-line-wide"></div>
				<div class="book-block-content">
                    <div class="cancellation-policy">
                        <h3>Cancellation Policy</h3>
                        <p>Full Payment Due 14 Days prior to Arrival.</p>
                        <p>The reservation is non refundable at 14  days prior to arrival.</p>
                        <p>Villa is subject to change based upon availability.</p>
                        <input value="1" type="checkbox" class="required" name="cancellationpolicy" id="cancellationpolicy" /> <label for="cancellationpolicy">Please check the box to indicate you have read and understand the cancellation policy.</label>
                    </div>
                    <!-- <div class="cancellation-policy">
                        <input value="1" type="checkbox" class="required" name="rentalagreement" id="rentalagreement" /> <label for="rentalagreement">Please check the box to indicate you have read and accept the Guest Vacation <a href="/templates/email/rental-agreement.html" data-fancybox-type="iframe" class="fancybox">Rental Agreement</a>.</label>
                    </div> -->
            	</div>
            </div>

			<div class="book-block book-request">
				<div class="block-heading">Golf Tee Time Request</div>
				<div class="book-block-content">
					<xsl:choose>
						<xsl:when test="/output/module/bookingcart/data/booking/requests!=''">
							<textarea id="requests" name="requests"><xsl:value-of select="/output/module/bookingcart/data/booking/requests"/></textarea>
						</xsl:when>
						<xsl:otherwise>
							<textarea id="requests" name="requests" placeholder="Tee Times Request"></textarea>
                            <span>Golf Tee Time Confirmation will be sent separately</span>
						</xsl:otherwise>
					</xsl:choose>
				</div>
			</div>

			<input type="submit" id="book-submit" class="unit-book orange-btn guest-info-book" value="proceed to checkout"/>

		</form>
	</div>
</xsl:template>

<xsl:template name="promo-guestinfo">
	<div class="guest-output">
		<div class="book-block">
                    <div class="block-heading">Guest Information <a class="blue-btn" id="edit-guestinfo" href="/booking/guestinfo">Edit</a></div>
			<div class="book-block-content">
				<div class="book-guest-1">
					<div class="guest-name"><b>Name:</b>&#160;
						<xsl:value-of select="//module/bookingcart/data/user/firstname"/>&#160;
						<xsl:value-of select="//module/bookingcart/data/user/lastname"/>
					</div>
					<div class="num-guests"><b>Guests:</b>&#160;
						<xsl:value-of select="//module/bookingcart/data/booking/@adults"/>&#160;Adults,
						<xsl:value-of select="//module/bookingcart/data/booking/@children"/>&#160;Children
					</div>
					<div class="car-pass"><b>Car Pass:</b>&#160;
						<xsl:value-of select="//module/bookingcart/data/booking/@carpasses"/>
					</div>
				</div>
			</div>
		</div>
	</div>
</xsl:template>

<xsl:template name="promo-reservationist">
	<div class="guest-output">
		<div class="book-block">
			<div class="block-heading">Reservationist</div>
			<div class="book-block-content">
				<div class="field-wrap">
					<div class="book-guest-2">
                        <div class="guest-email"><b>Email Address:</b>&#160;
                        	<xsl:value-of select="//module/bookingcart/data/user/email"/>
                        </div>
                        <div class="guest-phone"><b>Primary Phone Number:</b>&#160;
                        	<xsl:value-of select="//module/bookingcart/data/user/phone"/>
                        </div>
                    </div>
				</div>
            </div>
		</div>
    </div>
</xsl:template>
