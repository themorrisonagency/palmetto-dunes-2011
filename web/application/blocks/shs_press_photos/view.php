<?php  defined('C5_EXECUTE') or die("Access Denied.");
$pressphoto_count = count($results);
?>

<div class="press-gallery">
    <? if (count($filterTopics) > 0) { ?>
    <div class="press-gallery-picker">
        <label>Please select a category from the list:</label>
        <select>
            <?  foreach ($filterTopics as $topic): ?>
                    <option value="<?= $view->controller->getTopicLink($topic); ?>" <? if($active_topic == $topic->getTreeNodeID()) { echo 'selected'; } ?>>
						<?php if ($topic->getTreeNodeDisplayName() == 'Arthur Hills Golf Course') { echo '&nbsp;&nbsp;&nbsp;&nbsp;'; } ?>
						<?php if ($topic->getTreeNodeDisplayName() == 'George Fazio Golf Course') { echo '&nbsp;&nbsp;&nbsp;&nbsp;'; } ?>
						<?php if ($topic->getTreeNodeDisplayName() == 'Robert Trent Jones Oceanfront Golf Course') { echo '&nbsp;&nbsp;&nbsp;&nbsp;'; } ?>
                        <?= $topic->getTreeNodeDisplayName(); ?>
                    </option>
            <?   endforeach; ?>
        </select>
    </div>
    <? } ?>
    <div class="advert-images">

        <?
            if (count($images) > 0) {
                foreach ($images as $img) { ?>

                <div class="advertSetHoriz">
                    <a href="<? echo $img['fullFilePath']; ?>" class="fancybox-trigger"><img src="<?= $img['thumbnail']->src; ?>" height="126" alt="<?php echo $img['title']; ?>" data-topic="<?= $img['topic']; ?>" data-active-topic="<?= $img['active_topic']; ?>" /></a>
                    <div class="image-text">
                        <a href="<? echo $img['fullFilePath']; ?>" class="fancybox-trigger"><?php echo $img['title']; ?></a>
                    </div>
                </div>

        <?      }
            } ?>

    </div>
</div>