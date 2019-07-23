<?php 
$obj_bloodbank= new MJ_hmgt_bloodbank();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'dispatchbloodlist';
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#dispatch_bood').DataTable({
		"responsive": true,
		 "aoColumns":[
	                  {"bSortable": true},
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
	<div class="panel-body"><!-- PANEL BODY DIV START-->
		<form name="wcwm_report" action="" method="post">
			<div class="panel-body"><!-- PANEL BODY DIV START-->
				<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->
					<table id="dispatch_bood" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th><?php _e('Patient Name', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>								
								<th><?php _e( 'Number Of Bags', 'hospital_mgt' ) ;?></th> 
								<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th> 
								<th><?php _e( 'Charges', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php _e( 'Total Charges', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th><?php _e('Patient Name', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Blood Group', 'hospital_mgt' ) ;?></th>								
								<th><?php _e( 'Number Of Bags', 'hospital_mgt' ) ;?></th> 
								<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th> 
								<th><?php _e( 'Charges', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php _e( 'Total Charges', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
							</tr>
						</tfoot>			 
						<tbody>
						 <?php 
						$dispatch_blooddata=$obj_bloodbank->get_all_dispatch_blood_data();
						 if(!empty($dispatch_blooddata))
						 {
							foreach($dispatch_blooddata as $retrieved_data)
							{ 
							$patient_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->patient_id);
							?>
							<tr>							
								<td class="patient"><?php echo $patient_data['first_name']." ".$patient_data['last_name']."(".$patient_data['patient_id'].")";?></td>
								<td class="bloodgroup">
								<?php 							
									echo $retrieved_data->blood_group;
								?>
								</td>							
								<td class="subject_name"><?php  echo $retrieved_data->blood_status;;?></td>
							  <td class=""><?php echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->date)); ?></td>
							  	<td class=""><?php  echo $retrieved_data->blood_charge;;?></td>
								<td class=""><?php  echo $retrieved_data->total_tax;;?></td>
								<td class=""><?php  echo $retrieved_data->total_blood_charge;;?></td>
								<td class="action">
								<a href="?page=hmgt_bloodbank&tab=adddispatchblood&action=edit&dispatchblood_id=<?php echo $retrieved_data->dispatchblood_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
								<a href="?page=hmgt_bloodbank&tab=dispatchbloodlist&action=delete&dispatchblood_id=<?php echo $retrieved_data->dispatchblood_id;?>" class="btn btn-danger" 
								onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
								<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
							   
								</td>							   
							</tr>
							<?php 
							} 							
						}?>
						</tbody>
					</table>
				</div><!-- TABLE RESPONSIVE DIV END-->
			</div><!-- PANEL BODY DIV END-->
	    </form>
    </div><!-- PANEL BODY DIV END-->
<?php ?>