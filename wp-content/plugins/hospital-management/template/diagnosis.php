<?php 
MJ_hmgt_browser_javascript_check();
$obj_dignosis = new MJ_hmgt_dignosis();
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
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'diagnosislist';
if(isset($_REQUEST['save_diagnosis']))
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
			
			if(isset($_REQUEST['action']) &&  $_REQUEST['action'] == 'edit')
			{
					wp_redirect ( home_url() . '?dashboard=user&page=diagnosis&tab=diagnosislist&message=2');
			}
			else 
			{
				wp_redirect ( home_url() . '?dashboard=user&page=diagnosis&tab=diagnosislist&message=1');
			}
		}
	}
	else
	{
		$result = $obj_dignosis->hmgt_add_dignosis($_POST);
			
		if(isset($_REQUEST['action']) &&  $_REQUEST['action'] == 'edit')
		{
				wp_redirect ( home_url() . '?dashboard=user&page=diagnosis&tab=diagnosislist&message=2');
		}
		else 
		{
			wp_redirect ( home_url() . '?dashboard=user&page=diagnosis&tab=diagnosislist&message=1');
		}
	}		
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result = $obj_dignosis->delete_dignosis(MJ_hmgt_id_decrypt($_REQUEST['diagnosis_id']));
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=diagnosis&tab=diagnosislist&message=3');
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
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Report status updated successfully','hospital_mgt');
	?></div></p><?php
			
	}
}	
$active_tab = isset($_GET['tab'])?$_GET['tab']:'diagnosislist';
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{					
	$edit=1;
	$result = $obj_dignosis->get_single_dignosis_report(MJ_hmgt_id_decrypt($_REQUEST['diagnosis_id']));
}?>


<script type="text/javascript">
jQuery(document).ready(function($) {
		$('#diagnosis1').DataTable({
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
		 $('#diagnosis_request_list').DataTable({
			 "responsive": true,
		 "order": [[ 0, "Desc" ]],
		 "aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bVisible": true},	                 
	                  {"bVisible": true},	                 
	                  {"bSortable": false}
	               ],
			language:<?php echo MJ_hmgt_datatable_multi_language();?>
		}); 
		
		
	$('#diagnosis_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#doctor_form_outpatient_popup_form_percription').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#symptoms').multiselect({
		nonSelectedText :'<?php _e('Select Symptoms','hospital_mgt'); ?>',
		includeSelectAllOption: true,
		selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
	 });
	var date = new Date();
       date.setDate(date.getDate()-0);
	    $.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
        $('.birth_date').datepicker({
	    startDate: date,
        autoclose: true
   }); 
				
		//add outpatient pop up//
	 
	    $('#doctor_form_outpatient_popup_form_percription').on('submit', function(e) {
		e.preventDefault();
		
		//var form = $(this).serialize(); 
		var valid = $('#doctor_form_outpatient_popup_form_percription').validationEngine('validate');
		if (valid == true) 
		{
			var form = new FormData(this);
		
		 $.ajax({
			type:"POST",
			url: $(this).attr('action'),
			data:form,
			cache: false,
            contentType: false,
            processData: false,
			success: function(data)
			{
				 if(data!=""){ 
				   var json_obj = $.parseJSON(data);
				    
					$('#doctor_form_outpatient_popup_form_percription').trigger("reset");
					$('#patient').append(json_obj[0]);
					
					$('#upload_user_avatar_preview').html('<img alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">');
					$('#hmgt_user_avatar_url').val('');
					
					$('.modal').modal('hide');
				}  
			},
			error: function(data){
			}
		})
		
		}
	}); 
	$('#report_type').multiselect({
		nonSelectedText : '<?php _e('Select Report Name','hospital_mgt'); ?>',
		includeSelectAllOption: true,
		selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
	 });
	$('.tax_charge').multiselect({
			nonSelectedText :'<?php _e('Select Tax','hospital_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
	
	$(".symptoms_alert").click(function()
	{	
		checked = $(".multiselect_validation_symtoms .dropdown-menu input:checked").length;
		if(!checked)
		{
		  alert("<?php _e('Please select atleast one Symptoms','hospital_mgt');?>");
		  return false;
		}	
	});

	  $('#dagnosis_report_form').on('submit', function(e)
		{
			e.preventDefault();
			var form = $(this).serialize();
			var valid = $("#dagnosis_report_form").validationEngine('validate');
			if (valid == true)
			{	
				$.ajax(
				{
					type:"POST",
					url: $(this).attr('action'),
					data:form,												
					success: function(data)
					{														
						$('#dagnosis_report_form').trigger("reset");
						$('.modal').modal('hide');
					
						window.location.href = window.location.href + "&message=4";								
					},
					error: function(data){
					}
				})
				
			}
		}); 	
	
	$("body").on("click", ".update_dagnosis_report", function(event)
	{
		var report_status  = $(this).attr('report_status');
		var pescription_id  = $(this).attr('priscription_id');
		$(".report_status").val(report_status);
		$(".pescription").val(pescription_id);
		
	});
	$(".save_diagnosis").click(function()
	{	
		checked = $(".multiselect_validation_Report .dropdown-menu input:checked").length;
		if(!checked)
		{
		  alert("<?php _e('Please select at least one report type','hospital_mgt'); ?>");
		  return false;
		}	
	});	
});
</script>
<!-- POP up code -->
<div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content overlay_content_css">
		<div class="modal-content">
			<div class="notice_content"></div>    
			<div class="category_list">
			 </div>
        </div>
   </div> 
