/* global console:false,$:false,jQuery:false, _:false, Registry:false, wp:false */
Registry.register("adminFunctions", (function($) {
	"use strict";
	var state;

	function createInstance() {
		return {
			init: function() {

			},
			/**
			 * WP Ajax
			 *
			 * @param {object} params
			 * @param {function} callbackSuccess
			 * @param {function} callbackError
			 * @returns {undefined}
			 */
			wpAjax: function(params, callbackSuccess, callbackError) {
				params.mptt_action = params.action;
				delete params.action;
				wp.ajax.send("route_url", {
					success: function(data) {
						if (!_.isUndefined(callbackError) && _.isFunction(callbackError)) {
							callbackSuccess(data);
						}
					},
					error: function(data) {
						if (!_.isUndefined(callbackError) && _.isFunction(callbackError)) {
							callbackError(data);
						} else {
							console.log(data);
						}
					},
					data: params
				});
			},
			/**
			 * Open popup window function
			 *
			 * @param start_content
			 * @param open_callback
			 */
			callModal: function(start_content, open_callback, args) {
				start_content = (_.isEmpty(start_content)) ? spinner : start_content;
				var height = $(window).outerHeight() - 60,
					width = $(window).outerWidth() - 60,
					spinner = wp.html.string({
							tag: "span",
							attrs: {
								class: "spinner is-active"
							},
							content: ""
						}
					),
					params = {
						content: start_content,
						closeOnEsc: true,
						animation: {open: 'zoomIn', close: 'zoomOut'},
						width: width,
						height: height,
						closeButton: "box",
						onOpen: function() {
							var jbox_container = $("#" + this.id);
							open_callback.call(this, jbox_container);
						},
						onClose: function() {
							//Registry._get('ContentDML').nonceAjax = Math.random().toString(36).slice(2);
							$("#" + this.id).remove();
						}
					};
				if (!_.isUndefined(args)) {
					$.extend(params, args);
				}
				var popup = new jBox('Modal', params);
				popup.open();
			}, /**
			 * Parse Url Request
			 *
			 * @param {type} value - get params name
			 */
			parseRequest: function(value) {
				var request = location.search;
				var array,
					result = {};
				if (_.isEmpty(request) || request === "?") {
					return result;
				}
				request = request.replace("?", "");
				array = request.split("&");
				$.each(array, function() {
					var value = this;
					value = value.split("=");
					result[value[0]] = value[1];
				});
				if (_.isUndefined(value)) {
					return result;
				} else {
					return result[value];
				}

			}, /**
			 * Generate HTML
			 *
			 * @param params - json
			 * @returns {string|*|n|string}
			 */
			generateHTML: function(params) {
				var content = "",
					result;
				if (_.isObject(params)) {
					var element = document.createElement(params.tag);
					if (!_.isUndefined(params.attrs)) {
						$.each(params.attrs, function(key, value) {
							if (!_.isUndefined(value) && value !== '') {
								element.setAttribute(key, value);
							}
						});
					}
					if (_.isArray(params.content)) {

						$.each(params.content, function(key, value) {
							content += state.generateHTML(value);
						});
						$(element).html(content);
					} else if (_.isObject(params.content)) {
						content = state.generateHTML(params.content);
						$(element).html(content);
					} else {
						if (!_.isUndefined(params.content)) {
							$(element).html(params.content);
						} else {
							$(element).html("");
						}
					}
					result = $(element).get(0).outerHTML;
				} else if (_.isString(params)) {
					result = params;
				} else {
					result = false;
				}
				return result;
			},
			/**
			 * Put the data to hetml code and return here
			 *
			 * @param $template
			 * @param $data
			 * @returns {boolean}
			 */
			getHtml: function($template, $data) {
				if (_.isUndefined($template)) {
					return false;
				}
				var result = false;
				if (_.isUndefined($data)) {
					if (_.isArray($template)) {
						result = "";
						$.each($template, function(key, value) {
							result += state.generateHTML(value);
						});
					} else {
						result = state.generateHTML($template);
					}
				}
				if (_.isObject($data)) {
					var template = _.template(result);
					result = template($data);
				}
				return result;
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
/*global jQuery:false, console:false, _:false,Registry:false,typenow:false,pagenow:false*/
(function($) {
	"use strict";
	$(document).ready(function() {
		if ((typeof typenow) !== "undefined") {
			if (pagenow === typenow) {
				switch (typenow) {
					case 'mp-event':
						Registry._get("Event").init();
						break;
					case 'mp-column':
						Registry._get("Event").initDatePicker();
						Registry._get("Event").columnRadioBox();
						break;
					default:
						break;
				}
			}
		}

		Registry._get("Event").filterShortcodeEvents();
		Registry._get("Event").setColorSettings();
	});
})(jQuery);