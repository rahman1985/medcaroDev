<?php 
// GET ALL TREATMETN  LIST *//
function MJ_hmgt_get_all_request($start , $limit,$orderby='treat_create_Date',$order='desc')
{
	global $wpdb;
	$table_treatment = $wpdb->prefix. 'hmgt_treatment';
	$rrequest= $wpdb->get_results("SELECT * from ".$table_treatment." order by ".$orderby." ".$order." limit $start,$limit");
	return $rrequest;
}
// GET FRONTEND MENU TITLE IN TRANSLATABLE FORMATE //
function MJ_hmgt_change_menutitle($key)
{	
	$hmgt_menu_titlearray=array('doctor'=>__('Doctor','hospital_mgt'),'instrument'=>__('Instrument','hospital_mgt'),'patient'=>__('Inpatient','hospital_mgt'),'outpatient'=>__('Outpatient','hospital_mgt'),'nurse'=>__('Nurse','hospital_mgt'),'supportstaff'=>__('Support Staff','hospital_mgt'),'pharmacist'=>__('Pharmacist','hospital_mgt'),'laboratorystaff'=>__('Laboratory Staff','hospital_mgt'),'accountant'=>__('Accountant','hospital_mgt'),'medicine'=>__('Medicine','hospital_mgt'),'treatment'=>__('Treatment','hospital_mgt'),'prescription'=>__('Prescription','hospital_mgt'),'bedallotment'=>__('Assign Bed-Nurse','hospital_mgt'),'operation'=>__('Operation List','hospital_mgt'),'diagnosis'=>__('Diagnosis','hospital_mgt'),'bloodbank'=>__('Blood Bank','hospital_mgt'),'appointment'=>__('Appointment','hospital_mgt'),'invoice'=>__('Invoice','hospital_mgt'),'event'=>__('Event','hospital_mgt'),'message'=>__('Message','hospital_mgt'),'ambulance'=>__('Ambulance','hospital_mgt'),'report'=>__('Report','hospital_mgt'),'account'=>__('Account','hospital_mgt'));
	
	return $hmgt_menu_titlearray[$key];
}
// GET OUTPATIENT LIST //
function MJ_hmgt_get_outpatient_list_by_doctor($user_id)
{
		global $wpdb;
		$table_inpatient_guardian = $wpdb->prefix."hmgt_inpatient_guardian";
		$table_users = $wpdb->prefix."users";
		$table_usermeta = $wpdb->prefix."usermeta";
		$sql="SELECT u.* FROM $table_inpatient_guardian as gr,$table_users as u, $table_usermeta as um WHERE gr.doctor_id = $user_id AND gr.patient_id = u.id AND um.user_id= u.id AND um.meta_key= 'patient_type' AND um.meta_value= 'outpatient'";
	
		$patient=$wpdb->get_results($sql); 
		return $patient;
}
// UPDATE MESSAGE READ STATUS //
function MJ_hmgt_change_read_status($id){
	global $wpdb;
	$table_name = $wpdb->prefix . "hmgt_message";
	$data['msg_status']=1;
	$whereid['message_id']=$id;
	return $retrieve_subject = $wpdb->update($table_name,$data,$whereid);
}
// TREATMENT PAGINATION //
function pagination($totalposts,$p,$lpm1,$prev,$next){
    $adjacents = 3;
	$page_order = "";
	if(isset($_GET['orderby']))
	{
		$page_order='&orderby='.$_GET['orderby'].'&order='.$_GET['order'];	
	}
    if($totalposts > 1)
    {
        $pagination .= "<center><div>";
        //previous button
        if ($p > 1)
        $pagination.= "<a href=\"?page=hmgt_treatment&pg=$prev$page_order\"><< Previous</a> ";
        else
        $pagination.= "<span class=\"disabled\"><< Previous</span> ";
        if ($totalposts < 7 + ($adjacents * 2)){
            for ($counter = 1; $counter <= $totalposts; $counter++){
                if ($counter == $p)
                $pagination.= "<span class=\"current\">$counter</span>";
                else
                $pagination.= " <a href=\"?page=hmgt_treatment&pg=$counter$page_order\">$counter</a> ";}
        }elseif($totalposts > 5 + ($adjacents * 2)){
            if($p < 1 + ($adjacents * 2)){
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
                    if ($counter == $p)
                    $pagination.= " <span class=\"current\">$counter</span> ";
                    else
                    $pagination.= " <a href=\"?page=hmgt_treatment&pg=$counter$page_order\">$counter</a> ";
                }
                $pagination.= " ... ";
                $pagination.= " <a href=\"?page=hmgt_treatment&pg=$lpm1$page_order\">$lpm1</a> ";
                $pagination.= " <a href=\"?page=hmgt_treatment&pg=$totalposts$page_order\">$totalposts</a> ";
            }
            //in middle; hide some front and some back
            elseif($totalposts - ($adjacents * 2) > $p && $p > ($adjacents * 2)){
                $pagination.= " <a href=\"?page=hmgt_treatment&pg=1$page_order\">1</a> ";
                $pagination.= " <a href=\"?page=hmgt_treatment&pg=2$page_order\">2</a> ";
                $pagination.= " ... ";
                for ($counter = $p - $adjacents; $counter <= $p + $adjacents; $counter++){
                    if ($counter == $p)
                    $pagination.= " <span class=\"current\">$counter</span> ";
                    else
                    $pagination.= " <a href=\"?page=hmgt_treatment&pg=$counter$page_order\">$counter</a> ";
                }
                $pagination.= " ... ";
                $pagination.= " <a href=\"?page=hmgt_treatment&pg=$lpm1$page_order\">$lpm1</a> ";
                $pagination.= " <a href=\"?page=hmgt_treatment&pg=$totalposts$page_order\">$totalposts</a> ";
            }else{
                $pagination.= " <a href=\"?page=hmgt_treatment&pg=1$page_order\">1</a> ";
                $pagination.= " <a href=\"?page=hmgt_treatment&pg=2$page_order\">2</a> ";
                $pagination.= " ... ";
                for ($counter = $totalposts - (2 + ($adjacents * 2)); $counter <= $totalposts; $counter++){
                    if ($counter == $p)
                    $pagination.= " <span class=\"current\">$counter</span> ";
                    else
                    $pagination.= " <a href=\"?page=hmgt_treatment&pg=$counter$page_order\">$counter</a> ";
                }
            }
        }
        if ($p < $counter - 1)
        $pagination.= " <a href=\"?page=hmgt_treatment&pg=$next$page_order\">Next >></a>";
        else
        $pagination.= " <span class=\"disabled\">Next >></span>";
        $pagination.= "</center>\n";
    }
    return $pagination;
}
// GET TREATMETN CHARGE *//
function MJ_hmgt_get_treatment_charge($treatment_id){
	global $wpdb;
	$table_treatment = $wpdb->prefix. 'hmgt_treatment';
	$result = $wpdb->get_row("SELECT * FROM $table_treatment where treatment_id= ".$treatment_id);
	
	$treatment_price=$result->treatment_price;
	
	return $treatment_price;
}
// GET DOCTOR CHARGE *//
function MJ_hmgt_get_doctor_charge($doc_id)
{
	return get_user_meta($doc_id,'visiting_fees',true);
}
// RUN REMOTE FILE //
function MJ_hmgt_get_remote_file($url, $timeout = 30)
{
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	return ($file_contents) ? $file_contents : FALSE;
}
// LOAD ALL USERLIST IN MESSAGE MODULE //
function MJ_hmgt_get_all_user_in_message()
{
      $user_id=get_current_user_id();
	  $user = get_userdata($user_id);
	  $role=$user->roles;
	  $reciverrole=$role[0];
	  if($reciverrole == 'doctor')
	  {
		$patient=get_users(array('role'=>'patient'));
		 $all_user = array(
		''.__('patient','hospital_mgt').''=>$patient
		);
	   }
	   elseif($reciverrole == 'patient')
	   {
		  $doctor=get_users(array('role'=>'doctor')); 
		  $all_user = array(''.__('doctor','hospital_mgt').''=>$doctor
			);
	   }
	    else
	    {
			$doctor=get_users(array('role'=>'doctor'));
			$patient=get_users(array('role'=>'patient'));
			$nurse=get_users(array('role'=>'nurse'));			
			$receptionist=get_users(array('role'=>'receptionist'));
			$pharmacist=get_users(array('role'=>'pharmacist'));
			$laboratorist=get_users(array('role'=>'laboratorist'));
			$accountant=get_users(array('role'=>'accountant'));
			
			$all_user = array(''.__('doctor','hospital_mgt').''=>$doctor,
					''.__('patient','hospital_mgt').''=>$patient,
					''.__('nurse','hospital_mgt').''=>$nurse,
					''.__('all support staff','hospital_mgt').''=>$receptionist,
					''.__('pharmacist','hospital_mgt').''=>$pharmacist,
					''.__('laboratorist','hospital_mgt').''=>$laboratorist,
					''.__('accountant','hospital_mgt').''=>$accountant
					);
			  }
	$return_array = array();

	foreach($all_user as $key => $value)
	{ 
		if(!empty($value))
		{
		 echo '<optgroup label="'.$key.'" style = "text-transform: capitalize;">';
		foreach($value as $user)
		{
			
			echo '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
		}
		}
	}	
}
// LOAD DOCUMENTS //
function MJ_hmgt_load_documets($file,$type,$nm) 
{
	$imagepath =$file;     
	$parts = pathinfo($_FILES[$type]['name']);
	$inventoryimagename = time()."-".$nm."-"."in".".".$parts['extension'];
	
	$document_dir = WP_CONTENT_DIR ;
	 $document_dir .= '/uploads/hospital_assets/';
		$document_path = $document_dir;
	if($imagepath != "")
	{	
		if(file_exists(WP_CONTENT_DIR.$document_dir.$imagepath['name']))
		unlink(WP_CONTENT_DIR.$document_dir.$imagepath['name']);
	}
	if (!file_exists($document_path)) 
	{
		 
		mkdir($document_path, 0777, true);
	} 	
	    if (move_uploaded_file($_FILES[$type]['tmp_name'], $document_path.$inventoryimagename))	
		{
			$imagepath= $inventoryimagename;	
		}
	return $imagepath;
}
// LOAD Multiple DOCUMENTS //
function MJ_hmgt_load_multiple_documets($file,$type,$nm) 
{
	$imagepath =$file;     
	
	$parts = pathinfo($type['name']);

	$inventoryimagename = time()."-".rand().".".$parts['extension'];

	$document_dir = WP_CONTENT_DIR ;
	 $document_dir .= '/uploads/hospital_assets/';
		$document_path = $document_dir;
	if($imagepath != "")
	{	
		if(file_exists(WP_CONTENT_DIR.$document_dir.$imagepath['name']))
		unlink(WP_CONTENT_DIR.$document_dir.$imagepath['name']);
	}
	 if (!file_exists($document_path)) {
		 
		mkdir($document_path, 0777, true);
	} 	
	
		if (move_uploaded_file($type['tmp_name'], $document_path.$inventoryimagename))
		{
			$imagepath= $inventoryimagename;	
		}
	return $imagepath;
}
// GET COUNTRY PHONE CODE BY COUNTRY NAME
function MJ_hmgt_get_countery_phonecode($country_name)
{
	//$xml=simplexml_load_file(plugins_url( 'countrylist.xml', __FILE__ )) or die("Error: Cannot create object");
	$url = plugins_url( 'countrylist.xml', __FILE__ );
	$xml =simplexml_load_string(MJ_hmgt_get_remote_file($url));
	foreach($xml as $country)
	{
		if($country_name == $country->name)
			return $country->phoneCode;

	}
}
add_action( 'wp_login_failed', 'MJ_hmgt_login_failed' ); // hook failed login //
// HANDLE LOGIN FAILD ACTIONS//
function MJ_hmgt_login_failed( $user )
{
	// check what page the login attempt is coming from
	$referrer = $_SERVER['HTTP_REFERER'];
	
	 $curr_args = array(
				'page_id' => get_option('hmgt_login_page'),
				'login' => 'failed'
				);
				print_r($curr_args);
				$referrer_faild = add_query_arg( $curr_args, get_permalink( get_option('hmgt_login_page') ) );
	// check that were not on the default login page
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null ) {
		// make sure we don't already have a failed login attempt
		if ( !strstr($referrer, 'login=failed' )) {
			// Redirect to the login page and append a query string of login failed
			wp_redirect( $referrer_faild);
		} else {
			wp_redirect( $referrer );
		}

		exit;
	}
}
// CREATE LOGIN PAGE FOR FRONTEND USERS //
function MJ_hmgt_login_link()
{
	
	$args = array( 'redirect' => site_url() );
	
	if(isset($_GET['login']) && $_GET['login'] == 'failed')
	{
		?>
		<div id="login-error" style="background-color: #FFEBE8;border:1px solid #C00;padding:5px;">
		  <p><?php _e('Login failed: You have entered an incorrect Username or password, please try again.','hospital_mgt'); ?></p>
		</div>
		<?php
	}
	 $args = array(
			'echo' => true,
			'redirect' => site_url( $_SERVER['REQUEST_URI'] ),
			'form_id' => 'loginform',
			'label_username' => __( 'Username' , 'hospital_mgt'),
			'label_password' => __( 'Password', 'hospital_mgt' ),
			'label_remember' => __( 'Remember Me' , 'hospital_mgt'),
			'label_log_in' => __( 'Log In' , 'hospital_mgt'),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'remember' => true,
			'value_username' => NULL,
	        'value_remember' => false ); 
	 $args = array('redirect' => site_url('/?dashboard=user') );
	 
	 if ( is_user_logged_in() )
	 {?>
<a href="<?php echo home_url('/')."?dashboard=user"; ?>">
<i class="fa fa-sign-out m-r-xs"></i>
<?php _e('Dashboard','hospital_mgt');?>
</a>
<?php 
	 }
	 else 
	 {
		wp_login_form( $args );
		echo '<a href="'.wp_lostpassword_url().'" title="Lost Password">Forgot your password?</a>';
	 }
	 
}
// GET RECORDS LIST BY PASSING TABLE NAME //
function MJ_hmgt_tables_rows($table_name)
{
	global $wpdb;
	$table_name = $wpdb->prefix . $table_name;
	$count_query = "select count(*) from $table_name";
	$num = $wpdb->get_var($count_query);

	echo  $num;
}
// GET BLOOD GROUP ARRAY //
function MJ_hmgt_blood_group()
{
	return $blood_group=array( __( 'O+' ,'hospital_mgt'),__( 'O-' ,'hospital_mgt'),__( 'A+' ,'hospital_mgt'),__( 'B+' ,'hospital_mgt'),__( 'A-' ,'hospital_mgt'),__( 'B-' ,'hospital_mgt'),__( 'AB+' ,'hospital_mgt'),__( 'AB-' ,'hospital_mgt') );
	
}
// GET USER PROFILE IMAGE BY PASSING ROLE
function MJ_hmgt_get_default_userprofile($role)
{
	$profile_pict=array('doctor'=>get_option('hmgt_doctor_thumb'),
			'nurse'=>get_option('hmgt_nurse_thumb'),
			'pharmacist'=>get_option('hmgt_pharmacist_thumb'),
			'laboratorist'=>get_option('hmgt_laboratorist_thumb'),
			'accountant'=>get_option('hmgt_accountant_thumb'),
			'patient'=>get_option('hmgt_patient_thumb'),
			'receptionist'=>get_option('hmgt_support_thumb')
	);
	return $profile_pict[$role];

}
// GET ROLE LIST IN MESSAGE MODULE
function MJ_hmgt_get_role_name_in_message($role)
{
	$profile_pict=array('doctor'=>__( 'Doctor' ,'hospital_mgt'),
			'nurse'=>__( 'Nurse' ,'hospital_mgt'),
			'pharmacist'=> __( 'Pharmacist' ,'hospital_mgt'),
			'laboratorist'=> __( 'Laboratory Staff' ,'hospital_mgt'),
			'accountant'=>__( 'Accountant' ,'hospital_mgt'),
			'patient'=> __( 'Patient' ,'hospital_mgt'),
			'receptionist'=> __( 'Support Staff' ,'hospital_mgt'),
			'administrator'=> __( 'Admin' ,'hospital_mgt'),
	);
	
	return $profile_pict[$role];
}
// GET ROLE LIST IN Event MODULE //
function MJ_hmgt_get_role_name_in_event($notice_for_array)
{
	$notice_for_data_array=array();
	if(!empty($notice_for_array))
	{	
		foreach($notice_for_array as $data)
		{	
			if($data == 'patient')
			{
				$notice_for_data_array[]='Patient';
			}
			elseif($data == 'doctor')
			{
				$notice_for_data_array[]='Doctor';
			}
			elseif($data == 'nurse')
			{
				$notice_for_data_array[]='Nurse';	
			}
			elseif($data == 'receptionist')
			{
				$notice_for_data_array[]='Support Staff';
			}
			elseif($data == 'pharmacist')
			{
				$notice_for_data_array[]='Pharmacist';		
			}
			elseif($data == 'laboratorist')
			{
				$notice_for_data_array[]='Laboratory Staff';
			}
			elseif($data == 'accountant')
			{
				$notice_for_data_array[]='Accountant';
			}
			elseif($data == 'all')
			{
				$notice_for_data_array[]='ALL';
			}
		}
	}	
	return implode(",",$notice_for_data_array);
}
// GET ROLE LIST IN MESSAGE MODULE *//
function MJ_hmgt_report_tables_rows($table_name,$type)
{
	global $wpdb;
	$table_name = $wpdb->prefix . $table_name;
	$count_query = "select count(*) from $table_name where report_type = '$type'";
	$num = $wpdb->get_var($count_query);
	echo  $num;
}
// FRONTEND SIDE MENU LIST *//
function MJ_hmgt_menu()
{
	$obj_hospital = new Hospital_Management(get_current_user_id());
	 $role = $obj_hospital->role;
	 $patient_type='';
	 if($role=='patient')
		$patient_type=get_user_meta(get_current_user_id(),'patient_type',true);
	
	$user_menu = array();
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/doctor.png' ),'menu_title'=>__( 'Doctor', 'hospital_mgt' ),'patient'=>1,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>0,'laboratorist'=>1,'page_link'=>'doctor');
	
	if($patient_type=='outpatient'){
		$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Patient.png' ),'menu_title'=>__( 'Inpatient', 'hospital_mgt' ),'patient'=>0,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'patient');
		$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/outpatient.png' ),'menu_title'=>__( 'Outpatient', 'hospital_mgt' ),'patient'=>1,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'outpatient');
	}
	else
	{
		$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Patient.png' ),'menu_title'=>__( 'Inpatient', 'hospital_mgt' ),'patient'=>1,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'patient');
		$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/outpatient.png' ),'menu_title'=>__( 'Outpatient', 'hospital_mgt' ),'patient'=>0,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'outpatient');
	}	
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Nurse.png' ),'menu_title'=>__( 'Nurse', 'hospital_mgt' ),'patient'=>0,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'nurse');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/support.png' ),'menu_title'=>__( 'Support Staff', 'hospital_mgt' ),'patient'=>0,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'supportstaff');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Pharmacist.png' ),'menu_title'=>__( 'Pharmacist', 'hospital_mgt' ),'patient'=>0,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'pharmacist');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Laboratorist.png' ),'menu_title'=>__( 'Laboratory Staff', 'hospital_mgt' ),'patient'=>0,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'laboratorystaff');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Accountant.png' ),'menu_title'=>__( 'Accountant', 'hospital_mgt' ),'patient'=>0,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'accountant');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Medicine.png' ),'menu_title'=>__( 'Medicine', 'hospital_mgt' ),'doctor' => 0,'nurse' => 0,'receptionist'=>0,'accountant' =>0,'pharmacist'=>1,'laboratorist'=>0,'page_link'=>'medicine');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Treatment.png' ),'menu_title'=>__( 'Treatment', 'hospital_mgt' ),'doctor' => 1,'nurse' => 0,'receptionist'=>0,'accountant' =>0,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'treatment');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Prescription.png' ),'menu_title'=>__( 'Prescription', 'hospital_mgt' ),'doctor' => 1,'nurse' => 0,'receptionist'=>0,'accountant' =>0,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'prescription');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Assign--Bed-nurse.png' ),'menu_title'=>__( 'Assign Bed-Nurse', 'hospital_mgt' ),'doctor' => 1,'nurse' => 1,'receptionist'=>0,'accountant' =>0,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'bedallotment');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Operation-List.png' ),'menu_title'=>__( 'Operation List', 'hospital_mgt' ),'doctor' => 1,'nurse' => 0,'receptionist'=>0,'accountant' =>0,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'operation');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Diagnosis-Report.png' ),'menu_title'=>__( 'Diagnosis', 'hospital_mgt' ),'patient'=>1,'doctor' => 1,'nurse' => 0,'accountant' =>0,'receptionist'=>0,'pharmacist'=>0,'laboratorist'=>1,'page_link'=>'diagnosis');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Blood-Bank.png' ),'menu_title'=>__( 'Blood Bank', 'hospital_mgt' ),'doctor' => 1,'nurse' => 1,'receptionist'=>0,'accountant' =>0,'pharmacist'=>0,'laboratorist'=>1,'page_link'=>'bloodbank');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Appointment.png' ),'menu_title'=>__( 'Appointment', 'hospital_mgt' ),'patient'=>1,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>0,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'appointment');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/payment.png' ),'menu_title'=>__( 'Invoice', 'hospital_mgt' ),'doctor' => 0,'nurse' => 0,'receptionist'=>0,'accountant' =>1,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'invoice');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/notice.png' ),'menu_title'=>__( 'Event', 'hospital_mgt' ),'patient'=>1,'doctor' => 1,'nurse' => 0,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'event');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/message.png' ),'menu_title'=>__( 'Message', 'hospital_mgt' ),'patient'=>1,'doctor' => 1,'nurse' => 1,'accountant' =>1,'receptionist'=>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'message');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Ambulance.png' ),'menu_title'=>__( 'Ambulance', 'hospital_mgt' ),'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>0,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'ambulance');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Instrument.png' ),'menu_title'=>__( 'Instrument Mgt', 'hospital_mgt' ),'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>0,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'instrument');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Report.png' ),'menu_title'=>__( 'Report', 'hospital_mgt' ),'patient'=>0,'doctor' => 1,'nurse' => 0,'receptionist'=>0,'accountant' =>0,'pharmacist'=>0,'laboratorist'=>0,'page_link'=>'report');
	$user_menu[] = array('menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/account.png' ),'menu_title'=>__( 'Account', 'hospital_mgt' ),'patient'=>1,'doctor' => 1,'nurse' => 1,'receptionist'=>1,'accountant' =>1,'pharmacist'=>1,'laboratorist'=>1,'page_link'=>'account');
	return  $user_menu;
}

// Add guardian Record //
function MJ_hmgt_add_guardian($records,$record_id)
{	
	global $wpdb;
	 $table_name = $wpdb->prefix .'hmgt_inpatient_guardian';
	if(!empty($record_id))
	{
		return $result=$wpdb->update($table_name,$records,array('inpatient_id'=>$record_id));
	}
	else
	{
		$wpdb->insert( $table_name, $records);
		return $wpdb->insert_id;
	}
}
// GET PATIENT STATUS //
function MJ_hmgt_get_patient_status($patient_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_inpatient_guardian';
	$pstatus =$wpdb->get_var("SELECT  patient_status FROM $table_name WHERE  	patient_id=".$patient_id);
	return $pstatus;
}
// DELETE GUARDIAN data //
function MJ_hmgt_delete_guardian($record_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_inpatient_guardian';
	return $result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE patient_id= %d",$record_id));
	
}
// UPDATE GUARDIAN DATA //
function MJ_hmgt_update_guardian($records,$record_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_inpatient_guardian';
	
	return $result=$wpdb->update($table_name,$records,array('patient_id'=>$record_id));
}
// GET GARDIAN BY PATIENT ID //
function MJ_hmgt_get_guardianby_patient($record_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_inpatient_guardian';
	
	$guardian =$wpdb->get_row("SELECT *FROM $table_name WHERE  	patient_id=".$record_id, ARRAY_A);
	
	return $guardian;
}
// PATIENT ADMIT REASONS ARRAY
function MJ_hmgt_admit_reason()
{
	return $reason=array(__('Admitted','hospital_mgt'),
				  		__('Under Treatment','hospital_mgt'),
				 	 	__('Operated','hospital_mgt'),
				  		__('Recovery','hospital_mgt'),
				  		__('Cured','hospital_mgt'),
						__('Discharged','hospital_mgt'),
						__('Death','hospital_mgt')
					);				  
}
// OPERATION STATUS ARRAY *//
function MJ_hmgt_operation_status()
{
	return $status=array(__('Inprogress','hospital_mgt'),
				  		__('Completed','hospital_mgt'),
				 	 	__('Scheduled','hospital_mgt')
					);				  
}
// GET USERS BY ROLES //
function MJ_hmgt_getuser_by_user_role($role)
{
	$user_list = get_users(array('role' => $role));
	$user_return = array();
	foreach($user_list as $users)
	{
		$first_name = get_user_meta($users->ID,'first_name',true);
		$last_name = get_user_meta($users->ID,'last_name',true);
		$user_return[] =array('id'=>$users->ID,'first_name'=>$first_name,'last_name' =>$last_name) ;
	}
	return $user_return;
}
// GET DISPLAY NAME BY USER ID //
function MJ_hmgt_get_display_name($user_id)
{
	if (!$user = get_userdata($user_id))
		return false;
	return $user->data->display_name;
}
// APPEND AUDIT LOG // 
function MJ_hmgt_append_audit_log($audit_action,$user_id)
{
	$current = file_get_contents(HMS_LOG_file);
	
	$current .= "\n".$audit_action." ".__('at','hospital_mgt')." ".MJ_hmgt_hmgtConvertTime(date("Y-m-d H:i:s"))." ".__('by','hospital_mgt')." ".MJ_hmgt_get_display_name($user_id);
	// Write the contents back to the file
	file_put_contents(HMS_LOG_file, $current);
}
// GET EMAIL USING USER ID //
function MJ_hmgt_get_emailid_byuser_id($id)
{
	if (!$user = get_userdata($id))
		return false;
	return $user->data->user_email;
}

// GET PATIENT DETAILS //
function MJ_hmgt_get_user_detail_byid($patient_id)
{
	$user_return = array();
	$first_name = get_user_meta($patient_id,'first_name',true);
	$last_name = get_user_meta($patient_id,'last_name',true);
	$patient_id = get_user_meta($patient_id,'patient_id',true);
	$user_return =array('id'=>$patient_id,'first_name'=>$first_name,'last_name' =>$last_name,'patient_id'=>$patient_id) ;
	return $user_return;
}
// GET PATIENT LIST WITH PATIENT ID //
function MJ_hmgt_patientid_list()
{
	$user_list = get_users(array('role' => 'patient'));
	$user_return = array();
	foreach($user_list as $users)
	{
		$first_name = get_user_meta($users->ID,'first_name',true);
		$last_name = get_user_meta($users->ID,'last_name',true);
		$patient_id = get_user_meta($users->ID,'patient_id',true);
		$user_return[] =array('id'=>$users->ID,'first_name'=>$first_name,'last_name' =>$last_name,'patient_id'=>$patient_id) ;
	}
	return $user_return;
}
// GET INPATIENT LIST WITH PATIENT ID //
function MJ_hmgt_inpatient_list()
{
	$user_list = get_users(array('role' => 'patient','meta_key'=>'patient_type','meta_value'=>'inpatient'));
	$user_return = array();
	foreach($user_list as $users)
	{
		$first_name = get_user_meta($users->ID,'first_name',true);
		$last_name = get_user_meta($users->ID,'last_name',true);
		$patient_id = get_user_meta($users->ID,'patient_id',true);
		$user_return[] =array('id'=>$users->ID,'first_name'=>$first_name,'last_name' =>$last_name,'patient_id'=>$patient_id) ;
	}
	return $user_return;
}
// GET INPATIETN STATUS //
function MJ_get_inpatient_status($patient_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_inpatient_guardian';
	
	$patient_status =$wpdb->get_row("SELECT *FROM $table_name WHERE  patient_id=".$patient_id);
	
	return $patient_status;
}
// GET GUARDIAN NAME //
function MJ_hmgt_get_guardian_name($user_id)
{
	global $wpdb;
	$table_inpatient_guardian = $wpdb->prefix."hmgt_inpatient_guardian";
	$sql="SELECT * FROM $table_inpatient_guardian  WHERE patient_id = $user_id";
	$guardian = $wpdb->get_row($sql);
	return $guardian;
}
// GET LAST PATIENT ID BY ROLE //
function MJ_hmgt_get_lastpatient_id($role)
{
	global $wpdb;
	$this_role = "'[[:<:]]".$role."[[:>:]]'";
	$table_name = $wpdb->prefix .'usermeta';
	$metakey=$wpdb->prefix .'capabilities';
	$userid=$wpdb->get_row("SELECT MAX(user_id)as uid FROM $table_name where meta_key = '$metakey' AND meta_value RLIKE $this_role");
	return get_user_meta($userid->uid,'patient_id',true);
	
}
// DISPLAY PATIENT REPORT //
function MJ_hmgt_display_patient_reports($patientid)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_priscription';
	
	$patientdata=$wpdb->get_results("SELECT * FROM $table_name where patient_id='$patientid'");
	
	return $patientdata;
}
// GET MONTHS LIST IN ARRAY //
function MJ_hmgt_month_list()
{
	$month =array('1'=>__("January",'hospital_mgt'),
			'2'=>__("February",'hospital_mgt'),
			'3'=>__("March",'hospital_mgt'),
			'4'=>__("April",'hospital_mgt'),
			'5'=>__("May",'hospital_mgt'),
			'6'=>__("June",'hospital_mgt'),
			'7'=>__("July",'hospital_mgt'),
			'8'=>__("August",'hospital_mgt'),
			'9'=>__("September",'hospital_mgt'),
			'10'=>__("Octomber",'hospital_mgt'),
			'11'=>__("November",'hospital_mgt'),
			'12'=>__("December",'hospital_mgt'));
	return $month;
}

//AJAX FUNCTION CALLS
add_action( 'wp_ajax_MJ_hmgt_add_remove_category',  'MJ_hmgt_add_remove_category');

add_action( 'wp_ajax_MJ_hmgt_remove_category', 'MJ_hmgt_remove_category');
add_action( 'wp_ajax_MJ_hmgt_remove_nurse_note', 'MJ_hmgt_remove_nurse_note');
add_action( 'wp_ajax_MJ_hmgt_add_category',  'MJ_hmgt_add_category');
add_action( 'wp_ajax_MJ_hmgt_get_bednumber',  'MJ_hmgt_get_bednumber');

add_action( 'wp_ajax_MJ_hmgt_get_bed_location',  'MJ_hmgt_get_bed_location');

add_action( 'wp_ajax_MJ_hmgt_patient_status_view',  'MJ_hmgt_patient_status_view');
add_action( 'wp_ajax_MJ_hmgt_add_nurse_notes',  'MJ_hmgt_add_nurse_notes');
add_action( 'wp_ajax_MJ_hmgt_add_doctor_notes',  'MJ_hmgt_add_doctor_notes');
add_action( 'wp_ajax_MJ_hmgt_user_profile',  'MJ_hmgt_user_profile');
add_action( 'wp_ajax_MJ_hmgt_patient_charges_view',  'MJ_hmgt_patient_charges_view');
add_action( 'wp_ajax_MJ_hmgt_patient_invoice_view',  'MJ_hmgt_patient_invoice_view');
add_action( 'wp_ajax_MJ_hmgt_view_event',  'MJ_hmgt_view_event');
add_action( 'wp_ajax_MJ_hmgt_view_priscription',  'MJ_hmgt_view_priscription');
add_action( 'wp_ajax_MJ_hmgt_load_convert_patient',  'MJ_hmgt_load_convert_patient');
add_action( 'wp_ajax_MJ_hmgt_sms_service_setting',  'MJ_hmgt_sms_service_setting');
add_action( 'wp_ajax_MJ_hmgt_change_profile_photo',  'MJ_hmgt_change_profile_photo');
add_action( 'wp_ajax_MJ_hmgt_import_data',  'MJ_hmgt_import_data');
add_action( 'wp_ajax_MJ_hmgt_get_patient_invoice',  'MJ_hmgt_get_patient_invoice');
add_action( 'wp_ajax_MJ_hmgt_load_invoice_cat',  'MJ_hmgt_load_invoice_cat');
add_action( 'wp_ajax_MJ_hmgt_invoice_entry_charge_autofill',  'MJ_hmgt_invoice_entry_charge_autofill');

add_action( 'wp_ajax_MJ_hmgt_load_prescription_id_madicine',  'MJ_hmgt_load_prescription_id_madicine');
add_action( 'wp_ajax_nopriv_MJ_hmgt_load_prescription_id_madicine',  'MJ_hmgt_load_prescription_id_madicine');

add_action( 'wp_ajax_MJ_hmgt_load_madicine_price_by_qty',  'MJ_hmgt_load_madicine_price_by_qty');
add_action( 'wp_ajax_nopriv_MJ_hmgt_load_madicine_price_by_qty',  'MJ_hmgt_load_madicine_price_by_qty');

add_action( 'wp_ajax_MJ_hmgt_load_madicine_html',  'MJ_hmgt_load_madicine_html');

add_action( 'wp_ajax_MJ_hmgt_get_appliyed_bad',  'MJ_hmgt_get_appliyed_bad');

add_action( 'wp_ajax_MJ_hmgt_load_patient_prescription',  'MJ_hmgt_load_patient_prescription');
add_action( 'wp_ajax_nopriv_MJ_hmgt_load_patient_prescription',  'MJ_hmgt_load_patient_prescription');
add_action( 'wp_ajax_nopriv_MJ_hmgt_save_outpatient_popup_form_template',  'MJ_hmgt_save_outpatient_popup_form_template');
add_action( 'wp_ajax_MJ_hmgt_save_outpatient_popup_form_template',  'MJ_hmgt_save_outpatient_popup_form_template');

add_action( 'wp_ajax_MJ_hmgt_instrument_assign_period',  'MJ_hmgt_instrument_assign_period');
add_action( 'wp_ajax_Mj_hmgt_save_doctor_popup_form',  'Mj_hmgt_save_doctor_popup_form');
add_action( 'wp_ajax_MJ_hmgt_save_outpatient_popup_form',  'MJ_hmgt_save_outpatient_popup_form');
add_action( 'wp_ajax_MJ_hmgt_asignbad_addbad_popup_form',  'MJ_hmgt_asignbad_addbad_popup_form');
add_action( 'wp_ajax_MJ_hmgt_save_nurce_popup_form',  'MJ_hmgt_save_nurce_popup_form');
add_action( 'wp_ajax_hmgt_select_symptoms',  'Mj_hmgt_select_symptoms');
add_action( 'wp_ajax_nopriv_Mj_hmgt_select_symptoms',  'Mj_hmgt_select_symptoms');
add_action( 'wp_ajax_Mj_hmgt_onchage_gate_apointment_time',  'Mj_hmgt_onchage_gate_apointment_time');
add_action( 'wp_ajax_MJ_hmgt_onchage_gate_apointment',  'MJ_hmgt_onchage_gate_apointment');
add_action( 'wp_ajax_hmgt_onchage_gate_apointment_time_avilability',  'MJ_hmgt_onchage_gate_apointment_time_avilability');
add_action( 'wp_ajax_hmgt_check_medicine_name_duplicate',  'hmgt_check_medicine_name_duplicate');
add_action( 'wp_ajax_MJ_hmgt_check_edit_medicine_name_duplicate',  'MJ_hmgt_check_edit_medicine_name_duplicate');
add_action( 'wp_ajax_MJ_hmgt_check_medicine_id_duplicate',  'MJ_hmgt_check_medicine_id_duplicate');
add_action( 'wp_ajax_hmgt_check_edit_medicine_id_duplicate',  'MJ_hmgt_check_edit_medicine_id_duplicate');
add_action( 'wp_ajax_MJ_hmgt_add_more_dignosis',  'MJ_hmgt_add_more_dignosis');
add_action( 'wp_ajax_MJ_hmgt_edit_diagnosisreport_name',  'MJ_hmgt_edit_diagnosisreport_name');
add_action( 'wp_ajax_nopriv_MJ_hmgt_edit_diagnosisreport_name',  'MJ_hmgt_edit_diagnosisreport_name');
add_action( 'wp_ajax_MJ_hmgt_update_cancel_diagnosisreport_name',  'MJ_hmgt_update_cancel_diagnosisreport_name');
add_action( 'wp_ajax_nopriv_MJ_hmgt_update_cancel_diagnosisreport_name',  'MJ_hmgt_update_cancel_diagnosisreport_name');
add_action( 'wp_ajax_MJ_hmgt_update_diagnosisreport_name',  'MJ_hmgt_update_diagnosisreport_name');
add_action( 'wp_ajax_nopriv_MJ_hmgt_update_diagnosisreport_name',  'MJ_hmgt_update_diagnosisreport_name');

add_action( 'wp_ajax_MJ_hmgt_count_dagnosisreport_amount',  'MJ_hmgt_count_dagnosisreport_amount');
add_action( 'wp_ajax_nopriv_MJ_hmgt_count_dagnosisreport_amount',  'MJ_hmgt_count_dagnosisreport_amount');

add_action( 'wp_ajax_MJ_hmgt_update_diagnosis_report_status_function',  'MJ_hmgt_update_diagnosis_report_status_function');
add_action( 'wp_ajax_nopriv_MJ_hmgt_update_diagnosis_report_status_function',  'MJ_hmgt_update_diagnosis_report_status_function');

add_action( 'wp_ajax_MJ_hmgt_hmgt_add_more_charge_entry',  'MJ_hmgt_hmgt_add_more_charge_entry');
add_action( 'wp_ajax_MJ_hmgt_operation_charge_calculation',  'MJ_hmgt_operation_charge_calculation');

add_action( 'wp_ajax_MJ_hmgt_uploads_dagnosisreport_table_formate',  'MJ_hmgt_uploads_dagnosisreport_table_formate');
add_action( 'wp_ajax_MJ_hmgt_patient_address',  'MJ_hmgt_patient_address');
add_action( 'wp_ajax_MJ_hmgt_show_event_notice',  'MJ_hmgt_show_event_notice');
add_action( 'wp_ajax_MJ_hmgt_discount_onchange_invoice_entry_charge_autofill',  'MJ_hmgt_discount_onchange_invoice_entry_charge_autofill');
add_action( 'wp_ajax_MJ_hmgt_discount_onchange_new_invoice_entry_charge_autofill',  'MJ_hmgt_discount_onchange_new_invoice_entry_charge_autofill');

// Add more diagnosis REPORT fronted side //
function MJ_hmgt_add_more_dignosis()
{
	?>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/lib/bootstrap-fileinput-master/js/fileinput.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/lib/bootstrap-fileinput-master/js/fileinput.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/lib/bootstrap-fileinput-master/js/fileinput_locale_es.js'; ?>"
></script>

<div class="form-group"><label class="col-sm-2 control-label" for="diagnosis"><?php _e('Diagnosis Report','hospital_mgt');?></label><div class="col-sm-3 margin_bottom_5px"><input type="file" class="dignosisreport form-control file" name="diagnosis[]"></div><div class="col-sm-2"><input type="button" value="<?php _e('Delete','hospital_mgt') ?>" onclick="deleteParentElement(this)" class="remove_cirtificate btn btn-default"></div></div>

<?php	
die();
}
// GET APPOINTMENT TIME  //
function Mj_hmgt_onchage_gate_apointment_time()
{	
		$patient_id=$_POST['patient_id'];
		$doctor_id=$_POST['doctor_id'];		
		$date=MJ_hmgt_get_format_for_db($_POST['apointment_date']);
		$day=$_POST['dayofweek'];
		$docter_avilable_time = array();
		$allpatient_appointment_time = array();
		
		global $wpdb;
	    $table_appointment_time = $wpdb->prefix. 'hmgt_apointment_time';
		$table_appointment = $wpdb->prefix. 'hmgt_appointment';
		
		if(!empty($patient_id))
		{
			$user_id = wp_get_current_user();
			$current_doctor_id=$user_id->ID;
			$result_allpatient_appointment_time=$wpdb->get_results("SELECT appointment_time  FROM $table_appointment where appointment_date='".$date."' and doctor_id=".$current_doctor_id."");	
			$result_docter_avilable_appointment_time=$wpdb->get_results("SELECT apointment_time FROM $table_appointment_time where day='".$day."' and '$date' between apointment_startdate and apointment_enddate AND user_id=".$current_doctor_id."");
		}
 		if(!empty($doctor_id))
		{
			
		$result_allpatient_appointment_time=$wpdb->get_results("SELECT appointment_time  FROM $table_appointment where appointment_date='".$date."' and doctor_id=".$doctor_id."");
		

		$result_docter_avilable_appointment_time=$wpdb->get_results("SELECT apointment_time,apointment_startdate,apointment_enddate  FROM $table_appointment_time where day='".$day."' and '$date' between apointment_startdate and apointment_enddate AND user_id=".$doctor_id."");
		}	
		if(!empty($patient_id) && !empty($doctor_id))
		{
			$result_allpatient_appointment_time=$wpdb->get_results("SELECT appointment_time  FROM $table_appointment where appointment_date='".$date."'  and doctor_id=".$doctor_id."");
			
			$result_docter_avilable_appointment_time=$wpdb->get_results("SELECT apointment_time,apointment_startdate,apointment_enddate  FROM $table_appointment_time where day='".$day."' and '$date' between apointment_startdate and apointment_enddate AND user_id=".$doctor_id."");
		}
		 foreach($result_docter_avilable_appointment_time as $times)
		 {			
			 $docter_avilable_time[] = strtotime($times->apointment_time); 
		 }
		  foreach($result_allpatient_appointment_time as $time)
		 {
			 $allpatient_appointment_time[] = strtotime($time->appointment_time); 		
		 }
		 $result_diffrent_appointment_time_array =array_diff($docter_avilable_time,$allpatient_appointment_time);
		  foreach($result_diffrent_appointment_time_array as $array)
		 {
			 $new_Diff[] = date("H:i",$array);
		 } 
		
     echo json_encode($new_Diff);
	 die(); 
}
// GET APPOINTMENT TIME //
function MJ_hmgt_onchage_gate_apointment()
{
    $edit_apointment_date=$_POST['edit_apointment_date'];
    $edit_apointment_time[]=$_POST['edit_apointment_time'];	  
    $date=MJ_hmgt_get_format_for_db($_POST['apointment_date']);
	$doctor_id=$_POST['doctor_id'];
	$patient_id=$_POST['patient_id'];
		
	if($date == $edit_apointment_date)
	{
			global $wpdb;
			$table_appointment = $wpdb->prefix. 'hmgt_appointment';
			if(!empty($patient_id) && !empty($doctor_id))
	     	{
				$doctor_id=$_POST['doctor_id'];
				$allbook_appointmet_result=$wpdb->get_results("SELECT appointment_time  FROM $table_appointment where appointment_date='".$date."' and doctor_id=".$doctor_id."");
				
		    }	
			foreach($allbook_appointmet_result as $appointment)
		    {
				$time_new = strtotime($appointment->appointment_time);
				$book_appointment[]  = date('H:i', $time_new); 
				
		    }
			$result_differant_appointment_array = array_diff($book_appointment,$edit_apointment_time);
			
			 if(!empty($patient_id))
			 {
				 $user_id = wp_get_current_user();
				 $current_doctor_id=$user_id->ID;
				 $allbook_appointmet_result1=$wpdb->get_results("SELECT appointment_time  FROM $table_appointment where appointment_date='".$date."' and doctor_id=".$current_doctor_id."");
			 }
			
			foreach($allbook_appointmet_result1 as $appointment)
		    {
				
				$time_new = strtotime($appointment->appointment_time);
				$book_appointment[]  = date('H:i', $time_new); 				
		    }
			
			$result_differant_appointment_array = array_diff($book_appointment,$edit_apointment_time);
			
			$result['book_appointment_time'] = $result_differant_appointment_array; 
			$result['edit_appointment_time'] = $edit_apointment_time; 
	        
			echo json_encode($result);	    
	}
	else
	{
		global $wpdb;
	    $table_appointment = $wpdb->prefix. 'hmgt_appointment';
		if(!empty($patient_id))
		{
			$user_id = wp_get_current_user();
			$current_doctor_id=$user_id->ID;
			$result=$wpdb->get_results("SELECT appointment_time  FROM $table_appointment where appointment_date='".$date."' and doctor_id=".$current_doctor_id."");
		}
		if(!empty($doctor_id))
		{
			$result=$wpdb->get_results("SELECT appointment_time  FROM $table_appointment where appointment_date='".$date."' and doctor_id=".$doctor_id."");
		}
		if(!empty($patient_id) && !empty($doctor_id))
		{
			$result=$wpdb->get_results("SELECT appointment_time  FROM $table_appointment where appointment_date='".$date."' and doctor_id=".$doctor_id."");
		}
		foreach($result as $time)
		{	
			$time_new = strtotime($time->appointment_time);
			$timeArr1['book_appointment_time'][]  = date('H:i', $time_new); 			 
		}
		echo json_encode($timeArr1);
	}
	   
	 die();
}  
// CHECK  APOUINTMENT AVALIABLE OR NOT FUNCTION IN APPOINTMENT //
function MJ_hmgt_onchage_gate_apointment_time_avilability()
{
	$formate=MJ_hmgt_date_formate();
	if($formate=='d/m/Y')
	{
		$app_date = str_replace('/', '-', $_REQUEST['apointment_date']);
	}
	else
	{	
		$app_date =$_REQUEST['apointment_date'];
	}
	$date=date('Y-m-d', strtotime($app_date));
	$user_id = wp_get_current_user();
	$doctor_id=$user_id->ID;
	$array_var=array();

	global $wpdb;
	
	$table_appointment_time = $wpdb->prefix. 'hmgt_apointment_time';		
	
	$array_var['result']=$wpdb->get_results("SELECT apointment_startdate,apointment_enddate,apointment_time,day FROM $table_appointment_time where '$date' between apointment_startdate and apointment_enddate and user_id=".$doctor_id."");
	
	$array_var['dateformate']=MJ_hmgt_date_formate();
    
	foreach($array_var['result'] as $data)
	{		
		$array_var['date']= array("apointment_startdate" => "$data->apointment_startdate", "apointment_enddate" => "$data->apointment_enddate");
	}
	echo json_encode($array_var);

	die();
}  
  
// SELECT SYMPTOMS //
function Mj_hmgt_select_symptoms()
{
	$symptoms_post=get_post($_REQUEST['symptoms_id']);
	echo $symptoms_post->post_title;
	die(); 
	
}
// ASSIGN INSTRUMENT PERIOD //
function MJ_hmgt_instrument_assign_period()
{
?>
	<script type="text/javascript">
	$(document).ready(function()
	 {
				var start = new Date();
			var end = new Date(new Date().setYear(start.getFullYear()+1));
			$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
			$('#start_date').datepicker({
				startDate : start,
				endDate   : end,
				autoclose: true
			}).on('changeDate', function (selected) {
			var minDate = new Date(selected.date.valueOf());
			$('#end_date').datepicker('setStartDate', minDate);
			}); 
			$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
			$('#end_date').datepicker({
				startDate : start,
				endDate   : end,
				autoclose: true
			}).on('changeDate', function (selected) {
				var maxDate = new Date(selected.date.valueOf());
				$('#start_date').datepicker('setEndDate', maxDate);
			}); 
			
				 $('#start_time').timepicki(
				   {
					show_meridian:false,
					min_hour_value:0,
					max_hour_value:23,
					step_size_minutes:15,
					overflow_minutes:true,
					increase_direction:'up',
					disable_keyboard_mobile: true}
				  );
				  $('#end_time').timepicki(
					 {
					show_meridian:false,
					min_hour_value:0,
					max_hour_value:23,
					step_size_minutes:15,
					overflow_minutes:true,
					increase_direction:'up',
					disable_keyboard_mobile: true}
					);
		
	   });
	</script>
	<?php $obj_instrument = new MJ_hmgt_Instrumentmanage();
	if(isset($_REQUEST['instrument_id']) && $_REQUEST['instrument_id']!=""){
		$result = $obj_instrument->get_single_instrument($_REQUEST['instrument_id']);
		
	if($result->charge_type=='Daily'){ 
	
	?>
	<input id="charge_type"  type="hidden" value="Daily" name="charge_type">
	<div class="form-group">
			<label class="col-sm-2 control-label" for="instrument_start_date">
			<?php _e('Start Date','hospital_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="start_date" class="form-control validate[required] start" type="text"  
				value="" name="start_date">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="end_date">
			<?php _e('Expected End Date','hospital_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="end_date" class="form-control validate[required] end" type="text"  
				value="" name="end_date">
			</div>
		</div>
		
	<?php }	
	if($result->charge_type=='Hourly')
	{ 
	?>	
	<input id="charge_type"  type="hidden" value="Hourly" name="charge_type">
	<div class="form-group">
			<label class="col-sm-2 control-label" for="instrument_assign_date">
			<?php _e('Instrument Assign Date','hospital_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="start_date" class="form-control validate[required]" type="text" 
				value="" name="start_date">
			</div>
		</div>
	<div class="form-group">
			<label class="col-sm-2 control-label" for="instrument_start_date">
			<?php _e('Start Time','hospital_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="start_time" type="text" value="<?php if(isset($_POST['start_time'])) echo $_POST['start_time'];?>" class="form-control timepicker start validate[required]" name="start_time"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="end_date">
			<?php _e('Expected End Time','hospital_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="end_time" type="text" value="<?php if(isset($_POST['end_time'])) echo $_POST['end_time'];?>" class="form-control timepicker end  validate[required]" name="end_time"/>
			</div>
		</div>
		
	<?php }	
	
	}
	die();
}
// LOAD PATIENT PRESCRIPTION //
function MJ_hmgt_load_patient_prescription ()
{
	$obj_madicine = new MJ_hmgt_medicine();
	$patient_id = $_REQUEST['patient_id'];	
	global $wpdb;
	$table_priscription = $wpdb->prefix.'hmgt_priscription';
	$priscriptiondata = $wpdb->get_results("SELECT * FROM $table_priscription WHERE prescription_type='treatment' AND patient_id=$patient_id ");
		print '<option name="" value="">'.__("Select Prescription").'</option>';
		foreach($priscriptiondata as $key=>$val){		
			print '<option name="presciption_id" value="'.$val->priscription_id.'">'.MJ_hmgt_get_display_name($val->patient_id) .' - '.$val->pris_create_date.'</option>';
		}
	die();	
}
// SAVE TO ADD DOCTOR FROM POPUP //
function Mj_hmgt_save_doctor_popup_form()
{
   $user_object=new MJ_hmgt_user();
   if(isset($_FILES['doctor_cv']) && !empty($_FILES['doctor_cv']) && $_FILES['doctor_cv']['size'] !=0)
	{
		if($_FILES['doctor_cv']['size'] > 0)
			$cv=MJ_hmgt_load_documets($_FILES['doctor_cv'],'doctor_cv','CV');
	}
	else
	{
		if(isset($_REQUEST['hidden_cv']))
			$cv=$_REQUEST['hidden_cv'];
	}
	if(isset($_FILES['education_certificate']) && !empty($_FILES['education_certificate']) && $_FILES['education_certificate']['size'] !=0)
	{
		if($_FILES['education_certificate']['size'] > 0)
			$education_cert=MJ_hmgt_load_documets($_FILES['education_certificate'],'education_certificate','Edu');
	}
	else{
		if(isset($_REQUEST['hidden_education_certificate']))
			$education_cert=$_REQUEST['hidden_education_certificate'];
	}
	if(isset($_FILES['experience_cert']) && !empty($_FILES['experience_cert']) && $_FILES['experience_cert']['size'] !=0)
	{
		if($_FILES['experience_cert']['size'] > 0)
			$experience_cert=MJ_hmgt_load_documets($_FILES['experience_cert'],'experience_cert','Exp');
	}
	else
	{
		if(isset($_REQUEST['hidden_exp_certificate']))
			$experience_cert=$_REQUEST['hidden_exp_certificate'];
	}
	
	$ext=MJ_hmgt_check_valid_extension($_POST['hmgt_user_avatar']);
	$ext1=MJ_hmgt_check_valid_file_extension($cv);
	$ext2=MJ_hmgt_check_valid_file_extension($education_cert);
	$ext3=MJ_hmgt_check_valid_file_extension($experience_cert);
   
	if(!$ext == 0)
	{ 
		if(!$ext1 == 0 && !$ext2 == 0 && !$ext3 == 0  )
		{
			$result=$user_object->hmgt_add_user($_POST);
		
			 $docter_id=get_userdata($result);
		
			 $user_object->upload_documents($cv,$education_cert,$experience_cert,$result);
			 
			 $option = "<option value='".$docter_id->ID."'>".$docter_id->display_name."</option>";
			
			 
			 $array_var[] = $option;
			 echo json_encode($array_var);
		}
		else
		{
			echo 3;
		}	
	}
	else
	{
		echo 2;
	}	
	die();    
 }
// SAVE TO ADD OUTPATIENT FROM POPUP //
function MJ_hmgt_save_outpatient_popup_form()
{	 
	$user_object=new MJ_hmgt_user();

	//multiple diagnosis report insert //
	$upload_dignosis_array=array();

	if(!empty($_FILES['diagnosis']['name']))
	{
		$count_array=count($_FILES['diagnosis']['name']);

		for($a=0;$a<$count_array;$a++)
		{			
			foreach($_FILES['diagnosis'] as $image_key=>$image_val)
			{	
				if($_FILES['diagnosis']['name'][$a]!='')
				{	
					$diagnosis_array[$a]=array(
					'name'=>$_FILES['diagnosis']['name'][$a],
					'type'=>$_FILES['diagnosis']['type'][$a],
					'tmp_name'=>$_FILES['diagnosis']['tmp_name'][$a],
					'error'=>$_FILES['diagnosis']['error'][$a],
					'size'=>$_FILES['diagnosis']['size'][$a]
					);							
				}	
			}
		}
		if(!empty($diagnosis_array))
		{
			foreach($diagnosis_array as $key=>$value)		
			{	
				$get_file_name=$diagnosis_array[$key]['name'];	
				
				$upload_dignosis_array[]=MJ_hmgt_load_multiple_documets($value,$value,$get_file_name);				
			} 	
		}				
	}			
	$diagnosis_report_url=$upload_dignosis_array;
	$ext1=MJ_hmgt_check_valid_file_extension_for_diagnosis($diagnosis_report_url);
	if($ext1 == 0 )
	{ 
		$result=$user_object->hmgt_add_user($_POST); 
	 
		  $user=get_userdata($result);
		   $guardian_data=array('patient_id'=>$result,
							'doctor_id'=>$_POST['doctor'],
							'symptoms'=>implode(",",$_POST['symptoms']),
							'inpatient_create_date'=>date("Y-m-d H:i:s"),'inpatient_create_by'=>get_current_user_id()
					); 
		$inserted=MJ_hmgt_add_guardian($guardian_data,'');
		
		$patient_id=get_user_meta($result, 'patient_id', true);
		
		$user_object->upload_multiple_diagnosis_report($result,$upload_dignosis_array);			 
		$option="<option value='".$user->ID."'>".$user->display_name." - ".$patient_id."</option>";
		$array_var[] = $option;
		echo json_encode($array_var); 
	}
	else
	{
		echo 2;
	}  
	die(); 
}
// TEMPLATE OF PATIENT IN POPUP //
function MJ_hmgt_save_outpatient_popup_form_template()
{
		 $user_object=new MJ_hmgt_user();
		if(isset($_FILES['upload_user_avatar_image']) && !empty($_FILES['upload_user_avatar_image']) && $_FILES['upload_user_avatar_image']['size'] !=0)
		{
			if($_FILES['upload_user_avatar_image']['size'] > 0)
			{
			 $patient_image=MJ_hmgt_load_documets($_FILES['upload_user_avatar_image'],'upload_user_avatar_image','pimg');
			$patient_image_url=content_url().'/uploads/hospital_assets/'.$patient_image;
			}
			else 
			{
				$patient_image=$_REQUEST['hidden_upload_user_avatar_image'];
			$patient_image_url=$patient_image;
			}
				
		}
		else{
			if(isset($_REQUEST['hidden_upload_user_avatar_image']))
				$patient_image=$_REQUEST['hidden_upload_user_avatar_image'];
			$patient_image_url=$patient_image;
		}
		
	//multiple diagnosis insert //
	$upload_dignosis_array=array();

	if(!empty($_FILES['diagnosis']['name']))
	{
		$count_array=count($_FILES['diagnosis']['name']);

		for($a=0;$a<$count_array;$a++)
		{			
			foreach($_FILES['diagnosis'] as $image_key=>$image_val)
			{	
				if($_FILES['diagnosis']['name'][$a]!='')
				{	
					$diagnosis_array[$a]=array(
					'name'=>$_FILES['diagnosis']['name'][$a],
					'type'=>$_FILES['diagnosis']['type'][$a],
					'tmp_name'=>$_FILES['diagnosis']['tmp_name'][$a],
					'error'=>$_FILES['diagnosis']['error'][$a],
					'size'=>$_FILES['diagnosis']['size'][$a]
					);							
				}	
			}
		}
		if(!empty($diagnosis_array))
		{
			foreach($diagnosis_array as $key=>$value)		
			{	
				$get_file_name=$diagnosis_array[$key]['name'];	
				
				$upload_dignosis_array[]=MJ_hmgt_load_multiple_documets($value,$value,$get_file_name);				
			} 
		}				
	}			
	$diagnosis_report_url=$upload_dignosis_array;
	$ext1=MJ_hmgt_check_valid_file_extension_for_diagnosis($diagnosis_report_url);
	if($ext1 == 0 )
	{  
	   $result=$user_object->hmgt_add_user($_POST); 
	   $returnans=update_user_meta( $result,'hmgt_user_avatar',$patient_image_url);
	   $user=get_userdata($result);
	   $guardian_data=array('patient_id'=>$result,
						'doctor_id'=>$_POST['doctor'],
						'symptoms'=>implode(",",$_POST['symptoms']),
						'inpatient_create_date'=>date("Y-m-d H:i:s"),'inpatient_create_by'=>get_current_user_id()
				); 
		$inserted=MJ_hmgt_add_guardian($guardian_data,'');
		$patient_id=get_user_meta($result, 'patient_id', true);
		$user_object->upload_multiple_diagnosis_report($result,$upload_dignosis_array);
		$option="<option value='".$user->ID."'>".$user->display_name." - ".$patient_id."</option>";
		$array_var[] = $option;
		echo json_encode($array_var);
		
	}
	else
	{
		echo 2;
	}	 
	die();
}

// ASSIGN BED POPUP FORM //
function MJ_hmgt_asignbad_addbad_popup_form()
{
   global $wpdb;
   $table_bed = $wpdb->prefix. 'hmgt_bed';
   $obj_bed = new MJ_hmgt_bedmanage();
   $result = $obj_bed->hmgt_add_bed($_POST);
   $bad_id = $wpdb->get_var("SELECT * FROM $table_bed where bed_id= ".$result);
   $option = "<option value='".$bad_id->bed_id."'>".$bad_id->bed_number."</option>";
   $array_var[] = $option;
   echo json_encode($array_var);
   die(); 
}
// SAVE NURSE TO ADD FROM POPUP //
function MJ_hmgt_save_nurce_popup_form()
{ 
   global $wpdb;
   $user_object=new MJ_hmgt_user();
   $result=$user_object->hmgt_add_user($_POST);
   $nurce_id=get_userdata($result);
   $option = "<option value='".$nurce_id->ID."'>".$nurce_id->display_name."</option>";
   $array_var[] = $option;
   echo json_encode($array_var);
   die();    
}   
// CHANGE PROFILE PHOTO IN USER DASHBOARD //
function MJ_hmgt_change_profile_photo()
{
	?>
	<div class="modal-header"> <a href="#" class="close-btn-cat badge badge-danger pull-right">X</a>
	</div>
	<form class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
	<div class="form-group">
	<label for="inputEmail" class="control-label col-sm-3"><?php _e('Select Profile Picture','hospital_mgt');?></label>
		<div class="col-xs-8">	
			<input id="input-1" name="profile" type="file" class="form-control file">
		</div>
	</div>
	<div class="form-group">
		<div class="col-xs-offset-2 col-sm-10">
				<button type="submit" class="btn btn-success" name="save_profile_pic"><?php _e('Save','hospital_mgt');?></button>
		</div>
	</div>
	</form>
    <?php 
	die();
}
// Import data function //
function MJ_hmgt_import_data()
{
	?>
	<div class="modal-header"><?php _e('Import Data','hospital_mgt');?><a href="#" class="close-btn-cat badge badge-danger pull-right">X</a>
	</div>
	<form class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
	<div class="form-group">
	<label for="inputEmail" class="control-label col-sm-2" style="padding-top: 0px;"><?php _e('Select CSV File','hospital_mgt');?></label>
		<div class="col-sm-8">	
			<input id="input-1" name="csv_file" type="file" class="form-control file">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10" style="margin-bottom:10px;">
			<button type="submit" class="btn btn-success" name="upload_csv_file"><?php _e('Save','hospital_mgt');?></button>
		</div>
	</div>
	</form>
    <?php 
	die();
}
// LOAD INVOICE CHATEGORIES //
function MJ_hmgt_load_invoice_cat()
{
	$types = $_REQUEST['types'];
	if($types=="Bed Charges")
	{
		$obj_bad = new Hmgtbedmanage;
		$beddata = $obj_bad->get_all_bed();
		print '<option value="">'.__('Select Title','hospital_mgt').'</option>';
		if(!empty($beddata))
		{
			foreach($beddata as $bed_key=>$bed_val)
			{
				print '<option value="'.$bed_val->bed_id.'">'.$bed_val->bed_number.'</option>';
			}
		}		
	}
	elseif($types=="Nurse Charges")
	{
		$allnurse = MJ_hmgt_getuser_by_user_role('nurse');		
		print '<option value="">'.__('Select Title','hospital_mgt').'</option>';
		if(!empty($allnurse))
		{
			foreach($allnurse as $nurse_key=>$nurse_val)
			{
				print '<option value="'.$nurse_val['id'].'">'.MJ_hmgt_get_display_name($nurse_val['id']).'</option>';
			}
		}	
	}
	elseif($types=="Doctor Fees")
	{
		$alldoctor = MJ_hmgt_getuser_by_user_role('doctor');	
		print '<option value="">'.__('Select Title','hospital_mgt').'</option>';	
		if(!empty($alldoctor))
		{
			foreach($alldoctor as $doc_key=>$doc_val)
			{
				print '<option value="'.$doc_val['id'].'">'.MJ_hmgt_get_display_name($doc_val['id']).'</option>';
			}
		}
	}
	elseif($types=="Operation Charges")
	{
			
		$operation_type=new MJ_hmgt_operation();
		$operationdata =$operation_type->get_all_operationtype();
		
		print '<option value="">'.__('Select Title','hospital_mgt').'</option>';
		if(!empty($operationdata))
		{
			foreach($operationdata as $ope_key=>$ope_val)
			{
				$operation_type_data=$ope_val->post_title;
				$operation_type_array=json_decode($operation_type_data);
				
				print '<option value="'.$ope_val->ID.'">'.$operation_type_array->category_name.'</option>';
			}
		}
	}
	elseif($types=="Instrument Charges")
	{
		$obj_instrument = new MJ_hmgt_Instrumentmanage();
		$instrumentdata = $obj_instrument->get_all_instrument();
		print '<option value="">'.__('Select Title','hospital_mgt').'</option>';
		
		if(!empty($instrumentdata))
		{
			foreach($instrumentdata as $instrument_key=>$instrument_val)
			{
				print '<option value="'.$instrument_val->id.'">'.$instrument_val->instrument_name.'</option>';
			}
		}
	}
	elseif($types=="Treatment Fees")
	{
		$obj_treatment = new MJ_hmgt_treatment;
		$treatmentdata = $obj_treatment->get_all_treatment();		
		print '<option value="">'.__('Select Title','hospital_mgt').'</option>';
		if(!empty($treatmentdata))
		{
			foreach($treatmentdata as $trea_key=>$trea_val)
			{
				print '<option value="'.$trea_val->treatment_id.'">'.$trea_val->treatment_name.'</option>';
			}
		}
	}
	elseif($types=="Ambulance Charges")
	{
		$obj_ambulance = new MJ_Hmgt_ambulance();
		$ambulance_data = $obj_ambulance->get_all_ambulance();		
		print '<option value="">'.__('Select Title','hospital_mgt').'</option>';
		if(!empty($ambulance_data))
		{	
			foreach($ambulance_data as $ambulance_key=>$ambulance_val)
			{
				print '<option value="'.$ambulance_val->amb_id.'">'.$ambulance_val->ambulance_id.'</option>';
			}
		}
	}
	elseif($types=="Blood Charges")
	{		
		$blood_data = blood_group();	
		print '<option value="">'.__('Select Title','hospital_mgt').'</option>';
		if(!empty($blood_data))
		{	
			foreach($blood_data as $blood_val)
			{
				print '<option value="'.$blood_val.'">'.$blood_val.'</option>';
			}
		}	
	}
	else
	{		
		$title = get_post($types);
		
		$charge_data=$title->post_title;
		$charge_type_array=json_decode($charge_data);		
						
		print '<option value="">'.__('Select Title','hospital_mgt').'</option>';	
		print '<option value="'.$types.'">'.$charge_type_array->category_name.'</option>';
	
	}
	die();
}
//charge on change INVOICE Entry charge auto fill //
function MJ_hmgt_invoice_entry_charge_autofill()
{
	$types = $_REQUEST['types'];
	$id = $_REQUEST['id'];
	$array_var = array();
	if($types!="Bed Charges" && $types !="Nurse Charges" && $types !="Doctor Fees" && $types !="Operation Charges" && $types !="Instrument Charges" && $types !="Treatment Fees" && $types !="Ambulance Charges" && $types !="Blood Charges")
	{
	   $title = get_post($id);
		
		$charge_data=$title->post_title;
		$charge_type_array=json_decode($charge_data);	
		
		$amount=$charge_type_array->charge_amount;
		$tax=$charge_type_array->charge_tax;
		
		$tax_array=explode(',',$tax);
		
		if(!empty($tax))
		{
			$total_tax=0;
			foreach($tax_array as $tax_id)
			{
				$tax_percentage=MJ_hmgt_tax_percentage_by_tax_id($tax_id);
				$tax_amount=$amount * $tax_percentage / 100;
				
				$total_tax=$total_tax + $tax_amount;
				
			}
			
			$total_tax=$total_tax;
			$total_amount=$amount+$total_tax;
		}
		else
		{
			$total_tax=0;
			$total_amount=$amount;
		}
		$total_discount=0;
		$readonly=1;			
	}
	else
	{
		$amount='';
		$total_tax='';
		$total_amount='';
		$total_discount='';
		$readonly=0;	
	}
	
	$array_var[] = $amount;
	$array_var[] = $total_discount;
	$array_var[] = $total_tax;
	$array_var[] = $total_amount;
	$array_var[] = $readonly;
	
	echo json_encode($array_var);
	die();
}
// discount on change old INVOICE Entry charge auto fill //
function MJ_hmgt_discount_onchange_invoice_entry_charge_autofill()
{
	$amount = $_REQUEST['amount'];
	$discount_amount = $_REQUEST['discount_amount'];
	$old_discount_amount = $_REQUEST['old_discount_amount'];
	$old_tax_amount = $_REQUEST['old_tax_amount'];
	
	$array_var = array();
    $amount_after_old_discount_minus=$amount-$old_discount_amount;
	$tax_percentage=$old_tax_amount*100/$amount_after_old_discount_minus;
	
	$after_discount_amount=$amount-$discount_amount;
	$after_discount_tax_amount=$after_discount_amount*$tax_percentage/100;	
	$total_amount=$amount-$discount_amount+$after_discount_tax_amount;

	$array_var[] = number_format($amount,2, '.', '');
	$array_var[] = number_format($discount_amount,2, '.', '');
	$array_var[] = number_format($after_discount_tax_amount,2, '.', '');
	$array_var[] = number_format($total_amount,2, '.', '');
	
	echo json_encode($array_var);
	die();
}
// discount on change new INVOICE Entry charge autofill //
function MJ_hmgt_discount_onchange_new_invoice_entry_charge_autofill()
{
	$types = $_REQUEST['type'];	
	$discount_amount = $_REQUEST['discount_amount'];	
	$array_var = array();
	
	if($types!="Bed Charges" && $types !="Nurse Charges" && $types !="Doctor Fees" && $types !="Operation Charges" && $types !="Instrument Charges" && $types !="Treatment Fees" && $types !="Ambulance Charges" && $types !="Blood Charges")
	{
		$title = get_post($types);
		
		$charge_data=$title->post_title;
		$charge_type_array=json_decode($charge_data);	
		
		$amount=$charge_type_array->charge_amount;
		$amount_after_discount=$amount-$discount_amount;
		$tax=$charge_type_array->charge_tax;
		
		$tax_array=explode(',',$tax);
		
		if(!empty($tax))
		{
			$total_tax=0;
			foreach($tax_array as $tax_id)
			{
				$tax_percentage=MJ_hmgt_tax_percentage_by_tax_id($tax_id);
				$tax_amount=$amount_after_discount * $tax_percentage / 100;
				
				$total_tax=$total_tax + $tax_amount;				
			}
			
			$total_tax=$total_tax;
			$total_amount=$amount_after_discount+$total_tax;
		}
		else
		{
			$total_tax=0;
			$total_amount=$amount_after_discount+$total_tax;
		}
		
		$charge_category=1;
		
		$array_var[] = number_format($discount_amount, 2, '.', '');
		$array_var[] = number_format($total_tax, 2, '.', '');
		$array_var[] = number_format($total_amount, 2, '.', '');
		$array_var[] = $charge_category;
		
		echo json_encode($array_var);
	}
	die();
} 
// LOAD MEDICIAN PRICE BY CATEGORY or discount//
function MJ_hmgt_load_madicine_price_by_qty()
{	
	$obj_medicine = new MJ_hmgt_medicine();
	$medication_id = $_REQUEST['dataid'];
	$discount_value = $_REQUEST['discount_value'];
	$med_discount_in = $_REQUEST['med_discount_in'];
	
	$medicationdata = $obj_medicine ->get_single_medicine($medication_id);	
	$total_price = $medicationdata->medicine_price *  $_REQUEST['qty'];
	
	if($med_discount_in == 'percentage')
	{
		$total_discount = $total_price * $discount_value / 100;
	}
	else
	{
		$total_discount = $discount_value;
	}
	
	$after_discount_total_price=$total_price - $total_discount;
	
	$tax_array=explode(',',$medicationdata->med_tax);
	
	if(!empty($tax_array))
	{
		$total_tax=0;
		foreach($tax_array as $tax_id)
		{
			$tax_percentage=MJ_hmgt_tax_percentage_by_tax_id($tax_id);
			$tax_amount=$after_discount_total_price * $tax_percentage / 100;
			
			$total_tax=$total_tax + $tax_amount;			
		}
	}
	else
	{
		$total_tax=0;		
	}
	
	$array_var=array();
	$array_var[]=number_format($total_price, 2, '.', '');
	$array_var[]=number_format($total_discount, 2, '.', '');
	$array_var[]=number_format($total_tax, 2, '.', '');
	echo json_encode($array_var);	
die();	
}
// LOAD MEDICINE HTML //
function MJ_hmgt_load_madicine_html(){
$obj_medicine = new MJ_hmgt_medicine();
?>
<script type="text/javascript">
$(document).ready(function() 
{	
	$('select.medication_class').select2();
});
</script>

<div class="form-group">		
	<label class="col-sm-2 control-label" for="medication"><?php _e('Medication','hospital_mgt');?><span class="require-field">*</span></label>
		<div class="col-sm-3">			
			<select name="medication[]"  class="form-control medication_class">				
				<?php 
					$medicinedata=$obj_medicine->get_all_medicine_in_stock();
					if(!empty($medicinedata))
					{
						$medicine_array = array ();
						foreach ($medicinedata as $retrieved_data){
							$medicine_array [] = $retrieved_data->medicine_name;
							echo '<option data-tokens="'.$retrieved_data->medicine_name.'" value="'.$retrieved_data->medicine_id.'">'.$retrieved_data->medicine_name.'</option>';
						}
					}
				?>
			</select>
		</div>
		<div class="col-sm-1 margin_bottom_5px padding_left_right_15px" style="padding:0px">
			<select name="times1[]" class="form-control  validate[required]">
				<option value=""><?php _e('Frequency','hospital_mgt');?></option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>					
			</select>
		</div>
		<div class="col-sm-1 margin_bottom_5px width_50_per" style="padding-right: 0px;"><input id="days" class="form-control validate[required]" type="number" step="1" maxlength="2" min="0" value="" name="days[]" placeholder="<?php _e('No Of','hospital_mgt');?>"></div>
		<div class="col-sm-1 margin_bottom_5px width_50_per" style="padding-right: 0px;">
			<select name="time_period[]" class="form-control validate[required]">				
				<option value="day"><?php _e('Day','hospital_mgt');?></option>
				<option value="week"><?php _e('Week','hospital_mgt');?></option>
				<option value="month"><?php _e('Month','hospital_mgt');?></option>
				<option value="hour"><?php _e('Hour','hospital_mgt');?></option>
			</select>
		</div>
		<div class="col-sm-2 margin_bottom_5px">
			<select name="takes_time[]" class="form-control validate[required]">
				<option value=""><?php _e('When to take','hospital_mgt');?></option>
				<option value="before_breakfast"><?php _e('Before Breakfast','hospital_mgt');?></option>
				<option value="after_meal"><?php _e('After Meal','hospital_mgt');?></option>
				<option value="before_meal"><?php _e('Before Meal','hospital_mgt');?></option>
				<option value="night"><?php _e('Night ','hospital_mgt');?></option>
			</select>
		</div>
		<div class="col-sm-1">
			<button type="button" class="btn btn-default" onclick="deleteParentElement(this)"><i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i></button>
		</div>
</div>

<?php 
die();
}
//GET PATIENT INVOICE //
function MJ_hmgt_get_patient_invoice()
{ 
	?>
	<!-- POP up code -->
	<div class="popup-bg medicine_details">
		<div class="overlay-content overlay_content_css">
			<div class="modal-content">			
				<div class="medicine_data"></div>			
			</div>
		</div>     
	</div> 
	<!-- End POP-UP Code -->
	<div class="modal-header" > 
		<a href="#" class="close-btn-cat badge badge-danger pull-right">X</a>
	</div>
	<?php
	if(!empty($_REQUEST['patient_id']))
	{
		$obj_invoice_genrate = new MJ_Hmgt_invoice_genrate;
		$obj_trtarment = new MJ_hmgt_treatment();	
		$priscriptiondata = $obj_invoice_genrate->get_all_by_patient_id($_REQUEST['patient_id']);
		?>
		<script>
		$(document).ready(function()
		{ 
			var date = new Date();
			date.setDate(date.getDate()-0);
			$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
			$('.invoice_date').datepicker({
			endDate: '+0d',
			autoclose: true
		   }); 
		});
		</script>
		<style>
		.table123 td, .table123>tbody>tr>td, .table123>tbody>tr>th, .table123>tfoot>tr>td, .table123>tfoot>tr>th, .table123>thead>tr>td, .table123>thead>tr>th
		{
			padding: 8px!important;
		}
		</style>
		<?php 
		$userdata = get_userdata($_REQUEST['patient_id']);
		print "<h4>".$userdata->display_name."</h4>";
		?>

		<form method="post" name="totle_form">
			<input type="hidden" name="patient_id" value="<?php echo $_REQUEST['patient_id'];?>">
			<div class="table123" id="patient_transaction">
				<div class="charges_heading">
					<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center padding_8">				
						#
					</div>
					<div class="col-md-2 col-sm-4 col-xs-12 div_padding_right_0 align_center padding_8">				
						<?php _e('Type','hospital_mgt');?>
					</div>
					<div class="col-md-2 col-sm-4 col-xs-12 div_padding_right_0 align_center padding_8">
					<?php _e('Title','hospital_mgt');?>
					</div>
					<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center padding_8">
					<?php _e('Date','hospital_mgt');?>
					</div>
					<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center padding_8">
					<?php _e('Unit','hospital_mgt');?>
					</div>
					<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center padding_8">
					<?php _e('Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(); ?>)
					</div>
					<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center padding_8">
					<?php _e('Discount Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(); ?>)
					</div>
					<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center padding_8">
					<?php _e('Tax Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(); ?>)
					</div>
					<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center padding_8">
					<?php _e('Total Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(); ?>)
					</div>
					<div class="col-md-1 col-sm-4 col-xs-12 align_center padding_8">
					<?php _e('Action','hospital_mgt');?>
					</div>
				</div>
				<div class="charges_body">
					<?php 				
					$i=0; 
					if(!empty($priscriptiondata))
					{
						foreach($priscriptiondata as $priscption_key=>$priscption_val)
						{			
							if($priscption_val->type == "Treatment Fees")
							{
								$type_title=$priscption_val->type;
								$title = $obj_trtarment->get_treatment_name($priscption_val->type_id);
							}
							elseif($priscption_val->type=="Operation Charges")
							{
								$type_title=$priscption_val->type;
								$obj_operation = new MJ_hmgt_operation;
								$opedata = $obj_operation->get_single_operation($priscption_val->type_id);
								$title=$obj_operation->get_operation_name($opedata->operation_title);				
							}
							elseif($priscption_val->type == "Bed Charges")
							{
								$type_title=$priscption_val->type;
								$obj_bed = new MJ_hmgt_bedmanage();
								$title ="Bed ".$obj_bed->get_bed_number($priscption_val->type_id);
							}
							elseif($priscption_val->type == "Instrument Charges")
							{
								$type_title=$priscption_val->type;
								$obj_instrument = new MJ_hmgt_Instrumentmanage();
								$instrumentdata=$obj_instrument->get_single_instrument($priscption_val->type_id); 
								$title =$instrumentdata->instrument_name;
							}
							elseif($priscption_val->type == "Ambulance Charges")
							{
								$type_title=$priscption_val->type;
								$obj_ambulance = new MJ_Hmgt_ambulance();
								$ambulance_data=$obj_ambulance->get_single_ambulance($priscption_val->type_id); 
								$title =$ambulance_data->ambulance_id;
							}
							elseif($priscption_val->type == "Dignosis Report Charges")
							{
								$type_title=$priscption_val->type;
								$type_id=explode(",",$priscption_val->type_id);
								$report_name=array();
								if(!empty($type_id))
								{
									foreach($type_id as $id)
									{
										$post = get_post( $id );
										$report_title=$post->post_title;
										$report_title_array=json_decode($report_title);						
										$report_name[]= $report_title_array->category_name;		
									}	
								}					
								$title = implode(",",$report_name);
							}
							elseif($priscption_val->type == "Blood Charges")
							{	
								$type_title=$priscption_val->type;
								$title =$priscption_val->type_id;
							}
							elseif($priscption_val->type == "Medicine Charges")
							{	
								$type_title=$priscption_val->type;
								$title =$priscption_val->type_id;
							}
							elseif($priscption_val->type == "Doctor Fees" || $priscption_val->type == "Nurse Charges")
							{	
								$type_title=$priscption_val->type;
								$first_name = get_user_meta($priscption_val->type_id,'first_name',true);
								$last_name = get_user_meta($priscption_val->type_id,'last_name',true);		
								$title = $first_name .' ' .$last_name;
							}
							else
							{	
								$title_data = get_post($priscption_val->type);
						
								$charge_data=$title_data->post_title;
								$charge_type_array=json_decode($charge_data);
								
								$type_title=$charge_type_array->category_name;
								$title = $type_title;
							}
													
							$type_value=number_format($priscption_val->type_value, 2, '.', '');
							$type_discount=number_format($priscption_val->type_discount, 2, '.', '');
							$type_tax=number_format($priscption_val->type_tax, 2, '.', '');
							
							if(!empty($priscption_val->type_total_value))
							{
								$type_total_value=number_format($priscption_val->type_total_value, 2, '.', '');
							}	
							else
							{
								$typetotal_value=$type_value-$type_discount+$type_tax;
								$type_total_value=number_format($typetotal_value, 2, '.', '');
							}
							?>					
								<div class="row">
									<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
										<input type="hidden" name="transaction_ids[cheak_<?php echo $i; ?>]" value="<?php echo $priscption_val->id; ?>">
										<input type="hidden" name="patient" value="<?php echo $_REQUEST['patient_id'];?>">
										<input id=""  type="checkbox" value="1" name="cheak[cheak_<?php echo $i; ?>]" checked >
									</div>
									<div class="col-md-2 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">		
										<input id="type" class="form-control text-input" type="text" value="<?php echo $type_title; ?>" name="type[]" placeholder="" readonly="readonly">
									</div>
									<div class="col-md-2 col-sm-4 col-xs-12 align_center div_padding_right_0 div_padding_bottom_5">	
										<input id="title"  class="form-control text-input" type="text" value="<?php echo $title; ?>" name="title[]" placeholder="" readonly="readonly">
									</div>
									<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
										<input id="date"  class="form-control text-input" type="text" value="<?php echo  date(MJ_hmgt_date_formate(),strtotime($priscption_val->date)); ?>" name="date[]" placeholder="" readonly="readonly">
									</div>
									<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
										<input id="unit"  class="form-control text-input" type="text" value="<?php echo $priscption_val->unit; ?>" name="unit[]" placeholder="" readonly="readonly">
									</div>		
									<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
										<input id="amount" class="form-control text-input amount_<?php echo $i; ?>" type="number" min="0"  onKeyPress="if(this.value.length==10) return false;" step="0.01" value="<?php echo $type_value; ?>" name="amount[cheak_<?php echo $i; ?>]" placeholder=""  readonly="readonly">
										
									</div>
									<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">
										<input type="hidden" name="old_discount_amount" class="old_discount_amount_<?php echo $i; ?>" value="<?php echo $type_discount; ?>"> 							
										<input id="discount_amount" class="form-control text-input transaction_discount discount_amount_<?php echo $i; ?>" type="number" dataid1="<?php echo $i; ?>" min="0"  onKeyPress="if(this.value.length==10) return false;" step="0.01" value="<?php echo $type_discount; ?>" name="discount_amount[cheak_<?php echo $i; ?>]" placeholder="" <?php if($priscption_val->type == "Medicine Charges") { ?> readonly="readonly" <?php } ?>>
										
									</div>
									<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
										<input type="hidden" name="old_tax_amount" class="old_tax_amount_<?php echo $i; ?>" value="<?php echo $type_tax; ?>"> 
										<input id="tax_amount" class="form-control text-input tax_amount_<?php echo $i; ?>" type="number" min="0"  onKeyPress="if(this.value.length==10) return false;" step="0.01" value="<?php echo $type_tax; ?>" name="tax_amount[cheak_<?php echo $i; ?>]" placeholder=""  readonly="readonly">
										
									</div>
									<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
										<input id="total_amount" class="form-control text-input total_amount_<?php echo $i; ?>" type="number" min="0"  onKeyPress="if(this.value.length==10) return false;" step="0.01" value="<?php echo $type_total_value; ?>" name="total_amount[cheak_<?php echo $i; ?>]" placeholder="" readonly="readonly">
									</div>
									<div class="col-md-1 col-sm-4 col-xs-12 align_center div_padding_bottom_5">	
									<?php
									if($priscption_val->type == "Medicine Charges")
									{
									?>
										<a href="?page=hmgt_medicine&dispatchmedicinepdf=dispatchmedicinepdf&dispatch_medicine_id=<?php echo $priscption_val->refer_id;?>" target="_blank" class="btn btn-primary"> <?php _e('View','hospital_mgt');?></a>
									<?php
									}
									?>	
									</div>												
								</div>									
							<?php  
							$i++; 
						}
						?>
						<div id="entriys">
						</div>
						<?php
					}
					else
					{	
					?>
						<div id="entriys">
							<div class="row">
								<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
									<input class="form-control" type="checkbox" name="newentry[newentry_0]" value="0" checked >
								</div>
								<div class="col-md-2 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
									<select name="type[newentry_0]" dataid="0" id="type_0" class="load_category more_invoice_charges dropdown_width_100_per" required>
										<option value=""><?php _e('Select Charge','hospital_mgt');?></option>
										<option value="Treatment Fees"><?php _e('Treatment Fees','hospital_mgt');?></option>
										<option value="Doctor Fees" ><?php _e('Doctor Fees','hospital_mgt');?></option>
										<option value="Operation Charges" ><?php _e('Operation Charges','hospital_mgt');?></option>
										<option value="Bed Charges" ><?php _e('Bed Charges','hospital_mgt');?></option>
										<option value="Nurse Charges" ><?php _e('Nurse Charges','hospital_mgt');?></option>
										<option value="Instrument Charges" ><?php _e('Instrument Charges','hospital_mgt');?></option>
										<option value="Ambulance Charges" ><?php _e('Ambulance Charges','hospital_mgt');?></option>
										<option value="Blood Charges" ><?php _e('Blood Charges','hospital_mgt');?></option>
										<?php
										$obj_invoice= new MJ_hmgt_invoice();
										$all_charge=$obj_invoice->get_all_invoice_charge();
										foreach($all_charge as $chargedata)
										{
											$charge_data=$chargedata->post_title;
											$charge_type_array=json_decode($charge_data);								
										?>
											<option value="<?php echo $chargedata->ID;?>" ><?php echo $charge_type_array->category_name; ?></option>
										<?php	
										}
										?>
									</select>
								</div>				
								<div class="col-md-2 col-sm-4 col-xs-12 align_center div_padding_right_0 div_padding_bottom_5">	
									<select id="title_0" class="charge_amount_autofill dropdown_width_100_per" dataid="0" style="width: 125px;" name="title[newentry_0]" required> 
										<option value=""><?php _e('Select Title','hospital_mgt');?></option>
									</select>
								</div>
								<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
									 <input id="date_0"  class="form-control text-input invoice_date" Placeholder="<?php _e('Date','hospital_mgt');?>" type="text"  name="date[newentry_0]" required>
								</div>
								<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
									<input id="unit_0" class="form-control text-input" type="text" Placeholder="<?php _e('Unit','hospital_mgt');?>"  name="unit[newentry_0]">
								</div>
								<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
									<input id="amount_0" class="form-control text-input" type="number" min="0"  onKeyPress="if(this.value.length==10) return false;" step="0.01" Placeholder="<?php _e('Amount','hospital_mgt');?>"  name="amount[newentry_0]"  required>
								</div>
								<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
									<input id="discount_amount_0" class="form-control text-input transaction_discount_new_entry" dataid1="0" type="number" min="0"  onKeyPress="if(this.value.length==10) return false;" step="0.01" Placeholder="<?php _e('Discount Amount','hospital_mgt');?>"  name="discount_amount[newentry_0]">
								</div>
								<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">		
									<input id="tax_amount_0" class="form-control text-input" type="number" min="0"  onKeyPress="if(this.value.length==10) return false;" step="0.01" Placeholder="<?php _e('Tax Amount','hospital_mgt');?>"  name="tax_amount[newentry_0]" >
								</div>
								<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5">	
									<input id="total_amount_0" class="form-control text-input" type="number" min="0"  onKeyPress="if(this.value.length==10) return false;" step="0.01" Placeholder="<?php _e('Total Amount','hospital_mgt');?>"  name="total_amount[newentry_0]"  required>
								</div>
								<div class="col-md-1 col-sm-4 col-xs-12 align_center div_padding_bottom_5">							
								</div>			
							</div>			
						</div>			
					<?php
					}
					?>	
				</div>
			</div>
			<div class="col-md-12" style="padding-top:10px;padding-bottom:10px;">
				<button id="add_new_entry" style="width:100%;" class="btn btn-default btn-sm btn-icon icon-left add_more_charge_entry" type="button" name="add_new_entry" ><span class="dashicons dashicons-plus-alt"></span> <?php _e('NEW ROW','hospital_mgt'); ?></button>
			</div>
			<div>
				<div class="col-md-3">	
					<button id="addremove"  model="invoice_charge" style="width:100%;margin-bottom: 10px;" class="btn btn-default btn-sm btn-icon icon-left" type="button"><span class="dashicons dashicons-plus-alt"></span><span class="dashicons dashicons-minus"></span> <?php _e('CHARGE CATEGORY','hospital_mgt'); ?></button>
				</div>
				<div class="col-md-2">	
					<input type="submit"  class="btn btn-success" style="width:100%;" style="margin-left:8px;" name="get_totale" value="<?php _e('SAVE','hospital_mgt'); ?>">
				</div>
			</div>
			<script>
				// CREATING BLANK INVOICE ENTRY
				var blank_income_entry ='';
				var blank_custom_label ='';
				$(document).ready(function()
				{ 
					blank_income_entry = $('#invoice_entry').html();
					blank_custom_label = $('#custom_labels').html();
						
				}); 	
				function add_entry()
				{
					$("#invoice_entry").append(blank_income_entry);
				}  
				// REMOVING INVOICE ENTRY
				function deleteParentElement(n)
				{
					n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
				}
			 </script>
		</form>
	<?php
	die(); 
	}
	else
	{ ?>
		<p><strong><?php _e('You must select patient.','hospital_mgt'); ?></strong></p>
	<?php 
	}	
}

// LOAD PRESCRIPTION DYNAMIC IN MEDICIAN //
function MJ_hmgt_load_prescription_id_madicine()
{
	$obj_madicine = new MJ_hmgt_medicine();
	$prescription_id = $_REQUEST['prescription_id'];
	
	global $wpdb;
	$table_priscription = $wpdb->prefix.'hmgt_priscription';
	$priscriptiondata = $wpdb->get_row("SELECT * FROM $table_priscription WHERE priscription_id=$prescription_id ");
	
	if(!empty($priscriptiondata))
	{	?> 
		<div class="form-group"><div class="col-sm-2"></div>
			 <div class="col-sm-2"><?php _e('Medicine','hospital_mgt');?></div>
			 <div class="col-sm-1"><?php _e('Quantity','hospital_mgt');?></div>
			 <div class="col-sm-1" style="medicine_padding_right_0"><?php _e('Price','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</div>
			  <div class="col-sm-2"><?php _e('Discount','hospital_mgt');?></div>
			  <div class="col-sm-1" style="padding_left_0"><?php _e('Discount Amount','hospital_mgt');?></div>
			 <div class="col-sm-1" style="padding_left_0"><?php _e('Tax','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</div>
			 <div class="col-sm-2"></div>
		 </div>
		<?php
		$i=1;
		$arrmadicine=json_decode($priscriptiondata->medication_list);		
		foreach($arrmadicine as $key=>$madicine)
		{			
			$singgle_madicine = $obj_madicine->get_single_medicine($madicine->medication_name);
		?>
		<div id="invoice_entry">
			<div class="form-group">		
				 <div class="col-sm-2 margin_bottom_5px"></div>
				<input type="hidden"  name="madicine_id[]" value="<?php print $singgle_madicine->medicine_id ?>">
				<input type="hidden"  class="madicine_quantity_<?php print $i;?>" name="madicine_quantity" value="<?php print $singgle_madicine->med_quantity ?>">
				<div class="col-sm-2 margin_bottom_5px">
					<input type="text" name="madicine_title[]" class="form-control" value="<?php print $singgle_madicine->medicine_name ?> " readonly>
				</div>
				<div class="col-sm-1 margin_bottom_5px">
					<input id="qty_<?php print $i;?>" class="days form-control validate[required] medicineqty_<?php print $singgle_madicine->medicine_id ?>" dataid="<?php print $singgle_madicine->medicine_id ?>" counter="<?php print $i;?>" class="form-control" type="number" min="0" value="0" name="qty[]">
				</div>
				<div class="col-sm-1 margin_bottom_5px" style="medicine_padding_right_0">
					<input id="price_<?php print $i;?>"  class="med_price form-control" type="text" value="0" name="price[]" readonly>
				</div>	
				<div class="col-sm-1 margin_bottom_5px" style="padding-right:0px;">
					<input id="discount_value_<?php print $i;?>" dataid="<?php print $singgle_madicine->medicine_id ?>" onKeyPress="if(this.value.length==10) return false;" step="0.01" class="med_discount_value form-control" type="number" value="<?php print $singgle_madicine->med_discount ?>" name="discount_value[]" counter="<?php print $i;?>">
				</div>	
				<div class="col-sm-1 margin_bottom_5px">
					<select class="form-control" id="med_discount_in_<?php print $i;?>" name="med_discountin[]" disabled>
						<option value="flat" <?php selected($singgle_madicine->med_discount_in,'flat'); ?>><?php _e('Flat','hospital_mgt');?></option>
						<option value="percentage" <?php selected($singgle_madicine->med_discount_in,'percentage'); ?>>%</option>
					</select>
					<input type="hidden" name="med_discount_in[]" value="<?php echo $singgle_madicine->med_discount_in; ?>">
				</div>	
				<div class="col-sm-1 margin_bottom_5px" style="padding_left_0">
					<input id="discount_<?php print $i;?>"  class="med_discount form-control" type="text" value="0" name="discount_amount[]" readonly>			
				</div>
				<div class="col-sm-1" style="padding_left_0">
					<input id="tax_<?php print $i;?>"  class="tax_amount form-control" type="text" value="0" name="tax_amount[]" readonly>
				</div>		
				 <div class="col-sm-2"></div>
			</div>
		</div>
		<?php 
		$i++; 
		}	
	}	
	else
	{
		print "No Medicine Available";
	}
	
die();	 
}
// GET APPLIYEDYED BAD //
function MJ_hmgt_get_appliyed_bad(){
	$allotment_date = $_REQUEST['allotment_date'];	
	$bednumber = $_REQUEST['bednumber'];
	
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_bed_allotment';
	$sql ="SELECT * FROM $table_name WHERE discharge_time=( SELECT MAX(discharge_time) FROM $table_name WHERE bed_number=$bednumber)";
	$result = $wpdb->get_results($sql);
	$result['0']->discharge_time;
	$currunt_date = date('Y-m-d');
	if(!empty($result['0']->discharge_time)){
		if($result['0']->discharge_time <= $currunt_date){ ?>
			<script>
				$("#save_allow").attr('disabled',false);
			</script>
		<?php }else{ 
			$patientdetails = MJ_hmgt_get_user_detail_byid($result[0]->patient_id);
			
			$name = $patientdetails['first_name'] ." ".$patientdetails['last_name']." - " .$patientdetails['patient_id'] . " Until " .$result['0']->discharge_time ;
		?>
		<script>
			$("#save_allow").attr('disabled',true);
			//$('#discharge_time').val("");
			swal("","<?php _e('Already Assigned Bed To ','hospital_mgt'); echo $name; ?>.","error");
		</script>
		<?php }
	}else{?>
	<script>
		$("#save_allow").attr('disabled',false);
	</script>
	<?php }
	die();
}
// ADD CATEGORY DYNAMIC //
function MJ_hmgt_add_remove_category()
{
	$model = $_REQUEST['model'];
	MJ_hmgt_add_category_type($model);
}
// ADD DYNAMIC CATEGORY //
function MJ_hmgt_add_category_type($model) 
{
	$title = "Title here";
	$table_header_title ="Table head";
	$button_text= "Button Text"; 
	$label_text = "Label Text";
	if($model == 'medicine')
	{
		$category_obj = new MJ_hmgt_medicine();
		$cat_result = $category_obj->get_all_category();
		$title = __("Medicine Category",'hospital_mgt');
		$table_header_title =  __("Medicine Category",'hospital_mgt');
		$button_text=  __("Add",'hospital_mgt');
		$label_text =  __("Category Name",'hospital_mgt');
	}
	if($model == 'department')
	{
		$user_object=new MJ_hmgt_user();
		$cat_result =$user_object->get_staff_department();
		$title = __("Department",'hospital_mgt');
		$table_header_title =  __("Department Name",'hospital_mgt');
		$button_text=  __("Add",'hospital_mgt');
		$label_text =  __("Department Name",'hospital_mgt');
	}
	if($model == 'bedtype')
	{
		$bed_type=new MJ_hmgt_bedmanage();
		$cat_result =$bed_type->get_all_bedtype();
		$title = __("Bed Category",'hospital_mgt');
		$table_header_title =  __("Bed Category Name",'hospital_mgt');
		$button_text=  __("Add",'hospital_mgt');
		$label_text =  __("Bed Category Name",'hospital_mgt');
	}
	if($model == 'operation')
	{
		$operation_type=new MJ_hmgt_operation();
		$cat_result =$operation_type->get_all_operationtype();
				
		$title = __("Operation",'hospital_mgt');
		$table_header_title =  __("Operation Name",'hospital_mgt');
		$table_header_amount =  __("Amount",'hospital_mgt');
		$table_header_description =  __("Description",'hospital_mgt');
		$table_header_tax =  __("Tax",'hospital_mgt');
		$button_text=  __("Add",'hospital_mgt');
		$label_text =  __("Operation Name",'hospital_mgt');
		$label_amount =  __("Amount",'hospital_mgt');
		$label_description =  __("Description",'hospital_mgt');
		$label_tax =  __("Tax",'hospital_mgt');
	}
	if($model == 'specialization')
	{
		$user_object=new MJ_hmgt_user();
		$cat_result =$user_object->get_doctor_specilize();
		$title = __("Specialization",'hospital_mgt');
		$table_header_title =  __("Specialization Name",'hospital_mgt');
		$button_text=  __("Add",'hospital_mgt');
		$label_text =  __("Specialization Name",'hospital_mgt');
	}
	if($model == 'report_type')
	{
		$user_object=new MJ_hmgt_dignosis();
		$cat_result =$user_object->get_all_report_type();
		$title = __("Diagnosis Report",'hospital_mgt');
		$table_header_title =  __("Report Name",'hospital_mgt');
		$table_header_amount =  __("Amount",'hospital_mgt');
		$table_header_description =  __("Description",'hospital_mgt');
		$table_header_tax =  __("Tax",'hospital_mgt');
		$button_text=  __("Add",'hospital_mgt');
		$label_text =  __("Report Name",'hospital_mgt');
		$label_amount =  __("Amount",'hospital_mgt');
		$label_description =  __("Description",'hospital_mgt');
		$label_tax =  __("Tax",'hospital_mgt');
	}
	if($model == 'symptoms')
	{
		$user_object=new MJ_hmgt_user();
		$cat_result =$user_object->getPatientSymptoms();
		$title = __("Symptoms",'hospital_mgt');
		$table_header_title =  __("Symptoms",'hospital_mgt');
		$button_text=  __("Add",'hospital_mgt');
		$label_text =  __("Symptoms",'hospital_mgt');
	}
	if($model == 'invoice_charge')
	{
		$obj_invoice= new MJ_hmgt_invoice();
		$cat_result =$obj_invoice->get_all_invoice_charge();
		$title = __("Invoice Charges",'hospital_mgt');
		$table_header_title =  __("Charge Name",'hospital_mgt');	
		$table_header_amount =  __("Amount",'hospital_mgt');
		$table_header_description =  __("Description",'hospital_mgt');
		$table_header_tax =  __("Tax",'hospital_mgt');
		$button_text=  __("Add",'hospital_mgt');
		$label_text =  __("Charge Name",'hospital_mgt');
		$label_amount =  __("Amount",'hospital_mgt');
		$label_description =  __("Description",'hospital_mgt');
		$label_tax =  __("Tax",'hospital_mgt');
	}
	
	if($model == 'invoice_charge')
	{
		$close_btn_name='close-btn-cat1';
	}
	else
	{
		$close_btn_name='close-btn-cat';
	}
	?>
	<div class="modal-header"> <a href="#" class="<?php echo $close_btn_name; ?> badge badge-danger pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title"><?php echo $title;?></h4>
	</div>
	<hr>
	<div class="panel panel-white">
  	    <div class="category_listbox">
  	        <div class="table-responsive" style="min-height: 100%;">			
			    <form name="report_typ_form" action="" method="post" class="form-horizontal" id="report_typ_form">
  	            <table class="table">			
				<?php 
					if($model == 'report_type')
	                { 
					?>				
					<thead>
						<tr>							
							<th><?php echo $table_header_title;?></th>
							<th><?php echo $table_header_amount;?></th>
							<th><?php echo $table_header_tax;?></th>
							<th><?php echo $table_header_description;?></th>
							<th><?php _e('Delete','hospital_mgt');?></th>
							<th><?php _e('Edit','hospital_mgt');?></th>
						</tr>
					</thead>
					<?php 
						$i = 1;
						if(!empty($cat_result))
						{							
							foreach ($cat_result as $retrieved_data)
							{
								$report_type=json_decode($retrieved_data->post_title);
									
								echo '<tr id="cat-'.$retrieved_data->ID.'">';
								echo '<td>'.$report_type->category_name.'</td>';
								echo '<td>'.$report_type->report_cost.'</td>';
								echo '<td>'.MJ_hmgt_tax_name_array_by_tax_id_array($report_type->diagnosis_tax).'</td>';
								echo '<td>'.$report_type->diagnosis_description.'</td>';
								echo '<td id='.$retrieved_data->ID.'><a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$retrieved_data->ID.'>X</a></td>';
								echo '<td id='.$retrieved_data->ID.'><a class="btn-edit-cat badge badge-edit"  model='.$model.' href="#" id="'.$retrieved_data->ID.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>';
								echo '</tr>';
								$i++;	
								
							}
						}
					}
					elseif($model == 'operation')
	                { 
					?>				
					<thead>
						<tr>							
							<th><?php echo $table_header_title;?></th>
							<th><?php echo $table_header_amount;?></th>
							<th><?php echo $table_header_tax;?></th>
							<th><?php echo $table_header_description;?></th>
							<th><?php _e('Delete','hospital_mgt');?></th>
							<th><?php _e('Edit','hospital_mgt');?></th>
						</tr>
					</thead>
					<?php 
						$i = 1;
						if(!empty($cat_result))
						{							
							foreach ($cat_result as $retrieved_data)
							{
								$report_type=json_decode($retrieved_data->post_title);
									
								echo '<tr id="cat-'.$retrieved_data->ID.'">';
								echo '<td>'.$report_type->category_name.'</td>';
								echo '<td>'.$report_type->operation_cost.'</td>';
								echo '<td>'.MJ_hmgt_tax_name_array_by_tax_id_array($report_type->operation_tax).'</td>';
								echo '<td>'.$report_type->operation_description.'</td>';
								echo '<td id='.$retrieved_data->ID.'><a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$retrieved_data->ID.'>X</a></td>';
								echo '<td id='.$retrieved_data->ID.'><a class="btn-edit-cat badge badge-edit"  model='.$model.' href="#" id="'.$retrieved_data->ID.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>';
								echo '</tr>';
								$i++;	
								
							}
						}
					} 
					elseif($model == 'invoice_charge')
	                { 
					?>				
					<thead>
						<tr>							
							<th><?php echo $table_header_title;?></th>
							<th><?php echo $table_header_amount;?></th>
							<th><?php echo $table_header_tax;?></th>
							<th><?php echo $table_header_description;?></th>
							<th><?php _e('Delete','hospital_mgt');?></th>
							<th><?php _e('Edit','hospital_mgt');?></th>
						</tr>
					</thead>
					<?php 
						$i = 1;
						if(!empty($cat_result))
						{							
							foreach ($cat_result as $retrieved_data)
							{
								$charge_type=json_decode($retrieved_data->post_title);
									
								echo '<tr id="cat-'.$retrieved_data->ID.'">';
								echo '<td>'.$charge_type->category_name.'</td>';
								echo '<td>'.$charge_type->charge_amount.'</td>';
								echo '<td>'.MJ_hmgt_tax_name_array_by_tax_id_array($charge_type->charge_tax).'</td>';
								echo '<td>'.$charge_type->charge_description.'</td>';
								echo '<td id='.$retrieved_data->ID.'><a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$retrieved_data->ID.'>X</a></td>';
								echo '<td id='.$retrieved_data->ID.'><a class="btn-edit-cat badge badge-edit"  model='.$model.' href="#" id="'.$retrieved_data->ID.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>';
								echo '</tr>';
								$i++;	
								
							}
						}
					}
					else
					{ ?>
				    <thead>
						<tr>							
							<th><?php echo $table_header_title;?></th>
							<th><?php _e('Action','hospital_mgt');?></th>
						</tr>
					</thead>
					<?php
					$i = 1;
						if(!empty($cat_result))
						{
							
							foreach ($cat_result as $retrieved_data)
							{
								echo '<tr id="cat-'.$retrieved_data->ID.'">';							
								echo '<td>'.$retrieved_data->post_title.'</td>';
								echo '<td id='.$retrieved_data->ID.'><a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$retrieved_data->ID.'>X</a></td>';
								echo '</tr>';
								$i++;		
							}
						}
					}
					?>
                </table>
				</form>
  	        </div>
  	    </div>
	<?php
	if($model == 'symptoms')
	{
	?>
	<form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
  	 	<div class="form-group">
			<label class="col-sm-4 control-label" for="medicine_name"><?php echo $label_text;?><span class="require-field">*</span></label>
			<div class="col-sm-4 margin_bottom_5px">
				<input id="medicine_name" class="validate[required,custom[popup_category_validation]] form-control text-input" maxlength="50" type="text" value="" name="category_name">
			</div>
			<div class="col-sm-4">
				<input type="button" value="<?php echo $button_text;?>" name="save_category" class="btn btn-success" model="<?php echo $model;?>" id="btn-add-cat"/>
			</div>
		</div>
  	</form>
	<?php 
	}
	elseif($model == 'specialization')
	{?>
    <form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
  	 	<div class="form-group">
			<label class="col-sm-4 control-label" for="medicine_name"><?php echo $label_text;?><span class="require-field">*</span></label>
			<div class="col-sm-4 margin_bottom_5px">
				<input id="medicine_name" class="validate[required,custom[popup_category_validation]] form-control text-input" maxlength="50" type="text" value="" name="category_name">
			</div>
			<div class="col-sm-4">
				<input type="button" value="<?php echo $button_text;?>" name="save_category" class="btn btn-success" model="<?php echo $model;?>" id="btn-add-cat"/>
			</div>
		</div>
  	</form>
		
	<?php
	}
	elseif($model == 'report_type')
	{
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {		
			$('.tax_charge').multiselect({
					nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
					includeSelectAllOption: true,
					selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
				 });
		} );
		</script>
    <form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
  	 	<div class="form-group">
			<label class="col-sm-2 control-label" for="medicine_name"><?php echo $label_text;?><span class="require-field">*</span></label>
			<div class="col-sm-4 margin_bottom_5px">
				<input id="medicine_name" class="validate[required,custom[popup_category_validation]] form-control text-input" maxlength="50" type="text" value="" name="category_name">
			</div>
			
			<label class="col-sm-1 control-label" for="medicine_name"><?php echo $label_amount;?><span class="require-field">*</span></label>
			<div class="col-sm-3 margin_bottom_5px">
				<input id="report_cost" class="form-control  text-input validate[required]" type="number" min="0" onkeypress="if(this.value.length==8) return false;" step="0.01" value="" name="report_cost">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="medicine_name"><?php echo $label_description;?><span class="require-field">*</span></label>
			<div class="col-sm-4">
				<textarea id="diagno_description" maxlength="50" class="form-control validate[required,custom[address_description_validation]]" name="diagno_description"> </textarea>
			</div>			
			<label class="col-sm-1 control-label" for=""><?php echo $label_tax;?></label>
				<div class="col-sm-3 margin_bottom_5px">
					<select  class="form-control tax_charge" id="diagnosis_tax" name="tax[]" multiple="multiple">					
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
				<div class="col-sm-2 margin_bottom_5px">
				<input type="button" value="<?php echo $button_text;?>" name="save_category" class="btn btn-success" model="<?php echo $model;?>" id="btn-add-cat"/>
			</div>
		</div>						
		
  	</form>		
	<?php
	}
	elseif($model == 'operation')
	{
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {		
			$('.tax_charge').multiselect({
					nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
					includeSelectAllOption: true,
					selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
				 });
		} );
		</script>
    <form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
  	 	<div class="form-group">
			<label class="col-sm-2 control-label" for="medicine_name"><?php echo $label_text;?><span class="require-field">*</span></label>
			<div class="col-sm-4 margin_bottom_5px">
				<input id="medicine_name" class="validate[required,custom[popup_category_validation]] form-control text-input" maxlength="50" type="text" value="" name="category_name">
			</div>
			
			<label class="col-sm-1 control-label" for="medicine_name"><?php echo $label_amount;?><span class="require-field">*</span></label>
			<div class="col-sm-3 margin_bottom_5px">
				<input id="operation_cost" class="form-control  text-input validate[required]" type="number" min="0" onkeypress="if(this.value.length==8) return false;" step="0.01" value="" name="operation_cost">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="medicine_name"><?php echo $label_description;?><span class="require-field">*</span></label>
			<div class="col-sm-4">
				<textarea id="operation_description" maxlength="50" class="form-control validate[required,custom[address_description_validation]]" name="operation_description"> </textarea>
			</div>			
			<label class="col-sm-1 control-label" for=""><?php echo $label_tax;?></label>
				<div class="col-sm-3 margin_bottom_5px">
					<select  class="form-control tax_charge" id="operation_tax" name="tax[]" multiple="multiple">					
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
				<div class="col-sm-2 margin_bottom_5px">
				<input type="button" value="<?php echo $button_text;?>" name="save_category" class="btn btn-success" model="<?php echo $model;?>" id="btn-add-cat"/>
			</div>
		</div>						
		
  	</form>
	<?php
	}
	elseif($model == 'invoice_charge')
	{
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($)
		{		
			$('.tax_charge').multiselect({
					nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
					includeSelectAllOption: true,
					selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
				 });
		} );
		</script>
    <form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
  	 	<div class="form-group">
			<label class="col-sm-2 control-label" for="medicine_name"><?php echo $label_text;?><span class="require-field">*</span></label>
			<div class="col-sm-4 margin_bottom_5px">
				<input id="medicine_name" class="validate[required,custom[popup_category_validation]] form-control text-input" maxlength="50" type="text" value="" name="category_name">
			</div>
			
			<label class="col-sm-1 control-label" for="medicine_name"><?php echo $label_amount;?><span class="require-field">*</span></label>
			<div class="col-sm-3 margin_bottom_5px">
				<input id="charge_amount" class="form-control  text-input validate[required]" type="number" min="0" onkeypress="if(this.value.length==8) return false;" step="0.01" value="" name="charge_amount">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="medicine_name"><?php echo $label_description;?><span class="require-field">*</span></label>
			<div class="col-sm-4">
				<textarea id="charge_description" maxlength="50" class="form-control validate[required,custom[address_description_validation]]" name="charge_description"> </textarea>
			</div>			
			<label class="col-sm-1 control-label" for=""><?php echo $label_tax;?></label>
				<div class="col-sm-3 margin_bottom_5px">
					<select  class="form-control tax_charge" id="charge_tax" name="tax[]" multiple="multiple">					
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
				<div class="col-sm-2 margin_bottom_5px">
				<input type="button" value="<?php echo $button_text;?>" name="save_category" class="btn btn-success" model="<?php echo $model;?>" id="btn-add-cat"/>
			</div>
		</div>						
		
  	</form>		
	<?php
	}
	else
	{ ?>
	<form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
  	 	<div class="form-group">
			<label class="col-sm-4 control-label" for="medicine_name"><?php echo $label_text;?><span class="require-field">*</span></label>
			<div class="col-sm-4 margin_bottom_5px">
				<input id="medicine_name" class="validate[required,custom[popup_category_validation]] form-control text-input" maxlength="50" type="text" value="" name="category_name">
			</div>
			<div class="col-sm-4">
				<input type="button" value="<?php echo $button_text;?>" name="save_category" class="btn btn-success" model="<?php echo $model;?>" id="btn-add-cat"/>
			</div>
		</div>
  	</form>
		
	<?php } ?>
  
  	</div>
	<?php 
	die();
}
// REMOVE DYNAMIC CATEGORY //
function MJ_hmgt_remove_category()
{
	$model = $_REQUEST['model'];
	if($model == 'medicine')
	{
		$obj_medicine = new MJ_hmgt_medicine();
		$obj_medicine->delete_medicine_category($_POST['cat_id']);
		die();
	}
	if($model == 'department')
	{
		$user_object=new MJ_hmgt_user();
		$user_object->delete_staff_department($_POST['cat_id']);
		die();
	}
	if($model == 'bedtype')
	{
		$bed_type=new MJ_hmgt_bedmanage();
		$cat_result =$bed_type->delete_bed_type($_POST['cat_id']);
	}
	if($model == 'specialization')
	{
		$user_object=new MJ_hmgt_user();
		$user_object->delete_doctor_specilize($_POST['cat_id']);
		die();
	}
	if($model == 'operation')
	{
		$operation_type=new MJ_hmgt_operation();
		$operation_type->delete_operation_type($_POST['cat_id']);
		die();
	}
	
	if($model == 'report_type')
	{
		$report_type=new MJ_hmgt_dignosis();
		$report_type->delete_report_type($_POST['cat_id']);
		die();
	}
	
	if($model == 'symptoms')
	{
		$user_object=new MJ_hmgt_user();
		$user_object->hmgtDeleteSymptoms($_POST['cat_id']);
		die();
	}
	if($model == 'invoice_charge')
	{
		$obj_invoice= new MJ_hmgt_invoice();
		$obj_invoice->delete_invoice_charge($_POST['cat_id']);
		die();
	}
}
// ADD DYNAMIC CATEGORY //
function MJ_hmgt_add_category()
{	
	global $wpdb;
	$model = $_REQUEST['model'];
	$array_var = array();
	$data['category_name'] = MJ_hmgt_strip_tags_and_stripslashes($_REQUEST['medicine_cat_name']);
	$data['report_cost'] = $_REQUEST['report_cost'];	
	$data['diagnosis_tax'] =implode(",",$_REQUEST['diagnosis_tax']);
	if(!empty($_REQUEST['diagnosis_tax']))
	{	
		$data['diagnosis_tax_name'] =MJ_hmgt_tax_name_array_by_tax_id_array(implode(",",$_REQUEST['diagnosis_tax']));
	}	
	$data['diagnosis_description'] = MJ_hmgt_strip_tags_and_stripslashes($_REQUEST['diagnosis_description']);
	
	$data['operation_cost'] = $_REQUEST['operation_cost'];	
	$data['operation_tax'] =implode(",",$_REQUEST['operation_tax']);
	if(!empty($_REQUEST['operation_tax']))
	{
		$data['operation_tax_name'] =MJ_hmgt_tax_name_array_by_tax_id_array(implode(",",$_REQUEST['operation_tax']));
	}	
	$data['operation_description'] = MJ_hmgt_strip_tags_and_stripslashes($_REQUEST['operation_description']);
	
	$data['charge_amount'] = $_REQUEST['charge_amount'];	
	$data['charge_tax'] =implode(",",$_REQUEST['charge_tax']);
	if(!empty($_REQUEST['charge_tax']))
	{
		$data['charge_tax_name'] =MJ_hmgt_tax_name_array_by_tax_id_array(implode(",",$_REQUEST['charge_tax']));
	}	
	$data['charge_description'] = MJ_hmgt_strip_tags_and_stripslashes($_REQUEST['charge_description']);
	
	if($model == 'medicine')
	{
		$obj_medicine = new MJ_hmgt_medicine();
		$obj_medicine->hmgt_add_medicinecategory($data);
		$id = $wpdb->insert_id;
	}
	if($model == 'department')
	{
		$user_object=new MJ_hmgt_user();
		$user_object->add_staff_department($data);
		$id = $wpdb->insert_id;
	}
	if($model == 'bedtype')
	{
		$bed_type=new MJ_hmgt_bedmanage();
		$bed_type->hmgt_add_bedtype($data);
		$id = $wpdb->insert_id;
	}
	if($model == 'specialization')
	{
		$user_object=new MJ_hmgt_user();
		$user_object->add_doctor_specilize($data);
		$id = $wpdb->insert_id;
	}
	if($model == 'operation')
	{
		$operation_type=new MJ_hmgt_operation();
		$operation_type->hmgt_add_operationtype($data);
		$id = $wpdb->insert_id;
	}
	if($model == 'report_type')
	{
		$report_type=new MJ_hmgt_dignosis();
		$report_type->hmgt_add_report_type($data);
		$id = $wpdb->insert_id;
	}
	if($model == 'symptoms')
	{
		$user_object=new MJ_hmgt_user();
		$user_object->hmgtAddSymptoms($data);
		$id = $wpdb->insert_id;
	}
	if($model == 'invoice_charge')
	{
		$obj_invoice= new MJ_hmgt_invoice();
		$obj_invoice->hmgt_add_invoice_charge($data);
		$id = $wpdb->insert_id;
	} 
	
	if($model == 'report_type')
	{
		$row1 = '<tr id="cat-'.$id.'"><td>'.stripslashes($_REQUEST['medicine_cat_name']).'</td>
		<td>'.$_REQUEST['report_cost'].'</td><td>'.$data['diagnosis_tax_name'].'</td><td>'.stripslashes($_REQUEST['diagnosis_description']).'</td>
		<td><a class="btn-delete-cat badge badge-delete" model="report_type" href="#" id='.$id.'>X</a></td>
		<td><a class="btn-edit-cat badge badge-edit" model="report_type" href="#" id='.$id.'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
		</tr>';
		
		$option = "<option value='$id'>".stripslashes($_REQUEST['medicine_cat_name'])."</option>";
		$array_var[] = $row1;
		$array_var[] = $option;
		echo json_encode($array_var);
	}	
	elseif($model == 'operation')
	{
		$row1 = '<tr id="cat-'.$id.'"><td>'.stripslashes($_REQUEST['medicine_cat_name']).'</td>
		<td>'.$_REQUEST['operation_cost'].'</td><td>'.$data['operation_tax_name'].'</td><td>'.stripslashes($_REQUEST['operation_description']).'</td>
		<td><a class="btn-delete-cat badge badge-delete" model="operation" href="#" id='.$id.'>X</a></td>
		<td><a class="btn-edit-cat badge badge-edit" model="operation" href="#" id='.$id.'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
		</tr>';
		
		$option = "<option value='$id'>".stripslashes($_REQUEST['medicine_cat_name'])."</option>";
		$array_var[] = $row1;
		$array_var[] = $option;
		echo json_encode($array_var);
	}
	elseif($model == 'invoice_charge')
	{
		$row1 = '<tr id="cat-'.$id.'"><td>'.stripslashes($_REQUEST['medicine_cat_name']).'</td>
		<td>'.$_REQUEST['charge_amount'].'</td><td>'.$data['charge_tax_name'].'</td><td>'.stripslashes($_REQUEST['charge_description']).'</td>
		<td><a class="btn-delete-cat badge badge-delete" model="invoice_charge" href="#" id='.$id.'>X</a></td>
		<td><a class="btn-edit-cat badge badge-edit" model="invoice_charge" href="#" id='.$id.'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
		</tr>';
		
		$option = "<option value='$id'>".stripslashes($_REQUEST['medicine_cat_name'])."</option>";
		$array_var[] = $row1;
		$array_var[] = $option;
		echo json_encode($array_var);
	}		
	else
	{
		$row1 = '<tr id="cat-'.$id.'"><td>'.stripslashes($_REQUEST['medicine_cat_name']).'</td><td><a class="btn-delete-cat badge badge-delete" href="#" id='.$id.'>X</a></td></tr>';
		$option = "<option value='$id'>".stripslashes($_REQUEST['medicine_cat_name'])."</option>";
		$array_var[] = $row1;
		$array_var[] = $option;
		echo json_encode($array_var);
	}
	die();
}

// GET BED NUMBER //
function MJ_hmgt_get_bednumber()
{
	$bed_type_id = $_POST['bed_type_id'];
	$obj_bed = new MJ_hmgt_bedmanage();
	$bedtype_data = $obj_bed->get_bed_by_bedtype($bed_type_id);	
	if(!empty($bedtype_data))
	{	
		echo '<option value=""> Select Bad Number</option>';
		foreach ($bedtype_data as $retrieved_data){			
			echo '<option value="'.$retrieved_data->bed_id.'" '.selected($bed_type1,$retrieved_data->bed_id).'>'.$retrieved_data->bed_number.'</option>';
		}
	}
	die();
}
// GET BED LOCATION //
function MJ_hmgt_get_bed_location()
{
	 $bed_no = $_REQUEST['bed_no'];
	
	$obj_bed = new MJ_hmgt_bedmanage();
	$beddata = $obj_bed->get_single_bed($bed_no);
	if(!empty($beddata->bed_location)) { ?>
		<p class="bg-info" style="padding:10px; float:left; width:100%"><strong><?php _e('Bed Location','hospital_mgt');?> : </strong><?php print $beddata->bed_location  ?></p>
	<?php } 	
	 exit;	
}

// PATIETN STATUS VIEW IN POPUP //
function MJ_hmgt_patient_status_view()
{
		 $uid=$_REQUEST['idtest'];
		 $obj_hospital = new Hospital_Management(get_current_user_id());
	?>
	<div class="modal-header"> <a href="#" class="close-btn-cat badge badge-danger pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title"><?php 
			$user=$user_info = get_userdata($uid);
			echo $user->display_name;
			?></h4>
	</div>
	<hr>
  	<ul class="nav nav-tabs panel_tabs" role="tablist">
  	 <?php if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'doctor' || 
  	 		$obj_hospital->role == 'nurse' || $obj_hospital->role == 'patient' || $obj_hospital->role == 'administrator'){?>
      <li class="active margin_bottom_5px">
          <a href="#diagnosis" role="tab" data-toggle="tab">
             <i class="fa fa-align-justify"></i> <?php _e(' Diagnosis Report', 'hospital_mgt'); ?></a>
          </a>
      </li>
      <?php }?>
      <li  class="<?php if($obj_hospital->role == 'pharmacist') {?>active<?php }?> margin_bottom_5px"><a href="#doctor_note" role="tab" data-toggle="tab">
        <i class="fa fa-align-justify"></i> <?php _e('Doctor Notes', 'hospital_mgt'); ?></a> 
      </li>
      <?php if( $obj_hospital->role == 'doctor' || 
  	 		$obj_hospital->role == 'nurse' || $obj_hospital->role == 'patient' || $obj_hospital->role == 'administrator'){?>
	  <li class="margin_bottom_5px"><a href="#patient_history" role="tab" data-toggle="tab">
        <i class="fa fa-align-justify"></i> <?php _e('Patient History', 'hospital_mgt'); ?></a> 
      </li>
      <?php }?>
       <?php if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'doctor' || 
  	 		$obj_hospital->role == 'nurse' || $obj_hospital->role == 'patient' || $obj_hospital->role == 'administrator'){?>
	   <li class="margin_bottom_5px"><a href="#nurse_notes" role="tab" data-toggle="tab" >
        <i class="fa fa-align-justify"></i> <?php _e('Nurse Notes', 'hospital_mgt'); ?></a> 
      </li>
      <?php }?>
    </ul>
		<div class="tab-content">
			<div class="tab-pane fade  <?php if($obj_hospital->role != 'pharmacist') {?> active in <?php }?>"  id="diagnosis">
				<div class="panel panel-white">
					<div class="panel-body patient_viewbox_full">
						<?php
						if($obj_hospital->role == 'doctor' || $obj_hospital->role == 'laboratorist') 
						{
						
							$path = "?dashboard=user&page=diagnosis&tab=adddiagnosis&action=insert&patient_id=".$uid;
							$path1="?dashboard=user&page=prescription&tab=addprescription&action=insert&type=report&patient_id=".$uid;	
						?>
						<div class="print-button ">
							<a  href="<?php echo $path;?>" class="btn btn-success margin_bottom_5px"><?php _e('Upload Diagnosis Report','hospital_mgt');?></a>
							<?php 
							if($obj_hospital->role == 'doctor')
							{
							?>		
								<a  href="<?php echo $path1;?>" class="btn btn-success margin_bottom_5px"><?php _e('Prescribe New Report','hospital_mgt');?></a>
							<?php
							}
							?>
						</div>
						<?php
						}
						elseif($obj_hospital->role == 'administrator')
						{	
							$path=admin_url()."admin.php?page=hmgt_diagnosis&tab=adddiagnosis&action=insert&patient_id=".$uid;						 
						 ?>
						 <div class="print-button ">
							<a  href="<?php echo $path;?>" class="btn btn-success margin_bottom_5px"><?php _e('Upload Diagnosis Report','hospital_mgt');?></a>							
						</div>
						 <?php } ?>
					    <div class="clearfix"></div>
							<?php
								$diagnosis_obj=new MJ_hmgt_dignosis(); 
								$diagnosisdata=$diagnosis_obj->get_diagnosis_by_patient($uid);
								foreach($diagnosisdata as $diagnosis)
								{
									if(MJ_hmgt_isJSON($diagnosis->attach_report))
									{
										$dignosis_array=json_decode($diagnosis->attach_report);
										
										foreach($dignosis_array as $key=>$value)
										{
											$report_type=new MJ_hmgt_dignosis();
											$report_data=$report_type->get_report_by_id($value->report_id);
											$report_type_array=json_decode($report_data);
										?>
										<div class="form-group">
											<div class="col-md-10">
												<blockquote class="diagnosis-report">
												 <b><?php $doctor_data= get_userdata($diagnosis->diagno_create_by);
												 echo __('Diagnosis report by','hospital_mgt')." ".$doctor_data->display_name." on ".date(MJ_hmgt_date_formate(),strtotime($diagnosis->diagnosis_date));?> </b>
												 <?php if($diagnosis->attach_report!=""){?>
													<a href="<?php echo content_url().'/uploads/hospital_assets/'.$value->attach_report;?>" target="_blank" class="btn btn-default"><i class="fa fa-download"></i> <?php echo $report_type_array->category_name.' '.__('Report','hospital_mgt'); ?></a>
													<?php }
														else{?>
															<a href="#" class="btn btn-default"><i class="fa fa-download"></i><?php _e('No Report','hospital_mgt');?></a>
														<?php }
														if($diagnosis->diagno_description!=""){?>
													<p>"<?php echo $diagnosis->diagno_description; ?>"</p>
														<?php }?>
												</blockquote>
											</div>
										</div>
										<?php
										}
									}
									else
									{
										?>
										<div class="form-group">
											<div class="col-md-10">
												<blockquote class="diagnosis-report">
												 <b><?php $doctor_data= get_userdata($diagnosis->diagno_create_by);
												 echo __('Diagnosis report by','hospital_mgt')." ".$doctor_data->display_name." on ".date(MJ_hmgt_date_formate(),strtotime($diagnosis->diagnosis_date));?> </b>
												 <?php if($diagnosis->attach_report!=""){?>
													<a href="<?php echo content_url().'/uploads/hospital_assets/'.$diagnosis->attach_report;?>" target="_blank" class="btn btn-default"><i class="fa fa-download"></i><?php _e('View Report','hospital_mgt');?></a>
													<?php }
														else{?>
															<a href="#" class="btn btn-default"><i class="fa fa-download"></i><?php _e('No Report','hospital_mgt');?></a>
														<?php }
														if($diagnosis->diagno_description!=""){?>
													<p>"<?php echo $diagnosis->diagno_description; ?>"</p>
														<?php }?>
												</blockquote>
											</div>
										</div>
										<?php
									}								
								} 
								$obj_var=new MJ_hmgt_prescription();
								$prescribedata=$obj_var->get_all_report_prescription_patientid($uid);
								foreach($prescribedata as $prescribe)
								{
								?>	
									<div class="form-group">
										<div class="col-md-10">
											<blockquote class="diagnosis-report">
											 <b><?php 
											 $doctor_data= get_userdata($prescribe->prescription_by);
											 echo __('Prescribe report by','hospital_mgt')." ".$doctor_data->display_name." on ".date(MJ_hmgt_date_formate(),strtotime($prescribe->pris_create_date));?> </b>
												<?php
												if($prescribe->report_description!="")
												{
												?>
												<p>"<?php echo $prescribe->report_description; ?>"</p>
												<?php 
												}
												?>
											</blockquote>
										</div>
									</div>
								<?php 
								}
								?>	
						</div>
					</div>
			</div>
			<div class="tab-pane fade  <?php if($obj_hospital->role == 'pharmacist') {?> active in <?php }?>" id="doctor_note">
				<div class="panel-body patient_viewbox_full ">
					<?php if($obj_hospital->role == 'doctor') {
					  if($obj_hospital->role == 'doctor' || $obj_hospital->role == 'laboratorist')
						$path = "?dashboard=user&page=prescription&tab=addprescription&action=insert&patient_id=".$uid;
					 else 
						$path="?page=diagnosis&tab=adddiagnosis&action=insert";
						?>
					<div class="print-button">
							<a  href="<?php echo $path;?>" class="btn btn-success"><?php _e('Add Prescription','hospital_mgt');?></a>
					</div>
					<?php }
					elseif($obj_hospital->role == 'administrator')
									 {	$path=admin_url()."admin.php?page=hmgt_prescription&tab=addprescription&action=insert&patient_id=".$uid;?>
									 <div class="print-button">
										<a  href="<?php echo $path;?>" class="btn btn-success"><?php _e('Add Prescription','hospital_mgt');?></a>
									</div>
									 <?php }?>
							<?php 
							$patient_id=$uid;
							$patientreport=MJ_hmgt_display_patient_reports($uid);
							foreach($patientreport as $report)
							{
							
							$patient_id=$report->patient_id;
							?>
							<div class="col-md-10">
								<p>
								<label  class="control-label create-date" style="border: 1px solid;">
									<?php  echo date(MJ_hmgt_date_formate(),strtotime($report->pris_create_date)); ?></b>
								</label>
							</div>
							<div class="form-group doctor_note_part">
							    <div class="col-md-10 date_wise">
								<b><?php _e("case History","hospital_mgt");?></b>
									<p><?php 
									echo $report->case_history;?></p>
								</div>
								
								<div class="col-md-10 date_wise">
								    <hr><b><?php _e("Medicine List","hospital_mgt");?></b>
									<p>
									<div class="table-responsive">
										<table class="table">
											<thead>
												<tr>
													<th><?php _e('Name','hospital_mgt');?></th>
													<th><?php _e('Times ','hospital_mgt');?></th>
													<th><?php _e('Days','hospital_mgt');?></th>
												</tr>
											</thead>
											<tbody>
											<?php
												$obj_medicine = new MJ_hmgt_medicine();
												$medicine_list=json_decode($report->medication_list);
												foreach($medicine_list as $retrieved_data)
												{?>
													<tr>
													
													<td><?php 
														$medicine=$obj_medicine->get_single_medicine($retrieved_data->medication_name);
													echo $medicine->medicine_name; ?></td>
													<td><?php echo $retrieved_data->time; ?></td>
													<td><?php echo $retrieved_data->per_days; ?></td>
													</tr>
												<?php }
											//echo $report->medication_list;?>
											</tbody>
										</table>
									</div>
									</p>
								</div>
								
								<div class="col-md-10 date_wise">
								<hr><b><?php _e("Extra Note","hospital_mgt");?></b>
									<p><?php 
									
									?></p>
								</div>
							</div>
						
						<?php } 
								$user_object=new Hospital_Management();
								$patient_note_id=$user_object-> get_nurse_notes($patient_id);
						?>
							<div class="col-xs-10 doctor_notes">
							<?php foreach($patient_note_id as $notepost_id){
								$note_data= get_post($notepost_id->post_id);
								if($note_data->post_type=='doctor_notes' || $note_data->post_type=='administrator_notes'){
								echo '<div class="col-md-10 ">';
								echo '<blockquote id="note-'.$notepost_id->post_id.'">';
								echo  '<b><h4>';
								
								  $postdate=$note_data->post_date;
								
								$nurse=get_userdata($note_data->post_author);
								
								echo __('Notes by','hospital_mgt')." ".$nurse->display_name." on ".     date(MJ_hmgt_date_formate().' H:i:s',strtotime($note_data->post_date)).'</h4></b>';
								echo '<p>'.$note_data->post_content.'</p>';
								echo '</blockquote>';
								echo '</div>';
								if( $obj_hospital->role == 'administrator' || (  $obj_hospital->role == 'doctor'&& $note_data->post_author ==  get_current_user_id()))
								{
								echo '<div class="col-md-1 ">';
								echo '<a class="btn-delete-note badge badge-delete" href="#" id="notex-'.$notepost_id->post_id.'" noteid='.$notepost_id->post_id.'>X</a>';
								echo '</div>';
								}
								}
								
							 }?>
							</div>
							
			    </div>
						<?php if($obj_hospital->role == 'doctor' || $obj_hospital->role == 'administrator'){?>
							<div class="panel-body">
								<form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
									<div class="form-group">
										<label class="col-sm-4 control-label" for="medicine_name"><?php _e('Add Note','hospital_mgt');?><span class="require-field">*</span></label>
										<div class="col-sm-4">
											<input id="doctor_note_text" class="form-control text-input" type="text" name="doctor_note">
											<input type="hidden" value="<?php if(isset($patient_id))echo $patient_id;?>" name="patient_id" id="patient_id">
											<input type="hidden" value="<?php echo get_current_user_id();?>" name="docotr_id" id="docotr_id">
											
										</div>
										<div class="col-sm-4">
											<input type="button" value="<?php _e('Save Note','hospital_mgt');?>" name="save_note" class="btn btn-success"  id="btn-add-doctor-note" note_by="<?php echo $obj_hospital->role;?>"/>
										</div>
									</div>
								</form>
	                        </div>	
						<?php }?>
		    </div>
			<div class="tab-pane fade" id="patient_history">
			    <div class="panel panel-white">
						<div class="panel-body patient_viewbox_full">
							<div class="form-group">
								<div class="col-md-10">
									<p>
									<?php 
									$user_info = get_userdata($uid);
									$message=0;
									if (file_exists(HMS_LOG_file)) 
									{
										foreach(file(HMS_LOG_file) as $line)
										{
											
											if (strpos($line, $user_info->display_name) == true)
											{
												$message=$message+1;
												echo "<P>".$line. "<P>";
											}
										}
										if($message < 1)
										{
											_e("No any patient history found",'hospital_mgt');
										}
									}
									else 
									{			
										_e("No any patient history found",'hospital_mgt');
									}
									?>							
									</p>
							    </div>
						    </div>
						</div>
						
			    </div>
		    </div>
		    <div class="tab-pane fade" id="nurse_notes">
			
				<?php MJ_hmgt_add_remove_nurse_notes($uid); ?>
					
			
		    </div>
	    </div>
	<?php 
	die();
}
//------------add remove notes list-------//
function MJ_hmgt_add_remove_nurse_notes($patient_id)
{
	$obj_hospital = new Hospital_Management(get_current_user_id());
	?>

	<div class="panel panel-white">
		<div class="panel-body patient_viewbox">
            <?php 
			$user_object=new Hospital_Management();
			 $patient_note_id=$user_object-> get_nurse_notes($patient_id);
			?>
				<div class="form-group">
					<div class="col-xs-10 nurse_notes">
						<?php foreach($patient_note_id as $notepost_id){
						$note_data= get_post($notepost_id->post_id);
						if($note_data->post_type=='nurse_notes' || $note_data->post_type=='administrator_notes'){
						echo '<div class="col-md-10 ">';
						echo '<blockquote id="note-'.$notepost_id->post_id.'">';
						echo  '<b><h4>';
					
						$nurse=get_userdata($note_data->post_author);
						
						 echo __('Notes by','hospital_mgt')." ".$nurse->display_name." on ".     date(MJ_hmgt_date_formate().' H:i:s',strtotime($note_data->post_date)).'</h4></b>';
						echo '<p>'.$note_data->post_content.'</p>';
						echo '</blockquote>';
						echo '</div>';
						if(  $obj_hospital->role == 'administrator' || ( $obj_hospital->role == 'nurse' && $note_data->post_author ==  get_current_user_id()))
						{
						echo '<div class="col-md-1 ">';
						echo '<a class="btn-delete-note badge badge-delete" href="#" id="notex-'.$notepost_id->post_id.'" noteid='.$notepost_id->post_id.'>X</a>';
						echo '</div>';
						}
						}
						
					 }?>
				    </div>
				</div>
		</div>	
						
		<?php if($obj_hospital->role == 'nurse' || $obj_hospital->role == 'administrator'){?>
		<div class="panel-body">
				<form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="medicine_name"><?php _e('Add Note','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-4">
							<input id="nurse_note_text" class="form-control text-input" type="text" name="nurse_note">
							<input type="hidden" value="<?php echo $patient_id;?>" name="patient_id" id="patient_id">
							<input type="hidden" value="<?php echo get_current_user_id();?>" name="nurse_id" id="nurse_id">
							
						</div>
						<div class="col-sm-4">
							<input type="button" value="<?php _e('Save Note','hospital_mgt');?>" name="save_note" class="btn btn-success"  id="btn-add-note" note_by="<?php echo $obj_hospital->role;?>"/>
						</div>
				    </div>
  	            </form>
	    </div>	
						<?php }?>
	</div>
<?php }
//-----------End list of notes----------//
//---------Add nurse notes-------------------------//
function MJ_hmgt_add_nurse_notes() 
{
	global $wpdb;
	
	$array_var = array();
	if($_REQUEST['note_by']=="doctor" || $_REQUEST['note_by']=="administrator")
		$data['note'] = $_REQUEST['doctor_note'];
	if($_REQUEST['note_by']=="nurse" || $_REQUEST['note_by']=="administrator")
		$data['note'] = $_REQUEST['nurse_note'];
	$data['note_by'] = 'nurse';
	$data['patient_id'] = $_REQUEST['patient_id'];
	
	if(!empty($data))
	{
		$obj_hospital = new Hospital_Management();
		$obj_hospital->hmgt_add_nurse_note($data);
		$id = $wpdb->insert_id;
	}
	$nurse=get_userdata(get_current_user_id());
	 $row='<div class="col-md-10 "><blockquote id="note-'.$id.'"><b>'.__('Notes by','hospital_mgt').' '.$nurse->display_name.' on '.MJ_hmgt_hmgtConvertTime(date("Y-m-d H:i:s")).'</b><p>'.$data['note'].'</p></blockquote></div><div class="col-md-1 "><a class="btn-delete-note badge badge-delete" href="#" id="notex-'.$id.'" noteid='.$id.'>X</a></div>';
	
	$array_var[] = $row;
	echo json_encode($array_var);
	die();
}
// ADD DOCTOR NOTES //
function MJ_hmgt_add_doctor_notes()
{
	
	$array_var = array();
	if($_REQUEST['note_by']=="doctor" || $_REQUEST['note_by']=="administrator")
		$data['note'] = $_REQUEST['doctor_note'];
	
	$data['note_by'] = 'doctor';
	$data['patient_id'] = $_REQUEST['patient_id'];

	if(!empty($data))
	{
		$obj_hospital = new Hospital_Management();

		$obj_hospital->hmgt_add_nurse_note($data);
		$id = $wpdb->insert_id;
	}
	$nurse=get_userdata(get_current_user_id());
	$row='<div class="col-md-10 "><blockquote id="note-'.$id.'"><b>'.__('Notes by','hospital_mgt').' '. $nurse->display_name.' on '.MJ_hmgt_hmgtConvertTime(date("Y-m-d H:i:s")).'</b><p>'.$data['note'].'</p></blockquote></div><div class="col-md-1 "><a class="btn-delete-note badge badge-delete" href="#" id="notex-'.$id.'" noteid='.$id.'>X</a></div>'; 
	
	$array_var[] = $row;
	echo json_encode($array_var);
	die();
}
// REMOVE NURSE NOTES //
function MJ_hmgt_remove_nurse_note()
{
	$obj_hospital = new Hospital_Management();
		$obj_hospital->delete_nurse_note($_POST['note_id']);
		die();
}
//--------patient charges *-------------------------//
function MJ_hmgt_get_patient_cherges($patient_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_charges';
	
	$charges_data=$wpdb->get_results("SELECT * FROM $table_name where patient_id='$patient_id'");
	
	return $charges_data;
}
//--------patient transactions-------------------------//
function MJ_hmgt_get_patient_transactions($patient_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'hmgt_patient_transation';
	
	$charges_data=$wpdb->get_results("SELECT * FROM $table_name where patient_id='$patient_id'");
	
	return $charges_data;
}
// PATIETN CHARGES VIEW //
function MJ_hmgt_patient_charges_view()
{
	 $uid=$_REQUEST['idtest'];
	
	?>
	<div class="modal-header"> <a href="#" class="close-btn-cat badge badge-danger pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title"><?php 
			$user=$user_info = get_userdata($uid);
			echo $user->display_name;
			?>
		</h4>
	</div>
	<hr>
	<div class="panel panel-white">
		<div class="panel-body patient_viewbox_full">
			<div class="form-group">
				<div class="col-md-10">
					<p>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th><?php _e('Date','hospital_mgt');?></th>
										<th><?php _e('Charge Name','hospital_mgt');?></th>
										<th><?php _e('Charges','hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
									</tr>
								</thead>
								<tbody>
								<?php									
									$patient_transactions_data=MJ_hmgt_get_patient_transactions($uid);
									
									foreach($patient_transactions_data as $retrieved_data)
									{ ?>
										<tr>
										<td><?php echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->date)); ?></td>
										<td><?php echo $retrieved_data->type; ?></td>
										<td><?php echo $retrieved_data->type_value; ?></td>
										</tr>
									<?php 
									} 
									?>
								</tbody>
							</table>
						</div>
					</p>
				</div>
			</div>
		</div>
	   </div>
    </div>
	<?php
	die();
}
//-----------view patient invoice----------------- //
function MJ_hmgt_get_invoice_data($invoice_id)
{
	global $wpdb;
		$table_invoice= $wpdb->prefix. 'hmgt_invoice';
		$result = $wpdb->get_row("SELECT *FROM $table_invoice where invoice_id = ".$invoice_id);
		return $result;
}
// PATIENT INVOICE VIEW
function MJ_hmgt_patient_invoice_view()
{
	$obj_invoice= new MJ_hmgt_invoice();
	
	if($_POST['invoice_type']=='invoice')
	{
		$invoice_data=MJ_hmgt_get_invoice_data($_POST['idtest']);	
	}
	if($_POST['invoice_type']=='income')
	{
		$income_data=$obj_invoice->hmgt_get_income_data($_POST['idtest']);
	}
	if($_POST['invoice_type']=='expense')
	{
		$expense_data=$obj_invoice->hmgt_get_income_data($_POST['idtest']);
	}
	?>
		<div class="modal-header">
			<a href="#" class="close-btn-cat badge badge-danger pull-right">X</a>			
			<h4 class="modal-title"><?php echo get_option('hmgt_hospital_name','hospital_mgt'); ?></h4>
		</div>
	
	<!--rinkal invoice work-->
		<div class="modal-body1">
			<img class=" invoiceimage"  src="<?php echo plugins_url('/hospital-management/assets/images/invoice.jpg'); ?>" width="100%">
			<div class="main_div" id="invoice_print">			
				<div class="width_100" border="0">
					<div class="row">
						<div class="col-md-1 col-sm-2 col-xs-3">
							<div class="width_1">
								<img class="system_logo"  src="<?php echo get_option( 'hmgt_hospital_logo' ); ?>">
							</div>
						</div>						
						<div class="col-md-11 col-sm-10 col-xs-9 invoice_address invoice_address_css">	
							<div class="row">	
								<div class="col-md-1 col-sm-2 col-xs-3 address_css" style="padding-right:0px;">	
								<b><?php _e('Address :','hospital_mgt');?>
								<?php
								$address_length=strlen(get_option( 'hmgt_hospital_address' ));
								if($address_length>120)
								{
								?>
								<BR><BR><BR><BR><BR>
								<?php
								}
								elseif($address_length>90)
								{													
								?>
									<BR><BR><BR><BR>
								<?php												
								}
								elseif($address_length>60)
								{?>
									<BR><BR><BR>
								<?php
								}
								elseif($address_length>30)
								{?>
									<BR><BR>
								<?php
								}
								?>
								</b>
								</div>
								<div class="col-md-9 col-sm-8 col-xs-7">									
									<?php
										echo chunk_split(get_option( 'hmgt_hospital_address' ),30,"<BR>").""; 
									?>																
								</div>
							</div>
							<div class="row">	
								<div class="col-md-1 col-sm-2 col-xs-3 address_css" style="padding-right:0px;">	
									<b><?php _e('Email :','hospital_mgt');?></b>
								</div>
								<div class="col-md-10 col-sm-8 col-xs-7">					
									<?php echo get_option( 'hmgt_email' )."<br>";  ?>
								</div>																			
							</div>																			
							<div class="row">	
								<div class="col-md-1 col-sm-2 col-xs-3 address_css" style="padding-right:0px;">
									<b><?php _e('Phone :','hospital_mgt');?></b>
								</div>
								<div class="col-md-10 col-sm-8 col-xs-7">				
									<?php echo get_option( 'hmgt_contact_number' )."<br>";  ?>
								</div>																			
							</div>	
								<div align="right" class="width_24">
								</div>									
						</div>				
					</div>
				</div>
		<div class="col-md-12 col-sm-12 col-xl-12">
		<div class="row">
				<div class="width_50a1">
							
							<div class="col-md-1 col-sm-2 col-xs-3" style="padding: 0px";>
							<div class="billed_to">								
								<h3 class="billed_to_lable disply_name_margin_top_28" ><?php _e('| Bill To.','hospital_mgt');?> </h3>
							</div>
							</div>
						 <div class="col-md-8 col-sm-6 col-xs-5" style="padding: 0px";>
							<div class="width_60b2">								
							<?php 	
							if(!empty($expense_data))
							{
								$party_name=$expense_data->party_name; 
								echo "<h3 class='display_name disply_name_margin_top_28'>".chunk_split(ucwords($party_name),30,"<BR>"). "</h3>";
							}
							else
							{
								if(!empty($income_data))
									$patiet_id=$income_data->party_name;
								 if(!empty($invoice_data))
									$patiet_id=$invoice_data->patient_id;
								$patient=get_userdata($patiet_id);							
								echo "<h3 class='display_name disply_name_margin_top_28'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";
								$address=get_user_meta( $patiet_id,'address',true );
								echo chunk_split($address,30,"<BR>"); 
								echo get_user_meta( $patiet_id,'city_name',true ).","; 
								echo get_user_meta( $patiet_id,'zip_code',true )."<br>"; 								
								echo get_user_meta( $patiet_id,'mobile',true )."<br>"; 
							}		
							?>	
							
							</div>
						</div>
						<?php	
					if(!empty($income_data))
					{
						$issue_date=$income_data->income_create_date;
						$payment_status=$income_data->payment_status;
					}
					if(!empty($invoice_data))
					{
						$invoice_no=$invoice_data->invoice_number;			
						$issue_date=$invoice_data->invoice_create_date;
						$payment_status=$invoice_data->status;	
					}
					if(!empty($expense_data))
					{
						$issue_date=$expense_data->income_create_date;
						$payment_status=$expense_data->payment_status;
					}				
					?>
				<div class="col-md-3 col-sm-4 col-xs-7";>
					<div class="width_50a1112">
						<div class="width_20c" align="center">
						<?php
						if(!empty($invoice_data))
						{
						?>
							<h3 class="invoice_lable"><span style="font-size: 12px;"><?php echo __('INVOICE','hospital_mgt')?> #<br></span><span style="font-size: 18px;"><?php echo $invoice_no;?></span></h3>
						<?php
						}
						?>
							<h5 class="align_left"> <?php   echo __('Date','hospital_mgt')." : ".date(MJ_hmgt_date_formate(),strtotime($issue_date)); ?></h5>
							<h5 class="align_left"><?php echo __('Status','hospital_mgt')." : ". __($payment_status,'hospital_mgt');?>
							</h5>														
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<!--end rinkal-->	
				<?php
				if(!empty($income_data) || !empty($expense_data))
				{
				?>
				<table class="width_100">	
					<tbody>		
						<tr>
							<td>						
								<h3 class="entry_lable">								
								<?php 
								if(!empty($income_data))
								{
									_e('Income Entries','hospital_mgt');
								}
								elseif(!empty($expense_data))
								{
									_e('Expense Entries','hospital_mgt');
								}									
								?>
								</h3>
							</td>
						</tr>	
					</tbody>	
				</table>
				<table class="table table-bordered table_row_color" border="1">
					<thead class="entry_heading">					
							<tr>
								<th class="color_white align_center">#</th>
								<th class="color_white align_center"> <?php _e('Date','hospital_mgt');?></th>
								<th class="color_white"><?php _e('Entry','hospital_mgt');?> </th>
								<th class="color_white align_right"><?php _e('Amount','hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
							</tr>						
					</thead>
					<tbody>
					<?php 
						$id=1;
						$total_amount=0;
						if(!empty($income_data) || !empty($expense_data))
						{
							if(!empty($expense_data))
								$income_data=$expense_data;
							
							/* $patient_all_income=$obj_invoice->get_onepatient_income_data($income_data->party_name);
							
							foreach($patient_all_income as $result_income)
							{ */
								$income_entries=json_decode($income_data->income_entry);
								foreach($income_entries as $each_entry)
								{
									$total_amount+=$each_entry->amount;								
									?>
									<tr class="entry_list">
										<td class="align_center"><?php echo $id;?></td>
										<td class="align_center">
										<?php echo date(MJ_hmgt_date_formate(),strtotime($income_data->income_create_date));?>
										</td>
										<td><?php echo $each_entry->entry; ?> </td>
										<td class="align_right">
										<?php
										print number_format($each_entry->amount,2); 
										?></td>							
									</tr>
									<?php 
									$id+=1;
								}
							//}
						}						
						?>
					</tbody>
				</table>
				<?php 
				}
				if($_REQUEST['invoice_type']=='invoice') 
				{
				?>					
					<table class="width_100">	
						<tbody>		
							<tr>
								<td>						
									<h3 class="entry_lable"><?php _e('Patient Transaction','hospital_mgt'); ?></h3>
								</td>
							</tr>	
						</tbody>	
					</table>
					<div class="patient_transaction_div">
					<table class="table table-bordered table_row_color" border="1">
						<thead class="entry_heading">					
								<tr>
									<th class="color_white align_center">#</th>
									<th class="color_white align_center"> <?php _e('Type','hospital_mgt');?></th>
									<th class="color_white align_center"><?php _e('Title','hospital_mgt');?></th>
									<th class="color_white align_center"><?php _e('Date','hospital_mgt');?></th>
									<th class="color_white align_center"><?php _e('Unit','hospital_mgt');?></th>
									<th class="color_white align_center"><?php  _e('Amount','hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
									<th class="color_white align_center"><?php  _e('Discount Amount','hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
									<th class="color_white align_center"><?php  _e('Tax Amount','hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
									<th class="color_white align_center"><?php  _e('Total Amount','hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
									<th class="color_white align_center"><?php  _e('Action','hospital_mgt');?></th>
								</tr>						
						</thead>
						<tbody>
						<?php 
								global $wpdb;
								$obj_trtarment = new MJ_hmgt_treatment();	
								$invoice_id = $_POST['idtest'];
								$table = $wpdb->prefix.'hmgt_patient_transation';
								$sql = "SELECT * FROM $table where invoice_id = ".$invoice_id;
								$transactiondata = $wpdb->get_results($sql);
								
								$total_amount=0.00;
								$total_discount=0.00;
								$total_tax=0.00;
								
								$i = 1;
								foreach($transactiondata as $kay=>$value)
								{									
									if($value->type == "Treatment Fees")
									{
										$type_title=$value->type;
										$title = $obj_trtarment->get_treatment_name($value->type_id);
									}
									elseif($value->type=="Operation Charges")
									{
										$type_title=$value->type;
										$obj_operation = new MJ_hmgt_operation;
										$opedata = $obj_operation->get_single_operation($value->type_id);
										if(!empty($opedata))
										{	
											$title=$obj_operation->get_operation_name($opedata->operation_title);
										}
										else
										{
											$title=$obj_operation->get_operation_name($value->type_id);
										}
									}
									elseif($value->type == "Bed Charges")
									{
										$type_title=$value->type;
										$obj_bed = new MJ_hmgt_bedmanage();
										$title ="Bed ".$obj_bed->get_bed_number($value->type_id);
									}
									elseif($value->type == "Instrument Charges")
									{
										$type_title=$value->type;
										$obj_instrument = new MJ_hmgt_Instrumentmanage();
										$instrumentdata=$obj_instrument->get_single_instrument($value->type_id); 
										$title =$instrumentdata->instrument_name;
									}
									elseif($value->type == "Ambulance Charges")
									{
										$type_title=$value->type;
										$obj_ambulance = new MJ_Hmgt_ambulance();
										$ambulancedata=$obj_ambulance->get_single_ambulance($value->type_id); 
										$title =$ambulancedata->ambulance_id;
									}
									elseif($value->type == "Blood Charges")
									{	
										$type_title=$value->type;
										$title =$value->type_id;
									}
									elseif($value->type == "Dignosis Report Charges")
									{
										$report_type_array=explode(",",$value->type_id);
										$report_name=array();
										if(!empty($report_type_array))
										{	
											foreach($report_type_array as $report_id)
											{	
												$post = get_post( $report_id );
												$report_title=$post->post_title;
												$report_title_array=json_decode($report_title);						
												$report_name[]= $report_title_array->category_name;	
											}
										}	
										$type_title=$value->type; 
										$title =implode(',',$report_name);
									}
									elseif($value->type == "Medicine Charges")
									{	
										$type_title=$value->type;
										$title =$value->type_id;
									}
									elseif($value->type == "Doctor Fees" || $value->type == "Nurse Charges")
									{	
										$type_title=$value->type;
										$first_name = get_user_meta($value->type_id,'first_name',true);
										$last_name = get_user_meta($value->type_id,'last_name',true);		
										$title = $first_name .' ' .$last_name;
									}
									else
									{	
										$title_data = get_post($value->type);
		
										$charge_data=$title_data->post_title;
										$charge_type_array=json_decode($charge_data);
										
										$type_title=$charge_type_array->category_name;
										$title = $type_title;
									}
									?>				
									<tr>
										<td class="align_center"><?php print $i; ?></td>										
										
										<td class="align_center"><?php print $type_title;?> </td>
										
										<td class="align_center"><?php print $title; ?> </td>
										<td class="align_center"><?php print date (MJ_hmgt_date_formate(),strtotime($value->date)); ?></td>
										<td class="align_center"><?php print $value->unit; ?> </td>
										<td class="align_center"><?php
										print number_format($value->type_value,2); ?></td>
										<td class="align_center"><?php
										print number_format($value->type_discount,2); ?></td>
										<td class="align_center"><?php
										print number_format($value->type_tax,2); ?></td>	
										<td class="align_center"><?php
										if(!empty($value->type_total_value))
										{
											print number_format($value->type_total_value,2);
										}	
										else
										{
											$type_total_value=$value->type_value-$value->type_discount+$value->type_tax;
											echo number_format($type_total_value,2);
										}
										
										?></td>	
										<td class="align_center" style="padding:0px;">
										<?php
										if($value->type == "Medicine Charges")
										{
										?>
											<a href="?page=hmgt_medicine&dispatchmedicinepdf=dispatchmedicinepdf&dispatch_medicine_id=<?php echo $value->refer_id;?>" target="_blank" class="btn btn-primary"> <?php _e('View','hospital_mgt');?></a>
										<?php
										}
										?>	</td>										
									</tr>						
							<?php  
								$total_amount+=$value->type_value;
								$total_discount+=$value->type_discount;
								$total_tax+=$value->type_tax;
							$i++; 
							}
							?>
						</tbody>
					</table>
					</div>
				<?php 
				}
				?>
			    <!--rinkal invoice work-->					
					<div class="width_100" border="0">
						<div class="row">				
							<?php
							if(!empty($invoice_data))
							{
								$grand_total = $total_amount + $total_tax - $total_discount - $invoice_data->adjustment_amount-$invoice_data->paid_amount;	
							?>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
									<div class="align_right col-md-10 col-sm-8 col-xs-6" style="padding-right:0px;"><h4 class="margin text-right"><?php _e('Subtotal Amount:','hospital_mgt'); ?></h4></div>
									<div class="align_right col-md-2 col-sm-4 col-xs-6" style="padding-right:5px;padding-left:0px;"><h4 class="margin text-right"><?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($total_amount,2);?></h4></div>
								</div>							
							</div>							
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
									<div class="align_right col-md-10 col-sm-8 col-xs-6" style="padding-right:0px;"><h4 class="margin text-right"><?php _e('Discount Amount:','hospital_mgt'); ?></h4></div>
									<div class="align_right col-md-2 col-sm-4 col-xs-6" style="padding-right:5px;padding-left:0px;"><h4 class="margin text-right"><?php echo "-". "<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($total_discount,2);?></h4></div>									
								</div>							
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
									<div class="align_right col-md-10 col-sm-8 col-xs-6" style="padding-right:0px;"><h4 class="margin text-right"><?php _e('Adjustment Amount:','hospital_mgt');?></h4></div>
									<div class="align_right col-md-2 col-sm-4 col-xs-6" style="padding-right:5px;padding-left:0px;"><h4 class="margin text-right">
										<?php
										if(empty($invoice_data->adjustment_amount))
										{
											echo  "-"."<span>".MJ_hmgt_get_currency_symbol()."</span> "."0.00";
										} 
										else 
										{
											echo  "-"."<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($invoice_data->adjustment_amount,2);
										}
										?></h4></div>									
								</div>								
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
									<div class="align_right col-md-10 col-sm-8 col-xs-6" style="padding-right:0px;"><h4 class="margin text-right"><?php _e('Tax Amount:','hospital_mgt');?></h4></div>
									<div class="align_right col-md-2 col-sm-4 col-xs-6" style="padding-right:5px;padding-left:0px;"><h4 class="margin text-right"><?php
										echo  "+"."<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($total_tax,2);
										?></h4></div>										
								</div>							
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
									<div class="align_right col-md-10 col-sm-8 col-xs-6" style="padding-right:0px;"><h4 class="margin text-right"><?php _e('Paid Amount:','hospital_mgt');?></h4></div>
									<div class="align_right col-md-2 col-sm-4 col-xs-6" style="padding-right:5px;padding-left:0px;"><h4 class="margin text-right"><?php echo "-"."<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($invoice_data->paid_amount,2);?></h4></div>
					    		</div>								
							</div>
							<?php
							}
							if(!empty($income_data))
							{
								$grand_total=$total_amount;
							}
							?>	
								<div class="col-md-12 col-sm-12 col-xs-12 view_invoice_lable_css">
									<div class="align_right col-md-7 col-sm-4 col-xs-0" style="padding-right:0px;"><h3 class="padding color_white margin"></h3></div>
									<div class="align_right col-md-3 col-sm-4 col-xs-6 grand_total_lable view_invoice_lable padding_11" style="padding-right:0px;padding-left:0px;"><h3 class="padding color_white margin"><?php _e('Grand Total :','hospital_mgt');?></h3></div>
									<div class="align_right col-md-2 col-sm-4 col-xs-6 grand_total_lable view_invoice_lable padding_11" style="padding-right:5px;padding-left:5px;"><h3 class="margin text-right color_white"><?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($grand_total,2); ?></h3></div>
								</div>
							</div>
						</div>
					</div>		
			</div>
				<div class="col-md-12 print-button pull-left">
					<a  href="?page=invoice&print=print&invoice_id=<?php echo $_POST['idtest'];?>&invoice_type=<?php echo $_POST['invoice_type'];?>" target="_blank"class="btn entry_heading color_white"><?php _e('Print','hospital_mgt');?></a>
			<?php if($_REQUEST['invoice_type']=='invoice'){?>
			 <a href="?page=invoinvoiceice&invoicepdf=invoicepdf&invoice_id=<?php echo $_POST['idtest'];?>&invoice_type=<?php echo $_POST['invoice_type'];?>" target="_blank" class="btn entry_heading color_white"><?php _e('PDF','hospital_mgt');?></a>
	           <?php } ?>
				</div>
		</div>
	<?php die();
}
// PATIENT INVOICE PRINT HTML //
function MJ_hmgt_patient_invoice_print($invoice_id)
{
	$obj_invoice= new MJ_hmgt_invoice();
	if($_REQUEST['invoice_type']=='invoice')
	{
		$invoice_data=MJ_hmgt_get_invoice_data($invoice_id);
	}
	if($_REQUEST['invoice_type']=='income')
	{
		$income_data=$obj_invoice->hmgt_get_income_data($invoice_id);
	}
	if($_REQUEST['invoice_type']=='expense')
	{
		$expense_data=$obj_invoice->hmgt_get_income_data($invoice_id);
	}
	echo '<link rel="stylesheet" type = "text/css" href="'.plugins_url( '/assets/css/custom.css', __FILE__).'"></link>';	
	
			if (is_rtl())
			{
			 ?>
			 <link rel="stylesheet" type = "text/css"	href="<?php echo HMS_PLUGIN_URL.'/assets/css/bootstrap.min.css'; ?>"/>					 	
			<?php
			}				
			?>
			<div class="modal-header">			
					<h4 class="modal-title"><?php echo get_option('hmgt_hospital_name');?></h4>
				</div>		
			<div class="modal-body">
				<img class="invoicefont1"  src="<?php echo plugins_url('/hospital-management/assets/images/invoice.jpg'); ?>" width="100%">
				<div class="main_div print_css_heading" id="invoice_print"> 
						
				<table class="width_100" border="0">			
						<tbody>
							<tr>
								<td class="width_1">
									<img class="system_logo"  src="<?php echo get_option( 'hmgt_hospital_logo' ); ?>">
								</td>							
								<td class="width_40">
								<table border="0">					
									<tbody>
										<tr>																	
											<td style="">
												<b><?php _e('Address :','hospital_mgt');?></b>
												<?php
												$address_length=strlen(get_option( 'hmgt_hospital_address' ));
												if($address_length>120)
												{
												?>
												<BR><BR><BR><BR><BR>
												<?php
												}
												elseif($address_length>90)
												{?>
												<BR><BR><BR><BR>
												<?php
												}
												elseif($address_length>60)
												{?>
												<BR><BR><BR>
												<?php	
												}
												elseif($address_length>30)
												{
												?>
												<BR><BR>
												<?php
												}
												?>
											</td>	
											<td class="padding_left_5">
												<?php echo chunk_split(get_option( 'hmgt_hospital_address' ),30,"<BR>").""; ?>
											</td>											
										</tr>
										<tr>																	
											<td>
												<b><?php _e('Email :','hospital_mgt');?></b>
											</td>	
											<td class="padding_left_5">
												<?php echo get_option( 'hmgt_email' )."<br>"; ?>
											</td>	
										</tr>
										<tr>																	
											<td>
												<b><?php _e('Phone :','hospital_mgt');?></b>
											</td>	
											<td class="padding_left_5">
												<?php echo get_option( 'hmgt_contact_number' )."<br>";  ?>
											</td>											
										</tr>
									</tbody>
								</table>							
							</td>
							<td align="right" class="width_24">
							</td>
							</tr>
						</tbody>
					</table>
					
					<table class="width_50" border="0">
					<tbody>				
						<tr>
							<td colspan="2" class="billed_to_print" align="center">								
								<h3 class="billed_to_lable disply_name_margin_top_28" ><?php _e('| Bill To.','hospital_mgt');?> </h3>
							</td>
							<td class="width_60">								
							<?php 	
							if(!empty($expense_data))
							{
								$party_name=$expense_data->party_name; 
								echo "<h3 class='display_name disply_name_margin_top_28'>".chunk_split(ucwords($party_name),30,"<BR>"). "</h3>";
							}
							else
							{
								if(!empty($income_data))
									$patiet_id=$income_data->party_name;
								 if(!empty($invoice_data))
									$patiet_id=$invoice_data->patient_id;
								$patient=get_userdata($patiet_id);							
								echo "<h3 class='display_name disply_name_margin_top_28'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";
								$address=get_user_meta( $patiet_id,'address',true );
								echo chunk_split($address,30,"<BR>"); 
								echo get_user_meta( $patiet_id,'city_name',true ).","; 
								echo get_user_meta( $patiet_id,'zip_code',true )."<br>"; 								
								echo get_user_meta( $patiet_id,'mobile',true )."<br>"; 
							}		
							?>			
							</td>
						</tr>									
					</tbody>
				</table>
					<?php 					
					if(!empty($income_data))
					{
						$issue_date=$income_data->income_create_date;
						$payment_status=$income_data->payment_status;
					}
					if(!empty($invoice_data))
					{
						$invoice_no=$invoice_data->invoice_number;			
						$issue_date=$invoice_data->invoice_create_date;
						$payment_status=$invoice_data->status;	
					}
					if(!empty($expense_data))
					{
						$issue_date=$expense_data->income_create_date;
						$payment_status=$expense_data->payment_status;
					}				
					?>
					<table class="width_50" border="0">
						<tbody>				
							<tr>	
								<td class="width_30">
								</td>
								<td class="width_20" align="center">
								<?php
								if(!empty($invoice_data))
								{
								?>
									<h3 class="invoice_lable invoice_color"><span style="font-size: 12px;"><?php echo __('INVOICE','hospital_mgt')?> #<br></span><span style="font-size: 18px;"><?php echo $invoice_no;?></span></h3>
								<?php
								}
								?>
									<h5 class="font_weight align_left"> <?php   echo __('Date','hospital_mgt')." : ".date(MJ_hmgt_date_formate(),strtotime($issue_date)); ?></h5>
									<h5 class="font_weight align_left"><?php echo __('Status','hospital_mgt')." : ". __($payment_status,'hospital_mgt');?></h5>									
								</td>							
							</tr>									
						</tbody>
					</table>
					<?php
					if(!empty($income_data) || !empty($expense_data))
					{
					?>
					<table class="width_100">	
					<tbody>		
						<tr>
							<td>						
								<h3 class="entry_lable">
								<?php 
								if(!empty($income_data))
								{
									_e('Income Entries','hospital_mgt');
								}
								elseif(!empty($expense_data))
								{
									_e('Expense Entries','hospital_mgt');
								}									
								?>
								</h3>
							</td>
						</tr>	
					</tbody>	
				</table>
				<table class="table table-bordered width_100 margin_bottom_10 table_row_color print_table_border" border="1">
					<thead class="entry_heading entry_heading_print">					
							<tr>
								<th class="color_white align_center">#</th>
								<th class="color_white align_center"> <?php _e('Date','hospital_mgt');?></th>
								<th class="color_white"><?php _e('Entry','hospital_mgt');?> </th>
								<th class="color_white align_right"><?php _e('Amount','hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
							</tr>						
					</thead>
					<tbody>
					<?php 
						$id=1;
						$total_amount=0;
						if(!empty($income_data) || !empty($expense_data))
						{
							if(!empty($expense_data))
								$income_data=$expense_data;
							
							/* $patient_all_income=$obj_invoice->get_onepatient_income_data($income_data->party_name);
							foreach($patient_all_income as $result_income)
							{ */
								$income_entries=json_decode($income_data->income_entry);
								foreach($income_entries as $each_entry)
								{
									$total_amount+=$each_entry->amount;								
									?>
									<tr class="entry_list">
										<td class="align_center"><?php echo $id;?></td>
										<td class="align_center"><?php echo date(MJ_hmgt_date_formate(),strtotime($income_data->income_create_date));?></td>
										<td><?php echo $each_entry->entry; ?> </td>
										<td class="align_right"> 
										<?php 
										print number_format($each_entry->amount,2);
										?></td>							
									</tr>
										<?php 
									$id+=1;
								}
							//}
						}						
						?>
					</tbody>
				</table>
				<?php 
				}	
				if($_REQUEST['invoice_type']=='invoice') 
				{
				?>					
					<table class="width_100">	
						<tbody>		
							<tr>
								<td>						
									<h3 class="entry_lable"><?php _e('Patient Transaction','hospital_mgt'); ?></h3>
								</td>
							</tr>	
						</tbody>	
					</table>
					<table class="table table-bordered width_100 margin_bottom_10 table_row_color print_table_border" border="1">
						<thead class="entry_heading entry_heading_print">					
								<tr>
									<th class="color_white align_center">#</th>
									<th class="color_white align_center"> <?php _e('Type','hospital_mgt');?></th>
									<th class="color_white"><?php _e('Title','hospital_mgt');?></th>
									<th class="color_white align_center"><?php _e('Date','hospital_mgt');?></th>
									<th class="color_white align_center"><?php _e('Unit','hospital_mgt');?></th>
									<th class="color_white align_center"><?php _e('Amount','hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
									<th class="color_white align_center"><?php _e('Discount Amount','hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
									<th class="color_white align_center"><?php _e('Tax Amount','hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
									<th class="color_white align_right"><?php _e('Total Amount','hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								</tr>						
						</thead>
						<tbody>
						<?php 
								global $wpdb;
								$obj_trtarment = new MJ_hmgt_treatment();	
								
								$table = $wpdb->prefix.'hmgt_patient_transation';
								$sql = "SELECT * FROM $table where invoice_id = ".$invoice_id;
								$transactiondata = $wpdb->get_results($sql);
								$i = 1;
								$total_amount=0.00;
								$total_discount=0.00;
								$total_tax=0.00;
								foreach($transactiondata as $kay=>$value)
								{									
									if($value->type == "Treatment Fees")
									{
										$type_title=$value->type;
										$title = $obj_trtarment->get_treatment_name($value->type_id);
									}
									elseif($value->type=="Operation Charges")
									{
										$type_title=$value->type;
										$obj_operation = new MJ_hmgt_operation;
										$opedata = $obj_operation->get_single_operation($value->type_id);
										if(!empty($opedata))
										{	
											$title=$obj_operation->get_operation_name($opedata->operation_title);
										}
										else
										{
											$title=$obj_operation->get_operation_name($value->type_id);
										}
									}
									elseif($value->type == "Bed Charges")
									{
										$type_title=$value->type;
										$obj_bed = new MJ_hmgt_bedmanage();
										$title ="Bed ".$obj_bed->get_bed_number($value->type_id);
									}
									elseif($value->type == "Instrument Charges")
									{
										$type_title=$value->type;
										$obj_instrument = new MJ_hmgt_Instrumentmanage();
										$instrumentdata=$obj_instrument->get_single_instrument($value->type_id); 
										$title =$instrumentdata->instrument_name;
									}
									elseif($value->type == "Ambulance Charges")
									{
										$type_title=$value->type;
										$obj_ambulance = new MJ_Hmgt_ambulance();
										$ambulancedata=$obj_ambulance->get_single_ambulance($value->type_id); 
										$title =$ambulancedata->ambulance_id;
									}
									elseif($value->type == "Blood Charges")
									{	
										$type_title=$value->type;
										$title =$value->type_id;
									}
									elseif($value->type == "Dignosis Report Charges")
									{
										$report_type_array=explode(",",$value->type_id);
										$report_name=array();
										if(!empty($report_type_array))
										{	
											foreach($report_type_array as $report_id)
											{	
												$post = get_post( $report_id );
												$report_title=$post->post_title;
												$report_title_array=json_decode($report_title);						
												$report_name[]= $report_title_array->category_name;	
											}
										}	
										$type_title=$value->type; 
										$title =implode(',',$report_name);
									}
									elseif($value->type == "Medicine Charges")
									{	
										$type_title=$value->type;
										$title =$value->type_id;
									}
									elseif($value->type == "Doctor Fees" || $value->type == "Nurse Charges")
									{	
										$type_title=$value->type;
										$first_name = get_user_meta($value->type_id,'first_name',true);
										$last_name = get_user_meta($value->type_id,'last_name',true);		
										$title = $first_name .' ' .$last_name;
									}
									else
									{	
										$title_data = get_post($value->type);
		
										$charge_data=$title_data->post_title;
										$charge_type_array=json_decode($charge_data);
										
										$type_title=$charge_type_array->category_name;
										$title = $type_title;									
									}
									?>				
									<tr>
										<td class="align_center"><?php print $i; ?></td>
										<td class="align_center"><?php print $type_title; ?> </td>
										<td class="align_center"><?php print $title; ?> </td>
										<td class="align_center"><?php print date (MJ_hmgt_date_formate(),strtotime($value->date)); ?></td>
										<td class="align_center"><?php print $value->unit; ?> </td>
										<td class="align_center">
										<?php 
										print number_format($value->type_value,2); 
										?></td>	
										<td class="align_center">
										<?php 
										print number_format($value->type_discount,2); 
										?></td>	
										<td class="align_center">
										<?php 
										print number_format($value->type_tax,2); 
										?></td>	
										<td class="align_right">
										<?php 
										if(!empty($value->type_total_value))
										{
											print number_format($value->type_total_value,2); 
										}	
										else
										{
											$type_total_value=$value->type_value-$value->type_discount+$value->type_tax;
											echo number_format($type_total_value,2);
										}
										
										?></td>							
									</tr>						
							<?php  
							$total_amount+=$value->type_value;
							$total_discount+=$value->type_discount;
							$total_tax+=$value->type_tax;
							$i++; 
							}
							?>
						</tbody>
					</table>
				<?php 
				}
				?>

					<table class="width_100" border="0">
						<tbody>							
							<?php
							if(!empty($invoice_data))
							{								
								if(empty($invoice_data->adjustment_amount))
								{
									$adjustment_amount=0;
								}
								else
								{
									$adjustment_amount=$invoice_data->adjustment_amount;
								}
								
								if(empty($invoice_data->paid_amount))
								{
									$paid_amount=0;
								}
								else
								{
									$paid_amount=$invoice_data->paid_amount;
								}
								$grand_total = $total_amount + $total_tax - $total_discount - $adjustment_amount - $paid_amount;								
							?>
							<tr>
								<td class="width_80 align_right"><h4 class="margin"><?php _e('Subtotal Amount:','hospital_mgt');?></h4></td>
								<td class="align_right amount_padding_5"> <h4 class="margin"><?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($total_amount,2);?></h4></td>		
							</tr>							
							<tr>
								<td class="width_80 align_right"><h4 class="margin"><?php _e('Discount Amount:','hospital_mgt');?></h4></td>

								<td class="align_right amount_padding_5"> <h4 class="margin">							
									<?php									
									echo  "-"."<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($total_discount,2);									
									?>
								</h4></td>	
							</tr>
							<tr>
								<td class="width_80 align_right"><h4 class="margin"><?php _e('Adjustment Amount:','hospital_mgt');?></h4></td>
								<td class="align_right amount_padding_5"> 
									<h4 class="margin">
									<?php
									if(empty($invoice_data->adjustment_amount))
									{
										echo  "-"."<span>".MJ_hmgt_get_currency_symbol()."</span> "."0.00";
									} 
									else 
									{
										echo  "-"."<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($invoice_data->adjustment_amount,2);
									}
									?>
									</h4>
								</td>	
							</tr>
							<tr>
								<td class="width_80 align_right"><h4 class="margin"><?php _e('Tax Amount:','hospital_mgt');?></h4></td>
								<td class="align_right amount_padding_5"> 
									<h4 class="margin">
									<?php									
										echo  "+"."<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($total_tax,2);									
									?>
									</h4>
								</td>	
							</tr>							
							<tr>
								<td class="width_80 align_right"><h4 class="margin"><?php _e('Paid Amount:','hospital_mgt');?></h4></td>
								<td class="align_right amount_padding_5"> 
									<h4 class="margin">
										<?php
										echo  "-"."<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($invoice_data->paid_amount,2);								
									?>
									</h4>
								</td>	
							</tr>
							<?php
							}
							if(!empty($income_data))
							{
								$grand_total=$total_amount;
							}
							?>								
							<tr>
								<td class="align_right grand_total_lable grand_total_lable1 padding_11"><h3 class="padding color_white margin"><?php _e('Grand Total :','hospital_mgt');?></h3></td>
								<td class="grand_amount_width_20 align_right grand_total_amount grand_total_amount1 amount_padding_5"><h3 class="padding grand_total_amount1 color_white margin"><?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($grand_total,2); ?></h3></td>
							</tr>
						</tbody>
					</table>					
				</div>
				
			</div>
	
	<?php die();
}
// INVOICE Payment Receipt PRINT //
function MJ_hmgt_invoice_payment_receipt($invoice_id)
{
	$obj_invoice= new MJ_hmgt_invoice();
	
	$invoice_data=MJ_hmgt_get_invoice_data($invoice_id);
	
	echo '<link rel="stylesheet" type = "text/css" href="'.plugins_url( '/assets/css/custom.css', __FILE__).'"></link>';	
	?>
		<?php
			if (is_rtl())
			 {
			 ?>
			 <link rel="stylesheet" type = "text/css"	href="<?php echo HMS_PLUGIN_URL.'/assets/css/bootstrap.min.css'; ?>"/>	
			
			<?php
			}
			?>
			<div class="modal-header" id="invoice_print">			
					<h4 class="modal-title"><?php echo get_option('hmgt_hospital_name');?></h4>
					<h3 class="modal-title align_center invoice_payment_receipt"><?php _e('Invoice Payment Receipt','hospital_mgt');?></h3>
				</div>		
			<div class="modal-body">				
				<div class="main_div print_css_heading" id="invoice_print"> 
						
				<table class="width_100" style="padding-top:0px;" border="0">			
						<tbody>
							<tr>
								<td class="width_1">
									<img class="system_logo"  src="<?php echo get_option( 'hmgt_hospital_logo' ); ?>">
								</td>							
								<td class="width_40">
								<table border="0">					
									<tbody>
										<tr>																	
											<td style="">
												<b><?php _e('Address :','hospital_mgt');?></b>
												<?php
												$address_length=strlen(get_option( 'hmgt_hospital_address' ));
												if($address_length>120)
												{
												?>
												<BR><BR><BR><BR><BR>
												<?php
												}
												elseif($address_length>90)
												{?>
												<BR><BR><BR><BR>
												<?php
												}
												elseif($address_length>60)
												{?>
												<BR><BR><BR>
												<?php	
												}
												elseif($address_length>30)
												{
												?>
												<BR><BR>
												<?php
												}
												?>
											</td>	
											<td class="padding_left_5">
												<?php echo chunk_split(get_option( 'hmgt_hospital_address' ),30,"<BR>").""; ?>
											</td>											
										</tr>
										<tr>																	
											<td>
												<b><?php _e('Email :','hospital_mgt');?></b>
											</td>	
											<td class="padding_left_5">
												<?php echo get_option( 'hmgt_email' )."<br>"; ?>
											</td>	
										</tr>
										<tr>																	
											<td>
												<b><?php _e('Phone :','hospital_mgt');?></b>
											</td>	
											<td class="padding_left_5">
												<?php echo get_option( 'hmgt_contact_number' )."<br>";  ?>
											</td>											
										</tr>
									</tbody>
								</table>							
							</td>
							<td align="right" class="width_24">
							</td>
							</tr>
						</tbody>
					</table>
					
					<table class="width_50" border="0">
					<tbody>				
						<tr>
							<td colspan="2" class="billed_to_print" align="center">								
								<h3 class="billed_to_lable disply_name_margin_top_28" style="font-size: 14px;"><?php _e('Payment By:','hospital_mgt');?> </h3>
							</td>
							<td class="width_60">								
							<?php								 
								$patiet_id=$invoice_data->patient_id;
								$patient=get_userdata($patiet_id);							
								echo "<h3 class='display_name disply_name_margin_top_28'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";
								$address=get_user_meta( $patiet_id,'address',true );
								echo chunk_split($address,30,"<BR>"); 
								echo get_user_meta( $patiet_id,'city_name',true ).","; 
								echo get_user_meta( $patiet_id,'zip_code',true )."<br>"; 								
								echo get_user_meta( $patiet_id,'mobile',true )."<br>"; 								
							?>			
							</td>
						</tr>									
					</tbody>
				</table>
					<?php 				
					$invoice_no=$invoice_data->invoice_number;			
					$issue_date=$invoice_data->invoice_create_date;
					$payment_status=$invoice_data->status;									
					?>
					<table class="width_50" border="0">
						<tbody>				
							<tr>	
								<td class="width_30">
								</td>
								<td class="width_20" align="center">
								<?php
								if(!empty($invoice_data))
								{
								?>
									<h3 class="invoice_lable invoice_color"><span style="font-size: 12px;"><?php echo __('INVOICE','hospital_mgt')?> #<br></span><span style="font-size: 18px;"><?php echo $invoice_no;?></span></h3>
								<?php
								}
								?>
									<h5 class="font_weight align_left"> <?php   echo __('Date','hospital_mgt')." : ".date(MJ_hmgt_date_formate(),strtotime($issue_date)); ?></h5>
									<h5 class="font_weight align_left"><?php echo __('Status','hospital_mgt')." : ". __($payment_status,'hospital_mgt');?></h5>									
								</td>							
							</tr>									
						</tbody>
					</table>															
					<table class="width_100">	
						<tbody>		
							<tr>
								<td>						
									<h3 class="entry_lable"><?php _e('Payment History','hospital_mgt'); ?></h3>
								</td>
							</tr>	
						</tbody>	
					</table>
					<table class="table table-bordered width_100 margin_bottom_10 table_row_color print_table_border" border="1">
						<thead class="entry_heading entry_heading_print">					
								<tr>
									<th class="color_white align_center">#</th>									
									<th class="color_white align_center"><?php _e('Date','hospital_mgt');?></th>
									<th class="color_white align_center"><?php _e('Payment Method','hospital_mgt');?></th>
									<th class="color_white align_right"><?php _e('Amount','hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								</tr>						
						</thead>
						<tbody>
						<?php 
							global $wpdb;
							$table_income=$wpdb->prefix.'hmgt_income_expense';
							$sql = "SELECT * FROM $table_income where invoice_id = ".$invoice_id;
							$payment_history = $wpdb->get_results($sql);
								$i = 1;
								$grand_total = 0;
								foreach($payment_history as $kay=>$value)
								{		
										$income_entry=json_decode($value->income_entry);
										if(!empty($income_entry))
										{	
											foreach($income_entry as $data)
											{
												$income_amount=$data->amount;
											}
										}								
									?>				
									<tr>
										<td class="align_center"><?php print $i; ?></td>										
										<td class="align_center"><?php print date(MJ_hmgt_date_formate(),strtotime($value->income_create_date)); ?></td>
										<td class="align_center"><?php print $value->payment_method; ?> </td>
										<td class="align_right">
										<?php 
										print number_format($income_amount,2); 
										?></td>							
									</tr>						
							<?php 
							$grand_total=$grand_total+$income_amount;
							$i++; 
							}
							?>
						</tbody>
					</table>				
					<table class="width_100" border="0">
						<tbody>															
							<tr>
								<td class="align_right grand_total_lable grand_total_lable1 padding_11"><h3 class="padding color_white margin"><?php _e('Grand Total :','hospital_mgt');?></h3></td>
								<td class="grand_amount_width_20 width_20_payment_receipt align_right grand_total_amount grand_total_amount1 amount_padding_5"><h3 class="padding grand_total_amount1 color_white margin"><?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span> ".number_format($grand_total,2); ?></h3></td>
							</tr>
						</tbody>
					</table>					
				</div>
				
			</div>
	
	<?php die();
}
// INVOICE FUNCTION CALL ON INIT ACTION //
function MJ_hmgt_print_init()
{
	if (is_user_logged_in ()) 
	{
		if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'print' && $_REQUEST['page'] == 'invoice')
		{
			?>
		<script>window.onload = function(){ window.print(); };</script>
		<?php 
		MJ_hmgt_patient_invoice_print($_REQUEST['invoice_id']);
		exit;
		}
		if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'print' && $_REQUEST['page'] == 'hmgt_prescription')
		{ ?>
		<script>window.onload = function(){ window.print(); };</script>
		<?php 
			MJ_hmgt_print_priscriptionl($_REQUEST['prescription_id']);
			exit;
		}
		if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'print' && $_REQUEST['page'] == 'payment_receipt')
		{ ?>
		<script>window.onload = function(){ window.print(); };</script>
		<?php 
			MJ_hmgt_invoice_payment_receipt($_REQUEST['invoice_id']);
			exit;
		}
	}
	
}
add_action('init','MJ_hmgt_print_init');
// pdf function call on init //
function MJ_hmgt_pdf_init()
{
	if (is_user_logged_in ()) 
	{
		if(isset($_REQUEST['invoicepdf']) && $_REQUEST['invoicepdf'] == 'invoicepdf')
		{			
			MJ_hmgt_invoice_pdf($_REQUEST['invoice_id'],'invoice');
			exit;
		}
		if(isset($_REQUEST['prescriptionpdf']) && $_REQUEST['prescriptionpdf'] == 'prescriptionpdf')
		{
			MJ_hmgt_perscription_pdf($_REQUEST['prescription_id']);
			exit;
		}
		if(isset($_REQUEST['dispatchmedicinepdf']) && $_REQUEST['dispatchmedicinepdf'] == 'dispatchmedicinepdf')
		{
			MJ_hmgt_dispatch_medicine_pdf($_REQUEST['dispatch_medicine_id']);
			exit;
		}
	}
}
add_action('init','MJ_hmgt_pdf_init');

//Medicine Bill pdf //
function MJ_hmgt_dispatch_medicine_pdf($dispatch_medicine_id)
{ 
	error_reporting(0);
	$obj_medicine = new MJ_hmgt_medicine();
	
	$dispatch_medicine_data=$obj_medicine->get_single_dispatch_medicine($dispatch_medicine_id);
	
     echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap.min.css', __FILE__).'"></link>';
     echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap.min.js', __FILE__).'"></script>'; 
    ob_clean();
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="Medicine Bill.pdf"');
	header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    require HMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';	
	$stylesheet = file_get_contents(HMS_PLUGIN_DIR. '/assets/css/custom.css'); // Get css content
    $mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 5 , 0 , 0 , 0); 
	$mpdf->debug = true;
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style></style>');
	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');		
	$mpdf->SetTitle('Medicine Bill');	
	$mpdf->WriteHTML('<div class="modal-header">');
		$mpdf->WriteHTML('<h4 class="modal-title">'.get_option('hmgt_hospital_name').'</h4>');
	$mpdf->WriteHTML('</div>');
	$mpdf->WriteHTML('<img class="invoicefont1 img_padding_right_pdf" src="'.plugins_url('/hospital-management/assets/images/invoice.jpg').'" width="100%">');

     $mpdf->WriteHTML('<div class="main_div_pdf" id="invoice_print">');	
		$mpdf->WriteHTML('<table class="width_100_print" border="0">');					
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="width_1_print">');
								$mpdf->WriteHTML('<img class="system_logo system_logo_print"  src="'.get_option( 'hmgt_hospital_logo' ).'">');
							$mpdf->WriteHTML('</td>');							
							$mpdf->WriteHTML('<td class="only_width_20_print">');	
								$mpdf->WriteHTML('<table border="0">');					
								$mpdf->WriteHTML('<tbody>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td>');
											$mpdf->WriteHTML('<b class="font_family">'.__('Address ','hospital_mgt').':</b>');
											 $address_length=strlen(get_option( 'hmgt_hospital_address' ));
												if($address_length>120)
												{
												
												$mpdf->WriteHTML('<BR><BR><BR><BR><BR><BR>');
												
												}
												elseif($address_length>90)
												{
													
												$mpdf->WriteHTML('<BR><BR><BR><BR><BR>');
												
												}
												elseif($address_length>60)
												{
												
													$mpdf->WriteHTML('<BR><BR><BR><BR>');
														
												}
												elseif($address_length>30)
												{
												
												$mpdf->WriteHTML('<BR><BR><BR>');
												
												}
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.chunk_split(get_option( 'hmgt_hospital_address' ),30,"<BR>").'');
										$mpdf->WriteHTML('</td>');											
									$mpdf->WriteHTML('</tr>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td><b class="font_family">'.__('Email ','hospital_mgt').' :</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.get_option( 'hmgt_email' )."<br>".'');
										$mpdf->WriteHTML('</td>');	
									$mpdf->WriteHTML('</tr>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td>');
											$mpdf->WriteHTML('<b class="font_family">'.__('Phone ','hospital_mgt').' :</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.get_option( 'hmgt_contact_number' )."<br>".'');
										$mpdf->WriteHTML('</td>');											
									$mpdf->WriteHTML('</tr>');
								$mpdf->WriteHTML('</tbody>');
							$mpdf->WriteHTML('</table>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td align="right" class="width_24">');
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
			$mpdf->WriteHTML('<table>');
			 $mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td>');
				
					$mpdf->WriteHTML('<table class="width_50_print"  border="0">');
						$mpdf->WriteHTML('<tbody>');				
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td colspan="2" class="billed_to_pdf" align="center">');								
								$mpdf->WriteHTML('<h3 class="billed_to_lable font_family"> | '.__('Bill To','hospital_mgt').'. </h3>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_40_print">');	
								
									$patiet_id=$dispatch_medicine_data->patient;
									$patient=get_userdata($patiet_id);		
									
									$address=get_user_meta( $patiet_id,'address',true );
									
									$mpdf->WriteHTML('<h3 class="display_name font_family">'.chunk_split(ucwords($patient->display_name),30,"<BR>").'</h3>'); 																		
									$mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").''); 
									  $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'city_name',true ).','); 
									 $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'zip_code',true ).'<br>'); 
									 $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'mobile',true ).'<br>'); 
								
							 $mpdf->WriteHTML('</td>');
						 $mpdf->WriteHTML('</tr>');									
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');				
				$mpdf->WriteHTML('</td>');	
				$mpdf->WriteHTML('<td>');				
				   $mpdf->WriteHTML('<table class="width_50_print"  border="0">');
					 $mpdf->WriteHTML('<tbody>');										
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_20_print">');
								$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'.__('Date','hospital_mgt').' : '.date(MJ_hmgt_date_formate(),strtotime($dispatch_medicine_data->med_create_date)).'</h5>');																	
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');						
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');	
				$mpdf->WriteHTML('</td>');				
			  $mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>'); 
			
		$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Medicine Details','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');
	
		$mpdf->WriteHTML('<table class="table table-borderedtable_row_color" class="width_93" border="1">');
		$mpdf->WriteHTML('<thead>');
		$mpdf->WriteHTML('<tr>');
	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Medicine Name','hospital_mgt').'</th>');			
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Mfg','hospital_mgt').'</th>');	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Batch','hospital_mgt').'</th>');								
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Exp.','hospital_mgt').'</th>');	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Quantity','hospital_mgt').'</th>');	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Discount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Tax','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Total Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');		
		$mpdf->WriteHTML('</tr>');
        $mpdf->WriteHTML('</thead>');
        $mpdf->WriteHTML('<tbody>');
		
		$i = 1;
		$medication = json_decode($dispatch_medicine_data->madicine);
		
		foreach($medication as $key=>$val)
		{
			$singgle_madicine = $obj_medicine->get_single_medicine($val->madicine_id);
			
			$bg_color = $i % 2 === 0 ? "#cad5f5" : "white";
			$mpdf->WriteHTML('<tr style="background-color: '. $bg_color .';">');
						
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$singgle_madicine->medicine_name.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$singgle_madicine->medicine_menufacture.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$singgle_madicine->batch_number.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$singgle_madicine->medicine_expiry_date.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$val->qty.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			if(!empty($val->price))
			{
				$mpdf->WriteHTML(''.number_format((float)$val->price,2,'.','').'');
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			if(!empty($val->discount_amount))
			{
				$mpdf->WriteHTML(''.number_format((float)$val->discount_amount,2,'.','').'');
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			if(!empty($val->tax_amount))
			{
				$mpdf->WriteHTML(''.number_format((float)$val->tax_amount,2,'.','').'');
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$final_price =$val->price-$val->discount_amount+$val->tax_amount;
			
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($final_price))
			{
				$mpdf->WriteHTML(''.number_format((float)$final_price,2,'.','').'');
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('</tr>');
			
			$i++; 
		}
		$mpdf->WriteHTML('<tr>');
			$mpdf->WriteHTML('<td colspan="5" class="align_left table_td_font padding_10_pdf">');
			$mpdf->WriteHTML('<b>'.__('Total','hospital_mgt').'</b>');			
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			if(!empty($dispatch_medicine_data->med_price))
			{
				$mpdf->WriteHTML(''.number_format((float)$dispatch_medicine_data->med_price,2,'.','').'');	
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			if(!empty($dispatch_medicine_data->discount))
			{
				$mpdf->WriteHTML(''.number_format((float)$dispatch_medicine_data->discount,2,'.','').'');	
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			if(!empty($dispatch_medicine_data->total_tax_amount))
			{
				$mpdf->WriteHTML(''.number_format((float)$dispatch_medicine_data->total_tax_amount,2,'.','').'');	
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			$total_final_price=$dispatch_medicine_data->med_price-$dispatch_medicine_data->discount+$dispatch_medicine_data->total_tax_amount;
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($total_final_price))
			{
				$mpdf->WriteHTML(''.number_format((float)$total_final_price,2,'.','').'');
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
		$mpdf->WriteHTML('</tr>');
        $mpdf->WriteHTML('</tbody>');
        $mpdf->WriteHTML('</table>');
		
		$mpdf->WriteHTML('<table class="width_97" style="margin-left:50%;" border="0">');
		$mpdf->WriteHTML('<tbody>');
			
		$grand_total = $total_final_price;
		
		$mpdf->WriteHTML('<tr>');
			$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Sub Total Amount','hospital_mgt').':</h4></td>');
			if(empty($dispatch_medicine_data->med_price))
			{				
				$mpdf->WriteHTML('<td class="align_right" style="padding-right:6px;"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span> 0.00</h4></td>');	
									
			}
			else
			{
				$mpdf->WriteHTML('<td class="align_right" style="padding-right:6px;"> <h4 class="margin h4_pdf"><span style="">'.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$dispatch_medicine_data->med_price,2,'.','').'</h4></td>');
			}
		$mpdf->WriteHTML('</tr>');			
		$mpdf->WriteHTML('<tr>');
			$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Discount Amount','hospital_mgt').':</h4></td>');
			if(empty($dispatch_medicine_data->discount))
			{				
				$mpdf->WriteHTML('<td class="align_right" style="padding-right:6px;"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span> 0.00</h4></td>');	
									
			}
			else
			{
				$mpdf->WriteHTML('<td class="align_right" style="padding-right:6px;"> <h4 class="margin h4_pdf"><span style="">- '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$dispatch_medicine_data->discount,2,'.','').'</h4></td>');	
			}			
		$mpdf->WriteHTML('</tr>');
				
		$mpdf->WriteHTML('<tr>');
		$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Tax Amount','hospital_mgt').':</h4></td>');			
		if(empty($dispatch_medicine_data->total_tax_amount))
		{				
			$mpdf->WriteHTML('<td class="align_right" style="padding-right:6px;"> <h4 class="margin h4_pdf"><span style="">+ '.MJ_hmgt_get_currency_symbol().'</span> 0.00</h4></td>');	
								
		}
		else
		{
			$mpdf->WriteHTML('<td class="align_right" style="padding-right:6px;"> <h4 class="margin h4_pdf"><span style="">+ '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$dispatch_medicine_data->total_tax_amount,2,'.','').'</h4></td>');	
		}
		$mpdf->WriteHTML('</tr>');
			
	    $mpdf->WriteHTML('<tr>');		
			$mpdf->WriteHTML('<td style="margin-left:55px;" class="align_right grand_total_lable font_family padding_11"><h3 class="color_white margin font_family">'.__('Net Payable Amount','hospital_mgt').':</h3></td>');
			
			$mpdf->WriteHTML('<td class="align_right grand_total_amount" style="padding-right:6px;"><h3 class="color_white margin"> <span style="">'.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$grand_total,2,'.','').'</h3></td>');		
			
		$mpdf->WriteHTML('</tr>');
		$mpdf->WriteHTML('</tbody>');
		$mpdf->WriteHTML('</table>'); 	
		$mpdf->WriteHTML('</div>');
		$mpdf->WriteHTML("</body>");
		$mpdf->WriteHTML("</html>");	
		$mpdf->Output();	
	ob_end_flush();
	unset($mpdf);	
}
//patient invoice pdf //
function MJ_hmgt_invoice_pdf($invoice_id,$type)
{ 
	
	error_reporting(0);
	$obj_invoice= new MJ_hmgt_invoice();
	
	if($type == 'invoice')
	{
		$invoice_data=MJ_hmgt_get_invoice_data($invoice_id);	
	}
	if($type == 'income')
	{
		$income_data=$obj_invoice->hmgt_get_income_data($invoice_id);	
	}
	
	if($type == 'expense')
	{
		$expense_data=$obj_invoice->hmgt_get_income_data($invoice_id);
	}
     echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap.min.css', __FILE__).'"></link>';
     echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap.min.js', __FILE__).'"></script>'; 
    ob_clean();
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="invoice.pdf"');
	header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    require HMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';	
	$stylesheet = file_get_contents(HMS_PLUGIN_DIR. '/assets/css/custom.css'); // Get css content
    $mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 5 , 0 , 0 , 0); 
	$mpdf->debug = true;
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style></style>');
	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');		
	$mpdf->SetTitle('Invoice');	
	$mpdf->WriteHTML('<div class="modal-header">');
		$mpdf->WriteHTML('<h4 class="modal-title">'.get_option('hmgt_hospital_name').'</h4>');
	$mpdf->WriteHTML('</div>');
	$mpdf->WriteHTML('<img class="invoicefont1 img_padding_right_pdf" src="'.plugins_url('/hospital-management/assets/images/invoice.jpg').'" width="100%">');

     $mpdf->WriteHTML('<div class="main_div_pdf" id="invoice_print">');	
		$mpdf->WriteHTML('<table class="width_100_print" border="0">');					
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="width_1_print">');
								$mpdf->WriteHTML('<img class="system_logo system_logo_print"  src="'.get_option( 'hmgt_hospital_logo' ).'">');
							$mpdf->WriteHTML('</td>');							
							$mpdf->WriteHTML('<td class="only_width_20_print">');	
								$mpdf->WriteHTML('<table border="0">');					
								$mpdf->WriteHTML('<tbody>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td>');
											$mpdf->WriteHTML('<b class="font_family">'.__('Address ','hospital_mgt').':</b>');
											 $address_length=strlen(get_option( 'hmgt_hospital_address' ));
												if($address_length>120)
												{												
													$mpdf->WriteHTML('<BR><BR><BR><BR><BR><BR>');
												}
												elseif($address_length>90)
												{	
											    	$mpdf->WriteHTML('<BR><BR><BR><BR><BR>');
												}
												elseif($address_length>60)
												{
													$mpdf->WriteHTML('<BR><BR><BR><BR>');
												}
												elseif($address_length>30)
												{
												
												$mpdf->WriteHTML('<BR><BR><BR>');
												
												}
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.chunk_split(get_option( 'hmgt_hospital_address' ),30,"<BR>").'');
										$mpdf->WriteHTML('</td>');											
									$mpdf->WriteHTML('</tr>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td><b class="font_family">'.__('Email ','hospital_mgt').' :</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.get_option( 'hmgt_email' )."<br>".'');
										$mpdf->WriteHTML('</td>');	
									$mpdf->WriteHTML('</tr>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td>');
											$mpdf->WriteHTML('<b class="font_family">'.__('Phone ','hospital_mgt').' :</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.get_option( 'hmgt_contact_number' )."<br>".'');
										$mpdf->WriteHTML('</td>');											
									$mpdf->WriteHTML('</tr>');
								$mpdf->WriteHTML('</tbody>');
							$mpdf->WriteHTML('</table>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td align="right" class="width_24">');
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('<table>');
			 $mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td>');
				
					$mpdf->WriteHTML('<table class="width_50_print"  border="0">');
						$mpdf->WriteHTML('<tbody>');				
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td colspan="2" class="billed_to_pdf" align="center">');								
								$mpdf->WriteHTML('<h3 class="billed_to_lable font_family"> | '.__('Bill To','hospital_mgt').'. </h3>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_40_print">');	
								
								if(!empty($expense_data))
								{
									$party_name=$expense_data->party_name; 
									echo "<h3 class='display_name'>".chunk_split(ucwords($party_name),30,"<BR>"). "</h3>";
									$mpdf->WriteHTML('<h3 class="display_name font_family">'.chunk_split(ucwords($party_name),30,"<BR>").'</h3>'); 	
								}
								else
								{
									if(!empty($income_data))
										$patiet_id=$income_data->party_name;
									 if(!empty($invoice_data))
										$patiet_id=$invoice_data->patient_id;
									$patient=get_userdata($patiet_id);		
																
								
									$address=get_user_meta( $patiet_id,'address',true );
									
									$mpdf->WriteHTML('<h3 class="display_name font_family">'.chunk_split(ucwords($patient->display_name),30,"<BR>").'</h3>'); 																		
									$mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").''); 
									  $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'city_name',true ).','); 
									 $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'zip_code',true ).'<br>'); 
									 $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'mobile',true ).'<br>'); 
								}
							 $mpdf->WriteHTML('</td>');
						 $mpdf->WriteHTML('</tr>');									
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td>');
				
				   $mpdf->WriteHTML('<table class="width_50_print"  border="0">');
					 $mpdf->WriteHTML('<tbody>');				
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_20_print invoice_lable align_center">');
								
								//$issue_date='DD-MM-YYYY';
								if(!empty($income_data))
								{ 
								 $issue_date=$income_data->income_create_date;
								 $payment_status=$income_data->payment_status; 
								}
								if(!empty($invoice_data))
								{
									$invoice_no=$invoice_data->invoice_number;
									$issue_date=$invoice_data->invoice_create_date;
									$payment_status=$invoice_data->status;	
								}
								if(!empty($expense_data))
								{
									$issue_date=$expense_data->income_create_date;
									$payment_status=$expense_data->payment_status;
								}		
																
									$mpdf->WriteHTML('<h3 class="invoice_color font_family"><span style="font-size: 12px;">'.__('INVOICE','hospital_mgt').' #<br></span><span style="font-size: 18px;">'.$invoice_no.'</span>');									
																							
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_20_print">');
								$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'.__('Date','hospital_mgt').' : '.date(MJ_hmgt_date_formate(),strtotime($issue_date)).'</h5>');
							$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'.__('Status','hospital_mgt').' :'.__(''.$payment_status.'','hospital_mgt').' </h5>');											
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');						
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');	
				$mpdf->WriteHTML('</td>');
			  $mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>');
		
	if(!empty($income_data) || !empty($expense_data))
	{					
		$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							
							if(!empty($income_data))
							{
								$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Income Entries','hospital_mgt').'</h3>');
							}
							elseif(!empty($expense_data))
							{
								$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Expense Entries','hospital_mgt').'</h3>');
							}	
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');				
			
		$mpdf->WriteHTML('<table class="table table-bordered table_row_color" class="width_93" border="1">');
		$mpdf->WriteHTML('<thead>');
		$mpdf->WriteHTML('<tr>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">#</th>');					
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Date','hospital_mgt').'</th>');					
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Entry','hospital_mgt').'</th>');		
		$mpdf->WriteHTML('<th class="color_white entry_heading align_right padding_10_pdf font_family">'.__('Amount','hospital_mgt').' (<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');		
		$mpdf->WriteHTML('</tr>');
		
        $mpdf->WriteHTML('</thead>');
        $mpdf->WriteHTML('<tbody>');
		
		
		$id=1;
		$total_amount=0;
		if(!empty($income_data) || !empty($expense_data))
		{
			if(!empty($expense_data))
				$income_data=$expense_data;
			
			/* $patient_all_income=$obj_invoice->get_onepatient_income_data($income_data->party_name);
			foreach($patient_all_income as $result_income)
			{ */
				$income_entries=json_decode($income_data->income_entry);
				foreach($income_entries as $each_entry)
				{
					$total_amount+=$each_entry->amount;
					$bg_color = $i % 2 === 0 ? "#cad5f5" : "white";
					$mpdf->WriteHTML('<tr style="background-color: '. $bg_color .';">');
					$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
					$mpdf->WriteHTML(''.$id.'<br>');
					
					$mpdf->WriteHTML('</td>');
					$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
					$mpdf->WriteHTML('' .date(MJ_hmgt_date_formate(),strtotime($income_data->income_create_date)).'');
					$mpdf->WriteHTML('</td>');
					$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
					$mpdf->WriteHTML('' . $each_entry->entry .'');
					$mpdf->WriteHTML('</td>');
					if(!empty($total_amount))
					{
						$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">'.number_format((float)$total_amount,2,'.','').'</td>');
					}
					else
					{
						$mpdf->WriteHTML('0.00');
					}
					//$mpdf->WriteHTML('<td class="text-right">'.$symbol. ''. $total_amount.'</td>');
					$mpdf->WriteHTML('</tr>');
					$id+=1;
				}
			//}
		}	
        $mpdf->WriteHTML('</tbody>');
        $mpdf->WriteHTML('</table>');		
	}
		$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Patient Transaction','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');
	
		$mpdf->WriteHTML('<table class="table table-borderedtable_row_color" class="width_93" border="1">');
		$mpdf->WriteHTML('<thead>');
		$mpdf->WriteHTML('<tr>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">#</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Type','hospital_mgt').'</th>');	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Title','hospital_mgt').'</th>');			
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Date','hospital_mgt').'</th>');								
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Unit','hospital_mgt').'</th>');	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Discount Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Tax Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_right padding_10_pdf font_family">'.__('Total Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');		
		$mpdf->WriteHTML('</tr>');
        $mpdf->WriteHTML('</thead>');
        $mpdf->WriteHTML('<tbody>');
		global $wpdb;
        
		$table = $wpdb->prefix.'hmgt_patient_transation';
		$sql = "SELECT * FROM $table where invoice_id = ".$invoice_id;
		$transactiondata = $wpdb->get_results($sql);
		$i = 1;
		$total_amount=0.00;
		$total_discount=0.00;
		$total_tax=0.00;
		foreach($transactiondata as $kay=>$value)
		{
			if($value->type == "Treatment Fees")
			{
				$type_title=$value->type;
				$obj_trtarment = new MJ_hmgt_treatment();	
				$title = $obj_trtarment->get_treatment_name($value->type_id);
			}
			elseif($value->type=="Operation Charges")
			{
				$type_title=$value->type;
				$obj_operation = new MJ_hmgt_operation;
				$opedata = $obj_operation->get_single_operation($value->type_id);
				if(!empty($opedata))
				{	
					$title=$obj_operation->get_operation_name($opedata->operation_title);
				}
				else
				{
					$title=$obj_operation->get_operation_name($value->type_id);
				}
			}
			elseif($value->type == "Bed Charges")
			{
				$type_title=$value->type;
				$obj_bed = new MJ_hmgt_bedmanage();
				$title ="Bed ".$obj_bed->get_bed_number($value->type_id);
			}
			elseif($value->type == "Instrument Charges")
			{
				$type_title=$value->type;
				$obj_instrument = new MJ_hmgt_Instrumentmanage();
				$instrumentdata=$obj_instrument->get_single_instrument($value->type_id); 
				$title =$instrumentdata->instrument_name;
			}
			elseif($value->type == "Ambulance Charges")
			{
				$type_title=$value->type;
				$obj_ambulance = new MJ_Hmgt_ambulance();
				$ambulancedata=$obj_ambulance->get_single_ambulance($value->type_id); 
				$title =$ambulancedata->ambulance_id;
			}
			elseif($value->type == "Blood Charges")
			{
				$type_title=$value->type;				
				$title =$value->type_id;
			}
			elseif($value->type == "Dignosis Report Charges")
			{
				$report_type_array=explode(",",$value->type_id);
				$report_name=array();
				if(!empty($report_type_array))
				{	
					foreach($report_type_array as $report_id)
					{
						$post = get_post( $report_id );
						$report_title=$post->post_title;
						$report_title_array=json_decode($report_title);						
						$report_name[]= $report_title_array->category_name;	
					}
				}	
				$type_title=$value->type;
				$title =implode(',',$report_name);
			}
			elseif($value->type == "Medicine Charges")
			{	
				$type_title=$value->type;
				$title =$value->type_id;
			}
			elseif($value->type == "Doctor Fees" || $value->type == "Nurse Charges")
			{	
				$type_title=$value->type;
				$first_name = get_user_meta($value->type_id,'first_name',true);
				$last_name = get_user_meta($value->type_id,'last_name',true);		
				$title = $first_name .' ' .$last_name;
			} 
			else
			{	
				$title_data = get_post($value->type);
		
				$charge_data=$title_data->post_title;
				$charge_type_array=json_decode($charge_data);
				
				$type_title=$charge_type_array->category_name;
				$title = $type_title;
				
			}
						
			$bg_color = $i % 2 === 0 ? "#cad5f5" : "white";
			$mpdf->WriteHTML('<tr style="background-color: '. $bg_color .';">');
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$i.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$type_title.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$title.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.date (MJ_hmgt_date_formate(),strtotime($value->date)).'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$value->unit.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($value->type_value))
			{
				$mpdf->WriteHTML(''.number_format((float)$value->type_value,2,'.','').'');
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($value->type_discount))
			{
				$mpdf->WriteHTML(''.number_format((float)$value->type_discount,2,'.','').'');
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($value->type_tax))
			{
				$mpdf->WriteHTML(''.number_format((float)$value->type_tax,2,'.','').'');
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($value->type_total_value))
			{
				$mpdf->WriteHTML(''.number_format((float)$value->type_total_value,2,'.','').'');
			}
			else
			{				
				$type_total_value=$value->type_value-$value->type_discount+$value->type_tax;
				
				$mpdf->WriteHTML(''.number_format((float)$type_total_value,2,'.','').'');
				
			}
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('</tr>');
			
			$total_amount+=$value->type_value;
			$total_discount+=$value->type_discount;
			$total_tax+=$value->type_tax;
		$i++; 
		}
        $mpdf->WriteHTML('</tbody>');
        $mpdf->WriteHTML('</table>');
		$mpdf->WriteHTML('<table class="width_97" style="margin-left:55%;" border="0">');
		$mpdf->WriteHTML('<tbody>');
		
		if(!empty($invoice_data))
		{	
			$grand_total = $total_amount + $total_tax - $total_discount - $invoice_data->adjustment_amount - $invoice_data->paid_amount;
			
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Sub Total Amount','hospital_mgt').':</h4></td>');
				if(empty($total_amount))
				{				
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span> 0.00</h4></td>');	
										
				}
				else
				{
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">'.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$total_amount,2,'.','').'</h4></td>');	
				}	
			$mpdf->WriteHTML('</tr>');			
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Discount Amount','hospital_mgt').':</h4></td>');
				if(empty($total_discount))
				{				
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span> 0.00</h4></td>');	
										
				}
				else
				{
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">- '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$total_discount,2,'.','').'</h4></td>');	
				}
			$mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Adjustment Amount','hospital_mgt').':</h4></td>');			
			if(empty($invoice_data->adjustment_amount))
			{				
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span> 0.00</h4></td>');	
									
			}
			else
			{
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">- '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$invoice_data->adjustment_amount,2,'.','').'</h4></td>');	
			}
			$mpdf->WriteHTML('</tr>');
			
			$mpdf->WriteHTML('<tr>');
			$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Tax Amount','hospital_mgt').':</h4></td>');			
			if(empty($total_tax))
			{				
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span> 0.00</h4></td>');	
									
			}
			else
			{
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">+ '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$total_tax,2,'.','').'</h4></td>');	
			}
			$mpdf->WriteHTML('</tr>');
			
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Paid Amount','hospital_mgt').':</h4></td>');			
				if(empty($invoice_data->paid_amount))
				{				
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span> 0.00</h4></td>');	
										
				}
				else
				{
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">- '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$invoice_data->paid_amount,2,'.','').'</h4></td>');	
				}
			$mpdf->WriteHTML('</tr>');
			
			$mpdf->WriteHTML('<tr>');
			$mpdf->WriteHTML('<td colspan="2">');
		
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('</tr>');
		}
		if(!empty($income_data))
		{
			$grand_total=$total_amount;
		}
	    $mpdf->WriteHTML('<tr>');		
			$mpdf->WriteHTML('<td style="margin-left:50px;" class="align_right grand_total_lable font_family padding_11"><h3 class="color_white margin font_family">'.__('Grand Total ','hospital_mgt').':</h3></td>');
			$mpdf->WriteHTML('<td class="align_right grand_total_amount amount_padding_8"><h3 class="color_white margin"> <span style="">'.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$grand_total,2,'.','').'</h3></td>');		
		$mpdf->WriteHTML('</tr>');
		$mpdf->WriteHTML('</tbody>');
		$mpdf->WriteHTML('</table>');		
		$mpdf->WriteHTML('</div>');
		$mpdf->WriteHTML("</body>");
		$mpdf->WriteHTML("</html>");	
		$mpdf->Output();	
	ob_end_flush();
	unset($mpdf);	
}
//generate pdf for prescription //
function MJ_hmgt_perscription_pdf($perscription_id)
{
     error_reporting(0);
     $obj_medicine = new MJ_hmgt_medicine();
	 $obj_treatment=new MJ_hmgt_treatment();
	 $obj_prescription=new MJ_hmgt_prescription();
	 $result = $obj_prescription->get_prescription_data($perscription_id);
     ob_clean();
     header('Content-type: application/pdf');
     header('Content-Disposition: inline; filename="invoice.pdf"');
     header('Content-Transfer-Encoding: binary');
     header('Accept-Ranges: bytes');	
     require HMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';	
	 $stylesheet = file_get_contents(HMS_PLUGIN_DIR. '/assets/css/custom.css'); // Get css content
    $mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 5 , 0 , 0 , 0); 
	$mpdf->debug = true;
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style></style>');
	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
    $mpdf->SetTitle('Prescription');
	if(get_option('hmgt_enable_hospitalname_in_priscription')=='yes')
	{
		$mpdf->WriteHTML('<div class="modal-header">');
		
		$mpdf->WriteHTML('<h4 class="modal-title">'.get_option('hmgt_hospital_name').'</h4>');
		$mpdf->WriteHTML('</div>');
	} 
		
	$mpdf->WriteHTML('<img class="invoicefont1 img_padding_right_pdf" src="'.plugins_url('/hospital-management/assets/images/invoice.jpg').'" width="100%">');

     $mpdf->WriteHTML('<div class="main_div_pdf" id="invoice_print">');	
		$mpdf->WriteHTML('<table class="width_100_print" border="0">');					
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="width_1_print">');
								$mpdf->WriteHTML('<img class="system_logo system_logo_print"  src="'.get_option( 'hmgt_hospital_logo' ).'">');
							$mpdf->WriteHTML('</td>');							
							$mpdf->WriteHTML('<td class="only_width_20_print">');	
								$mpdf->WriteHTML('<table border="0">');					
								$mpdf->WriteHTML('<tbody>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td>');
											$mpdf->WriteHTML('<b class="font_family">'.__('Address ','hospital_mgt').':</b>');
												
												 $address_length=strlen(get_option( 'hmgt_hospital_address' ));
												if($address_length>120)
												{
												
												$mpdf->WriteHTML('<BR><BR><BR><BR><BR><BR>');
												
												}
												elseif($address_length>90)
												{
													
												$mpdf->WriteHTML('<BR><BR><BR><BR><BR>');
												
												}
												elseif($address_length>60)
												{
												
													$mpdf->WriteHTML('<BR><BR><BR><BR>');
														
												}
												elseif($address_length>30)
												{
												
													$mpdf->WriteHTML('<BR><BR><BR>');
												
												}
												
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.chunk_split(get_option( 'hmgt_hospital_address' ),30,"<BR>").'');
										$mpdf->WriteHTML('</td>');											
									$mpdf->WriteHTML('</tr>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td><b class="font_family">'.__('Email ','hospital_mgt').' :</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.get_option( 'hmgt_email' )."<br>".'');
										$mpdf->WriteHTML('</td>');	
									$mpdf->WriteHTML('</tr>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td>');
											$mpdf->WriteHTML('<b class="font_family">'.__('Phone ','hospital_mgt').' :</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.get_option( 'hmgt_contact_number' )."<br>".'');
										$mpdf->WriteHTML('</td>');											
									$mpdf->WriteHTML('</tr>');
								$mpdf->WriteHTML('</tbody>');
							$mpdf->WriteHTML('</table>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td align="right" class="width_24">');
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('<table>');
			 $mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td>');
				
					$mpdf->WriteHTML('<table class="width_50_print"  border="0">');
						$mpdf->WriteHTML('<tbody>');				
						$mpdf->WriteHTML('<tr>');							
							$mpdf->WriteHTML('<td class="width_40_print" style="padding-left:20px;">');	
							
									$patiet_id=$result->patient_id;
									$patient=get_userdata($patiet_id);		
																
								
									$address=get_user_meta( $patiet_id,'address',true );
									
									$mpdf->WriteHTML('<h3 class="display_name font_family">'.chunk_split(ucwords($patient->display_name),30,"<BR>").'</h3>'); 																		
									$mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").''); 
									  $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'city_name',true ).','); 
									 $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'zip_code',true ).'<br>'); 
									 $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'mobile',true ).'<br>'); 
								
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td colspan="2" class="billed_to_pdf" align="center">');								
								//$mpdf->WriteHTML('<h3 class="billed_to_lable font_family"> | '.__('Bill To','hospital_mgt').'. </h3>');
							$mpdf->WriteHTML('</td>');
						 $mpdf->WriteHTML('</tr>');									
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td>');
				
				   $mpdf->WriteHTML('<table class="width_50_print"  border="0">');
					 $mpdf->WriteHTML('<tbody>');				
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
										
						 $mpdf->WriteHTML('</tr>');
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_20_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_30_print">');
								$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'.__('Date','hospital_mgt').' : '.date(MJ_hmgt_date_formate(),strtotime($result->pris_create_date)).'</h5>');
							
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');						
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');	
				$mpdf->WriteHTML('</td>');
			  $mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>');
		
		
			$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Case History :','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td class="font_12 padding_left_15" style="padding-left:20px;">'.$result->case_history.'</td>');
					$mpdf->WriteHTML('</tr>');
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');
			
			$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Treatment :','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
					$treatment=$obj_treatment->get_treatment_name($result->teratment_id); 
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td class="font_12 padding_left_15" style="padding-left:20px;">'.$treatment.'</td>');
					$mpdf->WriteHTML('</tr>');
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');	
			
		$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Rx :','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');
	
		$mpdf->WriteHTML('<table class="table table-borderedtable_row_color" class="width_93" border="1">');
		$mpdf->WriteHTML('<thead>');
		$mpdf->WriteHTML('<tr>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">#</th>');					
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Medicine Name','hospital_mgt').'</th>');					
		$mpdf->WriteHTML('<th class="color_white entry_heading  align_center padding_10_pdf font_family">'.__('Frequency Of Medicine','hospital_mgt').'</th>');		
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Time Period','hospital_mgt').'</th>');		
		$mpdf->WriteHTML('<th class="color_white entry_heading align_left  padding_10_pdf font_family">'.__('When Take','hospital_mgt').'</th>');				
		$mpdf->WriteHTML('</tr>');
        $mpdf->WriteHTML('</thead>');
        $mpdf->WriteHTML('<tbody>');
		$i=1;
						
		$all_medicine_list=json_decode($result->medication_list);
		if(!empty($all_medicine_list))
		{
			foreach($all_medicine_list as $retrieved_data)
			{
				$bg_color = $i % 2 === 0 ? "#cad5f5" : "white";
				$mpdf->WriteHTML('<tr style="background-color: '. $bg_color .';">');
				$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
				$mpdf->WriteHTML(''.$i.'');
				
				$mpdf->WriteHTML('</td>');
				$medicine=$obj_medicine->get_single_medicine($retrieved_data->medication_name);
							
				$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
				$mpdf->WriteHTML(''.$medicine->medicine_name.'');
				
				$mpdf->WriteHTML('</td>');
						
				$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
				$mpdf->WriteHTML(''.$retrieved_data->time.'');
				
				$mpdf->WriteHTML('</td>');
				
				$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
				$mpdf->WriteHTML(''.$retrieved_data->per_days.' '.$retrieved_data->time_period.'');
				
				$mpdf->WriteHTML('</td>');
				
				$mpdf->WriteHTML('<td class=" table_td_font padding_10_pdf">');
				$mpdf->WriteHTML(''.MJ_hmgt_get_medicine_take_timelabel($retrieved_data->takes_time).'');
				
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('</tr>');
				
				$i++; 
			}
		}	
        $mpdf->WriteHTML('</tbody>');
        $mpdf->WriteHTML('</table>');	
		
		
			$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Extra Note :','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
					
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td class="font_12 padding_left_15" style="padding-left:20px;">'.$result->treatment_note.'</td>');
					$mpdf->WriteHTML('</tr>');
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');	
			
		$all_entry=json_decode($result->custom_field);
		if(!empty($all_entry))
		{
		
			foreach($all_entry as $entry)
			{
				$mpdf->WriteHTML('<table class="width_100_print">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td style="padding-left:20px;">');
								$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.$entry->label.'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
						
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="font_12 padding_left_15" style="padding-left:20px;">'.$entry->value.'</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');		
			}
		}		
		$mpdf->WriteHTML('</div>');

	$mpdf->WriteHTML("</body>");
	$mpdf->WriteHTML("</html>");	
	$mpdf->Output();	
	ob_end_flush();
	unset($mpdf);	
}
// generated invoice end send in mail //
function MJ_hmgt_send_invoice_generate_mail($emails,$subject,$message,$invoice_id)
{	
	error_reporting(0);
	$obj_invoice= new MJ_hmgt_invoice();
	$type="invoice";
	
	$invoice_data=MJ_hmgt_get_invoice_data($invoice_id);	
	
     echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap.min.css', __FILE__).'"></link>';
     echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap.min.js', __FILE__).'"></script>'; 
     ob_clean();
     header('Content-type: application/pdf');
     header('Content-Disposition: inline; filename="invoice.pdf"');
     header('Content-Transfer-Encoding: binary');
     header('Accept-Ranges: bytes');	

	require HMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';	
	$stylesheet = file_get_contents(HMS_PLUGIN_DIR. '/assets/css/custom.css'); // Get css content
    $mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 5 , 0 , 0 , 0); 
	$mpdf->debug = true;
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style></style>');
	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');		
	$mpdf->SetTitle('Invoice');
	$mpdf->WriteHTML('<div class="modal-header">');
		$mpdf->WriteHTML('<h4 class="modal-title">'.get_option('hmgt_hospital_name').'</h4>');
	$mpdf->WriteHTML('</div>');	
	$mpdf->WriteHTML('<img class="invoicefont1 img_padding_right_pdf" src="'.plugins_url('/hospital-management/assets/images/invoice.jpg').'" width="100%">');

     $mpdf->WriteHTML('<div class="main_div_pdf" id="invoice_print">');	
		$mpdf->WriteHTML('<table class="width_100_print" border="0">');					
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="width_1_print">');
								$mpdf->WriteHTML('<img class="system_logo system_logo_print"  src="'.get_option( 'hmgt_hospital_logo' ).'">');
							$mpdf->WriteHTML('</td>');							
							$mpdf->WriteHTML('<td class="only_width_20_print">');	
								$mpdf->WriteHTML('<table border="0">');					
								$mpdf->WriteHTML('<tbody>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td style="padding-bottom: 20px;">');
											$mpdf->WriteHTML('<b class="font_family">'.__('Address ','hospital_mgt').':</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.chunk_split(get_option( 'hmgt_hospital_address' ),30,"<BR>").'');
										$mpdf->WriteHTML('</td>');											
									$mpdf->WriteHTML('</tr>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td><b class="font_family">'.__('Email ','hospital_mgt').' :</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.get_option( 'hmgt_email' )."<br>".'');
										$mpdf->WriteHTML('</td>');	
									$mpdf->WriteHTML('</tr>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td>');
											$mpdf->WriteHTML('<b class="font_family">'.__('Phone ','hospital_mgt').' :</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.get_option( 'hmgt_contact_number' )."<br>".'');
										$mpdf->WriteHTML('</td>');											
									$mpdf->WriteHTML('</tr>');
								$mpdf->WriteHTML('</tbody>');
							$mpdf->WriteHTML('</table>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td align="right" class="width_24">');
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('<table>');
			 $mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td>');
				
					$mpdf->WriteHTML('<table class="width_50_print"  border="0">');
						$mpdf->WriteHTML('<tbody>');				
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td colspan="2" class="billed_to_pdf" align="center">');								
								$mpdf->WriteHTML('<h3 class="billed_to_lable font_family"> | '.__('Bill To','hospital_mgt').'. </h3>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_40_print">');	
								
								if(!empty($expense_data))
								{
									$party_name=$expense_data->party_name; 
									echo "<h3 class='display_name'>".chunk_split(ucwords($party_name),30,"<BR>"). "</h3>";
									$mpdf->WriteHTML('<h3 class="display_name font_family">'.chunk_split(ucwords($party_name),30,"<BR>").'</h3>'); 	
								}
								else
								{
									if(!empty($income_data))
										$patiet_id=$income_data->party_name;
									 if(!empty($invoice_data))
										$patiet_id=$invoice_data->patient_id;
									$patient=get_userdata($patiet_id);		
																
								
									$address=get_user_meta( $patiet_id,'address',true );
									
									$mpdf->WriteHTML('<h3 class="display_name font_family">'.chunk_split(ucwords($patient->display_name),30,"<BR>").'</h3>'); 																		
									$mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").''); 
									  $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'city_name',true ).','); 
									 $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'zip_code',true ).'<br>'); 
									 $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'mobile',true ).'<br>'); 
								}
							 $mpdf->WriteHTML('</td>');
						 $mpdf->WriteHTML('</tr>');									
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td>');
				
				   $mpdf->WriteHTML('<table class="width_50_print"  border="0">');
					 $mpdf->WriteHTML('<tbody>');				
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_20_print invoice_lable align_center">');
								
								//$issue_date='DD-MM-YYYY';
								if(!empty($income_data))
								{ 
								 $issue_date=$income_data->income_create_date;
								 $payment_status=$income_data->payment_status; 
								}
								if(!empty($invoice_data))
								{
									$invoice_no=$invoice_data->invoice_number;
									$issue_date=$invoice_data->invoice_create_date;
									$payment_status=$invoice_data->status;	
								}
								if(!empty($expense_data))
								{
									$issue_date=$expense_data->income_create_date;
									$payment_status=$expense_data->payment_status;
								}		
																
									$mpdf->WriteHTML('<h3 class="invoice_color font_family"><span style="font-size: 12px;">'.__('INVOICE','hospital_mgt').' #<br></span><span style="font-size: 18px;">'.$invoice_no.'</span>');									
																							
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_20_print">');
								$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'.__('Date','hospital_mgt').' : '.date(MJ_hmgt_date_formate(),strtotime($issue_date)).'</h5>');
							$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'.__('Status','hospital_mgt').' :'.__(''.$payment_status.'','hospital_mgt').' </h5>');											
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');						
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');	
				$mpdf->WriteHTML('</td>');
			  $mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>');
			
	if(!empty($income_data) || !empty($expense_data))
	{
		$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');							
							if(!empty($income_data))
							{
								$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Income Entries','hospital_mgt').'</h3>');
							}
							elseif(!empty($expense_data))
							{
								$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Expense Entries','hospital_mgt').'</h3>');
							}	
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');				
			
		$mpdf->WriteHTML('<table class="table table-bordered table_row_color" class="width_93" border="1">');
		$mpdf->WriteHTML('<thead>');
		$mpdf->WriteHTML('<tr>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">#</th>');					
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Date','hospital_mgt').'</th>');					
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Entry','hospital_mgt').'</th>');		
		$mpdf->WriteHTML('<th class="color_white entry_heading align_right padding_10_pdf font_family">'.__('Amount','hospital_mgt').'</th>');		
		$mpdf->WriteHTML('</tr>');
		
        $mpdf->WriteHTML('</thead>');
        $mpdf->WriteHTML('<tbody>');
		
		
		$id=1;
		$total_amount=0;
	if(!empty($income_data) || !empty($expense_data))
	{
		if(!empty($expense_data))
			$income_data=$expense_data;
		
		/* $patient_all_income=$obj_invoice->get_onepatient_income_data($income_data->party_name);
		foreach($patient_all_income as $result_income)
		{ */
			$income_entries=json_decode($income_data->income_entry);
			foreach($income_entries as $each_entry)
			{
				$total_amount+=$each_entry->amount;
				$bg_color = $i % 2 === 0 ? "#cad5f5" : "white";
				$mpdf->WriteHTML('<tr style="background-color: '. $bg_color .';">');
				$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
				$mpdf->WriteHTML(''.$id.'<br>');
				
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
				$mpdf->WriteHTML('' .date(MJ_hmgt_date_formate(),strtotime($income_data->income_create_date)).'');
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
				$mpdf->WriteHTML('' . $each_entry->entry .'');
				$mpdf->WriteHTML('</td>');
				if(!empty($total_amount))
				{
					$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf"><span style="">'.MJ_hmgt_get_currency_symbol().'</span> '. number_format((float)$total_amount,2,'.','').'</td>');
				}
				else
				{
					$mpdf->WriteHTML('0.00');
				}
				$mpdf->WriteHTML('</tr>');
				$id+=1;
			}
		//}
	 }
        $mpdf->WriteHTML('</tbody>');
        $mpdf->WriteHTML('</table>');
	}	
		$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Patient Transaction','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');
	
		$mpdf->WriteHTML('<table class="table table-borderedtable_row_color" class="width_93" border="1">');
		$mpdf->WriteHTML('<thead>');
		$mpdf->WriteHTML('<tr>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">#</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Type','hospital_mgt').'</th>');	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Title','hospital_mgt').'</th>');			
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Date','hospital_mgt').'</th>');								
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Unit','hospital_mgt').'</th>');	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Discount Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Tax Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_right padding_10_pdf font_family">'.__('Total Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');		
		$mpdf->WriteHTML('</tr>');
        $mpdf->WriteHTML('</thead>');
        $mpdf->WriteHTML('<tbody>');
		global $wpdb;
        
		$table = $wpdb->prefix.'hmgt_patient_transation';
		$sql = "SELECT * FROM $table where invoice_id = ".$invoice_id;
		$transactiondata = $wpdb->get_results($sql);
		$i = 1;
		$total_amount = 0.00;
		$total_discount = 0.00;
		$total_tax = 0.00;
		foreach($transactiondata as $kay=>$value)
		{
			if($value->type == "Treatment Fees")
			{
				$type_title=$value->type;
				$obj_trtarment = new MJ_hmgt_treatment();	
				$title = $obj_trtarment->get_treatment_name($value->type_id);
			}
			elseif($value->type=="Operation Charges")
			{
				$type_title=$value->type;
				$obj_operation = new MJ_hmgt_operation;
				$opedata = $obj_operation->get_single_operation($value->type_id);
				if(!empty($opedata))
				{	
					$title=$obj_operation->get_operation_name($opedata->operation_title);
				}
				else
				{
					$title=$obj_operation->get_operation_name($value->type_id);
				}
			}
			elseif($value->type == "Bed Charges")
			{
				$type_title=$value->type;
				$obj_bed = new MJ_hmgt_bedmanage();
				$title ="Bed ".$obj_bed->get_bed_number($value->type_id);
			}
			elseif($value->type == "Instrument Charges")
			{
				$type_title=$value->type;
				$obj_instrument = new MJ_hmgt_Instrumentmanage();
				$instrumentdata=$obj_instrument->get_single_instrument($value->type_id); 
				$title =$instrumentdata->instrument_name;
			}
			elseif($value->type == "Ambulance Charges")
			{
				$type_title=$value->type;
				$obj_ambulance = new MJ_Hmgt_ambulance();
				$ambulancedata=$obj_ambulance->get_single_ambulance($value->type_id); 
				$title =$ambulancedata->ambulance_id;
			}
			elseif($value->type == "Blood Charges")
			{	
				$type_title=$value->type;
				$title =$value->type_id;
			}
			elseif($value->type == "Dignosis Report Charges")
			{
				$report_type_array=explode(",",$value->type_id);
				$report_name=array();
				if(!empty($report_type_array))
				{	
					foreach($report_type_array as $report_id)
					{	
						$post = get_post( $report_id );
						$report_title=$post->post_title;
						$report_title_array=json_decode($report_title);						
						$report_name[]= $report_title_array->category_name;	
					}
				}	
				$type_title=$value->type; 
				$title =implode(',',$report_name);
			}
			elseif($value->type == "Medicine Charges")
			{	
				$type_title=$value->type;
				$title =$value->type_id;
			}
			elseif($value->type == "Doctor Fees" || $value->type == "Nurse Charges")
			{
				$type_title=$value->type;	
				$first_name = get_user_meta($value->type_id,'first_name',true);
				$last_name = get_user_meta($value->type_id,'last_name',true);		
				$title = $first_name .' ' .$last_name;
			}
			else
			{
				$title_data = get_post($value->type);
		
				$charge_data=$title_data->post_title;
				$charge_type_array=json_decode($charge_data);
				
				$type_title=$charge_type_array->category_name;
				$title = $type_title;
			}
			
			$bg_color = $i % 2 === 0 ? "#cad5f5" : "white";
			$mpdf->WriteHTML('<tr style="background-color: '. $bg_color .';">');
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$i.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$type_title.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$title.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.date (MJ_hmgt_date_formate(),strtotime($value->date)).'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$value->unit.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($value->type_value))
			{
				$mpdf->WriteHTML(''.number_format((float)$value->type_value,2,'.','').'');
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($value->type_discount))
			{
				$mpdf->WriteHTML(''.number_format((float)$value->type_discount,2,'.','').'');
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($value->type_tax))
			{
				$mpdf->WriteHTML(''.number_format((float)$value->type_tax,2,'.','').'');
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($value->type_total_value))
			{
				$mpdf->WriteHTML(''.number_format((float)$value->type_total_value,2,'.','').'');
			}
			else
			{				
				$type_total_value=$value->type_value-$value->type_discount+$value->type_tax;
				
				$mpdf->WriteHTML(''.number_format((float)$type_total_value,2,'.','').'');
			}
			$mpdf->WriteHTML('</tr>');
			
			$total_amount+=$value->type_value;
			$total_discount+=$value->type_discount;
			$total_tax+=$value->type_tax;
			$i++; 
		}
        $mpdf->WriteHTML('</tbody>');
        $mpdf->WriteHTML('</table>');
		$mpdf->WriteHTML('<table class="width_97" style="margin-left:55%;" border="0">');
		$mpdf->WriteHTML('<tbody>');
		
		if(!empty($invoice_data))
		{
		   $grand_total = $total_amount + $total_tax - $total_discount - $invoice_data->adjustment_amount - $invoice_data->paid_amount;
			
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Sub Total Amount','hospital_mgt').':</h4></td>');
				if(empty($total_amount))
				{				
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span>0.00</h4></td>');	
				}
				else
				{
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">'.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$total_amount,2,'.','').'</h4></td>');
				}
			$mpdf->WriteHTML('</tr>');			
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Discount Amount','hospital_mgt').':</h4></td>');
				if(empty($total_discount))
				{				
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span>0.00</h4></td>');	
				}
				else
				{
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">- '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$total_discount,2,'.','').'</h4></td>');	
				}
			$mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Adjustment Amount','hospital_mgt').':</h4></td>');			
			if(empty($invoice_data->adjustment_amount))
			{				
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span>0.00</h4></td>');	
			}
			else
			{
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">- '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$invoice_data->adjustment_amount,2,'.','').'</h4></td>');	
			}
			$mpdf->WriteHTML('</tr>');
			
			$mpdf->WriteHTML('<tr>');
			$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Tax Amount','hospital_mgt').':</h4></td>');			
			if(empty($total_tax))
			{				
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span>0.00</h4></td>');	
			}
			else
			{
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">+ '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$total_tax,2,'.','').'</h4></td>');	
			}
			$mpdf->WriteHTML('</tr>');
			
			$mpdf->WriteHTML('<tr>');
			$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Paid Amount',	'hospital_mgt').':</h4></td>');	
			if(empty($invoice_data->paid_amount))
			{				
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span>0.00</h4></td>');	
			}
			else
			{	
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">- '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$invoice_data->paid_amount,2,'.','').'</h4></td>');	
			}		
			$mpdf->WriteHTML('</tr>');
			
			$mpdf->WriteHTML('<tr>');
			$mpdf->WriteHTML('<td colspan="2">');
		
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('</tr>');
		}
		if(!empty($income_data))
		{
		$grand_total=$total_amount;
		}
	    $mpdf->WriteHTML('<tr>');		
			$mpdf->WriteHTML('<td style="margin-left:50px;" class="align_right grand_total_lable font_family padding_11"><h3 class="color_white margin font_family">'.__('Grand Total ','hospital_mgt').':</h3></td>');
			$mpdf->WriteHTML('<td class="align_right grand_total_amount amount_padding_8"><h3 class="color_white margin"><span style="">'.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$grand_total,2,'.','').'</h3></td>');		
		$mpdf->WriteHTML('</tr>');
		$mpdf->WriteHTML('</tbody>');
		$mpdf->WriteHTML('</table>');		
		$mpdf->WriteHTML('</div>');
		$mpdf->WriteHTML("</body>");
		$mpdf->WriteHTML("</html>");	

		  $invoice_dir = WP_CONTENT_DIR ;
		  $invoice_dir .= '/uploads/invoice/';
		  $invoice_path = $invoice_dir;
		  
		  if (!file_exists($invoice_path)) 
		  {
			mkdir($invoice_path, 0777, true);
			$mpdf->Output( WP_CONTENT_DIR . '/uploads/invoice/'.$invoice_id.'.pdf','F');
		  }
		  else
		  {
			   $mpdf->Output( WP_CONTENT_DIR . '/uploads/invoice/'.$invoice_id.'.pdf','F');
		  }	
												
    ob_end_flush();
    unset($mpdf);	
    $hospital_name = get_option('hmgt_hospital_name');
 
	$headers = "From: ".$hospital_name.' <noreplay@gmail.com>' . "\r\n";	
    $mail_attachment = array(WP_CONTENT_DIR . '/uploads/invoice/'.$invoice_id.'.pdf');
	$enable_notofication=get_option('hospital_enable_notifications');
	if($enable_notofication=='yes')
	{
		wp_mail($emails,$subject,$message,$headers,$mail_attachment); 
	}
}
 //send  payment invoice in  mail //
function MJ_hmgt_send_invoice_payment_mail($emails,$subject,$message,$invoice_id)
{	
	error_reporting(0);
	$obj_invoice= new MJ_hmgt_invoice();
	$type="invoice";
	
	$invoice_data=MJ_hmgt_get_invoice_data($invoice_id);	
	
     echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap.min.css', __FILE__).'"></link>';
     echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap.min.js', __FILE__).'"></script>'; 
     ob_clean();
     header('Content-type: application/pdf');
     header('Content-Disposition: inline; filename="invoice.pdf"');
     header('Content-Transfer-Encoding: binary');
     header('Accept-Ranges: bytes');	

	require HMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';	
	$stylesheet = file_get_contents(HMS_PLUGIN_DIR. '/assets/css/custom.css'); // Get css content
    $mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 5 , 0 , 0 , 0); 
	$mpdf->debug = true;
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style></style>');
	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');		
	$mpdf->SetTitle('Invoice');
	$mpdf->WriteHTML('<div class="modal-header">');
		$mpdf->WriteHTML('<h4 class="modal-title">'.get_option('hmgt_hospital_name').'</h4>');
	$mpdf->WriteHTML('</div>');	
	$mpdf->WriteHTML('<img class="invoicefont1 img_padding_right_pdf" src="'.plugins_url('/hospital-management/assets/images/invoice.jpg').'" width="100%">');

     $mpdf->WriteHTML('<div class="main_div_pdf" id="invoice_print">');	
		$mpdf->WriteHTML('<table class="width_100_print" border="0">');					
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="width_1_print">');
								$mpdf->WriteHTML('<img class="system_logo system_logo_print"  src="'.get_option( 'hmgt_hospital_logo' ).'">');
							$mpdf->WriteHTML('</td>');							
							$mpdf->WriteHTML('<td class="only_width_20_print">');	
								$mpdf->WriteHTML('<table border="0">');					
								$mpdf->WriteHTML('<tbody>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td style="padding-bottom: 20px;">');
											$mpdf->WriteHTML('<b class="font_family">'.__('Address ','hospital_mgt').':</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.chunk_split(get_option( 'hmgt_hospital_address' ),30,"<BR>").'');
										$mpdf->WriteHTML('</td>');											
									$mpdf->WriteHTML('</tr>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td><b class="font_family">'.__('Email ','hospital_mgt').' :</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.get_option( 'hmgt_email' )."<br>".'');
										$mpdf->WriteHTML('</td>');	
									$mpdf->WriteHTML('</tr>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td>');
											$mpdf->WriteHTML('<b class="font_family">'.__('Phone ','hospital_mgt').' :</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.get_option( 'hmgt_contact_number' )."<br>".'');
										$mpdf->WriteHTML('</td>');											
									$mpdf->WriteHTML('</tr>');
								$mpdf->WriteHTML('</tbody>');
							$mpdf->WriteHTML('</table>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td align="right" class="width_24">');
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('<table>');
			 $mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td>');
				
					$mpdf->WriteHTML('<table class="width_50_print"  border="0">');
						$mpdf->WriteHTML('<tbody>');				
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td colspan="2" class="billed_to_pdf" align="center">');								
								$mpdf->WriteHTML('<h3 class="billed_to_lable font_family"> | '.__('Bill To','hospital_mgt').'. </h3>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_40_print">');	
																
									$patiet_id=$invoice_data->patient_id;
									$patient=get_userdata($patiet_id);		
									$address=get_user_meta( $patiet_id,'address',true );
									
									$mpdf->WriteHTML('<h3 class="display_name font_family">'.chunk_split(ucwords($patient->display_name),30,"<BR>").'</h3>'); 																		
									$mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").''); 
									  $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'city_name',true ).','); 
									 $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'zip_code',true ).'<br>'); 
									 $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'mobile',true ).'<br>'); 
								
							 $mpdf->WriteHTML('</td>');
						 $mpdf->WriteHTML('</tr>');									
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td>');
				
				   $mpdf->WriteHTML('<table class="width_50_print"  border="0">');
					 $mpdf->WriteHTML('<tbody>');				
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_20_print invoice_lable align_center">');
								
									$invoice_no=$invoice_data->invoice_number;
									$issue_date=$invoice_data->invoice_create_date;
									$payment_status=$invoice_data->status;	
																								
									$mpdf->WriteHTML('<h3 class="invoice_color font_family"><span style="font-size: 12px;">'.__('INVOICE','hospital_mgt').' #<br></span><span style="font-size: 18px;">'.$invoice_no.'</span>');									
																							
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_20_print">');
								$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'.__('Date','hospital_mgt').' : '.date(MJ_hmgt_date_formate(),strtotime($issue_date)).'</h5>');
							$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'.__('Status','hospital_mgt').' :'.__(''.$payment_status.'','hospital_mgt').' </h5>');											
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');						
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');	
				$mpdf->WriteHTML('</td>');
			  $mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>');
		
		$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Patient Transaction','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');
	
		$mpdf->WriteHTML('<table class="table table-borderedtable_row_color" class="width_93" border="1">');
		$mpdf->WriteHTML('<thead>');
		$mpdf->WriteHTML('<tr>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">#</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Type','hospital_mgt').'</th>');	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Title','hospital_mgt').'</th>');			
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Date','hospital_mgt').'</th>');								
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Unit','hospital_mgt').'</th>');	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Discount Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Tax Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_right padding_10_pdf font_family">'.__('Total Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');
		$mpdf->WriteHTML('</tr>');
        $mpdf->WriteHTML('</thead>');
        $mpdf->WriteHTML('<tbody>');
		global $wpdb;
        
		$table = $wpdb->prefix.'hmgt_patient_transation';
		$sql = "SELECT * FROM $table where invoice_id = ".$invoice_id;
		$transactiondata = $wpdb->get_results($sql);
		$i = 1;
		
		$total_amount = 0.00;
		$total_discount = 0.00;
		$total_tax = 0.00;
		
		foreach($transactiondata as $kay=>$value)
		{
			if($value->type == "Treatment Fees")
			{
				$type_title=$value->type;
				$obj_trtarment = new MJ_hmgt_treatment();	
				$title = $obj_trtarment->get_treatment_name($value->type_id);
			}
			elseif($value->type=="Operation Charges")
			{
				$type_title=$value->type;
				$obj_operation = new MJ_hmgt_operation;
				$opedata = $obj_operation->get_single_operation($value->type_id);
				if(!empty($opedata))
				{	
					$title=$obj_operation->get_operation_name($opedata->operation_title);
				}
				else
				{
					$title=$obj_operation->get_operation_name($value->type_id);
				}
			}
			elseif($value->type == "Bed Charges")
			{
				$type_title=$value->type;
				$obj_bed = new MJ_hmgt_bedmanage();
				$title ="Bed ".$obj_bed->get_bed_number($value->type_id);
			}
			elseif($value->type == "Instrument Charges")
			{
				$type_title=$value->type;
				$obj_instrument = new MJ_hmgt_Instrumentmanage();
				$instrumentdata=$obj_instrument->get_single_instrument($value->type_id); 
				$title =$instrumentdata->instrument_name;
			}
			elseif($value->type == "Ambulance Charges")
			{
				$type_title=$value->type;
				$obj_ambulance = new MJ_Hmgt_ambulance();
				$ambulancedata=$obj_ambulance->get_single_ambulance($value->type_id); 
				$title =$ambulancedata->ambulance_id;
			}
			elseif($value->type == "Blood Charges")
			{	
				$type_title=$value->type;
				$title =$value->type_id;
			}
			elseif($value->type == "Dignosis Report Charges")
			{
				$report_type_array=explode(",",$value->type_id);
				$report_name=array();
				if(!empty($report_type_array))
				{	
					foreach($report_type_array as $report_id)
					{
						$post = get_post( $report_id );
						$report_title=$post->post_title;
						$report_title_array=json_decode($report_title);						
						$report_name[]= $report_title_array->category_name;	
						
					}
				}	
				$type_title=$value->type; 
				$title =implode(',',$report_name);
			}
			elseif($value->type == "Medicine Charges")
			{
				$type_title=$value->type;				
				$title =$value->type_id;
			}
			elseif($value->type == "Doctor Fees" || $value->type == "Nurse Charges")
			{
				$type_title=$value->type;	
				$first_name = get_user_meta($value->type_id,'first_name',true);
				$last_name = get_user_meta($value->type_id,'last_name',true);		
				$title = $first_name .' ' .$last_name;
			}
			else
			{	
				$title_data = get_post($value->type);
		
				$charge_data=$title_data->post_title;
				$charge_type_array=json_decode($charge_data);
				
				$type_title=$charge_type_array->category_name;
				$title = $type_title;
				
			}
			
			$bg_color = $i % 2 === 0 ? "#cad5f5" : "white";
			$mpdf->WriteHTML('<tr style="background-color: '. $bg_color .';">');
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$i.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$type_title.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$title.'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.date (MJ_hmgt_date_formate(),strtotime($value->date)).'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$value->unit.'');			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($value->type_value))
			{
				$mpdf->WriteHTML(''.number_format((float)$value->type_value,2,'.','').'');			
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($value->type_discount))
			{
				$mpdf->WriteHTML(''.number_format((float)$value->type_discount,2,'.','').'');			
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($value->type_tax))
			{
				$mpdf->WriteHTML(''.number_format((float)$value->type_tax,2,'.','').'');			
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($value->type_total_value))
			{
				$mpdf->WriteHTML(''.number_format((float)$value->type_total_value,2,'.','').'');	
			}
			else
			{				
				$type_total_value=$value->type_value-$value->type_discount+$value->type_tax;
				
				$mpdf->WriteHTML(''.number_format((float)$type_total_value,2,'.','').'');
			}
			$mpdf->WriteHTML('</td>');	
			
			$mpdf->WriteHTML('</tr>');
			$total_amount+=$value->type_value;
			$total_discount+=$value->type_discount;
			$total_tax+=$value->type_tax;
			$i++; 
		}
        $mpdf->WriteHTML('</tbody>');
        $mpdf->WriteHTML('</table>');
		$mpdf->WriteHTML('<table class="width_97" style="margin-left:55%;" border="0">');
		$mpdf->WriteHTML('<tbody>');
		
		if(!empty($invoice_data))
		{
		    $grand_total = $total_amount + $total_tax - $total_discount - $invoice_data->adjustment_amount - $invoice_data->paid_amount;
			
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Sub Total Amount','hospital_mgt').':</h4></td>');
				if(empty($total_amount))
				{				
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span>0.00</h4></td>');	
				}
				else
				{
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">'.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$total_amount,2,'.','').'</h4></td>');	
				}
			$mpdf->WriteHTML('</tr>');			
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Discount Amount','hospital_mgt').':</h4></td>');
				if(empty($total_discount))
				{				
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span>0.00</h4></td>');	
				}
				else
				{
					$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">- '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$total_discount,2,'.','').'</h4></td>');	
				}
			$mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Adjustment Amount','hospital_mgt').':</h4></td>');			
			if(empty($invoice_data->adjustment_amount))
			{				
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span>0.00</h4></td>');	
			}
			else
			{
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">- '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$invoice_data->adjustment_amount,2,'.','').'</h4></td>');	
			}
			$mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('<tr>');
			$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Tax Amount','hospital_mgt').':</h4></td>');			
			if(empty($total_tax))
			{				
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span>0.00</h4></td>');	
			}
			else
			{
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">+ '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$total_tax,2,'.','').'</h4></td>');	
			}
			$mpdf->WriteHTML('</tr>');
			
			$mpdf->WriteHTML('<tr>');
			$mpdf->WriteHTML('<td  class="width_70 align_right"><h4 class="margin h4_pdf font_family">'.__('Paid Amount',	'hospital_mgt').':</h4></td>');			
			if(empty($invoice_data->paid_amount))
			{				
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">-'.MJ_hmgt_get_currency_symbol().'</span>0.00</h4></td>');	
			}
			else
			{
				$mpdf->WriteHTML('<td class="align_right amount_padding_8"> <h4 class="margin h4_pdf"><span style="">- '.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$invoice_data->paid_amount,2,'.','').'</h4></td>');	
			}		
			$mpdf->WriteHTML('</tr>');
			
			$mpdf->WriteHTML('<tr>');
			$mpdf->WriteHTML('<td colspan="2">');
		
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('</tr>');
		}		
	    $mpdf->WriteHTML('<tr>');		
			$mpdf->WriteHTML('<td style="margin-left:50px;" class="align_right grand_total_lable font_family padding_11"><h3 class="color_white margin font_family">'.__('Grand Total ','hospital_mgt').':</h3></td>');
			$mpdf->WriteHTML('<td class="align_right grand_total_amount amount_padding_8"><h3 class="color_white margin"><span style="">'.MJ_hmgt_get_currency_symbol().'</span> '.number_format((float)$grand_total,2,'.','').'</h3></td>');		
		$mpdf->WriteHTML('</tr>');
		$mpdf->WriteHTML('</tbody>');
		$mpdf->WriteHTML('</table>');
		$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Payment History','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');
		//payment history table
		$mpdf->WriteHTML('<table class="table table-borderedtable_row_color" class="width_93" border="1">');
		$mpdf->WriteHTML('<thead>');
		$mpdf->WriteHTML('<tr>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">#</th>');			
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Date','hospital_mgt').'</th>');								
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Payment Method','hospital_mgt').'</th>');	
		$mpdf->WriteHTML('<th class="color_white entry_heading align_right padding_10_pdf font_family">'.__('Amount','hospital_mgt').'(<span style="">'.MJ_hmgt_get_currency_symbol().'</span>)</th>');			
		$mpdf->WriteHTML('</tr>');
        $mpdf->WriteHTML('</thead>');
        $mpdf->WriteHTML('<tbody>');
		global $wpdb;
		$table_income=$wpdb->prefix.'hmgt_income_expense';
		$sql = "SELECT * FROM $table_income where invoice_id = ".$invoice_id;
		$payment_history = $wpdb->get_results($sql);
		$i = 1;
		foreach($payment_history as $kay=>$value)
		{			
			$bg_color = $i % 2 === 0 ? "#cad5f5" : "white";
			$mpdf->WriteHTML('<tr style="background-color: '. $bg_color .';">');
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$i.'');			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.date(MJ_hmgt_date_formate(),strtotime($value->income_create_date)).'');
			
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
			$mpdf->WriteHTML(''.$value->payment_method.'');
			
			$mpdf->WriteHTML('</td>');
			$income_entry=json_decode($value->income_entry);
			if(!empty($income_entry))
			{	
				foreach($income_entry as $data)
				{
					$income_amount=$data->amount;
				}
			}
			$mpdf->WriteHTML('<td class="align_right table_td_font padding_10_pdf">');
			if(!empty($income_amount))
			{
				$mpdf->WriteHTML(''.number_format((float)$income_amount,2,'.','').'');
			}
			else
			{
				$mpdf->WriteHTML('0.00');
			}
			$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('</tr>');
			
			$i++; 
		}
        $mpdf->WriteHTML('</tbody>');
        $mpdf->WriteHTML('</table>');
		
		$mpdf->WriteHTML('</div>');
		$mpdf->WriteHTML("</body>");
		$mpdf->WriteHTML("</html>");

		  $invoice_dir = WP_CONTENT_DIR ;
		  $invoice_dir .= '/uploads/invoice/';
		  $invoice_path = $invoice_dir;
		  
		  if (!file_exists($invoice_path)) 
		  {
			mkdir($invoice_path, 0777, true);
			$mpdf->Output( WP_CONTENT_DIR . '/uploads/invoice/'.$invoice_id.'.pdf','F');
		  }
		  else
		  {
			   $mpdf->Output( WP_CONTENT_DIR . '/uploads/invoice/'.$invoice_id.'.pdf','F');
		  }	
      ob_end_flush();
      unset($mpdf);	
    $hospital_name = get_option('hmgt_hospital_name');
 
	$headers = "From: ".$hospital_name.' <noreplay@gmail.com>' . "\r\n";	
    $mail_attachment = array(WP_CONTENT_DIR . '/uploads/invoice/'.$invoice_id.'.pdf');
	$enable_notofication=get_option('hospital_enable_notifications');
	if($enable_notofication=='yes')
	{
		wp_mail($emails,$subject,$message,$headers,$mail_attachment); 
	}
}
//send prescription pdf in  mail //
function MJ_hmgt_send_perscription_mail($emails,$subject,$message,$prescription_id)
{	
     error_reporting(0);
     $obj_medicine = new MJ_hmgt_medicine();
	 $obj_treatment=new MJ_hmgt_treatment();
	 $obj_prescription=new MJ_hmgt_prescription();
	 $result = $obj_prescription->get_prescription_data($prescription_id);
  
     echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap.min.css', __FILE__).'"></link>';
     
     echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap.min.js', __FILE__).'"></script>'; 
     ob_clean();
     header('Content-type: application/pdf');
     header('Content-Disposition: inline; filename="invoice.pdf"');
     header('Content-Transfer-Encoding: binary');
     header('Accept-Ranges: bytes');	
     require HMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';	
  	 $stylesheet = file_get_contents(HMS_PLUGIN_DIR. '/assets/css/custom.css'); // Get css content
    $mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 5 , 0 , 0 , 0); 
	$mpdf->debug = true;
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style></style>');
	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
    $mpdf->SetTitle('Prescription');
	if(get_option('hmgt_enable_hospitalname_in_priscription')=='yes')
	{
		$mpdf->WriteHTML('<div class="modal-header">');
		
		$mpdf->WriteHTML('<h4 class="modal-title">'.get_option('hmgt_hospital_name').'</h4>');
		$mpdf->WriteHTML('</div>');
	} 
	$mpdf->WriteHTML('<img class="invoicefont1 img_padding_right_pdf" src="'.plugins_url('/hospital-management/assets/images/invoice.jpg').'" width="100%">');

     $mpdf->WriteHTML('<div class="main_div_pdf" id="invoice_print">');	
		$mpdf->WriteHTML('<table class="width_100_print" border="0">');					
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="width_1_print">');
								$mpdf->WriteHTML('<img class="system_logo system_logo_print"  src="'.get_option( 'hmgt_hospital_logo' ).'">');
							$mpdf->WriteHTML('</td>');							
							$mpdf->WriteHTML('<td class="only_width_20_print">');	
								$mpdf->WriteHTML('<table border="0">');					
								$mpdf->WriteHTML('<tbody>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td style="padding-bottom: 20px;">');
											$mpdf->WriteHTML('<b class="font_family">'.__('Address ','hospital_mgt').':</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.chunk_split(get_option( 'hmgt_hospital_address' ),30,"<BR>").'');
										$mpdf->WriteHTML('</td>');											
									$mpdf->WriteHTML('</tr>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td><b class="font_family">'.__('Email ','hospital_mgt').' :</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.get_option( 'hmgt_email' )."<br>".'');
										$mpdf->WriteHTML('</td>');	
									$mpdf->WriteHTML('</tr>');
									$mpdf->WriteHTML('<tr>');																	
										$mpdf->WriteHTML('<td>');
											$mpdf->WriteHTML('<b class="font_family">'.__('Phone ','hospital_mgt').' :</b>');
										$mpdf->WriteHTML('</td>');	
										$mpdf->WriteHTML('<td class="padding_left_5 table_td_font font_family">'.get_option( 'hmgt_contact_number' )."<br>".'');
										$mpdf->WriteHTML('</td>');											
									$mpdf->WriteHTML('</tr>');
								$mpdf->WriteHTML('</tbody>');
							$mpdf->WriteHTML('</table>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td align="right" class="width_24">');
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('<table>');
			 $mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td>');
				
					 $mpdf->WriteHTML('<table class="width_50_print"  border="0">');
						 $mpdf->WriteHTML('<tbody>');				
						 $mpdf->WriteHTML('<tr>');							
							$mpdf->WriteHTML('<td class="width_40_print" style="padding-left:20px;">');	
							
									 $patiet_id=$result->patient_id;
									$patient=get_userdata($patiet_id);
									
									$address=get_user_meta( $patiet_id,'address',true );
									
									$mpdf->WriteHTML('<h3 class="display_name font_family">'.chunk_split(ucwords($patient->display_name),30,"<BR>").'</h3>'); 																		
									$mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").''); 
									  $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'city_name',true ).','); 
									 $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'zip_code',true ).'<br>'); 
									 $mpdf->WriteHTML(''.get_user_meta( $patiet_id,'mobile',true ).'<br>'); 
								
							 $mpdf->WriteHTML('</td>'); 
							 /*$mpdf->WrieHTML('<td colspan="2" class="billed_to_pdf" align="center">');
							$mpdf->WriteHTML('</td>'); */
						 $mpdf->WriteHTML('</tr>');	 							
					 $mpdf->WriteHTML('</tbody>'); 
				 $mpdf->WriteHTML('</table>'); 
				
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td>');
				
				   $mpdf->WriteHTML('<table class="width_50_print"  border="0">');
					 $mpdf->WriteHTML('<tbody>');				
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
										
						 $mpdf->WriteHTML('</tr>');
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_20_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_30_print">');
								$mpdf->WriteHTML('<h5 class="h5_pdf font_family align_left">'.__('Date','hospital_mgt').' : '.date(MJ_hmgt_date_formate(),strtotime($result->pris_create_date)).'</h5>');
							
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');						
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');	
				$mpdf->WriteHTML('</td>');
			  $mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>');	 
		
			$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Case History :','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td class="font_12 padding_left_15" style="padding-left:20px;">'.$result->case_history.'</td>');
					$mpdf->WriteHTML('</tr>');
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');
			
			$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Treatment :','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
					$treatment=$obj_treatment->get_treatment_name($result->teratment_id); 
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td class="font_12 padding_left_15" style="padding-left:20px;">'.$treatment.'</td>');
					$mpdf->WriteHTML('</tr>');
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');	
			
		 $mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Rx :','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');
	
		$mpdf->WriteHTML('<table class="table table-borderedtable_row_color" class="width_93" border="1">');
		$mpdf->WriteHTML('<thead>');
		$mpdf->WriteHTML('<tr>');
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">#</th>');					
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Medicine Name','hospital_mgt').'</th>');					
		$mpdf->WriteHTML('<th class="color_white entry_heading  align_center padding_10_pdf font_family">'.__('Frequency Of Medicine','hospital_mgt').'</th>');		
		$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_10_pdf font_family">'.__('Time Period','hospital_mgt').'</th>');		
		$mpdf->WriteHTML('<th class="color_white entry_heading align_left  padding_10_pdf font_family">'.__('When Take','hospital_mgt').'</th>');				
		$mpdf->WriteHTML('</tr>');
        $mpdf->WriteHTML('</thead>');
        $mpdf->WriteHTML('<tbody>');
		$i=1;
						
		$all_medicine_list=json_decode($result->medication_list);
		if(!empty($all_medicine_list))
		{
			foreach($all_medicine_list as $retrieved_data)
			{
				$bg_color = $i % 2 === 0 ? "#cad5f5" : "white";
				$mpdf->WriteHTML('<tr style="background-color: '. $bg_color .';">');
				$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
				$mpdf->WriteHTML(''.$i.'');
				
				$mpdf->WriteHTML('</td>');
				$medicine=$obj_medicine->get_single_medicine($retrieved_data->medication_name);
							
				$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
				$mpdf->WriteHTML(''.$medicine->medicine_name.'');
				
				$mpdf->WriteHTML('</td>');
						
				$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
				$mpdf->WriteHTML(''.$retrieved_data->time.'');
				
				$mpdf->WriteHTML('</td>');
				
				$mpdf->WriteHTML('<td class="align_center table_td_font padding_10_pdf">');
				$mpdf->WriteHTML(''.$retrieved_data->per_days.'  '.$retrieved_data->time_period.'');
				
				$mpdf->WriteHTML('</td>');
				
				$mpdf->WriteHTML('<td class=" table_td_font padding_10_pdf">');
				$mpdf->WriteHTML(''.MJ_hmgt_get_medicine_take_timelabel($retrieved_data->takes_time).'');
				
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('</tr>');
				
				$i++; 
			}
		}	
        $mpdf->WriteHTML('</tbody>');
        $mpdf->WriteHTML('</table>');		
		
			$mpdf->WriteHTML('<table class="width_100_print">');	
				$mpdf->WriteHTML('<tbody>');	
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td style="padding-left:20px;">');
							$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.__('Extra Note :','hospital_mgt').'</h3>');
						$mpdf->WriteHTML('</td>');	
					$mpdf->WriteHTML('</tr>');	
					
					$mpdf->WriteHTML('<tr>');
						$mpdf->WriteHTML('<td class="font_12 padding_left_15" style="padding-left:20px;">'.$result->treatment_note.'</td>');
					$mpdf->WriteHTML('</tr>');
				$mpdf->WriteHTML('</tbody>');
			$mpdf->WriteHTML('</table>');	
			
		$all_entry=json_decode($result->custom_field);
		if(!empty($all_entry))
		{
		
			foreach($all_entry as $entry)
			{
				$mpdf->WriteHTML('<table class="width_100_print">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td style="padding-left:20px;">');
								$mpdf->WriteHTML('<h3 class="entry_lable font_family">'.$entry->label.'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
						
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="font_12 padding_left_15" style="padding-left:20px;">'.$entry->value.'</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');		
			}
		}	 	
		$mpdf->WriteHTML('</div>'); 

	$mpdf->WriteHTML("</body>");
	$mpdf->WriteHTML("</html>");
										
	  $prescription_dir = WP_CONTENT_DIR;
	  $prescription_dir .= '/uploads/prescription/';
	  $prescription_path = $prescription_dir;
	  
	  if (!file_exists($prescription_path))
	  {	 
		mkdir($prescription_path, 0777, true);
		$mpdf->Output( WP_CONTENT_DIR . '/uploads/prescription/'.$prescription_id.'.pdf','F');
	  }
	  else
	  {
		   $mpdf->Output( WP_CONTENT_DIR . '/uploads/prescription/'.$prescription_id.'.pdf','F');
		  
	  }		

	  ob_end_flush();
	  unset($mpdf);	
         $hospital_name = get_option('hmgt_hospital_name');
      $headers = "From: ".$hospital_name.' <noreplay@gmail.com>' . "\r\n";
	
    $mail_attachment = array(WP_CONTENT_DIR . '/uploads/prescription/'.$prescription_id.'.pdf');
	$enable_notofication=get_option('hospital_enable_notifications');
	if($enable_notofication=='yes'){
		wp_mail($emails,$subject,$message,$headers,$mail_attachment); 
	}
}

add_action('init','MJ_hmgt_frontend_menu_list');
//--------End nurse notes *------------------------- //
function get_patient_history_data($patient_id)
{
		global $wpdb;
		$table_history= $wpdb->prefix. 'hmgt_history';
		$result = $wpdb->get_results("SELECT *FROM $table_history where patient_id = ".$patient_id);
		return $result;
}
//---------view event------------//
function MJ_hmgt_view_event()
{
	 $notice = get_post($_REQUEST['evnet_id']);
	
	 ?>
	<div class="form-group"> 	<a href="#" class="close-btn-cat badge badge-danger pull-right">X</a>
	  <h4 class="modal-title" id="myLargeModalLabel">
		<?php _e('Event Detail','hospital_mgt'); ?>
	  </h4>
	</div>
	<hr>
	<div class="panel panel-white form-horizontal">
	  <div class="form-group">
		<label class="col-sm-3" for="notice_title">
		<?php _e(' Title','hospital_mgt');?>
		: </label>
		<div class="col-sm-9"> <?php echo $notice->post_title;?> </div>
	  </div>
	  <div class="form-group">
		<label class="col-sm-3" for="notice_title">
		<?php _e(' Comment','hospital_mgt');?>
		: </label>
		<div class="col-sm-9"> <?php echo wordwrap($notice->post_content,85,"<br>\n",TRUE);?> </div>
	  </div>
	  <div class="form-group">
		<label class="col-sm-3" for="notice_title">
		<?php _e('Event/Notice For','hospital_mgt');?>
		: </label>
		<div class="col-sm-9"> <?php 
		$notice_for_array=explode(",",get_post_meta( $notice->ID, 'notice_for',true));
		echo MJ_hmgt_get_role_name_in_event($notice_for_array);
		?> 
		</div>
	  </div>
	  <div class="form-group">
		<label class="col-sm-3" for="notice_title">
		<?php _e('Start Date','hospital_mgt');?>
		: </label>
		<div class="col-sm-9"> <?php echo  date(MJ_hmgt_date_formate(),strtotime(get_post_meta( $notice->ID, 'start_date',true)));?> </div>
	  </div>
	  <div class="form-group">
		<label class="col-sm-3" for="notice_title">
		<?php _e('End Date','hospital_mgt');?>
		: </label>
		<div class="col-sm-9"> <?php echo date(MJ_hmgt_date_formate(),strtotime(get_post_meta( $notice->ID, 'end_date',true)));?> </div>
	  </div>
	</div>
	<?php 
	die();
}
// GET TIME LABEL OF TAKE MEAL
function MJ_hmgt_get_medicine_take_timelabel($id)
{
	if($id=='before_breakfast')
		return __('Before Breakfast','hospital_mgt');
	if($id=='after_meal')
		return __('After Meal','hospital_mgt');
	if($id=='before_meal')
		return __('Before Meal','hospital_mgt');
	if($id=='night')
		return __('Night','hospital_mgt');
}
// PRINT PRESCRIPTION HTML
function MJ_hmgt_print_priscriptionl($presciption_id)
{
	$obj_medicine = new MJ_hmgt_medicine();
	$obj_treatment=new MJ_hmgt_treatment();
	$obj_prescription=new MJ_hmgt_prescription();
	$result = $obj_prescription->get_prescription_data($presciption_id);
	
	echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/custom.css', __FILE__).'"></link>';	?>
			
			<div class="panel-body prescription_pop_content">
			<?php if(get_option('hmgt_enable_hospitalname_in_priscription')=='yes'){?>
			<div class="modal-header">
			
			<h4 class="modal-title"><?php echo get_option('hmgt_hospital_name');?></h4>
			</div>
			<?php } ?>
			
			<img class="invoicefont1"  src="<?php echo plugins_url('/hospital-management/assets/images/invoice.jpg'); ?>" width="100%">
			<div class="main_div print_css_heading" id="invoice_print">
				<table class="width_100" border="0">					
					<tbody>
						<tr>
							<td class="width_1">
								<img class="system_logo"  src="<?php echo get_option( 'hmgt_hospital_logo' ); ?>">
							</td>							
							<td class="width_40">
								<table border="0">					
									<tbody>
										<tr>																	
											<td style="">
												<b><?php _e('Address :','hospital_mgt');?></b>
													<?php 
												 $address_length=strlen(get_option( 'hmgt_hospital_address' ));
												if($address_length>120)
												{
												?>
												<BR><BR><BR><BR><BR>
												<?php		
												}
												elseif($address_length>90)
												{
												?>
												<BR><BR><BR><BR>
												<?php	
												}
												elseif($address_length>60)
												{
												?>
													<BR><BR><BR>
												<?php		
												}
												elseif($address_length>30)
												{
												?>
												<BR><BR>
												<?php	
												}
												?>
											</td>	
											<td class="padding_left_5">
												<?php echo chunk_split(get_option( 'hmgt_hospital_address' ),30,"<BR>").""; ?>
											</td>											
										</tr>
										<tr>																	
											<td>
												<b><?php _e('Email :','hospital_mgt');?></b>
											</td>	
											<td class="padding_left_5">
												<?php echo get_option( 'hmgt_email' )."<br>"; ?>
											</td>	
										</tr>
										<tr>																	
											<td>
												<b><?php _e('Phone :','hospital_mgt');?></b>
											</td>	
											<td class="padding_left_5">
												<?php echo get_option( 'hmgt_contact_number' )."<br>";  ?>
											</td>											
										</tr>
									</tbody>
								</table>							
							</td>
							<td align="right" class="width_24">
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_50" border="0">
					<tbody>				
						<tr>							
							<td class="width_60">								
							<?php 							
								$patiet_id=$result->patient_id;
								$patient=get_userdata($patiet_id);							
								echo "<h3 class='display_name disply_name_margin_top_28'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";
								$address=get_user_meta( $patiet_id,'address',true );
								echo chunk_split($address,30,"<BR>"); 
								echo get_user_meta( $patiet_id,'city_name',true ).","; 
								echo get_user_meta( $patiet_id,'zip_code',true )."<br>"; 								
								echo get_user_meta( $patiet_id,'mobile',true )."<br>"; 
							?>			
							</td>
						</tr>									
					</tbody>
				</table>				
				<table class="width_50" border="0">
					<tbody>				
						<tr>	
							<td class="width_30">
							</td>
							<td class="width_20_prescription" style="padding-top:15px;" align="center">							
								<h5 class="align_left"> <?php   echo __('Date','hospital_mgt')." : ".date(MJ_hmgt_date_formate(),strtotime($result->pris_create_date)); ?></h5>
							</td>							
						</tr>									
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20" border="0">				
				<tbody>
					<tr>
						<td colspan="2">
							<h3 class="payment_method_lable"><?php _e('Case History :','hospital_mgt');?>
							</h3>
						</td>								
					</tr>
					<tr>
						<td class="font_12 padding_left_15"><?php echo $result->case_history; ?></td>
					</tr>							
				</tbody>
				</table>
				<table class="width_100 margin_bottom_20" border="0">				
				<tbody>
					<tr>
						<td colspan="2">
							<h3 class="payment_method_lable"><?php _e('Treatment :','hospital_mgt');?>
							</h3>
						</td>								
					</tr>
					<tr>
						<td class="font_12 padding_left_15"><?php  $treatment=$obj_treatment->get_treatment_name($result->teratment_id); echo $treatment; ?></td>
					</tr>							
				</tbody>
				</table>
		
				<table class="width_100">	
					<tbody>		
						<tr>
							<td>						
								<h3 class="entry_lable"><?php _e('Rx :','hospital_mgt');?></h3>
							</td>
						</tr>	
					</tbody>	
				</table>
				<table class="table table-bordered width_100 margin_bottom_10 table_row_color print_table_border" border="1">
					<thead class="entry_heading entry_heading_print">					
							<tr>
								<th class="color_white align_center">#</th>
								<th class="color_white align_center"> <?php _e('Medicine Name','hospital_mgt');?></th>
								<th class="color_white align_center"><?php _e('Frequency Of Medicine','hospital_mgt');?> </th>
								<th class="color_white align_center"><?php _e('Time Period','hospital_mgt');?> </th>
								<th class="color_white"><?php _e('When Take','hospital_mgt');?> </th>
							</tr>						
					</thead>
					<tbody>
					<?php 
						$id=1;
						
                        $all_medicine_list=json_decode($result->medication_list);
                        if(!empty($all_medicine_list))
                        {
                        	foreach($all_medicine_list as $retrieved_data)
							{
                        	?>																			
								<tr class="entry_list">
									<td class="align_center"><?php echo $id;?></td>
									<td class="align_center"><?php 
											$medicine=$obj_medicine->get_single_medicine($retrieved_data->medication_name);
										echo $medicine->medicine_name; ?></td>
									<td class="align_center"><?php echo $retrieved_data->time; ?></td>
									<td class="align_center"><?php echo $retrieved_data->per_days; ?> <?php echo $retrieved_data->time_period; ?></td>
									<td><?php echo MJ_hmgt_get_medicine_take_timelabel($retrieved_data->takes_time); ?></td>								
								</tr>								
                        	<?php 	
								$id=$id+1;
                        	}
                        }                       
						?>						
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20" border="0">				
				<tbody>
					<tr>
						<td colspan="2">
							<h3 class="payment_method_lable"><?php _e('Extra Note :','hospital_mgt');?>
							</h3>
						</td>								
					</tr>
					<tr>
						<td class="font_12 padding_left_15"><?php  echo $result->treatment_note; ?></td>
					</tr>							
				</tbody>
				</table>
				 <?php 
				  $all_entry=json_decode($result->custom_field);
				  if(!empty($all_entry))
				  {
					
					foreach($all_entry as $entry)
					{
						?>
						<table class="width_100 margin_bottom_20" border="0">				
							<tbody>
								<tr>
									<td colspan="2">
										<h3 class="payment_method_lable"><?php  echo $entry->label;?>
										</h3>
									</td>								
								</tr>
								<tr>
									<td class="font_12 padding_left_15"><?php echo $entry->value;?></td>
								</tr>							
							</tbody>
						</table>		
						<?php 
					}
				  }
				?>						
			</div>	
			</div>				
	<?php 
	die();
}
// VIEW PRESCRIPTION HTML FUNCTION BY AJAX //
function MJ_hmgt_view_priscription()
{
	$obj_medicine = new MJ_hmgt_medicine();
	$obj_treatment=new MJ_hmgt_treatment();
	$obj_prescription=new MJ_hmgt_prescription();

	$result = $obj_prescription->get_prescription_data($_REQUEST['prescription_id']);
	$hospital_name_print = get_option( 'hmgt_enable_hospitalname_in_priscription');
	?>
			<div class="modal-header">
			<a href="#" class="close-btn-cat badge badge-danger pull-right">X</a>
			<?php
			if($hospital_name_print == 'yes')
		    {
			 ?>
			 <h4 class="modal-title"><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h4>
			<?php			 
		    }			
			?>
			
			</div>
			<!--  rinkal changes prescription view -->
			<div class="panel-body prescription_pop_content">
			<img class="invoicefont123"  src="<?php echo plugins_url('/hospital-management/assets/images/invoice.jpg'); ?>" width="100%">
			<div class="main_div " id="invoice_print">
				<table class="width_100_123" border="0">					
					<tbody>
						<tr>
							<td class="width_1">
								<img class="system_logo"  src="<?php echo get_option( 'hmgt_hospital_logo' ); ?>">
							</td>							
							<td class="width_40">
								<table border="0">					
									<tbody>
										<tr>
											<td style="">
												<b><?php _e('Address:','hospital_mgt');?></b>
												<?php 
												 $address_length=strlen(get_option( 'hmgt_hospital_address' ));
												if($address_length>120)
												{
												?>
												<BR><BR><BR><BR><BR>
												<?php		
												}
												elseif($address_length>90)
												{
													?>
												<BR><BR><BR><BR>
												<?php	
												}
												elseif($address_length>60)
												{
												?>
													<BR><BR><BR>
												<?php		
												}
												elseif($address_length>30)
												{
												?>
												<BR><BR>
												<?php	
												}
												?>
											</td>	
											<td class="padding_left_5">
												<?php echo chunk_split(get_option( 'hmgt_hospital_address' ),30,"<BR>").""; ?>
											</td>											
										</tr>
										<tr>
											<td>
												<b><?php _e('Email:','hospital_mgt');?></b>
											</td>	
											<td class="padding_left_5">
												<?php echo get_option( 'hmgt_email' )."<br>"; ?>
											</td>	
										</tr>
										<tr>																	
											<td>
												<b><?php _e('Phone:','hospital_mgt');?></b>
											</td>	
											<td class="padding_left_5">
												<?php echo get_option( 'hmgt_contact_number' )."<br>";  ?>
											</td>											
										</tr>
									</tbody>
								</table>							
							</td>
							<td align="right" class="width_24">
							</td>
						</tr>
					</tbody>
				</table>  
				<table class="width_50" border="0">
					<tbody>				
						<tr>							
							<td class="width_60">								
							<?php 							
								$patiet_id=$result->patient_id;
								$patient=get_userdata($patiet_id);							
								echo "<h3 class='display_name disply_name_margin_top_28'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";
								$address=get_user_meta( $patiet_id,'address',true );
								echo chunk_split($address,30,"<BR>"); 
								echo get_user_meta( $patiet_id,'city_name',true ).","; 
								echo get_user_meta( $patiet_id,'zip_code',true )."<br>"; 								
								echo get_user_meta( $patiet_id,'mobile',true )."<br>"; 
							?>			
							</td>
						</tr>									
					</tbody>
				</table>				
				<table class="width_50" border="0">
					<tbody>				
						<tr>	
							<td class="width_30">
							</td>
							<td class="width_20_date" style="padding-top:38px;" align="center">							
								<h5 class="align_left_d"> <?php echo __('Date','hospital_mgt')." : ".date(MJ_hmgt_date_formate(),strtotime($result->pris_create_date)); ?></h5>
							</td>							
						</tr>									
					</tbody>
				</table><!-- rinkal end priscription-->
				<?php				
				if($_REQUEST['prescription_type'] == 'treatment')
				{
				?>
				<table class="width_100 margin_bottom_20" border="0">				
				<tbody>
					<tr>
						<td colspan="2">
							<h3 class="payment_method_lable"><?php _e('Case History :','hospital_mgt');?>
							</h3>
						</td>								
					</tr>
					<tr>
						<td class="font_12 padding_left_15"><?php echo $result->case_history; ?></td>
					</tr>							
				</tbody>
				</table>
				<table class="width_100 margin_bottom_20" border="0">				
				<tbody>
					<tr>
						<td colspan="2">
							<h3 class="payment_method_lable"><?php _e('Treatment :','hospital_mgt');?>
							</h3>
						</td>								
					</tr>
					<tr>
						<td class="font_12 padding_left_15"><?php  $treatment=$obj_treatment->get_treatment_name($result->teratment_id); echo $treatment; ?></td>
					</tr>							
				</tbody>
				</table>
		
				<table class="width_100">	
					<tbody>		
						<tr>
							<td>						
								<h3 class="entry_lable"><?php _e('Rx :','hospital_mgt');?></h3>
							</td>
						</tr>	
					</tbody>	
				</table>
				<table class="table table-bordered table_row_color" border="1">
					<thead class="entry_heading">					
							<tr>
								<th class="color_white align_center">#</th>
								<th class="color_white align_center"> <?php _e('Medicine Name','hospital_mgt');?></th>
								<th class="color_white align_center"><?php _e('Frequency Of Medicine','hospital_mgt');?> </th>
								<th class="color_white align_center"><?php _e('Time Period','hospital_mgt');?> </th>
								<th class="color_white"><?php _e('When Take','hospital_mgt');?> </th>
							</tr>						
					</thead>
					<tbody>
					<?php 
						$id=1;
						
                        $all_medicine_list=json_decode($result->medication_list);
                        if(!empty($all_medicine_list))
                        {
                        	foreach($all_medicine_list as $retrieved_data)
							{
                        	?>																			
								<tr class="entry_list">
									<td class="align_center"><?php echo $id;?></td>
									<td class="align_center"><?php 
											$medicine=$obj_medicine->get_single_medicine($retrieved_data->medication_name);
										echo $medicine->medicine_name; ?></td>
									<td class="align_center"><?php echo $retrieved_data->time; ?></td>
									<td class="align_center"><?php echo $retrieved_data->per_days; ?>  <?php echo $retrieved_data->time_period; ?></td>
									<td><?php echo MJ_hmgt_get_medicine_take_timelabel($retrieved_data->takes_time); ?></td>								
								</tr>								
                        	<?php 	
								$id=$id+1;
                        	}
                        }                       
						?>						
					</tbody>
				</table>
				<table class="width_100 margin_bottom_20" border="0">				
				<tbody>
					<tr>
						<td colspan="2">
							<h3 class="payment_method_lable"><?php _e('Extra Note :','hospital_mgt');?>
							</h3>
						</td>								
					</tr>
					<tr>
						<td class="font_12 padding_left_15"><?php  echo $result->treatment_note; ?></td>
					</tr>							
				</tbody>
				</table>
				 <?php 
				  $all_entry=json_decode($result->custom_field);
				  if(!empty($all_entry))
				  {
					
					foreach($all_entry as $entry)
					{
						?>
						<table class="width_100 margin_bottom_20" border="0">				
							<tbody>
								<tr>
									<td colspan="2">
										<h3 class="payment_method_lable"><?php  echo $entry->label;?>
										</h3>
									</td>								
								</tr>
								<tr>
									<td class="font_12 padding_left_15"><?php echo $entry->value;?></td>
								</tr>							
							</tbody>
						</table>		
						<?php 
					}
				  }
				?>						
			</div>	
			<div class="print-button pull-left">
				<a  href="?page=hmgt_prescription&print=print&prescription_id=<?php echo $_POST['prescription_id'];?>" target="_blank"class="btn entry_heading color_white"><?php _e('Print','hospital_mgt');?></a>
				<a href="?page=hmgt_prescription&prescriptionpdf=prescriptionpdf&prescription_id=<?php echo $_POST['prescription_id'];?>" target="_blank" class="btn entry_heading color_white"><?php _e('PDF','hospital_mgt');?></a>
			</div>
			<?php
			}			
			if($_REQUEST['prescription_type'] == 'report')
			{
			?>			
			<table class="width_100 margin_bottom_20" border="0">				
				<tbody>
					<tr>
						<td colspan="2">
							<h3 class="payment_method_lable"><?php _e('Report Description :','hospital_mgt');?>
							</h3>
						</td>								
					</tr>
					<tr>
						<td class="font_12 padding_left_15"><?php  echo $result->report_description; ?></td>
					</tr>							
				</tbody>
				</table>
			<?php
			}
			?>
			</div>
            </div>
        	</div>
        	 </div>
	<?php 
	die();
}
//--USER PROFILE HTML //
function MJ_hmgt_user_profile()
{
	$obj_hospital = new Hospital_Management(get_current_user_id());
	$user_info =get_userdata( $_REQUEST['user_id']);
	?>
<style>
.profile-cover{
	background: url("<?php echo get_option( 'hmgt_hospital_background_image', 'hospital_mgt' );?>") repeat scroll 0 0 / cover rgba(0, 0, 0, 0);
}

</style>
	<div class="modal-header"> <a href="#" class="close-btn-cat badge badge-danger pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title"><?php 
			$user=$user_info = get_userdata($_REQUEST['user_id']);
			echo $user->display_name;
		?>
		</h4>
	</div>
	<hr>
	<div class="profile-cover">
			<div class="row">				
						<div class="col-md-3 profile-image">
							<div class="profile-image-container">
							<?php $umetadata=get_user_meta($_REQUEST['user_id'], 'hmgt_user_avatar', true);
								
									if(empty($umetadata))
									{
										echo '<img src='.MJ_hmgt_get_default_userprofile($obj_hospital->role).' height="150px" width="150px" class="img-circle" />';
									}
									else
									{
										echo '<img src='.$umetadata.' height="150px" width="150px" class="img-circle" />';
									}
							?>
							</div>
						</div>						
			</div>
	</div>
	<div id="main-wrapper">
		<div class="panel-heading scroll_css">
			<table class="table table-bordered ">
				<tr>
					<td><?php _e('Email','hospital_mgt');?></td>
					<td><?php echo $user_info->user_email;?></td>
				</tr>
				<tr>
					<td><?php _e('Home Town Address','hospital_mgt');?></td>
					<td>
						<?php 
							echo $user_info->address;
							if( $user_info->home_city !="")
								echo  ", ".$user_info->home_city;
							if( $user_info->home_state !="")
								echo 	" ,".$user_info->home_state;
							if( $user_info->home_country !="")
								echo 	", ".$user_info->home_country.".";
						?>
						</td>
				</tr>
				<tr>
					<td><?php _e('Office Address', 'hospital_mgt');?></td>
					<td>
						<?php 
							if($user_info->office_address != "")
								echo $user_info->office_address;
							if( $user_info->city_name !="")
								echo 	", ".$user_info->city_name;
							if( $user_info->state_name !="")
								echo 	",".$user_info->state_name;
							if( $user_info->country_name !="")
								echo 	", ".$user_info->country_name.".";
						?>
					</td>
				</tr>
				<tr>
					<td><?php _e('Sex', 'hospital_mgt');?></td>
					<td><?php echo $user_info->gender;?></td>
				</tr>
				<tr>
					<td><?php _e('Birth Date', 'hospital_mgt');?></td>
					<td><?php echo  date(MJ_hmgt_date_formate(),strtotime($user_info->birth_date));?></td>
				</tr>
				<tr>
					<td><?php _e('Degree', 'hospital_mgt');?></td>
					<td><?php echo $user_info->doctor_degree;?></td>
				</tr>
				<tr>
					<td><?php _e('Visiting Charge', 'hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</td>
					<td><?php echo $user_info->visiting_fees;?></td>
				</tr>
				<tr>
					<td><?php _e('Consulting Charge', 'hospital_mgt');?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</td>
					<td><?php echo $user_info->consulting_fees;?></td>
				</tr>
			</table>
			
		</div>
		<div class="panel-body">
		</div>		   
	</div>	
	
	<?php 	
	die();
	
}
//------report period--------------- //
function MJ_hmgt_load_convert_patient()
{
		//$_REQUEST['patient_id'];
		 $patient_type=get_user_meta($_REQUEST['patient_id'],'patient_type',true);
			if( $patient_type=='outpatient'){
				
			echo '<label class="col-sm-2 control-label" for="patient_convert"></label>';
			echo '<div class="col-sm-8">';
			echo '<input type="checkbox"  name="patient_convert" value="inpatient">';
			echo __(' Convert into Inpatient','hospital_mgt');
			echo '</div>';
			}
			
		exit;
}
// SMS SERVICES SETTINGS //
function MJ_hmgt_sms_service_setting()
{

	$select_serveice = $_POST['select_serveice'];
	
	if($select_serveice == 'clickatell')
	{
		$clickatell=get_option( 'hmgt_clickatell_sms_service');
		?>
			<div class="form-group">
				<label class="col-sm-2 control-label " for="username"><?php _e('Username','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="username" class="form-control validate[required]" type="text" value="<?php if(isset($clickatell['username'])) echo $clickatell['username'];?>" name="username">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label " for="password"><?php _e('Password','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="password" class="form-control validate[required]" type="text" value="<?php if(isset($clickatell['password'])) echo $clickatell['password'];?>" name="password">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label " for="api_key"><?php _e('API Key','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="api_key" class="form-control validate[required]" type="text" value="<?php if(isset($clickatell['api_key'])) echo $clickatell['api_key'];?>" name="api_key">
				</div>
			</div>
		<?php 
		}
		
		if($select_serveice == 'twillo')
		{
		$twillo=get_option( 'hmgt_twillo_sms_service');
				?>
				<div class="form-group">
				<label class="col-sm-2 control-label " for="account_sid"><?php _e('Account SID','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="account_sid" class="form-control validate[required]" type="text" value="<?php if(isset($twillo['account_sid'])) echo $twillo['account_sid'];?>" name="account_sid">
				</div>
			</div>
		<div class="form-group">
				<label class="col-sm-2 control-label" for="auth_token"><?php _e('Auth Token','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="auth_token" class="form-control validate[required] text-input" type="text" name="auth_token" value="<?php if(isset($twillo['auth_token'])) echo $twillo['auth_token'];?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="from_number"><?php _e('From Number','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="from_number" class="form-control validate[required] text-input" type="text" name="from_number" value="<?php if(isset($twillo['from_number'])) echo $twillo['from_number'];?>">
				</div>
			</div>
			
		<?php }
		
		die();
}
//-------DATA TABLE MULTILANGUAGE----------- //
function MJ_hmgt_datatable_multi_language()
{
	$datatable_attr=array("sEmptyTable"=> __("No data available in table","hospital_mgt"),
						"sInfo"=>__("Showing _START_ to _END_ of _TOTAL_ entries","hospital_mgt"),
						"sInfoEmpty"=>__("Showing 0 to 0 of 0 entries","hospital_mgt"),
						"sInfoFiltered"=>__("(filtered from _MAX_ total entries)","hospital_mgt"),
						"sInfoPostFix"=> "",
						"sInfoThousands"=>",",
						"sLengthMenu"=>__("Show _MENU_ entries","hospital_mgt"),
						"sLoadingRecords"=>__("Loading...","hospital_mgt"),
						"sProcessing"=>__("Processing...","hospital_mgt"),
						"sSearch"=>__("Search:","hospital_mgt"),
						"sZeroRecords"=>__("No matching records found","hospital_mgt"),
						"oPaginate"=>array(
							"sFirst"=>__("First","hospital_mgt"),
							"sLast"=>__("Last","hospital_mgt"),
							"sNext"=>__("Next","hospital_mgt"),
							"sPrevious"=>__("Previous","hospital_mgt")
						),
						"oAria"=>array(
							"sSortAscending"=>__(": activate to sort column ascending","hospital_mgt"),
							"sSortDescending"=>__(": activate to sort column descending","hospital_mgt")
						)
	);
	
	return $data=json_encode( $datatable_attr);
}
// NEW FRONTEND MENU LIST //
function MJ_hmgt_frontend_menu_list()
{
	
	$access_array=array('doctor' => 
    array (
      'menu_icone' =>plugins_url('hospital-management/assets/images/icon/doctor.png'),
      'menu_title' =>'Doctor',
      'patient' => '1',
      'doctor' =>'1',
      'nurse' => '1' ,
      'receptionist' =>'1',
      'accountant' =>'1',
      'pharmacist' =>'1',
      'laboratorist' =>'1',
      'page_link' =>'doctor'),
	  
	   'outpatient' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/outpatient.png'),
      'menu_title' => 'Outpatient',
      'patient' => '1',
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => '1',
      'accountant' => '1',
      'pharmacist' => 0,
      'laboratorist' => 0,
      'page_link' =>'outpatient'),
	  
  'patient' => 
    array (
	  'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Patient.png'),
      'menu_title' =>'Inpatient',
      'patient' => '1',
      'doctor' =>'1',
      'nurse' => '1',
      'receptionist' =>'1',
      'accountant' => '1',
      'pharmacist' => '1',
      'laboratorist' => '1',
      'page_link' => 'patient'),
 
  'nurse' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Nurse.png'),
      'menu_title' =>'Nurse',
      'patient' => 0,
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => '1',
      'accountant' => '1',
      'pharmacist' => '1',
      'laboratorist' => '1',
      'page_link' => 'nurse'),
  'supportstaff' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/support.png'),
      'menu_title' => 'Support Staff',
      'patient' => 0,
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => '1',
      'accountant' => '1',
      'pharmacist' => '1',
      'laboratorist' => '1',
      'page_link' =>'supportstaff'),
  'pharmacist' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Pharmacist.png'),
      'menu_title' =>'Pharmacist',
      'patient' => 0,
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => '1',
      'accountant' => '1',
      'pharmacist' => '1',
      'laboratorist' => '1',
      'page_link' =>'pharmacist'),
  'laboratorystaff' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Laboratorist.png'),
      'menu_title' =>'Laboratory Staff',
      'patient' => 0,
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => '1',
      'accountant' => '1',
      'pharmacist' => '1',
      'laboratorist' => '1',
      'page_link' =>'laboratorystaff'),
  'accountant' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Accountant.png'),
      'menu_title' =>'Accountant',
      'patient' => 0,
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => '1',
      'accountant' => '1',
      'pharmacist' => '1',
      'laboratorist' => '1',
      'page_link' =>'accountant'),
  'medicine' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Medicine.png'),
      'menu_title' =>'Medicine',
      'patient' => 0,
      'doctor' => 0,
      'nurse' => 0,
      'receptionist' => 0,
      'accountant' => 0,
      'pharmacist' => '1',
      'laboratorist' => 0,
      'page_link' =>'medicine'),
  'treatment' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Treatment.png'),
      'menu_title' =>'Treatment',
      'patient' => 0,
      'doctor' => '1',
      'nurse' => 0,
      'receptionist' => 0,
      'accountant' => 0,
      'pharmacist' => 0,
      'laboratorist' => 0,
      'page_link' =>'treatment'),
  'prescription' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Prescription.png'),
      'menu_title' =>'Prescription',
      'patient' => 0,
      'doctor' => '1',
      'nurse' => 0,
      'receptionist' => 0,
      'accountant' => 0,
      'pharmacist' => 0,
      'laboratorist' => 0,
      'page_link' =>'prescription'),
  'bedallotment' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Assign--Bed-nurse.png'),
      'menu_title' =>'Assign Bed-Nurse',
      'patient' => 0,
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => 0,
      'accountant' => 0,
      'pharmacist' => 0,
      'laboratorist' => 0,
      'page_link' =>'bedallotment'),
  'operation' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Operation-List.png'),
      'menu_title' =>'Operation List',
      'patient' => 0,
      'doctor' => '1',
      'nurse' => 0,
      'receptionist' => 0,
      'accountant' => 0,
      'pharmacist' => 0,
      'laboratorist' => 0,
      'page_link' => 'operation'),
  'diagnosis' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Diagnosis-Report.png'),
      'menu_title' => 'Diagnosis',
      'patient' => '1',
      'doctor' => '1',
      'nurse' => 0,
      'receptionist' => 0,
      'accountant' => 0,
      'pharmacist' => 0,
      'laboratorist' => '1',
      'page_link' =>'diagnosis'),
  'bloodbank' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Blood-Bank.png'),
      'menu_title' =>'Blood Bank',
      'patient' => 0,
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => 0,
      'accountant' => 0,
      'pharmacist' => 0,
      'laboratorist' => '1',
      'page_link' =>'bloodbank'),
  'appointment' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Appointment.png'),
      'menu_title' =>'Appointment',
      'patient' => '1',
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => '1',
      'accountant' => 0,
      'pharmacist' => 0,
      'laboratorist' => 0,
      'page_link' =>'appointment'),
	  
	   'instrument' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Instrument.png'),
      'menu_title' =>'Instrument',
      'patient' => '1',
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => '1',
      'accountant' => 0,
      'pharmacist' => 0,
      'laboratorist' => 0,
      'page_link' =>'instrument'),
	  
  'invoice' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/payment.png'),
      'menu_title' =>  'Invoice',
      'patient' => 0,
      'doctor' => 0,
      'nurse' => 0,
      'receptionist' => 0,
      'accountant' => '1',
      'pharmacist' => 0,
      'laboratorist' => 0,
      'page_link' =>'invoice'),
  'event' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/notice.png'),
      'menu_title' =>'Event',
      'patient' => 0,
      'doctor' => '1',
      'nurse' => 0,
      'receptionist' => '1',
      'accountant' => '1',
      'pharmacist' => '1',
      'laboratorist' => '1',
      'page_link' => 'event'),
  'message' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/message.png'),
      'menu_title' => 'Message',
      'patient' => '1',
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => '1',
      'accountant' => '1',
      'pharmacist' => '1',
      'laboratorist' => '1',
      'page_link' => 'message'),
  'ambulance' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Ambulance.png'),
      'menu_title' =>'Ambulance',
	  'patient' => '1',
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => '1',
      'accountant' => 0,
      'pharmacist' => 0,
      'laboratorist' => 0,
      'page_link' =>'ambulance'),
  'report' => 
    array (
	   'menu_icone' =>plugins_url('hospital-management/assets/images/icon/Report.png'),
      'menu_title' => 'Report',
      'patient' => 0,
      'doctor' => '1',
      'nurse' => 0,
      'receptionist' => 0,
      'accountant' => 0,
      'pharmacist' => 0,
      'laboratorist' => 0,
      'page_link' =>'report'),
  'account' => 
    array (
	'menu_icone' =>plugins_url('hospital-management/assets/images/icon/account.png'),
      'menu_title' =>'Account',
      'patient' => '1',
      'doctor' => '1',
      'nurse' => '1',
      'receptionist' => '1',
      'accountant' => '1',
      'pharmacist' => '1',
      'laboratorist' => '1',
      'page_link' =>'account'));
	  
	
	if ( !get_option('hmgt_access_right') ) {
		update_option( 'hmgt_access_right', $access_array );
	}
	
}
// GET DAYS BETWEEN TWO DATES //
function MJ_hmgt_get_day_between_date($start_date,$end_date)
{
	$startTimeStamp =strtotime($start_date);
	$endTimeStamp =strtotime($end_date);		
	$timeDiff = abs($endTimeStamp - $startTimeStamp) ;		
	$numberDays = $timeDiff/86400;  // 86400 seconds in one day
		
	// and you might want to convert to integer
	 return $numberDays = intval($numberDays) + 1;
}
// GET BED TOTAL AMOUNT BY BED ID
function MJ_hmgt_get_bed_total_amount($days,$bad_id)
{
	global $wpdb;
	$table_hmgt_bed =$wpdb->prefix ."hmgt_bed";
	$beddata = $wpdb->get_row("SELECT * FROM $table_hmgt_bed WHERE bed_id=$bad_id");
	
	$total_bed_charge = $beddata->bed_charges * $days;
		
	return $total_bed_charge;
	
}
// GET NURSE TOTAL AMOUNT BY DAYS AND NURSE ID
function MJ_hmgt_get_nurse_total_amount($days,$nurse_id)
{
	global $wpdb;
	$nursecharge = get_user_meta($nurse_id,'charge',true);
	$nurse_total_charge = $nursecharge * $days;
				
	return $nurse_total_charge;
}
// GET NURSE TOTAL Tax by total fees
function MJ_hmgt_get_nurse_total_tax($nurse_total_charge,$nurse_id) 
{
	global $wpdb;
	$tax = get_user_meta($nurse_id,'tax',true);
	$tax_array=explode(',',$tax);
		
	if(!empty($tax_array))
	{
		$total_tax=0;
		foreach($tax_array as $tax_id)
		{
			$tax_percentage=MJ_hmgt_tax_percentage_by_tax_id($tax_id);
			$tax_amount=$nurse_total_charge * $tax_percentage / 100;
			
			$total_tax=$total_tax + $tax_amount;
			
		}
	}
	else
	{
		$total_tax=0;
	}		
				
	return $total_tax;
}
// GET INSTRUMENT CHARGES BY DAYS AND INSTRUMENT ID
function MJ_hmgt_get_instrument_total_amount($days,$instrumentid){
	
	$obj_instrument = new MJ_hmgt_Instrumentmanage();
	$result = $obj_instrument->get_single_instrument($instrumentid);
	return $result->instrument_charge * $days;
}
// CHECK NURSE TRANSFER 
function MJ_hmgt_cheack_nurse_transfer($nurse_id,$transfar_allotment_id){	
	global $wpdb;
	$hmgt_assign_type = $wpdb->prefix. 'hmgt_assign_type';
	$sql =  "SELECT * FROM $hmgt_assign_type WHERE child_id=$nurse_id AND parent_id=$transfar_allotment_id";
	$result = $wpdb->get_row($sql);
	return $result;
}
// CHECK NURSE IN TRANSITION
function MJ_hmgt_cheack_nurse_in_transition($nurse_id,$transfar_allotment_id,$type){
	global $wpdb;
	$table_hmgt_patient_transation = $wpdb->prefix. 'hmgt_patient_transation';
	 $sql =  "SELECT * FROM $table_hmgt_patient_transation WHERE type_id=$nurse_id AND refer_id=$transfar_allotment_id AND type='$type'";
	$result = $wpdb->get_row($sql);
	return $result;
}
// CHECK PAGE IS HOSPITAL PAGE OR NOT
function MJ_hmgt_is_hmgtpage()
{
	$current_page = isset($_REQUEST['page'])?$_REQUEST['page']:'';
	$pos = strrpos($current_page, "hmgt_");	
	
	if($pos !== false)			
	{
		return true;
	}
	return false;
}
function MJ_hmgt_check_ourserver()
{
	//$api_server = 'http://license.dasinfomedia.com';
	$api_server = 'license.dasinfomedia.com';
	//$api_server = '192.168.1.22';
	$fp = @fsockopen($api_server,80, $errno, $errstr, 2);
	$location_url = admin_url().'admin.php?page=hospital';
	if (!$fp)
              return false; /*server down*/
        else
              return true; /*Server up*/
}
function MJ_hmgt_check_productkey($domain_name,$licence_key,$email)
{
	//$api_server = 'http://license.dasinfomedia.com';
	$api_server = 'license.dasinfomedia.com';
	//$api_server = '192.168.1.22';
	$fp = @fsockopen($api_server,80, $errno, $errstr, 2);
	$location_url = admin_url().'admin.php?page=hospital';
	if (!$fp)
              $server_rerror = 'Down';
        else
              $server_rerror = "up";
	if($server_rerror == "up")
	{
	//$url = 'http://192.168.1.22/php/test/index.php';
	$url = 'http://license.dasinfomedia.com/index.php';
	$fields = 'result=2&domain='.$domain_name.'&licence_key='.$licence_key.'&email='.$email.'&item_name=hospital';
	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);

	//execute post
	$result = curl_exec($ch);
	
	curl_close($ch);
	return $result;
	}
	else{
		return '3';
	}
		
}
function MJ_hmgt_submit_setupform($data)
{
	$domain_name= $data['domain_name'];
	$licence_key = $data['licence_key'];
	$email = $data['enter_email'];
	
	$result = MJ_hmgt_check_productkey($domain_name,$licence_key,$email);
	
	if($result == '1')
	{
		$message = 'Please provide correct Envato purchase key.';
			$_SESSION['hmgt_verify'] = '1';
	}
	elseif($result == '2')
	{
		$message = 'This purchase key is already registered with the different domain. If have any issue please contact us at sales@dasinfomedia.com';
			$_SESSION['hmgt_verify'] = '2';
	}
	elseif($result == '3')
	{
		$message = 'There seems to be some problem please try after sometime or contact us on sales@dasinfomedia.com';
			$_SESSION['hmgt_verify'] = '3';
	}
	elseif($result == '4')
	{
		$message = 'Please provide correct Envato purchase key for this plugin.';
			$_SESSION['hmgt_verify'] = '1';
	}
	else{
		update_option('domain_name',$domain_name,true);
	    update_option('licence_key',$licence_key,true);
	     update_option('hmgt_setup_email',$email,true);
		$message = 'Licence Successfully Registered.';
			$_SESSION['hmgt_verify']='0';
	}	
			
	$result_array = array('message'=>$message,'hmgt_verify'=>$_SESSION['hmgt_verify']);
	return $result_array;
}
function MJ_hmgt_chekserver($server_name)
{
	if($server_name == 'localhost')
	{

		return true;
	}		
}
function MJ_hmgt_check_verify_or_not($result)
{	
	$server_name = $_SERVER['SERVER_NAME'];
	$current_page = isset($_REQUEST['page'])?$_REQUEST['page']:'';
	$pos = strrpos($current_page, "hmgt_");	
	
	if($pos !== false)			
	{
		if($server_name == 'localhost')
		{
			return true;
		}
		else
		{
			if($result == '0')
			{
				return true;
			}
		}
		return false;
	}
	
}
// CONVERT TIME FORMATE //
function MJ_hmgt_hmgtConvertTime( $time ) 
{
	$timestamp = strtotime( $time ); // Converting time to Unix timestamp
	$offset = get_option( 'gmt_offset' ) * 60 * 60; // Time offset in seconds
	$local_timestamp = $timestamp + $offset;
	$local_time = date_i18n(MJ_hmgt_date_formate() . ' H:i:s', $local_timestamp );
	return $local_time;
}
// REPLACE STRING FUNTION FOR MAIL TEMPLATE//
function MJ_hmgt_string_replacemnet($arr,$message)
{
	$data = str_replace(array_keys($arr),array_values($arr),$message);
	return $data;
}
// REPLACE STRING FUNTION FOR MAIL TEMPLATE//
function MJ_hmgt_subject_string_replacemnet($sub_arr,$subject)
{
	$data = str_replace(array_keys($sub_arr),array_values($sub_arr),$subject);
	return $data;
} 
// SEND MAIL FUNCTION FOR NOTIFICATION//
function MJ_hmgt_send_mail($emails,$subject,$message)
{	
    $hospital=get_option('hmgt_hospital_name');
	
	$headers="";
	$headers .= 'From: '.$hospital.' <noreplay@gmail.com>' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
	$enable_notofication=get_option('hospital_enable_notifications');
	if($enable_notofication=='yes'){
	return wp_mail($emails,$subject,$message,$headers);
	}
}  
//check valid image extension//
function MJ_hmgt_check_valid_extension($filename)
{
	$flag = 2; 
	if($filename != '')
	{
		$flag = 0;
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$valid_extension = ['gif','png','jpg','jpeg','bmp',""];
		if(in_array($ext,$valid_extension) )
		{
			$flag = 1;
		}
	}
	 return $flag;
}
//check valid file extension //
function MJ_hmgt_check_valid_file_extension($filename)
{
	$flag = 2; 
	if($filename != '')
	{
		$flag = 0;
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$valid_extension = ['pdf',""];
		if(in_array($ext,$valid_extension) )
		{
		  $flag = 1;
		}
	}
	return $flag;
}
//check diagnosis file validation //
function MJ_hmgt_check_valid_file_extension_for_diagnosis($report)
{
	$flag = 0;
	if(!empty($report))
	{	
		foreach($report as $filename)
		{
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$valid_extension = ['jpg','jpeg','png','gif','.doc','pdf','zip',""];
			if(in_array($ext,$valid_extension) )
			{
			 
			}
			else
			{
			   $flag =$flag+1;
			}
			
		}
	}	

	return $flag;
} 
//get currency symbol
function MJ_hmgt_get_currency_symbol( $currency = '' ) 
{			
             $currency = get_option('hmgt_currency_code');
			switch ( $currency ) {
			case 'AED' :
			$currency_symbol = 'د.إ';
			break;
			case 'AUD' :
			$currency_symbol = '&#36;';
			break;
			case 'CAD' :
			$currency_symbol = 'C&#36;';
			break;
			case 'CLP' :
			case 'COP' :
			case 'HKD' :
			$currency_symbol = '&#36';
			break;
			case 'MXN' :
			$currency_symbol = '&#36';
			break;
			case 'NZD' :
			$currency_symbol = '&#36;';
			break;
			case 'SGD' :
			case 'USD' :
			$currency_symbol = '&#36;';
			break;
			case 'BDT':
			$currency_symbol = '&#2547;&nbsp;';
			break;
			case 'BGN' :
			$currency_symbol = '&#1083;&#1074;.';
			break;
			case 'BRL' :
			$currency_symbol = '&#82;&#36;';
			break;
			case 'CHF' :
			$currency_symbol = '&#67;&#72;&#70;';
			break;
			case 'CNY' :
			case 'JPY' :
			case 'RMB' :
			$currency_symbol = '&yen;';
			break;
			case 'CZK' :
			$currency_symbol = '&#75;&#269;';
			break;
			case 'DKK' :
			$currency_symbol = 'kr.';
			break;
			case 'DOP' :
			$currency_symbol = 'RD&#36;';
			break;
			case 'EGP' :
			$currency_symbol = '£';
			break;
			case 'EUR' :
			$currency_symbol = '&euro;';
			break;
			/* case 'INR' :
			$currency_symbol = '&#x20B9;';
			break; */
			case 'GBP' :
			$currency_symbol = '&pound;';
			break;
			case 'HRK' :
			$currency_symbol = 'Kn';
			break;
			case 'HUF' :
			$currency_symbol = '&#70;&#116;';
			break;
			case 'IDR' :
			$currency_symbol = 'Rp';
			break;
			case 'ILS' :
			$currency_symbol = '&#8362;';
			break;
			case 'INR' :
			$currency_symbol = 'Rs.';
			break;
			case 'ISK' :
			$currency_symbol = 'Kr.';
			break;
			case 'KIP' :
			$currency_symbol = '&#8365;';
			break;
			case 'KRW' :
			$currency_symbol = '&#8361;';
			break;
			case 'MYR' :
			$currency_symbol = '&#82;&#77;';
			break;
			case 'NGN' :
			$currency_symbol = '&#8358;';
			break;
			case 'NOK' :
			$currency_symbol = '&#107;&#114;';
			break;
			case 'NPR' :
			$currency_symbol = 'Rs.';
			break;
			case 'PHP' :
			$currency_symbol = '&#8369;';
			break;
			case 'PLN' :
			$currency_symbol = '&#122;&#322;';
			break;
			case 'PYG' :
			$currency_symbol = '&#8370;';
			break;
			case 'RON' :
			$currency_symbol = 'lei';
			break;
			case 'RUB' :
			$currency_symbol = '&#1088;&#1091;&#1073;.';
			break;
			case 'SEK' :
			$currency_symbol = '&#107;&#114;';
			break;
			case 'THB' :
			$currency_symbol = '&#3647;';
			break;
			case 'TRY' :
			$currency_symbol = '&#8378;';
			break;
			case 'TWD' :
			$currency_symbol = '&#78;&#84;&#36;';
			break;
			case 'UAH' :
			$currency_symbol = '&#8372;';
			break;
			case 'VND' :
			$currency_symbol = '&#8363;';
			break;
			case 'ZAR' :
			$currency_symbol = '&#82;';
			break;
			case 'GHC' :
	        $currency_symbol = '&#8373;';
	        break;
			default :
			$currency_symbol = $currency;
			break;
	}
	return $currency_symbol;

}

   function MJ_hmgt_date_formate()
	{
	  $dateFormat=get_option( 'MJ_hmgt_date_formate' );
	 return $dateFormat;
	}
	
	function MJ_hmgt_dateformat_PHP_to_jQueryUI($php_format)
       {
			$SYMBOLS_MATCHING = array(
			// Day
			'd' => 'dd',
			'D' => 'D',
			'j' => 'd',
			'l' => 'DD',
			'N' => '',
			'S' => '',
			'w' => '',
			'z' => 'o',
			// Week
			'W' => '',
			// Month
			'F' => 'MM',
			'm' => 'mm',
			'M' => 'M',
			'n' => 'm',
			't' => '',
			// Year
			'L' => '',
			'o' => '',
			'Y' => 'yyyy',
			'y' => 'y',
			// Time
			'a' => '',
			'A' => '',
			'B' => '',
			'g' => '',
			'G' => '',
			'h' => '',
			'H' => '',
			'i' => '',
			's' => '',
			'u' => ''
			);
				$jqueryui_format = "";
				$escaping = false;
				for($i = 0; $i < strlen($php_format); $i++)
				{
					$char = $php_format[$i];
					if($char === '\\') // PHP date format escaping character
					{
					$i++;
					if($escaping) $jqueryui_format .= $php_format[$i];
					else $jqueryui_format .= '\'' . $php_format[$i];
					$escaping = true;
					}
					else
					{
					if($escaping) { $jqueryui_format .= "'"; $escaping = false; }
					if(isset($SYMBOLS_MATCHING[$char]))
					$jqueryui_format .= $SYMBOLS_MATCHING[$char];
					else
					$jqueryui_format .= $char;
					}
				}
   return $jqueryui_format;
 }
 //get date formate for database
 function MJ_hmgt_get_format_for_db($date)
{
	if(!empty($date))
	{
		$date = trim($date);
		
		 $new_date = DateTime::createFromFormat(MJ_hmgt_date_formate(), $date);

		 $new_date=$new_date->format('Y-m-d');
		 return $new_date;
	}
	else
	{
		$new_date ='';
		return $new_date;
	}
	
}
//get display date formate *// 
function get_format_for_display($date)
{
	 $date = trim($date);
	 $new_date = DateTime::createFromFormat(MJ_hmgt_date_formate(), $date);
	 $new_date=$new_date->format(MJ_hmgt_date_formate());
	 return $new_date;
}
//insert time check medicine name duplicate 
function hmgt_check_medicine_name_duplicate()
{
	$medicine_name=$_REQUEST['medicine_name'];
		
	global $wpdb;
	$table_medicine_category = $wpdb->prefix. 'hmgt_medicine';		
	$result = $wpdb->get_results("SELECT * FROM $table_medicine_category where medicine_name='$medicine_name'");	
	echo  sizeof($result);	
	die;
}
//edit time check medicine name duplicate 
function MJ_hmgt_check_edit_medicine_name_duplicate()
{
	$medicine_name=$_REQUEST['medicine_name'];
	$medicine_id=$_REQUEST['medicine_id'];
		
	global $wpdb;
	$table_medicine_category = $wpdb->prefix. 'hmgt_medicine';		
	$result = $wpdb->get_results("SELECT * FROM $table_medicine_category where medicine_name='$medicine_name' AND medicine_id!=$medicine_id");	
	echo  sizeof($result);	
	die;
}
//insert time medicine id duplication
function MJ_hmgt_check_medicine_id_duplicate()
{
	$med_uniqueid=$_REQUEST['med_uniqueid'];
		
	global $wpdb;
	$table_medicine_category = $wpdb->prefix. 'hmgt_medicine';		
	$result = $wpdb->get_results("SELECT * FROM $table_medicine_category where med_uniqueid='$med_uniqueid'");	
	echo  sizeof($result);	
	die;
}
//check edit time medicine id duplicate 
function MJ_hmgt_check_edit_medicine_id_duplicate()
{
	$med_uniqueid=$_REQUEST['med_uniqueid'];
	$medicine_id=$_REQUEST['medicine_id'];
	
	global $wpdb;
	$table_medicine_category = $wpdb->prefix. 'hmgt_medicine';		
	$result = $wpdb->get_results("SELECT * FROM $table_medicine_category where med_uniqueid='$med_uniqueid' AND medicine_id!=$medicine_id");	
	echo  sizeof($result);	
	die;
}
// GET current user role
function MJ_hmgt_get_current_user_role()
{
	$user = wp_get_current_user();
	$role=implode(" ",$user->roles);
	
	return $role;
}
//user role wise access right array
function MJ_hmgt_get_userrole_wise_access_right_array()
{
	$role=MJ_hmgt_get_current_user_role();
	
	if($role=='doctor')
	{
		$menu = get_option( 'hmgt_access_right_doctor');
	}
	elseif($role=='patient')
	{
		$menu = get_option( 'hmgt_access_right_patient');
	}
	elseif($role=='nurse')
	{
		$menu = get_option( 'hmgt_access_right_nurse');
	}
	elseif($role=='receptionist')
	{
		$menu = get_option( 'hmgt_access_right_supportstaff');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'hmgt_access_right_accountant');
	}
	elseif($role=='pharmacist')
	{
		$menu = get_option( 'hmgt_access_right_pharmacist');
	}
	elseif($role=='laboratorist')
	{
		$menu = get_option( 'hmgt_access_right_laboratories');
	}	
	
	/*foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{				
			if ($_REQUEST ['page'] == $value['page_link'])
			{				
				return $value;
			}
		}
	}*/	
}
//dashboard page access right //
function MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page)
{
	$role=MJ_hmgt_get_current_user_role();
	
	if($role=='doctor')
	{
		$menu = get_option( 'hmgt_access_right_doctor');
	}
	elseif($role=='patient')
	{
		$menu = get_option( 'hmgt_access_right_patient');
	}
	elseif($role=='nurse')
	{
		$menu = get_option( 'hmgt_access_right_nurse');
	}
	elseif($role=='receptionist')
	{
		$menu = get_option( 'hmgt_access_right_supportstaff');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'hmgt_access_right_accountant');
	}
	elseif($role=='pharmacist')
	{
		$menu = get_option( 'hmgt_access_right_pharmacist');
	}
	elseif($role=='laboratorist')
	{
		$menu = get_option( 'hmgt_access_right_laboratories');
	}	
	
	/*foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if ($page == $value['page_link'])
			{				
				if($value['view']=='0')
				{			
					$flage=0;
				}
				else
				{
				  $flage=1;
				}
			}
		}
	}*/	
	
	return $flage;
}
//dashboard fronted own data access right //
function MJ_hmgt_page_wise_view_data_on_fronted_dashboard($page)
{
	$role=MJ_hmgt_get_current_user_role();
	
	if($role=='doctor')
	{
		$menu = get_option( 'hmgt_access_right_doctor');
	}
	elseif($role=='patient')
	{
		$menu = get_option( 'hmgt_access_right_patient');
	}
	elseif($role=='nurse')
	{
		$menu = get_option( 'hmgt_access_right_nurse');
	}
	elseif($role=='receptionist')
	{
		$menu = get_option( 'hmgt_access_right_supportstaff');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'hmgt_access_right_accountant');
	}
	elseif($role=='pharmacist')
	{
		$menu = get_option( 'hmgt_access_right_pharmacist');
	}
	elseif($role=='laboratorist')
	{
		$menu = get_option( 'hmgt_access_right_laboratories');
	}	
	
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if ($page == $value['page_link'])
			{				
				if($value['own_data']=='0')
				{			
					$flage=0;
				}
				else
				{
				  $flage=1;
				}
			}
		}
	}	
	
	return $flage;
} 
//access right page not access message
function MJ_hmgt_access_right_page_not_access_message()
{
	?>
	<script type="text/javascript">
		$(document).ready(function() 
		{	
			alert('<?php _e('You do not have permission to perform this operation.','hospital_mgt');?>');
			window.location.href='?dashboard=user';
		});
	</script>
<?php
}	
//get role by id//
function MJ_hmgt_get_roles($user_id)
{
	$roles = array();
	$user = new WP_User( $user_id );

	if ( !empty( $user->roles ) && is_array( $user->roles ) )
	{	
		return $user->roles;
	}	
}
//get id by  display_name
function MJ_hmgt_get_user_id_by_display_name( $display_name ) 
{
    global $wpdb;

    if ( ! $user = $wpdb->get_row( $wpdb->prepare(
        "SELECT `ID` FROM $wpdb->users WHERE `display_name` = %s", $display_name
    ) ) )
        return false;

    return $user->ID;
}
//encrypt id
function MJ_hmgt_id_encrypt($id)
{
	$encrypted_id = base64_encode($id);

	return $encrypted_id;
}
//decrypted_id
function MJ_hmgt_id_decrypt($encrypted_id)
{	
	$decrypted_id = base64_decode($encrypted_id);

	return $decrypted_id;
}
//add_action('init','MJ_hmgt_browser_javascript_check');
 function MJ_hmgt_browser_javascript_check()
{
	$plugins_url = plugins_url( 'hospital-management/ShowErrorPage.php' );
?>
	<noscript><meta http-equiv="refresh" content="0;URL=<?php echo $plugins_url;?>"></noscript> 
<?php
} 
//strip tags and slashes
function MJ_hmgt_strip_tags_and_stripslashes($string)
{
	$new_string=stripslashes(strip_tags($string));
	return $new_string;
}	
// GET doctor all PATIENT id LIST
function MJ_hmgt_doctor_patientid_list()
{	
	global $wpdb;		
	$table_hmgt_inpatient_guardian = $wpdb->prefix .'hmgt_inpatient_guardian';
	$current_user_id=get_current_user_id();
	$patient_id= array();
	$patient_id_array = $wpdb->get_results("SELECT patient_id FROM $table_hmgt_inpatient_guardian where doctor_id=$current_user_id");
	if(!empty($patient_id_array))
	{	
		foreach($patient_id_array as $data)
		{
			$patient_id[]=$data->patient_id;
		}
	}
	return $patient_id;

}
//single operation assined id doctor array
function MJ_hmgt_doctor_all_operation_array()
{	
	global $wpdb;		
	$table_hmgt_assign_type = $wpdb->prefix .'hmgt_assign_type';
	$current_user_id=get_current_user_id();
	$operation_id= array();
	$operation_array = $wpdb->get_results("SELECT parent_id FROM $table_hmgt_assign_type where child_id=$current_user_id And assign_type='operation_theater'");
	
	if(!empty($operation_array))
	{	
		foreach($operation_array as $data)
		{
			$operation_id[]=$data->parent_id;
		}
	}
	return $operation_id;

}
//single operation assined id doctor array
function MJ_hmgt_nurse_all_bedallotment_data_array()
{	
	global $wpdb;		
	$table_hmgt_assign_type = $wpdb->prefix .'hmgt_assign_type';
	$current_user_id=get_current_user_id();
	$operation_id= array();
	$operation_array = $wpdb->get_results("SELECT parent_id FROM $table_hmgt_assign_type where child_id=$current_user_id And assign_type='nurse-bedallotment'");
	
	if(!empty($operation_array))
	{	
		foreach($operation_array as $data)
		{
			$operation_id[]=$data->parent_id;
		}
	}
	return $operation_id;

}
// GET nurse all PATIENT id LIST
function MJ_hmgt_nurse_patientid_list()
{	
	global $wpdb;		
	$table_hmgt_assign_type = $wpdb->prefix .'hmgt_assign_type';
	$table_hmgt_bed_allotment = $wpdb->prefix .'hmgt_bed_allotment';
	$current_user_id=get_current_user_id();
	
	$patient_id= array();
	$parent_array= array();
	$parent_id_array = $wpdb->get_results("SELECT parent_id FROM $table_hmgt_assign_type where child_id=$current_user_id");
	
	if(!empty($parent_id_array))
	{	
		foreach($parent_id_array as $data)
		{
			$parent_array[]=$data->parent_id;
		}	
	}
	
	if(!empty($parent_array))
	{
		foreach($parent_array as $data1)
		{
			$parent_id=$data1;
	
			$patient_id_data = $wpdb->get_row("SELECT patient_id FROM $table_hmgt_bed_allotment where bed_allotment_id=$parent_id");
			
			$patient_id[]=$patient_id_data->patient_id;
		}
	}	
	
	return $patient_id;
}
// GET nurse all PATIENT id array
function MJ_hmgt_nurse_patientid_array()
{	
	$patient_id_array=array();
	$patient_data=MJ_hmgt_nurse_patientid_list(); 
	foreach($patient_data as $id)
	{
		$patient_id_array[]=get_user_meta($id, 'patient_id',true);
	}
	return $patient_id_array;
}
//Diagnosis report name edit function //
function MJ_hmgt_edit_diagnosisreport_name()
{
	global $wpdb;
	$model = $_REQUEST['model'];
	$cat_id = $_REQUEST['cat_id'];
	
	$post = get_post($_REQUEST['cat_id']);
	$retrieved_data=$post->post_title;
	$report_type= json_decode($retrieved_data);
	

	if($model == 'report_type')
	{
		$data='<td><input type="text" class="validate[required,custom[popup_category_validation]] form-control text-input" maxlength="50" name="report_name" value="'.$report_type->category_name.'" id="report_name">
			</td><td><input  class="form-control  text-input validate[required]" name="report_amount" type="number" min="0" onkeypress="if(this.value.length==8) return false;" step="0.01" value="'.$report_type->report_cost.'" id="report_amount">
			</td><td>
					<script type="text/javascript">
					jQuery("document").ready(function($)
					{
						$(".tax_charge").multiselect({
							nonSelectedText :"'.__("Select Tax","hospital_mgt").'",
							includeSelectAllOption: true,
							selectAllText : "'.__('Select all','hospital_mgt').'"
						 });
					});
				</script>			
					<select class="form-control tax_charge" name="tax[]" multiple="multiple" id="diagnosis_tax">';	
						
						$tax_id=explode(',',$report_type->diagnosis_tax);
						
						$obj_invoice= new MJ_hmgt_invoice();
						$hmgt_taxs=$obj_invoice->get_all_tax_data();	
						
						if(!empty($hmgt_taxs))
						{
							foreach($hmgt_taxs as $entry)
							{
								$selected = "";
								if(in_array($entry->tax_id,$tax_id))
									$selected = "selected";
							
								$data.='<option value="'.$entry->tax_id.'" '.$selected.' >'.$entry->tax_title.'-'.$entry->tax_value.'</option>';						
							}
						}
						
					$data.='</select></td><td><input type="text" class="form-control validate[required,custom[address_description_validation]]" name="report_des" value="'.$report_type->diagnosis_description.'" id="report_des"></td>';
		$data.='<td id='.$cat_id.'>
			<a class="btn-cat-update-cancel btn btn-danger" model='.$model.' href="#" id='.$cat_id.'>'.__('Cancel','hospital_mgt').'</a>
		 </td>';
		$data.='<td id='.$cat_id.'> 
		<a class="btn-cat-update btn btn-primary" model='.$model.' href="#" id='.$cat_id.'>'.__('Save','hospital_mgt').'</a>
		</td>';
	}
	elseif($model == 'operation')
	{
			$data='<td><input type="text" class="validate[required,custom[popup_category_validation]] form-control text-input" maxlength="50" name="operation_name" value="'.$report_type->category_name.'" id="operation_name">
			</td><td><input  class="form-control  text-input validate[required]" name="operation_amount" type="number" min="0" onkeypress="if(this.value.length==8) return false;" step="0.01" value="'.$report_type->operation_cost.'" id="operation_amount">
			</td><td>
				<script type="text/javascript">
					jQuery("document").ready(function($)
					{
						$(".tax_charge").multiselect({
							nonSelectedText :"'.__("Select Tax","hospital_mgt").'",
							includeSelectAllOption: true,
							selectAllText : "'.__('Select all','hospital_mgt').'"
						 });
					});
				</script>				
					<select class="form-control tax_charge" name="tax[]" multiple="multiple" id="operation_tax">';	
						
						$tax_id=explode(',',$report_type->operation_tax);
						
						$obj_invoice= new MJ_hmgt_invoice();
						$hmgt_taxs=$obj_invoice->get_all_tax_data();	
						
						if(!empty($hmgt_taxs))
						{
							foreach($hmgt_taxs as $entry)
							{
								$selected = "";
								if(in_array($entry->tax_id,$tax_id))
									$selected = "selected";
							
								$data.='<option value="'.$entry->tax_id.'" '.$selected.' >'.$entry->tax_title.'-'.-$entry->tax_value.'</option>';						
							}
						}
						
					$data.='</select></td><td><input type="text" class="form-control validate[required,custom[address_description_validation]]" name="operation_des" value="'.$report_type->operation_description.'" id="operation_des"></td>';
		$data.='<td id='.$cat_id.'>
			<a class="btn-cat-update-cancel btn btn-danger" model='.$model.' href="#" id='.$cat_id.'>'.__('Cancel','hospital_mgt').'</a>
		 </td>';
		$data.='<td id='.$cat_id.'> 
		<a class="btn-cat-update btn btn-primary" model='.$model.' href="#" id='.$cat_id.'>'.__('Save','hospital_mgt').'</a>
		</td>';
	}
	elseif($model == 'invoice_charge')
	{
		$data='<td><input type="text" class="validate[required,custom[popup_category_validation]] form-control text-input" maxlength="50" name="charge_name" value="'.$report_type->category_name.'" id="report_name">
			</td><td><input  class="form-control  text-input validate[required]" name="charge_amount" type="number" min="0" onkeypress="if(this.value.length==8) return false;" step="0.01" value="'.$report_type->charge_amount.'" id="charge_amount">
			</td><td>
					<script type="text/javascript">
					jQuery("document").ready(function($)
					{
						$(".tax_charge").multiselect({
							nonSelectedText :"'.__("Select Tax","hospital_mgt").'",
							includeSelectAllOption: true,
							selectAllText : "'.__('Select all','hospital_mgt').'"
						 });
					});
				</script>			
					<select class="form-control tax_charge" name="tax[]" multiple="multiple" id="charge_tax">';	
						
						$tax_id=explode(',',$report_type->charge_tax);
						
						$obj_invoice= new MJ_hmgt_invoice();
						$hmgt_taxs=$obj_invoice->get_all_tax_data();	
						
						if(!empty($hmgt_taxs))
						{
							foreach($hmgt_taxs as $entry)
							{
								$selected = "";
								if(in_array($entry->tax_id,$tax_id))
									$selected = "selected";
							
								$data.='<option value="'.$entry->tax_id.'" '.$selected.' >'.$entry->tax_title.'-'.$entry->tax_value.'</option>';						
							}
						}
						
					$data.='</select></td><td><input type="text" class="form-control validate[required,custom[address_description_validation]]" name="charge_description" value="'.$report_type->charge_description.'" id="charge_description"></td>';
		$data.='<td id='.$cat_id.'>
			<a class="btn-cat-update-cancel btn btn-danger" model='.$model.' href="#" id='.$cat_id.'>'.__('Cancel','hospital_mgt').'</a>
		 </td>';
		$data.='<td id='.$cat_id.'> 
		<a class="btn-cat-update btn btn-primary" model='.$model.' href="#" id='.$cat_id.'>'.__('Save','hospital_mgt').'</a>
		</td>';
	}
	echo $data;	
	die();
}
//Get single report name function //
function MJ_hmgt_single_dignosisname($diagnosis_id)
{
	global $wpdb;
	$title=get_the_title( $diagnosis_id );
	return $title;
}
//update cancel diagnosis report name
function MJ_hmgt_update_cancel_diagnosisreport_name()
{
	$cat_id=$_POST['cat_id'];
	$model=$_POST['model'];
	global $wpdb;
	$post = get_post($_POST['cat_id']);
	$retrieved_data=$post->post_title;
	$report_type= json_decode($retrieved_data);
	
	if($model == 'report_type')
	{
		echo '<td>'.$report_type->category_name.'</td>';
		echo '<td>'.$report_type->report_cost.'</td>';
		echo '<td>'.MJ_hmgt_tax_name_array_by_tax_id_array($report_type->diagnosis_tax).'</td>';
		echo '<td>'.$report_type->diagnosis_description.'</td>';
		echo '<td id='.$cat_id.'>
		<a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$cat_id.'>X</a></td>';
		echo '<td id='.$cat_id.'><a class="btn-edit-cat badge badge-edit" model='.$model.' href="#" id='.$cat_id.'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
		</td>'; 
	}
	elseif($model == 'operation')	
	{
		echo '<td>'.$report_type->category_name.'</td>';
		echo '<td>'.$report_type->operation_cost.'</td>';
		echo '<td>'.MJ_hmgt_tax_name_array_by_tax_id_array($report_type->operation_tax).'</td>';
		echo '<td>'.$report_type->operation_description.'</td>';
		echo '<td id='.$cat_id.'>
		<a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$cat_id.'>X</a></td>';
		echo '<td id='.$cat_id.'><a class="btn-edit-cat badge badge-edit" model='.$model.' href="#" id='.$cat_id.'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
		</td>'; 
					
	}	
	elseif($model == 'invoice_charge')
	{
		echo '<td>'.$report_type->category_name.'</td>';
		echo '<td>'.$report_type->charge_amount.'</td>';
		echo '<td>'.MJ_hmgt_tax_name_array_by_tax_id_array($report_type->charge_tax).'</td>';
		echo '<td>'.$report_type->charge_description.'</td>';
		echo '<td id='.$cat_id.'>
		<a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$cat_id.'>X</a></td>';
		echo '<td id='.$cat_id.'><a class="btn-edit-cat badge badge-edit" model='.$model.' href="#" id='.$cat_id.'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
		</td>'; 
	}
	die();
}

function MJ_hmgt_update_diagnosisreport_name()
{
	global $wpdb;
	$model=$_POST['model'];
	$cat_id=$_POST['cat_id'];
	$array_var = array();
	
	if($model == 'report_type')
	{
		$report_name=$_POST['report_name'];
		$report_des=$_POST['report_des'];
		$diagnosis_tax= implode(",",$_POST['diagnosis_tax']);
		$report_amount=$_POST['report_amount'];
		$category_name=MJ_hmgt_strip_tags_and_stripslashes($report_name);
		$diagnosis_description=MJ_hmgt_strip_tags_and_stripslashes($report_des);
		
		$report_type_array=array("category_name"=>$category_name,"report_cost"=>$report_amount,"diagnosis_tax"=>$diagnosis_tax,"diagnosis_description"=>$diagnosis_description);
			
		$report_type=json_encode($report_type_array);		
		
		$update_dagnosis_report_array = array(
		  'ID'           => $_POST['cat_id'],
		  'post_status'   => 'publish',
		  'post_title' => $report_type
		);
		$result=wp_update_post($update_dagnosis_report_array );	
		
		if($result)
		{
			$row1='<td>'.stripslashes($category_name).'</td><td>'.stripslashes($report_amount).'</td><td>'.MJ_hmgt_tax_name_array_by_tax_id_array($diagnosis_tax).'</td><td>'.stripslashes($diagnosis_description).'</td><td id='.$cat_id.'><a class="btn-delete-cat badge badge-delete" model='.stripslashes($model).' href="#" id='.stripslashes($cat_id).'>X</a></td><td id='.stripslashes($cat_id).'><a class="btn-edit-cat badge badge-edit" model='.stripslashes($model).' href="#" id='.stripslashes($cat_id).'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>'; 
			
			$option = '<option value='.stripslashes($cat_id).'>'.stripslashes($category_name).'</option>';
			$array_var[] = $row1;
			$array_var[] = $option;
			echo json_encode($array_var);
		}
	}
	elseif($model == 'operation')
	{
		$operation_name=$_POST['operation_name'];
		$operation_des=$_POST['operation_des'];
		$operation_tax= implode(",",$_POST['operation_tax']);
		$operation_amount=$_POST['operation_amount'];
		$category_name=MJ_hmgt_strip_tags_and_stripslashes($operation_name);
		$operation_description=MJ_hmgt_strip_tags_and_stripslashes($operation_des);
		
		$operation_type_array=array("category_name"=>$category_name,"operation_cost"=>$operation_amount,"operation_tax"=>$operation_tax,"operation_description"=>$operation_description);
			
		$operation_type=json_encode($operation_type_array);
				
		$update_operation_type_array = array(
		  'ID'           => $_POST['cat_id'],
		  'post_status'   => 'publish',
		  'post_title' => $operation_type
		);
		$result=wp_update_post($update_operation_type_array );	
		
		if($result)
		{
			$row1='<td>'.stripslashes($category_name).'</td><td>'.stripslashes($operation_amount).'</td><td>'.MJ_hmgt_tax_name_array_by_tax_id_array($operation_tax).'</td><td>'.stripslashes($operation_description).'</td><td id='.stripslashes($cat_id).'><a class="btn-delete-cat badge badge-delete" model='.stripslashes($model).' href="#" id='.stripslashes($cat_id).'>X</a></td><td id='.stripslashes($cat_id).'><a class="btn-edit-cat badge badge-edit" model='.stripslashes($model).' href="#" id='.stripslashes($cat_id).'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
			</td>'; 
			
			$option = '<option value='.stripslashes($cat_id).'>'.stripslashes($category_name).'</option>';
			$array_var[] = $row1;
			$array_var[] = $option;
			echo json_encode($array_var);
		}
	}	
	elseif($model == 'invoice_charge')
	{
		$report_name=$_POST['report_name'];
		$charge_description=$_POST['charge_description'];
		$charge_tax= implode(",",$_POST['charge_tax']);
		$charge_amount=$_POST['charge_amount'];
		$category_name=MJ_hmgt_strip_tags_and_stripslashes($report_name);
		$charge_description=MJ_hmgt_strip_tags_and_stripslashes($charge_description);
		
		$charge_type_array=array("category_name"=>$category_name,"charge_amount"=>$charge_amount,"charge_tax"=>$charge_tax,"charge_description"=>$charge_description);		
		
		$charge_type=json_encode($charge_type_array);		
		
		$update_charge_array = array(
		  'ID'           => $_POST['cat_id'],
		  'post_status'   => 'publish',
		  'post_title' => $charge_type
		);
		$result=wp_update_post($update_charge_array );	
		
		if($result)
		{
			$row1='<td>'.stripslashes($category_name).'</td><td>'.stripslashes($charge_amount).'</td><td>'.MJ_hmgt_tax_name_array_by_tax_id_array($charge_tax).'</td><td>'.stripslashes($charge_description).'</td><td id='.stripslashes($cat_id).'><a class="btn-delete-cat badge badge-delete" model='.stripslashes($model).' href="#" id='.stripslashes($cat_id).'>X</a></td><td id='.stripslashes($cat_id).'><a class="btn-edit-cat badge badge-edit" model='.stripslashes($model).' href="#" id='.stripslashes($cat_id).'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>'; 
			
			$option = '<option value='.stripslashes($cat_id).'>'.stripslashes($category_name).'</option>';
			$array_var[] = $row1;
			$array_var[] = $option;
			echo json_encode($array_var);
		}
	}
die();
}
//get tax percentage by tax id
function MJ_hmgt_tax_percentage_by_tax_id($tax_id)
{
	$obj_invoice= new MJ_hmgt_invoice();
	if(!empty($tax_id))
	{	
		$hmgt_taxs=$obj_invoice->hmgt_get_single_tax_data($tax_id);		
	}
	else
	{
		$hmgt_taxs='';
	}
	
	if(!empty($hmgt_taxs))
	{
		return $hmgt_taxs->tax_value;
	}
	else
	{
		return 0;
	}
	die;
}
//get tax Name array by tax id array
function MJ_hmgt_tax_name_array_by_tax_id_array($tax_id_string)
{
	$obj_invoice= new MJ_hmgt_invoice();
	
	$tax_name=array();
	$tax_id_array=explode(",",$tax_id_string);
	$tax_name_string="";
	if(!empty($tax_id_string))
	{
		foreach($tax_id_array as $tax_id)
		{
			$hmgt_taxs=$obj_invoice->hmgt_get_single_tax_data($tax_id);		
			$tax_name[]=$hmgt_taxs->tax_title.'-'.$hmgt_taxs->tax_value;
		}	
		$tax_name_string=implode(",",$tax_name);		
	}	
	return $tax_name_string;
	die;
}
//count diagnosis report amount
function MJ_hmgt_count_dagnosisreport_amount()
{
	$report_type_id=$_REQUEST['report_type_id'];
	global $wpdb;
	$report_type=new MJ_hmgt_dignosis();
	$total_report_cost = 0;
	$diagnosis_total_tax = 0;
	$report_total_amount = 0;
	//$report_amountnew = 5;
	
	$array_var = array();
	
	foreach($report_type_id  as $report_id)
	{
		$report_data=$report_type->get_report_by_id($report_id);
		$report_type_array=json_decode($report_data);
		$report_cost =$report_type_array->report_cost;
		$diagnosis_tax_array = explode(",",$report_type_array->diagnosis_tax);
		
		$total_tax=0;
		
		if(!empty($diagnosis_tax_array))
		{	
			foreach($diagnosis_tax_array  as $tax_id)
			{				
				$tax_percentage=MJ_hmgt_tax_percentage_by_tax_id($tax_id);
				$tax_amount=$report_cost * $tax_percentage / 100;
				
				$total_tax=$total_tax + $tax_amount;
			}	
		}	
		$total_report_cost +=$report_cost;
		$diagnosis_total_tax +=$total_tax;
		$report_total_amount +=$report_cost+$total_tax;
	} 
	
	$array_var[] = $total_report_cost;
	$array_var[] = $diagnosis_total_tax;
	$array_var[] = $report_total_amount;
	
	echo json_encode($array_var);
	
	die;
}

//count diagnosis report amount
function MJ_hmgt_update_diagnosis_report_status_function()
{
	global $wpdb;	
    $table_prescription=$wpdb->prefix. 'hmgt_priscription';
	$pescription_id=$_POST['pescription_id'];
	$prescriptiondata['status']=$_POST['report_status'];
	$prescription_dataid['priscription_id']=$_POST['pescription_id'];
	$result=$wpdb->update( $table_prescription, $prescriptiondata ,$prescription_dataid);
	
	die;
}
//---------Invoice Charge Po pup More Entry HTML------------
function MJ_hmgt_hmgt_add_more_charge_entry()
{
	$counter = $_REQUEST['counter'];
	$window_width = $_REQUEST['window_width'];
	if($window_width > '991')
	{
		$margin='margin-bottom:0px';
	}
	else
	{
		$margin='margin-bottom:5px';
	}	
	?>
		<div class="row">
			<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5" style="<?php echo $margin;?>">	
				<input class="form-control" type="checkbox" name="newentry[newentry_<?php echo $counter; ?>]" value="<?php echo $counter; ?>" checked >
			</div>
			<div class="col-md-2 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5" style="<?php echo $margin;?>">	
			<select name="type[newentry_<?php echo $counter; ?>]" dataid="<?php echo $counter; ?>" id="type_<?php echo $counter; ?>" class="load_category more_invoice_charges dropdown_width_100_per" required>
					<option value=""><?php _e('Select Charge','hospital_mgt');?></option>
					<option value="Treatment Fees"><?php _e('Treatment Fees','hospital_mgt');?></option>
					<option value="Doctor Fees" ><?php _e('Doctor Fees','hospital_mgt');?></option>
					<option value="Operation Charges" ><?php _e('Operation Charges','hospital_mgt');?></option>
					<option value="Bed Charges" ><?php _e('Bed Charges','hospital_mgt');?></option>
					<option value="Nurse Charges" ><?php _e('Nurse Charges','hospital_mgt');?></option>
					<option value="Instrument Charges" ><?php _e('Instrument Charges','hospital_mgt');?></option>
					<option value="Ambulance Charges" ><?php _e('Ambulance Charges','hospital_mgt');?></option>
					<option value="Blood Charges" ><?php _e('Blood Charges','hospital_mgt');?></option>
					<?php
					$obj_invoice= new MJ_hmgt_invoice();
					$all_charge=$obj_invoice->get_all_invoice_charge();
					foreach($all_charge as $chargedata)
					{
						$charge_data=$chargedata->post_title;
						$charge_type_array=json_decode($charge_data);
					?>
						<option value="<?php echo $chargedata->ID;?>" ><?php echo $charge_type_array->category_name;?></option>
					<?php	
					}
					?>
				</select>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-12  align_center div_padding_right_0 div_padding_bottom_5" style="<?php echo $margin;?>">	
				<select id="title_<?php echo $counter; ?>" class="charge_amount_autofill dropdown_width_100_per" dataid="<?php echo $counter; ?>" style="width: 125px;" name="title[newentry_<?php echo $counter; ?>]" required>
					<option value=""><?php _e('Select Title','hospital_mgt');?></option>
				</select>
			</div>
			<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5" style="<?php echo $margin;?>">	
				<input id="date_<?php echo $counter; ?>"  class="form-control text-input invoice_date" Placeholder="<?php _e('Date','hospital_mgt');?>" type="text"  name="date[newentry_<?php echo $counter; ?>]" required>
			</div>
			<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5" style="<?php echo $margin;?>">
				<input id="unit_<?php echo $counter; ?>" class="form-control text-input" type="text" Placeholder="<?php _e('Unit','hospital_mgt');?>"  name="unit[newentry_<?php echo $counter; ?>]">
			</div>
			<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5" style="<?php echo $margin;?>">
				<input id="amount_<?php echo $counter; ?>" class="form-control text-input" type="number" min="0"  onKeyPress="if(this.value.length==10) return false;" step="0.01" Placeholder="<?php _e('Amount','hospital_mgt');?>"  name="amount[newentry_<?php echo $counter; ?>]" required>
			</div>
			<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5" style="<?php echo $margin;?>">	
				<input id="discount_amount_<?php echo $counter; ?>" class="form-control text-input transaction_discount_new_entry" dataid1="<?php echo $counter; ?>" type="number" min="0"  onKeyPress="if(this.value.length==10) return false;" step="0.01" Placeholder="<?php _e('Discount Amount','hospital_mgt');?>"  name="discount_amount[newentry_<?php echo $counter; ?>]">
			</div>
			<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5" style="<?php echo $margin;?>">	
				<input id="tax_amount_<?php echo $counter; ?>" class="form-control text-input" type="number" min="0"  onKeyPress="if(this.value.length==10) return false;" step="0.01" Placeholder="<?php _e('Tax Amount','hospital_mgt');?>"  name="tax_amount[newentry_<?php echo $counter; ?>]" >
			</div>
			<div class="col-md-1 col-sm-4 col-xs-12 div_padding_right_0 align_center div_padding_bottom_5" style="<?php echo $margin;?>">	
				<input id="total_amount_<?php echo $counter; ?>" class="form-control text-input" type="number" min="0"  onKeyPress="if(this.value.length==10) return false;" step="0.01" Placeholder="<?php _e('Total Amount','hospital_mgt');?>"  name="total_amount[newentry_<?php echo $counter; ?>]"  required>
			</div>
			<div class="col-md-1 col-sm-4 col-xs-12 align_center div_padding_bottom_5" style="<?php echo $margin;?>">	
				<button type="button" class="btn btn-default remove_transaction"><i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i></button>
			</div>				
		</div>						
	<?php 
	die();
}
//doctor specialization title
function MJ_hmgt_doctor_specialization_title($user_id)
{
	$specialization_data=get_user_meta($user_id, 'specialization',true);
	$specialization_title=get_the_title($specialization_data);
	return $specialization_title;
die();
}	
// Operation charge calculation //
function MJ_hmgt_operation_charge_calculation()
{
	$operation_id=$_REQUEST['operation_id'];
	global $wpdb;	
	$operation_cost = 0;
	$operation_tax = 0;
	$operation_total_amount = 0;
	
	$array_var = array();
	
	$post = get_post($operation_id);
	
	$operation_data=$post->post_title;
	$operation_type_array=json_decode($operation_data);
	$operation_cost =$operation_type_array->operation_cost;
	$operation_tax_array = explode(",",$operation_type_array->operation_tax);
	
	$total_tax=0;
	if(!empty($operation_id))
	{	
		if(!empty($operation_tax_array))
		{	
			foreach($operation_tax_array  as $tax_id)
			{				
				$tax_percentage=MJ_hmgt_tax_percentage_by_tax_id($tax_id);
				$tax_amount=$operation_cost * $tax_percentage / 100;
				
				$total_tax=$total_tax + $tax_amount;
			}	
		}	
		$operation_tax=$total_tax;
		$operation_total_amount=$operation_cost + $operation_tax;
	}	
	
	$array_var[] = $operation_cost;
	$array_var[] = $operation_tax;
	$array_var[] = $operation_total_amount;
	
	echo json_encode($array_var);
	
	die;
}	

//-------- -hmgt_uploads_dagnosisreport_table_formate ----------- //
function MJ_hmgt_uploads_dagnosisreport_table_formate()
{
	
	$report_type_id=$_REQUEST['report_type_id'];
	$action_name=$_REQUEST['action_name'];
	
	global $wpdb;
	$report_type=new MJ_hmgt_dignosis();
	
	if($action_name == 'insert')
	{
		$array_var ='<div class="form-group">		   
				<label class="col-sm-offset-2 col-sm-2 control-label upload_document_text_align"  for="document">'.__('Report Name','hospital_mgt').'</label>
				<label class="col-sm-3 control-label upload_document_text_align"  for="document">'.__('Upload Report ','hospital_mgt').'</label>
				<label class="col-sm-2 control-label upload_document_text_align"  for="document">'.__('Amount','hospital_mgt').'('.MJ_hmgt_get_currency_symbol().')</label>
					
			</div>';
			
		foreach($report_type_id  as $report_id)
		{
			$report_data=$report_type->get_report_by_id($report_id);
			$report_type_array=json_decode($report_data);
			$report_cost =$report_type_array->report_cost;
			$diagnosis_tax_array = explode(",",$report_type_array->diagnosis_tax);
			
			$total_tax=0;
		
			if(!empty($diagnosis_tax_array))
			{	
				foreach($diagnosis_tax_array  as $tax_id)
				{				
					$tax_percentage=MJ_hmgt_tax_percentage_by_tax_id($tax_id);
					$tax_amount=$report_cost * $tax_percentage / 100;
					
					$total_tax=$total_tax + $tax_amount;
				}	
			}
			$total_report_cost=$report_cost + $total_tax;
					
			 $report_name=$report_type_array->category_name;
		
			  $diagnosis_total_amount=$report_total_amount;
		
			$array_var .='<div class="form-group">
				
					<div class="col-sm-offset-2 col-sm-2">
					<input type="hidden" name="report_id[]" value="'.$report_id.'">
					<input type="text" class="form-control fronted_file report_name" style="text-align: center;" value='.$report_name.' name="report_name[]" readonly>
					</div>
					
					<div class="col-sm-3">
						<input type="file" class="form-control fronted_file document validate[required]" style="text-align: center;" name="document[]">
					</div>				
					<div class="col-sm-2">
					<input type="text" class="form-control fronted_file diagnosis_total_amount" style="text-align: center;" value='.$total_report_cost.' name="diagnosis_total_amount[]" readonly>
					</div>			 
				</div>';
		} 	
		echo $array_var;
	}
	else
	{
		$dignosis_report_data = $report_type->get_single_dignosis_report($_REQUEST['diagnosisid']);
		
		$array_var ='<div class="form-group">		   
				<label class="col-sm-offset-2 col-sm-2 control-label upload_document_text_align"  for="document">'.__('Report Name','hospital_mgt').'</label>
				<label class="col-sm-3 control-label upload_document_text_align"  for="document">'.__('Upload Report ','hospital_mgt').'</label>
				<label class="col-sm-2 control-label upload_document_text_align"  for="document">'.__('View Report','hospital_mgt').'</label>
				<label class="col-sm-1 control-label upload_document_text_align"  for="document">'.__('Amount','hospital_mgt').'('.MJ_hmgt_get_currency_symbol().')</label>
					
			</div>';
			
		foreach($report_type_id  as $report_id)
		{
			$report_data=$report_type->get_report_by_id($report_id);
			$report_type_array=json_decode($report_data);
			$report_cost =$report_type_array->report_cost;
			$diagnosis_tax_array = explode(",",$report_type_array->diagnosis_tax);
			$dignosis_array=json_decode($dignosis_report_data->attach_report);
			$total_tax=0;
		
			if(!empty($diagnosis_tax_array))
			{	
				foreach($diagnosis_tax_array  as $tax_id)
				{				
					$tax_percentage=MJ_hmgt_tax_percentage_by_tax_id($tax_id);
					$tax_amount=$report_cost * $tax_percentage / 100;
					
					$total_tax=$total_tax + $tax_amount;
				}	
			}
			$total_report_cost=$report_cost + $total_tax;
					
			$report_name=$report_type_array->category_name;
			
			$diagnosis_total_amount=$report_total_amount;
			$report_id_array=array();
			if(!empty($dignosis_report_data->attach_report))
			{
				foreach($dignosis_array as $key=>$value)
				{	
					$report_id_array[]=$value->report_id;					
				}
			}	
			if(in_array($report_id,$report_id_array))
			{			
				$validation='';
			}
			else
			{
				$validation='validate[required]';
			} 
			
			$array_var .='<div class="form-group">
				
					<div class="col-sm-offset-2 col-sm-2">
					<input type="hidden" name="report_id[]" value="'.$report_id.'">
					<input type="text" class="form-control fronted_file report_name" style="text-align: center;" value='.$report_name.' name="report_name[]" readonly>
					</div>					
					<div class="col-sm-3">
						<input type="file" class="form-control fronted_file document '.$validation.'" style="text-align: center;" name="document[]">
					</div>	
					<div class="col-sm-2">';					
			
						if(!empty($dignosis_report_data->attach_report))
						{
							foreach($dignosis_array as $key=>$value)
							{	
								if($value->report_id == $report_id)
								{
									$attch_report_url=$value->attach_report;
								
									$array_var .='<a href="'.content_url().'/uploads/hospital_assets/'.$attch_report_url.'" class="btn btn-default" target="_blank"><i class="fa fa-download"></i> '.$report_type_array->category_name.' '.__('Report','hospital_mgt').'</a>
									<input type="hidden" name="hidden_attach_report[]" value="'.$attch_report_url.'" >';
																
								}
							}
						}	
						
					$array_var .='</div>		
					<div class="col-sm-1">
						<input type="text" class="form-control fronted_file diagnosis_total_amount" style="text-align: center;" value='.$total_report_cost.' name="diagnosis_total_amount[]" readonly>
					</div>			 
				</div>';
		} 	
		echo $array_var;
	}
	
	die; 
}
//string is json or not check //
function MJ_hmgt_isJSON($string)
{
   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}
// auto fill up patient address in ambulance request //
function MJ_hmgt_patient_address()
{
	$patient_id=$_REQUEST['patient_id'];
	$patient_address = get_user_meta($patient_id, 'address',true);
	echo $patient_address;
	die;
}
//show event and notice view details pop-up //
function MJ_hmgt_show_event_notice()
{	
	$id = $_REQUEST['id'];	
	$model = $_REQUEST['model'];	
	if($model=='Event Details')
	{
		 $data = get_post($id);		
	}
	elseif($model=='Notice Details')
	{
		 $data = get_post($id);	
	}
	elseif($model=='Appointment Details')
	{
		$obj_appointment = new MJ_hmgt_appointment();
		$data = $obj_appointment->get_single_appointment($id);
	} 
	elseif($model=='Operation Details')
	{
		$obj_ot = new MJ_hmgt_operation();
		$data = $obj_ot->get_single_operation($id);
	} 
	elseif($model=='Prescription Details')
	{
		 $obj_var=new MJ_hmgt_prescription();
		 $obj_treatment=new MJ_hmgt_treatment();
		 $obj_medicine = new MJ_hmgt_medicine();
		$data = $obj_var->get_prescription_data($id);
	}
	elseif($model=='Assigned Bed Details')
	{
		$obj_bed = new MJ_hmgt_bedmanage();
		$data = $obj_bed->get_single_bedallotment($id);
	}
  ?>
     <div class="modal-header model_header_padding">
		<a href="#" class="event_close-btn badge badge-success pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title">
			<?php 
			if($model=='Event Details')
			{
				_e('Event Details','hospital_mgt');
			}
			elseif($model=='Notice Details')
			{
				_e('Notice Details','hospital_mgt'); 
			}
			elseif($model=='Appointment Details')
			{
				_e('Appointment Details','hospital_mgt'); 
			}
			elseif($model=='Operation Details')
			{
				_e('Operation Details','hospital_mgt'); 
			} 
			elseif($model=='Prescription Details')
			{
				_e('Prescription Details','hospital_mgt'); 
			} 
			elseif($model=='Assigned Bed Details')
			{
				_e('Assigned Bed Details','hospital_mgt'); 
			} 
			?>
		</h4>
	</div>
	<div class="panel panel-white">
	<?php
	if($model=='Event Details')
	{	
		$notice_for_array=explode(",",get_post_meta( $data->ID, 'notice_for',true));
	?>
		<div class="modal-body view_details_body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php _e('Event Title :','hospital_mgt'); ?></td>
						<td><?php echo $data->post_title; ?></td>
					</tr>
					<tr>
						<td><?php _e('Event Comment :','hospital_mgt'); ?></td>
						<td><?php echo $data->post_content; ?></td>
					</tr>
					<tr>
						<td><?php _e('Event For :','hospital_mgt'); ?></td>
						<td><?php echo MJ_hmgt_get_role_name_in_event($notice_for_array); ?></td>
					</tr>			
					<tr>
						<td><?php _e('Event Start Date :','hospital_mgt'); ?></td>
						<td><?php echo get_post_meta($data->ID,'start_date',true); ?></td>
					</tr>
					<tr>
						<td><?php _e('Event End Date :','hospital_mgt'); ?></td>
						<td><?php echo get_post_meta($data->ID,'end_date',true); ?></td>
					</tr>				
				</tbody>
			</table>
        </div>  		
	<?php
	}
	elseif($model=='Notice Details')
	{
		$notice_for_array=explode(",",get_post_meta( $data->ID, 'notice_for',true));
	?>
		<div class="modal-body view_details_body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php _e('Notice Title :','hospital_mgt'); ?></td>
						<td><?php echo $data->post_title; ?></td>
					</tr>
					<tr>
						<td><?php _e('Notice Comment :','hospital_mgt'); ?></td>
						<td><?php echo $data->post_content; ?></td>
					</tr>
					<tr>
						<td><?php _e('Notice For :','hospital_mgt'); ?></td>
						<td><?php echo MJ_hmgt_get_role_name_in_event($notice_for_array); ?></td>
					</tr>			
					<tr>
						<td><?php _e('Notice Start Date :','hospital_mgt'); ?></td>
						<td><?php echo get_post_meta($data->ID,'start_date',true); ?></td>
					</tr>
					<tr>
						<td><?php _e('Notice End Date :','hospital_mgt'); ?></td>
						<td><?php echo get_post_meta($data->ID,'end_date',true); ?></td>
					</tr>				
				</tbody>
			</table>
        </div>  		
	<?php	
	}
	elseif($model=='Appointment Details')
	{
	?>
		<div class="modal-body view_details_body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php _e('Patient Name :','hospital_mgt'); ?></td>
						<td>
							<?php 
							$patient_data =	MJ_hmgt_get_user_detail_byid($data->patient_id);
							echo $patient_data['first_name']." ".$patient_data['last_name'];
							?>
						</td>
					</tr>
					<tr>
						<td><?php _e('Doctor Name :','hospital_mgt'); ?></td>
						<td> 
						<?php $doctor_data = MJ_hmgt_get_user_detail_byid($data->doctor_id);
						echo $doctor_data['first_name']." ".$doctor_data['last_name']; ?>
					</td>
					</tr>
					<tr>
						<td><?php _e('Appointment Date :','hospital_mgt'); ?></td>
						<td>
							<?php 
							echo date(MJ_hmgt_date_formate(),strtotime($data->appointment_date)); 
							?>	
						</td>
					</tr>			
					<tr>
						<td><?php _e('Appointment Time :','hospital_mgt'); ?></td>
						<td><?php echo MJ_hmgt_appoinment_time_language_translation($data->appointment_time_with_a); ?></td>
					</tr>								
				</tbody>
			</table>
        </div>  		
	<?php	
	}
	elseif($model=='Operation Details')
	{
	?>
		<div class="modal-body view_details_body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php _e('Patient Name :','hospital_mgt'); ?></td>
						<td>
							<?php 
							$patient_data =	MJ_hmgt_get_user_detail_byid($data->patient_id);
							echo $patient_data['first_name']." ".$patient_data['last_name']; 
							?>
						</td>
					</tr>
					<tr>
						<td><?php _e('Doctor Name :','hospital_mgt'); ?></td>
						<td> 
						<?php 
							$surgenlist =  $obj_ot->get_doctor_by_oprationid($data->operation_id) ;
							$surgenlist_names = '';
							foreach($surgenlist as $assign_id)
							{
								$doctory_data =	MJ_hmgt_get_user_detail_byid($assign_id->child_id);
							  $surgenlist_names.= $doctory_data['first_name']." ".$doctory_data['last_name'].",";
							}
							echo rtrim($surgenlist_names, ',');
						?>
					</td>
					</tr>
					<tr>
						<td><?php _e('Operation name :','hospital_mgt'); ?></td>
						<td> 
						<?php echo $obj_ot->get_operation_name($data->operation_title);?>
					</td>
					</tr>
					<tr>
						<td><?php _e('Operation Date :','hospital_mgt'); ?></td>
						<td>
							<?php  echo date(MJ_hmgt_date_formate(),strtotime($data->operation_date));	?>
						</td>
					</tr>			
					<tr>
						<td><?php _e('Operation Time :','hospital_mgt'); ?></td>
						<td><?php echo $data->operation_time; ?></td>
					</tr>								
				</tbody>
			</table>
        </div>  		
	<?php	
	}
	elseif($model=='Prescription Details')
	{
	?>
		<div class="modal-body view_details_body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php _e('Date :','hospital_mgt'); ?></td>
						<td> 
						<?php  echo date(MJ_hmgt_date_formate(),strtotime($data->pris_create_date));?>
					</td>
					</tr>
					<tr>
						<td><?php _e('Patient Name :','hospital_mgt'); ?></td>
						<td>
							<?php 
							$patient_data =	MJ_hmgt_get_user_detail_byid($data->patient_id);
							echo $patient_data['first_name']." ".$patient_data['last_name']; 
							?>
						</td>
					</tr>
					<tr>
						<td><?php _e('Doctor Name :','hospital_mgt'); ?></td>
						<td> 
						<?php 
							$doctor_data =	MJ_hmgt_get_user_detail_byid($data->doctor_id);
							echo $doctor_data['first_name']." ".$doctor_data['last_name']; 
						?>
					</td>
					</tr>					
					<?php
					if($data->prescription_type=='report')
					{		
					?>
					<tr>
						<td><?php _e('Report Type :','hospital_mgt'); ?></td>
						<td>
						<?php
						$report_type=new MJ_hmgt_dignosis();
						$report_type_data=explode(",",$data->report_type);
						$reporttype_array=array();
						if(!empty($data->report_type))
						{	  
							foreach ($report_type_data as $report_id)
							{
								$report_data=$report_type->get_report_by_id($report_id);
								$report_type_array=json_decode($report_data);
								$reporttype_array[]=$report_type_array->category_name;
							}
							echo implode(",",$reporttype_array);
						}
						?>
					</td>
					</tr>
					<tr>
						<td><?php _e('Report Description :','hospital_mgt'); ?></td>
						<td>
							<?php  echo $data->report_description;	?>
						</td>
					</tr>
					<?php	
					}
					else
					{
					?>	
					<tr>
						<td><?php _e('Treatment :','hospital_mgt'); ?></td>
						<td> 
						<?php echo $obj_treatment->get_treatment_name($data->teratment_id); ?>
					</td>
					</tr>
					<tr>
						<td><?php _e('Case History :','hospital_mgt'); ?></td>
						<td>
							<?php  echo $data->case_history; ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<?php _e('Medication :','hospital_mgt'); ?>
						<br>
						<table class="table table-striped " cellspacing="0" width="100%" style="margin-bottom:0px;">
							<thead>
								<tr>
									<th><?php _e('Medicine Name','hospital_mgt'); ?></th>
									<th><?php _e('Frequency Of Medicine','hospital_mgt'); ?></th>
									<th><?php _e('Time Period','hospital_mgt'); ?></th>
									<th align="left"><?php _e('When Take','hospital_mgt'); ?></th>
								</tr>                                 
							</thead>
							<tbody>
							<?php 
								$all_medicine_list=json_decode($data->medication_list);
								if(!empty($all_medicine_list))
								{
									foreach($all_medicine_list as $retrieved_data)
									{
									?>	
										<tr>
											<td>
											<?php 
												$medicine=$obj_medicine->get_single_medicine($retrieved_data->medication_name);
												echo $medicine->medicine_name; 
											?>
											</td>
											<td><?php echo $retrieved_data->time; ?></td>
											<td><?php echo $retrieved_data->per_days; ?> <?php echo $retrieved_data->time_period; ?></td>
											<td><?php echo MJ_hmgt_get_medicine_take_timelabel($retrieved_data->takes_time); ?></td>
										</tr>									
									<?php	
									}
								}                       
								?>			
								
							</tbody>
						</table>
						</td>						
					</tr>	
					<tr>
						<td><?php _e('Note :','hospital_mgt'); ?></td>
						<td><?php echo $data->treatment_note; ?></td>
					</tr>
					<?php
					}											
					?>													
				</tbody>
			</table>
        </div>  		
	<?php	
	}
	elseif($model=='Assigned Bed Details')
	{
	?>
		<div class="modal-body view_details_body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php _e('Patient Name :','hospital_mgt'); ?></td>
						<td>
							<?php
							$patient_data =	MJ_hmgt_get_user_detail_byid($data->patient_id);							
							echo $patient_data['first_name']." ".$patient_data['last_name']; 
							?>
						</td>
					</tr>
					<tr>
						<td><?php _e('Nurse Name :','hospital_mgt'); ?></td>
						<td> 
						<?php 
							$nurselist =  $obj_bed->get_nurse_by_assignid($data->bed_allotment_id) ;
							foreach($nurselist as $assign_id)
							{
								$nurse_data =	MJ_hmgt_get_user_detail_byid($assign_id->child_id);
								echo $nurse_data['first_name']." ".$nurse_data['last_name'].",";
								
							}
						?>
					</td>
					</tr>
					<tr>
						<td><?php _e('Bed Category :','hospital_mgt'); ?></td>
						<td> 
						<?php echo $obj_bed->get_bedtype_name($data->bed_type_id);	?>
					</td>
					</tr>
					<tr>
						<td><?php _e('Bed Number :','hospital_mgt'); ?></td>
						<td>
							<?php echo $obj_bed->get_bed_number($data->bed_number);?>
						</td>
					</tr>			
					<tr>
						<td><?php _e('Allotment Date :','hospital_mgt'); ?></td>
						<td><?php  echo date(MJ_hmgt_date_formate(),strtotime($data->allotment_date));?></td>
					</tr>	
					<tr>
						<td><?php _e('Discharge Date :','hospital_mgt'); ?></td>
						<td><?php  echo date(MJ_hmgt_date_formate(),strtotime($data->discharge_time));?></td>
					</tr>						
				</tbody>
			</table>
        </div>  		
	<?php	
	}	
	?>	
  	</div>
	<?php   
	die();	 
}
//appointment time language translation
function MJ_hmgt_appoinment_time_language_translation($value)
{		
	if (strpos($value, 'AM') !== false) 
	{
		$translated_value=str_replace('AM',__('AM','hospital_mgt'),$value);
	}
	else
	{
		$translated_value=str_replace('PM',__('PM','hospital_mgt'),$value);
	}
	return $translated_value; 	
	die();	
}	
?>