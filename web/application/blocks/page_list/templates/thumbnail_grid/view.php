<?php
defined('C5_EXECUTE') or die("Access Denied.");
$th = Loader::helper('text');
$c = Page::getCurrentPage();

?>

 <?php if ($pageListTitle): ?>
    <h1><?php echo $pageListTitle?></h1>
<?php endif; ?>

<div class="list-block list-large-img">
    <?php
        foreach ($pages as $page):

        $url = $nh->getLinkToCollection($page);
        $target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
        $target = empty($target) ? '_self' : $target;
        $thumbnail = $page->getAttribute('thumbnail');

        $pageTitle = $page->getAttribute('pageListTitle');
        $pageDesc = $page->getAttribute('pageListDescription');

        if ( empty($pageTitle) ) {
            $title = $th->entities($page->getCollectionName());
        } else {
            $title = $pageTitle;
        }

        if ( empty($pageDesc) ) {
            $description = $page->getCollectionDescription();
        } else {
            $description = $pageDesc;
        }

    ?>
    <?php if (is_object($thumbnail)): ?>
        <div class="item-wrap">
            <div class="item-th">
                <?php
                    $img = Core::make('html/image', array($thumbnail));
                    $tag = $img->getTag();
                    print $tag;
                ?>
            </div>
            <div class="item-copy">
                <h3 class="item-title"><?php echo $title; ?></h3>
                <div class="category-description">
                    <?php echo $description ?>

                    <div class="item-link">
                        <a href="<?php echo $url ?>" class="blue-btn">Learn More</a>
                    </div>

                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php endforeach; ?>
</div>


<?php if ($showPagination): ?>
    <?php echo $pagination;?>
<?php endif; ?>

<?php if ( $c->isEditMode() && $controller->isBlockEmpty()): ?>
    <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Page List Block.')?></div>
<?php endif; ?>