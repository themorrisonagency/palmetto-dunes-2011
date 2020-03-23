$(function() {
/*

// google

	dataSource: '/poi', // '/poi' is default - if multiple maps on a site, will need to set for each. must be a standalone file, not one with rendered content
	mapType: 'both', // feature: only show the featured category (#feature-wrapper), poi: only show the categories (#category-wrapper), both: show both feature and category divs
  	mapStyle : 'road', // Style of Map { 'road', 'satellite', 'hybrid', 'terrain' }
	disableDefaultUI: false, // google only
	mapTypeControl: true, // use controls
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
	printFeatureFirst: true, // use for mapType 'both' if you want the feature-wrapper div before the category-wrapper in the markup
	activeCatOnly: true, // is true, when a cetegory clicked, close other categories and clear pins
	showPoiID: true, // show id in list in addition to name
	showPinID: true, // show id on pin marker
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
	resetViewOnCategoryChange: true,
	infoBoxOptions: {
		alignBottom:false, // false sets pin at top left corner, true at bottom left corner
		disableAutoPan: false,
		maxWidth: 0, // set at ) for no maxWidth
		//pixelOffset: new google.maps.Size(10, -65), // (0, 0) is at pin bottom - works in conjunction with alignBottom setting - increasing first number moves right, increasing second number moves down. Do not include px!
		horizontalOffset: 10,
		verticalOffset: -65,
		zIndex: null,
		infoBoxClass: 'window-feature',
		boxStyle: { 
			
				//must include at least a width - css will act only on the div inside of this one
				//if you have a side beak, include its width in the boxStyle.width
				//add beak to boxStyle.background - position in conjunction with infoBoxOptions alignBottom and pixelOffset
				//deal with beak width/height (depending on whether beak is top/right/left/bottom) in css .infoWindowContent margin
			
			background: 'url('+RELPATH+'images/map/beak.png) no-repeat 0 20px', // if you wish to seperate, need to use backgroundImage, not background-image etc.
			width: '330px' // width is mandatory! cannot be overriden by css file to increase
		},
		closeBoxMargin: '2px', // standard css top right bottom left format
		closeBoxURL: 'http://www.google.com/intl/en_us/mapfiles/close.gif', // required - can be defined as a image, or hidden with css ( .infoBox>img { visibility:hidden; } ) to use the one in the infoBox html
		//infoBoxClearance: new google.maps.Size(25, 65), // limit at the sides of the map, to make sure map pans if window opens that would be outside - 25,25 good default - may need to adjust if pixelOffset has negative value(s)
		horizontalClearance: 25,
		verticalClearance: 65,
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

// bing

	dataSource : '/poi',
	mapType	: 'both', // Plugin map type { 'feature', 'poi', or 'both' }
  	mapStyle : 'road', // Style of Map { 'aerial', 'auto', 'birdseye', 'collinsBart', 'mercator', 'ordnanceSurvey', 'road': }
	credentials	: 'AlhQ2tbqEMqXpqkcpMtPeOZE3b_LfrLrN9JSvHD3NqG2D7R00kfem-1QhN3L_ki7', // bing only // Credentials for each map. Get from https://www.bingmapsportal.com/
	useSingleIcon : false, 	//bing only (kill this once crud pin ids gone)	// if true, use same icon for all, without letters, numbers etc.
	singleIcon : { //bing only (kill this once crud pin ids gone)
		url : RELPATH+'/images/map/pins/pin-specific-1.png', // path to uniform icon
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
	zoomedView: {
		type: Microsoft.Maps.MapTypeId.birdseye,
		level: 18
	},
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
		//height : 250,			// set for initial positioning
		top : 10, 				// # of px open infoBox should be below controls
		horizontalOffset : 0,	// increasing # moves to right
		verticalOffset: 0,		// increasing # moves up; note, top: may be more precise when boxTiedToPin is false
		boxTiedToPin: false,	// true moves map with box to keep tied together (beak positioning etc.)
		displayCatIcons: true	// if true, generates list to display category icons
	},
	// additions -  not all fully implemented / tested
	closeAllWindows	: true,	// true closes all pinInfobox on click of another whatever
	closeFeatureWindows	: true, 			//true close all pinInfobox when another feature item clicked
	closeCatWindows	: true 	//true close all pinInfobox when another category item clicked


*/
});