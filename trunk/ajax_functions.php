<?
	$BF = "";
	$NON_HTML_PAGE=1;
	require('_lib.php');
	
	if($_REQUEST['postType'] == "checkpeople") {
		$q = "SELECT ID, chrKEY 
				FROM People 
				WHERE ".$_REQUEST['col']."='".$_REQUEST['val']."'";
		$result = db_query($q,"Checking for Records",1); 
		if($result['ID'] != '') { 
			echo $result['chrKEY'];			
		} 
	} 

?>
