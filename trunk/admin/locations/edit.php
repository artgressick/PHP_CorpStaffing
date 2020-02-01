<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tcleft">
										
						<?=form_text(array('caption'=>'Location Name','required'=>'true','name'=>'chrLocation','size'=>'30','maxlength'=>'100','value'=>$info['chrLocation']))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
	
						<?=form_checkbox(array('type'=>'radio','caption'=>'Enable Staffing?','title'=>'No','name'=>'bStaffingEnabled','value'=>'0','required'=>'true','checked'=>(!$info['bStaffingEnabled']?'true':'false')))?>&nbsp;&nbsp;&nbsp;
						<?=form_checkbox(array('type'=>'radio','title'=>'Yes','name'=>'bStaffingEnabled','value'=>'1','checked'=>($info['bStaffingEnabled']?'true':'false')))?>
	
					</td>
				</tr>
			</table>
			<div class='FormButtons'>
				<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
			</div>
		</form>
	</div>
<?
	}
?>