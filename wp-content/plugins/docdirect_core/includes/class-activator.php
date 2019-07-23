<?php

/**
 * Fired during plugin activation
 *
 * @link       http://themographics.com
 * @since      1.0
 *
 * @package    DocDirect Core
 * @subpackage DocDirect Core/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0
 * @package    DocDirect Core
 * @subpackage DocDirect Core/includes
 * @author     Themographics <themographics@gmail.com>
 */
class DocDirect_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0
	 */
	public static function activate() {
        self::update_settings();
	}
	
	/**
	 * @init            Update settings
	 * @package         Docdirect
	 * @subpackage      Docdirect/admin/partials
	 * @since           1.0
	 * @desc           Update default options
	 */
    public static function update_settings() {
        update_option( 'docdirect_packages_settings','default');
    }

}
