<!--
Full Navigation (Primary, Secondary, Tertiary)
-->
<xsl:template match="//output/nav" mode="full">
	<ul id="primary-nav">
		<xsl:for-each select="page[@id!='utility' and @id!='privacy' and @id!='ajax' and @id!='event' and not(@exclude='true')]">
			<!--<xsl:call-template name="nav-node-active" select="."/>-->
            <!-- if you need the full, nested nav, call nav-node instead -->
            <xsl:call-template name="nav-node" select="."/>
		</xsl:for-each>
	</ul>
</xsl:template>


<!-- Breadcrumb -->
<xsl:template name="breadcrumb">
	<xsl:variable name="amaxus"><xsl:text>http://www.palmettodunes.com</xsl:text></xsl:variable>
	<ul>
		<li><a href="{$amaxus}/">Home</a></li>
		<li><span class="sep">&#160;<xsl:text>  > </xsl:text>&#160;</span>Vacation Rentals</li>
		<li><span class="sep">&#160;<xsl:text>  > </xsl:text>&#160;</span><a href="http://www.palmettodunes.com/vacation-rentals/hilton-head-villa-rentals/">Home &amp; Villa Rentals</a></li>
		<li><span class="sep">&#160;<xsl:text>  > </xsl:text>&#160;</span>Search Results</li>
	</ul>
</xsl:template>


<!--
Primary Navigation Only
-->
<xsl:template match="//output/nav" mode="primary">
	<ul id="primary-nav">
		<xsl:for-each select="page[@id!='utility' and @id!='privacy' and @id!='ajax' and @id!='event' and not(@exclude='true')]">
			<xsl:choose>
				<xsl:when test="position()=last()">
					<li id="{@id}" class="last"><xsl:call-template name="makeLink" select="." /></li>
				</xsl:when>
				<xsl:otherwise>
					<li id="{@id}"><xsl:call-template name="makeLink" select="." /></li>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:for-each>
	</ul>
</xsl:template>

<!-- Secondary Navigation -->
<xsl:template match="//output/nav" mode="secondary">
	<xsl:variable name="currentsection" select="//system/activeSection0/@value" />
	<xsl:variable name="currentsub" select="//system/activeSection1/@value" />
	<xsl:if test="count(*)>0">
		<ul>
			<xsl:for-each select="//output/nav/page[@id=$currentsection]/page[not(@exclude='true')]">
				<li>
	                <xsl:attribute name="class">
	                    <xsl:text>pos</xsl:text><xsl:value-of select="position()"/>
	                	<xsl:if test="position()=last()"><xsl:text> last</xsl:text></xsl:if>
	                    <xsl:if test="descendant-or-self::page[@id = //pageinfo/@id]"><xsl:text> current</xsl:text></xsl:if>
	                </xsl:attribute>
					<xsl:choose>
						<xsl:when test="@id=//pageinfo/@page_id">
							<a href="{@name}" class="current">
								<xsl:value-of select="@title"/>
							</a>
						</xsl:when>
						<xsl:otherwise>
							<a href="{@name}" >
								<xsl:value-of select="@title"/>
							</a>
						</xsl:otherwise>
					</xsl:choose>
				</li>
				<!--<xsl:call-template name="nav-node" select="."/>-->
			</xsl:for-each>
		</ul>
	</xsl:if>
</xsl:template>

<!--
Language Links
-->
<xsl:template match="pageinfo" mode="language">
	<ul id="language-list">
		<xsl:for-each select="langs/*">
            <xsl:choose>
                <xsl:when test="@status='active'">
        			<li id="lang-{@name}" class="active">
        					<xsl:value-of select="@title"/>
        			</li>
                </xsl:when>
                <xsl:otherwise>
        			<li id="lang-{@name}">
        				<a href="{//siteRelRoot/@value}{@root}{@path}">
        					<xsl:value-of select="@title"/>
        				</a>
        			</li>
                </xsl:otherwise>
            </xsl:choose>
		</xsl:for-each>
	</ul>
</xsl:template>


<xsl:template match="*" mode="lang">
	<xsl:if test="position()=last()">
		<xsl:value-of select="normalize-space(.)"/>
	</xsl:if>
</xsl:template>

<xsl:template  match="*" mode="device">
	<xsl:if test="position()=last()">
		<xsl:value-of select="."/>
	</xsl:if>
</xsl:template>

<xsl:template name="copy">
	<xsl:apply-templates select="copy[@lang=//pageinfo/@language] | copy[1]" mode="lang"/>
</xsl:template>

