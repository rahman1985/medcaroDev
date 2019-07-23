<?php 
//Treatment
$obj_treatment = new MJ_hmgt_treatment();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'treatmentlist';
?>
<div class="page-inner" style="min-height:1631px !important">  <!--PAGE INNNER DIV START-->
     <!--PAGE TITLE START-->
    <div class="page-title">
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt') ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div>  <!--PAGE TITLE END-->
	<?php 
	if(isset($_REQUEST['save_treatment']))
	{
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{
	
			$result = $obj_treatment->hmgt_add_treatment($_POST);
			if($result)
			{
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url().'admin.php?page=hmgt_treatment&tab=treatmentlist&message=2');
				}
				else
				{
					wp_redirect ( admin_url().'admin.php?page=hmgt_treatment&tab=treatmentlist&message=1');
				}	
			}
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result = $obj_treatment->delete_treatment($_REQUEST['treatment_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=hmgt_treatment&tab=treatmentlist&message=3');
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
							<a href="?page=hmgt_treatment&tab=treatmentlist" class="nav-tab <?php echo $active_tab == 'treatmentlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Treatment List', 'hospital_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=hmgt_treatment&tab=addtreatment&&action=edit&treatment_id=<?php echo $_REQUEST['treatment_id'];?>" class="nav-tab <?php echo $active_tab == 'addtreatment' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Treatment', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_treatment&tab=addtreatment" class="nav-tab <?php echo $active_tab == 'addtreatment' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add New Treatment', 'hospital_mgt'); ?></a>  
							<?php  }?>
						   
						</h2>
						 <?php 
						//Report 1 
						if($active_tab == 'treatmentlist')
						{ 
						
						?>	
						<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('#treatment_list').DataTable({
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
						<form name="treatment" action="" method="post">						
							<div class="panel-body"><!-- START PANEL BODY DIV-->		
								<div class="table-responsive">	<!-- START TABLE RESPONSIVE DIV-->		
									<table id="treatment_list" class="display" cellspacing="0" width="100%">
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
										$treatment_data=$obj_treatment->get_all_treatment();
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
													<a href="?page=hmgt_treatment&tab=addtreatment&action=edit&treatment_id=<?php echo $retrieved_data->treatment_id;?>" class="btn btn-info"> 
													<?php _e('Edit', 'hospital_mgt' ) ;?></a>
													<a href="?page=hmgt_treatment&tab=treatmentlist&action=delete&treatment_id=<?php echo $retrieved_data->treatment_id;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
													<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
												</td>
											</tr>
											<?php } 
										}?>
										</tbody>
									
									</table>
							    </div>	<!-- END TABLE RESPONSIVE DIV-->		
							</div>	<!-- END PANEL BODY DIV-->							   
					    </form>
						 <?php 
						}
						if($active_tab == 'addtreatment')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/treatment/add_treatment.php';
						}
						?>
                    </div>	<!-- END PANEL BODY DIV-->		
		        </div><!-- END PANEL WHITE DIV-->
	        </div>
        </div><!-- END ROW DIV-->
	</div>
	<!-- END WRAPPER DIV-->
<?php //} ?>