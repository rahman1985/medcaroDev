<?php 
MJ_hmgt_browser_javascript_check();
$user_object=new MJ_hmgt_user();
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'pharmacistlist'; 
//access right function
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
//SAVE Pharmacist DATA
if(isset($_POST['save_pharmacist']))
{
    if(isset($_FILES['upload_user_avatar_image']) && !empty($_FILES['upload_user_avatar_image']) && $_FILES['upload_user_avatar_image']['size'] !=0)
	{
		
		if($_FILES['upload_user_avatar_image']['size'] > 0)
		{
		 $pharmacist_image=MJ_hmgt_load_documets($_FILES['upload_user_avatar_image'],'upload_user_avatar_image','pimg');
		$pharmacist_image_url=content_url().'/uploads/hospital_assets/'.$pharmacist_image;
		}
		else 
		{
			$pharmacist_image=$_REQUEST['hidden_upload_user_avatar_image'];
		$pharmacist_image_url=$pharmacist_image;
		}
	}
	else
	{		
		if(isset($_REQUEST['hidden_upload_user_avatar_image']))
			$pharmacist_image=$_REQUEST['hidden_upload_user_avatar_image'];
		$pharmacist_image_url=$pharmacist_image;
	}
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$ext=MJ_hmgt_check_valid_extension($pharmacist_image_url);
			if(!$ext == 0)
			{
				$result=$user_object->hmgt_add_user($_POST);
				$returnans=update_user_meta( $result,'hmgt_user_avatar',$pharmacist_image_url);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=pharmacist&tab=pharmacistlist&message=2');
				}
			}
					  
			else
			{ ?>
				<div id="message" class="updated below-h2">
									<p><p><?php _e('Sorry, Only JPG, JPEG, PNG & GIF Files Are Allowed.','hospital_mgt');?></p></p>
				</div>
				<?php 
						
			}
		}
		else
		{
		if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) {
			$ext=MJ_hmgt_check_valid_extension($pharmacist_image_url);
			if(!$ext == 0)
			{
					$result=$user_object->hmgt_add_user($_POST);
					$returnans=update_user_meta( $result,'hmgt_user_avatar',$pharmacist_image_url);
					if($result)
					{
						wp_redirect ( home_url() . '?dashboard=user&page=pharmacist&tab=pharmacistlist&message=1');
					}
			}
				else{ ?>
				 <div id="message" class="updated below-h2">
								<p><p><?php _e('Sorry, Only JPG, JPEG, PNG & GIF Files Are Allowed.','hospital_mgt');?></p></p>
				 </div>
				<?php 
				   }
			}
			else
				{?>
							<div id="message" class="updated below-h2">
							<p><?php _e('Username Or Emailid Already Exist.','hospital_mgt');?></p>
							</div>
		  <?php }
		}
			
}
	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
				
		$result=$user_object->delete_usedata(MJ_hmgt_id_decrypt($_REQUEST['pharmacist_id']));
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=pharmacist&tab=pharmacistlist&message=3');
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
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="profile_data "></div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#pharmacist_list').DataTable({
		"responsive": true,
		 "order": [[ 1, "asc" ]],
		 "aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false},
	                 ],
		language:<?php echo MJ_hmgt_datatable_multi_language();?>			  
		});
} );
</script>
<div class="panel-body panel-white"><!-- START PANEL BODY DIV-->
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="<?php if($active_tab=='pharmacistlist'){?>active<?php }?>">
			  <a href="?dashboard=user&page=pharmacist&tab=pharmacistlist" class="tab <?php echo $active_tab == 'pharmacistlist' ? 'active' : ''; ?>" >
				 <i class="fa fa-align-justify"></i> <?php _e('Pharmacist List', 'hospital_mgt'); ?></a>
			  </a>
		</li>
		<li class="<?php if($active_tab=='addpharmacist'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{?>
				<a href="?dashboard=user&page=pharmacist&tab=addpharmacist&&action=edit&pharmacist_id=<?php echo $_REQUEST['pharmacist_id'];?>" class="tab <?php echo $active_tab == 'addpharmacist' ? 'active' : ''; ?>">
				<i class="fa fa"></i> <?php _e('Edit Pharmacist', 'hospital_mgt'); ?></a>
			 <?php 
			}
			else
			{
				if($user_access['add']=='1')
				{			
				?>				
					<a href="?dashboard=user&page=pharmacist&tab=addpharmacist&&action=insert" class="tab <?php echo $active_tab == 'addpharmacist' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php _e('Add New Pharmacist', 'hospital_mgt'); ?></a>
				<?php
				}
			}
			?>	  
		</li>
	</ul>
