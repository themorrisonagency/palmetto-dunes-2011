<xsl:template name="blog">
	<div id="blog-wrapper">
		<div id="blog-nav">
			<xsl:call-template name="blog-nav" />
		</div>
	</div>
	<div id="posts-wrapper">
		<xsl:call-template name="blog-list" />
	</div>
</xsl:template>

<xsl:template name="blog-list">
	<xsl:for-each select="//module/blog/data/record">
		<div class="post rss-item">
			<div class="blog-sidebar">
				<div class="post-date">
					<div class="post-month"><xsl:call-template name="short-month-names"><xsl:with-param name="month" select="@month" /></xsl:call-template></div>
					<div class="post-day"><xsl:value-of select="@day" /></div>
					<div class="post-years"><xsl:value-of select="@year" /></div>
				</div>
				<a class="post-icon" href="/blog/{reference}"><img alt="photo" src="/images/layout/blog/icon-pic.gif" /></a>
			</div>
			<div class="blog-content">
				<h3><xsl:value-of select="item_title" /></h3>
				<div class="post-author">Posted by <xsl:value-of select="firstname" /><xsl:text> </xsl:text><xsl:value-of select="lastname" /><xsl:text> | </xsl:text><xsl:value-of select="@date" /></div>
				<div class="post-intro"><xsl:value-of select="item_desc" /></div>
				<div class="post-description"><xsl:if test="//pageinfo/permalinks/param"><xsl:value-of select="item_html" /></xsl:if></div>
				<div class="post-links">
					<xsl:if test="tags/record">
					<div class="tags" rel="{id}">
						<h3>Tags:</h3>
						<ul>
							<xsl:for-each select="tags/record">
								<li><a href="?tag={@id}"><xsl:value-of select="name" /></a><xsl:if test="position() != last()"><xsl:text>, </xsl:text></xsl:if></li>
							</xsl:for-each>
						</ul>
					</div>
					</xsl:if>
				</div>
				<div class="comment-wrapper">
					<xsl:choose>
						<xsl:when test="//pageinfo/permalinks/param">
							<a href="/the-blog-scrapbook" class="btn submit">Back to Blog</a>
						</xsl:when>
						<xsl:otherwise>
							<a class="posting-details" href="/blog/{reference}" id="bl-{id}" rel="{reference}">Read More...</a>
						</xsl:otherwise>
					</xsl:choose>
					<div class="post-comment-total"><a href="/blog/{reference}#comments-posted">Comments (<xsl:value-of select="comments/@count" />)</a></div>
					<div class="post-comments">
						<xsl:attribute name="class">
							<xsl:text>post-comments</xsl:text><xsl:if test="//pageinfo/querystring/s = 'sf'"> hidebtn</xsl:if>
						</xsl:attribute>
						<a href="/blog/{reference}?s=sf#blog-comment" id="cm-{id}" rel="{//module[@id='blog']/param[@name='cid']/@value}">Add A Comment</a>
					</div>
					<a href="#" class="share-link"><img src="/images/layout/blog/btn-share.gif" alt="Share" /></a>
					<xsl:call-template name="blogform">
						<xsl:with-param name="blogid" select="id" />
					</xsl:call-template>
					<xsl:if test="//pageinfo/permalinks/param and comments/@count > 0">
						<xsl:call-template name="comments" />
					</xsl:if>
				</div>
			</div>
		</div>
	</xsl:for-each>
</xsl:template>

<xsl:template name="blogform">
	<xsl:param name="blogid" />
	<form action="/mcc/postcomment" method="post">
		<xsl:attribute name="class">
			<xsl:text>validate</xsl:text><xsl:if test="not(//pageinfo/querystring/s = 'sf')"> hide-form</xsl:if><xsl:text> blog-comment</xsl:text>
		</xsl:attribute>
		<input type="hidden" name="scrapbook[item]" value="{$blogid}"/>
		<fieldset>           
			<div class="main-fields">
				<div class="field name-fields">
					<label for="first_name">First Name</label>
					<input type="text" name="scrapbook[first_name]" id="first_name" maxlength="100" class="textfield required" value="" />
				</div>
				<div class="field name-fields">
					<label for="last_name">Last Name</label>
					<input type="text" name="scrapbook[last_name]" id="last_name" maxlength="100" class="textfield required" value="" />
				</div>
				<div class="field name-fields">
					<label for="email_address">Email Address</label>
					<input type="text" name="scrapbook[email]" id="email_address" maxlength="100" class="textfield required" value="" />
				</div>
			</div>
			<div class="textarea-field">
				<div class="field clear-left">
					<label for="item_desc" id="post-comment">Comment</label>
					<textarea name="scrapbook[description]" cols="60" rows="4" id="item_desc" class="textfield required"></textarea>
				</div>
			</div>
			<div class="clear-left">
				<div class="buttons">
					<input type="image" src="/images/layout/blog/btn-post-comment.gif" class="form-btn submit" value="submit" name="submit" id="btn-submit"/>
				</div>
			</div>
		</fieldset>
	</form>
