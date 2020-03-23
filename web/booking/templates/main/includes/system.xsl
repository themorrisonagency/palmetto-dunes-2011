<xsl:template name="system-errors" match="//output/pageinfo/messages/message">
	<xsl:choose>
		<xsl:when test="@type='error'">
			<div class="system-errors">
				<xsl:value-of select="self::node()" />
			</div>
		</xsl:when>
		<xsl:when test="@type='message'">
			<div class="system-messages">
				<xsl:value-of select="self::node()" />
			</div>
		</xsl:when>
	</xsl:choose>
</xsl:template>

