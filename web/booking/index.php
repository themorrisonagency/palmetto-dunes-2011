<?php
require_once('esite/config/conf.php');
require_once('conf/conf.php');
require_once(CDE_PATH.'/caliban.php');				//	Require machine path to CDE - may need to change locally
error_reporting(E_ALL);
error_reporting(1);
new caliban(__FILE__,Array('prePath'=>$_GET['page'],'deviceHandling'=>0));
?>