</xsl:template>

<xsl:template name="blog-nav">
	<div id="archive-wrapper">
		<h2>Archive</h2>
		<form action="/the-blog-scrapbook" method="GET">
			<select name="month" id="archive-months">
				<xsl:for-each select="//module/dates/data/record">
					<xsl:sort select="@year" data-type="number" order="descending" />
					<xsl:sort select="@month" data-type="number" order="descending" />
					<option value="{@month}{@year}"><xsl:call-template name="month-names"><xsl:with-param name="month" select="@month" /></xsl:call-template><xsl:text> </xsl:text><xsl:value-of select="@year" /></option>
				</xsl:for-each>
			</select>
			<input type="submit" value="View" class="btn submit" />
			<input type="hidden" name="tag" value="{//pageinfo/querystring/tag}"/>
		</form>
	</div>
	<div id="tags-wrapper">
		<h2>Tags</h2>
		<ul>
			<xsl:for-each select="//module/tags/data/record">
				<li><a href="/the-blog-scrapbook?tag={@id}"><xsl:value-of select="name" /><xsl:text> </xsl:text>(<xsl:value-of select="@items" />)</a></li>
			</xsl:for-each>
		</ul>
	</div>
</xsl:template>

<xsl:template name="month-names">
	<xsl:param name="month" />
	<xsl:choose>
		<xsl:when test="$month=01">January</xsl:when>
		<xsl:when test="$month=02">February</xsl:when>
		<xsl:when test="$month=03">March</xsl:when>
		<xsl:when test="$month=04">April</xsl:when>
		<xsl:when test="$month=05">May</xsl:when>
		<xsl:when test="$month=06">June</xsl:when>
		<xsl:when test="$month=07">July</xsl:when>
		<xsl:when test="$month=08">August</xsl:when>
		<xsl:when test="$month=09">September</xsl:when>
		<xsl:when test="$month=10">October</xsl:when>
		<xsl:when test="$month=11">November</xsl:when>
		<xsl:when test="$month=12">December</xsl:when>
	</xsl:choose>
</xsl:template>

<xsl:template name="short-month-names">
	<xsl:param name="month" />
	<xsl:choose>
		<xsl:when test="$month=01">Jan</xsl:when>
		<xsl:when test="$month=02">Feb</xsl:when>
		<xsl:when test="$month=03">Mar</xsl:when>
		<xsl:when test="$month=04">Apr</xsl:when>
		<xsl:when test="$month=05">May</xsl:when>
		<xsl:when test="$month=06">Jun</xsl:when>
		<xsl:when test="$month=07">Jul</xsl:when>
		<xsl:when test="$month=08">Aug</xsl:when>
		<xsl:when test="$month=09">Sep</xsl:when>
		<xsl:when test="$month=10">Oct</xsl:when>
		<xsl:when test="$month=11">Nov</xsl:when>
		<xsl:when test="$month=12">Dec</xsl:when>
	</xsl:choose>
</xsl:template>

<xsl:template name="comments">
	<div id="comments-posted">
		<h3 id="header-comments">Comments:</h3>
		<xsl:for-each select="//comment">
		<div class="comment-post">
			<div class="comment-wrap">
				<span class="comment"><p>We are coming as well, first time and very excited!</p> </span>
			</div>
			<div class="comment-author-date">
				<span class="comment-author">Penney Schultz</span>,
				<span class="comment-date">posted July 4, 2012</span>
			</div>
		</div>
		</xsl:for-each>
	</div>
</xsl:template>