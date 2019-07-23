<?php
require_once(ABSPATH.'wp-admin/includes/user.php' );
$obj_hospital = new Hospital_Management(get_current_user_id());

if (! is_user_logged_in ()) 
{
	$page_id = get_option ( 'hmgt_login_page' );
	wp_redirect ( home_url () . "?page_id=" . $page_id );
}

if (is_super_admin ()) 
{
	wp_redirect ( admin_url () . 'admin.php?page=hmgt_hospital' );
}
 // Get appointment data//
 $obj_appointment = new MJ_hmgt_appointment();
$appointment_data = $obj_hospital->appointment;
$cal_array = array ();
if (! empty ( $appointment_data ))
{
	foreach ( $appointment_data as $appointment )
	{
		$patient_data =	MJ_hmgt_get_user_detail_byid($appointment->patient_id);
		$patient_name = $patient_data['first_name']." ".$patient_data['last_name']."(".$patient_data['patient_id'].")";
		$doctor_data =	MJ_hmgt_get_user_detail_byid($appointment->doctor_id);
		$doctor_name = $doctor_data['first_name']." ".$doctor_data['last_name'];
		$appointment_time_with_a=$appointment->appointment_time_with_a;
		$d = new DateTime($appointment_time_with_a); 
		$starttime=date_format($d,'H:i:s'); 
		$appointment_start_date=date('Y-m-d',strtotime($appointment->appointment_time_string));
		$appointment_start_date_new=$appointment_start_date." ".$starttime;
		$appointment_enddate = date('Y-m-d H:i:s',strtotime($appointment_start_date_new) + 900);
		$i=1;
		$cal_array [] = array (
				'type' =>  'appointment',
				'title' =>  __( 'Detail', 'hospital_mgt' ) ,
				'start' => $appointment_start_date_new,
				'end' =>$appointment_enddate,
				'patient_name' =>$patient_name,
				'doctor_name' =>$doctor_name,
				'appointment_time'=> $appointment->appointment_time_with_a);
	}
}
//get event data
$args['post_type'] = array('hmgt_event');
$args['posts_per_page'] = -1;
$args['post_status'] = 'public';
$q = new WP_Query();
$retrieve_class = $q->query( $args );

if(!empty($retrieve_class))
{
	foreach ($retrieve_class as $retrieved_data)
	{
		
		$event_for_array=explode(",",get_post_meta( $retrieved_data->ID, 'notice_for',true));
		$role=MJ_hmgt_get_current_user_role();	
		
		if(in_array($role,$event_for_array))
		{
			$i=1;
			$cal_array [] = array (	
						'type' =>  'event',
						'title' => $retrieved_data->post_title,
						'start' => get_post_meta($retrieved_data->ID,'start_date',true),
						'end' => date('Y-m-d',strtotime(get_post_meta($retrieved_data->ID,'end_date',true).' +'.$i.' days')) ,
						'event_for' =>MJ_hmgt_get_role_name_in_event($event_for_array),
						'event_comment' =>$retrieved_data->post_content,
						'backgroundColor' => 'green'						
					);	
		}
	} 
}
//get notice data
$args['post_type'] = array('hmgt_notice');
$args['posts_per_page'] = -1;
$args['post_status'] = 'public';
$q = new WP_Query();
$retrieve_class1 = $q->query( $args );

