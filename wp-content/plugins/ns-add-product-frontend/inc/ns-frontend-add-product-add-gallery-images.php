<?php

function ns_add_gallery_images($post_id){
	$images_ids = null;
	if(isset($_POST["ns-image-from-list"])){
		$images_ids = sanitize_text_field($_POST["ns-image-from-list"]);
		update_post_meta( $post_id, '_product_image_gallery', $images_ids );
	}
}

?>