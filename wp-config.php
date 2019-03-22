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
define( 'DB_NAME', 'wordpress_b2bnetworking' );

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

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '?&-C5_sjhp1EeAY.;2-vEP6FGjH*;~i*$4QA7>=mQ0Hbq}9+o?j$@!W,$88f=OdJ' );
define( 'SECURE_AUTH_KEY',  '_QPNMgM-=A[eskOr70|>LvY6HK q`sie&ug-]z7zB]Bg>^n)7k/J>5k?(i4.0mb{' );
define( 'LOGGED_IN_KEY',    'N|F|LXMHzRMCbQ$oF:=TJK+7]I5 0/=FoAwz0e70 }kxHo8-<,t<gXw>st)#fWw<' );
define( 'NONCE_KEY',        '+wl3WoZRqr`ym:_axp*Qx-3$dQc*~$235tiG^ 2*XLK;,-,()dPhhU$&nYv8H)tQ' );
define( 'AUTH_SALT',        'uk-rN>K&7 3q)N-Z/V O0#?RD]iVYiY*jl%z2.?njiOObhp^Er%mjP??ZTGk[ kw' );
define( 'SECURE_AUTH_SALT', '=83[~wOyF5Y2u4f(*dr.:6q M/@3rb,;TV&+w%2:$|rieSZ1F5>a:+O[A6&/{n(q' );
define( 'LOGGED_IN_SALT',   'n5!i{<3>7-1#N~K/3l9D@[SGykaiRYtKL*YU[d|M@bQpxxM,*cW6?[C,~>FBT!aL' );
define( 'NONCE_SALT',       '26J0S!7Za1/U_c;ki90K>}Sp(a1=NBojy1}r+k2|RFq6fKjI*BI[ bEW=yesS4rA' );

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
