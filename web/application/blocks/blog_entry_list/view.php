<?php

defined('C5_EXECUTE') or die("Access Denied.");
    $service = Core::make('helper/date');
?>
<div class="blog-list">
<? if ($blogEntryListTitle) { ?>
    <h2><?=$blogEntryListTitle?></h2>
<? } ?>
    <ul class="multiList">

<?
if ($total = count($entries)) {
    foreach($entries as $entry) { ?>
        <li>
					<?
            $image = $entry->getAttribute('blog_entry_thumbnail');
						if (is_object($image)) {
					?>
            <div>
					<? } else { ?>
						<div class="no-img">
					<? }
                if (is_object($image)) {
                    $im = Core::make('helper/image');
                    $thumb = $im->getThumbnail(
                        $image,
                        175, //$maxWidth
                        175 , // $maxHeight
                        true // crop
                    );
                    $tag = new \HtmlObject\Image();
                    $tag->src($thumb->src)->width($thumb->width)->height($thumb->height);
                ?>
                    <div class="thumb thumbleft"><?php print $tag; ?></div>
                    <? }
                ?>
                <div class="amax-module-header">
                    <h3>
                        <a href="<?=$entry->getCollectionLink()?>"><?=$entry->getCollectionName()?></a>
                    </h3>
                </div>
                <div class="amax-module-body">
                    <p><?=$entry->getCollectionDescription()?></p>
                </div>
            </div>
        </li>

    <?
    } ?>
    </ul>
<? } else { ?>
    <p><?=t('There are no upcoming events.')?></p>
<? } ?>

</div>

<div class="blog-controls">
    <div class="buttons">
        <button type="button" class="b-prev"><em class="alt">prev</em></button>
        <button type="button" class="b-next"><em class="alt">next</em></button>
    </div>
    <? if ($blogPage) { ?>
        <a href="<?=$blogPage->getCollectionLink()?>" class="view-cal white-btn"><?=$buttonLinkText?></a>
    <? } ?>
</div>
