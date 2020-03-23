<xsl:template name="css">
	<link rel="stylesheet" href="{//system/activeTemplatePathRel/@value}css/styles.css" type="text/css" media="screen" />
    <xsl:if test="//pageinfo/@id = 'gallery'">
        <link rel="stylesheet" href="{//system/activeTemplatePathRel/@value}css/gallery.css" type="text/css" media="screen" />
    </xsl:if>
</xsl:template>

<xsl:template name="rss-alt-links">
	<xsl:for-each select="//feed-links/feed">
	<link rel="alternate" type="application/rss+xml" title="{@title}" href="/{@url}/{@cid}/{@type}" />
	</xsl:for-each>
</xsl:template>

<xsl:template match="module" />
