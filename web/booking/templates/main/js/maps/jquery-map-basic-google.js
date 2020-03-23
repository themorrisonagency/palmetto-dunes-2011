function log(msg){ if ( window.console ) { console.log.call(console, msg); }}

var map;
var items = [];
var infoWindow = new google.maps.InfoWindow({maxWidth:300});
var infoBox = new InfoBox();
var categories = [];
var catArray = [];
var longcats = [];
var longCatArray = [];
var hashURL;
var property;

var start = true; // createMap() sets to false
var loadIds = null;
var layers = new Object();
var centerArray = new Array();
var queryArray = new Array();
var pinLayers = new Object();
var bounds;
var maptype;

/*$(function() {
	var openFnMap=function(hash){	
		hash.w.show();
		hashURL = hash.t;
		$('#map-modal').css({left:($(window).width()-$('#map-modal').width())/2,top:$(window).scrollTop()+10});		
		$('#cstm-submit').live("click",function() { ClickGeocode(); return false; });
		$('#cstm-address').live('click',function() { $(this).val(''); $(this).css('color','#000000'); });
	};
	var hideFnMap=function(hash){
		hash.w.hide()
		hash.o.remove();
		$('#map-content').html('');
		layers = {};
		pinLayers = {};
	};

	$('#map-modal').each(function() {
		$('#map-modal').jqm({ajax:'includes/map-google.php', trigger: 'a#google-map-trigger', onShow: openFnMap, onHide: hideFnMap, target:'div#map-content' });
	});
	
});*/

function GoogleMap(mapid,type,project) {
	id = mapid;
	mapType = settings.mapType;
	$('#map-wrapper').addClass('google-map');
	this.getData();					
	$(window).unload(function() { if(this._map) { this._map.Dispose(); } });
	
}

function getData() {
	var obj = this;

	/*$.getJSON(RELPATH+'includes/map-nikko.json', function(jobj) {
		alert(jobj.rss.channel.item[2].address);
	});*/
	/*$.getJSON('/poi', function(jobj) {
		//console.log(jobj);

		var raw = jobj;
		console.log(raw);
	});*/

	/*$.getJSON('/poi', function(data) {
		$.each(data.record.record, function(key, val) {
	    	var cid = $(this);
	    	var id = cid[0]['@attributes'].id; // auto-generated
	    	var title = cid[0]['@attributes'].title; // admin required
	    	var category = cid[0].category.record ? cid[0].category.record :null; // array
	    	var desc = cid[0].description; // admin required
	    	var website = cid[0].website.length ? cid[0].website : null;
	    	var address = cid[0].address.length ? cid[0].address : null;
	    	var images = cid[0].images.record ? cid[0].images.record : null; // array
	    	var georss = cid[0]['@attributes'].georss_point.length ? cid[0]['@attributes'].georss_point : null;
	    	console.log(georss);
	  	});

	});*/

	$.get(settings.dataSource, function(data) { 			
		$('item', data).each(function(i) {
			items[i] = {};
			$(this).children().each(function() {
				if (this.tagName == 'category'){		
					var tempCat = $(this).text().split(",");
					var catName = [];
					var longName = [];
					
					if (tempCat.length > 1) { 
						var tempCatArray = tempCat;
						catName = [];
						longName = [];
	
						for (x in tempCatArray){								
							if ( tempCatArray.hasOwnProperty(x) ) {
								var tempCatName = tempCatArray[x].replace(/\W/g, "");
								var longCatName = tempCatArray[x].replace(/^\s+|\s+$/g, "");
								tempCatName = tempCatName.toLowerCase(); 
								catName.push(tempCatName);										
								longName.push(longCatName);										
							}
						}
					} else { 
						catName = [];
						longName = [];
						var tempCatName = $(this).text().replace(/\W/g, "");
						var longCatName = $(this).text().replace(/^\s+|\s+$/g, "");
						tempCatName = tempCatName.toLowerCase(); 
						catName.push(tempCatName);	
						longName.push(longCatName);	
					}
					items[i][this.tagName] = catName; 
					items[i]['longName'] = longName;
				} else if (this.tagName == 'categories'){
					$(this).children().each(function() {
						var tempCat = $(this).text();
						var catName = [];
						var longName = [];

						var tempCatName = $(this).text().replace(/\W/g, "");
						var longCatName = $(this).text().replace(/^\s+|\s+$/g, "");
						tempCatName = tempCatName.toLowerCase(); 
						catName.push(tempCatName);	
						longName.push(longCatName);	

						items[i][this.tagName] = catName;
						items[i]['longName'] = longName;
					});	
				} else 
					items[i][this.tagName] = $(this).text();
			});
		});
		createMap();
	});	
}

