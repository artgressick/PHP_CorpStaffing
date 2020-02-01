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
			$title = "My Profile";	# Page Title
			$directions = 'You may update the information below and press the "Update Information" to save the changes. Changes are not saved until all required fields are filled.';
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			$auth_options = 'EventNotNeeded';
			auth_check('litm','1,2,3,4');
			include_once($BF.'components/formfields.php');


			$info = db_query("
								SELECT * 
								FROM People 
								WHERE ID='". $_SESSION['idPerson'] ."'
			","getting info",1); // Get Info
		
			if(isset($_POST['chrEmail'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'View/Modify My Account';
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>