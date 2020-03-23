<xsl:template name="footer-js">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"><![CDATA[]]></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"><![CDATA[]]></script>
    <script src="/templates/{//system/activeTemplatePath/@value}js/site.all.all.js"><![CDATA[]]></script>
	<script>
		var isMobileSearch = (Modernizr.touch &amp;&amp; window.innerWidth &lt; 768 &amp;&amp; $('body').hasClass('b-search-results'));
	</script>

    <xsl:if test="//system/activeLang/@value != 'en'">
		<!-- Debug block -->
		<!-- <script src="/templates/{//system/activeTemplatePath/@value}js/datepicker/ui.datepicker-{caliban:system:activeLang}.js"><![CDATA[]]></script> -->
		<!-- Minify block -->
		<!--<script src="/min/{//system/activeTemplatePath/@value}js/datepicker/ui.datepicker-{caliban:system:activeLang}.js&amp;001"><![CDATA[]]></script>-->
    </xsl:if>

	<xsl:choose>
		<xsl:when test="//output/content/mapvendor = 'google' and //pageinfo/@id = 'map'">
			<!-- <script src="/templates/{//system/activeTemplatePath/@value}js/jqModal.js"><![CDATA[]]></script> -->
			<script src="//maps.googleapis.com/maps/api/js?v=3&amp;key=AIzaSyARJ73kkj3nvoEmZut-EbK_AFUoSW_g1rw&amp;libraries=places&amp;sensor=false&amp;language={caliban:system:activeLang}"><![CDATA[]]></script>
			<!-- Debug block -->
			<script src="/templates/{//system/activeTemplatePath/@value}js/maps/jquery-uniqueArray.min.js"><![CDATA[]]></script>
			<script src="/templates/{//system/activeTemplatePath/@value}js/maps/infobox.js"><![CDATA[]]></script>
			<script src="/templates/{//system/activeTemplatePath/@value}js/maps/richmarker-compiled.js"><![CDATA[]]></script>
			<script src="/templates/{//system/activeTemplatePath/@value}js/maps/jquery-map-basic-google.js"><![CDATA[]]></script>
			<script src="/templates/{//system/activeTemplatePath/@value}js/maps/settings-googleBasic.js"><![CDATA[]]></script>
			<!-- Minify block -->
			<!--<script src="/min/{//system/activeTemplatePath/@value}js/maps/jquery-uniqueArray.min.js,infobox.js,richmarker-compiled.js,jquery-map-basic-google.js,settings-googleBasic.js"></script>-->
		</xsl:when>
		
		<xsl:when test="//output/content/mapvendor = 'google'">
			<!-- <script src="/templates/{//system/activeTemplatePath/@value}js/jqModal.js"><![CDATA[]]></script> -->
			
			<script src="//maps.googleapis.com/maps/api/js?v=3&amp;key=AIzaSyARJ73kkj3nvoEmZut-EbK_AFUoSW_g1rw&amp;libraries=places&amp;sensor=false&amp;language={caliban:system:activeLang}"><![CDATA[]]></script>
			<!-- Debug block -->
			<script>
				if (isMobileSearch) {
					$('#map-toggle, #map-outer-wrapper').remove();
				} else {
					Modernizr.load('/templates/<xsl:value-of select="//system/activeTemplatePath/@value"/>js/maps/jquery-uniqueArray.min.js');
					Modernizr.load('/templates/<xsl:value-of select="//system/activeTemplatePath/@value"/>js/maps/infobox.js');
					Modernizr.load('/templates/<xsl:value-of select="//system/activeTemplatePath/@value"/>js/maps/richmarker-compiled.js');
					Modernizr.load('/templates/<xsl:value-of select="//system/activeTemplatePath/@value"/>js/maps/markerclusterer.js');
					Modernizr.load('/templates/<xsl:value-of select="//system/activeTemplatePath/@value"/>js/maps/jquery-poi-google.js');
					Modernizr.load('/templates/<xsl:value-of select="//system/activeTemplatePath/@value"/>js/maps/settings.js');
				}
			</script>
			<!-- Minify block -->
			<!--<script src="/min/{//system/activeTemplatePath/@value}js/maps/jquery-uniqueArray.min.js,infobox.js,richmarker-compiled.js,jquery-poi-google.js,settings.js"></script>-->
		</xsl:when>
	</xsl:choose>

	<xsl:choose>
		<xsl:when test="//output/content/mapvendor = 'bing' and //pageinfo/@id = 'map'">
			<!-- this is for the Basic Bing Map -->
			<script src="//ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0&amp;c={caliban:system:activeLang}"></script>

			<!-- Debug block -->
			<script src="/templates/{//system/activeTemplatePath/@value}js/maps/jquery-uniqueArray.min.js"><![CDATA[]]></script>
			<script src="/templates/{//system/activeTemplatePath/@value}js/maps/jquery-poi-basic-bing.js"><![CDATA[]]></script>
			<!-- Minify block -->
			<!--<script src="/min/{//system/activeTemplatePath/@value}js/maps/jquery-uniqueArray.min.js,jquery-poi-basic-bing.js&amp;001"><![CDATA[]]></script>-->
		</xsl:when>
	    <xsl:when test="//output/content/mapvendor = 'bing'">
			<!-- <script src="/templates/{//system/activeTemplatePath/@value}js/jqModal.js"><![CDATA[]]></script> -->
			<script src="//ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0&amp;c={caliban:system:activeLang}"></script>

			<!-- Debug block -->
			<script src="/templates/{//system/activeTemplatePath/@value}js/maps/jquery-uniqueArray.min.js"><![CDATA[]]></script>
			<script src="/templates/{//system/activeTemplatePath/@value}js/maps/jquery-poi-bing.js"><![CDATA[]]></script>
			<script src="/templates/{//system/activeTemplatePath/@value}js/maps/settings.js"><![CDATA[]]></script>
			<!-- Minify block -->
			<!--<script src="/min/{//system/activeTemplatePath/@value}js/maps/jquery-uniqueArray.min.js,jquery-poi-bing.js,settings.js&amp;001"><![CDATA[]]></script>-->
	    </xsl:when>
	</xsl:choose>

	<!-- Debug block -->
	<script src="/templates/{//system/activeTemplatePath/@value}js/main.js"><![CDATA[]]></script>
	<script src="/templates/{//system/activeTemplatePath/@value}js/i18n/{caliban:system:activeLang}.js"><![CDATA[]]></script>
	<script src="/templates/{//system/activeTemplatePath/@value}js/classie.js"><![CDATA[]]></script>
	<script src="/templates/{//system/activeTemplatePath/@value}js/cbpAnimatedHeader.js"><![CDATA[]]></script>
	<script src="/templates/{//system/activeTemplatePath/@value}js/jquery.easydropdown.min.js"><![CDATA[]]></script>
	<script src="/templates/{//system/activeTemplatePath/@value}js/jquery.cycle2.min.js"><![CDATA[]]></script>
    <script src="/templates/{//system/activeTemplatePath/@value}js/jquery.cycle2.carousel.js"><![CDATA[]]></script>
	<script src="/templates/{//system/activeTemplatePath/@value}js/jquery.cycle2.tile.js"><![CDATA[]]></script>
	<script src="/templates/{//system/activeTemplatePath/@value}js/jPages.js"><![CDATA[]]></script>
	<script src="/templates/{//system/activeTemplatePath/@value}js/jquery.fancybox.js"><![CDATA[]]></script>
	<script src="/templates/{//system/activeTemplatePath/@value}js/jquery.cookie.js"><![CDATA[]]></script>
	<script src="/templates/{//system/activeTemplatePath/@value}js/special.js?v=2"><![CDATA[]]></script>
    
	
