<xsl:template name="offers">
	<div class="subscribe"><a href="/rss"><em class="alt">Subscribe to RSS</em></a></div>
	<xsl:variable name="permalink" select="//pageinfo/permalinks/@path" />
	<xsl:for-each select="//module/offers/data/record" >
		<xsl:if test="$permalink=''">
			<div class="horz-rule"/>
		</xsl:if>
		<div class="package-wrapper rss-item package">
			<div class="package-interior-wrap">
				<xsl:variable name="packageimage" select="@image" /> 
				<xsl:if test="string-length($packageimage)>0"><div class="package-image"><img src="{@image_url}{@image}" alt="{@title}" /></div></xsl:if>
				<div class="package-title"><xsl:value-of select="title"/></div>
				<div class="package-content">

					<div class="package-short">
						<p><xsl:value-of select="description"/></p>
					</div>
					<div class="package-long">
						<xsl:if test="$permalink!=''">
							<xsl:value-of select="item_html"/>
						</xsl:if>
					</div>
					<xsl:if test="$permalink!=''">
						<xsl:call-template name="share" />
					</xsl:if>
				</div>
			</div>
			<div class="package-links">
				<xsl:if test="$permalink=''">
					<xsl:call-template name="share">
						<xsl:with-param name="title" select="title" />
						<xsl:with-param name="permalink" select="permalink" />
						<xsl:with-param name="record" select="." />
					</xsl:call-template>
				</xsl:if>
				<xsl:variable name="bookinglink" select="booking_link" />

				<xsl:if test="$permalink=''">
					<a class="package-details btn" href="{//system/activePathRel/@value}{permalink}" rel="{id}" id="{permalink}">View Details</a>
				</xsl:if>
				<xsl:if test="string-length($bookinglink)>0">
					<a class="reserve btn" name="reservations:{permalink}" rel="nofollow" href="{booking_link}">Book Now</a> <br />
				</xsl:if>
			</div>
		</div>
	</xsl:for-each>
</xsl:template>
<xsl:template match="//module"/>