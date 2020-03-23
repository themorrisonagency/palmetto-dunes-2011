<xsl:template name="js">
	<script type="text/javascript"> 
		//Global language variable for javascript
		LANG = '<xsl:value-of select="//system/activeLang/@value" />';
		CMT = '<xsl:if test="//system/userAgent/@value = 'CONTEXT CMS'">1</xsl:if>';
		RELPATH = '<xsl:value-of select="//system/activeTemplatePathRel/@value" />';
		ROOTPATH = '<xsl:value-of select="//system/siteRelRoot/@value" />';
		SMARTPHONE = false;
		TABLET = false;
		<xsl:if test="//system/deviceGroup/@value=2">
			<xsl:text></xsl:text>SMARTPHONE = true;
		</xsl:if>	
		<xsl:if test="//system/deviceGroup/@value=1">
			<xsl:text></xsl:text>TABLET = true;
		</xsl:if>
	</script>

	<script type="text/javascript">
	try {
		var _gaq = _gaq || [];
		_gaq.push(
			['_setAccount', '<xsl:value-of select="//info/google/@ga_id"/>'],
			['_setDomainName', '<xsl:value-of select="//info/google/@ga_domain"/>'],
			['_setAllowLinker', true],
			['_trackPageview']
		);
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	} catch(err) {}
	</script>
</xsl:template>