<!--	<script src="/templates/{//system/activeTemplatePath/@value}js/gallery/xml2json.js"><![CDATA[]]></script>
    <script src="/templates/{//system/activeTemplatePath/@value}js/gallery/galleria.sabre.js"><![CDATA[]]></script>
    <script src="/templates/{//system/activeTemplatePath/@value}js/gallery/special-gallery.js"><![CDATA[]]></script>
    <script src="/templates/{//system/activeTemplatePath/@value}js/gallery/galleria.js"><![CDATA[]]></script>-->
	<!-- Minify block -->
	<!--<script src="/min/{//system/activeTemplatePath/@value}js/main.js,i18n/{caliban:system:activeLang}.js,special.js&amp;001"><![CDATA[]]></script>-->

	<xsl:if test="//system/activeSection1/@value='hilton-head-rental-units' or //system/activeSection1/@value='hilton-head-vacation-rentals'">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
		<script src="/templates/{//system/activeTemplatePath/@value}js/rental-calendar.js"><![CDATA[]]></script>
	</xsl:if>
	<xsl:if test="//pageinfo/@id = 'gallery' or //pageinfo/@id = 'embedded-gallery'">
		<script src="/templates/{//system/activeTemplatePath/@value}js/gallery/galleria.js,xml2json.js,galleria.sabre.js,special-gallery.js&amp;001"><![CDATA[]]></script>
	</xsl:if>
    
