<?php
/**
 * Require all palmetto css assets
 * These assets are defined in /application/config/app.php
 */
$view = \View::getInstance();
$view->requireAsset('css', 'palmetto.layout');


$this->inc('elements/header.php');
?>

    <div class="masthead-wrapper">
        <?
            $a = new Area('Masthead');
            $a->display($c);
        ?>
    </div>

<?
    $a = new GlobalArea('Booking Console');
    $a->setBlockLimit(1);
    if ($c->getAttribute('console_display') != 'No Console') {
        $a->display();
    }
?>

<?
    $a = new Area('Push Carousel');
    $a->display($c);
?>
<div class="event-blog">
    <div class="eb-wrapper">
        <div class="eb-col eb-left">
            <?php

            $a = new Area('Left Promo');
            $a->display($c);

            ?>
        </div>
        <div class="eb-col eb-right">
            <?php

            $a = new Area('Right Promo');
            $a->display($c);

            ?>
        </div>
    </div>
</div>
<div class="content">
    <div class="content-inner">
        <?php

        $a = new Area('Main');
        $a->display($c);

        ?>
    </div>
</div>

<?php
$this->inc('elements/footer.php');
?>
