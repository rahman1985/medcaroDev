<?php 
MJ_hmgt_browser_javascript_check();
//Add bed
$edit = 0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit = 1;
	$bed_type_id = $_REQUEST['bed_id'];
	$result = $obj_bed->get_single_bed($bed_type_id);
}	
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#bed_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
	} );
</script>
<div class="panel-body"><!-- PANEL BODY DIV START-->
	<form name="bed_form" action="" method="post" class="form-horizontal" id="bed_form">
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="bed_id" value="<?php if(isset($_REQUEST['bed_id'])) echo $_REQUEST['bed_id'];?>"  />
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bed_type_id"><?php _e('Select Bed Category','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8 margin_bottom_5px">
			<?php if(isset($_REQUEST['bed_type_id']))
					$bed_type1 = $_REQUEST['bed_type_id'];
				elseif($edit)
					$bed_type1 = $result->bed_type_id;
				else 
					$bed_type1 = "";
				?>
				<select name="bed_type_id" class="form-control validate[required]" id="bedtype">
				<option value = ""><?php _e('Select Bed Category','hospital_mgt');?></option>
				<?php 
				
				$bedtype_data=$obj_bed->get_all_bedtype();
				if(!empty($bedtype_data))
				{
					foreach ($bedtype_data as $retrieved_data)
					{
						echo '<option value="'.$retrieved_data->ID.'" '.selected($bed_type1,$retrieved_data->ID).'>'.$retrieved_data->post_title.'</option>';
					}
				}
				?>
				</select>
			</div>
			<div class="col-sm-2"><button id="addremove" model="bedtype"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bed_number"><?php _e('Bed Number','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="bed_number" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="10" type="text"  value="<?php if($edit){ echo $result->bed_number;}elseif(isset($_POST['bed_number'])) echo $_POST['bed_number'];?>" name="bed_number">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="bed_charges"><?php _e('Charges','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="bed_charges" class="form-control validate[required]" min="0" type="number" onKeyPress="if(this.value.length==8) return false;" step="0.01"
				value="<?php if($edit){ echo $result->bed_charges;}elseif(isset($_POST['bed_charges'])) echo $_POST['bed_charges'];?>" name="bed_charges">
			</div>
			<div class="col-sm-2 padding_left_0" style="float:left;padding-top: 8px;font-size: 13px;">
			<?php _e('/ Per Day','hospital_mgt');?>
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
			<label class="col-sm-2 control-label" for="bed_location"><?php _e('Location','hospital_mgt');?></label>
			<div class="col-sm-8">
				<textarea id="bed_location" class="form-control validate[custom[address_description_validation]]"  maxlength="150" name="bed_location"><?php if($edit){ echo $result->bed_location;}elseif(isset($_POST['bed_location'])) echo $_POST['bed_location'];?></textarea>
			</div>
		</div>
		<div class="form-group margin_bottom_5px">
			<label class="col-sm-2 control-label" for="bed_description"><?php _e('Description','hospital_mgt');?></label>
			<div class="col-sm-8">
				<textarea id="bed_description" class="form-control validate[custom[address_description_validation]]"  maxlength="150" name="bed_description"><?php if($edit){ echo $result->bed_description;}elseif(isset($_POST['bed_description'])) echo $_POST['bed_description'];?></textarea>
				
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
			<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_bed" class="btn btn-success"/>
		</div>
	</form>
</div><!-- PANEL BODY DIV END-->
<?php 
//}
?>