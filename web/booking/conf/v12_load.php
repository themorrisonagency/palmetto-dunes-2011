<?php

if ( !class_exists('GenericErrors') ) { require_once('esite/classes/GenericErrors.class.php'); }

if ( !class_exists('WebServiceCache') ) {
    require_once('esite/classes/WebServiceCache.class.php');
}

if ( !class_exists('Db') ) {
    require_once('esite/classes/Db.Class.php');
}

if ( !class_exists('GenericAdmin') ) {
    require_once( 'esite/classes/GenericAdmin.Class.php');
}

if ( !class_exists('Mailer') ) {
    require_once('esite/classes/Mailer.Class.php');
}

if ( !class_exists('Property') ) {
    require_once( DIR. '/classes/Property.Class.php');
}

if ( !class_exists('Rates') ) {
    require_once( DIR. '/classes/Rates.Class.php');
}

require_once( DIR. '/classes/weblink/Exceptions.Class.php');

# This is the base, include this first
require_once( DIR. '/classes/weblink/response/WeblinkResponse.Class.php');

require_once( DIR. '/classes/weblink/response/BookingResponse.Class.php');
require_once( DIR. '/classes/weblink/response/AvailabilityResponse.Class.php');
require_once( DIR. '/classes/weblink/response/ChangeLogResponse.Class.php');
require_once( DIR. '/classes/weblink/response/PropertyDescResponse.Class.php');
require_once( DIR. '/classes/weblink/response/ReservationQueryResponse.Class.php');
require_once( DIR. '/classes/weblink/response/ReservationChangeResponse.Class.php');
require_once( DIR. '/classes/weblink/response/PropertyIndexesResponse.Class.php');

require_once( DIR.'/classes/weblink/Import.Class.php');
require_once( DIR. '/classes/weblink/SoapWeblink.Class.php');
require_once( DIR. '/classes/weblink/TokenGenerator.Class.php');

require_once( DIR. '/classes/weblink/Pull.Class.php');
require_once( DIR. '/classes/Util.Class.php');
require_once( DIR. '/classes/weblink/Logger.Class.php');
require_once( DIR. '/classes/BedtypeList.Class.php');
require_once( DIR. '/classes/BedroomtypeList.Class.php');
require_once( DIR. '/classes/LocationList.Class.php');
require_once( DIR. '/classes/ViewList.Class.php');
require_once( DIR. '/classes/LocationareaList.Class.php');
require_once( DIR. '/classes/PropertytypeList.Class.php');
require_once( DIR. '/classes/AmenityList.Class.php');
