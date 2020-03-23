var imagesJSON = [],
	categories = [],
	subcategories = [];
	subcatholder = '',
	currentGallery = '',
	videoid =  '',
	imageData = [],
	picked = '',
	whichgallery = '',
	currentGalleryTxt = '',
	gimmeVideos = false,
	onloadCat = '',
	mediatrigger = false,
	isHandheld = {
		Android_Mobile: function() {
			if(navigator.userAgent.match(/Android/i) && navigator.userAgent.match(/Mobile/i))
				return true;
			else
				return false;
		},
		Android_Tablet: function() {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function() {
			return (isHandheld.Android_Mobile() || isHandheld.Android_Tablet() || isHandheld.BlackBerry() || isHandheld.iOS() || isHandheld.Opera() || isHandheld.Windows());
		}
	};


/**
*** @preserve Galleria Fozzie Theme 2012
**/

// defaults and globals
Galleria.requires(1.25, 'This version of Fozzie theme requires Galleria 1.2.5 or later');

(function($) {

Galleria.addTheme({
    name: 'Fozzie',
    author: 'Jimmy Morris',
	
    defaults: {
        transition: 'none',
        thumbCrop:  true,
		_client_name: 'Suncadia Resort'
		
	},
    init: function(options) {
		var gallery = this;
		//console.log(isHandheld.any() .' '. isHandheld.Android_Mobile());
		this.addIdleState( this.get('image-nav-left'), { left: -34 });
		this.addIdleState( this.get('image-nav-right'), { right:-34 });
		if(isHandheld) {
			//$('body').addClass('mobile');				
			// prevent the mobile devices to disable y-axis scrolling when photo-gallery is active/visiable.
			if($('.galleria').parent().hasClass('fullscreen'))	{
				$(window).bind('touchmove', function(e) {
					var move = window.event;
					if(move.touches[0].pageY > 0)
						e.preventDefault();
				});
			}
		}
			 
		// add some elements
		this.addElement('thumbnails-wrapper');
		this.appendChild('container', 'thumbnails-wrapper');
		this.appendChild('thumbnails-wrapper','thumbnails-container');
		
        this.addElement('thumbnails-tab');
        this.prependChild('thumbnails-wrapper','thumbnails-tab');
		this.appendChild('thumbnails-tab','<em class=alt>More Photos</em>');
		
		//cache some stuff
		var thumbstab = $('.galleria-thumbnails-tab'),
			thumbsholder = $('.galleria-thumbnails-container'),
			next_prev = $('.galleria-thumb-nav-left, .galleria-thumb-nav-right'),
			info = this.$('info'),
            touch = Galleria.TOUCH,
            click = touch ? 'touchstart' : 'click',
			landscape;
				
		thumbstab.toggle(
			function(e){
				e.preventDefault();
				$(thumbstab).addClass('active'); //33px
				$(next_prev).animate({top: '72px'});
				$(thumbsholder).animate({ 'margin-top': '72px' });
				//console.log('info::'+info)
				$(info).animate({ bottom: '28px' });
			},
			function(e){
				e.preventDefault();
				$(thumbstab).removeClass('active');
				$(next_prev).animate({top: '0'});
				$(thumbsholder).animate({ 'margin-top': '0px' });
				//console.log('info2::'+info)
				$(info).animate({ bottom: '100px' });
			}
		);	
		   
		// show loader & counter with opacity
        this.$('loader,counter').show().css('opacity', 0.3);

        // bind some stuff
		/*this.attachKeyboard({
			right: this.next,
			left: this.prev,
			up: function() {
				//console.log( 'up key pressed' )
			}
		});*/

		
		this.bind('image', function(e) {
			if($('.media-gallery').hasClass('first-load')) {
				this.show(onloadIndex);
				$('.media-gallery').removeClass('first-load');
			}
			var imageW = e.imageTarget.width;
			var imageH = e.imageTarget.height;
			
			if (imageH > imageW) {
				landscape = 'height'; // for portrait
			} else if (imageH < imageW) {
				landscape = true; // for landscape
			}
			this.setOptions({ imageCrop: landscape }).refreshImage();
			//console.log('images image');
		});

		this.bind('thumbnail', function(e) {
			
            if (! touch ) {
                // fade thumbnails
                $(e.thumbTarget).parent().hover(function() {
                    $(this).not('.active').children('img').stop().fadeTo(100, 1);
                }, function() {
                    $(this).not('.active').children('img').stop().fadeTo(400, 0.7);
                });
            } else {
                $(e.thumbTarget).css('opacity', this.getIndex() ? 1 : 0.7);
            }

			this.updateCarousel();

			/*
			var desc = this.getData( e.index ).title + '<br />' + this.getData( e.index ).description;
			if (desc) {
				this.bindTooltip( e.thumbTarget, '<p>'+desc+'</p>' );
			}*/
			//console.log('images thumbnail');
        });
		
		this.bind('rescale', function () {
			var cssSet = { 
				'width' : Math.min( this.$('stage').width() ),
				'height' : Math.min( this.$('stage').height() )
			};
			
			var thumbnailSet = {
				'width' : Math.min( this.$('stage').width() ),
				'bottom' : '28px'
			};
			this.$('container').css(cssSet);
			if((Math.min( $('.media-gallery').width()) > 768 && !($('.media-gallery').height() < 320)) || $('.media-gallery').parent().hasClass('embed')){
				this.$('thumbnails-wrapper').show();
				this.removeIdleState('.media-gallery .menu-bar');
				this.$('thumbnails-wrapper').css(thumbnailSet);
				$('.gallery-footer').css('bottom', '0');
			} else {
				this.$('thumbnails-wrapper').hide();
				$('.gallery-footer').css('bottom', '-47px');
			}
		});
		
        this.bind('loadstart', function(e) {
			if (!e.cached) {
                this.$('loader').show().fadeTo(200, 0.4);
            }
			$(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity', 0.7);

			var video_id = this.getData(e.index).video;
			//console.log(video_id);
			
			if(video_id != undefined && video_id.length > 0 ) {
				this.$('youtube').show();
			} else {
				this.$('youtube').hide();
			}
			//this.$('info').toggle( this.hasInfo() );
           //console.log('images loadstate');
        });		
		
        this.bind('loadfinish', function(e) {
			this.$('loader').fadeOut(200);
			this.$('images').show();
			
			this.rescale();
			
			var current_title = encodeURIComponent(this.getData( e.index ).title);
				current_desc = encodeURIComponent(this.getData(e.index).description),
				full_desc = current_title + ' ' + current_desc + ' Via ' + options._client_name,
				current_url = encodeURIComponent(window.location),
				video_id = this.getData(e.index).video,
				current_media = this.getData(e.index).image;
						
			if(video_id != undefined && video_id.length > 0 ) {
				this.$('youtube').show();
			}
			//build the share links
												
			if(video_id)
				$('.gallery-facebook').attr('href','http://www.facebook.com/sharer.php?s=100&p[title]='+encodeURIComponent(current_title)+'&p[url]=http://youtu.be/'+video_id+'&p[images][0]='+current_media+'&p[summary]='+full_desc+' ('+current_url+')');
			else
				$('.gallery-facebook').attr('href','http://www.facebook.com/sharer.php?s=100&p[images][0]='+current_media+'&p[title]='+current_title+'&p[url]='+current_media+'&p[summary]='+full_desc);
			
			$('.gallery-twitter').attr('href','http://twitter.com/share?text=' + full_desc +'&url='+ current_media);
			$('.gallery-pinterest').attr('href','http://pinterest.com/pin/create/button/?url='+current_media+'&media='+current_media+'&description='+full_desc);
			
			
			/*console.log(options._client_name);
			console.log(current_title);
			console.log(current_desc);
			console.log(full_desc);
			console.log(current_url);
			console.log(video_id);
			console.log(current_media);
			console.log('images loadfinish');*/
			
		});
		
 		$(window).resize(function() { 
			if($('.fullscreen .galleria').data('galleria')) {
				$('.fullscreen .galleria').data('galleria').rescale();
				
			}
		});

    }
});

$(function(){
	
	$('.media-gallery .gallery-close').click(function(e){
		e.preventDefault();
		var el = $(this).closest('.fullscreen');
		$(window).unbind('touchmove');
		el.find('.photo-video').removeClass('videos').addClass('photos').hide()
		el.find('.galleria,.subgalleries dt,.choose-gallery,.gallery-back').hide();
		el.find('.galleria-youtube').html('');
		el.hide();
	});


	$('.dropdown dt a').toggle(
		function(e){
			e.preventDefault();				
			$(this).parent().parent().addClass('active');
			$(this).parent().parent().find('dd ul').slideDown('fast');
			whichgallery = $(this).parent().parent().attr('rel');
		},
		function(e){
			e.preventDefault();
			$(this).parent().parent().removeClass('active');
			$(this).parent().parent().find('dd ul').slideUp('fast');
			whichgallery = $(this).parent().parent().attr('rel');
		}
	);
	
	$('.dropdown dd ul li a').click(function(e){
		e.preventDefault();
		currentGallery = $(this).attr('rel');
		var el = $(this),
			picked = el.text();
		el.parent().parent().slideUp('fast').parent().parent().find('dt a').text(picked).parent().parent().removeClass('active');
		el.parents('.dropdown dt a').unbind();
		el.parents('.dropdown dt a').toggle(
			function(e){
				e.preventDefault();
				$(this).parent().parent().addClass('active');
				$(this).parent().parent().find('dd ul').slideDown('fast');
				whichgallery = $(this).parent().parent().attr('gallery-launch');
			}, 
			function(e){
				e.preventDefault();
				$(this).parent().parent().removeClass('active');
				$(this).parent().parent().find('dd ul').slideUp('fast');
				whichgallery = $(this).parent().parent().attr('gallery-launch');
			}
		); 
		$.loadGalleria(currentGallery, xmlfilename, el.closest('.media-gallery').attr('id'));
	});
		
	$(".gallery-picker ul li a").live("click", function(e){ 
		e.preventDefault();
		currentGallery = $(this).attr('class');
		currentGalleryTxt = $(this).text();
		console.log(currentGallery);
		console.log(xmlfilename);
		var el = $(this).closest('.media-gallery');
		el.find('.photo-video').show();
		el.find('.choose-gallery .galleries dt a').text(currentGalleryTxt);
		el.find('.gallery-picker').hide();
		el.find('.galleria').show();
		el.find('.choose-gallery').show();
		el.find('.media-gallery .menu-bar').show();
		if(mediatrigger)
			el.find('.photo-video').show().addClass('photos');
		else
			el.find('.photo-video').hide();
		el.find('.gallery-back').show();
		el.find('.gallery-share').show();
		if($(window).width() <= 768 || $(window).height() <= 320 || isHandheld.any() || isHandheld.Android_Mobile()) {
			el.find('.media-gallery .menu-bar').show();
			el.find('.photo-video').show();
			mediatrigger = false;
		}
		$.loadGalleria(currentGallery, xmlfilename, el.attr('id'));
	});
	
	$('.gallery-back').click(function(e){
		e.preventDefault();
		var el = $(this).closest('.media-gallery');
		el.find('.galleria-youtube').html('');
		el.find('.galleria').hide();
		el.find('.subgalleries dt').hide();
		el.find('.choose-gallery').hide();
		el.find('.photo-video').removeClass('videos').addClass('photos').hide();
		el.find('.gallery-share').hide();		
		el.find('dl.galleries dd ul').hide();
		$(this).hide();
		if($(window).width() <= 768 || $(window).height() <= 320 || isHandheld.any() || isHandheld.Android_Mobile()) {
			$('.gallery-footer').css({'bottom': '-40px'});
			//$('.media-gallery .menu-bar').hide();
		}		
		el.find('.gallery-picker').show();
		gimmeVideos = false;
	});
	
	$('.view-videos').live("click", function(e){ 
		e.preventDefault();
		var el = $(this).closest('.media-gallery');
		if($(this).parent().hasClass('photos')){
			$(this).parent().removeClass('photos').addClass('videos');
			el.find('.galleria-youtube').html('');
		}
		gimmeVideos = true;
		$.loadGalleria(currentGallery, xmlfilename, el.attr('id'));
	});
	
	$('.view-photos').live("click", function(e){ 
		e.preventDefault();
		var el = $(this).closest('.media-gallery');
		if($(this).parent().hasClass('videos')){
			el.find('.galleria-youtube').hide();
			$(this).parent().removeClass('videos').addClass('photos');
		}
		gimmeVideos = false;
		$.loadGalleria(currentGallery, xmlfilename, el.attr('id'));
	});
	
	$('html').click(function(){
		$('.dropdown dd ul').slideUp('fast');
		$('.dropdown').removeClass('active');
		$('.dropdown dt a').unbind();
		$('.dropdown dt a').toggle(
			function(e){
				e.preventDefault();
				$(this).parent().parent().addClass('active');
				$(this).parent().parent().find('dd ul').slideDown('fast');
				whichgallery = $(this).parent().parent().attr('rel');
			}, 
			function(e){
				e.preventDefault();
				$(this).parent().parent().removeClass('active');
				$(this).parent().parent().find('dd ul').slideUp('fast');
				whichgallery = $(this).parent().parent().attr('rel');				
			}
		);		
	});
	
	$('.share-this-on').toggle(
		function(e){ //show
			e.preventDefault();				
			if($(window).width() <= 768 || $(window).height() <= 320 || isHandheld.any() || isHandheld.Android_Mobile()) {
				$(this).parent().parent().animate({bottom: 0}, 'fast');
			}
		},
		function(e){ //hide
			e.preventDefault();
			if($(window).width() <= 768 || $(window).height() <= 320 || isHandheld.any() || isHandheld.Android_Mobile()) {
				$(this).parent().parent().animate({bottom: -47}, 'fast');
			}
	});

	$('.gallery-share .gallery-share-icon').click(function(e){
		var url = $(this).attr('href');
        
		if($(window).width() <= 768 || $(window).height() <= 320 || isHandheld.any() || isHandheld.Android_Mobile()) {
			$(this).parent().parent().animate({bottom: '-47'}, 'slow');
		} else {
			e.preventDefault();
			window.open(url, 'share', 'width=652,height=352,scrollbars=1,toolbar=0,location=0,status=0');	
		}
	});		

});

$(window).resize(function() { 
	if($('.media-gallery .galleria').data('galleria')) {
		Galleria.get(0).rescale($('.media-gallery').width(),$('.media-gallery').height()); //rescales the image only
	}
});

function animateItems(what2do, timer, width){
	
	if(what2do == 'hide it'){
		$('.galleria-image-nav-left').animate({ 'left': '-34px' }, 100);
		$('.galleria-image-nav-right').animate({ 'right': '-34px' }, 100);
		if(width <= 768)
			$('.menu-bar').animate({ 'top': '-42px' }, 100);
	} else {
		$('.galleria-image-nav-left').animate({ 'left': '0' }, 100);
		$('.galleria-image-nav-right').animate({ 'right': '0' }, 100);
		if(width <= 768)
			$('.menu-bar').animate({ 'top': '0' }, 100);
	}

}

// Single Category, no video
$.onecat = function(cat, filename, el){
	mediatrigger = false;
	$('.media-gallery').show();
	$('.gallery-picker').hide();
	$('.gallery-footer .gallery-share').show();
	$('.galleria').show();
	$.loadGalleria(cat, filename, el);
};

$.embed = function(cat, filename, galleryid){	
	mediatrigger = false;
	$('#'+galleryid).find('.media-gallery').show();
	$('.gallery-picker, .galleria-info').hide();
	$('.gallery-footer, .gallery-share').show();
	$.loadGalleria(cat, filename, galleryid);
};

// Open gallery with specific category
$.specific_cat = function(cat, gotvideos, filename, galleryid){
	var el = $('#'+galleryid),
		textlabel = '';
	mediatrigger = gotvideos;
	xmlfilename = filename;
	currentGallery = cat;
	el.find('.media-gallery').show();
	el.find('.gallery-picker').hide();
	el.find('.choose-gallery .galleries dd a').each(function(){
		if(cat.toLowerCase() == $(this).attr('rel')) {
			textlabel = $(this).text();
		}
	});
	el.find('.choose-gallery .galleries dt a').text(textlabel);
	el.find('.gallery-footer .gallery-share,.choose-gallery,.gallery-back, .photo-video').show();
	el.show();
	$.loadGalleria(cat, filename, galleryid);
};

// Multi-category, no video
$.catlady = function(filename, galleryid){
	var el = $('#'+galleryid);
	mediatrigger = false;
	xmlfilename = filename;
	$('.media-gallery').show();
	$('.gallery-picker').show();
	el.show();
};

// Multi-category, photo and video
$.catlady_videos = function(filename, galleryid){
	var el = $('#'+galleryid);
	mediatrigger = true;
	xmlfilename = filename;	
	$('.media-gallery').show();
	$('.gallery-picker').show();
	el.show();
};

$.fullscreenMobile = function() {  //disables up and down scrolling on touch devices when lightbox version of the gallery is visible.
	if((isHandheld.any() || isHandheld.Android_Mobile()) && $('.fullscreen').is(':visible')) {
		$('html, body').css('overflow', 'hidden');
	}
};

$.loadGalleria = function(cat, file, selectorid){
	//console.log("picked: " + cat);
	var newData = [],
		imagesObj = [],
		categories = [],
		counter = 0,
		catholder = "",
		el = $('#'+selectorid);
	
	//console.log(cat+' -- '+file);
	
	$.get(file, function(xml){
		var imageObj = $.xml2json(xml);
		//console.log(imageObj);
		$.each(imageObj.channel.item, function(i, image){
			catholder = image.category.toLowerCase(); // this is the current images assigned categories
			categories = catholder.split(',');
			//console.log(catholder);
			for(var i in categories) {
				if(cat == categories[i]) {
					videoid = image.content.id;
					if(!mediatrigger){
						newData.push( // push all data in to display the gallery
							{
								image: image.content.url,
								thumb: image.thumbnail.url,
								title: image.title,
								description: image.description,
								video: videoid
							}		
						);
						counter++;
					}
					else if ((!videoid == undefined || videoid.length > 0) && gimmeVideos) {
						newData.push( // push just videos
							{
								image: image.content.url,
								thumb: image.thumbnail.url,
								title: image.title,
								description: image.description,
								video: videoid
							}		
						);
						counter++;
					} else if((!videoid.length > 0) && (!gimmeVideos)) {
						newData.push( // push just photos
							{
								image: image.content.url,
								thumb: image.thumbnail.url,
								title: image.title,
								description: image.description
							}
						);
						counter++;
					}
				}
			}
		});
		if(counter > 0) {
			if($('.galleria-info, .galleria-thumbnails-wrapper, .galleria-image-nav').css('display', 'none')) {
				$('.galleria-info, .galleria-thumbnails-wrapper, .galleria-image-nav').show();
			}
			//console.log(el);
			if(el != undefined){
				el.find('.galleria').galleria({
					dataSource: newData,
					thumbCrop: true,
					swipe: true,
					fullscreenDoubleTap: false
				});
			}
		} else {
			el.find('.galleria').html('<div class="no-stuff"><p><span>Sorry</span><br />NO VISUALS</p></div>');
			el.find('.galleria-info, .galleria-thumbnails-wrapper, .galleria-image-nav').hide();
		}
		
	}, "xml");
};

}(jQuery));
