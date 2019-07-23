<?php
function ns_save_categories($post_id){
	$ns_cat_array = array();
	
	$all_existent_cat = get_terms( array(
										'taxonomy' => 'product_cat',
										'hide_empty' => false,
									));	
							
	foreach($all_existent_cat as $cat_obj){		
		/*already saved categories*/
		$remove_spaces = str_replace(' ', '_', $cat_obj->name);
		if(isset($_POST[$remove_spaces])){
			$cat = sanitize_text_field($_POST[$remove_spaces]);
			array_push($ns_cat_array, $cat);
		}

		/*set product categories*/
		if($ns_cat_array){
			wp_set_object_terms($post_id, $ns_cat_array, 'product_cat');
		}
	
	}
	
	/*$ns_cat_array = array();
	if(isset($_POST["clothing"]))
		array_push($ns_cat_array,$_POST["clothing"]);

	if(isset($_POST["hoddies"]))
		array_push($ns_cat_array,$_POST["hoddies"]);

	if(isset($_POST["tshirts"]))
		array_push($ns_cat_array,$_POST["tshirts"]);

	if(isset($_POST["music"]))
		array_push($ns_cat_array,$_POST["music"]);

	if(isset($_POST["albums"]))
		array_push($ns_cat_array,$_POST["albums"]);

	if(isset($_POST["singles"]))
		array_push($ns_cat_array,$_POST["singles"]);

	if(isset($_POST["posters"]))
		array_push($ns_cat_array,$_POST["posters"]);*/

	/*set product categories*/
	/*if($ns_cat_array){
		wp_set_object_terms($post_id, $ns_cat_array, 'product_cat');
	}*/
	
}
?>