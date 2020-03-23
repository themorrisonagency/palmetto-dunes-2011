<?php
/*
 * The purpose of this file is to help cache, optimize and auto resize images.
 * This is only for use when image resizing in enabled in .htaccess by adding the Media mod-rewrite rules.
 * See: http://cakebox.esitelabs.com/caliban/manual/pmwiki.php?n=Main.AutomatedImageResizing for more information.
 */ 

require_once('../conf/conf.php');
require_once(CLASSES . 'Media.Class.php');

Media::proccess();
?>
