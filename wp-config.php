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
define('DB_NAME', 'restro_mobcamp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '0510#mysql');

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
define('AUTH_KEY',         '|swe$/GiZ`$xRE6,+ql|Pm@kc}W 8+b%BZC^G0tGkpqHcKy&&~xRL~0u>r1-NQ:u');
define('SECURE_AUTH_KEY',  'J^OpsaagJXGy=-W3$!qg 1c%I WlkgJ1C:(ncw;#nrM>mKiYHJ:Z2i%YX(lf|Fe(');
define('LOGGED_IN_KEY',    'lGTdU&R%Hm8iK;3/T;#+G*H#6)V?rhm!1N8l.mx%E@+7klu+Q}o^|Q^WDmMtX<Fc');
define('NONCE_KEY',        'N>@)?KSX^-&b`D&mmN[^3p|-!4rcMt|f(#k$Mr7-_=h+{iWNm0TwGY@Uwm#[L}OH');
define('AUTH_SALT',        'Uw3vh+(XA`T@:&.l{h<)22>.dy34k+K5?Lgi;)<?URV[e$V~ib/$X$AE4S,k+qvk');
define('SECURE_AUTH_SALT', 'SkZ]?;EXIb*~ebROp_:{iFlV|0^D(]HLSy$iOT#r{rRiY!^8|x~(+<w41@+6ZkLH');
define('LOGGED_IN_SALT',   '|3aLHu}b 95%~889,O1Tuv98bgKEd+ }0}=t]Mt=Z@ -|qi(8 d<MJ-;5RU ki((');
define('NONCE_SALT',       'Ro-7 `K5z]qbgLX|b*-q)D?p4iZ${KQ/sbaW#MisA-g^6wU~&a4~M9iD4e!D#oE-');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'mb_';

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
