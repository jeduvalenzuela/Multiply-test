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
define( 'DB_NAME', 'multiply' );

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
define( 'AUTH_KEY',         'wR+DTVa]A2ajT$#`_#!INW64~oS^G3N5N6..t]O#}E(AL$77i;IGF_/.Ah^;u^PJ' );
define( 'SECURE_AUTH_KEY',  '57gbIU{/|LR_}FL)2,sH6c-rl71awj).M}_[=P{%isgU}<nEU.yt_rGc;cP(WZ!S' );
define( 'LOGGED_IN_KEY',    'Isti:*c3hF`],?R=pi,qyTT{:l:HNqFNI$xvJ/`3W/MK&F :%f_FMY_1Ly+[_?/@' );
define( 'NONCE_KEY',        'Cd&SaO)fb3+uoUr_v(m4!ujkywI*P/w,|Aex}E8};!I1+N`VONeTt5;>*.rd(eTx' );
define( 'AUTH_SALT',        ': !2S=;VSU4:6II0-I6r5##>QLy*??YX<7Wr.,f bfg>p:[x+U/ox~os)NCX_ZMV' );
define( 'SECURE_AUTH_SALT', '_vey)dkeX(Z`am+CWO2eoxJh1q*,iT9gBGm*`B|FBpUr{vf&rn`,51?NMTq3g|@v' );
define( 'LOGGED_IN_SALT',   'LuSeBU`pV(?|GGXW{k203{L}w:X;|LO8Oq7=b-yt>SOq5/N?f*r0h+]1-IUMcTg&' );
define( 'NONCE_SALT',       'WU0*t6WN;I/VkvES0=$==KPLcK4z-`~tFQX<0=$a/,Zb@aT$DmyZjl3q`_fr>`A3' );

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
