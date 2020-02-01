<?
		$_SESSION['ExpertiseUpdated'] = 1;
		unset($_SESSION['Expertise']);
		$count=0;
		if(isset($_POST['idExpertise'])) {
			foreach($_POST['idExpertise'] as $row => $id) {
				$_SESSION['Expertise'][$id] = $_POST['idLevel'.$id];
				if($_POST['idLevel'.$id] == "") {
					$_SESSION['errorMessages'][] = "You must select a Experience level for ".$_POST['chrExpertise'][$id].".";
				}
			$count++;
			}
		}
		if($count == 0 ) { $_SESSION['errorMessages'][] = "You must choose at least 1 Expertise."; }

		if(count($_SESSION['errorMessages']) == 0) {

			if(isset($_SESSION['Editing'])) {
				header("Location: step3.php");
				die();
			} else {
				header("Location: step2.php");
				die();
			}

		} else { $totalerrors = count($_SESSION['errorMessages']); }
?>