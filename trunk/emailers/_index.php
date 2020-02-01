<?php
	if($_POST['chrTo'] == '1') {
		$_SESSION['emailer']['query'] = "SELECT ID,chrEmail FROM People WHERE !bDeleted AND idPersonStatus=3 GROUP BY chrEmail";
	} else if($_POST['chrTo'] == '2') {
		$_SESSION['emailer']['query'] = "SELECT People.ID, chrEmail
			FROM People
			JOIN Schedule_Requested ON Schedule_Requested.idPerson=People.ID AND idEvent='". $_SESSION['idEvent'] ."'
			WHERE !bDeleted AND idPersonStatus=3
			GROUP BY chrEmail
		";
		
	} else if($_POST['chrTo'] == '3') {
		$_SESSION['emailer']['query'] = "SELECT DISTINCT People.ID, chrFirst, chrLast, chrEmail, chrLocation, Events.chrEvent, 
			CONCAT(Stations.chrStation,' (',Stations.chrNumber,')') AS chrStation,
			DATE_FORMAT(Shifts.dDate, '%M %D, %Y') as dDate, DATE_FORMAT(Shifts.tBegin, '%l:%i %p') as dBegin, DATE_FORMAT(Shifts.tEnd, '%l:%i %p') as dEnd
			FROM Schedule
			JOIN Shifts ON Shifts.ID=Schedule.idShift
			JOIN Stations ON Stations.ID=Schedule.idStation
			JOIN Locations ON Locations.ID=Shifts.idLocation AND Stations.idLocation=Locations.ID
			JOIN Events ON Events.ID=Locations.idEvent
			JOIN People ON Schedule.idPerson=People.ID
			WHERE Events.ID=". $_SESSION['idEvent'] ." AND idPersonStatus=3
			ORDER BY chrLast, chrFirst, Shifts.dDate, Shifts.tBegin, Shifts.tEnd
		";
	}

	include_once($BF.'components/add_functions.php');

	$tmp = db_query("INSERT INTO EmailerLogs SET
					 bAttachSchedule = '". (isset($_POST['bSendSched'])?'1':'0') ."',
					 chrKEY = '". makekey() ."',
					 idEmailerType = '". $_POST['chrTo'] ."',
					 idPerson = '". $_SESSION['idPerson'] ."',
					 chrSubject = '". encode($_POST['chrSubject']). "',
					 txtMessage = '". encode($_POST['txtSourceCode']) ."'
					","Insert into EmailerLogs");
	
	global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
	$_SESSION['emailer']['newID'] = mysqli_insert_id($mysqli_connection);

	if(is_uploaded_file($_FILES['chrAttachment']['tmp_name'])) {
		$attName = str_replace(" ","_",basename($_FILES['chrAttachment']['name']));  //dtn: Replace any spaces with underscores.
		
		$uploaddir = $BF . 'upload/'; 	//dtn: Setting up the directory name for where things go
		$uploadfile = $uploaddir . $_SESSION['emailer']['newID'] .'-'. $attName;
		
		move_uploaded_file($_FILES['chrAttachment']['tmp_name'], $uploadfile);  //dtn: move the file to where it needs to go.
		
		$tmp = db_query("UPDATE EmailerLogs SET 
			intAttachmentSize=". $_FILES['chrAttachment']['size'] .",
			chrAttachmentName='". $_SESSION['emailer']['newID'] ."-". $attName ."',
			chrAttachmentType='". $_FILES['chrAttachment']['type'] ."'
			WHERE ID=". $_SESSION['emailer']['newID'] ."","Insert Attachment information into the DB");
	}

	header("Location: sendemails.php");
	die();
	
?>