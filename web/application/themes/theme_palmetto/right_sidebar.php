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
        <div class="sec-nav int-nav">
        <?php
            /* Omit secondary navigation for special offer permalink pages */
            if ( $c->getCollectionTypeHandle() != 'special_offer' ) {

                $a = new GlobalArea('Secondary Nav');
                //this Global Area is only editable within Page Defaults
                if ($c->getMasterCollectionID() != $c->getCollectionID()) {
                    $a->disableControls();
                }
                $a->display();
            }
        ?>
        </div>
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

        <div class="pri-header"><h1><span><? echo $c->getCollectionName(); ?></span></h1></div>
        <?php

        $a = new GlobalArea('Breadcrumb');
        $a->setBlockLimit(1);
        $a->display();

        ?>

        <div class="content-wrapper">
            <div class="content-inner">
                <div class="content-col-right">
                    <div class="mobile-filters<? if($c->isEditMode()) { print '-editmode'; } ?>">
                        <? $a = new Area("Mobile Filters"); $a->display($c); ?>
                    </div>
                    <div class="filters">
                        <? $a = new Area("Filters"); $a->display($c); ?>
                    </div>
                    
                </div>
                <div class="content-col-left">
                    <? $a = new Area("Main");
                    $a->setAreaGridMaximumColumns(12);
                    $a->display($c); ?>
                </div>
                <div class="assist-contact">
                    <? $a = new Area("Sidebar"); $a->display($c); ?>
                </div>
            </div>
        </div>
        <div class="signup-content"></div>
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
</div>

<?php
$this->inc('elements/footer.php');
?>
