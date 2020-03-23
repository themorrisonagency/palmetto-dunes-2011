<xsl:key name="viewtype" match="//module/propertyview/data/record" use="@id" />
<xsl:key name="amenities" match="//module/amenities/data/record" use="@id" />
<xsl:key name="location" match="//module/locations/data/record" use="@id" />

<xsl:template name="search-results-header" match="//system-module[@id='reservations']/">
	<div class="result-head cf">
		<div class="result-count">Search Results (<span> <xsl:value-of select="count(//module/reservations/data/record)"/></span>)</div>
		<div class="results-sort-wrapper">
            <div id="sort-by">
                <label class="high-low" for="high-to-low">Sort By:</label>
                <select name="sortorder" class="sort-filter" id="sort-order">
                    <option value="">Default</option>
                    <xsl:element name="option">
                        <xsl:attribute name="value"><xsl:text>low_nightly</xsl:text></xsl:attribute>
                        <xsl:if test="//pageinfo/querystring/sortby = 'low_nightly'">
                            <xsl:attribute name="selected">selected</xsl:attribute>
                        </xsl:if>
                        <xsl:text>Nightly Rates: Low to High</xsl:text>
                    </xsl:element>
                    <xsl:element name="option">
                        <xsl:attribute name="value"><xsl:text>high_nightly</xsl:text></xsl:attribute>
                        <xsl:if test="//pageinfo/querystring/sortby = 'high_nightly'">
                            <xsl:attribute name="selected">selected</xsl:attribute>
                        </xsl:if>
                        <xsl:text>Nightly Rates: High to Low</xsl:text>
                    </xsl:element>
                    <xsl:element name="option">
						<xsl:attribute name="value"><xsl:text>low_bedrooms</xsl:text></xsl:attribute>
                        <xsl:if test="//pageinfo/querystring/sortby = 'low_bedrooms'">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						<xsl:text>Bedrooms: Low to High</xsl:text>
                    </xsl:element>
                    <xsl:element name="option">
						<xsl:attribute name="value"><xsl:text>high_bedrooms</xsl:text></xsl:attribute>
                        <xsl:if test="//pageinfo/querystring/sortby = 'high_bedrooms'">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						<xsl:text>Bedrooms: High to Low</xsl:text>
                    </xsl:element>
                    <xsl:element name="option">
						<xsl:attribute name="value"><xsl:text>low_sleeps</xsl:text></xsl:attribute>
                        <xsl:if test="//pageinfo/querystring/sortby = 'low_sleeps'">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						<xsl:text>Sleeps: Low to High</xsl:text>
                    </xsl:element>
                    <xsl:element name="option">
						<xsl:attribute name="value"><xsl:text>high_sleeps</xsl:text></xsl:attribute>
                        <xsl:if test="//pageinfo/querystring/sortby = 'high_sleeps'">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						<xsl:text>Sleeps: High to Low</xsl:text>
                    </xsl:element>

                </select>
            </div>
		</div>
	</div>
</xsl:template>

