<?php
//error_reporting(E_ALL^E_NOTICE);
ini_set('default_socket_timeout', 600); //	Database Settings 
//define('DB_HOST','localhost');
define('DB_HOST','dbreadwrite.esitedb.com');
define("DB_USER","palmetto");
define("DB_PASS","uAt44sfJXQQJ:P3r");
define("DB_NAME","palmettodunes");
	
define('DB_AUTOSLASH',true);
define('DB_SHOWERRORS', false);

//define('DHR', false);								//	Set to true for DHR sites

//	Local Settings
define('USE_PROXY', false);							//	Network Proxy On?
//define('PROXY_HOST', 'www-ad-proxy.sabre.com');	//	Ethernet Settings
//define('PROXY_PORT', 80);
//define('PROXY_HOST', '127.0.0.1');				//	VPN Settings
//define('PROXY_PORT', 8080);
//define('PROXY_LOGIN', 'loginName');				//	Domain Username
//define('PROXY_PASSWORD', 'loginPassword');		//	Domain Password

define('APP_DEBUG', true);							//	Debug Items - this can stay on

define("RPC_HOST",        "context.esitemarketing.com"); 
define("RPC_PORT",        80); 
define("RPC_NSPC",        "/server/rpc/index.php");

define("APP_REL","/booking/");
//define("APP_WEB","http://" . $_SERVER["SERVER_NAME"].($_SERVER["SERVER_PORT"]!=80 ?":".$_SERVER["SERVER_PORT"] : null).'/'. APP_REL ); 
define("APP_PATH",$_SERVER['DOCUMENT_ROOT'].'/' . APP_REL); 
define("APP_INCLUDES","cdc/");  
define("APP_CLASSES",APP_INCLUDES ."classes/");
define("APP_ERROR_LOG", APP_PATH.'../logs/errors/');
define('BOOKING_URL', 'https://www.palmettodunes.com/booking');
define('PROPERTY_IMAGE_URL', 'https://www.palmettodunes.com/booking/assets/images');

define('CDE_PATH', 'cde/tags/v1.8.7');

define('RATEPLAN_ID_DAILY', 1);
define('RATEPLAN_ID_WEEKLY', 2);
//define('CDE_DEBUGXSL', true);

// define('CDE_PATH', 'cde/trunk');


// MOBILE SNIFFER
// uncomment lines below to enable mobile redirects to non-CDE mobile site
/*
define('SNIFFER_REDIRECTS', TRUE);
define('SNIFFER_REDIRECT_URL', 'http://raynor.mobilehotelier.com');
*/

define('replaceAmpersands', false);

define('WEBLINK_USERID', 'P1547');
define('WEBLINK_PASSWORD', 'Cicy#V^ZGv6:R;RuvdF#');
define('WEBLINK_COMPANYID', '2369');

define('SYNCID_AVAIL_INITIAL', 1); // No longer in use

define('SYNCID_AVAIL_PARTIAL', 3);
define('SYNCID_RATE_INITIAL', 5);
define('SYNCID_RATE_PARTIAL', 7);
define('SYNCID_PROPERTY_INITIAL', 9);
define('SYNCID_PROPERTY_PARTIAL', 11);
define('SYNCID_FULL', 13);
define('SYNCID_PROPERTYRATES', 15);

define('DIR', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

/*
 * These are the HAT credentials used in the tokenization of the credit card per the ISILink spec
 */

define('HAT_CLIENTID', 'ae80bcdd-2844-47c3-bbf4-5a88c07e6b92');
define('HAT_APIKEY', 'e7a7a722730f4a10b5845cbd6402bbab');

#define('LOG_DIR', '/www/work/palmettodunes_booking/logs/application/');
define('LOG_DIR', '/var/www/html/palmettodunes_booking/logs/application/');
define('ENVIRONMENT', 'development');

define('ERRORCODE_EMPTYRESULT', 20001);

// For the is_v12 flag in the bookings table
define('USE_V12', true);

// Not doing this anymore
define('ALLOW_OVERMONTHSTAY', false);

// For availability requests
define('DEFAULT_ADULTS', 2);
define('DEFAULT_KIDS', 0);

// This needs to be true when we go live
define('ENABLE_CCTOKENS', true);

// The default socket timeout value. This is used on weblink requests to reset the socket since Amaxus resets it
define('SOCKET_TIMEOUT', 600);

define('CLI_RUN', php_sapi_name() === 'cli');

define('MACHINE_ROOT', '/var/www/html/palmettodunes_booking/trunk/');

#define('MACHINE_ROOT', '/www/work/palmettodunes_booking/branches/subdomain-test/');

define('PROPERTY_PATH', MACHINE_ROOT.'assets/images/properties/');
define('UPLOAD_PATH',   MACHINE_ROOT.'assets/images/properties/');
#define('IMAGE_URL',     WEB_ROOT.'assets/images/properties/');

define('CARPASS_PRICE_EARLY', 18);
define('CARPASS_PRICE_REGULAR', 20);

define('CDN_SUBDIR', 'pdbookingv12');

define('WEEKLY_NTHDAY', 6);

define('GOOGLE_RECAPTCHA_PUBLIC_KEY', '6LfZknUUAAAAAHz44QIJrdKbMCxDIyv68h5Hh_1F');
define('GOOGLE_RECAPTCHA_SECRET_KEY', '6LfZknUUAAAAAORbpunN_WWf2D2QFV4OuoBZfAuc');
