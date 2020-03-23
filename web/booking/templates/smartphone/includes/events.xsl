<xsl:template name="events">
  <!-- Set a variable for the event list, for the selected day, if any exist -->
  <xsl:variable name="day" select="//module/calendar/data/record[@selected=1]/weeks/record/record[@selected=1]" />
  <xsl:variable name="events" select="//module/calendar/data/record[@selected=1]/weeks/record/record[@selected=1]/events/record" />  
  <xsl:variable name="mcc" select="//module/events/data/record[@month=//module/calendar/data/record[@selected=1]/@month]" />  
  <xsl:variable name="aux" select="//module/calendar/aux/messages" /> 

  <div id="events-wrapper">
      <div class="subscribe"><a href="/rss" class="btn rss">Subscribe to RSS</a></div>
    <xsl:choose>
      <xsl:when test="//permalinks/param[@name='day']/@value=0 or not(//permalinks/param)">
        <p><xsl:value-of select="$aux/month_on"/></p>
        <ul id="events" class="vcalendar xoxo vcalendarmain">
          <xsl:for-each select="$mcc" >
            <li class="event vevent rss-item clearfloat">
              <div class="event-inner">
              <h3 class="summary"><xsl:value-of select="item_title"/></h3>
              <p class="event-date">
                <strong>From: </strong>  
                <span class="dtstart">
                  <xsl:value-of select="@month"/>.<xsl:value-of select="@day_begin"/>.<xsl:value-of select="@year"/>
                </span> 
                <strong> To: </strong>  
                <span class="dtend">
                  <xsl:value-of select="@month_end"/>.<xsl:value-of select="@day_end"/>.<xsl:value-of select="@year_end"/>
                </span>
              </p>
              <div class="event-description clearfloat"></div>
              <xsl:if test="permalink != ''" >
                <div class="event-links clearfloat">
                  <xsl:call-template name="share" />
                  <a href="{//system/activePathRel/@value}events/{@year}/{@month}/{@day_begin}/{permalink}" class="url view-details" id="{permalink}">View Details</a>
                </div>
              </xsl:if>
            </div>
              <div class="horz-rule"/>
            </li>
          </xsl:for-each>
          <xsl:if test="not($mcc)">
            <p>
              There are currently no events for  <xsl:value-of select="$day/../../../@calendar_string"/>.
              Please check back later or try another date by clicking a highlighted date on the calendar.
            </p>
          </xsl:if>
        </ul>
      </xsl:when>
      <xsl:otherwise>
        <xsl:if test="$events">
          <p><xsl:value-of select="$aux/day_on"/></p>
          <ul id="events" class="vcalendar xoxo vcalendarmain">
            <xsl:for-each select="$events" >
              <li class="event vevent rss-item clearfloat">
                <div class="event-inner">
                <h3 class="summary"><xsl:value-of select="$events/title"/></h3>
                <p class="event-date">
                  <strong>From: </strong>  
                  <span class="dtstart">
                    <xsl:value-of select="$mcc[@id=$events/@id]/@month"/>/<xsl:value-of select="$mcc[@id=$events/@id]/@day_begin"/>/<xsl:value-of select="$mcc[@id=$events/@id]/@year"/>
                  </span> 
                    <strong> To: </strong>  
                  <span class="dtend">
                    <xsl:value-of select="$mcc[@id=$events/@id]/@month_end"/>/<xsl:value-of select="$mcc[@id=$events/@id]/@day_end"/>/<xsl:value-of select="$mcc[@id=$events/@id]/@year_end"/>
                  </span>
                </p>
                <div class="event-description clearfloat">
                  <xsl:value-of select="$events/description"/>
                    <div class="permalink-share"><xsl:call-template name="share" /></div>
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