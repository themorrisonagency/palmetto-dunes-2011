$(function(){

        // save the vacation assurance checked value
        if ( jQuery.cookie && $('body').hasClass('userinfo') ) {

            if ( $.cookie('vacation_assurance') === '0' ) {
                $('input#vacationassurance').removeAttr('checked')
            }

            $('body.userinfo input#vacationassurance').click(function() {
                $.cookie('vacation_assurance', $('input#vacationassurance').is(':checked') ? 1 : 0 );
            });
        }

	var nextDay,
		button_arrive = $('#button-arrive'), arrive = $('.date-begin').pickadate({
			format: 'mm/dd/yyyy',
			formatSubmit: 'mm/dd/yyyy',
			min: true,
			today: 'Today',
			container: '#wrapper',
			clear: 'Clear', //hijacking clear to close and not clear -- see line 150 of picker.js
			close: '',
			onClose: function(){
        $(document.activeElement).blur()
    	} //prevent calendar from showing on browser tab change
		}),
		golf_menu_button_arrive = $('#golf-menu-button-arrive'), golfMenuArrive = $('.golf-menu-date-begin').pickadate({
			format: 'mm/dd/yyyy',
			formatSubmit: 'mm/dd/yyyy',
			min: true,
			today: 'Today',
			container: '#wrapper',
			clear: 'Clear', //hijacking clear to close and not clear -- see line 150 of picker.js
			close: '',
			onClose: function(){
        $(document.activeElement).blur()
    	} //prevent calendar from showing on browser tab change
		}),
		button_depart = $('#button-depart'), depart = $('.date-end').pickadate({
			format: 'mm/dd/yyyy',
			formatSubmit: 'mm/dd/yyyy',
			today: '',
			container: '#wrapper',
			clear: 'Clear', //hijacking clear to close and not clear -- see line 150 of picker.js
			close: '',
			onClose: function(){
        $(document.activeElement).blur()
    	} //prevent calendar from showing on browser tab change
		});
	
	var arrive_picker = arrive.pickadate('picker'),
		golf_menu_arrive_picker = golfMenuArrive.pickadate('picker'),
		depart_picker = depart.pickadate('picker');

		if (typeof golf_menu_arrive_picker === 'undefined') {
			golf_menu_arrive_picker = $('.datepicker').pickadate({
				format: 'mm/dd/yyyy',
				formatSubmit: 'mm-dd-yyyy',
				min: true,
				today: 'Today',
				clear: 'Close',
				onClose: function(){
        $(document.activeElement).blur()
    	} //prevent calendar from showing on browser tab change
			});
		}

	if(arrive_picker && depart_picker) {
		if ( arrive_picker.get('value') ) {
			depart_picker.set('min', arrive_picker.get('select'));
		}
		if ( golf_menu_arrive_picker.get('value') ) {
			depart_picker.set('min', golf_menu_arrive_picker.get('select'));
		}
		if ( depart_picker.get('value') ) {
			arrive_picker.set('max', depart_picker.get('select'));
		}

		// When something is selected, update the “from” and “to” limits.
		arrive_picker.on('set', function(event) {
			if ( event.select ) {
				depart_picker.set('min', arrive_picker.get('select'));
			}
		});

		golf_menu_arrive_picker.on('set', function(event) {
			if ( event.select ) {
				depart_picker.set('min', golf_menu_arrive_picker.get('select'));
			}
		});

		depart_picker.on('set', function(event) {
			if ( event.select ) {
				//arrive_picker.set('max', depart_picker.get('select'));
			}
		});

		arrive_picker.on('click', function(e) {
			e.preventDefault();
			arrive_picker.open();
			e.stopPropagation();
		});

		golf_menu_arrive_picker.on('click', function(e) {
			e.preventDefault();
			golf_menu_arrive_picker.open();
			e.stopPropagation();
		});

		depart_picker.on('click', function(e) {
			e.preventDefault();
			depart_picker.open();
			e.stopPropagation();
		});

		button_arrive.on( 'click', function(event) {
			//console.log('calendar clicked');
		    if (arrive_picker.get('open')) {
		        arrive_picker.close()
		    }
		    else {
		        arrive_picker.open()
		    }
		    event.stopPropagation()
		});

		golf_menu_button_arrive.on( 'click', function(event) {
			//console.log('calendar clicked');
		    if (golf_menu_arrive_picker.get('open')) {
		        golf_menu_arrive_picker.close()
		    }
		    else {
		        golf_menu_arrive_picker.open()
		    }
		    event.stopPropagation()
		});

		button_depart.on( 'click', function(event) {
		    if (depart_picker.get('open')) {
		        depart_picker.close()
		    }
		    else {
		        depart_picker.open()
		    }
		    event.stopPropagation()
		});
	}

	$('#header').addClass('sticky');

	if (!isMobileSearch) {
		// activate mega menu images
		$('.menu-pushes-wrapper img[data-src]').each(function() {
			$(this).attr('src',$(this).attr('data-src'));
		});
	}

	// hack to allow .on for show/hide
	$.each(['show', 'hide'], function (i, ev) {
		var el = $.fn[ev];
		$.fn[ev] = function () {
			this.trigger(ev);
			return el.apply(this, arguments);
		};
	});

	$('a[href*=#]:not([href=#])').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top
				}, 1000);
				return false;
			}
		}
	});

	// temp nav hack to account for activeSection1 failure to register properly
	$('.sec-nav .pos1').addClass('current');

	// expand collapse filters - animation in CSS
	$('.search-filter-header').click(function() {
		$(this).toggleClass('active');
	});
	$('.form-footer .cancel').on('click', function(e) {
		e.preventDefault();
		$('.search-filter-header').removeClass('active');
	});

	// add results count and list marker numbering
	$('#results-container').each(function() {
		var listItems = $(this).children('li'),
			totalVisible = listItems.length;
		if ($(this).parent().is('#search-results')) {
			$('.result-count span').text(totalVisible);
		}
		$('#search-results > ul > li').each(function() {
			var idx = $(this).index() + 1;
			$(this).find('.list-marker').text(idx);
		});
		if (totalVisible == 0) {
			$('.no-results-wrapper, .results-similar, .search-filter-header').addClass('active');
		}
	});

	$(".fancybox").fancybox({
		maxWidth	: 800,
		maxHeight	: 1000,
		fitToView	: false,
		width		: '70%',
		height		: '90%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});

	// expand collapse map - animation in CSS
	$('#map-toggle').click(function() {
		$(this).toggleClass('active');
		$('#mapDiv').show();
		$('#map-outer-wrapper').toggleClass('open');
		$('#map-toggle').html('expand map<span></span>');
		$('#map-toggle.active').html('reduce map<span></span>');
	});

	/*if (!$('#map-outer-wrapper').hasClass('open')) {
		$('#map-toggle').click(function() {
			$('#mapDiv').fadeIn(1000);
		});
	}*/

	$('.unit-li:visible .promo-close').on('click', function(e) {
	 	e.preventDefault();
	 	$(this).parent('.results-promo-details').slideUp('slow');
	});

    $('.unit-nav a').on('click', function () {
        $(this).parent().siblings().find('a').removeClass('active');
        $(this).addClass('active');
    });

    $('.unit-email').on('click', function(e) {
    	e.preventDefault();
    	$('.email-unit-popup').show();
    });

    $('.email-unit-popup .close').on('click', function(e) {
    	e.preventDefault();
    	$('.email-unit-popup').hide();
    });

    $('.book-block .info').on('click', function() {
    	$('.book-pricing-tooltip').toggleClass('active');
    });

    $('.rate-week .info').on('click', function() {
    	$('.unit-pricing-tooltip').toggleClass('active');
    });

    $('.results-pricing .tax-disclaimer .info').click(function() {
    	$(this).parent().parent().find('.search-unit-pricing-tooltip').toggleClass('active');
    });

    $('#search-form .clear').on('click', function(e) {
    	e.preventDefault();
    	$('#search-form').each(function() {
    		var dates = $('#arrival, #departure');
	        arrive_picker.$node.val('') // clear the input value
	        depart_picker.$node.val('')
					arrive_picker.stop().start() // restart the picker
					depart_picker.stop().start()
		    dates.val('');
		    pickAgain();
    		$(this).find('select').each(function() {
    			$(this).find('option').attr('selected', false);
				$(this).find('option:first').attr('selected', true);
			});
    		$('input').prop('checked', false);
			$('#promocode').val('');
    	});
    });

    $('#sort-order').on('change', function() {
    	$('#search-form #sortby').val($(this).val());
    	$('#search-form').submit();
    });

    if (isMobileSearch) {
    	// do not init cycle for mobile on search results page
    	$('#footer-tw-count').hide();
    }

  // pagination of search results page
  var $pHolder = $('.holder');
  if ($pHolder.length) {
    (function searchResults($pHolder, isMobileSearch) {
      var originalItemsCount = 10;
      var newPerPage = parseInt($.cookie("perPageCount") || originalItemsCount);
      var pContainer = ($('.page-wrapper').hasClass('resultcount-0')) ? 'similar-container' : 'results-container';
      var $resultsList = $('.results-list:visible');
      var $currentPageWrapper = $('.current-page-wrapper');
      var totalResults = $resultsList.find('.unit-li').length;

      pageIt();

      /* on select change */
      $('.holder-select select').change(function(){
        $.cookie("perPageCount", $(this).val());
        newPerPage = parseInt($.cookie("perPageCount"));
        $('.holder').jPages('destroy');
        pageIt();
        // pageIt() wiping span arrows on change
        $('.arrowPrev, .arrowNext').each(function() {
          if($(this).find('span').length == 0) {
            $(this).append('<span/>');
          }
        });
      });

      $('.holder-select select option[value=' + newPerPage + ']').attr("selected","selected");

      function pageIt() {
        $pHolder.jPages({
          containerID : pContainer,
          perPage: newPerPage,
          first: false,
          last: false,
          minHeight: false,
          previous: '.arrowPrev',
          next: '.arrowNext',
          links: 'blank',
          callback: onPageChange
        });
      }

      function onPageChange() {
      	var cdnhost = '0';
        curPageStatus();
        $('.unit-li:visible .room-slideshow').each(function(i, slideshow) {
          var $slideshow = $(slideshow);

          // if the images for this slideshow are already loaded, skip to next one
          if ('undefined' !== typeof $slideshow.attr('data-images-loaded')) return true;

          var $slideParent = $slideshow.parent().parent();
          var propId = $slideshow.attr('data-id');
          var slideLimit = isMobileSearch ? 1 : -1;
          var imgList = '/booking/ajax/property-images?propertyid=' + propId;
			
          if (isMobileSearch) {
            $slideParent.find('.room-prev, .room-next').hide();
          }

          $.get(imgList, function(json) {

              if ( !json.data || !json.data.images || !json.data.images.record ) {
                  return false;
              }

              // this craps out on single responses due to a CDE inconsistency
              var records = json.data.images.record;

              // normalize it
              if ( records.filename ) {
                  records = [records];
              }

            $.each(records, function(i, item) {
              var imgFilename = item.filename;
              var imgCaption = item.caption;
              cdnhost = (i + 1) % 5;
              if (cdnhost === 0) {
                  cdnhost = 5;
              }

              var newSlide = '<img src="https://media'+cdnhost+'.sabrecdn.com/pdbookingv12/images/properties/'+propId+'/images/'+imgFilename+'" alt="'+imgCaption+'" data-cycle-title="'+imgCaption+'" />';
              
              $slideshow.append(newSlide);

              if (isMobileSearch && (i + 1) === slideLimit) {
                $slideshow.find('.cycle-overlay').html('<div>'+imgCaption+'</div>');
                return false;
              }
            });

            if (!isMobileSearch) {
              $slideshow.cycle({
                fx: 'scrollHorz',
                slides: '> img',
                speed: 'fast',
                sync: 'true',
                timeout: 0,
                log: false,
                autoHeight: 'container',
                next: $slideParent.find('.room-next'),
                prev: $slideParent.find('.room-prev'),
              })
              .attr('data-images-loaded', '1');
            }
          });
        });
      }

      function curPageStatus() {
        var firstVis = $resultsList.find('.unit-li:visible:first').index() + 1;
        var lastVis = $resultsList.find('.unit-li:visible:last').index() + 1;
        $currentPageWrapper.text(firstVis + '-' + lastVis + ' of ' + totalResults);
      }
    }($pHolder, isMobileSearch));
  }

  /*$('.holder-select option:last-child').text("View All");*/

  /*
  if (window.location.search.indexOf('sortby=low_nightly') > -1) {
    var $lowHigh = $('#results-container');

    $lowHigh.find('.unit-li').sort(function (a, b) {
        return +a.getAttribute('data-sortby') - +b.getAttribute('data-sortby');
    })
    .appendTo( $lowHigh );
  }

  if (window.location.search.indexOf('sortby=high_nightly') > -1) {
    var $lowHigh = $('#results-container');

    $lowHigh.find('.unit-li').sort(function (b, a) {
        return +a.getAttribute('data-sortby') - +b.getAttribute('data-sortby');
    })
    .appendTo( $lowHigh );
  }*/

  // on the unit details pages, this keeps the two slideshows in sync
  if ($(document.body).hasClass('unit-details')) {
    var slideshows = $('.cycle-slideshow').on('cycle-next cycle-prev', function(e, opts) {
      slideshows.not(this).cycle('goto', opts.currSlide);
    });

    $('#cycle-2 .cycle-slide').click(function(){
      var index =$('#cycle-2').data('cycle.API').getSlideIndex(this);
      slideshows.cycle('goto', index);
    });

    $('.room-gallery .room-slideshow').on('cycle-before', function(e, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
        // your code to handle 'before' event
        var $el = $('.room-gallery-thumbs img:first'),
          $lel = $('.room-gallery-thumbs img:last');
        if ( $('.room-gallery-thumbs-wrapper').hasClass('dir-prev') ) {
          $el.parent().prepend($lel);
        } else {
          $el.parent().append($el);
        }
    });
  }

    // booking
    $('.book-details-pricing').each(function() {
		var lHeight = $('.book-unit-details .book-block').height(),
			rHeight = $('.book-pricing-details .book-block').height();
		if (lHeight > rHeight) {
			$('.book-pricing-details .book-block').height(lHeight);
		} else if (rHeight > lHeight) {
			$('.book-unit-details .book-block').height(rHeight);
		}
    });
   /* $('#book-submit').on('click', function() {
    	$(this).closest('form').submit();
    });*/

	// smooth scroll links to in-page anchors
	$('a[href*=#]:not([href=#])').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
	        || location.hostname == this.hostname) {

	        var target = $(this.hash);
	        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	           if (target.length) {
	             $('html,body').animate({
	                 scrollTop: target.offset().top
	            }, 400);
	            return false;
	        }
	    }
	});

	// check referer query string
	var qs = window.location.href.slice(window.location.href.indexOf('?') + 1);
	if (typeof qs !== 'undefined') {
	    var ref = document.referrer.split('?')[0];

	    // allow user to keep search string on 'back to search' button
	    $('.unit-tab-search a').each(function() {
	    	var cur = $(this).attr('href');
	    	qs = qs.split('#')[0];
	    	$(this).attr('href', cur + '?' + qs);
	    });
	}

	// Back to Search button
	if (window.location.hash == "#show") {
		$('.unit-details').addClass('go-back');
	}

	if ($(document.body).hasClass('go-back')) {
		$('.nav-tab-search a').on('click', function() {
			window.history.back();
		});
	}

	$('.conf-print a').click(function() {
		window.print();
	});

	$.extend({
  		getUrlVars: function(){
    		var vars = [], hash;
    		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    		for(var i = 0; i < hashes.length; i++) {
      			hash = hashes[i].split('=');
      			vars.push(hash[0]);
      			vars[hash[0]] = hash[1];
    		}
    		return vars;
  		},
  		getUrlVar: function(name){
    		return $.getUrlVars()[name];
  		}
	});

	var promoVal = $.getUrlVar("promocode");

	$('.unit-promo .add-promo-featured').click(function(e){
		e.preventDefault();
		promoCodeVal=$(this).attr('rel');
		$('body.unit-details #cal-booking-form input#promocode').val(promoCodeVal);

		var href = window.location.href;
		href=href.split('&');

		var arriveDate = $('#cal-booking-form #arrive').val();
		var departDate = $('#cal-booking-form #depart').val();

		window.location.href = href[0]+'&arrive='+arriveDate+'&depart='+departDate+'&promocode='+promoCodeVal+'&promocodeapplied=true';
	});

	$('.rates-promo .add-promo').click(function(e){
		e.preventDefault();
		promoCodeVal=$('body.unit-details #cal-booking-form input#promocode').val();
		var href = window.location.href;
		href=href.split('&');
		var arriveDate = $('#cal-booking-form #arrive').val();
		var departDate = $('#cal-booking-form #depart').val();
		var propertyid = $(this).closest('#cal-booking-form').find('input[name="propertyid"]').val();

		window.location.href = href[0]+'&arrive='+arriveDate+'&depart='+departDate+'&promocode='+promoCodeVal+'&propertyid='+propertyid;
	});

	$('.rates-promo .add-promo-featured').click(function(e){
		e.preventDefault();
		promoCodeVal=$(this).attr('rel');
		$('body.unit-details #cal-booking-form #promocode').val(promoCodeVal);

		var arriveDate = $('#cal-booking-form #arrive').val();
		var departDate = $('#cal-booking-form #depart').val();

		var href = window.location.href;

		href=href.split('&');

		window.location.href = href[0]+'&arrive='+arriveDate+'&depart='+departDate+'&promocode='+promoCodeVal+'&promocodeapplied=true';
	});

	var promoCodePassed = $.getUrlVar("promocodeapplied");
	if (promoCodePassed == 'true') {
		$('html, body').animate({scrollTop:$('#sect-heading').offset().top }, 'slow');
		$('.rates-promo span.promo-code-text').html('Promo code has been applied.');
		$('body.unit-details #cal-booking-form input#promocode').val(promoVal);
	}

	$('.promo-learn-more').click(function(e){
		e.preventDefault();
	    $('.results-promo-details').fadeIn();
	});
	$('.unit-promo .promo-close').click(function(e){
		e.preventDefault();
	    $('.results-promo-details').fadeOut();
	});

	// picker reset auto date
	function pickAgain() {
		arrive_picker.on('set', function(event) {
			if ( event.select ) {
				depart_picker.set('min', arrive_picker.get('select'));
			}
		});
		depart_picker.on('set', function(event) {
			if ( event.select ) {
				arrive_picker.set('max', depart_picker.get('select'));
			}
		});
	}

	$('#guest-info .field-select select#adults, #guest-info .field-select select#children, #guest-info .field-passes select#carpasses').find('option').each(function() {
		selClass = $(this).parent('select').attr('class');
		opt = $(this);
			if (opt.val() == selClass) {
				opt.attr({selected: 'selected'});
			}
    });

	var searchText = 'null-null of 0';

	$('.current-page-wrapper').filter(function () {
		return $(this).text() === searchText;
	}).css('color', 'transparent');


	//booking console villas/homes location change
	$('select.location-villas, select.location-homes, select.view-homes').hide();
	updateLocation($('#search-panel select#type').val());
	$('#search-panel select#type').on('change', function() {
		updateLocation($(this).val());
	});

	function updateLocation (selVal) {
		if ( selVal == '2') { //if villas selected show villas dropdown hide others
			$('select.location-all, select.location-homes, select.view-homes').hide();
			$('select.location-all, select.location-homes, select.view-homes').removeAttr('name');
			$('select.location-villas, select.view-all').show();
			$('select.location-villas').attr('name', 'locationid');
		} else if ( selVal == '1' ) { //if homes selected show homes dropdown hide others
			$('select.location-all, select.location-villas, select.view-all').hide();
			$('select.location-all, select.location-villas, select.view-all').removeAttr('name');
			$('select.location-homes, select.view-homes').show();
			$('select.view-homes').attr('name', 'viewid');
			$('select.location-homes').attr('name', 'locationid');
		} else { //if no type selected show all locations dropdown
			$('select.location-villas, select.location-homes, select.view-homes').hide();
			$('select.location-villas, select.location-homes, select.view-homes').removeAttr('name');
			$('select.location-all, select.view-all').show();
			$('select.view-all').attr('name', 'viewid');
			$('select.location-all').attr('name', 'locationid');
		}
	}


});

$(document).ready(function () {
	var anchor_id = window.location.hash;
	if (anchor_id !== "" && anchor_id=='#rates-avail-anchor') {
		$('html, body').animate({scrollTop:$('#rates-avail-anch').position().top+150}, 'slow');
	}


	//added to clear max date when departure date is cleared
	var arrive = $('.date-begin').pickadate({
			format: 'mm/dd/yyyy',
			formatSubmit: 'mm/dd/yyyy',
			min: true,
			today: 'Today',
			container: '#wrapper',
			clear: 'Clear', //hijacking clear to close and not clear -- see line 150 of picker.js
			close: ''
		});
	var arrive_picker = arrive.pickadate('picker');

	$('#departure_root .picker__button--clear').on('click', function(e) {
		e.preventDefault();
		arrive_picker.set('max', '');
	});
});
