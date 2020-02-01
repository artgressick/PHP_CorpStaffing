<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
?>
	<form action="" method="post" id="idForm" style="padding:0px; margin:0px;">
<?
	$tableHeaders = array(
		'chrEmployeeType' 	=> array('displayName'=>'Employee Type Name'),
		'opt_other'			=> 'order',
		'opt_del' 			=> 'chrEmployeeType'
	);
	
	sortList('EmployeeTypes',		# Table Name
		$tableHeaders,			# Table Name
		$results,				# Query results
		'edit.php?key=',		# The linkto page when you click on the row
		'width: 100%;border-top:none;border-right:none;border-left:none;', 			# Additional header CSS here
		''
	);
?>
	<div class='FormButtons' style='padding:5px;'>
		<?=form_button(array('type'=>'submit','name'=>'submit','value'=>'Save / Refresh Page'))?>
	</div>
	</form>
<?	} ?>