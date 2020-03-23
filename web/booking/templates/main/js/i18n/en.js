jQuery(function($){
	$('form').initializeForm();
	$('.validate').formValidate(); 
	$('.vcalendar').eventPreview();
	$('.post').postPreview();
	$('.package').packagePreview();
	//$('.ajax-calendar').ajaxCalendar();
	$('.share-link').initializeSharing();
	$('.follow-link').initializeFollowing();
	if (typeof jQuery.prototype.msnMap != 'undefined') {	   
		//$('#map').msnMap({simpleMap:true});
	}
});