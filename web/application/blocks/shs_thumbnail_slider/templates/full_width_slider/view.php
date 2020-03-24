<? defined('C5_EXECUTE') or die("Access Denied.");
$navigationTypeText = ($navigationType == 0) ? 'arrows' : 'pages';
$c = Page::getCurrentPage();
if ($c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item masthead" style="width: <? echo $width; ?>; height: <? echo $height; ?>">
        <div style="padding: 40px 0px 40px 0px"><? echo t('Slides disabled in edit mode.')?></div>
    </div>
<?  } else { ?>

    <div class="overview-masthead">

        <?php if(count($rows) > 0) { ?>
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
                        <div class="ccm-image-slider-text cycle-caption">
                            <div class="cycle-wrap">
                                <?php if($row['title']) { ?>
                                    <div class="hp-title"><?php echo $row['title'] ?></div>
                                <?php } ?>
                                <div class="hp-description">
                                    <?php echo $row['description'] ?>
                                </div>

                                <div class="hp-link">
                                    <?php if ( $row['internalLinkCID'] == '0' ) {
                                        $target="_blank";
                                    } else {
                                        $target="_self";
                                    }
                                    ?>

                                    <?php if($row['linkURL']) { ?>
                                        <a href="<?php echo $row['linkURL'] ?>" class="orange-btn" target="<?php echo $target; ?>"><?php echo $row['buttonText'] ?></a>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <ul id="nav">
                <?php foreach($rows as $row) { ?>
                    <li><a href="#"><span><?php echo $row['thumbnailCaption'] ?></span>
                        <?php
                        $f = File::getByID($row['tID'])
                        ?>
                        <?php if(is_object($f)) {
                            $tag = Core::make('html/image', array($f, false))->getTag();
                            if($row['thumbnailCaption']) {
                                $tag->alt($row['thumbnailCaption']);
                            }else{
                                $tag->alt("slide");
                            }
                            print $tag; ?>
                        <?php } ?>
                    </a></li>
                <?php } ?>
            </ul>
            <div class="controls">
                <a href="#" id="prev"><em class="alt">Prev</em></a>
                <a href="#" id="next"><em class="alt">Next</em></a>
            </div>
            <div class="mobile-pager"></div>
        <?php } else { ?>
            <div class="ccm-image-slider-placeholder">
                <p><?php echo t('No Slides Entered.'); ?></p>
            </div>
        <?php } ?>
    </div>
<?php } ?>
