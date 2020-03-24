<?php   defined("C5_EXECUTE") or die("Access Denied."); ?>

<?php
	$pinterestClass = '';
	if (trim($pinterestBtn) != "") {

		switch($pinterestBtn){
			case "no":
			    $pinterestClass="no-pinterest";
			    break;
			case "yes":
			    $pinterestClass="pinterest";
			    break;
		}
	}
?>


<div class="gallery-push-wrapper push-<?php  echo h($ctaIcon); ?>">
	<div class="push-inset <?php  echo h($pinterestClass); ?>">

		<a href="<?php  echo h($linkUrl); ?>" class="<?php  echo h($ctaIcon); ?>">
			<?php  if ($image){ ?>
    			<img src="<?php  echo $image->getURL(); ?>" alt="<?php  echo $image->getTitle(); ?>"/><?php  } ?>

    		<div class="overlay">
				<div class="cta-icon"></div>
				<div class="cta-text">
					<?php  if (isset($title) && trim($title) != ""){ ?>
    					<?php  echo h($title); ?><?php  } ?>
				</div>
			</div>
		</a>
	</div>
</div>
