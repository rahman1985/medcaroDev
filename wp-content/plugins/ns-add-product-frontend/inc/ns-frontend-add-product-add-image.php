<?php
function ns_add_image($post_id){

	$user_id = wp_get_current_user()->ID;

	if (!function_exists('wp_generate_attachment_metadata')){
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                require_once(ABSPATH . "wp-admin" . '/includes/media.php');
            }
	if ($_FILES['ns-thumbnail']['name']) {
		foreach ($_FILES as $file => $array) {
			if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
				return "upload error : " . $_FILES[$file]['error'];
			}
	
			$attach_id = media_handle_upload( $file, $post_id );

			return $attach_id;
		}   
	}
	return false;
			
}
?>