<xsl:template name="search-panel" match="//system-module[@id='reservations']/">
	<div class="filter-outer-wrapper">
		<div id="view-filters" class="search-filter-header">Filter Results<span></span></div>
		<div id="search-panel">
                    <form id="search-form" class="validate standard inline track" method="get" action="/booking/vacation-rentals/hilton-head-vacation-rentals" name="reservations:console">
				<div class="form-inner">
					<fieldset>
		                <div class="field">
		                    <label for="arrival">Arrival:</label>
		                    <input type="text" name="arrive" id="arrival" maxlength="10" class="date-picker date-begin textfield" value="{//pageinfo/querystring/arrive}" />
                            <img id="button-arrive" class="ui-datepicker-trigger date-picker" src="/templates/main/images/calendar.svg" alt="mm/dd/yyyy" title="mm/dd/yyyy" width="28" height="26" />
		                </div>
		                <div class="field">
		                    <label for="departure">Departure:</label>
		                    <input type="text" name="depart" id="departure" maxlength="10" class="date-picker date-end textfield" value="{//pageinfo/querystring/depart}" />
                            <img id="button-depart" class="ui-datepicker-trigger date-picker" src="/templates/main/images/calendar.svg" alt="mm/dd/yyyy" title="mm/dd/yyyy" width="28" height="26" />
		                </div>
						<div class="ddropdown">
                            <div class="field">
                                <label for="type">Type:</label>
                                <select id="type" name="typeid">
                                    <option value="">Any</option>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>2</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/typeid = '2'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>Villas</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>1</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/typeid = '1'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>Homes</xsl:text>
                                    </xsl:element>
                                </select>
                            </div>
                            <div class="field field-view">
                                <label for="view">View:</label>
                                <select id="view" name="viewid" class="view-all">
                                	<option value="">Any</option>
                                    <xsl:for-each select="//module/propertyview/data/record">
                            			<xsl:element name="option">
                                            <xsl:attribute name="value"><xsl:value-of select="@id"/></xsl:attribute>
                                            <xsl:if test="//pageinfo/querystring/viewid = @id">
                                                <xsl:attribute name="selected">selected</xsl:attribute>
                                            </xsl:if>
                                            <xsl:value-of select="name" />
                                        </xsl:element>
                            		</xsl:for-each>
                                </select>
                                <select id="view" class='view-homes'>
                                	<option value="">Any</option>
                                	<xsl:for-each select="//module/propertyview/data/record">
                                    	<xsl:choose>
                                    		<xsl:when test="@homes != '0'">
                                       			<xsl:element name="option">
                                       				<xsl:attribute name="value"><xsl:value-of select="@id"/></xsl:attribute>
                                           			<xsl:if test="//pageinfo/querystring/viewid = @id">
                                              			<xsl:attribute name="selected">selected</xsl:attribute>
                                           			</xsl:if>
                                           			<xsl:value-of select="name" />
                                      			</xsl:element>
                                      		</xsl:when>
                                   		</xsl:choose>
                                	</xsl:for-each>
                          		</select>
                            </div>
                        </div>
                        <div class="ddropdown">
							<div class="field field-location">
								<label for="location">Location:</label>
                                <div class="dropdown-all">
                                    <select id="location" name="locationid" class="location-all">
                                        <option value="">Any</option>
                                        <xsl:for-each select="//module/locations/data/record" >
                                            <xsl:if test="name!='Midstream'">
                                                <xsl:element name="option">
                                                    <xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
                                                    <xsl:if test="//pageinfo/querystring/locationid = @id">
                                                        <xsl:attribute name="selected">selected</xsl:attribute>
                                                    </xsl:if>
                                                    <xsl:value-of select="name"/>
                                                </xsl:element>
                                            </xsl:if>
                                        </xsl:for-each>
                                    </select>
                             	</div>
                                <div class="dropdown-villas">
                                	<select id="location" class="location-villas">
                                        <option value="">Any</option>
                                        <xsl:for-each select="//module/locations/data/record">
                                            <xsl:choose>
                                                <xsl:when test="@homes = '0' and name!='Midstream'">
                                                    <xsl:element name="option">
                                                        <xsl:attribute name="value"><xsl:value-of select="@id"/></xsl:attribute>
                                                        <xsl:if test="//pageinfo/querystring/locationid = @id">
                                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                                        </xsl:if>
                                                        <xsl:value-of select="name" />
                                                    </xsl:element>
                                                </xsl:when>
                                            </xsl:choose>
                                        </xsl:for-each>
                                    </select>
                                </div>
                                <div class="dropdown-homes">
                                	<select id="location" class="location-homes">
                                		<option value="">Any</option>
                                        <xsl:for-each select="//module/locations/data/record">
                                            <xsl:choose>
                                                <xsl:when test="@homes != '0' and name!='Midstream'">
                                                    <xsl:element name="option">
                                                        <xsl:attribute name="value"><xsl:value-of select="@id"/></xsl:attribute>
                                                        <xsl:if test="//pageinfo/querystring/locationid = @id">
                                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                                        </xsl:if>
                                                        <xsl:value-of select="name" />
                                                    </xsl:element>
                                                </xsl:when>
                                            </xsl:choose>
                                        </xsl:for-each>
                                    </select>
                                </div>
							</div>
						</div>
						<div class="ddropdown">
							<div class="field field-bedrooms">
								<label for="bedrooms">Bedrooms:</label>
								<select id="bedrooms" name="bedrooms">
									<option value="">Any</option>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>1</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/bedrooms = '1'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>1</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>1.5</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/bedrooms = '1.5'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>1.5</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>2</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/bedrooms = '2'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>2</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>2.5</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/bedrooms = '2.5'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>2.5</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>3</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/bedrooms = '3'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>3</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>4</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/bedrooms = '4'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>4+</xsl:text>
                                    </xsl:element>
								</select>
							</div>
							<div class="field">
								<label for="sleeps">Sleeps:</label>
								<select id="sleeps" name="sleeps">
									<option value="">Any</option>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>1</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/sleeps = '1'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>1+</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>2</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/sleeps = '2'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>2+</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>3</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/sleeps = '3'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>3+</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>4</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/sleeps = '4'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>4+</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>5</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/sleeps = '5'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>5+</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>6</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/sleeps = '6'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>6+</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>7</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/sleeps = '7'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>7+</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>8</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/sleeps = '8'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>8+</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>9</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/sleeps = '9'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>9+</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>10</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/sleeps = '10'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>10+</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>11</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/sleeps = '11'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>11+</xsl:text>
                                    </xsl:element>
                                    <xsl:element name="option">
                                        <xsl:attribute name="value"><xsl:text>12</xsl:text></xsl:attribute>
                                        <xsl:if test="//pageinfo/querystring/sleeps = '12'">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:text>12+</xsl:text>
                                    </xsl:element>
								</select>
							</div>
							<div class="field">
								<label for="promocodes">Promo Code:</label>
								<input type="text" id="promocode" name="promocode" placeholder="" value="{//pageinfo/querystring/promocode}" class="medium textfield" />
							</div>
						</div>
					</fieldset>
					<div class="o-vline"></div>
					<fieldset>
						<div class="form-amenities-heading">Amenities</div>
						<ul id="amenities-list" class="checkboxgroup checkboxgroup-columns cf">
							<xsl:variable name="amen-path">
                            <xsl:value-of select="//pageinfo/querystring/amenityids"/></xsl:variable>
							<li>
		                        <xsl:element name="input">
		                        	<xsl:attribute name="class">no-clear-item checkbox</xsl:attribute>
		                        	<xsl:attribute name="type">checkbox</xsl:attribute>
		                            <xsl:attribute name="value">75</xsl:attribute>
		                            <xsl:attribute name="id">amenityids-75</xsl:attribute>
		                            <xsl:attribute name="name">amenityids[]</xsl:attribute>
		                            <xsl:if test="contains($amen-path, '75')">
										<xsl:attribute name="checked">checked</xsl:attribute>
		                            </xsl:if>
		                        </xsl:element>
								<label for="amenityids-75">&#160;Flat Screen TVs</label>
	                        </li>
							<li>
		                        <xsl:element name="input">
		                        	<xsl:attribute name="class">no-clear-item checkbox</xsl:attribute>
		                        	<xsl:attribute name="type">checkbox</xsl:attribute>
		                            <xsl:attribute name="value">67</xsl:attribute>
		                            <xsl:attribute name="id">amenityids-67</xsl:attribute>
		                            <xsl:attribute name="name">amenityids[]</xsl:attribute>
		                            <xsl:if test="contains($amen-path, '67')">
										<xsl:attribute name="checked">checked</xsl:attribute>
		                            </xsl:if>
		                        </xsl:element>
								<label for="amenityids-67">&#160;Gym Accessibility</label>
	                        </li>
							<li>
		                        <xsl:element name="input">
		                        	<xsl:attribute name="class">no-clear-item checkbox</xsl:attribute>
		                        	<xsl:attribute name="type">checkbox</xsl:attribute>
		                            <xsl:attribute name="value">87</xsl:attribute>
		                            <xsl:attribute name="id">amenityids-87</xsl:attribute>
		                            <xsl:attribute name="name">amenityids[]</xsl:attribute>
		                            <xsl:if test="contains($amen-path, '87')">
										<xsl:attribute name="checked">checked</xsl:attribute>
		                            </xsl:if>
		                        </xsl:element>
								<label for="amenityids-87">&#160;Grill</label>
	                        </li>
							<li>
		                        <xsl:element name="input">
		                        	<xsl:attribute name="class">no-clear-item checkbox</xsl:attribute>
		                        	<xsl:attribute name="type">checkbox</xsl:attribute>
		                            <xsl:attribute name="value">96</xsl:attribute>
		                            <xsl:attribute name="id">amenityids-96</xsl:attribute>
		                            <xsl:attribute name="name">amenityids[]</xsl:attribute>
		                            <xsl:if test="contains($amen-path, '96')">
										<xsl:attribute name="checked">checked</xsl:attribute>
		                            </xsl:if>
		                        </xsl:element>
								<label for="amenityids-96">&#160;Pet Friendly</label>
	                        </li>
							<li>
		                        <xsl:element name="input">
		                        	<xsl:attribute name="class">no-clear-item checkbox</xsl:attribute>
		                        	<xsl:attribute name="type">checkbox</xsl:attribute>
		                            <xsl:attribute name="value">39</xsl:attribute>
		                            <xsl:attribute name="id">amenityids-39</xsl:attribute>
		                            <xsl:attribute name="name">amenityids[]</xsl:attribute>
		                            <xsl:if test="contains($amen-path, '39')">
										<xsl:attribute name="checked">checked</xsl:attribute>
		                            </xsl:if>
		                        </xsl:element>
								<label for="amenityids-39">&#160;Elevator</label>
	                        </li>
							<li>
		                        <xsl:element name="input">
		                        	<xsl:attribute name="class">no-clear-item checkbox</xsl:attribute>
		                        	<xsl:attribute name="type">checkbox</xsl:attribute>
		                            <xsl:attribute name="value">97</xsl:attribute>
		                            <xsl:attribute name="id">amenityids-97</xsl:attribute>
		                            <xsl:attribute name="name">amenityids[]</xsl:attribute>
		                            <xsl:if test="contains($amen-path, '97')">
										<xsl:attribute name="checked">checked</xsl:attribute>
		                            </xsl:if>
		                        </xsl:element>
								<label for="amenityids-97">&#160;Granite Counter Tops</label>
	                        </li>
							<li>
		                        <xsl:element name="input">
		                        	<xsl:attribute name="class">no-clear-item checkbox</xsl:attribute>
		                        	<xsl:attribute name="type">checkbox</xsl:attribute>
		                            <xsl:attribute name="value">73</xsl:attribute>
		                            <xsl:attribute name="id">amenityids-73</xsl:attribute>
		                            <xsl:attribute name="name">amenityids[]</xsl:attribute>
		                            <xsl:if test="contains($amen-path, '73')">
										<xsl:attribute name="checked">checked</xsl:attribute>
		                            </xsl:if>
		                        </xsl:element>
								<label for="amenityids-73">&#160;Children's Playground</label>
	                        </li>
                            <!--Linked to Pool Checkmark-->
                            <li>
                                <xsl:element name="input">
                                    <xsl:attribute name="class">no-clear-item checkbox</xsl:attribute>
                                    <xsl:attribute name="type">checkbox</xsl:attribute>
                                    <xsl:attribute name="value">191</xsl:attribute>
                                    <xsl:attribute name="id">amenityids-123</xsl:attribute>
                                    <xsl:attribute name="name">amenityids[]</xsl:attribute>
                                    <xsl:if test="contains($amen-path, '191')">
                                        <xsl:attribute name="checked">checked</xsl:attribute>
                                    </xsl:if>
                                </xsl:element>
                                <label for="amenityids-191">&#160;Pool<!--Private Indoor Pool--></label>
                            </li>
						</ul>
						<div class="form-amenities-footer">All units have internet access</div>
					</fieldset>
				</div>
				<div class="o-hline"></div>
				<div class="form-footer">
					<a href="" class="cancel">Cancel</a>
					<a href="" class="clear">Clear Filters</a>
					<input type="hidden" name="amenityids" id="amenityids" value="" />
					<input type="hidden" name="sortby" id="sortby" value="{//pageinfo/querystring/sortby}" />
					<input type="submit" class="filter-submit blue-btn submit" value="Apply Filters"/>
				</div>
			</form>
		</div>
	</div>
</xsl:template>

<xsl:template name="search-map">
    <div id="map-outer-wrapper">
		<xsl:call-template name="map-include" />
	</div>
</xsl:template>

