<?php 
$obj_medicine = new MJ_hmgt_medicine();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'medicinelist';
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"></div>
        </div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important"><!-- page inner div start -->
    <div class="page-title"><!-- page title div start -->
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div><!-- page title div end -->
	<?php 
	if(isset($_REQUEST['save_category']))
	{
		$result = $obj_medicine->hmgt_add_medicinecategory($_POST);
		if($result)
		{
			?>
				<div id="message" class="updated below-h2">
				<?php 
					_e('Medicine Category Inserted Successfully.','hospital_mgt');
				?></div><?php 				
		}
		
	}
	if(isset($_REQUEST['save_medicine']))
	{		
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit')){			
			$result = $obj_medicine->hmgt_add_medicine($_POST);
			if($result)
			{
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url().'admin.php?page=hmgt_medicine&tab=medicinelist&message=2'); }
				else 
				{
					wp_redirect ( admin_url().'admin.php?page=hmgt_medicine&tab=medicinelist&message=1');
				}
			}
		}
	}
	
	//save dispatch medicine...
	if(isset($_REQUEST['save_dispatch_medicine']))
	{		
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{			
			$result = $obj_medicine->hmgt_add_dispatch_medicine($_POST);
			if($result){
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( admin_url().'admin.php?page=hmgt_medicine&tab=dispatchlist&message=2'); }
				else 
				{
					wp_redirect ( admin_url().'admin.php?page=hmgt_medicine&tab=dispatchlist&message=1');
				}
			}
		}
	}
	//export medicine in csv
	if(isset($_POST['export_csv']))
	{	
		global $wpdb;
		$table_hmgt_medicine = $wpdb->prefix. 'hmgt_medicine';
	
		$medicine_list = $wpdb->get_results("SELECT * FROM $table_hmgt_medicine ORDER BY medicine_id DESC");
		
		if(!empty($medicine_list))
		{
			$header = array();	
			$header[] = 'med_uniqueid';			
			$header[] = 'medicine_name';
			$header[] = 'medicine_description';	
			$header[] = 'med_cat_id';
			$header[] = 'medicine_price';
			$header[] = 'med_quantity';			
			$header[] = 'med_tax';			
			$header[] = 'med_discount';			
			$header[] = 'medicine_menufacture';				
			$header[] = 'manufactured_date';
			$header[] = 'medicine_expiry_date';
			$header[] = 'batch_number';
			$header[] = 'note';	
			$header[] = 'medicine_stock';
			$header[] = 'med_create_date';
			$header[] = 'med_create_by';			
			$header[] = 'med_discount_in';			
			
			$document_dir = WP_CONTENT_DIR;
			$document_dir .= '/uploads/export/';
			$document_path = $document_dir;
			if (!file_exists($document_path))
			{
				mkdir($document_path, 0777, true);		
			}
	
			$filename=$document_path.'export_medicine.csv';
			$fh = fopen($filename, 'w') or die("can't open file");
			fputcsv($fh, $header);
			
			foreach($medicine_list as $retrive_data)
			{
				$row = array();	
				
				$row[] =  $retrive_data->med_uniqueid;
				$row[] =  $retrive_data->medicine_name;
				$row[] =  $retrive_data->medicine_description;
				$row[] =  get_the_title($retrive_data->med_cat_id);
				$row[] =  $retrive_data->medicine_price;
				$row[] =  $retrive_data->med_quantity;
				$row[] =  $retrive_data->med_tax;
				$row[] =  $retrive_data->med_discount;
				$row[] =  $retrive_data->medicine_menufacture;
				$row[] =  $retrive_data->manufactured_date;
				$row[] =  $retrive_data->medicine_expiry_date;
				$row[] =  $retrive_data->batch_number;
				$row[] =  $retrive_data->note;
				$row[] =  $retrive_data->medicine_stock;			
				$row[] =  $retrive_data->med_create_date;
				$row[] =  $retrive_data->med_discount_in;
				$userdata=get_userdata($retrive_data->med_create_by);				
				$row[] = $userdata->display_name;			
				
				fputcsv($fh, $row);
			}
			fclose($fh);
	
			//download csv file.
			ob_clean();
			$file=$document_path.'export_medicine.csv';//file location
			
			$mime = 'text/plain';
			header('Content-Type:application/force-download');
			header('Pragma: public');       // required
			header('Expires: 0');           // no cache
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
			header('Cache-Control: private',false);
			header('Content-Type: '.$mime);
			header('Content-Disposition: attachment; filename="'.basename($file).'"');
			header('Content-Transfer-Encoding: binary');			
			header('Connection: close');
			readfile($file);		
			exit;			
		}
		else
		{
		?>
			<div class="alert_msg alert alert-danger alert-dismissible fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<?php _e('Records not found.','hospital_mgt');?>
			</div>
		<?php	
		}		
	}
	//upload medicine csv	
	if(isset($_REQUEST['upload_csv_file']))
	{		
		if(isset($_FILES['csv_file']))
		{				
			$errors= array();
			$file_name = $_FILES['csv_file']['name'];
			$file_size =$_FILES['csv_file']['size'];
			$file_tmp =$_FILES['csv_file']['tmp_name'];
			$file_type=$_FILES['csv_file']['type'];			
			$value = explode(".", $_FILES['csv_file']['name']);
			$file_ext = strtolower(array_pop($value));
			$extensions = array("csv");
			$upload_dir = wp_upload_dir();
			if(in_array($file_ext,$extensions )=== false)
			{
				$errors[]="this file not allowed, please choose a CSV file.";
				wp_redirect ( admin_url().'admin.php?page=hmgt_medicine&tab=medicinelist&message=4');
			}
			if($file_size > 2097152)
			{
				$errors[]='File size limit 2 MB';
				wp_redirect ( admin_url().'admin.php?page=hmgt_medicine&tab=medicinelist&message=4');
			}			
			if(empty($errors)==true)
			{	
				$rows = array_map('str_getcsv', file($file_tmp));		
					
				$header = array_map('strtolower',array_shift($rows));
				
				$csv = array();
				foreach ($rows as $row) 
				{
					$csv = array_combine($header, $row);
					
					if(isset($csv['med_uniqueid']))
						$medicinedata['med_uniqueid']=$csv['med_uniqueid'];
					if(isset($csv['medicine_name']))
						$medicinedata['medicine_name']=$csv['medicine_name'];
					if(isset($csv['medicine_description']))
						$medicinedata['medicine_description']=$csv['medicine_description'];
					$category = get_page_by_title( $csv['med_cat_id'], OBJECT, 'medicine_category' );
					
					if(isset($csv['med_cat_id']))
						$medicinedata['med_cat_id']=$category->ID;
					if(isset($csv['medicine_price']))
						$medicinedata['medicine_price']=$csv['medicine_price'];
					if(isset($csv['med_quantity']))
						$medicinedata['med_quantity']=$csv['med_quantity'];
					if(isset($csv['med_tax']))
						$medicinedata['med_tax']=$csv['med_tax'];							
					if(isset($csv['med_discount']))
						$medicinedata['med_discount']=$csv['med_discount'];							
					if(isset($csv['medicine_menufacture']))
						$medicinedata['medicine_menufacture']=$csv['medicine_menufacture'];
					if(isset($csv['manufactured_date']))
						$medicinedata['manufactured_date']=$csv['manufactured_date'];
					if(isset($csv['medicine_expiry_date']))
						$medicinedata['medicine_expiry_date']=$csv['medicine_expiry_date'];
					if(isset($csv['batch_number']))
						$medicinedata['batch_number']=$csv['batch_number'];
					if(isset($csv['note']))
						$medicinedata['note']=$csv['note'];
					if(isset($csv['medicine_stock']))
						$medicinedata['medicine_stock']=$csv['medicine_stock'];
					if(isset($csv['med_create_date']))
						$medicinedata['med_create_date']=$csv['med_create_date'];	
					if(isset($csv['med_create_by']))
						$medicinedata['med_create_by']=MJ_hmgt_get_user_id_by_display_name($csv['med_create_by'] ) ;	 
					if(isset($csv['med_discount_in']))
						$medicinedata['med_discount_in']=$csv['med_discount_in'];	
					
					global $wpdb;
					$table_hmgt_medicine = $wpdb->prefix. 'hmgt_medicine';
					
					$all_medicine = $wpdb->get_results("SELECT * FROM $table_hmgt_medicine");	
					
					$medicine_name=array();
					$med_uniqueid=array();
					
					foreach ($all_medicine as $medicine_data) 
					{
						$medicine_name[]=$medicine_data->medicine_name;
						$med_uniqueid[]=$medicine_data->med_uniqueid;
					}
					
					if (in_array($medicinedata['medicine_name'], $medicine_name) && in_array($medicinedata['med_uniqueid'], $med_uniqueid))					
					{
						$import_medicine_name=$medicinedata['medicine_name'];
						$import_med_uniqueid=$medicinedata['med_uniqueid'];
						
						$existing_medicine_data = $wpdb->get_row("SELECT medicine_id FROM $table_hmgt_medicine where medicine_name='$import_medicine_name' AND med_uniqueid='$import_med_uniqueid'");
						
						$id['medicine_id']=$existing_medicine_data->medicine_id;
												
						$wpdb->update( $table_hmgt_medicine, $medicinedata,$id);	
						
						$success = 1;	
					}
					else
					{						
						$wpdb->insert( $table_hmgt_medicine, $medicinedata );	
						
						$success = 1;	
					}	
				}
			}
			else
			{
				foreach($errors as &$error) echo $error;
			}
			if(isset($success))
			{			
				wp_redirect ( admin_url().'admin.php?page=hmgt_medicine&tab=medicinelist&message=4');
			} 
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		if(isset($_REQUEST['medicine_id']))
		{
			$result = $obj_medicine->delete_medicine($_REQUEST['medicine_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=hmgt_medicine&tab=medicinelist&message=3');
			}
		}
		else
		{
			$result = $obj_medicine->delete_dispatch_medicine($_REQUEST['dispatch_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=hmgt_medicine&tab=dispatchlist&message=3');
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
		elseif($message == 4) 
		{?>
			<div class="alert_msg alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
             <?php _e('Medicine Uploaded Successfully','hospital_mgt');?>
			</div>
		<?php				
		}			
	}
	?>
	<!--MAIN WRAPPER DIV START-->
	<div id="main-wrapper">
	<!--ROW DIV START-->
		<div class="row">
			<div class="col-md-12">
			<!--PANEL WHITE START-->
				<div class="panel panel-white">
				<!--PANEL BODY START-->
					<div class="panel-body">					
						<h2 class="nav-tab-wrapper">
							<a href="?page=hmgt_medicine&tab=medicinelist" class="nav-tab <?php echo $active_tab == 'medicinelist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Medicine List', 'hospital_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['medicine_id'])) 
							{?>
							<a href="?page=hmgt_medicine&tab=addmedicine&&action=edit&medicine_id=<?php  echo isset($_REQUEST['medicine_id']); ?>" class="nav-tab <?php echo $active_tab == 'addmedicine' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Medicine', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=hmgt_medicine&tab=addmedicine" class="nav-tab <?php echo $active_tab == 'addmedicine' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Add New Medicine', 'hospital_mgt'); ?></a>  
							<?php  
							}
							?>							
							<a href="?page=hmgt_medicine&tab=dispatchlist" class="nav-tab <?php echo $active_tab == 'dispatchlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Dispatched Medicine List', 'hospital_mgt'); ?></a>
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['dispatch_id']))
							{?>
							<a href="?page=hmgt_medicine&tab=dispatch-medicine&&action=edit&dispatch_id=<?php echo isset($_REQUEST['dispatch_id']);?>" class="nav-tab <?php echo $active_tab == 'dispatch-medicine' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Dispatch Medicine', 'hospital_mgt'); ?></a>  
							<?php 
							}
							else
							{
							?>
								<a href="?page=hmgt_medicine&tab=dispatch-medicine" class="nav-tab <?php echo $active_tab == 'dispatch-medicine' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.__('Dispatch Medicine', 'hospital_mgt'); ?></a>  
							<?php  
							} 
							?>
						</h2>
						 <?php 						
						if($active_tab == 'medicinelist')
						{ 
						
						?>	
						<script type="text/javascript">
					    jQuery(document).ready(function() {
						jQuery('#medicine_list').DataTable({
							"responsive": true,
							"aoColumns":[
										  {"bSortable": true},
										  {"bSortable": true},
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
					      } );
					    </script>
						<form name="medicine" action="" method="post">
							<div class="panel-body"><!--PANEL BODY DIV START-->							     	
								<input type="submit" value="<?php _e('Export CSV','hospital_mgt');?>" name="export_csv" class="btn btn-success margin_bottom_5px"/> 
								<input type="button" value="<?php _e('Import CSV','hospital_mgt');?>" name="import_csv" class="btn btn-success importdata margin_bottom_5px"/> 
								<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
									<table id="medicine_list" class="display" cellspacing="0" width="100%">
										 <thead>
											<tr>
											<th><?php _e( 'Medicine Name', 'hospital_mgt' ) ;?></th>
											 <th><?php _e( 'Category', 'hospital_mgt' ) ;?></th>
											 <th><?php _e( 'Batch Number', 'hospital_mgt' ) ;?></th>
											 <th><?php _e( 'Quantity', 'hospital_mgt' ) ;?></th>
											   <th><?php _e( 'Price', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th> 
												<th><?php _e( 'Discount', 'hospital_mgt' ) ;?></th> 
											   <th><?php _e( 'Tax', 'hospital_mgt' ) ;?></th> 
											   <th><?php _e( 'Expiry Date', 'hospital_mgt' ) ;?></th> 
												<th><?php _e( 'Stock', 'hospital_mgt' ) ;?></th> 
												<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
											</tr>
										 </thead>
										<tfoot>
											<tr>
											<th><?php _e( 'Medicine Name', 'hospital_mgt' ) ;?></th>
											 <th><?php _e( 'Category', 'hospital_mgt' ) ;?></th>
											 <th><?php _e( 'Batch Number', 'hospital_mgt' ) ;?></th>
											 <th><?php _e( 'Quantity', 'hospital_mgt' ) ;?></th>
											   <th><?php _e( 'Price', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th> 
												<th><?php _e( 'Discount', 'hospital_mgt' ) ;?></th> 
												<th><?php _e( 'Tax', 'hospital_mgt' ) ;?></th> 
												<th><?php _e( 'Expiry Date', 'hospital_mgt' ) ;?></th> 
												<th><?php _e( 'Stock', 'hospital_mgt' ) ;?></th> 
												<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
											</tr>
										</tfoot>
								 
										<tbody>
										 <?php 
										  $medicinedata=$obj_medicine->get_all_medicine();
										  if(!empty($medicinedata))
										   {
											foreach ($medicinedata as $retrieved_data){ 
										   ?>
										<tr>
											<td class="medicine_name"><?php	echo $retrieved_data->medicine_name;	?></td>
											<td class="category"><?php echo $obj_medicine->get_medicine_categoryname($retrieved_data->med_cat_id);?></td>
											<td class=""><?php	echo $retrieved_data->batch_number;?></td>
											<td class=""><?php	echo $retrieved_data->med_quantity;?></td>
										
											<td class="price"><?php  echo $retrieved_data->medicine_price; ?></td>
											<td class="price">
											<?php  
											if(!empty($retrieved_data->med_discount))
											{	
												echo $retrieved_data->med_discount; 
												if($retrieved_data->med_discount_in == 'percentage')
												{
													?>
													(%)
													<?php
												}	
												else
												{
													?>
													(<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)
													<?php 	
												}
											}
											else
											{
												echo '-';	
											}
											?>											
											</td>
											<td class="">
											<?php	
											if(!empty($retrieved_data->med_tax))
											{
												echo MJ_hmgt_tax_name_array_by_tax_id_array($retrieved_data->med_tax);
											}
											else
											{
												echo '-';	
											}
											?>
											</td>
											<td class="price"><?php  echo  date(MJ_hmgt_date_formate(),strtotime($retrieved_data->medicine_expiry_date));	?></td>
											<td class="medicine_qty"><?php echo $retrieved_data->medicine_stock;?></td>
											
											<td class="action"> <a href="?page=hmgt_medicine&tab=addmedicine&action=edit&medicine_id=<?php echo $retrieved_data->medicine_id;?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
											<a href="?page=hmgt_medicine&tab=medicinelist&action=delete&medicine_id=<?php echo $retrieved_data->medicine_id;?>" class="btn btn-danger" 
											onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
											<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
											
											</td>										   
										</tr>
										<?php } 
										 }?>
								 
										</tbody>
									
									</table>
								</div><!--TABLE RESPONSIVE DIV END-->
						    </div><!--PANEL BODY DIV END-->						   
					    </form>
						<?php 
						}						
						if($active_tab == 'addmedicine')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/medicine/add_medicine.php';
						}
						if($active_tab == 'dispatchlist')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/medicine/dispatchlist.php';
						}
						if($active_tab == 'dispatch-medicine')
						{
							require_once HMS_PLUGIN_DIR. '/admin/includes/medicine/dispatch-medicine.php';
						}
						 ?>
                    </div>			
		        </div>
				<!-- END PANEL BODY DIV-->
	        </div>
        </div><!--END ROW DIV -->
    </div>
	<!--END MAIN WRAPPER DIV-->

<?php //} ?>