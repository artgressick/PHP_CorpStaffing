<?php
	include('_controller.php');

	function sitm() {
		global $BF;
		# Lets get the Landing Page Information
			$landingPage = db_query("SELECT txtHTML FROM LandingPage WHERE idEvent='".$_SESSION['idEvent']."'","Getting Landing Page Info",1);
		if($landingPage['txtHTML'] != '') {
?>
										<div class='innerbody'><?=decode($landingPage['txtHTML'])?></div>
<?
		}
		
		
		$q = "SELECT SCH.ID, L.chrLocation, CONCAT(S.chrStation,' (',S.chrNumber,')') AS chrStation, SH.dDate, SH.tBegin, SH.tEnd
			  FROM Schedule AS SCH
			  JOIN Locations AS L ON SCH.idLocation AND L.idEvent='".$_SESSION['idEvent']."'
			  JOIN Stations AS S ON S.idLocation=L.ID AND SCH.idStation=S.ID
			  JOIN Shifts AS SH ON SH.idLocation=L.ID AND SCH.idShift=SH.ID
			  WHERE SCH.idPerson='".$_SESSION['idPerson']."'
			  ORDER BY dDate,tBegin,tEnd
			";
				
		$schedule = db_query($q,"Getting Users Schedule");
		$schedule_display=false;	
		if(mysqli_num_rows($schedule) > 0) {
			$schedule_display=true;
			
			if($landingPage['txtHTML'] != '') {
?>
       								</td>
				          		</tr>
				       		</table>
							<table cellspacing="0" cellpadding="0" border="0" width="100%" style="padding:10px;padding-right:5px;">
								<tr>
									<td style='width:4px;' align="left" background="<?=$BF?>images/wire-header-bg.gif"><img src="<?=$BF?>images/wire-header-left.gif"></td>
									<td background="<?=$BF?>images/wire-header-bg.gif" style='font-weight:bold; color:#282828;font-size:12px;padding-left:5px;'>
										My schedule for <?=$_SESSION['chrEvent']?>
									</td>
									<td style='width:4px;' align="right" background="<?=$BF?>images/wire-header-bg.gif"><img src="<?=$BF?>images/wire-header-right.gif"></td>
								</tr>
								<tr>
				              		<td style="border:solid 1px #c0c1c2;" colspan='3'>
<?
			}
?>	
				              			<div class='innerbody'>
										<table width="100%" class="" id="Assigned" cellpadding="0" cellspacing="0" style='padding-top:5px;'>
											<tr>
												<td>
													<div class='header2'>Assigned Schedule</div>
													<div class='directions'>Below is what you are currently assigned to work.</div>
												
													<table class='List' style='width:100%;' cellpadding="0" cellspacing="0">
														<thead>
															<tr>
																<th style='white-space: nowrap;'>Location</th>
																<th style='white-space: nowrap;'>Station</th>
																<th style='white-space: nowrap;'>Date</th>
																<th style='white-space: nowrap;'>Start Time</th>
																<th style='white-space: nowrap;'>End Time</th>
															</tr>
														</thead>
														<tbody>
<?
					$count=0;
					while($row = mysqli_fetch_assoc($schedule)) {
?>
															<tr id='Schedule<?=$row['ID']?>' class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>' onmouseover='RowHighlight("Schedule<?=$row['ID']?>");' onmouseout='UnRowHighlight("Schedule<?=$row['ID']?>");'>
																<td style='cursor: default;'><?=$row['chrLocation']?></td>
																<td style='cursor: default;'><?=$row['chrStation']?></td>
																<td style='cursor: default;'><?=date('M. jS, Y',strtotime($row['dDate']))?></td>
																<td style='cursor: default;'><?=date('g:i a',strtotime($row['tBegin']))?></td>
																<td style='cursor: default;'><?=date('g:i a',strtotime($row['tEnd']))?></td>
															</tr>					
<?
					}
?>
														</tbody>
													</table>
												</td>
											</tr>
										</table>
<?
		}
?>		
<?
		$q = "SELECT SH.ID, L.chrLocation, SH.dDate, SH.tBegin, SH.tEnd
			  FROM Schedule_Requested AS SR
			  JOIN Shifts AS SH ON SR.idShift=SH.ID
			  JOIN Locations AS L ON SH.idLocation=L.ID AND L.idEvent='".$_SESSION['idEvent']."'
			  WHERE SR.idPerson='".$_SESSION['idPerson']."'
			  ORDER BY dDate,tBegin,tEnd
			";
				
		$schedule = db_query($q,"Getting Users Schedule");
	
		if(mysqli_num_rows($schedule) > 0) {
			if(!$schedule_display && $landingPage['txtHTML'] != '') {
?>
       								</td>
				          		</tr>
				       		</table>
							<table cellspacing="0" cellpadding="0" border="0" width="100%" style="padding:10px;padding-right:5px;">
								<tr>
									<td style='width:4px;' align="left" background="<?=$BF?>images/wire-header-bg.gif"><img src="<?=$BF?>images/wire-header-left.gif"></td>
									<td background="<?=$BF?>images/wire-header-bg.gif" style='font-weight:bold; color:#282828;font-size:12px;padding-left:5px;'>
										My schedule for <?=$_SESSION['chrEvent']?>
									</td>
									<td style='width:4px;' align="right" background="<?=$BF?>images/wire-header-bg.gif"><img src="<?=$BF?>images/wire-header-right.gif"></td>
								</tr>
								<tr>
				              		<td style="border:solid 1px #c0c1c2;" colspan='3'>
				              			<div class='innerbody'>
<?
			}
?>	
										<table width="100%" class="" id="Requested" cellpadding="0" cellspacing="0"<?=($schedule_display?' style="padding-top:10px;"':'')?>>
											<tr>
												<td>
													<div class='header2'>Requested Schedule</div>
													<div class='directions'>Below is what dates/times you have requested to work.</div>
												
													<table class='List' style='width:100%;' cellpadding="0" cellspacing="0">
														<thead>
															<tr>
																<th style='white-space: nowrap;'>Location</th>
																<th style='white-space: nowrap;'>Date</th>
																<th style='white-space: nowrap;'>Start Time</th>
																<th style='white-space: nowrap;'>End Time</th>
															</tr>
														</thead>
														<tbody>
<?
					$count=0;
					while($row = mysqli_fetch_assoc($schedule)) {
?>
															<tr id='Requested<?=$row['ID']?>' class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>' onmouseover='RowHighlight("Requested<?=$row['ID']?>");' onmouseout='UnRowHighlight("Requested<?=$row['ID']?>");'>
																<td style='cursor: default;'><?=$row['chrLocation']?></td>
																<td style='cursor: default;'><?=date('M. jS, Y',strtotime($row['dDate']))?></td>
																<td style='cursor: default;'><?=date('g:i a',strtotime($row['tBegin']))?></td>
																<td style='cursor: default;'><?=date('g:i a',strtotime($row['tEnd']))?></td>
															</tr>					
<?
					}
?>
														</tbody>
													</table>
												</td>
											</tr>
										</table>
<?
		}
?>		
									</div>
<?	} ?>