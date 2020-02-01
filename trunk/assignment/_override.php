<?
	if($_POST['moveTo'] == "") { // If they changed a option on the drop down menus
		
		parse_str(base64_decode($_POST['d']), $olddata);
		
		if($_POST['idLocation'] != $olddata['idLocation']) {
			$data['idLocation'] = $_POST['idLocation'];
			$data['idShift'] = '';
			$data['idStation'] = '';
		} else {
			$data['idLocation'] = $_POST['idLocation'];
			$data['idShift'] = $_POST['idShift'];
			$data['idStation'] = $_POST['idStation'];
		}
		
	} else {

		if($_POST['idLocation']=="") {
			$_SESSION['errorMessages'][] = "You must select a Location.";
		}
		if($_POST['idShift']=="") {
			$_SESSION['errorMessages'][] = "You must select a Shift.";
		}
		if($_POST['idStation']=="") {
			$_SESSION['errorMessages'][] = "You must select a Station.";
		}
		if(!isset($_POST['idPerson']) || $_POST['idPerson']=="") {
			$_SESSION['errorMessages'][] = "You must select a Staffer.";
		}
		if(count($_SESSION['errorMessages']) == 0) {
			
			$test = db_query("SELECT idPerson FROM Schedule WHERE idEvent='".$_SESSION['idEvent']."' AND idLocation='".$_POST['idLocation']."' AND idShift='".$_POST['idShift']."' AND idStation='".$_POST['idStation']."'","Getting old data if there",1);

			if($_POST['idPerson'] != $test['idPerson']) {
				$tmp = db_query("DELETE FROM Schedule WHERE idEvent='".$_SESSION['idEvent']."' AND idLocation='".$_POST['idLocation']."' AND idShift='".$_POST['idShift']."' AND idStation='".$_POST['idStation']."'","Removing old entries");
				$q = "
					  INSERT INTO Schedule SET 
					  idEvent='".$_SESSION['idEvent']."', 
					  idLocation='".$_POST['idLocation']."', 
					  idShift='".$_POST['idShift']."', 
					  idStation='".$_POST['idStation']."', 
					  idPerson='".$_POST['idPerson']."',
					  idAssigner='".$_SESSION['idPerson']."',
					  dtStamp=NOW()
					 ";
				if(db_query($q,"Inserting New Schedule")) {
					if($_POST['idPerson'] != 0) {
						$info = db_query("SELECT chrFirst, chrLast, chrEmail FROM People WHERE ID='".$_POST['idPerson']."'","Getting Staffer Information",1);
						
						$info['chrSubject'] = $PROJECT_NAME." Shift Update";

						$info['txtMsg'] = "<p>Dear ".$info['chrFirst'].",</p>
						<p>This e-mail is to notify you that your staffing schedule for the ".$_SESSION['chrEvent']." event has changed.</p>
						<p>Please log into the ".$PROJECT_NAME." web-site to check your updated schedule.</p>
						<p>Web-site Address: <a href='".$PROJECT_ADDRESS."'>".$PROJECT_ADDRESS."</a></p>
						<p>If you have any questions, please contact us at <a href='mailto:".$PROJECT_EMAIL."'>".$PROJECT_EMAIL."</a></p>
						<p>Thanks,<br />
						".$PROJECT_NAME."</p>";
						
						// send E-mail
						include($BF .'includes/_emailer.php');
						$_SESSION['infoMessages'][] = $info['chrFirst']." ".$info['chrLast']." has been Scheduled Successfully.";
					} else {
						$_SESSION['infoMessages'][] = "Scheduling options has been saved successfully.";
					}
					
					if($test['idPerson'] != 0 && $test['idPerson'] != "") {
						$info = db_query("SELECT chrFirst, chrLast, chrEmail, idPersonStatus FROM People WHERE ID='".$test['idPerson']."'","Getting Old Staffer Information",1);
						if($info['idPersonStatus'] == 3) {
							$info['chrSubject'] = $PROJECT_NAME." Shift Update";
			
							$info['txtMsg'] = "<p>Dear ".$info['chrFirst'].",</p>
							<p>This e-mail is to notify you that your staffing schedule for the ".$_SESSION['chrEvent']." event has changed.</p>
							<p>Please log into the ".$PROJECT_NAME." web-site to check your updated schedule.</p>
							<p>Web-site Address: <a href='".$PROJECT_ADDRESS."'>".$PROJECT_ADDRESS."</a></p>
							<p>If you have any questions, please contact us at <a href='mailto:".$PROJECT_EMAIL."'>".$PROJECT_EMAIL."</a></p>
							<p>Thanks,<br />
							".$PROJECT_NAME."</p>";
							
							// send E-mail
							include($BF .'includes/_emailer.php');
						}
					}
				} else {
					errorPage('An Error has occurred while scheduling this Staffer, Please contact an Administrator.');
				}
			} else {
				$_SESSION['infoMessages'][] = "No Scheduling options has been changed.";
			}

			if($_POST['moveTo'] == 'override.php') { 
				$_POST['moveTo'] = 'override.php?d='.$_POST['d'];
			} else {
				$_SESSION['showmatrix'] = true;
			 	$_POST['moveTo'] = 'index.php?idLocation='.$_POST['idLocation'];
			}
			header("Location: ".$_POST['moveTo']);
			die();	
		} else {
			$data = $_POST;
			$_REQUEST['d'] = base64_encode('idLocation='.$data['idLocation'].'&idShift='.$data['idShift'].'&idStation='.$data['idStation']);
		}
	}
?>