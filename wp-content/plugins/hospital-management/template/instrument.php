<?php 
MJ_hmgt_browser_javascript_check();
$obj_instrument = new MJ_hmgt_Instrumentmanage();
$user_role=MJ_hmgt_get_current_user_role();
if($user_role=='patient')
{	
	$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'assigninstrumentlist';
}
else
{
	$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'instrumentlist';
}	
//access right function
$user_access=MJ_hmgt_get_userrole_wise_access_right_array();

if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJ_hmgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJ_hmgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJ_hmgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJ_hmgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
$obj_hospital = new Hospital_Management(get_current_user_id());
$user_role = $obj_hospital->role;
if(isset($_REQUEST['save_instrument']))
{
	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{

		$result = $obj_instrument->hmgt_add_instrument($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url() . '?dashboard=user&page=instrument&tab=instrumentlist&message=2');
			}
			else
			{
				wp_redirect ( home_url() . '?dashboard=user&page=instrument&tab=instrumentlist&message=1');
			}	
		}
	}
}
//SAVE Assigned Instrument DATA
if(isset($_REQUEST['assign_instrument']))
{ 
	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{	
		$result = $obj_instrument->hmgt_assign_instrument($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect (home_url().'?dashboard=user&page=instrument&tab=assigninstrumentlist&message=2');
			}
			else{
				wp_redirect (home_url().'?dashboard=user&page=instrument&tab=assigninstrumentlist&message=1');
			}	
		}
	}
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	if(isset($_REQUEST['instumrnt_id']))
	{
		$result = $obj_instrument->delete_instrument(MJ_hmgt_id_decrypt($_REQUEST['instumrnt_id']));
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=instrument&tab=instrumentlist&message=3');
		}
	}
	if(isset($_REQUEST['assign_instument_id']))
	{
		$result = $obj_instrument->delete_assigned_instrument(MJ_hmgt_id_decrypt($_REQUEST['assign_instument_id']));
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=instrument&tab=assigninstrumentlist&message=3');
		}
	}
}
if(isset($_REQUEST['message']))
{
	$message =$_REQUEST['message'];
	if($message == 1)
	{?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				_e('Record inserted successfully','hospital_mgt');
			?></p></div>
			<?php 
		
	}
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Record updated successfully",'hospital_mgt');
				?></p>
				</div>
			<?php 
		
	}
	elseif($message == 3) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Record deleted successfully','hospital_mgt');
	?></div></p><?php
			
	}
} 
 ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	jQuery('#instrument_list').DataTable({
	"responsive": true,
	"aoColumns":[
	    {"bSortable": true},
	    {"bSortable": true},
	    {"bSortable": true},
	    {"bSortable": true},       	                 
	    {"bSortable": true},       	                 	        	                 
	    {"bSortable": true},       	                 
	    {"bSortable": false}],
		language:<?php echo MJ_hmgt_datatable_multi_language();?>
	});
});
</script>

<div class="panel-body panel-white"><!--START PANEL BODY DIV-->
	 <ul class="nav nav-tabs panel_tabs" role="tablist">
		<?php
		$user_role=MJ_hmgt_get_current_user_role();
		if($user_role!='patient')
		{			
		?>	
		  <li class="<?php if($active_tab=='instrumentlist'){?>active<?php }?>">
				<a href="?dashboard=user&page=instrument&tab=instrumentlist" class="tab <?php echo $active_tab == 'instrumentlist' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php _e('Instrument List', 'hospital_mgt'); ?></a>
			  </a>
		  </li>
		<?php
		}
		?>	
		  <li class="<?php if($active_tab=='addinstrument'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] =='edit')
			{?>
				<a href="?dashboard=user&page=instrument&tab=addinstrument&&action=edit&instumrnt_id=<?php echo $_REQUEST['instumrnt_id'];?>" class="tab <?php echo $active_tab == 'addinstrument' ? 'active' : ''; ?>">
				<i class="fa fa"></i> <?php _e('Edit Instrument', 'hospital_mgt'); ?></a>
			 <?php 
			}
			else
			{
				if($user_access['add']=='1')
				{			
				?>				
					<a href="?dashboard=user&page=instrument&tab=addinstrument&&action=insert" class="tab <?php echo $active_tab == 'addinstrument' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php _e('Add Instrument', 'hospital_mgt'); ?></a>
				<?php
				}
			}
			?>	  
		</li>	
		   <li class="<?php if($active_tab=='assigninstrumentlist'){?>active<?php }?>">
				<a href="?dashboard=user&page=instrument&tab=assigninstrumentlist" class="tab <?php echo $active_tab == 'assigninstrumentlist' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php _e('Assigned Instrument List', 'hospital_mgt'); ?></a>
			  </a>
		  </li>
		  <li class="<?php if($active_tab=='assign_instrument'){?>active<?php }?>">
		  <?php
			if(isset($_REQUEST['action']) && $_REQUEST['action'] =='edit' && $_REQUEST['tab'] == 'assign_instrument')
			{
				?>
				<a href="?dashboard=user&page=instrument&tab=assign_instrument&&action=edit&assign_instument_id=<?php echo $_REQUEST['assign_instument_id'];?>" class="tab <?php echo $active_tab == 'assign_instrument' ? 'active' : ''; ?>">
				<i class="fa fa"></i> <?php _e('Edit Assigned Instrument', 'hospital_mgt'); ?></a>
			 <?php 
			}
			else
			{
				if($user_access['add']=='1')
				{			
				?>				
					<a href="?dashboard=user&page=instrument&tab=assign_instrument&&action=insert" class="tab <?php echo $active_tab == 'assign_instrument' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php _e('Assign Instrument', 'hospital_mgt'); ?></a>
				<?php
				}
			}
			?>	  
		</li>
				
				  
	</ul>
