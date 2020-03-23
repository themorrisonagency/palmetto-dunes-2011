<xsl:template name="css">
	<!-- Debug block -->
	<!--<link rel="stylesheet" href="http://www.palmettodunes.com/css/all.16.html.css" media="all" />-->
	<link rel="stylesheet" href="/templates/{//system/activeTemplatePath/@value}css/all.16.html.css?v.1" media="all" />
	<link rel="stylesheet" href="/templates/{//system/activeTemplatePath/@value}css/easydropdown.css" media="all" />
	<link rel="stylesheet" href="/templates/{//system/activeTemplatePath/@value}css/styles.css?v=2.2" media="all" />
	<link rel="stylesheet" href="/templates/{//system/activeTemplatePath/@value}css/calendar.css" media="screen" />
	<link rel="stylesheet" href="/templates/{//system/activeTemplatePath/@value}css/jquery.fancybox.css" media="screen" />
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600%7CCabin:400,600,700%7CLora:400,700" />
    <!--<link rel="stylesheet" href="/templates/{//system/activeTemplatePath/@value}css/gallery.css" media="screen" />-->
	<!-- Minify block -->
	<!--<link rel="stylesheet" href="/min/main/css/styles.css,calendar.css&amp;002" media="screen" />-->

	<xsl:if test="//pageinfo/@id = 'gallery' or //pageinfo/@id = 'embedded-gallery'">
		<link rel="stylesheet" href="/templates/{//system/activeTemplatePath/@value}css/gallery.css" media="screen" />
	</xsl:if>

	<!--<xsl:if test="//pageinfo/@id = 'complete'"> Need a working test on the confirmation page -->
		<link rel="stylesheet" type="text/css" media="print" href="/templates/{//system/activeTemplatePath/@value}css/print.css" />
	<!--</xsl:if>-->

	<link rel="stylesheet" href="/templates/{//system/activeTemplatePath/@value}css/pickadate.css" media="screen" />
</xsl:template>

<xsl:template name="rss-alt-links">
	<xsl:for-each select="//feed-links/feed">
	<link rel="alternate" type="application/rss+xml" title="{@title}" href="/{@url}/{@cid}/{@type}" />
	</xsl:for-each>
</xsl:template>

<xsl:template match="module" />
