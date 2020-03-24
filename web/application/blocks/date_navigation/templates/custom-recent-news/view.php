<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="press-list-archives">
	<label>Press Archives:</label>
	
		<select class="press-archives">
			<option value="/hilton-head-resort-recent-news">Select</option>
			<option <?php if ($view->controller->selectedYear == 2017) { echo ' selected="selected" '; } ?> value="/hilton-head-resort-recent-news/2017">2017</option>
			<option <?php if ($view->controller->selectedYear == 2016) { echo ' selected="selected" '; } ?> value="/hilton-head-resort-recent-news/2016">2016</option>
			<option <?php if ($view->controller->selectedYear == 2015) { echo ' selected="selected" '; } ?> value="/hilton-head-resort-recent-news/2015">2015</option>
			<option <?php if ($view->controller->selectedYear == 2014) { echo ' selected="selected" '; } ?> value="/hilton-head-resort-recent-news/2014">2014</option>
			<!--<option <?php if ($view->controller->selectedYear == 2013) { echo ' selected="selected" '; } ?> value="/hilton-head-resort-recent-news/2013">2013</option>
			<option <?php if ($view->controller->selectedYear == 2012) { echo ' selected="selected" '; } ?> value="/hilton-head-resort-recent-news/2012">2012</option>
			<option <?php if ($view->controller->selectedYear == 2011) { echo ' selected="selected" '; } ?> value="/hilton-head-resort-recent-news/2011">2011</option>
			<option <?php if ($view->controller->selectedYear == 2010) { echo ' selected="selected" '; } ?> value="/hilton-head-resort-recent-news/2010">2010</option>-->
		</select>
</div>