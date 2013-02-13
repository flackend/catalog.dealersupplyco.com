<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Ethix Systems Application Constant Definition
|--------------------------------------------------------------------------
|
| These constants are used by various controllers, views, and models at the
| application level.  The apache server configuration will determine which
| set of application constants is needed for that specific instance of the
| application.
|
*/

define(APP_IDENT."_GOOGLE_ANALYTICS_KEY", "");

switch(@$_SERVER['APP_ENV']) {
	case 'dev':
	case 'staging':
		
		// base web host information
		define(APP_IDENT."_COOKIE_DOMAIN", "ethixsystems.com");
		define(APP_IDENT."_APPLICATION_PROTOCOL", "http");
		
		// development database information
		if($_SERVER['APP_ENV'] == 'staging')
			define(APP_IDENT."_DB_SERVER", "10.25.1.1");
		else
			define(APP_IDENT."_DB_SERVER", "dbprod.ethixsystems.com");
		define(APP_IDENT."_SES_DB", "DB_DealersSupplySES");
		define(APP_IDENT."_SES_DB_USER", "ses_ds");
		define(APP_IDENT."_SES_DB_PASS", "vw457W54W7w3NB*%W#B&aQ74QAbv3A&24B7Q#AVA#3A*B*%B8585Eb85EAAB85*b5b8A3V872632Aawe4");
		define(APP_IDENT."_APP_DB", "DB_DealersSupply");
		define(APP_IDENT."_APP_DB_S_USER", "s_ds");
		define(APP_IDENT."_APP_DB_S_PASS", "bbs*%444%&W2365aVWeDS6D&R%B&DT8Ty(6n4&@Q##Q2b#S46R9TrDcFydves457bh%(^TCgf8%*W45");
		define(APP_IDENT."_APP_DB_U_USER", "u_ds");
		define(APP_IDENT."_APP_DB_U_PASS", "ba5499x6nrx698r(7rtnrts7n*(NR(6n7dOiuLKG]y8dIxsRDyxz^WWs4v*&6tdx(6^&86rw&e75varyf");
		
		// mssql inventory database information
		if($_SERVER['APP_ENV'] == 'staging')
			define(APP_IDENT."_INVENTORY_DB_SERVER", "10.25.1.6");
		else
			define(APP_IDENT."_INVENTORY_DB_SERVER", "ethix-w2k8.ethixsystems.com");
		define(APP_IDENT."_INVENTORY_DB", "DB_DealersSupply");
		define(APP_IDENT."_INVENTORY_DB_USER", "s_ds");
		define(APP_IDENT."_INVENTORY_DB_PASS", "s_ds");
		
		// automated mailing parameters
		define(APP_IDENT."_EMAIL_SENDER", "p0060devel@ethixsystems.com");
		define(APP_IDENT."_EMAIL_REPLY_TO", "webmaster@ethixsystems.com");
		
		// additional services
		define(APP_IDENT."_ENABLE_GOOGLE_ANALYTICS", 0);
		
		// additional configuration
		define(APP_IDENT."_MAXIMUM_IMAGE_UPLOAD_SIZE", 2097152); // application limitation only; upload_max_filesize, memory_limit, and post_max_size will still need to be configured in php.ini correctly to support up to the amount indicated here
		define(APP_IDENT."_PASSWORD_SALT", '5dn963a4%Euc9ytnffD54b%6s#we7vb^D*NIfuyDNBs23');
		
		// debugging / testing
		define(APP_IDENT."_DEBUG", true);
		
		break;
	case 'prod':
		
		// base web host information
		define(APP_IDENT."_COOKIE_DOMAIN", "dealerssupplyco.com");
		define(APP_IDENT."_APPLICATION_PROTOCOL", "http");
		
		// production database information
		define(APP_IDENT."_DB_SERVER", "localhost");
		define(APP_IDENT."_SES_DB", "DB_SalesRepAppSES");
		define(APP_IDENT."_SES_DB_USER", "ses_srapp");
		define(APP_IDENT."_SES_DB_PASS", "wbh586w34bq23b98579n7IYUFDEv5wq34B(*D%7(N87f096*,)MRm678[]46p7w\#P&%{BR8m67P}B&w43623V67d5^(Jf^&mMn45ber");
		define(APP_IDENT."_APP_DB", "DB_SalesRepApp");
		define(APP_IDENT."_APP_DB_S_USER", "s_srapp");
		define(APP_IDENT."_APP_DB_S_PASS", "sd469m#W4sjE%*ID^[xp|^Q}#AWOV{E*n7qV!5g2#vs475RD*N^&8%Br7E5m,)(r^&DVV#WS^RT*IDK57s4e5n*e45Dbee7R&e");
		define(APP_IDENT."_APP_DB_U_USER", "u_srapp");
		define(APP_IDENT."_APP_DB_U_PASS", "RO^&uD%b^Y%WV#5q23%Qw4he6s36S&^hb%*%E^V^3w@352q4^G%86ge7845^Cw3#FESR&^%DS87re658WB#4Wa@Qc^%Swvg457wve^AW#.");
		
		// mssql inventory database information
		define(APP_IDENT."_INVENTORY_DB_SERVER", "mssqlsecureconnect.dealerssupplyco.com");
		define(APP_IDENT."_INVENTORY_DB", "DealersApp");
		define(APP_IDENT."_INVENTORY_DB_USER", "srapp");
		define(APP_IDENT."_INVENTORY_DB_PASS", "*jH5<}3!sQAmn0(H^`Kfjh%bcP+zD4");
		
		// automated mailing parameters
		define(APP_IDENT."_EMAIL_SENDER", "no-reply@");
		define(APP_IDENT."_EMAIL_REPLY_TO", "");
		
		// additional services
		define(APP_IDENT."_ENABLE_GOOGLE_ANALYTICS", 1);
		
		// additional configuration
		define(APP_IDENT."_MAXIMUM_IMAGE_UPLOAD_SIZE", 2097152); // application limitation only; upload_max_filesize, memory_limit, and post_max_size will still need to be configured in php.ini correctly to support up to the amount indicated here
		define(APP_IDENT."_PASSWORD_SALT", 'sv4r68SB#65qw34NHJ(DRDIF&n5t5eW3sb790t]d236w6/.');
		
		// debugging / testing
		define(APP_IDENT."_DEBUG", true);
		
		break;
	default:
		die('You must have the APP_ENV variable defined in apache for this application to load!');
}

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./system/application/config/constants.php */
