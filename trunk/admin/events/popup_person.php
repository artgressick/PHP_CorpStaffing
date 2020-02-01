<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;

		if(!isset($_REQUEST['chrSearch'])) { $_REQUEST['chrSearch'] = ''; }
?>
	<div class="header3" style='padding-bottom:10px;'>Search for User &nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='Close Window' onclick='javascript:window.close();' /></div>
	<form action="" method="get" id="idForm" style="padding:0px; margin:0px;">
		<table cellspacing="0" cellpadding="0" class='filter'>
			<tr>
				<td class='filterRight'>
					<?=form_button(array('type'=>'submit','name'=>'filter','value'=>'Filter','style'=>'float:right;'))?>
					<?=form_text(array('name'=>'chrSearch','nocaption'=>'true','value'=>($_REQUEST['chrSearch'] != "" ? $_REQUEST['chrSearch'] : ''),'style'=>'float:right;'))?>
					<?=form_text(array('type'=>'hidden','name'=>'d','nocaption'=>'true','value'=>$_REQUEST['d']))?>
				</td>
			</tr>
		</table>
	</form>	
<?

		if($_REQUEST['chrSearch'] != "") { 
			$results = db_query("SELECT ID,chrKEY,chrLast,chrFirst
				FROM People
				WHERE !bDeleted AND idPersonStatus=3 AND !bAdmin
				". ($_REQUEST['chrSearch'] != "" ? " AND ((chrFirst like '%".$_REQUEST['chrSearch'] ."%') OR (chrLast like '%".$_REQUEST['chrSearch'] ."%') OR (chrEmail like '%".$_REQUEST['chrSearch'] ."%'))" : '') ."
				ORDER BY chrLast,chrFirst
			","getting people");
					
?>

	<table id='Events' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th class='ListHeadSortOn sorttable_sorted' >Last Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
			<th>First Name&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
		</tr>
		</thead>
		
<?			$count = 0;
			while($row = mysqli_fetch_assoc($results)) { ?>
		<tr id='Eventstr<?=$row['ID']?>' class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>' 
			onmouseover='RowHighlight("Eventstr<?=$row['ID']?>");' onmouseout='UnRowHighlight("Eventstr<?=$row['ID']?>");'>
			<td onclick='associate(<?=$row['ID']?>,"<?=$row['chrFirst']?>","<?=$row['chrLast']?>");'><?=$row['chrLast']?></td>
			<td onclick='associate(<?=$row['ID']?>,"<?=$row['chrFirst']?>","<?=$row['chrLast']?>");'><?=$row['chrFirst']?></td>
		</tr>
<?			} ?>


	</table>
	
<?		}
	} ?>