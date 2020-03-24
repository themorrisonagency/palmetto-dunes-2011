<!DOCTYPE html>
<html lang="en" class="no-js" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<head>

    <?php \Loader::element('header_required') ?>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//vjs.zencdn.net/4.2/video.js"></script>
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
    ?>
    <link rel="stylesheet" type="text/css" href="<?= $view->getThemePath() ?>/css/custom.foundation.grid.css" media="all" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600%7CCabin:400,600,700%7CLora:400,700,400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?= $view->getThemePath() ?>/css/galleria.sabre.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?= $view->getThemePath() ?>/css/jquery.fancybox.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?= $view->getThemePath() ?>/css/video-js.css" media="all" />
    <link rel="shortcut icon" type="image/x-icon" href="<?= $view->getThemePath() ?>/images/favicon.ico" />
    <?php
        // if admin user, load null NAVIS script
        $user = new User;
        if ($user->isLoggedIn()) { ?>
            <script type="text/javascript" src="<?= $view->getThemePath() ?>/js/navis-null.js"></script>
    <?php } else { ?>
            <script type="text/javascript" src="https://www.navistechnologies.info/JavascriptPhoneNumber/js.aspx?account=15521&amp;jspass=r4wbe0wmz555swj4p02c&amp;dflt=8889099566"></script>
            <script type="text/javascript">ProcessNavisNCKeyword5(".palmettodunes.com", true, false, false, 90);</script>
    <?php } ?>	
</head>
<body class="catering-landing interior packages <?php
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
?>">
<div class="<?=$c->getPageWrapperClass()?>">
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
<div id="wrapper">
