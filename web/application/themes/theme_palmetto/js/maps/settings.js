
$(function() {
  var mapSettings = {
    mapVendor: MAPVENDOR,
    mapModal: false,

    google : {
      dataSource: ((typeof MAPDATA !== "undefined") ? MAPDATA : '/application/themes/theme_palmetto/js/maps/poi.json'), // '/poi' is default - if multiple maps on a site, will need to set for each. must be a standalone file, not one with rendered content

      useGeo: false,
      mapStyle: 'road',
      disableDefaultUI: false,
      mapTypeControl: false,
      mapTypeControlOptions: {
        style: 'default' // horizontal, dropdown, default
      },
      zoomControl: true,
      zoomControlOptions: {
        style: 'default' // small, large, default
      },
      scaleControl: true,
      styledMap: false, // if true, will also need styles settings
      printPoints: true, // true shows the list of points when a category is clicked.
      needsIntl: false, // if true, won't lowercase category class name due to needing to allow any number of 'special' chars
      featuredCategory: 'activities',  // if needsIntl: true, this will need to be whatever the category id would be,
                      // ie the category name with spaces and any & removed, so French/Spanish/Italian might be capitalized,
                      // where English wouldn't be
      activeCatOnly: true, // is true, when a cetegory clicked, close other categories and clear pins
      showPoiID: true, // show id in list in addition to name
      showPinID: false, // show id on pin marker
      pinLabelType: 'list', // 'poi': unique for poi, 'list': position in current list
      pinLabelChar: 'number', // 'number', 'letter': should not be used with pinLabelType 'poi'
                  // so as not to create issues with more than 26 points of interest
                  // 'letter' shouldn't be used with needsIntl: true for non-english standard alphabets
      hover: false,
      zoom: 15,
      changeViewOnZoom: false, // if true, the map type will change when clicking on a point, or on user zoom
      zoomedView: {
        type: 'hybrid', // 'road', 'satellite', 'hybrid', 'terrain'
        level: 16
      },
      //centerPoint: 1,
      mapType: 'both', // feature: only show the featured category (#feature-wrapper), poi: only show the categories (#category-wrapper), both: show both feature and category divs
      printFeatureFirst: true, // use for mapType 'both' if you want the feature-wrapper div before the category-wrapper in the markup
      excludeFeaturedCategory: false, // if true, the featured category will not be added to the category wrapper (usually for mapType:both)
      disableShadows: false, // can likely leave false if using richmarker.js
      infoBoxOptions: {
        alignBottom:true, // false sets pin at top left corner, true at bottom left corner
        disableAutoPan: false,
        maxWidth: 0, // set at 0 for no maxWidth
        horizontalOffset: -153,
        verticalOffset: -20,
        zIndex: null,
        infoBoxClass: 'window-feature',
        boxStyle: {
            // must include at least a width - css will act only on the div inside of this one
            // if you have a side beak, include its width in the boxStyle.width
            // add beak to boxStyle.background - position in conjunction with infoBoxOptions alignBottom, horizontalOffset and verticalOffset
            // deal with beak width/height (depending on whether beak is top/right/left/bottom) in css .infoWindowContent margin
          background: 'url('+RELPATH+'images/map/beak.png) no-repeat 0 100%', // if you wish to seperate, need to use backgroundImage, not background-image etc.
          width: '300px' // width is mandatory! cannot be overriden by css file to increase
        },
        closeBoxMargin: '0', // standard css top right bottom left format
        closeBoxURL: RELPATH+'images/map/close.png', // image path required - can be defined as the image you wish to use,
                              // or hidden with css ( .infoBox>img { visibility:hidden; } ) to use the one in the infoBox html
        //infoBoxClearance: new google.maps.Size(25, 65), // limit at the sides of the map, to make sure map pans if window opens that would be outside - 25,25 good default - may need to adjust if pixelOffset has negative value(s)
        horizontalClearance: 25,
        verticalClearance: 65,
        isHidden: false,
        usePlacesData: false, // if true, will pull data from google places if present //not fully implemented
        pane: 'floatPane', // floatPane is 'above' mapPane - best for windows
        enableEventPropagation: false
      },
      infoBoxHtmlOptions: { // sets which attributes from poi json should be included in infoBox
        useImage: true,
        useTitle: true,
        useDescription: true,
        useWebsite: true,
        useAddress: false,
        addDirections: true,
        directionsType: 'link' // link (adds directions link), form (adds form with "from" field)
      },
      // example of google styles settings - generate at http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html
      // if used, styledMap: true must be set in main settings
      mapStyles: {
        styles : [
          {
            featureType: "water",
            elementType: "geometry",
            stylers: [
              { color: "#6096c6" },
              { lightness: -33 }
            ]
          },{
            featureType: "water",
            elementType: "labels.text.fill",
            stylers: [
              { color: "#feffff" }
            ]
          },{
            featureType: "water",
            elementType: "labels.text.stroke",
            stylers: [
              { visibility: "off" }
            ]
          },{
            featureType: "landscape.natural",
            elementType: "geometry.fill",
            stylers: [
              { color: "#d7ad6b" },
              { lightness: -13 },
              { saturation: -43 }
            ]
          },{
            featureType: "landscape.man_made",
            elementType: "geometry.fill",
            stylers: [
              { lightness: 45 },
              { color: "#756e80" },
              { gamma: 3.28 }
            ]
          },{
            featureType: "road",
            elementType: "geometry.stroke",
          },{
          }
        ]
      } // end google options

    } // end mapSettings
  };

  var mapVendor = mapSettings.mapVendor;
  if (mapSettings.mapModal === false) {
    switch(mapVendor) {
      case 'bing':
        try {$('#mapDiv').bingMap(mapSettings.bing);} catch (err) {}
        break;
      case 'google':
        try {$('#mapDiv').googleMap(mapSettings.google);} catch (err) {}
        break;
      default:
        break;
    }
  }

  /*
    below are for reponsive sites, mostly for orientation change on tablet, though also useful if QA is scaling browser to test
    note that maps (both bing and google) tend to "trap" the user, so you should never have the map at full device height if your comp
    has it at full width
    modify the items in skinHeight as needed to ensure the user always has something outside of the map to interact with for page scrolling

    the commented out code was used on a site (Suncadia) where there was a full page map

    window.addEventListener() isn't supported by IE8, but no device with (frequent) portrait/tablet orientation changes uses IE8, so as long
    as if (window.addEventListener) is included, all should be well
  */

  var adjustSkin=function(){
    var winW = 630,
      winH = 460;
    if (document.body && document.body.offsetWidth) {
     winW = document.body.offsetWidth;
     winH = document.body.offsetHeight;
    }
    if (document.compatMode=='CSS1Compat' &&
        document.documentElement &&
        document.documentElement.offsetWidth ) {
     winW = document.documentElement.offsetWidth;
     winH = document.documentElement.offsetHeight;
    }
    if (window.innerWidth && window.innerHeight) {
     winW = window.innerWidth;
     winH = window.innerHeight;
    }

    // var skinHeight,
    //   headerH = $('#header').height(),
    //   breadcrumbH = $('#breadcrumb').height(),
    //   footerH = $('#footer').height(),
    //   footAddressH = $('#footer-address').height();
    // skinHeight = headerH + breadcrumbH + footAddressH;
    // $('#map-wrapper, .MapContainer').height(winH - skinHeight - 5);
    // $('.MapContainer').width(winW - 293);
  }

  adjustSkin();

  if (window.addEventListener) {

    window.addEventListener('orientationchange', function() {
      adjustSkin();
    }, false);

    window.addEventListener('resize', function() {
      adjustSkin();
    }, false);

  }

});