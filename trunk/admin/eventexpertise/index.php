<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results,$info;
?>
		<form action="" method="post" id="idForm">
<?
		$events = db_query("SELECT ID, chrEvent AS chrRecord, IF(dEnd < NOW(), 'Archived','Current') as optGroup
							FROM Events
							WHERE !bDeleted
							ORDER BY dBegin DESC, chrRecord, dEnd DESC","Getting all Events");
?>
		<div style='padding:10px 0 10px 10px;background:#C8C9CA;font-weight:bold;'>
			Choose an Event: <?=form_select($events,array('nocaption'=>'true','caption'=>'-Select Event-','required'=>'true','name'=>'idEvent','value'=>$info['idEvent'],'extra'=>'onchange="javascript:document.getElementById(\'idForm\').submit();"'))?>
		</div>
<?
		if(isset($results)) { 
			$extra = '';
			if(date('Y-m-d H:i',strtotime($info['dEnd'])) < date('Y-m-d H:i')) { $extra = 'disabled="disabled"'; } 
			$cols = 4;
			$rows = ceil(mysqli_num_rows($results) / $cols);	
			$count=0;
?>	
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td valign="top" width="<?=100/$cols?>%">
<?
				while ($row = mysqli_fetch_assoc($results)) {
					
					if($count >= $rows) {
						$count = 0;
?>
					</td>
					<td width="<?=100/$cols?>%" valign="top">
<?
						}
					if ($row['idExpertise'] != "") {
?>
					<div style="white-space:nowrap;background-color:#DDD;"><?=form_checkbox(array('name'=>'eventexpertise','array'=>'true','value'=>$row['ID'],'title'=>$row['chrExpertise'],'checked'=>'true','extra'=>$extra))?></div>
<?
					} else {
?>
					<div style="white-space:nowrap;"><?=form_checkbox(array('name'=>'eventexpertise','array'=>'true','value'=>$row['ID'],'title'=>$row['chrExpertise'],'extra'=>$extra))?></div>
<?					
					}
					$count++;
				}
?>
					</td>
				</tr>
			</table>
			<div style="padding:5px;padding-top:15px;">
				<?=form_button(array('type'=>'submit','name'=>'save','value'=>'Save/Update','extra'=>$extra))?>
			</div>
		</div>
<?
		}
?>
		</form>
<?	} ?>