<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<? if(isset($_SESSION['idPerson']) && is_numeric($_SESSION['idPerson'])) { // This is added to auto logout person if they stay on the same page for over 20 minutes == 1200 seconds
?>	<meta http-equiv="refresh" content="1200;URL=?logout=2" />
<?	} ?>
	<title><?=(isset($title) ? $title.' - ' : '')?><?=$PROJECT_NAME?></title>
	<link href="<?=$BF?>includes/global.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" src="<?=$BF?>includes/nav.js"></script>
	<script type='text/javascript'>var BF = '<?=$BF?>';</script>

<?		# If the "Stuff in the Header" function exists, then call it
		if(function_exists('sith')) { sith(); } 
?>
</head>
<body <?=(isset($bodyParams) ? 'onload="'. $bodyParams .'"' : '')?>>
<?// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // This is to display the SESSION variables, unrem to use?>
<table width="908" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="3"><a href="<?=$BF?>index.php" title="Link to the Main Page"><img src="<?=$BF?>images/general-logo.gif" width="309" height="150" /><img src="<?=$BF?>images/general-main1.jpg" width="599" height="150" /></a></td>
	</tr>
	<tr>
		<td width="4" background="<?=$BF?>images/shadow-left.gif"><img src="<?=$BF?>images/shadow-left.gif" width="4" height="5" /></td>
<!-- This is the middle of the smooth bar-->
		<td width="900" style='background: url(<?=$BF?>images/smoothbar.gif) #fff repeat-x; height:20px;'>
<? if(isset($_SESSION['idPerson']) && is_numeric($_SESSION['idPerson'])) { ?>

			<table width="900" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="<?=$BF?>images/smoothbar_arrow.gif" /></td>
					<td style="text-align:left; width:100%; padding-left:5px;"><span class="loginbar" style="">Welcome <a href="<?=$BF?>myprofile/index.php" style='color:blue;font-weight:bold;'><?=$_SESSION['chrFirst']?> <?=$_SESSION['chrLast']?></a> <?=(isset($_SESSION['chrEvent']) ? ' to <span style="color:blue;font-weight:bold;">'.$_SESSION['chrEvent'].'</span>' : '')?></span>
					</td>
					<td align="right" nowrap="nowrap" style='padding-right:10px;'>
						<a href="?logout=1">Logout</a>
            		</td>
        		</tr>
			</table>
<?	} else { ?>
			&nbsp;
<?	} ?>

		  	</td>
		  	<td width="4" background="<?=$BF?>images/shadow-right.gif"><img src="<?=$BF?>images/shadow-right.gif" width="4" height="5" /></td>
		</tr>
		<tr>
			<td width="4" background="<?=$BF?>images/shadow-left.gif"><img src="<?=$BF?>images/shadow-left.gif" width="4" height="5" /></td>
			<td style='padding:10px; background:white;'>
<!-- Begin code -->
<?
	# This is where we will put in the code for the page.
	(!isset($sitm) || $sitm == '' ? sitm() : $sitm());
?>
<!-- End code -->

    		</td>
		  	<td width="4" background="<?=$BF?>images/shadow-right.gif"><img src="<?=$BF?>images/shadow-right.gif" width="4" height="5" /></td>
	    </tr>
		<tr>
	      	<td colspan="3">
			  	<table width="908" border="0" cellspacing="0" cellpadding="0">
				  	<tr>
						<td rowspan="2" align="left" valign="bottom" background="<?=$BF?>images/shadowblue-left.gif"><img src="<?=$BF?>images/blue-bottomleft.gif" width="15" height="18" /></td>
						<td width="100%" height="45" bgcolor="#C2CED8">
							<div class="Copyright">
								<p class="Copyright">&copy; <?=date('Y')?>, Apple, Inc. Internal Use Only</p>
								<p class="Copyright">Apple Events Staffing Version 2.0a built by techIT Solutions</p>
							</div>
						</td>
						<td rowspan="2" align="right" valign="bottom" background="<?=$BF?>images/shadowblue-right.gif"><img src="<?=$BF?>images/blue-bottomright.gif" width="15" height="18" /></td>
				  	</tr>
				  	<tr>
						<td background="<?=$BF?>images/shadow-bottom.gif"><img src="<?=$BF?>images/shadow-bottom.gif" width="4" height="9" /></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

<?
	# Any aditional things can go down here including javascript or hidden variables
	# "Stuff on the Bottom"
	if(function_exists('sotb')) { sotb(); } 
?>
</body>
</html>