<?
	include('_controller.php');
	
	
	function sitm() { 
		global $BF,$info,$directions,$data;

		if(!isset($_REQUEST['idLocation'])) { $_REQUEST['idLocation'] = ""; }
	
		// Getting the locations drop down
		$locations = db_query("SELECT L.ID, L.chrLocation
								FROM Locations AS L
								WHERE !L.bDeleted AND L.idEvent='". $_SESSION['idEvent'] ."' AND L.bStaffingEnabled 
								GROUP BY L.ID
								ORDER BY L.chrLocation ASC
							","getting locations");
		
		if($_REQUEST['idLocation'] != "") {
			// get list of AS_Shifts for this location
			$query = "SELECT ID, idLocation, DATE_FORMAT(dDate,'%W<br />%M %d, %Y') as dDate, DATE_FORMAT(tBegin, '%l:%i %p') as tBegin, DATE_FORMAT(tEnd, '%l:%i %p') as tEnd, dDate as dOrder, tBegin as tStartTime
				FROM Shifts
				WHERE !Shifts.bDeleted AND Shifts.idLocation = '" . $_REQUEST['idLocation'] . "'
				ORDER BY dOrder, tStartTime";
			$shift_result = db_query($query, 'get Shifts');
			
			$total_shifts = mysqli_num_rows($shift_result);
			
			$shifts = array();
			while ($row = mysqli_fetch_assoc($shift_result)) {
				$shifts[$row['ID']] = $row['dDate'].'<br />'.$row['tBegin'].' to '.$row['tEnd'];
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
				<div style='padding:5px 10px;<?=($_REQUEST['idLocation'] != '' ? ' border-bottom:1px solid #C0C1C2;' : '')?>'>
					<form name="idForm" method="get" action="" style='padding:0; margin:0;'>
						Location:&nbsp;
						<select name='idLocation' onchange='this.form.submit();'>
							<option value=''>- Select Location -</option>
<?
							while($row = mysqli_fetch_assoc($locations)) { 
?>
							<option value='<?=$row['ID']?>' <?=($row['ID']==$_REQUEST['idLocation']?' selected="selected"':'')?>><?=$row['chrLocation']?></option>
<?
							} 
?>
						</select>
						&nbsp;&nbsp;<input type='submit' name='go' value='Submit' />&nbsp;&nbsp;<input type='button' name='excel' value='Download matrix to excel' onclick='window.location="excel.php";' />		
					</form>
				</div>
<?
		if($_REQUEST['idLocation'] != '') {
			if($total_shifts > 0) {
?>
				<table class='matrix' cellpadding="0" cellspacing="0" border="0" style='width: 100%;'>
					<tr>
						<th class='blank' style='width:<?=$cell_width?>%;white-space:nowrap;'></th>
<?
					foreach($shifts AS $id => $display) { 
?>
						<th style='width:<?=$cell_width?>%; border-left:1px solid #C0C1C2;white-space:nowrap;background:#fff;padding:5px;'><?=$display?></th>
<?
					} 
		$count=1;
		foreach($stationresults AS $idStation => $value) {
?>
					</tr>
					<tr class="<?=(($count++%2)?'odd':'even')?>">	
						<td style='text-align:left; padding:5px;white-space:nowrap;background:#fff;border-top:1px solid #C0C1C2;'><?=$value['chrDisplay']?></td>
<?						
			foreach($shifts AS $k => $v) {
?>					
						<td style='<?=($stationresults[$idStation][$k] != '&lt;Empty&gt;' ? 'background:#EFEFC2;' : '')?>padding:5px;white-space:nowrap;text-align:center;border-left:1px solid #C0C1C2;border-top:1px solid #C0C1C2;'>
							<?=$stationresults[$idStation][$k]?>
						</td>
<?
			}
		}
		if($count==1) {
?>
					</tr>
					<tr>
						<td colspan='<?=count($shifts)+1?>' style='padding:5px 10px; text-align:center;border-top:1px solid #C0C1C2;'><em>No Stations found for this location</em></td>
					</tr>
<?
		}
?>
				</table>
<?
			} else { // No Shifts
?>
				<div style='padding:5px 10px; text-align:center;'><em>No shifts found for this location</em></div>
<?
			} // End No Shifts
		} // End location != ''
?>
<?
	}
?>