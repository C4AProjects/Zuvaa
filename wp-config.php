<?php
# Database Configuration
define( 'DB_NAME', 'zuvaa' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'bigas' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_kw52afcvbw_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         '#x][!?]0Y@IlO+-CyIu&%_`vS|[//u+4g?l?eIU1_^|1]&BX9!GrP>V1{-JF:Dw5');
define('SECURE_AUTH_KEY',  'fhw |/nQ0D%]F#a|ofgq#8-4P8HM/]D[?}#>)^Fe?I?7-+ag+uB321n?{e/L$Dl-');
define('LOGGED_IN_KEY',    '4TmsEQ9@V[/ T/;33!fOfS %3!fLqG0m5S)x^`$.Pi@*j=(m#gdvF[^C_kQmQ+j+');
define('NONCE_KEY',        'gm;yfCqa])MNw%x1|{>@0`L0b/zh(Il1sQ+oZX=2Md6Ihk9Zz(OS)eioc^(K0xj4');
define('AUTH_SALT',        '>V2gw7*>n6D)Dum*^e!4UYLY}VY3l+br7z1?!W<7!$D,6g5[$;>.K+:p$SAJT<F<');
define('SECURE_AUTH_SALT', 'm{,O-~pZ%TxR*|?iux|;%kFRE8~ox_F#pfS!vAjjTdu~KE}Y0IN]0Db-dp@5,-4l');
define('LOGGED_IN_SALT',   '*+<?1drP{0iJ-uRwXG?^h^6@hYv?Ua{:u@4OmKUB[?Q9Im_z#ZI&u-=hg_1k%++#');
define('NONCE_SALT',       '-F9)wA:B`2dhu3f!U$v%k0S4w%50hCx3|<1<_Xxjb`USblhqQTq8~P=V-[W{_9{*');
/**#@-*/
define('WP_HOME','http://zuvaa.dev');
define('WP_SITEURL','http://zuvaa.dev');


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

$_wpe_preamble_path = null; if(false){}
