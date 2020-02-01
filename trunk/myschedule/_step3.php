<?
		unset($_SESSION['Editing']);
		$errcnt = 0;
		// Delete existing records for person and event for Expertise
		if(db_query("DELETE FROM PersonExpertise WHERE idEvent='".$_SESSION['idEvent']."' AND idPerson='".$_SESSION['idPerson']."'","Removing all Records")) {
			$q2 = "";
			//make values
			foreach($_SESSION['Expertise'] as $expertise => $level) {
				$q2 .= "('".$_SESSION['idEvent']."','".$_SESSION['idPerson']."','".$expertise."','".$level."'),";
			}
			$q2 = substr($q2,0,-1);
			if($q2 != "") {
				// insert new records
				if(!db_query("INSERT INTO PersonExpertise (idEvent,idPerson,idExpertise,idLevel) VALUES ".$q2,"Inserting Records")) {
					$errcnt++;
				}
			}
		} else {
			$errcnt++;
		}

		// Delete exisiting records for persona and event for Shifts
		if(db_query("DELETE FROM Schedule_Requested WHERE idEvent='".$_SESSION['idEvent']."' AND idPerson='".$_SESSION['idPerson']."'","Removing all Records")) {
			$q2 = "";
			//make values
			foreach($_SESSION['Shifts'] as $id) {
				$q2 .= "('".$_SESSION['idEvent']."','".$_SESSION['idPerson']."','".$id."'),";
			}
			$q2 = substr($q2,0,-1);
			if($q2 != "") {
				// insert new records
				if(!db_query("INSERT INTO Schedule_Requested (idEvent,idPerson,idShift) VALUES ".$q2,"Inserting Records")) {
					$errcnt++;
				}
			}
		} else {
			$errcnt++;
		}

		
		if ($errcnt == 0) {
			
			// Send E-mail
			$info['chrEmail'] = $_SESSION['chrEmail'];
			$info['chrSubject'] = $PROJECT_NAME." Scheduling Confirmation";
			
			// Grab Staffing info
				$q = "SELECT S.ID, S.dDate, S.tBegin, S.tEnd, L.chrLocation, S.idLocation
						FROM Locations L 
						JOIN Shifts S ON S.idLocation = L.ID 
						WHERE !L.bDeleted AND !S.bDeleted AND L.idEvent='".$_SESSION['idEvent']."' AND S.ID IN (". implode(",",array_keys($_SESSION['Shifts'])) .") 
						ORDER BY chrLocation, S.dDate, S.tBegin
						";
						
				$results = db_query($q, "Getting all Shifts for Event");
			
			$info['txtMsg'] = "<p>Dear ".$_SESSION['chrFirst'].",</p>

			<p>Congratulations you've registered as a ".$_SESSION['chrEvent']." Volunteer. Stay tuned for the shift time and location you're selected to staff.</p>

			<p>Review the times you are available to work below. If you need to make any corrections please log-on to  
			<a href='".$PROJECT_ADDRESS."'>".$PROJECT_ADDRESS."</a></p>

			<p>Shifts you applied for<br />
			---------------------------------------------<br />";

		while ($row = mysqli_fetch_assoc($results)) {
			$info['txtMsg'] .= "<strong>Location:</strong> ".decode($row['chrLocation'])." <strong>on</strong> ".date('l, F jS, Y',strtotime($row['dDate']))." 
			 <strong>from</strong> ".date('g:i a',strtotime($row['tBegin']))." <strong>to</strong> ".date('g:i a',strtotime($row['tEnd']))."<br />";			
		}
			
			$info['txtMsg'] .= "</p>
			
			<p>Once you have been selected to staff the event, details can be found at the URL above. You'll also be receiving an email prior to the start of the event.</p>
			
			<p>Send questions to <a href='mailto:".$PROJECT_EMAIL."'>".$PROJECT_EMAIL."</a>.</p>
			
			<p>Thank you, See you at the Event!<br />
			".$PROJECT_NAME."</p>";

			// send E-mail
			include($BF .'includes/_emailer.php');
				
			unset($_SESSION['Expertise']);
			unset($_SESSION['Shifts']);
			unset($_SESSION['Editing']);
			unset($_SESSION['ExpertiseUpdated']);
			unset($_SESSION['ShiftsUpdated']);

			header("Location: thankyou.php");
			die();
		
		} else { 
			$_SESSION['errorMessages'][] = "An Error occurred while saving your information, please contact an Administrator";
		}	
		
?>