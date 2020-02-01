<?
		$_SESSION['ShiftsUpdated'] = 1;
		unset($_SESSION['Shifts']);
		$count=0;
		foreach($_POST['idShifts'] as $row => $id) {
			$_SESSION['Shifts'][$id] = $id;
		$count++;
		}

		if($count == 0 ) { $_SESSION['errorMessages'][] = "You must choose at least 1 Shift."; }

		if(count($_SESSION['errorMessages']) == 0) {

			header("Location: step3.php");
			die();

		} else { $totalerrors = count($_SESSION['errorMessages']); }
?>