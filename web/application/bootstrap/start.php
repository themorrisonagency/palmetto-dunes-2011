<?php

use Concrete\Core\Application\Application;
use Concrete\Core\User\User;

/**
 * ----------------------------------------------------------------------------
 * Instantiate concrete5
 * ----------------------------------------------------------------------------
 */
$app = new Application();

/**
 * ----------------------------------------------------------------------------
 * Detect the environment based on the hostname of the server
 * ----------------------------------------------------------------------------
 */
/** @todo Define the live hostname */
$env = $app->detectEnvironment(
    array(
        'live'   => array(
            // Put the live server's hostname here, this will allow c5 to pick
            // the correct database config.
            'www.palmettodunes.com'
        ),
        'andrew' => array(
            'Andrews-MacBook-Pro.local'
        ),
        'korvin' => array(
            'chuck-testa',
            'chuck-testa.local'
        ),
        'corey'  => array(
            'JAMB1C0F1G4',
            'JAMB1C0F1G4.local'
        ),
        'yvonne' => array(
            'C02PW1M8G8WL',
            'C02PW1M8G8WL.local'
        ),
        'ali'  => array(
            'MAC-AliK.local'
        ),
        'dallas' => array(
            'Dallass-MacBook-Pro.local'
        ),
        'jbrooks'  => array(
            'C02T70DBGTDY',
            'C02T70DBGTDY.local'
        ),
        /**
         * Sample Environment
         * This environment is defined as "sample", and is used with hostnames
         * matching either "computer-1" or "server-a". This will override any
         * configurations in "database.php" with the configurations in
         * "sample.database.php" and so with all config groups.
         */
        'sample' => array(
            'computer-1',
            'server-a'
        ),
		'matt' => array(
			'DDB4K3G2'
        ),
        'brandon' => array(
            'C02T70DLGTDY',
            'C02T70DLGTDY.local',
        ),
    ));

return $app;
