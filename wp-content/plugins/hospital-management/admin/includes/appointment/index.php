<?php 
//Appointment
$obj_appointment = new MJ_hmgt_appointment();

$active_tab = isset($_GET['tab'])?$_GET['tab']:'appointmentlist';
$current_sms_service = get_option( 'hmgt_sms_service');
?>

<div class="page-inner" style="min-height:1631px !important"><!-- PANEL INNER DIV START-->
	<div class="page-title"><!-- PANEL TITLE DIV START-->
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div><!-- PANEL TITLE DIV END-->
	<?php 
	if(isset($_REQUEST['save_appointment']))
	{	
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{
			$result = $obj_appointment->hmgt_add_appointment($_POST);
			
			if($result)
			{
				$hmgt_sms_service_enable=0;
				if(isset($_POST['hmgt_sms_service_enable']))
					$hmgt_sms_service_enable = $_POST['hmgt_sms_service_enable'];
				if($hmgt_sms_service_enable)
				{		
					if(!empty(get_user_meta($_REQUEST['doctor_id'], 'phonecode',true))){ $phone_code_doctor=get_user_meta($_REQUEST['doctor_id'], 'phonecode',true); }else{ $phone_code_doctor='+'.MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); }				
										
					$doctor_number = $phone_code_doctor.get_user_meta($_REQUEST['doctor_id'], 'mobile',true);
					
					if(!empty(get_user_meta($_REQUEST['patient_id'], 'phonecode',true))){ $phone_code_patient=get_user_meta($_REQUEST['patient_id'], 'phonecode',true); }else{ $phone_code_patient='+'.MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); }
					
					$patient_number = $phone_code_patient.get_user_meta($_REQUEST['patient_id'], 'mobile',true);
					
					$doctor_name = MJ_hmgt_get_display_name($_REQUEST['doctor_id']);
					$patient_name = MJ_hmgt_get_display_name($_REQUEST['patient_id']);
					$message_content = "The Appointment has been booked for $patient_name with Dr. $doctor_name on DATE : ".$_REQUEST['appointment_date']." TIME : ".$_REQUEST['realtime'];
					
					if($current_sms_service == 'clickatell')
					{
						
						$clickatell=get_option('hmgt_clickatell_sms_service');
						$username = urlencode($clickatell['username']);
						$password = urlencode($clickatell['password']);
						$api_id = urlencode($clickatell['api_key']);
						$to1 = $doctor_number;
						$to2 = $patient_number;
						$message = urlencode($message_content);
						$doctor=file_get_contents("https://api.clickatell.com/http/sendmsg". "?user=$username&password=$password&api_id=$api_id&to=$to1,$to2&text=$message");
						
					}
					if($current_sms_service == 'twillo')
					{
						//Twilio lib
						require_once HMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
						$twilio=get_option( 'hmgt_twillo_sms_service');
					
						$account_sid = $twilio['account_sid']; //Twilio SID
						$auth_token = $twilio['auth_token']; // Twilio token
						$from_number = $twilio['from_number'];//My number
						$receiver = $reciever_number; //Receiver Number
						
						//twilio object
						$client = new Services_Twilio($account_sid, $auth_token);
						$message_sent = $client->account->messages->sendMessage(
								$from_number, // From a valid Twilio number
								$doctor_number, // Text this number
								$message
						);
						$message_sent = $client->account->messages->sendMessage(
								$from_number, // From a valid Twilio number
								$patient_number, // Text this number
								$message
						);					
					}
				}
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_appointment&tab=appointmentlist&message=2');
				}
				else
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_appointment&tab=appointmentlist&message=1');
				}	
			}
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result = $obj_appointment->delete_appointment($_REQUEST['appointment_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=hmgt_appointment&tab=appointmentlist&message=3');
		}
	}
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				_e('Record inserted successfully','hospital_mgt');
			?></p></div>
			<?php 
		}
		elseif($message == 2)
		{?>
			<div id="message" class="updated below-h2 "><p><?php
				_e("Record updated successfully.",'hospital_mgt');
				?></p>
				</div>
			<?php 
		}
		elseif($message == 3) 
		{?>
			<div id="message" class="updated below-h2">
			<p>
			<?php 
				_e('Record deleted successfully','hospital_mgt');
			?>
			</p></div>
			<?php		
		}
	}
	?>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START-->
		<div class="row"><!-- ROW DIV START-->
			<div class="col-md-12"> 
				<div class="panel panel-white"><!-- PANEL WHITE DIV START-->
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						<h2 class="nav-tab-wrapper">
							<a href="?page=hmgt_appointment&tab=appointmentlist" class="nav-tab <?php echo $active_tab == 'appointmentlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Appointment List', 'hospital_mgt'); ?></a>
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=hmgt_appointment&tab=addappointment&&action=edit&appointment_id=<?php echo $_REQUEST['appointment_id'];?>" class="nav-tab <?php echo $active_tab == 'addappointment' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Appointment', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_appointment&tab=addappointment" class="nav-tab <?php echo $active_tab == 'addappointment' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add New Appointment', 'hospital_mgt'); ?></a>  
							<?php  
							}?>						   
						</h2>
						<?php 
						if($active_tab == 'appointmentlist')
						{ 						
						?>	
							<script type="text/javascript">
							jQuery(document).ready(function($) {
								jQuery('#appointment_list').DataTable({
									"responsive": true,
									 "order": [[ 0, "Desc" ]],
									 "aoColumns":[
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},	                                 
												  {"bSortable": false}
											   ],
									language:<?php echo MJ_hmgt_datatable_multi_language();?>
									});
									
								
							} );
							</script>
							<form name="wcwm_report" action="" method="post">						
								<div class="panel-body"><!-- PANEL BODY DIV START-->
									<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->
										<table id="appointment_list" class="display" cellspacing="0" width="100%">
											<thead>
												<tr>
												<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>
												 <th><?php _e( 'Patient ', 'hospital_mgt' ) ;?></th>
												  <th><?php _e( 'Doctor', 'hospital_mgt' ) ;?></th>
												  <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
												<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>
												 <th><?php _e( 'Patient ', 'hospital_mgt' ) ;?></th>
												  <th><?php _e( 'Doctor', 'hospital_mgt' ) ;?></th>
												  <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
												</tr>
											</tfoot>								 
											<tbody>
											 <?php 
											$appointment_data=$obj_appointment->get_all_appointment();
											if(!empty($appointment_data))
											{
												foreach ($appointment_data as $retrieved_data)
												{
												?>
												<tr>													
													<td class="appointment_time"><?php echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->appointment_date));?>(<?php echo MJ_hmgt_appoinment_time_language_translation($retrieved_data->appointment_time_with_a); ?>)</td>
													
													<td class="patient">
													<?php 
													$patient_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->patient_id);
													echo $patient_data['first_name']." ".$patient_data['last_name'];?></td>     
													<td class="doctor">
													 <?php $doctor_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->doctor_id);
													echo $doctor_data['first_name']." ".$doctor_data['last_name'];?></td>                
													<td class="action"> 
													<a href="?page=hmgt_appointment&tab=addappointment&action=edit&appointment_id=<?php echo $retrieved_data->appointment_id;?>" class="btn btn-info"> 
													<?php _e('Edit', 'hospital_mgt' ) ;?></a>
													<a href="?page=hmgt_appointment&tab=appointmentlist&action=delete&appointment_id=<?php echo $retrieved_data->appointment_id;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
													<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
												   
													</td>
												   
												</tr>
												<?php 
												} 												
											}?>
											</tbody>										
										</table>
									</div><!-- TABEL RESPONSIVEE DIV END-->
								</div><!-- PANEL BODY DIV END-->					   
							</form>
						 <?php 
						}						
						if($active_tab == 'addappointment')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/appointment/add-appointment.php';
						}
						?>
					</div>	<!-- PANEL BODY DIV END-->		
				</div><!-- PANEL WHITE DIV END-->
			</div>
		</div><!-- ROW DIV END-->
<?php ?>