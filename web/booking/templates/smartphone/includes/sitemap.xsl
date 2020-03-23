<xsl:template name="sitemap">
					<xsl:for-each select="//output/nav/page">
								<xsl:choose>
									<xsl:when test="@exclude" />	
									<xsl:otherwise>
										<li>
											<a href="{caliban:system:activePathRel}{@name}"><xsl:value-of select="@title"/></a>
											<xsl:if test="page">
												<ul>
													<xsl:for-each select="page">
														<li>
															<a href="{caliban:system:activePathRel}{../@name}/{@name}"><xsl:value-of select="@title"/></a>
															
															<xsl:if test="page">
																<ul>
																	<xsl:for-each select="page">
																		<li>
																			<a href="{caliban:system:activePathRel}{../@name}/{../@name}/{@name}"><xsl:value-of select="@title"/></a>
																		</li>
																	</xsl:for-each>
																</ul>
															</xsl:if>
															
														</li>
													</xsl:for-each>
												</ul>
											</xsl:if>
										</li>
									</xsl:otherwise>
								</xsl:choose>
							</xsl:for-each>
</xsl:template>