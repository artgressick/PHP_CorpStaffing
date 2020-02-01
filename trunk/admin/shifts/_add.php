<?
	include_once($BF.'components/add_functions.php');
	$table = 'Shifts'; # added so not to forget to change the insert AND audit

	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		dDate = '". date('Y-m-d',strtotime($_POST['dDate'])) ."',
		tBegin = '". date('H:i',strtotime($_POST['tBegin'])) .":00',
		tEnd = '". date('H:i',strtotime($_POST['tEnd'])) .":00',
		idLocation = '". $_POST['idLocation'] ."'
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
			txtNewValue='". encode($_POST['dDate']." at ". $_POST['tBegin']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idPerson='". $_SESSION['idPerson'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		$_SESSION['infoMessages'][] = "New Shift on ".$_POST['dDate']." at ".$_POST['tBegin']." has been added";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this Shift.');
	}
?>