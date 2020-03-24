<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<? if (count($dates)) { ?>
    <ul class="archives">
        <? foreach($dates as $date) { ?>
            <li>
                <a href="<?=$view->controller->getDateLink($date)?>"><?=$view->controller->getDateLabel($date)?></a>
            </li>
        <? } ?>
    </ul>
<? } else { ?>
    <?=t('None.')?>
<? } ?>
