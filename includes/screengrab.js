// Load up iframe stuff
function EA_thumbnail(frame, image) {
	EA_loadImage(
		frame.document.getElementsByTagName("body")[0],
		document.getElementById(image)
		);
	}

// Load an image off the element, place it in location
function EA_loadImage(element, location){
    html2canvas(element, {
    	"logging"	: true,
    	"proxy"		: "extensions/EmbedAnything/includes/html2canvasproxy.php",
        "onrendered": function( canvas ) {
        	var img = new Image();
            img.onload = function() {
            	img.onload = null;
                location.appendChild(img);
                };
            img.onerror = function() {
            	img.onerror = null;
                if(window.console.log) {
                	window.console.log("Not loaded image from canvas.toDataURL");
                } else {
                	alert("Not loaded image from canvas.toDataURL");
                }
            };
            img.src = canvas.toDataURL("image/png");
            img.width = 300;
            //EA_cacheImage(encodeURIComponent(img));
			},
        "height": 800,
     //   "allowTaint" : true
        });
}
function EA_cacheImage(data){
	alert("Image sending");
}