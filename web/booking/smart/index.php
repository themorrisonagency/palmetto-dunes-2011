<?php
require_once('../conf/conf.php');
//error_reporting(E_ALL^E_NOTICE);
new caliban(__FILE__,Array('prePath'=>$_GET['page'],'deviceHandling'=>1));
?>