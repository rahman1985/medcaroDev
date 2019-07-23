<?php 
$obj_appointment = new MJ_hmgt_appointment();
$active_tab = "addnotice";
// Get appointment data//
$appointment_data=$obj_appointment->get_all_appointment();
$cal_array = array ();
if (! empty ( $appointment_data )) 
{
	foreach ( $appointment_data as $appointment )
	{		
		$patient_data =	MJ_hmgt_get_user_detail_byid($appointment->patient_id);
		$patient_name = $patient_data['first_name']." ".$patient_data['last_name']."(".$patient_data['patient_id'].")";
		$doctor_data =	MJ_hmgt_get_user_detail_byid($appointment->doctor_id);
		$doctor_name = $doctor_data['first_name']." ".$doctor_data['last_name'];
		$appointment_time_with_a=$appointment->appointment_time_with_a;
		$d = new DateTime($appointment_time_with_a); 
		 $starttime=date_format($d,'H:i:s'); 
		 $appointment_start_date=date('Y-m-d',strtotime($appointment->appointment_time_string));
		$appointment_start_date_new=$appointment_start_date." ".$starttime;
		
		$appointment_enddate = date('Y-m-d H:i:s',strtotime($appointment_start_date_new) + 900);
		
		$i=1;
			
		 $cal_array [] = array (
				'type' =>  'appointment',
				'title' =>  __( 'Detail', 'hospital_mgt' ) ,
				'start' => $appointment_start_date_new,
				'end' =>$appointment_enddate,
				'patient_name' =>$patient_name,
				'doctor_name' =>$doctor_name,
				'appointment_time'=> $appointment->appointment_time_with_a 
			); 	
	}
}
//get event data
$args['post_type'] = array('hmgt_event');
$args['posts_per_page'] = -1;
$args['post_status'] = 'public';
$q = new WP_Query();
$retrieve_class = $q->query( $args );

if(!empty($retrieve_class))
{
	foreach ($retrieve_class as $retrieved_data)
	{
		
		$event_for_array=explode(",",get_post_meta( $retrieved_data->ID, 'notice_for',true));
		$i=1;
		 $cal_array [] = array (	
					'type' =>  'event',
					'title' => $retrieved_data->post_title,
					'start' => get_post_meta($retrieved_data->ID,'start_date',true),
					'end' => date('Y-m-d',strtotime(get_post_meta($retrieved_data->ID,'end_date',true).' +'.$i.' days')) ,
					'event_for' =>MJ_hmgt_get_role_name_in_event($event_for_array),
					'event_comment' =>$retrieved_data->post_content,
					'backgroundColor' => 'green'						
				); 
	} 
}
//get notice data
$args['post_type'] = array('hmgt_notice');
$args['posts_per_page'] = -1;
$args['post_status'] = 'public';
$q = new WP_Query();
$retrieve_class1 = $q->query( $args );

if(!empty($retrieve_class1))
{
	foreach ($retrieve_class1 as $retrieved_data)
	{
		
		$notice_for_array=explode(",",get_post_meta( $retrieved_data->ID, 'notice_for',true));
		$i=1;		
	} 
}
?>

