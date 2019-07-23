<?php
require_once("../../../wp-load.php");
global $current_user, $wp_roles,$userdata,$post;
$user_identity	= $current_user->ID;
$db_user_type	= get_user_meta( $user_identity, 'user_type', true);
$args = array( 
    'author'                => $url_identity,
    'post_type'             => 'docappointments',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1,
    'order'	                => 'DESC',
    'orderby'               => 'ID',
  );
$query = new WP_Query($args);
if($query->have_posts()):
    $msg = [];
    while($query->have_posts()) : $query->the_post();
        global $post;
        $bk_status = get_post_meta($post->ID, 'bk_status',true);
        $bk_username = get_post_meta($post->ID, 'bk_username',true);
        $bk_user_to = get_post_meta($post->ID, 'bk_user_to',true);
        
        $db_user_first_name	= get_user_meta( $bk_user_to, 'first_name', true);
        $db_user_last_name	= get_user_meta( $bk_user_to, 'last_name', true);
        $doctorName = ucfirst($db_user_first_name)." ".ucfirst($db_user_last_name);

        $bk_booking_note_status = get_post_meta($post->ID, 'bk_booking_note_status',true);
        $bk_booking_note_approve_status = get_post_meta($post->ID, 'bk_booking_note_approve_status',true);
        $bk_remainder_note_status = get_post_meta($post->ID, 'bk_remainder_note_status',true);
        if($db_user_type == "visitor" && $bk_booking_note_approve_status <1 && $bk_booking_note_status >0 && $bk_status != "pending"){
            //$msg['id'][] = $post->ID;
            //$msg['msg'][] = "Appointment Approved by ".$doctorName;
            $status_text = "confirmed";
            $status_icon = "glyphicon glyphicon-comment";
            if ($bk_status == "cancelled"){
                $status_text = "cancelled";
                $status_icon = "glyphicon glyphicon-warning-sign";
            }
            $msg['title'] = "Appointment status";
            $msg['msg'] = "Your appointment is ".$status_text;
            $msg['icon'] = $status_icon;
        }     
        if($bk_booking_note_status <1 && $db_user_type != "visitor"){
            //$msg['id'][] = $post->ID;
            //$msg['msg'][] = "Appointment booked by ".$bk_username;
            $msg['title'] = "Appointment notification";
            $msg['msg'] = "You have recived appointment request";
            $msg['icon'] = "glyphicon glyphicon-envelope";
        }
    endwhile;
    wp_reset_postdata();
    echo json_encode($msg);
    exit;
endif;
?>