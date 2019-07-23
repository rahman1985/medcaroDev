<?php 
$obj_dignosis = new MJ_hmgt_dignosis();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'diagnosislist';
?>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content overlay_content_css">
    <div class="modal-content">
    <div class="category_list">
     </div>
     
    </div>
    </div> 
    
</div>
<!-- End POP-UP Code -->

<div class="page-inner" style="min-height:1631px !important"><!-- PAGE INNER DIV START-->
	<div class="page-title"><!-- PAGE TITILE DIV START-->
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div><!-- PAGE TITILE DIV END-->
	<?php 
	if(isset($_REQUEST['save_diagnosis']))
	{		
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{	 
			if(isset($_FILES['document']) && !empty($_FILES['document']) && $_FILES['document']['size'] !=0)
			{		
				$valid='0';
				
				$count_array=count($_FILES['document']['name']);

				for($a=0;$a<$count_array;$a++)
				{
					
					foreach($_FILES['document'] as $image_key=>$image_val)
					{						
						$value = explode(".", $_FILES['document']['name'][$a]);
					
						$file_ext = strtolower(array_pop($value));
						$extensions = array("jpg","jpeg","png","doc","gif","pdf","zip","");
						if(in_array($file_ext,$extensions ) == false)
						{
							$valid='1';
						}	
					}
				}
				if($valid == '1')
				{
				?>
					<div id="message" class="updated below-h2 ">
					<p>
					<?php 
						_e('Sorry, Only JPG,JPEG,PNG,GIF,DOC,PDF and ZIP files are allowed.','hospital_mgt');
					?></p></div>
					<?php 
				}
				else
				{ 
				
					$result = $obj_dignosis->hmgt_add_dignosis($_POST);
				
					if($result)
					{
						if($_REQUEST['action'] == 'edit')
						{
							wp_redirect ( admin_url() . 'admin.php?page=hmgt_diagnosis&tab=diagnosislist&message=2');
						}
						else
						{
							wp_redirect ( admin_url() . 'admin.php?page=hmgt_diagnosis&tab=diagnosislist&message=1');
						}
					}	
				}		
			}
			else
			{
				
				$result = $obj_dignosis->hmgt_add_dignosis($_POST);
				
				
				if($result)
				{
					if($_REQUEST['action'] == 'edit')
					{
						wp_redirect ( admin_url() . 'admin.php?page=hmgt_diagnosis&tab=diagnosislist&message=2');
					}
					else
					{
						wp_redirect ( admin_url() . 'admin.php?page=hmgt_diagnosis&tab=diagnosislist&message=1');
					}
				}	
			}					
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result = $obj_dignosis->delete_dignosis($_REQUEST['diagnosis_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=hmgt_diagnosis&tab=diagnosislist&message=3');
		}
	}
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{	?>
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
							<a href="?page=hmgt_diagnosis&tab=diagnosislist" class="nav-tab <?php echo $active_tab == 'diagnosislist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Diagnosis Report List', 'hospital_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=hmgt_diagnosis&tab=adddiagnosis&&action=edit&diagnosis_id=<?php echo $_REQUEST['diagnosis_id'];?>" class="nav-tab <?php echo $active_tab == 'adddiagnosis' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Diagnosis Report', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_diagnosis&tab=adddiagnosis" class="nav-tab <?php echo $active_tab == 'adddiagnosis' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add Diagnosis Report', 'hospital_mgt'); ?></a>  
							<?php  }?>
						   
						</h2>
						<?php 
						if($active_tab == 'diagnosislist')
						{ 
						
						?>	
						<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('#diagnosis').DataTable({
								"responsive": true,
								 "order": [[ 0, "Desc" ]],
								 "aoColumns":[
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bVisible": true},	                 
											  {"bSortable": false}
										   ],
								language:<?php echo MJ_hmgt_datatable_multi_language();?>
								});
						} );
						</script>
						<form name="wcwm_report" action="" method="post">
							<div class="panel-body"><!-- PANEL BODY DIV START-->
								<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->
									<table id="diagnosis" class="display" cellspacing="0" width="100%">
										<thead>
										<tr>
										<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
										 <th> <?php _e( 'Patient ID-Name', 'hospital_mgt' ) ;?></th>
										  <th> <?php _e( 'Report Type & Amount', 'hospital_mgt' ) ;?></th>
											<th width="250px"> <?php _e( 'Description', 'hospital_mgt' ) ;?></th>
											<th> <?php _e( 'Report', 'hospital_mgt' ) ;?></th>
											<th><?php _e( 'Report Cost', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
											<th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
											<th><?php _e( 'Total Report Cost', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
											<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
										</tr>
										</thead>
										<tfoot>
											<tr>
											<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
											<th> <?php _e( 'Patient ID-Name', 'hospital_mgt' ) ;?></th>
											  <th> <?php _e( 'Report Type & Amount', 'hospital_mgt' ) ;?></th>
												<th width="250px">  <?php _e( 'Description', 'hospital_mgt' ) ;?></th>
												<th> <?php _e( 'Report', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Report Cost', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th><?php _e( 'Total Report Cost', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
											</tr>
										</tfoot>
										<tbody>
										<?php 
										$dignosis_data=$obj_dignosis->get_all_dignosis_report();
										if(!empty($dignosis_data))
										{
											foreach ($dignosis_data as $retrieved_data)
											{ 
											?>
											<tr>
												<td class="date"><?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->diagnosis_date));	?></td>
												<td class="patient_id">
												<?php 
													$patient = MJ_hmgt_get_user_detail_byid( $retrieved_data->patient_id);
													echo $patient['id']." - ".$patient['first_name']." ".$patient['last_name'];
												
												?></td>
												<!-- <td class="report_type"> <?php echo $obj_dignosis->get_report_type_name($retrieved_data->report_type);?></td> -->
												<?php 
												  $report_type=new MJ_hmgt_dignosis();
												  $report_type_data=explode(",",$retrieved_data->report_type);
												?>
												<td class="report_type">
												<?php
												  $i=1;
												
												  if(!empty($retrieved_data->report_type))
												  {	  
													  foreach ($report_type_data as $report_id)
													  {
														$report_data=$report_type->get_report_by_id($report_id);
														$report_type_array=json_decode($report_data);
														echo '('.$i .') '.$report_type_array->category_name.'=>'.$report_type_array->report_cost.'';
														?>
														</br>
														<?php
														 $i++;
													  }
												  }	  
												?>
												 </td> 
												
												<td class="description"><?php echo $retrieved_data->diagno_description;?></td>		
												<td class="report">
												<?php
													if(MJ_hmgt_isJSON($retrieved_data->attach_report))
													{
														$dignosis_array=json_decode($retrieved_data->attach_report);
														
														foreach($dignosis_array as $key=>$value)
														{
															$report_type=new MJ_hmgt_dignosis();
															$report_data=$report_type->get_report_by_id($value->report_id);
															$report_type_array=json_decode($report_data);
														
															echo '<a href="'.content_url().'/uploads/hospital_assets/'.$value->attach_report.'" class="btn btn-default" target="_blank"><i class="fa fa-download"></i> '.$report_type_array->category_name.' '.__('Report','hospital_mgt').'</a></br>';
														}
													}	
													elseif(trim($retrieved_data->attach_report) != "")
													{
														echo '<a href="'.content_url().'/uploads/hospital_assets/'.$retrieved_data->attach_report.'" class="btn btn-default" target="_blank"><i class="fa fa-download"></i>  '. __( "Download", "hospital_mgt" ) .' </a>';
													}
													else 
													{
														 _e( 'No any Report', 'hospital_mgt' ) ; 
													}
												?>
												</td>	
												<td class=""><?php echo number_format($retrieved_data->report_cost, 2, '.', ''); ?></td>
												<td class=""><?php echo number_format($retrieved_data->total_tax, 2, '.', ''); ?></td>
												<td class=""><?php echo number_format($retrieved_data->total_cost, 2, '.', ''); ?></td>	
												<td class="action"> 
												<?php
												if($retrieved_data->total_cost!="")
												{
												?>
													<a href="?page=hmgt_diagnosis&tab=adddiagnosis&action=edit&diagnosis_id=<?php echo $retrieved_data->diagnosis_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
												<?php
												}
												?>
												<a href="?page=hmgt_diagnosis&tab=diagnosislist&action=delete&diagnosis_id=<?php echo $retrieved_data->diagnosis_id;?>" 
												class="btn btn-danger" 
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
								</div><!-- TABLE RESPONSIVE DIV END-->
							</div><!-- PANEL BODY DIV END-->						   
						</form>
						<?php 
						}						
						if($active_tab == 'adddiagnosis')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/diagnosis/add_diagnosis.php';
						}
						?>
				</div><!-- PANEL BODY DIV END-->			
			</div><!-- PANEL WHITE DIV END-->
		</div>
	</div><!-- ROW DIV END-->
<?php //} ?>