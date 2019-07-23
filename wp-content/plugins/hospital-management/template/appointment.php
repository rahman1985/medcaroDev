<?php 
MJ_hmgt_browser_javascript_check();
$obj_appointment = new MJ_hmgt_appointment();
$hospital_obj=new Hospital_Management(get_current_user_id());
//access right
$user_access=MJ_hmgt_get_userrole_wise_access_right_array();

if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJ_hmgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJ_hmgt_access_right_page_not_access_message();
				die;
			}			
		} 
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJ_hmgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJ_hmgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.apointment_times_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	
		var date = new Date();
            date.setDate(date.getDate()-0);
	        $.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
    
		$('#appointment_date').datepicker({
	        startDate: date,
            autoclose: true
           });
      
		$('.appointmet_sdate').datepicker({
	        startDate: date,
            autoclose: true
           }).on('changeDate', function(){
			$('#appointment_time_enddate').datepicker('setStartDate', new Date($(this).val()));
		}); 
		
		$('#appointment_time_enddate').datepicker({
	        startDate: date,
            autoclose: true
           }).on('changeDate', function(){
			$('.appointmet_sdate').datepicker('setEndDate', new Date($(this).val()));
		});
		
		$("#appointment_time_startdate").change(function()
		{	
			
			var apointment_date  = $('#appointment_time_startdate').val();
			
			if(apointment_date=="")
			{
				var already_appointment_set_time =$('#already_appointment_set_time').val();
				
				var apointment_date=$('#appointment_time_startdate').val(already_appointment_set_time);
			}
			else
			{
				
			
				$('.checked .avilable_time').prop('checked', false);
				$('.checked').removeClass('checked');	
				$('.appointment_note').css("display", "none");				

				  var curr_data = {
							 action: 'MJ_hmgt_onchage_gate_apointment_time_avilability',
							 apointment_date: apointment_date,
							 dataType: 'json'
							 };
							 
						$.post(hmgt.ajax, curr_data, function(response) {
						
						var json_obj = $.parseJSON(response);	
						
						var dateformate_value=json_obj['dateformate'];
						
						if(dateformate_value == 'Y-m-d')
						{						
							var dateformate='YYYY-MM-DD';
						}
						if(dateformate_value == 'm/d/Y')
						{						
							var dateformate='MM/DD/YYYY';
						}	
						if(dateformate_value == 'd/m/Y')
						{						
							var dateformate='DD/MM/YYYY';
						}				
						if(dateformate_value == 'F j, Y')
						{					
							var dateformate='MMMM DD,YYYY';				
						}
						
						var result=json_obj['result'];
						
						if(result!="")
						{	
							
							var date=json_obj['date'];
							var sdate = moment(date.apointment_startdate).format(dateformate);			
							var appstartdate=sdate;
							var edate = moment(date.apointment_enddate).format(dateformate);
							var enddate=edate;	
							$('.appointment_note').css("display", "block");
							$('.appointment_msg').html('You have Allready Appointment Time Set '+appstartdate+' to '+edate+' You Want To Edit It...');
							
							$('#already_appointment_set_time').val('');
							$('#already_appointment_set_time').val(appstartdate);
							
							var apointment_date  = $('#appointment_time_startdate');
							apointment_date.datepicker('setEndDate',edate );
							var enddate = $('#appointment_time_enddate');
							enddate.datepicker('option', 'minDate', appstartdate);	
							enddate.datepicker('setDate', edate);
							
							for (var i in result) 
							{
							   var apointment_startdate=result[i].apointment_startdate;
							   var apointment_enddate=result[i].apointment_enddate;
							   var time=result[i].apointment_time;
							   var day=result[i].day;
								var apointment_sdate = moment(apointment_startdate).format(dateformate);
								$('#appointment_time_startdate').val(apointment_sdate);
								var apointment_edate = moment(apointment_enddate).format(dateformate);
								$('#appointment_time_enddate').val(apointment_edate); 
							   var time = time.replace(":","_");					 
							   var day = day;
							   
							   $('.selected_'+time+'_'+day).addClass("checked"); 				 
							
								$('.checked .avilable_time').prop('checked', true); 
								
							}					
							return true;					
						}
						else
						{	
							
							$('.appointment_note').css("display", "none");
							
							var enddate = $('#appointment_time_enddate');
							var startDate = $('.appointmet_sdate').datepicker('getDate');
													
							startDate.setDate(startDate.getDate() + 7);
							enddate.datepicker('option', 'minDate', startDate);	
							enddate.datepicker('setDate', startDate); 
							
						}	
					}); 
			}	
		}); 
		 $("#appointment_date").change(function()
		{
			
			   $('.removeselect').css("background","#FFFFFF");			    
			   $('.removeselect').removeClass("select"); 
			   $('.removeselect .time').css('visibility', 'hidden');			   
			   $('.removeselect').removeClass("checked"); 
			   $('.removeselect .time').prop('checked', false); 
			 
			 var apointment_date  = $('#appointment_date').val() ;
			 var doctor_id =$('#doctor').val();
			 var patient_id =$('#patient').val();
			
			var date1 = $('#appointment_date').datepicker('getDate');
			var day = date1.getDay();	
				if (day == 1)
				{
					var dayofweek="monday";
				}
				if (day == 2){
					var dayofweek="tuesday";
				}
				if (day == 3)
				{
					var dayofweek="wednesday";
				}
				if (day == 4){
					var dayofweek="thursday";
				}
				if (day == 5)
				{
					var dayofweek="friday";
				}
				if (day == 6){
					var dayofweek="saturday";
				}
				if (day == 7){
					var dayofweek="sunday";
				}
	 		  var curr_data = {
	 					 action: 'Mj_hmgt_onchage_gate_apointment_time',
	 					 apointment_date: apointment_date,			
						 doctor_id: doctor_id,	
	 					 patient_id: patient_id,	
						 dayofweek: dayofweek,					 
	 					 dataType: 'json'
	 					 };
						 
	 				$.post(hmgt.ajax, curr_data, function(response) {
					
					var json_obj = $.parseJSON(response);
										
					 var new_val ="";
					 $.each( json_obj, function( i, val ) {
						
						
					  new_val = val.replace(":","_");
					 
					 
				      $('.selected_'+new_val).css("background","#4CAF50");
				      $('.selected_'+new_val).addClass("select"); 
				      $('.select .time').css("visibility","visible");
					
					 }); 					
	 			 return true;				 
	 			 });
		    }); 
	
		$('.appointment_date').change(function(){
			
			  $('.removeselect').css("background","#FFFFFF");			    
			  $('.removeselect').removeClass("select_apointment"); 
			  $('.removeselect .time').css('visibility', 'hidden');
			 
			 var apointment_date  = $('#appointment_date').val();
			 var edit_apointment_date  = $('#hide_date_value').val();
			 var edit_apointment_time  = $('#hide_time_value').val();
			 var doctor_id =$('#doctor').val() ;
			 var patient_id =$('#patient').val() ;
			
			 var curr_data = {
	 					action: 'MJ_hmgt_onchage_gate_apointment',
	 					apointment_date: apointment_date,			
	 					doctor_id: doctor_id,						
	 					edit_apointment_date: edit_apointment_date,						
	 					edit_apointment_time: edit_apointment_time,						
	 					patient_id: patient_id,						
	 					dataType: 'json'
	 					};
				    $.post(hmgt.ajax, curr_data, function(response) {
						
					var json_obj = jQuery.parseJSON(response);	
					var new_val ="";
					$.each(json_obj['book_appointment_time'], function( i, val ) {
					new_val = val.replace(":","_");
				    $('.selected_'+new_val).css("background","#008CBA");
				    $('.selected_'+new_val).addClass("select_apointment"); 
				    $('.select_apointment .time').css("visibility","hidden");
					});
					$.each(json_obj['edit_appointment_time'], function( i, val ) {
					
						time = val.replace(":","_");
						
					   $('.selected_'+time).css("background","#4CAF50");
				       $('.selected_'+time).addClass("edited_select"); 
				       $('.edited_select .time').css("visibility","visible");					
					   $('.selected_'+time).addClass("checked"); 				
				       $('.checked .time').prop('checked', true); 
					});
	 			return true;
	 			});
		 });   
});

