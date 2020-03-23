<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml" version="1.0" xmlns:date="http://exslt.org/dates-and-times" xmlns:math="http://exslt.org/math" extension-element-prefixes="math">
	<xsl:output method="html" indent="yes"/>
	<xsl:param name="lang" select="//pageinfo/@language"/>
	<xsl:preserve-space elements="*" />
	<xsl:strip-space elements="h1 h2 h3 h4 h5" />
	<xsl:template match="/">
<![CDATA[<!DOCTYPE html>
<!--[if lt IE 7]><html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 7]><html lang="en" class="no-js lt-ie9 lt-ie8" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 8]><html lang="en" class="no-js lt-ie9" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if gt IE 8]><!-->]]><html lang="en" class="no-js" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml"><![CDATA[ <!--<![endif]-->]]>
<head>
<meta charset="utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="twitter:widgets:csp" content="on" />
<xsl:choose>
	<xsl:when test="//system/activeSection1/@value='hilton-head-rental-units'">
		<xsl:call-template name="opengraph-unit-photos-overview" />
	</xsl:when>
	<xsl:when test="//module/propdetails/data">
		<xsl:call-template name="opengraph-unit" />
	</xsl:when>
	<xsl:otherwise>
		<xsl:value-of select="//output/content/meta" />
	</xsl:otherwise>
</xsl:choose>
<xsl:call-template name="css" />
<xsl:call-template name="rss-alt-links" />
<xsl:call-template name="js" />
<script type="text/javascript" src="https://www.navistechnologies.info/JavascriptPhoneNumber/js.aspx?account=15521&amp;jspass=r4wbe0wmz555swj4p02c&amp;dflt=8889099566"></script>
<script type="text/javascript">ProcessNavisNCKeyword5(".palmettodunes.com", true, false, false, 90);</script>
</head>
<body class="interior {//output/content/class} sticky-head">
	<xsl:call-template name="tracking" />
	<xsl:variable name="template-root"><xsl:text>/templates/main</xsl:text></xsl:variable>
	<div id="wrapper">
	<div id="fb-root" style="display:none;"></div>
	    <script>
	        (function(d, s, id) {
	            var js, fjs = d.getElementsByTagName(s)[0];
	            if (d.getElementById(id)) return;
	            js = d.createElement(s); js.id = id;
	            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&#38;appId=138365476186446";
	            fjs.parentNode.insertBefore(js, fjs);
	        }(document, 'script', 'facebook-jssdk'));
	    </script>
		<!--<xsl:call-template name="facebook-setup" />-->
		<xsl:variable name="resultcount">
			<xsl:value-of select="count(//module/reservations/data/record)" />
		</xsl:variable>
		<div class="page-wrapper resultcount-{$resultcount}">
			<div id="top"></div>
			<xsl:if test="//system/activeSection1/@value='hilton-head-vacation-rentals'">
				<div class="primary-header">
					<h1><xsl:value-of select="//heading" /></h1>
				</div>
				<div class="breadcrumb">
					<xsl:call-template name="breadcrumb"/>
          <p style="padding-top: 15px; padding-left: 20px; padding-right: 20px;">
            For accurate pricing for stays longer than 7 nights, please call us at <a href="tel:888-909-9566">888-909-9566</a>. We are sorry for any inconvenience.
          </p>
				</div>
			</xsl:if>
            <xsl:call-template name="livechat"/>
			<div class="content-wrapper">
				<div class="main-content">
				<!--Temporary Error Message when Promocode is active. 
	    		Will only show if system-errors isn't showing.
				<div class="promo-errors" id="temp-promo-error" style="display: none;">Promotional codes are not currently working as expected on our website. Please call <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span> for booking assistance.</div> -->
					<!-- <xsl:call-template name="topNote" /> -->
					<xsl:apply-templates select="//output/pageinfo/messages" />

					<xsl:if test="//system/activeSection1/@value='hilton-head-vacation-rentals'">
						<xsl:call-template name="search-panel" />
						<xsl:call-template name="search-results-header" />
						<xsl:call-template name="search-map" />
						<xsl:call-template name="search-results-list" />
					</xsl:if>
					<xsl:if test="//system/activeSection1/@value='hilton-head-rental-units'">
						<xsl:call-template name="unit-header" />
						<xsl:call-template name="photos-overview" />
						<xsl:call-template name="rates-availability" />
						<xsl:call-template name="unit-map" />
						<!--<xsl:call-template name="similar-units" />-->
					</xsl:if>
					<!-- includes below in booking.xsl unless indicated otherwise -->
					<xsl:if test="//pageinfo/@id = 'userinfo'">
						<xsl:call-template name="book-header"/>
						<xsl:call-template name="book-details-pricing"/>
						<xsl:choose>
                        	<xsl:when test="//bookingcart/data/property/@wholesale='1'">
                            	<xsl:call-template name="promo-guestcontact"/>
                            </xsl:when>
                            <xsl:otherwise>
                            	<xsl:call-template name="guestcontact"/>
                            </xsl:otherwise>
                    	</xsl:choose>
					</xsl:if>
                    <xsl:if test="//pageinfo/@id = 'paymentinfo'">
						<xsl:call-template name="book-header"/>
						<xsl:call-template name="book-details-pricing"/>
						<xsl:call-template name="paymentinfo"/>
					</xsl:if>
					<xsl:if test="//pageinfo/@id = 'complete'">
                    	<xsl:call-template name="book-details-pricing"/>
						<xsl:choose>
                        	<xsl:when test="//bookingcart/data/property/@wholesale='1'">
                            	<xsl:call-template name="promo-guestinfo"/>
                        		<xsl:call-template name="promo-reservationist"/>
                            </xsl:when>
                            <xsl:otherwise>
                            	<xsl:call-template name="guestinfo"/>
                            </xsl:otherwise>
                    	</xsl:choose>
                        <xsl:call-template name="activities-push"/><!-- in includes.xsl -->
					</xsl:if>
				</div>
			</div>

			<xsl:if test="//system/activeSection1/@value='hilton-head-vacation-rentals'">
				<xsl:call-template name="signupcontent" /><!-- temp? pull from amaxus side if/when possible -->
				<xsl:call-template name="crosspromocontent" /><!-- temp? pull from amaxus side if/when possible -->
			</xsl:if>

		</div><!-- /.page-wrapper -->
		
        <xsl:call-template name="amaxusNav" />
		<!--<xsl:call-template name="amaxusNavStatic" />-->
		<div class="sec-nav">
			<xsl:apply-templates select="//output/nav" mode="secondary"/>
		</div>
	</div><!-- /#wrapper -->

	<xsl:call-template name="footer" />
	<xsl:call-template name="footer-js" />


	
