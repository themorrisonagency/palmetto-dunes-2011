<!--
	First-level Navigation
-->
<xsl:template match="page">		
		<xsl:choose>
	        <xsl:when test="@exclude" />
			<xsl:when test="not(*)">
				<li class="cheese">
					<xsl:choose>
						<xsl:when test="@href">
							<a href="{@href}" target="_blank"><xsl:value-of select="@title"/></a> <span class="nav-arrow">&amp;gt;</span>
						</xsl:when>
						<xsl:otherwise>
							<a href="{caliban:system:APP_WEB}{caliban:system:pathSiteRoot}index.php/{@id}"><xsl:value-of select="@title"/></a> <span class="nav-arrow">&amp;gt;</span> cheese
						</xsl:otherwise>
					</xsl:choose>
				</li>		
			</xsl:when>
			<xsl:otherwise>
				<li><a href="#" id="s{@id}" class="open-sec-nav"><xsl:value-of select="@title"/></a> <span class="nav-arrow">&amp;gt;</span></li>						
			</xsl:otherwise>
		</xsl:choose>
</xsl:template> 
 
<!--
	Second-level Navigation
-->
<xsl:template match="page" mode="navigation_level2">
	<article class="nav" id="sub_{@id}">
		<nav class="nav-secondary">
			<ul>
            	<li><a href="#" class="back-main-menu">&amp;lt; Main Menu</a></li>
				<xsl:for-each select="page">				
					<xsl:choose>
						<xsl:when test="@exclude" />	
						<xsl:when test="@href">			
								<li><a href="{@href}"><xsl:value-of select="@title"/></a> <span class="nav-arrow">&amp;gt;</span></li>
						</xsl:when>
						<xsl:otherwise>	
								<li><a href="{caliban:system:APP_WEB}{caliban:system:pathSiteRoot}index.php/{../@id}/{@id}"><xsl:value-of select="@title"/></a> <span class="nav-arrow">&amp;gt;</span></li>	
						</xsl:otherwise>
					</xsl:choose>						
				</xsl:for-each>				
			</ul>
		</nav>
	</article>
</xsl:template>  

<!--
	Utility Navigation For Interior Templates
-->
<xsl:template match="nav">	
	<![CDATA[
		<ul>
			<li><a href="tel:(000) 000-000"><img alt="Reservations" src="{caliban:system:activeTemplatePathRel}images/layout/reservations.jpg"><br />Reservations</a></li>
            <li><a href="{caliban:system:APP_WEB}{caliban:system:pathSiteRoot}index.php/offers"><img alt="Offers" src="{caliban:system:activeTemplatePathRel}images/layout/offers.jpg"><br />Offers</a></li>
			<li><a rel="external" href="#CLIENTGOOGLELINK"><img alt="Directions" src="{caliban:system:activeTemplatePathRel}images/layout/directions.jpg" border="0" /><br />Directions</a></li>
			<li><a href="tel:(000) 000-0000"><img alt="Contact Us" src="{caliban:system:activeTemplatePathRel}images/layout/contact.jpg" border="0" /><br />Contact Us</a></li>
		</ul>
	]]>	
</xsl:template> 

<!--
	Push Marketing On the Main Page
-->
<xsl:template match="push">	
		<xsl:for-each select="data/record" >
			<article><a href="{mobile_booking_link}"><img src="{@image_url}{@mobile_image}" alt="{title}"/></a></article>
		</xsl:for-each>
</xsl:template> 

<!--
	Special Offers
-->
<xsl:template match="offers">	
	<div class="package-wrapper rss-item package">
		<xsl:for-each select="data/record" >
			<div class="package-inside-wrapper">
				<div class="package-title-border">
                	<h2><xsl:value-of select="title"/></h2>
                </div>
				<div class="package-content">
					<img src="{@image_url}{@mobile_image}" />
					<div class="package-short">
						<p><xsl:value-of select="description"/></p>
					</div>
                    <div class="package-long">
                    	<xsl:value-of select="html"/>
                    </div>
                    <p class="read-more"><a href="#">View Details</a></p>
					<xsl:if test="@showbooking=1">
						<div class="booking-link">
							<p>Call 000-000-0000 to book or request a reservation <a href="{booking_link}">online</a>!</p>
						</div>
					</xsl:if>
				</div>
			</div>
		</xsl:for-each>
	</div>		
</xsl:template> 



<!--
	Events Calendar for Interior Events Template
-->
<xsl:template match="events">
	<section id="month">
		<a id="prev" href="#" class="off"><img src="{caliban:system:activeTemplatePathRel}images/smartphone/nav-arrow-left.png" /></a>
		<span class="month-title"><monthname>MONTH 2010</monthname></span>
		<a id="next" href="#" class="on"><img src="{caliban:system:activeTemplatePathRel}images/smartphone/nav-arrow-right.png" /></a>
	</section>
	<section id="eventscalendar" class="content event-calendar" >
		<xsl:for-each select="data/record" >
				<article title="{@month_longtext} {@year}">
					<xsl:for-each select="set" >
						<section class="event-listing">
							<h3><xsl:value-of select="item_title"/></h3>
							<p class="event-date">
								When: 
								From <xsl:value-of select="../@month"/>/<xsl:value-of select="@day_begin"/>/<xsl:value-of select="../@year"/> 
								to  
								<xsl:value-of select="../@month_end"/>/<xsl:value-of select="@day_end"/>/<xsl:value-of select="../@year_end"/>
							</p>
							<div style="display:none;" class="event-description">
								<xsl:value-of select="description"/>	
							</div>	
							<p class="details"><a href="#" onclick="showHide(this);return false;" class="view-details">view details</a></p>				
						</section>
					 </xsl:for-each> 
				 </article>	
		</xsl:for-each>
    </section>	
</xsl:template>
