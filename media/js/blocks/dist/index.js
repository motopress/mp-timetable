window.wp=window.wp||{},window.wp["./media/js/blocks/dist/index.js"]=function(e){var t={};function n(l){if(t[l])return t[l].exports;var a=t[l]={i:l,l:!1,exports:{}};return e[l].call(a.exports,a,a.exports,n),a.l=!0,a.exports}return n.m=e,n.c=t,n.d=function(e,t,l){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:l})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var l=Object.create(null);if(n.r(l),Object.defineProperty(l,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)n.d(l,a,function(t){return e[t]}.bind(null,a));return l},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=6)}([function(e,t){e.exports=window.wp.i18n},function(e,t){e.exports=window.lodash},,,,,function(e,t,n){"use strict";n.r(t);var l=n(1),a=n(0);function o(e){return(o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function i(e,t){for(var n=0;n<t.length;n++){var l=t[n];l.enumerable=l.enumerable||!1,l.configurable=!0,"value"in l&&(l.writable=!0),Object.defineProperty(e,l.key,l)}}function c(e,t){return!t||"object"!==o(t)&&"function"!=typeof t?u(e):t}function u(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function b(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}function p(e){return(p=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function m(e,t){return(m=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var s=wp.element.Component,f=wp.editor.InspectorControls,_=wp.components,v=_.SelectControl,d=_.CheckboxControl,h=_.PanelBody,y=_.TextControl,O=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&m(e,t)}(O,e);var t,n,o,s,_=(t=O,function(){var e,n=p(t);if(b()){var l=p(this).constructor;e=Reflect.construct(n,arguments,l)}else e=n.apply(this,arguments);return c(this,e)});function O(){var e;return r(this,O),(e=_.apply(this,arguments)).setOptions=e.setOptions.bind(u(e)),e}return n=O,(o=[{key:"setOptions",value:function(e){var t=[];return e&&(t=e.map((function(e){return{value:e.id.toString(),label:Object(l.get)(e,["title","raw"])||Object(l.get)(e,["name"])}}))),t}},{key:"render",value:function(){var e=this.props,t=e.attributes,n=t.col,l=t.events,o=t.event_categ,r=t.increment,i=t.view,c=t.view_sort,u=t.label,b=t.hide_label,p=t.hide_hrs,m=t.hide_empty_rows,s=t.title,_=t.time,O=t.sub_title,j=t.description,g=t.user,w=t.group,C=t.disable_event_url,E=t.text_align,R=t.text_align_vertical,S=t.id,k=t.custom_class,x=t.row_height,T=t.font_size,P=t.responsive,D=t.table_layout,I=e.selectedEvents,N=e.selectedColumns,H=e.selectedEventCategories,z=e.setAttributes;return React.createElement(f,null,React.createElement(h,{title:Object(a.__)("Settings","mp-timetable")},React.createElement(v,{className:"timetable-wp56-fix",multiple:!0,size:"7",label:Object(a.__)("Columns","mp-timetable"),help:Object(a.__)("Hold the Ctrl or Command key to select/deselect multiple options.","mp-timetable"),value:n,onChange:function(e){return z({col:e})},options:this.setOptions(N)}),React.createElement(v,{className:"timetable-wp56-fix",multiple:!0,size:"7",label:Object(a.__)("Specific events","mp-timetable"),help:Object(a.__)("Hold the Ctrl or Command key to select/deselect multiple options.","mp-timetable"),value:l,onChange:function(e){return z({events:e})},options:this.setOptions(I)}),React.createElement(v,{className:"timetable-wp56-fix",multiple:!0,size:"7",label:Object(a.__)("Event categories","mp-timetable"),help:Object(a.__)("Hold the Ctrl or Command key to select/deselect multiple options.","mp-timetable"),value:o,onChange:function(e){return z({event_categ:e})},options:this.setOptions(H)}),React.createElement(d,{label:Object(a.__)("Title","mp-timetable"),checked:"1"==s,onChange:function(e){z({title:e?"1":"0"})}}),React.createElement(d,{label:Object(a.__)("Time","mp-timetable"),checked:"1"==_,onChange:function(e){z({time:e?"1":"0"})}}),React.createElement(d,{label:Object(a.__)("Subtitle","mp-timetable"),checked:"1"==O,onChange:function(e){z({sub_title:e?"1":"0"})}}),React.createElement(d,{label:Object(a.__)("Description","mp-timetable"),checked:"1"==j,onChange:function(e){z({description:e?"1":"0"})}}),React.createElement(d,{label:Object(a.__)("Event Head","mp-timetable"),checked:"1"==g,onChange:function(e){z({user:e?"1":"0"})}}),React.createElement(y,{label:Object(a.__)("Block height in pixels","mp-timetable"),type:"number",value:isNaN(x)?0:parseInt(x),onChange:function(e){z({row_height:e.toString()})},min:1,step:1}),React.createElement(y,{label:Object(a.__)("Base font size","mp-timetable"),help:Object(a.__)("Base font size for the table. Example 12px, 2em, 80%.","mp-timetable"),value:T,onChange:function(e){return z({font_size:e})}}),React.createElement(v,{label:Object(a.__)("Time frame for event","mp-timetable"),value:r,onChange:function(e){return z({increment:e})},options:[{value:"1",label:Object(a.__)("Hour (1h)","mp-timetable")},{value:"0.5",label:Object(a.__)("Half hour (30min)","mp-timetable")},{value:"0.25",label:Object(a.__)("Quarter hour (15min)","mp-timetable")}]}),React.createElement(v,{label:Object(a.__)("Filter events style","mp-timetable"),value:i,onChange:function(e){return z({view:e})},options:[{value:"dropdown_list",label:Object(a.__)("Dropdown list","mp-timetable")},{value:"tabs",label:Object(a.__)("Tabs","mp-timetable")}]}),React.createElement(v,{label:Object(a.__)("Order of items in filter","mp-timetable"),value:c,onChange:function(e){return z({view_sort:e})},options:[{value:"",label:Object(a.__)("Default","mp-timetable")},{value:"menu_order",label:Object(a.__)("Menu Order","mp-timetable")},{value:"post_title",label:Object(a.__)("Title","mp-timetable")}]}),React.createElement(y,{label:Object(a.__)("Filter title to display all events","mp-timetable"),value:u,onChange:function(e){return z({label:e})}}),React.createElement(v,{label:Object(a.__)("Hide 'All Events' option","mp-timetable"),value:b,onChange:function(e){return z({hide_label:e})},options:[{value:"0",label:Object(a.__)("No","mp-timetable")},{value:"1",label:Object(a.__)("Yes","mp-timetable")}]}),React.createElement(v,{label:Object(a.__)("Hide column with hours","mp-timetable"),value:p,onChange:function(e){return z({hide_hrs:e})},options:[{value:"0",label:Object(a.__)("No","mp-timetable")},{value:"1",label:Object(a.__)("Yes","mp-timetable")}]}),React.createElement(v,{label:Object(a.__)("Do not display empty rows","mp-timetable"),value:m,onChange:function(e){return z({hide_empty_rows:e})},options:[{value:"0",label:Object(a.__)("No","mp-timetable")},{value:"1",label:Object(a.__)("Yes","mp-timetable")}]}),React.createElement(v,{label:Object(a.__)("Merge cells with common events","mp-timetable"),value:w,onChange:function(e){return z({group:e})},options:[{value:"0",label:Object(a.__)("No","mp-timetable")},{value:"1",label:Object(a.__)("Yes","mp-timetable")}]}),React.createElement(v,{label:Object(a.__)("Disable event link","mp-timetable"),value:C,onChange:function(e){return z({disable_event_url:e})},options:[{value:"0",label:Object(a.__)("No","mp-timetable")},{value:"1",label:Object(a.__)("Yes","mp-timetable")}]}),React.createElement(v,{label:Object(a.__)("Horizontal align","mp-timetable"),value:E,onChange:function(e){return z({text_align:e})},options:[{value:"center",label:Object(a.__)("center","mp-timetable")},{value:"left",label:Object(a.__)("left","mp-timetable")},{value:"right",label:Object(a.__)("right","mp-timetable")}]}),React.createElement(v,{label:Object(a.__)("Vertical align","mp-timetable"),value:R,onChange:function(e){return z({text_align_vertical:e})},options:[{value:"default",label:Object(a.__)("Default","mp-timetable")},{value:"top",label:Object(a.__)("top","mp-timetable")},{value:"middle",label:Object(a.__)("middle","mp-timetable")},{value:"bottom",label:Object(a.__)("bottom","mp-timetable")}]}),React.createElement(v,{label:Object(a.__)("Column width","mp-timetable"),value:D,onChange:function(e){return z({table_layout:e})},options:[{value:"",label:Object(a.__)("Default","mp-timetable")},{value:"auto",label:Object(a.__)("Auto","mp-timetable")},{value:"fixed",label:Object(a.__)("Fixed","mp-timetable")}]}),React.createElement(y,{label:Object(a.__)("Unique ID","mp-timetable"),help:Object(a.__)("If you use more than one table on a page specify the unique ID for a timetable. It is usually all lowercase and contains only letters, numbers, and hyphens.","mp-timetable"),value:S,onChange:function(e){return z({id:e})}}),React.createElement(y,{label:Object(a.__)("CSS class","mp-timetable"),value:k,onChange:function(e){return z({custom_class:e})}}),React.createElement(v,{label:Object(a.__)("Mobile behavior","mp-timetable"),value:P,onChange:function(e){return z({responsive:e})},options:[{value:"0",label:Object(a.__)("Table","mp-timetable")},{value:"1",label:Object(a.__)("List","mp-timetable")}]})))}}])&&i(n.prototype,o),s&&i(n,s),O}(s);function j(e){return(j="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function g(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function w(e,t){for(var n=0;n<t.length;n++){var l=t[n];l.enumerable=l.enumerable||!1,l.configurable=!0,"value"in l&&(l.writable=!0),Object.defineProperty(e,l.key,l)}}function C(e,t){return!t||"object"!==j(t)&&"function"!=typeof t?function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e):t}function E(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}function R(e){return(R=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function S(e,t){return(S=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var k=wp.element,x=k.Component,T=k.Fragment,P=wp.compose.compose,D=wp.components,I=D.Disabled,N=D.ServerSideRender,H=wp.data.withSelect,z=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&S(e,t)}(i,e);var t,n,a,o,r=(t=i,function(){var e,n=R(t);if(E()){var l=R(this).constructor;e=Reflect.construct(n,arguments,l)}else e=n.apply(this,arguments);return C(this,e)});function i(){return g(this,i),r.apply(this,arguments)}return n=i,(a=[{key:"initTable",value:function(){var e=this.props.clientId,t=jQuery("#block-".concat(e)),n=setInterval((function(){t.find(".mptt-shortcode-wrapper").length&&!t.find(".mptt-shortcode-wrapper").hasClass("table-init")&&(clearInterval(n),window.mptt.tableInit())}),1)}},{key:"componentDidUpdate",value:function(e,t){Object(l.isEqual)(this.props.attributes,e.attributes)||this.initTable()}},{key:"componentDidMount",value:function(){this.initTable()}},{key:"render",value:function(){var e=this.props.attributes;return e.events,e.event_categ,React.createElement(T,null,React.createElement(O,this.props),React.createElement(I,null,React.createElement(N,{block:"mp-timetable/timetable",attributes:this.props.attributes})))}}])&&w(n.prototype,a),o&&w(n,o),i}(x),M=P([H((function(e,t){var n=e("core"),a=n.getEntityRecords,o=(n.getCategories,a("postType","mp-event",{per_page:-1,orderby:"title",order:"asc"})),r=a("postType","mp-column",{per_page:-1}),i=a("taxonomy","mp-event_category",{per_page:-1});return{selectedEvents:o?o.map((function(e){return Object(l.pick)(e,["id","title"])})):null,selectedColumns:r?r.map((function(e){return Object(l.pick)(e,["id","title"])})):null,selectedEventCategories:i?i.map((function(e){return Object(l.pick)(e,["id","name"])})):null}}))])(z);(0,wp.blocks.registerBlockType)("mp-timetable/timetable",{title:Object(a.__)("Timetable","mp-timetable"),category:"common",icon:"calendar",supports:{align:["wide","full"]},getEditWrapperProps:function(e){var t=e.align;if(["wide","full"].includes(t))return{"data-align":t}},edit:M,save:function(){return null}})}]);