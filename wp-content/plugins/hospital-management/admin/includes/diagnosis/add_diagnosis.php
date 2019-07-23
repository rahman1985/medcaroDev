<?php 
	MJ_hmgt_browser_javascript_check();
	$role='patient';
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($)
	{
	$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
     $('.birth_date').datepicker({
		endDate: '+0d',
        autoclose: true,		 
	}); 
	
	$('#symptoms').multiselect({
		nonSelectedText : '<?php _e('Select Symptoms','hospital_mgt'); ?>',
		includeSelectAllOption: true,
		selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
	 });
	 $('.tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
	 	
	$('#report_type').multiselect({
		nonSelectedText : '<?php _e('Select Report Name','hospital_mgt'); ?>',
		includeSelectAllOption: true,
		selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
	 });
	 
	
	$('#diagnosis_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	$('#doctor_form_popup_add_percription').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#doctor_form_outpatient_popup_form_percription').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

    	   
	   $('#add_doctor_form_popup_active').hide();
	    $('#adddoctor_label').hide();
	
	    $("body").on("click",'#add_precription_doctor', function(){	
	  
	       $('#add_doctor_form_popup_active').show();
	       $('#add_outpatient_form_popup_active').hide();
		   $('#add_doctor_tab').addClass("nav-tab-active");
		   $('#add_outpatient_tab').removeClass("nav-tab-active");
		   $('#adddoctor_label').show();
		   $('#outpatient_label').hide();
		  
		     });  
		
		     $('#add_doctor_tab').click(function(){
		     $('#add_doctor_tab').addClass("nav-tab-active");
		     $('#add_doctor_form_popup_active').show();
			 $('#add_outpatient_form_popup_active').hide();
			 $('#add_outpatient_tab').removeClass("nav-tab-active");
			 $('#adddoctor_label').show();
			 $('#outpatient_label').hide();
	      });
	   
	   $('#add_outpatient_tab').click(function(){
		 $('#add_doctor_tab').removeClass("nav-tab-active");
		 $('#add_doctor_form_popup_active').hide();
		  $('#add_outpatient_tab').addClass("nav-tab-active");
		  $('#add_outpatient_form_popup_active').show();
		  $('#adddoctor_label').hide();
			  $('#outpatient_label').show();
	   });
	    //adD doctor popup ajax//
	    $('#doctor_form_popup_add_percription').on('submit', function(e) {
		e.preventDefault();
		var valid = $('#doctor_form_popup_add_percription').validationEngine('validate');
		if (valid == true) {		
		
			
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
				 
				if(data!=""){ 
				   var json_obj = $.parseJSON(data);
					$('#doctor_form_popup_add_percription').trigger("reset");
					$('#doctor').append(json_obj[0]);
					
					$('#add_doctor_tab').removeClass("nav-tab-active");
					 $('#add_doctor_form_popup_active').hide();
					 $('#add_outpatient_form_popup_active').show();
					  $('#add_outpatient_tab').addClass("nav-tab-active");
					  $('.upload_user_avatar_preview').html('<img alt="" src="<?php echo get_option( 'hmgt_doctor_thumb' ); ?>">');
					$('.hmgt_user_avatar_url').val('');
					
				} 
			},
			error: function(data){
			}
		})
		}	
	}); 
	
	 //add outpatient popup//
	 
	$('#doctor_form_outpatient_popup_form_percription').on('submit', function(e) 
	{			
		e.preventDefault();
		
		var valid = $('#doctor_form_outpatient_popup_form_percription').validationEngine('validate');
		if (valid == true) 
		{	
			
		var form = new FormData(this);
		
		$.ajax({
			type:"POST",
			url: $(this).attr('action'),
			data:form,
			cache: false,
			contentType: false,
            processData: false,
			success: function(data)
			{			
				if(data!="")
				{				
				   var json_obj = $.parseJSON(data);
				    
					$('#doctor_form_outpatient_popup_form_percription').trigger("reset");
					$('#patient').append(json_obj[0]);
					$('.upload_user_avatar_preview').html('<img alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">');
					$('.hmgt_user_avatar_url').val('');
					
					$('.modal').modal('hide');					
					
				}      
			},
			error: function(data){
			}
		})
		
		}
	});   
	$(".symptoms_alert").click(function()
	{	
		checked = $(".multiselect_validation_symtoms .dropdown-menu input:checked").length;
		if(!checked)
		{
		  alert("<?php _e('Please select at least one Symptoms','hospital_mgt'); ?>");
		  return false;
		}	
	}); 

	$(".save_diagnosis").click(function()
	{	
		checked = $(".multiselect_validation_Report .dropdown-menu input:checked").length;
		if(!checked)
		{
		  alert("<?php _e('Please select at least one report type','hospital_mgt'); ?>");
		  return false;
		}	
	});	
});
</script>	
<?php
if($active_tab == 'adddiagnosis')
{
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_dignosis->get_single_dignosis_report($_REQUEST['diagnosis_id']);
		}
		?>		
        <div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="diagnosis_form" action="" method="post" class="form-horizontal" id="diagnosis_form" enctype="multipart/form-data">
				 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" id="action_name" name="action" value="<?php echo $action; ?>">
				<input type="hidden" id="diagnosisid" name="diagnosisid" value="<?php if(isset($_REQUEST['diagnosis_id'])) echo $_REQUEST['diagnosis_id']; ?>"  />
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient','hospital_mgt'); ?><span class="require-field">*</span></label>
					<div class="col-sm-8 margin_bottom_5px">
						<select name="patient_id" id="patient" class="form-control validate[required] ">
							<option value=""><?php _e('Select Patient','hospital_mgt'); ?></option>
							<?php 
							if($edit)
								$patient_id1 = $result->patient_id;
							elseif(isset($_REQUEST['patient_id']))
								$patient_id1 = $_REQUEST['patient_id'];
							else 
								$patient_id1 = "";
							$patients = MJ_hmgt_patientid_list();
							
							if(!empty($patients))
							{
								foreach($patients as $patient)
								{
									echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'> '.$patient['first_name'].' '.$patient['last_name'].' - '.$patient['patient_id'].'</option>';
								}
							}
							?>
						</select>						
					</div>
					<!--ADD OUT PATIENT POPUP BUTTON -->
					<div class="col-sm-2">		
						<a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal_add_outpatient"> <?php _e('Add Outpatient','hospital_mgt'); ?></a>			
					</div>
				</div>
				<div class="form-group">
						<label class="col-sm-2 control-label" for="Report"><?php _e('Report Name','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8 multiselect_validation_Report margin_bottom_5px">
							<select class="form-control reportlist_list report_type dignosis_upload" multiple="multiple" name="report_type[]"
							id="report_type">
							<?php 
							$report_type=new MJ_hmgt_dignosis();
							$operation_array =$report_type->get_all_report_type();
							if(!empty($operation_array))
							{
								foreach ($operation_array as $retrive_data)
								{
									$report_type_data=$retrive_data->post_title;
									$report_type_array=json_decode($report_type_data);			
									$report_type=explode(",",$result->report_type);
									?>
									<option value="<?php echo $retrive_data->ID; ?>" <?php  if(in_array($retrive_data->ID,$report_type)){ echo 'selected'; } ?>><?php echo $report_type_array->category_name; ?></option>
									<?php
								}
							}
							?>						
							</select>
							<br>
						</div>
						<div class="col-sm-2"><button id="addremove" model="report_type"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
				</div>
				<?php
				if($edit)
				{
					?>
					<div class="add_document_div_main_class">		
						<div class="form-group">		   
							<label class="col-sm-offset-2 col-sm-2 control-label upload_document_text_align"  for="document"><?php _e('Report Name','hospital_mgt');?></label>
							<label class="col-sm-3 control-label upload_document_text_align"  for="document"><?php _e('Upload Report','hospital_mgt');?></label>
							<label class="col-sm-2 control-label upload_document_text_align"  for="document"><?php _e('View Report','hospital_mgt');?></label>
							<label class="col-sm-1 control-label upload_document_text_align"  for="document"><?php _e('Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(); ?>)</label>
								
						</div>
						<?php
						$dignosis_array=json_decode($result->attach_report);
						
						if(!empty($result->attach_report))
						{
							foreach($dignosis_array as $key=>$value)
							{
								$report_type=new MJ_hmgt_dignosis();
								$report_data=$report_type->get_report_by_id($value->report_id);
								$report_type_array=json_decode($report_data);
								?>
							<div class="form-group">			
								<div class="col-sm-offset-2 col-sm-2">
								<input type="hidden" name="report_id[]" value="<?php echo $value->report_id; ?>">
								<input type="text" class="form-control file report_name" style="text-align: center;" value="<?php echo $report_type_array->category_name; ?>" name="report_name[]" readonly>
								</div>
								
								<div class="col-sm-3">
									<input type="file" class="form-control file document" style="text-align: center;" name="document[]" value="<?php echo $value->attach_report; ?>">
									
								</div>		
								<div class="col-sm-2">
									<?php
									echo '<a href="'.content_url().'/uploads/hospital_assets/'.$value->attach_report.'" class="btn btn-default" target="_blank"><i class="fa fa-download"></i> '.$report_type_array->category_name.' '.__('Report','hospital_mgt').'</a>';
									?>
									<input type="hidden" name="hidden_attach_report[]" value="<?php echo $value->attach_report; ?>" >
								</div>	
								<div class="col-sm-1">
								<input type="text" class="form-control file diagnosis_total_amount" style="text-align: center;" value="<?php echo $value->report_amount; ?>" name="diagnosis_total_amount[]" readonly>
								</div>			 
							</div>	
						<?php
							}		
						}
						?>
					</div>	
					<?php	
				}
				else
				{
				?>		
				<div class="add_document_div_main_class">		
					
				</div>
				<?php
				}	
				?>		
				<div class="form-group">
					<label class="col-sm-2 control-label" for="report_cost"><?php _e('Report Total Cost','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
					<div class="col-sm-8">
						<input type="hidden" name="cost" class="cost" value="<?php if($edit){ echo $result->report_cost; } ?>">
						<input type="hidden" name="report_tax" class="report_tax" value="<?php if($edit){ echo $result->total_tax;}  ?>">
						<input id="report_cost" class="form-control  text-input report_cost" type="number" min="0" onKeyPress="if(this.value.length==8) return false;" step="0.01" value="<?php if($edit){ echo $result->total_cost;}elseif(isset($_POST['report_cost'])) echo $_POST['report_cost'];?>" name="report_cost" readonly>
					</div>
				</div>	
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="description"><?php _e('Description','hospital_mgt');?></label>
					<div class="col-sm-8">
						<textarea id="diagno_description" maxlength="150" class="form-control validate[custom[address_description_validation]]" name="diagno_description"><?php if($edit)echo $result->diagno_description; elseif(isset($_POST['diagno_description'])) echo $_REQUEST['diagno_description']; else echo "";?> </textarea>
					</div>
				</div>				
				<div class="form-group">
					<label class="col-sm-2 control-label " for="enable"><?php _e('Send Email to Patient ','hospital_mgt');?></label>
					<div class="col-sm-8">
						 <div class="checkbox">
							<label>
								<input  type="checkbox" value="1"  name="hmgt_send_mail_to_patient">
							</label>
						</div>						 
					</div>
				</div>				
				<div class="form-group margin_bottom_5px">
					<label class="col-sm-2 control-label " for="enable"><?php _e('Send Email to Doctor ','hospital_mgt');?></label>
					<div class="col-sm-8 margin_bottom_5px">
						 <div class="checkbox">
							<label>
								<input  type="checkbox" value="1" name="hmgt_send_mail_to_doctor" checked>
							</label>
						</div>						 
					</div>
				</div>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" id="dignosisreport" value="<?php if($edit){ _e('Save Diagnosis Report','hospital_mgt'); }else{ _e('Create Diagnosis Report','hospital_mgt');}?>" name="save_diagnosis" class="btn btn-success save_diagnosis"/>
				</div>
			</form>
        </div><!-- PANEL BODY DIV END-->
<?php 
}
?>
<!----------ADD OUT PATIENT POPUP------------->
<div class="modal fade" id="myModal_add_outpatient" role="dialog" style="overflow:scroll;"><!-- MODAL DIV START-->
    <div class="modal-dialog modal-lg"><!-- MODAL DIALOG DIV START-->
		<div class="modal-content"><!-- MODAL CONTENT DIV START-->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 id="outpatient_label" class="modal-title"><?php _e('Add Outpatient','hospital_mgt');?></h3>
				<h3 id="adddoctor_label" class="modal-title"><?php _e('Add Doctor','hospital_mgt');?></h3>
			</div>
			<div class="modal-body"><!-- MODAL BODY DIV START-->
				<h2 class="nav-tab-wrapper" style="position: relative; top: 1px;">
					<a class="nav-tab nav-tab-active" id="add_outpatient_tab">
					<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Outpatient', 'hospital_mgt'); ?></a>
					<a class="nav-tab" id="add_doctor_tab" >
					<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Doctor', 'hospital_mgt'); ?></a>  
				</h2>
				<div id="add_outpatient_form_popup_active">	<!-- ADD OUTPATIENT FORM POPUP DIV START-->	 
					<?php 
					$role='patient';
					$patient_type='outpatient';
					$lastpatient_id=MJ_hmgt_get_lastpatient_id($role);
					$nodate=substr($lastpatient_id,0,-4);
					$patientno=substr($nodate,1);
					$patientno+=1;
					$newpatient='P'.$patientno.date("my");
				   ?>
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						 <form name="out_patient_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal"
						 id="doctor_form_outpatient_popup_form_percription" enctype="multipart/form-data">
							<input type="hidden" name="action" value="MJ_hmgt_save_outpatient_popup_form">		
							<input type="hidden" name="role" value="<?php echo $role; ?>"  />
							<input type="hidden" name="patient_type" value="<?php echo $patient_type; ?>"  />
							<div class="header">	
										<h3 class="first_hed"><?php _e('Personal Information','hospital_mgt');?></h3>
										<hr>
							</div>
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="roll_id"><?php _e('Patient Id','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="patient_id" class="form-control validate[required]" type="text" 
									value="<?php  echo $newpatient;?>" readonly name="patient_id">
								</div>
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="first_name"><?php _e('First Name','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="first_name">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="middle_name"><?php _e('Middle Name','hospital_mgt');?></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" type="text" maxlength="50" value="" name="middle_name">
								</div>
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="last_name"><?php _e('Last Name','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="" name="last_name">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="birth_date"><?php _e('Date of birth','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input class="form-control validate[required] birth_date " type="text"  name="birth_date" 
									value="" readonly>
								</div>
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="blood_group"><?php _e('Blood Group','hospital_mgt');?></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									
									<select id="blood_group" class="form-control" name="blood_group">
									<option value=""><?php _e('Select Blood Group','hospital_mgt');?></option>
									<?php
									$userblood=0;
									foreach(MJ_hmgt_blood_group() as $blood){ ?>
											<option value="<?php echo $blood;?>" <?php selected($userblood,$blood);  ?>><?php echo $blood; ?> </option>
									<?php } ?>
								</select>
								</div>
							</div>		
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
								<?php $genderval = "male"; ?>
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
									<input id="address" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="150"  name="address" 
									value="">
								</div>
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="city_name"><?php _e('City','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" type="text" maxlength="50" name="city_name" 
									value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="state_name"><?php _e('State','hospital_mgt');?></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" type="text" maxlength="50" name="state_name" value="">
								</div>
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="state_name"><?php _e('Country','hospital_mgt');?></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="country_name" class="form-control validate[custom[city_state_country_validation]]" type="text" maxlength="50"  name="country_name" 	value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="zip_code"><?php _e('Zip Code','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" type="text" maxlength="15"  name="zip_code" value="">
								</div>
							</div>	
							<div class="header">
									<h3><?php _e('Contact Information','hospital_mgt');?></h3>
									<hr>
							</div>
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="mobile"><?php _e('Mobile Number','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 margin_bottom_5px">
								<input type="text" value="<?php if(isset($_POST['phonecode'])){ echo $_POST['phonecode']; }else{ ?>+<?php echo MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); }?>"  class="form-control  validate[required] onlynumber_and_plussign" name="phonecode" maxlength="5">
								</div>
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 has-feedback">
									<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="" name="mobile">
								</div>
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="phone"><?php _e('Phone','hospital_mgt');?></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="phone" class="form-control validate[custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="" name="phone">
								</div>
							</div>		
							<div class="header">
								<h3><?php _e('Login Information','hospital_mgt');?></h3>
								<hr>
							</div>
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" value="">
								</div>
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="username"><?php _e('User Name','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="username" class="form-control validate[required,custom[username_validation]]" type="text"  maxlength="30" name="username" value="">
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
								<label class="col-sm-2 control-label" for="doctor"><?php _e('Assign Doctor','hospital_mgt');?></label>
								<div class="col-sm-3 margin_bottom_5px">								
									<select name="doctor" id="doctor" class="form-control">								
										<option ><?php _e('select Doctor','hospital_mgt');?></option>
										<?php
										$doctorid=0;
										$get_doctor = array('role' => 'doctor');
										$doctordata=get_users($get_doctor);
										if(!empty($doctordata))
										{
											foreach($doctordata as $retrieved_data){?>
											<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($doctorid,$retrieved_data->ID);?>><?php echo $retrieved_data->display_name;?> - <?php echo MJ_hmgt_doctor_specialization_title($retrieved_data->ID); ?></option>
											<?php }
										}
										?>									 
									</select>
								</div>
								<!-- Adddoctor Button -->
								 <div class="col-sm-2">			
									<a href="#" class="btn btn-default"  id="add_precription_doctor" data-toggle="modal" data-target="#myModal_add_doctor"> <?php _e('Add Doctor','hospital_mgt');?></a>			
								</div>
							</div>
							<div class="form-group">
									<label class="col-sm-2 control-label" for="symptoms"><?php _e('Symptoms','hospital_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-3 multiselect_validation_symtoms margin_bottom_5px">
										<select class="form-control symptoms_list" multiple="multiple" name="symptoms[]" id="symptoms">					
										<?php 
										$user_object=new MJ_hmgt_user();
										$symptoms_category = $user_object->getPatientSymptoms();
										
										if(!empty($symptoms_category))
										{
											foreach ($symptoms_category as $retrive_data)
											{						
												?>
												<option value="<?php echo $retrive_data->ID; ?>"><?php echo $retrive_data->post_title; ?></option>
												<?php
											}
										}
										?>					
										</select>
										<br>
									</div>
									<div class="col-sm-3"><button id="addremove" model="symptoms"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
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
										<img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">
									</div>
								</div>
							</div>
							<div class="col-sm-offset-2 col-sm-8">        	
								<input type="submit" value="<?php _e('Save Patient','hospital_mgt');?>" name="save_outpatient" class="btn btn-success symptoms_alert"/>
							</div>
						</form>
					</div><!-- PANEL BODY DIV END-->
				</div>	<!-- ADD OUTPATIENT FORM POPUP DIV END-->	 
				<!-- start add doctor popup foem active -->
				<div id="add_doctor_form_popup_active"><!-- ADD DOCTOR FORM POPUP DIV START-->
					<div class="panel-body"><!-- PANEL BODY DIV START-->	
						<form name="doctor_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal" id="doctor_form_popup_add_percription" enctype="multipart/form-data">
							<input type="hidden" name="action" value="Mj_hmgt_save_doctor_popup_form">
							<input type="hidden" name="role" value="doctor"/>
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
									<input  class="form-control validate[required] birth_date " type="text"  name="birth_date" 
									value="" readonly>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
								   <?php $genderval='male'; ?>
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
									<input id="" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" 
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
										<h3><?php _e('HomeTown Address Information','hospital_mgt');?></h3>
										<hr>
							</div>
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="address"><?php _e('Home Town Address','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text"  name="address" 
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
									value="<?php if($edit){ echo $user_info->home_zip_code;}elseif(isset($_POST['home_zip_code'])) echo $_POST['home_zip_code'];?>">
								</div>
							</div>
							<div class="header">
										<h3><?php _e('Education Information','hospital_mgt');?></h3>
										<hr>
							</div>
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="birth_date"><?php _e('Degree','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="doc_degree" class="form-control validate[required,custom[popup_category_validation]]" type="text"  maxlength="50" name="doc_degree" 
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
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
									value="">
								</div>
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="username"><?php _e('User Name','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input id="username" class="form-control validate[required,custom[username_validation]]" type="text"  maxlength="30" name="username" 
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
									$user_object=new MJ_hmgt_user();
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
								<div class="col-sm-2"><button id="addremove" style="margin-right: 0px;" model="department"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
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
											foreach ($specialize_array as $retrieved_data){
												?>
												<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($specializeid,$retrieved_data->ID); ?>><?php echo $retrieved_data->post_title;?></option>
											<?php }
										 }?>
										</select>
								</div>
								<div class="col-sm-2"><button id="addremove"  style="margin-right: 0px;" model="specialization"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
							</div>	
							<div class="form-group">
								<label class="col-sm-2 control-label" for="visiting_fees"><?php _e('Visiting Charge','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
								<div class="col-sm-3">
									<input id="doc_degree" class="form-control" type="number" min="0" onKeyPress="if(this.value.length==8) return false;"  name="visiting_fees" step="0.01"	value="">
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
								<label class="col-sm-2 control-label" for=""><?php _e('Consulting Charge Tax','hospital_mgt');?></label>
								<div class="col-sm-3">
									<select  class="form-control tax_charge"  name="consulting_fees_tax[]" multiple="multiple">					
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
									<?php if(isset($user_info->edu_certificate) && $user_info->edu_certificate!=""){ ?>
									<a href="<?php echo content_url().'/uploads/hospital_assets/'.$user_info->edu_certificate;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php _e('Education Certificate','hospital_mgt'); ?></a>
									<?php } ?>
									 
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="document"><?php _e('Experience Certificate','hospital_mgt'); ?></label>
								<div class="col-sm-3">
									<input type="file" class="form-control file" name="experience_cert" id="experience_cert">
									<input type="hidden" name="hidden_exp_certificate" value="">
									<p class="help-block"><?php _e('Upload document in PDF','hospital_mgt');?></p> 
								</div>
								<div class="col-sm-2">
									<?php if(isset($user_info->exp_certificate) && $user_info->exp_certificate!=""){?>
									<a href="<?php echo content_url().'/uploads/hospital_assets/'.$user_info->exp_certificate;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php _e('Experience Certificate','hospital_mgt');?></a>
									<?php } ?>
									 
								</div>
							</div>
							<div class="col-sm-offset-2 col-sm-12">
								<input type="submit" value="<?php _e('Save Doctor','hospital_mgt'); ?>" name="save_doctor" class="btn btn-success"/>
							</div>
						</form>
					</div><!-- PANEL BODY DIV END--> 
				</div><!-- ADD DOCTOR FORM POPUP DIV END-->
				<!-- end doctor popup -->
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal"><?php  _e('Close','hospital_mgt'); ?></button>  
			</div>
		</div><!-- MODAL CONTENT DIV END-->
    </div><!-- MODAL DIALOG DIV END-->
</div><!-- MODAL DIV END-->