<xsl:template name="booking-calendar">
    <xsl:variable name="arrivemonth"><xsl:value-of select="substring(//pageinfo/querystring/arrive, 1, 2)"/></xsl:variable>
    <xsl:variable name="arriveday"><xsl:value-of select="substring(//pageinfo/querystring/arrive, 4, 2)"/></xsl:variable>
    <xsl:variable name="arriveyear"><xsl:value-of select="substring(//pageinfo/querystring/arrive, 7, 4)"/></xsl:variable>
    <xsl:variable name="departmonth"><xsl:value-of select="substring(//pageinfo/querystring/depart, 1, 2)"/></xsl:variable>
    <xsl:variable name="departday"><xsl:value-of select="substring(//pageinfo/querystring/depart, 4, 2)"/></xsl:variable>
    <xsl:variable name="departyear"><xsl:value-of select="substring(//pageinfo/querystring/depart, 7, 4)"/></xsl:variable>

    <div class="system-errors" style="display:none;">Please select new dates. There is a minimum night stay for all units. View Details for the unit for more information or please call <span><script type="text/javascript">ShowNavisNCPhoneNumber();</script></span> for questions and assistance. Error #9994</div>
    <div class="calendar cf">
        <div class="calendar-cal-wrap">
            <form id="cal-booking-form" action="/booking/vacation-rentals/hilton-head-rental-units" method="get" name="reservations:details">
                <input type="hidden" name="propertyid" value="{//output/pageinfo/querystring/propertyid}" />
                <div class="table-container">
                    <a href="" class="previous-month"><span>&lt;</span></a>
                    <div class="ajax-cal cf"><img src="/templates/main/images/ajax-loader.gif" /></div>
                    <a class="next-month" href=""><span>&gt;</span></a>
                </div>
                <div class="calendar-legend">
                    <ul>
                        <li class="legend-available">Available</li>
                        <li class="legend-checkinonly">Check-In Only</li>
                        <li class="legend-checkoutonly">Check-Out Only</li>
                        <li class="legend-unavailable">Unavailable</li>
                        <li class="legend-selected">Selected</li>
                        <li class="legend-cutoff">Not currently available online. Please call.</li>
                    </ul>
                </div>
                <input type="hidden" id="start_date" name="arrival" value="{$arriveyear}-{$arrivemonth}-{$arriveday}" />
                <input type="hidden" id="end_date" name="departure" value="{$departyear}-{$departmonth}-{$departday}" />
                <fieldset>
                    <div class="field hover-field">
                        <img id="button-arrive" class="ui-datepicker-trigger" src="/templates/main/images/calendar.svg" alt="mm/dd/yyyy" width="28" height="26"/>
                        <div>
                            <label for="arrive">Arrival Date:</label>
                            <input type="text" name="arrive" id="arrive" maxlength="10" class="textfield required" readonly="readonly" value="{//pageinfo/querystring/arrive}" />
                        </div>
                    </div>
                    <div class="field hover-field">
                        <img id="button-arrive" class="ui-datepicker-trigger" src="/templates/main/images/calendar.svg" alt="mm/dd/yyyy" width="28" height="26"/>
                        <div>
                            <label for="depart">Departure Date:</label>
                            <input type="text" name="depart" id="depart" maxlength="10" class="textfield required" readonly="readonly" value="{//pageinfo/querystring/depart}" />
                        </div>
                    </div>
                    <div class="field promo-field">
                        <label for="promocode">Promo Code:</label>
                        <input type="text" id="promocode" name="promocode" placeholder="" value="{/output/pageinfo/querystring/promocode}" class="medium textfield" /> 
                        <!-- <input type="text" id="promocode" name="promocode" placeholder="" value="{//propdetails/data/promo/promocode}" class="medium textfield" /> -->
                    </div>
                    <div class="field">
                        <div class="rates-promo">
                            <a href="#" class="add-promo blue-btn">Check Rates</a>
                        </div>
                    </div>
                    <xsl:if test="//reservations/data/record/@id = //module/propdetails/data/@id">
                        <xsl:variable name="propId" select="//module/propdetails/data/@id" />
                        <div class="promo-text-container">
                            <xsl:if test="//pageinfo/querystring/promocode/text()">
                                <span class="promo-code-text">
                                    <div class="unit-promo">
                                        <xsl:value-of select="//module/propdetails/data/promo/name"/>
                                        <a href="#" rel="{@id}" class="promo-learn-more">Learn More</a>
                                        <div class="results-promo">
                                            <xsl:variable name="propPromo" select="//module/reservations/data/record" />
                                            <div id="results-promo-details" class="results-promo-details cf">
                                                <a href="#" class="promo-close">close</a>
                                                <div class="results-promo-content">
                                                    <div class="results-promo-short">
                                                        <xsl:value-of select="$propPromo/promotions/record/name"/>
                                                    </div>
                                                    <div class="results-promo-long">
                                                        <xsl:value-of select="$propPromo//promotions/record/shortdescription"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </xsl:if>
                                <div class="promo-rate-content">
                                    <xsl:choose>
                                        <xsl:when test="//propdetails/data/@totalrate">
                                            <div class="rate-from">For Dates Selected</div>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <div class="rate-from">Starting from</div>
                                        </xsl:otherwise>
                                    </xsl:choose>

                                    <xsl:choose>
                                        <xsl:when test="//pageinfo/querystring/promocode/text()">
                                            <div class="rate-night">
                                                <div class="promo-rate-night">
                                                    $<i class="num">
                                                        <xsl:choose>
                                                            <xsl:when test="//propdetails/data/@totalrate">
                                                                <xsl:value-of select="round(//module/propdetails/data/@dailyrate)"/>
                                                            </xsl:when>
                                                            <xsl:otherwise>
                                                                <xsl:value-of select="round(//module/propdetails/data/@promodailyminrate)"/>
                                                            </xsl:otherwise>
                                                        </xsl:choose>
                                                    </i>
                                                </div>
                                                <div class="orig-rate">
                                                    $<i class="num">
                                                        <xsl:choose>
                                                            <xsl:when test="//propdetails/data/@totalrate">
                                                                <xsl:value-of select="round(//module/propdetails/data/@slashthroughdailyrate)" />
                                                            </xsl:when>
                                                            <xsl:otherwise>
                                                                <xsl:value-of select="round(//module/propdetails/data/@dailyminrate)" />
                                                            </xsl:otherwise>
                                                        </xsl:choose>
                                                    </i>
                                                </div>
                                                <span>per night</span>
                                            </div>
                                            <div class="rate-week">
                                                <div class="promo-rate-week">
                                                    $<i class="num">
                                                        <xsl:choose>
                                                            <xsl:when test="//propdetails/data/@totalrate">
                                                                <xsl:value-of select="round(//module/propdetails/data/@totalrate)" />
                                                            </xsl:when>
                                                            <xsl:otherwise>
                                                                <xsl:value-of select="format-number((round(//module/propdetails/data/@promoweeklyminrate * 7)),'#,###')"/>
                                                            </xsl:otherwise>
                                                        </xsl:choose>
                                                    </i>
                                                </div>
                                                <div class="orig-rate">
                                                    $<i class="num">
                                                        <xsl:choose>
                                                            <xsl:when test="//propdetails/data/@totalrate">
                                                                <xsl:value-of select="round(//module/propdetails/data/@slashthroughtotalrate)" />
                                                            </xsl:when>
                                                            <xsl:otherwise>
                                                                <xsl:value-of select="format-number((round(//module/propdetails/data/@weeklyminrate * 7)),'#,###')"/>
                                                            </xsl:otherwise>
                                                        </xsl:choose>
                                                    </i>
                                                </div>
                                                <span>subtotal <span class="tax-disclaimer">
                                                    <span class="info"></span>plus taxes &amp; fees
                                                        <div class="unit-pricing-tooltip">
                                                            <xsl:value-of select="//module/reservfee/data/content" />
                                                        </div>
                                                    </span>
                                                </span>
                                            </div>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <div class="rate-night">
                                                $<i class="num">
                                                    <xsl:choose>
                                                        <xsl:when test="//propdetails/data/@totalrate">
                                                            <xsl:value-of select="round(//module/propdetails/data/@dailyrate)" />
                                                        </xsl:when>
                                                        <xsl:otherwise>
                                                            <xsl:value-of select="round(//module/propdetails/data/@dailyminrate)" />
                                                        </xsl:otherwise>
                                                    </xsl:choose>
                                                </i><span>per night</span>
                                            </div>
                                            <div class="rate-week">
                                                $<i class="num">
                                                    <xsl:choose>
                                                        <xsl:when test="//propdetails/data/@totalrate">
                                                            <xsl:value-of select="round(//module/propdetails/data/@totalrate)" />
                                                        </xsl:when>
                                                        <xsl:otherwise>
                                                            <xsl:value-of select="format-number((round(//module/propdetails/data/@weeklyminrate * 7)),'#,###')"/>
                                                        </xsl:otherwise>
                                                    </xsl:choose>
                                                </i><span>subtotal</span>
                                            </div>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </div>
                            
                        </div>
                    </xsl:if>
                </fieldset>
                <span class="cal-booking-btn"><a href="/booking/guestinfo?propertyid={//module/propdetails/data/@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;arrive_submit={//pageinfo/querystring/arrive_submit}&#38;depart={//pageinfo/querystring/depart}&#38;depart_submit={//pageinfo/querystring/depart_submit}&#38;typeid={//pageinfo/querystring/typeid}&#38;viewid={//pageinfo/querystring/viewid}&#38;locationid={//pageinfo/querystring/locationid}&#38;bedrooms={//pageinfo/querystring/bedrooms}&#38;sleeps={//pageinfo/querystring/sleeps}&#38;amenityids={//pageinfo/querystring/amenityids}&#38;promocode={//pageinfo/querystring/promocode}" class="unit-book orange-btn">Book Now</a></span>
                
            </form>
        </div>

        
    </div>
</xsl:template>

