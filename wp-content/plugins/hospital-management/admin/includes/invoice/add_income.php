<?php 
MJ_hmgt_browser_javascript_check();
$obj_invoice= new MJ_hmgt_invoice();
?>
<script type="text/javascript">
jQuery(document).ready(function($) 
{
	$('#income_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	        $.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
            $('#invoice_date').datepicker({
            autoclose: true
           }); 
});
</script>
<?php 	
if($active_tab == 'addincome')
{
	$income_id=0;
	if(isset($_REQUEST['income_id']))
		$income_id=$_REQUEST['income_id'];
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$result = $obj_invoice->hmgt_get_income_data($income_id);
	}
	if(isset($_REQUEST['invoice_id']))
	{	
	?>
		<style>
		.add_more_entry_div,.payment_status_div
		{
			display:none;	
		}	
		</style>	
	<?php
	}
	?>
    <div class="panel-body"><!-- PANEL BODY DIV START-->
        <form name="income_form" action="" method="post" class="form-horizontal" id="income_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="income_id" value="<?php echo $income_id;?>">
			<input type="hidden" name="invoice_id" value="<?php if(isset($_REQUEST['invoice_id']))
			{ echo $_REQUEST['invoice_id']; } ?>">			
			<input type="hidden" name="invoice_type" value="income">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">					
					<?php 
					if($edit)
					{
						$patient_id1=$result->party_name; 
					}
					elseif(isset($_REQUEST['patient_id']))
					{
						$patient_id1 = $_REQUEST['patient_id'];
					}
					elseif(isset($_POST['patient']))
					{
						$patient_id1=$_POST['patient'];
					}
					else
					{
						$patient_id1="";
					}
					?>
					<select name="party_name" class="form-control validate[required]">
					<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
					<?php						
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
			<div class="form-group payment_status_div">
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
				<label class="col-sm-2 control-label" for="payment_method"><?php _e('Payment Method','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">			
					<select name="payment_method" id="payment_method" class="form-control">					
						<option value="<?php _e('Cash','hospital_mgt');?>" <?php if($edit)selected( __('Cash','hospital_mgt'),$result->payment_method);?>><?php _e('Cash','hospital_mgt');?></option>
						<option value="<?php _e('Cheque','hospital_mgt');?>" <?php if($edit)selected( __('Cheque','hospital_mgt'),$result->payment_method);?>><?php _e('Cheque','hospital_mgt');?></option>
						<option value="<?php _e('Bank Transfer','hospital_mgt');?>" <?php if($edit)selected( __('Bank Transfer','hospital_mgt'),$result->payment_method);?>><?php _e('Bank Transfer','hospital_mgt');?></option>		
						<option value="<?php _e('Credit Card Or Debit Card','hospital_mgt');?>" <?php if($edit)selected( __('Credit Card Or Debit Card','hospital_mgt'),$result->payment_method);?>><?php _e('Credit Card Or Debit Card','hospital_mgt');?></option>	
					</select>
				</div>
			</div>
			<div class="form-group">			
				<label class="col-sm-2 control-label" for=""><?php _e('Payment Details','hospital_mgt');?></label>
				<div class="col-sm-8">			
					<textarea name="payment_description" class="form-control validate[custom[address_description_validation]]" maxlength="150" id="notice_content"><?php if($edit){ echo $result->payment_description; }?></textarea>					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="invoice_date" class="form-control " type="text"  value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->income_create_date));}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date(MJ_hmgt_date_formate());}?>" name="invoice_date">
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
						foreach($all_entry as $entry)
						{
						?>
						<div id="income_entry">
							<div class="form-group">
							<label class="col-sm-2 control-label" for="income_entry"><?php _e('Income Entry','hospital_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-2 margin_bottom_5px">
								<input id="income_amount" class="form-control validate[required] text-input"  min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01"  value="<?php echo $entry->amount;?>" name="income_amount[]">
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
						<?php
						}				
				}
				else
				{
				?>
						<div id="income_entry">
							<div class="form-group">
							<label class="col-sm-2 control-label" for="income_entry"><?php _e('Income Entry','hospital_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-2 margin_bottom_5px">
								<input id="income_amount" class="form-control validate[required] text-input"  min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01"  value="<?php if(isset($_REQUEST['invoice_id'])){	echo $_REQUEST['due_amount']; } ?>" <?php if(isset($_REQUEST['invoice_id'])){ ?> max="<?php echo $_REQUEST['due_amount']; ?>" <?php } ?> name="income_amount[]" placeholder="<?php _e('Income Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)">
							</div>
							<div class="col-sm-4">
								<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if(isset($_REQUEST['invoice_id'])){	echo 'Invoice Income'; } ?>" name="income_entry[]" placeholder="<?php _e('Income Entry Label','hospital_mgt');?>">
							</div>						
							<div class="col-sm-2">							
							</div>
							</div>	
						</div>
						
			<?php
			}
			?>			
			<div class="form-group add_more_entry_div">
				<label class="col-sm-2 control-label" for="income_entry"></label>
				<div class="col-sm-3">					
					<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add Income Entry','hospital_mgt'); ?>
					</button>
				</div>
			</div>
			<hr>
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save Income','hospital_mgt'); }else{ _e('Create Income Entry','hospital_mgt');}?>" name="save_income" class="btn btn-success"/>
			</div>
        </form>
    </div><!-- PANEL BODY DIV END-->
    <script>   
   	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	function add_entry()
   	{   		
   		$("#income_entry").append('<div class="form-group"><label class="col-sm-2 control-label" for="income_entry"><?php _e('Income Entry','hospital_mgt');?><span class="require-field">*</span></label><div class="col-sm-2 margin_bottom_5px"><input id="income_amount" class="form-control validate[required] text-input"  min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01" value="" name="income_amount[]" placeholder="<?php _e('Income Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)"></div><div class="col-sm-4 margin_bottom_5px"><input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="income_entry[]" placeholder="<?php _e('Income Entry Label','hospital_mgt');?>"></div><div class="col-sm-2"><button type="button" class="btn btn-default" onclick="deleteParentElement(this)"><i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i></button></div></div>');   		
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