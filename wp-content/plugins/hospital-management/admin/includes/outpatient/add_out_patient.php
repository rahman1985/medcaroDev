<?php 
$role='patient';
$patient_type='outpatient';
$obj_bloodbank=new MJ_hmgt_bloodbank();
$diagnosis_obj=new MJ_hmgt_dignosis();
$user_object=new MJ_hmgt_user();
?>
 <script type="text/javascript">
   jQuery(document).ready(function($) {
	$('#out_patient_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#doctor_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

	$('#symptoms').multiselect({
		nonSelectedText :'<?php _e('Select Symptoms','hospital_mgt'); ?>',
		includeSelectAllOption: true,
		selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
	 });
	 $('.tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
	 $.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
      $('.birth_date').datepicker({
     endDate: '+0d',
        autoclose: true
   });
	//user name not  allow space validation
	$('#username').keypress(function( e ) {
       if(e.which === 32) 
         return false;
    });
		
	 //------ADD DOCTOR AJAX----------
	   $('#doctor_form').on('submit', function(e) {
		e.preventDefault();
		
		var valid = $('#doctor_form').validationEngine('validate');
		
		var form = new FormData(this);
		var x = $("#doctor_cv")[0].files;
		var y = $("#education_certificate")[0].files;
		var z = $("#experience_cert")[0].files;
        form.append('doctor_cv',x);
        form.append('doctor_cv',y);
        form.append('doctor_cv',z);  
		$.ajax({
			 type:"POST",
			url: $(this).attr('action'),
			data:form,
			cache: false,
            contentType: false,
            processData: false,
			success: function(data)
			{   
				if(data=='2')
				{ 
					$('.show_msg1').css('display','none');
					$('.show_msg').css('display','block');
				}
				else if(data=='3')
				{ 
					$('.show_msg').css('display','none');
					$('.show_msg1').css('display','block');
				}
				else
				{
				  if(data!="")
				  { 
					   var json_obj = $.parseJSON(data);
						$('#doctor_form').trigger("reset");
						$('#doctors').append(json_obj[0]);
						$('.upload_user_avatar_preview').html('<img alt="" src="<?php echo get_option( 'hmgt_doctor_thumb' ); ?>">');
						$('.hmgt_user_avatar_url').val('');
						$('.modal').modal('hide');
						$('.show_msg').css('display','none');
						$('.show_msg1').css('display','none');
					}   
				}
			},
			error: function(data){
			}
		})
		
	});  
	$("body").on("click", ".add_more_report", function()
	{
		$(".diagnosissnosis_div").append('<div class="form-group"><label class="col-sm-2 control-label" for="diagnosis"><?php _e('Diagnosis Report','hospital_mgt');?></label><div class="col-sm-3"><input type="file" class="dignosisreport form-control file" name="diagnosis[]"></div><div class="col-sm-2"><input type="button" value="<?php _e('Delete','hospital_mgt') ?>" onclick="deleteParentElement(this)" class="remove_cirtificate btn btn-default"></div></div>');
	});				
	$("body").on("click", ".remove_cirtificate", function()
	{
	    alert("<?php _e('Do you really want to delete this record ?','hospital_mgt');?>");
		$(this).parent().parent().remove();
	});	
	$(".symptoms_alert").click(function()
	{	
		checked = $(".multiselect_validation_symtoms .dropdown-menu input:checked").length;
		if(!checked)
		{
		  alert("<?php _e('Please select atleast one Symtoms','hospital_mgt');?>");
		  return false;
		}	
	}); 	
});
	
</script>
<?php 	
if($active_tab == 'addoutpatient')
{
	MJ_hmgt_browser_javascript_check();
	 ?>
	 <!-- POP up code -->
	<div class="popup-bg" style="z-index:100000 !important;">
		<div class="overlay-content">
		<div class="modal-content">
		<div class="category_list">
		 </div>
		</div>
		</div> 
		
	</div>
	<!-- End POP-UP Code -->
	<?php 
    $edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$doctor=[];
		$edit=1;
		$user_info = get_userdata($_REQUEST['outpatient_id']);
		$doctordetail=MJ_hmgt_get_guardianby_patient($_REQUEST['outpatient_id']);
		$diagnosis=$diagnosis_obj->get_last_diagnosis_by_patient($_REQUEST['outpatient_id']);
		if(isset($doctordetail['doctor_id'])){
		$doctor = get_userdata($doctordetail['doctor_id']);
		}
	}
	else
	{
		$lastpatient_id=MJ_hmgt_get_lastpatient_id($role);
		$nodate=substr($lastpatient_id,0,-4);
		$patientno=substr($nodate,1);
		$patientno+=1;
		$newpatient='P'.$patientno.date("my");
	}
	?>      
    <div class="panel-body"> <!-- PANEL BODY DIV START -->
	   <!-- outpatient form start   -->
		<form name="out_patient_form" action="" method="post" class="form-horizontal" id="out_patient_form" enctype="multipart/form-data">
			 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="role" value="<?php echo $role;?>"  />
			<input type="hidden" name="patient_type" value="<?php echo $patient_type;?>"  />
			<input type="hidden" name="diagnosis_id" value="<?php if(!empty($diagnosis)) echo $diagnosis->diagnosis_id;?>"  />
			<input type="hidden" name="user_id" value="<?php if(isset($_REQUEST['outpatient_id'])) echo $_REQUEST['outpatient_id'];?>"  />
			<div class="header">	
					<h3 class="first_hed"><?php _e('Personal Information','hospital_mgt');?></h3>
					<hr>
			</div>		
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="roll_id"><?php _e('Patient Id','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="patient_id" class="form-control validate[required]" type="text" 
					value="<?php if($edit){ echo $user_info->patient_id;}else echo $newpatient;?>" readonly name="patient_id">
				</div>
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="first_name"><?php _e('First Name','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="middle_name"><?php _e('Middle Name','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" type="text"  maxlength="50" value="<?php if($edit){ echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
				</div>
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="last_name"><?php _e('Last Name','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="birth_date"><?php _e('Date of birth','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input class="form-control validate[required] birth_date" type="text"  name="birth_date" 
					value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($user_info->birth_date));}elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>" readonly>
				</div>
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="blood_group"><?php _e('Blood Group','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<?php if($edit){ $userblood=$user_info->blood_group; }elseif(isset($_POST['blood_group'])){$userblood=$_POST['blood_group'];}else{$userblood='';}?>
					<select id="blood_group" class="form-control" name="blood_group">
					<option value=""><?php _e('Select Blood Group','hospital_mgt');?></option>
					<?php foreach(MJ_hmgt_blood_group() as $blood){ ?>
							<option value="<?php echo $blood;?>" <?php selected($userblood,$blood);  ?>><?php echo $blood; ?> </option>
					<?php } ?>
				</select>
				</div>
			</div>		
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
				<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
					<label class="radio-inline">
					 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php _e('Male','hospital_mgt');?>
					</label>
					<label class="radio-inline">
					  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','hospital_mgt');?> 
					</label>
				</div>
			</div>
			<div class="header">
				<h3><?php _e('HomeTown Address Information','hospital_mgt');?></h3>
				<hr>
		    </div>
				<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="address"><?php _e('Home Town Address','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text"  name="address" 
					value="<?php if($edit){ echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
				</div>
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="city_name"><?php _e('City','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
					value="<?php if($edit){ echo $user_info->city_name;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="state_name"><?php _e('State','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" type="text"  maxlength="50" name="state_name" 
					value="<?php if($edit){ echo $user_info->state_name;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
				</div>
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="state_name"><?php _e('Country','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="country_name" class="form-control validate[custom[city_state_country_validation]]" type="text"  maxlength="50" name="country_name" 
					value="<?php if($edit){ echo $user_info->country_name;}elseif(isset($_POST['country_name'])) echo $_POST['country_name'];?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="zip_code"><?php _e('Zip Code','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" type="text" maxlength="15"  name="zip_code" 
					value="<?php if($edit){ echo $user_info->zip_code ;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
				</div>
			</div>
			<div class="header">
					<h3><?php _e('Contact Information','hospital_mgt');?></h3>
					<hr>
		    </div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="mobile"><?php _e('Mobile Number','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 margin_bottom_5px">
				<input type="text" value="<?php if($edit) { if(!empty($user_info->phonecode)){ echo $user_info->phonecode; }else{ ?>+<?php echo MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); }  }elseif(isset($_POST['phonecode'])){ echo $_POST['phonecode']; }else{ ?>+<?php echo MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); }?>"  class="form-control  validate[required] onlynumber_and_plussign" name="phonecode" maxlength="5">
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 has-feedback">
					<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="<?php if($edit){ echo $user_info->mobile;}elseif(isset($_POST['mobile'])) echo $_POST['mobile'];?>" name="mobile">					
				</div>
				 <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="phone"><?php _e('Phone','hospital_mgt');?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="phone" class="form-control validate[custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="<?php if($edit){ echo $user_info->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>" name="phone">
				</div>
			</div>
			<div class="header">
					<h3><?php _e('Login Information','hospital_mgt');?></h3>
					<hr>
		    </div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
					value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
				</div>
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="username"><?php _e('User Name','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="username" class="form-control validate[required,custom[username_validation]]" type="text" maxlength="30" name="username" 
					value="<?php if($edit){ echo $user_info->user_login;}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?>>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="password"><?php _e('Password','hospital_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<input id="password" class="form-control <?php if(!$edit){ echo 'validate[required,minSize[8]]'; }else{ echo 'validate[minSize[8]]'; }?>" type="password" maxlength="12" name="password" value="">
				</div>
			</div>
			<div class="header">
					<h3><?php _e('Other Information','hospital_mgt');?></h3>
					<hr>
		    </div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="doctor"><?php _e('Assign Doctor','hospital_mgt');?></label>
				<div class="col-sm-3 margin_bottom_5px">
					<?php if($edit){ if(!empty($doctor->ID)){ $doctorid=$doctor->ID;}else{ $doctorid=''; } }elseif(isset($_POST['doctor'])){$doctorid=$_POST['doctor'];}else{$doctorid='';}?>
					<select name="doctor" id="doctors" class="form-control">					
					<option ><?php _e('Select Doctor','hospital_mgt');?></option>
					<?php $get_doctor = array('role' => 'doctor');
						$doctordata=get_users($get_doctor);
						 if(!empty($doctordata))
						 {
							foreach($doctordata as $retrieved_data)
							{								
								?>
							<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($doctorid,$retrieved_data->ID);?>><?php echo $retrieved_data->display_name;?> - <?php echo MJ_hmgt_doctor_specialization_title($retrieved_data->ID); ?></option>
							<?php 
							}
						 }?>
						 
					</select>
				</div>
				<!-- Adddoctor Button -->
				 <div class="col-sm-2">				
				<a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal_add_doctor"> <?php _e('Add Doctor','hospital_mgt');?></a>
				
				</div>
			</div>
		
			<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){?>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient_convert"><?php  _e(' Convert into Inpatient','hospital_mgt');?></label>
					<div class="col-sm-3">
					<input type="checkbox"  name="patient_convert" value="inpatient">
					
					</div>
			</div>
			<?php }
			?>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="symptoms"><?php _e('Symptoms','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-3 multiselect_validation_symtoms margin_bottom_5px">
					<select class="form-control symptoms_list" multiple="multiple" name="symptoms[]" id="symptoms">
					<?php 
					$symptoms_category = $user_object->getPatientSymptoms();
					
					if(!empty($symptoms_category))
					{
						foreach ($symptoms_category as $retrive_data)
						{
							$symptoms_array=explode(",",$user_info->symptoms);
							?>
							<option value="<?php echo $retrive_data->ID; ?>" <?php if(in_array($retrive_data->ID,$symptoms_array)){ echo 'selected'; } ?>><?php echo $retrive_data->post_title; ?></option>
							<?php
						}
					}
					?>					
					</select>
					<br>					
				</div>
					<div class="col-sm-2"><button id="addremove" model="symptoms"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
			</div>
			<?php 
			if(!$edit)
			{ 
			?>	
			<div class="diagnosissnosis_div">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="diagnosis"><?php _e('Diagnosis Report','hospital_mgt');?></label>
					<div class="col-sm-3">
						<input type="file" class="form-control file dignosisreport" name="diagnosis[]">
					</div>
				</div>	
			</div>
			<?php
			}			 
			if($edit)
			{ 			
			 	$diagnosis_obj=new MJ_hmgt_dignosis(); 
				$diagnosisdata=$diagnosis_obj->get_diagnosis_outpatient($_REQUEST['outpatient_id']);
				if(!empty($diagnosisdata))
				{	
					?>
					<div class="diagnosissnosis_div">
					<?php
					foreach($diagnosisdata as $diagnosis)
					{
					?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="diagnosis"><?php _e('Diagnosis Report','hospital_mgt');?></label>
							<div class="col-sm-3">
								<input type="file" class="form-control file dignosisreport" name="diagnosis[]" value=''>
							</div>
							<div class="col-sm-2">
								<?php 
								if(!empty($diagnosis) && $diagnosis->attach_report!="")
								{
								?>
									<a href="<?php echo content_url().'/uploads/hospital_assets/'.$diagnosis->attach_report;?>" target="_blank" class="btn btn-default"><i class="fa fa-download"></i><?php _e('View Report','hospital_mgt');?></a>
									<input type="hidden" name="hidden_attach_report[]" value="<?php print  $diagnosis->attach_report ?>" >
								<?php
								}
								else
								{
									?>
									<a href="#" class="btn btn-default"><i class="fa fa-download"></i><?php _e('No Report','hospital_mgt');?></a>
									<?php 
								}
								?>
							</div>
						</div>
					<?php
					}
					?>
					</div>		
				<?php
				}
				else
				{
					?>
					<div class="diagnosissnosis_div">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="diagnosis"><?php _e('Diagnosis Report','hospital_mgt');?></label>
							<div class="col-sm-3">
								<input type="file" class="form-control file dignosisreport" name="diagnosis[]">
							</div>
						</div>	
					</div>
					<?php
				}			
			}
			?>	
			<div class="form-group">			
				<div class="col-sm-2">
				</div>
				<div class="col-sm-2">
					<input type="button" value="<?php _e('Add More Report','hospital_mgt') ?>" name="add_more_report" class="add_more_report btn btn-default">
				</div>
			</div>
		
				<div class="form-group">
				<label class="col-sm-2 control-label" for="photo"><?php _e('Image','hospital_mgt');?></label>
				<div class="col-sm-3 margin_bottom_5px">
					<input type="text" id="hmgt_user_avatar_url" class="form-control" name="hmgt_user_avatar"  
					value="<?php if($edit)echo esc_url( $user_info->hmgt_user_avatar );elseif(isset($_POST['hmgt_user_avatar'])) echo $_POST['hmgt_user_avatar']; ?>" readonly />
				</div>	
					<div class="col-sm-3">
						 <input id="upload_user_avatar_button" type="button" class="button" value="<?php _e( 'Upload image', 'hospital_mgt' ); ?>" />
						 <br>
						 <span class="description"><?php _e('Upload only JPG, JPEG, PNG & GIF image', 'hospital_mgt' ); ?></span>
				
				</div>
				<div class="clearfix"></div>
				
				<div class="col-sm-offset-2 col-sm-8">
						 <div id="upload_user_avatar_preview" >
							 <?php if($edit) 
								{
								if($user_info->hmgt_user_avatar == "")
								{?>
								<img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">
								<?php }
								else {
									?>
								<img class="image_preview_css" src="<?php if($edit)echo esc_url( $user_info->hmgt_user_avatar ); ?>" />
								<?php 
								}
								}
								else {
									?>
									<img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">
									<?php 
								}?>
						</div>
			 </div>
			</div>
			<div class="col-sm-offset-2 col-sm-8">
				
				<input type="submit" value="<?php if($edit){ _e('Save Patient','hospital_mgt'); }else{ _e('Save Patient','hospital_mgt');}?>" name="save_outpatient" class="btn btn-success symptoms_alert"/>
			</div>
		</form>
    <!-- outpatient form END -->
    </div>
     <?php 
} ?>

	<!----------ADD Doctor Form code start------------->	
	<!----ADD Doctor Form Popup div ----->
	<div class="modal fade" id="myModal_add_doctor" role="dialog" style="overflow:scroll;"><!----MODEL div START ----->
		<div class="modal-dialog modal-lg"><!----MODEL DIALOG div START ----->
		   <div class="modal-content"><!----MODEL CONTENT div START ----->
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h3 class="modal-title"><?php _e('Add Doctor','hospital_mgt');?></h3>
				</div>
				<div id="message" class="updated below-h2 show_msg">
					<p>
					<?php _e('Sorry, only JPG, JPEG, PNG & GIF And BMP files are allowed.','hospital_mgt');?>
					</p>
				</div>
				<div id="message" class="updated below-h2 show_msg1">
					<p>
					<?php _e('Sorry, only PDF files are allowed.','hospital_mgt');?>
					</p>
				</div>
				<div class="modal-body"><!----MODEL BODY div START ----->				
					<form name="doctor_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal" id="doctor_form" enctype="multipart/form-data">
			   
					<input type="hidden" name="action" value="Mj_hmgt_save_doctor_popup_form">
					<input type="hidden" name="role" value="doctor"  />
					<div class="header">	
						<h3 class="first_hed"><?php _e('Personal Information','hospital_mgt') ?></h3>
						<hr>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="first_name"><?php _e('First Name','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="first_name">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="middle_name"><?php _e('Middle Name','hospital_mgt');?></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="" name="middle_name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="last_name"><?php _e('Last Name','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="" name="last_name">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="birth_date"><?php _e('Date of birth','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input  class="form-control validate[required] birth_date"  type="text"   name="birth_date" 
							value="" readonly>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
						
							<label class="radio-inline">
							 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php _e('Male','hospital_mgt');?>
							</label>
							<label class="radio-inline">
							  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','hospital_mgt');?> 
							</label>
						</div>
					</div>
					
				
					<div class="header">
						<h3><?php _e('Office Address Information','hospital_mgt') ?></h3>
						<hr>
					</div>
						<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="address"><?php _e('Office Address','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text"  name="office_address" 
							value="">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="city_name"><?php _e('City','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
							value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="state_name"><?php _e('State','hospital_mgt');?></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="" class="form-control validate[custom[city_state_country_validation]]" type="text" maxlength="50" name="state_name" 
							value="">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="country_name"><?php _e('Country','hospital_mgt');?></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="country_name" 
							value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="zip_code"><?php _e('Zip Code','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="" class="form-control  validate[required,custom[onlyLetterNumber]]" type="text" maxlength="15" name="zip_code" 
							value="">
						</div>
					</div>
					<div class="header">
						<h3><?php _e('HomeTown Address Information','hospital_mgt') ?></h3>
						<hr>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="address"><?php _e('Home Town Address','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="address" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="150" name="address" 
							value="">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="home_city_name"><?php _e('City','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="home_city_name" 
							value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="home_state_name"><?php _e('State','hospital_mgt');?></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="home_state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="home_state_name" 
							value="">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="country_name"><?php _e('Country','hospital_mgt');?></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="country_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="country_name" 
							value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="zip_code"><?php _e('Zip Code','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" type="text" maxlength="15"  name="home_zip_code" 
							value="<?php if($edit){ echo $user_info->home_zip_code;}elseif(isset($_POST['home_zip_code'])) echo $_POST['home_zip_code'];?>">
						</div>
					</div>
					<div class="header">
						<h3><?php _e('Education Information','hospital_mgt') ?></h3>
						<hr>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="birth_date"><?php _e('Degree','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="doc_degree" class="form-control validate[required,custom[popup_category_validation]]" maxlength="500" type="text"  name="doc_degree" 
							value="">
						</div>
					</div>	
					<div class="header">
						<h3><?php _e('Contact Information','hospital_mgt') ?></h3>
						<hr>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="mobile"><?php _e('Mobile Number','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-1 col-md-1 col-sm-2 col-xs-12 margin_bottom_5px">					
							<input type="text" value="<?php if(isset($_POST['phonecode'])){ echo $_POST['phonecode']; }else{ ?>+<?php echo MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); }?>"  class="form-control  validate[required] onlynumber_and_plussign" name="phonecode" maxlength="5">
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 has-feedback">
							<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="" name="mobile">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="phone"><?php _e('Phone','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="phone" class="form-control validate[custom[phone_number]] text-input" minlength="" maxlength="15" type="text" value="" name="phone">
						</div>
					</div>
					<div class="header">
						<h3><?php _e('Login Information','hospital_mgt');?></h3>
						<hr>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
							value="">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="username"><?php _e('User Name','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="username" class="form-control validate[required,custom[username_validation]]" type="text" maxlength="30"  name="username" 
							value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="password"><?php _e('Password','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="password" class="form-control validate[required,minSize[8]]" type="password"    maxlength="12" name="password" value="">
						</div>
					</div>
					<div class="header">
						<h3><?php _e('Other Information','hospital_mgt');?></h3>
						<hr>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="department"><?php _e('Department','hospital_mgt');?></label>
						<div class="col-sm-3 margin_bottom_5px">
						
							<select name="department" class="form-control" id="department">
							<option><?php _e('select Department','hospital_mgt');?></option>
							<?php 
							$departmentid=0;
							$department_array = $user_object->get_staff_department();
							 if(!empty($department_array))
							 {
								foreach ($department_array as $retrieved_data){?>
									<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($departmentid,$retrieved_data->ID);?>><?php echo $retrieved_data->post_title;?></option>
								<?php }
							 }
							?>
							</select>
						</div>
						<div class="col-sm-2"><button id="addremove" model="department"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
					</div>	
					<div class="form-group">
						<label class="col-sm-2 control-label" for="birth_date"><?php _e('Specialization','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3 margin_bottom_5px">
							<select class="form-control validate[required]" 
							id="specialization" name="specialization" >
								<option value=""><?php _e('Select Specialization','hospital_mgt');?></option>
								<?php 
								$specializeid=0;
								$specialize_array = $user_object->get_doctor_specilize();
								 if(!empty($specialize_array))
								 {
									foreach ($specialize_array as $retrieved_data){?>
										<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($specializeid,$retrieved_data->ID);?>><?php echo $retrieved_data->post_title;?></option>
									<?php }
								 }?>
								</select>
						</div>
						<div class="col-sm-2"><button id="addremove" model="specialization"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
					</div>	
					<div class="form-group">
						<label class="col-sm-2 control-label" for="visiting_fees"><?php _e('Visiting Charge','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
						<div class="col-sm-3">
							<input id="doc_degree" class="form-control" type="number" min="0" onKeyPress="if(this.value.length==8) return false;"name="visiting_fees" step="0.01" value="">
						</div>
						<div class="col-sm-2 padding_left_0" style="float:left;padding-top: 8px;font-size: 13px;">
							<?php _e('/ Per Session','hospital_mgt');?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for=""><?php _e('Visiting Charge Tax','hospital_mgt');?></label>
						<div class="col-sm-3">
							<select  class="form-control tax_charge"  name="visiting_fees_tax[]" multiple="multiple">		<?php										
								$obj_invoice= new MJ_hmgt_invoice();
								$hmgt_taxs=$obj_invoice->get_all_tax_data();	
								
								if(!empty($hmgt_taxs))
								{
									foreach($hmgt_taxs as $entry)
									{							
										?>
										<option value="<?php echo $entry->tax_id; ?>"><?php echo $entry->tax_title;?>-<?php echo $entry->tax_value;?></option>
									<?php 
									}
								}
								?>
							</select>		
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for=""><?php _e('Consulting Charge','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
						<div class="col-sm-3">
							<input id="" class="form-control" type="number" min="0" onKeyPress="if(this.value.length==8) return false;"  name="consulting_fees" step="0.01"
							value="">
						</div>
						<div class="col-sm-2 padding_left_0" style="float:left;padding-top: 8px;font-size: 13px;">
							<?php _e('/ Per Session','hospital_mgt');?>
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-2 control-label" for="visiting_fees"><?php _e('Consulting Charge Tax','hospital_mgt');?></label>
						<div class="col-sm-3">
							<select  class="form-control tax_charge" name="consulting_fees_tax[]" multiple="multiple">		<?php
								$obj_invoice= new MJ_hmgt_invoice();
								$hmgt_taxs=$obj_invoice->get_all_tax_data();	
								
								if(!empty($hmgt_taxs))
								{
									foreach($hmgt_taxs as $entry)
									{							
										?>
										<option value="<?php echo $entry->tax_id; ?>"><?php echo $entry->tax_title;?>-<?php echo $entry->tax_value;?></option>
										<?php 
									}
								}
								?>
							</select>		
						</div>
					</div>
				
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php _e('Image','hospital_mgt');?></label>
						<div class="col-sm-3 margin_bottom_5px">
							<input type="text"  class="form-control hmgt_user_avatar_url" name="hmgt_user_avatar" readonly 
							 />
						</div>	
						<div class="col-sm-4">
							 <input  type="button" class="button upload_user_avatar_button" value="<?php _e( 'Upload image', 'hospital_mgt' ); ?>" />
							 <br>
							 <span class="description"><?php _e('Upload only JPG, JPEG, PNG & GIF image', 'hospital_mgt' ); ?></span>
						</div>
						<div class="clearfix"></div>
						
						<div class="col-sm-offset-2 col-sm-8">
							<div class="upload_user_avatar_preview" >	                     
								<img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_doctor_thumb' ); ?>">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="document"><?php _e('Curriculum Vitae','hospital_mgt');?></label>
						<div class="col-sm-3">
							<input type="file" class="form-control file" id="doctor_cv" name="doctor_cv" >
							<input type="hidden" name="hidden_cv" value="">
							<p class="help-block"><?php _e('Upload document in PDF','hospital_mgt');?></p> 
						</div>
						<div class="col-sm-2">
							<?php if(isset($user_info->doctor_cv) && $user_info->doctor_cv!=""){?>
							<a href="<?php echo content_url().'/uploads/hospital_assets/'.$user_info->doctor_cv;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php _e('Curriculum Vitae','hospital_mgt');?></a>
							<?php } ?>
							 
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="document"><?php _e('Education Certificate','hospital_mgt');?></label>
						<div class="col-sm-3">
							<input type="file" class="form-control file" name="education_certificate" id="education_certificate">
							<input type="hidden" name="hidden_education_certificate" value="">
							<p class="help-block"><?php _e('Upload document in PDF','hospital_mgt');?></p> 
						</div>
						<div class="col-sm-2">
							<?php if(isset($user_info->edu_certificate) && $user_info->edu_certificate!=""){?>
							<a href="<?php echo content_url().'/uploads/hospital_assets/'.$user_info->edu_certificate;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php _e('Education Certificate','hospital_mgt');?></a>
							<?php } ?>
							 
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="document"><?php _e('Experience Certificate','hospital_mgt');?></label>
						<div class="col-sm-3">
							<input type="file" class="form-control file" name="experience_cert" id="experience_cert" >
							<input type="hidden" name="hidden_exp_certificate" value="">
							<p class="help-block"><?php _e('Upload document in PDF','hospital_mgt');?></p> 
						</div>
						<div class="col-sm-2">
							<?php if(isset($user_info->exp_certificate) && $user_info->exp_certificate!=""){?>
							<a href="<?php echo content_url().'/uploads/hospital_assets/'.$user_info->exp_certificate;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php _e('Experience Certificate','hospital_mgt');?></a>
							<?php } ?>
							 
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-8">
						<input type="submit" value="<?php _e('Save Doctor','hospital_mgt');?>" name="save_doctor" class="btn btn-success"/>
					</div>
				</form>				
				</div><!----MODEL BODY div END ----->
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal"><?php  _e('Close','hospital_mgt');?></button>
				</div>
			</div><!----MODEL CONTENT div END ----->
		</div><!----MODEL DIALOG div END ----->
	</div><!----MODEL div END ----->
<!----end rinkal ----->