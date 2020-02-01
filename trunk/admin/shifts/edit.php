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
										
						<?=form_text(array('caption'=>'Date','required'=>'Required, Format: MM/DD/YYYY','name'=>'dDate','size'=>'15','maxlength'=>'20','value'=>date('n/j/Y',strtotime($info['dDate']))))?>
						<?=form_text(array('caption'=>'Begin Time','required'=>'Required, Format: 16:00','name'=>'tBegin','size'=>'15','maxlength'=>'20','value'=>date('H:i',strtotime($info['tBegin']))))?>
						<?=form_text(array('caption'=>'End Time','required'=>'Required, Format: 16:00','name'=>'tEnd','size'=>'15','maxlength'=>'20','value'=>date('H:i',strtotime($info['tEnd']))))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
	
<?					
						$q = "SELECT ID, chrLocation AS chrRecord FROM Locations WHERE !bDeleted ORDER BY chrLocation";
						$results = db_query($q,"getting locations");
?>
						<?=form_select($results,array('caption'=>'Location','required'=>'true','name'=>'idLocation','value'=>$info['idLocation']))?>

	
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