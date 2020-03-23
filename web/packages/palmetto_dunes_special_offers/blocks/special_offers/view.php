<?php
/**
 * Available Composer settings
 *
 * "Title"
 *   $item->getCollectionName()
 *
 * "Description"
 *   $item->getCollectionDescription()
 *
 * "SEO Title"
 *   $item->getAttribute('meta_title')
 *
 * "SEO Description"
 *   $item->getAttribute('meta_description')
 *
 * "SEO Keywords"
 *   $item->getAttribute('meta_keywords')
 *
 * "Start Date"
 *   $item->getCollectionDatePublic()
 *
 * "End Date"
 *   $item->getAttribute('special_offer_end_date')
 *
 * "Category" - Returns an array of \Concrete\Core\Tree\Node\Type\Topic objects
 *   $item->getAttribute('special_offer_topics')
 *
 * "Tags"
 *   $item->getAttribute('special_offer_tags')
 *
 * "Image"
 *   $item->getAttribute('special_offer_image')
 *
 * "Mobile Image"
 *   $item->getAttribute('special_offer_mobile_image')
 *
 * "Show Booking Link"
 *   $item->getAttribute('special_offer_show_booking_link')
 *
 * "Booking Link"
 *   $controller->getAttributeLink($item, 'special_offer_booking_link')
 *
 * "Mobile Booking Link"
 *   $controller->getAttributeLink($item, 'special_offer_mobile_booking_link')
 *
 * "Show Alternate Link"
 *   $item->getAttribute('special_offer_show_alternate_link')
 *
 * "Alternate Link"
 *   $controller->getAttributeLink($item, 'special_offer_alternate_link')
 *
 * "Mobile Alternate Link"
 *   $controller->getAttributeLink($item, 'special_offer_alternate_mobile_link')
 *
 * "Content" - Outputs directly
 *   $item->getArea('Main')->display($item)
 *
 */

use Concrete\Core\Page\Collection\Version\Version;
use Concrete\Core\View\View;

defined('C5_EXECUTE') or die(_("Access Denied."));
$nh = \Core::make('helper/navigation');
$offer_count = count($results);
?>

<div class='amax-specialoffer'>
    <?php
        for($i = 0; $i <= $offer_count; $i += 2) {
            $items = array_slice($results, $i, 2);
    ?>


        <?php
            /** @var \Page $item */
            foreach ($items as $item) {
                /** @var \File $image */
                $image = $item->getAttribute('special_offer_image');
                $image_src = "";
                if ($image) {
                    $image_src = BASE_URL . \File::getRelativePathFromID($image->getFileID());
                }

                $active = false;
                if ($item->getCollectionID() == $offer_id) {
                    $active = true;
                }

                $string = strtolower($item->getCollectionName());

                $stringResult = preg_replace('/[^a-z0-9]+/i','-',$string);

                $link = $nh->getLinkToCollection($item);

        ?>
            <article class="<?= $item->getCollectionID() ?>">
                <div class="<?= $item->getCollectionID() ?>">
                    <div class="media">
                        <a class="package-toggle" href="<?= $link ?>">
                            <div class="media_top">
                                <div class="media_top_inner">
                                    <img src='<?= $image_src ?: 'http://placehold.it/314x178' ?>' class="media_img" width="314" height="178" alt="<?= h($item->getCollectionName()) ?>" />
                                    <h3><?= h($item->getCollectionName()) ?></h3>
                                    <div class="short-desc"><?= h($item->getCollectionDescription()) ?></div>
                                    <span class="btn-details"><em class="alt">View Details</em></span>
                                </div>
                            </div>
                        </a>
                        <div class="media_body">
                            <div class="media_body_inner">
                                <div class="long-desc">
                                    <?php
                                        $main_area = $item->getArea('Main');
                                        $main_area->display($item);
                                    ?>
                                </div>
                                <?php
                                    if ($item->getAttribute('special_offer_show_alternate_link')) {
                                ?>
                                        <a href="<?= $controller->getAttributeLink($item, 'special_offer_alternate_link') ?>"
                                           class="btn btn-full orange-btn">
                                            <?= $item->getAttribute('special_offer_alternate_link_text') ?>
                                        </a>
                                <?php
                                    }
                                ?>

                                <div class="share package-share">
                                    <ul>
                                        <li class="share-email">
                                            <a href="mailto:?subject=<?= h($item->getCollectionName()) ?>&amp;body=<?= urlencode($link) ?>" class="track">
                                                <em class="alt">Share</em>
                                            </a>
                                        </li>
                                        <li class="share-twitter">
                                            <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?= h($link) ?>" data-text="Check out the <?= h($item->getCollectionName()) ?> offer -">Tweet</a>
                                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                                        </li>
                                        <li class="share-facebook">
                                            <div class="fb-like" data-href="<?= h($link) ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>

                                        </li>
                                        <li class="share-google" style="margin-top:-1px;">
                                            <div class="g-plusone" data-size="medium" data-action="share" data-href="<?= $link ?>"></div>

                                        </li>
                                        <li class="share-pinit">
                                            <a href="//www.pinterest.com/pin/create/button/?url=<?= urlencode($item->getCollectionName()) ?>&amp;media=<?= urlencode($image_src) ?>&amp;guid=D3pHPU2kC8dK-0&amp;description=<?= urlencode($item->getCollectionName()) ?>" class="PIN_1396333597106_pin_it_button_20 PIN_1396333597106_pin_it_button_en_20_gray PIN_1396333597106_pin_it_button_inline_20 PIN_1396333597106_pin_it_none_20" target="_blank" data-pin-log="button_pinit" data-pin-config="none"><span class="PIN_1396333597106_hidden" id="PIN_1396333597106_pin_count_0"><i></i></span></a>
                                        </li>
                                    </ul>
                                </div>
                                <a class="btn-close-details" href="#">Close details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        <?php
            }
        ?>
    <?php
        }
    ?>
