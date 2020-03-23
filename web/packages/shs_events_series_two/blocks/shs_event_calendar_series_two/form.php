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

</fieldset>

<fieldset>
    <legend><?= t('Settings') ?></legend>

    <div class="form-group">
        <?php  echo $form->label("layoutType", t("Default Layout before Toggle Cookie")); ?>
        <?php  echo (isset($btFieldsRequired) && in_array('layoutType', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null); ?>
        <?php  $options = array(
            '0' => 'Grid',
            '1' => 'Calendar'
        ); ?>
        <?php  echo $form->select("layoutType", $options, $layoutType); ?>
    </div>
</fieldset>

<!--
<fieldset>
    <legend><?=t('Pagination')?></legend>

    <div class="checkbox"><label>
        <?php echo $form->checkbox('paginate', 1, $paginate);?>
        <?php echo t('Display pagination interface if more items are available than are displayed.')?>
    </label></div>

    <div class="form-group">
        <label class="control-label" for="num"><?= t('Max Number of Items to Display') ?></label>
        <?php echo $form->number('num', $num);?>
    </div>
</fieldset>
-->

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
