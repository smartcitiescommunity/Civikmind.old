
(function(e,t){var n,r,i=typeof t,o=e.location,a=e.document,s=a.documentElement,l=e.jQuery,u=e.$,c={},p=[],f="1.10.2",d=p.concat,h=p.push,g=p.slice,m=p.indexOf,y=c.toString,v=c.hasOwnProperty,b=f.trim,x=function(e,t){return new x.fn.init(e,t,r)},w=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,T=/\S+/g,C=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,N=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,k=/^<(\w+)\s*\/?>(?:<\/\1>|)$/,E=/^[\],:{}\s]*$/,S=/(?:^|:|,)(?:\s*\[)+/g,A=/\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g,j=/"[^"\\\r\n]*"|true|false|null|-?(?:\d+\.|)\d+(?:[eE][+-]?\d+|)/g,D=/^-ms-/,L=/-([\da-z])/gi,H=function(e,t){return t.toUpperCase()},q=function(e){(a.addEventListener||"load"===e.type||"complete"===a.readyState)&&(_(),x.ready())},_=function(){a.addEventListener?(a.removeEventListener("DOMContentLoaded",q,!1),e.removeEventListener("load",q,!1)):(a.detachEvent("onreadystatechange",q),e.detachEvent("onload",q))};x.fn=x.prototype={jquery:f,constructor:x,init:function(e,n,r){var i,o;if(!e)return this;if("string"==typeof e){if(i="<"===e.charAt(0)&&">"===e.charAt(e.length-1)&&e.length>=3?[null,e,null]:N.exec(e),!i||!i[1]&&n)return!n||n.jquery?(n||r).find(e):this.constructor(n).find(e);if(i[1]){if(n=n instanceof x?n[0]:n,x.merge(this,x.parseHTML(i[1],n&&n.nodeType?n.ownerDocument||n:a,!0)),k.test(i[1])&&x.isPlainObject(n))for(i in n)x.isFunction(this[i])?this[i](n[i]):this.attr(i,n[i]);return this}if(o=a.getElementById(i[2]),o&&o.parentNode){if(o.id!==i[2])return r.find(e);this.length=1,this[0]=o}return this.context=a,this.selector=e,this}return e.nodeType?(this.context=this[0]=e,this.length=1,this):x.isFunction(e)?r.ready(e):(e.selector!==t&&(this.selector=e.selector,this.context=e.context),x.makeArray(e,this))},selector:"",length:0,toArray:function(){return g.call(this)},get:function(e){return null==e?this.toArray():0>e?this[this.length+e]:this[e]},pushStack:function(e){var t=x.merge(this.constructor(),e);return t.prevObject=this,t.context=this.context,t},each:function(e,t){return x.each(this,e,t)},ready:function(e){return x.ready.promise().done(e),this},slice:function(){return this.pushStack(g.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(e){var t=this.length,n=+e+(0>e?t:0);return this.pushStack(n>=0&&t>n?[this[n]]:[])},map:function(e){return this.pushStack(x.map(this,function(t,n){return e.call(t,n,t)}))},end:function(){return this.prevObject||this.constructor(null)},push:h,sort:[].sort,splice:[].splice},x.fn.init.prototype=x.fn,x.extend=x.fn.extend=function(){var e,n,r,i,o,a,s=arguments[0]||{},l=1,u=arguments.length,c=!1;for("boolean"==typeof s&&(c=s,s=arguments[1]||{},l=2),"object"==typeof s||x.isFunction(s)||(s={}),u===l&&(s=this,--l);u>l;l++)if(null!=(o=arguments[l]))for(i in o)e=s[i],r=o[i],s!==r&&(c&&r&&(x.isPlainObject(r)||(n=x.isArray(r)))?(n?(n=!1,a=e&&x.isArray(e)?e:[]):a=e&&x.isPlainObject(e)?e:{},s[i]=x.extend(c,a,r)):r!==t&&(s[i]=r));return s},x.extend({expando:"jQuery"+(f+Math.random()).replace(/\D/g,""),noConflict:function(t){return e.$===x&&(e.$=u),t&&e.jQuery===x&&(e.jQuery=l),x},isReady:!1,readyWait:1,holdReady:function(e){e?x.readyWait++:x.ready(!0)},ready:function(e){if(e===!0?!--x.readyWait:!x.isReady){if(!a.body)return setTimeout(x.ready);x.isReady=!0,e!==!0&&--x.readyWait>0||(n.resolveWith(a,[x]),x.fn.trigger&&x(a).trigger("ready").off("ready"))}},isFunction:function(e){return"function"===x.type(e)},isArray:Array.isArray||function(e){return"array"===x.type(e)},isWindow:function(e){return null!=e&&e==e.window},isNumeric:function(e){return!isNaN(parseFloat(e))&&isFinite(e)},type:function(e){return null==e?e+"":"object"==typeof e||"function"==typeof e?c[y.call(e)]||"object":typeof e},isPlainObject:function(e){var n;if(!e||"object"!==x.type(e)||e.nodeType||x.isWindow(e))return!1;try{if(e.constructor&&!v.call(e,"constructor")&&!v.call(e.constructor.prototype,"isPrototypeOf"))return!1}catch(r){return!1}if(x.support.ownLast)for(n in e)return v.call(e,n);for(n in e);return n===t||v.call(e,n)},isEmptyObject:function(e){var t;for(t in e)return!1;return!0},error:function(e){throw Error(e)},parseHTML:function(e,t,n){if(!e||"string"!=typeof e)return null;"boolean"==typeof t&&(n=t,t=!1),t=t||a;var r=k.exec(e),i=!n&&[];return r?[t.createElement(r[1])]:(r=x.buildFragment([e],t,i),i&&x(i).remove(),x.merge([],r.childNodes))},parseJSON:function(n){return e.JSON&&e.JSON.parse?e.JSON.parse(n):null===n?n:"string"==typeof n&&(n=x.trim(n),n&&E.test(n.replace(A,"@").replace(j,"]").replace(S,"")))?Function("return "+n)():(x.error("Invalid JSON: "+n),t)},parseXML:function(n){var r,i;if(!n||"string"!=typeof n)return null;try{e.DOMParser?(i=new DOMParser,r=i.parseFromString(n,"text/xml")):(r=new ActiveXObject("Microsoft.XMLDOM"),r.async="false",r.loadXML(n))}catch(o){r=t}return r&&r.documentElement&&!r.getElementsByTagName("parsererror").length||x.error("Invalid XML: "+n),r},noop:function(){},globalEval:function(t){t&&x.trim(t)&&(e.execScript||function(t){e.eval.call(e,t)})(t)},camelCase:function(e){return e.replace(D,"ms-").replace(L,H)},nodeName:function(e,t){return e.nodeName&&e.nodeName.toLowerCase()===t.toLowerCase()},each:function(e,t,n){var r,i=0,o=e.length,a=M(e);if(n){if(a){for(;o>i;i++)if(r=t.apply(e[i],n),r===!1)break}else for(i in e)if(r=t.apply(e[i],n),r===!1)break}else if(a){for(;o>i;i++)if(r=t.call(e[i],i,e[i]),r===!1)break}else for(i in e)if(r=t.call(e[i],i,e[i]),r===!1)break;return e},trim:b&&!b.call("\ufeff\u00a0")?function(e){return null==e?"":b.call(e)}:function(e){return null==e?"":(e+"").replace(C,"")},makeArray:function(e,t){var n=t||[];return null!=e&&(M(Object(e))?x.merge(n,"string"==typeof e?[e]:e):h.call(n,e)),n},inArray:function(e,t,n){var r;if(t){if(m)return m.call(t,e,n);for(r=t.length,n=n?0>n?Math.max(0,r+n):n:0;r>n;n++)if(n in t&&t[n]===e)return n}return-1},merge:function(e,n){var r=n.length,i=e.length,o=0;if("number"==typeof r)for(;r>o;o++)e[i++]=n[o];else while(n[o]!==t)e[i++]=n[o++];return e.length=i,e},grep:function(e,t,n){var r,i=[],o=0,a=e.length;for(n=!!n;a>o;o++)r=!!t(e[o],o),n!==r&&i.push(e[o]);return i},map:function(e,t,n){var r,i=0,o=e.length,a=M(e),s=[];if(a)for(;o>i;i++)r=t(e[i],i,n),null!=r&&(s[s.length]=r);else for(i in e)r=t(e[i],i,n),null!=r&&(s[s.length]=r);return d.apply([],s)},guid:1,proxy:function(e,n){var r,i,o;return"string"==typeof n&&(o=e[n],n=e,e=o),x.isFunction(e)?(r=g.call(arguments,2),i=function(){return e.apply(n||this,r.concat(g.call(arguments)))},i.guid=e.guid=e.guid||x.guid++,i):t},access:function(e,n,r,i,o,a,s){var l=0,u=e.length,c=null==r;if("object"===x.type(r)){o=!0;for(l in r)x.access(e,n,l,r[l],!0,a,s)}else if(i!==t&&(o=!0,x.isFunction(i)||(s=!0),c&&(s?(n.call(e,i),n=null):(c=n,n=function(e,t,n){return c.call(x(e),n)})),n))for(;u>l;l++)n(e[l],r,s?i:i.call(e[l],l,n(e[l],r)));return o?e:c?n.call(e):u?n(e[0],r):a},now:function(){return(new Date).getTime()},swap:function(e,t,n,r){var i,o,a={};for(o in t)a[o]=e.style[o],e.style[o]=t[o];i=n.apply(e,r||[]);for(o in t)e.style[o]=a[o];return i}}),x.ready.promise=function(t){if(!n)if(n=x.Deferred(),"complete"===a.readyState)setTimeout(x.ready);else if(a.addEventListener)a.addEventListener("DOMContentLoaded",q,!1),e.addEventListener("load",q,!1);else{a.attachEvent("onreadystatechange",q),e.attachEvent("onload",q);var r=!1;try{r=null==e.frameElement&&a.documentElement}catch(i){}r&&r.doScroll&&function o(){if(!x.isReady){try{r.doScroll("left")}catch(e){return setTimeout(o,50)}_(),x.ready()}}()}return n.promise(t)},x.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),function(e,t){c["[object "+t+"]"]=t.toLowerCase()});function M(e){var t=e.length,n=x.type(e);return x.isWindow(e)?!1:1===e.nodeType&&t?!0:"array"===n||"function"!==n&&(0===t||"number"==typeof t&&t>0&&t-1 in e)}r=x(a),function(e,t){var n,r,i,o,a,s,l,u,c,p,f,d,h,g,m,y,v,b="sizzle"+-new Date,w=e.document,T=0,C=0,N=st(),k=st(),E=st(),S=!1,A=function(e,t){return e===t?(S=!0,0):0},j=typeof t,D=1<<31,L={}.hasOwnProperty,H=[],q=H.pop,_=H.push,M=H.push,O=H.slice,F=H.indexOf||function(e){var t=0,n=this.length;for(;n>t;t++)if(this[t]===e)return t;return-1},B="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",P="[\\x20\\t\\r\\n\\f]",R="(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",W=R.replace("w","w#"),$="\\["+P+"*("+R+")"+P+"*(?:([*^$|!~]?=)"+P+"*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|("+W+")|)|)"+P+"*\\]",I=":("+R+")(?:\\(((['\"])((?:\\\\.|[^\\\\])*?)\\3|((?:\\\\.|[^\\\\()[\\]]|"+$.replace(3,8)+")*)|.*)\\)|)",z=RegExp("^"+P+"+|((?:^|[^\\\\])(?:\\\\.)*)"+P+"+$","g"),X=RegExp("^"+P+"*,"+P+"*"),U=RegExp("^"+P+"*([>+~]|"+P+")"+P+"*"),V=RegExp(P+"*[+~]"),Y=RegExp("="+P+"*([^\\]'\"]*)"+P+"*\\]","g"),J=RegExp(I),G=RegExp("^"+W+"$"),Q={ID:RegExp("^#("+R+")"),CLASS:RegExp("^\\.("+R+")"),TAG:RegExp("^("+R.replace("w","w*")+")"),ATTR:RegExp("^"+$),PSEUDO:RegExp("^"+I),CHILD:RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+P+"*(even|odd|(([+-]|)(\\d*)n|)"+P+"*(?:([+-]|)"+P+"*(\\d+)|))"+P+"*\\)|)","i"),bool:RegExp("^(?:"+B+")$","i"),needsContext:RegExp("^"+P+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+P+"*((?:-\\d)?\\d*)"+P+"*\\)|)(?=[^-]|$)","i")},K=/^[^{]+\{\s*\[native \w/,Z=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,et=/^(?:input|select|textarea|button)$/i,tt=/^h\d$/i,nt=/'|\\/g,rt=RegExp("\\\\([\\da-f]{1,6}"+P+"?|("+P+")|.)","ig"),it=function(e,t,n){var r="0x"+t-65536;return r!==r||n?t:0>r?String.fromCharCode(r+65536):String.fromCharCode(55296|r>>10,56320|1023&r)};try{M.apply(H=O.call(w.childNodes),w.childNodes),H[w.childNodes.length].nodeType}catch(ot){M={apply:H.length?function(e,t){_.apply(e,O.call(t))}:function(e,t){var n=e.length,r=0;while(e[n++]=t[r++]);e.length=n-1}}}function at(e,t,n,i){var o,a,s,l,u,c,d,m,y,x;if((t?t.ownerDocument||t:w)!==f&&p(t),t=t||f,n=n||[],!e||"string"!=typeof e)return n;if(1!==(l=t.nodeType)&&9!==l)return[];if(h&&!i){if(o=Z.exec(e))if(s=o[1]){if(9===l){if(a=t.getElementById(s),!a||!a.parentNode)return n;if(a.id===s)return n.push(a),n}else if(t.ownerDocument&&(a=t.ownerDocument.getElementById(s))&&v(t,a)&&a.id===s)return n.push(a),n}else{if(o[2])return M.apply(n,t.getElementsByTagName(e)),n;if((s=o[3])&&r.getElementsByClassName&&t.getElementsByClassName)return M.apply(n,t.getElementsByClassName(s)),n}if(r.qsa&&(!g||!g.test(e))){if(m=d=b,y=t,x=9===l&&e,1===l&&"object"!==t.nodeName.toLowerCase()){c=mt(e),(d=t.getAttribute("id"))?m=d.replace(nt,"\\$&"):t.setAttribute("id",m),m="[id='"+m+"'] ",u=c.length;while(u--)c[u]=m+yt(c[u]);y=V.test(e)&&t.parentNode||t,x=c.join(",")}if(x)try{return M.apply(n,y.querySelectorAll(x)),n}catch(T){}finally{d||t.removeAttribute("id")}}}return kt(e.replace(z,"$1"),t,n,i)}function st(){var e=[];function t(n,r){return e.push(n+=" ")>o.cacheLength&&delete t[e.shift()],t[n]=r}return t}function lt(e){return e[b]=!0,e}function ut(e){var t=f.createElement("div");try{return!!e(t)}catch(n){return!1}finally{t.parentNode&&t.parentNode.removeChild(t),t=null}}function ct(e,t){var n=e.split("|"),r=e.length;while(r--)o.attrHandle[n[r]]=t}function pt(e,t){var n=t&&e,r=n&&1===e.nodeType&&1===t.nodeType&&(~t.sourceIndex||D)-(~e.sourceIndex||D);if(r)return r;if(n)while(n=n.nextSibling)if(n===t)return-1;return e?1:-1}function ft(e){return function(t){var n=t.nodeName.toLowerCase();return"input"===n&&t.type===e}}function dt(e){return function(t){var n=t.nodeName.toLowerCase();return("input"===n||"button"===n)&&t.type===e}}function ht(e){return lt(function(t){return t=+t,lt(function(n,r){var i,o=e([],n.length,t),a=o.length;while(a--)n[i=o[a]]&&(n[i]=!(r[i]=n[i]))})})}s=at.isXML=function(e){var t=e&&(e.ownerDocument||e).documentElement;return t?"HTML"!==t.nodeName:!1},r=at.support={},p=at.setDocument=function(e){var n=e?e.ownerDocument||e:w,i=n.defaultView;return n!==f&&9===n.nodeType&&n.documentElement?(f=n,d=n.documentElement,h=!s(n),i&&i.attachEvent&&i!==i.top&&i.attachEvent("onbeforeunload",function(){p()}),r.attributes=ut(function(e){return e.className="i",!e.getAttribute("className")}),r.getElementsByTagName=ut(function(e){return e.appendChild(n.createComment("")),!e.getElementsByTagName("*").length}),r.getElementsByClassName=ut(function(e){return e.innerHTML="<div class='a'></div><div class='a i'></div>",e.firstChild.className="i",2===e.getElementsByClassName("i").length}),r.getById=ut(function(e){return d.appendChild(e).id=b,!n.getElementsByName||!n.getElementsByName(b).length}),r.getById?(o.find.ID=function(e,t){if(typeof t.getElementById!==j&&h){var n=t.getElementById(e);return n&&n.parentNode?[n]:[]}},o.filter.ID=function(e){var t=e.replace(rt,it);return function(e){return e.getAttribute("id")===t}}):(delete o.find.ID,o.filter.ID=function(e){var t=e.replace(rt,it);return function(e){var n=typeof e.getAttributeNode!==j&&e.getAttributeNode("id");return n&&n.value===t}}),o.find.TAG=r.getElementsByTagName?function(e,n){return typeof n.getElementsByTagName!==j?n.getElementsByTagName(e):t}:function(e,t){var n,r=[],i=0,o=t.getElementsByTagName(e);if("*"===e){while(n=o[i++])1===n.nodeType&&r.push(n);return r}return o},o.find.CLASS=r.getElementsByClassName&&function(e,n){return typeof n.getElementsByClassName!==j&&h?n.getElementsByClassName(e):t},m=[],g=[],(r.qsa=K.test(n.querySelectorAll))&&(ut(function(e){e.innerHTML="<select><option selected=''></option></select>",e.querySelectorAll("[selected]").length||g.push("\\["+P+"*(?:value|"+B+")"),e.querySelectorAll(":checked").length||g.push(":checked")}),ut(function(e){var t=n.createElement("input");t.setAttribute("type","hidden"),e.appendChild(t).setAttribute("t",""),e.querySelectorAll("[t^='']").length&&g.push("[*^$]="+P+"*(?:''|\"\")"),e.querySelectorAll(":enabled").length||g.push(":enabled",":disabled"),e.querySelectorAll("*,:x"),g.push(",.*:")})),(r.matchesSelector=K.test(y=d.webkitMatchesSelector||d.mozMatchesSelector||d.oMatchesSelector||d.msMatchesSelector))&&ut(function(e){r.disconnectedMatch=y.call(e,"div"),y.call(e,"[s!='']:x"),m.push("!=",I)}),g=g.length&&RegExp(g.join("|")),m=m.length&&RegExp(m.join("|")),v=K.test(d.contains)||d.compareDocumentPosition?function(e,t){var n=9===e.nodeType?e.documentElement:e,r=t&&t.parentNode;return e===r||!(!r||1!==r.nodeType||!(n.contains?n.contains(r):e.compareDocumentPosition&&16&e.compareDocumentPosition(r)))}:function(e,t){if(t)while(t=t.parentNode)if(t===e)return!0;return!1},A=d.compareDocumentPosition?function(e,t){if(e===t)return S=!0,0;var i=t.compareDocumentPosition&&e.compareDocumentPosition&&e.compareDocumentPosition(t);return i?1&i||!r.sortDetached&&t.compareDocumentPosition(e)===i?e===n||v(w,e)?-1:t===n||v(w,t)?1:c?F.call(c,e)-F.call(c,t):0:4&i?-1:1:e.compareDocumentPosition?-1:1}:function(e,t){var r,i=0,o=e.parentNode,a=t.parentNode,s=[e],l=[t];if(e===t)return S=!0,0;if(!o||!a)return e===n?-1:t===n?1:o?-1:a?1:c?F.call(c,e)-F.call(c,t):0;if(o===a)return pt(e,t);r=e;while(r=r.parentNode)s.unshift(r);r=t;while(r=r.parentNode)l.unshift(r);while(s[i]===l[i])i++;return i?pt(s[i],l[i]):s[i]===w?-1:l[i]===w?1:0},n):f},at.matches=function(e,t){return at(e,null,null,t)},at.matchesSelector=function(e,t){if((e.ownerDocument||e)!==f&&p(e),t=t.replace(Y,"='$1']"),!(!r.matchesSelector||!h||m&&m.test(t)||g&&g.test(t)))try{var n=y.call(e,t);if(n||r.disconnectedMatch||e.document&&11!==e.document.nodeType)return n}catch(i){}return at(t,f,null,[e]).length>0},at.contains=function(e,t){return(e.ownerDocument||e)!==f&&p(e),v(e,t)},at.attr=function(e,n){(e.ownerDocument||e)!==f&&p(e);var i=o.attrHandle[n.toLowerCase()],a=i&&L.call(o.attrHandle,n.toLowerCase())?i(e,n,!h):t;return a===t?r.attributes||!h?e.getAttribute(n):(a=e.getAttributeNode(n))&&a.specified?a.value:null:a},at.error=function(e){throw Error("Syntax error, unrecognized expression: "+e)},at.uniqueSort=function(e){var t,n=[],i=0,o=0;if(S=!r.detectDuplicates,c=!r.sortStable&&e.slice(0),e.sort(A),S){while(t=e[o++])t===e[o]&&(i=n.push(o));while(i--)e.splice(n[i],1)}return e},a=at.getText=function(e){var t,n="",r=0,i=e.nodeType;if(i){if(1===i||9===i||11===i){if("string"==typeof e.textContent)return e.textContent;for(e=e.firstChild;e;e=e.nextSibling)n+=a(e)}else if(3===i||4===i)return e.nodeValue}else for(;t=e[r];r++)n+=a(t);return n},o=at.selectors={cacheLength:50,createPseudo:lt,match:Q,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(e){return e[1]=e[1].replace(rt,it),e[3]=(e[4]||e[5]||"").replace(rt,it),"~="===e[2]&&(e[3]=" "+e[3]+" "),e.slice(0,4)},CHILD:function(e){return e[1]=e[1].toLowerCase(),"nth"===e[1].slice(0,3)?(e[3]||at.error(e[0]),e[4]=+(e[4]?e[5]+(e[6]||1):2*("even"===e[3]||"odd"===e[3])),e[5]=+(e[7]+e[8]||"odd"===e[3])):e[3]&&at.error(e[0]),e},PSEUDO:function(e){var n,r=!e[5]&&e[2];return Q.CHILD.test(e[0])?null:(e[3]&&e[4]!==t?e[2]=e[4]:r&&J.test(r)&&(n=mt(r,!0))&&(n=r.indexOf(")",r.length-n)-r.length)&&(e[0]=e[0].slice(0,n),e[2]=r.slice(0,n)),e.slice(0,3))}},filter:{TAG:function(e){var t=e.replace(rt,it).toLowerCase();return"*"===e?function(){return!0}:function(e){return e.nodeName&&e.nodeName.toLowerCase()===t}},CLASS:function(e){var t=N[e+" "];return t||(t=RegExp("(^|"+P+")"+e+"("+P+"|$)"))&&N(e,function(e){return t.test("string"==typeof e.className&&e.className||typeof e.getAttribute!==j&&e.getAttribute("class")||"")})},ATTR:function(e,t,n){return function(r){var i=at.attr(r,e);return null==i?"!="===t:t?(i+="","="===t?i===n:"!="===t?i!==n:"^="===t?n&&0===i.indexOf(n):"*="===t?n&&i.indexOf(n)>-1:"$="===t?n&&i.slice(-n.length)===n:"~="===t?(" "+i+" ").indexOf(n)>-1:"|="===t?i===n||i.slice(0,n.length+1)===n+"-":!1):!0}},CHILD:function(e,t,n,r,i){var o="nth"!==e.slice(0,3),a="last"!==e.slice(-4),s="of-type"===t;return 1===r&&0===i?function(e){return!!e.parentNode}:function(t,n,l){var u,c,p,f,d,h,g=o!==a?"nextSibling":"previousSibling",m=t.parentNode,y=s&&t.nodeName.toLowerCase(),v=!l&&!s;if(m){if(o){while(g){p=t;while(p=p[g])if(s?p.nodeName.toLowerCase()===y:1===p.nodeType)return!1;h=g="only"===e&&!h&&"nextSibling"}return!0}if(h=[a?m.firstChild:m.lastChild],a&&v){c=m[b]||(m[b]={}),u=c[e]||[],d=u[0]===T&&u[1],f=u[0]===T&&u[2],p=d&&m.childNodes[d];while(p=++d&&p&&p[g]||(f=d=0)||h.pop())if(1===p.nodeType&&++f&&p===t){c[e]=[T,d,f];break}}else if(v&&(u=(t[b]||(t[b]={}))[e])&&u[0]===T)f=u[1];else while(p=++d&&p&&p[g]||(f=d=0)||h.pop())if((s?p.nodeName.toLowerCase()===y:1===p.nodeType)&&++f&&(v&&((p[b]||(p[b]={}))[e]=[T,f]),p===t))break;return f-=i,f===r||0===f%r&&f/r>=0}}},PSEUDO:function(e,t){var n,r=o.pseudos[e]||o.setFilters[e.toLowerCase()]||at.error("unsupported pseudo: "+e);return r[b]?r(t):r.length>1?(n=[e,e,"",t],o.setFilters.hasOwnProperty(e.toLowerCase())?lt(function(e,n){var i,o=r(e,t),a=o.length;while(a--)i=F.call(e,o[a]),e[i]=!(n[i]=o[a])}):function(e){return r(e,0,n)}):r}},pseudos:{not:lt(function(e){var t=[],n=[],r=l(e.replace(z,"$1"));return r[b]?lt(function(e,t,n,i){var o,a=r(e,null,i,[]),s=e.length;while(s--)(o=a[s])&&(e[s]=!(t[s]=o))}):function(e,i,o){return t[0]=e,r(t,null,o,n),!n.pop()}}),has:lt(function(e){return function(t){return at(e,t).length>0}}),contains:lt(function(e){return function(t){return(t.textContent||t.innerText||a(t)).indexOf(e)>-1}}),lang:lt(function(e){return G.test(e||"")||at.error("unsupported lang: "+e),e=e.replace(rt,it).toLowerCase(),function(t){var n;do if(n=h?t.lang:t.getAttribute("xml:lang")||t.getAttribute("lang"))return n=n.toLowerCase(),n===e||0===n.indexOf(e+"-");while((t=t.parentNode)&&1===t.nodeType);return!1}}),target:function(t){var n=e.location&&e.location.hash;return n&&n.slice(1)===t.id},root:function(e){return e===d},focus:function(e){return e===f.activeElement&&(!f.hasFocus||f.hasFocus())&&!!(e.type||e.href||~e.tabIndex)},enabled:function(e){return e.disabled===!1},disabled:function(e){return e.disabled===!0},checked:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&!!e.checked||"option"===t&&!!e.selected},selected:function(e){return e.parentNode&&e.parentNode.selectedIndex,e.selected===!0},empty:function(e){for(e=e.firstChild;e;e=e.nextSibling)if(e.nodeName>"@"||3===e.nodeType||4===e.nodeType)return!1;return!0},parent:function(e){return!o.pseudos.empty(e)},header:function(e){return tt.test(e.nodeName)},input:function(e){return et.test(e.nodeName)},button:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&"button"===e.type||"button"===t},text:function(e){var t;return"input"===e.nodeName.toLowerCase()&&"text"===e.type&&(null==(t=e.getAttribute("type"))||t.toLowerCase()===e.type)},first:ht(function(){return[0]}),last:ht(function(e,t){return[t-1]}),eq:ht(function(e,t,n){return[0>n?n+t:n]}),even:ht(function(e,t){var n=0;for(;t>n;n+=2)e.push(n);return e}),odd:ht(function(e,t){var n=1;for(;t>n;n+=2)e.push(n);return e}),lt:ht(function(e,t,n){var r=0>n?n+t:n;for(;--r>=0;)e.push(r);return e}),gt:ht(function(e,t,n){var r=0>n?n+t:n;for(;t>++r;)e.push(r);return e})}},o.pseudos.nth=o.pseudos.eq;for(n in{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})o.pseudos[n]=ft(n);for(n in{submit:!0,reset:!0})o.pseudos[n]=dt(n);function gt(){}gt.prototype=o.filters=o.pseudos,o.setFilters=new gt;function mt(e,t){var n,r,i,a,s,l,u,c=k[e+" "];if(c)return t?0:c.slice(0);s=e,l=[],u=o.preFilter;while(s){(!n||(r=X.exec(s)))&&(r&&(s=s.slice(r[0].length)||s),l.push(i=[])),n=!1,(r=U.exec(s))&&(n=r.shift(),i.push({value:n,type:r[0].replace(z," ")}),s=s.slice(n.length));for(a in o.filter)!(r=Q[a].exec(s))||u[a]&&!(r=u[a](r))||(n=r.shift(),i.push({value:n,type:a,matches:r}),s=s.slice(n.length));if(!n)break}return t?s.length:s?at.error(e):k(e,l).slice(0)}function yt(e){var t=0,n=e.length,r="";for(;n>t;t++)r+=e[t].value;return r}function vt(e,t,n){var r=t.dir,o=n&&"parentNode"===r,a=C++;return t.first?function(t,n,i){while(t=t[r])if(1===t.nodeType||o)return e(t,n,i)}:function(t,n,s){var l,u,c,p=T+" "+a;if(s){while(t=t[r])if((1===t.nodeType||o)&&e(t,n,s))return!0}else while(t=t[r])if(1===t.nodeType||o)if(c=t[b]||(t[b]={}),(u=c[r])&&u[0]===p){if((l=u[1])===!0||l===i)return l===!0}else if(u=c[r]=[p],u[1]=e(t,n,s)||i,u[1]===!0)return!0}}function bt(e){return e.length>1?function(t,n,r){var i=e.length;while(i--)if(!e[i](t,n,r))return!1;return!0}:e[0]}function xt(e,t,n,r,i){var o,a=[],s=0,l=e.length,u=null!=t;for(;l>s;s++)(o=e[s])&&(!n||n(o,r,i))&&(a.push(o),u&&t.push(s));return a}function wt(e,t,n,r,i,o){return r&&!r[b]&&(r=wt(r)),i&&!i[b]&&(i=wt(i,o)),lt(function(o,a,s,l){var u,c,p,f=[],d=[],h=a.length,g=o||Nt(t||"*",s.nodeType?[s]:s,[]),m=!e||!o&&t?g:xt(g,f,e,s,l),y=n?i||(o?e:h||r)?[]:a:m;if(n&&n(m,y,s,l),r){u=xt(y,d),r(u,[],s,l),c=u.length;while(c--)(p=u[c])&&(y[d[c]]=!(m[d[c]]=p))}if(o){if(i||e){if(i){u=[],c=y.length;while(c--)(p=y[c])&&u.push(m[c]=p);i(null,y=[],u,l)}c=y.length;while(c--)(p=y[c])&&(u=i?F.call(o,p):f[c])>-1&&(o[u]=!(a[u]=p))}}else y=xt(y===a?y.splice(h,y.length):y),i?i(null,a,y,l):M.apply(a,y)})}function Tt(e){var t,n,r,i=e.length,a=o.relative[e[0].type],s=a||o.relative[" "],l=a?1:0,c=vt(function(e){return e===t},s,!0),p=vt(function(e){return F.call(t,e)>-1},s,!0),f=[function(e,n,r){return!a&&(r||n!==u)||((t=n).nodeType?c(e,n,r):p(e,n,r))}];for(;i>l;l++)if(n=o.relative[e[l].type])f=[vt(bt(f),n)];else{if(n=o.filter[e[l].type].apply(null,e[l].matches),n[b]){for(r=++l;i>r;r++)if(o.relative[e[r].type])break;return wt(l>1&&bt(f),l>1&&yt(e.slice(0,l-1).concat({value:" "===e[l-2].type?"*":""})).replace(z,"$1"),n,r>l&&Tt(e.slice(l,r)),i>r&&Tt(e=e.slice(r)),i>r&&yt(e))}f.push(n)}return bt(f)}function Ct(e,t){var n=0,r=t.length>0,a=e.length>0,s=function(s,l,c,p,d){var h,g,m,y=[],v=0,b="0",x=s&&[],w=null!=d,C=u,N=s||a&&o.find.TAG("*",d&&l.parentNode||l),k=T+=null==C?1:Math.random()||.1;for(w&&(u=l!==f&&l,i=n);null!=(h=N[b]);b++){if(a&&h){g=0;while(m=e[g++])if(m(h,l,c)){p.push(h);break}w&&(T=k,i=++n)}r&&((h=!m&&h)&&v--,s&&x.push(h))}if(v+=b,r&&b!==v){g=0;while(m=t[g++])m(x,y,l,c);if(s){if(v>0)while(b--)x[b]||y[b]||(y[b]=q.call(p));y=xt(y)}M.apply(p,y),w&&!s&&y.length>0&&v+t.length>1&&at.uniqueSort(p)}return w&&(T=k,u=C),x};return r?lt(s):s}l=at.compile=function(e,t){var n,r=[],i=[],o=E[e+" "];if(!o){t||(t=mt(e)),n=t.length;while(n--)o=Tt(t[n]),o[b]?r.push(o):i.push(o);o=E(e,Ct(i,r))}return o};function Nt(e,t,n){var r=0,i=t.length;for(;i>r;r++)at(e,t[r],n);return n}function kt(e,t,n,i){var a,s,u,c,p,f=mt(e);if(!i&&1===f.length){if(s=f[0]=f[0].slice(0),s.length>2&&"ID"===(u=s[0]).type&&r.getById&&9===t.nodeType&&h&&o.relative[s[1].type]){if(t=(o.find.ID(u.matches[0].replace(rt,it),t)||[])[0],!t)return n;e=e.slice(s.shift().value.length)}a=Q.needsContext.test(e)?0:s.length;while(a--){if(u=s[a],o.relative[c=u.type])break;if((p=o.find[c])&&(i=p(u.matches[0].replace(rt,it),V.test(s[0].type)&&t.parentNode||t))){if(s.splice(a,1),e=i.length&&yt(s),!e)return M.apply(n,i),n;break}}}return l(e,f)(i,t,!h,n,V.test(e)),n}r.sortStable=b.split("").sort(A).join("")===b,r.detectDuplicates=S,p(),r.sortDetached=ut(function(e){return 1&e.compareDocumentPosition(f.createElement("div"))}),ut(function(e){return e.innerHTML="<a href='#'></a>","#"===e.firstChild.getAttribute("href")})||ct("type|href|height|width",function(e,n,r){return r?t:e.getAttribute(n,"type"===n.toLowerCase()?1:2)}),r.attributes&&ut(function(e){return e.innerHTML="<input/>",e.firstChild.setAttribute("value",""),""===e.firstChild.getAttribute("value")})||ct("value",function(e,n,r){return r||"input"!==e.nodeName.toLowerCase()?t:e.defaultValue}),ut(function(e){return null==e.getAttribute("disabled")})||ct(B,function(e,n,r){var i;return r?t:(i=e.getAttributeNode(n))&&i.specified?i.value:e[n]===!0?n.toLowerCase():null}),x.find=at,x.expr=at.selectors,x.expr[":"]=x.expr.pseudos,x.unique=at.uniqueSort,x.text=at.getText,x.isXMLDoc=at.isXML,x.contains=at.contains}(e);var O={};function F(e){var t=O[e]={};return x.each(e.match(T)||[],function(e,n){t[n]=!0}),t}x.Callbacks=function(e){e="string"==typeof e?O[e]||F(e):x.extend({},e);var n,r,i,o,a,s,l=[],u=!e.once&&[],c=function(t){for(r=e.memory&&t,i=!0,a=s||0,s=0,o=l.length,n=!0;l&&o>a;a++)if(l[a].apply(t[0],t[1])===!1&&e.stopOnFalse){r=!1;break}n=!1,l&&(u?u.length&&c(u.shift()):r?l=[]:p.disable())},p={add:function(){if(l){var t=l.length;(function i(t){x.each(t,function(t,n){var r=x.type(n);"function"===r?e.unique&&p.has(n)||l.push(n):n&&n.length&&"string"!==r&&i(n)})})(arguments),n?o=l.length:r&&(s=t,c(r))}return this},remove:function(){return l&&x.each(arguments,function(e,t){var r;while((r=x.inArray(t,l,r))>-1)l.splice(r,1),n&&(o>=r&&o--,a>=r&&a--)}),this},has:function(e){return e?x.inArray(e,l)>-1:!(!l||!l.length)},empty:function(){return l=[],o=0,this},disable:function(){return l=u=r=t,this},disabled:function(){return!l},lock:function(){return u=t,r||p.disable(),this},locked:function(){return!u},fireWith:function(e,t){return!l||i&&!u||(t=t||[],t=[e,t.slice?t.slice():t],n?u.push(t):c(t)),this},fire:function(){return p.fireWith(this,arguments),this},fired:function(){return!!i}};return p},x.extend({Deferred:function(e){var t=[["resolve","done",x.Callbacks("once memory"),"resolved"],["reject","fail",x.Callbacks("once memory"),"rejected"],["notify","progress",x.Callbacks("memory")]],n="pending",r={state:function(){return n},always:function(){return i.done(arguments).fail(arguments),this},then:function(){var e=arguments;return x.Deferred(function(n){x.each(t,function(t,o){var a=o[0],s=x.isFunction(e[t])&&e[t];i[o[1]](function(){var e=s&&s.apply(this,arguments);e&&x.isFunction(e.promise)?e.promise().done(n.resolve).fail(n.reject).progress(n.notify):n[a+"With"](this===r?n.promise():this,s?[e]:arguments)})}),e=null}).promise()},promise:function(e){return null!=e?x.extend(e,r):r}},i={};return r.pipe=r.then,x.each(t,function(e,o){var a=o[2],s=o[3];r[o[1]]=a.add,s&&a.add(function(){n=s},t[1^e][2].disable,t[2][2].lock),i[o[0]]=function(){return i[o[0]+"With"](this===i?r:this,arguments),this},i[o[0]+"With"]=a.fireWith}),r.promise(i),e&&e.call(i,i),i},when:function(e){var t=0,n=g.call(arguments),r=n.length,i=1!==r||e&&x.isFunction(e.promise)?r:0,o=1===i?e:x.Deferred(),a=function(e,t,n){return function(r){t[e]=this,n[e]=arguments.length>1?g.call(arguments):r,n===s?o.notifyWith(t,n):--i||o.resolveWith(t,n)}},s,l,u;if(r>1)for(s=Array(r),l=Array(r),u=Array(r);r>t;t++)n[t]&&x.isFunction(n[t].promise)?n[t].promise().done(a(t,u,n)).fail(o.reject).progress(a(t,l,s)):--i;return i||o.resolveWith(u,n),o.promise()}}),x.support=function(t){var n,r,o,s,l,u,c,p,f,d=a.createElement("div");if(d.setAttribute("className","t"),d.innerHTML="  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",n=d.getElementsByTagName("*")||[],r=d.getElementsByTagName("a")[0],!r||!r.style||!n.length)return t;s=a.createElement("select"),u=s.appendChild(a.createElement("option")),o=d.getElementsByTagName("input")[0],r.style.cssText="top:1px;float:left;opacity:.5",t.getSetAttribute="t"!==d.className,t.leadingWhitespace=3===d.firstChild.nodeType,t.tbody=!d.getElementsByTagName("tbody").length,t.htmlSerialize=!!d.getElementsByTagName("link").length,t.style=/top/.test(r.getAttribute("style")),t.hrefNormalized="/a"===r.getAttribute("href"),t.opacity=/^0.5/.test(r.style.opacity),t.cssFloat=!!r.style.cssFloat,t.checkOn=!!o.value,t.optSelected=u.selected,t.enctype=!!a.createElement("form").enctype,t.html5Clone="<:nav></:nav>"!==a.createElement("nav").cloneNode(!0).outerHTML,t.inlineBlockNeedsLayout=!1,t.shrinkWrapBlocks=!1,t.pixelPosition=!1,t.deleteExpando=!0,t.noCloneEvent=!0,t.reliableMarginRight=!0,t.boxSizingReliable=!0,o.checked=!0,t.noCloneChecked=o.cloneNode(!0).checked,s.disabled=!0,t.optDisabled=!u.disabled;try{delete d.test}catch(h){t.deleteExpando=!1}o=a.createElement("input"),o.setAttribute("value",""),t.input=""===o.getAttribute("value"),o.value="t",o.setAttribute("type","radio"),t.radioValue="t"===o.value,o.setAttribute("checked","t"),o.setAttribute("name","t"),l=a.createDocumentFragment(),l.appendChild(o),t.appendChecked=o.checked,t.checkClone=l.cloneNode(!0).cloneNode(!0).lastChild.checked,d.attachEvent&&(d.attachEvent("onclick",function(){t.noCloneEvent=!1}),d.cloneNode(!0).click());for(f in{submit:!0,change:!0,focusin:!0})d.setAttribute(c="on"+f,"t"),t[f+"Bubbles"]=c in e||d.attributes[c].expando===!1;d.style.backgroundClip="content-box",d.cloneNode(!0).style.backgroundClip="",t.clearCloneStyle="content-box"===d.style.backgroundClip;for(f in x(t))break;return t.ownLast="0"!==f,x(function(){var n,r,o,s="padding:0;margin:0;border:0;display:block;box-sizing:content-box;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;",l=a.getElementsByTagName("body")[0];l&&(n=a.createElement("div"),n.style.cssText="border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px",l.appendChild(n).appendChild(d),d.innerHTML="<table><tr><td></td><td>t</td></tr></table>",o=d.getElementsByTagName("td"),o[0].style.cssText="padding:0;margin:0;border:0;display:none",p=0===o[0].offsetHeight,o[0].style.display="",o[1].style.display="none",t.reliableHiddenOffsets=p&&0===o[0].offsetHeight,d.innerHTML="",d.style.cssText="box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;",x.swap(l,null!=l.style.zoom?{zoom:1}:{},function(){t.boxSizing=4===d.offsetWidth}),e.getComputedStyle&&(t.pixelPosition="1%"!==(e.getComputedStyle(d,null)||{}).top,t.boxSizingReliable="4px"===(e.getComputedStyle(d,null)||{width:"4px"}).width,r=d.appendChild(a.createElement("div")),r.style.cssText=d.style.cssText=s,r.style.marginRight=r.style.width="0",d.style.width="1px",t.reliableMarginRight=!parseFloat((e.getComputedStyle(r,null)||{}).marginRight)),typeof d.style.zoom!==i&&(d.innerHTML="",d.style.cssText=s+"width:1px;padding:1px;display:inline;zoom:1",t.inlineBlockNeedsLayout=3===d.offsetWidth,d.style.display="block",d.innerHTML="<div></div>",d.firstChild.style.width="5px",t.shrinkWrapBlocks=3!==d.offsetWidth,t.inlineBlockNeedsLayout&&(l.style.zoom=1)),l.removeChild(n),n=d=o=r=null)}),n=s=l=u=r=o=null,t
}({});var B=/(?:\{[\s\S]*\}|\[[\s\S]*\])$/,P=/([A-Z])/g;function R(e,n,r,i){if(x.acceptData(e)){var o,a,s=x.expando,l=e.nodeType,u=l?x.cache:e,c=l?e[s]:e[s]&&s;if(c&&u[c]&&(i||u[c].data)||r!==t||"string"!=typeof n)return c||(c=l?e[s]=p.pop()||x.guid++:s),u[c]||(u[c]=l?{}:{toJSON:x.noop}),("object"==typeof n||"function"==typeof n)&&(i?u[c]=x.extend(u[c],n):u[c].data=x.extend(u[c].data,n)),a=u[c],i||(a.data||(a.data={}),a=a.data),r!==t&&(a[x.camelCase(n)]=r),"string"==typeof n?(o=a[n],null==o&&(o=a[x.camelCase(n)])):o=a,o}}function W(e,t,n){if(x.acceptData(e)){var r,i,o=e.nodeType,a=o?x.cache:e,s=o?e[x.expando]:x.expando;if(a[s]){if(t&&(r=n?a[s]:a[s].data)){x.isArray(t)?t=t.concat(x.map(t,x.camelCase)):t in r?t=[t]:(t=x.camelCase(t),t=t in r?[t]:t.split(" ")),i=t.length;while(i--)delete r[t[i]];if(n?!I(r):!x.isEmptyObject(r))return}(n||(delete a[s].data,I(a[s])))&&(o?x.cleanData([e],!0):x.support.deleteExpando||a!=a.window?delete a[s]:a[s]=null)}}}x.extend({cache:{},noData:{applet:!0,embed:!0,object:"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"},hasData:function(e){return e=e.nodeType?x.cache[e[x.expando]]:e[x.expando],!!e&&!I(e)},data:function(e,t,n){return R(e,t,n)},removeData:function(e,t){return W(e,t)},_data:function(e,t,n){return R(e,t,n,!0)},_removeData:function(e,t){return W(e,t,!0)},acceptData:function(e){if(e.nodeType&&1!==e.nodeType&&9!==e.nodeType)return!1;var t=e.nodeName&&x.noData[e.nodeName.toLowerCase()];return!t||t!==!0&&e.getAttribute("classid")===t}}),x.fn.extend({data:function(e,n){var r,i,o=null,a=0,s=this[0];if(e===t){if(this.length&&(o=x.data(s),1===s.nodeType&&!x._data(s,"parsedAttrs"))){for(r=s.attributes;r.length>a;a++)i=r[a].name,0===i.indexOf("data-")&&(i=x.camelCase(i.slice(5)),$(s,i,o[i]));x._data(s,"parsedAttrs",!0)}return o}return"object"==typeof e?this.each(function(){x.data(this,e)}):arguments.length>1?this.each(function(){x.data(this,e,n)}):s?$(s,e,x.data(s,e)):null},removeData:function(e){return this.each(function(){x.removeData(this,e)})}});function $(e,n,r){if(r===t&&1===e.nodeType){var i="data-"+n.replace(P,"-$1").toLowerCase();if(r=e.getAttribute(i),"string"==typeof r){try{r="true"===r?!0:"false"===r?!1:"null"===r?null:+r+""===r?+r:B.test(r)?x.parseJSON(r):r}catch(o){}x.data(e,n,r)}else r=t}return r}function I(e){var t;for(t in e)if(("data"!==t||!x.isEmptyObject(e[t]))&&"toJSON"!==t)return!1;return!0}x.extend({queue:function(e,n,r){var i;return e?(n=(n||"fx")+"queue",i=x._data(e,n),r&&(!i||x.isArray(r)?i=x._data(e,n,x.makeArray(r)):i.push(r)),i||[]):t},dequeue:function(e,t){t=t||"fx";var n=x.queue(e,t),r=n.length,i=n.shift(),o=x._queueHooks(e,t),a=function(){x.dequeue(e,t)};"inprogress"===i&&(i=n.shift(),r--),i&&("fx"===t&&n.unshift("inprogress"),delete o.stop,i.call(e,a,o)),!r&&o&&o.empty.fire()},_queueHooks:function(e,t){var n=t+"queueHooks";return x._data(e,n)||x._data(e,n,{empty:x.Callbacks("once memory").add(function(){x._removeData(e,t+"queue"),x._removeData(e,n)})})}}),x.fn.extend({queue:function(e,n){var r=2;return"string"!=typeof e&&(n=e,e="fx",r--),r>arguments.length?x.queue(this[0],e):n===t?this:this.each(function(){var t=x.queue(this,e,n);x._queueHooks(this,e),"fx"===e&&"inprogress"!==t[0]&&x.dequeue(this,e)})},dequeue:function(e){return this.each(function(){x.dequeue(this,e)})},delay:function(e,t){return e=x.fx?x.fx.speeds[e]||e:e,t=t||"fx",this.queue(t,function(t,n){var r=setTimeout(t,e);n.stop=function(){clearTimeout(r)}})},clearQueue:function(e){return this.queue(e||"fx",[])},promise:function(e,n){var r,i=1,o=x.Deferred(),a=this,s=this.length,l=function(){--i||o.resolveWith(a,[a])};"string"!=typeof e&&(n=e,e=t),e=e||"fx";while(s--)r=x._data(a[s],e+"queueHooks"),r&&r.empty&&(i++,r.empty.add(l));return l(),o.promise(n)}});var z,X,U=/[\t\r\n\f]/g,V=/\r/g,Y=/^(?:input|select|textarea|button|object)$/i,J=/^(?:a|area)$/i,G=/^(?:checked|selected)$/i,Q=x.support.getSetAttribute,K=x.support.input;x.fn.extend({attr:function(e,t){return x.access(this,x.attr,e,t,arguments.length>1)},removeAttr:function(e){return this.each(function(){x.removeAttr(this,e)})},prop:function(e,t){return x.access(this,x.prop,e,t,arguments.length>1)},removeProp:function(e){return e=x.propFix[e]||e,this.each(function(){try{this[e]=t,delete this[e]}catch(n){}})},addClass:function(e){var t,n,r,i,o,a=0,s=this.length,l="string"==typeof e&&e;if(x.isFunction(e))return this.each(function(t){x(this).addClass(e.call(this,t,this.className))});if(l)for(t=(e||"").match(T)||[];s>a;a++)if(n=this[a],r=1===n.nodeType&&(n.className?(" "+n.className+" ").replace(U," "):" ")){o=0;while(i=t[o++])0>r.indexOf(" "+i+" ")&&(r+=i+" ");n.className=x.trim(r)}return this},removeClass:function(e){var t,n,r,i,o,a=0,s=this.length,l=0===arguments.length||"string"==typeof e&&e;if(x.isFunction(e))return this.each(function(t){x(this).removeClass(e.call(this,t,this.className))});if(l)for(t=(e||"").match(T)||[];s>a;a++)if(n=this[a],r=1===n.nodeType&&(n.className?(" "+n.className+" ").replace(U," "):"")){o=0;while(i=t[o++])while(r.indexOf(" "+i+" ")>=0)r=r.replace(" "+i+" "," ");n.className=e?x.trim(r):""}return this},toggleClass:function(e,t){var n=typeof e;return"boolean"==typeof t&&"string"===n?t?this.addClass(e):this.removeClass(e):x.isFunction(e)?this.each(function(n){x(this).toggleClass(e.call(this,n,this.className,t),t)}):this.each(function(){if("string"===n){var t,r=0,o=x(this),a=e.match(T)||[];while(t=a[r++])o.hasClass(t)?o.removeClass(t):o.addClass(t)}else(n===i||"boolean"===n)&&(this.className&&x._data(this,"__className__",this.className),this.className=this.className||e===!1?"":x._data(this,"__className__")||"")})},hasClass:function(e){var t=" "+e+" ",n=0,r=this.length;for(;r>n;n++)if(1===this[n].nodeType&&(" "+this[n].className+" ").replace(U," ").indexOf(t)>=0)return!0;return!1},val:function(e){var n,r,i,o=this[0];{if(arguments.length)return i=x.isFunction(e),this.each(function(n){var o;1===this.nodeType&&(o=i?e.call(this,n,x(this).val()):e,null==o?o="":"number"==typeof o?o+="":x.isArray(o)&&(o=x.map(o,function(e){return null==e?"":e+""})),r=x.valHooks[this.type]||x.valHooks[this.nodeName.toLowerCase()],r&&"set"in r&&r.set(this,o,"value")!==t||(this.value=o))});if(o)return r=x.valHooks[o.type]||x.valHooks[o.nodeName.toLowerCase()],r&&"get"in r&&(n=r.get(o,"value"))!==t?n:(n=o.value,"string"==typeof n?n.replace(V,""):null==n?"":n)}}}),x.extend({valHooks:{option:{get:function(e){var t=x.find.attr(e,"value");return null!=t?t:e.text}},select:{get:function(e){var t,n,r=e.options,i=e.selectedIndex,o="select-one"===e.type||0>i,a=o?null:[],s=o?i+1:r.length,l=0>i?s:o?i:0;for(;s>l;l++)if(n=r[l],!(!n.selected&&l!==i||(x.support.optDisabled?n.disabled:null!==n.getAttribute("disabled"))||n.parentNode.disabled&&x.nodeName(n.parentNode,"optgroup"))){if(t=x(n).val(),o)return t;a.push(t)}return a},set:function(e,t){var n,r,i=e.options,o=x.makeArray(t),a=i.length;while(a--)r=i[a],(r.selected=x.inArray(x(r).val(),o)>=0)&&(n=!0);return n||(e.selectedIndex=-1),o}}},attr:function(e,n,r){var o,a,s=e.nodeType;if(e&&3!==s&&8!==s&&2!==s)return typeof e.getAttribute===i?x.prop(e,n,r):(1===s&&x.isXMLDoc(e)||(n=n.toLowerCase(),o=x.attrHooks[n]||(x.expr.match.bool.test(n)?X:z)),r===t?o&&"get"in o&&null!==(a=o.get(e,n))?a:(a=x.find.attr(e,n),null==a?t:a):null!==r?o&&"set"in o&&(a=o.set(e,r,n))!==t?a:(e.setAttribute(n,r+""),r):(x.removeAttr(e,n),t))},removeAttr:function(e,t){var n,r,i=0,o=t&&t.match(T);if(o&&1===e.nodeType)while(n=o[i++])r=x.propFix[n]||n,x.expr.match.bool.test(n)?K&&Q||!G.test(n)?e[r]=!1:e[x.camelCase("default-"+n)]=e[r]=!1:x.attr(e,n,""),e.removeAttribute(Q?n:r)},attrHooks:{type:{set:function(e,t){if(!x.support.radioValue&&"radio"===t&&x.nodeName(e,"input")){var n=e.value;return e.setAttribute("type",t),n&&(e.value=n),t}}}},propFix:{"for":"htmlFor","class":"className"},prop:function(e,n,r){var i,o,a,s=e.nodeType;if(e&&3!==s&&8!==s&&2!==s)return a=1!==s||!x.isXMLDoc(e),a&&(n=x.propFix[n]||n,o=x.propHooks[n]),r!==t?o&&"set"in o&&(i=o.set(e,r,n))!==t?i:e[n]=r:o&&"get"in o&&null!==(i=o.get(e,n))?i:e[n]},propHooks:{tabIndex:{get:function(e){var t=x.find.attr(e,"tabindex");return t?parseInt(t,10):Y.test(e.nodeName)||J.test(e.nodeName)&&e.href?0:-1}}}}),X={set:function(e,t,n){return t===!1?x.removeAttr(e,n):K&&Q||!G.test(n)?e.setAttribute(!Q&&x.propFix[n]||n,n):e[x.camelCase("default-"+n)]=e[n]=!0,n}},x.each(x.expr.match.bool.source.match(/\w+/g),function(e,n){var r=x.expr.attrHandle[n]||x.find.attr;x.expr.attrHandle[n]=K&&Q||!G.test(n)?function(e,n,i){var o=x.expr.attrHandle[n],a=i?t:(x.expr.attrHandle[n]=t)!=r(e,n,i)?n.toLowerCase():null;return x.expr.attrHandle[n]=o,a}:function(e,n,r){return r?t:e[x.camelCase("default-"+n)]?n.toLowerCase():null}}),K&&Q||(x.attrHooks.value={set:function(e,n,r){return x.nodeName(e,"input")?(e.defaultValue=n,t):z&&z.set(e,n,r)}}),Q||(z={set:function(e,n,r){var i=e.getAttributeNode(r);return i||e.setAttributeNode(i=e.ownerDocument.createAttribute(r)),i.value=n+="","value"===r||n===e.getAttribute(r)?n:t}},x.expr.attrHandle.id=x.expr.attrHandle.name=x.expr.attrHandle.coords=function(e,n,r){var i;return r?t:(i=e.getAttributeNode(n))&&""!==i.value?i.value:null},x.valHooks.button={get:function(e,n){var r=e.getAttributeNode(n);return r&&r.specified?r.value:t},set:z.set},x.attrHooks.contenteditable={set:function(e,t,n){z.set(e,""===t?!1:t,n)}},x.each(["width","height"],function(e,n){x.attrHooks[n]={set:function(e,r){return""===r?(e.setAttribute(n,"auto"),r):t}}})),x.support.hrefNormalized||x.each(["href","src"],function(e,t){x.propHooks[t]={get:function(e){return e.getAttribute(t,4)}}}),x.support.style||(x.attrHooks.style={get:function(e){return e.style.cssText||t},set:function(e,t){return e.style.cssText=t+""}}),x.support.optSelected||(x.propHooks.selected={get:function(e){var t=e.parentNode;return t&&(t.selectedIndex,t.parentNode&&t.parentNode.selectedIndex),null}}),x.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],function(){x.propFix[this.toLowerCase()]=this}),x.support.enctype||(x.propFix.enctype="encoding"),x.each(["radio","checkbox"],function(){x.valHooks[this]={set:function(e,n){return x.isArray(n)?e.checked=x.inArray(x(e).val(),n)>=0:t}},x.support.checkOn||(x.valHooks[this].get=function(e){return null===e.getAttribute("value")?"on":e.value})});var Z=/^(?:input|select|textarea)$/i,et=/^key/,tt=/^(?:mouse|contextmenu)|click/,nt=/^(?:focusinfocus|focusoutblur)$/,rt=/^([^.]*)(?:\.(.+)|)$/;function it(){return!0}function ot(){return!1}function at(){try{return a.activeElement}catch(e){}}x.event={global:{},add:function(e,n,r,o,a){var s,l,u,c,p,f,d,h,g,m,y,v=x._data(e);if(v){r.handler&&(c=r,r=c.handler,a=c.selector),r.guid||(r.guid=x.guid++),(l=v.events)||(l=v.events={}),(f=v.handle)||(f=v.handle=function(e){return typeof x===i||e&&x.event.triggered===e.type?t:x.event.dispatch.apply(f.elem,arguments)},f.elem=e),n=(n||"").match(T)||[""],u=n.length;while(u--)s=rt.exec(n[u])||[],g=y=s[1],m=(s[2]||"").split(".").sort(),g&&(p=x.event.special[g]||{},g=(a?p.delegateType:p.bindType)||g,p=x.event.special[g]||{},d=x.extend({type:g,origType:y,data:o,handler:r,guid:r.guid,selector:a,needsContext:a&&x.expr.match.needsContext.test(a),namespace:m.join(".")},c),(h=l[g])||(h=l[g]=[],h.delegateCount=0,p.setup&&p.setup.call(e,o,m,f)!==!1||(e.addEventListener?e.addEventListener(g,f,!1):e.attachEvent&&e.attachEvent("on"+g,f))),p.add&&(p.add.call(e,d),d.handler.guid||(d.handler.guid=r.guid)),a?h.splice(h.delegateCount++,0,d):h.push(d),x.event.global[g]=!0);e=null}},remove:function(e,t,n,r,i){var o,a,s,l,u,c,p,f,d,h,g,m=x.hasData(e)&&x._data(e);if(m&&(c=m.events)){t=(t||"").match(T)||[""],u=t.length;while(u--)if(s=rt.exec(t[u])||[],d=g=s[1],h=(s[2]||"").split(".").sort(),d){p=x.event.special[d]||{},d=(r?p.delegateType:p.bindType)||d,f=c[d]||[],s=s[2]&&RegExp("(^|\\.)"+h.join("\\.(?:.*\\.|)")+"(\\.|$)"),l=o=f.length;while(o--)a=f[o],!i&&g!==a.origType||n&&n.guid!==a.guid||s&&!s.test(a.namespace)||r&&r!==a.selector&&("**"!==r||!a.selector)||(f.splice(o,1),a.selector&&f.delegateCount--,p.remove&&p.remove.call(e,a));l&&!f.length&&(p.teardown&&p.teardown.call(e,h,m.handle)!==!1||x.removeEvent(e,d,m.handle),delete c[d])}else for(d in c)x.event.remove(e,d+t[u],n,r,!0);x.isEmptyObject(c)&&(delete m.handle,x._removeData(e,"events"))}},trigger:function(n,r,i,o){var s,l,u,c,p,f,d,h=[i||a],g=v.call(n,"type")?n.type:n,m=v.call(n,"namespace")?n.namespace.split("."):[];if(u=f=i=i||a,3!==i.nodeType&&8!==i.nodeType&&!nt.test(g+x.event.triggered)&&(g.indexOf(".")>=0&&(m=g.split("."),g=m.shift(),m.sort()),l=0>g.indexOf(":")&&"on"+g,n=n[x.expando]?n:new x.Event(g,"object"==typeof n&&n),n.isTrigger=o?2:3,n.namespace=m.join("."),n.namespace_re=n.namespace?RegExp("(^|\\.)"+m.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,n.result=t,n.target||(n.target=i),r=null==r?[n]:x.makeArray(r,[n]),p=x.event.special[g]||{},o||!p.trigger||p.trigger.apply(i,r)!==!1)){if(!o&&!p.noBubble&&!x.isWindow(i)){for(c=p.delegateType||g,nt.test(c+g)||(u=u.parentNode);u;u=u.parentNode)h.push(u),f=u;f===(i.ownerDocument||a)&&h.push(f.defaultView||f.parentWindow||e)}d=0;while((u=h[d++])&&!n.isPropagationStopped())n.type=d>1?c:p.bindType||g,s=(x._data(u,"events")||{})[n.type]&&x._data(u,"handle"),s&&s.apply(u,r),s=l&&u[l],s&&x.acceptData(u)&&s.apply&&s.apply(u,r)===!1&&n.preventDefault();if(n.type=g,!o&&!n.isDefaultPrevented()&&(!p._default||p._default.apply(h.pop(),r)===!1)&&x.acceptData(i)&&l&&i[g]&&!x.isWindow(i)){f=i[l],f&&(i[l]=null),x.event.triggered=g;try{i[g]()}catch(y){}x.event.triggered=t,f&&(i[l]=f)}return n.result}},dispatch:function(e){e=x.event.fix(e);var n,r,i,o,a,s=[],l=g.call(arguments),u=(x._data(this,"events")||{})[e.type]||[],c=x.event.special[e.type]||{};if(l[0]=e,e.delegateTarget=this,!c.preDispatch||c.preDispatch.call(this,e)!==!1){s=x.event.handlers.call(this,e,u),n=0;while((o=s[n++])&&!e.isPropagationStopped()){e.currentTarget=o.elem,a=0;while((i=o.handlers[a++])&&!e.isImmediatePropagationStopped())(!e.namespace_re||e.namespace_re.test(i.namespace))&&(e.handleObj=i,e.data=i.data,r=((x.event.special[i.origType]||{}).handle||i.handler).apply(o.elem,l),r!==t&&(e.result=r)===!1&&(e.preventDefault(),e.stopPropagation()))}return c.postDispatch&&c.postDispatch.call(this,e),e.result}},handlers:function(e,n){var r,i,o,a,s=[],l=n.delegateCount,u=e.target;if(l&&u.nodeType&&(!e.button||"click"!==e.type))for(;u!=this;u=u.parentNode||this)if(1===u.nodeType&&(u.disabled!==!0||"click"!==e.type)){for(o=[],a=0;l>a;a++)i=n[a],r=i.selector+" ",o[r]===t&&(o[r]=i.needsContext?x(r,this).index(u)>=0:x.find(r,this,null,[u]).length),o[r]&&o.push(i);o.length&&s.push({elem:u,handlers:o})}return n.length>l&&s.push({elem:this,handlers:n.slice(l)}),s},fix:function(e){if(e[x.expando])return e;var t,n,r,i=e.type,o=e,s=this.fixHooks[i];s||(this.fixHooks[i]=s=tt.test(i)?this.mouseHooks:et.test(i)?this.keyHooks:{}),r=s.props?this.props.concat(s.props):this.props,e=new x.Event(o),t=r.length;while(t--)n=r[t],e[n]=o[n];return e.target||(e.target=o.srcElement||a),3===e.target.nodeType&&(e.target=e.target.parentNode),e.metaKey=!!e.metaKey,s.filter?s.filter(e,o):e},props:"altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(e,t){return null==e.which&&(e.which=null!=t.charCode?t.charCode:t.keyCode),e}},mouseHooks:{props:"button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(e,n){var r,i,o,s=n.button,l=n.fromElement;return null==e.pageX&&null!=n.clientX&&(i=e.target.ownerDocument||a,o=i.documentElement,r=i.body,e.pageX=n.clientX+(o&&o.scrollLeft||r&&r.scrollLeft||0)-(o&&o.clientLeft||r&&r.clientLeft||0),e.pageY=n.clientY+(o&&o.scrollTop||r&&r.scrollTop||0)-(o&&o.clientTop||r&&r.clientTop||0)),!e.relatedTarget&&l&&(e.relatedTarget=l===e.target?n.toElement:l),e.which||s===t||(e.which=1&s?1:2&s?3:4&s?2:0),e}},special:{load:{noBubble:!0},focus:{trigger:function(){if(this!==at()&&this.focus)try{return this.focus(),!1}catch(e){}},delegateType:"focusin"},blur:{trigger:function(){return this===at()&&this.blur?(this.blur(),!1):t},delegateType:"focusout"},click:{trigger:function(){return x.nodeName(this,"input")&&"checkbox"===this.type&&this.click?(this.click(),!1):t},_default:function(e){return x.nodeName(e.target,"a")}},beforeunload:{postDispatch:function(e){e.result!==t&&(e.originalEvent.returnValue=e.result)}}},simulate:function(e,t,n,r){var i=x.extend(new x.Event,n,{type:e,isSimulated:!0,originalEvent:{}});r?x.event.trigger(i,null,t):x.event.dispatch.call(t,i),i.isDefaultPrevented()&&n.preventDefault()}},x.removeEvent=a.removeEventListener?function(e,t,n){e.removeEventListener&&e.removeEventListener(t,n,!1)}:function(e,t,n){var r="on"+t;e.detachEvent&&(typeof e[r]===i&&(e[r]=null),e.detachEvent(r,n))},x.Event=function(e,n){return this instanceof x.Event?(e&&e.type?(this.originalEvent=e,this.type=e.type,this.isDefaultPrevented=e.defaultPrevented||e.returnValue===!1||e.getPreventDefault&&e.getPreventDefault()?it:ot):this.type=e,n&&x.extend(this,n),this.timeStamp=e&&e.timeStamp||x.now(),this[x.expando]=!0,t):new x.Event(e,n)},x.Event.prototype={isDefaultPrevented:ot,isPropagationStopped:ot,isImmediatePropagationStopped:ot,preventDefault:function(){var e=this.originalEvent;this.isDefaultPrevented=it,e&&(e.preventDefault?e.preventDefault():e.returnValue=!1)},stopPropagation:function(){var e=this.originalEvent;this.isPropagationStopped=it,e&&(e.stopPropagation&&e.stopPropagation(),e.cancelBubble=!0)},stopImmediatePropagation:function(){this.isImmediatePropagationStopped=it,this.stopPropagation()}},x.each({mouseenter:"mouseover",mouseleave:"mouseout"},function(e,t){x.event.special[e]={delegateType:t,bindType:t,handle:function(e){var n,r=this,i=e.relatedTarget,o=e.handleObj;return(!i||i!==r&&!x.contains(r,i))&&(e.type=o.origType,n=o.handler.apply(this,arguments),e.type=t),n}}}),x.support.submitBubbles||(x.event.special.submit={setup:function(){return x.nodeName(this,"form")?!1:(x.event.add(this,"click._submit keypress._submit",function(e){var n=e.target,r=x.nodeName(n,"input")||x.nodeName(n,"button")?n.form:t;r&&!x._data(r,"submitBubbles")&&(x.event.add(r,"submit._submit",function(e){e._submit_bubble=!0}),x._data(r,"submitBubbles",!0))}),t)},postDispatch:function(e){e._submit_bubble&&(delete e._submit_bubble,this.parentNode&&!e.isTrigger&&x.event.simulate("submit",this.parentNode,e,!0))},teardown:function(){return x.nodeName(this,"form")?!1:(x.event.remove(this,"._submit"),t)}}),x.support.changeBubbles||(x.event.special.change={setup:function(){return Z.test(this.nodeName)?(("checkbox"===this.type||"radio"===this.type)&&(x.event.add(this,"propertychange._change",function(e){"checked"===e.originalEvent.propertyName&&(this._just_changed=!0)}),x.event.add(this,"click._change",function(e){this._just_changed&&!e.isTrigger&&(this._just_changed=!1),x.event.simulate("change",this,e,!0)})),!1):(x.event.add(this,"beforeactivate._change",function(e){var t=e.target;Z.test(t.nodeName)&&!x._data(t,"changeBubbles")&&(x.event.add(t,"change._change",function(e){!this.parentNode||e.isSimulated||e.isTrigger||x.event.simulate("change",this.parentNode,e,!0)}),x._data(t,"changeBubbles",!0))}),t)},handle:function(e){var n=e.target;return this!==n||e.isSimulated||e.isTrigger||"radio"!==n.type&&"checkbox"!==n.type?e.handleObj.handler.apply(this,arguments):t},teardown:function(){return x.event.remove(this,"._change"),!Z.test(this.nodeName)}}),x.support.focusinBubbles||x.each({focus:"focusin",blur:"focusout"},function(e,t){var n=0,r=function(e){x.event.simulate(t,e.target,x.event.fix(e),!0)};x.event.special[t]={setup:function(){0===n++&&a.addEventListener(e,r,!0)},teardown:function(){0===--n&&a.removeEventListener(e,r,!0)}}}),x.fn.extend({on:function(e,n,r,i,o){var a,s;if("object"==typeof e){"string"!=typeof n&&(r=r||n,n=t);for(a in e)this.on(a,n,r,e[a],o);return this}if(null==r&&null==i?(i=n,r=n=t):null==i&&("string"==typeof n?(i=r,r=t):(i=r,r=n,n=t)),i===!1)i=ot;else if(!i)return this;return 1===o&&(s=i,i=function(e){return x().off(e),s.apply(this,arguments)},i.guid=s.guid||(s.guid=x.guid++)),this.each(function(){x.event.add(this,e,i,r,n)})},one:function(e,t,n,r){return this.on(e,t,n,r,1)},off:function(e,n,r){var i,o;if(e&&e.preventDefault&&e.handleObj)return i=e.handleObj,x(e.delegateTarget).off(i.namespace?i.origType+"."+i.namespace:i.origType,i.selector,i.handler),this;if("object"==typeof e){for(o in e)this.off(o,n,e[o]);return this}return(n===!1||"function"==typeof n)&&(r=n,n=t),r===!1&&(r=ot),this.each(function(){x.event.remove(this,e,r,n)})},trigger:function(e,t){return this.each(function(){x.event.trigger(e,t,this)})},triggerHandler:function(e,n){var r=this[0];return r?x.event.trigger(e,n,r,!0):t}});var st=/^.[^:#\[\.,]*$/,lt=/^(?:parents|prev(?:Until|All))/,ut=x.expr.match.needsContext,ct={children:!0,contents:!0,next:!0,prev:!0};x.fn.extend({find:function(e){var t,n=[],r=this,i=r.length;if("string"!=typeof e)return this.pushStack(x(e).filter(function(){for(t=0;i>t;t++)if(x.contains(r[t],this))return!0}));for(t=0;i>t;t++)x.find(e,r[t],n);return n=this.pushStack(i>1?x.unique(n):n),n.selector=this.selector?this.selector+" "+e:e,n},has:function(e){var t,n=x(e,this),r=n.length;return this.filter(function(){for(t=0;r>t;t++)if(x.contains(this,n[t]))return!0})},not:function(e){return this.pushStack(ft(this,e||[],!0))},filter:function(e){return this.pushStack(ft(this,e||[],!1))},is:function(e){return!!ft(this,"string"==typeof e&&ut.test(e)?x(e):e||[],!1).length},closest:function(e,t){var n,r=0,i=this.length,o=[],a=ut.test(e)||"string"!=typeof e?x(e,t||this.context):0;for(;i>r;r++)for(n=this[r];n&&n!==t;n=n.parentNode)if(11>n.nodeType&&(a?a.index(n)>-1:1===n.nodeType&&x.find.matchesSelector(n,e))){n=o.push(n);break}return this.pushStack(o.length>1?x.unique(o):o)},index:function(e){return e?"string"==typeof e?x.inArray(this[0],x(e)):x.inArray(e.jquery?e[0]:e,this):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(e,t){var n="string"==typeof e?x(e,t):x.makeArray(e&&e.nodeType?[e]:e),r=x.merge(this.get(),n);return this.pushStack(x.unique(r))},addBack:function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}});function pt(e,t){do e=e[t];while(e&&1!==e.nodeType);return e}x.each({parent:function(e){var t=e.parentNode;return t&&11!==t.nodeType?t:null},parents:function(e){return x.dir(e,"parentNode")},parentsUntil:function(e,t,n){return x.dir(e,"parentNode",n)},next:function(e){return pt(e,"nextSibling")},prev:function(e){return pt(e,"previousSibling")},nextAll:function(e){return x.dir(e,"nextSibling")},prevAll:function(e){return x.dir(e,"previousSibling")},nextUntil:function(e,t,n){return x.dir(e,"nextSibling",n)},prevUntil:function(e,t,n){return x.dir(e,"previousSibling",n)},siblings:function(e){return x.sibling((e.parentNode||{}).firstChild,e)},children:function(e){return x.sibling(e.firstChild)},contents:function(e){return x.nodeName(e,"iframe")?e.contentDocument||e.contentWindow.document:x.merge([],e.childNodes)}},function(e,t){x.fn[e]=function(n,r){var i=x.map(this,t,n);return"Until"!==e.slice(-5)&&(r=n),r&&"string"==typeof r&&(i=x.filter(r,i)),this.length>1&&(ct[e]||(i=x.unique(i)),lt.test(e)&&(i=i.reverse())),this.pushStack(i)}}),x.extend({filter:function(e,t,n){var r=t[0];return n&&(e=":not("+e+")"),1===t.length&&1===r.nodeType?x.find.matchesSelector(r,e)?[r]:[]:x.find.matches(e,x.grep(t,function(e){return 1===e.nodeType}))},dir:function(e,n,r){var i=[],o=e[n];while(o&&9!==o.nodeType&&(r===t||1!==o.nodeType||!x(o).is(r)))1===o.nodeType&&i.push(o),o=o[n];return i},sibling:function(e,t){var n=[];for(;e;e=e.nextSibling)1===e.nodeType&&e!==t&&n.push(e);return n}});function ft(e,t,n){if(x.isFunction(t))return x.grep(e,function(e,r){return!!t.call(e,r,e)!==n});if(t.nodeType)return x.grep(e,function(e){return e===t!==n});if("string"==typeof t){if(st.test(t))return x.filter(t,e,n);t=x.filter(t,e)}return x.grep(e,function(e){return x.inArray(e,t)>=0!==n})}function dt(e){var t=ht.split("|"),n=e.createDocumentFragment();if(n.createElement)while(t.length)n.createElement(t.pop());return n}var ht="abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",gt=/ jQuery\d+="(?:null|\d+)"/g,mt=RegExp("<(?:"+ht+")[\\s/>]","i"),yt=/^\s+/,vt=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,bt=/<([\w:]+)/,xt=/<tbody/i,wt=/<|&#?\w+;/,Tt=/<(?:script|style|link)/i,Ct=/^(?:checkbox|radio)$/i,Nt=/checked\s*(?:[^=]|=\s*.checked.)/i,kt=/^$|\/(?:java|ecma)script/i,Et=/^true\/(.*)/,St=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,At={option:[1,"<select multiple='multiple'>","</select>"],legend:[1,"<fieldset>","</fieldset>"],area:[1,"<map>","</map>"],param:[1,"<object>","</object>"],thead:[1,"<table>","</table>"],tr:[2,"<table><tbody>","</tbody></table>"],col:[2,"<table><tbody></tbody><colgroup>","</colgroup></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:x.support.htmlSerialize?[0,"",""]:[1,"X<div>","</div>"]},jt=dt(a),Dt=jt.appendChild(a.createElement("div"));At.optgroup=At.option,At.tbody=At.tfoot=At.colgroup=At.caption=At.thead,At.th=At.td,x.fn.extend({text:function(e){return x.access(this,function(e){return e===t?x.text(this):this.empty().append((this[0]&&this[0].ownerDocument||a).createTextNode(e))},null,e,arguments.length)},append:function(){return this.domManip(arguments,function(e){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var t=Lt(this,e);t.appendChild(e)}})},prepend:function(){return this.domManip(arguments,function(e){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var t=Lt(this,e);t.insertBefore(e,t.firstChild)}})},before:function(){return this.domManip(arguments,function(e){this.parentNode&&this.parentNode.insertBefore(e,this)})},after:function(){return this.domManip(arguments,function(e){this.parentNode&&this.parentNode.insertBefore(e,this.nextSibling)})},remove:function(e,t){var n,r=e?x.filter(e,this):this,i=0;for(;null!=(n=r[i]);i++)t||1!==n.nodeType||x.cleanData(Ft(n)),n.parentNode&&(t&&x.contains(n.ownerDocument,n)&&_t(Ft(n,"script")),n.parentNode.removeChild(n));return this},empty:function(){var e,t=0;for(;null!=(e=this[t]);t++){1===e.nodeType&&x.cleanData(Ft(e,!1));while(e.firstChild)e.removeChild(e.firstChild);e.options&&x.nodeName(e,"select")&&(e.options.length=0)}return this},clone:function(e,t){return e=null==e?!1:e,t=null==t?e:t,this.map(function(){return x.clone(this,e,t)})},html:function(e){return x.access(this,function(e){var n=this[0]||{},r=0,i=this.length;if(e===t)return 1===n.nodeType?n.innerHTML.replace(gt,""):t;if(!("string"!=typeof e||Tt.test(e)||!x.support.htmlSerialize&&mt.test(e)||!x.support.leadingWhitespace&&yt.test(e)||At[(bt.exec(e)||["",""])[1].toLowerCase()])){e=e.replace(vt,"<$1></$2>");try{for(;i>r;r++)n=this[r]||{},1===n.nodeType&&(x.cleanData(Ft(n,!1)),n.innerHTML=e);n=0}catch(o){}}n&&this.empty().append(e)},null,e,arguments.length)},replaceWith:function(){var e=x.map(this,function(e){return[e.nextSibling,e.parentNode]}),t=0;return this.domManip(arguments,function(n){var r=e[t++],i=e[t++];i&&(r&&r.parentNode!==i&&(r=this.nextSibling),x(this).remove(),i.insertBefore(n,r))},!0),t?this:this.remove()},detach:function(e){return this.remove(e,!0)},domManip:function(e,t,n){e=d.apply([],e);var r,i,o,a,s,l,u=0,c=this.length,p=this,f=c-1,h=e[0],g=x.isFunction(h);if(g||!(1>=c||"string"!=typeof h||x.support.checkClone)&&Nt.test(h))return this.each(function(r){var i=p.eq(r);g&&(e[0]=h.call(this,r,i.html())),i.domManip(e,t,n)});if(c&&(l=x.buildFragment(e,this[0].ownerDocument,!1,!n&&this),r=l.firstChild,1===l.childNodes.length&&(l=r),r)){for(a=x.map(Ft(l,"script"),Ht),o=a.length;c>u;u++)i=l,u!==f&&(i=x.clone(i,!0,!0),o&&x.merge(a,Ft(i,"script"))),t.call(this[u],i,u);if(o)for(s=a[a.length-1].ownerDocument,x.map(a,qt),u=0;o>u;u++)i=a[u],kt.test(i.type||"")&&!x._data(i,"globalEval")&&x.contains(s,i)&&(i.src?x._evalUrl(i.src):x.globalEval((i.text||i.textContent||i.innerHTML||"").replace(St,"")));l=r=null}return this}});function Lt(e,t){return x.nodeName(e,"table")&&x.nodeName(1===t.nodeType?t:t.firstChild,"tr")?e.getElementsByTagName("tbody")[0]||e.appendChild(e.ownerDocument.createElement("tbody")):e}function Ht(e){return e.type=(null!==x.find.attr(e,"type"))+"/"+e.type,e}function qt(e){var t=Et.exec(e.type);return t?e.type=t[1]:e.removeAttribute("type"),e}function _t(e,t){var n,r=0;for(;null!=(n=e[r]);r++)x._data(n,"globalEval",!t||x._data(t[r],"globalEval"))}function Mt(e,t){if(1===t.nodeType&&x.hasData(e)){var n,r,i,o=x._data(e),a=x._data(t,o),s=o.events;if(s){delete a.handle,a.events={};for(n in s)for(r=0,i=s[n].length;i>r;r++)x.event.add(t,n,s[n][r])}a.data&&(a.data=x.extend({},a.data))}}function Ot(e,t){var n,r,i;if(1===t.nodeType){if(n=t.nodeName.toLowerCase(),!x.support.noCloneEvent&&t[x.expando]){i=x._data(t);for(r in i.events)x.removeEvent(t,r,i.handle);t.removeAttribute(x.expando)}"script"===n&&t.text!==e.text?(Ht(t).text=e.text,qt(t)):"object"===n?(t.parentNode&&(t.outerHTML=e.outerHTML),x.support.html5Clone&&e.innerHTML&&!x.trim(t.innerHTML)&&(t.innerHTML=e.innerHTML)):"input"===n&&Ct.test(e.type)?(t.defaultChecked=t.checked=e.checked,t.value!==e.value&&(t.value=e.value)):"option"===n?t.defaultSelected=t.selected=e.defaultSelected:("input"===n||"textarea"===n)&&(t.defaultValue=e.defaultValue)}}x.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(e,t){x.fn[e]=function(e){var n,r=0,i=[],o=x(e),a=o.length-1;for(;a>=r;r++)n=r===a?this:this.clone(!0),x(o[r])[t](n),h.apply(i,n.get());return this.pushStack(i)}});function Ft(e,n){var r,o,a=0,s=typeof e.getElementsByTagName!==i?e.getElementsByTagName(n||"*"):typeof e.querySelectorAll!==i?e.querySelectorAll(n||"*"):t;if(!s)for(s=[],r=e.childNodes||e;null!=(o=r[a]);a++)!n||x.nodeName(o,n)?s.push(o):x.merge(s,Ft(o,n));return n===t||n&&x.nodeName(e,n)?x.merge([e],s):s}function Bt(e){Ct.test(e.type)&&(e.defaultChecked=e.checked)}x.extend({clone:function(e,t,n){var r,i,o,a,s,l=x.contains(e.ownerDocument,e);if(x.support.html5Clone||x.isXMLDoc(e)||!mt.test("<"+e.nodeName+">")?o=e.cloneNode(!0):(Dt.innerHTML=e.outerHTML,Dt.removeChild(o=Dt.firstChild)),!(x.support.noCloneEvent&&x.support.noCloneChecked||1!==e.nodeType&&11!==e.nodeType||x.isXMLDoc(e)))for(r=Ft(o),s=Ft(e),a=0;null!=(i=s[a]);++a)r[a]&&Ot(i,r[a]);if(t)if(n)for(s=s||Ft(e),r=r||Ft(o),a=0;null!=(i=s[a]);a++)Mt(i,r[a]);else Mt(e,o);return r=Ft(o,"script"),r.length>0&&_t(r,!l&&Ft(e,"script")),r=s=i=null,o},buildFragment:function(e,t,n,r){var i,o,a,s,l,u,c,p=e.length,f=dt(t),d=[],h=0;for(;p>h;h++)if(o=e[h],o||0===o)if("object"===x.type(o))x.merge(d,o.nodeType?[o]:o);else if(wt.test(o)){s=s||f.appendChild(t.createElement("div")),l=(bt.exec(o)||["",""])[1].toLowerCase(),c=At[l]||At._default,s.innerHTML=c[1]+o.replace(vt,"<$1></$2>")+c[2],i=c[0];while(i--)s=s.lastChild;if(!x.support.leadingWhitespace&&yt.test(o)&&d.push(t.createTextNode(yt.exec(o)[0])),!x.support.tbody){o="table"!==l||xt.test(o)?"<table>"!==c[1]||xt.test(o)?0:s:s.firstChild,i=o&&o.childNodes.length;while(i--)x.nodeName(u=o.childNodes[i],"tbody")&&!u.childNodes.length&&o.removeChild(u)}x.merge(d,s.childNodes),s.textContent="";while(s.firstChild)s.removeChild(s.firstChild);s=f.lastChild}else d.push(t.createTextNode(o));s&&f.removeChild(s),x.support.appendChecked||x.grep(Ft(d,"input"),Bt),h=0;while(o=d[h++])if((!r||-1===x.inArray(o,r))&&(a=x.contains(o.ownerDocument,o),s=Ft(f.appendChild(o),"script"),a&&_t(s),n)){i=0;while(o=s[i++])kt.test(o.type||"")&&n.push(o)}return s=null,f},cleanData:function(e,t){var n,r,o,a,s=0,l=x.expando,u=x.cache,c=x.support.deleteExpando,f=x.event.special;for(;null!=(n=e[s]);s++)if((t||x.acceptData(n))&&(o=n[l],a=o&&u[o])){if(a.events)for(r in a.events)f[r]?x.event.remove(n,r):x.removeEvent(n,r,a.handle);
u[o]&&(delete u[o],c?delete n[l]:typeof n.removeAttribute!==i?n.removeAttribute(l):n[l]=null,p.push(o))}},_evalUrl:function(e){return x.ajax({url:e,type:"GET",dataType:"script",async:!1,global:!1,"throws":!0})}}),x.fn.extend({wrapAll:function(e){if(x.isFunction(e))return this.each(function(t){x(this).wrapAll(e.call(this,t))});if(this[0]){var t=x(e,this[0].ownerDocument).eq(0).clone(!0);this[0].parentNode&&t.insertBefore(this[0]),t.map(function(){var e=this;while(e.firstChild&&1===e.firstChild.nodeType)e=e.firstChild;return e}).append(this)}return this},wrapInner:function(e){return x.isFunction(e)?this.each(function(t){x(this).wrapInner(e.call(this,t))}):this.each(function(){var t=x(this),n=t.contents();n.length?n.wrapAll(e):t.append(e)})},wrap:function(e){var t=x.isFunction(e);return this.each(function(n){x(this).wrapAll(t?e.call(this,n):e)})},unwrap:function(){return this.parent().each(function(){x.nodeName(this,"body")||x(this).replaceWith(this.childNodes)}).end()}});var Pt,Rt,Wt,$t=/alpha\([^)]*\)/i,It=/opacity\s*=\s*([^)]*)/,zt=/^(top|right|bottom|left)$/,Xt=/^(none|table(?!-c[ea]).+)/,Ut=/^margin/,Vt=RegExp("^("+w+")(.*)$","i"),Yt=RegExp("^("+w+")(?!px)[a-z%]+$","i"),Jt=RegExp("^([+-])=("+w+")","i"),Gt={BODY:"block"},Qt={position:"absolute",visibility:"hidden",display:"block"},Kt={letterSpacing:0,fontWeight:400},Zt=["Top","Right","Bottom","Left"],en=["Webkit","O","Moz","ms"];function tn(e,t){if(t in e)return t;var n=t.charAt(0).toUpperCase()+t.slice(1),r=t,i=en.length;while(i--)if(t=en[i]+n,t in e)return t;return r}function nn(e,t){return e=t||e,"none"===x.css(e,"display")||!x.contains(e.ownerDocument,e)}function rn(e,t){var n,r,i,o=[],a=0,s=e.length;for(;s>a;a++)r=e[a],r.style&&(o[a]=x._data(r,"olddisplay"),n=r.style.display,t?(o[a]||"none"!==n||(r.style.display=""),""===r.style.display&&nn(r)&&(o[a]=x._data(r,"olddisplay",ln(r.nodeName)))):o[a]||(i=nn(r),(n&&"none"!==n||!i)&&x._data(r,"olddisplay",i?n:x.css(r,"display"))));for(a=0;s>a;a++)r=e[a],r.style&&(t&&"none"!==r.style.display&&""!==r.style.display||(r.style.display=t?o[a]||"":"none"));return e}x.fn.extend({css:function(e,n){return x.access(this,function(e,n,r){var i,o,a={},s=0;if(x.isArray(n)){for(o=Rt(e),i=n.length;i>s;s++)a[n[s]]=x.css(e,n[s],!1,o);return a}return r!==t?x.style(e,n,r):x.css(e,n)},e,n,arguments.length>1)},show:function(){return rn(this,!0)},hide:function(){return rn(this)},toggle:function(e){return"boolean"==typeof e?e?this.show():this.hide():this.each(function(){nn(this)?x(this).show():x(this).hide()})}}),x.extend({cssHooks:{opacity:{get:function(e,t){if(t){var n=Wt(e,"opacity");return""===n?"1":n}}}},cssNumber:{columnCount:!0,fillOpacity:!0,fontWeight:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":x.support.cssFloat?"cssFloat":"styleFloat"},style:function(e,n,r,i){if(e&&3!==e.nodeType&&8!==e.nodeType&&e.style){var o,a,s,l=x.camelCase(n),u=e.style;if(n=x.cssProps[l]||(x.cssProps[l]=tn(u,l)),s=x.cssHooks[n]||x.cssHooks[l],r===t)return s&&"get"in s&&(o=s.get(e,!1,i))!==t?o:u[n];if(a=typeof r,"string"===a&&(o=Jt.exec(r))&&(r=(o[1]+1)*o[2]+parseFloat(x.css(e,n)),a="number"),!(null==r||"number"===a&&isNaN(r)||("number"!==a||x.cssNumber[l]||(r+="px"),x.support.clearCloneStyle||""!==r||0!==n.indexOf("background")||(u[n]="inherit"),s&&"set"in s&&(r=s.set(e,r,i))===t)))try{u[n]=r}catch(c){}}},css:function(e,n,r,i){var o,a,s,l=x.camelCase(n);return n=x.cssProps[l]||(x.cssProps[l]=tn(e.style,l)),s=x.cssHooks[n]||x.cssHooks[l],s&&"get"in s&&(a=s.get(e,!0,r)),a===t&&(a=Wt(e,n,i)),"normal"===a&&n in Kt&&(a=Kt[n]),""===r||r?(o=parseFloat(a),r===!0||x.isNumeric(o)?o||0:a):a}}),e.getComputedStyle?(Rt=function(t){return e.getComputedStyle(t,null)},Wt=function(e,n,r){var i,o,a,s=r||Rt(e),l=s?s.getPropertyValue(n)||s[n]:t,u=e.style;return s&&(""!==l||x.contains(e.ownerDocument,e)||(l=x.style(e,n)),Yt.test(l)&&Ut.test(n)&&(i=u.width,o=u.minWidth,a=u.maxWidth,u.minWidth=u.maxWidth=u.width=l,l=s.width,u.width=i,u.minWidth=o,u.maxWidth=a)),l}):a.documentElement.currentStyle&&(Rt=function(e){return e.currentStyle},Wt=function(e,n,r){var i,o,a,s=r||Rt(e),l=s?s[n]:t,u=e.style;return null==l&&u&&u[n]&&(l=u[n]),Yt.test(l)&&!zt.test(n)&&(i=u.left,o=e.runtimeStyle,a=o&&o.left,a&&(o.left=e.currentStyle.left),u.left="fontSize"===n?"1em":l,l=u.pixelLeft+"px",u.left=i,a&&(o.left=a)),""===l?"auto":l});function on(e,t,n){var r=Vt.exec(t);return r?Math.max(0,r[1]-(n||0))+(r[2]||"px"):t}function an(e,t,n,r,i){var o=n===(r?"border":"content")?4:"width"===t?1:0,a=0;for(;4>o;o+=2)"margin"===n&&(a+=x.css(e,n+Zt[o],!0,i)),r?("content"===n&&(a-=x.css(e,"padding"+Zt[o],!0,i)),"margin"!==n&&(a-=x.css(e,"border"+Zt[o]+"Width",!0,i))):(a+=x.css(e,"padding"+Zt[o],!0,i),"padding"!==n&&(a+=x.css(e,"border"+Zt[o]+"Width",!0,i)));return a}function sn(e,t,n){var r=!0,i="width"===t?e.offsetWidth:e.offsetHeight,o=Rt(e),a=x.support.boxSizing&&"border-box"===x.css(e,"boxSizing",!1,o);if(0>=i||null==i){if(i=Wt(e,t,o),(0>i||null==i)&&(i=e.style[t]),Yt.test(i))return i;r=a&&(x.support.boxSizingReliable||i===e.style[t]),i=parseFloat(i)||0}return i+an(e,t,n||(a?"border":"content"),r,o)+"px"}function ln(e){var t=a,n=Gt[e];return n||(n=un(e,t),"none"!==n&&n||(Pt=(Pt||x("<iframe frameborder='0' width='0' height='0'/>").css("cssText","display:block !important")).appendTo(t.documentElement),t=(Pt[0].contentWindow||Pt[0].contentDocument).document,t.write("<!doctype html><html><body>"),t.close(),n=un(e,t),Pt.detach()),Gt[e]=n),n}function un(e,t){var n=x(t.createElement(e)).appendTo(t.body),r=x.css(n[0],"display");return n.remove(),r}x.each(["height","width"],function(e,n){x.cssHooks[n]={get:function(e,r,i){return r?0===e.offsetWidth&&Xt.test(x.css(e,"display"))?x.swap(e,Qt,function(){return sn(e,n,i)}):sn(e,n,i):t},set:function(e,t,r){var i=r&&Rt(e);return on(e,t,r?an(e,n,r,x.support.boxSizing&&"border-box"===x.css(e,"boxSizing",!1,i),i):0)}}}),x.support.opacity||(x.cssHooks.opacity={get:function(e,t){return It.test((t&&e.currentStyle?e.currentStyle.filter:e.style.filter)||"")?.01*parseFloat(RegExp.$1)+"":t?"1":""},set:function(e,t){var n=e.style,r=e.currentStyle,i=x.isNumeric(t)?"alpha(opacity="+100*t+")":"",o=r&&r.filter||n.filter||"";n.zoom=1,(t>=1||""===t)&&""===x.trim(o.replace($t,""))&&n.removeAttribute&&(n.removeAttribute("filter"),""===t||r&&!r.filter)||(n.filter=$t.test(o)?o.replace($t,i):o+" "+i)}}),x(function(){x.support.reliableMarginRight||(x.cssHooks.marginRight={get:function(e,n){return n?x.swap(e,{display:"inline-block"},Wt,[e,"marginRight"]):t}}),!x.support.pixelPosition&&x.fn.position&&x.each(["top","left"],function(e,n){x.cssHooks[n]={get:function(e,r){return r?(r=Wt(e,n),Yt.test(r)?x(e).position()[n]+"px":r):t}}})}),x.expr&&x.expr.filters&&(x.expr.filters.hidden=function(e){return 0>=e.offsetWidth&&0>=e.offsetHeight||!x.support.reliableHiddenOffsets&&"none"===(e.style&&e.style.display||x.css(e,"display"))},x.expr.filters.visible=function(e){return!x.expr.filters.hidden(e)}),x.each({margin:"",padding:"",border:"Width"},function(e,t){x.cssHooks[e+t]={expand:function(n){var r=0,i={},o="string"==typeof n?n.split(" "):[n];for(;4>r;r++)i[e+Zt[r]+t]=o[r]||o[r-2]||o[0];return i}},Ut.test(e)||(x.cssHooks[e+t].set=on)});var cn=/%20/g,pn=/\[\]$/,fn=/\r?\n/g,dn=/^(?:submit|button|image|reset|file)$/i,hn=/^(?:input|select|textarea|keygen)/i;x.fn.extend({serialize:function(){return x.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var e=x.prop(this,"elements");return e?x.makeArray(e):this}).filter(function(){var e=this.type;return this.name&&!x(this).is(":disabled")&&hn.test(this.nodeName)&&!dn.test(e)&&(this.checked||!Ct.test(e))}).map(function(e,t){var n=x(this).val();return null==n?null:x.isArray(n)?x.map(n,function(e){return{name:t.name,value:e.replace(fn,"\r\n")}}):{name:t.name,value:n.replace(fn,"\r\n")}}).get()}}),x.param=function(e,n){var r,i=[],o=function(e,t){t=x.isFunction(t)?t():null==t?"":t,i[i.length]=encodeURIComponent(e)+"="+encodeURIComponent(t)};if(n===t&&(n=x.ajaxSettings&&x.ajaxSettings.traditional),x.isArray(e)||e.jquery&&!x.isPlainObject(e))x.each(e,function(){o(this.name,this.value)});else for(r in e)gn(r,e[r],n,o);return i.join("&").replace(cn,"+")};function gn(e,t,n,r){var i;if(x.isArray(t))x.each(t,function(t,i){n||pn.test(e)?r(e,i):gn(e+"["+("object"==typeof i?t:"")+"]",i,n,r)});else if(n||"object"!==x.type(t))r(e,t);else for(i in t)gn(e+"["+i+"]",t[i],n,r)}x.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(e,t){x.fn[t]=function(e,n){return arguments.length>0?this.on(t,null,e,n):this.trigger(t)}}),x.fn.extend({hover:function(e,t){return this.mouseenter(e).mouseleave(t||e)},bind:function(e,t,n){return this.on(e,null,t,n)},unbind:function(e,t){return this.off(e,null,t)},delegate:function(e,t,n,r){return this.on(t,e,n,r)},undelegate:function(e,t,n){return 1===arguments.length?this.off(e,"**"):this.off(t,e||"**",n)}});var mn,yn,vn=x.now(),bn=/\?/,xn=/#.*$/,wn=/([?&])_=[^&]*/,Tn=/^(.*?):[ \t]*([^\r\n]*)\r?$/gm,Cn=/^(?:about|app|app-storage|.+-extension|file|res|widget):$/,Nn=/^(?:GET|HEAD)$/,kn=/^\/\//,En=/^([\w.+-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/,Sn=x.fn.load,An={},jn={},Dn="*/".concat("*");try{yn=o.href}catch(Ln){yn=a.createElement("a"),yn.href="",yn=yn.href}mn=En.exec(yn.toLowerCase())||[];function Hn(e){return function(t,n){"string"!=typeof t&&(n=t,t="*");var r,i=0,o=t.toLowerCase().match(T)||[];if(x.isFunction(n))while(r=o[i++])"+"===r[0]?(r=r.slice(1)||"*",(e[r]=e[r]||[]).unshift(n)):(e[r]=e[r]||[]).push(n)}}function qn(e,n,r,i){var o={},a=e===jn;function s(l){var u;return o[l]=!0,x.each(e[l]||[],function(e,l){var c=l(n,r,i);return"string"!=typeof c||a||o[c]?a?!(u=c):t:(n.dataTypes.unshift(c),s(c),!1)}),u}return s(n.dataTypes[0])||!o["*"]&&s("*")}function _n(e,n){var r,i,o=x.ajaxSettings.flatOptions||{};for(i in n)n[i]!==t&&((o[i]?e:r||(r={}))[i]=n[i]);return r&&x.extend(!0,e,r),e}x.fn.load=function(e,n,r){if("string"!=typeof e&&Sn)return Sn.apply(this,arguments);var i,o,a,s=this,l=e.indexOf(" ");return l>=0&&(i=e.slice(l,e.length),e=e.slice(0,l)),x.isFunction(n)?(r=n,n=t):n&&"object"==typeof n&&(a="POST"),s.length>0&&x.ajax({url:e,type:a,dataType:"html",data:n}).done(function(e){o=arguments,s.html(i?x("<div>").append(x.parseHTML(e)).find(i):e)}).complete(r&&function(e,t){s.each(r,o||[e.responseText,t,e])}),this},x.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(e,t){x.fn[t]=function(e){return this.on(t,e)}}),x.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:yn,type:"GET",isLocal:Cn.test(mn[1]),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":Dn,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText",json:"responseJSON"},converters:{"* text":String,"text html":!0,"text json":x.parseJSON,"text xml":x.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(e,t){return t?_n(_n(e,x.ajaxSettings),t):_n(x.ajaxSettings,e)},ajaxPrefilter:Hn(An),ajaxTransport:Hn(jn),ajax:function(e,n){"object"==typeof e&&(n=e,e=t),n=n||{};var r,i,o,a,s,l,u,c,p=x.ajaxSetup({},n),f=p.context||p,d=p.context&&(f.nodeType||f.jquery)?x(f):x.event,h=x.Deferred(),g=x.Callbacks("once memory"),m=p.statusCode||{},y={},v={},b=0,w="canceled",C={readyState:0,getResponseHeader:function(e){var t;if(2===b){if(!c){c={};while(t=Tn.exec(a))c[t[1].toLowerCase()]=t[2]}t=c[e.toLowerCase()]}return null==t?null:t},getAllResponseHeaders:function(){return 2===b?a:null},setRequestHeader:function(e,t){var n=e.toLowerCase();return b||(e=v[n]=v[n]||e,y[e]=t),this},overrideMimeType:function(e){return b||(p.mimeType=e),this},statusCode:function(e){var t;if(e)if(2>b)for(t in e)m[t]=[m[t],e[t]];else C.always(e[C.status]);return this},abort:function(e){var t=e||w;return u&&u.abort(t),k(0,t),this}};if(h.promise(C).complete=g.add,C.success=C.done,C.error=C.fail,p.url=((e||p.url||yn)+"").replace(xn,"").replace(kn,mn[1]+"//"),p.type=n.method||n.type||p.method||p.type,p.dataTypes=x.trim(p.dataType||"*").toLowerCase().match(T)||[""],null==p.crossDomain&&(r=En.exec(p.url.toLowerCase()),p.crossDomain=!(!r||r[1]===mn[1]&&r[2]===mn[2]&&(r[3]||("http:"===r[1]?"80":"443"))===(mn[3]||("http:"===mn[1]?"80":"443")))),p.data&&p.processData&&"string"!=typeof p.data&&(p.data=x.param(p.data,p.traditional)),qn(An,p,n,C),2===b)return C;l=p.global,l&&0===x.active++&&x.event.trigger("ajaxStart"),p.type=p.type.toUpperCase(),p.hasContent=!Nn.test(p.type),o=p.url,p.hasContent||(p.data&&(o=p.url+=(bn.test(o)?"&":"?")+p.data,delete p.data),p.cache===!1&&(p.url=wn.test(o)?o.replace(wn,"$1_="+vn++):o+(bn.test(o)?"&":"?")+"_="+vn++)),p.ifModified&&(x.lastModified[o]&&C.setRequestHeader("If-Modified-Since",x.lastModified[o]),x.etag[o]&&C.setRequestHeader("If-None-Match",x.etag[o])),(p.data&&p.hasContent&&p.contentType!==!1||n.contentType)&&C.setRequestHeader("Content-Type",p.contentType),C.setRequestHeader("Accept",p.dataTypes[0]&&p.accepts[p.dataTypes[0]]?p.accepts[p.dataTypes[0]]+("*"!==p.dataTypes[0]?", "+Dn+"; q=0.01":""):p.accepts["*"]);for(i in p.headers)C.setRequestHeader(i,p.headers[i]);if(p.beforeSend&&(p.beforeSend.call(f,C,p)===!1||2===b))return C.abort();w="abort";for(i in{success:1,error:1,complete:1})C[i](p[i]);if(u=qn(jn,p,n,C)){C.readyState=1,l&&d.trigger("ajaxSend",[C,p]),p.async&&p.timeout>0&&(s=setTimeout(function(){C.abort("timeout")},p.timeout));try{b=1,u.send(y,k)}catch(N){if(!(2>b))throw N;k(-1,N)}}else k(-1,"No Transport");function k(e,n,r,i){var c,y,v,w,T,N=n;2!==b&&(b=2,s&&clearTimeout(s),u=t,a=i||"",C.readyState=e>0?4:0,c=e>=200&&300>e||304===e,r&&(w=Mn(p,C,r)),w=On(p,w,C,c),c?(p.ifModified&&(T=C.getResponseHeader("Last-Modified"),T&&(x.lastModified[o]=T),T=C.getResponseHeader("etag"),T&&(x.etag[o]=T)),204===e||"HEAD"===p.type?N="nocontent":304===e?N="notmodified":(N=w.state,y=w.data,v=w.error,c=!v)):(v=N,(e||!N)&&(N="error",0>e&&(e=0))),C.status=e,C.statusText=(n||N)+"",c?h.resolveWith(f,[y,N,C]):h.rejectWith(f,[C,N,v]),C.statusCode(m),m=t,l&&d.trigger(c?"ajaxSuccess":"ajaxError",[C,p,c?y:v]),g.fireWith(f,[C,N]),l&&(d.trigger("ajaxComplete",[C,p]),--x.active||x.event.trigger("ajaxStop")))}return C},getJSON:function(e,t,n){return x.get(e,t,n,"json")},getScript:function(e,n){return x.get(e,t,n,"script")}}),x.each(["get","post"],function(e,n){x[n]=function(e,r,i,o){return x.isFunction(r)&&(o=o||i,i=r,r=t),x.ajax({url:e,type:n,dataType:o,data:r,success:i})}});function Mn(e,n,r){var i,o,a,s,l=e.contents,u=e.dataTypes;while("*"===u[0])u.shift(),o===t&&(o=e.mimeType||n.getResponseHeader("Content-Type"));if(o)for(s in l)if(l[s]&&l[s].test(o)){u.unshift(s);break}if(u[0]in r)a=u[0];else{for(s in r){if(!u[0]||e.converters[s+" "+u[0]]){a=s;break}i||(i=s)}a=a||i}return a?(a!==u[0]&&u.unshift(a),r[a]):t}function On(e,t,n,r){var i,o,a,s,l,u={},c=e.dataTypes.slice();if(c[1])for(a in e.converters)u[a.toLowerCase()]=e.converters[a];o=c.shift();while(o)if(e.responseFields[o]&&(n[e.responseFields[o]]=t),!l&&r&&e.dataFilter&&(t=e.dataFilter(t,e.dataType)),l=o,o=c.shift())if("*"===o)o=l;else if("*"!==l&&l!==o){if(a=u[l+" "+o]||u["* "+o],!a)for(i in u)if(s=i.split(" "),s[1]===o&&(a=u[l+" "+s[0]]||u["* "+s[0]])){a===!0?a=u[i]:u[i]!==!0&&(o=s[0],c.unshift(s[1]));break}if(a!==!0)if(a&&e["throws"])t=a(t);else try{t=a(t)}catch(p){return{state:"parsererror",error:a?p:"No conversion from "+l+" to "+o}}}return{state:"success",data:t}}x.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/(?:java|ecma)script/},converters:{"text script":function(e){return x.globalEval(e),e}}}),x.ajaxPrefilter("script",function(e){e.cache===t&&(e.cache=!1),e.crossDomain&&(e.type="GET",e.global=!1)}),x.ajaxTransport("script",function(e){if(e.crossDomain){var n,r=a.head||x("head")[0]||a.documentElement;return{send:function(t,i){n=a.createElement("script"),n.async=!0,e.scriptCharset&&(n.charset=e.scriptCharset),n.src=e.url,n.onload=n.onreadystatechange=function(e,t){(t||!n.readyState||/loaded|complete/.test(n.readyState))&&(n.onload=n.onreadystatechange=null,n.parentNode&&n.parentNode.removeChild(n),n=null,t||i(200,"success"))},r.insertBefore(n,r.firstChild)},abort:function(){n&&n.onload(t,!0)}}}});var Fn=[],Bn=/(=)\?(?=&|$)|\?\?/;x.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var e=Fn.pop()||x.expando+"_"+vn++;return this[e]=!0,e}}),x.ajaxPrefilter("json jsonp",function(n,r,i){var o,a,s,l=n.jsonp!==!1&&(Bn.test(n.url)?"url":"string"==typeof n.data&&!(n.contentType||"").indexOf("application/x-www-form-urlencoded")&&Bn.test(n.data)&&"data");return l||"jsonp"===n.dataTypes[0]?(o=n.jsonpCallback=x.isFunction(n.jsonpCallback)?n.jsonpCallback():n.jsonpCallback,l?n[l]=n[l].replace(Bn,"$1"+o):n.jsonp!==!1&&(n.url+=(bn.test(n.url)?"&":"?")+n.jsonp+"="+o),n.converters["script json"]=function(){return s||x.error(o+" was not called"),s[0]},n.dataTypes[0]="json",a=e[o],e[o]=function(){s=arguments},i.always(function(){e[o]=a,n[o]&&(n.jsonpCallback=r.jsonpCallback,Fn.push(o)),s&&x.isFunction(a)&&a(s[0]),s=a=t}),"script"):t});var Pn,Rn,Wn=0,$n=e.ActiveXObject&&function(){var e;for(e in Pn)Pn[e](t,!0)};function In(){try{return new e.XMLHttpRequest}catch(t){}}function zn(){try{return new e.ActiveXObject("Microsoft.XMLHTTP")}catch(t){}}x.ajaxSettings.xhr=e.ActiveXObject?function(){return!this.isLocal&&In()||zn()}:In,Rn=x.ajaxSettings.xhr(),x.support.cors=!!Rn&&"withCredentials"in Rn,Rn=x.support.ajax=!!Rn,Rn&&x.ajaxTransport(function(n){if(!n.crossDomain||x.support.cors){var r;return{send:function(i,o){var a,s,l=n.xhr();if(n.username?l.open(n.type,n.url,n.async,n.username,n.password):l.open(n.type,n.url,n.async),n.xhrFields)for(s in n.xhrFields)l[s]=n.xhrFields[s];n.mimeType&&l.overrideMimeType&&l.overrideMimeType(n.mimeType),n.crossDomain||i["X-Requested-With"]||(i["X-Requested-With"]="XMLHttpRequest");try{for(s in i)l.setRequestHeader(s,i[s])}catch(u){}l.send(n.hasContent&&n.data||null),r=function(e,i){var s,u,c,p;try{if(r&&(i||4===l.readyState))if(r=t,a&&(l.onreadystatechange=x.noop,$n&&delete Pn[a]),i)4!==l.readyState&&l.abort();else{p={},s=l.status,u=l.getAllResponseHeaders(),"string"==typeof l.responseText&&(p.text=l.responseText);try{c=l.statusText}catch(f){c=""}s||!n.isLocal||n.crossDomain?1223===s&&(s=204):s=p.text?200:404}}catch(d){i||o(-1,d)}p&&o(s,c,p,u)},n.async?4===l.readyState?setTimeout(r):(a=++Wn,$n&&(Pn||(Pn={},x(e).unload($n)),Pn[a]=r),l.onreadystatechange=r):r()},abort:function(){r&&r(t,!0)}}}});var Xn,Un,Vn=/^(?:toggle|show|hide)$/,Yn=RegExp("^(?:([+-])=|)("+w+")([a-z%]*)$","i"),Jn=/queueHooks$/,Gn=[nr],Qn={"*":[function(e,t){var n=this.createTween(e,t),r=n.cur(),i=Yn.exec(t),o=i&&i[3]||(x.cssNumber[e]?"":"px"),a=(x.cssNumber[e]||"px"!==o&&+r)&&Yn.exec(x.css(n.elem,e)),s=1,l=20;if(a&&a[3]!==o){o=o||a[3],i=i||[],a=+r||1;do s=s||".5",a/=s,x.style(n.elem,e,a+o);while(s!==(s=n.cur()/r)&&1!==s&&--l)}return i&&(a=n.start=+a||+r||0,n.unit=o,n.end=i[1]?a+(i[1]+1)*i[2]:+i[2]),n}]};function Kn(){return setTimeout(function(){Xn=t}),Xn=x.now()}function Zn(e,t,n){var r,i=(Qn[t]||[]).concat(Qn["*"]),o=0,a=i.length;for(;a>o;o++)if(r=i[o].call(n,t,e))return r}function er(e,t,n){var r,i,o=0,a=Gn.length,s=x.Deferred().always(function(){delete l.elem}),l=function(){if(i)return!1;var t=Xn||Kn(),n=Math.max(0,u.startTime+u.duration-t),r=n/u.duration||0,o=1-r,a=0,l=u.tweens.length;for(;l>a;a++)u.tweens[a].run(o);return s.notifyWith(e,[u,o,n]),1>o&&l?n:(s.resolveWith(e,[u]),!1)},u=s.promise({elem:e,props:x.extend({},t),opts:x.extend(!0,{specialEasing:{}},n),originalProperties:t,originalOptions:n,startTime:Xn||Kn(),duration:n.duration,tweens:[],createTween:function(t,n){var r=x.Tween(e,u.opts,t,n,u.opts.specialEasing[t]||u.opts.easing);return u.tweens.push(r),r},stop:function(t){var n=0,r=t?u.tweens.length:0;if(i)return this;for(i=!0;r>n;n++)u.tweens[n].run(1);return t?s.resolveWith(e,[u,t]):s.rejectWith(e,[u,t]),this}}),c=u.props;for(tr(c,u.opts.specialEasing);a>o;o++)if(r=Gn[o].call(u,e,c,u.opts))return r;return x.map(c,Zn,u),x.isFunction(u.opts.start)&&u.opts.start.call(e,u),x.fx.timer(x.extend(l,{elem:e,anim:u,queue:u.opts.queue})),u.progress(u.opts.progress).done(u.opts.done,u.opts.complete).fail(u.opts.fail).always(u.opts.always)}function tr(e,t){var n,r,i,o,a;for(n in e)if(r=x.camelCase(n),i=t[r],o=e[n],x.isArray(o)&&(i=o[1],o=e[n]=o[0]),n!==r&&(e[r]=o,delete e[n]),a=x.cssHooks[r],a&&"expand"in a){o=a.expand(o),delete e[r];for(n in o)n in e||(e[n]=o[n],t[n]=i)}else t[r]=i}x.Animation=x.extend(er,{tweener:function(e,t){x.isFunction(e)?(t=e,e=["*"]):e=e.split(" ");var n,r=0,i=e.length;for(;i>r;r++)n=e[r],Qn[n]=Qn[n]||[],Qn[n].unshift(t)},prefilter:function(e,t){t?Gn.unshift(e):Gn.push(e)}});function nr(e,t,n){var r,i,o,a,s,l,u=this,c={},p=e.style,f=e.nodeType&&nn(e),d=x._data(e,"fxshow");n.queue||(s=x._queueHooks(e,"fx"),null==s.unqueued&&(s.unqueued=0,l=s.empty.fire,s.empty.fire=function(){s.unqueued||l()}),s.unqueued++,u.always(function(){u.always(function(){s.unqueued--,x.queue(e,"fx").length||s.empty.fire()})})),1===e.nodeType&&("height"in t||"width"in t)&&(n.overflow=[p.overflow,p.overflowX,p.overflowY],"inline"===x.css(e,"display")&&"none"===x.css(e,"float")&&(x.support.inlineBlockNeedsLayout&&"inline"!==ln(e.nodeName)?p.zoom=1:p.display="inline-block")),n.overflow&&(p.overflow="hidden",x.support.shrinkWrapBlocks||u.always(function(){p.overflow=n.overflow[0],p.overflowX=n.overflow[1],p.overflowY=n.overflow[2]}));for(r in t)if(i=t[r],Vn.exec(i)){if(delete t[r],o=o||"toggle"===i,i===(f?"hide":"show"))continue;c[r]=d&&d[r]||x.style(e,r)}if(!x.isEmptyObject(c)){d?"hidden"in d&&(f=d.hidden):d=x._data(e,"fxshow",{}),o&&(d.hidden=!f),f?x(e).show():u.done(function(){x(e).hide()}),u.done(function(){var t;x._removeData(e,"fxshow");for(t in c)x.style(e,t,c[t])});for(r in c)a=Zn(f?d[r]:0,r,u),r in d||(d[r]=a.start,f&&(a.end=a.start,a.start="width"===r||"height"===r?1:0))}}function rr(e,t,n,r,i){return new rr.prototype.init(e,t,n,r,i)}x.Tween=rr,rr.prototype={constructor:rr,init:function(e,t,n,r,i,o){this.elem=e,this.prop=n,this.easing=i||"swing",this.options=t,this.start=this.now=this.cur(),this.end=r,this.unit=o||(x.cssNumber[n]?"":"px")},cur:function(){var e=rr.propHooks[this.prop];return e&&e.get?e.get(this):rr.propHooks._default.get(this)},run:function(e){var t,n=rr.propHooks[this.prop];return this.pos=t=this.options.duration?x.easing[this.easing](e,this.options.duration*e,0,1,this.options.duration):e,this.now=(this.end-this.start)*t+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),n&&n.set?n.set(this):rr.propHooks._default.set(this),this}},rr.prototype.init.prototype=rr.prototype,rr.propHooks={_default:{get:function(e){var t;return null==e.elem[e.prop]||e.elem.style&&null!=e.elem.style[e.prop]?(t=x.css(e.elem,e.prop,""),t&&"auto"!==t?t:0):e.elem[e.prop]},set:function(e){x.fx.step[e.prop]?x.fx.step[e.prop](e):e.elem.style&&(null!=e.elem.style[x.cssProps[e.prop]]||x.cssHooks[e.prop])?x.style(e.elem,e.prop,e.now+e.unit):e.elem[e.prop]=e.now}}},rr.propHooks.scrollTop=rr.propHooks.scrollLeft={set:function(e){e.elem.nodeType&&e.elem.parentNode&&(e.elem[e.prop]=e.now)}},x.each(["toggle","show","hide"],function(e,t){var n=x.fn[t];x.fn[t]=function(e,r,i){return null==e||"boolean"==typeof e?n.apply(this,arguments):this.animate(ir(t,!0),e,r,i)}}),x.fn.extend({fadeTo:function(e,t,n,r){return this.filter(nn).css("opacity",0).show().end().animate({opacity:t},e,n,r)},animate:function(e,t,n,r){var i=x.isEmptyObject(e),o=x.speed(t,n,r),a=function(){var t=er(this,x.extend({},e),o);(i||x._data(this,"finish"))&&t.stop(!0)};return a.finish=a,i||o.queue===!1?this.each(a):this.queue(o.queue,a)},stop:function(e,n,r){var i=function(e){var t=e.stop;delete e.stop,t(r)};return"string"!=typeof e&&(r=n,n=e,e=t),n&&e!==!1&&this.queue(e||"fx",[]),this.each(function(){var t=!0,n=null!=e&&e+"queueHooks",o=x.timers,a=x._data(this);if(n)a[n]&&a[n].stop&&i(a[n]);else for(n in a)a[n]&&a[n].stop&&Jn.test(n)&&i(a[n]);for(n=o.length;n--;)o[n].elem!==this||null!=e&&o[n].queue!==e||(o[n].anim.stop(r),t=!1,o.splice(n,1));(t||!r)&&x.dequeue(this,e)})},finish:function(e){return e!==!1&&(e=e||"fx"),this.each(function(){var t,n=x._data(this),r=n[e+"queue"],i=n[e+"queueHooks"],o=x.timers,a=r?r.length:0;for(n.finish=!0,x.queue(this,e,[]),i&&i.stop&&i.stop.call(this,!0),t=o.length;t--;)o[t].elem===this&&o[t].queue===e&&(o[t].anim.stop(!0),o.splice(t,1));for(t=0;a>t;t++)r[t]&&r[t].finish&&r[t].finish.call(this);delete n.finish})}});function ir(e,t){var n,r={height:e},i=0;for(t=t?1:0;4>i;i+=2-t)n=Zt[i],r["margin"+n]=r["padding"+n]=e;return t&&(r.opacity=r.width=e),r}x.each({slideDown:ir("show"),slideUp:ir("hide"),slideToggle:ir("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(e,t){x.fn[e]=function(e,n,r){return this.animate(t,e,n,r)}}),x.speed=function(e,t,n){var r=e&&"object"==typeof e?x.extend({},e):{complete:n||!n&&t||x.isFunction(e)&&e,duration:e,easing:n&&t||t&&!x.isFunction(t)&&t};return r.duration=x.fx.off?0:"number"==typeof r.duration?r.duration:r.duration in x.fx.speeds?x.fx.speeds[r.duration]:x.fx.speeds._default,(null==r.queue||r.queue===!0)&&(r.queue="fx"),r.old=r.complete,r.complete=function(){x.isFunction(r.old)&&r.old.call(this),r.queue&&x.dequeue(this,r.queue)},r},x.easing={linear:function(e){return e},swing:function(e){return.5-Math.cos(e*Math.PI)/2}},x.timers=[],x.fx=rr.prototype.init,x.fx.tick=function(){var e,n=x.timers,r=0;for(Xn=x.now();n.length>r;r++)e=n[r],e()||n[r]!==e||n.splice(r--,1);n.length||x.fx.stop(),Xn=t},x.fx.timer=function(e){e()&&x.timers.push(e)&&x.fx.start()},x.fx.interval=13,x.fx.start=function(){Un||(Un=setInterval(x.fx.tick,x.fx.interval))},x.fx.stop=function(){clearInterval(Un),Un=null},x.fx.speeds={slow:600,fast:200,_default:400},x.fx.step={},x.expr&&x.expr.filters&&(x.expr.filters.animated=function(e){return x.grep(x.timers,function(t){return e===t.elem}).length}),x.fn.offset=function(e){if(arguments.length)return e===t?this:this.each(function(t){x.offset.setOffset(this,e,t)});var n,r,o={top:0,left:0},a=this[0],s=a&&a.ownerDocument;if(s)return n=s.documentElement,x.contains(n,a)?(typeof a.getBoundingClientRect!==i&&(o=a.getBoundingClientRect()),r=or(s),{top:o.top+(r.pageYOffset||n.scrollTop)-(n.clientTop||0),left:o.left+(r.pageXOffset||n.scrollLeft)-(n.clientLeft||0)}):o},x.offset={setOffset:function(e,t,n){var r=x.css(e,"position");"static"===r&&(e.style.position="relative");var i=x(e),o=i.offset(),a=x.css(e,"top"),s=x.css(e,"left"),l=("absolute"===r||"fixed"===r)&&x.inArray("auto",[a,s])>-1,u={},c={},p,f;l?(c=i.position(),p=c.top,f=c.left):(p=parseFloat(a)||0,f=parseFloat(s)||0),x.isFunction(t)&&(t=t.call(e,n,o)),null!=t.top&&(u.top=t.top-o.top+p),null!=t.left&&(u.left=t.left-o.left+f),"using"in t?t.using.call(e,u):i.css(u)}},x.fn.extend({position:function(){if(this[0]){var e,t,n={top:0,left:0},r=this[0];return"fixed"===x.css(r,"position")?t=r.getBoundingClientRect():(e=this.offsetParent(),t=this.offset(),x.nodeName(e[0],"html")||(n=e.offset()),n.top+=x.css(e[0],"borderTopWidth",!0),n.left+=x.css(e[0],"borderLeftWidth",!0)),{top:t.top-n.top-x.css(r,"marginTop",!0),left:t.left-n.left-x.css(r,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){var e=this.offsetParent||s;while(e&&!x.nodeName(e,"html")&&"static"===x.css(e,"position"))e=e.offsetParent;return e||s})}}),x.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(e,n){var r=/Y/.test(n);x.fn[e]=function(i){return x.access(this,function(e,i,o){var a=or(e);return o===t?a?n in a?a[n]:a.document.documentElement[i]:e[i]:(a?a.scrollTo(r?x(a).scrollLeft():o,r?o:x(a).scrollTop()):e[i]=o,t)},e,i,arguments.length,null)}});function or(e){return x.isWindow(e)?e:9===e.nodeType?e.defaultView||e.parentWindow:!1}x.each({Height:"height",Width:"width"},function(e,n){x.each({padding:"inner"+e,content:n,"":"outer"+e},function(r,i){x.fn[i]=function(i,o){var a=arguments.length&&(r||"boolean"!=typeof i),s=r||(i===!0||o===!0?"margin":"border");return x.access(this,function(n,r,i){var o;return x.isWindow(n)?n.document.documentElement["client"+e]:9===n.nodeType?(o=n.documentElement,Math.max(n.body["scroll"+e],o["scroll"+e],n.body["offset"+e],o["offset"+e],o["client"+e])):i===t?x.css(n,r,s):x.style(n,r,i,s)},n,a?i:t,a,null)}})}),x.fn.size=function(){return this.length},x.fn.andSelf=x.fn.addBack,"object"==typeof module&&module&&"object"==typeof module.exports?module.exports=x:(e.jQuery=e.$=x,"function"==typeof define&&define.amd&&define("jquery",[],function(){return x}))})(window);

;window.Modernizr=function(a,b,c){function t(a){i.cssText=a}function u(a,b){return t(prefixes.join(a+";")+(b||""))}function v(a,b){return typeof a===b}function w(a,b){return!!~(""+a).indexOf(b)}function x(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:v(f,"function")?f.bind(d||b):f}return!1}var d="2.6.2",e={},f=b.documentElement,g="modernizr",h=b.createElement(g),i=h.style,j,k={}.toString,l={},m={},n={},o=[],p=o.slice,q,r={}.hasOwnProperty,s;!v(r,"undefined")&&!v(r.call,"undefined")?s=function(a,b){return r.call(a,b)}:s=function(a,b){return b in a&&v(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=p.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(p.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(p.call(arguments)))};return e}),l.audio=function(){var a=b.createElement("audio"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/,""),c.mp3=a.canPlayType("audio/mpeg;").replace(/^no$/,""),c.wav=a.canPlayType('audio/wav; codecs="1"').replace(/^no$/,""),c.m4a=(a.canPlayType("audio/x-m4a;")||a.canPlayType("audio/aac;")).replace(/^no$/,"")}catch(d){}return c};for(var y in l)s(l,y)&&(q=y.toLowerCase(),e[q]=l[y](),o.push((e[q]?"":"no-")+q));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)s(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof enableClasses!="undefined"&&enableClasses&&(f.className+=" "+(b?"":"no-")+a),e[a]=b}return e},t(""),h=j=null,e._version=d,e}(this,this.document);

var libFuncName=null;if(typeof jQuery=="undefined"&&typeof Zepto=="undefined"&&typeof $=="function")libFuncName=$;else if(typeof jQuery=="function")libFuncName=jQuery;else{if(typeof Zepto!="function")throw new TypeError;libFuncName=Zepto}(function(e,t,n,r){"use strict";t.matchMedia=t.matchMedia||function(e,t){var n,r=e.documentElement,i=r.firstElementChild||r.firstChild,s=e.createElement("body"),o=e.createElement("div");return o.id="mq-test-1",o.style.cssText="position:absolute;top:-100em",s.style.background="none",s.appendChild(o),function(e){return o.innerHTML='&shy;<style media="'+e+'"> #mq-test-1 { width: 42px; }</style>',r.insertBefore(s,i),n=o.offsetWidth===42,r.removeChild(s),{matches:n,media:e}}}(n),Array.prototype.filter||(Array.prototype.filter=function(e){if(this==null)throw new TypeError;var t=Object(this),n=t.length>>>0;if(typeof e!="function")return;var r=[],i=arguments[1];for(var s=0;s<n;s++)if(s in t){var o=t[s];e&&e.call(i,o,s,t)&&r.push(o)}return r}),Function.prototype.bind||(Function.prototype.bind=function(e){if(typeof this!="function")throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");var t=Array.prototype.slice.call(arguments,1),n=this,r=function(){},i=function(){return n.apply(this instanceof r&&e?this:e,t.concat(Array.prototype.slice.call(arguments)))};return r.prototype=this.prototype,i.prototype=new r,i}),Array.prototype.indexOf||(Array.prototype.indexOf=function(e){if(this==null)throw new TypeError;var t=Object(this),n=t.length>>>0;if(n===0)return-1;var r=0;arguments.length>1&&(r=Number(arguments[1]),r!=r?r=0:r!=0&&r!=Infinity&&r!=-Infinity&&(r=(r>0||-1)*Math.floor(Math.abs(r))));if(r>=n)return-1;var i=r>=0?r:Math.max(n-Math.abs(r),0);for(;i<n;i++)if(i in t&&t[i]===e)return i;return-1}),e.fn.stop=e.fn.stop||function(){return this},t.Foundation={name:"Foundation",version:"4.2.3",cache:{},init:function(t,n,r,i,s,o){var u,a=[t,r,i,s],f=[],o=o||!1;o&&(this.nc=o),this.rtl=/rtl/i.test(e("html").attr("dir")),this.scope=t||this.scope;if(n&&typeof n=="string"&&!/reflow/i.test(n)){if(/off/i.test(n))return this.off();u=n.split(" ");if(u.length>0)for(var l=u.length-1;l>=0;l--)f.push(this.init_lib(u[l],a))}else{/reflow/i.test(n)&&(a[1]="reflow");for(var c in this.libs)f.push(this.init_lib(c,a))}return typeof n=="function"&&a.unshift(n),this.response_obj(f,a)},response_obj:function(e,t){for(var n=0,r=t.length;n<r;n++)if(typeof t[n]=="function")return t[n]({errors:e.filter(function(e){if(typeof e=="string")return e})});return e},init_lib:function(e,t){return this.trap(function(){return this.libs.hasOwnProperty(e)?(this.patch(this.libs[e]),this.libs[e].init.apply(this.libs[e],t)):function(){}}.bind(this),e)},trap:function(e,t){if(!this.nc)try{return e()}catch(n){return this.error({name:t,message:"could not be initialized",more:n.name+" "+n.message})}return e()},patch:function(e){this.fix_outer(e),e.scope=this.scope,e.rtl=this.rtl},inherit:function(e,t){var n=t.split(" ");for(var r=n.length-1;r>=0;r--)this.lib_methods.hasOwnProperty(n[r])&&(this.libs[e.name][n[r]]=this.lib_methods[n[r]])},random_str:function(e){var t="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz".split("");e||(e=Math.floor(Math.random()*t.length));var n="";for(var r=0;r<e;r++)n+=t[Math.floor(Math.random()*t.length)];return n},libs:{},lib_methods:{set_data:function(e,t){var n=[this.name,+(new Date),Foundation.random_str(5)].join("-");return Foundation.cache[n]=t,e.attr("data-"+this.name+"-id",n),t},get_data:function(e){return Foundation.cache[e.attr("data-"+this.name+"-id")]},remove_data:function(t){t?(delete Foundation.cache[t.attr("data-"+this.name+"-id")],t.attr("data-"+this.name+"-id","")):e("[data-"+this.name+"-id]").each(function(){delete Foundation.cache[e(this).attr("data-"+this.name+"-id")],e(this).attr("data-"+this.name+"-id","")})},throttle:function(e,t){var n=null;return function(){var r=this,i=arguments;clearTimeout(n),n=setTimeout(function(){e.apply(r,i)},t)}},data_options:function(t){function u(e){return!isNaN(e-0)&&e!==null&&e!==""&&e!==!1&&e!==!0}function a(t){return typeof t=="string"?e.trim(t):t}var n={},r,i,s=(t.attr("data-options")||":").split(";"),o=s.length;for(r=o-1;r>=0;r--)i=s[r].split(":"),/true/i.test(i[1])&&(i[1]=!0),/false/i.test(i[1])&&(i[1]=!1),u(i[1])&&(i[1]=parseInt(i[1],10)),i.length===2&&i[0].length>0&&(n[a(i[0])]=a(i[1]));return n},delay:function(e,t){return setTimeout(e,t)},scrollTo:function(n,r,i){if(i<0)return;var s=r-e(t).scrollTop(),o=s/i*10;this.scrollToTimerCache=setTimeout(function(){isNaN(parseInt(o,10))||(t.scrollTo(0,e(t).scrollTop()+o),this.scrollTo(n,r,i-10))}.bind(this),10)},scrollLeft:function(e){if(!e.length)return;return"scrollLeft"in e[0]?e[0].scrollLeft:e[0].pageXOffset},empty:function(e){if(e.length&&e.length>0)return!1;if(e.length&&e.length===0)return!0;for(var t in e)if(hasOwnProperty.call(e,t))return!1;return!0}},fix_outer:function(e){e.outerHeight=function(e,t){return typeof Zepto=="function"?e.height():typeof t!="undefined"?e.outerHeight(t):e.outerHeight()},e.outerWidth=function(e){return typeof Zepto=="function"?e.width():typeof bool!="undefined"?e.outerWidth(bool):e.outerWidth()}},error:function(e){return e.name+" "+e.message+"; "+e.more},off:function(){return e(this.scope).off(".fndtn"),e(t).off(".fndtn"),!0},zj:function(){return typeof Zepto!="undefined"?Zepto:jQuery}()},e.fn.foundation=function(){var e=Array.prototype.slice.call(arguments,0);return this.each(function(){return Foundation.init.apply(Foundation,[this].concat(e)),this})}})(libFuncName,this,this.document),function(e,t,n,r){"use strict";Foundation.libs.dropdown={name:"dropdown",version:"4.2.0",settings:{activeClass:"open",is_hover:!1,opened:function(){},closed:function(){}},init:function(t,n,r){return this.scope=t||this.scope,Foundation.inherit(this,"throttle scrollLeft data_options"),typeof n=="object"&&e.extend(!0,this.settings,n),typeof n!="string"?(this.settings.init||this.events(),this.settings.init):this[n].call(this,r)},events:function(){var n=this;e(this.scope).on("click.fndtn.dropdown","[data-dropdown]",function(t){var r=e.extend({},n.settings,n.data_options(e(this)));t.preventDefault(),r.is_hover||n.toggle(e(this))}).on("mouseenter","[data-dropdown]",function(t){var r=e.extend({},n.settings,n.data_options(e(this)));r.is_hover&&n.toggle(e(this))}).on("mouseleave","[data-dropdown-content]",function(t){var r=e('[data-dropdown="'+e(this).attr("id")+'"]'),i=e.extend({},n.settings,n.data_options(r));i.is_hover&&n.close.call(n,e(this))}).on("opened.fndtn.dropdown","[data-dropdown-content]",this.settings.opened).on("closed.fndtn.dropdown","[data-dropdown-content]",this.settings.closed),e("body").on("click.fndtn.dropdown",function(t){var r=e(t.target).closest("[data-dropdown-content]");if(e(t.target).data("dropdown"))return;if(r.length>0&&(e(t.target).is("[data-dropdown-content]")||e.contains(r.first()[0],t.target))){t.stopPropagation();return}n.close.call(n,e("[data-dropdown-content]"))}),e(t).on("resize.fndtn.dropdown",n.throttle(function(){n.resize.call(n)},50)).trigger("resize"),this.settings.init=!0},close:function(t){var n=this;t.each(function(){e(this).hasClass(n.settings.activeClass)&&(e(this).css(Foundation.rtl?"right":"left","-99999px").removeClass(n.settings.activeClass),e(this).trigger("closed"))})},open:function(e,t){this.css(e.addClass(this.settings.activeClass),t),e.trigger("opened")},toggle:function(t){var n=e("#"+t.data("dropdown"));this.close.call(this,e("[data-dropdown-content]").not(n)),n.hasClass(this.settings.activeClass)?this.close.call(this,n):(this.close.call(this,e("[data-dropdown-content]")),this.open.call(this,n,t))},resize:function(){var t=e("[data-dropdown-content].open"),n=e("[data-dropdown='"+t.attr("id")+"']");t.length&&n.length&&this.css(t,n)},css:function(n,r){var i=n.offsetParent();if(i.length>0&&/body/i.test(n.offsetParent()[0].nodeName)){var s=r.offset();s.top-=n.offsetParent().offset().top,s.left-=n.offsetParent().offset().left}else var s=r.position();if(this.small())n.css({position:"absolute",width:"95%",left:"2.5%","max-width":"none",top:s.top+this.outerHeight(r)});else{if(!Foundation.rtl&&e(t).width()>this.outerWidth(n)+r.offset().left){var o=s.left;n.hasClass("right")&&n.removeClass("right")}else{n.hasClass("right")||n.addClass("right");var o=s.left-(this.outerWidth(n)-this.outerWidth(r))}n.attr("style","").css({position:"absolute",top:s.top+this.outerHeight(r),left:o})}return n},small:function(){return e(t).width()<768||e("html").hasClass("lt-ie9")},off:function(){e(this.scope).off(".fndtn.dropdown"),e("html, body").off(".fndtn.dropdown"),e(t).off(".fndtn.dropdown"),e("[data-dropdown-content]").off(".fndtn.dropdown"),this.settings.init=!1},reflow:function(){}}}(Foundation.zj,this,this.document),function(e,t,n){function f(e){var t={},r=/^jQuery\d+$/;return n.each(e.attributes,function(e,n){n.specified&&!r.test(n.name)&&(t[n.name]=n.value)}),t}function l(e,r){var i=this,s=n(i);if(i.value==s.attr("placeholder")&&s.hasClass("placeholder"))if(s.data("placeholder-password")){s=s.hide().next().show().attr("id",s.removeAttr("id").data("placeholder-id"));if(e===!0)return s[0].value=r;s.focus()}else i.value="",s.removeClass("placeholder"),i==t.activeElement&&i.select()}function c(){var e,t=this,r=n(t),i=r,s=this.id;if(t.value==""){if(t.type=="password"){if(!r.data("placeholder-textinput")){try{e=r.clone().attr({type:"text"})}catch(o){e=n("<input>").attr(n.extend(f(this),{type:"text"}))}e.removeAttr("name").data({"placeholder-password":!0,"placeholder-id":s}).bind("focus.placeholder",l),r.data({"placeholder-textinput":e,"placeholder-id":s}).before(e)}r=r.removeAttr("id").hide().prev().attr("id",s).show()}r.addClass("placeholder"),r[0].value=r.attr("placeholder")}else r.removeClass("placeholder")}var r="placeholder"in t.createElement("input"),i="placeholder"in t.createElement("textarea"),s=n.fn,o=n.valHooks,u,a;r&&i?(a=s.placeholder=function(){return this},a.input=a.textarea=!0):(a=s.placeholder=function(){var e=this;return e.filter((r?"textarea":":input")+"[placeholder]").not(".placeholder").bind({"focus.placeholder":l,"blur.placeholder":c}).data("placeholder-enabled",!0).trigger("blur.placeholder"),e},a.input=r,a.textarea=i,u={get:function(e){var t=n(e);return t.data("placeholder-enabled")&&t.hasClass("placeholder")?"":e.value},set:function(e,r){var i=n(e);return i.data("placeholder-enabled")?(r==""?(e.value=r,e!=t.activeElement&&c.call(e)):i.hasClass("placeholder")?l.call(e,!0,r)||(e.value=r):e.value=r,i):e.value=r}},r||(o.input=u),i||(o.textarea=u),n(function(){n(t).delegate("form","submit.placeholder",function(){var e=n(".placeholder",this).each(l);setTimeout(function(){e.each(c)},10)})}),n(e).bind("beforeunload.placeholder",function(){n(".placeholder").each(function(){this.value=""})}))}(this,document,Foundation.zj),function(e,t,n,r){"use strict";Foundation.libs.placeholder={name:"placeholder",version:"4.2.2",init:function(n,r,i){this.scope=n||this.scope,typeof r!="string"&&(t.onload=function(){e("input, textarea").placeholder()})}}}(Foundation.zj,this,this.document),function(e,t,n,r){"use strict";Foundation.libs.forms={name:"forms",version:"4.2.3",cache:{},settings:{disable_class:"no-custom",last_combo:null},init:function(t,n,r){return typeof n=="object"&&e.extend(!0,this.settings,n),typeof n!="string"?(this.settings.init||this.events(),this.assemble(),this.settings.init):this[n].call(this,r)},assemble:function(){e('form.custom input[type="radio"]',e(this.scope)).not('[data-customforms="disabled"]').not("."+this.settings.disable_class).each(this.append_custom_markup),e('form.custom input[type="checkbox"]',e(this.scope)).not('[data-customforms="disabled"]').not("."+this.settings.disable_class).each(this.append_custom_markup),e("form.custom select",e(this.scope)).not('[data-customforms="disabled"]').not("."+this.settings.disable_class).not("[multiple=multiple]").each(this.append_custom_select)},events:function(){var r=this;e(this.scope).on("click.fndtn.forms","form.custom span.custom.checkbox",function(t){t.preventDefault(),t.stopPropagation(),r.toggle_checkbox(e(this))}).on("click.fndtn.forms","form.custom span.custom.radio",function(t){t.preventDefault(),t.stopPropagation(),r.toggle_radio(e(this))}).on("change.fndtn.forms","form.custom select",function(t,n){if(e(this).is('[data-customforms="disabled"]'))return;r.refresh_custom_select(e(this),n)}).on("click.fndtn.forms","form.custom label",function(t){if(e(t.target).is("label")){var n=e("#"+r.escape(e(this).attr("for"))).not('[data-customforms="disabled"]'),i,s;n.length!==0&&(n.attr("type")==="checkbox"?(t.preventDefault(),i=e(this).find("span.custom.checkbox"),i.length===0&&(i=n.add(this).siblings("span.custom.checkbox").first()),r.toggle_checkbox(i)):n.attr("type")==="radio"&&(t.preventDefault(),s=e(this).find("span.custom.radio"),s.length===0&&(s=n.add(this).siblings("span.custom.radio").first()),r.toggle_radio(s)))}}).on("mousedown.fndtn.forms","form.custom div.custom.dropdown",function(){return!1}).on("click.fndtn.forms","form.custom div.custom.dropdown a.current, form.custom div.custom.dropdown a.selector",function(t){var n=e(this),s=n.closest("div.custom.dropdown"),o=i(s,"select");s.hasClass("open")||e(r.scope).trigger("click"),t.preventDefault();if(!1===o.is(":disabled"))return s.toggleClass("open"),s.hasClass("open")?e(r.scope).on("click.fndtn.forms.customdropdown",function(){s.removeClass("open"),e(r.scope).off(".fndtn.forms.customdropdown")}):e(r.scope).on(".fndtn.forms.customdropdown"),!1}).on("click.fndtn.forms touchend.fndtn.forms","form.custom div.custom.dropdown li",function(t){var n=e(this),r=n.closest("div.custom.dropdown"),s=i(r,"select"),o=0;t.preventDefault(),t.stopPropagation();if(!e(this).hasClass("disabled")){e("div.dropdown").not(r).removeClass("open");var u=n.closest("ul").find("li.selected");u.removeClass("selected"),n.addClass("selected"),r.removeClass("open").find("a.current").text(n.text()),n.closest("ul").find("li").each(function(e){n[0]===this&&(o=e)}),s[0].selectedIndex=o,s.data("prevalue",u.html()),s.trigger("change")}}),e(t).on("keydown",function(t){var r=n.activeElement,i=Foundation.libs.forms,s=e(".custom.dropdown.open");if(s.length>0){t.preventDefault(),t.which===13&&s.find("li.selected").trigger("click"),t.which===27&&s.removeClass("open");if(t.which>=65&&t.which<=90){var o=i.go_to(s,t.which),u=s.find("li.selected");o&&(u.removeClass("selected"),i.scrollTo(o.addClass("selected"),300))}if(t.which===38){var u=s.find("li.selected"),a=u.prev(":not(.disabled)");a.length>0&&(a.parent()[0].scrollTop=a.parent().scrollTop()-i.outerHeight(a),u.removeClass("selected"),a.addClass("selected"))}else if(t.which===40){var u=s.find("li.selected"),o=u.next(":not(.disabled)");o.length>0&&(o.parent()[0].scrollTop=o.parent().scrollTop()+i.outerHeight(o),u.removeClass("selected"),o.addClass("selected"))}}}),this.settings.init=!0},go_to:function(e,t){var n=e.find("li"),r=n.length;if(r>0)for(var i=0;i<r;i++){var s=n.eq(i).text().charAt(0).toLowerCase();if(s===String.fromCharCode(t).toLowerCase())return n.eq(i)}},scrollTo:function(e,t){if(t<0)return;var n=e.parent(),r=this.outerHeight(e),i=r*e.index()-n.scrollTop(),s=i/t*10;this.scrollToTimerCache=setTimeout(function(){isNaN(parseInt(s,10))||(n[0].scrollTop=n.scrollTop()+s,this.scrollTo(e,t-10))}.bind(this),10)},append_custom_markup:function(t,n){var r=e(n),i=r.attr("type"),s=r.next("span.custom."+i);r.parent().hasClass("switch")||r.addClass("hidden-field"),s.length===0&&(s=e('<span class="custom '+i+'"></span>').insertAfter(r)),s.toggleClass("checked",r.is(":checked")),s.toggleClass("disabled",r.is(":disabled"))},append_custom_select:function(t,n){var r=Foundation.libs.forms,i=e(n),s=i.next("div.custom.dropdown"),o=s.find("ul"),u=s.find(".current"),a=s.find(".selector"),f=i.find("option"),l=f.filter(":selected"),c=i.attr("class")?i.attr("class").split(" "):[],h=0,p="",d,v=!1;if(s.length===0){var m=i.hasClass("small")?"small":i.hasClass("medium")?"medium":i.hasClass("large")?"large":i.hasClass("expand")?"expand":"";s=e('<div class="'+["custom","dropdown",m].concat(c).filter(function(e,t,n){return e===""?!1:n.indexOf(e)===t}).join(" ")+'"><a href="#" class="selector"></a><ul /></div>'),a=s.find(".selector"),o=s.find("ul"),p=f.map(function(){var t=e(this).attr("class")?e(this).attr("class"):"";return"<li class='"+t+"'>"+e(this).html()+"</li>"}).get().join(""),o.append(p),v=s.prepend('<a href="#" class="current">'+l.html()+"</a>").find(".current"),i.after(s).addClass("hidden-field")}else p=f.map(function(){return"<li>"+e(this).html()+"</li>"}).get().join(""),o.html("").append(p);r.assign_id(i,s),s.toggleClass("disabled",i.is(":disabled")),d=o.find("li"),r.cache[s.data("id")]=d.length,f.each(function(t){this.selected&&(d.eq(t).addClass("selected"),v&&v.html(e(this).html())),e(this).is(":disabled")&&d.eq(t).addClass("disabled")});if(!s.is(".small, .medium, .large, .expand")){s.addClass("open");var r=Foundation.libs.forms;r.hidden_fix.adjust(o),h=r.outerWidth(d)>h?r.outerWidth(d):h,Foundation.libs.forms.hidden_fix.reset(),s.removeClass("open")}},assign_id:function(e,t){var n=[+(new Date),Foundation.random_str(5)].join("-");e.attr("data-id",n),t.attr("data-id",n)},refresh_custom_select:function(t,n){var r=this,i=0,s=t.next(),o=t.find("option"),u=s.find("li");if(u.length!==this.cache[s.data("id")]||n)s.find("ul").html(""),o.each(function(){var t=e("<li>"+e(this).html()+"</li>");s.find("ul").append(t)}),o.each(function(t){this.selected&&(s.find("li").eq(t).addClass("selected"),s.find(".current").html(e(this).html())),e(this).is(":disabled")&&s.find("li").eq(t).addClass("disabled")}),s.removeAttr("style").find("ul").removeAttr("style"),s.find("li").each(function(){s.addClass("open"),r.outerWidth(e(this))>i&&(i=r.outerWidth(e(this))),s.removeClass("open")}),u=s.find("li"),this.cache[s.data("id")]=u.length},toggle_checkbox:function(e){var t=e.prev(),n=t[0];!1===t.is(":disabled")&&(n.checked=n.checked?!1:!0,e.toggleClass("checked"),t.trigger("change"))},toggle_radio:function(e){var t=e.prev(),n=t.closest("form.custom"),r=t[0];!1===t.is(":disabled")&&(n.find('input[type="radio"][name="'+this.escape(t.attr("name"))+'"]').next().not(e).removeClass("checked"),e.hasClass("checked")||e.toggleClass("checked"),r.checked=e.hasClass("checked"),t.trigger("change"))},escape:function(e){return e?e.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"):""},hidden_fix:{tmp:[],hidden:null,adjust:function(t){var n=this;n.hidden=t.parents(),n.hidden=n.hidden.add(t).filter(":hidden"),n.hidden.each(function(){var t=e(this);n.tmp.push(t.attr("style")),t.css({visibility:"hidden",display:"block"})})},reset:function(){var t=this;t.hidden.each(function(n){var i=e(this),s=t.tmp[n];s===r?i.removeAttr("style"):i.attr("style",s)}),t.tmp=[],t.hidden=null}},off:function(){e(this.scope).off(".fndtn.forms")},reflow:function(){}};var i=function(t,n){var t=t.prev();while(t.length){if(t.is(n))return t;t=t.prev()}return e()}}(Foundation.zj,this,this.document),function(e,t,n,r){"use strict";Foundation.libs.alerts={name:"alerts",version:"4.2.2",settings:{speed:300,callback:function(){}},init:function(t,n,r){return this.scope=t||this.scope,typeof n=="object"&&e.extend(!0,this.settings,n),typeof n!="string"?(this.settings.init||this.events(),this.settings.init):this[n].call(this,r)},events:function(){var t=this;e(this.scope).on("click.fndtn.alerts","[data-alert] a.close",function(n){n.preventDefault(),e(this).closest("[data-alert]").fadeOut(t.speed,function(){e(this).remove(),t.settings.callback()})}),this.settings.init=!0},off:function(){e(this.scope).off(".fndtn.alerts")},reflow:function(){}}}(Foundation.zj,this,this.document),function(e,t,n,r){"use strict";Foundation.libs.reveal={name:"reveal",version:"4.2.2",locked:!1,settings:{animation:"fadeAndPop",animationSpeed:250,closeOnBackgroundClick:!0,closeOnEsc:!0,dismissModalClass:"close-reveal-modal",bgClass:"reveal-modal-bg",open:function(){},opened:function(){},close:function(){},closed:function(){},bg:e(".reveal-modal-bg"),css:{open:{opacity:0,visibility:"visible",display:"block"},close:{opacity:1,visibility:"hidden",display:"none"}}},init:function(t,n,r){return Foundation.inherit(this,"data_options delay"),typeof n=="object"?e.extend(!0,this.settings,n):typeof r!="undefined"&&e.extend(!0,this.settings,r),typeof n!="string"?(this.events(),this.settings.init):this[n].call(this,r)},events:function(){var t=this;return e(this.scope).off(".fndtn.reveal").on("click.fndtn.reveal","[data-reveal-id]",function(n){n.preventDefault();if(!t.locked){var r=e(this),i=r.data("reveal-ajax");t.locked=!0;if(typeof i=="undefined")t.open.call(t,r);else{var s=i===!0?r.attr("href"):i;t.open.call(t,r,{url:s})}}}).on("click.fndtn.reveal",this.close_targets(),function(n){n.preventDefault();if(!t.locked){var r=e.extend({},t.settings,t.data_options(e(".reveal-modal.open")));if(e(n.target)[0]===e("."+r.bgClass)[0]&&!r.closeOnBackgroundClick)return;t.locked=!0,t.close.call(t,e(this).closest(".reveal-modal"))}}).on("open.fndtn.reveal",".reveal-modal",this.settings.open).on("opened.fndtn.reveal",".reveal-modal",this.settings.opened).on("opened.fndtn.reveal",".reveal-modal",this.open_video).on("close.fndtn.reveal",".reveal-modal",this.settings.close).on("closed.fndtn.reveal",".reveal-modal",this.settings.closed).on("closed.fndtn.reveal",".reveal-modal",this.close_video),e("body").bind("keyup.reveal",function(n){var r=e(".reveal-modal.open"),i=e.extend({},t.settings,t.data_options(r));n.which===27&&i.closeOnEsc&&r.foundation("reveal","close")}),!0},open:function(t,n){if(t)if(typeof t.selector!="undefined")var r=e("#"+t.data("reveal-id"));else{var r=e(this.scope);n=t}else var r=e(this.scope);if(!r.hasClass("open")){var i=e(".reveal-modal.open");typeof r.data("css-top")=="undefined"&&r.data("css-top",parseInt(r.css("top"),10)).data("offset",this.cache_offset(r)),r.trigger("open"),i.length<1&&this.toggle_bg(r);if(typeof n=="undefined"||!n.url)this.hide(i,this.settings.css.close),this.show(r,this.settings.css.open);else{var s=this,o=typeof n.success!="undefined"?n.success:null;e.extend(n,{success:function(t,n,u){e.isFunction(o)&&o(t,n,u),r.html(t),e(r).foundation("section","reflow"),s.hide(i,s.settings.css.close),s.show(r,s.settings.css.open)}}),e.ajax(n)}}},close:function(t){var t=t&&t.length?t:e(this.scope),n=e(".reveal-modal.open");n.length>0&&(this.locked=!0,t.trigger("close"),this.toggle_bg(t),this.hide(n,this.settings.css.close))},close_targets:function(){var e="."+this.settings.dismissModalClass;return this.settings.closeOnBackgroundClick?e+", ."+this.settings.bgClass:e},toggle_bg:function(t){e(".reveal-modal-bg").length===0&&(this.settings.bg=e("<div />",{"class":this.settings.bgClass}).appendTo("body")),this.settings.bg.filter(":visible").length>0?this.hide(this.settings.bg):this.show(this.settings.bg)},show:function(n,r){if(r){if(/pop/i.test(this.settings.animation)){r.top=e(t).scrollTop()-n.data("offset")+"px";var i={top:e(t).scrollTop()+n.data("css-top")+"px",opacity:1};return this.delay(function(){return n.css(r).animate(i,this.settings.animationSpeed,"linear",function(){this.locked=!1,n.trigger("opened")}.bind(this)).addClass("open")}.bind(this),this.settings.animationSpeed/2)}if(/fade/i.test(this.settings.animation)){var i={opacity:1};return this.delay(function(){return n.css(r).animate(i,this.settings.animationSpeed,"linear",function(){this.locked=!1,n.trigger("opened")}.bind(this)).addClass("open")}.bind(this),this.settings.animationSpeed/2)}return n.css(r).show().css({opacity:1}).addClass("open").trigger("opened")}return/fade/i.test(this.settings.animation)?n.fadeIn(this.settings.animationSpeed/2):n.show()},hide:function(n,r){if(r){if(/pop/i.test(this.settings.animation)){var i={top:-e(t).scrollTop()-n.data("offset")+"px",opacity:0};return this.delay(function(){return n.animate(i,this.settings.animationSpeed,"linear",function(){this.locked=!1,n.css(r).trigger("closed")}.bind(this)).removeClass("open")}.bind(this),this.settings.animationSpeed/2)}if(/fade/i.test(this.settings.animation)){var i={opacity:0};return this.delay(function(){return n.animate(i,this.settings.animationSpeed,"linear",function(){this.locked=!1,n.css(r).trigger("closed")}.bind(this)).removeClass("open")}.bind(this),this.settings.animationSpeed/2)}return n.hide().css(r).removeClass("open").trigger("closed")}return/fade/i.test(this.settings.animation)?n.fadeOut(this.settings.animationSpeed/2):n.hide()},close_video:function(t){var n=e(this).find(".flex-video"),r=n.find("iframe");r.length>0&&(r.attr("data-src",r[0].src),r.attr("src","about:blank"),n.hide())},open_video:function(t){var n=e(this).find(".flex-video"),i=n.find("iframe");if(i.length>0){var s=i.attr("data-src");if(typeof s=="string")i[0].src=i.attr("data-src");else{var o=i[0].src;i[0].src=r,i[0].src=o}n.show()}},cache_offset:function(e){var t=e.show().height()+parseInt(e.css("top"),10);return e.hide(),t},off:function(){e(this.scope).off(".fndtn.reveal")},reflow:function(){}}}(Foundation.zj,this,this.document),function(e,t,n,r){"use strict";Foundation.libs.tooltips={name:"tooltips",version:"4.2.2",settings:{selector:".has-tip",additionalInheritableClasses:[],tooltipClass:".tooltip",appendTo:"body","disable-for-touch":!1,tipTemplate:function(e,t){return'<span data-selector="'+e+'" class="'+Foundation.libs.tooltips.settings.tooltipClass.substring(1)+'">'+t+'<span class="nub"></span></span>'}},cache:{},init:function(t,n,r){Foundation.inherit(this,"data_options");var i=this;typeof n=="object"?e.extend(!0,this.settings,n):typeof r!="undefined"&&e.extend(!0,this.settings,r);if(typeof n=="string")return this[n].call(this,r);Modernizr.touch?e(this.scope).on("click.fndtn.tooltip touchstart.fndtn.tooltip touchend.fndtn.tooltip","[data-tooltip]",function(t){var n=e.extend({},i.settings,i.data_options(e(this)));n["disable-for-touch"]||(t.preventDefault(),e(n.tooltipClass).hide(),i.showOrCreateTip(e(this)))}).on("click.fndtn.tooltip touchstart.fndtn.tooltip touchend.fndtn.tooltip",this.settings.tooltipClass,function(t){t.preventDefault(),e(this).fadeOut(150)}):e(this.scope).on("mouseenter.fndtn.tooltip mouseleave.fndtn.tooltip","[data-tooltip]",function(t){var n=e(this);/enter|over/i.test(t.type)?i.showOrCreateTip(n):(t.type==="mouseout"||t.type==="mouseleave")&&i.hide(n)})},showOrCreateTip:function(e){var t=this.getTip(e);return t&&t.length>0?this.show(e):this.create(e)},getTip:function(t){var n=this.selector(t),r=null;return n&&(r=e('span[data-selector="'+n+'"]'+this.settings.tooltipClass)),typeof r=="object"?r:!1},selector:function(e){var t=e.attr("id"),n=e.attr("data-tooltip")||e.attr("data-selector");return(t&&t.length<1||!t)&&typeof n!="string"&&(n="tooltip"+Math.random().toString(36).substring(7),e.attr("data-selector",n)),t&&t.length>0?t:n},create:function(t){var n=e(this.settings.tipTemplate(this.selector(t),e("<div></div>").html(t.attr("title")).html())),r=this.inheritable_classes(t);n.addClass(r).appendTo(this.settings.appendTo),Modernizr.touch&&n.append('<span class="tap-to-close">tap to close </span>'),t.removeAttr("title").attr("title",""),this.show(t)},reposition:function(n,r,i){var s,o,u,a,f,l;r.css("visibility","hidden").show(),s=n.data("width"),o=r.children(".nub"),u=this.outerHeight(o),a=this.outerHeight(o),l=function(e,t,n,r,i,s){return e.css({top:t?t:"auto",bottom:r?r:"auto",left:i?i:"auto",right:n?n:"auto",width:s?s:"auto"}).end()},l(r,n.offset().top+this.outerHeight(n)+10,"auto","auto",n.offset().left,s);if(e(t).width()<767)l(r,n.offset().top+this.outerHeight(n)+10,"auto","auto",12.5,e(this.scope).width()),r.addClass("tip-override"),l(o,-u,"auto","auto",n.offset().left);else{var c=n.offset().left;Foundation.rtl&&(c=n.offset().left+n.offset().width-this.outerWidth(r)),l(r,n.offset().top+this.outerHeight(n)+10,"auto","auto",c,s),r.removeClass("tip-override"),i&&i.indexOf("tip-top")>-1?l(r,n.offset().top-this.outerHeight(r),"auto","auto",c,s).removeClass("tip-override"):i&&i.indexOf("tip-left")>-1?l(r,n.offset().top+this.outerHeight(n)/2-u*2.5,"auto","auto",n.offset().left-this.outerWidth(r)-u,s).removeClass("tip-override"):i&&i.indexOf("tip-right")>-1&&l(r,n.offset().top+this.outerHeight(n)/2-u*2.5,"auto","auto",n.offset().left+this.outerWidth(n)+u,s).removeClass("tip-override")}r.css("visibility","visible").hide()},inheritable_classes:function(t){var n=["tip-top","tip-left","tip-bottom","tip-right","noradius"].concat(this.settings.additionalInheritableClasses),r=t.attr("class"),i=r?e.map(r.split(" "),function(t,r){if(e.inArray(t,n)!==-1)return t}).join(" "):"";return e.trim(i)},show:function(e){var t=this.getTip(e);this.reposition(e,t,e.attr("class")),t.fadeIn(150)},hide:function(e){var t=this.getTip(e);t.fadeOut(150)},reload:function(){var t=e(this);return t.data("fndtn-tooltips")?t.foundationTooltips("destroy").foundationTooltips("init"):t.foundationTooltips("init")},off:function(){e(this.scope).off(".fndtn.tooltip"),e(this.settings.tooltipClass).each(function(t){e("[data-tooltip]").get(t).attr("title",e(this).text())}).remove()},reflow:function(){}}}(Foundation.zj,this,this.document),function(e,t,n,r){"use strict";Foundation.libs.clearing={name:"clearing",version:"4.2.2",settings:{templates:{viewing:'<a href="#" class="clearing-close">&times;</a><div class="visible-img" style="display: none"><img src="//:0"><p class="clearing-caption"></p><a href="#" class="clearing-main-prev"><span></span></a><a href="#" class="clearing-main-next"><span></span></a></div>'},close_selectors:".clearing-close",init:!1,locked:!1},init:function(t,n,r){var i=this;return Foundation.inherit(this,"set_data get_data remove_data throttle data_options"),typeof n=="object"&&(r=e.extend(!0,this.settings,n)),typeof n!="string"?(e(this.scope).find("ul[data-clearing]").each(function(){var t=e(this),n=n||{},r=t.find("li"),s=i.get_data(t);!s&&r.length>0&&(n.$parent=t.parent(),i.set_data(t,e.extend({},i.settings,n,i.data_options(t))),i.assemble(t.find("li")),i.settings.init||i.events().swipe_events())}),this.settings.init):this[n].call(this,r)},events:function(){var n=this;return e(this.scope).on("click.fndtn.clearing","ul[data-clearing] li",function(t,r,i){var r=r||e(this),i=i||r,s=r.next("li"),o=n.get_data(r.parent()),u=e(t.target);t.preventDefault(),o||n.init(),i.hasClass("visible")&&r[0]===i[0]&&s.length>0&&n.is_open(r)&&(i=s,u=i.find("img")),n.open(u,r,i),n.update_paddles(i)}).on("click.fndtn.clearing",".clearing-main-next",function(e){this.nav(e,"next")}.bind(this)).on("click.fndtn.clearing",".clearing-main-prev",function(e){this.nav(e,"prev")}.bind(this)).on("click.fndtn.clearing",this.settings.close_selectors,function(e){Foundation.libs.clearing.close(e,this)}).on("keydown.fndtn.clearing",function(e){this.keydown(e)}.bind(this)),e(t).on("resize.fndtn.clearing",function(){this.resize()}.bind(this)),this.settings.init=!0,this},swipe_events:function(){var t=this;e(this.scope).on("touchstart.fndtn.clearing",".visible-img",function(t){t.touches||(t=t.originalEvent);var n={start_page_x:t.touches[0].pageX,start_page_y:t.touches[0].pageY,start_time:(new Date).getTime(),delta_x:0,is_scrolling:r};e(this).data("swipe-transition",n),t.stopPropagation()}).on("touchmove.fndtn.clearing",".visible-img",function(n){n.touches||(n=n.originalEvent);if(n.touches.length>1||n.scale&&n.scale!==1)return;var r=e(this).data("swipe-transition");typeof r=="undefined"&&(r={}),r.delta_x=n.touches[0].pageX-r.start_page_x,typeof r.is_scrolling=="undefined"&&(r.is_scrolling=!!(r.is_scrolling||Math.abs(r.delta_x)<Math.abs(n.touches[0].pageY-r.start_page_y)));if(!r.is_scrolling&&!r.active){n.preventDefault();var i=r.delta_x<0?"next":"prev";r.active=!0,t.nav(n,i)}}).on("touchend.fndtn.clearing",".visible-img",function(t){e(this).data("swipe-transition",{}),t.stopPropagation()})},assemble:function(t){var n=t.parent();n.after('<div id="foundationClearingHolder"></div>');var r=e("#foundationClearingHolder"),i=this.get_data(n),s=n.detach(),o={grid:'<div class="carousel">'+this.outerHTML(s[0])+"</div>",viewing:i.templates.viewing},u='<div class="clearing-assembled"><div>'+o.viewing+o.grid+"</div></div>";return r.after(u).remove()},open:function(e,t,n){var r=n.closest(".clearing-assembled"),i=r.find("div").first(),s=i.find(".visible-img"),o=s.find("img").not(e);this.locked()||(o.attr("src",this.load(e)).css("visibility","hidden"),this.loaded
(o,function(){o.css("visibility","visible"),r.addClass("clearing-blackout"),i.addClass("clearing-container"),s.show(),this.fix_height(n).caption(s.find(".clearing-caption"),e).center(o).shift(t,n,function(){n.siblings().removeClass("visible"),n.addClass("visible")})}.bind(this)))},close:function(t,n){t.preventDefault();var r=function(e){return/blackout/.test(e.selector)?e:e.closest(".clearing-blackout")}(e(n)),i,s;return n===t.target&&r&&(i=r.find("div").first(),s=i.find(".visible-img"),this.settings.prev_index=0,r.find("ul[data-clearing]").attr("style","").closest(".clearing-blackout").removeClass("clearing-blackout"),i.removeClass("clearing-container"),s.hide()),!1},is_open:function(e){return e.parent().attr("style").length>0},keydown:function(t){var n=e(".clearing-blackout").find("ul[data-clearing]");t.which===39&&this.go(n,"next"),t.which===37&&this.go(n,"prev"),t.which===27&&e("a.clearing-close").trigger("click")},nav:function(t,n){var r=e(".clearing-blackout").find("ul[data-clearing]");t.preventDefault(),this.go(r,n)},resize:function(){var t=e(".clearing-blackout .visible-img").find("img");t.length&&this.center(t)},fix_height:function(t){var n=t.parent().children(),r=this;return n.each(function(){var t=e(this),n=t.find("img");t.height()>r.outerHeight(n)&&t.addClass("fix-height")}).closest("ul").width(n.length*100+"%"),this},update_paddles:function(e){var t=e.closest(".carousel").siblings(".visible-img");e.next().length>0?t.find(".clearing-main-next").removeClass("disabled"):t.find(".clearing-main-next").addClass("disabled"),e.prev().length>0?t.find(".clearing-main-prev").removeClass("disabled"):t.find(".clearing-main-prev").addClass("disabled")},center:function(e){return this.rtl?e.css({marginRight:-(this.outerWidth(e)/2),marginTop:-(this.outerHeight(e)/2)}):e.css({marginLeft:-(this.outerWidth(e)/2),marginTop:-(this.outerHeight(e)/2)}),this},load:function(e){if(e[0].nodeName==="A")var t=e.attr("href");else var t=e.parent().attr("href");return this.preload(e),t?t:e.attr("src")},preload:function(e){this.img(e.closest("li").next()).img(e.closest("li").prev())},loaded:function(e,t){function n(){t()}function r(){this.one("load",n);if(/MSIE (\d+\.\d+);/.test(navigator.userAgent)){var e=this.attr("src"),t=e.match(/\?/)?"&":"?";t+="random="+(new Date).getTime(),this.attr("src",e+t)}}if(!e.attr("src")){n();return}e[0].complete||e[0].readyState===4?n():r.call(e)},img:function(e){if(e.length){var t=new Image,n=e.find("a");n.length?t.src=n.attr("href"):t.src=e.find("img").attr("src")}return this},caption:function(e,t){var n=t.data("caption");return n?e.html(n).show():e.text("").hide(),this},go:function(e,t){var n=e.find(".visible"),r=n[t]();r.length&&r.find("img").trigger("click",[n,r])},shift:function(e,t,n){var r=t.parent(),i=this.settings.prev_index||t.index(),s=this.direction(r,e,t),o=parseInt(r.css("left"),10),u=this.outerWidth(t),a;t.index()!==i&&!/skip/.test(s)?/left/.test(s)?(this.lock(),r.animate({left:o+u},300,this.unlock())):/right/.test(s)&&(this.lock(),r.animate({left:o-u},300,this.unlock())):/skip/.test(s)&&(a=t.index()-this.settings.up_count,this.lock(),a>0?r.animate({left:-(a*u)},300,this.unlock()):r.animate({left:0},300,this.unlock())),n()},direction:function(t,n,r){var i=t.find("li"),s=this.outerWidth(i)+this.outerWidth(i)/4,o=Math.floor(this.outerWidth(e(".clearing-container"))/s)-1,u=i.index(r),a;return this.settings.up_count=o,this.adjacent(this.settings.prev_index,u)?u>o&&u>this.settings.prev_index?a="right":u>o-1&&u<=this.settings.prev_index?a="left":a=!1:a="skip",this.settings.prev_index=u,a},adjacent:function(e,t){for(var n=t+1;n>=t-1;n--)if(n===e)return!0;return!1},lock:function(){this.settings.locked=!0},unlock:function(){this.settings.locked=!1},locked:function(){return this.settings.locked},outerHTML:function(e){return e.outerHTML||(new XMLSerializer).serializeToString(e)},off:function(){e(this.scope).off(".fndtn.clearing"),e(t).off(".fndtn.clearing"),this.remove_data(),this.settings.init=!1},reflow:function(){this.init()}}}(Foundation.zj,this,this.document),function(e,t,n,r){"use strict";Foundation.libs.section={name:"section",version:"4.2.3",settings:{deep_linking:!1,small_breakpoint:768,one_up:!0,section_selector:"[data-section]",region_selector:"section, .section, [data-section-region]",title_selector:".title, [data-section-title]",active_region_selector:"section.active, .section.active, .active[data-section-region]",content_selector:".content, [data-section-content]",nav_selector:'[data-section="vertical-nav"], [data-section="horizontal-nav"]',callback:function(){}},init:function(t,n,r){var i=this;return Foundation.inherit(this,"throttle data_options position_right offset_right"),typeof n=="object"&&e.extend(!0,i.settings,n),typeof n!="string"?(this.set_active_from_hash(),this.events(),!0):this[n].call(this,r)},events:function(){var r=this;e(this.scope).on("click.fndtn.section","[data-section] .title, [data-section] [data-section-title]",function(t){var n=e(this),i=n.closest(r.settings.region_selector);i.children(r.settings.content_selector).length>0&&(r.toggle_active.call(this,t,r),r.reflow())}),e(t).on("resize.fndtn.section",r.throttle(function(){r.resize.call(this)},30)).on("hashchange",function(){r.settings.toggled||(r.set_active_from_hash(),e(this).trigger("resize"))}).trigger("resize"),e(n).on("click.fndtn.section",function(t){e(t.target).closest(r.settings.title_selector).length<1&&e(r.settings.nav_selector).children(r.settings.region_selector).removeClass("active").attr("style","")})},toggle_active:function(t,n){var r=e(this),n=Foundation.libs.section,i=r.closest(n.settings.region_selector),s=r.siblings(n.settings.content_selector),o=i.parent(),u=e.extend({},n.settings,n.data_options(o)),a=o.children(n.settings.active_region_selector);n.settings.toggled=!0,!u.deep_linking&&s.length>0&&t.preventDefault();if(i.hasClass("active"))(n.small(o)||n.is_vertical_nav(o)||n.is_horizontal_nav(o)||n.is_accordion(o))&&(a[0]!==i[0]||a[0]===i[0]&&!u.one_up)&&i.removeClass("active").attr("style","");else{var a=o.children(n.settings.active_region_selector),f=n.outerHeight(i.children(n.settings.title_selector));if(n.small(o)||u.one_up)n.small(o)?a.attr("style",""):a.attr("style","visibility: hidden; padding-top: "+f+"px;");n.small(o)?i.attr("style",""):i.css("padding-top",f),i.addClass("active"),a.length>0&&a.removeClass("active").attr("style",""),n.is_vertical_tabs(o)&&(s.css("display","block"),a!==null&&a.children(n.settings.content_selector).css("display","none"))}setTimeout(function(){n.settings.toggled=!1},300),u.callback()},resize:function(){var t=Foundation.libs.section,n=e(t.settings.section_selector);n.each(function(){var n=e(this),r=n.children(t.settings.active_region_selector),i=e.extend({},t.settings,t.data_options(n));if(r.length>1)r.not(":first").removeClass("active").attr("style","");else if(r.length<1&&!t.is_vertical_nav(n)&&!t.is_horizontal_nav(n)&&!t.is_accordion(n)){var s=n.children(t.settings.region_selector).first();(i.one_up||!t.small(n))&&s.addClass("active"),t.small(n)?s.attr("style",""):s.css("padding-top",t.outerHeight(s.children(t.settings.title_selector)))}t.small(n)?r.attr("style",""):r.css("padding-top",t.outerHeight(r.children(t.settings.title_selector))),t.position_titles(n),t.is_horizontal_nav(n)&&!t.small(n)||t.is_vertical_tabs(n)&&!t.small(n)?t.position_content(n):t.position_content(n,!1)})},is_vertical_nav:function(e){return/vertical-nav/i.test(e.data("section"))},is_horizontal_nav:function(e){return/horizontal-nav/i.test(e.data("section"))},is_accordion:function(e){return/accordion/i.test(e.data("section"))},is_horizontal_tabs:function(e){return/^tabs$/i.test(e.data("section"))},is_vertical_tabs:function(e){return/vertical-tabs/i.test(e.data("section"))},set_active_from_hash:function(){var n=t.location.hash.substring(1),r=e("[data-section]"),i=this;r.each(function(){var t=e(this),r=e.extend({},i.settings,i.data_options(t));if(n.length>0&&r.deep_linking){var s=t.children(i.settings.region_selector).attr("style","").removeClass("active"),o=s.map(function(){var t=e(i.settings.content_selector,this),r=t.data("slug");if((new RegExp(r,"i")).test(n))return t}),u=o.length;for(var a=u-1;a>=0;a--)e(o[a]).parent().addClass("active")}})},position_titles:function(t,n){var r=this,i=t.children(this.settings.region_selector).map(function(){return e(this).children(r.settings.title_selector)}),s=0,o=0,r=this;typeof n=="boolean"?i.attr("style",""):i.each(function(){r.is_vertical_tabs(t)?(e(this).css("top",o),o+=r.outerHeight(e(this))):(r.rtl?e(this).css("right",s):e(this).css("left",s),s+=r.outerWidth(e(this)))})},position_content:function(t,n){var r=this,i=t.children(r.settings.region_selector),s=i.map(function(){return e(this).children(r.settings.title_selector)}),o=i.map(function(){return e(this).children(r.settings.content_selector)});if(typeof n=="boolean")o.attr("style",""),t.attr("style",""),o.css("minHeight",""),o.css("maxWidth","");else if(r.is_vertical_tabs(t)&&!r.small(t)){var u=0,a=Number.MAX_VALUE,f=null;i.each(function(){var n=e(this),i=n.children(r.settings.title_selector),s=n.children(r.settings.content_selector),o=0;f=r.outerWidth(i),o=r.outerWidth(t)-f,o<a&&(a=o),u+=r.outerHeight(i),e(this).hasClass("active")||s.css("display","none")}),i.each(function(){var t=e(this).children(r.settings.content_selector);t.css("minHeight",u),t.css("maxWidth",a-2)})}else i.each(function(){var t=e(this),n=t.children(r.settings.title_selector),i=t.children(r.settings.content_selector);r.rtl?i.css({right:r.position_right(n)+1,top:r.outerHeight(n)-2}):i.css({left:n.position().left-1,top:r.outerHeight(n)-2})}),typeof Zepto=="function"?t.height(this.outerHeight(e(s[0]))):t.height(this.outerHeight(e(s[0]))-2)},position_right:function(t){var n=this,r=t.closest(this.settings.section_selector),i=r.children(this.settings.region_selector),s=t.closest(this.settings.section_selector).width(),o=i.map(function(){return e(this).children(n.settings.title_selector)}).length;return s-t.position().left-t.width()*(t.index()+1)-o},reflow:function(t){var t=t||n;e(this.settings.section_selector,t).trigger("resize")},small:function(t){var n=e.extend({},this.settings,this.data_options(t));return this.is_horizontal_tabs(t)?!1:t&&this.is_accordion(t)?!0:e("html").hasClass("lt-ie9")?!0:e("html").hasClass("ie8compat")?!0:e(this.scope).width()<n.small_breakpoint},off:function(){e(this.scope).off(".fndtn.section"),e(t).off(".fndtn.section"),e(n).off(".fndtn.section")}}}(Foundation.zj,this,this.document),function(e,t,n,r){"use strict";Foundation.libs.topbar={name:"topbar",version:"4.2.3",settings:{index:0,stickyClass:"sticky",custom_back_text:!0,back_text:"Back",is_hover:!0,scrolltop:!0,init:!1},init:function(n,r,i){Foundation.inherit(this,"data_options");var s=this;return typeof r=="object"?e.extend(!0,this.settings,r):typeof i!="undefined"&&e.extend(!0,this.settings,i),typeof r!="string"?(e(".top-bar, [data-topbar]").each(function(){e.extend(!0,s.settings,s.data_options(e(this))),s.settings.$w=e(t),s.settings.$topbar=e(this),s.settings.$section=s.settings.$topbar.find("section"),s.settings.$titlebar=s.settings.$topbar.children("ul").first(),s.settings.$topbar.data("index",0);var n=e("<div class='top-bar-js-breakpoint'/>").insertAfter(s.settings.$topbar);s.settings.breakPoint=n.width(),n.remove(),s.assemble(),s.settings.$topbar.parent().hasClass("fixed")&&e("body").css("padding-top",s.outerHeight(s.settings.$topbar))}),s.settings.init||this.events(),this.settings.init):this[r].call(this,i)},events:function(){var n=this,r=this.outerHeight(e(".top-bar, [data-topbar]"));e(this.scope).off(".fndtn.topbar").on("click.fndtn.topbar",".top-bar .toggle-topbar, [data-topbar] .toggle-topbar",function(i){var s=e(this).closest(".top-bar, [data-topbar]"),o=s.find("section, .section"),u=s.children("ul").first();i.preventDefault(),n.breakpoint()&&(n.rtl?(o.css({right:"0%"}),o.find(">.name").css({right:"100%"})):(o.css({left:"0%"}),o.find(">.name").css({left:"100%"})),o.find("li.moved").removeClass("moved"),s.data("index",0),s.toggleClass("expanded").css("height","")),s.hasClass("expanded")?s.parent().hasClass("fixed")&&(s.parent().removeClass("fixed"),s.addClass("fixed"),e("body").css("padding-top","0"),n.settings.scrolltop&&t.scrollTo(0,0)):s.hasClass("fixed")&&(s.parent().addClass("fixed"),s.removeClass("fixed"),e("body").css("padding-top",r))}).on("mouseenter mouseleave",".top-bar li",function(t){if(!n.settings.is_hover)return;/enter|over/i.test(t.type)?e(this).addClass("hover"):e(this).removeClass("hover")}).on("click.fndtn.topbar",".top-bar li.has-dropdown",function(t){if(n.breakpoint())return;var r=e(this),i=e(t.target),s=r.closest("[data-topbar], .top-bar"),o=s.data("topbar");if(n.settings.is_hover&&!Modernizr.touch)return;t.stopImmediatePropagation(),i[0].nodeName==="A"&&i.parent().hasClass("has-dropdown")&&t.preventDefault(),r.hasClass("hover")?r.removeClass("hover").find("li").removeClass("hover"):r.addClass("hover")}).on("click.fndtn.topbar",".top-bar .has-dropdown>a, [data-topbar] .has-dropdown>a",function(t){if(n.breakpoint()){t.preventDefault();var r=e(this),i=r.closest(".top-bar, [data-topbar]"),s=i.find("section, .section"),o=i.children("ul").first(),u=r.next(".dropdown").outerHeight(),a=r.closest("li");i.data("index",i.data("index")+1),a.addClass("moved"),n.rtl?(s.css({right:-(100*i.data("index"))+"%"}),s.find(">.name").css({right:100*i.data("index")+"%"})):(s.css({left:-(100*i.data("index"))+"%"}),s.find(">.name").css({left:100*i.data("index")+"%"})),i.css("height",n.outerHeight(r.siblings("ul"),!0)+n.outerHeight(o,!0))}}),e(t).on("resize.fndtn.topbar",function(){n.breakpoint()||e(".top-bar, [data-topbar]").css("height","").removeClass("expanded").find("li").removeClass("hover")}.bind(this)),e("body").on("click.fndtn.topbar",function(t){var n=e(t.target).closest("[data-topbar], .top-bar");if(n.length>0)return;e(".top-bar li, [data-topbar] li").removeClass("hover")}),e(this.scope).on("click.fndtn",".top-bar .has-dropdown .back, [data-topbar] .has-dropdown .back",function(t){t.preventDefault();var r=e(this),i=r.closest(".top-bar, [data-topbar]"),s=i.children("ul").first(),o=i.find("section, .section"),u=r.closest("li.moved"),a=u.parent();i.data("index",i.data("index")-1),n.rtl?(o.css({right:-(100*i.data("index"))+"%"}),o.find(">.name").css({right:100*i.data("index")+"%"})):(o.css({left:-(100*i.data("index"))+"%"}),o.find(">.name").css({left:100*i.data("index")+"%"})),i.data("index")===0?i.css("height",""):i.css("height",n.outerHeight(a,!0)+n.outerHeight(s,!0)),setTimeout(function(){u.removeClass("moved")},300)})},breakpoint:function(){return e(n).width()<=this.settings.breakPoint||e("html").hasClass("lt-ie9")},assemble:function(){var t=this;this.settings.$section.detach(),this.settings.$section.find(".has-dropdown>a").each(function(){var n=e(this),r=n.siblings(".dropdown"),i=n.attr("href");if(i&&i.length>1)var s=e('<li class="title back js-generated"><h5><a href="#"></a></h5></li><li><a class="parent-link js-generated" href="'+i+'">'+n.text()+"</a></li>");else var s=e('<li class="title back js-generated"><h5><a href="#"></a></h5></li>');t.settings.custom_back_text==1?s.find("h5>a").html("&laquo; "+t.settings.back_text):s.find("h5>a").html("&laquo; "+n.html()),r.prepend(s)}),this.settings.$section.appendTo(this.settings.$topbar),this.sticky()},height:function(t){var n=0,r=this;return t.find("> li").each(function(){n+=r.outerHeight(e(this),!0)}),n},sticky:function(){var n="."+this.settings.stickyClass;if(e(n).length>0){var r=e(n).length?e(n).offset().top:0,i=e(t),s=this.outerHeight(e(".top-bar"));e(t).resize(function(){clearTimeout(t_top),t_top=setTimeout(function(){r=e(n).offset().top},105)}),i.scroll(function(){i.scrollTop()>r?(e(n).addClass("fixed"),e("body").css("padding-top",s)):i.scrollTop()<=r&&(e(n).removeClass("fixed"),e("body").css("padding-top","0"))})}},off:function(){e(this.scope).off(".fndtn.topbar"),e(t).off(".fndtn.topbar")},reflow:function(){}}}(Foundation.zj,this,this.document);


(function(t,e,i){function o(i,o,n){var r=e.createElement(i);return o&&(r.id=te+o),n&&(r.style.cssText=n),t(r)}function n(){return i.innerHeight?i.innerHeight:t(i).height()}function r(t){var e=E.length,i=(j+t)%e;return 0>i?e+i:i}function l(t,e){return Math.round((/%/.test(t)?("x"===e?H.width():n())/100:1)*parseInt(t,10))}function h(t,e){return t.photo||t.photoRegex.test(e)}function s(t,e){return t.retinaUrl&&i.devicePixelRatio>1?e.replace(t.photoRegex,t.retinaSuffix):e}function a(t){"contains"in v[0]&&!v[0].contains(t.target)&&(t.stopPropagation(),v.focus())}function d(){var e,i=t.data(A,Z);null==i?(O=t.extend({},Y),console&&console.log&&console.log("Error: cboxElement missing settings object")):O=t.extend({},i);for(e in O)t.isFunction(O[e])&&"on"!==e.slice(0,2)&&(O[e]=O[e].call(A));O.rel=O.rel||A.rel||t(A).data("rel")||"nofollow",O.href=O.href||t(A).attr("href"),O.title=O.title||A.title,"string"==typeof O.href&&(O.href=t.trim(O.href))}function c(i,o){t(e).trigger(i),se.trigger(i),t.isFunction(o)&&o.call(A)}function u(){var t,e,i,o,n,r=te+"Slideshow_",l="click."+te;O.slideshow&&E[1]?(e=function(){clearTimeout(t)},i=function(){(O.loop||E[j+1])&&(t=setTimeout(J.next,O.slideshowSpeed))},o=function(){R.html(O.slideshowStop).unbind(l).one(l,n),se.bind(ne,i).bind(oe,e).bind(re,n),v.removeClass(r+"off").addClass(r+"on")},n=function(){e(),se.unbind(ne,i).unbind(oe,e).unbind(re,n),R.html(O.slideshowStart).unbind(l).one(l,function(){J.next(),o()}),v.removeClass(r+"on").addClass(r+"off")},O.slideshowAuto?o():n()):v.removeClass(r+"off "+r+"on")}function p(i){G||(A=i,d(),E=t(A),j=0,"nofollow"!==O.rel&&(E=t("."+ee).filter(function(){var e,i=t.data(this,Z);return i&&(e=t(this).data("rel")||i.rel||this.rel),e===O.rel}),j=E.index(A),-1===j&&(E=E.add(A),j=E.length-1)),g.css({opacity:parseFloat(O.opacity),cursor:O.overlayClose?"pointer":"auto",visibility:"visible"}).show(),V&&v.add(g).removeClass(V),O.className&&v.add(g).addClass(O.className),V=O.className,O.closeButton?P.html(O.close).appendTo(x):P.appendTo("<div/>"),$||($=q=!0,v.css({visibility:"hidden",display:"block"}),W=o(ae,"LoadedContent","width:0; height:0; overflow:hidden"),x.css({width:"",height:""}).append(W),_=b.height()+k.height()+x.outerHeight(!0)-x.height(),D=T.width()+C.width()+x.outerWidth(!0)-x.width(),N=W.outerHeight(!0),z=W.outerWidth(!0),O.w=l(O.initialWidth,"x"),O.h=l(O.initialHeight,"y"),J.position(),u(),c(ie,O.onOpen),B.add(S).hide(),v.focus(),O.trapFocus&&e.addEventListener&&(e.addEventListener("focus",a,!0),se.one(le,function(){e.removeEventListener("focus",a,!0)})),O.returnFocus&&se.one(le,function(){t(A).focus()})),w())}function f(){!v&&e.body&&(X=!1,H=t(i),v=o(ae).attr({id:Z,"class":t.support.opacity===!1?te+"IE":"",role:"dialog",tabindex:"-1"}).hide(),g=o(ae,"Overlay").hide(),L=t([o(ae,"LoadingOverlay")[0],o(ae,"LoadingGraphic")[0]]),y=o(ae,"Wrapper"),x=o(ae,"Content").append(S=o(ae,"Title"),M=o(ae,"Current"),K=t('<button type="button"/>').attr({id:te+"Previous"}),I=t('<button type="button"/>').attr({id:te+"Next"}),R=o("button","Slideshow"),L),P=t('<button type="button"/>').attr({id:te+"Close"}),y.append(o(ae).append(o(ae,"TopLeft"),b=o(ae,"TopCenter"),o(ae,"TopRight")),o(ae,!1,"clear:left").append(T=o(ae,"MiddleLeft"),x,C=o(ae,"MiddleRight")),o(ae,!1,"clear:left").append(o(ae,"BottomLeft"),k=o(ae,"BottomCenter"),o(ae,"BottomRight"))).find("div div").css({"float":"left"}),F=o(ae,!1,"position:absolute; width:9999px; visibility:hidden; display:none"),B=I.add(K).add(M).add(R),t(e.body).append(g,v.append(y,F)))}function m(){function i(t){t.which>1||t.shiftKey||t.altKey||t.metaKey||t.ctrlKey||(t.preventDefault(),p(this))}return v?(X||(X=!0,I.click(function(){J.next()}),K.click(function(){J.prev()}),P.click(function(){J.close()}),g.click(function(){O.overlayClose&&J.close()}),t(e).bind("keydown."+te,function(t){var e=t.keyCode;$&&O.escKey&&27===e&&(t.preventDefault(),J.close()),$&&O.arrowKey&&E[1]&&!t.altKey&&(37===e?(t.preventDefault(),K.click()):39===e&&(t.preventDefault(),I.click()))}),t.isFunction(t.fn.on)?t(e).on("click."+te,"."+ee,i):t("."+ee).live("click."+te,i)),!0):!1}function w(){var n,r,a,u=J.prep,p=++de;q=!0,U=!1,A=E[j],d(),c(he),c(oe,O.onLoad),O.h=O.height?l(O.height,"y")-N-_:O.innerHeight&&l(O.innerHeight,"y"),O.w=O.width?l(O.width,"x")-z-D:O.innerWidth&&l(O.innerWidth,"x"),O.mw=O.w,O.mh=O.h,O.maxWidth&&(O.mw=l(O.maxWidth,"x")-z-D,O.mw=O.w&&O.w<O.mw?O.w:O.mw),O.maxHeight&&(O.mh=l(O.maxHeight,"y")-N-_,O.mh=O.h&&O.h<O.mh?O.h:O.mh),n=O.href,Q=setTimeout(function(){L.show()},100),O.inline?(a=o(ae).hide().insertBefore(t(n)[0]),se.one(he,function(){a.replaceWith(W.children())}),u(t(n))):O.iframe?u(" "):O.html?u(O.html):h(O,n)?(n=s(O,n),U=e.createElement("img"),t(U).addClass(te+"Photo").bind("error",function(){O.title=!1,u(o(ae,"Error").html(O.imgError))}).one("load",function(){var e;p===de&&(U.alt=t(A).attr("alt")||t(A).attr("data-alt")||"",O.retinaImage&&i.devicePixelRatio>1&&(U.height=U.height/i.devicePixelRatio,U.width=U.width/i.devicePixelRatio),O.scalePhotos&&(r=function(){U.height-=U.height*e,U.width-=U.width*e},O.mw&&U.width>O.mw&&(e=(U.width-O.mw)/U.width,r()),O.mh&&U.height>O.mh&&(e=(U.height-O.mh)/U.height,r())),O.h&&(U.style.marginTop=Math.max(O.mh-U.height,0)/2+"px"),E[1]&&(O.loop||E[j+1])&&(U.style.cursor="pointer",U.onclick=function(){J.next()}),U.style.width=U.width+"px",U.style.height=U.height+"px",setTimeout(function(){u(U)},1))}),setTimeout(function(){U.src=n},1)):n&&F.load(n,O.data,function(e,i){p===de&&u("error"===i?o(ae,"Error").html(O.xhrError):t(this).contents())})}var g,v,y,x,b,T,C,k,E,H,W,F,L,S,M,R,I,K,P,B,O,_,D,N,z,A,j,U,$,q,G,Q,J,V,X,Y={transition:"elastic",speed:300,fadeOut:300,width:!1,initialWidth:"600",innerWidth:!1,maxWidth:!1,height:!1,initialHeight:"450",innerHeight:!1,maxHeight:!1,scalePhotos:!0,scrolling:!0,inline:!1,html:!1,iframe:!1,fastIframe:!0,photo:!1,href:!1,title:!1,rel:!1,opacity:.9,preloading:!0,className:!1,retinaImage:!1,retinaUrl:!1,retinaSuffix:"@2x.$1",current:"image {current} of {total}",previous:"previous",next:"next",close:"close",xhrError:"This content failed to load.",imgError:"This image failed to load.",open:!1,returnFocus:!0,trapFocus:!0,reposition:!0,loop:!0,slideshow:!1,slideshowAuto:!0,slideshowSpeed:2500,slideshowStart:"start slideshow",slideshowStop:"stop slideshow",photoRegex:/\.(gif|png|jp(e|g|eg)|bmp|ico|webp)((#|\?).*)?$/i,onOpen:!1,onLoad:!1,onComplete:!1,onCleanup:!1,onClosed:!1,overlayClose:!0,escKey:!0,arrowKey:!0,top:!1,bottom:!1,left:!1,right:!1,fixed:!1,data:void 0,closeButton:!0},Z="colorbox",te="cbox",ee=te+"Element",ie=te+"_open",oe=te+"_load",ne=te+"_complete",re=te+"_cleanup",le=te+"_closed",he=te+"_purge",se=t("<a/>"),ae="div",de=0,ce={};t.colorbox||(t(f),J=t.fn[Z]=t[Z]=function(e,i){var o=this;if(e=e||{},f(),m()){if(t.isFunction(o))o=t("<a/>"),e.open=!0;else if(!o[0])return o;i&&(e.onComplete=i),o.each(function(){t.data(this,Z,t.extend({},t.data(this,Z)||Y,e))}).addClass(ee),(t.isFunction(e.open)&&e.open.call(o)||e.open)&&p(o[0])}return o},J.position=function(e,i){function o(){b[0].style.width=k[0].style.width=x[0].style.width=parseInt(v[0].style.width,10)-D+"px",x[0].style.height=T[0].style.height=C[0].style.height=parseInt(v[0].style.height,10)-_+"px"}var r,h,s,a=0,d=0,c=v.offset();if(H.unbind("resize."+te),v.css({top:-9e4,left:-9e4}),h=H.scrollTop(),s=H.scrollLeft(),O.fixed?(c.top-=h,c.left-=s,v.css({position:"fixed"})):(a=h,d=s,v.css({position:"absolute"})),d+=O.right!==!1?Math.max(H.width()-O.w-z-D-l(O.right,"x"),0):O.left!==!1?l(O.left,"x"):Math.round(Math.max(H.width()-O.w-z-D,0)/2),a+=O.bottom!==!1?Math.max(n()-O.h-N-_-l(O.bottom,"y"),0):O.top!==!1?l(O.top,"y"):Math.round(Math.max(n()-O.h-N-_,0)/2),v.css({top:c.top,left:c.left,visibility:"visible"}),y[0].style.width=y[0].style.height="9999px",r={width:O.w+z+D,height:O.h+N+_,top:a,left:d},e){var u=0;t.each(r,function(t){return r[t]!==ce[t]?(u=e,void 0):void 0}),e=u}ce=r,e||v.css(r),v.dequeue().animate(r,{duration:e||0,complete:function(){o(),q=!1,y[0].style.width=O.w+z+D+"px",y[0].style.height=O.h+N+_+"px",O.reposition&&setTimeout(function(){H.bind("resize."+te,J.position)},1),i&&i()},step:o})},J.resize=function(t){var e;$&&(t=t||{},t.width&&(O.w=l(t.width,"x")-z-D),t.innerWidth&&(O.w=l(t.innerWidth,"x")),W.css({width:O.w}),t.height&&(O.h=l(t.height,"y")-N-_),t.innerHeight&&(O.h=l(t.innerHeight,"y")),t.innerHeight||t.height||(e=W.scrollTop(),W.css({height:"auto"}),O.h=W.height()),W.css({height:O.h}),e&&W.scrollTop(e),J.position("none"===O.transition?0:O.speed))},J.prep=function(i){function n(){return O.w=O.w||W.width(),O.w=O.mw&&O.mw<O.w?O.mw:O.w,O.w}function l(){return O.h=O.h||W.height(),O.h=O.mh&&O.mh<O.h?O.mh:O.h,O.h}if($){var a,d="none"===O.transition?0:O.speed;W.empty().remove(),W=o(ae,"LoadedContent").append(i),W.hide().appendTo(F.show()).css({width:n(),overflow:O.scrolling?"auto":"hidden"}).css({height:l()}).prependTo(x),F.hide(),t(U).css({"float":"none"}),a=function(){function i(){t.support.opacity===!1&&v[0].style.removeAttribute("filter")}var n,l,a=E.length,u="frameBorder",p="allowTransparency";$&&(l=function(){clearTimeout(Q),L.hide(),c(ne,O.onComplete)},S.html(O.title).add(W).show(),a>1?("string"==typeof O.current&&M.html(O.current.replace("{current}",j+1).replace("{total}",a)).show(),I[O.loop||a-1>j?"show":"hide"]().html(O.next),K[O.loop||j?"show":"hide"]().html(O.previous),O.slideshow&&R.show(),O.preloading&&t.each([r(-1),r(1)],function(){var i,o,n=E[this],r=t.data(n,Z);r&&r.href?(i=r.href,t.isFunction(i)&&(i=i.call(n))):i=t(n).attr("href"),i&&h(r,i)&&(i=s(r,i),o=e.createElement("img"),o.src=i)})):B.hide(),O.iframe?(n=o("iframe")[0],u in n&&(n[u]=0),p in n&&(n[p]="true"),O.scrolling||(n.scrolling="no"),t(n).attr({src:O.href,name:(new Date).getTime(),"class":te+"Iframe",allowFullScreen:!0,webkitAllowFullScreen:!0,mozallowfullscreen:!0}).one("load",l).appendTo(W),se.one(he,function(){n.src="//about:blank"}),O.fastIframe&&t(n).trigger("load")):l(),"fade"===O.transition?v.fadeTo(d,1,i):i())},"fade"===O.transition?v.fadeTo(d,0,function(){J.position(0,a)}):J.position(d,a)}},J.next=function(){!q&&E[1]&&(O.loop||E[j+1])&&(j=r(1),p(E[j]))},J.prev=function(){!q&&E[1]&&(O.loop||j)&&(j=r(-1),p(E[j]))},J.close=function(){$&&!G&&(G=!0,$=!1,c(re,O.onCleanup),H.unbind("."+te),g.fadeTo(O.fadeOut||0,0),v.stop().fadeTo(O.fadeOut||0,0,function(){v.add(g).css({opacity:1,cursor:"auto"}).hide(),c(he),W.empty().remove(),setTimeout(function(){G=!1,c(le,O.onClosed)},1)}))},J.remove=function(){v&&(v.stop(),t.colorbox.close(),v.stop().remove(),g.remove(),G=!1,v=null,t("."+ee).removeData(Z).removeClass(ee),t(e).unbind("click."+te))},J.element=function(){return t(A)},J.settings=Y)})(jQuery,document,window);
function csrfSafeMethod(method) {
return (/^(GET|HEAD|OPTIONS|TRACE)$/.test(method));
};
$.ajaxSetup({
crossDomain: false, // obviates need for sameOrigin test
cache: false,
beforeSend: function(xhr, settings) {
if (!csrfSafeMethod(settings.type)) {
xhr.setRequestHeader("X-CSRFToken", confLH.csrf_token);
}
}
});
$.postJSON = function(url, data, callback) {
return $.post(url, data, callback, "json");
};
(function(e,t,n){"use strict";e.fn.foundationAccordion=function(t){var n=function(e){return e.hasClass("hover")&&!Modernizr.touch};e(document).on("mouseenter",".accordion-lhc li",function(){var t=e(this).parent();if(n(t)){var r=e(this).children(".content-lhc").first();e(".content-lhc",t).not(r).hide().parent("li").removeClass("active-lhc"),r.show(0,function(){r.parent("li").addClass("active-lhc")})}}),e(document).on("click.fndtn",".accordion-lhc li .title-lhc",function(){var t=e(this).closest("li"),r=t.parent();if(!n(r)){var i=t.children(".content-lhc").first();t.hasClass("active-lhc")?r.find("li").removeClass("active-lhc").end().find(".content-lhc").hide():(e(".content-lhc",r).not(i).hide().parent("li").removeClass("active-lhc"),i.show(0,function(){i.parent("li").addClass("active-lhc")}))}})}})(jQuery,this);
var LHCCallbacks = {};
function lh(){
this.wwwDir = WWW_DIR_JAVASCRIPT;
this.addmsgurl = "chat/addmsgadmin/";
this.addmsgurluser = "chat/addmsguser/";
this.addmsgurluserchatbox = "chatbox/addmsguser/";
this.syncuser = "chat/syncuser/";
this.syncadmin = "chat/syncadmin/";
this.closechatadmin = "chat/closechatadmin/";
this.deletechatadmin = "chat/deletechatadmin/";
this.checkchatstatus = "chat/checkchatstatus/";
this.syncadmininterfaceurl = "chat/syncadmininterface/";
this.accepttransfer = "chat/accepttransfer/";
this.trasnsferuser = "chat/transferuser/";
this.userclosechaturl = "chat/userclosechat/";
this.disableremember = false;
this.operatorTyping = false;
this.appendSyncArgument = '';
this.chat_id = null;
this.hash = null;
this.soundIsPlaying = false;
this.soundPlayedTimes = 0;
this.last_message_id = 0;
this.isSinchronizing = false;
this.isWidgetMode = false;
this.syncroRequestSend = false;
this.currentMessageText = '';
this.setWidgetMode = function(status) {
this.isWidgetMode = status;
};
this.setSynchronizationRequestSend = function(status)
{
this.syncroRequestSend = status;
};
this.setSyncUserURL = function(url) {
this.syncuser = url;
};
this.trackLastIDS = {};
this.chatsSynchronising = [];
this.chatsSynchronisingMsg = [];
this.notificationsArray = [];
this.underMessageAdd = false;
this.closeWindowOnChatCloseDelete = false;
this.userTimeout = false;
this.lastOnlineSyncTimeout = false;
this.setLastUserMessageID = function(message_id) {
this.last_message_id = message_id;
};
this.setChatID = function (chat_id){
this.chat_id = chat_id;
};
this.setwwwDir = function (wwwdir){
this.wwwDir = wwwdir;
};
this.setCloseWindowOnEvent = function (value)
{
this.closeWindowOnChatCloseDelete = value;
};
this.setDisableRemember = function (value)
{
this.disableremember = value;
};
this.setSynchronizationStatus = function(status)
{
this.underMessageAdd = status;
};
this.addTab = function(tabs, url, name, chat_id) {
tabs.find('> section.active').removeClass("active").attr('style','');
tabs.append('<section class="active" id="chat-tab-'+chat_id+'"><p class="title"><a class="chat-tab-item" id="chat-id-'+chat_id+'" href="#chat'+chat_id+'">'+ '<i id="user-chat-status-'+chat_id+'" class="icon-user-status icon-user icon-user-online"></i>' + name.replace(/</g,'&lt;').replace(/>/g,'&gt;') + '</a><a href="#" onclick="return lhinst.removeDialogTab('+chat_id+',$(\'#tabs\'),true)" class="icon-cancel icon-close-chat"></a></p><div class="content" data-slug="chat'+chat_id+'" id="simple'+chat_id+'Tab">...</div></section>');
$('#chat-id-'+chat_id).click(function() {
var inst = $(this);
setTimeout(function(){
inst.find('.msg-nm').remove();
inst.removeClass('has-pm');
$(document).foundation('section', 'resize');
$('#messagesBlock-'+chat_id).animate({ scrollTop: $('#messagesBlock-'+chat_id).prop('scrollHeight') }, 1000);
},500);
});
$.get(url, function(data) {
$('#simple'+chat_id+'Tab').html(data);
$(document).foundation('section', 'resize');
});
};
this.attachTabNavigator = function(){
$('#tabs > section > p.title > a.chat-tab-item').click(function(){
$(this).find('.msg-nm').remove();
$(this).removeClass('has-pm');
});
};
this.startChat = function (chat_id,tabs,name) {
if ( this.chatUnderSynchronization(chat_id) == false ) {
var rememberAppend = this.disableremember == false ? '/(remember)/true' : '';
this.addTab(tabs, this.wwwDir +'chat/adminchat/'+chat_id+rememberAppend, name, chat_id);
var inst = this;
setTimeout(function(){
inst.syncadmininterfacestatic();
},1000);
}
};
this.protectCSFR = function()
{
$('a.csfr-required').click(function(){
var inst = $(this);
if (!inst.attr('data-secured')){
inst.attr('href',inst.attr('href')+'/(csfr)/'+confLH.csrf_token);
inst.attr('data-secured',1);
}
});
};
this.setChatHash = function (hash)
{
this.hash = hash;
};
this.addSynchroChat = function (chat_id,message_id)
{
this.chatsSynchronising.push(chat_id);
this.chatsSynchronisingMsg.push(chat_id + ',' +message_id);
if (LHCCallbacks.addSynchroChat) {
LHCCallbacks.addSynchroChat(chat_id,message_id);
}
};
this.removeSynchroChat = function (chat_id)
{
var j = 0;
while (j < this.chatsSynchronising.length) {
if (this.chatsSynchronising[j] == chat_id) {
this.chatsSynchronising.splice(j, 1);
this.chatsSynchronisingMsg.splice(j, 1);
} else { j++; }
};
if (LHCCallbacks.removeSynchroChat) {
LHCCallbacks.removeSynchroChat(chat_id);
}
};
this.is_typing = false;
this.typing_timeout = null;
this.initTypingMonitoringAdmin = function(chat_id) {
var www_dir = this.wwwDir;
var inst = this;
jQuery('#CSChatMessage-'+chat_id).bind('keyup', function (evt){
if (inst.is_typing == false) {
inst.is_typing = true;
clearTimeout(inst.typing_timeout);
if (LHCCallbacks.initTypingMonitoringAdminInform) {
inst.typing_timeout = setTimeout(function(){inst.typingStoppedOperator(chat_id);},3000);
LHCCallbacks.initTypingMonitoringAdminInform({'chat_id':chat_id,'status':true});
} else {
$.getJSON(www_dir + 'chat/operatortyping/' + chat_id+'/true',{ }, function(data){
inst.typing_timeout = setTimeout(function(){inst.typingStoppedOperator(chat_id);},3000);
if (LHCCallbacks.initTypingMonitoringAdmin) {
LHCCallbacks.initTypingMonitoringAdmin(chat_id,true);
}
}).fail(function(){
inst.typing_timeout = setTimeout(function(){inst.typingStoppedOperator(chat_id);},3000);
});
}
} else {
clearTimeout(inst.typing_timeout);
inst.typing_timeout = setTimeout(function(){inst.typingStoppedOperator(chat_id);},3000);
}
});
};
this.remarksTimeout = null;
this.saveRemarks = function(chat_id) {
clearTimeout(this.remarksTimeout);
$('#remarks-status-'+chat_id).addClass('warning-color').html('...');
var inst = this;
this.remarksTimeout = setTimeout(function(){
$.postJSON(inst.wwwDir + 'chat/saveremarks/' + chat_id,{'data':$('#ChatRemarks-'+chat_id).val()}, function(data){
$('#remarks-status-'+chat_id).removeClass('warning-color').html('');
});
},500);
};
this.closeWindow  = function() {
window.open('','_self','');
window.close();
};
this.typingStoppedOperator = function(chat_id) {
var inst = this;
if (inst.is_typing == true){
if (LHCCallbacks.typingStoppedOperatorInform) {
inst.is_typing = false;
LHCCallbacks.typingStoppedOperatorInform({'chat_id':chat_id,'status':false});
} else {
$.getJSON(this.wwwDir + 'chat/operatortyping/' + chat_id+'/false',{ }, function(data){
inst.is_typing = false;
if (LHCCallbacks.initTypingMonitoringAdmin) {
LHCCallbacks.initTypingMonitoringAdmin(chat_id,false);
};
}).fail(function(){
inst.is_typing = false;
});
}
}
};
this.cancelcolorbox = function(){
$.colorbox.remove();
};
this.sendemail = function(){
$.postJSON(this.wwwDir + 'chat/sendchat/' + this.chat_id+'/'+this.hash,{csfr_token:confLH.csrf_token, email:$('input[name="UserEmail"]').val()}, function(data){
if (data.error == 'false') {
$.colorbox.remove();
} else {
$('#user-action .alert-box').remove();
$('#user-action').prepend(data.result);
$.colorbox.resize();
}
});
};
this.reopenchat = function(inst){
$.postJSON(this.wwwDir + 'chat/reopenchat/' + inst.attr('data-id'), function(data){
if (data.error == 'true') {
alert(data.result);
} else {
$('#action-block-row-'+ inst.attr('data-id')+' .send-row').removeClass('hide');
$('#CSChatMessage-'+inst.attr('data-id')).removeAttr('readonly').focus();
$('#chat-status-text-'+inst.attr('data-id')).text(data.status);
inst.remove();
}
});
};
this.initTypingMonitoringUser = function(chat_id) {
var www_dir = this.wwwDir;
var inst = this;
if (sessionStorage && sessionStorage.getItem('lhc_ttxt') && sessionStorage.getItem('lhc_ttxt') != '') {
jQuery('#CSChatMessage').val(sessionStorage.getItem('lhc_ttxt'));
}
jQuery('#CSChatMessage').bind('keyup', function (evt){
if (sessionStorage) {
sessionStorage.setItem('lhc_ttxt',$(this).val());
};
if (inst.is_typing == false) {
inst.is_typing = true;
clearTimeout(inst.typing_timeout);
if (LHCCallbacks.initTypingMonitoringUserInform) {
inst.typing_timeout = setTimeout(function(){inst.typingStoppedUser(chat_id);},3000);
LHCCallbacks.initTypingMonitoringUserInform({'chat_id':chat_id,'hash':inst.hash,'status':true,msg:$(this).val()});
} else {
$.postJSON(www_dir + 'chat/usertyping/' + chat_id+'/'+inst.hash+'/true',{msg:$(this).val()}, function(data){
inst.typing_timeout = setTimeout(function(){inst.typingStoppedUser(chat_id);},3000);
if (LHCCallbacks.initTypingMonitoringUser) {
LHCCallbacks.initTypingMonitoringUser(chat_id,true);
};
}).fail(function(){
inst.typing_timeout = setTimeout(function(){inst.typingStoppedUser(chat_id);},3000);
});
}
} else {
clearTimeout(inst.typing_timeout);
inst.typing_timeout = setTimeout(function(){inst.typingStoppedUser(chat_id);},3000);
var txtArea = $(this).val();
if (inst.currentMessageText != txtArea ) {
if ( Math.abs(inst.currentMessageText.length - txtArea.length) > 6) {
inst.currentMessageText = txtArea;
if (LHCCallbacks.initTypingMonitoringUserInform) {
LHCCallbacks.initTypingMonitoringUserInform({'chat_id':chat_id,'hash':inst.hash,'status':true,msg:txtArea});
} else {
$.postJSON(www_dir + 'chat/usertyping/' + chat_id+'/'+inst.hash+'/true',{msg:txtArea}, function(data){
if (LHCCallbacks.initTypingMonitoringUser) {
LHCCallbacks.initTypingMonitoringUser(chat_id,true);
};
});
}
}
}
}
});
};
this.typingStoppedUser = function(chat_id) {
var inst = this;
if (inst.is_typing == true){
if (LHCCallbacks.typingStoppedUserInform) {
inst.is_typing = false;
LHCCallbacks.typingStoppedUserInform({'chat_id':chat_id,'hash':this.hash,'status':false});
} else {
$.getJSON(this.wwwDir + 'chat/usertyping/' + chat_id+'/'+this.hash+'/false',{ }, function(data){
inst.is_typing = false;
if (LHCCallbacks.initTypingMonitoringUser) {
LHCCallbacks.initTypingMonitoringUser(chat_id,false);
};
}).fail(function(){
inst.is_typing = false;
});
}
}
};
this.refreshFootPrint = function(inst) {
inst.addClass('disabled');
$.get(this.wwwDir + 'chat/chatfootprint/' + inst.attr('rel'),{ }, function(data){
$('#footprint-'+inst.attr('rel')).html(data);
inst.removeClass('disabled');
});
};
this.refreshOnlineUserInfo = function(inst) {
inst.addClass('disabled');
$.get(this.wwwDir + 'chat/refreshonlineinfo/' + inst.attr('rel'),{ }, function(data){
$('#online-user-info-'+inst.attr('rel')).html(data);
inst.removeClass('disabled');
});
};
this.processCollapse = function(chat_id)
{
if (!$('#chat-main-column-'+chat_id+' .collapse-right').hasClass('icon-left-circled')){
$('#chat-right-column-'+chat_id).hide();
$('#chat-main-column-'+chat_id).addClass('large-12');
$('#chat-main-column-'+chat_id+' .collapse-right').addClass('icon-left-circled').removeClass('icon-right-circled');
} else {
$('#chat-right-column-'+chat_id).show();
$('#chat-main-column-'+chat_id).removeClass('large-12');
$('#chat-main-column-'+chat_id+' .collapse-right').removeClass('icon-left-circled').addClass('icon-right-circled');
$(document).foundation('section', 'reflow');
};
};
this.chatUnderSynchronization = function(chat_id)
{
var j = 0;
while (j < this.chatsSynchronising.length) {
if (this.chatsSynchronising[j] == chat_id) {
return true;
} else { j++; }
}
return false;
};
this.getChatIndex = function(chat_id)
{
var j = 0;
while (j < this.chatsSynchronising.length) {
if (this.chatsSynchronising[j] == chat_id) {
return j;
} else { j++; }
}
return false;
};
this.updateUserSyncInterface = function(inst,data)
{
try {
if (data.error == 'false')
{
if (data.blocked != 'true')
{
if (data.result != 'false' && data.status == 'true')
{
$('#messagesBlock').append(data.result);
$('#messagesBlock').animate({ scrollTop: $('#messagesBlock').prop('scrollHeight') }, 1000);
if ( confLH.new_message_sound_user_enabled == 1 && data.uw == 'false') {
inst.playNewMessageSound();
};
inst.last_message_id = data.message_id;
} else {
if ( data.status != 'true') $('#status-chat').html(data.status);
}
inst.userTimeout = setTimeout(chatsyncuser,confLH.chat_message_sinterval);
if (data.cs == 't') {
inst.chatsyncuserpending();
}
if ( data.ott != '' ) {
var instStatus = $('#id-operator-typing');
instStatus.find('i').html(data.ott);
instStatus.fadeIn();
inst.operatorTyping = true;
} else {
inst.operatorTyping = false;
$('#id-operator-typing').fadeOut();
}
if (data.op != '') {
$.each(data.op,function(i,item) {
if (item.indexOf('lhinst.') != -1) { // Internal operation
eval(item);
} else if (inst.isWidgetMode) {
parent.postMessage(item, '*');
} else if (window.opener) {
window.opener.postMessage(item, '*');
};
});
};
} else {
$('#status-chat').html(data.status);
$('#ChatMessageContainer').remove();
$('#ChatSendButtonContainer').remove();
}
};
} catch(err) {
inst.userTimeout = setTimeout(chatsyncuser,confLH.chat_message_sinterval);
};
inst.syncroRequestSend = false;
};
this.syncusercall = function()
{
var inst = this;
if (this.syncroRequestSend == false)
{
clearTimeout(inst.userTimeout);
this.syncroRequestSend = true;
var modeWindow = this.isWidgetMode == true ? '/(mode)/widget' : '';
var operatorTyping = this.operatorTyping == true ? '/(ot)/t' : '';
$.getJSON(this.wwwDir + this.syncuser + this.chat_id + '/'+ this.last_message_id + '/' + this.hash + modeWindow + operatorTyping ,{ }, function(data){
inst.updateUserSyncInterface(inst,data);
if (LHCCallbacks.syncusercall) {
LHCCallbacks.syncusercall(inst,data);
};
}).fail(function(){
inst.syncroRequestSend = false;
inst.userTimeout = setTimeout(chatsyncuser,confLH.chat_message_sinterval);
});
}
};
this.scheduleSync = function() {
this.syncroRequestSend = false;
this.userTimeout = setTimeout(chatsyncuser,confLH.chat_message_sinterval);
};
this.closeActiveChatDialog = function(chat_id, tabs, hidetab)
{
$.ajax({
type: "POST",
url: this.wwwDir + this.closechatadmin + chat_id,
async: false
});
if ($('#CSChatMessage-'+chat_id).length != 0){
$('#CSChatMessage-'+chat_id).unbind('keydown', 'enter', function(){});
$('#CSChatMessage-'+chat_id).unbind('keyup', 'up', function(){});
};
if (hidetab == true) {
var index = tabs.find(' > section.active').index();
tabs.find(' > section.active').remove();
tabs.find(' > section:eq(' + (index - 1) + ')').addClass("active");
$(document).foundation('section', 'resize');
if (this.closeWindowOnChatCloseDelete == true)
{
window.close();
}
};
if (LHCCallbacks.chatClosedCallback) {
LHCCallbacks.chatClosedCallback(chat_id);
};
this.removeSynchroChat(chat_id);
this.syncadmininterfacestatic();
};
this.startChatCloseTabNewWindow = function(chat_id, tabs, name)
{
window.open(this.wwwDir + 'chat/single/'+chat_id,'chatwindow-chat-id-'+chat_id,"menubar=1,resizable=1,width=800,height=650");
$.ajax({
type: "GET",
url: this.wwwDir + 'chat/adminleftchat/' + chat_id,
async: true
});
var index = tabs.find(' > section.active').index();
tabs.find(' > section.active').remove();
tabs.find(' > section:eq(' + (index - 1) + ')').addClass("active");
$(document).foundation('section', 'resize');
if (this.closeWindowOnChatCloseDelete == true)
{
window.close();
};
this.removeSynchroChat(chat_id);
this.syncadmininterfacestatic();
return false;
};
this.removeDialogTab = function(chat_id, tabs, hidetab)
{
if ($('#CSChatMessage-'+chat_id).length != 0){
$('#CSChatMessage-'+chat_id).unbind('keydown', 'enter', function(){});
$('#CSChatMessage-'+chat_id).unbind('keyup', 'up', function(){});
}
this.removeSynchroChat(chat_id);
if (hidetab == true) {
$.ajax({
type: "GET",
url: this.wwwDir + 'chat/adminleftchat/' + chat_id,
async: true
});
var index = tabs.find(' > #chat-tab-'+chat_id).index();
tabs.find(' > #chat-tab-'+chat_id).remove();
tabs.find(' > section:eq(' + (index - 1) + ')').addClass("active");
$(document).foundation('section', 'resize');
if (this.closeWindowOnChatCloseDelete == true)
{
window.close();
};
};
this.syncadmininterfacestatic();
};
this.removeActiveDialogTag = function(tabs) {
var index = tabs.find(' > section.active').index();
tabs.find(' > section.active').remove();
tabs.find(' > section:eq(' + (index - 1) + ')').addClass("active");
$(document).foundation('section', 'resize');
if (this.closeWindowOnChatCloseDelete == true)
{
window.close();
};
};
this.deleteChat = function(chat_id, tabs, hidetab)
{
if ($('#CSChatMessage-'+chat_id).length != 0){
$('#CSChatMessage-'+chat_id).unbind('keydown', 'enter', function(){});
$('#CSChatMessage-'+chat_id).unbind('keyup', 'up', function(){});
}
$.ajax({
type: "POST",
url: this.wwwDir + this.deletechatadmin + chat_id,
cache: false,
dataType: 'json',
async: false
}).done(function(data){
if (data.error == 'true')
{
alert(data.result);
}
});
if (hidetab == true) {
var index = tabs.find(' > section.active').index();
tabs.find(' > section.active').remove();
tabs.find(' > section:eq(' + (index - 1) + ')').addClass("active");
$(document).foundation('section', 'resize');
if (this.closeWindowOnChatCloseDelete == true)
{
window.close();
}
};
if (LHCCallbacks.chatDeletedCallback) {
LHCCallbacks.chatDeletedCallback(chat_id);
};
this.syncadmininterfacestatic();
this.removeSynchroChat(chat_id);
};
this.rejectPendingChat = function(chat_id, tabs)
{
$.postJSON(this.wwwDir + this.deletechatadmin + chat_id ,{}, function(data){
});
this.syncadmininterfacestatic();
};
this.startChatNewWindow = function(chat_id,name)
{
window.open(this.wwwDir + 'chat/single/'+chat_id,'chatwindow-chat-id-'+chat_id,"menubar=1,resizable=1,width=800,height=650").focus();
var inst = this;
setTimeout(function(){
inst.syncadmininterfacestatic();
},1000);
return false;
};
this.startCoBrowse = function(chat_id)
{
window.open(this.wwwDir + 'cobrowse/browse/'+chat_id,'chatwindow-cobrowse-chat-id-'+chat_id,"menubar=1,resizable=1,width=800,height=650").focus();
return false;
};
this.startChatTransfer = function(chat_id,tabs,name,transfer_id){
var inst = this;
$.getJSON(this.wwwDir + this.accepttransfer + transfer_id ,{}, function(data){
inst.startChat(chat_id,tabs,name);
if (LHCCallbacks.operatorAcceptedTransfer) {
LHCCallbacks.operatorAcceptedTransfer(chat_id);
};
}).fail(function(){
inst.startChat(chat_id,tabs,name);
});
};
this.startChatNewWindowTransfer = function(chat_id,name,transfer_id)
{
$.getJSON(this.wwwDir + this.accepttransfer + transfer_id ,{}, function(data){
if (LHCCallbacks.operatorAcceptedTransfer) {
LHCCallbacks.operatorAcceptedTransfer(chat_id);
};
});
return this.startChatNewWindow(chat_id,name);
};
this.startChatNewWindowTransferByTransfer = function(transfer_id)
{
var inst = this;
$.ajax({
type: "GET",
url: this.wwwDir + this.accepttransfer + transfer_id,
cache: false,
dataType: 'json',
async: false
}).done(function(data){
inst.startChatNewWindow(data.chat_id,'');
if (LHCCallbacks.operatorAcceptedTransfer) {
LHCCallbacks.operatorAcceptedTransfer(data.chat_id);
};
});
this.syncadmininterfacestatic();
return false;
};
this.blockUser = function(chat_id,msg) {
if (confirm(msg)) {
$.postJSON(this.wwwDir + 'chat/blockuser/' + chat_id,{}, function(data){
alert(data.msg);
});
}
};
this.switchLang = function(form,lang){
var languageAppend = '<input type="hidden" value="'+lang+'" name="switchLang" />';
form.append(languageAppend);
form.submit();
return false;
},
this.sendMail = function(chat_id) {
$.colorbox({iframe:true, width:'90%',height:'90%', href:this.wwwDir + 'chat/sendmail/'+chat_id});
};
this.modifyChat = function(chat_id) {
$.colorbox({iframe:true, width:'90%',height:'90%', href:this.wwwDir + 'chat/modifychat/'+chat_id});
};
this.attatchLinkToFile = function(chat_id) {
$.colorbox({iframe:true, width:'90%',height:'90%', href:this.wwwDir + 'file/attatchfile/'+chat_id});
};
this.embedFileSendMail = function(chat_id) {
$.colorbox({iframe:true, width:'90%',height:'90%', href:this.wwwDir + 'file/attatchfilemail'});
};
this.sendLinkToMail = function( embed_code,file_id) {
var val = window.parent.$('#MailMessage').val();
window.parent.$('#MailMessage').val(((val != '') ? val+"\n" : val)+embed_code);
$('#embed-button-'+file_id).addClass('success');
},
this.sendLinkToEditor = function(chat_id, embed_code,file_id) {
var val = window.parent.$('#CSChatMessage-'+chat_id).val();
window.parent.$('#CSChatMessage-'+chat_id).val(((val != '') ? val+"\n" : val)+embed_code);
$('#embed-button-'+file_id).addClass('success');
},
this.sendMailArchive = function(archive_id,chat_id) {
$.colorbox({iframe:true, width:'550px',height:'500px', href:this.wwwDir + 'chatarchive/sendmail/'+archive_id+'/'+chat_id});
};
this.transferChat = function(chat_id)
{
var user_id = $('[name=TransferTo'+chat_id+']:checked').val();
$.postJSON(this.wwwDir + this.trasnsferuser + chat_id + '/' + user_id ,{'type':'user'}, function(data){
if (data.error == 'false') {
$('#transfer-block-'+data.chat_id).html(data.result);
};
});
};
this.previewChat = function(chat_id)
{
$.colorbox({'iframe':true,height:'500px',width:'500px', href:this.wwwDir+'chat/previewchat/'+chat_id});
};
this.redirectContact = function(chat_id,message){
if (confirm(message)){
$.postJSON(this.wwwDir + 'chat/redirectcontact/' + chat_id, function(data){
lhinst.syncadmininterfacestatic();
if (LHCCallbacks.userRedirectedContact) {
LHCCallbacks.userRedirectedContact(chat_id);
};
});
}
};
this.redirectToURL = function(chat_id,trans) {
var url = prompt(trans, "");
if (url != null) {
lhinst.addRemoteCommand(chat_id,'lhc_chat_redirect:'+url.replace(new RegExp(':','g'),'__SPLIT__'));
}
};
this.transferChatDep = function(chat_id)
{
var user_id = $('[name=DepartamentID'+chat_id+']:checked').val();
$.postJSON(this.wwwDir + this.trasnsferuser + chat_id + '/' + user_id ,{'type':'dep'}, function(data){
if (data.error == 'false') {
$('#transfer-block-'+data.chat_id).html(data.result);
};
});
};
this.chatTabsOpen = function ()
{
window.open(this.wwwDir + 'chat/chattabs/','chatwindows',"menubar=1,resizable=1,width=800,height=650");
return false;
};
this.userclosedchat = function()
{
if (LHCCallbacks.userleftchatNotification) {
LHCCallbacks.userleftchatNotification(this.chat_id);
};
$.ajax({
type: "GET",
url: this.wwwDir + this.userclosechaturl + this.chat_id + '/' + this.hash,
cache: false,
async: false
});
};
this.userclosedchatembed = function()
{
if (!!window.postMessage) {
parent.postMessage("lhc_close", '*');
};
};
this.userclosedchatandbrowser = function()
{
if (LHCCallbacks.userleftchatNotification) {
LHCCallbacks.userleftchatNotification(this.chat_id);
};
$.get(this.wwwDir + this.userclosechaturl + this.chat_id + '/' + this.hash,function(data){
lhinst.closeWindow();
});
};
this.sendCannedMessage = function(chat_id,link_inst)
{
if ($('#id_CannedMessage-'+chat_id).val() > 0) {
link_inst.addClass('secondary');
var delayMiliseconds = parseInt($('#id_CannedMessage-'+chat_id).find(':selected').attr('data-delay'))*1000;
var www_dir = this.wwwDir;
var inst  = this;
if (inst.is_typing == false) {
inst.is_typing = true;
clearTimeout(inst.typing_timeout);
if (LHCCallbacks.initTypingMonitoringAdminInform) {
LHCCallbacks.initTypingMonitoringAdminInform({'chat_id':chat_id,'status':true});
};
$.getJSON(www_dir + 'chat/operatortyping/' + chat_id+'/true',{ }, function(data){
if (LHCCallbacks.initTypingMonitoringAdmin) {
LHCCallbacks.initTypingMonitoringAdmin(chat_id,true);
};
inst.typing_timeout = setTimeout(function(){inst.typingStoppedOperator(chat_id);link_inst.removeClass('secondary');},(delayMiliseconds > 3000 ? delayMiliseconds : 3000));
}).fail(function(){
inst.typing_timeout = setTimeout(function(){inst.typingStoppedOperator(chat_id);},3000);
});
} else {
clearTimeout(inst.typing_timeout);
inst.typing_timeout = setTimeout(function(){inst.typingStoppedOperator(chat_id);},3000);
link_inst.removeClass('secondary');
};
if (delayMiliseconds > 0) {
setTimeout(function(){
var pdata = {
msg	: $('#id_CannedMessage-'+chat_id).find(':selected').text()
};
$('#CSChatMessage-'+chat_id).val('');
$.postJSON(www_dir + inst.addmsgurl + chat_id, pdata , function(data){
if (LHCCallbacks.addmsgadmin) {
LHCCallbacks.addmsgadmin(chat_id);
};
lhinst.syncadmincall();
return true;
});
},delayMiliseconds);
} else {
var pdata = {
msg	: $('#id_CannedMessage-'+chat_id).find(':selected').text()
};
$('#CSChatMessage-'+chat_id).val('');
$.postJSON(this.wwwDir + this.addmsgurl + chat_id, pdata , function(data){
if (LHCCallbacks.addmsgadmin) {
LHCCallbacks.addmsgadmin(chat_id);
};
lhinst.syncadmincall();
return true;
});
}
};
return false;
};
this.voteAction = function(inst) {
var chat_id = this.chat_id;
$.postJSON(this.wwwDir + 'chat/voteaction/' + this.chat_id + '/' + this.hash + '/' + inst.attr('data-id') ,{}, function(data){
if (data.error == 'false')
{
if (LHCCallbacks.uservoted) {
LHCCallbacks.uservoted(chat_id);
};
if (data.status == 0) {
$('.icon-thumbs-up').removeClass('up-voted');
$('.icon-thumbs-down').removeClass('down-voted');
} else if (data.status == 1) {
$('.icon-thumbs-up').addClass('up-voted');
$('.icon-thumbs-down').removeClass('down-voted');
} else if (data.status == 2) {
$('.icon-thumbs-up').removeClass('up-voted');
$('.icon-thumbs-down').addClass('down-voted');
}
}
});
};
this.chatsyncuserpending = function ()
{
var modeWindow = this.isWidgetMode == true ? '/(mode)/widget' : '';
var inst = this;
$.getJSON(this.wwwDir + this.checkchatstatus + this.chat_id + '/' + this.hash + modeWindow ,{}, function(data){
if (data.error == 'false')
{
if (data.activated == 'false')
{
if (data.result != 'false')
{
$('#status-chat').html(data.result);
}
if (data.ru != '') {
document.location = data.ru;
}
setTimeout(chatsyncuserpending,confLH.chat_message_sinterval);
} else {
$('#status-chat').html(data.result);
}
}
}).fail(function(){
setTimeout(chatsyncuserpending,confLH.chat_message_sinterval);
});
};
this.isBlinking = false;
this.startBlinking = function(){
if (this.isBlinking == false) {
var inst = this;
var newExcitingAlerts = (function () {
var oldTitle = document.title;
var msg = "!!! "+document.title;
var timeoutId;
var blink = function() { document.title = document.title == msg ? ' ' : msg; };
var clear = function() {
clearInterval(timeoutId);
document.title = oldTitle;
window.onmousemove = null;
timeoutId = null;
inst.isBlinking = false;
};
return function () {
if (!timeoutId) {
timeoutId = setInterval(blink, 1000);
window.onmousemove = clear;
}
};
}());
newExcitingAlerts();
this.isBlinking = true;
};
};
this.playNewMessageSound = function() {
if (Modernizr.audio) {
var audio = new Audio();
audio.autoplay = 'autoplay';
audio.src = Modernizr.audio.ogg ? WWW_DIR_JAVASCRIPT_FILES + '/new_message.ogg' :
Modernizr.audio.mp3 ? WWW_DIR_JAVASCRIPT_FILES + '/new_message.mp3' : WWW_DIR_JAVASCRIPT_FILES + '/new_message.wav';
audio.load();
};
if(!$("textarea[name=ChatMessage]").is(":focus")) {
this.startBlinking();
};
};
this.playInvitationSound = function() {
if (Modernizr.audio) {
var audio = new Audio();
audio.src = Modernizr.audio.ogg ? WWW_DIR_JAVASCRIPT_FILES + '/invitation.ogg' :
Modernizr.audio.mp3 ? WWW_DIR_JAVASCRIPT_FILES + '/invitation.mp3' : WWW_DIR_JAVASCRIPT_FILES + '/invitation.wav';
audio.load();
setTimeout(function(){
audio.play();
},500);
}
};
this.syncadmincall = function()
{
if (this.chatsSynchronising.length > 0)
{
if (this.underMessageAdd == false && this.syncroRequestSend == false)
{
this.syncroRequestSend = true;
clearTimeout(this.userTimeout);
$.postJSON(this.wwwDir + this.syncadmin ,{ 'chats[]': this.chatsSynchronisingMsg }, function(data){
try {
if (data.error == 'false')
{
if (data.result != 'false')
{
$.each(data.result,function(i,item) {
$('#messagesBlock-'+item.chat_id).append(item.content);
$('#messagesBlock-'+item.chat_id).animate({ scrollTop: $("#messagesBlock-"+item.chat_id).prop("scrollHeight") }, 1000);
lhinst.updateChatLastMessageID(item.chat_id,item.message_id);
var mainElement = $('#chat-id-'+item.chat_id);
if (!mainElement.parent().parent().hasClass('active')) {
if (mainElement.find('span.msg-nm').length > 0) {
mainElement.find('span.msg-nm').html(' (' + (parseInt(mainElement.find('span.msg-nm').attr('rel')) + item.mn) + ')' );
} else {
mainElement.append('<span rel="'+item.mn+'" class="msg-nm"> ('+item.mn+')</span>');
mainElement.addClass('has-pm');
$(document).foundation('section', 'resize');
}
}
if ( confLH.new_message_browser_notification == 1 && data.uw == 'false') {
lhinst.showNewMessageNotification(item.chat_id,item.msg,item.nck);
};
});
if ( confLH.new_message_sound_admin_enabled == 1  && data.uw == 'false') {
lhinst.playNewMessageSound();
};
};
if (data.result_status != 'false')
{
$.each(data.result_status,function(i,item) {
if (item.tp == 'true') {
$('#user-is-typing-'+item.chat_id).html(item.tx).fadeIn();
} else {
$('#user-is-typing-'+item.chat_id).fadeOut();
};
if (item.us == 0){
$('#user-chat-status-'+item.chat_id).addClass('icon-user-online');
} else {
$('#user-chat-status-'+item.chat_id).removeClass('icon-user-online');
};
if (typeof item.oad != 'undefined') {
eval(item.oad);
};
});
};
lhinst.userTimeout = setTimeout(chatsyncadmin,confLH.chat_message_sinterval);
};
} catch (err) {
lhinst.userTimeout = setTimeout(chatsyncadmin,confLH.chat_message_sinterval);
};
lhinst.setSynchronizationRequestSend(false);
if (LHCCallbacks.syncadmincall) {
LHCCallbacks.syncadmincall(lhinst,data);
};
}).fail(function(){
lhinst.userTimeout = setTimeout(chatsyncadmin,confLH.chat_message_sinterval);
lhinst.setSynchronizationRequestSend(false);
});
} else {
lhinst.userTimeout = setTimeout(chatsyncadmin,confLH.chat_message_sinterval);
}
} else {
this.isSinchronizing = false;
}
};
this.updateVoteStatus = function(chat_id) {
$.getJSON(this.wwwDir + 'chat/updatechatstatus/'+chat_id ,{ }, function(data){
$('#main-user-info-tab-'+chat_id).html(data.result);
});
};
this.updateChatLastMessageID = function(chat_id,message_id)
{
this.chatsSynchronisingMsg[this.getChatIndex(chat_id)] = chat_id+','+message_id;
};
this.requestNotificationPermission = function() {
if (window.webkitNotifications) {
window.webkitNotifications.requestPermission();
} else if(window.Notification){
Notification.requestPermission(function(permission){});
} else {
alert('Notification API in your browser is not supported.');
}
};
this.playNewChatAudio = function() {
clearTimeout(this.soundIsPlaying);
this.soundPlayedTimes++;
if (Modernizr.audio) {
var audio = new Audio();
audio.src = Modernizr.audio.ogg ? WWW_DIR_JAVASCRIPT_FILES + '/new_chat.ogg' :
Modernizr.audio.mp3 ? WWW_DIR_JAVASCRIPT_FILES + '/new_chat.mp3' : WWW_DIR_JAVASCRIPT_FILES + '/new_chat.wav';
audio.load();
setTimeout(function(){
audio.play();
},500);
if (confLH.repeat_sound > this.soundPlayedTimes) {
var inst = this;
this.soundIsPlaying = setTimeout(function(){inst.playNewChatAudio();},confLH.repeat_sound_delay*1000);
}
};
};
this.focusChanged = function(status){
if (confLH.new_message_browser_notification == 1 && status == true){
if (window.webkitNotifications || window.Notification) {
var inst = this;
$.each(this.chatsSynchronising, function( index, chat_id ) {
if (typeof inst.notificationsArrayMessages[chat_id] !== 'undefined') {
if (window.webkitNotifications) {
inst.notificationsArrayMessages[chat_id].cancel();
} else {
inst.notificationsArrayMessages[chat_id].close();
}
}
});
}
}
};
this.notificationsArrayMessages = [];
this.showNewMessageNotification = function(chat_id,message,nick) {
try {
if (window.webkitNotifications || window.Notification) {
if (focused == false) {
if (typeof this.notificationsArrayMessages[chat_id] !== 'undefined') {
if (window.webkitNotifications) {
this.notificationsArrayMessages[chat_id].cancel();
} else {
this.notificationsArrayMessages[chat_id].close();
}
};
if (window.webkitNotifications) {
var havePermission = window.webkitNotifications.checkPermission();
if (havePermission == 0) {
var notification = window.webkitNotifications.createNotification(
WWW_DIR_JAVASCRIPT_FILES_NOTIFICATION + '/notification.png',
nick,
message
);
notification.onclick = function () {
window.focus();
notification.cancel();
};
notification.show();
this.notificationsArrayMessages[chat_id] = notification;
}
} else if(window.Notification) {
if (window.Notification.permission == 'granted') {
var notification = new Notification(nick, { icon: WWW_DIR_JAVASCRIPT_FILES_NOTIFICATION + '/notification.png', body: message });
notification.onclick = function () {
window.focus();
notification.close();
};
this.notificationsArrayMessages[chat_id] = notification;
}
}
}
}
} catch(err) {
console.log(err);
};
};
this.playSoundNewAction = function(identifier,chat_id,nick,message) {
if (confLH.new_chat_sound_enabled == 1 && (identifier == 'pending_chat' || identifier == 'transfer_chat' || identifier == 'unread_chat')) {
this.soundPlayedTimes = 0;
this.playNewChatAudio();
};
if(!$("textarea[name=ChatMessage]").is(":focus")) {
this.startBlinking();
};
var inst = this;
if ( (identifier == 'pending_chat' || identifier == 'transfer_chat' || identifier == 'unread_chat') && (window.webkitNotifications || window.Notification)) {
if (window.webkitNotifications) {
var havePermission = window.webkitNotifications.checkPermission();
if (havePermission == 0) {
var notification = window.webkitNotifications.createNotification(
WWW_DIR_JAVASCRIPT_FILES_NOTIFICATION + '/notification.png',
nick,
message
);
notification.onclick = function () {
if (identifier == 'pending_chat' || identifier == 'unread_chat'){
inst.startChatNewWindow(chat_id,'ChatRequest');
} else {
inst.startChatNewWindowTransferByTransfer(chat_id);
};
notification.cancel();
};
notification.show();
this.notificationsArray.push(notification);
}
} else if(window.Notification) {
if (window.Notification.permission == 'granted') {
var notification = new Notification(nick, { icon: WWW_DIR_JAVASCRIPT_FILES_NOTIFICATION + '/notification.png', body: message });
notification.onclick = function () {
if (identifier == 'pending_chat' || identifier == 'unread_chat'){
inst.startChatNewWindow(chat_id,'ChatRequest');
} else {
inst.startChatNewWindowTransferByTransfer(chat_id);
};
notification.close();
};
this.notificationsArray.push(notification);
}
}
};
if (confLH.show_alert == 1) {
setTimeout(function() {
if ($('#right-pending-chats ul').size() > 0) {
if (confirm(confLH.transLation.new_chat)){
if (identifier == 'pending_chat'){
inst.startChatNewWindow(chat_id,'ChatRequest');
} else {
inst.startChatNewWindowTransferByTransfer(chat_id);
};
};
};
},5000);
};
};
this.hideNotifications = function(){
clearTimeout(this.soundIsPlaying);
$.each(this.notificationsArray,function(i,item) {
try {
if (window.webkitNotifications) {
item.cancel();
} else {
item.close();
}
} catch(err) {
console.log(err);
};
});
this.notificationsArray = [];
};
this.syncadmininterfacestatic = function()
{
try {
var lhcController = angular.element('body').scope();
lhcController.loadChatList();
} catch(err) {
};
};
this.transferUserDialog = function(chat_id,title)
{
$.colorbox({width:'550px',height:'400px', href:this.wwwDir + 'chat/transferchat/'+chat_id});
};
this.addmsgadmin = function (chat_id)
{
var textArea = $("#CSChatMessage-"+chat_id);
var pdata = {
msg	: textArea.val()
};
textArea.val('');
if (textArea.hasClass('edit-mode')) {
pdata.msgid = textArea.attr('data-msgid');
$.postJSON(this.wwwDir + 'chat/updatemsg/' + chat_id, pdata , function(data){
if (data.error == 'f') {
textArea.removeClass('edit-mode');
textArea.removeAttr('data-msgid');
$('#msg-'+pdata.msgid).replaceWith(data.msg);
if (LHCCallbacks.addmsgadmin) {
LHCCallbacks.addmsgadmin(chat_id);
};
return true;
}
});
} else {
$.postJSON(this.wwwDir + this.addmsgurl + chat_id, pdata , function(data){
if (LHCCallbacks.addmsgadmin) {
LHCCallbacks.addmsgadmin(chat_id);
};
lhinst.syncadmincall();
return true;
});
}
};
this.editPrevious = function(chat_id) {
var textArea = $('#CSChatMessage-'+chat_id);
if (textArea.val() == '') {
$.getJSON(this.wwwDir + 'chat/editprevious/'+chat_id, function(data){
if (data.error == 'f') {
textArea.val(data.msg);
textArea.attr('data-msgid',data.id);
textArea.addClass('edit-mode');
$('#msg-'+data.id).addClass('edit-mode');
}
});
}
};
this.editPreviousUser = function() {
var textArea = $('#CSChatMessage');
if (textArea.val() == '') {
$.getJSON(this.wwwDir + 'chat/editprevioususer/'+this.chat_id + '/' + this.hash, function(data){
if (data.error == 'f'){
textArea.val(data.msg);
textArea.attr('data-msgid',data.id);
textArea.addClass('edit-mode');
$('#msg-'+data.id).addClass('edit-mode');
}
});
}
};
this.addmsguserchatbox = function (chat_id)
{
var nickCurrent = false;
var pdata = {
msg	: $("#CSChatMessage").val(),
nick: $("#CSChatNick").val()
};
var modeWindow = this.isWidgetMode == true ? '/(mode)/widget' : '';
$('#CSChatMessage').val('');
var inst = this;
$.postJSON(this.wwwDir + this.addmsgurluserchatbox + this.chat_id + '/' + this.hash + modeWindow + this.appendSyncArgument, pdata , function(data) {
if (data.error == 'f') {
if (LHCCallbacks.addmsguserchatbox) {
LHCCallbacks.addmsguserchatbox(inst,{chat_id:inst.chat_id,data:data});
};
inst.syncusercall();
} else {
alert(data.or);
}
});
if (nickCurrent != $("#CSChatNick").val() && !!window.postMessage && parent) {
parent.postMessage('lhc_chb:nick:'+ $("#CSChatNick").val(), '*');
nickCurrent = $("#CSChatNick").val();
}
};
this.updateMessageRow = function(msgid){
var modeWindow = this.isWidgetMode == true ? '/(mode)/widget' : '';
$.getJSON(this.wwwDir + 'chat/getmessage/' + this.chat_id + '/' + this.hash + '/'+ msgid + modeWindow, function(data) {
if (data.error == 'f') {
$('#msg-'+msgid).replaceWith(data.msg);
$('#msg-'+msgid).addClass('edit-mode-done');
setTimeout(function(){
$('#msg-'+msgid).removeClass('edit-mode-done');
},2000);
}
});
};
this.updateMessageRowAdmin = function(chat_id, msgid){
$.getJSON(this.wwwDir + 'chat/getmessageadmin/' + chat_id + '/' + msgid, function(data) {
if (data.error == 'f') {
$('#msg-'+msgid).replaceWith(data.msg);
$('#msg-'+msgid).addClass('edit-mode-done');
setTimeout(function(){
$('#msg-'+msgid).removeClass('edit-mode-done');
},2000);
}
});
};
this.addmsguser = function ()
{
if (LHCCallbacks.addmsguserbefore) {
LHCCallbacks.addmsguserbefore(this);
};
var textArea = $("#CSChatMessage");
var pdata = {
msg	: textArea.val()
};
var modeWindow = this.isWidgetMode == true ? '/(mode)/widget' : '';
textArea.val('');
var inst = this;
if (sessionStorage) {
sessionStorage.setItem('lhc_ttxt','');
};
if (textArea.hasClass('edit-mode')) {
pdata.msgid = textArea.attr('data-msgid');
$.postJSON(this.wwwDir + 'chat/updatemsguser/' + this.chat_id + '/' + this.hash + modeWindow, pdata , function(data){
if (data.error == 'f') {
textArea.removeClass('edit-mode');
textArea.removeAttr('data-msgid');
$('#msg-'+pdata.msgid).replaceWith(data.msg);
return true;
}
});
} else {
$.postJSON(this.wwwDir + this.addmsgurluser + this.chat_id + '/' + this.hash + modeWindow, pdata , function(data) {
if (data.error == 'f') {
if (LHCCallbacks.addmsguser) {
LHCCallbacks.addmsguser(inst,data);
};
inst.syncusercall();
} else {
$('#CSChatMessage').val(pdata.msg);
var instStatus = $('#id-operator-typing');
instStatus.find('i').html(data.r);
instStatus.fadeIn();
}
});
}
};
this.startSyncAdmin = function()
{
if (this.isSinchronizing == false)
{
this.isSinchronizing = true;
this.syncadmincall();
}
};
this.disableChatSoundAdmin = function(inst)
{
if (inst.hasClass('icon-mute')){
$.get(this.wwwDir+  'user/setsettingajax/chat_message/1');
confLH.new_message_sound_admin_enabled = 1;
inst.removeClass('icon-mute');
} else {
$.get(this.wwwDir+  'user/setsettingajax/chat_message/0');
confLH.new_message_sound_admin_enabled = 0;
inst.addClass('icon-mute');
}
return false;
};
this.disableNewChatSoundAdmin = function(inst)
{
if (inst.hasClass('icon-mute')){
$.get(this.wwwDir+  'user/setsettingajax/new_chat_sound/1');
confLH.new_chat_sound_enabled = 1;
inst.removeClass('icon-mute');
} else {
$.get(this.wwwDir+  'user/setsettingajax/new_chat_sound/0');
confLH.new_chat_sound_enabled = 0;
inst.addClass('icon-mute');
}
return false;
};
this.changeUserSettings = function(attr,value){
$.get(this.wwwDir+  'user/setsettingajax/'+attr+'/'+value);
};
this.changeUserSettingsIndifferent = function(attr,value){
$.get(this.wwwDir+  'user/setsettingajax/'+attr+'/'+value+'/(indifferent)/true');
};
this.disableUserAsOnline = function(inst)
{
if (inst.hasClass('user-online-disabled')){
$.get(this.wwwDir+  'user/setoffline/false');
inst.removeClass('user-online-disabled');
} else {
$.get(this.wwwDir+  'user/setoffline/true');
inst.addClass('user-online-disabled');
}
return false;
};
this.closeReveal = function(id){
$(id).foundation('reveal', 'close');
};
this.switchToOfflineForm = function(){
var form = $('#form-start-chat');
form.attr('action',$('#form-start-chat').attr('action')+'/(switchform)/true/(offline)/true/(leaveamessage)/true/(department)/'+$('#id_DepartamentID').val());
form.submit();
return false;
};
this.changeChatStatus = function(chat_id){
if ($('#myModal').size() == 0){
$('body').append('<div id="myModal" class="reveal-modal"></div>');
}
$('#myModal').foundation('reveal','open',{url: this.wwwDir+  'chat/changestatus/'+chat_id});
};
this.changeStatusAction = function(form,chat_id){
var inst = this;
$.postJSON(form.attr('action'),form.serialize(), function(data) {
if (data.error == 'false') {
$('#myModal').foundation('reveal', 'close');
inst.updateVoteStatus(chat_id);
} else {
alert(data.result);
}
});
return false;
};
this.changeVisibility = function(inst)
{
if (inst.hasClass('user-online-disabled')){
$.get(this.wwwDir+  'user/setinvisible/false');
inst.removeClass('user-online-disabled');
} else {
$.get(this.wwwDir+  'user/setinvisible/true');
inst.addClass('user-online-disabled');
}
return false;
};
this.disableChatSoundUser = function(inst)
{
if (inst.hasClass('icon-mute')){
$.get(this.wwwDir+  'user/setsettingajax/chat_message/1');
confLH.new_message_sound_user_enabled = 1;
inst.removeClass('icon-mute');
} else {
$.get(this.wwwDir+  'user/setsettingajax/chat_message/0');
confLH.new_message_sound_user_enabled = 0;
inst.addClass('icon-mute');
};
if (!!window.postMessage && parent) {
if (inst.hasClass('icon-mute')){
parent.postMessage("lhc_ch:s:0", '*');
} else {
parent.postMessage("lhc_ch:s:1", '*');
}
};
return false;
};
this.addCaptcha = function(timestamp,inst) {
if (inst.find('.form-protected').size() == 0){
inst.find('input[type="submit"]').attr('disabled','disabled');
$.getJSON(this.wwwDir + 'captcha/captchastring/form/'+timestamp, function(data) {
inst.append('<input type="hidden" value="'+timestamp+'" name="captcha_'+data.result+'" /><input type="hidden" value="'+timestamp+'" name="tscaptcha" /><input type="hidden" class="form-protected" value="1" />');
inst.submit();
});
return false;
};
return true;
};
this.deleteChatfile = function(file_id){
$.postJSON(this.wwwDir + 'file/deletechatfile/' + file_id, function(data){
if (data.error == 'false') {
$('#file-id-'+file_id).remove();
} else {
alert(data.result);
}
});
};
this.addFileUserUpload = function(data_config) {
$('#fileupload').fileupload({
url: this.wwwDir + 'file/uploadfile/'+data_config.chat_id+'/'+data_config.hash,
dataType: 'json',
add: function(e, data) {
var uploadErrors = [];
var acceptFileTypes = data_config.ft_us;
if (!(acceptFileTypes.test(data.originalFiles[0]['type']) || acceptFileTypes.test(data.originalFiles[0]['name']))) {
uploadErrors.push(data_config.ft_msg);
};
if(data.originalFiles[0]['size'] > data_config.fs) {
uploadErrors.push(data_config.fs_msg);
};
if(uploadErrors.length > 0) {
alert(uploadErrors.join("\n"));
} else {
data.submit();
};
},
done: function(e,data) {
if (LHCCallbacks.addFileUserUpload) {
LHCCallbacks.addFileUserUpload(data_config.chat_id);
};
},
progressall: function (e, data) {
var progress = parseInt(data.loaded / data.total * 100, 10);
$('#id-operator-typing').show();
$('#id-operator-typing').html(progress+'%');
}}).prop('disabled', !$.support.fileInput)
.parent().addClass($.support.fileInput ? undefined : 'disabled');
};
this.updateChatFiles = function(chat_id) {
$.postJSON(this.wwwDir + 'file/chatfileslist/' + chat_id, function(data){
$('#chat-files-list-'+chat_id).html(data.result);
});
};
this.addFileUpload = function(data_config) {
$('#fileupload-'+data_config.chat_id).fileupload({
url: this.wwwDir + 'file/uploadfileadmin/'+data_config.chat_id,
dataType: 'json',
add: function(e, data) {
var uploadErrors = [];
var acceptFileTypes = data_config.ft_op;
if(!(acceptFileTypes.test(data.originalFiles[0]['type']) || acceptFileTypes.test(data.originalFiles[0]['name']))) {
uploadErrors.push(data_config.ft_msg);
};
if(data.originalFiles[0]['size'] > data_config.fs) {
uploadErrors.push(data_config.fs_msg);
};
if(uploadErrors.length > 0) {
alert(uploadErrors.join("\n"));
} else {
data.submit();
};
},
done: function(e,data) {
lhinst.updateChatFiles(data_config.chat_id);
if (LHCCallbacks.addFileUpload) {
LHCCallbacks.addFileUpload(data_config.chat_id);
};
},
dropZone: $('#drop-zone-'+data_config.chat_id),
pasteZone: $('#CSChatMessage-'+data_config.chat_id),
progressall: function (e, data) {
var progress = parseInt(data.loaded / data.total * 100, 10);
$('#user-is-typing-'+data_config.chat_id).show();
$('#user-is-typing-'+data_config.chat_id).html(progress+'%');
}}).prop('disabled', !$.support.fileInput)
.parent().addClass($.support.fileInput ? undefined : 'disabled');
};
this.addExecutionCommand = function(online_user_id,operation) {
$.postJSON(this.wwwDir + 'chat/addonlineoperation/' + online_user_id,{'operation':operation}, function(data){
if (LHCCallbacks.addExecutionCommand) {
LHCCallbacks.addExecutionCommand(online_user_id);
};
});
if (operation == 'lhc_screenshot') {
$('#user-screenshot-container').html('').addClass('screenshot-pending');
var inst = this;
setTimeout(function(){
inst.updateScreenshotOnline(online_user_id);
},15000);
};
};
this.addRemoteCommand = function(chat_id,operation) {
$.postJSON(this.wwwDir + 'chat/addoperation/' + chat_id,{'operation':operation}, function(data){
if (LHCCallbacks.addRemoteCommand) {
LHCCallbacks.addRemoteCommand(chat_id);
};
});
if (operation == 'lhc_screenshot') {
$('#user-screenshot-container').html('').addClass('screenshot-pending');
var inst = this;
setTimeout(function(){
inst.updateScreenshot(chat_id);
},5000);
};
};
this.updateScreenshot = function(chat_id) {
$('#user-screenshot-container').html('').addClass('screenshot-pending');
$.get(this.wwwDir + 'chat/checkscreenshot/' + chat_id,function(data){
$('#user-screenshot-container').html(data);
$('#user-screenshot-container').removeClass('screenshot-pending');
});
};
this.updateScreenshotOnline = function(online_id) {
$('#user-screenshot-container').html('').addClass('screenshot-pending');
$.get(this.wwwDir + 'chat/checkscreenshotonline/' + online_id,function(data){
$('#user-screenshot-container').html(data);
$('#user-screenshot-container').removeClass('screenshot-pending');
});
};
}
var lhinst = new lh();
function gMapsCallback(){
var $mapCanvas = $('#map_canvas');
var map = new google.maps.Map($mapCanvas[0], {
zoom: GeoLocationData.zoom,
center: new google.maps.LatLng(GeoLocationData.lat, GeoLocationData.lng),
mapTypeId: google.maps.MapTypeId.ROADMAP,
disableDefaultUI: true,
options: {
zoomControl: true,
scrollwheel: true,
streetViewControl: true
}
});
var locationSet = false;
var processing = false;
var pendingProcess = false;
var pendingProcessTimeout = false;
google.maps.event.addListener(map, 'idle', showMarkers);
var mapTabSection = $('#map-activator').parent().parent();
function showMarkers() {
if ( processing == false) {
if (mapTabSection.hasClass('active')) {
processing = true;
$.ajax({
url : WWW_DIR_JAVASCRIPT + 'chat/jsononlineusers'+(parseInt($('#id_department_map_id').val()) > 0 ? '/(department)/'+parseInt($('#id_department_map_id').val()) : '' )+(parseInt($('#maxRows').val()) > 0 ? '/(maxrows)/'+parseInt($('#maxRows').val()) : '' ),
dataType: "json",
error:function(){
clearTimeout(pendingProcessTimeout);
pendingProcessTimeout = setTimeout(function(){
showMarkers();
},10000);
},
success : function(response) {
bindMarkers(response);
processing = false;
clearTimeout(pendingProcessTimeout);
if (pendingProcess == true) {
pendingProcess = false;
showMarkers();
} else {
pendingProcessTimeout = setTimeout(function(){
showMarkers();
},10000);
}
}
});
} else {
pendingProcessTimeout = setTimeout(function(){
showMarkers();
},10000);
}
} else {
pendingProcess = true;
}
};
var markers = [];
var markersObjects = [];
var infoWindow = new google.maps.InfoWindow({ content: 'Loading...' });
function bindMarkers(mapData) {
$(mapData.result).each(function(i, e) {
if ($.inArray(e.Id,markers) == -1) {
var latLng = new google.maps.LatLng(e.Latitude, e.Longitude);
var marker = new google.maps.Marker({ position: latLng, icon : e.icon, map : map });
google.maps.event.addListener(marker, 'click', function() {
var content = '<div class="map-preview">...</div>';
infoWindow.setContent(content);
infoWindow.open(map, this);
$.get(WWW_DIR_JAVASCRIPT + 'chat/getonlineuserinfo/'+e.Id,function(result){
infoWindow.setContent(result);
setTimeout(function(){
$(document).foundation('section', 'reflow');
},250);
});
});
marker.setVisible(true);
marker.setAnimation(google.maps.Animation.DROP);
markersObjects[e.Id] = marker;
markers.push(e.Id);
clearTimeout(markersObjects[e.Id].timeOutMarker);
markersObjects[e.Id].timeOutMarker = setTimeout(function(){
markers.splice($.inArray(e.Id,markers), 1);
google.maps.event.clearInstanceListeners(markersObjects[e.Id]);
markersObjects[e.Id].setMap(null);
markersObjects[e.Id] = null;
},parseInt($('#markerTimeout option:selected').val())*1000);
} else {
markersObjects[e.Id].setIcon(e.icon);
clearTimeout(markersObjects[e.Id].timeOutMarker);
markersObjects[e.Id].timeOutMarker = setTimeout(function(){
markers.splice($.inArray(e.Id,markers), 1);
google.maps.event.clearInstanceListeners(markersObjects[e.Id]);
markersObjects[e.Id].setMap(null);
markersObjects[e.Id] = null;
},parseInt($('#markerTimeout option:selected').val())*1000);
}
});
};
$('#id_department_map_id').change(function(){
showMarkers();
lhinst.changeUserSettingsIndifferent('omap_depid',$(this).val());
});
$('#markerTimeout').change(function(){
showMarkers();
lhinst.changeUserSettingsIndifferent('omap_mtimeout',$(this).val());
});
$('#map-activator').click(function(){
setTimeout(function(){
google.maps.event.trigger(map, 'resize');
if (locationSet == false) {
locationSet = true;
map.setCenter(new google.maps.LatLng(GeoLocationData.lat, GeoLocationData.lng));
}
},500);
showMarkers();
});
};
var focused = true;
window.onfocus = window.onblur = function(e) {
focused = (e || event).type === "focus";
lhinst.focusChanged(focused);
};
function chatsyncuser()
{
lhinst.syncusercall();
}
function chatsyncuserpending()
{
lhinst.chatsyncuserpending();
}
function chatsyncadmin()
{
lhinst.syncadmincall();
}
(function(jQuery){jQuery.fn.__bind__=jQuery.fn.bind;jQuery.fn.__unbind__=jQuery.fn.unbind;jQuery.fn.__find__=jQuery.fn.find;var hotkeys={version:'0.7.9',override:/keypress|keydown|keyup/g,triggersMap:{},specialKeys:{27:'esc',9:'tab',32:'space',13:'return',8:'backspace',145:'scroll',20:'capslock',144:'numlock',19:'pause',45:'insert',36:'home',46:'del',35:'end',33:'pageup',34:'pagedown',37:'left',38:'up',39:'right',40:'down',109:'-',112:'f1',113:'f2',114:'f3',115:'f4',116:'f5',117:'f6',118:'f7',119:'f8',120:'f9',121:'f10',122:'f11',123:'f12',191:'/'},shiftNums:{"`":"~","1":"!","2":"@","3":"#","4":"$","5":"%","6":"^","7":"&","8":"*","9":"(","0":")","-":"_","=":"+",";":":","'":"\"",",":"<",".":">","/":"?","\\":"|"},newTrigger:function(type,combi,callback){var result={};result[type]={};result[type][combi]={cb:callback,disableInInput:false};return result;}};hotkeys.specialKeys=jQuery.extend(hotkeys.specialKeys,{96:'0',97:'1',98:'2',99:'3',100:'4',101:'5',102:'6',103:'7',104:'8',105:'9',106:'*',107:'+',109:'-',110:'.',111:'/'});jQuery.fn.find=function(selector){this.query=selector;return jQuery.fn.__find__.apply(this,arguments);};jQuery.fn.unbind=function(type,combi,fn){if(jQuery.isFunction(combi)){fn=combi;combi=null;}
if(combi&&typeof combi==='string'){var selectorId=((this.prevObject&&this.prevObject.query)||(this[0].id&&this[0].id)||this[0]).toString();var hkTypes=type.split(' ');for(var x=0;x<hkTypes.length;x++){delete hotkeys.triggersMap[selectorId][hkTypes[x]][combi];}}
return this.__unbind__(type,fn);};jQuery.fn.bind=function(type,data,fn){if (type.match != undefined){var handle=type.match(hotkeys.override);};if(jQuery.isFunction(data)||!handle){return this.__bind__(type,data,fn);}
else{var result=null,pass2jq=jQuery.trim(type.replace(hotkeys.override,''));if(pass2jq){result=this.__bind__(pass2jq,data,fn);}
if(typeof data==="string"){data={'combi':data};}
if(data.combi){for(var x=0;x<handle.length;x++){var eventType=handle[x];var combi=data.combi.toLowerCase(),trigger=hotkeys.newTrigger(eventType,combi,fn),selectorId=((this.prevObject&&this.prevObject.query)||(this[0].id&&this[0].id)||this[0]).toString();trigger[eventType][combi].disableInInput=data.disableInInput;if(!hotkeys.triggersMap[selectorId]){hotkeys.triggersMap[selectorId]=trigger;}
else if(!hotkeys.triggersMap[selectorId][eventType]){hotkeys.triggersMap[selectorId][eventType]=trigger[eventType];}
var mapPoint=hotkeys.triggersMap[selectorId][eventType][combi];if(!mapPoint){hotkeys.triggersMap[selectorId][eventType][combi]=[trigger[eventType][combi]];}
else if(mapPoint.constructor!==Array){hotkeys.triggersMap[selectorId][eventType][combi]=[mapPoint];}
else{hotkeys.triggersMap[selectorId][eventType][combi][mapPoint.length]=trigger[eventType][combi];}
this.each(function(){var jqElem=jQuery(this);if(jqElem.attr('hkId')&&jqElem.attr('hkId')!==selectorId){selectorId=jqElem.attr('hkId')+";"+selectorId;}
jqElem.attr('hkId',selectorId);});result=this.__bind__(handle.join(' '),data,hotkeys.handler)}}
return result;}};hotkeys.findElement=function(elem){if(!jQuery(elem).attr('hkId')){if(jQuery.browser.opera||jQuery.browser.safari){while(!jQuery(elem).attr('hkId')&&elem.parentNode){elem=elem.parentNode;}}}
return elem;};hotkeys.handler=function(event){var target=hotkeys.findElement(event.currentTarget),jTarget=jQuery(target),ids=jTarget.attr('hkId');if(ids){ids=ids.split(';');var code=event.which,type=event.type,special=hotkeys.specialKeys[code],character=!special&&String.fromCharCode(code).toLowerCase(),shift=event.shiftKey,ctrl=event.ctrlKey,alt=event.altKey||event.originalEvent.altKey,mapPoint=null;for(var x=0;x<ids.length;x++){if(hotkeys.triggersMap[ids[x]][type]){mapPoint=hotkeys.triggersMap[ids[x]][type];break;}}
if(mapPoint){var trigger;if(!shift&&!ctrl&&!alt){trigger=mapPoint[special]||(character&&mapPoint[character]);}
else{var modif='';if(alt)modif+='alt+';if(ctrl)modif+='ctrl+';if(shift)modif+='shift+';trigger=mapPoint[modif+special];if(!trigger){if(character){trigger=mapPoint[modif+character]||mapPoint[modif+hotkeys.shiftNums[character]]||(modif==='shift+'&&mapPoint[hotkeys.shiftNums[character]]);}}}
if(trigger){var result=false;for(var x=0;x<trigger.length;x++){if(trigger[x].disableInInput){var elem=jQuery(event.target);if(jTarget.is("input")||jTarget.is("textarea")||jTarget.is("select")||elem.is("input")||elem.is("textarea")||elem.is("select")){return true;}}
result=result||trigger[x].cb.apply(this,[event]);}
return result;}}}};window.hotkeys=hotkeys;return jQuery;})(jQuery);

(function (factory) {
if (typeof define === "function" && define.amd) {
define(["jquery"], factory);
} else {
factory(jQuery);
}
}(function( $, undefined ) {
var uuid = 0,
slice = Array.prototype.slice,
_cleanData = $.cleanData;
$.cleanData = function( elems ) {
for ( var i = 0, elem; (elem = elems[i]) != null; i++ ) {
try {
$( elem ).triggerHandler( "remove" );
} catch( e ) {}
}
_cleanData( elems );
};
$.widget = function( name, base, prototype ) {
var fullName, existingConstructor, constructor, basePrototype,
proxiedPrototype = {},
namespace = name.split( "." )[ 0 ];
name = name.split( "." )[ 1 ];
fullName = namespace + "-" + name;
if ( !prototype ) {
prototype = base;
base = $.Widget;
}
$.expr[ ":" ][ fullName.toLowerCase() ] = function( elem ) {
return !!$.data( elem, fullName );
};
$[ namespace ] = $[ namespace ] || {};
existingConstructor = $[ namespace ][ name ];
constructor = $[ namespace ][ name ] = function( options, element ) {
if ( !this._createWidget ) {
return new constructor( options, element );
}
if ( arguments.length ) {
this._createWidget( options, element );
}
};
$.extend( constructor, existingConstructor, {
version: prototype.version,
_proto: $.extend( {}, prototype ),
_childConstructors: []
});
basePrototype = new base();
basePrototype.options = $.widget.extend( {}, basePrototype.options );
$.each( prototype, function( prop, value ) {
if ( !$.isFunction( value ) ) {
proxiedPrototype[ prop ] = value;
return;
}
proxiedPrototype[ prop ] = (function() {
var _super = function() {
return base.prototype[ prop ].apply( this, arguments );
},
_superApply = function( args ) {
return base.prototype[ prop ].apply( this, args );
};
return function() {
var __super = this._super,
__superApply = this._superApply,
returnValue;
this._super = _super;
this._superApply = _superApply;
returnValue = value.apply( this, arguments );
this._super = __super;
this._superApply = __superApply;
return returnValue;
};
})();
});
constructor.prototype = $.widget.extend( basePrototype, {
widgetEventPrefix: existingConstructor ? basePrototype.widgetEventPrefix : name
}, proxiedPrototype, {
constructor: constructor,
namespace: namespace,
widgetName: name,
widgetFullName: fullName
});
if ( existingConstructor ) {
$.each( existingConstructor._childConstructors, function( i, child ) {
var childPrototype = child.prototype;
$.widget( childPrototype.namespace + "." + childPrototype.widgetName, constructor, child._proto );
});
delete existingConstructor._childConstructors;
} else {
base._childConstructors.push( constructor );
}
$.widget.bridge( name, constructor );
};
$.widget.extend = function( target ) {
var input = slice.call( arguments, 1 ),
inputIndex = 0,
inputLength = input.length,
key,
value;
for ( ; inputIndex < inputLength; inputIndex++ ) {
for ( key in input[ inputIndex ] ) {
value = input[ inputIndex ][ key ];
if ( input[ inputIndex ].hasOwnProperty( key ) && value !== undefined ) {
if ( $.isPlainObject( value ) ) {
target[ key ] = $.isPlainObject( target[ key ] ) ?
$.widget.extend( {}, target[ key ], value ) :
$.widget.extend( {}, value );
} else {
target[ key ] = value;
}
}
}
}
return target;
};
$.widget.bridge = function( name, object ) {
var fullName = object.prototype.widgetFullName || name;
$.fn[ name ] = function( options ) {
var isMethodCall = typeof options === "string",
args = slice.call( arguments, 1 ),
returnValue = this;
options = !isMethodCall && args.length ?
$.widget.extend.apply( null, [ options ].concat(args) ) :
options;
if ( isMethodCall ) {
this.each(function() {
var methodValue,
instance = $.data( this, fullName );
if ( !instance ) {
return $.error( "cannot call methods on " + name + " prior to initialization; " +
"attempted to call method '" + options + "'" );
}
if ( !$.isFunction( instance[options] ) || options.charAt( 0 ) === "_" ) {
return $.error( "no such method '" + options + "' for " + name + " widget instance" );
}
methodValue = instance[ options ].apply( instance, args );
if ( methodValue !== instance && methodValue !== undefined ) {
returnValue = methodValue && methodValue.jquery ?
returnValue.pushStack( methodValue.get() ) :
methodValue;
return false;
}
});
} else {
this.each(function() {
var instance = $.data( this, fullName );
if ( instance ) {
instance.option( options || {} )._init();
} else {
$.data( this, fullName, new object( options, this ) );
}
});
}
return returnValue;
};
};
$.Widget = function( ) {};
$.Widget._childConstructors = [];
$.Widget.prototype = {
widgetName: "widget",
widgetEventPrefix: "",
defaultElement: "<div>",
options: {
disabled: false,
create: null
},
_createWidget: function( options, element ) {
element = $( element || this.defaultElement || this )[ 0 ];
this.element = $( element );
this.uuid = uuid++;
this.eventNamespace = "." + this.widgetName + this.uuid;
this.options = $.widget.extend( {},
this.options,
this._getCreateOptions(),
options );
this.bindings = $();
this.hoverable = $();
this.focusable = $();
if ( element !== this ) {
$.data( element, this.widgetFullName, this );
this._on( true, this.element, {
remove: function( event ) {
if ( event.target === element ) {
this.destroy();
}
}
});
this.document = $( element.style ?
element.ownerDocument :
element.document || element );
this.window = $( this.document[0].defaultView || this.document[0].parentWindow );
}
this._create();
this._trigger( "create", null, this._getCreateEventData() );
this._init();
},
_getCreateOptions: $.noop,
_getCreateEventData: $.noop,
_create: $.noop,
_init: $.noop,
destroy: function() {
this._destroy();
this.element
.unbind( this.eventNamespace )
.removeData( this.widgetName )
.removeData( this.widgetFullName )
.removeData( $.camelCase( this.widgetFullName ) );
this.widget()
.unbind( this.eventNamespace )
.removeAttr( "aria-disabled" )
.removeClass(
this.widgetFullName + "-disabled " +
"ui-state-disabled" );
this.bindings.unbind( this.eventNamespace );
this.hoverable.removeClass( "ui-state-hover" );
this.focusable.removeClass( "ui-state-focus" );
},
_destroy: $.noop,
widget: function() {
return this.element;
},
option: function( key, value ) {
var options = key,
parts,
curOption,
i;
if ( arguments.length === 0 ) {
return $.widget.extend( {}, this.options );
}
if ( typeof key === "string" ) {
options = {};
parts = key.split( "." );
key = parts.shift();
if ( parts.length ) {
curOption = options[ key ] = $.widget.extend( {}, this.options[ key ] );
for ( i = 0; i < parts.length - 1; i++ ) {
curOption[ parts[ i ] ] = curOption[ parts[ i ] ] || {};
curOption = curOption[ parts[ i ] ];
}
key = parts.pop();
if ( value === undefined ) {
return curOption[ key ] === undefined ? null : curOption[ key ];
}
curOption[ key ] = value;
} else {
if ( value === undefined ) {
return this.options[ key ] === undefined ? null : this.options[ key ];
}
options[ key ] = value;
}
}
this._setOptions( options );
return this;
},
_setOptions: function( options ) {
var key;
for ( key in options ) {
this._setOption( key, options[ key ] );
}
return this;
},
_setOption: function( key, value ) {
this.options[ key ] = value;
if ( key === "disabled" ) {
this.widget()
.toggleClass( this.widgetFullName + "-disabled ui-state-disabled", !!value )
.attr( "aria-disabled", value );
this.hoverable.removeClass( "ui-state-hover" );
this.focusable.removeClass( "ui-state-focus" );
}
return this;
},
enable: function() {
return this._setOption( "disabled", false );
},
disable: function() {
return this._setOption( "disabled", true );
},
_on: function( suppressDisabledCheck, element, handlers ) {
var delegateElement,
instance = this;
if ( typeof suppressDisabledCheck !== "boolean" ) {
handlers = element;
element = suppressDisabledCheck;
suppressDisabledCheck = false;
}
if ( !handlers ) {
handlers = element;
element = this.element;
delegateElement = this.widget();
} else {
element = delegateElement = $( element );
this.bindings = this.bindings.add( element );
}
$.each( handlers, function( event, handler ) {
function handlerProxy() {
if ( !suppressDisabledCheck &&
( instance.options.disabled === true ||
$( this ).hasClass( "ui-state-disabled" ) ) ) {
return;
}
return ( typeof handler === "string" ? instance[ handler ] : handler )
.apply( instance, arguments );
}
if ( typeof handler !== "string" ) {
handlerProxy.guid = handler.guid =
handler.guid || handlerProxy.guid || $.guid++;
}
var match = event.match( /^(\w+)\s*(.*)$/ ),
eventName = match[1] + instance.eventNamespace,
selector = match[2];
if ( selector ) {
delegateElement.delegate( selector, eventName, handlerProxy );
} else {
element.bind( eventName, handlerProxy );
}
});
},
_off: function( element, eventName ) {
eventName = (eventName || "").split( " " ).join( this.eventNamespace + " " ) + this.eventNamespace;
element.unbind( eventName ).undelegate( eventName );
},
_delay: function( handler, delay ) {
function handlerProxy() {
return ( typeof handler === "string" ? instance[ handler ] : handler )
.apply( instance, arguments );
}
var instance = this;
return setTimeout( handlerProxy, delay || 0 );
},
_hoverable: function( element ) {
this.hoverable = this.hoverable.add( element );
this._on( element, {
mouseenter: function( event ) {
$( event.currentTarget ).addClass( "ui-state-hover" );
},
mouseleave: function( event ) {
$( event.currentTarget ).removeClass( "ui-state-hover" );
}
});
},
_focusable: function( element ) {
this.focusable = this.focusable.add( element );
this._on( element, {
focusin: function( event ) {
$( event.currentTarget ).addClass( "ui-state-focus" );
},
focusout: function( event ) {
$( event.currentTarget ).removeClass( "ui-state-focus" );
}
});
},
_trigger: function( type, event, data ) {
var prop, orig,
callback = this.options[ type ];
data = data || {};
event = $.Event( event );
event.type = ( type === this.widgetEventPrefix ?
type :
this.widgetEventPrefix + type ).toLowerCase();
event.target = this.element[ 0 ];
orig = event.originalEvent;
if ( orig ) {
for ( prop in orig ) {
if ( !( prop in event ) ) {
event[ prop ] = orig[ prop ];
}
}
}
this.element.trigger( event, data );
return !( $.isFunction( callback ) &&
callback.apply( this.element[0], [ event ].concat( data ) ) === false ||
event.isDefaultPrevented() );
}
};
$.each( { show: "fadeIn", hide: "fadeOut" }, function( method, defaultEffect ) {
$.Widget.prototype[ "_" + method ] = function( element, options, callback ) {
if ( typeof options === "string" ) {
options = { effect: options };
}
var hasOptions,
effectName = !options ?
method :
options === true || typeof options === "number" ?
defaultEffect :
options.effect || defaultEffect;
options = options || {};
if ( typeof options === "number" ) {
options = { duration: options };
}
hasOptions = !$.isEmptyObject( options );
options.complete = callback;
if ( options.delay ) {
element.delay( options.delay );
}
if ( hasOptions && $.effects && $.effects.effect[ effectName ] ) {
element[ method ]( options );
} else if ( effectName !== method && element[ effectName ] ) {
element[ effectName ]( options.duration, options.easing, callback );
} else {
element.queue(function( next ) {
$( this )[ method ]();
if ( callback ) {
callback.call( element[ 0 ] );
}
next();
});
}
};
});
}));


(function (factory) {
'use strict';
if (typeof define === 'function' && define.amd) {
define(['jquery'], factory);
} else {
factory(window.jQuery);
}
}(function ($) {
'use strict';
var counter = 0;
$.ajaxTransport('iframe', function (options) {
if (options.async) {
var initialIframeSrc = options.initialIframeSrc || 'javascript:false;',
form,
iframe,
addParamChar;
return {
send: function (_, completeCallback) {
form = $('<form style="display:none;"></form>');
form.attr('accept-charset', options.formAcceptCharset);
addParamChar = /\?/.test(options.url) ? '&' : '?';
if (options.type === 'DELETE') {
options.url = options.url + addParamChar + '_method=DELETE';
options.type = 'POST';
} else if (options.type === 'PUT') {
options.url = options.url + addParamChar + '_method=PUT';
options.type = 'POST';
} else if (options.type === 'PATCH') {
options.url = options.url + addParamChar + '_method=PATCH';
options.type = 'POST';
}
counter += 1;
iframe = $(
'<iframe src="' + initialIframeSrc +
'" name="iframe-transport-' + counter + '"></iframe>'
).bind('load', function () {
var fileInputClones,
paramNames = $.isArray(options.paramName) ?
options.paramName : [options.paramName];
iframe
.unbind('load')
.bind('load', function () {
var response;
try {
response = iframe.contents();
if (!response.length || !response[0].firstChild) {
throw new Error();
}
} catch (e) {
response = undefined;
}
completeCallback(
200,
'success',
{'iframe': response}
);
$('<iframe src="' + initialIframeSrc + '"></iframe>')
.appendTo(form);
window.setTimeout(function () {
form.remove();
}, 0);
});
form
.prop('target', iframe.prop('name'))
.prop('action', options.url)
.prop('method', options.type);
if (options.formData) {
$.each(options.formData, function (index, field) {
$('<input type="hidden"/>')
.prop('name', field.name)
.val(field.value)
.appendTo(form);
});
}
if (options.fileInput && options.fileInput.length &&
options.type === 'POST') {
fileInputClones = options.fileInput.clone();
options.fileInput.after(function (index) {
return fileInputClones[index];
});
if (options.paramName) {
options.fileInput.each(function (index) {
$(this).prop(
'name',
paramNames[index] || options.paramName
);
});
}
form
.append(options.fileInput)
.prop('enctype', 'multipart/form-data')
.prop('encoding', 'multipart/form-data');
}
form.submit();
if (fileInputClones && fileInputClones.length) {
options.fileInput.each(function (index, input) {
var clone = $(fileInputClones[index]);
$(input).prop('name', clone.prop('name'));
clone.replaceWith(input);
});
}
});
form.append(iframe).appendTo(document.body);
},
abort: function () {
if (iframe) {
iframe
.unbind('load')
.prop('src', initialIframeSrc);
}
if (form) {
form.remove();
}
}
};
}
});
$.ajaxSetup({
converters: {
'iframe text': function (iframe) {
return iframe && $(iframe[0].body).text();
},
'iframe json': function (iframe) {
return iframe && $.parseJSON($(iframe[0].body).text());
},
'iframe html': function (iframe) {
return iframe && $(iframe[0].body).html();
},
'iframe xml': function (iframe) {
var xmlDoc = iframe && iframe[0];
return xmlDoc && $.isXMLDoc(xmlDoc) ? xmlDoc :
$.parseXML((xmlDoc.XMLDocument && xmlDoc.XMLDocument.xml) ||
$(xmlDoc.body).html());
},
'iframe script': function (iframe) {
return iframe && $.globalEval($(iframe[0].body).text());
}
}
});
}));


(function (factory) {
'use strict';
if (typeof define === 'function' && define.amd) {
define([
'jquery',
'jquery.ui.widget'
], factory);
} else {
factory(window.jQuery);
}
}(function ($) {
'use strict';
$.support.fileInput = !(new RegExp(
'(Android (1\\.[0156]|2\\.[01]))' +
'|(Windows Phone (OS 7|8\\.0))|(XBLWP)|(ZuneWP)|(WPDesktop)' +
'|(w(eb)?OSBrowser)|(webOS)' +
'|(Kindle/(1\\.0|2\\.[05]|3\\.0))'
).test(window.navigator.userAgent) ||
$('<input type="file">').prop('disabled'));
$.support.xhrFileUpload = !!(window.ProgressEvent && window.FileReader);
$.support.xhrFormDataFileUpload = !!window.FormData;
$.support.blobSlice = window.Blob && (Blob.prototype.slice ||
Blob.prototype.webkitSlice || Blob.prototype.mozSlice);
$.widget('blueimp.fileupload', {
options: {
dropZone: $(document),
pasteZone: $(document),
fileInput: undefined,
replaceFileInput: true,
paramName: undefined,
singleFileUploads: true,
limitMultiFileUploads: undefined,
sequentialUploads: false,
limitConcurrentUploads: undefined,
forceIframeTransport: false,
redirect: undefined,
redirectParamName: undefined,
postMessage: undefined,
multipart: true,
maxChunkSize: undefined,
uploadedBytes: undefined,
recalculateProgress: true,
progressInterval: 100,
bitrateInterval: 500,
autoUpload: true,
messages: {
uploadedBytes: 'Uploaded bytes exceed file size'
},
i18n: function (message, context) {
message = this.messages[message] || message.toString();
if (context) {
$.each(context, function (key, value) {
message = message.replace('{' + key + '}', value);
});
}
return message;
},
formData: function (form) {
return form.serializeArray();
},
add: function (e, data) {
if (e.isDefaultPrevented()) {
return false;
}
if (data.autoUpload || (data.autoUpload !== false &&
$(this).fileupload('option', 'autoUpload'))) {
data.process().done(function () {
data.submit();
});
}
},
processData: false,
contentType: false,
cache: false
},
_specialOptions: [
'fileInput',
'dropZone',
'pasteZone',
'multipart',
'forceIframeTransport'
],
_blobSlice: $.support.blobSlice && function () {
var slice = this.slice || this.webkitSlice || this.mozSlice;
return slice.apply(this, arguments);
},
_BitrateTimer: function () {
this.timestamp = ((Date.now) ? Date.now() : (new Date()).getTime());
this.loaded = 0;
this.bitrate = 0;
this.getBitrate = function (now, loaded, interval) {
var timeDiff = now - this.timestamp;
if (!this.bitrate || !interval || timeDiff > interval) {
this.bitrate = (loaded - this.loaded) * (1000 / timeDiff) * 8;
this.loaded = loaded;
this.timestamp = now;
}
return this.bitrate;
};
},
_isXHRUpload: function (options) {
return !options.forceIframeTransport &&
((!options.multipart && $.support.xhrFileUpload) ||
$.support.xhrFormDataFileUpload);
},
_getFormData: function (options) {
var formData;
if (typeof options.formData === 'function') {
return options.formData(options.form);
}
if ($.isArray(options.formData)) {
return options.formData;
}
if ($.type(options.formData) === 'object') {
formData = [];
$.each(options.formData, function (name, value) {
formData.push({name: name, value: value});
});
return formData;
}
return [];
},
_getTotal: function (files) {
var total = 0;
$.each(files, function (index, file) {
total += file.size || 1;
});
return total;
},
_initProgressObject: function (obj) {
var progress = {
loaded: 0,
total: 0,
bitrate: 0
};
if (obj._progress) {
$.extend(obj._progress, progress);
} else {
obj._progress = progress;
}
},
_initResponseObject: function (obj) {
var prop;
if (obj._response) {
for (prop in obj._response) {
if (obj._response.hasOwnProperty(prop)) {
delete obj._response[prop];
}
}
} else {
obj._response = {};
}
},
_onProgress: function (e, data) {
if (e.lengthComputable) {
var now = ((Date.now) ? Date.now() : (new Date()).getTime()),
loaded;
if (data._time && data.progressInterval &&
(now - data._time < data.progressInterval) &&
e.loaded !== e.total) {
return;
}
data._time = now;
loaded = Math.floor(
e.loaded / e.total * (data.chunkSize || data._progress.total)
) + (data.uploadedBytes || 0);
this._progress.loaded += (loaded - data._progress.loaded);
this._progress.bitrate = this._bitrateTimer.getBitrate(
now,
this._progress.loaded,
data.bitrateInterval
);
data._progress.loaded = data.loaded = loaded;
data._progress.bitrate = data.bitrate = data._bitrateTimer.getBitrate(
now,
loaded,
data.bitrateInterval
);
this._trigger(
'progress',
$.Event('progress', {delegatedEvent: e}),
data
);
this._trigger(
'progressall',
$.Event('progressall', {delegatedEvent: e}),
this._progress
);
}
},
_initProgressListener: function (options) {
var that = this,
xhr = options.xhr ? options.xhr() : $.ajaxSettings.xhr();
if (xhr.upload) {
$(xhr.upload).bind('progress', function (e) {
var oe = e.originalEvent;
e.lengthComputable = oe.lengthComputable;
e.loaded = oe.loaded;
e.total = oe.total;
that._onProgress(e, options);
});
options.xhr = function () {
return xhr;
};
}
},
_isInstanceOf: function (type, obj) {
return Object.prototype.toString.call(obj) === '[object ' + type + ']';
},
_initXHRData: function (options) {
var that = this,
formData,
file = options.files[0],
multipart = options.multipart || !$.support.xhrFileUpload,
paramName = options.paramName[0];
options.headers = $.extend({}, options.headers);
if (options.contentRange) {
options.headers['Content-Range'] = options.contentRange;
}
if (!multipart || options.blob || !this._isInstanceOf('File', file)) {
options.headers['Content-Disposition'] = 'attachment; filename="' +
encodeURI(file.name) + '"';
}
if (!multipart) {
options.contentType = file.type;
options.data = options.blob || file;
} else if ($.support.xhrFormDataFileUpload) {
if (options.postMessage) {
formData = this._getFormData(options);
if (options.blob) {
formData.push({
name: paramName,
value: options.blob
});
} else {
$.each(options.files, function (index, file) {
formData.push({
name: options.paramName[index] || paramName,
value: file
});
});
}
} else {
if (that._isInstanceOf('FormData', options.formData)) {
formData = options.formData;
} else {
formData = new FormData();
$.each(this._getFormData(options), function (index, field) {
formData.append(field.name, field.value);
});
}
if (options.blob) {
formData.append(paramName, options.blob, file.name);
} else {
$.each(options.files, function (index, file) {
if (that._isInstanceOf('File', file) ||
that._isInstanceOf('Blob', file)) {
formData.append(
options.paramName[index] || paramName,
file,
file.name
);
}
});
}
}
options.data = formData;
}
options.blob = null;
},
_initIframeSettings: function (options) {
var targetHost = $('<a></a>').prop('href', options.url).prop('host');
options.dataType = 'iframe ' + (options.dataType || '');
options.formData = this._getFormData(options);
if (options.redirect && targetHost && targetHost !== location.host) {
options.formData.push({
name: options.redirectParamName || 'redirect',
value: options.redirect
});
}
},
_initDataSettings: function (options) {
if (this._isXHRUpload(options)) {
if (!this._chunkedUpload(options, true)) {
if (!options.data) {
this._initXHRData(options);
}
this._initProgressListener(options);
}
if (options.postMessage) {
options.dataType = 'postmessage ' + (options.dataType || '');
}
} else {
this._initIframeSettings(options);
}
},
_getParamName: function (options) {
var fileInput = $(options.fileInput),
paramName = options.paramName;
if (!paramName) {
paramName = [];
fileInput.each(function () {
var input = $(this),
name = input.prop('name') || 'files[]',
i = (input.prop('files') || [1]).length;
while (i) {
paramName.push(name);
i -= 1;
}
});
if (!paramName.length) {
paramName = [fileInput.prop('name') || 'files[]'];
}
} else if (!$.isArray(paramName)) {
paramName = [paramName];
}
return paramName;
},
_initFormSettings: function (options) {
if (!options.form || !options.form.length) {
options.form = $(options.fileInput.prop('form'));
if (!options.form.length) {
options.form = $(this.options.fileInput.prop('form'));
}
}
options.paramName = this._getParamName(options);
if (!options.url) {
options.url = options.form.prop('action') || location.href;
}
options.type = (options.type ||
($.type(options.form.prop('method')) === 'string' &&
options.form.prop('method')) || ''
).toUpperCase();
if (options.type !== 'POST' && options.type !== 'PUT' &&
options.type !== 'PATCH') {
options.type = 'POST';
}
if (!options.formAcceptCharset) {
options.formAcceptCharset = options.form.attr('accept-charset');
}
},
_getAJAXSettings: function (data) {
var options = $.extend({}, this.options, data);
this._initFormSettings(options);
this._initDataSettings(options);
return options;
},
_getDeferredState: function (deferred) {
if (deferred.state) {
return deferred.state();
}
if (deferred.isResolved()) {
return 'resolved';
}
if (deferred.isRejected()) {
return 'rejected';
}
return 'pending';
},
_enhancePromise: function (promise) {
promise.success = promise.done;
promise.error = promise.fail;
promise.complete = promise.always;
return promise;
},
_getXHRPromise: function (resolveOrReject, context, args) {
var dfd = $.Deferred(),
promise = dfd.promise();
context = context || this.options.context || promise;
if (resolveOrReject === true) {
dfd.resolveWith(context, args);
} else if (resolveOrReject === false) {
dfd.rejectWith(context, args);
}
promise.abort = dfd.promise;
return this._enhancePromise(promise);
},
_addConvenienceMethods: function (e, data) {
var that = this,
getPromise = function (args) {
return $.Deferred().resolveWith(that, args).promise();
};
data.process = function (resolveFunc, rejectFunc) {
if (resolveFunc || rejectFunc) {
data._processQueue = this._processQueue =
(this._processQueue || getPromise([this])).pipe(
function () {
if (data.errorThrown) {
return $.Deferred()
.rejectWith(that, [data]).promise();
}
return getPromise(arguments);
}
).pipe(resolveFunc, rejectFunc);
}
return this._processQueue || getPromise([this]);
};
data.submit = function () {
if (this.state() !== 'pending') {
data.jqXHR = this.jqXHR =
(that._trigger(
'submit',
$.Event('submit', {delegatedEvent: e}),
this
) !== false) && that._onSend(e, this);
}
return this.jqXHR || that._getXHRPromise();
};
data.abort = function () {
if (this.jqXHR) {
return this.jqXHR.abort();
}
this.errorThrown = 'abort';
return that._getXHRPromise();
};
data.state = function () {
if (this.jqXHR) {
return that._getDeferredState(this.jqXHR);
}
if (this._processQueue) {
return that._getDeferredState(this._processQueue);
}
};
data.progress = function () {
return this._progress;
};
data.response = function () {
return this._response;
};
},
_getUploadedBytes: function (jqXHR) {
var range = jqXHR.getResponseHeader('Range'),
parts = range && range.split('-'),
upperBytesPos = parts && parts.length > 1 &&
parseInt(parts[1], 10);
return upperBytesPos && upperBytesPos + 1;
},
_chunkedUpload: function (options, testOnly) {
options.uploadedBytes = options.uploadedBytes || 0;
var that = this,
file = options.files[0],
fs = file.size,
ub = options.uploadedBytes,
mcs = options.maxChunkSize || fs,
slice = this._blobSlice,
dfd = $.Deferred(),
promise = dfd.promise(),
jqXHR,
upload;
if (!(this._isXHRUpload(options) && slice && (ub || mcs < fs)) ||
options.data) {
return false;
}
if (testOnly) {
return true;
}
if (ub >= fs) {
file.error = options.i18n('uploadedBytes');
return this._getXHRPromise(
false,
options.context,
[null, 'error', file.error]
);
}
upload = function () {
var o = $.extend({}, options),
currentLoaded = o._progress.loaded;
o.blob = slice.call(
file,
ub,
ub + mcs,
file.type
);
o.chunkSize = o.blob.size;
o.contentRange = 'bytes ' + ub + '-' +
(ub + o.chunkSize - 1) + '/' + fs;
that._initXHRData(o);
that._initProgressListener(o);
jqXHR = ((that._trigger('chunksend', null, o) !== false && $.ajax(o)) ||
that._getXHRPromise(false, o.context))
.done(function (result, textStatus, jqXHR) {
ub = that._getUploadedBytes(jqXHR) ||
(ub + o.chunkSize);
if (currentLoaded + o.chunkSize - o._progress.loaded) {
that._onProgress($.Event('progress', {
lengthComputable: true,
loaded: ub - o.uploadedBytes,
total: ub - o.uploadedBytes
}), o);
}
options.uploadedBytes = o.uploadedBytes = ub;
o.result = result;
o.textStatus = textStatus;
o.jqXHR = jqXHR;
that._trigger('chunkdone', null, o);
that._trigger('chunkalways', null, o);
if (ub < fs) {
upload();
} else {
dfd.resolveWith(
o.context,
[result, textStatus, jqXHR]
);
}
})
.fail(function (jqXHR, textStatus, errorThrown) {
o.jqXHR = jqXHR;
o.textStatus = textStatus;
o.errorThrown = errorThrown;
that._trigger('chunkfail', null, o);
that._trigger('chunkalways', null, o);
dfd.rejectWith(
o.context,
[jqXHR, textStatus, errorThrown]
);
});
};
this._enhancePromise(promise);
promise.abort = function () {
return jqXHR.abort();
};
upload();
return promise;
},
_beforeSend: function (e, data) {
if (this._active === 0) {
this._trigger('start');
this._bitrateTimer = new this._BitrateTimer();
this._progress.loaded = this._progress.total = 0;
this._progress.bitrate = 0;
}
this._initResponseObject(data);
this._initProgressObject(data);
data._progress.loaded = data.loaded = data.uploadedBytes || 0;
data._progress.total = data.total = this._getTotal(data.files) || 1;
data._progress.bitrate = data.bitrate = 0;
this._active += 1;
this._progress.loaded += data.loaded;
this._progress.total += data.total;
},
_onDone: function (result, textStatus, jqXHR, options) {
var total = options._progress.total,
response = options._response;
if (options._progress.loaded < total) {
this._onProgress($.Event('progress', {
lengthComputable: true,
loaded: total,
total: total
}), options);
}
response.result = options.result = result;
response.textStatus = options.textStatus = textStatus;
response.jqXHR = options.jqXHR = jqXHR;
this._trigger('done', null, options);
},
_onFail: function (jqXHR, textStatus, errorThrown, options) {
var response = options._response;
if (options.recalculateProgress) {
this._progress.loaded -= options._progress.loaded;
this._progress.total -= options._progress.total;
}
response.jqXHR = options.jqXHR = jqXHR;
response.textStatus = options.textStatus = textStatus;
response.errorThrown = options.errorThrown = errorThrown;
this._trigger('fail', null, options);
},
_onAlways: function (jqXHRorResult, textStatus, jqXHRorError, options) {
this._trigger('always', null, options);
},
_onSend: function (e, data) {
if (!data.submit) {
this._addConvenienceMethods(e, data);
}
var that = this,
jqXHR,
aborted,
slot,
pipe,
options = that._getAJAXSettings(data),
send = function () {
that._sending += 1;
options._bitrateTimer = new that._BitrateTimer();
jqXHR = jqXHR || (
((aborted || that._trigger(
'send',
$.Event('send', {delegatedEvent: e}),
options
) === false) &&
that._getXHRPromise(false, options.context, aborted)) ||
that._chunkedUpload(options) || $.ajax(options)
).done(function (result, textStatus, jqXHR) {
that._onDone(result, textStatus, jqXHR, options);
}).fail(function (jqXHR, textStatus, errorThrown) {
that._onFail(jqXHR, textStatus, errorThrown, options);
}).always(function (jqXHRorResult, textStatus, jqXHRorError) {
that._onAlways(
jqXHRorResult,
textStatus,
jqXHRorError,
options
);
that._sending -= 1;
that._active -= 1;
if (options.limitConcurrentUploads &&
options.limitConcurrentUploads > that._sending) {
var nextSlot = that._slots.shift();
while (nextSlot) {
if (that._getDeferredState(nextSlot) === 'pending') {
nextSlot.resolve();
break;
}
nextSlot = that._slots.shift();
}
}
if (that._active === 0) {
that._trigger('stop');
}
});
return jqXHR;
};
this._beforeSend(e, options);
if (this.options.sequentialUploads ||
(this.options.limitConcurrentUploads &&
this.options.limitConcurrentUploads <= this._sending)) {
if (this.options.limitConcurrentUploads > 1) {
slot = $.Deferred();
this._slots.push(slot);
pipe = slot.pipe(send);
} else {
this._sequence = this._sequence.pipe(send, send);
pipe = this._sequence;
}
pipe.abort = function () {
aborted = [undefined, 'abort', 'abort'];
if (!jqXHR) {
if (slot) {
slot.rejectWith(options.context, aborted);
}
return send();
}
return jqXHR.abort();
};
return this._enhancePromise(pipe);
}
return send();
},
_onAdd: function (e, data) {
var that = this,
result = true,
options = $.extend({}, this.options, data),
limit = options.limitMultiFileUploads,
paramName = this._getParamName(options),
paramNameSet,
paramNameSlice,
fileSet,
i;
if (!(options.singleFileUploads || limit) ||
!this._isXHRUpload(options)) {
fileSet = [data.files];
paramNameSet = [paramName];
} else if (!options.singleFileUploads && limit) {
fileSet = [];
paramNameSet = [];
for (i = 0; i < data.files.length; i += limit) {
fileSet.push(data.files.slice(i, i + limit));
paramNameSlice = paramName.slice(i, i + limit);
if (!paramNameSlice.length) {
paramNameSlice = paramName;
}
paramNameSet.push(paramNameSlice);
}
} else {
paramNameSet = paramName;
}
data.originalFiles = data.files;
$.each(fileSet || data.files, function (index, element) {
var newData = $.extend({}, data);
newData.files = fileSet ? element : [element];
newData.paramName = paramNameSet[index];
that._initResponseObject(newData);
that._initProgressObject(newData);
that._addConvenienceMethods(e, newData);
result = that._trigger(
'add',
$.Event('add', {delegatedEvent: e}),
newData
);
return result;
});
return result;
},
_replaceFileInput: function (input) {
var inputClone = input.clone(true);
$('<form></form>').append(inputClone)[0].reset();
input.after(inputClone).detach();
$.cleanData(input.unbind('remove'));
this.options.fileInput = this.options.fileInput.map(function (i, el) {
if (el === input[0]) {
return inputClone[0];
}
return el;
});
if (input[0] === this.element[0]) {
this.element = inputClone;
}
},
_handleFileTreeEntry: function (entry, path) {
var that = this,
dfd = $.Deferred(),
errorHandler = function (e) {
if (e && !e.entry) {
e.entry = entry;
}
dfd.resolve([e]);
},
dirReader;
path = path || '';
if (entry.isFile) {
if (entry._file) {
entry._file.relativePath = path;
dfd.resolve(entry._file);
} else {
entry.file(function (file) {
file.relativePath = path;
dfd.resolve(file);
}, errorHandler);
}
} else if (entry.isDirectory) {
dirReader = entry.createReader();
dirReader.readEntries(function (entries) {
that._handleFileTreeEntries(
entries,
path + entry.name + '/'
).done(function (files) {
dfd.resolve(files);
}).fail(errorHandler);
}, errorHandler);
} else {
dfd.resolve([]);
}
return dfd.promise();
},
_handleFileTreeEntries: function (entries, path) {
var that = this;
return $.when.apply(
$,
$.map(entries, function (entry) {
return that._handleFileTreeEntry(entry, path);
})
).pipe(function () {
return Array.prototype.concat.apply(
[],
arguments
);
});
},
_getDroppedFiles: function (dataTransfer) {
dataTransfer = dataTransfer || {};
var items = dataTransfer.items;
if (items && items.length && (items[0].webkitGetAsEntry ||
items[0].getAsEntry)) {
return this._handleFileTreeEntries(
$.map(items, function (item) {
var entry;
if (item.webkitGetAsEntry) {
entry = item.webkitGetAsEntry();
if (entry) {
entry._file = item.getAsFile();
}
return entry;
}
return item.getAsEntry();
})
);
}
return $.Deferred().resolve(
$.makeArray(dataTransfer.files)
).promise();
},
_getSingleFileInputFiles: function (fileInput) {
fileInput = $(fileInput);
var entries = fileInput.prop('webkitEntries') ||
fileInput.prop('entries'),
files,
value;
if (entries && entries.length) {
return this._handleFileTreeEntries(entries);
}
files = $.makeArray(fileInput.prop('files'));
if (!files.length) {
value = fileInput.prop('value');
if (!value) {
return $.Deferred().resolve([]).promise();
}
files = [{name: value.replace(/^.*\\/, '')}];
} else if (files[0].name === undefined && files[0].fileName) {
$.each(files, function (index, file) {
file.name = file.fileName;
file.size = file.fileSize;
});
}
return $.Deferred().resolve(files).promise();
},
_getFileInputFiles: function (fileInput) {
if (!(fileInput instanceof $) || fileInput.length === 1) {
return this._getSingleFileInputFiles(fileInput);
}
return $.when.apply(
$,
$.map(fileInput, this._getSingleFileInputFiles)
).pipe(function () {
return Array.prototype.concat.apply(
[],
arguments
);
});
},
_onChange: function (e) {
var that = this,
data = {
fileInput: $(e.target),
form: $(e.target.form)
};
this._getFileInputFiles(data.fileInput).always(function (files) {
data.files = files;
if (that.options.replaceFileInput) {
that._replaceFileInput(data.fileInput);
}
if (that._trigger(
'change',
$.Event('change', {delegatedEvent: e}),
data
) !== false) {
that._onAdd(e, data);
}
});
},
_onPaste: function (e) {
var items = e.originalEvent && e.originalEvent.clipboardData &&
e.originalEvent.clipboardData.items,
data = {files: []};
if (items && items.length) {
$.each(items, function (index, item) {
var file = item.getAsFile && item.getAsFile();
if (file) {
data.files.push(file);
}
});
if (this._trigger(
'paste',
$.Event('paste', {delegatedEvent: e}),
data
) !== false) {
this._onAdd(e, data);
}
}
},
_onDrop: function (e) {
e.dataTransfer = e.originalEvent && e.originalEvent.dataTransfer;
var that = this,
dataTransfer = e.dataTransfer,
data = {};
if (dataTransfer && dataTransfer.files && dataTransfer.files.length) {
e.preventDefault();
this._getDroppedFiles(dataTransfer).always(function (files) {
data.files = files;
if (that._trigger(
'drop',
$.Event('drop', {delegatedEvent: e}),
data
) !== false) {
that._onAdd(e, data);
}
});
}
},
_onDragOver: function (e) {
e.dataTransfer = e.originalEvent && e.originalEvent.dataTransfer;
var dataTransfer = e.dataTransfer;
if (dataTransfer && $.inArray('Files', dataTransfer.types) !== -1 &&
this._trigger(
'dragover',
$.Event('dragover', {delegatedEvent: e})
) !== false) {
e.preventDefault();
dataTransfer.dropEffect = 'copy';
}
},
_initEventHandlers: function () {
if (this._isXHRUpload(this.options)) {
this._on(this.options.dropZone, {
dragover: this._onDragOver,
drop: this._onDrop
});
this._on(this.options.pasteZone, {
paste: this._onPaste
});
}
if ($.support.fileInput) {
this._on(this.options.fileInput, {
change: this._onChange
});
}
},
_destroyEventHandlers: function () {
this._off(this.options.dropZone, 'dragover drop');
this._off(this.options.pasteZone, 'paste');
this._off(this.options.fileInput, 'change');
},
_setOption: function (key, value) {
var reinit = $.inArray(key, this._specialOptions) !== -1;
if (reinit) {
this._destroyEventHandlers();
}
this._super(key, value);
if (reinit) {
this._initSpecialOptions();
this._initEventHandlers();
}
},
_initSpecialOptions: function () {
var options = this.options;
if (options.fileInput === undefined) {
options.fileInput = this.element.is('input[type="file"]') ?
this.element : this.element.find('input[type="file"]');
} else if (!(options.fileInput instanceof $)) {
options.fileInput = $(options.fileInput);
}
if (!(options.dropZone instanceof $)) {
options.dropZone = $(options.dropZone);
}
if (!(options.pasteZone instanceof $)) {
options.pasteZone = $(options.pasteZone);
}
},
_getRegExp: function (str) {
var parts = str.split('/'),
modifiers = parts.pop();
parts.shift();
return new RegExp(parts.join('/'), modifiers);
},
_isRegExpOption: function (key, value) {
return key !== 'url' && $.type(value) === 'string' &&
/^\/.*\/[igm]{0,3}$/.test(value);
},
_initDataAttributes: function () {
var that = this,
options = this.options;
$.each(
$(this.element[0].cloneNode(false)).data(),
function (key, value) {
if (that._isRegExpOption(key, value)) {
value = that._getRegExp(value);
}
options[key] = value;
}
);
},
_create: function () {
this._initDataAttributes();
this._initSpecialOptions();
this._slots = [];
this._sequence = this._getXHRPromise(true);
this._sending = this._active = 0;
this._initProgressObject(this);
this._initEventHandlers();
},
active: function () {
return this._active;
},
progress: function () {
return this._progress;
},
add: function (data) {
var that = this;
if (!data || this.options.disabled) {
return;
}
if (data.fileInput && !data.files) {
this._getFileInputFiles(data.fileInput).always(function (files) {
data.files = files;
that._onAdd(null, data);
});
} else {
data.files = $.makeArray(data.files);
this._onAdd(null, data);
}
},
send: function (data) {
if (data && !this.options.disabled) {
if (data.fileInput && !data.files) {
var that = this,
dfd = $.Deferred(),
promise = dfd.promise(),
jqXHR,
aborted;
promise.abort = function () {
aborted = true;
if (jqXHR) {
return jqXHR.abort();
}
dfd.reject(null, 'abort', 'abort');
return promise;
};
this._getFileInputFiles(data.fileInput).always(
function (files) {
if (aborted) {
return;
}
if (!files.length) {
dfd.reject();
return;
}
data.files = files;
jqXHR = that._onSend(null, data).then(
function (result, textStatus, jqXHR) {
dfd.resolve(result, textStatus, jqXHR);
},
function (jqXHR, textStatus, errorThrown) {
dfd.reject(jqXHR, textStatus, errorThrown);
}
);
}
);
return this._enhancePromise(promise);
}
data.files = $.makeArray(data.files);
if (data.files.length) {
return this._onSend(null, data);
}
}
return this._getXHRPromise(false, data && data.context);
}
});
}));


(function(o){var t={url:!1,callback:!1,target:!1,duration:120,on:"mouseover",touch:!0,onZoomIn:!1,onZoomOut:!1,magnify:1};o.zoom=function(t,n,e,i){var u,c,a,m,l,r,s,f=o(t).css("position"),h=o(n);return t.style.position=/(absolute|fixed)/.test(f)?f:"relative",t.style.overflow="hidden",e.style.width=e.style.height="",o(e).addClass("zoomImg").css({position:"absolute",top:0,left:0,opacity:0,width:e.width*i,height:e.height*i,border:"none",maxWidth:"none",maxHeight:"none"}).appendTo(t),{init:function(){c=o(t).outerWidth(),u=o(t).outerHeight(),n===t?(m=c,a=u):(m=h.outerWidth(),a=h.outerHeight()),l=(e.width-c)/m,r=(e.height-u)/a,s=h.offset()},move:function(o){var t=o.pageX-s.left,n=o.pageY-s.top;n=Math.max(Math.min(n,a),0),t=Math.max(Math.min(t,m),0),e.style.left=t*-l+"px",e.style.top=n*-r+"px"}}},o.fn.zoom=function(n){return this.each(function(){var e,i=o.extend({},t,n||{}),u=i.target||this,c=this,a=o(c),m=document.createElement("img"),l=o(m),r="mousemove.zoom",s=!1,f=!1;(i.url||(e=a.find("img"),e[0]&&(i.url=e.data("src")||e.attr("src")),i.url))&&(function(){var o=u.style.position,t=u.style.overflow;a.one("zoom.destroy",function(){a.off(".zoom"),u.style.position=o,u.style.overflow=t,l.remove()})}(),m.onload=function(){function t(t){e.init(),e.move(t),l.stop().fadeTo(o.support.opacity?i.duration:0,1,o.isFunction(i.onZoomIn)?i.onZoomIn.call(m):!1)}function n(){l.stop().fadeTo(i.duration,0,o.isFunction(i.onZoomOut)?i.onZoomOut.call(m):!1)}var e=o.zoom(u,c,m,i.magnify);"grab"===i.on?a.on("mousedown.zoom",function(i){1===i.which&&(o(document).one("mouseup.zoom",function(){n(),o(document).off(r,e.move)}),t(i),o(document).on(r,e.move),i.preventDefault())}):"click"===i.on?a.on("click.zoom",function(i){return s?void 0:(s=!0,t(i),o(document).on(r,e.move),o(document).one("click.zoom",function(){n(),s=!1,o(document).off(r,e.move)}),!1)}):"toggle"===i.on?a.on("click.zoom",function(o){s?n():t(o),s=!s}):"mouseover"===i.on&&(e.init(),a.on("mouseenter.zoom",t).on("mouseleave.zoom",n).on(r,e.move)),i.touch&&a.on("touchstart.zoom",function(o){o.preventDefault(),f?(f=!1,n()):(f=!0,t(o.originalEvent.touches[0]||o.originalEvent.changedTouches[0]))}).on("touchmove.zoom",function(o){o.preventDefault(),e.move(o.originalEvent.touches[0]||o.originalEvent.changedTouches[0])}),o.isFunction(i.callback)&&i.callback.call(m)},m.src=i.url)})},o.fn.zoom.defaults=t})(window.jQuery);

!function( $ ) {
function UTCDate(){
return new Date(Date.UTC.apply(Date, arguments));
}
function UTCToday(){
var today = new Date();
return UTCDate(today.getUTCFullYear(), today.getUTCMonth(), today.getUTCDate());
}
var Datepicker = function(element, options) {
var that = this;
this.element = $(element);
this.closeButton = options.closeButton;
this.language = options.language||this.element.data('date-language')||"en";
this.language = this.language in dates ? this.language : this.language.split('-')[0]; //Check if "de-DE" style date is available, if not language should fallback to 2 letter code eg "de"
this.language = this.language in dates ? this.language : "en";
this.isRTL = dates[this.language].rtl||false;
this.format = DPGlobal.parseFormat(options.format||this.element.data('date-format')||dates[this.language].format||'mm/dd/yyyy');
this.isInline = false;
this.isInput = this.element.is('input');
this.component = this.element.is('.date') ? this.element.find('.prefix, .postfix') : false;
this.hasInput = this.component && this.element.find('input').length;
this.onRender = options.onRender || function () {};
if(this.component && this.component.length === 0)
this.component = false;
this._attachEvents();
this.forceParse = true;
if ('forceParse' in options) {
this.forceParse = options.forceParse;
} else if ('dateForceParse' in this.element.data()) {
this.forceParse = this.element.data('date-force-parse');
}
this.picker = $(DPGlobal.template)
.appendTo(this.isInline ? this.element : 'body')
.on({
click: $.proxy(this.click, this),
mousedown: $.proxy(this.mousedown, this)
});
if (this.closeButton){
this.picker.find('a.datepicker-close').show();
}
if(this.isInline) {
this.picker.addClass('datepicker-inline');
} else {
this.picker.addClass('datepicker-dropdown dropdown-menu');
}
if (this.isRTL){
this.picker.addClass('datepicker-rtl');
this.picker.find('.prev i, .next i')
.toggleClass('fa fa-chevron-left fa-chevron-right').toggleClass('fa-chevron-left fa-chevron-right');
}
$(document).on('mousedown', function (e) {
if ($(e.target).closest('.datepicker.datepicker-inline, .datepicker.datepicker-dropdown').length === 0) {
that.hide();
}
});
this.autoclose = true;
if ('autoclose' in options) {
this.autoclose = options.autoclose;
} else if ('dateAutoclose' in this.element.data()) {
this.autoclose = this.element.data('date-autoclose');
}
this.keyboardNavigation = true;
if ('keyboardNavigation' in options) {
this.keyboardNavigation = options.keyboardNavigation;
} else if ('dateKeyboardNavigation' in this.element.data()) {
this.keyboardNavigation = this.element.data('date-keyboard-navigation');
}
this.viewMode = this.startViewMode = 0;
switch(options.startView || this.element.data('date-start-view')){
case 2:
case 'decade':
this.viewMode = this.startViewMode = 2;
break;
case 1:
case 'year':
this.viewMode = this.startViewMode = 1;
break;
}
this.todayBtn = (options.todayBtn||this.element.data('date-today-btn')||false);
this.todayHighlight = (options.todayHighlight||this.element.data('date-today-highlight')||false);
this.calendarWeeks = false;
if ('calendarWeeks' in options) {
this.calendarWeeks = options.calendarWeeks;
} else if ('dateCalendarWeeks' in this.element.data()) {
this.calendarWeeks = this.element.data('date-calendar-weeks');
}
if (this.calendarWeeks)
this.picker.find('tfoot th.today')
.attr('colspan', function(i, val){
return parseInt(val) + 1;
});
this.weekStart = ((options.weekStart||this.element.data('date-weekstart')||dates[this.language].weekStart||0) % 7);
this.weekEnd = ((this.weekStart + 6) % 7);
this.startDate = -Infinity;
this.endDate = Infinity;
this.daysOfWeekDisabled = [];
this.setStartDate(options.startDate||this.element.data('date-startdate'));
this.setEndDate(options.endDate||this.element.data('date-enddate'));
this.setDaysOfWeekDisabled(options.daysOfWeekDisabled||this.element.data('date-days-of-week-disabled'));
this.fillDow();
this.fillMonths();
this.update();
this.showMode();
if(this.isInline) {
this.show();
}
};
Datepicker.prototype = {
constructor: Datepicker,
_events: [],
_attachEvents: function(){
this._detachEvents();
if (this.isInput) { // single input
this._events = [
[this.element, {
focus: $.proxy(this.show, this),
keyup: $.proxy(this.update, this),
keydown: $.proxy(this.keydown, this)
}]
];
}
else if (this.component && this.hasInput){ // component: input + button
this._events = [
[this.element.find('input'), {
focus: $.proxy(this.show, this),
keyup: $.proxy(this.update, this),
keydown: $.proxy(this.keydown, this)
}],
[this.component, {
click: $.proxy(this.show, this)
}]
];
}
else if (this.element.is('div')) {  // inline datepicker
this.isInline = true;
}
else {
this._events = [
[this.element, {
click: $.proxy(this.show, this)
}]
];
}
for (var i=0, el, ev; i<this._events.length; i++){
el = this._events[i][0];
ev = this._events[i][1];
el.on(ev);
}
},
_detachEvents: function(){
for (var i=0, el, ev; i<this._events.length; i++){
el = this._events[i][0];
ev = this._events[i][1];
el.off(ev);
}
this._events = [];
},
show: function(e) {
this.picker.show();
this.height = this.component ? this.component.outerHeight() : this.element.outerHeight();
this.update();
this.place();
$(window).on('resize', $.proxy(this.place, this));
if (e ) {
e.stopPropagation();
e.preventDefault();
}
this.element.trigger({
type: 'show',
date: this.date
});
},
hide: function(e){
if(this.isInline) return;
if (!this.picker.is(':visible')) return;
this.picker.hide();
$(window).off('resize', this.place);
this.viewMode = this.startViewMode;
this.showMode();
if (!this.isInput) {
$(document).off('mousedown', this.hide);
}
if (
this.forceParse &&
(
this.isInput && this.element.val() ||
this.hasInput && this.element.find('input').val()
)
)
this.setValue();
this.element.trigger({
type: 'hide',
date: this.date
});
},
remove: function() {
this._detachEvents();
this.picker.remove();
delete this.element.data().datepicker;
},
getDate: function() {
var d = this.getUTCDate();
return new Date(d.getTime() + (d.getTimezoneOffset()*60000));
},
getUTCDate: function() {
return this.date;
},
setDate: function(d) {
this.setUTCDate(new Date(d.getTime() - (d.getTimezoneOffset()*60000)));
},
setUTCDate: function(d) {
this.date = d;
this.setValue();
},
setValue: function() {
var formatted = this.getFormattedDate();
if (!this.isInput) {
if (this.component){
this.element.find('input').val(formatted);
}
this.element.data('date', formatted);
} else {
this.element.val(formatted);
}
},
getFormattedDate: function(format) {
if (format === undefined)
format = this.format;
return DPGlobal.formatDate(this.date, format, this.language);
},
setStartDate: function(startDate){
this.startDate = startDate||-Infinity;
if (this.startDate !== -Infinity) {
this.startDate = DPGlobal.parseDate(this.startDate, this.format, this.language);
}
this.update();
this.updateNavArrows();
},
setEndDate: function(endDate){
this.endDate = endDate||Infinity;
if (this.endDate !== Infinity) {
this.endDate = DPGlobal.parseDate(this.endDate, this.format, this.language);
}
this.update();
this.updateNavArrows();
},
setDaysOfWeekDisabled: function(daysOfWeekDisabled){
this.daysOfWeekDisabled = daysOfWeekDisabled||[];
if (!$.isArray(this.daysOfWeekDisabled)) {
this.daysOfWeekDisabled = this.daysOfWeekDisabled.split(/,\s*/);
}
this.daysOfWeekDisabled = $.map(this.daysOfWeekDisabled, function (d) {
return parseInt(d, 10);
});
this.update();
this.updateNavArrows();
},
place: function(){
if(this.isInline) return;
var zIndex = parseInt(this.element.parents().filter(function() {
return $(this).css('z-index') != 'auto';
}).first().css('z-index'))+10;
var textbox = this.component ? this.component : this.element;
var offset = textbox.offset();
var height = textbox.outerHeight() + parseInt(textbox.css('margin-top'));
var width = textbox.outerWidth() + parseInt(textbox.css('margin-left'));
var fullOffsetTop = offset.top + height;
var offsetLeft = offset.left;
if((fullOffsetTop + this.picker.height()) >= $(window).scrollTop() + $(window).height()){
fullOffsetTop = offset.top - height - this.picker.height();
}
if(offset.left + this.picker.width() >= $(window).width()){
offsetLeft = (offset.left + width)  - this.picker.width();
}
this.picker.css({
top: fullOffsetTop,
left: offsetLeft,
zIndex: zIndex
});
},
update: function(){
var date, fromArgs = false;
if(arguments && arguments.length && (typeof arguments[0] === 'string' || arguments[0] instanceof Date)) {
date = arguments[0];
fromArgs = true;
} else {
date = this.isInput ? this.element.val() : this.element.data('date') || this.element.find('input').val();
}
this.date = DPGlobal.parseDate(date, this.format, this.language);
if(fromArgs) this.setValue();
if (this.date < this.startDate) {
this.viewDate = new Date(this.startDate.valueOf());
} else if (this.date > this.endDate) {
this.viewDate = new Date(this.endDate.valueOf());
} else {
this.viewDate = new Date(this.date.valueOf());
}
this.fill();
},
fillDow: function(){
var dowCnt = this.weekStart,
html = '<tr>';
if(this.calendarWeeks){
var cell = '<th class="cw">&nbsp;</th>';
html += cell;
this.picker.find('.datepicker-days thead tr:first-child').prepend(cell);
}
while (dowCnt < this.weekStart + 7) {
html += '<th class="dow">'+dates[this.language].daysMin[(dowCnt++)%7]+'</th>';
}
html += '</tr>';
this.picker.find('.datepicker-days thead').append(html);
},
fillMonths: function(){
var html = '',
i = 0;
while (i < 12) {
html += '<span class="month">'+dates[this.language].monthsShort[i++]+'</span>';
}
this.picker.find('.datepicker-months td').html(html);
},
fill: function() {
var d = new Date(this.viewDate.valueOf()),
year = d.getUTCFullYear(),
month = d.getUTCMonth(),
startYear = this.startDate !== -Infinity ? this.startDate.getUTCFullYear() : -Infinity,
startMonth = this.startDate !== -Infinity ? this.startDate.getUTCMonth() : -Infinity,
endYear = this.endDate !== Infinity ? this.endDate.getUTCFullYear() : Infinity,
endMonth = this.endDate !== Infinity ? this.endDate.getUTCMonth() : Infinity,
currentDate = this.date && this.date.valueOf(),
today = new Date();
this.picker.find('.datepicker-days thead th.date-switch')
.text(dates[this.language].months[month]+' '+year);
this.picker.find('tfoot th.today')
.text(dates[this.language].today)
.toggle(this.todayBtn !== false);
this.updateNavArrows();
this.fillMonths();
var prevMonth = UTCDate(year, month-1, 28,0,0,0,0),
day = DPGlobal.getDaysInMonth(prevMonth.getUTCFullYear(), prevMonth.getUTCMonth());
prevMonth.setUTCDate(day);
prevMonth.setUTCDate(day - (prevMonth.getUTCDay() - this.weekStart + 7)%7);
var nextMonth = new Date(prevMonth.valueOf());
nextMonth.setUTCDate(nextMonth.getUTCDate() + 42);
nextMonth = nextMonth.valueOf();
var html = [];
var clsName;
while(prevMonth.valueOf() < nextMonth) {
if (prevMonth.getUTCDay() == this.weekStart) {
html.push('<tr>');
if(this.calendarWeeks){
var a = new Date(prevMonth.getUTCFullYear(), prevMonth.getUTCMonth(), prevMonth.getUTCDate() - prevMonth.getDay() + 10 - (this.weekStart && this.weekStart%7 < 5 && 7)),
b = new Date(a.getFullYear(), 0, 4),
calWeek =  ~~((a - b) / 864e5 / 7 + 1.5);
html.push('<td class="cw">'+ calWeek +'</td>');
}
}
clsName = ' '+this.onRender(prevMonth)+' ';
if (prevMonth.getUTCFullYear() < year || (prevMonth.getUTCFullYear() == year && prevMonth.getUTCMonth() < month)) {
clsName += ' old';
} else if (prevMonth.getUTCFullYear() > year || (prevMonth.getUTCFullYear() == year && prevMonth.getUTCMonth() > month)) {
clsName += ' new';
}
if (this.todayHighlight &&
prevMonth.getUTCFullYear() == today.getFullYear() &&
prevMonth.getUTCMonth() == today.getMonth() &&
prevMonth.getUTCDate() == today.getDate()) {
clsName += ' today';
}
if (currentDate && prevMonth.valueOf() == currentDate) {
clsName += ' active';
}
if (prevMonth.valueOf() < this.startDate || prevMonth.valueOf() > this.endDate ||
$.inArray(prevMonth.getUTCDay(), this.daysOfWeekDisabled) !== -1) {
clsName += ' disabled';
}
html.push('<td class="day'+clsName+'">'+prevMonth.getUTCDate() + '</td>');
if (prevMonth.getUTCDay() == this.weekEnd) {
html.push('</tr>');
}
prevMonth.setUTCDate(prevMonth.getUTCDate()+1);
}
this.picker.find('.datepicker-days tbody').empty().append(html.join(''));
var currentYear = this.date && this.date.getUTCFullYear();
var months = this.picker.find('.datepicker-months')
.find('th:eq(1)')
.text(year)
.end()
.find('span').removeClass('active');
if (currentYear && currentYear == year) {
months.eq(this.date.getUTCMonth()).addClass('active');
}
if (year < startYear || year > endYear) {
months.addClass('disabled');
}
if (year == startYear) {
months.slice(0, startMonth).addClass('disabled');
}
if (year == endYear) {
months.slice(endMonth+1).addClass('disabled');
}
html = '';
year = parseInt(year/10, 10) * 10;
var yearCont = this.picker.find('.datepicker-years')
.find('th:eq(1)')
.text(year + '-' + (year + 9))
.end()
.find('td');
year -= 1;
for (var i = -1; i < 11; i++) {
html += '<span class="year'+(i == -1 || i == 10 ? ' old' : '')+(currentYear == year ? ' active' : '')+(year < startYear || year > endYear ? ' disabled' : '')+'">'+year+'</span>';
year += 1;
}
yearCont.html(html);
},
updateNavArrows: function() {
var d = new Date(this.viewDate.valueOf()),
year = d.getUTCFullYear(),
month = d.getUTCMonth();
switch (this.viewMode) {
case 0:
if (this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear() && month <= this.startDate.getUTCMonth()) {
this.picker.find('.prev').css({visibility: 'hidden'});
} else {
this.picker.find('.prev').css({visibility: 'visible'});
}
if (this.endDate !== Infinity && year >= this.endDate.getUTCFullYear() && month >= this.endDate.getUTCMonth()) {
this.picker.find('.next').css({visibility: 'hidden'});
} else {
this.picker.find('.next').css({visibility: 'visible'});
}
break;
case 1:
case 2:
if (this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear()) {
this.picker.find('.prev').css({visibility: 'hidden'});
} else {
this.picker.find('.prev').css({visibility: 'visible'});
}
if (this.endDate !== Infinity && year >= this.endDate.getUTCFullYear()) {
this.picker.find('.next').css({visibility: 'hidden'});
} else {
this.picker.find('.next').css({visibility: 'visible'});
}
break;
}
},
click: function(e) {
e.stopPropagation();
e.preventDefault();
if ($(e.target).hasClass('datepicker-close')){
this.hide();
}
var target = $(e.target).closest('span, td, th');
if (target.length == 1) {
switch(target[0].nodeName.toLowerCase()) {
case 'th':
switch(target[0].className) {
case 'date-switch':
this.showMode(1);
break;
case 'prev':
case 'next':
var dir = DPGlobal.modes[this.viewMode].navStep * (target[0].className == 'prev' ? -1 : 1);
switch(this.viewMode){
case 0:
this.viewDate = this.moveMonth(this.viewDate, dir);
break;
case 1:
case 2:
this.viewDate = this.moveYear(this.viewDate, dir);
break;
}
this.fill();
break;
case 'today':
var date = new Date();
date = UTCDate(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0);
this.showMode(-2);
var which = this.todayBtn == 'linked' ? null : 'view';
this._setDate(date, which);
break;
}
break;
case 'span':
if (!target.is('.disabled')) {
this.viewDate.setUTCDate(1);
if (target.is('.month')) {
var month = target.parent().find('span').index(target);
this.viewDate.setUTCMonth(month);
this.element.trigger({
type: 'changeMonth',
date: this.viewDate
});
} else {
var year = parseInt(target.text(), 10)||0;
this.viewDate.setUTCFullYear(year);
this.element.trigger({
type: 'changeYear',
date: this.viewDate
});
}
this.showMode(-1);
this.fill();
}
break;
case 'td':
if (target.is('.day') && !target.is('.disabled')){
var day = parseInt(target.text(), 10)||1;
var year = this.viewDate.getUTCFullYear(),
month = this.viewDate.getUTCMonth();
if (target.is('.old')) {
if (month === 0) {
month = 11;
year -= 1;
} else {
month -= 1;
}
} else if (target.is('.new')) {
if (month == 11) {
month = 0;
year += 1;
} else {
month += 1;
}
}
this._setDate(UTCDate(year, month, day,0,0,0,0));
}
break;
}
}
},
_setDate: function(date, which){
if (!which || which == 'date')
this.date = date;
if (!which || which  == 'view')
this.viewDate = date;
this.fill();
this.setValue();
this.element.trigger({
type: 'changeDate',
date: this.date
});
var element;
if (this.isInput) {
element = this.element;
} else if (this.component){
element = this.element.find('input');
}
if (element) {
element.change();
if (this.autoclose && (!which || which == 'date')) {
this.hide();
}
}
},
moveMonth: function(date, dir){
if (!dir) return date;
var new_date = new Date(date.valueOf()),
day = new_date.getUTCDate(),
month = new_date.getUTCMonth(),
mag = Math.abs(dir),
new_month, test;
dir = dir > 0 ? 1 : -1;
if (mag == 1){
test = dir == -1
? function(){ return new_date.getUTCMonth() == month; }
: function(){ return new_date.getUTCMonth() != new_month; };
new_month = month + dir;
new_date.setUTCMonth(new_month);
if (new_month < 0 || new_month > 11)
new_month = (new_month + 12) % 12;
} else {
for (var i=0; i<mag; i++)
new_date = this.moveMonth(new_date, dir);
new_month = new_date.getUTCMonth();
new_date.setUTCDate(day);
test = function(){ return new_month != new_date.getUTCMonth(); };
}
while (test()){
new_date.setUTCDate(--day);
new_date.setUTCMonth(new_month);
}
return new_date;
},
moveYear: function(date, dir){
return this.moveMonth(date, dir*12);
},
dateWithinRange: function(date){
return date >= this.startDate && date <= this.endDate;
},
keydown: function(e){
if (this.picker.is(':not(:visible)')){
if (e.keyCode == 27) // allow escape to hide and re-show picker
this.show();
return;
}
var dateChanged = false,
dir, day, month,
newDate, newViewDate;
switch(e.keyCode){
case 27: // escape
this.hide();
e.preventDefault();
break;
case 37: // left
case 39: // right
if (!this.keyboardNavigation) break;
dir = e.keyCode == 37 ? -1 : 1;
if (e.ctrlKey){
newDate = this.moveYear(this.date, dir);
newViewDate = this.moveYear(this.viewDate, dir);
} else if (e.shiftKey){
newDate = this.moveMonth(this.date, dir);
newViewDate = this.moveMonth(this.viewDate, dir);
} else {
newDate = new Date(this.date.valueOf());
newDate.setUTCDate(this.date.getUTCDate() + dir);
newViewDate = new Date(this.viewDate.valueOf());
newViewDate.setUTCDate(this.viewDate.getUTCDate() + dir);
}
if (this.dateWithinRange(newDate)){
this.date = newDate;
this.viewDate = newViewDate;
this.setValue();
this.update();
e.preventDefault();
dateChanged = true;
}
break;
case 38: // up
case 40: // down
if (!this.keyboardNavigation) break;
dir = e.keyCode == 38 ? -1 : 1;
if (e.ctrlKey){
newDate = this.moveYear(this.date, dir);
newViewDate = this.moveYear(this.viewDate, dir);
} else if (e.shiftKey){
newDate = this.moveMonth(this.date, dir);
newViewDate = this.moveMonth(this.viewDate, dir);
} else {
newDate = new Date(this.date.valueOf());
newDate.setUTCDate(this.date.getUTCDate() + dir * 7);
newViewDate = new Date(this.viewDate.valueOf());
newViewDate.setUTCDate(this.viewDate.getUTCDate() + dir * 7);
}
if (this.dateWithinRange(newDate)){
this.date = newDate;
this.viewDate = newViewDate;
this.setValue();
this.update();
e.preventDefault();
dateChanged = true;
}
break;
case 13: // enter
this.hide();
e.preventDefault();
break;
case 9: // tab
this.hide();
break;
}
if (dateChanged){
this.element.trigger({
type: 'changeDate',
date: this.date
});
var element;
if (this.isInput) {
element = this.element;
} else if (this.component){
element = this.element.find('input');
}
if (element) {
element.change();
}
}
},
showMode: function(dir) {
if (dir) {
this.viewMode = Math.max(0, Math.min(2, this.viewMode + dir));
}
this.picker.find('>div').hide().filter('.datepicker-'+DPGlobal.modes[this.viewMode].clsName).css('display', 'block');
this.updateNavArrows();
}
};
$.fn.fdatepicker = function ( option ) {
var args = Array.apply(null, arguments);
args.shift();
return this.each(function () {
var $this = $(this),
data = $this.data('datepicker'),
options = typeof option == 'object' && option;
if (!data) {
$this.data('datepicker', (data = new Datepicker(this, $.extend({}, $.fn.fdatepicker.defaults,options))));
}
if (typeof option == 'string' && typeof data[option] == 'function') {
data[option].apply(data, args);
}
});
};
$.fn.fdatepicker.defaults = {
onRender: function(date) {
return '';
}
};
$.fn.fdatepicker.Constructor = Datepicker;
var dates = $.fn.fdatepicker.dates = {
en: {
days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
today: "Today"
},
pl: {
days: ["Niedziela", "Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela"],
daysShort: ["Nie", "Pon", "Wt", "Śr", "Czw", "Pt", "Sob", "Nie"],
daysMin: ["Nd", "Po", "Wt", "Śr", "Czw", "Pt", "So", "Nd"],
months: ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"],
monthsShort: ["Sty", "Lut", "Mar", "Kwi", "Maj", "Cze", "Lip", "Sie", "Wrz", "Paź", "Lit", "Gru"],
today: "Dzisiaj"
},
es: {
days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab", "Dom"],
daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Deciembre"],
monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
today: "Hoy"
},
pt: {
days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo"],
daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", "Dom"],
daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sá", "Do"],
months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
today: "Hoje"
},
it: {
days: ["Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica"],
daysShort: ["Dom", "Lun", "Mar", "Mer", "Gio", "Veb", "Sab", "Dom"],
daysMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa", "Do"],
months: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
monthsShort: ["Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"],
today: "Oggi"
}
};
var DPGlobal = {
modes: [
{
clsName: 'days',
navFnc: 'Month',
navStep: 1
},
{
clsName: 'months',
navFnc: 'FullYear',
navStep: 1
},
{
clsName: 'years',
navFnc: 'FullYear',
navStep: 10
}],
isLeapYear: function (year) {
return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0));
},
getDaysInMonth: function (year, month) {
return [31, (DPGlobal.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
},
validParts: /dd?|DD?|mm?|MM?|yy(?:yy)?/g,
nonpunctuation: /[^ -\/:-@\[\u3400-\u9fff-`{-~\t\n\r]+/g,
parseFormat: function(format){
var separators = format.replace(this.validParts, '\0').split('\0'),
parts = format.match(this.validParts);
if (!separators || !separators.length || !parts || parts.length === 0){
throw new Error("Invalid date format.");
}
return {separators: separators, parts: parts};
},
parseDate: function(date, format, language) {
if (date instanceof Date) return date;
if (/^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/.test(date)) {
var part_re = /([\-+]\d+)([dmwy])/,
parts = date.match(/([\-+]\d+)([dmwy])/g),
part, dir;
date = new Date();
for (var i=0; i<parts.length; i++) {
part = part_re.exec(parts[i]);
dir = parseInt(part[1]);
switch(part[2]){
case 'd':
date.setUTCDate(date.getUTCDate() + dir);
break;
case 'm':
date = Datepicker.prototype.moveMonth.call(Datepicker.prototype, date, dir);
break;
case 'w':
date.setUTCDate(date.getUTCDate() + dir * 7);
break;
case 'y':
date = Datepicker.prototype.moveYear.call(Datepicker.prototype, date, dir);
break;
}
}
return UTCDate(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), 0, 0, 0);
}
var parts = date && date.match(this.nonpunctuation) || [],
date = new Date(),
parsed = {},
setters_order = ['yyyy', 'yy', 'M', 'MM', 'm', 'mm', 'd', 'dd'],
setters_map = {
yyyy: function(d,v){ return d.setUTCFullYear(v); },
yy: function(d,v){ return d.setUTCFullYear(2000+v); },
m: function(d,v){
v -= 1;
while (v<0) v += 12;
v %= 12;
d.setUTCMonth(v);
while (d.getUTCMonth() != v)
d.setUTCDate(d.getUTCDate()-1);
return d;
},
d: function(d,v){ return d.setUTCDate(v); }
},
val, filtered, part;
setters_map['M'] = setters_map['MM'] = setters_map['mm'] = setters_map['m'];
setters_map['dd'] = setters_map['d'];
date = UTCDate(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0);
var fparts = format.parts.slice();
if (parts.length != fparts.length) {
fparts = $(fparts).filter(function(i,p){
return $.inArray(p, setters_order) !== -1;
}).toArray();
}
if (parts.length == fparts.length) {
for (var i=0, cnt = fparts.length; i < cnt; i++) {
val = parseInt(parts[i], 10);
part = fparts[i];
if (isNaN(val)) {
switch(part) {
case 'MM':
filtered = $(dates[language].months).filter(function(){
var m = this.slice(0, parts[i].length),
p = parts[i].slice(0, m.length);
return m == p;
});
val = $.inArray(filtered[0], dates[language].months) + 1;
break;
case 'M':
filtered = $(dates[language].monthsShort).filter(function(){
var m = this.slice(0, parts[i].length),
p = parts[i].slice(0, m.length);
return m == p;
});
val = $.inArray(filtered[0], dates[language].monthsShort) + 1;
break;
}
}
parsed[part] = val;
}
for (var i=0, s; i<setters_order.length; i++){
s = setters_order[i];
if (s in parsed && !isNaN(parsed[s]))
setters_map[s](date, parsed[s]);
}
}
return date;
},
formatDate: function(date, format, language){
var val = {
d: date.getUTCDate(),
D: dates[language].daysShort[date.getUTCDay()],
DD: dates[language].days[date.getUTCDay()],
m: date.getUTCMonth() + 1,
M: dates[language].monthsShort[date.getUTCMonth()],
MM: dates[language].months[date.getUTCMonth()],
yy: date.getUTCFullYear().toString().substring(2),
yyyy: date.getUTCFullYear()
};
val.dd = (val.d < 10 ? '0' : '') + val.d;
val.mm = (val.m < 10 ? '0' : '') + val.m;
var date = [],
seps = $.extend([], format.separators);
for (var i=0, cnt = format.parts.length; i < cnt; i++) {
if (seps.length)
date.push(seps.shift());
date.push(val[format.parts[i]]);
}
return date.join('');
},
headTemplate: '<thead>'+
'<tr>'+
'<th class="prev"><i class="fa fa-chevron-left fa-chevron-left"/></th>'+
'<th colspan="5" class="date-switch"></th>'+
'<th class="next"><i class="fa fa-chevron-right fa-chevron-right"/></th>'+
'</tr>'+
'</thead>',
contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>',
footTemplate: '<tfoot><tr><th colspan="7" class="today"></th></tr></tfoot>'
};
DPGlobal.template = '<div class="datepicker">'+
'<div class="datepicker-days">'+
'<table class=" table-condensed">'+
DPGlobal.headTemplate+
'<tbody></tbody>'+
DPGlobal.footTemplate+
'</table>'+
'</div>'+
'<div class="datepicker-months">'+
'<table class="table-condensed">'+
DPGlobal.headTemplate+
DPGlobal.contTemplate+
DPGlobal.footTemplate+
'</table>'+
'</div>'+
'<div class="datepicker-years">'+
'<table class="table-condensed">'+
DPGlobal.headTemplate+
DPGlobal.contTemplate+
DPGlobal.footTemplate+
'</table>'+
'</div>'+
'<a class="button datepicker-close small alert right" style="width:auto;"><i class="fa fa-remove fa-times"></i></a>'+
'</div>';
$.fn.fdatepicker.DPGlobal = DPGlobal;
}( window.jQuery );
