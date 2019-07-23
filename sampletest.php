<?php
require_once( dirname( __FILE__ ) . '/wp-config.php' );
global $wpdb;

ini_set('display_errors', 1);
error_reporting(E_ALL);
$table = "adc_comments";
$result = $wpdb->get_results("SELECT * FROM adc_comments");

if ($result) {
	echo "data successfully loaded";
	print_r($result);
}
else{
	echo "data not loaded";
}
?>