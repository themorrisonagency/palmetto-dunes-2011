<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" version="2.0" xmlns:exslt="http://exslt.org/common" xmlns:date="http://exslt.org/dates-and-times">
	<xsl:output method="html" indent="yes"/>
	<xsl:param name="lang" select="//pageinfo/@language"/>
	<xsl:preserve-space element="*" />
	<xsl:strip-space element="h1 h2 h3 h4 h5" />
	<xsl:template match="/">
		<xsl:call-template name="calendar-wrap" />
	</xsl:template>
	<caliban import="activeTemplatePathAbs,includes/rental-calendar.xsl" />
</xsl:stylesheet>