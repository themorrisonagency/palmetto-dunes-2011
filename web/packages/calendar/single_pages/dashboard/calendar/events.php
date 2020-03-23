<? use Concrete\Package\Calendar\Src\Event\EventOccurrence;

defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="ccm-dashboard-header-buttons">
    <div class="btn-group">
        <div class="btn-group">
            <button type="button" id="calendar_button" class="btn btn-default" data-toggle="dropdown">
                <?= $calendar->getName() ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="calendar_button">
                <? foreach ($calendars as $cal) { ?>
                    <li><a href="<?= $controller->action('view', $cal->getID()) ?>"><?= $cal->getName() ?></a></li>
                <? } ?>
                <li class="divider"></li>
                <li><a href="<?= URL::to('/dashboard/calendar/add') ?>"><?= t("Add Calendar") ?></a></li>
                <li class="divider"></li>
                <li><a href="<?= URL::to('/dashboard/calendar/add', $calendar->getID()) ?>"><?= t("Edit Calendar") ?></a>
                </li>
                <li><a href="#" data-dialog="delete-calendar"><span class="text-danger"><?= t(
                                "Delete Calendar") ?></span></a></li>

            </ul>
        </div>
        <div class="btn-group">
            <button type="button" id="topics_button" class="btn btn-default" data-toggle="dropdown">
                <?= $topic ? h($topic->getTreeNodeDisplayName('html')): 'All Categories' ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="topics_button">
                <li>
                    <a href="?topic_id=0">All Categories</a>
                </li>
                <?php
                /** @var \Concrete\Core\Tree\Node\Node $topic_node */
                foreach ((array) $topics as $topic_node) {
                    ?>
                    <li>
                        <a href="?topic_id=<?= $topic_node->getTreeNodeID() ?>">
                            <?= h($topic_node->getTreeNodeDisplayName('html')) ?>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
        <a class="dialog-launch btn btn-primary" dialog-width="640" dialog-title="<?= t('Add Event') ?>"
           dialog-height="400"
           href="<?= URL::to('/ccm/calendar/dialogs/event/add', $calendar->getID()) ?>"><?= t("Add Event") ?></a>
    </div>
</div>

<div class="btn-group pull-right">
    <a href="<?= $previousLink ?>" class="btn btn-sm btn-default"><i class="fa fa-angle-double-left"></i></a>
    <a href="<?= $todayLink ?>" class="btn btn-sm btn-default"><?= t('Today') ?></i></a>
    <a href="<?= $nextLink ?>" class="btn btn-sm btn-default"><i class="fa fa-angle-double-right"></i></a>
</div>

<h2><?= $monthText ?>
    <select id="ccm-dashboard-calendar-year-selector">
        <? for ($i = 1970; $i <= 2085; $i++) { ?>
            <option value="<?=$i?>" <? if ($year == $i) { ?>selected<? } ?>><?=$i?></option>
        <? } ?>
    </select>
</h2>

<table class="ccm-dashboard-calendar table table-bordered">
    <thead>
    <tr>
        <td width="14%"><h4><?= t('Sun') ?></h4></td>
        <td width="14%"><h4><?= t('Mon') ?></h4></td>
        <td width="14%"><h4><?= t('Tue') ?></h4></td>
        <td width="14%"><h4><?= t('Wed') ?></h4></td>
        <td width="14%"><h4><?= t('Thu') ?></h4></td>
        <td width="14%"><h4><?= t('Fri') ?></h4></td>
        <td width="14%"><h4><?= t('Sat') ?></h4></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <?
        $cols = 0;
        $cellCounter = 0;
        $isToday = false;
        Loader::helper('text');
        for ($i = ($firstDayInMonthNum * -1) + 1; $i <= $daysInMonth; $i++) {
            $cellCounter++;
            if ($cols >= 7) {
                echo '</tr><tr>';
                $cols = 0;
            }
            $cols++;
            $isToday = (date('Y') == $year && $month == date('m') && $i == date('j'));
            ?>
            <td class="<? if ($isToday) { ?>ccm-dashboard-calendar-today<? } ?> <? if ($i > 0) { ?>ccm-dashboard-calendar-active-day<? } ?>">
                <div class="ccm-dashboard-calendar-date-wrap">
                    <? if ($i > 0) { ?>
                        <div class="ccm-dashboard-calendar-date"><?= $i ?></div>

                        <?
                        $list = new \Concrete\Package\Calendar\Src\Event\EventOccurrenceList();
                        if ($topic) {
                            $list->filterByAttribute('topics', $topic->getTreeNodeID());
                        }
                        $list->filterByCalendar($calendar);
                        $list->filterByDate(date('Y-m-d', strtotime($year . '-' . $month . '-' . $i)));
                        $results = $list->getResults();

                        /** @var EventOccurrence $occurrence */
                        foreach ($results as $occurrence) {
                            $event = $occurrence->getEvent(); ?>
                            <div class="ccm-dashboard-calendar-date-event<?= $occurrence->isCancelled() ? " ccm-dashboard-calendar-date-event-cancelled" : '' ?>">
                                <?php
                                if ($occurrence->isCancelled()) {
                                    ?>
                                    <span>
                                        <?= h($occurrence->getEvent()->getName()) ?: "&nbsp;" ?>
                                    </span>
                                    <?php
                                } else {
                                    ?>
                                    <a class="dialog-launch"
                                       dialog-title="<?= t('Edit Event') ?>"
                                       dialog-width="640"
                                       dialog-height="400"
                                       href="<?= URL::to(
                                           '/ccm/calendar/dialogs/event/edit',
                                           $occurrence->getID()) ?>">
                                        <?= h($occurrence->getEvent()->getName()) ?: "&nbsp;" ?>
                                    </a>
                                <?php
                                }
                                ?>
                            </div>
                        <?
                        }
                        ?>
                    <? } else { ?>
                        <div class="ccm-dashboard-calendar-date-inactive">&nbsp;</div>
                    <? } ?>
                </div>
            </td>
        <? }
        while ($cols < 7) {
            echo '<td><div class="ccm-dashboard-calendar-date-wrap"><div class="ccm-dashboard-calendar-date-inactive">&nbsp;</div></div></td>';
            $cols++;
        }
        ?>
    </tr>
    </tbody>
