<?
	include_once($BF.'components/add_functions.php');
	$table = 'Events'; # added so not to forget to change the insert AND audit

	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		chrEvent = '". encode($_POST['chrEvent']) ."',
		dBegin = '". date('Y-m-d',strtotime($_POST['dBegin'])) ."',
		dEnd = '". date('Y-m-d',strtotime($_POST['dEnd'])) ."',
		idTimeZone='".$_POST['idTimeZone']."',
		chrTextMessage='".encode($_POST['chrTextMessage'])."',
		bTextMessage='".(isset($_POST['bTextMessage']) ? 1 : 0)."'
	";
	
	# if there database insertion is successful	
	if(db_query($q,"Insert into ". $table)) {

		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

		// Set Upload Directory
		$upload_dir = $BF. "eventfiles/".$newID;
		// If the directory doesn't exist then create
		if (!is_dir($upload_dir)) {
			#chmod($BF ."eventfiles/", 0777);
			mkdir($upload_dir, 0777);
		}
		//check if the directory is writable.
        if (!is_writeable("$upload_dir")) { chmod($upload_dir, 0777); }		
		
		$tmp = db_query("INSERT INTO LandingPage SET idEvent = '". $newID ."'","Insert event into Landing Page Table");
		
		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($_POST['chrEvent']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idPerson='". $_SESSION['idPerson'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
		if(isset($_POST['idUsers']) && $_POST['idUsers'] != '') {
			$users = explode(',',$_POST['idUsers']);
			$q2 = "";
			foreach($users as $v) {
				$q2 .= "('".makekey()."','".$newID."','".$v."'),";
			}
			db_query("INSERT INTO ShowManagers (chrKEY,idEvent,idPerson) VALUES ". substr($q2,0,-1),"inserting show managers");
		}	
		$_SESSION['InfoMessage'] = "New Event: " . encode($_POST['chrEvent']) . " has been added";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this Event.');
	}
?>