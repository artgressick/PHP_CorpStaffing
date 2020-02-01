<?
	include('_controller.php');
		
	function sitm() { 
		global $BF,$results;
		$tableHeaders = array(
			'chrLocation' 		=> array('displayName'=>'Locations','default'=>'asc'),
			'dDate' 			=> array('displayName'=>'Begin Date'),
			'tBegin' 			=> array('displayName'=>'Begin Time'),
			'tEnd' 				=> array('displayName'=>'End Time'),
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
?>