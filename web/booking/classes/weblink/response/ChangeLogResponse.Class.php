<?php

namespace weblink;

class ChangeLogResponse extends WeblinkResponse
{
	protected $data=null;

        const resultname = 'getChangeLogInfoResult';

	public function __construct($result)
	{
		parent::__construct($result, self::resultname);
                $this->data = $this->normalize($this->data);
                $this->db = new \Db;
	}

        /*
         * Normalize the data structure
         * @return array
         */
        private function normalize($structure) {
            $data = array();

            // For single results, normalize so we parse it properly
            if ( isset( $structure['anyType']['enc_value']) && $structure['anyType']['enc_value'] != '' ) {
                $reference = $structure['anyType'];
                $structure['anyType'] = array();
                $structure['anyType'][] = $reference;
            }

            if ( $structure['anyType'] && count ( $structure['anyType'] ) > 0 ) {
                foreach ( $structure['anyType'] as $property ) {
                    $data[] = $property['enc_value'];
                }
            }

            return $data;
        }

        /*
         * Get a list of updates (can have multiple updates from the same property) that have changed
         * @return array
         */

        public function getupdates($type) {

            // Dont filter for now
            $type = strtoupper($type);

            // Filter by type
            if ( $type != '' ) {
                foreach ( $this->data as $k => $property ) {
                    if ( strtoupper($property['strChangeLog']) !== $type ) {
                        unset($this->data[$k]);
                    }
                }
            }

            return $this->data;
        }

        /*
         * Get a list of non available dates
         * @return mixed
         */
        public function getnonavailabledates() {

            $data = array();
            $dates = array();

            if ( empty ( $this->data ) ) {
                return false;
            }

            $node = $this->data;

            $non_available = $node['clsNonAvailDates'];

            // If empty
            if ( empty ( $non_available ) ) {
                return false;
            }

            foreach ($non_available as $dateObj ) {
                $startDate = new \DateTime($dateObj['dtFromDate']);
                $endDate = new \DateTime($dateObj['dtToDate']);

                // Don't do this
                // $endDate->add(new DateInterval('P1D'));

                $periodInterval = new \DateInterval('P1D');
                $period = new \DatePeriod( $startDate, $periodInterval, $endDate );

                foreach ($period as $date ) {
                    $dates[] = array(
                        'date' => $date->format('Y-m-d'),
                        'available' => 0,
                        'staytype' => $dateObj['strStayType'],
                        'property_id' => $this->property_id,

                        // Confirmation number
                        'quotenum' => $dateObj['intQuoteNum'],
                    );
                }

            }

            return $dates;
        }
}
