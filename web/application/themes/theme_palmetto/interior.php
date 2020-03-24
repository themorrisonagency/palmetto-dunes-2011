<?php
/**
 * Require all palmetto css assets
 * These assets are defined in /application/config/app.php
 */
$view = \View::getInstance();
$view->requireAsset('css', 'palmetto.layout');


$this->inc('elements/header.php');
?>

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


	<?php
		$a = new Area('Full Width Blue Bar Header');
		if (($a->getTotalBlocksInArea($c) > 0) || ($c->isEditMode())) {
	?>
		<div class="clear" style="margin-top: 22px;"></div>
			<div class="quote-content">
				<div class="quote-inner">
					<div class="quote-wrap">
						<div class="quote-header">
							<?php $a->display($c); ?>
						</div>
					</div>
				</div>
			</div>
		<div class="clear"></div>
	<?php } ?>


    <?php
    // Display custom page title and shortened breadcrumb for Press Release pages
    if ($c->getCollectionTypeHandle() == 'press_release') {
    ?>
        <div class="pri-header"><h1><span>Press Releases</span></h1></div>
        <div class="breadcrumb" role="navigation" aria-label="breadcrumb"><ul><li><a href="/" target="_self">Home</a><span class="sep">&nbsp;&nbsp;&gt;&nbsp;</span></li><li><a href="<?=URL::to('/hilton-head-island-resort-press')?>">Press Center</a></li></ul></div>
    <?php
    } else {
    ?>
        <div class="pri-header"><h1><span><? echo $c->getCollectionName(); ?></span></h1></div>
    <?php

    $a = new GlobalArea('Breadcrumb');
    $a->setBlockLimit(1);
    $a->display();

    }
    ?>
    <div class="content-wrapper">
        <div class="content-inner">
            <?php
                if ( $c->getCollectionTypeName() == 'Press Release') {
            ?>      
                    <h2 itemprop="name"><?= $c->getCollectionName(); ?></h2>
                    <a href="/hilton-head-resort-press-releases" class="back-to-link">Back to Press Releases</a>
            <?php
                }
            ?>
            
            <?php

            $a = new Area('Main');
            $a->setAreaGridMaximumColumns(12);
            $a->display($c);

            ?>
            <div class="clearfix"></div>
            <div class="columns">
                <div class="recent-reviews">
                    <div class="small-12 medium-4">
                        <?php

                        $a = new Area('Review Block One');
                        $a->setAreaGridMaximumColumns(4);
                        $a->display($c);

                        ?>
                    </div>
                    <div class="small-12 medium-4">
                        <?php

                        $a = new Area('Review Block Two');
                        $a->setAreaGridMaximumColumns(12);
                        $a->display($c);

                        ?>
                    </div>
                    <div class="small-12 medium-4">
                        <?php

                        $a = new Area('Review Block Three');
                        $a->setAreaGridMaximumColumns(12);
                        $a->display($c);

                        ?>
                    </div>
                </div>
            </div>
            <div class="clearfix">&nbsp;</div>
            <?php

            $a = new Area('Main Two');
            $a->setAreaGridMaximumColumns(12);
            $a->display($c);

            ?>
        </div>
    </div>

    <?php
    $a = new Area('Quote Bar');
    $a->display($c);
    ?>


	<?php
		$a = new Area('Full Width Blue Bar');
		if (($a->getTotalBlocksInArea($c) > 0) || ($c->isEditMode())) {
	?>
	<div class="clear"></div>
	<div class="quote-content">
		<div class="quote-inner">
			<div class="quote-wrap">
				<?php $a->display($c); ?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<?php } ?>

    <div class="secondary-content">
        <?php
        $a = new Area('Secondary Content');
        $a->setAreaGridMaximumColumns(12);
        $a->display($c);
        ?>
    </div>

    <div class="signup-content">
        <?php
        $a = new Area('Signup Bar');
        $a->display($c);
        ?>
    </div>

    <div class="supplemental-content">
        <div class="supplemental-content-inner">
        <?php

        $a = new Area('Supplemental Content');
        $a->setAreaGridMaximumColumns(12);
        $a->display($c);

        ?>
        </div>
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
$this->inc('elements/footer.php');
?>
