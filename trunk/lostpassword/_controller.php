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
			$title = "Lost Password";	# Page Title
			$directions = 'Please fill out the information below and click on the "Submit", A new password verification link will be sent to you with further instructions. You must complete the within 24 hours from submitting this request. ';
			# Adding in the lib file
			include($BF .'_lib.php');
			include_once($BF.'components/formfields.php');
		
			if(isset($_POST['chrEmail'])) { include($post_file); } else { (isset($_REQUEST['email']) && $_REQUEST['email'] != "" ? $info['chrEmail'] = $_REQUEST['email'] : $info = 0); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."models/nonav.php");		
			
			break;
		#################################################
		##	Change Password Page
		#################################################
		case 'change.php':
			$title = "Create New Password";	# Page Title
			$directions = 'Please fill out the information below and click on the "Submit".';
			# Adding in the lib file
			include($BF .'_lib.php');
			include_once($BF.'components/formfields.php');
			
			if(!isset($_REQUEST['key'])) {
				errorPage('Invalid or Missing key. Please check your URL and try again.');
			}
			//The explode code here was set from the index.php page, this must match exactly
			list($chrLostPassword, $chrKEY) = explode('7Y1g3', $_REQUEST['key']);

			if(strlen($chrLostPassword) != 40) {
				$tmp = db_query("UPDATE People SET dtLostPassword=NULL, chrLostPassword='' WHERE chrKEY='".$chrKEY."'");
				$_SESSION['errorMessages'][] = "The Lost Password Request you clicked is invalid. Please fill out this form to send a new Request.";
				header("Location: index.php");
				die();
			}

			if(strlen($chrKEY) != 40) {
				$tmp = db_query("UPDATE People SET dtLostPassword=NULL, chrLostPassword='' WHERE chrLostPassword='".$chrLostPassword."'");
				$_SESSION['errorMessages'][] = "The Lost Password Request you clicked is invalid. Please fill out this form to send a new Request.";
				header("Location: index.php");
				die();
			}
			
			// Lets grab the user first off
			$q = "SELECT ID, dtLostPassword, chrKEY, chrEmail FROM People WHERE !bDeleted AND idPersonStatus NOT IN (4,5) AND chrKEY='".$chrKEY."' AND chrLostPassword='".$chrLostPassword."'";
			$info = db_query($q,"Getting Person Information",1);
			
			if ($info['ID'] == "") {
				errorPage('Invalid or Missing key. Please check your URL and try again.');
			}
			
			if(strtotime($info['dtLostPassword']) < strtotime('NOW-25 HOURS')) {
				$tmp = db_query("UPDATE People SET dtLostPassword=NULL, chrLostPassword='' WHERE chrKEY='".$chrKEY."'");
				$_SESSION['errorMessages'][] = "The Lost Password Request you clicked has expired (it must be used within 24 hours). Please fill out this form to send a new Request.";
				header("Location: index.php?email=" . $info['chrEmail']);
				die();
			} 

			if(isset($_POST['chrPassword1'])) { include($post_file); }
			
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