<?php if($active_tab=='instrumentlist'){ ?>
	<form name="wcwm_report" action="" method="post">
        <div class="panel-body"><!--START PANEL BODY DIV-->
        	<div class="table-responsive"><!--START TABLE RESPONSIVE DIV-->
				<table id="instrument_list" class="display" cellspacing="0" width="100%"><!--START Instrument LIST TABLE-->
					<thead>
						<tr>
							<th><?php _e( 'Instrument Code', 'hospital_mgt' ) ;?></th>
							 <th><?php _e( 'Name', 'hospital_mgt' ) ;?></th>
							 <th><?php _e( 'Charges', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
							 <th><?php _e( 'Tax', 'hospital_mgt' ) ;?></th>
							 <th><?php _e( 'Charges Type', 'hospital_mgt' ) ;?></th>
							  <th><?php _e( 'Description', 'hospital_mgt' ) ;?></th>			 
							  <th><?php _e( 'Action', 'hospital_mgt' ) ;?></th>			 
						</tr>
				    </thead>
					<tfoot>
						<tr>
							<th><?php _e( 'Instrument Code', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Name', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Charges', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
							<th><?php _e( 'Tax', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Charges Type', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Description', 'hospital_mgt' ) ;?></th>		
						     <th><?php _e( 'Action', 'hospital_mgt' ) ;?></th>				  
						</tr>
					</tfoot>
					<tbody>
					 <?php 
					
					$instrumentdata=$obj_instrument->get_all_instrument();
					
					if(!empty($instrumentdata))
					{
						foreach ($instrumentdata as $retrieved_data)
						{ 
						?>
						<tr>
							<td class="bed_number"><?php echo $retrieved_data->instrument_code;?></td>
							<td class="bed_type"><?php echo $retrieved_data->instrument_name;?></td>
							<td class="charge">	<?php echo $retrieved_data->instrument_charge;?></td>
							<td class="tax"><?php 
							if(!empty($retrieved_data->tax))
							{  
								echo MJ_hmgt_tax_name_array_by_tax_id_array($retrieved_data->tax);
							}
							else
							{
								echo '-'; 
							}
							?></td>
							<td class="descrition"><?php echo $retrieved_data->charge_type;?></td>
							<td class="descrition"><?php echo $retrieved_data->instrument_description ;?></td>
							 <td class="action">
							<?php
							if($user_access['edit']=='1')
							{
							?>
								<a href="?dashboard=user&page=instrument&tab=addinstrument&&action=edit&instumrnt_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->id);?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
							<?php
							}
							if($user_access['delete']=='1')
							{
							?>	
								<a href="?dashboard=user&page=instrument&tab=instrumentlist&action=delete&instumrnt_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->id);?>" class="btn btn-danger" 
								onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
								<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
							<?php
							}
							?>	
							</td>
						   
						</tr>
						<?php } 
					}?>
					</tbody>
				</table><!--END INSTRUMENT LIST TABLE-->
            </div><!--END TABLE RESPONSIVE DIV-->
        </div><!--END PANEL BODY DIV-->
    </form>
<?php }
	if($active_tab=='addinstrument')
	{
		$obj_instrument = new MJ_hmgt_Instrumentmanage();
		$edit = 0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
			$edit = 1;
			$instumrnt_id = MJ_hmgt_id_decrypt($_REQUEST['instumrnt_id']);
			$result = $obj_instrument->get_single_instrument($instumrnt_id);
		}
		?>
		<script>
		$(document).ready(function() {
			$('#instrument_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
			$('#tax_charge').multiselect({
					nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
					includeSelectAllOption: true,
					selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
				 });
		});
		</script>
        <div class="panel-body"><!--START PANEL BODY DIV-->
			<form name="bed_form" action="" method="post" class="form-horizontal" id="instrument_form"><!--START INSTRUMENT FORM-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="instrument_id" value="<?php if(isset($_REQUEST['instumrnt_id'])) echo MJ_hmgt_id_decrypt($_REQUEST['instumrnt_id']);?>"  />
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
									<input id="instrument_name" class="form-control validate[required,custom[popup_category_validation]]" type="text"  maxlength="50"
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
											<input id="name" class="form-control text-input  validate[custom[popup_category_validation]]" type="text" maxlength="30"
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
											<input id="quantity" class="form-control  text-input" min="0" type="number" onKeyPress="if(this.value.length==4) return false;" 
											value="<?php if($edit){ echo $result->quantity;}elseif(isset($_POST['quantity'])) echo $_POST['quantity'];?>" name="quantity">
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label" for="price"><?php _e('Price','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
										<div class="col-sm-10">
											<input id="price" class="form-control  text-input" min="0" type="number" onKeyPress="if(this.value.length==8) return false;" step="0.01"
											value="<?php if($edit){ echo $result->price;}elseif(isset($_POST['price'])) echo $_POST['price'];?>" name="price">
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
								<input id="serial" class="form-control  text-input" type="number" min="0" onKeyPress="if(this.value.length==6) return false;" value="<?php if($edit){ echo $result->serial;}elseif(isset($_POST['serial'])) echo $_POST['serial'];?>" name="serial">
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
							<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_instrument" class="btn btn-success"/>
				</div>
	        </form><!--END INSTRUMENT FORM-->
	    </div><!--END PANEL BODY DIV-->
		<?php }
		if($active_tab=="assigninstrumentlist")
		{ ?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				jQuery('#instrument_lists').DataTable({
				"responsive": true,
				"aoColumns":[
				   {"bSortable": true},
				   {"bSortable": true},
				   {"bSortable": true},
				   {"bSortable": true},  		
				   {"bSortable": true},  		
				   {"bSortable": true},  		
					{"bSortable": true},			 
				   {"bSortable": false}],
				   language:<?php echo MJ_hmgt_datatable_multi_language();?>
				});
			});
		</script>
		<div class="panel-body"><!--STRAT PANEL BODY DIV-->
				<div class="table-responsive"><!--START TABLE RESPONSIVE DIV-->
					<table id="instrument_lists" class="display" cellspacing="0" width="100%"><!--START ASSIGNED Instrument LIST TABLE-->
						<thead>
							<tr>
								<th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Instrument', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Assigned Date', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Expected Return Date', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Charges Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php _e( 'Total Charges Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>							 
							</tr>
						</thead>
						<tfoot>
							<tr>
								 <th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
								 <th><?php _e( 'Instrument', 'hospital_mgt' ) ;?></th>
								 <th><?php _e( 'Assigned Date', 'hospital_mgt' ) ;?></th>
								 <th><?php _e( 'Expected Return Date', 'hospital_mgt' ) ;?></th>
								 <th><?php _e( 'Charges Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								 <th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								 <th><?php _e( 'Total Charges Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								 <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
							 
							</tr>
						</tfoot>
						<tbody>
						<?php 
						if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'pharmacist' || $obj_hospital->role == 'accountant' || $obj_hospital->role == 'receptionist') 
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$assigned_instrumentdata=$obj_instrument->get_all_assigned_instrument_by_created_by();
							}
							else
							{
								$assigned_instrumentdata=$obj_instrument->get_all_assigned_instrument();
							}
						}
						elseif($obj_hospital->role == 'doctor') 
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$assigned_instrumentdata=$obj_instrument->get_doctor_all_assigned_instrument_by_created_by();
							}
							else
							{
								$assigned_instrumentdata=$obj_instrument->get_all_assigned_instrument();
							}
						}
						elseif($obj_hospital->role == 'nurse') 
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$assigned_instrumentdata=$obj_instrument->get_nurse_all_assigned_instrument_by_created_by();
							}
							else
							{
								$assigned_instrumentdata=$obj_instrument->get_all_assigned_instrument();
							}
						}
						elseif($obj_hospital->role == 'patient')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$assigned_instrumentdata=$obj_instrument->get_all_assigned_instrument_by_patient();
							}
							else
							{
								$assigned_instrumentdata=$obj_instrument->get_all_assigned_instrument();
							}
						}						
						
						 if(!empty($assigned_instrumentdata))
						 {
							foreach ($assigned_instrumentdata as $retrieved_data){ 
							$patientdata=get_userdata($retrieved_data->patient_id);
						?>		
							<tr>
								<?php if($user_role == "doctor" || $user_role=="nurse" || $user_role=="receptionist" ){ ?> 
									<td class="bed_number"><a href="?dashboard=user&page=instrument&tab=assign_instrument&action=edit&assign_instument_id=<?php echo $retrieved_data->id;?>"><?php  echo $patientdata->display_name; ?></a></td>
								<?php } else { ?>
									<td class="bed_number"><?php  echo $patientdata->display_name; ?></td>
								<?php } ?>
								<td class="bed_type"><?php $instrumentdata=$obj_instrument->get_single_instrument($retrieved_data->instrument_id); echo $instrumentdata->instrument_name;?></td>
								
								<td class="descrition"><?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->start_date));	?></td>
								
								<td class="descrition"><?php if($retrieved_data->end_date!="0000-00-00" && "00/00/0000" && "00/00/0000"){ echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->end_date)); }?></td>
								<td class="charge">	<?php echo number_format($retrieved_data->charges_amount, 2, '.', '');?></td>
								<td class="charge">	<?php echo number_format($retrieved_data->total_tax, 2, '.', '');?></td>
								<td class="charge">	<?php echo number_format($retrieved_data->total_charge_amount, 2, '.', '');?></td>
								
								<td class="action"> 
								<?php
								if($user_access['edit']=='1')
								{
								?>
									<a href="?dashboard=user&page=instrument&tab=assign_instrument&action=edit&assign_instument_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->id);?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
								<?php
								}
								if($user_access['delete']=='1')
								{
								?>	
									<a href="?dashboard=user&page=instrument&tab=assigninstrumentlist&action=delete&assign_instument_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->id);?>" class="btn btn-danger" 
									onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
									<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>  
								<?php
								}
								?>	
								</td>
								<?php } ?>
							   
							</tr>
							<?php } ?>     
						</tbody>        
					</table><!--END Assign INSTRUMENT LIST TABLE-->
		        </DIV><!--END TABLE RESPONSIVE DIV-->
		</DIV><!--END PANEL BODY DIV-->
		<?php }
		if($active_tab=="assign_instrument")
		{ 
			$edit = 0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab']=='assign_instrument'){
				$edit = 1;
				$assign_instument_id = MJ_hmgt_id_decrypt($_REQUEST['assign_instument_id']);
				$result = $obj_instrument->get_single_assigned_instrument($assign_instument_id);
			}	
	    ?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#assign_instrument_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
			
			var start = new Date();
			var end = new Date(new Date().setYear(start.getFullYear()+1));
			$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
			$('#start_date').datepicker({
				startDate : start,
				endDate   : end,
				autoclose: true
			}).on('changeDate', function (selected) {
			var minDate = new Date(selected.date.valueOf());
			$('#end_date').datepicker('setStartDate', minDate);
			}); 
			$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
			$('#end_date').datepicker({
				startDate : start,
				endDate   : end,
				autoclose: true
			}).on('changeDate', function (selected) {
				var maxDate = new Date(selected.date.valueOf());
				$('#start_date').datepicker('setEndDate', maxDate);
			});

			$('#end_time').timepicki(  
			{
				show_meridian:false,
				min_hour_value:0,
				max_hour_value:23,
				step_size_minutes:15,
				overflow_minutes:true,
				increase_direction:'up',
				disable_keyboard_mobile: true}
				);
				$('#start_time').timepicki(
			{
				show_meridian:false,
				min_hour_value:0,
				max_hour_value:23,
				step_size_minutes:15,
				overflow_minutes:true,
				increase_direction:'up',
				disable_keyboard_mobile: true}
				);
		} );
		</script>
		<div class="panel-body"><!--START PANEL BODY DIV-->
				<form name="assign_instrument_form" action="" method="post" class="form-horizontal" id="assign_instrument_form"><!--START Assign INSTRUMENT FORM-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="assign_instrument_id" value="<?php if(isset($_REQUEST['assign_instument_id'])) echo MJ_hmgt_id_decrypt($_REQUEST['assign_instument_id']);?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="patient"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">				
						<?php if($edit){ $patient_id1=$result->patient_id; }elseif(isset($_POST['patient'])){$patient_id1=$_POST['patient'];}else{ $patient_id1="";}?>
						<select name="patient_id" class="form-control validate[required] " id="patient_id">
						<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
						<?php 					
							$patients = MJ_hmgt_patientid_list();
							//print_r($patient);
							if(!empty($patients)){
							foreach($patients as $patient){
								echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['patient_id'].' - '.$patient['first_name'].' '.$patient['last_name'].'</option>';
							} } ?>
						</select>
					</div>
						
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="patient"><?php _e('Instrument','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">				
						<?php if($edit){ $instrument_id=$result->instrument_id; }elseif(isset($_POST['instrument_id'])){$instrument_id=$_POST['instrument_id'];}else{ $instrument_id="";}?>
						<select name="instrument_id" class="form-control validate[required] " id="instrument_id" <?php if($edit) print "disabled"; ?> >
						<option value=""><?php _e('Select Instrument','hospital_mgt');?></option>
						<?php 					
							$instrumentdata=$obj_instrument->get_all_instrument();
							 if(!empty($instrumentdata))
							 {
								foreach ($instrumentdata as $retrieved_data){ 
							
								echo '<option value="'.$retrieved_data->id.'" '.selected($instrument_id,$retrieved_data->id).'>'.$retrieved_data->instrument_code.' - '.$retrieved_data->instrument_name.'</option>';
							} } ?>
						</select>
						<?php if($edit) print '<input type="hidden" name="instrument_id" value="'.$instrument_id.'">'; ?>
					</div>
						
				</div>
				<?php if($edit==1 && $result->charge_type=='Daily'){ ?>
				<input id="charge_type"  type="hidden" value="Daily" name="charge_type">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="facility_start_date">
					<?php _e('Start Date','hospital_mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="start_date" class="form-control validate[required] start" type="text"   
						value="<?php if($edit){ echo $result->start_date;}
						elseif(isset($_POST['start_date'])) echo $_POST['start_date'];?>" name="start_date">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="end_date">
					<?php _e('Expected End Date','hospital_mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="end_date" class="form-control validate[required] end" type="text"  
						value="<?php if($edit){ echo $result->end_date;}
						elseif(isset($_POST['end_date'])) echo $_POST['end_date'];?>" name="end_date">
					</div>
				</div>
				<?php } ?>
				<?php if($edit==1 && $result->charge_type=='Hourly'){ ?>
				<input id="charge_type"  type="hidden" value="Hourly" name="charge_type">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="facility_start_date">
					<?php _e('Instrument Assign Date','hospital_mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="start_date" class="form-control validate[required]" type="text"  
						value="<?php if($edit){ echo $result->start_date;}
						elseif(isset($_POST['start_date'])) echo $_POST['start_date'];?>" name="start_date">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="facility_start_date">
					<?php _e('Start Time','hospital_mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="start_time" type="text" value="<?php if($edit){ echo $result->start_time;}elseif(isset($_POST['start_time'])) echo $_POST['start_time'];?>" class="form-control timepicker start validate[required]" name="start_time"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="end_date">
					<?php _e('Expected End Time','hospital_mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="end_time" type="text"  value="<?php if($edit){ echo $result->end_time;}elseif(isset($_POST['end_time'])) echo $_POST['end_time'];?>" class="form-control timepicker end  validate[required]" name="end_time"/>
					</div>
				</div>
				<?php } ?>
				
				<div id="select_instrument_block">
						
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="description"><?php _e('Description','hospital_mgt');?></label>
					<div class="col-sm-8">
						<textarea id="description" class="form-control validate[custom[address_description_validation]]" maxlength="150"  name="description"><?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?></textarea>
					</div>
				</div>
				
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Assign Instrument','hospital_mgt');}?>" name="assign_instrument" class="btn btn-success assigned_instrument_validation"/>
				</div>
		    </form><!--END Assign INSTRUMENT FORM-->
        </div>	<!--END PANEL BODY DIV-->
	<?php }	?>
</div><!--END PANEL BODY DIV-->
</div><!--END PANEL BODY DIV-->