<xsl:template name="promo-calendar">
    <xsl:variable name="arrivemonth"><xsl:value-of select="substring(//pageinfo/querystring/arrive, 1, 2)"/></xsl:variable>
    <xsl:variable name="arriveday"><xsl:value-of select="substring(//pageinfo/querystring/arrive, 4, 2)"/></xsl:variable>
    <xsl:variable name="arriveyear"><xsl:value-of select="substring(//pageinfo/querystring/arrive, 7, 4)"/></xsl:variable>
    <xsl:variable name="departmonth"><xsl:value-of select="substring(//pageinfo/querystring/depart, 1, 2)"/></xsl:variable>
    <xsl:variable name="departday"><xsl:value-of select="substring(//pageinfo/querystring/depart, 4, 2)"/></xsl:variable>
    <xsl:variable name="departyear"><xsl:value-of select="substring(//pageinfo/querystring/depart, 7, 4)"/></xsl:variable>

    <xsl:choose>
        <xsl:when test="/output/pageinfo/system/deviceGroup/@value='2'">
            <div class="calendar cf">
                <div class="calendar-cal-wrap">
                    <a href="/booking/guestinfo?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;arrive_submit={//pageinfo/querystring/arrive_submit}&#38;depart={//pageinfo/querystring/depart}&#38;depart_submit={//pageinfo/querystring/depart_submit}&#38;typeid={//pageinfo/querystring/typeid}&#38;viewid={//pageinfo/querystring/viewid}&#38;locationid={//pageinfo/querystring/locationid}&#38;bedrooms={//pageinfo/querystring/bedrooms}&#38;sleeps={//pageinfo/querystring/sleeps}&#38;amenityids={//pageinfo/querystring/amenityids}&#38;promocode={//pageinfo/querystring/promocode}" class="unit-book orange-btn">Book Now</a>
                </div>
            </div>
        </xsl:when>
        <xsl:otherwise>
            <div class="calendar cf" rel="{@id}">
                <div class="calendar-cal-wrap">
                    <form id="cal-booking-form" action="/processunit" method="get" name="reservations:details">
                        <input type="hidden" id="start_date" name="arrival" value="{$arriveyear}-{$arrivemonth}-{$arriveday}" />
                        <input type="hidden" id="end_date" name="departure" value="{$departyear}-{$departmonth}-{$departday}" />
                        <fieldset>
                            <div class="field">
                                <label for="arrive">Arrival:</label>
                                <input type="text" name="arrival" id="arrive" maxlength="10" class="textfield required" readonly="readonly" value="{//pageinfo/querystring/arrive}" />
                            </div>
                            <div class="field">
                                <label for="depart">Departure:</label>
                                <input type="text" name="departure" id="depart" maxlength="10" class="textfield required" readonly="readonly" value="{//pageinfo/querystring/depart}" />
                            </div>
                        </fieldset>

                        <xsl:variable name="promoCode">
                            <xsl:choose>
                                <xsl:when test="(/output/pageinfo/querystring/promocode='' or not(/output/pageinfo/querystring/promocode)) and (featuredpromo)"><xsl:value-of select="featuredpromo/promocode"/></xsl:when>
                                <xsl:when test="(/output/pageinfo/querystring/promocode) and (/output/pageinfo/querystring/promocode = /output/module/reservations/data/record/promotions/record/promocode) "><xsl:value-of select="//pageinfo/querystring/promocode"/></xsl:when>
                            </xsl:choose>
                        </xsl:variable>

                        <a href="/booking/guestinfo?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;arrive_submit={//pageinfo/querystring/arrive_submit}&#38;depart={//pageinfo/querystring/depart}&#38;depart_submit={//pageinfo/querystring/depart_submit}&#38;typeid={//pageinfo/querystring/typeid}&#38;viewid={//pageinfo/querystring/viewid}&#38;locationid={//pageinfo/querystring/locationid}&#38;bedrooms={//pageinfo/querystring/bedrooms}&#38;sleeps={//pageinfo/querystring/sleeps}&#38;amenityids={//pageinfo/querystring/amenityids}&#38;promocode={$promoCode}" class="unit-book orange-btn">Book Now</a>
                        <div class="table-container">
                            <a href="" class="previous-month"><span>&lt;</span></a>
                            <div class="ajax-cal cf"><img src="/templates/main/images/ajax-loader.gif" /></div>
                            <a class="next-month" href=""><span>&gt;</span></a>
                        </div>
                        <div class="calendar-legend">
                            <ul>
                                <li class="legend-available">Available</li>
                                <li class="legend-checkinonly">Check-In Only</li>
                                <li class="legend-checkoutonly">Check-Out Only</li>
                                <li class="legend-unavailable">Unavailable</li>
                                <li class="legend-selected">Selected</li>
                                <li class="legend-cutoff">Not currently available online. Please call.</li>
                            </ul>
                        </div>
                        <xsl:if test="//module/reservations/data/record/featuredpromo/@minstay>0">
                            <div class="calendar-min-stay">
                                <xsl:value-of select="//module/reservations/data/record/featuredpromo/@minstay"/>&#160;Night Minimum Stay Required
                            </div>
                        </xsl:if>
                    </form>
                </div>

            </div>
        </xsl:otherwise>
    </xsl:choose>

</xsl:template>

