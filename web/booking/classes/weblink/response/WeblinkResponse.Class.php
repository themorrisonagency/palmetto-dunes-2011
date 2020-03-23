<?php

namespace weblink;

/*
 * Generic class for weblink responses
 * Useful for consistent error catching
 */

class WeblinkResponse
{
        protected $emptyresult = false;
	
        /*
         * @param $data the data array
         * @param $resultname The result set nodename, different per each method
         */
	function __construct($data, $resultname='')
	{

            /* 
             * Result set can be empty like such

                object(stdClass)#5 (1) {
                  ["getChangeLogInfoResult"]=>
                  object(stdClass)#6 (0) {
                  }
                }

             */


            if ( !is_object($data) ) {
                throw new \Exception('Unable to parse data', 20002);
            }

            if ( \Util::isEmptyObject($data->{$resultname}) ) {
                # Dont really throw an exception
                # throw new \Exception('Successful response/call but empty result', ERRORCODE_EMPTYRESULT);
                $this->emptyresult = true;
            } else {
                $this->data = json_decode(json_encode($data->{$resultname}), true);
            }
	}

        /*
         * Check if the result is empty
         * @return bool
         */
        public function isEmptyResult() {
            return $this->emptyresult;
        }
}
