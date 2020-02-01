<?
	include('_controller.php');
	
	
	
	function sitm() { 
		global $BF,$info,$expertise,$shifts;
		
		$Levels = array(1 => 'Expert',2 => 'Intermediate',3 => 'Novice');
?>
	<div class='header1' style='margin-top:-20px;'><?=$info['chrFirst']?> <?=$info['chrLast']?></div>
	<div id='innerBody' class='innerbody'>
		<hr />
		<div class='header3'>Expertise</div>
			<table class='List' style='width: 100%' cellpadding="0" cellspacing="0">
				<tr>
					<th>Expertise</th>
					<th>Level</th>
				</tr>
<?
		if(mysqli_num_rows($expertise) > 0) {
			$count=0;
			while($row = mysqli_fetch_assoc($expertise)) {
?>
				<tr class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>'>
					<td style='cursor: default;'><?=$row['chrExpertise']?></td>
					<td style='cursor: default;'><?=$Levels[$row['idLevel']]?></td>
				</tr>
				
<?				
			}
		} else {
?>
				<tr>
					<td colspan='2' style='text-align:center; height:20px;'>No Expertise Selected</td>
				</tr>
<?			
		}
?>		
			</table>
		<hr />
		<div class='header3'>Requested Schedule Options</div>
			<table class='List' style='width: 100%' cellpadding="0" cellspacing="0">
				<tr>
					<th>Location</th>
					<th>Shift</th>
				</tr>
<?
		if(mysqli_num_rows($shifts) > 0) {
			$count=0;
			while($row = mysqli_fetch_assoc($shifts)) {
?>
				<tr class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>'>
					<td style='cursor: default;'><?=$row['chrLocation']?></td>
					<td style='cursor: default;'><?=$row['chrShift']?></td>
				</tr>
				
<?				
			}
		} else {
?>
				<tr>
					<td colspan='2' style='text-align:center; height:20px;'>No Shifts Selected</td>
				</tr>
<?			
		}
?>		
			</table>
	</div>



<?	} ?>