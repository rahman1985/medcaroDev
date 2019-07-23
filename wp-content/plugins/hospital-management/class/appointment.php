<?php 	  
class MJ_hmgt_appointment
{
	//Medicine Category
	public function hmgt_add_appointment($data)
	{
		$aa = $data['timeabc'];
		$time_with_ampm=$aa[$data['realtime']];
		$bb = $data['time'];
		$time=$bb[$data['realtime']];
		global $wpdb;
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';
		//-------usersmeta table data--------------
		$appointmentdata['appointment_time_string']=$data['appointment_date']." ".trim($time).":00";
		$appointmentdata['patient_id']=$data['patient_id'];
		$appointmentdata['doctor_id']=$data['doctor_id'];
		$appointmentdata['appointment_date']=MJ_hmgt_get_format_for_db($data['appointment_date']);
		$appointmentdata['appointment_time']=$time;
		$appointmentdata['appointment_time_with_a']=$time_with_ampm;
		$appointmentdata['appoint_create_date']=date("Y-m-d");
		$appointmentdata['appoint_create_by']=get_current_user_id();
		
		
		if($data['action']=='edit')	
		{
			
			$appointment_id['appointment_id']=$data['appointment_id'];			
			$result=$wpdb->update( $table_appointment, $appointmentdata ,$appointment_id);
			if(isset($result))
			{
				$patient=get_userdata($data['patient_id']);
			$patient_email=$patient->user_email;
			$patientname=$patient->display_name;;

			$doctor_id=get_userdata($data['doctor_id']);
			$doctor_name=$doctor_id->display_name;
			$doctor_email=$doctor_id->user_email;
			
		    $hospital_name = get_option('hmgt_hospital_name');
			$arr['{{Patient Name}}']=$patientname;			
			$arr['{{Doctor Name}}']=$doctor_name;			
			$arr['{{Appointment Time}}']=$time_with_ampm;			
			$arr['{{Appointment Date}}']=date(MJ_hmgt_date_formate(),strtotime($data['appointment_date']));			
			$arr['{{Hospital Name}}']=$hospital_name;
			$subject =get_option('MJ_hmgt_edit_appointment_patient_subject');
			
			$sub_arr['{{Doctor Name}}']=$doctor_name;
			$sub_arr['{{Appointment Time}}']=$time_with_ampm;			
			$sub_arr['{{Appointment Date}}']=date(MJ_hmgt_date_formate(),strtotime($data['appointment_date']));
			$sub_arr['{{Hospital Name}}']=$hospital_name;
			$subject = MJ_hmgt_subject_string_replacemnet($sub_arr,$subject);
			
			$message = get_option('MJ_hmgt_edit_appointment_patient_mail');
			$message_replacement = MJ_hmgt_string_replacemnet($arr,$message);	
			$to[]=$patient_email;
			//doctor mail
			$subject_doc =get_option('MJ_hmgt_edit_appointment_doctor_subject');
			$sub_doc_arr['{{Patient Name}}']=$patientname;
			$sub_doc_arr['{{Appointment Time}}']=$time_with_ampm;			
			$sub_doc_arr['{{Appointment Date}}']=date(MJ_hmgt_date_formate(),strtotime($data['appointment_date']));	
			$subject_doctor = MJ_hmgt_subject_string_replacemnet($sub_doc_arr,$subject_doc);
			
			$arr_doc['{{Doctor Name}}']=$doctor_name;
            $arr_doc['{{Patient Name}}']=$patientname;			
			$arr_doc['{{Appointment Time}}']=$time_with_ampm;			
			$arr_doc['{{Appointment Date}}']=date(MJ_hmgt_date_formate(),strtotime($data['appointment_date']));			
			$arr_doc['{{Hospital Name}}']=$hospital_name;
			$message_doc = get_option('MJ_hmgt_edit_appointment_doctor_mail');
			$message_replacement_doc = MJ_hmgt_string_replacemnet($arr_doc,$message_doc);	
			$doctormail[]=$doctor_email;
			//patient mail
			 MJ_hmgt_send_mail($to,$subject,$message_replacement);
			//doctor mail
			MJ_hmgt_send_mail($doctormail,$subject_doctor,$message_replacement_doc);
			MJ_hmgt_append_audit_log(''.__('Update appointment','hospital_mgt').'',get_current_user_id());
			return $result;
			}
			
		}
		else
		{
			$result=$wpdb->insert( $table_appointment, $appointmentdata );
			$patient=get_userdata($data['patient_id']);
			$patient_email=$patient->user_email;
			$patientname=$patient->display_name;;
				
			$doctor_id=get_userdata($data['doctor_id']);
			$doctor_name=$doctor_id->display_name;
			$doctor_email=$doctor_id->user_email;
			
		    $hospital_name = get_option('hmgt_hospital_name');
			$arr['{{Patient Name}}']=$patientname;			
			$arr['{{Doctor Name}}']=$doctor_name;			
			$arr['{{Appointment Time}}']=$time_with_ampm;			
			$arr['{{Appointment Date}}']=date(MJ_hmgt_date_formate(),strtotime($data['appointment_date']));			
			$arr['{{Hospital Name}}']=$hospital_name;
			$subject =get_option('MJ_hmgt_appointment_booking_patient_mail_subject');
			
			$sub_arr['{{Doctor Name}}']=$doctor_name;
			$sub_arr['{{Appointment Time}}']=$time_with_ampm;			
			$sub_arr['{{Appointment Date}}']=date(MJ_hmgt_date_formate(),strtotime($data['appointment_date']));
			$sub_arr['{{Hospital Name}}']=$hospital_name;
			$subject = MJ_hmgt_subject_string_replacemnet($sub_arr,$subject);
			
			$message = get_option('MJ_hmgt_appointment_booking_patient_mail_template');
			$message_replacement = MJ_hmgt_string_replacemnet($arr,$message);	
			$to[]=$patient_email;
			MJ_hmgt_send_mail($to,$subject,$message_replacement);
			//patient mail template
		    $hospital_name = get_option('hmgt_hospital_name');
		   
		    $subject =get_option('MJ_hmgt_appointment_booking_doctor_mail_subject');
			$sub_arr['{{Patient Name}}']=$patientname;
			$sub_arr['{{Appointment Time}}']=$time_with_ampm;			
			$sub_arr['{{Appointment Date}}']=date(MJ_hmgt_date_formate(),strtotime($data['appointment_date']));	
			$subject = MJ_hmgt_subject_string_replacemnet($sub_arr,$subject);
			
			$arr['{{Doctor Name}}']=$doctor_name;
            $arr['{{Patient Name}}']=$patientname;			
			$arr['{{Appointment Time}}']=$time_with_ampm;			
			$arr['{{Appointment Date}}']=date(MJ_hmgt_date_formate(),strtotime($data['appointment_date']));			
			$arr['{{Hospital Name}}']=$hospital_name;
			$message = get_option('MJ_hmgt_appointment_booking_patient_mail_template');
			$message_replacement = MJ_hmgt_string_replacemnet($arr,$message);	
			$doctormail[]=$doctor_email;
			MJ_hmgt_send_mail($doctormail,$subject,$message_replacement);		
			
			MJ_hmgt_append_audit_log(''.__('Add new appointment ','hospital_mgt').'',get_current_user_id());
			return $result;	
		   }
	}
	//get all appointment
	public function get_all_appointment()
	{
		$current_docter = wp_get_current_user();
		$id=$current_docter->ID;
		global $wpdb;
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';		
		$result = $wpdb->get_results("SELECT * FROM $table_appointment ORDER BY appointment_date DESC");
		return $result;		
	}
	//get all appointment by created by
	public function get_all_appointment_by_create_by()
	{
		$current_docter = wp_get_current_user();
		$id=$current_docter->ID;
		global $wpdb;
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';		
		$result = $wpdb->get_results("SELECT * FROM $table_appointment where appoint_create_by=$id ORDER BY appointment_date DESC");
		return $result;		
	}
	//get doctor created all appointment 
	public function get_doctor_all_appointment_by_create_by()
	{
		$current_docter = wp_get_current_user();
		$id=$current_docter->ID;
		global $wpdb;
		$array=MJ_hmgt_doctor_patientid_list(); 
		$array = implode("','",$array);
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';		
		$result = $wpdb->get_results("SELECT * FROM $table_appointment where appoint_create_by=$id OR patient_id IN ('".$array."') ORDER BY appointment_date DESC");
		return $result;		
	}
	//get nurse crated all appointment
	public function get_nurse_all_appointment_by_create_by()
	{
		$current_docter = wp_get_current_user();
		$id=$current_docter->ID;
		global $wpdb;
		$array=MJ_hmgt_nurse_patientid_list(); 
		
		$array = implode("','",$array);
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';		
		$result = $wpdb->get_results("SELECT * FROM $table_appointment where appoint_create_by=$id OR patient_id IN ('".$array."') ORDER BY appointment_date DESC");
		return $result;		
	}
	//get patient all appointment
	public function get_patient_all_appointment()
	{
		$current_patient = wp_get_current_user();
		$id=$current_patient->ID;
		global $wpdb;
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';		
		$result = $wpdb->get_results("SELECT * FROM $table_appointment where patient_id=$id ORDER BY appointment_date DESC");
		
		return $result;		
	}
	//get tretment name
	public function get_treatment_name($treatment_id)
	{
		global $wpdb;
		$table_treatment = $wpdb->prefix. 'hmgt_treatment';
		
		$result = $wpdb->get_var("SELECT treatment_name FROM $table_treatment where appointment_id= ".$treatment_id);
		return $result;
	}
	//get single appointment
	public function get_single_appointment($appointment_id)
	{
		global $wpdb;
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';
		$result = $wpdb->get_row("SELECT * FROM $table_appointment where appointment_id= ".$appointment_id);
		
		return $result;
	}
	//delete appointment
	public function delete_appointment($appointment_id)
	{
		global $wpdb;
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';
		//patient mail
		$appointment_data=$this->get_single_appointment($appointment_id);
		
		$patient=get_userdata($appointment_data->patient_id);
		$patient_email=$patient->user_email;
		$patientname=$patient->display_name;;

		$doctor_id=get_userdata($appointment_data->doctor_id);
		$doctor_name=$doctor_id->display_name;
		$doctor_email=$doctor_id->user_email;
		
		$hospital_name = get_option('hmgt_hospital_name');
		$arr['{{Patient Name}}']=$patientname;			
		$arr['{{Doctor Name}}']=$doctor_name;			
		$arr['{{Appointment Time}}']=$appointment_data->appointment_time_with_a;			
		$arr['{{Appointment Date}}']=$appointment_data->appointment_date;			
		$arr['{{Hospital Name}}']=$hospital_name;
		$subject =get_option('MJ_hmgt_cancel_appointment_patient_subject');
		
		$sub_arr['{{Doctor Name}}']=$doctor_name;
		$sub_arr['{{Appointment Time}}']=$appointment_data->appointment_time_with_a;			
		$sub_arr['{{Appointment Date}}']=$appointment_data->appointment_date;
		$sub_arr['{{Hospital Name}}']=$hospital_name;
		$subject = MJ_hmgt_subject_string_replacemnet($sub_arr,$subject);
		
		$message = get_option('MJ_hmgt_cancel_appointment_patient_mail');
		$message_replacement = MJ_hmgt_string_replacemnet($arr,$message);	
		$to[]=$patient_email;
		
		//doctor mail
		$subject_doc =get_option('MJ_hmgt_cancel_appointment_doctor_subject');
		$sub_doc_arr['{{Patient Name}}']=$patientname;
		$sub_doc_arr['{{Appointment Time}}']=$appointment_data->appointment_time_with_a;			
		$sub_doc_arr['{{Appointment Date}}']=$appointment_data->appointment_date;	
		$subject_doctor = MJ_hmgt_subject_string_replacemnet($sub_doc_arr,$subject_doc);
		
		$arr_doc['{{Doctor Name}}']=$doctor_name;
		$arr_doc['{{Patient Name}}']=$patientname;			
		$arr_doc['{{Appointment Time}}']=$appointment_data->appointment_time_with_a;			
		$arr_doc['{{Appointment Date}}']=$appointment_data->appointment_date;			
		$arr_doc['{{Hospital Name}}']=$hospital_name;
		$message_doc = get_option('MJ_hmgt_cancel_appointment_doctor_mail');
		$message_replacement_doc = MJ_hmgt_string_replacemnet($arr_doc,$message_doc);	
		$doctormail[]=$doctor_email;
		$result = $wpdb->query("DELETE FROM $table_appointment where appointment_id= ".$appointment_id);
		if(isset($result))
		{
			//patient mail
			MJ_hmgt_send_mail($to,$subject,$message_replacement);
		   //doctor mail
		   MJ_hmgt_send_mail($doctormail,$subject_doctor,$message_replacement_doc);
		   MJ_hmgt_append_audit_log(''.__('Delete appointment ','hospital_mgt').'',get_current_user_id());
		   return $result;  
		}	  
	}
	//add appointment time
	public function hmgt_add_appointment_time($data)
	{	
		global $wpdb;
		$table_appointment_time = $wpdb->prefix. 'hmgt_apointment_time';       
		
		$user_id = wp_get_current_user();
		$doctor_id=$user_id->ID;
		
		$date=MJ_hmgt_get_format_for_db($data['appointment_time_startdate']);
		$result=$wpdb->get_results("SELECT id FROM $table_appointment_time where '$date' between apointment_startdate and apointment_enddate and user_id=".$doctor_id."");
		
		foreach($result as $time)
		{	
			$result_delete=$wpdb->query("delete from $table_appointment_time where id=".$time->id);
		}			
		
		$time=$data['time'];
		 
		foreach ($time as $key => $value)
		{		
			foreach ($value as $key1 => $value1)
			{				
				$appointment_time_data['user_id']=get_current_user_id();
				$appointment_time_data['apointment_startdate']=MJ_hmgt_get_format_for_db($data['appointment_time_startdate']);
				$appointment_time_data['apointment_enddate']=MJ_hmgt_get_format_for_db($data['appointment_time_enddate']);
			
				$appointment_time_data['day']=$key1;		
				$appointment_time_data['apointment_time']=$value1; 
				$appointment_time_data['created_date']=date("Y-m-d");
				$appointment_time_data['created_by']=get_current_user_id();	
				
				$result_insert=$wpdb->insert( $table_appointment_time, $appointment_time_data);		
			}	
		 }
		return $result_insert;	
	}
	//admin dashboard appointment list
	public function get_appointment_on_admin_dashboard()
	{
		global $wpdb;
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';		
		$result = $wpdb->get_results("SELECT * FROM $table_appointment ORDER BY appointment_id DESC LIMIT 3");
		return $result;		
	}
	//fronted dashboard appointment list
	public function get_appointment_on_fronted_dashboard()
	{
		global $wpdb;
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';	
		
		$page='appointment';
		$data=MJ_hmgt_page_wise_view_data_on_fronted_dashboard($page);
		$role=MJ_hmgt_get_current_user_role();	
		$current_user = wp_get_current_user();
		$id=$current_user->ID;
		
		if($data==1)
		{	
			if($role == 'laboratorist' || $role == 'pharmacist' || $role == 'accountant' || $role == 'receptionist') 
			{
				$result = $wpdb->get_results("SELECT * FROM $table_appointment where appoint_create_by=$id ORDER BY appointment_id DESC LIMIT 3");			  
			}
			elseif($role == 'doctor') 
			{
				$array=MJ_hmgt_doctor_patientid_list(); 
				$array = implode("','",$array);
				
				$result = $wpdb->get_results("SELECT * FROM $table_appointment where appoint_create_by=$id OR patient_id IN ('".$array."') ORDER BY appointment_id DESC LIMIT 3");
			}
			elseif($role == 'nurse') 
			{				
				$array=MJ_hmgt_nurse_patientid_list(); 				
				$array = implode("','",$array);
					
				$result = $wpdb->get_results("SELECT * FROM $table_appointment where appoint_create_by=$id OR patient_id IN ('".$array."') ORDER BY appointment_id DESC LIMIT 3");
			}
			elseif($role == 'patient')
			{
				$result = $wpdb->get_results("SELECT * FROM $table_appointment where patient_id=$id ORDER BY appointment_id DESC LIMIT 3");				
			}			
		}
		else
		{
			$result = $wpdb->get_results("SELECT * FROM $table_appointment ORDER BY appointment_id DESC LIMIT 3");
		}
		return $result;		
	}
}
?>