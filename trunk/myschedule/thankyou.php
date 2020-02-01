<?
	include('_controller.php');
		
	function sitm() { 
		global $BF;
?>
	<div class='header2'>My Schedule - Thank You!</div>
	<?=messages()?>
	<p>Within the next 24 hours (usually instantly) you will receive an e-mail at <strong><?=$_SESSION['chrEmail']?></strong> confirming your staffing options you selected.  Also contained with the e-mail is information as to what will happen next.</p>
	<p><?=form_button(array('type'=>'button','name'=>'Home','value'=>'Home','extra'=>'onclick="javascript:location.href=\''.$BF.'index.php\'";'))?></p>
<?	} ?>