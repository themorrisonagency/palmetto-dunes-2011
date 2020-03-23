<xsl:template name="facebook-setup">
	<div id="fb-root" style="display:none;"></div>
	    <!-- Facebook -->
	    <script>
		window.fbAsyncInit = function() {
		  FB.init({appId: '<xsl:value-of select="//info/facebook/@api_key" />', status: true, cookie: true,
				   xfbml: true});
		};
		(function() {
		  var e = document.createElement('script'); e.async = true;
		  e.src = document.location.protocol +
			'//connect.facebook.net/en_US/all.js';
		  document.getElementById('fb-root').appendChild(e);
		}());
	    </script>
</xsl:template>

<xsl:template name="opengraph">
	<xsl:variable name="offer" select="//offers/data/record" />
	<xsl:if test="//pageinfo/permalinks/param">
		<title><xsl:value-of select="concat($offer/title,' | ',//info/name/@short)"/></title>
		<meta property="og:title" content="{$offer/title} | {//info/name/@short}" />
		<meta property="og:description" content="{$offer/description}" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="{//system/activePathRel/@value}{$offer/permalink}" />
		<meta property="og:image" content="{//system/activePathRel/@value}{$offer/@image_url}{$offer/@image}" />
	</xsl:if>
</xsl:template>

<xsl:template name="opengraph-unit">
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
	<meta name="description" content="Palmetto Dunes Oceanfront Resort is a Hilton Head Island resort that offers one of the widest selections of vacation rentals, villas and homes. Enjoy a relaxing Hilton Head vacation with one of these spacious home or villa rentals." />
	<meta name="keywords" content="hilton head island vacation rentals, hilton head island villas, hilton head sc vacation home rentals, hilton head vacation home rentals" />
</xsl:template>

<xsl:template name="signupcontent"><!-- temp? pull from amaxus side if/when possible -->
	<div class="signup-content">
		<div class="signup-inner">
			<div class="signup-wrap">
				<div class="amax-module-header">
					<h2>Sign Up to Receive Special Offers</h2>
				</div>
				<div class="amax-module-body">
					<form method="post" class="conForm" action="">
						<fieldset class="form-token">
							<input type="hidden" name="__token" value="XNKXJUNIORSXUFCRQNVBVHVSLDGTZL" />
						</fieldset>
						<fieldset class="fieldGroup">
							<div class="formRow pos1">
								<div class="fieldLeft"><label for="id397954445630"></label></div>
								<div  class="formfield "><input type="text" name="sendField[6]" id="id397954445630" placeholder="Email Address" style="width: auto" /></div>
							</div>
						</fieldset>
                        <div class="submitRow">
                            <input type="hidden" name="contentId" value="2" />
                            <input type="hidden" name="controller" value="content" />
                            <input type="hidden" name="contentType" value="conForm" />
                            <input type="hidden" name="formId" value="2" />
                            <input type="submit" name="submit" value="Sign Up" class="submitButton orange-btn" />
                        </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</xsl:template>

<xsl:template name="crosspromocontent"><!-- temp? pull from amaxus side if/when possible -->
    <xsl:variable name="template-root"><xsl:text>/booking/templates/main</xsl:text></xsl:variable>
	<div class="cross-promo-content">
		<xsl:variable name="amaxus">http://www.palmettodunes.com</xsl:variable>
		<xsl:variable name="preview-uploads">http://staging.sabrehospitalitycms.com</xsl:variable>
		<div class="cross-promo-inner">
			<div class="promo-left">
				<div class="crosspromo-push">
					<div class="crosspromo-wrapper">
                        <div class="crosspromo-title">Bike Rentals for Everyone</div>
                        <div class="crosspromo-inset"><img src="/booking/amaxus-upload/cross-promo-bike-rentals-for-everyone.jpg" alt="Bike Rentals for Everyone"/></div>
                        <div class="crosspromo-description">Hilton Head Outfitters features the island’s largest bike rental fleet. Choose from beach cruisers, comfort bikes, tandems, kids bikes, tag-a-longs, kiddie carts and more. Plus bike equipment, accessories, sales and repair. <![CDATA[<br />]]><a class="blue-btn" href="http://www.palmettodunes.com/activities/hilton-head-bike-rentals/">Learn More</a>
                        </div>
					</div>
				</div>
			</div>
			<div class="promo-right">
				<div class="crosspromo-push">
					<div class="crosspromo-wrapper">
						<div class="crosspromo-title">Golf Stay &amp; Play </div>
                                                <div class="crosspromo-inset"><img src="/booking/amaxus-upload/cross-promo-golf-academy-computer.jpg" alt="Golf Stay &amp; Play"/></div>
						<div class="crosspromo-description">Our most popular golf getaway! The Golf Stay &amp; Play packages feature lodging at our Hilton Head resort, multiple golf rounds, merchandise and resort dining discounts plus preferred rates and priority access to all other resort activities. <![CDATA[<br />]]><a class="blue-btn" href="http://www.palmettodunes.com/specials/64980/Golf+Stay+and+Play">View Details</a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</xsl:template>

