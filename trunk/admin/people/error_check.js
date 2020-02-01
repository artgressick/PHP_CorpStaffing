document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;

	if(errEmpty('chrFirst', "You must enter a First Name.")) { totalErrors++; }
	if(errEmpty('chrLast', "You must enter a Last name.")) { totalErrors++; }
	if(errEmpty('chrEmail',"You must enter a E-mail Address.")) { 
		totalErrors++; 
	} else {
		if(errEmail('chrEmail','','This is not a valid Email Address.')) { 
			totalErrors++;
		} else if(page == 'add') {
				var emailaddy = document.getElementById('chrEmail').value; 
				ajax = startAjax();
				if(ajax) {
					ajax.open("GET", BF + "ajax_functions.php?postType=checkpeople&col=chrEmail&val=" + emailaddy);
				
					ajax.onreadystatechange = function() { 
						if(ajax.readyState == 4 && ajax.status == 200) { 
							 //alert(ajax.responseText);
							 var result = ajax.responseText;
							 if(result != '' && result.length == 40) {
							 	setErrorMsg("The E-mail address you have entered already exists. <a href='#' onclick='newwin = window.open(\"popup_showperson.php?key=" + result + "\",\"new\",\"width=435,height=500,resizable=1,scrollbars=1\"); newwin.focus();'><strong>Click Here</strong></a> to view this entry.");
								
								
								
								
								 
								setColorError(chrEmail); 
								totalErrors++;
							 }  
						} 
					} 
					ajax.send(null); 
				}

		}
	}
	
	//if(errEmpty('chrJobTitle', "You must enter a Job Title.")) { totalErrors++; }
	if(errEmpty('idTshirt', "You must select a T-Shirt Size.")) { totalErrors++; }
	//if(errEmpty('chrOfficePhone', "You must enter a Office Phone Number.")) { totalErrors++; }
	if(errEmpty('chrMobilePhone', "You must enter Mobile Phone Number.")) { totalErrors++; }
	if(errEmpty('idMobileCarrier', "You must select a Mobile Carrier.")) { totalErrors++; }
	if(document.getElementById('idCountry').value == "213" || document.getElementById('idCountry').value == "38") {
		if(errEmpty('idLocale', "You must select a State/Province")) { totalErrors++; }
	}
	if(errEmpty('idCountry', "You must select a Country")) { totalErrors++; }
	//if(errEmpty('idEmployeeType', "You must select a Employee Type")) { totalErrors++; }
	//if(errEmpty('idDepartment', "You must select a Department")) { totalErrors++; }
	if(errEmpty('idPersonStatus', "You must select a Account Status")) { totalErrors++; }
	if(page == 'add') {
		if(errPasswordsEmpty('chrPassword','chrPassword2',"You Must Enter a Password")) { totalErrors++; }
		else if (errPasswordsMatch('chrPassword','chrPassword2',"Passwords must match")) { totalErrors++; }
	} else {
		if(errPasswordsMatch('chrPassword','chrPassword2',"Passwords must match")) { totalErrors++; }
	}
	return (totalErrors == 0 ? true : false);
}