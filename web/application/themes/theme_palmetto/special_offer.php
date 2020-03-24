<?php
/**
 * Require all palmetto css assets
 * These assets are defined in /application/config/app.php
 */
$view = \View::getInstance();
$view->requireAsset('css', 'palmetto.layout');


$this->inc('elements/header.php');
/** @var \File $image */
$nh = \Core::make('helper/navigation');
$image = $c->getAttribute('special_offer_image');
$image_src = "";
if ($image) {
    $image_src = BASE_URL . \File::getRelativePathFromID($image->getFileID());
} else {
    $image_src = 'http://placehold.it/314x178';
}

$active = false;
if ($c->getCollectionID() == $offer_id) {
    $active = true;
}

$string = strtolower($c->getCollectionName());

$stringResult = preg_replace('/[^a-z0-9]+/i','-',$string);

$link = $nh->getLinkToCollection($c);

/**
 * OpenGraph tagging
 */
$opengraph = '<meta property="og:image" content="'.$image_src.'">';
if ($c->getCollectionAttributeValue('meta_title') != '') {
    $opengraph .= '<meta property="og:title" content="'.$c->getCollectionAttributeValue('meta_title').'">';
} else {
    $opengraph .= '<meta property="og:title" content="'.h($c->getCollectionName()).'">';
}
if ($c->getCollectionAttributeValue('meta_description') != '') {
    $opengraph .= '<meta property="og:description" content="'.$c->getCollectionAttributeValue('meta_description').'">';
} else {
    $opengraph .= '<meta property="og:description" content="'.h($c->getCollectionDescription()).'">';
}
    $opengraph .= '<meta property="og:keywords" content="'.$c->getCollectionAttributeValue('meta_keywords').'">';

$this->addHeaderItem($opengraph);
?>

<script type="text/javascript">
	var __lc = {};
	__lc.license = 6344501;
	window.__lc.chat_between_groups = false;
	(function() {
		var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
		lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
	})();
</script>

<div id="wrapper">
    <div class="page-wrapper">
        <div class="sec-nav int-nav">

        </div>
        <div class="masthead-wrapper">
            <?
                $a = new GlobalArea('Offer Masthead');
                $a->display();
            ?>
        </div>

        <?
        $a = new GlobalArea('Booking Console');
        $a->setBlockLimit(1);
        $a->display();
        ?>

        <div class="pri-header"><h1><span>Vacation Packages</span></h1></div>
        <div class="breadcrumb"></div>
        <div class="content-wrapper">
            <div class="content-inner">
                <div class="content-col-right">
                    <div class="mobile-filters<? if($c->isEditMode()) { print '-editmode'; } ?>">
                        <? $a = new GlobalArea("Mobile Offer Filters"); $a->display(); ?>
                    </div>
                    <div class="filters">
                        <? $a = new GlobalArea("Offer Filters"); $a->display(); ?>
                    </div>                    
                </div>
                <div class="content-col-left">
                    <div class="permalink amax-specialoffer">
                        <article class="end odd active" itemscope="" itemtype="http://schema.org/Product">
                            <meta itemprop="brand" content="Palmetto Dunes Oceanfront Resort">
                            <meta itemprop="sameAs" content="<?= $link; ?>">
                            <div class="2058">
                                <div class="media permalink-open ">
                                    <a class="package-toggle" href="<?= $link; ?>">
                                        <div class="media_top">
                                            <div class="media_top_inner">
                                                <img src="<?= $image_src ?: 'http://placehold.it/314x178' ?>" alt="<?= h($c->getCollectionName()) ?>" class="media_img" itemprop="image" width="314" height="178">
                                                <h3 itemprop="name"><?= h($c->getCollectionName()) ?></h3>
                                                <div class="short-desc"><?= h($c->getCollectionDescription()) ?></div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="media_body">
                                        <div class="media_body_inner">
                                            <div class="long-desc">
                                                <? $a = new Area("Main");
                                                $a->display($c); ?>
                                            </div>
                                            <?php
                                                if ($c->getAttribute('special_offer_show_alternate_link')) {
                                            ?>
                                                    <a href="<?= $c->getAttribute('special_offer_alternate_link') ?>"
                                                       class="btn btn-full orange-btn">
                                                        <?= $c->getAttribute('special_offer_alternate_link_text') ?>
                                                    </a>
                                            <?php
                                                }
                                            ?>
                                            <div class="share package-share">
                                                <ul>
                                                    <li class="share-email">
                                                        <a href="mailto:?subject=<?= h($c->getCollectionName()) ?>&amp;body=<?= urlencode($link) ?>" class="track">
                                                            <em class="alt">Share</em>
                                                        </a>
                                                    </li>
                                                    <li class="share-twitter">
                                                        <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?= h($link) ?>" data-text="Check out the <?= h($c->getCollectionName()) ?> offer -">Tweet</a>
                                                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                                                    </li>
                                                    <li class="share-facebook">
                                                        <div class="fb-like" data-href="<?= h($link) ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>

                                                    </li>
                                                    <li class="share-google" style="margin-top:-1px;">
                                                        <div class="g-plusone" data-size="medium" data-action="share" data-href="<?= $link ?>"></div>

                                                    </li>
                                                    <li class="share-pinit">
                                                        <a href="//www.pinterest.com/pin/create/button/?url=<?= urlencode($c->getCollectionName()) ?>&amp;media=<?= urlencode($image_src) ?>&amp;guid=D3pHPU2kC8dK-0&amp;description=<?= urlencode($c->getCollectionName()) ?>" class="PIN_1396333597106_pin_it_button_20 PIN_1396333597106_pin_it_button_en_20_gray PIN_1396333597106_pin_it_button_inline_20 PIN_1396333597106_pin_it_none_20" target="_blank" data-pin-log="button_pinit" data-pin-config="none"><span class="PIN_1396333597106_hidden" id="PIN_1396333597106_pin_count_0"><i></i></span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                    <!-- static snippet -->
                </div>
				<div class="assist-contact">
					<? $a = new GlobalArea("Offer Sidebar"); $a->display(); ?>
				</div>
            </div>
        </div>
        <div class="signup-content"></div>
        <div class="cross-promo-content">
            <div class="cross-promo-inner">
            <?php

            $a = new GlobalArea('Offer Cross-Promo Content');
            $a->display();

            ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->inc('elements/footer.php');
?>
