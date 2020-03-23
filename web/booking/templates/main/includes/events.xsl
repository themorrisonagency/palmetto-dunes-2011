<xsl:template name="events">
  <!-- Set a variable for the event list, for the selected day, if any exist -->
  <xsl:variable name="day" select="//module/calendar/data/record[@selected=1]/weeks/record/record[@selected=1]" />
  <!--<xsl:variable name="events" select="$day/events/record" />-->
  <xsl:variable name="events" select="//module/events/data/record[string-length(id) != 0]" />
  <xsl:variable name="aux" select="//module/calendar/aux/messages" /> 
  <div id="events-wrapper">
      <div class="subscribe"><a href="/rss"><em class="alt">Subscribe to RSS</em></a></div>
    <xsl:choose>
      <xsl:when test="not(//permalinks/param)">
        <p>
			<xsl:choose>
				<xsl:when test="$events and //pageinfo/querystring/day">
					<xsl:value-of select="$aux/day_on"/>
				</xsl:when>
				<xsl:when test="$events">
					<xsl:value-of select="$aux/month_on"/>
				</xsl:when>
				<xsl:otherwise>
					There are currently no events for  <xsl:value-of select="$day/../../../@calendar_string"/>. Please check back later or try another date by clicking a highlighted date on the calendar.
				</xsl:otherwise>
			</xsl:choose>
		</p>
        <ul id="events" class="vcalendar xoxo vcalendarmain">
          <xsl:for-each select="$events" >
            <li class="event vevent rss-item">
              <div class="event-inner">
              <h3 class="summary"><xsl:value-of select="item_title"/></h3>
              <p class="event-date">When: 
                <strong> From: </strong>  
                <span class="dtstart">
                  <xsl:value-of select="@month"/>.<xsl:value-of select="@day_begin"/>.<xsl:value-of select="@year"/>
                </span> 
                <strong> To: </strong>  
                <span class="dtend">
                  <xsl:value-of select="@month_end"/>.<xsl:value-of select="@day_end"/>.<xsl:value-of select="@year_end"/>
                </span>
              </p>
              <div class="event-description"></div>
              <xsl:if test="permalink != ''" >
                <div class="event-links">
                  <xsl:call-template name="share">
                    <xsl:with-param name="title" select="item_title"/>
                    <xsl:with-param name="permalink"><xsl:value-of select="concat(//system/activePathRel/@value,'events/',@year,'/',@month,'/',@day_begin,'/',permalink)" /></xsl:with-param>
					          <xsl:with-param name="record" select="."/>
                  </xsl:call-template>
                   <a href="{//system/activePathRel/@value}{permalink}" class="url view-details btn" id="{id}">View Details</a>
                </div>
              </xsl:if>
            </div>
              <div class="horz-rule"/>
            </li>
          </xsl:for-each>
        </ul>
      </xsl:when>
      <xsl:otherwise>
        <xsl:if test="$events">
          <p><xsl:value-of select="$aux/day_on"/></p>
          <ul id="events" class="vcalendar xoxo vcalendarmain">
            <xsl:for-each select="$events" >
              <li class="event vevent rss-item">
                <div class="event-inner">
                <h3 class="summary"><xsl:value-of select="item_title" /></h3>
                <p class="event-date">
                  <strong>From: </strong>  
                  <span class="dtstart">
                    <xsl:value-of select="@month"/>/<xsl:value-of select="@day_begin"/>/<xsl:value-of select="@year"/>
                  </span> 
                    <strong> To: </strong>  
                  <span class="dtend">
                    <xsl:value-of select="@month_end"/>/<xsl:value-of select="@day_end"/>/<xsl:value-of select="@year_end"/>
                  </span>
                </p>
                <div class="event-description">
                  <xsl:value-of select="$events/description"/>
                    <div class="permalink-share">
                      <xsl:call-template name="share">
                        <xsl:with-param name="title" select="item_title"/>
                        <xsl:with-param name="permalink" select="$events/permalink"/>
                        <xsl:with-param name="record" select="."/>
                      </xsl:call-template>
                    </div>
                </div>
            
              </div>
              </li>
            </xsl:for-each>
          </ul>
        </xsl:if>
        <xsl:if test="not($events)">
          <p>
            There are currently no events on 
            <xsl:value-of select="$day/../../../@month"/>/<xsl:value-of select="$day/@day"/>/<xsl:value-of select="$day/../../../@year"/>. 
            Please check back later or try another date by clicking a highlighted date on the calendar.
          </p>
        </xsl:if>
      </xsl:otherwise>
    </xsl:choose>
  </div>
</xsl:template>
<xsl:template match="//module"/>
