<?
	$ERROR = array();
	include_once($BF.'components/add_functions.php');
	
	if($_POST['chrFirst'] == "") { $_SESSION['errorMessages'][] = "You must enter a First Name."; $ERROR['chrFirst'] = true; }
	if($_POST['chrLast'] == "") { $_SESSION['errorMessages'][] = "You must enter a Last Name."; $ERROR['chrLast'] = true; }
	if($_POST['chrEmail'] == "") { 
		$_SESSION['errorMessages'][] = "You must enter an E-mail Address."; $ERROR['chrEmail'] = true;
	} else {
		if(!preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',$_POST['chrEmail'])) { 
			$_SESSION['errorMessages'][] = "You must enter a valid E-mail Address."; $ERROR['chrEmail'] = true;
		} else {
			$result = db_query("SELECT ID FROM People WHERE chrEmail='". $_POST['chrEmail'] ."' AND !bDeleted","Email Check");
			if(mysqli_num_rows($result) != 0) {
				$_SESSION['errorMessages'][] = "This E-mail Address has already been registered."; $ERROR['chrEmail'] = true;
			}
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
	if($_POST['chrPassword'] == "") { $_SESSION['errorMessages'][] = "You must choose a Password."; $ERROR['chrPassword'] = true; }
	if($_POST['chrPassword2'] == "") { $_SESSION['errorMessages'][] = "You must confirm your Password."; $ERROR['chrPassword2'] = true; }
	if($_POST['chrPassword'] != "" && $_POST['chrPassword2'] != "" && $_POST['chrPassword'] != $_POST['chrPassword2']) { 
		$_SESSION['errorMessages'][] = "Your Passwords do not match."; $ERROR['chrPassword'] = true; $ERROR['chrPassword2'] = true;  
	}
		
	if (count($_SESSION['errorMessages'])==0) {
		$table = 'People';

		$_POST['chrKEY'] = makekey(); // Generate a key for this person
	
		$q = "INSERT INTO ". $table ." SET 
			chrKEY = '". $_POST['chrKEY'] ."',
			idPersonStatus = 1,
			chrFirst = '". encode($_POST['chrFirst']) ."',
			chrLast = '". encode($_POST['chrLast']) ."',
			chrEmail = '". encode($_POST['chrEmail']) ."',
			chrOfficePhone = '". encode($_POST['chrOfficePhone']) ."',
			chrMobilePhone = '". encode($_POST['chrMobilePhone']) ."',
			idMobileCarrier = '". $_POST['idMobileCarrier'] ."',
			idCountry = '". $_POST['idCountry'] ."',
			idLocale = '". $_POST['idLocale'] ."',
			chrJobTitle = '". encode($_POST['chrJobTitle']) ."',
			idTshirt = '". $_POST['idTshirt'] ."',
			idEmployeeType = '". $_POST['idEmployeeType'] ."',
			idDepartment = '". $_POST['idDepartment'] ."',
			chrPassword = '". sha1($_POST['chrPassword']) ."',
			dtCreated = now()
		";
				
		// if nothing has changed, don't do anything.  Otherwise update / audit.
		if(db_query($q,"Insert into ". $table)) { 

				// This is the code for inserting the Audit Page
				// Type 1 means ADD NEW RECORD, change the TABLE NAME also
				global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
				$newID = mysqli_insert_id($mysqli_connection);
			
				$q = "INSERT INTO Audit SET 
					idType=1, 
					idRecord='". $newID ."',
					txtNewValue='". encode($_POST['chrFirst']) ." ". encode($_POST['chrLast']) ."',
					dtDateTime=now(),
					chrTableName='". $table ."',
					idPerson='". $newID ."'
				";
				db_query($q,"Insert audit");
				//End the code for History Insert 
				
				$_SESSION['chrFirst'] = $_POST['chrFirst'];
				$_SESSION['chrLast'] = $_POST['chrLast'];
				$_SESSION['chrEmail'] = $_POST['chrEmail'];
				
				// Setup of Email
				$info['chrEmail'] = $_POST['chrEmail'];
				$info['chrSubject'] = "Welcome to ".$PROJECT_NAME.".";
				$info['txtMsg'] = "
					Dear ".encode($_POST['chrFirst'])." ".encode($_POST['chrLast']).",<br /><br /> 
					Thank you for registering with ".$PROJECT_NAME.".<br /><br />
					For security reasons we ask that you verify your E-mail address before continuing.<br /><br />
					Please click (or copy and paste into your browser) the link below to confirm your e-mail address.<br /><br />
					<a href='".$PROJECT_ADDRESS."register/confirmemail.php?key=".$_POST['chrKEY']."'>".$PROJECT_ADDRESS."register/confirmemail.php?key=".$_POST['chrKEY']."</a><br /><br />
					Thank you,<br /><br />
					".$PROJECT_NAME;
				// send E-mail
				include($BF .'includes/_emailer.php');
		
				header("Location: regnextstep.php");
				die();
			
			
			
		} else {
			$info = $_POST;
			$_SESSION['errorMessages'][] = "An Error has occured while trying to create your account. Please check all information and try again.";
			$totalerrors = count($_SESSION['errorMessages']);
		}
	} else {
		$info = $_POST;
		$totalerrors = count($_SESSION['errorMessages']);
	}
?>