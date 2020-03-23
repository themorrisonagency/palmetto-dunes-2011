<xsl:template name="share-booking">
	<xsl:param name="title"/>
	<xsl:param name="permalink" />
	<xsl:param name="record" />
	<div class="share">
		<ul class="share-list">
			<xsl:variable name="encodedTitle">
				<xsl:call-template name="url-encode">
					<xsl:with-param name="str" select="'I just booked my Hilton Head Island vacation at Palmetto Dunes Oceanfront Resort!'" />
				</xsl:call-template>
			</xsl:variable>
			<xsl:variable name="encodedUrl">
				<xsl:call-template name="url-encode">
					<xsl:with-param name="str" select="concat(//output/pageinfo/system/sitePath/@value,'vacation-rentals/hilton-head-rental-units','?propertyid=',//module[@id='bookingcart']/bookingcart/data/property/@propertyid)" />
				</xsl:call-template>
			</xsl:variable>
			<!-- <li class="share-email">
				<a href="mailto:?subject={$encodedTitle}&amp;body={$encodedUrl}"><em class="alt">Share</em></a>
			</li> -->
			<li class="share-twitter {$encodedUrl}">
				<a href="https://twitter.com/share?text={$encodedTitle}&amp;url={$encodedUrl}" class="twitter-share-button" data-lang="en" data-count="none" text="{$encodedTitle}" name="share:twitter:{$encodedUrl}">Tweet</a>
			</li>
			<li class="share-facebook">
				<fb:like href="{$encodedUrl}" send="false" layout="button" width="100" show_faces="false" action="like" font=""></fb:like>
			</li>
			<li class="share-google {$encodedUrl}">
				<div class="g-plusone" data-size="medium" data-href="{$encodedUrl}" data-annotation="none"></div>
			</li>
		</ul>
	</div>
</xsl:template>