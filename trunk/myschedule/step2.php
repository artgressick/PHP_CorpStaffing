<?
	include('_controller.php');
		
	function sitm() { 
		global $BF,$directions,$totalerrors;
		
		if(!isset($_POST['idShifts'])) { $_POST['idShifts'] = array(); }
		
		if(!isset($_SESSION['ShiftsUpdated']) && !isset($_SESSION['Shifts'])) {
			// does this person have anything already in the DB?
			$q = "SELECT idShift FROM Schedule_Requested WHERE idEvent='".$_SESSION['idEvent']."' AND idPerson='".$_SESSION['idPerson']."'";
			$results = db_query($q,"Getting Person Expertise");
			
			while ($row = mysqli_fetch_assoc($results)) {
				$_SESSION['Shifts'][$row['idShift']] = $row['idShift'];
			}
		}
		
		$q = "SELECT S.ID, S.dDate, S.tBegin, S.tEnd, L.chrLocation, S.idLocation
				FROM Locations L 
				JOIN Shifts S ON S.idLocation = L.ID 
				WHERE !L.bDeleted AND !S.bDeleted AND L.idEvent='".$_SESSION['idEvent']."' 
				ORDER BY chrLocation, S.dDate, S.tBegin
			";
			
		$results = db_query($q, "Getting all Shifts for Event");
		
?>

	<div class='header2'>My Schedule - Shifts</div>
	<form action="" method="post" id="idForm">
		<div class='directions2'><?=$directions?></div>
		<?=messages()?>
<?
	$count=0;
	$newLocation="";
	while ($row = mysqli_fetch_assoc($results)) {
	
		if($row['idLocation'] != $newLocation) {
			$newLocation = $row['idLocation'];
?>
		<div class="LocationHeader" colspan="6"><?=$row['chrLocation']?></div>
<?
		}
?>
		<div class="ShiftRow"><?=form_checkbox(array('name'=>'idShifts','array'=>'true','value'=>$row['ID'],'checked'=>(isset($_SESSION['Shifts'][$row['ID']]) ? 'true' : 'false'),'title'=>encode(date('l, F jS, Y',strtotime($row['dDate'])).' -- '.date('g:i a',strtotime($row['tBegin'])).' to '.date('g:i a',strtotime($row['tEnd'])))))?></div>
<?	
		$count++;
	}
	if ($count==0) { 
?>			
		<div class="ShiftRow" colspan="6">No Shifts Avaliable at this Time</div>
<?			
	}
?>
		<div style="padding-top:15px;">
			<?=(isset($totalerrors) ? ' <div class="FormErrorCount">'.$totalerrors.' Error(s) Detected.</div>' : "" )?>
<?
		if($count!=0) {
			if (isset($_SESSION['Editing'])) { ?>
				<?=form_button(array('type'=>'submit','name'=>'submit','title'=>'Back to Confirmation','value'=>'Back to Confirmation'))?>
<?			} else { ?>
				<?=form_button(array('type'=>'submit','name'=>'submit','title'=>'Continue to Confirmation -->','value'=>'Continue to Confirmation -->'))?>
<?			}
		}
?>
		</div>
	</form>
<?	} ?>