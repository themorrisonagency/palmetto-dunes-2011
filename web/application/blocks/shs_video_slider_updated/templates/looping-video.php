<? defined('C5_EXECUTE') or die("Access Denied.");
$c = Page::getCurrentPage();
/*
Block configuration settings:
- $loopVideo (0|1)
- $muteSound (0|1)
- $autoplay (0|1)
- $arrows (0|1)
- $speed (in milliseconds)
- $autoplaySpeed (in milliseconds)

Each slide in the collection ($rows) contains these elements:
- video (URL)
- videoImage (file ID)
- promoTitle (string)
- promoDescription (HTML)
- promoButtonText (string)
- internalLinkCID (page ID)
- externalLinkURL (URL string)
- linkedFileID (file ID)
- sortOrder (integer)
*/
?>

<?php if ($c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item" style="width: <?php echo $width; ?>; height: <?php echo $height; ?>">
        <div style="padding: 40px 0px 40px 0px"><?php echo t('Video Slider disabled in edit mode.')?></div>
    </div>
<?php  } else { ?>

<div class="contentHeaderVid">
    <?php
        $idx = 0;
		$poster = '';
		$poster2 = '';
        foreach ($rows as $slide) {
            $f = File::getByID($slide['videoImage']);
            if(is_object($f)) {
                $poster = File::getRelativePathFromID($slide['videoImage']);
            }
			if ($slide['videoImageMobile'] != ''){
				$f2 = File::getByID($slide['videoImageMobile']);
				if(is_object($f2)) {
					$poster2 = File::getRelativePathFromID($slide['videoImageMobile']);
				} else {
					if(is_object($f)) {
						$poster2 = File::getRelativePathFromID($slide['videoImage']);
					} 
				}
			}
    ?>
        <div class="video">
            <img class="large-poster" src="<?=$poster?>" alt="" />			
			<img class="small-poster" src="<?=$poster2?>" alt="" />
            <video id="headerVid<?=$idx?>" poster="<?=$poster?>" class="cover" loop autoplay>
                <source src="<?=$slide['video']?>" type="video/mp4">
            </video>
            <div class="masthead-promo">

				<?php if ($slide['promoTitle'] != ''){ ?>
                <div class="masthead-title"><?=$slide['promoTitle']?></div>
				<?php } ?>
				
				<?php if ($slide['promoDescription'] != ''){ ?>				
                <div class="masthead-description">
                    <?=$slide['promoDescription']?>
                </div>
				<?php } ?>				
				
				<?php if (($slide['linkURL'] != '') && ($slide['promoButtonText'] != '')){ ?>				
                <div class="masthead-buttons">
                    <a href="<?=$slide['linkURL']?>" class="orange-btn"><?=$slide['promoButtonText']?></a>
                </div>
				<?php } ?>				
				
            </div>
        </div>
    <?php
        $idx++;   
        }
    ?>     
</div>



<?php } ?>
