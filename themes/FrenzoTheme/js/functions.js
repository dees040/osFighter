function getX(pElement,pAddElement){
	var isIE = (navigator.appName.toLowerCase() == "microsoft internet explorer")
	var isNN = (navigator.appName.toLowerCase() == "netscape")
	
	var objItem = pElement
	var objParent = null
	var intX = 0
	do
	 { // Walk up our document tree until we find the body
	  // and add the distance from the parent to our counter.
	  intX += objItem.offsetLeft
	  objParent = objItem.offsetParent.tagName
	  objItem = objItem.offsetParent
	 }
	while(objParent != 'BODY')
	
	
	var myScrollX
	(isIE) ? myScrollX = document.body.scrollLeft: myScrollX = window.pageXOffset;
	
	intX += (pAddElement * pElement.offsetWidth)
	
	var intImgLeft = intX - myScrollX
	return intImgLeft
	
}

function getY(pElement,pAddElement){
	var isIE = (navigator.appName.toLowerCase() == "microsoft internet explorer")
	var isNN = (navigator.appName.toLowerCase() == "netscape")
	
	var objItem = pElement
	var objParent = null
	var intY = 0
	do
	 { // Walk up our document tree until we find the body
	  // and add the distance from the parent to our counter.
	  intY += objItem.offsetTop
	  objParent = objItem.offsetParent.tagName
	  objItem = objItem.offsetParent
	 }
	while(objParent != 'BODY')
	
	
	var myScrollY
	(isIE) ? myScrollY = document.body.scrollTop: myScrollY = window.pageYOffset;
	
	intY += (pAddElement * pElement.offsetHeight)
	
	var intImgTop = intY - myScrollY
	
	return intImgTop
	
}

function countdown(tijd,id,url){
	if(tijd>0){
	    if(tijd>86399){
        	dagen=Math.floor(tijd/3600/24)
        	uren=Math.floor((tijd-dagen*3600*24)/3600)
        	minuten=Math.floor((tijd-dagen*3600*24-uren*3600)/60)
        	seconden=Math.floor(tijd-dagen*3600*24-uren*3600-minuten*60)
 			
        	var zichttijd=dagen+":"+uren+":"+minuten+":"+seconden
    	}else{
			uren=Math.floor(tijd/3600)
			minuten=Math.floor((tijd-uren*3600)/60)
			seconden=Math.floor(tijd-uren*3600-minuten*60)
			if(minuten<10 && seconden <10){
				var zichttijd="0"+uren+":0"+minuten+":0"+seconden
			}else if(minuten<10) {
 				var zichttijd="0"+uren+":0"+minuten+":"+seconden
 			}else if (seconden <10) {
 				var zichttijd="0"+uren+":"+minuten+":0"+seconden
 			}else {
        		var zichttijd="0"+uren+":"+minuten+":"+seconden
 			}
    	}
    	tijd=tijd-1
 		
    	document.getElementById(id).innerHTML=zichttijd;
		countdownTimer=setTimeout("countdown('"+tijd+"','"+id+"','"+url+"')",1000);
	}else{
  		document.getElementById(id).innerHTML="00:00"
  		if(url != '') document.location.href = url
	}
}