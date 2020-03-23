<xsl:key name="viewtype" match="//module/propertyview/data/record" use="@id" />
<xsl:key name="amenities" match="//module/amenities/data/record" use="@id" />
<xsl:key name="location" match="//module/locations/data/record" use="@id" />

<xsl:template name="opengraph-unit-photos-overview">
	<xsl:variable name="unit" select="//module/propdetails/data/@id" />
	<xsl:variable name="bed" select="//output/module/propdetails/data/@bedrooms" />
	<xsl:variable name="bath" select="//output/module/propdetails/data/@bathrooms" />
	<xsl:variable name="typeid"> 
		<xsl:choose>
			<xsl:when test="//output/module/propdetails/data/@typeid = '1'">
				<xsl:text>Vacation Rental</xsl:text>
			</xsl:when>
			<xsl:otherwise>
				<xsl:text>Villa Rental</xsl:text>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<title><xsl:value-of select="concat(//propdetails/data/name,' ',$bed,' Bedrooms ',$bath,' Baths ',$typeid,' | ',//info/name/@short,' | Hilton Head ',$typeid,'s')"/></title>
	<meta property="og:title" content="{concat(//propdetails/data/name, ' at Palmetto Dunes.')}" />
	<meta property="og:description" content="'I just found this great place to stay at Palmetto Dunes Oceanfront Resort'" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="{//system/activePathRel/@value}{//system/activeSection0/@value}/{//system/activeSection1/@value}?propertyid={//propdetails/data/@id}" />
	<meta property="og:image" content="/assets/images/properties/{//propdetails/data/@id}/images/{//propdetails/data/images/record[position() = 1]/filename}" />
	<meta name="description" content="{//module/propdetails/data/description}" />
	<meta name="keywords" content="hilton head island vacation rentals, hilton head island villas, hilton head sc vacation home rentals, hilton head vacation home rentals" />
</xsl:template>

<xsl:template name="unit-header">
	<div class="primary-header">
		<xsl:for-each select="//module/propdetails/data">
			<h1 class="unit-header">
	            <xsl:choose>
	            	<xsl:when test="@typeid='1'"><xsl:text>Home</xsl:text></xsl:when>
	            	<xsl:otherwise><xsl:text>Villa</xsl:text></xsl:otherwise>
	            </xsl:choose>
	            <xsl:text> &#8211; </xsl:text>
	            <xsl:value-of select="name/text()"/>
			</h1>
		</xsl:for-each>
	</div>
	<div class="unit-nav">
		<ul>
			<li class="nav-tab nav-tab-search unit-tab-search"><a href="/booking/vacation-rentals/hilton-head-vacation-rentals">Back to Search</a></li>
			<li class="nav-tab nav-tab-overview"><a href="#photos-overview"><span></span>Photos &#38; Overview</a></li>
			<li class="nav-tab nav-tab-rates"><a href="#rates-availability"><span></span>Rates &#38; Availability</a></li>
			<li class="nav-tab nav-tab-map"><a href="#unit-map"><span></span>View on Map</a></li>
		</ul>
	</div>
</xsl:template>

<xsl:template name="bedding">
    <div class="bed">
        <xsl:choose><xsl:when test="../../name/text()='Extra Bedroom'"><xsl:value-of select="name" /></xsl:when><xsl:otherwise><xsl:if test="@count &gt; 0"><xsl:value-of select="@count"/>&#160;</xsl:if><xsl:value-of select="name"/><!--&#160;Bed<xsl:if test="@count > 1">s</xsl:if>-->
            </xsl:otherwise>
        </xsl:choose>
    </div>
</xsl:template>