<!-- Utility nav -->
<xsl:template match="//output/nav/page[@id='utility']" mode="utility">
	<ul>
		<xsl:for-each select="*">
			<li>
				<xsl:attribute name="class">
					<xsl:text>pos</xsl:text><xsl:value-of select="position()"/>
					<xsl:choose>
						<xsl:when test="position() mod 2 = 0"> even</xsl:when>
						<xsl:otherwise> odd</xsl:otherwise>
					</xsl:choose>
				</xsl:attribute>
				<xsl:choose>
					<xsl:when test="@id=//pageinfo/@page_id">
						<a href="{@name}" class="current">
							<xsl:value-of select="@title"/>
						</a>
					</xsl:when>
					<xsl:otherwise>
						<a href="{@name}" >
							<xsl:value-of select="@title"/>
						</a>
					</xsl:otherwise>
				</xsl:choose>
            </li>
		</xsl:for-each>
	</ul>
</xsl:template>

<!-- Site Map -->
<xsl:template name="sitemap">
	<ul class="tree">
		<xsl:for-each select="//nav/page[not(@exclude='true')]">
			<xsl:call-template name="nav-node" select="." />
		</xsl:for-each>
	</ul>
</xsl:template>

<xsl:template name="nav-node">
	<xsl:choose>
		<xsl:when test="@title">
			<li id="{@id}">
                <xsl:attribute name="class">
                    <xsl:text>pos</xsl:text><xsl:value-of select="position()"/>
                	<xsl:if test="position()=last()"><xsl:text> last</xsl:text></xsl:if>
                    <xsl:if test="descendant-or-self::page[@id = //pageinfo/@id]"><xsl:text> current</xsl:text></xsl:if>
                </xsl:attribute>
				<xsl:call-template name="makeLink" select="." />
				<xsl:if test="child::page">
					<ul>
						<xsl:for-each select="child::page[not(@exclude='true')]">
							<xsl:call-template name="nav-node" select="." />
						</xsl:for-each>
					</ul>
				</xsl:if>
			</li>
		</xsl:when>
		<xsl:otherwise>
			<xsl:if test="child::page[not(@exclude='true')]">
				<xsl:for-each select="child::page[not(@exclude='true')]">
					<xsl:call-template name="nav-node" select="." />
				</xsl:for-each>
			</xsl:if>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="nav-node-active">
	<li id="{@id}">
        <xsl:attribute name="class">
            <xsl:text> pos</xsl:text><xsl:value-of select="position()"/>
            <xsl:if test="position()=last()"><xsl:text> last</xsl:text></xsl:if>
            <xsl:if test="descendant-or-self::page[@id = //pageinfo/@id]"><xsl:text> current</xsl:text></xsl:if>
        </xsl:attribute>
		<xsl:call-template name="makeLink" select="." />
		<xsl:if test="child::page[not(@exclude='true')] and (descendant-or-self::page[@id = //pageinfo/@id])">
			<ul>
				<xsl:for-each select="child::page[not(@exclude='true')]">
					<xsl:call-template name="nav-node-active" select="." />
				</xsl:for-each>
			</ul>
		</xsl:if>
	</li>
</xsl:template>

<xsl:template name="makeLink">
	<xsl:variable name="amaxus"><xsl:text>http://www.palmettodunes.com</xsl:text></xsl:variable>
	<xsl:choose>
		<xsl:when test="@href">
			<a href="{@href}" target="{@target}"><xsl:value-of select="@title" /></a>
		</xsl:when>
		<xsl:when test="@target">
			<a href="{//system/siteRel/@value}{@name}" target="{@target}"><xsl:value-of select="@title" /></a>
		</xsl:when>
		<xsl:otherwise>
			<xsl:if test="page[@id='search-results-list' or @id='search-results-details']">
				<a href="{@name}"><xsl:value-of select="@title" /></a>
			</xsl:if>
			<xsl:if test="page[@id!='search-results-list' and @id!='search-results-details']">
				<a href="{$amaxus}{@name}"><xsl:value-of select="@title" /></a>
			</xsl:if>
			<!--<a href="{@name}"><xsl:value-of select="@title" /></a>-->
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="topNote">
	<div class="top-note">
		<p><strong>Thank you for visiting our website. Due to scheduled maintenance the ability to book online is currently unavailable. Please call <script type='text/javascript'>ShowNavisNCPhoneNumber();</script>.</strong></p>
	</div>
</xsl:template>

<xsl:template name="amaxusNav">
	<xsl:value-of select="/output/module/amaxusheader/data/html" />
</xsl:template>


