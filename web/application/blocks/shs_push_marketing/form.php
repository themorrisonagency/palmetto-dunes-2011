<?php defined('C5_EXECUTE') or die("Access Denied.");
$asset_lib = \Core::make('helper/concrete/asset_library');

$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
$bg = null;
if ($controller->getFileID() > 0) {
    $bf = $controller->getFileObject();
}
?>
<div class="ccm-image-feature-edit">
<fieldset>
    <legend><?= t('Image') ?></legend>
    <div class="form-group ccm-block-feature-select-image">
        <?php
        $file = null;
        if ($fID) {
            $file = File::getByID($fID);
        }
        ?>
        <?= $asset_lib->image('ccm_block_image_feature_fID', 'fID', t('Select an Image'), $file) ?>
    </div>
</fieldset>

<fieldset>
    <legend><?= t('Text') ?></legend>

    <div class="form-group">
        <?= $form->label('title', t('Title')) ?>
        <?php echo $form->text('title', $title); ?>
    </div>

    <div class="form-group">
        <?= $form->label('paragraph', t('Paragraph')) ?>
        <?php echo $form->textarea('paragraph', $paragraph, array('rows' => 5, 'class' => 'redactor-content')); ?>
    </div>

    <div class="form-group">
        <?= $form->label('Button Text', t('Button Text')) ?>
        <?php echo $form->text('buttonText', $buttonText, array('placeholder' => 'Learn More')); ?>
    </div>


</fieldset>

<fieldset>
    <legend><?= t('Link') ?></legend>

    <div class="form-group">
        <select name="linkType" data-select="feature-link-type" class="form-control">
            <option
                value="0" <?= (empty($externalLink) && empty($internalLinkCID) ? 'selected="selected"' : '') ?>><?= t(
                    'None') ?></option>
            <option
                value="1" <?= (empty($externalLink) && !empty($internalLinkCID) ? 'selected="selected"' : '') ?>><?= t(
                    'Another Page') ?></option>
            <option value="2" <?= (!empty($externalLink) ? 'selected="selected"' : '') ?>><?= t(
                    'External URL') ?></option>
            <option value="3" <?= (!empty($linkedFileID) ? 'selected="selected"' : '') ?>><?= t(
                    'File') ?></option>
        </select>
    </div>

    <div data-select-contents="feature-link-type-internal" style="display: none;" class="form-group">
        <?= $form->label('internalLinkCID', t('Choose Page:')) ?>
        <?= Loader::helper('form/page_selector')->selectPage('internalLinkCID', $internalLinkCID); ?>
    </div>

    <div data-select-contents="feature-link-type-external" style="display: none;" class="form-group">
        <?= $form->label('externalLink', t('URL')) ?>
        <?= $form->text('externalLink', $externalLink); ?>
    </div>

    <div data-select-contents="feature-link-type-file" style="display: none;" class="form-group">
        <?= $form->label('linkedFileID', t('File')) ?>
        <?=$asset_lib->file('ccm-b-file', 'linkedFileID', t('Choose File'), $bf);?>
    </div>

</fieldset>
</div>
<script type="text/javascript">

    (function () {
        var container = $('div.ccm-image-feature-edit');

        container.find('.redactor-content').redactor({
            minHeight: '200',
            'concrete5': {
                filemanager: <?=$fp->canAccessFileManager()?>,
                sitemap: <?=$tp->canAccessSitemap()?>,
                lightbox: true
            }
        });

        $('select[data-select=feature-link-type]').on('change', function () {
            if ($(this).val() == '0') {
                $('div[data-select-contents=feature-link-type-internal]').hide();
                $('div[data-select-contents=feature-link-type-external]').hide();
                $('div[data-select-contents=feature-link-type-file]').hide();
            }
            if ($(this).val() == '1') {
                $('div[data-select-contents=feature-link-type-internal]').show();
                $('div[data-select-contents=feature-link-type-external]').hide();
                $('div[data-select-contents=feature-link-type-file]').hide();
            }
            if ($(this).val() == '2') {
                $('div[data-select-contents=feature-link-type-internal]').hide();
                $('div[data-select-contents=feature-link-type-external]').show();
                $('div[data-select-contents=feature-link-type-file]').hide();
            }
            if ($(this).val() == '3') {
                $('div[data-select-contents=feature-link-type-internal]').hide();
                $('div[data-select-contents=feature-link-type-external]').hide();
                $('div[data-select-contents=feature-link-type-file]').show();
            }
        }).trigger('change');
    }());
</script>

<style type="text/css">
    div.ccm-block-feature-select-icon {
        position: relative;
    }

    div.ccm-block-feature-select-icon i {
        position: absolute;
        right: 15px;
        top: 10px;
    }
</style>
