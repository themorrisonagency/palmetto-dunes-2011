<xsl:template name="footer">
	<footer>
		<a href="tel:{//info/address/@phone1}" name="external:phone"><xsl:value-of select="//info/address/@phone1" /></a>
		<a href="{//info/address/@maplink}" target="_blank" name="external:googlemaps"><xsl:value-of select="//info/address/@street" />,&#160;<xsl:value-of select="//info/address/@city" />, <xsl:value-of select="//info/address/@state" />&#160;<xsl:value-of select="//info/address/@zip" /></a>
		<a href="mailto:{//info/address/@email}"><xsl:value-of select="//info/address/@email" /></a>
		Copyright ©2012<br />
		<xsl:value-of select="//info/address/@title" /><br />
		<ul id="social-icons">
			<li><a href="http://www.facebook.com/" target="_blank" name="external:facebook" class="facebook"><em class="alt">Facebook</em></a></li>
			<li><a href="http://twitter.com/" target="_blank" name="external:twitter" class="twitter"><em class="alt">Twitter</em></a></li>
		</ul> 
		<a href="/">View Full Website</a>
	</footer>

</xsl:template>