<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="press-list-archives">
	<label>Press Archives:</label>

	<? if (count($dates)) { ?>
		<select class="press-archives">
			<option value="<?=$view->controller->getDateLink()?>">Select</option>
			<? foreach($dates as $date) { ?>
				<option value="<?=$view->controller->getDateLink($date)?>" <? if ($view->controller->isSelectedDate($date)) { ?>
                            selected="selected"
                        <? } ?>><?=$view->controller->getDateLabel($date)?></option>
			<? } ?>

		</select>
	<? } else { ?>
        <?=t('None.')?>
    <? } ?>
</div>