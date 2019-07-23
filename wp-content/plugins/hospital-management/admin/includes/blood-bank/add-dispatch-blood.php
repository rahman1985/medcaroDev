<?php 
$obj_bloodbank=new MJ_hmgt_bloodbank();
?>
<script type="text/javascript">
jQuery(document).ready(function($)
{
	$('#dispatch_blood_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
    $('#date').datepicker({
		endDate: '+0d',
        autoclose: true,
	}); 
	$('#tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
} );
</script>
    <?php 	
	if($active_tab == 'adddispatchblood')	
	{
		MJ_hmgt_browser_javascript_check();
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_bloodbank->get_single_dispatch_blood_data($_REQUEST['dispatchblood_id']);	
		}
		?>
    <div class="panel-body"><!-- PANEL BODY DIV START-->
        <form name="dispatch_blood_form" action="" method="post" class="form-horizontal" id="dispatch_blood_form">
			 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="old_blood_group" value="<?php if($edit){ echo $result->blood_group; } ?>">
			<input type="hidden" name="old_blood_status" value="<?php if($edit){ echo $result->blood_status; } ?>">
			<input type="hidden" name="dispatchblood_id" value="<?php if(isset($_REQUEST['dispatchblood_id'])) echo $_REQUEST['dispatchblood_id'];?>"  />		
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select name="patient_id" id="" class="form-control validate[required] ">
						<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
						<?php 
						if($edit)
							$patient_id1 = $result->patient_id;						
						elseif(isset($_REQUEST['patient_id']))
							$patient_id1 = $_REQUEST['patient_id'];
						else 
							$patient_id1 = "";
						
						$patients = MJ_hmgt_inpatient_list();
						
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
				<label class="col-sm-2 control-label" for="bloodgruop"><?php _e('Blood Group','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<?php if($edit){ $userblood=$result->blood_group; }elseif(isset($_POST['blood_group'])){$userblood=$_POST['blood_group'];}else{$userblood='';}?>
					<select id="blood_group" class="form-control validate[required] selected_blood_group" name="blood_group">
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
					<input id="blood_status" class="form-control validate[required] text-input dispatch_blood_status_check" type="number" min="1" onKeyPress="if(this.value.length==1) return false;" value="<?php if($edit){ echo $result->blood_status;}elseif(isset($_POST['blood_status'])) echo $_POST['blood_status'];?>" name="blood_status">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for=""><?php _e('Charge','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
				<div class="col-sm-8">				
					<input id="" class="form-control validate[required]" min="0" type="number" onKeyPress="if(this.value.length==8) return false;"  step="0.01" value="<?php if($edit){ echo $result->blood_charge;}elseif(isset($_POST['blood_charge'])) echo $_POST['blood_charge'];?>" name="blood_charge">				
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
			<div class="form-group margin_bottom_5px">
				<label class="col-sm-2 control-label" for="last_donet_date"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="date" class="form-control validate[required]" type="text"  value="<?php if($edit){ echo date(
			   MJ_hmgt_date_formate(),strtotime($result->date));}elseif(isset($_POST['date'])) echo $_POST['date'];?>" name="date">
				</div>
			</div>			
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_dispatch_blood" class="btn btn-success"/>
			</div>
	    </form>
    </div><!-- PANEL BODY DIV END-->
<?php 
 }
?>