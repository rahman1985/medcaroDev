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
/** The name of the database for WordPress */
define('DB_NAME', 'medcaroDev');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**
* Authentication Unique Keys and Salts.
*
* Change these to different unique phrases!
* You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
* You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
*
* @since 2.6.0
*/
define('AUTH_KEY', '6BGrkN4ou5XqhwP3jyp4jJPADoWo5Cm1iaey8mEgVnJ9KtFM0kMGmaNsk3JJLtVb');
define('SECURE_AUTH_KEY', 'FHfg5aK3xISdH85eeMoGvEppFc9vcDIusXRXjLjBkoDwEPlVBbtukES8tqFk0Y1G');
define('LOGGED_IN_KEY', 'g2im5anfCOIAKnZ45Zoa5yTbWkfhcVZDNyl4Yb0qk2rS+w/UXd2Iv7FaaLRXa9g0');
define('NONCE_KEY', 'd2QTyMgyHdQSimJwsysNTCH5U0tDY6RWS6gML+7k7j9AXemDGYOgrzCCXqReU8yx');
define('AUTH_SALT', 'gJWKKKYgoqvFgDbe0CnZSoqlRcazurDlaHcJ1gGK8iqI4elycjMtYQJLQ/9+1Ej1');
define('SECURE_AUTH_SALT', 'o9tGX7LjiKCT3sC3ssgKNsKbkBwj10OjUatlzPQ9vlcyOuSHE8NMkqTbTSv1cP4r');
define('LOGGED_IN_SALT', 'FM4P/dT1H4p0c2Dxq77naUBZOPFyPnFTRRnde0NwgauITSkrOOoRG3zOulgeSRzm');
define('NONCE_SALT', 'SYFJeZOUIVht57yoZqUTNOj228KZCx44VJueuIVacxR4gifRZx6dGMsbiaYQaSCv');

define( 'WP_DEBUG', false );
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
define('FS_METHOD', 'direct');
