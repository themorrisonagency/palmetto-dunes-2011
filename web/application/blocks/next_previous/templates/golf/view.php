<?php defined('C5_EXECUTE') or die("Access Denied.");
$nh = Loader::helper('navigation');
$previousLinkURL = is_object($previousCollection) ? $nh->getLinkToCollection($previousCollection) : '';
$parentLinkURL = is_object($parentCollection) ? $nh->getLinkToCollection($parentCollection) : '';
$nextLinkURL = is_object($nextCollection) ? $nh->getLinkToCollection($nextCollection) : '';
$previousLinkText = is_object($previousCollection) ? $previousCollection->getCollectionName() : '';
$nextLinkText = is_object($nextCollection) ? $nextCollection->getCollectionName() : '';
?>

<? if ($previousLinkURL || $nextLinkURL || $parentLinkText): ?>

<div class="golf-course-next">
    <?php if ($previousLinkText):
	   echo $previousLinkURL ? '<a class="course-left" href="' . $previousLinkURL . '">&lt; <b>' . $previousLabel . '</b>: ' . $previousLinkText . '</a>' : '';
    endif; ?>

    <?php if ($nextLinkText):
        echo $nextLinkURL ? '<a class="course-right" href="' . $nextLinkURL . '"> <b>'. $nextLabel . '</b>: ' . $nextLinkText . ' &gt;</a>' : '';
    endif; ?>
</div>

<? endif; ?>
