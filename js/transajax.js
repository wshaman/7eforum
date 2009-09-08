/***********************************************************************************************
 * TransAjax (Transport Ajax Library) v1.3 developed by Sergey Shuchkin, sshuchkin@sibvision.ru
 * Supported content types: HTML, JSON, XML, sPHP
\***********************************************************************************************																	
 * History:
 * 1.3 - 13 oct 2008
 * + Added global events support: TransAjax.onStart, TransAjax.onSend, TransAjax.onComplete, TransAjax.onStop, TransAjax.onError
 * - some fixes
 *
 * 1.2.2 - 14 sep 2008
 * + Added TransAjax.getQueryString (form data or object serialization to url string)
 * + Added autodetect form type to ajax submit
 * + phpSerialize functions moved to TransAjax namespace
 * + Replaced "busy image" support behaviour
 * - Fixed IE bugs with IFRAME submissions
 *
 * 1.2.1 - 23 jun 2008
 * - Fixed ajax_submit, iframe added to BODY now (NOT in self form)
 *
 * 1.2 - dec 2007 Full featured ajax engine
 *
 * Usage:
   <script type="text/javascript" src="transajax.js"></script>
   <script type="text/javascript">
   <!--
   function load_news( id ) {
	   ajax("news.php?ajax=1&id="+id, "divNewsContent");
   }
   function set_rating( r ) {
	   ajax("ratings.php?set="+r, _set_rating);
   }
   function _set_rating( obj ) {
	   alert("OK!");
	   document.getElementById("spnRatingMessage").innerHTML = obj.responseText;
   }
   function save_comment() {
	   ajax_submit("frmComment", _save_comment);
   }
   function _save_comment(obj) {
	   if (obj.success) {
		   alert("OK!");
	   } else {
		   alert("Error!\n"+obj.error);
	   }
   }
   -->
   </script>
 */
