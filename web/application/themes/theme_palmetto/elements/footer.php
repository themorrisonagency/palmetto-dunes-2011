
<style>

/* this is a fix for the scroll glitch, had to seperate the scrollup button from the logo */
.interior #header.fixed #header-wrapper #brandingtop a {
    text-indent: -9999em;
    overflow: hidden;
    display: block !important;
    width: 265px;
    height: 59px;
    background: url("/application/themes/theme_palmetto/images/logo-up.gif") 0 0 no-repeat;
}
.interior #header.fixed #header-wrapper #brandingtop {
    display: inline-block !important;
}

.interior #header.fixed #header-wrapper #branding {

    display: none;
}
.interior #header.fixed .resort-num {

}
.interior #header.fixed .bg-subnav{display: none !important;}
/* Hide Header Elements on /shelter-cove/shelter-cove-harbour-marina */
.interior.landing-schm .primary-nav,
.interior.landing-schm .resort-num,
.interior.landing-schm .fireworks-widget-wrapper,
.interior.landing-schm .weather-widget-wrapper {
    display: none !important;
}

#cookie-notification {
  display: none;
  width: 100%;
  position: fixed;
  left: 0;
  bottom: 0;
  background-color: rgba(70, 70, 70, 0.8);
  color: #fff;
  text-align: center;
  padding: 15px 0;
  z-index: 9999;
}

#cookie-notification a {
  color: #fff;
}

#accept-cookie-notification {
  display: inline-block;
  float: right;  
  padding: 0 25px;
  text-decoration: underline;
  color: #fff;  
}

#cookie-notification .cookie-text {
  max-width: 90%;
  display: inline-block;
}

@media only screen and (max-width: 1024px) {
  #accept-cookie-notification {
    float: none;
    width: 100%;
    border-left: none;
    padding: 10px 0 0 0;
  }
}
</style>

    <?php
        $th = Loader::helper('text');
        $pageName = $th->sanitizeFileSystem($c->getCollectionName(), $leaveSlashes=false);
    ?>

<div  id="header">

    <div id="header-wrapper">
        <div class="weather-update-widget-wrapper-mobile">
            <?php
                $a = new GlobalArea('Weather Update Widget Mobile');
                $a->display();
            ?>
        </div>
        <div id="owner-login">
             <div id="owner-copy">
                <? if ($pageName == 'overview2') { ?>
                     (855) 869-3480
                <? } else { ?>
                    <?
                        $a = new GlobalArea("Property Announcement");
                        $a->display();
                    ?>
                <? } ?>
            </div>
            <div id="owner-link">
                <div id="owner-login-tip">Login Here for Palmetto Dunes Vacation Rental Owners</div>
                <a href="https://secure.instantsoftwareonline.com/OwnerLink/Owners/PropertyOwnerLogin.aspx?coid=2369"><i class="fa fa-key" aria-hiden="true"></i>Owner Login</a>
            </div>
        </div>
        <div id="branding">
            <? if ($pageName == 'overview2') { ?>
                <a href="/" title="Return to Home Page"><img src="/application/themes/theme_palmetto/images/logo-schm.png" alt="" border="0" width="" height=""/></a>
            <? } else { ?>
            <a href="/" title="Return to Home Page"><img src="/application/themes/theme_palmetto/images/logo.svg" alt="Palmetto Dunes Oceanfront Resort" border="0" width="" height=""/></a>
            <? } ?>
        </div>

        <div id="brandingtop" style="display:none;"  >
            <a href="#" title="Return to Home Page"><img src="/application/themes/theme_palmetto/images/logo.svg" alt="Palmetto Dunes Oceanfront Resort" border="0" width="" height=""/></a>
        </div>

        <div class="primary-nav">
            <div class="nav-toggle gradient">
                <div class="menu-btn">
                    <div class="menu-inner">
                        <span class="tablet-menu">Navigation </span>Menu
                    </div>
                </div>
            </div>
            <div class="console-toggle gradient">
                <div class="console-menu-btn">
                    <div class="console-menu-inner">
                        <span>Check</span> Availability
                    </div>
                </div>
            </div>
            <div class="amax-block amax-menu amax-defaultblock">
                <?
                    $a = new GlobalArea("Primary Navigation");
                    $a->display();
                ?>
            </div>
        </div>
        <div class="resort-num">
            <div class="weather-feed">
                <?php
                    $a = new GlobalArea('Weather Feed');
                    $a->display();
                ?>
            </div>
            <div class="header-phone"><script type='text/javascript'>ShowNavisNCPhoneNumber();</script><noscript>(855) 909-9566</noscript></div>
        </div>
        <div class="weather-update-widget-wrapper">
            <?php
                $a = new GlobalArea('Weather Update Widget');
                $a->display();
            ?>
        </div>
        <div class="fireworks-widget-wrapper">
            <?php
                $a = new GlobalArea('Fireworks Widget');
                $a->display();
            ?>
        </div>
        <div class="weather-widget-wrapper">
            <?php
                $a = new GlobalArea('Weather Widget');
                $a->display();
            ?>
        </div>
    </div>
    <div class="bg-subnav gradient"></div>
