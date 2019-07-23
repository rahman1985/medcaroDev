<?php 
MJ_hmgt_browser_javascript_check();
$obj_invoice= new MJ_hmgt_invoice();
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'invoicelist';
//access right
$user_access=MJ_hmgt_get_userrole_wise_access_right_array();

if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJ_hmgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJ_hmgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJ_hmgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJ_hmgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
if(isset($_POST['save_invoice']))
{
	if($_REQUEST['action']=='edit')
	{
		$result=$obj_invoice->hmgt_add_invoice($_POST);
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=invoice&tab=invoicelist&message=2');
		}
	}
	else
	{
		$result=$obj_invoice->hmgt_add_invoice($_POST);
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=invoice&tab=invoicelist&message=1');
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
					wp_redirect ( home_url() . '?dashboard=user&page=invoice&tab=incomelist&message=2');
				}
			}
			else
			{
				$result=$obj_invoice->add_income($_POST);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=invoice&tab=incomelist&message=1');
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
					wp_redirect ( home_url() . '?dashboard=user&page=invoice&tab=expenselist&message=2');
				}
			}
			else
			{
				$result=$obj_invoice->add_expense($_POST);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=invoice&tab=expenselist&message=1');
				}
			}
			
		}

	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			if(isset($_REQUEST['invoice_id'])){
			$result=$obj_invoice->delete_invoice(MJ_hmgt_id_decrypt($_REQUEST['invoice_id']));
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=invoice&tab=invoicelist&message=3');
				}
			}
			if(isset($_REQUEST['income_id'])){
			$result=$obj_invoice->delete_income(MJ_hmgt_id_decrypt($_REQUEST['income_id']));
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=invoice&tab=incomelist&message=3');
				}
			}
			if(isset($_REQUEST['expense_id'])){
			$result=$obj_invoice->delete_expense(MJ_hmgt_id_decrypt($_REQUEST['expense_id']));
				if($result)
				{
					wp_redirect (  home_url() . '?dashboard=user&page=invoice&tab=expenselist&message=3');
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

<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#invoice_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#income_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#expense_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
			
	        $.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
            $('#invoice_date').datepicker({
            autoclose: true
           }); 
} );
</script>

<style>
select{
	padding:6px;
}
</style>
<!-- START POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
    <div class="modal-content">
    <div class="invoice_data">
     </div>
     
    </div>
    </div> 
    
</div>
<!-- End POP-UP Code -->
<div class="panel-body panel-white"><!-- START PANEL BODY DIV -->
	 <ul class="nav nav-tabs panel_tabs" role="tablist">
		  <li class="<?php if($active_tab=='invoicelist'){?>active<?php }?>">
				<a href="?dashboard=user&page=invoice&tab=invoicelist" class="tab <?php echo $active_tab == 'invoicelist' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php _e('Invoice List', 'hospital_mgt'); ?></a>
			  </a>
		  </li>
		   <li class="<?php if($active_tab=='addinvoice'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['invoice_id']))
				{?>
				<a href="?dashboard=user&page=invoice&tab=addinvoice&action=edit&invoice_id=<?php if(isset($_REQUEST['invoice_id'])) echo $_REQUEST['invoice_id'];?>"" class="tab <?php echo $active_tab == 'addinvoice' ? 'active' : ''; ?>">
				 <i class="fa fa"></i> <?php _e('Edit Invoice', 'hospital_mgt'); ?></a>
				 <?php }
				else
				{
					if($user_access['add']=='1')
					{	
					?>
						<a href="?dashboard=user&page=invoice&tab=addinvoice&&action=insert" class="tab <?php echo $active_tab == 'addinvoice' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php _e('Add Invoice', 'hospital_mgt'); ?></a>
				<?php  
					} 
				}
				?>
		  
		</li>
		<?php
		$user_role=MJ_hmgt_get_current_user_role();
		if($user_role!='patient')
		{			
		?>	
		<li class="<?php if($active_tab=='incomelist'){?>active<?php }?>">
				<a href="?dashboard=user&page=invoice&tab=incomelist" class="tab <?php echo $active_tab == 'incomelist' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php _e('Income List', 'hospital_mgt'); ?></a>
			  </a>
		</li>
		<?php
		}
		?>	
		   <li class="<?php if($active_tab=='addincome'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['income_id']))
				{?>
				<a href="?dashboard=user&page=invoice&tab=addincome&action=edit&income_id=<?php if(isset($_REQUEST['income_id'])) echo $_REQUEST['income_id'];?>"" class="tab <?php echo $active_tab == 'addincome' ? 'active' : ''; ?>">
				 <i class="fa fa"></i> <?php _e('Edit Income', 'hospital_mgt'); ?></a>
				 <?php }
				else
				{
					if($user_access['add']=='1')
					{
					?>
						<a href="?dashboard=user&page=invoice&tab=addincome&&action=insert" class="tab <?php echo $active_tab == 'addincome' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php _e('Add Income', 'hospital_mgt'); ?></a>
					<?php 
					} 
				}
				?>	  
		</li>
		<?php
		$user_role=MJ_hmgt_get_current_user_role();
		if($user_role!='patient')
		{			
		?>	
		<li class="<?php if($active_tab=='expenselist'){?>active<?php }?>">
				<a href="?dashboard=user&page=invoice&tab=expenselist" class="tab <?php echo $active_tab == 'expenselist' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php _e('Expense List', 'hospital_mgt'); ?></a>
			  </a>
		  </li>
		<?php
		}
		?>	
		   <li class="<?php if($active_tab=='addexpense'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['expense_id']))
				{?>
				<a href="?dashboard=user&page=invoice&tab=addexpense&action=edit&expense_id=<?php if(isset($_REQUEST['expense_id'])) echo $_REQUEST['expense_id'];?>"" class="tab <?php echo $active_tab == 'addexpense' ? 'active' : ''; ?>">
				 <i class="fa fa"></i> <?php _e('Edit Expense', 'hospital_mgt'); ?></a>
				 <?php }
				else
				{
					if($user_access['add']=='1')
					{
					?>
						<a href="?dashboard=user&page=invoice&tab=addexpense&&action=insert" class="tab <?php echo $active_tab == 'addexpense' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php _e('Add Expense', 'hospital_mgt'); ?></a>
				<?php 
					} 
				}
				?>
		  
		</li>
	</ul>
