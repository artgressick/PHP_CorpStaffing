<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		if ($_SESSION['idEventFilter'] != 4) {	
			$tableHeaders = array(
				'chrEvent' 		=> array('displayName'=>'Event'),
				'dBegin' 		=> array('displayName'=>'Begin Date','default'=>'asc'),
				'dEnd' 			=> array('displayName'=>'End Date'),
				'opt_del' 		=> 'chrEvent'
			);
		} else {
			$tableHeaders = array(
				'chrEvent' 		=> array('displayName'=>'Event'),
				'dBegin' 		=> array('displayName'=>'Begin Date','default'=>'asc'),
				'dEnd' 			=> array('displayName'=>'End Date'),
			);
		}
			
		sortList('Events', 		# Table Name
			$tableHeaders,		# Table Name
			$results,			# Query results
			'edit.php?key=',	# The linkto page when you click on the row
			'width: 100%;border:none;', 		# Additional header CSS here
			''
		);

	}