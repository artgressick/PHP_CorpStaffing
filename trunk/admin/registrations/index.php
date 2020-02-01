<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
	
		$tableHeaders = array(
			'chrLast' 			=> array('displayName' => 'Last Name'),
			'chrFirst' 			=> array('displayName' => 'First Name'),
			'chrEmail' 			=> array('displayName' => 'Email Address'),
			'dtCreated' 		=> array('displayName' => 'Registered On'),
			'intAge' 			=> array('displayName' => 'Age','default' => 'desc'),
			'chrPersonStatus' 	=> array('displayName' => 'Account Status'),
		);
		
		sortList('People', 		# Table Name
			$tableHeaders,		# Table Name
			$results,			# Query results
			'edit.php?key=',	# The linkto page when you click on the row
			'width: 100%;border:none;', 		# Additional header CSS here
			''
		);
	
	}
?>