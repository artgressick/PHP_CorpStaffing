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
			$title = "Manage New Registrations";						# Page Title

			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include_once($BF.'components/formfields.php');

			if (!isset($_REQUEST['idStatus']) || !is_numeric($_REQUEST['idStatus']) || $_REQUEST['idStatus'] == 3 || $_REQUEST['idStatus'] == 5) {
				$_REQUEST['idStatus'] = 2;
			}			
			
			$q = "SELECT P.ID, P.chrKEY, chrLast, chrFirst, chrEmail, DATE_FORMAT(dtCreated,'%c/%e/%Y - %l:%i %p') AS dtCreated, chrPersonStatus, DATEDIFF(NOW(),dtCreated) AS intAge 
				  FROM PersonStatus PS 
				  JOIN People P ON P.idPersonStatus=PS.ID 
				  WHERE !P.bDeleted AND !PS.bDeleted AND PS.ID='".$_REQUEST['idStatus']."' 
				  ORDER BY intAge";
				
			$results = db_query($q,"getting people");		
			
			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'People <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$directions = "Choose a person from the list below. Click on the column header to sort the list by that column.";
			$filter = db_query("SELECT ID, chrPersonStatus as chrRecord FROM PersonStatus WHERE !bDeleted AND ID IN (1,2,4) ORDER BY ID","getting employee types");
			$filters = form_select($filter,array('name'=>'idStatus','caption'=>'-Select Status-','nocaption'=>'true','value'=>$_REQUEST['idStatus'],'extra'=>'onchange="javascript:document.getElementById(\'idForm\').submit();"'));
			
			include($BF ."models/template.php");		

			break;

		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			$title = "Update Registrant";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include_once($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Registrant'); } // Check Required Field for Query

			$info = db_query("
								SELECT * 
								FROM People 
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Registrant'); } // Did we get a result?
			
			if(isset($_POST['idPersonStatus'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?

			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Update Registrant: '.$info['chrFirst'].' '.$info['chrLast'];
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
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