<?
	include_once($BF.'components/add_functions.php');
	include_once($BF.'components/edit_functions.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'Zones';
	$mysqlStr = '';
	$audit = '';

	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrZone',$info['chrZone'],$audit,$table,$info['ID']);

	$tmp = db_query("DELETE FROM ZoneStations WHERE idZone=".$info['ID'],"Delete old Zone Station Information");
	
	if(isset($_POST['idStations']) && count($_POST['idStations']) > 0) {
		$q2 = '';
		foreach($_POST['idStations'] AS $idStation) {
			$q2 .= "('".$info['ID']."','".$idStation."'),";
		}
		
		if($q2 != '') {
			$q2 = substr($q2,0,-1);
			$tmp = db_query("INSERT INTO ZoneStations (idZone,idStation) VALUES ".$q2,"Insert Stations for Zone");
		}
	}
	
	db_query("DELETE FROM ZoneManagers WHERE idZone=". $info['ID'],"erasing users");	 
	if(isset($_POST['idUsers']) && $_POST['idUsers'] != '') {
		$users = explode(',',$_POST['idUsers']);
		$q2 = "";
		foreach($users as $v) {
			$q2 .= "('".makekey()."','".$_SESSION['idEvent']."','".$v."','".$info['ID']."'),";
		}
		db_query("INSERT INTO ZoneManagers (chrKEY,idEvent,idPerson,idZone) VALUES ". substr($q2,0,-1),"inserting zone managers");
	}		
	// if nothing has changed, don't do anything.  Otherwise update / audit.
	if($mysqlStr != '') { 
		list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
	}
	
	$_SESSION['infoMessages'][] = $_POST['chrZone']. " has been successfully updated in the Database.";
		
	header("Location: index.php");
	die();	
?>