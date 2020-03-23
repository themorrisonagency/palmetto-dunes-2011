<xsl:template name="calendar">
  <div id="calendar-wrapper">
    <ul id="months-list" class="clear-float">
      <xsl:for-each select="//module/calendar/data/record" >
        <!--define the link variable for each month -->
        <xsl:variable name="linkpart" select="concat(//system/activePathRel/@value,'events/',@year,'/',@month)" />
        <xsl:if test="number(@selected)!=1">
          <li>
            <a href="{$linkpart}"><xsl:value-of select="@month_name"/></a>
          </li>
        </xsl:if>
        <xsl:if test="number(@selected)=1">
          <li class="selected-month">
            <div id="month-wrapper">
              <table summary="Current Month">
                <thead>
                  <tr id="months">
                    <th colspan="7" id="current_month_now">
                      <a class='this-month' href="{$linkpart}">
                        <xsl:value-of select="@calendar_string"/>
                      </a>
                    </th>
                  </tr>
                  <tr id="days">
                    <xsl:for-each select="//module/calendar/aux/day_names/record" >
                      <th>
                        <xsl:if test="number(@day)=1">
                          <xsl:attribute name="class">first-col</xsl:attribute>
                        </xsl:if>                       
                        <xsl:value-of select="@letter"/>
                      </th>
                    </xsl:for-each> 
                  </tr>
                </thead>
                <tbody>
                  <xsl:for-each select="weeks/record" >
                    <!-- set a variable for the record result set for use later -->
                    <xsl:variable name="wrecord" select="record" />
                    <tr>
                      <xsl:if test="number(record/@day)=1">
                        <xsl:attribute name="class">first-row</xsl:attribute>
                      </xsl:if> 
                      <!-- Apply day template -->
                      <xsl:for-each select="//module/calendar/aux/day_names/record" >
                        <xsl:call-template name="cday">
                          <xsl:with-param name="day" select="@day"/>
                          <xsl:with-param name="record" select="$wrecord"/>
                        </xsl:call-template>
                      </xsl:for-each>                     
                    </tr>
                  </xsl:for-each> 
                </tbody>
              </table>
            </div>
          </li>
        </xsl:if>
      </xsl:for-each>
    </ul>
  </div>
</xsl:template>

<xsl:template name="cday">
  <xsl:param name="day" select="1"/>
  <xsl:param name="record" />
  <xsl:variable name="events" select="$record[@weekday=$day]/@events" />
  <td>
    <xsl:if test="number($day)=1">
      <xsl:attribute name="class">first-col</xsl:attribute>
    </xsl:if>
    
    <xsl:if test="number($events) > 0">
      <xsl:attribute name="class">daily</xsl:attribute>
    </xsl:if> 
    
    <xsl:if test="number($events) > 0 and $day=1">
      <xsl:attribute name="class">daily first-col</xsl:attribute>
    </xsl:if> 
    
    <xsl:choose>
      <xsl:when test="$record[@weekday=$day]/@day and number($events) > 0">
        <a href="{//system/activePathRel/@value}events/{$record/../../../@year}/{$record/../../../@month}/{$record[@weekday=$day]/@day}">
          <xsl:value-of select="$record[@weekday=$day]/@day" />
        </a>
      </xsl:when>
      <xsl:when test="$record[@weekday=$day]/@day">
        <xsl:value-of select="$record[@weekday=$day]/@day" /> 
      </xsl:when>
      <xsl:otherwise>
        &#160;
      </xsl:otherwise>
    </xsl:choose>
  </td>

</xsl:template>