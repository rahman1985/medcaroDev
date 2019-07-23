<div class="mailbox-content"><!--MAILBOX CONTENT DIV START-->
 	<table class="table">
 		<thead>
 			<tr>
 				<th class="text-right" colspan="5">
                 <?php 
                $message = $obj_message->hmgt_count_inbox_item(get_current_user_id());
              
 		$max = 10;
 		if(isset($_GET['pg'])){
 			$p = $_GET['pg'];
 		}else{
 			$p = 1;
 		}
 		 
 		$limit = ($p - 1) * $max;
 		$prev = $p - 1;
 		$next = $p + 1;
 		$limits = (int)($p - 1) * $max;
 		$totlal_message =count($message);
 		$totlal_message = ceil($totlal_message / $max);
 		$lpm1 = $totlal_message - 1;
 		$offest_value = ($p-1) * $max;
		echo $obj_message->hmgt_pagination($totlal_message,$p,$prev,$next,'page=hmgt_message&tab=inbox');?>
                </th>
 			</tr>
 		</thead>
 		<tbody>
 		<tr>
 			<th class="hidden-xs">
            	<span><?php _e('Message For','hospital_mgt');?></span>
            </th>
            <th><?php _e('Subject','hospital_mgt');?></th>
             <th>
                  <?php _e('Description','hospital_mgt');?>
            </th>
			 <th>
                  <?php _e('Date','hospital_mgt');?>
            </th>
            </tr>
 		<?php $post_id=0;
 		$message = $obj_message->hmgt_get_inbox_message(get_current_user_id(),$limit,$max);
		
 		foreach($message as $msg)
 		{
			if($post_id==$msg->post_id)
			{
				continue;
			}
			else
			{?>
 			<tr>
 			
            <td><?php echo MJ_hmgt_get_display_name($msg->sender);?></td>
             <td>
                 <a href="?page=hmgt_message&tab=inbox&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>"> <?php echo wordwrap($msg->msg_subject,10,"<br>\n",TRUE); if($obj_message->hmgt_count_reply_item($msg->post_id)>=1){?><span class="badge badge-success pull-right"><?php echo $obj_message->hmgt_count_reply_item($msg->post_id);?></span><?php } ?></a>
            </td>
            <td><?php echo wordwrap($msg->message_body,30,"<br>\n",TRUE);?>
            </td>
            <td>
				<?php  echo  date(MJ_hmgt_date_formate(),strtotime($msg->msg_date));?>
            </td>
            </tr>
 			<?php 
			}
			$post_id=$msg->post_id;
 		}?> 		
 		</tbody>
 	</table>
 </div><!--MAILBOX CONTENT DIV END-->
 <?php ?>