<? defined('C5_EXECUTE') or die("Access Denied.");
$navigationTypeText = ($navigationType == 0) ? 'arrows' : 'pages';
$c = Page::getCurrentPage();
if ($c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item masthead" style="width: <? echo $width; ?>; height: <? echo $height; ?>">
        <div style="padding: 40px 0px 40px 0px"><? echo t('Masthead disabled in edit mode.')?></div>
    </div>
<?  } else { ?>


<div class="masthead secondary-masthead">
    <?php if(count($rows) > 0) { ?>
        <?php foreach($rows as $row) { ?>
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
        <?php } ?>

    <?php } else { ?>
        <div class="ccm-image-slider-placeholder masthead secondary-masthead">
            <p><?php echo t('No Mastheads Entered.'); ?></p>
        </div>
    <?php } ?>
</div>
<?php } ?>