if(!empty($retrieve_class1))
{	
	foreach ($retrieve_class1 as $retrieved_data)
	{
		$notice_for_array=explode(",",get_post_meta( $retrieved_data->ID, 'notice_for',true));
		$role=MJ_hmgt_get_current_user_role();	
		
		if(in_array($role,$notice_for_array))
		{
			$i=1;
			$cal_array [] = array (	
						'type' =>  'notice',
						'title' => $retrieved_data->post_title,
						'start' => get_post_meta($retrieved_data->ID,'start_date',true),
						'end' =>date('Y-m-d',strtotime(get_post_meta($retrieved_data->ID,'end_date',true).' +'.$i.' days')),
						'event_for' =>MJ_hmgt_get_role_name_in_event($notice_for_array),
						'event_comment' =>$retrieved_data->post_content,				
						'backgroundColor' => '#F25656'								
					);	
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/sweetalert.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/example.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/bootstrap-select.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/dataTables.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/dataTables.editor.min.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/dataTables.tableTools.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/dataTables.responsive.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/jquery-ui.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/font-awesome.min.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/popup.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/style.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/custom.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/fullcalendar.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/lib/select2-3.5.3/select2.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/bootstrap.min.css'; ?>"/>	
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/datepicker.min.css'; ?>"/>  
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/time.css'; ?>"/>  
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/bootstrap-multiselect.css'; ?>"/>	
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/white.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/hospitalmgt.min.css'; ?>"/>
<?php  if (is_rtl())
		 {?>
			<link rel="stylesheet" type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/bootstrap-rtl.min.css'; ?>"/>
		<?php  } ?>

<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/lib/validationEngine/css/validationEngine.jquery.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/hospital-responsive.css'; ?>"/>
<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/lib/bootstrap-fileinput-master/css/fileinput.min.css'; ?>"/>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/jquery-1.11.1.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo HMS_PLUGIN_URL.'/lib/select2-3.5.3/select2.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/jquery-ui.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/jquery.timeago.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/moment.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/bootstrap-select.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/fullcalendar.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/jquery.dataTables.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/dataTables.tableTools.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/dataTables.editor.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/dataTables.responsive.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/bootstrap.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/bootstrap-datepicker.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/time.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/bootstrap-multiselect.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/responsive-tabs.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/sweetalert-dev.js'; ?>"></script>

<?php		
$lancode=get_locale();
$code=substr($lancode,0,2);
?>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/calendar-lang/'.$code.'.js'; ?>"></script>
<?php $lancode=get_locale();
$code=substr($lancode,0,2);	
?>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/lib/bootstrap-fileinput-master/js/plugins/canvas-to-blob.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/lib/bootstrap-fileinput-master/js/fileinput.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/lib/bootstrap-fileinput-master/js/fileinput.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/lib/bootstrap-fileinput-master/js/fileinput_locale_es.js'; ?>"></script>

<script>
jQuery(document).ready(function() {	
	jQuery('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: false,
			timeFormat: 'h(:mm)A',
			eventLimit: 1, 
			slotDuration:'00:15:00',			
			events:<?php echo json_encode($cal_array);?>,
			eventRender: function(event, element) 
			{ 
				if(event.type=='appointment')
				{
					element.find('.fc-title').append("<?php  __( 'Doctor', 'hospital_mgt' ) ?> :" + event.doctor_name +"  <?php __( 'Patient', 'hospital_mgt' ) ?> :" + event.patient_name + ", ");	
				}
				if(event.type=='event')
				{ 
					element.find('.fc-content').css("background-color", "green");	
				}
				if(event.type=='notice')
				{ 
					element.find('.fc-content').css("background-color", "#F25656");	
				}		
	        },
			eventMouseover: function(event, element) 
			{ 
				if(event.type=='appointment')
				{
					var date = new Date(event.start);
					var time = event.appointment_time;					
					var month = date.getMonth() + 1;
					var day = date.getDate();
					var year = date.getFullYear();							
					var full_date = year + "-" + month + "-" + day;				
					var tooltip = '<div class="tooltipevent" style="background:#feb811;width:230px;color:white;padding:5px;position:absolute;z-index:10001;margin:0;"><?php  _e( 'Doctor Name', 'hospital_mgt' ) ?>  : ' + event.doctor_name + '<br> <?php  _e( 'Patient Name', 'hospital_mgt' ) ?> :' + event.patient_name +' <br>  <?php  _e( 'Date', 'hospital_mgt' ) ?>  :' + full_date +'<br> <?php  _e( 'Time', 'hospital_mgt' ) ?>  :'+ time +' </div>'; 
					var $tool = $(tooltip).appendTo('body');
					
					$(this).mouseover(function(e) 
					{
						$(this).css('z-index', 10000);
						$tool.fadeIn('500');
						$tool.fadeTo('10', 1.9);
					}).mousemove(function(e) 
					{
						$tool.css('top', e.pageY + 5);
						$tool.css('left', e.pageX + 5);
					});
				}
				else
				{							
					var date = new Date(event.start);
					var month = date.getMonth() + 1;
					var day = date.getDate();
					var year = date.getFullYear();						
					var full_start_date = year + "-" + month + "-" + day;		
					
					var newdate = event.end;				
					var newdate1 = new Date(newdate);
					newdate1.setDate(newdate1.getDate() - 1);
					
					var date1 = new Date(newdate1);
					var month1 = date1.getMonth() + 1;
					var day1 = date1.getDate();
					var year1 = date1.getFullYear();						
					var full_end_date = year1 + "-" + month1 + "-" + day1;		
					
					if(event.type=='event')
					{
						var tooltip = '<div class="tooltipevent" style="background:#feb811;width:230px;color:white;padding:5px;position:absolute;z-index:10001;margin:0;"><?php  _e( 'Event Name', 'hospital_mgt' ) ?>  : ' + event.title + '<br> <?php  _e( 'Start Date', 'hospital_mgt' ) ?> :' + full_start_date +' <br>  <?php  _e( 'End Date', 'hospital_mgt' ) ?>  :' + full_end_date +'<br> <?php  _e( 'Event For', 'hospital_mgt' ) ?>  :'+ event.event_for +' <br> <?php  _e( 'Comment', 'hospital_mgt' ) ?>  :'+ event.event_comment +'</div>';
					}
					else
					{
						var tooltip = '<div class="tooltipevent" style="background:#feb811;width:230px;color:white;padding:5px;position:absolute;z-index:10001;margin:0;"><?php  _e( 'Notice Name', 'hospital_mgt' ) ?>  : ' + event.title + '<br> <?php  _e( 'Start Date', 'hospital_mgt' ) ?> :' + full_start_date +' <br>  <?php  _e( 'End Date', 'hospital_mgt' ) ?>  :' + full_end_date +'<br> <?php  _e( 'Notice For', 'hospital_mgt' ) ?>  :'+ event.event_for +' <br> <?php  _e( 'Comment', 'hospital_mgt' ) ?>  :'+ event.event_comment +'</div>';
					}
					var $tool = $(tooltip).appendTo('body');	
					
					$(this).mouseover(function(e)
					{
						$(this).css('z-index', 10000);
							$tool.fadeIn('500');
							$tool.fadeTo('10', 1.9);
					}).mousemove(function(e) {
						$tool.css('top', e.pageY + 5);
						$tool.css('left', e.pageX + 5);
					});
				}
			},
			eventMouseout: function(event, element) 
			{
				$(this).css('z-index', 8);
				$('.tooltipevent').remove();
			}
		});
	});
</script>
<style>
@media only screen and (max-width : 768px) 
{
	input[type=radio] 
	{
		margin: 2px 0 0;
		
	}	
	.radio-inline input[type=radio]
	{
		margin-left: -20px;
	}
	.radio input[type=radio]
	{
		margin-left: -20px;
	}
}
@media only screen and (max-width : 480px) 
{
	input[type=checkbox], input[type=radio]
	 {
		margin: 2px 0px 0px;
	 } 
}
</style>
</head>
<body class="hospital-management-content"><!-- start body div-->
<?php
	$user = wp_get_current_user ();
?>
<!--task-event POP up code -->
  <div class="popup-bg">
    <div class="overlay-content content_width">
		<div class="modal-content" style="border-top: 5px solid #22baa0;">
			<div class="task_event_list">
			</div>     
		</div>
    </div>     
  </div>
 <!-- End task-event POP-UP Code -->
<div class="container-fluid mainpage"><!-- start container fluid div-->
    <div class="navbar"><!-- start navbar div-->
     <!-- HOSPTAL LOGO AND NAME -->	
		<div class="col-md-3 col-sm-2 col-xs-4">
			<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" />
			<span><?php echo get_option( 'hmgt_hospital_name' );?></span>
			</h3>
		</div>
	    <!--  END HOSPTAL LOGO AND NAME  -->	
		<ul class="nav navbar-right col-md-9 col-sm-10 col-xs-8">
				
				<!-- BEGIN USER LOGIN DROPDOWN -->
				<li class="dropdown">
					<a data-toggle="dropdown"
						class="dropdown-toggle" href="javascript:;">
							<?php
							$userimage = get_user_meta( $user->ID,'hmgt_user_avatar',true );
							if (empty ( $userimage )){
								echo '<img src='.MJ_hmgt_get_default_userprofile($obj_hospital->role).' height="40px" width="40px" class="img-circle" />';
							}
							else	
								echo '<img src=' . $userimage . ' height="40px" width="40px" class="img-circle"/>';
							?>
								<span>	<?php echo $user->display_name;?> </span> <b class="caret"></b>
					</a>
					<ul class="dropdown-menu extended logout">
						<li><a href="?dashboard=user&page=account"><i class="fa fa-user"></i>
								<?php _e('My Profile','hospital_mgt');?></a></li>
						<li><a href="<?php echo wp_logout_url(home_url()); ?>"><i
								class="fa fa-sign-out m-r-xs"></i><?php _e('Log Out','hospital_mgt');?> </a></li>
					</ul>
				</li>
				<!-- END USER LOGIN DROPDOWN -->
			</ul>
	</div><!-- end navbar div-->
</div><!-- end container fluid div-->
<div class="container-fluid"><!-- start container fluid div-->
	<div class="row"><!-- start row div-->
		<div class="col-sm-2 nopadding hospital_left"><!-- start menu div-->
			<!--  Left Side -->
			<?php
			$role = $obj_hospital->role;
			
			if($role=='doctor')
			{
				$menu = get_option( 'hmgt_access_right_doctor');
			}
			elseif($role=='patient')
			{
				$menu = get_option( 'hmgt_access_right_patient');
			}
			elseif($role=='nurse')
			{
				$menu = get_option( 'hmgt_access_right_nurse');
			}
			elseif($role=='receptionist')
			{
				$menu = get_option( 'hmgt_access_right_supportstaff');
			}
			elseif($role=='accountant')
			{
				$menu = get_option( 'hmgt_access_right_accountant');
			}
			elseif($role=='pharmacist')
			{
				$menu = get_option( 'hmgt_access_right_pharmacist');
			}
			elseif($role=='laboratorist')
			{
				$menu = get_option( 'hmgt_access_right_laboratories');
			}	
			$class = "";
			if (! isset ( $_REQUEST ['page'] ))	
				$class = 'class = "active"';		
			$patient_type='';
			if($role=='patient')
				$patient_type=get_user_meta(get_current_user_id(),'patient_type',true);	 
			?>
			<ul class="nav nav-pills nav-stacked">
				<li>
				<a href="<?php echo site_url();?>"><span class="icone"><img src="<?php echo plugins_url( 'hospital-management/assets/images/icon/home.png' )?>"/></span><span class="title"><?php _e('Home','hospital_mgt');?></span></a></li>
				<li <?php echo $class;?>><a href="?dashboard=user"><span class="icone"><img src="<?php echo plugins_url( 'hospital-management/assets/images/icon/dashboard.png' )?>"/></span><span
						class="title"><?php _e('Dashboard','hospital_mgt');?></span></a></li>
						<?php
						$role = $obj_hospital->role;												
						$access_page_view_array=array();	
						if(!empty($menu))	
						{											
							foreach ( $menu as $key1=>$value1 ) 
							{									
								foreach ( $value1 as $key=>$value ) 
								{													
									if($value['view']=='1')
									{
										$access_page_view_array[]=$value ['page_link'];
										
										if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $value ['page_link'])
											$class = 'class = "active"';
										else
											$class = "";	
										
										echo '<li ' . $class . '><a href="?dashboard=user&page=' . $value ['page_link'] . '" class="left-tooltip" data-tooltip="'. $value ['menu_title'] . '" title="'. $value ['menu_title'] . '">
										<span class="icone"> <img src="' .$value ['menu_icone'].'" /></span><span class="title">'.MJ_hmgt_change_menutitle($key). '</span></a></li>';
									}	
								}									
							}
						}	
					?>								
			</ul>
		</div><!-- end menu div-->
		<div class="page-inner" style="min-height:1050px;"><!-- start page inner div-->
			<div class="row right_side <?php if(isset($_REQUEST['page']))echo $_REQUEST['page'];?>"><!-- start dashboard content div-->
				<?php
				if (isset ( $_REQUEST ['page'] ))
				{			
					if(in_array($_REQUEST ['page'],$access_page_view_array))
					{	
						require_once HMS_PLUGIN_DIR . '/template/' . $_REQUEST['page'] . '.php';
						return false;
					}
					else
					{
						wp_redirect ('?dashboard=user');
						return false;
					}			
				}
				?>
				<!---start new dashboard------>
				<?php
				$page='patient';
				$patient_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($patient_access)
				{
				?>					
				<div class="row">	
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">			
					<a href="<?php echo home_url().'?dashboard=user&page=patient';?>">			
						<div class="panel info-box panel-white">
							<div class="panel-body patient">
								<div class="info-box-stats">
									<p class="counter"><?php echo count(get_users(array('role'=>'patient')));?></p>
									
									<span class="info-box-title"><?php echo esc_html( __( 'Patient', 'hospital_mgt' ) );?></span>
								</div>
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/patient.png"?>" class="dashboard_background">
								
							</div>
						</div>
					</div>	
				<?php
				}
				$page='doctor';
				$doctor_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($doctor_access)
				{
				?>	
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">			
					<a href="<?php echo home_url().'?dashboard=user&page=doctor';?>">			
						<div class="panel info-box panel-white">
							<div class="panel-body doctor">
								<div class="info-box-stats">
									<p class="counter"><?php echo count(get_users(array('role'=>'doctor')));?></p>
									<span class="info-box-title"><?php echo esc_html( __( 'Doctor', 'hospital_mgt' ) );?></span>
								</div>
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/doctor.png"?>" class="dashboard_background">
								
							</div>
						</div>
					</div>
				<?php
				}
				$page='nurse';
				$nurse_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($nurse_access)
				{
				?>		
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
					<a href="<?php echo home_url().'?dashboard=user&page=nurse';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body nurse">
								<div class="info-box-stats">
									<p class="counter"><?php echo count(get_users(array('role'=>'nurse')));?></p>
									<span class="info-box-title"><?php echo esc_html( __( 'Nurse', 'hospital_mgt' ) );?></span>
								</div>
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/nurse.png"?>" class="dashboard_background">
								
							</div>
						</div>
					</div>
				<?php
				}	
				$page='supportstaff';
				$supportstaff_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($supportstaff_access)
				{
				?>	
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
					<a href="<?php echo home_url().'?dashboard=user&page=supportstaff';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body receptionist">
								<div class="info-box-stats">
									<p class="counter"><?php echo count(get_users(array('role'=>'receptionist')));?></p>
									
									<span class="info-box-title"><?php echo esc_html( __( 'Support Staff', 'hospital_mgt' ) );?></span>
								</div>
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/support-staft.png"?>" class="dashboard_background">
							</div>
						</div>
					</div>
				<?php
				}	
				$page='message';
				$message_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($message_access)
				{
				?>	
					
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
					<a href="<?php echo home_url().'?dashboard=user&page=message&tab=inbox';?>">	
						<div class="panel info-box panel-white">
							<div class="panel-body message">
								<div class="info-box-stats">
									<p class="counter"><?php 
									$obj_message = new MJ_hmgt_message();
									$message = $obj_message->hmgt_count_inbox_item(get_current_user_id());
									echo count($message);
									?></p>
									
									<span class="info-box-title"><?php echo esc_html( __( 'Message', 'hospital_mgt' ) );?></span>
								</div>
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/message.png"?>" class="dashboard_background">
								
							</div>
						</div>
					</a>
					</div>
				<?php
				}	
				$page='account';
				$account_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($account_access)
				{
				?>	
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
					<a href="<?php echo home_url().'?dashboard=user&page=account';?>">	
						<div class="panel info-box panel-white">
							<div class="panel-body setting">
								<div class="info-box-stats">
									<p class="counter"> &nbsp;</p>
									<span class="info-box-title"><?php echo esc_html( __( 'Setting', 'hospital_mgt' ) );?></span>
								</div>
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/setting-image.png"?>" class="dashboard_background">
							</div>
						</div>
						</a>
					</div>
				<?php
				}	
				$page='appointment';
				$appointment_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($appointment_access)
				{
				?>		
					<?php
					if($obj_hospital->role == 'nurse' || $obj_hospital->role == 'doctor')
					{
					?>
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
					<a href="<?php echo home_url().'?dashboard=user&page=appointment';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body appointment">
								<div class="info-box-stats">
									<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_appointment');?></p>
									<span class="info-box-title"><?php echo esc_html( __( 'Appointment', 'hospital_mgt' ) );?></span>
								</div>
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/appointment-image.png"?>" class="dashboard_background">
							</div>
						</div>
					</a>
					</div>					
					<?php 
					}			
				}	
				$page='prescription';
				$prescription_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($prescription_access)
				{							
					if($obj_hospital->role == 'doctor')
					{
					?>
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
					<a href="<?php echo home_url().'?dashboard=user&page=prescription';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body prescription">
								<div class="info-box-stats">
									<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_priscription');?></p>
									
									<span class="info-box-title"><?php echo esc_html( __( 'Prescription', 'hospital_mgt' ) );?></span>
								</div>
								 <img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/preseription-image.png"?>" class="dashboard_background"> 
								
							</div>
						</div>
						</a>
					</div>
					<?php 
					} 
				}	
				$page='bedallotment';
				$bedallotment_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($bedallotment_access)
				{				
					if($obj_hospital->role == 'nurse' || $obj_hospital->role == 'doctor') 
					{
						?>
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
					<a href="<?php echo home_url().'?dashboard=user&page=bedallotment&tab=bedassign';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body assignbed">
								<div class="info-box-stats">
									<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_bed_allotment');?></p>
									
									<span class="info-box-title"><?php echo _e( __( 'Assign <BR> Bed/Nurse', 'hospital_mgt' ) );?></span>
								</div>
								 <img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/assign-bed-image.png"?>" class="dashboard_background">   
								
							</div>
						</div>
					</a>
					</div>
					<?php 
					}
				}	
				$page='treatment';
				$treatment_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($treatment_access)
				{							
					if($obj_hospital->role == 'doctor') 
					{
					?>
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
					<a href="<?php echo home_url().'?dashboard=user&page=treatment';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body treatment">
								<div class="info-box-stats">
									<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_treatment');?></p>
									
									<span class="info-box-title"><?php echo esc_html( __( 'Treatment', 'hospital_mgt' ) );?></span>
								</div>
								  <img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/tretment-image.png"?>" class="dashboard_background">
								
							</div>
						</div>
						</a>
					</div>
					<?php
					}
				}	
				$page='event';
				$event_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($event_access)
				{						
				 ?>
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
						<a href="<?php echo home_url().'?dashboard=user&page=event';?>">
							<div class="panel info-box panel-white">
								<div class="panel-body eventnotice">
									<div class="info-box-stats">
										<p class="counter">
										<?php 
										$args['post_type'] = array('hmgt_event','hmgt_notice');
										$args['posts_per_page'] = -1;
										$args['post_status'] = 'public';
										$q = new WP_Query();
										$retrieve_class = $q->query( $args ); 
										$noticedata=$obj_hospital->all_events_notice;
										echo count($noticedata);
										?></p>
										
										<span class="info-box-title"><?php echo _e( __( 'Events/ <BR> Notice', 'hospital_mgt' ) );?></span>
									</div>
									<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/notice-event-image.png"?>" class="dashboard_background">									
								</div>
							</div>
						</a>
					</div>
				<?php 					
				}	
				$page='report';
				$report_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($report_access)
				{
					 if($obj_hospital->role == 'doctor') 
					 {
						?>
						<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
						<a href="<?php echo home_url().'?dashboard=user&page=report';?>">
							<div class="panel info-box panel-white">
								<div class="panel-body operation_report">
									<div class="info-box-stats">
										<p class="counter">&nbsp;</p>
										
										<span class="info-box-title"><?php echo esc_html( __( 'Report', 'hospital_mgt' ) );?></span>
									</div>
									<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/report.png"?>" class="dashboard_background">
								</div>
							</div>
							</a>
						</div>
					<?php
					}
				}	
				$page='pharmacist';
				$pharmacist_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($pharmacist_access)
				{					
					?>
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
					<a href="<?php echo home_url().'?dashboard=user&page=pharmacist';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body pharmacist">
								<div class="info-box-stats">
									<p class="counter"><?php echo count(get_users(array('role'=>'pharmacist')));?></p>
									
									<span class="info-box-title"><?php echo esc_html( __( 'Pharmacist', 'hospital_mgt' ) );?></span>
								</div>
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/pharmacist.png"?>" class="dashboard_background">
							</div>
						</div>
					</div>
					<?php 
				}	
				$page='medicine';
				$medicine_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($medicine_access)
				{
					if($obj_hospital->role == 'pharmacist') 
					{
					?>
						<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
						<a href="<?php echo home_url().'?dashboard=user&page=medicine';?>">
							<div class="panel info-box panel-white">
								<div class="panel-body medicine">
									<div class="info-box-stats">
										<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_medicine');?></p>
										
										<span class="info-box-title"><?php echo esc_html( __( 'Medicine', 'hospital_mgt' ) );?></span>
									</div>
									<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/medicine.png"?>" class="dashboard_background"> 
								</div>
							</div>
							</a>
						</div>
					<?php
					} 
				}	
				$page='laboratorystaff';
				$laboratorystaff_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($laboratorystaff_access)
				{	
					?>
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
					<a href="<?php echo home_url().'?dashboard=user&page=laboratorystaff';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body laboratorist">
								<div class="info-box-stats">
									<p class="counter"><?php echo count(get_users(array('role'=>'laboratorist')));?></p>
									
									<span class="info-box-title"><?php echo _e( __( 'Laboratory <BR> Staff', 'hospital_mgt' ) );?></span>
								</div>
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/laboratorist.png"?>" class="dashboard_background">
							</div>
						</div>
					</div>
					<?php 
				}	
				$page='diagnosis';
				$diagnosis_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($diagnosis_access)
				{	
					if($obj_hospital->role == 'doctor' || $obj_hospital->role == 'laboratorist')
					{
					?>
						<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
						<a href="<?php echo home_url().'?dashboard=user&page=diagnosis';?>">
							<div class="panel info-box panel-white">
								<div class="panel-body diagnosis">
									<div class="info-box-stats">
										<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_diagnosis');?></p>
										
										<span class="info-box-title"><?php echo _e( __( 'Diagnosis <BR> Report', 'hospital_mgt' ) );?></span>
									</div>
									<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/diagnosis-image.png"?>" class="dashboard_background">
									
								</div>
							</div>
						</a>
						</div>
					<?php
					}
				}	
				$page='accountant';
				$accountant_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($accountant_access)
				{
				?>
					<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
					<a href="<?php echo home_url().'?dashboard=user&page=accountant';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body accountant">
								<div class="info-box-stats">
									<p class="counter"><?php echo count(get_users(array('role'=>'accountant')));?></p>
									<span class="info-box-title"><?php echo esc_html( __( 'Accountant', 'hospital_mgt' ) );?></span>
								</div>
								 <img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/accountant.png"?>" class="dashboard_background">
								
							</div>
						</div>
						</a>
					</div>
					<?php 
				}	
				$page='invoice';
				$invoice_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
				if($invoice_access)
				{	
					if($obj_hospital->role == 'accountant')
					{
					?>
						<div class="col-lg-2 col-md-2 col-xs-6 col-sm-3">
						<a href="<?php echo home_url().'?dashboard=user&page=invoice';?>">
							<div class="panel info-box panel-white">
								<div class="panel-body invoice">
									<div class="info-box-stats">
										<p class="counter"><?php MJ_hmgt_tables_rows('hmgt_invoice');?></p>
										
										<span class="info-box-title"><?php echo esc_html( __( 'Invoice', 'hospital_mgt' ) );?></span>
									</div>
									 <img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/invoice.png"?>" class="dashboard_background"> 
								</div>
							</div>
							</a>
						</div>
					<?php
					} 
				}					
				?>
				</div>
				<div class="row dashboard_panel_heading_border">
					<div class="col-md-6 no-paddingR">
						<!--  Start Prescription Box -->
						<?php
						if($prescription_access)
						{
						?>
							<div class="panel panel-white event priscription">
								<div class="panel-heading ">					
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/Prescription.png"?>" >
									<h3 class="panel-title"><?php _e('Prescription','hospital_mgt');?></h3>						
								</div>
								<div class="panel-body">
									<div class="events">
										 <?php 
										 $obj_var=new MJ_hmgt_prescription();
										$prescriptiondata=$obj_var->get_prescription_on_fronted_dashboard();
										
										if(!empty($prescriptiondata))
										{
											foreach ($prescriptiondata as $retrieved_data)
											{ 
											?>								
												<div class="calendar-event"> 
													<p class="remainder_title_pr Bold viewpriscription show_task_event" id="<?php echo $retrieved_data->priscription_id; ?>" model="Prescription Details" >  <?php _e('Patient Name','hospital_mgt');?> : 
													<?php 	$patient = MJ_hmgt_get_user_detail_byid( $retrieved_data->patient_id);
															echo  $patient['first_name']." ".$patient['last_name'];
														?>
													</p>
													<p class="remainder_date_pr"> <?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->pris_create_date));?> </p>
													<p class="remainder_title_pr viewpriscription" > <?php _e('Description','hospital_mgt');?>	 : <?php
														if($retrieved_data->prescription_type=='report')
														{		
															echo $retrieved_data->report_description; 
														}
														else
														{
															echo $retrieved_data->case_history; 
														}											
													?></p>
												</div>	
											<?php
											}
										}	
										?>	
									</div>                       
								</div>
							</div>
						<?php
						}
						?>
						<!-- End Prescription Box -->
								
						<!--  Start Operation Box -->
						<?php
						$page='operation';
						$operation_access=MJ_hmgt_page_access_rolewise_and_accessright_dashboard($page);
						if($operation_access)
						{
						?>
							<div class="panel panel-white event operation">
								<div class="panel-heading ">
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/Operation-List.png"?>" >
								<h3 class="panel-title"><?php _e('Operation','hospital_mgt');?></h3>						
								</div>
								<div class="panel-body">
									<div class="events">
										<?php
										$obj_ot = new MJ_hmgt_operation();
										$ot_data=$obj_ot->get_operation_on_fronted_dashboard();
										if(!empty($ot_data))
										{
											foreach ($ot_data as $retrieved_data)
											{		
												$patient_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->patient_id);		
											?>
												<div class="calendar-event"> 
													<p class="remainder_title_pr Bold viewoperation show_task_event" id="<?php echo $retrieved_data->operation_id; ?>" model="Operation Details">	<?php _e('Patient Name','hospital_mgt');?> : <?php echo $patient_data['first_name']." ".$patient_data['last_name']; ?></p>
													<p class="remainder_date_pr"> <?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->operation_date));	?> 	</p>
													
													<p class="remainder_title_pr  viewoperation"  > <?php _e('Operation Name','hospital_mgt');?>: <?php echo $obj_ot->get_operation_name($retrieved_data->operation_title);?></p>
												</div>	
											<?php
											}
										}	
										?>		
									</div>                       
								</div>
							</div>
						<?php
						}
						?>
						<!-- End Operation Box -->
						
						<!-- start calendar Box -->
						<div class="panel panel-white">
						   <div class="panel-heading" style="margin-bottom: 15px;">
										<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/calender.png"?>" >
										<h3 class="panel-title"><?php _e('Calendar','hospital_mgt');?></h3>			
									</div>
							<div class="panel-body">
								<div id="calendar"></div>
							</div>
						</div>	
						<!-- end calendar Box -->							
					 </div>	
					<div class="col-md-6">	
						<!-- Start Appointment Box -->	
						<?php
						if($appointment_access)
						{
						?>
							<div class="panel panel-white Appoinment">
								<div class="panel-heading">
									<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/Appointment.png"?>" >
									<h3 class="panel-title"><?php _e('Appointment','hospital_mgt');?></h3>	
									<?php
									if($obj_hospital->role == 'patient')
									{
									?>		
										<a href="?dashboard=user&page=appointment&tab=addappoint&&action=insert" class="btn btn-default"style="float:right;margin-right:10px;"><i class="fa fa-plus-circle"></i> <?php _e('Add Appointment', 'hospital_mgt'); ?></a>
									<?php
									}
									?>	
								</div>
								<div class="panel-body">
									<div class="events">
										<?php								
										$appointment_data=$obj_appointment->get_appointment_on_fronted_dashboard();
										
										if(!empty($appointment_data))
										{
											foreach ($appointment_data as $retrieved_data)
											{		
												$patient_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->patient_id);
											?>									
											<div class="calendar-event"> 
												<p class="remainder_title Bold save1 show_task_event" id="<?php echo $retrieved_data->appointment_id; ?>" model="Appointment Details">
												<?php _e('Patient Name','hospital_mgt');?> : <?php  echo $patient_data['first_name']." ".$patient_data['last_name']; ?>  </p>
												<p class="remainder_date" style="width: 160px;background-position: 85px;">
												<?php 
												echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->appointment_date)); ?>		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $retrieved_data->appointment_time_with_a; ?>
												</p>
											</div>	
											<?php
											}
										}	
										?>
									</div>					
								</div>
							</div>
						<?php
						}
						?>	
						<!--  End Appoinment box -->
						 
						<!--  Start assigned bed Box -->
						<?php
						if($bedallotment_access)
						{
						?>
							<div class="panel panel-white event assignbed">
								<div class="panel-heading">
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/Assign--Bed-nurse.png"?>" >
								<h3 class="panel-title"><?php _e('Assigned Bed','hospital_mgt');?></h3>						
								</div>
								<div class="panel-body">
									<div class="events">
										<?php
										$obj_bed = new MJ_hmgt_bedmanage();
										$bedallotment_data=$obj_bed->get_bedallotment_on_fronted_dashboard();
										if(!empty($bedallotment_data))
										{
											foreach ($bedallotment_data as $retrieved_data)
											{
											?>									
												<div class="calendar-event"> 
													<p class="remainder_title Bold viewbedlist show_task_event" id="<?php echo $retrieved_data->bed_allotment_id; ?>" model="Assigned Bed Details" > <?php _e('Patient Name','hospital_mgt'); ?> : 	  
														<?php
														$patient_data =	MJ_hmgt_get_user_detail_byid($retrieved_data->patient_id);	
														echo $patient_data['first_name']." ".$patient_data['last_name'];
														?>
													</p>
													<p class="remainder_date">	<?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->allotment_date));?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php  echo date(MJ_hmgt_date_formate(),strtotime($retrieved_data->discharge_time));?> </p>
												</div>		
											<?php
											}
										}	
										?>	
									</div>                       
								</div>
							</div>
						<?php
						}
						?>
						<!-- End assigned bed Box -->
						<!--  Start Event Box -->
						<?php
						if($event_access)
						{						
						?>
							<div class="panel panel-white event">
								<div class="panel-heading ">
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/event.png"?>" >
								<h3 class="panel-title"><?php _e('Events','hospital_mgt');?></h3>						
								</div>					
								<div class="panel-body">
									<div class="events">	
									<?php         
									$args = array(								 
									  'post_type'   => 'hmgt_event',
									  'order'     => 'DESC',
									  'orderby'   => 'ID'
									);
									
									$retrieve_class = get_posts($args);
									
									if(!empty($retrieve_class))
									{ 
										$i=1;
										foreach ($retrieve_class as $retrieved_data)
										{ 
											$event_for_array=explode(",",get_post_meta( $retrieved_data->ID, 'notice_for',true));
											$role=MJ_hmgt_get_current_user_role();	
											
											if(in_array($role,$event_for_array))
											{
												if($i<=3)
												{	
												?>
													<div class="calendar-event">
														<p class="remainder_title Bold viewdetail show_task_event" id="<?php echo $retrieved_data->ID; ?>" model="Event Details">
															<?php echo $retrieved_data->post_title; ?>
														</p>									
														<p class="remainder_date">
															<?php echo date(MJ_hmgt_date_formate(),strtotime(get_post_meta($retrieved_data->ID,'start_date',true))); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo date(MJ_hmgt_date_formate(),strtotime(get_post_meta($retrieved_data->ID,'end_date',true))); ?>
														</p>
														<p class="remainder_title viewdetail">	
															<?php echo $retrieved_data->post_content; ?>
														</p>
													</div>
												<?php
												}
												$i++;
											}
										}
									}
									?>
									</div>                       
								</div>
							</div>						
							<!-- End Event Box -->
							<!--  Start Notice box -->
							<div class="panel panel-white nt">
								<div class="panel-heading">
								<img src="<?php echo HMS_PLUGIN_URL."/assets/images/dashboard/notice1.png"?>" >
								<h3 class="panel-title"><?php _e('Notice','hospital_mgt');?></h3>						
								</div>
								<div class="panel-body">
									<div class="events">
										<?php         
										$args = array(									 
										  'post_type'   => 'hmgt_notice',
										  'order'     => 'DESC',
										  'orderby'   => 'ID'
										);
										
										$retrieve_class = get_posts($args);
										
										if(!empty($retrieve_class))
										{ 
											$i=1;										
											foreach ($retrieve_class as $retrieved_data)
											{ 
												$notice_for_array=explode(",",get_post_meta( $retrieved_data->ID, 'notice_for',true));
												$role=MJ_hmgt_get_current_user_role();	
													
												if(in_array($role,$notice_for_array))
												{
													if($i<=3)
													{
													?>						
														<div class="calendar-event"> 
															<p class="remainder_title Bold viewdetail show_task_event" id="<?php echo $retrieved_data->ID; ?>" model="Notice Details">	
																<?php echo $retrieved_data->post_title; ?>	
															</p>
															<p class="remainder_date">	<?php echo date(MJ_hmgt_date_formate(),strtotime(get_post_meta($retrieved_data->ID,'start_date',true))); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo date(MJ_hmgt_date_formate(),strtotime(get_post_meta($retrieved_data->ID,'end_date',true))); ?></p>
															<p class="remainder_title viewdetail"><?php echo $retrieved_data->post_content; ?></p>
														</div>	
													<?php
													}
													$i++;
												}
											}
										}
										?>					
									</div>
								</div>
							</div>
						<?php
						}
						?>
						<!--  End Notice box -->
					</div>
				</div>
				<!---End new dashboard------>
			</div><!-- end dashboard content div-->
	    </div><!-- end page inner div-->
	</div><!-- end row div-->
 </div><!-- end container fluid div-->
</body>
</html>