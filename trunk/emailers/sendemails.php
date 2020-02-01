<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<div class='innerbody'>

	<div>Starting E-mails....</div>
	<div id="emaillog"></div>
	<iframe 
	src="<?=$BF?>emailers/_send_emails.php"
	width="1" height="1" style="border:none;" frameborder="0">
	</iframe>
	</div>
<?
	}
?>