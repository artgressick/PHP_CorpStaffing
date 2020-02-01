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
		<td width="900" style='background: url(<?=$BF?>images/smoothbar.gif) #fff repeat-x;'>
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
<?	} ?>
		  	</td>
		  	<td width="4" background="<?=$BF?>images/shadow-right.gif"><img src="<?=$BF?>images/shadow-right.gif" width="4" height="5" /></td>
		</tr>
		<tr>
			<td width="4" background="<?=$BF?>images/shadow-left.gif"><img src="<?=$BF?>images/shadow-left.gif" width="4" height="5" /></td>
			<td>
				<table width='100%' cellpadding='0' cellspacing='0' border='0' style='background:white;'>
					<tr>
						<td style='vertical-align:top;'>
							<table cellspacing="0" cellpadding="0" border="0" width="100%" style="padding:10px;padding-right:5px;">
								<tr>
									<td style='width:4px;' align="left" background="<?=$BF?>images/wire-header-bg.gif"><img src="<?=$BF?>images/wire-header-left.gif"></td>
									<td background="<?=$BF?>images/wire-header-bg.gif" style='font-weight:bold; color:#282828;font-size:12px;'>
										<table cellpadding='0' cellspacing='0' border='0' style='width:100%;'>
											<tr>
												<td style='text-align:left;padding-left:5px;vertical-align:middle;'><?=$page_title?></td>
<?
											if(isset($filters)) {
?>
												<td style='text-align:right;padding-right:5px;vertical-align:middle;font-weight:normal;font-size:10px;'>
													<form action="" method="get" id="idFilterForm" style="padding:0px; margin:0px;">
														<?=$filters?>
														<?=form_button(array('type'=>'submit','name'=>'filter','value'=>'Filter'))?>								
													</form>
												</td>
<?									
											}
?>
											</tr>
										</table>
									</td>
									<td style='width:4px;' align="right" background="<?=$BF?>images/wire-header-bg.gif"><img src="<?=$BF?>images/wire-header-right.gif"></td>
								</tr>
<?
							if(isset($directions)) {
?>
								<tr>
									<td colspan='3' class='directions'><?=$directions?></td>	
								</tr>
<?
							}
?>
								<tr>
				              		<td style="border:solid 1px #c0c1c2;" colspan='3'>
				          				
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
    					</td>
    					<td width='150' style='vertical-align:top;'>
