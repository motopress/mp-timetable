/*global jQuery:false, console:false, _:false, CommonManager:false,Registry:false*/
Registry.register("PopupEvents", (function($) {
	"use strict";
	var state,
		jBox,
		container;

	function createInstance() {
		return {
			_itemID: 0,
			getPopup: function() {
				return container;
			},
			set itemID(value) {
				this._itemID = value;
			},
			get itemID() {
				return this._itemID;
			},
			init: function(jbox, html_container, buttonCallback) {
				jBox = jbox;
				container = html_container;
				state.buttonEvents(buttonCallback);
			},
			/**
			 * Button save event
			 * @param buttonCallback
			 */
			buttonEvents: function(buttonCallback) {
				container.find("#insert-into").off("click").on("click", function() {
					buttonCallback($(this).parents('form').serializeArray());
				});
			},
			/**
			 * Event on resize popup Window
			 */
			popupResize: function() {
				$(window).off("resize").on("resize", function() {
					jBox.setWidth($(window).outerWidth() - 60);
					jBox.setHeight($(window).outerHeight() - 60);
					Registry._get("Popup").update_responsive_popup(container);
				});
			},
			/**
			 * Navigation popup menu
			 */
			mediaMenu: function() {
				container.find('.media-menu a').off('click').on("click", function() {
					if (!$(this).hasClass('unclickable') && !$(this).hasClass("active")) {
						var menuItemId = $(this).attr("id");
						Registry._get("Popup").prepareSaveGallery();
						switch (menuItemId) {
							case 'listOfGallery':
								Registry._get("Popup").showGalleries(container);
								break;
							case 'imagesOfGallery':
								Registry._get("Popup").showImages(container, state.itemID);
								break;
							case 'displayOfGallery':
								Registry._get("Popup").showDisplay(container, state.itemID);
								break;
							default:
								break;
						}
					}
				});
				$('.media-frame-title').off('click').on("click", function() {
					$('.media-menu').toggleClass('visible');
				});
			},
			/**
			 * Upload or select from media library
			 */
			popupImagesView: function() {
				$('.media-frame-router.images a').off('click').on('click', function() {
					$('.media-frame-router.images a').removeClass('active');

					if ($(this).hasClass('upload-images')) {
						$(this).addClass('active');
						Registry._get("GlobalManager").showBlocks("upload,images", container);
						Registry._get("GlobalManager").hideBlocks("no-upload", container);
						Registry._get("UploaderManager").uploader_init();
					}
					if ($(this).hasClass('media-library')) {
						$(this).addClass('active');
						Registry._get("GlobalManager").showBlocks("images-grid,images", container);
					}
				});
			},
			/**
			 * Click to gallery item
			 */
			selectGallery: function() {
				var stub_icon_url = Registry._get("CommonManager").mediaUrl + "img/stub.png";
				/**
				 * click on gallery
				 */
				container.find("#galleries li:not(.gallery-create)").off("click").on("click", function() {
					var item = $(this);
					Registry._get("CommonManager").selectItem({
						selector: $(this),
						action: "always_one",
						afterSelect: function(status) {
							if (status) {
								var id = item.attr("data-id");
								state.itemID = id;
								Registry._get("RightPopupManager").galleryInit(id);
								container.find('.media-menu-item').removeClass('unclickable');
								container.find('.media-button').removeAttr("disabled");
								container.find("#display li.selected").removeClass("selected");
								container.find("#display li[data-id=" + Registry._get("Popup").galleries[state.itemID].gallery_type.id + "]").addClass("selected");
							} else {
								state.itemID = 0;
								if ($('#listOfGallery').hasClass('active')) {
									$(".media-menu-item:not(.active)").addClass('unclickable');
								}
								container.find('.media-button').attr('disabled', true);
							}
						}
					});
				});

				/**
				 * create new gallery event
				 */
				container.find("#galleries li.gallery-create").off("click").on("click", function() {
					if (!container.find(".new-gallery").length) {
						var prototype = $(this),
							new_item = prototype.clone();
						new_item.find(".thumbnail img").attr("src", stub_icon_url);
						new_item.removeClass("gallery-create");
						new_item.addClass("new-gallery selected");
						new_item.find(".thumbnail-info p").addClass("hidden");
						var input = new_item.find(".thumbnail-info [name=gallery_name]");
						input.removeClass("hidden");
						prototype.after(new_item);
						Registry._get("GlobalManager").setCaretToPos(input, 1, 0);
						state.unFocusNameInput(input);
						state.selectGallery();
					}
				});

				/**
				 * double click on gallery
				 */
				container.find("#galleries li:not(.gallery-create)").off("dblclick").on("dblclick", function() {
					Registry._get("Popup").showImages(container, state.itemID);
				});


			},
			/**
			 * Edit actions for gallery
			 * @param id
			 */
			editGallery: function(id) {
				container.find(".galleries-block .settings input").off("change").on("change", function() {
					var input = $(this),
						name = input.attr("name"),
						spinner = $(".spinner"),
						params = {
							controller: "gallery",
							df_action: "update_gallery_name",
							id: id
						};
					params[input.attr("name")] = input.val();
					spinner.addClass('is-active');
					Registry._get("CommonManager").wpAjax(params, function(data) {
						spinner.removeClass('is-active');
						Registry._get("Popup").galleries[data.id].obj.post_title = data.title;
						Registry._get("Popup").galleries[data.id].obj.post_name = data.title;
						container.find("#galleries li[data-id=" + data.id + "] .thumbnail-info p").text(data.title);
					}, function(data) {
						console.warn("some error!: " + data);
						spinner.removeClass('is-active');
					});
				});
				container.find(".galleries-block [data-setting=images-count] .edit a").off("click").on("click", function() {
					Registry._get("Popup").showImages(container, id);
					return false;
				});
				container.find(".galleries-block [data-setting=gallery-display] .edit a").off("click").on("click", function() {
					Registry._get("Popup").showDisplay(container, id);
					return false;
				});
			},
			/**
			 * unFocus from new gallery
			 * @param input
			 */
			unFocusNameInput: function(input) {
				var new_block = input.parents(".new-gallery");
				input.off("blur").on("blur", function() {
					if (input.val() === "") {
						new_block.remove();
					} else {
						Registry._get("Popup").createGallery(new_block, input);
					}
				});
				if (input.is(":focus")) {
					input.off("keypress").on("keypress", function(e) {
						if (e.which === 13) {
							if (input.val() === "") {
								new_block.remove();
							} else {
								Registry._get("Popup").createGallery(new_block, input);
							}
						}
					});
				}
			},
			/**
			 * top select events
			 */
			selectEvents: function() {
				container.find(".galleries .media-attachment-date-filters").off("change").on("change", function() {
					var params = {
							controller: "gallery",
							df_action: "get_gallery_by_date"
						},
						spinner = $(".spinner");
					spinner.addClass("is-active");
					if ($(this).val() !== "all") {
						var date_array = $(this).val().split('/');
						params.year = date_array[0];
						params.month = date_array[1];
					}

					Registry._get("CommonManager").wpAjax(params, function(data) {
						container.find("#galleries ul").replaceWith(data);
						spinner.removeClass("is-active");
						state.selectGallery();
					}, function(data) {
						console.warn("some error!: " + data);
						spinner.removeClass('is-active');
					});
				});

				container.find(".displays .media-attachment-date-filters").off("change").on("change", function() {
					var value = $(this).val();
					container.find("#display li.attachment").addClass("hidden");
					switch (value) {
						case "popular":
							container.find("#display li.attachment[popular=1]").removeClass("hidden");
							break;
						case "all":
							container.find("#display li.attachment").removeClass("hidden");
							break;
						default:
							container.find("#display li.attachment[view-type=" + value + "]").removeClass("hidden");
							break;
					}
				});
			},
			/**
			 * Search gallery event
			 */
			gallereisSearch: function() {
				container.find(".media-toolbar-primary .galleries input.search").off("change").on("change", function() {
					var spinner = $(".spinner"),
						search_text = $(this).val();
					spinner.addClass("is-active");
					Registry._get("CommonManager").wpAjax({
						controller: "gallery",
						df_action: "get_gallery_by_search",
						search_text: search_text,
						post_type: "dml_gallery"
					}, function(data) {
						container.find("#galleries ul").replaceWith(data);
						spinner.removeClass("is-active");
						state.selectGallery();
					}, function(data) {
						console.warn("some error!: " + data);
						spinner.removeClass('is-active');
					});
				});
			},
			/**
			 * select images event for images
			 */
			selectItems: function() {
				container.find("#images li .thumbnail").off("focus").on("focus", function() {
					$(this).parents("li").trigger("focus");
				});
				container.find("#images li").off("focus").on("focus", function() {
					var focused = $(this);
					Registry._get("CommonManager").selectItem({
						selector: $(this),
						action: "multi",
						afterSelect: function(status) {
							var id = focused.attr('data-id');
							Registry._get("RightPopupManager").imgInit(id);
							container.find(".media-frame-content .media-sidebar.visible .edit-attachment-frame").removeClass("hidden");
							if (_.isArray(status)) {
								$.each(status, function(key, value) {
									Registry._get("Popup").addBottomItem({value: value});
								});
							} else {
								if (!status) {
									Registry._get("Popup").addBottomItem({value: id});
								} else {
									$('.media-selection .attachments li').removeClass('selected');
									$('.media-selection .attachments li[data-id=' + id + ']').addClass('selected');
								}
							}
						}
					});
					state.unselectItems();
					state.selectChoosen();
				});
			},
			/**
			 * Tag search
			 */
			tagSearch: function() {
				$('.search-res').addClass("hidden");
				$('.search-res ul.ac_results li:not(".hidden")').remove();
				$('#img-tag').val("");
				$('#img-tag').off("keyup").on("keyup", function() {
					clearTimeout(window.delayTimer);
					var string = $(this).val();
					window.delayTimer = setTimeout(function() {
						Registry._get('GlobalAjax').searchTag({q: string}, function($data) {
							$('.search-res ul.ac_results li:not(".hidden")').remove();
							$.each($data, function(key, term) {
								Registry._get('CommonManager').buildSearchTags(term, string);
							});
							$('.search-res').removeClass("hidden");
							Registry._get("PopupEvents").getTermData();
						});
					}, 1000);
				});
			},
			/**
			 * Change Filter by Type Images
			 */
			selectFilterTypeImages: function() {
				var selectorTypeImages = $('select[name="type_images"]');
				selectorTypeImages.off("change").on("change", function() {
					Registry._get("PopupEvents").footerTermClick();
					Registry._get('ContentDML').nonceAjax = Math.random().toString(36).slice(2);
					var params = {nonceAjax: Registry._get('ContentDML').nonceAjax};
					var type = $(this).val();
					$(".search-form .images").addClass("hidden");
					switch (type) {
						case "favorite":
							Registry._get('Popup').termsFilters({
								params: params,
								container: container,
								type: type,
								termType: 'favorite'
							});
							break;
						case "category":
							Registry._get('Popup').termsFilters({
								params: params,
								container: container,
								type: type,
								termType: 'cat'
							});
							break;
						case "sets":
							Registry._get('Popup').termsFilters({
								params: params,
								container: container,
								type: type,
								termType: 'set'
							});
							break;
						case "albums":
							Registry._get('Popup').termsFilters({
								params: params,
								container: container,
								type: type,
								termType: 'album'
							});
							break;
						case "tag":
							Registry._get('ContentDML').removeItem();
							Registry._get('Popup').termsFilters({
								params: params,
								container: container,
								type: type
							});
							state.tagSearch();
							break;
						default :
							container.find('.media-toolbar-secondary .images>*:not(.types)').addClass("hidden");
							container.find('.media-toolbar-secondary .images .date').removeClass("hidden");
							container.find(".spinner").addClass("is-active");
							Registry._get('ContentDML').nonceAjax = Math.random().toString(36).slice(2);
							Registry._get('GlobalAjax').loadContent({nonceAjax: Registry._get('ContentDML').nonceAjax}, function($data) {
								Registry._get('ContentDML').initPopup($data);
							});
							$(".search-form .images").removeClass("hidden");
							break;
					}
					$('#post-query-submit').addClass("hidden");
					state.addToGallery();
					state.initAttachmentFilters();

				});
				selectorTypeImages.val("images");
				selectorTypeImages.change();
			},
			/**
			 * Get tag data
			 */
			getTermData: function() {
				$('.search-res ul.ac_results li').off("click").on("click", function() {
					var liItem = $(this);
					var params = {};
					var type = $('select[name="type_images"]').val();
					params[type] = liItem.attr("data-id");
					params.termType = type;
					Registry._get('ContentDML').removeItem();
					$(".spinner").addClass("is-active");
					Registry._get('ContentDML').nonceAjax = Math.random().toString(36).slice(2);
					params.nonceAjax = Registry._get('ContentDML').nonceAjax;
					Registry._get('GlobalAjax').loadContent(params, function($data) {
						Registry._get('ContentDML').initPopup($data);
					});
					$('#img-tag').val(liItem.find('.tag-name').text());
					$('#img-tag').attr('data-id', liItem.attr('data-id'));
					$('.search-res').addClass("hidden");
					$('.search-res ul.ac_results li:not(".hidden")').remove();
				});
			},
			/**
			 * Footer Term click
			 */
			footerTermClick: function() {
				$('.selection-view .attachments li.footer-term').off('click').on('click', function() {
					var ID = $(this).attr("data-id");
					var type = $(this).attr("data-type");
					$.each(Registry._get("Popup").galleries[Registry._get("PopupEvents").itemID].footer, function(key, value) {
						if (value && value.type === type && parseInt(value.value) === parseInt(ID)) {
							if (_.isUndefined(value.term.term_id)) {
								value.term.term_id = ID;
							}
							Registry._get("Popup").renderRightTerms(value.type, value.term, true);
						}
					});
				});
			},
			/**
			 * Init Delete term button from gallery
			 *
			 * @param selector
			 * @param term
			 */
			initDelFromGallery: function(selector, term, type) {
				selector.off("click").on("click", function() {
					switch (type) {
					}
					Registry._get("Popup").removeItemFromImageData(term.term_id, type);
					$(".attachments-browser .media-sidebar.visible .edit-attachment-frame .attachment-info").empty();
				});
			},
			/**
			 *
			 */
			addToGallery: function() {
				$('#post-query-submit').off("click").on("click", function() {
					var type = $('select[name="type_images"]').val();
					var value = '';
					if (type === 'tag') {
						value = $('#img-tag').attr('data-id');
					} else if (type === 'favorite') {
						value = Registry._get("PopupEvents").itemID;
					}
					else {
						value = parseInt($('div.type .attachment-filters:visible').val());
					}
					var container = $('.edit-attachment-frame .media-frame-content .attachment-info');
					var item = {
						name: container.find('label[data-setting="tag-name"] .text b').text(),
						count: container.find('label[data-setting="images-count"] .text b').text(),
						description: container.find('p.important-text').text()
					};

					Registry._get("Popup").galleries[Registry._get("PopupEvents").itemID].footer.push({
						value: value,
						type: type,
						term: item,
						count: parseInt(item.count)
					});
					Registry._get("Popup").galleries[Registry._get("PopupEvents").itemID].img_count += parseInt(item.count);
					Registry._get('Popup').addBottomItem({value: value, type: type});
				});
			},
			/**
			 * Init select filters on change
			 */
			initAttachmentFilters: function() {

				$('div.type select.attachment-filters:visible').off('change').on('change', function() {
					var type = $(this).attr("data-type");
					var params = {};
					Registry._get('ContentDML').removeItem();
					if (type !== 'date') {
						params.termType = type;
						params[type] = parseInt($('div.type .attachment-filters:visible').val());
					} else {
						if ($(this).val() !== "all") {
							var date_array = $(this).val().split('/'),
								year = date_array[0],
								month = date_array[1];
							params.year = year;
							params.month = month;
						}
					}
					Registry._get('ContentDML').nonceAjax = Math.random().toString(36).slice(2);
					params.nonceAjax = Registry._get('ContentDML').nonceAjax;
					Registry._get('GlobalAjax').loadContent(params, function($data) {
						Registry._get('ContentDML').initPopup($data);
					});
					$('#post-query-submit').addClass("hidden");
				});
			},
			/**
			 * select for chosen images in bottom
			 */
			selectChoosen: function() {
				container.find(".media-selection .attachments").off("click").on("click", 'li:not(.footer-term)', function() {
					container.find('.media-frame-toolbar ul.attachments li').removeClass("selected");
					if (!$(this).hasClass('selected')) {
						$('.media-selection .attachments li').removeClass('selected');
						$(this).addClass('selected');
						$('#images .attachments li[data-id=' + $(this).attr("data-id") + ']').focus();
					}
				});
			},
			/**
			 * deselect items button event
			 */
			unselectItems: function() {
				container.find("#images .button-link").off("click").on("click", function() {
					if ($('select[name="type_images"]').val() === 'images') {
						$(this).parent().removeClass('selected');
						var unselectedItemID = $(this).parent().attr('data-id');
						container.find(".media-selection li:not(.footer-term)[data-id=" + unselectedItemID + "]").remove();
						Registry._get("Popup").removeItemFromImageData(unselectedItemID);
					}
				});
			},
			/**
			 * deselect all items event
			 */
			unselectAllItems: function() {
				container.find(".clear-selection").off("click").on("click", function() {
					$('.media-selection .attachments').html('');
					$('#images .attachments li').removeClass('selected');
					$('#choosen_counter').html(0);
					Registry._get("Popup").galleries[Registry._get("PopupEvents").itemID].termsData = [];
					Registry._get("Popup").galleries[Registry._get("PopupEvents").itemID].images = [];
					Registry._get("Popup").galleries[Registry._get("PopupEvents").itemID].footer = [];
				});
			},
			/**
			 * events for click to display type
			 */
			selectDisplay: function() {
				container.find("#display li").off("click").on("click", function() {
					Registry._get("CommonManager").selectItem({
						selector: $(this),
						action: "always_one"
					});
				});
			},
			/**
			 * events for scrolling choosen items in popup
			 */
			scrollChoosen: function() {
				var ul = $('.selection-view ul');
				container.find("#scroll-left").off("click").on("click", function() {
					if (!ul.is(':animated')) {
						if (ul.css('left') === 'auto') {
							ul.animate({left: 200}, 500);
						} else {
							if (parseInt(ul.css('left')) < 0) {
								ul.animate({left: parseInt(ul.css('left')) + 200}, 500);
							}
						}
					}
				});
				container.find("#scroll-right").off("click").on("click", function() {
					if (!ul.is(':animated')) {
						if (ul.css('left') === 'auto') {
							ul.animate({left: -200}, 500);
						} else {
							if (parseInt(ul.css('left')) > 200 - ul.width()) {
								ul.animate({left: parseInt(ul.css('left')) - 200}, 500);
							}
						}
					}
				});
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
})
(jQuery));
