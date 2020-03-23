jQuery(function($){
	$('form').initializeForm({
		required : '必要な',
		roomTotal : '総客室数'
	});
	$('.validate').formValidate({
		errorStart: 'あの',
		errorEnd: '次の領域は、正しい値が必要です'
	}); 
	$('.package-expandable').expand({
		openText : '詳細を参照してください',
		closeText : '詳細を隠す'
	});
	$('.vcalendar').eventPreview();
	if (typeof jQuery.prototype.msnMap != 'undefined') {	   
		$('#map').msnMap();
		$.msnMap._regional['jp'] = {
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
		$.msnMap.setDefaults($.msnMap._regional['jp']);
	}
});