<?php 
if($active_tab=='invoicelist')
{ ?>
	<script type="text/javascript">
	jQuery(document).ready(function($)
	{
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
	<div class="tab-content"><!-- START TABLE CONTENT DIV -->
    	 
		<div class="panel-body"><!-- START PANEL BODY DIV -->
        <div class="table-responsive"><!-- START TABLE RESPONSIVE DIV -->
        <table id="tblinvoice" class="display dataTable" cellspacing="0" width="100%"><!-- START INVOICE LIST TABLE-->
        	<thead>
            <tr>
			    <th><?php _e( 'Invoice ID', 'hospital_mgt' ) ;?></th>
                <th><?php _e( 'Title', 'hospital_mgt' ) ;?></th>
			    <th> <?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
				<th> <?php _e( 'Total Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>				
				<th> <?php _e( 'Adjustment Amount ', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
				<th> <?php _e( 'Paid Amount ', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
				<th> <?php _e( 'due Amount ', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
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
            $userid=get_current_user_id();			 
			if($obj_hospital->role == 'patient')
			{	
				$own_data=$user_access['own_data'];
				if($own_data == '1')
			    {
					$invoicedata=$obj_invoice->get_all_invoice_data_by_patient($userid);
				}
                else
				{
			      $invoicedata=$obj_invoice->get_all_invoice_data();
				}
				
			}
			elseif($obj_hospital->role == 'doctor')
			{	
				$own_data=$user_access['own_data'];
				if($own_data == '1')
			    {
					$invoicedata=$obj_invoice->get_doctor_all_invoice_data_creted_by($userid);
			    }
                else
				{
			      $invoicedata=$obj_invoice->get_all_invoice_data();
				}
				
			}
			elseif($obj_hospital->role == 'nurse')
			{	
				$own_data=$user_access['own_data'];
				if($own_data == '1')
			    {
					$invoicedata=$obj_invoice->get_nurse_all_invoice_data_creted_by($userid);
			    }
                else
				{
					$invoicedata=$obj_invoice->get_all_invoice_data();
				}				
			}
			elseif($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'pharmacist' || $obj_hospital->role == 'accountant' || $obj_hospital->role == 'receptionist') 
		    {
			   $own_data=$user_access['own_data'];
			   if($own_data == '1')
			    {
					$invoicedata=$obj_invoice->get_all_invoice_data_creted_by($userid);
			    }
                else
				{
			      $invoicedata=$obj_invoice->get_all_invoice_data();
				}
			}		
			
		 	foreach ($invoicedata as $retrieved_data)
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
                <td class="title"><a href="?dashboard=user&page=invoice&tab=addinvoice&action=edit&invoice_id=<?php echo $retrieved_data->invoice_id;?>"><?php echo $retrieved_data->invoice_title; ?></a></td>
                <td class="patient"><?php echo $patient_id=get_user_meta($retrieved_data->patient_id, 'patient_id', true);?></td>
				<td class=""><?php echo number_format($retrieved_data->invoice_amount, 2, '.', ''); ?></td>
				<td class="adjustment_amount"><?php if(!empty($retrieved_data->adjustment_amount)) { echo number_format($retrieved_data->adjustment_amount, 2, '.', ''); }else{ echo '0.00'; } ?></td>
				<td class=""><?php echo number_format($retrieved_data->paid_amount, 2, '.', ''); ?></td>
				<td class=""><?php echo number_format($due_amount, 2, '.', ''); ?></td>
                <td class="email"><?php echo $retrieved_data->status; ?></td>
                
               	<td class="action">
				<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->invoice_id; ?>" invoice_type="invoice">
				<i class="fa fa-eye"></i> <?php _e('View Invoice', 'hospital_mgt');?></a>
				<?php
				if($obj_hospital->role != 'patient')
				{
					if($retrieved_data->status != 'Paid')
					{
					?>	
						<a href="?dashboard=user&page=invoice&tab=addincome&patient_id=<?php echo $retrieved_data->patient_id;?>&due_amount=<?php echo number_format($due_amount, 2, '.', ''); ?>&invoice_id=<?php echo $retrieved_data->invoice_id;?>" class="btn btn-success margin_bottom_5px"><?php  _e( 'Add Income', 'hospital_mgt' ) ;?> </a>
					<?php
					}				
				}	
				if($retrieved_data->paid_amount>0)
				{
				?>
					<a href="?dashboard=user&page=payment_receipt&print=print&invoice_id=<?php echo $retrieved_data->invoice_id; ?>&invoice_type=payment_receipt" target="_blank" class="btn btn-info"> <?php _e('Print Payment Receipt', 'hospital_mgt' ) ;?>
						</a> 
				<?php													
				}
			
				if($user_access['edit']=='1')
				{
				?>
					<a href="?dashboard=user&page=invoice&tab=addinvoice&action=edit&invoice_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->invoice_id);?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <?php
				}
				if($user_access['delete']=='1')
				{
				?>	
					<a href="?dashboard=user&page=invoice&tab=invoicelist&action=delete&invoice_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->invoice_id);?>" class="btn btn-danger" 
					onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
					<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
                <?php
				}
				?>	
                </td>				
            </tr>
            <?php } 
			
		?>
     
        </tbody>
        
        </table><!-- END Invoice LIST TABLE -->
        </div><!-- END TABLE REPONSIVE DIV -->
        </div><!-- END PANEL BODY DIV -->
	<?php
	} // end of invoice list

	if($active_tab == 'addinvoice')
	{ 
		$obj_invoice= new MJ_hmgt_invoice();
		?>
		<script type="text/javascript">

		jQuery(document).ready(function($) {
			$('#invoice_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
					
					$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
					$('#invoice_date').datepicker({				   
					autoclose: true
				   }); 
		});
		</script>
    <?php 	
	if($active_tab == 'addinvoice')
	{				
		$transaction_ids = array();
		$transationdata = array();
		$amount = array();
		$discount_amount = array();
		$tax_amount = array();
		$total_amount = array();
		
		
		global $wpdb;	
		$table_hmgt_patient_transation = $wpdb->prefix. 'hmgt_patient_transation';	
		//SAVE Charges TransACTION DATA
		if(isset($_POST['get_totale']))
		{	
			$total=0;
			$amount =array();
			$discount_amount =array();
			$tax_amount =array();
			$total_amount =array();
			$transaction_ids =array();
			if(isset($_POST['amount']))	
			{
				$amount = $_POST['amount'];	
			}
			if(isset($_POST['discount_amount']))	
			{
				$discount_amount = $_POST['discount_amount'];	
			}
			if(isset($_POST['tax_amount']))	
			{
				$tax_amount = $_POST['tax_amount'];	
			}
			if(isset($_POST['total_amount']))	
			{
				$total_amount = $_POST['total_amount'];	
			}
			
			if(!empty($_POST['transaction_ids']))
				$transaction_ids = $_POST['transaction_ids'];		
			
			if(!empty($_POST['cheak']))
			{
				foreach($_POST['cheak'] as $key=>$val)
				{			 
					$total = $total + $total_amount[$key];	
					$trasationdata['type_value'] =$amount[$key];							
					$trasationdata['type_discount'] =$discount_amount[$key];							
					$trasationdata['type_tax'] =$tax_amount[$key];							
					$trasationdata['type_total_value'] =$total_amount[$key];
					
					$whereid['id']= $transaction_ids[$key];
					$wpdb->update($table_hmgt_patient_transation,$trasationdata,$whereid);	
					
					if(isset($transaction_ids[$key]))
						$transationdata[$key]=$transaction_ids[$key];				
				} 
			}		
			if(!empty($_POST['newentry']))
			{	
				$new_entry_id=array();
				
				foreach($_POST['newentry'] as $key=>$val)
				{	
					$total = $total + $total_amount[$key];
					$trasationdata['type'] =$_POST['type'][$key]; 
					$trasationdata['type_id'] = $_POST['title'][$key];  
					$trasationdata['status'] ="Unpaid"; 
					$trasationdata['patient_id'] =$_POST['patient_id']; 
					$trasationdata['date'] =date("Y-m-d"); 
					$trasationdata['unit'] = MJ_hmgt_strip_tags_and_stripslashes($_POST['unit'][$key]);  
					$trasationdata['type_value'] =$amount[$key];	
					$trasationdata['type_discount'] =$discount_amount[$key];					
					$trasationdata['type_tax'] =$tax_amount[$key];							
					$trasationdata['type_total_value'] =$total_amount[$key];
					$wpdb->insert( $table_hmgt_patient_transation, $trasationdata );
					
					$lastid = $wpdb->insert_id;	
					$new_entry_id[]=$lastid;	
				}
			}
		} 
	
	$invoice_id=0;
	if(isset($_REQUEST['invoice_id']))
		$invoice_id=MJ_hmgt_id_decrypt($_REQUEST['invoice_id']);
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{					
		$edit=1;
		$result = $obj_invoice->hmgt_get_invoice_data($invoice_id);
	}
	?>
	<!-- START POPUP CODE-->
	<div class="popup-bg" style="background: rgba(0,0,0,0);">
		<div class="overlay-content invoice_entry_popup">   
			<div class="patient_invoice"></div>     
		</div>     
	</div>	
	<!-- END POPUP CODE -->
    <div class="panel-body"><!-- START PANEL BODY DIV -->
        <form name="invoice_form" action="" method="post" class="form-horizontal" id="invoice_form"><!-- START INVOICE FORM -->
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="invoice_id" value="<?php echo $invoice_id;?>">
		
		<?php if(!empty($transationdata)){ 
			foreach($transationdata as $key=>$value){ ?>
			<input type="hidden" name="transationdata[<?php echo $key ?>]" value="<?php echo $value; ?>">
		<?php } 
		} ?>
		
		<?php if(!empty($new_entry_id)){ 
			$auto_value=1;
			foreach($new_entry_id as $value)
			{			
				$auto_value++;
			?>
			<input type="hidden" name="transationdata[<?php echo $auto_value; ?>]" value="<?php echo $value; ?>">
		<?php } 
		} ?>
	
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="invoice_number"><?php _e('Invoice ID','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="invoice_number" class="form-control validate[required] text-input" type="text" readonly value="<?php if($edit){ echo $result->invoice_number;} else echo $obj_invoice->generate_invoce_number();?>" name="invoice_number" readonly>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8 margin_bottom_5px">				
				<?php if($edit){ $patient_id1=$result->patient_id; }elseif(isset($_REQUEST['patient'])){$patient_id1=$_REQUEST['patient'];}else{ $patient_id1="";}?>
				<select name="patient" class="form-control validate[required]" id="patient_id">
				<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
				<?php 
					
					$patients = MJ_hmgt_patientid_list();					
					if(!empty($patients))
					{
					foreach($patients as $patient)
					{
						echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['patient_id'].' - '.$patient['first_name'].' '.$patient['last_name'].'</option>';
					}
					}?>
				</select>
			</div>
				<div class="col-sm-1">					
					<a href="#" class="show-inovice btn btn-default"><i class="fa fa-eye"></i> <?php _e('Check Charges','hospital_mgt');?></a>					
				</div>			
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="invice_title"><?php _e('Invoice Title','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="invice_title" class="form-control validate[custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $result->invoice_title;}elseif(isset($_POST['invice_title'])) echo $_POST['invice_title'];?>" name="invice_title">
			</div>
		</div>		
		<div class=""></div>		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="vat_percentage"><?php _e('Subtotal Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="invoice_amount" class="form-control validate[required] text-input" min="0" type="number" onKeyPress="if(this.value.length==12) return false;" step="0.01" value="<?php if($edit){ echo $result->invoice_amount + $total;}elseif(isset($_POST['invoice_amount'])) echo $_POST['invoice_amount']; elseif(isset($total)) echo $total;   ?>" name="invoice_amount" readonly>
			</div>
		</div>		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="adjustment_amount"><?php _e('Adjustment Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
			<div class="col-sm-8">
				<input id="adjustment_amount" class="form-control text-input" min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01" value="<?php if($edit){ echo $result->adjustment_amount;}elseif(isset($_POST['adjustment_amount'])) echo $_POST['adjustment_amount'];?>" name="adjustment_amount">
			</div>
		</div>		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="invoice_date" class="form-control validate[required] " type="text"   value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->invoice_create_date));}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date(MJ_hmgt_date_formate());}?>" name="invoice_date">
			</div>
		</div>		
		<div class="form-group margin_bottom_5px">
			<label class="col-sm-2 control-label" for="comments"><?php _e('Comments','hospital_mgt');?></label>
			<div class="col-sm-8">
				<textarea id="comments" class="form-control validate[custom[address_description_validation]]" maxlength="150" name="comments"><?php if($edit){echo $result->comments; }elseif(isset($_POST['comments'])) echo $_POST['comments']; ?></textarea>
			</div>
		</div>	
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Create Invoice Entry','hospital_mgt');}?>" name="save_invoice" class="btn btn-success"/>
        </div>
        </form><!-- EMD Invoice FORM -->
        </div><!-- END PANEL BODY DIV -->
    <script>  
   	// CREATING BLANK INVOICE ENTRY
   	var blank_invoice_entry ='';
   	$(document).ready(function() { 
   		blank_invoice_entry = $('#invoice_entry').html();
   		
   	}); 
   	function add_entry(){
   		$("#invoice_entry").append(blank_invoice_entry);   	
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n){
		alert("<?php _e('Do you really want to delete this record','hospital_mgt');?>");
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}
   </script> 
	<?php 	
	}		
	
	
	}  // end of add invoice
	if($active_tab == 'incomelist')
	 {
        	$invoice_id=0;
			if(isset($_REQUEST['income_id']))
				$invoice_id=MJ_hmgt_id_decrypt($_REQUEST['income_id']);
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$result = $obj_invoice->hmgt_get_invoice_data($invoice_id);
				}?>
		  <script type="text/javascript">
			jQuery(document).ready(function($)
			{
				jQuery('#tblincome').DataTable({
					"responsive": true,
					 "order": [[ 3, "Desc" ]],
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
			});
		</script>
     <div class="panel-body"><!-- START PANEL BODY DIV -->
        	<div class="table-responsive"><!-- START TABLE REPONSIVE DIV -->
				<table id="tblincome" class="display dataTable" cellspacing="0" width="100%"><!-- START Income LIST TABLE-->
					 <thead>
					<tr>
						<th><?php _e( 'Invoice ID', 'hospital_mgt' ) ;?></th>
						<th> <?php _e( 'Patient Id', 'hospital_mgt' ) ;?></th>
						<th> <?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
						<th> <?php _e( 'Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
						<th> <?php _e( 'Date', 'hospital_mgt' ) ;?></th>
						<th> <?php _e( 'Payment Method', 'hospital_mgt' ) ;?></th>
						<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
					</tr>
				</thead> 
				<tfoot>
					<tr>
						<th><?php _e( 'Invoice ID', 'hospital_mgt' ) ;?></th>
						<th> <?php _e( 'Patient Id', 'hospital_mgt' ) ;?></th>
						<th> <?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>
						<th> <?php _e( 'Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
						<th> <?php _e( 'Date', 'hospital_mgt' ) ;?></th>
						<th> <?php _e( 'Payment Method', 'hospital_mgt' ) ;?></th>
						<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
					</tr>
				</tfoot> 
				<tbody>
				 <?php
					if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'pharmacist' || $obj_hospital->role == 'accountant' || $obj_hospital->role == 'receptionist') 
					{
					   $own_data=$user_access['own_data'];
					   if($own_data == '1')
						{
							$incomedata=$obj_invoice->get_all_income_data_by_income_create_by();
						}
						else
						{
						  $incomedata=$obj_invoice->get_all_income_data();
						}
					}
					elseif($obj_hospital->role == 'doctor') 
					{
					   $own_data=$user_access['own_data'];
					   if($own_data == '1')
						{
							$incomedata=$obj_invoice->get_doctor_all_income_data_by_income_create_by();
						}
						else
						{
						  $incomedata=$obj_invoice->get_all_income_data();
						}
					}
					elseif($obj_hospital->role == 'nurse') 
					{
					   $own_data=$user_access['own_data'];
					   if($own_data == '1')
						{
							$incomedata=$obj_invoice->get_nurse_all_income_data_by_income_create_by();
						}
						else
						{
						  $incomedata=$obj_invoice->get_all_income_data();
						}
					}
					elseif($obj_hospital->role == 'patient')
					{	
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{
							$userid=get_current_user_id();	
							$incomedata=$obj_invoice->get_all_income_data_by_patient($userid);
						}
						else
						{
						  $incomedata=$obj_invoice->get_all_income_data();
						}
					}
					
					foreach ($incomedata as $retrieved_data)
					{ 
						$all_entry=json_decode($retrieved_data->income_entry);
						$total_amount=0;
						foreach($all_entry as $entry)
						{
							$total_amount+=$entry->amount;
						}
						$obj_invoice= new MJ_hmgt_invoice();
						if(!empty($retrieved_data->invoice_id))
						{
							$invoice_data=$obj_invoice->hmgt_get_invoice_data($retrieved_data->invoice_id);
						}
					?>
					<tr>
						<td class=""><?php if(!empty($invoice_data)){ echo $invoice_data->invoice_number; } ?></td>
						<td class="patient"><?php echo $patient_id=get_user_meta($retrieved_data->party_name, 'patient_id', true);?></td>
						<td class="patient_name"><?php $user=get_userdata($retrieved_data->party_name);
							echo $user->display_name;?></td>
						<td class="income_amount"> <?php echo $total_amount;?></td>
						<td class="status"><?php echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->income_create_date));?></td>
						<td class=""><?php echo $retrieved_data->payment_method;?></td>
						<td class="action">
						<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->income_id; ?>" invoice_type="income">
						<i class="fa fa-eye"></i> <?php _e('View Income', 'hospital_mgt');?></a>
						<?php
						if($user_access['edit']=='1')
						{
						?>
							<a href="?dashboard=user&page=invoice&tab=addincome&action=edit&income_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->income_id);?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
						<?php
						}
						if($user_access['delete']=='1')
						{
						?>
							<a href="?dashboard=user&page=invoice&tab=incomelist&action=delete&income_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->income_id);?>" class="btn btn-danger" 
							onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
							<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
						<?php
						}
						?>		
						</td>
					</tr>
					<?php 
					}			
					?>     
				</tbody>        
				</table><!-- END INCOME LIST TABLE -->
			</div><!-- EMD TABLE REPONSIVE DIV -->
        </div><!-- END PANEL BODY DIV -->
	 <?php  } // end of income list
	if($active_tab == 'addincome')
	{
        	$income_id=0;
			if(isset($_REQUEST['income_id']))
			$income_id=MJ_hmgt_id_decrypt($_REQUEST['income_id']);
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{					
				$edit=1;
				$result = $obj_invoice->hmgt_get_income_data($income_id);
							
			}
			if(isset($_REQUEST['invoice_id']))
			{	
			?>
				<style>
				.add_more_entry_div,.payment_status_div
				{
					display:none;	
				}	
				</style>	
			<?php
			}			
			?>		
       <div class="panel-body"><!-- START PANEL BODY DIV -->
        <form name="income_form" action="" method="post" class="form-horizontal" id="income_form"><!-- START INCOME FORM -->
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="income_id" value="<?php echo $income_id;?>">
		<input type="hidden" name="invoice_type" value="income">		
		<input type="hidden" name="invoice_id" value="<?php if(isset($_REQUEST['invoice_id']))
			{ echo $_REQUEST['invoice_id']; } ?>">			
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				
				<?php
				if($edit)
				{ 
					$patient_id1=$result->party_name; 
				}
				elseif(isset($_REQUEST['patient_id']))
				{
					$patient_id1 = $_REQUEST['patient_id'];
				}
				elseif(isset($_POST['patient']))
				{
					$patient_id1=$_POST['patient'];
				}
				else
				{
					$patient_id1="";
				}
				?>
				<select name="party_name" class="form-control validate[required]">
				<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
				<?php 					
					$patients = MJ_hmgt_patientid_list();
					
					if(!empty($patients))
					{
						foreach($patients as $patient)
						{
							echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['patient_id'].' - '.$patient['first_name'].' '.$patient['last_name'].'</option>';
						}
					}
					?>
				</select>
			</div>
		</div>
		<div class="form-group payment_status_div">
			<label class="col-sm-2 control-label" for="payment_status"><?php _e('Status','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="payment_status" id="payment_status" class="form-control validate[required]">
			      <option value="<?php _e('Paid','hospital_mgt'); ?>"
						<?php if($edit)selected( __('Paid','hospital_mgt'),$result->payment_status);?> ><?php _e('Paid','hospital_mgt');?></option>
					<option value="<?php _e('Part Paid','hospital_mgt'); ?>"
						<?php if($edit)selected( __('Part Paid','hospital_mgt'),$result->payment_status);?>><?php _e('Part Paid','hospital_mgt');?></option>
						<option value="<?php _e('Unpaid','hospital_mgt'); ?>"
						<?php if($edit)selected( __('Unpaid','hospital_mgt'),$result->payment_status);?>><?php _e('Unpaid','hospital_mgt');?></option>
				</select>
			</div>
		</div>
		<div class="form-group">			
				<label class="col-sm-2 control-label" for="payment_method"><?php _e('Payment Method','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">			
					<select name="payment_method" id="payment_method" class="form-control">					
						<option value="<?php _e('Cash','hospital_mgt');?>" <?php if($edit)selected( __('Cash','hospital_mgt'),$result->payment_method);?>><?php _e('Cash','hospital_mgt');?></option>
						<option value="<?php _e('Cheque','hospital_mgt');?>" <?php if($edit)selected( __('Cheque','hospital_mgt'),$result->payment_method);?>><?php _e('Cheque','hospital_mgt');?></option>
						<option value="<?php _e('Bank Transfer','hospital_mgt');?>" <?php if($edit)selected( __('Bank Transfer','hospital_mgt'),$result->payment_method);?>><?php _e('Bank Transfer','hospital_mgt');?></option>
						<option value="<?php _e('Credit Card Or Debit Card','hospital_mgt');?>" <?php if($edit)selected( __('Credit Card Or Debit Card','hospital_mgt'),$result->payment_method);?>><?php _e('Credit Card Or Debit Card','hospital_mgt');?></option>							
					</select>
				</div>
			</div>
			<div class="form-group">			
				<label class="col-sm-2 control-label" for=""><?php _e('Payment Details','hospital_mgt');?></label>
				<div class="col-sm-8">			
					<textarea name="payment_description" class="form-control validate[custom[address_description_validation]]" maxlength="150" id="notice_content"><?php if($edit){ echo $result->payment_description;}?></textarea>					
				</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="invoice_date" class="form-control validate[required]" type="text"   value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->income_create_date));}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date(MJ_hmgt_date_formate());}?>" name="invoice_date">
			</div>
		</div>
		<hr>		
		<?php			
			if($edit)
			{
				$all_entry=json_decode($result->income_entry);
			}
			else
			{
				if(isset($_POST['income_entry']))
				{					
					$all_data=$obj_invoice->get_entry_records($_POST);
					$all_entry=json_decode($all_data);
				}	
			}
			if(!empty($all_entry))
			{
					foreach($all_entry as $entry)
					{
					?>
					<div id="income_entry">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Income Entry','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2 margin_bottom_5px">
							<input id="income_amount" class="form-control validate[required] text-input"  min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01" value="<?php echo $entry->amount;?>" name="income_amount[]">
						</div>
						<div class="col-sm-4 margin_bottom_5px">
							<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php echo $entry->entry;?>" name="income_entry[]">
						</div>						
						<div class="col-sm-2">
						<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
						</button>
						</div>
						</div>	
					</div>
					<?php }
				
			}
			else
			{
			?>
					<div id="income_entry">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Income Entry','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2 margin_bottom_5px">
							<input id="income_amount" class="form-control validate[required] text-input"  min="0"type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01"  value="<?php if(isset($_REQUEST['invoice_id'])){ echo $_REQUEST['due_amount']; } ?>" <?php if(isset($_REQUEST['invoice_id'])){ ?> max="<?php echo $_REQUEST['due_amount']; ?>" <?php } ?> name="income_amount[]" placeholder="<?php _e('Income Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)">
						</div>
						<div class="col-sm-4">
							<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if(isset($_REQUEST['invoice_id'])){	echo 'Invoice Income'; } ?>" name="income_entry[]" placeholder="<?php _e('Income Entry Label','hospital_mgt');?>">
						</div>
						<div class="col-sm-2">
						<!--<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
						</button>-->
						</div>
						</div>	
					</div>				
		<?php 
		}
		?>
		<div class="form-group add_more_entry_div">
			<label class="col-sm-2 control-label" for="income_entry"></label>
			<div class="col-sm-3">
				
				<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add Income Entry','hospital_mgt'); ?>
				</button>
			</div>
		</div>
		<hr>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Create Income Entry','hospital_mgt');}?>" name="save_income" class="btn btn-success"/>
        </div>
        </form><!-- END INCOME FORM  -->
        </div><!-- END PANEL BODY DIV -->
       <script>

   	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	jQuery(document).ready(function() { 
   		blank_income_entry = $('#income_entry').html();
   	}); 

   	function add_entry()
   	{
   		
   		$("#income_entry").append('<div class="form-group"><label class="col-sm-2 control-label" for="income_entry"><?php _e('Income Entry','hospital_mgt');?><span class="require-field">*</span></label><div class="col-sm-2 margin_bottom_5px"><input id="income_amount" class="form-control validate[required] text-input"  min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01" value="" name="income_amount[]" placeholder="<?php _e('Income Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)"></div><div class="col-sm-4 margin_bottom_5px"><input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="income_entry[]" placeholder="<?php _e('Income Entry Label','hospital_mgt');?>"></div><div class="col-sm-2"><button type="button" class="btn btn-default" onclick="deleteParentElement(this)"><i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i></button></div></div>');
   		//alert("hellooo");
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n){
		alert("<?php _e('Do you really want to delete this record','hospital_mgt');?>");
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}
       </script> 
     <?php 
	 } 
	 if($active_tab == 'addexpense')
	 {
        	$expense_id=0;
			if(isset($_REQUEST['expense_id']))
				$expense_id=MJ_hmgt_id_decrypt($_REQUEST['expense_id']);
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					$edit=1;
					$result = $obj_invoice->hmgt_get_income_data($expense_id);
					
				}?>
		
       <div class="panel-body"><!-- START PANEL BODY DIV -->
        <form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form"><!-- START EXPENSE FORM-->
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="expense_id" value="<?php echo $expense_id;?>">
		<input type="hidden" name="invoice_type" value="expense">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient"><?php _e('Supplier Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="party_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="30" type="text" value="<?php if($edit){ echo $result->party_name;}elseif(isset($_POST['party_name'])) echo $_POST['party_name'];?>" name="party_name">
			</div>
		</div>		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="payment_status"><?php _e('Status','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="payment_status" id="payment_status" class="form-control validate[required]">
					
						
					<option value="<?php _e('Paid','hospital_mgt'); ?>"
					<?php if($edit)selected( __('Paid','hospital_mgt'),$result->payment_status);?> ><?php _e('Paid','hospital_mgt');?></option>
		           <option value="<?php _e('Part Paid','hospital_mgt'); ?>"
						<?php if($edit)selected( __('Part Paid','hospital_mgt'),$result->payment_status);?>><?php _e('Part Paid','hospital_mgt');?></option>
		            <option value="<?php _e('Unpaid','hospital_mgt'); ?>"
						<?php if($edit)selected( __('Unpaid','hospital_mgt'),$result->payment_status);?>><?php _e('Unpaid','hospital_mgt');?></option>
			</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="invoice_date"><?php _e('Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="invoice_date" class="form-control validate[required]" type="text"  value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->income_create_date));}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date(MJ_hmgt_date_formate());}?>" name="invoice_date">
			</div>
		</div>
		<hr>
		
		<?php 
			
			if($edit){
				$all_entry=json_decode($result->income_entry);
			}
			else
			{
				if(isset($_POST['income_entry'])){
					
					$all_data=$obj_invoice->get_entry_records($_POST);
					$all_entry=json_decode($all_data);
				}
				
					
			}
			if(!empty($all_entry))
			{
					foreach($all_entry as $entry){
					?>
					<div id="expense_entry">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Expense Entry','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2 margin_bottom_5px">
							<input id="income_amount" class="form-control validate[required] text-input"  min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01" value="<?php echo $entry->amount;?>" name="income_amount[]">
						</div>
						<div class="col-sm-4 margin_bottom_5px">
							<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php echo $entry->entry;?>" name="income_entry[]">
						</div>
						
						<div class="col-sm-2">
						<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
						</button>
						</div>
						</div>	
					</div>
					<?php }
				
			}
			else
			{?>
					<div id="expense_entry">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Expense Entry','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2 margin_bottom_5px">
							<input id="income_amount" class="form-control validate[required] text-input"  min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01" value="" name="income_amount[]" placeholder="<?php _e('Expense Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)">
						</div>
						<div class="col-sm-4">
							<input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="income_entry[]" placeholder="<?php _e('Expense Entry Label','hospital_mgt');?>">
						</div>
						<div class="col-sm-2">
						<!--<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
						</button>-->
						</div>
						</div>	
					</div>
					
		<?php }?>
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="expense_entry"></label>
			<div class="col-sm-3">
				
				<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php _e('Add Expense Entry','hospital_mgt'); ?>
				</button>
			</div>
		</div>
		<hr>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Create Expense Entry','hospital_mgt');}?>" name="save_expense" class="btn btn-success"/>
        </div>
        </form><!-- END EXPENSE FORM -->
        </div><!-- END PANEL BODY DIV -->
       <script>

   
   
   	
  
   	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	
   	function add_entry()
   	{
		$("#expense_entry").append('<div class="form-group"><label class="col-sm-2 control-label" for="income_entry"><?php _e('Expense Entry','hospital_mgt');?><span class="require-field">*</span></label><div class="col-sm-2 margin_bottom_5px"><input id="income_amount" class="form-control validate[required] text-input"  min="0" type="number" onKeyPress="if(this.value.length==10) return false;" step="0.01" value="" name="income_amount[]" placeholder="<?php _e('Expense Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)"></div><div class="col-sm-4 margin_bottom_5px"><input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="income_entry[]" placeholder="<?php _e('Expense Entry Label','hospital_mgt');?>"></div><div class="col-sm-2"><button type="button" class="btn btn-default" onclick="deleteParentElement(this)"><i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i></button></div></div>');
	
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n){
		alert("<?php _e('Do you really want to delete this record','hospital_mgt');?>");
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}
       </script> 
     <?php 
	 }
	  if($active_tab == 'expenselist')
	 {
        	$invoice_id=0;
			if(isset($_REQUEST['income_id']))
				$invoice_id=MJ_hmgt_id_decrypt($_REQUEST['income_id']);
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$result = $obj_invoice->hmgt_get_invoice_data($invoice_id);
				}?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#tblexpence').DataTable({
				"responsive": true,
				 "order": [[ 2, "Desc" ]],
				 "aoColumns":[
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": false}
						   ],
				language:<?php echo MJ_hmgt_datatable_multi_language();?>		   
				});
				
			
		} );
		</script>
     <div class="panel-body"><!-- START PANEL BODY DIV -->
        	<div class="table-responsive"><!-- START TABLE REPONSIVE DIV -->
				<table id="tblexpence" class="display dataTable" cellspacing="0" width="100%"><!-- START EXPENSE LIST TABLE-->
					 <thead>
					<tr>
						<th> <?php _e( 'Supplier Name', 'hospital_mgt' ) ;?></th>
						<th> <?php _e( 'Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
						<th> <?php _e( 'Date', 'hospital_mgt' ) ;?></th>
						<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
					</tr>
				</thead>
		 
				<tfoot>
					<tr>
						<th> <?php _e( 'Supplier Name', 'hospital_mgt' ) ;?></th>
						<th> <?php _e( 'Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
						<th> <?php _e( 'Date', 'hospital_mgt' ) ;?></th>
						<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
					</tr>
				</tfoot>
		 
				<tbody>
				 <?php 
					if($obj_hospital->role == 'doctor' || $obj_hospital->role == 'nurse' || $obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'pharmacist' || $obj_hospital->role == 'accountant' || $obj_hospital->role == 'receptionist') 
					{
					   $own_data=$user_access['own_data'];
					   if($own_data == '1')
						{
							$expensedata=$obj_invoice->get_all_expense_data_by_income_create_by();
						}
						else
						{
							$expensedata=$obj_invoice->get_all_expense_data();
						}
					}
					else
					{
						 $expensedata=$obj_invoice->get_all_expense_data();
					}
					
					foreach ($expensedata as $retrieved_data)
					{ 
						$all_entry=json_decode($retrieved_data->income_entry);
						$total_amount=0;
						foreach($all_entry as $entry){
							$total_amount+=$entry->amount;
						}
				 ?>
					<tr>
						
						<td class="patient_name"><?php echo $retrieved_data->party_name;?></td>
						<td class="income_amount"><?php echo $total_amount;?></td>
						<td class="status"><?php echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->income_create_date));?></td>
						
						<td class="action">
						<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->income_id; ?>" invoice_type="expense">
						<i class="fa fa-eye"></i> <?php _e('View Expense', 'hospital_mgt');?></a>
						<?php
						if($user_access['edit']=='1')
						{
						?>
							<a href="?dashboard=user&page=invoice&tab=addexpense&action=edit&expense_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->income_id);?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
						<?php
						}
						if($user_access['delete']=='1')
						{
						?>	
							<a href="?dashboard=user&page=invoice&tab=expenselist&action=delete&expense_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->income_id);?>" class="btn btn-danger" 
							onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
							<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
						<?php
						}
						?>	
						</td>
					</tr>
					<?php } 
					
				?>
			 
				</tbody>
				
				</table<!-- END Expense LIST TABLE-->
			</div><!-- END TABLE RESPONSIVE DIV -->
        </div><!-- END PANEL BODY DIV -->
	 <?php 
	 }
	 ?>
	</div<!-- END PANEL BODY DIV -->
</div><!-- END TAB CONTENT DIV -->