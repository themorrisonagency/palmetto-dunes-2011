<?php
/**
 * Require all palmetto css assets
 * These assets are defined in /application/config/app.php
 */
$view = \View::getInstance();
$view->requireAsset('css', 'palmetto.layout');


$this->inc('elements/header.php');
?>

<div id="wrapper">
    <div class="page-wrapper">
        <?
        $a = new GlobalArea('Booking Console');
        $a->setBlockLimit(1);
        if ($c->getAttribute('console_display') != 'No Console') {
            $a->display();
        }
        ?>
        <div class="pri-header"><h1><span>Resort Blog</span></h1></div>
        <div class="breadcrumb">
            <ul>
                <li><a href="/">Home</a><span class="sep">&nbsp;&nbsp;&gt;&nbsp;</span></li>
                <? if ($controller->archives) { ?>
                    <li class=""><a href="/hilton-head-resort-blog">Resort Blog</a><span class="sep">&nbsp;&nbsp;&gt;&nbsp;</span></li>
                    <li>Archives</li>
                <? } else { ?>
                <li>Resort Blog</li>
                <? } ?>
            </ul>
        </div>

        <div class="content-wrapper">
            <div class="content-inner">
                <div class="content-col-right">
                    <? $view->inc('elements/blog_sidebar.php')?>
                </div>
                <div class="content-col-left">
                    <div class="blog-listing">
                        <ul class="multiList">
                    <? foreach($entries as $entry) {
                        print '<li>';
                        $totalComments = 0;
                        $entryController = $entry->getPageController();
                        if ($entryController instanceof
                            \Concrete\Package\PalmettoDunesBlog\Controller\PageType\BlogEntry) {
                            $totalComments = $entryController->getTotalComments();
                        }
                        ?>

                        <? $view->inc('elements/blog_body.php', array('entry' => $entry))?>

                        <p class="post-info"><a href="<?=$entry->getCollectionLink()?>"><?=t2('1 Comment', '%s Comments', $totalComments)?></a></p>
                        </li>
                        <div class="clear"></div>
                    <? } ?>

                    <? if (count($entries) == 0) { ?>
                        <li><p>No entries found.</p></li>
                    <? } ?>
                    </div>
                    </ul>
                    <? if ($pagination->getTotalPages() > 1) { ?>
                        <? echo $pagination->renderDefaultView();?>
                    <? } ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->inc('elements/footer.php');
?>
