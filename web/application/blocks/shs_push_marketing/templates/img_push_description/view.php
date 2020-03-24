<div class="amax-pushmarketinginterior img-text-push">
	<div class="amax-module-body">
		<div class="interior-push">
			<div class="int-push-wrapper">
				<div class="push-inset">

					<?php if ($file instanceof \File) { ?>
					<img width="290" height="164" alt="<?= $title ?>" src="<?= $file->getRelativePathFromID($file->getFileID()) ?>" />
					<?php } ?>

					<div class="int-push-description">
						<?= $paragraph ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>