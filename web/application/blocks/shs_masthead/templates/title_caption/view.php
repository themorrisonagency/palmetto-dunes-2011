<? defined('C5_EXECUTE') or die("Access Denied.");
$navigationTypeText = ($navigationType == 0) ? 'arrows' : 'pages';
$c = Page::getCurrentPage();
if ($c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item masthead" style="width: <? echo $width; ?>; height: <? echo $height; ?>">
        <div style="padding: 40px 0px 40px 0px"><? echo t('Masthead disabled in edit mode.')?></div>
    </div>
<?  } else { ?>

    <div class="masthead masthead-tc">
        <div class="golf-course-carousel">
            <?php if(count($rows) > 0) {
                $i = 0;
            ?>
                <div class="home-cycle" id="ccm-image-slider-<?php echo $bID ?>">
                    <?php foreach($rows as $row) { ?>
                        <div class="slide">
                            <?php
                                $f = File::getByID($row['fID'])
                            ?>
                            <div class="slide-img">
                                <?php if(is_object($f)) {
                                    $tag = Core::make('html/image', array($f, false))->getTag();
                                    if($row['title']) {
                                        $tag->alt($row['title']);
                                    }else{
                                        $tag->alt("slide");
                                    }
                                    print $tag; ?>
                                <?php } ?>
                            </div>
                            <div class="cycle-caption caption">
                                <div class="cycle-wrap">
                                    <?php if($row['title']) { ?>
                                        <div class="hp-title"><?php echo $row['title'] ?></div>
                                    <?php } ?>
                                    <div class="hp-description">
                                        <?php echo $row['description'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        if (++$i == 1) break;
                    }

                    ?>
                </div>
            <?php } else { ?>
                <div class="ccm-image-slider-placeholder">
                    <p><?php echo t('No Mastheads Entered.'); ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>
