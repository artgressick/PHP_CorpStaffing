<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info,$totalerrors,$ERROR;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="">
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tcleft">
				
						<?=form_text(array('caption'=>'First Name','required'=>'true','name'=>'chrFirst','size'=>'30','maxlength'=>'100','value'=>encode($info['chrFirst']),'style'=>($ERROR['chrFirst'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
						<?=form_text(array('caption'=>'Last Name','required'=>'true','name'=>'chrLast','size'=>'30','maxlength'=>'100','value'=>encode($info['chrLast']),'style'=>($ERROR['chrLast'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
						<?=form_text(array('caption'=>'Email Address','required'=>'true','name'=>'chrEmail','size'=>'30','maxlength'=>'150','value'=>encode($info['chrEmail']),'style'=>($ERROR['chrEmail'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
						<?=form_text(array('caption'=>'Job Title','required'=>'true','name'=>'chrJobTitle','size'=>'30','maxlength'=>'200','value'=>encode($info['chrJobTitle']),'style'=>($ERROR['chrJobTitle'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
<?						
						$q = "SELECT ID, chrShirtSize AS chrRecord FROM ShirtSizes ORDER BY intOrder";
						$shirtsizes = db_query($q, "Getting Shirt Sizes");
?>
						<?=form_select($shirtsizes,array('caption'=>'Shirt Size','required'=>'true','name'=>'idTshirt','value'=>$info['idTshirt'],'style'=>($ERROR['idTshirt'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
						<?=form_text(array('caption'=>'Office Phone','required'=>'true','name'=>'chrOfficePhone','size'=>'30','maxlength'=>'30','value'=>encode($info['chrOfficePhone']),'style'=>($ERROR['chrOfficePhone'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
						<?=form_text(array('caption'=>'Mobile Phone','required'=>'true','name'=>'chrMobilePhone','size'=>'30','maxlength'=>'30','value'=>encode($info['chrMobilePhone']),'style'=>($ERROR['chrMobilePhone'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
<?						
						$q = "SELECT ID, chrMobileCarrier AS chrRecord FROM MobileCarriers WHERE !bDeleted ORDER BY chrMobileCarrier";
						$mobilecarriers = db_query($q,"getting mobile carriers");
?>
						<?=form_select($mobilecarriers,array('caption'=>'Mobile Carrier','required'=>'true','name'=>'idMobileCarrier','value'=>$info['idMobileCarrier'],'style'=>($ERROR['idMobileCarrier'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
<?					
						$q = "SELECT ID, chrLocale AS chrRecord FROM Locales WHERE !bDeleted ORDER BY intOrder, chrLocale";
						$states = db_query($q,"getting states");
?>
						<?=form_select($states,array('caption'=>'State / Province','required'=>'US / Canada','name'=>'idLocale','value'=>$info['idLocale'],'style'=>($ERROR['idLocale'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
<?					
						$q = "SELECT ID, chrCountry AS chrRecord FROM Countries WHERE !bDeleted ORDER BY intOrder, chrCountry";
						$countries = db_query($q,"getting countries");
?>
						<?=form_select($countries,array('caption'=>'Country','required'=>'true','name'=>'idCountry','value'=>$info['idCountry'],'style'=>($ERROR['idCountry'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
	
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
<?					
						$q = "SELECT ID, chrEmployeeType AS chrRecord FROM EmployeeTypes ORDER BY intOrder, chrEmployeeType";
						$employeetypes = db_query($q,"getting employee types");
?>
						<?=form_select($employeetypes,array('caption'=>'Employee Type','required'=>'true','name'=>'idEmployeeType','value'=>$info['idEmployeeType'],'style'=>($ERROR['idEmployeeType'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
<?					
						$q = "SELECT ID, chrDepartment AS chrRecord FROM Departments WHERE !bDeleted ORDER BY chrDepartment";
						$departments = db_query($q,"getting departments");
?>
						<?=form_select($departments,array('caption'=>'Department','required'=>'true','name'=>'idDepartment','value'=>$info['idDepartment'],'style'=>($ERROR['idDepartment'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>

						<?=form_text(array('caption'=>'Password','type'=>'password','required'=>'Required if Changing','name'=>'chrPassword','size'=>'30','maxlength'=>'100','value'=>'','title'=>'Enter New Password','style'=>($ERROR['chrPasswords'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
						<?=form_text(array('caption'=>'Confirm Password','type'=>'password','required'=>'Required if Changing','name'=>'chrPassword2','size'=>'30','maxlength'=>'100','value'=>'','style'=>($ERROR['chrPasswords'] ? 'background:#FFDFE6;' : 'background:#FFF;')))?>
					</td>
				</tr>
			</table>
			<div class='FormButtons'>
				<?=(isset($totalerrors) ? ' <div class="FormErrorCount">'.$totalerrors.' Error(s) Detected.</div>' : "" )?>
				<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
			</div>
		</form>
	</div>

<?
	}
?>