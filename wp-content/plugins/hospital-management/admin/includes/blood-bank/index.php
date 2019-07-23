<?php 
$obj_bloodbank=new MJ_hmgt_bloodbank();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'bloodbanklist';
?>
<div class="page-inner" style="min-height:1631px !important"><!-- page INNER DIV START-->
    <div class="page-title"><!-- page title DIV START-->
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div><!-- page title DIV END-->
	<?php 
	if(isset($_POST['save_dispatch_blood']))
	{
		global $wpdb;		
		$table_bloodbank=$wpdb->prefix. 'hmgt_blood_bank';
		
		if($_REQUEST['action']=='edit')
		{	
			//check blood stock
			$blood_group=$_POST['blood_group'];
			$blood_status=$_POST['blood_status'];
			$old_blood_group=$_POST['old_blood_group'];
			$old_blood_status=$_POST['old_blood_status'];
			$dispatchblood_id=$_POST['dispatchblood_id'];
			
			$result_blood_group = $wpdb->get_row("SELECT * FROM $table_bloodbank where blood_group='$blood_group'");
			$oldblood_status=$result_blood_group->blood_status;
		
			if($blood_group == $old_blood_group)
			{
				$oldblood_status=$oldblood_status+$old_blood_status;
				if(empty($result_blood_group))
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=adddispatchblood&action=edit&dispatchblood_id='.$dispatchblood_id.'&message=5');
				}
				elseif($blood_status>$oldblood_status)
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=adddispatchblood&action=edit&dispatchblood_id='.$dispatchblood_id.'&message=5');
				}
				else
				{
					$result=$obj_bloodbank->hmgt_add_dispatch_blood($_POST);
					if($result)
					{	
						wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=dispatchbloodlist&message=2');
					}
				}
			}
			else
			{
				if(empty($result_blood_group))
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=adddispatchblood&action=edit&dispatchblood_id='.$dispatchblood_id.'&message=5');
				}
				elseif($blood_status>$oldblood_status)
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=adddispatchblood&action=edit&dispatchblood_id='.$dispatchblood_id.'&message=5');
				}
				else
				{
					$result=$obj_bloodbank->hmgt_add_dispatch_blood($_POST);
					if($result)
					{	
						wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=dispatchbloodlist&message=2');
					}
				}	
			}
		}
		else
		{
			//check blood stock
			$blood_group=$_POST['blood_group'];
			$blood_status=$_POST['blood_status'];
			$result_blood_group = $wpdb->get_row("SELECT * FROM $table_bloodbank where blood_group='$blood_group'");
			$oldblood_status=$result_blood_group->blood_status;
			
			if(empty($result_blood_group))
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=adddispatchblood&message=5');
			}
			elseif($blood_status>$oldblood_status)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=adddispatchblood&message=5');
			}
			else
			{	
				$result=$obj_bloodbank->hmgt_add_dispatch_blood($_POST);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=dispatchbloodlist&message=1');
				}
			}
		}	
	}
	if(isset($_POST['save_blooddonor']))
	{
		if($_REQUEST['action']=='edit')
		{				
			$result=$obj_bloodbank->hmgt_add_blood_donor($_POST);
			if($result)
			{	
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=blooddonorlist&message=2');
			}
		}
		else
		{
			$result=$obj_bloodbank->hmgt_add_blood_donor($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=blooddonorlist&message=1');
			}
		}	
	}
	if(isset($_POST['save_bloodgroup']))
	{	
		if($_REQUEST['action']=='edit')
		{
			global $wpdb;
			$table_bloodbank=$wpdb->prefix. 'hmgt_blood_bank';
			$blood_group=$_POST['blood_group'];
			$bloodgroup_id=$_POST['bloodgroup_id'];
			$allready_added_result = $wpdb->get_row("SELECT * FROM $table_bloodbank where blood_group = '$blood_group' AND blood_id !=".$bloodgroup_id);
			
			if(!empty($allready_added_result))
			{				
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=addbloodgroop&action=edit&bloodgroup_id='.$bloodgroup_id.'&message=4');
			}
			else
			{			
				$result=$obj_bloodbank->add_blood_group($_POST);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=bloodbanklist&message=2');
				}
			}	
		}
		else
		{
			global $wpdb;
			$table_bloodbank=$wpdb->prefix. 'hmgt_blood_bank';
			$blood_group=$_POST['blood_group'];
			$allready_added_result = $wpdb->get_row("SELECT * FROM $table_bloodbank where blood_group = '$blood_group'");
			
			if(!empty($allready_added_result))
			{				
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=addbloodgroop&message=4');
			}
			else
			{		
				$result=$obj_bloodbank->add_blood_group($_POST);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=hmgt_bloodbank&tab=bloodbanklist&message=1');
				}
			}
		}
	}	
	
	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		if(isset($_REQUEST['blooddonor_id']))
		{
			$result=$obj_bloodbank->delete_blooddonor($_REQUEST['blooddonor_id']);
			wp_redirect( admin_url () . 'admin.php?page=hmgt_bloodbank&tab=blooddonorlist&message=3');
		}
		if(isset($_REQUEST['bloodgroup_id']))
		{
			$result=$obj_bloodbank->delete_bloodgroup($_REQUEST['bloodgroup_id']);
			wp_redirect( admin_url () . 'admin.php?page=hmgt_bloodbank&tab=bloodbanklist&message=3');
		}
		if(isset($_REQUEST['dispatchblood_id']))
		{
			$result=$obj_bloodbank->delete_dispatchblood_data($_REQUEST['dispatchblood_id']);
			wp_redirect( admin_url () . 'admin.php?page=hmgt_bloodbank&tab=dispatchbloodlist&message=3');
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
					_e("Record updated successfully.",'hospital_mgt');
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
		elseif($message == 4) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('This blood group allready added you want to update it','hospital_mgt');
		?></div></p><?php
				
		}
		elseif($message == 5) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('This blood group stock not enough','hospital_mgt');
		?></div></p><?php
				
		}
	}
	?>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START -->
		<div class="row"><!-- ROW DIV START-->
			<div class="col-md-12">
				<div class="panel panel-white"><!-- PANEL WHITE DIV START-->
				<!-- PANEL BODY DIV START-->
					<div class="panel-body">
						<h2 class="nav-tab-wrapper">
							<a href="?page=hmgt_bloodbank&tab=bloodbanklist" class="nav-tab <?php echo $active_tab == 'bloodbanklist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Blood Manage', 'hospital_mgt'); ?></a>
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['bloodgroup_id']))
							{?>
								<a href="?page=hmgt_bloodbank&tab=addbloodgroop&action=edit&bloodgroup_id=<?php if(isset($_REQUEST['bloodgroup_id'])) echo $_REQUEST['bloodgroup_id'];?>" class="nav-tab <?php echo $active_tab == 'addbloodgroop' ? 'nav-tab-active' : ''; ?>">
							<?php echo __('Edit Blood Group', 'hospital_mgt'); ?></a>
							<?php }
							else
							{?>
							<a href="?page=hmgt_bloodbank&tab=addbloodgroop" class="nav-tab <?php echo $active_tab == 'addbloodgroop' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Blood Group', 'hospital_mgt'); ?></a>
							<?php }?>
							<a href="?page=hmgt_bloodbank&tab=blooddonorlist" class="nav-tab <?php echo $active_tab == 'blooddonorlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Blood Donor List', 'hospital_mgt'); ?></a> 
							<?php 
							if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['blooddonor_id']))
							{	?>
							<a href="?page=hmgt_bloodbank&tab=addblooddonor&action=edit&blooddonor_id=<?php if(isset($_REQUEST['blooddonor_id'])) echo $_REQUEST['blooddonor_id'];?>" class="nav-tab <?php echo $active_tab == 'addblooddonor' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Blood Donor', 'hospital_mgt'); ?></a>  
							<?php 
							
							}
							else
							{?>
								<a href="?page=hmgt_bloodbank&tab=addblooddonor" class="nav-tab <?php echo $active_tab == 'addblooddonor' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Blood Donor', 'hospital_mgt'); ?></a>  
							<?php 
							}
							?>
							<a href="?page=hmgt_bloodbank&tab=dispatchbloodlist" class="nav-tab <?php echo $active_tab == 'dispatchbloodlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Dispatch Blood List', 'hospital_mgt'); ?></a>
							<?php							
							if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['dispatchblood_id']))
							{	?>
							<a href="?page=hmgt_bloodbank&tab=adddispatchblood&action=edit&dispatchblood_id=<?php if(isset($_REQUEST['dispatchblood_id'])) echo $_REQUEST['dispatchblood_id'];?>" class="nav-tab <?php echo $active_tab == 'adddispatchblood' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Dispatch Blood', 'hospital_mgt'); ?></a>  
							<?php							
							}
							else
							{?>
								<a href="?page=hmgt_bloodbank&tab=adddispatchblood" class="nav-tab <?php echo $active_tab == 'adddispatchblood' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Dispatch Blood', 'hospital_mgt'); ?></a>  
							<?php  }?>
						</h2>
						 <?php 
						
						if($active_tab == 'bloodbanklist')
						{ ?>	
							<script type="text/javascript">
							jQuery(document).ready(function() {
								jQuery('#bloodbag').DataTable({
									"responsive": true,
									 "aoColumns":[
												  {"bSortable": true},
												  {"bSortable": true},	                             
												  {"bSortable": false}
											   ],
									language:<?php echo MJ_hmgt_datatable_multi_language();?>		   
									});
									
							} );
							</script>
							<form name="wcwm_report" action="" method="post">
								<div class="panel-body"><!-- PANEL BODY DIV START-->
										<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->
											<table id="bloodbag" class="display" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th><?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
														<th><?php _e( 'Number Of Bags', 'hospital_mgt' ) ;?></th> 
														<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
													</tr>
												</thead>
												<tfoot>
													<tr>
													<th><?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>
													   <th><?php _e( 'Number Of Bags', 'hospital_mgt' ) ;?></th> 
														<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
													</tr>
												</tfoot>
												<tbody>
												 <?php foreach($obj_bloodbank->get_all_bloodgroups() as $retrieved_data){  ?>
													<tr>
														<td class="blood_group">
														<?php 
																echo $retrieved_data->blood_group;
														?></td>
														<td class="subject_name"><?php  echo $retrieved_data->blood_status;;?></td>
													  
														<td class="action"> <a href="?page=hmgt_bloodbank&tab=addbloodgroop&&action=edit&bloodgroup_id=<?php echo $retrieved_data->blood_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
														<a href="?page=hmgt_bloodbank&tab=bloodbanklist&action=delete&bloodgroup_id=<?php  echo $retrieved_data->blood_id;?>" class="btn btn-danger" 
														onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
														<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
														</td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div><!-- TABLE RESPONSIVE DIV START-->
								</div><!-- PANEL BODY DIV END-->
							</form>
						<?php 
						}
						if($active_tab == 'addbloodgroop')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/blood-bank/add-blood-group.php';
						}						
						if($active_tab == 'addblooddonor')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/blood-bank/add-blood-donor.php';
						}
						if($active_tab == 'blooddonorlist')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/blood-bank/blood-donor-list.php';
						}
						if($active_tab == 'adddispatchblood')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/blood-bank/add-dispatch-blood.php';
						}
						if($active_tab == 'dispatchbloodlist')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/blood-bank/dispatch-blood-list.php';
						}
						?>
                    </div><!-- END BODY DIV -->
		        </div><!-- END PANEL WHITE DIV -->
	        </div>
        </div><!-- ROW DIV START-->
    </div><!-- END MAIN WRAPER DIV -->		
<?php //} ?>