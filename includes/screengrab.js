/*  Ajax Library! */
var ajax={send:function(a){if("object"==typeof a){a.async=a.async||!0,a.dataType=a.dataType||"html",a.data=a.data||!1,a.type=a.type||"GET";var b=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");b.onreadystatechange=function(){"function"==typeof a.beforeSend&&a.beforeSend(),4===b.readyState&&200===b.status?"function"==typeof a.success&&("html"===a.dataType&&a.success(b.responseText),"xml"===a.dataType&&a.success(b.responseXML),"json"===a.dataType&&a.success(ajax.parseJSON(b.responseText))):"function"==typeof a.error&&a.error()},(type=~["GET","DELETE","POST","PUT","HEAD"].indexOf(a.type))&&("GET"===a.type?(b.open("GET",a.url+"?"+ajax.toQueryString(a.data),!0),b.setRequestHeader("X-Requested-With","XMLHttpRequest"),b.send(null)):(b.open(a.type,a.url,!0),b.setRequestHeader("Content-type","application/x-www-form-urlencoded"),b.setRequestHeader("X-Requested-With","XMLHttpRequest"),b.send(ajax.toQueryString(a.data))))}},parseJSON:function(a){if("string"!=typeof a||!a||""===a)return null;if(a=a.replace(/^\s+/,""),a=a.replace(/\s+$/,""),window.JSON&&window.JSON.parse)return window.JSON.parse(a);var b=/^[\],:{}\s]*$/,c=/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,d=/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,e=/(?:^|:|,)(?:\s*\[)+/g;return b.test(a.replace(c,"@").replace(d,"]").replace(e,""))?new Function("return "+a)():void 0},toQueryString:function(a){var c,e,f,b="",d=function(a,c){b+=encodeURIComponent(a)+"="+encodeURIComponent(c)+"&"};for(e in a)if(Object.hasOwnProperty.call(a,e))if(f=a[e],"object"==typeof a&&a instanceof Array)for(c=0;c<f.length;c++)d(e,f[c]);else d(e,a[e]);return b.replace(/&$/,"").replace(/%20/g,"+")}};
/*      ------------------------         */


// Load up iframe stuff
function EA_LoadThumb(target){
	target.setAttribute('onerror', '');
	var php = target.src; // Images intended target
	target.src = EA_ext_path()+"/includes/loading.gif";
	var wrapper = document.createElement('div');
	wrapper.setAttribute("overflow", "hidden");
	wrapper.setAttribute("position", "relative");
	wrapper = target.parentNode.appendChild(wrapper);
	var frame = document.createElement("iframe");
	frame.setAttribute("style",'position: absolute;top:0px;left:4000px;width:1200px;height:800px;');
	frame.setAttribute("id",'testing');
	frame = wrapper.appendChild(frame);
	frame.src = php+"&html=true"; //images original url
	frame.onload = function (e){
		var iframeDocument = frame.contentDocument || frame.contentWindow.document;
		var iframe_body = iframeDocument.getElementsByTagName('body')[0];
		EA_loadImage(iframe_body, target, php);
	}
}

// Load an image off the element, place it in location
function EA_loadImage(element, img, url){
    html2canvas(element, {
    	"logging"	: true,
    	"proxy"		: EA_ext_path()+"/includes/html2canvasproxy.php",
   // 	"proxy"		: "proxy.php",
        "onrendered": function( canvas ) {
            img.onerror = function() {
            	img.onerror = null;
                if(window.console.log) {
                	window.console.log("Not loaded image from canvas.toDataURL");
                } else {
                	alert("Not loaded image from canvas.toDataURL");
                }
            };
            var img_data = canvas.toDataURL("image/png");
            img.src = img_data;
            EA_cacheImage(img_data, url); // Send completed thumbnail
			},
        "height": 800
     //   "allowTaint" : true
        });
}

// Save our computed image off for caching.
function EA_cacheImage(data, url){
    ajax.send({
        url: url,
        data: { data : encodeURIComponent(data)},
        type: "POST",
        dataType: "html",
        beforeSend: function(){
	      //  alert("About to send datas!");
        },
        success: function(result){
//        	img.src = result;
            console.log(result);
        },
        error: function(){
        //	alert("There was an error sending data.");
        }
    });
}