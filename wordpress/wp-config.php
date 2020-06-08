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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('WPLANG', 'zh_CN');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'BP7}=QVH@?y5DD5.aAPWiwc`T1YS7h[_bXyi0c5J-mTK>ff_{sls}buprk-1J23e' );
define( 'SECURE_AUTH_KEY',  'z%d*u_W*2%/:edTZMEdz-n1<xr>c8CDy2>$7EXml|ecc2#H|9FijVS7($+{HKDPX' );
define( 'LOGGED_IN_KEY',    'MaRdCe3%[Y]X%,#/~EX6yjI%{.a]s.@~iu55Lx3q7}S2:%X3pa4Dwlt8b,#M>_+:' );
define( 'NONCE_KEY',        '<bJOMHa{JH.F=wG66L(PIOsu3U>OTba%?]|5M<tH1.+{Khd}@SDWO78VoBT[A#8T' );
define( 'AUTH_SALT',        '(1wUqW70ojLX.2@qx<20DO.tkNfHM>2+:%)KO>HaBq}HH~T*lP_#@7uK-wx37 z#' );
define( 'SECURE_AUTH_SALT', '%&59qpLPrWeac!Ni1YvzWW!idtkLwfjsr(f(TH[):8#[;`-kc(RM0LB|`!6>c3Zb' );
define( 'LOGGED_IN_SALT',   '2uX!mUT07K,wQ!f*G>0MaXhkLQ46Q)q~Of|,t9ReHGNM5[zc5<s1)n?q+n]U[7=G' );
define( 'NONCE_SALT',       'Fyj*C-1 cs*~-VHM2e7A!y%)|#HSa( Y}-q!5w8M9loH]`o8!1k8*F;f%7Wvu,{R' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

define("FS_METHOD", "direct");
define("FS_CHMOD_DIR", 0777);
define("FS_CHMOD_FILE", 0777);
//define('WP_HOME', 'http://108.61.194.183/wordpress');
//define('WP_SITEURL', 'http://108.61.194.183/wordpress');
