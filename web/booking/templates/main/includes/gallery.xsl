<xsl:template name="embedded-gallery">
    <div class="media-gallery embed">
        <div class="galleria"></div>
        <div class="gallery-footer">
            <div class="gallery-share">
                <div class="share-this-on"><span class="alt">Share this on</span></div>
                <a target="_blank" class="gallery-facebook gallery-share-icon"><em class="alt">Facebook</em></a>
                <a target="_blank" class="gallery-twitter gallery-share-icon" data-count="horiztonal"><em class="alt">Twitter</em></a>
                <a target="_blank" class="gallery-pinterest pin-it-button gallery-share-icon" count-layout="none"><em class="alt">Pinterest</em></a>
            </div>
            <div class="copyright"><p>All images copyrighted by <xsl:value-of select="//info/name/@short"/>. Downloading images is prohibited.</p></div>
        </div>
    </div>
</xsl:template>

<xsl:template name="gallery">
	<div class="media-gallery fullscreen" id="fullscreen-example">
        <div class="galleria"></div>
        <div class="gallery-picker">
            <ul class="pick-gallery">
                <li><a href="#accommodations" class="accommodations" rel="accommodations"><em class="alt">Accommodations</em></a></li>
                <li><a href="#dining" class="dining" rel="dining"><em class="alt">Dining</em></a></li>
                <li><a href="#golf" class="golf" rel="golf"><em class="alt">Golf</em></a></li>
                <li><a href="#spa" class="spa" rel="spa"><em class="alt">Spa</em></a></li>
                <li><a href="#activities" class="activities" rel="activities"><em class="alt">Activities</em></a></li>
                <li><a href="#events" class="events" rel="events"><em class="alt">Meetings &amp; Events</em></a></li>
                <li><a href="#weddings" class="weddings" rel="weddings"><em class="alt">Weddings</em></a></li>
                <li><a href="#location" class="location" rel="location"><em class="alt">Location</em></a></li>
            </ul>
        </div>
        <div class="menu-bar">
            <div class="photo-video photos">
                <a href="#" class="view-photos"><em class="alt">photos</em></a>
                <a href="#" class="view-videos"><em class="alt">videos</em></a>
            </div>
            <div class="choose-gallery">
                <dl class="galleries dropdown">
                    <dt><a href="#">Galleries</a></dt>
                    <dd>
                        <ul>
                            <li><a href="#accommodations" rel="accommodations">Accommodations</a></li>
                            <li><a href="#dining" rel="dining">Dining</a></li>
                            <li><a href="#golf" rel="golf">Golf</a></li>
                            <li><a href="#spa" rel="spa">Spa</a></li>
                            <li><a href="#activities" rel="activities">Activities</a></li>
                            <li><a href="#events" rel="events">Meetings &amp; Events</a></li>
                            <li><a href="#weddings" rel="weddings">Weddings</a></li>
                            <li><a href="#location" rel="location">Location</a></li>
                        </ul>
                    </dd>
                </dl>
            </div>
            <div class="gallery-buttons">
                <a href="#" class="gallery-back"><em class="alt">back to gallery</em></a>
                <a href="#" class="gallery-close"><em class="alt">close</em></a>
            </div>
        </div>
        <div class="gallery-footer">
            <div class="gallery-share">
                <div class="share-this-on"><span class="alt">Share this on</span></div>
                <a target="_blank" class="gallery-facebook gallery-share-icon"><em class="alt">Facebook</em></a>
                <a target="_blank" class="gallery-twitter gallery-share-icon" data-count="horiztonal"><em class="alt">Twitter</em></a>
                <a target="_blank" class="gallery-pinterest pin-it-button gallery-share-icon" count-layout="none"><em class="alt">Pinterest</em></a>
            </div>
            <div class="copyright"><p>All images copyrighted by <xsl:value-of select="//info/name/@short"/>. Downloading images is prohibited.</p></div>
        </div>
    </div>
</xsl:template>