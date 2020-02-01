document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;

		if(errEmpty('chrLocation', "You must enter a Location Name.")) { totalErrors++; }

		if(document.getElementById('bStaffingEnabled0').checked == false && document.getElementById('bStaffingEnabled1').checked == false) {
			errCustom('bStaffingEnabled0','You must select to Enable Staffing or not');
		}
		
	return (totalErrors == 0 ? true : false);
}