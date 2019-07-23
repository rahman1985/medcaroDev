<?php 
$retval = $api->campaigns();
$retval1 = $api->lists();
?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#setting_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
} );
</script>
<div class="panel-body"><!-- PANEL BODY DIV START -->
    <form name="student_form" action="" method="post" class="form-horizontal" id="setting_form">
	    <div class="form-group">
			<label class="col-sm-2 control-label" for="quote_form"><?php _e('MailChimp list','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="list_id" id="quote_form"  class="form-control validate[required]">
					<option value=""><?php _e('Select list','hospital_mgt');?></option>
					<?php 
					foreach ($retval1['data'] as $list)
					{
						echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
					}
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="quote_form"><?php _e('Campaign list','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="camp_id" id="quote_form"  class="form-control validate[required]">
					<option value=""><?php _e('Select Campaign','hospital_mgt');?></option>
					<?php 
					foreach ($retval['data'] as $c)
					{    						
						echo '<option value="'.$c['id'].'">'.$c['title'].'</option>';
					}
					?>
				</select>
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">        	
			<input type="submit" value="<?php _e('Send Campaign', 'hospital_mgt' ); ?>" name="send_campign" class="btn btn-success"/>
		</div>
    </form>
</div><!-- PANEL BODY DIV END -->