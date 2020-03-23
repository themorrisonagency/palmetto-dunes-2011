<xsl:template name="share">
	<xsl:param name="title"/>
	<xsl:param name="permalink" />
	<xsl:param name="record" />
	<div class="share">
		<ul class="share-list">
			<xsl:choose>
				<xsl:when test="//module[@id='offers']">
					<xsl:variable name="encodedTitle">
						<xsl:call-template name="url-encode">
							<xsl:with-param name="str" select="title" />
						</xsl:call-template>
					</xsl:variable>
					<xsl:variable name="emailMsg">
						<xsl:call-template name="url-encode">
							<xsl:with-param name="str" select="concat('Check out this ', //info/address/@title, ' offer!')" />
						</xsl:call-template>
					</xsl:variable>
					<xsl:variable name="tweetMsg">
						<xsl:call-template name="url-encode">
							<xsl:with-param name="str" select="concat('Check out this ', title, ' offer!')" />
						</xsl:call-template>
					</xsl:variable>
					
					<li class="share-email">
						<a href="mailto:?subject={$encodedTitle}&amp;body={$emailMsg}%20{//system/activePathRel/@value}{permalink}" name="share:email:{permalink}"><em class="alt">Share</em></a>
					</li>
					<li class="share-google">
						<div class="g-plusone" data-size="medium" data-href="{//system/activePathRel/@value}{permalink}"></div>
					</li>
					<li class="share-twitter">
						<a href="https://twitter.com/share?text={$tweetMsg}&amp;url={//system/activePathRel/@value}{permalink}" class="twitter-share-button" data-lang="en" text="{title}" name="share:twitter:{permalink}">Tweet</a>
					</li>
					<li class="share-facebook">
						<fb:like href="{//system/activePathRel/@value}{permalink}" send="false" layout="button_count" width="100" show_faces="false" action="like" font=""></fb:like>
					</li>
				</xsl:when>

				<xsl:when test="//module[@id='events']">
					<xsl:variable name="encodedTitle">
						<xsl:call-template name="url-encode">
							<xsl:with-param name="str" select="$title" />
						</xsl:call-template>
					</xsl:variable>
					<xsl:variable name="msg">
						<xsl:call-template name="url-encode">
							<xsl:with-param name="str" select="concat('Check out this ', //info/address/@title, ' event!')" />
						</xsl:call-template>
					</xsl:variable>
					
					<li class="share-email">
						<a href="mailto:?subject={$encodedTitle}&amp;body={$msg}%20{//system/activePathRel/@value}{permalink}" name="share:email:{permalink}"><em class="alt">Share</em></a>
					</li>
					<li class="share-google">
						<div class="g-plusone" data-size="medium" data-href="{$permalink}"></div>
					</li>
					<li class="share-twitter">
						<a href="https://twitter.com/share?text={$msg}&amp;url={$permalink}" class="twitter-share-button" data-lang="en" text="lorem ipsum" name="share:twitter:{$record/permalink}">Tweet</a>
					</li>
					<li class="share-facebook">
						<fb:like href="{//system/activePathRel/@value}{permalink}" send="false" layout="button_count" width="100" show_faces="false" action="like" font=""></fb:like>
					</li>
				</xsl:when>

				<xsl:when test="//module[@id='press']">
					<xsl:variable name="encodedTitle">
						<xsl:call-template name="url-encode">
							<xsl:with-param name="str" select="$title" />
						</xsl:call-template>
					</xsl:variable>
					<xsl:variable name="emailMsg">
						<xsl:call-template name="url-encode">
							<xsl:with-param name="str" select="concat('Read this article about ', //info/address/@title)" />
						</xsl:call-template>
					</xsl:variable>
					<xsl:variable name="tweetMsg">
						<xsl:call-template name="url-encode">
							<xsl:with-param name="str" select="title" />
						</xsl:call-template>
					</xsl:variable>
					
					<li class="share-email">
						<a href="mailto:?subject={$encodedTitle}&amp;body={$emailMsg}%20{//system/activePathRel/@value}{permalink}" name="share:email:{permalink}"><em class="alt">Share</em></a>
					</li>
					<li class="share-google">
						<div class="g-plusone" data-size="medium" data-href="/release/{permalink}"></div>
					</li>
					<li class="share-twitter">
						<a href="https://twitter.com/share?text={$tweetMsg} &amp;url={//system/activePathRel/@value}{//output/pageinfo/@id}/{//pageinfo/permalinks/@path}" class="twitter-share-button" data-lang="en" text="lorem ipsum" name="share:twitter:{permalink}">Tweet</a>
					</li>
					<li class="share-facebook">
						<fb:like href="{//system/activePathRel/@value}{permalink}" send="false" layout="button_count" width="100" show-faces="false" action="like" font=""></fb:like>
					</li>
				</xsl:when>

				<xsl:when test="//module[@id='blog']">
					<xsl:variable name="encodedTitle">
						<xsl:call-template name="url-encode">
							<xsl:with-param name="str" select="$title" />
						</xsl:call-template>
					</xsl:variable>
					<li class="share-email">
						<a href="mailto:?subject={$encodedTitle}&amp;body={//module/press/data/record/item_title}{//system/activePathRel/@value}{//output/pageinfo/@id}/{//pageinfo/permalinks/@path}" name="share:email:{permalink}"><em class="alt">Share</em></a>
					</li>
					<li class="share-google">
						<div class="g-plusone" data-size="medium" data-href="/post/{permalink}"></div>
					</li>
					<li class="share-twitter">
						<a href="https://twitter.com/share?text={//module/data/record/title} &amp;url={//system/activePathRel/@value}{//output/pageinfo/@id}/{//pageinfo/permalinks/@path}" class="twitter-share-button" data-lang="en" text="lorem ipsum" name="share:twitter:{permalink}">Tweet</a>
					</li>
					<li class="share-facebook">
						<fb:like href="{//system/activePathRel/@value}{//output/pageinfo/@id}/{//pageinfo/permalinks/@path}" send="false" layout="button_count" width="100" show-faces="false" action="like" font=""></fb:like>
					</li>
				</xsl:when>

				<xsl:when test="//module[@id='reservations']">
					<xsl:variable name="location">
						<xsl:value-of select="key('location',//propdetails/data/@locationid)"/>
					</xsl:variable>
					<xsl:variable name="view">
						<xsl:value-of select="key('viewtype',//propdetails/data/@viewid)"/>
					</xsl:variable>
					<xsl:variable name="encodedTitle">
						<xsl:call-template name="url-encode">
							<xsl:with-param name="str" select="'I just found this great place to stay at Palmetto Dunes Oceanfront Resort'" />
						</xsl:call-template>
					</xsl:variable>
					<xsl:variable name="emailMsg">
						<xsl:call-template name="url-encode">
							<xsl:with-param name="str" select="concat('Check out ', //propdetails/data/name, ' in the ', $location, ' neighborhood. It has ', //propdetails/data/@bedrooms, ' bedrooms and a ', $view, '.')" />
						</xsl:call-template>
					</xsl:variable>
					<xsl:variable name="tweetMsg">
						<xsl:call-template name="url-encode">
							<xsl:with-param name="str" select="concat('Check out ', //propdetails/data/name, ' at Palmetto Dunes.')" />
						</xsl:call-template>
					</xsl:variable>
					<li class="share-email">
						<a href="mailto:?subject={$encodedTitle}&amp;body={$emailMsg}%0d%0A%0d%0A{//system/activePathRel/@value}{//system/activeSection0/@value}/{//system/activeSection1/@value}?propertyid={//propdetails/data/@id}" name="share:email:{//system/activePathRel/@value}vacation-rentals/hilton-head-rental-units%63propertyid={//propdetails/data/@id}"><em class="alt">Share</em></a>
					</li>
					<li class="share-twitter {$location}">
						<a href="https://twitter.com/share?text={$tweetMsg}&amp;url={//system/activePathRel/@value}vacation-rentals/hilton-head-rental-units%63propertyid={//propdetails/data/@id}" class="twitter-share-button" data-lang="en" text="{title}" name="share:twitter:{//system/activePathRel/@value}vacation-rentals/hilton-head-rental-units%63propertyid={//propdetails/data/@id}">Tweet</a>
					</li>
					<li class="share-facebook">
						<!-- <fb:like href="{//system/activePathRel/@value}{//system/activeSection0/@value}/{//system/activeSection1/@value}?propertyid={//propdetails/data/@id}" send="false" layout="button_count" width="100" show_faces="false" action="like" font=""></fb:like> -->
						<div class="fb-like" data-href="{//system/activePathRel/@value}{//system/activeSection0/@value}/{//system/activeSection1/@value}?propertyid={//propdetails/data/@id}" data-width="100" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
					</li>
					<li class="share-google">
						<div class="g-plusone" data-size="medium" data-href="{//system/activePathRel/@value}{//system/activeSection0/@value}/{//system/activeSection1/@value}?propertyid={//propdetails/data/@id}"></div>
					</li>
					<li class="share-pinit">
						<a class="pin-it-button track" name="share:pinterest" href="//www.pinterest.com/pin/create/button/?url={//system/activePathRel/@value}%63propertyid={//propdetails/data/@id}&amp;media={//system/activePathRel/@value}assets/images/properties/{//propdetails/data/@id}/images/{//propdetails/data/images/record[position() = 1]/filename}&amp;description={$tweetMsg}" data-pin-do="buttonPin" data-pin-config="none"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
					</li>
				</xsl:when>
			</xsl:choose>	
		</ul>
	</div>
</xsl:template>