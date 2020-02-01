<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
	
		$tableHeaders = array(
			'chrLocation' 		=> array('displayName'=>'Locations','default'=>'asc'),
			'bStaffingEnabled' 	=> array('displayName'=>'Staffing Enabled', 'style'=>'width: 1px; white-space: nowrap;'),
			'opt_del' 			=> 'chrLocation'
		);
		
		sortList('Locations',	# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'edit.php?key=',		# The linkto page when you click on the row
			'width: 100%;border:none;', 			# Additional header CSS here
			''
		);
	}