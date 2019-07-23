<?php 
MJ_hmgt_browser_javascript_check();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'noticelist';
//access right function
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
//SAVE NOTICE DATA
if(isset($_POST['save_notice']))
{
	if($_REQUEST['action']=='edit')
	{
		$args = array(
		  'ID'           => $_REQUEST['notice_id'],
		  'post_type' => $_REQUEST['notice_type'],
		  'post_title'   => MJ_hmgt_strip_tags_and_stripslashes($_REQUEST['notice_title']),
		  'post_content' =>  MJ_hmgt_strip_tags_and_stripslashes($_REQUEST['notice_content']),
		);
		$result1=wp_update_post( $args );
		$start_date = MJ_hmgt_get_format_for_db($_POST['start_date']);
        $end_date =  MJ_hmgt_get_format_for_db($_POST['end_date']);
		$result2=update_post_meta($_REQUEST['notice_id'], 'notice_for', implode(",",$_POST['notice_for']));
		$result3=update_post_meta($_REQUEST['notice_id'], 'start_date',$start_date);
		$result4=update_post_meta($_REQUEST['notice_id'], 'end_date',$end_date);
		
		$role=$_POST['notice_for'];
		$hmgt_sms_service_enable=0;
		if(isset($_POST['hmgt_sms_service_enable']))
		$hmgt_sms_service_enable = $_POST['hmgt_sms_service_enable'];
		if($hmgt_sms_service_enable)
		{
			$userdata = array();
			foreach($role as $role_data)
			{
				$current_role_users=get_users(array('role'=>$role_data));
				$userdata = array_merge($current_role_users, $userdata);
			}
			if(!empty($userdata))
			{
				$mail_id = array();
				foreach($userdata as $user)
				{
					$mail_id[]=$user->ID;
				}
			}
				foreach($mail_id as $user_id)
				{
					if(!empty(get_user_meta($user_id, 'phonecode',true))){ $phone_code=get_user_meta($user_id, 'phonecode',true); }else{ $phone_code='+'.MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); }
					
					$reciever_number = $phone_code.get_user_meta($user_id, 'mobile',true);
					
					$message_content = MJ_hmgt_strip_tags_and_stripslashes($_POST['sms_template']);
					$current_sms_service = get_option( 'hmgt_sms_service');
					if($current_sms_service == 'clickatell')
					{
						$clickatell=get_option('hmgt_clickatell_sms_service');
						$username = urlencode($clickatell['username']);
						$password = urlencode($clickatell['password']);
						$api_id = urlencode($clickatell['api_key']);
						$to_mob_number = $reciever_number;
						$message = urlencode($message_content);
						$send=file_get_contents("https://api.clickatell.com/http/sendmsg". "?user=$username&password=$password&api_id=$api_id&to=$to_mob_number&text=$message");
						
					}
					if($current_sms_service == 'twillo')
					{
						//Twilio lib
						require_once HMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
						$twilio=get_option( 'hmgt_twillo_sms_service');
						
						$account_sid = $twilio['account_sid']; //Twilio SID
						$auth_token = $twilio['auth_token']; // Twilio token
						$from_number = $twilio['from_number'];//My number
						$receiver = $reciever_number; //Receiver Number
						$message = $message_content; // Message Text
						//twilio object								
						$client = new Services_Twilio($account_sid, $auth_token);
						$message_sent = $client->account->messages->sendMessage(
						$from_number, // From a valid Twilio number
						$receiver, // Text this number
						$message
						);
					}		
				}
		}	
		if($result1 || $result2 || $result3 || $result4)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=event&tab=event_list&message=2');
		}
	}
	else
	{
		$post_id = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type' => $_REQUEST['notice_type'],
					'post_title' => MJ_hmgt_strip_tags_and_stripslashes($_REQUEST['notice_title']),
					'post_content' => MJ_hmgt_strip_tags_and_stripslashes($_REQUEST['notice_content'])
				) );
		
			 
			$start_date = MJ_hmgt_get_format_for_db($_POST['start_date']);
			$end_date =  MJ_hmgt_get_format_for_db($_POST['end_date']);
			$result=add_post_meta($post_id, 'notice_for',implode(",",$_POST['notice_for']));
			$result=add_post_meta($post_id, 'start_date',$start_date);
			$result=add_post_meta($post_id, 'end_date',$end_date);
			$result=add_post_meta($post_id, 'created_by',get_current_user_id());
			$role=$_POST['notice_for'];
			$hmgt_sms_service_enable=0;
			if(isset($_POST['hmgt_sms_service_enable']))
				$hmgt_sms_service_enable = $_POST['hmgt_sms_service_enable'];
			if($hmgt_sms_service_enable)
			{				
				$userdata = array();
				foreach($role as $role_data)
				{
					$current_role_users=get_users(array('role'=>$role_data));
					$userdata = array_merge($current_role_users, $userdata);
				}
				if(!empty($userdata))
				{
					$mail_id = array();
					foreach($userdata as $user)
					{
						$mail_id[]=$user->ID;
					}
				}
				
				foreach($mail_id as $user_id)
				{
					if(!empty(get_user_meta($user_id, 'phonecode',true))){ $phone_code=get_user_meta($user_id, 'phonecode',true); }else{ $phone_code='+'.MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); }
					
					$reciever_number = $phone_code.get_user_meta($user_id, 'mobile',true);
					
					$message_content = MJ_hmgt_strip_tags_and_stripslashes($_POST['sms_template']);
					$current_sms_service = get_option( 'hmgt_sms_service');
					if($current_sms_service == 'clickatell')
					{
						$clickatell=get_option('hmgt_clickatell_sms_service');
						$username = urlencode($clickatell['username']);
						$password = urlencode($clickatell['password']);
						$api_id = urlencode($clickatell['api_key']);
						$to_mob_number = $reciever_number;
						$message = urlencode($message_content);
						$send=file_get_contents("https://api.clickatell.com/http/sendmsg". "?user=$username&password=$password&api_id=$api_id&to=$to_mob_number&text=$message");					
					}
					if($current_sms_service == 'twillo')
					{
						//Twilio lib
						require_once HMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
						$twilio=get_option( 'hmgt_twillo_sms_service');
						$account_sid = $twilio['account_sid']; //Twilio SID
						$auth_token = $twilio['auth_token']; // Twilio token
						$from_number = $twilio['from_number'];//My number
						$receiver = $reciever_number; //Receiver Number
						$message = $message_content; // Message Text							
						//twilio object
						$client = new Services_Twilio($account_sid, $auth_token);
						$message_sent = $client->account->messages->sendMessage(
								$from_number, // From a valid Twilio number
								$receiver, // Text this number
								$message
						);
					
					}
					
				}
			}
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=event&tab=event_list&message=1');
			}
	}	
		
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
		$result=wp_delete_post(MJ_hmgt_id_decrypt($_REQUEST['notice_id']));
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=event&tab=event_list&message=3');
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
	
