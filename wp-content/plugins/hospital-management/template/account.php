<?php 
error_reporting(0);
ini_set('display_errors', 0);
MJ_hmgt_browser_javascript_check();
$user_object=new MJ_hmgt_user();
$user = wp_get_current_user ();
$user_data =get_userdata( $user->ID);
require_once ABSPATH . 'wp-includes/class-phpass.php';
$wp_hasher = new PasswordHash( 8, true );
//SAVE USER DATA
if(isset($_POST['save_change']))
{
		$referrer = $_SERVER['HTTP_REFERER'];
		$success=0;
		if($wp_hasher->CheckPassword($_REQUEST['current_pass'],$user_data->user_pass))
		{
			if(isset($_REQUEST['new_pass'])==$_REQUEST['conform_pass'])
			{
				 wp_set_password( $_REQUEST['new_pass'], $user->ID);
					$success=1;
			}
			else
			{
				wp_redirect($referrer.'&sucess=2');
			}
		}
		else{
			
			wp_redirect($referrer.'&sucess=3');
		}
		if($success==1)
		{
			wp_cache_delete($user->ID,'users');
			wp_cache_delete($user_data->user_login,'userlogins');
			wp_logout();
			if(wp_signon(array('user_login'=>$user_data->user_login,'user_password'=>$_REQUEST['new_pass']),false)):
				$referrer = $_SERVER['HTTP_REFERER'];
				
				wp_redirect($referrer.'&sucess=1');
			endif;
			ob_start();
		}else{
    wp_set_auth_cookie($user->ID, true);
		}
}
//SAVE PROFILE PICTURE
if(isset($_POST['save_profile_pic']))
{
	$referrer = $_SERVER['HTTP_REFERER'];
	if($_FILES['profile']['size'] > 0)
	{
		$patient_image=MJ_hmgt_load_documets($_FILES['profile'],'profile','pimg');
		$patient_image_url=content_url().'/uploads/hospital_assets/'.$patient_image;
	}
	
   $extension=MJ_hmgt_check_valid_extension($patient_image_url);
   if(!$extension == 0)
   {
	  $returnans=update_user_meta($user->ID,'hmgt_user_avatar',$patient_image_url);
	  if($returnans)
	 {
		wp_redirect($referrer.'&sucess=2');
	 }
   }
   else
   { 
	   ?><div id="message" class="updated below-h2"><p>
			 <?php 
				 _e('Sorry, only JPG, JPEG, PNG & GIF files are allowed.','hospital_mgt');
			  ?></div> <?php 
   }
}		
?>
<?php 
$edit=1;
$coverimage=get_option( 'hmgt_hospital_background_image' );
if($coverimage!="")
{?>

<style>
.profile-cover{
	background: url("<?php echo get_option( 'hmgt_hospital_background_image' );?>") repeat scroll 0 0 / cover rgba(0, 0, 0, 0);
}
<?php 
}
?>
</style>
<script type="text/javascript">
jQuery(document).ready(function($) 
{
	$('#acountform').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#doctor_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	
	$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
      $('#birth_date').datepicker({
        endDate: '+0d',
        autoclose: true
   }); 
});
</script>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="profile_picture">
			 </div>
		</div>
   </div> 
