/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./media/js/blocks/src/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./media/js/blocks/src/index.js":
/*!**************************************!*\
  !*** ./media/js/blocks/src/index.js ***!
  \**************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _timetable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./timetable */ \"./media/js/blocks/src/timetable/index.js\");\n\n\n//# sourceURL=webpack:///./media/js/blocks/src/index.js?");

/***/ }),

/***/ "./media/js/blocks/src/timetable/edit.js":
/*!***********************************************!*\
  !*** ./media/js/blocks/src/timetable/edit.js ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _inspector__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./inspector */ \"./media/js/blocks/src/timetable/inspector.js\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);\nfunction _typeof(obj) { if (typeof Symbol === \"function\" && typeof Symbol.iterator === \"symbol\") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === \"function\" && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj; }; } return _typeof(obj); }\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nfunction _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }\n\nfunction _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }\n\nfunction _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === \"object\" || typeof call === \"function\")) { return call; } return _assertThisInitialized(self); }\n\nfunction _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError(\"this hasn't been initialised - super() hasn't been called\"); } return self; }\n\nfunction _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }\n\nfunction _inherits(subClass, superClass) { if (typeof superClass !== \"function\" && superClass !== null) { throw new TypeError(\"Super expression must either be null or a function\"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }\n\nfunction _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }\n\n\n\nvar __ = wp.i18n.__;\nvar _wp$element = wp.element,\n    Component = _wp$element.Component,\n    Fragment = _wp$element.Fragment;\nvar compose = wp.compose.compose;\nvar _wp$components = wp.components,\n    Disabled = _wp$components.Disabled,\n    ServerSideRender = _wp$components.ServerSideRender;\nvar withSelect = wp.data.withSelect;\n\nvar Edit =\n/*#__PURE__*/\nfunction (_Component) {\n  _inherits(Edit, _Component);\n\n  function Edit() {\n    _classCallCheck(this, Edit);\n\n    return _possibleConstructorReturn(this, _getPrototypeOf(Edit).apply(this, arguments));\n  }\n\n  _createClass(Edit, [{\n    key: \"componentDidUpdate\",\n    value: function componentDidUpdate(prevProps, prevState) {\n      if (!Object(lodash__WEBPACK_IMPORTED_MODULE_1__[\"isEqual\"])(this.props.attributes, prevProps.attributes)) {\n        setTimeout(function () {\n          window.mptt.tableInit();\n        }, 1000);\n      }\n    }\n  }, {\n    key: \"componentDidMount\",\n    value: function componentDidMount() {\n      setTimeout(function () {\n        window.mptt.tableInit();\n      }, 1000);\n    }\n  }, {\n    key: \"render\",\n    value: function render() {\n      var _this$props$attribute = this.props.attributes,\n          events = _this$props$attribute.events,\n          event_categ = _this$props$attribute.event_categ;\n      return React.createElement(Fragment, null, React.createElement(_inspector__WEBPACK_IMPORTED_MODULE_0__[\"default\"], this.props), React.createElement(Disabled, null, React.createElement(ServerSideRender, {\n        block: \"mp-timetable/timetable\",\n        attributes: this.props.attributes\n      })));\n    }\n  }]);\n\n  return Edit;\n}(Component);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (compose([withSelect(function (select, props) {\n  var _select = select(\"core\"),\n      getEntityRecords = _select.getEntityRecords,\n      getCategories = _select.getCategories;\n\n  var events = getEntityRecords(\"postType\", \"mp-event\", {\n    per_page: -1,\n    orderby: 'title',\n    order: 'asc'\n  });\n  var columns = getEntityRecords(\"postType\", \"mp-column\", {\n    per_page: -1\n  });\n  var eventCategories = getEntityRecords(\"taxonomy\", \"mp-event_category\", {\n    per_page: -1\n  });\n  return {\n    selectedEvents: events ? events.map(function (event) {\n      return Object(lodash__WEBPACK_IMPORTED_MODULE_1__[\"pick\"])(event, ['id', 'title']);\n    }) : null,\n    selectedColumns: columns ? columns.map(function (column) {\n      return Object(lodash__WEBPACK_IMPORTED_MODULE_1__[\"pick\"])(column, ['id', 'title']);\n    }) : null,\n    selectedEventCategories: eventCategories ? eventCategories.map(function (categorie) {\n      return Object(lodash__WEBPACK_IMPORTED_MODULE_1__[\"pick\"])(categorie, ['id', 'name']);\n    }) : null\n  };\n})])(Edit));\n\n//# sourceURL=webpack:///./media/js/blocks/src/timetable/edit.js?");

/***/ }),

