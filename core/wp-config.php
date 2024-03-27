<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'if0_36235408_wp55' );

/** Database username */
define( 'DB_USER', '36235408_1' );

/** Database password */
define( 'DB_PASSWORD', 'hL3-pS824[' );

/** Database hostname */
define( 'DB_HOST', 'sql206.byetcluster.com' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'k181b2uwpub54h3hp6zbnvz79uzwe6pudwrxwitycvbvrntivfzhxedvjo6edirl' );
define( 'SECURE_AUTH_KEY',  'lyewluewxueqvsisq80mer1e2aus1appavike2yufl0bifudlxmtbaf9zjtzcew3' );
define( 'LOGGED_IN_KEY',    'rl1iwwwjbpvvsdws3ai2rqcnvnz39jdebip8h8ozhuzwhqecd4n4okqjgpayti6a' );
define( 'NONCE_KEY',        'qj00r3shexsl5yoqheh7tpliluidqrninnpum7hsmsa7ussbm4aaw7uoseys15zv' );
define( 'AUTH_SALT',        'pfgcn4f57oqgrwid0tmws8ildpb9rmeivj9ndouyamzluctbybqs6rcngdbujtoh' );
define( 'SECURE_AUTH_SALT', 'gd9ejddoopw6vyxocyaf7xo6wz9n72arynrnzwuyy4ghsnfslnjmo08ywfigadct' );
define( 'LOGGED_IN_SALT',   'rqve7hze5ekyqlca9xznw0ifys839tna0ncj25rh1qsl8rklu50rnrwxxcg5h30o' );
define( 'NONCE_SALT',       'jl8c1ydrurucsd82d2q3iw4xraphh33igt3n9xgfwpiptm2dqueboikfvnyxk7iu' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpcb_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
