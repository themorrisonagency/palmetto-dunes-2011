<?php   defined("C5_EXECUTE") or die("Access Denied."); ?>
<div class="menu-pushes-wrapper">
	<? // Photo-Based Push ?>
	<div class="menu-push-overview">
		<div class="menu-push-inset">
			<?php if ($pushimg){ ?>
    			<a href="<? print $photolink; ?>"><img src="<?php  echo $pushimg->getURL(); ?>" alt="<?php  echo $pushimg->getTitle(); ?>"/></a><?php  } ?>
    		<div class="menu-push-title">
				<span><a href="<? print $photolink; ?>"><? print $photolinktitle; ?></a></span><a href="<? print $photolink; ?>" class="orange-btn">View</a>
			</div>
    	</div>
		<?php if (isset($description) && trim($description) != ""){ ?>
    	<div class="menu-push-description"><?php   echo $description; ?></div><?php   } ?>
    </div>

	<? // Do we show the booking widget ($showwidget == '1' ?) 
		if ($showwidget === '1') { 
	?>
		<div class="menu-widget">
			<form name="reservations:console" id="menu-console-golf" action="https://bookit.activegolf.com/book-palmetto-dunes-public-tee-times/69" class="track validate inline console reservations-console auto-set-dates" target="_blank">
				<input type="hidden" name="c" value="7734af81af83b113">
				<h3 class="header-reservations">Search Tee Times</h3>
				<fieldset>
					<div class="field dropdown full-field">
						<label for="courses">Select a Course:</label>
						<select id="courses" name="CourseId">
							<option name="Robert Trent Jones" value="91">Robert Trent Jones</option>
							<option name="George Fazio" value="89">George Fazio</option>
							<option name="Arthur Hills" value="88">Arthur Hills</option>
						</select>
					</div>
					<div class="field dropdown split-field players-field">
						<label for="players-golf">Players:</label>
						<select name="players" id="players-golf">
							<option value="1">1</option>
							<option value="2" selected="selected">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
						</select>
					</div>
					<div class="field split-field date-of-play-field">
						<label for="arrivalGolf" class="ie-icon-required">Date of Play: 
						<input type="text" name="Date" id="arrivalGolf" maxlength="10" class="date-picker-console textfield required golf-menu-date-begin hasDatepicker" placeholder="" value="" />
						<img id="golf-menu-button-arrive" class="ui-datepicker-trigger date-picker" src="/application/themes/theme_palmetto/images/calendar.svg" alt="mm/dd/yyyy" title="mm/dd/yyyy" width="28" height="26">
						</label>
					</div>
					<div class="field full-field">
						<label for="promo-codeGolf">Promo Code:</label>
						<input type="text" name="promo" id="promo-codeGolf" placeholder="" value="" class="medium textfield" />
					</div>
					<div class="buttons">
						<input type="submit" class="submit btn btn-primary orange-btn" value="Check Tee Times" />
					</div>
				</fieldset>
			</form>
		</div>
	<? 
		} else {
		// Otherwise, show the Text-Based Push
	?>


	    <div class="menu-push-offer">
	    	<div class="menu-push-box">
				<div class="push-content">
				<?php  if (isset($title) && trim($title) != ""){ ?>
			    	<h2><?php  echo h($title); ?></h2><?php  } ?>

				<?php   if (isset($text) && trim($text) != ""){ ?>
			    	<?php   echo $text; ?><?php   } ?>

				<?php   if (!empty($linkURL) && !empty($fID)) {
					echo "<a href='" . View::url('/download_file', $controller->getFileID(),$cID) ."' target='_blank'>" . (trim($linktext) != "" ? $linktext : t("Learn More")) . "</a>";
						} elseif (!empty($linkURL)) {
				    echo "<a href='$linkURL'>" . (trim($linktext) != "" ? $linktext : t("Learn More")) . "</a>";
				} ?>
				</div>
			</div>
		</div>
	<? } ?>
</div>