<xsl:template name="photos-overview">
	<xsl:for-each select="//module/propdetails/data">
		<xsl:variable name="arrivemonth"><xsl:value-of select="substring(//pageinfo/querystring/arrive, 1, 2)"/></xsl:variable>
		<xsl:variable name="arriveday"><xsl:value-of select="substring(//pageinfo/querystring/arrive, 4, 2)"/></xsl:variable>
		<xsl:variable name="arriveyear"><xsl:value-of select="substring(//pageinfo/querystring/arrive, 7, 4)"/></xsl:variable>
		<xsl:variable name="arrivedate"><xsl:value-of select="concat($arriveyear,'-',$arrivemonth,'-',$arriveday)"/></xsl:variable>
		<xsl:variable name="rateperiod">

			<xsl:value-of select="displayrateline/@minstay"/>

			<!-- <xsl:for-each select="ratetable/record">
				<xsl:choose>
					<xsl:when test="substring(@startdate, 1, 4) &gt;= $arriveyear and substring(@startdate, 6, 2) &lt;= $arrivemonth and substring(@enddate, 1, 4) &lt;= $arriveyear and substring(@enddate, 6, 2) &lt;= $arrivemonth">
                		<xsl:value-of select="position()" />
					</xsl:when>
					<xsl:otherwise></xsl:otherwise>
				</xsl:choose>
			</xsl:for-each> -->
		</xsl:variable>

		<xsl:variable name="satrule">
			<xsl:value-of select="//module/propdetails/data/displayrateline/@sattosat"/>
		</xsl:variable>

		<div id="photos-overview" class="photos-overview cf" data-id="{@id}" data-address="{name}" data-latitude="{@latitude}" data-longitude="{@longitude}" data-rateperiod="{$rateperiod}" data-location="{key('location',@locationid)}" data-view="{key('viewtype',@viewid)}" data-bedrooms="{@bedrooms}">
			<div class="gallery-wrapper">
				<xsl:variable name="imgpath"><xsl:text>https://sabrecdn.com/pdbookingv12/images/properties/</xsl:text></xsl:variable>
				<xsl:variable name="gallery-images">
					<xsl:for-each select="images/record">
						<xsl:value-of select="."/>
						<xsl:if test="position() != last()">
							<xsl:text>,</xsl:text>
						</xsl:if>
					</xsl:for-each>
				</xsl:variable>

				<div class="unit-img-wrapper">
					<xsl:if test="//module/propdetails/data/@tour_url != ''">
						<xsl:call-template name="virtual-tour-small" />
					</xsl:if>
                	<xsl:variable name="imgCaption"><xsl:value-of select="//module/reservations/data/record/images/record/caption"></xsl:value-of></xsl:variable>
					<xsl:variable name="currprop"><xsl:value-of select="@id"></xsl:value-of></xsl:variable>
					<div id="slideshow-1" class="room-gallery">
                    	
						<div id="cycle-1" class="cycle-slideshow"
                            data-cycle-slides="> div"
                            data-cycle-timeout="0"
                            data-cycle-prev="#slideshow-1 .cycle-prev"
                            data-cycle-next="#slideshow-1 .cycle-next"
                            data-cycle-caption-plugin="caption2" 
                            data-cycle-fx="fade"
                            data-cycle-log="false"
                            >
                            <xsl:for-each select="images/record">
								<div class="lg-gal-image">
                                	<img src="{$imgpath}{$currprop}/images/{filename}" alt="{caption}" data-cycle-title="{caption}" />
                                	<span class="span-cycle-overlay"><xsl:value-of select="caption"/></span>
                              	</div>
							</xsl:for-each>
                            
						</div>
						<a href="#" class="room-prev cycle-prev"></a>
        				<a href="#" class="room-next cycle-next"></a>
					</div>
                    
					<div id="slideshow-2" class="room-gallery-thumbs-wrapper">
						<div class="room-gallery-thumbs center external">
                        	<div id="cycle-2" class="cycle-slideshow"
                                data-cycle-slides="> div"
                                data-cycle-timeout="0"
                                data-cycle-prev="#slideshow-2 .cycle-prev"
                                data-cycle-next="#slideshow-2 .cycle-next"
                                data-cycle-fx="carousel"
                                data-cycle-carousel-visible="6"
                                data-allow-wrap="false"
                                data-cycle-log="false"
                                >
                                <xsl:for-each select="images/record">
                                    <div><img src="{$imgpath}{$currprop}/images/{filename}" alt="" /></div>
                                </xsl:for-each>
							</div>
                        </div>
						<a href="#" class="cycle-prev blue-btn"><span></span></a>
                        <a href="#" class="cycle-next blue-btn"><span></span></a>
					</div>
					<xsl:if test="//module/propdetails/data/@tour_url != ''">
						<xsl:call-template name="virtual-tour" />
					</xsl:if>
					<xsl:call-template name="share" />
				</div><!-- pre-edit diff -->
			</div>
			<div class="unit-overview-sidebar">
				<div>
					<!-- temp using position()=1 since "selected dates" method not working on server -->
					<xsl:attribute name="class">
						<xsl:choose>
							<xsl:when test="//pageinfo/querystring/promocode/text()">
								<xsl:text>rate-wrapper total-rate-wrapper promo-rate-wrapper</xsl:text>
							</xsl:when>
							<xsl:when test="//propdetails/data/@totalrate">
								<xsl:text>rate-wrapper total-rate-wrapper</xsl:text>
							</xsl:when>
							<xsl:otherwise>
								<xsl:text>rate-wrapper</xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:attribute>

					<xsl:choose>
						<xsl:when test="//propdetails/data/@totalrate">
							<div class="rate-from">For Dates Selected</div>
						</xsl:when>
						<xsl:otherwise>
							<div class="rate-from">Starting from</div>
						</xsl:otherwise>
					</xsl:choose>
					<xsl:variable name="propIdNew" select="//module/propdetails/data/@id" />
					<div class="rate-night">
						<xsl:choose>
							<xsl:when test="//pageinfo/querystring/promocode/text()">
								<div class="promo-rate-night">
									$<i class="num">
										<xsl:choose>
											<xsl:when test="//propdetails/data/@totalrate">
												<xsl:value-of select="round(//module/propdetails/data/@dailyrate)"/>
											</xsl:when>
											<xsl:otherwise>
												<!--<xsl:value-of select="round(//module/propdetails/data/@promodailyminrate)"/>-->									
												<xsl:value-of select="round(//module/propdetails/data/@promodailyminrate)"/>
											</xsl:otherwise>
										</xsl:choose>
									</i>
								</div>
								<div class="orig-rate">
									$<i class="num">
										<xsl:choose>
											<xsl:when test="//propdetails/data/@totalrate">
												<xsl:value-of select="round(//module/propdetails/data/@slashthroughdailyrate)" />
											</xsl:when>
											<xsl:otherwise>
												<!--<xsl:value-of select="round(//module/propdetails/data/@dailyminrate)" />-->
												<xsl:value-of select='round(//module/propdetails/data/@dailyminrate)' />
											</xsl:otherwise>
										</xsl:choose>
									</i>
								</div>
							</xsl:when>
							<xsl:otherwise>
								$<i class="num">
									<xsl:choose>
										<xsl:when test="//propdetails/data/@totalrate">
											<xsl:value-of select="round(//module/propdetails/data/@dailyrate)" />
										</xsl:when>
										<xsl:otherwise>
											<xsl:value-of select="round(//module/propdetails/data/@dailyminrate)" />
										</xsl:otherwise>
									</xsl:choose>
								</i>
							</xsl:otherwise>
						</xsl:choose>
						<span>per night</span>
					</div>
					<div class="rate-week">
						<xsl:choose>
							<xsl:when test="//pageinfo/querystring/promocode/text()">
								<div class="promo-rate-week">
									$<i class="num">
										<xsl:choose>
											<xsl:when test="//propdetails/data/@totalrate">
												<xsl:value-of select="round(//module/propdetails/data/@totalrate)" />
											</xsl:when>
											<xsl:otherwise>
												<!--<xsl:value-of select="format-number((round(//module/propdetails/data/@promoweeklyminrate * 7)),'#,###')"/>-->
												<xsl:value-of select='round(//module/propdetails/data/@promoweeklyminrate * 7)' />
											</xsl:otherwise>
										</xsl:choose>
									</i>
								</div>
								<div class="orig-rate">
									$<i class="num">
										<xsl:choose>
											<xsl:when test="//propdetails/data/@totalrate">
												<xsl:value-of select="round(//module/propdetails/data/@slashthroughtotalrate)" />
											</xsl:when>
											<xsl:otherwise>

												<!--<xsl:value-of select="format-number((round(//module/propdetails/
												data/@weeklyminrate * 7)),'#,###')"/>-->
												<xsl:value-of select='	round(//module/propdetails/data/@weeklyminrate * 7)' />
											</xsl:otherwise>
										</xsl:choose>
									</i>
								</div><span>subtotal <span class="tax-disclaimer"><span class="info"></span>plus taxes &amp; fees</span></span>

								<div class="unit-pricing-tooltip">
									<xsl:value-of select="//module/reservfee/data/content" />
								</div>
							</xsl:when>
							<xsl:otherwise>
								$<i class="num">
									<xsl:choose>
										<xsl:when test="//propdetails/data/@totalrate">
											<xsl:value-of select="round(//module/propdetails/data/@totalrate)" />
										</xsl:when>
										<xsl:otherwise>
											<xsl:value-of select="format-number((round(//module/propdetails/data/@weeklyminrate * 7)),'#,###')"/>
										</xsl:otherwise>
									</xsl:choose>
								</i><span>subtotal</span>
							</xsl:otherwise>
						</xsl:choose>
					</div>
				</div>
				<!-- <xsl:if test="(//reservations/data/record/@id = //module/propdetails/data/@id) and (not(/output/pageinfo/querystring/promocode/text()))">
					<xsl:variable name="propId" select="//module/propdetails/data/@id" />
					<xsl:if test="//reservations/data/record[@id=$propId]/featuredpromo/name">
						<div class="unit-promo">
                        	<xsl:value-of select="//reservations/data/record/featuredpromo/name"/>
                        	<a href="#" rel="{//reservations/data/record/featuredpromo/promocode}" class="add-promo-featured blue-btn">Apply Promo</a>
                        </div>
					</xsl:if>
					<xsl:choose>
                        <xsl:when test="/output/pageinfo/querystring/promocode/text()">
                        	<div class="unit-promo">
                            	<xsl:value-of select="//propdetails/data/promo/name"/>
                            	<a href="#" class="add-promo blue-btn">Apply Promo</a>
                        	</div>
                        </xsl:when>
                        <xsl:when test="//module/propdetails/data/@id = //module/featured/data/record/@id">
                        	<div class="unit-promo">
                        	<xsl:value-of select="/output/module/featured/data/record/featuredpromo/name"/>
                        	<a href="#" rel="{/output/module/featured/data/record/featuredpromo/promocode}" class="add-promo-featured blue-btn">Apply Promo</a>
                        	</div>
                        </xsl:when>
                        <xsl:otherwise>
                        </xsl:otherwise>
                    </xsl:choose>
				</xsl:if> -->

				<xsl:choose>
					<xsl:when test="(//reservations/data/record/@id = //module/propdetails/data/@id) and (not(/output/pageinfo/querystring/promocode/text()))">
						<xsl:variable name="propId" select="//module/propdetails/data/@id" />
						<xsl:if test="//reservations/data/record[@id=$propId]/featuredpromo/name">
							<div class="unit-promo">
	                        	<xsl:value-of select="//reservations/data/record/featuredpromo/name"/>
	                        	<a href="#" rel="{//reservations/data/record/featuredpromo/promocode}" class="add-promo-featured blue-btn">Apply Promo</a>
	                        </div>
						</xsl:if>
					</xsl:when>
					<xsl:when test="//pageinfo/querystring/promocode/text()">
						<div class="unit-promo">
							<xsl:value-of select="//module/propdetails/data/promo/name"/>
                        	<a href="#" rel="{@id}" class="promo-learn-more">Learn More</a>
                        	<div class="results-promo">
								<xsl:variable name="propPromo" select="//module/reservations/data/record" />
                        		<div id="results-promo-details" class="results-promo-details cf">
                                    <a href="#" class="promo-close">close</a>
                                    <div class="results-promo-content">
                                    	<div class="results-promo-short">
                                            <xsl:value-of select="$propPromo/promotions/record/name"/>
                                        </div>
                                        <div class="results-promo-long">
                                            <xsl:value-of select="$propPromo//promotions/record/shortdescription"/>
                                        </div>
                                    </div>
                                </div>
                        	</div>
                        </div>
					</xsl:when>
					<xsl:otherwise>
					</xsl:otherwise>
				</xsl:choose>

				<div class="unit-quality"></div>
				<div class="unit-view"><xsl:value-of select="key('viewtype',@viewid)" /></div>
				<div class="unit-bedrooms"><xsl:value-of select="@bedrooms" /> Bedrooms</div>
				<div class="unit-sleeps">Sleeps <xsl:value-of select="@sleeps" /></div>
				<div class="unit-bathrooms"><xsl:value-of select="@bathrooms" /> Baths</div>
				<div class="unit-restrictions"><xsl:if test="$rateperiod != ''"><!-- <xsl:value-of select="ratetable/record[position() = $rateperiod]/@minstay" /> --> <xsl:value-of select="$rateperiod"/> Night Minimum Stay Required </xsl:if></div>

				<div class="unit-satrule"><xsl:if test="$satrule = '1'">Saturday-to-Saturday Stay Only</xsl:if></div>

				<div class="unit-book-wrapper">
					<xsl:choose>
						<xsl:when test="//pageinfo/querystring/arrive!='' and //pageinfo/querystring/depart!=''">
                                                    <a href="/booking/guestinfo?propertyid={//module/propdetails/data/@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;arrive_submit={//pageinfo/querystring/arrive_submit}&#38;depart={//pageinfo/querystring/depart}&#38;depart_submit={//pageinfo/querystring/depart_submit}&#38;typeid={//pageinfo/querystring/typeid}&#38;viewid={//pageinfo/querystring/viewid}&#38;locationid={//pageinfo/querystring/locationid}&#38;bedrooms={//pageinfo/querystring/bedrooms}&#38;sleeps={//pageinfo/querystring/sleeps}&#38;amenityids={//pageinfo/querystring/amenityids}&#38;promocode={//pageinfo/querystring/promocode}" class="unit-book orange-btn">Book Unit</a>
						</xsl:when>
					    <xsl:otherwise>
							<a href="#rates-availability" class="unit-book orange-btn">Book Unit</a>
						</xsl:otherwise>
					</xsl:choose>
					<div class="unit-contact">Questions? Call <script type='text/javascript'>ShowNavisNCPhoneNumber();</script></div>

					<div id="push-2-talk">
						<a id="lnkP2Talk" href="https://www.thenavisway.com/p2talk/P2TCust.aspx?account=15521" target="new" class="Push2Talk"><img src="/templates/main/images/buttons/push2talk.png" alt="Push 2 Talk" /></a>
						<script language="javascript">SetNavisP2TalkCustomLink("lnkP2Talk");</script>
					</div>

					<a href="#" class="unit-email white-btn">Email This Unit</a>
					<div class="email-unit-popup">
						<a href="#" class="close"><img src="/templates/main/images/buttons/close-grey-trans.png" alt="Close"/></a>
                                                <form action="/booking/emailunit?SHOWXML" id="email-unit" name="emailunit" method="post">
							<!-- <input type="hidden" id="start_date" name="arrival" value="" /> -->
							<fieldset>
			                    <div class="field fromemail">
			                        <label for="fromemail">Your Email:</label>
			                        <input type="text" name="fromemail" id="fromemail" maxlength="30" class="textfield required" placeholder="Enter Your Email Address" value="" />
			                        <input type="hidden" name="propertyid" value="{/output/pageinfo/querystring/propertyid}" />
			                        <input type="hidden" name="arrive" value="{/output/pageinfo/querystring/arrive}" />
			                        <input type="hidden" name="depart" value="{/output/pageinfo/querystring/depart}" />
			                        <input type="hidden" name="promocode" value="{/output/pageinfo/querystring/promocode}" />
			                    </div>
                                <div class="field check-optin clear-float">
                                    <label for="optin">I'd like to sign up for exclusive email savings&#33;</label>
                                    <input type="checkbox" value="1" id="optin" name="optin" />
                            	</div>
                            	<div class="field from-email">   
                                    <label for="email">Recipients Email:</label>
			                        <input type="text" name="email" id="email" maxlength="30" class="textfield required" placeholder="Recipients Email" value="" />
                            	</div>
							</fieldset>
							<fieldset>
                                <script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
				                <div id="recaptchaDiv">
				                	<div class="g-recaptcha" data-sitekey="6LfZknUUAAAAAHz44QIJrdKbMCxDIyv68h5Hh_1F"></div>
				              	</div>
								<a href="#" onClick="document.emailunit.submit();" class="email-unit-send orange-btn">Send Email</a>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="unit-overview">
				<div class="section-heading prop-desc-head">Property Description:</div>
				<div class="prop-description"><xsl:value-of select="description"/></div>
				<xsl:if test="count(bedcount/record) > 0">
					<div class="section-heading">Bedrooms:</div>
					<div class="bed-types cf">
						<xsl:variable name="bedrows">
							<xsl:choose>
								<xsl:when test="@bedrooms mod 2 = 1"><xsl:value-of select="floor(@bedrooms div 2) + 1"/></xsl:when>
								<xsl:otherwise><xsl:value-of select="@bedrooms div 2"/></xsl:otherwise>
							</xsl:choose>
						</xsl:variable>
						<div class="bed-column bed-col1">
							<xsl:for-each select="bedcount/record[position() &lt;= $bedrows]">
								<div class="bedroom cf">
									<div class="bedroom-name"><xsl:value-of select="name"/></div>
									<div class="beds">
										<xsl:for-each select="beds/record">
                      <xsl:call-template name="bedding" />
										</xsl:for-each>
									</div>
								</div>
							</xsl:for-each>
						</div>
						<div class="bed-column bed-col2">
							<xsl:for-each select="bedcount/record[position() &gt; $bedrows]">
								<div class="bedroom cf">
									<div class="bedroom-name"><xsl:value-of select="name"/></div>
									<div class="beds">
										<xsl:for-each select="beds/record">
                      <xsl:call-template name="bedding" />
										</xsl:for-each>
									</div>
								</div>
							</xsl:for-each>
							<!--<xsl:if test="@bedrooms mod 2 = 1"><div class="bedroom">&#160;</div></xsl:if>-->
						</div>
					</div>
				</xsl:if>
				<div class="section-heading">Amenities Include:</div>
				<ul class="unit-amenities">
					<xsl:for-each select="amenities/record">
                                            <!-- 
                                                 29 = Seasonally Heated Pool
                                                 87 = Gas Grill 

                                                 These are strictly used for filtering
                                                 so exclude them from display
                                             -->
                                            <xsl:if test="@id != '29' and @id != '87'">
                                                <li>
                                                    <xsl:value-of select="key('amenities',@id)" /></li>
                                            </xsl:if>
					</xsl:for-each>
				</ul>
			</div>
		</div>
	</xsl:for-each>
