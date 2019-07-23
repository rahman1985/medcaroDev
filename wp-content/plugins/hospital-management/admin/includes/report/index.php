<?php 
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'occupandy_report';
$month =MJ_hmgt_month_list();

if(isset($_POST['view_occupancy']))
{
	$start_date =MJ_hmgt_get_format_for_db($_POST['sdate']);
	$end_date =MJ_hmgt_get_format_for_db($_POST['edate']);
	
	if($end_date >= $start_date)
	{
		global $wpdb;
		$hmgt_bed_allotment = $wpdb->prefix."hmgt_bed_allotment";
		$hmgt_bed = $wpdb->prefix."hmgt_bed";
		$posts = $wpdb->prefix."posts";
		$sql_query = "SELECT post.post_title as bedtype,count(allotment_date) as count FROM $hmgt_bed_allotment as bed_altmt 
				inner join $hmgt_bed as bed on bed.bed_id =  bed_altmt.bed_number inner join $posts as post on post.id = bed.bed_type_id WHERE
				bed_altmt.allotment_date BETWEEN '$start_date' AND '$end_date' GROUP BY bed.bed_type_id";
		$result=$wpdb->get_results($sql_query);
		
		 $chart_array = array();
		$chart_array[] = array(__('Bed Number','hospital_mgt'),__('Bed Count','hospital_mgt'));
		foreach($result as $r)
		{
			$chart_array[]=array( "$r->bedtype",(int)$r->count);
		}
		
		$options = Array(
				'title' => __('Bed Occupancy Report','hospital_mgt'),
			  'pieHole' => 0.2,
				'pieSliceText' => 'value'
		); 
		require_once HMS_PLUGIN_DIR.'/lib/chart/GoogleCharts.class.php';
		$GoogleCharts = new GoogleCharts;
		if(isset($chart_array))
		{
			$chart = $GoogleCharts->load( 'pie' , 'chart_div' )->get( $chart_array , $options );
		}	
	}
	else
	{
		wp_redirect ( admin_url().'admin.php?page=hmgt_report&tab=occupandy_report&message=1');
	}
}
if(isset($_POST['view_operation']))
{
	$start_date =MJ_hmgt_get_format_for_db($_POST['sdate']);
	$end_date =MJ_hmgt_get_format_for_db($_POST['edate']);
	if($end_date >= $start_date)
	{
		global $wpdb;
		$hmgt_ot = $wpdb->prefix."hmgt_ot";
		$sql_query = "SELECT EXTRACT(DAY FROM operation_date) as date,count(*) as count FROM ".$hmgt_ot."
		WHERE operation_date BETWEEN '$start_date' AND '$end_date' group by date(operation_date) ORDER BY operation_date ASC";
		$result=$wpdb->get_results($sql_query);


		$chart_array = array();
		$chart_array[] = array(__('Date','hospital_mgt'),__('Number Of Operation','hospital_mgt'));
		foreach($result as $r)
		{
			$chart_array[]=array( "$r->date",(int)$r->count);
		}
		
		$options = Array(
				'title' => __('Operation Report','hospital_mgt'),
				'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'legend' =>Array('position' => 'right',
						'textStyle'=> Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),

				'hAxis' => Array(
						'title' =>  __('Date','hospital_mgt'),
						'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#222','fontSize' => 10),
						'maxAlternation' => 2


				),
				'vAxis' => Array(
						'title' =>  __('No of Operation','hospital_mgt'),
					 'minValue' => 0,
						'maxValue' => 5,
					 'format' => '#',
						'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#222','fontSize' => 12)
				)
		);
		require_once HMS_PLUGIN_DIR.'/lib/chart/GoogleCharts.class.php';
		$GoogleCharts = new GoogleCharts;
		if(isset($chart_array))
		{
			$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
		}
	}
	else
	{
		wp_redirect ( admin_url().'admin.php?page=hmgt_report&tab=operation_report&message=1');
	}
}
if(isset($_POST['view_fail_operation']))
{	
	$start_date =MJ_hmgt_get_format_for_db($_POST['sdate']);
	$end_date =MJ_hmgt_get_format_for_db($_POST['edate']);
	if($end_date >= $start_date)
	{
		global $wpdb;
		$hmgt_ot = $wpdb->prefix."hmgt_ot";
		$sql_query = "SELECT EXTRACT(DAY FROM operation_date) as date,count(*) as count FROM ".$hmgt_ot."
		WHERE operation_date BETWEEN '$start_date' AND '$end_date' AND out_come_status = 'Fail' group by date(operation_date)  ORDER BY operation_date ASC";
		$result=$wpdb->get_results($sql_query);

		$chart_array = array();
		$chart_array[] = array(__('Date','hospital_mgt'),__('Number Of Fail Operation','hospital_mgt'));
		foreach($result as $r)
		{
			$chart_array[]=array( "$r->date",(int)$r->count);
		}

		$options = Array(
				'title' => __('Operation Fail Report','hospital_mgt'),
				'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'legend' =>Array('position' => 'right',
						'textStyle'=> Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),

				'hAxis' => Array(
						'title' =>  __('Date','hospital_mgt'),
						'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#222','fontSize' => 10),
						'maxAlternation' => 2


				),
				'vAxis' => Array(
						'title' =>  __('No of Fail Operation','hospital_mgt'),
					 'minValue' => 0,
						'maxValue' => 5,
					 'format' => '#',
						'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#222','fontSize' => 12)
				)
		);
		require_once HMS_PLUGIN_DIR.'/lib/chart/GoogleCharts.class.php';
		$GoogleCharts = new GoogleCharts;
		if(isset($chart_array))
		{
			$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
		}
	}
	else
	{
		wp_redirect ( admin_url().'admin.php?page=hmgt_report&tab=operation_fail&message=1');
	}
}

