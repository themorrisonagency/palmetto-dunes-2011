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
			return navigator.userAgent.match(/iPhone|iPod/i);
		},
		iPad: function() {
			return navigator.userAgent.match(/iPad/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
		tablet: function() {
			return (isHandheld.iPad() || isHandheld.Android_Tablet);
		},
		mobile: function() {
			return (isHandheld.Android_Mobile() || isHandheld.BlackBerry() || isHandheld.iOS()  || isHandheld.iPad() || isHandheld.Opera() || isHandheld.Windows());
		},
		any: function() {
			return (isHandheld.Android_Mobile() || isHandheld.Android_Tablet() || isHandheld.BlackBerry() || isHandheld.iOS()  || isHandheld.iPad() || isHandheld.Opera() || isHandheld.Windows());
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
		_client_name: 'Sabre Hospitality Solutions'
		
	},
    init: function(options) {
		var gallery = this;
		//console.log(isHandheld.any() .' '. isHandheld.Android_Mobile());
		
		if(isHandheld){
			this.removeIdleState( this.get('image-nav-left'));
			this.removeIdleState( this.get('image-nav-right'));
			this.addIdleState( this.get('image-nav-left'), { display: 'none' });
			this.addIdleState( this.get('image-nav-right'), { display: 'none' });		
		} else {
			this.removeIdleState( this.get('image-nav-left'));
			this.removeIdleState( this.get('image-nav-right'));
			this.addIdleState( this.get('image-nav-left'), { left: -34 });
			this.addIdleState( this.get('image-nav-right'), { right:-34 });		
		}
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
			crop;
				
		thumbstab.click(function(e){
			e.preventDefault();
			if (thumbstab.hasClass('active')) {
				$(thumbstab).removeClass('active');
				$(next_prev).animate({top: '0'});
				$(thumbsholder).animate({ 'margin-top': '0px' });
				//console.log('info2::'+info)
				$(info).animate({ bottom: '100px' });
			} else {
				e.preventDefault();
				$(thumbstab).addClass('active'); //33px
				$(next_prev).animate({top: '72px'});
				$(thumbsholder).animate({ 'margin-top': '72px' });
				//console.log('info::'+info)
				$(info).animate({ bottom: '28px' });
			}
		});	
		   
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
			if(gallery._stageWidth < 768 || gallery._stageHeight < 320 || isHandheld.mobile()){
				landscape = e.imageTarget.width >= e.imageTarget.height ? 'width' : 'height';
				gallery.setOptions( 'imageCrop', landscape ).refreshImage();
			} else {
				var imageW = e.imageTarget.width,
					imageH = e.imageTarget.height;
				
				if (imageH > imageW) {
					crop = 'height'; // for portrait
				} else if (imageH < imageW) {
					crop = 'true'; // for landscape
				}
				gallery.setOptions({ imageCrop: crop }).refreshImage();
			}

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
			
			$('.galleria-images img').each(function(){
				var current = gallery.getData(gallery.getIndex())
				if($(this).attr('src') == current.image) {
					if(gallery._stageWidth < 768 || gallery._stageHeight < 320 || isHandheld.mobile()){
						landscape = $(this).width() >= $(this).height() ? 'width' : 'height';
						gallery.setOptions( 'imageCrop', landscape ).refreshImage();
					} else {
						var imageW = $(this).width(),
							imageH = $(this).height();
						
						if (imageH > imageW) {
							crop = 'height'; // for portrait
						} else if (imageH < imageW) {
							crop = 'true'; // for landscape
						}
						gallery.setOptions({ imageCrop: crop }).refreshImage();
					}
				}
			});			
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
			
			if(gallery._stageWidth < 768 || gallery._stageHeight < 320 || isHandheld.mobile()){
				landscape = e.imageTarget.width >= e.imageTarget.height ? 'width' : 'height';
				gallery.setOptions( 'imageCrop', landscape ).refreshImage();
			} else {
				var imageW = e.imageTarget.width,
					imageH = e.imageTarget.height;
				
				if (imageH > imageW) {
					crop = 'height'; // for portrait
				} else if (imageH < imageW) {
					crop = 'true'; // for landscape
				}
				gallery.setOptions({ imageCrop: crop }).refreshImage();
			}

			//build the share links
												
			if(video_id)
			$('.gallery-facebook').attr('href','http://www.facebook.com/sharer.php?s=100&m2w&v=1&p[title]='+encodeURIComponent(current_title)+'&p[url]=http://youtu.be/'+video_id+'&p[images][0]='+current_media+'&p[summary]='+full_desc+' ('+current_url+')');
			else
			$('.gallery-facebook').attr('href','http://www.facebook.com/sharer.php?s=100&m2w&v=1&p[images][0]='+current_media+'&p[title]='+current_title+'&p[url]='+current_media+'&p[summary]='+full_desc);
			
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
		el.find('.galleria,.subgalleries dt,.choose-gallery,.gallery-back,.gallery-share').hide();
		el.find('.galleria').html('');
		el.hide();
		$('html, body').css('overflow', 'auto');
	});


	$('.dropdown dt a').click(function(e){
		e.preventDefault();
		if ($(this).parent().parent().hasClass('active')) {
			$(this).parent().parent().removeClass('active');
			$(this).parent().parent().find('dd ul').slideUp('fast');
			whichgallery = $(this).parent().parent().attr('rel');
		} else {
			$(this).parent().parent().addClass('active');
			$(this).parent().parent().find('dd ul').slideDown('fast');
			whichgallery = $(this).parent().parent().attr('rel');
		}
	});
	
	$('.dropdown dd ul li a').click(function(e){
		e.preventDefault();
		currentGallery = $(this).attr('rel');
		var el = $(this),
			picked = el.text();
		el.parent().parent().slideUp('fast').parent().parent().find('dt a').text(picked).parent().parent().removeClass('active');
		el.parents('.dropdown dt a').unbind();
		el.parents('.dropdown dt a').click(function(e) {
			e.preventDefault();
			if ($(this).parent().parent().hasClass('active')) {
				$(this).parent().parent().removeClass('active');
				$(this).parent().parent().find('dd ul').slideUp('fast');
				whichgallery = $(this).parent().parent().attr('gallery-launch');
			} else {
				$(this).parent().parent().addClass('active');
				$(this).parent().parent().find('dd ul').slideDown('fast');
				whichgallery = $(this).parent().parent().attr('gallery-launch');
			}
		}); 
		$.loadGalleria(currentGallery, xmlfilename, el.closest('.media-gallery').attr('id'));
	});
		
	$(".gallery-picker ul li").on("click", "a", function(e){ 
		e.preventDefault();
		currentGallery = $(this).attr('class');
		currentGalleryTxt = $(this).text();
		//console.log(currentGallery);
		//console.log(xmlfilename);
		var el = $(this).closest('.media-gallery');
		el.find('.choose-gallery .galleries dt a').text(currentGalleryTxt);
		el.find('.gallery-picker').hide();
		el.find('.galleria').show();
		el.find('.choose-gallery').show();
		el.find('.media-gallery .menu-bar').show();
		//console.log(mediatrigger);
		if(mediatrigger)
			el.find('.photo-video').show().addClass('photos');
		else
			el.find('.photo-video').hide();
		el.find('.gallery-share').show();
		if($(window).width() < 768 || $(window).height() <= 320 || isHandheld.mobile() && !($('.gallery-picker').is('hidden'))) {
			el.find('.media-gallery .menu-bar').show();
		} else {
			el.find('.gallery-back').show();	
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
		if($(window).width() < 768 || $(window).height() <= 320 || isHandheld.mobile() && !($('.gallery-picker').is('hidden'))) {
			$('.gallery-footer').css({'bottom': '-47px'});
		}		
		el.find('.gallery-picker').show();
		gimmeVideos = false;
	});
	
	$(document).on("click", ".view-videos", function(e){ 
		e.preventDefault();
		var el = $(this).closest('.media-gallery');
		if($(this).parent().hasClass('photos')){
			$(this).parent().removeClass('photos').addClass('videos');
			el.find('.galleria-youtube').html('');
		}
		gimmeVideos = true;
		$.loadGalleria(currentGallery, xmlfilename, el.attr('id'));
	});
	
	$(document).on("click", ".view-photos", function(e){ 
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
		$('.dropdown dt a').click(function(e){
			e.preventDefault();
			if ($(this).parent().parent().hasClass('active')) {
				$(this).parent().parent().removeClass('active');
				$(this).parent().parent().find('dd ul').slideUp('fast');
				whichgallery = $(this).parent().parent().attr('rel');
			} else {
				$(this).parent().parent().addClass('active');
				$(this).parent().parent().find('dd ul').slideDown('fast');
				whichgallery = $(this).parent().parent().attr('rel');				
			}
		});		
	});
	
	$('.share-this-on').click(function(e){
		e.preventDefault();
		if ($(this)._clicked) {
			//hide
			$(this)._clicked = false;
			console.log('share this was closed');
			if($(window).width() < 768 || $(window).height() <= 320 || isHandheld.mobile()) {
				$(this).parent().parent().animate({bottom: -47}, 'fast');
			}
		} else { 
			//show
			console.log('share this was clicked');		
			if($(window).width() < 768 || $(window).height() <= 320 || isHandheld.mobile()) {
				$(this).parent().parent().animate({bottom: 0}, 'fast');
			}
		}
	});

	$('.gallery-share .gallery-share-icon').click(function(e){
		var url = $(this).attr('href');
        e.preventDefault();
		window.open(url, 'share', 'width=652,height=352,scrollbars=1,toolbar=0,location=0,status=0');	
	});		

});