</div>
<!-- End POP-UP Code -->
<div class="panel-body panel-white"><!-- START PANEL BODY DIV-->
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		    <li class="<?php if($active_tab=='diagnosislist'){?>active<?php }?>">
				<a href="?dashboard=user&page=diagnosis&tab=diagnosislist" class="tab <?php echo $active_tab == 'diagnosislist' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php _e('Diagnosis Report List', 'hospital_mgt'); ?></a>
			  </a>
		    </li>
		    <li class="<?php if($active_tab=='adddiagnosis'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['diagnosis_id']))
				{?>
				<a href="?dashboard=user&page=diagnosis&tab=adddiagnosis&action=edit&diagnosis_id=<?php if(isset($_REQUEST['diagnosis_id'])) echo $_REQUEST['diagnosis_id'];?>"" class="tab <?php echo $active_tab == 'adddiagnosis' ? 'active' : ''; ?>">
				 <i class="fa fa"></i> <?php _e('Edit Diagnosis Report', 'hospital_mgt'); ?></a>
				 <?php }
				else
				{	
					if($user_access['add']=='1')
					{		
					?>
						<a href="?dashboard=user&page=diagnosis&tab=adddiagnosis&&action=insert" class="tab <?php echo $active_tab == 'adddiagnosis' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php _e('Add Diagnosis Report', 'hospital_mgt'); ?></a>
				<?php 
					}
				}
				
			?>
			</li>
			<?php 
			if($obj_hospital->role == 'laboratorist')
			{
			?>
			<li class="<?php if($active_tab=='new_diagnosis_report_request_list'){?>active<?php }?>">
				<a href="?dashboard=user&page=diagnosis&tab=new_diagnosis_report_request_list" class="tab <?php echo $active_tab == 'new_diagnosis_report_request_list' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php _e('New Diagnosis Report Request', 'hospital_mgt'); ?></a>
			  </a>
		    </li>
			<?php } ?>
		  
	</ul>
