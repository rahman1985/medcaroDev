<?php
MJ_hmgt_browser_javascript_check();
$obj_treatment = new MJ_hmgt_treatment();
//access right
$user_access=MJ_hmgt_get_userrole_wise_access_right_array();

 $user_id=get_current_user_id();
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
//SAVE Treatment DATA
if(isset($_REQUEST['save_treatment']))
{

	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{

		$result = $obj_treatment->hmgt_add_treatment($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url().'?dashboard=user&page=treatment&tab=treatmentlist&message=2');
			 }
			else 
			{	
			wp_redirect ( home_url().'?dashboard=user&page=treatment&tab=treatmentlist&message=1');
			}
			
			
		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_treatment->delete_treatment(MJ_hmgt_id_decrypt($_REQUEST['treatment_id']));
	if($result)
	{
			wp_redirect ( home_url().'?dashboard=user&page=treatment&tab=treatmentlist&message=3');
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
$active_tab = isset($_GET['tab'])?$_GET['tab']:'treatmentlist';
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#treatment_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
	jQuery('#hmgt_treatment').DataTable({
		"responsive": true,
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},	                
	                  {"bSortable": true},	                
	                  {"bSortable": false}],
			language:<?php echo MJ_hmgt_datatable_multi_language();?>		  
		});
} );
</script>

<div class="panel-body panel-white"><!-- START PANEL BODY DIV-->
	 <ul class="nav nav-tabs panel_tabs" role="tablist">
		  <li class="<?php if($active_tab == 'treatmentlist'){?>active<?php }?>">
			  <a href="?dashboard=user&page=treatment&tab=treatmentlist">
				 <i class="fa fa-align-justify"></i> <?php _e('Treatment List', 'hospital_mgt'); ?></a>
			  </a>
		  </li>
		  <li class="<?php if($active_tab=='addtreatment'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{?>
				<a href="?dashboard=user&page=treatment&tab=addtreatment&action=edit&treatment_id=<?php if(isset($_REQUEST['treatment_id'])) echo $_REQUEST['treatment_id'];?>"" class="tab <?php echo $active_tab == 'addtreatment' ? 'active' : ''; ?>">
				 <i class="fa fa"></i> <?php _e('Edit Treatment', 'hospital_mgt'); ?></a>
				 <?php }
				else
				{
					if($user_access['add']=='1')
					{
					?>
						<a href="?dashboard=user&page=treatment&tab=addtreatment&&action=insert" class="tab <?php echo $active_tab == 'addtreatment' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php _e('Add Treatment', 'hospital_mgt'); ?></a>
				<?php 
					}
				}
			?>
		  
		</li>
		 
	</ul>
	<div class="tab-content"><!-- START TAB CONTENT DIV-->
	<?php 
	if($active_tab == 'treatmentlist')
	{?>
	
    	<div class="tab-pane fade active in"  id="eventlist"><!-- START TAB PANE DIV-->
			<div class="panel-body"><!-- START PANEL BODY DIV-->
				<div class="table-responsive"><!-- START TABLE ReSPONSIVE DIV-->
					<table id="hmgt_treatment" class="display dataTable " cellspacing="0" width="100%"><!-- START Treatment LIST TABLE-->
							<thead>
							<tr>
							<th><?php _e( 'Treatment Name', 'hospital_mgt' ) ;?></th>
							 <th><?php _e( 'Price', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
							   <th><?php  _e( 'Tax', 'hospital_mgt' ) ;?></th>
							   <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
							<th><?php _e( 'Treatment Name', 'hospital_mgt' ) ;?></th>
							 <th><?php _e( 'Price', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php  _e( 'Tax', 'hospital_mgt' ) ;?></th>
								<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
							</tr>
						</tfoot>
				 
						<tbody>
						 <?php 		
						if($obj_hospital->role == 'doctor' || $obj_hospital->role == 'nurse' || $obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'pharmacist' || $obj_hospital->role == 'accountant' || $obj_hospital->role == 'receptionist') 
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$treatment_data=$obj_treatment->get_all_treatment_by_id();
							}
							else
							{
								$treatment_data=$obj_treatment->get_all_treatment();
							}
						}
						else
						{
							$treatment_data=$obj_treatment->get_all_treatment();
						}
						
						if(!empty($treatment_data))
						{
							foreach ($treatment_data as $retrieved_data)
							{ 
							?>
							<tr>
								<td class="treatment_name"><?php echo $retrieved_data->treatment_name;?></td>
								<td class="treatment_price"><?php echo $retrieved_data->treatment_price;?></td>                
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
								<td class="action">
								<?php
								if($user_access['edit']=='1')
								{
								?>				
									<a href="?dashboard=user&page=treatment&tab=addtreatment&action=edit&treatment_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->treatment_id);?>" class="btn btn-info"> 
									<?php _e('Edit', 'hospital_mgt' ) ;?></a>
								<?php
								}
								if($user_access['delete']=='1')
								{
								?>	               
									<a href="?dashboard=user&page=treatment&tab=treatmentlist&action=delete&treatment_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->treatment_id);?>" class="btn btn-danger" 
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
					</table><!-- END Treatment LIST TABLE-->
				</div><!-- END TABLE ReSPONSIVE DIV-->
			</div><!-- END PANEL BODY DIV-->
		</div><!-- END TAB PANE DIV-->
	<?php 
	}
	 if($active_tab == 'addtreatment')
	 {			 
		 $edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit = 1;
			$treatment_id = MJ_hmgt_id_decrypt($_REQUEST['treatment_id']);
			$result = $obj_treatment->get_single_treatment($treatment_id);
			
		}
	?>	
	<div class="panel-body"><!-- START PANEL BODY DIV-->
        <form name="treatment_form" action="" method="post" class="form-horizontal" id="treatment_form"><!-- START Treatment FORM-->
			 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="treatment_id" value="<?php if(isset($_REQUEST['treatment_id'])) echo MJ_hmgt_id_decrypt($_REQUEST['treatment_id']);?>"  />
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="med_category_name"><?php _e('Treatment Name','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="treatment_name" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" 
					value="<?php if($edit){ echo $result->treatment_name;}elseif(isset($_POST['treatment_name'])) echo $_POST['treatment_name'];?>" name="treatment_name">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="treatment_price"><?php _e('Treatment Price','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
				<div class="col-sm-8">
					<input id="treatment_price" class="form-control " min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01"  value="<?php if($edit){ echo $result->treatment_price;}elseif(isset($_POST['treatment_price'])) echo $_POST['treatment_price'];?>" name="treatment_price">
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

			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_treatment" class="btn btn-success"/>
			</div>
        </form><!-- END Treatment FORM-->
        </div><!-- END PANEL BODY DIV-->
	 <?php 
	 }
	 ?>
	</div><!-- END TAB CONTENT DIV-->
</div><!-- END PANEL BODY DIV-->