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
			$title = "Manage Employee Types";						# Page Title

			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');

			$q = "SELECT D.ID,D.chrKEY,D.chrEmployeeType,D.intOrder
				  FROM EmployeeTypes AS D 
		 		  WHERE !D.bDeleted 
				  ORDER BY intOrder";
				
			$results = db_query($q,"getting EmployeeTypes");

			if(isset($_POST['submit'])) { include($post_file); }
			include($BF.'components/formfields.php');
			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script><?
				include($BF .'components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "EmployeeTypes";
				include($BF ."includes/overlay.php");
			}

			# The template to use (should be the last thing before the break)
			$page_title = linkto(array('address'=>'add.php','img'=>'plus_add.png')).' Employee Types <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$directions = 'Choose a Employee Type from the list below. Click on the column header to sort the list by that column.';
			include($BF ."models/template.php");		

			break;
		

 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			$title = "Add Employee Type";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');
			include($BF.'components/formfields.php');

			if(isset($_POST['chrEmployeeType'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Add New Employee Type';
			$directions = 'You are adding a Employee Type to the database.';
			include($BF ."models/template.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			$title = "Edit Employee Type";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Employee Type'); } // Check Required Field for Query

			$info = db_query("SELECT * 
								FROM EmployeeTypes 
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Employee Type'); } // Did we get a result?
			
			if(isset($_POST['chrEmployeeType'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?

			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Edit Employee Type: '.$info['chrEmployeeType'];
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