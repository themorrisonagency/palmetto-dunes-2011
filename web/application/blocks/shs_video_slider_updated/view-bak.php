<? defined('C5_EXECUTE') or die("Access Denied.");
$c = Page::getCurrentPage();
/*
Each masthead slide in the collection ($rows) contains these elements:
fID (file ID of image)
linkURL (external, internal or file)
buttonText
title
description
sortOrder
*/
$isYouTube = 0;
if (strpos($video,'youtu.be') || strpos($video,'youtube')) {
    $video = str_replace('https://youtu.be/', '', $video);
    $video = str_replace('https://www.youtube.com/watch?v=', '', $video);
    $isYouTube = 1;
}

?>
<div class="video-masthead-wrapper <?= ($isYouTube) ? 'youtube' : ''; ?>">
    <? if ($c->isEditMode()): ?>
        <? if ($videoImage): ?>
            <img class="video-poster" src="<?= $videoImage; ?>" alt=""/>
        <?  else: ?>
            <div class="ccm-edit-mode-disabled-item" style="width: 100%; height: 390px">
        		<div style="padding: 80px 0px 0px 0px"><? echo t('Video disabled in edit mode.')?>
        			<div class="alert alert-info"><p><?php echo t('Click to edit')?></p></div>
        		</div>
        	</div>
        <? endif; ?>
    <?  else: ?>
            <? if ($isYouTube): ?>
                <div id="player-<?= $bID; ?>"><img class="video-poster" src="<?= $videoImage; ?>" alt=""/></div>
                <script>
                    var tag = document.createElement('script');

                    tag.src = "https://www.youtube.com/iframe_api";
                    var firstScriptTag = document.getElementsByTagName('script')[0];
                    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                    var player;
                    function onYouTubeIframeAPIReady() {
                        player = new YT.Player('player-<?= $bID; ?>', {
                            height: '390',
                            width: '640',
                            videoId: '<?= $video; ?>',
                            playerVars: {
                                'controls': 0,
                                'showinfo': 0,
                                'modestbranding': 1,
                                'autohide': 1,
                                'rel': 0,
                                'loop': <?= $loopVideo; ?>,
                                <?php if ($loopVideo): ?>
                                    'playlist': '<?= $video; ?>'
                                <? endif; ?>
                            },
                            events: {
                              'onReady': onPlayerReady
                            }
                        });
                    }

                function onPlayerReady(event) {
                    <?php if ($muteSound): ?>
                        event.target.setVolume(0);
                    <? endif; ?>
                    event.target.playVideo();
                }
                </script>
            <? else: ?>
                <video class="masthead-video" autoplay <? if($loopVideo){ echo 'loop'; } ?> <? if($muteSound){ echo 'muted'; } ?> poster="<?= $videoImage; ?>" src="<?= $video; ?>"></video>
            <? endif; ?>
    <?  endif; ?>
    <div class="masthead-promos" data-slick='{
        "autoplay": <? if ($c->isEditMode()) { print "false"; } else { print $controller->boolString($autoplay); } ?>,
        "arrows": <?= $controller->boolString($arrows) ?>,
        "dots": <?= $controller->boolString($dots) ?>,
        "fade": <?= $controller->boolString($fade) ?>,
        "infinite": <?= $controller->boolString($infinite) ?>,
        "centerMode": <?= $controller->boolString($centerMode) ?>,
        "slidesToShow": <?= $slidesToShow ?>,
        "slidesToScroll": <?= $slidesToScroll ?>,
        "speed": <?= $speed ?>,
        "autoplaySpeed": <?= $autoplaySpeed ?>
    }'>
        <?php if(count($rows) > 0 && ($rows[0]['title'] || $rows[0]['description'])):
            $i = 0;
        ?>

            <?php foreach($rows as $row): ?>
                <div>
                    <div class="masthead-promo">
                    <?php if ($row['title']): ?>
                        <div class="masthead-title">
                            <?= $row['title']; ?>
                        </div>
                    <?php endif ?>
                    <?php if ($row['description']): ?>
                        <div class="masthead-description">
                            <?= $row['description']; ?>
                        </div>
                    <?php endif ?>
                    <?php if ($row['linkURL']): ?>
                        <div class="masthead-buttons">
                            <a href="<?= $row['linkURL']; ?>" class="orange-btn"><?= $row['buttonText']; ?></a>
                        </div>
                    <?php endif ?>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>
</div>
