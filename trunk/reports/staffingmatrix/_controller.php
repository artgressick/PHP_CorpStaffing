<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';
	
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

			if(!isset($_REQUEST['idLocation']) || !is_numeric($_REQUEST['idLocation'])) { $_REQUEST['idLocation'] = ''; } 
			
			# Stuff In The Header
			function sith() { 
				global $BF;
			}
			
			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrEvent'].' - Staffing Matrix';
			$page_title = $_SESSION['chrEvent']." - Staffer Matrix";	# Page Title
			$directions = 'Select "Location" from list to show matrix. Excel report will show all locations as different tabs.<br />
						   <em>NOTE: Locations must have stations before they will show up in list</em>';
			include($BF ."models/fullscreen.php");		
			
			break;

		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>