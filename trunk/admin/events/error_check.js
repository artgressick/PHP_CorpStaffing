document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;

	if(errEmpty('chrEvent', "You must enter a Event Name.")) { totalErrors++; }
	if(errEmpty('idTimeZone', "You must select a Time Zone.")) { totalErrors++; }
	if(errEmpty('dBegin', "You must enter a Begin Date.")) { totalErrors++; }
	if(errEmpty('dEnd', "You must enter a End Date.")) { totalErrors++; }
	return (totalErrors == 0 ? true : false);
}

function txtMsgCounter(obj) { 
	var len = obj.value.length;
	if(len <= 250) { 
		document.getElementById("txtMsgCount").innerHTML = len; 
	} else {
		document.getElementById("txtMessage").value = document.getElementById("txtMessage").value.substr(0,250);
	}
}