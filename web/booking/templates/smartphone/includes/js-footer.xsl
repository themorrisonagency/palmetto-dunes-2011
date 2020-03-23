<xsl:template name="footer-js">
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"><![CDATA[]]></script>
    <xsl:if test="//system/activeLang/@value != 'en'">
		<script type="text/javascript" src="{//system/activeTemplatePathRel/@value}js/datepicker/ui.datepicker-{caliban:system:activeLang}.js"><![CDATA[]]></script>
    </xsl:if>
	<xsl:if test="//output/pageinfo/@id = 'map'">
		<script type="text/javascript" src="//dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=6.2"><![CDATA[]]></script>
		<script type="text/javascript" src="{//system/activeTemplatePathRel/@value}js/jquery.msnmap.js"><![CDATA[]]></script>
    </xsl:if>
	<script type="text/javascript" src="{//system/activeTemplatePathRel/@value}js/main.js"><![CDATA[]]></script>
   	<script type="text/javascript" src="{//system/activeTemplatePathRel/@value}js/i18n/{caliban:system:activeLang}.js"><![CDATA[]]></script>
	<script type="text/javascript" src="{//system/activeTemplatePathRel/@value}js/special.js"><![CDATA[]]></script>
	<script type="text/javascript" src="{//system/activeTemplatePathRel/@value}js/jquery.cycle.js"><![CDATA[]]></script>
   <xsl:if test="//output/pageinfo/@id = 'gallery'">
       <script type="text/javascript" src="{//system/activeTemplatePathRel/@value}js/gallery/galleria.js"><![CDATA[]]></script>
       <script type="text/javascript" src="{//system/activeTemplatePathRel/@value}js/gallery/xml2json.js"><![CDATA[]]></script>
       <script type="text/javascript" src="{//system/activeTemplatePathRel/@value}js/gallery/galleria.sabre.js"><![CDATA[]]></script>
       <script type="text/javascript" src="{//system/activeTemplatePathRel/@value}js/gallery/special-gallery.js"><![CDATA[]]></script>
   </xsl:if>
	<!-- Google +1 button -->
	<script type="text/javascript" src="//apis.google.com/js/plusone.js"><![CDATA[]]></script>
	<!-- Twitter -->
	<script src="//platform.twitter.com/widgets.js" type="text/javascript"><![CDATA[]]></script>
	<script type="text/javascript">
	try {
		(function() {
			var track = document.createElement('script'); track.type = 'text/javascript'; track.async = true; track.id = 'gatag';
			track.src = ('https:' == document.location.protocol ? 'https://www' : 'http://www') + '.gatag.it/?<xsl:value-of select="//info/google/@gatagit_token"/>/'+location.host;
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(track, s);
		})();
	} catch(err) {}
	</script>
</xsl:template>