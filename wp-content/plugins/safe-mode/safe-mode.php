<?php
/*
	Plugin Name: Safe Mode
	Description: Makes it possible to enable safe mode for WordPress. In safe mode, plugins will not be loaded and the default theme (if installed) will be activated.
	Version: 1.1.3
	Author: Uffe Fey, WordPress consultant
	Author URI: https://wpkonsulent.dk
*/
	$wpkonsulent_safemode = new WPkonsulentSafeMode();
	
	class WPkonsulentSafeMode
	{
		function __construct()
		{
			register_activation_hook(__FILE__, array($this, 'activate'));
			register_deactivation_hook(__FILE__, array($this, 'deactivate'));
		}
		
		function activate()
		{
			// This plugin works by the somewhat hidden feature of WordPress called MU-plugins (that's short for 'must use', not the old abbreviation for multisite).
			// So basically we'll make sure the folder exists and copy a file to that folder.
			
			if(!file_exists(WP_CONTENT_DIR . '/mu-plugins/'))
				@mkdir(WP_CONTENT_DIR . '/mu-plugins/');
		
			if(file_exists(WP_CONTENT_DIR . '/mu-plugins/safe-mode-loader.php'))
				@unlink(WP_CONTENT_DIR . '/mu-plugins/safe-mode-loader.php');
				
			if(file_exists(WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)) . '/loader/safe-mode-loader.php'))
				@copy(WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)) . '/loader/safe-mode-loader.php', WP_CONTENT_DIR . '/mu-plugins/safe-mode-loader.php');
		}
		
		function deactivate()
		{
			if(file_exists(WP_CONTENT_DIR . '/mu-plugins/safe-mode-loader.php'))
				@unlink(WP_CONTENT_DIR . '/mu-plugins/safe-mode-loader.php');
		}
	}
?>