<xsl:template name="amaxusNavStatic">
<![CDATA[
	<div id="header">
<div id="header-wrapper">
<div id="branding"><a href="http://www.palmettodunes.com/"><img src="http://www.palmettodunes.com/custom/a4_palmettodunes/img/logo.svg" alt="Palmetto Dunes Oceanfront Resort" border="0" width="" height=""></a></div>
<div class="primary-nav">
<div class="nav-toggle gradient"><div class="menu-btn"><div class="menu-inner">
<span class="tablet-menu">Navigation </span>Menu</div></div></div>
<div class="console-toggle gradient"><div class="console-menu-btn"><div class="console-menu-inner">
<span>Check</span> Availability</div></div></div>
<div id="amax-menu-fullmenu" class="amax-block amax-menu amax-defaultblock">
<div class="amax-module-header"></div>
<div class="amax-module-body"><ul class="treelevel treelevel-1">
<li class="
        test test2 pos1 t1 n6 odd end">
<a class="" id="content-7" href="http://www.palmettodunes.com/resort/hilton-head-sc-resorts/">About the Resort</a><div class="secondary-nav"><div class="menu-pushes-wrapper">
<div class="menu-push-overview">
<div class="menu-push-inset">
<a href="http://www.palmettodunes.com/resort/hilton-head-sc-resorts/"><img src="http://www.palmettodunes.com/upload/img/megamenu-overview-about-resort.jpg" alt="Resort Overview" width="330" height="285"></a><div class="menu-push-title">
<span><a href="http://www.palmettodunes.com/resort/hilton-head-sc-resorts/">Resort Overview</a></span><a href="http://www.palmettodunes.com/resort/hilton-head-sc-resorts/" class="orange-btn">View</a>
</div>
</div>
<div class="menu-push-description">Play More. Do More. Get More. Palmetto Dunes provides fun activities and amenities for the whole family!</div>
</div>
<div class="menu-push-offer"><div class="menu-push-box"><div class="push-content">
<h2>Travel + Leisure Award Winner</h2>
<p>Find out why T+L&amp;nbsp; Magazine named Palmetto Dunes to its Top 25 "World's Best Family Resorts".</p>
<a href="http://www.palmettodunes.com/hilton-head-island-resort-press/press-awards/">View Our Awards</a>
</div></div></div>
</div></div>
</li>
<li class="
        test test2 pos2 t2 n6 even end">
        <a class="" id="content-18" href="/booking/vacation-rentals/hilton-head-villa-rentals/">Vacation Rentals</a><div class="secondary-nav"><div class="menu-pushes-wrapper">
<div class="menu-push-overview">
<div class="menu-push-inset">
<a href="http://www.palmettodunes.com/vacation-rentals/hilton-head-villa-rentals/"><img src="http://www.palmettodunes.com/upload/img/megamenu-overview-vacation-rentals.jpg" alt="Vacation Rentals Overview" width="330" height="285"></a><div class="menu-push-title">
<span><a href="http://www.palmettodunes.com/vacation-rentals/hilton-head-villa-rentals/">Vacation Rentals Overview</a></span><a href="http://www.palmettodunes.com/vacation-rentals/hilton-head-villa-rentals/" class="orange-btn">View</a>
</div>
</div>
<div class="menu-push-description">Finding the perfect home or villa rental is easy, fast and fun!</div>
</div>
<div class="menu-push-offer"><div class="menu-push-box"><div class="push-content">
<h2>New Rental: 4 Night Harbour</h2>
<p>Features new kitchen, flooring and furniture and huge front deck and private pool!</p>
<a href="https://booking.palmettodunes.com/vacation-rentals/hilton-head-rental-units?propertyid=856">View Details</a>
</div></div></div>
</div></div>
</li>
<li class="
        test test2 pos3 t3 n6 odd end">
<a class="" id="content-26" href="/golf/hilton-head-golf/">Golf</a><div class="secondary-nav"><div class="menu-pushes-wrapper">
<div class="menu-push-overview">
<div class="menu-push-inset">
<a href="/category/26"><img src="http://www.palmettodunes.com/upload/img/megamenu-overview-golf.jpg" alt="Golf Overview" width="330" height="285"></a><div class="menu-push-title">
<span><a href="/category/26">Golf Overview</a></span><a href="/category/26" class="orange-btn">View</a>
</div>
</div>
<div class="menu-push-description">Play our three award winning courses, and discover your&amp;nbsp;favorite!</div>
</div>
<div class="menu-push-offer"><div class="menu-push-box"><div class="push-content">
<h2>Multi-Round Golf Passes</h2>
<p>Reduced rates when you book 2 or more rounds</p>
<a href="/specials/65257/Multi-Round+Golf+Specials">View Details</a>
</div></div></div>
</div></div>
</li>
<li class="
        test test2 pos4 t4 n6 even end">
<a class="" id="content-40" href="/activities/hilton-head-island-activities/">Tennis &amp; Recreation</a><div class="secondary-nav"><div class="menu-pushes-wrapper">
<div class="menu-push-overview">
<div class="menu-push-inset">
<a href="http://www.palmettodunes.com/activities/hilton-head-island-activities/"><img src="http://www.palmettodunes.com/upload/img/activities-overview.jpg" alt="Activities Overview" width="330" height="285"></a><div class="menu-push-title">
<span><a href="http://www.palmettodunes.com/activities/hilton-head-island-activities/">Activities Overview</a></span><a href="http://www.palmettodunes.com/activities/hilton-head-island-activities/" class="orange-btn">View</a>
</div>
</div>
<div class="menu-push-description">Discover all the outdoor fun and recreation available at Palmetto Dunes.</div>
</div>
<div class="menu-push-offer"><div class="menu-push-box"><div class="push-content">
<h2>Stay &amp; Play Tennis Packages</h2>
<p>3 night stay with two 1-hour clinics, bike rentals and more!</p>
<a href="/specials/65013/Tennis+Stay+and+Play+Beginner+to+Intermediate">Click Here to Learn More</a>
</div></div></div>
</div></div>
</li>
<li class="
        test test2 pos5 t5 n6 odd end">
<a class="" id="content-56" href="/meetings-weddings/hilton-head-island-weddings/">Weddings &amp; Events</a><div class="secondary-nav"><div class="menu-pushes-wrapper">
<div class="menu-push-overview">
<div class="menu-push-inset">
<a href="http://www.palmettodunes.com/meetings-weddings/hilton-head-island-weddings/"><img src="http://www.palmettodunes.com/upload/img/megamenu-overview-weddings.jpg" alt="Wedding Overview" width="330" height="285"></a><div class="menu-push-title">
<span><a href="http://www.palmettodunes.com/meetings-weddings/hilton-head-island-weddings/">Wedding Overview</a></span><a href="http://www.palmettodunes.com/meetings-weddings/hilton-head-island-weddings/" class="orange-btn">View</a>
</div>
</div>
<div class="menu-push-description">If a beach wedding is something you’ve always dreamed about, consider Palmetto Dunes Oceanfront Resort.</div>
</div>
<div class="menu-push-offer"><div class="menu-push-box"><div class="push-content">
<h2>Say "I do" on our soft sand beaches!</h2>
<p>Hear from other Brides about their Beach Wedding Experience.&nbsp;</p>
<a href="http://www.weddingwire.com/vendor/VendorViewStoreFront?vid=82f7ecd3e84189dc&amp;z=z&amp;mode=&amp;tab=reviews&amp;subtab=reviews&amp;sortBy=highestRating">View Testimonials</a>
</div></div></div>
</div></div>
</li>
<li class="
        test test2 pos6 t6 n6 last even end">
<a class="" id="content-64" href="/shelter-cove/shelter-cove-harbour/">Shelter Cove Harbour</a><div class="secondary-nav"><div class="menu-pushes-wrapper">
<div class="menu-push-overview">
<div class="menu-push-inset">
<a href="http://www.palmettodunes.com/shelter-cove/events-hilton-head/"><img src="http://www.palmettodunes.com/upload/img/megamenu-overview-shelter-cove.jpg" alt="Shelter Cove Harbour" width="330" height="285"></a><div class="menu-push-title">
<span><a href="http://www.palmettodunes.com/shelter-cove/events-hilton-head/">Shelter Cove Harbour</a></span><a href="http://www.palmettodunes.com/shelter-cove/events-hilton-head/" class="orange-btn">View</a>
</div>
</div>
<div class="menu-push-description">Check out our festival and event schedule at Shelter Cove Harbour.</div>
</div>
<div class="menu-push-offer"><div class="menu-push-box"><div class="push-content">
<h2>Marina Docking Special!</h2>
<p>Special low monthly rate of only $225 for boats 27 ft or less.</p>
<a href="http://www.palmettodunes.com/upload/pdf/SCH-DockageSpecialFlier2014_1.pdf" target="_blank">Click Here to Learn More</a>
</div></div></div>
</div></div>
</li>
</ul></div>
</div>
</div>
<div class="resort-num"><a href="tel:866-380-1778" style="cursor: default;">866.380.1778</a></div>
<div class="weather-feed"><div id="amax-weather-currentweather" class="amax-block amax-weather amax-defaultblock">
<div class="amax-module-header"></div>
<div class="amax-module-body"><ul><li>
<img src="http://www.palmettodunes.com/custom/a4_palmettodunes/img/weather/partly-cloudy.png" width="50" height="50">87° F</li></ul></div>
</div></div>
</div>
<div class="bg-subnav gradient"></div>
</div>
]]>
</xsl:template>


