<?php use Concrete\Core\Tree\Node\Node;

defined('C5_EXECUTE') or die('Access Denied.');

$c = Page::getCurrentPage();
$cID = $c->getCollectionID();
$nh = Loader::helper('navigation');
$cpl = $nh->getCollectionURL($c);

echo '<section class="calendar columns events-listing'; if ($permalink) { echo ' permalink'; } else { echo ' large-7'; } echo '">';
    $num_items = count($items);
    foreach ($items as $item) {
        $event_name = $item['name'];
        $event_intro = $item['eventIntro'];
        $event_description = $item['description'];
        $f = File::getByID($item['eventImage']);
        if(is_object($f)) {
            $event_image = Core::make('html/image', array($f, false))->getTag();
            $event_image->itemprop('image');
            if ($item['eventImageAlt']) {
                $event_image->alt($item['eventImageAlt']);
            } else {
                $event_image->alt('');
            }
        }
        $event_link = $item['eventAddLink'];
        $event_link_text = $item['eventLinkText'];
        $event_link_url = $item['eventLinkUrl'];
        $event_permalink = View::url('/event/') . '/' . $bID . '/' . $item['id'] . '/' . rawurldecode(str_replace(' ','-',strtolower($event_name)));
        $event_date_format = $item['eventDateFormat'];
        switch ($event_date_format) {
            case '':
                $event_date = date("F j, Y", $item['start']['time']);
                break;
            case 0:
                $event_date = date("m/d/Y", $item['start']['time']);
                break;
            case 1:
                $event_date = date("d/m/Y", $item['start']['time']);
                break;
            default:
                $event_date = date("F j, Y", $item['start']['time']);
                break;
        }
        $event_time_from = $item['start']['hour'] . ':' . $item['start']['minute'] . ' ' . $item['start']['meridiem'];
        $event_time_to = $item['end']['hour'] . ':' . $item['end']['minute'] . ' ' . $item['end']['meridiem'];
        $event_meta_date = date("Y-m-d H:i:s", $item['start']['time']);
?>
        <section class="row" itemscope="itemscope" itemtype="http://schema.org/Event">
            <? if ($event_image) { ?>
            <aside class="small-12 medium-5 columns">
                <?= $event_image; ?>
            </aside>
            <? } ?>
            <article class="small-12 <? if ($event_image) { ?>medium-7 <? } ?>columns left-extra">
                <header>
                    <h3 itemprop="name"><?= $event_name; ?></h3>
                    <h4><?= $event_date; ?></h4>
                    <h5>From <?= $event_time_from; ?> to <?= $event_time_to; ?></h5>
                    <meta itemprop="startDate" content="<?= $event_meta_date; ?>" />
                    <p itemprop="description"><?= $event_intro; ?></p>
                </header>
                <section class="expandable long-description">
                    <?= $event_description; ?>
                    <ul class="share-tags">
                        <li><a class="fi-mail" href="mailto:?subject=<?= $event_name ?>, <?= Config::get('concrete.site'); ?>&amp;body=Check out this <?= Config::get('concrete.site'); ?> event!%0D%0A%0D%0A<?= $event_permalink ?>"><i class="fa fa-envelope"></i><span class="share-text">Share</span></a></li>
                        <li><a class="fi-social-twitter" target="_blank" href="https://www.twitter.com/intent/tweet?text=Check+out+this+<?= Config::get('concrete.site'); ?>+event!&url=<?= $event_permalink ?>"><i class="fa fa-twitter"></i><span class="share-text">Tweet</span></a></li>
                        <li><a class="fi-social-google-plus" target="_blank" href="https://plus.google.com/share?url=<?= $event_permalink ?>"><i class="fa fa-google-plus"></i><span class="share-text">Google+</span></a></li>
                        <li><a class="fi-social-facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= $event_permalink ?>"><i class="fa fa-facebook"></i><span class="share-text">Like It</span></a></li>
                    </ul>
                </section>
                <? if ($permalink && $event_link) { ?>
                <section class="buttons">
                    <a href="<?= $event_link_url; ?>" class="info-button" itemprop="url"><?= $event_link_text; ?></a>
                </section>
                <? } ?>
                <? if (!$permalink) { ?>
                <section class="buttons">
                    <a href="<?= $event_permalink; ?>" class="info-button expand-toggle" data-toggle-open="View Details" data-toggle-close="Hide Details" itemprop="url">View Details</a>
                    <? if ($event_link) { ?>
                        <a href="<?= $event_link_url; ?>" class="info-button" itemprop="url"><?= $event_link_text; ?></a>
                    <? } ?>
                </section>
                <? } ?>
            </article>
        </section>
<?
    }
    if ($num_items == 0) {
        $dateObj   = DateTime::createFromFormat('!m', $thisMonth);
        $monthName = $dateObj->format('F');
        echo '<p class="no-events">There are currently no events for '.$monthName.' '.$thisYear.'. Please check back later or try another date by clicking a highlighted date on the calendar.</p>';
    }
    echo '</section>';
?>

<?php if ($showPagination): ?>

    <?php echo $pagination;?>
<?php endif; ?>
