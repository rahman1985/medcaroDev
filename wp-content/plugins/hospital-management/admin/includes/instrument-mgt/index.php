<?php 
//Manage bed
$obj_instrument = new MJ_hmgt_Instrumentmanage();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'instrumentlist';
?>
<div class="page-inner" style="min-height:1631px !important"><!-- PAGE INNER DIV START-->	
	<div class="page-title"><!-- PAGE TITLE DIV START-->	
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div><!-- PAGE TITLE DIV END-->
	<?php 
	if(isset($_REQUEST['save_instrument']))
	{	
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{	
			$result = $obj_instrument->hmgt_add_instrument($_POST);
			if($result)
			{
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_instrument_mgt&tab=instrumentlist&message=2');
				}
				else
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_instrument_mgt&tab=instrumentlist&message=1');
				}	
			}
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		if(isset($_REQUEST['instumrnt_id']))
		{
			$result = $obj_instrument->delete_instrument($_REQUEST['instumrnt_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_instrument_mgt&tab=instrumentlist&message=3');
			}
		}
		if(isset($_REQUEST['assign_instument_id']))
		{
			$result = $obj_instrument->delete_assigned_instrument($_REQUEST['assign_instument_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_instrument_mgt&tab=assigned_instrumentlist&message=3');
			}
		}
	}	
	if(isset($_REQUEST['assign_instrument']))
	{	
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{			
			$result = $obj_instrument->hmgt_assign_instrument($_POST);
			if($result)
			{
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_instrument_mgt&tab=assigned_instrumentlist&message=2');
				}
				else
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_instrument_mgt&tab=assigned_instrumentlist&message=1');
				}	
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
		{?>
			<div id="message" class="updated below-h2 "><p><?php
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
			?></div></p>
			<?php				
		}
	}
	?>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START -->
		<div class="row"><!-- ROW DIV START -->
			<div class="col-md-12">
				<div class="panel panel-white"><!-- PANE WHITE DIV START -->
					<div class="panel-body"><!-- PANEL BODY DIV START -->
						<h2 class="nav-tab-wrapper">
							<a href="?page=hmgt_instrument_mgt&tab=instrumentlist" class="nav-tab <?php echo $active_tab == 'instrumentlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Instrument List', 'hospital_mgt'); ?></a>
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'addinstrument')
							{?>
							<a href="?page=hmgt_instrument_mgt&tab=addinstrument&&action=edit&instumrnt_id=<?php echo $_REQUEST['instumrnt_id'];?>" class="nav-tab <?php echo $active_tab == 'addinstrument' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Instrument', 'hospital_mgt'); ?></a>  
							
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_instrument_mgt&tab=addinstrument" class="nav-tab <?php echo $active_tab == 'addinstrument' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Instrument', 'hospital_mgt'); ?></a>  
							
							 
							<?php  }?>
							
							<a href="?page=hmgt_instrument_mgt&tab=assigned_instrumentlist" class="nav-tab <?php echo $active_tab == 'assigned_instrumentlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Assigned Instrument List', 'hospital_mgt'); ?></a>
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab']=='assigninstrument')
							{?>
							<a href="?page=hmgt_instrument_mgt&tab=assigninstrument&&action=edit&assign_instument_id=<?php echo $_REQUEST['assign_instument_id'];?>" class="nav-tab <?php echo $active_tab == 'assigninstrument' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Assigned Instrument', 'hospital_mgt'); ?></a>  
							
							<?php 
							}
							else 
							{?>
								<a href="?page=hmgt_instrument_mgt&tab=assigninstrument" class="nav-tab <?php echo $active_tab == 'assigninstrument' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Assign Instrument', 'hospital_mgt'); ?></a>  
							<?php  }?>
						</h2>
						<?php 						
						if($active_tab == 'instrumentlist')
						{ 					
						?>	
							<script type="text/javascript">
							jQuery(document).ready(function()
							{
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
							<form name="wcwm_report" action="" method="post">
								<div class="panel-body"><!-- PANEL BODY DIV START-->
									<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
										<table id="instrument_list" class="display" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th><?php _e( 'Instrument Code', 'hospital_mgt' ) ;?></th>
													<th><?php _e( 'Name', 'hospital_mgt' ) ;?></th>
													<th><?php _e( 'Charges', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
													<th><?php _e( 'Tax', 'hospital_mgt' ) ;?></th>
													<th><?php _e( 'Charges Type', 'hospital_mgt' ) ;?></th>
													<th><?php _e( 'Description', 'hospital_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
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
													  <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
												</tr>
											</tfoot>
											<tbody>
											 <?php 
											$instrumentdata=$obj_instrument->get_all_instrument();
											if(!empty($instrumentdata))
											{
												foreach ($instrumentdata as $retrieved_data){ 
											?>
												<tr>
													<td class="bed_number"><a href="?page=hmgt_instrument_mgt&tab=addinstrument&action=edit&instumrnt_id=<?php echo $retrieved_data->id;?>"><?php echo $retrieved_data->instrument_code;?></a></td>
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
													<td class="descrition"><?php echo $retrieved_data->instrument_description;?></td>
													<td class="action"> <a href="?page=hmgt_instrument_mgt&tab=addinstrument&action=edit&instumrnt_id=<?php echo $retrieved_data->id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
													<a href="?page=hmgt_instrument_mgt&tab=instrumentlist&action=delete&instumrnt_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
													<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>               
													</td>
												   
												</tr>
												<?php } 
											}?>
											</tbody>
										</table>
									</div><!--TABLE RESPONSIVE DIV END -->
								</div><!-- TABLE BODY DIV END -->								   
							</form>
						 <?php 
						}
						if($active_tab == 'addinstrument')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/instrument-mgt/add-instrument.php';
						}
						if($active_tab == 'assigned_instrumentlist')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/instrument-mgt/assigned-instrument-list.php';
						}
						if($active_tab == 'assigninstrument')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/instrument-mgt/assign-instrument.php';
						}
						 ?>
                    </div><!-- PANEL BODY DIV END -->		
		        </div><!-- PANEL WHITE DIV END -->		
	        </div>
        </div><!-- ROW DIV END -->		
    </div><!-- END MAIN WRAPER DIV -->	
<?php ?>