</div>
<div  class="footer-wrapper"><div class="footer">
    <div class="footer-top"><a href="#" class="go-top"><em class="alt">Back to Top</em></a></div>
    <div class="footer-cols">
            <div class="col col-1">
                <h3><a href="https://www.instagram.com/palmettodunessc/" target="_blank"><span>Instagram &#35;Palmettodunes</span></a></h3>
                <div class="insta-feed">
                    <a href="https://www.instagram.com/palmettodunessc/" target="_blank"><img src="/application/themes/theme_palmetto/images/instagram/instagram1.jpg"></a>
                    <a href="https://www.instagram.com/palmettodunessc/" target="_blank"><img src="/application/themes/theme_palmetto/images/instagram/instagram2.jpg"></a>
                    <a href="https://www.instagram.com/palmettodunessc/" target="_blank"><img src="/application/themes/theme_palmetto/images/instagram/instagram3.jpg"></a>
                    <a href="https://www.instagram.com/palmettodunessc/" target="_blank"><img src="/application/themes/theme_palmetto/images/instagram/instagram4.jpg"></a>
                </div>
            </div>
            <div class="col col-2">
                <ul class="push-footer">
                    <li class="push-photos"><a href="/photos-videos"><div class="footer-push-inset">
                        <img src="/application/themes/theme_palmetto/images/layout/ftr-photo-video-btn.png" alt="Photos &amp; Videos Footer" border="0" width="85" height="85"/>
                        <div class="footer-push-title">Photos &amp; Videos</div></div>
                        </a>
                    </li>
                    <li class="gift-card-store"><a href="https://palmettodunesstore.com/" target="_blank"><div class="footer-push-inset">
                        <img src="/application/themes/theme_palmetto/images/layout/ftr-gift-card-store-btn.png" alt="Gift Card Store" border="0" width="85" height="85"/>
                        <div class="footer-push-title">Gift Card Store</div></div>
                        </a>
                    </li>
                </ul>
                <p>Palmetto Dunes Oceanfront Resort<br><a href="https://www.google.com/maps/place/Palmetto+Dunes+Oceanfront+Resort/@32.177764,-80.726486,17z/data=!3m1!4b1!4m2!3m1!1s0x88fc79f40d10ee35:0xddd6dc64d3cc0ee2" target="_blank">4 Queens Folly Road<br>
                        Hilton Head Island, SC 29928</a></p>
                <p class="phone">Phone: <script type='text/javascript'>ShowNavisNCPhoneNumber();</script><noscript>(855) 909-9566</noscript></p>
                <p>
                    <a href="/contact-us" class="footer-link">Contact Us</a>
                    <span class="divide"> | </span>
                    <a href="/hilton-head-resort-directions" class="footer-link">Maps</a>
                    <span class="divide"> | </span>
                    <a href="https://www.palmettodunes.com/resort-policies" class="footer-link">Resort Policies</a>
                </p>
            </div>
        </div>
        <div id="social-icons">
            <ul>
                <li id="footer-facebook"><a href="https://www.facebook.com/PalmettoDunes" target="_blank"><em class="alt">Facebook</em></a></li>
                <li id="footer-twitter"><a href="https://twitter.com/palmettodunessc" target="_blank"><em class="alt">Twitter</em></a></li>
                <li id="footer-youtube"><a href="http://www.youtube.com/user/PalmettoDunes" target="_blank"><em class="alt">Youtube</em></a></li>
                <li id="footer-pinterest"><a href="http://www.pinterest.com/palmettodunes/" target="_blank"><em class="alt">Pinterest</em></a></li>
            </ul>
            <div class="ftr-signup-btn"><a href="/resort-enews-signup" class="footer-btn btn-contact tan-btn">Sign up for our Resort Newsletter here</a></div>
            <div id="footer-search">
                <?php
                    $a = new GlobalArea('Site Search');
                    $a->display();
                ?>
            </div>
        </div>

        <div class="footer-bottom">
            <div id="footer-links">&copy; 2019 <?php echo Config::get('concrete.site'); ?>
                <a href="http://www.greenwoodcr.com/" target="_blank"><img class="logo-gw" src="/application/themes/theme_palmetto/images/layout/logo-greenwood.svg" alt="Greenwood" border="0" width="" height=""/></a>
            </div>
            <div id="utility-nav">
                <ul>
                    <li><a href="/hilton-head-island-resort-press">Press Center &amp; Careers</a></li>
                    <li><a href="/privacy-policy">Privacy Policy</a></li>
                    <li><a href="/cookie-policy">Cookie Policy</a></li>
                    <li><a href="/terms-conditions">Terms &amp; Conditions</a></li>
                    <li><a href="/site-map">Site Map</a></li>
                </ul>
            </div>
        </div>
        
        <div id="cookie-notification">
          <div class="cookie-text">
            <p>This website uses cookies to ensure you get the best experience on our website. For more info please visit our <a target="_blank" href="https://www.palmettodunes.com/cookie-policy">cookie policy</a>.</p>

            <p>We are committed to providing a website that is accessible to all. Please see our <a href="https://urldefense.proofpoint.com/v2/url?u=https-3A__www.palmettodunes.com_accessibility-2Dpolicy&d=DwMGaQ&c=FXJfUb1oWgygD0uNz-ujnA&r=Sh8EGsBqxXCERaeaIk-_nEBmZEI0bkd8fvrh2IgWNgA&m=jlTZUMGqA_llvn0Qp86EdCYlxFFOpn3oGxVF095pWM4&s=2RX0c61Q0JFBGAMMV9lNKzL12WqDkXJTUJf7M9pPYns&e=">accessibility policy</a>.</p>
            
          </div>
          <a href="#" id="accept-cookie-notification">Got it</a>
        </div>
    </div></div>
    <?php
        $a = new GlobalArea('Gallery Holder');
        $a->display();
    ?>
