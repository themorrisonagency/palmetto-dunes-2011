jQuery(function($){
	$('form').initializeForm({
		required : 'Erforderlich',
		roomTotal : 'Zimmer Insgesamt'
	});
	$('.validate').formValidate({
		errorStart: 'Das ',
		errorEnd: ' folgende Feld erfordert einen korrekten Wert'
	}); 
	$('.package-expandable').expand({
		openText : 'Einzelheiten ansehen',
		closeText : 'Einzelheiten verstecken'
	});
	$('.vcalendar').eventPreview();
	if (typeof jQuery.prototype.msnMap != 'undefined') {	   
		$('#map').msnMap();
		$.msnMap._regional['de'] = {
			moreinfo : 'More Info',
			showMiniMap : 'Show Mini Map',
			hideMiniMap : 'Hide Mini Map',
			addToPlanner : 'Add to Route Planner',
			removePlanner : 'Remove from Route Planner',
			centerPoint : 'Center point on map',
			hidePoint : 'Hide point on map',
			showPoint : 'Show point on map',
			hideSection : 'Hide Section',
			showSection : 'Show Section',
			custom : 'Custom',
			myRoute : 'My Route',
			viewPrint : 'View Printable Directions',
			printDirections : 'Print'
		};
		$.msnMap.setDefaults($.msnMap._regional['de']);
	}
});