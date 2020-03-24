<? defined('C5_EXECUTE') or die("Access Denied.");
$c = Page::getCurrentPage();
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
if ($c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item masthead" style="width: <? echo $width; ?>; height: 60px">
        <div style="padding: 10px 0px 10px 0px"><? echo t('Slide-Up Nav disabled in edit mode.')?></div>
    </div>
<?  } else { ?>
<div class="slideup-nav">
	<ul class="tertiary">
		<?php
		$count = count($slides);
		$idx = 1;
		foreach($slides as $slide){ ?>
			<li class="<? echo 'pos'.$idx; if ($count == $idx) { print ' last'; } ?> <?php if ($url == $slide['link']) { print ' current'; } ?>">
				<div class="masthead-popup">
					<div class="masthead-popup-inner">
						<?php if(File::getByID($slide['img'])) { ?>					
						<img width="330" height="137" src="<?php echo File::getByID($slide['img'])->getRelativePath();?>">
						<?php } ?>
						<div class="popup-icon"></div>
						<div class="masthead-popup-title"><?php echo $slide['title']; ?></div>
						<div class="masthead-popup-intro">
							<?php echo $slide['descr']; ?>
							<a href="<?php echo $slide['link']; ?>" class="blue-btn">Learn More</a>
						</div>
					</div>
					<div class="popup-icon"></div>
					<?php 
						if ($slide['link']=='https://www.palmettodunes.com/photos-videos')
							$slide['link']='#gallery';
					?>
					<a href="<?php echo $slide['link']; ?>" id="" class=""><?php echo $slide['title']; ?></a>
				</div>
			</li>
		<?php
			$idx++;
		} ?>
	</ul>
</div>
<?php } ?>