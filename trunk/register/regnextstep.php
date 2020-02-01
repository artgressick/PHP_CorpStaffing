<?
	include('_controller.php');
		
	function sitm() { 
		global $BF;
?>
	<div class='header2'>Registration - Next Step</div>
	<?=messages()?>
		<p class="header4">Thank you <?=$_SESSION['chrFirst']?> <?=$_SESSION['chrLast']?> for registering with Apple Staffing.</p>

		<p>For security reason we ask that you verify your E-mail address.</p>
		
		<p>Within the next 24 hours (usually instantly) you will receive an E-mail at <strong><?=$_SESSION['chrEmail']?></strong> with a link to verify this account.</p>

<?	} ?>