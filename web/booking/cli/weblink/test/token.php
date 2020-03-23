<?php

/*
 *
 * This cli is to test token generation with an input of a CC #
 */

    $root = dirname(dirname(dirname(dirname(__FILE__))));

    require_once('esite/config/conf.php');
    require_once( $root . '/conf/conf.php');
    require_once( $root . '/conf/v12_load.php');

    use weblink\TokenGenerator;

    $token_generator = new TokenGenerator;

    $cc = '4111111111111111';

    $token = $token_generator->generateToken($cc);

    if ( $token ) {
        echo 'token is ' . $token;
    } else  {
        $errors = $token_generator->getErrors();
        var_dump($errors);
    }
