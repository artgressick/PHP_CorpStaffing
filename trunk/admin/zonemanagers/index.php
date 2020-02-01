<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
	
		$tableHeaders = array(
			'chrManager' 			=> array('displayName'=>'Manager Name','default'=>'asc'),
			'chrLocation' 			=> array('displayName'=>'Location'),
			'chrZone' 				=> array('displayName'=>'Zone'),
			'opt_del' 				=> 'chrFirst,chrLast'
		);
		
		sortList('ZoneManagers',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'edit.php?key=',		# The linkto page when you click on the row
			'width: 100%', 			# Additional header CSS here
			''
		);
	}
?>