<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="press-list-archives">
	<label>Archive:</label>
	
		<select class="press-archives">
			<option value="/hilton-head-resort-press-releases">Select</option>
			<option <?php if ($view->controller->selectedYear == 2017) { echo ' selected="selected" '; } ?> value="/hilton-head-resort-press-releases/2017">2017</option>
			<option <?php if ($view->controller->selectedYear == 2016) { echo ' selected="selected" '; } ?> value="/hilton-head-resort-press-releases/2016">2016</option>
			<option <?php if ($view->controller->selectedYear == 2015) { echo ' selected="selected" '; } ?> value="/hilton-head-resort-press-releases/2015">2015</option>
			<option <?php if ($view->controller->selectedYear == 2014) { echo ' selected="selected" '; } ?> value="/hilton-head-resort-press-releases/2014">2014</option>
		 
		</select>
</div>