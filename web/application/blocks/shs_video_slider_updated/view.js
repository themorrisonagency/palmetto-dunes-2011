$(document).ready(function(){
	var width = $(window).width();
	if (width <= 640) {
		$('.contentHeaderVid .video').each(function(){
			var small_poster = $('.small-poster').attr('src');
			$('video',$(this)).attr('poster',small_poster);
		});
	}
	$(window).resize(function(){
		var width = $(window).width();
		if (width <= 640) {
			$('.contentHeaderVid .video').each(function(){
				var small_poster = $('.small-poster').attr('src');
				$('video',$(this)).attr('poster',small_poster);
			});
		} else {
			$('.contentHeaderVid .video').each(function(){
				var large_poster = $('.large-poster').attr('src');
				$('video',$(this)).attr('poster',large_poster);
			});			
		}	
	});
});