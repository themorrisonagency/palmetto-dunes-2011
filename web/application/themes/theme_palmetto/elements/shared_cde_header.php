<? // this is parsed via a cron job to sync up with the CDE-driven Pal Dunes sites ?>
<div id="wrapper"><article>
<div  id="header">
    <div id="header-wrapper">
        <div id="branding">
            <a href="<?=URL::to('/'); ?>"><img src="<?=URL::to('/application/themes/theme_palmetto/images/logo.svg'); ?>" alt="Palmetto Dunes Oceanfront Resort" border="0" width="" height=""/></a>
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
                    $a = new GlobalArea("Weather Feed");
                    $a->display();
                ?>
            </div>
            <div class="header-phone"><script type='text/javascript'>ShowNavisNCPhoneNumber();</script></div>
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
</article>
</div>
