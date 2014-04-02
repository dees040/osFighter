var pop_open = false;

function popup_close(){
	document.getElementById('div_popunder_bg').style.display = "none";
	document.getElementById('div_popunder').style.display = "none";
	document.getElementById('div_popup').style.display = "none";
	document.body.style.overflow = '';
	pop_open = false;
}

function popup(){

	x = 50; //50%
	y = 20; //20%
	
	if (parseInt(navigator.appVersion)>3) {
 		if (navigator.appName=="Netscape") {
  			winW = window.innerWidth;
  			winH = window.innerHeight;
 		}
 		if (navigator.appName.indexOf("Microsoft")!=-1) {
  			winW = document.body.offsetWidth;
  			winH = document.body.offsetHeight;
 		}
	}
	
	//Centreren
	document.getElementById('div_popup').style.left = (winW - 400)/ (100/x)+ 'px';
	document.getElementById('div_popup').style.top = winH/ (100/y)+ 'px';
	
	document.getElementById('div_popunder_bg').style.display = "inline";
	
	document.body.style.overflow = 'hidden';
	pop_open = true;
}

function popup_error_close(){
	document.getElementById('div_popunder_bg').style.display = "none";
	document.getElementById('div_popunder').style.display = "none";
	document.getElementById('div_popup_error').style.display = "none";
	document.body.style.overflow = '';
	pop_open = false;
}

function popup_error(){

	x = 50; //50%
	y = 20; //20%
	
	if (parseInt(navigator.appVersion)>3) {
 		if (navigator.appName=="Netscape") {
  			winW = window.innerWidth;
  			winH = window.innerHeight;
 		}
 		if (navigator.appName.indexOf("Microsoft")!=-1) {
  			winW = document.body.offsetWidth;
  			winH = document.body.offsetHeight;
 		}
	}
	
	//Centreren
	document.getElementById('div_popup_error').style.left = (winW - 400)/ (100/x)+ 'px';
	document.getElementById('div_popup_error').style.top = winH/ (100/y)+ 'px';
	
	document.getElementById('div_popunder_bg').style.display = "inline";
	
	document.body.style.overflow = 'hidden';
	pop_open = true;
}

function opacity(id, opacStart, opacEnd, millisec) {
    //speed for each frame
    var speed = Math.round(millisec / 100);
    var timer = 0;

    //determine the direction for the blending, if start and end are the same nothing happens
    if(opacStart > opacEnd) {
        for(i = opacStart; i >= opacEnd; i--) {
            setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    } else if(opacStart < opacEnd) {
        for(i = opacStart; i <= opacEnd; i++)
            {
            setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    }
}

//change the opacity for different browsers
function changeOpac(opacity, id) {
    var object = document.getElementById(id).style;
    object.opacity = (opacity / 100);
    object.MozOpacity = (opacity / 100);
    object.KhtmlOpacity = (opacity / 100);
    object.filter = "alpha(opacity=" + opacity + ")";
} 

function switchElement(pElement){
	if(document.getElementById(pElement).style.display == 'none'){
		document.getElementById(pElement).style.display = 'block';
	}else{
		document.getElementById(pElement).style.display = 'none';
	}
}

function switchElementNoBlock(pElement){
	if(document.getElementById(pElement).style.display == 'none'){
		document.getElementById(pElement).style.display = '';
	}else{
		document.getElementById(pElement).style.display = 'none';
	}
}

var list_quotes = new Array();

function openCloseQuote(pId,pLink){
	if(document.getElementById(pId).style.display == 'none'){
		document.getElementById(pId).style.display = '';
		document.getElementById(pLink).innerHTML = '-';
	}else{
		document.getElementById(pId).style.display = 'none';
		document.getElementById(pLink).innerHTML = '+';
	}
}

function addQuote(pId){
	list_quotes.push(pId);
}

function openAllQuote(pLink){
	var all_open = true;
	
	for(var i = 0; i < list_quotes.length; i++){
		if(document.getElementById(list_quotes[i]).style.display == 'none') all_open = false;
	}
	
	if(all_open){
		for(var i = 0; i < list_quotes.length; i++){
			document.getElementById(list_quotes[i]).style.display = 'none';		
		}
		document.getElementById(pLink).innerHTML = '++';
	}else{
		for(var i = 0; i < list_quotes.length; i++){
			document.getElementById(list_quotes[i]).style.display = '';		
		}
		document.getElementById(pLink).innerHTML = '--';
	}
}

//
// getPageSize()
// Returns array with page width, height and window width, height
// Core code from - quirksmode.org
// Edit for Firefox by pHaez
//
function getPageSize(){
	
	var xScroll, yScroll;
	
	if (window.innerHeight && window.scrollMaxY) {	
		xScroll = document.body.scrollWidth;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
		xScroll = document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
		xScroll = document.body.offsetWidth;
		yScroll = document.body.offsetHeight;
	}
	
	var windowWidth, windowHeight;
	if (self.innerHeight) {	// all except Explorer
		windowWidth = self.innerWidth;
		windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}	
	
	// for small pages with total height less then height of the viewport
	if(yScroll < windowHeight){
		pageHeight = windowHeight;
	} else { 
		pageHeight = yScroll;
	}

	// for small pages with total width less then width of the viewport
	if(xScroll < windowWidth){	
		pageWidth = windowWidth;
	} else {
		pageWidth = xScroll;
	}


	arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight) 
	return arrayPageSize;
}
