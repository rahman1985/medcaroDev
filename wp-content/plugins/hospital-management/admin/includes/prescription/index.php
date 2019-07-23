<?php 
MJ_hmgt_browser_javascript_check();
$obj_var=new MJ_hmgt_prescription();
$obj_treatment=new MJ_hmgt_treatment();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'prescriptionlist';
?>
<div class="popup-bg" style="z-index:100000 !important;">
	<div class="overlay-content">
		<div class="prescription_content"></div>    
	</div> 
</div>  
<div class="page-inner" style="min-height:1631px !important"><!--paGE INNER DIV START-->	      
   <div class="page-title"><!--paGE TITLE DIV START-->
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div><!--paGE TITLE DIV END-->
	<?php 
	if(isset($_POST['save_prescription']))
	{
		if($_REQUEST['action']=='edit')
		{
			$result=$obj_var->hmgt_add_prescription($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_prescription&tab=prescriptionlist&message=2');
			}
		}
		else
		{
			$result=$obj_var->hmgt_add_prescription($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_prescription&tab=prescriptionlist&message=1');
			}
		}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
			
		$result=$obj_var->delete_prescription($_REQUEST['prescription_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=hmgt_prescription&tab=prescriptionlist&message=3');
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
	<!--WRAPPER DIV START-->
	<div id="main-wrapper">
		<div class="row"><!--ROW DIV START-->
			<div class="col-md-12">
			<!--PANEL WHITE DIV START-->
				<div class="panel panel-white">
				<!--PANEL BODY DIV START-->
					<div class="panel-body">
						<h2 class="nav-tab-wrapper">
							<a href="?page=hmgt_prescription&tab=prescriptionlist" class="nav-tab <?php echo $active_tab == 'prescriptionlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Prescription List', 'hospital_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=hmgt_prescription&tab=addprescription&&action=edit&prescription_id=<?php echo $_REQUEST['prescription_id'];?>" class="nav-tab <?php echo $active_tab == 'addprescription' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Prescription', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_prescription&tab=addprescription" class="nav-tab <?php echo $active_tab == 'addprescription' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Prescription', 'hospital_mgt'); ?></a>  
							<?php  }?>

						   
						</h2>
						<?php 
						if($active_tab == 'prescriptionlist')
						{ ?>	
							<script type="text/javascript">
							jQuery(document).ready(function() {
								jQuery('#prescription_list').DataTable({
									"responsive": true,							
									"order": [[ 0, "Desc" ]],
									"aoColumns":[
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
								<div class="panel-body"><!--PANEL BODY DIV START-->
									<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
										<table id="prescription_list" class="display" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
													<th> <?php _e( 'Patient ID', 'hospital_mgt' ) ;?></th>
													<th> <?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
													<th> <?php _e( 'Type', 'hospital_mgt' ) ;?></th>
													<th> <?php _e( 'Treatment', 'hospital_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
													<th> <?php _e( 'Patient ID', 'hospital_mgt' ) ;?></th>
													<th> <?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
													<th> <?php _e( 'Type', 'hospital_mgt' ) ;?></th>
													<th> <?php _e( 'Treatment', 'hospital_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
												</tr>
											</tfoot>
											<tbody>
											 <?php 
											$prescriptiondata=$obj_var->get_all_prescription();
											
											if(!empty($prescriptiondata))
											{
												foreach ($prescriptiondata as $retrieved_data){ 
											 ?>
												<tr>
													<td class="name"><a href="?page=hmgt_prescription&tab=addprescription&action=edit&prescription_id=<?php echo $retrieved_data->priscription_id;?>"><?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->pris_create_date));?></a></td>
													<td class="patient">
														 <?php 
														   echo $patient_id=get_user_meta($retrieved_data->patient_id, 'patient_id', true);
														 ?>
													</td>
													<td class="patient">
														 <?php 
															$patient = MJ_hmgt_get_user_detail_byid( $retrieved_data->patient_id);
															echo  $patient['first_name']." ".$patient['last_name'];?>
													</td>
												   <td class=""><?php 
												    if(!empty($retrieved_data->prescription_type))
												    {
														echo ucfirst($retrieved_data->prescription_type); 
													}
													else
													{ 
														echo '-'; 
													}
												   ?> </td>
													<td class="treatment"><?php 
													if(!empty($retrieved_data->teratment_id)){ echo $treatment=$obj_treatment->get_treatment_name($retrieved_data->teratment_id); }else{ echo '-'; } ?></td>
													<td class="action"> 
														  <a href="#" class="btn btn-primary view-prescription" id="<?php echo $retrieved_data->priscription_id;?>" prescription_type="<?php echo $retrieved_data->prescription_type; ?>"> <?php _e('View','hospital_mgt');?></a>
														<a href="?page=hmgt_prescription&tab=addprescription&action=edit&prescription_id=<?php echo $retrieved_data->priscription_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
														<a href="?page=hmgt_prescription&tab=prescriptionlist&action=delete&prescription_id=<?php echo $retrieved_data->priscription_id;?>" class="btn btn-danger" 
														onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
														<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
													</td>
												</tr>
												<?php } 
												
											}?>
											</tbody>										
										</table>
									</div><!-- TABLE RESPONSIVE DIV END-->
								</div> <!--PANEL BODY DIV END-->
							</form>
						<?php 
						}
						if($active_tab == 'addprescription')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/prescription/add_prescription.php';
						}
						?>
                    </div><!--PANEL BODY DIV END-->
	            </div><!--PANEL WHITE DIV END-->
	        </div>
        </div><!-- END ROW DIV-->
    </div>
	<!-- END WRAPPER DIV-->
<?php //} ?>