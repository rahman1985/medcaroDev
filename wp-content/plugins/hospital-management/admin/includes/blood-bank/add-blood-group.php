<?php 
$obj_bloodbank=new MJ_hmgt_bloodbank();
if($active_tab == 'addbloodgroop')	
    {
	MJ_hmgt_browser_javascript_check();
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$result = $obj_bloodbank->get_single_bloodgroup($_REQUEST['bloodgroup_id']);	
	} ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#bloodgroup_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
} );
</script>
    <div class="panel-body"><!-- PANEL BODY DIV START-->
        <form name="bloodgroup_form" action="" method="post" class="form-horizontal" id="bloodgroup_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="bloodgroup_id" value="<?php if(isset($_REQUEST['bloodgroup_id'])) echo $_REQUEST['bloodgroup_id'];?>"  />
			<div class="form-group">
				<label class="col-sm-2 control-label" for="blood_group"><?php _e('Blood Group','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<?php if($edit){ $userblood=$result->blood_group; }elseif(isset($_POST['blood_group'])){$userblood=$_POST['blood_group'];}else{$userblood='';}?>
					<select id="blood_group" class="form-control validate[required]" name="blood_group">
					<option value=""><?php _e('Select Blood Group','hospital_mgt');?></option>
					<?php foreach(MJ_hmgt_blood_group() as $blood){ ?>
							<option value="<?php echo $blood;?>" <?php selected($userblood,$blood);  ?>><?php echo $blood; ?> </option>
					<?php } ?>
				</select>
				</div>
			</div>
			
			<div class="form-group margin_bottom_5px">
				<label class="col-sm-2 control-label" for="blood_status"><?php _e('Number Of Bags','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="blood_status" class="form-control validate[required] text-input" type="number" min="0" onKeyPress="if(this.value.length==4) return false;" value="<?php if($edit){ echo $result->blood_status;}elseif(isset($_POST['blood_status'])) echo $_POST['blood_status'];?>" name="blood_status">
				</div>
			</div>
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save','hospital_mgt'); }else{ _e('Save','hospital_mgt');}?>" name="save_bloodgroup" class="btn btn-success"/>
			</div>
	    </form>
    </div><!-- PANEL BODY DIV END-->
        
     <?php 
	 }
	 ?>