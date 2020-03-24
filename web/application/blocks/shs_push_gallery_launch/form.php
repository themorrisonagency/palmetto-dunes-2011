<?php   defined("C5_EXECUTE") or die("Access Denied."); ?>

<?php  $al = Loader::helper("concrete/asset_library"); ?>
<div class="form-group">
    <?php
    if ($image > 0) {
        $image_o = File::getByID($image);
        if ($image_o->isError()) {
            unset($image_o);
        }
    } ?>
    <?php  echo $form->label("image", t("Image")); ?>
    <?php  echo (isset($btFieldsRequired) && in_array('image', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null); ?>
    <?php  echo $al->file("ccm-b-file-image", "image", t("Choose File"), $image_o); ?>
</div>

<div class="form-group">
    <?php  echo $form->label("title", t("Title")); ?>
    <?php  echo (isset($btFieldsRequired) && in_array('title', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null); ?>
    <?php  echo $form->text("title", $title, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php  echo $form->label("ctaIcon", t("CTA Icon")); ?>
    <?php  echo (isset($btFieldsRequired) && in_array('ctaIcon', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null); ?>
    <?php  echo $form->text("ctaIcon", $ctaIcon, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php  echo $form->label("pinterestBtn", t("Pinterest Button")); ?>
    <?php  echo (isset($btFieldsRequired) && in_array('pinterestBtn', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null); ?>
    <?php  $options = array(
        '' => '-- None --',
        no => 'No',
        yes => 'Yes'
    ); ?>
    <?php  echo $form->select("pinterestBtn", $options, $pinterestBtn); ?>
</div>

<div class="form-group">
    <?php  echo $form->label("linkUrl", t("Link")); ?>
    <?php  echo (isset($btFieldsRequired) && in_array('linkUrl', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null); ?>
    <?php  echo $form->text("linkUrl", $linkUrl, array()); ?>
</div>