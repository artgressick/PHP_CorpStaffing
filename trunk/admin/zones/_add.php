<?
	include_once($BF.'components/add_functions.php');
	$table = 'Zones'; # added so not to forget to change the insert AND audit

	$location = db_query("SELECT ID FROM Locations WHERE chrKEY='".$_POST['key']."'","Getting Location ID",1);
	
	
	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		chrZone = '". encode($_POST['chrZone']) ."',
		idLocation = '". $location['ID'] ."',
		idEvent = '". $_SESSION['idEvent'] ."'
	";
	
	# if there database insertion is successful	
	if(db_query($q,"Insert into ". $table)) {

		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

		if(isset($_POST['idStations']) && count($_POST['idStations']) > 0) {
			$q2 = '';
			foreach($_POST['idStations'] AS $idStation) {
				$q2 .= "('".$newID."','".$idStation."'),";
			}
			
			if($q2 != '') {
				$q2 = substr($q2,0,-1);
				$tmp = db_query("INSERT INTO ZoneStations (idZone,idStation) VALUES ".$q2,"Insert Stations for Zone");
			}
		}
		
		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($_POST['chrZone']) ."',
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
				$q2 .= "('".makekey()."','".$_SESSION['idEvent']."','".$v."','".$newID."'),";
			}
			db_query("INSERT INTO ZoneManagers (chrKEY,idEvent,idPerson,idZone) VALUES ". substr($q2,0,-1),"inserting zone managers");
		}
			
		$_SESSION['InfoMessage'] = "New Zone: " . encode($_POST['chrEvent']) . " has been added";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this Zone.');
	}
?>