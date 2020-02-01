<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
		<form action="" method="post" id="idForm" onsubmit="return error_check()" style='padding:0; margin:0;'>
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tcleft" colspan="3" style='padding:5px;'>
						<?=form_text(array('caption'=>'Event Name','value'=>$info['chrEvent'],'display'=>'true'))?>
					</td>
				</tr>

				<tr>
					<td class="tcleft" style='padding:5px;'>
						<?=form_text(array('caption'=>'Start Date','value'=>date('n/j/Y',strtotime($info['dBegin'])),'display'=>'true'))?>
					</td>
					<td class="tcgutter"></td>
					<td class="tcright" style='padding:5px;'>
						<?=form_text(array('caption'=>'End Date','value'=>date('n/j/Y',strtotime($info['dEnd'])),'display'=>'true'))?>
					</td>
				</tr>
				<tr>
					<td class="tcleft" style='padding:5px;border-top:1px solid #C0C1C2;'>
<?						
						$q = "SELECT ID, chrStatus AS chrRecord FROM EventStaffingStatus ORDER BY ID";
						$staffingStatus = db_query($q,"getting Staffing Status");
?>
						<?=form_select($staffingStatus,array('caption'=>'Staffing Status','required'=>'true','name'=>'idStaffingStatus','value'=>$info['idStaffingStatus']))?>
						<div>
							<ul style="margin-left:0px;padding-left:15px;">
								<li style="padding-bottom:5px;"><strong>Closed</strong>: Staffers and Zone Managers alike will not have access to this event.</li>
								<li style="padding-bottom:5px;"><strong>Open</strong>: Staffers and Zone Managers alike will have access to this event.</li>
								<li style="padding-bottom:5px;"><strong>Read Only</strong>: Staffers can view, but unable to modify any information.</li>
							</ul>
						</div>
					</td>
					<td class="tcgutter" style='border-top:1px solid #C0C1C2;'></td>
					<td class="tcright" style="padding:5px;border-top:1px solid #C0C1C2;">
<?						
						$q = "SELECT ID, chrStatus AS chrRecord FROM EventZoneManagerStatus ORDER BY ID";
						$ZoneManagerStatus = db_query($q,"getting Zone Manager Status");
?>
						<?=form_select($ZoneManagerStatus,array('caption'=>'Zone Manager Status','required'=>'true','name'=>'idZoneManagerStatus','value'=>$info['idZoneManagerStatus']))?>
						<div>
							<ul style="margin-left:0px;padding-left:15px;">
								<li style="padding-bottom:5px;"><strong>Read Only</strong>: Zone Managers can view, but unable to modify any information. (Staffers Status must be either Open or Read Only)</li>
								<li style="padding-bottom:5px;"><strong>Able to Schedule</strong>: Zone Managers can schedule their zones. (Staffers Status must be either Open or Read Only)</li>
							</ul>
						</div>
					</td>
				</tr>
			</table>
			<div class='FormButtons' style='padding:5px;'>
				<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
			</div>
		</form>
<?
	}
?>