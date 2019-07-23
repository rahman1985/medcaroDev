=== Safe Mode ===
Contributors: wpkonsulent
Tags: safe mode, debug, recovery, error
Requires at least: 3.0.1
Tested up to: 5.0.2
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Makes it possible to enable safe mode for WordPress. In safe mode, plugins will not be loaded and the default theme (if installed) will be activated.

== Description ==

This plugin enables safe mode for WordPress. This means:

* No plugins will be loaded.
* A default theme will be temporarily activated (if it is installed).

= Why is this useful? =
Whenever something's wrong with a WordPress site, the first rule of thumb is to disable plugins and revert to the default theme if possible. Depending on the nature of the error, that is not always an option. The back end (dashboard) may also be down or you may not have FTP access to manually disable plugins and themes.

**Enter Safe Mode**. Safe Mode will completely disable all plugins and the active theme temporarily for a single page view - ultimately enabling you to log in and remove the offending plugin. (This is not guaranteed to work in all scenarios, please read the disclaimer)

= How does it work? =
If your site crashes due to an upsetting plugin or theme, all you have to do is add a querystring parameter to the URL. Doing that will temporarily disable all plugins for that single page view, as well as temporarily activate a default theme if one is installed.

Let's say you're the owner of www.example.com. To enable safe mode for one particular page, you add this to the URL: "?safe_mode=1".

Bear in mind that you have to do this for every view. The querystring parameter isn't carried on automatically. So, for instance:

* If you need to log in, go to: www.example.com/wp-admin/?safe_mode=1
* If you need to go to plugin management, go to: www.example.com/wp-admin/plugins.php?safe_mode=1
* If you need to go to theme management, go to: www.example.com/wp-admin/themes.php?safe_mode=1

When you go to plugin management, all plugins will seem to be deactivated (due to the way Safe Mode works), but you'll still be able to explicitly deactivate each plugin. Just use the "Deactivate (safe mode)" option.

= What are default themes? =
By default theme, I'm referring to the themes that ships with WordPress, you know, the Twenty "something" ones.

The plugin checks if any of these themes are installed, and if so, activates the first theme it encounters. Thus I highly recommend that you keep one of those themes installed at all times. If you don't, safe mode will keep your current active theme, and that theme may just be the offender - leaving safe mode useless. Keep this in mind.

= Disclaimer =
This plugin will be able to handle many scenarios, but not all. If your site's crash is caused by a database crash, or something that simply brings down the PHP parser, like a call to an undefined function, Safe Mode won't be able to do anything about that.

== Installation ==

1. Upload the `safe-mode` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

NOTE: Upon activation, the plugin tries to copy a file to the `/wp-content/mu-plugins/` directory. If the `mu-plugins` directory does not exist, the plugin will attempt to create it and copy the file. Depending on your file permissions it may not be successful. If not, you'll have to create the directory manually and copy the file `/wp-content/plugins/safe-mode/loader/safe-moder-loader.php` to the `/wp-content/mu-plugins/` directory.

== Frequently Asked Questions ==

No questions asked so far..

== Changelog ==

= 1.1.3 =
* Code maintenance - nothing new in terms of features has been added.
* Added support for new default themes in case the theme constant WP_DEFAULT_THEME somehow doesn't work.

= 1.1.2 =
* If you need to, you can keep certain plugins active by modifying an array in safe-mode-loader.php. Comments in the source code have been added, explaining how to do that. Thanks to KZeni for suggesting that in the support forum.
* Added Twenty Fourteen and Fifteen (for future support) to the list of default themes.

= 1.1.1 =
* Fixed embarrassing typo and removed warnings. Thanks to Doug Sparling for pointing it out.

= 1.1 =
* Changed default theme "guessing" to use the constant WP_DEFAULT_THEME. Also added Twenty Thirteen to the fallback in case said constant isn't defined.

= 1.0 =
* Initial release.