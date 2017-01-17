/*global tinymce:false, wp:false, console: false, md5:false, jBox:false, _:false, CommonManager:false, PopupEvents:false,Registry:false*/
(function($) {
	"use strict";

	tinymce.PluginManager.add('mp_timetable', function(editor, url) {
		editor.addButton('addTimeTableButton', {
			title: 'TimeTable',
			icon: false,
			//text: 'Add Time Table',
			image: url + '/../img/shortcode-icon.png',
			onclick: function() {

				Registry._get("adminFunctions").callModal("", function(container) {
						//callback open
						var jbox = this;

						Registry._get("adminFunctions").wpAjax(
							{
								controller: "popup",
								action: "get_popup_html_content"
							},
							function(data) {
								jbox.setContent(data.html);
								//click on insert button
								Registry._get("adminFunctions").initJBox(jbox, container, function(item) {

									var shortcode_obj = {
										tag: 'mp-timetable',
										attrs: {},
										type: "single"
									};

									$.each(item, function(index, parameters) {
										switch (parameters.name) {
											case 'event':
												if (shortcode_obj.attrs.events === '' || typeof shortcode_obj.attrs.events === 'undefined') {
													shortcode_obj.attrs.events = parameters.value;
												} else {
													shortcode_obj.attrs.events += ',' + parameters.value;
												}
												break;
											case 'event_category':
												if (shortcode_obj.attrs.event_categ === '' || typeof shortcode_obj.attrs.event_categ === 'undefined') {
													shortcode_obj.attrs.event_categ = parameters.value;
												} else {
													shortcode_obj.attrs.event_categ += ',' + parameters.value;
												}
												break;
											case 'weekday':
												if (shortcode_obj.attrs.col === '' || typeof shortcode_obj.attrs.col === 'undefined') {
													shortcode_obj.attrs.col = parameters.value;
												} else {
													shortcode_obj.attrs.col += ',' + parameters.value;
												}
												break;
											case 'measure':
												shortcode_obj.attrs.increment = parameters.value;
												break;
											case 'filter_style':
												shortcode_obj.attrs.view = parameters.value;
												break;
											case 'filter_label':
												shortcode_obj.attrs.label = parameters.value;
												break;
											case 'hide_all_events_view':
												shortcode_obj.attrs.hide_label = parameters.value;
												break;
											case 'hide_hours_column':
												shortcode_obj.attrs.hide_hrs = parameters.value;
												break;
											case 'group_events':
												shortcode_obj.attrs.group = parameters.value;
												break;
											case 'hide_empty':
												shortcode_obj.attrs.hide_empty_rows = parameters.value;
												break;
											case 'title':
												shortcode_obj.attrs.title = parameters.value;
												break;
											case 'time':
												shortcode_obj.attrs.time = parameters.value;
												break;
											case 'sub-title':
												shortcode_obj.attrs['sub-title'] = parameters.value;
												break;
											case 'description':
												shortcode_obj.attrs.description = parameters.value;
												break;
											case 'user':
												shortcode_obj.attrs.user = parameters.value;
												break;
											case 'disable_event_url':
												shortcode_obj.attrs.disable_event_url = parameters.value;
												break;
											case 'text_align':
												shortcode_obj.attrs.text_align = parameters.value;
												break;
											case 'text_align_vertical':
												shortcode_obj.attrs.text_align_vertical = parameters.value;
												break;
											case 'id':
												shortcode_obj.attrs.id = parameters.value;
												break;
											case 'custom_class':
												shortcode_obj.attrs.custom_class = parameters.value;
												break;
											case 'row_height':
												shortcode_obj.attrs.row_height = parameters.value;
												break;
											case 'font_size':
												shortcode_obj.attrs.font_size = parameters.value;
												break;
											case 'responsive':
												shortcode_obj.attrs.responsive = parameters.value;
												break;
										}
									});

									var shortcode = wp.shortcode.string(shortcode_obj);

									editor.insertContent(shortcode);

									jbox.close();

								});
							},
							function(data) {
								console.warn(data);
							}
						);
					}
				);
			}
		});
	});
})(window.jQuery);