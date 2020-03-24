<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<fieldset>
    <legend><?=t('Filtering')?></legend>

    <div class="form-group">
        <label class="control-label" for="totalToRetrieve"><?= t('Filter by Topic Attribute') ?></label>
        <select class="form-control" name="filterByTopicAttributeKeyID">
            <option value=""><?=t('** None')?></option>
            <? foreach($attributeKeys as $ak) {
                $attributeController = $ak->getController();?>
                <option value="<?=$ak->getAttributeKeyID()?>" <? if ($ak->getAttributeKeyID() == $filterByTopicAttributeKeyID) { ?>selected<? } ?> data-tree-id="<?=$attributeController->getTopicTreeID()?>"><?=$ak->getAttributeKeyDisplayName()?> (<?=$ak->getAttributeKeyHandle()?>)</option>
            <? } ?>
        </select>
        <input type="hidden" name="filterByTopicID" value="<?=$filterByTopicID?>">
        <div id="ccm-block-event-list-topic-tree-wrapper"></div>
    </div>
    <div class="form-group">
        <label class="control-label"><?= t('Featured Posts') ?></label>
        <div class="checkbox">
            <label>
                <input <? if (!is_object($featuredAttribute)) { ?> disabled <? } ?>
                    type="checkbox" name="filterByFeatured" value="1" <? if ($filterByFeatured == 1) { ?> checked <? } ?>
                    style="vertical-align: middle" />
                    <?= t('Display featured blog posts only.') ?>
            </label>
        </div>
        <? if (!is_object($featuredAttribute)) { ?>
            <div class="alert alert-info">
                <?=t('(<strong>Note</strong>: You must create the "is_featured" blog post attribute first.)'); ?>
            </div>
        <? } ?>
    </div>

</fieldset>

<fieldset>
    <legend><?=t('Results')?></legend>
    <div class="form-group">
        <label class="control-label" for="blogEntryListTitle"><?= t('Title') ?></label>
        <?=$form->text('blogEntryListTitle', $blogEntryListTitle)?>
    </div>

    <div class="form-group">
        <label class="control-label" for="totalToRetrieve"><?= t('Total Number of Posts to Retrieve') ?></label>
        <input id="totalToRetrieve" type="text" name="totalToRetrieve" value="<?= $totalToRetrieve ?>" class="form-control">
    </div>

    <div class="form-group">
        <label class="control-label" for="buttonLinkText"><?= t('Button Text') ?></label>
        <?=$form->text('buttonLinkText', $buttonLinkText)?>
    </div>

    <div class="form-group">
        <label class="control-label" for="internalLinkCID"><?= t('Link to Blog') ?></label>
        <?=$pageSelector->selectPage('internalLinkCID', $internalLinkCID)?>
    </div>

</fieldset>

<script type="text/javascript">
    $(function() {
        $('select[name=filterByTopicAttributeKeyID]').on('change', function() {
            $('#ccm-block-event-list-topic-tree').remove();
            var toolsURL = '<?php echo Loader::helper('concrete/urls')->getToolsURL('tree/load'); ?>';
            var chosenTree = $(this).find('option:selected').attr('data-tree-id');
            if (!chosenTree) {
                return;
            }
            $('#ccm-block-event-list-topic-tree-wrapper').append($('<div id=ccm-block-event-list-topic-tree>').ccmtopicstree({
                'treeID': chosenTree,
                'chooseNodeInForm': true,
                <? if ($filterByTopicID) { ?>
                'selectNodesByKey': [<?php echo intval($filterByTopicID) ?>],
                <? } ?>
                'onSelect' : function(select, node) {
                    if (select) {
                        $('input[name=filterByTopicID]').val(node.data.key);
                    } else {
                        $('input[name=filterByTopicID]').val('');
                    }
                }
            }));
        }).trigger('change');
    });
</script>