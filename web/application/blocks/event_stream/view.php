<?php use Concrete\Core\Tree\Node\Node;

defined('C5_EXECUTE') or die('Access Denied.');

$c = Page::getCurrentPage();
$cID = $c->getCollectionID();
if (is_object($c) && $c->isEditMode()) {
    ?>
    <div class="ccm-edit-mode-disabled-item"><?= t('Event Stream') ?></div>
<?php
} else {
    ?>
    <div class="palmetto-event-stream unbound">

        <script type="text/html" class="stream-group-template">
            <div class="stream-event-group">
                <div class="stream-event-group-title">
                    <%- group.getTitle() %>
                </div>
                <div class="stream-event-group-container">
                </div>
            </div>
        </script>

        <script type="text/html" class="stream-item-template">
            <div class="stream-event-item">
                <div class="stream-event-item-header">
                    <div class="stream-event-item-title">
                        <div class="stream-event-item-title-text">
                            <a href="<%= event.getFullURL() %>">
                                <span class="stream-event-item-name">
                                    <%- event.name %>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="stream-event-item-content">
                    <div class="stream-event-item-description">
                        <% if (event.image) { %>
                        <div class="stream-event-item-image">
                            <img src="<%= event.image %>"/>
                        </div>
                        <% } %>
                        <div class="stream-event-item-info<%= event.image ? "" : " no-img" %>">
                            <p>
                                Date(s): <%- event.getDates() %>
                            </p>

                            <p>
                                Time: <%- event.getTime() %>
                            </p>

                            <p>
                                Location: <%- event.location %>
                            </p>

                            <p>
                                <%= event.extra ? event.extra : "&nbsp;" %>
                            </p>

                            <p class="stream-event-item-description-text">
                                <%= event.description %>
                            </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="share">
                        <ul>
                            <li class="share-email">
                                <a href="mailto:?subject=<%= event.name %>&amp;body=<%= event.getFullURL() %>" class="track" name="share:email:<%= event.getFullURL() %>">
                                    <em class="alt">Share</em>
                                </a>
                            </li>
                            <li class="share-google">
                                <div class="g-plusone" data-size="medium" data-href="<%= event.getFullURL() %>"></div>
                            </li>
                            <li class="share-twitter">
                                <a class="twitter-share-button" data-url="<%= event.getFullURL() %>" href="https://twitter.com/share">Tweet</a>
                            </li>
                            <li class="share-facebook">
                                <iframe src="//www.facebook.com/plugins/like.php?href=<%= event.getFullURL() %>&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=511361785582687" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </script>

        <div class="stream-sidebar">
            <div class="stream-calendar-wrapper">
                <div class='stream-calendar unbound'>
                    <div class='header'>
                        <button class='previous'>Previous</button>
                        <select class='date-select'></select>
                        <button class='next'>Next</button>
                        <div class="clearfix"></div>
                    </div>
                    <div class='calendar'></div>
                </div>
            </div>
            <? if ($filterByTopicAttributeKeyID && count($filterTopics)) { ?>
                <?php
                $filtering = false;
                foreach ($filterTopics as $topic) {
                    if (isset($filterByTopicIDSelectedInUI) && $filterByTopicIDSelectedInUI == $topic->getTreeNodeID()) {
                        $filtering = true;
                        break;
                    }
                }
                ?>
                <br/><br/>

				<div class="mobile-filters">
					<h2>Filter By</h2>
					<select name="mobile-select" id="mobile-select">
						<?php foreach ($filterTopics as $topic) { ?>
							<option value="<?= $view->controller->getTopicLink($topic) ?>"<? if (isset($filterByTopicIDSelectedInUI) && $filterByTopicIDSelectedInUI == $topic->getTreeNodeID()) { print " selected"; } ?>><?= $topic->getTreeNodeDisplayName() ?></option>
						<?php } ?>
					</select>
				</div>

				<div id="filter-wrapper">
                <h1>Filter</h1>

                <div class="stream-topics">
                    <div class="stream-topic stream-topic-all <?= !$filtering && !$date ? 'active' : '' ?>">
                        <a href="<?= $view->action('') ?>">All Events</a>
                    </div>

                    <?php
                    /** @var Node $topic */
                    foreach ($filterTopics as $topic) {
                        $handle = trim(
                            preg_replace(
                                '/[-]+/',
                                '-',
                                preg_replace('/[^a-z]/', '-', strtolower($topic->getTreeNodeDisplayName('plain')))),
                            '-');
                        if (isset($filterByTopicIDSelectedInUI) && $filterByTopicIDSelectedInUI == $topic->getTreeNodeID()) {
                            ?>
                            <div class="stream-topic stream-topic-<?= $handle ?> active">
                                <span><?= $topic->getTreeNodeDisplayName() ?></span>
                            </div>
                        <?php
                        } else {
                            ?>
                            <div class="stream-topic stream-topic-<?= $handle ?>">
                                <a href="<?= $view->controller->getTopicLink($topic) ?>">
                                    <?= $topic->getTreeNodeDisplayName() ?>
                                </a>
                            </div>
                        <?php
                        }
                        ?>

                    <? } ?>
                    </div>
                </div>
                <? } ?>

                <?php
                    if ($cID !=='271') {
                ?>
                        <a class="sidebar-rss-icon" style="margin-top: 20px;" href="/rss"><img src="http://www.palmettodunes.com/application/files/2714/2723/6329/icon-rss.gif" /></a>
                <?php
                    }
                ?>
        </div>
        <div class="stream-stream">
            <? if (!$expanded) { ?>
            <div class="stream-title">&nbsp;</div>
            <div class="stream-prev"></div>
            <? } ?>
            <div class="stream-container">
                <div class="spacer">&nbsp;</div>
            </div>
            <? if (!$expanded) { ?>
            <div class="stream-next"></div>
            <? } ?>
        </div>

        <script>
            window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));
        </script>
    </div>

    <script>
        (function () {

            if (typeof $ != 'undefined' && typeof _ != 'undefined') {
                streamize();
            } else {
                var interval = setInterval(function () {
                    if (typeof $ != 'undefined' && typeof _ != 'undefined') {
                        clearInterval(interval);
                        streamize();
                    }
                }, 20);
            }

            var bID = <?= $bID ?>,
                date = "<?= $date ?>",
                occupied = JSON.parse(<?= json_encode(json_encode($occupied)) ?>),
                occupied_link = '<?= \URL::to('/ccm/event_stream/get_occupied_dates') ?>',
                base_url = '<?= $c->getPageController()->action('') ?>';

            function streamize() {
                var element = $('div.palmetto-event-stream.unbound').removeClass('unbound'),
                    initial = <?= $expanded ?>,
                    group_template = _.template(element.children('.stream-group-template').html()),
                    item_template = _.template(element.children('.stream-item-template').html()),
                    stream_container = element.children('.stream-stream'),
                    stream_sidebar = element.children('.stream-sidebar'),
                    container = stream_container.children('.stream-container'),
                    next = stream_container.children('.stream-next'),
                    previous = stream_container.children('.stream-prev'),
                    seed = JSON.parse(<?= json_encode(json_encode($items)) ?>),
                    groups = {},
                    list_uri = '<?= \URL::to('/ccm/event_stream/get_occurrences') ?>',
                    events = $('<div />'),
                    title_container = stream_container.children('.stream-title');

                        function Group() {
                            return this.init.apply(this, Array.prototype.slice.call(arguments));
                        };

                Group.prototype = {

                    init: function (occurrence, template) {
                        this.rootOccurrence = occurrence;
                        this.before = null;
                        this.after = null;
                        this.element = $(template({group: this}));
                        this.container = this.element.children('.stream-event-group-container');
                        this.container.append(occurrence.getElement());
                    },

                    getStart: function () {
                        return this.rootOccurrence.getStart();
                    },

                    getElement: function () {
                        return this.element;
                    },

                    getTitle: function () {
                        var start = this.rootOccurrence.getStart();
                        return start.month + " " + start.day;
                    },

                    /**
                     * @param {Occurrence} occurrence
                     */
                    addOccurrence: function (occurrence) {
                        occurrence.group = this;

                        if (!this.rootOccurrence) {
                            this.rootOccurrence = occurrence;
                            this.element.append(occurrence.getElement());
                        } else {
                            var current = this.rootOccurrence,
                                occurrence_start = occurrence.getStart().time;

                            while (current) {
                                if (occurrence.id == current.id) {
                                    break;
                                }
                                if (occurrence_start < current.getStart().time) {
                                    occurrence.before = current.before;
                                    occurrence.after = current;
                                    current.before = occurrence;

                                    current.getElement().before(occurrence.getElement());
                                    break;
                                } else if (!current.after) {
                                    current.after = occurrence;
                                    occurrence.before = current;

                                    current.getElement().after(occurrence.getElement());
                                    break;
                                }

                                current = current.after;
                            }
                        }
                    },

                    getOffset: function () {
                        return this.getElement().offset().top - container.offset().top + container.scrollTop();
                    }
                };

                function Occurrence() {
                    return this.init.apply(this, Array.prototype.slice.call(arguments));
                }

                Occurrence.prototype = {
                    init: function (data, template) {
                        this.id = data.id;

                        this.start = data.start;
                        this.end = data.end;

                        this.name = data.name;
                        this.description = data.description;
                        this.topic = data.topic;
                        this.location = data.location;
                        this.extra = data.extra;
                        this.image = data.image;
                        this.before = null;
                        this.after = null;
                        this.element = $(template({event: this}));

                        this.expanded = false;
                        this.sliding = false;
                        this.group = null;
                        this.stream = null;
                        this.slide_element = this.element.find('.stream-event-item-content');

                        var when = function(test, callback) {
                            var _do = function() {
                                if (test()) {
                                    return callback();
                                } else {
                                    _.defer(function(){
                                        _do();
                                    }, 10);
                                }
                            };
                            return _do();
                        };

                        var me = this;
                        this.permalink = this.element.find('.stream-event-item-header a').click(function (e) { e.preventDefault(); });
                        this.header = this.element.find('.stream-event-item-header').click(function () {
                            if (me.expanded) {
                                me.slide_element.slideUp();
                                me.element.removeClass('active');
                            } else {
                                me.slide_element.slideDown(function () {
                                    _.defer(function () {
                                        if (!me.isFullyVisible()) {
                                            me.stream.scrollTo(me, 500);
                                        }
                                    });
                                });
                                me.element.addClass('active');

                                events.trigger('event_expand', [me]);
                            }
                            me.expanded = !me.expanded;
                        });
                        events.bind('event_expand', function (event, occurrence) {
                            me.initSharing();

                            if (occurrence != me && me.expanded) {
                                if (me.isVisible()) {
                                    me.header.click();
                                } else {
                                    me.expanded = false;
                                    me.slide_element.slideUp(0);
                                    me.element.removeClass('active');
                                    me.stream.scrollTo(me.stream.currentOccurrence);
                                }
                            }
                        });
                    },

                    getStart: function () {
                        return this.start;
                    },

                    getElement: function () {
                        return this.element;
                    },

                    getSanitizedName: function() {
                        return this.name.toLowerCase().replace(/[^a-z ]/gi, '').replace(/\s/g, '+');
                    },

                    getFullURL: function() {
                        return ['<?= $view->action('event') ?>', this.id, this.getSanitizedName()].join('/');
                    },

                    initSharing: function () {
                        if (typeof twttr.widgets != 'undefined') {
                            twttr.widgets.load();
                        } else {
                            var interval = setInterval(function () {
                                if (typeof twttr.widgets != 'undefined') {
                                    clearInterval(interval);
                                    twttr.widgets.load();
                                }
                            }, 20);
                        }
                        if (typeof gapi != 'undefined') {
                            gapi.plusone.go();
                        } else {
                            var interval = setInterval(function () {
                                if (typeof gapi != 'undefined') {
                                    clearInterval(interval);
                                    gapi.plusone.go();
                                }
                            }, 20);
                        }
                    },

                    getDates: function () {
                        if (this.start.month == this.end.month &&
                            this.start.day == this.end.day &&
                            this.start.year == this.end.year) {
                            return this.start.month + " " + this.start.day + ", " + this.start.year
                        }

                        return this.start.month + " " + this.start.day + ", " + this.start.year + " - " +
                            this.end.month + " " + this.end.day + ", " + this.end.year
                    },
                    getTime: function () {
						console.log((this.start.hour+this.start.minute+this.start.meridiem) == (this.end.hour+this.end.minute+this.end.meridiem));
						if ( (this.start.hour+this.start.minute+this.start.meridiem) == (this.end.hour+this.end.minute+this.end.meridiem) ) {
							return "all day";
						} else {
							return [this.start.hour, ':', this.start.minute, ' ', this.start.meridiem, ' - ',
								this.end.hour, ':', this.end.minute, ' ', this.end.meridiem].join('');
						}
                    },

                    getOffset: function () {
                        return this.getElement().offset().top - container.offset().top + container.scrollTop();
                    },

                    isVisible: function () {
                        var offset_min = this.getOffset(),
                            offset_max = offset_min + this.getElement().height(),
                            min = container.scrollTop(),
                            max = min + container.height();

                        if (offset_max >= min && offset_max <= max) {
                            return true;
                        }
                        return !!(offset_min >= min && offset_min <= max);
                    },

                    isFullyVisible: function () {
                        var offset_min = this.getOffset(),
                            offset_max = offset_min + this.getElement().height(),
                            min = container.scrollTop(),
                            max = min + container.height();

                        if (offset_max >= min && offset_max <= max &&
                            offset_min >= min && offset_min <= max) {
                            return true;
                        }
                        return false;
                    }

                };

                function Stream() {
                    return this.init.apply(this, Array.prototype.slice.call(arguments));
                }

                Stream.prototype = {

                    init: function (container, next_element, previous_element) {
                        this.container = container;
                        this.next = next_element;
                        this.previous = previous_element;
                        this.rootGroup = null;
                        this.hasLess = false;
                        this.hasMore = true;
                        this.requesting = false;
                        this.scrolling = false;
                        this.currentOccurrence = null;

                        this.previous.addClass('disabled');

                        this.bindEvents();
                    },

                    pushState: function (occurrence) {
                        var base_uri = '<?= $view->action('event') ?>',
                            uri = [base_uri, occurrence.id, occurrence.getSanitizedName()].join('/');

                        if (history && this.currentOccurrence != occurrence) {
                            history.pushState({
                                    occurrence: occurrence.id
                                },
                                occurrence.name,
                                uri);
                        }
                    },

                    bindEvents: function () {
                        var me = this;

                        this.next.click(function () {
                            var group, occurrence, first = true;

                            if (me.requesting || me.scrolling) {
                                return;
                            }

                            me.hasLess = true;
                            me.previous.removeClass('disabled');

                            if (me.hasMore) {
                                me.requesting = true;

                                group = me.currentOccurrence.group;
                                while (group.after) {
                                    group = group.after;
                                }

                                occurrence = group.rootOccurrence;
                                while (occurrence.after) {
                                    occurrence = occurrence.after;
                                }

                                var new_items = null,
                                    params = {
                                        occurrence_id: occurrence.id,
                                        bID: bID,
                                        topic: <?php if (isset($filterByTopicIDSelectedInUI)) { echo $filterByTopicIDSelectedInUI; } else { echo "null"; } ?>
                                    };

                                $.getJSON(list_uri, params, function (data) {
                                    me.requesting = false;

                                    if (data.error) {
                                        me.hasMore = false;
                                        return;
                                    }

                                    if (!me.scrolling) {
                                        me.addRawOccurrences(data.params);
                                    } else {
                                        new_items = data.params;
                                    }
                                }).fail(function () {
                                    me.hasMore = false;
                                    me.requesting = false;
                                });
                            }

                            if (me.rootGroup) {
                                group = me.getGroupForOccurrence(me.currentOccurrence);
                                occurrence = me.currentOccurrence;

                                while (group) {
                                    if (first) {
                                        occurrence = me.currentOccurrence;
                                        first = false;
                                    } else {
                                        occurrence = group.rootOccurrence;
                                    }

                                    while (occurrence) {
                                        if (!occurrence.isFullyVisible() && occurrence !== me.currentOccurrence) {
                                            $(container).find('.active').removeClass('active').find('.stream-event-item-content').slideUp(0);
                                            me.scrollTo(occurrence, 1000, function () {
                                                if (new_items) {
                                                    me.addRawOccurrences(new_items);
                                                }
                                            });
                                            return;
                                        }

                                        occurrence = occurrence.after;
                                    }

                                    group = group.after;
                                }
                            }

                        });


                        this.previous.click(function () {
                            var group, occurrence, first = true;
                            if (me.requesting || me.scrolling || me.hasLess == false) {
                                return;
                            }

                            $(container).find('.active').removeClass('active').find('.stream-event-item-content').slideUp(0);

                            var max = container.scrollTop(),
                                min = max - container.height();

                            if (min > 0) {
                                var totalOffset = previous.offset().top + 48;
                                $(container).animate({ scrollTop: container.scrollTop() - 323}, 1000, function() {
                                    $(container).find('.stream-event-item').each(function() {
                                        if ($(this).offset().top > totalOffset) {
                                            $(container).animate({ scrollTop: container.scrollTop() + ($(this).offset().top - totalOffset)});
                                            $(this).find('.stream-event-item-header').trigger('click');
                                            return false;
                                        }
                                    });
                                });
                                
                                return;
                            }

                            if (me.hasLess) {
                                me.requesting = true;

                                var params = {
                                    occurrence_id: me.rootGroup.rootOccurrence.id,
                                    direction: 'desc',
                                    bID: bID
                                };
                                if (date) {
                                    params["pd_stream" + bID + "_date"] = date;
                                }

                                var new_items = null;
                                $.getJSON(list_uri, params, function (data) {
                                    me.requesting = false;
                                    if (data.error || data.params.length < 2) {
                                        me.hasLess = false;
                                        me.previous.addClass('disabled');
                                        return;
                                    }
                                    if (!me.scrolling) {
                                        me.addRawOccurrences(data.params);
                                    } else {
                                        new_items = data.params;
                                    }
                                }).fail(function () {
                                    me.hasLess = false;
                                    me.previous.addClass('disabled');
                                    me.requesting = false;
                                });
                            }

                            if (me.rootGroup) {
                                occurrence = me.currentOccurrence;
                                group = occurrence.group;

                                while (group) {
                                    var group_top = group.getOffset(),
                                        group_bottom = group_top + group.getElement().height();

                                    if (group_top < min) {
                                        var item = group.rootOccurrence;

                                        while (item.after) {
                                            if (item.getOffset() >= min) {
                                                return me.scrollTo(item, 1000, function () {
                                                    if (new_items) {
                                                        me.addRawOccurrences(new_items);
                                                    }
                                                });
                                            }
                                            item = item.after;
                                        }

                                    }
                                    group = group.before;
                                }
                            }

                            if (me.currentOccurrence != me.rootGroup.rootOccurrence) {
                                return me.scrollTo(me.rootGroup.rootOccurrence, 1000, function () {
                                    if (new_items) {
                                        me.addRawOccurrences(new_items);
                                    }
                                });
                            }


                        });
                    },

                    getTitle: function () {
                        if (!this.currentOccurrence) {
                            return '';
                        }
                        var occurrence = this.currentOccurrence,
                            first_group = occurrence.group,
                            last_group;

                        var group = first_group.after;
                        while (group && group.rootOccurrence.isFullyVisible()) {
                            if (group.after) {
                                group = group.after;
                            } else {
                                break;
                            }
                        }

                        if (group) {
                            last_group = group.before;
                        } else {
                            last_group = first_group;
                        }


                        if (first_group.getStart()) {
                            return first_group.getStart().month + ' ' + first_group.getStart().year;
                        }
                        return "Events";
                        /**
                        if (last_group == first_group) {
                            return first_group.getTitle();
                        }

                        return first_group.getTitle() + ' - ' + last_group.getTitle();
                         **/
                    },

                    scrollTo: function (occurrence, duration, callback) {
                        if (!occurrence) return;

                        callback = callback || $.noop;
                        var me = this,
                            top = occurrence.getOffset(),
                            group = this.getGroupForOccurrence(occurrence);

                        if (group.rootOccurrence == occurrence) {
                            top = group.getOffset();
                        }

                        this.currentOccurrence = occurrence;
                        if (!occurrence.element.hasClass('active'))
                            occurrence.header.trigger('click');
                        if (!duration) {
                            this.container.scrollTop(top);
                        } else {
                            this.scrolling = true;
                            this.container.animate({
                                scrollTop: top
                            }, duration, function () {
                                me.scrolling = false;
                                me.updateTitle();
                                me.updateCalendar();
                                callback.apply(this, Array.prototype.slice.call(arguments));
                            });
                        }
                    },

                    updateTitle: function () {
                        title_container.text(this.getTitle());
                    },

                    updateCalendar: function() {
                        if (this.currentOccurrence && this.calendar) {
                            var occurrence = this.currentOccurrence,
                                group = occurrence.group,
                                date = new Date(group.getStart().time * 1000),
                                year = date.getYear() + 1900,
                                month = date.getMonth();

                            if (this.calendar.month != month || this.calendar.year != year) {
                                this.calendar.month = month;
                                this.calendar.year = year;
                                this.calendar._render();
                                this.calendar.fillSelect(month, year);
                            }
                        }
                    },

                    /**
                     * @param {Occurrence} occurrence
                     */
                    addOccurrence: function (occurrence) {
                        var group = this.rootGroup, item;
                        while (group) {
                            item = group.rootOccurrence;
                            while (item) {
                                if (occurrence.id == item.id) {
                                    return;
                                }

                                item = item.after;
                            }

                            group = group.after;
                        }

                        occurrence.stream = this;

                        if (!this.currentOccurrence) {
                            this.currentOccurrence = occurrence;
                        }
                        var group = this.getGroupForOccurrence(occurrence);

                        group.addOccurrence(occurrence);
                    },

                    addRawOccurrences: function (occurrences) {
                        var stream = this, selected_id = initial, selected;
                        if (date) {
                            stream.hasLess = true;
                            stream.previous.removeClass('disabled');
                        }
                        _(occurrences).each(function (data) {
                            var occurrence = new Occurrence(data, item_template);
                            if (selected_id) {
                                // permalink view
                                if (occurrence.id == selected_id) {
                                    selected = occurrence;
                                    selected.stream = stream;
                                    selected.group = stream.getGroupForOccurrence(selected);
                                    selected.element.addClass("active");
                                    selected.slide_element.slideDown();
                                    selected.expanded = true;
                                    stream.addOccurrence(selected);
                                }
                            } else {
                                stream.addOccurrence(occurrence);
                            }
                        });
                        if (selected) {
                            selected.initSharing();
                        } else {
                            this.scrollTo(this.currentOccurrence);
                        }
                    },

                    getGroupForOccurrence: function (occurrence) {
                        if (!this.rootGroup) {
                            this.rootGroup = new Group(occurrence, group_template);
                            this.container.prepend(this.rootGroup.getElement());
                            return this.rootGroup;
                        }

                        if (occurrence.group) {
                            return occurrence.group;
                        }

                        var current = this.rootGroup,
                            start_time = occurrence.getStart().day_time,
                            group;

                        while (current) {
                            if (start_time == current.getStart().day_time) {
                                return current;
                            }
                            if (start_time < current.getStart().day_time) {
                                group = new Group(occurrence, group_template);

                                group.before = current.before;
                                group.after = current;
                                current.before = group;

                                current.getElement().before(group.getElement());

                                if (!group.before) {
                                    this.rootGroup = group;
                                }

                                return group;
                            } else if (!current.after) {
                                group = new Group(occurrence, group_template);
                                current.after = group;
                                group.before = current;

                                current.getElement().after(group.getElement());

                                return group;
                            }

                            current = current.after;
                        }

                    }
                };

                var stream = new Stream(container, next, previous);
                stream.addRawOccurrences(seed);
                stream.updateTitle();

                if (!stream.rootGroup) {
                    container.text('There are currently no events available');
                }

                stream.calendar = calendarize();
            }


            function calendarize() {

                var calendar;

                /** CALENDAR **/
                (function (global, $) {
                    function Calendar(container, month, year) {
                        this.element = container;
                        this.month = month;
                        this.year = year;

                    }

                    Calendar.prototype = {

                        render: function (href_callback) {
                            href_callback = href_callback || $.noop;

                            var date = this.getDate(),
                                container = $('<div class="pal-cal" />');
                            container.append(this.getTitleElement());


                            while (date.getDay() != 1) {
                                date.setTime(date.getTime() - 86400 * 1000);
                            }

                            do {
                                container.append(this.getWeekElement(date, href_callback));
                                date.setTime(date.getTime() + 86400 * 7000);
                            } while (date.getMonth() == this.month);

                            this.element.empty().append(container);
                        },

                        getDayElement: function (start, href_callback) {
                            var elem = $('<div class="pal-cal-day" />'),
                                href = href_callback.call(this, start);
                            elem.append(href);

                            if (start.getMonth() == this.month) {
                                elem.addClass('active');
                            }

                            if (date) {
                                var date_object = new Date(date);
                                if (start.getMonth() == date_object.getMonth() &&
                                    start.getDate() == date_object.getDate() &&
                                    start.getFullYear() == date_object.getFullYear()) {
                                    elem.addClass('selected');
                                }
                            }

                            return elem;
                        },

                        getTitleElement: function () {
                            var elem = $("<div class='pal-cal-header' />"),
                                day_elem = $('<div class="pal-cal-day-header" />');

                            elem.append(day_elem.clone().addClass('monday').text('Mon'));
                            elem.append(day_elem.clone().addClass('tuesday').text('Tue'));
                            elem.append(day_elem.clone().addClass('wednesday').text('Wed'));
                            elem.append(day_elem.clone().addClass('thursday').text('Thu'));
                            elem.append(day_elem.clone().addClass('friday').text('Fri'));
                            elem.append(day_elem.clone().addClass('saturday').text('Sat'));
                            elem.append(day_elem.clone().addClass('sunday').text('Sun'));

                            return elem;
                        },

                        getWeekElement: function (start, href_callback) {
                            start = new Date(start.getTime());
                            while (start.getDay() != 1) {
                                start.setTime(start.getTime() - 86400 * 1000);
                            }

                            var container = $('<div class="pal-cal-week" />'), i = 7;
                            while (i--) {
                                container.append(this.getDayElement(start, href_callback));
                                start.setTime(start.getTime() + 86400 * 1000);
                            }

                            return container;
                        },

                        getDate: function () {
                            var date = new Date(this.year, this.month);
                            date.setTime(date.getTime() + 60 * 60000 * 5);
                            return date;
                        }

                    };

                    $.fn.palmettoCalendar = function (month, year) {
                        return new Calendar($(this), month, year);
                    };
                }(this, jQuery));
                (function () {
                    var my_calendar = $('div.stream-calendar.unbound').removeClass('unbound');

                    var month_names = [
                            'January',
                            'February',
                            'March',
                            'April',
                            'May',
                            'June',
                            'July',
                            'August',
                            'September',
                            'October',
                            'November',
                            'December'
                        ],
                        calendar_elem = my_calendar.find('.calendar'),
                        selector = my_calendar.find('select.date-select'),
                        previous = my_calendar.find('button.previous'),
                        next = my_calendar.find('button.next'),
                        now = date ? new Date(date) : new Date(),
                        month = now.getMonth(),
                        year = now.getFullYear(),
                        i = 24, elem;


                    calendar = calendar_elem.palmettoCalendar(month, year);

                    calendar._render = function() {
                        calendar.render(function (start) {
                            var url = base_url + '?pd_stream' + bID + '_date=' + [start.getMonth() + 1, start.getDate(), start.getFullYear()].join('/');
                            var link = $('<a />').attr('href', url).text(start.getDate());
                            link.attr('data-year', start.getFullYear())
                                .attr('data-month', start.getMonth())
                                .attr('data-day', start.getDate());
                            return link;
                        });

                        if (!occupied[calendar.year]) {
                            occupied[calendar.year] = [];
                            $.getJSON(occupied_link, {
                                year: calendar.year
                            }, function (data) {
                                if (!data.error) {
                                    _(data.params).each(function (arr, year) {
                                        occupied[year] = arr;
                                    });
                                    showOccupied();
                                }
                            });
                        } else {
                            showOccupied();
                        }
                    };

                    function showOccupied() {
                        var year = occupied[calendar.year], month;
                        if (!year) {
                            return;
                        }

                        month = year[calendar.month + 1];
                        if (!month) {
                            return;
                        }

                        $('.pal-cal-day.active>a', calendar_elem).attr('title', '0 events');
                        _(month).each(function (amount, day) {
                            $('.pal-cal-day>a[data-year="' + calendar.year + '"]' +
                            '[data-month="' + calendar.month + '"]' +
                            '[data-day="' + day + '"]', calendar_elem).parent().addClass('occupied').children().attr('title', amount + ' event' + (amount != 1 ? 's' : ''));
                        });

                    }

                    calendar.fillSelect = function(month, year) {
                        selector.empty();
                        var i = 12;
                        while (i--) {
                            elem = $('<option>' + month_names[month] + ' ' + year + '</option>').data('month', month).data('year', year);
                            selector.append(elem);
                            month++;

                            if (month > 11) {
                                month = 0;
                                year++;
                            }
                        }
                    };

                    calendar._render();
                    calendar.fillSelect(month, year);

                    selector.change(function () {
                        var selected = selector.children(':selected');
                        calendar.month = selected.data('month');
                        calendar.year = selected.data('year');

                        calendar._render();

                        var month = calendar.month + 1;
                        if (month < 10) {
                            month = '0' + month;
                        }

                        window.location = base_url + '?pd_stream' + bID + '_date=' + month + '/01/' + calendar.year;
                    });

                    previous.click(function () {
                        calendar.month -= 1;
                        if (calendar.month < 0) {
                            calendar.month = 11;
                            calendar.year--;
                        }

                        calendar.fillSelect(calendar.month, calendar.year);
                        calendar._render();

                        var month = calendar.month + 1;
                        if (month < 10) {
                            month = '0' + month;
                        }

                        window.location = base_url + '?pd_stream' + bID + '_date=' + month + '/01/' + calendar.year;

                        return false;
                    });

                    next.click(function () {
                        calendar.month++;
                        if (calendar.month > 11) {
                            calendar.month = 0;
                            calendar.year++;
                        }

                        calendar.fillSelect(calendar.month, calendar.year);
                        calendar._render();

                        var month = calendar.month + 1;
                        if (month < 10) {
                            month = '0' + month;
                        }

                        window.location = base_url + '?pd_stream' + bID + '_date=' + month + '/01/' + calendar.year;

                        return false;
                    });

                }());

                return calendar;
            }

        }());

		$(document).ready(function(){
			$('#mobile-select').change(function(){
				window.location = $(this).val();
			});
		});
    </script>
<?php
}
