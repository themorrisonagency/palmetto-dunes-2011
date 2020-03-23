jQuery(function($){
	$('form').initializeForm({
		required : 'Nécessaire',
		roomTotal : 'Nombre total de chambres'
	});
	$('.validate').formValidate({
		errorStart: 'Que',
		errorEnd: 'Les domaines suivants exigent la valeur correcte'
	}); 
	$('.package-expandable').expand({
		openText : 'Voir les détails',
		closeText : 'Masquer les détails'
	});
	$('.vcalendar').eventPreview();
	if (typeof jQuery.prototype.msnMap != 'undefined') {	   
		$('#map').msnMap();
		$.msnMap._regional['fr'] = {
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
		$.msnMap.setDefaults($.msnMap._regional['fr']);
	}
});
