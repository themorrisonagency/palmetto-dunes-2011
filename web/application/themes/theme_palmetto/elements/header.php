<!DOCTYPE html>
<html lang="en" class="no-js" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<head>

    <?php \Loader::element('header_required') ?>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    /**
     * This is here to prevent c5 forms from being 600px
     */
    if (\Page::getCurrentPage()->isEditMode()) {
        ?>
        <style>
            body {
                position: static;
            }
            form {
                width: auto;
            }
			 
        </style>
    <?php
    }
    ?>

    <?php
        $th = Loader::helper('text');
        $pageName = $th->sanitizeFileSystem($c->getCollectionName(), $leaveSlashes=false);
        $parent = page::getByID($c->getCollectionParentID());
        $parentLink = $parent->getCollectionLink();
    ?>
 		 <style>
		 	/* need to move this to sass*/
			.packages-permalink .sg-mobile-next{display:none !important;}
			@media only screen and (min-width: 414px) and (max-width: 1024px) {
			.masthead-promo { bottom: 32%; }
			.masthead-title { font-size: 42px; }
			}
			@media only screen and (min-width: 614px) and (max-width: 1024px) {
			 .signup-wrap #survey-wrapper-1517811 form#sg_FormFor1517811 #sgE-1517811-1-26-element { height: 29px !important; }
			}
			@media only screen and (max-width: 768px){
				body.wed-home .overview-reviews .push-inset {margin: 0 auto !important;}
			   .overview-carousel .home-cycle .slide .cycle-caption .cycle-wrap .hp-title { font-size: 20px; }
			   .home-masthead .home-cycle .slide .cycle-caption .hp-title, .overview-masthead .home-cycle .slide .cycle-caption .hp-title {
					text-shadow: none;
					margin: 0 auto 18px;
					width: 100%;
					color: #024372;
					font-size: 20px;
				}
				#sg-signup-widget {  z-index: 99; }
				.sec-nav, body.interior .masthead, body.interior .masthead-wrapper {
					z-index: 90;
				}
			}
			.content-wrapper strong, .content-full strong { white-space: pre-wrap; }
        </style>
    <link rel="stylesheet" type="text/css" href="<?= $view->getThemePath() ?>/css/custom.foundation.grid.css" media="all" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600%7CCabin:400,600,700%7CLora:400,700,400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?= $view->getThemePath() ?>/css/galleria.sabre.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?= $view->getThemePath() ?>/css/video-js.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?= $view->getThemePath() ?>/css/jquery.fancybox.css" media="all" />
    <link rel="shortcut icon" type="image/x-icon" href="<?= $view->getThemePath() ?>/images/favicon.ico" />
    <link href="https://vjs.zencdn.net/6.6.3/video-js.css" rel="stylesheet">
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js" async></script>

    <?php
        // if admin user, load null NAVIS script
        $user = new User;
        if ($user->isLoggedIn()) { ?>
            <script type="text/javascript" src="<?= $view->getThemePath() ?>/js/navis-null.js"></script>
    <?php } else { ?>
            <script type="text/javascript" src="https://www.navistechnologies.info/JavascriptPhoneNumber/js.aspx?account=15521&amp;jspass=r4wbe0wmz555swj4p02c&amp;dflt=8889099566"></script>
            <script type="text/javascript">ProcessNavisNCKeyword5(".palmettodunes.com", true, false, false, 90);</script>
    <?php } ?>

    <?php //Event Permalink Page Meta Data
        if ($pageName == 'event') {
            $this->inc('elements/opengraph.php');
        }
    ?>