<script>
	 $(document).ready(function() {	
		 $('#calendar').fullCalendar({			
			 header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
			editable: false,
			timeFormat: 'h(:mm)A',
			eventLimit: 1, 
			slotDuration:'00:15:00',			
			events:<?php echo json_encode($cal_array);?>,			
			eventRender: function(event, element)
			{
				if(event.type=='appointment')
				{   
					element.find('.fc-title').append("<?php  __( 'Doctor', 'hospital_mgt' ) ?> :" + event.doctor_name +"  <?php __( 'Patient', 'hospital_mgt' ) ?> :" + event.patient_name + ", ");							
				}	
				if(event.type=='event')
				{ 
					element.find('.fc-content').css("background-color", "green");	
				}
				if(event.type=='notice')
				{ 
					element.find('.fc-content').css("background-color", "#F25656");	
				}				
			},		

			eventMouseover: function(event, element)
			{
				if(event.type=='appointment')
				{	
					var date = new Date(event.start);			
					var time = event.appointment_time;				
					var month = date.getMonth() + 1;
					var day = date.getDate();
					var year = date.getFullYear();						
					var full_date = year + "-" + month + "-" + day;				
					var tooltip = '<div class="tooltipevent" style="background:#feb811;width:230px;color:white;padding:5px;position:absolute;z-index:10001;margin:0;"><?php  _e( 'Doctor Name', 'hospital_mgt' ) ?>  : ' + event.doctor_name + '<br> <?php  _e( 'Patient Name', 'hospital_mgt' ) ?> :' + event.patient_name +' <br>  <?php  _e( 'Date', 'hospital_mgt' ) ?>  :' + full_date +'<br> <?php  _e( 'Time', 'hospital_mgt' ) ?>  :'+ time +' </div>';
					var $tool = $(tooltip).appendTo('body');					
					
					jQuery(this).mouseover(function(e)
					{
						$(this).css('z-index', 10000);
						$tool.fadeIn('500');
						$tool.fadeTo('10', 1.9);
					}).mousemove(function(e) 
					{
						$tool.css('top', e.pageY + 5);
						$tool.css('left', e.pageX + 5);							
					});
				}
				else
				{							
					var date = new Date(event.start);
					var month = date.getMonth() + 1;
					var day = date.getDate();
					var year = date.getFullYear();						
					var full_start_date = year + "-" + month + "-" + day;		
					
					var newdate = event.end;				
					var newdate1 = new Date(newdate);
					newdate1.setDate(newdate1.getDate() - 1);
					
					var date1 = new Date(newdate1);
					var month1 = date1.getMonth() + 1;
					var day1 = date1.getDate();
					var year1 = date1.getFullYear();						
					var full_end_date = year1 + "-" + month1 + "-" + day1;		
					
					if(event.type=='event')
					{
						var tooltip = '<div class="tooltipevent" style="background:#feb811;width:230px;color:white;padding:5px;position:absolute;z-index:10001;margin:0;"><?php  _e( 'Event Name', 'hospital_mgt' ) ?>  : ' + event.title + '<br> <?php  _e( 'Start Date', 'hospital_mgt' ) ?> :' + full_start_date +' <br>  <?php  _e( 'End Date', 'hospital_mgt' ) ?>  :' + full_end_date +'<br> <?php  _e( 'Event For', 'hospital_mgt' ) ?>  :'+ event.event_for +' <br> <?php  _e( 'Comment', 'hospital_mgt' ) ?>  :'+ event.event_comment +'</div>';
					}
					else
					{
						var tooltip = '<div class="tooltipevent" style="background:#feb811;width:230px;color:white;padding:5px;position:absolute;z-index:10001;margin:0;"><?php  _e( 'Notice Name', 'hospital_mgt' ) ?>  : ' + event.title + '<br> <?php  _e( 'Start Date', 'hospital_mgt' ) ?> :' + full_start_date +' <br>  <?php  _e( 'End Date', 'hospital_mgt' ) ?>  :' + full_end_date +'<br> <?php  _e( 'Notice For', 'hospital_mgt' ) ?>  :'+ event.event_for +' <br> <?php  _e( 'Comment', 'hospital_mgt' ) ?>  :'+ event.event_comment +'</div>';
					}
					var $tool = $(tooltip).appendTo('body');	
					
					jQuery(this).mouseover(function(e)
					{
						$(this).css('z-index', 10000);
						$tool.fadeIn('500');
						$tool.fadeTo('10', 1.9);
					}).mousemove(function(e) 
					{	
						$tool.css('top', e.pageY + 5);
						$tool.css('left', e.pageX + 5);
					});
				}
			},
			eventMouseout: function(event, element) 
			{				
				$(this).css('z-index', 8);
				$('.tooltipevent').remove();
			}
		});		
	});

