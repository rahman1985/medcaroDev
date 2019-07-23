<script>
function myFunction() 
{
    confirm('<?php _e('Press a button!','hospital_mgt');?>');
}
</script>
<?php 
if(isset($_REQUEST['clear_log']))
{
	$path = $_REQUEST['path'];
	$fp = fopen($path, "r+");
	ftruncate($fp, 0);
	fclose($fp);
}
?>
<div class="page-inner" style="min-height:1631px !important"><!-- PAGE INNER DIV START-->
	<div class="page-title"><!-- PAGE TITLE DIV START-->
		<h3><img src="<?php echo get_option( 'hmgt_hospital_logo', 'hospital_mgt'); ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('hmgt_hospital_name','hospital_mgt');?></h3>
	</div><!-- PAGE TITLE DIV END-->
	<div  id="main-wrapper" class="marks_list"><!--MAIN WRAPPER DIV START-->
		<div class="panel panel-white"><!-- PANEL WHITE DIV START-->
			<div class="panel-body"> <!--PANEL BODY DIV START-->
				<h2 class="nav-tab-wrapper">
					<a href="?page=hmgt_audit_log" class="nav-tab  nav-tab-active">
					<?php echo '<span class="dashicons dashicons-welcome-view-site"></span> '.__('Audit Log/Activity', 'hospital_mgt'); ?></a>					
				</h2>
				<div class="panel-body"> <!--PANEL BODY DIV START-->
					<form name="bed_form" action="" method="post" class="form-horizontal" id="bed_form">
						<div class="audit_button">
							<?php echo '<a href="'.HMS_PLUGIN_URL.'/download_log.php?mime=hmgt_log.txt&title=audit_log.txt&token='.HMS_LOG_file.'" class="btn btn-success">'.  __('Download Log','hospital_mgt') .'</a>';?>
							<input type="hidden" name="path" value = "<?php echo HMS_LOG_file;?>" >
							<input type="submit" value="<?php _e('Clear Log','hospital_mgt');?>" onclick="return confirm('<?php _e('Are you sure you want to clear log ?','hospital_mgt');?>');" name="clear_log" class="btn btn-success"/>
						</div>
						<div class="aduit_log_file">
							<?php 
							if (file_exists(HMS_LOG_file)) 
							{
								foreach(file(HMS_LOG_file) as $line) 
								{
									echo "<P>".$line. "<P>";
								}
							}
							else 
							{
								_e("No any Log found",'hospital_mgt');
							}
							?>
						</div>
						<div class="audit_button">
							<?php echo '<a href="'.HMS_PLUGIN_URL.'/download_log.php?mime=hmgt_log.txt&title=audit_log.txt&token='.HMS_LOG_file.'" class="btn btn-success">'.  __('Download Log','hospital_mgt') .'</a>'; 							   
							?>
							<input type="hidden" name="path" value = "<?php echo HMS_LOG_file;?>" >
							<input type="submit"  value="<?php _e('Clear Log','hospital_mgt');?>" onclick="return confirm('<?php _e('Are you sure you want to clear log ?','hospital_mgt');?>');"  name="clear_log" class="btn btn-success"  />
						</div>
					</form>					
				</div> <!--PANEL BODY DIV END-->
			</div><!--PANEL BODY DIV END-->	
		</div>	<!-- PANEL WHITE DIV END-->
    </div><!--MAIN WRAPPER DIV END-->
</div><!-- PAGE INNER DIV END-->