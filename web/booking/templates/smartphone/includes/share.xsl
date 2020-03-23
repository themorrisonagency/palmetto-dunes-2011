<xsl:template name="share">
<div class="share">
	<ul>
	    <xsl:choose>
			<xsl:when test="//module[@id='offers']">
				<li class="share-email">
					<a href="mailto:?subject={//module/offers/data/record/title}&amp;body=Lorem ipsum dolor sit amen consecteture adipiscing elit. {//system/activePathRel/@value}specials/package/{permalink}" class="btn share" name="share:email:{permalink}">Share</a>
				</li>
				<li class="share-google">
					<div class="g-plusone" data-size="medium" data-href="http://google.com"></div>
				</li>
				<li class="share-facebook">
			    	<div class="fb-like" data-href="{//system/activePathRel/@value}specials/package/{permalink}" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-action="recommend"></div>
			    </li>
			    <li class="share-twitter">
			    	<a href="https://twitter.com/share?text=Lorem ipsum dolor sit amet&amp;url={//system/activePathRel/@value}specials/package/{permalink}" class="twitter-share-button" data-lang="en" text="lorem ipsum" name="share:twitter:{permalink}">Tweet</a>
					<script>console.log('test');!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				</li>
			</xsl:when>
			<xsl:when test="//module[@id='events']">
				<li class="share-email">
					<a href="mailto:?subject={//module/events/data/record/item_title}&amp;body=Lorem ipsum dolor sit amen consecteture adipiscing elit. {//system/activePathRel/@value}events/{permalink}" class="btn share" name="share:email:{permalink}}">Share</a>
				</li>
				<li class="share-google">
					<div class="g-plusone" data-size="medium" data-href="{//system/activePathRel/@value}events/{permalink}"></div>
				</li>
				<li class="share-facebook">
			    	<div class="fb-like" data-href="{//system/activePathRel/@value}events/{permalink}" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-action="recommend"></div>
			    </li>
			    <li class="share-twitter">
			    	<a href="https://twitter.com/share?text=Lorem ipsum dolor sit amet&amp;url={//system/activePathRel/@value}events/{permalink}" class="twitter-share-button" data-lang="en" text="lorem ipsum" name="share:twitter:{permalink}">Tweet</a>
					<script>console.log('test');!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				</li>
			</xsl:when>
			<xsl:when test="//module[id='press']">
				<li class="share-email">
					<a href="mailto:?subject={//module/press/data/record/item_title}&amp;body=Lorem ipsum dolor sit amen consecteture adipiscing elit. {//system/activePathRel/@value}{//output/pageinfo/@id}/{//pageinfo/permalinks/@path}" class="btn share" name="share:email:{permalink}">Share</a>
				</li>
				<li class="share-google">
					<div class="g-plusone" data-size="medium" data-href="{//system/activePathRel/@value}{//output/pageinfo/@id}/{//pageinfo/permalinks/@path}"></div>
				</li>
			    <li class="share-facebook">
			    	<div class="fb-like" data-href="{//system/activePathRel/@value}{//output/pageinfo/@id}/{//pageinfo/permalinks/@path}" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-action="recommend"></div>
			    </li>
			    <li class="share-twitter">
			    	<a href="https://twitter.com/share?text=Lorem ipsum dolor sit amet&amp;url={//system/activePathRel/@value}{//output/pageinfo/@id}/{//pageinfo/permalinks/@path}" class="twitter-share-button" data-lang="en" text="lorem ipsum" name="share:twitter:{permalink}">Tweet</a>
					<script>console.log('test');!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				</li>
			</xsl:when>
		</xsl:choose>	
	</ul>
</div>
</xsl:template>