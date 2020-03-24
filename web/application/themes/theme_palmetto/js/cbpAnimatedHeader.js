/**
 * cbpAnimatedHeader.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */

if( $(window).width() > 764) {
	var cbpAnimatedHeader = (function() {

		var docElem = document.documentElement,
			header = $('#header'),
			secNav = $('.sec-nav'),
			filterPanel = $('.filter-outer-wrapper'),
			didScroll = false,
			changeHeaderOn = 0,
			changesecNavOn = 0,
			changeFilterOn = 1;

		function init() {
			window.addEventListener('scroll', function(event) {
				if(!didScroll) {
					didScroll = true;
					setTimeout( scrollPage, 150 );
				}
			}, false );
		}

		function scrollPage() {
			var sy = scrollY();
			var ay = scrollY();
			var by = scrollY();

			if ( sy >= changeHeaderOn ) {
				$(header).addClass('header-shrink' );
				// Animate sticky header scroll to top
				 /* $('.header-shrink #branding a').click(function(e) {
					 e.preventDefault();
					$('html, body').animate({scrollTop: 0}, 300);	 
					 
				}); */ 
				
			} else {
				$(header).removeClass('header-shrink');
			}

			if ( by >= changesecNavOn ) {
				$(secNav).addClass('nav-shrink');

			} else {
				$(secNav).removeClass('nav-shrink');
			}

			if ( $('.filter-outer-wrapper').length ) {
				if ( ay >= changeFilterOn ) {
					$(filterPanel).addClass('filter-shrink');
				} else {
					$(filterPanel).removeClass('filter-shrink');
				}
			}

			didScroll = false;
		}

		function scrollY() {
			return window.pageYOffset || docElem.scrollTop;
		}

		init();
	})();
}