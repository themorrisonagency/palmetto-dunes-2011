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
        <xsl:choose>
            <xsl:when test="//module/blog/data/record/id > 0">
                <xsl:for-each select="//module/blog/data/record">
                    <xsl:variable name="title" select="item_title" />
                    <xsl:variable name="permalinks" select="//pageinfo/permalinks/@path" />
                    <div class="post rss-item">
                        <div class="blog-content">
                            <h3><xsl:value-of select="item_title" /></h3>
                            <div class="post-author">posted by <xsl:value-of select="firstname" /><xsl:text> </xsl:text><xsl:value-of select="lastname" /><xsl:text>, </xsl:text><xsl:value-of select="@date" /></div>
                            <div class="post-intro"><xsl:value-of select="item_desc" /></div>
                            <xsl:choose>
                                <xsl:when test="string-length($permalinks)>0">
                                    <div class="post-description"><xsl:if test="$permalinks"><xsl:value-of select="item_html" /></xsl:if></div>
                                </xsl:when>
                                <xsl:otherwise>
                                    <div class="post-description"><xsl:text> </xsl:text></div>
                                </xsl:otherwise>
                            </xsl:choose>
                            <xsl:if test="count(tags/record) > 0">
                                <div class="post-tags">Tags: 
                                    <xsl:for-each select="tags/record">
                                        <a href=""><xsl:value-of select="name" /></a><xsl:if test="position()!=last()"><xsl:text>, </xsl:text></xsl:if>
                                    </xsl:for-each>
                                </div>
                            </xsl:if>
                            <div class="comment-wrapper">
                                <xsl:choose>
                                    <xsl:when test="//pageinfo/permalinks/param">
                                        <a href="/the-blog-scrapbook" class="back-to-blog">Back to Blog</a>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <a class="posting-details" href="/{permalink}" id="{permalink}" rel="{id}">Read More</a>
                                    </xsl:otherwise>
                                </xsl:choose>
                                <xsl:call-template name="share">
                                    <xsl:with-param name="title" select="$title"/>
                                    <xsl:with-param name="permalink" select="$permalinks"/>
                                    <xsl:with-param name="record" select="."/>
                                </xsl:call-template>
                                <div class="post-comment-total"><a href="/{permalink}#comments-posted">Comments (<xsl:value-of select="comments/@count" />)</a></div>
                                <div class="post-comments">
                                    <a href="/{permalink}?s=sf#blog-comment" id="cm-{id}" rel="{//module[@id='blog']/param[@name='cid']/@value}">Post Comment</a>
                                </div>
                                <xsl:call-template name="post-comment" />
                                <xsl:call-template name="posted-comments" />
                            </div>
                        </div>
                    </div>
                </xsl:for-each>
            </xsl:when>
            <xsl:otherwise>
                <p>Sorry no blog posts.</p>
            </xsl:otherwise>
        </xsl:choose>    
	</xsl:template>
    
    <xsl:template name="blog-nav">
        <div id="blog-nav">
            <div class="blog-nav-item">
                <h3>Share Your Story</h3>
                <p><em>Connect Online</em></p>
                <a href="/forms/blog-post" class="post-entry"><em class="alt">Post an Entry</em></a>
            </div>
            <div id="archive-wrapper" class="blog-nav-item">
                <h3>Search Archive</h3>
                <p><em>Explore the Blog</em></p>
                <form action="/the-blog-scrapbook" method="GET">
                    <xsl:for-each select="//module/dates/data/record">
                    	<xsl:if test="position()=last()">
                        	<xsl:variable name="lastmonth" select="@month"/>                    	
                            <input type="hidden" value="{$lastmonth}" name="month" id="blog-month" />
                            <input type="hidden" value="{//module/dates/data/record/@year}" name="year" id="blog-year" />
                    	</xsl:if>
                    </xsl:for-each>
                    <select id="archive-months">
                    <xsl:variable name="selectedmonth" select="//output/pageinfo/querystring/month"/>
                        <xsl:for-each select="//module/dates/data/record">
                            <xsl:sort select="@year" data-type="number" order="descending" />
                            <xsl:sort select="@month" data-type="number" order="descending" />
                            <xsl:choose>
                            	<xsl:when test="@month = $selectedmonth">
                               		<option selected="selected" value="{@month}-{@year}"><xsl:call-template name="month-names"><xsl:with-param name="month" select="@month" /></xsl:call-template><xsl:text> </xsl:text><xsl:value-of select="@year" /></option>
								</xsl:when>                                
								<xsl:otherwise>
                               		<option value="{@month}-{@year}"><xsl:call-template name="month-names"><xsl:with-param name="month" select="@month" /></xsl:call-template><xsl:text> </xsl:text><xsl:value-of select="@year" /></option>
								</xsl:otherwise>                                
                            </xsl:choose>
                        </xsl:for-each>
                    </select>
                    <input type="image" src="{//system/activeTemplatePathRel/@value}images/buttons/go-btn.png" value="Go" class="btn submit" />
                    <input type="hidden" name="tag" value=""/>
                </form>
            </div>
            <div class="blog-nav-item">
                <h3>Subscribe to RSS</h3>
                <p><em>Stay Connected</em></p>
                <a href="/rss" class="subscribe-rss"><em class="alt">Subscribe to RSS</em></a>
            </div>
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
		<div class="comments-posted">
			<h3 class="header-comments">Comments:</h3>
			<xsl:for-each select="//comment">
			<div class="comment-post">
				<div class="comment-author-date">
					comment by <span class="comment-author">Penney Schultz</span>, <span class="comment-date">July 4, 2012</span>
				</div>
				<div class="comment-wrap">
					<span class="comment"><p>We are coming as well, first time and very excited!</p> </span>
				</div>
			</div>
			</xsl:for-each>
		</div>
	</xsl:template>
    
    <xsl:template name="post-comment">
    	<form action="/mcc/postcomment" method="post" class="validate blog-comment" enctype="multipart/form-data">
            <input type="hidden" name="op" value="save" class="alt-input" />
			<input type="hidden" name="scrapbook[item]" class="alt-input" value="{id}" />
            <fieldset>
               	<div class="info-fields">
                    <div class="field">
                        <label for="first_name" title="First Name" class="required">First Name:</label>
                        <input type="text" value="" class="textfield required" maxlength="100" id="first_name" name="scrapbook[first_name]"/>
                    </div>
                    <div class="field">
                        <label for="last_name" title="Last Name" class="required">Last Name:</label>
                        <input type="text" value="" class="textfield required" maxlength="100" id="last_name" name="scrapbook[last_name]"/>
                    </div>
                    <div class="field">
                        <label for="email" title="Email Address" class="required">Email Address:</label>
                        <input type="text" value="" class="large email textfield required" maxlength="100" id="email" name="scrapbook[email]"/>
                    </div>
                </div>
                <div class="field">
	                <label for="comment" id="post-comment">Your Comment</label>
                    <textarea cols="60" rows="4" id="comment" class="textfield required" name="scrapbook[description]"><xsl:text> </xsl:text></textarea>
                </div>
            </fieldset>
            <input type="image" src="{//system/activeTemplatePathRel/@value}images/buttons/submit-btn.png" value="Submit" class="submit btn" id="submit" name="submit" />
        </form>
    </xsl:template>
    
    <xsl:template name="posted-comments">
		<div class="posted-comments">
            <xsl:choose>
            	<xsl:when test="count(comments/record) > 0">            
                    <xsl:for-each select="comments/record">
                    	<!-- <datecreated>2011-06-17 09:53:04</datecreated> -->
                        <xsl:variable name="year" select="substring-before(datecreated,'-')" />
                        <xsl:variable name="month" select="substring(substring-after(datecreated,'-'),0,3)" />
                        <xsl:variable name="day" select="substring(substring-after(datecreated,'-'),4,2)" />
                        <div class="comment-post">
                            <div class="comment-author-date">
                                <xsl:choose>
									<xsl:when test="(string-length(firstname) > 0) or (string-length(lastname) > 0)">
                                    	<span class="comment-author">posted by <xsl:value-of select="firstname" />&#160;<xsl:value-of select="lastname" /></span>,
                                    </xsl:when>
                                    <xsl:otherwise>
	                                    <span class="comment-author">posted by Anonymous</span>,
                                    </xsl:otherwise>
								</xsl:choose>
                                <span class="comment-date">posted <xsl:call-template name="month-names"><xsl:with-param name="month" select="$month" /></xsl:call-template><xsl:text> </xsl:text><xsl:value-of select="$day" /><xsl:text>, </xsl:text><xsl:value-of select="$year" /></span>
                            </div>
                            <div class="comment-wrap">
                                <span class="comment"><p><xsl:value-of select="description" /></p></span>
                            </div>
                        </div>
                    </xsl:for-each>
				</xsl:when>
                <xsl:otherwise>
                	<p>No comments yet!</p>
                </xsl:otherwise>
			</xsl:choose>
		</div>
	</xsl:template>