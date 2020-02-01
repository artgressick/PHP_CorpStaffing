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
										
						<?=form_text(array('caption'=>'Station Name','required'=>'true','name'=>'chrStation','size'=>'30','maxlength'=>'100','value'=>encode($info['chrStation'])))?>
						<?=form_text(array('caption'=>'Station Number','required'=>'true','name'=>'chrNumber','size'=>'5','maxlength'=>'10','value'=>encode($info['chrNumber'])))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
					
<?					
						$q = "SELECT ID, chrStationType AS chrRecord FROM StationTypes WHERE !bDeleted ORDER BY chrStationType";
						$results = db_query($q,"getting Station Types");
?>
						<?=form_select($results,array('caption'=>'Station Type','required'=>'true','name'=>'idStationType','value'=>$info['idStationType']))?>
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