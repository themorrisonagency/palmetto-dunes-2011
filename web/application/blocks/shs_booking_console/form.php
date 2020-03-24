<?php  defined('C5_EXECUTE') or die(_("Access Denied."));
$al = Loader::helper('concrete/asset_library');?>

<script>
    var CCM_EDITOR_SECURITY_TOKEN = "<?php echo Loader::helper('validation/token')->generate('editor')?>";

    $(function() {
    	// activate redactors
        $('.redactor-content').redactor({
            minHeight: '200',
            width: '650',
            buttons: ['formatting','bold','italic','deleted','unorderedlist','orderedlist','alignment','link','horizontalrule','html'],
        });
    });
</script>

<div class="form-group">
	<?php echo $form->label('vacationRentalsTab', t('Vacation Rentals Tab Content'))?>
    <div class="redactor-edit-content"></div>
    <textarea class="redactor-content" name="vacationRentalsTab"><?php echo $vacationRentalsTab; ?></textarea>
</div>

<div class="form-group">
	<?php echo $form->label('teeTimesTab', t('Tee Times Tab Content'))?>
    <div class="redactor-edit-content"></div>
    <textarea class="redactor-content" name="teeTimesTab"><?php echo $teeTimesTab; ?></textarea>
</div>

<div class="form-group">
	<?php echo $form->label('bikeRentalsTab', t('Bike Rentals Tab Content'))?>
    <div class="redactor-edit-content"></div>
    <textarea class="redactor-content" name="bikeRentalsTab"><?php echo $bikeRentalsTab; ?></textarea>
</div>

<div class="form-group">
    <?php echo $form->label('fareharborTab', t('Fishing Tab Content'))?>
    <div class="redactor-edit-content"></div>
    <textarea class="redactor-content" name="fareharborTab"><?php echo $fareharborTab; ?></textarea>
</div>