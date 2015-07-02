<?php
# Database Configuration
define( 'DB_NAME', 'wp_zuvaa' );
define( 'DB_USER', 'zuvaa' );
define( 'DB_PASSWORD', 'ZoTDONVpL3Smbfa0IAO2' );
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


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'zuvaa' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', 'edaed3f0a4481dc3a550b933b5ea3eed79295255' );

define( 'WPE_FOOTER_HTML', "" );

define( 'WPE_CLUSTER_ID', '40102' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '45.56.119.23' );

define( 'WPE_CDN_DISABLE_ALLOWED', false );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', true );

define( 'FORCE_SSL_LOGIN', true );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'zuvaa.com', 1 => 'www.zuvaa.com', 2 => 'zuvaa.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-40102', );

$wpe_special_ips=array ( 0 => '45.56.119.23', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings





# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