</head>
<body data-pageid="<?php echo $c->getCollectionID() ?>" class="<?
switch($c->getPageTemplateHandle()) {
    case 'blog':
    case 'blog_entry':
        print 'interior blog ';
        break;
    case 'right_sidebar':
        print 'interior packages ';
        break;
    case 'special_offer':
        print 'interior packages packages-permalink';
        break;
    case 'full_width':
        print 'interior full ';
        break;
    default:
        if ($pageName == 'home') {
            print 'home ';
        } else {
            print 'interior ';
        }
        break;
}
switch($c->getAttribute('console_display')) {
    case 'No Console':
        print 'no-console ';
        break;
    case 'Open to Vacation Rentals':
        print 'vacation-console ';
        break;
    case 'Open to Tee Times':
        print 'golf-console ';
        break;
    case 'Open to Bike Rentals':
        print 'bikes-console ';
        break;
    case 'Open to Fishing Charters':
        print 'fareharbor-console ';
        break;
    default:
        break;
}

if (strpos($parentLink, 'meetings-weddings') == true) {
    print 'weddings-overview ';
    if ($pageName == 'weddings') { print 'wed-home'; }
    if ($pageName == 'request-a-wedding-proposal') { print 'wed-home wed-rfp interior no-signup'; }
    if ($pageName == 'wedding-venues') { print 'wed-venues interior'; }
    if ($pageName == 'honeymoons') { print 'wed-honeymoons interior'; }
    if ($pageName == 'our-experts') { print 'our-experts interior no-console'; }
    if ($pageName == 'wedding-details') { print 'wedding-details interior no-console'; }
    if ($pageName == 'weekend-itinerary') { print 'weekend-itinerary interior no-console'; }
    if ($pageName == 'thank-you-for-your-request') { print 'thank-you-for-your-request'; }
    if ($pageName == 'wedding-packages-details') { print 'wedding-packages-details packages'; }
} if (strpos($parentLink, 'golf') == true) {
    print 'golf-overview ';
    print $pageName.' ';
} else {
    if ($pageName == 'weddings') { print 'weddings-overview wed-home'; }
    if ($pageName == 'request-a-wedding-proposal') { print 'weddings-overview wed-home wed-rfp interior no-signup'; }
    if ($pageName == 'wedding-venues') { print 'weddings-overview wed-venues interior'; }
    if ($pageName == 'honeymoons') { print 'weddings-overview wed-honeymoons interior'; }
    if ($pageName == 'our-experts') { print 'weddings-overview our-experts interior no-console'; }
    if ($pageName == 'wedding-details') { print 'weddings-overview wedding-details interior no-console'; }
    if ($pageName == 'weekend-itinerary') { print 'weddings-overview weekend-itinerary interior no-console'; }
    if ($pageName == 'thank-you-for-your-request') { print 'weddings-overview thank-you-for-your-request'; }
    if ($pageName == 'wedding-packages-details') { print 'weddings-overview wedding-packages-details packages'; }
}

if ($pageName == 'hilton-head-resort-map') { print ' no-signup map '; }
if ($pageName == 'rates-packages') { print 'packages '; }
if ($pageName == 'live-webcams') { print 'webcam '; }
if ($pageName == 'overview') { print 'overview-page cove-overview'; }
if ($pageName == 'overview2') { print 'overview-page cove-overview landing-schm'; }
if ($pageName == 'activities-overview') { print 'overview-page activities-overview'; }
if ($pageName == 'video-masthead-slider') { print 'mast-vid'; }
if ($pageName == 'resort-calendar') { print 'resort-calendar'; }
if ($pageName == 'event') { print 'event-permalink'; }
if ($pageName == 'cruises-water-sports') { print 'cruises-water-sports'; }
if ($pageName == 'contact-a-vacation-planner') { print 'hilton-head-vacation-planner'; }
if ($pageName == 'group-packages-specials') { print 'packages '; }

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (strpos($url,'/hilton-head-island-tennis') !== false) { print 'tennis'; }
if (strpos($url,'hilton-head-pet-friendly-rentals') !== false) { print 'pet-friendly'; }
?>">

<? if (\Page::getCurrentPage()->isEditMode() === false) {
    // omit Google Tag Manager and Fb when in Edit Mode ?>
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WRZVG6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WRZVG6');</script><div id="fb-root" style="display:none;"></div>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=138365476186446";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<? // end of GTM and Fb omission logic
    } ?>


<div class="<?=$c->getPageWrapperClass()?>">

<div id="wrapper">
