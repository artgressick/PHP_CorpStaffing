<?
	include('_controller.php');
		
	function sitm() { 
		global $BF,$directions;
		
		if(!isset($_POST['idShifts'])) { $_POST['idShifts'] = array(); }
		
		if(!isset($_SESSION['ShiftsUpdated']) && !isset($_SESSION['Shifts'])) {
			// does this person have anything already in the DB?
			$q = "SELECT idShift FROM Schedule_Requested WHERE idEvent='".$_SESSION['idEvent']."' AND idPerson='".$_SESSION['idPerson']."'";
			$results = db_query($q,"Getting Person Expertise");
			
			while ($row = mysqli_fetch_assoc($results)) {
				$_SESSION['Shifts'][$row['idShift']] = $row['idShift'];
			}
		}
	
?>
	<div class='header2'>My Schedule - Confirmation</div>
	<form action="" method="post" id="idForm">
		<div class='directions2'><?=$directions?></div>
		<?=messages()?>
<?
	$q = "SELECT ID, chrExpertise 
			FROM EventExpertise EE 
			JOIN Expertise E ON EE.idExpertise=E.ID 
			WHERE !E.bDeleted AND EE.idEvent='".$_SESSION['idEvent']."' AND ID IN (".implode(",",array_keys($_SESSION['Expertise'])).") 
			ORDER BY chrExpertise";

	$results = db_query($q,"Getting Expertise options for Event");

	$cols = 2; // how many columns do you usually want (May change depending on the amount of records to show)
	$rows = ceil(mysqli_num_rows($results) / $cols);	
	$count=0;
?>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="LocationHeader">Expertise</td>
				<td class="LocationHeader" align="right"><input type='button' name="edit" value='Edit/Change' onclick="javascript:location.href='step1.php'" /></td>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:2px solid #CCC;">
			<tr>
				<td valign="top" width="<?=100/$cols?>%">
					<table cellpadding="3" cellspacing="0" border="0" style="padding:5px;">
<?
			while ($row = mysqli_fetch_assoc($results)) {
				
				if($count++ >= $rows) {
					$count = 0;
?>
					</table>
				</td>
				<td width="<?=100/$cols?>%" valign="top">
					<table cellpadding="3" cellspacing="0" border="0" style="padding:5px;">
<?
				}
?>
						<tr>
							<td><?=$row['chrExpertise']?></td>
							<td><strong>Level:</strong> <?=($_SESSION['Expertise'][$row['ID']]==1 ? "Expert" : ($_SESSION['Expertise'][$row['ID']]==2 ? "Intermediate" : "Novice"))?> </td>
						</tr>
<?			
			}
?>
					</table>
				</td>
			</tr>
		</table>
		<br />
<?

	$q = "SELECT S.ID, S.dDate, S.tBegin, S.tEnd, L.chrLocation, S.idLocation
			FROM Locations L 
			JOIN Shifts S ON S.idLocation = L.ID 
			WHERE !L.bDeleted AND !S.bDeleted AND L.idEvent='".$_SESSION['idEvent']."' AND S.ID IN (". implode(",",array_keys($_SESSION['Shifts'])) .") 
			ORDER BY chrLocation, S.dDate, S.tBegin
			";
			
	$results = db_query($q, "Getting all Shifts for Event");
?>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="LocationHeader">Shifts</td>
				<td class="LocationHeader" align="right"><input type='button' name="edit" value='Edit/Change' onclick="javascript:location.href='step2.php'" /></td>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:2px solid #CCC;">
<?
	while($row = mysqli_fetch_assoc($results)) {
?>
			<tr>
				<td class="ShiftRow"><strong>Location:</strong> <?=$row['chrLocation']?> <strong>on</strong> <?=date('l, F jS, Y',strtotime($row['dDate']))?> <strong>from</strong> <?=date('g:i a',strtotime($row['tBegin']))?> <strong>to</strong> <?=date('g:i a',strtotime($row['tEnd']))?></td>
			</tr>
<?
	}
?>
			
		</table>

		<div style="padding-top:15px;">
			<?=(isset($totalerrors) ? ' <div class="FormErrorCount">'.$totalerrors.' Error(s) Detected.</div>' : "" )?>
			<?=form_button(array('type'=>'submit','name'=>'submit','title'=>'Submit / Save','value'=>'Submit / Save'))?>
		</div>
	</form>
<?	} ?>