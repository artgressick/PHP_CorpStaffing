<?
	include_once($BF.'components/add_functions.php');
	$table = 'People'; # added so not to forget to change the insert AND audit

	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		chrFirst = '". encode($_POST['chrFirst']) ."',
		chrLast = '". encode($_POST['chrLast']) ."',
		chrEmail = '". encode($_POST['chrEmail']) ."',
		chrJobTitle = '". encode($_POST['chrJobTitle']) ."',
		idTshirt = '". $_POST['idTshirt'] ."',
		chrOfficePhone = '". encode($_POST['chrOfficePhone']) ."',
		chrMobilePhone = '". encode($_POST['chrMobilePhone']) ."',
		idMobileCarrier = '". $_POST['idMobileCarrier'] ."',
		idLocale = '". $_POST['idLocale'] ."',
		idCountry = '". $_POST['idCountry'] ."',
		idEmployeeType = '". $_POST['idEmployeeType'] ."',
		idDepartment = '". $_POST['idDepartment'] ."',
		idPersonStatus = '". $_POST['idPersonStatus'] ."',
		bLocked = '". $_POST['bLocked'] ."',
		chrPassword = '". sha1($_POST['chrPassword']) ."',
		dtCreated = NOW()
	";
	
	# if there database insertion is successful	
	if(db_query($q,"Insert into ". $table)) {

		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($_POST['chrFirst'].' '.$_POST['chrLast']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idPerson='". $_SESSION['idPerson'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
		$_SESSION['InfoMessage'] = "New Pers: " . encode($_POST['chrFirst'].' '.$_POST['chrLast']) . " has been added";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this Person.');
	}
?>