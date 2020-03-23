<?php

namespace weblink;

class TokenGenerator
{

    /* Curl example
     *   curl --header "Content-Type: application/xml; charset=utf-8" -v -d "<tokenForm><tokenType>CC</tokenType><value>4111111111111111</value></tokenForm>" "https://sensei.homeaway.com/tokens?time=1332537312345&digest=
     *   a4ac5a448dda50bd0ae0ff6ffcbe0487407e7e40c448c398a4a51c775a012345&clientId=7ad6a654-fd51-492d-90c2-b3237e169cb2"
     */

    const HOST = 'https://sensei.homeaway.com/';

    protected $errors = array();

    /*
     * The response, stored as JSON
     */
    protected $response;

    /*
     * The raw response, which is an XML string
     */
    protected $raw_response;

    /*
     * If the response was actually well formed / valid
     */
    protected $validResponse = false;

    /*
     * To debug or not
     */
    protected $debug = false;

    /*
     * @param string $cc_number The credit card number
     */
    public function __construct() {}

    /*
     * Public method to generate a token for a given cc number
     * @param string $cc_number
     * @return mixed
     */
    public function generateToken($cc_number) {

        return $this->_generateToken($cc_number);
    }

    /*
     * Private method to generate a token for a given cc number
     * @param string $cc_number
     * @return mixed
     */
    private function _generateToken($cc_number) {

        $response = $this->post( self::HOST, $cc_number );

        if ( !$response['success'] ) {
            $this->addError('Failed to create the CC token.');
            return false;
        }

        $this->parseResponse($response['result']);

        if ( $this->validResponse ) {
            $token_id = $this->getTokenId();
            return $token_id;
        }

        return false;

    }

    /*
     * Get the token id
     * @return string
     */
    private function getTokenId() {
        return $this->response['@attributes']['id'];
    }

    /*
     * Validate the response
     * @param string $xml
     * @return void
     */
    private function parseResponse($xml) {
        $this->raw_response = $xml;

        $xml = @simplexml_load_string($xml);

        if ( !$xml ) {
            $this->addError('Failed to parse the xml payload');
            return false;
        }

        $json = json_encode($xml, JSON_PRETTY_PRINT);

        if ( $json ) {

            $array = json_decode($json, true);

            if ( isset($array['@attributes']) && isset($array['@attributes']['id']) ) {

                $this->response = $array;

                $this->validResponse = true;

            } else {

                $this->addError('Not a valid response');

                if ( $array['body']['p'] ) {
                    $this->addError(trim($array['body']['p']));
                }
            }

            return true;
        }

        $this->addError('Failed to convert to JSON');

        return false;

    }

    /*
     * Call curl and make a GET request
     * @param string $url
     * @param string $cc_number
     * @param array $params
     * @param int $timeout
     * @param bool $ssl
     * @return array
     */
    private function post( $url, $cc_number, $params=array(), $timeout = 20, $ssl=true )
    {
        if ( !$timeout ) {
            $timeout = 30;
        }
        
        $param_string =http_build_query($params);

        $utc_time = $this->getUTCMilliseconds();

        $api_key = HAT_APIKEY;
        $client_id = HAT_CLIENTID;

        #$api_key = 'c5de60cfccd04f84a502bfb9c63d2f28';
        #$utc_time = '1301602208909';
        #$client_id = 'a7209c11-32e5-4c55-bff5-617607d85e8f';

        $digest = $this->createDigest($api_key, $utc_time);

        $post_url = $url . 'tokens?time=' . $utc_time . '&digest=' . $digest . '&clientId=' . HAT_CLIENTID;

        $data_json = json_encode(array(
            'tokenType' => 'CC',
            'value' => $cc_number,
        ));

        if ( $this->debug ) {
            Util::line($post_url);
            Util::line($data_json);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $post_url);
        curl_setopt($curl, CURLOPT_POST, 1 );
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        #curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout ); 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $ssl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ssl);

        $result = curl_exec ($curl);

        /* Sample success response

<?xml version="1.0" encoding="UTF-8" standalone="yes"?><token id="0d7c6a17-e2b4-43a3-a42c-835137ff53ab"><createDateUtc>2016-11-09T20:57:55.688Z</createDateUtc><digest>A0BtyWq3D5HudCm4CfmDGmWRZ8oDk87nWCkiVuzbWoE=</digest><hapiUser id="b8c5fb5c-c08e-4898-a4ea-a48d74013e11" href="/users/b8c5fb5c-c08e-4898-a4ea-a48d74013e11"/><maskedValue>************1111</maskedValue><tokenType>ACCTNUM</tokenType></token>
        */

        $info = curl_getinfo($curl);


        $error_number= curl_errno($curl);
        $error = curl_error($curl);

        curl_close($curl);

        // Only return true if service replies with 200 OK
        if ( !$error_number ) {
            return array(
                'success' => 1,
                'result' => $result,
                'info' => $info,
            );
        }

        return array(
            'success' => 0,
            'error' => $error,
        );

    }

    /*
     * Get the utc time in milliseconds
     * @return int
     */
    private function getUTCMilliseconds() {
        $dt = new \DateTime(null, new \DateTimeZone('UTC'));

        # to debug with since this is not valid and too short: $utc_time = $dt->format('U');
        $utc_time= ($dt->format('U') * 1000) + ($dt->format('u') / 1000);


        return $utc_time;
    }


    /*
     * This is generated by taking the time, appending on the apiKey, then hashing the string using sha-256 (preferred) or md5. If you use md5, you must specify the alg=md5 queryParam as well.  
     * You can test with this
     * The outcome should be 85f9a6b9a73db7eadd3ac99ac49f323dfc36e276b0f78fe7ff068c031593dec0
        $api_key = 'c5de60cfccd04f84a502bfb9c63d2f28';
        $utc_time = '1301602208909';
     */
    private function createDigest( $api_key, $utc_time ) {
        $digest = hash('sha256', $utc_time . $api_key);
        return $digest;
    }


    private function addError($error) {
        $this->errors[] = $error;
    }

    public function getErrors() {
        return $this->errors;
    }

}
