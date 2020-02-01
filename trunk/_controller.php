<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '';
	
	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];

	switch($file_name[0]) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			//$title = "Welcome to Apple Staffing";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			auth_check('litm','1,2,3,4');
			include_once($BF.'components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'components/list/sortlistjs.php');
			}

			# The template to use (should be the last thing before the break)
			$page_title = 'Welcome to '.$PROJECT_NAME;
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Make iCal Page
		#################################################
		case 'makeical.php':
			$title = "iCal Subscribe";
			include($BF .'_lib.php');
			include($BF.'components/add_functions.php');
			# Stuff In The Header
			function sith() { 
				global $BF;
			}
			$key = makekey();
			$query = "SELECT SCH.ID, E.chrEvent, L.chrLocation, S.dDate, S.tBegin, S.tEnd, ST.chrStation, ST.chrNumber
						FROM Schedule AS SCH
						JOIN Stations AS ST ON SCH.idStation=ST.ID
						JOIN Shifts AS S ON SCH.idShift=S.ID
						JOIN Locations AS L ON ST.idLocation=L.ID AND S.idLocation=L.ID
						JOIN Events AS E ON L.idEvent=E.ID
						WHERE SCH.idPerson=".$_SESSION['idPerson']."
						ORDER BY dBegin, tBegin, tEnd";
			
			$q = "INSERT INTO CalendarQueries SET 
				chrKEY='". $key ."',
				dtCreated=now(),
				idPerson='". $_SESSION['idPerson'] ."',
				chrCalendarQuery='". encode($query) ."'
			";	
			if(db_query($q,"Inserting Query into Database")) {
				$ICAL_ADDRESS = str_replace('http://','webcal://',$PROJECT_ADDRESS);
				header("Location: ".$ICAL_ADDRESS."ical.php?k=". $key);
			}	
			# The template to use (should be the last thing before the break)
			$page_title = 'iCal Subscribe';
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Log Out Page
		#################################################
		case 'logout.php':
			$title = "Logged Off";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."models/nonav.php");		
			
			break;
		#################################################
		##	Error Page
		#################################################
		case 'error.php':
			$title = "Error Page";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			include_once($BF.'components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."models/nonav.php");		
			
			break;
		#################################################
		##	Error Page
		#################################################
		case 'locked.php':
			$title = "Account Locked";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			include_once($BF.'components/formfields.php');

			# Stuff In The Header
			function sith() { 
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
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>