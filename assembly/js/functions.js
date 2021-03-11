
function viewImage(divID) {
	document.getElementById(divID).style.display = "block";
	//document.getElementById("shade").style.position = "absolute";
	//document.getElementById("shade").style.top = "0";
	//document.getElementById("shade").style.left = "0";
	//document.getElementById("shade").style.backgroundColor = "#000";
	//document.getElementById("shade").style.width = "100%";
	//document.getElementById("shade").style.height = "100%";
	//document.getElementById("shade").style.display = "block";
	//window.scrollTo(0,0);
	
	//position:absolute;
	//top:0;
	//left:0;
	//background-color:#000;
	//width:100%;
	//height:100%;
	//display:none;
}

function closeImage(divID) {
		document.getElementById(divID).style.display = "none";
		document.getElementById("shade").style.display = "none";
}

function showPart(divID) {
	document.getElementById(divID).style.display = "block";
}

function closePart(divID) {
	document.getElementById(divID).style.display = "none";
}