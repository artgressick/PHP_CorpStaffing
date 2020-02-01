<?
	include('_controller.php');
	
	
	function sitm() { 
		global $BF,$info,$directions,$data;

		if(!isset($_REQUEST['idLocation'])) { $_REQUEST['idLocation'] = ""; }
//		if(!isset($_REQUEST['idZone'])) { $_REQUEST['idZone'] = ""; }
	
		// Getting the locations drop down
		$locations = db_query("SELECT ID, chrLocation
			FROM Locations 
			WHERE !bDeleted AND Locations.idEvent='". $_SESSION['idEvent'] ."' AND bStaffingEnabled 
			ORDER BY chrLocation ASC
		","getting locations");
		
		// Getting zones
//		if($_SESSION['bZoneManager'] == 1) { $str = implode(',',$_SESSION['idZones']); }
		$zones = db_query("SELECT ID, chrZone
				FROM Zones
				WHERE !bDeleted AND idEvent=". $_SESSION['idEvent'] ."
				
				ORDER BY chrZone"
		,"Getting zones");
		
		
		if($_REQUEST['idLocation'] != "") {
						$query = "SELECT ID, idLocation, DATE_FORMAT(dDate,'%W<br />%M %d, %Y') as dDate, DATE_FORMAT(tBegin, '%l:%i %p') as tBegin, DATE_FORMAT(tEnd, '%l:%i %p') as tEnd, dDate as dOrder, tBegin as tStartTime
				FROM Shifts
				WHERE !Shifts.bDeleted AND Shifts.idLocation = '" . $_REQUEST['idLocation'] . "'
				ORDER BY dOrder, tStartTime";
			$shift_result = db_query($query, 'get Shifts');
			
			$total_shifts = mysqli_num_rows($shift_result);
			
			$shifts = array();
			while ($row = mysqli_fetch_assoc($shift_result)) {
				$shifts[$row['ID']] = $row['dDate'].'<br />'.$row['tBegin'].'<br />to<br />'.$row['tEnd'];
			}
			$cell_width = floor(100 / ($total_shifts + 1));

			$q = "SELECT CONCAT(chrNumber,' - ',chrStation) as chrDisplay,chrFirst,chrLast,idPerson,dDate,tBegin,Shifts.idLocation,Stations.ID as idStation,Shifts.ID as idShift
				FROM Stations
				LEFT JOIN Shifts USING (idLocation) 
				LEFT JOIN Schedule ON Schedule.idStation=Stations.ID && Schedule.idShift=Shifts.ID 
				LEFT JOIN People ON Schedule.idPerson=People.ID
				WHERE !Stations.bDeleted AND Stations.idLocation='" . $_REQUEST['idLocation'] . "'
				ORDER BY chrNumber, chrStation, idStation, Shifts.dDate, Shifts.tBegin
			";
			$data_result = db_query($q,'getting data');
			
			$stationresults = array();
			$check = '';
			while($row = mysqli_fetch_assoc($data_result)) {
				if($check != $row['idStation']) {
					$check = $row['idStation'];
					$stationresults[$row['idStation']]['chrDisplay'] = $row['chrDisplay'];	
				}
				
				if($row['idPerson'] == '') { 
					$stationresults[$row['idStation']][$row['idShift']] = '&lt;Empty&gt;';
				} else if($row['idPerson'] == '0') {
					$stationresults[$row['idStation']][$row['idShift']] = '-Not&nbsp;Required-';
				} else {
					$stationresults[$row['idStation']][$row['idShift']] = $row['chrFirst'] . ' ' . $row['chrLast'];
				}
			}
		}
?>
	<form name="locationform" method="get" action="">
	<table class='FilterBar' cellpadding="0" cellspacing="0" border="0" style='padding:10px;'>
		<tr>
			<td>
				<span style='padding: 2px 2px 0 0;'>Sort by</span> <select name="idLocation" onchange='this.form.submit();'>
					<option value=''>- All Locations -</option>
<?
				while($row = mysqli_fetch_assoc($locations)) { 
?>
					<option value='<?=$row['ID']?>' <?=($row['ID']==$_REQUEST['idLocation']?' selected="selected"':'')?>><?=$row['chrLocation']?></option>
<?
				}
?>
				</select>
<?
/*
				<span style='padding: 2px 2px 0 10px;'>Sort by</span> <select name="idZone" onchange='this.form.submit();'>
					<option value=''>- All Zones -</option>
<?	while($row = mysqli_fetch_assoc($zones)) { ?>
					<option value='<?=$row['ID']?>' <?=($row['ID']==$_REQUEST['idZone']?' selected="selected"':'')?>><?=$row['chrZone']?></option>
<?	} ?>
				</select>
*/
?>				
			</td>
			<td class='Right' style='padding-left:10px;'><input type='button' value='Filter' onclick='document.form.submit()' /></td>
		</tr>
	</table>

	</form>

<?
	if($_REQUEST['idLocation'] != '') { 
?>
	<div style='padding:10px; padding-top:0;'>
		<input type='button' value='Show People' onclick='innerPopup();revertPopup();innerPopup();' />  
	</div>

	<div id='overlaypage' class='overlaypage'>
		<div id='gray' class='gray'></div>
			<div class='message'>
				<table cellspacing="0" cellpadding="0" align="center" style="width: 100%; width: 926px; margin: 10px auto 0;">
					<tr>
						<td style='width: 926px;'><img src="<?=$BF?>images/popup-top.png" /></td>
					</tr>
					<tr>
						<td background="<?=$BF?>images/popup-middle.png" bgcolor="#FFFFFF" style='width: 13px; padding:10px;'>
							<input type='button' value='Close' onclick='revertPopup();' />
<?
						if($total_shifts > 0) {
?>
							<table class='matrix' cellpadding="0" cellspacing="0" border="0" style='width: 100%;'>
								<tr>
									<th class='blank' style='width: <?=$cell_width?>%'></th>
<?
								foreach($shifts AS $id => $display) { 
?>
									<th style='width: <?=$cell_width?>%'><?=$display?></th>
<?
								}
							$count=1;
							foreach($stationresults AS $idStation => $value) {
?>
								</tr>
								<tr class="<?=(($count++%2)?'odd':'even')?>">	
									<td class='left' style='padding: 2px 0px 0 5px;'><?=$value['chrDisplay']?></td>
<?
								foreach($shifts AS $k => $v) {
									$link = base64_encode("idLocation=".$_REQUEST['idLocation']."&idStation=".$idStation."&idShift=".$k);
?>
									<td class='<?=($stationresults[$idStation][$k] != '&lt;Empty&gt;' ? 'staffed' : '')?>' onclick='location.href=href="assignshift.php?d=<?=$link?>"' style='cursor: pointer;'>
										<a href="assignshift.php?d=<?=$link?>"><?=$stationresults[$idStation][$k]?></a>
									</td>
<?
								}
							} 	
							if($count == 1) {
?>
								</tr>
								<tr>
									<td colspan='<?=$total_shifts+1?>' style='text-align: center; padding: 2px 2px 2px 10px;'><em>No Stations found for this location.</em></td>
<?
							}
?>
	
								</tr>
							</table>
<?
						} else { // No Shifts
?>
							<div style='padding:5px 10px; text-align:center;'><em>No shifts found for this location</em></div>
<?
						} // End No Shifts
?>
						</td>
					</tr>
					<tr>
						<td style='width: 926px;'><img src="<?=$BF?>images/popup-bottom.png" /></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
<?
	} // end of "if idLocation"
	}
?>