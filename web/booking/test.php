<?php

function getSSLPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT,7);
    curl_setopt($ch, CURLOPT_URL, $url);

    $headers = array();
    $headers[] = 'X-Apple-Tz: 0';
    $headers[] = 'X-Apple-Store-Front: 143444,12';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
    $headers[] = 'Accept-Encoding: gzip, deflate';
    $headers[] = 'Accept-Language: en-US,en;q=0.5';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
    $headers[] = 'Host: www.example.com';
    $headers[] = 'Referer: http://www.example.com/index.php'; //Your referrer address
    $headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';
    $headers[] = 'X-MicrosoftAjax: Delta=true';

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, true);

    curl_setopt($ch, CURLOPT_SSLVERSION,3); 
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
); 

ini_set('default_socket_timeout', 10);
$url = 'https://www.palmettodunes.com/cde-header';
$htmlstring=getSSLPage($url);
#$htmlstring=file_get_contents($url, false, stream_context_create( $arrContextOptions));
#$htmlstring=file_get_contents($url);

/*
$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,$url);
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
#curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
$htmlstring = curl_exec($curl_handle);
curl_close($curl_handle);
 */
var_dump($htmlstring);
