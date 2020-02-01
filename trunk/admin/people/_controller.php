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
			$title = "Manage People";						# Page Title

			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include_once($BF.'components/formfields.php');

			if (!isset($_REQUEST['idStatus']) || !is_numeric($_REQUEST['idStatus']) || $_REQUEST['idStatus'] == 1 || $_REQUEST['idStatus'] == 2 || $_REQUEST['idStatus'] == 4) { $_REQUEST['idStatus'] = 3;	}
						
			if (!isset($_SESSION['char_people']) && !isset($_REQUEST['chrChr'])) {  // Default
				$_SESSION['char_people'] = "A";	$_REQUEST['chrChr'] = "A";
			} else if (isset($_REQUEST['chrChr']) && $_REQUEST['chrChr'] != "") {
				$_SESSION['char_people'] = $_REQUEST['chrChr'];
			}
			
			if(!isset($_REQUEST['chrSearch'])) { $_REQUEST['chrSearch'] = ""; }
		
			if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == "Search ALL") {
				$_SESSION['char_people'] = ""; $_REQUEST['chrChr'] = "";
			}
			
			$q = "SELECT P.ID,P.chrKEY,P.chrFirst,P.chrLast,P.chrEmail,P.bDeleted,
					PS.chrPersonStatus 
					FROM PersonStatus AS PS
					JOIN People AS P ON P.idPersonStatus=PS.ID
					WHERE !PS.bDeleted";
			
			if($_REQUEST['chrSearch'] != "") {
				$searchstr = str_replace(" ","%",$_REQUEST['chrSearch']);
				$q2 = " chrLast LIKE '%".encode($searchstr)."%' OR chrFirst LIKE '%".encode($searchstr)."%' OR chrEmail LIKE '%".encode($searchstr)."%' OR ";
				if(preg_match('/ /',$_REQUEST['chrSearch'],$matches)) {
					$search = explode(" ", $_REQUEST['chrSearch']);
					foreach ($search as $k) {
						$q2 .= " chrLast LIKE '%".encode($k)."%' OR chrFirst LIKE '%".encode($k)."%' OR chrEmail LIKE '%".encode($k)."%' OR ";
					}
				}
				$q2 = substr($q2,0,-3);
				$q .= " AND ( ".$q2." ) ";
			} 
			
			if($_SESSION['char_people'] != "" && $_SESSION['char_people'] != "All") { $q .= " AND chrLast LIKE '".$_SESSION['char_people']."%' "; }
			
			if($_REQUEST['idStatus'] != 0 && $_REQUEST['idStatus'] != 999) {
				$q .= " AND !P.bDeleted AND PS.ID=".$_REQUEST['idStatus'];
			} else if ($_REQUEST['idStatus'] != 999) {
				$q .= " AND P.bDeleted";
			}
			
			$q .= " ORDER BY chrLast,chrFirst";
				
			$results = db_query($q,"getting people");
			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script><?
				include($BF .'components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "People";
				include($BF ."includes/overlay.php");
			}

			# The template to use (should be the last thing before the break)
			$page_title = (access_check('1') ? linkto(array('address'=>'add.php','img'=>'plus_add.png')).' ' : '').'People <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$directions = "Choose a person from the list below. Click on the column header to sort the list by that column.";
			$filters = 'Search:&nbsp;';
			$filters .= form_text(array('nocaption'=>'true','name'=>'chrSearch','caption'=>'Search Users','value'=>$_REQUEST['chrSearch'],'size'=>'15'));
			$filters .= form_button(array('type'=>'submit','name'=>'search','value'=>'Search ALL','style'=>'height:17px;padding:0px 4px; margin:0px; font-size: 10px;'));
			if($_SESSION['char_people'] != "" && $_SESSION['char_people'] != "%" && $_SESSION['char_people'] != "All") {
				$filters .= form_button(array('type'=>'submit','name'=>'search','value'=>'Search Within &quot;'.$_SESSION['char_people'].'&quot;','style'=>'height:17px;padding:0px 4px; margin:0px; font-size: 10px;'));
			}			
			$filters .= '&nbsp;&nbsp;&nbsp;&nbsp;Filter By Status:&nbsp;';

			$employeetypes = db_query("SELECT ID, chrPersonStatus FROM PersonStatus WHERE !bDeleted AND ID IN (3,5) ORDER BY ID","getting employee types");
			$filter = array();
			while($row = mysqli_fetch_assoc($employeetypes)) {
				$filter[$row['ID']] = $row['chrPersonStatus'];
			}
			$filter['0'] = 'Deleted';
			$filter['999'] = 'All';
				
			$filters .= form_select($filter,array('name'=>'idStatus','caption'=>'-Select Status-','nocaption'=>'true','value'=>$_REQUEST['idStatus']));			
			include($BF ."models/template.php");		

			break;

		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			$title = "Add Person";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');
			include_once($BF.'components/formfields.php');
			
			if(isset($_POST['chrEmail'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script>
	<script type='text/javascript' src='error_check.js'></script>
<?

			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Add Person';
			$directions = 'You are adding a Person to the database.';
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			$title = "Edit Person";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');
			include_once($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Person'); } // Check Required Field for Query

			$info = db_query("
								SELECT * 
								FROM People 
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Person'); } // Did we get a result?
			
			if(isset($_POST['chrEmail'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?

			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Edit Person: '.$info['chrFirst'].' '.$info['chrLast'];
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	view Page
		#################################################
		case 'view.php':
			$title = "View Person";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include_once($BF.'components/formfields.php');
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Person'); } // Check Required Field for Query

			$info = db_query("
								SELECT * 
								FROM People 
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Person'); } // Did we get a result?
			
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'View Person: '.$info['chrFirst'].' '.$info['chrLast'];
			$directions = 'This is a view page only. You can not edit any of this information here.';
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Popup View Person Page
		#################################################
		case 'popup_showperson.php':
			$title = "View Person";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Person'); } // Check Required Field for Query

			$info = db_query("SELECT * 
								FROM People 
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Person'); } // Did we get a result?

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>
		function edituser(key) {
			window.opener.location = 'edit.php?key=' + key;
			window.close();
		}
	</script>
<?
				
			}


			# The template to use (should be the last thing before the break)
			$page_title = 'View Person';
			$directions = '';
			include($BF ."models/popup.php");		
			
			break;
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>