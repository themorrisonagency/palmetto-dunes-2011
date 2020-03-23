$(function(){

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

	var promoParam = $.getUrlVar("promocode"),
		arriveParam = $.getUrlVar("arrive"),
		departParam = $.getUrlVar("depart");
	var addDay = 24 * 60 * 60 * 1000;
	if(arriveParam === '' && departParam === '') {
		var newArrive = new Date();
		var newDepart = new Date(newArrive.getTime() + addDay);

		$('#cal-booking-form #arrive').val(("0" + (newArrive.getMonth() + 1)).slice(-2) + '/' + ("0" + (newArrive.getDate())).slice(-2) + '/' + newArrive.getFullYear());
		$('#cal-booking-form #depart').val(("0" + (newDepart.getMonth() + 1)).slice(-2) + '/' + ("0" + (newDepart.getDate())).slice(-2) + '/' + newDepart.getFullYear());
	}

	var arriveSplit = $('#cal-booking-form #arrive').val().split('/'),
		departSplit = $('#cal-booking-form #depart').val().split('/'),
		arrive = '',
		depart = '',
		items = new Array;
		
	arrive = ('0' + arriveSplit[0]).slice(-2) + '/' + ('0' + arriveSplit[1]).slice(-2) + '/' + arriveSplit[2];
	depart = ('0' + departSplit[0]).slice(-2) + '/' + ('0' + departSplit[1]).slice(-2) + '/' + departSplit[2];
	
	$('#cal-booking-form #arrive').val(arrive);
	$('#cal-booking-form #depart').val(depart);

	var now = new Date(),
		nowYear = now.getFullYear(),
		nowMonth = ("0" + (now.getMonth() + 1)).slice(-2);
		nowDay = ("0" + (now.getDate())).slice(-2);

        function getMonth(dateObj) {
            return ("0" + (dateObj.getMonth() + 1)).slice(-2);
        }

        function getDate(dateObj) {
            return ("0" + dateObj.getDate()).slice(-2);
        }

	// Create json object from onload expanded specials offer form 'BOOK' link
	var btnUrl = $('#cal-booking-form .unit-book').attr('href');

	if(btnUrl) {
		jsonBtnUrl = JSON.parse('{"' + decodeURI(btnUrl).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}')
	}

	if (now.getMonth() == 11) {
		var next = new Date(now.getFullYear() + 1, 0, 1),
			nextMonth = '01',
			nextYear = next.getFullYear();
	} else {
		var next = new Date(now.getFullYear(), now.getMonth() + 1, 1),
			nextMonth = ("0" + (next.getMonth() + 1)).slice(-2),
			nextYear = next.getFullYear();
	}

	if (arrive !== undefined && arrive !== '' && depart !== undefined && depart !== '') {
		arriveYear = arrive.substr(6);
		arriveMonth = arrive.substr(0, 2);
		useYear = arriveYear;
		useMonth = arriveMonth;
		if (useMonth == 12) {
			nextYear = parseInt(useYear) + 1;
			nextMonth = '01';
		} else {
			nextYear = useYear;
			nextMonth = ('0' + (parseInt(useMonth) + 1)).slice(-2);
		}
	} else {
		useYear = nowYear;
		useMonth = nowMonth;
		if (useMonth == 12) {
			nextYear = parseInt(useYear) + 1;
			nextMonth = '01';
		} else {
			nextYear = useYear;
			nextMonth = ('0' + (parseInt(useMonth) + 1)).slice(-2);
		}

		$('#arrive').attr('value', useMonth+'/'+nowDay+'/'+useYear);

        // use Date.setDate natively
        var then = new Date(now);
        then.setDate( then.getDate() +3 );

        $('#depart').attr('value', getMonth(then) + '/' + getDate(then) + '/' + then.getFullYear());

        // update Book Now button with default dates
        if (btnUrl) {
	        jsonBtnUrl.arrive = useMonth+'/'+nowDay+'/'+useYear;
			jsonBtnUrl.arrive_submit = useMonth+'/'+nowDay+'/'+useYear;
			jsonBtnUrl.depart = getMonth(then) + '/' + getDate(then) + '/' + then.getFullYear();
			jsonBtnUrl.depart_submit = getMonth(then) + '/' + getDate(then) + '/' + then.getFullYear();
			var jsonBtnUrlString = decodeURIComponent(jQuery.param(jsonBtnUrl));
			$('#cal-booking-form .unit-book').attr('href', jsonBtnUrlString);
		}

		/*
		if(parseInt(nowDay) > 27) {
			depDay = '01';
			var depMonth = nextMonth;
			$('#depart').attr('value', depMonth+'/'+depDay+'/'+nextYear);
		} else {
			$('#depart').attr('value', useMonth+'/'+(parseInt(nowDay)+3)+'/'+useYear);
		}
                */
	}

	if (!isMobileSearch) {
		loadCalendar(useMonth,useYear,nextMonth,nextYear);
	}

	var currentMonthYear, currentMonth, currentYear;
	function loadCalendar(m,y,mtwo,ytwo,pID) {
		var months;
		$('.ajax-cal').html('<img src="/templates/main/images/ajax-loader.gif" />');
		$.get('/booking/rental-calendar?month='+m+'&year='+y,function(data){
			months = data;
			$.get('/booking/rental-calendar?month='+mtwo+'&year='+ytwo,function(data){
				months += data;
				$('.ajax-cal').html(months);
				currentMonthYear = $('.table:first .current_month').text().split(' ');
				currentMonth = monthVal(currentMonthYear[0]);
				currentYear = currentMonthYear[1];

				if (screen.width < 749 ) {
					var currentMonthYear = new Date(currentYear, currentMonth, 1);
					var futureFirstMonth = new Date(new Date(currentMonthYear).setMonth(currentMonthYear.getMonth()+1));
					var futureSecondMonth = new Date(new Date(currentMonthYear).setMonth(currentMonthYear.getMonth()+2));
					var pastFirstMonth = new Date(new Date(currentMonthYear).setMonth(currentMonthYear.getMonth()-2));
					var pastSecondMonth = new Date(new Date(currentMonthYear).setMonth(currentMonthYear.getMonth()-1));

					$('.table-container .next-month').css('visibility','visible').attr('id','date'+parseInt(parseInt(futureFirstMonth.getMonth())+1)+'-'+futureFirstMonth.getFullYear()+'-'+parseInt(parseInt(futureSecondMonth.getMonth())+1)+'-'+futureSecondMonth.getFullYear());

					$('.table-container .previous-month').css('visibility','visible').attr('id','date'+parseInt(parseInt(pastFirstMonth.getMonth())+1)+'-'+pastFirstMonth.getFullYear()+'-'+parseInt(parseInt(pastSecondMonth.getMonth())+1)+'-'+pastSecondMonth.getFullYear());

				} else {
					var currentMonthYear = new Date(currentYear, currentMonth, 1);
					var futureFirstMonth = new Date(new Date(currentMonthYear).setMonth(currentMonthYear.getMonth()+2));
					var futureSecondMonth = new Date(new Date(currentMonthYear).setMonth(currentMonthYear.getMonth()+3));
					var pastFirstMonth = new Date(new Date(currentMonthYear).setMonth(currentMonthYear.getMonth()-2));
					var pastSecondMonth = new Date(new Date(currentMonthYear).setMonth(currentMonthYear.getMonth()-1));

					$('.table-container .next-month').css('visibility','visible').attr('id','date'+parseInt(parseInt(futureFirstMonth.getMonth())+1)+'-'+futureFirstMonth.getFullYear()+'-'+parseInt(parseInt(futureSecondMonth.getMonth())+1)+'-'+futureSecondMonth.getFullYear());

					$('.table-container .previous-month').css('visibility','visible').attr('id','date'+parseInt(parseInt(pastFirstMonth.getMonth())+1)+'-'+pastFirstMonth.getFullYear()+'-'+parseInt(parseInt(pastSecondMonth.getMonth())+1)+'-'+pastSecondMonth.getFullYear());
				}
				success:
				$('div.table').each(function() {
					$('.table:even').addClass('left');
					$('.table:odd').addClass('right');
				});
				modifyCal(pID);
				evenHeight();
			});

		});

	}

	$('.next-month, .previous-month').click(function(e){
		e.preventDefault();

		$('#cal-booking-form').remove('bad-dates').find('no-dates').remove();
		var goto = $(this).attr('id').substr(4).split('-');
		var gotoFirstMonth = ("0"+goto[0]).slice(-2);
		var gotoFirstYear = goto[1];
		var gotoSecondMonth = ("0"+goto[2]).slice(-2);
		var gotoSecondYear = goto[3];
		var pID = $(this).parents('.calendar').attr('rel');
		loadCalendar(gotoFirstMonth,gotoFirstYear,gotoSecondMonth,gotoSecondYear,pID);
	});

	function findIndexByKeyValue(obj, key, value) {
	    for (var i = 0; i < obj.length; i++) {
	        if (obj[i][key] == value) {
	            return i;
	        }
	    }
	    return null;
	}

	function modifyCal(pID){
		var status, prop, propId, calURL;

		if ($('body').hasClass('unit-details')) {
			prop = $('#photos-overview').attr('data-id');

			if ( promoParam == null || promoParam == '' || promoParam == 'undefined'  ) {
				calURL='/booking/calendar-data?propertyid='+prop;
			} else {
				calURL = '/booking/calendar-data?propertyid='+prop+'&promocode='+promoParam;
			}

		} else {
			prop = $('.results-promo-calendar').closest('li').attr('data-id');
		}

		if (pID!=undefined) {
			featPromo = $(this).find('.results-promo').attr('data-promo');
			propId = pID;
			if ( (promoParam == null || promoParam == '' || promoParam == 'undefined') && (featPromo == null || featPromo == '' || featPromo == 'undefined')  ) {
				calURL='/booking/calendar-data?propertyid='+propId;
			} else if ( (featPromo =='' || featPromo =='undefined' || featPromo ==null) && $('body').hasClass('b-search-results') && (promoParam !== null || promoParam !== '' || promoParam !== 'undefined') ) {
				calURL = '/booking/calendar-data?propertyid='+propId+'&promocode='+promoParam;
			} else if ( (featPromo !=='' || featPromo !=='undefined' || featPromo !==null) && $('body').hasClass('b-search-results') ) {
				calURL = '/booking/calendar-data?propertyid='+propId+'&promocode='+featPromo;
			} else {
				calURL = '/booking/calendar-data?propertyid='+propId+'&promocode='+promoParam;
			}
		} else {
			$('#search-results #results-container .unit-li').each(function(){
				featPromo = $(this).find('.results-promo').attr('data-promo');
				propId = $(this).attr('data-id');

				if ( (promoParam == null || promoParam == '' || promoParam == 'undefined') && (featPromo == null || featPromo == '' || featPromo == 'undefined')  ) {
					calURL='/booking/calendar-data?propertyid='+propId;
				} else if ( (featPromo =='' || featPromo =='undefined' || featPromo ==null) && $('body').hasClass('b-search-results') && (promoParam !== null || promoParam !== '' || promoParam !== 'undefined') ) {
					calURL = '/booking/calendar-data?propertyid='+propId+'&promocode='+promoParam;
				} else if ( (featPromo !=='' || featPromo !=='undefined' || featPromo !==null) && $('body').hasClass('b-search-results') ) {
					calURL = '/booking/calendar-data?propertyid='+propId+'&promocode='+featPromo;
				} else {
					calURL = '/booking/calendar-data?propertyid='+propId+'&promocode='+promoParam;
				}
				// console.log('promoParam: '+promoParam);
				// console.log('featPromo: '+featPromo);
				// console.log('calURL: '+calURL);
			});
		}

		var startDate = $('#arrive').attr('value'),
			endDate = $('#depart').attr('value'),
			startDateP = '',
			endDateP = '';
		if (startDate && endDate) {
			startDateP = startDate.substr(6, 4) + '-' + startDate.substr(0, 2) + '-' + startDate.substr(3, 2);
			endDateP = endDate.substr(6, 4) + '-' + endDate.substr(0, 2) + '-' + endDate.substr(3, 2);
		}

		$.getJSON(calURL, function(data) {
			$.each(data.data.record, function(i) {
				items[i] = {};
				var data_id = $(this),
					date = data_id[0]['@attributes'].date,
					checkoutonly = data_id[0]['@attributes'].checkoutonly;
					checkinonly = data_id[0]['@attributes'].checkinonly;

				items[i].date = date;
				items[i].checkoutonly = checkoutonly;
				items[i].checkinonly = checkinonly;
				if (data_id[0].rates) {
					$.each(data_id[0].rates, function(j) {
						var rate_id = $(this);
						if( rate_id[0]['@attributes'].amount !== undefined) {
							var rateplanid = rate_id[0]['@attributes'].rateplanid.length ? rate_id[0]['@attributes'].rateplanid : null,
								minstay = rate_id[0]['@attributes'].minstay.length ? rate_id[0]['@attributes'].minstay : null,
								maxstay = rate_id[0]['@attributes'].maxstay.length ? rate_id[0]['@attributes'].maxstay : null,
								amount = rate_id[0]['@attributes'].amount.length ? rate_id[0]['@attributes'].amount : null;
							items[i].rateplanid = rateplanid;
							items[i].minstay = minstay;
							items[i].maxstay = maxstay;
							items[i].amount = amount;
						}
					});
				}
			});

			$('.realtd').each(function() {
				var nowDay = ("0" + now.getDate()).slice(-2),
					today = nowYear + '-' + nowMonth + '-' + nowDay,
					td = $(this),
					tdDateID = $(this).attr('id').substr(5),
					isAvail = 'false',
					idx = findIndexByKeyValue(items, 'date', tdDateID),
					checkoutonly = 'false';
					checkinonly = 'false';

				if (idx == null) {
					// if idx is null, date is not bookable online
					$(this).addClass('not-available');
				} else {
					// proceed with valid date checks
					if (items[idx].hasOwnProperty('amount') && items[idx].amount != null) {
						isAvail = 'true';
					}

					if (items[idx].checkoutonly === '1') {
						checkoutonly = 'true';
						$(td).addClass('checkoutonly');
					}

					if (items[idx].checkinonly === '1') {
						checkinonly = 'true';
						$(td).addClass('check-in-only');
					}
					if (items[idx].date === 'undefined' || items[idx].date === null) {

						$(td).addClass('notonline');
					}
					//console.log(items[idx].date);

					if (tdDateID < today) {
						$(td).addClass('past');
					} else if (isAvail === 'true') {
						$(td).addClass('available');
					} else if (checkoutonly === 'true') {
						$(td).addClass('checkoutonly');
					} else if (checkoutonly === 'true') {
						$(td).addClass('check-in-only');
					}
					 else {
						$(td).addClass('unavailable');
					}

					if (($('#start_date').attr('value') == tdDateID && td.hasClass('unavailable')) || ($('#end_date').attr('value') == tdDateID && td.hasClass('unavailable'))) {
						$('#cal-booking-form').addClass('bad-dates');
					}

					var tdDate = $(this);
					var theDate = tdDate.attr('id').substr(5, 4) + '-' + tdDate.attr('id').substr(10, 2) + '-' + tdDate.attr('id').substr(13, 2);

					if (theDate == startDateP) {
						if (tdDate.hasClass('available')) {
							tdDate.addClass('selected start-date');
						}
					} else if (theDate == endDateP) {
						if (tdDate.hasClass('available')) {
							tdDate.removeClass('checkoutonly');
							tdDate.addClass('selected end-date');
						}
					} else if (endDate != '' && theDate <= endDateP && theDate >= startDateP) {
						if (tdDate.hasClass('available')) {
							tdDate.addClass('selected');
						}
					}
				}
			});

			$('.cal-wrap tr .unavailable').each(function(){
				if($(this).is('td:last-child')){
					if ($(this).parent('tr').next('tr').children(':first-child').hasClass('available')) {
						$(this).addClass('go-to-next');
					}
				} else if ($(this).next().hasClass('available')) {
					$(this).addClass('go-to-next');
				}
			});
			// $('.go-to-next').each(function(){
			// 	if($(this).is('td:last-child')) {
			// 		$(this).parent('tr').next('tr').children(':first-child').addClass('check-in-only');
			// 	} else {
			// 		$(this).next('td').addClass('check-in-only');
			// 	}
			// });


			$('.unit-details #cal-booking-form.bad-dates').each(function() {
				$('#cal-booking-form').prepend('<div class="no-dates">We\'re sorry, this date range has unavailable dates.  Please select a new set of dates.</div>');
				$('#arrive, #depart, #start_date, #end_date').attr('value','');
				$('html, body').animate({scrollTop:$('#rates-availability').position().top}, 'slow');
			});

			$('.available, .checkoutonly:not(.selected), .checkinonly:not(.selected)').on('click',function(){
				var a = $(this);
				var startDate = $('#start_date').attr('value');
				var endDate = $('#end_date').attr('value');
				//var curProp = $
				var thisId = ($(this).closest('.unit-li').attr('data-id')) ? $(this).closest('.unit-li').attr('data-id') : $('.photos-overview').attr('data-id');

				if ( !startDate && !endDate ) {
					if($('.cal-wrap .start-date').length > 0) {
						$('.realtd').removeClass('start-date').removeClass('end-date').removeClass('selected');
					}
				}
				// Let's check if the user already selected 2 dates and they want to start over
				if ( startDate && endDate ) {
					//Get rid of all selected dates
					$('.selected').removeClass('selected');
					//Get rid of the start-date and end-date classes
					$('.start-date').removeClass('start-date');
					$('.end-date').removeClass('end-date');
					// Reset the variables
					startDate = '';
					endDate = '';
					// Reset the hidden input fields
					$('#start_date, #end_date').attr('value','');
				}

				// No days or only one date is selected
				a.addClass('selected');
				var selDate = a.attr('id').substr(5),
					selDateFmtd = selDate.substr(5,2)+'/'+selDate.substr(8)+'/'+selDate.substr(0,4);

				// if no dates are selected set start date
				if ( startDate == '' && endDate == '' ){
					$('.checkoutonly').removeClass('selected');
					if ( !$(a).hasClass('checkoutonly') ) {
						$('#start_date').attr('value',selDate);
						$('#arrive').attr('value', selDateFmtd);
						$(a).parents('#cal-booking-form').find('#arrive').attr('value', selDate.substr(5,2)+'/'+selDate.substr(8)+'/'+selDate.substr(0,4));
						jsonBtnUrl.arrive = selDate.substr(5,2)+'/'+selDate.substr(8)+'/'+selDate.substr(0,4);
						jsonBtnUrl.arrive_submit = selDate.substr(5,2)+'/'+selDate.substr(8)+'/'+selDate.substr(0,4);
						a.addClass('start-date');
					}
				} else if ( endDate == '' ) { // if there is a start date
					startDateArr = startDate.split('-');
					startD = new Date(startDateArr[0],startDateArr[1]-1,startDateArr[2]); // Date friendly var for the start date
					selDateArr = selDate.split('-');
					selD = new Date(selDateArr[0],selDateArr[1]-1,selDateArr[2]); // Date friendly var for the selected date
					if ( startD < selD ) { // if startDate is less than selected date - selDate is end-date
						a.addClass('end-date');
						$('#end_date').attr('value',selDate);
						$('#depart').attr('value',selDateFmtd);
						$(a).parents('#cal-booking-form').find('#depart').attr('value', selDate.substr(5,2)+'/'+selDate.substr(8)+'/'+selDate.substr(0,4));
						jsonBtnUrl.depart = selDate.substr(5,2)+'/'+selDate.substr(8)+'/'+selDate.substr(0,4);
						jsonBtnUrl.depart_submit = selDate.substr(5,2)+'/'+selDate.substr(8)+'/'+selDate.substr(0,4);
						getDatesInBetween(startD,selD);
					} else { // If start date is greater than selected date - selDate is the new startDate
						$('.start-date').addClass('end-date').removeClass('start-date');
						a.addClass('start-date');
						$('#end_date').attr('value',startDate); // Set the previous start date as the end date input hidden field value
						$('#start_date').attr('value',selDate); // Set the new selected date to the start date input hidden field value
						$('#depart').attr('value', startDate.substr(5,2)+'/'+startDate.substr(8)+'/'+startDate.substr(0,4));
						$('#arrive').attr('value', selDate.substr(5,2)+'/'+selDate.substr(8)+'/'+selDate.substr(0,4));
						jsonBtnUrl.depart = startDate.substr(5,2)+'/'+startDate.substr(8)+'/'+startDate.substr(0,4);
						jsonBtnUrl.depart_submit = startDate.substr(5,2)+'/'+startDate.substr(8)+'/'+startDate.substr(0,4);
						jsonBtnUrl.arrive = selDate.substr(5,2)+'/'+selDate.substr(8)+'/'+selDate.substr(0,4);
						jsonBtnUrl.arrive_submit = selDate.substr(5,2)+'/'+selDate.substr(8)+'/'+selDate.substr(0,4);
						getDatesInBetween(selD,startD); // note startD is really the end date and the selD is really the start date
					}
				} else {
				//alert('empty else');
				}
				// Update json object for expanded specials offer form 'BOOK' link set new URL
				//jsonBtnUrl.propertyid = thisId;
				var jsonBtnUrlString = decodeURIComponent(jQuery.param(jsonBtnUrl)),
					temp = jsonBtnUrlString.split('&'),
					fullLoc = temp[0],
					loc = temp[0].split('=');
					repl = fullLoc.replace(loc[1],thisId);
				jsonBtnUrlString = jsonBtnUrlString.replace(fullLoc,repl);
				$('#cal-booking-form .unit-book').attr('href', jsonBtnUrlString);
			});
			if (startDate && endDate) {
				setSelectedDates();
			}
		});
	}

	function setSelectedDates() {
		var startDate = $('#start_date').attr('value');
		var endDate = $('#end_date').attr('value');
		var sD = $('#date_'+startDate);
		sD.addClass('selected').addClass('start-date');
		startDateArr = startDate.split('-');
		endDateArr = endDate.split('-');
		startD = new Date(startDateArr[0],startDateArr[1]-1,startDateArr[2]); // Date friendly var for the start date
		endD = new Date(endDateArr[0],endDateArr[1]-1,endDateArr[2]); // Date friendly var for the end date

		var eD = $('#date_'+endDate);
		eD.addClass('selected').addClass('end-date');
		jsonBtnUrl.depart = endDate.substr(5,2)+'/'+endDate.substr(8)+'/'+endDate.substr(0,4);
		getDatesInBetween(startD,endD);
	}

	function getDatesInBetween(s,e){
		$('.realtd').each(function(){
			var tdDate = $(this);
			var theDate = tdDate.attr('id').substr(5).split('-');
			var theRealDate = new Date(theDate[0],theDate[1]-1,theDate[2]);
			//console.log(s + ' ' + theRealDate + ' ' + e + ' ' + theRealDate );
			// Check if the date is greater than the start and less than the end date add the class selected
			if ( s < theRealDate && e > theRealDate ) {
				if ( tdDate.hasClass('available') ) {
					tdDate.addClass('selected');
				} else {
					//Get rid of all selected dates
					$('.selected').removeClass('selected');
					//Get rid of the start-date and end-date classes
					$('.start-date').removeClass('start-date');
					$('.end-date').removeClass('end-date');
					// Reset the hidden input fields
					$('#start_date').attr('value','');
					$('#end_date').attr('value','');
					alert('Sorry this date range has unavailable dates.  Please select a new set of dates');
					return false;
				}
			}
		});
	}

	function monthVal(m){
		switch(m){
			case 'January':
				return '0';
				break;
			case 'February':
				return '1';
				break;
			case 'March':
				return '2';
				break;
			case 'April':
				return '3';
				break;
			case 'May':
				return '4';
				break;
			case 'June':
				return '5';
				break;
			case 'July':
				return '6';
				break;
			case 'August':
				return '7';
				break;
			case 'September':
				return '8';
				break;
			case 'October':
				return '9';
				break;
			case 'November':
				return '10';
				break;
			case 'December':
				return '11';
				break;
		}
	}

	// make sure calendars are of equal height
	function evenHeight() {
		var lHeight = $('.table.left .cal-wrap').height(),
			rHeight = $('.table.right .cal-wrap').height();
		if (lHeight > rHeight) {
			$('.table.right .cal-wrap').height(lHeight);
		} else if (rHeight > lHeight) {
			$('.table.left .cal-wrap').height(rHeight);
		}
	}

	$('.rates-table td').each(function() {
		if($(this).text() == '$.00') {
			$(this).html('&#8212;');
		}
	});

	$( document ).on( 'click', '.results-promo:visible a', function(e) {
		e.preventDefault();
		var pID = $(this).attr('rel'),
			$details = $(this).parents('.unit-li').find('.results-promo-details'),
			arrVal = $('#arrive').val(),
			depVal = $('#depart').val();
		loadCalendar(useMonth,useYear,nextMonth,nextYear,pID);
		$details.find('.field #arrive').val(arrVal);
		$details.find('.field #depart').val(depVal);
		$details.slideDown('slow');
	});

	$('#cal-booking-form .unit-book').click(function(e) {
		$('#rates-availability .system-errors').hide();
		var arrive = new Date($('#cal-booking-form #arrive').val()),
		    depart = new Date($('#cal-booking-form #depart').val()),
		    timeDiff = Math.abs(depart.getTime() - arrive.getTime())
		    diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
		if (diffDays < 3) {
			e.preventDefault();
			$('#rates-availability .system-errors').show();
			$('html,body').animate({
					scrollTop: ($('#rates-availability .system-errors').offset().top - 110)
				}, 1000);
		}
	});

});
