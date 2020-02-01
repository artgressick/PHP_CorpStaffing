<?
	include_once($BF.'components/edit_functions.php');
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
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idPersonStatus',$info['idPersonStatus'],$audit,$table,$info['ID']);
	
	// if nothing has changed, don't do anything.  Otherwise update / audit.
	if($mysqlStr != '') { 
		$_SESSION['infoMessages'][] = $info['chrFirst']." ".$info['chrLast']. " has been successfully updated in the Database.";
		list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
		$tmp = db_query("UPDATE ".$table." SET dtModified=now() WHERE ID=".$info['ID'],"Setting dtModified");
	 } else {
	 	$_SESSION['infoMessages'][] = "No Changes have been made to ".$info['chrFirst']." ".$info['chrLast'];
	 }

		 if($_POST['idPersonStatus'] != $info['idPersonStatus']) {

//			$_POST['chrEmail'] = "jsummers@techitsolutions.com"; // for testing

		 	if($_POST['idPersonStatus'] == 3) {
			
				// Setup of Email
				$info['chrEmail'] = $info['chrEmail'];
				$info['chrSubject'] = "Account Approved - ".$PROJECT_NAME;
				$info['txtMsg'] = "
					Dear ".encode($info['chrFirst'])." ".encode($info['chrLast']).",<br /><br /> 
					This e-mail is to notify you that your account is now Approved.<br /><br />
					You are now able to login and access the web-site via the link below using your E-mail address and Password you provided.<br /><br />
					Login at <a href='".$PROJECT_ADDRESS."'>".$PROJECT_ADDRESS."</a><br /><br />
					Thank you,<br /><br />
					".$PROJECT_NAME;
				// send E-mail
				include($BF .'includes/_emailer.php');
		 	
		 	} else if($_POST['idPersonStatus'] == 4) {

				// Setup of Email
				$info['chrEmail'] = $info['chrEmail'];
				$info['chrSubject'] = "Account Declined - ".$PROJECT_NAME;
				$info['txtMsg'] = "
					Dear ".encode($info['chrFirst'])." ".encode($info['chrLast']).",<br /><br /> 
					This e-mail is to notify you that your account has been Declined at this time.<br /><br />
					You may re-register at any time or your account may be changed to Approved if there is a position available.<br /><br />
					You may re-submit your registration at <a href='".$PROJECT_ADDRESS."'>".$PROJECT_ADDRESS."</a><br /><br />
					Thank you,<br /><br />
					".$PROJECT_NAME;
				// send E-mail
				include($BF .'includes/_emailer.php');
		 	
		 	} else if($_POST['idPersonStatus'] == 5) {

				// Setup of Email
				$info['chrEmail'] = $info['chrEmail'];
				$info['chrSubject'] = "Account Blocked - ".$PROJECT_NAME;
				$info['txtMsg'] = "
					Dear ".encode($info['chrFirst'])." ".encode($info['chrLast']).",<br /><br /> 
					This E-mail is to notify you that your E-mail address has been flagged as Spam and is Blocked form accessing our Web-site.<br /><br />
					If you feel this is an error please contact an Administrator.<br /><br />
					Thank you,<br /><br />
					".$PROJECT_NAME;
				// send E-mail
				include($BF .'includes/_emailer.php');
		 	
		 	}
		 
		 }
		
		header("Location: index.php?idStatus=".$info['idPersonStatus']);
		die();	 
	 
?>