</script>

<?php 
//SAVE Appointment TIME DATA
 if(isset($_REQUEST['save_appointment_time']))
{	
	$result = $obj_appointment->hmgt_add_appointment_time($_POST);
	
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=appointment&tab=appointmentlist&message=4');
	}	
}
// SAVE Appointment DATA	
if(isset($_REQUEST['save_appointment']))
{
	
	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{
		
           global $wpdb;
		   $doctor_id=$_POST['doctor_id'];
		   $apointment_dates=$_POST['appointment_date'];
		   $apointment_time=$_POST['time'];
		   $bb = $_POST['time'];
		   $apointment_time=$bb[$_POST['realtime']]; 
		   
		  $aa = $_POST['timeabc'];
		  $time_with_ampm=$aa[$_POST['realtime']];
		
		   $table_appointment_time = $wpdb->prefix. 'hmgt_apointment_time';
		   $table_appointment_time_data= $wpdb->get_row("SELECT * FROM $table_appointment_time where apointment_time='".$apointment_time."'
		   and user_id=".$doctor_id);
		   if(!empty($table_appointment_time_data))
		   {
		   global $wpdb;
		   $table_appointment = $wpdb->prefix. 'hmgt_appointment';
		   $table_appointment_data= $wpdb->get_row("SELECT * FROM $table_appointment where apointment_time='".$apointment_time."'
		   and doctor_id=".$doctor_id);
		   if(empty($table_appointment_data))
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
				$message1 = "The Appointment has been booked for $patient_name with Dr. $doctor_name on DATE : ".$_REQUEST['appointment_date']." TIME : ".$time_with_ampm;
				//$message = str_replace(" ","%20",$message1);
				$current_sms_service = get_option( 'hmgt_sms_service');
				if($current_sms_service == 'clickatell')
				{				 
				
					$clickatell=get_option('hmgt_clickatell_sms_service');
					$username = urlencode($clickatell['username']);
					$password = urlencode($clickatell['password']);
					$api_id = urlencode($clickatell['api_key']);
					$to1 = $doctor_number;
					$to2 = $patient_number;
					$message = urlencode($message1);
					$send=file_get_contents("https://api.clickatell.com/http/sendmsg". "?user=$username&password=$password&api_id=$api_id&to=$to1,$to2&text=$message");
				}
				if($current_sms_service == 'twillo')
				{
					//Twilio lib
					require_once HMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
					$twilio=get_option( 'hmgt_twillo_sms_service');
					$account_sid = $twilio['account_sid']; //Twilio SID
					$auth_token = $twilio['auth_token']; // Twilio token
					$from_number = $twilio['from_number'];//My number
					
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
				wp_redirect ( home_url() . '?dashboard=user&page=appointment&tab=appointmentlist&message=2');
			}
			else 
			{
				wp_redirect ( home_url() . '?dashboard=user&page=appointment&tab=appointmentlist&message=1');
			}	

		}
		   }
		   else{
			   wp_redirect ( home_url() . '?dashboard=user&page=appointment&tab=appointmentlist&message=6');
			   
		   }
		
		   }
		   
		   else{
			   wp_redirect ( home_url() . '?dashboard=user&page=appointment&tab=appointmentlist&message=5');
		     }
		 
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_appointment->delete_appointment(MJ_hmgt_id_decrypt($_REQUEST['appointment_id']));
	if($result)
	{
			wp_redirect ( home_url() . '?dashboard=user&page=appointment&tab=appointmentlist&message=3');
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
				_e('Appointment booked successfully','hospital_mgt');
			?></p></div>
			<?php 
		
	}
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Appointment updated successfully.",'hospital_mgt');
				?></p>
				</div>
			<?php 
		
	}
	elseif($message == 3) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Appointment deleted successfully','hospital_mgt');
	?></div></p><?php
			
	}
	
	elseif($message == 4) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Appointment time inserted successfully','hospital_mgt');
	?></div></p><?php
			
	}
	elseif($message == 5) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('This time not available appointment','hospital_mgt');
	?></div></p><?php
			
	}
	elseif($message == 6) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('This time already appointment booking , select another time','hospital_mgt');
	?></div></p><?php
			
	}
}	

