
// ------------ This code comes from a website ------------------- //
//   http://javascript.internet.com/page-details/smart-popup.html  //

var version4 = (navigator.appVersion.charAt(0) == "4"); 
var popupHandle;

function closePopup() {
    if(popupHandle != null && !popupHandle.closed) popupHandle.close();
}

function displayHelp(url,width,height,evnt) {
   var properties = "toolbar=0, location=0, height=" + height;
   properties = properties + ", width=" + width;
   var leftprop, topprop, screenX, screenY, cursorX, cursorY, padAmt;
   if(navigator.appName == "Microsoft Internet Explorer") {
      screenY = document.body.offsetHeight;
      screenX = window.screen.availWidth;
   }
   else {
      screenY = window.outerHeight
      screenX = window.outerWidth
   }
   cursorX = evnt.screenX;
   cursorY = evnt.screenY; 
   padAmtX = 10;
   padAmtY = 10;
   
   if((cursorY + height + padAmtY) > screenY) {
      // make sizes a negative number to move left/up
      padAmtY = (-30) + (height * -1);
      // if up or to left, make 30 as padding amount
   }
   if((cursorX + width + padAmtX) > screenX)	{
      padAmtX = (-30) + (width * -1);	
      // if up or to left, make 30 as padding amount
   }
   if(navigator.appName == "Microsoft Internet Explorer") {
      leftprop = cursorX + padAmtX;
      topprop = cursorY + padAmtY;
   }
   else {
      leftprop = cursorX  + padAmtX;
      topprop = cursorY + padAmtY;
   }
   if(evnt != null) {
      properties = properties + ", left=" + leftprop;
      properties = properties + ", top=" + topprop;
   }
   closePopup();
   popupHandle = window.open(url,'Help',properties);
}

function displayActionConfirmationDialog(url,width,height,evnt) {
   var properties = "toolbar=0, location=0, height=" + height;
   properties = properties + ", width=" + width;
   var leftprop, topprop, screenX, screenY, cursorX, cursorY, padAmt;
   if(navigator.appName == "Microsoft Internet Explorer") {
      screenY = document.body.offsetHeight;
      screenX = window.screen.availWidth;
   }
   else {
      screenY = window.outerHeight
      screenX = window.outerWidth
   }
   cursorX = evnt.screenX;
   cursorY = evnt.screenY; 
   padAmtX = 10;
   padAmtY = 10;
   if((cursorY + height + padAmtY) > screenY) {
      // make sizes a negative number to move left/up
      padAmtY = (-30) + (height * -1);
      // if up or to left, make 30 as padding amount
   }
   if((cursorX + width + padAmtX) > screenX)	{
      padAmtX = (-30) + (width * -1);	
      // if up or to left, make 30 as padding amount
   }
   if(navigator.appName == "Microsoft Internet Explorer") {
      leftprop = cursorX + padAmtX;
      topprop = cursorY + padAmtY;
   }
   else {
      leftprop = cursorX  + padAmtX;
      topprop = cursorY + padAmtY;
   }
   if(evnt != null) {
      properties = properties + ", left=" + leftprop;
      properties = properties + ", top=" + topprop;
   }
   closePopup();
   popupHandle = window.open(url,'Confirm',properties);
}

function changeMenuStyleMouseOver(obj) {
    obj.className="menuMouseOver";
}

function changeMenuStyleMouseOut(obj) {
    obj.className="menuMouseOut";
}

function openBareScrollingWindow(id, url, width, height) {
   window.open(url,id, "status=no,resizable=yes,scrollbars=yes,toolbar=no,WIDTH="+width+",HEIGHT="+height)
}
//used in conjunction with displayActionConfirmationDialog, the 'Yes'
//UI should fire this passing in the id of the form
//[JS] 2005 02 16
function submitThis(idOfForm, queryString){
	parentWindow = window.opener;
  parentWindow.document.getElementById(idOfForm).action = queryString;
	parentWindow.document.getElementById(idOfForm).submit();
	parent.close();
}