$active_tab = isset($_GET['tab'])?$_GET['tab']:'event_list';
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#hmgt_event').DataTable({
		"responsive": true,
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
<!--START POPUP CODE-->
<div class="popup-bg">
    <div class="overlay-content">
    
    	<div class="notice_content"></div>    
    </div>
</div>
<!--END POPUP CODE-->
<div class="panel-body panel-white"><!--START PANEL BODY DIV-->
    <ul class="nav nav-tabs panel_tabs" role="tablist">
        <li class="<?php if($active_tab == 'event_list'){?>active<?php }?>">
          <a href="?dashboard=user&page=event&tab=event_list">
             <i class="fa fa-align-justify"></i> <?php _e('Event List', 'hospital_mgt'); ?></a>
          </a>
        </li>
	    <li class="<?php if($active_tab=='addnotice'){?>active<?php }?>">
	  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
			<a href="?dashboard=user&page=event&tab=addnotice&&action=edit&notice_id=<?php echo $_REQUEST['notice_id'];?>" class="tab <?php echo $active_tab == 'addnotice' ? 'active' : ''; ?>">
			<i class="fa fa"></i> <?php _e('Edit Event', 'hospital_mgt'); ?></a>
		 <?php 
		}
		else
		{
			if($user_access['add']=='1')
			{			
			?>				
				<a href="?dashboard=user&page=event&tab=addnotice&&action=insert" class="tab <?php echo $active_tab == 'addnotice' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add Event', 'hospital_mgt'); ?></a>
			<?php
			}
		}
		?>	  
	    </li>
    </ul>
