
					
function change(val) {
	
	if(val == 'preview' && document.getElementById('emailTo').value != '') {

		var len = document.getElementById('chrTo').options.length;  
		i=0;	
		while( i < len ) {  
			if( document.getElementById('chrTo').value == document.getElementById('chrTo').options[i].value) { 
				document.getElementById('emailTo').innerHTML = document.getElementById('chrTo').options[i].innerHTML;
			}
			i++;
		}

		document.getElementById('emailSubject').innerHTML = document.getElementById('chrSubject').value;
		document.getElementById('emailBody').innerHTML = tinyMCE.getContent();
			
		document.getElementById('previewEmail').style.display = "block";
		document.getElementById('editEmail').style.display = "none";
	} else {
		document.getElementById('previewEmail').style.display = "none";
		document.getElementById('editEmail').style.display = "block";
	}
}

function showHideCheckbox(obj) {
	var chkbox = document.getElementById('sendSchedule');
	if(obj.value == '3') {
		chkbox.style.display = '';
	} else {
		chkbox.style.display = 'none;';
		document.getElementById('bSendSched').checked = false;
	}
	if(document.getElementById('emailTo').value != '') {
		document.getElementById('preview').disabled = false;
	} else {
		document.getElementById('preview').disabled = true;
	}
}