<?php
function ns_save_tags($post_id){
	/*First need to sanitize the post variables, then explode the string on the comma to have the array*/
	$ns_tags_comma = null;
	if(isset($_POST["ns-new-tag-product"]))
		$ns_tags_comma = sanitize_text_field($_POST["ns-new-tag-product"]);

	$ns_tags = explode("," , $ns_tags_comma);

	/*set the product tags*/
	if($ns_tags){
		wp_set_object_terms($post_id, $ns_tags, 'product_tag');
	}
	
}
?>