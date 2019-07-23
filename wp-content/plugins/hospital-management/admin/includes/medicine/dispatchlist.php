<?php 
$obj = new MJ_hmgt_prescription();
?>	
<script type="text/javascript">
jQuery(document).ready(function($) {
	jQuery('#dispatchlist').DataTable({
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
} );
</script>
<form name="dispatchlist" action="" method="post">
    <div class="panel-body"><!--PANEL BODY DIV START-->
        <div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
			<table id="dispatchlist" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
						<th><?php _e( 'Prescription', 'hospital_mgt' ) ;?></th>
						<th><?php _e( 'Medicine Price', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th> 
						<th><?php _e( 'Discount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th> 
						<th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th> 
						<th><?php _e( 'Sub Total', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th> 
						<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
					</tr>
				</thead>
				<tfoot>
						 <tr>
							<th><?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Prescription', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Medicine Price', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th> 
							<th><?php _e( 'Discount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th> 
							<th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th> 
							<th><?php _e( 'Sub Total', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th> 
							<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
						</tr>
				</tfoot>		 
				<tbody>
					 <?php 
					$medicinedata=$obj_medicine->get_all_dispatch_medicine();
					
					if(!empty($medicinedata))
					{
						foreach ($medicinedata as $retrieved_data)
						{
							$prescriptiondata = $obj->get_prescription_data($retrieved_data->prescription_id);
						?>
						<tr>
							<td class=""><?php	echo MJ_hmgt_get_display_name($retrieved_data->patient);	?></td>
							<td class=""><?php echo MJ_hmgt_get_display_name($prescriptiondata->patient_id) .' - '.$prescriptiondata->pris_create_date; ?></td>
							<td class=""><?php  echo $retrieved_data->med_price;	?></td>
							<td class=""><?php  echo $retrieved_data->discount;	?></td>
							<td class=""><?php  echo $retrieved_data->total_tax_amount;	?></td>							
							<td class=""><?php echo $retrieved_data->sub_total;?></td>
							
							<td class="action"> <a href="?page=hmgt_medicine&tab=dispatch-medicine&&action=edit&dispatch_id=<?php echo $retrieved_data->id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
							<a href="?page=hmgt_medicine&tab=medicinelist&action=delete&dispatch_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
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
        </div><!--TABLE RESPONSIVE DIV END-->
    </div><!--PANEL BODY DIV END-->
</form>
<?php ?>