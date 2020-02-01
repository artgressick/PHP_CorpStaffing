<?
	if (isset($_POST['idEvent'])) {  // check to see if this is a submission of the login form
		$ERROR = array();
		if($_POST['idEvent'] == "") { $_SESSION['errorMessages'][] = "You must select a Event to Continue."; $ERROR['idEvent'] = true; }
		
		if(count($_SESSION['errorMessages']) == 0) {
		
			$event = db_query("SELECT ID, chrEvent FROM Events WHERE ID='".$_POST['idEvent']."'","Getting Event Info",1);
			if ($event['ID'] == "") {
				$_SESSION['errorMessages'][] = "You must select a valid Event to Continue."; $ERROR['idEvent'] = true;
			}
			if(count($_SESSION['errorMessages']) == 0) {			
				$_SESSION['idEvent'] = $event['ID'];
				$_SESSION['chrEvent'] = $event['chrEvent'];

				// First Security Check

				$q = "SELECT P.bAdmin, SM.ID as idShowMan, ZM.ID as idZoneMan
						FROM People AS P
						LEFT JOIN ShowManagers AS SM ON SM.idPerson=P.ID AND SM.idEvent='".$_SESSION['idEvent']."' AND !SM.bDeleted 
						LEFT JOIN ZoneManagers AS ZM ON ZM.idPerson=P.ID AND ZM.idEvent='".$_SESSION['idEvent']."' AND !ZM.bDeleted
						WHERE P.ID='".$_SESSION['idPerson']."'";
				
				$tmp = db_query($q,'Checking Access',1);				
				if($tmp['bAdmin']=='1') { // If they are a Admin, set bAdmin and idLevel to 1 
					$_SESSION['bAdmin']='1';
					$_SESSION['idLevel']='1';
				} else if ($tmp['idShowMan'] != "") { // if they are a Show Manager
					 $_SESSION['idLevel']='2';
				} else if ($tmp['idZoneMan'] != "") { // if they are a Zone Manager
					 $_SESSION['idLevel']='3';
				} else { // They are a Staffer
					$_SESSION['idLevel']='4';
				}
				
				$_SESSION['dtLastSecurityCheck'] = date('m/d/Y H:i:s');
				
				# This sends the user to whatever page they were originally trying to get to before being stopped to choose an event
				header('Location: ' . $_SERVER['REQUEST_URI']);
				die();
			}
		}
	}
	# if they need to be log in for the current page and currently are not yet logged in, send them to the login page.
	include_once($BF.'components/formfields.php');
	include($BF . "event_select.php");
	$page_title='Please Select an event to Continue';
	$directions = 'Please select the event you wish to go to from the list below.';
	include($BF ."models/template.php");		

	die();
?>
