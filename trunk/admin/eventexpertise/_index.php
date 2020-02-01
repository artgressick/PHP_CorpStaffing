<?
	if(db_query("DELETE FROM EventExpertise WHERE idEvent='".$_POST['idEvent']."'","Deleting all entries for this Event")) {
		$deletesuccess=true;
	} else {
		$deletesuccess=false;
	}
	
	
	$q2 = "";
	
	if(isset($_POST['eventexpertise'])) {
		foreach ($_POST['eventexpertise'] as $id) {
			if($q2 != "") { $q2 .= ","; }
			
			$q2 .= "(".$id.",".$_POST['idEvent'].")";
		}
	}
	
	$savesuccess=true;
	
	if ($q2 != "") {
		
		$q = "INSERT INTO EventExpertise (idExpertise, idEvent) VALUES ".$q2;
		
		if(!db_query($q,"Inserting Expertise for Events")) {
			$savesuccess=false;
		}
	} 

	if($deletesuccess && $savesuccess) {
		$_SESSION['infoMessages'][] = "All changes have been saved successfully.";
	
//		header("Location: index.php");
//		die();

	} else {
		errorPage('An error has occured while trying to save the changes.');
	}
?>