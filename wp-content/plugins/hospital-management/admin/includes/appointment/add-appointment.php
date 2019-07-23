<?php
$edit = 0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$appointment_id = $_REQUEST['appointment_id'];
	$result = $obj_appointment->get_single_appointment($appointment_id);
	
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
		
		if(!empty($patient_id) && !empty($doctor_id))
		{
		$result_appointment_time=$wpdb->get_results("SELECT apointment_time,apointment_startdate,apointment_enddate  FROM $table_appointment_time where day='".$weekday."' and '$date' between apointment_startdate and apointment_enddate  and user_id=".$doctor_id."");
		
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
	
			 jQuery(document).ready(function($) {
		
			    var appointment_times_array = <?php echo $appointment_times; ?>					
				
				$.each( appointment_times_array, function( i, val ) {
					  new_val="";
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
jQuery(document).ready(function($) {
	$('#patient_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

		var date = new Date();
			date.setDate(date.getDate()-0);
			$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
			
			$('#appointment_date').datepicker({
			startDate: date,
			autoclose: true
		   }); 
} );
</script>
<?php 	
if($active_tab == 'addappointment')
{
	MJ_hmgt_browser_javascript_check();
	?>		
    <div class="panel-body"><!-- PANEL BODY DIV START-->
        <form name="patient_form" action="" method="post" class="form-horizontal" id="patient_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="appointment_id" value="<?php if(isset($_REQUEST['appointment_id'])) echo $_REQUEST['appointment_id'];?>"  />
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
			<div class="form-group">
				<label class="col-sm-2 control-label" for="middle_name"><?php _e('Select Doctor','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<?php $doctors = MJ_hmgt_getuser_by_user_role('doctor');					
						
						?>
					<select name="doctor_id" class="form-control validate[required] " id="doctor">
					<option value=""><?php  _e('Select Doctor ','hospital_mgt');?></option>
					<?php 
						
						$doctory_data=$result->doctor_id;
						if(!empty($doctors))
						{
							foreach($doctors as $doctor)
							{							
							echo '<option value='.$doctor['id'].' '.selected($doctory_data,$doctor['id']).'>'.$doctor['first_name'].' - '.MJ_hmgt_doctor_specialization_title($doctor['id']).'</option>';
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="apointment_time_reset form-group">
				<label class="col-sm-2 control-label" for="bed_number"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-6">
					<input id="appointment_date" class="form-control validate[required] text-input appointment_date appointment_gatedate" 
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
					<p> <h3 style="color:#4CAF50;"><?php _e('Green box is available appointments', 'hospital_mgt' ); ?> </h3>
					</p>
					<p> <h3 style="color:#008CBA;"><?php _e('Blue box is already Booked appointments', 'hospital_mgt' );?></h3>
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
						<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3 appointment_padding_border col_xs_3_css">	
							<span style="border: 1px solid #000;padding: 0;" class="appointment_col_md_12 col-md-12">  
							<span style="padding: 5px;border-bottom: 1px solid #000;text-align: center;background: #f2f2f2;"  class="appointment_col_md_12 col-md-12 time_font_size"><?php echo MJ_hmgt_appoinment_time_language_translation($value); ?></span>
							<span style="text-align: center; padding: 0;" class="appointment_col_md_12 col-md-12"> <span class="removeselect selected_<?php print str_replace(":","_","$key")?>" style="float: left;width: 100%;padding: 0px;"> 
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
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 appointment_padding_border" align="center">		
					<?php _e('Afternoon', 'hospital_mgt' ); ?>
				</div>
				<?php 
				$afternoon_time=array("12:00"=>"12:00PM","12:15"=>"12:15PM","12:30"=>"12:30PM","12:45"=>"12:45PM","01:00"=>"01:00PM","01:15"=>"01:15PM","01:30"=>"01:30PM","01:45"=>"01:45PM","02:00"=>"02:00PM","02:15"=>"02:15PM","02:30"=>"02:30PM","02:45"=>"02:45PM","03:00"=>"03:00PM","03:15"=>"03:15PM","03:30"=>"03:30PM","03:45"=>"03:45PM","04:00"=>"04:00PM","04:15"=>"04:15PM","04:30"=>"04:30PM","04:45"=>"04:45PM","05:00"=>"05:00PM","05:15"=>"05:15PM","05:30"=>"05:30PM","05:45"=>"05:45PM");
				 
				 $i = 0;
				foreach ($afternoon_time as $key => $value)
				{ 
				  ?>
						<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3 appointment_padding_border col_xs_3_css">	
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
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 appointment_padding_border" align="center">		
					<?php _e('Evening', 'hospital_mgt' ); ?>
				</div>
			 
				<?php 
				$evening_time=array("06:00"=>"06:00PM","06:15"=>"06:15PM","06:30"=>"06:30PM","06:45"=>"06:45PM","07:00"=>"07:00PM","07:15"=>"07:15PM","07:30"=>"07:30PM","07:45"=>"07:45PM","08:00"=>"08:00PM");
				 
				 $i = 0;
				foreach ($evening_time as $key => $value)
				{ 
				  ?>
						<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3 appointment_padding_border col_xs_3_css">	
							<span style="border: 1px solid #000;padding: 0;" class="appointment_col_md_12 col-md-12">  
							<span style="padding: 5px;border-bottom: 1px solid #000;text-align: center;background: #f2f2f2;"  class="appointment_col_md_12 col-md-12 time_font_size"><?php echo MJ_hmgt_appoinment_time_language_translation($value); ?></span>
							<span style="text-align: center; padding: 0;" class="appointment_col_md_12 col-md-12"> <span class="removeselect selected_<?php print str_replace(":","_","$key")?> " style="float: left;width: 100%;padding: 0px;">
							<input type="radio" name="realtime" class="time" value="<?php echo $value;?>"></input>
							<input type="hidden" name="timeabc[<?php echo $value;?>]" class="time" value="<?php echo $value;?>"></input>
							<input type="hidden" name="time[<?php echo $value;?>]" value="<?php echo $key;?>  "></input> </span>
							</span>
						</div>
					<?php  
					$i++; 
				} 
				?>
				</div>
			</div>		
			<div class="form-group margin_bottom_5px">
				<label class="col-sm-2 control-label " for="enable"><?php _e('Send SMS','hospital_mgt');?></label>
				<div class="col-sm-8 margin_bottom_5px">
					 <div class="checkbox">
						<label>
							<input id="chk_sms_sent11" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="hmgt_sms_service_enable">
						</label>
					</div>				 
				</div>
			</div>			
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_appointment" class="btn btn-success"/>
			</div>
		</form>
    </div> <!-- PANEL BODY DIV END-->  
<?php 
}
?>