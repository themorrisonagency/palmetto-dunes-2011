<xsl:template name="book-header">
	<div class="book-header cf">
		<div class="book-head-1">
			<div class="unit-nav">
				<ul>
					<xsl:choose>
						<xsl:when test="//system/activeSection1/@value='userinfo'">
                            <li class="nav-tab nav-tab-search"><a href="/booking/vacation-rentals/hilton-head-rental-units/?propertyid={/output/module/bookingcart/data/property/@propertyid}&amp;arrive={/output/pageinfo/querystring/arrive}&amp;depart={/output/pageinfo/querystring/depart}&amp;typeid={/output/pageinfo/querystring/typeid}&amp;viewid={/output/pageinfo/querystring/viewid}&amp;locationid={/output/pageinfo/querystring/locationid}&amp;bedrooms={/output/pageinfo/querystring/bedrooms}&amp;sleeps={/output/pageinfo/querystring/sleeps}&amp;amenityids={/output/pageinfo/querystring/amenityids}&amp;promocode={/output/pageinfo/querystring/promocode}#rates-avail-anchor">Modify Dates</a></li>
						</xsl:when>
						<xsl:when test="//system/activeSection1/@value='paymentinfo'">
                            <li class="nav-tab nav-tab-search"><a href="/booking/guestinfo">Previous Step</a></li>
						</xsl:when>
						<xsl:otherwise></xsl:otherwise>
					</xsl:choose>
				</ul>
			</div>
			<div class="book-top-heading"><xsl:value-of select="//output/content/heading"/></div>
		</div>
		<div class="book-head-2">
			<xsl:if test="//system/activeSection1/@value='userinfo'">
				<div class="book-unit-email"><a href="#" class="unit-email white-btn">Email This Unit</a></div>
			</xsl:if>
			<div class="unit-contact">Questions? Call <script type='text/javascript'>ShowNavisNCPhoneNumber();</script></div>
		</div>
		<xsl:if test="//system/activeSection1/@value='userinfo'">
			<div class="email-unit-popup">
				<a href="#" class="close"><img src="/templates/main/images/buttons/close-grey-trans.png" alt="Close"/></a>
                <form action="/booking/guestinfo-emailunit?SHOWXML" id="email-unit" name="emailunit" method="post">
					<fieldset>
	                    <div class="field">
	                        <label for="email">Send To:</label>
	                        <input type="text" name="email" id="email" maxlength="30" class="textfield required" placeholder="Enter Your Email Address" value="" />
	                        <input type="hidden" name="propertyid" value="{/output/module/bookingcart/data/booking/@propertyid}" />
	                        <input type="hidden" name="arrive" value="{/output/module/bookingcart/data/booking/@arrive}" />
	                        <input type="hidden" name="depart" value="{/output/module/bookingcart/data/booking/@depart}" />
	                        <input type="hidden" name="promocode" value="{/output/pageinfo/querystring/promocode}" />
	                    </div>
					</fieldset>
					<fieldset>
		                <div id="recaptchaDiv">
                            <script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
		                	<div>
							    <div class="g-recaptcha" data-sitekey="6LfZknUUAAAAAHz44QIJrdKbMCxDIyv68h5Hh_1F"></div>
		                    </div>
		              	</div>
						<a href="#" onClick="document.emailunit.submit();" class="email-unit-send orange-btn">Send Email</a>
					</fieldset>
				</form>
			</div>
		</xsl:if>
	</div>
</xsl:template>

