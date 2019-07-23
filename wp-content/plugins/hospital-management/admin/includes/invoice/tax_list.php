<?php 
MJ_hmgt_browser_javascript_check();
$obj_invoice= new MJ_hmgt_invoice();
if($active_tab == 'taxlist')
{
	?>
	<script type="text/javascript">
	jQuery(document).ready(function()
	{
		jQuery('#tbltax').DataTable({
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
    <div class="panel-body"><!-- PANEL BODY DIV START-->
		<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->
			<table id="tbltax" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><?php _e( 'Tax Name', 'hospital_mgt' ) ;?></th>
						<th> <?php _e( 'Tax Value', 'hospital_mgt' ) ;?> (%)</th>
						<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php _e( 'Tax Name', 'hospital_mgt' ) ;?></th>
						<th> <?php _e( 'Tax Value', 'hospital_mgt' ) ;?> (%)</th>
						<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
					</tr>
				</tfoot>	 
				<tbody>
					<?php 
					foreach ($obj_invoice->get_all_tax_data() as $retrieved_data)
					{ 							
					?>
					<tr>
						<td class=""><?php echo $retrieved_data->tax_title; ?></td>
						<td class=""><?php echo $retrieved_data->tax_value; ?></td>							
						<td class="action">							
							<a href="?page=hmgt_invoice&tab=addtax&action=edit&tax_id=<?php echo $retrieved_data->tax_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>							
							<a href="?page=hmgt_invoice&tab=taxlist&action=delete&tax_id=<?php echo $retrieved_data->tax_id;?>" class="btn btn-danger" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');"><?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
						</td>
					</tr>
					<?php
					} 						
					?>				 
				</tbody>
			</table>
		</div><!-- TABLE RESPONSIVE DIV END -->
    </div><!-- PANEL BODY DIV END-->
<?php 
} 
?>