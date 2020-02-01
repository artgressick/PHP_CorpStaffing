<?
	function litm() { 
		global $BF,$title,$instructions;
?>
		<form id="form1" name="form1" method="post" action="">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="40%" valign="top">
					<div style="font-size:16px; font-weight:bold; padding-top:15px;">New Staffer?</div>
					<div style="color:#666666; font-size:12px; padding-top:15px; padding-right:10px;">In order to register for Events Staffing, you must first create a profile. Please click the button below to begin the process.</div>
					<div style="padding-top:20px;"><?=form_button(array('value'=>'Register Here','extra'=>'onclick="javascript:location.href=\''.$BF.'register/\'";'))?></div>
				</td>
				<td width="60%" valign="top">
					<div style="font-size:16px; font-weight:bold; padding-top:15px; padding-left:10px;">Returning Staffer?</div>
					<div style="color:#666666; font-size:12px; padding-top:15px; padding-left: 10px; padding-right:10px;">If you have already registered please type your email address and password below to login. Once you have logged into the site you can edit your profile and register to staff an event.</div>
					<div style="padding: 10px;">
					<?=messages()?> 
					<?=form_text(array('caption'=>'E-mail Address','required'=>'true','value'=>(isset($_REQUEST['auth_form_name']) ? $_REQUEST['auth_form_name'] : ''),'name'=>'auth_form_name','size'=>'30','maxlength'=>'35'))?>
					<?=form_text(array('caption'=>'Password','required'=>'true','name'=>'auth_form_password','size'=>'30','maxlength'=>'30','type'=>'password'))?>
					<div style="padding-top:10px;">
						<?=form_button(array('type'=>'submit','name'=>'Submit','value'=>'Log In'))?>
					</div>
					<div style="padding-top:10px;">
						<?=linkto(array('address'=>$BF.'/lostpassword/','display'=>'Lost your password? Get a new one.'))?>

					</div>
		   	    </td>
			</tr>
		</table>
		</form>
<?	} ?>