<xsl:template name="press-release-listing">
	<xsl:variable name="permalink" select="//pageinfo/permalinks/@path" />
	<xsl:choose>
		<xsl:when test="$permalink != ''">
			<h2><xsl:value-of select="//module/press/data/record/item_title"/></h2>
            <xsl:variable name="subtitle" select="//module/press/data/record/item_sub_title" />
            <xsl:if test="string-length($subtitle)>0">
                <h3><xsl:value-of select="$subtitle" /></h3>
            </xsl:if>
            <xsl:value-of select="//module/press/data/record/item_html"/>
            <a href="/press-releases" class="back">Back to Press Releases</a>
		</xsl:when>
		<xsl:otherwise>
	<table class="press-release-table" summary="Press Releases">
		<thead>
			<tr>
			<th class="release-date">Release Date</th><th>Release Title</th>
			</tr>
		</thead>
		<tbody>
			<xsl:for-each select="//module/press/data/record">
			<tr>
				<td class="release-date"><xsl:value-of select="@date" /></td>
				<td class="rss-item"><a href="{permalink}"><xsl:value-of select="item_title" /></a></td>
			</tr>
			</xsl:for-each>
		</tbody>
		<tfoot></tfoot>
	</table>
</xsl:otherwise>
</xsl:choose>
</xsl:template>