<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<style>
	.testimonials-awards .multiList .thumb {
		padding-top: 30px;
	}
</style>

<div class="golf-news">
	<div class="golf-news-inner">
			<h1>Recent News &amp; Press</h1>
			<div class="recent-news">
				<ul class="multiList">
				<?php foreach ($rows as $row){ ?>
					<li>
						<div class="news-item">
							<div class="thumb thumbleft">
								<img src="<?php if (File::getByID($row['img'])) { echo File::getByID($row['img'])->getRelativePath(); } ?>" width="291" height="176"/>
							</div>
							<div class="news-info">
								<div class="news-date"><?php echo $row['articleDate']; ?></div>
								<div class="news-copy">
									<h2><a target="_blank" href="<?php echo $row['readMoreLink']; ?>"><?php echo $row['title']; ?></a></h2>
									<?php echo $row['descr']; ?>
									<div class="news-link">
										<a target="_blank" href="<?php echo $row['readMoreLink']; ?>">READ MORE</a>
									</div>
								</div>
							</div>
						</div>
					</li>
				<?php } ?>
				</ul>
			</div>
			<div class="blog-controls">
				<button type="button" class="b-prev"><em class="alt">prev</em></button>
				<button type="button" class="b-next"><em class="alt">next</em></button>
				<?php if ($displayViewAll) {  ?>
				<a href="<?php echo $viewAllButtonLink; ?>" class="view-blog white-btn"><?php echo t($viewAllButtonText); ?></a>
				<?php } ?>
			</div>
			<div style="width: 100%; height: 1px; clear: both;"></div>
	</div>
</div>