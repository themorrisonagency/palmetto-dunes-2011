<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="overview-reviews">
	<div class="cycle-wrap">
		<ul class="cycle-slideshow" data-cycle-slides="li" data-cycle-timeout="0" data-cycle-prev="#weddings-prev" data-cycle-next="#weddings-next" data-cycle-pager=".cycle-pager" style="position: relative; margin: 0 auto 40px;">
			<?php foreach ($rows as $row){ ?>
				<li class="cycle-slide">
					<div class="push-wrapper">
						<div class="push-inset">
							<img src="<?php if (File::getByID($row['img'])) { echo File::getByID($row['img'])->getRelativePath(); } ?>" width="196" height="197"/>
						</div>
						<div class="push-inner-wrapper">
							<div class="push-inner-title">
								<h3><?php echo $row['title']; ?></h3>
							</div>
							<div class="push-inner-copy">
								<?php echo $row['descr']; ?>
							</div>
							<div class="push-inner-link">
								<a target="_blank" href="<?php echo $row['readMoreLink']; ?>"><?php echo $row['linkText']; ?></a>
							</div>
						</div>
					</div>
				</li>
			<?php } ?>
		</ul>
		<a href="#" id="weddings-prev"><em class="alt">Prev</em></a>
		<a href="#" id="weddings-next"><em class="alt">Next</em></a>
		<div class="cycle-pager"></div>
	</div>
</div>