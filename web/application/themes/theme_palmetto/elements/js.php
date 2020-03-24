<?
	if(!$c->isEditMode()) {
?>
	<!--<script type="text/javascript" src="<?= $view->getThemePath()?>/js/jquery-ui.js"></script>-->
	<script src="/application/js/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/jquery-ui-effects.js"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/modernizr.js"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/jcycle2.js"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/jcarousel.js"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/jquery.touchwipe.1.1.1.js"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/slick.min.js"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/jquery.object-fit.min.js"></script>
	<!--[if lte IE9 ]>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/placeMe.min.js"></script>
	<![endif]-->
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/jquery.fancybox.js"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/jquery.fancybox-media.js"></script>
<? if (176 == $c->getCollectionID()) { //map page check ?>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&amp;key=AIzaSyARJ73kkj3nvoEmZut-EbK_AFUoSW_g1rw&amp;libraries=places&amp;sensor=false&amp;language=en"></script>
	<script type="text/javascript" src="https://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0&amp;c=en"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/maps/jquery-uniqueArray.min.js"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/maps/infobox.js"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/maps/richmarker-compiled.js"></script>

	<script type="text/javascript">
		MAPVENDOR = 'google';
		RELPATH = '/application/themes/theme_palmetto/';
		SMARTPHONE = false;
		MAPDATA = '/application/themes/theme_palmetto/js/maps/poi.json';

		var map_data = '/application/themes/theme_palmetto/js/maps/poi.json';
	</script>

	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/maps/jquery-poi-google.js"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/maps/settings.js"></script>
<? } // end map page check ?>
  <!-- If you'd like to support IE8 -->
	<script src="https://vjs.zencdn.net/6.6.3/video.js" async></script>
	<!-- <script src='https://gallusgolf.com/Utilities/MoWebJavaScript/104/script.js' type='text/javascript' async></script> -->
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/sabre-components.js"></script>
	<script type="text/javascript" src="<?= $view->getThemePath()?>/js/cbpAnimatedHeader.js"></script>
<?
	}
?>

<script type="text/javascript" src="<?= $view->getThemePath()?>/js/pickadate.js"></script>
<script type="text/javascript" src="<?= $view->getThemePath()?>/js/parsley.min.js"></script>

<script src="https://fareharbor.com/embeds/api/v1/"></script>

<script type="text/javascript">
(function(config) {
window._peekConfig = config || {};
var idPrefix = 'peek-book-button';
var id = idPrefix+'-js'; if (document.getElementById(id)) return;
var head = document.getElementsByTagName('head')[0];
var el = document.createElement('script'); el.id = id;
var date = new Date; var stamp = date.getMonth()+"-"+date.getDate();
var basePath = "https://js.peek.com";
el.src = basePath + "/widget_button.js?ts="+stamp;
head.appendChild(el); id = idPrefix+'-css'; el = document.createElement('link'); el.id = id;
el.href = basePath + "/widget_button.css?ts="+stamp;
el.rel="stylesheet"; el.type="text/css"; head.appendChild(el);
})({key: '5263aba4-e7cd-47a4-b36a-ec54cb3a823e'});
</script>

<script type="text/javascript" src="<?= $view->getThemePath()?>/js/js.cookie.js"></script>
