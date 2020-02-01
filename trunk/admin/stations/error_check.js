document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;

		if(errEmpty('chrNumber', "You must enter a Station Number.")) { totalErrors++; }
		if(errEmpty('chrStation', "You must enter a Station Name.")) { totalErrors++; }
		if(errEmpty('idStationType', "You must select a Station Type.")) { totalErrors++; }
		if(errEmpty('idLocation', "You must select a Location.")) { totalErrors++; }
		
	return (totalErrors == 0 ? true : false);
}