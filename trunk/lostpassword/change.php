<?php
	include('_controller.php');

	function sitm() {
	global $BF, $directions,$info,$totalerrors,$ERROR;		
?>
	
	<div class='header2'>Create New Password</div>
	<div class='innerbody'>
		<?=messages()?>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<div class='directions2'><?=$directions?></div>

						<?=form_text(array('caption'=>'Password','type'=>'password','required'=>'true','name'=>'chrPassword1','size'=>'30','maxlength'=>'100','value'=>'','title'=>'Enter New Password','style'=>($ERROR['chrPassword1'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
						<?=form_text(array('caption'=>'Confirm Password','type'=>'password','required'=>'true','name'=>'chrPassword2','size'=>'30','maxlength'=>'100','value'=>'','style'=>($ERROR['chrPassword2'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>

			<div class='FormButtons'>
				<?=(isset($totalerrors) ? ' <div class="FormErrorCount">'.$totalerrors.' Error(s) Detected.</div>' : "" )?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
				<?=form_button(array('type'=>'submit','value'=>'Submit'))?>
			</div>

		</form>
	</div>		
		
<?	} ?>