<xsl:template name="activities-push">
	<div class="book-section-heading">
		<div class="cart-heading cart-heading-bold">Enhance Your Trip - Book Your Activities Now</div>
	</div>
	<div class="activities-push-wrapper cf">
		<div class="activities-push">
			<a href="http://www.palmettodunes.com/golf/hilton-head-golf/">
				<span class="activities-push-heading">Play Golf at Palmetto Dunes</span>
				<span class="activities-push-image">
					<img src="/templates/main/images/temp/activities-golf.jpg" alt="Play Golf at Palmetto Dunes"/>
				</span>
				<span class="blue-btn">Learn More</span>
			</a>
		</div>
		<div class="activities-push">
			<a href="http://www.palmettodunes.com/activities/hilton-head-bike-rentals/">
				<span class="activities-push-heading">Bike Rentals and More</span>
				<span class="activities-push-image">
					<img src="/templates/main/images/temp/activities-cycling.jpg" alt="Bike Rentals and More"/>
				</span>
				<span class="blue-btn">Learn More</span>
			</a>
		</div>
		<div class="activities-push">
			<a href="http://www.palmettodunes.com/activities/hilton-head-canoes-kayaks/">
				<span class="activities-push-heading">Kayak on the Lagoon</span>
				<span class="activities-push-image">
					<img src="/templates/main/images/temp/activities-kayaking.jpg" alt="Kayak on the Lagoon"/>
				</span>
				<span class="blue-btn">Learn More</span>
			</a>
		</div>
	</div>
</xsl:template>

<xsl:template name="replace-string">
	<xsl:param name="text"/>
	<xsl:param name="replace"/>
	<xsl:param name="with"/>
	<xsl:choose>
		<xsl:when test="contains($text,$replace)">
			<xsl:value-of select="substring-before($text,$replace)"/>
			<xsl:value-of select="$with"/>
			<xsl:call-template name="replace-string">
				<xsl:with-param name="text" select="substring-after($text,$replace)"/>
				<xsl:with-param name="replace" select="$replace"/>
				<xsl:with-param name="with" select="$with"/>
			</xsl:call-template>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="$text"/>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>
<xsl:template name="livechat">
	<script type="text/javascript">
	<![CDATA[
		var __lc = {};
		__lc.license = 6344501;
		window.__lc.chat_between_groups = false;
		(function() {
			var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
			lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
		})();
	]]>
	</script>
