<?php
/*
	Plugin Name: Safe Mode Loader
	Description: Makes it possible to enable a safe mode for WordPress. In safe mode, plugins will not be loaded and the default theme (if installed) will be activated.
	Version: 1.1.3
	Author: Uffe Fey, WordPress consultant
	Author URI: https://wpkonsulent.dk
*/
	$wpkonsulent_safemode_loader = new WPkonsulentSafeModeLoader();
	
	class WPkonsulentSafeModeLoader
	{
		function __construct()
		{
			// Only do this if safe mode is activated by querystring.
			if(isset($_GET['safe_mode']) && $_GET['safe_mode'] == '1')
			{
				add_filter('template', array($this, 'disable_theme'), 10, 1);
				add_filter('stylesheet', array($this, 'disable_theme'), 10, 1);
				add_filter('option_active_plugins', array($this, 'disable_plugins'), 10, 1);
				add_filter('plugin_action_links', array($this, 'plugin_links'), 10, 4);
			}
		}
		
		function disable_plugins($plugins)
		{
			// Returning an empty array will instruct the loader not to load any plugins at all.
			
			// HOWEVER, if you need to keep certain plugins activated, you can do this very easily.
			// Just modify the array below to contain the file names of the plugins you need to stay active.
			// Example: plugin file is located in "some-plugin/some-plugin.php"
			// return array('some-plugin/some-plugin.php');
			
			return array();
		}
		
		function disable_theme($theme)
		{
			if(defined('WP_DEFAULT_THEME'))
			{
				return WP_DEFAULT_THEME;
			}
			else
			{
				// Check if twenty * is installed, and if so, activate it.
				$themes = wp_get_themes();
				
				if(array_key_exists('twentytwenty', $themes))
					return 'twentytwenty';
				else if(array_key_exists('twentynineteen', $themes))
					return 'twentynineteen';
				else if(array_key_exists('twentyeighteen', $themes))
					return 'twentyeighteen';
				else if(array_key_exists('twentyseventeen', $themes))
					return 'twentyseventeen';
				else if(array_key_exists('twentysixteen', $themes))
					return 'twentysixteen';
				else if(array_key_exists('twentyfifteen', $themes))
					return 'twentyfifteen';
				else if(array_key_exists('twentyfourteen', $themes))
					return 'twentyfourteen';
				else if(array_key_exists('twentythirteen', $themes))
					return 'twentythirteen';
				else if(array_key_exists('twentytwelve', $themes))
					return 'twentytwelve';
				else if(array_key_exists('twentyeleven', $themes))
					return 'twentyeleven';
				else if(array_key_exists('twentyten', $themes))
					return 'twentyten';
			}
			
			// No default themes are installed, so we'll stick with the current active theme.
			return $theme;
		}
		
		function plugin_links($actions, $plugin_file, $plugin_data, $context)
		{
			// Make sure all plugins can be deactivated in safe mode.
			
			if($plugin_file != 'safe-mode/safe-mode.php')
			{			
				$actions['deactivate'] = '<a href="' . wp_nonce_url('plugins.php?action=deactivate&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'deactivate-plugin_' . $plugin_file) . '" title="' . esc_attr__('Deactivate this plugin') . ' (safe mode)' . '">' . __('Deactivate') . ' (safe mode)' . '</a>';
			}
			
			return $actions;
		}
	}
?>