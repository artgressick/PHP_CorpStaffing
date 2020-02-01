<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info,$ERROR;
?>
	<div class='innerbody'>
		<form method='post' action='' enctype="multipart/form-data" onsubmit="return error_check()">
			<div id='editEmail'>
<?	
				$emailtypes = db_query("SELECT ID,chrSendTo AS chrRecord FROM EmailerTypes WHERE !bDeleted AND chrEmailer='admin'","Getting Emailer Types");
?>
				<?=form_select($emailtypes,array('caption'=>'Send To','required'=>'true','name'=>'chrTo','extra'=>'onchange=showHideCheckbox(this)'))?>

				<?=form_text(array('caption'=>'Subject','required'=>'true','name'=>'chrSubject','size'=>'40','maxlength'=>'75'))?>

				<?=form_text(array('caption'=>'Attachment','required'=>'true','name'=>'chrAttachment','type'=>'file'))?>

				<div id='sendSchedule' style='display: none;'>
					<?=form_checkbox(array('nocaption'=>'true','title'=>'Send Schedule with Email','name'=>'bSendSched'))?>
				</div>

				<?=form_textarea(array('caption'=>'Email Contents','required'=>'true','name'=>'txtSourceCode','cols'=>'75','rows'=>'30'))?>

                <div style='margin-top: 10px;' class='AdminFormButton'>
					<input name="submit" id='preview' disabled='disabled' type="button" value="Preview Information" onclick='change("preview");' />
					<input name="submit" type="submit" value="Send Email" />
				</div>

			</div>
			<div id='previewEmail' style='display: none;'>

				<div class='InputField'>
					<div class='InputLabel'><strong>To</strong>:</div>
                	<div id='emailTo'></div>
				</div>
				<div class='InputField' style='margin-top: 10px;'>
					<div class='InputLabel'><strong>Subject</strong>:</div>
                	<div id='emailSubject'></div>
				</div>				

				<div class='InputField' style='margin-top: 10px;'>
					<div class='InputLabel'><strong>Email Content</strong>:</div>
					<div id='emailBody' style='border: 1px solid #999;'></div>
				</div>

                <div class='AdminFormButton' style='margin-top: 10px;'>
					<input name="submit" type="button" value="Edit Information" onclick='change("edit");' />
				</div>
			</div>
		

				
		

		</form>
	</div>

<?
	}
?>