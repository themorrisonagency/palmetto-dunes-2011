<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">	
    <xsl:output method="html"/>
    <xsl:param name="lang" select="//output/pageinfo/@language"/> 
	<xsl:template match="output/content">
	<![CDATA[<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">]]>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<xsl:value-of select="//output/content/meta" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<script type="text/javascript">
		//Global language variable for javascript
		LANG = 'en';
	</script>
	<script type="text/javascript" src="http://dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=6.2"></script>
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/jquery.msnmap.js"></script>
	<script type="text/javascript">
	$(function() {
		$('#map').msnMap({printMap:true}); 
	});
	</script>
	<style type="text/css">
	.wrapper { padding: 10px 0; overflow: hidden; }
	table { width: 100%; margin-bottom: 5px; }
	table tr td { padding: 3px 5px 3px 0; }
	table tr .first { width: 65%; }
	#route { float: left; width: 55% }
	#route ol { float: left; display: inline; overflow: hidden; list-style: none; font-family: Arial; font-size: 12px; margin: 0; padding: 0; border: 1px solid #000; border-bottom: none; }
	#route ol li { float: left; display: inline; width: 93%; padding: 2% 3%; overflow: hidden; border-bottom: 1px solid #000; padding:3px;  }
	#route ol .location { background-color: #F2F2FF; }
	#route ol .location div { float: left; display: inline; width: 65%; }
	#route ol li .pre { font-weight: bold; float: left; display: inline; margin-right: 3%; }
	#route ol li .text { float: left; display: inline; width: 75%; }
	#route ol li .distance { float: right; display: inline; font-weight: bold; }
	.map { float: right; width: 40%; margin: 0 2% 0 0; position: relative; overflow: hidden; height: 400px; }
	#addresses { display: none; }
	</style>
</head>
<body>
<div id="addresses">
	<?php
		foreach($address['address'] as $key => $value) {
			echo '<span>'.$value.'</span>';
		} 
	?>
</div>
<div class="wrapper">
    <h1>Directions</h1>
    <div>
        <div class="map" id="map"> </div>
        <div id="route"> </div>
    </div>
</div>
</body>


</body>




</html>

	</xsl:template>	
	{caliban:import(activeTemplatePathAbs,includes/navigation.xsl)}	
	{caliban:import(activeTemplatePathAbs,includes/includes.xsl)}	
	{caliban:import(activeTemplatePathAbs,includes/booking-console.xsl)}	
</xsl:stylesheet>
