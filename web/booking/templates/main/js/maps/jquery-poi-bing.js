(function( $ ){

	$.fn.bingMap = function( options ) {

		var settings = $.extend( {
			dataSource : '/poi',
			mapType	: 'poi', // Plugin map type { 'feature', 'poi', or 'both' }
	      	mapStyle : 'road', // Style of Map { 'aerial', 'auto', 'birdseye', 'collinsBart', 'mercator', 'ordnanceSurvey', 'road': }
			credentials	: 'AlhQ2tbqEMqXpqkcpMtPeOZE3b_LfrLrN9JSvHD3NqG2D7R00kfem-1QhN3L_ki7', // bing only // Credentials for each map. Get from https://www.bingmapsportal.com/
			useSingleIcon : true, 	//bing only (kill this once crud pin ids gone)	// if true, use same icon for all, without letters, numbers etc.
			singleIcon : { //bing only (kill this once crud pin ids gone)
				url : '/templates/main/images/map/pin-green.png', // path to uniform icon
				width : 24, 			// width of icon in px ( do not include 'px')
				height : 24 			// height of icon in px ( do not include 'px' )
			}, 
			smallIcon : { //bing only (kill this once crud pin ids gone)
				width : 24, 			// width of icon in px ( do not include 'px')
				height : 24 			// height of icon in px ( do not include 'px' )
			},
			useLargeIcon : true, 		//true uses intermediate large pin with poi name etc to trigger pinInfobox
			largeIcon : { //bing only (kill this once crud pin ids gone)
				width : 258, 			// width of icon in px ( do not include 'px')
				height : 35 			// height of icon in px ( do not include 'px' )
			},
			printPoints : false, 	// true shows the list of points when a category is clicked.
			needsIntl: false, // if true, won't lowercase category class name due to needing to allow any number of 'special' chars
			featuredCategory: 'featurepoint',	// if needsIntl: true, this will need to be whatever the category id would be, 
											// ie the category name with spaces and any & removed, so French/Spanish/Italian might be capitalized,
											// where English wouldn't be
			printFeatureFirst : true, // use for mapType 'both' if you want the feature-wrapper div before the category-wrapper in the markup
			showCat	: 'all', 	// will show non feature category when the map loads.  Can be a specific cateogory or set to "all" to show all categories.
			activeCatOnly: true, // is true, when a cetegory clicked, close other categories and clear pins
			showPoiID: true, // show id in list in addition to name
			showPinID: true, // show id on pin marker
			hover: false,
			zoom: 11,
			changeViewOnZoom: false, // if true, the map type will change when clicking on a point, or on user zoom
			zoomedView: {
				type: 'hybrid', // 'road', 'satellite', 'hybrid', 'terrain'
				level: 16
			},
			centerPoint	: '1',		// center point when map loads.  This will need to be the ID of the element from the json
			startPoint : '29.22889,-1.40625',	// same thing as center Point
			infoBoxOptions : {
				alignBottom: false, 	// false sets pin at top left corner, true at bottom left corner
				width : 300,			// set width here for positioning calculations in functions
				top : 10, 				// # of px open infoBox should be below controls
				horizontalOffset : 0,	// increasing # moves to right
				verticalOffset: 0,		// increasing # moves up; note, top: may be more precise when boxTiedToPin is false
				boxTiedToPin: false,	// true moves map with box to keep tied together (beak positioning etc.)
				displayCatIcons: true,	// if true, generates list to display category icons
				addDirections: true,
				directionsType: 'link' // link (adds directions link), form (adds form with "from" field)
			},
			// additions -  not all fully implemented / tested
			closeAllWindows	: true,	// true closes all pinInfobox on click of another whatever
			closeFeatureWindows	: true, //true close all pinInfobox when another feature item clicked
			closeCatWindows	: true 	//true close all pinInfobox when another category item clicked
		}, options);

		return this.each(function() {
			var map = null,
				id = null,
				data = null,
				categories = [],
				longcats = [],
				subcategories = [],
				featurepoints = [],
				contextPin = null,
				loadIds = null,
				items = [],
				layer,
				hashURL,
				currentPin=null,
				currentID=null,
				pinInfobox = null,
				zoomed = false;

			var catArray = new Array(),
				longCatArray = new Array(),
				subCatArray = new Array(),
				centerArray = new Array(),
				layers = new Object(),
				pinLayers = new Object(),
				queryArray = new Array();

			mapId=id=$(this).attr('id');
			maptype = settings.mapType;

			switch(settings.mapStyle){
				case 'aerial':
					mapStyle = Microsoft.Maps.MapTypeId.aerial;
					break;
				case 'auto':
					mapStyle = Microsoft.Maps.MapTypeId.auto;
					break;
				case 'birdseye':
					mapStyle = Microsoft.Maps.MapTypeId.birdseye;
					break;
				case 'collinsBart':
					mapStyle = Microsoft.Maps.MapTypeId.collinsBart;
					break;
				case 'mercator':
					mapStyle = Microsoft.Maps.MapTypeId.mercator;
					break;
				case 'ordnanceSurvey':
					mapStyle = Microsoft.Maps.MapTypeId.ordnanceSurvey;
					break;
				case 'road':
					/* falls through */
				default:
					mapStyle = Microsoft.Maps.MapTypeId.road;
					break;
			}
			switch(settings.zoomedView.type){
				case 'aerial':
					var zoomedViewType = Microsoft.Maps.MapTypeId.aerial;
					break;
				case 'auto':
					var zoomedViewType = Microsoft.Maps.MapTypeId.auto;
					break;
				case 'collinsBart':
					var zoomedViewType = Microsoft.Maps.MapTypeId.collinsBart;
					break;
				case 'mercator':
					var zoomedViewType = Microsoft.Maps.MapTypeId.mercator;
					break;
				case 'ordnanceSurvey':
					var zoomedViewType = Microsoft.Maps.MapTypeId.ordnanceSurvey;
					break;
				case 'road':
					var zoomedViewType = Microsoft.Maps.MapTypeId.road;
					break;
				case 'birdseye':
					/* falls through */
				default:
					var zoomedViewType = Microsoft.Maps.MapTypeId.birdseye;
					break;
			}

			$('#map-wrapper').addClass('type-'+maptype); // only needed to enable extra 'hook' for styling
			getData();							
			$(window).unload(function() { if(this._map) { this._map.Dispose(); } });

			function getData() {
				var obj = this;
				$.getJSON(settings.dataSource, function(data) {
					$.each(data.record.record, function(i) {
						items[i] = {};
				    	var data_id = $(this),
				    		id = data_id[0]['@attributes'].id,
				    		title = data_id[0]['@attributes'].title,
				    		category = data_id[0].category.record ? data_id[0].category.record : null, // array
				    		desc = data_id[0].description,
				    		website = data_id[0].website.length ? data_id[0].website : null,
				    		address = data_id[0].address.length ? data_id[0].address : null,
				    		image = data_id[0].images.record ? data_id[0].images.record : null, // array
				    		georss = data_id[0]['@attributes'].georss_point.length ? data_id[0]['@attributes'].georss_point : null;

				    	if(georss !== null) {
					    	items[i].id = id;
					    	items[i].title = title;
					    	items[i].description = desc;
					    	items[i].website = website;
					    	items[i].address = address;
					    	items[i].georss = georss;
					    	items[i].poiID = i;

							var catName = [],
								longName = [];
							if (typeof(category) === 'object') {
								catName = [];
								longName = [];
								$.each(category, function(j, val) {
									if (settings.needsIntl === true) {
										var tempCatName = val.replace(/\s/g, '').replace('&', '');
									} else {
										var tempCatName = val.replace(/\W/g, '').toLowerCase();
									}
									var longCatName = val.replace(/^\s+|\s+$/g, '');
									catName.push(tempCatName);										
									longName.push(longCatName);										
								});
							} else {
								catName = [];
								longName = [];
								if (settings.needsIntl === true) {
									var tempCatName = category.replace(/\s/g, '').replace('&', '');
								} else {
									var tempCatName = category.replace(/\W/g, '').toLowerCase();
								}
								var longCatName = category.replace(/^\s+|\s+$/g, '');
								catName.push(tempCatName);	
								longName.push(longCatName);	
							}
							items[i].category = catName;
							items[i].longName = longName;

							var imgName = [];
							if (typeof(image) === 'object' && image != null) {
								imgName = [];
								$.each(image, function(j, val) {
									var tempImgName = val;
									if ($.inArray(tempImgName,imgName) == -1) {
										imgName.push(tempImgName);
									}
								});
							} else if (image != null) {
								imgName = [];
								var tempImgName = image;
								imgName.push(tempImgName);	
							}
							items[i].image = imgName;
				    	}

					});
					createMap();
				});
				
			}

	
			function createMap() {
				// initial point to load map, 
				var fp = getLatLong(items[0]['georss']);
				// Retrieve the latitude and longitude values- normalize the longitude value
				var latVal = parseInt(fp[0]);
				var longVal = Microsoft.Maps.Location.normalizeLongitude(parseInt(fp[1]));
				var mapOptions = {
					credentials: settings.credentials,
					mapTypeId: mapStyle
				}
				map = new Microsoft.Maps.Map(document.getElementById(id), mapOptions);

				if(settings.useGeo === true) {
					var geoLocationProvider = new Microsoft.Maps.GeoLocationProvider(map);
					geoLocationProvider.getCurrentPosition({successCallback:displayCenter});					

					function successCallback(position) {
					    var latitude = position.coords.latitude;
					    var longitude = position.coords.longitude;
					    //console.log("Your location is: " + latitude + "," + longitude);
					}

					function errorCallback(error) {
					    //console.log(error);
					}

					if (Modernizr.geolocation) {
					    navigator.geolocation.getCurrentPosition(successCallback, errorCallback, { maximumAge: 0 });
					    //console.log("geolocation is enabled");
					} else {
					    //console.log("geolocation is NOT enabled");
					}

				}

				dataLayer = new Microsoft.Maps.EntityCollection();
				map.entities.push(dataLayer);
				dataLayer = map.entities.get(0);
				dataLayer.setOptions({ visible: true, zIndex: 500 });

				infoboxLayer = new Microsoft.Maps.EntityCollection();
				map.entities.push(infoboxLayer);
				infoboxLayer = map.entities.get(1);
				infoboxLayer.setOptions({ visible: true, zIndex: 1000 });

				// Set the map center
				map.setView({zoom: settings.zoom, center:new Microsoft.Maps.Location(latVal, longVal)});
				
				Microsoft.Maps.Events.addHandler(map, 'viewchangeend',mapZoomed);
		
				getCategories();
			
				var q = String(hashURL);
				q = q.split('?');
				q = q[1];
				if(q != undefined) {
					var query = parseQuery(q);
					if(query.id!==null&&query.id!==undefined) {
						loadIds = query.id.split(',');
					}
					viewQuery(loadIds);
				}
			}
	

			function displayCenter(args) {
				// Display the user location when the geo location request returns
				console.log("The user's location is " + args.center);
			}

			function viewQuery(loadIds) {
				$.each(loadIds, function(k, val2) {
					$.each(items, function(j) {
						if(items[j].id==val2) {
							queryArray.push(j);
						}								
					});						
				});
				if(queryArray.length==1) {
					var j = queryArray[0];
					if (items[j].category==settings.featuredCategory) {
						var qID = items[j].id;
						var loc = getCenterPoint(j);

						var pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+qID+'-large.png', width: settings.largeIcon.width, height: settings.largeIcon.height, zIndex:1002};
						var pinUpdate = getMapEntity(qID);
						pinUpdate.setOptions(pushpinOptions);													
						$('div#feature-list-'+qID).addClass('on');

						map.setView({center:loc, zoom:16});
					} else {
						var cat = items[queryArray[0]].category;
						loadPoints(cat);			
						var loc = getCenterPoint(j);			
						map.setView({center:loc, zoom:16});
					}					
				} else if(queryArray.length>1) {
					var cat = items[queryArray[0]].category;
					loadPoints(cat);		
				}
			}
		
			function getCategories() {
				var obj = this,
					divFeatureWrapper = $('<div />').attr('id','feature-wrapper'),
					divFeature = $('<div />').attr('id','feature-points');

				$.each(items, function(i, val) {
					if(settings.centerPoint==items[i].id) centerPoint=i;				   
					var dup = false
						longDup = false;
					catArray = items[i].category;
					longCatArray = items[i].longName;
					
					for (x in catArray){
						dup = false;

						$.each(categories, function(j, val2) {
							if ( catArray[x] == val2 ) {
								dup = true;
							}					
						});
						if ( !window.one ) {
							window.one = [];
						}
						window.one.push(val);
						
						if ((catArray[x] == settings.featuredCategory)&&(maptype!='poi')){
							loadfeaturepoints(val.id); 
							var anchorFeat = $('<a />').attr({'href':'#', 'class':'feat-link'}).html(val.title)
								.click(function(e) {
									e.preventDefault();
									showFeaturePoint(items[i].id,settings.featuredCategory);
									return false;
								}); // show property's info window when clicked from list - fix
							$('<div />').addClass('feature-list').attr('id','feature-list-'+val.id).html(anchorFeat)
							.click(function(e) {
								if(settings.closeFeatureWindow==true) { // this not in google??
									$('.infoWindow').hide();
								}
								loadfeaturepoints(val.id);
								return false;
							}).appendTo(divFeature);	
							$('div.feature-list').mouseover(function () {$(this).addClass('featurehover');});
							$('div.feature-list').mouseout(function () {$(this).removeClass('featurehover');});				
						}
						if(!dup) {
							categories.push(catArray[x]);
						}			
					}			

					for (y in longCatArray){
						longDup = false;
						$.each(longcats, function(j, val2) {
							if ( longCatArray[y] == val2 ) {
								longDup = true;
							}					
						});
						
						if(!longDup) {
							longcats.push(longCatArray[y]);
						}			
					}			
					
				});
				
				if((maptype=='both') && (settings.printFeatureFirst != undefined) && (settings.printFeatureFirst === true)) {
					$(divFeatureWrapper).append(divFeature);
					$('div#map-wrapper').append(divFeatureWrapper);
					printCategories();
				} else {
					if(maptype!='feature') {
						printCategories();
					}
					if((maptype=='both')||(maptype=='feature')) {
						$(divFeatureWrapper).append(divFeature);
						$('div#map-wrapper').append(divFeatureWrapper);
					}
				}

				if((settings.centerPoint!=null)) {
					var loc = getCenterPoint(centerPoint);											
					map.setView({center:loc, zoom:settings.zoom});
				}
				mapLoading = false;	

			}

			function printCategories() {
				// Print all of the categories
				var divWrapper = $('<div />').attr('id','category-wrapper'),
					div = $('<div />').attr('id','categories');

				$.each(categories, function(i, val) {
					var testsub = val.toString().substring(0,4),
						excludeCat = false;
					// for maptype 'both', hhis should remove any of the "feature" points category listing.
					if( settings.excludeFeaturedCategory === true && val === settings.featuredCategory) {
						excludeCat = true;
					}
					if ( (((val!='property') && (val != 'featurepoint')) && (excludeCat != true))  && testsub!='func') {
						var divCat = $('<div />').addClass('category').attr('id',val)	
						.click(function(e) {
							e.preventDefault();
							if (settings.activeCatOnly == true) {
								$(this).siblings('.on').removeClass('on').children('ul').slideUp();
								$(this).siblings('.off').removeClass('off').children('ul').slideUp();
								$('.marker-cat').parent().parent().hide();
							} 
							$('.infoWindow').hide();
							$(this).addClass('on'); 
							$(this).children('ul.point-list').slideDown();
							loadPoints(val);
							return false;
						}).appendTo(div);
						
						var anchorCat = $('<a />').attr({'href':'#', 'class':'cat-link'}).html(longcats[i]).appendTo(divCat);

						if(settings.printPoints==true) printPoints(val,divCat);
						
						$('div.category').mouseover(function () {$(this).addClass('hover');});
						$('div.category').mouseout(function () {$(this).removeClass('hover');});
					} 
				});
				$(divWrapper).append(div);
				$('div#map-wrapper').append(divWrapper);
				if((settings.showCat!='')&&(settings.showCat!=undefined)) {
				//if((settings.featuredCategory!=undefined)) {
					if(settings.showCat=='all'){
						$.each(categories, function(i, val) { 
							loadPoints(val);
							$('div#'+val).addClass('on').children('ul.point-list').slideDown();
						});
					} else {
						$.each(categories, function(i, val) {
							if(val == settings.showCat) {
								loadPoints(settings.showCat);
								$('div#'+settings.showCat).addClass('on').children('ul.point-list').slideDown();
							}
						});
						/*loadPoints(settings.showCat);
						$('div#'+settings.showCat).addClass('on').children('ul.point-list').slideDown();*/
						//loadPoints(settings.featuredCategory);
						//$('div#'+settings.featuredCategory).addClass('on').children('ul.point-list').slideDown();
					}
				}
			}		
		
			function printPoints(cat,divCat) {
				var obj=this;
				var cats = cat===null?categories:[cat];
				var catName = '';
				var pointUl = $('<ul />').attr('id','point-list-'+cat).addClass('point-list');
				$.each(cats, function(i, val) {
					layers[val] = new Array;
					$.each(items, function(j) {										   										   
							var catSelectedArray = new Array( );
							var currarray = String(items[j].category);
							catSelectedArray = currarray.split(",");
		
							for (x in catSelectedArray){
								var point;
								point = items[j]['georss']; 
								if((catSelectedArray[x]==val)&&point!='') {
									var pointLi = $('<li />').attr('id',items[j].id);
									var listHtml = items[j].title;
									if (settings.showPoiID === true)
										listHtml = '<span>'+items[j].poiID+'</span> '+items[j].title;  							
									var pointAnchor = $('<a />').attr('href','#').html(listHtml).click(function(){
										//showSinglePoint(items[j].id,cat);
										showSinglePoint(j,cat);
										//displayInfobox();
										return false;																 
									});
									$(pointLi).append(pointAnchor);
									$(pointUl).append(pointLi);							
								}
							}
						});
				});
				$(divCat).append(pointUl);
			}
			
			function loadPoints(cat) {
				var obj=this;
				var cats = cat===null?categories:[cat];
				var notMade = checkLayers(cat); //seems to always return true
				notMade = true;
				var catName = '';
				if(notMade) {
					var pinLayer = new Microsoft.Maps.EntityCollection();
					$.each(cats, function(i, val) {
						layers[val] = new Microsoft.Maps.EntityCollection();

						if(loadIds!==null) {
							$.each(loadIds, function(k, val2) {
								$.each(items, function(j) {
									if(items[j].id==val2) {
										var d = addPOIPoint(j,  cat, pinLayer, 'qstring');
										var loc = getCenterPoint(j);								
										centerArray.push(loc);
									}								
								});						
							});
						} else {
							$.each(items, function(j) {										   										   
								var catSelectedArray = new Array();
								var currarray = String(items[j].category);
								catSelectedArray = currarray.split(",");

								$.each(catSelectedArray,function(x) {
									if((catSelectedArray[x]==val)&&(!(val!=settings.featuredCategory && settings.excludeFeaturedCategory == true))) {
										var d = addPOIPoint(j, cat, pinLayer);
										var loc = getCenterPoint(j);								
										centerArray.push(loc);
									}
								});

							});
						}								
						if ( (cat != 'property') && (cat != 'featurepoint')) {	
							$('.feature-list').each(function() { 
								if ($(this).hasClass('on')) {	
									var featureon = $(this).attr('id').toString().substr(13,1);
									var mapLayers = map.entities.getLength();
									for(var h=0; h<mapLayers; h++) {
										var l =map.entities.indexOf(h);
										if(l.id!=undefined && l.id==featureon) {
											if ( l.getVisible() ) {
												var pushpinOptions = {icon: '', typeName: 'idx-'+j+' map-marker-small map-marker-sa-'+items[j].id, width: settings.smallIcon.width, height: settings.smallIcon.height, zIndex:1002};
												var pinUpdate = getMapEntity(featureon);
												pinUpdate.setOptions(pushpinOptions);	
												$(this).removeClass('on');
											}
										}
									}
								}
							});					
						}
						//entityIdx = map.entities.getLength();
						entityIdx = map.entities.get(0); // replaced getLength() with hard layer to preserve correct pin z-index;
						pinLayers[cat]=entityIdx;
						//map.entities.push(pinLayer);
						dataLayer.push(pinLayer);
						map.setView({ bounds: Microsoft.Maps.LocationRect.fromLocations(centerArray)  })
						centerArray.length = 0;
						loadIds = null;
					});
				}
			}
		
			function showSinglePoint(j,cat) {
				var obj=this;
				$('.infoWindow').hide();
				var pinLayer = new Microsoft.Maps.EntityCollection();
				layers[cat] = new Microsoft.Maps.EntityCollection();
				var d = addPOIPoint(j, cat, pinLayer);
				var loc = getCenterPoint(j);
				centerArray.push(loc);
				entityIdx = map.entities.get(0);
				pinLayers[cat]=entityIdx;
				dataLayer.push(pinLayer);
				map.setView({ bounds: Microsoft.Maps.LocationRect.fromLocations(centerArray) , center: loc, zoom:16 });
				for (var i = 0; i< pinLayer.getLength(); i++) {
					var elt = pinLayer.get(i);
					var typeName = elt.getTypeName(); // returns all of the classes
					var n = typeName.split(' ');
					q = n[0].split('-');
					var k = q[1];
					if( k == j ) {
						addInfoboxPOI(elt);
					}
				}
				centerArray.length = 0;
				loadIds = null;
			}

			function showFeaturePoint(idx,cat) {
				var obj=this;
				$('.infoWindow').hide();
				var pinLayer = new Microsoft.Maps.EntityCollection();
				layers[cat] = new Microsoft.Maps.EntityCollection();
				var d = addPOIPoint(idx, cat, pinLayer);
				var loc = getCenterPoint(idx);
				centerArray.push(loc);
				entityIdx = map.entities.get(0);
				pinLayers[cat]=entityIdx;
				dataLayer.push(pinLayer);
				map.setView({ bounds: Microsoft.Maps.LocationRect.fromLocations(centerArray) , center: loc, zoom:16 });
				for (var i = 0; i< pinLayer.getLength(); i++) {
					var elt = pinLayer.get(i);
					var typeName = elt.getTypeName(); // returns all of the classes
					var n = typeName.split(' ');
					q = n[0].split('-');
					var k = q[1];
					//if( k == j-1 ) { // test, may actually need this version
					if( k == j ) {
						addInfoboxPOI(elt);
					}
				}
				centerArray.length = 0;
				loadIds = null;
			}
	
			/*function getMapPin(pinid,pincat) {
				for (var i = 0; i < map.entities.getLength(); i++) {
					var elt = map.entities.get(i);
					var eltId = elt.getId();
					if (items[eltId].id == pinid.toString())
						return elt;					
				}		
			}*/
			
			function loadfeaturepoints(featureid) {
				
				featureid=featureid.toString();
				var notMade = checkfeaturepoints(featureid);
				if(notMade) {
					layers[featureid] = new Microsoft.Maps.EntityCollection();			
					
					$.each(items, function(j) {
						if (featureid == items[j].id) {	
							var d = addfeaturePoint(j, settings.featuredCategory);
							var loc = getCenterPoint(j);
							centerArray.push(loc);
						}							
					});
					map.setView({ bounds: Microsoft.Maps.LocationRect.fromLocations(centerArray)  })
					centerArray.length = 0;
				}		
			}
		
			function checkLayers(cat) {	
				
				var mapLayers = map.entities.getLength();
				entityIdx=pinLayers[cat];
				l=map.entities.get(entityIdx);

				for (var i = 0; i < map.entities.getLength(); i++) {
					var elt = map.entities.get(i);
				}					
				if(l!=undefined) {
					if ( l.getVisible() ) {
						l.setOptions({ visible: false });
						$('div#'+cat).removeClass('on');
						$('div#'+cat).addClass('off');
					} else {
						l.setOptions({ visible: !l.getVisible() });
						if ( (cat != 'property') && (cat != settings.featuredCategory)) {
							$('.feature-list').each(function() { 
								if ($(this).hasClass('on')) {
									var featureon = $(this).attr('id').toString().substr(13,1);
									var pushpinOptions = {icon: '', typeName: 'idx-'+j+' map-marker-small map-marker-sb-'+featureon, width: settings.smallIcon.width, height: settings.smallIcon.height};
									var pinUpdate = getMapEntity(featureon);
									pinUpdate.setOptions(pushpinOptions);
									$(this).removeClass('on');
								}
							});
							var cats = cat===null?categories:[cat];
							$.each(cats, function(i, val) {
																			
								$.each(items, function(j) {										   										   
									var catSelectedArray = new Array( );
									var currarray = String(items[j].category);
									catSelectedArray = currarray.split(",");
			
									for (x in catSelectedArray){
										if(catSelectedArray[x]==val) {
											var loc = getCenterPoint(j);
											centerArray.push(loc);
										}
									}
								});
							});
							map.setView({ bounds: Microsoft.Maps.LocationRect.fromLocations(centerArray)  })
							centerArray.length = 0;	
						}
										
						$('div#'+cat).removeClass('off');
						$('div#'+cat).addClass('on');	
					}
					return false;
				} 			
				return true;
			}
	
			function checkfeaturepoints(featureid) {
				featureid = featureid.toString()
				var mapLayers = map.entities.getLength();
				l=layers[featureid];
				if(l!=undefined) {
					if ( l.getVisible() ) {					
						if ( $('div#feature-list-'+featureid).hasClass('on') == false ) {
							// edit this !!																		
							$.each(items, function(j) {
								if (featureid == items[j].id) {
									featureid = parseFloat(featureid);
									var point;
									point = getLatLong(items[j]['georss']); 

									if (settings.changeViewOnZoom === true) {
										var zoomedViewKind = zoomedViewType;
									} else {
										var zoomedViewKind = mapStyle;
									}

									map.setView({mapTypeId : zoomedViewType, zoom: settings.zoomedView.level, center: new Microsoft.Maps.Location(parseFloat(point[0]), parseFloat(point[1])) });
									
									currentID = featureid;

									if (settings.useLargeIcon==true) {
										var pushpinOptions = {icon: '', text: items[j]['title'], typeName: 'idx-'+j+' map-marker-large map-marker-la-'+items[j].id, width: settings.largeIcon.width, height: settings.largeIcon.height, zIndex:1002};
									} else if (settings.useSingleIcon==true) {
										var pushpinOptions = {icon: settings.singleIcon.url, width: settings.singleIcon.width, height: settings.singleIcon.height, zIndex:1002};
									} else {
										var pushpinOptions = {icon: '', text: items[j].id, typeName: 'idx-'+j+' map-marker map-marker-sc-' + items[j].id, width: settings.smallIcon.width, height: settings.smallIcon.height, zIndex:1002};
									}

									var pinUpdate = getMapEntity(featureid);
									pinUpdate.setOptions(pushpinOptions);										
									
								}
							});								
						} else {
							// edit this !!						
							var pinidx;
							$.each(items, function(j) {
								if (featureid == items[j].id) {
									pinidx = j;
								}
							});	
							var pushpinOptions = {icon: '', typeName: 'idx-'+j+' map-marker-small map-marker-sd-'+items[pinidx].id, width: settings.smallIcon.width, height: settings.smallIcon.height};
							var pinUpdate = getMapEntity(featureid);
							pinUpdate.setOptions(pushpinOptions);
							
							$('div#feature-list-'+featureid).removeClass('on');
							//$('div#feature-list-'+featureid).addClass('off');
						}
						$('.feature-list').each(function() { 
							var featureon = $(this).attr('id').toString().substr(13); // extract the number
							if ($(this).hasClass('on') && (featureon!=featureid)) {		
								var pushpinOptions = {icon: '', typeName: 'idx-'+j+' map-marker-small map-marker-se-'+featureon, width: settings.smallIcon.width, height: settings.smallIcon.height};
								var pinUpdate = getMapEntity(featureon);
								pinUpdate.setOptions(pushpinOptions);
								$(this).removeClass('on');
							}
						});
					} else {
						var pushpinOptions = {icon: '', typeName: 'idx-'+j+' map-marker-small map-marker-sf-'+items[j].id, width: settings.smallIcon.width, height: settings.smallIcon.height};
						var pinUpdate = getMapEntity();
						pinUpdate.setOptions(pushpinOptions);
					}
					return false;
				} 			
				return true;
			}
		
			function addPOIPoint(j, cat, pinLayer, cstm) {
				var info = '';
				var point;
				point = getLatLong(items[j]['georss']);
				if (point != '' && point!='null,null') {
					var loc = new Microsoft.Maps.Location(parseFloat(point[0]), parseFloat(point[1]));
					var thisPin = items[j].id.toString();
					var pinLabel = (settings.showPinID === true) ? items[j].poiID.toString() : '';
					if ( items[j].category.length > 1 ) {
						var pushpinOptions = {icon: '', text: pinLabel, typeName: 'idx-'+j+' pin-'+thisPin+' map-marker-small marker-cat marker-cat-multicat', width: settings.smallIcon.width, height: settings.smallIcon.height}; 
					} else {
						var pushpinOptions = {icon: '', text: pinLabel, typeName: 'idx-'+j+' pin-'+thisPin+' map-marker-small marker-cat marker-cat-'+items[j].category, width: settings.smallIcon.width, height: settings.smallIcon.height}; 
					}			
					var pin = new Microsoft.Maps.Pushpin(loc, pushpinOptions); 
					pinLayer.push(pin);
		
					// Add a handler for the pushpin click event.
					Microsoft.Maps.Events.addHandler(pin, 'click', displayInfobox);
					if(settings.hover==true) {
						Microsoft.Maps.Events.addHandler(pin, 'mouseover', displayInfobox);
					}
				}
			}
		
			function addfeaturePoint(j, cat, cstm) {
				
				var info = '';
				var point;
				point = getLatLong(items[j]['georss']);

				if (point != '' && point!='null,null') {
					var loc = new Microsoft.Maps.Location(parseFloat(point[0]), parseFloat(point[1]));
					if (cstm=='qstring') {
						var pushpinOptions = {icon: '', typeName:'idx-'+j+' feature map-marker-large map-marker-lb-'+items[j].id, width: settings.largeIcon.width, height: settings.largeIcon.height, id:j.toString()}; 
					}
					else {
						if (settings.useSingleIcon == true)
							pushpinOptions = {icon: settings.singleIcon.url, text: j, width: settings.singleIcon.width, height: settings.singleIcon.height, id:j.toString(), typeName:'feature'};
						else
							var pushpinOptions = {icon: '', text: items[j].id, typeName: 'idx-'+j+' feature map-marker-small map-marker-sg-'+items[j].id, width: settings.smallIcon.width, height: settings.smallIcon.height, id:j.toString()};
					} 
					var pin = new Microsoft.Maps.Pushpin(loc, pushpinOptions); 
					map.entities.push(pin);
		
					// Add a handler for the pushpin click event.
					Microsoft.Maps.Events.addHandler(pin, 'click', displayInfobox);
				}
			}
		  
	  
		/*
			Helper Functions
		*/
		
		function getLatLong(point) {
			var pts = point.split(' ');
			return pts;
		}	  
		  
		function getCenterPoint(j) { 
			var centerPoint; 		
			centerPoint = getLatLong(items[j]['georss']); 

			if (centerPoint != ''&&centerPoint!='null,null')
				return loc = new Microsoft.Maps.Location(parseFloat(centerPoint[0]), parseFloat(centerPoint[1]));
			else
				return false; 
		 }

		function setBestView() {
			map.setView({ bounds: Microsoft.Maps.LocationRect.fromLocations(centerArray)  })
			centerArray.length = 0; 		 		 
		 }

		function changePin() {
			if (currentID != null) {
				if (settings.useSingleIcon == true) {
					var pushpinOptions = {icon: settings.singleIcon.url, width: settings.singleIcon.width, height: settings.singleIcon.height}; 						
				} else {
					var pushpinOptions = {icon: '', typeName: 'map-marker-small map-marker-sh-'+currentID, width: settings.smallIcon.width, height: settings.smallIcon.height}; 						
				}
				var pinUpdate = getMapEntity(currentID);
				pinUpdate.setOptions(pushpinOptions);
				$('div#feature-list-'+currentID).removeClass('on');
			}
		}
		
		function parseQuery(str) {
			var vars = str.split("&");
			var pairs = {};
			for (var i=0;i<vars.length;i++) {
				var p = vars[i].split("=");
				pairs[p[0]] = this.urlDecode(p[1]);
			}
			return pairs;
		}
			
		function urlDecode(encodedString) {
			var output = encodedString;
			var binVal, thisString;
			var myregexp = /(%[^%]{2})/;
			while ((match = myregexp.exec(output)) !== null && match.length > 1 && match[1] !== '') {
				binVal = parseInt(match[1].substr(1),16);
				thisString = String.fromCharCode(binVal);
				output = output.replace(match[1], thisString);
			}
			return output;
		}


		// ***** Hide Info Box on Zoom.  Also need to hide Infobox on Pan
		function OnStartZoom(e){
			CustomHideInfoBox(_currentShape);
		}
		
		function mapZoomed(e){
			if (pinInfobox!=null){
				var isvisible=pinInfobox.getVisible(); 
				if(isvisible==true) {
				}
			}
			var zoom = map.getZoom();
			if(map.getZoom()>=16) {
				zoomed=true;
			}
		
			if(zoomed==true) {
				if (zoom >= 16) {
				} else { 
					// Set Map to Road
					map.setView({mapTypeId : Microsoft.Maps.MapTypeId.road});
					
					// Change Icon to small
					changePin();
					
					// Remove Current State from pin
					$('div.feature-list').each(function() { 														  
						var featureon = $(this).attr('id').toString().substr(11,2);
						if ( $(this).hasClass('on') ){							
							$.each(items, function(k) {
								if (featureon != items[k].id) { 
									$('div#feature-list-'+featureon).removeClass('on'); 
								}
							});
						}
					});						
					zoomed=false;
				}			
			}	
		}
		
		function getMapEntity(pinid) {
			for (var i = 0; i < map.entities.getLength(); i++) {
				var elt = map.entities.get(i);
				var eltId = parseFloat(elt.getId());
				if (items[eltId].id == pinid.toString())
					return elt;					
			}
		}


		/*  
			Infobox Controllers
		*/
		
		function showInfobox(internalId) {
		  for (var i = 0; i < map.entities.getLength(); i++) {
			var elt = map.entities.get(i);
			if (elt.dbObject != null) {
			  if (elt.dbObject.internalId == internalId) {
				addInfoboxPOI(elt);
			  }
			}
		  }
		}

		function displayInfobox(e) {
		  if (e.targetType == 'pushpin') {
			if (e.target != null) {
			  currentPin = e.target;
			  var pinReference = e.target;
			  addInfoboxPOI(pinReference);
			}
		  }

		}

		function hideInfobox() {
			$('.infoWindow').hide();
		}

/* infoBox window functions */

		var getTitle = function(j) {
			if(items[j].title!==''&&items[j].title!==null) {
				info += '<h4>'+items[j].title+'</h4>';
			}
		}
		var getDescription = function(j) {
			if(items[j].description!==''&&items[j].description!==null) {
				info += '<div class="description">' + items[j].description + '</div>';
			}
		}
		var getCatIconList = function(j) {
			var catarraytemp = String(items[j].category);
			catarraytemp = catarraytemp.split(",");
			info += '<ul class="itemsub">'; // for category icons
			for (x in catarraytemp){								
				info += '<li id="iconcat-'+ catarraytemp[x] + '">' + catarraytemp[x] + '</li>';
			}
			info += '</ul>';
		}
		var getAddress = function(j) {
			if(items[j].address!==''&&items[j].address!==null) {
				info += '<p>' + items[j].address + '</p>';
			}
		}
		var getImage = function(j) {
			if(items[j].image!==''&&items[j].image!==null) {
				info += '<div class="inset"><img src="'+items[j].image+'" alt="" /></div>';
			}
		}
		var getWebsite = function(j) {
			if(items[j].website!==''&&items[j].website!==null) {
				info += '<a class="website" href="'+items[j].website+'" target="_blank">'+items[j].website+'</a>';
			}
		}

/* end window functions */

		function addInfoboxPOI(elt) {	
			var infoboxLayer = new Microsoft.Maps.EntityCollection();
			map.entities.push(infoboxLayer);
			infoboxLayer = map.entities.get(1);
			infoboxLayer.setOptions({ visible: true, zIndex: 1000 });
			var infoboxOptions;
			var typeName = elt.getTypeName(); // returns all of the classes
			var n = typeName.split(' ');
			q = n[0].split('-');
			var j = q[1];

			// need to find way to set markup variations with settings
			info = '<div class="infoWindow">';
				info += '<div class="infoWindowContent">';
				info += '<div class="close-box" onclick="$('+"'.infoWindow'"+').hide()">[x]</div>';
				if(items[j].category!==''&&items[j].category!==undefined) {
					/*if ( items[j].category.length > 1 ) {
						if(settings.infoBoxOptions.displayCatIcons==true) {
							getCatIconList(j);
						}
					}*/ 
					getTitle(j);
					getImage(j);
					getAddress(j);
					getDescription(j);
					getWebsite(j);				
				}
				if (settings.infoBoxOptions.addDirections === true) {
					var directionsType = settings.infoBoxOptions.directionsType;
					var geoURL = items[j].georss.replace(/\s/g, '_');
					switch(directionsType) {
						case 'form':
							info += '<div class="directions-links">'+
										'<form class="directions">'+
											'<input onblur="processLink()" type="text" name="address" id="address" maxlength="100" class="textfield" value="" />'+
											'<a class="btn" target="_blank" href="#">Directions</a>'+
										'</form>'+
									'</div>';
							processLink = function(){
								var start = $('#mapDiv .directions #address').val().replace(/\s/g, '%20');
								var dirLink = 'http://bing.com/maps/default.aspx?v=3&rtp=adr.'+start+'~pos.'+geoURL;
								$('.directions-links .btn').attr('href', dirLink);
							}
							break;
						case 'link':
						default:
							info += '<div class="direction-links"> ' +
										'<a href="http://bing.com/maps/default.aspx?v=3&rtp=~pos.'+geoURL+'" target="_blank">Directions</a>'+
									'</div>';
							break;
					}
				}// http://bing.com/maps/default.aspx?v=3&rtp=adr.4835%20Cordell%20Ave%20Bethesda%20MD~pos.45.23423_-122.1232

				info += '</div>';
			info += '</div>';

			infoboxOptions = { width : settings.infoBoxOptions.width, offset:new Microsoft.Maps.Point(settings.infoBoxOptions.horizontalOffset,settings.infoBoxOptions.verticalOffset), showPointer: true, visible: true, zIndex: 100, htmlContent: info };

			if (pinInfobox != null) {
				pinInfobox.setLocation(elt.getLocation());
				pinInfobox.setOptions(infoboxOptions);
			
			} else {
				pinInfobox = new Microsoft.Maps.Infobox(elt.getLocation(), infoboxOptions);
				//map.entities.push(pinInfobox); // changed to preserve z-index relationship between pin and infobox
				infoboxLayer.push(pinInfobox);
			}

			var topOffset = settings.infoBoxOptions.top + 5;
		  
			var buffer = 25; 
			var infoboxOffset = pinInfobox.getOffset();
			var infoboxAnchor = pinInfobox.getAnchor();
			var infoboxLocation = map.tryLocationToPixel(elt.getLocation(), Microsoft.Maps.PixelReference.control);
			var dx = infoboxLocation.x + infoboxOffset.x - infoboxAnchor.x;
			var dy = infoboxLocation.y - topOffset - infoboxAnchor.y;
			
			if(dy < buffer){
				dy *= -1;
				dy += buffer;
			}else{
				dy = 0;
			}
			
			if(dx < buffer){
				dx *= -1;
				dx += buffer; 
			}else{		
				dx = map.getWidth() - infoboxLocation.x + infoboxAnchor.x - pinInfobox.getWidth();
				if(dx > buffer){
					dx = 0;
				}else{
					dx -= buffer;
				}
			}
			if(dx != 0 || dy != 0){
				if(settings.infoBoxOptions.boxTiedToPin == true) {
					map.setView({ centerOffset : new Microsoft.Maps.Point(dx-settings.infoBoxOptions.horizontalOffset,dy+settings.infoBoxOptions.verticalOffset), center : map.getCenter()});
				} else {
					map.setView({ centerOffset : new Microsoft.Maps.Point(dx,dy), center : map.getCenter()});
				}

			}

			$('.infoWindow').parent().show();	
			$('.infoWindow').parent().parent().css('z-index',1010);	
			$('.map-marker-large').css('visibility', 'hidden');
			pinInfobox.setOptions({visible:true});
			if(settings.infoBoxOptions.alignBottom === false) {
				var vAdj = '';
				//vAdj = $('.infoWindow').parent().parent().css('top') + settings.infoBoxOptions.verticalOffset;
				vAdj = $('.infoWindow').parent().parent().css('top');
				//$('.infoWindow').parent().css({ 'position':'relative', 'top':vAdj});
			}
		}

		function hideInfobox(box) {
			pinInfobox.setOptions({ visible: false });
		}
		
		function HideInfoBoxWindow() {
			pinInfobox.setOptions({ visible: false });
		}
		
		
		
		function zoomHere(lat, lng, zoom, hideBox) {
			map.setView({ center: new Microsoft.Maps.Location(lat, lng), zoom: zoom });
		
			if (hideBox) {
				hideInfobox(pinInfobox);
			}
		}


		/*  
															Used For Custom Pin via Address Form
		
		*/
		function ClickGeocode(credentials){
			map.getCredentials(MakeGeocodeRequest);
		 }
		
		function MakeGeocodeRequest(credentials) {
			var geocodeRequest = "http://dev.virtualearth.net/REST/v1/Locations/" + document.getElementById('cstm-address').value + "?output=json&jsonp=GeocodeCallback&key=" + credentials;
			CallRestService(geocodeRequest);
		 }
		
		function GeocodeCallback(result) {
			if (result &&
				   result.resourceSets &&
				   result.resourceSets.length > 0 &&
				   result.resourceSets[0].resources &&
				   result.resourceSets[0].resources.length > 0) 
			{
			   // Set the map view using the returned bounding box
			   var bbox = result.resourceSets[0].resources[0].bbox;
			   var viewBoundaries = Microsoft.Maps.LocationRect.fromLocations(new Microsoft.Maps.Location(bbox[0], bbox[1]), new Microsoft.Maps.Location(bbox[2], bbox[3]));
			   map.setView({ bounds: viewBoundaries});
		
			   // Add a pushpin at the found location
			   var location = new Microsoft.Maps.Location(result.resourceSets[0].resources[0].point.coordinates[0], result.resourceSets[0].resources[0].point.coordinates[1]);
			   var pushpin = new Microsoft.Maps.Pushpin(location);
			   map.entities.push(pushpin);
			}
		 }
		
		function CallRestService(request) {
			var script = document.createElement("script");
			script.setAttribute("type", "text/javascript");
			script.setAttribute("src", request);
			document.body.appendChild(script);
		 }	
 
 
     }); // End Plugin .each return

	}; // End plugin

})( jQuery );