<xsl:template name="search-results-list" match="//system-module[@id='reservations']/">
    <xsl:variable name="imgpath"><xsl:text>https://sabrecdn.com/pdbookingv12/images/properties/</xsl:text></xsl:variable>

	<div id="search-results">
		<div id="map-toggle">expand map<span></span></div>
		<xsl:variable name="recordcount"><xsl:value-of select="count(//module/reservations/data/record)" /></xsl:variable>
        <xsl:variable name="sortorder"><xsl:value-of select="//pageinfo/querystring/sortby/text()"/></xsl:variable>
        <xsl:variable name="vsortBy">
            <xsl:choose>
                <xsl:when test="$sortorder='low_bedrooms'"><xsl:text>bedrooms</xsl:text></xsl:when>
                <xsl:when test="$sortorder='high_bedrooms'"><xsl:text>bedrooms</xsl:text></xsl:when>
                <xsl:when test="$sortorder='low_nightly'"><xsl:text>dailyminrate</xsl:text></xsl:when>
                <xsl:when test="$sortorder='high_nightly'"><xsl:text>dailyminrate</xsl:text></xsl:when>
                <xsl:when test="$sortorder='low_sleeps'"><xsl:text>sleeps</xsl:text></xsl:when>
                <xsl:when test="$sortorder='high_sleeps'"><xsl:text>sleeps</xsl:text></xsl:when>
                <xsl:otherwise><xsl:text></xsl:text></xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        <xsl:variable name="vsortOrder">
            <xsl:choose>
                <xsl:when test="$sortorder='high_bedrooms'"><xsl:text>descending</xsl:text></xsl:when>
                <xsl:when test="$sortorder='high_sleeps'"><xsl:text>descending</xsl:text></xsl:when>
                <xsl:when test="$sortorder='high_nightly'"><xsl:text>descending</xsl:text></xsl:when>
                <xsl:otherwise><xsl:text>ascending</xsl:text></xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        <ul id="results-container" class="results-list {$vsortBy} {$vsortOrder}">
			<xsl:for-each select="//module/reservations/data/record">
                <xsl:sort select="@*[name() = $vsortBy]" data-type="number" order="{$vsortOrder}"/>
                <xsl:sort select="*[name() = $vsortBy]" data-type="number" order="{$vsortOrder}"/>
				<xsl:variable name="showit">
					<xsl:if test="//pageinfo/querystring/view/text()">
						<xsl:choose>
							<xsl:when test="//pageinfo/querystring/view/text() = current()/@viewid" />
							<xsl:otherwise>
								<xsl:text>false</xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:if>
					<xsl:if test="//pageinfo/querystring/bedrooms/text()">
						<xsl:choose>
                            <xsl:when test="//pageinfo/querystring/bedrooms/text() = 4 and current()/@bedrooms &gt;= 4" />
                            <xsl:when test="//pageinfo/querystring/bedrooms/text() = current()/@bedrooms" />
							<xsl:otherwise>
								<xsl:text>false</xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:if>
					<xsl:if test="//pageinfo/querystring/type/text()">
						<xsl:choose>
							<xsl:when test="//pageinfo/querystring/type/text() = current()/@typeid" />
							<xsl:otherwise>
								<xsl:text>false</xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:if>
					<xsl:if test="//pageinfo/querystring/location/text()">
						<xsl:choose>
							<xsl:when test="//pageinfo/querystring/location/text() = current()/@locationid" />
							<xsl:otherwise>
								<xsl:text>false</xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:if>
					<xsl:if test="//pageinfo/querystring/sleeps/text()">
						<xsl:choose>
							<xsl:when test="//pageinfo/querystring/sleeps/text() = current()/@sleeps or current()/@sleeps &gt; //pageinfo/querystring/sleeps/text()" />
							<xsl:otherwise>
								<xsl:text>false</xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:if>
				</xsl:variable>
				<xsl:if test="string-length($showit) = 0">
                    <li id="unit-{@unit}" class="unit-li cf" data-location="{key('location',@locationid)}" data-id="{@id}" data-address="{name}" data-latitude="{@latitude}" data-longitude="{@longitude}" data-booklink="/vacation-rentals/hilton-head-rental-units?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;depart={//pageinfo/querystring/depart}&#38;viewid={//pageinfo/querystring/viewid}&#38;promocode={//pageinfo/querystring/promocode}" data-thumb="{@id}/images/{images/*[1]/filename}" data-totalrate="{@totalrate}" data-dailyrate="{@dailyrate}" data-sortby="{@sortbyrate}">
                        <xsl:attribute name="data-minrate">
                            <xsl:choose>
                                <xsl:when test="/output/pageinfo/querystring/promocode">
                                    <xsl:value-of select="format-number(/output/module/reservations/data/record/@dailyminrate, '#')" />
                                </xsl:when>
                                <xsl:when test="not(@dailyminrate)">
                                    <xsl:value-of select="format-number(/output/module/reservations/data/record/@dailyrate, '#')" />
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:value-of select="format-number(@dailyminrate, '#')" />
                                </xsl:otherwise>
                            </xsl:choose>
                        </xsl:attribute>

                        <xsl:attribute name="data-weekrate">
                            <xsl:choose>
                                <xsl:when test="not(/output/pageinfo/querystring/promocode='')">
                                    <xsl:text>Not Available </xsl:text>
                                </xsl:when>
                                <xsl:when test="not(@weeklyminrate)">
                                    <xsl:text>$</xsl:text><xsl:value-of select="format-number(@weeklyrate, '#,###')" />
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:text>$</xsl:text><xsl:value-of select="format-number(@weeklyminrate, '#,###')" />
                                </xsl:otherwise>
                            </xsl:choose>
                        </xsl:attribute>

                        <div class="unit-inner cf">
                            <div class="property-address mobile-property-address">
                                <span class="list-marker"><xsl:value-of select="position()"/></span>
                                <xsl:choose>
                                    <xsl:when test="count(/output/pageinfo/querystring/promocode) &gt; 0">
                                        <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&amp;promocode={/output/pageinfo/querystring/promocode}"><xsl:value-of select="name"/></a>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}"><xsl:value-of select="name"/></a>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </div>
    						<div class="results-img-wrapper">
    							<xsl:variable name="gallery-images">
    								<xsl:for-each select="images/record">
    									<xsl:value-of select="."/>
    									<xsl:if test="position() != last()">
    										<xsl:text>,</xsl:text>
    									</xsl:if>
    								</xsl:for-each>
    							</xsl:variable>
    							<div class="room-gallery foo1">
                                    <xsl:variable name="currprop"><xsl:value-of select="@id"></xsl:value-of></xsl:variable>
    								<xsl:variable name="firstimg"><xsl:value-of select="images/record[1]/filename"/></xsl:variable>
                                    <!-- data-id="{@id}" data-cycle-prev=".prev-{position()}" data-cycle-next=".next-{position()}" data-cycle-timeout="0" data-cycle-center-horz="true" data-cycle-center-vert="true" data-cycle-log="false" data-cycle-loader="true" data-cycle-progressive="#images-{$currprop}" data-cycle-caption-plugin="caption2" data-cycle-overlay-fx-sel=">div" -->
                                    <div class="room-slideshow" data-id="{@id}" data-cycle-caption-plugin="caption2" data-firstimg="{$firstimg}">
                                        <div class="cycle-overlay"></div>
                                        <!-- <img src="{$imgpath}{$currprop}/images/{$firstimg}" alt="{images/record[1]/caption}" data-cycle-title="{images/record[1]/caption}" />
                                        <script id="images-{$currprop}" type="text/cycle">
                                            <xsl:for-each select="images/record[position() &gt; 1]">
                                                <img src="{$imgpath}{$currprop}/images/{filename}" alt="{caption}" data-cycle-title="{caption}" />
                                            </xsl:for-each>
                                        </script> -->
                                    </div>
    							</div>
    							<a href="#" class="room-prev prev-{position()}"></a>
    							<a href="#" class="room-next next-{position()}"></a>
    						</div>
    						<div class="results-content">
    							<div class="results-main">
    								<div class="property-address">
    									<span class="list-marker tester"><xsl:value-of select="position()"/></span>
                                        <xsl:choose>
                                            <xsl:when test="count(/output/pageinfo/querystring/promocode) &gt; 0">
                                                <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;depart={//pageinfo/querystring/depart}&amp;promocode={/output/pageinfo/querystring/promocode}"><xsl:value-of select="name"/></a>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;depart={//pageinfo/querystring/depart}"><xsl:value-of select="name"/></a>
                                            </xsl:otherwise>
                                        </xsl:choose>
    								</div>
                                    <div class="tagline">
                                        <xsl:value-of select="substring(tagline, 1, 200 + string-length(substring-before(substring(tagline, 201),' ')))"/>
                                    </div>

                                    <xsl:choose>
                                        <xsl:when test="(/output/pageinfo/querystring/promocode='' or not(/output/pageinfo/querystring/promocode)) and (featuredpromo)">
                                            <div class="results-promo" id="featured-promo" data-promo="{featuredpromo/promocode}"><xsl:value-of select="featuredpromo/name"/>&#160;<a href="#" rel="{@id}">Learn More</a></div>
                                        </xsl:when>
                                        <xsl:when test="(/output/pageinfo/querystring/promocode) and (/output/pageinfo/querystring/promocode = /output/module/reservations/data/record/promotions/record/promocode) ">
                                            <div class="results-promo"><xsl:value-of select="/output/module/reservations/data/record/promotions/record/promocode"/>&#160;<a href="#"  rel="{@id}">Learn More</a></div>
                                        </xsl:when>
                                        <xsl:otherwise>
                                        </xsl:otherwise>
                                    </xsl:choose>

                                    <xsl:choose>
                                        <xsl:when test="string-length(tagline) &gt; 5">
                                            <div class="results-description"><xsl:value-of select="substring(shortdescription, 1, 150 + string-length(substring-before(substring(shortdescription, 151),' ')))"/><!-- limits characters to 150, then backs up to a space so no partial words are output --> &#8230;</div>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <div class="results-description"><xsl:value-of select="substring(shortdescription, 1, 200 + string-length(substring-before(substring(shortdescription, 201),' ')))"/><!-- limits characters to 200, then backs up to a space so no partial words are output --> &#8230;</div>
                                        </xsl:otherwise>
                                    </xsl:choose>

                                    <!-- <div class="results-description"><xsl:value-of select="substring(shortdescription, 1, 200 + string-length(substring-before(substring(shortdescription, 201),' ')))"/> &#8230;</div> -->
    							</div>
    							<div class="results-pricing">
                                    <xsl:choose>
                                        <xsl:when test='@totalrate'>
                                            <xsl:choose>
                                                <xsl:when test="//pageinfo/querystring/promocode/text()">
                                                    <div class="range promo-date-range">
                                                        <div>For Dates Selected<div class="price-low"><span>$<xsl:value-of select='format-number(@dailyminrate, "#")' /></span> per night</div><span class="price-slash">$<xsl:value-of select='format-number(@slashthroughdailyrate, "#")' /></span></div>

                                                        <div class="price-weekly"><span>$<xsl:value-of select='format-number(@totalrate, "#")' /></span> subtotal<span class="price-slash">$<xsl:value-of select='format-number(@slashthroughtotalrate, "#")' /></span></div><span class="tax-disclaimer"><span class="info"></span>plus taxes &amp; fees</span>

                                                        <div class="search-unit-pricing-tooltip">
                                                            <xsl:choose>
                                                                <xsl:when test="//module/reservations/data/record/promotions/record/@wholesale='1'">
                                                                    <xsl:value-of select="//module/wholesalereservconf/data/content" />
                                                                </xsl:when>
                                                                <xsl:otherwise>
                                                                    <xsl:value-of select="//module/reservfee/data/content" />
                                                                </xsl:otherwise>
                                                            </xsl:choose>
                                                        </div>
                                                    </div>
                                                </xsl:when>
                                                <xsl:otherwise>
                                                    <!-- with date no promo -->
                                                    <div class="range">
                                                        <div>For Dates Selected
                                                            <div class="price-low">$<xsl:value-of select='format-number(@dailyrate, "#")' /></div>per night
                                                        </div>
                                                        <div class="price-weekly"><span>$<xsl:value-of select='format-number(@totalrate, "#,###")' /> subtotal</span></div><span class="tax-disclaimer"><span class="info"></span>plus taxes &amp; fees</span>

                                                        <div class="search-unit-pricing-tooltip">
                                                            <xsl:choose>
                                                                <xsl:when test="//module/reservations/data/record/promotions/record/@wholesale='1'">
                                                                    <xsl:value-of select="//module/wholesalereservconf/data/content" />
                                                                </xsl:when>
                                                                <xsl:otherwise>
                                                                    <xsl:value-of select="//module/reservfee/data/content" />
                                                                </xsl:otherwise>
                                                            </xsl:choose>
                                                        </div>
                                                    </div>
                                                </xsl:otherwise>
                                            </xsl:choose>
                                        </xsl:when>
                                        <xsl:when test='format-number(@dailyrate, "#") != "NaN" and @weeklyrate and not(@totalrate)'>
                                            <div class="range"><div><div class="price-low">$<xsl:value-of select='format-number(@dailyrate, "#")' /></div>per night</div>
                                            <div class="price-weekly"><span>$<xsl:value-of select='format-number(@weeklyrate, "#,###")' /></span>/per week</div></div>
                                        </xsl:when>

                                        <!-- BASIC SEARCH RESULTS - INCLUDING PROMO -->
                                        <xsl:when test='(format-number(@dailyminrate, "#") != "NaN" or format-number(@weeklyminrate, "#") != "NaN") and (@weeklyminrate)'>
                                        	<xsl:choose>
                                                <xsl:when test="//pageinfo/querystring/promocode/text()">
                                                    <div class="range rate-cal-wrap">
                                                        <div>Starting from<div class="price-low"><span>$<xsl:value-of select='format-number(@promodailyminrate, "#")' /></span>/per night</div><span class="price-slash">$<xsl:value-of select='format-number(@dailyminrate, "#")' /></span></div>
														<xsl:choose>
															<xsl:when test='not(@promoweeklyminrate)'>
																<div class="price-weekly"><br />Not Available/Per Week</div>
															</xsl:when>
															<xsl:otherwise>
																<div class="price-weekly"><span>$<xsl:value-of select='format-number(@promoweeklyminrate, "#")' /></span>/per week<span class="price-slash">$<xsl:value-of select='format-number(@weeklyminrate, "#,###")' /></span></div>
															</xsl:otherwise>
														</xsl:choose>
														<!--
														<div class="price-weekly"><span>$<xsl:value-of select='format-number(@promoweeklyminrate, "#")' /></span>/per week<span class="price-slash">$<xsl:value-of select='format-number(@weeklyminrate, "#,###")' /></span></div>
														-->
                                                    </div>
                                                </xsl:when>
                                                <xsl:otherwise>
                                                    <!-- no date no promo -->
                                                    <div class="range">
                                                        <div>Starting from<div class="price-low">$<xsl:value-of select='format-number(@dailyminrate, "#")' /></div>per night</div>
                                                        <div class="price-weekly"><span>$<xsl:value-of select='format-number(@weeklyminrate, "#,###")' /></span>/per week</div>
                                                    </div>
                                                </xsl:otherwise>
                                            </xsl:choose>
                                        </xsl:when>

                                        <xsl:when test='(not(@weeklyrate) or not(@weeklyminrate))and //pageinfo/querystring/promocode/text()'>
                                            <div class="range"><div>Starting from<div class="price-low">$<xsl:value-of select='format-number(@dailyminrate, "#")' /></div>per night</div>
                                            <div class="price-weekly">Not Available/Per Week</div></div>
                                        </xsl:when>
                                        <xsl:otherwise>
                                        	<div class="no-rates">Rates Not Available, Please Call <script type='text/javascript'>ShowNavisNCPhoneNumber();</script> to speak to a vacation planner or make a reservation.</div>
                                        </xsl:otherwise>
                                    </xsl:choose>

                                <xsl:if test='format-number(@dailyrate, "#") != "NaN"'>
                                    <a href="/booking/guestinfo?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;arrive_submit={//pageinfo/querystring/arrive_submit}&#38;depart={//pageinfo/querystring/depart}&#38;depart_submit={//pageinfo/querystring/depart_submit}&#38;typeid={//pageinfo/querystring/typeid}&#38;viewid={//pageinfo/querystring/viewid}&#38;locationid={//pageinfo/querystring/locationid}&#38;bedrooms={//pageinfo/querystring/bedrooms}&#38;sleeps={//pageinfo/querystring/sleeps}&#38;amenityids={//pageinfo/querystring/amenityids}&#38;promocode={//pageinfo/querystring/promocode}#show" class="pricing-book orange-btn">Book Now</a>
                                </xsl:if>
    							</div>
    							<div class="results-more">
    								<ul class="more-attributes">
    									<li class="view">
    	                                    <xsl:for-each select="key('viewtype',@viewid)">
    	                                        <xsl:value-of select="." />
    	                                    </xsl:for-each>
    	 								</li>
    									<li class="bedrooms"><xsl:value-of select="@bedrooms" /> Bedroom</li>
    									<li class="baths"><xsl:value-of select="@bathrooms" /> Bath</li>
    									<li class="sleeps">Sleeps <xsl:value-of select="@sleeps" /></li>
    								</ul>
    								<xsl:choose>
                                        <xsl:when test="(/output/pageinfo/querystring/promocode='' or not(/output/pageinfo/querystring/promocode)) and (featuredpromo)">
                                            <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;arrive_submit={//pageinfo/querystring/arrive_submit}&#38;depart={//pageinfo/querystring/depart}&#38;depart_submit={//pageinfo/querystring/depart_submit}&#38;typeid={//pageinfo/querystring/typeid}&#38;viewid={//pageinfo/querystring/viewid}&#38;locationid={//pageinfo/querystring/locationid}&#38;bedrooms={//pageinfo/querystring/bedrooms}&#38;sleeps={//pageinfo/querystring/sleeps}&#38;amenityids={//pageinfo/querystring/amenityids}&#38;promocode={//pageinfo/querystring/promocode}#show" class="link-details white-btn">View Details</a>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;arrive_submit={//pageinfo/querystring/arrive_submit}&#38;depart={//pageinfo/querystring/depart}&#38;depart_submit={//pageinfo/querystring/depart_submit}&#38;typeid={//pageinfo/querystring/typeid}&#38;viewid={//pageinfo/querystring/viewid}&#38;locationid={//pageinfo/querystring/locationid}&#38;bedrooms={//pageinfo/querystring/bedrooms}&#38;sleeps={//pageinfo/querystring/sleeps}&#38;amenityids={//pageinfo/querystring/amenityids}&#38;promocode={//pageinfo/querystring/promocode}#show" class="link-details white-btn">View Details</a>
                                        </xsl:otherwise>
                                    </xsl:choose>
    							</div>
    						</div>
    					</div>
                        <!-- <xsl:if test="featuredpromo">
                            <div id="results-promo-details" class="results-promo-details cf">
                                <a href="#" class="promo-close">close</a>
                                <div class="results-promo-content">
                                    <div class="results-promo-short">
                                        <xsl:value-of select="featuredpromo/name"/>
                                    </div>
                                    <div class="results-promo-long">
                                        <xsl:value-of select="featuredpromo/shortdescription"/>
                                    </div>
                                </div>
                                <div class="results-promo-calendar">
                                    <xsl:call-template name="promo-calendar"/>
                                </div>
                            </div>
                        </xsl:if> -->

                        <xsl:choose>
                            <xsl:when test="(/output/pageinfo/querystring/promocode='' or not(/output/pageinfo/querystring/promocode)) and (featuredpromo)">
                                <div id="results-promo-details" class="results-promo-details cf">
                                    <a href="#" class="promo-close">close</a>
                                    <div class="results-promo-content">
                                        <div class="results-promo-short">
                                            <xsl:value-of select="featuredpromo/name"/>
                                        </div>
                                        <div class="results-promo-long">
                                            <xsl:value-of select="featuredpromo/shortdescription"/>
                                        </div>
                                    </div>
                                    <div class="results-promo-calendar">
                                        <xsl:call-template name="promo-calendar"/>
                                    </div>
                                </div>
                            </xsl:when>
                            <xsl:when test="(/output/pageinfo/querystring/promocode) and (/output/pageinfo/querystring/promocode = /output/module/reservations/data/record/promotions/record/promocode) ">
                                <div id="results-promo-details" class="results-promo-details cf">
                                    <a href="#" class="promo-close">close</a>
                                    <div class="results-promo-content">
                                        <div class="results-promo-short">
                                            <xsl:value-of select="//reservations/data/record/promotions/record/name"/>
                                        </div>
                                        <div class="results-promo-long">
                                            <xsl:value-of select="//reservations/data/record/promotions/record/shortdescription"/>
                                        </div>
                                    </div>
                                    <div class="results-promo-calendar">
                                        <xsl:call-template name="promo-calendar"/>
                                    </div>
                                </div>
                            </xsl:when>
                            <xsl:otherwise>
                            </xsl:otherwise>
                        </xsl:choose>
                    </li>
				</xsl:if>
			</xsl:for-each>
		</ul>
		<xsl:value-of select="//module/nounitsmessage/data/content" />
		<div class="results-similar">
			<div class="similar-heading">These Similar Units May Interest You</div>
			<ul id="similar-container" class="results-list">
                <xsl:choose>
                    <xsl:when test="count(//module/similarprops/data/record) &gt; 0 and count(//module/reservations/data/record) &lt; 1">
                        <xsl:for-each select="//module/similarprops/data/record">
                            <!-- data attributes are for map use (windows and location clustering) -->
                            <li id="unit-{@unit}" class="unit-li cf" data-location="{key('location',@locationid)}" data-id="{@id}" data-address="{name}" data-latitude="{@latitude}" data-longitude="{@longitude}" data-minrate="{format-number(@minrate, '#')}" data-weekrate="{format-number(@minrate * 7, '#,###')}" data-totalrate="{@totalrate}" data-dailyrate="{@dailyrate}" data-sortby="{@sortbyrate}" data-booklink="?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;depart={//pageinfo/querystring/depart}&#38;viewid={//pageinfo/querystring/viewid}&#38;promocode={//pageinfo/querystring/promocode}">
                                <div class="unit-inner cf">
                                    <div class="property-address mobile-property-address">
                                        <span class="list-marker"><xsl:value-of select="position()"/></span>
                                        <xsl:choose>
                                            <xsl:when test="count(/output/pageinfo/querystring/promocode) &gt; 0">
                                                <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&amp;promocode={/output/pageinfo/querystring/promocode}"><xsl:value-of select="name"/></a>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}"><xsl:value-of select="name"/></a>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </div>
                                    <div class="results-img-wrapper">
                                        <xsl:variable name="gallery-images">
                                            <xsl:for-each select="images/record">
                                                <xsl:value-of select="."/>
                                                <xsl:if test="position() != last()">
                                                    <xsl:text>,</xsl:text>
                                                </xsl:if>
                                            </xsl:for-each>
                                        </xsl:variable>
                                        <div class="room-gallery foo2">
                                            <xsl:variable name="currprop"><xsl:value-of select="@id"></xsl:value-of></xsl:variable>
                                            <xsl:variable name="firstimg"><xsl:value-of select="images/record[1]/filename"/></xsl:variable>
                                            <!-- data-id="{@id}" data-cycle-prev=".prev-{position()}" data-cycle-next=".next-{position()}" data-cycle-timeout="0" data-cycle-center-horz="true" data-cycle-center-vert="true" data-cycle-log="false" data-cycle-loader="true" data-cycle-progressive="#images-{$currprop}" data-cycle-caption-plugin="caption2" data-cycle-overlay-fx-sel=">div" -->
                                            <div class="room-slideshow" data-id="{@id}" data-cycle-caption-plugin="caption2" data-firstimg="{$firstimg}">
                                                <div class="cycle-overlay"></div>
                                                <!-- <img src="{$imgpath}{$currprop}/images/{$firstimg}" alt="{images/record[1]/caption}" data-cycle-title="{images/record[1]/caption}" />
                                                <script id="images-{$currprop}" type="text/cycle">
                                                    <xsl:for-each select="images/record[position() &gt; 1]">
                                                        <img src="{$imgpath}{$currprop}/images/{filename}" alt="{caption}" data-cycle-title="{caption}" />
                                                    </xsl:for-each>
                                                </script> -->
                                            </div>
                                        </div>
                                        <a href="#" class="room-prev prev-{position()}"></a>
                                        <a href="#" class="room-next next-{position()}"></a>
                                    </div>
                                    <div class="results-content">
                                        <div class="results-main">
                                            <div class="property-address">
                                                <span class="list-marker"><xsl:value-of select="position()"/></span>
                                                <xsl:choose>
                                                    <xsl:when test="count(/output/pageinfo/querystring/promocode) &gt; 0">
                                                        <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&amp;promocode={/output/pageinfo/querystring/promocode}"><xsl:value-of select="name"/></a>
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}"><xsl:value-of select="name"/></a>
                                                    </xsl:otherwise>
                                                </xsl:choose>
                                            </div>
                                            <xsl:if test="featuredpromo">
                                                <div class="results-promo"><xsl:value-of select="featuredpromo/name"/>&#160;<a href="#" rel="{@id}">Learn More</a></div>
                                            </xsl:if>
                                            <div class="results-description"><xsl:value-of select="substring(shortdescription, 1, 200 + string-length(substring-before(substring(shortdescription, 201),' ')))"/><!-- limits characters to 200, then backs up to a space so no partial words are output --> &#8230;</div>
                                        </div>
                                        <div class="results-pricing">
                                            <xsl:choose>
                                                <xsl:when test='@totalrate'>
                                                    <div class="range">
                                                        <div>For Dates Selected
                                                            <div class="price-low">$<xsl:value-of select='format-number(@dailyrate, "#")' /></div>per night
                                                        </div>
                                                        <div class="price-weekly"><span>$<xsl:value-of select='format-number(@totalrate, "#,###")' /> subtotal</span></div><span class="tax-disclaimer"><span class="info"></span>plus taxes &amp; fees</span>

                                                        <div class="search-unit-pricing-tooltip">
                                                            <xsl:value-of select="//module/reservfee/data/content" />
                                                        </div>
                                                    </div>
                                                </xsl:when>
												<xsl:when test='format-number(@dailyrate, "#") != "NaN" and @weeklyrate'>
													<div class="range"><div><div class="price-low">$<xsl:value-of select='format-number(@dailyrate, "#")' /></div>per night</div>
													<div class="price-weekly"><span>$<xsl:value-of select='format-number(@weeklyrate, "#,###")' /></span>/per week</div></div>
												</xsl:when>
												<xsl:when test='(format-number(@dailyminrate, "#") != "NaN" or format-number(@weeklyminrate, "#") != "NaN") and (@weeklyminrate)'>
													<div class="range"><div>Starting from<div class="price-low">$<xsl:value-of select='format-number(@dailyminrate, "#")' /></div>per night</div>
													<div class="price-weekly"><span>$<xsl:value-of select='format-number(@weeklyminrate, "#,###")' /></span>/per week</div></div>
												</xsl:when>
												<xsl:when test='(not(@weeklyrate) or not(@weeklyminrate))and //pageinfo/querystring/promocode/text()'>
													<div class="range"><div>Starting from<div class="price-low">$<xsl:value-of select='format-number(@dailyminrate, "#")' /></div>per night</div>
													<div class="price-weekly">Not Available/Per Week</div></div>
												</xsl:when>
												<xsl:otherwise>
													<div class="no-rates">Rates Not Available, Please Call <script type='text/javascript'>ShowNavisNCPhoneNumber();</script> to speak to a vacation planner or make a reservation.</div>
												</xsl:otherwise>
											</xsl:choose>

                                        <xsl:if test='format-number(@minrate, "#") != "NaN"'>
                                            <a href="/booking/guestinfo?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;arrive_submit={//pageinfo/querystring/arrive_submit}&#38;depart={//pageinfo/querystring/depart}&#38;depart_submit={//pageinfo/querystring/depart_submit}&#38;typeid={//pageinfo/querystring/typeid}&#38;viewid={//pageinfo/querystring/viewid}&#38;locationid={//pageinfo/querystring/locationid}&#38;bedrooms={//pageinfo/querystring/bedrooms}&#38;sleeps={//pageinfo/querystring/sleeps}&#38;amenityids={//pageinfo/querystring/amenityids}&#38;promocode={//pageinfo/querystring/promocode}#show" class="pricing-book orange-btn">Book Now</a>
                                        </xsl:if>
                                        </div>
                                        <div class="results-more">
                                            <ul class="more-attributes">
                                                <li class="view">
                                                    <xsl:for-each select="key('viewtype',@viewid)">
                                                        <xsl:value-of select="." />
                                                    </xsl:for-each>
                                                </li>
                                                <li class="bedrooms"><xsl:value-of select="@bedrooms" /> Bedroom</li>
                                                <li class="baths"><xsl:value-of select="@bathrooms" /> Bath</li>
                                                <li class="sleeps">Sleeps <xsl:value-of select="@sleeps" /></li>
                                            </ul>
                                            <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;arrive_submit={//pageinfo/querystring/arrive_submit}&#38;depart={//pageinfo/querystring/depart}&#38;depart_submit={//pageinfo/querystring/depart_submit}&#38;typeid={//pageinfo/querystring/typeid}&#38;viewid={//pageinfo/querystring/viewid}&#38;locationid={//pageinfo/querystring/locationid}&#38;bedrooms={//pageinfo/querystring/bedrooms}&#38;sleeps={//pageinfo/querystring/sleeps}&#38;amenityids={//pageinfo/querystring/amenityids}&#38;promocode={//pageinfo/querystring/promocode}#show" class="link-details white-btn">View Details</a>
                                        </div>
                                    </div>
                                   </div>
                                <xsl:if test="featuredpromo">
                                    <div id="results-promo-details" class="results-promo-details cf">
                                        <a href="#" class="promo-close">close</a>
                                        <div class="results-promo-content">
                                            <div class="results-promo-short">
                                                <xsl:value-of select="featuredpromo/name"/>
                                            </div>
                                            <div class="results-promo-long">
                                                <xsl:value-of select="featuredpromo/shortdescription"/>
                                            </div>
                                        </div>
                                        <div class="results-promo-calendar">
                                            <xsl:call-template name="promo-calendar"/>
                                        </div>
                                    </div>
                                </xsl:if>
                            </li>
                        </xsl:for-each>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:for-each select="//module/featured/data/record">
                            <!-- data attributes are for map use (windows and location clustering) -->
                            <li id="unit-{@unit}" class="unit-li cf" data-location="{key('location',@locationid)}" data-id="{@id}" data-address="{name}" data-latitude="{@latitude}" data-longitude="{@longitude}" data-minrate="{format-number(@minrate, '#')}" data-weekrate="{format-number(@minrate * 7, '#,###')}" data-totalrate="{@totalrate}" data-dailyrate="{@dailyrate}" data-sortby="{@sortbyrate}" data-booklink="?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;depart={//pageinfo/querystring/depart}&#38;viewid={//pageinfo/querystring/viewid}&#38;promocode={//pageinfo/querystring/promocode}">
                                <div class="unit-inner cf">
                                    <div class="property-address mobile-property-address">
                                        <span class="list-marker"><xsl:value-of select="position()"/></span>
                                        <xsl:choose>
                                            <xsl:when test="count(/output/pageinfo/querystring/promocode) &gt; 0">
                                                <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&amp;promocode={/output/pageinfo/querystring/promocode}"><xsl:value-of select="name"/></a>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}"><xsl:value-of select="name"/></a>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </div>
                                    <div class="results-img-wrapper">
                                        <xsl:variable name="gallery-images">
                                            <xsl:for-each select="images/record">
                                                <xsl:value-of select="."/>
                                                <xsl:if test="position() != last()">
                                                    <xsl:text>,</xsl:text>
                                                </xsl:if>
                                            </xsl:for-each>
                                        </xsl:variable>
                                        <div class="room-gallery foo">
                                            <xsl:variable name="currprop"><xsl:value-of select="@id"></xsl:value-of></xsl:variable>
                                            <xsl:variable name="firstimg"><xsl:value-of select="images/record[1]/filename"/></xsl:variable>
                                            <div class="cycle-slideshow" data-id="{@id}" data-cycle-prev=".prev-{position()}" data-cycle-next=".next-{position()}" data-cycle-timeout="0" data-cycle-center-horz="true" data-cycle-center-vert="true" data-cycle-log="false" data-cycle-loader="true" data-cycle-progressive="#images-{$currprop}" data-cycle-caption-plugin="caption2" data-cycle-overlay-fx-sel=">div">
                                                <div class="cycle-overlay"></div>
                                                <img src="{$imgpath}{$currprop}/images/{$firstimg}" alt="{images/record[1]/caption}" data-cycle-title="{images/record[1]/caption}" />
                                                <script id="images-{$currprop}" type="text/cycle">
                                                    <!-- <xsl:for-each select="images/record[position() &gt; 1]">
                                                        <img src="{$imgpath}{$currprop}/images/{filename}" alt="{caption}" data-cycle-title="{caption}" />
                                                    </xsl:for-each> -->
                                                </script>
                                            </div>
                                        </div>
                                        <a href="#" class="room-prev prev-{position()}"></a>
                                        <a href="#" class="room-next next-{position()}"></a>
                                    </div>
                                    <div class="results-content">
                                        <div class="results-main">
                                            <div class="property-address">
                                                <span class="list-marker"><xsl:value-of select="position()"/></span>
                                                <xsl:choose>
                                                    <xsl:when test="count(/output/pageinfo/querystring/promocode) &gt; 0">
                                                        <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&amp;promocode={/output/pageinfo/querystring/promocode}"><xsl:value-of select="name"/></a>
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}"><xsl:value-of select="name"/></a>
                                                    </xsl:otherwise>
                                                </xsl:choose>
                                            </div>
                                            <xsl:if test="featuredpromo">
                                                <div class="results-promo"><xsl:value-of select="featuredpromo/name"/>&#160;<a href="#" rel="{@id}">Learn More</a></div>
                                            </xsl:if>
                                            <div class="results-description"><xsl:value-of select="substring(shortdescription, 1, 200 + string-length(substring-before(substring(shortdescription, 201),' ')))"/><!-- limits characters to 200, then backs up to a space so no partial words are output --> &#8230;</div>
                                        </div>
                                        <div class="results-pricing">
                                            <xsl:choose>
                                                <xsl:when test='@totalrate'>
                                                    <div class="range">
                                                        <div>For Dates Selected
                                                            <div class="price-low">$<xsl:value-of select='format-number(@dailyrate, "#")' /></div>per night
                                                        </div>
                                                        <div class="price-weekly"><span>$<xsl:value-of select='format-number(@totalrate, "#,###")' /> subtotal</span></div><span class="tax-disclaimer"><span class="info"></span>plus taxes &amp; fees</span>

                                                        <div class="search-unit-pricing-tooltip">
                                                            <xsl:value-of select="//module/reservfee/data/content" />
                                                        </div>
                                                    </div>
                                                </xsl:when>
												<xsl:when test='format-number(@dailyrate, "#") != "NaN" and @weeklyrate'>
													<div class="range"><div><div class="price-low">$<xsl:value-of select='format-number(@dailyrate, "#")' /></div>per night</div>
													<div class="price-weekly"><span>$<xsl:value-of select='format-number(@weeklyrate, "#,###")' /></span>/per week</div></div>
												</xsl:when>
												<xsl:when test='(format-number(@dailyminrate, "#") != "NaN" or format-number(@weeklyminrate, "#") != "NaN") and (@weeklyminrate)'>
													<div class="range"><div>Starting from<div class="price-low">$<xsl:value-of select='format-number(@dailyminrate, "#")' /></div>per night</div>
													<div class="price-weekly"><span>$<xsl:value-of select='format-number(@weeklyminrate, "#,###")' /></span>/per week</div></div>
												</xsl:when>
												<xsl:when test='(not(@weeklyrate) or not(@weeklyminrate))and //pageinfo/querystring/promocode/text()'>
													<div class="range"><div>Starting from<div class="price-low">$<xsl:value-of select='format-number(@dailyminrate, "#")' /></div>per night</div>
													<div class="price-weekly">Not Available/Per Week</div></div>
												</xsl:when>
												<xsl:otherwise>
													<div class="no-rates">Rates Not Available, Please Call <script type='text/javascript'>ShowNavisNCPhoneNumber();</script> to speak to a vacation planner or make a reservation.</div>
												</xsl:otherwise>
											</xsl:choose>

                                        <xsl:if test='format-number(@minrate, "#") != "NaN"'>
                                            <a href="/booking/guestinfo?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;arrive_submit={//pageinfo/querystring/arrive_submit}&#38;depart={//pageinfo/querystring/depart}&#38;depart_submit={//pageinfo/querystring/depart_submit}&#38;typeid={//pageinfo/querystring/typeid}&#38;viewid={//pageinfo/querystring/viewid}&#38;locationid={//pageinfo/querystring/locationid}&#38;bedrooms={//pageinfo/querystring/bedrooms}&#38;sleeps={//pageinfo/querystring/sleeps}&#38;amenityids={//pageinfo/querystring/amenityids}&#38;promocode={//pageinfo/querystring/promocode}#show" class="pricing-book orange-btn">Book Now</a>
                                        </xsl:if>
                                        </div>
                                        <div class="results-more">
                                            <ul class="more-attributes">
                                                <li class="view">
                                                    <xsl:for-each select="key('viewtype',@viewid)">
                                                        <xsl:value-of select="." />
                                                    </xsl:for-each>
                                                </li>
                                                <li class="bedrooms"><xsl:value-of select="@bedrooms" /> Bedroom</li>
                                                <li class="baths"><xsl:value-of select="@bathrooms" /> Bath</li>
                                                <li class="sleeps">Sleeps <xsl:value-of select="@sleeps" /></li>
                                            </ul>
                                            <a href="/booking/vacation-rentals/hilton-head-rental-units?propertyid={@id}&#38;arrive={//pageinfo/querystring/arrive}&#38;arrive_submit={//pageinfo/querystring/arrive_submit}&#38;depart={//pageinfo/querystring/depart}&#38;depart_submit={//pageinfo/querystring/depart_submit}&#38;typeid={//pageinfo/querystring/typeid}&#38;viewid={//pageinfo/querystring/viewid}&#38;locationid={//pageinfo/querystring/locationid}&#38;bedrooms={//pageinfo/querystring/bedrooms}&#38;sleeps={//pageinfo/querystring/sleeps}&#38;amenityids={//pageinfo/querystring/amenityids}&#38;promocode={//pageinfo/querystring/promocode}#show" class="link-details white-btn">View Details</a>
                                        </div>
                                    </div>
                                   </div>
                                <xsl:if test="featuredpromo">
                                    <div id="results-promo-details" class="results-promo-details cf">
                                        <a href="#" class="promo-close">close</a>
                                        <div class="results-promo-content">
                                            <div class="results-promo-short">
                                                <xsl:value-of select="featuredpromo/name"/>
                                            </div>
                                            <div class="results-promo-long">
                                                <xsl:value-of select="featuredpromo/shortdescription"/>
                                            </div>
                                        </div>
                                        <div class="results-promo-calendar">
                                            <xsl:call-template name="promo-calendar"/>
                                        </div>
                                    </div>
                                </xsl:if>
                            </li>
                        </xsl:for-each>
                    </xsl:otherwise>
                </xsl:choose>
			</ul>
		</div>
        <!-- pagination -->
        <div class="pagination cf">
            <div class="holder-select">
                <form>
                    Show
                    <select>
                        <option>5</option>
                        <option selected="selected">10</option>
                        <option>15</option>
                        <option value="200">View All</option>
                    </select>
                    <label>properties per page</label>
                </form>
            </div>
            <div class="customBtns">
                <div class="holder"></div>
                <a class="arrowPrev blue-btn" href="#top"><span></span></a>
                <a class="arrowNext blue-btn" href="#top"><span></span></a>
            </div>
            <div class="current-page-wrapper"></div>
        </div>
	</div>

</xsl:template>
