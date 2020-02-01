<?
	$BF = '../';
	include($BF .'_lib.php');

	if(!isset($_SESSION['emailer']) || !is_numeric($_SESSION['emailer']['newID'])) { 
		$_SESSION['errorMessages'][] = 'You need to create a e-mail before you can send it.';
		header("Location: index.php");
		die();
	}
	
	$emailinfo = db_query("SELECT chrSubject, chrAttachmentName, txtMessage, bAttachSchedule
					   FROM EmailerLogs
					   WHERE EmailerLogs.ID=".$_SESSION['emailer']['newID'],"Getting Emailer Information",1);

	$results = db_query($_SESSION['emailer']['query'],"Getting Attendees to email");
	
	$tmp = db_query("UPDATE EmailerLogs SET
					 intRecipients = '".mysqli_num_rows($results)."',
					 dtSent=now()
					 WHERE ID=".$_SESSION['emailer']['newID'],"Setting number of recipients and dtSent");
	
	$usrMsg = array();
	if($emailinfo['bAttachSchedule']) {
		$schedules = db_query("SELECT SCH.idPerson, L.chrLocation, CONCAT(S.chrStation,' (',S.chrNumber,')') AS chrStation, SH.dDate, SH.tBegin, SH.tEnd
			  FROM Schedule AS SCH
			  JOIN Locations AS L ON SCH.idLocation AND L.idEvent='".$_SESSION['idEvent']."'
			  JOIN Stations AS S ON S.idLocation=L.ID AND SCH.idStation=S.ID
			  JOIN Shifts AS SH ON SH.idLocation=L.ID AND SCH.idShift=SH.ID
			  ORDER BY SCH.idPerson,dDate,tBegin,tEnd","Getting Users Schedule");

		$userschedule = array();
		while($row = mysqli_fetch_assoc($schedules)) {
			if(!isset($userschedule[$row['idPerson']])) { $userschedule[$row['idPerson']] = ''; }
			$userschedule[$row['idPerson']] .= '
				<p><b>Location:</b> '. $row['chrLocation'] .'<br />
				<b>Station:</b> '. $row['chrStation'] .'<br />
				<b>Date:</b> '. date('l, F jS, Y',strtotime($row['dDate'])) .', '. date('g:i a', strtotime($row['tBegin'])) .' - '. date('g:i a', strtotime($row['tEnd'])) .'</p>'; 
		}
		
		while($row = mysqli_fetch_assoc($results)) {
			if(!isset($usrMsg[$row['ID']]['msg'])) { $usrMsg[$row['ID']]['msg'] = ""; }
			$usrMsg[$row['ID']]['msg'] .= "
				<br /><p>Your Schedule for <b>".$row['chrEvent']."</b><br />
				---------------------------------------------------------------------</p>
				".$userschedule[$row['ID']];
			$usrMsg[$row['ID']]['chrEmail'] = $row['chrEmail'];
		}
	} else {
		while($row = mysqli_fetch_assoc($results)) {
			$usrMsg[$row['ID']]['chrEmail'] = $row['chrEmail'];
		}
	}


	$count=0;
	foreach($usrMsg as $k => $v) {
		$count++;
		$info['txtMsg'] = $emailinfo['txtMessage'];

		if($emailinfo['bAttachSchedule']) {
			$info['txtMsg'] .= $usrMsg[$k]['msg'];
		}
		
		$info['chrEmail'] = $usrMsg[$k]['chrEmail'];
		$info['chrSubject'] = $emailinfo['chrSubject'];
		if($emailinfo['chrAttachmentName'] != '') { $info['Attachment'] = $BF.'upload/'.$emailinfo['chrAttachmentName']; }
		
		include($BF.'includes/_emailer.php');	
?>
		<script language="javascript" type="text/javascript">
			var cnt='<?=$count?>';
			if (cnt > 25) { window.parent.document.getElementById('line'+(cnt - 25)).style.display = 'none'; }
			window.parent.document.getElementById('emaillog').innerHTML += '<div id="line<?=$count?>">E-mail Sent to <strong><?=$info['chrEmail']?></strong> - <?=$count?></div>';
		</script>
<?
		ob_flush();
		flush();
					
		if (($count%50) == 0) {
?>
		<script language="javascript" type="text/javascript">
			var cnt='<?=$count?>';
			if (cnt > 25) { window.parent.document.getElementById('line'+(cnt - 25)).style.display = 'none'; }	
			window.parent.document.getElementById('emaillog').innerHTML += '<div id="pause<?=$count?>">PAUSE FOR 1 SECOND</div>';
		</script>
<?
			ob_flush();
			flush();
			sleep(1);
?>
		<script language="javascript" type="text/javascript">
			var cnt='<?=$count?>';
			window.parent.document.getElementById('pause'+(<?=$count?>)).style.display = 'none';
		</script>
<?
			ob_flush();
			flush();
		}
	}
?>
<script language="javascript" type="text/javascript">
		var cnt='<?=$count?>';
		if (cnt > 25) { window.parent.document.getElementById('line'+(cnt - 25)).style.display = 'none'; }	
		window.parent.document.getElementById('emaillog').innerHTML += '<div>FINISHED, YOU MAY CLOSE OR MOVE ON</div>';
		
</script>
<?
ob_flush();
flush();
unset($_SESSION['emailer']);
?>