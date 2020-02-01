<?
	include('_controller.php');

	function sitm() { 
		global $BF,$results;
	
		$tableHeaders = array(
			'chrZone' 		=> array('displayName' => 'Zone Name','default' => 'asc'),
			'chrLocation'   => array('displayName' => 'Location'),
			'intZones'		=> array('displayName' => '# of Stations'),
			'intManagers'	=> array('displayName' => '# of Managers'),
			'opt_del' 		=> 'chrZone'
		);
		
		sortList('Zones',	# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'edit.php?key=',		# The linkto page when you click on the row
			'width: 100%;border:none;', 			# Additional header CSS here
			''
		);
	}
?>

