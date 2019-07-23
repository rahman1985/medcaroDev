<?php
MJ_hmgt_browser_javascript_check(); 
//Add bed
$obj_instrument = new MJ_hmgt_Instrumentmanage();
$edit = 0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$instumrnt_id = $_REQUEST['instumrnt_id'];
	$result = $obj_instrument->get_single_instrument($instumrnt_id);
}
?>
<script>
jQuery(document).ready(function($)
{
	$('#instrument_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
});
</script>
<div class="panel-body"><!-- PANEL BODY DIV START -->		
	<form name="bed_form" action="" method="post" class="form-horizontal" id="instrument_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="instrument_id" value="<?php if(isset($_REQUEST['instumrnt_id'])) echo $_REQUEST['instumrnt_id'];?>"  />
			<div class="col-sm-6" style="min-height: 400px;" >
				<fieldset>
					<legend><?php _e('Instrument Info:','hospital_mgt'); ?></legend>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="instrument_code"><?php _e('Instrument Code','hospital_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-9">
								<input id="instrument_code" class="form-control validate[required] text-input" min="0" type="number" onKeyPress="if(this.value.length==8) return false;" 
								value="<?php if($edit){ echo $result->instrument_code;}elseif(isset($_POST['instrument_code'])) echo $_POST['instrument_code'];?>" name="instrument_code">
							</div>
						</div>
			
						<div class="form-group">
							<label class="col-sm-3 control-label" for="instrument_name"><?php _e('Instrument Name','hospital_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-9">
								<input id="instrument_name" class="form-control  validate[required,custom[popup_category_validation]]" type="text"  maxlength="50"
								value="<?php if($edit){ echo $result->instrument_name;}elseif(isset($_POST['instrument_name'])) echo $_POST['instrument_name'];?>" name="instrument_name">
							</div>
						</div>	
			
						<div class="form-group">
							<label class="col-sm-3 control-label" for="charge_type"><?php _e('Charges Type','hospital_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-9">
							<?php $charge_type = "Daily"; if($edit){ $charge_type=$result->charge_type; }elseif(isset($_POST['charge_type'])) {$charge_type=$_POST['charge_type'];}?>
								<label class="radio-inline">
								 <input type="radio" value="Daily" class="tog validate[required]" name="charge_type"  <?php  checked( 'Daily', $charge_type);  ?>/><?php _e('Daily','hospital_mgt');?>
								</label>
								<label class="radio-inline">
								  <input type="radio" value="Hourly" class="tog validate[required]" name="charge_type"  <?php  checked( 'Hourly', $charge_type);  ?>/><?php _e('Hourly','hospital_mgt');?> 
								</label>
							</div>
						</div>
			
					<div class="form-group">
						<label class="col-sm-3 control-label" for="instrument_charge"><?php _e('Instrument charge','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
						<div class="col-sm-9">
							<input id="instrument_charge" class="form-control validate[required] " min="0" type="number" onKeyPress="if(this.value.length==8) return false;"  step="0.01"
							value="<?php if($edit){ echo $result->instrument_charge;}elseif(isset($_POST['instrument_charge'])) echo $_POST['instrument_charge'];?>" name="instrument_charge">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for=""><?php _e('Tax','hospital_mgt');?></label>
						<div class="col-sm-9">
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
						<label class="col-sm-3 control-label" for="instrument_description"><?php _e('Description','hospital_mgt');?></label>
						<div class="col-sm-9">
							<textarea id="instrument_description" class="form-control validate[custom[address_description_validation]]" maxlength="150"  name="instrument_description"><?php if($edit){ echo $result->instrument_description;}elseif(isset($_POST['instrument_description'])) echo $_POST['instrument_description'];?></textarea>
							
						</div>
					</div>		
		        </fieldset>
	        </div>
			<div class="col-sm-6" style="min-height: 400px;" >
			    <fieldset>
							<legend><?php _e('Firm Info:','hospital_mgt'); ?></legend>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="code"><?php _e('Code','hospital_mgt');?></label>
								<div class="col-sm-10">
									<input id="code" class="form-control text-input" min="0" type="number" onKeyPress="if(this.value.length==8) return false;" 
									value="<?php if($edit){ echo $result->code;}elseif(isset($_POST['code'])) echo $_POST['code'];?>" name="code">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="name"><?php _e('Name','hospital_mgt');?></label>
								<div class="col-sm-10">
									<input id="name" class="form-control text-input validate[custom[popup_category_validation]]" type="text" maxlength="30"
									value="<?php if($edit){ echo $result->name;}elseif(isset($_POST['name'])) echo $_POST['name'];?>" name="name">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="address"><?php _e('Address','hospital_mgt');?></label>
								<div class="col-sm-10">
									<textarea id="address" class="form-control validate[custom[address_description_validation]]" maxlength="150" name="address" cols="29"><?php if($edit) print $result->address ?></textarea>
								</div>
							</div>	
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="contact"><?php _e('Contact','hospital_mgt');?></label>
								<div class="col-sm-10">
									<input id="contact" class="form-control validate[custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="<?php if($edit){ echo $result->contact;}elseif(isset($_POST['contact'])) echo $_POST['contact'];?>" name="contact">									
								</div>
							</div>			
				</fieldset>
			</div>
	        <div class="col-sm-6" style="min-height: 350px;" >
	            <fieldset>
					<legend><?php _e('Asset Info:','hospital_mgt'); ?></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="description"><?php _e('Description','hospital_mgt');?></label>
						<div class="col-sm-10">
							<textarea name="description"  maxlength="150" class="form-control validate[custom[address_description_validation]]"  ><?php if($edit) print $result->description ?></textarea>
						</div>
					</div>	
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="quantity"><?php _e('Quantity','hospital_mgt');?></label>
						<div class="col-sm-10">
							<input id="quantity" class="form-control  text-input" min="0"  type="number" onKeyPress="if(this.value.length==4) return false;" 
							value="<?php if($edit){ echo $result->quantity;}elseif(isset($_POST['quantity'])) echo $_POST['quantity'];?>" name="quantity">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="price"><?php _e('Price','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
						<div class="col-sm-10">
							<input id="price" class="form-control  text-input" min="0" type="number" onKeyPress="if(this.value.length==8) return false;" step="0.01" value="<?php if($edit){ echo $result->price;}elseif(isset($_POST['price'])) echo $_POST['price'];?>" name="price">
						</div>
					</div>
					
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="class"><?php _e('Class','hospital_mgt');?></label>
						<div class="col-sm-10">
							<input id="class" class="form-control  text-input validate[custom[popup_category_validation]]" type="text" maxlength="30"
							value="<?php if($edit){ echo $result->class;}elseif(isset($_POST['class'])) echo $_POST['class'];?>" name="class">
						</div>
					</div>			
				</fieldset>
	        </div>
	        <div class="col-sm-6" style="min-height: 350px;" >
	            <fieldset>
					<legend><?php _e('Invoice Info:','hospital_mgt');?></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="serial"><?php _e('Serial','hospital_mgt');?></label>
						<div class="col-sm-10">
							<input id="serial" class="form-control  text-input" min="0" type="number" onKeyPress="if(this.value.length==6) return false;" value="<?php if($edit){ echo $result->serial;}elseif(isset($_POST['serial'])) echo $_POST['serial'];?>" name="serial">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="acquire"><?php _e('Acquire','hospital_mgt');?></label>
						<div class="col-sm-10">
							<input id="acquire" class="form-control  text-input" min="0" type="number" onKeyPress="if(this.value.length==6) return false;" value="<?php if($edit){ echo $result->acquire;}elseif(isset($_POST['acquire'])) echo $_POST['acquire'];?>" name="acquire">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="asset_id"><?php _e('Asset ID','hospital_mgt');?></label>
						<div class="col-sm-10">
							<input id="asset_id" class="form-control  text-input" min="0" type="number" onKeyPress="if(this.value.length==6) return false;" value="<?php if($edit){ echo $result->asset_id;}elseif(isset($_POST['asset_id'])) echo $_POST['asset_id'];?>" name="asset_id">
						</div>
					</div>				
	            </fieldset>
	        </div>
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save ','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_instrument" class="btn btn-success"/>
			</div>
	</form>
</div><!-- PANEL BODY DIV END -->		