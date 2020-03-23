<?php

namespace weblink;

class Logger {

    /*
     * The dir to log in
     */
    protected $dir;

    /* 
     * The environment 
     */
    protected $environment;

    // Should we output this as well?
    // Options: none, screen
    protected $output = 'none';

    // Should we log? 
    protected $log = true;

    protected $logs = array();

    protected $useYear = true;
 
    // The log time
    protected $logTime;

    public function __construct($options=array()) {

        $this->_parseOptions($options);
        $dt = new \DateTime(null, new \DateTimeZone('America/New_York'));
        $this->logTime = $dt->format('Y-m-d H:i:s');

    }

    /*
     * Parse the options
     * @param array $options
     * @return void
     */
    private function _parseOptions($options) {

        if ( isset($options['dir']) ) {
            $this->dir = $options['dir'];
        }

        if ( isset($options['output'] ) ) {
            $this->output = $options['output'];
        }
    }

    /*
     * Log to the screen
     * @param string $message
     * @param string $command
     */
    public function logtoscreen($message, $command='') {
        $this->_log($message, $command, false, true);
    }

    /*
     * Log to the file
     * @param string $message
     * @param string $command
     */
    public function logtofile($message, $command='') {
        $this->_log($message, $command, true, false);
    }

    /*
     * Log the message
     * @param string $message
     * @return void
     */
    public function log($message, $command='') {
        $this->_log($message, $command, true, true);
    }

    /*
     * Log to screen or file
     * @param string $message
     * @return void
     */
    private function _log($message, $command, $writeToFile=true, $writeToScreen=true) {

        if ( $command == 'start' ) {
            $this->logs[] = $this->hr();
        }

        $this->logs[] = $message;

        if ( $writeToScreen ) {

            if ( $command == 'start' ) {
                $this->_writeToScreen($this->hr());
            }

            $this->_writeToScreen($message);

            if ( $command == 'end' ) {
                $this->_writeToScreen($this->hr());
            }
        }
        
        if ( $writeToFile ) {

            if ( $command == 'start' ) {
                $this->_writeToFile($this->hr());
            }

            $this->_writeToFile($message);

            if ( $command == 'end' ) {
                $this->_writeToFile($this->hr());
            }
        }

    }

    public function getLogs() {
        return $this->logs;
    }


    /*
     * Write this to the screen
     * @return void
     */
    private function _writeToScreen($message) {
        echo $message . PHP_EOL;
    }

    /*
     * Write this to a log file
     * @param string $message
     * @return void
     */
    private function _writeToFile($message) {

        $dt = new \DateTime(null, new \DateTimeZone('America/New_York'));
        $dir = $this->dir . '/';

        $wrote = false;

        $check = $this->_checkDir($dir);

        if ( $check ) {
            $date_string = $this->logTime;

            $filename = $dir . '/' . $date_string . '.log';
            $wrote = @file_put_contents( $filename , $message . PHP_EOL, FILE_APPEND | LOCK_EX);
        }

        return $wrote;
    }

    /*
     * Make sure the directory is writable
     * @param string $dir
     * @return void
     */
    private function _checkDir($dir) {
        if ( !is_dir($dir) || !is_writable($dir) ) {

            // Try to make it
            $mkdir = @mkdir( $dir, 0755, true );

            if ( $mkdir && is_dir($dir) && is_writable( $dir ) ) {
                return true;
            }

            return false;
        }

        return true;
    }

    /*
     * Log a horizontal rule
     * @return void
     */
    public function hr() {
        return ('--------------------------------------------------------------------');
    }

}
