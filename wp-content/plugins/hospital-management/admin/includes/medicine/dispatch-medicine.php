<?php 
MJ_hmgt_browser_javascript_check();
//Add Medicine
$edit = 0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$medcategory_id = $_REQUEST['dispatch_id'];	
	$result = $obj_medicine->get_single_dispatch_medicine($medcategory_id);
}	
?>
<script type="text/javascript">
jQuery(document).ready(function($)
{
	$('#dispatch_medicine_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#expiry_date').datepicker({
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
                    
     }); 
	$("#patient_id").select2();			
} );
</script>
<div class="panel-body"><!--PANEL BODY DIV START-->
	<form name="dispatch_medicine_form" action="" method="post" class="form-horizontal" id="dispatch_medicine_form">
			 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="dispatch_id" value="<?php if(isset($_REQUEST['dispatch_id'])) echo $_REQUEST['dispatch_id'];?>"/>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">				
					<?php if($edit){ $patient_id1=$result->patient; }elseif(isset($_POST['patient'])){$patient_id1=$_POST['patient'];}else{ $patient_id1="";}?>
					<select name="patient" class="form-control" id="patient_id" <?php if($edit) print 'disabled="disabled"'; ?> >
					<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
					<?php 					
						$patients = MJ_hmgt_patientid_list();
						if(!empty($patients)){
						foreach($patients as $patient){
							echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['patient_id'].' - '.$patient['first_name'].' '.$patient['last_name'].'</option>';
						} } ?>
					</select>
				<?php if($edit) print '<input type="hidden" name="patient" value="'.$patient_id1=$result->patient.'">'; ?>
				</div>
					
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient"><?php _e('Prescription','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">					
					<select name="prescription_id" class="form-control" id="prescription"  <?php if($edit) print 'disabled="disabled"'; ?>  >
						<option><?php _e('Select Prescription', 'hospital_mgt');?></option>	
						<?php 
							if($edit){
								$obj_prescription = new MJ_hmgt_prescription();
								$prescriptiondata = $obj_prescription->get_all_prescription();
								//print_r($patient);
								if(!empty($prescriptiondata)){
								foreach($prescriptiondata as $key=>$value){
									echo '<option value="'.$value->priscription_id.'" '.selected($result->prescription_id,$value->priscription_id).'>'.MJ_hmgt_get_display_name($value->patient_id) .' - '.$value->pris_create_date.'</option>';
								} }
							}
						?>
					<?php if($edit) print '<input type="hidden" name="prescription_id" value="'.$result->prescription_id.'">'; ?>
					</select>
				</div>				
			</div>
			
			<div id="madicinedata"></div>
				<?php 
				if($edit)
				{ 
					$obj_madicine = new MJ_hmgt_medicine();
					$medication = json_decode($result->madicine);
					$i=1;
					?>
					<div class="form-group"><div class="col-sm-2"></div>
						 <div class="col-sm-2"><?php _e('Medicine','hospital_mgt');?></div>
						 <div class="col-sm-1"><?php _e('Quantity','hospital_mgt');?></div>
						 <div class="col-sm-1" style="medicine_padding_right_0"><?php _e('Price','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</div>
						  <div class="col-sm-2"><?php _e('Discount','hospital_mgt');?></div>
						  <div class="col-sm-1" style="padding_left_0"><?php _e('Discount Amount','hospital_mgt');?></div>
						 <div class="col-sm-1" style="padding_left_0"><?php _e('Tax','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</div>
						 <div class="col-sm-2"></div>
					 </div>
					<?php
					foreach($medication as $key=>$val)
					{							
						$singgle_madicine = $obj_madicine->get_single_medicine($val->madicine_id);
						
					?>
					<div id="invoice_entry">
						<div class="form-group">									
							 <div class="col-sm-2 margin_bottom_5px"></div>
							<input type="hidden"  name="madicine_id[]" value="<?php print $singgle_madicine->medicine_id ?>">
							<input type="hidden"  class="madicine_quantity_<?php print $i;?>" name="madicine_quantity" value="<?php print $singgle_madicine->med_quantity+$val->qty ?>">
							<input type="hidden"  class="" name="old_quantity[]" value="<?php print $val->qty ?>">
							<div class="col-sm-2 margin_bottom_5px">
								<input type="text" name="madicine_title[]" class="form-control" value="<?php print $singgle_madicine->medicine_name; ?> " readonly>
							</div>
							<div class="col-sm-1 margin_bottom_5px">
								<input id="qty_<?php print $i;?>" class="days form-control validate[required] medicineqty_<?php print $singgle_madicine->medicine_id ?>" dataid="<?php print $singgle_madicine->medicine_id ?>" counter="<?php print $i;?>" class="form-control" type="number" min="0" value="<?php print $val->qty ?>" name="qty[]">
							</div>
							<div class="col-sm-1 margin_bottom_5px" style="medicine_padding_right_0">
								<input id="price_<?php print $i;?>"  class="med_price form-control" type="text" value="<?php  print $val->price; ?>" name="price[]" readonly>
							</div>	
							<div class="col-sm-1 margin_bottom_5px" style="medicine_padding_right_0">
								<input id="discount_value_<?php print $i;?>" dataid="<?php print $singgle_madicine->medicine_id ?>" onKeyPress="if(this.value.length==10) return false;" step="0.01" class="med_discount_value form-control" type="number" value="<?php print $val->discount_value ?>" name="discount_value[]" counter="<?php print $i;?>">
							</div>	
							<div class="col-sm-1 margin_bottom_5px">
								<select class="form-control" id="med_discount_in_<?php print $i;?>" name="med_discountin[]" disabled>
									<option value="flat" <?php selected($val->med_discount_in,'flat'); ?>><?php _e('Flat','hospital_mgt');?></option>
									<option value="percentage" <?php selected($val->med_discount_in,'percentage'); ?>>%</option>
								</select>
								<input type="hidden" name="med_discount_in[]" value="<?php echo $val->med_discount_in; ?>">
							</div>
							<div class="col-sm-1 margin_bottom_5px" style="padding_left_0">
								<input id="discount_<?php print $i;?>"  class="med_discount form-control" type="text" value="<?php  print $val->discount_amount; ?>" name="discount_amount[]" readonly>
							</div>	
							<div class="col-sm-1" style="padding_left_0">
								<input id="tax_<?php print $i;?>"  class="tax_amount form-control" type="text" value="<?php  print $val->tax_amount; ?>" name="tax_amount[]" readonly>
							</div>		
							 <div class="col-sm-2"></div>
						</div>
					</div>
					<?php  $i++;
					}	
				}
			?>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="med_price"><?php _e('Total Price Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="dispatch_medicine_price" class="form-control validate[required] text-input" type="text" 
					value="<?php if($edit) print $result->med_price; ?>" name="med_price" readonly>
				</div>
			</div>			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="discount"><?php _e('Total Discount Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
				<div class="col-sm-8">
					<input id="discount" class="form-control discount text-input" min="0" type="number" onKeyPress="if(this.value.length==8) return false;" step="0.01" value="<?php  if($edit) print $result->discount; ?>" name="discount" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="med_price"><?php _e('Total Tax Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
				<div class="col-sm-8">
					<input id="med_tax" class="form-control text-input" type="text" 
					value="<?php if($edit) print $result->total_tax_amount; ?>" name="total_tax_amount" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="sub_total"><?php _e('Sub Total','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
				<div class="col-sm-8">
					<input id="sub_total" class="form-control  text-input" type="text" value="<?php  if($edit) print $result->sub_total; ?>" name="sub_total" readonly>
				</div>
			</div>
			<div class="form-group margin_bottom_5px">
				<label class="col-sm-2 control-label" for="description"><?php _e('Description','hospital_mgt');?></label>
				<div class="col-sm-8">
					<textarea name="description" id="description" maxlength="150" class="form-control validate[custom[address_description_validation]]"><?php if($edit){ echo trim($result->description);}elseif(isset($_POST['description'])) echo $_POST['description'];?></textarea>					
				</div>
			</div>			
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_dispatch_medicine" class="btn btn-success"/>
			</div>
	</form>
</div><!--PANEL BODY DIV END-->

		
		