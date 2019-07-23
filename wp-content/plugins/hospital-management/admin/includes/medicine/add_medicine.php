<?php 
MJ_hmgt_browser_javascript_check();
//Add Medicine
$edit = 0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$medcategory_id = $_REQUEST['medicine_id'];
	$result = $obj_medicine->get_single_medicine($medcategory_id);
}	
?>
<script type="text/javascript">
jQuery(document).ready(function($) 
{
	$('#medicine_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	
		$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
		  $('#manufactured_date').datepicker({
			endDate: '+0d',
			autoclose: true
	   }); 
		var date = new Date();
        date.setDate(date.getDate()-0);
	  $.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
        $('#expiry_date').datepicker({
	    startDate: date,
        autoclose: true
   }); 
   $('.tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
} );
</script>
   <!-- PANEL BODY DIV START -->
<div class="panel-body">
    <form name="medicine_form" action="" method="post" class="form-horizontal" id="medicine_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" class="medicine_id" name="medicine_id" value="<?php if(isset($_REQUEST['medicine_id'])) echo $_REQUEST['medicine_id'];?>"  />
			<div class="form-group">
			<label class="col-sm-2 control-label" for="medicine_category"><?php _e('Category Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8 margin_bottom_5px">			
				<select class="form-control validate[required]" name="medicine_category" id="medicine">
				<option value=""><?php _e('Select Category','hospital_mgt');?></option>
				<?php 
				$medicine_category = $obj_medicine->get_all_category();
				if(isset($_REQUEST['medicine_category']))
					$category =$_REQUEST['medicine_category'];  
				elseif($edit)
					$category =$result->med_cat_id;
				else 
					$category = "";
				
				if(!empty($medicine_category))
				{
					foreach ($medicine_category as $retrive_data)
					{
						echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
					}
				}
				?>
				</select>
			</div>
			<div class="col-sm-2"><button id="addremove" model="medicine"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
		</div>
		<?php
		if($edit)
		{
		?>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="medicine_name"><?php _e('Medicine','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-3 margin_bottom_5px">
					<input id="medicine_name" class="form-control validate[required,custom[popup_category_validation]] text-input edit_medicine_name" maxlength="50" type="text" placeholder="<?php _e('Medicine Name','hospital_mgt');?>"
					value="<?php if($edit){ echo $result->medicine_name;}elseif(isset($_POST['medicine_name'])) echo $_POST['medicine_name'];?>" name="medicine_name">
				</div>
				<div class="col-sm-6 margin_bottom_5px">				
					<textarea rows="1"  name="description"  class="form-control validate[custom[address_description_validation]]" id="" maxlength="150" placeholder="<?php _e('Description','hospital_mgt');?>"><?php if($edit){ echo trim($result->medicine_description);}elseif(isset($_POST['description'])) echo $_POST['description'];?></textarea>
				</div>				
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-3 margin_bottom_5px">
					<input class="form-control validate[custom[popup_category_validation]]" type="text" maxlength="20" placeholder="<?php _e('Batch Number','hospital_mgt');?>" value="<?php if($edit){ echo $result->batch_number;}elseif(isset($_POST['batch_number'])) echo $_POST['batch_number'];?>" name="batch_number">
				</div>	
				<div class="col-sm-2 margin_bottom_5px">
					<input  class="form-control validate[required] text-input" min="0" type="number" onKeyPress="if(this.value.length==6) return false;"  placeholder="<?php _e('Quantity','hospital_mgt');?>"
					value="<?php if($edit){ echo $result->med_quantity;}elseif(isset($_POST['med_quantity'])) echo $_POST['med_quantity'];?>" name="med_quantity">
				</div>		
				<div class="col-sm-2 margin_bottom_5px">
					<input id="med_price" class="form-control validate[required] text-input" min="1" type="number" onKeyPress="if(this.value.length==8) return false;" step="0.01"  placeholder="<?php _e('Price','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)"
					value="<?php if($edit){ echo $result->medicine_price;}elseif(isset($_POST['med_price'])) echo $_POST['med_price'];?>" name="med_price">
				</div>
				<div class="col-sm-2">
					<input id="" class="form-control validate[custom[popup_category_validation]] text-input edit_med_uniqueid" maxlength="10" type="text" placeholder="<?php _e('Medicine ID','hospital_mgt');?>"	value="<?php if($edit){ echo $result->med_uniqueid;}elseif(isset($_POST['med_uniqueid'])) echo $_POST['med_uniqueid'];?>" name="med_uniqueid">
				</div>					
			</div>	
			<div class="form-group">				
				<div class="col-sm-offset-2 col-sm-3 margin_bottom_5px">				
					<textarea rows="1"  name="note"  class="form-control validate[custom[address_description_validation]]"  maxlength="150" placeholder="<?php _e('Note','hospital_mgt');?>"><?php if($edit){ echo trim($result->note);}elseif(isset($_POST['note'])) echo $_POST['note'];?></textarea>
				</div>
				<div class="col-sm-2 margin_bottom_5px">
					<input id="med_discount" class="form-control text-input" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01"  placeholder="<?php _e('Discount','hospital_mgt');?>" value="<?php if($edit){ echo $result->med_discount;}elseif(isset($_POST['med_discount'])) echo $_POST['med_discount'];?>" name="med_discount">
				</div>	
				<div class="col-sm-2 margin_bottom_5px">
					<select class="form-control" name="med_discount_in">
						<option value="flat" <?php selected($result->med_discount_in,'flat'); ?>><?php _e('Flat','hospital_mgt');?></option>
						<option value="percentage" <?php selected($result->med_discount_in,'percentage'); ?>><?php _e('Percentage','hospital_mgt');?></option>
					</select>
				</div>	
				<div class="col-sm-2 margin_bottom_5px">					
					<select  class="form-control tax_charge"  name="med_tax[]" multiple="multiple">					
					<?php
					$tax_id=explode(',',$result->med_tax);
					
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
				<div class="col-sm-offset-2 col-sm-3 margin_bottom_5px">
					<input id="mfg_cmp_name" class="form-control validate[custom[popup_category_validation]]" type="text" maxlength="50" placeholder="<?php _e('Manufacturer Company Name','hospital_mgt');?>"
					value="<?php if($edit){ echo $result->medicine_menufacture;}elseif(isset($_POST['mfg_cmp_name'])) echo $_POST['mfg_cmp_name'];?>" name="mfg_cmp_name">
				</div>
				<div class="col-sm-2 margin_bottom_5px">
					<input id="manufactured_date" class="form-control validate[required]" type="text"  name="manufactured_date" placeholder="<?php _e('Manufactured Date','hospital_mgt');?>" value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->manufactured_date));}elseif(isset($_POST['manufactured_date'])) echo $_POST['manufactured_date'];?>" readonly>
				</div>	
				<div class="col-sm-2 margin_bottom_5px">
					<input id="expiry_date" class="form-control validate[required]" type="text"  name="expiry_date" 
					placeholder="<?php _e('Expiry Date','hospital_mgt');?>"	value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->medicine_expiry_date));}elseif(isset($_POST['expiry_date'])) echo $_POST['expiry_date'];?>" readonly>
				</div>					
			</div>			
		<?php	
		}
		else
		{	
		?>			
			<div class="main_medicine_div">				
				<div class="medicine_div">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="medicine_name"><?php _e('Medicine','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3 margin_bottom_5px">
							<input id="medicine_name" class="form-control validate[required,custom[popup_category_validation]] text-input medicine_name" maxlength="50" type="text" placeholder="<?php _e('Medicine Name','hospital_mgt');?>" value="" name="medicine_name[]">
						</div>
						<div class="col-sm-6 margin_bottom_5px">				
							<textarea rows="1"  name="description[]"  class="form-control validate[custom[address_description_validation]]" id="description" maxlength="150" placeholder="<?php _e('Description','hospital_mgt');?>"></textarea>
						</div>						
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-3 margin_bottom_5px">
							<input class="form-control validate[custom[popup_category_validation]]" type="text" maxlength="20" placeholder="<?php _e('Batch Number','hospital_mgt');?>" value="" name="batch_number[]">
						</div>						
						<div class="col-sm-2 margin_bottom_5px">
							<input  class="form-control validate[required] text-input" min="0" type="number" onKeyPress="if(this.value.length==6) return false;"  placeholder="<?php _e('Quantity','hospital_mgt');?>" value="" name="med_quantity[]">
						</div>
						<div class="col-sm-2 margin_bottom_5px">
							<input id="med_price" class="form-control validate[required] text-input" min="1" type="number" onKeyPress="if(this.value.length==8) return false;" step="0.01"  placeholder="<?php _e('Price','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)" value="" name="med_price[]">
						</div>	
						<div class="col-sm-2 margin_bottom_5px">
							<input id="" class="form-control validate[custom[popup_category_validation]] text-input med_uniqueid" maxlength="10" type="text" placeholder="<?php _e('Medicine ID','hospital_mgt');?>"	value="" name="med_uniqueid[]">
						</div>													
					</div>
					<div class="form-group">						
						<div class="col-sm-offset-2 col-sm-3 margin_bottom_5px">				
							<textarea rows="1"  name="note[]"  class="form-control validate[custom[address_description_validation]]"  maxlength="150" placeholder="<?php _e('Note','hospital_mgt');?>"></textarea>
						</div>
						<div class="col-sm-2 margin_bottom_5px">
							<input id="med_discount" class="form-control text-input" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01"  placeholder="<?php _e('Discount','hospital_mgt');?> " value="" name="med_discount[]">
						</div>		
						<div class="col-sm-2 margin_bottom_5px">
							<select class="form-control" name="med_discount_in[]">
								<option value="flat"><?php _e('Flat','hospital_mgt');?></option>
								<option value="percentage"><?php _e('Percentage','hospital_mgt');?></option>
							</select>
						</div>						
						<div class="col-sm-2 margin_bottom_5px">						
							<select  class="form-control tax_charge"  name="med_tax[0][]" multiple="multiple">					
								<?php	
								$obj_invoice= new MJ_hmgt_invoice();
								$hmgt_taxs=$obj_invoice->get_all_tax_data();	
								
								if(!empty($hmgt_taxs))
								{
									foreach($hmgt_taxs as $entry)
									{										
										?>
										<option value="<?php echo $entry->tax_id; ?>"><?php echo $entry->tax_title;?>-<?php echo $entry->tax_value;?></option>
									<?php 
									}
								}
								?>
							</select>		
						</div>		
					</div> 
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-3 margin_bottom_5px">
							<input id="mfg_cmp_name" class="form-control validate[custom[popup_category_validation]]" type="text" maxlength="50" placeholder="<?php _e('Manufacturer Company Name','hospital_mgt');?>"
							value="" name="mfg_cmp_name[]">
						</div>
						<div class="col-sm-2 margin_bottom_5px">
							<input id="manufactured_date" class="form-control validate[required]" type="text"  name="manufactured_date[]" placeholder="<?php _e('Manufactured Date','hospital_mgt');?>"
							value="" readonly>
						</div>	
						<div class="col-sm-2 margin_bottom_5px">
							<input id="expiry_date" class="form-control validate[required]" type="text"  name="expiry_date[]" placeholder="<?php _e('Expiry Date','hospital_mgt');?>" value="" readonly>
						</div>	
					</div>
					
				</div>
			</div>
			<div class="form-group">
					<label class="col-sm-2 control-label" for="expense_entry"></label>
					<div class="col-sm-3 margin_bottom_5px">				
						<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add More Medicine','hospital_mgt'); ?>
						</button>
					</div>				
			</div>
		<?php
		}
		?>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_medicine" class="btn btn-success"/>
        </div>
    </form>
</div>
<script>
var key=0;
function add_entry()
{		
	key++;
	$(".main_medicine_div").append('<div class="medicine_div"><div class="form-group"><label class="col-sm-2 		control-label" for="medicine_name"><?php _e('Medicine','hospital_mgt');?><span class="require-field">*</span></label><div class="col-sm-3 margin_bottom_5px"><input id="medicine_name" class="form-control validate[required,custom[popup_category_validation]] text-input  medicine_name" maxlength="50" type="text" placeholder="<?php _e('Medicine Name','hospital_mgt');?>" value="" name="medicine_name[]"></div><div class="col-sm-6"><textarea rows="1"  name="description[]"  class="form-control validate[custom[address_description_validation]]" id="description" maxlength="150" placeholder="<?php _e('Description','hospital_mgt');?>"></textarea></div></div><div class="form-group"><div class="col-sm-offset-2 col-sm-3 margin_bottom_5px"><input class="form-control validate[custom[popup_category_validation]]" type="text" maxlength="20" placeholder="<?php _e('Batch Number','hospital_mgt');?>" value="" name="batch_number[]"></div><div class="col-sm-2 margin_bottom_5px"><input  class="form-control validate[required] text-input" min="0" type="number" onKeyPress="if(this.value.length==6) return false;"  placeholder="<?php _e('Quantity','hospital_mgt');?>" value="" name="med_quantity[]"></div><div class="col-sm-2 margin_bottom_5px"><input id="med_price" class="form-control validate[required] text-input" min="1" step="0.01" type="number" onKeyPress="if(this.value.length==8) return false;"  placeholder="<?php _e('Price','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)" value="" name="med_price[]"></div><div class="col-sm-2 margin_bottom_5px"><input id="" class="form-control validate[] text-input med_uniqueid validate[custom[popup_category_validation]]" maxlength="10" type="text" placeholder="<?php _e('Medicine ID','hospital_mgt');?>"value="" name="med_uniqueid[]"></div></div><div class="form-group"><div class="col-sm-offset-2 col-sm-3 margin_bottom_5px"><textarea rows="1"  name="note[]"  class="form-control validate[custom[address_description_validation]]"  maxlength="150" placeholder="<?php _e('Note','hospital_mgt');?>"></textarea></div><div class="col-sm-2 margin_bottom_5px"><input id="med_discount" class="form-control text-input" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01"  placeholder="<?php _e('Discount','hospital_mgt');?>" value="" name="med_discount[]"></div><div class="col-sm-2 margin_bottom_5px"><select class="form-control" name="med_discount_in[]"><option value="flat"><?php _e('Flat','hospital_mgt');?></option><option value="percentage"><?php _e('Percentage','hospital_mgt');?></option></select></div>	<div class="col-sm-2 margin_bottom_5px"><select  class="form-control tax_charge"  name="med_tax['+key+'][]" multiple="multiple"><?php $obj_invoice= new MJ_hmgt_invoice(); $hmgt_taxs=$obj_invoice->get_all_tax_data(); if(!empty($hmgt_taxs)){	foreach($hmgt_taxs as $entry){ ?> <option value="<?php echo $entry->tax_id; ?>"><?php echo $entry->tax_title;?>-<?php echo $entry->tax_value;?></option> <?php } }	?></select></div></div><div class="form-group"><div class="col-sm-offset-2 col-sm-3 margin_bottom_5px"><input id="mfg_cmp_name" class="form-control validate[custom[popup_category_validation]]" type="text" maxlength="50" placeholder="<?php _e('Manufacturer Company Name','hospital_mgt');?>" value="" name="mfg_cmp_name[]"></div><div class="col-sm-2 margin_bottom_5px"><input id="" class="form-control validate[required] medicine_manufactured_date" type="text"  name="manufactured_date[]" placeholder="<?php _e('Manufactured Date','hospital_mgt');?>" value="" readonly></div><div class="col-sm-2 margin_bottom_5px"><input id="" class="form-control validate[required] medicine_expiry_date" type="text"  name="expiry_date[]" placeholder="<?php _e('Expiry Date','hospital_mgt');?>" value="" readonly></div><div class="col-sm-offset-2 col-sm-1"><button type="button" class="btn btn-default delete_medicine_div"><i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i></button></div></div></div>');
}
</script> 