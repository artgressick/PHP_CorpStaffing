<?
	include('_controller.php');
		
	function sitm() { 
		global $BF,$directions,$totalerrors;

		if(!isset($_SESSION['ExpertiseUpdated']) && !isset($_SESSION['Expertise'])) {
			// does this person have anything already in the DB?
			$q = "SELECT idExpertise, idLevel FROM PersonExpertise WHERE idEvent='".$_SESSION['idEvent']."' AND idPerson='".$_SESSION['idPerson']."'";
			$results = db_query($q,"Getting Person Expertise");
			
			while ($row = mysqli_fetch_assoc($results)) {
				$_SESSION['Expertise'][$row['idExpertise']] = $row['idLevel'];
			}
		}	
		
		
?>

		<div class='header2'>My Schedule - Expertise</div>
		<form action="" method="post" id="idForm">
			<div class='directions2'><?=$directions?></div>
			<?=messages()?>
<?
			$q = "SELECT ID, chrExpertise 
				FROM EventExpertise EE 
				JOIN Expertise E ON EE.idExpertise=E.ID 
				WHERE !E.bDeleted AND EE.idEvent='".$_SESSION['idEvent']."'
				ORDER BY chrExpertise";
	
			$results = db_query($q,"Getting Expertise options for Event");

			$cols = 2; // how many columns do you usually want (May change depending on the amount of records to show)
			$rows = ceil(mysqli_num_rows($results) / $cols);	
			$count=0;
?>	
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td valign="top" width="<?=100/$cols?>%">
						<table cellpadding="3" cellspacing="0" border="0" style="width:100%; padding:5px;">
<?
				while ($row = mysqli_fetch_assoc($results)) {
					
					if($count++ >= $rows) {
						$count = 0;
?>
						</table>
					</td>
					<td width="<?=100/$cols?>%" valign="top">
						<table cellpadding="3" cellspacing="0" border="0" style="width:100%; padding:5px;">
<?
					}
	
					if(isset($_SESSION['Expertise'][$row['ID']])) {
?>
							<tr<?=($_SESSION['Expertise'][$row['ID']]=="" ? ' class="FormError"' : "")?>>
								<td style='white-space:nowrap; text-align:left;'>
									<?=form_checkbox(array('title'=>$row['chrExpertise'],'name'=>'idExpertise['.$row['ID'].']','value'=>$row['ID'],'checked'=>'true'))?>
									<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'chrExpertise['.$row['ID'].']','value'=>encode($row['chrExpertise'])))?>
								</td>
								<td style='white-space:nowrap; text-align:right;'>
<?
									$values = array(1 => 'Expert',2 => 'Intermediate',3 => 'Novice'); ?>
									<?=form_select($values,array('nocaption'=>'true','title'=>'Experience Level','value'=>$_SESSION['Expertise'][$row['ID']],'name'=>'idLevel'.$row['ID'],'caption'=>'-Experience Level-'))?>
								</td>
							</tr>
<?
					} else {
?>
							<tr>
								<td style='white-space:nowrap; text-align:left;'>
									<?=form_checkbox(array('title'=>$row['chrExpertise'],'name'=>'idExpertise['.$row['ID'].']','value'=>$row['ID']))?>
									<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'chrExpertise['.$row['ID'].']','value'=>encode($row['chrExpertise'])))?>
								</td>
								<td style='white-space:nowrap; text-align:right;'>
<?
									$values = array(1 => 'Expert',2 => 'Intermediate',3 => 'Novice'); ?>
									<?=form_select($values,array('nocaption'=>'true','title'=>'Experience Level','name'=>'idLevel'.$row['ID'],'caption'=>'-Experience Level-'))?>
								</td>
							</tr>
<?					
					}
				}
	
				if ($count==0) { 
?>			
							<tr>
								<td>No Expertise Options Available at this Time</td>
							</tr>
<?			
				}
?>
						</table>
					</td>
				</tr>
			</table>
			<div style="padding-top:15px;">
				<?=(isset($totalerrors) ? ' <div class="FormErrorCount">'.$totalerrors.' Error(s) Detected.</div>' : "" )?>
<?
			if($count!=0) {
				if (isset($_SESSION['Editing'])) { ?>
					<?=form_button(array('type'=>'submit','name'=>'submit','title'=>'Back to Confirmation','value'=>'Back to Confirmation'))?>
<?				} else { ?>
					<?=form_button(array('type'=>'submit','name'=>'submit','title'=>'Continue to Shifts -->','value'=>'Continue to Shifts -->'))?>
<?				}
			}
?>
			</div>
		</form>
<?	} ?>