(function($) {
    $.QueryString = (function(a) {
        if (a == "") return {};
        var b = {};
        for (var i = 0; i < a.length; ++i)
        {
            var p=a[i].split('=');
            if (p.length != 2) continue;
            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return b;
    })(window.location.search.substr(1).split('&'))
})(jQuery);

function getURLParameter(url, name) {
    return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
}

function updateQueryStringParameter(uri, key, value) { // thanks stackoverflow :)
	var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
	var separator = uri.indexOf('?') !== -1 ? "&" : "?";
	if (uri.match(re)) {
		return uri.replace(re, '$1' + key + "=" + value + '$2');
	}
	else {
		return uri + separator + key + "=" + value;
	}
}

$(function() {
    function getParameterByName(name) {
	    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

    function setButtonHeight() {
	    $('.events-grid-view .event-category .category-label').each(function() {
	    	var labelHeight = $(this).outerHeight();
	    	var button = $(this).siblings('.category-button').find('.button');
	    	button.css({'height': labelHeight, 'line-height': labelHeight+'px'});
	    });
	}

	var buffer = false;

	$(window).unbind('resize').bind('resize', function() {
        if (buffer !== false) {
            clearTimeout(buffer);
        }
        buffer = setTimeout(function(){
        	setButtonHeight();
        }, 300);
    });

	var NEEDS_DATA_REFRESH = false;

	var gridEvent = $("#grid-event").html();
	var calendarCurrentMonth = $("#calendar-current-month").html();
	var eventsDatesBar = $("#events-dates-bar").html();
	var eventIconFilter = $("#event-icon-filter").html();
	var columnEvent = $("#column-event").html();
	var categoryListing = $("#category-listing").html();
	var categoryList = $("#category-list").html();
	var overlayEvent = $("#overlay-event").html();

	var gridEventTemplate = Handlebars.compile(gridEvent);
	var calendarCurrentMonthTemplate = Handlebars.compile(calendarCurrentMonth);
	var eventsDatesBarTemplate = Handlebars.compile(eventsDatesBar);
	var eventIconFilterTemplate = Handlebars.compile(eventIconFilter);
	var columnEventTemplate = Handlebars.compile(columnEvent);
	var categoryListingTemplate = Handlebars.compile(categoryListing);
	var categoryListTemplate = Handlebars.compile(categoryList);
	var overlayEventTemplate = Handlebars.compile(overlayEvent);

	var topicsWithEvents = [];

	// build the grid listing view
	$.fn.buildEventGrid = function(data){
		var $self = $(this);
		var gridEventHtml = gridEventTemplate(data).replace(/[\u200B]/g, '');
		$self.html(gridEventHtml);
	};

	// calendar view and append the category dots to dates
	$.fn.buildCalendarColumn = function(context){
		var $self = $(this);
		var calendarCurrentMonthHtml = calendarCurrentMonthTemplate(context).replace(/[\u200B]/g, '');
		$self.html(calendarCurrentMonthHtml);
		$self.buildCalendarCategories(context);
		$self.buildIconFilter(context);
	};

	// build the months bar
	$.fn.buildMonthBar = function(context) {
		var $self = $(this);
		var eventsDatesBarHtml = eventsDatesBarTemplate(context).replace(/[\u200B]/g, '');
		$self.html(eventsDatesBarHtml);
		$self.find('.events-months').slick({
			respondTo: 'min',
			dots: false,
			swipe: false,
			touchMove: false,
			infinite: false,
			mobileFirst: true,
			slidesToShow: 3,
			slidesToScroll: 3,
			prevArrow: '<button class="months-prev months-arrow" aria-label="Previous" role="button" id="prevmonth"><i class="fa fa-chevron-left"></i><span>Previous</span></button>',
			nextArrow: '<button class="months-next months-arrow" aria-label="Next" role="button" id="nextmonth"><i class="fa fa-chevron-right"></i><span>Next</span></button>',
			responsive: [{
				breakpoint: 1025,
				settings: {
					slidesToShow: 6,
					slidesToScroll: 6
				}
			}]
		});

		$(window).on('resize', function() {
			setTimeout(function() {
				$self.find('.events-months').slick('slickGoTo', 0);
			}, 100);
		});
	};

	// build the colored category dots
	$.fn.buildIconFilter = function(context) {
		var $self = $(this);
		var curCat = '';
		curCat = $('.events-bar .category-list a.active').text();
		curCat = curCat.replace(/\s+/g, '-').toLowerCase();
		// console.log(curCat);
		var eventIconFilterTemplate = Handlebars.compile(eventIconFilter);
		var eventIconFilterHtml = eventIconFilterTemplate(context.items).replace(/[\u200B]/g, '');
		$self.find('.event-date .event-icons').html(eventIconFilterHtml);
		$self.find('.event-date .event-icons').each(function() {
			var eventDate = $(this).data('event-date');
			$(this).children().each(function() {
				var eventStartTime = $(this).data('event-start-time');
				if (eventDate != eventStartTime) {
					$(this).remove();
				}
			});
			$(this).children().each(function() {
				$('[class="' + $(this).attr('class') + '"]:not(:first)').remove();
			});
		});
	};

	// build the event list view
	$.fn.buildEventColumn = function(context) {
		var $self = $(this);
		var columnEventTemplate = Handlebars.compile(columnEvent);
		var columnEventHtml = columnEventTemplate(context).replace(/[\u200B]/g, '');
		$self.html(columnEventHtml);		
		$self.each(function() {
			var eventDate = $self.find('.column-events-wrapper').data('event-date');
			
			$(this).find('.event-list-item').each(function() {
				if (!$(this).hasClass(eventDate)) {
					$(this).hide();
				}
			});
		});
	};

	// build the category listing under the calendar in list view
	$.fn.buildCalendarCategories = function(context) {
		var $self = $(this);
		var categoryListingTemplate = Handlebars.compile(categoryListing);
		var categoryListingHtml = categoryListingTemplate(context.filterTopics).replace(/[\u200B]/g, '');
		$self.append(categoryListingHtml);
	};

	$.fn.buildCategoryFilters = function(context) {
		var $self = $(this);
		var categoryListTemplate = Handlebars.compile(categoryList);
		var categoryListHtml = categoryListTemplate(context.filterTopics).replace(/[\u200B]/g, '');
		var topic = getURLParameter(window.location, 'shs_stream_topic');
		$self.append(categoryListHtml);
		if(topic !== undefined && topic !== null && topic !== '') {
			$self.find('.active').removeClass('active');
			$self.find('[data-category-id="' + topic + '"]').addClass('active');
		}
	};

	$.fn.refreshCalendar = function(JSON_PATH){
		var $self = $(this),
			$testData,
			$grid = $self.find('.events-grid-view');

		$grid.masonry('destroy').find('.event, .gutter-sizer').remove();

		$self.addClass('pre').find('.calendar').hide()
		$self.find('.grid').show();
		
		$self.find('.no-events-month').hide();

		$.getJSON(JSON_PATH, function(data) {
			var context = data;
			$self.find('.grid').removeAttr('style');
			$self.find('.calendar').removeAttr('style');
			$grid.buildEventGrid(context);
			$self.find('.column-calendar').buildCalendarColumn(context);	
			$self.find('.column-listing').buildEventColumn(context);
	    	$self.find('.category-list').buildCategoryFilters(context);

	    	$('.events-grid-view, .events-calendar-view .column-events-wrapper').each(function(){
                if ($.isEmptyObject(context.items)) {
                    $self.find('.no-events-month').show();
                } else {
                    $self.find('.no-events-month').hide();
                }
            });

			if (overlayEvent) {
				$(document).off('click', '.event .category-button .button, .column-events-wrapper a.event-list-item');
				$(document).on('click', '.event .category-button .button, .column-events-wrapper a.event-list-item', function(e) {
					e.preventDefault();
					var itemId = $(this).data('item-id');
					$('.overlay-event-window').each(function() {
						$('.overlay-event-window').modal('hide');
						$(this).remove();
					});
					//console.log('context', context);
					for (var i = 0; i < context.items.length; i++){
						if (context.items[i].id == itemId){
							var overlayEventTemplate = Handlebars.compile(overlayEvent);
							var overlayEventHtml = overlayEventTemplate(context.items[i]).replace(/[\u200B]/g, '');
							/* @TODO Assign .content-inner dynamically */
							$('.content-inner').append(overlayEventHtml);
							$('.overlay-event-window').modal('show');
						}
					}
				});
				$(document).off('click', '.overlay-event-window .event-back');
				$(document).on('click', '.overlay-event-window .event-back', function(e) {
					e.preventDefault();
					$('.overlay-event-window').each(function() {
						$('.overlay-event-window').modal('hide');
						$(this).remove();
					});
				});
			}
		}).done(function(){
			initCalendarEvents();
		});
	};

	$.fn.buildEventCalendar = function(JSON_PATH) {
		var $wrapper = $(this);
		$.getJSON(JSON_PATH, function(data) {
			var context = data;
			// console.log(data);
			$wrapper.find('.events-dates-bar').buildMonthBar(context);
			$wrapper.find('.events-grid-view').buildEventGrid(context);
			$wrapper.find('.column-calendar').buildCalendarColumn(context);	
			$wrapper.find('.column-listing').buildEventColumn(context);
	    	$wrapper.find('.category-list').buildCategoryFilters(context);

	    	// check to see which topics actually have events
	    	var oldestDateToUse = shs_stream_date.replace(/-/g, '');
	    	if (context.items) {
		    	for (var i = 0; i < context.items.length; i++){
		    		if (context.items[i].topic) {
		    			if (context.items[i].end.day_time <= oldestDateToUse && context.items[i].topic.length) {
			    			var topics = context.items[i].topic;
			    			$.each(topics, function(j, topic){
			    				var topicName = topic.treeNodeTopicName;
			    				topicName = topicName.replace(/\s+/g, '-').toLowerCase();
			    				if ($.inArray(topicName, topicsWithEvents) === -1) {
			    					topicsWithEvents.push(topicName);
			    				}
			    			});
			    		}
		    		}
		    	}
		    }

			if (overlayEvent) {
				//$(document).on('click', '.event .category-button .button, .column-events-wrapper a.event-list-item, a.button', function(e) {
				$(".event .category-button .button, .column-events-wrapper a.event-list-item").click(function(e) {
					e.preventDefault();
					var itemId = $(this).data('item-id');
					$('.overlay-event-window').each(function() {
						$('.overlay-event-window').modal('hide');
						$(this).remove();
					});
					for (var i = 0; i < context.items.length; i++){
						if (context.items[i].id == itemId){
							var overlayEventTemplate = Handlebars.compile(overlayEvent);
							var overlayEventHtml = overlayEventTemplate(context.items[i]).replace(/[\u200B]/g, '');
							/* @TODO Assign .content-inner dynamically */
							$('.content-inner').append(overlayEventHtml);
							$('.overlay-event-window').modal('show');
						}
					}
				});

				$(document).on('click', '.overlay-event-window .event-back', function(e) {
					e.preventDefault();
					$('.overlay-event-window').each(function() {
						$('.overlay-event-window').modal('hide');
						$(this).remove();
					});
				});
			}

		})
		.done(function() {
			initCalendarEvents();
			initEvents();
		});

	}

	function initCalendarEvents() {
		$('.events-listing.grid').css({ 'height': '0', 'overflow': 'hidden' }); // this isn't happening at all

		var elems;
		var calWrap = $('.cal-wrap');
	    var grid = $('.events-grid-view');
	    var calendar = $('.events-calendar-view');

	    $('.events-grid-view .event').each(function() {
	    	var elem = $(this);
	    	elems = elems ? elems.add(elem) : elem;
	    });

    	if (jQuery().imagesLoaded) {
	        imagesLoaded(grid, function() {
	        	if(grid.data('masonry') !== undefined)
	        		grid.masonry('destroy');
	        	grid.append(elems);
	        	grid.masonry({"itemSelector": ".event", "columWidth": ".event-sizer", "gutter": ".gutter-sizer", "percentPosition": "true"}).masonry('appended', elems).masonry('reloadItems').masonry('layout');
			    $('.buttons').show();
    			$('.cal-wrap').removeClass('pre');
			    $('.events-listing.grid').removeAttr('style');
			    //  below is silly attempt to fix bug where .event-date for last event in month would flash
			    //  before grid load, but won't work by changing class - WTF?
			    $('.event .event-date').css({'opacity': '1'});
	        });
    	}

	    $.fn.updateMonthLinks = function(categoryID){
			var $el = $(this);

			if(categoryID === undefined || categoryID === null || categoryID === '')
				categoryID = ''

	    	$el.find('a').each(function(){
	    		var $link = $(this);
	    		var href = $link.attr('href');

	    		$link.attr('href', updateQueryStringParameter(href, 'shs_stream_topic', categoryID));
	    	});
	    };

	    $(".events-calendar-view .slide-down").on('click',function(e) {
	        e.preventDefault();
	        var top = $('.column-listing').position().top;
			if ($('.wrapper').scrollTop() != 0) {
	            $('html, body, .wrapper').animate({
	                scrollTop: top
	            }, 500);
	        }
	    });

		$('.events-listing .category-list li:first-child a').on('click', function(e){
			$('.events-grid-view, .events-calendar-view .column-events-wrapper').each(function(){
		        if ($(this).find('.filter').length < 1) {
		            $(this).find('.no-events-category').show();
		        } else {
		            $(this).find('.no-events-category').hide();
		        }
		    });
		});

		$('.events-listing .category-list li a').each(function() {
			var trigger = $(this).data('trigger');
			if(trigger)
			{
				var className = $(this).attr('class');
				$('.'+trigger).each(function() {
					$(this).addClass(className);
				});
				if ($.inArray(trigger, topicsWithEvents) === -1) {
					//$(this).closest('li:not(.all-cats)').addClass('no-events');
				}
			}
		});

		// Calendar toggle

	    $('.events-switch input[name="view"]').off('click').on('change', function(){

	        if ($(this).val() == 'calendar') {
	        	Cookies.set('toggle', 'calendar');
	        	if (Cookies.get('toggle') == 'calendar') {
		            $('.events-grid-view').hide();
		            calendar.fadeIn();
		            calWrap.addClass('cal-view');
		            calWrap.removeClass('grid-view');
	        	}
	        } else {
	        	Cookies.set('toggle', 'grid');
	        	if (Cookies.get('toggle') == 'grid') {
		            calendar.hide();
		            $('.events-grid-view').fadeIn();
		            calWrap.addClass('grid-view');
		            calWrap.removeClass('cal-view');
		        }
		        var grid = $('.events-grid-view');
					grid.masonry({"itemSelector": ".event", "columWidth": ".event-sizer", "gutter": ".gutter-sizer", "percentPosition": "true"}).masonry('reloadItems').masonry('layout');
				setButtonHeight();
	        }

	        $('.events-calendar-view .column-events-wrapper').each(function(){
		        if ($(this).find('.filter:visible').length < 1) {
		            $(this).find('.no-events-category').show();
		        } else {
		            $(this).find('.no-events-category').hide();
		        }
		    });

		    $('.events-grid-view').each(function(){
		        if ($(this).find('.filter').length < 1) {
		            $(this).find('.no-events-category').show();
		        } else {
		            $(this).find('.no-events-category').hide();
		        }
		    });

	    });
		
		if (!Cookies.get('toggle')) {
			Cookies.set('toggle', 'grid');
			/*
			var grid = $('.events-grid-view');
				grid.masonry('destroy');
				grid.masonry({"itemSelector": ".event", "columWidth": ".event-sizer", "gutter": ".gutter-sizer", "percentPosition": "true"});
				grid.masonry();
			$('.events-grid-view').fadeIn();
			*/
		}

		if (Cookies.get('toggle') == 'calendar') {
	    	$('#calendar').prop('checked', true);
	    	calendar.show();
	    	calWrap.addClass('cal-view');
		    calWrap.removeClass('grid-view');
	    } else {
	    	$('#grid').prop('checked', true);
	    	$('.events-grid-view').show();
	    	calWrap.addClass('grid-view');
		    calWrap.removeClass('cal-view');
	    }

	    $('.events-switch input[name="view"]').trigger('change load');

	    // Displayed events

	    $('.events-calendar-view .column-events-wrapper').each(function(){
	        if ($(this).find('.filter').length < 1 || $(this).find('.filter:visible').length < 1) {
	            $(this).find('.no-events-category').show();
	        } else {
	            $(this).find('.no-events-category').hide();
	        }
	    });

	    $('.events-grid-view').each(function(){
	        if ($(this).find('.filter').length < 1 || $(this).find('.filter:visible').length < 1) {
	            $(this).find('.no-events-category').show();
	        } else {
	            $(this).find('.no-events-category').hide();
	        }
	    });

	    // Permalink back

	    $('.event-back').off('click').on('click', function(e) {
	    	e.preventDefault();
	    	var href = $(this).attr('href');
	    	if (document.referrer == "") {
			    window.location.href = href;
			} else {
			    window.history.back()
			}
	    });

	    // Msc

	    setButtonHeight();

		if( $('.event-calendar-wrapper').attr('data-event-topic') !== undefined && $('.event-calendar-wrapper').attr('data-event-topic') !== '') {
			var categoryTrigger = $('.event-calendar-wrapper').attr('data-event-topic');
			updateIcons(categoryTrigger);
		}

        $('.calendar-layout .first-row').each(function() {
        	if ($(this).find('td a').length === 1) {
        		$(this).addClass('fix-squashed');
        	}
        });		

	}

	function initEvents() {
		var elems;
	    var grid = $('.events-grid-view');
	    var calendar = $('.events-calendar-view');

		// Category clicks

		// drop down
		$('.events-listing .category-toggle span').each(function() {
			$(this).html('All Categories');
		});

		$('.events-listing .category-toggle').on('click', function(e){
	        e.preventDefault();
	        var el = $(this);
	        if(el.hasClass('active')) {
	            el.removeClass('active');
	            el.find('.fa-caret').removeClass('fa-caret-up').addClass('fa-caret-down');
	            el.find('.fa-folder-open').removeClass('fa-folder-open').addClass('fa-folder');
	            el.parents('.events-bar').find('.category-list').slideUp('fast');
	        } else {
	            el.addClass('active');
	            el.find('.fa-caret').removeClass('fa-caret-down').addClass('fa-caret-up');
	            el.find('.fa-folder').removeClass('fa-folder').addClass('fa-folder-open');
	            el.parents('.events-bar').find('.category-list').slideDown('fast');
	        }
	    });

		// filter a category
	    $('.events-listing .category-list a').on('click', function(e){
	        e.preventDefault();
	        
	        var dataEventToggle = $('.column-events-wrapper').attr('data-event-date');

			var grid = $('.events-grid-view');
			if(grid.data('masonry') !== undefined) {
			    grid.masonry('destroy');
			    grid.masonry({"itemSelector": ".event", "columWidth": ".event-sizer", "gutter": ".gutter-sizer", "percentPosition": "true"}).masonry('reloadItems').masonry('layout');
			}

	        $('.events-listing .category-list .active').removeClass('active');
	        var categoryTrigger = $(this).data('trigger');
	        var categoryName = $(this).text();
	        var categoryID = $(this).attr('data-category-id');
			
			if (categoryTrigger) {
				$('.event-calendar-wrapper').attr('data-event-topic', categoryTrigger);
			} else {
				$('.event-calendar-wrapper').removeAttr('data-event-topic');
			}

	        $(this).addClass('active');

	        if(NEEDS_DATA_REFRESH) { // we don't need to make an AJAX call
				$('.category-toggle').trigger('click');
				$('.events-months').updateMonthLinks(categoryID);

				var linky = $('.events-months').find('.active').attr('href') || $('.events-months').find('.active').data('href');
				var grid = 0;
				var shs_stream_monthview = getURLParameter(linky, 'shs_stream_monthview');
				var shs_stream_date = new Date(getURLParameter(linky, 'shs_stream_date'));
					shs_stream_date = shs_stream_date.getFullYear() + '-' + ('0' + (shs_stream_date.getMonth()+1)).slice(-2) + '-' + ('0' + shs_stream_date.getDate()).slice(-2);
				var shs_stream_topic = categoryID;

				// $('.events-listing .events-year, .events-listing .events-months').remove();

				var href=document.getElementById('eventbaseurl');
				//var href = 'http://' + window.location.hostname + window.location.pathname;
				JSON_PATH = href + '/getevents/'+grid+'/'+shs_stream_monthview+'/'+shs_stream_date;

				if(shs_stream_topic !== null && shs_stream_topic !== undefined) {
					JSON_PATH += '/'+shs_stream_topic;
				}

				// $('.events-months').find('.active').trigger('click');
				if($(this).parent().hasClass('all-cats')) {
					NEEDS_DATA_REFRESH = false;
				} else {
					NEEDS_DATA_REFRESH = true;
				}
				
				$('.cal-wrap').refreshCalendar(JSON_PATH);
			} else {
				if (categoryTrigger && categoryName) {
		            $('.events-listing .filter:not(.filter-legend)').hide();
		            $('.events-listing .filter-name').text(categoryName);
		            $('.events-listing .filter.'+categoryTrigger).show();
		            $('.events-grid-view, .events-calendar-view .column-events-wrapper').each(function(){
		                if ($(this).find('.filter.'+categoryTrigger).length < 1) {
		                    $('.no-events-month').show();
		                    $(this).find('.no-events-category').show();
		                } else {
		                    $('.no-events-month').hide();
		                    $(this).find('.no-events-category').hide();
		                }
		            });
		            $('.event-date').each(function(){
		            	updateIcons(categoryTrigger);
		            });
		        } else {
		            $('.events-listing .filter-name').text('');
		            $('.events-listing .filter').show();
		            $('.no-events-category').hide();
		        }

		        $('.category-toggle').trigger('click');

		        $('.events-months').updateMonthLinks(categoryID); // this updates the month links.

		        $('.events-calendar-view .column-events-wrapper .filter').each(function(){
		        	if (!$(this).hasClass(dataEventToggle)) {
		        		$(this).hide();
		        	}
		        });
		        
		        if($('.column-events-wrapper .filter:visible').length > 0) {
		        	$('.column-events-wrapper .no-events-category').hide();
		        } else {
		        	$('.column-events-wrapper .no-events-category').show();
		        }

			    $('.events-grid-view').masonry('reloadItems').masonry('layout');
			}

			var className = $(this).attr('class');
			//className = className.substr(0, className.indexOf(' '));
			$('.events-listing .category-toggle span').each(function() {
				$(this).html(categoryName);
				$(this).attr('class', className);
			});

			if (categoryName == 'All Categories') {
				$('.category-toggle .folder').show();
				$('.events-listing .category-toggle span').removeAttr('class');
			} else {
				$('.category-toggle .folder').hide();
			}

		});

		$('.events-listing .category-list li:first-child a').on('click', function(e){
			$('.events-grid-view, .events-calendar-view .column-events-wrapper').each(function(){
		        if ($(this).find('.filter').length < 1) {
		            $(this).find('.no-events-category').show();
		        } else {
		            $(this).find('.no-events-category').hide();
		        }
		    });
		});

		$('.events-listing .category-list li a').each(function() {
			var trigger = $(this).data('trigger');
			var className = $(this).attr('class');
			$('.'+trigger).each(function() {
				$(this).addClass(className);
			})
		});

		// Calendar toggle

	    $('.events-switch input[name="view"]').on('change', function(){

	        if ($(this).val() == 'calendar') {
	        	Cookies.set('toggle', 'calendar');
	        	if (Cookies.get('toggle') == 'calendar') {
		            $('.events-grid-view').hide();
		            calendar.fadeIn()
	        	}
	        } else {
	        	Cookies.set('toggle', 'grid');
	        	if (Cookies.get('toggle') == 'grid') {
		            calendar.hide();
		            $('.events-grid-view').fadeIn();
		        }
		        var grid = $('.events-grid-view');
					grid.masonry({"itemSelector": ".event", "columWidth": ".event-sizer", "gutter": ".gutter-sizer", "percentPosition": "true"}).masonry('reloadItems').masonry('layout');
				setButtonHeight();
	        }

	        $('.events-calendar-view .column-events-wrapper').each(function(){
		        if ($(this).find('.filter:visible').length < 1) {
		            $(this).find('.no-events-category').show();
		        } else {
		            $(this).find('.no-events-category').hide();
		        }
		    });

		    $('.events-grid-view').each(function(){
		        if ($(this).find('.filter').length < 1) {
		            $(this).find('.no-events-category').show();
		        } else {
		            $(this).find('.no-events-category').hide();
		        }
		    });

	    });
		
		if (!Cookies.get('toggle')) {
			Cookies.set('toggle', 'grid');
			/*
			var grid = $('.events-grid-view');
				grid.masonry('destroy');
				grid.masonry({"itemSelector": ".event", "columWidth": ".event-sizer", "gutter": ".gutter-sizer", "percentPosition": "true"});
				grid.masonry();
			$('.events-grid-view').fadeIn();
			*/
		}

		if (Cookies.get('toggle') == 'calendar') {
	    	$('#calendar').prop('checked', true);
	    	calendar.show();
	    } else {
	    	$('#grid').prop('checked', true);
	    	$('.events-grid-view').show();
	    }

	    $('.events-switch input[name="view"]').trigger('change load');
	}

	function updateIcons(categoryTrigger) {
        $('.event-date').each(function(){
        	if ($(this).find('.event-icons .filter').length < 1) {
        		$(this).addClass('inactive');
        	} else if ($(this).find('.event-icons .filter.'+categoryTrigger).length < 1) {
        		$(this).addClass('inactive');
        	} else {
        		$(this).removeClass('inactive');
        	}
        });
	}

	// Post-ajax delegated events

	// standard calendar date click
	$(document).on('click', '.column-calendar .event-date a', function(e) {

		e.preventDefault();
		
		$('.daily.selected-date').removeClass('selected-date');
		
		var dataEventDate = $(this).data('event-date');

		$('.events-calendar-view .column-events-wrapper').attr('data-event-date', dataEventDate);
		$('.events-calendar-view .column-events-wrapper .event-list-item').hide();
		$('.events-calendar-view .column-events-wrapper .event-list-item.'+dataEventDate).fadeIn();

		$('.events-calendar-view .column-events-wrapper .event-list-item').each(function() {
			var numIcons = $(this).find('.title span:visible').length;
			if (numIcons === 0) {
				$(this).hide();
			}
		});

		var filterFullDate = new Date(String(dataEventDate).slice(0, 4),(String(dataEventDate).slice(4, 6)-1),String(dataEventDate).slice(6, 8));
		filterFullDate = filterFullDate.getTime();
		filterFullDate = moment(filterFullDate).utc().format('MMMM Do');
		$('.filter-full-date').html(filterFullDate);

		$(this).parent('.event-date').addClass('selected-date');

		var dataEventToggle = $('.column-events-wrapper').attr('data-event-date');
		var dataEventTopic = $('.column-events-wrapper').attr('data-event-topic');

		$('.events-calendar-view .column-events-wrapper .filter').each(function(){
			if(dataEventTopic) {
	        	if (!($(this).hasClass(dataEventToggle)) || !($(this).hasClass(dataEventTopic))) {
	        		$(this).hide();
	        	}
	        }
        });

        if($('.column-events-wrapper .filter:visible').length > 0) {
        	$('.column-events-wrapper .no-events-category').hide();
        } else {
        	$('.column-events-wrapper .no-events-category').show();
        }

        if (window.innerWidth < 668) {
	        var top = $('.column-listing').position().top;
			if ($('.wrapper').scrollTop() != 0) {
	            $('html, body, .wrapper').animate({
	                scrollTop: top
	            }, 500);
	        }
        }
	});

	$(document).on('click', '.events-months .months-arrow:not(.disabled)', function(e) {
		e.preventDefault();
	});

	$(document).on('click', '.events-months a:not(.active)', function(e) {

		e.preventDefault();

		//console.log('here');
		$('.cal-wrap').addClass('pre');

		var linky = $(this).attr('href') || $(this).data('href');
		var grid = 0;
		var shs_stream_monthview = getURLParameter(linky, 'shs_stream_monthview');
		var shs_stream_date = new Date(getURLParameter(linky, 'shs_stream_date'));
			shs_stream_date = shs_stream_date.getFullYear() + '-' + ('0' + (shs_stream_date.getMonth()+1)).slice(-2) + '-' + ('0' + shs_stream_date.getDate()).slice(-2);
		var shs_stream_topic = getURLParameter(window.location, 'shs_stream_topic');

		if(getURLParameter(linky, 'shs_stream_topic') !== undefined) {
			shs_stream_topic = getURLParameter(linky, 'shs_stream_topic');			
		}

		// $('.events-listing .events-year, .events-listing .events-months').remove();
		var href=document.getElementById('eventbaseurl');
		//var href = 'http://' + window.location.hostname + window.location.pathname;
		JSON_PATH = href + '/getevents/'+grid+'/'+shs_stream_monthview+'/'+shs_stream_date;

		if(shs_stream_topic !== null && shs_stream_topic !== undefined) {
			JSON_PATH += '/'+shs_stream_topic;
		}

		// $('.category-toggle, .category-list a').unbind();

		// $('.category-list, .category-listing').each(function() {
		// 	$(this).children('li').not('.all-cats').remove();
		// });

		$('.buttons').hide();

		// refresh the calendar!!
		$('.cal-wrap').refreshCalendar(JSON_PATH);

		$('.events-months a').each(function() {
			$(this).removeClass('active');
		})

		$(this).addClass('active');

		if($('.all-cats').find('a').hasClass('active')) {
			NEEDS_DATA_REFRESH = false;
		} else {
			NEEDS_DATA_REFRESH = true;
		}

		// $('.column-calendar .calendar-layout, .column-listing .column-events-wrapper').remove();

		$('.cal-wrap').removeClass('loading');

	});

	$(document).on('click', '.events-months .active', function(e) {
		e.preventDefault();
	});
	
	$(document).on('click', '.events-months .months-arrow.disabled', function(e) {
		e.preventDefault();
	});

	/*

	$(document).on('click', '.events-listing .category-list a', function(e) {
		$('.events-grid-view').each(function(){
	        if ($(this).find('.filter').length < 1) {
	            $('.no-events-month').show();
	        } else {
	            $('.no-events-month').hide();
	        }
	    });
	});

	*/

	var grid = 0,
		shs_stream_monthview = 1,
		shs_stream_date = '',
		shs_stream_topic = getURLParameter(window.location, 'shs_stream_topic');

	shs_stream_date = new Date().toISOString();
	shs_stream_date = moment(shs_stream_date).utc().format('YYYY') + '-' + moment(shs_stream_date).utc().format('MM') + '-' + moment(shs_stream_date).utc().format('DD');

	if ($.QueryString['shs_stream_monthview'] == 0) {
		shs_stream_monthview = 0;
	}

	if ($.QueryString['shs_stream_date']) {
		shs_stream_date = new Date($.QueryString['shs_stream_date']).toISOString();
		shs_stream_date = moment(shs_stream_date).utc().format('YYYY') + '-' + ('0'+ moment(shs_stream_date).utc().format('MM')).slice(-2) + '-' + ('0' + moment(shs_stream_date).utc().format('DD')).slice(-2);
	}
	
	var href=document.getElementById('eventbaseurl');
	//var href = 'http://' + window.location.hostname + window.location.pathname;
	JSON_PATH = href + '/getevents/'+grid+'/'+shs_stream_monthview+'/'+shs_stream_date;

	if(shs_stream_topic !== null && shs_stream_topic !== undefined) {
		JSON_PATH += '/'+shs_stream_topic;
	}

	$('.event-calendar-wrapper').buildEventCalendar(JSON_PATH);
	// triggerWrapper(JSON_PATH);

});

$(function() {

	if ($(".back-to-top").length != 0) {

        $(".back-to-top").on('click',function(e) {
            e.preventDefault();
            var top = $('.events-bar').position().top;
            $('html, body, .wrapper').animate({
                scrollTop: top
            }, 500);
        });
    	
    	$('.wrapper').scroll(function() {
            var top = $('.events-bar').position().top;
            if ($('.wrapper').scrollTop() <= top) {
                $(".back-to-top").removeClass('active');
            } else {
                $(".back-to-top").addClass('active');
            }
        });
    }

    $.fn.backToTop = function() {
        $(window).scroll(function(){
            if ($(this).scrollTop() > 200) {
                $('.scrollToTop').addClass('come-in')
            } else {
                $('.scrollToTop').removeClass('come-in')
            }
        });

        $('button.scrolltotop-back').on('click', function (e) {
            e.preventDefault();
            $('html,body').animate({
                scrollTop: 0
            }, 700);
        });
    }
    $('.events-listing.grid').backToTop();

});

Handlebars.registerHelper('counter', function (index){
	return index + 1;
});

Handlebars.registerHelper('url', function (name){
	var url = encodeURI(name.toLowerCase().replace(/ /g, '-'));
	return url;
});

Handlebars.registerHelper('handle', function (name)
	{
		handle='';
		if(name)
			var handle = name.toLowerCase().replace(/ /g, '-');
		return handle;
	}
);

Handlebars.registerHelper('metaStartTime', function (start){
	var d = new Date(start*1000);
	return d.toISOString();
});

Handlebars.registerHelper('abbrevDay', function (start){
	var d = new Date(start*1000);
	//d.setDate(d.getDate() + 1); //included because the json timestamp for start.time converts to the previous day
	var momentDay = moment(d).utcOffset(240).format('ddd');
	return momentDay;
});

Handlebars.registerHelper('abbrevMonth', function (start){
	var d = new Date(start*1000);
	//d.setDate(d.getDate() + 1); //included because the json timestamp for start.time converts to the previous day
	var momentMonth = moment(d).utcOffset(240).format('MMM');
	return momentMonth;
});

Handlebars.registerHelper('splitTopics', function (topic, type)
	{
		if (topic)
		{
			var topicLower = topic.replace(/\s+/g, '-').toLowerCase();
			switch(type) {
				case 'span':
					topic = '<span class="'+topicLower+'"></span>';
					break;
				case 'grid':
					topic = '<span class="'+topicLower+'">'+topic+'</span>';
					break;
				default:
					topic = topicLower + ' ';
			}
		}
		else
		{
			topic='<span class=""></span>';
		}
	return topic;
});

Handlebars.registerHelper('splitTopicsTime', function (topic, eventStartTime){
	var topicLower = topic.replace(/\s+/g, '-').toLowerCase();
	topic = '<span class="event-icon filter '+topicLower+' '+eventStartTime+'" data-event-start-time="'+eventStartTime+'"></span>';
	return topic;
});

Handlebars.registerHelper('dateHeading', function (date){
	var dateHeading = new Date(date).toISOString();
	dateHeading = moment(dateHeading).utc().format('MMMM YYYY');
	return dateHeading;
});

Handlebars.registerHelper('buildEventsDatesBar', function (monthDropDown, month, year, dateToday, previous_month, next_month){
	var monthDropDown = monthDropDown;
	var month = month;
	var year = year;
	var dateToday = dateToday;
	var previous_month = previous_month;
	var next_month = next_month;
	var dateLink = '';
	var selected = '';
	var disabled = '';
	var selectedDate = month + '/01/' + year;
	var selectedDateTest = new Date(selectedDate).toISOString();
	var topic = getURLParameter(window.location, 'shs_stream_topic');

	var currentDate = new Date(dateToday);
	currentDate.setMonth(currentDate.getMonth());
	currentDate = (currentDate.getMonth() + 1) + '/01/' + currentDate.getFullYear();
	currentDateTest = new Date(currentDate).toISOString();

	var previous_month_test = new Date(previous_month).toISOString();

	for (var key in monthDropDown) {
		if (monthDropDown.hasOwnProperty(key)) {
			var monthParam = key;
			var monthParamTest = new Date(monthParam).toISOString();
			var monthName = monthDropDown[key]['month'];
			var year = monthDropDown[key]['year'];
			if (selectedDateTest <= monthParamTest) {
				if (selectedDate == monthParam) {
					selected = ' active';
				} else {
					selected = '';
				}
				var href = '?shs_stream_monthview=1&shs_stream_date='+ monthParam;

				if(topic !== null && topic !== undefined) {
					href += '&shs_stream_topic='+topic;
				}

				dateLink += '<a href="' + href + '" class="' + selected + '">'+ monthName + ' ' + year +'</a>';
			}
		}
		if (previous_month_test < currentDateTest) {
			disabled = ' disabled';
		}
	}
	
	return dateLink;
});

Handlebars.registerHelper('buildCalendar', function (dateToday, firstDayOffset, daysInMonth, year, month, selectedDay, selectedDate, occupied){
	var dateToday = dateToday;
	var firstday = true;
	//var firstDayOffset = parseInt(firstDayOffset - 1);
	var daysInMonth = daysInMonth;
	var year = year;
	var month = month;
	var monthForISO = month;
	var selectedDay = selectedDay;
	var selectedDate = selectedDate;
	var occupied = occupied;
	var row = '';

	var v1 = new Date(dateToday).toISOString();
	var v2 = new Date(selectedDate).toISOString();
	if (v1 > v2) {
		var dataDateComparitor = v1;
	} else {
		var dataDateComparitor = v2;
	}

	var selectedDateTime = new Date(selectedDate).toISOString();
	var dateTodayTime = new Date(dateToday).toISOString();

	if (selectedDay < 10 && selectedDay > 0) {
		selectedDay = '0'+selectedDay;
	}

	if (monthForISO < 10 && monthForISO > 0) {
		monthForISO = '0'+monthForISO;
	}

	var compareSelectedDayTime = new Date(year + '-' + monthForISO + '-' + selectedDay).toISOString();

	var j = 1;

	while(firstDayOffset <= daysInMonth) {
		row += '<tr';
		if (firstday) {
            row += ' class="first-row" ';
        }
        row += '>';
        for (var x = 0; x < 7; x++) {

        	var day = firstDayOffset;
        	
        	var classNames = ['daily'];
        	
        	if (firstDayOffset < 10 && firstDayOffset > 0) {
                day = '0'+firstDayOffset;
            }
        	if (j > 0 && j <= daysInMonth) {
        		var d = j;
        		
        		if (j < 10) {
        			d = '0'+j;
        		}
        		
        		var compareDateTimeDay = new Date(year + '-' + monthForISO + '-' + d).toISOString();
        	}
        	
        	if (x == 0) {
                classNames.push('first-col');
            }
        	
        	if (compareDateTimeDay == dateTodayTime) {
                classNames.push('today');
            }
        	
        	if (compareDateTimeDay < dateTodayTime) {
                classNames.push('past-date');
            }

           // if (occupied[year] > 0 ) {
	            if (compareDateTimeDay >= dateTodayTime && occupied[year][month][firstDayOffset]) {
	            	classNames.push('event-date');
	            }
	       // }
            

            if (firstDayOffset > 0 && firstDayOffset <= daysInMonth) {
                var dataEventDateComparitor = new Date(year + '-' + monthForISO + '-' + day).toISOString();
             	if (dataDateComparitor == dataEventDateComparitor) {
             		classNames.push('selected-date');
             	}
         	}
        	
            row += '<td class="'+classNames.join(' ')+'">';

        	if (firstDayOffset > 0 && firstDayOffset <= daysInMonth) {
        		if ($.inArray('event-date', classNames) > 0) {
                 	var title = 'title="'+occupied[year][month][firstDayOffset]+' event"';
                 	if (occupied[year][month][firstDayOffset] > 1) {
                 		var title = 'title="'+occupied[year][month][firstDayOffset]+' events"';
                 	}
                 	var dataEventDate = year.toString() + monthForISO + day.toString();
                 	if ($.QueryString['shs_stream_monthview'] == 0) {
                 		if (firstDayOffset == selectedDay) {
                 			row += '<a data-event-date="'+dataEventDate+'" href="'+'?shs_stream_date='+year + '-' + monthForISO + '-' + day+'"'+title+'>'+firstDayOffset+'</a>';
                 			row += '<div class="event-icons" data-event-date="'+dataEventDate+'"></div>';
                 		} else {
                 			row += firstDayOffset;
                 		}
                 	} else {
                 		row += '<a data-event-date="'+dataEventDate+'" href="'+'?shs_stream_date='+year + '-' + monthForISO + '-' + day+'"'+title+'>'+firstDayOffset+'</a>';
                 		row += '<div class="event-icons" data-event-date="'+dataEventDate+'"></div>';
                 	}
                } else {
                	row += firstDayOffset;
                }
        	}

        	row += '</td>';

            firstDayOffset++;
            j++;
		}
        row += '</tr>';
        firstday = false;
	}
	return row;
});

Handlebars.registerHelper('ifCondGridDate', function(v1, v2, v3, options) {
	var eventStart = v1;
	var selectedMonth = v2.replace(/-/g, '');
	var today = v3.replace(/-/g, '');

	if (selectedMonth <= today) {
		if(eventStart >= today) {
			return options.fn(this);
		}
	} else {
		if(eventStart >= selectedMonth) {
			return options.fn(this);
		}
	}
});

Handlebars.registerHelper('ifDataDate', function (v1, v2, options){
	var v1 = new Date(v1).toISOString();
	var v2 = new Date(v2).toISOString();
	v1 = moment(v1).utc().format('YYYYMMDD');
	v2 = moment(v2).utc().format('YYYYMMDD');
	return Math.max(v1, v2);
});

Handlebars.registerHelper('ifDataDateHeading', function (v1, v2, options){
	var v1 = new Date(v1).toISOString();
	var v2 = new Date(v2).toISOString();
	if (v1 > v2) {
		var dataDateHeading = moment(v1).utc().format('MMMM Do');
	} else {
		var dataDateHeading = moment(v2).utc().format('MMMM Do');
	}			
	return dataDateHeading;
});

Handlebars.registerHelper('debug', 
	function(value)
	{
		alert(value);
		return '';
	}
);