<xsl:template name="book-details-pricing">
	<div class="book-details-pricing cf">
		<div class="book-section-heading">
			<div class="cart-heading">
				<xsl:choose>
					<xsl:when test="//pageinfo/@id = 'userinfo'">Rental Unit Details &#38; Pricing</xsl:when>
					<xsl:when test="//pageinfo/@id = 'paymentinfo'">Review Reservation Details</xsl:when>
					<xsl:when test="//pageinfo/@id = 'complete'">
                    	<xsl:choose>
                        	<xsl:when test="//bookingcart/data/property/@wholesale='1'">
                            	Wholesale Reservation Confirmation
                            </xsl:when>
                            <xsl:otherwise>
                            	Reservation Confirmation
                            </xsl:otherwise>
                        </xsl:choose>
                    </xsl:when>
					<xsl:otherwise></xsl:otherwise>
				</xsl:choose>
			</div>
		</div>
		<xsl:if test="//pageinfo/@id='complete'"><xsl:call-template name="confirmation-header"/></xsl:if>
		<div class="book-unit-details">
			<div class="book-block">
				<div class="block-heading">Unit Details</div>
				<div class="book-block-content">
					<xsl:variable name="imgpath"><xsl:text>https://sabrecdn.com/pdbookingv12/images/properties/</xsl:text></xsl:variable>
					<xsl:variable name="date1" select="//module/bookingcart/data/booking/@arrive"/>
					<xsl:variable name="date1-year" select="substring-before($date1,'-')"/>
					<xsl:variable name="date1-month" select="substring-before(substring-after($date1,'-'),'-')"/>
					<xsl:variable name="date1-day" select="substring-after(substring-after($date1,'-'),'-')"/>
					<xsl:variable name="date2" select="//module/bookingcart/data/booking/@depart"/>
					<xsl:variable name="date2-year" select="substring-before($date2,'-')"/>
					<xsl:variable name="date2-month" select="substring-before(substring-after($date2,'-'),'-')"/>
					<xsl:variable name="date2-day" select="substring-after(substring-after($date2,'-'),'-')"/>
					<xsl:variable name="nights">
						<!-- calculates difference between dates -->
						<xsl:value-of select="date:difference($date1, $date2)"/>
					</xsl:variable>
					<xsl:variable name="type">
						<xsl:choose>
							<xsl:when test="//module/bookingcart/data/property/@typeid = '2'">Villa</xsl:when>
							<xsl:otherwise>Home</xsl:otherwise>
						</xsl:choose>
					</xsl:variable>
						<div class="block-inset">
							<!-- <xsl:choose>
								<xsl:when test="//module/bookingcart/data/booking"> -->
									<img src="{$imgpath}{//module/bookingcart/data/property/@propertyid}/images/{//module/bookingcart/data/property/image}" alt="" />
								<!-- </xsl:when> -->
								<!-- <xsl:otherwise>
									<img src="{$imgpath}{//module/propdetails/data/@id}/images/{//module/propdetails/data/images/record[1]/filename}" alt="" />
								</xsl:otherwise>
							</xsl:choose> -->
							<!-- <img src="{$imgpath}{//module/propdetails/data/@id}/images/{//module/propdetails/data/images/record[1]/filename}" alt="" /> -->
						</div>
						<div class="block-content">
							<div class="property-address"><xsl:value-of select="//module/bookingcart/data/property/name"/></div>
							<div class="unit-type"><b>Unit Type:</b><xsl:value-of select="$type"/></div>
							<div class="arriving"><b>Arriving:</b><xsl:value-of select="concat($date1-month,'/',$date1-day,'/',$date1-year)"/></div>
							<div class="departing"><b>Departing:</b><xsl:value-of select="concat($date2-month,'/',$date2-day,'/',$date2-year)"/></div>
							<!-- date:difference has chars 'P' and 'D' for 'Period' and 'Day' - need to strip out -->
							<!-- may need revisiting if stays longer than 1 month are involved -->
							<div class="nights"><b>Nights:</b><xsl:value-of select="substring-before(substring-after($nights,'P'),'D')"/></div>
						</div>
				</div>
			</div>
		</div>
		<div class="book-pricing-details">
			<xsl:variable name="sub-amount" select="//module/bookingcart/data/booking/rate"/>
			<xsl:variable name="subtotal">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '1']/amount &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '1']/amount"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="discount">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '2']/amount &gt; 0 or $sub-amount/record[@id = '2']/amount &lt; 0 ">
						<xsl:value-of select="$sub-amount/record[@id = '2']/amount"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="damage">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '3']/amount &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '3']/amount"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="res-fee">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '4']/amount &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '4']/amount"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="carpasses">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '5']/amount &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '5']/amount"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="assurance">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '6']/amount &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '6']/amount"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="tax">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '7']/amount &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '7']/amount"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="total"><xsl:value-of select="$subtotal + $discount + $damage + $res-fee + $carpasses + $assurance + $tax"/></xsl:variable>
			<xsl:variable name="total-verbiage">
				<xsl:choose>
					<xsl:when test="//pageinfo/@id = 'paymentinfo'">Updated Total</xsl:when>
					<xsl:when test="//pageinfo/@id = 'process'">Grand Total</xsl:when>
					<xsl:otherwise>Total</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="total-due">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '7']/amount &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '7']/amount"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>


			<xsl:variable name="due-subtotal">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '1']/duetoday &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '1']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-discount">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '2']/amount &gt; 0 or $sub-amount/record[@id = '2']/duetoday &lt; 0 ">
						<xsl:value-of select="$sub-amount/record[@id = '2']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-damage">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '3']/duetoday &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '3']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-res-fee">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '4']/duetoday &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '4']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-carpasses">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '5']/duetoday &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '5']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-assurance">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '6']/duetoday &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '6']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-tax">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '7']/duetoday &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '7']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-total">
				<xsl:choose>
					<xsl:when test="
	            (//module/bookingcart/data/promocode= 'CYBERMONDAY16')
	            or (//module/bookingcart/data/promocode= 'CYBERMONDAY16-W')
	            or (//module/bookingcart/data/promocode= 'BLACKFRIDAY16')
	            or (//module/bookingcart/data/promocode= 'BLACKFRIDAY16-W')
	            or (//module/bookingcart/data/promocode= 'BFF16')
	            or (//module/bookingcart/data/promocode= 'BFF16-W')
	            or (//module/bookingcart/data/promocode= 'BLACKFRIDAY17')
	            or (//module/bookingcart/data/promocode= 'BFF17')
	            or (//module/bookingcart/data/promocode= 'CYBERMONDAY17')">99</xsl:when>
	        <xsl:when test="(//module/bookingcart/data/promocode= '199DOWNPD')
	            or (//module/bookingcart/data/promocode= '199DOWNPD-W')
	            or (//module/bookingcart/data/promocode= 'PDSPRING')
	            or (//module/bookingcart/data/promocode= 'SPRING17')
	            or (//module/bookingcart/data/promocode= 'PD199DOWN')">199</xsl:when>
	        <xsl:when test="(//module/bookingcart/data/promocode= 'ILOVEPD17')
	        	or (//module/bookingcart/data/promocode= 'ILOVEPD17-W')">250</xsl:when>
					<xsl:otherwise>
						<xsl:value-of select="$due-subtotal + $due-discount + $due-damage + $due-res-fee + $due-carpasses + $due-assurance + $due-tax"/>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
				<xsl:variable name="blackfriday">
					<xsl:choose>
						<xsl:when test="
							(//module/bookingcart/data/booking/rateplan = 'BLACKFRIDAY15')
							or (//module/bookingcart/data/booking/rateplan = 'BFF2015')
							or (//module/bookingcart/data/booking/rateplan = 'CYBERMONDAY2015')
							or (//module/bookingcart/data/booking/rateplan = '199DOWN') 
							or (//module/bookingcart/data/booking/rateplan = '199DOWN-W')
							or (//module/bookingcart/data/booking/rateplan = '2OFFERSN1')
							or (//module/bookingcart/data/booking/rateplan = '2OFFERSN1-W')
							or (//module/bookingcart/data/booking/rateplan = '2OFFERS4YOU')
							or (//module/bookingcart/data/booking/rateplan = '2OFFERS4YOU-W')
							or (//module/bookingcart/data/booking/rateplan = 'FALL4PD')
							or (//module/bookingcart/data/booking/rateplan = 'FALL4PD-W')
							or (//module/bookingcart/data/booking/rateplan = 'IMISSYOU2')
							or (//module/bookingcart/data/booking/rateplan = 'IMISSYOU2-W')
							or (//module/bookingcart/data/booking/rateplan = 'PD199DOWN')">1</xsl:when>
						<xsl:otherwise>0</xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
			
			<div class="book-block">
				<div class="block-heading">Pricing Details<span class="info"></span></div>
				<div class="book-block-content">
                                    <div class="block-content">
						<xsl:if test="string-length($subtotal) &gt; 0">
							<div class="subtotal"><b>Subtotal:</b><xsl:value-of select="format-number($subtotal, '#,###.00')"/></div>
						</xsl:if>
						<xsl:if test="string-length($discount) &gt; 0 and $discount != '0'">
							<div class="discount"><b>Promo:</b><xsl:value-of select="format-number($discount, '#,###.00')"/></div>
						</xsl:if>
						<xsl:if test="string-length($damage) &gt; 0 and $damage != '0'">
							<div class="damage"><b>Damage Waiver:</b><xsl:value-of select="format-number($damage, '#,###.00')"/></div>
						</xsl:if>
						<xsl:if test="string-length($res-fee) &gt; 0 and $res-fee != '0'">
							<div class="res-fee"><b>Resort Fee:</b><xsl:value-of select="format-number($res-fee, '#,###.00')"/></div>
						</xsl:if>
						<xsl:if test="string-length($assurance) &gt; 0 and $assurance != '0'">
							<div class="assurance"><b>Vacation Assurance Fee:</b><xsl:value-of select="format-number($assurance, '#,###.00')"/></div>
						</xsl:if>
						<xsl:if test="string-length($carpasses) &gt; 0 and $carpasses != '0'">
							<div class="carpasses"><b>Parking Pass Fee:</b><xsl:value-of select="format-number($carpasses, '#,###.00')"/></div>
						</xsl:if>
						<xsl:if test="string-length($tax) &gt; 0 and $tax != '0'">
							<div class="tax"><b>Tax:</b><xsl:value-of select="format-number($tax, '#,###.00')"/></div>
						</xsl:if>
						<xsl:if test="string-length($total) &gt; 0 and $total != 'NaN'">
							<div class="total"><b><xsl:value-of select="$total-verbiage"/>:</b>USD&#160;<xsl:value-of select="format-number($total, '#,###.00')"/></div>
						</xsl:if>
						
						<xsl:if test="string-length($blackfriday) &gt; 0 and $blackfriday = '0'">
							<xsl:if test="string-length($due-total) &gt; 0 and $due-total != 'NaN'">
								<div class="total due-now"><b><xsl:text>Due Today</xsl:text>:</b>USD&#160;<xsl:value-of select="format-number($due-total, '#,###.00')"/></div>
							</xsl:if>
						</xsl:if>
						<!-- <xsl:for-each select="//module/bookingcart/data/booking/rate/record">
							<xsl:if test="string-length(amount) &gt; 0">
								<div><b><xsl:value-of select="name"/>:</b><xsl:value-of select="format-number(amount,'#,###.00')"/></div>
							</xsl:if>
						</xsl:for-each> -->
					</div>
				</div>
			</div>
            <div class="book-pricing-tooltip">
            	<xsl:choose>
                	<xsl:when test="//bookingcart/data/property/@wholesale='1'">
                    	<xsl:value-of select="//module/wholesalereservconf/data/content" />
                    </xsl:when>
                    <xsl:otherwise>
                    	<xsl:value-of select="//module/reservfee/data/content" />
                    </xsl:otherwise>
            	</xsl:choose>
			</div>
		</div>

	</div>
</xsl:template>

<xsl:template name="guestcontact">
	<div class="guest-contact">
		<div class="book-section-heading">
			<div class="cart-heading">Provide Your Stay Details</div>
		</div>
                <form action="/booking/payment" method="post" id="guest-info" name="guest-info" class="validate">
            <input type="hidden" id="arrive" name="arrive" value="{//module/bookingcart/data/booking/@arrive}" />
            <input type="hidden" id="depart" name="depart" value="{//module/bookingcart/data/booking/@depart}" />
            <input type="hidden" id="propertyid" name="propertyid" value="{//module/bookingcart/data/booking/@propertyid}" />
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
					<div class="field-wrap">
						<div class="field">
							<label for="phone">Primary Phone Number:</label>
							<input type="text" name="phone" id="phone" maxlength="15" class="medium textfield required" value="{//module/bookingcart/data/formdata/phone}" />
						</div>
					</div>
					<div class="field-wrap">
						<div class="field">
							<label for="email">Email Address:</label>
							<input type="text" name="email" id="email" maxlength="100" class="large email textfield required" value="{//module/bookingcart/data/formdata/email}" />
						</div>
						<div class="field">
							<label for="email_confirm" data-label="Email Address Confirmation">Email Address Confirm:</label>
							<input type="text" name="email_confirm" id="email_confirm" maxlength="100" class="large email textfield required" value="{//module/bookingcart/data/formdata/email_confirm}" />
						</div>
					</div>
					<div class="h-line"></div>
					<div>Number of Guests:</div>
					<div class="field-select">
		                <div class="field">
		                    <label for="adults" data-label="Number of Adult Guests">Adults</label>
		                    <select id="adults" name="adults" class="{/output/module/bookingcart/data/formdata/adults} required">
		                    	<option value="">Please Select</option>
		                    	<xsl:call-template name="buildOptions">
		                    		<xsl:with-param name="max" select="12" />
		                    		<xsl:with-param name="sel" select="/output/module/bookingcart/data/formdata/adults" />
		                    	</xsl:call-template>
		                    </select>
		                </div>
		                <div class="field">
		                    <label for="children" data-label="Number of Child Guests">Children</label>
		                    <select id="children" name="children" class="{/output/module/bookingcart/data/formdata/children} required">
		                    	<option value="">Please Select</option>
								<xsl:call-template name="buildOptions">
									<xsl:with-param name="count" select="0" />
		                    		<xsl:with-param name="max" select="10" />
		                    		<xsl:with-param name="sel" select="/output/module/bookingcart/data/formdata/children" />
		                    	</xsl:call-template>
		                    </select>
		                </div>
					</div>
					<div class="h-line"></div>
					<div>Number of Car Passes Needed:</div>
	                <div class="field field-passes">
	                    <select id="carpasses" name="carpasses" class="{/output/module/bookingcart/data/formdata/carpasses}">
	                    	<xsl:call-template name="buildOptions">
	                    		<xsl:with-param name="max" select="4" />
	                    		<xsl:with-param name="sel" select="/output/module/bookingcart/data/formdata/carpasses" />
	                    	</xsl:call-template>
	                    </select>
	                </div>
                    <div class="carpasses-copy">
                         <xsl:value-of select="//module/carpassdescr/data/content" />
<!--
                    	<p>Palmetto Dunes Property Owners Association requires a $12 per-car, per-week ($15 within 7 days) Parking Pass fee for all vacation home and villa rentals. “Purchase Now to Save” verbiage goes here.</p>
                    	<p>The Parking Pass Fee will be added to your pricing details within the next step, once you click the “Proceed to Payment” button below.</p>
-->
                    </div>
					<div class="h-line"></div>
					<div>Purchase Optional Vacation Assurance:</div>
					<input type="checkbox" value="1" id="vacationassurance" name="vacationassurance">
					</input>
					<div class="assurance-copy">
						<p>Yes I would like to purchase Vacation Assurance for $<xsl:value-of select="//module/bookingcart/data/property/@vacationassurance" /></p>
                         <xsl:value-of select="//module/vacationassur/data/content" />
<!--
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
-->
					</div>
				</div>
			</div>

			<div class="book-block book-request">
				<div class="block-heading">Special Requests &#8211; <span>Optional</span></div>
				<div class="book-block-content">
					<xsl:choose>
						<xsl:when test="/output/module/bookingcart/data/booking/requests!=''">
							<textarea id="requests" name="requests"><xsl:value-of select="/output/module/bookingcart/data/booking/requests"/></textarea>
						</xsl:when>
						<xsl:otherwise>
							<textarea id="requests" name="requests" placeholder="Type any special requests here. Examples: Additional Cleaning Requested, Convert twins into Swing King"></textarea>
						</xsl:otherwise>
					</xsl:choose>
				</div>
			</div>

			<input type="submit" id="book-submit" class="unit-book orange-btn guest-info-book submit" value="proceed to payment"/>
		</form>
	</div>
</xsl:template>

<xsl:template name="guestinfo">
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
				<div class="book-guest-2">
					<div class="guest-email"><xsl:value-of select="//module/bookingcart/data/user/email"/></div>
					<div class="guest-phone"><xsl:value-of select="//module/bookingcart/data/user/phone"/></div>
					<xsl:if test="not(/output/module/bookingcart/data/booking/requests='')">
						<div class="guest-request">
							<div><b>Special Request:</b></div>
							<div><xsl:value-of select="//module/bookingcart/data/booking/requests"/></div>
						</div>
					</xsl:if>
				</div>
			</div>
		</div>
	</div>
</xsl:template>

<xsl:template name="paymentinfo">

	<xsl:variable name="subtotal">
		<xsl:choose>
			<xsl:when test="//module/bookingcart/data/booking/rate/record[@id = '1']/amount &gt; 0">
				<xsl:value-of select="//module/bookingcart/data/booking/rate/record[@id = '1']/amount"/>
			</xsl:when>
			<xsl:otherwise>0</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:variable name="discount">
		<xsl:choose>
			<xsl:when test="(//module/bookingcart/data/booking/rate/record[@id = '2']/amount &gt; 0 or //module/bookingcart/data/booking/rate/record[@id = '2']/amount &lt; -1)">
				<xsl:value-of select="//module/bookingcart/data/booking/rate/record[@id = '2']/amount"/>
			</xsl:when>
			<xsl:otherwise>0</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:variable name="damage">
		<xsl:choose>
			<xsl:when test="//module/bookingcart/data/booking/rate/record[@id = '3']/amount &gt; 0">
				<xsl:value-of select="//module/bookingcart/data/booking/rate/record[@id = '3']/amount"/>
			</xsl:when>
			<xsl:otherwise>0</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:variable name="res-fee">
		<xsl:choose>
			<xsl:when test="//module/bookingcart/data/booking/rate/record[@id = '4']/amount &gt; 0">
				<xsl:value-of select="//module/bookingcart/data/booking/rate/record[@id = '4']/amount"/>
			</xsl:when>
			<xsl:otherwise>0</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:variable name="carpasses">
		<xsl:choose>
			<xsl:when test="//module/bookingcart/data/booking/rate/record[@id = '5']/amount &gt; 0">
				<xsl:value-of select="//module/bookingcart/data/booking/rate/record[@id = '5']/amount"/>
			</xsl:when>
			<xsl:otherwise>0</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:variable name="assurance">
		<xsl:choose>
			<xsl:when test="//module/bookingcart/data/booking/rate/record[@id = '6']/amount &gt; 0">
				<xsl:value-of select="//module/bookingcart/data/booking/rate/record[@id = '6']/amount"/>
			</xsl:when>
			<xsl:otherwise>0</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:variable name="tax">
		<xsl:choose>
			<xsl:when test="//module/bookingcart/data/booking/rate/record[@id = '7']/amount &gt; 0">
				<xsl:value-of select="//module/bookingcart/data/booking/rate/record[@id = '7']/amount"/>
			</xsl:when>
			<xsl:otherwise>0</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:variable name="total"><xsl:value-of select="$subtotal + $discount + $damage + $res-fee + $carpasses + $assurance + $tax"/></xsl:variable>

        <form action="/booking/processbooking" method="post" id="payment-info" name="payment-info" class="validate standard inline track">
        <input type="hidden" id="arrive" name="arrive" value="{//module/bookingcart/data/booking/@arrive}" />
        <input type="hidden" id="depart" name="depart" value="{//module/bookingcart/data/booking/@depart}" />
        <input type="hidden" id="propertyid" name="propertyid" value="{//module/bookingcart/data/booking/@propertyid}" />
        <input type="hidden" id="propertyid" name="propertyid" value="{//module/bookingcart/data/booking/@propertyid}" />

		<xsl:call-template name="guestinfo"/>

		<div class="book-section-heading">
			<div class="cart-heading payment">Payment Information</div>
		</div>

		<div class="payment-info">
			<div class="book-block">
				<div class="block-heading">Credit Card</div>
				<div class="book-block-content">
					<div class="field-wrap">
						<div class="field">
							<label for="cardholder">Name on Card:</label>
							<input type="text" name="cardname" id="cardholder" maxlength="100" class="textfield required" value="" />
						</div>
		                <div class="field">
		                    <label for="cardtype">Card Type:</label>
		                    <select id="cardtype" name="cardtype">
		                    	<option value="">Please Select</option>
		                    	<option value="VISA">VISA</option>
		                    	<option value="MC">Mastercard</option>
		                    	<option value="AMEX">American Express</option>
		                    	<option value="DISC">Discover</option>
		                    </select>
		                </div>
					</div>
					<div class="h-line"></div>
					<div class="field-wrap">
						<div class="field">
							<label for="cc-number">Card Number:</label>
							<input type="text" name="cardnumber" id="cc-number" maxlength="30" class="textfield required" value="" />
						</div>
		                <div class="field field-dates">
		                    <label>Expiration Date:</label>
		                    <select id="exp-month" name="cardexpmonth">
		                    	<option value="01">01</option>
		                    	<option value="02">02</option>
		                    	<option value="03">03</option>
		                    	<option value="04">04</option>
		                    	<option value="05">05</option>
		                    	<option value="06">06</option>
		                    	<option value="07">07</option>
		                    	<option value="08">08</option>
		                    	<option value="09">09</option>
		                    	<option value="10">10</option>
		                    	<option value="11">11</option>
		                    	<option value="12">12</option>
		                    </select>
		                    <select id="exp-year" name="cardexpyear">
		                    	<option value="2017">2017</option>
		                    	<option value="2018">2018</option>
		                    	<option value="2019">2019</option>
		                    	<option value="2020">2020</option>
		                    	<option value="2021">2021</option>
		                    	<option value="2022">2022</option>
		                    	<option value="2023">2023</option>
		                    	<option value="2024">2024</option>
		                    	<option value="2025">2025</option>
		                    	<option value="2026">2026</option>
		                    	<option value="2027">2027</option>
		                    	<option value="2028">2028</option>
		                    	<option value="2029">2029</option>
		                    </select>
		                </div>
					</div>
					<div class="h-line"></div>
					<div class="field-wide">
						<div class="field">
							<label for="address1">Address Line 1:</label>
							<input type="text" name="address1" id="address1" maxlength="100" class="textfield required" value="{/output/module/bookingcart/data/formdata/address1}" placeholder="Street Address, P.O. Box, company name, c/o"/>
						</div>
						<div class="field">
							<label for="address2">Address Line 2:</label>
							<input type="text" name="address2" id="address2" maxlength="100" class="textfield" value="{/output/module/bookingcart/data/formdata/address2}" placeholder="Apartment, suite, unit, building, floor, etc." />
						</div>
						<div class="field">
							<label for="city">City:</label>
							<input type="text" name="city" id="city" maxlength="100" class="textfield required" value="{/output/module/bookingcart/data/formdata/city}" />
						</div>
						<div class="field">
							<label for="state">State/Province/Region:</label>
							<input type="text" name="state" id="state" maxlength="100" class="textfield required" value="{/output/module/bookingcart/data/formdata/state}" />
						</div>
						<div class="field">
							<label for="">Zip:</label>
							<input type="text" name="zipcode" id="zipcode" maxlength="100" class="textfield required" value="{/output/module/bookingcart/data/formdata/zipcode}" />
						</div>
						<div class="field">
							<label for="country">Country</label>
							<select name="country" id="country">
								<option value="">Please Select</option>
								<option value="US">United States</option>
								<option value="CA">Canada</option>
								<option value="MX">Mexico</option>
							</select>
						</div>
					</div>
					<div class="h-line h-line-wide"></div>
					<div class="cancellation-policy">
						<p>Cancellation Policy</p>
                         <xsl:value-of select="//module/cancelpolicy/data/content" />
<!--
						<p>Villa reservations cancelled outside of the 30 day window will receive a full refund, minus a $75 cancellation fee. Homes are required to cancel outside of 60 days.</p>
-->
						<input value="1" type="checkbox" class="required" name="cancellationpolicy" id="cancellationpolicy" /> <label for="cancellationpolicy">Please check the box to indicate you have read and understand the cancellation policy.</label>
					</div>
					<!--div class="cancellation-policy">
						<input value="1" type="checkbox" class="required" name="rentalagreement" id="rentalagreement" /> <label for="rentalagreement">Please check the box to indicate you have read and accept the Guest Vacation <a href="/templates/email/rental-agreement.html" data-fancybox-type="iframe" class="fancybox">Rental Agreement</a>.</label>
					</div-->
				</div>
			</div>
		</div>

		<div class="total-footer">
			<b>Total:</b>&#160;&#160;USD&#160;<xsl:value-of select="format-number($total, '#,###.00')"/>
			<!-- Black Friday Use Only -->
			<xsl:variable name="sub-amount" select="//module/bookingcart/data/booking/rate"/>
			<xsl:variable name="due-subtotal">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '1']/duetoday &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '1']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-discount">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '2']/amount &gt; 0 or $sub-amount/record[@id = '2']/duetoday &lt; 0 ">
						<xsl:value-of select="$sub-amount/record[@id = '2']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-damage">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '3']/duetoday &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '3']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-res-fee">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '4']/duetoday &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '4']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-carpasses">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '5']/duetoday &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '5']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-assurance">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '6']/duetoday &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '6']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-tax">
				<xsl:choose>
					<xsl:when test="$sub-amount/record[@id = '7']/duetoday &gt; 0">
						<xsl:value-of select="$sub-amount/record[@id = '7']/duetoday"/>
					</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="due-total">
				<xsl:choose>
					<xsl:when test="
	            (//module/bookingcart/data/promocode= 'CYBERMONDAY16')
	            or (//module/bookingcart/data/promocode= 'CYBERMONDAY16-W')
	            or (//module/bookingcart/data/promocode= 'BLACKFRIDAY16')
	            or (//module/bookingcart/data/promocode= 'BLACKFRIDAY16-W')
	            or (//module/bookingcart/data/promocode= 'BFF16')
	            or (//module/bookingcart/data/promocode= 'BFF16-W')
	            or (//module/bookingcart/data/promocode= 'BLACKFRIDAY17')
	            or (//module/bookingcart/data/promocode= 'BFF17')
	            or (//module/bookingcart/data/promocode= 'CYBERMONDAY17')">99</xsl:when>
	        <xsl:when test="(//module/bookingcart/data/promocode= '199DOWNPD')
	            or (//module/bookingcart/data/promocode= '199DOWNPD-W')
	            or (//module/bookingcart/data/promocode= 'PDSPRING')
	            or (//module/bookingcart/data/promocode= 'SPRING17')
	            or (//module/bookingcart/data/promocode= 'PD199DOWN')">199</xsl:when>
	        <xsl:when test="(//module/bookingcart/data/promocode= 'ILOVEPD17')
	        	or (//module/bookingcart/data/promocode= 'ILOVEPD17-W')">250</xsl:when>
					<xsl:otherwise>
						<xsl:value-of select="$due-subtotal + $due-discount + $due-damage + $due-res-fee + $due-carpasses + $due-assurance + $due-tax"/>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="blackfriday">
				<xsl:choose>
					<xsl:when test="
						(//module/bookingcart/data/booking/rateplan = 'BLACKFRIDAY15')
						or (//module/bookingcart/data/booking/rateplan = 'BFF2015')
						or (//module/bookingcart/data/booking/rateplan = 'CYBERMONDAY2015')
						or (//module/bookingcart/data/booking/rateplan = '199DOWN') 
						or (//module/bookingcart/data/booking/rateplan = '199DOWN-W')
						or (//module/bookingcart/data/booking/rateplan = '2OFFERSN1')
						or (//module/bookingcart/data/booking/rateplan = '2OFFERSN1-W')
						or (//module/bookingcart/data/booking/rateplan = '2OFFERS4YOU')
						or (//module/bookingcart/data/booking/rateplan = '2OFFERS4YOU-W')
						or (//module/bookingcart/data/booking/rateplan = 'FALL4PD')
						or (//module/bookingcart/data/booking/rateplan = 'FALL4PD-W')
						or (//module/bookingcart/data/booking/rateplan = 'IMISSYOU2')
						or (//module/bookingcart/data/booking/rateplan = 'IMISSYOU2-W')
						or (//module/bookingcart/data/booking/rateplan = 'PD199DOWN')">1</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:choose>
				<xsl:when test="string-length($blackfriday) &gt; 0 and $blackfriday = '0'">
					<xsl:choose>
						<xsl:when test="string-length($due-total) &gt; 0 and $due-total != 'NaN'">
							<div class="due-today">
							<b>Due Today:</b>&#160;&#160;USD&#160;<xsl:value-of select="format-number($due-total, '#,###.00')"/></div>
							<input type="submit" id="book-submit" class="unit-book orange-btn payment-info-book submit" value="book now" onclick="this.disabled=true;this.value='Processing, Please Wait';this.form.submit();"/>
							<div class="click-once bf-edit">Click Book Now only once to avoid multiple charges.</div>
						</xsl:when>
						<xsl:otherwise>
							<input type="submit" id="book-submit" class="unit-book orange-btn payment-info-book submit" value="book now" onclick="this.disabled=true;this.value='Processing, Please Wait';this.form.submit();"/>
							<div class="click-once">Click Book Now only once to avoid multiple charges.</div>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:when>
				<xsl:otherwise>
					<input type="submit" id="book-submit" class="unit-book orange-btn payment-info-book submit" value="book now" onclick="this.disabled=true;this.value='Processing, Please Wait';this.form.submit();"/>
					<div class="click-once">Click Book Now only once to avoid multiple charges.</div>
				</xsl:otherwise>
			</xsl:choose>

			<!-- End of Black Friday Code -->
			<!--<input type="submit" id="book-submit" class="unit-book orange-btn payment-info-book submit" value="book now" onclick="this.disabled=true;this.value='Processing, Please Wait';this.form.submit();"/>
			<div class="click-once">Click Book Now only once to avoid multiple charges.</div>-->
			<div class="geotrust">
				<!-- GeoTrust QuickSSL [tm] Smart  Icon tag. Do not edit. -->
				<script language="javascript" type="text/javascript" src="//smarticon.geotrust.com/si.js"></script>
				<!-- end  GeoTrust Smart Icon tag -->
			</div>
		</div>
	</form>
</xsl:template>

<xsl:template name="confirmation-header">
	<div class="confirmation-header cf">
		<div class="book-unit-details">
			<div class="confirmation-text">
				<xsl:choose>
					<xsl:when test="//bookingcart/data/property/@wholesale='1'">

                    </xsl:when>
                    <xsl:otherwise>
                    	<xsl:value-of select="//module/reservconf/data/content" />
                    </xsl:otherwise>
            	</xsl:choose>
                <!--<xsl:value-of select="//module/reservconf/data/content" />-->
			</div>

			<div class="confirmation-number">Confirmation #:&#160;<xsl:value-of select="//module/bookingcart/data/booking/confirmation"></xsl:value-of></div>
		</div>
		<div class="book-pricing-details conf-contact">
			<div class="conf-send cf">
				<div class="conf-link">
					<div class="conf-print"><a href="#">print</a></div>
				</div>
				<div class="conf-link">
					<div class="conf-email"><a href="#" class="unit-email">Email</a>
						<div class="email-unit-popup">
							<a href="#" class="close"><img src="/templates/main/images/buttons/close-grey-trans.png" alt="Close"/></a>
                                                        <form action="/booking/emailprocess" id="email-unit" name="emailunit" method="post">
								<!-- <input type="hidden" id="start_date" name="arrival" value="" /> -->
								<fieldset>
									<div class="field">
										<label for="email">Send To:</label>
										<input type="text" name="emailto" id="email" maxlength="30" class="textfield required" placeholder="Enter Your Email Address" value="" />
										<input type="hidden" name="email" value="{//module/bookingcart/data/user/email}" />
										<input type="hidden" name="confirmation" value="{//module/bookingcart/data/booking/confirmation}" />
									</div>
								</fieldset>
								<fieldset>
									<div id="recaptchaDiv">
										<div>
											<script type="text/javascript" src="https://www.google.com/recaptcha/api/challenge?k=6Ld3B8kSAAAAAOv5nGoY1oI61eD3_VSEylicYrKF"></script>
											<noscript>
												<iframe src="https://www.google.com/recaptcha/api/noscript?k=6Ld3B8kSAAAAAOv5nGoY1oI61eD3_VSEylicYrKF" height="300" width="500" frameborder="0"></iframe><br/>
												<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
												<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
											</noscript>
										</div>
									</div>
									<a href="#" onClick="document.emailunit.submit();" class="email-unit-send orange-btn">Send Email</a>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="h-line"></div>
			<div class="conf-share">
				<xsl:call-template name="share-booking" />
			</div>
			<div class="h-line"></div>
			<div class="conf-call">Questions? Call <script type='text/javascript'>ShowNavisNCPhoneNumber();</script></div>
		</div>
	</div>
</xsl:template>
<xsl:template name="buildOptions">
    <xsl:param name="count" select="1" />
    <xsl:param name="max" select="$count+1" />
    <xsl:param name="sel"/>

    <xsl:if test="$count &lt;= $max">
    	<xsl:choose>
    		<xsl:when test="$count = $sel">
    			<option value="{$count}" selected="selected"><xsl:value-of select="$count" /></option>
    		</xsl:when>
    		<xsl:otherwise>
        		<option value="{$count}"><xsl:value-of select="$count" /></option>
        	</xsl:otherwise>
        </xsl:choose>
        <xsl:call-template name="buildOptions">
            <xsl:with-param name="count" select="$count + 1"/>
            <xsl:with-param name="max" select="$max"/>
            <xsl:with-param name="sel" select="$sel"/>
        </xsl:call-template>
    </xsl:if>
</xsl:template>