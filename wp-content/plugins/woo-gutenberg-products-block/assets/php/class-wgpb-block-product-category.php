<?php
/**
 * Display the Products by Category block in the post content.
 * NOTE: DO NOT edit this file in WooCommerce core, this is generated from woocommerce-gutenberg-products-block.
 *
 * @package WooCommerce\Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handler for getting Products by Category for display.
 */
class WGPB_Block_Product_Category extends WGPB_Block_Grid_Base {
	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected $block_name = 'product-category';

	/**
	 * This function is not necessary in this block.
	 *
	 * @param array $query_args Query args.
	 */
	protected function set_block_query_args( &$query_args ) {}
}