function createMap() {
	// initial point to load map, 
	if ( items[0]['georss:point'] ) {
		var fp = getLatLong(items[0]['georss:point']);
	} else if ( items[0]['georss:polyline'] ) {
		var fp = getLatLong(items[0]['georss:polyline']);
	}
	var latlng = new google.maps.LatLng(parseFloat(fp[0]),parseFloat(fp[1]));
	var mapOptions = {
		center: latlng,
		zoom: settings.zoom,
		mapTypeId: settings.mapTypeId,
		disableDefaultUI: settings.disableDefaultUI,
		mapTypeControl: settings.mapTypeControl,
		mapTypeControlOptions: settings.mapTypeControlOptions,
		navigationControl: settings.navigationControl,
		navigationControlOptions: settings.navigationControlOptions,
		scaleControl: settings.scaleControl,
		keyboardShortcuts: true	// no good reason to ever disable, so not in settings
	};

	map = new google.maps.Map(document.getElementById(id), mapOptions);
	var marker = new google.maps.Marker();
		
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
		/*if(infoWindow!=null)
			infoWindow.close();*/
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
		if (items[j].category=='featurepoint') {				
			var qID = items[j].id;
			var loc = getCenterPoint(j);
			
			currentID = qID;
			l=layers[qID];	
			l.setIcon(RELPATH+'images/map/pins/pin-'+items[j].id+'-large.png');
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
	var obj = this;
	var divFeatureWrapper = $('<div />').attr('id','feature-wrapper');
	var divFeature = $('<div />').attr('id','feature-points');
	
	$.each(items, function(i, val) {			
		if(settings.centerPoint==items[i].id) centerPoint=i;
		var dup = false; // duplicate set to false
		var longDup = false; // duplicate set to false
		var tempsplit = val.category; // split each subcategory node
		
		catArray = val.category;
		longCatArray = val.longName;
		
		for (x in catArray){
			dup = false; // duplicate set to valse
			$.each(categories, function(j, val2) {
				if ( catArray[x]	 == val2 ) {
					dup = true;
				}					
			});
			
			if ( !window.one ) {
				window.one = [];
			}
			window.one.push(val);
			
			//if ((catArray[x] == 'featurepoint')&&(maptype!='poi')) {
			//if ((catArray[x] == settings.featuredCategory)&&(mapType!='poi')){
			if ((items[i].id == settings.centerPoint)&&(mapType!='poi')){
				loadfeaturepoints(val.id);	
				$('<div />').addClass('feature-list').attr('id','feature-list-'+val.id).html(val.title)
				.click(function(e) {
					//e.preventDefault();							
					loadfeaturepoints(val.id);		
					//setBestView(); // will reset zoom - make an option??
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
			longDup = false; // duplicate set to false
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

	if(maptype!='feature' && settings.includeCategoryWrapper==true)
		printCategories();
	
	if((maptype=='both')||(maptype=='feature')) {
		$(divFeatureWrapper).append(divFeature);
		$('div#map-wrapper').append(divFeatureWrapper);
	}
	
	if(maptype!='poi')
		setBestView(settings.zoom);		
}
	
function printCategories() {
	// Print all of the categories
	var divWrapper = $('<div />').attr('id','category-wrapper');
	var div = $('<div />').attr('id','categories');
	
	$.each(categories, function(i, val) {
		var testsub = val.toString();
		testsub = testsub.substring(0,4);
		// This should remove any of the "feature" points category listing.  feature points are listed separately. 
		if ( ((val!='property') && (val != 'featurepoint')) && testsub!='func') {
			var divCat = $('<div />').addClass('category').attr('id',val)	
			.click(function(e) {
				e.preventDefault();
				closeInfoWindows();
				// added the following line to close other categories and clear pins
				if (settings.activeCatOnly == true) {
					$(this).siblings('.on').trigger('click');
				}
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

	if((settings.featuredCategory!=undefined)) {
		if(settings.featuredCategory=='all'){
			$.each(categories, function(i, val) { 
				loadPoints(val);
				$('div#'+val).addClass('on').children('ul.point-list').slideDown();
			});
		} else {
			loadPoints(settings.featuredCategory);
			$('div#'+settings.featuredCategory).addClass('on').children('ul.point-list').slideDown();
		}
	}
}

// specific to nikko/trump style category accordian
function printPoints(cat,divCat) {
	var obj=this;
	var cats = cat===null?categories:[cat];
	var catName = '';
	var pointUl = $('<ul />').attr('id','point-list-'+cat).addClass('point-list');
	$.each(cats, function(i, val) {
		layers[val] = new Array;
		//var shapes = [];
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
						var pointLi = $('<li />').attr('id',items[j].id);							
						var pointAnchor = $('<a />').attr('href','#').html(items[j].title).click(function(){
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
					var catSelectedArray = new Array( );
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
						l.setIcon(RELPATH+'images/map/pins/pin-'+items[pinidx].id+'-small.png');
		
						$('div#feature-list-'+featureid).removeClass('on');
						$('div#feature-list-'+featureid).addClass('off');
						$(this).removeClass('on');
					}
				});					
			}
			loadIds = null;
			if(settings.resetViewOnCategoryChange==true && start ==false) {
				setBestView(settings.zoom);	// breaking - map now showing - only water color						
			}
		});
	}
}

// nikko/trump
function showSinglePoint(idx,cat) {		
	tempL = layers[cat];			
	$.each(tempL, function(i, val) {
		var tempPoint = val;
		if (tempPoint.pinID.toString()==idx.toString()) {
			marker=val;
		}
	});
	google.maps.event.trigger(marker, "click");		
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
						l2.setIcon(RELPATH+'images/map/pins/pin-'+items[pinidx].id+'-small.png');
		
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
				setBestView(settings.zoom);
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
	featureid = featureid.toString()
	l=layers[featureid];		
	if(l!=undefined) {
		if ( l.getVisible() ) {		
			if ( $('div#feature-list-'+featureid).hasClass('on') == false ) {
				var obj = this;																		
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

						var loc = new google.maps.LatLng(parseFloat(point[0]), parseFloat(point[1]));
						map.setOptions({center: loc, zoom: 16 });
						currentID = featureid;
						l.setIcon(RELPATH+'images/map/pins/pin-'+items[j].id+'-large.png');
						$('div#feature-list-'+featureid).addClass('on');
					}
				});								
			} else {
				var obj = this;						
				var pinidx = getPinIndex(featureid);
				//l.setIcon(RELPATH+'images/map/pins/pin-'+items[pinidx].id+'-small.png');
				$('div#feature-list-'+featureid).removeClass('on');
				$('div#feature-list-'+featureid).addClass('off');
			}
			$('.feature-list').each(function() { 
				var featureon = $(this).attr('id').toString().substr(13);	
				if ($(this).hasClass('on') && (featureon!=featureid)) {							
					var pinidx = getPinIndex(featureon);
					l2=layers[featureon];	
					//if(l2!=undefined)
						//l2.setIcon(RELPATH+'images/map/pins/pin-'+items[pinidx].id+'-small.png');
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
	
function addPOIPoint(shapes, j, cat, cstm) {
	var obj = this;
	var info = '';

	var point;
	if ( items[j]['georss:point'] == undefined) {
		if ( items[j]['georss:polyline'] != undefined) { 
			point = obj.getLatLong(items[j]['georss:polyline']); 
		}
	} else {
		point = obj.getLatLong(items[j]['georss:point']);
	}

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
	var obj = this;
	var info = '';
	//var point = obj.getLatLong(items[j]['georss:point']); 

	var point;
	if ( items[j]['georss:point'] == undefined) {
		if ( items[j]['georss:polyline'] != undefined) { 
			point = obj.getLatLong(items[j]['georss:polyline']); 
		}
	} else {
		point = obj.getLatLong(items[j]['georss:point']);
	} 

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
	var pts = point.split(" ");
	return pts;
}

function getCenterPoint(j) { 
	//var centerPoint = getLatLong(items[j]['georss:point']); 		

	var centerPoint;
	if ( items[j]['georss:point'] == undefined) {
		if ( items[j]['georss:polyline'] != undefined) { 
			centerPoint = getLatLong(items[j]['georss:polyline']); 
		}
	} else 
		centerPoint = getLatLong(items[j]['georss:point']); 

	if (centerPoint != ''&centerPoint!='null,null')
		return loc = new google.maps.LatLng(parseFloat(centerPoint[0]), parseFloat(centerPoint[1]));
	else
		return false; 
}
 
 function setBestView(zoom) {
	bounds = new google.maps.LatLngBounds( );
	var pointCount = centerArray.length;
	for ( var i = 0; i < centerArray.length; i++ ) {
		if(centerArray[i]!=false)
			bounds.extend( centerArray[ i ] );
	}
	if (pointCount > 1) {
		map.fitBounds(mapBounds);
	}
	else if (pointCount == 1) {
		map.setCenter(bounds.getCenter());
		map.setZoom(zoom);
	}
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
	//infoWindow = new google.maps.InfoWindow();  
	var name = items[idx].property_name;
	var address = items[idx].address;

	var markerContent = document.createElement('DIV');
	markerContent.innerHTML = '<div class="pin-marker marker-cat-'+items[idx].category+' marker-'+items[idx].id+'"><div class="pin-label">'+items[idx].id+'</div></div>';

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
	html += '<div class="infoWindowContent '+items[idx].category+' win-'+items[idx].id+'">';
	html += '<div class="close-box" onclick="infoBox.close();">[x]</div>';
	$.each(items[idx], function(k, val2) {  // k is key, ie category, type, image etc.
		if((val2!='')&&(val2!=undefined)) {
			switch(k) {
				case 'title':
					html += '<h4>'+items[idx].title+'</h4>';
					break;
				case 'image':
					html += '<div class="inset"><img src="'+items[idx].image+'" alt="" / ></div>';	
					break;
				case 'address':
					var addy ='Not in XML';
					var testXML = items[idx].address;
					if ( testXML !=undefined && testXML.length > 10 ) addy = items[idx].address;
					html += '<p>'+addy+'</p>';
					break;
				case 'description':
					html += '<div class="description">'+items[idx].description+'</div>';
					break;
				case 'website':
					html += '<a class="website" href="'+items[idx].website+'" target="_blank">'+items[idx].website+'</a>';
					break;
			}
		} else {
			switch(k) {
				case 'title':
					html += '<h4>' + place.name + '</h4>';
					break;
				case 'image':
					html += '<div class="inset"><img src="'+items[idx].image+'" alt="" / ></div>';	
					break;
				case 'address':
					html += '<p>'+place.formatted_address+'</p>';
					break;
				case 'description':
					html += '<div class="description">'+items[idx].description+'</div>';
					break;
				case 'website':
					html += '<a class="website" href="'+items[idx].website+'" target="_blank">'+items[idx].website+'</a>';
					break;
			}
		}
	});
		/*$('body').each(function() {
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
		});*/
	html += '</div>';
	return html;
}

