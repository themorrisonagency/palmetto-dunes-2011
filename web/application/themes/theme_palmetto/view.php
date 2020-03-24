<?php
/**
 * Require all palmetto css assets
 * These assets are defined in /application/config/app.php
 */
$view = \View::getInstance();
$view->requireAsset('css', 'palmetto.layout');
$page = Page::getCurrentPage();

// CDE sync logic
if ($c->getCollectionName() == 'CDE Header') {
$this->inc('elements/shared_cde_header.php');
} elseif ($c->getCollectionName() == 'CDE Footer') {
$this->inc('elements/shared_cde_footer.php');
} else {
    $this->inc('elements/header.php');
?>

    <div class="masthead-wrapper" style="margin-top: 107px;">
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

<div class="page-wrapper">
    <div class="content-wrapper">
        <div class="content-inner">
            <?php print $innerContent; ?>
        </div>
    </div>
	
    <?php

    $a = new Area('Signup Bar');
    $a->display($c);

    ?>
	
</div>

<?php
$this->inc('elements/footer.php');
} // end if/elseif/else CDE sync logic
?>
