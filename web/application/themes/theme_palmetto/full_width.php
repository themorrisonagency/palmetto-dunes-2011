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


<div class="page-wrapper">
    <div class="content-wrapper">
        <div class="content-inner">

            <?php

            $a = new Area('Main');
            $a->setAreaGridMaximumColumns(12);
            $a->display($c);

            ?>
        </div>
    </div>
</div>

<?php
$this->inc('elements/footer.php');
?>
