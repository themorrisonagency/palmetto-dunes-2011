<xsl:template name="virtual-tour-small">
	<div class="unit-tour-small-wrapper">
		<a data-href="{//module/propdetails/data/@tour_url}" class="unit-tour-small"><img src="/templates/main/images/buttons/360-short-btn.png" alt="360 Virtual Tour" /></a>
	</div>
</xsl:template>
<xsl:template name="virtual-tour">
	<div class="unit-tour-wrapper">
		<a data-href="{//module/propdetails/data/@tour_url}" class="unit-tour green-btn">View Virtual Tour</a>
	</div>
	<div id="virtual-lightbox" style="display: none;">
		<iframe width='853' height='480' src='{//module/propdetails/data/@tour_url}' frameborder='0' allowfullscreen='true'></iframe>
		<!--<xsl:value-of select="//module/propdetails/data/@tour_url"/>-->
	</div>
</xsl:template>