<?php 
MJ_hmgt_browser_javascript_check();
$user_object=new MJ_hmgt_user();
$obj_bloodbank=new MJ_hmgt_bloodbank();
?>
<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'patientlist';
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			 <div class="patient_data">
			 </div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
		
<div class="page-inner" style="min-height:1631px !important"><!-- PAGE INNER DIV START-->
    <!-- PAGE TITLE DIV START-->
	<div class="page-title">
			<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
    </div><!-- PAGE TITLE DIV END-->
	<?php 
	if(isset($_POST['save_patient']))
	{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='insert')
		{
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) {
			   $txturl=$_POST['hmgt_user_avatar'];
			   $ext=MJ_hmgt_check_valid_extension($txturl);
			   if(!$ext == 0)
			   {
					$result=$user_object->hmgt_add_user($_POST);
					
					if($result)
					{
						
					wp_redirect ( admin_url () . 'admin.php?page=hmgt_patient&tab=addpatient_step2&patient_id='.$result ); 
								
					}
			   }
				else{ ?>
				<div id="message" class="updated below-h2">
						<p><p><?php _e('Sorry, only JPG, JPEG, PNG & GIF files are allowed.','hospital_mgt');?></p></p>
               </div>
				<?php 
				}
						
			}
	    }
		else			
		{
			$result=$user_object->hmgt_add_user($_POST);
			if($result)
			{
				wp_redirect ( admin_url () . 'admin.php?page=hmgt_patient&tab=addpatient_step2&action=edit&patient_id='.$result); 
			}
		}
	}
		if(isset($_POST['save_patient_step3']))
		{
			$guardian_data=array('admit_date'=>date(MJ_hmgt_get_format_for_db($_POST['admit_date'])) ,			                
							'admit_time'=>$_POST['admit_time'].":00",
							'patient_status'=>$_POST['patient_status'],
							'doctor_id'=>$_POST['doctor'],
							'symptoms'=>implode(",",$_POST['symptoms'])
							);
					if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
					{
					 $result=MJ_hmgt_update_guardian($guardian_data,$_REQUEST['patient_id']);
					  if($result)
						{
							//patint asign to doctor patient mail template code start
							$doctorid=$_POST['doctor'];
							$doctorinfo=get_userdata($doctorid);
							$doctorname=$doctorinfo->display_name;
							$doctoremail=$doctorinfo->user_email;
							$departmentsname=get_post($doctorinfo->department);
							$dep=$departmentsname->post_title; 
							$userinfo=get_userdata($_REQUEST['patient_id']);
							$username=$userinfo->display_name;
							$user_email=$userinfo->user_email; 
							$hospital_name = get_option('hmgt_hospital_name');
								$subject =get_option('MJ_hmgt_patient_assigned_to_doctor_patient_email_subject');
								$sub_arr['{{Doctor Name}}']=$doctorname;
								$subject = MJ_hmgt_subject_string_replacemnet($sub_arr,$subject);
								$arr['{{Patient Name}}']=$username;			
								$arr['{{Doctor Name}}']=$doctorname;			
								$arr['{{Department Name}}']=$dep;
								$arr['{{Hospital Name}}']=$hospital_name;
								$message = get_option('MJ_hmgt_patient_assigned_to_doctor_patient_email_template');
								$message_replacement = MJ_hmgt_string_replacemnet($arr,$message);	
									$to[]=$user_email;
								MJ_hmgt_send_mail($to,$subject,$message_replacement);
						
							   //patint asign to doctor patient mail template code end
						
							   // patint asign to doctor docor mail template code  start
								$subject =get_option('MJ_hmgt_patient_assigned_to_doctor_mail_subject');
								$sub_arr['{{Patient Name}}']=$username;
								$subject = MJ_hmgt_subject_string_replacemnet($sub_arr,$subject);
								$arr['{{Patient Name}}']=$username;			
								$arr['{{Doctor Name}}']=$doctorname;			
								$arr['{{Hospital Name}}']=$hospital_name;
								$message = get_option('MJ_hmgt_patient_assigned_to_doctor_mail_template');
								$message_replacement = MJ_hmgt_string_replacemnet($arr,$message);	
								$doctoremail_to[]=$doctoremail;
								MJ_hmgt_send_mail($doctoremail_to,$subject,$message_replacement);
											wp_redirect ( admin_url() . 'admin.php?page=hmgt_patient&tab=patientlist&message=2');
						}
										
					}
					else
					{
						$result=MJ_hmgt_update_guardian($guardian_data,$_REQUEST['patient_id']);
						if($result)
						{
						    //patint asign to doctor patient mail template code start
							$doctorid=$_POST['doctor'];
							$doctorinfo=get_userdata($doctorid);
							$doctorname=$doctorinfo->display_name;
							$doctoremail=$doctorinfo->user_email;
							$departmentsname=get_post($doctorinfo->department);
							$dep=$departmentsname->post_title; 
							$userinfo=get_userdata($_REQUEST['patient_id']);
							$username=$userinfo->display_name;
							$user_email=$userinfo->user_email; 
							$hospital_name = get_option('hmgt_hospital_name');
								$subject =get_option('MJ_hmgt_patient_assigned_to_doctor_patient_email_subject');
								$sub_arr['{{Doctor Name}}']=$doctorname;
								$subject = MJ_hmgt_subject_string_replacemnet($sub_arr,$subject);
								$arr['{{Patient Name}}']=$username;			
								$arr['{{Doctor Name}}']=$doctorname;			
								$arr['{{Department Name}}']=$dep;
								$arr['{{Hospital Name}}']=$hospital_name;
								$message = get_option('MJ_hmgt_patient_assigned_to_doctor_patient_email_template');
								$message_replacement = MJ_hmgt_string_replacemnet($arr,$message);	
									$to[]=$user_email;
							    MJ_hmgt_send_mail($to,$subject,$message_replacement);
						
								//patint asign to doctor patient mail template code end
								
								// patint asign to doctor docor mail template code  start
								$subject =get_option('MJ_hmgt_patient_assigned_to_doctor_mail_subject');
								$sub_arr['{{Patient Name}}']=$username;
								$subject = MJ_hmgt_subject_string_replacemnet($sub_arr,$subject);
								$arr['{{Patient Name}}']=$username;			
								$arr['{{Doctor Name}}']=$doctorname;			
								$arr['{{Hospital Name}}']=$hospital_name;
								$message = get_option('MJ_hmgt_patient_assigned_to_doctor_mail_template');
								$message_replacement = MJ_hmgt_string_replacemnet($arr,$message);	
								$doctoremail_to[]=$doctoremail;
								MJ_hmgt_send_mail($doctoremail_to,$subject,$message_replacement);
										wp_redirect ( admin_url() . 'admin.php?page=hmgt_patient&tab=patientlist&message=1');
						}
				    }
		}
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			$result=$user_object->delete_usedata($_REQUEST['patient_id']);
			$result=MJ_hmgt_delete_guardian($_REQUEST['patient_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_patient&tab=patientlist&message=3');
			}
		}
		?>
		<?php 
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
		{?><div id="message" class="updated below-h2 "><p><?php
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
		?></div></p><?php
				
		}
	}
		
	?>
	<!-- MAIN WRAPPER DIV START-->	
	<div id="main-wrapper">
		<div class="row"> <!-- ROW DIV START-->
			<div class="col-md-12">
			    <!-- PANEL WHITE DIV START-->
				<div class="panel panel-white">
				<!-- PANEL BODY DIV START-->
					<div class="panel-body">
						<h2 class="nav-tab-wrapper">
							<a href="?page=hmgt_patient&tab=patientlist" class="nav-tab <?php echo $active_tab == 'patientlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Patient List', 'hospital_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=hmgt_patient&tab=addpatient&action=edit&patient_id=<?php echo $_REQUEST['patient_id'];?>" class="nav-tab <?php echo $active_tab == 'addpatient' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Patient', 'hospital_mgt'); ?></a> 
								
							<a href="?page=hmgt_patient&tab=addpatient_step2&action=edit&patient_id=<?php echo $_REQUEST['patient_id'];?>" class="nav-tab <?php echo $active_tab == 'addpatient_step2' ? 'nav-tab-active' : ''; ?>">
							<?php echo __('Edit Patient Step-2', 'hospital_mgt'); ?></a>  
							<a href="?page=hmgt_patient&tab=addpatient_step3&action=edit&patient_id=<?php echo $_REQUEST['patient_id'];?>" class="nav-tab <?php echo $active_tab == 'addpatient_step3' ? 'nav-tab-active' : ''; ?>">
							<?php echo __('Edit Patient Step-3', 'hospital_mgt'); ?></a>
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_patient&tab=addpatient" class="nav-tab <?php echo $active_tab == 'addpatient' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add New Patient', 'hospital_mgt'); ?></a>  
							<a href="?page=hmgt_patient&tab=addpatient_step2" class="nav-tab <?php echo $active_tab == 'addpatient_step2' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add New Patient Step-2', 'hospital_mgt'); ?></a>  
							<a href="?page=hmgt_patient&tab=addpatient_step3" class="nav-tab <?php echo $active_tab == 'addpatient_step3' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add New Patient Step-3', 'hospital_mgt'); ?></a>  
							<?php  }?>
						   
						</h2>
						 <?php 
						
						if($active_tab == 'patientlist')
						{
						?>	
							<script>
								jQuery(document).ready(function() {
								jQuery('#patient_list').DataTable({ 
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
												  {"bVisible": true},
												  {"bSortable": false}
											   ],
									language:<?php echo MJ_hmgt_datatable_multi_language();?>		   
								});
								
							} );
							</script>
						<form name="wcwm_report" action="" method="post">
							<div class="panel-body"> <!-- PANEL BODY DIV START-->
								<div class="table-responsive"> <!-- TABLE RESPONSIVE DIV START-->
									<table id="patient_list" class="display" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Patient Id', 'hospital_mgt' ) ;?></th>             
												<th> <?php _e( 'Mobile Number', 'hospital_mgt' ) ;?></th>
												<th> <?php _e( 'Status', 'hospital_mgt' ) ;?></th>
												<th> <?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
												<th> <?php _e( 'Assigned Doctor Name', 'hospital_mgt' ) ;?></th>
												<th> <?php _e( 'Admitted Date', 'hospital_mgt' ) ;?></th>
												<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
											</tr>
										</thead>
										<tfoot>
											<tr>
											   <th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
											   <th><?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
											   <th><?php _e( 'Patient Id', 'hospital_mgt' ) ;?></th>            
											   <th> <?php _e( 'Mobile Number', 'hospital_mgt' ) ;?></th>
											   <th> <?php _e( 'Status', 'hospital_mgt' ) ;?></th>
											   <th> <?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
											   <th> <?php _e( 'Assigned Doctor Name', 'hospital_mgt' ) ;?></th>
											   <th> <?php _e( 'Admitted Date', 'hospital_mgt' ) ;?></th>
											   <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
											</tr>
										</tfoot>
										<tbody>
										<?php 
										$get_patient = array('role' => 'patient','meta_key'=>'patient_type','meta_value'=>'inpatient');
										$patientdata=get_users($get_patient);
										 if(!empty($patientdata))
										 {
											foreach ($patientdata as $retrieved_data)
											{
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
												?>
												</td>
												<td class="name"><a href="?page=hmgt_patient&tab=addpatient&action=edit&patient_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->display_name;?></a></td>
												<td class="patient_id">
												<?php 
														echo get_user_meta($uid, 'patient_id', true);
												?></td>
												<td class="phone"><?php echo get_user_meta($uid, 'mobile', true);?></td>
												<td class="email"><?php echo MJ_hmgt_get_patient_status($retrieved_data->ID);?></td>
												<td class="bldgroup"><?php echo get_user_meta($uid, 'blood_group', true);?></td>
												<td class=""><?php echo $doctor->display_name;?></td>
												<td class=""><?php echo date(MJ_hmgt_date_formate(),strtotime($doctordetail['admit_date']));?></td>
												<td class="action">
													<a href="?page=hmgt_invoice&tab=addinvoice&patient=<?php echo $retrieved_data->ID; ?>" class="btn btn-default"> <?php _e('Billing', 'hospital_mgt' );?></a>
													<a href="?page=hmgt_bedallotment&tab=bedassign&patient_id=<?php echo $retrieved_data->ID; ?>" class="btn btn-default"> <?php _e('Stay', 'hospital_mgt' );?></a>
													<a  href="#" class="show-popup btn btn-default" idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php _e('View Detail', 'hospital_mgt');?></a>
													<a  href="#" class="show-charges-popup btn btn-default" idtest="<?php echo $retrieved_data->ID; ?>">
													<i class="fa fa-money"></i> <?php _e('Charges', 'hospital_mgt');?></a>
													<a href="?page=hmgt_patient&tab=addpatient&action=edit&patient_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' );?></a>
													<a href="?page=hmgt_patient&tab=patientlist&action=delete&patient_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
													<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
												
												</td>
											</tr>
											<?php } 
											
										}?>
										</tbody>
									</table>
								</div><!--TABLE RESPONSIVE DIV END-->
							</div><!-- PANEL BODY DIV END-->
						</form>
						<?php 
						}
						if($active_tab == 'addpatient')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/patient/add_patient.php';
						}
						if($active_tab == 'addpatient_step2')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/patient/add_patient_step2.php';
						}
						if($active_tab == 'addpatient_step3')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/patient/add_patient_step3.php';
						}
						?>
					</div><!-- PANEL BODY DIV END-->		
		        </div>	<!-- PANEL WHITE DIV END-->
	        </div>
        </div> <!-- ROW DIV END-->
	</div> <!-- MAIN WRAPPER DIV END-->