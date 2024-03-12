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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'WMyPham' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         ')wH1jwv7]#y19@tL|1T#`xPG)u=Om@ehRLBhs1n/%6Sea4kiFc?d[tY*Lj_Oy>rX' );
define( 'SECURE_AUTH_KEY',  '9V?s,5Z_s#F3E+(k07rY0zPePKy0pKwdf T9UbG^n;8(B/(2pM,8M.%UVP=w0c&y' );
define( 'LOGGED_IN_KEY',    ',h[LVf2Opqx$k&!YI,#cr}=8;/C2d T}&o25g[JqH/6pq9KO_yI`B}ZgQve|jDs`' );
define( 'NONCE_KEY',        '0o&-KNdRw%*+i}H4vxzSN_9^RG&yk?CCzr.$mJc=IG@zd0ibT/e?&_LF3^Vw?Wgg' );
define( 'AUTH_SALT',        '~wV=jPSJNlo`$p$=DL7>?50(n7yAD[PF1wo/I7d~vKj*ObBYHx`JO{ow=$x+?tc^' );
define( 'SECURE_AUTH_SALT', 'ZV}u5WwR-}^(4 .ZCHFys:7w}#s_wo;9`9(3LrGA^%bs*?2Bz{zC9l/cqwj&-aSV' );
define( 'LOGGED_IN_SALT',   '=rGRF,4j5Fhn~cE( pVnRP@pD!$H-@.l#HSQw>=yx!O5)Q8.rGT4Mi|Cp)*(eU>}' );
define( 'NONCE_SALT',       'dkbip{(X_u<X<+| hox/yy/wM`5tWf5;L-smfq4!AeTIpO!K5t[;b(_AYKOF^Nsb' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
