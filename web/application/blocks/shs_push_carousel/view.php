<? defined('C5_EXECUTE') or die("Access Denied.");
$c = Page::getCurrentPage();
?>
    
<div class="push-carousel<? if ($c->isEditMode()) { print ' disabled'; } ?>">
    <h2>Explore</h2>

    <?php if(count($rows) > 0) { ?>
        <ul class="home-push-items" id="ccm-image-slider-<?php echo $bID ?>">
            <?php foreach($rows as $row) { ?>
                <li class="home-push-item">
                    <div class="push-wrapper push-<?php echo $row['pushClass'] ?>">
                        <div class="push-inset">
                            <a href="<?php echo $row['linkURL'] ?>" <?php if($row['internalLinkCID'] == 0) echo 'target="_blank"'?>>
                                <?php
                                    $f = File::getByID($row['fID'])
                                ?>
                                <?php if(is_object($f)) {
                                    $tag = Core::make('html/image', array($f, false))->getTag();
                                    if($row['title']) {
                                        $tag->alt($row['title']);
                                    }else{
                                        $tag->alt("slide");
                                    }
                                    print $tag; ?>
                                <?php } ?>
                                <div class="overlay"></div>
                            </a>
                        </div>
                        <div class="push-title">
                            <a href="<?php echo $row['linkURL'] ?>">
                                <?php echo $row['title'] ?>
                            </a>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>
    <div class="ccm-image-slider-placeholder">
        <p><?php echo t('No Slides Entered.'); ?></p>
    </div>
    <?php } ?>
</div>

