<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../';
	
	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];

	switch($file_name[0]) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			$title = "Register";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			include_once($BF.'components/formfields.php');
		
			if(isset($_POST['chrEmail'])) { include($post_file); } else { $info = 0; }

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$page_title = 
			$directions = 'Please fill out the information below and click on the "Submit" button at the bottom.';
			include($BF ."models/nonav.php");		
			
			break;
		#################################################
		##	Registration Next Step
		#################################################
		case 'regnextstep.php':
			$title = "Registration - Next Step";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
		
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."models/nonav.php");		
			
			break;
		#################################################
		##	Confirm E-mail
		#################################################
		case 'confirmemail.php':
			$title = "Confirm E-mail";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			
			if(!isset($_REQUEST['key'])) {
				errorPage('Invalid or Missing key. Please check your URL and try again.');
			}
			// Lets grab the user first off
			$q = "SELECT ID, chrFirst, chrLast, chrEmail, bDeleted, bLocked, idPersonStatus FROM People WHERE chrKEY='".$_REQUEST['key']."'";
			$info = db_query($q,"Getting Person Information",1);
			
			if ($info['ID'] == "") {
				errorPage('Invalid or Missing key. Please check your URL and try again.');
			}	
			
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."models/nonav.php");		
			
			break;
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>