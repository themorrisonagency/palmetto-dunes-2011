<meta charset="utf-8">
<title><?=$title;?></title>
<meta name="description" content="<?=$meta_description;?>" />
<link rel="canonical" href="<?=$canonical;?>" />
<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">

<?php
	// added AddHandler application/x-httpd-php .css to .htaccess file in /amp to allow 'shared' css + page-specific additions
	// AMP does not allow linked CSS and total page CSS _must_ be under 50Kb
	// echoing the open and closing style tags allows the includes
	// $current to be defined in the page's opening variable setting
	echo '<style amp-custom>';
	include 'css/common.php';
	include 'css/masthead.php';
	include 'css/'.$current.'.php';
	echo '</style>';
?>

