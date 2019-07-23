<?php 
//Operation Theator
$obj_ot = new MJ_hmgt_operation();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'operationlist';
?>
<div class="datas"> </div>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content overlay_content_css">
		<div class="modal-content">
		   <div class="category_list"></div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important"><!-- PAGE INNER DIV START-->
	<div class="page-title"><!-- PAGE TITILE DIV START-->
			<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div><!-- PAGE TITLE DIV END-->
<?php 
if(isset($_REQUEST['save_operation']))
{
	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{
		$result = $obj_ot->hmgt_add_operation_theater($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( admin_url().'admin.php?page=hmgt_operation&tab=operationlist&message=2');
			}
			else 
			{
				wp_redirect ( admin_url().'admin.php?page=hmgt_operation&tab=operationlist&message=1'); 
			}
		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{			
	$result = $obj_ot->delete_oprationtheater($_REQUEST['ot_id']);
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=hmgt_operation&tab=operationlist&message=3');
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
}?>
 <!-- MAIN WRAPPER DIV START-->
	<div id="main-wrapper">
		<div class="row"> <!-- ROW DIV START-->
			<div class="col-md-12">
				<div class="panel panel-white"> <!-- PANEL WHITE DIV START-->
				<!-- PANEL BODY DIV START -->
					<div class="panel-body">
						<h2 class="nav-tab-wrapper">
							<a href="?page=hmgt_operation&tab=operationlist" class="nav-tab <?php echo $active_tab == 'operationlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Operation List', 'hospital_mgt'); ?></a>
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=hmgt_operation&tab=addoperation&&action=edit&ot_id=<?php echo $_REQUEST['ot_id'];?>" class="nav-tab <?php echo $active_tab == 'addoperation' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Operation List', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_operation&tab=addoperation" class="nav-tab <?php echo $active_tab == 'addoperation' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Operation', 'hospital_mgt'); ?></a>  
							<?php  }?>
						   
						</h2>
						<?php				 
						if($active_tab == 'operationlist')
						{ ?>	
							<script type="text/javascript">
						   jQuery(document).ready(function() {
							jQuery('#hmgt_operation').DataTable({
								"responsive": true,
								 "order": [[ 3, "Desc" ]],
								 "aoColumns":[
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bVisible": true},	                 
											  {"bVisible": true},	                 
											  {"bVisible": true},	                 
											  {"bSortable": false}
										   ],
									language:<?php echo MJ_hmgt_datatable_multi_language();?>
								});
						} );
						</script>
						<form name="wcwm_report" action="" method="post">
						    <div class="panel-body">
								<div class="table-responsive">
									<table id="hmgt_operation" class="display" cellspacing="0" width="100%">
										<thead>
											<tr>
												 <th><?php _e( 'Operation Name', 'hospital_mgt' ) ;?></th>
												 <th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
												 <th><?php _e( 'Surgeon', 'hospital_mgt' ) ;?></th>
												 <th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>
												 <th><?php _e( 'Operation Charge', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												  <th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												   <th><?php _e( 'Total Operation Charge', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												 <th><?php _e( 'Operation Status', 'hospital_mgt' ) ;?></th>
												 <th><?php _e( 'Out Come Status', 'hospital_mgt' ) ;?></th>
												 <th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
											</tr>
									    </thead>
										<tfoot>
											<tr>
												<th><?php _e( 'Operation Name', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Surgeon', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Operation Charge', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th><?php _e( 'Total Operation Charge', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th><?php _e( 'Operation Status', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Out Come Status', 'hospital_mgt' ) ;?></th>
												<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
											</tr>
										</tfoot>
										<tbody>
										 <?php 
										$ot_data=$obj_ot->get_all_operation();
										if(!empty($ot_data))
										{
											foreach ($ot_data as $retrieved_data)
											{ 
												$patient_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->patient_id);
											?>
											<tr>
												<td class="operation_name"><?php echo $obj_ot->get_operation_name($retrieved_data->operation_title);?></td>
												<td class="patient"><?php echo $patient_data['first_name']." ".$patient_data['last_name']."(".$patient_data['patient_id'].")";?></td>
												<td class="surgen">
												<?php 
													$surgenlist =  $obj_ot->get_doctor_by_oprationid($retrieved_data->operation_id) ;
													$surgenlist_names = '';
													foreach($surgenlist as $assign_id)
													{
														$doctory_data =	MJ_hmgt_get_user_detail_byid($assign_id->child_id);
													  $surgenlist_names.= $doctory_data['first_name']." ".$doctory_data['last_name'].",";
													}
													echo rtrim($surgenlist_names, ',');
												?></td>
												
												<td class=""><?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->operation_date));	?></td>
												<td class=""><?php echo number_format($retrieved_data->ot_charge, 2, '.', '');?></td>
												<td class=""><?php echo number_format($retrieved_data->ot_tax, 2, '.', ''); ?></td>
												<td class=""><?php echo number_format($retrieved_data->operation_charge, 2, '.', ''); ?></td>
												<td class=""><?php if(!empty($retrieved_data->operation_status)) { _e(''.$retrieved_data->operation_status.'','hospital_mgt'); }else{ echo '-'; } ?></td>
												<td class=""><?php if(!empty($retrieved_data->out_come_status)) { _e(''.$retrieved_data->out_come_status.'','hospital_mgt'); }else{ echo '-'; } ?></td>
												<td class="action"> <a href="?page=hmgt_operation&tab=addoperation&action=edit&ot_id=<?php echo $retrieved_data->operation_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
												<a href="?page=hmgt_operation&tab=operationlist&action=delete&ot_id=<?php echo $retrieved_data->operation_id;?>" class="btn btn-danger" 
												onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
												<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>               
												</td>
											   
											</tr>
											<?php
											} 
										}
										?>
										</tbody>
									</table>
							    </div>
						    </div>						   
					    </form>
						<?php 
						}						
						if($active_tab == 'addoperation')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/OT/add-opration.php';
						}
						?>
                    </div><!-- PANEL BODY DIV END--> 	
		        </div><!-- PANEL WHITE DIV END-->
	        </div>
        </div><!-- END ROW DIV -->
    </div><!-- END MAIN WRAPPER DIV -->