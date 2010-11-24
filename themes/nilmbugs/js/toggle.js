function toggle(id) {
	if (document.getElementById("resource" + id).style.display == "none") {
		document.getElementById("resource" + id).style.display = "block";
	} else {
		document.getElementById("resource" + id).style.display = "none";
	}
	return false;
}