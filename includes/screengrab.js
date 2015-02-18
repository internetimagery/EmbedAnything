// Load up iframe stuff
function EA_LoadThumb(target){
	target.onerror = null;
	var wrapper = document.createElement('div');
	wrapper.setAttribute("overflow", "hidden");
	wrapper.setAttribute("position", "relative");
	wrapper = target.parentNode.appendChild(wrapper);
	var frame = document.createElement("iframe");
	frame.setAttribute("style",'position: absolute;top:0px;left:4000px;width:1200px;height:800px;');
	frame.setAttribute("id",'testing');
	frame = wrapper.appendChild(frame);
	frame.src = target.src; //images oiginal url
	frame.onload = function (e){
		var iframeDocument = frame.contentDocument || frame.contentWindow.document;
		var iframe_body = iframeDocument.getElementsByTagName('body')[0];
		EA_loadImage(iframe_body, target);
	}
}

// Load an image off the element, place it in location
function EA_loadImage(element, img){
    html2canvas(element, {
    	"logging"	: true,
    	"proxy"		: "html2canvasproxy.php",
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
            img.src = canvas.toDataURL("image/png");
            img.width = 600;
            //EA_cacheImage(encodeURIComponent(img));
			},
        "height": 800
     //   "allowTaint" : true
        });
}
function EA_cacheImage(data){
	alert("Image sending");
}