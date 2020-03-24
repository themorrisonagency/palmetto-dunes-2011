<?php
  defined('C5_EXECUTE') or die("Access Denied.");
?>

<div class="form-group">
  <label><?= t('Status') ?></label><br />
  <div class="form-group">
      <div class="radio">
          <label><input type="radio" name="<?=$view->field('status')?>" value="0" <?php echo $status > 0 ? '' : 'checked' ?> /><?php echo t('Off') ?></label>
      </div>
  </div>
  <div class="form-group">
      <div class="radio">
          <label><input type="radio" name="<?=$view->field('status')?>" value="1" <?php echo $status > 0 ? 'checked' : '' ?> /><?php echo t('On') ?></label>
      </div>
  </div>
</div>

<div class="form-group">
  <label><?= t('Link Type') ?></label>
  <select name="linkType" class="form-control">
      <option value="0" <?= (empty($link) && empty($internalLinkCID) ? 'selected="selected"' : '') ?>><?= t(
              'None') ?></option>
      <option value="1" <?= (empty($link) && !empty($internalLinkCID) ? 'selected="selected"' : '') ?>><?= t(
              'Another Page') ?></option>
      <option value="2" <?= (!empty($link) ? 'selected="selected"' : '') ?>><?= t(
              'External URL') ?></option>
  </select>
</div>
<!-- Another Page -->
<div style="display: none;" data-link-type="1" class="form-group">
  <label><?= t('Choose Page') ?></label>
  <?= Loader::helper('form/page_selector')->selectPage('internalLinkCID', $internalLinkCID) ?>
</div>
<!-- External URL, just text -->
<div style="display: none;" data-link-type="2" class="form-group">
  <label><?= t('Push Link') ?></label>
  <?= $form->text('link', $link) ?>
</div>

<script>
(function() {
  $('select[name="linkType"]').on('change', function() {
    $('.form-group[data-link-type]')
      .css('display', 'none')
      .filter('[data-link-type="' + this.value + '"]')
        .css('display', 'block');
  }).trigger('change');
}());
</script>