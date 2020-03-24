<?php   defined("C5_EXECUTE") or die("Access Denied.");

$al = Loader::helper("concrete/asset_library");
$ps = Loader::helper('form/page_selector');
$bf = null;
if ($controller->getFileID() > 0) {
        $bf = $controller->getFileObject();
    }
?>
<div class="ccm-megamenu-push-edit">
<fieldset>
    <legend><?= t('Photo-Based Push') ?></legend>
    <div class="form-group">
        <?php 
        if ($pushimg > 0) {
            $pushimg_o = File::getByID($pushimg);
            if (!is_object($pushimg_o)) {
                unset($pushimg_o);
            }
        } ?>
        <?php  echo $form->label("pushimg", t("Push Image")); ?>
        <?php  echo $al->file("ccm-b-file-pushimg", "pushimg", t("Choose File"), $pushimg_o); ?>
    </div>

    <?php  
    $fp = FilePermissions::getGlobal();
    $tp = new TaskPermission();
    ?>

    <div class="form-group">
    <?php   echo $form->label("photolink", t("Link to Page")); ?>
        <?= $ps->selectPage('photolink', $photolink); ?>
    </div>

    <div class="form-group">
    <?php   echo $form->label("photolinktext", t("Custom Link Text")); ?>
        <?php  echo $form->text("photolinktext", $photolinktitle, array (
      'maxlength' => 120,
    )); ?>
    </div>

    <div class="form-group">
        <?php   echo $form->label("description", t("Description")); ?>
        <?php echo $form->textarea('description', $description, array('rows' => 3, 'class' => 'redactor-content')); ?>
    </div>
</fieldset>
<hr/>

<fieldset>
    <legend><?= t('Show Booking Widget?') ?></legend>
    <div class="radio">
        <label>
            <input type="radio" name="showwidget" id="showwidget-no"
                value="0" <?php if ($showwidget === '0') { ?> checked<?php } ?>>
            <?php echo t('No') ?>
        </label>
        &nbsp;&nbsp;
        <label>
            <input type="radio" name="showwidget" id="showwidget-yes"
                value="1" <?php if ($showwidget === '1') { ?> checked<?php } ?>>
            <?php echo t('Yes') ?>
        </label>
    </div>
</fieldset>
<fieldset id="text-push-info">
    <legend><?= t('Text-Based Push') ?></legend>
    <div class="form-group">
        <?php  echo $form->label("title", t("Title")); ?>
        <?php  echo $form->text("title", $title, array (
      'maxlength' => 120,
    )); ?>
    </div>

    <div class="form-group">
        <?php   echo $form->label("text", t("Text")); ?>
        <?php echo $form->textarea('text', $text, array('rows' => 3, 'class' => 'redactor-content')); ?>
    </div>

    <div class="form-group">
        <?= $form->label('linkType', t('Link Type')) ?>
        <select name="linkType" data-select="feature-link-type" class="form-control">
            <option
                value="0" <?= (empty($externalLink) && empty($internalLinkCID) ? 'selected="selected"' : '') ?>><?= t(
                    'None') ?></option>
            <option
                value="1" <?= (empty($externalLink) && !empty($internalLinkCID) ? 'selected="selected"' : '') ?>><?= t(
                    'Another Page') ?></option>
            <option value="2" <?= (!empty($externalLink) ? 'selected="selected"' : '') ?>><?= t(
                    'External URL') ?></option>
            <option value="3" <?= (!empty($fID) ? 'selected="selected"' : '') ?>><?= t(
                    'File') ?></option>
        </select>
    </div>

    <div data-select-contents="feature-link-type-internal" style="display: none;" class="form-group">
        <?= $form->label('internalLinkCID', t('Choose Page:')) ?>
        <?= $ps->selectPage('internalLinkCID', $internalLinkCID); ?>
    </div>

    <div data-select-contents="feature-link-type-external" style="display: none;" class="form-group">
        <?= $form->label('externalLink', t('URL')) ?>
        <?= $form->text('externalLink', $externalLink); ?>
    </div>

    <div data-select-contents="feature-link-type-file" style="display: none;" class="form-group">
        <?= $form->label('fID', t('File')) ?>
        <?=$al->file('ccm-b-file', 'fID', t('Choose File'), $bf);?>
    </div>

    <div class="form-group">
        <?php  echo $form->label("linktext", t("Link Text")); ?>
        <?php  echo $form->text("linktext", $linktext, array (
      'maxlength' => 120,
    )); ?>
    </div>
</fieldset>
</div>

<script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php   echo Loader::helper('validation/token')->generate('editor')?>";
    $(function () {
        var container = $('div.ccm-megamenu-push-edit');

        container.find('.redactor-content').redactor({
            minHeight: '100',
            buttons: ['formatting','bold','italic','deleted','alignment','link','horizontalrule','html'],
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
                $('div[data-select-contents=feature-link-type-file]').hide();
                $('div[data-select-contents=feature-link-type-external]').show();
            }
            if ($(this).val() == '3') {
                $('div[data-select-contents=feature-link-type-internal]').hide();
                $('div[data-select-contents=feature-link-type-external]').hide();
                $('div[data-select-contents=feature-link-type-file]').show();
            }
        }).trigger('change');

        $('input[name="showwidget"]').on('change', function () {
            if ($(this).is(":checked") && $(this).val() == '0') {
                $('#text-push-info').fadeIn('slow');
            }
            if ($(this).is(":checked") && $(this).val() == '1') {
                $('#text-push-info').fadeOut('slow');
            }
        }).trigger('change');
    });
</script>