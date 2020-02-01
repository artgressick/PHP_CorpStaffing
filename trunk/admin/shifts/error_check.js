document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;

		if(errEmpty('dDate', "You must enter a Date.")) { totalErrors++; }
		if(errEmpty('tBegin', "You must enter a Begin Time.")) { totalErrors++; }
		if(errEmpty('tEnd', "You must enter a End Time.")) { totalErrors++; }
		if(errEmpty('idLocation', "You must select a Location.")) { totalErrors++; }


		
	return (totalErrors == 0 ? true : false);
}