</table>

<div style="display: none">
    <div id="ccm-dialog-delete-calendar" class="ccm-ui">
        <form method="post" class="form-stacked" action="<?= $view->action('delete_calendar') ?>">
            <?= Loader::helper("validation/token")->output('delete_calendar') ?>
            <input type="hidden" name="caID" value="<?= $calendar->getID() ?>"/>

            <p><?= t('Are you sure? This action cannot be undone.') ?></p>
        </form>
        <div class="dialog-buttons">
            <button class="btn btn-default pull-left" onclick="jQuery.fn.dialog.closeTop()"><?= t('Cancel') ?></button>
            <button class="btn btn-danger pull-right" onclick="$('#ccm-dialog-delete-calendar form').submit()"><?= t(
                    'Delete Calendar') ?></button>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('a[data-dialog=delete-calendar]').on('click', function () {
            jQuery.fn.dialog.open({
                element: '#ccm-dialog-delete-calendar',
                modal: true,
                width: 320,
                title: '<?=t("Delete Calendar")?>',
                height: 'auto'
            });
        });

        $('select#ccm-dashboard-calendar-year-selector').on('change', function() {
           window.location.href='<?=URL::to('/dashboard/calendar/events', 'view', $calendar->getID())?>/'
           + $(this).val() + '/' + '<?=$month?>';
        });
    });
</script>

<style type="text/css">
    table.ccm-dashboard-calendar {
        width: 100%;
    }

    table.ccm-dashboard-calendar > thead > tr > td,
    table.ccm-dashboard-calendar {
        border-width: 0px !important;
    }

    table.ccm-dashboard-calendar td {
        min-height: 100px;
    }

    div.ccm-dashboard-calendar-date-wrap {
        min-height: 80px;
    }

    div.ccm-dashboard-calendar-date {
        text-align: right;
        font-size: 1.1em;
        margin-bottom: 20px;
        color: #666;
    }

    td.ccm-dashboard-calendar-today {
        background-color: rgba(91, 192, 222, 0.15);
    }

    div.ccm-dashboard-calendar-date-event {
        padding: 0px;
    }

    div.ccm-dashboard-calendar-date-event > a {
        background-color: #3988ED;
        display: block;
        text-decoration: none;
        color: #fff;
        padding: 2px 10px 2px 10px;
        margin-left: -8px;
        margin-right: -8px;
        text-decoration: none;
        border-top: 1px solid white;
    }

    div.ccm-dashboard-calendar-date + div.ccm-dashboard-calendar-date-event a {
        border-top: none;
    }

    div.ccm-dashboard-calendar-date-event-cancelled > span {
        background-color: #3988ED;
        display: block;
        text-decoration: none;
        color: #fff;
        padding: 2px 10px 2px 10px;
        margin-left: -8px;
        margin-right: -8px;
        cursor: not-allowed;
        opacity: .5;
    }

    div.ccm-dashboard-calendar-date-event > a:hover {
        color: #ccc;
    }

</style>
