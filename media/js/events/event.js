/*global jQuery:false, console:false, _:false, CommonManager:false,Registry:false, wp:false, MPTT:false*/

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
				 * init Delete Button
				 */
				initDeleteButton: function() {
					var $events = $('#events-list');

					$events.find('li.event').find('i.operation-button.dashicons-no.dashicons').off('click').on('click', function() {
						if ($events.find('li.event').length > 1) {
							$(this).parents('li.event').remove();
						} else {
							$events.remove();
						}
					});
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
					$(document).on('click.admin', '#add_mp_event', function() {
						if ($(this).hasClass('edit')) {
							state.updateEventData();
						} else {
							state.renderEventItem();
						}
					});
				},
				/**
				 * init event data delete button
				 */
				initDeleteButtons: function() {
					$(document).on('click.admin', '#events-list .delete-event-button', function() {
						var id = $(this).attr('data-id');
						state.deleteEvent(id);
					});
				},
				/**
				 * init event data edit button
				 */
				initEditButtons: function() {
					$(document).on('click.admin', '#events-list .edit-event-button', function() {
						var id = $(this).attr('data-id'),
							$tr = $(this).parent().parent();
						$(this).parent().find('.spinner').addClass('is-active');

						Registry._get("adminFunctions").wpAjax({
								controller: "events",
								action: "get_event_data",
								id: id
							},
							function(data) {
								var $addMpEvent = $('#add_mp_event');
								var $events = $('#events-list');
								$events.find('.spinner').removeClass('is-active');
								$events.find(' tr').removeClass('active');
								$tr.addClass('active');

								$('#event_start').val(data.event_start);
								$('#event_end').val(data.event_end);
								$('#description').val(data.description);
								$('#user_id').val(data.user_id);
								$('#weekday_id').val(data.column_id);

								$addMpEvent.addClass('edit');
								$addMpEvent.val('Update');

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
				 *
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
							var $deleteEvent = $('#events-list').find('tr[data-id="' + id + '"]');
							if ($deleteEvent.length) {
								$deleteEvent.remove();
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
					var item = $('#events-list').find('tr[data-id="' + state.event_id + '"]');
					var $userId = $('#user_id');

					item.find('td.event-column').text($('#weekday_id').find('option:selected').text());
					item.find('td.event-start').text($('#event_start').val());
					item.find('td.event-end').text($('#event_end').val());

					item.find('td.event-user-id').text(( $userId.val() === '-1') ? '' : $userId.find('option:selected').text());
					item.find('td.event-description').text($('#description').val());

					state.event_id = null;
					$('#add_mp_event').removeClass('edit').val('Add New');
				},
				/**
				 * Update Event data
				 */
				updateEventData: function() {
					var $addEventTable = $('#add_event_table').find('.spinner');

					$addEventTable.addClass('is-active');

					Registry._get("adminFunctions").wpAjax({
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
						function() {
							$addEventTable.removeClass('is-active');
							state.updateEventItem();
							state.clearTable();
						},
						function(data) {
							$addEventTable.removeClass('is-active');
							console.log(data);
						}
					);
				},
				/**
				 * Render event item
				 */
				renderEventItem: function() {
					var $weekdayId = $('#weekday_id');
					var $userId = $('#user_id');
					var column_ID = $weekdayId.find('option:selected').val();
					var $eventStart = $('#event_start');
					var $eventEnd = $('#event_end');
					var $description = $('#description');

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
											'value': $eventStart.val()
										}
									},
									{
										tag: 'input',
										attrs: {
											'type': 'hidden',
											'name': 'event_data[' + column_ID + '][event_end][]',
											'value': $eventEnd.val()
										}
									},
									{
										tag: 'input',
										attrs: {
											'type': 'hidden',
											'name': 'event_data[' + column_ID + '][description][]',
											'value': $description.val()
										}
									},
									{
										tag: 'input',
										attrs: {
											'type': 'hidden',
											'name': 'event_data[' + column_ID + '][user_id][]',
											'value': $userId.val()
										}
									}
								]
							},
							{
								tag: 'td',
								attrs: {
									'class': 'event-column'
								},
								content: [$weekdayId.find('option:selected').text()]
							},
							{
								tag: 'td',
								attrs: {
									'class': 'event-start'
								},
								content: [$eventStart.val()]
							},
							{
								tag: 'td',
								attrs: {
									'class': 'event-end'
								},
								content: [$eventEnd.val()]
							},
							{
								tag: 'td',
								attrs: {
									'class': 'event-description'
								},
								content: [$description.val()]
							},
							{
								tag: 'td',
								attrs: {
									'class': 'event-user-id'
								},
								content: [( $userId.val() === '-1') ? '' : $userId.find('option:selected').text()]
							},
							{
								tag: 'td',
								attrs: {},
								content: []
							}
						]
					};

					var htmlObject = Registry._get("adminFunctions").getHtml(template);
					$('#events-list').find('tbody').append(htmlObject);
					$('.events-list-wrapper').scrollTop(1E10);
					state.clearTable();
				},
				/**
				 * Set event height
				 *
				 * @param element
				 */
				setEventHeight: function(element) {
					var parent_height = element.parent().outerHeight(),
						body = $('body');

					var elementHeight = element.height();
					var min_height = element.data('min-height');
					var inner_height = element.find('.mptt-inner-event-content').height();

					element.css('position', '').css('width', '').css('min-height', '');

					if (!body.hasClass('mprm_ie_browser')) {
						if (inner_height <= min_height) {
							element.css('min-height', min_height);
						} else {
							element.css('min-height', inner_height);
						}
					} else {
						/** IE block **/
						inner_height = element.css('height', '').find('.mptt-inner-event-content').height();
						element.height(elementHeight);

						if (inner_height <= min_height) {
							element.css('max-height', min_height);
						} else {
							element.css('height', '');
							element.css('max-height', inner_height);
						}
					}

					if (parent_height < elementHeight) {
						element.height(elementHeight);
					}
				},
				/**
				 * Recalculate Height
				 * @param tdParent
				 * @param element
				 */
				recalculate_Height: function(tdParent, element) {
					var events = $('.mptt-event-container', tdParent),
						eventCount = events.length,
						heightItem = 0,
						top = 0,
						tdHeight = tdParent.height();

					if (!$('body').hasClass('mprm_ie_browser')) {

						heightItem = 100 / ((eventCount > 0) ? eventCount : 1);

						if (!_.isUndefined(element)) {
							element.height(heightItem + '%');

						} else {

							$.each(events, function() {
								var $event = $(this);
								$event.height(heightItem + '%');

								if (_.isEmpty($event.data('min-height'))) {
									$event.data('min-height', $event.height());
								}

								$event.css('top', top + '%');
								$event.removeClass('mptt-hidden');
								top += heightItem;
							});
						}

					} else {

						heightItem = tdHeight / ((eventCount > 0) ? eventCount : 1);

						if (!_.isUndefined(element)) {
							element.height(heightItem);
						} else {
							$.each(events, function() {
								var $event = $(this);
								$event.height(heightItem);

								if (_.isEmpty($event.data('min-height'))) {
									var min_height = $event.height();
									if (min_height === 0) {
										$event.data('min-height', heightItem);
									} else {
										$event.data('min-height', min_height);
									}
								}

								$event.css('top', top + 'px');
								$event.removeClass('mptt-hidden');
								top += heightItem;
							});
						}
					}
				},
				/**
				 * Fill all possible height in ceil
				 */
				setEventsHeight: function() {
					var events = $('.mptt-shortcode-wrapper').find('table').find('td.event');
					$.each(events, function() {
						var td = $(this);
						state.recalculate_Height(td);
					});
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
					// var height = '';
					$.each(elements, function() {
						var element = $(this),
							bg = element.attr('data-bg_hover_color'),
							color = element.attr('data-hover_color'),
							tdParent = element.parent();
						// parentHeight = tdParent.height(),
						// elementHeight = '';

						switch (element.attr('data-type')) {
							case "column":
							case "event":
								element.hover(
									function() {
										if (!_.isEmpty(bg)) {
											element.css('background-color', bg);
										}
										if (!_.isEmpty(color)) {
											element.css('color', color);
										}

										state.setEventHeight(element);

									}, function() {
										element.css('max-height', '').css('min-height', '');
										state.recalculate_Height(tdParent, element);

										element.css('background-color', element.attr('data-bg_color'));
										element.css('color', element.attr('data-color'));
									}
								);
								break;
							case "widget":
								element.hover(
									function() {
										element.css('background-color', element.attr('data-background-hover-color'));
										element.css('color', $(this).attr('data-hover-color'));
										element.css('border-left-color', element.attr('data-hover-border-color'));
									},
									function() {
										element.css('background-color', element.attr('data-background-color'));
										element.css('color', element.attr('data-color'));
										element.css('border-left-color', element.attr('data-border-color'));
									}
								);
								break;
							default:
								break;
						}
					});
				},
				/**
				 * Clear input data
				 */
				clearTable: function() {
					var $weekdayId = $("#weekday_id");
					$('#add_event_table input:not(.button),#add_event_table textarea').val('');
					$weekdayId.val($weekdayId.find('option:first').attr('value'));
				},
				/**
				 * get Row span
				 *
				 * @param events
				 * @returns {number}
				 */
				getRowSpan: function(events) {
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
				 * Responsive filter
				 *
				 * @param element
				 */
				responsiveFilter: function(element) {
					var eventID = 'all';
					var parentShortcode = element.parents('.mptt-shortcode-wrapper');

					if (element.is("select")) {
						eventID = element.val();
					} else {
						eventID = element.attr('href').replace("#", "");
					}

					var $listEvent = parentShortcode.find('.mptt-list-event');

					if (eventID !== 'all') {
						$listEvent.hide();
						parentShortcode.find('.mptt-list-event[data-event-id="' + eventID + '"]').show();
					} else {
						$listEvent.show();
					}

					$.each(parentShortcode.find('.mptt-column'), function() {
						$(this).show();
						if ($(this).find('.mptt-list-event:visible').length < 1) {
							$(this).hide();
						}
					});

				},
				/**
				 * Filter static version
				 *
				 * @param element
				 */
				filterStatic: function(element, event) {

					var parentShortcode = element.parents('.mptt-shortcode-wrapper');
					var eventID = '#all';
					var id = _.isEmpty(parentShortcode.attr('id')) ? 'not-set' : parentShortcode.attr('id');

					if (element.is("select")) {
						eventID = element.val();
					} else {
						eventID = element.attr('href').replace("#", "");
					}

					if (event == false || event.originalEvent === undefined) {
						//console.log('skip');
					} else {
						window.location.hash = id + ':' + eventID;
					}

					parentShortcode.find('table').hide();

					parentShortcode.find('table[id="#' + eventID + '"]').fadeIn();

					state.setEventsHeight();
				},
				/**
				 * Add class if exists events in <TD>
				 */
				setClassTd: function() {
					$.each($('.mptt-event-container'), function() {
						$(this).parents('td').addClass('event');
					});
				},
				/**
				 * Init TimeTable
				 */
				initTableData: function() {
					state.setClassTd();
					state.setRowSpanTd();
					var table_class = '.' + MPTT.table_class;

					if ($(table_class).data('hide_empty_row')) {
						state.hideEmptyRows();
					}
				},
				/**
				 *  init Filters
				 */
				filterShortcodeEvents: function() {
					var selector = $('.mptt-menu');

					if (selector.length) {

						selector.off('change').on('change', function(event) {
							state.filterStatic($(this), event);
							state.responsiveFilter($(this));
						});

						$('.mptt-navigation-tabs.mptt-menu a').off('click').on('click', function(event) {

							var $currentTab = $(this);
							$currentTab.parents('.mptt-navigation-tabs.mptt-menu').find('li').removeClass('active');

							$currentTab.parents('li').addClass('active');
							state.filterStatic($currentTab, event);
							state.responsiveFilter($currentTab);

						});

					}
				},
				/**
				 * Show events in shortcode container by current event
				 * @param shortcode_wrapper
				 * @param event
				 */
				showCurrentEvent: function(shortcode_wrapper, event) {
					if (shortcode_wrapper.find('.mptt-menu').hasClass('mptt-navigation-tabs')) {
						shortcode_wrapper.find('.mptt-navigation-tabs').find('a[href="#' + event + '"]').click();
					} else if (shortcode_wrapper.find('.mptt-menu').hasClass('mptt-navigation-select')) {
						if (shortcode_wrapper.find('.mptt-navigation-select option[value="' + event + '"]')) {
							shortcode_wrapper.find('.mptt-navigation-select').val(event).change();
						} else {
							shortcode_wrapper.find('table[id="#all"]').fadeIn();
						}
					} else {
						shortcode_wrapper.find('table[id="#all"]').fadeIn();
					}
				},
				/**
				 * Filter by hash
				 */
				getFilterByHash: function() {
					var is_single = 1;
					var hash = window.location.hash;

					if (!_.isUndefined(hash)) {
						var HashArray = hash.split(':');
						var id = HashArray[0];
						var event = HashArray[1];
						var shortcode_wrapper = $('.mptt-shortcode-wrapper');
						event = _.isUndefined(event) ? 'all' : event;

						if (shortcode_wrapper.length === is_single) {
							state.showCurrentEvent(shortcode_wrapper, event);
						} else {

							$.each(shortcode_wrapper, function(index, object) {
								var element = $(object);
								var element_id = '#' + element.attr('id');

								if (element_id === id) {
									state.showCurrentEvent(element, event);
								} else {
									state.showCurrentEvent(element, 'all');
								}
							});

						}
					}
					state.setEventsHeight();
				},
				/**
				 * Clear table after change colSpan
				 *
				 * @param columnIndex
				 * @param toColSpan
				 * @param $table
				 * @param row
				 */
				removeCellsAfterChangeColSpan: function(columnIndex, toColSpan, $table, row) {
					for (columnIndex; columnIndex < toColSpan; columnIndex++) {
						var columnId = $table.find('th[data-index="' + columnIndex + '"]').data('column-id');
						row.find('td:not(.event)[data-column-id="' + columnId + '"]').remove();
					}
				},
				/**
				 * Set rowSpan
				 * @param td
				 * @param rowSpan
				 * @param $table
				 * @param columnId
				 * @returns rowSpan
				 */
				removeCellsAfterChangeRowSpan: function(td, rowSpan, $table, columnId) {
					var index = td.parents('tr').attr('data-index'),
						toRowSpan = rowSpan + parseInt(index) - 1,
						colSpan = td.attr('colspan'),
						columnIndex = $table.find('th[data-column-id="' + columnId + '"]').data('index'),
						toColSpan = parseInt(columnIndex) + parseInt(colSpan);

					for (index; index < toRowSpan; index++) {

						var row = $table.find('tr.mptt-shortcode-row-' + (parseInt(index) + 1));

						if (row.length) {

							if (row.find('td.event[data-column-id="' + columnId + '"]').length) {
								rowSpan -= (toRowSpan - index);

								if (rowSpan < 2) {
									rowSpan = 1;
									break;
								}
							}

							if (colSpan > 1) {
								state.removeCellsAfterChangeColSpan(columnIndex, toColSpan, $table, row);
							}

							row.find('td:not(.event)[data-column-id="' + columnId + '"]').remove();
						}
					}
					return rowSpan;
				},
				/**
				 * Set rowSpan td
				 */
				setRowSpanTd: function() {
					var table_class = '.' + MPTT.table_class;
					$.each($(table_class), function() {
						var $table = $(this);

						$.each($table.find('td.event'), function() {
							var td = $(this),
								events = td.find('.mptt-event-container'),
								columnId = td.attr('data-column-id'),
								rowHeight = td.attr('data-row_height'),
								rowSpan = state.getRowSpan(events);

							if (!_.isUndefined(rowSpan) && rowSpan > 1) {

								rowSpan = state.removeCellsAfterChangeRowSpan(td, rowSpan, $table, columnId);

								if (!isNaN(rowHeight)) {
									td.css('height', rowSpan * rowHeight);
								}
							}

							td.attr('rowspan', rowSpan);
						});
					});
				},
				/**
				 * Remove empty rows
				 */
				hideEmptyRows: function() {
					var table_class = '.' + MPTT.table_class;

					var trs = $(table_class + ' tbody tr'),
						col_count = $(table_class).first().find('th').length;

					$.each(trs, function(index, value) {
						// if all columns in the row are empty
						if ($(value).find('td.event').length === 0 && $(value).find('td').length === col_count) {
							$(value).remove();
						}
					});
				},
				/**
				 * Widget settings
				 */
				displaySettings: function() {
					var $viewSettings = $('.view_settings');
					if ($viewSettings.length) {
						$viewSettings.change(function() {
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
						var selector_id = "." + $(this).attr('id');
						$('#' + selector).change(function() {
							if ($(this).val() === "server") {
								var id = $(this).attr('id');
								$(this).parents('.mptt-container').find(selector_id).css("display", "block");
							}
							else {
								$(this).parents('.mptt-container').find(selector_id).css("display", "none");
							}
						});
					}
				},
				/**
				 * init DatePicker for column
				 */
				initDatePicker: function() {
					var $date_picker = $("#datepicker");

					if ($date_picker.length) {
						$date_picker.datepicker({
							dateFormat: 'd/m/yy',
							setDate: Date.parse($date_picker.val())
						});
					}
				},
				/**
				 *  init Column  radio box change
				 */
				columnRadioBox: function() {
					var $date_picker = $('#datepicker');
					var $column_option = $('input.option-input[name="column[column_option]"]');
					var $weekday = $('select.mp-weekday');

					if ($column_option.length) {
						$column_option.on('change', function() {
							switch ($(this).val()) {
								case 'simple':
									$weekday.prop("disabled", true);
									$date_picker.prop("disabled", true);
									break;
								case 'weekday':
									$weekday.prop("disabled", false);
									$date_picker.val('').prop("disabled", true);
									break;
								case 'date':
									$weekday.prop("disabled", true);
									$date_picker.prop("disabled", false);
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