$active_tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'appointmentlist';
	?>
	 <?php
	if($obj_hospital->role == 'patient') 
	{		 
		?>
		<script type="text/javascript">
		$(document).ready(function()
		{
			jQuery('#appointment_list').DataTable({ 
			"responsive": true, 			
			"aoColumns":[
						  {"bSortable": true},
						  {"bSortable": true},
						  {"bSortable": true},
						 <?php	
						if($user_access['edit']=='1' || $user_access['delete']=='1')
						{
						?>
							{"bSortable": true},	
						<?php
						}
						?>						
					   ],
			language:<?php echo MJ_hmgt_datatable_multi_language();?>
			});
			
		} );
		</script>
	<?php	
	}
	elseif($obj_hospital->role == 'doctor')
	{
		?>
		<script type="text/javascript">
		$(document).ready(function() {
			jQuery('#appointment_list').DataTable({
				"responsive": true,
				"order": [[ 0, "Desc" ]],
			language:<?php echo MJ_hmgt_datatable_multi_language();?>
			});
			
		} );
		</script>
	<?php
	}
	else
	{
	?>
		<script type="text/javascript">
		$(document).ready(function()
		{
			jQuery('#appointment_list').DataTable({ 
			"responsive": true, 
			"order": [[ 0, "Desc" ]],
			language:<?php echo MJ_hmgt_datatable_multi_language();?>
			});
			
		} );
		</script>
		<?php 
	}
		?>
