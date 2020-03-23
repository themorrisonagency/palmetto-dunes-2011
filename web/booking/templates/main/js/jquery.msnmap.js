//still need to do drive to/from
(function($) {
	function MSNMap() {
		this._map = null;
		this._id = null;
		this._data = null;
		this._categories = [];
		this._contextPin = null;
		this._loadIds = null;
		this._cstmCount = 0;
		this._defaults = {
			xml : RELPATH+'/includes/map.xml',
			dashboard : 'Normal',
			sortable : true,
			listView : false,
			hotelOnly: false,
			printNames : true,
			miniMap : false,
			kilometers : false,
			context : false,
			acceptQuery : false,
			simpleMap : false,
			printMap : false
		};
		this._regional = [];
		this._regional[''] = {
			moreinfo : 'More Info',
			showMiniMap : 'Show Mini Map',
			hideMiniMap : 'Hide Mini Map',
			addToPlanner : 'Add to Route Planner',
			removePlanner : 'Remove from Route Planner',
			centerPoint : 'Center point on map',
			hidePoint : 'Hide point on map',
			showPoint : 'Show point on map',
			hideSection : 'Hide Section',
			showSection : 'Show Section',
			custom : 'my places',
			myRoute : 'My Route',
			numStops : 'Stops',
			startAddress : 'Start',
			endAddress : 'Finish',
			arriveAddress : 'Arrive',
			tripInfo : 'Trip',
			viewPrint : 'View Printable Directions',
			printDirections : 'Print'
		};
		$.extend(this._defaults, this._regional['']);
	}
	
	$.extend(MSNMap.prototype, {
		setDefaults : function(settings) {
			$.extend(this._defaults, settings || {});
		},
		initializeMap : function() {
			if(!this._defaults.printMap) {
				this.activateControls();
			}
			this.getData();
			
			$(window).unload(function() { if(this._map) { this._map.Dispose(); } });
		},
		activateControls: function() {	
			var obj = this;
						
			if(obj._defaults.sortable) {
				$("#route-list").sortable({ 
					items: "li", 
					revert: false, 
					handle: "span", 
					containment: '#route-list', 
					axis: 'y' 
				}); 
			}			

			$('#get-route').click(function() { obj.getRouteList(); return false; });
			$('#clear-route').click(function() { obj.clearRouteList(); return false; });
			$('#cstm-submit').click(function() {  if (obj._defaults.simpleMap) {obj.clearRouteList();} obj.findCustom($.trim($('#cstm-name').val()), $.trim($('#cstm-address').val()), true); return false; });
			$('#cstm-reverse').click(function() { if (obj._defaults.simpleMap) {obj.clearRouteList();} obj.findCustom(); return false; });
			$('#cstm-reset').click(function() { $(this).parents('form').trigger('reset'); if(obj._defaults.simpleMap) {obj.clearRouteList();} return false; });
			
			if(obj._defaults.context) {
				$('#context-add a').click(function() {
					if($('#context-add div').is(':visible')) {
						$('#context-add div').hide();
					} else {
						$('#context-add div').show();
					}
					return false;
				});	
				$('.context-zoom').click(function() {
						obj._map.SetCenterAndZoom(obj._contextPin, this.rel);
						obj.hideContext();
				});
				$('.context-center').click(function() {
						obj._map.SetCenter(obj._contextPin);
						obj.hideContext();
				});
			}
		},
		getData : function() {
			var obj = this;
			$.get(obj._defaults.xml, function(data) { 
				var items = [];
				$('item', data).each(function(i) {
					items[i] = {};
					$(this).children().each(function() {
						items[i][this.tagName] = $(this).text();									 
					});
				});
				obj._data = items;
				
				if(obj._defaults.acceptQuery) {
					var q = window.location.search.substring(1);
					if(q.length>0) {
						var query = obj.parseQuery(q);
						if(query.ids!==null&&query.ids!==undefined) {
							obj._loadIds = query.ids.split(',');
						}
					}
				}
				if(!obj._defaults.printMap) {
					obj.createMap();
				} else { 
					obj.createPrintMap();
				}
				if(q!==undefined&&q.length>0) {
					if(query.name!==undefined && query.address!==undefined) {
						obj.findCustom(query.name, query.address);
					}
				}
			});
		},
		parseQuery : function(str) {
			var vars = str.split("&");
			var pairs = {};
			for (var i=0;i<vars.length;i++) {
				var p = vars[i].split("=");
				pairs[p[0]] = this.urlDecode(p[1]);
			}
			return pairs;
		},
		urlDecode : function(encodedString) {
			var output = encodedString;
			var binVal, thisString;
			var myregexp = /(%[^%]{2})/;
			while ((match = myregexp.exec(output)) !== null && match.length > 1 && match[1] !== '') {
				binVal = parseInt(match[1].substr(1),16);
				thisString = String.fromCharCode(binVal);
				output = output.replace(match[1], thisString);
			}
			return output;
		},
		createMap : function() {
			var obj = this;
			obj._map = new VEMap(obj._id);
			switch(this._defaults.dashboard) {
				case 'Tiny':
					this._map.SetDashboardSize(VEDashboardSize.Tiny);
					break;
				case 'Small':
					this._map.SetDashboardSize(VEDashboardSize.Small);
					break;
				case 'Hide':
					this._map.HideDashboard();
					break;
			}
			
			var fp = obj.getLatLong(obj._data[0]['georss:point']);
			obj._map.LoadMap(new VELatLong(fp[0], fp[1]), 11);
			obj._map.SetMapStyle(VEMapStyle.Shaded);
			obj._map.SetMouseWheelZoomToCenter(false);
			obj._map.Hide3DNavigationControl();
			
			if(obj._defaults.context) {
				obj._map.AttachEvent("onclick", function(e) { obj.showContext(obj, e); });
				obj._map.AttachEvent("onmousedown", this.hideContext);
			}
			
			if(obj._defaults.miniMap) { 
				obj.showMiniMap(); 
			}
				
			if (obj._defaults.simpleMap) {
				$('#route').hide();
				$('#places-wrapper').hide();
			}

			obj.getCategories();
		},
		createPrintMap : function() {
			var obj = this;
			obj._map = new VEMap(obj._id);
			this._map.HideDashboard();
			obj._map.LoadMap();
			obj._map.SetMapStyle(VEMapStyle.Shaded);
			obj._map.Hide3DNavigationControl();
			obj.getRouteList();
		},
		getLatLong : function(point) {
			var pts = point.split(" ");
			return pts;
		},
		showMiniMap : function() {
			var obj = this;
			var pos = $('#'+obj._id).height();
								
			var block = $('<div />')
						.addClass('mini-control').attr({id: 'mini-show', title: obj._defaults.showMiniMap})
						.toggle(function() {
							block.hide();
							$('#MSVE_minimap').animate({top: "-=152px"}, "slow", function() {
								block.attr({id: 'mini-hide', title: obj._defaults.hideMiniMap}).show();
							}); 
						}, function() {
							block.hide();
							$('#MSVE_minimap').animate({top: "+=152px"}, "slow", function() {
								block.attr({id: 'mini-show', title: obj._defaults.showMiniMap}).show();
							});
						});
        	
			if (this._map.GetMapMode() == VEMapMode.Mode3D) { obj._map.SetMapMode(VEMapMode.Mode2D); }
            this._map.ShowMiniMap(0, pos, VEMiniMapSize.Small);  
			
			$('#MSVE_minimap_resize').remove();
			$('#'+obj._id).append(block);
		},
		getCategories : function() {
			var obj = this;
			$.each(this._data, function(i, val) {
				var dup = false;
				var tem = val.type;
				$.each(obj._categories, function(j, val2) {
					if(tem == val2) { 
						dup = true; 
					}
				}); 
				if(!dup) { 
					obj._categories.push(tem); 
				}
			});
			if(this._defaults.listView||this._loadIds!==null||!this._defaults.printNames||this._defaults.simpleMap) {
				this.loadPoints(null);
			} else {
				this.printCategories();
				this.loadPoints(this._categories[0]);
			}
		},
		printCategories : function() {
			var obj = this;
			var div = $('<div />').attr('id', 'categories').insertBefore($('#'+this._id));
			$.each(this._categories, function(i, val) {
				$('<div />').addClass('category').html(val)
					.click(function() {
						obj.loadPoints(val);
					}).appendTo(div);					  
			});
		},
		loadPoints : function(cat) {
			var obj = this;
			var cats = cat===null?obj._categories:[cat];
			var notMade = obj.checkLayers(cat);

			if(notMade) {
				$.each(cats, function(i, val) {
					var layer = new VEShapeLayer();
					layer.id = val;
					//layer.SetClusteringConfiguration(VEClusteringType.Grid);
					var shapes = [];
					var block = obj.createBlock(layer, val);
					
					$.each(obj._data, function(j) {
						if(obj._data[j].type==val) {
							if(obj._loadIds!==null) {
								$.each(obj._loadIds, function(k, val2) {
									if(obj._data[j].id==val2) {
										var d = obj.createPoints(layer, shapes, j);
										$('> div:last', block).append(d);
									}
								});
							} else {
								var d = obj.createPoints(layer, shapes, j);
								$('> div:last', block).append(d);
							}
						}
					});
					
					if(obj._loadIds!==null&&$('.place', block).length>0) {
						$('#places-wrapper').append(block);
					} else if(obj._defaults.printNames&&obj._loadIds===null) {
						$('#places-wrapper').append(block);
					}
					
					if(shapes.length) {
						obj._map.AddShapeLayer(layer);
						layer.AddShape(shapes);
					}
				});
				if(!obj._defaults.listView) {
					obj.loadAccordion();
				}
				
				if (obj._defaults.simpleMap) {
					obj.addToRoute('0',this);
				}
				
				if((obj._defaults.listView&&obj._defaults.hotelOnly&&obj._loadIds===null) || obj._defaults.simpleMap) {
					$('.place:gt(0) .name').trigger('click');
					$('.place:eq(0) .pushpin').trigger('click');
				} else {
					obj.getBestView();
				}
			}
		}, 
		createBlock : function(layer, val) {
			var block = $('<div />').addClass('places').attr('id', 'cat-'+val.replace(/\W/gi,''));
			$('<div />').addClass('container').appendTo(block);
			if(!this._defaults.listView) { 
				var title = $('<div />').addClass('title').html(val).prependTo(block);
				if(this._loadIds===null) {
					$('<span title="'+this._defaults.hideSection+'" />').addClass('close').html('X')
						.click(function() {
							var el = $(this).parents('.places');
							layer.Hide();
							if($(el).hasClass('selected')) {
								var sibs = $(el).nextAll(':visible');
								if(!sibs.length) { 
									sibs = $(el).prevAll(':visible');
								}
								$("#places-wrapper").accordion("activate", $('.title', $(sibs[0])));
							}
							$(el).slideUp('fast');

							return false;
						}).prependTo(title);
				}
			}
			return block;
		},
		checkLayers : function(cat) {
			var mapLayers = this._map.GetShapeLayerCount();
			for(var h=0; h<mapLayers; h++) {
				var l = this._map.GetShapeLayerByIndex(h);
				if(l.id!==undefined && l.id==cat) {
					l.Show();
					$('#cat-'+cat.replace(/\W/gi,'')).show();
					$("#places-wrapper").accordion("activate", $('.title', '#cat-'+cat.replace(/\W/gi,'')));
					this.getBestView();
					return false;
				}
			}
			return true;
		},
		createPoints : function(layer, shapes, j, cstm) {
			var obj = this;
			var info = '';
			var point = obj.getLatLong(obj._data[j]['georss:point']); 
			var shape = new VEShape(VEShapeType.Pushpin, new VELatLong(point[0], point[1]));
			shapes.push(shape);
			
			var shapeCount = cstm?obj._cstmCount:shapes.length;
			var newShapeCount = shapeCount;
			if ( newShapeCount == 1 ){
				newShapeCount = '';
			}
			shape.SetCustomIcon('<div class="pushpin"><img src="'+obj._data[j].pushpin+'" alt="" /><span>'+newShapeCount+'</span></div>');
			shape.SetTitle(obj._data[j].title);
			if(obj._data[j].image!==''&&obj._data[j].image!==undefined) {
				info += '<div id="pin-image"><img src="'+obj._data[j].image+'" alt="" /></div>';
			}
			if(obj._data[j].description!==''&&obj._data[j].description!==undefined) {
				info += obj._data[j].description;
			}
			if(obj._data[j].moreinfo!==''&&obj._data[j].moreinfo!==undefined) {
				info += '<p><a onclick="map.HideInfoBox();" href="'+obj._data[j].moreinfo+'" target="_blank">More Info</a></p>';
			}
			shape.SetDescription(info);
			
			if(obj._defaults.printNames) {
				var pin = $('<div title="'+obj._defaults.centerPoint+'" />').addClass('pushpin')
					.append('<img src="'+obj._data[j].pushpin+'" alt="" />')
					.click(function () {
						obj.centerMap(layer.GetShapeByIndex(shapeCount-1), 15);
					}).append('<span>'+newShapeCount+'</span>');
				
				var name = $('<div title="'+obj._defaults.hidePoint+'" />').addClass('name').html(obj._data[j].title).click(function() { 
						obj.createCover(this, layer.GetShapeByIndex(shapeCount-1));												 
						obj._map.HideInfoBox(); 
					});
				
				var add = $('<div title="'+obj._defaults.addToPlanner+'" />').addClass('add-route').html(obj._defaults.addToPlanner).click(function() {
						obj.addToRoute(j, this);
						$(this).hide();
					});
							
				var div = $('<div />').addClass('place').prepend(pin).append(name, add)
					.hover(function() { obj.showInfoBox(layer.GetShapeByIndex(shapeCount-1)); }, 
						   function() { obj._map.HideInfoBox(); 
					});
					
				return div;
			}		
		},
		createCover : function(el, shape) {
			var obj = this;
			var p = $(el).parents('.place');
			var _height = p.height()+parseInt(p.css('padding-top'), 10)+parseInt(p.css('padding-bottom'), 10);
			var _width =  p.width()+parseInt(p.css('padding-left'), 10)+parseInt(p.css('padding-right'), 10);
			
			shape.Hide();
			p.css('position', 'relative');
			var cover = $('<div title="'+obj._defaults.showPoint+'" />').height(_height	).width(_width)
				.css({backgroundColor: '#000', position: 'absolute', top: '0', left: '0', zIndex: '500', opacity: '.15'})
				.prependTo(p).click(function() {
					p.removeAttr('style');
					$(this).remove();
					shape.Show(); 
					obj.showInfoBox(shape); 
				});
				
			if ( !$.support.opacity ) {
			    p.height(_height-parseInt(p.css('padding-top'), 10)-parseInt(p.css('padding-bottom'), 10));
			    cover.css('filter', 'alpha(opacity=15)');
			}
		},
		findCustom: function(cstmName, cstmAddress, prepend) {
			var obj = this;
			var name = cstmName?cstmName:$.trim($('#cstm-name').val());
			var address = cstmAddress?cstmAddress:$.trim($('#cstm-address').val());
			try {
				//what, where, findType, shapeLayer, startIndex, numberOfResults, showResults, createResults, useDefaultDisambiguation, setBestMapView, callback
				obj._map.Find(null, address, null, null, null, null, null, null, null, false, function(l, resultsArray, places, hasMore, veErrorMessage) {
					if(places) {
						obj.loadCustom(obj._regional[''].custom, name, places, prepend);
					}
				});
			} catch(e) { alert(e.message); }
		},
		loadCustom : function(cat, name, point, prepend) {
			var num = this._data.length;
			var shapes = []; 
			var notMade = this.checkLayers(cat);
			var layer = null;
			var block = null;
			if(notMade) {
				layer = new VEShapeLayer();
				layer.id = cat;
				block = this.createBlock(layer, cat);
				if(this._loadIds===null&&!this._listView) {
					var obj = this;
					$('<div />').addClass('category').html(cat)
						.click(function() {
							obj.loadPoints(cat);
						}).appendTo('#categories');
				}
			} else {
				var mapLayers = this._map.GetShapeLayerCount();
				for(var h=0; h<mapLayers; h++) {
					var l = this._map.GetShapeLayerByIndex(h);
					if(l.id==this._regional[''].custom) {
						layer = l;
						block = $('#cat-custom');
						break;
					}
				}
			}
			
			this._data[num] = {};
			this._data[num].id = num;
			this._data[num].title = name;
			this._data[num].address = point[0].Name;
			this._data[num].description = '';
			this._data[num].image = '';
			this._data[num].pushpin = '/images/map/pins/pin-violet.gif';
			this._data[num].moreinfo = '';
			this._data[num]['georss:point'] = point[0].LatLong.Latitude+" "+point[0].LatLong.Longitude;
			this._data[num].type = cat;
			this._cstmCount++;

			var d = this.createPoints(layer, shapes, num, this._cstmCount);
			$('> div:last', block).append(d);

			if(notMade) { 
				this._map.AddShapeLayer(layer); 
				layer.AddShape(shapes);
				if (!this._defaults.simpleMap) {
					this.getBestView();
				}
			}
			if(this._defaults.simpleMap) {
				this.addToRoute(num,this);
				this.getRouteList(prepend);
			}

			if(this._defaults.printNames) { 
				$('#places-wrapper').append(block);	
				if(notMade&&!this._defaults.listView) {
					this.loadAccordion(); 
				}
			}
		},
		loadAccordion : function() {
			$("#places-wrapper").accordion("destroy").find('.selected').removeClass('selected');
			$('.ui-accordion-left, .ui-accordion-right').remove();
			$("#places-wrapper").accordion({ 
				header: '.title', 
				clearStyle: true,
				autoHeight: false
				});
			if(this._loadIds===null) {
				$("#places-wrapper").accordion("activate", '.title:last');
			}
		},
		getBestView : function() {
			var mapLayers = this._map.GetShapeLayerCount();
			var viewArray = [];
			for(var i=0; i<mapLayers; i++) {
				var l = this._map.GetShapeLayerByIndex(i);
				var c = l.GetShapeCount();
				for(var j=0; j<c; j++) {
					viewArray.push(l.GetShapeByIndex(j));
				}
			}
			this._map.SetMapView(viewArray);
		},
		showInfoBox : function(shape) {
			if(shape.GetVisibility()) {
				this._map.ShowInfoBox(shape);
			}
		},
		centerMap : function(shape, zoom) {
			var obj = this;
			obj._map.HideInfoBox();
			obj._map.SetCenterAndZoom(new VELatLong(shape.Latitude, shape.Longitude), zoom);
			setTimeout(function(){obj.showInfoBox(shape);}, 500);
		},
		addToRoute : function(num, el) {
			var name = this._defaults.sortable?'<span class="dragPoint">'+this._data[num].title+'</span>':this._data[num].title;
			var routeItem = $('<li />').html(name).data('point', num);
			
			$('<img class="remove" title="'+this._defaults.removePlanner+'" src="'+RELPATH+'images/layout/map-x.gif" />').click(function() {
				$(this).parents('li').remove();
				$(el).show();
			}).appendTo(routeItem);
			
			$('#route-list').append(routeItem);
			
			if(this._defaults.sortable) {
				$('#route-list').sortable('refresh');				
			}
			
		},
		getRouteList : function(reverseRoute) {
			var obj = this;
			var myOptions = new VERouteOptions();
			var routeOrder = [];
			obj.routePoints = [];
			
			if(!obj._defaults.printMap) {
				$('li', '#route-list').each(function(i) {
					var tempID = $(this).data('point');
					var place = obj._data[tempID].address;
					if(place==='') {
						var shape = obj.getLatLong(obj._data[tempID]['georss:point']);
						place = new VELatLong(shape[0], shape[1]);
					}
					if(reverseRoute) {
						obj.routePoints.unshift(place);
						routeOrder.unshift(place);
					}
					else {
						obj.routePoints.push(place);
						routeOrder.push(place);
					}
				});
			} else {
				$('span', '#addresses').each(function(i) {
					var place = $(this).text();
					if(reverseRoute) {
						obj.routePoints.unshift(place);
						routeOrder.unshift(place);
					}
					else {
						obj.routePoints.push(place);
						routeOrder.push(place);
					}
				});
			}
			if(routeOrder.length < 2) {
				alert("Please add another location to your trip");
			} else {
				myOptions.DrawRoute      = true;
				myOptions.Element 		 = obj;
				myOptions.RouteCallback  = obj.printRoute;
				if(obj._defaults.kilometers) {
					myOptions.DistanceUnit = VERouteDistanceUnit.Kilometer;
				}
				obj._map.GetDirections(routeOrder, myOptions);		
			}
		},
		clearRouteList : function(prepend) {
			if (this._defaults.simpleMap) {
				$('#route-list li:gt(0)').remove();
			}
			else {
				$('.remove', '#route-list').trigger('click');	
			}
			$('#route-info').html('').dialog("close");
			$('.show-dir').remove();
			
			try { this._map.DeleteRoute(); }            
			catch (err) { alert(err.message); } 
			
			this.getBestView();
		},
		printRoute : function(route) {
			var obj = this.Element;
			
			if(!obj._defaults.printMap) {
				var l = '/print-map.php?address[]=';
				$.each(obj.routePoints, function(i) {
					l += obj.routePoints[i];	
					if((i+1)!=obj.routePoints.length) {	l += '&address[]='; }
				});
				
				$('.show-dir').remove();
				var temp = obj._defaults.simpleMap?'#add-custom .buttons':'#route';
				$('<div />').addClass('show-dir').html(obj._regional[''].viewPrint).click(function() {
					var printWindow = window.open(l, 'PrintWindow', 'status=1, menubar=1, location=1, resizable=1, scrollbars=1, width=850, height=700');
					printWindow.moveTo(0,0);
				}).appendTo(temp).effect("highlight", {}, 3000);
			} else {
				var legs     = route.RouteLegs;
				var _unit 	 = obj._defaults.kilometers?' km':' mi';
				var time 	 = obj.convertTime(route.Time);

				var stops 	 = legs.length>1?'<strong>'+obj._regional[''].numStops + ': </strong>'+ (legs.length-1):'&nbsp;';
				var info = '<table cellspacing="0" cellpadding="3" id="print-directions">\n';
					info += '	<tr>\n';
					info += '		<td class="first"><strong>'+obj._regional[''].startAddress + ': </strong>'+ obj.routePoints[0]+'</td>\n';
					info += '		<td>'+stops+'</td>\n';
					info += '	</tr>\n';
					info += '	<tr>\n';
					info += '		<td class="first"><strong>'+obj._regional[''].endAddress + ': </strong>'+obj.routePoints[(obj.routePoints.length-1)]+'</td>\n';
					info += '		<td><strong>'+obj._regional[''].tripInfo + ': </strong>' + route.Distance.toFixed(1) + _unit + ', ' + time + '</td>\n';
					info += '	</tr>\n';
					info += '</table>';
				$(info).insertAfter('h1');
				
				var numTurns = 0;
				var numStops = 0;
				var leg      = null;

				var turns	 = '<ol>';
				turns += '<li class="location"><span class="pre">'+obj._regional[''].startAddress + ': </span><div>'+obj.routePoints[0]+'</div></li>';
				$.each(legs, function(i) {
					leg = legs[i]; 
					numStops++;
					var turn = null;  
					$.each(leg.Itinerary.Items, function(j) {
						turn = leg.Itinerary.Items[j];

						numTurns++;
						if(numTurns!==leg.Itinerary.Items.length) {
							turns += '<li><span class="pre">' + numTurns + '.</span><span class="text">' + turn.Text + '</span><span class="distance">' + turn.Distance.toFixed(1) + _unit +'</span></li>';
						}
					});
					numTurns=0;
					if(numStops!==legs.length) {
						turns += '<li class="location"><span class="pre">'+obj._regional[''].arriveAddress + ': </span><div> '+obj.routePoints[numStops]+'</div><span class="distance">' + leg.Distance.toFixed(1) + _unit +'</span></li>';
					}
				});
				turns += '<li class="location"><span class="pre">'+obj._regional[''].endAddress + ': </span><div>'+obj.routePoints[numStops]+'</div><span class="distance">' + route.Distance.toFixed(1) + _unit +'</span></li>';
				turns += '</ol>';
				$('#route').append(turns); 		
			}
		},
		convertTime : function(time) {
			var hrs, mins;
			var tmpTime = ((time/60)/60).toString();
			
			if(tmpTime.indexOf('.')!=-1) {
				var tmp = tmpTime.split('.');
				hrs = tmp[0];
				mins = parseFloat("."+tmp[1]);
				mins = ((mins*60)/100).toFixed(2);
				mins = parseInt(mins*100, 10);
			}
			
			return (hrs>0?hrs + " hours ":"") + mins + " minutes";
		},
		showContext : function(obj, ev) {
			if(ev.rightMouseButton) {
				obj._contextPin = obj._map.PixelToLatLong(new VEPixel(ev.mapX, ev.mapY));
				var offset = $('#context-menu').parent().offset();
				var osLeft = $('#context-menu').parent()[0].offsetLeft;
				var osTop = $('#context-menu').parent()[0].offsetTop;
				obj._map.FindLocations(obj._contextPin, function(locations) {
					if(locations!==null) { 
						$('#context-title').html(locations[0].Name+'<br />');
					}
					$('#context-title').append("Lat: "+obj._contextPin.Latitude.toFixed(2)+" Long: "+obj._contextPin.Longitude.toFixed(2));
					
					$('<img />').addClass('context-pin')
						.attr('src', '/images/map/red_circ.gif')
						.css({position: 'absolute', left: (ev.clientX-offset.left+osLeft)+'px', top: (ev.clientY-offset.top+osTop)+'px', cursor: 'pointer', zIndex: 1000})
						.insertBefore('#context-menu');
						
					$('#context-menu').css({left: (ev.clientX-offset.left+osLeft)+10+'px', top: (ev.clientY-offset.top+osTop)+'px'}).show();
					
				  $('#context-add form').bind("submit", function() {
						if(locations===null) {
							locations = [];
							locations[0] = {};
							locations[0].Name = '';
							locations[0].LatLong = {};
							locations[0].LatLong.Latitude = obj._contextPin.Latitude;
							locations[0].LatLong.Longitude = obj._contextPin.Longitude;
						}
																 
						obj.loadCustom('custom', $('#context-add #name').val(), locations);

						$('#context-add div').hide().find('#name').val('');
						obj.hideContext();
						
						return false;
					});
				});			
			}
		},
		hideContext : function() {
			$('#context-title').html('');
			$('#context-menu, #context-add div').hide();
			$('#context-add form').unbind("submit");
			$('.context-pin').remove();
		}
	});
	
	$.fn.msnMap = function(options){
		return this.each(function() {
			$.msnMap._id = this.id
			$.msnMap.setDefaults(options);
			$.msnMap.initializeMap();
		});
	};
	$.msnMap = new MSNMap();
})(jQuery);