?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#occupancy_report').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	var start = new Date();
		var end = new Date(new Date().setYear(start.getFullYear()+1));
		$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
		$('.sdate').datepicker({
			//startDate : start,
			endDate   : end,
			autoclose: true
		}).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('.edate').datepicker('setStartDate', minDate);
    });

 
		$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
		$('.edate').datepicker({
			endDate   : end,
			autoclose: true
		}).on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.sdate').datepicker('setEndDate', maxDate);
        });		
});
</script>
<div class="page-inner" style="min-height:1631px !important"><!-- PAGE INNER DIV START-->
	<div class="page-title"><!-- PAGE TITLE DIV START-->
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div><!-- PAGE TITLE DIV END-->
	<?php
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				_e('End date must be greater or equal than the start date','hospital_mgt');
			?></p></div>
			<?php 		
		}
	}
	?>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START-->
		<div class="row"><!-- ROW DIV START-->
			<div class="col-md-12">
				<div class="panel panel-white"><!-- PANEL WHITE DIV START-->
					<div class="panel-body"><!-- PANEL BODY DIV START-->
						<h2 class="nav-tab-wrapper">
							<a href="?page=hmgt_report&tab=occupandy_report" class="nav-tab <?php echo $active_tab == 'occupandy_report' ? 'nav-tab-active' : ''?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Bed Occupancy Report', 'hospital_mgt'); ?></a>  
							<a href="?page=hmgt_report&tab=operation_report" class="nav-tab <?php echo $active_tab == 'operation_report' ? 'nav-tab-active' : ''?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Operation Report', 'hospital_mgt'); ?></a>  
							<a href="?page=hmgt_report&tab=operation_fail" class="nav-tab <?php echo $active_tab == 'operation_fail' ? 'nav-tab-active' : ''?>">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.__('Operation Fail Report', 'hospital_mgt'); ?></a>   
						</h2>		
						  <form name="occupancy_report" action="" id="occupancy_report" method="post">
							<div class="form-group col-md-3">
								<label for="sdate"><?php _e('Start Date','hospital_mgt');?><span class="require-field">*</span></label>
							   
											
										<input type="text"  class="form-control sdate validate[required]" name="sdate"  value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];elseif(isset($_POST['sdate'])) echo $_POST['sdate'];?>">
										
							</div>
							<div class="form-group col-md-3">
								<label for="edate"><?php _e('End Date','hospital_mgt');?><span class="require-field">*</span></label>
									<input type="text"  class="form-control edate validate[required]" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];elseif(isset($_POST['edate'])) echo $_POST['edate'];?>">
										
							</div>
							<?php 
							$button_name ="";
							if($active_tab == 'occupandy_report')
								$button_name ='view_occupancy';
							if($active_tab == 'operation_report')
								$button_name ='view_operation';
							if($active_tab == 'operation_fail')
								$button_name ='view_fail_operation';
							
							?>
							<div class="form-group col-md-3 button-possition">
								<label for="subject_id">&nbsp;</label>
								<input type="submit" name="<?php echo $button_name;?>" Value="<?php _e('Go','hospital_mgt');?>"  class="btn btn-info"/>
							</div>	
						</form>
						<div class="clearfix"></div>
								
							<?php 	
							if(isset($result) && count($result) >0)
							{
							?>
								<div id="chart_div" style="width: 100%; height: 500px;"></div>
					  
							  <!-- Javascript --> 
							  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
							  <script type="text/javascript">
								<?php echo $chart;?>
								</script>
							<?php 
							}
							if(isset($result) && empty($result)) 
							{
							?>
								<div class="clear col-md-12"><?php _e("There is not enough data to generate a report.",'hospital_mgt');?></div>
							<?php
							}
							?>
					</div><!-- PANEL BODY DIV END-->
				</div><!-- PANEL WHITE DIV END-->
			</div>
		</div><!-- ROW DIV END-->
	</div><!-- MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER END-->

