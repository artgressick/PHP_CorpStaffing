<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';

	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];
    $auth_options = 'eventnotneeded';

	switch($file_name[0]) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			# Variables for the page
			$title = "Manage Expertise Per Event";						# Page Title

			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');
			include($BF.'components/formfields.php');

			if(isset($_POST['save']) && $_POST['save'] == "Save/Update") { include($post_file); }
			
			
			if(isset($_SESSION['idEvent']) && is_numeric($_SESSION['idEvent']) && (!isset($_POST['idEvent']) || !is_numeric($_POST['idEvent']))) {
				$tmp = $_SESSION['idEvent'];
			} else if (isset($_POST['idEvent']) && is_numeric($_POST['idEvent'])) {
				$tmp = $_POST['idEvent'];
			} else { 
				$tmp = '';
			}
			if($tmp != '') {
				$info = db_query("SELECT ID AS idEvent, chrEvent, dEnd FROM Events WHERE ID=".$tmp,"Getting Event Info",1);
			}
			
			if(isset($info['idEvent'])) {
				$q = "SELECT ID, chrExpertise, 
						(SELECT idExpertise FROM EventExpertise WHERE idExpertise=Expertise.ID AND idEvent='".$info['idEvent']."') as idExpertise
				 FROM Expertise WHERE !bDeleted ORDER BY chrExpertise";
				
				$results = db_query($q,"getting Expertise");
			}

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Event Expertise <span class="resultsShown">(<span id="resultCount">'.(isset($results)?mysqli_num_rows($results):'0').'</span> results)</span>';
			if(isset($info['idEvent'])) {
				$directions = 'Check the boxes next to the Expertise that Event <strong>'.$info['chrEvent'].'</strong> requires. Un-check to remove a expertise, click "Save/Update" to save changes.';
			} else {
				$directions = 'Please select a Event to modify the List of Expertise needed for this event.';
			}
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