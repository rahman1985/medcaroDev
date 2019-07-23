<?php 
MJ_hmgt_browser_javascript_check();
$obj_invoice= new MJ_hmgt_invoice();
?>
<script type="text/javascript">
jQuery(document).ready(function($) 
{
	$('#invoice_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
	$('#invoice_date').datepicker({
	autoclose: true
   }); 
});
</script>
<?php 	
if($active_tab == 'addinvoice')
{
	global $wpdb;	
	$table_hmgt_patient_transation = $wpdb->prefix. 'hmgt_patient_transation';
	
	$transaction_ids = array();
	$transationdata = array();
	$amount = array();
	$discount_amount = array();
	$tax_amount = array();
	$total_amount = array();
	
	if(isset($_POST['get_totale']))
	{		
		$total=0;
		$amount =array();
		$discount_amount = array();
		$tax_amount =array();
		$total_amount =array();
		$transaction_ids =array();
		if(isset($_POST['amount']))	
		{
			$amount = $_POST['amount'];	
		}
		if(isset($_POST['discount_amount']))	
		{
			$discount_amount = $_POST['discount_amount'];	
		}
		if(isset($_POST['tax_amount']))	
		{
			$tax_amount = $_POST['tax_amount'];	
		}
		if(isset($_POST['total_amount']))	
		{
			$total_amount = $_POST['total_amount'];	
		}
		if(isset($_POST['transaction_ids']))
		{	
			$transaction_ids = $_POST['transaction_ids'];
		}			
		if(isset($_POST['cheak']))
		{
			foreach($_POST['cheak'] as $key=>$val)
			{			 
				$total = $total + $total_amount[$key];				
				$trasationdata['type_value'] =$amount[$key];							
				$trasationdata['type_discount'] =$discount_amount[$key];							
				$trasationdata['type_tax'] =$tax_amount[$key];							
				$trasationdata['type_total_value'] =$total_amount[$key];
				
				$whereid['id']= $transaction_ids[$key];
				$wpdb->update($table_hmgt_patient_transation,$trasationdata,$whereid);	
				
				if(isset($transaction_ids[$key]))
					$transationdata[$key]=$transaction_ids[$key];				
			} 	
		}
		if(!empty($_POST['newentry']))
		{	
			$new_entry_id=array();
			foreach($_POST['newentry'] as $key=>$val)
			{			
				$total = $total + $total_amount[$key];
				$trasationdata['type'] =$_POST['type'][$key]; 
				$trasationdata['type_id'] = $_POST['title'][$key];  
				$trasationdata['status'] ="Unpaid"; 
				$trasationdata['patient_id'] =$_POST['patient_id']; 
				$trasationdata['date'] =date("Y-m-d"); 		
				$trasationdata['unit'] = MJ_hmgt_strip_tags_and_stripslashes($_POST['unit'][$key]);  
				$trasationdata['type_value'] =$amount[$key];							
				$trasationdata['type_discount'] =$discount_amount[$key];							
				$trasationdata['type_tax'] =$tax_amount[$key];							
				$trasationdata['type_total_value'] =$total_amount[$key];							
				$wpdb->insert( $table_hmgt_patient_transation, $trasationdata );	
				$lastid = $wpdb->insert_id;	
				$new_entry_id[]=$lastid;	
			}		
		}
	} 
	$invoice_id=0;
	if(isset($_REQUEST['invoice_id']))
		$invoice_id=$_REQUEST['invoice_id'];
		$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$result = $obj_invoice->hmgt_get_invoice_data($invoice_id);
	} ?>
	<!-- VIEW POPUP CODE START-->
	<div class="popup-bg" style="background: rgba(0,0,0,0);">
		<div class="overlay-content invoice_entry_popup">   
			<div class="patient_invoice"></div>     
		</div>     
	</div>	<!-- VIEW POPUP CODE END-->
    <div class="panel-body"><!-- PANEL BODY DIV START-->
        <form name="invoice_form" action="" method="post" class="form-horizontal" id="invoice_form">
		 	<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="invoice_id" value="<?php echo $invoice_id;?>">
			<input type="hidden" name="paid_amount" value="<?php if($edit){ echo $result->paid_amount;} else{ echo 0;} ?>">
			
			<?php if(!empty($transationdata)){ 
				foreach($transationdata as $key=>$value){ ?>
				<input type="hidden" name="transationdata[<?php echo $key ?>]" value="<?php echo $value; ?>">
			<?php } 
			} ?>
			
			<?php 
			if(!empty($new_entry_id))
			{ 
				$auto_value=1;
				foreach($new_entry_id as $value)
				{			
					$auto_value++;
				?>
				<input type="hidden" name="transationdata[<?php echo $auto_value; ?>]" value="<?php echo $value; ?>">
			<?php } 
			} ?>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="invoice_number"><?php _e('Invoice ID','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="invoice_number" class="form-control validate[required] text-input" type="text" readonly value="<?php if($edit){ echo $result->invoice_number;} else echo $obj_invoice->generate_invoce_number();?>" name="invoice_number" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8 margin_bottom_5px">				
					<?php if($edit){ $patient_id1=$result->patient_id; }elseif(isset($_REQUEST['patient'])){$patient_id1=$_REQUEST['patient'];}else{ $patient_id1="";}?>
					<select name="patient" class="form-control validate[required]" id="patient_id">
					<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
					<?php 					
						$patients = MJ_hmgt_patientid_list();					
						if(!empty($patients)){
						foreach($patients as $patient){
							echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['patient_id'].' - '.$patient['first_name'].' '.$patient['last_name'].'</option>';
						}	} ?>
					</select>
				</div>
				<div class="col-sm-1">					
					<a href="#" class="show-inovice btn btn-default"><i class="fa fa-eye"></i> <?php _e('Check Charges','hospital_mgt');?></a>					
				</div>			
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="invice_title"><?php _e('Invoice Title','hospital_mgt');?></label>
				<div class="col-sm-8">
					<input id="invice_title" class="form-control validate[custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $result->invoice_title;}elseif(isset($_POST['invice_title'])) echo $_POST['invice_title'];?>" name="invice_title">
				</div>
			</div>
			<div class=""></div>		
			<div class="form-group">
				<label class="col-sm-2 control-label" for="vat_percentage"><?php _e('Subtotal Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="invoice_amount" class="form-control validate[required] text-input" min="0" type="number" onKeyPress="if(this.value.length==12) return false;" step="0.01" value="<?php if($edit){ echo $result->invoice_amount + $total;}elseif(isset($_POST['invoice_amount'])) echo $_POST['invoice_amount']; elseif(isset($total)) echo $total;   ?>" name="invoice_amount" readonly>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-2 control-label" for="adjustment_amount"><?php _e('Adjustment Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
				<div class="col-sm-8">
					<input id="adjustment_amount" class="form-control text-input" min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01" value="<?php if($edit){ echo $result->adjustment_amount;}elseif(isset($_POST['adjustment_amount'])) echo $_POST['adjustment_amount'];?>" name="adjustment_amount">
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="invoice_date" class="form-control" type="text"   value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->invoice_create_date)) ;}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date(MJ_hmgt_date_formate());}?>" name="invoice_date">
				</div>
			</div>
			
			<div class="form-group margin_bottom_5px">
				<label class="col-sm-2 control-label" for="comments"><?php _e('Comments','hospital_mgt');?></label>
				<div class="col-sm-8">
					<textarea id="comments" class="form-control validate[custom[address_description_validation]]" maxlength="150" name="comments"><?php if($edit){echo $result->comments; }elseif(isset($_POST['comments'])) echo $_POST['comments']; ?></textarea>
				</div>
			</div>
		
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save Invoice','hospital_mgt'); }else{ _e('Create Invoice Entry','hospital_mgt');}?>" name="save_invoice" class="btn btn-success"/>
			</div>			
		</form>
    </div><!-- PANEL BODY DIV END-->
    <script>  
   	// CREATING BLANK INVOICE ENTRY
   	var blank_invoice_entry ='';
   	$(document).ready(function() { 
   		blank_invoice_entry = $('#invoice_entry').html();   		
   	}); 
   	function add_entry()
	{
   		$("#invoice_entry").append(blank_invoice_entry);   	
   	}   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n)
	{
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}
   </script> 
<?php 	 
}
?>