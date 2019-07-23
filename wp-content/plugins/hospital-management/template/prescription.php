<?php 
MJ_hmgt_browser_javascript_check();
$obj_var=new MJ_hmgt_prescription();
$obj_treatment=new MJ_hmgt_treatment();
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
//SAVE Prescription DATA
if(isset($_POST['save_prescription']))
{

	if($_REQUEST['action']=='edit')
	{
		$result=$obj_var->hmgt_add_prescription($_POST);
		if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=prescription&tab=prescriptionlist&message=2');
			}
	}
	else
	{
			$result=$obj_var->hmgt_add_prescription($_POST);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=prescription&tab=prescriptionlist&message=1');
				}
	}
		
}
		
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
			
			$result=$obj_var->delete_prescription(MJ_hmgt_id_decrypt($_REQUEST['prescription_id']));
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=prescription&tab=prescriptionlist&message=3');
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
}	
$active_tab = isset($_GET['tab'])?$_GET['tab']:'prescriptionlist';	
?>
  <!-- POP up code -->
  <div class="popup-bg" style="z-index:100000 !important;">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"></div>
			<div class="prescription_content"></div>    
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->	
<script type="text/javascript">
jQuery(document).ready(function($) {
	jQuery('#prescription_list').DataTable({
		"responsive": true,
		"order": [[ 0, "Desc" ]],
		"aoColumns":[
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
<div class="panel-body panel-white"><!-- START PANEL BODY DIV-->
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		  <li class="<?php if($active_tab=='prescriptionlist'){?>active<?php }?>">
			  <a href="?dashboard=user&page=prescription&tab=prescriptionlist">
				 <i class="fa fa-align-justify"></i> <?php _e('Prescription List', 'hospital_mgt'); ?></a>
			  </a>
		  </li>
		  <li class="<?php if($active_tab=='addprescription'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{?>
				<a href="?dashboard=user&page=prescription&tab=addprescription&action=edit&prescription_id=<?php if(isset($_REQUEST['prescription_id'])) echo $_REQUEST['prescription_id'];?>"" class="tab <?php echo $active_tab == 'addprescription' ? 'active' : ''; ?>">
				 <i class="fa fa"></i> <?php _e('Edit Prescription', 'hospital_mgt'); ?></a>
				 <?php }
				else
				{
					if($user_access['add']=='1')
					{
					?>
						<a href="?dashboard=user&page=prescription&tab=addprescription&&action=insert" class="tab <?php echo $active_tab == 'addprescription' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php _e('Add Prescription', 'hospital_mgt'); ?></a>
				<?php 
					}
				}
			?>  
			</li>	
	</ul>
	<div class="tab-content"><!-- START TAB CONTENT DIV-->
	<?php 
     if($active_tab=='prescriptionlist'){
	?>
    <div class="tab-pane fade active in" id="prescription"><!-- START TAB PANE DIV-->
		<div class="panel-body"><!-- START PANEL BODY DIV-->
            <div class="table-responsive"><!-- START TABLE RESPONSIVE DIV-->
			    <table id="prescription_list" class="display dataTable " cellspacing="0" width="100%"><!-- START PRESCRIPTION LIST TABLE-->
					<thead>
						<tr>
							<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Patient ID', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>             
							<th> <?php _e( 'Type', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Treatment', 'hospital_mgt' ) ;?></th>
							<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
						</tr>
				    </thead>
					<tfoot>
						<tr>
							<th><?php  _e( 'Date', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Patient ID', 'hospital_mgt' ) ;?></th>
							<th> <?php _e( 'Patient Name', 'hospital_mgt' ) ;?></th>  
							<th> <?php _e( 'Type', 'hospital_mgt' ) ;?></th>							
							<th> <?php _e( 'Treatment', 'hospital_mgt' ) ;?></th>
							<th><?php  _e( 'Action', 'hospital_mgt' ) ;?></th>
						</tr>
					</tfoot>
					<tbody>
					<?php 
					
					$id=get_current_user_id();
					$role=MJ_hmgt_get_current_user_role();
					if($role == 'patient')
					{						
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{ 
						   $prescriptiondata=$obj_var->get_all_prescription_patientid($id);
						}
						else
						{
							$prescriptiondata=$obj_var->get_all_prescription();
						}
					}
					elseif($obj_hospital->role == 'doctor') 
					{
						$own_data=$user_access['own_data'];
		                if($own_data == '1')
		                { 
						   $prescriptiondata=$obj_var->get_doctor_all_prescription_id($id);
						}
						else
						{
						    $prescriptiondata=$obj_var->get_all_prescription();
						}
					} 
					elseif($obj_hospital->role == 'nurse') 
					{
						$own_data=$user_access['own_data'];
		                if($own_data == '1')
		                { 
						   $prescriptiondata=$obj_var->get_nurse_all_prescription_id($id);
						}
						else
						{
						    $prescriptiondata=$obj_var->get_all_prescription();
						}
					} 
					elseif($obj_hospital->role == 'laboratorist' || $obj_hospital->role == 'pharmacist' || $obj_hospital->role == 'accountant' || $obj_hospital->role == 'receptionist') 
					{
						$own_data=$user_access['own_data'];
		                if($own_data == '1')
		                { 
						   $prescriptiondata=$obj_var->get_all_prescription_id($id);
						}
						else
						{
						    $prescriptiondata=$obj_var->get_all_prescription();
						}
					} 
					
					if(!empty($prescriptiondata))
					{
						foreach ($prescriptiondata as $retrieved_data){ ?>
					    <tr>
							
							<td class="name"><a href="?dashboard=user&page=prescription&action=edit&prescription_id=<?php echo $retrieved_data->priscription_id;?>"><?php echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->pris_create_date));?></a></td>
							<td class="patient">
							<?php 
								echo $patient_id=get_user_meta($retrieved_data->patient_id, 'patient_id', true);
							?>
							</td>
							<td class="patient">
							   <?php 	
							    $patient = MJ_hmgt_get_user_detail_byid( $retrieved_data->patient_id);
							    echo  $patient['first_name']." ".$patient['last_name'];
							    ?>
							</td>
						   	<td class=""><?php 
							if(!empty($retrieved_data->prescription_type))
							{
								echo ucfirst($retrieved_data->prescription_type); 
							}
							else
							{
								echo '-'; 
							}
								?> </td>
							<td class="treatment"><?php if(!empty($retrieved_data->teratment_id)){ echo $treatment=$obj_treatment->get_treatment_name($retrieved_data->teratment_id); }else{ echo '-'; } ?></td>
							
							<td class="action">
							<a href="#" class="btn btn-primary view-prescription" id="<?php echo $retrieved_data->priscription_id;?>" prescription_type="<?php echo $retrieved_data->prescription_type; ?>"> <?php _e('View','hospital_mgt');?></a> 
							<?php
							if($user_access['edit']=='1')
							{
							?>
								<a href="?dashboard=user&page=prescription&tab=addprescription&action=edit&prescription_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->priscription_id);?>" class="btn btn-info"> <?php _e('Edit', 'hospital_mgt' ) ;?></a>
							<?php
							}
							if($user_access['delete']=='1')
							{
							?>	
								<a href="?dashboard=user&page=prescription&tab=prescriptionlist&action=delete&prescription_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->priscription_id);?>" class="btn btn-danger" 
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
				</table><!-- END PANEL BODY DIV-->
 		    </div><!-- END TABLE RESPONSIVE DIV-->
		</div><!-- END PANEL BODY DIV-->
		
	</div><!-- END TAB PANE DIV-->
	<?php 
	} ?> 
    <script type="text/javascript">
	jQuery(document).ready(function($) {		
		jQuery('#prescription_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
		jQuery('#doctor_form_outpatient_popup_form_percription').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
		jQuery('#medication_id').select2();
		jQuery(".medication_listss").select2();		
		$('#symptoms').multiselect({
		nonSelectedText :'<?php _e('Select Symptoms','hospital_mgt'); ?>',
		includeSelectAllOption: true,
		selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
	 });
	 
	 $('#report_type').multiselect({
		nonSelectedText : '<?php _e('Select Report Name','hospital_mgt'); ?>',
		includeSelectAllOption: true,
		selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
	 });
	
		//username not  allow space validation
	$('#username').keypress(function( e ) {
       if(e.which === 32) 
         return false;
    });
		
	//birth date validation
		$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
		  $('.birth_date').datepicker({
		 endDate: '+0d',
			autoclose: true
			 
	   }); 
	
	    //add outpatient pop up//
	 
	    $('#doctor_form_outpatient_popup_form_percription').on('submit', function(e) {
		e.preventDefault();
	
		var valid = $('#doctor_form_outpatient_popup_form_percription').validationEngine('validate');
		if (valid == true) {
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
			
					if(data!="")
					{ 
						if(data == 2)
						{	
							alert('<?php _e('Sorry, only JPG,JPEG,PNG,GIF,DOC,PDF and ZIP files are allowed.','hospital_mgt'); ?>');
						}
						else
						{
						   var json_obj = $.parseJSON(data);
							
							$('#doctor_form_outpatient_popup_form_percription').trigger("reset");
							$('#patient_id').append(json_obj[0]);
							$('#upload_user_avatar_preview').html('<img alt="" src="<?php echo get_option( 'hmgt_patient_thumb' ); ?>">');
							$('#hmgt_user_avatar_url').val('');
							
							$('.modal').modal('hide');
						}							
					} 				
			},
			error: function(data){
			}
		})
		
		}
	}); 
	$("body").on("click", ".remove_cirtificate", function()
	{
	    alert("<?php _e('Do you really want to delete this record ?','hospital_mgt');?>");
		$(this).parent().parent().remove();
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
	});
   </script>
    <?php 
	if($active_tab=='addprescription')
	{
	?>

	<div class="tab-pane fade active in" id="add_Prescription"><!-- START TAB PANE DIV-->
		<?php 

		$obj_medicine = new MJ_hmgt_medicine();
		$medicinedata=$obj_medicine->get_all_medicine_in_stock();
		if(!empty($medicinedata))
		{
			$medicine_array = array ();
			foreach ($medicinedata as $retrieved_data){
				$medicine_array [] = $retrieved_data->medicine_name;
			}
		}
		
		$obj_treatment=new MJ_hmgt_treatment();
		$obj_var=new MJ_hmgt_prescription();
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_var->get_prescription_data(MJ_hmgt_id_decrypt($_REQUEST['prescription_id']));
			?>
		<style>
		<?php
		if($result->prescription_type == 'report' )
		{
		?>
			#prescription_report_div
			{
				display:block;	
			}
			#tretment_div
			{
				display:none;	
			}		
		<?php
		}
		if($obj_hospital->role == 'doctor') 
		{
		?>
			#doctor_div_css
			{
				display:none;	
			}	
		<?php
		}
		else
		{
		?>
			#doctor_div_css
			{
				display:block;	
			}	
		<?php
		}
		?>
		</style>
		<?php
		} ?>
		
        <div class="panel-body"><!-- START PANEL BODY DIV-->
			<form name="prescription_form" action="" method="post" class="form-horizontal" id="prescription_form"><!-- STRT Prescription FORM-->
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="prescription_id" value="<?php if(isset($_REQUEST['prescription_id'])) echo MJ_hmgt_id_decrypt($_REQUEST['prescription_id']);?>"  />
			<?php 
			if($obj_hospital->role == 'doctor') 
			{
				$user = wp_get_current_user();
			?>
				<input type="hidden" name="doctor_id" value="<?php echo $user->ID; ?>">
			<?php
			}
			else
			{	
			?>
			<div class="form-group" >
				<label class="col-sm-2 control-label" for="doctor"><?php _e('Doctor','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8 margin_bottom_5px">
					<?php if($edit){ $doctorid=$result->doctor_id; }elseif(isset($_POST['doctor_id'])){ $doctorid=$_POST['doctor_id']; }else{$doctorid=''; } ?>
					<select name="doctor_id" id="" class="form-control validate[required]">					
					<option ><?php _e('Select Doctor','hospital_mgt');?></option>
					<?php 
						if($obj_hospital->role == 'doctor') 
						{
							$get_doctor = get_current_user_id();
							$doctordata=array();
							$doctordata[]=get_userdata($get_doctor);
						}
						else
						{
							$get_doctor = array('role' => 'doctor');
							$doctordata=get_users($get_doctor);
						}					
					
						 if(!empty($doctordata))
						 {
							foreach($doctordata as $retrieved_data)
							{
							?>
							<option value="<?php echo $retrieved_data->ID; ?>" <?php selected($doctorid,$retrieved_data->ID);?>><?php echo $retrieved_data->display_name;?> - <?php echo MJ_hmgt_doctor_specialization_title($retrieved_data->ID); ?></option>
							<?php 
							}
						 }?>
						 
					</select>
				</div>				
			</div>
			<?php
			}
			?>
				<div class="form-group">				
					<label class="col-sm-2 control-label" for="patient_id"><?php _e('Patient','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8 margin_bottom_5px">
						<?php if($edit){ $patient_id1=$result->patient_id; }elseif(isset($_REQUEST['patient_id'])){$patient_id1=$_REQUEST['patient_id'];}else{ $patient_id1="";}?>
						<select name="patient_id" class="form-control validate[required]" id="patient_id">
						<option value=""><?php _e('Select Patient','hospital_mgt');?></option>
						<?php 
							
							$patients = MJ_hmgt_patientid_list();
							
							if(!empty($patients))
							{
							foreach($patients as $patient)
							{
								echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['first_name'].' '.$patient['last_name'].' - '.$patient['patient_id'].'</option>';
							
							}
							}?>
						</select>
						
					</div>
					<!--ADD OUT PATIENT POPUP BUTTON -->
					<div class="col-sm-2">
					
					<a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal_add_outpatient"> <?php _e('Add Outpatient','hospital_mgt');?></a>
					
					</div>
				</div>
				<div class="form-group convert_patient">
				</div>
				<div class="form-group">			
				<label class="col-sm-2 control-label" for="patient_id"><?php _e('Type','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<?php
					
					if(isset($_REQUEST['type']))
					{
						$prescription_type = $_REQUEST['type']; 
					?>
						<style>						
						#prescription_report_div
						{
							display:block;	
						}
						#tretment_div
						{
							display:none;	
						}								
						</style>
						<?php
					}
					else
					{
						$prescription_type = "treatment";
					}
					if($edit){ $prescription_type=$result->prescription_type; }elseif(isset($_POST['prescription_type'])) {$prescription_type=$_POST['prescription_type'];}?>
					<label class="radio-inline">
					 <input type="radio" value="treatment" class="tog validate[required]" name="prescription_type"  <?php  checked( 'treatment', $prescription_type);  ?>/><?php _e('Treatment','hospital_mgt');?>
					</label>
					<label class="radio-inline">
					  <input type="radio" value="report" class="tog validate[required]" name="prescription_type"  <?php  checked( 'report', $prescription_type);  ?>/><?php _e('Report','hospital_mgt');?> 
					</label>
				</div>
			</div>
			<div id="tretment_div">			
				<div class="form-group">
					<label class="col-sm-2 control-label" for="treatment_id"><?php _e('Treatment','hospital_mgt');?><span class="require-field">*</span></label>
					<?php if($edit){ $treatmentval=$result->teratment_id; }elseif(isset($_POST['treatment_id'])){$treatmentval=$_POST['treatment_id'];}else{ $treatmentval="";}?>
					<div class="col-sm-8">
						<?php $treatment_data=$obj_treatment->get_all_treatment();?>
						
						<select name="treatment_id" class="form-control validate[required]" name="treatment_id">
						<option value=""><?php _e('Select Treatment','hospital_mgt');?></option>
						<?php  if(!empty($treatment_data))
							   {
									foreach($treatment_data as $retrieved_data){ ?>
										<option value="<?php echo $retrieved_data->treatment_id;?>" <?php selected($treatmentval,$retrieved_data->treatment_id); ?> > <?php echo $retrieved_data->treatment_name;?></option>
									<?php }
							   }?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="case_history"><?php _e('Case History','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<textarea id="case_history" maxlength="150" class="form-control validate[required,custom[address_description_validation]]" name="case_history"><?php if($edit){echo $result->case_history; }elseif(isset($_POST['case_history'])) echo $_POST['case_history']; ?></textarea>
					</div>
				</div>
			    <?php 
				if($edit){
						$all_medicine_list=json_decode($result->medication_list);
					}
					else
					{
						if(isset($_POST['medication'])){
							
							$all_data=$obj_var->get_medication_records($_POST);
							$all_medicine_list=json_decode($all_data);
						}
						
							
					}
					if(!empty($all_medicine_list))
					{
						$id=0;
						foreach($all_medicine_list as $entry)
						{
						?>
				<div class="form-group">		
					<label class="col-sm-2 control-label" for="medication"><?php _e('Medication','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3">
					<select name="medication[]" class="form-control medication_listss">					
					<?php 
					$medicinedata=$obj_medicine->get_all_medicine_in_stock();
					if(!empty($medicinedata))
					{
						$medicine_array = array ();
						foreach ($medicinedata as $retrieved_data){
							$medicine_array [] = $retrieved_data->medicine_name;
							echo '<option data-tokens="'.$retrieved_data->medicine_name.'" value="'.$retrieved_data->medicine_id.'" '.selected($entry->medication_name,$retrieved_data->medicine_id).'>'.$retrieved_data->medicine_name.'</option>';
						}
					}
					$id++;
					?>
					</select>
					</div>
					<div class="col-sm-1 margin_bottom_5px padding_left_right_15px" style="padding:0px">
						<select name="times1[]" class="form-control validate[required]">
							<option value=""><?php _e('Frequency','hospital_mgt');?></option>
							<option value="1" <?php echo selected($entry->time,'1')?>>1</option>
							<option value="2" <?php echo selected($entry->time,'2')?>>2</option>
							<option value="3" <?php echo selected($entry->time,'3')?>>3</option>
							<option value="4" <?php echo selected($entry->time,'4')?>>4</option>
							<option value="5" <?php echo selected($entry->time,'5')?>>5</option>
							<option value="6" <?php echo selected($entry->time,'6')?>>6</option>
							<option value="7" <?php echo selected($entry->time,'7')?>>7</option>
							<option value="8" <?php echo selected($entry->time,'8')?>>8</option>					
						</select>
					</div>
					<div class="col-sm-1 margin_bottom_5px width_50_per" style="padding-right: 0px;"><input id="days" class="form-control validate[required]" type="number" step="1" maxlength="2" min="0" value="<?php echo $entry->per_days;?>" name="days[]" placeholder="<?php _e('No Of','hospital_mgt');?>"></div>
					<div class="col-sm-1 margin_bottom_5px width_50_per" style="padding-right: 0px;">
						<select name="time_period[]" class="form-control validate[required]">				
							<option value="day" <?php echo selected($entry->time_period,'day')?>><?php _e('Day','hospital_mgt');?></option>
							<option value="week" <?php echo selected($entry->time_period,'week')?>><?php _e('Week','hospital_mgt');?></option>
							<option value="month" <?php echo selected($entry->time_period,'month')?>><?php _e('Month','hospital_mgt');?></option>
							<option value="hour" <?php echo selected($entry->time_period,'hour')?>><?php _e('Hour','hospital_mgt');?></option>
						</select>
					</div>
					<div class="col-sm-2 margin_bottom_5px">
						<select name="takes_time[]" class="form-control validate[required]">
							<option value=""><?php _e('When to take','hospital_mgt');?></option>
							<option value="before_breakfast" <?php echo selected($entry->takes_time,'before_breakfast')?>><?php _e('Before Breakfast','hospital_mgt');?></option>
							<option value="after_meal" <?php echo selected($entry->takes_time,'after_meal')?>><?php _e('After Meal','hospital_mgt');?></option>
							<option value="before_meal" <?php echo selected($entry->takes_time,'before_meal')?>><?php _e('Before Meal','hospital_mgt');?></option>
							<option value="night" <?php echo selected($entry->takes_time,'night')?>><?php _e('Night ','hospital_mgt');?></option>
						</select>
					</div>
					<div class="col-sm-1">
						<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
						</button>
					</div>
				</div>				
						<?php 
						}
					}
					?>
				<div id="invoice_medicine_entry">
				<?php
				if(!$edit)
				{
				?>
				<div class="form-group">		
					<label class="col-sm-2 control-label" for="medication"><?php _e('Medication','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3">
					<select name="medication[]" id="medication_id" class="form-control">					
					<?php 
					$medicinedata=$obj_medicine->get_all_medicine_in_stock();
					if(!empty($medicinedata))
					{
						$medicine_array = array ();
						foreach ($medicinedata as $retrieved_data){
							$medicine_array [] = $retrieved_data->medicine_name;
							echo '<option data-tokens="'.$retrieved_data->medicine_name.'" value="'.$retrieved_data->medicine_id.'">'.$retrieved_data->medicine_name.'</option>';
						}
					}
					?>
					</select>
					</div>
					<div class="col-sm-1 margin_bottom_5px padding_left_right_15px" style="padding:0px">
						<select name="times1[]" class="form-control validate[required]">
							<option value=""><?php _e('Frequency','hospital_mgt');?></option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>					
						</select>
					</div>
					<div class="col-sm-1 margin_bottom_5px width_50_per" style="padding-right: 0px;"><input id="days" class="form-control validate[required]" type="number" step="1" maxlength="2" min="0" value="" name="days[]" placeholder="<?php _e('No Of','hospital_mgt');?>"></div>
					<div class="col-sm-1 margin_bottom_5px width_50_per" style="padding-right: 0px;">
						<select name="time_period[]" class="form-control validate[required]">				
							<option value="day"><?php _e('Day','hospital_mgt');?></option>
							<option value="week"><?php _e('Week','hospital_mgt');?></option>
							<option value="month"><?php _e('Month','hospital_mgt');?></option>
							<option value="hour"><?php _e('Hour','hospital_mgt');?></option>
						</select>
					</div>
					<div class="col-sm-2">
						<select name="takes_time[]" class="form-control validate[required]">
							<option value=""><?php _e('When to take','hospital_mgt');?></option>
							<option value="before_breakfast"><?php _e('Before Breakfast','hospital_mgt');?></option>
							<option value="after_meal"><?php _e('After Meal','hospital_mgt');?></option>
							<option value="before_meal"><?php _e('Before Meal','hospital_mgt');?></option>
							<option value="night"><?php _e('Night ','hospital_mgt');?></option>
						</select>
					</div>
					<div class="col-sm-1">
					
					</div>
				</div>
				<?php
				}
				?>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="invoice_entry"></label>
					<div class="col-sm-3">				
						<p  id="add_new_medicine_entry" class="btn btn-default btn-sm btn-icon icon-left"   name="add_new_entry" >
						<?php _e('Add Medicine Data','hospital_mgt'); ?>
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="note"><?php _e('Note','hospital_mgt');?></label>
					<div class="col-sm-8">
						<textarea id="note" class="form-control validate[custom[address_description_validation]]" maxlength="150" name="note"><?php if($edit){echo $result->treatment_note; }elseif(isset($_POST['note'])) echo $_POST['note']; ?> </textarea>
					</div>
				</div>
				<?php 
				if($edit){
					$all_entry=json_decode($result->custom_field);
				}
				else
				{
					if(isset($_POST['custom_label'])){
							
						$all_data=$obj_var->get_entry_records($_POST);
						$all_entry=json_decode($all_data);
					}
				
						
				}
				if(!empty($all_entry))
				{
					foreach($all_entry as $entry){
						?>
							<div id="custom_label">
								<div class="form-group">
								<label class="col-sm-2 control-label" for="income_entry"><?php _e('Custom Field','hospital_mgt');?></label>
								<div class="col-sm-2 margin_bottom_5px">
									<input id="income_amount" class="form-control text-input validate[custom[onlyLetter_specialcharacter]]" maxlength="30" type="text" value="<?php echo $entry->label;?>" name="custom_label[]" placeholder="Field lable">
								</div>
								<div class="col-sm-4 margin_bottom_5px">
									<input id="income_entry" class="form-control text-input validate[custom[address_description_validation]]" type="text" maxlength="50" value="<?php echo $entry->value;?>" name="custom_value[]" placeholder="Field value">
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
				else {
				?>
				<div id="custom_label">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="income_entry"><?php _e('Custom Field','hospital_mgt');?></label>
						<div class="col-sm-2 margin_bottom_5px">
							<input id="income_amount" class="form-control text-input validate[custom[onlyLetter_specialcharacter]]" maxlength="30" type="text" value="" name="custom_label[]" placeholder="Field lable">
						</div>
						<div class="col-sm-4 margin_bottom_5px">
							<input id="income_entry" class="form-control text-input validate[custom[address_description_validation]]" type="text" maxlength="50" value="" name="custom_value[]" placeholder="Field value">
						</div>						
						<div class="col-sm-2 margin_bottom_5px">
							<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php _e('Delete','hospital_mgt');?></i>
						</button>
						</div>
					</div>	
				</div>
				<?php }?>
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="income_entry"></label>
					<div class="col-sm-3">
						
						<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_custom_label()"><?php _e('Add More Field','hospital_mgt'); ?>
						</button>
					</div>
				</div>
			</div>
			
			<div id="prescription_report_div">			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="patient"><?php _e('Report Type','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8 multiselect_validation_Report margin_bottom_5px">
					<select class="form-control reportlist_list report_type" multiple="multiple" name="report_type[]" id="report_type">
					<?php 
					$report_type=new MJ_hmgt_dignosis();
					$operation_array =$report_type->get_all_report_type();
					if(!empty($operation_array))
					{
						foreach ($operation_array as $retrive_data)
						{
							$report_type_data=$retrive_data->post_title;
							$report_type_array=json_decode($report_type_data);					
							$report_type=explode(",",$result->report_type);
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
				<div class="form-group">
					<label class="col-sm-2 control-label" ><?php _e('Report Description','hospital_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<textarea id="" maxlength="150" class="form-control validate[required,custom[address_description_validation]]" name="report_description"><?php if($edit){echo $result->report_description; }elseif(isset($_POST['report_description'])) echo $_POST['report_description']; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label " for="enable"><?php _e('Doctor Visiting Charge','hospital_mgt');?></label>
					<div class="col-sm-8">
						 <div class="checkbox">
							<label>
								<input id="doctor_visiting_charge" type="checkbox" value="1" name="doctor_visiting_charge" <?php if($edit){  if($result->doctor_visiting_charge == '1'){ echo 'checked'; } } ?>>
							</label>
						</div>				 
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label " for="enable"><?php _e('Doctor Consulting Charge','hospital_mgt');?></label>
					<div class="col-sm-8">
						 <div class="checkbox">
							<label>
								<input id="doctor_consulting_charge" type="checkbox" value="1" name="doctor_consulting_charge" <?php if($edit){  if($result->doctor_consulting_charge == '1'){ echo 'checked'; } }else{ echo 'checked'; } ?>>
							</label>
						</div>				 
					</div>
				</div>
			</div>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Create Prescription','hospital_mgt');}?>" name="save_prescription" class="btn btn-success"/>
				</div>
			
			</form><!-- END Prescription FORM-->
        </div><!-- END PANEL BODY DIV-->
	</div><!-- END TAB PANE DIV-->
</div><!-- END TAB CONTENTDIV-->
	<?php }?>
</div>
 <script>  
 var blank_custom_label ='';
   	$(document).ready(function() { 
   		blank_custom_label = $('#custom_label').html();
   		
   	}); 
	function add_custom_label()
   	{
   		$("#custom_label").append(blank_custom_label);
   		
   	}
function deleteParentElement(n){
	    alert("<?php _e('Do you really want to delete this record','hospital_mgt');?>");
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}

 </script> 
<?php ?>

 <!----------ADD Outpatient------------->
<div class="modal fade" id="myModal_add_outpatient" role="dialog" style="overflow:scroll;"><!-- START MODAL FADE DIV-->

    <div class="modal-dialog modal-lg"><<!-- START MODAL DIALOG DIV-->
      <div class="modal-content"><!-- START MODAL CONTENT DIV-->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title"><?php _e('Add Outpatient','hospital_mgt');?></h3>
        </div>
		<div id="message" class="updated below-h2 show_msg">
			<p>
			<?php _e('Sorry, only JPG, JPEG, PNG & GIF And BMP files are allowed.','hospital_mgt');?>
			</p>
		</div>
        <div class="modal-body"><!-- START MODAL BODY DIV-->
		 <?php 
	          $role='patient';
	           $patient_type='outpatient';
	             $lastpatient_id=MJ_hmgt_get_lastpatient_id($role);
				 $nodate=substr($lastpatient_id,0,-4);
				 $patientno=substr($nodate,1);
				 $patientno+=1;
				 $newpatient='P'.$patientno.date("my");
	       ?>
          <form name="out_patient_form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="form-horizontal" id="doctor_form_outpatient_popup_form_percription" enctype="multipart/form-data"><!-- START Outpatient FORM-->
        <input type="hidden" name="action" value="MJ_hmgt_save_outpatient_popup_form_template">
		
		<div class="header">	
					<h3 class="first_hed"><?php _e('Personal Information','hospital_mgt');?></h3>
					<hr>
	    </div>		
		<input type="hidden" name="role" value="<?php echo $role;?>"  />
		<input type="hidden" name="patient_type" value="<?php echo $patient_type;?>"  />
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
				<input class="form-control validate[required] birth_date " type="text"  name="birth_date" 
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
				<input id="address" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="150" name="address" 
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
				<input id="zip_code" class="form-control validate[required,custom[onlyLetterNumber]]" type="text" maxlength="15" name="zip_code" 
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
				<input id="username" class="form-control validate[required,custom[username_validation]]" maxlength="30" type="text"  name="username" 
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
			<div class="col-sm-3 margin_bottom_5px">
				
				<select name="doctor" id="doctor" class="form-control">
				
				<option ><?php _e('select Doctor','hospital_mgt');?></option>
				<?php
				 $doctorid=0;
					if($obj_hospital->role == 'doctor') 
					{
						$get_doctor = get_current_user_id();
						$doctordata=array();
						$doctordata[]=get_userdata($get_doctor);
					}
					else
					{
						$get_doctor = array('role' => 'doctor');
						$doctordata=get_users($get_doctor);
					}	
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
		<!--<div class="form-group">
			<label class="col-sm-2 control-label" for="medicine"><?php _e('Medicine','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea id="medicine" class="form-control validate[required]" name="medicine"> <?php //if($edit){ echo $user_info['medicine'];}elseif(isset($_POST['medicine'])) echo $_POST['medicine'];?></textarea>
			</div>
		</div>-->
		<div class="diagnosissnosis_div">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="diagnosis"><?php _e('Diagnosis Report','hospital_mgt');?></label>
				<div class="col-sm-3">
					<input type="file" class="form-control file dignosisreport" name="diagnosis[]">
				</div>
			</div>	
		</div>
		<div class="form-group">			
				<div class="col-sm-2">
				</div>
				<div class="col-sm-2">
					<input type="button" value="<?php _e('Add More Report','hospital_mgt') ?>" name="add_more_report" class="add_more_report_fronted btn btn-default">
				</div>
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
        </form><!-- END Outpatient FORM-->
		
        </div><!-- END MODAL BODY DIV-->
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php  _e('Close','hospital_mgt');?></button>
		  
        </div>
    </div><!-- END MODAL CONTENT DIV-->
    </div><!-- START MODAL DIAGLOG DIV-->
</div><!-- END MODAL FADE DIV-->