</div>

<script>
    // window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));
    // (function() {
    //     var when = function(test, callback) {
    //         var _do = function() {
    //             if (test()) {
    //                 return callback();
    //             } else {
    //                 setTimeout(function(){
    //                     _do();
    //                 }, 10);
    //             }
    //         };
    //         return _do();
    //     };

    //     if (typeof $ != 'undefined') {
    //         specialoffer();
    //     } else {
    //         var interval = setInterval(function () {
    //             if (typeof $ != 'undefined') {
    //                 clearInterval(interval);
    //                 specialoffer();
    //             }
    //         }, 20);
    //     }

    //     function specialoffer() {
    //         var element = $('.special-offer-pages');
    //         when(function() {
    //             return !(typeof window.twttr == 'undefined' ||
    //             typeof window.twttr.widgets == 'undefined' ||
    //             typeof window.twttr.widgets.createShareButton != 'function');
    //         }, function() {
    //             var elem = element.find('.share-twitter > div');
    //             elem.each(function() {
    //                 var me = $(this);
    //                 window.twttr.widgets.createShareButton(
    //                     me.data('url'),
    //                     me.get(0));
    //             });
    //         });
    //         when(function() {
    //             return !(typeof window.gapi == 'undefined' ||
    //             typeof window.gapi.plusone == 'undefined' ||
    //             typeof window.gapi.plusone.render != 'function');
    //         }, function() {
    //             var elem = element.find('.share-google > div');
    //             elem.each(function() {
    //                 var me = $(this);
    //                 window.gapi.plusone.render(me.get(0), me.data());
    //             });
    //         });

    //         var offers = $('.special-offer').each(function () {
    //             var me = $(this),
    //                 id = me.data('offer-id'),
    //                 content_element = me.closest('.special-offer-row').find('div.special-offer-page[data-offer-id=' + id + ']');

    //             me.click(function () {
    //                 if (me.hasClass('active')) {
    //                     me.removeClass('active');
    //                     content_element.removeClass('active').hide();
    //                 } else {
    //                     offers.filter('.active').click();

    //                     me.addClass('active');
    //                     content_element.addClass('active').height('auto').show();
    //                     var full_height = content_element.height();
    //                     content_element.height(0).height(full_height);
    //                 }
    //             });
    //         });
    //     }


    // }());
</script>
