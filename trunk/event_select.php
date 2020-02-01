<?
	function litm() { 
		global $BF,$title;
?>
		<form id="form1" name="form1" method="post" action="" style='padding:0;margin:0;'>
			<div class='innerbody' style='padding-left:15px;'>
				<div>
<?
				if($_SESSION['bAdmin']==1) {
				$q = "SELECT ID, chrEvent AS chrRecord, IF(dEnd < NOW(), 'Archived','Current') as optGroup
							FROM Events
							WHERE !bDeleted
							ORDER BY dBegin DESC, chrRecord, dEnd DESC";	
				} else {
					$q = "
						SELECT E.ID, E.chrEvent as chrRecord
						FROM Events AS E
						JOIN People AS P ON P.ID='".$_SESSION['idPerson']."'AND !P.bDeleted
						LEFT JOIN ShowManagers AS SM ON !SM.bDeleted AND SM.idPerson=P.ID AND SM.idEvent = E.ID
						WHERE !E.bDeleted  
							AND E.dEnd > NOW() 
							AND (P.bAdmin 
								OR (SM.ID > 0 AND E.dBegin <= NOW()) 
								OR (E.idStaffingStatus != 1 AND E.dBegin <= NOW())
							)
						GROUP BY E.ID
						ORDER BY E.chrEvent
					";
				}
					$events = db_query($q,"getting results");
?>
					<?=form_select($events,array('caption'=>'Event','required'=>'true','name'=>'idEvent'))?>
				</div>
				<div style="padding:10px 0 10px;">
					<input type="submit" name="Submit" value="Submit" />
				</div>
			</div>
		</form>
<?	} ?>