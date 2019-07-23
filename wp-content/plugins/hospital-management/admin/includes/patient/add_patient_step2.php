<?php 
	$role='patient';
	$patient_id=0;
	if(isset($_REQUEST['patient_id']))
	$patient_id=$_REQUEST['patient_id'];
	$patient_no=get_user_meta($patient_id, 'patient_id', true);
	if(isset($_POST['save_patient_step2']))
	{
		$guardian_info = MJ_hmgt_get_guardianby_patient($_REQUEST['patient_id']);
		$guardian_data=array('guardian_id'=>$_POST['guardian_id'],
						'patient_id'=>$_REQUEST['patient_id'],
						'first_name'=>MJ_hmgt_strip_tags_and_stripslashes($_POST['first_name']),
						'middle_name'=>MJ_hmgt_strip_tags_and_stripslashes($_POST['middle_name']),
						'last_name'=>MJ_hmgt_strip_tags_and_stripslashes($_POST['last_name']),
						'gr_gender'=>$_POST['gender'],
						'gr_address'=>MJ_hmgt_strip_tags_and_stripslashes($_POST['address']),
						'gr_city'=>MJ_hmgt_strip_tags_and_stripslashes($_POST['city_name']),
						'gr_state'=>MJ_hmgt_strip_tags_and_stripslashes($_POST['state_name']),
						'gr_country'=>MJ_hmgt_strip_tags_and_stripslashes($_POST['country_name']),
						'gr_zipcode'=>$_POST['zip_code'],
						'gr_phone'=>$_POST['phone'],
						'gr_mobile'=>$_POST['mobile'],						
						'gr_relation'=>MJ_hmgt_strip_tags_and_stripslashes($_POST['guardian_realtion']),
						'image'=>MJ_hmgt_strip_tags_and_stripslashes($_POST['hmgt_user_avatar']),
						'inpatient_create_date'=>date("Y-m-d H:i:s"),'inpatient_create_by'=>get_current_user_id());
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$guardian_info = MJ_hmgt_get_guardianby_patient($_REQUEST['patient_id']);
			if($guardian_info!="")
			{			
				$image_url=$guardian_data['image'];
			        $ext=MJ_hmgt_check_valid_extension($image_url);
			        if(!$ext == 0)
			         {
						 	
						$result=MJ_hmgt_update_guardian($guardian_data,$_REQUEST['patient_id']);
					
						 if($result)
						 {	
							
							 wp_redirect ( admin_url () . 'admin.php?page=hmgt_patient&tab=addpatient_step3&action=edit&patient_id='.$_REQUEST['patient_id']);	
						 
						 }
					 }
					 
					  else {   ?>
				<div id="message" class="updated below-h2">
								<p><p><?php _e('Sorry, only JPG, JPEG, PNG & GIF files are allowed.','hospital_mgt');?></p></p>
										</div>
				<?php 
					
				   }
			}	
			else
			{
				
				 $image_url=$guardian_data['image'];
			        $ext=MJ_hmgt_check_valid_extension($image_url);
			        if(!$ext == 0)
			         {
					$result=MJ_hmgt_add_guardian($guardian_data,'');
						 if($result)
						 {
							 wp_redirect ( admin_url () . 'admin.php?page=hmgt_patient&tab=addpatient_step3&action=edit&patient_id='.$_REQUEST['patient_id']);		
						 }
					 }
					 
					  else {   ?>
				<div id="message" class="updated below-h2">
								<p><p><?php _e('Sorry, only JPG, JPEG, PNG & GIF files are allowed.','hospital_mgt');?></p></p>
										</div>
				<?php 
				   }
			}
		}
		else
		{
		   $image_url=$guardian_data['image'];
			$ext=MJ_hmgt_check_valid_extension($image_url);
			if(!$ext == 0)
			{
				$result=MJ_hmgt_add_guardian($guardian_data,'');
				 if($result)
				 {
					 wp_redirect ( admin_url () . 'admin.php?page=hmgt_patient&tab=addpatient_step3&patient_id='.$_REQUEST['patient_id']);		
				 }
			}
			else 
			{   ?>
				<div id="message" class="updated below-h2">
						<p><p><?php _e('Sorry, only JPG, JPEG, PNG & GIF files are allowed.','hospital_mgt');?></p></p></div>
			<?php 
		   }
		}
	}

    ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#guardian_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#birth_date').datepicker({
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',	
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
                    
                }); 
} );
</script>
<?php 	
if($active_tab == 'addpatient_step2')
{
	MJ_hmgt_browser_javascript_check();	
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$user_info = MJ_hmgt_get_guardianby_patient($patient_id);
	}
	?>
	<!-- PANAL BODY DIV start-->	
	<div class="panel-body">
		<form name="guardian_form" action="" method="post" class="form-horizontal" id="guardian_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="role" value="<?php echo $role;?>"  />
			<div class="header">	
				<h3 class="first_hed"><?php _e('Guardian Information','hospital_mgt');?></h3>
				<hr>
			</div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="guardian_number"><?php _e('Guardian Id','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="guardian_id" class="form-control " min="0" type="number" onKeyPress="if(this.value.length==6) return false;"
					value="<?php if($edit){ if(isset($user_info['guardian_id'])) { print $user_info['guardian_id']; } } elseif(isset($_POST['guardian_id'])) echo $_POST['guardian_id'];?>"   name="guardian_id">
				</div>

				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="patient_id"><?php _e('Patient Id','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					
					<input id="patient_no" class="form-control" type="text" 
					value="<?php if($edit){ echo get_user_meta($patient_id, 'patient_id', true);}elseif(isset($patient_no)) echo $patient_no;?>"
					name="patient_no" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="first_name"><?php _e('First Name','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ if(isset($user_info['first_name'])) { echo $user_info['first_name'];} }elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">
				</div>
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="middle_name"><?php _e('Middle Name','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" type="text" maxlength="50" value="<?php if($edit){ if(isset($user_info['middle_name'])) {  echo $user_info['middle_name'];}}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="last_name"><?php _e('Last Name','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="<?php if($edit){ if(isset($user_info['last_name'])) {  echo $user_info['last_name'];} }elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
				</div>
				 <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
				<?php $genderval = "male"; if($edit){  if(empty($user_info['gr_gender'])){ $genderval = "male"; }elseif(isset($user_info['gr_gender'])){ $genderval=$user_info['gr_gender']; } } elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
					<label class="radio-inline">
					 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval); ?>/><?php _e('Male','hospital_mgt');?>	
					</label>
					<label class="radio-inline">
					  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);?>/><?php _e('Female','hospital_mgt');?> 
					</label>	
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="guardian_realtion"><?php _e('Relation With Patient','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="guardian_realtion" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" type="text" maxlength="30" name="guardian_realtion" 
					value="<?php if($edit){ if(isset($user_info['gr_relation'])) {  echo $user_info['gr_relation'];} }elseif(isset($_POST['guardian_realtion'])) echo $_POST['guardian_realtion'];?>">
				</div>
			</div>
			<div class="header">
					<h3><?php _e('HomeTown Address Information','hospital_mgt');?></h3>
					<hr>
			</div> 
			
		    <div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="address"><?php _e('Home Town Address','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="address" class="form-control validate[custom[address_description_validation]]" type="text" maxlength="150" name="address" 
					value="<?php if($edit) { if(isset($user_info['gr_address'])) { echo $user_info['gr_address']; } } elseif(isset($_POST['address'])) echo $_POST['address'];?>">
				</div>
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="city_name"><?php _e('City','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="city_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
					value="<?php if($edit){ if(isset($user_info['gr_city'])) { echo $user_info['gr_city'];} }elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="state_name"><?php _e('State','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" 
					value="<?php if($edit){ if(isset($user_info['gr_state'])) {  echo $user_info['gr_state'];} }elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
				</div>
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="country_name"><?php _e('Country','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="country_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="country_name" 
					value="<?php if($edit){ if(isset($user_info['gr_country'])) {  echo $user_info['gr_country'];} }elseif(isset($_POST['country_name'])) echo $_POST['country_name'];?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="zip_code"><?php _e('Zip Code','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" type="text" maxlength="15" name="zip_code" 
					value="<?php if($edit){  if(isset($user_info['gr_zipcode'])) { echo $user_info['gr_zipcode'];} }elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
				</div>
			</div>
			<div class="header">
					<h3><?php _e('Contact Information','hospital_mgt');?></h3>
					<hr>
			</div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="mobile"><?php _e('Mobile Number','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="<?php if($edit){  if(isset($user_info['gr_mobile'])) {  echo $user_info['gr_mobile']	;} }elseif(isset($_POST['mobile'])) echo $_POST['mobile'];?>" name="mobile">
				</div>
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="phone"><?php _e('Phone','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="phone" class="form-control validate[custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="<?php if($edit){  if(isset($user_info['gr_phone'])) {  echo $user_info['gr_phone'];} }elseif(isset($_POST['phone'])) echo $_POST['phone'];?>" name="phone">
				</div>
			</div>
			<div class="header">
					<h3><?php _e('Other Information','hospital_mgt');?></h3>
					<hr>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="photo"><?php _e('Image','hospital_mgt');?></label>
				<div class="col-sm-3 margin_bottom_5px">
					<input type="text" id="hmgt_user_avatar_url" class="form-control" name="hmgt_user_avatar"  
					value="<?php if($edit) if(isset($user_info['image'])) if(trim($user_info['image']) != "")echo esc_url( $user_info['image'] );elseif(isset($_POST['hmgt_user_avatar'])) echo $_POST['hmgt_user_avatar']; ?>" readonly />
				</div>	
					<div class="col-sm-3">
						 <input id="upload_user_avatar_button" type="button" class="button" value="<?php _e( 'Upload image', 'hospital_mgt' ); ?>" />
						 <span class="description"><?php _e('Upload image', 'hospital_mgt' ); ?></span>
				
				</div>
				<div class="clearfix"></div>
				
				<div class="col-sm-offset-2 col-sm-8">
					 <div id="upload_user_avatar_preview" >
						 <?php
							if($edit) 
							{	
								if(isset($user_info) && isset($user_info['image']) && trim($user_info['image']) != "")
								{
									
									?>
									<img class="image_preview_css" src="<?php  echo esc_url( $user_info['image'] );?>" />
								
								<?php }
								
								else{?>
									<img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_guardian_thumb' ); ?>">
								<?php  }
							}
							else {
								?>
								<img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_guardian_thumb' ); ?>">
								<?php 
							}?>
					</div>
				</div>
			</div>
			<div class="col-sm-offset-2 col-sm-8">
				<a href="?page=hmgt_patient&tab=addpatient&action=edit&patient_id=<?php echo $patient_id; ?>">
				<input type="button" value="<?php if($edit){ _e('Back To Last Step','hospital_mgt'); }else{ _e('Back To Last Step','hospital_mgt');}?>" name="back_step" class="btn btn-success margin_bottom_5px"/>
				</a>
				<input type="submit" value="<?php if($edit){ _e('Save And Next Step','hospital_mgt'); }else{ _e('Save And Next Step','hospital_mgt');}?>" name="save_patient_step2" class="btn btn-success margin_bottom_5px"/>
				
			</div>			
		</form>
	</div>
	<!--END PANAL BODY DIV -->	
<?php 
}
?>