</script>	
<?php MJ_hmgt_datatable_multi_language(); ?>
<!--task-event POP up code -->
  <div class="popup-bg">
    <div class="overlay-content content_width">
		<div class="modal-content" style="border-top: 5px solid #22baa0;">
			<div class="task_event_list">
			</div>     
		</div>
    </div>     
  </div>
 <!-- End task-event POP-UP Code -->
<div class="page-inner" style="min-height:1088px !important">
	<!--  Page title div -->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?>
		</h3>
	</div>
	<!--  End Page title div -->
	<!-- main-wrapper div START-->  
	<div id="main-wrapper">
	  <!-- row div START--> 
		<div class="row">
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_patient';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body patient">
						<div class="info-box-stats">
							<p class="counter"><?php echo count(get_users(array('role'=>'patient')));?></p>
							
							<span class="info-box-title"><?php echo esc_html( __( 'Patient', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/patient.png"?>" class="dashboard_background">
					</div>
				</div>
			</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_doctor';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body doctor">
						<div class="info-box-stats">
							<p class="counter"><?php echo count(get_users(array('role'=>'doctor')));?></p>
							<span class="info-box-title"><?php echo esc_html( __( 'Doctor', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/doctor.png"?>" class="dashboard_background">
                        
					</div>
				</div>
				</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_nurse';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body nurse">
						<div class="info-box-stats">
							<p class="counter"><?php echo count(get_users(array('role'=>'nurse')));?></p>
							<span class="info-box-title"><?php echo esc_html( __( 'Nurse', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/nurse.png"?>" class="dashboard_background">
					</div>
				</div>
				</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_receptionist';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body receptionist">
						<div class="info-box-stats">
							<p class="counter"><?php echo count(get_users(array('role'=>'receptionist')));?></p>
							
							<span class="info-box-title"><?php echo esc_html( __( 'Support Staff', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/support-staft.png"?>" class="dashboard_background">
						
					</div>
				</div>
				</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_message';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body message">
						<div class="info-box-stats">
							<p class="counter"><?php 
							$obj_message = new MJ_hmgt_message();
							$message = $obj_message->hmgt_count_inbox_item(get_current_user_id());
							echo count($message);
							?></p>
							
							<span class="info-box-title"><?php echo esc_html( __( 'Message', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/message.png"?>" class="dashboard_background">
						
					</div>
				</div>
			</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_gnrl_settings';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body setting">
						<div class="info-box-stats">
							<p class="counter"> &nbsp;</p>
							<span class="info-box-title"><?php echo esc_html( __( 'Settings', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/setting-image.png"?>" class="dashboard_background">
					</div>
				</div>
				</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_appointment';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body appointment">
						<div class="info-box-stats">
							<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_appointment');?></p>
							<span class="info-box-title"><?php echo esc_html( __( 'Appointment', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/appointment-image.png"?>" class="dashboard_background">
					</div>
				</div>
			</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_prescription';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body prescription">
						<div class="info-box-stats">
							<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_priscription');?></p>
							
							<span class="info-box-title"><?php echo esc_html( __( 'Prescription', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/preseription-image.png"?>" class="dashboard_background"> 
						
					</div>
				</div>
				</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_bedallotment';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body assignbed">
						<div class="info-box-stats">
							<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_bed_allotment');?></p>
							
							<span class="info-box-title"><?php echo _e( __( 'Assign <BR> Bed/Nurse', 'hospital_mgt' ) );?></span>
						</div>
						 <img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/assign-bed-image.png"?>" class="dashboard_background"> 
						
					</div>
				</div>
			</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_treatment';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body treatment">
						<div class="info-box-stats">
							<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_treatment');?></p>
							
							<span class="info-box-title"><?php echo esc_html( __( 'Treatment', 'hospital_mgt' ) );?></span>
						</div>
						 <img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/tretment-image.png"?>" class="dashboard_background">
						
					</div>
				</div>
				</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_event';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body eventnotice">
						<div class="info-box-stats">
							<p class="counter">
							<?php 
							$args['post_type'] = array('hmgt_event','hmgt_notice');
							$args['posts_per_page'] = -1;
							$args['post_status'] = 'public';
							$q = new WP_Query();
							$retrieve_class = $q->query( $args );
								echo count($retrieve_class);
							?></p>
							
							<span class="info-box-title"><?php echo _e( __( 'Events/ <BR> Notice', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/notice-event-image.png"?>" class="dashboard_background">
						
					</div>
				</div>
			</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_report';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body operation_report">
						<div class="info-box-stats">
							<p class="counter">&nbsp;</p>
							
							<span class="info-box-title"><?php echo esc_html( __( 'Reports', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/report.png"?>" class="dashboard_background">
					</div>
				</div>
				</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_pharmacist';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body pharmacist">
						<div class="info-box-stats">
							<p class="counter"><?php echo count(get_users(array('role'=>'pharmacist')));?></p>
							
							<span class="info-box-title"><?php echo esc_html( __( 'Pharmacist', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/pharmacist.png"?>" class="dashboard_background">
					</div>
				</div>
				</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_medicine';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body medicine">
						<div class="info-box-stats">
							<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_medicine');?></p>
							
							<span class="info-box-title"><?php echo esc_html( __( 'Medicines', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/medicine.png"?>" class="dashboard_background"> 
						
					</div>
				</div>
				</a>
			</div>
			
			
			
		<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_laboratorist';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body laboratorist">
						<div class="info-box-stats">
							<p class="counter"><?php echo count(get_users(array('role'=>'laboratorist')));?></p>
							
							<span class="info-box-title"><?php echo _e( __( 'Laboratory <BR> Staff', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/laboratorist.png"?>" class="dashboard_background">
					</div>
				</div>
				</a>
			</div>
			
				<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_diagnosis';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body diagnosis">
						<div class="info-box-stats">
							<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_diagnosis');?></p>
							
							<span class="info-box-title"><?php echo _e( __( 'Diagnosis <BR> Reports', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/diagnosis-image.png"?>" class="dashboard_background">
						
					</div>
				</div>
			</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_accountant';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body accountant">
						<div class="info-box-stats">
							<p class="counter"><?php echo count(get_users(array('role'=>'accountant')));?></p>
							<span class="info-box-title"><?php echo esc_html( __( 'Accountant', 'hospital_mgt' ) );?></span>
						</div>					
                        <img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/accountant.png"?>" class="dashboard_background">
					</div>
				</div>
				</a>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=hmgt_invoice';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body invoice">
						<div class="info-box-stats">
							<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_invoice');?></p>
							
							<span class="info-box-title"><?php echo esc_html( __( 'Invoice', 'hospital_mgt' ) );?></span>
						</div>
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/invoice.png"?>" class="dashboard_background"> 
					</div>
				</div>
				</a>
			</div>		
		</div>		
		<!-- Rinkal changes --> 
		<div class="row dashboard_panel_heading_border">
			<div class="col-md-6 no-paddingR">
				<!--  Start Prescription Box -->
				<div class="panel panel-white event priscription">
					<div class="panel-heading ">					
					<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/Prescription.png"?>" >
						<h3 class="panel-title"><?php _e('Prescription','hospital_mgt');?></h3>						
					</div>
					<div class="panel-body">
						<div class="events">
							 <?php 
							 $obj_var=new MJ_hmgt_prescription();
							$prescriptiondata=$obj_var->get_prescription_on_admin_dashboard();
							
							if(!empty($prescriptiondata))
							{
								foreach ($prescriptiondata as $retrieved_data)
								{ 
								?>								
									<div class="calendar-event"> 
										<p class="remainder_title_pr Bold viewpriscription show_task_event" id="<?php echo $retrieved_data->priscription_id; ?>" model="Prescription Details" >  <?php _e('Patient Name','hospital_mgt');?> : 
										<?php 	$patient = MJ_hmgt_get_user_detail_byid( $retrieved_data->patient_id);
												echo  $patient['first_name']." ".$patient['last_name'];
											?>
										</p>
										<p class="remainder_date_pr"> <?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->pris_create_date));?> </p>
										<p class="remainder_title_pr viewpriscription" > <?php _e('Description','hospital_mgt');?>	 : <?php
											if($retrieved_data->prescription_type=='report')
											{		
												echo $retrieved_data->report_description; 
											}
											else
											{
												echo $retrieved_data->case_history; 
											}											
										?></p>
									</div>	
							<?php
								}
							}	
							?>	
						</div>                       
					</div>
				</div>
				<!-- End Prescription Box -->
						
				<!--  Start Operation Box -->
				<div class="panel panel-white event operation">
					<div class="panel-heading ">
					<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/Operation-List.png"?>" >
					<h3 class="panel-title"><?php _e('Operation','hospital_mgt');?></h3>						
					</div>
					<div class="panel-body">
						<div class="events">
							<?php
							$obj_ot = new MJ_hmgt_operation();
							$ot_data=$obj_ot->get_operation_on_admin_dashboard();
							if(!empty($ot_data))
							{
								foreach ($ot_data as $retrieved_data)
								{		
									$patient_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->patient_id);		
								?>
									<div class="calendar-event"> 
										<p class="remainder_title_pr Bold viewoperation show_task_event" id="<?php echo $retrieved_data->operation_id; ?>" model="Operation Details">	<?php _e('Patient Name','hospital_mgt');?> : <?php echo $patient_data['first_name']." ".$patient_data['last_name']; ?></p>
										<p class="remainder_date_pr"> <?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->operation_date));	?> 	</p>
										
										<p class="remainder_title_pr  viewoperation"  > <?php _e('Operation Name','hospital_mgt');?>: <?php echo $obj_ot->get_operation_name($retrieved_data->operation_title);?></p>
									</div>	
								<?php
								}
							}	
							?>		
						</div>                       
					</div>
				</div>
				<!-- End Operation Box -->
			
				<div class="panel panel-white">
				   <div class="panel-heading" style="margin-bottom: 15px;">
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/calender.png"?>" >
								<h3 class="panel-title"><?php _e('Calendar','hospital_mgt');?></h3>			
							</div>
					<div class="panel-body">
						<div id="calendar"></div>
					</div>
				</div>
				
			 </div>
			<!-- Start Appointment Box -->
				<div class="col-md-6">
			
					<div class="panel panel-white Appoinment">
						<div class="panel-heading">
						<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/Appointment.png"?>" >
						<h3 class="panel-title"><?php _e('Appointment','hospital_mgt');?></h3>						
						</div>
						<div class="panel-body">
							<div class="events">
								<?php								
								$appointment_data=$obj_appointment->get_appointment_on_admin_dashboard();
								
								if(!empty($appointment_data))
								{
									foreach ($appointment_data as $retrieved_data)
									{		
										$patient_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->patient_id);
									?>									
									<div class="calendar-event"> 
										<p class="remainder_title Bold save1 show_task_event" id="<?php echo $retrieved_data->appointment_id; ?>" model="Appointment Details">
										<?php _e('Patient Name','hospital_mgt');?> : <?php  echo $patient_data['first_name']." ".$patient_data['last_name']; ?>  </p>
										<p class="remainder_date" style="width: 160px;background-position: 85px;">
										<?php 
										echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->appointment_date)); ?>		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $retrieved_data->appointment_time_with_a; ?>
										</p>
									</div>	
									<?php
									}
								}	
								?>
							</div>					
						</div>
					</div>
				<!--  End Appoinment box -->
				 
				<!--  Start assigned bed Box -->
				<div class="panel panel-white event assignbed">
					<div class="panel-heading">
					<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/Assign--Bed-nurse.png"?>" >
					<h3 class="panel-title"><?php _e('Assigned Bed','hospital_mgt');?></h3>						
					</div>
					<div class="panel-body">
						<div class="events">
							<?php
							$obj_bed = new MJ_hmgt_bedmanage();
							$bedallotment_data=$obj_bed->get_bedallotment_on_admin_dashboard();
							if(!empty($bedallotment_data))
							{
								foreach ($bedallotment_data as $retrieved_data)
								{
								?>									
									<div class="calendar-event"> 
										<p class="remainder_title Bold viewbedlist show_task_event" id="<?php echo $retrieved_data->bed_allotment_id; ?>" model="Assigned Bed Details" > <?php _e('Patient Name','hospital_mgt'); ?> : 	  
											<?php
											$patient_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->patient_id);	
											echo $patient_data['first_name']." ".$patient_data['last_name'];
											?>
										</p>
										<p class="remainder_date">	<?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->allotment_date));?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->discharge_time));?> </p>
									</div>		
								<?php
								}
							}	
							?>	
						</div>                       
					</div>
				</div>
				<!-- End assigned bed Box -->
				<!--  Start Event Box -->
				
				<div class="panel panel-white event">
					<div class="panel-heading ">
					<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/event.png"?>" >
					<h3 class="panel-title"><?php _e('Events','hospital_mgt');?></h3>						
					</div>					
					<div class="panel-body">
						<div class="events">	
						<?php         
						$args = array(
						  'numberposts' => 3,
						  'post_type'   => 'hmgt_event',
						  'order'     => 'DESC',
						  'orderby'   => 'ID'
						);
						
						$retrieve_class = get_posts($args);
						
						if(!empty($retrieve_class))
						{ 
							foreach ($retrieve_class as $retrieved_data)
							{ 
							?>
								<div class="calendar-event">
									<p class="remainder_title Bold viewdetail show_task_event" id="<?php echo $retrieved_data->ID; ?>" model="Event Details">
										<?php echo $retrieved_data->post_title; ?>
									</p>									
									<p class="remainder_date">
										<?php echo date(MJ_hmgt_date_formate(),strtotime(get_post_meta($retrieved_data->ID,'start_date',true))); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo date(MJ_hmgt_date_formate(),strtotime(get_post_meta($retrieved_data->ID,'end_date',true))); ?>
									</p>
									<p class="remainder_title viewdetail">	
										<?php echo $retrieved_data->post_content; ?>
									</p>
								</div>
							<?php
							}
						}
						?>
						</div>                       
					</div>
				</div>
				<!-- End Event Box -->
				<!--  Start Notice box -->
				<div class="panel panel-white nt">
					<div class="panel-heading">
					<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/notice1.png"?>" >
					<h3 class="panel-title"><?php _e('Notice','hospital_mgt');?></h3>						
					</div>
					<div class="panel-body">
						<div class="events">
							<?php         
							$args = array(
							  'numberposts' => 3,
							  'post_type'   => 'hmgt_notice',
							  'order'     => 'DESC',
							  'orderby'   => 'ID'
							);
							
							$retrieve_class = get_posts($args);
							
							if(!empty($retrieve_class))
							{ 
								foreach ($retrieve_class as $retrieved_data)
								{ 
								?>						
									<div class="calendar-event"> 
										<p class="remainder_title Bold viewdetail show_task_event" id="<?php echo $retrieved_data->ID; ?>" model="Notice Details">	
											<?php echo $retrieved_data->post_title; ?>	
										</p>
										<p class="remainder_date">	<?php echo date(MJ_hmgt_date_formate(),strtotime(get_post_meta($retrieved_data->ID,'start_date',true))); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo date(MJ_hmgt_date_formate(),strtotime(get_post_meta($retrieved_data->ID,'end_date',true))); ?></p>
										<p class="remainder_title viewdetail"><?php echo $retrieved_data->post_content; ?></p>
									</div>	
							<?php
							}
						}
						?>					
						</div>
					</div>
				</div>
				<!--  End Notice box -->
			</div>
		</div><!--  end ROW DIV -->	
	</div><!--  end MAIN WRAPPER DIV -->	
</div><!--  end PAGE INNER DIV -->	