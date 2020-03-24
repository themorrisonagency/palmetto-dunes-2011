(function( $ ){

	$.fn.googleMap = function( options ) {

		var settings = $.extend( {
			dataSource: '/application/themes/theme_palmetto/js/maps/poi.json', // '/poi' is default - if multiple maps on a site, will need to set for each. must be a standalone file, not one with rendered content
			mapType: 'both', // feature: only show the featured category (#feature-wrapper), poi: only show the categories (#category-wrapper), both: show both feature and category divs
	      	mapStyle : 'road', // Style of Map { 'road', 'satellite', 'hybrid', 'terrain' }
			disableDefaultUI: false, // google only
			mapTypeControl: true,
			mapTypeControlOptions: {
				style: 'default' // horizontal, dropdown, default
			},
			zoomControl: true,
			zoomControlOptions: {
				style: 'large' // small, large, default
			},
			scaleControl: true, // google only
			styledMap: false, // google only // if true, will also need styles settings
			printPoints: true, // true shows the list of points when a category is clicked.
			needsIntl: false, // if true, won't lowercase category class name due to needing to allow any number of 'special' chars
			featuredCategory: 'activities',	// if needsIntl: true, this will need to be whatever the category id would be,
											// ie the category name with spaces and any & removed, so French/Spanish/Italian might be capitalized,
											// where English wouldn't be
			printFeatureFirst: true, // use for mapType 'both' if you want the feature-wrapper div before the category-wrapper in the markup
			activeCatOnly: true, // if true, when a cetegory clicked, close other categories and clear pins
			showPoiID: true, // show id in list in addition to name
			showPinID: false, // show id on pin marker
			hover: false,
			zoom: 15,
			changeViewOnZoom: true, // if true, the map type will change when clicking on a point, or on user zoom
			zoomedView: {
				type: 'hybrid', // 'road', 'satellite', 'hybrid', 'terrain'
				level: 16
			},
			//centerPoint: 1, // center point when map loads.  This will need to be the ID of the element from the json
			excludeFeaturedCategory: false, // if true, the featured category will not be added to the category wrapper (usually for mapType:both)
			disableShadows: false, // can likely leave false if using richmarker.js
			resetViewOnCategoryChange: true, // left as an option, but setting to false likely to mess things up, so just don't
			infoBoxOptions: {
				alignBottom:true, // false sets pin at top left corner, true at bottom left corner
				disableAutoPan: false,
				maxWidth: 0, // set at 0 for no maxWidth
				horizontalOffset: -150,
				verticalOffset: 0,
				zIndex: null,
				infoBoxClass: 'window-feature',
				boxStyle: {
						//must include at least a width - css will act only on the div inside of this one
						//if you have a side beak, include its width in the boxStyle.width
						//add beak to boxStyle.background - position in conjunction with infoBoxOptions alignBottom and pixelOffset
						//deal with beak width/height (depending on whether beak is top/right/left/bottom) in css .infoWindowContent margin
					width: '300px' // width is mandatory! cannot be overriden by css file to increase
				},
				closeBoxMargin: '0', // standard css top right bottom left format
				closeBoxURL: 'http://www.google.com/intl/en_us/mapfiles/close.gif', // required - can be defined as a image, or hidden with css ( .infoBox>img { visibility:hidden; } ) to use the one in the infoBox html
				horizontalClearance: 25,
				verticalClearance: 25,
				isHidden: false,
				usePlacesData: false, // if true, will pull data from google places if present //not fully implemented
				pane: 'floatPane', // floatPane is 'above' mapPane - best for windows
				enableEventPropagation: false,
				addDirections: true,
				directionsType: 'link'
			},
			infoBoxHtmlOptions: { // sets which attributes from poi json should be included in infoBox
		        useImage: true,
		        useTitle: true,
		        useDescription: true,
		        useWebsite: true,
		        useAddress: false,
		        addDirections: true,
		        directionsType: 'link' // link (adds directions link), form (adds form with "from" field)
		    }
		}, options);
		// deal with vendor-specific tweaks to settings
		settings.infoBoxOptions.pixelOffset = new google.maps.Size(settings.infoBoxOptions.horizontalOffset, settings.infoBoxOptions.verticalOffset);
		settings.infoBoxOptions.infoBoxClearance = new google.maps.Size(settings.infoBoxOptions.horizontalClearance, settings.infoBoxOptions.verticalClearance);

		return this.each(function() {

			var map,
				items = [],

				categories = [],
				catArray = [],
				longcats = [],
				longCatArray = [],

				subcategories = [],
				subcatArray = [],
				longsubcats = [],
				longSubCatArray = [],

				hashURL,
				property,
				start = true,
				loadIds = null,
				bounds,
				maptype,
				poiID,
				bounds,
				mapId = id = $(this).attr('id'),
				mapType = settings.mapType;

			var infoWindow = new google.maps.InfoWindow({maxWidth:300});
			var infoBox = new InfoBox();
			var layers = new Object();
			var centerArray = new Array();
			var queryArray = new Array();
			var pinLayers = new Object();

			switch(settings.mapStyle){
				case 'satellite':
					mapStyle = google.maps.MapTypeId.SATELLITE;
					break;
				case 'hybrid':
					mapStyle = google.maps.MapTypeId.HYBRID;
					break;
				case 'terrain':
					mapStyle = google.maps.MapTypeId.TERRAIN;
					break;
				case 'road':
				default:
					mapStyle = google.maps.MapTypeId.ROADMAP;
					break;
			}
			switch(settings.mapTypeControlOptions.style){
				case 'horizontal':
					var mapControlOptions = google.maps.MapTypeControlStyle.HORIZONTAL_BAR;
					break;
				case 'dropdown':
					var mapControlOptions = google.maps.MapTypeControlStyle.DROPDOWN_MENU;
					break;
				case 'default':
				default:
					var mapControlOptions = google.maps.MapTypeControlStyle.DEFAULT;
					break;
			}
			switch(settings.zoomControlOptions.style){
				case 'small':
					var zoomOptions = google.maps.ZoomControlStyle.SMALL;
					break;
				case 'large':
					var zoomOptions = google.maps.ZoomControlStyle.LARGE;
					break;
				case 'default':
				default:
					var zoomOptions = google.maps.ZoomControlStyle.DEFAULT;
					break;
			}
			switch(settings.zoomedView.type){
				case 'satellite':
					var zoomedViewType = google.maps.MapTypeId.SATELLITE;
					break;
				case 'road':
					var zoomedViewType = google.maps.MapTypeId.ROADMAP;
					break;
				case 'terrain':
					var zoomedViewType = google.maps.MapTypeId.TERRAIN;
					break;
				case 'hybrid':
				default:
					var zoomedViewType = google.maps.MapTypeId.HYBRID;
					break;
			}

			$('#map-wrapper').addClass('type-'+mapType); // only needed to enable extra 'hook' for styling
			getData();

			$(window).unload(function() { if(this._map) { this._map.Dispose(); } });

			function getData() {
				var obj = this;
				$.getJSON(settings.dataSource, function(data) {
					$.each(data.record.record, function(i) {
						items[i] = {};
				    	var data_id = $(this),
				    		id = data_id[0]['@attributes'].id,
				    		image = data_id[0].images ? data_id[0].images : null, // array
				    		title = data_id[0]['@attributes'].title,
				    		category = data_id[0].category ? data_id[0].category : null, // array
				    		subcategory = data_id[0].subcategory ? data_id[0].subcategory : null, // array
				    		desc = data_id[0].description,
				    		website = data_id[0].website.length ? data_id[0].website : null,
				    		address = data_id[0].address.length ? data_id[0].address : null,
				    		georss = data_id[0]['@attributes'].georss_point.length ? data_id[0]['@attributes'].georss_point : null;

					    	if(georss !== null) {
						    	items[i].id = id;
						    	items[i].title = title;
						    	items[i].description = desc;
						    	items[i].website = website;
						    	items[i].address = address;
						    	items[i].georss = georss;
						    	items[i].poiID = i;

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



								var subcatName = [],
									subLongName = [];
								if (settings.needsIntl === true) {
									var tempSubName = subcategory.replace(/\s/g, '').replace('&', '');
								} else {
									var tempSubName = subcategory.replace(/\W/g, '').toLowerCase();
								}
								var longSubName = subcategory.replace(/^\s+|\s+$/g, '');
								subcatName.push(tempSubName);
								subLongName.push(longSubName);

								items[i].subcategory = subcatName;
								items[i].subLongName = subLongName;
					    	}
					});
					createMap();
				});
			}

			function createMap() {
				//initial point to load map,
				var fp = getLatLong(items[0]['georss']);
				var latlng = new google.maps.LatLng(parseFloat(fp[0]),parseFloat(fp[1]));
				var mapOptions = {
					zoom: settings.zoom,
					maxZoom: 16,
					center: new google.maps.LatLng(32.185224, -80.718613),
					mapTypeId: 'roadmap',
					disableDefaultUI: settings.disableDefaultUI,
					mapTypeControl: settings.mapTypeControl,
					mapTypeControlOptions: {style: mapControlOptions},
					zoomControl: settings.zoomControl,
					zoomControlOptions: {style: zoomOptions},
					scaleControl: settings.scaleControl,
					keyboardShortcuts: true	// no good reason to ever disable, so not in settings
				};

				map = new google.maps.Map(document.getElementById(id), mapOptions);
				var marker = new google.maps.Marker();

				var userLocation;
				var browserSupportFlag =  new Boolean();
				if(settings.useGeo === true) {
					// Try W3C Geolocation (Preferred)
					if(navigator.geolocation) {
						browserSupportFlag = true;
						navigator.geolocation.getCurrentPosition(function(position) {
							var posLat = position.coords.latitude;
							var posLong = position.coords.longitude;
							userLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
							map.setCenter(userLocation);
							console.log(posLat+','+posLong);
						}, function() {
							handleNoGeolocation(browserSupportFlag);
						});
					}
					// Browser doesn't support Geolocation
					else {
						browserSupportFlag = false;
						handleNoGeolocation(browserSupportFlag);
					}

					function handleNoGeolocation(errorFlag) {
						if (errorFlag == true) {
							alert("Geolocation service failed.");
						} else {
							alert("Your browser doesn't support geolocation.");
						}
					}

					function successCallback(position) {
					    var latitude = position.coords.latitude;
					    var longitude = position.coords.longitude;
					    console.log("Your location is: " + latitude + "," + longitude);
					}

					function errorCallback(error) {
					    console.log(error);
					}

					if (Modernizr.geolocation) {
					    navigator.geolocation.getCurrentPosition(successCallback, errorCallback, { maximumAge: 0 });
					    console.log("geolocation is enabled");
					} else {
					    console.log("geolocation is NOT enabled");
					}
				}

				if (settings.styledMap === true) {
					var styledMap = new google.maps.StyledMapType(settings.mapStyles.styles,
					    {name: "Styled Map"});

					map.mapTypes.set('map_style', styledMap);
					map.setMapTypeId('map_style');
				}

				if(settings.changeViewOnZoom === true) {
					google.maps.event.addListener(map, 'zoom_changed', function() {
						zoom = map.getZoom();
						if(zoom>15) {
							map.setMapTypeId(zoomedViewType);
						} else {
							map.setMapTypeId(mapStyle);
						}
					});
				}

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

				google.maps.event.addListener(map, 'zoom_changed', function() {
					closeInfoWindows();
				});

					start = false;
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

						currentID = qID;
						l=layers[qID];
						$('div#feature-list-'+qID).addClass('on');
						map.setOptions({center: loc, zoom: 16 });

					} else {
						var cat = items[queryArray[0]].category;
						loadPoints(cat);
						var loc = getCenterPoint(j);
						map.setOptions({center: loc, zoom: 16 });
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
					subcatArray = items[i].subcategory;
					longCatArray = items[i].longName;
					longSubCatArray = items[i].subLongName;

					for (x in catArray)	{
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

						if(!dup) {
							categories.push(catArray[x]);
						}
					}

					for (x in subcatArray)	{
						dup = false;
						$.each(subcategories, function(j, val2) {
							if ( subcatArray[x] == val2 ) {
								dup = true;
							}
						});
						if ( !window.one ) {
							window.one = [];
						}
						window.one.push(val);

						if(!dup) {
							subcategories.push(subcatArray[x]);
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


					for (y in longSubCatArray){
						longDup = false;
						$.each(longsubcats, function(j, val2) {
							if ( longSubCatArray[y] == val2 ) {
								longDup = true;
							}
						});

						if(!longDup) {
							longsubcats.push(longSubCatArray[y]);
						}
					}

				});

				if((mapType=='both') && (settings.printFeatureFirst != undefined) && (settings.printFeatureFirst === true)) {
					$(divFeatureWrapper).append(divFeature);
					$('div#map-wrapper #category-wrapper').append(divFeatureWrapper);
					printCategories();
				} else {
					if(mapType!='feature') {
						printCategories();
					}
				}

				if(mapType!='poi')
					setBestView();
			}

			function printCategories() {
				// Print all of the categories
				var divWrapper = $('<div id="category-wrapper"><h2 class="mobile-toggle">Resort Map Directory</h2><div id="cat-inner-wrap"></div></div>'),
					div = $('<div />').attr('id','categories');

				$('div#map-wrapper').prepend(divWrapper);

				$('<div class="directions"><div class="directions-poi"><div class="poi-wrap"><img src="/application/themes/theme_palmetto/images/map/poi-close.gif" class="close-poi" /><div class="poi-intro">Select a point of interest from one of the dropdowns below:</div></div></div><a href="" class="dir-toggle">Get Directions</a><div class="dir-wrap"><div class="intro">Get directions from any two points of interest in the dropdown below.</div><div class="distance-input"><div class="distance-from"><div class="wrap">From:</div><input type="text" id="from-point" data-latitude="" data-longitude="" value="" /></div><div class="distance-to" ><div class="wrap">To:</div><input type="text" id="to-point" data-latitude="" data-longitude="" value="" /></div><div id="map-points-wrapper"></div></div><div class="distance-output"><div class="distance-total">Total Distance: <span class="output"></span></div><div class="walk-time">Estimated Driving Time: <span class="output"></span></div><div id="directions-output"></div><div id="warnings-panel"></div></div></div></div>').appendTo('#cat-inner-wrap');

				$(div).appendTo('#cat-inner-wrap');

				$('.close-poi').click(function(e){
					e.preventDefault();
					$('#map-wrapper .directions-poi').slideUp(500);
				});

				$('#map-wrapper #category-wrapper h2.mobile-toggle').click(function(){
					$(this).toggleClass('opened');
					$('#map-wrapper #cat-inner-wrap').slideToggle(600);
				});

				$.each(categories, function(g, val) {
					var groupWrapper = $('<div />').addClass('map-group').attr('id',val),
						groupName = $('<a />').attr({'href':'','class':'cat-link'}).html(longcats[g]).click(function(e){
							e.preventDefault();
							parentalUnit= $(this).parent();
							//console.log( parentalUnit );

							closeInfoWindows();


							$('.map-subcat').hide();
							$('.pin-marker').parent().parent().parent().hide();

							if ( $(this).parent().hasClass('on') ) {
								$(this).parent().removeClass('on');
								$(this).parent().children('.map-subcat').removeClass('on').slideUp(400);
								$(this).parent().children('.map-subcat').find('input').prop('checked', false);

								parentCatId = $(this).parent().attr('id');

								if ( (!$(this).parent().hasClass('on')) && ($(parentCatId) == 'activities') ) {
									$('.marker-cat-golf, .marker-cat-tennis, .marker-cat-bikes, .marker-cat-kayaksampcanoes, .marker-cat-chartersamptours').parent().parent().parent().hide();
								} else if ( !$(this).parent().hasClass('on') && $(parentCatId) == 'neighborhoods' ) {
									$('.marker-cat-viewallneighborhoods').parent().parent().parent().hide();
								} else if ( !$(this).parent().hasClass('on') && $(parentCatId) == 'dining' ) {
									$('.marker-cat-viewalldining').parent().parent().parent().hide();
								} else if ( !$(this).parent().hasClass('on') && $(parentCatId) == 'shopping' ) {
									$('.marker-cat-golfproshops, .marker-cat-othershopping, .marker-cat-sheltercoveharbourshops, .marker-cat-grocerystores').parent().parent().parent().hide();
								} else if ( !$(this).parent().hasClass('on') && $(parentCatId) == 'otherservices' ) {
									$('.marker-cat-weddingampmeetingvenues, .marker-cat-spaampsalonservices, .marker-cat-other').parent().parent().parent().hide();
								}

							} else {
								$('.map-group').removeClass('on');
								$('.map-subcat').slideUp(400);

								$(this).parent().addClass('on');
								$(this).parent().children('.map-subcat').addClass('on').slideDown(400);
								$(this).parent().children('.map-subcat').find('input').prop('checked', true);
								loadPoints(val);
							}

						}).appendTo(groupWrapper);
					groupWrapper.appendTo(div);

					var poigroupWrapper = $('<div />').addClass('poi-map-group').attr('id',val).appendTo('.poi-wrap');
					var poigroupName = $('<label />').html(longcats[g]).appendTo(poigroupWrapper);
					var subCatSelect = $('<select />').addClass('dropdown').attr('id',val).appendTo(poigroupWrapper);

				});

				$('<option value="">Select an activity</option>').appendTo('#map-wrapper .poi-map-group #activities');
				$('<option value="">Select a neighborhood</option>').appendTo('#map-wrapper .poi-map-group #neighborhoods');
				$('<option value="">Select a dining venue</option>').appendTo('#map-wrapper .poi-map-group #dining');
				$('<option value="">Select a shopping venue</option>').appendTo('#map-wrapper .poi-map-group #shopping');
				$('<option value="">Select one</option>').appendTo('#map-wrapper .poi-map-group #otherservices');


				google.maps.event.addDomListener(window, 'load', initializeDirections);

				var inputClicked;

				$('#map-wrapper .dir-wrap input').click(function() {
					$('.poi-map-group .dropdown').val('');
					$('#map-wrapper .directions-poi').slideDown(500);
					inputClicked = $(this);
				});

				$('.poi-map-group .dropdown').change(function() {
					var selLat = $(this).find(':selected').attr('data-latitude'),
					selLong = $(this).find(':selected').attr('data-longitude'),
					seltitle = $(this).find(':selected').val();

					$(inputClicked).val(seltitle);
					$(inputClicked).attr('data-latitude', selLat);
					$(inputClicked).attr('data-longitude', selLong);

					$('#map-wrapper .directions-poi').slideUp(400);

					$('.pin-marker').parent().parent().parent().hide();

					if ( $(inputClicked).val() ) {
						calcRoute();
					}

				});

				$('#map-wrapper .dir-toggle').click(function(e){
					e.preventDefault();

					panel = document.getElementById('directions-output');

					if ( $(this).hasClass('close') ) {
						$(this).removeClass('close');
						$(this).parent().children('#map-wrapper .dir-wrap').slideUp(500);
						$('.distance-input input').val('');
						$('.distance-output .distance-total span, .distance-output .walk-time span').empty();
						panel.innerHTML = '';
						directionsDisplay.setDirections({routes: []});
					} else {
						$(this).addClass('close');
						$(this).parent().children('#map-wrapper .dir-wrap').slideDown(500);
					}
				});

				$.each(subcategories, function(i, val) {
					var testsub = val.toString().substring(0,4),
						excludeCat = false;

					// for maptype 'both', hhis should remove any of the "feature" points category listing.
					if( settings.excludeFeaturedCategory === true && val === settings.featuredCategory) {
						excludeCat = true;
					}
					if ( (((val!=='property') && (val !== 'featurepoint')) && (excludeCat !== true))  && testsub!=='func') {
						var divCat = $('<div />').addClass('map-subcat ').attr('id',val);

						var subCatDiv = $('<div class="subcat-title" id="title-'+val+'"></div>').appendTo(divCat);
							subCatInput = $('<input type="checkbox" id="checkboxgroup-'+val+'" class="checkboxgroup" value="'+val+'" checked="checked" />').change(function(){

								if ( $(this).is(':checked') ) {
							        $(this).parent().parent('.map-subcat').children('.point-list').find('input').attr('checked',true);
							        $('.marker-cat-'+val).parent().parent().parent().show();
							    } else if ( !$(this).is(':checked') ) {
							    	 $(this).parent().parent('.map-subcat').children('.point-list').find('input').attr('checked',false);
							    	 $('.marker-cat-'+val).parent().parent().parent().hide();
							    }

							}).appendTo(subCatDiv);

						var subCatLink = $('<a href="#" class="subcat-link">'+longsubcats[i]+'</a></div>').appendTo(subCatDiv);

						if ( settings.printPoints===true ) {printPoints(val,divCat);}


						if ( ($(divCat).attr('id')=='golf') || ($(divCat).attr('id')=='tennis')  ||  ($(divCat).attr('id')=='bikes') ||  ($(divCat).attr('id')=='kayaksampcanoes') ||  ($(divCat).attr('id')=='chartersamptours') ) {
							$(divCat).appendTo('#categories #activities');

						} else if ( ($(divCat).attr('id')=='viewallneighborhoods') ) {
							$(divCat).appendTo('#categories #neighborhoods');

						} else if ( ($(divCat).attr('id')=='viewalldining') ) {
							$(divCat).appendTo('#categories #dining');

						} else if ( ($(divCat).attr('id')=='golfproshops') || ($(divCat).attr('id')=='othershopping') || ($(divCat).attr('id')=='sheltercoveharbourshops') || ($(divCat).attr('id')=='grocerystores') ) {
							$(divCat).appendTo('#categories #shopping');

						} else if ( ($(divCat).attr('id')=='weddingampmeetingvenues') || ($(divCat).attr('id')=='spaampsalonservices') || ($(divCat).attr('id')=='other') ) {
							$(divCat).appendTo('#categories #otherservices');
						}

					}

				});

				if((settings.featuredCategory!==undefined)) {
					if(settings.featuredCategory==='all'){
						$.each(subcategories, function(i, val) {
							loadPoints(val);
							$('div#'+val).addClass('on').children('ul.point-list').slideDown();
						});
					} else {
						loadPoints(settings.featuredCategory); // don't delete this - caused the call stack overflow error - oops!

						$('div#'+settings.featuredCategory).addClass('on').children('ul.point-list').slideDown();
						$('div#'+settings.featuredCategory).children('.map-subcat').addClass('open');

						$('div#'+settings.featuredCategory).children('.map-subcat').find('input').prop('checked', true);

					}
				}

				$('.subcat-link').click(function(e, val) {
					e.preventDefault();

					if ( $(this).hasClass('open') ) {
						$(this).removeClass('open');
						$(this).parent().parent('.map-subcat').removeClass('open');
						$(this).parent().parent().children('ul.point-list').slideDown(400);
						//loadPoints(val);

					} else {
						$(this).addClass('open');
						$(this).parent().parent('.map-subcat').addClass('open');
						$(this).parent().parent().children('ul.point-list').slideUp(400);
						$(this).parent().children('.map-subcat').find('input').prop('checked', false);

						closeInfoWindows();
					}

				});
			}

			// specific to category accordian
			function printPoints(cat,divCat) {
				var obj=this;
				var cats = cat===null?subcategories:[cat];
				var catName = '';
				var pointUl = $('<ul />').attr('id','point-list-'+cat).addClass('point-list');

				$.each(cats, function(i, val) {
					layers[val] = new Array;
					$.each(items, function(j) {
						var catSelectedArray = new Array( );
						var currarray = String(items[j].subcategory);
						catSelectedArray = currarray.split(",");


						for (x in catSelectedArray){
							var point;
							point = items[j]['georss'];

							if((catSelectedArray[x]==val)&&point!='') {
								var selLat = items[j].georss.split(' ')[0];
								var selLong = items[j].georss.split(' ')[1];
								var pointLi = $('<li id="'+items[j].id+'" data-longitude="'+selLong+'" data-latitude="'+selLat+'"></li>');

								var pointInput = $('<input type="checkbox" class="checkbox" checked="checked" />').change(function() {
									  	inputId = $(this).parent().attr('id');

									    if ( $(this).is(':checked') ) {
									        $('.marker-'+inputId).parent().parent().parent().show();
									    } else if ( !$(this).is(':checked') ) {
									    	$('.marker-'+inputId).parent().parent().parent().hide();
									    	$('.infoBox').hide();
									    }
								});

								$(pointInput).appendTo(pointLi);

								var listHtml = items[j].title;
								if (settings.showPoiID === true) {
									listHtml = items[j].title;
								}
								var pointAnchor = $('<a href="#"><span>'+items[j].id+'</span>. '+listHtml+'</a>').click(function(e){
									e.preventDefault();
									$(this).parent().siblings().find('a').removeClass('active');
									$(this).addClass('active');
									$(this).parent().children('input').attr('checked','checked');
									showSinglePoint(j,cat);

									if ( screen.width < 720 ) {

										$('#map-wrapper #category-wrapper h2.mobile-toggle').removeClass('opened');
										$('#map-wrapper #cat-inner-wrap').slideUp(600);
									}
								});
								$(pointLi).append(pointAnchor);
								$(pointUl).append(pointLi);


								var subCatSelectOptions = $('<option value="'+listHtml+'" data-address="'+items[j].address+'" data-latitude="'+selLat+'" data-longitude="'+selLong+'" class="'+currarray+'">'+listHtml+'</option>');


								if ( ($(subCatSelectOptions).hasClass('golf')) || ($(subCatSelectOptions).hasClass('tennis'))  ||  ($(subCatSelectOptions).hasClass('bikes')) ||  ($(subCatSelectOptions).hasClass('kayaksampcanoes')) ||  ($(subCatSelectOptions).hasClass('chartersamptours')) ) {
									$(subCatSelectOptions).appendTo('.poi-map-group select#activities');

								} else if ( ($(subCatSelectOptions).hasClass('viewallneighborhoods')) ) {
									$(subCatSelectOptions).appendTo('.poi-map-group select#neighborhoods');

								} else if ( ($(subCatSelectOptions).hasClass('viewalldining')) ) {
									$(subCatSelectOptions).appendTo('.poi-map-group select#dining');

								} else if ( ($(subCatSelectOptions).hasClass('golfproshops')) || ($(subCatSelectOptions).hasClass('othershopping')) || ($(subCatSelectOptions).hasClass('sheltercoveharbourshops')) || ($(subCatSelectOptions).hasClass('grocerystores')) ) {
									$(subCatSelectOptions).appendTo('.poi-map-group select#shopping');

								} else if ( ($(subCatSelectOptions).hasClass('weddingampmeetingvenues')) || ($(subCatSelectOptions).hasClass('spaampsalonservices')) || ($(subCatSelectOptions).hasClass('other')) ) {
									$(subCatSelectOptions).appendTo('.poi-map-group select#otherservices');
								}

							}
						}
					});
				});

				$(pointUl).find('li').each(function() {
					var listPos = $(this).parent().children().index(this) +1,
						pinLabelType = (settings.pinLabelType),
						pinLabelChar = (settings.pinLabelChar);
					if(pinLabelType === 'list'){
						if(pinLabelChar === 'letter') {
							listPos = String.fromCharCode(64 + listPos);
						}
						$(this).find('a span').html(listPos);
					}
					$(this).addClass('pos-'+listPos);
				});
				$(divCat).append(pointUl);
			}

			// deal with json format for currarray.split
			function loadPoints(cat) {
				var obj=this;
				var cats = cat===null?categories:[cat];
				var notMade = checkLayers(cat);
				var catName = '';
				if(notMade) {
					$.each(cats, function(i, val) {
						layers[val] = new Array;
						var shapes = [];
						if(loadIds!==null) {
							$.each(loadIds, function(k, val2) {
								$.each(items, function(j) {
									if(items[j].id==val2) {
										var d = addPOIPoint(shapes, j,  cat, 'qstring');
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

								for (x in catSelectedArray){
									if(catSelectedArray[x]==val) {
										var d = addPOIPoint(shapes, j, cat, layers[val]);
										layers[val].push(d);
										var loc = getCenterPoint(j);
										centerArray.push(loc);
									}
								}
							});
						}
						if ( (cat != 'property') && (cat != 'featurepoint')) {
							$('.feature-list').each(function() {
								if ($(this).hasClass('on')) {
									var featureid = $(this).attr('id').toString().substr(13);
									l=layers[featureid];
									var pinidx = getPinIndex(featureid);

									$('div#feature-list-'+featureid).removeClass('on');
									$('div#feature-list-'+featureid).addClass('off');
									$(this).removeClass('on');
								}
							});
						}
						loadIds = null;
						if(settings.resetViewOnCategoryChange===true && start ===false) {
							setBestView();
						}
					});
				}
			}

			function showSinglePoint(j,cat) {

				var point = getLatLong(items[j]['georss']);

				if (point != ''&point!='null,null') {
					var loc = new google.maps.LatLng(parseFloat(point[0]), parseFloat(point[1]));
					var marker = createMarker(cat,loc,j);
				}
				google.maps.event.trigger(marker, 'click');
				map.setZoom(settings.zoomedView.level); // make a setting
			}


			function loadfeaturepoints(featureid) {
				var obj = this;
				featureid=featureid.toString();
				var notMade = checkfeaturepoints(featureid);
				if(notMade) {

					bounds = new google.maps.LatLngBounds();
					var shapes = [];
					$.each(items, function(j) {
						if (featureid == items[j].id) {
							var d = addfeaturePoint(shapes, j, 'featurepoint');
							layers[featureid] = d;
							var loc = getCenterPoint(j);
							centerArray.push(loc);
						}
					});
				}
			}

			// deal with json format for currarray.split
			function checkLayers(cat) {
				var obj = this;
				entityIdx=pinLayers[cat];
				l=layers[cat];
				if((l!=undefined)&&(l.length!=0)) {
					if ( l[0].getVisible() ) {
						$('div#'+cat).removeClass('on');
						$('div#'+cat).children('ul.point-list').slideUp(); // to keep only one open - make option??
					} else {
						if ( (cat != 'property') && (cat != 'featurepoint')) {
							$('.feature-list').each(function() {
								if ($(this).hasClass('on')) {
									var featureid = $(this).attr('id').toString().substr(13);
									var pinidx = getPinIndex(featureid);
									l2=layers[featureid];

									$('div#feature-list-'+featureid).removeClass('on');
									$('div#feature-list-'+featureid).addClass('off');
									$(this).removeClass('on');
								}
							});
							var cats = cat===null?categories:[cat];
							$.each(cats, function(i, val) {
								var shapes = [];
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
							centerArray = $.uniqueArray(centerArray);
							setBestView();
						}


						$('div#'+cat).removeClass('off');
						$('div#'+cat).addClass('on');
					}
					for (i = 0; i < l.length; i++) {
						if(l[i]!=undefined) {
							if(l[i].getVisible() == true) {
								l[i].setVisible(false);
							} else {
								l[i].setVisible(true);
							}
						}
					}

					return false;
				}
				return true;
			}

			function checkfeaturepoints(featureid) {
				featureid = featureid.toString();
				l=layers[featureid];
				if(l!=undefined) {
					if ( l.getVisible() ) {
						if ( $('div#feature-list-'+featureid).hasClass('on') == false ) {
							var obj = this;
							$.each(items, function(j) {
								if (featureid == items[j].id) {
									featureid = parseFloat(featureid);
									var point;
									point = getLatLong(items[j]['georss']);


									var loc = new google.maps.LatLng(parseFloat(point[0]), parseFloat(point[1]));
									map.setOptions({center: loc, zoom: 16 });
									currentID = featureid;
									$('div#feature-list-'+featureid).addClass('on');
								}
							});
						} else {
							var obj = this;
							var pinidx = getPinIndex(featureid);
							$('div#feature-list-'+featureid).removeClass('on');
							$('div#feature-list-'+featureid).addClass('off');
						}
						$('.feature-list').each(function() {
							var featureon = $(this).attr('id').toString().substr(13);
							if ($(this).hasClass('on') && (featureon!=featureid)) {
								var pinidx = getPinIndex(featureon);
								l2=layers[featureon];
								$(this).removeClass('on');
							}
						});

					} else {
						var marker = createMarker(latLng,items[i].property_name,items[i].address,i,items[i].property_style);
					}
					return false;
				}
				return true;
			}

			// deal with the star thing
			function addPOIPoint(shapes, j, cat, cstm) {
				var info = '';

				var point = getLatLong(items[j]['georss']);

				if (point != ''&point!='null,null') {
					var loc = new google.maps.LatLng(parseFloat(point[0]), parseFloat(point[1]));
					if ( items[j].category.length > 1 ) {
						var pushpinOptions = {icon: RELPATH+'img/map/star.gif', width: 31, height: 46, id:j.toString(), typeName:j.toString()};
					} else {
						var pushpinOptions = {icon: RELPATH+'img/map/'+items[j].category+'.png', width: 20, height: 24, id:j.toString(), typeName:j.toString()};
					}
					var marker = createMarker(cat, loc,j);
					return marker;
				}
			}

			function addfeaturePoint(shapes, j, cat, cstm) {
				var info = '';

				var point = getLatLong(items[j]['georss']);

				if (point != ''&point!='null,null') {
					var loc = new google.maps.LatLng(parseFloat(point[0]), parseFloat(point[1]));
					var marker = createMarker(cat, loc,j);
					return marker;
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
				var centerPoint = getLatLong(items[j]['georss']);

				if (centerPoint != ''&centerPoint!='null,null')
					return loc = new google.maps.LatLng(parseFloat(centerPoint[0]), parseFloat(centerPoint[1]));
				else
					return false;
			}

			 function setBestView() {
				bounds = new google.maps.LatLngBounds( );
				for ( var i = 0; i < centerArray.length; i++ ) {
					if(centerArray[i]!=false)
						bounds.extend( centerArray[ i ] );
				}
				map.fitBounds(bounds);
				centerArray.length = 0;
			}

			 function getPinIndex(featureid) {
				var pinidx;
				$.each(items, function(j) {
					if (featureid == items[j].id) {
						pinidx = j;
					}
				});
				return pinidx;
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

			/*
			Marker Creation
			*/

			function createMarker(cat, latlng, idx, cstm) {
				var name = items[idx].title;
				var address = items[idx].address;

				var m = items[idx].category;

				var mCat = '';
				$.each(m, function(i) {
					mCat += ' marker-cat-'+m[i];
				});
				// works only for POIs in only one category if added directly to marker pin-label at creation
				// var catListPos = $('#categories .category ul').find('li[id="'+items[idx].id+'"]').attr('class').split('-');
				// catListPos = catListPos[1];

				var markerContent = document.createElement('DIV');
				if (settings.showPinID === true) {
					markerContent.innerHTML = '<div class="pin-marker '+mCat+' marker-'+items[idx].id+'"><div class="pin-label">'+items[idx].poiID+'</div></div>';
					//markerContent.innerHTML = '<div class="pin-marker '+mCat+' markerid-'+items[idx].id+'"><div class="pin-label">'+catListPos+'</div></div>';
				} else {
					markerContent.innerHTML = '<div class="pin-marker marker-cat-'+items[idx].subcategory+' marker-'+items[idx].id+'"></div>';
				}

				var marker = new RichMarker ({
					map: map, position: latlng, flat:true, content: markerContent
				});

				marker.set("id", idx);
				marker.set("pinID",items[idx].id); // nikko
				marker.set("category",cat);

			  	google.maps.event.addListener(marker, 'click', function() {
					closeInfoWindows();
					var latlng = getCenterPoint(idx);
					var hotelAddress;
					var html;

					var request = {location: latlng,radius:1};

					var service = new google.maps.places.PlacesService(map);
					service.search(request,function(results,status) {
						if (status == google.maps.places.PlacesServiceStatus.OK) {
							token = results[0].reference;
							var placeRequest = { reference: token };
							$('#mapDiv').children('div.gm-style').children('div:last').remove();
							var placeService = new google.maps.places.PlacesService(map);
							placeService.getDetails(placeRequest, function(place, status) {
								if (status == google.maps.places.PlacesServiceStatus.OK) {
									html ='';
									html = getInfoWindowContent(idx,place,html);
									var boxText = document.createElement('div');
									boxText.innerHTML = html;

									infoBox.setOptions(settings.infoBoxOptions);
									infoBox.setContent(boxText);
									infoBox.open(map, marker);
							  	}
							});
							$('#mapDiv').children('div.gm-style').children('div:last').remove();
						}
					});
			  	});

			  	if(settings.infoBoxOptions.infoBoxClass!='window-feature') {
			  		var windowTrigger;
			  		if(settings.hover!=undefined && settings.hover==true) {
			  			windowTrigger = 'mouseover';
			  		} else {
			  			windowTrigger = 'click';
			  		}

					google.maps.event.addListener(marker, ((navigator.platform == 'iPad') ? 'click' : windowTrigger), function() {
						closeInfoWindows();
						var latlng = getCenterPoint(idx);
						var hotelAddress;
						var html;
						var request = {location: latlng,radius:1};
						var service = new google.maps.places.PlacesService(map);
						service.search(request,function(results,status) {
							if (status == google.maps.places.PlacesServiceStatus.OK) {
								token = results[0].reference;
								var placeRequest = { reference: token };
								$('#mapDiv').children('div.gm-style').children('div:last').remove();
								var placeService = new google.maps.places.PlacesService(map);
								placeService.getDetails(placeRequest, function(place, status) {
									if (status == google.maps.places.PlacesServiceStatus.OK) {
										html ='';
										html = getInfoWindowContent(idx,place,html);
										var boxText = document.createElement("div");
										boxText.innerHTML = html;

										infoBox.setOptions(settings.infoBoxOptions);
										infoBox.setContent(boxText);
										infoBox.open(map, marker);
									}
								});
								$('#mapDiv').children('div.gm-style').children('div:last').remove();
							}
						});
					});
			  	}
			  	return marker;
			}

			function closeInfoWindows() {
				if(infoWindow!=null)
					infoWindow.close();
				if(infoBox!=null)
					infoBox.close();
			}

			function getInfoWindowContent(idx,place,html) {
				var w = items[idx].category;
				var wCat = '';
				$.each(w, function(i) {
					wCat += ' win-cat-'+w[i];
				});

				html += '<div class="infoWindowContent '+wCat+' win-'+items[idx].id+'">';
				$.each(items[idx], function(k, val2) {  // k is key, ie category, type, image etc.
					if((val2!='')&&(val2!=undefined)) {
						switch(k) {
							case 'image':
								if (settings.infoBoxHtmlOptions.useImage === true && items[idx].image != null)
									html += '<div class="inset"><img src="'+items[idx].image+'" alt="" / ></div>';
								break;
							case 'title':
								if (settings.infoBoxHtmlOptions.useTitle === true)
									html += '<h4>'+items[idx].title+'</h4>';
								break;
							case 'address':
								if (settings.infoBoxHtmlOptions.useAddress === true && items[idx].address != null)
									html += '<p>'+items[idx].address+'</p>';
								break;
							case 'description':
								if (settings.infoBoxHtmlOptions.useDescription === true && items[idx].description != null)
									html += '<div class="description">'+items[idx].description+'</div>';
								break;
							case 'website':
								if (settings.infoBoxHtmlOptions.useWebsite === true && items[idx].website != null)
									html += '<a class="website orange-btn" href="'+items[idx].website+'">Learn More</a>';
								break;
						}
					} else {
						switch(k) {
							case 'image':
								if (settings.infoBoxHtmlOptions.useImage === true && items[idx].image != null)
									html += '<div class="inset"><img src="'+items[idx].image+'" alt="" / ></div>';
								break;
							case 'title':
								if (settings.infoBoxHtmlOptions.useTitle === true)
									html += '<h4>' + place.name + '</h4>';
								break;
							case 'address':
								if (settings.infoBoxHtmlOptions.useAddress === true && place.formatted_address != null)
									html += '<p>'+place.formatted_address+'</p>';
								break;
							case 'description':
								if (settings.infoBoxHtmlOptions.useDescription === true && items[idx].description != null)
									html += '<div class="description">'+items[idx].description+'</div>';
								break;
							case 'website':
								if (settings.infoBoxHtmlOptions.useWebsite === true && items[idx].website != null)
									html += '<a class="website orange-btn" href="'+items[idx].website+'" target="_blank">Learn More</a>';
								break;
						}
					}

				});

				if (settings.infoBoxHtmlOptions.addDirections === true) {
					var directionsType = settings.infoBoxHtmlOptions.directionsType;
					switch(directionsType) {
						case 'form':
							html += '<div class="directions-links">'+
										'<form class="directions">'+
											'<input onblur="processLink()" type="text" name="address" id="address" maxlength="100" class="textfield" value="" />'+
											'<a class="btn" target="_blank" href="#">Directions</a>'+
										'</form>'+
									'</div>';
							processLink = function(){
								var start = $('#mapDiv .directions #address').val().replace(/\s/g, '+');
								var dirLink = 'http://maps.google.com/maps?saddr='+start+'&daddr='+items[idx].georss;
								$('.directions-links .btn').attr('href', dirLink);
							}
							break;
						case 'link':
						default:
							html += '<a href="#" class="direction-links" data-latitude="'+items[idx].georss.split(' ')[0]+'" data-longitude="'+items[idx].georss.split(' ')[1]+' rel="'+items[idx].title+'"  onclick="event.preventDefault(); processLinks()">Directions</a>';

							processLinks = function() {
								selLat = items[idx].georss.split(' ')[0];
							    selLong = items[idx].georss.split(' ')[1];
							    seltitle = items[idx].title;

							    $('html, body').animate({scrollTop: 0}, 300);
							    $('.dir-toggle').addClass('close');
							    $('#map-wrapper .dir-wrap').slideDown(500);
							    $('#map-wrapper #cat-inner-wrap').slideDown(500);

							    $('#map-wrapper .dir-wrap .distance-to input').val(seltitle);
							    $('#map-wrapper .dir-wrap .distance-to input').attr('data-latitude', selLat);
							    $('#map-wrapper .dir-wrap .distance-to input').attr('data-longitude', selLong);
							}
							break;
					}
				}

				if (settings.infoBoxOptions.usePlacesData === true) {
						if (place.rating !=undefined)
							html += '<div>'+place.rating+'</div>';
						if (place.reviews !=undefined)
							html += '<div>'+place.reviews+'</div>';
						if (place.url !=undefined)
							html += '<div class="place-url"><a href="'+place.url+'"" target="_blank">places url</a></div>';
						if (place.icon !=undefined)
							html += '<div class="place-icon"><img src="'+place.icon+'"></div>';
						if (place.international_phone_number !=undefined)
							html += '<div class="intl-phone">'+place.international_phone_number+'</div>';
				}
				html += '</div>';
				return html;
			}

			/* Directions Stuff */
			function initializeDirections() {
				// Instantiate a directions service.
				directionsService = new google.maps.DirectionsService();

				// Create a renderer for directions and bind it to the map.
				var rendererOptions = {
					map: map,
					polylineOptions: {
      					strokeColor: "#00ff36",
      					strokeOpacity: 0
    				}
				}

				directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);

				// Instantiate an info window to hold step text.
				stepDisplay = new google.maps.InfoWindow();


				var pdOverlay;
				var bikeOverlay;
				var beachOverlay;
				var streetnameOverlay

  				/* image overlay stuff */
  				var imageBounds = new google.maps.LatLngBounds(
			      	new google.maps.LatLng(32.150264, -80.752714), // bottom left corner (sw)
			      	new google.maps.LatLng(32.201406, -80.691519)); // top right corner (ne)


				pdOverlay = new google.maps.GroundOverlay('https://www.palmettodunes.com/application/themes/theme_palmetto/images/map/resortmap-smaller-new.png', imageBounds);
  				pdOverlay.setMap(map);


				bikeOverlay = new google.maps.GroundOverlay('https://www.palmettodunes.com/application/themes/theme_palmetto/images/map/bike-trails.png', imageBounds);


				beachOverlay = new google.maps.GroundOverlay('https://www.palmettodunes.com/application/themes/theme_palmetto/images/map/beach-access2.png', imageBounds);


				streetnameOverlay = new google.maps.GroundOverlay('https://www.palmettodunes.com/application/themes/theme_palmetto/images/map/street-names.png', imageBounds);


				function mapView() {
                   	map.setMapTypeId('roadmap');
                   	map.setOptions({maxZoom:16});
                    bikeOverlay.setMap(null);
                    beachOverlay.setMap(null);
                    streetnameOverlay.setMap(null);
                    pdOverlay.setMap(map);
                }

                function satelliteView() {
                    map.setMapTypeId('satellite');
                    map.setOptions({maxZoom:22});
                    pdOverlay.setMap(null);
                    bikeOverlay.setMap(null);
                    beachOverlay.setMap(null);
                    streetnameOverlay.setMap(null);
               	}

  				function addBikeOverlay() {
				    bikeOverlay.setMap(map);
				}

				function removeBikeOverlay() {
				  	bikeOverlay.setMap(null);
				}

				function addBeachOverlay() {
				    beachOverlay.setMap(map);
				}

				function removeBeachOverlay() {
				  	beachOverlay.setMap(null);
				}

				function addStreetNameOverlay() {
				    streetnameOverlay.setMap(map);
				}

				function removeStreetNameOverlay() {
				  	streetnameOverlay.setMap(null);
				}

                $('#controls-panel .sattelite-map-toggle').click(function(e){
                    e.preventDefault();
                    var el = this;
                    $(this).toggleClass('map-view');
                    return (el.t = !el.t) ? satelliteView(el) : mapView(el);
                });

  				$('#controls-panel .directions-control-toggle').click(function(e) {
  					e.preventDefault();
					$('html, body').animate({scrollTop: 0}, 300);
					$('.dir-toggle').addClass('close');
					$('#map-wrapper .dir-wrap').slideDown(500);
					$('#map-wrapper #cat-inner-wrap').slideDown(500);
				});

				$('#controls-panel .print-dir').click(function(e) {
  					e.preventDefault();
  					window.print();
				});

				$('#controls-panel .bikepath-toggle').click(function(e){
					e.preventDefault();
					var el = this;
  					return (el.t = !el.t) ? addBikeOverlay(el) : removeBikeOverlay(el);
				});

				$('#controls-panel .beach-toggle').click(function(e){
                    e.preventDefault();
                    var el = this;
                    return (el.t = !el.t) ? addBeachOverlay(el) : removeBeachOverlay(el);
                });

                $('#controls-panel .streetname-toggle').click(function(e){
                    e.preventDefault();
                    var el = this;
                    return (el.t = !el.t) ? addStreetNameOverlay(el) : removeStreetNameOverlay(el);
                });

			}

			function calcRoute(bLat, bLong) {
				// set up distance service
				var service = new google.maps.DistanceMatrixService();
				directionsDisplay.setMap(map);

				var start = $('.distance-from #from-point').attr('data-latitude') + ',' + $('.distance-from #from-point').attr('data-longitude');
				var end = $('.distance-to #to-point').attr('data-latitude') + ',' + $('.distance-to #to-point').attr('data-longitude');

				service.getDistanceMatrix({
					origins: [start],
					destinations: [end],
					travelMode: google.maps.TravelMode.DRIVING,
					unitSystem: google.maps.UnitSystem.IMPERIAL,
				}, distanceCallback);

				function distanceCallback(response, status) {
					if (status != google.maps.DistanceMatrixStatus.OK) {
						console.log('Error was: ' + status);
					} else {
						var origins = response.originAddresses;
						var destinations = response.destinationAddresses;
						for (var i = 0; i < origins.length; i++) {
							var results = response.rows[i].elements;
							for (var j = 0; j < results.length; j++) {
								$('.distance-total .output').text(results[j].distance.text);
								$('.walk-time .output').text(results[j].duration.text);

							}
						}
					}
				}

				var request = {
					origin: start,
					destination: end,
					travelMode: google.maps.TravelMode.DRIVING
				};

				// Route the directions and pass the response to a
				// function to create markers for each step.
				directionsService.route(request, function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						var panel = document.getElementById('directions-output');
						panel.innerHTML = '';
						directionsDisplay.setDirections(response);
						directionsDisplay.setPanel(panel);
					} else {
					}
				});
			}

		}); // End Plugin Return

	}; // End plugin
})( jQuery );