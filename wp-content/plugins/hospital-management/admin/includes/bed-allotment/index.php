<?php 
//bed allotment
$obj_bed = new MJ_hmgt_bedmanage();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'allotedbedlist';
?>
<div class="datas"></div>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content">
		<div class="modal-content">
		   <div class="category_list"></div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important"><!-- page INNER DIV START-->
	<div class="page-title"><!-- PAGE TITLE DIV START-->
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'hmgt_hospital_name' );?></h3>
	</div><!-- PAGE TITLE DIV END-->
<?php 
if(isset($_REQUEST['bedallotment']))
{	
	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{
		$result = $obj_bed->add_bed_allotment($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect(admin_url().'admin.php?page=hmgt_bedallotment&tab=allotedbedlist&message=2');
			}
			else
			{
				wp_redirect(admin_url().'admin.php?page=hmgt_bedallotment&tab=allotedbedlist&message=1');
			}				
		}
	}
}
if(isset($_POST['bed_transfar']))
{
	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'transfar')){	
		$result = $obj_bed->patient_bed_transfar($_POST);
		if($result)
		{		
			wp_redirect(admin_url().'admin.php?page=hmgt_bedallotment&tab=allotedbedlist&message=4');
		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_bed->delete_bedallocate_record($_REQUEST['allotment_id']);
	if($result)
	{
		wp_redirect ( admin_url() . 'admin.php?page=hmgt_bedallotment&tab=allotedbedlist&message=3');
	}
}
if(isset($_REQUEST['message']))
{
	$message =$_REQUEST['message'];
	if($message == 1){ ?>
		<div id="message" class="updated below-h2 "><p>		
			<?php _e('Record inserted successfully','hospital_mgt'); ?>
		</p></div>
		<?php 			
		}
		elseif($message == 2)
		{ ?>
			<div id="message" class="updated below-h2 "><p>
				<?php	_e("Record updated successfully.",'hospital_mgt');?>
			</p></div>
		<?php 			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Record deleted successfully','hospital_mgt');
		?></div></p><?php
				
		}
		
		elseif($message ==4) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Bed successfully Transfered','hospital_mgt');
		?></div></p><?php
				
		}
		
	}
	?>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START-->
		<div class="row"><!-- ROW DIV START-->
			<div class="col-md-12">
				<div class="panel panel-white"><!-- PANEL WHITE DIV START-->				
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						<h2 class="nav-tab-wrapper">
							<a href="?page=hmgt_bedallotment&tab=allotedbedlist" class="nav-tab <?php echo $active_tab == 'allotedbedlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Assigned Bed List', 'hospital_mgt'); ?></a>
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=hmgt_bedallotment&tab=bedassign&&action=edit&allotment_id=<?php echo $_REQUEST['allotment_id'];?>" class="nav-tab <?php echo $active_tab == 'bedassign' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Assigned Bed', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
							<a href="?page=hmgt_bedallotment&tab=bedassign" class="nav-tab <?php echo $active_tab == 'bedassign' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Assign Bed', 'hospital_mgt'); ?></a>  
							<?php  }?>
							<?php if($active_tab=="transfar"){ ?>
						   <a href="?page=hmgt_bedallotment&tab=transfar&action=transfar" class="nav-tab <?php echo $active_tab == 'transfar' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Transfer Bed', 'hospital_mgt'); ?></a>
							 <?php } ?>
						</h2>
						<?php 						
						if($active_tab == 'allotedbedlist')
						{ 
						?>	
						<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('#bedallotmentlist').DataTable({
								"responsive": true,
								 "order": [[ 4, "Desc" ]],
								 "aoColumns":[
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
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
									<table id="bedallotmentlist" class="display" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th><?php _e( 'Bed Category', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Bed Number', 'hospital_mgt' ) ;?></th>			 
												<th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Nurse', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Allotment Date', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Expected Discharge Date', 'hospital_mgt' ) ;?></th>
												<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
											</tr>
									    </thead>
										<tfoot>
											<tr>
												<th><?php _e( 'Bed Category', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Bed Number', 'hospital_mgt' ) ;?></th>			 
												<th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Nurse', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Allotment Date', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Expected Discharge Date', 'hospital_mgt' ) ;?></th>
												<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
											</tr>
										</tfoot>
										<tbody>
											 <?php 
											//$bedallotment_data=$obj_bed->get_all_bedallotment();
											$bedallotment_data=$obj_bed->get_all_bedallotment();
											if(!empty($bedallotment_data))
											{
												foreach ($bedallotment_data as $retrieved_data){ 
													$patient_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->patient_id);
											?>
												<tr>
													<td class="bed_type"><?php echo $obj_bed->get_bedtype_name($retrieved_data->bed_type_id);	?></td>
													<td class="bed_number"><?php 
													if(!empty($retrieved_data->bed_number))
													{  
														echo $obj_bed->get_bed_number($retrieved_data->bed_number);
													}
													else
													{ 
														echo '-'; 
													}	
													?></td>
													<td class="patient"><?php echo $patient_data['first_name']." ".$patient_data['last_name']."(".$patient_data['patient_id'].")";?></td>
													<td class="nurse">
													<?php 
													if(!empty($retrieved_data->bed_allotment_id))
													{ 
														$nurselist =  $obj_bed->get_nurse_by_assignid($retrieved_data->bed_allotment_id) ;
														foreach($nurselist as $assign_id)
														{
															$nurse_data =	MJ_hmgt_get_user_detail_byid($assign_id->child_id);
															echo $nurse_data['first_name']." ".$nurse_data['last_name'].",";
															
														}
													}
													else
													{ 
														echo '-'; 
													}	
													?>
													</td>
													<td class="allotment_time"><?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->allotment_date));?></td>
													<td class="discharge_time"><?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->discharge_time));?></td>
													<td class="action"> 
													<a href="?page=hmgt_bedallotment&tab=transfar&action=transfar&allotment_id=<?php echo $retrieved_data->bed_allotment_id;?>" class="btn btn-success"> <?php _e('Transfer Bed', 'hospital_mgt' ) ;?></a>
													<a href="?page=hmgt_bedallotment&tab=bedassign&action=edit&allotment_id=<?php echo $retrieved_data->bed_allotment_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
													
													<a href="?page=hmgt_bedallotment&tab=allotedbedlist&action=delete&allotment_id=<?php echo $retrieved_data->bed_allotment_id;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
													<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
													</td>
												</tr>
												<?php } 
											}?>
										</tbody>
									</table>
							    </div><!-- TABLE RESPONSIVE DIV END-->
							</div><!-- PANEL BODY DIV END-->
					    </form>
						<?php 
						}
						if($active_tab == 'bedassign')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/bed-allotment/bed-allotment.php';
						}
						if($active_tab == 'transfar')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/bed-allotment/transfar.php';
						}
						?>
                    </div><!-- PANEL BODY DIV END-->	
		        </div><!-- PANEL WHITE DIV END-->	
	        </div>
        </div><!-- ROW DIV END-->	
	</div><!-- main-wrapper DIV END -->	