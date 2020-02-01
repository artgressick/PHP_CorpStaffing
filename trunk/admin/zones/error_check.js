document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;

		if(errEmpty('idLocation', "You must select a Location.")) { totalErrors++; }
		if(errEmyty('chrZone', "You must enter a Zone Name.")) { totalErrors++; }
		
		
	return (totalErrors == 0 ? true : false);
}