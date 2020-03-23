<!--
	Push Marketing On the Main Page
-->
<xsl:template name="push">
		<xsl:for-each select="//module/smartphonepush/data/record" >
            <article><a href="{mobile_booking_link}"><img src="{@image_url}{@mobile_image}" alt="{title}"/></a><div class="description"><xsl:value-of select="description"/></div></article>
		</xsl:for-each>
</xsl:template> 

<!--
	Special Offers
-->
<xsl:template match="//output/module/offers" mode="offers">
	<xsl:for-each select="data/record" >
        <div class="package-wrapper rss-item package">
			<div class="package-inside-wrapper">
				<div class="package-title-border">
                	<h2><xsl:value-of select="title"/></h2>
                </div>
				<div class="package-content">
					<img src="{@image_url}{@image}" />
					<div class="package-short">
						<p><xsl:value-of select="description"/></p>
					</div>
                    <div class="package-long">
                    	<xsl:value-of select="item_html"/>
                    </div>                    
                    <p class="read-more"><a href="#">View Details</a></p>
					<xsl:if test="@showbooking=1">
						<div class="booking-link">
							<p>Call <a href="tel:800.709.1323">800.709.1323</a> to book or request a reservation <a href="{booking_link}">online</a>!</p>
						</div>
					</xsl:if>
				</div>
			</div>
        </div>		
	</xsl:for-each>
</xsl:template>

<xsl:template name="cta-buttons">
    <ul>
        <li><a href="https://gc.synxis.com/rez.aspx?Hotel={//info/synxis/@hotel_id}&amp;Chain={//info/synxis/@chain_id}&amp;start=searchres&amp;locale=en-US"><img alt="Reservations" src="{//system/activeTemplatePathRel/@value}images/reservations.png" /><br />Book Here</a></li>
        <li><a href="{caliban:system:activePathRel}media-gallery"><img alt="Photo Gallery" src="{//system/activeTemplatePathRel/@value}images/photos.png" border="0" /><br />Photos</a></li>
        <li><a href="{caliban:system:activePathRel}nav-secondary-two"><img alt="Rooms" src="{//system/activeTemplatePathRel/@value}images/rooms.png" border="0" /><br />Rooms</a></li>
        <li><a href="{caliban:system:activePathRel}nav-tertiary-three"><img alt="About Us" src="{//system/activeTemplatePathRel/@value}images/aboutus.png" /><br />About Us</a></li>
    </ul>
</xsl:template>