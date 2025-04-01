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
define( 'DB_NAME', 'WordPress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         'e*?&hlYfG%#i[Sm|f?*F^y2#5%#1{00Bhi._F8[*Sn>d0Gl`+D_bbCf_85B28Ar1' );
define( 'SECURE_AUTH_KEY',  's9~M7R)rCEm*vnSDVAWN&N%/g/^qlLn22f%[?R`1(yGTj4a$XC4<k6zr.vON(V)f' );
define( 'LOGGED_IN_KEY',    '17WJXbKz:L^oG6N=Koh#%Ow_VV.1T[2aG24}_M5M{RG;6oj,%xlXwlW%S# e<exB' );
define( 'NONCE_KEY',        '?kP`lckx9YG~>?Va~?!q5ZH(9/|3$J@-i5m8Crx#Z&Xqa6>c>LKy07Zmt3%B-7-f' );
define( 'AUTH_SALT',        'g4!BV,Wg`VTvxX?u#hnPw6_QY0Zp#*+96Lp>((2G2)}NPF;[&/SHiv9NJ=R!^{B[' );
define( 'SECURE_AUTH_SALT', '/t8f^<k*:gB1|EcUT:v%H{(JMgX:Vjf9?B2CCTs~Ibo@m-/;]=lxNVl-q3wR;7qW' );
define( 'LOGGED_IN_SALT',   '!s1(IJ)@iTR@d^^(Qp7OZZ7;8S5S|]KwOTtyk$xH&c]i6Z*F=$z%hv V$)tDg2XA' );
define( 'NONCE_SALT',       '-G!JAI$77#U-EqD~?()5)*_/T(EOy~/$.Ziob6B9%5X0NQGOy@23O3k}Sq&q#AW`' );

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