// TransAjax.exec() wrapper
function ajax(url, container_id_or_callback,method, content) {
	if (typeof(container_id_or_callback) == "function") {
		var objArgs = {
			url: url,
			method: (method ? method : "get"),
			content: (content ? content : null),
			onLoadObject: container_id_or_callback
		}
	} else {
		 var objArgs = {
			 url: url,
			 method: (method ? method : "get"),
			 content: (content ? content : null),
			 target: container_id_or_callback
		 }
	}
	TransAjax.exec(objArgs);
}
// Submit form via AJAX (FILES supported!);
function ajax_submit(form_id_or_name_or_obj, container_id_or_callback) {
	var form = null;
	if (typeof(form_id_or_name_or_obj) == "string") {
		form = document.forms[form_id_or_name_or_obj]; // by name
		if (!form) {
			form = document.getElementById(form_id_or_name_or_obj); // by id
		}
	} else {
		form = form_id_or_name_or_obj;
	}
	if (!form) { alert("Error! Form "+form_id_or_name_or_obj+" not found!"); return; }
	// fix form
	if (form.action == "") { 
		var s = document.location.href;
		var hash_pos = s.indexOf("#");
		if (hash_pos > 0) s = s.substring(0, hash_pos);
		form.action = s;
	}
	if (form.method == "") {
		form.method = "get";
	}
	var mode = "xhr"; // xhr (xmlhttprequest) / iframe
	// check files
	for (var i = 0; i < form.elements.length; i++) {
		if (form.elements[i].type &&
			form.elements[i].type == "file") //&& form.elements[i].value != '')
		{ mode = "iframe"; form.method = "post"; form.enctype = "multipart/form-data"; break; }
	}
	if (mode == "xhr") {
		var url = form.action;
		var content = TransAjax.getQueryString(form);
		if (form.method == "get") {
			url += (form.action.indexOf("?") == -1 ? "?" : "") + content;
			ajax(url, container_id_or_callback, "get");
		} else {			
			ajax(url, container_id_or_callback, "post", content);
		}
		return;
	}
	if (form.id == "") form.id = "frm_"+Math.round(Math.random()*1000);
	var iid = form.id+"_IFrame";
	if (typeof(form.iframed) == "undefined") {
		var e = TransAjax.isIE ? '<iframe id="'+iid+'" name="'+iid+'"  onload="parent.ajax_iframe_callback(this.id);">' : 'IFRAME';
		var iframe = document.createElement(e);
		iframe.id = iid;
		iframe.name = iid;
		iframe = document.body.appendChild(iframe);
		if (!TransAjax.isIE) iframe.onload = function() { ajax_iframe_callback(iid); };		
		iframe.style.width = "1px";	
		iframe.style.height = "1px";
		iframe.style.overflow = "hidden";
		iframe.style.border = "none";
		iframe._trans_callback = container_id_or_callback;
		form.iframed = true;
	}
	form.target = iid;
	form.submit();
	TransAjax.showBusyImage();
}
function ajax_iframe_callback(iid) {
  TransAjax.hideBusyImage();
  var iframe = document.getElementById(iid);
  
  var txt = iframe.contentWindow.document.body.innerHTML;
  //var txt = (iframe.contentDocument || iframe.contentWindow.document).documentElement.body.innerHTML;
  //alert(txt);
/*  window.console.log(txt);
  window.console.log('-------------------------------------------------------');
  txt = txt.replace(/"\\&quot;/g,'\\"');
  txt = txt.replace(/\\&quot;"/g,'\\"');
  window.console.log(txt);
  */
  if (!iframe._counter) iframe._counter = 0;
  iframe._counter++;
  if (typeof(iframe._trans_callback) == "function") {
	if (txt == '') return;
    if (TransAjax.detectResponseType(txt) != TransAjax.IS_UNKNOWN)
	    iframe._trans_callback(TransAjax.result);
	else
		iframe._trans_callback({responseText: txt}); // capability
  } else {
    TransAjax.setInnerHtml(iframe._trans_callback,txt);
  }
}
// TransAjax namespace
var TransAjax = [];
// global event handlers
TransAjax.onStart = null;
TransAjax.onSend = null;
TransAjax.onComplete = null;
TransAjax.onStop = null;
TransAjax.onError = null;
// detect type
TransAjax.IS_JSON = 1;
TransAjax.IS_PHP = 2;
TransAjax.IS_XML = 3;
TransAjax.IS_UNKNOWN = 0;
// detect result
TransAjax.result = null;
// cache
TransAjax.cache = false;
// busy image 
TransAjax.busyImageFile = "loading.gif";
TransAjax.busyImageVisible = false;
TransAjax.busyContainer = false;
// insert <div id="busyContainer" style="display: none"></div> to page
// or use TransAjax.setBusyContainer("mydiv");
TransAjax.busyContainerId = "busyContainer";
// internal
TransAjax.counter = 0;
TransAjax.isIE = /MSIE/.test(navigator.userAgent);
/* Full featured method "exec"
 * Arguments simple format:
 *    TransAjax.exec(url, container_id);
 *
 * Arguments object format:
 * {
 *   url: [string] relative or absolute URL to fetch,
 *   target: [string or object, optional] DOM element to loaded content
 *   method: [string, optional] method ('GET', 'POST', 'HEAD', 'PUT'),
 *   async: [boolean, optional] use asynchronous mode (default: true),
 *   contentType: [string, optional] content type when using POST,
 *   content: [string or object, optional] postable string or DOM object data
 *    when using POST,
 *   onLoad: [function, optional] function reference to call on success, returns obj.responseText,
 *   onLoadNotify: [function, optional] function reference to call on success load/replace content,
 *   onError: [function, optional] function reference to call on error,
 *   username: [string, optional] username,
 *   password: [string, optional] password,
 * }
*/
TransAjax.exec = function(objArgs) {
  if (arguments.length == 2) { // Simple call format
    objArgs = {url: arguments[0], target: arguments[1]};
  }
  // Check arguments
  if (objArgs == null || typeof objArgs != 'object') {  return null;  }
  if (!objArgs.url) {  return null;  }
  if (!TransAjax.cache) {
	  var rnd = 'rnd='+Math.random();
	  if (objArgs.url.search(/\?/) != -1) objArgs.url += '&'+rnd;
	  else objArgs.url += '?'+rnd;
  }
  if (typeof(objArgs.target) == "string") {
    objArgs.target = document.getElementById(objArgs.target);
    if (objArgs.target == null) return alert('Container "'+objArgs.target+'" not found!');
  }
  if (!objArgs.target) { objArgs.target = null;  } else {
    
  }
  if (!objArgs.method) {  objArgs.method = 'GET';  }
  if (typeof objArgs.async == 'undefined') {  objArgs.async = true;  }
  if (!objArgs.contentType && objArgs.method.toUpperCase() == 'POST') {
    objArgs.contentType = 'application/x-www-form-urlencoded';
  }
  if (!objArgs.content) { objArgs.content = null;  }
  if (!objArgs.onLoad) {  objArgs.onLoad = null;  }
  if (!objArgs.onLoadNotify) {  objArgs.onLoadNotify = null;  }
  if (!objArgs.onError) {  objArgs.onError = null;  }
  // Request URL
  //TransAjax.objRequest = (TransAjax.objRequest) ? TransAjax.objRequest : TransAjax.createRequestObject();
  var objRequest = TransAjax.createRequestObject();
  if (objRequest == null) { return null; }
  if (objArgs.target != null && (objArgs.target.tagName == "INPUT" || objArgs.target.tagName == "SELECT")) {
     objArgs.target.disabled="disabled";
  }
  // IE 6 calls onreadystatechange and then raises an exception if local file is
  // not found. This flag is used to prevent duplicate onError calls.
  var boolErrorDisplayed = false;
  try {
    // Open request
	// fire onStart event
	if (TransAjax.onStart && TransAjax.counter == 0) TransAjax.onStart(objArgs);
	
    if (typeof objArgs.username != 'undefined' &&
     typeof objArgs.password != 'undefined') {
      objRequest.open(objArgs.method, objArgs.url, objArgs.async,
       objArgs.username, objArgs.password);
    } else {
      objRequest.open(objArgs.method, objArgs.url, objArgs.async);
    }
	TransAjax.showBusyImage();
    // Onready handler
    var funcOnReady = function () {
	  TransAjax.counter--;
	  // fire onComplete && onStop event
      if (TransAjax.onComplete) TransAjax.onComplete(objRequest, objArgs);
	  if (TransAjax.onStop && TransAjax.counter == 0) TransAjax.onStop(objRequest, objArgs);
	  // hide busy
	  TransAjax.hideBusyImage();
      // Process response
      if (TransAjax.checkRequestStatus(objRequest)) {
        // OK or found, but determined unchanged and loaded from cache
        if (objArgs.onLoad) {
          objArgs.onLoad(objRequest.responseText);
		} else if (objArgs.onLoadObject) {
		  switch (TransAjax.detectResponseType(objRequest.responseText)) {
		    case TransAjax.IS_JSON:
			case TransAjax.IS_PHP:
			case TransAjax.IS_XML: objArgs.onLoadObject(TransAjax.result); break;
			case TransAjax.IS_UNKNOWN:
				//force eval scripts
//				if (objRequest.responseText.length > 0)
//					TransAjax.evalScripts(objRequest.responseText);
				objArgs.onLoadObject(objRequest);
				break;
		  }
        } else if (objArgs.target) {
          TransAjax.setInnerHtml(objArgs.target,objRequest.responseText);
		  if (objArgs.onLoadNotify) {
            objArgs.onLoadNotify();
		  }
        }	
      } else if (!boolErrorDisplayed) {
        boolErrorDisplayed = true;
        // 404 Not found, etc.
		if (!objArgs.onError) {
			alert('Error: Cannot fetch ' + objArgs.url + '.\n' + (objRequest.statusText || ''));
		} else {																
          objArgs.onError;
		}
      }
    };
    
	// Prevent duplicate funcOnReady call in synchronous mode
    if (objArgs.async) {
      // Set onreadystatechange handler
      objRequest.onreadystatechange = function () {
        if (objRequest.readyState == 4) {
          // Request complete
          funcOnReady();
          // Prevent memory leak 
 	      objRequest.onreadystatechange = {}; 
 	    }
      };
    }
    // Set content type if needed
    if (objArgs.contentType) {
      objRequest.setRequestHeader('Content-Type', objArgs.contentType);
    }
	// fire onSend event
	if (TransAjax.onSend) TransAjax.onSend(objArgs);

	TransAjax.counter++;
    // Send request
    objRequest.send(objArgs.content);
    // In synchronous mode the result is ready on the next line
    if (!objArgs.async) {
      funcOnReady();
    }
    return objRequest;
  } catch (objException) {
    // Process error
    if (!boolErrorDisplayed) {
      boolErrorDisplayed = true;
      if (objException.name && objException.name == 'NS_ERROR_FILE_NOT_FOUND') {
        alert('Error: Cannot load ' + objArgs.url + '.\nFile not found.');
        if (objArgs.onError) objArgs.onError;
	  } else {
        alert('Error: Cannot load ' + objArgs.url + '.\n' + (objException.message || ''));
        if (objArgs.onError) objArgs.onError;
      }
    }
  };
  return false;
}
// returns bool
TransAjax.busy = function() {
	return TransAjax.counter > 0;
}
TransAjax.detectResponseType = function(txt) {
  TransAjax.result = null;
  if (txt.substr(0,1) == "{" || txt.substr(0,1) == '[') {
    // is JSON
    eval("TransAjax.result = "+txt+";");
	return TransAjax.IS_JSON;
  } else if (txt.substr(0,2).search(/[aOsidbNorCRU]+:/) != -1) {
    //alert(objRequest.responseText);
    // is serialized PHP object
	TransAjax.result = TransAjax.phpUnserialize(txt);
    return TransAjax.IS_PHP;
  } else if (txt.substr(0,5) == "<?xml") {
    // is XML
    if (window.DOMParser) {
      // Mozilla
      try {
        TransAjax.result = (new DOMParser()).parseFromString(txt,'text/xml');
        return TransAjax.IS_XML;
      } catch (objException) {
        alert('Error: Cannot parse.\nString does not appear to be a valid XML fragment.:\n'+txt);
      };
	}
	if (typeof ActiveXObject != 'undefined') {
      // IE
      try {
        TransAjax.result = new ActiveXObject(TransAjax.XMLActiveX);
        TransAjax.result.loadXML(objArgs.strXml);
        return TransAjax.IS_XML;
      } catch (objException) {
        alert('Error: Cannot parse '+objException);
      };
    }
  }
  return TransAjax.IS_UNKNOWN;
}
TransAjax.setInnerHtml = function(target, html) {
  if (typeof(target) == "string") target = document.getElementById(target);
  if (target == null) return;
  if (target.tagName == "INPUT" || target.tagName == "SELECT") {
	 target.disabled="";
  }
  if (target.tagName == "INPUT") {
	var t = target.type;
	if (t == "text" ||  t == "password" || t == "hidden" || t == "submit" || t == "button") {
	  target.value = html;
	}
  } else if (target.tagName == "SELECT") { // && navigator.userAgent.indexOf("MSIE")
	target.options.length = 0;
	var items = html.split("</option>");
	for (var i=0; i<items.length; i++) {
	  items[i] += '</option>';
	  var r = items[i].match(/<option value="([^"]*)"(.*?)>([^<]*)<\/option>/i);
	  if (r) {
		  var val = r[1];
		  var sel = r[2].search(/selected/) != -1;
		  var txt = r[3];
		  var nOption = document.createElement('OPTION'); 
		  var nText = document.createTextNode(txt);
		  nOption.setAttribute('value',val); 
		  if (sel) nOption.setAttribute('selected','selected');
		  nOption.appendChild(nText); 
		  target.appendChild(nOption);
	  }
	}
  } else {
	target.innerHTML = html;
	TransAjax.evalScripts(html);
  }
}
TransAjax.setInnerHTML = TransAjax.setInnerHtml; // alias
// busy image processing
TransAjax.setBusyContainer = function(obj) {
	if (typeof(obj) == "string") obj = document.getElementById(obj);
	if (obj) {
		TransAjax.busyContainer = obj;
		var busyImg = document.createElement("IMG");
		busyImg.src = TransAjax.busyImageFile;
		busyImg.style.cssFloat = "right";
		busyImg.style.border = "none";
		busyImg.id = "bim_"+Math.floor(Math.random()*900)+100;
	    if (TransAjax.busyContainer.firstChild) {
			TransAjax.busyContainer.insertBefore(busyImg, TransAjax.busyContainer.firstChild);
	    } else {
			TransAjax.busyContainer.appendChild(busyImg);
		}
	} else {
		TransAjax.busyContainer = false;
	}
}
TransAjax.showBusyImage = function() {
	if (TransAjax.busyImageVisible) return;
	if (!TransAjax.busyContainer) return;
	TransAjax.busyContainer.style.display = "";
	TransAjax.busyImageVisible = true;
}
TransAjax.hideBusyImage = function() {
	if (!TransAjax.busyImageVisible) return;
	if (!TransAjax.busyContainer) return;
	TransAjax.busyContainer.style.display = "none";
	TransAjax.busyImageVisible = false;
}
/*--------------- Mini XMLHttpRequest wrapper ---------------*/
TransAjax.createRequestObject = function() {
  try {
    if (typeof XMLHttpRequest != 'undefined') {
      obj = new XMLHttpRequest();
    } else if (typeof ActiveXObject != 'undefined') {
      obj = new ActiveXObject(TransAjax.HTTPActiveX);
    }
	if (!obj) {
      alert("Please install new version of browser!");
      return false;
	}
	return obj;
  } catch(e) {
	alert(e);
  }
  return false;
}
TransAjax.getNameOfActiveX = function() {
  if (typeof ActiveXObject == 'undefined') return '';
  var v = ['Microsoft.XMLHTTP','Msxml2.XMLHTTP.4.0','MSXML2.XMLHTTP.3.0','MSXML2.XMLHTTP'];
  for (var i = 0; i < v.length; i++) {
    try {
      var objDocument = new ActiveXObject(v[i]);
      // If it gets to this point, the string worked
      return v[i];
    } catch (objException) {};
  }
  return null;
}
TransAjax.getXMLNameOfActiveX = function() {
  if (typeof ActiveXObject == 'undefined') return '';
  var v = ['Msxml2.DOMDocument.4.0','Msxml2.DOMDocument.3.0','MSXML2.DOMDocument','MSXML.DOMDocument','Microsoft.XMLDOM'];
  for (var i = 0; i < v.length; i++) {
    try {
      var objDocument = new ActiveXObject(v[i]);
      // If it gets to this point, the string worked
      return v[i];
    } catch (e) {};
  }
  return null;
}
TransAjax.checkRequestStatus = function(obj) {
  try {
    if (obj.readyState == 4) {
      if (obj.status == 200 || obj.status == 304 || (location.protocol == 'file:' && !obj.status)) {
        return true;
      }
	}
  } catch(e) {
    return false
  };
  return false;
}
//----------------- Search/Eval scripts
TransAjax.evalScripts = function(s) {
//	alert(s);
	if (s.search(/<\s*script[^>]*>/) != -1) {
		var $head = document.getElementsByTagName("head")[0] || document.documentElement;
		var r = s.split(/<\s*\/\s*script\s*>/i);
		var r2 = []; var r3 = []; var strScript = '';
		for (var i=0; i < r.length; i++) {
			// process "src"
			r2 = r[i].match(/<\s*script[^>]+src="([^"]+)"[^>]*>/i)
			if (r2) {
				var objScript = document.createElement("SCRIPT");
				objScript.src = r2[1];
				$head.appendChild(objScript);
				continue;
			}
			r3 = r[i].split(/<\s*script[^>]*>/i);
			if (r3.length == 2) {
				strScript = r3[1].replace(/function\s+([^(]+)/g, '$1=function');
				strScript = strScript.replace(/^(\s*<!--|\s*<!\[CDATA\[)/,"");
				strScript = strScript.replace(/(-->\s*|\]\]>\s*)$/g,"");
				
				if ( TransAjax.isIE ) {
					var $script = document.createElement("script");
					$script.type = "text/javascript";
					$script.text = strScript;
					$script = $head.appendChild( $script );
					$head.removeChild( $script );
				} else {
					window.eval( strScript );
					//$script.appendChild( document.createTextNode( strScript ) );
				}
				//$head.appendChild( $script );
				//$head.removeChild( $script );
			}
		}
	}
}
// Form submission utils
TransAjax.serializeForm = function(form_id_or_obj) { // alias for TransAjax.getQueryString
	if (typeof(form_id_or_obj) == "string") form_id_or_obj = document.getElementById(form_id_or_obj);
	return (form_id_or_obj) ? TransAjax.getQueryString(form_id_or_obj) : false;
}
TransAjax.getQueryString = function(form_or_obj) {
	if (typeof(form_or_obj) == "string") return form_or_obj;
    var str = "";
	if (form_or_obj.tagName && form_or_obj.tagName == "FORM") {
		var element, i = 0;
		while ((element = form_or_obj.elements[i++]) != null) {
			var qc = TransAjax.getQueryComponent(element);
			if (qc != "") str += "&" + qc;
		}
	} else {
		for (i in form_or_obj) {
			str += "&" + urlencode(i) + "=" + urlencode(form_or_obj[i]);
		}
	}
    return str.substring(1);
}
TransAjax.getQueryComponent = function(input) {
    if (!input.name || input.disabled)
        return "";

    var n = urlencode(input.name);

    switch (input.type) {
    case "text":
    case "password":
    case "submit":
    case "hidden":
        return n + "=" + urlencode(input.value);
    case "textarea":
        // normalize line breaks as CR LF pairs as per RFC 1866 
		var v = input.value.replace(/(\r\n)|\r|\n/g, "\r\n");
        return n + "=" + urlencode(v);
    case "checkbox":
    case "radio":
        if (!input.checked)
            return "";
        var v = TransAjax._getElementValue(input);
        if (v === null) v = "on";
        return n + "=" + urlencode(v);
    case "select-one":
    case "select-multiple":
        var nvp = [];
        var opt, i = 0;
        while ((opt = input.options[i++]) != null) {
            if (opt.selected) {
                var v = TransAjax._getElementValue(opt);
                if (v === null) v = opt.text;
                // older versions of IE do not support Array.push 
                nvp[nvp.length] = n + "=" + urlencode(v);
            }
        }
        return nvp.join("&");
    default:
        // input types reset, button, image, and file not implemented 
        return "";
    }
}
urlencode = function(str) {
    var v;
    try { v = encodeURIComponent(str); } catch (e) { v = escape(str); }
    return v.replace(/%20/g,"+");
}
TransAjax._getElementValue = function(input) {
    var attr = input.getAttributeNode("value");
    return (attr) ? input.getAttribute("value") : null;
}
/*---------------- PHP serialization style support ----------------*/
TransAjax.phpSerialize = function(val) {
  switch (typeof(val)) {
    case "number": return (Math.floor(val) == val ? "i" : "d") + ":" + val + ";";
    case "string": return "s:" + val.length + ":\"" + val + "\";";
    case "boolean": return "b:" + (val ? "1" : "0") + ";";
    case "object":
      if (val == null) {
        return "N;";
      } else if ("length" in val) { // collection
        var idxobj = {idx: -1 };
		return "a:" + val.length + ":{" + val.map(
          function (item) {
            this.idx++;
            var ser = TransAjax.phpSerialize(item);
            return ser ? TransAjax.phpSerialize(this.idx) + ser : false;
          }, idxobj).filter(
            function (item) {
              return item;
            }
          ).join("") + "}";
      } else {
        var class_name = TransAjax._getObjectClass(val);
        if (class_name == "undefined") {
          return false;
        }
        var props = new Array();
        for (var prop in val) {
          var ser = TransAjax.phpSerialize(val[prop]);
            if (ser) { props.push(TransAjax.phpSerialize(prop) + ser); }
        }
        return "O:" + class_name.length + ":\"" + class_name + "\":" + props.length + ":{" + props.join("") + "}";
      }
    case "undefined":
      return "N;";
    }
	return false;
}
TransAjax.phpUnserialize = function(input) {
  var result = TransAjax._phpUnserialize(input);
  return result[0];
}
/**
* Function which performs the actual unserializing
*
* @param string input Input to parse
*/
TransAjax._phpUnserialize = function(input) {
  var length = 0;
  switch (input.charAt(0)) {
    case 'a': // Array
      length = TransAjax._phpUnserialize_GetLength(input);
      input  = input.substr(String(length).length + 4);
      var arr   = new Array();
      var key   = null;
      var value = null;
      for (var i=0; i<length; ++i) {
        key = TransAjax._phpUnserialize(input);
        input = key[1];
        value = TransAjax._phpUnserialize(input);
        input = value[1];
		if (typeof(key[0]) == "number") {
          arr[key[0]] = value[0];
	    } else {
          eval("arr."+key[0]+" = '"+value[0]+"';");
		}
      }
      input = input.substr(1);
      return [arr, input];
    case 'O': // Objects
      length = TransAjax._phpUnserialize_GetLength(input);
      var classname = String(input.substr(String(length).length + 4, length));
      input  = input.substr(String(length).length + 6 + length);
      var numProperties = Number(input.substring(0, input.indexOf(':')))
      input = input.substr(String(numProperties).length + 2);
      var obj = new Object();
      var property = null;
      var value    = null;
      for (var i=0; i<numProperties; ++i) {
        key = TransAjax._phpUnserialize(input);
        input = key[1];		
        // Handle private/protected
        key[0] = key[0].replace(new RegExp('^\x00' + classname + '\x00'), '');
        key[0] = key[0].replace(new RegExp('^\x00\\*\x00'), '');
        value = TransAjax._phpUnserialize(input);
        input = value[1];
        obj[key[0]] = value[0];
      }
      input = input.substr(1);
      return [obj, input];
    case 's': //Strings
      length = TransAjax._phpUnserialize_GetLength(input);
      return [String(input.substr(String(length).length + 4, length)), input.substr(String(length).length + 6 + length)];
	case 'i': //Integers and doubles
    case 'd':
      var num = Number(input.substring(2, input.indexOf(';')));
      return [num, input.substr(String(num).length + 3)];
    case 'b': //Booleans
      var bool = (input.substr(2, 1) == 1);
      return [bool, input.substr(4)];
    case 'N': //Null
      return [null, input.substr(2)];
    // Unsupported
    case 'o':
    case 'r':
    case 'C':
    case 'R':
    case 'U':
      alert('Error: Unsupported PHP data type found!');
    default:
      return [null, null];
  }
}
TransAjax._phpUnserialize_GetLength = function(input) {
  input = input.substring(2);
  var length = Number(input.substr(0, input.indexOf(':')));
  return length;
}
TransAjax._getObjectClass = function(obj) {
 if (obj && obj.constructor && obj.constructor.toString) {
   var arr = obj.constructor.toString().match(/function\s*(\w+)/);
   if (arr && arr.length == 2) {
     return arr[1];
   }
 }
 return undefined;
}
//----------------- Get current path
TransAjax.getPath = function() {
  // Get last script element
  var objContainer = document.body;
  if (!objContainer) {
    objContainer = document.getElementsByTagName("head")[0];
    if (!objContainer)  objContainer = document;
  }
  var objScript = objContainer.lastChild;
  // Get path
  var path = "";
  var strSrc = objScript.getAttribute("src");
  if (strSrc) {
    var arrTokens = strSrc.split("/");
    // Remove last token
    arrTokens = arrTokens.slice(0, -1);
    if (arrTokens.length)
	  path = arrTokens.join("/") + "/";
  }
  TransAjax.path = path;
  TransAjax.busyImageFile = (TransAjax.busyImageFile.indexOf("http") == 0 ||  TransAjax.busyImageFile.indexOf("/") == 0) ? TransAjax.busyImageFile : TransAjax.path + TransAjax.busyImageFile;
  TransAjax._bim = new Image(); // preload busy image
  TransAjax._bim.src = TransAjax.busyImageFile;
}
// find Busy Image Container
TransAjax.findBusyContainer = function() {
	TransAjax.setBusyContainer(TransAjax.busyContainerId);
}
// -------------------- INITIALIZATION
TransAjax.getPath();
TransAjax.HTTPActiveX = TransAjax.getNameOfActiveX();
TransAjax.XMLActiveX = TransAjax.getXMLNameOfActiveX();
if (window.addEventListener) 
	window.addEventListener("load", TransAjax.findBusyContainer, false);
else if (window.attachEvent)
	window.attachEvent("onload", TransAjax.findBusyContainer);
else window.onload = TransAjax.findBusyContainer;