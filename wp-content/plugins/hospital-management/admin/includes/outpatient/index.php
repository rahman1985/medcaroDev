<?php 
MJ_hmgt_browser_javascript_check();
$role='outpatient';
$id=0;
$user_object=new MJ_hmgt_user();
$blood_obj=new MJ_hmgt_bloodbank();
$valtemp=0;
?>
<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'outpatientlist';
?>
<!-- POP up code -->
<div class="popup-bg" >
    <div class="overlay-content">
		<div class="modal-content">
		   <div class="patient_data"></div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
<!-- Page inner Div START-->
<div class="page-inner" style="min-height:1631px !important">
    <!-- Page title Div START-->
    <div class="page-title">
		<h3><img src="<?php echo get_option('hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div><!-- Page title Div END-->
	<?php 
	//export Outpatient in csv
	if(isset($_POST['export_csv']))
	{		
		$get_patient = array('role' => 'patient','meta_key'=>'patient_type','meta_value'=>'outpatient');
		$patient_list=get_users($get_patient);										
		
		if(!empty($patient_list))
		{
			$header = array();			
			$header[] = 'Username';
			$header[] = 'Email';
			$header[] = 'Password';
			$header[] = 'patient_id';
			$header[] = 'first_name';
			$header[] = 'middle_name';
			$header[] = 'last_name';			
			$header[] = 'gender';
			$header[] = 'birth_date';
			$header[] = 'blood_group';				
			$header[] = 'address';
			$header[] = 'city_name';
			$header[] = 'state_name';
			$header[] = 'country_name';
			$header[] = 'zip_code';
			$header[] = 'phonecode';
			$header[] = 'mobile';
			$header[] = 'phone';	
			$header[] = 'patient_type';	
			
			$document_dir = WP_CONTENT_DIR;
			$document_dir .= '/uploads/export/';
			$document_path = $document_dir;
			if (!file_exists($document_path))
			{
				mkdir($document_path, 0777, true);		
			}
			
			$filename=$document_path.'export_outpatients.csv';
			$fh = fopen($filename, 'w') or die("can't open file");
			fputcsv($fh, $header);
			foreach($patient_list as $retrive_data)
			{
				$row = array();
				$user_info = get_userdata($retrive_data->ID);
				
				$row[] = $user_info->user_login;
				$row[] = $user_info->user_email;			
				$row[] = $user_info->user_pass;			
			
				$row[] =  get_user_meta($retrive_data->ID, 'patient_id',true);
				$row[] =  get_user_meta($retrive_data->ID, 'first_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'middle_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'last_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'gender',true);
				$row[] =  get_user_meta($retrive_data->ID, 'birth_date',true);
				$row[] =  get_user_meta($retrive_data->ID, 'blood_group',true);					
				$row[] =  get_user_meta($retrive_data->ID, 'address',true);					
				$row[] =  get_user_meta($retrive_data->ID, 'city_name',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'state_name',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'country_name',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'zip_code',true);			
				$row[] =  get_user_meta($retrive_data->ID, 'phonecode',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'mobile',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'phone',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'patient_type',true);				
								
				fputcsv($fh, $row);
				
			}
			fclose($fh);
	
			//download csv file.
			ob_clean();
			$file=$document_path.'export_outpatients.csv';//file location
			
			$mime = 'text/plain';
			header('Content-Type:application/force-download');
			header('Pragma: public');       // required
			header('Expires: 0');           // no cache
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
			header('Cache-Control: private',false);
			header('Content-Type: '.$mime);
			header('Content-Disposition: attachment; filename="'.basename($file).'"');
			header('Content-Transfer-Encoding: binary');			
			header('Connection: close');
			readfile($file);		
			exit;				
		}
		else
		{
			?>
			<div class="alert_msg alert alert-danger alert-dismissible fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<?php _e('Records not found.','hospital_mgt');?>
			</div>
			<?php	
		}		
	}
	//upload Outpatient csv	
	if(isset($_REQUEST['upload_csv_file']))
	{	
		if(isset($_FILES['csv_file']))
		{			
			$errors= array();
			$file_name = $_FILES['csv_file']['name'];
			$file_size =$_FILES['csv_file']['size'];
			$file_tmp =$_FILES['csv_file']['tmp_name'];
			$file_type=$_FILES['csv_file']['type'];

			$value = explode(".", $_FILES['csv_file']['name']);
			$file_ext = strtolower(array_pop($value));
			$extensions = array("csv");
			$upload_dir = wp_upload_dir();
			if(in_array($file_ext,$extensions )=== false){
				$errors[]="this file not allowed, please choose a CSV file.";
				wp_redirect ( admin_url().'admin.php?page=hmgt_outpatient&tab=outpatientlist&message=5');
			}
			if($file_size > 2097152)
			{
				$errors[]='File size limit 2 MB';
				wp_redirect ( admin_url().'admin.php?page=hmgt_outpatient&tab=outpatientlist&message=6');
			}
			
			if(empty($errors)==true)
			{	
				
				$rows = array_map('str_getcsv', file($file_tmp));		
			
				$header = array_map('strtolower',array_shift($rows));
					
				$csv = array();
				foreach ($rows as $row) 
				{	
					$header_size=sizeof($header);
					$row_size=sizeof($row);
					if($header_size == $row_size)
					{
						$csv = array_combine($header, $row);
						
						$username = $csv['username'];
						$email = $csv['email'];
						$user_id = 0;
						
						$password = $csv['password'];
						
						$problematic_row = false;
						
						if( username_exists($username) )
						{ // if user exists, we take his ID by login
							$user_object = get_user_by( "login", $username );
							$user_id = $user_object->ID;
						
							if( !empty($password) )
								wp_set_password( $password, $user_id );
						}
						elseif( email_exists( $email ) )
						{ // if the email is registered, we take the user from this
							$user_object = get_user_by( "email", $email );
							$user_id = $user_object->ID;					
							$problematic_row = true;
						
							if( !empty($password) )
								wp_set_password( $password, $user_id );
						}
						else{
							if( empty($password) ) // if user not exist and password is empty but the column is set, it will be generated
								$password = wp_generate_password();
						
							$user_id = wp_create_user($username, $password, $email);
						}
						
						if( is_wp_error($user_id) )
						{ // in case the user is generating errors after this checks
							echo '<script>alert("'.__('Problems with user','hospital_mgt').'" : "'.__($username,'hospital_mgt').'","'.__('we are going to skip','hospital_mgt').'");</script>';
							continue;
						}

						if(!( in_array("administrator", MJ_hmgt_get_roles($user_id), FALSE) || is_multisite() && is_super_admin( $user_id ) ))
							
							wp_update_user(array ('ID' => $user_id, 'role' => 'patient')) ;
						
						if(isset($csv['patient_id']))
							update_user_meta( $user_id, "patient_id", $csv['patient_id'] );
						if(isset($csv['first_name']))
							update_user_meta( $user_id, "first_name", $csv['first_name'] );
						if(isset($csv['middle_name']))
							update_user_meta( $user_id, "middle_name", $csv['middle_name'] );
						if(isset($csv['last_name']))
							update_user_meta( $user_id, "last_name", $csv['last_name'] );
						if(isset($csv['gender']))
							update_user_meta( $user_id, "gender", $csv['gender'] );
						if(isset($csv['birth_date']))
							update_user_meta( $user_id, "birth_date",$csv['birth_date']);
						
						if(isset($csv['blood_group']))
							update_user_meta( $user_id, "blood_group",$csv['blood_group']);

						if(isset($csv['address']))
							update_user_meta( $user_id, "address", $csv['address'] );
						
						if(isset($csv['city_name']))
							update_user_meta( $user_id, "city_name", $csv['city_name'] );
						if(isset($csv['state_name']))
							update_user_meta( $user_id, "state_name", $csv['state_name'] );
						if(isset($csv['country_name']))
							update_user_meta( $user_id, "country_name", $csv['country_name'] );
						if(isset($csv['zip_code']))
							update_user_meta( $user_id, "zip_code", $csv['zip_code'] );
						if(isset($csv['phonecode']))
							update_user_meta( $user_id, "phonecode", $csv['phonecode'] );
						if(isset($csv['mobile']))
							update_user_meta( $user_id, "mobile", $csv['mobile'] );
						if(isset($csv['phone']))
							update_user_meta( $user_id, "phone", $csv['phone'] );	
						if(isset($csv['patient_type']))
							update_user_meta( $user_id, "patient_type", $csv['patient_type'] );
						$success = 1;	
					}
					else
					{
						wp_redirect ( admin_url().'admin.php?page=hmgt_outpatient&tab=outpatientlist&message=7');						
					}
				}
			}
			else
			{
				foreach($errors as &$error) echo $error;
			}
			if(isset($success))
			{
			?>
			<div id="message" class="updated below-h2">
				<p><?php _e('Outpatients CSV Successfully Uploaded.','hospital_mgt');?></p>
			</div>
			<?php
			} 
		}
	}
	if(isset($_POST['save_outpatient']))
	{		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='insert')
		{
			//multiple dignosis insert
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
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) 
			{
				$txturl=$_POST['hmgt_user_avatar'];
				  
			    $ext=MJ_hmgt_check_valid_extension($txturl);
			    if(!$ext == 0)
			    {
					$diagnosis_report_url=$upload_dignosis_array;
					$ext1=MJ_hmgt_check_valid_file_extension_for_diagnosis($diagnosis_report_url);
					if($ext1 == 0 )
					{
						$result=$user_object->hmgt_add_user($_POST);
						if($result)
						{
							$guardian_data=array('patient_id'=>$result,
									'doctor_id'=>$_POST['doctor'],
									'symptoms'=>implode(",",$_POST['symptoms']),
									'inpatient_create_date'=>date("Y-m-d H:i:s"),'inpatient_create_by'=>get_current_user_id()
							);
							$inserted=MJ_hmgt_add_guardian($guardian_data,$id);
							$user_object->upload_multiple_diagnosis_report($result,$upload_dignosis_array);
							if($inserted)
							{
								wp_redirect ( admin_url() . 'admin.php?page=hmgt_outpatient&tab=outpatientlist&message=1');
							}
						}
				    }						
					else
					{ 
						?>
						<div id="message" class="updated below-h2">
							<p><p><?php _e('Sorry, only JPG,JPEG,PNG,GIF,DOC,PDF and ZIP files are allowed.','hospital_mgt'); ?></p></p>
						</div><?php 
					} 
				}					 
				else
				{
				?>
					<div id="message" class="updated below-h2">
						<p><p><?php _e('Sorry, only JPG, JPEG, PNG & GIF files are allowed.','hospital_mgt');?></p></p>
					</div>
				<?php				
				}
			}
			else
			{
			?>
				<div id="message" class="updated below-h2">
				<p><p><?php _e('Username Or Emailid Already Exist.','hospital_mgt');?></p></p>
				</div>
			<?php 
			}		
		}
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			//multiple dignosis upload
			$upload_dignosis_array=array();
			$not_upload_dignosis_array=array();
		
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
						else
						{
							if(!empty($_POST['hidden_attach_report'][$a]))
							{
								$not_upload_dignosis_array[$a]=$_POST['hidden_attach_report'][$a];
							}	
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
			$upload_array_merge=array_merge($upload_dignosis_array,$not_upload_dignosis_array);

			$txturl=$_POST['hmgt_user_avatar'];
			$ext=MJ_hmgt_check_valid_extension($txturl);
			if(!$ext == 0)
			{			   
			   $ext1=MJ_hmgt_check_valid_file_extension_for_diagnosis($upload_array_merge);
			   if($ext1 == 0 )
			   {					   						   
					$result=$user_object->hmgt_add_user($_POST);
					$guardian_data=array('patient_id'=>$_REQUEST['outpatient_id'],
								'symptoms'=>implode(",",$_POST['symptoms']),
								'doctor_id'=>$_POST['doctor'],
								'inpatient_create_date'=>date("Y-m-d H:i:s"),'inpatient_create_by'=>get_current_user_id()
								);
												
					$result1=MJ_hmgt_update_guardian($guardian_data,$_REQUEST['outpatient_id']);
					
					$outpatient_id=0;
					if(isset($_POST['patient_convert']))
					{
						$outpatient_id=$_REQUEST['outpatient_id'];
					}
					global $wpdb;
					$table_diagnosis = $wpdb->prefix. 'hmgt_diagnosis';
					$result_delete_dignosis = $wpdb->query("DELETE FROM $table_diagnosis where report_cost IS NULL AND patient_id = ".$_REQUEST['outpatient_id']);
					
					$returnans=$user_object->upload_multiple_diagnosis_report($_REQUEST['outpatient_id'],$upload_array_merge);					
				}				  
				else
				{						  
				  ?>
					<div id="message" class="updated below-h2">
					<p><p><?php _e('Sorry, only JPG,JPEG,PNG,GIF,DOC,PDF and ZIP files are allowed.','hospital_mgt'); ?></p></p>
					</div>
					<?php 
				}					
			}					
			else
			{	
			?>
				<div id="" class="updated below-h2">
					<p><p><?php _e('Sorry, only JPG, JPEG, PNG & GIF files are allowed.','hospital_mgt');?></p></p>
				</div>
			<?php 
			}	
			if(isset($result) ||isset($result1) || isset($returnans))
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_outpatient&tab=outpatientlist&message=2&outpatient_id='.$outpatient_id);
			}
		}
	}
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
			{
				
				$result=$user_object->delete_usedata($_REQUEST['outpatient_id']);
				$result=MJ_hmgt_delete_guardian($_REQUEST['outpatient_id']);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_outpatient&tab=outpatientlist&message=3');
					
				}
			}
		?>
		<?php 
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'active_patient')
	{
		$out_patient_id = $_REQUEST['outpatient_id'];
		delete_user_meta($out_patient_id, 'hmgt_hash');
		$user_info = get_userdata($_REQUEST['outpatient_id']);
		$to = $user_info->user_email; 
		$patientname=$user_info->display_name;
		$login_link=home_url();
		$subject =get_option('MJ_hmgt_patient_approved_subject'); 
		$hospital_name = get_option('hmgt_hospital_name');
		$sub_arr['{{Hospital Name}}']=$hospital_name;
	    $subject = MJ_hmgt_subject_string_replacemnet($sub_arr,$subject);
		$search=array('{{Patient Name}}','{{Hospital Name}}','{{Login Link}} ');
		$replace = array($patientname,$hospital_name,$login_link);
		$message_replacement = str_replace($search, $replace,get_option('Patient_Approved_Email_Template'));	
		 MJ_hmgt_send_mail($to,$subject,$message_replacement);	 
		
		wp_redirect ( admin_url() . 'admin.php?page=hmgt_outpatient&tab=outpatientlist&message=4');
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
		{
			if(isset($_REQUEST['outpatient_id']))
				$valtemp=$_REQUEST['outpatient_id'];
			
			?><div id="message" class="updated below-h2 "><p><?php
					_e("Record updated successfully.",'hospital_mgt');
					?></p>
					</div>
				<?php 
			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Record deleted successfully','hospital_mgt');
		?></div></p><?php
				
		}
		elseif($message == 4) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Patient Actived Successfully','hospital_mgt');
		?></div></p><?php
				
		}
		elseif($message == 5) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Only CSV file are allow.','hospital_mgt');
		?></div></p><?php
				
		}
		
		elseif($message == 6) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('File size limit 2 MB allow.','hospital_mgt');
		?></div></p><?php
				
		}
		elseif($message == 7) 
		{?>
			<div id="message" class="updated below-h2"><p>
			<?php 
				_e('This file formate not proper.Please select CSV file with proper formate.','hospital_mgt');
			?></p></div>
			<?php				
		}
	}
	?>
	<!-- main-wrapper DIV START-->
	<div id="main-wrapper">
	<!--    row DIV START-->
		<div class="row">
			<div class="col-md-12">
			    <!--    PANAL white DIV START-->
				<div class="panel panel-white">
				     <!--    PANAL BODY DIV START -->	
					<div class="panel-body">
						<h2 class="nav-tab-wrapper">
							<a href="?page=hmgt_outpatient&tab=outpatientlist" class="nav-tab <?php echo $active_tab == 'outpatientlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Outpatient List', 'hospital_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=hmgt_outpatient&tab=addoutpatient&&action=edit&outpatient_id=<?php echo $_REQUEST['outpatient_id'];?>" class="nav-tab <?php echo $active_tab == 'addoutpatient' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Outpatient', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_outpatient&tab=addoutpatient" class="nav-tab <?php echo $active_tab == 'addoutpatient' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add New Outpatient', 'hospital_mgt'); ?></a>  
							
							<?php  }?>
						   
						</h2>
						<?php 
						if($active_tab == 'outpatientlist')
						{
						 ?>
							<script>
							jQuery(document).ready(function() {
							jQuery('#outpatient_list').DataTable({ 
							"responsive": true,
								"order": [[ 1, "asc" ]],
								"aoColumns":[
											  {"bSortable": false},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bVisible": true},
											  {"bVisible": true},
											  {"bVisible": true},
											  {"bSortable": false}
										   ],
								language:<?php echo MJ_hmgt_datatable_multi_language();?>
							});
							var tempval=<?php echo $valtemp;?>;
								if(tempval!=0){
								swal({
												title: "Outpatient successfully converted to inpatient!",
												text: "Do you Want to Admit this patient?",
												type: "warning",
												showCancelButton: true,
												confirmButtonColor: '#22baa0',
												confirmButtonText: 'Yes',
												cancelButtonText: "No",
												closeOnConfirm: false,
												closeOnCancel: true
											},
												function(isConfirm){
												if (isConfirm){
													window.location.href = "<?php echo admin_url().'admin.php?page=hmgt_patient&tab=addpatient_step2&action=edit&patient_id='.$valtemp; ?>";
												} else {
													tempval=0;
												 window.location.href = "<?php echo admin_url() . 'admin.php?page=hmgt_outpatient&tab=outpatientlist&message=2';?>";
												}
											});
								}
							
						} );
						</script>	
						<form name="wcwm_report" action="" method="post">
							<!--    PANAL BODY DIV START -->	
							<div class="panel-body">
							<input type="submit" value="<?php _e('Export CSV','hospital_mgt');?>" name="export_csv" class="btn btn-success margin_bottom_5px"/> 
							<input type="button" value="<?php _e('Import CSV','hospital_mgt');?>" name="import_csv" class="btn btn-success importdata margin_bottom_5px"/> 
								<div class="table-responsive">
									<table id="outpatient_list" class="display" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
												 <th><?php _e( 'Patient Id', 'hospital_mgt' ) ;?></th>           
												 <th> <?php _e( 'Mobile Number', 'hospital_mgt' ) ;?></th>
												 <th> <?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
												 <th> <?php _e( 'Email', 'hospital_mgt' ) ;?></th>
												 <th> <?php _e( 'Assigned Doctor Name', 'hospital_mgt' ) ;?></th>
												 <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
												<th><?php _e('Patient Id','hospital_mgt'); ?></th>				
												<th> <?php _e( 'Mobile Number', 'hospital_mgt' ) ;?></th>
												<th> <?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
												<th> <?php _e( 'Email', 'hospital_mgt' ) ;?></th>
												 <th> <?php _e( 'Assigned Doctor Name', 'hospital_mgt' ) ;?></th>
												<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
											</tr>
										</tfoot>
							 
										<tbody>
										<?php 
										$get_patient = array('role' => 'patient','meta_key'=>'patient_type','meta_value'=>'outpatient');
										$patientdata=get_users($get_patient);
										if(!empty($patientdata))
										{
										  foreach ($patientdata as $retrieved_data){

												$doctordetail=MJ_hmgt_get_guardianby_patient($retrieved_data->ID);
												$doctor = get_userdata($doctordetail['doctor_id']);												
											?>
											<tr>
												<td class="user_image"><?php $uid=$retrieved_data->ID;
															$userimage=get_user_meta($uid, 'hmgt_user_avatar', true);
																if(empty($userimage))
																	{
																		echo '<img src='.get_option( 'hmgt_patient_thumb' ).' height="50px" width="50px" class="img-circle" />';
																	}
															else
															echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
												?></td>
												<td class="name"><a href="?page=hmgt_outpatient&tab=addoutpatient&action=edit&outpatient_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->display_name;?></a></td>
												<td class="patient_id">
												<?php 
														echo get_user_meta($uid, 'patient_id', true);
												?></td>
												<td class="phone"><?php echo get_user_meta($uid, 'mobile', true);?></td>
												<td class="email"><?php echo get_user_meta($uid, 'blood_group', true);?></td>
												<td class="email"><?php echo $retrieved_data->user_email;?></td>
												<td class=""><?php if(!empty($doctor)) { echo $doctor->display_name; }?></td>

												<td class="action"> 
												<?php 
												if( !get_user_meta($retrieved_data->ID, 'hmgt_hash', true))
												 {
												?>
													<a href="?page=hmgt_invoice&tab=addinvoice&patient=<?php echo $retrieved_data->ID; ?>" class="btn btn-default"> <?php _e('Billing', 'hospital_mgt' );?></a>
													<a href="?page=hmgt_outpatient&tab=addoutpatient&action=edit&outpatient_id=<?php echo $retrieved_data->ID; ?>" class="btn btn-default"> <?php _e('Admit', 'hospital_mgt' );?></a>
													<a  href="?page=hmgt_outpatient&action=view_status&outpatient_id=<?php echo $retrieved_data->ID;?>" class="show-popup btn btn-default" idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php _e('View Detail', 'hospital_mgt');?></a>
													<a  href="?page=hmgt_outpatient&action=view_status&patient_id=<?php echo $retrieved_data->ID;?>" class="show-charges-popup btn btn-default" idtest="<?php echo $retrieved_data->ID; ?>">
													<i class="fa fa-money"></i> <?php _e('Charges', 'hospital_mgt');?></a>
													
													<a href="?page=hmgt_outpatient&tab=addoutpatient&action=edit&outpatient_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
													<a href="?page=hmgt_outpatient&tab=outpatientlist&action=delete&outpatient_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
													<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
											 <?php }
												else
												{
													?>
														<a  href="?page=hmgt_outpatient&action=active_patient&outpatient_id=<?php echo $retrieved_data->ID;?>" class="btn btn-default" > <?php _e('Active', 'hospital_mgt');?></a>
													<?php
												}?>
												</td>
											   
											</tr>
											<?php } 
											
										}?>
									 
										</tbody>
									</table>
								</div>
							</div>							
							<!--  END PANAL BODY DIV -->					   
						</form>
						 <?php 
						}
						if($active_tab == 'addoutpatient')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/outpatient/add_out_patient.php';
						}						 
						?>
                    </div>
					<!-- END PANAL BODY DIV -->
			
		        </div>
				<!-- PANAL white DIV -->
	        </div>
        </div>
		<!--END ROW DIV -->
	</div>
	<!-- END main-wrapper DIV -->