<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';

	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];

	switch($file_name[0]) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			# Variables for the page
			$title = "Manage Shifts";						# Page Title
			

			# Files to include
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'components/formfields.php');

			$q = "SELECT Shifts.ID,Shifts.chrKEY,chrLocation,
				DATE_FORMAT(dDate,'%m/%d/%Y') as dDate,DATE_FORMAT(tBegin,'%l:%i %p') as tBegin,DATE_FORMAT(tEnd,'%l:%i %p') as tEnd
				FROM Shifts
				JOIN Locations ON Locations.ID=Shifts.idLocation
				WHERE !Shifts.bDeleted AND !Locations.bDeleted AND Locations.idEvent='".$_SESSION['idEvent']."' ".(isset($_REQUEST['idLocation']) && is_numeric($_REQUEST['idLocation']) ? " AND Shifts.idLocation='".$_REQUEST['idLocation']."'":$_REQUEST['idLocation']='')."
				ORDER BY chrLocation,dDate,tBegin,tEnd";
				
			$results = db_query($q,"getting shifts");
			
			
			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script><?
				include($BF .'components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "Shifts";
				include($BF ."includes/overlay.php");
			}

			# The template to use (should be the last thing before the break)
			$page_title = linkto(array('address'=>'add.php','img'=>'plus_add.png')).' Shifts <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$directions = 'Choose a Shift from the list below. Click on the column header to sort the list by that column.';
			$filter = db_query('SELECT ID,chrLocation as chrRecord FROM Locations WHERE !bDeleted AND idEvent="'.$_SESSION['idEvent'].'" ORDER BY chrLocation',"getting locations");
			$filters = form_select($filter,array('name'=>'idLocation','caption'=>'-Select Location-','nocaption'=>'true','value'=>$_REQUEST['idLocation'],'filter'=>'Show All Locations'));
			
			include($BF ."models/template.php");		

			break;
		

 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			$title = "Add Shift";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include_once($BF.'components/formfields.php');

			if(isset($_POST['idLocation'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Add New Shift';
			$directions = 'You are adding a Shift to the database.';
			include($BF ."models/template.php");	
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			$title = "Edit Shift";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include_once($BF.'components/formfields.php');

			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Shift'); } // Check Required Field for Query

			$info = db_query("
								SELECT Shifts.* 
								FROM Shifts 
								JOIN Locations ON Shifts.idLocation=Locations.ID
								WHERE Shifts.chrKEY='". $_REQUEST['key'] ."' AND Locations.idEvent='".$_SESSION['idEvent']."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Shift'); } // Did we get a result?
			
			if(isset($_POST['idLocation'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?

			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Edit Shift: '.date('m/d/Y',strtotime($info['dDate'])).' from '.date('g:i a',strtotime($info['tBegin'])).' to '.date('g:i a',strtotime($info['tEnd']));
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