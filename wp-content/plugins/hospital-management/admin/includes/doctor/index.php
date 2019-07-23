<?php 
$role='doctor';
$user_object=new MJ_hmgt_user();
?>
<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'doctorlist';
?>
<!-- POP up code -->
<div class="popup-bg" >
    <div class="overlay-content">
		<div class="modal-content">
			 <div class="category_list">
			 </div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code START-->
<div class="page-inner" style="min-height:1631px !important">
    <!-- Page Title Code START-->
    <div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div>
	<!-- End Page Title Code END-->
	<?php 
	//export Doctor in csv
	if(isset($_POST['export_csv']))
	{		
		$doctor_list = get_users(array('role'=>'doctor'));
		
		if(!empty($doctor_list))
		{
			$header = array();			
			$header[] = 'Username';
			$header[] = 'Email';
			$header[] = 'Password';
			$header[] = 'first_name';
			$header[] = 'middle_name';
			$header[] = 'last_name';			
			$header[] = 'gender';
			$header[] = 'birth_date';
			$header[] = 'department';
			$header[] = 'specialization';
			$header[] = 'doctor_degree';
			$header[] = 'visiting_fees';			
			$header[] = 'consulting_fees';			
			$header[] = 'office_address';
			$header[] = 'city_name';
			$header[] = 'state_name';
			$header[] = 'country_name';
			$header[] = 'zip_code';
			$header[] = 'address';
			$header[] = 'home_city';
			$header[] = 'home_state';
			$header[] = 'home_country';
			$header[] = 'home_zip_code';
			$header[] = 'phonecode';
			$header[] = 'mobile';
			$header[] = 'phone';	
			
			$document_dir = WP_CONTENT_DIR;
			$document_dir .= '/uploads/export/';
			$document_path = $document_dir;
			if (!file_exists($document_path))
			{
				mkdir($document_path, 0777, true);		
			}
			
			$filename=$document_path.'export_doctors.csv';
			$fh = fopen($filename, 'w') or die("can't open file");
			fputcsv($fh, $header);
			foreach($doctor_list as $retrive_data)
			{
				$row = array();
				$user_info = get_userdata($retrive_data->ID);
				
				$row[] = $user_info->user_login;
				$row[] = $user_info->user_email;			
				$row[] = $user_info->user_pass;			
			
				$row[] =  get_user_meta($retrive_data->ID, 'first_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'middle_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'last_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'gender',true);
				$row[] =  get_user_meta($retrive_data->ID, 'birth_date',true);
				$department_id=get_user_meta($retrive_data->ID, 'department',true);
				$department_name=get_the_title($department_id);
				$specialization_id=get_user_meta($retrive_data->ID, 'specialization',true);
				$specialization_name=get_the_title($specialization_id);
				$row[] =  $department_name;
				$row[] = $specialization_name;
				$row[] =  get_user_meta($retrive_data->ID, 'doctor_degree',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'visiting_fees',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'consulting_fees',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'office_address',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'city_name',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'state_name',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'country_name',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'zip_code',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'address',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'home_city',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'home_state',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'home_country',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'home_zip_code',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'phonecode',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'mobile',true);				
				$row[] =  get_user_meta($retrive_data->ID, 'phone',true);
												
				fputcsv($fh, $row);
				
			}
			fclose($fh);
	
			//download csv file.
			ob_clean();
			$file=$document_path.'export_doctors.csv';//file location
			
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
	//upload Doctor csv	
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
				wp_redirect ( admin_url().'admin.php?page=hmgt_doctor&tab=doctorlist&message=4');
			}
			if($file_size > 2097152)
			{
				$errors[]='File size limit 2 MB';
				wp_redirect ( admin_url().'admin.php?page=hmgt_doctor&tab=doctorlist&message=5');
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
							wp_update_user(array ('ID' => $user_id, 'role' => 'doctor')) ;
											
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
						
						$department = get_page_by_title( $csv['department'], OBJECT, 'department' );
											
						if(isset($csv['department']))
							update_user_meta( $user_id, "department",$department->ID);		
						
						$specialization = get_page_by_title( $csv['specialization'], OBJECT, 'specialization' );
						if(isset($csv['specialization']))
							update_user_meta( $user_id, "specialization",$specialization->ID);
						if(isset($csv['doctor_degree']))
							update_user_meta( $user_id, "doctor_degree",$csv['doctor_degree']);
						if(isset($csv['visiting_fees']))
							update_user_meta( $user_id, "visiting_fees",$csv['visiting_fees']);
						if(isset($csv['consulting_fees']))
							update_user_meta( $user_id, "consulting_fees",$csv['consulting_fees']);
						if(isset($csv['office_address']))
						update_user_meta( $user_id, "office_address", $csv['office_address'] );
						if(isset($csv['city_name']))
						update_user_meta( $user_id, "city_name", $csv['city_name'] );			
						if(isset($csv['state_name']))
							update_user_meta( $user_id, "state_name", $csv['state_name'] );						
						if(isset($csv['country_name']))
							update_user_meta( $user_id, "country_name", $csv['country_name'] );
						if(isset($csv['zip_code']))
							update_user_meta( $user_id, "zip_code", $csv['zip_code'] );
						if(isset($csv['address']))
							update_user_meta( $user_id, "address", $csv['address'] );
						
						if(isset($csv['home_city']))
							update_user_meta( $user_id, "home_city", $csv['home_city'] );
						if(isset($csv['home_state']))
							update_user_meta( $user_id, "home_state", $csv['home_state'] );
						if(isset($csv['home_country']))
							update_user_meta( $user_id, "home_country", $csv['home_country'] );
						if(isset($csv['home_zip_code']))
							update_user_meta( $user_id, "home_zip_code", $csv['home_zip_code'] );
						if(isset($csv['phonecode']))
							update_user_meta( $user_id, "phonecode", $csv['phonecode'] );
						if(isset($csv['mobile']))
							update_user_meta( $user_id, "mobile", $csv['mobile'] );
						if(isset($csv['phone']))
							update_user_meta( $user_id, "phone", $csv['phone'] );
						
						$success = 1;	
					}
					else
					{
						wp_redirect ( admin_url().'admin.php?page=hmgt_doctor&tab=doctorlist&message=6');
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
				<p><?php _e('Doctors CSV Successfully Uploaded.','hospital_mgt');?></p>
			</div>
			<?php
			} 
		}
	}
	if(isset($_POST['save_doctor']))
	{
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
	
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='insert')
		{	
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] ))
			{
				// Start Document Validation //
			   $txturl=$_POST['hmgt_user_avatar'];
			   $ext=MJ_hmgt_check_valid_extension($txturl);
			   if(!$ext == 0)
			    {
				  $cv_url=$cv;
				  $education_cert_url=$education_cert;
				  $experience_cert_url=$experience_cert;
			      $ext1=MJ_hmgt_check_valid_file_extension($cv_url);
			      $ext2=MJ_hmgt_check_valid_file_extension($education_cert_url);
			      $ext3=MJ_hmgt_check_valid_file_extension($experience_cert_url);
				   if(!$ext1 == 0 && !$ext2 == 0 && !$ext3 == 0  )
					{
					    $result=$user_object->hmgt_add_user($_POST);
						$user_object->upload_documents($cv,$education_cert,$experience_cert,$result);
						if($result)
						{
						wp_redirect ( admin_url() . 'admin.php?page=hmgt_doctor&tab=doctorlist&message=1');
				   		}
				    }
					else{ ?>
						<div id="message" class="updated below-h2">
							<p><p><?php _e('Sorry, only PDF files are allowed.','hospital_mgt');?></p></p>
						</div><?php 
					   }
				}
				else{ ?>
				<div id="message" class="updated below-h2">
					<p><p><?php _e('Sorry, only JPG, JPEG, PNG & GIF files are allowed.','hospital_mgt');?></p></p>
				</div>
				<?php 
				   }
			}
			else
			{?>
				<div id="message" class="updated below-h2">
				  <p><p><?php _e('Username Or Emailid Already Exist.','hospital_mgt');?></p></p>
			    </div>
	  <?php } 
		}
		else{
			
				$txturl=$_POST['hmgt_user_avatar'];
				$ext=MJ_hmgt_check_valid_extension($txturl);
			    if(!$ext == 0)
			    {
					  $cv_url=$cv;
					  $education_cert_url=$education_cert;
					  $experience_cert_url=$experience_cert;
					  $ext1=MJ_hmgt_check_valid_file_extension($cv_url);
					  $ext2=MJ_hmgt_check_valid_file_extension($education_cert_url);
					  $ext3=MJ_hmgt_check_valid_file_extension($experience_cert_url);
						  
						if(!$ext1 == 0 && !$ext2 == 0 && !$ext3 == 0  )
						{
							$result=$user_object->hmgt_add_user($_POST);
							$user_object->update_upload_documents($cv,$education_cert,$experience_cert,$result);
							if($result)
							{
									wp_redirect ( admin_url().'admin.php?page=hmgt_doctor&tab=doctorlist&message=2');
								
							}
						}
						else{ ?>
							<div id="message" class="updated below-h2">
								<p><p><?php _e('Sorry, only PDF files are allowed.','hospital_mgt');?></p></p>
							</div>
							<?php }
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
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		
		$result=$user_object->delete_usedata($_REQUEST['doctor_id']);
		if($result)
		{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_doctor&tab=doctorlist&message=3');
			
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
		{
			?>
			<div id="message" class="updated below-h2 "><p><?php
				_e("Record updated successfully",'hospital_mgt');
				?></p>
				</div>
			<?php 			
		}
		elseif($message == 3) 
		{?>
			<div id="message" class="updated below-h2"><p>
			<?php 
				_e('Record deleted successfully','hospital_mgt');
			?></p></div>
			<?php
				
		}
		elseif($message == 4) 
		{?>
			<div id="message" class="updated below-h2"><p>
			<?php 
				_e('Only CSV file are allow.','hospital_mgt');
			?></p></div>
			<?php				
		}		
		elseif($message == 5) 
		{?>
			<div id="message" class="updated below-h2"><p>
			<?php 
				_e('File size limit 2 MB allow.','hospital_mgt');
			?></p></div>
			<?php				
		}
		elseif($message == 6) 
		{?>
			<div id="message" class="updated below-h2"><p>
			<?php 
				_e('This file formate not proper.Please select CSV file with proper formate.','hospital_mgt');
			?></p></div>
			<?php				
		}
	}
	?>
	<div id="main-wrapper"><!--  main-wrapper div start  -->
		<div class="row"><!--  row div start  -->
			<div class="col-md-12">
				<div class="panel panel-white"><!--  PANEL WHITE div start  -->
					<div class="panel-body"><!-- PANEL BODY div start  -->
							<h2 class="nav-tab-wrapper">
								<a href="?page=hmgt_doctor&tab=doctorlist" class="nav-tab <?php echo $active_tab == 'doctorlist' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Doctor List', 'hospital_mgt'); ?></a>
								
								<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
								{?>
								<a href="?page=hmgt_doctor&tab=adddoctor&&action=edit&doctor_id=<?php echo $_REQUEST['doctor_id'];?>" class="nav-tab <?php echo $active_tab == 'adddoctor' ? 'nav-tab-active' : ''; ?>">
								<?php _e('Edit Doctor', 'hospital_mgt'); ?></a>  
								<?php 
								}
								else
								{?>
									<a href="?page=hmgt_doctor&tab=adddoctor" class="nav-tab <?php echo $active_tab == 'adddoctor' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add New Doctor', 'hospital_mgt'); ?></a>  
								<?php  }?>
							   
							</h2>
							 <?phP
							if($active_tab == 'doctorlist')
							{ 
							?>	
								<script type="text/javascript">
								jQuery(document).ready(function() {
									jQuery('#doctor_list').DataTable({
										"responsive": true,
										 "order": [[ 1, "asc" ]],
										 "aoColumns":[
													  {"bSortable": false},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true}, 
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
									<div class="panel-body"><!-- PANEL BODY DIV START -->
										<input type="submit" value="<?php _e('Export CSV','hospital_mgt');?>" name="export_csv" class="btn btn-success margin_bottom_5px"/> 
										<input type="button" value="<?php _e('Import CSV','hospital_mgt');?>" name="import_csv" class="btn btn-success importdata margin_bottom_5px"/> 
										<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->
											<table id="doctor_list" class="display" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
														<th><?php _e( 'Doctor Name', 'hospital_mgt' ) ;?></th>
														<th><?php _e( 'Department', 'hospital_mgt' ) ;?></th>
														<th> <?php _e( 'Specialization', 'hospital_mgt' ) ;?></th>
														<th> <?php _e( 'Degree', 'hospital_mgt' ) ;?></th>
														<th> <?php _e( 'Doctor Email', 'hospital_mgt' ) ;?></th>
														<th> <?php _e( 'Mobile Number', 'hospital_mgt' ) ;?></th>
														<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
														<th><?php _e( 'Doctor Name', 'hospital_mgt' ) ;?></th>
														<th><?php _e( 'Department', 'hospital_mgt' ) ;?></th>
														<th><?php _e( 'Specialization', 'hospital_mgt' ) ;?></th>
														<th><?php _e( 'Degree', 'hospital_mgt' ) ;?></th>
														<th><?php _e( 'Doctor Email', 'hospital_mgt' ) ;?></th>
														<th> <?php _e( 'Mobile Number', 'hospital_mgt' ) ;?></th>
														<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
													</tr>
												</tfoot>
										 
												<tbody>
												 <?php 
												 $get_doctor = array('role' => 'doctor');
													$doctordata=get_users($get_doctor);
												if(!empty($doctordata))
												{
													foreach ($doctordata as $retrieved_data){
												  ?>
													<tr>
														<td class="user_image"><?php $uid=$retrieved_data->ID;
																	 
															$userimage=get_user_meta($uid, 'hmgt_user_avatar', true);
																	if(empty($userimage))
																	{
																		echo '<img src='.get_option( 'hmgt_doctor_thumb' ).' height="50px" width="50px" class="img-circle" />';
																	}
																	else
																	echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
														?></td>
														<td class="name"><a href="?page=hmgt_doctor&tab=adddoctor&action=edit&doctor_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->display_name;?></a></td>
														<td class="department"><?php 
														$postdata=get_post($retrieved_data->department);
														echo $postdata->post_title;?></td>
														<td class="specialization">
														<?php 
																$specialize_id=get_user_meta($uid, 'specialization', true);
																$specialization_data=get_post( $specialize_id);
																echo $specialization_data->post_title;
																
														?></td>
														<td class="subject_name"><?php echo get_user_meta($uid, 'doctor_degree', true);?></td>
														<td class="email"><?php echo $retrieved_data->user_email;?></td>
														<td class="email"><?php echo $retrieved_data->mobile;?></td>
														<td class="action"> <a href="?page=hmgt_doctor&tab=adddoctor&action=edit&doctor_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
															<a href="?page=hmgt_doctor&tab=doctorlist&action=delete&doctor_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
															onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
															<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
														</td>
													   
													</tr>
													<?php }	 
												}?>
												</tbody>
											</table>
										</div><!-- TABLE RESPONSIVE DIV END -->
									</div><!-- TABLE BODY END -->
								</form>
								<?php 
								}	
								if($active_tab == 'adddoctor')
								{
									require_once HMS_PLUGIN_DIR. '/admin/includes/doctor/add_doctor.php';
								}
								?>
                    </div>	<!-- PANEL BODY DIV END  -->		
		        </div><!-- PANEL WHITE DIV END  -->
	        </div>
		</div>	<!-- ROW DIV END  -->		
	