</xsl:template>

<xsl:template name="rates-availability">
	<div id="rates-availability" class="rates-availability cf">
		<div class="section-heading" id="sect-heading"><a id="rates-avail-anch" name="rates-avail-anchor"></a>Rates &#38; Availability:</div>
		<xsl:call-template name="booking-calendar"/>
	</div>
</xsl:template>

<xsl:template name="unit-map">
	<div id="unit-map" class="unit-map cf">
		<div class="section-heading">View on Map:</div>

		<div id="unit-map-outer-wrapper">
			<xsl:call-template name="map-include" />
		</div>

		<div class="unit-directions-wrapper">
			<div class="distance-input">
				<div class="distance-copy">Please use the map to see how far away this property is from our activities and amenities at the resort.</div>
				<div class="distance-from"><div class="wrap"><span>1</span>From:</div><i id="from-point"><xsl:value-of select="//module/propdetails/data/name"/></i></div>
				<div class="distance-to">
					<div class="wrap"><span>2</span>To:</div>
					<!-- <input id="to-locations" type="text" value="" class=""/> -->
					<!-- <input type="hidden" id="to-locations-id"/> -->
					<!-- <img id="to-locations-icon" src="/templates/main/images/map/icn-directions.png" class="ui-state-default" alt=""/> -->
					<select class="dropdown" id="destinations">
						<option value="">Select one...</option>
						<option value="10 Queens Folly Rd">Palmetto Dunes Welcome Center</option>
						<option value="10 Trent Jones Lane">Robert Trent Jones Golf Course</option>
						<option value="2 Leamington Lane">Arthur Hills Golf Course</option>
						<option value="2 Carnoustie Road">George Fazio Golf Course</option>
						<option value="6 Trent Jones Lane">Palmetto Dunes Tennis Center</option>
						<option value="14 Dunes House Lane">The Dunes House Oceanfront Restaurant</option>
						<option value="7 Trent Jones Lane">Big Jim's BBQ, Burgers &#150; Pizza Restaurant</option>
						<option value="1 Harbourside Lane">Shelter Cove Harbour</option>
						<option value="1 Shelter Cove Lane">Shelter Cove Marina</option>
						<option value="1 Shelter Cove Lane">Ship's Store</option>
						<option value="80 Queens Folly Road">Hilton Head Outfitters</option>
						<option value="1 Trent Jones Lane">Palmetto Dunes General Store</option>
						<option value="7 Queens Folly Road">Owners Association Pass Office</option>
					</select>
				</div>
			</div>
			<div class="distance-output">
				<div class="distance-total">Total Distance: <span class="output"></span></div>
				<div class="walk-time">Estimated Walking Time: <span class="output"></span></div>
				<div id="directions-output"></div>
				<div id="warnings-panel"></div>
			</div>
		</div>

	</div>
