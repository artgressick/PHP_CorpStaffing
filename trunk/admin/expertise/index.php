<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
	
	$tableHeaders = array(
		'chrExpertise' 		=> array('displayName'=>'Expertise Name','default'=>'asc'),
		'opt_del' 			=> 'chrExpertise'
	);
	
	sortList('Expertise',		# Table Name
		$tableHeaders,			# Table Name
		$results,				# Query results
		'edit.php?key=',		# The linkto page when you click on the row
		'width: 100%; border:none;', 			# Additional header CSS here
		''
	);


?>

<?	} ?>