</body>
</html>

</xsl:template>
	<caliban import="activeTemplatePathAbs,includes/system.xsl" />
	<caliban import="activeTemplatePathAbs,includes/navigation.xsl" />
	<caliban import="activeTemplatePathAbs,includes/css.xsl" />
	<caliban import="activeTemplatePathAbs,includes/js-header.xsl" />
	<caliban import="activeTemplatePathAbs,includes/js-footer.xsl" />
	<caliban import="activeTemplatePathAbs,includes/footer.xsl" />
	<caliban import="activeTemplatePathAbs,includes/calendar.xsl" />
	<caliban import="activeTemplatePathAbs,includes/share.xsl" />
	<caliban import="activeTemplatePathAbs,includes/share-booking.xsl" />
	<caliban import="activeTemplatePathAbs,includes/map.xsl" />
	<caliban import="activeTemplatePathAbs,includes/rss.xsl" />
	<caliban import="activeTemplatePathAbs,includes/includes.xsl" />
	<caliban import="activeTemplatePathAbs,includes/gallery.xsl" />
    <caliban import="activeTemplatePathAbs,includes/weather.xsl" />
	<caliban import="activeTemplatePathAbs,includes/search-list.xsl" />
	<caliban import="activeTemplatePathAbs,includes/units.xsl" />
	<caliban import="activeTemplatePathAbs,includes/rental-calendar.xsl" />
	<caliban import="activeTemplatePathAbs,includes/booking.xsl" />
    <caliban import="activeTemplatePathAbs,includes/promo.xsl" />
    <caliban import="activeTemplatePathAbs,includes/virtual-tour.xsl" />
</xsl:stylesheet>
