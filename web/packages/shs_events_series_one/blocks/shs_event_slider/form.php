<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<fieldset>
    <legend><?=t('Data Source')?></legend>

    <div class="form-group">
        <?= $form->label('caID', t('Calendar')) ?>
        <?php echo $form->select('caID', $calendars, $caID); ?>
    </div>

</fieldset>

<fieldset>
    <legend><?=t('Filtering')?></legend>

    <div class="form-group">
        <label class="control-label" for="totalToRetrieve"><?= t('Filter by Topic Attribute') ?></label>
        <select class="form-control" name="filterByTopicAttributeKeyID">
            <option value=""><?=t('** None')?></option>
            <? foreach($attributeKeys as $ak) {
                $attributeController = $ak->getController();?>
                <option value="<?=$ak->getAttributeKeyID()?>" <? if ($ak->getAttributeKeyID() == $filterByTopicAttributeKeyID) { ?>selected<? } ?> data-tree-id="<?=$attributeController->getTopicTreeID()?>"><?=$ak->getAttributeKeyDisplayName()?></option>
            <? } ?>
        </select>
        <input type="hidden" name="filterByTopicID" value="<?=$filterByTopicID?>">
        <div id="ccm-block-event-list-topic-tree-wrapper"></div>
    </div>

    <div class="form-group">
        <label class="control-label"><?= t('Featured Events') ?></label>
        <div class="checkbox">
            <label>
                <input <? if (!is_object($featuredAttribute)) { ?> disabled <? } ?>
                    type="checkbox" name="filterByFeatured" value="1" <? if ($filterByFeatured == 1) { ?> checked <? } ?>
                    style="vertical-align: middle" />
                    <?= t('Display featured events only.') ?>
            </label>
        </div>
        <? if (!is_object($featuredAttribute)) { ?>
            <div class="alert alert-info">
                <?=t('(<strong>Note</strong>: You must create the "is_featured" event attribute first.)'); ?>
            </div>
        <? } ?>
    </div>

</fieldset>

<fieldset>
    <legend><?=t('Results')?></legend>
    <div class="form-group">
        <label class="control-label" for="eventListTitle"><?= t('Title') ?></label>
        <?=$form->text('eventListTitle', $eventListTitle)?>
    </div>

    <div class="form-group">
        <label class="control-label" for="totalToRetrieve"><?= t('Total Number of Events to Retrieve') ?></label>
        <input id="totalToRetrieve" type="text" name="totalToRetrieve" value="<?= $totalToRetrieve ?>" class="form-control">
    </div>

    <div class="form-group">
        <label class="control-label" for="totalPerPage"><?= t('Events to Display Per Page') ?></label>
        <input id="totalPerPage" type="text" name="totalPerPage" value="<?= $totalPerPage ?>" class="form-control">
    </div>

    <div class="form-group">
        <label class="control-label" for="buttonLinkText"><?= t('Button Text') ?></label>
        <?=$form->text('buttonLinkText', $buttonLinkText)?>
    </div>

    <div class="form-group">
        <label class="control-label" for="buttonStyle"><?= t('Button Style') ?></label>
        <select name="buttonStyle" id="buttonStyle" class="form-control">
            <option value="1"<? if('1' == $buttonStyle) { print ' selected="selected"'; } ?>>Primary</option>
            <option value="2"<? if('2' == $buttonStyle) { print ' selected="selected"'; } ?>>Secondary</option>
        </select>
    </div>

    <div class="form-group">
        <label class="control-label" for="internalLinkCID"><?= t('Link to Calendar Page') ?></label>
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