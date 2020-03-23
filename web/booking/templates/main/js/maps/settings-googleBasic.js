var settings = ''; // general map settings 
var infoBoxOptions = ''; // Google Custom InfoBox plugin settings
var styles = []; // for customiztion of map canvas/colors - http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html
/*
	xml: root-relative path to georss XML file
	printPoints: true shows the list of points when a category is clicked.  
	showCat: will show non feature category when the map loads.  Can be a specific cateogory or set to "all" to show all categories.
	hover: if set to true non feature points will show infowindow on hover
	zoom: default zoom when map loads
	centerPoint: center point when map loads.  This will need to be the ID of the element

	mapTypeId: corresponds to Google's google.maps.MapTypeId for initial view. valid choices are:
		google.maps.MapTypeId.ROADMAP
		google.maps.MapTypeId.SATELLITE
		google.maps.MapTypeId.HYBRID
		google.maps.MapTypeId.TERRAIN
	disableDefaultUI: if true, the default zoom control and the map type chooser will not be displayed
	mapTypeControl: if true, the control positioned in the upper right corner of the map from which you can choose what map type to show will display - default is true
	mapTypeControlOptions: {
		style: google.maps.MapTypeControlStyle.xxxxx
	}  where xxxxx is DEFAULT, HORIZONTAL_BAR or DROPDOWN_MENU; note curly brackets are required, to allow for future mapTypeControlOptions
	navigationControl: true displays the navigation control
	navigationControlOptions: {
		style: google.maps.NavigationControlStyle.xxxxx
	} where xxxxx is DEFAULT, SMALL or ZOOM_PAN; DEFAULT will vary based on map size and other factors
	scaleControl: true will show the distance scale in lower left of map
	disableDefaultUI: true disables default UI entirely - no reason to include unless you want to display a static map with no controls or pan/zoom capability

	further map controls customization options may be found at https://developers.google.com/maps/documentation/javascript/controls

	infoBoxOptions {} needs a width at bare minimum; styling of child elements can be handled in css file; note that this will not expand if content wider
		alignBottom: if false, infoBox will be positioned so the pin 'point' (bottom center of image) is at top left corner
						true, will be positioned so the pin 'point' is at bottom left corner
*/

settings = {
	mapTypeId: google.maps.MapTypeId.ROADMAP,
	disableDefaultUI: false,
	mapTypeControl: true,
	mapTypeControlOptions: {
		style: google.maps.MapTypeControlStyle.DEFAULT
	},
	navigationControl: true,
	navigationControlOptions: {
		style: google.maps.NavigationControlStyle.DEFAULT
	},
	scaleControl: true,
	dataSource: ((typeof MAPDATA !== "undefined") ? MAPDATA : '/poi'),
	printPoints: true,
	featuredCategory: 'partners',
	activeCatOnly: true,
	includeCategoryWrapper: false,
	hover: false,
	zoom: 11,
	centerPoint: 1,
	mapType: 'feature',
	disableShadows: false,
	resetViewOnCategoryChange: true,
	infoBoxOptions: {
		alignBottom:true,
		disableAutoPan: false,
		maxWidth: 0,
		pixelOffset: new google.maps.Size(0, 0), // 0,0 is bottom left of window at bottom center of pin increasing first number moves right, increasing second number moves down
		zIndex: null,
		infoBoxClass: 'window-feature',
		boxStyle: {
			background: '#fff',
			width: '330px',
			border: '3px solid #444'
		},
		closeBoxMargin: '2px',
		closeBoxURL: 'http://www.google.com/intl/en_us/mapfiles/close.gif',
		infoBoxClearance: new google.maps.Size(25, 25),
		isHidden: false,
		usePlacesData: false, // if true, will pull data from google places if present //not fully implemented
		pane: 'floatPane',
		enableEventPropagation: false
	}
};
$(function() {						   
	GoogleMap('mapDiv',settings);
});

