
(function( $ ){

  $.fn.bingMap = function( options ) {  

    // Create some defaults, extending them with any options that were provided
    var settings = $.extend( {
		mapType	: 'feature',			// Plugin map type { 'feature', 'poi', or 'both' }
      	mapStyle : 'road',  		// Style of Map { 'aerial', 'auto', 'birdseye', 'collinsBart', 'mercator', 'ordnanceSurvey', 'road': }
		xml : MAPDATA, // this file will need to be added to .htaccess FilesMatch Allow from 127.0.0.1/localhost etc. to work
		credentials	: 'AlhQ2tbqEMqXpqkcpMtPeOZE3b_LfrLrN9JSvHD3NqG2D7R00kfem-1QhN3L_ki7', // Credentials for each map. Get from https://www.bingmapsportal.com/
		exceptions : [''], 			// need to setup (from Justin - need to check with him for intent)
		useSingleIcon : false, 		// if true, use same icon for all, without letters, numbers etc.
		activeCatOnly : true,
		showPinID: false,
		showPoiID: false,
		singleIcon	: {
			url : RELPATH+'/images/map/pins/pin-specific-1.png', // path to uniform icon
			width : 24, 			// width of icon in px ( do not include 'px')
			height : 24 			// height of icon in px ( do not include 'px' )
		},
		smallIcon : {
			width : 24, 			// width of icon in px ( do not include 'px')
			height : 24 			// height of icon in px ( do not include 'px' )
		},
		largeIcon : {
			width : 258, 			// width of icon in px ( do not include 'px')
			height : 35 			// height of icon in px ( do not include 'px' )
		},
		featureBox : {
			width : 300,			// set width here for positioning calculations in functions
			height : 250,			// set for initial positioning
			top : 10, 				// # of px open infoBox should be below controls
			horizontalOffset : 0,	// increasing # moves to right - try 1/2 padding to center
			verticalOffset: 0,		// increasing # moves up; note, top: may be more precise
			displayCatIcons: true,	// if true, generates list to display category icons
			needsImageSwap: false	// needed with POI admin files only, where XML has resized image (100px wide) 
									// in 'small' directory, and uploaded image in 'orig'
									// set true to have larger image in featureBox
		},
		poiBox : {
			width : 300,			// set width here for positioning calculations in functions
			height : 250,			// set for initial positioning
			top : 10,				//# of px open infoBox should be below controls
			horizontalOffset : 0, 	// increasing # moves to right - try 1/2 padding to center
			verticalOffset: 0,  		// increasing # moves up; note, top: may be more precise
			displayCatIcons: true	// if true, generates list to display category icons
		},
		printPoints 	: false, 	// true shows the list of points when a category is clicked. 
		showCat			: 'all', 	// will show non feature category when the map loads.  Can be a specific cateogory or set to "all" to show all categories.
		hover			: true, 	// if set to true non feature points will show infowindow on hover
		zoom			: 10,  		// default zoom when map loads 
		centerPoint		: '1',		// center point when map loads.  This will need to be the ID of the element from the xml
		startPoint		: '29.22889,-1.40625',	// same thing as center Point
		featuredCategory : 'featurepoint',		// default category, ie partners, condos, etc
		// additions -  not all fully implemented / tested
		closeAllWindows	: false,	//true closes all pinInfobox on click of another whatever
		closeFeatureWindows	: false, 			//true close all pinInfobox when another feature item clicked
		closeCatWindows	: false, 	//true close all pinInfobox when another category item clicked
		useLargeIcon	: true 		//true uses intermediate large pin with poi name etc to trigger pinInfobox
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
		mapType = settings.mapType;
		console.log(mapType);
		$('#map-wrapper').addClass('bing-map'); // optional
		getData();							
		$(window).unload(function() { if(this._map) { this._map.Dispose(); } });
	
		function getData() {
			
			$.get(settings.xml, function(data) { 			
				$('item', data).each(function(i) {
					items[i] = {};
					$(this).children().each(function() {
						if (this.tagName == 'category'){		
							var tempCat = $(this).text().split(",");
							var catName = [];
							
							if (tempCat.length > 1) { 
								var tempCatArray = tempCat;
								catName = [];
			
								for (x in tempCatArray){								
									if ( tempCatArray.hasOwnProperty(x) ) { // added by meder 12.14.10
										var tempCatName = tempCatArray[x].replace(/\W/g, "");
										tempCatName = tempCatName.toLowerCase(); 
										catName.push(tempCatName);										
									}
								}
							} else { 
								catName = []
								var tempCatName = $(this).text().replace(/\W/g, "");
								tempCatName = tempCatName.toLowerCase(); 
								catName.push(tempCatName);	
							}
							items[i][this.tagName] = catName;
						}else 
							items[i][this.tagName] = $(this).text();
					});
				});	
				createMap();
			});	
		}
	
		function createMap() {
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
					mapStyle = Microsoft.Maps.MapTypeId.road;
					break;
				default:
					mapStyle = Microsoft.Maps.MapTypeId.road;
					break;
					
			}
			var mapOptions = {
				credentials: settings.credentials,
				mapTypeId: mapStyle
			}
			map = new Microsoft.Maps.Map(document.getElementById(id), mapOptions);
		
			// initial point to load map, 
			if ( items[0]['georss:point'] ) {
				var fp = getLatLong(items[0]['georss:point']);
			} else if ( items[0]['georss:polyline'] ) {
				var fp = getLatLong(items[0]['georss:polyline']);
			}

			// Retrieve the latitude and longitude values- normalize the longitude value
			var latVal = parseInt(fp[0]);
			var longVal = Microsoft.Maps.Location.normalizeLongitude(parseInt(fp[1]));
	
			// Set the map center
			map.setView({zoom: 10, center:new Microsoft.Maps.Location(latVal, longVal)});
			
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
					var pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+qID+'-large.png', width: settings.largeIcon.width, height: settings.largeIcon.height, zIndex:1002};
					var pinUpdate = getMapEntity(qID);
					pinUpdate.setOptions(pushpinOptions);													
					$('div#feature-list-'+qID).addClass('on');
					var loc = getCenterPoint(j);			
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
			
			var divFeatureWrapper = $('<div />').attr('id','feature-wrapper');
			var divFeature = $('<div />').attr('id','feature-points');
			$.each(items, function(i, val) {		
				if(settings.centerPoint==items[i].id)
					centerPoint=i;				   
								   
				var dup = false; // duplicate set to false
				var tempsplit = val.category; // split each subcategory node
				
				catArray = val.category;
				
				for (x in catArray){
					dup = false; // duplicate set to valse
					$.each(categories, function(j, val2) {
						if ( catArray[x]	 == val2 ) {
							dup = true;
						}					
					});
					
					if ( !window.one ) {
						window.one = []
					}
					window.one.push(val)
					
					if ((catArray[x] == settings.featuredCategory)&&(mapType!='poi')){
						loadfeaturepoints(val.id);	
						$('<div />').addClass('feature-list').attr('id','feature-list-'+val.id).html(val.title)
						.click(function() {
							if(settings.closeFeatureWindow==true) {
								if (pinInfobox!=null){
									var isvisible=pinInfobox.getVisible(); 
									if(isvisible==true) {
										hideInfobox();
									}
								}
							}
							// close infobox if open; needed for close pins where user doesn't close manually
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
				
			});
			
			if(mapType!='feature')
				printCategories();
			
			if((mapType=='both')||(mapType=='feature')) {
				$(divFeatureWrapper).append(divFeature);
				$('div#map-wrapper').append(divFeatureWrapper);
			}

			if((settings.centerPoint!=null)) {
				var loc = getCenterPoint(centerPoint);											
				map.setView({center:loc, zoom:settings.zoom});
			}
			mapLoading = false;	
				
		}
	
		function printCategories() {
			// Print all of the categories
			var divWrapper = $('<div />').attr('id','category-wrapper');
			var div = $('<div />').attr('id','categories');
			
			$.each(categories, function(i, val) {
				var testsub = val.toString();
				testsub = testsub.substring(0,4);
				// This should remove any of the "feature" points category listing.  feature points are listed separately. 
				if ( ((val!='property') && (val != settings.featuredCategory)) && testsub!='func') {	
					var divCat = $('<div />').addClass('category').attr('id',val).html(val)
					.click(function() {
						$(this).addClass('on');
						$(this).children('ul.point-list').slideDown();
						loadPoints(val);							
						return false;
					}).appendTo(div);	
					
					if(settings.printPoints==true)
						printPoints(val,divCat);
					
					$('div.category').mouseover(function () {$(this).addClass('hover');});
					$('div.category').mouseout(function () {$(this).removeClass('hover');});
				} 
			});
			$(divWrapper).append(div);
			$('div#map-wrapper').append(divWrapper);
			if((settings.showCat!='')&&(settings.showCat!=undefined)) {
				if(settings.showCat=='all'){
					$.each(categories, function(i, val) { 
						if(val!='')	{						
							loadPoints(val);					  
							$('div#'+val).addClass('on');
							$('div#'+val).children('ul.point-list').slideDown();
						}
					});
				} else {
					loadPoints(settings.showCat);
					$('div#'+settings.showCat).addClass('on');
					$('div#'+settings.showCat).children('ul.point-list').slideDown();
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
							//added
							var point;
							if ( items[j]['georss:point'] == undefined) {
								if ( items[j]['georss:polyline'] != undefined) { 
									point = items[j]['georss:polyline']; 
								}
							} else {
								point = items[j]['georss:point']; 
							}
							//if((catSelectedArray[x]==val)&&(items[j]['georss:point']!='')) {
							if((catSelectedArray[x]==val)&&point!='') {
								var pointLi = $('<li />').attr('id',items[j].id).attr('href','#');							
								var pointAnchor = $('<a />').html(items[j].title).click(function(){
									showSinglePoint(items[j].id,cat);
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
			var notMade = checkLayers(cat);
			var catName = '';
			if(notMade) {
				var pinLayer = new Microsoft.Maps.EntityCollection();
				$.each(cats, function(i, val) {
					layers[val] = new Microsoft.Maps.EntityCollection();
					
					if(loadIds!==null) {
						$.each(loadIds, function(k, val2) {
							$.each(items, function(j) {
								if(items[j].id==val2) {
									//cat = 'allcats';
									var d = addPOIPoint(j,  cat, pinLayer, 'qstring');
									var loc = getCenterPoint(j);								
									centerArray.push(loc);
								}								
							});						
						});
					} else {
						$.each(items, function(j) {										   										   
							var catSelectedArray = new Array( );
							var currarray = String(items[j].category);
							catSelectedArray = currarray.split(",");							
							$.each(catSelectedArray,function(x) {
								if((catSelectedArray[x]==val)&&(val!=settings.featuredCategory)) {
									var d = addPOIPoint(j, cat, pinLayer);
									var loc = getCenterPoint(j);								
									centerArray.push(loc);
								}
							});
						});
					}								
					if ( (cat != 'property') && (cat != settings.featuredCategory)) {
						$('.feature-list').each(function() { 
							if ($(this).hasClass('on')) {	
								var featureon = $(this).attr('id').toString().substr(13,1);
								var mapLayers = map.entities.getLength();
								for(var h=0; h<mapLayers; h++) {
									var l =map.entities.indexOf(h);
									if(l.id!=undefined && l.id==featureon) {
										if ( l.getVisible() ) {
											var pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+items[j].id+'-small.png', width: settings.smallIcon.width, height: settings.smallIcon.height, zIndex:1002};
											var pinUpdate = getMapEntity(featureon);
											pinUpdate.setOptions(pushpinOptions);	
											$(this).removeClass('on');
										}
									}
								}
							}
						});					
					}
					entityIdx = map.entities.getLength();
					pinLayers[cat]=entityIdx;
					map.entities.push(pinLayer);
					map.setView({ bounds: Microsoft.Maps.LocationRect.fromLocations(centerArray)  })
					centerArray.length = 0;
					loadIds = null;
				});
			}
		}
	
		function showSinglePoint(idx,cat) {		
				tempL = layers[cat];		
				var mapLayers = map.entities.getLength();
				entityIdx=pinLayers[cat];
				pinL=map.entities.get(entityIdx);
				
				for (var i = 0; i< pinL.getLength(); i++) {
					var elt = pinL.get(i);
					if( elt.getTypeName().toString() == idx.toString() ) {
						pinIndex = elt.getId();
						//showInfobox(pinIndex);
						displayInfobox(elt);
					}
				}
		}
	
		function getMapPin(pinid,pincat) {
			for (var i = 0; i < map.entities.getLength(); i++) {
				var elt = map.entities.get(i);
				var eltId = elt.getId();
				if (items[eltId].id == pinid.toString())
					return elt;					
			}		
		}
		
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
								var pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+featureon+'-small.png', width: settings.smallIcon.width, height: settings.smallIcon.height}; 
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
								if ( items[j]['georss:point'] == undefined) {
									if ( items[j]['georss:polyline'] != undefined) { 
										point = getLatLong(items[j]['georss:polyline']); 
									}
								} else {
									point = getLatLong(items[j]['georss:point']); 
								}
								map.setView({mapTypeId : Microsoft.Maps.MapTypeId.road, zoom: 16, center: new Microsoft.Maps.Location(parseFloat(point[0]), parseFloat(point[1])) });
								
								// this section deals with the large icon, so it's gotta be something here
								currentID = featureid;

								if (settings.useLargeIcon==true) {
									var pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+items[j].id+'-large.png', width: settings.largeIcon.width, height: settings.largeIcon.height, zIndex:1002};
								} else if (settings.useSingleIcon==true) {
									var pushpinOptions = {icon: items[j]['pushpin'], width: settings.singleIcon.width, height: settings.singleIcon.height, zIndex:1002, text: items[j]['id']};
								} else {
									var pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+items[j].id+'-small.png', width: settings.smallIcon.width, height: settings.smallIcon.height, zIndex:1002};
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
						var pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+items[pinidx].id+'-small.png', width: settings.smallIcon.width, height: settings.smallIcon.height}; 						
						var pinUpdate = getMapEntity(featureid);
						pinUpdate.setOptions(pushpinOptions);
						
						$('div#feature-list-'+featureid).removeClass('on');
						$('div#feature-list-'+featureid).addClass('off');
					}
					$('.feature-list').each(function() { 
						var featureon = $(this).attr('id').toString().substr(13); // extract the number
						if ($(this).hasClass('on') && (featureon!=featureid)) {		
							var pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+featureon+'-small.png', width: settings.smallIcon.width, height: settings.smallIcon.height}; 
							var pinUpdate = getMapEntity(featureon);
							pinUpdate.setOptions(pushpinOptions);
							$(this).removeClass('on');
						}
					});
				} else {
					var pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+items[j].id+'-small.png', width: settings.smallIcon.width, height: settings.smallIcon.height}; 
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
			if ( items[j]['georss:point'] == undefined) {
				if ( items[j]['georss:polyline'] != undefined) { 
					point = getLatLong(items[j]['georss:polyline']); 
				}
			} else {
				point = getLatLong(items[j]['georss:point']);
			} 

			if (point != '' && point!='null,null') {
				var loc = new Microsoft.Maps.Location(parseFloat(point[0]), parseFloat(point[1]));
				if ( items[j].category.length > 1 ) {
					var pushpinOptions = {icon: RELPATH+'images/map/pins/star.gif', width: 31, height: 46, id:j.toString(), typeName:items[j].id.toString()}; 
				} else {
					var pushpinOptions = {icon: RELPATH+'images/map/pins/'+items[j].category+'.png', width: 27, height: 27, id:j.toString(), typeName:items[j].id.toString()}; 
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
			if ( items[j]['georss:point'] == undefined) {
				if ( items[j]['georss:polyline'] != undefined) { 
					point = getLatLong(items[j]['georss:polyline']); 
				}
			} else {
				point = getLatLong(items[j]['georss:point']);
			} 
			if (point != '' && point!='null,null') {
				var loc = new Microsoft.Maps.Location(parseFloat(point[0]), parseFloat(point[1]));
				if (cstm=='qstring') {
					var pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+items[j].id+'-large.png', width: settings.largeIcon.width, height: settings.largeIcon.height, id:j.toString(), typeName:'feature'}; 
				}
				else {
					if (settings.useSingleIcon == true)
						pushpinOptions = {icon: items[j]['pushpin'], width: settings.singleIcon.width, height: settings.singleIcon.height, id:j.toString(), typeName:'feature', text: items[j]['id']};
					else
						pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+items[j].id+'-small.png', width: settings.smallIcon.width, height: settings.smallIcon.height, id:j.toString(), typeName:'feature'};					//var pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+items[j].id+'-small.png', width: 27, height: 27, id:j.toString(), typeName:'feature'}; 
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
			if ( items[j]['georss:point'] == undefined) {
				if ( items[j]['georss:polyline'] != undefined) { 
					centerPoint = getLatLong(items[j]['georss:polyline']); 
				}
			} else {
				centerPoint = getLatLong(items[j]['georss:point']); 
			}
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
					var pushpinOptions = {icon: items[j]['pushpin'], width: settings.singleIcon.width, height: settings.singleIcon.height, text: items[j]['id']}; 						
				} else {
					var pushpinOptions = {icon: RELPATH+'images/map/pins/pin-'+currentID+'-small.png', width: settings.smallIcon.width, height: settings.smallIcon.height}; 						
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
					//changePin();
					
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

/* feature/poi window functions */

		function getTitle(j) {
			if(items[j].title!==''&&items[j].title!==undefined) {
				info += '<div class="title">'+items[j].title+'</div>';
			}
		}

		function getDescription(j) {
			if(items[j].description!==''&&items[j].description!==undefined) {
				info += '<div class="description">' + items[j].description + '</div>';
			}
		}

		function getCatIconList(j) {
			var catarraytemp = String(items[j].category);
			catarraytemp = catarraytemp.split(",");
			info += '<ul class="itemsub">'; // for category icons
			for (x in catarraytemp){								
				info += '<li id="iconcat-'+ catarraytemp[x] + '">' + catarraytemp[x] + '</li>';
			}
			info += '</ul>';
		}

		function getAddress(j) {
			if(items[j].address!==''&&items[j].address!==undefined) {
				info += '<div class="address">' + items[j].address + '</div>';
			}
		}

/* end window functions */

		function addInfoboxPOI(elt) {	
			var infoboxOptions;
			var typeName = elt.getTypeName();
			var j = parseFloat(elt.getId());
			/*
			typeName 'feature' refers to items clicked from featured category of mapType:'both' , or main list if mapType:'feature'
			else .window-poi refers to items clicked from non-feature category of mapType:'both', or main list if mapType:'poi'
			*/
			if ( typeName  == 'feature') {	
				// need to find way to set markup variations with settings
					info = '<div class="window-feature">';
						info += '<div class="more-info-content">';
						info += '<a href="#" onclick="HideInfoBoxWindow();return false;" class="btn-x-info infobox-close">Close</a>';
						if(items[j].category!==''&&items[j].category!==undefined) {
							if ( items[j].category.length > 1 ) {
								if(settings.featureBox.displayCatIcons==true) {
									getCatIconList(j);
								}
							} 
							getTitle(j);
							getDescription(j);				
							if ( (items[j].image!==''&&items[j].image!==undefined) || (items[j].website!==''&&items[j].website!==undefined) ) {
								info += '<div class="box-links">';
								if(items[j].image!==''&&items[j].image!==undefined) {
									var imageurl = items[j].image;
									if (settings.featureBox.needsImageSwap!==undefined && settings.featureBox.needsImageSwap==true) {
										imageurl = imageurl.replace('small','orig'); //poi admin stores larger image in /orig/ dir, and server-resized 100px width one in /small/
									}
									info += '<div class="popup-image"><img src="'+imageurl+'" alt="" /></div>';							
								}
								if(items[j].website!==''&&items[j].website!==undefined) {
									info += '<a class="link" href="'+items[j].website+'" target="_blank"><em class="alt">Learn More</em></a>';
								}		
								info += '</div>';		
							}
							getAddress(j);
						}
						info += '</div>';
					info += '</div>';

					infoboxOptions = { width : settings.featureBox.width, height :settings.featureBox.height, offset:new Microsoft.Maps.Point(settings.featureBox.horizontalOffset,settings.featureBox.verticalOffset), showPointer: true, visible: true, zIndex: 100, htmlContent: info };

			} else {	  		
				info = '<div class="window-poi">';
					info += '<a href="#" onclick="HideInfoBoxWindow();return false;" class="btn-x infobox-close">Close</a>';			
					if(items[j].category!==''&&items[j].category!==undefined) {
						if ( items[j].category.length > 1 ) {
							if(settings.poiBox.displayCatIcons==true) {
								getCatIconList(j);
							}
						}
						getTitle(j);
						if(items[j].image!==''&&items[j].image!==undefined) {
							info += '<div id="pin-image"><img src="'+items[j].image+'" alt="" /></div>';
						}
						getAddress(j);
						getDescription(j);
						info += '<p class="box-links">';
						if(items[j].moreinfo!==''&&items[j].moreinfo!==undefined) {
							info += '<a onclick="map.HideInfoBox();return false;" href="'+items[j].moreinfo+'" target="_blank">More Info</a>';
						}		
						if (items[j].moreinfo!==''&& items[j].moreinfo!==undefined && items[j].schedule!=='' && items[j].schedule!==undefined) {
							info += '<span class="box-pipe">|</span>';	
						}		
						if(items[j].schedule!==''&&items[j].schedule!==undefined) {
							info += '<a onclick="map.HideInfoBox();return false;" href="'+items[j].schedule+'" target="_blank">Schedule</a>';
						}				
						info += '</p>';		
					}
				info += '</div>';  
				infoboxOptions = { width : settings.poiBox.width, height :settings.poiBox.height, offset:new Microsoft.Maps.Point(settings.poiBox.horizontalOffset,settings.poiBox.verticalOffset), showPointer: true, visible: true, zIndex: 100, htmlContent: info };
			}
			if (pinInfobox != null) {
				pinInfobox.setLocation(elt.getLocation());
				pinInfobox.setOptions(infoboxOptions);
			
			} else {
				pinInfobox = new Microsoft.Maps.Infobox(elt.getLocation(), infoboxOptions);
				map.entities.push(pinInfobox);
			}

			if ( typeName  == 'feature') {
				var topOffset = settings.featureBox.top + 5; // need to add 5 px for the measurement to work out correctly
			} else {
				var topOffset = settings.poiBox.top + 5;
			}
		  
			var buffer = 75; 
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
				map.setView({ centerOffset : new Microsoft.Maps.Point(dx-settings.featureBox.horizontalOffset,dy+settings.featureBox.verticalOffset), center : map.getCenter()});
			}
			$('.window-feature').parent().show();	
			$('.window-feature').parent().parent().css('z-index',1010);	
			$('.window-poi').parent().show();
			pinInfobox.setOptions({visible:true});
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

function HideInfoBoxWindow() {
	$('.window-feature').parent().hide();
	$('.window-poi').parent().hide();
}

$(function() {
	var mapSettings = {
		xml : MAPDATA,
		printPoints: true,
		showCat: 'all',
		hover: false,
		zoom: 9,
		centerPoint:'1',
		mapType: 'feature',
		closeFeatureWindows: true,
		useLargeIcon: false,
		useSingleIcon: true,
		featureBox : {
			width : 244,
			height : 275,
			top : 0,
			horizontalOffset : 10,
			verticalOffset: -190,
			displayCatIcons: false,
			needsImageSwap: false
		},
		poiBox : {
			width : 244,
			height : 275,
			top : 20,
			horizontalOffset : 0,
			verticalOffset: 0,
			displayCatIcons: false
		},
		singleIcon: {
			url : RELPATH+'images/map/map-pin.png',
			width : 34,
			height : 36
		}
	};
	try {$('#map-wrapper div#mapDiv').bingMap(mapSettings);} catch (err) {}
	//$('#cstm-submit').live("click",function() { findCustom($.trim($('#cstm-address').val()), true); return false; });
	//$('#cstm-address').bind('click',function() { $(this).val(''); $(this).css('color','#000000'); });
});    

