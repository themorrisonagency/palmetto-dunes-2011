<xsl:template name="weather">
	<xsl:variable name="weather-path"><xsl:text>/templates/main/images/weather</xsl:text></xsl:variable>
	<div class="current-weather">
		<xsl:choose>
			<xsl:when test="//weather/data/record">
				<ul>
					<li>
						<img src="{$weather-path}/{//weather/data/record[1]/imageroot}.png" alt="{//weather/data/record[1]/summary}" />
						<xsl:value-of select="//weather/data/record[1]/current" /> ºF
					</li>
				</ul>
			</xsl:when>
			<xsl:otherwise>
				<p>Weather information is not currently available.</p>
			</xsl:otherwise>
		</xsl:choose>
	</div>
</xsl:template>

<xsl:template name="weather-page">
	<dl class="weather">
		<xsl:choose>
			<!-- "weatherPage" is set in the module definition on the weather page's content XML file -->
			<xsl:when test="//weatherPage/data/record">
				<xsl:for-each select="//weatherPage/data/record">
					<dt class="date">
						<xsl:choose>
							<xsl:when test="position() = 1">Today</xsl:when>
							<!-- <date> formatted as Dayname, Month Day so you can use substring-before() to grab just Dayname -->
							<xsl:otherwise><xsl:value-of select="substring-before(date, ',')" /></xsl:otherwise>
						</xsl:choose>
					</dt>
					<dd>
						<ul>
							<li class="icon"><img src="/images/weather/icons/{imageroot}.png" alt="{summary}" /></li>
							<li class="condition"><xsl:value-of select='summary' /></li>
							<li class="high-temp"><xsl:value-of select='high' />ºF</li>
							<li class="low-temp"><xsl:value-of select='low' />ºF</li>
						</ul>
					</dd>
				</xsl:for-each>
			</xsl:when>
			<xsl:otherwise>
				<dt>Real-time weather information is currently unavailable.</dt>
			</xsl:otherwise>
		</xsl:choose>
	</dl>
</xsl:template>

<!-- If you want to customize the icons used for various weather conditions, call this from your other weather template -->
<!-- currently set to use "summary" string but can be triggered by any piece of data you want -->
<xsl:template name="weather-icon">
	<xsl:param name="summary" />
	<xsl:variable name="sum" select="translate(summary, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')" />
	<img alt="{$summary}">
		<xsl:attribute name="src">
			<xsl:text>/templates/main/images/weather/large/</xsl:text>
		<xsl:choose>
			<xsl:when test="$sum = 'cloudy'">cloudy.png</xsl:when>
			<xsl:when test="$sum = 't-storms'">thunderstorm.png</xsl:when>
			<xsl:when test="$sum = 'mostly cloudy'">mostly-cloudy.png</xsl:when>
			<xsl:when test="$sum = 'partly cloudy'">partly-cloudy.png</xsl:when>
			<xsl:when test="$sum = 'cloud'">cloudy.png</xsl:when>
			<xsl:when test="$sum = 'rain'">rain.png</xsl:when>
			<xsl:when test="$sum = 'fog'">fog.png</xsl:when>
			<xsl:when test="$sum = 'clear'">clear.png</xsl:when>
			<xsl:when test="$sum = 'sunny'">sunny.png</xsl:when>
			<xsl:when test="$sum = 'snow'">snowy.png</xsl:when>
			<xsl:when test="$sum = 'mixed'">wintry-mix.png</xsl:when>
			<xsl:when test="$sum = 'wind'">sunny.png</xsl:when>
			<xsl:when test="$sum = 'sleet'">sleet.png</xsl:when>
			<xsl:when test="$sum = 'blizzard'">blizzard.png',</xsl:when>
			<xsl:when test="$sum = 'hazy'">hazy.png</xsl:when>
			<xsl:when test="$sum = 'drizzle'">drizzle.png</xsl:when>
			<xsl:when test="$sum = 'showers'">showers.png</xsl:when>
			<xsl:otherwise>sunny.png</xsl:otherwise>
		</xsl:choose>
		</xsl:attribute>
	</img>
</xsl:template>