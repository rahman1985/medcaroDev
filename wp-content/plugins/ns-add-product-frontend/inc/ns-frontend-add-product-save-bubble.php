<?php
function ns_save_bubble($post_id){
	$is_any = false;
	/*
	$custom_bubble = null;
	if(isset($_POST["ns-bubble"])){
		$custom_bubble = sanitize_text_field($_POST["ns-bubble"]);
		$is_any = true;
	}
	 */
	 $bubble_title = null;
	 if(isset($_POST["ns-bubble-text"])){
		$bubble_title = sanitize_text_field($_POST["ns-bubble-text"]);
		$is_any = true;
	 }
	 
	 $cus_tab_title = null;
	 if(isset($_POST["ns-custom-tab"])){
		 $cus_tab_title = sanitize_text_field($_POST["ns-custom-tab"]);
		 $is_any = true;
	 }
     
	 $cus_tab_content = null;
	 if(isset($_POST["ns-cus-tab-content"])){
		 $cus_tab_content = sanitize_text_field($_POST["ns-cus-tab-content"]);
		 $is_any = true;
	 }
	 
     $cus_tab_top = null;
	 if(isset($_POST["ns-top-content"])){
		 $cus_tab_top = sanitize_text_field($_POST["ns-top-content"]);
		 $is_any = true;
	 }
     
	 $cus_tab_bottom = null;
	 if(isset($_POST["ns-bottom-content"])){
		$cus_tab_bottom = sanitize_text_field($_POST["ns-bottom-content"]);
		 $is_any = true;
	 }
    
	$ns_video = null;
	if(isset($_POST["ns-video"])){
		$ns_video = sanitize_text_field($_POST["ns-video"]);
		$is_any = true;
	}
	 
	 $ns_video_size = null;
	 if(isset($_POST["ns-video-size"])){
		 $ns_video_size = sanitize_text_field($_POST["ns-video-size"]);
		 $is_any = true;
	 }
	 
	if($is_any){
		$ns_bubble_arr = Array( Array(
		 '_bubble_new' => "yes",
		 '_bubble_text' => $bubble_title,
		 '_custom_tab_title' => $cus_tab_title,
		 '_custom_tab' => $cus_tab_content,
		 '_product_video' =>  $ns_video,
		 '_product_video_size' => $ns_video_size,
		 '_top_content' =>  $cus_tab_top,
		 '_bottom_content' => $cus_tab_bottom,
		 )
		);

		update_post_meta( $post_id, 'wc_productdata_options', $ns_bubble_arr );
	} 
     
 	
}

?>