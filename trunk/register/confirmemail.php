<?
	include('_controller.php');
		
	function sitm() { 
		global $BF,$info;
		$messages = array();
		// Now lets find out what kind of uers this is, first check for a deleted account, locked, declined, or blocked
		if($info['bDeleted'] == 1) { // Account was deleted
			$messages[] = "<p class='header4'>Your account has been removed from the system. Please re-submit your registration by going <a href='register.php'>HERE</a></p>";
		} else if($info['bLocked'] == 1) { // Account was locked due to failed login attempts
			$messages[] = "<p class='header4'>Your account has been locked due to 5 or more invalid log-in attempts. Please contact the Administrator.</p>";
		} else if($info['idPersonStatus'] == 2) { // Account is pending admin approval
			$messages[] = "<p class='header4'>Your E-mail address has already been verified, however your account must be reviewed by the Administrator.</p><p class='header4'>You will be notified by E-mail when this process is complete.</p>  <p>Thank you for your patience.</p>";
		} else if($info['idPersonStatus'] == 3) { // Account is approved and active
			$messages[] = "<p class='header4'>Your account has already been verified and approved, please login <a href='".$BF."index.php'>HERE</a></p>";
		} else if($info['idPersonStatus'] == 4) { // Account has been declined
			$messages[] = "<p class='header4'>Your account has been declined. Please contact an Administrator if you feel this is an error.</p>";
		} else if($info['idPersonStatus'] == 5) { // Account has been blocked
			$messages[] = "<p class='header4'>This e-mail address has been blocked. Please contact an Administrator if you feel this is an error.</p>";
		} else if($info['idPersonStatus'] == 1) {
			
			$q = "SELECT chrTrustedEmail FROM TrustedEmails WHERE !bDeleted";
			$results = db_query($q, "Getting Trusted E-mail Servers");
	
			$istrusted = false;
			$userserver = explode("@", $info['chrEmail']);
	
			while ($row = mysqli_fetch_assoc($results)) {
				if($row['chrTrustedEmail'] == $userserver[1]) {
					$istrusted = true;
				}
			}
			
			if($istrusted) { // is a trusted domain
				$q = "UPDATE People SET idPersonStatus=3, dtModified=now() WHERE ID=".$info['ID']." AND chrKEY='".$_REQUEST['key']."'";
				
				if(db_query($q,"Updating Person to Approved")) { 
					$messages[] = "<p class='header4'>Success!</p><p>Your Account has been verified and approved, please continue by logging in <a href='".$BF."index.php'>HERE</a><p>";
				} else {
					errorPage('An error has occurred while updating the status of your account, please contact the Administrator');
				}	
			} else { // not a trusted domain
				$q = "UPDATE People SET idPersonStatus=2, dtModified=now() WHERE ID=".$info['ID']." AND chrKEY='".$_REQUEST['key']."'";
				
				if(db_query($q,"Updating Person to Pending")) { 
					$messages[] = "<p class='header4'>Thank You for verifying your E-mail address!</p><p>In order to proceed your account requires Administration review.  You will be notified by e-mail when this process is complete. Thank you for your patience.<p>";
				} else {
					errorPage('An error has occurred while updating the status of your account, please contact the Administrator');
				}	
			}
	
		}
?>	
	<div class='header2'>E-mail Confirmation.</div>
	<div class='directions2'>Please follow the instructions below to continue.</div>
	<div class='innerbody'>
	
<?		foreach($messages as $msg) { ?>
			<div><?=$msg?></div>
<?		} ?>

<?	} ?>
