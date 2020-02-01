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
			$title = "Manage Stations";						# Page Title

			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'components/formfields.php');
			
			$q = "SELECT S.ID,S.chrKEY,S.chrStation,S.chrNumber,ST.chrStationType,L.chrLocation
				  FROM Stations AS S 
				  JOIN StationTypes AS ST on S.idStationType=ST.ID
				  JOIN Locations AS L ON S.idLocation=L.ID
		 		  WHERE !S.bDeleted AND !ST.bDeleted AND !L.bDeleted AND L.idEvent='".$_SESSION['idEvent']."' ".(isset($_REQUEST['idLocation']) && is_numeric($_REQUEST['idLocation']) ? " AND S.idLocation='".$_REQUEST['idLocation']."'":$_REQUEST['idLocation']='')."
				  ORDER BY chrStation";
				
			$results = db_query($q,"getting stations");
			
			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script><?
				include($BF .'components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "Stations";
				include($BF ."includes/overlay.php");
			}

			# The template to use (should be the last thing before the break)
			$page_title = linkto(array('address'=>'add.php','img'=>'plus_add.png')).' Stations <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$directions = 'Choose a Location from the list below. Click on the column header to sort the list by that column.';
			$filter = db_query('SELECT ID,chrLocation as chrRecord FROM Locations WHERE !bDeleted AND idEvent="'.$_SESSION['idEvent'].'" ORDER BY chrLocation',"getting locations");
			$filters = form_select($filter,array('name'=>'idLocation','caption'=>'-Select Location-','nocaption'=>'true','value'=>$_REQUEST['idLocation'],'filter'=>'Show All Locations'));
			
			include($BF ."models/template.php");		

			break;
		

 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			$title = "Add Station";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'components/formfields.php');

			if(isset($_POST['chrStation'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Add New Station';
			$directions = 'You are adding a Station to the database.';
			include($BF ."models/template.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			$title = "Edit Station";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Station'); } // Check Required Field for Query

			$info = db_query("SELECT Stations.* 
								FROM Stations 
								JOIN Locations ON Stations.idLocation=Locations.ID
								WHERE Stations.chrKEY='". $_REQUEST['key'] ."' AND Locations.idEvent='".$_SESSION['idEvent']."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Station'); } // Did we get a result?
			
			if(isset($_POST['chrStation'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?

			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Edit Station: '.$info['chrStation'];
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