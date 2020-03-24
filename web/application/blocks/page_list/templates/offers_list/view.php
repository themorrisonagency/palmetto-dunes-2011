<?php
defined('C5_EXECUTE') or die("Access Denied.");
$th = Loader::helper('text');
$nh = Loader::helper("navigation");
$c = Page::getCurrentPage();
$cID = $c->getCollectionID();
$allOffersPage = Page::getByID(162);
?>

<h2>Filter By</h2>
<ul class="offer-filter">
	<li class="offers-all<? if ($cID == 162) { print ' active'; } ?>">
		<a href="<?= $nh->getLinkToCollection($allOffersPage); ?>"><?= $allOffersPage->getAttribute('pageListTitle'); ?></a>
	</li>
<?
	foreach ($pages as $page):
		$url = $nh->getLinkToCollection($page);
		$pageTitle = $page->getAttribute('pageListTitle');
		if ($pageTitle == '') {
			$pageTitle = $page->getCollectionName();
		}
		$cat = strtok(strtolower($pageTitle),' '); ?>
		<li class="offers-<? echo $cat; if ($cID == $page->getCollectionID()) { print ' active'; } ?>"><a href="<?= $url; ?>"><?= $pageTitle; ?></a></li>
<?
	endforeach; ?>
</ul>


<?php if ( $c->isEditMode() && $controller->isBlockEmpty()): ?>
    <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Page List Block.')?></div>
<?php endif; ?>