/*global jQuery:false, console:false, _:false, CommonManager:false,Registry:false, wp:false*/
Registry.register("Event",
	(function($) {
		"use strict";
		var state;

		function createInstance() {

			return {
				event_id: '',
				eventsData: {},
				/**
				 * Init
				 */
				init: function() {
					state.initTimePicker();
					state.addEventButton();
					state.initDeleteButtons();
					state.initEditButtons();
					state.initColorPicker();
					state.initDatePicker();
					state.columnRadioBox();
				},
				/**
				 * Init time picker
				 */
				initTimePicker: function() {
					var timeFormat = Boolean(parseInt($('#time_format').val()));
					$('#event_start').timepicker({
						showPeriod: timeFormat,     // Define whether or not to show AM/PM with selected time. (default: false)
						showPeriodLabels: timeFormat,
						defaultTime: '00:00'
					});
					$('#event_end').timepicker({
						showPeriod: timeFormat,     // Define whether or not to show AM/PM with selected time. (default: false)
						showPeriodLabels: timeFormat,
						defaultTime: '00:00'
					});
				},
				/**
				 * Init widget slider
				 * @param selector
				 * @param autoScroll
				 */
				initSlider: function(selector, autoScroll) {
					var play = _.isUndefined(autoScroll) ? false : Boolean(autoScroll);
					var id = selector.replace(/^\D+/g, '');
					$(selector).carouFredSel({
						items: {
							visible: 3
						},
						direction: "up",
						scroll: {
							items: 1,
							easing: "swing",
							pauseOnHover: true,
							onAfter: function(data) {
								data.items.old.each(function() {
										$(this).removeClass('visible');
									}
								);
								data.items.visible.each(function() {
										$(this).addClass('visible');
									}
								);
							}
						},
						auto: {
							play: play,
							timeoutDuration: 3000
						},
						prev: {
							button: "#mp_prev_button" + id
						},
						next: {
							button: "#mp_next_button" + id
						}
					});

					$(selector).trigger("currentVisible", function(items) {
						items.addClass("visible");
					});
					state.setColorSettings(selector + ' ' + '.mptt-colorized');
				},
				/**
				 * Init color picker
				 */
				initColorPicker: function(parent) {
					if (_.isUndefined(parent)) {
						parent = '';
					}
					var selectorColorInput = $(parent + ' input.clr-picker');
					var selectorTextInput = $(parent + ' input.regular-text');
					selectorColorInput.spectrum("destroy");

					// init color picker
					selectorColorInput.spectrum({
						preferredFormat: "rgb",
						showInput: true,
						showAlpha: true,
						allowEmpty: true,
						palette: [
							["#000", "#444", "#666", "#999", "#ccc", "#eee", "#f3f3f3", "#fff"],
							["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"],
							["#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#cfe2f3", "#d9d2e9", "#ead1dc"],
							["#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#9fc5e8", "#b4a7d6", "#d5a6bd"],
							["#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6fa8dc", "#8e7cc3", "#c27ba0"],
							["#c00", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3d85c6", "#674ea7", "#a64d79"],
							["#900", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#0b5394", "#351c75", "#741b47"],
							["#600", "#783f04", "#7f6000", "#274e13", "#0c343d", "#073763", "#20124d", "#4c1130"]
						],
						showPalette: true,
						show: function(color) {
							$(this).val(color);
						},
						hide: function(color) {
							var parent = $(this).parents('.select-color');
							parent.find('.regular-text').val($(this).val());
						},
						change: function(color) {
							var parent = $(this).parents('.select-color');
							parent.find('input:not([type="hidden"])').val($(this).val());
						}
					});

					//change color preview block
					selectorTextInput.off('keyup').on('keyup', function() {
						var parentTr = $(this).parents('.select-color');
						var spectrumElement = parentTr.find('.clr-picker');
						var colorElement = parentTr.find(".regular-text").val();
						var preview_inner = parentTr.find(".sp-preview-inner");
						preview_inner.css({
							'background-color': colorElement
						});
						spectrumElement.spectrum("set", colorElement);
					});
				},
				/**
				 * Add event
				 */
				addEventButton: function() {
					$('#add_mp_event').off('click').on('click', function() {
						if ($(this).hasClass('edit')) {
							state.updateEventData();
						} else {
							//state.renderEventsBlock();
							state.validateEventData();
							if (state.validateEventData()) {
								state.renderEventItem();
							}
						}
					});
				},
				/**
				 * init event data delete button
				 */
				initDeleteButtons: function() {
					$('#events-list #delete-event-button').off('click').on('click', function() {
						var id = $(this).attr('data-id');
						state.deleteEvent(id);
					});
				},
				/**
				 * init event data edit button
				 */
				initEditButtons: function() {
					$('#events-list #edit-event-button').off('click').on('click', function() {
						var id = $(this).attr('data-id');
						$(this).parent().find('.spinner').addClass('is-active');
						Registry._get("adminFunctions").wpAjax(
							{
								controller: "events",
								action: "get_event_data",
								id: id
							},
							function(data) {
								$('#events-list .spinner').removeClass('is-active');
								$('#event_start').val(data.event_start);
								$('#event_end').val(data.event_end);
								$('#description').val(data.description);
								$('#user_id').val(data.user_id);
								$('#weekday_id').val(data.column_id);
								$('#add_mp_event').addClass('edit');
								$('#add_mp_event').val('Update');
								state.event_id = data.id;
							},
							function(data) {
								console.warn(data);
							}
						);
					});
				},
				/**
				 * Delete event data by id
				 * @param id
				 */
				deleteEvent: function(id) {
					Registry._get("adminFunctions").wpAjax(
						{
							controller: "events",
							action: "delete",
							id: id
						},
						function(data) {
							if ($('#events-list tr[data-id="' + id + '"]').length) {
								$('#events-list tr[data-id="' + id + '"]').remove();
							}
						},
						function(data) {
							console.log(data);
						}
					);
				},
				/**
				 * Update event item
				 */
				updateEventItem: function() {
					var item = $('#events-list tr[data-id="' + state.event_id + '"]');
					item.find('td.event-column').text($('#weekday_id option:selected').text());
					item.find('td.event-start').text($('#event_start').val());
					item.find('td.event-end').text($('#event_end').val());
					item.find('td.event-description').text($('#description').val());
					state.event_id = null;
					$('#add_mp_event').removeClass('edit').val('Add Time Slot');
				},
				/**
				 * Update Event data
				 */
				updateEventData: function() {
					$('#add_event_table .spinner').addClass('is-active');
					Registry._get("adminFunctions").wpAjax(
						{
							controller: "events",
							action: "update_event_data",
							data: {
								id: Registry._get("Event").event_id,
								event_start: $('#event_start').val(),
								event_end: $('#event_end').val(),
								description: $('#description').val(),
								user_id: $('#user_id').val(),
								weekday_ids: $('#weekday_id').val()
							}
						},
						function(data) {
							$('#add_event_table .spinner').removeClass('is-active');
							state.updateEventItem();
							state.clearTable();
						},
						function(data) {
							$('#add_event_table .spinner').removeClass('is-active');
							console.log(data);
						}
					);
				},
				/**
				 * Render event item
				 */
				renderEventItem: function() {
					var column_ID = $('#weekday_id option:selected').val();
					var template = {
						tag: 'tr',
						attrs: {},
						content: [
							{
								tag: 'td',
								attrs: {'style': 'display:none;'},
								content: [
									{
										tag: 'input',
										attrs: {
											'type': 'hidden',
											'name': 'event_data[' + column_ID + '][weekday_ids][]',
											'value': column_ID
										}
									},
									{
										tag: 'input',
										attrs: {
											'type': 'hidden',
											'name': 'event_data[' + column_ID + '][event_start][]',
											'value': $('#event_start').val()
										}
									},
									{
										tag: 'input',
										attrs: {
											'type': 'hidden',
											'name': 'event_data[' + column_ID + '][event_end][]',
											'value': $('#event_end').val()
										}
									},
									{
										tag: 'input',
										attrs: {
											'type': 'hidden',
											'name': 'event_data[' + column_ID + '][description][]',
											'value': $('#description').val()
										}
									},
									{
										tag: 'input',
										attrs: {
											'type': 'hidden',
											'name': 'event_data[' + column_ID + '][user_id][]',
											'value': $('#user_id').val()
										}
									}
								]
							},
							{
								tag: 'td',
								attrs: {
									'class': 'event-column'
								},
								content: [$('#weekday_id option:selected').text()]
							},
							{
								tag: 'td',
								attrs: {
									'class': 'event-start'
								},
								content: [$('#event_start').val()]
							},
							{
								tag: 'td',
								attrs: {
									'class': 'event-end'
								},
								content: [$('#event_end').val()]
							},
							{
								tag: 'td',
								attrs: {
									'class': 'event-description'
								},
								content: [$('#description').val()]
							},
							{
								tag: 'td',
								attrs: {},
								content: []
							},
						]
					};

					var htmlObject = Registry._get("adminFunctions").getHtml(template);
					$('#events-list tbody').append(htmlObject);
					//state.initDeleteButton();
					state.clearTable();
				},
				renderShortcodeEventItem: function(columnId, eventID, trIndex) {
					var tdTemplate = {
						tag: 'td',
						attrs: {
							'class': 'mptt-shortcode-event',
							'data-column-id': columnId,
							'rowspan': ''
						},
						content: []

					};

					var td = $(Registry._get("adminFunctions").getHtml(tdTemplate));
					//var end = 0;
					//	var events = state.eventsData[columnId].events;

					if (eventID !== 'all') {
						$.each(state.eventsData[columnId].events, function(index, eventObject) {
							if (!_.isUndefined(eventObject)) {
								if (eventObject.eventId === eventID && eventObject.dataStartItem === trIndex) {
									if (state.eventsData[columnId].events[index].output === true) {
										return;
									}
									state.eventsData[columnId].events[index].output = true;
									td.addClass('event');
									td.append(state.renderEventContainer(eventObject));
									//if (parseInt(end) < parseInt(eventObject.dataEnd)) {
									//	end = eventObject.dataEnd;
									//}

									//$.each(events, function(subIndex, subEventObject) {
									//	if (!_.isUndefined(subEventObject)) {
									//		if (subEventObject.eventId === eventID) {
									//			if (subEventObject.output === true) {
									//				return;
									//			}
									//			if (parseInt(subEventObject.dataStart) <= parseInt(end)) {
									//				state.eventsData[columnId].events[subIndex].output = true;
									//				td.append(state.renderEventContainer(subEventObject));
									//				if (parseInt(end) < parseInt(subEventObject.dataEnd)) {
									//					end = subEventObject.dataEnd;
									//				}
									//			}
									//		}
									//	}
									//});
								}
							}
						});
					} else {
						$.each(state.eventsData[columnId].events, function(index, eventObject) {
							if (!_.isUndefined(eventObject)) {
								if (eventObject.dataStart === trIndex) {
									if (state.eventsData[columnId].events[index].output === true) {
										return;
									}
									state.eventsData[columnId].events[index].output = true;
									td.append(state.renderEventContainer(eventObject));
									td.addClass('event');
									//
									//if (parseInt(end) < parseInt(eventObject.dataEnd)) {
									//	end = eventObject.dataEnd;
									//}

									//$.each(events, function(subIndex, subEventObject) {
									//	if (!_.isUndefined(subEventObject)) {
									//		if (subEventObject.output === true) {
									//			return;
									//		}
									//		if (parseInt(subEventObject.dataStart) <= parseInt(end)) {
									//			state.eventsData[columnId].events[subIndex].output = true;
									//			td.append(state.renderEventContainer(subEventObject));
									//			if (parseInt(end) < parseInt(subEventObject.dataEnd)) {
									//				end = subEventObject.dataEnd;
									//			}
									//		}
									//	}
									//});
								}
							}
						});
					}

					return td;
				},
				/**
				 * Render Container
				 * @param event
				 */
				renderEventContainer: function(event) {
					var eventContainer = {
						tag: 'div',
						attrs: {
							'data-id': event.id,
							'data-event-id': event.eventId,
							'data-start': event.dataStart,
							'data-start-item': event.dataStartItem,
							'data-end': event.dataEnd,
							'data-color': event.dataColor,
							'data-hover_color': event.dataHoverColor,
							'data-bg_color': event.dataBgColor,
							'data-bg_hover_color': event.dataBgHoverColor,
							'data-type': "event",
							'style': event.style,
							'class': 'mptt-event-container id-' + event.id + ' mptt-hidden mptt-colorized'
						},
						content: [{
							tag: event.eventHeaderTag,
							attrs: {
								'title': event.eventHeader,
								'class': 'event-title',
								'href': _.isUndefined(event.eventHeaderHref) ? '' : event.eventHeaderHref,
								'style': _.isUndefined(event.eventHeaderStyle) ? '' : event.eventHeaderStyle
							},
							content: [event.eventHeader]
						}, {
							tag: 'p',
							attrs: {
								'class': 'timeslot'
							},
							content: [{
								tag: 'span',
								attrs: {
									'class': 'timeslot-start'
								},
								content: [event.topHour]
							},
								{
									tag: event.timeslotDelimiterTag,
									attrs: {
										'class': 'timeslot-delimiter'
									},
									content: [event.timeslotDelimiter]
								},
								{
									tag: 'span',
									attrs: {
										'class': 'timeslot-end'
									},
									content: [event.bottomHour]
								}]
						},
							{
								tag: 'p',
								attrs: {
									'class': 'event-subtitle'
								},
								content: [event.subTitle]
							},
							{
								tag: 'p',
								attrs: {
									'class': 'event-description'
								},
								content: [event.eventDescription]
							}, {
								tag: 'p',
								attrs: {
									'class': 'event-user'
								}, content: [event.afterText]
							}
						]
					};

					return Registry._get("adminFunctions").getHtml(eventContainer);
				},
				/**
				 * Render events block
				 */
				renderEventsBlock: function() {
					if (!$('#mp_events_data #events-list').length) {
						var template = {
							tag: 'ul',
							attrs: {
								'id': 'events-list'
							},
							content: []
						};

						$('#add_event_table').before(Registry._get("adminFunctions").getHtml(template));
					}

				},
				/**
				 * Set user color settings
				 * @param selector
				 */
				setColorSettings: function(selector) {
					if (_.isUndefined(selector)) {
						selector = '.mptt-colorized';
					}

					var elements = $(selector);
					var height = '';
					$.each(elements, function() {
						var element = $(this);
						switch (element.attr('data-type')) {
							case "column":
							case "event":
								element.hover(
									function() {
										$(this).css('background-color', $(this).attr('data-bg_hover_color'));
										$(this).css('color', $(this).attr('data-hover_color'));
										var parentHeight = $(this).parent().height();
										var elementHeight = $(this).height();
										if (parentHeight > elementHeight) {
											$(this).addClass('mptt-full-height');
										}

									}, function() {
										$(this).css('background-color', $(this).attr('data-bg_color'));
										$(this).css('color', $(this).attr('data-color'));
										$(this).removeClass('mptt-full-height');
									}
								);
								break;
							case "widget":
								element.hover(
									function() {
										//height = 0;
										$(this).css('background-color', $(this).attr('data-background-hover-color'));
										$(this).css('color', $(this).attr('data-hover-color'));
										$(this).css('border-left-color', $(this).attr('data-hover-border-color'));
										//var parent = $(this).parents('div.caroufredsel_wrapper');
										//var elements = parent.find('li.mptt-colorized.visible');
										//$.each(elements, function() {
										//	height += $(this).outerHeight(true);
										//});
										//parent.css('height', height);
									},
									function() {
										//height = 0;
										$(this).css('background-color', $(this).attr('data-background-color'));
										$(this).css('color', $(this).attr('data-color'));
										$(this).css('border-left-color', $(this).attr('data-border-color'));

										//var parent = $(this).parents('div.caroufredsel_wrapper');
										//var elements = parent.find('li.mptt-colorized.visible');
										//$.each(elements, function() {
										//	height += $(this).outerHeight(true);
										//});
										//parent.css('height', height);
									}
								);
								break;
							default:
								break;
						}

					});
				},
				/**
				 * Validate ?
				 * @returns {boolean}
				 */
				validateEventData: function() {
					return true;
				},
				/**
				 * Clear input data
				 */
				clearTable: function() {
					$('#add_event_table input:not(.button),#add_event_table textarea').val('');
					$("#weekday_id").val($("#weekday_id option:first").attr('value'));
				},
				/**
				 * init Delete Button
				 */
				initDeleteButton: function() {
					$('#events-list li.event i.operation-button.dashicons-no.dashicons').off('click').on('click', function() {
						if ($('#events-list li.event').length > 1) {
							$(this).parents('li.event').remove();
						} else {
							$('#events-list').remove();
						}

					});
				},
				/**
				 * SetRowspan
				 * @param events
				 * @returns {number}
				 */
				getRowspan: function(events) {
					var arrMax = [];
					var arrMin = [];

					$.each(events, function(index) {
						var start = $(this).attr('data-start');
						var end = $(this).attr('data-end');
						arrMin[index] = start;
						arrMax[index] = end;

					});
					var min = Math.min.apply(Math, arrMin);
					var max = Math.max.apply(Math, arrMax);
					var rowSpan = (max - min);
					return rowSpan < 1 ? 1 : rowSpan;
				},
				/**
				 * Get all events shortcode
				 * @param container
				 */
				getEvents: function(container) {
					if (_.isEmpty(state.eventsData)) {

						//get columns
						$.each(container.find('.mptt-shortcode-table th'), function(index) {
							if (!_.isUndefined($(this).attr('data-column-id'))) {
								var columnID = $(this).attr('data-column-id');
								state.eventsData[columnID] = {events: []};
								$.each($('td.mptt-shortcode-event[data-column-id="' + columnID + '"] .mptt-event-container'), function() {
									var eventContainer = $(this);
									state.eventsData[columnID].events.push({
										id: eventContainer.attr('data-id'),
										eventId: eventContainer.attr('data-event-id'),
										dataStart: eventContainer.attr('data-start'),
										dataStartItem: eventContainer.attr('data-start-item'),
										dataEnd: eventContainer.attr('data-end'),
										dataColor: eventContainer.attr('data-color'),
										dataHoverColor: eventContainer.attr('data-hover_color'),
										dataBgColor: eventContainer.attr('data-bg_color'),
										dataBgHoverColor: eventContainer.attr('data-bg_hover_color'),
										style: eventContainer.attr('style'),
										eventHeader: $.trim(eventContainer.find('.event-title').text()),
										eventHeaderTag: $.trim(eventContainer.find('.event-title').prop("tagName")),
										subTitle: $.trim(eventContainer.find('.event-subtitle').text()),
										eventHeaderStyle: eventContainer.find('.event-title').attr('style'),
										eventHeaderHref: eventContainer.find('.event-title').attr('href'),
										topHour: $.trim(eventContainer.find('.timeslot span.timeslot-start').text()),
										bottomHour: $.trim(eventContainer.find('.timeslot span.timeslot-end').text()),
										afterText: $.trim(eventContainer.find('.event-user').text()),
										eventDescription: $.trim(eventContainer.find('.event-description').text()),
										timeslotDelimiter: $.trim(eventContainer.find('.timeslot span.timeslot-delimiter').text()),
										timeslotDelimiterTag: $.trim(eventContainer.find('.timeslot span.timeslot-delimiter').prop("tagName"))
									});
								});
								state.eventsData[columnID].events.sort(function(a, b) {
									if (a.dataStart < b.dataStart) {
										return -1;
									}
									else if (a.dataStart > b.dataStart) {
										return 1;
									}
									else {
										return 0;
									}
								});

							}
						});
					}
				},
				/**
				 * Responsive filter
				 * @param eventID
				 * @param parentShortcode
				 */
				responsiveFilter: function(element) {
					var parentShortcode = element.parents('.mptt-shortcode-wrapper');
					var eventID = 'all';

					if (element.is("select")) {
						eventID = element.val();
					} else {
						eventID = element.parents('li').attr('data-event-id');
					}

					if (eventID !== 'all') {
						parentShortcode.find('.mptt-list-event').hide();
						parentShortcode.find('.mptt-list-event[data-event-id="' + eventID + '"]').show();
					} else {
						parentShortcode.find('.mptt-list-event').show();
					}
					$.each(parentShortcode.find('.mptt-column'), function() {
						$(this).show();
						if ($(this).find('.mptt-list-event:visible').length < 1) {
							$(this).hide();
						}
					});
				},
				/**
				 * Filter
				 * @param element
				 */
				filterStatic: function(element) {
					var parentShortcode = element.parents('.mptt-shortcode-wrapper');
					var eventID = 'all';
					if (element.is("select")) {
						eventID = element.val();
					} else {
						eventID = element.parents('li').attr('data-event-id');
					}
					state.renderTable(parentShortcode, eventID);

					$.each(state.eventsData, function(columnID) {
						$.each(state.eventsData[columnID].events, function(index) {
							state.eventsData[columnID].events[index].output = false;
						});
					});
					state.setEventHeight();
					state.setColorSettings('.mptt-colorized');
				},
				setEventHeight: function() {
					$.each($('td.event'), function() {
						var events = $(this).find('.mptt-event-container');
						var eventCount = events.length;
						var heightItem = 0;
						var top = 0;
						if (!$('body').hasClass('mprm_ie')) {

							heightItem = 100 / ((eventCount > 0) ? eventCount : 1);

							$.each(events, function() {
								$(this).height(heightItem + "%");
								$(this).css('top', top + "%");
								$(this).removeClass('mptt-hidden');
								top += heightItem;
							});
						} else {
							var tdHeight = $(this).height();
							heightItem = tdHeight / ((eventCount > 0) ? eventCount : 1);
							$.each(events, function() {
								$(this).height(heightItem + "px");
								$(this).css('top', top + "px");
								$(this).removeClass('mptt-hidden');
								top += heightItem;
							});
						}
					});
				},
				/**
				 * Filter events by name
				 */
				filterShortcodeTable: function(element) {
					/*if (element.parents().find('table').attr('data-table-id')) {
					 location.hash = '#' + element.parents('.mptt-shortcode-wrapper').find('table').attr('data-table-id') + '/' + element.find(":selected").text().trim();
					 } else {
					 location.hash = '#' + element.find(":selected").text().trim();
					 }*/
				},
				filterShortcodeEvents: function() {
					var selector = $('.mptt-menu');

					if (selector.length) {


						/*$('.mptt-navigation-tabs.mptt-menu a').off('click').on('click', function() {
						 $(this).parents('.mptt-navigation-tabs.mptt-menu').find('li').removeClass('active');
						 $(this).parents('li').addClass('active');
						 state.responsiveFilter($(this));
						 });
						 if ( $(window).width() < 767 ) {
						 selector.off('change').on('change', function() {
						 //state.filterShortcodeTable($(this));

						 });



						 } else {*/
						$.each($('.mptt-event-container'), function(index, value) {
							$(this).parents('td').addClass('event');
						});

						state.setRowspanTd();

						selector.off('change').on('change', function() {
							//state.filterShortcodeTable($(this));
							state.filterStatic($(this));
							state.responsiveFilter($(this));
						});
						state.setEventHeight();

						$('.mptt-navigation-tabs.mptt-menu a').off('click').on('click', function() {
							$(this).parents('.mptt-navigation-tabs.mptt-menu').find('li').removeClass('active');
							$(this).parents('li').addClass('active');
							state.filterStatic($(this));
							state.responsiveFilter($(this));
						});
						//}
					}
				},
				getFilterByHash: function() {
					/*state.filterShortcodeTable($(this));
					 var hash = window.location.hash.substr(1);
					 if ($('.mptt-menu').hasClass('mptt-navigation-tabs')) {
					 $('.mptt-navigation-tabs').find('a[title="' + hash + '"]').click();
					 } else {
					 $('.mptt-navigation-select').find('option:contains(' + hash + ')').change();
					 }*/
				},
				/**
				 * Show gide empty rows
				 * @param shortcodeContainer
				 */
				toggleRows: function() {
					$.each($('.mptt-shortcode-wrapper'), function() {
						var shortcode_params = $.parseJSON($(this).find('input[name="hide_empty_rows"]').val());

						if (Boolean(shortcode_params)) {
							var arrMin = [];
							var arrMax = [];

							$.each($(this).find('.mptt-event-container'), function(index, value) {
								var start = $(this).attr('data-start');
								var end = $(this).attr('data-end');
								arrMin[index] = start;
								arrMax[index] = end;
							});

							var min = Math.min.apply(Math, arrMin);
							var max = Math.max.apply(Math, arrMax);
							$.each($(this).find('.mptt-shortcode-table tbody tr'), function(index) {
								var trIndex = $(this).attr('data-index');
								if ((parseInt(trIndex) < parseInt(min) || parseInt(trIndex) > parseInt(max))) {
									$(this).hide();
								} else {
									$(this).show();
								}
							});
						}
					});

				}, /**
				 * Re-render table
				 * @param shortcodeContainer
				 */
				renderTable: function(shortcodeContainer, eventID) {
					state.getEvents(shortcodeContainer);
					shortcodeContainer.find('.mptt-shortcode-event').remove();
					$.each(shortcodeContainer.find('.mptt-shortcode-table tbody tr'), function(index) {
						var tr = $(this);
						var trIndex = $(this).attr('data-index');
						$.each(state.eventsData, function(columnID) {
							tr.append(state.renderShortcodeEventItem(columnID, eventID, trIndex));
						});
					});
					state.toggleRows(shortcodeContainer);
					state.setRowspanTd();
				},
				/**
				 * Set rowspan td
				 */
				setRowspanTd: function() {
					$.each($('.mptt-shortcode-table td.event'), function() {
						var events = $(this).find('.mptt-event-container');
						var columnId = $(this).attr('data-column-id');
						var rowSpan = state.getRowspan(events);
						var tableContainer = $(this).parents('.mptt-shortcode-table');
						if (!_.isUndefined(rowSpan) && rowSpan > 1) {
							var index = $(this).parents('tr').attr('data-index');
							var torowSpan = rowSpan + parseInt(index) - 1;
							for (index; index < torowSpan; index++) {
								tableContainer.find('tr.mptt-shortcode-row-' + (parseInt(index) + 1) + ' td:not(.event)[data-column-id="' + columnId + '"]').remove();
							}
						}
						$(this).attr('rowspan', rowSpan);
					});
				},
				/**
				 * Widget settings
				 */
				displaySettings: function() {
					if ($('.view_settings').length) {
						$('.view_settings').change(function() {
							if ($(this).val() === "all") {
								var id = $(this).attr('id');
								$(this).parents('.mptt-container').find('.next-days').css("display", "block");
							}
							else {
								$(this).parents('.mptt-container').find(".next-days").css("display", "none");
							}
						});
					}
				},
				/**
				 * Widget time settings
				 * @param selector
				 */
				timeMode: function(selector) {
					if (selector) {
						$('#' + selector).change(function() {
							if ($(this).val() === "server") {
								var id = $(this).attr('id');
								$(this).parents('.mptt-container').find("." + $(this).attr('id')).css("display", "block");
							}
							else {
								$(this).parents('.mptt-container').find("." + $(this).attr('id')).css("display", "none");
							}
						});
					}
				},
				/**
				 * init Datepicker for column
				 */
				initDatePicker: function() {
					var datepicker = $("#datepicker");
					if (datepicker.length) {
						datepicker.datepicker({
							dateFormat: 'd/m/yy',
							setDate: Date.parse(datepicker.val())
						});
					}
				},
				/**
				 *  init Column  radio box change
				 */
				columnRadioBox: function() {
					if ($('input.option-input[name="column[column_option]"]').length) {
						$('input.option-input[name="column[column_option]"]').on('change', function() {
							switch ($(this).val()) {
								case 'simple':
									$('select.mp-weekday').prop("disabled", true);
									$('#datepicker').prop("disabled", true);
									break;
								case 'weekday':
									$('select.mp-weekday').prop("disabled", false);
									$('#datepicker').val('').prop("disabled", true);
									break;
								case 'date':
									$('select.mp-weekday').prop("disabled", true);
									$('#datepicker').prop("disabled", false);
									break;
							}
						});
					}
				}
			};
		}

		return {
			getInstance: function() {
				if (!state) {
					state = createInstance();
				}
				return state;
			}
		};
	})(jQuery));
