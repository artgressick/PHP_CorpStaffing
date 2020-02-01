<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tcleft" colspan="3">
						<?=form_text(array('caption'=>'Event Name','required'=>'true','name'=>'chrEvent','size'=>'40','maxlength'=>'100','value'=>$info['chrEvent']))?>
						
						<? $timezones = db_query("SELECT ID, chrTimeZone as chrRecord FROM TimeZone ORDER BY intOffset, chrTimeZone","Getting TimeZones"); ?>
						<?=form_select($timezones,array('caption'=>'TimeZone','required'=>'true','name'=>'idTimeZone','value'=>$info['idTimeZone']))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
						<?=form_textarea(array('caption'=>'Text Message <span id="txtMsgCount">'.strlen(decode($info['chrTextMessage'])).'</span>/250','name'=>'chrTextMessage','cols'=>'43','rows'=>'5','value'=>$info['chrTextMessage'],'extra'=>'onkeyup=txtMsgCounter(this); onchange=txtMsgCounter(this);'))?>

						<div>
							<?=form_checkbox(array('nocaption'=>'true','name'=>'bTextMessage','title'=>'Automatic Text Message','checked'=>($info['bTextMessage'] == 1 ? 'true' : 'false')))?>
						</div>

					</td>
				</tr>
			</table>
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tcleft">
						<?=form_text(array('caption'=>'Start Date','required'=>'true','name'=>'dBegin','size'=>'15','maxlength'=>'20','value'=>date('n/j/Y',strtotime($info['dBegin']))))?>
						<div>This date marks the start point of when a Show Manager will have access to this event, They will not have access to this event prior to this date, this will include all lower ranks regardless of options set in the Event Setup area.</div>
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
						<?=form_text(array('caption'=>'End Date','required'=>'true','name'=>'dEnd','size'=>'15','maxlength'=>'20','value'=>date('n/j/Y',strtotime($info['dEnd']))))?>
						<div>This date marks the archive point of the event. Event will be locked and unable to be modified. After this date NO ONE will see this event in Log-in Event Select Box. It will also be removed from the default view of the Manage Events area.</div>
					</td>
				</tr>
			</table>
			
			
			

<?
	$results = db_query("SELECT People.ID,chrFirst,chrLast FROM People JOIN ShowManagers ON ShowManagers.idPerson=People.ID WHERE ShowManagers.idEvent=".$info['ID'],"getting users");
	$info['idUsers']="";
	$info['chrUsers']="";
	while($row = mysqli_fetch_assoc($results)) {
		$info['idUsers'] = $row['ID'].",";
		$info['chrUsers'] = $row['chrFirst']." ".$row['chrLast'].",";
	}
	$info['idUsers'] = substr($info['idUsers'],0,-1);
	$info['chrUsers'] = substr($info['chrUsers'],0,-1);
	
?>			
						
			<div class='innerbody'>
				<div class='FormName'><?=linkto(array('address'=>'#','img'=>'plus_add.png','extra'=>'onclick=\'newwin = window.open("popup_person.php?d='.urlencode(base64_encode('functioncall=user_add')).'","new","width=435,height=400,resizable=1,scrollbars=1"); newwin.focus();\''))?> Show Managers</div>
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
				<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
			</div>
		</form>
	</div>
<?
	}
?>