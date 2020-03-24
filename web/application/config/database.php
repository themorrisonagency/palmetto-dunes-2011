<?php
/**
 * Staging Database Credentials
 */
return array(
    'default-connection' => 'concrete',
    'connections' => array(
        'concrete' => array(
            'driver' => 'c5_pdo_mysql',
            'server' => 'dbreadwrite.esitedb.com',
            // These values should be used for dev purposes. If you'd like to
            // use a different set of credentials, add a new environment to
            // /application/bootstrap/start.php
            'database' => 'c5_palmettodunes',
            'username' => 'palmetto',
            'password' => 'uAt44sfJXQQJ:P3r',
            'charset' => 'utf8'
        )
    )
);
