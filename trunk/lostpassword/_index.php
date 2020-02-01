<?
	$ERROR = array();
	include_once($BF.'components/add_functions.php');
	
	if($_POST['chrEmail'] == "") { 
		$_SESSION['errorMessages'][] = "You must enter an Email Address."; $ERROR['chrEmail'] = true;
	} else {
		if(!preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',$_POST['chrEmail'])) { 
			$_SESSION['errorMessages'][] = "You must enter a valid Email Address."; $ERROR['chrEmail'] = true;
		} else {
			$results = db_query("SELECT chrKEY, chrEmail FROM People WHERE chrEmail='". $_POST['chrEmail'] ."' AND !bDeleted AND idPersonStatus NOT IN (4,5)","Email Check");
			if(mysqli_num_rows($results) == 0) {
				$_SESSION['errorMessages'][] = "You must enter a valid Email Address."; $ERROR['chrEmail'] = true;
			} else {
				$result=mysqli_fetch_assoc($results);
				$result['chrLostPassword'] = makekey(); // Generate a key for this person's Lost Password Request
			}
			
		}
	}
	
	if (count($_SESSION['errorMessages'])==0) {
		
		$tmp = db_query("UPDATE People SET dtLostPassword=now(), chrLostPassword='".$result['chrLostPassword']."' WHERE chrKEY='".$result['chrKEY']."'","Updating Lost Password KEY and Lost Password DT");
		
		//The variable below is a random alphanumeric code to seperate the person's chrKEY and chrLostPassword key
		$random = '7Y1g3';
		
		$info['chrEmail'] = $result['chrEmail'];
		$info['chrSubject'] = "Lost Password Verification. - ".$PROJECT_NAME;
		$info['txtMsg'] = "
			Someone (hopefully you!) notified us that you have forgotten your password to the ".$PROJECT_NAME." Web-site.<br /><br />
			To create a new password, click the following link (or copy and paste it into your browser's address bar):<br /><br />
			<a href='".$PROJECT_ADDRESS."lostpassword/change.php?key=".$result['chrLostPassword'].$random.$result['chrKEY']."'>".$PROJECT_ADDRESS."lostpassword/change.php?key=".$result['chrLostPassword'].$random.$result['chrKEY']."</a><br /><br />
			Please note, you must use this link in the next 24 hours or it will be disabled, and you will have to place another Lost Password Request.";
		// send E-mail
		include($BF .'includes/_emailer.php');

		$_SESSION['infoMessages'][] = "An e-mail has been sent to the e-mail address you supplied, Please check this e-mail for instructions on how to proceed.";
		header("Location: ".$BF."index.php");
		die();		
	}
?>