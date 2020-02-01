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
			$title = "Managment Zones";						# Page Title

			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'components/formfields.php');

			$q = "SELECT Z.ID,Z.chrKEY,chrZone,chrLocation,
					(SELECT COUNT(ZA.idStation) FROM ZoneStations AS ZA WHERE ZA.idZone=Z.ID) as intZones,
					(SELECT COUNT(ZM.ID) FROM ZoneManagers AS ZM WHERE ZM.idZone=Z.ID) as intManagers
				  FROM Zones AS Z
				  JOIN Locations AS L ON Z.idLocation=L.ID
				  WHERE !Z.bDeleted AND !L.bDeleted ".(isset($_REQUEST['idLocation']) && is_numeric($_REQUEST['idLocation']) ? " AND Z.idLocation='".$_REQUEST['idLocation']."'":$_REQUEST['idLocation']='')." AND L.idEvent='".$_SESSION['idEvent']."'
				  ORDER BY chrZone, chrLocation";
			$results = db_query($q,"getting Zones");
			
			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script><?
				include($BF .'components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "Zones";
				include($BF ."includes/overlay.php");
			}

			# The template to use (should be the last thing before the break)
			$page_title = linkto(array('address'=>'add.php','img'=>'plus_add.png')).' Zones <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$directions = 'Choose a Zone from the list below. Click on the column header to sort the list by that column.';
			$filter = db_query('SELECT ID,chrLocation as chrRecord FROM Locations WHERE !bDeleted AND idEvent="'.$_SESSION['idEvent'].'" ORDER BY chrLocation',"getting locations");
			$filters = form_select($filter,array('name'=>'idLocation','caption'=>'-Select Location-','nocaption'=>'true','value'=>$_REQUEST['idLocation'],'filter'=>'Show All Locations'));
			include($BF ."models/template.php");		

			break;
		

 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			$title = "Add Zone";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include_once($BF.'components/formfields.php');
			
			if(isset($_POST['chrZone'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript' src='<?=$BF?>includes/popupmultiadd.js'></script>
<?
				include($BF .'components/list/sortlistjs.php');
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Add Zone';
			$directions = 'You are adding a Zone to the database. Start by Selecting a Location, then Fill in the other fields.';
			include($BF ."models/template.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			$title = "Edit Zone";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include_once($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Zone'); } // Check Required Field for Query

			$info = db_query("SELECT * 
								FROM Zones 
								WHERE chrKEY='". $_REQUEST['key'] ."' AND idEvent='".$_SESSION['idEvent']."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Zone'); } // Did we get a result?
			
			if(isset($_POST['chrZone'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript' src='<?=$BF?>includes/popupmultiadd.js'></script>
<?
				include($BF .'components/list/sortlistjs.php');
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Edit Zone: '.$info['chrZone'];
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
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>