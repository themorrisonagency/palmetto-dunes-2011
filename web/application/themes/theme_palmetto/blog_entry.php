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
                <li class=""><a href="/">Home</a><span class="sep">&nbsp;&nbsp;&gt;&nbsp;</span></li>
                <li class=""><a href="/hilton-head-resort-blog">Resort Blog</a></li>
            </ul>
        </div>

        <div class="content-wrapper">
            <div class="content-inner">
                <div class="content-col-right">
                    <? $view->inc('elements/blog_sidebar.php')?>
                </div>
                <div class="content-col-left">
                    <div class="blog-listing">

                        <? $view->inc('elements/blog_body.php', array('entry' => $c))?>

                        <div class="conversation">
                            <? $a = new Area('Conversation'); $a->display($c); ?>
                        </div>

                        <div style="height: 80px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->inc('elements/footer.php');
?>
