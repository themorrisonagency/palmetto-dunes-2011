$(document).ready(function(){
	var width = $(window).width();
	if (width <= 640) {
		$('.contentHeaderVid .video').each(function(){
			var small_poster = $('.small-poster').attr('src');
			$('video',$(this)).attr('poster',small_poster);
			$('.small-poster', this).show();
			$('.large-poster', this).hide();
		});
	}
	$(window).resize(function(){
		var width = $(window).width();
		if (width <= 640) {
			$('.contentHeaderVid .video').each(function(){
				var small_poster = $('.small-poster').attr('src');
				$('video',$(this)).attr('poster',small_poster);
				$('.small-poster', this).show();
				$('.large-poster', this).hide();				
			});
		} else {
			$('.contentHeaderVid .video').each(function(){
				var large_poster = $('.large-poster').attr('src');
				$('video',$(this)).attr('poster',large_poster);
				$('.small-poster', this).hide();
				$('.large-poster', this).show();				
			});			
		}	
	});
});