<?php use Concrete\Core\Tree\Node\Node;

defined('C5_EXECUTE') or die('Access Denied.');

$c = Page::getCurrentPage();
$cID = $c->getCollectionID();
$nh = Loader::helper('navigation');
$cpl = $nh->getCollectionURL($c);
$shsStreamDate = new DateTime($_GET['shs_stream_date']);
?>
<section class="calendar large-5 columns next-sibling-toggle">
    <h2 class="row">
        <span class="columns small-10 medium-11">
            <span><?php echo t('Calendar'); ?></span><br>
            <?php
                if (explode('/', $_GET['shs_stream_date'])[1] == '01') {
                    echo date_format($shsStreamDate, 'F Y');
                } else {
                    echo date_format($shsStreamDate, 'F d, Y');
                }
            ?>
        </span>

        <a class="columns small-2 medium-1" href="#"><img src="<?php echo $this->getThemePath(); ?>/img/calendar-icon.svg" class="cal-icon" alt="Show calendar" /></a>
    </h2>
    <div class="sibling-togglable pre">
        <ul id="months-list">
            <li class="selected-month">
                <div id="month-wrapper">
                    <table summary="Current Month">
                        <thead>
                            <tr id="months">
                                <th colspan="7" id="current_month_now">
                                    <a id="prevmonth" href="<?php echo $c->getCollectionPath(); ?>?shs_stream_monthview=1&shs_stream_date=<?php echo $previousMonth; ?>"><span><?php echo t('Previous'); ?></span></a>
                                    <a id="nextmonth" href="<?php echo $c->getCollectionPath(); ?>?shs_stream_monthview=1&shs_stream_date=<?php echo $nextMonth; ?>"><span><?php echo t('Next'); ?></span></a>
                                    <form action="">
                                        <label for="calMonthSelector" class="show-for-sr">Select event month</label>
                                        <select id="calMonthSelector">
                                            <?php
                                                foreach ($monthDropDown as $k => $v) {
                                                    $selected = "";
                                                    if (date('m', strtotime($k)) == $thisMonth && date('Y', strtotime($k)) == $thisYear) {
                                                        $selected = " selected='selected'";
                                                    }
                                                    echo '<option value="'.$k.'"'.$selected.'>'.t($v).'</option>';
                                                }
                                            ?>
                                        </select>
                                    </form>
                                </th>
                            </tr>
                            <tr id="days">
                                <?php
                                    foreach ($dayNames as $k=>$name) {
                                        $class = '';
                                        if ($k == 0) {
                                            $class = 'class="first-col"';
                                        }
                                        echo '<th '.$class.'>'.t($name).'</th>';
                                    }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $today = getdate(time());
                                $firstday=true;
                                $d = $firstDayOffset;
                                while ($d <= $daysInMonth) {
                                    echo "<tr";
                                    if($firstday)
                                        echo ' class="first-row" ';
                                    echo ">";

                                    for ($i = 0; $i < 7; $i++) {
                                        $day = $d;
                                        if ($d < 10 && $d > 0) {
                                            $day = '0'.$d;
                                        }
                                        $class = array('daily');
                                        if ($i == 0) {
                                            $class[] = 'first-col';
                                        }
                                        if ($thisYear.'-'.$thisMonth.'-'.$day >= date('Y-m-d', time()) && !empty($occupied[$thisYear][ltrim($thisMonth, 0)][$d])) {
                                            $class[] = 'event-date';
                                        }
                                        
                                        if($thisYear.'-'.$thisMonth.'-'.$day == $selectedDay)
                                            $class[] = 'selected-date';

                                        echo '<td class="'.implode(' ', $class).'">';
                                        if ($d > 0 && $d <= $daysInMonth) {
                                            if (in_array('event-date', $class)) {
                                                $title = 'title="'.$occupied[$thisYear][ltrim($thisMonth, 0)][$d].' event"';
                                                if ($occupied[$thisYear][ltrim($thisMonth, 0)][$d] > 1) {
                                                    $title = 'title="'.$occupied[$thisYear][ltrim($thisMonth, 0)][$d].' events"';
                                                }
                                                echo '<a href="'.$c->getCollectionPath() . '?shs_stream_date=' . $thisMonth .'/'. $day .'/'.$thisYear.'" '.$title.'>'.$d.'</a>';
                                             } else {
                                                echo $d;
                                            }
                                        } else {
                                            echo "&nbsp;";
                                        }
                                        echo "</td>";
                                        $d++;
                                    }
                                    echo "</tr>";
                                    $firstday = false;
                                }                   
                            ?>
                        </tbody>
                    </table>
                </div>
            </li>
        </ul>
    </div>
</section>
<script>
    $("#calMonthSelector").change(function(){
        window.location = "<?php echo $c->getCollectionPath(); ?>?shs_stream_monthview=1&shs_stream_date="+this.value;
    });
</script>