<?php if($active_tab=='diagnosislist')
{
?>
	<div class="tab-content"><!--START TAB CONTENT DIV-->
		<div class="panel-body"><!--STRAT PANEL BODY DIV-->
            <div class="table-responsive">	<!--START TABLE RESPONSIVE DIV-->
				<table id="diagnosis1" class="display dataTable" cellspacing="0" width="100%"><!--START DIGNOSIS LIST TABLE-->
				    <thead>
						<tr>
						    <th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
						    <th> <?php _e( 'Patient ID-Name', 'hospital_mgt' ) ;?></th>
						    <th> <?php _e( 'Report Type & Amount', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Description', 'hospital_mgt' ) ;?></th>
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
						    <th> <?php _e( 'Description', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Report', 'hospital_mgt' ) ;?></th>
							<th><?php _e( 'Report Cost', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
							<th><?php _e( 'Tax Amount', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
							<th><?php _e( 'Total Report Cost', 'hospital_mgt' ) ;?> (<?php echo "<span>".MJ_hmgt_get_currency_symbol()."</span>";?>)</th>
							<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
						</tr>
				    </tfoot>
					<tbody>
					<?php 
					$current_user_id=get_current_user_id();
					if($role == 'patient')
					{
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{ 
							$dignosis_data = $obj_hospital->get_current_patint_diagnosis_report($current_user_id);
						 
						}
						else
						{
						   $dignosis_data=$obj_dignosis->get_all_dignosis_report();
						}
						
					}
					elseif($role == 'doctor')
					{
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{ 
							$dignosis_data=$obj_dignosis->get_doctor_last_diagnosis_created_by($current_user_id);
						}
						else
						{
						   $dignosis_data=$obj_dignosis->get_all_dignosis_report();
						}
						
					}
					elseif($role == 'nurse')
					{
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{ 
							$dignosis_data=$obj_dignosis->get_nurse_last_diagnosis_created_by($current_user_id);
						}
						else
						{
						   $dignosis_data=$obj_dignosis->get_all_dignosis_report();
						}
						
					}
					elseif($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'pharmacist' || $obj_hospital->role == 'accountant' || $obj_hospital->role == 'receptionist') 
					{
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{ 
							$dignosis_data=$obj_dignosis->get_last_diagnosis_created_by($current_user_id);
						}
						else
						{
						   $dignosis_data=$obj_dignosis->get_all_dignosis_report();
						}
					}
					
					if(!empty($dignosis_data))
					{
						foreach ($dignosis_data as $retrieved_data){ 
						
					 ?>
						<tr>
							<td class="date"><?php echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->diagnosis_date));?></td>
							<td class="patient_id">
							<?php 
								$patient = MJ_hmgt_get_user_detail_byid( $retrieved_data->patient_id);
								echo $patient['id']." - ".$patient['first_name']." ".$patient['last_name'];
							
							?></td>
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
									echo '<a href="'.content_url().'/uploads/hospital_assets/'.$retrieved_data->attach_report.'" class="btn btn-default" target="_blank"
									><i class="fa fa-download"></i>  '. __( "Download", "hospital_mgt" ) .' </a>';
								}	
								else
								{		
									echo __('No any Report','hospital_mgt');
								}	
							?>
							</td>	
							<td class=""><?php echo number_format($retrieved_data->report_cost, 2, '.', ''); ?></td>
							<td class=""><?php echo number_format($retrieved_data->total_tax, 2, '.', ''); ?></td>
							<td class=""><?php echo number_format($retrieved_data->total_cost, 2, '.', ''); ?></td>			
							<td class="action"> 
							<?php
							if($user_access['edit']=='1')
							{
								if($retrieved_data->total_cost!="")
								{
								?>
								<a href="?dashboard=user&page=diagnosis&tab=adddiagnosis&action=edit&diagnosis_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->diagnosis_id);?>" 
								class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
								<?php
								}							
							}
							if($user_access['delete']=='1')
							{
							?>					
								<a href="?dashboard=user&page=diagnosis&tab=diagnosislist&action=delete&diagnosis_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->diagnosis_id);?>" 
								class="btn btn-danger" 
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
				</table><!--END DIGNOSIS LIST TABLE-->
            </div><!--END TABLE RESPONSIVE DIV-->
        </div><!--END PANEL BODY DIV-->
	<?php
	}
	if($active_tab=='adddiagnosis')
	{ 
   ?>
	    <div class="panel-body"><!--START PANEL BODY DIV-->
			<form name="diagnosis_form" action="" method="post" class="form-horizontal" id="diagnosis_form" enctype="multipart/form-data"><!--START DIGNOSIS FORM-->
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" id="action_name" name="action" value="<?php echo $action;?>">
			<input type="hidden" id="diagnosisid" name="diagnosisid" value="<?php if(isset($_REQUEST['diagnosis_id'])) echo MJ_hmgt_id_decrypt($_REQUEST['diagnosis_id']);?>"  />
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8 margin_bottom_5px">
					<select name="patient_id" id="patient" class="form-control validate[required] ">
						<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
						<?php 
						if($edit)
							$patient_id1 = $result->patient_id;
						elseif(isset($_REQUEST['patient_id']))
							$patient_id1 = $_REQUEST['patient_id'];
						else 
							$patient_id1 = "";
						$patients = MJ_hmgt_patientid_list();
						//print_r($patient);
						if(!empty($patients))
						{
							foreach($patients as $patient)
							{
								echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'> '.$patient['first_name'].'  '.$patient['last_name'].'-'.$patient['patient_id'].' </option>';
							}
						}
						?>
					</select>
					
				</div>
				<!--ADD OUT PATIENT POPUP BUTTON -->
				<div class="col-sm-2">
				<!--<a href="?page=gmgt_staff&tab=add_staffmember" class="btn btn-default"> <?php _e('Add Doctor','hospital_mgt');?></a>-->
				<a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal_add_outpatient"> <?php _e('Add Outpatient','hospital_mgt');?></a>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient"><?php _e('Report Type','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8 multiselect_validation_Report margin_bottom_5px">
					<select class="form-control reportlist_list report_type dignosis_upload" multiple="multiple" name="report_type[]" id="report_type">
					<?php 
					$report_type=new MJ_hmgt_dignosis();
					$operation_array =$report_type->get_all_report_type();
					if(!empty($operation_array))
					{
						foreach ($operation_array as $retrive_data)
						{
							$report_type_data=$retrive_data->post_title;
							$report_type_array=json_decode($report_type_data);						
							
							if($edit)
							{
							  $report_type=explode(",",$result->report_type);
							}
					        elseif(isset($_REQUEST['report_type']))
							{
						     $report_type=explode(",",$_REQUEST['report_type']);
							}
							else
							{
								$report_type=array();								
							}
							?>
							
							<option value="<?php echo $retrive_data->ID; ?>" <?php  if(in_array($retrive_data->ID,$report_type)){ echo 'selected'; } ?>><?php echo $report_type_array->category_name; ?></option>
							<?php
						}
					}
					?>						
					</select>
					<br>
				</div>
				<div class="col-sm-2"><button id="addremove" model="report_type"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
			</div>
			<?php
		if($edit)
		{
			?>
			<div class="add_document_div_main_class">		
				<div class="form-group">		   
					<label class="col-sm-offset-2 col-sm-2 control-label upload_document_text_align"  for="document"><?php _e('Report Name','hospital_mgt');?></label>
					<label class="col-sm-3 control-label upload_document_text_align"  for="document"><?php _e('Upload Report','hospital_mgt');?></label>
					<label class="col-sm-2 control-label upload_document_text_align"  for="document"><?php _e('View Report','hospital_mgt');?></label>
					<label class="col-sm-1 control-label upload_document_text_align"  for="document"><?php _e('Amount','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(); ?>)</label>
						
				</div>
				<?php
				$dignosis_array=json_decode($result->attach_report);
				
				if(!empty($result->attach_report))
				{
					foreach($dignosis_array as $key=>$value)
					{
						$report_type=new MJ_hmgt_dignosis();
						$report_data=$report_type->get_report_by_id($value->report_id);
						$report_type_array=json_decode($report_data);
						?>
					<div class="form-group">			
						<div class="col-sm-offset-2 col-sm-2">
						<input type="hidden" name="report_id[]" value="<?php echo $value->report_id; ?>">
						<input type="text" class="form-control fronted_file report_name" style="text-align: center;" value="<?php echo $report_type_array->category_name; ?>" name="report_name[]" readonly>
						</div>
						
						<div class="col-sm-3">
							<input type="file" class="form-control fronted_file document" style="text-align: center;" name="document[]" value="<?php echo $value->attach_report; ?>">
							
						</div>		
						<div class="col-sm-2">
							<?php
							echo '<a href="'.content_url().'/uploads/hospital_assets/'.$value->attach_report.'" class="btn btn-default" target="_blank"><i class="fa fa-download"></i> '.$report_type_array->category_name.' '.__('Report','hospital_mgt').'</a>';
							?>
							<input type="hidden" name="hidden_attach_report[]" value="<?php echo $value->attach_report; ?>" >
						</div>	
						<div class="col-sm-1">
						<input type="text" class="form-control fronted_file diagnosis_total_amount" style="text-align: center;" value="<?php echo $value->report_amount; ?>" name="diagnosis_total_amount[]" readonly>
						</div>			 
					</div>	
				<?php
					}		
				}
				?>
			</div>	
			<?php	
		}
		else
		{
		?>		
		<div class="add_document_div_main_class">
			<?php
			if(isset($_REQUEST['report_cost']))
			{	
				$report_type=new MJ_hmgt_dignosis();
				$report_type_id= explode(",",$_REQUEST['report_type']);
				?>		
				<div class="form-group">		   
					<label class="col-sm-offset-2 col-sm-2 control-label upload_document_text_align"  for="document"><?php _e('Report Name','hospital_mgt'); ?></label>
					<label class="col-sm-3 control-label upload_document_text_align"  for="document"><?php _e('Upload Report ','hospital_mgt'); ?></label>
					<label class="col-sm-2 control-label upload_document_text_align"  for="document"><?php _e('Amount','hospital_mgt'); ?>( <?php echo MJ_hmgt_get_currency_symbol(); ?> )</label>
						
				</div>
				<?php
				foreach($report_type_id  as $report_id)
				{
					$report_data=$report_type->get_report_by_id($report_id);
					$report_type_array=json_decode($report_data);
					$report_cost =$report_type_array->report_cost;
					 $report_name=$report_type_array->category_name;
					 $diagnosis_tax_array = explode(",",$report_type_array->diagnosis_tax);
			
					$total_tax=0;
				
					if(!empty($diagnosis_tax_array))
					{	
						foreach($diagnosis_tax_array  as $tax_id)
						{				
							$tax_percentage=MJ_hmgt_tax_percentage_by_tax_id($tax_id);
							$tax_amount=$report_cost * $tax_percentage / 100;
							
							$total_tax=$total_tax + $tax_amount;
						}	
					}
					$total_report_cost=$report_cost + $total_tax;
				?>
				<div class="form-group">
					
						<div class="col-sm-offset-2 col-sm-2">
						<input type="hidden" name="report_id[]" value="<?php echo $report_id; ?>">
						<input type="text" class="form-control fronted_file report_name" style="text-align: center;" value="<?php echo$report_name; ?>" name="report_name[]" readonly>
						</div>
						
						<div class="col-sm-3">
							<input type="file" class="form-control  fronted_file document validate[required]" style="text-align: center;" name="document[]">
						</div>				
						<div class="col-sm-2">
						<input type="text" class="form-control fronted_file diagnosis_total_amount" style="text-align: center;" value="<?php echo $total_report_cost; ?>" name="diagnosis_total_amount[]" readonly>
						</div>			 
					</div>
				<?php
				}
			}
			?>
		</div>
		<?php
		}	
		?>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="report_cost"><?php _e('Report Total Cost','hospital_mgt');?> (<?php echo MJ_hmgt_get_currency_symbol(get_option( 'hmgt_currency_code' )); ?>)</label>
				<div class="col-sm-8">
					<input type="hidden" name="cost" class="cost" value="<?php if($edit){ echo $result->report_cost; }elseif(isset($_REQUEST['cost'])){ echo $_REQUEST['cost']; } ?>">
					<input type="hidden" name="report_tax" class="report_tax" value="<?php if($edit){ echo $result->total_tax;}elseif(isset($_REQUEST['total_tax'])){echo $_REQUEST['total_tax'];}  ?>">
					<input id="report_cost" class="form-control  text-input report_cost" min="0" type="number" onKeyPress="if(this.value.length==8) return false;" step="0.01" value="<?php if($edit){ echo $result->total_cost;}     elseif(isset($_REQUEST['report_cost'])){ echo $_REQUEST['report_cost']; }elseif(isset($_POST['report_cost'])){ echo $_POST['report_cost'];}?>" name="report_cost" readonly>
				</div>
			</div>			
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="description"><?php _e('Description','hospital_mgt');?></label>
				<div class="col-sm-8">
					<textarea id="diagno_description" class="form-control validate[custom[address_description_validation]]" maxlength="150" name="diagno_description"><?php if($edit)echo $result->diagno_description; elseif(isset($_POST['diagno_description'])) echo $_REQUEST['diagno_description']; else echo "";?> </textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label " for="enable"><?php _e('Send Email to Patient ','hospital_mgt');?></label>
				<div class="col-sm-8">
					 <div class="checkbox">
						<label>
							<input  type="checkbox" value="1"  name="hmgt_send_mail_to_patient">
						</label>
					</div>
					 
				</div>
		</div>
		
		<div class="form-group">
				<label class="col-sm-2 control-label " for="enable"><?php _e('Send Email to Doctor ','hospital_mgt');?></label>
				<div class="col-sm-8">
					 <div class="checkbox">
						<label>
							<input  type="checkbox" value="1" name="hmgt_send_mail_to_doctor" checked>
						</label>
					</div>
					 
				</div>
		</div>
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" id="dignosisreport" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_diagnosis" class="btn btn-success save_diagnosis"/>
			</div>
			</form><!--END DIGNOSI SFORM -->
        </div><!--END PANEL BODY DIV-->
         
	<?php } ?>
	
	<?php
	if($active_tab=='new_diagnosis_report_request_list')
    {
    ?>
	<div class="panel-body"><!--START PANEL BODY DIV-->
            <div class="table-responsive"><!--START TABLE RESONSIVE DIV-->	
				<table id="diagnosis_request_list" class="display dataTable" cellspacing="0" width="100%"><!--START DIGNOSIS REPORT REQUEST TABLE-->
				    <thead>
						<tr>
						    <th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
						    <th> <?php _e( 'Patient ID-Name', 'hospital_mgt' ) ;?></th>
						    <th> <?php _e( 'Report Type', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Description', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Status', 'hospital_mgt' ) ;?></th>
							<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
						</tr>
				    </thead>
			        <tfoot>
						<tr>
							<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Patient ID-Name', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Report Type', 'hospital_mgt' ) ;?></th>
						    <th> <?php _e( 'Description', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Status', 'hospital_mgt' ) ;?></th>
							<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
						</tr>
				    </tfoot>
					<tbody>
					<?php
					$obj_var=new MJ_hmgt_prescription();
					$alldiagnosis_requst_data=$obj_var->get_all_diagnosis_requst();
					foreach($alldiagnosis_requst_data as $diagnosis_requst_data)
					{
					?>
					<tr>
					
					<td class="name"><?php echo date(MJ_hmgt_date_formate(),strtotime($diagnosis_requst_data->pris_create_date));?>
					</td>
					<td class="patient">
						<?php 
						    $patient = MJ_hmgt_get_user_detail_byid( $diagnosis_requst_data->patient_id);
							$patinet_full_name=$patient['first_name']." ".$patient['last_name'];
							$patient_id=get_user_meta($diagnosis_requst_data->patient_id, 'patient_id', true);
							echo $patient_id .'-'. $patinet_full_name;
						?>
					</td>
				<?php 
				  $report_type=new MJ_hmgt_dignosis();
				  $report_type_data=explode(",",$diagnosis_requst_data->report_type);
				?>
				<td class="report_type">
				<?php
				  $i=1;
				  $report_amount=0;
				  $total_tax=0;
 				  foreach ($report_type_data as $report_id)
				  {
					$report_data=$report_type->get_report_by_id($report_id);
		            $report_type_array=json_decode($report_data);
					
					echo '('.$i .') '.$report_type_array->category_name.','.$report_type_array->report_cost.' ';
					$i++;
					
					$report_amount += $report_type_array->report_cost;
					
					$diagnosis_tax_array = explode(",",$report_type_array->diagnosis_tax);
		
					if(!empty($diagnosis_tax_array))
					{	
						foreach($diagnosis_tax_array  as $tax_id)
						{				
							$tax_percentage=MJ_hmgt_tax_percentage_by_tax_id($tax_id);
							$tax_amount=$report_type_array->report_cost * $tax_percentage / 100;
							
							$total_tax=$total_tax + $tax_amount;
						}	
					}
				  }
				  $total_report_amount=$report_amount+$total_tax;
				?>
				 </td>
				  <td class="description"><?php echo $diagnosis_requst_data->report_description;?></td>
				  <td class="description"> <?php echo $diagnosis_requst_data->status;?></td>
				   <td class="action">
					     <a href="?dashboard=user&page=diagnosis&tab=adddiagnosis&action=insert&patient_id=<?php echo $diagnosis_requst_data->patient_id;?>&report_type=<?php echo $diagnosis_requst_data->report_type;?>&cost=<?php echo $report_amount;?>&total_tax=<?php echo $total_tax;?>&report_cost=<?php echo $total_report_amount;?>" class="btn btn-success margin_bottom_5px"><?php  _e( 'Upload Diagnosis Report', 'hospital_mgt' ) ;?> </a>
						 
						<a href="#" class="btn btn-info update_dagnosis_report" priscription_id="<?php echo $diagnosis_requst_data->priscription_id;?>" report_status="<?php echo $diagnosis_requst_data->status; ?>" data-toggle="modal"  data-target="#myModal_dagnosis_report"> <?php _e('Update Status ','hospital_mgt');?></a>	
					</td>
				   </tr>
					<?php }
       				?>
					</tbody>
				</table><!--END DIGNOSIS REPORT REQUEST TABLE-->
            </div><!--END TABLE RESONSIVE DIV-->
        </div><!--END PANEL BODY DIV-->
		<!----------Update status PopUP------------->
		<div class="modal fade" id="myModal_dagnosis_report" role="dialog" style="overflow:scroll;"><!--START MODAL FADE DIV-->
			<div class="modal-dialog modal-lg"><!--START MODAL DiaLOG DIV-->
				<div class="modal-content"><!--START MODAL CONTENT DIV-->
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h3 class="modal-title"><?php _e('Update Report Status','hospital_mgt');?></h3>
					</div>
					<div class="modal-body"><!--START MODAL BODY DIV-->
					<form name="dagnosis_report_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal" id="dagnosis_report_form"><!--START DIGNOSIS STATUS FROM-->
					
							<input type="hidden" name="pescription_id" class="pescription" value=""  />
							<input type="hidden" name="action" value="MJ_hmgt_update_diagnosis_report_status_function">
								<div class="form-group">
									<label class="col-sm-2 control-label" for="installment_amount"><?php _e('Report Status','hospital_mgt');?><span class="require-field"></span></label>
									<div class="col-sm-8">
										<select name="report_status" id="" class="form-control report_status">
						                   <option value="Pending" class="validate[required]"><?php _e('Pending','hospital_mgt');?></option>
						                   <option value="Processing" class="validate[required]"><?php _e('Processing','hospital_mgt');?></option>
						                   <option value="Completed" class="validate[required]"><?php _e('Completed','hospital_mgt');?></option>
						
					                    </select>
									</div>
								</div>
																			
							<div class="col-sm-offset-3 col-sm-8">
								<input type="submit" value="<?php if($edit){ _e('Update Status','hospital_mgt'); }else{ _e('Update Status','hospital_mgt');}?>" name="update_status" class="btn btn-success"/>
							</div>
					</form><!--END Diagnosis STATUS FORM-->
					</div><!--END MODAL BODY DIV-->
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php  _e('Close','hospital_mgt');?></button>
					</div>
				</div><!--END CONTENT FADE DIV-->
			</div><!--END DiaLOG FADE DIV-->
		</div><!--END MODAL FADE DIV-->
		<?php }
    ?>
	</div>
</div>
<?php ?>
<!----------ADD Outpatient------------->
<div class="modal fade" id="myModal_add_outpatient" role="dialog" style="overflow:scroll;"><!--START MODAL FADE DIV-->
    <div class="modal-dialog modal-lg"><!--START MODAL DiaLOG DIV-->
      <div class="modal-content"><!--START MODAL CONTENT DIV-->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title"><?php _e('Add Outpatient','hospital_mgt');?></h3>
        </div>
			<div class="modal-body"><!--START MODAL BODY DIV-->
			 <?php 
				  $role='patient';
				   $patient_type='outpatient';
					 $lastpatient_id=MJ_hmgt_get_lastpatient_id($role);
					 $nodate=substr($lastpatient_id,0,-4);
					 $patientno=substr($nodate,1);
					 $patientno+=1;
					 $newpatient='P'.$patientno.date("my");
			   ?>
				<form name="out_patient_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal" id="doctor_form_outpatient_popup_form_percription" enctype="multipart/form-data">
				<input type="hidden" name="action" value="MJ_hmgt_save_outpatient_popup_form_template">
								
				<input type="hidden" name="role" value="<?php echo $role;?>"  />
				<input type="hidden" name="patient_type" value="<?php echo $patient_type;?>"  />
				<div class="header">	
					<h3 class="first_hed"><?php _e('Personal Information','hospital_mgt');?></h3>
					<hr>
				</div>	
				<div class="form-group">
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="roll_id"><?php _e('Patient Id','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="patient_id" class="form-control validate[required]" type="text" 
						value="<?php  echo $newpatient;?>" readonly name="patient_id">
					</div>
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="first_name"><?php _e('First Name','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="first_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="middle_name"><?php _e('Middle Name','hospital_mgt');?></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" type="text" maxlength="50" value="" name="middle_name">
					</div>
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="last_name"><?php _e('Last Name','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="" name="last_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="birth_date"><?php _e('Date of birth','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input class="form-control validate[required] birth_date" type="text"  name="birth_date" 
						value="" readonly>
					</div>
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="blood_group"><?php _e('Blood Group','hospital_mgt');?></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						
						<select id="blood_group" class="form-control" name="blood_group">
						<option value=""><?php _e('Select Blood Group','hospital_mgt');?></option>
						<?php
						$userblood=0;
						foreach(MJ_hmgt_blood_group() as $blood){ ?>
								<option value="<?php echo $blood;?>" <?php selected($userblood,$blood);  ?>><?php echo $blood; ?> </option>
						<?php } ?>
					</select>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
					<?php $genderval = "male" ?>
						<label class="radio-inline">
						 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php _e('Male','hospital_mgt');?>
						</label>
						<label class="radio-inline">
						  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','hospital_mgt');?> 
						</label>
					</div>
				</div>
				<div class="header">
					<h3><?php _e('HomeTown Address Information','hospital_mgt');?></h3>
					<hr>
				</div>
				<div class="form-group">
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="address"><?php _e('Home Town Address','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="address" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="150"  name="address" 
						value="">
					</div>
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="city_name"><?php _e('City','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" type="text" maxlength="50" name="city_name" 
						value="">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="state_name"><?php _e('State','hospital_mgt');?></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" type="text" maxlength="50" name="state_name" 
						value="">
					</div>
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="state_name"><?php _e('Country','hospital_mgt');?></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="country_name" class="form-control validate[custom[city_state_country_validation]]" type="text" maxlength="50" name="country_name" 
						value="">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="zip_code"><?php _e('Zip Code','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" type="text" maxlength="15" name="zip_code" 
						value="">
					</div>
				</div>
				<div class="header">
					<h3><?php _e('Contact Information','hospital_mgt');?></h3>
					<hr>
				</div>
				<div class="form-group">
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label  " for="mobile"><?php _e('Mobile Number','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 margin_bottom_5px">
					<input type="text" value="<?php if(isset($_POST['phonecode'])){ echo $_POST['phonecode']; }else{ ?>+<?php echo MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); }?>"  class="form-control  validate[required] onlynumber_and_plussign" name="phonecode" maxlength="5">
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 has-feedback">
						<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="" name="mobile">
					</div>
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="phone"><?php _e('Phone','hospital_mgt');?></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="phone" class="form-control validate[custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="" name="phone">
					</div>
				</div>
				<div class="header">
					<h3><?php _e('Login Information','hospital_mgt');?></h3>
					<hr>
		        </div>
				<div class="form-group">
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label " for="email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
						value="">
					</div>
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="username"><?php _e('User Name','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="username" class="form-control validate[required,custom[username_validation]]" type="text" maxlength="30" name="username" 
						value="">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="password"><?php _e('Password','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
						<input id="password" class="form-control validate[required,minSize[8]]" type="password"  maxlength="12" name="password" value="">
					</div>
				</div>
				<div class="header">
					<h3><?php _e('Other Information','hospital_mgt');?></h3>
					<hr>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="doctor"><?php _e('Assign Doctor','hospital_mgt');?></label>
					<div class="col-sm-3">
						
						<select name="doctor" id="doctor" class="form-control">
						
						<option ><?php _e('select Doctor','hospital_mgt');?></option>
						<?php
						 $doctorid=0;
						$get_doctor = array('role' => 'doctor');
							$doctordata=get_users($get_doctor);
							 if(!empty($doctordata))
							 {
								foreach($doctordata as $retrieved_data){?>
								<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($doctorid,$retrieved_data->ID);?>><?php echo $retrieved_data->display_name;?> - <?php echo MJ_hmgt_doctor_specialization_title($retrieved_data->ID); ?></option>
								<?php }
							 }?>
							 
						</select>
					 </div>
				</div>
			
				<div class="form-group">
						<label class="col-sm-2 control-label" for="symptoms"><?php _e('Symptoms','hospital_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3 multiselect_validation_symtoms margin_bottom_5px">
								<select class="form-control symptoms_list" multiple="multiple" name="symptoms[]" id="symptoms">					
								<?php 
								$user_object=new MJ_hmgt_user();
								$symptoms_category = $user_object->getPatientSymptoms();
								
								if(!empty($symptoms_category))
								{
									foreach ($symptoms_category as $retrive_data)
									{
										
										?>
										<option value="<?php echo $retrive_data->ID; ?>"><?php echo $retrive_data->post_title; ?></option>
										<?php
									}
								}
								?>					
								</select>
								<br>					
							</div>
								<div class="col-sm-2"><button id="addremove" model="symptoms"><?php _e('Add Or Remove','hospital_mgt');?></button></div>
					</div>					
				<div class="form-group">
					<label class="col-sm-2 control-label" for="photo"><?php _e('Image','hospital_mgt');?></label>
						<div class="col-sm-3">
							<input type="hidden" id="hmgt_user_avatar_url" class="form-control" name="hmgt_user_avatar_url" readonly 
							/>
							<input type="hidden" name="hidden_upload_user_avatar_image" 
							>
							 <input id="upload_user_avatar_image" name="upload_user_avatar_image" type="file" class="form-control file" value="<?php _e( 'Upload image', 'hospital_mgt' ); ?>" />
					</div>
					<div class="clearfix"></div>
					
					<div class="col-sm-offset-2 col-sm-8">
							 <div id="upload_user_avatar_preview" >
								<img class="image_preview_css" alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">
							</div>
					</div>
				</div>
				<div class="col-sm-offset-2 col-sm-8">
					
					<input type="submit" value="<?php _e('Save Patient','hospital_mgt');?>" name="save_outpatient" class="btn btn-success symptoms_alert"/>
				</div>
			    </form>
            </div><!--END MODAL BODY DIV-->
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php  _e('Close','hospital_mgt');?></button>
		  
        </div>
    </div><!--END MODAL CONTENT DIV-->
 </div><!--END MODAL DiaLOG DIV-->
</div><!--END MODAL FADE DIV-->