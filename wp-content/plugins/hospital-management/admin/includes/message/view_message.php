<script>
jQuery(document).ready(function() 
{
  jQuery("span.timeago").timeago();
});
</script>
<?php 
//Subject
if($_REQUEST['from']=='sendbox')
{
$message = get_post($_REQUEST['id']);
$box='sendbox';
if(isset($_REQUEST['delete']))
{
		echo $_REQUEST['delete'];
		wp_delete_post($_REQUEST['id']);
		wp_safe_redirect(admin_url()."admin.php?page=hmgt_message&tab=sentbox&message=2" );
		exit();
}
}

if($_REQUEST['from']=='inbox')
{
	$message = $obj_message->hmgt_get_message_by_id($_REQUEST['id']);
	MJ_hmgt_change_read_status($_REQUEST['id']);
	$box='inbox';

	if(isset($_REQUEST['delete']))
	{
		
		$obj_message->delete_message($_REQUEST['id']);
		wp_safe_redirect(admin_url()."admin.php?page=hmgt_message&tab=inbox&message=2" );
		exit();
	}

}
if(isset($_POST['replay_message']))
{
	$message_id=$_REQUEST['id'];
	$message_from=$_REQUEST['from'];
	$result=$obj_message->hmgt_send_replay_message($_POST);
	if($result)
		wp_safe_redirect(admin_url()."admin.php?page=hmgt_message&tab=view_message&from=".$message_from."&id=$message_id&message=1" );
}
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete-reply')
{
	$message_id=$_REQUEST['id'];
	$message_from=$_REQUEST['from'];
	$result=$obj_message->hmgt_delete_reply($_REQUEST['reply_id']);
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=hmgt_message&tab=view_message&action=delete-reply&from='.$message_from.'&id='.$message_id.'&message=2');
	}
}
?>
<div class="mailbox-content"><!--MAILBOX CONTENT DIV START-->
 	<div class="message-header"><!--MESSAGE HEADER DIV START-->
		<h3><span><?php _e('Subject','hospital_mgt')?> :</span>  <?php if($box=='sendbox'){ echo $message->post_title; } else{ echo $message->msg_subject; } ?></h3>
        <p class="message-date"><?php 
		
		if($box=='sendbox') { echo date(MJ_hmgt_date_formate(),strtotime($message->post_date ));} else { echo  date(MJ_hmgt_date_formate(),strtotime($message->msg_date )) ; }?></p>
	</div><!--MESSAGE HEADER DIV END-->
	<div class="message-sender"> <!--MESSAGE SENDER DIV START-->                               
    	<p><?php if($box=='sendbox'){
				$message_for=get_post_meta($_REQUEST['id'],'message_for',true);
				echo "From: ".MJ_hmgt_get_display_name($message->post_author)."<span>&lt;".MJ_hmgt_get_emailid_byuser_id($message->post_author)."&gt;</span><br>";
				if($message_for == 'user'){
				echo "To: ".MJ_hmgt_get_display_name(get_post_meta($_REQUEST['id'],'message_for_userid',true))."<span>&lt;".MJ_hmgt_get_emailid_byuser_id(get_post_meta($_REQUEST['id'],'message_for_userid',true))."&gt;</span><br>";}
				else{
				echo "To: ".__('Group','hospital_mgt');}?>
			<?php } 
			else
			{ 
				echo "From: ".MJ_hmgt_get_display_name($message->sender)."<span>&lt;".MJ_hmgt_get_emailid_byuser_id($message->sender)."&gt;</span><br> To: ".MJ_hmgt_get_display_name($message->receiver);  ?> <span>&lt;<?php echo MJ_hmgt_get_emailid_byuser_id($message->receiver);?>&gt;</span>
			<?php }?>
		</p>	
	</div><!--MESSAGE SENDER DIV END-->
    <div class="message-content"><!--MESSAGE CONTENT DIV START-->
	
    	<!--<p><?php if($box=='sendbox'){ echo $message->post_content; } else{ echo $message->message_body; }?></p>-->
		<p><?php $receiver_id=0;
		if($box=='sendbox'){ 
		echo wordwrap($message->post_content,120,"<br>\n",TRUE);
		$receiver_id=(get_post_meta($_REQUEST['id'],'message_for_userid',true));} else{ echo wordwrap($message->message_body,120,"<br>\n",TRUE);
		$receiver_id=$message->sender;}?></p>
		 <div class="message-options pull-right">
			<a class="btn btn-default" href="?page=hmgt_message&tab=view_message&id=<?php echo $_REQUEST['id'];?>&from=<?php echo $box;?>&delete=1" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');"><i class="fa fa-trash m-r-xs"></i><?php _e('Delete','hospital_mgt')?></a> 
	   </div>
    </div><!--MESSAGE CONTENT DIV END-->
	<?php if(isset($_REQUEST['from']) && $_REQUEST['from']=='inbox')
				$allreply_data=$obj_message->get_all_replies($message->post_id);
			else
				$allreply_data=$obj_message->get_all_replies($_REQUEST['id']);
		foreach($allreply_data as $reply)
		{ ?>
			<div class="message-content">
				<p><?php echo $reply->message_comment;?><br><h5>Reply By: <?php echo MJ_hmgt_get_display_name($reply->sender_id);if($reply->sender_id == get_current_user_id())
				{?>		
				<span class="comment-delete">
				<a href="admin.php?page=hmgt_message&tab=view_message&action=delete-reply&from=<?php echo $_REQUEST['from'];?>&id=<?php echo $_REQUEST['id'];?>&reply_id=<?php echo $reply->id;?>"><?php _e('Delete','hospital_mgt');?></a></span> 
				<?php } ?>
				<span class="timeago" title="<?php echo $reply->created_date;?>"></span>
				</h5> 
				</p>
			</div>
		<?php } ?>
	<form name="message-replay" method="post" id="message-replay">
		<input type="hidden" name="message_id" value="<?php if($_REQUEST['from']=='sendbox') echo $_REQUEST['id']; else echo $message->post_id;?>">
		<input type="hidden" name="user_id" value="<?php echo get_current_user_id();?>">
		<input type="hidden" name="receiver_id" value="<?php echo $receiver_id;?>">
		<div class="message-content">
		 <div class="col-sm-8">
			<textarea name="replay_message_body" maxlength="150" id="replay_message_body" class="form-control validate[required,custom[address_description_validation]] text-input"></textarea>
			
		   </div>
		   <div class="message-options pull-right reply-message-btn">
				<button type="submit" name="replay_message" class="btn btn-default"><i class="fa fa-reply m-r-xs"></i><?php _e('Reply','hospital_mgt')?></button>			
		   </div>
		</div>
	</form>
 </div><!--MAILBOX CONTENT DIV END-->
<?php ?>