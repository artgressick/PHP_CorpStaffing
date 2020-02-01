document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;

		if(errEmpty('chrTo', "You must select who to send this E-mail to.")) { totalErrors++; }
		if(errEmpty('chrSubject', "You must enter a Subject.")) { totalErrors++; }
		if(errCustom('txtSourceCode', "You must enter the E-mail Message.",'tinyMCE')) { totalErrors++; }
		
	return (totalErrors == 0 ? true : false);
}