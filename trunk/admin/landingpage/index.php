<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
		<form action="" method="post" id="idForm" onsubmit="return error_check()" style='padding:0; margin:0;'>
			<div class='innerbody'>
				<?=form_textarea(array('caption'=>'Landing Page','name'=>'txtHTML','cols'=>'75','rows'=>'30','style'=>'width:100%;height:389px;','value'=>decode($info['txtHTML'])))?>
				<br />
				<?=form_button(array('type'=>'submit','name'=>'update','value'=>'Update Information'))?>
			</div>
		</form>
<?
	}
?>