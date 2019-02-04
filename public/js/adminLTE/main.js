var loadFile = function(event) {
	var elem = document.getElementById("image_elem");
	if (elem != null) {
		elem.parentNode.removeChild(elem);
	}
	
	$("#preview").contents().filter(function () {
	    return this.nodeType === 3; // Text nodes only
	}).remove();
	
	var elem_img = document.createElement("img");
	elem_img.setAttribute("id", "image_elem");
	elem_img.setAttribute("src", URL.createObjectURL(event.target.files[0]));
	document.getElementById("preview").appendChild(elem_img);
};