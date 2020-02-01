<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../';
	
	$file_name = basename($_SERVER['SCRIPT_NAME']);
	$post_file = '_'.$file_name;

	switch($file_name) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			$page_title = "Mass E-mailer";	# Page Title
			$directions = 'To send a Mass E-mail, please choose the filers and options below.  Fill out the message and click on the "Send E-mail" button.';
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			#$auth_options = 'EventNotNeeded';
			auth_check('litm','1');
			include_once($BF.'components/formfields.php');
			
			if(isset($_POST['submit'])) { include($post_file); }	

			# Stuff In The Header
			function sith() { 
				global $BF,$PROJECT_ADDRESS;
			?>	<script type='text/javascript' src='error_check.js'></script>
				<script type="text/javascript" src="<?=$BF?>components/tiny_mce/tiny_mce_gzip.js"></script>
				<script type="text/javascript">
				tinyMCE_GZ.init({
					plugins : 'style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',
					themes : 'simple,advanced',
					languages : 'en',
					disk_cache : true,
					debug : false
				});
				</script>
				<!-- Needs to be seperate script tags! -->
				<script language="javascript" type="text/javascript">
					tinyMCE.init({
						mode : "textareas",
						plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,filemanager",
						theme_advanced_buttons1_add : "fontselect,fontsizeselect",
						theme_advanced_buttons2_add : "separator,forecolor,backcolor",
						theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator",
						theme_advanced_buttons3_add : "emotions,flash,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
						theme_advanced_toolbar_location : "top",
						theme_advanced_path_location : "bottom",
						content_css : "/example_data/example_full.css",
					    plugin_insertdate_dateFormat : "%Y-%m-%d",
					    plugin_insertdate_timeFormat : "%H:%M:%S",
						extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
						external_link_list_url : "example_data/example_link_list.js",
						external_image_list_url : "example_data/example_image_list.js",
						flash_external_list_url : "example_data/example_flash_list.js",
						file_browser_callback : "mcFileManager.filebrowserCallBack",
						theme_advanced_resize_horizontal : false,
						theme_advanced_resizing : true,
						apply_source_formatting : true,
						
						filemanager_rootpath : "<?=realpath($BF . 'eventfiles/'.$_SESSION['idEvent'].'/')?>",
						filemanager_path : "<?=realpath($BF . 'eventfiles/'.$_SESSION['idEvent'].'/')?>",
						filemanager_extensions : "gif,jpg,htm,html,pdf,zip,txt,doc,xls",
						relative_urls : true,
						document_base_url : "<?=$PROJECT_ADDRESS?>"
					});
				</script>
				<script type='text/javascript' language='javascript' src='emailer.js'></script><?
			}

			# The template to use (should be the last thing before the break)
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	SendEmails Page
		#################################################
		case 'sendemails.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			#$auth_options = 'EventNotNeeded';
			auth_check('litm','1');
			include_once($BF.'components/formfields.php');

			if(!isset($_SESSION['emailer']) || !is_numeric($_SESSION['emailer']['newID'])) { 
				$_SESSION['errorMessages'][] = 'You need to create a e-mail before you can send it.';
				header("Location: index.php");
				die();
			}
			
			$emailinfo = db_query("SELECT chrSendTo
								   FROM EmailerLogs
								   JOIN EmailerTypes ON EmailerLogs.idEmailerType=EmailerTypes.ID
								   WHERE EmailerLogs.ID=".$_SESSION['emailer']['newID'],"Getting Emailer Information",1);
			
			$results = db_query($_SESSION['emailer']['query'],"Getting Attendees to email");
			
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$page_title = "Mass E-mailer";	# Page Title
			$directions = 'This will send out E-mails to "<strong>'.$emailinfo['chrSendTo'].'</strong>", which results in <strong>'. mysqli_num_rows($results) .'</strong> person/people.<br />Below is a log of the status of the E-mailer.  Please do not close this window. Until you see "FINISHED" in the log window.';
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>