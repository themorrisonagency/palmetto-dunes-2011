<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml" xmlns:func="http://exslt.org/functions" xmlns:util="http://sabrehospitality.com/xslt/util" extension-element-prefixes="func util" version="1.0" xmlns:date="http://exslt.org/dates-and-times">
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
			<xsl:value-of select="//output/content/meta" />
			<xsl:call-template name="css" />
			<xsl:call-template name="rss-alt-links" />
			<xsl:call-template name="js" />
			</head>
		<body class="home sticky-head">
		<xsl:call-template name="tracking" />
		<div id="wrapper">
			<div id="header">
				<div id="branding">
					<a href="{//siteRel/@value}"><img src="{//system/activeTemplatePathRel/@value}images/logo.png" alt="Raynor" border="0" /></a>
				</div>
                <div class="languages">
                    <xsl:apply-templates select="//pageinfo" mode="language" />
                </div>
			</div>
			<div id="content-wrapper">
				<div id="content">
					<h1><xsl:value-of select="//output/content/heading" /></h1>
					<h2><xsl:value-of select="subheading" /></h2>
					<xsl:variable name="inset" select="//output/content/inset" />
					<xsl:if test="string-length($inset)>0">
						<div id="inset">
							<xsl:value-of select="$inset" />
						</div>
					</xsl:if>
					<xsl:value-of select="//output/content/copy" />
				</div>
				<div id="sidebar">
					<div id="nav">
						<xsl:apply-templates select="//output/nav" mode="full"/>
					</div>
				</div>
			</div>
			<div id="booking-console">
				<xsl:call-template name="booking-console" />
			</div>
			<xsl:call-template name="footer" />
			<xsl:call-template name="footer-js" />
		</div>
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
