<?php
defined('C5_EXECUTE') or die('Access Denied.');

$c = Page::getCurrentPage();
$cID = $c->getCollectionID();
$nh = Loader::helper('navigation');
$cpl='';
$actualpageurl = $nh->getCollectionURL($c);
$base_url = View::url('/');


$single_day = true;
if (\Request::getInstance()->get('shs_stream_monthview')) {
    $single_day = false;
}
$start_time = \Request::getInstance()->get('shs_stream_date');
?>

<div class="event-calendar-wrapper">
<a id="eventbaseurl" href="<?php echo $actualpageurl;?>"><span class="show-for-sr">Page Url</span></a>
    <?php if (!$permalink) { ?>

    <div class="events-listing events-listing-top-bar">
        <div class="event-calendar">
            <div class="events-bar">
                <div class="column small-7 medium-5 large-3">
                    <a class="category-toggle" href="#">
                        <i class="fa fa-folder-open folder"></i>
                        <span>All Categories</span>
                        <i class="fa fa-caret fa-caret-down"> </i>
                    </a>
                </div>
                <div class="column small-5 medium-7 large-9">
                    <div class="events-switch">
                        <fieldset>
                            <legend><span class="show-for-sr">calendar/grid toggle</span></legend>
                            <input type="radio" class="switch-input" name="view" value="calendar" id="calendar">
                            <label for="calendar" class="switch-label switch-label-off">
                                <svg xmlns="http://www.w3.org/2000/svg" class="svg-calendar" width="24" height="25" viewBox="0 0 24 25" aria-labelledby="calendar-title" role="img"><title id="calendar-title">Calendar</title><g fill="#fff"><path d="M0 3h24v5H0zM0 24h24v1H0z"/><path d="M0 7.323h1V25H0zM23 7.323h1V25h-1zM4 0h2v3H4zM18 0h2v3h-2zM2 8.755h2v2H2zM2 11.755h2v2H2zM2 14.755h2v2H2zM2 17.755h2v2H2zM2 20.755h2v2H2zM5 8.755h2v2H5zM5 11.755h2v2H5zM5 14.755h2v2H5zM5 17.755h2v2H5zM5 20.755h2v2H5zM8 8.755h2v2H8zM8 11.755h2v2H8zM8 14.755h2v2H8zM8 17.755h2v2H8zM8 20.755h2v2H8zM11 8.755h2v2h-2zM11 11.755h2v2h-2zM11 14.755h2v2h-2zM11 17.755h2v2h-2zM11 20.755h2v2h-2zM14 8.755h2v2h-2zM14 11.755h2v2h-2zM14 14.755h2v2h-2zM14 17.755h2v2h-2zM17 8.755h2v2h-2zM17 11.755h2v2h-2zM17 14.755h2v2h-2zM17 17.755h2v2h-2zM20 8.755h2v2h-2zM20 11.755h2v2h-2zM20 14.755h2v2h-2zM20 17.755h2v2h-2z"/></g></svg>
                            </label>
                            <input type="radio" class="switch-input" name="view" value="grid" id="grid" checked="">
                            <label for="grid" class="switch-label switch-label-on">
                                <svg xmlns="http://www.w3.org/2000/svg" class="svg-grid" width="24" height="24" viewBox="-0.083 -0.021 24 24" aria-labelledby="grid-title" role="img"><title id="grid-title">Grid</title><g fill="#474747"><path d="M-.084-.02h7.05V6.99h-7.05zM16.865-.02h7.05V6.99h-7.05zM8.392-.02h7.05V6.99h-7.05zM-.084 8.452h7.05v7.012h-7.05zM16.865 8.452h7.05v7.012h-7.05zM8.392 8.452h7.05v7.012h-7.05zM-.084 16.967h7.05v7.012h-7.05zM16.865 16.967h7.05v7.012h-7.05zM8.392 16.967h7.05v7.012h-7.05z"/></g></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="svg-menu" width="32" height="25" viewBox="0 0 21 15.6" aria-labelledby="list-title" role="img"><title id="list-title">List</title><path fill="#fff" d="M0 12.4h21v3.1H0zm0-6.2h21v3.1H0zM0 0h21v3.1H0z"></path></svg>
                            </label>
                            <span class="switch-selection"></span>
                        </fieldset>
                    </div>
                </div>
                <ul class="category-list">
                    <li class="all-cats"><a class="active" href="#">All Categories</a></li>
                </ul>
                <script id="category-list" type="text/x-handlebars-template">​
                    {{#each this}}
                        {{#if treeNodeTopicName}}
                        <li><a href="#" data-trigger="{{handle treeNodeTopicName}}" data-category-id="{{treeNodeID}}" class="event-icon-{{counter @index}}">{{treeNodeTopicName}}</a></li>​
                        {{/if}}
                    {{/each}}
                ​</script>
            </div>
        </div>
    </div>

    <? } ?>

    <?php if (!$permalink) { ?>

        <div class="events-listing events-dates-bar">
            <script id="events-dates-bar" type="text/x-handlebars-template">​
                <div class="events-months" data-current-date="{{month}}/{{year}}">
                    {{{buildEventsDatesBar monthDropDown month year dateToday previous_month next_month}}}
                </div>
            </script>
        </div>

    <? } ?>

    <?php if (!$permalink) { echo '<div class="cal-wrap pre">'; } ?>

    <div class="events-listing grid">

    <?php if (!$permalink) { ?>

        <div class="events-grid-view toggle-view js-masonry" data-masonry-options='{ "itemSelector": ".event", "columWidth": ".event-sizer", "gutter": ".gutter-sizer", "percentPosition": "true" }'>
            <div class="no-events no-events-category" style="display: none">There are currently no <span class="filter-name"></span> events for this day. Please choose another day or try another category by clicking on the category listing above.</div>

            <script id="grid-event" type="text/x-handlebars-template">​
                <div class="event event-sizer"></div>
                <div class="gutter-sizer"></div>
                {{#each items}}
                    {{#ifCondGridDate start.day_time ../selectedDate ../dateToday}}
                    <div class="event filter {{#each topic}}{{#if treeNodeTopicName}}{{splitTopics treeNodeTopicName}}{{/if}}{{/each}}" itemscope="itemscope" itemtype="http://schema.org/Event">
                        <div class="event-info">
                            {{#if eventImage}}
                            <div class="event-image">
                                <img src="{{eventImage}}" alt="{{eventImageAlt}}" itemprop="image" />
                            </div>
                            {{/if}}
                            <header class="event-header">
                                <h3 itemprop="name">{{name}}</h3>
                                <meta itemprop="startDate" content="{{metaStartTime start.time}}" />
                                <h4>
				    {{total}}
                                    {{#if location}}
                                        <br />{{location}}
                                    {{/if}}
                                </h4>
                            </header>
                        </div>
                        <div class="event-category row">
                            <div class="category-label columns small-8">
                                {{#each topic}}
                                    {{#if treeNodeTopicName}}{{{splitTopics treeNodeTopicName 'grid'}}}{{/if}}
                                {{/each}}
                            </div>
                            <div class="category-button columns small-4">
                                <a href="<?= $cpl; ?>/event/<?= $bID; ?>/{{id}}/{{url name}}" class="button" itemprop="url" data-item-id="{{id}}">
                                    {{#if eventLinkText}}
                                        {{eventLinkText}}
                                    {{else}}
                                        Details
                                    {{/if}}
                                    <i class="fa fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="event-date">
                            <div class="month">{{abbrevMonth start.time}}</div>
                            <div class="date">{{start.day}}</div>
                            <div class="day">{{abbrevDay start.time}}</div>
                        </div>
                    </div>
                    {{/ifCondGridDate}}
                {{/each}}
            </script>
        </div>

    <?php } else { ?>

        <ul class="category-list" style="display: none;">
 <script id="category-list" type="text/x-handlebars-template">​
                {{#each this}}
                    <li><a href="#" data-trigger="{{#if treeNodeTopicName}}{{handle treeNodeTopicName}}{{/if}}" data-category-id="{{treeNodeID}}" class="event-icon-{{counter @index}}">{{#if treeNodeTopicName}}{{treeNodeTopicName}}{{/if}}</a></li>​
                {{/each}}
            ​</script>
        </ul>

        <div id="event-calendar" class="event-details permalink">
            <div class="event-details-bar row">
                <div class="column small-6 medium-3 large-2">
                    <a class="view-full event-back" href="/special-pages/events/events-series-two"><i class="fa fa-chevron-left"></i> View All Events</a>
                </div>
                <div class="column small-6 medium-9 large-10 text-center">
                    <div class="event-details-title">Event Details</div>
                </div>
            </div>
            <?php
                // Temp approach to limiting $items:
                $_SERVER['REQUEST_URI_PATH'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                $segments = explode('/', $_SERVER['REQUEST_URI_PATH']);
                $bID = $segments[2];
                $itemID = $segments[3];
                $event_topics = array();

                foreach ($items as $item) {
                    if ($item['id'] == $itemID) {
                        $event_name = $item['name'];
                        $event_intro = $item['eventIntro'];
                        $event_description = $item['description'];
                        foreach ($item['topic'] as $topic) {
                            array_push($event_topics, $topic->treeNodeTopicName);
                        }
                        // print_r($event_topics);
                        $f = File::getByID($item['eventImageID']);
                        if(is_object($f)) {
                            $event_image = Core::make('html/image', array($f, false))->getTag();
                            $event_image->itemprop('image');
                            if ($item['eventImageAlt']) {
                                $event_image->alt($item['eventImageAlt']);
                            } else {
                                $event_image->alt('');
                            }
                        }
                        $event_link = $item['eventAddLink'];
                        $event_link_text = $item['eventLinkText'];
                        $event_link_url = $item['eventLinkUrl'];
                        $event_permalink = View::url('/event/') . '/' . $bID . '/' . $item['id'] . '/' . rawurldecode(str_replace(' ','-',strtolower($event_name)));
                        $event_date_format = $item['eventDateFormat'];
                        switch ($event_date_format) {
                            case '':
                                $event_date = date("F j, Y", $item['start']['time']);
                                break;
                            case 0:
                                $event_date = date("m/d/Y", $item['start']['time']);
                                break;
                            case 1:
                                $event_date = date("d/m/Y", $item['start']['time']);
                                break;
                            default:
                                $event_date = date("F j, Y", $item['start']['time']);
                                break;
                        }
                        $event_time_from = $item['start']['hour'] . ':' . $item['start']['minute'] . ' ' . $item['start']['meridiem'];
                        $event_time_to = $item['end']['hour'] . ':' . $item['end']['minute'] . ' ' . $item['end']['meridiem'];
                        $event_meta_date = date("Y-m-d H:i:s", $item['start']['time']);
                        $event_location = $item['location'];
                        $cal_month = substr(date("F", $item['start']['time']), 0, 3);
                        $cal_date = date("j", $item['start']['time']);
                        $cal_day = substr(date("D", $item['start']['time']), 0, 3);
                    }
                }
            ?>

            <div class="event events-grid-view" itemscope="itemscope" itemtype="http://schema.org/Event">
                <div class="event-info">
                   <? if ($event_image) { ?>
                    <div class="event-image">
                        <?= $event_image; ?>
                    </div>
                    <? } ?>
                    <header class="event-header">
                        <h3 itemprop="name"><?= $event_name; ?></h3>
                        <meta itemprop="startDate" content="<?= $event_meta_date; ?>" />
                        <h4>
                            <?= $event_time_from; ?> - <?= $event_time_to; ?>
                            <? if ($event_location) { ?>
                                <br /><?= $event_location; ?>
                            <? } ?>
                        </h4>
                    </header>
                </div>
                <div class="event-date">
                    <div class="month"><?= $cal_month; ?></div>
                    <div class="date"><?= $cal_date; ?></div>
                    <div class="day"><?= $cal_day; ?></div>
                </div>
                <div class="event-description">
                    <div class="event-category row">
                        <div class="category-label">
                            <?php
                            $i = 0;
                            $len = count($event_topics);
                                foreach ($event_topics as $topic) {
                                    $icon_class = strtolower($topic);
                                    $icon_class = preg_replace("/[\s_]/", "-", $icon_class);
                                    echo '<span class="'.$icon_class.'">'.$topic.'</span>';
                                    $i++;
                                }
                            ?>
                        </div>
                    </div>

                    <section class="short-description">
                        <p itemprop="description"><?= $event_intro; ?></p>
                    </section>
                    <section class="expandable">
                        <?= $event_description; ?>
                        <div class="event-details-links row">
                            <!--<div class="event-details-share columns medium-8 medium-push-4 large-9 large-push-3">-->
                            <div class="event-details-share columns small-12">
                                <ul class="share-tags">
                                    <li><a class="fi-mail" href="mailto:?subject=Check out this <?= Config::get('concrete.site'); ?> event!&amp;body=<?= $event_permalink ?>"><i class="fa fa-envelope"></i><span class="share-text">Share</span></a></li>
                                    <li><a class="fi-social-twitter" target="_blank" href="https://www.twitter.com/intent/tweet?text=Check+out+this+<?= Config::get('concrete.site'); ?>+event!&url=<?= $event_permalink ?>"><i class="fa fa-twitter"></i><span class="share-text">Tweet</span></a></li>
                                    <!--li><a class="fi-social-google-plus" target="_blank" href="https://plus.google.com/share?url=<?= $event_permalink ?>"><i class="fa fa-google-plus"></i><span class="share-text">Google+</span></a></li-->
                                    <li><a class="fi-social-facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= $event_permalink ?>"><i class="fa fa-facebook"></i><span class="share-text">Like It</span></a></li>
                                </ul>
                            </div>
                            <!--<div class="event-details-back columns medium-4 medium-pull-8 large-3 large-pull-9">-->
                            <div class="event-details-back columns small-12 medium-6 end">
                                <a href="/special-pages/events/events-series-two" class="event-back button"><i class="fa fa-chevron-left"></i>View All Events</a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

    <? } ?>

    </div>

    <?php if (!$permalink) { ?>

    <div class="events-listing calendar">
        <div class="events-calendar-view toggle-view">
            <div class="row">
                <div class="column medium-7 large-8 column-calendar">
                    <script id="calendar-current-month" type="text/x-handlebars-template">​
                        <table summary="Current Month" class="calendar-layout" data-event-date="{{ifDataDate dateToday selectedDate}}">
                            <thead>
                                <tr id="months">
                                    <th colspan="7" id="current_month_now">
                                        {{dateHeading this_month}}
                                    </th>
                                </tr>
                                <tr id="days">
                                    {{#each dayNames}}
                                        {{#if @first}}
                                        <th class="first-col">{{this}}</th>
                                        {{else}}
                                        <th>{{this}}</th>
                                        {{/if}}
                                    {{/each}}
                                </tr>
                            </thead>
                            <tbody>
                                {{{buildCalendar dateToday firstDayOffset daysInMonth year month selectedDay selectedDate occupied}}}
                            </tbody>
                        </table>
                    </script>
                    <script id="event-icon-filter" type="text/x-handlebars-template">​
                        {{#each this}}
                            {{#each topic}}
                                {{#if treeNodeTopicName}}{{{splitTopicsTime treeNodeTopicName ../start.day_time}}}{{/if}}
                            {{/each}}
                        {{/each}}
                    ​</script>
                    <script id="category-listing" type="text/x-handlebars-template">​
                        <ul class="category-listing" id="category-listing">
                        {{#each this}}
                            {{#if treeNodeTopicName}}
                            <li class="filter filter-legend event-icon-{{counter @index}}">{{treeNodeTopicName}}</li>
                            {{/if}}
                        {{/each}}
                        </ul>
                    </script>
                </div>
                <?php
                    if (strtotime($start_time) < strtotime(date('m/d/Y'))) {
                        $start_time = date('m/d/Y');
                    }
                ?>
                <div class="column medium-5 large-4 column-listing">
                    <script id="column-event" type="text/x-handlebars-template">​
                        <div class="column-events-wrapper" data-event-date="{{ifDataDate dateToday selectedDate}}">
                            <div class="event-list-title"><span class="filter-full-date">{{ifDataDateHeading dateToday selectedDate}}</span> <span class="filter-name"></span> Events<i class="fa fa-chevron-circle-down slide-down"></i></div>
                            <div class="no-events no-events-category">There are currently no <span class="filter-name"></span> events for this day. Please choose another day or try another category by clicking on the category listing above.</div>
                            {{#each items}}
                            <a href="<?= $cpl; ?>/event/<?= $bID; ?>/{{id}}/{{url name}}" class="event-list-item filter {{#each topic}}{{#if treeNodeTopicName}}{{splitTopics treeNodeTopicName}}{{/if}}{{/each}}{{start.day_time}}" data-item-id="{{id}}">
                                <div class="column small-3 medium-4 large-3 time">
                                    <span class="from">{{start.hour}}:{{start.minute}} {{start.meridiem}}</span><br />
                                    <span class="to">{{end.hour}}:{{end.minute}} {{end.meridiem}}</span>
                                </div>
                                <div class="column small-9 medium-8 large-9 title">
                                    <div class="title-inner">
                                        <div class="event-listing-title">{{name}}</div>
                                        {{#if eventIntro}}
                                        <div class="event-listing-intro">{{eventIntro}}</div>
                                        {{/if}}
                                    </div>
                                    {{#each topic}}
                                        {{#if treeNodeTopicName}}{{{splitTopics treeNodeTopicName 'span'}}}{{/if}}
                                    {{/each}}
                                </div>
                            </a>
                            {{/each}}
                        </div>
                    </script>
                </div>
            </div>
        </div>
    </div>

    <div class="no-events no-events-month">There are currently no <span class="filter-name"></span> events for this category or month. Please choose another day or try another category by clicking on the category listing above.</div>

    <!--<section class="buttons" style="display: none;">
        <a href="#" class="button back-to-top">Back to Top</a>
    </section>-->

    <?php if (!$permalink) { echo '</div>'; } ?>

    <script id="overlay-event" type="text/x-handlebars-template">​
        <div class="event-details overlay-event-window modal fade">
            <div class="modal-dialog">
                <!--
                <div class="event-details-bar row">
                    <div class="column small-6 medium-3 large-2">
                        <a class="view-full event-back" href="/special-pages/events/events-series-two"><i class="fa fa-chevron-left"></i> View All Events</a>
                    </div>
                    <div class="column small-6 medium-9 large-10 text-center">
                        <div class="event-details-title">Event Details</div>
                    </div>
                </div>
                -->
                <div class="overlay event {{#each topic}}{{#if treeNodeTopicName}}{{splitTopics treeNodeTopicName}}{{/if}}{{/each}}" itemscope="itemscope" itemtype="http://schema.org/Event">
                    <div class="event-info">
                       {{#if eventImage}}
                        <div class="event-image">
                            <img src="{{eventImage}}" alt="{{name}}" itemprop="image" />
                        </div>
                        {{/if}}
                        <header class="event-header">
                            <h3 itemprop="name">{{name}}</h3>
                            <meta itemprop="startDate" content="{{metaStartTime start.time}}" />
                            <h4>
				{{total}}
                                {{#if location}}
                                    <br />{{location}}
                                {{/if}}
                            </h4>
                        </header>
                    </div>
                    <div class="event-date">
                        <div class="month">{{abbrevMonth start.time}}</div>
                        <div class="date">{{start.day}}</div>
                        <div class="day">{{abbrevDay start.time}}</div>
                    </div>
                    <div class="event-category">
                        <div class="category-label">
                            {{#each topic}}
                                {{#if treeNodeTopicName}}{{{splitTopics treeNodeTopicName 'grid'}}}{{/if}}
                            {{/each}}
                        </div>
                    </div>
                    <div class="event-description">
                        <section class="short-description">
                            <p itemprop="description">{{eventIntro}}</p>
                        </section>
                        <section class="long-description expandable">
                            {{{description}}}
                            <div class="event-details-links row">
                                <div class="event-details-share columns">
                                    <ul class="share-tags">
                                        <li><a class="fi-mail" href="mailto:?subject=Check out this <?= Config::get('concrete.site'); ?> event!&amp;body=<?= $base_url; ?>event/<?= $bID; ?>/{{id}}/{{url name}}"><i class="fa fa-envelope"></i><span class="share-text">Share</span></a></li>
                                        <li><a class="fi-social-twitter" href="https://www.twitter.com/intent/tweet?text=Check+out+this+<?= Config::get('concrete.site'); ?>+event!&url=<?= $base_url; ?>event/<?= $bID; ?>/{{id}}/{{url name}}" target="_blank"><i class="fa fa-twitter"></i><span class="share-text">Tweet</span></a></li>
                                        <!--li><a class="fi-social-google-plus" href="https://plus.google.com/share?url=<?= $base_url; ?>event/<?= $bID; ?>/{{id}}/{{url name}}" target="_blank"><i class="fa fa-google-plus"></i><span class="share-text">Google+</span></a></li-->
                                        <li><a class="fi-social-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?= $base_url; ?>event/<?= $bID; ?>/{{id}}/{{url name}}&t={{name}}" target="_blank"><i class="fa fa-facebook"></i><span class="share-text">Like It</span></a></li>
                                    </ul>
                                </div>
                                <div class="event-details-back columns medium-5">
                                    <a href="/special-pages/events/events-series-two" class="event-back button"><i class="fa fa-chevron-left"></i>View All Events</a>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <a href="/special-pages/events/events-series-two" class="event-back close"><span class="show-for-sr">Close</span></a>
        </div>
    </script>

    <?php if (!$permalink) { ?>
        <div class="scrollToTop">
            <button class="scrolltotop-back" aria-label="Back to Top"><svg xmlns="http://www.w3.org/2000/svg" class="svg-arrow" width="13" height="25" viewBox="0 0 13.661 25" aria-labelledby="back-top"><title id="back-top">Back to Top</title><path class="svg-fill" d="M13.66 12.49L1.217 0 0 .302 12.153 12.5 0 24.698 1.216 25 13.66 12.51l-.04-.01"></path></svg></button>
        </div>
    <? } ?>

</div>

<?php if ($layoutType == 1) { ?>
    <script type="text/javascript">
        var layoutType = 'calendar';
        $(document).ready(function(){
            if (!(Cookies.get('toggle')) && (Cookies.get('toggle') != layoutType)) {
                Cookies.set('toggle', layoutType);
            }
        });
    </script>
<?php } ?>

<?php } ?>
