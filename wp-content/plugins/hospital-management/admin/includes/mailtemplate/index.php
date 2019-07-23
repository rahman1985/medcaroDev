<script type="text/javascript">
$(document).ready(function() 
{
	$('#registration_email_template_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
});
</script>
<?php 
if(isset($_REQUEST['Patient_Registration_Template'])){
	
	update_option('MJ_hmgt_patient_registration',$_REQUEST['MJ_hmgt_patient_registration']);
	update_option('MJ_hmgt_registration_email_template',$_REQUEST['MJ_hmgt_registration_email_template']);	
		
} 
if(isset($_REQUEST['Save_Patient_Approved_Template'])){
	update_option('MJ_hmgt_patient_approved_subject',$_REQUEST['MJ_hmgt_patient_approved_subject']);
	update_option('MJ_hmgt_patient_approved_email_template',$_REQUEST['MJ_hmgt_patient_approved_email_template']);
	
} 

 if(isset($_REQUEST['User_registration_email_template_save'])){
	 
	
	update_option('MJ_hmgt_user_registration_subject',$_REQUEST['MJ_hmgt_user_registration_subject']);
	update_option('MJ_hmgt_user_registration_email_template',$_REQUEST['MJ_hmgt_user_registration_email_template']);	
		
}

if(isset($_REQUEST['Patient_Assigned_to_Doctor_Mail_Template_save'])){
	
	update_option('MJ_hmgt_patient_assigned_to_doctor_patient_email_subject',$_REQUEST['MJ_hmgt_patient_assigned_to_doctor_patient_email_subject']);
	update_option('MJ_hmgt_patient_assigned_to_doctor_patient_email_template',$_REQUEST['MJ_hmgt_patient_assigned_to_doctor_patient_email_template']);	
		
} 

if(isset($_REQUEST['Patient_Assigned_to_Doctor_doctor_Mail_Template_save'])){

	update_option('MJ_hmgt_patient_assigned_to_doctor_mail_subject',$_REQUEST['MJ_hmgt_patient_assigned_to_doctor_mail_subject']);
	update_option('MJ_hmgt_patient_assigned_to_doctor_mail_template',$_REQUEST['MJ_hmgt_patient_assigned_to_doctor_mail_template']);	
		
} 

if(isset($_REQUEST['Patient_Assigned_to_Nurse_template_save'])){
	
	 update_option('MJ_hmgt_patient_assigned_to_nurse_subject',$_REQUEST['MJ_hmgt_patient_assigned_to_nurse_subject']);
	 update_option('MJ_hmgt_patient_assigned_to_nurse_template',$_REQUEST['MJ_hmgt_patient_assigned_to_nurse_template']);	
		
} 

if(isset($_REQUEST['Appointment_Booking_Patient_mail_template_save'])){
	
	 update_option('MJ_hmgt_appointment_booking_patient_mail_subject',$_REQUEST['MJ_hmgt_appointment_booking_patient_mail_subject']);
	 update_option('MJ_hmgt_appointment_booking_patient_mail_template',$_REQUEST['MJ_hmgt_appointment_booking_patient_mail_template']);	
		
} 

 if(isset($_REQUEST['Appointment_Booking_Patient_Doctor_mail_template_save'])){
	
	 update_option('MJ_hmgt_appointment_booking_doctor_mail_subject',$_REQUEST['MJ_hmgt_appointment_booking_doctor_mail_subject']);
	 update_option('MJ_hmgt_appointment_booking_patient_mail_template',$_REQUEST['MJ_hmgt_appointment_booking_patient_mail_template']);	
		
} 

if(isset($_REQUEST['Add_Prescription_save'])){

	 update_option('MJ_hmgt_add_prescription_subject',$_REQUEST['MJ_hmgt_add_prescription_subject']);
	 update_option('MJ_hmgt_add_prescription_template',$_REQUEST['MJ_hmgt_add_prescription_template']);	
} 

if(isset($_REQUEST['Payment_Received_against_Invoice_save'])){
	
	 update_option('MJ_hmgt_payment_received_invoice_subject',$_REQUEST['MJ_hmgt_payment_received_invoice_subject']);
	 update_option('MJ_hmgt_payment_received_invoice_template',$_REQUEST['MJ_hmgt_payment_received_invoice_template']);	
} 

  if(isset($_REQUEST['Generate_Invoice_Template_save'])){

	 update_option('MJ_hmgt_generate_invoice_subject',$_REQUEST['MJ_hmgt_generate_invoice_subject']);
	 update_option('MJ_hmgt_generate_invoice_template',$_REQUEST['MJ_hmgt_generate_invoice_template']);	
} 

 if(isset($_REQUEST['Assign_Bed_to_Patient_save'])){
	
	 update_option('MJ_hmgt_assign_bed_patient_subject',$_REQUEST['MJ_hmgt_assign_bed_patient_subject']);
	 update_option('MJ_hmgt_assign_bed_patient_template',$_REQUEST['MJ_hmgt_assign_bed_patient_template']);	
} 

if(isset($_REQUEST['Message_Received'])){
	 update_option('MJ_hmgt_message_received_subject',$_REQUEST['MJ_hmgt_message_received_subject']);
	 update_option('MJ_hmgt_message_received_template',$_REQUEST['MJ_hmgt_message_received_template']);	
} 

if(isset($_REQUEST['diagnosis_report_template'])){
	 update_option('MJ_hmgt_add_diagnosis_report_subject',$_REQUEST['MJ_hmgt_add_diagnosis_report_subject']);
	 update_option('MJ_hmgt_add_diagnosis_report_template',$_REQUEST['MJ_hmgt_add_diagnosis_report_template']);	
} 

if(isset($_REQUEST['diagnosis_report_template_doctor'])){
	 update_option('MJ_hmgt_add_diagnosis_report_subject_doctor',$_REQUEST['MJ_hmgt_add_diagnosis_report_subject_doctor']);
	 update_option('MJ_hmgt_add_diagnosis_report_template_doctor',$_REQUEST['MJ_hmgt_add_diagnosis_report_template_doctor']);	
} 

if(isset($_REQUEST['cancel_appointment_doctor'])){
	 update_option('MJ_hmgt_cancel_appointment_doctor_subject',$_REQUEST['MJ_hmgt_cancel_appointment_doctor_subject']);
	 update_option('MJ_hmgt_cancel_appointment_doctor_mail',$_REQUEST['MJ_hmgt_cancel_appointment_doctor_mail']);	
} 

   if(isset($_REQUEST['cancel_appointment_patient'])){
	 update_option('MJ_hmgt_cancel_appointment_patient_subject',$_REQUEST['MJ_hmgt_cancel_appointment_patient_subject']);
	 update_option('MJ_hmgt_cancel_appointment_patient_mail',$_REQUEST['MJ_hmgt_cancel_appointment_patient_mail']);	
   } 
   
   if(isset($_REQUEST['edit_appointment_doctor'])){
	 update_option('MJ_hmgt_edit_appointment_doctor_subject',$_REQUEST['MJ_hmgt_edit_appointment_doctor_subject']);
	 update_option('MJ_hmgt_edit_appointment_doctor_mail',$_REQUEST['MJ_hmgt_edit_appointment_doctor_mail']);	
} 

   if(isset($_REQUEST['edit_appointment_patient'])){
	 update_option('MJ_hmgt_edit_appointment_patient_subject',$_REQUEST['MJ_hmgt_edit_appointment_patient_subject']);
	 update_option('MJ_hmgt_edit_appointment_patient_mail',$_REQUEST['MJ_hmgt_edit_appointment_patient_mail']);	
   }

?>


<div class="page-inner" style="min-height:1088px !important"> <!-- PAGE INNER DIV START--> 
	<div class="page-title"> <!-- PAGE TITLE DIV START--> 
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?>
		</h3>
	</div> <!-- PAGE TITLE DIV END--> 	
	<div id="main-wrapper"> <!-- MAIN WRAPPER DIV START--> 
		<div class="row"> <!-- ROW DIV START--> 
			<div class="col-md-12">
				<div class="panel panel-white mail_template_panel"> <!-- PANEL WHITE DIV START--> 
					<div class="panel-body"> <!-- PANEL BODY DIV START--> 
						<div class="panel-group" id="accordion"> <!-- PANEL GROUP DIV START--> 
							<div class="panel panel-default"> <!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
									  <?php _e('Patient Registration Template ','hospital_mgt'); ?>
										</a>
									</h4>
								</div>
								<div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body"><!-- PANEL BODY DIV START--> 
										<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_patient_registration" id="MJ_hmgt_patient_registration" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_patient_registration'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Registration Email Template','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_registration_email_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_registration_email_template'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												
												<label><strong>{{Patient Name}}</strong> <?php _e('User name of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Patient ID}}</strong> <?php _e('Id of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
												<label><strong>{{Login Link}}</strong> <?php _e('Login Page  Link','hospital_mgt'); ?></label><br>
															
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="Patient_Registration_Template" class="btn btn-success" type="submit">
										</div>
										</form>
									</div><!-- PANEL BODY DIV END--> 
								</div>
							</div><!-- PANEL DEFAULT DIV END-->
							<!---Member Approved by admin  -->
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsetwoone">
									  <?php _e('Patient Approved Template ','hospital_mgt'); ?>
										</a>
									</h4>
								</div>
								<div id="collapsetwoone" class="panel-collapse collapse">
									<div class="panel-body"><!-- PANEL BODY DIV START--> 
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input id="MJ_hmgt_patient_approved_subject" class="form-control validate[required]" name="MJ_hmgt_patient_approved_subject" id="Patient_Approved_Subject" placeholder="Enter Email Subject" value="<?php echo get_option('MJ_hmgt_patient_approved_subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Patient Approved Template','hospital_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="MJ_hmgt_patient_approved_email_template" name="MJ_hmgt_patient_approved_email_template" class="form-control validate[required]"><?php echo get_option('MJ_hmgt_patient_approved_email_template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>				
													<label><strong>{{Patient Name}} - </strong><?php _e('The Patient name','hospital_mgt');?></label><br>
													<label><strong>{{Hospital Name}} - </strong><?php _e('Name Of Hospital','hospital_mgt');?></label><br>
													<label><strong>{{Login Link}} - </strong><?php _e('Login Link','hospital_mgt');?></label><br>
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','hospital_mgt')?>" name="Save_Patient_Approved_Template" class="btn btn-success"/>
											 </div>
										</form>
									</div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
							<div class="panel-heading">
							  <h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
								  <?php _e('Add User in system Template ','hospital_mgt'); ?>
								</a>
							  </h4>
							</div>
							<div id="collapseTwo" class="panel-collapse collapse ">
							  <div class="panel-body"><!-- PANEL BODY DIV START--> 
								<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
								
									<div class="form-group">
										<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
										<div class="col-md-8">
											<input class="form-control validate[required]" name="MJ_hmgt_user_registration_subject" id="MJ_hmgt_user_registration_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_user_registration_subject'); ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="first_name" class="col-sm-3 control-label"><?php _e('Add User in system Template','hospital_mgt'); ?> </label>
										<div class="col-md-8">
											<textarea style="min-height:200px;" name="MJ_hmgt_user_registration_email_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_user_registration_email_template'); ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-md-8">
											<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
											<label><strong>{{UserName}}</strong> <?php _e('Name Of User','hospital_mgt'); ?></label><br>
											<label><strong>{{Role Name}}</strong> <?php _e('Role Of User','hospital_mgt'); ?></label><br>
											<label><strong>{{User_Name}}</strong> <?php _e('User name','hospital_mgt'); ?></label><br>
											<label><strong>{{Password}}</strong> <?php _e('Password Of User','hospital_mgt'); ?></label><br>
											<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
											<label><strong>{{Login Link}}</strong> <?php _e('Login Page  Link','hospital_mgt'); ?></label><br>
										</div>
									</div>
									<div class="col-sm-offset-3 col-sm-8">        	
										<input value="<?php _e('Save','hospital_mgt'); ?>" name="User_registration_email_template_save" class="btn btn-success" type="submit">
									</div>
								</form>
							  </div><!-- PANEL BODY DIV END--> 
							</div>
						</div>	<!-- PANEL DEFAULT DIV END--> 						
						<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
							<div class="panel-heading">
							  <h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
								  <?php _e('Patient Assigned to Doctor Patient Mail Template','hospital_mgt'); ?>
								</a>
							  </h4>
							</div>
							<div id="collapseThree" class="panel-collapse collapse ">
							  <div class="panel-body"><!-- PANEL BODY DIV START--> 
								<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
								
									<div class="form-group">
										<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
										<div class="col-md-8">
											<input class="form-control validate[required]" name="MJ_hmgt_patient_assigned_to_doctor_patient_email_subject" id="MJ_hmgt_patient_assigned_to_doctor_patient_email_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_patient_assigned_to_doctor_patient_email_subject'); ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="first_name" class="col-sm-3 control-label"><?php _e('Patient Assigned to Doctor Patient Mail Template','hospital_mgt'); ?> </label>
										<div class="col-md-8">
											<textarea style="min-height:200px;" name="MJ_hmgt_patient_assigned_to_doctor_patient_email_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_patient_assigned_to_doctor_patient_email_template'); ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-md-8">
											<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
											
											<label><strong>{{Patient Name}}</strong> <?php _e('Name of Patient','hospital_mgt'); ?></label><br>
											<label><strong>{{Doctor Name}}</strong> <?php _e('Name Of Dr Doctor','hospital_mgt'); ?></label><br>
											<label><strong>{{Department Name}}</strong> <?php _e('Name Of Department ','hospital_mgt'); ?></label><br>
											<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
														
										</div>
									</div>
									<div class="col-sm-offset-3 col-sm-8">        	
										<input value="<?php _e('Save','hospital_mgt'); ?>" name="Patient_Assigned_to_Doctor_Mail_Template_save" class="btn btn-success" type="submit">
									</div>
								</form>
							  </div><!-- PANEL BODY DIV END--> 
							</div>
						</div>	<!-- PANEL DEFAULT DIV END--> 						
						<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
									  <?php _e('Patient Assigned To Doctor Mail Template','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseFour" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_patient_assigned_to_doctor_mail_subject" id="MJ_hmgt_patient_assigned_to_doctor_mail_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_patient_assigned_to_doctor_mail_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Patient Assign to Doctor Mail Template','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_patient_assigned_to_doctor_mail_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_patient_assigned_to_doctor_mail_template'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												<label><strong>{{Patient Name}} </strong><?php _e('Name of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Doctor Name}} </strong><?php _e('Name Of Dr Doctor','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}} </strong><?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="Patient_Assigned_to_Doctor_doctor_Mail_Template_save" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsefifth">
									  <?php _e('Patient Assigned to Nurse Mail Template','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapsefifth" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_patient_assigned_to_nurse_subject" id="MJ_hmgt_patient_assigned_to_nurse_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_patient_assigned_to_nurse_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Patient Assigned to Nurse Mail Template','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_patient_assigned_to_nurse_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_patient_assigned_to_nurse_template'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												
												<label><strong>{{Nurse Name}}</strong> <?php _e('Name of Nurse','hospital_mgt'); ?></label><br>
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
															
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="Patient_Assigned_to_Nurse_template_save" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsesix">
									  <?php _e('Patient Appointment Booking Mail template','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapsesix" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_appointment_booking_patient_mail_subject" id="MJ_hmgt_appointment_booking_patient_mail_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_appointment_booking_patient_mail_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Patient Appointment Booking Mail template','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_appointment_booking_patient_mail_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_appointment_booking_patient_mail_template'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												
												
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Doctor Name}}</strong> <?php _e('Name Of Doctor','hospital_mgt'); ?></label><br>
												<label><strong>{{Appointment Time}}</strong> <?php _e('Appointment Time','hospital_mgt'); ?></label><br>
												<label><strong>{{Appointment Date}}</strong> <?php _e('Appointment Date','hospital_mgt'); ?></label><br>
												
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
															
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="Appointment_Booking_Patient_mail_template_save" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsesevan">
									  <?php _e(' Doctor Appointment Booking  Mail Template','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapsesevan" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_appointment_booking_doctor_mail_subject" id="MJ_hmgt_appointment_booking_doctor_mail_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_appointment_booking_doctor_mail_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Doctor Appointment Booking  Mail Template','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_appointment_booking_patient_mail_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_appointment_booking_patient_mail_template'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												
												<label><strong>{{Doctor Name}}</strong> <?php _e('Name Of Doctor','hospital_mgt'); ?></label><br>
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Appointment Time}}</strong> <?php _e('Appointment Time','hospital_mgt'); ?></label><br>
												<label><strong>{{Appointment Date}}</strong> <?php _e('Appointment Date','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
															
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="Appointment_Booking_Patient_Doctor_mail_template_save" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseeight">
									  <?php _e('Add Prescription Template','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseeight" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_add_prescription_subject" id="MJ_hmgt_add_prescription_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_add_prescription_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Add Prescription  Mail Template','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_add_prescription_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_add_prescription_template'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												
												<label><strong>{{Doctor Name}}</strong> <?php _e('Name Of Doctor','hospital_mgt'); ?></label><br>
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
															
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="Add_Prescription_save" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsenine">
									  <?php _e('Payment Received against Invoice Template','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapsenine" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_payment_received_invoice_subject" id="MJ_hmgt_payment_received_invoice_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_payment_received_invoice_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Payment Received against Invoice Template','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_payment_received_invoice_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_payment_received_invoice_template'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
											
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{InvoiceNo}}</strong> <?php _e('InvoiceNo','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
												
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="Payment_Received_against_Invoice_save" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
						    </div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseten">
									  <?php _e('Generate Invoice Template','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseten" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_generate_invoice_subject" id="MJ_hmgt_generate_invoice_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_generate_invoice_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Generate Invoice Template','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_generate_invoice_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_generate_invoice_template'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="Generate_Invoice_Template_save" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseeleven">
									  <?php _e('Assign Bed to Patient Template','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseeleven" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										 
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_assign_bed_patient_subject" id="MJ_hmgt_assign_bed_patient_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_assign_bed_patient_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Assign Bed to Patient Template','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_assign_bed_patient_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_assign_bed_patient_template'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
											
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Bed ID}}</strong> <?php _e('ID Of Bed','hospital_mgt'); ?></label><br>
												<label><strong>{{Bed Category}}</strong> <?php _e('Category Of Bed','hospital_mgt'); ?></label><br>
												<label><strong>{{Charges Amount}}</strong> <?php _e('Charges Amount Of Bed','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="Assign_Bed_to_Patient_save" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsetwelve">
									  <?php _e('Message Received Template','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapsetwelve" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										 
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_message_received_subject" id="MJ_hmgt_message_received_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_message_received_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Message Received Template','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_message_received_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_message_received_template'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
											
												<label><strong>{{Receiver Name}}</strong> <?php _e('Name Of Receiver','hospital_mgt'); ?></label><br>
												<label><strong>{{Sender Name}}</strong> <?php _e('Name Of Sender','hospital_mgt'); ?></label><br>
												<label><strong>{{Message Content}} </strong> <?php _e('Message Content','hospital_mgt'); ?></label><br>
												<label><strong>{{Message_Link}} </strong> <?php _e('Message_Link ','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
												
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="Message_Received" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsetharteen">
									  <?php _e('Diagnosis Report Mail Template For Patient','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapsetharteen" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_add_diagnosis_report_subject" id="MJ_hmgt_add_diagnosis_report_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_add_diagnosis_report_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Diagnosis Report Mail Template For Patient','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_add_diagnosis_report_template" class="form-control validate[required]"><?php print get_option('MJ_hmgt_add_diagnosis_report_template'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Charges Amount}}</strong> <?php _e('Charged amount for the diagnosis report.','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="diagnosis_report_template" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsefiftin">
									  <?php _e('Diagnosis Report Mail Template For Doctor.','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapsefiftin" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_add_diagnosis_report_subject_doctor" id="MJ_hmgt_add_diagnosis_report_subject_doctor" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_add_diagnosis_report_subject_doctor'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Diagnosis Report Mail Template For Doctor','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_add_diagnosis_report_template_doctor" class="form-control validate[required]"><?php print get_option('MJ_hmgt_add_diagnosis_report_template_doctor'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												<label><strong>{{Doctor Name}}</strong> <?php _e('Name Of Doctor','hospital_mgt'); ?></label><br>
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="diagnosis_report_template_doctor" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsesixtine">
									  <?php _e('Cancel Appointment Mail Template For Patient.','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapsesixtine" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_cancel_appointment_patient_subject" id="MJ_hmgt_cancel_appointment_patient_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_cancel_appointment_patient_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Cancel Appointment Mail Template For Patient','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_cancel_appointment_patient_mail" class="form-control validate[required]"><?php print get_option('MJ_hmgt_cancel_appointment_patient_mail'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Doctor Name}}</strong> <?php _e('Name Of Doctor','hospital_mgt'); ?></label><br>
												<label><strong>{{Appointment Time}}</strong> <?php _e('Appointment Time','hospital_mgt'); ?></label><br>
												<label><strong>{{Appointment Date}}</strong> <?php _e('Appointment Date','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="cancel_appointment_patient" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseseventine">
									  <?php _e('Cancel Appointment Mail Template For Doctor.','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseseventine" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_cancel_appointment_doctor_subject" id="MJ_hmgt_cancel_appointment_doctor_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_cancel_appointment_doctor_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Cancel Appointment Mail Template For Doctor','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_cancel_appointment_doctor_mail" class="form-control validate[required]"><?php print get_option('MJ_hmgt_cancel_appointment_doctor_mail'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Doctor Name}}</strong> <?php _e('Name Of Doctor','hospital_mgt'); ?></label><br>
												<label><strong>{{Appointment Time}}</strong> <?php _e('Appointment Time','hospital_mgt'); ?></label><br>
												<label><strong>{{Appointment Date}}</strong> <?php _e('Appointment Date','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="cancel_appointment_doctor" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseeighteen">
									  <?php _e('Edit Appointment Mail Template For Patient.','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseeighteen" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_edit_appointment_patient_subject" id="MJ_hmgt_edit_appointment_patient_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_edit_appointment_patient_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Edit Appointment Mail Template For Patient','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_edit_appointment_patient_mail" class="form-control validate[required]"><?php print get_option('MJ_hmgt_edit_appointment_patient_mail'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Doctor Name}}</strong> <?php _e('Name Of Doctor','hospital_mgt'); ?></label><br>
												<label><strong>{{Appointment Time}}</strong> <?php _e('Appointment Time','hospital_mgt'); ?></label><br>
												<label><strong>{{Appointment Date}}</strong> <?php _e('Appointment Date','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="edit_appointment_patient" class="btn btn-success" type="submit">
										</div>
									</form>
								  </div><!-- PANEL BODY DIV END--> 
								</div>
							</div>	<!-- PANEL DEFAULT DIV END--> 						
							<div class="panel panel-default"><!-- PANEL DEFAULT DIV START--> 
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseninineteen">
									  <?php _e('Edit Appointment Mail Template For Doctor.','hospital_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseninineteen" class="panel-collapse collapse ">
								  <div class="panel-body"><!-- PANEL BODY DIV START--> 
									<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
									
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','hospital_mgt');?> </label>
											<div class="col-md-8">
												<input class="form-control validate[required]" name="MJ_hmgt_edit_appointment_doctor_subject" id="MJ_hmgt_edit_appointment_doctor_subject" placeholder="Enter email subject" value="<?php print get_option('MJ_hmgt_edit_appointment_doctor_subject'); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="first_name" class="col-sm-3 control-label"><?php _e('Edit Appointment Mail Template For Doctor','hospital_mgt'); ?> </label>
											<div class="col-md-8">
												<textarea style="min-height:200px;" name="MJ_hmgt_edit_appointment_doctor_mail" class="form-control validate[required]"><?php print get_option('MJ_hmgt_edit_appointment_doctor_mail'); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-8">
												<label><?php _e('You can use following variables in the email template:','hospital_mgt');?></label><br>
												<label><strong>{{Patient Name}}</strong> <?php _e('Name Of Patient','hospital_mgt'); ?></label><br>
												<label><strong>{{Doctor Name}}</strong> <?php _e('Name Of Doctor','hospital_mgt'); ?></label><br>
												<label><strong>{{Appointment Time}}</strong> <?php _e('Appointment Time','hospital_mgt'); ?></label><br>
												<label><strong>{{Appointment Date}}</strong> <?php _e('Appointment Date','hospital_mgt'); ?></label><br>
												<label><strong>{{Hospital Name}}</strong> <?php _e('Name Of Hospital','hospital_mgt'); ?></label><br>
											</div>
										</div>
										<div class="col-sm-offset-3 col-sm-8">        	
											<input value="<?php _e('Save','hospital_mgt'); ?>" name="edit_appointment_doctor" class="btn btn-success" type="submit">
										</div>
									</form>
								    </div>	<!-- PANEL BODY DIV END--> 							
								</div>
							</div><!-- PANEL DEFAULT DIV END--> 
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>