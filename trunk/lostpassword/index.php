<?php
	include('_controller.php');

	function sitm() {
	global $BF, $directions,$info,$totalerrors,$ERROR;		
?>
	
	<div class='header2'>Request New Password Submission Form</div>
	<div class='innerbody'>
		<?=messages()?>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<div class='directions2'><?=$directions?></div>

			<?=form_text(array('caption'=>'Email Address','required'=>'true','name'=>'chrEmail','size'=>'30','maxlength'=>'150','value'=>encode($info['chrEmail']),'style'=>($ERROR['chrEmail'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>

			<div class='FormButtons'>
				<?=(isset($totalerrors) ? ' <div class="FormErrorCount">'.$totalerrors.' Error(s) Detected.</div>' : "" )?>
				<?=form_button(array('type'=>'submit','value'=>'Submit'))?>
			</div>

		</form>
	</div>		
		
<?	} ?>