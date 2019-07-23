<?php 
$obj_bloodbank=new MJ_hmgt_bloodbank();
?>
<script type="text/javascript">
jQuery(document).ready(function($)
{
	$('#blooddonor_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
      $('#last_donate_date').datepicker({
     endDate: '+0d',
        autoclose: true,
   }); 
} );
</script>
     <?php 	
	if($active_tab == 'addblooddonor')	
	{
		 MJ_hmgt_browser_javascript_check();
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
		$edit=1;
		$result = $obj_bloodbank->get_single_blooddonor($_REQUEST['blooddonor_id']);	
	}?>
    <div class="panel-body"><!-- PANEL BODY DIV START-->
        <form name="blooddonor_form" action="" method="post" class="form-horizontal" id="blooddonor_form">
			 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="old_blood_group" value="<?php if($edit){ echo $result->blood_group; } ?>">
			<input type="hidden" name="old_blood_status" value="<?php if($edit){ echo $result->blood_status; } ?>">
			<input type="hidden" name="blooddonor_id" value="<?php if(isset($_REQUEST['blooddonor_id'])) echo $_REQUEST['blooddonor_id'];?>"  />
			
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="first_name"><?php _e('Full Name','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="bool_dodnor_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input"  maxlength="50" type="text" value="<?php if($edit){ echo $result->donor_name;}elseif(isset($_POST['bool_dodnor_name'])) echo $_POST['bool_dodnor_name'];?>" name="bool_dodnor_name">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
				<?php $genderval = "male"; if($edit){ $genderval=$result->donor_gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
					<label class="radio-inline">
					 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php _e('Male','hospital_mgt');?>
					</label>
					<label class="radio-inline">
					  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','hospital_mgt');?> 
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="med_category_name"><?php _e('Age','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="dodnor_age" class="form-control validate[required,custom[integer],min[18]] text-input" min="0" max="99" type="number" onKeyPress="if(this.value.length==2) return false;"  value="<?php if($edit){ echo $result->donor_age;}elseif(isset($_POST['dodnor_age'])) echo $_POST['dodnor_age'];?>" name="dodnor_age">
				</div>
			</div>
			<div class="form-group">	
				<label class="col-sm-2 control-label " for="phone"><?php _e('Phone','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="phone" class="form-control validate[required,custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="<?php if($edit){ echo $result->donor_phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>" name="phone">					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label " for="email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="email" class="form-control validate[required,custom[email]] text-input" type="text" maxlength="100"  name="email" 
					value="<?php if($edit){ echo $result->donor_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="bloodgruop"><?php _e('Blood Group','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<?php if($edit){ $userblood=$result->blood_group; }elseif(isset($_POST['blood_group'])){$userblood=$_POST['blood_group'];}else{$userblood='';}?>
					<select id="blood_group" class="form-control validate[required]" name="blood_group">
					<option value=""><?php _e('Select Blood Group','hospital_mgt');?></option>
					<?php foreach(MJ_hmgt_blood_group() as $blood){ ?>
							<option value="<?php echo $blood;?>" <?php selected($userblood,$blood);  ?>><?php echo $blood; ?> </option>
					<?php } ?>
				</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="blood_status"><?php _e('Number Of Bags','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="blood_status" class="form-control validate[required] text-input" type="number" min="1" onKeyPress="if(this.value.length==1) return false;" value="<?php if($edit){ echo $result->blood_status;}elseif(isset($_POST['blood_status'])) echo $_POST['blood_status'];?>" name="blood_status">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="last_donet_date"><?php _e('Last Donation Date','hospital_mgt');?></label>
				<div class="col-sm-8 margin_bottom_5px">
					<input id="last_donate_date" class="form-control " type="text"  value="<?php if($edit){ echo date(
			   MJ_hmgt_date_formate(),strtotime($result->last_donet_date));}elseif(isset($_POST['last_donate_date'])) echo $_POST['last_donate_date'];?>" name="last_donate_date">
				</div>
			</div>
			
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_blooddonor" class="btn btn-success"/>
			</div>
	    </form>
    </div><!-- PANEL BODY DIV END-->
<?php 
 }
?>