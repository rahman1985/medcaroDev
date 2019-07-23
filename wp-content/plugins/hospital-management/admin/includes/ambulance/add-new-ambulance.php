<?php 
	MJ_hmgt_browser_javascript_check();
  	
	if($active_tab == 'add_ambulance')	
	{
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{					
			$edit=1;
			$result= $obj_ambulance->get_single_ambulance($_REQUEST['amb_id']);			
		}
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($)
		{
			$('#patient_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
			
		} );
		</script>
        <div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="patient_form" action="" method="post" class="form-horizontal" id="patient_form">
				 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="amb_id" value="<?php if(isset($_REQUEST['amb_id'])) echo $_REQUEST['amb_id'];?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="ambulance_id"><?php _e('Ambulance Id','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="ambulance_id" class="form-control validate[required]" type="text" readonly value="<?php if($edit){ echo $result->ambulance_id;}elseif(isset($_POST['ambulance_id'])) echo $_POST['ambulance_id']; else echo $obj_ambulance->generate_ambulance_id();?>" name="ambulance_id">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="amb_Reg_number"><?php _e('Registration Number','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="amb_Reg_number" class="form-control validate[required]" min="0" type="number" onKeyPress="if(this.value.length==6) return false;" value="<?php if($edit){ echo $result->registerd_no;}elseif(isset($_POST['registerd_no'])) echo $_POST['registerd_no'];?>" name="registerd_no">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="amb_driver_name"><?php _e('Driver Name','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="amb_driver_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]]" type="text"  maxlength="50" value="<?php if($edit){ echo $result->driver_name;}elseif(isset($_POST['driver_name'])) echo $_POST['driver_name'];?>" name="driver_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="amb_driver_address"><?php _e('Driver Address','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="amb_driver_address" class="form-control validate[required,custom[address_description_validation]]" type="text"  maxlength="150" value="<?php if($edit){ echo $result->driver_address;}elseif(isset($_POST['driver_address'])) echo $_POST['driver_address'];?>" name="driver_address">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="amb_phone_number"><?php _e('Driver Phone Number','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="amb_phone_number" class="form-control validate[required,custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="<?php if($edit){ echo $result->driver_phoneno;}elseif(isset($_POST['driver_phoneno'])) echo $_POST['driver_phoneno'];?>" name="driver_phoneno">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="discription"><?php _e('Description','hospital_mgt');?></label>
					<div class="col-sm-8">
						<input id="discription" class="form-control validate[custom[address_description_validation]]" maxlength="150" type="text"  value="<?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?>" name="description">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="driver_image"><?php _e('Driver Image','hospital_mgt');?></label>
					<div class="col-sm-2 margin_bottom_5px">
						 <input type="text" id="hmgt_user_avatar_url" class="form-control" name="driver_image" value="<?php if($edit)echo esc_url( $result->driver_image ); ?>" readonly />
						 </div>
						<div class="col-sm-4">
							 <input id="upload_user_avatar_button" type="button" class="button" value="<?php _e( 'Upload image', 'hospital_mgt' ); ?>" />
							 <span class="description"><?php _e('Upload image', 'hospital_mgt' ); ?></span>
						   </div>
					<div class="clearfix"></div>					
					<div class="col-sm-offset-2 col-sm-8 margin_bottom_5px">  
						 <div id="upload_user_avatar_preview">
						 <br>
						 <?php if($edit) 
								{
								if($result->driver_image == "")
								{
									?><img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_driver_thumb' ) ?>" height="100px" width="100px"><?php 
								}
								else {
									?>
								
								<img  class="image_preview_css" src="<?php if($edit)echo esc_url( $result->driver_image ); ?>" />
								<?php 
								}
								}
								else {
									?>
									
									<img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_driver_thumb' ) ?>" height="150px" width="150px">
									<?php 
								}?>  
						</div>
					</div>							
				</div>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_ambulance" class="btn btn-success"/>
				</div>
			</form>
        </div><!-- PANEL BODY DIV END-->
    <?php 
	}
	?>