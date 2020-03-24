<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<?
$c = Page::getCurrentPage();
if ($c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item" style="width: <? echo $width; ?>; height: <? echo $height; ?>">
        <div style="padding: 40px 0px 40px 0px"><? echo t('Push Slider disabled in edit mode.')?></div>
    </div>
<?  } else { ?>
<div id="push-slider" style="padding-top: 25px;">
		<h3 class="sg-ccd-text" style="text-align: center; margin: 0px;">See What Our Guests Are Saying</h3>
		<div class="testimonials-awards">
		<ul class="multiList" id="slider-<?= $bID; ?>">
		<?php foreach ($rows as $row){ ?>
			<li>
			<div>
				<div class="thumb thumbleft">
					<? if (File::getByID($row['img']) instanceof \File) { ?>
					<img src="<?php echo File::getByID($row['img'])->getRelativePath();?>" width="291" height="176"/>
					<? } else { ?>
					<img src="http://placehold.it/291x176" width="291" height="176"/>
					<? } ?>
				</div>
				<div>
					<div class="description">
						<value>
							<?php echo $row['descr']; ?>
							<p>
								<strong>
									- <?php echo $row['guest_name']; ?>
								</strong>
								<em>
									<br>
								</em>
									<?php echo $row['date']; ?>
								<em>
									<br>
								</em>
							</p>
						</value>
					</div>
				</div>
			</div>
			</li>
		<?php } ?>
		</ul>
		</div>
<div style="width: 100%; height: 1px; clear: both;"></div>
</div>
<script>
$(function() {
	$('#slider-<?= $bID; ?>').after('<div class="controls"> <button type="button" class="t-prev"><em class="alt">prev</em></button> <button type="button" class="t-next"><em class="alt">next</em></button> </div>').cycle({
		slides: 'li',
		containerResize: 1,
		timeout: 0,
		allowWrap:  false,
		speed: 1000,
		fx: 'scrollHorz',
		next: '.t-next',
		prev: '.t-prev'
	});
});
</script>
<?
}
?>