// main.js
$(document).ready(function(){

    $('.press-gallery-picker select').each(function(){
    	var select = $(this),
    		currentCategory = select.attr('data-current-category');

    	select.find('option').each(function(){
    		var opt = $(this);
			if (opt.val() == currentCategory)
				opt.attr({selected: 'selected'});
    	});
    });

	if($('window').width() <= 1024) {
		$('.blog-signup .email-signup').each(function(){
			var el = $(this);
			el.find('input').attr('placeholder', 'Subscribe by Email'); //.attr('placeholder', 'Subscribe by Email');
		});
	}

	$('.header-shrink #brandingtop a').click(function(e) {
		 e.preventDefault();
		$('html, body').animate({scrollTop: 0}, 300);

	});
});

(function($) {

	 if( $(window).width() > 764) {
		$(window).bind('scroll', function() {
			var winHeight = $( window ).height();
			var headerHeight = $( window ).height() - ($( window ).height() - 0);
			if ($(window).scrollTop() > headerHeight) {
				 $('#header').addClass('fixed');
				 $('#signup-widget, #signup-tab').addClass('fixed');
			  $('#header').removeClass('normalized');
				 $('.sec-nav.int-nav').addClass('sec-fixed');
				  $('.sec-nav.int-nav').removeClass('sec-normalized');
			}
			else {
			  $('#header').addClass('normalized');
				 $('#header').removeClass('fixed');
				 $('#signup-widget, #signup-tab').removeClass('fixed');
				 $('.sec-nav.int-nav').addClass('sec-normalized');
				 $('.sec-nav.int-nav').removeClass('sec-fixed');
			}
		});
	}

	$.fn.initializeForm = function(options) {
		var defaults = {
			calText: 'Click to select a date',
			dateFormat: 'mm/dd/yyyy'
		},
		opts = $.extend(defaults, options),
		focusIsSupported = (function(){
		    // Create an anchor + some styles including ':focus'.
		    // Focus the anchor, test if style was applied,
		    // if it was then we know ':focus' is supported.

		    var ud = 't' + +new Date(),
		    anchor = $('<a id="' + ud + '" href="#"/>').css({top:'-999px',position:'absolute'}).appendTo('body'),
		    style = $('<style>#'+ud+'{font-size:10px;}#'+ud+':focus{font-size:1px !important;}</style>').appendTo('head'),
		    supported = anchor.focus().css('fontSize') !== '10px';
		    anchor.add(style).remove();
		return supported;

		})();

		return this.each(function() {
			var form = $(this);

			if ( !focusIsSupported ) {
				$(".textfield")
					.focus(function() { $(this).css({backgroundColor: "#fdfcfa"}); })
					.blur(function() { $(this).css({backgroundColor: "#ffffff"}); });
				$('.required', form).each(function() {
					$(this).prev().addClass('ie-icon-required');
				});
			}

			/*$(".date-picker", form).each(function() {
				$(this).datepicker({
					beforeShow: setDatePicker,
					buttonImage: "/custom/a4_quaillodge/img/icon-cal.gif",
					buttonImageOnly: true ,
					buttonText: opts.calText,
					duration: "fast",
					gotoCurrent: true,
					hideIfNoPrevNext: true,
					numberOfMonths: 2,
					showAnim: "blind",
					showOn: "both"
				});

				if($(this).val()==opts.dateFormat) {
					$(this).val('');
				}
				else {
					if($(this).hasClass('date-end')&&$(this).hasClass('update-blocks'))
						getRange(this);
				}
			});

			$('.date-picker', form).each(function(){
				$(this).find('input').pickadate({
					format: 'mm/dd/yyyy',
					formatSubmit: 'mm/dd/yyyy',
					min: true,
					today: 'Today',
					clear: 'Close', //hijacking clear to close and not clear -- see line 150 of picker.js
					onClose: function() {
						console.log($(this).attr('id'))
						var selected_date = $(this).get('select', 'mm/dd/yyyy');
		                $(this).attr({
		                    'value'     : selected_date,
		                    'data-value': selected_date
		                });
					}
				});
			});*/

			// hide all "other" fields
			$("ul.checkboxgroup input.other", form).not(':radio, :checkbox').hide();
			$("ul.checkboxgroup input:not(:text)", form).click(function() {
				$('.other', $(this).parents('ul')).siblings().removeClass('required').filter(':text').hide();
				if($(this).is(':checked')) {
					$('.validation-error', $(this).parents('ul')).removeError(form);
					if($(this).hasClass('other'))
						$(this).siblings().addClass('required').show();
				}
			});

			$("div.select-other", form).hide();
			// if "Other" is selected, then show it's "other" field
			$("select.select-other", form).change( function () {
				var el = $(this).parent().next()
				if($(this).val() == "--") {
					el.show().children('label, input').addClass("required");
				} else {
					el.hide().children().removeClass("required").find("input").val("");
					$('.validation-error', el).removeError(form);
				}
			});

		});
	};

	$.fn.formValidate = function(options) {
		var defaults = {
			errorStart: 'A valid ',
			errorEnd: ' is required.'
		},
		opts = $.extend(defaults, options);
		return this.each(function() {
			var form = $(this);
			$('.submit', form).click(function() {
				var _errors = '';
				//Removes validation errors from previous submit
				$('.validation-error', form).removeError(form);

				$('.book-wrapper input#promo-code').val (function () {
				    return this.value.toUpperCase();
				});

				$('.required', form).each(function() {
					var valid = true;

					if($(this).is(':input')) {
						var o = $.trim($(this).val());

						if($(this).parents('.field').hasClass('email')) {
							// if it's an email address make sure the email is valid using both regular expressions
							valid = /^[a-z0-9_+.-]+\@(?:[a-z0-9-]+\.)+[a-z0-9]{2,4}$/i.test(o);
							if($(this).hasClass('confirm-email')&&valid) {
								var prev = $.trim($(this).parents('.field').prev().children('.email').val());
								if(o!=prev)
									valid=!valid;
							}
						} else if (o.replace(/(?:^\s+)|(?:\s+$)/g,'').length < 1) {
							// if not an email address take out funky characters and see if its still blank
							valid = !valid;
						}
					} else if($(this).is('ul')) {
						valid = false;

						$(':radio, :checkbox', this).each(function() {
							if($(this).is(':checked'))
								valid = true;
						});
					}

					if(!valid) {
						if(form.hasClass('inline')) {
							$(this).parents('.field').children('label').addClass('validation-error');
						} else {
							var warn = $('<img />').attr('src', '/custom/a4_quaillodge/img/icon-warning.gif').addClass('validation-error');

							if( $(this).is('ul') )
								$('li:first', this).append(warn);
							else
								$(this).parent().append(warn);
						}

						if(_errors == '')
							_errors = $(this).attr("id");
					}
				});
				if(_errors.length) {
					var lbl = $('#'+_errors).parents('.field').find('label:eq(0)');

					// Adds back in label in IE
					if (lbl.html() == '') {
						$('#arriving label.ie-datepicker-fix').text('Arrival');
						$('#departing label.ie-datepicker-fix').text('Departure');
					}

					alert(opts.errorStart + lbl.html().replace(/[*:]/g, '') + opts.errorEnd)

					// IE Datepicker Placeholder Fix
					if (lbl.html() == 'Arrival')
						$('#arriving label.ie-datepicker-fix').text('');
					if (lbl.html() == 'Departure')
						$('#departing label.ie-datepicker-fix').text('');

					if ($('#'+_errors).is('ul'))
						$('#'+_errors, form).find('label:eq(0)').focus();
					else
						$('#'+_errors, form).focus();
					return false;
				}
			});
		});
	};

	$.fn.expand = function(options) {
		var defaults = {
			openText: 'View Details',
			closeText: 'Hide Details',
			longClass: '.package-long'
		},
		opts = $.extend(defaults, options);
		return this.each(function() {
			var obj = $(this);
			var tog = $('<div />').addClass('toggle');
			var control = $('<a />').attr('href', '#')
							.addClass('toggler')
							.html(opts.openText)
							.appendTo(tog)
							.toggle(function() { $(opts.longClass, obj).slideDown('slow'); $(this).html(opts.closeText).addClass('open'); },
									function() { $(opts.longClass, obj).slideUp('slow'); $(this).html(opts.openText).removeClass('open'); });

			$(opts.longClass, obj).after(tog).hide();
		});
	};

	$.fn.removeError = function(form) {
		return this.each(function() {
			if(form.hasClass('inline'))
				$(this).removeClass('validation-error');
			else
				$(this).remove();
		});
	};

	$.fn.postPreview = function(options) {
		var defaults = {
			hideDetails : 'Hide Full Post',
			viewDetails : 'View Full Post'
		},
		opts = $.extend(defaults, options);
		return this.each(function( counter, context ) {

			$('.post-icon', this).click(function(e) {
			    e.preventDefault();
			    $('.post-details a', context).trigger('click')
			});

			$('a.posting-details', this).toggle(function(event) {
				event.preventDefault();
				$('.share-panel').hide();
				var el = $(this);
				$.getJSON('/blog-details/' + this.rel, function(json) {
					var testnode = el.find('*').contents().filter(function() { return this.nodeType == 3 });
					testnode.length ? testnode.after(opts.hideDetails).remove() : el.html(opts.hideDetails);
					el.parent().addClass('active').parents('.post').find('.post-description').hide().html(json.item_html).slideDown('slow');
				});
			},function(event) {
				event.preventDefault();
				$('.share-panel').hide();
				var testnode = $(this).find('*').contents().filter(function() { return this.nodeType == 3 });
				testnode.length ? testnode.after(opts.viewDetails).remove() : $(this).html(opts.viewDetails);
				$(this).parent().removeClass('active').parents('.post').find('.post-description').slideUp('slow', function() {
					$(this).html('');
				});
			});
		});
	};

	$.fn.packagePreview = function(options) {
		var defaults = {
			hideDetails : '',
			viewDetails : ''
		},
		opts = $.extend(defaults, options);
		return this.each(function() {
			if(!$('body').hasClass('permalink')){
				$(this).find('.share').hide();
			}
			$('a.package-details', this).toggle(function(event) {
				event.preventDefault();
				var el = $(this);
				$.getJSON(ROOTPATH+'packagesdata/'+this.rel,function(json){
					//console.log(json);
					var testnode = el.find('*').contents().filter(function() { return this.nodeType == 3 });
					testnode.length ? testnode.after(opts.hideDetails).remove() : el.html(opts.hideDetails);
					el.parent().parent().addClass('active').parents('.package-wrapper').find('.package-long').hide().html(json.data.record.item_html).slideDown('slow',function() {
						$(this).parents('.package-wrapper').find('.share').show();
						FB.XFBML.parse();
					});
				});
			},function(event) {
				event.preventDefault();
				$(this).parents('.package-wrapper').find('.share').hide();
				var testnode = $(this).find('*').contents().filter(function() { return this.nodeType == 3 });
				testnode.length ? testnode.after(opts.viewDetails).remove() : $(this).html(opts.viewDetails);
				$(this).parent().parent().removeClass('active').parent().find('.package-long').slideUp('slow', function() {
					$(this).html('');
					$(this).parents('.package-wrapper').find('.share').hide();
				});
			});
		});
	};

	$.fn.initializeSharing = function() {
		return this.each(function() {
			$('.share-link').live('click',function(event) {
				event.preventDefault();
				$('.share-panel').hide();
				$(this).parents('.rss-item').find('.share-panel').css('display','inline');
			});
			$('.share-close').live('click',function(event){
				event.preventDefault();
				$(this).parents('.rss-item').find('.share-panel').slideUp('fast');
			});
		});
	};

	$.fn.initializeFollowing = function() {
		return this.each(function() {
			$('.follow-link').click(function(event) {
				event.preventDefault();
				$('.follow-panel').hide();
				$(this).siblings('.follow-panel').css('display','inline');
			});
			$('.follow-close').click(function(event){
				event.preventDefault();
				$(this).parent('.follow-panel').slideUp('fast');
			});
		});
	};

	//If the URL has a hash (anchor) bit, scroll to that.
	// if ( window.location.hash ) {
	// 	$.scrollTo(window.location.hash);
	// }

	$('.press-archives').on('change', function(){
	    window.location = $(this).val();
	});

	// Fareharbor
	var fareharborDomain = 'https://fareharbor.com/embeds/book/';

	var types = {
		'bayrunnercharters': {
			'fishing': 'Fishing',
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'sailing': 'Sailing',
			'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'palmettolagooncharters': {
			'fishing': 'Fishing',
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'mightymako': {
			'fishing': 'Fishing',
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'hiltonheadislandcharterfishing': {
			'fishing': 'Fishing',
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'palmettolagoonfishing': {
			'fishing': 'Fishing',
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'backwatercatadventure': {
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'fishing': 'Fishing',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'hiltonheadoutfitters': {
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'fireworkscruisecapthook': 'Fireworks Cruise Capt Hook',
			'fishing': 'Fishing',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'kayaksmarina': {
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'fishing': 'Fishing',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'paddleboardsmarina': {
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'fishing': 'Fishing',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'captmarksdolphincruises': {
			'dolphin-cruises': 'Dolphin Tours',
			'water-sports': 'Water Sports',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'fishing': 'Fishing',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'dolphinseafari': {
			'dolphin-cruises': 'Dolphin Tours',
			'water-sports': 'Water Sports',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'fishing': 'Fishing',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'piratekidzofhiltonhead': {
			'dolphin-cruises': 'Dolphin Tours',
			'water-sports': 'Water Sports',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'fishing': 'Fishing',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'fireworks-cruises': {
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fishing': 'Fishing',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'boatrentalmarina': {
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'fishing': 'Fishing',
			'sailing': 'Sailing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'hiltonheadislandsailing': {
			'sailing': 'Sailing',
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'fishing': 'Fishing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},
		'hiltonheadsailingcharters': {
			'sailing': 'Sailing',
			'water-sports': 'Water Sports',
			'dolphin-cruises': 'Dolphin Tours',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'fishing': 'Fishing',
            'nature-and-adventure-cruises': 'Nature & Adventure Cruises'
		},

	};
	var activities = {
		'': {
			'':'Choose Activity',
			'backwatercatadventure': 'Two-person Catamaran Tours',
			'hiltonheadoutfitters': 'Surf Lessons',
			'kayaksmarina': 'Kayaks – Marina',
			'paddleboardsmarina': 'Paddleboards – Marina',
			'boatrentalmarina': 'Boat Rentals – Marina',
			'captmarksdolphincruises': 'Dolphin Cruises Holiday',
			'dolphinseafari': 'Dolphin &amp; Mermaid Cruises Seafari',
			'piratekidzofhiltonhead':'Shannon Tanner Pirate Cruise',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'fireworkscruisecapthook': 'Fireworks Cruise Capt Hook',
			'bayrunnercharters': 'Inshore Bayrunner Fishing',
			'palmettolagooncharters': 'Inshore Maverick Fishing',
			'mightymako': 'Inshore Mighty Mako Fishing',
			'hiltonheadislandcharterfishing': 'Offshore Gullah Gal Fishing',
			'palmettolagoonfishing': 'Lagoon Fishing',
			'hiltonheadislandsailing': 'Sailing Flying Circus Charter',
			'hiltonheadsailingcharters': 'Sailing Special K Charter'
		},
		'fishing': {
			'':'Choose Activity',
			'bayrunnercharters': 'Inshore Bayrunner Fishing',
			'palmettolagooncharters': 'Inshore Maverick Fishing',
			'inshorefishonfishing': 'Inshore Fish-On Fishing',
			'mightymako': 'Inshore Mighty Mako Fishing',
			'hiltonheadislandcharterfishing': 'Offshore Gullah Gal Fishing',
			//'saltysanta': 'Offshore Black Skimmer Fishing',
			'partyfishingboat': 'Party Fishing Boat',
			'outsidehhfishing': 'Outside Hilton Head Fishing',
			'palmettolagoonfishing': 'Lagoon Fishing'
		},
		'sailing': {
			'':'Choose Activity',
			'hiltonheadislandsailing': 'Sailing Flying Circus Charter',
			'hiltonheadsailingcharters': 'Sailing Special K Charter',
            'saltmarshsailing' : 'Salt Marsh Sailing',
		},
		'water-sports': {
			'':'Choose Activity',
			'backwatercatadventure': 'Two-person Catamaran Tours',
			'hiltonheadoutfitters': 'Surf Lessons',
			'kayaksmarina': 'Kayaks – Marina',
			'paddleboardsmarina': 'Paddleboards – Marina',
			'boatrentalmarina': 'Boat Rentals – Marina',
			'kayakcanoeslagoon': 'Kayak/Canoes - Lagoon'
		},
		'dolphin-cruises': {
			'':'Choose Activity',
			'captmarksdolphincruises': 'Dolphin Cruises Holiday',
			'dolphinseafari': 'Dolphin Cruises Seafari',
			'dolphinsailingcruiseflyingcircus': 'Dolphin Sailing Cruise Flying Circus',
			'dolphinecotour':'Dolphin Eco Tour',
			'slatmarshdolphinsail' : 'Salt Marsh Dolphin Sail',
		},
		'nature-and-adventure-cruises': {
            '':'Choose Activity',
            'piratekidzofhiltonhead':'Shannon Tanner Pirate Cruise',
			'sportcrabbingcrabberj': 'Sport Crabbing Crabber J',
            'daufuskieislandtour' : 'Daufuskie Island Tour',
            'beachcombingcruise' : 'Beachcombing Cruise',
        },
		'fireworks-cruises': {
			'':'Choose Activity',
			'fireworks-cruises': 'Fireworks Cruise Holiday',
			'fireworkscruisecapthook': 'Fireworks Cruise Capt Hook',
			'july4thfireworkscapthook': 'July 4th Fireworks Capt Hook',
			'fireworkscruiseseafari': 'Fireworks Cruise Seafari',
            'fireworkscruisecraigcat': 'Fireworks Cruise Craigcat',
			'fireworkssailsaltmarsh': 'Fireworks Sail Salt Marsh',
    		'fireworkssailflyingcircus': 'Fireworks Sail Flying Circus',
    		'fireworkspadle': 'Fireworks Paddle',
		}
	};

	var numPeople = {
		'bayrunnercharters': ['Up to 4', 'Up to 12'],
		'palmettolagooncharters': ['Up to 3'],
		'kayakcanoeslagoon': ['Up to 3'],
		'mightymako': ['Up to 4'],
		'hiltonheadislandcharterfishing': ['Up to 6'],
		'palmettolagoonfishing': ['Up to 3'],
		'hiltonheadislandsailing': ['Up to 6'],
		'hiltonheadsailingcharters': ['Up to 6'],
		'backwatercatadventure': ['Up to 2'],
		'hiltonheadoutfitters': ['Up to 10'],
		'captmarksdolphincruises': ['Up to 148'],
		'dolphinseafari': ['Up to 20'],
		'fireworks-cruises': ['Up to 148'],
		'kayaksmarina': ['Up to 15'],
		'paddleboardsmarina': ['Up to 5'],
		'piratekidzofhiltonhead': ['Up to 130'],
		'boatrentalmarina': ['Up to 6'],
		'fireworkscruisecapthook': ['Up to 40'],
		'fireworkscruiseseafari': ['Up to 20'],
		'july4thfireworkscapthook': ['Up to 40'],
		'dolphinsailingcruiseflyingcircus': ['Up to 6'],
        'dolphinecotour': ['Up to 18'],
        'slatmarshdolphinsail': ['Up to 6'],
		'inshorefishonfishing': ['Up to 4'],
		'partyfishingboat': ['Up to 40'],
        'outsidehhfishing': ['Up to 6'],
		'sportcrabbingcrabberj' : ['Up to 5'],
		'saltysanta': ['Up to 6'],
		'daufuskieislandtour' : ['Up to 13'],
        'beachcombingcruise' : ['Up to 18'],
		'saltmarshsailing' : ['Up to 6'],
        'fireworkscruisecraigcat': ['Up to 20'],
        'fireworkssailsaltmarsh': ['Up to 6'],
        'fireworkssailflyingcircus': ['Up to 6'],
        'fireworkspadle': ['Up to 19'],
	};

	var items = {
		'bayrunnercharters': {
			'Up to 4': [],
			'Up to 12': [38105]
		},
		'palmettolagooncharters': {
			'Up to 3': [37966]
		},
		'mightymako': {
			'Up to 4': [41114]
		},
		'hiltonheadislandcharterfishing': {
			'Up to 6': []
		},
		'palmettolagoonfishing': {
			'Up to 3': [41968]
		},
		'hiltonheadislandsailing': {
			'Up to 6': []
		},
		'hiltonheadsailingcharters': {
			'Up to 6': [37981]
		},
		'backwatercatadventure': {
			'Up to 2': []
		},
		'hiltonheadoutfitters': {
			//'Up to 10': [43964,45426]
            'Up to 10': [45426]
		},
		'captmarksdolphincruises': {
			'Up to 148': [43423,43428]
		},
		'dolphinseafari': {
			'Up to 20': []
		},
		'fireworks-cruises': {
			'Up to 148': [43432]
		},
		'kayaksmarina': {
			//'Up to 15': [47158]
			'Up to 15': ["?ref=palmettodunes.com&asn=palmettodunes&full-items=yes&flow=135093"]
		},
		'paddleboardsmarina': {
			//'Up to 5': [47158]
            'Up to 5': ["?ref=palmettodunes.com&asn=palmettodunes&full-items=yes&flow=135093"]
		},
		'piratekidzofhiltonhead': {
			'Up to 130': []
		},
		'boatrentalmarina': {
			'Up to 6': []
		},
		'fireworkscruisecapthook': {
			'Up to 40': [43265]
		},
		'july4thfireworkscapthook': {
			'Up to 40': [43266]
		},
		'fireworkscruiseseafari': {
			'Up to 20': [55011]
		},
		'dolphinsailingcruiseflyingcircus': {
			'Up to 6': [43270]
		},
		'partyfishingboat': {
			'Up to 40': []
		},
		'inshorefishonfishing': {
			'Up to 4': [77419]
		},
		'saltysanta': {
			'Up to 6': [77953]
		},
		'kayakcanoeslagoon': {
			/*'Up to 3': [43956]*/
			'Up to 3': ['?flow=196500']
		},
        'outsidehhfishing': {
            'Up to 6': ['77219/?full-items=yes&flow=no&asn=palmettodunes']
        },
        'dolphinecotour': {
            'Up to 18': ['75379/?flow=no&asn=palmettodunes']
        },
        'slatmarshdolphinsail': {
            'Up to 6': ['126745/calendar/2019/06/?flow=134442&asn=palmettodunes']
        },
        'sportcrabbingcrabberj': {
            'Up to 5': ['43435?asn=palmettodunes']
        },
		'daufuskieislandtour' : {
            'Up to 13': ['75500/calendar/?flow=60759&asn=palmettodunes']
		},
		'beachcombingcruise' : {
            'Up to 18': ['75488/calendar/?flow=no&asn=palmettodunes']
        },
        'saltmarshsailing' : {
          //'Up to 6': ['126736/calendar/?flow=134442&asn=palmettodunes']
			'Up to 6': ['calendar/?ref=palmettodunes.com&flow=134442&asn=palmettodunes']
		},
        'fireworkscruisecraigcat': {
			'Up to 20' : ['52352/?asn=palmettodunes']
		},
        'fireworkssailsaltmarsh':  {
			'Up to 6' : ['126741/calendar/?flow=134442&asn=palmettodunes']
		},
        'fireworkssailflyingcircus':  {
			'Up to 6' : ['80540/?asn=palmettodunes']
		},
        'fireworkspadle':  {
			'Up to 19' : ['75747/calendar/?flow=60759&asn=palmettodunes']
		},
	};


	$('#reservations-console-fareharbor').each(function(){
		var $form = $(this);

		$form.find('.btn').on('click', function(e){
    		e.preventDefault();

    		var activity = $('#activity').val(),
				numPeeps = $('#num-people').val(),
				href = fareharborDomain;

			if(activity && numPeeps) {
				if(typeof items[activity][numPeeps] !== 'undefined') {
					var fareharborOpts = {
						'ref': 'palmettodunes.com'
					};
					if(activity === 'fireworks-cruises')
						fareharborOpts['shortname'] = 'captmarksdolphincruises';

						else if(activity === 'dolphinsailingcruiseflyingcircus' || activity === 'fireworkssailflyingcircus')
						fareharborOpts['shortname'] = 'hiltonheadislandsailing';
					else if(activity === 'fireworkscruiseseafari')
						fareharborOpts['shortname'] = 'dolphinseafari';
					else if(activity === 'fireworkscruiseseafari')
						fareharborOpts['shortname'] = 'dolphinseafari';

					else if(activity === 'kayakcanoeslagoon')
						//fareharborOpts['shortname'] = 'hiltonheadoutfitters';
                    fareharborOpts['shortname'] = 'hiltonheadoutfitters-rentals';

					else if(activity === 'fireworkscruisecapthook' || activity === 'july4thfireworkscapthook' || activity === 'partyfishingboat')
						fareharborOpts['shortname'] = 'captainhookhiltonhead';
					else if(activity=== 'boatrentalmarina')
						fareharborOpts['shortname'] = 'islandmarinehh';
					else if(activity=== 'palmettolagoonfishing' || activity=== 'inshorefishonfishing')
						fareharborOpts['shortname'] = 'palmettolagooncharters';
                    else if(activity=== 'outsidehhfishing' || activity=== 'dolphinecotour' || activity==='daufuskieislandtour' || activity === 'beachcombingcruise' || activity === 'fireworkspadle')
                        fareharborOpts['shortname'] = 'outsidehiltonhead';
                    else if(activity=== 'slatmarshdolphinsail' || activity === 'saltmarshsailing' || activity === 'fireworkssailsaltmarsh')
                        fareharborOpts['shortname'] = 'saltmarshsailing';
                    else if (activity === 'sportcrabbingcrabberj')
                        fareharborOpts['shortname'] = 'captmarksdolphincruises';
                    else if(activity === 'fireworkscruisecraigcat')
                        fareharborOpts['shortname'] = 'bluewateradventurehhi';
                    else if(activity === 'hiltonheadoutfitters')
                        fareharborOpts['shortname'] = 'hiltonheadoutfitters';
                    else if(activity === 'backwatercatadventure')
                        fareharborOpts['shortname'] = 'bluewateradventurehhi';
                    else if(activity=== 'kayaksmarina'  || activity === 'paddleboardsmarina')
                        fareharborOpts['shortname'] = 'outsidehiltonhead';
					else
                    fareharborOpts['shortname'] = activity;

					if(items[activity][numPeeps].length === 1)
						fareharborOpts['view'] = { 'item': items[activity][numPeeps] };
					else if (items[activity][numPeeps].length > 1) {
						fareharborOpts['view'] = 'items';
					}
					console.log('fareharborOpts', fareharborOpts);
					FH.open(fareharborOpts);
				}

			}
    });

		$('#activity').on('change', function(){
			var activity = $(this).val();

			// If activity is Selected first
			var defaultTypes;
			switch (activity) {
			    case 'bayrunnercharters':
				case 'palmettolagooncharters':
				case 'mightymako':
				case 'hiltonheadislandcharterfishing':
				case 'palmettolagoonfishing':
				case 'saltysanta':
				case 'inshorefishonfishing':
				case 'partyfishingboat':
				case 'outsidehhfishing':
					defaultTypes = 'fishing';
					break;
				case 'hiltonheadislandsailing':
				case 'hiltonheadsailingcharters':
                case 'saltmarshsailing':
					defaultTypes = 'sailing';
					break;
				case 'backwatercatadventure':
				case 'hiltonheadoutfitters':
				case 'kayaksmarina':
				case 'paddleboardsmarina':
				case 'boatrentalmarina':
				case 'kayakcanoeslagoon':
					defaultTypes = 'water-sports';
					break;
				case 'captmarksdolphincruises':
				case 'dolphinseafari':
				case 'dolphinsailingcruiseflyingcircus':
                case 'dolphinecotour':
				case 'slatmarshdolphinsail':
					defaultTypes = 'dolphin-cruises';
					break;
                case 'piratekidzofhiltonhead':
				case 'sportcrabbingcrabberj':
				case 'daufuskieislandtour' :
				case'beachcombingcruise' :
                    defaultTypes = 'nature-and-adventure-cruises';
                    break;
				case 'fireworks-cruises':
				case 'fireworkscruiseseafari':
				case 'july4thfireworkscapthook':
				case 'fireworkscruisecapthook':
				case 'fireworkscruisecraigcat':
				case 'fireworkssailsaltmarsh':
				case 'fireworkssailflyingcircus':
				case 'fireworkspadle':
					defaultTypes = 'fireworks-cruises';
					break;
			};
			$('#activity-type option[value=""]').removeAttr('selected');
			var option = '#activity-type option[value='+ defaultTypes +']';
			$(option).attr('selected','selected');

			// Build Number People dropdown
			$('#num-people').html('');
			$.each(numPeople[activity], function(index, value) {
				$('#num-people').append('<option value="' + value + '">' + value + '</option>');
			});
			$('#num-people').trigger('change');
		});

		$('#activity-type').on('change', function(){
			var activityType = $(this).val();
			// Build Activity dropdown
			$('#activity').html('');
			$.each(activities[activityType], function(index, value) {
				$('#activity').append('<option value="' + index + '">' + value + '</option>');
			});
			$('#activity').trigger('change');
		});
	});
	// end Fareharbor


	// Bike Rentals and More Console
	$('#reservations-console-bikes').each(function(){

		$('#category').on('change', function(){
			var activity = $(this).val();

			// If activity is Selected first
			var defaultLength;
			switch (activity) {
			  case 'bikes':
					defaultLength = 'sevenday';
					break;
				case 'kayaks-lagoon':
				case 'canoes-lagoon':
				case 'kayaks–ocean':
				case 'paddleboards–ocean':
					defaultLength = 'twohour';
					break;
				case 'surfboards':
					defaultLength = 'fullday';
					break;
				case 'beach-chair-umbrellas':
					defaultLength = 'week1chair';
					break;
				case 'jogging-strollers':
					defaultLength = 'weeksingle';
					break;
				case 'fishing-equipment':
					defaultLength = 'fulldaylight';
					break;
			};
			$('#daysBikes option[value=""]').removeAttr('selected');
			var option = '#daysBikes option[value='+ defaultLength +']';
			$(option).attr('selected','selected');
		});

    var bikeRentalsIDs = {
      'bikes': {
				halfday: '127756',
				fullday: '66103',
				threeday: '66104',
				fourday: '66106',
				fiveday: '66108',
				sixday: '66109',
				sevenday: '66110',
				eightday:'66111',
				month: '66126',
          nineday:'150752/calendar/?flow=4438',
          tenday:'150755/calendar/?flow=4438',
          elevenday:'150757/calendar/?flow=4438',
          twelveday:'150758/calendar/?flow=4438',
          thirteenday:'150759/calendar/?flow=4438',
          twoweeks:'150760/calendar/?flow=4438'
			},
			/*'kayaks-lagoon': {
				onehour: '101271',
				twohour: '101271',
				threehour: '101271',
				secondfullday: '101271',
				secondtwoday: '101271',
				secondthreeday: '101271',
				week: '101271',
			},*/
        'kayaks-lagoon': {
            onehour: '155219/calendar/?flow=no',
            oneandhalfhour:'155485/calendar/?flow=no',
            twohour: '155490/calendar/?flow=no',
            threehour: '155497/calendar/?flow=no',
            secondfullday: '155499/calendar/?flow=no',
            secondtwoday: '155506/calendar/?flow=no',
            secondthreeday: '155508/calendar/?flow=no',
            week: '155510/calendar/?flow=no',
        },
			/*'canoes-lagoon': {
				onehour: '43961',
				twohour: '43961',
				threehour: '43961',
				secondfullday: '43961',
				secondtwoday: '43961',
				secondthreeday: '43961',
				week: '43961',
			},*/
        'canoes-lagoon': {
            onehour: '155219/calendar/?flow=no',
            oneandhalfhour:'155485/calendar/?flow=no',
            twohour: '155490/calendar/?flow=no',
            threehour: '155497/calendar/?flow=no',
            secondfullday: '155499/calendar/?flow=no',
            secondtwoday: '155506/calendar/?flow=no',
            secondthreeday: '155508/calendar/?flow=no',
            week: '155510/calendar/?flow=no',
        },
			/*'kayaks–ocean': {
				onehour: '101258',
				twohour: '101258',
				threehour: '101258',
				secondfullday: '101258',
				secondtwoday: '101258',
				secondthreeday: '101258',
				week: '101258',
			},*/
        'kayaks–ocean': {
            onehour: '155541/calendar/?flow=no',
            twohour: '155549/calendar/?flow=no',
            threehour: '155551/calendar/?flow=no',
            secondfullday: '155555/calendar/?flow=no',
            secondtwoday: '155558/calendar/?flow=no',
            secondthreeday: '155560/calendar/?flow=no',
            week: '155561',
        },
			'paddleboards–ocean': {
				onehour: '97139',
				twohour: '97139',
				threehour: '97139',
				secondfullday: '97139',
				secondtwoday: '97139',
				secondthreeday: '97139',
				week: '97139',
			},
			/*'surfboards': {
				secondfullday: '97149',
				secondtwoday: '97149',
				secondthreeday: '97149',
				week: '97149',
			},*/
        'surfboards': {
            secondfullday: '155514/calendar/?flow=no',
            secondtwoday: '155522/calendar/?flow=no',
            secondthreeday: '155523/calendar/?flow=no',
            week: '155525',
        },
			'beach-chair-umbrellas': {
				halfday1chair: '128297',
				fullday1chair: '128298',
				week1chair: '128299',
				halfday2chair: '128294',
				fullday2chair: '128295',
				week2chair: '128296',
			},
			'jogging-strollers': {
				halfdaysingle: '131226',
				fulldaysingle: '131226',
				threedaysingle: '131227',
				weeksingle: '131228',
				halfdaydouble: '131229',
				fulldaydouble: '131230',
				threedaydouble: '131231',
				weekdouble: '131232',
			},
			'fishing-equipment': {
				halfdaylight: '128127',
				fulldaylight: '128149',
				weeklight: '128150',
				halfdaysurf: '128151',
				fulldaysurf: '128152',
				weeksurf: '128153',
			},
		};
		
		var getRentalLengthTypes = function(){
      var $length = $('#reservations-console-bikes #daysBikes');
      var category = $('#reservations-console-bikes #category').val();

      $length.children('option').each(function () {
        var value = $(this).val();
        if (value == '' || bikeRentalsIDs[category][value]) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
		};
		
		getRentalLengthTypes();
		$('#reservations-console-bikes #category').on('change', function () {
      getRentalLengthTypes();
    });

		var $form = $(this);
		$form.find('.btn').on('click', function(e){
			e.preventDefault();
			var category = $('#reservations-console-bikes #category').val(),
					activitylength = $('#reservations-console-bikes #daysBikes').val();

			if(category && activitylength) {
				var fareharborOpts = {
					'ref': 'palmettodunes.com'
				};
				if( category === 'bikes' || category === 'beach-chair-umbrellas' || category === 'jogging-strollers' || category === 'fishing-equipment' )
					fareharborOpts['shortname'] = 'hiltonheadoutfitters-rentals';
				else if(category === 'kayaks-lagoon' || category === 'canoes-lagoon' || category === 'kayaks–ocean' || category === 'paddleboards–ocean' || category === 'surfboards')
					fareharborOpts['shortname'] = 'hiltonheadoutfitters-rentals';
				else
					fareharborOpts['shortname'] = category;

				if(bikeRentalsIDs[category][activitylength].length > 1)
					fareharborOpts['view'] = { 'item': bikeRentalsIDs[category][activitylength] };
				else if (bikeRentalsIDs[category][activitylength].length === 1) {
					fareharborOpts['view'] = 'items';
				}

				//console.log('fareharborOpts', fareharborOpts);
				FH.open(fareharborOpts);
			}
		});
	});

})(jQuery);


function setDatePicker(input) {
	if(($(input).hasClass('date-begin')||$(input).hasClass('date-end'))&&$(input).hasClass('update-blocks')){
		return {
			minDate: ( setDates(input, 1) ),
			maxDate: ( setDates(input, 0) ),
			onClose: function() { getRange(input); $(document.activeElement).blur();}
		};
	} else if(($(input).hasClass('date-begin')||$(input).hasClass('date-end'))&&$(input).hasClass('netbooker')){
		return {
			minDate: ( setDates(input, 1) ),
			maxDate: ( setDates(input, 0) ),
			onSelect: function() { splitDates(input); }
		};
	} else if($(input).hasClass('date-begin')||$(input).hasClass('date-end')) {
		return {
			minDate: ( setDates(input, 1) ),
			maxDate: ( setDates(input, 0) )
		};
	}
}

function setDates(input, minDate) {
	if( ($(input).hasClass('date-begin') && !$('.date-end', $(input).parent().next()).length) ||
		($(input).hasClass('date-end') && !$('.date-begin', $(input).parent().prev()).length) ) {
		return minDate ? new Date() : null;
	} else if( $(input).hasClass('date-begin') ) {
		return minDate ? new Date() : ($('.date-end', $(input).parent().next()).datepicker("getDate") ? new Date($('.date-end', $(input).parent().next()).datepicker("getDate").getTime() - 1000*60*60*24) : null);
	} else if( $(input).hasClass('date-end') ) {
		return minDate ? ($('.date-begin', $(input).parent().prev()).datepicker("getDate") ? new Date($('.date-begin', $(input).parent().prev()).datepicker("getDate").getTime() + 1000*60*60*24) : '+1d' ) : null;
	}
}

function splitDates(input) {
	var start = $(input).hasClass('date-end')?$('.date-begin', $(input).parent().prev()).datepicker("getDate"):$(input).datepicker("getDate");
	var end = $(input).hasClass('date-end')?$(input).datepicker("getDate"):$('.date-end', $(input).parent().next()).datepicker("getDate");

	if($(input).hasClass('date-begin')) {
		$('#arriveMonth').val( $.datepicker.formatDate('mm', $(input).datepicker("getDate")) );
		$('#arriveDay').val( $.datepicker.formatDate('dd', $(input).datepicker("getDate")) );
		$('#arriveYear').val( $.datepicker.formatDate('yy', $(input).datepicker("getDate")) );
	}
	if($(input).hasClass('date-end')) {
		$('#departMonth').val( $.datepicker.formatDate('mm', $(input).datepicker("getDate")) );
		$('#departDay').val( $.datepicker.formatDate('dd', $(input).datepicker("getDate")) );
		$('#departYear').val( $.datepicker.formatDate('yy', $(input).datepicker("getDate")) );
	}

	if(start&&end) {
		var one_day=1000*60*60*24;
		var days = Math.ceil((end.getTime()-start.getTime())/(one_day))
		$('#numberOfNights').val(days);
	}
}

function getRange(el) {
	var form = $(el).parents('form');
	var start = $(el).hasClass('date-end')?$('.date-begin', $(el).parent().prev()).datepicker("getDate"):$(el).datepicker("getDate");
	var end = $(el).hasClass('date-end')?$(el).datepicker("getDate"):$('.date-end', $(el).parent().next()).datepicker("getDate");

	if(start&&end) {
		var one_day=1000*60*60*24;
		var days = Math.ceil((end.getTime()-start.getTime())/(one_day))
		$('#room-requirements').show();
		createBlock(start, days, form);
		createMeeting(start, days, form);
	}
}

function createBlock(date, days, form) {
	$("#block-details", form).remove();
	var tbody = $('<tbody />').attr('id', 'block-details');

	for(var i=0; i<=days; i++) {
		var newDate = $.datepicker.formatDate('D, m/d', new Date(date.getFullYear(), date.getMonth(), date.getDate()+i) );

		tbody.append('<tr>\n\
					<th scope="row">' + newDate + '</th>\n\
					<td><input type="text" class="textfield single" maxlength="5" value="0" id="single_rooms_day_' + (i+1) + '" name="room_block[day_' + (i+1) + '][single]" /></td>\n\
					<td><input type="text" class="textfield dbl" maxlength="5" value="0" id="dbl_rooms_day_' + (i+1) + '" name="room_block[day_' + (i+1) + '][dbl]" /></td>\n\
					<td><input type="text" class="textfield suite" maxlength="5" value="0" id="suite_rooms_day_' + (i+1) + '" name="room_block[day_' + (i+1) + '][suite]" /></td>\n\
					<td><input type="text" class="textfield readonly day-total" readonly="readonly" value="0" id="day_' + (i+1) + '_total" name="room_block[day_' + (i+1) + '][total]" /></td>\n\
					</tr>');
	}
	//Add the totals row
	tbody.append('<tr><th scope="row">Room Total</th>\n\
		<td><input type="text" class="textfield readonly" readonly="readonly" value="0" id="single_rooms_total" name="room_block[single][total]" /></td>\n\
		<td><input type="text" class="textfield readonly" readonly="readonly" value="0" id="dbl_rooms_total" name="room_block[dbl][total]" /></td>\n\
		<td><input type="text" class="textfield readonly" readonly="readonly" value="0" id="suite_rooms_total" name="room_block[suite][total]" /></td>\n\
		<td><input type="text" class="textfield readonly" readonly="readonly" value="0" id="total_rooms" name="total_rooms" /></td>\n\
		</tr>');

	//add tbody to existing table
	$("#block-requirements", form).append(tbody);
	$('td input:not(.readonly)', tbody)
	    .focus(function() {
		if ( $(this).val().length ) {
		    $(this).data('num', $(this).val() )
		}
		$(this).val('');
	    })
	    .blur(function() {
		var num = $(this).data('num')
		if( $(this).val()=='') {
		    if ( !num ) {
			$(this).val(0);
		    } else {
			$(this).val( num )
		    }
		}
	    })
	    .change(function() { calcTotal(this); });
}

function createMeeting(date, days, form) {
	$("#meeting-details", form).remove();
	var tbody = $('<tbody />').attr('id', 'meeting-details');

	// For Each Number of Days that need information a row is created for each
	for(var i=0; i<=days; i++) {
		var newDate = $.datepicker.formatDate('D, m/d', new Date(date.getFullYear(), date.getMonth(), date.getDate()+i) );

		// Create a row with a cell for the day, room type and breakout room info
		tbody.append('<tr>\n\
			<th scope="row">' + newDate + '</th>\n\
			<td><select name="meeting_requirements[day_' + (i+1) + '][room_setup]">\n\
				<option value="Classroom" name="meeting_requirements[day_' + (i+1) + '][room_setup]">Classroom</option>\n\
				<option value="Boardroom" name="meeting_requirements[day_' + (i+1) + '][room_setup]">Boardroom</option>\n\
				<option value="Theater" name="meeting_requirements[day_' + (i+1) + '][room_setup]">Theater</option>\n\
				<option value="U-Shaped" name="meeting_requirements[day_' + (i+1) + '][room_setup]">U-Shaped</option>\n\
				<option value="Hollow Sq." name="meeting_requirements[day_' + (i+1) + '][room_setup]">Hollow Sq.</option>\n\
				<option value="Rounds" name="meeting_requirements[day_' + (i+1) + '][room_setup]">Rounds</option>\n\
				<option value="Reception" name="meeting_requirements[day_' + (i+1) + '][room_setup]">Reception</option>\n\
				</select></td>\n\
			<td><input type="text" class="textfield" maxlength="5" id="breakout_rooms_day_' + (i+1) + '" name="meeting_requirements[day_'+(i+1)+'][breakout_rooms]" /></td>\n\
			<td><input type="text" class="textfield" maxlength="5" id="breakout_people_day_' + (i+1) + '" name="meeting_requirements[day_'+(i+1)+'][breakout_people]" /></td>\n\
			<td><select name="meeting_requirements[day_' + (i+1) + '][breakout_setup]">\n\
				<option value="Classroom" name="meeting_requirements[day_' + (i+1) + '][breakout_setup]">Classroom</option>\n\
				<option value="Boardroom" name="meeting_requirements[day_' + (i+1) + '][breakout_setup]">Boardroom</option>\n\
				<option value="Theater" name="meeting_requirements[day_' + (i+1) + '][breakout_setup]">Theater</option>\n\
				<option value="U-Shaped" name="meeting_requirements[day_' + (i+1) + '][breakout_setup]">U-Shaped</option>\n\
				<option value="Hollow Sq." name="meeting_requirements[day_' + (i+1) + '][breakout_setup]">Hollow Sq.</option>\n\
				<option value="Rounds" name="meeting_requirements[day_' + (i+1) + '][breakout_setup]">Rounds</option>\n\
				<option value="Reception" name="meeting_requirements[day_' + (i+1) + '][breakout_setup]">Reception</option>\n\
				</select></td>\n\
			</tr>');
	}
	//add tbody to existing table
	$("#meeting-requirements", form).append(tbody);
}

function calcTotal(field) {

	var row = $(field).parents('tr'),
	    tbody = $(field).parents('tbody'),
	    rTotal=sTotal=dTotal=suite=total=0;

	$('input:not(.readonly)', row).each(function() {
		rTotal += parseInt($(this).val(), 10);
	});
	$('.day-total', row).val(rTotal);

	if($(field).hasClass('single')) {
		$('input.single', tbody).each(function() {
			sTotal += parseInt($(this).val(), 10);
		});
		$('#single_rooms_total',tbody).val(sTotal);
	}
	if($(field).hasClass('dbl')) {
		$('input.dbl', tbody).each(function() {
			dTotal += parseInt($(this).val(), 10);
		});
		$('#dbl_rooms_total',tbody).val(dTotal);
	}
	if($(field).hasClass('suite')) {
		$('input.suite', tbody).each(function() {
			dTotal += parseInt($(this).val(), 10);
		});
		$('#suite_rooms_total',tbody).val(dTotal);
	}

	$('.day-total', tbody).each(function() {
		total += parseInt($(this).val(), 10);
	});
	$('#total_rooms', tbody).val(total);
}

function openPreview(urlrequest) {
	window.open(urlrequest, 'myWindow', 'width=750,height=520,left=100,top=100,toolbar=No,location=No,scrollbars=No,status=No,resizable=No,fullscreen=No');
	document.getElementById('flashCallBox').value = urlrequest;
}
function extractParamFromUri(uri, paramName) {
  if (!uri) {
    return;
  }
  var uri = uri.split('#')[0];  // Remove anchor.
  var parts = uri.split('?');  // Check for query params.
  if (parts.length == 1) {
    return;
  }
  var query = decodeURI(parts[1]);

  // Find url param.
  paramName += '=';
  var params = query.split('&');
  for (var i = 0, param; param = params[i]; ++i) {
    if (param.indexOf(paramName) === 0) {
      return unescape(param.split('=')[1]);
    }
  }
}

jQuery(function($){
	//$('form').initializeForm();
	$('.validate').formValidate();
	$('.post').postPreview();
	$('.follow-link').initializeFollowing();
	if (typeof jQuery.prototype.msnMap != 'undefined') {
		//$('#map').msnMap({simpleMap:true});
	}
});



/* -------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

//special.js
$(function() {
	if (!(Modernizr.touch)) {
		$('.resort-num a').css('cursor','default');
		$('.resort-num a').bind("click", function(e) {
			e.preventDefault();
		});
	}

	// Animate the scroll to top
	$('.go-top').click(function(e) {
		e.preventDefault();
		$('html, body').animate({scrollTop: 0}, 300);
	});

	//mobile nav toggle
	$('.nav-toggle').click(function(){
		if($(this).hasClass('expanded')){
			$('.mega-menu').slideUp('fast');
			$(this).removeClass('expanded');
			$(this).children().html('<div class="menu-inner">Menu</div>');
		} else {
			//$('.book-wrapper').slideUp('fast');
			$('.console-toggle').removeClass('expanded');
			$('.console-toggle').children().html('<div class="console-menu-inner"><span>Check</span> Availability</div>');
			$('.mega-menu').slideDown('fast');
			$(this).addClass('expanded');
			$(this).children().html('<div class="menu-inner">Close Menu</div>');
		}
	});

	//MegaMenu Desktop

	if ($(window).width() > 1024) {
	    $(".mega-menu > li.primary").click(function(e) {
	    	// prevent link following for primary/section clicks
	    	if ($(e.target).hasClass('primary') || $(e.target).hasClass('secondary-nav')) {
	    		e.preventDefault();
	    	}

	    	var $thisSub = $(this).children('.secondary-nav');

	        if ($(this).hasClass('selected')) {
	            $(".secondary-nav:visible").slideUp(100, function() {
	                $('.bg-subnav').slideUp('fast');
	                $(".mega-menu > .selected").removeClass("selected");
	            });
	        } else {
	            $(".secondary-nav:visible").slideUp(100);
	            $(".mega-menu > .selected").removeClass("selected");
	            $(this).addClass("selected"); // display popup
	            $('.bg-subnav').slideDown('fast', function() {
	            	$thisSub.slideDown(200);
	            });
	        }
	    });
		$('.secondary-nav').click(function(e) {
			 e.stopPropagation();
		});
	}

	//Mobile/Tablet navigation
    $('.secondary-nav .parent > a').on('click',function(e){
    	if ( $(window).width() < 1025){
			e.preventDefault();
			e.stopPropagation();
			$(this).parents('.secondary-nav').find('.active').removeClass('active');
			var activeNav = $(this).siblings('.secondary-nav');
			activeNav.show().addClass('active');
			$(this).parents('.secondary-nav').css({height: 'auto', overflow: 'visible'});
			$('.secondary-nav').height(activeNav.outerHeight()).css('overflow','hidden');
			$('.mega-menu > .primary').hide();
			$(this).parents('.primary').show();
		}
	});

	$('.mega-menu > .primary > a').on('click',function(e){
		if ( $(window).width() < 1025){
			e.preventDefault();
			e.stopPropagation();

			$('.mega-menu').find('.active').removeClass('active');
			var activeNav = $(this).siblings('.secondary-nav');
			activeNav.show().addClass('active');
			$('.mega-menu, .secondary-nav').css({height: 'auto', overflow: 'visible'});
			$('.mega-menu > .primary').hide();
			$(this).parents('.primary').show();
		}
	});

	$('.secondary-nav > .section-name > a').on("click", function(e){
		e.preventDefault();
		if ($(window).width() < 1025) {
			$('.mega-menu').css({height: 'auto', overflow: 'visible'});
			$(this).closest('.active').hide().removeClass('active');
			$(this).closest('.primary').closest('div').show().addClass('active');
			var current = $(this).closest('.primary').closest('div');
			$('.mega-menu > .primary').show();
		}
	});

	$('.secondary-nav > .treelevel-3 > .section-name a').on('click', function(e) {
		e.preventDefault();
		if ($(window).width() < 1025) {
			$('.secondary-nav').css({height: 'auto', overflow: 'visible'});
			$(this).closest('.active').hide().removeClass('active');
			$(this).closest('.primary').closest('div').show().addClass('active');
			var current = $(this).closest('.primary').closest('div');
		}
	});

	//mobile booking console toggle
	$('.console-toggle').click(function(){
		if($(this).hasClass('expanded')){
			$('.book-wrapper').slideUp('fast');
			$(this).removeClass('expanded');
			$(this).children().html('<div class="console-menu-inner"><span>Check</span> Availability</div>');
		} else {
			$('.primary-nav ul.treelevel-1').slideUp('fast');
			$('.nav-toggle').removeClass('expanded');
			$('.nav-toggle').children().html('<div class="menu-inner">Menu</div>');
			$('.book-wrapper').slideDown('fast');
			$(this).addClass('expanded');
			$(this).children().html('<div class="console-menu-inner">Close Check Availability</div>');
		}
	});

	$(".book-wrapper .content-title").click(function () {
        $(".book-wrapper .cont-wrap").slideUp("fast");
        if ($(this).hasClass('expand')) {
            $(this).removeClass('expand');
        }
        $(".content").slideUp("fast");
        if ($(this).next().is(":hidden") == true ) {
            $('.expand').removeClass('expand');
            $(this).next().slideDown("normal")
            $(this).addClass('expand');
        }
    });

	$.urlParam = function(name){
		var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
		if (results==null){
			return null;
		}
		else{
			return results[1] || 0;
		}
	}
	var vacaPromo = $.urlParam('promocode');
	var bikesPromo = $.urlParam('promo');
	var golfPromo = $.urlParam('promo');
	$('#vacation input#promo-code').val(vacaPromo);
	$('#golf input#bc-promo-golf').val(golfPromo);
	$('#bikes input#promo-codeBikes').val(bikesPromo);
    if (window.location.hash == "#VACATION") {
    	$('body').removeClass('golf-console');
    	$('body').removeClass('bikes-console');
	    $('body').removeClass('fareharbor-console');
	    $('body').addClass('vacation-console');
	} else if (window.location.hash == "#GOLF") {
    	$('body').removeClass('vacation-console');
    	$('body').removeClass('bikes-console');
    	$('body').removeClass('fareharbor-console');
	    $('body').addClass('golf-console');
	} else if (window.location.hash == "#BIKES") {
    	$('body').removeClass('vacation-console');
    	$('body').removeClass('golf-console');
    	$('body').removeClass('fareharbor-console');
	    $('body').addClass('bikes-console');
	} else if (window.location.hash == "#FAREHARBOR") {
    	$('body').removeClass('vacation-console');
    	$('body').removeClass('golf-console');
    	$('body').removeClass('bikes-console');
    	$('body').addClass('fareharbor-console');
	}

	var navisRFP = $.urlParam('p');
	$('.form-rfp input#promo').val(navisRFP);

    if ($('body').hasClass('vacation-console') && !$('body').hasClass('home')) {
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf .cont-wrap').removeClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike .cont-wrap').removeClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor .cont-wrap').removeClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation .cont-wrap').addClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation .content-title').addClass('expand');
	} else if ($('body').hasClass('golf-console')) {
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation .cont-wrap').removeClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike .cont-wrap').removeClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor .cont-wrap').removeClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf .cont-wrap').addClass('open-console');
	} else if ($('body').hasClass('bikes-console')){
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf .cont-wrap').removeClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation .cont-wrap').removeClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor .cont-wrap').removeClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike .cont-wrap').addClass('open-console');
	}  else if ($('body').hasClass('fareharbor-console')){
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf .cont-wrap').removeClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation .cont-wrap').removeClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike .cont-wrap').removeClass('open-console');
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor .cont-wrap').addClass('open-console');
	}

	if ($('body').hasClass('home')) {
		$('.book-wrapper').removeClass('closed');
	}

	if ( $(window).width() < 1025 ) {

		if ($('body').hasClass('golf-console')) {
			$('.lava').css({left: $('.tab-golf').position().left, width: $('.tab-golf').width()});

			$('.book-wrapper').removeClass('closed');
			$('.book-wrapper .book-box .check-avail-wrapper').css('min-height', '490px');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-golf, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-golf a').attr('style', '');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-item, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper a').removeClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-golf, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-golf a').addClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf').css('display', 'block');

		} else if ($('body').hasClass('bikes-console')){

			$('.lava').css({left: $('.tab-bike').position().left, width: $('.tab-bike').width()});

			$('.book-wrapper').removeClass('closed');
			$('.book-wrapper .book-box .check-avail-wrapper').css('min-height', '490px');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-bike, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-bike a').attr('style', '');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-item, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper a').removeClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-bike, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-bike a').addClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike').css('display', 'block');

		} else if ($('body').hasClass('fareharbor-console')){
			$('.lava').css({left: $('.tab-fareharbor').position().left, width: $('.tab-fareharbor').width()});

			$('.book-wrapper').removeClass('closed');
			$('.book-wrapper .book-box .check-avail-wrapper').css('min-height', '490px');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor a').attr('style', '');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-item, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper a').removeClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor a').addClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor').css('display', 'block');
		} else if ($('body').hasClass('wed-home')){
			//$('.lava').css({left: $('.tab-fareharbor').position().left, width: $('.tab-fareharbor').width()});

			$('.book-wrapper').removeClass('closed');
			$('.book-wrapper .book-box .check-avail-wrapper').css('min-height', '490px');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor a').attr('style', '');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-item, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper a').removeClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor a').addClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor').css('display', 'block');
		} else {
			if ($('.lava').length > 0) {
				$('.lava').css({left: $('.tab-vacation').position().left, width: $('.tab-vacation').width()});
			}

			$('.book-wrapper').removeClass('closed');
			$('.book-wrapper .book-box .check-avail-wrapper').css('min-height', '490px');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-vacation, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-vacation a').attr('style', '');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-item, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper a').removeClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-vacation, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-vacation a').addClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation').css('display', 'block');
		}

		var tabContainers = $('div.booking-console-inner > div');

	    $('div.booking-tab-wrapper span.tab-item a').click(function(e) {
	    	e.preventDefault();
	    	var leftPos = Math.ceil($(this).parent().position().left - 3),
	    		tabWidth = Math.ceil($(this).parent().width() + 3);
	    	$('body.interior .lava').show();
	    	$('body.interior .book-wrapper .book-box .check-avail-wrapper').css('min-height', '410px').slideDown('slow');
	    	$('body.interior .book-wrapper .book-box .check-avail-wrapper .booking-console').show();

    		$('.lava').stop().animate({left: leftPos }, {duration:400});
    		$('.lava').css('width', tabWidth);

			$('div.booking-tab-wrapper span.tab-item a').removeClass('active');
			$(this).addClass('active');

	        $(tabContainers).hide().filter(this.hash).fadeIn('slow');

	        return false;
	    });
	} else {
		if ($('body').hasClass('golf-console')) {
			$('.lava').css({left: $('.tab-golf').position().left, width: $('.tab-golf').width()});
			$('body.interior .lava').show();

			$('.book-wrapper').removeClass('closed');
			$('.book-wrapper .book-box .check-avail-wrapper').css('min-height', '270px');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-golf, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-golf a').attr('style', '');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-item, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper a').removeClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-golf, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-golf a').addClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf').css('display', 'block');

		} else if ($('body').hasClass('bikes-console')){

			$('.lava').css({left: $('.tab-bike').position().left, width: $('.tab-bike').width()});
			$('body.interior .lava').show();

			$('.book-wrapper').removeClass('closed');
			$('.book-wrapper .book-box .check-avail-wrapper').css('min-height', '270px');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-bike, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-bike a').attr('style', '');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-item, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper a').removeClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-bike, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-bike a').addClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike').css('display', 'block');

		} else if ($('body').hasClass('fareharbor-console')){
			$('.lava').css({left: $('.tab-fareharbor').position().left, width: $('.tab-fareharbor').width()});
			$('body.interior .lava').show();

			$('.book-wrapper').removeClass('closed');
			$('.book-wrapper .book-box .check-avail-wrapper').css('min-height', '270px');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor a').attr('style', '');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-item, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper a').removeClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor a').addClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor').css('display', 'block');
		} else if ($('body').hasClass('wed-home')){
			//$('.lava').css({left: $('.tab-fareharbor').position().left, width: $('.tab-fareharbor').width()});

			$('.book-wrapper').removeClass('closed');
			$('.book-wrapper .book-box .check-avail-wrapper').css('min-height', '270px');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor a').attr('style', '');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-item, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper a').removeClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-fareharbor a').addClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor').css('display', 'block');
		} else {
			if ($('.lava').length > 0) {
				$('.lava').css({left: $('.tab-vacation').position().left, width: $('.tab-vacation').width()});
				$('body.interior .lava').show();
			}

			$('.book-wrapper').removeClass('closed');
			$('.book-wrapper .book-box .check-avail-wrapper').css('min-height', '270px');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-vacation, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-vacation a').attr('style', '');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-item, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper a').removeClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-vacation, .book-wrapper .book-box .check-avail-wrapper .booking-tab-wrapper .tab-vacation a').addClass('active');

			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-bike').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-golf').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-fareharbor').css('display', 'none');
			$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation').css('display', 'block');
		}


		var tabContainers = $('div.booking-console-inner > div');

	    $('div.booking-tab-wrapper span.tab-item a').click(function(e) {
	    	e.preventDefault();

	    	var leftPos = Math.ceil($(this).parent().position().left - 3),
	    		tabWidth = Math.ceil($(this).parent().width() + 3);

	    	if($('body.interior .lava').is(':visible') && $(this).hasClass('active')) {
	    		$('.book-wrapper').removeClass('closed');
		    	if ( $('body').not('.golf-overview') || $('body').not('.course') ) {
			    	$('body.interior .lava').hide();
			    	$('body.interior .book-wrapper .book-box .check-avail-wrapper').animate({ 'min-height': '0' }, 1000);
			    	$('body.interior .book-wrapper .book-box .check-avail-wrapper .booking-console').hide();
		    	}

	    		$('.lava').stop().animate({left: leftPos}, {duration:400});
	    		$('.lava').css({width: tabWidth });

	    		$(this).removeClass('active');

		      $(tabContainers).show().filter(this.hash).fadeIn('slow');

	    	} else {
	    		$('.book-wrapper').removeClass('closed');
		    	if ( $('body').not('.golf-overview') || $('body').not('.course') ) {
			    	$('body.interior .lava').show();
			    	$('body.interior .book-wrapper .book-box .check-avail-wrapper').css('min-height', '270px').slideDown('slow');
			    	$('body.interior .book-wrapper .book-box .check-avail-wrapper .booking-console').show();
		    	}

		    	$('.lava').stop().animate({left: leftPos}, {duration:400});
    			$('.lava').css({width: tabWidth });

		    	$('div.booking-tab-wrapper span.tab-item a').removeClass('active');
	      	$(this).addClass('active');

	      	$(tabContainers).hide().filter(this.hash).fadeIn('slow');
	    	}

	      return false;
	    });
	}

	if ( ($(window).width() > 764) && ( !($('body').hasClass("golf-overview") )) && ( !($('body').hasClass("course") )) && ( !($('body').hasClass("vacation-console") )) && ( !($('body').hasClass("activities-overview") )) && ( !($('body').hasClass("bikes-console") )) && ( !($('body').hasClass("golf-console") )) && ( !($('body').hasClass("fareharbor-console") )) ) {
		$('body.interior .lava').hide();
		$('body.interior .book-wrapper .book-box .check-avail-wrapper').css('min-height','0px');
		$('body.interior .book-wrapper .book-box .check-avail-wrapper .booking-console').hide();
		$('body.interior .booking-tab-wrapper .tab-item').removeClass('active');
		$('body.interior .booking-tab-wrapper .tab-item a').removeClass('active');
	}

	// Listen for orientation changes
	window.addEventListener("orientationchange", function() {

		//alert('screen width: '+$(window).width()+' screen height: '+screen.height);
		if ( ($(window).width() > 764 && $(window).width() < 1025) ) {
			$('.booking-tab-wrapper a.active').trigger('click');
		}
		var activeObj = $('.book-wrapper .booking-tab-wrapper .tab-item a.active').parent();
		if (!$('body').hasClass('wed-home') && activeObj.length > 0){
			if ($('.lava').length > 0) {
				$('.lava').css({
					left: $('.book-wrapper .booking-tab-wrapper .tab-item a.active').parent().position().left,
					width: $('.book-wrapper .booking-tab-wrapper .tab-item a.active').parent().width()
				});
			}
		}

	}, false);

	$(window).on('resize', function() {
		var activeObj = $('.book-wrapper .booking-tab-wrapper .tab-item a.active').parent();
		if (!$('body').hasClass('wed-home') && activeObj.length > 0) {
		    $('.lava').css({
		    	left: activeObj.position().left,
		    	width: activeObj.width()
		    });
		}
	});

    $('.mobile-tab a').click(function(e){
		e.preventDefault();
		mobiCont=$(this).parent().siblings('.cont-mobile');
		if ($(mobiCont).is(':visible')) {
			$(this).parent().removeClass('active');
			$(mobiCon).hide();
		}
		else {
			$('.cont-mobile').hide();
			$('.mobile-tab').removeClass('active');
			$(this).parent().attr('class','active');
			$(mobiCont).show();
		}
	});

    // Booking Console Promo Code Flyout
	$('#promo-trigger').click(function(e){
		e.preventDefault();
		if ($('.flyout-wrapper').is(':visible')) {
			$(this).removeClass('active');
			$('.flyout-wrapper').hide();
		}
		else {
			$(this).attr('class','active');
			$('.flyout-wrapper').show();
		}
	});

	//booking console villas/homes location change
	$('select.location-villas, select.location-homes, select.view-homes').hide();
	$('.booking-console .content-vacation select#type').on('change', function() {
		var selVal = $(this).val();
		if ( selVal == '2') { //if villas selected show villas dropdown hide others
			$('select.location-all, select.location-homes, select.view-homes').hide();
			$('select.location-all, select.location-homes, select.view-homes').removeAttr('name');
			$('select.location-villas, select.view-all').show();
			$('select.view-all').val('Any').attr('name', 'viewid');
			$('select.location-villas').val('Any Location').attr('name', 'locationid');
		} else if ( selVal == '1' ) { //if homes selected show homes dropdown hide others
			$('select.location-all, select.location-villas, select.view-all').hide();
			$('select.location-all, select.location-villas, select.view-all').removeAttr('name');
			$('select.location-homes, select.view-homes').show();
			$('select.view-homes').val('Any').attr('name', 'viewid');
			$('select.location-homes').val('Any Location').attr('name', 'locationid');
		} else { //if no type selected show all locations dropdown
			$('select.location-villas, select.location-homes, select.view-homes').hide();
			$('select.location-villas, select.location-homes, select.view-homes').removeAttr('name');
			$('select.location-all, select.view-all').show();
			$('select.view-all').val('Any').attr('name', 'viewid');
			$('select.location-all').val('Any Location').attr('name', 'locationid');
		}
	});


	if ( $('body').hasClass('gf-course') ) {
		$('.booking-console select#courses option[value=89]').attr('selected', 'selected');
	}

	if ( $('body').hasClass('ah-course') ) {
		$('.booking-console select#courses option[value=88]').attr('selected', 'selected');
	}

	$('a[href*="activegolf"]').each(function() {
		$(this).attr('onClick','GallusGolfMobileWeb.anchorMobileOrDefault(this);return false;');
	});


	var nextDay;

	var button_arrive = $('#button-arrive'), arrive = $('.date-begin').pickadate({
			format: 'mm/dd/yyyy',
			formatSubmit: 'mm/dd/yyyy',
			min: true,
			today: 'Today',
			clear: 'Close', //hijacking clear to close and not clear -- see line 150 of picker.js
			onClose: function(){
        $(document.activeElement).blur()
    	} //prevent calendar from showing on browser tab change
		}),

		golf_button_arrive = $('#golf-button-arrive'), golfArrive = $('.golf-date-begin').pickadate({
			format: 'mm/dd/yyyy',
			formatSubmit: 'mm-dd-yyyy',
			min: true,
			today: 'Today',
			clear: 'Close', //hijacking clear to close and not clear -- see line 150 of picker.js
			onClose: function(){
        $(document.activeElement).blur()
    	} //prevent calendar from showing on browser tab change
		}),

		golf_menu_button_arrive = $('#golf-menu-button-arrive'), golfMenuArrive = $('.golf-menu-date-begin').pickadate({
			format: 'mm/dd/yyyy',
			formatSubmit: 'mm-dd-yyyy',
			min: true,
			today: 'Today',
			clear: 'Close', //hijacking clear to close and not clear -- see line 150 of picker.js
			onClose: function(){
        $(document.activeElement).blur()
    	} //prevent calendar from showing on browser tab change
		}),

		button_depart = $('#button-depart'), depart = $('.date-end').pickadate({
			format: 'mm/dd/yyyy',
			formatSubmit: 'mm/dd/yyyy',
			today: '',
			clear: 'Close', //hijacking clear to close and not clear -- see line 150 of picker.js
			onClose: function(){
        $(document.activeElement).blur()
    	} //prevent calendar from showing on browser tab change
		});

	var arrive_picker = arrive.pickadate('picker'),
		golf_arrive_picker = golfArrive.pickadate('picker'),
		golf_menu_arrive_picker = golfMenuArrive.pickadate('picker'),
		depart_picker = depart.pickadate('picker');

		if (typeof golf_menu_arrive_picker === 'undefined') {
			golf_menu_arrive_picker = $('.datepicker').pickadate({
				format: 'mm/dd/yyyy',
				formatSubmit: 'mm-dd-yyyy',
				min: true,
				today: 'Today',
				clear: 'Close'
			});
		}

	if(arrive_picker && depart_picker) {
		if ( arrive_picker.get('value') ) {
			depart_picker.set('min', arrive_picker.get('select'));
		}
		if ( golf_arrive_picker.get('value') ) {
			depart_picker.set('min', golf_arrive_picker.get('select'));
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
				let arrP = new Date(arrive_picker.get('select').year, arrive_picker.get('select').month, arrive_picker.get('select').date);
				arrP.setDate(arrP.getDate() + 3);
				depart_picker.set('min', arrP);
			}
		});

		golf_arrive_picker.on('set', function(event) {
			if ( event.select ) {
				depart_picker.set('min', golf_arrive_picker.get('select'));
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

		golf_arrive_picker.on('click', function(e) {
			e.preventDefault();
			golf_arrive_picker.open();
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
			console.log('calendar clicked');
			event.preventDefault();
		    if (arrive_picker.get('open')) {
		        arrive_picker.close()
		    }
		    else {
		        arrive_picker.open()
		    }
		    event.stopPropagation()
		});

		golf_button_arrive.on( 'click', function(event) {
			//console.log('golf calendar clicked');
			event.preventDefault();
		    if (golf_arrive_picker.get('open')) {
		        golf_arrive_picker.close()
		    }
		    else {
		        golf_arrive_picker.open()
		    }
		    event.stopPropagation()
		});

		golf_menu_button_arrive.on( 'click', function(event) {
			//console.log('golf calendar clicked');
			event.preventDefault();
		    if (golf_menu_arrive_picker.get('open')) {
		        golf_menu_arrive_picker.close()
		    }
		    else {
		        golf_menu_arrive_picker.open()
		    }
		    event.stopPropagation()
		});

		button_depart.on( 'click', function(event) {
			event.preventDefault();
		    if (depart_picker.get('open')) {
		        depart_picker.close()
		    }
		    else {
		        depart_picker.open()
		    }
		    event.stopPropagation()
		});
	}

	$('a[href*="#target=blank"]').click(function(e) {
		e.preventDefault();
		var url = $(this).attr('href');
		window.open(url, '_blank');
	});

	//Home Masthead grayscale/blur sprite
	$('.home-masthead .home-cycle .slide').each(function(){
		var imgSrc = $(this).children('.slide-img').find('img').attr('src');
		$(this).children('.slide-img').css('background-image','url(' + imgSrc + ')');
		$(this).children('.cycle-caption').children('.cycle-wrap').hover(function(){
			$('.slide-img').toggleClass('active');
			$('.mast-overlay').toggleClass('active');
		});
	});

	//Video Masthead Slider grayscale/blur sprite
	$('.video-masthead .video-cycle .slide').each(function(){
		var imgSrc = $(this).children('.slide-img').find('img').attr('src');
		$(this).children('.slide-img').css('background-image','url(' + imgSrc + ')');
		$(this).children('.cycle-caption').children('.cycle-wrap').hover(function(){
			$('.slide-img').toggleClass('active');
			$('.mast-overlay').toggleClass('active');
		});
	});

	$('.video-cycle').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,
		fade: true,
		asNavFor: '.sliderSidebar',
		autoplay: true,
		autoplaySpeed: 3000
	});
	$('.sliderSidebar').slick({
		slidesToShow: 5,
		slidesToScroll: 1,
		asNavFor: '.sliderMain',
		dots: false,
		centerMode: false,
		focusOnSelect: true,
		vertical: true,
		arrows: false
	});

	function myHandler(e) {
		$('.sliderMain').slick('slickPlay');
	}

	$('.contain').objectFit('contain');
	$('.cover').objectFit({ type: 'cover', hideOverflow: true});


	//Home Masthead down button
	$(".go-down").on("click", function(e) {
        e.preventDefault();
        $("body, html").animate({scrollTop: $($('.book-wrapper')).offset().top}, 600);
    });

	//Homepage Parallax Section
	$('.parallax-section[data-type="background"]').each(function(){
		var $window = $(window);
		var $bgobj=$(this); //assign the object
		$(window).scroll(function(){
			var yPos= -( ($window.scrollTop()-$bgobj.offset().top)/$bgobj.data('speed'));
			var coords = '50%' + yPos + 'px';
			$bgobj.css({backgroundPosition: coords});
		});
	});

	if ( $(window).width() > 720 ) {
		//Homepage Explore Push Carousel
		var carousel = $('.home-push-items');

		$('.home-push-items').jcarousel({
			wrap: 'circular'
		});

		$('.home-push-items .carousel-prev').click(function() {
		    $('.home-push-items').jcarousel('scroll', '-=1');
		});

		$('.home-push-items .carousel-next').click(function() {
		    $('.home-push-items').jcarousel('scroll', '+=1');
		});

		$('.home-push-items').touchwipe({
  			wipeLeft: function() {
    			$('.home-push-items').jcarousel('next');
  			},
  			wipeRight: function() {
    			$('.home-push-items').jcarousel('prev');
  			},
  			// min_move_x: 20,
  			// min_move_y: 20,
  			preventDefaultEvents: false
		});
	}

	$(".push-carousel .push-wrapper").hover(function(){
		$(this).children('.push-inset').find('.overlay').slideToggle();
	},function(){
		$(this).children('.push-inset').find('.overlay').slideToggle();
	});

	$(".golf-course-wrapper .push-inset").hover(function(){
		$(this).parent('.golf-course-wrapper').find('.overlay').slideToggle();
	},function(){
		$(this).parent('.golf-course-wrapper').find('.overlay').slideToggle();
	});

	//Footer Push
	if (  !(Modernizr.touch) ) {
		$("ul.push-footer li").hover(function(){
			$(this).find('.overlay').css('display','block');
		},function(){
			$(this).find('.overlay').css('display','none');
		});
	}

	//Instagram Feed - Footer
	$("a.group").fancybox({
		'nextEffect'	:	'fade',
		'prevEffect'	:	'fade',
		'overlayOpacity' :  0.8,
		'overlayColor' : '#000000',
		'arrows' : false,
		'hideOnOverlayClick' : true,
	});
// Check if videojs is defined
if (typeof videojs == "function") {
  if ($("body").hasClass("rtj-course")) {
    //Golf Overview Webcam
    /*if ( $('body').hasClass('golf-overview') ) {
		videojs("example_video_1", {
			"controls": true,
			"autoplay": true,
			"preload": "auto",
		});
	}*/

    videojs("example_video_1", {
      controls: true,
      autoplay: true,
      preload: "auto"
    });
  }

	if ( $('body').hasClass('webcam') ) {
		videojs("example_video_1", {
			"controls": true,
			"autoplay": true,
			"preload": "auto"
		});
	}

	if ( $('body').hasClass('webcam') ) {
		videojs("example_video_2", {
			"controls": true,
			"autoplay": true,
			"preload": "auto"
		});
	}

	$('.fancybox-media').fancybox({
		helpers : {
			media : {}
		}
	});

	$('#web-cam-push').fancybox();
	$('.golf-overview-webcam').fancybox({
		helpers : {
			media : {}
		}
	});

	if ( ($('body').hasClass('webcam')) && ($(window).width() < 721) ) {

		$('.webcam-fancybox').fancybox({
			maxWidth : 250,
			maxHeight : 200,
			fitToView : false,
			autoSize : false
		});
	} else {
		$('.webcam-fancybox').fancybox();
	}

	if ( $('body').hasClass('dunes-house') ) {
		videojs("example_video_2", {
			"controls": true,
			"autoplay": true,
			"preload": "auto"
		});
	}
}
// End check if videojs is defined

	$('.dunes-house .webcam-fancybox').fancybox();

	$('.fancybox').fancybox({
		helpers : {
    		title : {
    			type : 'inside'
    		}
    	}
	});

    $('.advertSetHoriz .fancybox-trigger').fancybox({
        padding : 3
    });

	$('.lightbox a').fancybox();

	$('.tab-pic a').click(function(e){
		e.preventDefault();
		$(this).parent().parent().children('.tab-map').removeClass('active');
		$(this).parent().addClass('active');
		$(this).parent().parent().parent('.map-img-container').children('#hole-map').hide();
		$(this).parent().parent().parent('.map-img-container').children('#hole-image').show();
	});

	$('.tab-map a').click(function(e){
		e.preventDefault();
		$(this).parent().parent().children('.tab-pic').removeClass('active');
		$(this).parent().addClass('active');
		$(this).parent().parent().parent('.map-img-container').children('#hole-image').hide();
		$(this).parent().parent().parent('.map-img-container').children('#hole-map').show();
	});

	if ( $(window).width() > 1024 ) {
		$(".slideup-nav .tertiary li").hover(function(){
			$(this).find('.masthead-popup-inner').stop().slideToggle('slow');
		},function(){
			$(this).find('.masthead-popup-inner').stop().slideToggle('slow');
		});
	}

	if ( ((Modernizr.touch) && $(window).width() < 480 )) {
		//Homepage Masthead
		$('.home-masthead .home-cycle').cycle({
			slides: '.slide',
			fx: 'fade',
			speed: 2000,
			timeout: 3000,
			pauseOnHover: true,
			swipe: true,
			next: '#next',
			prev: '#prev',
		});
	} else {
		//Homepage Masthead
		$('.home-masthead .home-cycle').cycle({
	  		slides: '.slide',
  			fx: 'fade',
			speed: 3000,
			timeout: 5000,
			pauseOnHover: true,
			hideNonActive: true,
			next: '#next',
			prev: '#prev',
		});

		$('.home-masthead .home-cycle').on('cycle-after',function(e, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag){
	        $(outgoingSlideEl).find('.cycle-caption').animate({left: 500, opacity: 0}, 700);
	    	$(incomingSlideEl).find('.cycle-caption').animate({left: 0, opacity: 1}, 2500);
		});

		$('.home-masthead .home-cycle').on('cycle-before',function(e, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag){
	        $(outgoingSlideEl).find('.cycle-caption').animate({left: 500, opacity: 0}, 700);
	    	$(incomingSlideEl).find('.cycle-caption').animate({left: 0, opacity: 1}, 2500);
		});
	}

	if ( ((Modernizr.touch) && $(window).width() < 480 )) {
		//Video Masthead Slider
		$('.video-masthead .video-cycle').cycle({
			slides: '.slide',
			fx: 'fade',
			speed: 2000,
			timeout: 3000,
			pauseOnHover: false,
			swipe: true,
			next: '#next',
			prev: '#prev',
		});
	} else {
		//Video Masthead Slider
		$('.video-masthead .video-cycle').cycle({
	  		slides: '.slide',
  			fx: 'fade',
			speed: 3000,
			timeout: 10000,
			pauseOnHover: false,
			hideNonActive: true,
			next: '#next',
			prev: '#prev',
		});

		$('.video-masthead .video-cycle').on('cycle-after',function(e, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag){
	        $(outgoingSlideEl).find('.cycle-caption').animate({left: 500, opacity: 0}, 700);
	    	$(incomingSlideEl).find('.cycle-caption').animate({left: 0, opacity: 1}, 2500);
		});

		$('.video-masthead .video-cycle').on('cycle-before',function(e, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag){
	        $(outgoingSlideEl).find('.cycle-caption').animate({left: 500, opacity: 0}, 700);
	    	$(incomingSlideEl).find('.cycle-caption').animate({left: 0, opacity: 1}, 2500);
		});
	}

	if ( $(window).width() < 765 ) {
		//Overview Page Masthead
		$('.overview-masthead .home-cycle').cycle({
	  		slides: '.slide',
	  		fx: 'fade',
			speed: 3000,
			timeout: 5000,
			pauseOnHover: true,
			hideNonActive: true,
			next: '#next',
			prev: '#prev',
			pager: '.mobile-pager',
			pagerTemplate: '<span>&bull;</span>',
		});
	} else {
		//resort overview, activities overview, vacations overview, shelter cove overview masthead rotations
		$('.overview-masthead .home-cycle').cycle({
	  		slides: '.slide',
	  		fx: 'fade',
			speed: 3000,
			timeout: 5000,
			pauseOnHover: true,
			hideNonActive: true,
			next: '#next',
			prev: '#prev',
			pager:  '#nav',
			pagerTemplate: ''
		});
	}

	//golf overview masthead
	$('.golf-overview .overview-masthead .home-cycle').after('<ul id="nav"></ul>').cycle({
  		slides: '.slide',
  		fx: 'fade',
		speed: 3000,
		timeout: 5000,
		pauseOnHover: true,
		hideNonActive: true,
		next: '#next',
		prev: '#prev',
		pager:  '#nav',
		pagerTemplate: '<li><a href="#"><span>{{children.0.children.0.alt}}</span><img src="{{children.0.children.0.src}}" /></a></li>'
	});


	$('.overview-masthead .home-cycle').on('cycle-after',function(e, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag){
        $(outgoingSlideEl).find('.cycle-caption').animate({left: 500, opacity: 0}, 700);
	    $(incomingSlideEl).find('.cycle-caption').animate({left: 0, opacity: 1}, 2500);
	});

	$('.overview-masthead .home-cycle').on('cycle-before',function(e, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag){
        $(outgoingSlideEl).find('.cycle-caption').animate({left: 500, opacity: 0}, 700);
	    $(incomingSlideEl).find('.cycle-caption').animate({left: 0, opacity: 1}, 2500);
	});

	//Signup widget
	var widget = $('#signup-widget');
	var tab = $('#signup-tab');
	var w = window.innerWidth;

	if (readCookie('signup-scroll')) {

	}

	$(document).on('scroll', function() {
	    if( $(this).scrollTop() >= 5 ) {
	        $(document).off('scroll');
	        createCookie('signup-scroll',true,1)
			if ($('#signup-widget').css('left') == '-100%'){
		       //your code
		    } else {
		    	widget.animate({ left: -w + 'px' }, 1500);
		    }
			tab.fadeIn(600);
	    }
	});

	$.extend({
		getUrlVars: function(){
	    	var sgVars = [], hash;
	    	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    	for(var i = 0; i < hashes.length; i++)
		    	{
					hash = hashes[i].split('=');
					sgVars.push(hash[0]);
					sgVars[hash[0]] = hash[1];
				}
				return sgVars;
			},
			getUrlVar: function(name){
			return $.getUrlVars()[name];
		}
	});

	if (!readCookie('signup') && widget.length != 0) {
		widget.animate({ left: "0" }, 1500);
		var slide = function() {
			if (widget.position().left > 0) {
				widget.animate({ left: w + 'px' });
				tab.fadeIn(600);
			}
		}
		window.setTimeout(slide , 10000);
	}
	$('#signup-widget .close').on("click", function(e) {
		createCookie('signup',true,1)
		e.preventDefault();
		widget.animate({ left: -w + 'px' }, 1500);
		tab.fadeIn(600);
	});

	$(window).on('resize', function(){
		var win = $(this); //this = window
		if (win.height() < 764) {
			tab.find('a span').text('Get on the list');
		}
		if (win.width() >= 765) {
			tab.find('a span').text('Sign Up Offer');
		}
	});

	if( $(window).width() < 764) {
		tab.find('a span').text('Get on the list');
	}

	tab.on("click", function(e) {
		e.preventDefault();
		if( $(window).width() < 764) {
			widget.slideToggle();
			$(this).find('a span').toggleClass('active');
		} else {
			widget.animate({ left: "0" }, 1500);
			$(this).fadeOut(600);
		}
	});


	if (!readCookie('signed_UP')) {
		tab.removeClass('destroy-signup');

	} else {
		widget.hide();
		tab.hide();
	}
	if ($.getUrlVar('__sgtarget') != null) {
	    createCookie('signed_UP',true,365)
	    $('#signup-widget').hide();
	    $('.masthead-signup').addClass('signed-up');
	    widget.delay(3000).animate({ left: -w + 'px' }, 1500);
	    tab.hide();
	}

	$('.primary-nav ul li').click( function() {
		widget.animate({ left: -w + 'px' });
		tab.fadeIn(600);
	});

	function createCookie(name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	}

	function readCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}

	function eraseCookie(name) {
		createCookie(name,"",-1);
	}

	$("#sgE-1891399-1-26-element").focus( function() {
	    if ($(this).val() == "Email")
	        $(this).val("")
	});

	//Homepage Featured Events
	$('body.home .events-page').cycle({
		slides: '.events-wrapper',
		timeout: 0,
		allowWrap:  false,
		speed: 1000,
		fx: 'scrollHorz',
		hideNonActive: true,
		next: '.e-next',
		prev: '.e-prev',
	});

	//Homepage Featured Blog Posts
	$('.golf-overview .eb-right ul, body.home .eb-right ul').cycle({
		slides: 'li',
		timeout: 0,
		allowWrap:  false,
		speed: 1000,
		fx: 'scrollHorz',
		hideNonActive: true,
		next: '.b-next',
		prev: '.b-prev',
	});

	$('.recent-news .multiList').cycle({
		slides: 'li',
		timeout: 0,
		allowWrap: false,
		speed: 1000,
		fx: 'scrollHorz',
		next: '.b-next',
		prev: '.b-prev',
	});

	$('.blog-list ul').cycle({
		slides: 'li',
		timeout: 0,
		allowWrap:  false,
		speed: 1000,
		fx: 'scrollHorz',
		hideNonActive: true,
		next: '.b-next',
		prev: '.b-prev',
	});

	$('.blog-listing-sidebar ul').cycle({
		slides: 'li',
		timeout: 0,
		allowWrap:  true,
		speed: 1000,
		fx: 'scrollHorz',
		hideNonActive: true,
		next: '.b-next',
		prev: '.b-prev',
	});

	$('.course-container').cycle({
		slides: '.hole-info',
	    fx:     'fade',
	    speed:  'fast',
	    allowWrap:  false,
	    timeout: 0,
	    pager:  '.golf-course-tabs',
	    pagerTemplate: '<a href=#> {{slideNum}} </a>',
	    next: '.b-next',
		prev: '.b-prev',
	});

	function setHole(index) {
		$('.course-container').cycle('goto', index);
	}

	$('.course-controls select').on('change', function() {
		var dataHole = $(this).val() - 1;
		setHole(dataHole);
	});

	$('.course-controls select option').first().attr('selected', 'selected');

	//Golf Group Carousel
	$('.content-wrapper .golf-course-carousel .home-cycle').after('<ul id="course-nav">').cycle({
		slides: '.slide',
		fx: 'scrollHorz',
		speed: 'fast',
		timeout: 0,
		swipe: true,
		next: '#next',
		prev: '#prev',
		pager:  '#course-nav',
		pagerTemplate: '<li><a href="#"><span>{{children.0.children.0.alt}}</span><img src="{{children.0.children.0.src}}" /></a></li>'
	});

	$('.overview-carousel #next').addClass('car-next');
	$('.overview-carousel #prev').addClass('car-prev');

	//Golf Overview Push Cycle
	$('.overview-carousel .home-cycle').cycle({
		slides: '.slide',
		fx: 'scrollHorz',
		speed: 'fast',
		timeout: 0,
		swipe: true,
	    next: '.car-next',
		prev: '.car-prev',
	    pager:  '#push-nav',
	    pagerTemplate: ''
	});

	if ( $(window).width() > 1025 ) {
		$('.push-expand-wrapper a').click(function(e){
			e.preventDefault();

			var descDiv = $(this).parent().children('.push-expand-description');
			$('.push-middle-content').hide().empty();

			if ( $(this).parent().hasClass('active') ) {
				$(this).parent().removeClass('active');
				$('.push-middle-content').hide(400).empty();
			} else {
				$('.push-expand-wrapper').removeClass('active');
				//$('.push-middle-content').hide(400).empty();

				$(this).parent().addClass('active');
				$(descDiv).clone().appendTo('.push-middle-content');

				$('.push-middle-content').slideDown(400);
			}
		});
	} else if ( $(window).width() < 1025 ) {
		$('.push-expand-wrapper a.trigger').click(function(e){
			e.preventDefault();

			$('.top-row').css('height',"auto");
			$('.push-expand-description').hide();

			if ( $(this).parent().hasClass('active') ) {
				$(this).parent().removeClass('active');
				$(this).parent().children('.push-expand-description').slideUp(400);
			} else {
				$('.push-expand-wrapper').removeClass('active');
				$('.push-expand-description').slideUp(400);

				$(this).parent().addClass('active');
				$(this).parent().children('.push-expand-description').slideDown(400);

				if ($(this).parent().hasClass('top-row')) {
					$('.top-row').css('height',"395px");
				}
			}
		});
	}

	//Activities Overview Featured Events
	$('body.golf-overview .widget-featured-events .amax-module-body, body.golf-instruction .widget-featured-events .amax-module-body, body.golf-schools .widget-featured-events .amax-module-body, body.golf-lessons .widget-featured-events .amax-module-body, body.tennis-overview .widget-featured-events .amax-module-body, body.tennis-tournament .widget-featured-events .amax-module-body, body.dunes-house .cove-events .amax-module-body, body.cove-overview .cove-events .amax-module-body').after('<div class="events-controls"> <button type="button" class="e-prev"><em class="alt">prev</em></button> <button type="button" class="e-next"><em class="alt">next</em></button> </div>').cycle({
		slides: '.events-wrapper',
		timeout: 0,
		allowWrap:  false,
		speed: 1000,
		fx: 'scrollHorz',
		hideNonActive: true,
		next: '.e-next',
		prev: '.e-prev',
	});

	//Specials Masthead
	$('.specials-masthead .home-cycle').cycle({
  		slides: '.slide',
  		fx: 'fade',
		speed: 1000,
		timeout: 0,
		swipe: true,
		hideNonActive: true,
		next: '#next',
		prev: '#prev'
	});

	//special offers expand/collapse toggle
	$('.amax-specialoffer a.package-toggle').click(function(e){
		e.preventDefault();
		$('.media_body').hide();

		if ( $(this).parents('article').hasClass('active') ) {
			$(this).parents('article').removeClass('active');
			$(this).parent().children('.media_body').slideUp(400);
		} else {
			$('.media').removeClass('active');
			$('.media_body').slideUp(400);

			$(this).parents('article').addClass('active');
			$(this).parent().children('.media_body').slideDown(400);
		}
	});

	$('.amax-specialoffer a.btn-close-details').click(function(e){
		e.preventDefault();
		$('.media_body').hide();
		$('article').removeClass('active');
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


	var packageChannel = $.getUrlVar("tag");

	$('.amax-specialoffer article').each(function(index, value){

		if ( $(".amax-specialoffer article:visible").length < 1) {
			$('.content-col-left .amax-module-body').append('<p>There are currently no special packages available.</p>');
		}

	});


	$('.amax-specialoffer article').removeClass('odd').removeClass('even');
	$('.amax-specialoffer article:visible:odd').addClass('even');
	$('.amax-specialoffer article:visible:even').addClass('odd');

	$('.mobile-filters .offer-filter').change(function(){
	    var url = $(this).val();
	    window.location = url;
	});

	if ($('#ccm-page-controls-wrapper').length<1) {

		$('.gallery-push-wrapper').find('.pinterest img').each(function() {
			var url = window.location.href,
				media = $(this).attr('src'),
				desc = $(this).attr('alt');
			var pinit =	'<a href="//www.pinterest.com/pin/create/button/'+
				'?url='+url+''+
				'&media='+media+''+
				'&description='+desc+'" '+
				'data-pin-do="buttonPin" '+
				'data-pin-config="none">'+
				'<img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" />'+
			'</a>';
			if($(this).parent('a').length) {
				$(this).parent('a').after(pinit);
			} else {
				$(this).after(pinit);
			}
		});

		$('.weddings-overview').find('.pinterest img').each(function() {
			var url = window.location.href,
				media = $(this).attr('src'),
				desc = $(this).attr('alt');
			var pinit =	'<a href="//www.pinterest.com/pin/create/button/'+
				'?url='+url+''+
				'&media='+media+''+
				'&description='+desc+'" '+
				'data-pin-do="buttonPin" '+
				'data-pin-config="none">'+
				'<img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" />'+
			'</a>';
			if($(this).parent('a').length) {
				$(this).parent('a').after(pinit);
			} else {
				$(this).after(pinit);
			}
		});
	};

	$('a.pdf-link').each(function(){
		$(this).attr('target','_blank');
	});

	$(".signup-wrap .sg-input-text, .offers-signup .sg-input-text").focus( function() {
        if ( $(this).val()=="Enter Email Address") {
            $(this).val('');
        }
    });

    $(".signup-wrap .sg-input-text, .offers-signup .sg-input-text").blur( function() {
        if ( $(this).val()=="") {
            $(this).val('Enter Email Address');
        }
    });

    $('#HTMLBlock15090').on('click', '#sgE-1546186-1-173-10845', function(){
    	$('#sgE-1546186-1-191-box, #sgE-1546186-1-190-box').toggle();
    });


    //Events
    $('.calendar-carousel .title a').click(function(e){
    	e.preventDefault();
    });

    $('.calendar-carousel .title').on('click',function(){
		$('.event-content').hide();

		var pos = $(this).position();
			pos = pos.top - 5;
		$('.jcarousel-list-vertical').animate({top: -pos}, 800, function() {});

		if ( $(this).parent().hasClass('active') ) {
			$(this).parent().removeClass('active');
			$(this).parent().children('.event-content').slideUp(400);
		} else {
			$('.amax-resortcalendar article.event').removeClass('active');
			$('.event-content').slideUp(400);

			$(this).parent().addClass('active');
			$(this).parent().children('.event-content').slideDown(400);
		}
	});

	var eventsChannel = $.getUrlVar("eventTag");

	$('.calendar-carousel .events-carousel li').each(function(index, value){

		$(this).removeClass('hidden-event').removeClass('visible-event');

		//console.log( $(this).attr('class') );
		if ( $.getUrlVar("eventTag") == null || $.getUrlVar("eventTag") == 'undefined') {
			$('.amax-resortcalendar .events-carousel li').show();
		} else if ( $(this).hasClass(eventsChannel) ) {
			$(this).show();
			$('.amax-resortcalendar .events-carousel li:visible').addClass('visible-event');
		} else {
			$(this).hide();
			$('.amax-resortcalendar .events-carousel li:hidden').addClass('hidden-event').removeClass('visible-event');
			$('.hidden-event').remove();
		}
	});

	$(".calendar-carousel").each(function(){
		if ( $(".calendar-carousel .events-carousel li:visible").length < 1 ) {
			$('.events-carousel').addClass('no-events');
			if ( !($('body').hasClass('events-permalink')) ) {
				$('.content-col-right .amax-module-body').append('<p>There are currently no events available.</p>');
			}
		}
	});

	$('.events-filter li').each(function(index,value){
		//console.log( $(this).attr('class') );
		$(this).removeClass('active');
		if ( $.getUrlVar("eventTag") == null || $.getUrlVar("eventTag") == 'undefined') {
			$('.events-filter li.events-all').addClass('active');
		} else if ( $(this).attr('id') == eventsChannel ) {
			$(this).addClass('active');
		} else {
			$(this).removeClass('active');
		}
	});

	var mod466StartDate = $.getUrlVar('Module%5B466%5D%5BstartDate%5D');
    var mod476StartDate = $.getUrlVar('Module%5B476%5D%5BstartDate%5D');
 	var mod476EndDate = $.getUrlVar('Module%5B476%5D%5BendDate%5D');
    var mod476limit = $.getUrlVar('Module%5B476%5D%5Blimit%5D');

	$('.events-filter li a').click(function(e){
		e.preventDefault();
		eventLinkChannel = $(this).parent().attr('id');
	    window.location = '/resort/hilton-head-events/?eventTag='+eventLinkChannel+'&Module%5B466%5D%5BstartDate%5D='+mod466StartDate+'&Module%5B476%5D%5BstartDate%5D='+mod476StartDate+'&Module%5B476%5D%5BendDate%5D='+mod476EndDate+'&Module%5B476%5D%5Blimit%5D=100';
	});

	$('.amax-monthlycalendar .next-month').click(function(){
		//e.preventDefault();
		buttonHref = $(this).attr('href');
	    $(this).attr('href', buttonHref.replace(/&?Module%5B476%5D%5Bdate%5D=\d+/, ''));
	});

	$('.amax-monthlycalendar .prev-month').click(function(){
		//e.preventDefault();
		buttonHref = $(this).attr('href');
	    $(this).attr('href', buttonHref.replace(/&?Module%5B476%5D%5Bdate%5D=\d+/, ''));
	});



	var months = [];
    var years = [];

    var currentYear = new Date().getFullYear();
    var currentMonth = new Date().getMonth() - 1;
    var selectVal;
    var endSelectVal;

    for (var i = 0; i < 2; i++) {
      years.push(currentYear+i);
    }

    $(".calendar-months option").each(function() {
        months.push($(this).text());
    });

    $(".calendar-months").empty();

    for (var i = 0; i < years.length; i++) {
        for (var x = 0; x < months.length; x++) {
            if(x > currentMonth || i > 0){

            	currMonthVal=parseInt([x])+1;

            	if ( currMonthVal < 10){
            		currMonthVal = "0"+currMonthVal;
            	}

            	selectVal=years[i]+'-'+currMonthVal+'-01';
            	endSelectVal=years[i]+'-'+currMonthVal+'-30';

              $(".calendar-months").append('<option name="'+endSelectVal+'" value="'+selectVal+'">'+months[x]+' '+years[i]+'</option>');
            }
        }
    }

    $('.monthCal-dropdown select.calendar-months').change(function() {
    	//$('.amax-monthlycalendar table td.hasEvent').removeClass('dayClicked');
    	selVal=$(this).find('option:selected').val();
    	endSelVal=$(this).find('option:selected').attr('name');
    	$(this).find('option:selected').attr('selected', 'selected');
    	$('.calStartDate').val(selVal);
    	$('.calEndDate').val(endSelVal);
    	$('.monthCal-dropdown').submit();
	});

	$('.events-carousel').jcarousel({
		item: '.visible-event',
		vertical: true,
		scroll: 5,
		wrap: 'circular'
	});

	//$('body.golf-overview .widget-featured-events').append('<a href="/resort/hilton-head-events/?eventTag=288" class="view-cal white-btn">View Calendar</a>');

	$('.monthCal-dropdown select.calendar-months').find('option').each(function() {
		eventDateParam = $.getUrlVar("Module%5B466%5D%5BstartDate%5D");
		eventDateOpt = $(this).val();
		if ( eventDateOpt == eventDateParam ) {
			$(this).attr('selected', 'selected');
		}
    });

    $('.amax-monthlycalendar table .hasEvent').each(function(){
    	eventDayParam = $.getUrlVar("Module%5B476%5D%5Bdate%5D");
    	eventTdId = $(this).attr('id');
    	//console.log(eventTdId);

    	if ( eventDayParam !== null || eventDayParam == 'undefined') {
    		eventDay=eventDayParam.slice(-2);
    		//console.log(eventDay);
    		if ( eventDay == eventTdId) {
    			$(this).addClass('dayClicked');
    			$('.dateHeader span').append(' '+eventDay+',');
    		}
    	}
    });


    $('.press-archives').on('change', function(){
    	window.location = $(this).val();
    });

    $('.press-gallery-picker select').on('change', function(){
    	window.location = $(this).val();
    });

    $('.mobile-selector').each(function(){
    	var el = $(this);
    	el.on('change', function(){
    		window.location = el.val();
    	});
    });

    $('.blog-nav-trigger').on('click', function(e){
    	e.preventDefault();
    	var el = $(this);
    	if(el.hasClass('active')) {
    		el.removeClass('active').text('Menu');
    		el.next().slideUp();
    	} else {
    		el.addClass('active').text('Close');
    		el.next().slideDown();
    	}
    });
    // reset the blog nav
    $('window').on('resize', function(){
    	if($(this).width() > 1024) {
    		$('#amax-menu-blognav').removeAttr('style');
    		console.log('removed?');
			$('.blog-nav-trigger').removeClass('active').text('Menu');
			$('.blog-signup').each(function(){
				$('.email-signup').attr('placeholder', '');
			});
    	} else {
			$('.blog-signup .email-signup').each(function(){
				var el = $(this);
				el.find('input').attr('placeholder', 'Subscribe by Email');
			});
    	}
    });

    //mobile blog categories set selected state

    $('.category-listing select.mobile-selector').find('option').each(function() {
		categoryParam = $.getUrlVar("Module[631][categoryId]");
		catOptId = $(this).attr('id');
		if ( catOptId == categoryParam ) {
			$(this).attr('selected', 'selected');
		}
    });


    // $('.push-footer .push-photos a, .push-photos .push-inset a').on('click', function(e){
    //     e.preventDefault();
    //     console.log('blah');
    //     //$.catlady_videos('/ajaxGallery/662');
    //     $.catlady_videos('/custom/a4_palmettodunes/50.json', 'amax-662');
    // });


	//booking console popuplate fields if params exists
	var beArrive = $.getUrlVar("arrive");
	var beDepart = $.getUrlVar("depart");
	var beTypeid = $.getUrlVar("typeid");
	var beBedrooms = $.getUrlVar("bedrooms");
	//var bePromo = $.getUrlVar("promocode");
	var beViewid = $.getUrlVar("viewid");
	var beLocationid = $.getUrlVar("locationid");
	var beSleeps = $.getUrlVar("sleeps");

	if (beArrive !=='' || beArrive !==null) {
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation input#arrival').val(beArrive);
	}
	if (beDepart !=='' || beDepart !==null) {
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation input#departure').val(beDepart);
	}
	if (beTypeid !=='' || beTypeid !==null) {
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation select#type').val(beTypeid);
	}
	if (beBedrooms !=='' || beBedrooms !==null) {
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation select#bedrooms').val(beBedrooms);
	}
	/*if (bePromo !=='' || bePromo !==null) {
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation input#promo-code').val(bePromo);
	}*/
	if (beViewid !=='' || beViewid !==null) {
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation select#view').val(beViewid);
	}
	if (beLocationid !=='' || beLocationid !==null) {
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation select#location').val(beLocationid);
	}
	if (beSleeps !=='' || beSleeps !==null) {
		$('.book-wrapper .book-box .check-avail-wrapper .booking-console .booking-console-inner .content-vacation select#sleeps').val(beSleeps);
	}

});


function mycarousel_initCallback(carousel) {
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves cursor over the clip.
	$(".push-wrapper").hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
}

function golf_courses_highlight(){
	//robert trent jones course
	if (document.URL.indexOf('hilton-head-golf-courses') > -1){
		$('ul.tertiary .pos1 a:last').css('background','#1e4267');
		$('ul.tertiary .pos1 a:last').css('border-bottom','3px solid #febe10');
	}
	//george fazio course
	if (document.URL.indexOf('golf-courses-hilton-head') > -1){
		//arthur hills course
		if (document.URL.indexOf('golf-courses-hilton-head-island') > -1){
			$('ul.tertiary .pos3 a:last').css('background','#1e4267');
			$('ul.tertiary .pos3 a:last').css('border-bottom','3px solid #febe10');
		} else {
			$('ul.tertiary .pos2 a:last').css('background','#1e4267');
			$('ul.tertiary .pos2 a:last').css('border-bottom','3px solid #febe10');
		}
	}

}

$(document).ready(function(){
	golf_courses_highlight();
});

$(document).ready(function(){
	$('iframe[name="google_conversion_frame"]').hide();
});

$(document).ready(function(){
	$('#mobile-blog-category-selector select, #mobile-blog-tags select').change(function(){
		if ($(this).val() != ''){
			window.location = $(this).val();
		}
	});
});

$(document).ready(function(){
	if (document.URL.indexOf('golf/hilton-head-golf') == -1){ return false; }
	var i = 0;
	$('.overview-masthead #nav a').each(function(){
		i++;
		if (i == 1){
			$(this).click(function(){
				$('#reservations-console-golf #courses').val('91');
			});
		}
		if (i == 2){
			$(this).click(function(){
				$('#reservations-console-golf #courses').val('89');
			});
		}
		if (i == 3){
			$(this).click(function(){
				$('#reservations-console-golf #courses').val('88');
			});
		}
	});
});

$(document).ready(function(){
	if (document.URL.indexOf('golf/golf-courses-hilton-head') == -1){ return false;	}
	$('#reservations-console-golf #courses').val('89');
});

$(document).ready(function(){
	if (document.URL.indexOf('golf/golf-courses-hilton-head-island') == -1){ return false;	}
	$('#reservations-console-golf #courses').val('88');
});

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

$(document).ready(function(){
	var golfpromo = getParameterByName('golfpromo');
	$('#promo-codeGolf').val(golfpromo);

	//header phone icon to make clickable
	if($(window).width() < 768) {
		$('.resort-num .header-phone').click(function(e) {
			var PhoneNumber = $(this).text();
			PhoneNumber = PhoneNumber.replace('ShowNavisNCPhoneNumber();','');
			PhoneNumber = PhoneNumber.replace(' ','');
			PhoneNumber = PhoneNumber.replace('(','1');
			PhoneNumber = PhoneNumber.replace(')','');
			PhoneNumber = PhoneNumber.replace('-','');
			PhoneNumber = PhoneNumber.substring(0, 11); //only use first # if navis available
			window.location.href="tel://"+PhoneNumber;
		});
	}
});

//Captcha validate
$(document).ready(function(){
	setTimeout(
  function()
  {
    if ($('#g-recaptcha-response').length > 0) {
			$('#g-recaptcha-response').attr('required' , 'required');
			$('#g-recaptcha-response').attr('data-parsley-trigger' , 'change');
		}
  }, 2000);
});

//web-cam-push height responsive
$(window).on('load', function() {
	if($('#web-cam-push').length > 0) {
		webCamHeight();
	}
});

$(window).on('resize', function() {
	if($('#web-cam-push').length > 0) {
		webCamHeight();
	}
});

function webCamHeight() {
		var $parent = $('#web-cam-push').closest('.course-push-lower');
		var $closestImg = $parent.find('img');
		var $height = $closestImg.height();
		$parent.find('#web-cam-push').height($height);
}
