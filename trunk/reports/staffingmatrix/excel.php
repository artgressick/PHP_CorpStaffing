<?php
	require_once "Spreadsheet/Excel/Writer.php";
	
	$BF = "../../";
	$NON_HTML_PAGE=1;
	require($BF.'_lib.php');
		
	
	// create workbook
	$workbook = new Spreadsheet_Excel_Writer();
	
	$event = decode(str_replace(' ','_',$_SESSION['chrEvent']));
	$now = date('m-d-Y');
	// send the headers with this name
	$workbook->send($event.'-Matrix-'.$now.'.xls');	
	
	// create format for column headers
	$format_column_header =& $workbook->addFormat();
	$format_column_header->setBold();
	$format_column_header->setSize(10);
	$format_column_header->setAlign('left');

	// create format for column headers
	$format_column_header2 =& $workbook->addFormat();
	$format_column_header2->setBold();
	$format_column_header2->setSize(10);
	$format_column_header2->setAlign('center');
	
	$format_column_header3 =& $workbook->addFormat();
	$format_column_header3->setItalic();
	$format_column_header3->setSize(10);
	$format_column_header3->setAlign('left');
	
	// create data format
	$format_data =& $workbook->addFormat();
	$format_data->setSize(10);
	$format_data->setAlign('center');

	$q = "SELECT ID, chrLocation
			FROM Locations 
			WHERE !bDeleted AND Locations.idEvent='". $_SESSION['idEvent'] ."' AND bStaffingEnabled 
			ORDER BY chrLocation ASC";

	$location_results = db_query($q,"Getting Locations");
	$locations = array();
	while($row = mysqli_fetch_assoc($location_results)) {
		$locations[$row['ID']] = $row['chrLocation'];	
	}
	$q = "SELECT ID, idLocation, DATE_FORMAT(dDate,'%a. %b. %e, %Y') as dDate, DATE_FORMAT(tBegin, '%l:%i %p') as tBegin, DATE_FORMAT(tEnd, '%l:%i %p') as tEnd, dDate as dOrder, tBegin as tStartTime
		FROM Shifts
		WHERE !Shifts.bDeleted
		ORDER BY dOrder, tStartTime";
	$shift_result = db_query($q, 'get Shifts');
	
	$shifts = array();
	while ($row = mysqli_fetch_assoc($shift_result)) {
		$shifts[$row['idLocation']][$row['ID']] = $row['dDate'].' - '.$row['tBegin'].' to '.$row['tEnd'];
	}

	$q = "SELECT CONCAT(chrNumber,' - ',chrStation) as chrDisplay,chrFirst,chrLast,idPerson,dDate,tBegin,Shifts.idLocation,Stations.ID as idStation,Shifts.ID as idShift
		FROM Stations
		LEFT JOIN Shifts USING (idLocation) 
		LEFT JOIN Schedule ON Schedule.idStation=Stations.ID && Schedule.idShift=Shifts.ID 
		LEFT JOIN People ON Schedule.idPerson=People.ID
		WHERE !Stations.bDeleted
		ORDER BY chrNumber, chrStation, idStation, Shifts.dDate, Shifts.tBegin
	";
	$data_result = db_query($q,'getting data');
	
	$stationresults = array();
	while($row = mysqli_fetch_assoc($data_result)) {

		$stationresults[$row['idLocation']][$row['idStation']]['chrDisplay'] = $row['chrDisplay'];	
		if($row['idPerson'] == '') { 
			$stationresults[$row['idLocation']][$row['idStation']][$row['idShift']] = '<Empty>';
		} else if($row['idPerson'] == '0') {
			$stationresults[$row['idLocation']][$row['idStation']][$row['idShift']] = '-Not Required-';
		} else {
			$stationresults[$row['idLocation']][$row['idStation']][$row['idShift']] = $row['chrFirst'] . ' ' . $row['chrLast'];
		}
	}

	foreach($locations AS $idLocation => $chrLocation) {

		$worksheet =& $workbook->addWorksheet(decode($chrLocation));
		$worksheet->hideGridLines();
		$column_num = 0;
		$row_num = 0;
		if(isset($shifts[$idLocation])) {
			$worksheet->setColumn($column_num, $column_num, 40);
			$worksheet->write($row_num, $column_num, ' ', $format_column_header2);
			$column_num++;
		
			foreach($shifts[$idLocation] AS $shift) {
				$worksheet->setColumn($column_num, $column_num, 50);
				$worksheet->write($row_num, $column_num, $shift, $format_column_header2);
				$column_num++;
			}
			if(isset($stationresults[$idLocation])) {
				foreach($stationresults[$idLocation] AS $k => $v) {
					$column_num = 0;
					$row_num++;
					$worksheet->write($row_num, $column_num, decode($stationresults[$idLocation][$k]['chrDisplay']), $format_column_header);
					$column_num++;
					foreach($shifts[$idLocation] AS $s => $val) {
						$worksheet->write($row_num, $column_num, decode($stationresults[$idLocation][$k][$s]), $format_data);
						$column_num++;
					}
				}
			} else {
				$column_num = 0;
				$row_num++;
				$worksheet->write($row_num, $column_num, 'No Stations found for this location', $format_column_header3);
			}
		} else {
			$column_num = 0;
			$row_num++;
			$worksheet->setColumn($column_num, $column_num, 40);
			$worksheet->write($row_num, $column_num, 'No Shifts found for this location', $format_column_header3);
		}
	}

	$workbook->close();
?>