/***/ "./media/js/blocks/src/timetable/index.js":
/*!************************************************!*\
  !*** ./media/js/blocks/src/timetable/index.js ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ \"./media/js/blocks/src/timetable/edit.js\");\n\nvar registerBlockType = wp.blocks.registerBlockType;\nvar __ = wp.i18n.__;\n/* harmony default export */ __webpack_exports__[\"default\"] = (registerBlockType('mp-timetable/timetable', {\n  title: __('Timetable', 'mp-timetable'),\n  category: 'common',\n  icon: 'calendar',\n  supports: {\n    align: ['wide', 'full']\n  },\n  getEditWrapperProps: function getEditWrapperProps(attributes) {\n    var align = attributes.align;\n\n    if (['wide', 'full'].includes(align)) {\n      return {\n        'data-align': align\n      };\n    }\n  },\n  edit: _edit__WEBPACK_IMPORTED_MODULE_0__[\"default\"],\n  save: function save() {\n    return null;\n  }\n}));\n\n//# sourceURL=webpack:///./media/js/blocks/src/timetable/index.js?");

/***/ }),

/***/ "./media/js/blocks/src/timetable/inspector.js":
/*!****************************************************!*\
  !*** ./media/js/blocks/src/timetable/inspector.js ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);\nfunction _typeof(obj) { if (typeof Symbol === \"function\" && typeof Symbol.iterator === \"symbol\") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === \"function\" && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj; }; } return _typeof(obj); }\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nfunction _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }\n\nfunction _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }\n\nfunction _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === \"object\" || typeof call === \"function\")) { return call; } return _assertThisInitialized(self); }\n\nfunction _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }\n\nfunction _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError(\"this hasn't been initialised - super() hasn't been called\"); } return self; }\n\nfunction _inherits(subClass, superClass) { if (typeof superClass !== \"function\" && superClass !== null) { throw new TypeError(\"Super expression must either be null or a function\"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }\n\nfunction _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }\n\n\nvar __ = wp.i18n.__;\nvar Component = wp.element.Component;\nvar InspectorControls = wp.editor.InspectorControls;\nvar _wp$components = wp.components,\n    SelectControl = _wp$components.SelectControl,\n    CheckboxControl = _wp$components.CheckboxControl,\n    PanelBody = _wp$components.PanelBody,\n    TextControl = _wp$components.TextControl;\n\nvar Inspector =\n/*#__PURE__*/\nfunction (_Component) {\n  _inherits(Inspector, _Component);\n\n  function Inspector() {\n    var _this;\n\n    _classCallCheck(this, Inspector);\n\n    _this = _possibleConstructorReturn(this, _getPrototypeOf(Inspector).apply(this, arguments));\n    _this.setOptions = _this.setOptions.bind(_assertThisInitialized(_this));\n    return _this;\n  }\n\n  _createClass(Inspector, [{\n    key: \"setOptions\",\n    value: function setOptions(data) {\n      var options = [];\n\n      if (data) {\n        options = data.map(function (event) {\n          return {\n            value: event.id.toString(),\n            label: __(Object(lodash__WEBPACK_IMPORTED_MODULE_0__[\"get\"])(event, ['title', 'raw']) || Object(lodash__WEBPACK_IMPORTED_MODULE_0__[\"get\"])(event, ['name']), 'mp-timetable')\n          };\n        });\n      }\n\n      return options;\n    }\n  }, {\n    key: \"render\",\n    value: function render() {\n      var _this$props = this.props,\n          _this$props$attribute = _this$props.attributes,\n          col = _this$props$attribute.col,\n          events = _this$props$attribute.events,\n          event_categ = _this$props$attribute.event_categ,\n          increment = _this$props$attribute.increment,\n          view = _this$props$attribute.view,\n          label = _this$props$attribute.label,\n          hide_label = _this$props$attribute.hide_label,\n          hide_hrs = _this$props$attribute.hide_hrs,\n          hide_empty_rows = _this$props$attribute.hide_empty_rows,\n          title = _this$props$attribute.title,\n          time = _this$props$attribute.time,\n          sub_title = _this$props$attribute.sub_title,\n          description = _this$props$attribute.description,\n          user = _this$props$attribute.user,\n          group = _this$props$attribute.group,\n          disable_event_url = _this$props$attribute.disable_event_url,\n          text_align = _this$props$attribute.text_align,\n          text_align_vertical = _this$props$attribute.text_align_vertical,\n          id = _this$props$attribute.id,\n          custom_class = _this$props$attribute.custom_class,\n          row_height = _this$props$attribute.row_height,\n          font_size = _this$props$attribute.font_size,\n          responsive = _this$props$attribute.responsive,\n          selectedEvents = _this$props.selectedEvents,\n          selectedColumns = _this$props.selectedColumns,\n          selectedEventCategories = _this$props.selectedEventCategories,\n          setAttributes = _this$props.setAttributes;\n      return React.createElement(InspectorControls, null, React.createElement(PanelBody, {\n        title: __('Settings', 'mp-timetable')\n      }, React.createElement(SelectControl, {\n        multiple: true,\n        size: \"7\",\n        label: __('Columns (required)', 'mp-timetable'),\n        help: __('In order to display multiple points hold ctrl/cmd button.', 'mp-timetable'),\n        value: col,\n        onChange: function onChange(col) {\n          return setAttributes({\n            col: col\n          });\n        },\n        options: this.setOptions(selectedColumns)\n      }), React.createElement(SelectControl, {\n        multiple: true,\n        size: \"7\",\n        label: __('Specific events', 'mp-timetable'),\n        value: events,\n        onChange: function onChange(events) {\n          return setAttributes({\n            events: events\n          });\n        },\n        options: this.setOptions(selectedEvents)\n      }), React.createElement(SelectControl, {\n        multiple: true,\n        size: \"7\",\n        label: __('Event categories', 'mp-timetable'),\n        value: event_categ,\n        onChange: function onChange(event_categ) {\n          return setAttributes({\n            event_categ: event_categ\n          });\n        },\n        options: this.setOptions(selectedEventCategories)\n      }), React.createElement(CheckboxControl, {\n        label: __('Title', 'mp-timetable'),\n        checked: title == '1' ? true : false,\n        onChange: function onChange(title) {\n          setAttributes({\n            title: title ? '1' : '0'\n          });\n        }\n      }), React.createElement(CheckboxControl, {\n        label: __('Time', 'mp-timetable'),\n        checked: time == '1' ? true : false,\n        onChange: function onChange(time) {\n          setAttributes({\n            time: time ? '1' : '0'\n          });\n        }\n      }), React.createElement(CheckboxControl, {\n        label: __('Subtitle', 'mp-timetable'),\n        checked: sub_title == '1' ? true : false,\n        onChange: function onChange(sub_title) {\n          setAttributes({\n            sub_title: sub_title ? '1' : '0'\n          });\n        }\n      }), React.createElement(CheckboxControl, {\n        label: __('Description', 'mp-timetable'),\n        checked: description == '1' ? true : false,\n        onChange: function onChange(description) {\n          setAttributes({\n            description: description ? '1' : '0'\n          });\n        }\n      }), React.createElement(CheckboxControl, {\n        label: __('Event Head', 'mp-timetable'),\n        checked: user == '1' ? true : false,\n        onChange: function onChange(user) {\n          setAttributes({\n            user: user ? '1' : '0'\n          });\n        }\n      }), React.createElement(TextControl, {\n        label: __('Block height in pixels', 'mp-timetable'),\n        type: 'number',\n        value: isNaN(row_height) ? 0 : parseInt(row_height),\n        onChange: function onChange(row_height) {\n          setAttributes({\n            row_height: row_height.toString()\n          });\n        },\n        min: 1,\n        step: 1\n      }), React.createElement(TextControl, {\n        label: __('Base font size', 'mp-timetable'),\n        help: __('Base font size for the table. Example 12px, 2em, 80%.', 'mp-timetable'),\n        type: 'number',\n        value: isNaN(font_size) ? 0 : parseInt(font_size),\n        onChange: function onChange(font_size) {\n          setAttributes({\n            font_size: font_size.toString()\n          });\n        },\n        min: 1,\n        step: 1\n      }), React.createElement(SelectControl, {\n        label: __('Time frame for event', 'mp-timetable'),\n        value: increment,\n        onChange: function onChange(increment) {\n          return setAttributes({\n            increment: increment\n          });\n        },\n        options: [{\n          value: '1',\n          label: __('Hour (1h)', 'mp-timetable')\n        }, {\n          value: '0.5',\n          label: __('Half hour (30min)', 'mp-timetable')\n        }, {\n          value: '0.25',\n          label: __('Quater hour (15min)', 'mp-timetable')\n        }]\n      }), React.createElement(SelectControl, {\n        label: __('Filter events style', 'mp-timetable'),\n        value: view,\n        onChange: function onChange(view) {\n          return setAttributes({\n            view: view\n          });\n        },\n        options: [{\n          value: 'dropdown_list',\n          label: __('Dropdown list', 'mp-timetable')\n        }, {\n          value: 'tabs',\n          label: __('Tabs', 'mp-timetable')\n        }]\n      }), React.createElement(TextControl, {\n        label: __('Filter title to display all events', 'mp-timetable'),\n        value: label,\n        onChange: function onChange(label) {\n          return setAttributes({\n            label: label\n          });\n        }\n      }), React.createElement(SelectControl, {\n        label: __('Hide \\'All Events\\' option', 'mp-timetable'),\n        value: hide_label,\n        onChange: function onChange(hide_label) {\n          return setAttributes({\n            hide_label: hide_label\n          });\n        },\n        options: [{\n          value: '0',\n          label: __('No', 'mp-timetable')\n        }, {\n          value: '1',\n          label: __('Yes', 'mp-timetable')\n        }]\n      }), React.createElement(SelectControl, {\n        label: __('Hide column with hours', 'mp-timetable'),\n        value: hide_hrs,\n        onChange: function onChange(hide_hrs) {\n          return setAttributes({\n            hide_hrs: hide_hrs\n          });\n        },\n        options: [{\n          value: '0',\n          label: __('No', 'mp-timetable')\n        }, {\n          value: '1',\n          label: __('Yes', 'mp-timetable')\n        }]\n      }), React.createElement(SelectControl, {\n        label: __('Do not display empty rows', 'mp-timetable'),\n        value: hide_empty_rows,\n        onChange: function onChange(hide_empty_rows) {\n          return setAttributes({\n            hide_empty_rows: hide_empty_rows\n          });\n        },\n        options: [{\n          value: '0',\n          label: __('No', 'mp-timetable')\n        }, {\n          value: '1',\n          label: __('Yes', 'mp-timetable')\n        }]\n      }), React.createElement(SelectControl, {\n        label: __('Merge cells with common events', 'mp-timetable'),\n        value: group,\n        onChange: function onChange(group) {\n          return setAttributes({\n            group: group\n          });\n        },\n        options: [{\n          value: '0',\n          label: __('No', 'mp-timetable')\n        }, {\n          value: '1',\n          label: __('Yes', 'mp-timetable')\n        }]\n      }), React.createElement(SelectControl, {\n        label: __('Disable event link', 'mp-timetable'),\n        value: disable_event_url,\n        onChange: function onChange(disable_event_url) {\n          return setAttributes({\n            disable_event_url: disable_event_url\n          });\n        },\n        options: [{\n          value: '0',\n          label: __('No', 'mp-timetable')\n        }, {\n          value: '1',\n          label: __('Yes', 'mp-timetable')\n        }]\n      }), React.createElement(SelectControl, {\n        label: __('Horizontal align', 'mp-timetable'),\n        value: text_align,\n        onChange: function onChange(text_align) {\n          return setAttributes({\n            text_align: text_align\n          });\n        },\n        options: [{\n          value: 'center',\n          label: __('Center', 'mp-timetable')\n        }, {\n          value: 'left',\n          label: __('Left', 'mp-timetable')\n        }, {\n          value: 'right',\n          label: __('Right', 'mp-timetable')\n        }]\n      }), React.createElement(SelectControl, {\n        label: __('Vertical align', 'mp-timetable'),\n        value: text_align_vertical,\n        onChange: function onChange(text_align_vertical) {\n          return setAttributes({\n            text_align_vertical: text_align_vertical\n          });\n        },\n        options: [{\n          value: 'default',\n          label: __('Default', 'mp-timetable')\n        }, {\n          value: 'top',\n          label: __('Top', 'mp-timetable')\n        }, {\n          value: 'middle',\n          label: __('Middle', 'mp-timetable')\n        }, {\n          value: 'bottom',\n          label: __('Bottom', 'mp-timetable')\n        }]\n      }), React.createElement(TextControl, {\n        label: __('Unique ID', 'mp-timetable'),\n        help: __('If you use more than one table on a page specify the unique ID for a timetable. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'mp-timetable'),\n        value: id,\n        onChange: function onChange(id) {\n          return setAttributes({\n            id: id\n          });\n        }\n      }), React.createElement(TextControl, {\n        label: __('CSS class', 'mp-timetable'),\n        value: custom_class,\n        onChange: function onChange(custom_class) {\n          return setAttributes({\n            custom_class: custom_class\n          });\n        }\n      }), React.createElement(SelectControl, {\n        label: __('Mobile behavior', 'mp-timetable'),\n        value: responsive,\n        onChange: function onChange(responsive) {\n          return setAttributes({\n            responsive: responsive\n          });\n        },\n        options: [{\n          value: '0',\n          label: __('Table', 'mp-timetable')\n        }, {\n          value: '1',\n          label: __('List', 'mp-timetable')\n        }]\n      })));\n    }\n  }]);\n\n  return Inspector;\n}(Component);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Inspector);\n\n//# sourceURL=webpack:///./media/js/blocks/src/timetable/inspector.js?");

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = lodash;\n\n//# sourceURL=webpack:///external_%22lodash%22?");

/***/ })

/******/ });