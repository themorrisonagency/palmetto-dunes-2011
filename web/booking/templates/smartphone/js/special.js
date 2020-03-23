$(function() {
	// Navigation JS
	$('.nav-primary li a').each(function(){
		$('#nav_sub .nav-secondary').hide();
		var navItem = $(this);
		var secNav = $(this).parent().attr('id');
		navItem.click(function(e){
			if ($('#sub-'+secNav).length > 0) {
				e.preventDefault();
				$('#nav').hide();
				$('#nav_sub .nav-secondary').hide();
				$('#sub-'+secNav).show();
			}
		});
	});
	
	$('.back-main-menu').each(function(){
		$(this).click(function(e){
			e.preventDefault();
			$('#nav_sub .nav-secondary').hide();
			$('#nav').show();
		});
	});
	
	// Form Validation
	$('form').formValidate();
	
	// Special Offers
	$('.package').each(function(){
		var p = $(this);
		$('.package-long').hide();
		$('.read-more a',p).toggle(function(){
			$(this).text('Hide Details');
			$('.package-long',p).slideDown();
		}, function(){
			$(this).text('View Details');
			$('.package-long',p).slideUp();
		});
	});
	
	//Homepage push marketing
	$('#push-items').each(function(){
 		var contents = $('#push-items').html();
		homePushInit(contents);
		$(window).resize(function () {
			homePushInit(contents);
			$('#push-items').width('100%');
		});
	});

	function homePushInit(contents){
		//Reset arrows
		$('#next').removeClass('disabled');
		$('#prev').addClass('disabled');
		//Reset push with original html
		$('#push-items').html('').html(contents).cycle({ 
			fx:     'scrollHorz', 
			speed:  500, 
			timeout: 0, 
			next:   '#next', 
			prev:   '#prev' ,
			nowrap: 1,
			prevNextClick: function (isNext, zeroBasedSlideIndex, slideElement) {
				numItems = document.getElementById('push-items').getElementsByTagName('article').length-1;
				document.getElementById('prev').className = (zeroBasedSlideIndex==0 ? 'cycle-prev disabled' : 'cycle-prev');
				document.getElementById('next').className = (zeroBasedSlideIndex==numItems ? 'cycle-next disabled' : 'cycle-next');
				
			}
		});			
	}});