</div>
<!-- End POP-UP Code -->
<div> 
	<div class="profile-cover"><!-- START PROFILE COVER DIV -->
		<div class="row"><!-- START ROW DIV -->
			<div class="col-md-3 profile-image"><!-- START Profile IMAGE DIV -->
				<div class="profile-image-container">
					<?php $umetadata=get_user_meta($user->ID, 'hmgt_user_avatar', true);
							if(empty($umetadata)){
								echo '<img src='.MJ_hmgt_get_default_userprofile($obj_hospital->role).' height="150px" width="150px" class="img-circle" id="profile_pic"/>';
							}
							else
							{
							echo '<img src='.$umetadata.' height="150px" width="150px" class="img-circle" id="profile_pic" />';
							}
							?>
				</div>
				<?php $update_pic=get_option('hmgt_enable_change_profile_picture');
				if($update_pic=='yes')
				{?>
				<div class="col-md-1 update_dp">
					<button class="btn btn-default btn-file" type="file" name="profile_change" id="profile_change"><?php _e('Update Profile','hospital_mgt');?></button>
				
				</div>
				<?php } ?>
			</div>						
		</div><!-- END ROW DIV -->
	</div>	<!-- END PROFILE COVER DIV -->			
	<div Id="main-wrapper"> <!-- START MAIN WRAPPER DIV -->	
		<div class="row"> <!-- START ROW DIV -->	
			<div class="col-md-3 user-profile"> <!-- START USER PROFILE DIV -->	
				<h3 class="text-center">
					<?php 
						echo $user_data->display_name;
					?>
				</h3>				
				<hr>
				<ul class="list-unstyled text-center">
				<li>
				<p><i class="fa fa-map-marker m-r-xs"></i>
					<a href="#"><?php echo $user_data->address.",".$user_data->city;?></a></p>
				</li>	
				<li><i class="fa fa-envelope m-r-xs"></i>
							<a href="#"><?php echo 	$user_data->user_email;?></a></p>
				</p></li>
				</ul>
			</div><!-- END USER PROFILE DIV -->				
			<?php
			if(isset($_REQUEST['message']))
			{
				$message =$_REQUEST['message'];
				if($message == 2)
				{?>
					<div class="col-md-8 m-t-lg"><div id="message" class="updated below-h2 "><p><?php
							_e("Record updated successfully.",'hospital_mgt');
							?></p>
							</div>
					</div>
					<?php 
				}
				
			}?>
			
			<div class="col-md-8 m-t-lg">
				<div class="panel panel-white"><!-- START PANEL WHITE DIV -->	
					<div class="panel-heading">
						<div class="panel-title"><?php _e('Account Settings ','hospital_mgt');?>	</div>
					</div>
					<div class="panel-body"><!-- START PANEL BODY DIV -->	
					<form class="form-horizontal" id="acountform" name="acountform" action="#" method="post"><!-- START ACCOUNT FORM -->	
					
							<div class="form-group">
								<label  class="control-label col-xs-2"></label>
								<div class="col-xs-10">	
									<p>
									<h4 class="bg-danger"><?php 
									if(isset($_REQUEST['sucess']))
									{ 
										if($_REQUEST['sucess']==1)
										{
											wp_safe_redirect(home_url()."?dashboard=user&page=account&action=edit&message=2" );
										}
										
										
									}?></h4>
								</p>
								</div>
						</div>
						<div class="form-group">

							<label for="inputEmail" class="control-label col-sm-2"><?php _e('Name','hospital_mgt');?></label>

							<div class="col-sm-10">

								<input type="Name" class="form-control  validate[custom[onlyLetter_specialcharacter]]" id="name" name="first_name" placeholder="Full Name" maxlength="50" value="<?php echo $user->display_name; ?>" readonly>
								
							</div>

						</div>
						<div class="form-group">

							<label for="inputEmail" class="control-label col-sm-2"><?php _e('Username','hospital_mgt');?></label>

							<div class="col-sm-10">

								<input type="username" class="form-control validate[custom[username_validation]" id="name" placeholder="Full Name" maxlength="30" value="<?php echo $user->user_login; ?>" readonly>
								
							</div>

						</div>
						<div class="form-group">

							<label for="inputPassword" class="control-label col-sm-2 "><?php _e('Current Password','hospital_mgt');?></label>

							<div class="col-sm-10">

								<input type="password" class="form-control validate[required]" id="inputPassword" placeholder="Password" name="current_pass">

							</div>

						</div>
						<div class="form-group">

								<label for="inputPassword" class="control-label col-sm-2"><?php _e('New Password','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input type="password" class="validate[required,minSize[8]] form-control" id="new_pass" placeholder="New Password" maxlength="12" name="new_pass">

								</div>

						</div>
						<div class="form-group">

							<label for="inputPassword" class="control-label col-sm-2"><?php _e('Confirm Password','hospital_mgt');?></label>

							<div class="col-sm-10">

								<input type="password" class="validate[required,equals[new_pass],minSize[8]] form-control" id="inputPassword"  maxlength="12" placeholder="Confirm Password" name="conform_pass">

							</div>
						</div>
						<div class="form-group">

							<div class="col-xs-offset-2 col-sm-10">

								<button type="submit" class="btn btn-success" name="save_change"><?php _e('Save','hospital_mgt');?></button>

							</div>

						</div>

					</form><!-- END ACCOUNT FORM -->	
					</div>	<!-- END PANEL BODY DIV -->		   
				</div>					
					<?php 			
					$user_info=get_userdata(get_current_user_id());
					?> 
			
				<div class="panel panel-white"><!-- START PANEL WHITE DIV -->	
					<div class="panel-heading">
						<div class="panel-title"><?php _e('Other Information','hospital_mgt');?>	</div>
					</div>
					<div class="panel-body"><!-- START PANEL BODY DIV -->	
						<form class="form-horizontal" action="#" method="post" id="doctor_form"><!-- START USER INORMATION FORM -->	
							<input type="hidden" value="edit" name="action">
							<input type="hidden" value="<?php echo $obj_hospital->role;?>" name="role">
							<input type="hidden" value="<?php echo get_current_user_id();?>" name="user_id">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="birth_date"><?php _e('Date of birth','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-10">
									<input id="birth_date" class="form-control validate[required]" type="text" type="text"  name="birth_date" 
									value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime( $user_info->birth_date));}elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>" readonly>
								</div>
							</div>	
							<?php if($obj_hospital->role == 'doctor'){?>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="department"><?php _e('Department','hospital_mgt');?></label>
								<div class="col-sm-10">
								<?php if($edit){ $departmentid=$user_info->department; }elseif(isset($_POST['department'])){$departmentid=$_POST['department'];}else{$departmentid='';}?>
									<select name="department" class="form-control" id="category_data">
									<option><?php _e('select Department','hospital_mgt');?></option>
									<?php 
									
										$department_array = $user_object->get_staff_department();
										 if(!empty($department_array))
										 {
											foreach ($department_array as $retrieved_data){?>
												<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($departmentid,$retrieved_data->ID);?>><?php echo $retrieved_data->post_title;?></option>
											<?php }
										 }
							?>
									</select>
								</div>
								
							</div>	
							<div class="form-group">
								<label class="col-sm-2 control-label" for="birth_date"><?php _e('Specialization','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-10">
									<?php if($edit){ $specializeid= $user_info->specialization; }elseif(isset($_POST['specialization'])){$specializeid=$_POST['specialization'];}else{$specializeid='';}?>
									
									<select class="form-control validate[required]" 
									id="specialization" name="specialization" >
										<option><?php _e('Select Specialization','hospital_mgt');?></option>
										<?php 
									
										$specialize_array = $user_object->get_doctor_specilize();
										 if(!empty($specialize_array))
										 {
											foreach ($specialize_array as $retrieved_data){?>
												<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($specializeid,$retrieved_data->ID);?>><?php echo $retrieved_data->post_title;?></option>
											<?php }
										 }?>
										</select>
								</div>
								
							</div>	
							<div class="form-group">
								<label class="col-sm-2 control-label" for="birth_date"><?php _e('Degree','hospital_mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-10">
									<input id="doc_degree" class="form-control validate[required,custom[popup_category_validation]]" maxlength="50" type="text"  name="doc_degree" 
									value="<?php if($edit){ echo $user_info->doctor_degree;}elseif(isset($_POST['doc_degree'])) echo $_POST['doc_degree'];?>">
								</div>
							</div>	
							<div class="form-group">
								<label class="col-sm-2 control-label" for="visiting_fees"><?php _e('Visting Charge','hospital_mgt');?></label>
								<div class="col-sm-10">
									<input id="doc_degree" class="form-control" type="number" min="0" onKeyPress="if(this.value.length==8) return false;" step="0.01"  name="visiting_fees"
									value="<?php if($edit){ echo $user_info->visiting_fees;}elseif(isset($_POST['visiting_fees'])) echo $_POST['visiting_fees'];?>">
								</div>									
							</div>
							<?php } //end Docotr field?>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Home Town Address','hospital_mgt');?><span class="require-field">*</span></label>

								<div class="col-sm-10">

									<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text"  name="address" maxlength="150" value="<?php if($edit){ echo $user_info->address;}?>">

								</div>

							</div>
							<?php if($obj_hospital->role == 'doctor'){?>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('City','hospital_mgt');?><span class="require-field">*</span></label>

								<div class="col-sm-10">

									<input id="address" class="form-control validate[required,custom[city_state_country_validation]]" type="text"  name="home_city_name" maxlength="50" value="<?php if($edit){ echo $user_info->home_city;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('State','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input id="address" class="form-control validate[custom[city_state_country_validation]]" type="text"  name="home_state_name" maxlength="50" value="<?php if($edit){ echo $user_info->home_state;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Country','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input id="address" class="form-control validate[custom[city_state_country_validation]]" type="text"  name="home_country_name" maxlength="50" value="<?php if($edit){ echo $user_info->home_country;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Office Address','hospital_mgt');?><span class="require-field">*</span></label>

								<div class="col-sm-10">

							<input id="" class="form-control validate[required,custom[address_description_validation]]" type="text"  name="office_address" maxlength="150" value="<?php if($edit){ echo $user_info->office_address;}?>">

								</div>

							</div>
							<?php }//End Address field?>
							<div class="form-group">

								<label for="inputEmail" class="control-label col-sm-2"><?php _e('City','hospital_mgt');?><span class="require-field">*</span></label>

								<div class="col-sm-10">

									<input id="" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" value="<?php if($edit){ echo $user_info->city_name;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputstate" class="control-label col-sm-2"><?php _e('State','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input id="" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" value="<?php if($edit){ echo $user_info->state_name;}?>">

								</div>

							</div>
							<div class="form-group">

								<label for="inputstate" class="control-label col-sm-2"><?php _e('Country','hospital_mgt');?></label>

								<div class="col-sm-10">

									<input id="" class="form-control validate[custom[city_state_country_validation]]" type="text"  name="country_name" maxlength="50" value="<?php if($edit){ echo $user_info->country_name;}?>">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Phone','hospital_mgt');?></label>

								<div class="col-sm-10">
									<input id="phone" class="form-control validate[custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="<?php if($edit){ echo $user_info->phone;}?>" name="phone">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-sm-2"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>

								<div class="col-sm-10">

									<input id="email" class="form-control validate[required,custom[email]] text-input" type="text"  maxlength="100" name="email" value="<?php if($edit){ echo $user_info->user_email;}?>">

								</div>

							</div>
							<div class="form-group">

								<div class="col-sm-offset-2 col-sm-10">

									<button type="submit" class="btn btn-success" name="profile_save_change"><?php _e('Save','hospital_mgt');?></button>

								</div>

							</div>
						</form><!-- END USER INFORMATION FORM -->	
					</div><!-- END PANEL BODY DIV -->	
				</div><!-- END PANEL WHITE DIV -->	
			</div><!-- END USER PROFILE DIV -->	
		</div><!-- END ROW DIV -->	
 	</div>	<!-- END MAIN WRAPPER DIV -->		
</div>
<?php 
//SAVE USER INFORMATION
if(isset($_POST['profile_save_change']))
{
	$result=$user_object->hmgt_add_user($_POST);
	if($result)
	{ 
		wp_safe_redirect(home_url()."?dashboard=user&page=account&action=edit&message=2" );
	}
}
?>