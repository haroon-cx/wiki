<?php

/** WP 2FA plugin data encryption key. For more information please visit melapress.com */
define( 'WP2FA_ENCRYPT_KEY', 'lODL0OFCM5de3kaFDzCLew==' );

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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
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
define( 'AUTH_KEY',          'sX|jPl;Xj1RnGMTGl!nQReQLcDC:NJ}S&*oy^1Oit )TFL}/5e5f2dMFCwcQ_H80' );
define( 'SECURE_AUTH_KEY',   ')^(R$?Cq5c#YI34]l)rXlhL!8N>jK)_D@$64,/v<fOn%W=+Y4w-Yv7DW%d7|ha3y' );
define( 'LOGGED_IN_KEY',     'le:7[L?Dp-|d sf]yFWSz1V_]Sbuz?Tt18;)^YvX1xx_5M&|aTT<e{:cV+vnyg~|' );
define( 'NONCE_KEY',         'dj<>-h89vWpa)TP%0ToC^A6fP>nZs,?S<$7jTO;SO8Zwog:,]/l4c<G|l;rm:ni=' );
define( 'AUTH_SALT',         'S>k!u*i$}$6D9Y$o4QU^y-J%TG$QtyiO}?WJ4Watq_4l+fhHnx& topM{x;<T`#;' );
define( 'SECURE_AUTH_SALT',  '7kSan5uBn}Y{D8S$h5&mI9#z~&B;~<k,^pru*W-K4W<.!lUO./BMVe|t T@E8^J`' );
define( 'LOGGED_IN_SALT',    'AI*Z$c}Y07$.kA/&-y9Nd%NDIiw3Xai!113WJT?Sdj^T)R&W4CV2Y_<Rux&yRqR9' );
define( 'NONCE_SALT',        '5eg~Bee[k|B}:tD>F}PlRld3xH(G)J&xVpIg$1*7$ER&2q^T|dm_9$` VJ!mvs9)' );
define( 'WP_CACHE_KEY_SALT', 'l;^n_n-d9(G65nvh%IeZmlcq~_pfOVWj.;U>96$B<=8],YSzl_4zw6K:ziSh%z3x' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
