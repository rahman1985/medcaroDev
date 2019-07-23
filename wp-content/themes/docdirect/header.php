<?php

/**

 * The header for our theme.

 *

 * Displays all of the <head> section and everything up till <div id="content">

 *

 * @package Doctor Directory

 */

?><!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

<meta charset="<?php bloginfo( 'charset' ); ?>">

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="profile" href="http://gmpg.org/xfn/11">

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head();?>
<!-- <script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=663f5467-d72b-4caa-baf9-6bee09ce54c3"> </script> -->

</head>

<body <?php body_class()?>>

<?php do_action('docdirect_init_headers');?>