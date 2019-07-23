<?php 
class MJ_hmgt_invoice
{
	//add invoice data
	public function hmgt_add_invoice($data)
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix .'hmgt_invoice';
		$invoicedata['invoice_title']=MJ_hmgt_strip_tags_and_stripslashes($data['invice_title']);
		$invoicedata['invoice_number']=$data['invoice_number'];
		$invoicedata['patient_id']=$data['patient'];
		$invoicedata['invoice_create_date']=MJ_hmgt_get_format_for_db($data['invoice_date']);
		$invoicedata['vat_percentage']=0;
		$invoicedata['discount']=0;	
		$invoicedata['invoice_amount']=$data['invoice_amount'];
		$invoicedata['comments']=MJ_hmgt_strip_tags_and_stripslashes($data['comments']);
		$invoicedata['payment_type']='';
		$invoicedata['adjustment_amount']=$data['adjustment_amount'];
		
		$invoicedata['invoice_create_by']=get_current_user_id();		
		
		if($data['action']=='edit')
		{			
			$paid_amount=(float)$data['paid_amount'];
			
			if(empty($data['adjustment_amount']))
			{
				$total_amount=$data['invoice_amount'];
			}												
			else
			{
				$total_amount=$data['invoice_amount']-$data['adjustment_amount'];
			}
						
			if($paid_amount >= $total_amount)
			{
				$status='Paid';
			}
			elseif($paid_amount > 0)
			{
				$status='Part Paid';
			}
			else
			{
				$status= 'Unpaid';	
			}
			
			$invoicedata['status']= $status;			
			
			$invoice_dataid['invoice_id']=$data['invoice_id'];
			$result=$wpdb->update( $table_invoice, $invoicedata ,$invoice_dataid);
			
			$tbl_transation = $wpdb->prefix . "hmgt_patient_transation";
			
			if(!empty($data['transationdata']))
			{ 	
				foreach($data['transationdata'] as $key=>$value)
				{					   
					$wheredata['id']=$value;
					$datas['invoice_id']=$data['invoice_id'];
					$update = $wpdb->update( $tbl_transation, $datas, $wheredata );					
				}
			}
			
			MJ_hmgt_append_audit_log(''.__('Update invoice ','hospital_mgt').'',get_current_user_id());
			return $result;
		}
		else
		{
			global $wpdb;
			
			$invoicedata['paid_amount']=0;
			$invoicedata['status']= "Unpaid";
			
			$result=$wpdb->insert( $table_invoice,$invoicedata);
			
			$lastid = $wpdb->insert_id;	
			$tbl_transation = $wpdb->prefix . "hmgt_patient_transation";
			
			if(!empty($data['transationdata']))
			{ 	
				foreach($data['transationdata'] as $key=>$value)
				{					   
					$wheredata['id']=$value;
					$datas['invoice_id']=$lastid;
					$update = $wpdb->update( $tbl_transation, $datas, $wheredata );					
				}
			}			
			
			//generate invoice mail			
			$patient=get_userdata($data['patient']);
			$patient_email=$patient->user_email;
			$patientname=$patient->display_name;
			$hospital_name = get_option('hmgt_hospital_name');
			$subject =get_option('MJ_hmgt_generate_invoice_subject');
			$sub_arr['{{Hospital Name}}']=$hospital_name;
			$subject = MJ_hmgt_subject_string_replacemnet($sub_arr,$subject);

			$arr['{{Patient Name}}']=$patientname;			
			$arr['{{Hospital Name}}']=$hospital_name;
			$message = get_option('MJ_hmgt_generate_invoice_template');	
			$message_replacement = MJ_hmgt_string_replacemnet($arr,$message);
		
			$to[]=$patient_email;
			MJ_hmgt_send_invoice_generate_mail($to,$subject,$message_replacement,$lastid);
				
			//end generate invoice email
					
			MJ_hmgt_append_audit_log(''.__('Add new invoice ','hospital_mgt').'',get_current_user_id());
			return $result;
		}
	}
	//generate invoice number
	public function generate_invoce_number()
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'hmgt_invoice';
		
		$result = $wpdb->get_row("SELECT * FROM $table_invoice ORDER BY invoice_id DESC");
		$year = date("y");
		$month = date("m");
		$date = date("d");
		$concat = $year.$month.$date;
		if(!empty($result))
		{	$res = $result->invoice_id + 1;
			return $concat.$res;
		}
		else 
		{
			
			$res = 1;
			return $concat.$res;
		}
	}
	//get invoice data
	public function hmgt_get_invoice_data($invoice_id)
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'hmgt_invoice';
	
		$result = $wpdb->get_row("SELECT * FROM $table_invoice where invoice_id= ".$invoice_id);
		return $result;
	}
	//get all invoice data
	public function get_all_invoice_data()
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'hmgt_invoice';
		
		$result = $wpdb->get_results("SELECT * FROM $table_invoice");
		return $result;
		
	}
	//get all invoice data by patient
	public function get_all_invoice_data_by_patient($userid)
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'hmgt_invoice';
		
		$result = $wpdb->get_results("SELECT * FROM $table_invoice where patient_id=$userid");
		return $result;
		
	}
	//get all invoice data creted by
	public function get_all_invoice_data_creted_by($userid)
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'hmgt_invoice';
		
		$result = $wpdb->get_results("SELECT * FROM $table_invoice where invoice_create_by=$userid");
		return $result;
		
	}
	//get doctor created all invoice data 
	public function get_doctor_all_invoice_data_creted_by($userid)
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'hmgt_invoice';
		$array=MJ_hmgt_doctor_patientid_list(); 
		$array = implode("','",$array);
		$result = $wpdb->get_results("SELECT * FROM $table_invoice where invoice_create_by=$userid OR patient_id IN ('".$array."')");
		return $result;
		
	}
	//get nurse created all invoice data 
	public function get_nurse_all_invoice_data_creted_by($userid)
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'hmgt_invoice';
		$array=MJ_hmgt_nurse_patientid_list(); 
		
		$array = implode("','",$array);
		$result = $wpdb->get_results("SELECT * FROM $table_invoice where invoice_create_by=$userid OR patient_id IN ('".$array."')");
		return $result;
		
	}
	//delete invoice data
	public function delete_invoice($invoice_id)
	{
		global $wpdb;
		$table_invoice=$wpdb->prefix.'hmgt_invoice';
		$result = $wpdb->query("DELETE FROM $table_invoice where invoice_id= ".$invoice_id);
		MJ_hmgt_append_audit_log(''.__('Delete invoice ','hospital_mgt').'',get_current_user_id());
		return $result;
	}
	// get income record entry
	public function get_entry_records($data)
	{
			$all_income_entry=$data['income_entry'];
			 $all_income_amount=$data['income_amount'];
			
			$entry_data=array();
			$i=0;
			foreach($all_income_entry as $one_entry)
			{
				$entry_data[]= array('entry'=>MJ_hmgt_strip_tags_and_stripslashes($one_entry),
							'amount'=>$all_income_amount[$i]);
					$i++;
			}
			return json_encode($entry_data);
	}
	//---------add Income----------------
	public function add_income($data)
	{		
		$entry_value=$this->get_entry_records($data);
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
		$table_invoice=$wpdb->prefix .'hmgt_invoice';
		$invoice_date=MJ_hmgt_get_format_for_db($data['invoice_date']);
		$incomedata['invoice_type']=$data['invoice_type'];
		$incomedata['party_name']=$data['party_name'];
		$incomedata['income_create_date']=$invoice_date;		
		$incomedata['income_entry']=$entry_value;
		$incomedata['income_create_by']=get_current_user_id();
		$incomedata['payment_method']=$data['payment_method'];
		$incomedata['payment_description']=$data['payment_description'];
	
			
		if($data['action']=='edit')
		{
			$incomedata['payment_status']=$data['payment_status'];
			$incomedata['invoice_id']='';
			
			$income_dataid['income_id']=$data['income_id'];
			$result=$wpdb->update( $table_income, $incomedata ,$income_dataid);
			MJ_hmgt_append_audit_log(''.__('Update income ','hospital_mgt').'',get_current_user_id());
			return $result;
		}
		else
		{
			if(!empty($data['invoice_id']))
			{	
				$incomedata['invoice_id']=$data['invoice_id'];
				$invoice_data=$this->hmgt_get_invoice_data($data['invoice_id']);
				
				if(!empty($data['income_amount']))
				{	
					foreach($data['income_amount'] as $income_amount)
					{
						$paid_amount=(float)$income_amount;
					}	
				}	
				
				if(empty($invoice_data->discount) && empty($invoice_data->adjustment_amount))
				{
					$total_amount=$invoice_data->invoice_amount;
				}
				elseif(empty($invoice_data->discount))
				{
					$total_amount=$invoice_data->invoice_amount-$invoice_data->adjustment_amount;
				}
				elseif(empty($invoice_data->adjustment_amount))
				{
					$total_amount=$invoice_data->invoice_amount-$invoice_data->discount;
				}												
				else
				{
					$total_amount=$invoice_data->invoice_amount-$invoice_data->discount-$invoice_data->adjustment_amount;
				}
				
				$total_paid_amount=$paid_amount+$invoice_data->paid_amount;
				
				//payment status
				if($total_paid_amount >= $total_amount)
				{
					$status='Paid';
				}
				elseif($total_paid_amount > 0)
				{
					$status='Part Paid';
				}
				else
				{
					$status= 'Unpaid';	
				}
			
				$incomedata['payment_status']=$status;
			}
			else
			{
				$incomedata['invoice_id']='';
				$incomedata['payment_status']=$data['payment_status'];
			}
				
			$result=$wpdb->insert( $table_income,$incomedata);
			
			MJ_hmgt_append_audit_log(''.__('Add new income ','hospital_mgt').'',get_current_user_id());
			
			if(!empty($data['invoice_id']))
			{
				//update invoice data
				$update_invoice_data['status']=$status;
				$update_invoice_data['paid_amount']=$total_paid_amount;
			
				$invoiceid['invoice_id']=$data['invoice_id'];
				$result=$wpdb->update( $table_invoice, $update_invoice_data ,$invoiceid);
				
				$lastid=$data['invoice_id'];			
				
				$patient=get_userdata($data['party_name']);
				$patient_email=$patient->user_email;
				$patientname=$patient->display_name;
			
				//invoice payment receive  email 
			    $hospital_name = get_option('hmgt_hospital_name');
				$subject =get_option('MJ_hmgt_payment_received_invoice_subject');
				$sub_arr['{{InvoiceNo}}']=$invoice_data->invoice_number;
				$subject1 = MJ_hmgt_subject_string_replacemnet($sub_arr,$subject);
				$arr['{{Patient Name}}']=$patientname;			
				$arr['{{InvoiceNo}}']=$invoice_data->invoice_number;	
				$arr['{{Hospital Name}}']=$hospital_name;
				$message = get_option('MJ_hmgt_payment_received_invoice_template');	
				$message_replacement1 = MJ_hmgt_string_replacemnet($arr,$message);
				
				$to1[]=$patient_email;
						
				MJ_hmgt_send_invoice_payment_mail($to1,$subject1,$message_replacement1,$lastid); 				
				//end invoice payment receive mail	
			}	
			return $result;
		}
	}
	//get all income data
	public function get_all_income_data()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
		
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income'");
		return $result;
		
	}
	//get all income data by created by
	public function get_all_income_data_by_income_create_by()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
		$current_user_id=get_current_user_id();
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' AND income_create_by=$current_user_id");
		return $result;
		
	}
	//get doctor created all income data
	public function get_doctor_all_income_data_by_income_create_by()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
		$current_user_id=get_current_user_id();
		$array=MJ_hmgt_doctor_patientid_list(); 
		$array = implode("','",$array);
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' AND (income_create_by=$current_user_id OR party_name IN ('".$array."'))");
		return $result;
		
	}
	//get nurse created all income data
	public function get_nurse_all_income_data_by_income_create_by()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
		$current_user_id=get_current_user_id();
		$array=MJ_hmgt_nurse_patientid_list(); 
		
		$array = implode("','",$array);
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' AND (income_create_by=$current_user_id OR party_name IN ('".$array."'))");
		return $result;
		
	}
	//get patient all income data
	public function get_all_income_data_by_patient($patient_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
		
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' AND party_name=$patient_id");
		return $result;		
	}
	//get income data by id
	public function hmgt_get_income_data($income_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
	
		$result = $wpdb->get_row("SELECT * FROM $table_income where income_id= ".$income_id);
		return $result;
	}
	//delete income data
	public function delete_income($income_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
		$result = $wpdb->query("DELETE FROM $table_income where income_id= ".$income_id);
		MJ_hmgt_append_audit_log(''.__('Delete income ','hospital_mgt').'',get_current_user_id());
		return $result;
	}
	//get single patient income data
	public function get_onepatient_income_data($patient_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
	
		$result = $wpdb->get_results("SELECT * FROM $table_income where party_name= '".$patient_id."' order by income_create_date desc");
		
		return $result;
	}
	//-----------add Expense-----------------
	public function add_expense($data)
	{
		$entry_value=$this->get_entry_records($data);
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
		$incomedata['invoice_type']=$data['invoice_type'];
		$incomedata['party_name']=MJ_hmgt_strip_tags_and_stripslashes($data['party_name']);
		$incomedata['income_create_date']=MJ_hmgt_get_format_for_db($data['invoice_date']);;
		$incomedata['payment_status']=$data['payment_status'];
		$incomedata['income_entry']=$entry_value;
		$incomedata['income_create_by']=get_current_user_id();
		if($data['action']=='edit')
		{
			$expense_dataid['income_id']=$data['expense_id'];
			$result=$wpdb->update( $table_income, $incomedata ,$expense_dataid);
			MJ_hmgt_append_audit_log(''.__('Update expense ','hospital_mgt').'',get_current_user_id());
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_income,$incomedata);
			MJ_hmgt_append_audit_log(''.__('Add new expense ','hospital_mgt').'',get_current_user_id());
			return $result;
		}
	}
	//delete expense
	public function delete_expense($expense_id)
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
		$result = $wpdb->query("DELETE FROM $table_income where income_id= ".$expense_id);
		MJ_hmgt_append_audit_log(''.__('Delete expense ','hospital_mgt').'',get_current_user_id());
		return $result;
	}
	//get all expense data
	public function get_all_expense_data()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='expense'");
		return $result;
	}
	//get all expense data by created by
	public function get_all_expense_data_by_income_create_by()
	{
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
		$current_user_id=get_current_user_id();
		$result = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='expense' AND income_create_by=$current_user_id");
		return $result;
	}
	//get all charge category
	public function get_all_invoice_charge()
	{
		$args= array('post_type'=> 'invoice_charge','posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
		$result = get_posts( $args );
		return $result;		
	}
	// Add charge category
	public function hmgt_add_invoice_charge($data)
	{	
		global $wpdb;
						
		$category_name=MJ_hmgt_strip_tags_and_stripslashes($data['category_name']);
		$charge_amount=$data['charge_amount'];
		$charge_tax=$data['charge_tax'];
		$charge_description=MJ_hmgt_strip_tags_and_stripslashes($data['charge_description']);
				
		$charge_type_array=array("category_name"=>$category_name,"charge_amount"=>$charge_amount,"charge_tax"=>$charge_tax,"charge_description"=>$charge_description);
		
		$charge_type=json_encode($charge_type_array);
		
		$result = wp_insert_post( array(
				'post_status' => 'publish',
				'post_type' => 'invoice_charge',
				'post_title' => $charge_type ));		
		MJ_hmgt_append_audit_log(''.__('Add Invoice Charge type ','hospital_mgt').'',get_current_user_id());
		return $result;
	}
	//delete charge category
	public function delete_invoice_charge($invoice_id)
	{
		$result=wp_delete_post($invoice_id);
		MJ_hmgt_append_audit_log(''.__('Delete Invoice Charge type ','hospital_mgt').'',get_current_user_id());
		return $result;
	}
	// Add Tax
	public function hmgt_add_tax($data)
	{
		global $wpdb;
		$table_hmgt_taxes=$wpdb->prefix .'hmgt_taxes';
		$taxdata['tax_title']=MJ_hmgt_strip_tags_and_stripslashes($data['tax_title']);
		$taxdata['tax_value']=$data['tax_value'];		
		$taxdata['created_date']=date("Y-m-d");				
		
		if($data['action']=='edit')
		{	
			$taxid['tax_id']=$data['tax_id'];
			$result=$wpdb->update( $table_hmgt_taxes, $taxdata ,$taxid);					
			
			return $result;
		}
		else
		{						
			$result=$wpdb->insert( $table_hmgt_taxes,$taxdata);		
			
			return $result;		}
	}
	// get all tax data
	public function get_all_tax_data()
	{
		global $wpdb;
		$table_hmgt_taxes=$wpdb->prefix .'hmgt_taxes';
		$result = $wpdb->get_results("SELECT * FROM $table_hmgt_taxes");
		return $result;
	}
	//get single tax data
	public function hmgt_get_single_tax_data($tax_id)
	{
		global $wpdb;
		$table_hmgt_taxes=$wpdb->prefix .'hmgt_taxes';
	
		$result = $wpdb->get_row("SELECT * FROM $table_hmgt_taxes where tax_id= ".$tax_id);
		return $result;
	}
	//delete tax
	public function delete_tax($tax_id)
	{
		global $wpdb;
		$table_hmgt_taxes=$wpdb->prefix .'hmgt_taxes';
		$result = $wpdb->query("DELETE FROM $table_hmgt_taxes where tax_id= ".$tax_id);
		
		return $result;
	}
}
?>