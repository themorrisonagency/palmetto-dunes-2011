<?php use \PortlandLabs\Calendar\Event\EventOccurrence;

defined('C5_EXECUTE') or die("Access Denied.");
    $service = Core::make('helper/date');
    $c = Page::getCurrentPage();
    $cID = $c->getCollectionID();
    $numEvents = count($events);
?>
<div class="events-slider widget-featured-events unbound" data-page="<?= $totalPerPage ?: 3 ?>">
<? if ($eventListTitle) { ?>
    <h2><?=$eventListTitle?></h2>
<? } ?>
    <div class="events-slider-list" <? if($numEvents > $totalPerPage) { print 'style="display:none"'; } ?>>

<?
if ($total = count($events)) {
    /** @var EventOccurrence $occurrence */
    foreach($events as $occurrence) {
        $event = $occurrence->getEvent();
        $safe_name = strtolower($event->getName());
        $safe_name = preg_replace('/[^a-z ]/i', '', $safe_name);
        $safe_name = str_replace(' ', '+', $safe_name);
       ?>
        <div class="event">
            <div class="dates"><span class="start-date"><span class="mo"><?=$service->formatCustom('M', $occurrence->getStart())?></span><?=$service->formatCustom('d', $occurrence->getStart())?></span></div>
            <div class="tag">
            <? foreach($attributeKeys as $ak) { ?>
                <?
                    $tag = explode(",", $event->getAttribute($ak, 'displaySanitized'));
                    print $tag[0];
                    break;
                ?>
            <? } ?>
            </div>
            <div class="title">
                <? if ($calendarPage) {
                    $url = URL::to($calendarPage);
                    $path = $url->getPath();
                    $path->append(array('event', $bID, $occurrence->getID(), $safe_name));
                    $url = $url->setPath($path);
                    ?>
                    <a href="<?= $url ?>"><?=$event->getName()?></a>
                <? } else { ?>
                    <?=$event->getName()?>
                <? } ?>
            </div>
            <div class="clearfix"></div>
        </div>

    <? } ?>

<? } else { ?>
    <p class="no-events"><?=t('There are no upcoming events.')?></p>
<? } ?>

    </div>

    <div class="events-controls">
        <? if ($numEvents > $totalPerPage) { ?>
        <button type="button" class="e-prev fa fa-caret-left"><em class="alt">prev</em></button>
        <button type="button" class="e-next fa fa-caret-right"><em class="alt">next</em></button>
        <? } ?>
        <? if ($calendarPage && $buttonLinkText != '') { ?>
            <button type="button" data-calendar-url="<?=$calendarPage->getCollectionLink()?>" class="view-cal button <?=$buttonStyle == '2' ? 'primary' : 'secondary'?>"><?=$buttonLinkText?></button>
        <? } ?>
    </div>

</div>
<? if ($numEvents > $totalPerPage) { ?>
<script>
    (function() {
        function Button(element) {
            this.element = element;
        }

        Button.prototype.disable = function() {
            this.element.prop('disabled', true).addClass('disabled');
            return this;
        };

        Button.prototype.enable = function() {
            this.element.prop('disabled', false).removeClass('disabled');
            return this;
        };

        var routine = function() {
            $('.events-slider.unbound').removeClass('unbound').each(function(){
                var my = $(this),
                    previous  = new Button($('button.e-prev', my)),
                    next      = new Button($('button.e-next', my)),
                    page      = my.data('page'),
                    list      = my.children('.events-slider-list'),
                    events    = list.children(),
                    start     = 0,
                    container = $('<div />').css({
                        position: 'relative',
                        overflow: 'hidden'
                    }),
                    set_container = $('<div />'),
                    slider    = $('<div />').css({
                        position: 'absolute',
                        top: 0,
                        left: 0
                    }),
                    sliding = false;

                list.replaceWith(container);

                events.slice(start, page).appendTo(set_container.appendTo(container));
                container.height(container.height());

                previous.element.click(function(){

                    if (!sliding && start >= page) {
                        sliding = true;
                        start -= page;

                        var subset = events.slice(start, start + page);

                        slide(-1, subset, function() {
                            sliding = false;
                        });

                        if (!start) {
                            previous.disable();
                        }
                        next.enable();
                    }

                    return false;
                });

                next.element.click(function(){
                    if (!sliding || start + 1 >= events.length) {
                        sliding = true;
                        start += page;

                        var subset = events.slice(start, start + page);

                        slide(1, subset, function() {
                            sliding = false;
                        });


                        if (start + page >= events.length) {
                            next.disable();
                        }

                        previous.enable();
                    }

                    return false;
                });

                if (!start) {
                    previous.disable();
                }

                if (start + page > events.length) {
                    next.disable();
                }


                function slide(direction, subset, callback, length) {
                    length = length || 750;
                    slider.empty().append(subset).height(container.height()).width(container.width()).appendTo(container);
                    if (direction > 0) {
                        set_container.css({
                            position: 'absolute',
                            top: 0,
                            left: 0,
                            width: container.width()
                        }).animate({
                            left: -container.width()
                        }, length);
                        slider.css('left', container.width()).animate({left: 0}, length, function() {
                            set_container.empty().css({
                                position: 'static',
                                left: 0
                            }).append(subset);
                            slider.remove();
                            callback.apply(this, Array.prototype.slice.call(arguments));
                        });
                    } else {
                        set_container.css({
                            position: 'absolute',
                            top: 0,
                            left: 0,
                            width: container.width()
                        }).animate({
                            left: container.width()
                        }, length);
                        slider.css('left', -container.width()).animate({left: 0}, length, function() {
                            set_container.empty().css({
                                position: 'static',
                                left: 0
                            }).append(subset);
                            slider.remove();
                            callback.apply(this, Array.prototype.slice.call(arguments));
                        });
                    }
                }

            });
        };

        if (typeof jQuery != 'undefined') {
            routine();
            $('.view-cal').on('click', function() {
                var calLink = $(this).data('calendar-url');
                window.open(calLink, '_self');
            });
        } else {
            window.addEventListener('load', routine);
        }

    }());
</script>
<? } ?>