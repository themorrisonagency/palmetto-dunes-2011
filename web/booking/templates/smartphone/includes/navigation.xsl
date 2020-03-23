	<!-- Breadcrumb -->
	<xsl:template name="breadcrumb">
		<a href="../">Home</a>
		<xsl:for-each select="//output/pageinfo/path/page[@name!='' and @id!=//output/pageinfo/@id]">
			<xsl:text>  &#187;  </xsl:text><a href="{caliban:system:activePathRel}{@path}"><xsl:value-of select="@name"/></a>
		</xsl:for-each>
	</xsl:template>
   
    
	<!--Primary Navigation Only -->
    <xsl:template match="//output/nav" mode="primary">
    	<xsl:variable name="currentpage" select="//output/pageinfo/@id" />
		<ul id="primary-nav">
			<xsl:for-each select="page[@id!='utility' and @id!='privacy' and @id!='ajax' and @id!='event']">
				<xsl:choose>
					<xsl:when test="@exclude" />
					<xsl:when test="position()=last()">
						<li id="nav-{@id}" class="last"><xsl:call-template name="makeLink"><xsl:with-param name="page" select="." /></xsl:call-template></li>
					</xsl:when>
					<xsl:otherwise>
                    	<li id="nav-{@id}"><xsl:call-template name="makeLink"><xsl:with-param name="page" select="." /></xsl:call-template></li>
					</xsl:otherwise>
                </xsl:choose>
            </xsl:for-each>
        </ul>
    </xsl:template>

	<!-- Secondary Navigation -->
	<xsl:template match="//output/nav/page" mode="secondary">
		<xsl:choose>
			<xsl:when test="@exclude" />	
            <xsl:when test="page">
                <div class="nav-secondary" id="sub-nav-{@id}">
                    <ul>
                        <li><a href="#" class="back-main-menu">Main Menu</a></li>
                        <xsl:for-each select="page">
                            <li>
                                <xsl:choose>
                                    <xsl:when test="@exclude" />
                                    <xsl:when test="@id=//pageinfo/@page_id">
                                        <a href="{caliban:system:activePathRel}{@name}" class="current">
                                            <xsl:value-of select="@title"/>
                                        </a>	
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <a href="{caliban:system:activePathRel}{@name}" >
                                            <xsl:value-of select="@title"/>
                                        </a>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </li>
                        </xsl:for-each>
                    </ul>
                </div>
			</xsl:when>
		</xsl:choose>
            
	</xsl:template> 



	<!--
		Language Links
	-->
	<xsl:template match="pageinfo" mode="language">	
		<ul id="language-list">
			<xsl:for-each select="langs/*[@status != 'off']">
					<li id="lang-{@name}">
						<a href="{caliban:system:APP_WEB}{@root}{@path}">
							<xsl:if test="@status='active'">
								<xsl:attribute name="class">current</xsl:attribute>
							</xsl:if>
							<xsl:value-of select="@title"/>
						</a>
					</li>
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
				<xsl:choose>
					<xsl:when test="@href">
						<li id="utility-{@id}"><a href="{@href}" target="{@target}"><em class="alt"><xsl:value-of select="@title"/></em></a></li>
					</xsl:when>
					<xsl:otherwise>
						<li id="utility-{@id}"><a href="{caliban:system:activePathRel}{@name}"><em class="alt"><xsl:value-of select="@title"/></em></a></li>
					</xsl:otherwise>
				</xsl:choose>
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
		<li>
			<xsl:call-template name="makeLink"><xsl:with-param name="page" select="." /></xsl:call-template>
			<xsl:if test="child::page">
				<ul class="sec-nav">
					<xsl:for-each select="child::page">
						<xsl:call-template name="nav-node" select="." />
					</xsl:for-each>
				</ul>
			</xsl:if>
		</li>
		</xsl:when>
		<xsl:otherwise>
			<xsl:if test="child::page">
				<xsl:for-each select="child::page">
					<xsl:call-template name="nav-node" select="." />
				</xsl:for-each>
			</xsl:if>
		</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	
	<xsl:template name="makeLink">
		<xsl:param name="page" />
		<xsl:choose>
			<xsl:when test="$page/@href and $page/@target">
				<a href="{$page/@href}" target="{$page/@target}"><xsl:value-of select="$page/@title" /></a>
			</xsl:when>
			<xsl:when test="$page/@href">
				<a href="{$page/@href}"><xsl:value-of select="$page/@title" /></a>
			</xsl:when>
			<xsl:when test="$page/@target">
				<a href="{caliban:system:activePathRel}{$page/@name}" target="{$page/@target}"><xsl:value-of select="$page/@title" /></a>
			</xsl:when>
			<xsl:otherwise>
				<a href="{caliban:system:activePathRel}{$page/@name}"><xsl:value-of select="$page/@title" /></a>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>