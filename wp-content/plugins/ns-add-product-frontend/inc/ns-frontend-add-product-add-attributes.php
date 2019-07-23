<?php
function ns_add_attributes($post_id){
	$ns_outer_array = Array();
	if(isset($_POST["ns-color-attr"])){					//There's could be only one color attribute field
														//if is set then create the array(array) 
		$color_attributes = sanitize_text_field($_POST["ns-color-attr"]);
		$is_visible = 0;
		if(isset($_POST["ns-attr-visibility-status"])){
			$is_visible = 1;
		}
		$ns_attr = Array(
				'name' => "pa_color",
				'value' => "",
				'position' => "0",
				'is_visible' => $is_visible,
				'is_variation' =>  0,
				'is_taxonomy' => 1,
				);
		
		$ns_outer_array['pa_color'] = $ns_attr;			//adding the color with key 'pa_color' to let framework knows it is color
	    wp_set_object_terms($post_id, $_POST["ns-color-attr"], 'pa_color', false);
	
  }
  
  if(isset($_POST['ns-attribute-list'])){		//Check if user inserted custom attributes and loop over them
	  $num_custom_attr = intval(sanitize_text_field($_POST['ns-attribute-list']));

	  if($num_custom_attr >= 0){	
		  for($i=0; $i<$num_custom_attr; $i++){ 
				$is_visible = 0;
				$ns_attr_name = sanitize_text_field($_POST['ns-attr-names'.$i.'']);
				$ns_attr_value = sanitize_text_field($_POST['ns-attribute-values'.$i.'']);
				
				
				if(isset($_POST['ns-attr-visibility-status'.$i.''])){
					$is_visible = 1;
				}
				
				
				$ns_attr = Array(
					'name' => $ns_attr_name,
					'value' => $ns_attr_value,
					'position' => "1",
					'is_visible' => $is_visible,
					'is_variation' =>  0,
					'is_taxonomy' => 0,
					);
				array_push($ns_outer_array,  $ns_attr);		
		  }
	  }
  }
  if($ns_outer_array)
	update_post_meta( $post_id, '_product_attributes', $ns_outer_array );
  
  $arr_to_terms;
  if(isset($_POST["ns-attr-from-list"])){			//user selected an already saved color
		$arr_to_terms = explode(",",$_POST["ns-attr-from-list"]);
  }
  if(isset($_POST["ns-color-attr"])){				//user has inserted another new color
		array_push($arr_to_terms,$_POST["ns-color-attr"]);
  }
  if($arr_to_terms)									//if the array is not empty we have a new color or a already existing one
		wp_set_object_terms( $post_id, $arr_to_terms, 'pa_color'); 
}
?>