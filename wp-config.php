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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'wordpressuser' );

/** MySQL database password */
define( 'DB_PASSWORD', '2021@!FunDev@!' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define('AUTH_KEY',         'y-LD{[pLPU<-P!ODOCHT`HB$a8u`^v&Q&jq6/@xvRihw0?`ZI+W1,[8-_2`<UT7G');
define('SECURE_AUTH_KEY',  'l}--u.&QE&.io9,t3D3XuXhitZ[x:jyK1PKMbA|tRqQ80ax,|O<,`US-2_X%b+o4');
define('LOGGED_IN_KEY',    'r+$W@gzXXW;R5>bW3ewM<+G_#^y5I`4icHGe.@|f|`%i2@5gC.Dh/,&?+T>+&{i^');
define('NONCE_KEY',        '5N`]BdVOx|?#gCT09MD+q.q:DRYt16d>WK2-_`mk+7^;%2)]U;<(}It!%C%us6+k');
define('AUTH_SALT',        'x]{vVvV@@Ek1wFC--7}ei&2}zOJ#y7E(kwf<n-D>1T3H;]kU*(m7O@5,QujH)s)G');
define('SECURE_AUTH_SALT', '[#8X`|T&-:W^8+zxI+dZ+{Su,|upQ5Y)YqSqvY<IH fqI1v3s@M2xPaH:Kf]dn~+');
define('LOGGED_IN_SALT',   '-kb4A~5G5Ns16#{}{@eNHn(^~(s_+5{TYD9Y*gxu{B;!I|5Vj,|BFa[7>20iF<]9');
define('NONCE_SALT',       '2].m^d;szYPH+K<Yv*zIV!| )5jsnH2nxCSNSs<1eNuR6=Z|xK*$yMWl_ex40&hG');

/**#@-*/

define('FS_METHOD', 'direct');

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
