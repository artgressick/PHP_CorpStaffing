<form id="form1" name="form1" method="post" action="">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="40%" valign="top">
			<div style="font-size:16px; font-weight:bold; padding-top:15px;">New Staffer?</div>
			<div style="color:#666666; font-size:12px; padding-top:15px; padding-right:10px;">In order to register for Events Staffing, you must first create a profile. Please click the button below to begin the process.</div>
			<div style="padding-top:20px;"><input type="button" value="Register Here" onclick="javascript:location.href='register.php'"</div>
		</td>
		<td width="60%" valign="top">
			<div style="font-size:16px; font-weight:bold; padding-top:15px; padding-left:10px;">Returning Staffer?</div>
			<div style="color:#666666; font-size:12px; padding-top:15px; padding-left: 10px; padding-right:10px;">If you have already registered please type your email address and password below to login. Once you have logged into the site you can edit your profile and register to staff an event.</div>
			<div style="padding: 10px;">
<? 	if(isset($_SESSION['InfoMessage'])) { ?> 
		<div class='InfoMessage'><?=$_SESSION['InfoMessage']?></div> 
<? 	unset($_SESSION['InfoMessage']); } ?>	

<?	if(isset($error_messages)) {
		foreach($error_messages as $er) { ?>
			<div class='ErrorMessage'><?=$er?></div>
<?		}
	}
?>
        	<div style="padding-top: 10px;"><span class="FormName">E-mail Address</span> <span class="FormRequired">(Required)</span><br />
            	<input name="auth_form_name" type="text" size="30" maxlength="35" value='<?=(isset($_REQUEST['auth_form_name']) ? $_REQUEST['auth_form_name'] : '')?>' /></div>
			<div style="padding-top: 5px;"><span class="FormName">Password</span> <span class="FormRequired">(Required)</span><br />
            	<input name="auth_form_password" type="password" size="30" maxlength="30" /></div>
			<div style="padding-top:10px;">
				<input type="submit" name="Submit" value="Log In" />
			</div>
			<div style="padding-top:10px;">
				<a href="lostpassword.php">Lost your password? Get a new one.</a>
			</div>
   	    </td>
	</tr>
</table>
</form>