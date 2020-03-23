<?php

namespace weblink;

class ReservationChangeResponse extends WeblinkResponse
{
	protected $data=null;
        protected $property_id=null;
        protected $cancellation = false;

        const resultname = 'getReservationChangeLogResult';
        const resultinnername = 'clsResChangeLogInfo';

	public function __construct($result, $property_id)
	{
		parent::__construct($result, self::resultname);
                $this->property_id = \Property::getInternalPropertyId($property_id);

                if ( !$this->isEmptyResult() ) {
                    $this->data = $this->normalize($this->data);
                }

                $this->db = new \Db;
	}

        /*
         * Normalize the data structure as results with a single result differ from multiple

         *
         * @return array
         */
        private function normalize($structure) {
            $data = array();

            if ( isset( $structure[self::resultinnername]['strChangeLog'] ) ) {
                $data[] = $structure[self::resultinnername];
            } else {
                foreach ($structure[self::resultinnername] as $changelog) {
                    $data[] = $changelog;
                }
            }

            return $data;
        }

        /*
         * Get a list of properties that have changed
         * @param string $type The type
         * @return array
         */

        public function getupdatedproperties($type) {

            // Dont filter for now
            $type = '';

            // Filter by type
            if ( $type != '' ) {
                foreach ( $this->data as $k => $property ) {
                    if ( $property['strChangeLog'] !== $type ) {
                        unset($this->data[$k]);
                    }
                }
            }

            return $this->data;
        }

        /*
         * Get the raw reservations
         * @return array
         */
        public function getreservationsraw() {
            return $this->data;
        }


        /*
         * Get a list of dates and their availability
         * @return mixed
         */
        public function getdates() {

            $dates = array();

            // This can be multiple reservations per property
            foreach ($this->data as $changelog) {

                $status_flag = strtolower($changelog['strStatusFlag']);

                $rows = $this->createDateRows($changelog, $status_flag);

                foreach ($rows as $row ) {
                    $dates[] = $row;
                }

            }

            return $dates;
        }

        /*
         * Create a date row for a changelog
         * @param array $changelog
         * @return array
         */
        private function createDateRows($changelog, $statusflag) {
            $dates = array();
            $startDate = new \DateTime($changelog['dtStartdate']);
            $endDate = new \DateTime($changelog['dtEndDate']);

            $periodInterval = new \DateInterval('P1D');
            $period = new \DatePeriod( $startDate, $periodInterval, $endDate );

            foreach ($period as $date ) {
                $dates[] = array(
                    'date' => $date->format('Y-m-d'),

                    // X means cancel so this becomes available again
                    'available' => $statusflag == 'x' ? 1 : 0,
                    //'staytype' => $changelog['strStayType'],
                    'property_id' => $this->property_id,

                    // Confirmation number
                    'quotenum' => $changelog['intQuoteNum'],
                );
            }

            return $dates;
        }

        /*
         * See if we need to cancel this
         * @return void
         */
        public function isCancellation() {
            return $this->cancellation;
        }
	
}
