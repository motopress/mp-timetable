!function(e){var t={};function n(o){if(t[o])return t[o].exports;var l=t[o]={i:o,l:!1,exports:{}};return e[o].call(l.exports,l,l.exports,n),l.l=!0,l.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var l in e)n.d(o,l,function(t){return e[t]}.bind(null,l));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=1)}([function(e,t){e.exports=lodash},function(e,t,n){"use strict";n.r(t);var o=n(0);function l(e){return(l="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function r(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}function a(e){return(a=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function i(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function u(e,t){return(u=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var c=wp.i18n.__,p=wp.element.Component,s=wp.editor.InspectorControls,m=wp.components,b=m.SelectControl,f=m.CheckboxControl,v=m.PanelBody,y=m.TextControl,d=function(e){function t(){var e,n,o;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),n=this,(e=!(o=a(t).apply(this,arguments))||"object"!==l(o)&&"function"!=typeof o?i(n):o).setOptions=e.setOptions.bind(i(e)),e}var n,m,d;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&u(e,t)}(t,p),n=t,(m=[{key:"setOptions",value:function(e){var t=[];return e&&(t=e.map(function(e){return{value:e.id.toString(),label:c(Object(o.get)(e,["title","raw"])||Object(o.get)(e,["name"]),"mp-timetable")}})),t}},{key:"render",value:function(){var e=this.props,t=e.attributes,n=t.col,o=t.events,l=t.event_categ,r=t.increment,a=t.view,i=t.label,u=t.hide_label,p=t.hide_hrs,m=t.hide_empty_rows,d=t.title,h=t.time,g=t.sub_title,_=t.description,w=t.user,E=t.disable_event_url,O=t.text_align,C=t.id,R=t.row_height,j=t.font_size,S=t.responsive,k=e.selectedEvents,P=e.selectedColumns,T=e.selectedEventCategories,x=e.setAttributes;return React.createElement(s,null,React.createElement(v,{title:c("Settings","mp-timetable")},React.createElement(b,{multiple:!0,size:"7",label:c("Columns (required)","mp-timetable"),help:c("In order to display multiple points hold ctrl/cmd button.","mp-timetable"),value:n,onChange:function(e){return x({col:e})},options:this.setOptions(P)}),React.createElement(b,{multiple:!0,size:"7",label:c("Specific events","mp-timetable"),value:o,onChange:function(e){return x({events:e})},options:this.setOptions(k)}),React.createElement(b,{multiple:!0,size:"7",label:c("Event categories","mp-timetable"),value:l,onChange:function(e){return x({event_categ:e})},options:this.setOptions(T)}),React.createElement(b,{label:c("Time frame for event","mp-timetable"),value:r,onChange:function(e){return x({increment:e})},options:[{value:"1",label:c("Hour (1h)","mp-timetable")},{value:"0.5",label:c("Half hour (30min)","mp-timetable")},{value:"0.25",label:c("Quater hour (15min)","mp-timetable")}]}),React.createElement(b,{label:c("Filter events style","mp-timetable"),value:a,onChange:function(e){return x({view:e})},options:[{value:"dropdown_list",label:c("Dropdown list","mp-timetable")},{value:"tabs",label:c("Tabs","mp-timetable")}]}),React.createElement(y,{label:c("Filter title to display all events","mp-timetable"),value:i,onChange:function(e){return x({label:e})}}),React.createElement(b,{label:c("Hide 'All Events' option","mp-timetable"),value:u,onChange:function(e){return x({hide_label:e})},options:[{value:"0",label:c("No","mp-timetable")},{value:"1",label:c("Yes","mp-timetable")}]}),React.createElement(b,{label:c("Hide column with hours","mp-timetable"),value:p,onChange:function(e){return x({hide_hrs:e})},options:[{value:"0",label:c("No","mp-timetable")},{value:"1",label:c("Yes","mp-timetable")}]}),React.createElement(b,{label:c("Do not display empty rows","mp-timetable"),value:m,onChange:function(e){return x({hide_empty_rows:e})},options:[{value:"0",label:c("No","mp-timetable")},{value:"1",label:c("Yes","mp-timetable")}]}),React.createElement(f,{label:"Title",checked:"1"==d,onChange:function(e){x({title:e?"1":"0"})}}),React.createElement(f,{label:"Time",checked:"1"==h,onChange:function(e){x({time:e?"1":"0"})}}),React.createElement(f,{label:"Subtitle",checked:"1"==g,onChange:function(e){x({sub_title:e?"1":"0"})}}),React.createElement(f,{label:"Description",checked:"1"==_,onChange:function(e){x({description:e?"1":"0"})}}),React.createElement(f,{label:"Event Head",checked:"1"==w,onChange:function(e){x({user:e?"1":"0"})}}),React.createElement(b,{label:c("Disable event link","mp-timetable"),value:E,onChange:function(e){return x({disable_event_url:e})},options:[{value:"0",label:c("No","mp-timetable")},{value:"1",label:c("Yes","mp-timetable")}]}),React.createElement(b,{label:c("Horizontal align","mp-timetable"),value:O,onChange:function(e){return x({text_align:e})},options:[{value:"center",label:c("Center","mp-timetable")},{value:"left",label:c("Left","mp-timetable")},{value:"right",label:c("Right","mp-timetable")}]}),React.createElement(y,{label:c("Block height in pixels","mp-timetable"),type:"number",value:isNaN(R)?0:parseInt(R),onChange:function(e){x({row_height:e.toString()})},min:1,step:1}),React.createElement(y,{label:c("Base font size","mp-timetable"),help:c("Base font size for the table. Example 12px, 2em, 80%.","mp-timetable"),type:"number",value:isNaN(j)?0:parseInt(j),onChange:function(e){x({font_size:e.toString()})},min:1,step:1}),React.createElement(b,{label:c("Responsive","mp-timetable"),value:S,onChange:function(e){return x({responsive:e})},options:[{value:"0",label:c("No","mp-timetable")},{value:"1",label:c("Yes","mp-timetable")}]}),React.createElement(y,{label:c("Unique ID","mp-timetable"),help:c("If you use more than one table on a page specify the unique ID for a timetable. It is usually all lowercase and contains only letters, numbers, and hyphens.","mp-timetable"),value:C,onChange:function(e){return x({id:e})}})))}}])&&r(n.prototype,m),d&&r(n,d),t}();function h(e){return(h="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function g(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}function _(e,t){return!t||"object"!==h(t)&&"function"!=typeof t?function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e):t}function w(e){return(w=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function E(e,t){return(E=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}wp.i18n.__;var O=wp.element,C=O.Component,R=O.Fragment,j=wp.compose.compose,S=wp.components,k=S.Disabled,P=S.ServerSideRender,T=wp.data.withSelect,x=function(e){function t(){return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),_(this,w(t).apply(this,arguments))}var n,l,r;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&E(e,t)}(t,C),n=t,(l=[{key:"componentDidUpdate",value:function(e,t){Object(o.isEqual)(this.props.attributes,e.attributes)||setTimeout(function(){window.mptt.tableInit()},1e3)}},{key:"componentDidMount",value:function(){setTimeout(function(){window.mptt.tableInit()},1e3)}},{key:"render",value:function(){var e=this.props.attributes;e.events,e.event_categ;return React.createElement(R,null,React.createElement(d,this.props),React.createElement(k,null,React.createElement(P,{block:"mp-timetable/timetable",attributes:this.props.attributes})))}}])&&g(n.prototype,l),r&&g(n,r),t}(),I=j([T(function(e,t){var n=e("core"),l=n.getEntityRecords,r=(n.getCategories,l("postType","mp-event",{per_page:-1,orderby:"title",order:"asc"})),a=l("postType","mp-column",{per_page:-1}),i=l("taxonomy","mp-event_category",{per_page:-1});return{selectedEvents:r?r.map(function(e){return Object(o.pick)(e,["id","title"])}):null,selectedColumns:a?a.map(function(e){return Object(o.pick)(e,["id","title"])}):null,selectedEventCategories:i?i.map(function(e){return Object(o.pick)(e,["id","name"])}):null}})])(x);(0,wp.blocks.registerBlockType)("mp-timetable/timetable",{title:(0,wp.i18n.__)("Timetable","mp-timetable"),category:"common",icon:"calendar",supports:{align:["wide","full"]},getEditWrapperProps:function(e){var t=e.align;if(["wide","full"].includes(t))return{"data-align":t}},edit:I,save:function(){return null}})}]);