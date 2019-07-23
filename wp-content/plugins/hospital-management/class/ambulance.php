<?php 	  
class MJ_Hmgt_ambulance
{	
	//Medicine Category
	public function hmgt_add_ambulance($data)
	{		
		global $wpdb;
		$table_ambulance = $wpdb->prefix. 'hmgt_ambulance';	
		
		//-------usersmeta table data--------------
		$ambulancedata['ambulance_id']=$data['ambulance_id'];
		$ambulancedata['registerd_no']=MJ_hmgt_strip_tags_and_stripslashes($data['registerd_no']);
		$ambulancedata['driver_name']=MJ_hmgt_strip_tags_and_stripslashes($data['driver_name']);
		$ambulancedata['driver_address']=MJ_hmgt_strip_tags_and_stripslashes($data['driver_address']);
		$ambulancedata['driver_phoneno']=$data['driver_phoneno'];
		$ambulancedata['description']=MJ_hmgt_strip_tags_and_stripslashes($data['description']);
		if(isset($data['driver_image']))
			$ambulancedata['driver_image']=MJ_hmgt_strip_tags_and_stripslashes($data['driver_image']);		
		$ambulancedata['amb_createdby']=get_current_user_id();		
			
		if($data['action']=='edit')	
		{
			$amb_id['amb_id']=$data['amb_id'];			
			$result=$wpdb->update( $table_ambulance, $ambulancedata ,$amb_id);			
			MJ_hmgt_append_audit_log(''.__('Update ambulance detail ','hospital_mgt').'',get_current_user_id());
			return $result;
		}
		else
		{
			$ambulancedata['amb_created_date']=date("Y-m-d");
			$result=$wpdb->insert( $table_ambulance, $ambulancedata );		
			MJ_hmgt_append_audit_log(''.__('Add new ambulance ','hospital_mgt').'',get_current_user_id());
			return $result;			
		}
	}
	//generate ambulance id
	public function generate_ambulance_id()
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'hmgt_ambulance';
		$result = $wpdb->get_row("SELECT * FROM $table_invoice ORDER BY amb_id DESC");
		$year = date("y");
		$month = date("m");
		$date = date("d");
		$concat = "AMB".$month.$date;
		if(!empty($result))
		{	$res = $result->amb_id + 1;
		return $concat.$res;
		}
		else
		{
			$res = 1;
			return $concat.$res;
		}
	}
	//get all ambulance
	public function get_all_ambulance()
	{
		global $wpdb;
		$table_ambulance = $wpdb->prefix. 'hmgt_ambulance';
		$result = $wpdb->get_results("SELECT * FROM $table_ambulance");
		return $result;
	}
	//get all ambulance by created by
	public function get_all_ambulance_by_createdby()
	{
		global $wpdb;
		$table_ambulance = $wpdb->prefix. 'hmgt_ambulance';
		$current_user_id=get_current_user_id();
		$result = $wpdb->get_results("SELECT * FROM $table_ambulance where amb_createdby=$current_user_id");
		return $result;
	}
	//get ambulace id
	public function get_ambulance_id($amb_id)
	{
		global $wpdb;
		$table_ambulance = $wpdb->prefix. 'hmgt_ambulance';
		$result = $wpdb->get_var("SELECT ambulance_id FROM $table_ambulance where amb_id= ".$amb_id);
		return $result;
	}
	//get single abmulance
	public function get_single_ambulance($amb_id)
	{
		global $wpdb;
		$table_ambulance = $wpdb->prefix. 'hmgt_ambulance';
		$result = $wpdb->get_row("SELECT * FROM $table_ambulance where amb_id= ".$amb_id);
		return $result;
	}
	//delete single abmulance
	public function delete_ambulance($amb_id)
	{
		global $wpdb;
		$table_ambulance = $wpdb->prefix. 'hmgt_ambulance';
		$result = $wpdb->query("DELETE FROM $table_ambulance where amb_id = ".$amb_id);
		MJ_hmgt_append_audit_log(''.__('Delete ambulance ','hospital_mgt').'',get_current_user_id());
		return $result;
	}
	//add ambulace request
	public function hmgt_add_ambulance_request($data)
	{		
		global $wpdb;
		$table_ambulance_req = $wpdb->prefix. 'hmgt_ambulance_req';
		$hmgt_patient_transation = $wpdb->prefix. 'hmgt_patient_transation';
		
		$current_user_id=get_current_user_id();
		
		//-------usersmeta table data--------------
		$request_date=MJ_hmgt_get_format_for_db($data['request_date']);
		$ambulancedata['ambulance_id']=$data['ambulance_id'];
		$ambulancedata['patient_id']=$data['patient_id'];
		$ambulancedata['address']=MJ_hmgt_strip_tags_and_stripslashes($data['address']);		
		$ambulancedata['charge']=$data['charge'];
		$ambulancedata['request_date']=$request_date;
		$ambulancedata['request_time']=$data['request_time'].":00";
		$ambulancedata['dispatch_time']=$data['dispatch_time'].":00";	
		$ambulancedata['amb_create_by']=$current_user_id;
		$ambulancedata['tax']=implode(",",$data['tax']);
		
		$tax_array=$data['tax'];
			
		$charge=$data['charge'];
		
		if(!empty($tax_array))
		{
			$total_tax=0;
			foreach($tax_array as $tax_id)
			{
				$tax_percentage=MJ_hmgt_tax_percentage_by_tax_id($tax_id);
				$tax_amount=$charge * $tax_percentage / 100;
				
				$total_tax=$total_tax + $tax_amount;
				
			}
			
			$total_charge=$charge+$total_tax;
		}
		else
		{
			$total_tax=0;
			$total_charge=$charge;
		}	
		$ambulancedata['total_tax']=$total_tax;
		$ambulancedata['total_charge']=$total_charge;

       $request_time=$data['request_time'];
	   $request_time_new = strtotime($request_time);
	   $openingHour=date('H', $request_time_new);
	   $openingMinutes=date('i', $request_time_new);
	   $dispatch_time=$data['dispatch_time'];
	   $dispatch_time_new = strtotime($dispatch_time);
	   $closingHour=date('H', $dispatch_time_new);
	   $closingMinutes=date('i', $dispatch_time_new);
	   
		//invoice patient transaction entry
		$feesdata['patient_id'] = $data['patient_id'];
		$feesdata['type_id'] = $data['ambulance_id'];
		$feesdata['type_value'] = $charge;
		$feesdata['type_tax'] = $total_tax;
		$feesdata['type_total_value'] = $total_charge;
		$feesdata['status'] ="Unpaid";
		$feesdata['type'] ="Ambulance Charges";
		$feesdata['date'] =date("Y-m-d");
		
		 if($closingHour < $openingHour)
		{
	      echo '<script type="text/javascript">alert("'.__('Dispatch Time should be greater than Request Time','hospital_mgt').'");</script>';

        } 
		elseif($closingHour ==  $openingHour && $openingMinutes > $closingMinutes )
		 {
			 echo '<script type="text/javascript">alert("'.__('Dispatch Time should be greater than Request Time','hospital_mgt').'");</script>';
		 }
		else
	    {
			if($data['action']=='edit')
			{
				$amb_id['amb_req_id']=$data['amb_req_id'];
				$result=$wpdb->update( $table_ambulance_req, $ambulancedata ,$amb_id);
				MJ_hmgt_append_audit_log(''.__('Update ambulance request','hospital_mgt').'',get_current_user_id());
				
				//delete patient transaction
				$delete_ambulance_charge = $wpdb->query("DELETE FROM $hmgt_patient_transation where type='Ambulance Charges' AND refer_id= ".$data['amb_req_id']);
				
				//insert patient transaction
				$feesdata['refer_id'] = $data['amb_req_id'];		
				$wpdb->insert($hmgt_patient_transation,$feesdata);
				
				return $result;
			}
			else
			{
				$ambulancedata['amb_req_create_date']=date("Y-m-d");				
				
				$result=$wpdb->insert( $table_ambulance_req, $ambulancedata );
				$pre_id = $wpdb->insert_id;
				MJ_hmgt_append_audit_log(''.__('Add new ambulance request','hospital_mgt').'',get_current_user_id());
				
				//insert patient transaction
				$feesdata['refer_id'] = $pre_id;
				$invoice = $wpdb->insert($hmgt_patient_transation,$feesdata);
				
				return $result;
			}	
		}
	}
	//get all ambulace request
	public function get_all_ambulance_request()
	{
		global $wpdb;
		$table_ambulance_req = $wpdb->prefix. 'hmgt_ambulance_req';
	
		$result = $wpdb->get_results("SELECT * FROM $table_ambulance_req");
		return $result;	
	}
	//get all ambulace request by created by
	public function get_all_ambulance_request_by_amb_create_by()
	{
		global $wpdb;
		$table_ambulance_req = $wpdb->prefix. 'hmgt_ambulance_req';
		$current_user_id=get_current_user_id();
		$result = $wpdb->get_results("SELECT * FROM $table_ambulance_req where amb_create_by=$current_user_id");
		return $result;	
	}
	//get doctor created all ambulace request
	public function get_doctor_all_ambulance_request_by_amb_create_by()
	{
		global $wpdb;
		$table_ambulance_req = $wpdb->prefix. 'hmgt_ambulance_req';
		$current_user_id=get_current_user_id();
		$array=MJ_hmgt_doctor_patientid_list(); 
		$array = implode("','",$array);
		$result = $wpdb->get_results("SELECT * FROM $table_ambulance_req where amb_create_by=$current_user_id OR patient_id IN ('".$array."')");
		return $result;	
	}
	//get nurse created all ambulace request
	public function get_nurse_all_ambulance_request_by_amb_create_by()
	{
		global $wpdb;
		$table_ambulance_req = $wpdb->prefix. 'hmgt_ambulance_req';
		$current_user_id=get_current_user_id();
		$array=MJ_hmgt_nurse_patientid_list(); 
		$array = implode("','",$array);
		$result = $wpdb->get_results("SELECT * FROM $table_ambulance_req where amb_create_by=$current_user_id OR patient_id IN ('".$array."')");
		return $result;	
	}
	// get all abmulance request by patient
	public function get_all_ambulance_request_by_patient($patient_id)
	{
		global $wpdb;
		$table_ambulance_req = $wpdb->prefix. 'hmgt_ambulance_req';
	
		$result = $wpdb->get_results("SELECT * FROM $table_ambulance_req where patient_id=$patient_id");
		return $result;	
	}
	//get single ambulance request
	public function get_single_ambulance_req($amb_req_id)
	{
		global $wpdb;
		$table_ambulance_req = $wpdb->prefix. 'hmgt_ambulance_req';
		$result = $wpdb->get_row("SELECT * FROM $table_ambulance_req where amb_req_id= ".$amb_req_id);
		return $result;
	}
	//delete ambulace request
	public function delete_ambulance_req($amb_req_id)
	{
		global $wpdb;
		$table_ambulance_req = $wpdb->prefix. 'hmgt_ambulance_req';
		$result = $wpdb->query("DELETE FROM $table_ambulance_req where amb_req_id = ".$amb_req_id);
		MJ_hmgt_append_audit_log(''.__('Delete abmulance request ','hospital_mgt').'',get_current_user_id());
		return $result;
	}
}
?>