<div class="panel-body panel-white"><!-- START PANEL BODY DIV -->
	<ul class="nav nav-tabs panel_tabs" role="tablist"><!-- START NAV TABS-->
		  <li class="<?php if($active_tab == 'appointmentlist'){?>active<?php }?>">
		  
			  <a href="?dashboard=user&page=appointment&tab=appointmentlist">
				 <i class="fa fa-align-justify"></i> <?php _e('Appointment List', 'hospital_mgt'); ?></a>
			  </a>
		  </li>     
			<li class="<?php if($active_tab=='addappoint'){?>active<?php }?>">
		  <?php 
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				?>
				<a href="?dashboard=user&page=appointment&tab=addappoint&&action=edit&appointment_id=<?php echo $_REQUEST['appointment_id'];?>" class="tab <?php echo $active_tab == 'addappoint' ? 'active' : ''; ?>">
				<i class="fa fa"></i> <?php if($obj_hospital->role == 'patient') {  _e('Edit Request Appointment', 'hospital_mgt'); }else{ _e('Edit Appointment', 'hospital_mgt'); } ?></a>
			 <?php 
			}
			else
			{
				if($user_access['add']=='1')
				{			
				?>				
					<a href="?dashboard=user&page=appointment&tab=addappoint&&action=insert" class="tab <?php echo $active_tab == 'addappoint' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php if($obj_hospital->role == 'patient') {  _e('Request Appointment', 'hospital_mgt'); }else{ _e('Add Appointment', 'hospital_mgt'); } ?></a>
				<?php
				}
			}
			?>	  
		</li>
		  <?php
			if($obj_hospital->role == 'doctor')
			{
			?>
			  <li class="<?php if($active_tab == 'appointment_time'){?>active<?php }?>">
				  <a href="?dashboard=user&page=appointment&tab=appointment_time">
					 <i class="fa fa-align-justify"></i> <?php _e('Appointment Time', 'hospital_mgt'); ?></a>
				  </a>
			  </li>	  
			<?php
			}
			?>
	</ul><!-- END NAV TABS -->
	<div class="tab-content"><!-- SRAER TAB CONTENT DIV -->
		<div class="tab-pane <?php if($active_tab == 'appointmentlist'){?>fade active in<?php }?>" id="appointmentlist"><!-- END TAB PANE DIV-->
			<div class="panel-body"><!-- STAR PANEL BODY DIV -->
				<div class="table-responsive"><!--TABLE RESPONSIVE DIV -->
			   <table id="appointment_list" class="display dataTable " cellspacing="0" width="100%"><!-- START Appointment LIST TABLE -->
					 <thead>
					<tr>
					<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>
					 <th><?php _e( 'Patient ', 'hospital_mgt' ) ;?></th>
					  <th><?php _e( 'Doctor', 'hospital_mgt' ) ;?></th>       
						<?php
						if($user_access['edit']=='1' || $user_access['delete']=='1')
						{
						?>
							<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>			 
						<?php
						}
						?>
					</tr>
				</thead>
				<tfoot>
					<tr>
					<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>
					 <th><?php _e( 'Patient ', 'hospital_mgt' ) ;?></th>
					  <th><?php _e( 'Doctor', 'hospital_mgt' ) ;?></th>              
						<?php
						if($user_access['edit']=='1' || $user_access['delete']=='1')
						{
						?>
							<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>			 
						<?php
						}
						?>
					</tr>
				</tfoot>
			<tbody>
				 <?php 
				if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'pharmacist' || $obj_hospital->role == 'accountant' || $obj_hospital->role == 'receptionist') 
				{
				   $own_data=$user_access['own_data'];
					if($own_data == '1')
					{ 
					   $appointment_data=$obj_appointment->get_all_appointment_by_create_by();
					}
					else
					{
						$appointment_data=$obj_appointment->get_all_appointment();
					}
				}
				elseif($obj_hospital->role == 'doctor') 
				{
				   $own_data=$user_access['own_data'];
					if($own_data == '1')
					{ 
					   $appointment_data=$obj_appointment->get_doctor_all_appointment_by_create_by();
					}
					else
					{
						$appointment_data=$obj_appointment->get_all_appointment();
					}
				}
				elseif($obj_hospital->role == 'nurse') 
				{
				   $own_data=$user_access['own_data'];
					if($own_data == '1')
					{ 
					   $appointment_data=$obj_appointment->get_nurse_all_appointment_by_create_by();
					}
					else
					{
						$appointment_data=$obj_appointment->get_all_appointment();
					}
				}
				elseif($obj_hospital->role == 'patient')
				{
					$own_data=$user_access['own_data'];
					if($own_data == '1')
					{ 
					   $appointment_data=$obj_appointment->get_patient_all_appointment();
					}
					else
					{
						$appointment_data=$obj_appointment->get_all_appointment();
					}			
				}		
				
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
					   <?php
					   if($user_access['edit']=='1' || $user_access['delete']=='1')
					   {
							?>
							<td class="action"> 
							<?php
							if($user_access['edit']=='1')
							{
							?>
								<a href="?dashboard=user&page=appointment&tab=addappoint&action=edit&appointment_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->appointment_id);?>" class="btn btn-info"> 
								<?php _e('Edit', 'hospital_mgt' ) ;?></a>
							<?php
							}
							if($user_access['delete']=='1')
							{
							?>
								<a href="?dashboard=user&page=appointment&tab=appointmentlist&action=delete&appointment_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->appointment_id);?>" class="btn btn-danger" 
								onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
								<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
							<?php
							}
							?>
							</td>
							<?php
						}
						?>
					</tr>
					<?php 
					}					
				}?>
				</tbody>
				
				</table><!-- END Appointment LIST TABLE -->
			</div><!-- END TABLE RESPONSIVE DIV -->
		</div><!-- END PANEL BODY DIV -->
	</div><!-- END PANE TAB DIV -->
	<?php 
	$edit = 0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
			$edit = 1;
			$appointment_id = MJ_hmgt_id_decrypt($_REQUEST['appointment_id']);
			$result = $obj_appointment->get_single_appointment($appointment_id);
			
		//selected date avilable appointment
		$patient_timeArr[]  =$result->appointment_time;		
		$appointment_time=json_encode($result->appointment_time);
	    $date=$result->appointment_date;		
	    $doctor_id=$result->doctor_id;
	    $patient_id=$result->patient_id;	
		$str_date = date('l', strtotime($date));
		$weekday = strtolower($str_date);
		
		global $wpdb;
	    $table_appointment_time = $wpdb->prefix. 'hmgt_apointment_time';
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';
		
		if(!empty($patient_id))
		{			
			$result_appointment_time=$wpdb->get_results("SELECT apointment_time FROM $table_appointment_time where day='".$weekday."' and '$date' between apointment_startdate and apointment_enddate AND user_id=".$doctor_id."");
		
			$result_allpatient_appointments=$wpdb->get_results("SELECT appointment_time  FROM $table_appointment where appointment_date='".$date."' and doctor_id=".$doctor_id."");
		}	
		
		foreach($result_appointment_time as $time)
		{
			 $timeArr[]  =$time->apointment_time; 
		}
		$appointment_times=json_encode($timeArr);
		
		foreach($result_allpatient_appointments as $time)
		{
			 $allpatient_timeArr[]  =$time->appointment_time; 
		}
		 
		$appointment_times=json_encode($timeArr);
	
		$result_difference_appointment_time = array_diff($allpatient_timeArr, $patient_timeArr);
		$allpatient_appointment_times=json_encode($result_difference_appointment_time);
	
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				//all avilable appointment
			    var appointment_times_array = <?php echo $appointment_times; ?>					
				
				$.each( appointment_times_array, function( i, val ) {
						
					  new_val = val.replace(":","_");
				      $('.selected_'+new_val).css("background","#4CAF50");
				      $('.selected_'+new_val).addClass("select"); 
				      $('.select .time').css("visibility","visible");
					
					 }); 					
				//patient get appointment checked
			    var appointment_time_array = <?php echo $appointment_time; ?>					
				var time=appointment_time_array;				   
				var time = time.replace(":","_");
				
				$('.selected_'+time).addClass("checked"); 				
				$('.checked .time').prop('checked', true); 
				//booking appointments
				var allpatient_appointment_time_array = <?php echo $allpatient_appointment_times; ?>	
				$.each( allpatient_appointment_time_array, function( i, val ) {
				
				 new_val = val.replace(":","_");				 
				 $('.selected_'+new_val).css("background","#008CBA");
				 $('.selected_'+new_val).addClass("select_apointment"); 
				 $('.select_apointment .time').css("visibility","hidden");
				});
									
	 			return true;
			});
		</script>
	<?php
	}
	
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		
		$('#patient_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});		
	} );
	</script>
	<div class="tab-pane <?php if($active_tab == 'addappoint'){?>fade active in<?php }?>" id="add_appointment"><!-- STAR TAB PANE DIV -->
        <div class="panel-body"><!-- STAR PANEL BODY DIV -->
			<form name="patient_form" action="" method="post" class="form-horizontal " id="patient_form"><!-- STAR Appointment FORM-->
				 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="appointment_id" value="<?php if(isset($_REQUEST['appointment_id'])) echo MJ_hmgt_id_decrypt($_REQUEST['appointment_id']);?>"  />
				
				<?php
				if($obj_hospital->role == 'nurse' || $obj_hospital->role == 'doctor' || $obj_hospital->role == 'receptionist')
				{
				?>
				<div class="form-group">
				
					<label class="col-sm-2 control-label" for="middle_name"><?php _e('Select Patient','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select name="patient_id" id="patient" class="form-control validate[required] ">
							<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
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
								echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['patient_id'].' - '.$patient['first_name'].' '.$patient['last_name'].'</option>';
							}
							}
							?>
						</select>
					</div>
				
				</div>
				<?php 
				}
				elseif($obj_hospital->role == 'patient')
				{
					echo '<input type="hidden" name="patient_id" value="'.get_current_user_id().'">';
				}			
			
				if($obj_hospital->role == 'nurse' || $obj_hospital->role == 'patient' || $obj_hospital->role == 'receptionist' )
				{
				?>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="middle_name"><?php _e('Select Doctor','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<?php $doctors = MJ_hmgt_getuser_by_user_role('doctor');				
							
							?>
							
						<select name="doctor_id" class="form-control validate[required] doctor" id="doctor">
						<option value=""><?php  _e('Select Doctor','hospital_mgt');?></option>
						<?php 
						$doctory_data=$result->doctor_id;	
							if(!empty($doctors))
							{	
								foreach($doctors as $doctor)
								{							
									echo '<option value="'.$doctor['id'].'" '.selected($doctory_data,$doctor['id']).'>'.$doctor['first_name'].' - '.MJ_hmgt_doctor_specialization_title($doctor['id']).'</option>';
								}
							}
							?>
						</select>
					</div>
				</div>
				
				<?php 
					}
					elseif($obj_hospital->role == 'doctor')
					{
						echo '<input type="hidden" name="doctor_id" value="'.get_current_user_id().'">';
					}
				?>
				
				<div class="apointment_time_reset form-group">
					<label class="col-sm-2 control-label" for="bed_number"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-6">
						<input id="appointment_date" class="form-control validate[required] text-input appointment_date" 
						type="text" value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->appointment_date));}elseif(isset($_POST['appointment_date'])) echo $_POST['appointment_date'];?>" 
						name="appointment_date">
						<input type="hidden" value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->appointment_date));}?>" id="hide_date_value">
						<input type="hidden" value="<?php if($edit){ echo $result->appointment_time;}?>" id="hide_time_value">
						</div> 				
				</div>	
				<div class="form-group">
					<label class="col-sm-2 control-label" for="Note"><?php _e('Note','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-6">
					  <note>
						<p> <h3 style="color:#4CAF50;"><?php _e( 'Green box is available appointments', 'hospital_mgt' ); ?> </h3>
						</p>
						<p> <h3 style="color:#008CBA;"><?php _e( 'Blue box is already Booked appointments', 'hospital_mgt' ); ?> </h3>
						</p>
					</note>
				</div>
		   </div>		
				
			<div class="form-group">
			<label class="col-sm-2 control-label" for="time"><?php _e('Select Appointment Time','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">	
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 appointment_padding_border" align="center">		
					<?php _e('Morning', 'hospital_mgt' ); ?>
				</div>
			<?php
		   $morning_time=array("10:00"=>"10:00AM","10:15"=>"10:15AM","10:30"=>"10:30AM","10:45"=>"10:45AM","11:00"=>"11:00AM","11:15"=>"11:15AM ","11:30"=>"11:30AM","11:45"=>"11:45AM");
			
			 $i = 0;
			foreach ($morning_time as $key => $value)
			{ 
			  ?>
			  <div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 appointment_padding_border col_xs_4_css">	
					<span style="border: 1px solid #000;padding: 0;" class="appointment_col_md_12 col-md-12">  
					<span style="padding: 5px;border-bottom: 1px solid #000;text-align: center;background: #f2f2f2;"  class="appointment_col_md_12 col-md-12 time_font_size">
					<?php echo MJ_hmgt_appoinment_time_language_translation($value); ?></span>
					<span style="text-align: center; padding: 0;" class="appointment_col_md_12 col-md-12"> <span class="removeselect selected_<?php print str_replace(":","_","$key")?>" style="float: left;width: 100%;padding: 0px;">
					<input type="radio" name="realtime" class="time align_time"  value="<?php echo $value;?>"></input>
					<input type="hidden" name="timeabc[<?php echo $value;?>]" class="time" value="<?php echo $value;?>"></input>
					<input type="hidden" name="time[<?php echo $value;?>]" value="<?php echo $key;?>  "></input>
					</span>
					</span>
				</div> 
				<?php 
				$i++; 
			} 
			?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 appointment_padding_border" align="center">		
				<?php _e('Afternoon', 'hospital_mgt' ); ?>
			</div> 
			<?php 
			$afternoon_time=array("12:00"=>"12:00PM","12:15"=>"12:15PM","12:30"=>"12:30PM","12:45"=>"12:45PM","01:00"=>"01:00PM","01:15"=>"01:15PM","01:30"=>"01:30PM","01:45"=>"01:45PM","02:00"=>"02:00PM","02:15"=>"02:15PM","02:30"=>"02:30PM","02:45"=>"02:45PM","03:00"=>"03:00PM","03:15"=>"03:15PM","03:30"=>"03:30PM","03:45"=>"03:45PM","04:00"=>"04:00PM","04:15"=>"04:15PM","04:30"=>"04:30PM","04:45"=>"04:45PM","05:00"=>"05:00PM","05:15"=>"05:15PM","05:30"=>"05:30PM","05:45"=>"05:45PM");
			
			 $i = 0;
			foreach ($afternoon_time as $key => $value)
			{ 
			  ?>
				<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 appointment_padding_border col_xs_4_css">	
					<span style="border: 1px solid #000;padding: 0;" class="appointment_col_md_12 col-md-12">  
					<span style="padding: 5px;border-bottom: 1px solid #000;text-align: center;background: #f2f2f2;"  class="appointment_col_md_12 col-md-12 time_font_size"><?php echo MJ_hmgt_appoinment_time_language_translation($value); ?></span>
					<span style="text-align: center; padding: 0;" class="appointment_col_md_12 col-md-12"> <span class="removeselect selected_<?php print str_replace(":","_","$key")?> " style="float: left;width: 100%;padding: 0px;">
					<input type="radio" name="realtime" class="time" value="<?php echo $value;?>"></input>
					<input type="hidden" name="timeabc[<?php echo $value;?>]" class="time" value="<?php echo $value;?>"></input>
					<input type="hidden" name="time[<?php echo $value;?>]" value="<?php echo $key;?>  "></input></span>
					</span>
				</div> 
				<?php  
				$i++; 
			} ?>

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 appointment_padding_border" align="center">		
				<?php _e('Evening', 'hospital_mgt' ); ?>
			</div> 
			<?php 
			$evening_time=array("06:00"=>"06:00PM","06:15"=>"06:15PM","06:30"=>"06:30PM","06:45"=>"06:45PM","07:00"=>"07:00PM","07:15"=>"07:15PM","07:30"=>"07:30PM","07:45"=>"07:45PM","08:00"=>"08:00PM");
			 
			 $i = 0;
			foreach ($evening_time as $key => $value)
			{ 
			  ?>
				<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 appointment_padding_border col_xs_4_css">
					<span style="border: 1px solid #000;padding: 0;" class="appointment_col_md_12 col-md-12">  
					<span style="padding: 5px;border-bottom: 1px solid #000;text-align: center;background: #f2f2f2;"  class="appointment_col_md_12 col-md-12 time_font_size"><?php echo MJ_hmgt_appoinment_time_language_translation($value); ?></span>
					<span style="text-align: center; padding: 0;" class="appointment_col_md_12 col-md-12"> <span class="removeselect selected_<?php print str_replace(":","_","$key")?> " style="float: left;width: 100%;padding: 0px;">
					<input type="radio" name="realtime" class="time" value="<?php echo $value;?>"></input>
					<input type="hidden" name="timeabc[<?php echo $value;?>]" class="time" value="<?php echo $value;?>"></input>
					<input type="hidden" name="time[<?php echo $value;?>]" value="<?php echo $key;?>  "></input>
					</span>
					</span>
				</div> 
				<?php  
				$i++; 
			}
			?>	 
			</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label " for="enable"><?php _e('Send SMS','hospital_mgt');?></label>
					<div class="col-sm-8">
						 <div class="checkbox">
							<label>
								<input id="chk_sms_sent11" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="hmgt_sms_service_enable">
							</label>
						</div>
						 
					</div>
				</div>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php _e('Save','hospital_mgt'); ?>" name="save_appointment" class="btn btn-success"/>
				</div>
			</form><!-- END Appointment FORM-->
        </div><!-- END PANEL BODY DIV -->
	</div><!-- END TAB PANE  DIV -->
		
	<!-- doctor side  -->
	
	<!---   start add time tab -->	
	<div class="tab-pane <?php if($active_tab == 'appointment_time'){?>fade active in<?php }?>" id="add_appointment"><!-- STAR TAB PANE DIV -->
         <div class="panel-body" ><!-- STAR PANEL BODY DIV -->
				<form name="apintment_time_form" action="" method="post" class="form-horizontal apointment_times_form" id="patient_form"><!--- START Appointment TIME FORM -->
				<div class="form-group appointment_note" style="display:none;">	
					<div class="col-sm-offset-2 col-sm-10">
						<note>
							<p>
							<h3 style="color:#e21313fc;" class="appointment_msg"></h3>
							<input type="hidden" name="already_appointment_set_time" id="already_appointment_set_time" value="">	
							</p>				
						</note>
					</div>
				</div>
					<div class="form-group">
					<label class="col-sm-2 control-label" for="notice_content"><?php _e('Start Date','hospital_mgt');?><span class="require-field">*</span></label>
					
					<div class="col-sm-8">
					<input id="appointment_time_startdate" class="appointment_start_date appointmet_sdate datepicker form-control validate[required] text-input"  type="text" value="" name="appointment_time_startdate" readonly>
							
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="notice_content"><?php _e('End Date','hospital_mgt');?><span class="require-field">*</span></label>
					
					<div class="col-sm-8">
					<input id="appointment_time_enddate" class="datepicker form-control validate[required] text-input"  type="text" value="" name="appointment_time_enddate" readonly>
						
					</div>
				</div>	
			<div class="form-group">
			<label class="col-sm-2 control-label" for="time"><?php _e('Add Appointment Time','hospital_mgt');?><span class="require-field">*</span></label>
			</div>
			<?php 
				$days=array("monday"=>__('Monday','hospital_mgt'),"tuesday"=>__('Tuesday','hospital_mgt'),"wednesday"=>__('Wednesday','hospital_mgt'),"thursday"=>__('Thursday','hospital_mgt'),"friday"=>__('Friday','hospital_mgt'),"saturday"=>__('Saturday','hospital_mgt'),"sunday"=>__('Sunday','hospital_mgt'));
			?>
			<div class="form-group">
			<?php 
			foreach($days as $key => $value)
			{	
				$day=$key;	
				?>		
			
			<div class="col-sm-offset-2 col-sm-8">
				<div id="accordion" class="panel-group" aria-multiselectable="true" role="tablist">
				<div class="panel panel-default">
					<div id="heading_<?php echo $day;?>" class="panel-heading" role="tab">
					<h4 class="panel-title">
					<a class="collapsed" aria-controls="collapse_<?php echo $day;?>" aria-expanded="false" href="#collapse_<?php echo $day;?>" data-parent="#accordion" data-toggle="collapse">
				   <?php echo $value; ?></a>          	
					</h4>
				   </div>		
					<div id="collapse_<?php echo $day;?>" class="panel-collapse collapse" aria-labelledby="heading_<?php echo $day;?>" role="tabpanel" aria-expanded="false" style="height: 0px;">
					<div class="panel-body">					
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 appointment_padding_border" align="center">		
							<?php _e('Morning', 'hospital_mgt' ); ?>
						</div> 
							
						<?php						
						 $morning_time=array("10:00"=>"10:00".__('AM','hospital_mgt'),"10:15"=>"10:15".__('AM','hospital_mgt'),"10:30"=>"10:30".__('AM','hospital_mgt'),"10:45"=>"10:45".__('AM','hospital_mgt'),"11:00"=>"11:00".__('AM','hospital_mgt'),"11:15"=>"11:15".__('AM','hospital_mgt'),"11:30"=>"11:30".__('AM','hospital_mgt'),"11:45"=>"11:45".__('AM','hospital_mgt'));
						
						$i = 0;
			
						foreach ($morning_time as $key => $value)
						{ 						
							?>					
							<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 appointment_padding_border col_xs_4_css">
								<span style="border: 1px solid #000;padding: 0;" class="appointment_col_md_12 col-md-12">  
								<span style="padding: 5px;border-bottom: 1px solid #000;text-align: center;background: #f2f2f2;"  class="appointment_col_md_12 col-md-12 time_font_size"><?php echo $value; ?></span>
								<span style="text-align: center" class="appointment_col_md_12 col-md-12">
								<span class="selected_<?php print str_replace(":","_","$key")?>_<?php echo $day; ?>" style="float: left;width: 100%;padding: 0px;">
								<input type="checkbox" class="avilable_time" id="chktime" name="time[<?php echo $key;?>][<?php echo $day;?>]" value="<?php echo $key;?>"> </input>
								</span>
								</span>
							</div>
							<?php 
							$i++; 
						}
						?>				
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 appointment_padding_border" align="center">		
							<?php _e('Afternoon', 'hospital_mgt' ); ?>
						</div>
						<?php 
						$afternoon_time=array("12:00"=>"12:00".__('PM','hospital_mgt'),"12:15"=>"12:15".__('PM','hospital_mgt'),"12:30"=>"12:30".__('PM','hospital_mgt'),"12:45"=>"12:45".__('PM','hospital_mgt'),"01:00"=>"01:00".__('PM','hospital_mgt'),"01:15"=>"01:15".__('PM','hospital_mgt'),"01:30"=>"01:30".__('PM','hospital_mgt'),"01:45"=>"01:45".__('PM','hospital_mgt'),"02:00"=>"02:00".__('PM','hospital_mgt'),"02:15"=>"02:15".__('PM','hospital_mgt'),"02:30"=>"02:30".__('PM','hospital_mgt'),"02:45"=>"02:45".__('PM','hospital_mgt'),"03:00"=>"03:00".__('PM','hospital_mgt'),"03:15"=>"03:15".__('PM','hospital_mgt'),"03:30"=>"03:30".__('PM','hospital_mgt'),"03:45"=>"03:45".__('PM','hospital_mgt'),"04:00"=>"04:00".__('PM','hospital_mgt'),"04:15"=>"04:15".__('PM','hospital_mgt'),"04:30"=>"04:30".__('PM','hospital_mgt'),"04:45"=>"04:45".__('PM','hospital_mgt'),"05:00"=>"05:00".__('PM','hospital_mgt'),"05:15"=>"05:15".__('PM','hospital_mgt'),"05:30"=>"05:30".__('PM','hospital_mgt'),"05:45"=>"05:45".__('PM','hospital_mgt'));
			 
					$i = 0;			
					
					foreach ($afternoon_time as $key => $value)
					{ 
					?>
						<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 appointment_padding_border col_xs_4_css">
							<span style="border: 1px solid #000;padding: 0;" class="appointment_col_md_12 col-md-12">  
							<span style="padding: 5px;border-bottom: 1px solid #000;text-align: center;background: #f2f2f2;"  class="appointment_col_md_12 col-md-12 time_font_size"><?php echo $value; ?></span>
							<span style="text-align: center" class="appointment_col_md_12 col-md-12">
							<span class="selected_<?php print str_replace(":","_","$key")?>_<?php echo $day; ?>" style="float: left;width: 100%;padding: 0px;">
							<input type="checkbox" class="avilable_time" id="chktime" name="time[<?php echo $key;?>][<?php echo $day;?>]" value="<?php echo $key;?>"></input></span>
							</span>
						</div>	
						<?php 
						$i++; 
					} 
					?>			
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 appointment_padding_border" align="center">		
						<?php _e('Evening', 'hospital_mgt' ); ?>
					</div>			
					<?php
					$evening_time=array("06:00"=>"06:00".__('PM','hospital_mgt'),"06:15"=>"06:15".__('PM','hospital_mgt'),"06:30"=>"06:30".__('PM','hospital_mgt'),"06:45"=>"06:45".__('PM','hospital_mgt'),"07:00"=>"07:00".__('PM','hospital_mgt'),"07:15"=>"07:15".__('PM','hospital_mgt'),"07:30"=>"07:30".__('PM','hospital_mgt'),"07:45"=>"07:45".__('PM','hospital_mgt'),"08:00"=>"08:00".__('PM','hospital_mgt'));
			 
					$i = 0;		
					
					foreach ($evening_time as $key => $value)
					{ 
					?>
						<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 appointment_padding_border col_xs_4_css">
							<span style="border: 1px solid #000;padding: 0;" class="appointment_col_md_12 col-md-12">  
							<span style="padding: 5px;border-bottom: 1px solid #000;text-align: center;background: #f2f2f2;"  class="appointment_col_md_12 col-md-12 time_font_size"><?php echo $value; ?></span>
							<span style="text-align: center" class="appointment_col_md_12 col-md-12">
							<span class="selected_<?php print str_replace(":","_","$key")?>_<?php echo $day; ?>" style="float: left;width: 100%;padding: 0px;">
							<input type="checkbox" class="avilable_time" id="chktime" name="time[<?php echo $key;?>][<?php echo $day;?>]" value="<?php echo $key;?>"></input></span>
							</span>
						</div>
						<?php  
						$i++; 
					} 
					?>
					
					</div>
					</div>
				</div>        
			</div>	
			</div>
			<?php } ?>
				</div>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" id="btnsavetime" value="<?php _e('Save','hospital_mgt'); ?>" name="save_appointment_time" class="btn btn-success"/>
				</div>
			</form><!-- end appointment time form-->
		</div><!-- end PANEL BODY DIV-->
	</div><!-- end TAB PANE DIV-->
	</div><!-- end PANEL WHITE DIV-->
</div><!-- end PANEL BODY DIV-->