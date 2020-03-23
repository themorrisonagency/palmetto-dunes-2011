<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">	
    <xsl:output method="html" indent="yes" />
	<xsl:template match="/">
	<![CDATA[<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<!--[if lt IE 7]> <html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml"> <![endif]-->
		<!--[if IE 7]>    <html lang="en" class="no-js lt-ie9 lt-ie8" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml"> <![endif]-->
		<!--[if IE 8]>    <html lang="en" class="no-js lt-ie9" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml"> <![endif]-->
		<!--[if gt IE 8]><!-->]]><html lang="en" class="no-js" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml"><![CDATA[ <!--<![endif]-->]]>
		<head>
			<meta charset="utf-8" />
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<xsl:value-of select="//output/content/meta" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	
			<xsl:call-template name="css" />
			<xsl:call-template name="rss-alt-links" />
			<xsl:call-template name="js" />
            <link rel="apple-touch-icon" href="{//system/activeTemplatePathRel/@value}images/icon.png"/>
    
        </head>
        <body class="index nav">
			<div id="masthead">
				<xsl:value-of select="//output/content/masthead" />
			</div>
			<h1 class="branding">
                <a href="{//system/siteRelRoot/@value}"><img src="{//system/activeTemplatePathRel/@value}images/logo.png" alt="{//info/address/@title}" border="0" /></a>
			</h1>
            <section class="wrapper">
                <article id="nav">
                    <header>
                        <nav><a href="./" class="back">Home</a></nav>
                    </header>
                    <nav class="nav-primary">
                        <xsl:apply-templates select="//output/nav" mode="primary"/>
                    </nav>
                </article>
            </section>		
            <div id="nav_sub">
            	<xsl:apply-templates select="//output/nav/page" mode="secondary"/>
            </div>				
            <xsl:call-template name="footer-js" />           
		</body>
	</html>
	</xsl:template>	
	<caliban import="activeTemplatePathAbs,includes/navigation.xsl" />	
	<caliban import="activeTemplatePathAbs,includes/css.xsl" />	
	<caliban import="activeTemplatePathAbs,includes/js-header.xsl" />	
	<caliban import="activeTemplatePathAbs,includes/js-footer.xsl" />	
	<caliban import="activeTemplatePathAbs,includes/footer.xsl" />	
	<caliban import="activeTemplatePathAbs,includes/includes.xsl" />	
</xsl:stylesheet>
