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
    while($query->have_posts()) : $query->the_post();
        global $post;
        $bk_booking_note_status = get_post_meta($post->ID, 'bk_booking_note_status',true);
        $bk_booking_note_approve_status = get_post_meta($post->ID, 'bk_booking_note_approve_status',true);
        $bk_remainder_note_status = get_post_meta($post->ID, 'bk_remainder_note_status',true);
        $bk_user_to = get_post_meta($post->ID, 'bk_user_to',true);
        $bk_user_from = get_post_meta($post->ID, 'bk_user_from',true);
        if($db_user_type == "visitor" && $bk_booking_note_approve_status <1 && $bk_booking_note_status >0){
            if($user_identity == $bk_user_from){
                update_post_meta($post->ID, 'bk_booking_note_approve_status', 1);
            }
        }        
        if($bk_booking_note_status <1 && $db_user_type != "visitor"){
            if($user_identity == $bk_user_to){
                update_post_meta($post->ID, 'bk_booking_note_status', 1);
            }
        }
    endwhile;
    wp_reset_postdata();
endif;
?>