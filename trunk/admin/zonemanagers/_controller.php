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
			$title = "Manage Zone Managers";						# Page Title

			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'components/formfields.php');
			
			$q = "SELECT M.ID,M.chrKEY,CONCAT(P.chrLast,', ',P.chrFirst) AS chrManager,P.chrLast,P.chrFirst, Z.chrZone, L.chrLocation
				  FROM ZoneManagers AS M 
				  JOIN People AS P on M.idPerson=P.ID
				  JOIN Zones AS Z ON M.idZone=Z.ID
				  JOIN Locations AS L ON Z.idLocation=L.ID
		 		  WHERE !M.bDeleted AND !P.bDeleted AND P.idPersonStatus=3 AND M.idEvent='".$_SESSION['idEvent']."' AND !Z.bDeleted AND Z.idEvent='".$_SESSION['idEvent']."' AND !L.bDeleted AND L.idEvent='".$_SESSION['idEvent']."'
				  ORDER BY chrLast, chrFirst";
				
			$results = db_query($q,"getting Managers");
			
			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script><?
				include($BF .'components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "ZoneManagers";
				include($BF ."includes/overlay.php");
			}

			# The template to use (should be the last thing before the break)
			$page_title = linkto(array('address'=>'add.php','img'=>'plus_add.png')).' Zone Managers <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$directions = 'Choose a Zone Manager from the list below. Click on the column header to sort the list by that column.';
			include($BF ."models/template.php");		

			break;
		

 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			$title = "Add Zone Manager";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'components/formfields.php');

			if(isset($_POST['idPerson'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Add New Zone Manager';
			$directions = 'You are adding a Zone Manager to the database.';
			include($BF ."models/template.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			$title = "Edit Zone Manager";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'components/formfields.php');

			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Zone Manager'); } // Check Required Field for Query

			$info = db_query("SELECT M.*,P.chrFirst,P.chrLast 
								FROM ZoneManagers AS M 
								JOIN People AS P ON M.idPerson=P.ID
								WHERE M.chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Zone Manager'); } // Did we get a result?
			
			if(isset($_POST['idPerson'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?

			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Edit Show Manager: '.$info['chrFirst'].' '.$info['chrLast'];
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