<xsl:choose>
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

		<xsl:variable name="confirmation" select="//module/bookingcart/data/booking/confirmation"/>
    	<script type="text/javascript">SendNavisConfirmationNumber("<xsl:value-of select="$confirmation"/>");</script>
    
    <!-- Google Code for Booking Conversion Page -->
	<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 1000613885;
    var google_conversion_language = "en";
    var google_conversion_format = "3";
    var google_conversion_color = "ffffff";
    var google_conversion_label = "4MZvCPOqhgUQ_c-Q3QM";
    var google_remarketing_only = false;
    /* ]]> */
    </script>
    <script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
    </script>
    <noscript>
    <div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1000613885/?label=4MZvCPOqhgUQ_c-Q3QM&amp;guid=ON&amp;script=0"/>
    </div>
    </noscript>
    
    
    
    	<!-- Google Code for Booking Conversion Page -->
		<script type="text/javascript">
			/* <![CDATA[ */
			var google_conversion_id = 1000613885;
			var google_conversion_language = "en";
			var google_conversion_format = "3";
			var google_conversion_color = "ffffff";
			var google_conversion_label = "4MZvCPOqhgUQ_c-Q3QM";
			var google_conversion_value = %JS_TotalCost%;
			var google_conversion_currency = "USD";
			var google_remarketing_only = false;
			/* ]]> */
		</script>
		<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
		</script>
		<noscript>
		<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1000613885/?value=1.00&amp;currency_code=USD&amp;label=4MZvCPOqhgUQ_c-Q3QM&amp;guid=ON&amp;script=0"/>
		</div>
        </noscript>
        
        <!--
        Start of DoubleClick Floodlight Tag: Please do not remove
        Activity name of this tag: Conversions
        URL of the webpage where the tag is expected to be placed: https://www.palmettodunes.com/
        This tag must be placed between the <body> and </body> tags, as close as possible to the opening tag.
        Creation Date: 09/18/2014
        -->
        <iframe src="https://3258938.fls.doubleclick.net/activityi;src=3258938;type=conve183;cat=conve628;qty=1;cost=[Revenue];u1=[number of rooms];u2=[number of nights];u3=[revenue];ord=[OrderID]?" width="1" height="1" frameborder="0" style="display:none"></iframe>
        <!-- End of DoubleClick Floodlight Tag: Please do not remove -->
		
		<script type="text/javascript">
			adroll_conversion_value_in_dollars = '<xsl:value-of select="format-number($total, '#.00')"/>';
			adroll_adv_id = "API2XN4LPVAP7DO67HGTZ4";
			adroll_pix_id = "K56HXQWN3BATLABHESNSQ6";
			(function () {
			var oldonload = window.onload;
			window.onload = function(){
			   __adroll_loaded=true;
			   var scr = document.createElement("script");
			   var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
			   scr.setAttribute('async', 'true');
			   scr.type = "text/javascript";
			   scr.src = host + "/j/roundtrip.js";
			   ((document.getElementsByTagName('head') || [null])[0] ||
				document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
			   if(oldonload){oldonload()}};
			}());
		</script>
		<script type="text/javascript">
        	adroll_segments = "success"
        </script>
    </xsl:when>
    <xsl:otherwise>
    	<script type="text/javascript">
			adroll_adv_id = "API2XN4LPVAP7DO67HGTZ4";
			adroll_pix_id = "K56HXQWN3BATLABHESNSQ6";
				(function () {
				var oldonload = window.onload;
				window.onload = function(){
					__adroll_loaded=true;   
					var scr = document.createElement("script");   
					var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");   
					scr.setAttribute('async', 'true');   scr.type = "text/javascript";   
					scr.src = host + "/j/roundtrip.js";   
					((document.getElementsByTagName('head') || [null])[0] ||    
					document.getElementsByTagName('script')[0].parentNode).appendChild(scr);   
					if(oldonload){oldonload()}};
				}());
    	</script>
    </xsl:otherwise>
</xsl:choose>

	<script>
		if (!isMobileSearch) {
			Modernizr.load('https://apis.google.com/js/plusone.js'); <!-- G+ -->
			Modernizr.load('https://platform.twitter.com/widgets.js'); <!-- Twitter -->
			<!-- Pinterest -->
			(function(d){
			    var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
			    p.type = 'text/javascript';
			    p.async = true;
			    p.src = 'https://assets.pinterest.com/js/pinit.js';
			    f.parentNode.insertBefore(p, f);
			}(document));
		}
	</script>

</xsl:template>