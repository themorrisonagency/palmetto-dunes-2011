<? // this is parsed via a cron job to sync up with the CDE-driven Pal Dunes sites ?>
<div id="wrapper">
<article>
<div  class="footer-wrapper">
    <div class="footer">
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
                        <li class="push-photos"><a href="<?=URL::to('/'); ?>#gallery"><div class="footer-push-inset">
                            <img src="<?=URL::to('/application/themes/theme_palmetto/images/layout/ftr-photo-video-btn.png'); ?>" alt="Photos &amp; Videos Footer" border="0" width="85" height="85"/>
                            <div class="footer-push-title">Photos &amp; Videos</div></div>
                            </a>
                        </li>
                        <li class="gift-card-store"><a href="https://palmettodunesstore.com/" target="_blank"><div class="footer-push-inset">
                            <img src="<?=URL::to('/application/themes/theme_palmetto/images/layout/ftr-gift-card-store-btn.png'); ?>" alt="Gift Card Store" border="0" width="85" height="85"/>
                            <div class="footer-push-title">Gift Card Store</div></div>
                            </a>
                        </li>
                    </ul>
                    <p>Palmetto Dunes Oceanfront Resort<br><a href="https://www.google.com/maps/place/Palmetto+Dunes+Oceanfront+Resort/@32.177764,-80.726486,17z/data=!3m1!4b1!4m2!3m1!1s0x88fc79f40d10ee35:0xddd6dc64d3cc0ee2" target="_blank">4 Queens Folly Road<br>
                            Hilton Head Island, SC 29928</a></p>
                    <p class="phone">Phone: <script type='text/javascript'>ShowNavisNCPhoneNumber();</script></p>
                    <p><a href="<?=URL::to('/contact-us'); ?>" class="footer-link">Contact Us</a><span class="divide"> | </span>
                    <a href="<?=URL::to('/hilton-head-resort-directions'); ?>" class="footer-link">Maps</a></p>
                </div>
            </div>
            <div id="social-icons">
                <ul>
                    <li id="footer-facebook"><a href="https://www.facebook.com/PalmettoDunes" target="_blank"><em class="alt">Facebook</em></a></li>
                    <li id="footer-twitter"><a href="https://twitter.com/palmettodunessc" target="_blank"><em class="alt">Twitter</em></a></li>
                    <li id="footer-youtube"><a href="http://www.youtube.com/user/PalmettoDunes" target="_blank"><em class="alt">Youtube</em></a></li>
                    <li id="footer-pinterest"><a href="http://www.pinterest.com/palmettodunes/" target="_blank"><em class="alt">Pinterest</em></a></li>
                </ul>
                <div class="ftr-signup-btn"><a href="<?=URL::to('/resort-enews-signup'); ?>" class="footer-btn btn-contact tan-btn">Sign up for our Resort Newsletter here</a></div>
                <div id="footer-search">
                    <?php
                        $a = new GlobalArea('Site Search');
                        $a->display();
                    ?>
                </div>
            </div>

            <div class="footer-bottom">
                <div id="footer-links">&copy; 2019 <?php echo Config::get('concrete.site'); ?>
                    <a href="http://www.greenwoodcr.com/" target="_blank"><img class="logo-gw" src="<?=URL::to('/application/themes/theme_palmetto/images/layout/logo-greenwood.svg'); ?>" alt="Greenwood" border="0" width="" height=""/></a>
                </div>
                <div id="utility-nav">
                    <ul>
                        <li><a href="<?=URL::to('/hilton-head-island-resort-press'); ?>">Press Center &amp; Careers</a></li>
                        <li><a href="<?=URL::to('/privacy-policy'); ?>">Privacy Policy</a></li>
                        <li><a href="<?=URL::to('/cookie-policy'); ?>">Cookie Policy</a></li>
                        <li><a href="<?=URL::to('/terms-conditions'); ?>">Terms &amp; Conditions</a></li>
                        <li><a href="<?=URL::to('/site-map'); ?>">Site Map</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</article>
</div>
