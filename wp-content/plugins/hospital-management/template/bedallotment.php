<div class="datas"> </div>
<?php 
MJ_hmgt_browser_javascript_check();
$obj_bed = new MJ_hmgt_bedmanage();
$obj_hospital = new Hospital_Management(get_current_user_id());
$user_role = $obj_hospital->role;
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
 //SAVE BAD ALLOTMENT DATA   
if(isset($_REQUEST['bedallotment']))
{

	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'insert' || $_REQUEST['action'] == 'edit'))
	{

		$result = $obj_bed->add_bed_allotment($_POST);
		if($result)
		{
			if($_REQUEST['action'] == 'edit')
			{
				wp_redirect ( home_url() . '?dashboard=user&page=bedallotment&tab=bedallotlist&message=2');
			}
			else 
			{
				wp_redirect ( home_url() . '?dashboard=user&page=bedallotment&tab=bedallotlist&message=1');
			}
			
			
		}
	}
}
//SAVE BAS Transfer BED
if(isset($_POST['bed_transfar'])){
	if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'transfer')){	
			$result = $obj_bed->patient_bed_transfar($_POST);
			if($result){		
				wp_redirect ( home_url() . '?dashboard=user&page=bedallotment&tab=bedallotlist&message=4');				
			}
		}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_bed->delete_bedallocate_record(MJ_hmgt_id_decrypt($_REQUEST['allotment_id']));
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=bedallotment&tab=bedallotlist&message=3');
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
	{?><div id="message" class="updated below-h2 "><p><?php
				_e(" Bed Transfered successfully",'hospital_mgt');
				?></p>
				</div>
			<?php 
		
	}
}	
$active_tab = isset($_GET['tab'])?$_GET['tab']:'bedallotlist';
?>
<div class="panel-body panel-white"><!-- START PANEL BODY DIV-->
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="<?php if($active_tab=='bedallotlist'){?>active<?php }?>">
			  <a href="?dashboard=user&page=bedallotment&tab=bedallotlist">
				 <i class="fa fa-align-justify"></i> <?php _e('Assigned Bed List', 'hospital_mgt'); ?></a>
			  </a>
		</li>    
		<li class="<?php if($active_tab=='bedassign'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{?>
				<a href="?dashboard=user&page=bedallotment&tab=bedassign&&action=edit&allotment_id=<?php echo $_REQUEST['allotment_id'];?>" class="tab <?php echo $active_tab == 'bedassign' ? 'active' : ''; ?>">
				<i class="fa fa"></i> <?php _e('Edit Assign Bed', 'hospital_mgt'); ?></a>
			 <?php 
			}
			else
			{
				if($user_access['add']=='1')
				{			
				?>				
					<a href="?dashboard=user&page=bedallotment&tab=bedassign&&action=insert" class="tab <?php echo $active_tab == 'bedassign' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php _e('Assign Bed', 'hospital_mgt'); ?></a>
				<?php
				}
			}
			?>	  
		</li>	  
		<?php
		if($active_tab=="transfer"){
		  ?> 
		  
		   <li class="<?php if($active_tab=='transfer'){?>active<?php }?>">
			  <a href="?dashboard=user&page=bedallotment&tab=transfer&action=transfer&allotment_id=<?php echo $_REQUEST['allotment_id'];?>">
				 <i class="fa fa-plus-circle"></i> <?php _e('Transfer Bed', 'hospital_mgt'); ?></a>
			  </a>
		  </li>
		  <?php }  ?>
		  
	</ul>
	<div class="tab-content"><!-- START TAB CONTENT DIV-->
		<?php 
		if($active_tab=='bedallotlist'){
		?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
		jQuery('#bedallotmentlist').DataTable({
			"responsive": true,
			 "order": [[ 4, "Desc" ]],
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
		} );
		</script>
		<div class="tab-pane fade active in"  id="bedallotlist"><!-- START TAB PANE DIV-->
			<div class="panel-body"><!-- START PANEL BODY DIV-->
				<div class="table-responsive"><!-- START TABLE RESPONSIVE DIV-->
					<table id="bedallotmentlist" class="display dataTable " cellspacing="0" width="100%"><!-- START BAD Allotment LIST TABLE-->
						<thead>
							<tr>
								<th><?php _e( 'Bed Type', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Bed Number', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Nurse', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Allotment Date', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Expected Discharge Date', 'hospital_mgt' ) ;?></th>
								<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th><?php _e( 'Bed Type', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Bed Number', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Patient', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Nurse', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Allotment Date', 'hospital_mgt' ) ;?></th>
								<th><?php _e( 'Expected Discharge Date', 'hospital_mgt' ) ;?></th>
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
								$bedallotment_data=$obj_bed->get_all_bedallotment_by_allotment_by();
							}
							else
							{
								$bedallotment_data=$obj_bed->get_all_bedallotment();
							}
						}
						elseif($obj_hospital->role == 'doctor') 
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$bedallotment_data=$obj_bed->get_doctor_all_bedallotment_by_allotment_by();
							}
							else
							{
								$bedallotment_data=$obj_bed->get_all_bedallotment();
							}
						}
						elseif($obj_hospital->role == 'nurse') 
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$bedallotment_data=$obj_bed->get_nurse_all_bedallotment_by_allotment_by();
							}
							else
							{
								$bedallotment_data=$obj_bed->get_all_bedallotment();
							}
						}
						elseif($obj_hospital->role == 'patient') 
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$bedallotment_data=$obj_bed->get_all_bedallotment_by_patient();
							}
							else
							{
								$bedallotment_data=$obj_bed->get_all_bedallotment();
							}
						}
												
						if(!empty($bedallotment_data))
						{
							foreach ($bedallotment_data as $retrieved_data){ 
								$patient_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->patient_id);
						?>
							<tr>
								<td class="bed_type"><?php echo $obj_bed->get_bedtype_name($retrieved_data->bed_type_id);	?></td>
								<td class="bed_number"><?php
								if(!empty($retrieved_data->bed_number))
								{  
									echo $obj_bed->get_bed_number($retrieved_data->bed_number);
								}
								else
								{ 
									echo '-'; 
								}	
								?></td>
								<td class="patient"><?php echo $patient_data['first_name']." ".$patient_data['last_name']."(".$patient_data['patient_id'].")";?></td>
								 <td class="nurse">
								<?php 
								if(!empty($retrieved_data->bed_allotment_id))
								{  
									$nurselist =  $obj_bed->get_nurse_by_assignid($retrieved_data->bed_allotment_id) ;
									foreach($nurselist as $assign_id)
									{
										$nurse_data =	MJ_hmgt_get_user_detail_byid($assign_id->child_id);
										echo $nurse_data['first_name']." ".$nurse_data['last_name'].",";
										
									}
								}
								else
								{ 
									echo '-'; 
								}	
								?>
								</td>
								<td class="allotment_time"><?php echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->allotment_date));?></td>
								<td class="discharge_time"><?php echo date(MJ_hmgt_date_formate(),strtotime( $retrieved_data->discharge_time));?></td>
								<td class="action"> 
								<?php
								if($user_access['add']=='1')
								{
								?>					
									<a href="?dashboard=user&page=bedallotment&tab=transfer&action=transfer&allotment_id=<?php echo $retrieved_data->bed_allotment_id;?>" class="btn btn-success"> <?php _e('Transfer Bed', 'hospital_mgt' ) ;?></a>
								<?php
								}
								if($user_access['edit']=='1')
								{
								?>
									<a href="?dashboard=user&page=bedallotment&tab=bedassign&action=edit&allotment_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->bed_allotment_id);?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
								<?php
								}
								if($user_access['delete']=='1')
								{
								?>	
									<a href="?dashboard=user&page=bedallotment&tab=bedallotlist&action=delete&allotment_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->bed_allotment_id);?>" class="btn btn-danger" 
									onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
									<?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>
							   <?php
								}
							   ?>
								</td>
							   
							</tr>
							<?php } 
							
						}?>
						</tbody>
					</table><!-- END BEDALLOTMENT TABLE-->
				</div><!-- END TABLE RESPONSIVE DIV-->
			</div><!-- END PANEL BODY DIV-->
		</div><!-- END PANE TAB DIV-->
		<?php }
		 if($active_tab=='bedassign'){
		?>
		<div class="tab-pane fade active in"  id="bedallot"><!-- END TAB PANE DIV-->
		<?php 
		$obj_bed = new MJ_hmgt_bedmanage();
		?>
	<script type="text/javascript">
	jQuery(document).ready(function($) 
	{
		$('#patient_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
			var start = new Date();
			var end = new Date(new Date().setYear(start.getFullYear()+1));
			 $.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
			$('#allotment_date').datepicker({
				startDate : start,
				
				autoclose: true
			}).on('changeDate', function(){
				$('#discharge_time').datepicker('setStartDate', new Date($(this).val()));
			}); 
			$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
			$('#discharge_time').datepicker({
				startDate : start,
				autoclose: true
			}).on('changeDate', function(){
				$('#allotment_date').datepicker('setEndDate', new Date($(this).val()));
			});
		 $('#nurse').multiselect({
		nonSelectedText :'<?php _e('Select Nurse','hospital_mgt'); ?>',
		includeSelectAllOption: true,
		selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
	} );
	</script>
		 <?php 	
			$edit=0;
					if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
						$edit=1;
						$result = $obj_bed->get_single_bedallotment(MJ_hmgt_id_decrypt($_REQUEST['allotment_id']));
					}?>
			
		<div class="panel-body"><!-- START PANEL BODY DIV-->
			<form name="patient_form" action="" method="post" class="form-horizontal" id="patient_form"><!-- START BED ALLOTMENT FORM-->
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="allotment_id" value="<?php if(isset($_REQUEST['allotment_id'])) echo MJ_hmgt_id_decrypt($_REQUEST['allotment_id']);?>"  />
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select name="patient_id" id="patient_id" class="form-control validate[required] ">
						<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
						<?php 
						if($edit)
							$patient_id1 = $result->patient_id;
						elseif(isset($_REQUEST['patient_id']))
							$patient_id1 = $_REQUEST['patient_id'];
						else 
							$patient_id1 = "";
						$patients = MJ_hmgt_inpatient_list();
						//print_r($patient);
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
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient_status"><?php _e('Patient Status','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8" >
					<?php 
					$patient_status = "";
					if($edit){ $patient=MJ_get_inpatient_status($result->patient_id);
						if(!empty($patient)){
					 $patient_status=$patient->patient_status;}}elseif(isset($_POST['patient_status'])){$patient_status=$_POST['patient_status'];}else{$patient_status=' ';} ?>
					<select name="patient_status" class="form-control validate[required]" >
					<option value=""><?php _e('select Patient Status','hospital_mgt');?></option>
					<?php foreach(MJ_hmgt_admit_reason() as $reason)
					{?>
						<option value="<?php echo $reason;?>" <?php selected($patient_status,$reason);?>><?php echo $reason;?></option>
					<?php }?>				
					</select>				
				</div>	
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="bed_type_id"><?php _e('Select Bed Type','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
				<?php if(isset($_REQUEST['bed_type_id']))
						$bed_type1 = $_REQUEST['bed_type_id'];
					elseif($edit)
						$bed_type1 = $result->bed_type_id;
					else 
						$bed_type1 = "";
					?>
					<select name="bed_type_id" class="form-control validate[required]" id="bed_type_id">
					<option value = ""><?php _e('Bed type','hospital_mgt');?></option>
					<?php 
					
					$bedtype_data=$obj_bed->get_all_bedtype();
					if(!empty($bedtype_data))
					{
						foreach ($bedtype_data as $retrieved_data)
						{
							echo '<option value="'.$retrieved_data->ID.'" '.selected($bed_type1,$retrieved_data->ID).'>'.$retrieved_data->post_title.'</option>';
						}
					}
					?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="bednumber"><?php _e('Bed Number','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select name="bed_number" class="form-control validate[required]" id="bednumber">
					<option value=""><?php _e('Select Bed Number','hospital_mgt');?></option>
					<?php 
					if($edit)
					{
						
						$bedtype_data = $obj_bed->get_bed_by_bedtype($result->bed_type_id);
						if(!empty($bedtype_data))
						{
							foreach ($bedtype_data as $retrieved_data)
							{
								echo '<option value="'.$retrieved_data->bed_id.'" '.selected($result->bed_number,$retrieved_data->bed_id).'>'.$retrieved_data->bed_number.'</option>';
							}
						}
					}
					?>
					</select>
				</div>
			</div>
			
			<div class="col-sm-2"></div>
			<div class="col-sm-8" id="bedlocation">
			<?php 
			if($edit)
			{
				$obj_bed = new MJ_hmgt_bedmanage();
				$beddata = $obj_bed->get_single_bed($result->bed_number);
				
			?>	
				<p class="bg-info" style="padding:10px; float:left; width:100%"><strong>Bed Location : </strong><?php print $beddata->bed_location  ?></p>
			<?php
			}
			?>	
			</div>
			<div class="col-sm-2" style="padding:10px; float:left; width:100%" ></div>
			
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="allotment_date"><?php _e('Allotment Date','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="allotment_date" class="form-control validate[required]" type="text"   value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->allotment_date));}elseif(isset($_POST['allotment_date'])) echo $_POST['allotment_date'];?>" name="allotment_date">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="discharge_time"><?php _e('Expected Discharge Date','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="discharge_time" class="form-control validate[required]" type="text"  value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime($result->discharge_time));}elseif(isset($_POST['discharge_time'])) echo $_POST['discharge_time'];?>" name="discharge_time">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="doctor"><?php _e('Select Nurse','hospital_mgt');?></label>
				<div class="col-sm-8">
				<?php $allnurse = MJ_hmgt_getuser_by_user_role('nurse');
						$nurse_data = array();
						if($edit)
						{
							
							$nurse_list = $obj_bed->get_nurse_by_bedallotment_id(MJ_hmgt_id_decrypt($_REQUEST['allotment_id']));
							
							foreach($nurse_list as $assign_id)
							{
								$nurse_data[]=$assign_id->child_id;
							
							}
						}
						elseif(isset($_REQUEST['doctor']))
						{
							$nurse_list = $_REQUEST['doctor'];
							foreach($nurse_list as $assign_id)
							{
								$nurse_data[]=$assign_id;
							
							}
						}
						
						?>
					<select name="nurse[]" class="form-control" multiple="multiple" id="nurse">
					
					<?php 
						
						
						if(!empty($allnurse))
						{
						foreach($allnurse as $nurse)
						{
							$selected = "";
							if(in_array($nurse['id'],$nurse_data))
								$selected = "selected";
							echo '<option value='.$nurse['id'].' '.$selected.'>'.$nurse['first_name'].'</option>';
						}
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="middle_name"><?php _e('Description','hospital_mgt');?></label>
				<div class="col-sm-8">
					<textarea class="form-control validate[custom[address_description_validation]]" maxlength="150" name="allotment_description" id="allotment_description"><?php if($edit){ echo $result->allotment_description;}elseif(isset($_POST['allotment_description'])) echo $_POST['allotment_description'];?></textarea>
					
				</div>
			</div>
			<div class="col-sm-offset-2 col-sm-8">
				<input id="save_allow" type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="bedallotment" class="btn btn-success"/>
			</div>
			</form><!-- END BED Allotment FORM-->
		</div><!-- END PANEL BODY DIV-->
		
	</div><!-- END PANE TAB DIV-->
	<?php } 
	if($active_tab=="transfer"){ 
		require_once HMS_PLUGIN_DIR. '/template/transfer.php';
	 }
			?>
    </div><!-- END TAB CONTENT DIV-->
</div><!-- END PANE BODY DIV-->
<?php ?>