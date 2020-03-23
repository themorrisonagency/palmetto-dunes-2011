<xsl:template name="map-include">
    <div id="map-wrapper">
        <div class="MapContainer">
            <div id="mapDiv"><![CDATA[]]></div>
        </div>
    </div>
</xsl:template>

<xsl:template name="map-lightbox">
	<div class="jqmWindow" id="map-lightbox">
		<a href="#" class="jqmClose"><img src="/templates/{//system/activeTemplatePath/@value}images/x.png" alt="close" /></a>
	    <xsl:call-template name="map-include" />
    </div>
</xsl:template>