</xsl:template>
<xsl:template name="url-encode">
	<xsl:param name="str"/>
	<xsl:variable name="hex" select="'0123456789ABCDEF'"/>
	<xsl:variable name="ascii"> !"#$%&amp;'()*+,-./0123456789:;&lt;=&gt;?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~</xsl:variable>
	<xsl:variable name="safe">!'()*-.0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz~</xsl:variable>
	<xsl:variable name="latin1">&#160;&#161;&#162;&#163;&#164;&#165;&#166;&#167;&#168;&#169;&#170;&#171;&#172;&#173;&#174;&#175;&#176;&#177;&#178;&#179;&#180;&#181;&#182;&#183;&#184;&#185;&#186;&#187;&#188;&#189;&#190;&#191;&#192;&#193;&#194;&#195;&#196;&#197;&#198;&#199;&#200;&#201;&#202;&#203;&#204;&#205;&#206;&#207;&#208;&#209;&#210;&#211;&#212;&#213;&#214;&#215;&#216;&#217;&#218;&#219;&#220;&#221;&#222;&#223;&#224;&#225;&#226;&#227;&#228;&#229;&#230;&#231;&#232;&#233;&#234;&#235;&#236;&#237;&#238;&#239;&#240;&#241;&#242;&#243;&#244;&#245;&#246;&#247;&#248;&#249;&#250;&#251;&#252;&#253;&#254;&#255;</xsl:variable>
	<xsl:if test="$str">
		<xsl:variable name="first-char" select="substring($str,1,1)"/>
		<xsl:choose>
			<xsl:when test="contains($safe,$first-char)">
				<xsl:value-of select="$first-char"/>
			</xsl:when>
			<xsl:otherwise>
				<xsl:variable name="codepoint">
					<xsl:choose>
						<xsl:when test="contains($ascii,$first-char)">
							<xsl:value-of select="string-length(substring-before($ascii,$first-char)) + 32"/>
						</xsl:when>
						<xsl:when test="contains($latin1,$first-char)">
							<xsl:value-of select="string-length(substring-before($latin1,$first-char)) + 160"/>
						</xsl:when>
						<xsl:otherwise>
							<xsl:message terminate="no">Warning: string contains a character that is out of range! Substituting "?".</xsl:message>
							<xsl:text>63</xsl:text>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				<xsl:variable name="hex-digit1" select="substring($hex,floor($codepoint div 16) + 1,1)"/>
				<xsl:variable name="hex-digit2" select="substring($hex,$codepoint mod 16 + 1,1)"/>
				<xsl:value-of select="concat('%',$hex-digit1,$hex-digit2)"/>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:if test="string-length($str) &gt; 1">
			<xsl:call-template name="url-encode">
				<xsl:with-param name="str" select="substring($str,2)"/>
			</xsl:call-template>
		</xsl:if>
	</xsl:if>
</xsl:template>

