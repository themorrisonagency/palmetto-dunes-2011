<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<fieldset>

    <div class="form-group">
        <?= $form->label('location', t('Location')); ?>
        <?= $form->text('location', $location); ?>
    </div>

    <div class="form-group">
        <?= $form->label('zipCode', t('Zip Code')); ?>
        <?= $form->text('zipCode', $zipCode); ?>
    </div>


	<div class="form-group">
		<div><?= $form->label('units', t('Units')); ?></div>
		<div class="radio-inline">
		<label>
			<?= $form->radio('units', 'F', $units); ?>
			<span><?php echo t('F')?></span>
		</label>
		</div>
		<div class="radio-inline">
		<label>
			<?= $form->radio('units', 'C', $units); ?>
			<span><?php echo t('C')?></span>
		</label>
		</div>
	</div>

</fieldset>