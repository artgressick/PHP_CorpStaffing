<?php
	$ERROR = array();
	include_once($BF.'components/edit_functions.php');

	if($_POST['chrPassword1'] == "") { $_SESSION['errorMessages'][] = "You must choose a Password."; $ERROR['chrPassword1'] = true; }
	if($_POST['chrPassword2'] == "") { $_SESSION['errorMessages'][] = "You must confirm your Password."; $ERROR['chrPassword2'] = true; }
	if($_POST['chrPassword1'] != "" && $_POST['chrPassword2'] != "" && $_POST['chrPassword1'] != $_POST['chrPassword2']) { 
		$_SESSION['errorMessages'][] = "Your Passwords do not match."; $ERROR['chrPassword1'] = true; $ERROR['chrPassword2'] = true;  
	}

	if (count($_SESSION['errorMessages'])==0) {
		
		$_POST['chrPassword'] = $_POST['chrPassword1']; 
		
		// Set the basic values to be used.
		//   $table = the table that you will be connecting to to check / make the changes
		//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		$table = 'People';
		$mysqlStr = '';
		$audit = '';
	
		// "List" is a way for php to split up an array that is coming back.  
		// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
		//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
		//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
		//    ...  This also will ONLY add changes to the audit table if the values are different.
		list($mysqlStr,$audit) = set_strs_password($mysqlStr,'chrPassword',$info['chrPassword'],$audit,$table,$info['ID']);
				
		// if nothing has changed, don't do anything.  Otherwise update / audit.
		if($mysqlStr != '') { 
			$_SESSION['infoMessages'][] = "Your new Password has been saved.";
			db_query("UPDATE ".$table." SET bLocked=0, intAttempts=0 WHERE ID=".$info['ID'],"Resetting Bad Login Count");
			list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
			$tmp = db_query("UPDATE ".$table." SET dtModified=now() WHERE ID=".$info['ID'],"Setting dtModified");
		 } else {
		 	$_SESSION['infoMessages'][] = "No Changes have been made to your account.";
		 }
		
		header("Location: ".$BF."index.php");
		die();	
		
	}
	
	
?>