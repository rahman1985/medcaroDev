<?php
function ns_save_post(){
	/*Checking if user is logged in*/

	$user_id = wp_get_current_user()->ID;
	
	/*Get the inserted product title*/
	$ns_title = "New Product";
	if(isset($_POST["ns-product-name"])){
		$ns_title = sanitize_text_field($_POST["ns-product-name"]);
	}
	
	/*Get the inserted product short description*/
	$ns_short_desc = null;
	if(isset($_POST["ns-short-desc-text"])){
		$ns_short_desc = sanitize_text_field($_POST["ns-short-desc-text"]);
	}
	
	/*Get the inserted product post content*/	
	$ns_post_content = null;
	if(isset($_POST["ns-post-content-text"])){
		$ns_post_content = sanitize_text_field($_POST["ns-post-content-text"]);
	}
	
	/*If user wanna activate the reviews*/	
	$ns_is_reviews = "closed";
	if(isset($_POST["ns-comment-status"])){
		$ns_is_reviews = "open";
	}
	
	/*Get the menu order inserted by user*/
	$ns_menu_order = 0;
	if(isset($_POST["ns-menu-order"])){
		$ns_menu_order = $_POST["ns-menu-order"];	
	}
	
	$post = array(
    'post_author' => $user_id,
    'post_content' => $ns_post_content,	
    'post_status' => "publish",
    'post_title' => $ns_title,
    'post_parent' => '',
    'post_type' => "product",
	'post_excerpt' => $ns_short_desc,
	'comment_status' => $ns_is_reviews,
	'menu_order' => $ns_menu_order,
);

	//Create post
	$post_id = wp_insert_post( $post, true );

	return $post_id;
}
?>