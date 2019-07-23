<?php
/*
Plugin Name: My Training Page
Description: This plugin is used for display the post in "My Training Page"
Version:     1.0
Author:      Syed Abdul Rahman
Author URI:  https://www.mitosistech.com/
*/
/*Custom Post type start*/

function cw_post_type_training_page() {

    $supports = array(
        'title', // post title
        'editor', // post content
    );

    $labels = array(
        'name' => _x('My Training Page', 'plural'),
        'singular_name' => _x('My Training Page', 'singular'),
        'menu_name' => _x('My Training Page', 'admin menu'),
        'name_admin_bar' => _x('My Training Page', 'admin bar'),
        'add_new' => _x('Add New', 'add new'),
        'add_new_item' => __('Add New post'),
        'new_item' => __('New post'),
        'edit_item' => __('Edit post'),
        'view_item' => __('View post'),
        'all_items' => __('All post'),
        'search_items' => __('Search post'),
        'not_found' => __('No post found.'),
    );

    $args = array(
        'supports' => $supports,
        'labels' => $labels,
        'public' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'my-training-page'),
        'has_archive' => true,
        'hierarchical' => false,
    );
    register_post_type('my-training-page', $args);
}
add_action('init', 'cw_post_type_training_page');

/*Custom Post type end*/
?>