<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../';

	$file_name = basename($_SERVER['SCRIPT_NAME']);
    $post_file = '_'.$file_name;
	
	switch($file_name) {
		#################################################
		##	Step 1 Page (Expertise)
		#################################################
		case 'step1.php':
			# Variables for the page
			$title = "My Schedule";						# Page Title
			$directions = 'Check the boxes next to the Expertise that you have and then select the Experience level you have for that Expertise.<br />
						   <strong>WARNING: Changes are not saved until after you click "Submit" on the Confirmation Page!</strong>';

			# Files to include
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2,3,4');
			include($BF.'components/formfields.php');

			if(isset($_POST['submit'])) { include($post_file); }			
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."models/nonav.php");		

			break;
		#################################################
		##	Step 2 Page (Shifts)
		#################################################
		case 'step2.php':
			# Variables for the page
			$title = "My Schedule";						# Page Title
			$directions = 'Check the boxes next to the Shifts that you would be interested in.<br />
						   <strong>WARNING: Changes are not saved until after you click "Submit" on the Confirmation Page!</strong>';

			# Files to include
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2,3,4');
			include($BF.'components/formfields.php');
			$ERROR = array();

			if(isset($_POST['submit'])) { include($post_file); }			
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."models/nonav.php");		

			break;
		#################################################
		##	Step 3 Page (Confirmation)
		#################################################
		case 'step3.php':
			# Variables for the page
			$title = "My Schedule";						# Page Title
			$directions = 'Please confirm all information you have choosen, and click "Submit". You may change these options later by going to "Preferences" then "My Schedule".<br />
						   <strong>WARNING: Changes are not saved until after you click "Submit" on the Confirmation Page!</strong>';

			# Files to include
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2,3,4');
			$_SESSION['Editing'] = 1;
	
			include($BF.'components/formfields.php');

			if(isset($_POST['submit'])) { include($post_file); }			
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."models/nonav.php");		

			break;
		#################################################
		##	Thank You Page (Thank You)
		#################################################
		case 'thankyou.php':
			# Variables for the page
			$title = "My Schedule";						# Page Title

			# Files to include
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2,3,4');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# Stuff On The Bottom
			function sotb() { 
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
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.'.$file_name);
	}

?>