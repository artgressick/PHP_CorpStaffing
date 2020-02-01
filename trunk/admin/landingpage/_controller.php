<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';

	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];

	switch($file_name[0]) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			$title = "Edit Landing Page";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'components/formfields.php');
			
			$info = db_query("
								SELECT * 
								FROM LandingPage 
								WHERE idEvent='".$_SESSION['idEvent']."'
			","getting info",1); // Get Info

			if(isset($_POST['update']) && $_POST['update'] = 'Update Information' && isset($_POST['txtHTML'])) { include($post_file); }
			
			if($info['ID'] == "") { errorPage('Invalid Landing Page'); } // Did we get a result? You should never see this unless we forgot to add the entry to the DB.
			
			

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
				</script><?
				
			}
				
			# The template to use (should be the last thing before the break)
			$page_title = 'Edit Landing Page';
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.<br /><strong>Please set main width to 100% and it is not recommended to go beyond 300px in height.</strong>';
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