<?
defined('C5_EXECUTE') or die("Access Denied.");
?>

<div class="ccm-ui">
	<form data-form="special-offer" action="<?=$controller->action('publish')?>">
		<? Loader::helper('concrete/composer')->display($pagetype, $c); ?>
		<div class="dialog-buttons">
			<button type="button" data-special-offer-action="cancel" class="btn btn-default pull-left"><?=t('Cancel')?></button>
			<? if ($cp->canEditPageContents()) { ?>
				<button type="button" data-special-offer-action="submit" value="save" class="btn btn-success pull-right"><?=t('Publish')?></button>
			<? } ?>
			<? if ($cp->canDeletePage()) { ?>
				<button type="button" value="delete" data-special-offer-action="delete" class="btn btn-danger pull-right"><?=t('Delete')?></button>
			<? } ?>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(function() {
		$('form[data-form=special-offer] button[data-special-offer-action=cancel]').on('click', function() {
			jQuery.fn.dialog.closeTop();
		});

		$('form[data-form=special-offer]').concreteAjaxForm({
			'success': function() {
				window.location.reload();
			}
		});

		$('form[data-form=special-offer] button[data-special-offer-action=submit]').on('click', function() {
			$('[data-form=special-offer]').submit();
		});

		$('button[data-special-offer-action=delete]').on('click', function() {
			jQuery.fn.dialog.open({
				title: '<?=t('Delete Special Offer')?>',
				href: '<?= URL::to('/ccm/system/dialogs/page/delete_from_sitemap') ?>?cID=<?= $c->getCollectionID() ?>',
				modal: true,
				width: 400,
				height: 250
			});
		});

		ConcreteEvent.unsubscribe('SitemapDeleteRequestComplete');
		ConcreteEvent.subscribe('SitemapDeleteRequestComplete', function(e) {
			window.location.reload();
		});

	});
</script>