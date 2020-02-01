<?
	include_once($BF.'components/edit_functions.php');
	$ERROR = array();
	if($_POST['chrFirst'] == "") { $_SESSION['errorMessages'][] = "You must enter a First Name."; $ERROR['chrFirst'] = true; }
	if($_POST['chrLast'] == "") { $_SESSION['errorMessages'][] = "You must enter a Last Name."; $ERROR['chrLast'] = true; }
	if($_POST['chrEmail'] == "") { 
		$_SESSION['errorMessages'][] = "You must enter an Email Address."; $ERROR['chrEmail'] = true; 
	} else {
		$result = db_query("SELECT ID FROM People WHERE chrEmail='". $_POST['chrEmail'] ."' AND ID != ".$_SESSION['idPerson']." AND !bDeleted","Email Check");
		if(mysqli_num_rows($result) != 0) {
			$_SESSION['errorMessages'][] = "This Email Address has already been registered."; $ERROR['chrEmail'] = true;
		}
	}
	if($_POST['chrOfficePhone'] == "") { $_SESSION['errorMessages'][] = "You must enter an Office Phone Number."; $ERROR['chrOfficePhone'] = true; }
	if($_POST['chrMobilePhone'] == "") { $_SESSION['errorMessages'][] = "You must enter a Mobile Phone Number."; $ERROR['chrMobilePhone'] = true; }
	if($_POST['idMobileCarrier'] == "") { $_SESSION['errorMessages'][] = "You must choose a Mobile Phone Carrier."; $ERROR['idMobileCarrier'] = true; }
	if(($_POST['idCountry'] == "213" || $_POST['idCountry'] == "38") && $_POST['idLocale'] == "") { $_SESSION['errorMessages'][] = "You must choose a State/Province."; $ERROR['idLocale'] = true; }
	if($_POST['idCountry'] == "") { $_SESSION['errorMessages'][] = "You must choose a Country."; $ERROR['idCountry'] = true; }
	if($_POST['chrJobTitle'] == "") { $_SESSION['errorMessages'][] = "You must enter a Job Title."; $ERROR['chrJobTitle'] = true; }
	if($_POST['idTshirt'] == "") { $_SESSION['errorMessages'][] = "You must choose a Shirt Size."; $ERROR['idTshirt'] = true; }
	if($_POST['idEmployeeType'] == "") { $_SESSION['errorMessages'][] = "You must choose an Employee Type."; $ERROR['idEmployeeType'] = true; }
	if($_POST['idDepartment'] == "") { $_SESSION['errorMessages'][] = "You must choose a Department."; $ERROR['idDepartment'] = true; }
	if($_POST['chrPassword'] != $_POST['chrPassword2']) { 
		$_SESSION['errorMessages'][] = "Your Passwords do not match."; $ERROR['chrPasswords'] = true;  
	}

	
	
	
	
		
	if (count($_SESSION['errorMessages'])==0) {
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
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrFirst',$info['chrFirst'],$audit,$table,$_SESSION['ID']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrLast',$info['chrLast'],$audit,$table,$_SESSION['ID']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrEmail',$info['chrEmail'],$audit,$table,$_SESSION['ID']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrJobTitle',$info['chrJobTitle'],$audit,$table,$_SESSION['ID']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idTshirt',$info['idTshirt'],$audit,$table,$_SESSION['ID']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrOfficePhone',$info['chrOfficePhone'],$audit,$table,$_SESSION['ID']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrMobilePhone',$info['chrMobilePhone'],$audit,$table,$_SESSION['ID']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idMobileCarrier',$info['idMobileCarrier'],$audit,$table,$_SESSION['ID']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idLocale',$info['idLocale'],$audit,$table,$_SESSION['ID']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idCountry',$info['idCountry'],$audit,$table,$_SESSION['ID']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idEmployeeType',$info['idEmployeeType'],$audit,$table,$_SESSION['ID']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idDepartment',$info['idDepartment'],$audit,$table,$_SESSION['ID']);
		if($_POST['chrPassword'] != "" && $_POST['chrPassword2'] != "" && $_POST['chrPassword'] == $_POST['chrPassword2']) {
			list($mysqlStr,$audit) = set_strs_password($mysqlStr,'chrPassword',$info['chrPassword'],$audit,$table,$_SESSION['ID']);
		}
		
		// if nothing has changed, don't do anything.  Otherwise update / audit.
		if($mysqlStr != '') { 
			$_SESSION['infoMessages'][] = "Your profile has been updated successfully.";
			list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
			$tmp = db_query("UPDATE ".$table." SET dtModified=now() WHERE ID=".$info['ID'],"Setting dtModified");
		 } else {
		 	$_SESSION['infoMessages'][] = "No changes have been made to your profile ";
		 }
		
		header("Location: ".$BF."index.php");
		die();	
	} else {
		$info = $_POST;
		$totalerrors = count($_SESSION['errorMessages']);
	}
?>