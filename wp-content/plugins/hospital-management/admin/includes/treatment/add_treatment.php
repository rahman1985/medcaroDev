<?php 
MJ_hmgt_browser_javascript_check();
//Add Treatment
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$treatment_id = $_REQUEST['treatment_id'];
	$result = $obj_treatment->get_single_treatment($treatment_id);
}
?>
	<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#treatment_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
} );
</script>
   <!--PANEL BODY START-->
    <div class="panel-body">
		<form name="treatment_form" action="" method="post" class="form-horizontal" id="treatment_form">
			 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="treatment_id" value="<?php if(isset($_REQUEST['treatment_id'])) echo $_REQUEST['treatment_id'];?>"  />
			<div class="form-group">
				<label class="col-sm-2 control-label" for="med_category_name"><?php _e('Treatment Name','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="treatment_name" class="form-control validate[required,custom[popup_category_validation]]  text-input" maxlength="50" type="text" 
					value="<?php if($edit){ echo $result->treatment_name;}elseif(isset($_POST['treatment_name'])) echo $_POST['treatment_name'];?>" name="treatment_name">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="treatment_price"><?php _e('Treatment Price','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
				<div class="col-sm-8">
					<input id="treatment_price" class="form-control" type="number" min="0" onKeyPress="if(this.value.length==10) return false;" step="0.01"  value="<?php if($edit){ echo $result->treatment_price;}elseif(isset($_POST['treatment_price'])) echo $_POST['treatment_price'];?>" name="treatment_price">
				</div>
			</div>
			<div class="form-group margin_bottom_5px">
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
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_treatment" class="btn btn-success"/>
			</div>
		</form>
    </div> <!-- END PANEL BODY-->