<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'colgan');

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

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'f)<u-q79YYDw?+M&Y!36.Ch-M-^Sl!X^+T|s|4ol8>(-8C+muU+G;E=><J7y{z}4');
define('SECURE_AUTH_KEY',  '_Y0-^2EScGoZ{_rOo$bC.&Y]ZE2XI}uX*-rIO9,r`SB8@z3j5(R]xnvZ_iQ5,)%+');
define('LOGGED_IN_KEY',    'Gi;$,ZbVn<#-{J:UXHH]-i0+k-!QxowHj*J$(+^28n6D;L.K_x^H>to1en.f=2E|');
define('NONCE_KEY',        'D%-EBv,RMhlW=2Tve)(bAC0dY/SH_bC2VSO?7bhb>8vD[Gz~FK!VrYL]00UkR?!v');
define('AUTH_SALT',        'o_5-RN4oI(DZWeOz#/0qRw^bF( R+5^-yLgpj@$alP*8^?%g2^P=c^4ELny`=DnQ');
define('SECURE_AUTH_SALT', 'C-9lPp77QP9#IlW9r7yN$C=pHvHqocK>n=g$m}@v:u 6qIbUt9r[[bh#90dhE|7}');
define('LOGGED_IN_SALT',   'R-UW1_]8|]Es[7<1+ (0W/+L{^mZt4`KV3GA}s+OPbwe7yS~}W0z+j{u$R<3pJ#_');
define('NONCE_SALT',       '3s]2b}:<saf|?hpDvE~T-SOuF.B1nF*>d2F*&Y 0m7L5N@M`VA)b8hu (*rf -8w');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
