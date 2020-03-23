jQuery(function($){
	$('form').initializeForm({
		required : '必需的',
		roomTotal : '房間總數'
	});
	$('.validate').formValidate({
		errorStart: '那個',
		errorEnd: '以下領域需要正確的值'
	}); 
	$('.package-expandable').expand({
		openText : '見詳情',
		closeText : '隱藏詳細信息'
	});
	$('.vcalendar').eventPreview();
	if (typeof jQuery.prototype.msnMap != 'undefined') {	   
		$('#map').msnMap();
		$.msnMap._regional['cn'] = {
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
		$.msnMap.setDefaults($.msnMap._regional['cn']);
	}
});
