(function( $ ){

	$.fn.googleMap = function( options ) {

		var settings = $.extend( {
			dataSource: '/poi', // '/poi' is default - if multiple maps on a site, will need to set for each. must be a standalone file, not one with rendered content
			mapType: 'poi', // feature: only show the featured category (#feature-wrapper), poi: only show the categories (#category-wrapper), both: show both feature and category divs
	      	mapStyle : 'road', // Style of Map { 'road', 'satellite', 'hybrid', 'terrain' }
			disableDefaultUI: false, // google only
			mapTypeControl: true,
			mapTypeControlOptions: {
				style: 'default' // horizontal, dropdown, default
			},
			zoomControl: true,
			zoomControlOptions: {
				style: 'default' // small, large, default
			},
			scaleControl: true, // google only
			styledMap: false, // google only // if true, will also need styles settings
			printPoints: true, // true shows the list of points when a category is clicked.
			needsIntl: false, // if true, won't lowercase category class name due to needing to allow any number of 'special' chars
			featuredCategory: 'featurepoint',	// if needsIntl: true, this will need to be whatever the category id would be,
											// ie the category name with spaces and any & removed, so French/Spanish/Italian might be capitalized,
											// where English wouldn't be
			printFeatureFirst: false, // use for mapType 'both' if you want the feature-wrapper div before the category-wrapper in the markup
			activeCatOnly: true, // if true, when a cetegory clicked, close other categories and clear pins
			showPoiID: false, // show id in list in addition to name
			showPinID: false, // show id on pin marker
			hover: false,
			zoom: 11,
			changeViewOnZoom: true, // if true, the map type will change when clicking on a point, or on user zoom
			zoomedView: {
				type: 'hybrid', // 'road', 'satellite', 'hybrid', 'terrain'
				level: 16
			},
			centerPoint: 1, // center point when map loads.  This will need to be the ID of the element from the json
			excludeFeaturedCategory: false, // if true, the featured category will not be added to the category wrapper (usually for mapType:both)
			disableShadows: false, // can likely leave false if using richmarker.js
			resetViewOnCategoryChange: true, // left as an option, but setting to false likely to mess things up, so just don't
			infoBoxOptions: {
				alignBottom:false, // false sets pin at top left corner, true at bottom left corner
				disableAutoPan: false,
				maxWidth: 0, // set at 0 for no maxWidth
				horizontalOffset: 0,
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
				closeBoxMargin: '2px', // standard css top right bottom left format
				closeBoxURL: 'http://www.google.com/intl/en_us/mapfiles/close.gif', // required - can be defined as a image, or hidden with css ( .infoBox>img { visibility:hidden; } ) to use the one in the infoBox html
				horizontalClearance: 25,
				verticalClearance: 25,
				isHidden: false,
				usePlacesData: false, // if true, will pull data from google places if present //not fully implemented
				pane: 'floatPane', // floatPane is 'above' mapPane - best for windows
				enableEventPropagation: false,
				addDirections: false,
				directionsType: 'link'
			},
			infoBoxHtmlOptions: { // sets which attributes from poi json should be included in infoBox
				useTitle: true,
				useDescription: true,
				useWebsite: true,
				useAddress: false,
				useImage: true,
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
				hashURL,
				property,
				start = true,
				loadIds = null,
				bounds,
				maptype,
				poiID,
				bounds,
				minLat = 32.0,
				maxLat = 33.0,
				minLong = -80.0,
				maxLong = -81.0,
				mapId = id = $(this).attr('id'),
				mapType = settings.mapType,
				directionsDisplay,
				directionsService,
				stepDisplay,
				cityState = ', Hilton Head Island, SC 29928',
				markerArray = [];

			var infoWindow = new google.maps.InfoWindow({maxWidth:368});
			var infoBox = new InfoBox();
			var layers = new Object();
			var centerArray = new Array();
			var queryArray = new Array();
			var pinLayers = new Object();

			var clusterInfoWindow;
			var markerCluster;
			var markers = [];

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

			var imagePath = RELPATH+'images/map/',
				unitDataElement = $('#photos-overview'),
				//resultsDataElement = $('#results-container:visible > li');
				resultsDataElement = $('.results-list:visible .unit-li');

			if ( $('body').hasClass('unit-details') ) {
				getUnitData();
			} else if ( $('body').hasClass('b-search-results') ) {
				//getData();
				getResultsData();
			} else {
				getData();
			}

			$(window).unload(function() { if(this._map) { this._map.Dispose(); } });

			$('.distance-to .dropdown').change(function() {
				calcRoute();
			});

			function getResultsData() {
				var obj = this,
					anyValid = false;
				$(resultsDataElement).each(function(i) {
					items[i] = {};
					var data_id = $(this),
						id = data_id.attr('data-id'),
						address = data_id.attr('data-address'),
						thumb = data_id.attr('data-thumb'),
						minrate = data_id.attr('data-minrate'),
						weekrate = data_id.attr('data-weekrate'),
						dailyrate = data_id.attr('data-dailyrate'),
						totalrate = data_id.attr('data-totalrate'),
						booklink = data_id.attr('data-booklink'),
						reLat = (data_id.attr('data-latitude') >= minLat && data_id.attr('data-latitude') <= maxLat) ? data_id.attr('data-latitude') : null,
						reLong = (data_id.attr('data-longitude') >= maxLong && data_id.attr('data-longitude') <= minLong) ? data_id.attr('data-longitude') : null,
						georss = (reLat !== null && reLong !== null) ? reLat + ' ' + reLong : null,
						category = 'main',
			    		validRange = false;
				    	validRange = (reLat!=null && reLong!=null) ? true : false;
				    if (validRange === true) {
				    	anyValid = true;
				    }
					if(georss !== null && georss !== undefined) {
						items[i].id = id;
						items[i].georss = georss;
						items[i].latitude = reLat;
						items[i].longitude = reLong;
						items[i].poiID = i;
						items[i].address = address;
						items[i].thumb = thumb;
						items[i].minrate = minrate;
						items[i].weekrate = weekrate;
						items[i].dailyrate = dailyrate;
						items[i].totalrate = totalrate;
						items[i].booklink = booklink;
						items[i].validRange = validRange;
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
					}
				});
				createMap();
				//createResultsMap();
				$('#mapDiv').append('<div class="add-shadow" />');
			}

			function getUnitData() {
				var obj = this,
					anyValid = false;
				$(unitDataElement).each(function(i) {
					items[i] = {};
					var data_id = $(this),
						id = data_id.attr('data-id'),
						address = data_id.attr('data-address'),
						reLat = (data_id.attr('data-latitude') >= minLat && data_id.attr('data-latitude') <= maxLat) ? data_id.attr('data-latitude') : null,
						reLong = (data_id.attr('data-longitude') >= maxLong && data_id.attr('data-longitude') <= minLong) ? data_id.attr('data-longitude') : null,
						georss = (reLat !== null && reLong !== null) ? reLat + ' ' + reLong : null,
			    		validRange = false;
				    	validRange = (reLat!=null && reLong!=null) ? true : false;
				    if (validRange === true) {
				    	anyValid = true;
				    }
					if(georss !== null && georss !== undefined) {
						items[i].id = id;
						items[i].georss = georss;
						items[i].latitude = reLat;
						items[i].longitude = reLong;
						items[i].poiID = i;
						items[i].address = address;
						items[i].validRange = validRange;
						items[i].category = 'main';
					}
					if (items[0].validRange == true) {
						createUnitMap();
						google.maps.event.addDomListener(window, 'load', initializeDirections);
						$('#mapDiv').append('<div class="add-shadow" />');
					} else {
						var badDataMsg = '<div class="bad-map-data">';
						badDataMsg += '<p>We\'re sorry. Our GPS data for this property is invalid.</p>';
						badDataMsg += '<p>We\'re temporarily unable to show this unit on a map or provide directions to local points of interest. Thank-you for your understanding in this matter.</p>';
						badDataMsg += '</div>';
						$('#unit-map-outer-wrapper, .unit-directions-wrapper').addClass('no-map');
						$('#mapDiv').append(badDataMsg);
					}
				});
			}

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
					$('#mapDiv').append('<div class="add-shadow" />');
				});

			}

			function createUnitMap() {

				// initial point to load map,
				var fp = getLatLong(items[0]['georss']);
				var latlng = new google.maps.LatLng(parseFloat(fp[0]),parseFloat(fp[1]));
        var pointsOfInterest =[
          {
            featureType: "poi",
            elementType: "all",
            stylers: [
              { visibility: "off" }
            ]
          }
        ];
				var mapOptions = {
					center: latlng,
					zoom: 17,
					//center: new google.maps.LatLng(32.1689019,-80.7192984), // <- palmetto dunes
					mapTypeId: mapStyle,
					disableDefaultUI: settings.disableDefaultUI,
					mapTypeControl: settings.mapTypeControl,
					mapTypeControlOptions: {style: mapControlOptions},
					zoomControl: settings.zoomControl,
					zoomControlOptions: {style: zoomOptions},
					scaleControl: settings.scaleControl,
					keyboardShortcuts: true,	// no good reason to ever disable, so not in settings
          styles: pointsOfInterest
				};
				var image = 'https://booking.palmettodunes.com/templates/main/images/map/pin-unit.png';
				map = new google.maps.Map(document.getElementById(id), mapOptions);

				var marker = new google.maps.Marker({
					position: latlng,
					map: map
				});

				//getCategories();
				//showSinglePoint(items[0].id,'main');

				google.maps.event.addListener(map, 'zoom_changed', function() {
					closeInfoWindows();
				});

					start = false;
			}

			function createMap() {
				// initial point to load map,
				var fp = getLatLong(items[0]['georss']);
				var latlng = new google.maps.LatLng(parseFloat(fp[0]),parseFloat(fp[1]));
				var mapOptions = {
					center: latlng,
					zoom: settings.zoom,
					//center: new google.maps.LatLng(32.17799887061119, -80.7262147217989), // <- palmetto dunes
					mapTypeId: mapStyle,
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

				// Setting Marker Clusterer options
				var styles = [[{
					url: '/templates/main/images/map/pin-cluster.png',
					height: 43,
					width: 74,
					anchorIcon: [0, 0],
					anchorText: [-7, 0],
					textColor: '#ffffff',
					fontFamily: 'Cabin,sans-serif',
					//text: 'Units',
					textSize: 12
				  }]];
				var mcOptions = {gridSize: 5, maxZoom: 18, styles: styles[0]};
				markerCluster = new MarkerClusterer(map, markers, mcOptions);
				clusterInfoWindow = new google.maps.InfoWindow();

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
				$('.markerid-841').trigger('click');

				google.maps.event.addListener(map, 'zoom_changed', function() {
					closeInfoWindows();
				});

				setBestView();

					start = false;
			}

			function initializeDirections() {
				$('.unit-directions-wrapper').show();
				// Instantiate a directions service.
				directionsService = new google.maps.DirectionsService();

				// Create a renderer for directions and bind it to the map.
				var rendererOptions = {
					map: map
				}

				directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);

				// Instantiate an info window to hold step text.
				//stepDisplay = $('#unit-map #directions-output'); // stepDisplay = new google.maps.InfoWindow();
				stepDisplay = new google.maps.InfoWindow();

			}

			function calcRoute() {
				// set up distance service
				var service = new google.maps.DistanceMatrixService();

				// First, clear out any existing markerArray
				// from previous calculations.
				for (i = 0; i < markerArray.length; i++) {
					markerArray[i].setMap(null);
				}

				// Retrieve the start and end locations and create
				// a DirectionsRequest using WALKING directions.
				//var start = document.getElementById('from-point').textContent + cityState; // use property address
				var start = $('#photos-overview').attr('data-latitude') + ',' + $('#photos-overview').attr('data-longitude');
				var end = document.getElementById('destinations').value + cityState; //pull from page select
				//console.log('start: '+start+' ; end: '+end);

				service.getDistanceMatrix({
					origins: [start],
					destinations: [end],
					travelMode: google.maps.TravelMode.WALKING,
					unitSystem: google.maps.UnitSystem.IMPERIAL,
				}, distanceCallback);

				function distanceCallback(response, status) {
					if (status != google.maps.DistanceMatrixStatus.OK) {
						//console.log('Error was: ' + status);
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
					travelMode: google.maps.TravelMode.WALKING
				};

				// Route the directions and pass the response to a
				// function to create markers for each step.
				directionsService.route(request, function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						var panel = document.getElementById('directions-output');
						panel.innerHTML = '';
						directionsDisplay.setDirections(response);
						directionsDisplay.setPanel(panel);
					}
				});

			}

			/*function showSteps(directionResult) {
				// For each step, place a marker, and add the text to the marker's
				// info window. Also attach the marker to an array so we
				// can keep track of it and remove it when calculating new
				// routes.
				var myRoute = directionResult.routes[0].legs[0];

				for (var i = 0; i < myRoute.steps.length; i++) {
					var marker = new google.maps.Marker({
						position: myRoute.steps[i].start_point,
						map: map
					});
					attachInstructionText(marker, myRoute.steps[i].instructions);
					markerArray[i] = marker;
				}
			}*/

			/*function attachInstructionText(marker, text) {
				google.maps.event.addListener(marker, 'click', function() {
					stepDisplay.setContent(text);
					stepDisplay.open(map, marker);
				});
			}*/

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
					longCatArray = items[i].longName;

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

						if ((catArray[x] == settings.featuredCategory)&&(mapType!='poi')){
							loadfeaturepoints(val.id);
							var anchorFeat = $('<a />').attr({'href':'#', 'class':'feat-link'}).html(val.title)
								.click(function(e) {
									showFeaturePoint(items[i].id,settings.featuredCategory);
									return false;
								}); // show property's info window when clicked from list
							$('<div />').addClass('feature-list').attr('id','feature-list-'+val.id).html(anchorFeat)
							.click(function(e) {
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

				if((mapType=='both') && (settings.printFeatureFirst != undefined) && (settings.printFeatureFirst === true)) {
					$(divFeatureWrapper).append(divFeature);
					$('div#map-wrapper').append(divFeatureWrapper);
					printCategories();
				} else {
					if(mapType!='feature') {
						printCategories();
					}
					if((mapType=='both')||(mapType=='feature')) {
						$(divFeatureWrapper).append(divFeature);
						$('div#map-wrapper').append(divFeatureWrapper);
					}
				}

				if(mapType!='poi')
					setBestView();
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
					if ( (((val!=='property') && (val !== 'featurepoint')) && (excludeCat !== true))  && testsub!=='func') {
						var divCat = $('<div />').addClass('category').attr('id',val)
						.click(function(e) {
							e.preventDefault();
							closeInfoWindows();

							if(settings.activeCatOnly === true) {$(this).siblings('.on').trigger('click');}
							$(this).addClass('on');
							$(this).children('ul.point-list').slideDown();
							loadPoints(val);
							return false;
						}).appendTo(div);

						var anchorCat = $('<a />').attr({'href':'#', 'class':'cat-link'}).html(longcats[i]).appendTo(divCat);

						if(settings.printPoints===true) {printPoints(val,divCat);}

						$('div.category').mouseover(function () {$(this).addClass('hover');});
						$('div.category').mouseout(function () {$(this).removeClass('hover');});
					}
				});
				$(divWrapper).append(div);
				$('div#map-wrapper').append(divWrapper);

				if((settings.featuredCategory!==undefined)) {
					if(settings.featuredCategory==='all'){
						$.each(categories, function(i, val) {
							loadPoints(val);
							$('div#'+val).addClass('on').children('ul.point-list').slideDown();
						});
					} else {
						loadPoints(settings.featuredCategory); // don't delete this - caused the call stack overflow error - oops!
						$('div#'+settings.featuredCategory).addClass('on').children('ul.point-list').slideDown();
					}
				}
			}

			// specific to category accordian
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
								if (settings.showPoiID === true) {
									listHtml = '<span>'+items[j].poiID+'</span> '+items[j].title;
								}
								var pointAnchor = $('<a />').attr('href','#').html(listHtml).click(function(){
									$(this).parent().siblings().find('a').removeClass('active');
									$(this).addClass('active');
									showSinglePoint(items[j].id,cat);
									return false;
								});
								$(pointLi).append(pointAnchor);
								$(pointUl).append(pointLi);
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

			function showSinglePoint(idx,cat) {
				tempL = layers[cat];
				$.each(tempL, function(i, val) {
					var tempPoint = val;
					if (tempPoint.pinID.toString()==idx.toString()) {
						marker=val;
					}
				});
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
						markers.push(marker);
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
						var pushpinOptions = {icon: RELPATH+'images/map/pins/star.gif', width: 31, height: 46, id:j.toString(), typeName:j.toString()};
					} else {
						var pushpinOptions = {icon: RELPATH+'images/map/pins/'+items[j].category+'.png', width: 20, height: 24, id:j.toString(), typeName:j.toString()};
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
				var name = items[idx].property_name;
				var address = items[idx].address;

				var m = items[idx].category;
				var mCat = '';
				$.each(m, function(i) {
					mCat += ' marker-cat-'+m[i];
				});
				// works only for POIs in only one category if added directly to marker pin-label at creation
				var catListPos = $('#categories .category ul').find('li[id="'+items[idx].id+'"]').attr('class').split('-');
				catListPos = catListPos[1];

				var markerContent = document.createElement('DIV');
				if (settings.showPinID === true) {
					//markerContent.innerHTML = '<div class="pin-marker '+mCat+' marker-'+items[idx].id+'"><div class="pin-label">'+items[idx].poiID+'</div></div>';
					markerContent.innerHTML = '<div class="pin-marker '+mCat+' markerid-'+items[idx].id+'"><div class="pin-label">'+catListPos+'</div></div>';
				} else {
					markerContent.innerHTML = '<div class="pin-marker marker-cat-'+items[idx].category+' marker-'+items[idx].id+'"></div>';
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
							//$('#mapDiv').children('div').children('div:last').remove();
							var placeService = new google.maps.places.PlacesService(map);
							placeService.getDetails(placeRequest, function(place, status) {
								if (status == google.maps.places.PlacesServiceStatus.OK) {
									html ='';
									//html = getInfoWindowContent(idx);
										//html = getInfoWindowContent(idx,place,html);
									html = getInfoWindowContent(idx,html);
									var boxText = document.createElement('div');
									boxText.innerHTML = html;

									infoBox.setOptions(settings.infoBoxOptions);
									infoBox.setContent(boxText);
									infoBox.open(map, marker);
							  	}
							});
							//$('#mapDiv').children('div').children('div:last').remove();
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
								//$('#mapDiv').children('div').children('div:last').remove();
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
								//$('#mapDiv').children('div').children('div:last').remove();
							}
						});
					});
			  	}
			  	markers.push(marker);
			  	return marker;
			}

			function closeInfoWindows() {
				if(infoWindow!=null)
					infoWindow.close();
				if(infoBox!=null)
					infoBox.close();
			}

			//function getInfoWindowContent(idx,place,html) {
			function getInfoWindowContent(idx,html) {
				html = '';

				if ( items[idx].totalrate !=='' ) {

					html += '<div class="infoWindowContent win-'+items[idx].id+'">';
					html += '<div class="close-box" onclick="$(this).closest(\'.infoBox\').hide()"></div>';
					html += '<div class="inset"><img src="https://sabrecdn.com/pdbookingv12/images/properties/'+items[idx].thumb+'" alt="" / ></div>';
					html += '<div class="window-content">';
					html += '<div class="map-address">'+items[idx].address+'</div>';
					html += '<div>For Dates Selected</div>';
					html += '<div>$'+items[idx].dailyrate+' per night</div>';
					html += '<div>$'+Math.ceil(items[idx].totalrate)+' subtotal</div>';
					html += '<div>plus taxes &amp; fees</div>';
					html += '<a class="orange-btn" href="booking'+items[idx].booklink+'">View Details</a>';
					html += '</div>';
					html += '</div>';


				} else {

					html += '<div class="infoWindowContent win-'+items[idx].id+'">';
					html += '<div class="close-box" onclick="$(this).closest(\'.infoBox\').hide()"></div>';
					html += '<div class="inset"><img src="https://sabrecdn.com/pdbookingv12/images/properties/'+items[idx].thumb+'" alt="" / ></div>';
					html += '<div class="window-content">';
					html += '<div class="map-address">'+items[idx].address+'</div>';
					html += '<div>Starting from</div>';
					html += '<div>$'+items[idx].minrate+' per night</div>';
					html += '<div>'+items[idx].weekrate+' per week</div>';
					html += '<a class="orange-btn" href="booking'+items[idx].booklink+'">View Details</a>';
					html += '</div>';
					html += '</div>';
				}

				return html;
			}

		}); // End Plugin Return

	}; // End plugin

})( jQuery );



