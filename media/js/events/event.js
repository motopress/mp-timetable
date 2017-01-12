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
					$('#add_mp_event').removeClass('edit').val('Add Time Slot');
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
					state.clearTable();
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
										var bg = $(this).attr('data-bg_hover_color'),
											color = $(this).attr('data-hover_color');

										if (!_.isEmpty(bg)) {
											$(this).css('background-color', bg);
										}
										if (!_.isEmpty(color)) {
											$(this).css('color', color);
										}

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
									},
									function() {
										//height = 0;
										$(this).css('background-color', $(this).attr('data-background-color'));
										$(this).css('color', $(this).attr('data-color'));
										$(this).css('border-left-color', $(this).attr('data-border-color'));
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
					var $weekdayId = $("#weekday_id");
					$('#add_event_table input:not(.button),#add_event_table textarea').val('');
					$weekdayId.val($weekdayId.find('option:first').attr('value'));
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
				 * get Row span
				 *
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
				 * Responsive filter
				 *
				 * @param element
				 */
				responsiveFilter: function(element) {
					var eventID = '#all';
					var parentShortcode = element.parents('.mptt-shortcode-wrapper');

					if (element.is("select")) {
						eventID = element.val();
					} else {
						eventID = element.attr('href').replace("#", "");
					}

					var $listEvent = parentShortcode.find('.mptt-list-event');

					if (eventID !== '#all') {
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
				filterStatic: function(element) {
					var parentShortcode = element.parents('.mptt-shortcode-wrapper');

					var eventID = '#all';

					if (element.is("select")) {
						eventID = element.val();
					} else {
						eventID = element.attr('href').replace("#", "");
					}

					var id = _.isEmpty(parentShortcode.attr('id')) ? 'no-setup' : parentShortcode.attr('id');

					window.location.hash = id + ':' + eventID;

					parentShortcode.find('table').hide();

					parentShortcode.find('table[id="#' + eventID + '"]').fadeIn();

					state.setEventHeight();
				},
				/**
				 * Fill all possible height in ceil
				 */
				setEventHeight: function() {
					$.each($('.mptt-shortcode-wrapper table td.event'), function() {
						var events = $('.mptt-event-container', $(this));
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
				 *
				 */
				filterShortcodeEvents: function() {
					var selector = $('.mptt-menu');

					if (selector.length) {

						$.each($('.mptt-event-container'), function() {
							$(this).parents('td').addClass('event');
						});

						state.setRowspanTd();

						if ($('.' + MPTT.table_class).data('hide_empty_row')) {
							state.hideEmptyRows();
						}

						selector.off('change').on('change', function() {
							state.filterStatic($(this));
							state.responsiveFilter($(this));
						});

						$('.mptt-navigation-tabs.mptt-menu a').off('click').on('click', function() {

							var $currentTab = $(this);
							$currentTab.parents('.mptt-navigation-tabs.mptt-menu').find('li').removeClass('active');

							$currentTab.parents('li').addClass('active');
							state.filterStatic($currentTab);

							state.responsiveFilter($currentTab);

						});

					}
				},
				/**
				 * Filter by hash
				 */
				getFilterByHash: function() {

					var hash = window.location.hash;

					if (!_.isUndefined(hash)) {
						var HashArray = hash.split(':');
						var id = HashArray[0];
						var event = HashArray[1];

						$.each($('.mptt-shortcode-wrapper'), function(index, object) {
							var element = $(object);
							var element_id = '#' + element.attr('id');

							if (element_id === id) {
								if ($('.mptt-menu').hasClass('mptt-navigation-tabs')) {
									element.find('.mptt-navigation-tabs').find('a[href="#' + event + '"]').click();
								} else {
									element.find('.mptt-navigation-select').val(event).change();
								}
							} else {
								var table = element.find('table[id="#all"]');

								table.fadeIn();
							}
						});

					}
					state.setEventHeight();
				},


				/**
				 * Set row-span td
				 */
				setRowspanTd: function() {

					$.each($('.' + MPTT.table_class), function() {
						var $table = $(this);

						$.each($table.find('td.event'), function() {
							var td = $(this);

							var events = td.find('.mptt-event-container'),
								columnId = td.attr('data-column-id'),
								rowHeight = td.attr('data-row_height'),
								rowSpan = state.getRowspan(events);


							if (!_.isUndefined(rowSpan) && rowSpan > 1) {

								var index = td.parents('tr').attr('data-index'),
									toRowSpan = rowSpan + parseInt(index) - 1;

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
										row.find('td:not(.event)[data-column-id="' + columnId + '"]').remove();
									}
								}

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
					var trs = $('.' + MPTT.table_class + ' tbody tr'),
						col_count = $('.' + MPTT.table_class).first().find('th').length;

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