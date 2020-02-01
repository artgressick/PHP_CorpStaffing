<?
	include('_controller.php');

	function sitm() { 
		global $BF;
?>
		<form action="" method="get" id="idFilter" style='padding:0; margin:0;'>
			<div style='padding:10px 0 10px 10px;background:#C8C9CA;font-weight:bold;'>
<?
						$q = "SELECT chrKEY, chrLocation AS chrRecord FROM Locations WHERE !bDeleted ORDER BY chrLocation";
						$results = db_query($q,"getting locations");
?>
						Select Location: <?=form_select($results,array('nocaption'=>'true','caption'=>'-Select Location-','name'=>'key','value'=>(isset($_REQUEST['key'])?$_REQUEST['key']:''),'extra'=>'onchange="javascript:document.getElementById(\'idFilter\').submit();"'))?>
			</div>
		</form>
<?
		if(isset($_REQUEST['key']) && strlen($_REQUEST['key']) == 40) {
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tcleft" style='vertical-align:top;'>
						<?=form_text(array('caption'=>'Zone Name','required'=>'true','name'=>'chrZone','size'=>'40','maxlength'=>'100'))?>

					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
						<div class='FormName'>Stations<div>
<?
		$stations = db_query("SELECT S.ID, S.chrStation, S.chrNumber
							  FROM Stations AS S
							  JOIN Locations AS L ON S.idLocation=L.ID AND !L.bDeleted AND L.chrKEY='".$_REQUEST['key']."'
							  WHERE !S.bDeleted AND L.idEvent='".$_SESSION['idEvent']."'
							  ORDER BY chrStation, chrNumber", "Getting All Stations");
		
				
		if(mysqli_num_rows($stations) > 0) {
			$half = ceil(mysqli_num_rows($stations)/2);
?>
						<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tcleft" style='vertical-align:top;font-weight:normal;'>
<?
							$count=0;
							while($row = mysqli_fetch_assoc($stations)) {
								if($count++ >= $half) {
?>
								</td>
								<td class="tcgutter"></td>
								<td class="tcright" style='vertical-align:top;font-weight:normal;'>
<?
								}
?>
									<div style="white-space:nowrap;"><?=form_checkbox(array('name'=>'idStations','array'=>'true','value'=>$row['ID'],'title'=>$row['chrStation'].' ('.$row['chrNumber'].')'))?></div>
<?
							}
?>
						
								</td>
							</tr>
						</table>
<?
		} else {
?>			
						<div style='padding:5px;font-weight:normal;'>No Stations have been created for this Location</div>
<?		
		}
?>

					</td>
				</tr>
			</table>
			<div class='innerbody'>
				<div class='FormName'><?=linkto(array('address'=>'#','img'=>'plus_add.png','extra'=>'onclick=\'newwin = window.open("popup_person.php?d='.urlencode(base64_encode('functioncall=user_add')).'","new","width=435,height=400,resizable=1,scrollbars=1"); newwin.focus();\''))?> Zone Managers</div>
						<input type='hidden' id='idUsers' name='idUsers' value='<?=$info['idUsers']?>' />
						<input type='hidden' id='chrUsers' name='chrUsers' value='<?=$info['chrUsers']?>' />
						<table id='ListUsers' class='List' style='width: 100%;' cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th style='white-space: nowrap;'>Name</th>
									<th style='white-space: nowrap; width:15px;'><img src='<?=$BF?>images/options.gif' alt='options' /></th>
								</tr>
							</thead>
							<tbody>
<?			if($info['idUsers'] != '') { 
				$ids = explode(',', $info['idUsers']);
				$chrs = explode(',', $info['chrUsers']);
				$count = 0;
				foreach($ids as $k => $user_id) { 
					$chr = $chrs[$k];
?>
								<tr id='ListUserstr<?=$user_id?>' class='<?=(++$count%2?'ListOdd':'ListEven')?>'>
									<td style='width: 99%;'><?=$chr?></td>
									<td style='width: 1%;'><a href='#' onclick="user_remove(<?=$user_id?>, this);" title='Delete: <?=$chr?>'><img id='deleteButton<?=$user_id?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></td>
								</tr>
<?				} ?>
<?			} ?>
							</tbody>
						</table>
						<div id='ListUserstrNoUser' style='border:1px solid #999;border-top:none;height:20px; padding-top:4px; text-align:center; <?=($info['idUsers'] == ''?'':'display: none;')?>'>
							No Show Managers have been added.
						</div>

<script type="text/javascript">//<![CDATA[
function user_add(id, first, last) 
{ 
	document.getElementById('ListUserstrNoUser').style.display='none';
	var row = list_add('ListUsers', 'idUsers', 'chrUsers', id, first+" "+last);
	if(!row) {
	} else {
		row.lastChild.innerHTML= "<a href='#' onclick=\"user_remove(" + id + ", this);\" title='Delete: "+first+" "+last+"'><img id='deleteButton"+id+"' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src=\"<?=$BF?>images/button_delete_on.png\"' onmouseout='this.src=\"<?=$BF?>images/button_delete.png\"' /></a>";
	}
	repaint('ListUsers');
}
function user_remove(id, button)
{
	list_remove('ListUsers', 'idUsers', 'chrUsers', id, button);
	var table = document.getElementById('ListUsers');
	var tbody = table.getElementsByTagName("TBODY")[0];
	var rows = tbody.getElementsByTagName("TR");
	repaint('ListUsers');
	if(!rows.length) {
		document.getElementById('ListUserstrNoUser').style.display='block';
	}
}
// ]]></script>

	 				   	</div>
					</div>
			<div class='FormButtons'>
				<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
			</div>
		</form>
	</div>
		
<?
		}
	}
?>

