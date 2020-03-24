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
            <video id="headerVid<?=$idx?>" poster="<?=$poster?>" class="cover">
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

<?php if (count($rows) > 1){ ?>

<div class="buttons">
    <div class="vidBtn" id="slickPrev">
        <span class="prev-icon"></span>
    </div>
    <div class="vidBtn" id="slickNext">
        <span class="next-icon"></span>
    </div>
</div>

<?php } ?>

<script>
	$(document).ready(function(){
		var isHandheld = {
			Android_Mobile: function() {
				if(navigator.userAgent.match(/Android/i) && navigator.userAgent.match(/Mobile/i))
					return true;
				else
					return false;
			},
			Android_Tablet: function() {
				return navigator.userAgent.match(/Android/i);
			},
			BlackBerry: function() {
				return navigator.userAgent.match(/BlackBerry/i);
			},
			iOS: function() {
				return navigator.userAgent.match(/iPhone|iPod/i);
			},
			iPad: function() {
				return navigator.userAgent.match(/iPad/i);
			},
			Opera: function() {
				return navigator.userAgent.match(/Opera Mini/i);
			},
			Windows: function() {
				return navigator.userAgent.match(/IEMobile/i);
			},
			tablet: function() {
				return (isHandheld.iPad() || isHandheld.Android_Tablet);
			},
			mobile: function() {
				return (isHandheld.Android_Mobile() || isHandheld.BlackBerry() || isHandheld.iOS()  || isHandheld.iPad() || isHandheld.Opera() || isHandheld.Windows());
			},
			any: function() {
				return (isHandheld.Android_Mobile() || isHandheld.Android_Tablet() || isHandheld.BlackBerry() || isHandheld.iOS()  || isHandheld.iPad() || isHandheld.Opera() || isHandheld.Windows());
			}
		};
		
		/*
		if ((isHandheld.iOS()) || (isHandheld.iPad())) {
			$('video').remove();
		}
		*/
	});
	
    $(document).ready(function(){		
        $('.contentHeaderVid').slick({
            // arrows: <?=$this->controller->boolString($arrows)?>,
            // autoplay: <?=$this->controller->boolString($autoplay)?>,
            // autoplaySpeed: <?=$autoplaySpeed?>,
            draggable: false,
            slide: 'div',
            cssEase: 'ease-in-out',
            fade: true,
            arrows: false,
            speed: 1000
        });
        $('#slickPrev').on('click', function() {
            $('.contentHeaderVid').slick('slickPrev');
        });
        $('#slickNext').on('click', function() {
            $('.contentHeaderVid').slick('slickNext');
        });
		if (!isHandheld.iOS() && !isHandheld.iPad()){
			$('.contentHeaderVid').on('beforeChange', function(event, slick, currentSlide, nextSlide){
				playProperVid(currentSlide, nextSlide);
			});		
			pageLoaded();
		}
    });
        
	//if (!isHandheld.iOS() && !isHandheld.iPad()){
		// Play first video once entire page is loaded
		var vid0 = document.getElementById("headerVid0");
		function pageLoaded() {
			vid0.play();
			// Hide initial masthead-promo on load, then fade in after 5 seconds using animate.css fadeIn.
			$('.masthead-promo').hide();
			if ( $(window).width()>764 ) {
				setTimeout(function() {
				  $('.masthead-promo').show().addClass('fadeIn animated');
				}, 6000);
			} else {
				$('.masthead-promo').show().addClass('fadeIn animated');
			}
		}

		// Auto advance to the next slide. NOTE: "loop" attribute for <video> and "ended" will not work together. "loop" must be removed from <video>
		$("#headerVid0").bind("ended", function() {
		   $('.contentHeaderVid').slick('slickNext');
		   console.log();
		});
		$("#headerVid1").bind("ended", function() {
		   $('.contentHeaderVid').slick('slickNext');
		});
		$("#headerVid2").bind("ended", function() {
		   $('.contentHeaderVid').slick('slickNext');
		});

		// Play/Pause vid based on which one is currently showing in slider
		function playProperVid(currentSlide, nextSlide) {
			var currSlide = document.getElementById("headerVid" + currentSlide);
			var nxtSlide = document.getElementById("headerVid" + nextSlide);
			//console.log("currSlide: "+currSlide.id, "nxtSlide: "+nxtSlide.id);
			currSlide.pause();
			nxtSlide.play();
		}
	//}
</script>
<?php } ?>