<?php
if($active_tab=='event_list')
{
?>
	<div class="tab-content"><!--START TAB CONTENT DIV-->
    	<div class="tab-pane fade active in"  id="eventlist"><!--START TAB PANE DIV-->
		    <div class="panel-body"><!--START PANEL BODY DIV-->
                <div class="table-responsive"><!--START TABLE ReSPONSIVE DIV-->
					<table id="hmgt_event" class="display dataTable " cellspacing="0" width="100%"><!--START EVENT LIST TABLE-->
						<thead>
							<tr>                
								<th width="190px"><?php _e('Title','hospital_mgt');?></th>
								<th><?php _e('Comment','hospital_mgt');?></th>
								<th><?php _e(' Start Date','hospital_mgt');?></th>
								<th><?php _e(' End Date','hospital_mgt');?></th>
								<th><?php _e('For','hospital_mgt');?></th>
								<th width="185px"><?php _e('Action','hospital_mgt');?></th>               
							</tr>
					    </thead>	
						<tfoot>
							<tr>
								<th width="190px"><?php _e('Title','hospital_mgt');?></th>
								<th><?php _e('Comment','hospital_mgt');?></th>
								<th><?php _e(' Start Date','hospital_mgt');?></th>
								<th><?php _e(' End Date','hospital_mgt');?></th>
								<th><?php _e('For','hospital_mgt');?></th>
								<th width="185px"><?php _e('Action','hospital_mgt');?></th>   
							</tr>
						</tfoot>
						<tbody>
						<?php
						$user_id=get_current_user_id();
						$user_role=MJ_hmgt_get_current_user_role();
						
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{
							$args['post_type'] = array('hmgt_event','hmgt_notice');
							$args['posts_per_page'] = -1;
							$args['post_status'] = 'public';
							$q = new WP_Query();
							$retrieve_class = $q->query( $args );
							$format =get_option('date_format') ;
							$eventdata=$obj_hospital->all_own_events_notice;	
						}
						else
						{
							$args['post_type'] = array('hmgt_event','hmgt_notice');
							$args['posts_per_page'] = -1;
							$args['post_status'] = 'public';
							$q = new WP_Query();
							$retrieve_class = $q->query( $args );
							$format =get_option('date_format') ;
							$eventdata=$obj_hospital->all_events_notice;
						}
						
						if(!empty($eventdata))
						{
							foreach ($eventdata as $retrieved_data)
							{ 
						 ?>
							<tr>
								<td><?php echo $retrieved_data->post_title;?></td>
								<td><?php 
									$strlength= strlen($retrieved_data->post_content);
									if($strlength > 60)
									{
										echo substr($retrieved_data->post_content, 0,60).'...';
									}
									else
									{
										echo $retrieved_data->post_content;
									}
								
								?></td>
								<td><?php echo date(MJ_hmgt_date_formate(),strtotime(get_post_meta($retrieved_data->ID,'start_date',true)));?></td> 
								<td><?php echo date(MJ_hmgt_date_formate(),strtotime(get_post_meta($retrieved_data->ID,'end_date',true)));?></td> 				
								<td>
									<?php 
									$notice_for_array=explode(",",get_post_meta( $retrieved_data->ID, 'notice_for',true));
									
									echo MJ_hmgt_get_role_name_in_event($notice_for_array);
									?>
								</td>              
							   <td>
								<a href="#" class="btn btn-primary view-notice" id="<?php echo $retrieved_data->ID;?>"> <?php _e('View','hospital_mgt');?></a>
							   <?php
								if($user_access['edit']=='1')
								{
								?>
									<a href="?dashboard=user&page=event&tab=addnotice&action=edit&notice_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->ID);?>"class="btn btn-info"><?php _e('Edit','hospital_mgt');?></a>
								<?php
								}
								if($user_access['delete']=='1')
								{
								?>	
									<a href="?dashboard=user&page=event&tab=noticelist&action=delete&notice_id=<?php echo MJ_hmgt_id_encrypt($retrieved_data->ID);?>" class="btn btn-danger" 
										onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');"> <?php _e('Delete','hospital_mgt');?></a>
								<?php
								}
								?>	
								</td>
							</tr>
						<?php }
						}
							?>
						</tbody>
				    </table><!--END NOTICE LIST TABLE-->
 		        </div><!--END TABLE RESPONSIVE DIV-->
		    </div><!--END PANEL BODY DIV-->
		</div>	<!--END TAB PANE DIV-->
	</div><!--END TAB CONTENT DIV-->
<?php
}
if($active_tab=='addnotice')
{
	?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		 $('#notice_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
		 $('.event_for_multiselect').multiselect({
			nonSelectedText :'<?php _e('Select Role','hospital_mgt'); ?>',
			includeSelectAllOption: true,
			selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
		 });
			var start = new Date();
			var end = new Date(new Date().setYear(start.getFullYear()+1));
			 $.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
			$('.datepicker1').datepicker({
				startDate : start,
				endDate   : end,
				autoclose: true
			}).on('changeDate', function (selected) {
			var minDate = new Date(selected.date.valueOf());
			$('.datepicker2').datepicker('setStartDate', minDate);
			}); 
			$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
			$('.datepicker2').datepicker({
				startDate : start,
				endDate   : end,
				autoclose: true
			}).on('changeDate', function (selected) {
				var maxDate = new Date(selected.date.valueOf());
				$('.datepicker1').datepicker('setEndDate', maxDate);
			});
			$(".event_for_alert").click(function()
			{	
				checked = $(".multiselect_validation_role .dropdown-menu input:checked").length;
				if(!checked)
				{
				  alert("<?php _e('Please select atleast one Role','hospital_mgt');?>");
				  return false;
				}	
			}); 	
	} );
	</script>
		<?php 
		$edit=0;
	    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			 $postdata = get_post(MJ_hmgt_id_decrypt($_REQUEST['notice_id']));
		}
		?>
        <div class="panel-body"> <!--SATRT PANEL BODY DIV-->
		   <form name="class_form" action="" method="post" class="form-horizontal" id="notice_form"><!--STRAT NOTICE FORM-->
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="notice_id"   value="<?php if($edit){ echo $postdata->ID;}?>"/> 
			<div class="form-group">
				<label class="col-sm-2 control-label" for="notice_type"><?php _e('Event/Notice','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select id="notice_type" class="form-control validate[required]" name="notice_type">
						<option value=""><?php _e('Select Type','hospital_mgt');?></option>
						<option value="hmgt_notice" <?php if($edit) selected('hmgt_notice',$postdata->post_type);?>><?php echo _e('Notice','hospital_mgt'); ?></option>
						<option value="hmgt_event" <?php if($edit) selected('hmgt_event',$postdata->post_type);?>><?php echo _e('Event','hospital_mgt'); ?></option>
					</select>
					 
				</div>
			</div>
		   <div class="form-group">
				<label class="col-sm-2 control-label" for="notice_title"><?php _e('Event Title','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="notice_title" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $postdata->post_title;}?>" name="notice_title">
					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="notice_content"><?php _e('Event Comment','hospital_mgt');?></label>
				<div class="col-sm-8">
				<textarea name="notice_content" class="form-control validate[custom[address_description_validation]]" maxlength="150" id="notice_content"><?php if($edit){ echo $postdata->post_content;}?></textarea>
					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="notice_content"><?php _e('Event Start Date','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
				<input id="notice_Start_date" class="datepicker1 form-control validate[required] text-input" type="text"  value="<?php if($edit){ echo date(MJ_hmgt_date_formate(),strtotime(get_post_meta($postdata->ID,'start_date',true)));}?>" name="start_date">
					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="notice_content"><?php _e('Event End Date','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
				<input id="notice_end_date" class="datepicker2 form-control validate[required] text-input" type="text"  value="<?php if($edit){ echo  date(MJ_hmgt_date_formate(),strtotime(get_post_meta($postdata->ID,'end_date',true)));}?>" name="end_date">
					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="notice_for"><?php _e('Event For','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8 multiselect_validation_role">
				 <select name="notice_for[]" id="notice_for" multiple="multiple" class="form-control validate[required] text-input event_for_multiselect">

						   <?php 
						   if($edit)
						   {
								$notice_for_array=explode(",",get_post_meta( $postdata->ID, 'notice_for',true));
							
							?>
								
								<option value="patient" <?php if(in_array('patient',$notice_for_array)){ echo 'selected'; } ?>><?php _e('Patient','hospital_mgt');?></option>
								<option value="doctor" <?php if(in_array('doctor',$notice_for_array)){ echo 'selected'; } ?>><?php _e('Doctor','hospital_mgt');?></option>	
								<option value="nurse" <?php if(in_array('nurse',$notice_for_array)){ echo 'selected'; } ?>><?php _e('Nurse','hospital_mgt');?></option>	
								<option value="receptionist" <?php if(in_array('receptionist',$notice_for_array)){ echo 'selected'; } ?>><?php _e('Support Staff','hospital_mgt');?></option>	
								<option value="pharmacist" <?php if(in_array('pharmacist',$notice_for_array)){ echo 'selected'; } ?>><?php _e('Pharmacist','hospital_mgt');?></option>	
								<option value="laboratorist" <?php if(in_array('laboratorist',$notice_for_array)){ echo 'selected'; } ?>><?php _e('Laboratory Staff','hospital_mgt');?></option>	
								<option value="accountant" <?php if(in_array('accountant',$notice_for_array)){ echo 'selected'; } ?>><?php _e('Accountant','hospital_mgt');?></option>	   
						   <?php 
						   }
						   else
						   {
							?>
								<!--<option value="all"><?php _e('All','hospital_mgt');?></option>-->
								<option value="patient"><?php _e('Patient','hospital_mgt');?></option>	
								<option value="doctor"><?php _e('Doctor','hospital_mgt');?></option>
								<option value="nurse"><?php _e('Nurse','hospital_mgt');?></option>									
								<option value="receptionist"><?php _e('Support Staff','hospital_mgt');?></option>	
								<option value="pharmacist"><?php _e('Pharmacist','hospital_mgt');?></option>	
								<option value="laboratorist"><?php _e('Laboratory Staff','hospital_mgt');?></option>	
								<option value="accountant"><?php _e('Accountant','hospital_mgt');?></option>	
							<?php 
						   }
							  ?>
						   </select>
					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label " for="enable"><?php _e('Send SMS','hospital_mgt');?></label>
				<div class="col-sm-8">
					 <div class="checkbox">
						<label>
							<input id="chk_sms_sent" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="hmgt_sms_service_enable">
						</label>
					</div>
					 
				</div>
			</div>
			<div id="hmsg_message_sent" class="hmsg_message_none">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="sms_template"><?php _e('SMS Text','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<textarea name="sms_template" class="form-control validate[required,custom[address_description_validation]]" maxlength="160"></textarea>
					<label><?php _e('Max. 160 Character','hospital_mgt');?></label>
				</div>
			</div>
			</div>
			<div class="col-sm-offset-2 col-sm-8">        	
				<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_notice" class="btn btn-success event_for_alert" />
			</div>
			</form><!--END NOTICE FORM-->
       </div><!--END PANEL BODY DIV-->
<?php      
}
?>	
</div><!--END PANEL BODY DIV-->
<?php ?>