<div class="blog-nav">
    <ul>
        <li><a class="" href="<?=URL::to('/resort/hilton-head-sc-resorts')?>">About</a></li>
        <li><a class="" href="<?=URL::page($blog, 'archives')?>">Archives</a>
        <?
        $a = new GlobalArea('Blog Archive List');
        if ($controller->archives || $c->isEditMode()) {
            $a->display();
        }
        ?>
        </li>
        <li><a class="" href="<?=URL::to('/resort/hilton-head-vacation-packages')?>">Special Offers</a></li>
    </ul>
</div>


<div class="sidebar-widget blog-search"><div class="amax-conform"><form method="get" action="<?=URL::page($blog, 'search')?>">
    <fieldset class="fieldGroup"><div class="formfield"><input placeholder="SEARCH" type="text" value="<?=h($_GET['search'])?>" name="search" class="text"></div></fieldset>
    <div class="submitRow"><input type="image" src="/application/themes/theme_palmetto/images/white-magnify-icon.svg" name="submit" class="smallSubmitButton blue-icon-btn blog-submit"></div>
</form></div></div>


<div class="sidebar-widget twitter-follow"><iframe id="twitter-widget-11" scrolling="no" frameborder="0" allowtransparency="true" src="https://platform.twitter.com/widgets/follow_button.b8521baa6750e75c2cbc4369801f822e.en.html#_=1422573651369&amp;dnt=false&amp;id=twitter-widget-11&amp;lang=en&amp;screen_name=PalmettoDunesSC&amp;show_count=false&amp;show_screen_name=true&amp;size=m" class="twitter-follow-button twitter-follow-button" title="Twitter Follow Button" data-twttr-rendered="true" style="width: 167px; height: 20px;"></iframe></div>

<!--<div class="sidebar-widget blog-signup">
    <?
    //$a = new GlobalArea('Sidebar Subscribe');
    //$a->display();
    ?>
</div>-->

<div class="sidebar-widget recent-blogposts">
    <h3>Recent Posts</h3>
    <? if (count($recentPosts)) { ?>
    <div><ul>
        <?  $count = 0;
            foreach($recentPosts as $rp) {
                if ($count < 5) {
        ?>
                    <li><a href="<?=$rp->getCollectionLink()?>"><?=$rp->getCollectionName()?></a></li>
        <?          $count++;
                } else {
                    break;
                }
            }?>
    </ul></div>
    <? } else { ?>
        None.
    <? } ?>
</div>

<div class="sidebar-widget category-listing tags-listing">
    <h3>Categories</h3>
    <? if (count($categories)) { ?>
		<div id="mobile-blog-category-selector">
			<select>
				<option value="">Select</option>
				<? foreach($categories as $topic) {
						$nodeName = $topic->getTreeNodeName();
						$nodeName = strtolower($nodeName); // convert to lowercase
						$nodeName = Core::make('helper/text')->encodePath($nodeName);
						?>		
					<option value="<?=URL::page($blog, 'category', $nodeName)?>"><?=$topic->getTreeNodeDisplayName()?></option>				
				<?php } ?>
			</select>
		</div>
        <div id="desktop-blog-categories"><ul>
                <? foreach($categories as $topic) {
                    $nodeName = $topic->getTreeNodeName();
                    $nodeName = strtolower($nodeName); // convert to lowercase
                    $nodeName = Core::make('helper/text')->encodePath($nodeName);
                    ?>
                    <li><a href="<?=URL::page($blog, 'category', $nodeName)?>"><?=$topic->getTreeNodeDisplayName()?></a>
                <? } ?>
            </ul></div>
    <? } else { ?>
        None.
    <? } ?>
</div>


<div class="sidebar-widget rss-feed-link">
    <p class="feedLink"><a href="<?=URL::page($blog, 'rss')?>"><em class="alt">RSS Feed</em></a></p>
</div>