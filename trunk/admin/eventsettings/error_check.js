document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;

	if(errEmpty('idStaffingStatus', "You must select a Staffing Status.")) { totalErrors++; }
	if(errEmpty('idZoneManagerStatus', "You must select a Zone Manager Status Status.")) { totalErrors++; }
	return (totalErrors == 0 ? true : false);
}