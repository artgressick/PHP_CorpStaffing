	<?
	include('_controller.php');
	
	
	function sitm() { 
		global $BF,$info,$directions,$data;
?>
	<div class='innerbody'>
		<?=messages()?>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<table width="100%" cellpadding="0" cellspacing="0" style="padding-top:10px;">
				<tr>
					<td>
						<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tcleft">
									<div class='header3'>Station Information <span class='FormRequired'>(All Required)</span></div>
									<table cellpadding="5" cellspacing="0" style='background:#EEE; border:1px solid #666;'>
										<tr>
											<td width="75" class="FormName">Location:</td> 
<?											$Locations = db_query("SELECT ID, chrLocation AS chrRecord FROM Locations WHERE !bDeleted AND bStaffingEnabled AND idEvent='".$_SESSION['idEvent']."' ORDER BY chrLocation","Getting Locations"); ?>
											<td><?=form_select($Locations,array('name'=>'idLocation','caption'=>'-Select Location-','required'=>'true','nocaption'=>'true','value'=>$data['idLocation'],'extra'=>'onchange="javascript:document.getElementById(\'idForm\').submit();"'))?></td>
										</tr>
										<tr>
											<td width="75" class="FormName">Shift:</td> 
<?											$Shifts = db_query("SELECT ID, CONCAT(DATE_FORMAT(dDate,'%a, %b %e, %Y'),' from ',DATE_FORMAT(tBegin,'%l:%i %p'),' to ',DATE_FORMAT(tEnd,'%l:%i %p')) as chrRecord FROM Shifts WHERE !bDeleted AND idLocation='".$data['idLocation']."' ORDER by dDate, tBegin, tEnd","Getting Shifts"); ?>
											<td><?=form_select($Shifts,array('name'=>'idShift','caption'=>'-Select Shift-','required'=>'true','nocaption'=>'true','value'=>$data['idShift'],'extra'=>'onchange="javascript:document.getElementById(\'idForm\').submit();"'))?></td>
										</tr>
										<tr>
											<td width="75" class="FormName">Station:</td> 
<?											$Stations = db_query("SELECT ID, CONCAT(chrStation,' (',chrNumber,')') as chrRecord FROM Stations WHERE !bDeleted AND idLocation='".$data['idLocation']."' ORDER BY chrRecord","Getting Stations"); ?>
											<td><?=form_select($Stations,array('name'=>'idStation','caption'=>'-Select Station-','required'=>'true','nocaption'=>'true','value'=>$data['idStation'],'extra'=>'onchange="javascript:document.getElementById(\'idForm\').submit();"'))?></td>
										</tr>
									</table>
								</td>
								<td class="tcgutter"></td>
								<td class="tcright">
									<div class='header3''>Currently Assigned</div>
									<table class='List' style='width: 100%' cellpadding="0" cellspacing="0">
										<tr>
											<th style='width:20px;'>Info</th>
											<th>Last Name</th>
											<th>First Name</th>
										</tr>
<?
								$q = "SELECT S.idPerson, People.chrKEY, People.chrFirst, People.chrLast, People.idPersonStatus
										FROM Schedule AS S
										LEFT JOIN People ON S.idPerson=People.ID
										WHERE S.idShift='".$data['idShift']."' AND S.idStation='".$data['idStation']."' AND S.idLocation='".$data['idLocation']."' AND S.idEvent='".$_SESSION['idEvent']."' 
										ORDER BY chrLast, chrFirst		
									 ";

								$scheduled = db_query($q,"Getting Scheduled Staffers",1);
								$count=0;
								if($scheduled['idPerson'] != "" && ($scheduled['idPerson'] == 0 || $scheduled['idPersonStatus'] == 3)) {
									if($scheduled['idPerson'] != 0) {
?>
										<tr class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>'>
											<td><?=miniPopup('popupinfo.php?key='.$scheduled['chrKEY'],'Info')?></td>
											<td style='cursor: default;'><?=$scheduled['chrLast']?></td>
											<td style='cursor: default;'><?=$scheduled['chrFirst']?></td>
										</tr>
<?
									} else {
?>
										<tr class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>'>
											<td>&nbsp;</td>
											<td colspan='2' style='cursor: default;'>No Staffer Needed</td>
										</tr>
<?
									}
								} else {
?>
										<tr>
											<td colspan='3' style='text-align:center; height:20px;vertical-align:middle;'>No Staffer Scheduled</td>
										</tr>
<?							
								}
?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<div class='header3' style='padding-top:10px;'>Staffers that have requested this shift.</div>
<?
	if($data['idStation'] != "" && $data['idShift'] != "") {
?>

						<table id='PersonList' class='Tabs' style='margin-top: 1em; margin-bottom: -3px; width: 100%;'>
							<tr>
<?						if(access_check('1,2')) { ?>
								<td class='Current'><a href='assignshift.php?d=<?=$_REQUEST['d']?>' style='padding-left:5px;padding-right:5px;'>Standard</a></td>
								<td class=''><a href='override.php?d=<?=$_REQUEST['d']?>' style='padding-left:5px;padding-right:5px;'>Override</a></td>
<?						} ?>
								<td style='width: 100%; text-align: right; padding:2px 0 2px 0;'>
									<input type='text' name='chrSearch' value='<?=(isset($_POST['chrSearch']) ? $_POST['chrSearch'] : '')?>' />
									<input type='submit' value='Name Search' />
								</td>
							</tr>
						</table>
						
						<table class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
							<tr>
								<th style='width:20px;'>&nbsp;</th>
								<th style='width:20px;'>Info</th>
								<th class='ListHeadSortOn sorttable_sorted' >Last Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
								<th>First Name&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
								<th>Assigned&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							</tr>
<?
/* // The following query is for only allowing staffer being scheduled once per day
 		$q = "SELECT People.ID, People.chrKEY, People.chrFirst, People.chrLast,Shifts.dDate,
				(SELECT count(ID) FROM Schedule WHERE idPerson=People.ID) as intAssigned
			FROM People
			JOIN Schedule_Requested AS SR ON SR.idPerson=People.ID AND SR.idShift='".$data['idShift']."' AND SR.idEvent='".$_SESSION['idEvent']."'
			JOIN Shifts ON Shifts.ID=SR.idShift
			WHERE People.idPersonStatus=3
			AND People.ID NOT IN (SELECT Schedule.idPerson FROM Schedule JOIN Shifts as S ON S.ID=Schedule.idShift AND S.dDate IN 
				(SELECT dDate from Shifts as Sh WHERE Sh.ID='".$data['idShift']."')
			)";
*/
	// The following query is for allowing staffer to scheduled multiple times per day
		$shift_times = db_query("SELECT tBegin,tEnd FROM Shifts WHERE ID=".$data['idShift'],"getting shift times",1);	

		$q = "SELECT People.ID, People.chrKEY, People.chrFirst, People.chrLast,Shifts.dDate,
				(SELECT count(ID) FROM Schedule WHERE idPerson=People.ID) as intAssigned
			FROM People
			JOIN Schedule_Requested AS SR ON SR.idPerson=People.ID AND SR.idShift='".$data['idShift']."' AND SR.idEvent='".$_SESSION['idEvent']."'
			JOIN Shifts ON Shifts.ID=SR.idShift
			WHERE People.idPersonStatus=3
			AND People.ID NOT IN (SELECT Schedule.idPerson FROM Schedule JOIN Shifts as S ON S.ID=Schedule.idShift 
				AND S.dDate IN (SELECT dDate from Shifts as Sh WHERE Sh.ID='".$data['idShift']."')
				AND (S.tBegin BETWEEN '". $shift_times['tBegin'] ."' AND '". $shift_times['tEnd'] ."'
				  OR S.tEnd BETWEEN '". $shift_times['tBegin'] ."' AND '". $shift_times['tEnd'] ."')
			)";
// 				AND S.ID!='".$data['idShift']."'



		if(isset($_POST['chrSearch'])) {
			$q .= " AND ((chrFirst LIKE '%".$_POST['chrSearch']."%') OR (chrLast LIKE '%".$_POST['chrSearch']."%') OR (chrEmail LIKE '%".$_POST['chrSearch']."%'))";
		}
		$q .= "ORDER BY People.chrLast, People.chrFirst";

						$results = db_query($q,"Getting Eligible Staffers");
						$count=0;
						if(mysqli_num_rows($results) > 0) {
							while($row = mysqli_fetch_assoc($results)) {
?>
							<tr id='PersonListtr<?=$row['ID']?>' class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>'>
								<td style='cursor: default;'><input style='cursor: pointer;' type='radio' name='idPerson' value='<?=$row['ID']?>' id='idPerson<?=$row['ID']?>' title='<?=$row['chrFirst'].' '.$row['chrLast']?>' <?=($data['idPerson']==$row['ID']?'checked="checked" ':($scheduled['idPerson']==$row['ID']?'checked="checked" ':''))?>/></td>
								<td><?=miniPopup('popupinfo.php?key='.$row['chrKEY'],'Info')?></td>
								<td style='cursor: default;'><label for='idPerson<?=$row['ID']?>' style='cursor: pointer;'><?=$row['chrLast']?></label></td>
								<td style='cursor: default;'><label for='idPerson<?=$row['ID']?>' style='cursor: pointer;'><?=$row['chrFirst']?></label></td>
								<td style='cursor: default;'><label for='idPerson<?=$row['ID']?>' style='cursor: pointer;'><?=$row['intAssigned']?></label></td>
							</tr>
<?
							}
						} else {
?>
							<tr>
								<td colspan='5' style='text-align:center; height:20px;vertical-align:middle;'>No Eligible Staffers to Select</td>
							</tr>
<?							
						}
?>
							<tfoot>
							<tr class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>'>
								<td style='cursor: default;'><input type='radio' name='idPerson' value='0' id='idPerson0' title='Staffer Not Needed' <?=($data['idPerson']=='0'?'checked="checked" ':($scheduled['idPerson']=='0'?'checked="checked" ':''))?>/></td>
								<td style='cursor: default;'>&nbsp;</td>
								<td colspan='3' style='cursor: default;'><label for='idPerson0' style='cursor: pointer;'>Staffer Not Needed</label></td>
							</tr>
							</tfoot>
						</table>
<?
	} else {
?>
					<div class='header4'>You must select a Location, Shift, and Station to view Eligible Staffers</div>
<?	
	}
?>
					</td>
				</tr>
			</table>
			<div class='FormButtons'>
<?
	if($data['idStation'] != "" && $data['idShift'] != "") {
?>

				<?=form_button(array('type'=>'submit','value'=>'Add And Return','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'assignshift.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Go to Matrix','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
<?
	}
?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'d','value'=>base64_encode('idLocation='.$data['idLocation'].'&idShift='.$data['idShift'].'&idStation='.$data['idStation'])))?>
			</div>
		</form>
		<iframe id='miniPopupWindow' style='position: absolute; display: none; background: #ccc; border: 1px solid gray; width:400px; height:350px;'></iframe>

	</div>

<?
	}
?>