<?php

/*
 * Class to interface with availability/pricing pull times and differences
 * @author <meder.omuraliev@sabre.com>
 */

class Pull {

    protected $db;

    // The sync id
    protected $id;

    // Is the script running
    protected $isRunning = false;

    // The start time
    protected $starttime = null;

    const tablename = 'synctypes';
    const timezone = 'America/New_York';

    public function __construct($id) {
        $this->db = new \Db;

        if ( !$id || $id <0 ) {
            throw new Exception('Please specify a valid sync id');
        }

        $this->id = $id;

        //$this->start();  this can't be here.  you are updating the last pulled date before checking it

    }

    /*
     * Set the rate as finished
     * @return bool
     */
    public function isRunning() {

        return $this->isRunning;
    }

    /*
     * Set the rate as running
     * @param int $id
     * @return void
     */
    public function start() {

        $this->starttime = new \DateTime;

        $sql = 'UPDATE ' . self::tablename . ' SET is_running=1, last_pull_start = "' . $this->starttime->format('Y-m-d H:i:s') . '" WHERE id = ' . $this->id;
        $this->isRunning = true;
        $this->db->query($sql);

        return true;
    }

    /*
     * Set the rate as finished
     * @param bool $record_pull Should we record the pull?
     * @return void
     */
    public function end( $record_pull = true ) {
        $this->db->query('UPDATE ' . self::tablename . ' SET is_running=0 WHERE id = ' . $this->id );

        $endtime = new \DateTime;

        if ( $record_pull ) {
            $this->db->query('UPDATE ' . self::tablename . ' SET last_pull_end="' . $endtime->format('Y-m-d H:i:s') . '" WHERE id = ' . $this->id );
        }

        $this->isRunning = false;

        return true;

    }

    /*
     *
     * Get the number of minutes since a pull
     * @return mixed
     */
    public function getLastPull() {
        $minutes_since = null;

        $sql='SELECT last_pull_start FROM ' . self::tablename . ' WHERE id = ' . $this->id;

        $this->db->query($sql);

        if ( $temp = $this->db->fetchAssoc() ) {

            $now = strtotime('now');
            $then = strtotime($temp['last_pull_start']);

            // Never pulled
            if ( $then === false ) {
                return $minutes_since;
            }

            $interval = abs($now-$then);
            $minutes_since = round($interval/60);

        }
        return $minutes_since;
    }

}
