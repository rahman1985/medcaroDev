<?php 
MJ_hmgt_browser_javascript_check();
$obj_invoice= new MJ_hmgt_invoice();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'invoicelist';
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="invoice_data"></div>			
		</div>
    </div>     
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important"><!-- PAGE INNER DIV START-->
	<div class="page-title"><!-- PAGE TITLE DIV START-->
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div><!-- PAGE TITLE DIV END-->
	<?php 
	//save tax
	if(isset($_POST['save_tax']))
	{	
		if($_REQUEST['action']=='edit')
		{				
			$result=$obj_invoice->hmgt_add_tax($_POST);
			if($result)	
			{			
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=taxlist&message=2');
			}				
		}
		else
		{		
			$result=$obj_invoice->hmgt_add_tax($_POST);
			
			if($result)
			{				
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=taxlist&message=1');
			}
		}
	}
	if(isset($_POST['save_invoice']))
	{
		global $wpdb;
		$table_hmgt_patient_transation = $wpdb->prefix . 'hmgt_patient_transation';
		if($_REQUEST['action']=='edit'){				
			$result=$obj_invoice->hmgt_add_invoice($_POST);
			if($result)	
			{			
				if(isset($_POST['transationdata']) && !empty($_POST['transationdata']))
				{
					foreach($_POST['transationdata'] as $transactionid)
					{					
						$wpdb->update($table_hmgt_patient_transation,array('status'=>'Paid'),array('id'=>$transactionid));
					}
				}				
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=invoicelist&message=2');
			}				
		}
		else
		{		
			$result=$obj_invoice->hmgt_add_invoice($_POST);
			
			if($result)
			{	
				if(isset($_POST['transationdata']) && !empty($_POST['transationdata']))
				{
					foreach($_POST['transationdata'] as $transactionid)
					{						
						$wpdb->update($table_hmgt_patient_transation,array('status'=>'Paid'),array('id'=>$transactionid));
					}
				}					
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=invoicelist&message=1');
			}
		}
	}
		
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		if(isset($_REQUEST['invoice_id']))
		{
			$result=$obj_invoice->delete_invoice($_REQUEST['invoice_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=invoicelist&message=3');
			}
		}
		
		if(isset($_REQUEST['income_id']))
		{
			$result=$obj_invoice->delete_income($_REQUEST['income_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=incomelist&message=3');
			}
		}
			
		if(isset($_REQUEST['expense_id']))
		{
			$result=$obj_invoice->delete_expense($_REQUEST['expense_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=expenselist&message=3');
			}
		}
		if(isset($_REQUEST['tax_id']))
		{
			$result=$obj_invoice->delete_tax($_REQUEST['tax_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=taxlist&message=3');
			}
		}				
	}
	//--------save income-------------
	if(isset($_POST['save_income']))
	{
		if($_REQUEST['action']=='edit')
		{
			$result=$obj_invoice->add_income($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=incomelist&message=2');
			}
		}
		else
		{
			$result=$obj_invoice->add_income($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=incomelist&message=1');
			}
		}			
	}
	
	//--------save Expense-------------
	if(isset($_POST['save_expense']))
	{			
		if($_REQUEST['action']=='edit')
		{
				
			$result=$obj_invoice->add_expense($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=expenselist&message=2');
			}
		}
		else
		{
			$result=$obj_invoice->add_expense($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=hmgt_invoice&tab=expenselist&message=1');
			}
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
	<div id="main-wrapper"><!-- MAIN WRAPER DIV START-->
		<div class="row"><!-- ROW DIV START-->
			<div class="col-md-12">
				<div class="panel panel-white"><!-- PANEL WHITE DIV START-->
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						<h2 class="nav-tab-wrapper">
							<a href="?page=hmgt_invoice&tab=invoicelist" class="nav-tab <?php echo $active_tab == 'invoicelist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Invoice List', 'hospital_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['invoice_id']))
							{?>
							<a href="?page=hmgt_invoice&tab=addinvoice&action=edit&invoice_id=<?php echo $_REQUEST['invoice_id'];?>" class="nav-tab <?php echo $active_tab == 'addinvoice' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Invoice', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_invoice&tab=addinvoice" class="nav-tab <?php echo $active_tab == 'addinvoice' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Invoice', 'hospital_mgt'); ?></a>  
							<?php  }?>
							<a href="?page=hmgt_invoice&tab=incomelist" class="nav-tab <?php echo $active_tab == 'incomelist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Income List', 'hospital_mgt'); ?></a>
							 <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['income_id']))
							{?>
							<a href="?page=hmgt_invoice&tab=addincome&action=edit&income_id=<?php echo $_REQUEST['income_id'];?>" class="nav-tab <?php echo $active_tab == 'addincome' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Income', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_invoice&tab=addincome" class="nav-tab <?php echo $active_tab == 'addincome' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Income', 'hospital_mgt'); ?></a>  
							<?php 
							}
							?>
							<a href="?page=hmgt_invoice&tab=expenselist" class="nav-tab <?php echo $active_tab == 'expenselist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Expense List', 'hospital_mgt'); ?></a>
							 <?php  
							 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['expense_id']))
							{?>
							<a href="?page=hmgt_invoice&tab=addexpense&action=edit&expense_id=<?php echo $_REQUEST['expense_id'];?>" class="nav-tab <?php echo $active_tab == 'addexpense' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Expense', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_invoice&tab=addexpense" class="nav-tab <?php echo $active_tab == 'addexpense' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Expense', 'hospital_mgt'); ?></a>  
							<?php
							}
							?>
							<a href="?page=hmgt_invoice&tab=taxlist" class="nav-tab <?php echo $active_tab == 'taxlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Tax List', 'hospital_mgt'); ?></a>
							 <?php  
							 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['tax_id']))
							{?>
							<a href="?page=hmgt_invoice&tab=addtax&action=edit&tax_id=<?php echo $_REQUEST['tax_id'];?>" class="nav-tab <?php echo $active_tab == 'addtax' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Tax', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_invoice&tab=addtax" class="nav-tab <?php echo $active_tab == 'addtax' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Tax', 'hospital_mgt'); ?></a>  
							<?php
							}
							?>
						</h2>
						<?php 						
						if($active_tab == 'invoicelist')
						{ 
						?>	
						 <script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('#tblinvoice').DataTable({
								"responsive": true,
								 "order": [[ 6, "Desc" ]],
								 "aoColumns":[
											  {"bSortable": true},
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
						<form name="wcwm_report" action="" method="post">
							<div class="panel-body"><!-- PANEL BODY DIV START-->
								<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->
									<table id="tblinvoice" class="display" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th><?php _e( 'Invoice ID', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Title', 'hospital_mgt' ) ;?></th>
												<th> <?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
												<th> <?php _e( 'Total Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th> <?php _e( 'Adjustment Amount ', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th> <?php _e( 'Paid Amount ', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th> <?php _e( 'Due Amount ', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th> <?php _e( 'Status', 'hospital_mgt' ) ;?></th>
												<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>												
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th><?php _e( 'Invoice ID', 'hospital_mgt' ) ;?></th>
												<th><?php _e( 'Title', 'hospital_mgt' ) ;?></th>
												<th> <?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
												<th> <?php _e( 'Total Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th> <?php _e( 'Adjustment Amount ', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th> <?php _e( 'Paid Amount ', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th> <?php _e( 'Due Amount ', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
												<th> <?php _e( 'Status', 'hospital_mgt' ) ;?></th>
												<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
											
											</tr>
										</tfoot>
										<tbody>
										 <?php
											foreach ($obj_invoice->get_all_invoice_data() as $retrieved_data)
											{
												
												if(empty($retrieved_data->adjustment_amount))
												{
													$due_amount=$retrieved_data->invoice_amount-$retrieved_data->paid_amount;
												}												
												else
												{
													$due_amount=$retrieved_data->invoice_amount-$retrieved_data->adjustment_amount-$retrieved_data->paid_amount;
												}
											?>
											<tr>
											<td class="title"><?php echo $retrieved_data->invoice_number; ?></td>
											<td class="title"><a href="?page=hmgt_invoice&tab=addinvoice&action=edit&invoice_id=<?php echo $retrieved_data->invoice_id;?>"><?php echo $retrieved_data->invoice_title; ?></a></td>
												<td class="patient"><?php echo $patient_id=get_user_meta($retrieved_data->patient_id, 'patient_id', true);?></td>
												<td class="vat_percentage"><?php echo number_format($retrieved_data->invoice_amount, 2, '.', '');?></td>
												<td class="adjustment_amount"><?php if(!empty($retrieved_data->adjustment_amount)) { echo number_format($retrieved_data->adjustment_amount, 2, '.', ''); }else{ echo '0.00'; } ?></td>
												<td class=""><?php echo number_format($retrieved_data->paid_amount, 2, '.', ''); ?></td>
												<td class=""><?php echo number_format($due_amount, 2, '.', ''); ?></td>
												<td class=""><?php echo $retrieved_data->status;?></td>												
												<td class="action">
												<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->invoice_id; ?>" invoice_type="invoice">
												<i class="fa fa-eye"></i> <?php _e('View Invoice', 'hospital_mgt');?></a>
												<?php
												if($retrieved_data->status != 'Paid')
												{
												?>	
													<a href="?page=hmgt_invoice&tab=addincome&patient_id=<?php echo $retrieved_data->patient_id;?>&due_amount=<?php echo number_format($due_amount, 2, '.', ''); ?>&invoice_id=<?php echo $retrieved_data->invoice_id;?>" class="btn btn-success margin_bottom_5px"><?php  _e( 'Add Income', 'hospital_mgt' ) ;?> </a>
												<?php
												}
												
												if($retrieved_data->paid_amount>0)
												{
												?>
													<a href="?page=payment_receipt&print=print&invoice_id=<?php echo $retrieved_data->invoice_id; ?>&invoice_type=payment_receipt" target="_blank" class="btn btn-info"> <?php _e('Print Payment Receipt', 'hospital_mgt' ) ;?>
														</a> 
												<?php													
												}
												?>
												<a href="?page=hmgt_invoice&tab=addinvoice&action=edit&invoice_id=<?php echo $retrieved_data->invoice_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
												<a href="?page=hmgt_invoice&tab=invoicelist&action=delete&invoice_id=<?php echo $retrieved_data->invoice_id;?>" class="btn btn-danger" 
												onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
												<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>												
												</td>											 
											</tr>
											<?php 
											}											
											?>									 
										</tbody>
									</table>
								</div><!-- TABLE RESPONSIVE DIV END-->
							</div><!-- PANEL BODY DIV END-->
						</form>
						<?php 
						}
						if($active_tab == 'addinvoice')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/add_invoice.php';
						}
						if($active_tab == 'incomelist')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/income-list.php';
						}
						if($active_tab == 'addincome')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/add_income.php';
						}
						if($active_tab == 'expenselist')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/expense-list.php';
						}
						if($active_tab == 'addexpense')
						{
								require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/add_expense.php';
						}
						if($active_tab == 'taxlist')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/tax_list.php';
						}
						if($active_tab == 'addtax')
						{
								require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/add_tax.php';
						}
						?>
                    </div><!-- PANEL BODY DIV END-->
	            </div><!-- PANEL WHITE DIV END-->
	        </div>
        </div><!-- ROW DIV END-->
    </div><!-- END MAIN WRAPER DIV -->
<?php ?>
