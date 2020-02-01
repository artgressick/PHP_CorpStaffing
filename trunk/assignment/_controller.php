<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../';
	
	$file_name = basename($_SERVER['SCRIPT_NAME']);
    $post_file = '_'.$file_name;

	switch($file_name) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2,3');
			include_once($BF.'components/formfields.php');


/*			if(count($_POST)) { 
				include($post_file);
			} else {			
				# If no variable d was passed via the browser then error
				if(!isset($_REQUEST['d']) || $_REQUEST['d'] == "") { errorPage('Invalid Matrix Selection'); } // Check Required Field for Query
				parse_str(base64_decode($_REQUEST['d']), $data);
			}
				
			# Check to ensure that we have all the required variables before showing the page.			
			if(!isset($data['idLocation'])) { errorPage('Invalid Matrix Selection'); } // Check Required Field for Query
			if(!isset($data['idShift'])) { errorPage('Invalid Matrix Selection'); } // Check Required Field for Query
			if(!isset($data['idStation'])) { errorPage('Invalid Matrix Selection'); } // Check Required Field for Query
			if(!isset($_POST['idPerson'])) { $data['idPerson'] = ''; }
*/
			

			# Stuff In The Header
			function sith() { 
?>

		<style type="text/css">
		/*<![CDATA[*/
		<!--
		
		table.matrix a { width: 100%; }
		table.matrix a { width: 100%; display: block; padding: 2px 0; }
		table.matrix td { text-align: center; border: 1px solid #ccc; width: 10px; white-space: nowrap; }
		table.matrix td.left { text-align: left;}
		table.matrix td.staffed { background: #EFEFC2; }		
		table.matrix th { background: #E7EDF6; color: #000; padding: 2px 0; border-top: 1px solid #ccc; border-right: 1px solid #ccc; }
		table.matrix th.blank { background: white; border: none; border-right: 1px solid #ccc; }

		div.matrix { margin-top: 10px; }	
		div.matrix a { color: #333333; background: inherit; text-decoration: none; }	
		div.matrix { position:absolute;top:0; left:0; } /* fixes IE slowness?? */					
		div.matrix a:link {color: #333333; text-decoration: none; background: #cccc99; }
		div.matrix a:active {color: #000000; text-decoration: none; background: #cccc99; }
		div.matrix a:hover {color: #fff; text-decoration: none; background: #3875D7; }
		
		-->
		/*]]>*/
		</style>
		<script type='text/javascript'>
function innerPopup() {
	var height = (document.height > window.innerHeight ? document.height : window.innerHeight);
	document.getElementById('gray').style.height = height + "px";

	document.getElementById('overlaypage').style.display = "block";
}
function revertPopup() {
	document.getElementById('overlaypage').style.display = "none";
}
		</script>
		
<?
			}

			
			if(isset($_SESSION['showmatrix']) && $_SESSION['showmatrix']) {
				unset($_SESSION['showmatrix']);
				$bodyParams = 'innerPopup();revertPopup();innerPopup();';
			}
			# The template to use (should be the last thing before the break)
			$page_title = "Staffer Assignment Matrix";	# Page Title
			//$directions = 'Select the staffer you would like to Schedule and click "Add". Click on the Info link to view information on that staffer. Click the link again to close the pop-up.';
			$directions = 'Select Location from list below, click "Show People" to view staffer matrix. Click matrix cell to add/edit the assignment.';
			include($BF ."models/template.php");		
			
			break;

		#################################################
		##	Assignshift Page
		#################################################
		case 'assignshift.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2,3');
			include_once($BF.'components/formfields.php');

			if(count($_POST)) { 
				include($post_file);
			} else {			
				# If no variable d was passed via the browser then error
				if(!isset($_REQUEST['d']) || $_REQUEST['d'] == "") { errorPage('Invalid Matrix Selection'); } // Check Required Field for Query
				parse_str(base64_decode($_REQUEST['d']), $data);
			}
				
			# Check to ensure that we have all the required variables before showing the page.			
			if(!isset($data['idLocation'])) { errorPage('Invalid Matrix Selection'); } // Check Required Field for Query
			if(!isset($data['idShift'])) { errorPage('Invalid Matrix Selection'); } // Check Required Field for Query
			if(!isset($data['idStation'])) { errorPage('Invalid Matrix Selection'); } // Check Required Field for Query
			if(!isset($data['idPerson'])) { $data['idPerson'] = ''; }


			function sith() {
				global $BF;
				include($BF.'components/miniPopup.php');
				include($BF.'components/list/sortlistjs.php');
			}
			
			# The template to use (should be the last thing before the break)
			$page_title = "Staffer Assignment";	# Page Title
			$directions = 'Select the staffer you would like to schedule and click "Add". Click on the Info link to view information on that staffer. Click the link again to close the pop-up.<br />
						   <em>NOTE: Staffers listed are those who will not have their schedule overlap and have requested to work this shift.</em>';
			include($BF ."models/template.php");		
			
			break;
			
			
			
		#################################################
		##	Schedule Override Page
		#################################################
		case 'override.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include_once($BF.'components/formfields.php');


			if(count($_POST)) { 
				include($post_file);
			} else {			
				# If no variable d was passed via the browser then error
				if(!isset($_REQUEST['d']) || $_REQUEST['d'] == "") { errorPage('Invalid Matrix Selection'); } // Check Required Field for Query
				parse_str(base64_decode($_REQUEST['d']), $data);
			}
				
			# Check to ensure that we have all the required variables before showing the page.			
			if(!isset($data['idLocation'])) { errorPage('Invalid Matrix Selection'); } // Check Required Field for Query
			if(!isset($data['idShift'])) { errorPage('Invalid Matrix Selection'); } // Check Required Field for Query
			if(!isset($data['idStation'])) { errorPage('Invalid Matrix Selection'); } // Check Required Field for Query
			if(!isset($data['idPerson'])) { $data['idPerson'] = ''; }

			function sith() {
				global $BF;
				include($BF.'components/miniPopup.php');
				include($BF.'components/list/sortlistjs.php');
			}
			
			# The template to use (should be the last thing before the break)
			$page_title = "Staffer Override";	# Page Title
			$directions = 'Select the staffer you would like to schedule and click "Add". Click on the Info link to view information on that staffer. Click the link again to close the pop-up.<br />
						   <em>NOTE: All staffers are listed unless their schedule would overlap.</em>';
			include($BF ."models/template.php");		
			
			break;
			
		#################################################
		##	Popup Staffer Info Page
		#################################################
		case 'popupinfo.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2,3');
			include_once($BF.'components/formfields.php');

			$info = db_query("SELECT ID,chrFirst,chrLast FROM People WHERE chrKEY='".$_REQUEST['key']."'","Getting Person Info",1);
			$expertise = db_query("SELECT E.chrExpertise, PE.idLevel
								   FROM Expertise AS E
								   JOIN EventExpertise AS EE ON E.ID=EE.idExpertise AND EE.idEvent='".$_SESSION['idEvent']."' 
								   JOIN PersonExpertise AS PE ON E.ID=PE.idExpertise AND PE.idPerson='".$info['ID']."' AND PE.idEvent='".$_SESSION['idEvent']."' 
								   WHERE !E.bDeleted
								   ORDER BY chrExpertise
								","Getting Person Expertise");
			
			$shifts = db_query("SELECT L.chrLocation,CONCAT(DATE_FORMAT(Shifts.dDate,'%a, %b %e, %Y'),' from ',DATE_FORMAT(Shifts.tBegin,'%l:%i %p'),' to ',DATE_FORMAT(Shifts.tEnd,'%l:%i %p')) AS chrShift
								FROM Shifts
								JOIN Locations AS L ON L.ID=Shifts.idLocation AND L.idEvent='".$_SESSION['idEvent']."' AND L.bStaffingEnabled AND !L.bDeleted
								JOIN Schedule_Requested AS SR ON Shifts.ID=SR.idShift AND SR.idPerson='".$info['ID']."'
								WHERE !Shifts.bDeleted
								ORDER BY chrLocation, Shifts.dDate,Shifts.tBegin,Shifts.tEnd
								","Getting Requested Shifts");
			

			$bodyParams = "resizeMiniPopup();";			
			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'components/list/sortlistjs.php');
?>
<script type='text/javascript'>
	function resizeMiniPopup() {
		window.parent.document.getElementById('miniPopupWindow').style.height = (document.getElementById('innerBody').offsetHeight + 50) + "px";
	}
</script>
<?
			}
			
			# The template to use (should be the last thing before the break)
			$title = "Staffer Expertise/Shift Information";	# Page Title
			$directions = '';
			include($BF ."models/popup.php");		
			
			break;
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>