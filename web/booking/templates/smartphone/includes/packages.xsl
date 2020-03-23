<xsl:template name="offers">
	<xsl:variable name="permalink" select="//pageinfo/permalinks/@path" />
	<xsl:for-each select="//module/offers/data/record" >
		<div class="package-wrapper rss-item package">
			<div class="package-interior-wrap">
				<div class="package-title"><xsl:value-of select="title"/></div>
				<div class="package-content">
					<xsl:variable name="packageimage" select="image" /> 
					<xsl:if test="string-length($packageimage)>0"><div class="package-image"><img src="{@image_url}{@mobile_image}" alt="{@title}" /></div></xsl:if>
					<div class="package-short">
						<p><xsl:value-of select="description"/></p>
					</div>
					<div class="package-long">
						<xsl:if test="$permalink!=''">
							<xsl:value-of select="item_html"/>
						</xsl:if>
					</div>
				</div>
			</div>
			<div class="package-links">
				<xsl:variable name="bookinglink" select="booking_link" />
				<xsl:if test="string-length($bookinglink)>0">
					<a class="reserve" name="reservations:{permalink}" rel="nofollow" href="{booking_link}">Reserve</a> <br />
				</xsl:if>
				<xsl:if test="$permalink=''">
					<a class="package-details" href="{//system/activePathRel/@value}package/{permalink}" id="{permalink}">View Details</a>
				</xsl:if>
			</div>
		</div>
	</xsl:for-each>
</xsl:template>