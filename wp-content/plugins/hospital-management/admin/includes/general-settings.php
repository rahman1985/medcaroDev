
<script type="text/javascript">
jQuery(document).ready(function($) {
	 $('#setting_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
} );
</script>
<div class="page-inner" style="min-height:1631px !important"><!-- PAGE INNER DIV START-->		
   <div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt') ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div>
	<?php 
	MJ_hmgt_browser_javascript_check();	
	if(isset($_POST['save_setting']))
	{
		$txturl=$_POST['hmgt_hospital_logo'];
		$txturl1=$_POST['hmgt_hospital_background_image'];
		$ext=MJ_hmgt_check_valid_extension($txturl);
		$ext1=MJ_hmgt_check_valid_extension($txturl1);
		if(!$ext == 0 && !$ext1==0)
		{	
			$optionval=MJ_hmgt_option();
			foreach($optionval as $key=>$val)
			{
				if(isset($_POST[$key]))
				{
					$result=update_option( $key, MJ_hmgt_strip_tags_and_stripslashes($_POST[$key]) );
				}
			}
		}			
		else
		{ ?>
			<div id="message" class="updated below-h2 ">
			<p>
				<?php _e('Sorry, only JPG, JPEG, PNG & GIF And BMP files are allowed.','hospital_mgt');?>
			</p></div>				 
			<?php 
		}	
	
		if(isset($_REQUEST['hmgt_enable_change_profile_picture']))
				update_option( 'hmgt_enable_change_profile_picture', 'yes' );
			else 
				update_option( 'hmgt_enable_change_profile_picture', 'no' );	
		if(isset($_REQUEST['hmgt_enable_hospitalname_in_priscription']))
				update_option( 'hmgt_enable_hospitalname_in_priscription', 'yes' );
			else 
				update_option( 'hmgt_enable_hospitalname_in_priscription', 'no' );	
		if(isset($_REQUEST['hmgt_enable_staff_can_message']))
				update_option( 'hmgt_enable_staff_can_message', 'yes' );
			else 
				update_option( 'hmgt_enable_staff_can_message', 'no' );	

	   if(isset($_REQUEST['hospital_enable_notifications']))
				update_option( 'hospital_enable_notifications', 'yes' );
			else 
				update_option( 'hospital_enable_notifications', 'no' );	
			
		if(isset($result))
		{?>
			<div id="message" class="updated below-h2">
				<p><?php _e('Record updated successfully','hospital_mgt');?></p>
			</div>
			<?php 
		}
	}
	?>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START-->		
	    <div class="panel panel-white"><!-- PANEL WHITE DIV START-->		
				<div class="panel-body"><!-- PANEL BODY DIV START-->		
					<h2>	
					<?php  echo esc_html( __( 'General Settings', 'hospital_mgt')); ?>
					</h2>
					<div class="panel-body"><!-- PANEL BODY DIV START-->	
						<form name="student_form" action="" method="post" class="form-horizontal" id="setting_form">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="hmgt_hospital_name"><?php _e('Hospital Name','hospital_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="hmgt_hospital_name" class="form-control validate[required,custom[popup_category_validation]]" type="text" maxlength="50" value="<?php echo get_option( 'hmgt_hospital_name' );?>"  name="hmgt_hospital_name">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="hmgt_staring_year"><?php _e('Starting Year','hospital_mgt');?></label>
							<div class="col-sm-8">
								<input id="hmgt_staring_year" class="form-control" min="0" type="number" onKeyPress="if(this.value.length==4) return false;" value="<?php echo get_option( 'hmgt_staring_year' );?>"  name="hmgt_staring_year">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="hmgt_hospital_address"><?php _e('Hospital Address','hospital_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="hmgt_hospital_address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text" value="<?php echo get_option( 'hmgt_hospital_address' );?>"  name="hmgt_hospital_address">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="hmgt_contact_number"><?php _e('Official Phone Number','hospital_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="hmgt_contact_number" class="form-control validate[required,custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="<?php echo get_option( 'hmgt_contact_number' );?>" name="hmgt_contact_number">
							</div>
						</div>
						<div class="form-group" class="form-control" id="">
							<label class="col-sm-2 control-label" for="hmgt_contry"><?php _e('Country','hospital_mgt');?></label>
							<div class="col-sm-8">														
							<?php 
							
							$url = plugins_url( 'countrylist.xml', __FILE__ );
							
							if(MJ_hmgt_get_remote_file($url))
							{
								$xml =simplexml_load_string(MJ_hmgt_get_remote_file($url));
							}
							else
							{
								die("Error: Cannot create object");
							}
							?>
							 <select name="hmgt_contry" class="form-control validate[required]" id="smgt_contry">
											<option value=""><?php _e('Select Country','hospital_mgt');?></option>
											<?php
												foreach($xml as $country)
												{  
												?>
												 <option value="<?php echo $country->name;?>" <?php selected(get_option( 'hmgt_contry' ), $country->name);  ?>><?php echo $country->name;?></option>
											<?php }?>
										</select> 
							</div>
						</div>
						
						<div class="form-group">
						<label class="col-sm-2 control-label" for="hmgt_currency_code"><?php _e('Select Currency','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
						<select name="hmgt_currency_code" class="form-control validate[required] text-input">
						  <option value=""> <?php _e('Select Currency','hospital_mgt');?></option>
						  <option value="AUD" <?php echo selected(get_option( 'hmgt_currency_code' ),'AUD');?>>
						  <?php _e('Australian Dollar','hospital_mgt');?></option>
						  <option value="BRL" <?php echo selected(get_option( 'hmgt_currency_code' ),'BRL');?>>
						  <?php _e('Brazilian Real','hospital_mgt');?> </option>
						  <option value="CAD" <?php echo selected(get_option( 'hmgt_currency_code' ),'CAD');?>>
						  <?php _e('Canadian Dollar','hospital_mgt');?></option>
						  <option value="CZK" <?php echo selected(get_option( 'hmgt_currency_code' ),'CZK');?>>
						  <?php _e('Czech Koruna','hospital_mgt');?></option>
						  
						  <option value="COP" <?php echo selected(get_option( 'hmgt_currency_code' ),'COP');?>>
						  <?php _e('Colombia Peso','hospital_mgt');?></option>
						  
						  <option value="DKK" <?php echo selected(get_option( 'hmgt_currency_code' ),'DKK');?>>
						  <?php _e('Danish Krone','hospital_mgt');?></option>
						  <option value="EUR" <?php echo selected(get_option( 'hmgt_currency_code' ),'EUR');?>>
						  <?php _e('Euro','hospital_mgt');?></option>
						  <option value="EGP" <?php echo selected(get_option( 'hmgt_currency_code' ),'EGP');?>>
						  <?php _e('Egypt','hospital_mgt');?></option>
						  <option value="GHC" <?php echo selected(get_option( 'hmgt_currency_code' ),'GHC');?>>
		                   <?php _e('Cedis','hospital_mgt');?></option>
						  <option value="HKD" <?php echo selected(get_option( 'hmgt_currency_code' ),'HKD');?>>
						  <?php _e('Hong Kong Dollar','hospital_mgt');?></option>
						  <option value="HUF" <?php echo selected(get_option( 'hmgt_currency_code' ),'HUF');?>>
						  <?php _e('Hungarian Forint','hospital_mgt');?> </option>
						  <option value="INR" <?php echo selected(get_option( 'hmgt_currency_code' ),'INR');?>>
						  <?php _e('Indian Rupee','hospital_mgt');?></option>
						  <option value="ILS" <?php echo selected(get_option( 'hmgt_currency_code' ),'ILS');?>>
						  <?php _e('Israeli New Sheqel','hospital_mgt');?></option>
						  <option value="JPY" <?php echo selected(get_option( 'hmgt_currency_code' ),'JPY');?>>
						  <?php _e('Japanese Yen','hospital_mgt');?></option>
						  <option value="MYR" <?php echo selected(get_option( 'hmgt_currency_code' ),'MYR');?>>
						  <?php _e('Malaysian Ringgit','hospital_mgt');?></option>
						  <option value="MXN" <?php echo selected(get_option( 'hmgt_currency_code' ),'MXN');?>>
						  <?php _e('Mexican Peso','hospital_mgt');?></option>
						  <option value="NOK" <?php echo selected(get_option( 'hmgt_currency_code' ),'NOK');?>>
						  <?php _e('Norwegian Krone','hospital_mgt');?></option>
						  <option value="NZD" <?php echo selected(get_option( 'hmgt_currency_code' ),'NZD');?>>
						  <?php _e('New Zealand Dollar','hospital_mgt');?></option>
						  <option value="NGN" <?php echo selected(get_option( 'hmgt_currency_code' ),'NGN');?>>
						  <?php _e('Nigeria','hospital_mgt');?></option>
						  
						  <option value="PHP" <?php echo selected(get_option( 'hmgt_currency_code' ),'PHP');?>>
						  <?php _e('Philippine Peso','hospital_mgt');?></option>
						  <option value="PLN" <?php echo selected(get_option( 'hmgt_currency_code' ),'PLN');?>>
						  <?php _e('Polish Zloty','hospital_mgt');?></option>
						  <option value="GBP" <?php echo selected(get_option( 'hmgt_currency_code' ),'GBP');?>>
						  <?php _e('Pound Sterling','hospital_mgt');?></option>
						  <option value="SGD" <?php echo selected(get_option( 'hmgt_currency_code' ),'SGD');?>>
						  <?php _e('Singapore Dollar','hospital_mgt');?></option>
						  <option value="SEK" <?php echo selected(get_option( 'hmgt_currency_code' ),'SEK');?>>
						  <?php _e('Swedish Krona','hospital_mgt');?></option>
						  <option value="CHF" <?php echo selected(get_option( 'hmgt_currency_code' ),'CHF');?>>
						  <?php _e('Swiss Franc','hospital_mgt');?></option>
						  <option value="ZAR" <?php echo selected(get_option( 'hmgt_currency_code' ),'ZAR');?>>
						  <?php _e('South Africa','hospital_mgt');?></option>
						  <option value="TWD" <?php echo selected(get_option( 'hmgt_currency_code' ),'TWD');?>>
						  <?php _e('Taiwan New Dollar','hospital_mgt');?></option>
						  <option value="THB" <?php echo selected(get_option( 'hmgt_currency_code' ),'THB');?>>
						  <?php _e('Thai Baht','hospital_mgt');?></option>
						  <option value="TRY" <?php echo selected(get_option( 'hmgt_currency_code' ),'TRY');?>>
						  <?php _e('Turkish Lira','hospital_mgt');?></option>
						  <option value="USD" <?php echo selected(get_option( 'hmgt_currency_code' ),'USD');?>>
						  <?php _e('U.S. Dollar','hospital_mgt');?></option>
						</select>
						</div>
						<div class="col-sm-1">
							<span style="font-size: 20px;"><?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' ));?></span>
						</div>

						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="hmgt_email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="hmgt_email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text" value="<?php echo get_option( 'hmgt_email' );?>"  name="hmgt_email">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label" for="hmgt_currency_code"><?php _e('Date Format','hospital_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
							   
						  <select name="MJ_hmgt_date_formate" class="form-control validate[required] text-input">
						  <option value=""> <?php _e('Select Date Format','hospital_mgt');?></option>
						  <option value="Y-m-d" <?php echo selected(get_option( 'MJ_hmgt_date_formate' ),'Y-m-d');?>>
						  <?php _e('2017-12-12','hospital_mgt');?></option>
						  <option value="m/d/Y" <?php echo selected(get_option( 'MJ_hmgt_date_formate' ),'m/d/Y');?>>
						  <?php _e('12/31/2017','hospital_mgt');?></option>
						   <option value="d/m/Y" <?php echo selected(get_option( 'MJ_hmgt_date_formate' ),'d/m/Y');?>>
						  <?php _e('31/12/2017','hospital_mgt');?></option>  
						  <option value="F j, Y" <?php echo selected(get_option( 'MJ_hmgt_date_formate' ),'F j, Y');?>>
						  <?php _e('December 12, 2017','hospital_mgt');?></option>
						</select>
							</div>
						</div>						
						<!-- notification template   -->
						<div class="form-group">
							<label class="col-sm-2 control-label" for="hospital_enable_notifications"><?php _e('Enable Notifications','hospital_mgt');?></label>
							<div class="col-sm-8">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="hospital_enable_notifications"  value="1" <?php echo checked(get_option('hospital_enable_notifications'),'yes');?>/><?php _e('Enable','hospital_mgt');?>
								  </label>
							  </div>
							</div>
						</div>
						<!-- end notification template   -->
						
						<div class="form-group">
							<label class="col-sm-2 control-label" for="hmgt_email"><?php _e('Hospital Logo','hospital_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8 ">
							<input type="text" id="hmgt_user_avatar_url"  name="hmgt_hospital_logo" class="validate[required] margin_bottom_5px" value="<?php  echo get_option( 'hmgt_hospital_logo' ); ?>" readonly />
									 <input id="upload_user_avatar_button" type="button" class="button" value="<?php _e( 'Upload image', 'hospital_mgt' ); ?>" />
									 <span class="description"><?php _e('Upload image.', 'hospital_mgt' ); ?></span>
									 
									 <div id="upload_user_avatar_preview" style="min-height: 100px;">
										<img class="image_preview_css" src="<?php  echo get_option( 'hmgt_hospital_logo' ); ?>" /></div>
						</div>
						</div>
							<div class="form-group">
							<label class="col-sm-2 control-label" for="hmgt_cover_image"><?php _e('Profile Cover Image','hospital_mgt');?></label>
							<div class="col-sm-8 ">
							
							<input type="text" class="margin_bottom_5px" id="hmgt_hospital_background_image" name="hmgt_hospital_background_image" value="<?php 		 echo get_option( 'hmgt_hospital_background_image' ); ?>" readonly />	
									  <input id="upload_image_button" type="button" class="button upload_user_cover_button" value="<?php _e( 'Upload Cover Image', 'hospital_mgt' ); ?>" />
									 <span class="descriptions"><?php _e('Upload Cover Image', 'hospital_mgt' ); ?></span>
									 
									<div id="upload_hospital_cover_preview" style="min-height: 100px;margin-top:5px;">
										<img style="width:100%;" src="<?php  echo get_option( 'hmgt_hospital_background_image' ); ?>" />
									</div>
								</div>
						</div>												
						<div class="header">	<hr>
							<h3><?php _e('User Can Change Profile Picture Setting','hospital_mgt');?></h3>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="hmgt_enable_change_profile_picture"><?php _e("User Can Change Profile Picture","hospital_mgt");?></label>
							<div class="col-sm-8">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="hmgt_enable_change_profile_picture"  value="yes" <?php echo checked(get_option('hmgt_enable_change_profile_picture'),'yes');?>/><?php _e('Enable','hospital_mgt');?>
								  </label>
							  </div>
							</div>
						</div>
						
						<div class="header">	<hr>
							<h3><?php _e('Keeps Hospital Name In Prescription print','hospital_mgt');?></h3>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="hmgt_enable_hospitalname_in_priscription"><?php _e("Hospital Name print in Prescription","hospital_mgt");?></label>
							<div class="col-sm-8">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="hmgt_enable_hospitalname_in_priscription"  value="yes" <?php echo checked(get_option('hmgt_enable_hospitalname_in_priscription'),'yes');?>/><?php _e('Enable','hospital_mgt');?>
								  </label>
							  </div>
							</div>
						</div>
						<div class="header">	
							<hr>
							<h3><?php _e('Message Setting','hospital_mgt');?></h3>
						</div>
						<div class="form-group margin_bottom_5px">
							<label class="col-sm-2 control-label" for="hmgt_enable_staff_can_message"><?php _e("Staff can Message To Admin","hospital_mgt");?></label>
							<div class="col-sm-8 margin_bottom_5px">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="hmgt_enable_staff_can_message"  value="yes" <?php echo checked(get_option('hmgt_enable_staff_can_message'),'yes');?>/><?php _e('Enable','hospital_mgt');?>
								  </label>
							  </div>
							</div>
						</div>						
						<div class="col-sm-offset-2 col-sm-8">							
							<input type="submit" value="<?php _e('Save', 'hospital_mgt' ); ?>" name="save_setting" class="btn btn-success"/>
						</div>				
						
						</form>
					</div><!-- PANEL BODY DIV END-->	
                </div><!-- PANEL BODY DIV END-->	
        </div><!-- PANEL WHITE DIV END-->
    </div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->