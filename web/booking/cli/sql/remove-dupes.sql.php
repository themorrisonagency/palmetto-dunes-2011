<?php

    $root = dirname(dirname(dirname(__FILE__)));

    require_once('esite/config/conf.php');
    require_once 'esite/classes/class.phpmailer.php';
    require_once('esite/classes/Mailer.Class.php');
    require_once( $root . '/conf/conf.php');
    require_once( $root . '/conf/v12_load.php');

    if ( !CLI_RUN ) exit;

    $db = new Db;

    // There are around 7 test properties. delete these.
    $sql = 'DELETE FROM properties WHERE ows_id = ""';

    $db->query($sql);

    $sql = 'ALTER IGNORE TABLE `properties` ADD UNIQUE(`ows_id`)';

    $db->query($sql);

    /*
    $sql = '
    CREATE TEMPORARY TABLE IF NOT EXISTS `duperows`
    SELECT 
    *,
    COUNT(*) AS c
    FROM properties p
    GROUP BY p.ows_id
    HAVING c> 1 AND p.is_active=0;

    DELETE FROM properties WHERE id IN ( SELECT id FROM duperows );

    DROP TEMPORARY TABLE `duperows`;
    ';
     */
