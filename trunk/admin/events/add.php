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
					
						<?=form_text(array('caption'=>'Event Name','required'=>'true','name'=>'chrEvent','size'=>'40','maxlength'=>'100'))?>
						
						<? $timezones = db_query("SELECT ID, chrTimeZone as chrRecord FROM TimeZone ORDER BY intOffset, chrTimeZone","Getting TimeZones"); ?>
						<?=form_select($timezones,array('caption'=>'TimeZone','required'=>'true','name'=>'idTimeZone'))?>
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
						<?=form_textarea(array('caption'=>'Text Message <span id="txtMsgCount">0</span>/250','name'=>'chrTextMessage','cols'=>'43','rows'=>'5','extra'=>'onkeyup=txtMsgCounter(this); onchange=txtMsgCounter(this);'))?>

						<div>
							<?=form_checkbox(array('nocaption'=>'true','name'=>'bTextMessage','title'=>'Automatic Text Message'))?>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tcleft">
						<?=form_text(array('caption'=>'Start Date','required'=>'Required) (i.e., MM/DD/YYYY','name'=>'dBegin','size'=>'15','maxlength'=>'20'))?>
						<div>This date marks the start point of when a Show Manager will have access to this event, They will not have access to this event prior to this date, this will include all lower ranks regardless of options set in the Event Setup area.</div>
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
						<?=form_text(array('caption'=>'End Date','required'=>'Required) (i.e., MM/DD/YYYY','name'=>'dEnd','size'=>'15','maxlength'=>'20'))?>
						<div>This date marks the archive point of the event. Event will be locked and unable to be modified. After this date NO ONE will see this event in Log-in Event Select Box. It will also be removed from the default view of the Manage Events area.</div>
					</td>
				</tr>
			</table>
			
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
				<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
			</div>
		</form>
	</div>
<?
	}
?>