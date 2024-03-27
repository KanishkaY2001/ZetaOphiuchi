!function(){"use strict";var e={n:function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,{a:n}),n},d:function(t,n){for(var s in n)e.o(n,s)&&!e.o(t,s)&&Object.defineProperty(t,s,{enumerable:!0,get:n[s]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)}},t=window.wp.element,n=window.wp.i18n,s=window.wp.api,a=e.n(s),i={webyx:(0,t.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 512 512"},(0,t.createElement)("path",{d:"M 86.878906 51 C 67.002123 51 51 67.002123 51 86.878906 L 51 425.12109 C 51 444.99788 67.002123 461 86.878906 461 L 425.12109 461 C 444.99788 461 461 444.99788 461 425.12109 L 461 86.878906 C 461 67.002123 444.99788 51 425.12109 51 L 86.878906 51 z M 97 97 L 415 97 L 415 415 L 97 415 L 97 97 z M 143.47656 108.27734 C 124.84714 108.27734 109.84961 123.27487 109.84961 141.9043 L 109.84961 224.65039 C 109.84961 243.27981 124.84714 258.27734 143.47656 258.27734 L 226.22266 258.27734 C 244.85208 258.27734 259.84961 243.27981 259.84961 224.65039 L 259.84961 141.9043 C 259.84961 123.27487 244.85208 108.27734 226.22266 108.27734 L 143.47656 108.27734 z M 285.47656 252.27734 C 266.84714 252.27734 251.84961 267.27487 251.84961 285.9043 L 251.84961 368.65039 C 251.84961 387.27981 266.84714 402.27734 285.47656 402.27734 L 368.22266 402.27734 C 386.85208 402.27734 401.84961 387.27981 401.84961 368.65039 L 401.84961 285.9043 C 401.84961 267.27487 386.85208 252.27734 368.22266 252.27734 L 285.47656 252.27734 z"})),section:(0,t.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 512 512"},(0,t.createElement)("path",{style:{fill:"#000000",fillOpacity:1,fillRule:"evenodd",strokeWidth:.96189594},d:"M 81.589844 51 C 64.643077 51 51 64.643077 51 81.589844 L 51 200.41016 C 51 217.35692 64.643077 231 81.589844 231 L 200.41016 231 C 217.35692 231 231 217.35692 231 200.41016 L 231 81.589844 C 231 64.643077 217.35692 51 200.41016 51 L 81.589844 51 z M 81.589844 281 C 64.643077 281 51 294.64308 51 311.58984 L 51 430.41016 C 51 447.35692 64.643077 461 81.589844 461 L 200.41016 461 C 217.35692 461 231 447.35692 231 430.41016 L 231 311.58984 C 231 294.64308 217.35692 281 200.41016 281 L 81.589844 281 z "}),(0,t.createElement)("path",{style:{opacity:.4,fill:"#000000",fillOpacity:1,fillRule:"evenodd",strokeWidth:.96189594},d:"M 311.58984 51 C 294.64308 51 281 64.643077 281 81.589844 L 281 200.41016 C 281 217.35692 294.64308 231 311.58984 231 L 430.41016 231 C 447.35692 231 461 217.35692 461 200.41016 L 461 81.589844 C 461 64.643077 447.35692 51 430.41016 51 L 311.58984 51 z M 311.58984 281 C 294.64308 281 281 294.64308 281 311.58984 L 281 430.41016 C 281 447.35692 294.64308 461 311.58984 461 L 430.41016 461 C 447.35692 461 461 447.35692 461 430.41016 L 461 311.58984 C 461 294.64308 447.35692 281 430.41016 281 L 311.58984 281 z ",id:"rect833-4-6-1"})),slide:(0,t.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 512 512"},(0,t.createElement)("rect",{style:{fill:"#000000",fillOpacity:1,fillRule:"evenodd",strokeWidth:1.73141265},id:"rect833-4-6-4",width:"324",height:"324",x:"51",y:"51",ry:"55.061699"}),(0,t.createElement)("path",{style:{opacity:.4,fill:"#000000",fillOpacity:1,fillRule:"evenodd",strokeWidth:1.73141265},d:"M 399.30078 137 L 399.30078 335.97852 C 399.30078 371.05832 371.05832 399.30078 335.97852 399.30078 L 137 399.30078 L 137 405.9375 C 137 436.44168 161.55832 461 192.0625 461 L 405.9375 461 C 436.44168 461 461 436.44168 461 405.9375 L 461 192.0625 C 461 161.55832 436.44168 137 405.9375 137 L 399.30078 137 z ",id:"rect833-4-6-1"}))},_=window.wp.components,l=window.wp.data,r=window.wp.notices;const o=()=>{const e=(0,l.useSelect)((e=>e(r.store).getNotices().filter((e=>"snackbar"===e.type))),[]),{removeNotice:n}=(0,l.useDispatch)(r.store);return(0,t.createElement)(_.SnackbarList,{className:"webyx-fep-edit-site-notices",notices:e,onRemove:n})};class p extends t.Component{constructor(){super(...arguments),this.state={webyx_fep_ak:"",webyx_fep_lk:"",webyx_fep_licence_pending:!1,message:"",webyx_fep_settings_pending:!1,webyx_fep_hide_admin_top_bar:!0,webyx_fep_menu:!0,webyx_fep_http_api_debug:!1,isAPILoaded:!1,webyx_fep_version:null,webyx_fep_version_number_current:null,webyx_fep_version_number_selected:"",webyx_fep_download_url:"",webyx_fep_version_pending:!1,webyx_fep_version_message_err:"",webyx_fep_version_message_completed:""},this._handleSaveSet=this._handleSaveSet.bind(this),this._handleAct=this._handleAct.bind(this),this._handleDeact=this._handleDeact.bind(this),this._getBtnHandleKeyAct=this._getBtnHandleKeyAct.bind(this),this._webyxFepLkVr=this._webyxFepLkVr.bind(this),this._getBtnHandleV=this._getBtnHandleV.bind(this),this._handleGetVer=this._handleGetVer.bind(this),this._handleUpdate=this._handleUpdate.bind(this),this._cancelUpdate=this._cancelUpdate.bind(this),this._getVL=this._getVL.bind(this),this._getNV=this._getNV.bind(this),this._handleOnChangeV=this._handleOnChangeV.bind(this)}_handleSaveSet(){this.setState({webyx_fep_settings_pending:!0});const{webyx_fep_hide_admin_top_bar:e,webyx_fep_menu:t,webyx_fep_http_api_debug:s}=this.state;new(a().models.Settings)({webyx_fep_hide_admin_top_bar:e?"true":"",webyx_fep_menu:t?"true":"",webyx_fep_http_api_debug:s?"true":""}).save().then((e=>{this.setState({webyx_fep_settings_pending:!1}),(0,l.dispatch)("core/notices").createNotice("success",(0,n.__)("Settings Saved!","webyx-fep"),{type:"snackbar",isDismissible:!0})}))}_handleAct(){this.setState({webyx_fep_licence_pending:!0}),wp.apiRequest({path:"webyx/v1/active",type:"POST",data:{webyx_lk:this.state.webyx_fep_lk}}).done((e=>{if("string"==typeof e){const n=JSON.parse(e);var t=n.active,s=n.err}else t=e.active,s=e.err;if(t){const{webyx_fep_lk:e}=this.state;new wp.api.models.Settings({webyx_fep_ak:"true",webyx_fep_lk:e}).save().then((t=>{this.setState({webyx_fep_ak:"true",webyx_fep_lk:e,webyx_fep_licence_pending:!1,message:""}),(0,l.dispatch)("core/notices").createNotice("success",(0,n.__)("License Activated Successfully!","webyx-fep"),{type:"snackbar",isDismissible:!0})}))}else s&&this.setState({message:e.err.message||"",webyx_fep_licence_pending:!1})})).fail(((e,t)=>{e.responseJSON&&e.responseJSON.message?console.error(e.responseJSON.message):console.error(t)}))}_handleDeact(){this.setState({webyx_fep_licence_pending:!0}),wp.apiRequest({path:"webyx/v1/deactive",type:"POST",data:{webyx_lk:this.state.webyx_fep_lk}}).done((e=>{if("string"==typeof e){const n=JSON.parse(e);var t=n.deactive,s=n.err}else t=e.deactive,s=e.err;t?new(a().models.Settings)({webyx_fep_ak:"",webyx_fep_lk:""}).save().then((e=>{this.setState({webyx_fep_ak:"",webyx_fep_lk:"",webyx_fep_licence_pending:!1,message:""}),(0,l.dispatch)("core/notices").createNotice("success",(0,n.__)("License Deactivated Successfully!","webyx-fep"),{type:"snackbar",isDismissible:!0})})):s&&this.setState({message:e.err.message||"",webyx_fep_licence_pending:!1})})).fail(((e,t)=>{e.responseJSON&&e.responseJSON.message?console.error(e.responseJSON.message):console.error(t)}))}_getBtnHandleKeyAct(){const{webyx_fep_ak:e,webyx_fep_lk:s,webyx_fep_licence_pending:a,webyx_fep_settings_pending:i,webyx_fep_support_pending:l,webyx_fep_version_pending:r,webyx_fep_licence_support_pending:o}=this.state,p=!s||!this._webyxFepLkVr(s)||a||i||l||r||o;return e?(0,t.createElement)(_.Button,{className:`webyx-fep-deactivate-license-btn${p?" webyx-fep-disabled-btn":""}${a?" webyx-fep-deactivate-is-busy":""}`,disabled:p,isBusy:a,onClick:this._handleDeact,variant:"primary",isLarge:!0},a?(0,n.__)("Deactive product...","webyx-fep"):(0,n.__)("Deactive product","webyx-fep")):(0,t.createElement)(_.Button,{className:`webyx-fep-activate-license-btn${p?" webyx-fep-disabled-btn":""}${a?" webyx-fep-activate-is-busy":""}`,disabled:p,isBusy:a,onClick:this._handleAct,variant:"primary",isLarge:!0},a?(0,n.__)("Activate product...","webyx-fep"):(0,n.__)("Activate product","webyx-fep"))}_webyxFepLkVr(e){return/^\w{8}-\w{8}-\w{8}-\w{8}$/.test(e)}_getBtnHandleV(){const{webyx_fep_ak:e,webyx_fep_lk:s,webyx_fep_licence_pending:a,webyx_fep_version_pending:i,webyx_fep_version:l,webyx_fep_settings_pending:r,webyx_fep_version_number_selected:o,webyx_fep_version_number_current:p,webyx_fep_support_pending:c}=this.state,b=!s||!this._webyxFepLkVr(s)||a||r||i||c||o===p;return l&&l.avalaible?(0,t.createElement)("div",null,(0,t.createElement)(_.Button,{className:`webyx-fep-save-settings-btn${b?" webyx-fep-disabled-btn":""}${i?" webyx-fep-is-busy":""}`,disabled:b,isBusy:i,onClick:this._handleUpdate,variant:"primary",isLarge:!0},i?(0,n.__)("Rollback version...","webyx-fep"):(0,n.__)(b?"Rollback version":`Rollback to ${o}`,"webyx-fep")),!i&&(0,t.createElement)(_.Button,{className:"webyx-fep-update-cancel-btn "+(i?" webyx-fep-deactivate-is-busy":""),isBusy:i,onClick:this._cancelUpdate,variant:"secondary",isLarge:!0},(0,n.__)("Cancel","webyx-fep"))):(0,t.createElement)("div",null,(0,t.createElement)("div",{className:"webyx-fep-version-label"},"To browse the list of available previous versions click on the button below."),(0,t.createElement)(_.Button,{className:`webyx-fep-save-settings-btn${b?" webyx-fep-disabled-btn":""}${i?" webyx-fep-is-busy":""}`,disabled:b,isBusy:i,onClick:this._handleGetVer,variant:"primary",isLarge:!0},i?(0,n.__)("Get Available Versions...","webyx-fep"):(0,n.__)("Get Available Versions","webyx-fep")))}_handleGetVer(){this.setState({webyx_fep_version_pending:!0,webyx_fep_version_message_err:"",webyx_fep_version_message_completed:""}),wp.apiRequest({path:"webyx/v1/version",type:"POST",data:{webyx_lk:this.state.webyx_fep_lk}}).done((e=>{if("string"==typeof e){const s=JSON.parse(e);var t=s.version,n=s.err}else t=e.version,n=e.err;const s=t;s&&s.avalaible?this.setState({webyx_fep_version:s,webyx_fep_version_number_current:s.current,webyx_fep_version_number_selected:s.current,webyx_fep_version_pending:!1,webyx_fep_version_message_err:""}):n&&this.setState({webyx_fep_version_message_err:e.err.message||"",webyx_fep_version_pending:!1})})).fail(((e,t)=>{e.responseJSON&&e.responseJSON.message?console.error(e.responseJSON.message):console.error(t)}))}_handleUpdate(){this.setState({webyx_fep_version_pending:!0}),wp.apiRequest({path:"webyx/v1/update",type:"POST",data:{webyx_fep_download_url:this.state.webyx_fep_download_url,webyx_fep_version:this.state.webyx_fep_version_number_current}}).done((e=>{console.log(e),e.update?this.setState({webyx_fep_version:null,webyx_fep_version_number_current:null,webyx_fep_version_number_selected:"",webyx_fep_download_url:"",webyx_fep_version_pending:!1,webyx_fep_version_message_completed:(0,n.__)("Rollback to previous version completed!","webyx-fep"),webyx_fep_version_message_err:""}):e.err&&this.setState({webyx_fep_version:null,webyx_fep_version_number_current:null,webyx_fep_version_number_selected:"",webyx_fep_download_url:"",webyx_fep_version_pending:!1,webyx_fep_version_message_completed:"",webyx_fep_version_message_err:e.err.message||""})})).fail(((e,t)=>{e.responseJSON&&e.responseJSON.message?console.error(e.responseJSON.message):console.error(t)}))}_cancelUpdate(){this.setState({webyx_fep_version:null,webyx_fep_version_number_current:null,webyx_fep_version_number_selected:"",webyx_fep_download_url:"",webyx_fep_version_pending:!1,webyx_fep_version_message_completed:"",webyx_fep_version_message_err:""})}_getVL(){const{webyx_fep_version:e}=this.state;if(e&&e.avalaible){var t=[];const a=e.avalaible,i=e.current;for(var n=0;n<a.length;n++){const e=a[n];var s={label:e.number,value:e.download_url};s.label===i&&(s.label=s.label+" (current)"),t.push(s)}}return t}_getNV(e){const t=this.state.webyx_fep_version.avalaible;for(var n=0;n<t.length;n++)if(t[n].download_url===e)return t[n].number}_handleOnChangeV(e){var t=this._getNV(e);this.setState({webyx_fep_download_url:e,webyx_fep_version_number_selected:t,webyx_fep_version_message_err:"",webyx_fep_version_message_completed:""})}render(){const{webyx_fep_ak:e,webyx_fep_lk:s,webyx_fep_licence_pending:a,webyx_fep_version:l,webyx_fep_download_url:r,webyx_fep_version_number_current:p,webyx_fep_version_pending:c,webyx_fep_version_message_err:b,webyx_fep_version_message_completed:d,webyx_fep_settings_pending:y,webyx_fep_hide_admin_top_bar:w,webyx_fep_http_api_debug:f,webyx_fep_menu:x,isAPILoaded:u,message:m}=this.state;return u?(0,t.createElement)(t.Fragment,null,(0,t.createElement)("div",{className:"webyx-fep-header"},(0,t.createElement)("div",{className:"webyx-fep-container"},(0,t.createElement)("div",{className:"webyx-fep-title"},(0,t.createElement)("span",{className:"webyx-fep-icon-webyx"},i.webyx),(0,t.createElement)("h1",null,(0,n.__)("Webyx FE Pro Settings","webyx-fep"))))),(0,t.createElement)("div",{className:"webyx-fep-main"},(0,t.createElement)(_.PanelBody,{initialOpen:!e,title:(0,n.__)("Webyx licenses","webyx-fep"),onToggle:()=>{this.setState({message:"",message_support:""})},icon:"admin-network"},m&&(0,t.createElement)("div",{className:"webyx-fep-license-msg"},m),(0,t.createElement)(_.TextControl,{className:"webyx-fep-input"+(e?" webyx-fep-input-activated":""),placeholder:(0,n.__)("enter your product license here...","webyx-fep"),disabled:e,value:s,label:(0,n.__)("Product license","webyx-fep"),help:(0,n.__)("The product license key was sent to you on the purchase confirmation email. In order to register Webyx FE Pro plugin for another domain you will have to deactived your license for the current domain.","webyx-fep"),onChange:e=>{this.setState({webyx_fep_lk:e,message:""})}}),e&&(0,t.createElement)("div",null,(0,t.createElement)("div",{className:"webyx-fep-version-label"},(0,t.createElement)("span",{className:"webyx-fep-important"},(0,n.__)("IMPORTANT: ","webyx-fep")),(0,n.__)("you can deactivate and reactivate your activation key any time you want as long as your product license is active. In installations where the product is deactivated it will no longer be usable.","webyx-fep")),(0,t.createElement)("div",{className:"webyx-fep-version-label"},(0,t.createElement)("span",{className:"webyx-fep-warning"},(0,n.__)("WARNING: ","webyx-fep")),(0,n.__)("if the product license has expired, deactivating the activation key will no longer allow its reactivation. You will need to purchase a new product license.","webyx-fep"))),this._getBtnHandleKeyAct()),e&&(0,t.createElement)("div",null,(0,t.createElement)(_.PanelBody,{initialOpen:!1,title:(0,n.__)("Hide WP admin top bar","webyx-fep"),icon:"admin-plugins"},(0,t.createElement)(_.ToggleControl,{checked:w,help:(0,n.__)("Hide WP admin top bar in Webyx pages preview","webyx-fep"),label:(0,n.__)("Hide WP admin top bar","webyx-fep"),onChange:e=>this.setState({webyx_fep_hide_admin_top_bar:e})})),(0,t.createElement)(_.PanelBody,{initialOpen:!1,title:(0,n.__)("Webyx menu","webyx-fep"),icon:"admin-plugins"},(0,t.createElement)(_.ToggleControl,{checked:x,help:(0,n.__)('Enable Webyx menu "Display location" in Appearance/Menus/Menu structure/Menu settings',"webyx-fep"),label:(0,n.__)("Enable Webyx menu","webyx-fep"),onChange:e=>this.setState({webyx_fep_menu:e})})),(0,t.createElement)(_.PanelBody,{initialOpen:!1,title:(0,n.__)("Version manager","webyx-fep"),onToggle:()=>{this.setState({webyx_fep_version_message_err:"",webyx_fep_version_message_completed:""})},icon:"admin-plugins"},b&&(0,t.createElement)("div",{className:"webyx-fep-license-msg"},b),d&&(0,t.createElement)("div",{className:"webyx-fep-license-msg-completed"},d),l&&(0,t.createElement)(_.SelectControl,{label:(0,n.__)("available versions","webyx-fep"),disabled:!1,labelPosition:"top",value:r,help:(0,n.__)("It is recommended to always use the latest version available, but you can use a previous one if it is necessary. Only use this feature carefully if you really need to go back to a previous version. Before rolling back make sure to backup your database and your WordPress installation files. To undo this operation, simply click on the cancel button.","webyx-fep"),onChange:e=>{this._handleOnChangeV(e)},options:this._getVL()}),this._getBtnHandleV())),(0,t.createElement)(_.PanelBody,{initialOpen:!1,title:(0,n.__)("Licenses API debugger","webyx-fep"),icon:"admin-plugins"},(0,t.createElement)(_.ToggleControl,{checked:f,help:(0,n.__)("If it is not possible to activate/deactivate your license or you have problems with your license, enable this option. In this way the generated error will be saved in a log file. Send this file to our support for a quick resolution!","webyx-fep"),label:(0,n.__)("Enable API debugger","webyx-fep"),onChange:e=>this.setState({webyx_fep_http_api_debug:e})})),e&&(0,t.createElement)(_.Button,{className:"webyx-fep-save-settings-btn"+(y?" webyx-fep-is-busy":""),onClick:this._handleSaveSet,isBusy:y||a||c,isLarge:!0},(0,n.__)("Save Settings","webyx-fep"))),(0,t.createElement)("div",{className:"webyx-fep-notices"},(0,t.createElement)(o,null))):(0,t.createElement)("div",{className:"webyx-fep-spinner-front-page"},(0,t.createElement)("div",{className:"webyx-fep-logo-container"},(0,t.createElement)("div",{className:"webyx-fep-circ-front-page"}),(0,t.createElement)("span",{className:"webyx-fep-logo-icon"},i.webyx)),(0,t.createElement)("div",{className:"webyx-fep-txt-loader"},(0,n.__)("LOADING","webyx-fep")))}componentDidMount(){wp.api.loadPromise.then((()=>{this.settings=new wp.api.models.Settings;const{isAPILoaded:e}=this.state;!1===e&&this.settings.fetch().then((e=>{this.setState({webyx_fep_lk:e.webyx_fep_lk,webyx_fep_ak:Boolean(e.webyx_fep_ak),webyx_fep_hide_admin_top_bar:Boolean(e.webyx_fep_hide_admin_top_bar),webyx_fep_http_api_debug:Boolean(e.webyx_fep_http_api_debug),webyx_fep_menu:Boolean(e.webyx_fep_menu),isAPILoaded:!0})}))}))}}document.addEventListener("DOMContentLoaded",(()=>{const e=document.getElementById("webyx-fep-settings");e&&(0,t.render)((0,t.createElement)(p,null),e)}))}();