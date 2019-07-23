<?php 
if($active_tab == 'assigned_instrumentlist')
{ ?>	
    <script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#instrument_list').DataTable({
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
    <form name="wcwm_report" action="" method="post">    
        <div class="panel-body"><!-- PANEL BODY DIV START -->		
        	<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->		
				<table id="instrument_list" class="display" cellspacing="0" width="100%">
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
				$assigned_instrumentdata=$obj_instrument->get_all_assigned_instrument();
				
				 if(!empty($assigned_instrumentdata))
				 {
					foreach ($assigned_instrumentdata as $retrieved_data){ 
							
					$patientdata=get_userdata($retrieved_data->patient_id);
					
				?>		
					<tr>
						<td class="bed_number"><a href="?page=hmgt_instrument_mgt&tab=assigninstrument&action=edit&assign_instument_id=<?php echo $retrieved_data->id;?>"><?php  echo $patientdata->display_name; ?></a></td>
						<td class="bed_type"><?php $instrumentdata=$obj_instrument->get_single_instrument($retrieved_data->instrument_id); echo $instrumentdata->instrument_name;?></td>
						
						<td class="descrition"><?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->start_date));	?></td>
						
						
						<td class="descrition"><?php if($retrieved_data->end_date!="0000-00-00" && "00/00/0000" && "00/00/0000"){ echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->end_date)); }?></td>
						<td class="charge">	<?php echo number_format($retrieved_data->charges_amount, 2, '.', ''); ?></td>
						<td class="charge">	<?php echo number_format($retrieved_data->total_tax, 2, '.', ''); ?></td>
						<td class="charge">	<?php echo number_format($retrieved_data->total_charge_amount, 2, '.', ''); ?></td>
						<td class="action"> <a href="?page=hmgt_instrument_mgt&tab=assigninstrument&action=edit&assign_instument_id=<?php echo $retrieved_data->id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
						<a href="?page=hmgt_instrument_mgt&tab=instrumentlist&action=delete&assign_instument_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
						onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
						<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>               
						</td>
					   
					</tr>
					<?php } 
					
				}?>
			 
				</tbody>
				
				</table>
			</div><!-- TABLE RESPONSIVE DIV END -->		
        </div> <!-- PANEL BODY DIV END -->		      
	</form>
    <?php 
} ?>