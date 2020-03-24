<?php
defined('C5_EXECUTE') or die("Access Denied.");
$th = Loader::helper('text');
$nh = Loader::helper("navigation");
$c = Page::getCurrentPage();
$cID = $c->getCollectionID();
$allOffersPage = Page::getByID(162);
?>

<h2>Filter By</h2>
<select class="offer-filter" name="tag">
	<option class="offers-all" value="<?= $nh->getLinkToCollection($allOffersPage); ?>"<? if ($cID == 162) { print ' selected'; } ?>>
		<?= $allOffersPage->getAttribute('pageListTitle'); ?>
	</option>
<?
	foreach ($pages as $page):
		$url = $nh->getLinkToCollection($page);
		$pageTitle = $page->getAttribute('pageListTitle');
		if ($pageTitle == '') {
			$pageTitle = $page->getCollectionName();
		}
		$cat = strtok(strtolower($pageTitle),' '); ?>
		<option class="offers-<?= $cat; ?>" value="<?= $url; ?>"<? if ($cID == $page->getCollectionID()) { print ' selected'; } ?>>
			<?= $pageTitle; ?>
		</option>
<?
	endforeach; ?>
</select>

<?php if ( $c->isEditMode() && $controller->isBlockEmpty()): ?>
    <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Page List Block.')?></div>
<?php endif; ?>