jQuery(function($){
	$('form').initializeForm({
		required : 'Требуемый',
		roomTotal : 'Всего комнат'
	});
	$('.validate').formValidate({
		errorStart: 'Тот',
		errorEnd: 'следующие области требует правильного значения'
	}); 
	$('.package-expandable').expand({
		openText : 'См. подробнее',
		closeText : 'Скрыть подробности'
	});
	$('.vcalendar').eventPreview();
	if (typeof jQuery.prototype.msnMap != 'undefined') {	   
		$('#map').msnMap();
		$.msnMap._regional['ru'] = {
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
		$.msnMap.setDefaults($.msnMap._regional['ru']);
	}
});
