<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="push-expander">
	<div class="push-grid">
		<div class="push-top-row">
			<?php
				$i=0;
				foreach($res_grid as $grid){
					$i++;
					if ($i<=4) {
			?>

					<div id="<?php echo $i; ?>" class="push-expand-wrapper push-expand-<?php echo $i; ?>">
						<a class="trigger" href="#">
							<div class="push-expand-img">
								<img width="250" height="177" alt="<?php echo $grid['img']; ?>" src="<?php if (File::getByID($grid['img'])) { echo File::getByID($grid['img'])->getRelativePath(); } ?>" />
								<div class="push-expand-title"><?php echo $grid['img_title']; ?></div>
							</div>
						</a>
						<div class="push-expand-description">
							<div class="push-expand-left">
								<bodytext>
									<?php echo $grid['descr']; ?>
								</bodytext>
							</div>
							<div class="push-expand-link">
								<a class="blue-btn" href="<?php if ($grid['link_id'] > 0) { $c = Page::getByID($grid['link_id']); echo \Core::make('helper/navigation')->getLinkToCollection($c); } elseif ($grid['link_url'] != '') { echo $grid['link_url']; } ?>"><?php echo $grid['link_text']; ?></a>
							</div>
						</div>
					</div>

			<?php } } ?>
		</div>
		<div class="push-middle-content"></div>
		<div class="push-bottom-row">
			<?php
				$i=0;
				foreach($res_grid as $grid){
					$i++;
					if ($i>4) {
			?>

					<div id="<?php echo $i; ?>" class="push-expand-wrapper push-expand-<?php echo $i; ?>">
						<a class="trigger" href="#">
							<div class="push-expand-img">
								<img width="250" height="177" alt="<?php echo $grid['img']; ?>" src="<?php if (File::getByID($grid['img'])) { echo File::getByID($grid['img'])->getRelativePath(); } ?>" />
								<div class="push-expand-title"><?php echo $grid['img_title']; ?></div>
							</div>
						</a>
						<div class="push-expand-description">
							<div class="push-expand-left">
								<bodytext>
									<?php echo $grid['descr']; ?>
								</bodytext>
							</div>
							<div class="push-expand-link">
								<a class="blue-btn" href="<?php if ($grid['link_id'] > 0) { $c = Page::getByID($grid['link_id']); echo \Core::make('helper/navigation')->getLinkToCollection($c); } elseif ($grid['link_url'] != '') { echo $grid['link_url']; } ?>"><?php echo $grid['link_text']; ?></a>
							</div>
						</div>
					</div>

			<?php } } ?>
		</div>
	</div>
</div>