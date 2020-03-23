<xsl:variable name="DisplayDate" select="date:date(concat(//pageinfo/querystring/year,'-',//pageinfo/querystring/month,'-01'))"/>
<xsl:variable name="Year" select="date:year($DisplayDate)"/>
<xsl:variable name="Month" select="date:month-in-year($DisplayDate)"/>
<xsl:variable name="MonthName" select="date:month-name($DisplayDate)" />

<xsl:variable name="NumberOfDaysInMonth">
	<xsl:call-template name="DaysInMonth">
		<xsl:with-param name="month" select="$Month" />
		<xsl:with-param name="year" select="$Year" />
	</xsl:call-template>
</xsl:variable>

<xsl:variable name="FirstDayInWeekForMonth">
	<xsl:choose>
		<xsl:when test="$Month &lt; 10">
			<xsl:value-of select="date:day-in-week(date:date(concat($Year,'-0', $Month, '-01')))" />
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="date:day-in-week(date:date(concat($Year,'-', $Month, '-01')))" />
		</xsl:otherwise>
	</xsl:choose>
</xsl:variable>

<xsl:variable name="WeeksInMonth"><xsl:value-of select="($NumberOfDaysInMonth + $FirstDayInWeekForMonth - 1) div 7" /></xsl:variable>

<xsl:template name="DaysInMonth">
	<xsl:param name="month"><xsl:value-of select="$Month" /></xsl:param>
	<xsl:param name="year"><xsl:value-of select="$Year" /></xsl:param>
	<xsl:choose>
		<xsl:when test="$month = 1 or $month = 3 or $month = 5 or $month = 7 or $month = 8 or $month = 10 or $month = 12">31</xsl:when>
		<xsl:when test="$month=2">
			<xsl:choose>
				<xsl:when test="$year mod 4 = 0">29</xsl:when>
				<xsl:otherwise>28</xsl:otherwise>
			</xsl:choose>
		</xsl:when>
		<xsl:otherwise>30</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="calendar-wrap">
	<div class="table">
		<div class="cal-wrap">
			<table summary="Monthly calendar">
				<thead>
					<tr class="months">
						<th colspan="7" class="current_month">
							<xsl:value-of select="$MonthName" />
							<xsl:text> </xsl:text>
							<xsl:value-of select="$Year" />
						</th>
					</tr>
					<tr class="days">
						<th abbr="Sunday">Sun</th>
						<th abbr="Monday">Mon</th>
						<th abbr="Tuesday">Tue</th>
						<th abbr="Wednesday">Wed</th>
						<th abbr="Thursday">Thu</th>
						<th abbr="Friday">Fri</th>
						<th abbr="Saturday">Sat</th>
					</tr>
				</thead>
				<xsl:call-template name="CalendarWeek"/>
			</table>
		</div>
	</div>
</xsl:template>

<xsl:template name="CalendarWeek">
	<xsl:param name="week">1</xsl:param>
	<xsl:param name="day">1</xsl:param>
	<tr>
		<xsl:call-template name="CalendarDay">
			<xsl:with-param name="day" select="$day" />
		</xsl:call-template>
	</tr>
	<xsl:if test="$WeeksInMonth &gt; $week">
	<xsl:call-template name="CalendarWeek">
		<xsl:with-param name="week" select="$week + 1" />
		<xsl:with-param name="day" select="$week * 7 - ($FirstDayInWeekForMonth - 2)" />
	</xsl:call-template>
	</xsl:if>
</xsl:template>

<xsl:template name="CalendarDay">
	<xsl:param name="count">1</xsl:param>
	<xsl:param name="day" />
	<xsl:param name="month"><xsl:value-of select="$Month" /></xsl:param>
	<xsl:param name="year"><xsl:value-of select="$Year" /></xsl:param>
	<xsl:choose>
		<xsl:when test="($day = 1 and $count != $FirstDayInWeekForMonth) or $day &gt; $NumberOfDaysInMonth">
			<td class="empty default"><xsl:text disable-output-escaping="yes">&#160;</xsl:text></td>
			<xsl:if test="$count &lt; 7">
				<xsl:call-template name="CalendarDay">
					<xsl:with-param name="count" select="$count + 1" />
					<xsl:with-param name="day" select="$day" />
				</xsl:call-template>
			</xsl:if>
		</xsl:when>
		<xsl:otherwise>
			<td id="date_{$year}-{format-number($month, '00')}-{format-number($day, '00')}">
				<xsl:attribute name="class">
					<xsl:text>realtd </xsl:text>
				</xsl:attribute>
				<span><xsl:value-of select="$day" /></span>
			</td>
			<xsl:if test="$count &lt; 7">
				<xsl:call-template name="CalendarDay">
					<xsl:with-param name="count" select="$count + 1" />
					<xsl:with-param name="day" select="$day + 1" />
				</xsl:call-template>
			</xsl:if>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>