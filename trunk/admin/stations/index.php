<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
	
		$tableHeaders = array(
			'chrStation' 		=> array('displayName' => 'Name','default' => 'asc'),
			'chrNumber'			=> array('displayName' => 'Number'),
			'chrStationType'	=> array('displayName' => 'Type'),
			'chrLocation'		=> array('displayName' => 'Location'),
			'opt_del' 			=> 'chrStation'
		);
		
		sortList('Stations',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'edit.php?key=',		# The linkto page when you click on the row
			'width: 100%;border:none;', 			# Additional header CSS here
			''
		);
	}
?>