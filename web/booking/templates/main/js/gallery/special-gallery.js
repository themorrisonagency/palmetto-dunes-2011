$(document).ready(function() {
	$('.embed-gallery').each(function(){
		var el = $(this);
		$.embed('golf', '/assets/xml/gallery-no-video.xml', el.attr('id'));
	});	
});


$(function(){	
	$('.one-cat').click(function(e){
		e.preventDefault();
		$.onecat('activities', '/assets/xml/gallery-no-video.xml', 'fullscreen-example');
	});

	$('.cat-lady').click(function(e){ 
		e.preventDefault();
		$.catlady('/assets/xml/gallery-no-video.xml', 'fullscreen-example');
	});
	
	$('.cat-lady-videos').click(function(e){ 
		e.preventDefault();
		$.catlady_videos('/assets/xml/gallery.xml', 'fullscreen-example');
	});
});