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
			$title = "Manage Events";						# Page Title

			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');
			include($BF.'components/formfields.php');

			$q = "
					SELECT E.ID,E.chrKEY,E.chrEvent,DATE_FORMAT(E.dBegin,'%m/%d/%Y') AS dBegin,DATE_FORMAT(E.dEnd,'%m/%d/%Y') AS dEnd
					FROM Events AS E
				";
			
			if(isset($_REQUEST['idFilter']) && is_numeric($_REQUEST['idFilter'])) { 
				$_SESSION['idEventFilter'] = $_REQUEST['idFilter'];
			} else if (!isset($_SESSION['idEventFilter']) || $_SESSION['idEventFilter'] > 4 || @$_REQUEST['idFilter'] > 4 || @$_REQUEST['idFilter'] < 1 || $_SESSION['idEventFilter'] < 1) {
				$_SESSION['idEventFilter'] = 1;
			}
			
			if($_SESSION['idEventFilter'] == 4) { // Deleted
				$q .= " WHERE E.bDeleted ";
			} else if ($_SESSION['idEventFilter'] == 2) { // Past
				$q .= " WHERE !E.bDeleted AND E.dEnd <= NOW() ";
			} else if ($_SESSION['idEventFilter'] == 3) { // ALL
				$q .= " WHERE !E.bDeleted ";
			} else { // Default, Current
				$q .= " WHERE !E.bDeleted AND E.dEnd >= NOW()";
			}
			
			$q .= " ORDER BY dBegin,dEnd,chrEvent";
		
				
			$results = db_query($q,"getting Events");		
			
			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script><?
				include($BF .'components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "Events";
				include($BF ."includes/overlay.php");
			}

			# The template to use (should be the last thing before the break)
			$page_title = linkto(array('address'=>'add.php','img'=>'plus_add.png')).' Events <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$directions = 'Choose a event from the list below. Click on the column header to sort the list by that column.';
			$filter = array(1 => 'Current/Upcoming',2 => 'Past',3 => 'All', 4 => 'Deleted');
			$filters = form_select($filter,array('name'=>'idFilter','caption'=>'-Select Filter-','nocaption'=>'true','value'=>$_SESSION['idEventFilter'],'extra'=>'onchange="javascript:document.getElementById(\'idForm\').submit();"'));
			include($BF ."models/template.php");		

			break;
		
 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			$title = "Add Event";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');
			include($BF.'components/formfields.php');

			if(isset($_POST['chrEvent'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add'; var BF = '<?=$BF?>';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript' src='<?=$BF?>includes/popupmultiadd.js'></script>
<?
				include($BF .'components/list/sortlistjs.php');
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Add New Event';
			$directions = 'You are adding a Event to the database.';
			include($BF ."models/template.php");	
			
			break;

		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			$title = "Edit Event";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Event'); } // Check Required Field for Query

			$info = db_query("
								SELECT * 
								FROM Events 
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Event'); } // Did we get a result?
			
			if(isset($_POST['chrEvent'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'edit'; var BF = '<?=$BF?>';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript' src='<?=$BF?>includes/popupmultiadd.js'></script>
<?
				include($BF .'components/list/sortlistjs.php');
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Edit Event: '.$info['chrEvent'];
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			include($BF ."models/template.php");		
			
			break;


		#################################################
		##	Popup People Page
		#################################################
		case 'popup_person.php':
			$title = "Edit Event";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');
			include($BF.'components/formfields.php');
			
/*			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Event'); } // Check Required Field for Query

			$info = db_query("SELECT * 
								FROM Events 
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Event'); } // Did we get a result?

*/
			
			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'components/list/sortlistjs.php');
?>	<script type='text/javascript'>var page = 'edit'; var BF = '<?=$BF?>';</script>
	<script type='text/javascript' src='error_check.js'></script>
<script type="text/javascript">
	function associate(id, first,last) {
<?		parse_str(base64_decode($_REQUEST['d']), $data);
		if(isset($data['functioncall'])) { ?>
			window.opener.<?=$data['functioncall']?>(id, first, last);
<?		} ?>
	}
</script>

<?

			}


			# The template to use (should be the last thing before the break)
			$page_title = 'People - People';
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			include($BF ."models/popup.php");		
			
			break;

		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
			errorPage($file_name[0].'Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>