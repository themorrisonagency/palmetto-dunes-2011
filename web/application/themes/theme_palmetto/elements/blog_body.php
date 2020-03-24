
<div class="publishedDate"><?=t('Posted on %s', date('jS M Y, g:i a', strtotime($entry->getCollectionDatePublic())))?></div>
<h2><a href="<?=$entry->getCollectionLink()?>"><?=$entry->getCollectionName()?></a></h2>
<div class="share">
    <ul>
        <li class="share-email">
            <a href="mailto:?subject=<?=$entry->getCollectionName()?>&amp;body=<?=urlencode($entry->getCollectionLink(true))?>" class="track" name="share:email:<?=urlencode($entry->getCollectionLink(true))?>">
                <em class="alt">Share</em>
            </a>
        </li>
        <li class="share-google">
            <div class="g-plusone" data-size="medium" data-action="share" data-href="<?=urlencode($entry->getCollectionLink(true))?>"></div>
        </li>
        <li class="share-twitter">
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?= h($entry->getCollectionLink(true)) ?>" data-text="Check out this post: <?= h($entry->getCollectionName()) ?>">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        </li>
        <li class="share-facebook">
            <iframe src="//www.facebook.com/plugins/like.php?href=<?=urlencode($entry->getCollectionLink(true))?>&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=511361785582687" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>
        </li>
    </ul>
</div>
<div class="bodyText">
    <? $a = new Area('Main'); $a->display($entry); ?>
</div>
<? $tags = $entry->getAttribute('blog_entry_tags');
if ($tags instanceof Concrete\Attribute\Select\OptionList && $tags->count()) { ?>
<ul class="blog-tagged">
    <li>Tags:&nbsp;</li>
    <?
    for ($i = 0; $i < $tags->count(); $i++) {
        $tag = $tags->get($i);
        $value = strtolower($tag->getSelectAttributeOptionValue());
        $value = Core::make('helper/text')->encodePath($value);
        ?>
        <li>
            <a href="<?=URL::page($blog, 'tag', $value)?>"><?=$tag->getSelectAttributeOptionDisplayValue()?></a><?
            if (($i + 1) < $tags->count()) { ?>,&nbsp;<? } ?>
        </li>
    <? } ?>
</ul>
<? } ?>