<?
						 if(access_check('1,2,3,4')) {
?>
							<table cellspacing="0" cellpadding="0" border="0" width="100%" class="navigation">
								<tr>
									<td align="left" background="<?=$BF?>images/wire-header-bg.gif"><img src="<?=$BF?>images/wire-header-left.gif"></td>
									<td class="heading" width="100%" background="<?=$BF?>images/wire-header-bg.gif">My Information</td>
									<td align="right" background="<?=$BF?>images/wire-header-bg.gif"><img src="<?=$BF?>images/wire-header-right.gif"></td>
								</tr>
								<tr>
				              		<td class="navbox" padding:0px;" colspan='3'>
										<ul>
											<li><a href="<?=$BF?>">Home</a></li>
											<li><a href="<?=$BF?>myprofile/">My Profile</a></li>
											<? if(access_check('1,2,3,4') && isset($_SESSION['idEvent']) && is_numeric($_SESSION['idEvent'])) { 
												$tmp = db_query("SELECT idEvent FROM Schedule_Requested WHERE idEvent='".$_SESSION['idEvent']."' AND idPerson='".$_SESSION['idPerson']."'","See if person has requested a Schedule",1); ?>
											<li><a href="<?=$BF?>myschedule/step1.php"><?=($tmp['idEvent'] == ''?'Sign-up to Work':'Edit Work Request')?></a></li>
											<? } ?>
											<? if(access_check('1,2,3,4') && 1==0 && isset($_SESSION['idEvent']) && is_numeric($_SESSION['idEvent'])) { ?>
											<li><a href="<?=$BF?>mytravel/">My Travel</a></li>
											<? } ?>
										</ul>
										<div style="text-align:center; padding-bottom:5px; background-color:#c0c1c2; padding:5px;">
											<strong>iCal Subscription</strong>
										</div>
										<div style="text-align:center; padding-bottom:5px; background-color:#c0c1c2; padding:5px;">
											<a href='<?=$PROJECT_ADDRESS?>/makeical.php'><img src='<?=$BF?>images/ical_subscription.png' /></a><br />
										</div>
<?
									$calinfo = db_query("SELECT dtCreated, dtSynced FROM CalendarQueries WHERE idPerson='".$_SESSION['idPerson']."' ORDER BY dtCreated DESC","Getting Calendar Information",1);
?>
										<div style="padding-bottom:5px; background-color:#c0c1c2; padding:5px; color:#FFFFFF; font-size:10px; text-align:center;">
											<strong>Last Sync'd:</strong> <?=($calinfo['dtSynced'] != '' && $calinfo['dtSynced'] != '0000-00-00 00:00:00' ? date('n/j/Y',strtotime($calinfo['dtSynced'])):'n/a')?><br>
											<strong>Subscribed:</strong> <?=($calinfo['dtCreated'] != '' && $calinfo['dtCreated'] != '0000-00-00 00:00:00' ? date('n/j/Y',strtotime($calinfo['dtCreated'])):'n/a')?>
										</div>
									</td>
								</tr>
							</table>
<?
						}
						if(access_check('1,2,3') || $_SESSION['bAdmin']==1) { 
?>
							<table cellspacing="0" cellpadding="0" border="0" width="100%" class="navigation">
								<tr>
									<td align="left" background="<?=$BF?>images/wire-header-bg.gif"><img src="<?=$BF?>images/wire-header-left.gif"></td>
									<td class="heading" width="100%" background="<?=$BF?>images/wire-header-bg.gif">Administration</td>
									<td align="right" background="<?=$BF?>images/wire-header-bg.gif"><img src="<?=$BF?>images/wire-header-right.gif"></td>
								</tr>
								<tr>
				              		<td class="navbox" colspan='3'>
										<ul>
										<? if(isset($_SESSION['idEvent']) && is_numeric($_SESSION['idEvent'])) { ?>
											<li><a href="<?=$BF?>assignment/">Staffer Assignment</a></li>
											<li><a href="<?=$BF?>emailers/">Mass E-mailer</a></li>
										<? } else { ?>
											<li><a href="<?=$BF?>">Sign into Event</a></li>
										<? } ?>
										<? if(access_check('1,2')) { ?>
											<li><a href="<?=$BF?>admin/eventsettings/">Event Settings</a></li>
											<li><a href="<?=$BF?>admin/locations/">Locations</a></li>
											<li><a href="<?=$BF?>admin/shifts/">Shifts</a></li>
											<li><a href="<?=$BF?>admin/stations/">Stations/Positions</a></li>
											<li><a href="<?=$BF?>admin/zones/">Zones</a></li>
											<li><a href="<?=$BF?>admin/people/">All Staffers</a></li>
											<li><a href="<?=$BF?>admin/landingpage/">Landing Page</a></li>
										<? } ?>
										<? if(access_check('1') || $_SESSION['bAdmin']==1) { ?>
											<li><a href="<?=$BF?>admin/registrations/">New Registrations</a></li>
											<li><a href="<?=$BF?>admin/events/">Events</a></li>
											<li><a href="<?=$BF?>admin/departments/">Departments</a></li>
											<li><a href="<?=$BF?>admin/eventexpertise/">Event Expertise</a></li>
											<li><a href="<?=$BF?>admin/expertise/">Expertise</a></li>
											<li><a href="<?=$BF?>admin/employeetypes/">Employee Types</a></li>
										<? } ?>
										
										</ul>
									</td>
								</tr>
							</table>
<?
						}
						if(access_check('1,2,3') && isset($_SESSION['idEvent']) && is_numeric($_SESSION['idEvent'])) {
?>
						
							<table cellspacing="0" cellpadding="0" border="0" width="100%" class="navigation">
								<tr>
									<td align="left" background="<?=$BF?>images/wire-header-bg.gif"><img src="<?=$BF?>images/wire-header-left.gif"></td>
									<td class="heading" width="100%" background="<?=$BF?>images/wire-header-bg.gif">Reports</td>
									<td align="right" background="<?=$BF?>images/wire-header-bg.gif"><img src="<?=$BF?>images/wire-header-right.gif"></td>
								</tr>
								<tr>
				              		<td class="navbox" colspan='3'>
										<ul>
											<li><a href="<?=$BF?>reports/staffingmatrix/" target='_blank'>Staffing Matrix</a></li>
										</ul>
									</td>
								</tr>
							</table>
<?
						}						
?>
	  					</td>
    				</tr>
    			</table>
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