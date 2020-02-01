<?php
	include('_controller.php');
	function sitm() { ?>


		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="home_content">
			<tr>
				<td width="100%">
					<form id='idForm' name='idForm' method='post' action=''>
					<div style="text-align:center; font-size:14px;"><strong>Your Account has been disabled.</strong></div>
					<div style="text-align:center; font-size:12px; padding-top:20px;">
						<div>Due to multiple bad login attempts your account has been Locked.</div>
						<div>To unlock your account you must submit a request for a new password using the link below.</div>
					</div>
					<div style="text-align:center; padding-top:20px;">
							<?=form_button(array('type'=>'button','name'=>'LostPassword','value'=>'Get New Password','extra'=>'onclick="javascript:location.href=\''.$BF.'lostpassword\'";'))?>
					</div>
					</form>
				</td>
			</tr>
		</table>
<?	} ?>