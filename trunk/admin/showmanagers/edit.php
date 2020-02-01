<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tcleft">
<?					
						$q = "SELECT ID, CONCAT(chrLast,', ',chrFirst) AS chrRecord FROM People WHERE !bDeleted AND idPersonStatus=3 ORDER BY chrLast, chrFirst";
						$results = db_query($q,"getting People");
?>
						<?=form_select($results,array('caption'=>'Manager Name','name'=>'idPerson','value'=>$info['idPerson'],'display'=>'true'))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">

						&nbsp;					

					</td>
				</tr>
			</table>
			<div class='FormButtons'>
				<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
			</div>
		</form>
	</div>
<?
	}
?>