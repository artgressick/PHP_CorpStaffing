<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
?>
	<form action="" method="get" id="idForm" style="padding:0px; margin:0px;">
		<table cellpadding="0" border="0" cellspacing="0" class='Tabs' width='100%' style='padding-top:5px;padding-left:5px;'>
			<tr>
				<td class='<?=("All"==$_SESSION['char_people']?"Current":"")?>'><a href='index.php?chrChr=All' style="padding: 0 5px;">ALL</a></td>
<? 
			$char = 65;
			$end = 90;
			while ($char <= $end ) {
				$chrChr = chr($char++);
?>
				<td class='<?=($chrChr==$_SESSION['char_people']?"Current":"")?>'><a href='index.php?chrChr=<?=$chrChr?>' style="padding: 0 5px;"><?=$chrChr?></a></td>
<?
			}
?>
			</tr>
		</table>
	</form>	
<?

if(access_check('1')) {
	if ($_REQUEST['idStatus'] != 0) {
		$tableHeaders = array(
			'chrLast' 			=> array('displayName' => 'Last Name','default' => 'asc'),
			'chrFirst' 			=> array('displayName' => 'First Name'),
			'chrEmail' 			=> array('displayName' => 'Email Address'),
			'chrPersonStatus' 	=> array('displayName' => 'Account Status'),
			'opt_link'			=> array('address' => 'edit.php?key=','display' => 'Edit'),	
			'opt_del' 			=> 'chrFirst,chrLast'
		);
	} else {
		$tableHeaders = array(
			'chrLast' 			=> array('displayName' => 'Last Name','default' => 'asc'),
			'chrFirst' 			=> array('displayName' => 'First Name'),
			'chrEmail' 			=> array('displayName' => 'Email Address'),
			'chrPersonStatus' 	=> array('displayName' => 'Account Status'),
			'opt_link'			=> array('address' => 'edit.php?key=','display' => 'Edit'),	
		);
	}
} else {
	$tableHeaders = array(
		'chrLast' 			=> array('displayName' => 'Last Name','default' => 'asc'),
		'chrFirst' 			=> array('displayName' => 'First Name'),
		'chrEmail' 			=> array('displayName' => 'Email Address'),
		'chrPersonStatus' 	=> array('displayName' => 'Account Status'),
	);
}
	
	sortList('People', 		# Table Name
		$tableHeaders,		# Table Name
		$results,			# Query results
		'view.php?key=',	# The linkto page when you click on the row
		'width: 100%;border-left:none;border-right:none;border-bottom:none;', 		# Additional header CSS here
		''
	);


?>

<?	} ?>