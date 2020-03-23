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
</head>
<body class="interior {//output/content/class}">
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



		<div class="page-wrapper">
			<div class="primary-header">
				<h1>404 Page Not Found</h1>
			</div>
			<div class="breadcrumb">
				<xsl:call-template name="breadcrumb"/>
			</div>

			<div class="content-wrapper">
				<div class="main-content">
					<xsl:copy-of select="/output/content/copy" />
				</div>
			</div>

		</div><!-- /.page-wrapper -->

		<xsl:call-template name="amaxusNav" />
		<div class="sec-nav">
			<xsl:apply-templates select="//output/nav" mode="secondary"/>
		</div>
	</div><!-- /#wrapper -->
	<xsl:call-template name="footer" />
	<xsl:call-template name="footer-js" />
</body>
</html>
	</xsl:template>
	<caliban import="activeTemplatePathAbs,includes/navigation.xsl" />
	<caliban import="activeTemplatePathAbs,includes/css.xsl" />
	<caliban import="activeTemplatePathAbs,includes/js-header.xsl" />
	<caliban import="activeTemplatePathAbs,includes/js-footer.xsl" />
	<caliban import="activeTemplatePathAbs,includes/footer.xsl" />
	<caliban import="activeTemplatePathAbs,includes/booking-console.xsl" />
	<caliban import="activeTemplatePathAbs,includes/weather.xsl" />
</xsl:stylesheet>
