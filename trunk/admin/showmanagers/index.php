<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
	
		$tableHeaders = array(
			'chrManager' 			=> array('displayName'=>'Manager','default'=>'asc'),
			'opt_del' 				=> 'chrFirst,chrLast'
		);
		
		sortList('ShowManagers',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'edit.php?key=',		# The linkto page when you click on the row
			'width: 100%;border:none;', 			# Additional header CSS here
			''
		);
	} 
?>