</div>

<?php
    $a = new GlobalArea('Signup Flyout');
    $a->display();
?>


<script src="//apis.google.com/js/plusone.js"></script><script src="//platform.twitter.com/widgets.js"></script><script type="text/javascript">
    (function(d){
        var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
        p.type = 'text/javascript';
        p.async = true;
        p.src = '//assets.pinterest.com/js/pinit.js';
        f.parentNode.insertBefore(p, f);
    }(document));
</script>
<script type="text/javascript">adroll_adv_id = "API2XN4LPVAP7DO67HGTZ4";adroll_pix_id = "K56HXQWN3BATLABHESNSQ6";(function () {var oldonload = window.onload;window.onload = function(){   __adroll_loaded=true;   var scr = document.createElement("script");   var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");   scr.setAttribute('async', 'true');   scr.type = "text/javascript";   scr.src = host + "/j/roundtrip.js";   ((document.getElementsByTagName('head') || [null])[0] ||    document.getElementsByTagName('script')[0].parentNode).appendChild(scr);   if(oldonload){oldonload()}};}());</script><script type="text/javascript">
    /*  */
    var google_conversion_id = 1000613885;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /*  */
</script><script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script><noscript><div style="display:inline;"><img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1000613885/?value=0&amp;guid=ON&amp;script=0"></div></noscript>
<script type="text/javascript">

    var $buoop = {
        vs: {i:8,f:20,o:12.1,s:4.1,c:10},
        reminder: 24,
        text: "This website is best viewed in a new version of your browser. Please update. <a href=\"https://www.google.com/chrome/browser/\">Chrome</a>, <a href=\"https://www.mozilla.org/en-US/firefox/new/\">Firefox</a>, <a href=\"http://windows.microsoft.com/en-us/internet-explorer/download-ie\">Internet Explorer</a>",
        newwindow: true
    };
    $buoop.ol = window.onload;
    window.onload=function(){
        try {if ($buoop.ol) $buoop.ol();}catch (e) {}
        var e = document.createElement("script");
        e.setAttribute("type", "text/javascript");
        e.setAttribute("src", "<?= $view->getThemePath() ?>/js/browser-update.js");
        document.body.appendChild(e);
    }

</script>
<script type="text/javascript">
$(document).ready(function(){
  if ( typeof Cookies.get('cookie-notification') === 'undefined' ) {
    $('#cookie-notification').slideDown(300);
  }
  $('#accept-cookie-notification').click(function(e){
    e.preventDefault();
    $('#cookie-notification').slideUp(300);
    Cookies.set('cookie-notification', 'yes');
  });
});
</script>
</div>
<?php
    $this->inc('elements/js.php');
?>
<?php \Loader::element('footer_required') ?>


</body>
</html>