<?php
if($active_tab=='pharmacistlist')
{
?>
	<div class="tab-content"><!-- START TAB CONTENT DIV-->
		<div class="panel-body"><!-- START PANEL BODY DIV-->
			<div class="table-responsive"><!-- START TABLE RESPONSIVE DIV-->
			    <table id="pharmacist_list" class="display dataTable " cellspacing="0" width="100%"><!-- START Pharmacist LIST TABLE-->
					<thead>
						<tr>
							<th style="width: 50px;height:50px;"><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Pharmacist Name', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Mobile Number', 'hospital_mgt' ) ;?></th>
						    <th> <?php _e( 'Pharmacist Email', 'hospital_mgt' ) ;?></th>
						    <th> <?php _e( 'Action', 'hospital_mgt' ) ;?></th>
						</tr>
				    </thead>
					<tfoot>
						<tr>
						<th><?php  _e( 'Photo', 'hospital_mgt' ) ;?></th>
						 <th><?php _e( 'Pharmacist Name', 'hospital_mgt' ) ;?></th>
						   
						  <th> <?php _e( 'Mobile Number', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Pharmacist Email', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Action', 'hospital_mgt' ) ;?></th>
						</tr>
					</tfoot>
					<tbody>
						<?php
						if($obj_hospital->role == 'pharmacist') 
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$user_id=get_current_user_id();	
								$receptionistdata=array();
								$receptionistdata[]=get_userdata($user_id);		
							}
							else
							{
								$get_receptionist = array('role' => 'pharmacist');
								$receptionistdata=get_users($get_receptionist);
							}
						}
						else
						{
							$get_receptionist = array('role' => 'pharmacist');
							$receptionistdata=get_users($get_receptionist);
						}
						
							
						if(!empty($receptionistdata))
						{
							foreach ($receptionistdata as $retrieved_data){
						 ?>
							<tr>
								<td class="user_image"><?php $uid=$retrieved_data->ID;
											$userimage=get_user_meta($uid, 'hmgt_user_avatar', true);
										if(empty($userimage))
										{
														echo '<img src='.get_option( 'hmgt_nurse_thumb' ).' height="50px" width="50px" class="img-circle" />';
										}
										else
											echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
								?></td>
								<td class="name"><a href="#"><?php echo $retrieved_data->display_name;?></a></td>
								
								<td class="phone">
								<?php 
									echo get_user_meta($uid, 'mobile', true);
								?></td>
								
								<td class="email"><?php echo $retrieved_data->user_email;?></td>
									<td class="action">
								<?php
								if($user_access['edit']=='1')
								{
								?>
									<a href="?dashboard=user&page=pharmacist&tab=addpharmacist&&action=edit&pharmacist_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->ID);?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
								<?php
								}
								if($user_access['delete']=='1')
								{
								?>	
									<a href="?dashboard=user&page=pharmacist&tab=pharmacistlist&action=delete&pharmacist_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->ID);?>" class="btn btn-danger" 
									onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
									<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
								<?php
								}
								?>	
								</td>
							   
							</tr>
							<?php } 
							
						}?>
					 
					</tbody>
				</table><!-- END Pharmacist LIST TABLE-->
			</div><!-- END TABLE RESPONSIVE DIV-->
		</div><!-- END PANEL BODY DIV-->
	</div><!-- END TAB CONTENT DIV-->
	<?php
	}
	if($active_tab=='addpharmacist')
	{
	
		$role='pharmacist';
		?>
		<script type="text/javascript">
		   jQuery(document).ready(function($) {
			$('#pharmacist_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
			$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
			  $('#birth_date').datepicker({
			 endDate: '+0d',
				autoclose: true,
		   }); 
		} );
		</script>
		<?php 	
		if($active_tab == 'addpharmacist')
		{
			$pharmacist_id=0;
			if(isset($_REQUEST['pharmacist_id']))
				$pharmacist_id=MJ_hmgt_id_decrypt($_REQUEST['pharmacist_id']);
				$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{				
					$edit=1;
					$user_info = get_userdata($pharmacist_id);
					
				}
				?>
			<div class="panel-body"><!-- START PANEL BODY DIV-->
				<form name="pharmacist_form" action="" method="post" class="form-horizontal" id="pharmacist_form" enctype="multipart/form-data"><!-- START Pharmacist FORM-->
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<input type="hidden" name="role" value="<?php echo $role;?>"  />
					<input type="hidden" name="user_id" value="<?php echo $pharmacist_id;?>"  />
					<div class="header">	
						<h3 class="first_hed"><?php _e('Personal Information','hospital_mgt');?></h3>
						<hr>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="first_name"><?php _e('First Name','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="middle_name"><?php _e('Middle Name','hospital_mgt');?></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" type="text" maxlength="50" value="<?php if($edit){ echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="last_name"><?php _e('Last Name','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="birth_date"><?php _e('Date of birth','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="birth_date" class="form-control validate[required]" type="text"  name="birth_date"  
							value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($user_info->birth_date));}elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>" readonly>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
							<label class="radio-inline">
							 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php _e('Male','hospital_mgt');?>
							</label>
							<label class="radio-inline">
							  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','hospital_mgt');?> 
							</label>
						</div>
					</div>
					
					<div class="header">
						<h3><?php _e('HomeTown Address Information','hospital_mgt');?></h3>
						<hr>
					</div>
					
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="address"><?php _e('Home Town Address','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="address" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="150" name="address" 
							value="<?php if($edit){ echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="city_name"><?php _e('City','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
							value="<?php if($edit){ echo $user_info->city_name;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="state_name"><?php _e('State','hospital_mgt');?></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" 
							value="<?php if($edit){ echo $user_info->state_name;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="country_name"><?php _e('Country','hospital_mgt');?></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="country_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="country_name" 
							value="<?php if($edit){ echo $user_info->country_name;}elseif(isset($_POST['country_name'])) echo $_POST['country_name'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="zip_code"><?php _e('Zip Code','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" type="text" maxlength="15"  name="zip_code" 
							value="<?php if($edit){ echo $user_info->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
						</div>
					</div>
					<div class="header">
						<h3><?php _e('Contact Information','hospital_mgt');?></h3>
						<hr>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="mobile"><?php _e('Mobile Number','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 margin_bottom_5px">
						<input type="text" value="<?php if($edit) { if(!empty($user_info->phonecode)){ echo $user_info->phonecode; }else{ ?>+<?php echo MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); } }elseif(isset($_POST['phonecode'])){ echo $_POST['phonecode']; }else{ ?>+<?php echo MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); }?>"  class="form-control  validate[required] onlynumber_and_plussign" name="phonecode" maxlength="5">
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 has-feedback">
							<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="<?php if($edit){ echo $user_info->mobile;}elseif(isset($_POST['mobile'])) echo $_POST['mobile'];?>" name="mobile">
						</div>
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="phone"><?php _e('Phone','hospital_mgt');?></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="phone" class="form-control validate[custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="<?php if($edit){ echo $user_info->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>" name="phone">
						</div>
					</div>
					<div class="header">
						<h3><?php _e('Login Information','hospital_mgt');?></h3>
						<hr>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
							value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
						</div>
							<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="username"><?php _e('User Name','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="username" class="form-control validate[required,custom[username_validation]]" type="text" maxlength="30" name="username" 
							value="<?php if($edit){ echo $user_info->user_login;}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?>>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="password"><?php _e('Password','hospital_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
							<input id="password" class="form-control <?php if(!$edit) { echo 'validate[required,minSize[8]]'; }else{ echo 'validate[minSize[8]]'; }?>" type="password"  maxlength="12" name="password" value="">
						</div>
					</div>
					<div class="header">
						<h3><?php _e('Other Information','hospital_mgt');?></h3>
						<hr>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php _e('Image','hospital_mgt');?></label>
					
							<div class="col-sm-3">
							<input type="hidden" id="hmgt_user_avatar_url" class="form-control" name="hmgt_user_avatar_url"  
							value="<?php if($edit)echo esc_url( $user_info->hmgt_user_avatar );elseif(isset($_POST['upload_user_avatar_image'])) echo $_POST['upload_user_avatar_image']; ?>" readonly />
								<input type="hidden" name="hidden_upload_user_avatar_image" 
								value="<?php if($edit){ echo $user_info->hmgt_user_avatar;}elseif(isset($_POST['upload_user_avatar_image'])) echo $_POST['upload_user_avatar_image'];
								else echo get_option('hmgt_patient_thumb');?>">
								 <input id="upload_user_avatar_image" name="upload_user_avatar_image" type="file" class="form-control file" value="<?php _e( 'Upload image', 'hospital_mgt' ); ?>" />
							</div>
						<div class="clearfix"></div>
						
						<div class="col-sm-offset-2 col-sm-8">
								 <div id="upload_user_avatar_preview" >
									 <?php if($edit) 
										{
										if($user_info->hmgt_user_avatar == "")
										{?>
										<img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">
										<?php }
										else {
											
											?>
										<img class="image_preview_css" src="<?php if($edit)echo esc_url( $user_info->hmgt_user_avatar ); ?>" />
										<?php 
										}
										}
										else {
											?>
											<img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">
											<?php 
										}?>
								</div>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-8">
						<input type="submit" value="<?php if($edit){ _e('Save Pharmacist','hospital_mgt'); }else{ _e('Add Pharmacist','hospital_mgt');}?>" name="save_pharmacist" class="btn btn-success"/>
					</div>
				</form><!-- END Pharmacist FORMV-->
			</div><!-- END PANEL BODY DIV-->
		 <?php 
		}
	}
?>	
</div>
<!-- END PANEL WHITE DIV  -->