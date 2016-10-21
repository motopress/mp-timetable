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
								Registry._get("PopupEvents").init(jbox, container, function(item) {
									var shortcode_obj = {
										tag: 'mp-timetable',
										attrs: {},
										type: "single"
									};
									$.each(item, function(index, parram) {
										switch (parram['name']) {
											case 'event':
												if (shortcode_obj['attrs']['events'] == '' || typeof shortcode_obj['attrs']['events'] == "undefined") {
													shortcode_obj['attrs']['events'] = parram['value'];
												} else {
													shortcode_obj['attrs']['events'] += ',' + parram['value'];
												}
												break;
											case 'event_category':
												if (shortcode_obj['attrs']['event_categ'] == '' || typeof shortcode_obj['attrs']['event_categ'] == "undefined") {
													shortcode_obj['attrs']['event_categ'] = parram['value'];
												} else {
													shortcode_obj['attrs']['event_categ'] += ',' + parram['value'];
												}
												break;
											case 'weekday':
												if (shortcode_obj['attrs']['col'] == '' || typeof shortcode_obj['attrs']['col'] == "undefined") {
													shortcode_obj['attrs']['col'] = parram['value'];
												} else {
													shortcode_obj['attrs']['col'] += ',' + parram['value'];
												}
												break;
											case 'measure':
												shortcode_obj['attrs']['increment'] = parram['value'];
												break;
											case 'filter_style':
												shortcode_obj['attrs']['view'] = parram['value'];
												break;
											case 'filter_label':
												shortcode_obj['attrs']['label'] = parram['value'];
												break;
											case 'hide_all_events_view':
												shortcode_obj['attrs']['hide_label'] = parram['value'];
												break;
											case 'hide_hours_column':
												shortcode_obj['attrs']['hide_hrs'] = parram['value'];
												break;
											case 'hide_empty':
												shortcode_obj['attrs']['hide_empty_rows'] = parram['value'];
												break;
											case 'title':
												shortcode_obj['attrs']['title'] = parram['value'];
												break;
											case 'time':
												shortcode_obj['attrs']['time'] = parram['value'];
												break;
											case 'sub-title':
												shortcode_obj['attrs']['sub-title'] = parram['value'];
												break;
											case 'description':
												shortcode_obj['attrs']['description'] = parram['value'];
												break;
											case 'user':
												shortcode_obj['attrs']['user'] = parram['value'];
												break;
											case 'disable_event_url':
												shortcode_obj['attrs']['disable_event_url'] = parram['value'];
												break;
											case 'text_align':
												shortcode_obj['attrs']['text_align'] = parram['value'];
												break;
											case 'id':
												shortcode_obj['attrs']['id'] = parram['value'];
												break;
											case 'row_height':
												shortcode_obj['attrs']['row_height'] = parram['value'];
												break;
											case 'font_size':
												shortcode_obj['attrs']['font_size'] = parram['value'];
												break;
											case 'responsive':
												shortcode_obj['attrs']['responsive'] = parram['value'];
												break
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
