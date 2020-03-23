<?php
if (extension_loaded('newrelic')) {
    // new relic naming for pages
    newrelic_name_transaction($_SERVER['PATH_INFO']);
}
require('concrete/dispatcher.php');
