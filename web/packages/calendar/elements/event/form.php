
<?php
use Concrete\Package\Calendar\Src\Event\EventOccurrence;
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
?>
<div class="ccm-event-form">
    <?php
    /** @var EventOccurrence $occurrence */
    if ($occurrence) {
        $event = $occurrence->getEvent();
        if ($event->getRepetition()->repeats()) {
            ?>
            <fieldset>
                <div class="form-group">
                    <div class="radio">
                        <label>
                            <input type="radio" name="edit_type" value="forward" data-start="<?= $occurrence->getStart() ?>" data-end="<?php $occurrence->getEnd() ?>"/> <?= t('Occurrences after this one') ?>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="edit_type" value="local"/> <?= t('Just this occurrence') ?>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="edit_type" value="all" checked/> <?= t('All occurrences') ?>
                        </label>
                    </div>
                </div>
            </fieldset>

        <?php
        }
    }
    ?>

    <fieldset>
        <legend><?=t('Basics')?></legend>
        <div class="form-group">
            <label for="name" class="control-label">
                <?= t('Name') ?>
            </label>

            <input type="text" class="form-control" placeholder="Name" name="name" value="<?= $event ? $event->getName() : '' ?>">
        </div>
        <div class="form-group">
            <label for="name" class="control-label">
                <?= t('Description') ?>
            </label>
            <textarea class="form-control" rows="3" name="description"><?= $event ? $event->getDescription() : '' ?></textarea>
        </div>
	<div class="form-group">
		<label for="eventIntro">
			<?= t('Event Intro'); ?>
		</label>
		<input type="text" class="form-control" placeholder="Event Intro" name="eventIntro" value="<?= $event ? $event->getEventIntro() : ''; ?>"/>
	</div>
    </fieldset>
    <?php
    if ($occurrence) {
        ?>
        <fieldset class="date-time" style="display:none">
            <legend><?= t('Date &amp; Time') ?></legend>
            <?php
            $form = \Core::make('helper/form');
            $dt = \Core::make('helper/form/date_time');

            $pdStartDate = date('Y-m-d H:i:s', $occurrence->getStart());
            $pdEndDate = date('Y-m-d H:i:s', $occurrence->getEnd());
            ?>
            <div id="ccm-permissions-access-entity-dates">

                <div class="form-group">
                    <label for="pdStartDate_activate" class="control-label"><?= tc('Start date', 'From') ?></label>

                    <div class="">
                        <?= $dt->datetime('pdOccurrenceStartDate', $pdStartDate, true); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="pdEndDate_activate" class="control-label"><?= tc('End date', 'To') ?></label>

                    <div class="">
                        <?= $dt->datetime('pdOccurrenceEndDate', $pdEndDate, true); ?>
                    </div>
                </div>

            </div>
        </fieldset>
        <?php
    }
    ?>
    <fieldset class="repeat-date-time">
        <legend><?=t('Date & Time')?></legend>
        <?= \View::element('permission/duration', array('pd' => $event ? $event->getRepetition() : null)); ?>
    </fieldset>
</div>

<script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php echo Loader::helper('validation/token')->generate('editor')?>";
    _.defer(function() {
        var radios = $("input[name='edit_type']"),
            local = $('fieldset.date-time'),
            all = $('fieldset.repeat-date-time'),
            delete_local = $('a.delete-local'),
            delete_all = $('a.delete-all');

        radios.closest('form').change(function() {
            if (radios.filter(':checked').val() === 'local') {
                local.show();
                all.hide();

                delete_local.show();
                delete_all.hide();
            } else {
                local.hide();
                all.show();

                delete_local.hide();
                delete_all.show();
            }
        });

        delete_local.click(function() {
            return confirm('<?= t('Are you sure you want to delete this occurrence?') ?> ');
        });
        delete_all.click(function() {
            return confirm('<?= t('Are you sure you want to delete this event and all occurrences?') ?> ');
        });
    });

    $(function() {
        $('.ccm-event-form textarea[name=description]').redactor({
                minHeight: '200',
                buttons: ['formatting','bold','italic','deleted','unorderedlist','orderedlist','link','horizontalrule','html'],
                'concrete5': {
                    filemanager: <?=$fp->canAccessFileManager()?>,
                    sitemap: <?=$tp->canAccessSitemap()?>,
                    lightbox: true
                }
            });
    });
	
	$(document).ready(function(){
		$('.control-label').each(function(){
			if ($(this).html() == 'Event Photo'){
				$(this).html('Event Photo &nbsp; <small><i>(recommended size: 175 x 175)</i></small>');
			}
		});
	});
</script>

<style type="text/css">
    div.ccm-event-form div.redactor_editor {
        height: 120px;
    }
</style>
<?
$af = Core::make('helper/form/attribute');
if (is_object($event)) {
    $af->setAttributeObject($event);
}
$category = \Concrete\Core\Attribute\Key\Category::getByHandle('event');
if (is_object($category) && $category->allowAttributeSets()) {
    $sets = $category->getAttributeSets();
}
if (count($sets)) {
    foreach($sets as $set) { ?>
        <fieldset>
            <legend><?=$set->getAttributeSetDisplayName()?></legend>
            <?
            $keys = $set->getAttributeKeys();
            foreach($keys as $ak) {
                echo $af->display($ak);
            }
            ?>
        </fieldset>
    <? } ?>
<? } else {
    $attributes = \Concrete\Package\Calendar\Src\Attribute\Key\EventKey::getList();
    if (count($attributes)) { ?>
        <fieldset>
            <legend><?=t("Custom Attributes")?></legend>
            <?
            foreach($attributes as $ak) {
                echo $af->display($ak);
            }
            ?>
        </fieldset>
    <? } ?>
<? } ?>
