<?php 
MJ_hmgt_browser_javascript_check();
$obj_ambulance = new MJ_Hmgt_ambulance();
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
//SAVE AMBULANCE DATA
if(isset($_REQUEST['save_ambulance']))
{
	if(isset($_FILES['upload_user_avatar_image']) && !empty($_FILES['upload_user_avatar_image']) && $_FILES['upload_user_avatar_image']['size'] !=0)
	{		
		if($_FILES['upload_user_avatar_image']['size'] > 0)
		{
		 $driver_image=MJ_hmgt_load_documets($_FILES['upload_user_avatar_image'],'upload_user_avatar_image','pimg');
		$driver_image_url=content_url().'/uploads/hospital_assets/'.$driver_image;
		}
		else 
		{
			$driver_image=$_REQUEST['hidden_upload_user_avatar_image'];
		$driver_image_url=$driver_image;
		}
			
	}
	else
	{		
		if(isset($_REQUEST['hidden_upload_user_avatar_image']))
			$driver_image=$_REQUEST['hidden_upload_user_avatar_image'];
		$driver_image_url=$driver_image;
	}
	
	
		if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
		{			
			$ext=MJ_hmgt_check_valid_extension($driver_image_url);
			
			if(!$ext == 0)
			{
				$result = $obj_ambulance->hmgt_add_ambulance($_POST);
				
				global $wpdb;
				$table_ambulance = $wpdb->prefix. 'hmgt_ambulance';

				if($_REQUEST['action']== 'edit')
				{
					$lastid =$_REQUEST['amb_id'];
				}
				else
				{
					$lastid =$wpdb->insert_id;
				}

				$amb_id['amb_id']=$lastid;	
				$ambulancedata['driver_image']=$driver_image_url;
					
				$result_image_update=$wpdb->update( $table_ambulance, $ambulancedata ,$amb_id);
					if($_REQUEST['action'] == 'edit')
					{
						wp_redirect ( home_url() . '?dashboard=user&page=ambulance&tab=ambulance_list&message=2');
					}
					else 
					{
						wp_redirect ( home_url() . '?dashboard=user&page=ambulance&tab=ambulance_list&message=1');
					}
			}
			else
			{
				if($_REQUEST['action'] == 'edit')
				{
					wp_redirect ( home_url() . '?dashboard=user&page=ambulance&tab=add_ambulance&action=edit&amb_id='.MJ_hmgt_id_encrypt($_REQUEST['amb_id']).'&message=4');
				}
				else 
				{
					?>
					<div id="message" class="updated below-h2"><p>
					<?php 
					 _e('Sorry, only JPG, JPEG, PNG & GIF files are allowed.','hospital_mgt');
					?></div></p><?php
				}				
				
		   }	
		}	
}
//SAVE Ambulance Request DATA
if(isset($_REQUEST['save_ambulance_request']))
{
	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{		
		$result = $obj_ambulance->hmgt_add_ambulance_request($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url() . '?dashboard=user&page=ambulance&tab=ambulance_req_list&message=2');
			}
			else 
			{
				wp_redirect ( home_url() . '?dashboard=user&page=ambulance&tab=ambulance_req_list&message=1');
			}
			
			
		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	if($_GET['tab'] == 'ambulance_req_list')
	{
		$result = $obj_ambulance->delete_ambulance_req(MJ_hmgt_id_decrypt($_REQUEST['amb_req_id']));
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=ambulance&tab=ambulance_req_list&message=3');
		}
	}
	else
	{
		$result = $obj_ambulance->delete_ambulance(MJ_hmgt_id_decrypt($_REQUEST['amb_id']));
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=ambulance&tab=ambulance_list&message=3');
		}
	}
}

$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] =='edit' && $_REQUEST['tab'] == 'add_ambulance_req'){
		
	$edit=1;
	$result= $obj_ambulance->get_single_ambulance_req(MJ_hmgt_id_decrypt($_REQUEST['amb_req_id']));
		
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
	elseif($message == 4) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
	 _e('Sorry, only JPG, JPEG, PNG & GIF files are allowed.','hospital_mgt');
	?></div></p><?php
			
	}
}	

