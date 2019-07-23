<?php 
	MJ_hmgt_browser_javascript_check();
	$role='patient';
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#patient_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#request_time').timepicki(
	{
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:15,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: true}
		);
	$('#dispatch_time').timepicki(
	{
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:15,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: true}
		);
	
   var date = new Date();
            date.setDate(date.getDate()-0);
	        $.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
            $('.request_date').datepicker({
	        startDate: date,
            autoclose: true
           }); 
	$('#tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
} );
</script>
    <?php 	
	if($active_tab == 'add_ambulance_req')	
	{
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{			
			$edit=1;
			$result= $obj_ambulance->get_single_ambulance_req($_REQUEST['amb_req_id']);	
		}
		?>	
        <div class="panel-body"><!-- PANEL BODY DIV START-->
			<form name="patient_form" action="" method="post" class="form-horizontal" id="patient_form">
				 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="amb_req_id" value="<?php if(isset($_REQUEST['amb_req_id']))echo $_REQUEST['amb_req_id'];?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="ambulance_id"><?php _e('Ambulance','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select name="ambulance_id" class="form-control validate[required]" id="ambulance_id">
							<option value=""><?php _e('Select Ambulance','hospital_mgt');?></option>
							<?php 
							if($edit)
								$amb_id = $result->ambulance_id;
							elseif(isset($_REQUEST['ambulance_id']))
								$amb_id = $_REQUEST['ambulance_id'];
							else 	
								$amb_id = "";
								$ambulance_data=$obj_ambulance->get_all_ambulance();
								if(!empty($ambulance_data))
								{
									foreach ($ambulance_data as $retrieved_data)
									{ 
										echo '<option value = '.$retrieved_data->amb_id.' '.selected($amb_id,$retrieved_data->amb_id).'>'.$retrieved_data->ambulance_id.'</option>';
									}
								}						
							 ?>
						</select>						
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient','hospital_mgt');?></label>
					<div class="col-sm-8">
						<select name="patient_id" id="patient_id" class="form-control validate[required] patient_address">
							<option><?php _e('Select Patient','hospital_mgt');?></option>
							<?php 
							if($edit)
								$patient_id1 = $result->patient_id;
							elseif(isset($_REQUEST['patient_id']))
								$patient_id1 = $_REQUEST['patient_id'];
							else 
								$patient_id1 = "";
							$patients = MJ_hmgt_patientid_list();
							
							if(!empty($patients))
							{
							foreach($patients as $patient)
							{
								echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['patient_id'].' - '.$patient['first_name'].' '.$patient['last_name'].'</option>';
							
							}
							}
							?>
						</select>
						
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="address"><?php _e('Address','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<textarea name = "address" id="address" maxlength="150" class="form-control validate[required,custom[address_description_validation]]"><?php if($edit){ echo $result->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="charges"><?php _e('Charges','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="charges" class="form-control validate[required]" min="0" type="number" onKeyPress="if(this.value.length==8) return false;" step="0.01"  value="<?php if($edit){ echo $result->charge;}elseif(isset($_POST['charge'])) echo $_POST['charge'];?>" name="charge">
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-2 control-label" for="visiting_fees"><?php _e('Tax','hospital_mgt');?></label>
					<div class="col-sm-2">
						<select  class="form-control" id="tax_charge" name="tax[]" multiple="multiple">					
							<?php					
							if($edit)
							{
								$tax_id=explode(',',$result->tax);
							}
							else
							{	
								$tax_id[]='';
							}
							$obj_invoice= new MJ_hmgt_invoice();
							$hmgt_taxs=$obj_invoice->get_all_tax_data();	
							
							if(!empty($hmgt_taxs))
							{
								foreach($hmgt_taxs as $entry)
								{
									$selected = "";
									if(in_array($entry->tax_id,$tax_id))
										$selected = "selected";
									?>
									<option value="<?php echo $entry->tax_id; ?>" <?php echo $selected; ?> ><?php echo $entry->tax_title;?>-<?php echo $entry->tax_value;?></option>
								<?php 
								}
							}
							?>
						</select>		
					</div>
				</div>		
				<div class="form-group">
					<label class="col-sm-2 control-label" for="request_date"><?php _e('Request Date','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="request_date" class="form-control validate[required] request_date" type="text"   value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->request_date)) ;}elseif(isset($_POST['request_date'])) echo $_POST['request_date'];?>" name="request_date">
					</div>
				</div>
				
			
				<div class="form-group">
					<label class="col-sm-2 control-label" for="request_time"><?php _e('Request Time','hospital_mgt');?></label>
					<div class="col-sm-8">
						<input id="request_time" class="form-control request_time" 
						type="text"  value="<?php if($edit){ echo $result->request_time;}elseif(isset($_POST['request_time'])) echo $_POST['request_time'];?>" name="request_time">
					</div>
				</div>
				<div class="form-group margin_bottom_5px">
					<label class="col-sm-2 control-label" for="dispatch_time"><?php _e('Dispatch Time','hospital_mgt');?></label>
					<div class="col-sm-8">
						<input id="dispatch_time" class="form-control dispatch_time"  data-default-time="02:25" type="text"  value="<?php if($edit){ echo $result->dispatch_time;}elseif(isset($_POST['dispatch_time'])) echo $_POST['dispatch_time'];?>" name="dispatch_time">
					</div>
				</div>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_ambulance_request" class="btn btn-success"/>
				</div>
			</form>
        </div><!-- PANEL BODY DIV END-->        
     <?php 
	 }
	 ?>