<xsl:template name="tracking">
	<xsl:variable name="arrival-search" select="//pageinfo/querystring/arrive_submit"/> 
    <xsl:variable name="departure-search" select="//pageinfo/querystring/depart_submit"/>
    <xsl:variable name="accomm-type" select="//pageinfo/querystring/typeid"/>
    <xsl:variable name="accomm-view" select="//pageinfo/querystring/viewid"/>
    <xsl:variable name="accom-location" select="//pageinfo/querystring/locationid"/>
    <xsl:variable name="accomm-rooms" select="//pageinfo/querystring/bedrooms"/>
    <xsl:variable name="accomm-sleeps" select="//pageinfo/querystring/sleeps"/>
    <xsl:variable name="accomm-promocode" select="//pageinfo/querystring/promocode"/>
           
	<xsl:choose>
		<xsl:when test="//system/activeSection1/@value='hilton-head-vacation-rentals'">

			<script>
				dataLayer = [];
				dataLayer.push({
					'VRHotelId': '80',
					'VRArrivalSearch': '<xsl:value-of select="$arrival-search"/>',
					'VRDepartureSearch': '<xsl:value-of select="$departure-search"/>',
					'VRAccommodationType': '<xsl:value-of select="$accomm-type"/>',
					'VRAccommodationView': '<xsl:value-of select="$accomm-view"/>',
					'VRAccommodationLocation': '<xsl:value-of select="$accom-location"/>',
					'VRAccommodationRoomQty': '<xsl:value-of select="$accomm-rooms"/>',
					'VRAccommodationSleepsQty': '<xsl:value-of select="$accomm-sleeps"/>',
					'VRAccommodationPromoCode': '<xsl:value-of select="$accomm-promocode"/>',
					'VRBookingEngineStep': 'VR/booking-engine/check-availability',
					'gtm.blacklist': ['fsl', 'tl', 'jel', 'hl']
				});
			</script>
			<!-- End Google Tag Manager Data Layer --> 


			<!-- Google Tag Manager -->
			<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WRZVG6"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&amp;l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-WRZVG6');</script>
			<!-- End Google Tag Manager -->

		</xsl:when>
		<xsl:when test="//system/activeSection1/@value='hilton-head-rental-units'">

			<!-- Google Tag Manager Data Layer -->
			<script>
                dataLayer = [];
                dataLayer.push({
                    'VRHotelId': '80',
                    'VRArrivalSearch': '<xsl:value-of select="$arrival-search"/>',
					'VRDepartureSearch': '<xsl:value-of select="$departure-search"/>',
					'VRAccommodationType': '<xsl:value-of select="$accomm-type"/>',
					'VRAccommodationView': '<xsl:value-of select="$accomm-view"/>',
					'VRAccommodationLocation': '<xsl:value-of select="$accom-location"/>',
					'VRAccommodationRoomQty': '<xsl:value-of select="$accomm-rooms"/>',
					'VRAccommodationSleepsQty': '<xsl:value-of select="$accomm-sleeps"/>',
					'VRAccommodationPromoCode': '<xsl:value-of select="$accomm-promocode"/>',
                    'VRBookingEngineStep': 'VR/booking-engine/unit-detail',
					'gtm.blacklist': ['fsl', 'tl', 'jel', 'hl']
                    });
            </script>
            <!-- End Google Tag Manager Data Layer --> 


			<!-- Google Tag Manager -->
			<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WRZVG6"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&amp;l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-WRZVG6');</script>
			<!-- End Google Tag Manager -->

		</xsl:when>
		<xsl:when test="//pageinfo/@id = 'userinfo'">

			<!-- Google Tag Manager Data Layer -->
			<script>
                dataLayer = [];
                dataLayer.push({
                    'VRHotelId': '80',
                    'VRArrivalSearch': '<xsl:value-of select="$arrival-search"/>',
					'VRDepartureSearch': '<xsl:value-of select="$departure-search"/>',
					'VRAccommodationType': '<xsl:value-of select="$accomm-type"/>',
					'VRAccommodationView': '<xsl:value-of select="$accomm-view"/>',
					'VRAccommodationLocation': '<xsl:value-of select="$accom-location"/>',
					'VRAccommodationRoomQty': '<xsl:value-of select="$accomm-rooms"/>',
					'VRAccommodationSleepsQty': '<xsl:value-of select="$accomm-sleeps"/>',
					'VRAccommodationPromoCode': '<xsl:value-of select="$accomm-promocode"/>',
					'VRBookingEngineStep': 'VR/booking-engine/customer-information',
					'gtm.blacklist': ['fsl', 'tl', 'jel', 'hl']
					});  
			</script>
			<!-- End Google Tag Manager Data Layer -->


			<!-- Google Tag Manager -->
			<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WRZVG6"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&amp;l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-WRZVG6');</script>
			<!-- End Google Tag Manager -->

		</xsl:when>
		<xsl:when test="//pageinfo/@id = 'paymentinfo'">

			<!-- Google Tag Manager Data Layer -->
			<script>
                dataLayer = [];
                dataLayer.push({
                    'VRHotelId': '80',
                    'VRArrivalSearch': '<xsl:value-of select="$arrival-search"/>',
					'VRDepartureSearch': '<xsl:value-of select="$departure-search"/>',
					'VRAccommodationType': '<xsl:value-of select="$accomm-type"/>',
					'VRAccommodationView': '<xsl:value-of select="$accomm-view"/>',
					'VRAccommodationLocation': '<xsl:value-of select="$accom-location"/>',
					'VRAccommodationRoomQty': '<xsl:value-of select="$accomm-rooms"/>',
					'VRAccommodationSleepsQty': '<xsl:value-of select="$accomm-sleeps"/>',
					'VRAccommodationPromoCode': '<xsl:value-of select="$accomm-promocode"/>',
					'VRBookingEngineStep': 'VR/booking-engine/payment',
					'gtm.blacklist': ['fsl', 'tl', 'jel', 'hl']
					});  
			</script>
			<!-- End Google Tag Manager Data Layer -->


			<!-- Google Tag Manager -->
			<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WRZVG6"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&amp;l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-WRZVG6');</script>
			<!-- End Google Tag Manager -->

		</xsl:when>
		<xsl:when test="//pageinfo/@id = 'complete'">
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
					<xsl:when test="//module/bookingcart/data/booking/rate/record[@id = '2']/amount &gt; 0 or //module/bookingcart/data/booking/rate/record[@id = '2']/amount &lt; 0 ">
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
				<!-- Google Tag Manager Data Layer -->
				<script>
				dataLayer = [{
				    'VRHotelId': '80',
				    'VRBookingEngineStep': 'VR/booking-engine/booking-confirmation',
				    'transactionId': 'VR - <xsl:value-of select="//module/bookingcart/data/booking/confirmation"/>',
				    'transactionTotal': '<xsl:value-of select="format-number($total, '#.00')"/>',
				    'VRTransactionUnitId': '<xsl:value-of select="//module/bookingcart/data/booking/@propertyid"/>',
				    'VRTransactionCCity': '<xsl:value-of select="//module/bookingcart/data/address/city"/>',
				    'VRTransactionCState': '<xsl:value-of select="//module/bookingcart/data/address/state"/>',
				    'VRTransactionCCountry': '<xsl:value-of select="//module/bookingcart/data/address/country"/>',
				    'VRTransactionNightsQty': '<xsl:value-of select="substring-before(substring-after($nights,'P'),'D')"/>',
				    'VRTransactionRoomName': '<xsl:value-of select="//module/bookingcart/data/property/name"/>',
				    'VRTransactionRateName': '<xsl:value-of select="//module/bookingcart/data/booking/rateplan"/>',
				    'VRTransactionDailyRate': '<xsl:value-of select="//module/bookingcart/data/booking/@dailyrate"/>',
				    'transactionProducts': [{
				        'sku': 'VR - <xsl:value-of select="//module/bookingcart/data/property/owsid"/> - <xsl:value-of select="//module/bookingcart/data/booking/rateplan"/> - <xsl:value-of select="//module/bookingcart/data/promocode"/>',
				        'name': 'VR - <xsl:value-of select="//module/bookingcart/data/property/name"/>',
				        'category': 'VR - <xsl:value-of select="//module/bookingcart/data/booking/rateplan"/>',
				        'price': '<xsl:value-of select="//module/bookingcart/data/booking/@dailyrate"/>',
				        'quantity': '<xsl:value-of select="substring-before(substring-after($nights,'P'),'D')"/>'
				    }]
				}];
				</script>
				<!-- End Google Tag Manager Data Layer -->

				<!-- Google Tag Manager -->
				<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WRZVG6"
				height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
				<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&amp;l='+l:'';j.async=true;j.src=
				'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','GTM-WRZVG6');</script>
				<!-- End Google Tag Manager -->
				<!--
				Start of DoubleClick Floodlight Tag: Please do not remove
				Activity name of this tag: Conversions
				URL of the webpage where the tag is expected to be placed: https://www.palmettodunes.com/
				This tag must be placed between the <body> and </body> tags, as close as possible to the opening tag.
				Creation Date: 06/05/2017
				-->
				<iframe src="https://3258938.fls.doubleclick.net/activityi;src=3258938;type=conve183;cat=conve628;qty=1;cost={format-number($total, '#.00')};u1={//pageinfo/querystring/bedrooms};u2={substring-before(substring-after($nights,'P'),'D')};u3={format-number($total, '#.00')};dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;ord={//module/bookingcart/data/booking/confirmation}?" width="1" height="1" frameborder="0" style="display:none"></iframe>
				<!-- End of DoubleClick Floodlight Tag: Please do not remove -->
		</xsl:when>
		<xsl:otherwise></xsl:otherwise>
	</xsl:choose>
</xsl:template>