</xsl:template>

<xsl:template name="similar-units">
	<xsl:variable name="imgpath"><xsl:text>https://sabrecdn.com/pdbookingv12/images/properties/</xsl:text></xsl:variable>
	<div id="similar-units">
		<div class="similar-heading">Additional Units That May Interest You</div>
		<xsl:for-each select="//module/similarprops/data">
			<xsl:variable name="unitcount"><xsl:value-of select="count(record) - 2"/></xsl:variable>
			<!-- start at random position, so limit $unitcount so $random can't be higher than # of records - 2 -->
			<xsl:variable name="random"><xsl:value-of select="(floor(math:random()*$unitcount))" /></xsl:variable>

			<!-- 1 featured unit -->
			<xsl:variable name="feunitcount"><xsl:value-of select="count(/output/module/featured/data/record) - 1"/></xsl:variable>
			<!-- start at random position, so limit $unitcount so $random can't be higher than # of records - 1 -->
			<xsl:variable name="ferandom"><xsl:value-of select="(floor(math:random()*$feunitcount))" /></xsl:variable>

			<!-- 2 featured units -->
			<xsl:variable name="funitcount"><xsl:value-of select="count(/output/module/featured/data/record) - 2"/></xsl:variable>
			<!-- start at random position, so limit $unitcount so $random can't be higher than # of records - 2 -->
			<xsl:variable name="frandom"><xsl:value-of select="(floor(math:random()*$funitcount))" /></xsl:variable>


			<xsl:choose>
				<xsl:when test="count(record) &gt; 1">
					<div class="featured-prop-wrapper">
						<div class="featured-prop-content">
							<xsl:for-each select="record[position() &gt; $random and position() &lt; ($random + 3)]">
								<xsl:variable name="currprop"><xsl:value-of select="@id"></xsl:value-of></xsl:variable>
								<div class="featured-prop">
									<div class="featured-title"><xsl:value-of select="name"/></div>
									<div class="featured-inset"><img src="{$imgpath}{$currprop}/images/{images/record[position()=1]/filename}" alt="" /></div>
									<div class="featured-desc">
										<xsl:value-of select="substring(shortdescription, 1, 200 + string-length(substring-before(substring(shortdescription, 201),' ')))"/><!-- limits characters to 200, then backs up to a space so no partial words are output --> &#8230;
										<a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;depart={//pageinfo/querystring/depart}&#38;promocode={//pageinfo/querystring/promocode}" class="featured-link blue-btn">Check Availability</a>
									</div>
								</div>
							</xsl:for-each>
						</div>
					</div>
				</xsl:when>

				<xsl:when test="count(record) = 1">
					<div class="featured-prop-wrapper">
						<div class="featured-prop-content">
							<xsl:for-each select="record">
								<xsl:variable name="currprop"><xsl:value-of select="@id"></xsl:value-of></xsl:variable>
								<div class="featured-prop">
									<div class="featured-title"><xsl:value-of select="name"/></div>
									<div class="featured-inset"><img src="{$imgpath}{$currprop}/images/{images/record[position()=1]/filename}" alt="" /></div>
									<div class="featured-desc">
										<xsl:value-of select="substring(shortdescription, 1, 200 + string-length(substring-before(substring(shortdescription, 201),' ')))"/><!--test case limits characters to 200, then backs up to a space so no partial words are output --> &#8230;
										<a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;depart={//pageinfo/querystring/depart}&#38;promocode={//pageinfo/querystring/promocode}" class="featured-link blue-btn">Check Availability</a>
									</div>
								</div>
							</xsl:for-each>
							<xsl:for-each select="/output/module/featured/data/record[position() &gt; $ferandom and position() &lt; ($ferandom + 3)]">
								<xsl:variable name="currprop"><xsl:value-of select="@id"></xsl:value-of></xsl:variable>
									<xsl:if test="not(position() > 1)">
										<div class="featured-prop">
											<div class="featured-title"><xsl:value-of select="name"/></div>
											<div class="featured-inset"><img src="{$imgpath}{$currprop}/images/{images/record[position()=1]/filename}" alt="" /></div>
											<div class="featured-desc">
												<xsl:value-of select="substring(shortdescription, 1, 200 + string-length(substring-before(substring(shortdescription, 201),' ')))"/><!-- limits characters to 200, then backs up to a space so no partial words are output --> &#8230;
												<a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;depart={//pageinfo/querystring/depart}&#38;promocode={//pageinfo/querystring/promocode}" class="featured-link blue-btn">Check Availability</a>
											</div>
										</div>
									</xsl:if>
							</xsl:for-each>
						</div>
					</div>
				</xsl:when>

				<xsl:when test="(count(record) = 0) and (count(/output/module/featured/data/record) &gt; 1)">
					<div class="featured-prop-wrapper">
						<div class="featured-prop-content">
							<xsl:for-each select="/output/module/featured/data/record[position() &gt; $frandom and position() &lt; ($frandom + 3)]">
								<xsl:variable name="currprop"><xsl:value-of select="@id"></xsl:value-of></xsl:variable>
									<xsl:if test="not(position() > 2)">
										<div class="featured-prop {$frandom}">
											<div class="featured-title"><xsl:value-of select="name"/></div>
											<div class="featured-inset"><img src="{$imgpath}{$currprop}/images/{images/record[position()=1]/filename}" alt="" /></div>
											<div class="featured-desc">
												<xsl:value-of select="substring(shortdescription, 1, 200 + string-length(substring-before(substring(shortdescription, 201),' ')))"/><!-- limits characters to 200, then backs up to a space so no partial words are output --> &#8230;
												<a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;depart={//pageinfo/querystring/depart}&#38;promocode={//pageinfo/querystring/promocode}" class="featured-link blue-btn">Check Availability</a>
											</div>
										</div>
									</xsl:if>
							</xsl:for-each>
						</div>
					</div>
				</xsl:when>

				<xsl:otherwise>
					<p class="featured-none">There are no featured offers at this time.</p>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:for-each>
	</div>
</xsl:template>