$active_tab = isset($_GET['tab'])?$_GET['tab']:'ambulance_req_list';
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	$('.request_time').timepicki(
	{
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:15,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: true});
		
	$('.dispatch_time').timepicki({
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:15,
		overflow_minutes:true,
		increase_direction:'up',
		disable_keyboard_mobile: true});
			var date = new Date();
            date.setDate(date.getDate()-0);
	        $.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
            $('#request_date').datepicker({
	        startDate: date,
            autoclose: true
           }); 
		   $('#tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
} );
</script>
<div class="panel-body panel-white"><!-- START PANEL BODY DIV -->	
	<ul class="nav nav-tabs panel_tabs" role="tablist"><!-- START NAV TAB -->	
		  <li class="<?php if($active_tab == 'ambulance_req_list'){?>active<?php }?>">
			  <a href="?dashboard=user&page=ambulance&tab=ambulance_req_list">
				 <i class="fa fa-align-justify"></i> <?php _e('Request List', 'hospital_mgt'); ?></a>
			  </a>
		  </li>     
		   <li class="<?php if($active_tab=='add_ambulance_req'){?>active<?php }?>">
		  <?php			
				if(isset($_REQUEST['action']) && $_REQUEST['action'] =='edit' && $_REQUEST['tab'] == 'add_ambulance_req')
				{?>
					<a href="?dashboard=user&page=ambulance&tab=add_ambulance_req&&action=edit&amb_req_id=<?php echo $_REQUEST['amb_req_id'];?>" class="tab <?php echo $active_tab == 'add_ambulance_req' ? 'active' : ''; ?>">
					<i class="fa fa"></i> <?php _e('Edit Request', 'hospital_mgt'); ?></a>
				 <?php 
				}
				else
				{
					if($user_access['add']=='1')
					{			
					?>				
						<a href="?dashboard=user&page=ambulance&tab=add_ambulance_req&&action=insert" class="tab <?php echo $active_tab == 'add_ambulance_req' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php _e('Add Request', 'hospital_mgt'); ?></a>
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
			  <li class="<?php if($active_tab == 'ambulance_list'){?>active<?php }?>">
				  <a href="?dashboard=user&page=ambulance&tab=ambulance_list">
					 <i class="fa fa-align-justify"></i> <?php _e('Ambulance List', 'hospital_mgt'); ?></a>
				  </a>
			  </li> 
						  
			   <li class="<?php if($active_tab=='add_ambulance'){?>active<?php }?>">	 
			  <?php
					if(isset($_REQUEST['action']) && $_REQUEST['action'] =='edit' && $_REQUEST['tab'] == 'add_ambulance')
					{?>
						<a href="?dashboard=user&page=ambulance&tab=add_ambulance&&action=edit&amb_id=<?php echo $_REQUEST['amb_id'];?>" class="tab <?php echo $active_tab == 'add_ambulance' ? 'active' : ''; ?>">
						<i class="fa fa"></i> <?php _e('Edit Ambulance', 'hospital_mgt'); ?></a>
					 <?php 
					}
					else
					{
						if($user_access['add']=='1')
						{			
						?>				
							<a href="?dashboard=user&page=ambulance&tab=add_ambulance&&action=insert" class="tab <?php echo $active_tab == 'add_ambulance' ? 'active' : ''; ?>">
							<i class="fa fa-plus-circle"></i> <?php _e('Add New Ambulance', 'hospital_mgt'); ?></a>
						<?php
						}
					}
				
				?>	  
				</li>
			
			<?php
			}
			?>
	</ul><!-- END NAV TAB -->	
<?php
if($active_tab == 'ambulance_req_list')
{	
?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#ambulance_req_listlist1').DataTable({
			"responsive": true,
			 "order": [[ 2, "Desc" ]],
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
	<div class="tab-content"><!-- START TAB CONTENT DIV-->	
    	<div class="tab-pane fade active in" id="prescription"><!-- START TAB FADE DIV -->	
		    <div class="panel-body"><!-- START PANEL BODY DIV -->	
                <div class="table-responsive"><!-- START TABLE RESPONSIVE DIV -->	
					<table id="ambulance_req_listlist1" class="display dataTable" cellspacing="0" width="100%"><!-- START AMBULANCE Request LIST TABLE -->	
						<thead>
							<tr>
							<th><?php _e( 'Ambulance', 'hospital_mgt' ) ;?></th>
							   <th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>	
								<th><?php _e( 'Time', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Dispatch Time', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Charges', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php _e( 'Total Charges', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>	
								<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
							</tr>
					    </thead>
						<tfoot>
							<tr>
								<th><?php _e( 'Ambulance', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Date', 'hospital_mgt' ) ;?></th>	
								<th><?php _e( 'Time', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Dispatch Time', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Charges', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php _e( 'Total Charges', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
								<th><?php _e( 'Action', 'hospital_mgt' ) ;?></th>
							</tr>
						</tfoot>
			 
						<tbody>
						 <?php 
						if($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'pharmacist' || $obj_hospital->role == 'accountant' || $obj_hospital->role == 'receptionist') 
						{
						   $own_data=$user_access['own_data'];
						   if($own_data == '1')
							{
								$ambulancereq_data=$obj_ambulance->get_all_ambulance_request_by_amb_create_by();
							}
							else
							{
								$ambulancereq_data=$obj_ambulance->get_all_ambulance_request();
							}
						}
						elseif($obj_hospital->role == 'doctor') 
						{
						   $own_data=$user_access['own_data'];
						   if($own_data == '1')
							{
								$ambulancereq_data=$obj_ambulance->get_doctor_all_ambulance_request_by_amb_create_by();
							}
							else
							{
								$ambulancereq_data=$obj_ambulance->get_all_ambulance_request();
							}
						}
						elseif($obj_hospital->role == 'nurse') 
						{
						   $own_data=$user_access['own_data'];
						   if($own_data == '1')
							{
								$ambulancereq_data=$obj_ambulance->get_nurse_all_ambulance_request_by_amb_create_by();
							}
							else
							{
								$ambulancereq_data=$obj_ambulance->get_all_ambulance_request();
							}
						}
						elseif($obj_hospital->role == 'patient')
						{	
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$userid=get_current_user_id();	
								$ambulancereq_data=$obj_ambulance->get_all_ambulance_request_by_patient($userid);
							}
							else
							{
								$ambulancereq_data=$obj_ambulance->get_all_ambulance_request();
							}
							
						}						
								
						 if(!empty($ambulancereq_data))
						 {
							foreach ($ambulancereq_data as $retrieved_data)
							{ 
								$patient_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->patient_id);?>
							<tr>
								<td class="ambulanceid"><?php echo $obj_ambulance->get_ambulance_id($retrieved_data->ambulance_id);?></td>
								<td class="patient"><?php echo $patient_data['first_name']." ".$patient_data['last_name']."(".$patient_data['patient_id'].")";?></td>
								<td class="date"><?php echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->request_date));?></td>
								<td class=""><?php echo $retrieved_data->request_time;?></td>
								<td class="dispatchtime"><?php echo $retrieved_data->dispatch_time;?></td>
								<td class=""><?php echo number_format($retrieved_data->charge, 2, '.', ''); ?></td>
								<td class=""><?php echo number_format($retrieved_data->total_tax, 2, '.', ''); ?></td>
								<td class=""><?php echo number_format($retrieved_data->total_charge, 2, '.', ''); ?></td>
								<td class="action"> 
								<?php
								if($user_access['edit']=='1')
								{
								?>
									<a href="?dashboard=user&page=ambulance&tab=add_ambulance_req&action=edit&amb_req_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->amb_req_id);?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
								<?php
								}
								if($user_access['delete']=='1')
								{
								?>		
									<a href="?dashboard=user&page=ambulance&tab=ambulance_req_list&action=delete&amb_req_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->amb_req_id);?>" class="btn btn-danger" 
									onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
									<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>              
								<?php
								}
								?>	
							</tr>
							<?php } 
							
						}?>
						</tbody>
					</table><!-- END Ambulance Request LIST TABLE -->	
                </div><!-- END TABLE RESPONSIVE DIV -->	
            </div><!-- END PANEL BODY DIV -->	
		</div><!-- END TAB FADE DIV -->	
	</div><!-- END TAB CONTENT DIV -->	
<?php 
}
if($active_tab == 'add_ambulance_req')
{
?>
<div class="tab-content"><!-- START TAB CONTENT DIV -->		
	<div class="tab-pane fade active in" id="add_req"><!-- START TAB FADE DIV -->	
     <script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#patient_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
		
				var date = new Date();
				date.setDate(date.getDate()-0);
				$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
				$('#request_date').datepicker({
				startDate: date,
				autoclose: true
			   }); 
	} );
	</script>	
    <div class="panel-body"><!-- START PANEL BODY DIV-->	
        <form name="patient_form" action="" method="post" class="form-horizontal" id="patient_form"><!-- START AMBULAMCE Request FORM-->	
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="amb_req_id" value="<?php if(isset($_REQUEST['amb_req_id']))echo MJ_hmgt_id_decrypt($_REQUEST['amb_req_id']);?>"  />
		<div class="form-group">
			<label class="col-sm-2 control-label" for="ambulance_id"><?php _e('Ambulance','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="ambulance_id" class="form-control validate[required] " id="ambulance_id">
					<option value=""><?php _e('Select Ambulance','hospital_mgt');?></option>
					<?php 
					if($edit)
						$amb_id = $result->ambulance_id;
					elseif(isset($_REQUEST['ambulance_id']))
						$amb_id = $_REQUEST['ambulance_id'];
					else 	
						$amb_id = "";
						$ambulance_data=$obj_ambulance->get_all_ambulance();
					 	if(!empty($ambulance_data))
					 	{
					 		foreach ($ambulance_data as $retrieved_data)
					 		{ 
					 			echo '<option value = '.$retrieved_data->amb_id.' '.selected($amb_id,$retrieved_data->amb_id).'>'.$retrieved_data->ambulance_id.'</option>';
					 		}
					 	}						
					 ?>
				</select>
				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient','hospital_mgt');?></label>
			<div class="col-sm-8">
				<select name="patient_id" id="patient_id" class="form-control validate[required] patient_address">
					<option><?php _e('Select Patient','hospital_mgt');?></option>
					<?php 
					if($edit)
						$patient_id1 = $result->patient_id;
					elseif(isset($_REQUEST['patient_id']))
						$patient_id1 = $_REQUEST['patient_id'];
					else 
						$patient_id1 = "";
					$patients = MJ_hmgt_patientid_list();
					
					if(!empty($patients))
					{
						foreach($patients as $patient)
						{
							if($obj_hospital->role == 'patient')
							{
								$current_user_id=get_current_user_id();
								if($current_user_id == $patient['id'])
								{
									echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['patient_id'].' - '.$patient['first_name'].' '.$patient['last_name'].'</option>';
								}
							}
							else
							{
								echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['patient_id'].' - '.$patient['first_name'].' '.$patient['last_name'].'</option>';
							}
						}
					}
					?>
				</select>
				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="address"><?php _e('Address','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea name = "address" id="address" maxlength="150" class="form-control validate[required,custom[address_description_validation]]"><?php if($edit){ echo $result->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="charges"><?php _e('Charges','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="charges" class="form-control validate[required]" min="0" type="number" onKeyPress="if(this.value.length==8) return false;" step="0.01" value="<?php if($edit){ echo $result->charge;}elseif(isset($_POST['charge'])) echo $_POST['charge'];?>" name="charge">
			</div>
		</div>		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="visiting_fees"><?php _e('Tax','hospital_mgt');?></label>
			<div class="col-sm-2">
				<select  class="form-control" id="tax_charge" name="tax[]" multiple="multiple">					
					<?php					
					if($edit)
					{
						$tax_id=explode(',',$result->tax);
					}
					else
					{	
						$tax_id[]='';
					}
					$obj_invoice= new MJ_hmgt_invoice();
					$hmgt_taxs=$obj_invoice->get_all_tax_data();	
					
					if(!empty($hmgt_taxs))
					{
						foreach($hmgt_taxs as $entry)
						{
							$selected = "";
							if(in_array($entry->tax_id,$tax_id))
								$selected = "selected";
							?>
							<option value="<?php echo $entry->tax_id; ?>" <?php echo $selected; ?> ><?php echo $entry->tax_title;?>-<?php echo $entry->tax_value;?></option>
						<?php 
						}
					}
					?>
				</select>		
			</div>
		</div>		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="request_date"><?php _e('Request Date','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="request_date" class="form-control validate[required]" type="text"    value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->request_date));}elseif(isset($_POST['request_date'])) echo $_POST['request_date'];?>" name="request_date">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="request_time"><?php _e('Request Time','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="request_time" class="form-control request_time" type="text"  value="<?php if($edit){ echo $result->request_time;}elseif(isset($_POST['request_time'])) echo $_POST['request_time'];?>" name="request_time">
			</div>
		</div>
		<div class="form-group margin_bottom_5px">
			<label class="col-sm-2 control-label" for="dispatch_time"><?php _e('Dispatch Time','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="dispatch_time" class="form-control dispatch_time" data-show-meridian="false" data-minute-step="15"type="text"  value="<?php if($edit){ echo $result->dispatch_time;}elseif(isset($_POST['dispatch_time'])) echo $_POST['dispatch_time'];?>" name="dispatch_time">
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_ambulance_request" class="btn btn-success"/>
        </div>
        </form><!-- END Ambulance Request FORM -->	
        </div><!-- END PANEL BODY DIV -->
	</div>	<!-- END TAB FADE DIV -->	
</div><!-- END TAB CONTENT DIV -->	
<?php 
}
if($active_tab == 'ambulance_list')
{
?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#ambulance_list').DataTable({
			"responsive": true,
			 "order": [[ 1, "asc" ]],
			 "aoColumns":[
						  {"bSortable": false},
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
        	<div class="table-responsive"><!-- START TABLE RESPONSIVE DIV -->	
				<table id="ambulance_list" class="display" cellspacing="0" width="100%"><!-- START Ambulance LIST FORM -->	
					<thead>
						<tr>
							<th style="width: 50px;height:50px;"><?php _e( 'Image', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Ambulance ID', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Reg NO', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Driver Name', 'hospital_mgt' ) ;?></th>	
										
							<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
						</tr>
				    </thead>
				    <tfoot>
						<tr>
							<th><?php _e( 'Image', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Ambulance ID', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Reg NO', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Driver Name', 'hospital_mgt' ) ;?></th>	
							
							<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
						</tr>
					</tfoot>
		 
					<tbody>
					 <?php 					
					 $ambulance_data=$obj_ambulance->get_all_ambulance();					
					 if(!empty($ambulance_data))
					 {
						foreach ($ambulance_data as $retrieved_data)
						{ 
						?>
						<tr>
							<td class="driver_image">
								<?php //echo get_option( 'hmgt_driver_thumb' )."image";
									if(trim($retrieved_data->driver_image) == "")
										echo '<img src='.get_option( 'hmgt_driver_thumb' ).' height="50px" width="50px" class="img-circle"/>';
									else
										echo '<img src='.$retrieved_data->driver_image.' height="50px" width="50px" class="img-circle"/>';
								?>
							</td>
							<td class="amb_id"><?php echo $retrieved_data->ambulance_id;?></td>
							<td class="regno"><?php echo $retrieved_data->registerd_no;?></td>
							<td class="driver_name"><?php echo $retrieved_data->driver_name;?></td>
						   
							<td class="action"> 
							<?php
							if($user_access['edit']=='1')
							{
							?>
								<a href="?dashboard=user&page=ambulance&tab=add_ambulance&action=edit&amb_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->amb_id);?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
							<?php
							}
							if($user_access['delete']=='1')
							{
							?>	
								<a href="?dashboard=user&page=ambulance&tab=ambulance_list&action=delete&amb_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->amb_id);?>" class="btn btn-danger" 
								onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
								<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
							<?php
							}
							?>	
						</tr>
						<?php } 
						
					}?>
				 
					</tbody>
				</table><!-- END Ambulance LIST DIV -->	
            </div><!-- END TABLE RESPONSIVE DIV -->	
        </div><!-- END PANEL BODY DIV -->	
	<?php 
}
if($active_tab == 'add_ambulance')
{
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] =='edit' && $_REQUEST['tab'] == 'add_ambulance')
	{
		
		$edit=1;
		$result= $obj_ambulance->get_single_ambulance(MJ_hmgt_id_decrypt($_REQUEST['amb_id']));		
	}?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#patient_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
		
	} );
	</script>
    <div class="panel-body"><!-- START PANEL BODY DIV -->
        <form name="patient_form" action="" method="post" class="form-horizontal" id="patient_form" enctype="multipart/form-data"><!-- START Ambulance FORM -->
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="amb_id" value="<?php if(isset($_REQUEST['amb_id'])) echo MJ_hmgt_id_decrypt($_REQUEST['amb_id']);?>"  />
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="ambulance_id"><?php _e('Ambulance Id','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="ambulance_id" class="form-control validate[required]" type="text" readonly value="<?php if($edit){ echo $result->ambulance_id;}elseif(isset($_POST['ambulance_id'])) echo $_POST['ambulance_id']; else echo $obj_ambulance->generate_ambulance_id();?>" name="ambulance_id">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="amb_Reg_number"><?php _e('Registration Number','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="amb_Reg_number" class="form-control validate[required]" min="0" type="number" onKeyPress="if(this.value.length==6) return false;" value="<?php if($edit){ echo $result->registerd_no;}elseif(isset($_POST['registerd_no'])) echo $_POST['registerd_no'];?>" name="registerd_no">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="amb_driver_name"><?php _e('Driver Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="amb_driver_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]]" type="text"  maxlength="50" value="<?php if($edit){ echo $result->driver_name;}elseif(isset($_POST['driver_name'])) echo $_POST['driver_name'];?>" name="driver_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="amb_driver_address"><?php _e('Driver Address','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="amb_driver_address" class="form-control validate[required,custom[address_description_validation]]" type="text"  maxlength="150" value="<?php if($edit){ echo $result->driver_address;}elseif(isset($_POST['driver_address'])) echo $_POST['driver_address'];?>" name="driver_address">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="amb_phone_number"><?php _e('Driver Phone Number','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="amb_phone_number" class="form-control validate[required,custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="<?php if($edit){ echo $result->driver_phoneno;}elseif(isset($_POST['driver_phoneno'])) echo $_POST['driver_phoneno'];?>" name="driver_phoneno">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="discription"><?php _e('Description','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="discription" class="form-control validate[custom[address_description_validation]]" maxlength="150" type="text"  value="<?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?>" name="description">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="photo"><?php _e('Driver Image','hospital_mgt');?></label>
			<div class="col-sm-2 margin_bottom_5px">
				<input type="text" id="hmgt_user_avatar_url" class="form-control" name="hmgt_user_avatar_url"  
				value="<?php if($edit)echo esc_url( $result->driver_image );elseif(isset($_POST['upload_user_avatar_image'])) echo $_POST['upload_user_avatar_image']; ?>" readonly />
			</div>	
				<div class="col-sm-3">
					<input type="hidden" name="hidden_upload_user_avatar_image" 
					value="<?php if($edit){ echo $result->driver_image;}elseif(isset($_POST['upload_user_avatar_image'])) echo $_POST['upload_user_avatar_image'];
					else echo get_option('hmgt_patient_thumb');?>">
       				 <input id="upload_user_avatar_image" name="upload_user_avatar_image" type="file" class="form-control file" value="<?php _e( 'Upload image', 'hospital_mgt' ); ?>" />
       			</div>
			<div class="clearfix"></div>
			
			<div class="col-sm-offset-2 col-sm-8">
				 <div id="upload_user_avatar_preview" >
					 <?php if($edit) 
						{
						if($result->driver_image == "")
						{?>
						<img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">
						<?php }
						else {
							
							?>
						<img class="image_preview_css" src="<?php if($edit)echo esc_url( $result->driver_image ); ?>" />
						<?php 
						}
						}
						else {
							?>
							<img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">
							<?php 
						}?>
				</div>
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_ambulance" class="btn btn-success"/>
        </div>
        </form><!-- END Ambulance FROM-->
    </div><!-- END PANEL BODY DIV -->
    <?php
}
?>