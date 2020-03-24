<?php
/**
 * Require all palmetto css assets
 * These assets are defined in /application/config/app.php
 */
$view = \View::getInstance();
$view->requireAsset('css', 'palmetto.layout');


$this->inc('elements/header_catering.php');
?>

<div class="page-wrapper">
    <div class="masthead-wrapper">
        <?
            $a = new Area('Masthead');
            $a->display($c);
        ?>
    </div>

    <?
    $a = new GlobalArea('Booking Console');
    if ($c->getAttribute('console_display') != 'No Console') {
        $a->display();
    }
    ?>
	
    <div class="content-wrapper">
        <div class="content-inner">
            <?php
                if ( $c->getCollectionTypeName() == 'Press Release') {
            ?>
                    <a href="/hilton-head-island-resort-press/hilton-head-resort-press-releases/" class="back-to-link">Back to Press Releases</a>
            <?php
                }
            ?>

            <?php

            $a = new Area('Main');
            $a->setAreaGridMaximumColumns(12);
            $a->display($c);

            ?>
        </div>
        <div class="secondary-content">
        <?php
        $a = new Area('Secondary Content');
        $a->display($c);
        ?>
        </div>
    </div>

    <?php
    $a = new Area('Signup Bar');
    if (($a->getTotalBlocksInArea($c) > 0) || ($c->isEditMode())) {
        print '<div class="signup-content">';
        $a->display($c);
        print '</div>';
    }
    ?>

    <div class="supplemental-content">
        <?php

        $a = new Area('Supplemental Content');
        $a->display($c);

        ?>
    </div>
    <div class="cross-promo-content">
        <div class="cross-promo-inner">
        <?php

        $a = new Area('Cross-Promo Content');
        $a->setAreaGridMaximumColumns(12);
        $a->display($c);

        ?>
        </div>
    </div>
</div>


<?php
$this->inc('elements/footer_catering.php');
?>