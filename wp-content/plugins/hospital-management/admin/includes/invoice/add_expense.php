<?php 
MJ_hmgt_browser_javascript_check();
$obj_invoice= new MJ_hmgt_invoice();
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#expense_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
		$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
		$('#invoice_date').datepicker({
		autoclose: true
	   }); 
} );
</script>
<?php 	
if($active_tab == 'addexpense')
{
	$expense_id=0;
	if(isset($_REQUEST['expense_id']))
	$expense_id=$_REQUEST['expense_id'];
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$result = $obj_invoice->hmgt_get_income_data($expense_id);
	}?>
    <div class="panel-body"><!-- PANEL BODY DIV START-->
	    <form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="expense_id" value="<?php echo $expense_id;?>">
			<input type="hidden" name="invoice_type" value="expense">		
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient"><?php _e('Supplier Name','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="party_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="30" type="text" value="<?php if($edit){ echo $result->party_name;}elseif(isset($_POST['party_name'])) echo $_POST['party_name'];?>" name="party_name">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="payment_status"><?php _e('Status','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select name="payment_status" id="payment_status" class="form-control validate[required]">
						
						<option value="<?php _e('Paid','hospital_mgt'); ?>"
							<?php if($edit)selected( __('Paid','hospital_mgt'),$result->payment_status);?> ><?php _e('Paid','hospital_mgt');?></option>
					   <option value="<?php _e('Part Paid','hospital_mgt'); ?>"
							<?php if($edit)selected( __('Part Paid','hospital_mgt'),$result->payment_status);?>><?php _e('Part Paid','hospital_mgt');?></option>
						<option value="<?php _e('Unpaid','hospital_mgt'); ?>"
							<?php if($edit)selected( __('Unpaid','hospital_mgt'),$result->payment_status);?>><?php _e('Unpaid','hospital_mgt');?></option>
				    </select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="invoice_date" class="form-control validate[required]" type="text"  value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->income_create_date));}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date(MJ_hmgt_date_formate());}?>" name="invoice_date">
				</div>
			</div>
			<hr>
			<?php 
				if($edit){
					$all_entry=json_decode($result->income_entry);
				}
				else
				{
					if(isset($_POST['income_entry'])){
						
						$all_data=$obj_invoice->get_entry_records($_POST);
						$all_entry=json_decode($all_data);
					}
				}
				if(!empty($all_entry))
				{
					foreach($all_entry as $entry){
					?>
					<div id="expense_entry">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Expense Entry','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2 margin_bottom_5px">
							<input id="income_amount" class="form-control validate[required] text-input"  min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01" value="<?php echo $entry->amount;?>" name="income_amount[]" >
						</div>
						<div class="col-sm-4 margin_bottom_5px">
							<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php echo $entry->entry;?>" name="income_entry[]">
						</div>
						
						<div class="col-sm-2">
						<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
						</button>
						</div>
						</div>	
					</div>
					<?php }
					
				}
				else
				{?>
					<div id="expense_entry">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Expense Entry','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2 margin_bottom_5px">
							<input id="income_amount" class="form-control validate[required] text-input"  min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01"  value="" name="income_amount[]" placeholder="<?php _e('Expense Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)">
						</div>
						<div class="col-sm-4">
							<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="income_entry[]" placeholder="<?php _e('Expense Entry Label','hospital_mgt');?>">
						</div>
						
						<div class="col-sm-2">
						</div>
						</div>	
					</div>
			<?php }?>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="expense_entry"></label>
				<div class="col-sm-3">
					
					<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add Expense Entry','hospital_mgt'); ?>
					</button>
				</div>
			</div>
			<hr>
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save Expense','hospital_mgt'); }else{ _e('Create Expense Entry','hospital_mgt');}?>" name="save_expense" class="btn btn-success"/>
			</div>
		</form>
    </div><!-- PANEL BODY DIV END-->
    <script>
   	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	
   	function add_entry()
   	{
   		$("#expense_entry").append('<div class="form-group"><label class="col-sm-2 control-label" for="income_entry"><?php _e('Expense Entry','hospital_mgt');?><span class="require-field">*</span></label><div class="col-sm-2 margin_bottom_5px"><input id="income_amount" class="form-control validate[required] text-input"  min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01" value="" name="income_amount[]" placeholder="<?php _e('Expense Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)"></div><div class="col-sm-4 margin_bottom_5px"><input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="income_entry[]" placeholder="<?php _e('Expense Entry Label','hospital_mgt');?>"></div><div class="col-sm-2"><button type="button" class="btn btn-default" onclick="deleteParentElement(this)"><i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i></button></div></div>');
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n)
	{
		alert("<?php _e('Do you really want to delete this record ?','hospital_mgt');?>");
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}
    </script> 
<?php 
}
?>