$(window).resize(function() { 
	if($('.media-gallery .galleria').data('galleria')) {
		Galleria.get(0).rescale(this._stageWidth,this._stageHeight); //rescales the image only
	}
});

// Single Category, no video
$.onecat = function(cat, filename, galleryid){
	mediatrigger = false;
	$('.media-gallery').show();
	$('.gallery-picker').hide();
	$('.gallery-footer .gallery-share').show();
	$('.galleria').show();
	$.loadGalleria(cat, filename, galleryid);
	$('#'+galleryid).fullscreenMobile();
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
	el.find('.gallery-footer .gallery-share,.choose-gallery, .photo-video').show();
	if(isHandheld.mobile())
		el.find('.gallery-back').show();
	el.show().find('.galleria').show();
	$.loadGalleria(cat, filename, galleryid);
	$('#'+galleryid).fullscreenMobile();
};

// Open gallery with specific category's videos
$.specific_cat_videos = function(cat, gotvideos, filename, galleryid){
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
	el.find('.gallery-footer .gallery-share,.choose-gallery, .photo-video').show();
	if(isHandheld.mobile())
		el.find('.gallery-back').show();
	el.show().find('.galleria').show();

	if($('.photo-video').hasClass('photos')){
		$('.photo-video').removeClass('photos').addClass('videos');
	}
	gimmeVideos = true;

	$.loadGalleria(cat, filename, galleryid);
	$('#'+galleryid).fullscreenMobile();
};

// Multi-category, no video
$.catlady = function(filename, galleryid){
	var el = $('#'+galleryid);
	mediatrigger = false;
	xmlfilename = filename;
	$('.media-gallery').show();
	$('.gallery-picker').show();
	el.show();
	$('#'+galleryid).fullscreenMobile();
};

// Multi-category, photo and video
$.catlady_videos = function(filename, galleryid){
	var el = $('#'+galleryid);
	mediatrigger = true;
	xmlfilename = filename;	
	$('.media-gallery').show();
	$('.gallery-picker').show();
	el.show();
	$('#'+galleryid).fullscreenMobile();
};

$.fn.fullscreenMobile = function() {  //disables up and down scrolling on touch devices when lightbox version of the gallery is visible.
	var self = this;
	if((isHandheld.any() || isHandheld.Android_Mobile()) || this.hasClass('fullscreen')) {
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
					fullscreenDoubleTap: false,
					transition: 'fadeBlack'
				});
			}
		} else {
			el.find('.galleria').html('<div class="no-stuff"><p><span>Sorry</span><br />NO VISUALS</p></div>');
			el.find('.galleria-info, .galleria-thumbnails-wrapper, .galleria-image-nav').hide();
		}
		
	}, "xml");
};

}(jQuery));
