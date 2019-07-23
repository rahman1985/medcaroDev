<?php 
$role='patient';
$patient_id=0;
if(isset($_REQUEST['patient_id']))
	$patient_id=$_REQUEST['patient_id'];
 $patient_no=get_user_meta($patient_id,'patient_id', true);
 $user_object=new MJ_hmgt_user();
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
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#admit_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
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
		 
	$('#admit_time').timepicki({
	 	show_meridian:false, 
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:15,		
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: true
});
		
	
	$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
      $('#birth_date').datepicker({
     endDate: '+0d',
        autoclose: true
		 
   }); 
		 var date = new Date();
           date.setDate(date.getDate()-0);
	  $.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
        $('#admit_date').datepicker({
	    startDate: date,
        autoclose: true
		 
   }); 
				 
				
		 //------ADD DOCTOR AJAX----------
	   $('#doctor_form').on('submit', function(e)
	   {
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
if($active_tab == 'addpatient_step3')
{
	MJ_hmgt_browser_javascript_check();
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{						
		$edit=1;
		$user_info = MJ_hmgt_get_guardianby_patient($_REQUEST['patient_id']);
		$doctordetail=MJ_hmgt_get_guardianby_patient($_REQUEST['patient_id']);
		$doctor = get_userdata($doctordetail['doctor_id']);
	}
	?>		
    <div class="panel-body"><!-- PANEL BODY DIV START -->
        <form name="admit_form" action="" method="post" class="form-horizontal" id="admit_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="role" value="<?php echo $role;?>"  />		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="admit_date"><?php _e('Admit Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="admit_date"  class="form-control validate[required] admit_date" type="text"  value="<?php if($edit){  if(isset($user_info['admit_date']))  { if(!empty($user_info['admit_date']!='0000-00-00')) { echo date(MJ_hmgt_date_formate(),strtotime($user_info['admit_date']));} } }elseif(isset($_POST['admit_date'])) echo $_POST['admit_date'];?>" name="admit_date">
			</div>
		</div>
	
		<div class="form-group">
			<label class="col-sm-2 control-label" for="admit_time"><?php _e('Admit Time','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="admit_time" class="form-control validate[required]" type="text" value="<?php if($edit){ if(isset($user_info['admit_time']))  { echo $user_info['admit_time'];} }elseif(isset($_POST['admit_time'])) echo $_POST['admit_time'];?>" 
				name="admit_time"   data-template="dropdown">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient_status"><?php _e('Patient Status','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8" >
				<?php if($edit){ if(isset($user_info['patient_status']))  {  $patient_status=$user_info['patient_status']; } else{ $patient_status=''; } }elseif(isset($_POST['patient_status'])){$patient_status=$_POST['patient_status'];}else{$patient_status='';}?>
				<select name="patient_status" class="form-control validate[required]" >
				<option><?php _e('Select Patient Status','hospital_mgt');?></option>
				<?php foreach(MJ_hmgt_admit_reason() as $reason)
				{?>
					<option value="<?php echo $reason;?>" <?php selected($patient_status,$reason);?>><?php echo $reason;?></option>
				<?php }?>				
				</select>				
			</div>	
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="doctor"><?php _e('Assign Doctor','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8 margin_bottom_5px">
			<?php if($edit){ if(!empty($doctor)) $doctorid=$doctor->ID; else $doctorid=""; }elseif(isset($_POST['doctor'])){$doctorid=$_POST['doctor'];}else{$doctorid='';}?>
				<select name="doctor" id="doctors" class="form-control validate[required]">
				<option value=""><?php _e('Select Doctor','hospital_mgt');?></option>
				<?php 
				$get_doctor = array('role' => 'doctor');
					$doctordata=get_users($get_doctor);
					 if(!empty($doctordata))
					 {
						foreach ($doctordata as $retrieved_data){?>
							<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($doctorid,$retrieved_data->ID);?>><?php echo $retrieved_data->display_name;?> - <?php echo MJ_hmgt_doctor_specialization_title($retrieved_data->ID); ?></option>
						<?php }
					 }
		?>
				</select>
			</div>
			<!--ADD DOCTER POPUP BUTTON -->
			<div class="col-sm-2">			
				<a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal_add_doctor"> <?php _e('Add Doctor','hospital_mgt');?></a>			
			</div>
		</div>		
		<div class="form-group">
				<label class="col-sm-2 control-label" for="symptoms"><?php _e('Symptoms','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-2 multiselect_validation_symtoms margin_bottom_5px">
					<select class="form-control symptoms_list" multiple="multiple" name="symptoms[]" id="symptoms">
					<?php 					
					$symptoms_category = $user_object->getPatientSymptoms();
					
					if(!empty($symptoms_category))
					{
						foreach ($symptoms_category as $retrive_data)
						{
							$symptoms_array=explode(",",$doctordetail['symptoms']);
							?>
							<option value="<?php echo $retrive_data->ID; ?>" <?php if(in_array($retrive_data->ID,$symptoms_array)){ echo 'selected'; } ?>><?php echo $retrive_data->post_title; ?></option>
							<?php
						}
					}
					?>					
					</select>
					<br>					
				</div>
					<div class="col-sm-2 margin_bottom_5px"><button id="addremove" model="symptoms"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
			</div>  
			<div class="col-sm-offset-2 col-sm-8">
				<a href="?page=hmgt_patient&tab=addpatient_step2&action=edit&patient_id=<?php echo $patient_id;?>""><input type="button" value="<?php  _e('Back To Last Step','hospital_mgt');?>" name="back_step" class="btn btn-success margin_bottom_5px" /></a>
				<input type="submit" value="<?php  _e('Save Patient','hospital_mgt'); ?>" name="save_patient_step3" class="btn btn-success symptoms_alert margin_bottom_5px"/>
			</div>
        </form>
    </div><!-- PANEL BODY DIV END --> 
<?php 
}
?>
	 <!-- add doctor --> <!-- rinkal changes add doctor --> 
	<div class="modal fade" id="myModal_add_doctor" role="dialog" style="overflow:scroll;"><!-- MODAL DIV START -->
		<div class="modal-dialog modal-lg"><!-- MODAL DIALOG DIV START -->
			<div class="modal-content"><!-- MODAL CONTENT DIV START -->
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
				<div class="modal-body"><!-- MODAL BODY DIV START -->				
					<form name="doctor_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal" id="doctor_form" enctype="multipart/form-data">
			   
					<input type="hidden" name="action" value="Mj_hmgt_save_doctor_popup_form">
					<input type="hidden" name="role" value="doctor"  />
					<input type="hidden" name="user_id" value=""  />
					<div class="header">	
								<h3 class="first_hed"><?php _e('Personal Information','hospital_mgt');?></h3>
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
							<input  id="birth_date" class="form-control validate[required] admit_date"  type="text"  name="birth_date" 
							value="" readonly>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
						  <?php $genderval='male' ?>
							<label class="radio-inline">
							 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php _e('Male','hospital_mgt');?>
							</label>
							<label class="radio-inline">
							  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','hospital_mgt');?> 
							</label>
						</div>
					</div>
					<div class="header">
								<h3><?php _e('Office Address Information','hospital_mgt');?></h3>
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
							<input id="" class="form-control validate[custom[city_state_country_validation]]" type="text"  maxlength="50" name="country_name" 
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
								<h3><?php _e('HomeTown Address Information','hospital_mgt');?></h3>
								<hr>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="address"><?php _e('Home Town Address','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="address" class="form-control validate[required,custom[address_description_validation]]" type="text"  maxlength="150" name="address" 
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
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="home_country_name"><?php _e('Country','hospital_mgt');?></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="home_country_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="home_country_name" 
							value="">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="zip_code"><?php _e('Zip Code','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" type="text" maxlength="15"  name="home_zip_code" 
							value="">
						</div>
					</div>
					<div class="header">
								<h3><?php _e('Education Information','hospital_mgt');?></h3>
								<hr>
					</div>
						<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="birth_date"><?php _e('Degree','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="doc_degree" class="form-control validate[required,custom[popup_category_validation]]" maxlength="50" type="text"  name="doc_degree" 
							value="">
						</div>
					   </div>
					<div class="header">
						<h3><?php _e('Contact Information','hospital_mgt');?></h3>
						<hr>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="mobile"><?php _e('Mobile Number','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-1 col-md-1 col-sm-2 col-xs-12 margin_bottom_5px">			
						<input type="text" value="<?php if(isset($_POST['phonecode'])){ echo $_POST['phonecode']; }else{ ?>+<?php echo MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); }?>"  class="form-control  validate[required] onlynumber_and_plussign" name="phonecode" maxlength="5">
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 has-feedback">
							<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="" name="mobile">				
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="phone"><?php _e('Phone','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="phone" class="form-control validate[custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="" name="phone">
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
							<input id="username" class="form-control validate[required,custom[username_validation]]" type="text" maxlength="30" name="username" 
							value="">
						</div>
					</div>				
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="password"><?php _e('Password','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="password" class="form-control validate[required,minSize[8]]" type="password"  maxlength="12" name="password" value="">
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
									foreach ($specialize_array as $retrieved_data)
									{?>
										<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($specializeid,$retrieved_data->ID);?>><?php echo $retrieved_data->post_title;?></option>
									<?php
									}
								 }?>
								</select>
						</div>
						<div class="col-sm-2"><button id="addremove" model="specialization"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
					</div>	
					<div class="form-group">
						<label class="col-sm-2 control-label" for="visiting_fees"><?php _e('Visiting Charge','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
						<div class="col-sm-3">
							<input id="doc_degree" class="form-control" type="number" min="0" onKeyPress="if(this.value.length==8) return false;" name="visiting_fees" step="0.01" value="">
						</div>
						<div class="col-sm-2 padding_left_0" style="float:left;padding-top: 8px;font-size: 13px;">
							<?php _e('/ Per Session','hospital_mgt');?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for=""><?php _e('Visiting Charge Tax','hospital_mgt');?></label>
						<div class="col-sm-3">
							<select  class="form-control tax_charge"  name="visiting_fees_tax[]" multiple="multiple">					
								<?php										
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
							<select  class="form-control tax_charge" name="consulting_fees_tax[]" multiple="multiple">					
								<?php
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
				</div><!-- PANEL BODY DIV END -->
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal"><?php  _e('Close','hospital_mgt');?></button>				  
				</div>
			</div><!-- MODAL CONTENT DIV END -->
		</div><!-- MODAL DIALOG DIV END -->
	</div><!-- MODAL DIV END -->