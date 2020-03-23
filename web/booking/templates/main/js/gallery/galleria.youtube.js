/**
 * @preserve Galleria youtube Plugin 2011-07-28
 * http://galleria.aino.se
 *
 * Copyright 2011, Aino
 * Licensed under the MIT license.
 */

/*global jQuery, Galleria */

( function($) {
	var data, elem,
	
	onload = function() {
		this.addElement('youtube').prependChild('stage', 'youtube');

		var //z = Galleria.utils.parseValue(this.$('image-nav-left').css('z-index')),
            touch = Galleria.TOUCH;
		
		var youtube = this.$('youtube').css({
			position: 'absolute',
			top: 0,
			left: 0,
			bottom: 0,
			right: 0,
			zIndex: 10,
			background: '#000 url(../images/photo-gallery/classic-loader.gif) center 50px no-repeat'
		}).hide(),

		insert = function() {
			var source = "";

			$.each(data, function(key, val) {
				if(key == 'video') {
					source = val;					
				}
			});

			if(source.length) {
				elem = $('<iframe>', {
					frameborder: '0',
					type: 'text/html',
					allowtransparency: 'true',
					src: 'http://www.youtube.com/embed/' + source + '?wmode=transparent&hd=1&rel=0&showinfo=0&showsearch=0'
				}).appendTo(youtube);
			} else {
				elem = false;
			}
		};

		this.bind('loadstate', function(e){
			if (!e.cached) {
				this.$('loader').show().fadeTo(200, 0.4);
			}
			//console.log('youtube loadstart');
		});
		
		this.bind('loadfinish', function(e) {
			data = this.getData(e.index);
			this.$('loader').fadeOut(200);
			youtube.html('');
			insert();
			//console.log('youtube loadfinish');
		});
		
		this.bind('image', function(e) {
			if(isHandheld.any() || isHandheld.Android_Mobile() || this.getStageWidth() < 768) {
				var videoW = (this.getStageWidth() - 66),
					videoH = (this.getStageHeight() - 49)/(1.777),
					videoStyle = 'margin-left: -'+ parseInt(videoW / 2) + 'px; top: 50%; margin-top: -'+ parseInt(videoH)+'px;'
			} else {
				var videoW = this.getStageWidth() - 100,
					currentH = this.getStageHeight() - 150,
					aspectratio = 0.5625,
					videoH = videoW * aspectratio;
			
				if(currentH < videoH) {
					videoH = currentH;
					videoW = videoH * (1.777);
					
				}
				var videoStyle = 'margin-left: -'+ (videoW / 2) + 'px; top: 49px;'
			}

			
			if(elem){
				elem.attr({
					width : videoW,
					height : videoH,
					style: videoStyle
				});
			}
			//console.log('youtube image');
		});
		
		/*this.bind('thumbnail', function(){
			
		});*/
		
		this.bind('rescale', function() {
			if(isHandheld.any() || isHandheld.Android_Mobile() || this.getStageWidth() < 768) {
				var videoW = (this.getStageWidth() - 66),
					videoH = (this.getStageHeight() - 49)/(1.777),
					videoStyle = 'margin-left: -'+ parseInt(videoW / 2) + 'px; top: 50%; margin-top: -'+ parseInt(videoH / 2)+'px;'
			} else {
				var videoW = this.getStageWidth() - 100,
					currentH = this.getStageHeight() - 150,
					aspectratio = 0.5625,
					videoH = videoW * aspectratio;
			
				if(currentH < videoH) {
					videoH = currentH;
					videoW = videoH * (1.777);
					
				}
				var videoStyle = 'margin-left: -'+ (videoW / 2) + 'px; top: 49px;'
			}

			
			if(elem){
				elem.attr({
					width : videoW,
					height : videoH,
					style: videoStyle
				});
			}
			//console.log('youtube rescale');
		});
	};

	Galleria.ready(onload);

}(jQuery));