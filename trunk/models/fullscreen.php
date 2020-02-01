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
<?// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // This is to display the SESSION variables, unrem to use ?>
	<table cellpadding='0' cellspacing='0' style='width:100%; border:1px solid #C0C1C2;'>
		<tr>
			<td style='padding:5px 10px;font-weight:bold; font-size:20px;'><?=$page_title?></td>
		</tr>
<?
	if(isset($directions)) {
?>
		<tr>
			<td style='background:#BFF4FF; border-top:1px solid #C0C1C2;border-bottom:1px solid #C0C1C2;padding:5px 10px;'><?=$directions?></td>	
		</tr>
<?
	}
?>
		<tr>
			<td>				          				
<?=messages()?>
<!-- Begin code -->
<?
	# This is where we will put in the code for the page.
	(!isset($sitm) || $sitm == '' ? sitm() : $sitm());
?>
<!-- End code -->
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