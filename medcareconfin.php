<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/** The name of the database for WordPress */
define( 'DB_NAME', 'flipware_adc' );

/** MySQL database username */
define( 'DB_USER', 'flipware_adc' );

/** MySQL database password */
define( 'DB_PASSWORD', '7CDBF2Ai6vfa9s4g3z5j8d1' );

/** MySQL hostname */
define( 'DB_HOST', '69.89.31.225' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '11`)6]%K!aRD(LjMQe;>0rs!i.9nTItYZ]]nE+8P.C0vT^)|t2fkf>JG)uyW7D-8');
define('SECURE_AUTH_KEY',  '>!z~h[UtvnYST1Y@K%pvhl8dkn_V|eJ+|Cw-PKYa]|6%dd$g=M(@-ECN59q}zrA;');
define('LOGGED_IN_KEY',    ';W?@7ou8yNd!&<S2raRD&VkFFsFpSAKtBtF?A&AWp|5iFH5!>:p%7>My,t-<Z[K0');
define('NONCE_KEY',        'I.%W@o$3|EK4T}H;mmo?Y<!3vWgc@a}Y/A|{q$e}xD,k.2I}P<zRd-qz(QaJPK&(');
define('AUTH_SALT',        'c|>vK2+|Sz%c,I;snGfG^m|!%}_ib~7OD_qmO{z-a~XqpdmbW[n|i.jg)|a# }fC');
define('SECURE_AUTH_SALT', '|_b?:Xv17WfQDW5P-Z0jmhA([dROxVT]Hx}~M6NdXgxK~I(*DGhTJs&tK~|xm=3]');
define('LOGGED_IN_SALT',   ']:3Xtr13s(N[8C/xN7WM@G8Ih,r@,<%pC]_F8d_SxiL;G:y%njjV^$>}*LaS/MKy');
define('NONCE_SALT',       'K@cZ+#-_pIsA#%d{$e[v=q9X>|YNY1*EG`28-UU_dS&W+Jeow,:9rWoL&/F5sxZV');


/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'adc_';



define( 'AUTOSAVE_INTERVAL',    300  );
define( 'WP_POST_REVISIONS',    5    );
define( 'EMPTY_TRASH_DAYS',     7    );
define( 'WP_AUTO_UPDATE_CORE',  true );
define( 'WP_CRON_LOCK_TIMEOUT', 120  );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
?>
