<?php defined('C5_EXECUTE') or die("Access Denied.");
/** @var \File $file */
$c = Page::getCurrentPage();
if($c instanceof Page) {
    $cID = $c->getCollectionID();
}
if ($linkURL != '' && !empty($linkedFileID)) {
    $linkURL = View::url('/download_file', $controller->getFileID(),$cID);
}
?>
<div class="cross-promo-content">
    <div class="cross-promo-inner">
        <div id="amax-templatearea-5" class="amax-templatearea">
            <div id="amax-crosspromo-248" class="amax-block amax-crosspromo single-push">
                <div class="amax-module-header"></div>
                <div class="amax-module-body">
                    <div class="single-push-wrapper">                        
                        <div class="single-push-content">
                            <div class="single-push-img">
                                <?php
                                if ($file instanceof \File) {
                                    ?>
                                    <img src="<?= $file->getRelativePathFromID($file->getFileID()) ?>" alt="Boats at the Marina" width="466">
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="single-push-description">
								<div class="single-push-title"><?= $title ?></div>
                                <?= $paragraph ?>
								<?php if ($linkURL != ''){ ?>
                                <div>
                                    <a href="<?= $linkURL ?>" class="green-btn pdf-link">
                                        <?= $buttonText ?: t('Learn More') ?>
                                    </a>
                                </div>
								<?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
