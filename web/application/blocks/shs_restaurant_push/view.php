<?php foreach ($rows as $row) { ?>
<div class="big-jim-push">
	<div class="big-jim-inner">
		<div class="big-jim-wrap">
			<div class="big-jim-left">
				<? 
					$imageFile = File::getByID($row['push_img']);
					if ($imageFile instanceof \File) {
						$relpath = $imageFile->getRelativePath(); ?>
						<img src="<?php echo $relpath; ?>" alt="Big Jim Push">
				<?  } ?>
			</div>
			<div class="big-jim-right">
				<div class="big-j-logo">
					<? 
						$imageFile = File::getByID($row['push_logo']);
						if ($imageFile instanceof \File) {
							$relpath = $imageFile->getRelativePath(); ?>
							<img src="<?php echo $relpath; ?>" alt="Big Jim Push">
					<?	} ?>
				</div>
				<div class="big-j-content">
					<div class="big-j-inner">
						<?php echo $row['push_text']; ?>
					</div>
					<div class="big-j-learn-more">
						<a href="<?= $linkURL ?>">
							<em class="alt">Learn More</em>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>