<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tcleft">
										
						<?=form_text(array('caption'=>'Station Name','required'=>'true','name'=>'chrStation','size'=>'30','maxlength'=>'100'))?>
						<?=form_text(array('caption'=>'Station Number','required'=>'true','name'=>'chrNumber','size'=>'5','maxlength'=>'10'))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
					
<?					
						$q = "SELECT ID, chrStationType AS chrRecord FROM StationTypes WHERE !bDeleted ORDER BY chrStationType";
						$results = db_query($q,"getting Station Types");
?>
						<?=form_select($results,array('caption'=>'Station Type','required'=>'true','name'=>'idStationType'))?>
<?					
						$q = "SELECT ID, chrLocation AS chrRecord FROM Locations WHERE !bDeleted ORDER BY chrLocation";
						$results = db_query($q,"getting locations");
?>
						<?=form_select($results,array('caption'=>'Location','required'=>'true','name'=>'idLocation'))?>

					</td>
				</tr>
			</table>
			<div class='FormButtons'>
				<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
			</div>
		</form>
	</div>
<?
	}
?>