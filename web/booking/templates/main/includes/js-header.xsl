<xsl:template name="js">
        <link rel="shortcut icon" type="image/x-icon" href="/application/themes/theme_palmetto/images/favicon.ico" />
	<script type="text/javascript"> 
		//Global language variable for javascript
		LANG = '<xsl:value-of select="//system/activeLang/@value" />';
		CMT = '<xsl:if test="//system/userAgent/@value = 'CONTEXT CMS'">1</xsl:if>';
		RELPATH = '<xsl:value-of select="//system/activeTemplatePathRel/@value" />';
		ROOTPATH = '<xsl:value-of select="//system/siteRelRoot/@value" />';
		LINKPATH = '<xsl:value-of select="//system/activePathRel/@value" />';
                CDN_SUBDIR = 'pdbookingv12';
		SMARTPHONE = false;
		TABLET = false;
		<xsl:if test="//system/deviceGroup/@value=2">
			<xsl:text></xsl:text>SMARTPHONE = true;
		</xsl:if>	
		<xsl:if test="//system/deviceGroup/@value=1">
			<xsl:text></xsl:text>TABLET = true;
		</xsl:if>
		<xsl:if test="//output/content/mapvendor = 'bing'">
			<xsl:text></xsl:text>MAPVENDOR = 'bing';
		</xsl:if>
		<xsl:if test="//output/content/mapvendor = 'google'">
			<xsl:text></xsl:text>MAPVENDOR = 'google';
		</xsl:if>
		<xsl:choose>
			<xsl:when test="//pageinfo/@id = 'map'">
			<xsl:text></xsl:text>MAPDATA = '/assets/xml/map.xml';
			</xsl:when>
			<xsl:otherwise>
			<xsl:text></xsl:text>MAPDATA = '/poi';
			</xsl:otherwise>
		</xsl:choose>
	</script>
	<script>
		var RecaptchaOptions = {
		   theme : 'white'
		};		
	</script>
</xsl:template>
