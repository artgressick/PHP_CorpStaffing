<?
	$BF = "";
	$NON_HTML_PAGE=1;
	require('_lib.php');
	
	if($_REQUEST['postType'] == "delete") {
		$total = 0;
		echo $q = "UPDATE ". $_REQUEST['tbl'] ." SET bDeleted=1 WHERE ID=".$_REQUEST['id'];
		if(db_query($q,"update bDeleted")) { 
			$total++;
			$q = "INSERT INTO Audit SET idPerson=".$_REQUEST['idUser'].", idRecord=".$_REQUEST['id'].", chrTableName='". $_REQUEST['tbl'] ."', chrColumnName='bDeleted', dtDatetime=now(), 
					txtOldValue='0', txtNewValue='1', idType=3"; 
			if(db_query($q,"insert into audit")) { $total += 2; }
		}
  		echo $total;
	} else 	if(@$_REQUEST['postType'] == "permDelete") {
		$total = 0;
		$q = "DELETE FROM ". $_REQUEST['tbl'] ." WHERE ID=".$_REQUEST['id'];
		if(db_query($q,"perm delete")) { 
			$total++;
			$q = "INSERT INTO Audit SET idPerson=".$_REQUEST['idUser'].", idRecord=".$_REQUEST['id'].", chrTableName='". $_REQUEST['tbl'] ."', chrColumnName='', dtDatetime=now(), 
					txtOldValue='', txtNewValue='', idType=4"; 
			if(db_query($q,"insert into audit table")) { $total += 2; }
		}
  		echo $total;
  	} 

?>
