<xsl:template name="rss-listing">
	<div id="rss-listing">
		<xsl:for-each select="//module/rss/data/record">
        	<div class="feed">
				<h3><xsl:value-of select="@title" /></h3>        	
				<h5>Add this feed to:</h5>
				<div class="feed-readers cf">
					<a class="rss ir" href="{@rss}" target="_blank">RSS</a>
					<a class="aol ir" href="{@aol}" target="_blank">AOL</a>
					<a class="msn ir" href="{@msn}" target="_blank">Add to MSN</a>
					<a class="yahoo ir" href="{@yahoo}" target="_blank">Add to MyYahoo</a>
					<a class="google ir" href="{@google}" target="_blank">Add to MyGoogle</a>
				</div>
				<div class="feed-item-wrapper">
					<xsl:for-each select="record">
						<div class="feed-item">
							<h4><xsl:value-of select="title" /></h4>
							<p>
								<xsl:value-of select="description" />
								<a target="_blank" href="{@link}">Read More</a>
							</p>
						</div>
					</xsl:for-each>
				</div>
            </div>
    	</xsl:for-each>
    </div>
</xsl:template>
