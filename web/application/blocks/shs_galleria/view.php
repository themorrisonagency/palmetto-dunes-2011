<?php defined('C5_EXECUTE') or die("Access Denied.");
	$ih = Core::make('helper/image');
	$c = Page::getCurrentPage();
if ($c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item" style="width: <? echo $width; ?>; height: <? echo $height; ?>">
        <div style="padding: 40px 0px 40px 0px"><? echo t('Galleria disabled in edit mode.')?></div>
    </div>
<?  } else { ?>

<div class="media-gallery fullscreen" id="gallery-<?php echo $bID; ?>">
    <div class="galleria"></div>
    <div class="gallery-picker">
        <ul class="pick-gallery"></ul>
    </div>
    <div class="menu-bar">
        <div class="choose-gallery">
            <!--<div class="photo-video photos">
                <a href="#" class="view-photos"><em class="alt">photos</em></a><a href="#" class="view-videos"><em class="alt">videos</em></a>
            </div>-->
            <dl class="galleries dropdown">
                <dt><a href="#">Galleries</a></dt>
                <dd><ul></ul></dd>
            </dl>
        </div>
        <div class="gallery-buttons">
            <a class="gallery-back" href="#"><em class="alt">back to gallery</em></a><a class="gallery-close" href="#"><em class="alt">close</em></a>
        </div>
    </div>
    <div class="gallery-footer" style="bottom: 0px;">
        <div class="gallery-share">
            <div class="share-this-on"><span class="alt">Share this on</span></div>
            <a target="_blank" class="gallery-facebook gallery-share-icon"><em class="alt">Facebook</em></a>
            <a target="_blank" class="gallery-twitter gallery-share-icon" data-count="horiztonal"><em class="alt">Twitter</em></a>
            <a target="_blank" class="gallery-pinterest pin-it-button gallery-share-icon" count-layout="none"><em class="alt">Pinterest</em></a>
        </div>
        <div class="copyright"><p><?php echo (strlen($copyright) > 0) ? $copyright : t('All images are copyright protected. Downloading images is prohibited.'); ?></p></div>
    </div>
</div>

<script type="text/javascript">
    function getGalleriaData() {
        var content = [
            <?php 
            if($images):
                foreach ($images as $image) : ?>
                    <?php $thumb = $ih->getThumbnail(File::getByID($image['fID']),100,60); ?>
                    {
                        image: "<?php echo $image['fullFilePath']; ?>",
                        thumb: "<?php echo $thumb->src; ?>",
                        title:"<?php echo $image['title']; ?>",
                        description:"<?php echo $image['description']; ?>",
                        category: [<?php 
                            $numItems = count($image['category']);
                            $i = 0;
                            if ($numItems > 0) {
                                foreach($image['category'] as $cat) { 
                                    $i++;
                                    echo '{'.strtolower(preg_replace('/[^\da-z]/i','',$cat->treeNodeTopicName)).':"'.$cat->treeNodeTopicName.'"}';
                                    if ($i < $numItems) { echo ','; }
                                }
                            } else {
                                echo '{other:"Other"}';
                            } 
                        ?>],
                        isVideo: false
                    },
                <?php endforeach;
            endif;
            
            if($videos):
                
                foreach ($videos as $video) : ?>
                    {
                        video: "<?php echo $video['videoURL']; ?>",
                        title:"<?php echo $video['title']; ?>",
                        description:"<?php echo $video['description']; ?>",
                        category: [<? 
                            $categorynames = explode(',',$video['categorynames']);
                            $i = 0;
                            $numItems = count($categorynames);
                            if ($numItems > 0 && $categorynames[0] != '') {
                                foreach($categorynames as $cats) {
                                    if ($cats[$i] != '') {
                                        echo '{'.strtolower(preg_replace('/[^\da-z]/i','',$categorynames[$i])).':"'.$categorynames[$i].'"}';
                                        if ($i < $numItems-1) { echo ','; }
                                    }
                                    $i++;
                                }
                            } else {
                                echo '{other:"Other"}';
                            }
                        ?>],
                        isVideo: true
                    },
                <?php endforeach;
            endif; ?>
        ];

        return content;
    }
    $(document).ready(function() {
        sabre_clientName = "<?php echo Config::get('concrete.site'); ?>";
        <?php if ($useTrigger == 1) { ?>
            // add click event handler for the trigger page, using the long URL format and the pretty URL format
            var galLong = "/index.php<?php echo Page::getCollectionPathFromID($triggerPage); ?>";
            galLong = encodeURI(galLong);
            var galShort = "<?php echo Page::getCollectionPathFromID($triggerPage); ?>";
            galShort = encodeURI(galShort);

            $('a[href="'+galLong+'"],a[href="'+galShort+'"],a[href="http://palmettodunes.sabredemos.com'+galShort+'"],a[href="http://palmettodunes.sabredemos.com'+galLong+'"],a[href="http://palmettodunes.com'+galShort+'"],a[href="http://palmettodunes.com'+galLong+'"],a[href="http://www.palmettodunes.com'+galShort+'"],a[href="http://www.palmettodunes.com'+galLong+'"], a[href="https://www.palmettodunes.com'+galShort+'"], a[href="https://www.palmettodunes.com'+galLong+'"]').click(function(e) {
                e.preventDefault();
                if( !$(this).hasClass('blue-btn') ) {
                    $.catlady('gallery-<?php echo $bID; ?>');
                    $('.galleries.dropdown').show();
                } 
            });
        <?php } else { ?>
            // launch the gallery on page load
            $.catlady('gallery-<?php echo $bID; ?>');
        <?php } ?>

        var cat = window.location.hash;
            cat = cat.substring(1);
            switch(cat) {
                case 'gallery':
                    $.catlady('gallery-<?php echo $bID; ?>');
                break;
            default:
        }

        $('.gallery-trigger .img-wrap').click(function(e){
            e.preventDefault();
            $.catlady('gallery-<?php echo $bID; ?>');
        });
        
        $('.gallery-push-wrapper .photos, .weddings-overview .masthead-popup:first() a').click(function(e){
            e.preventDefault();
            $.specific_cat('weddingsevents','gallery-<?php echo $bID; ?>');
        });
        $('.gallery-push-wrapper .videos').click(function(e){
            e.preventDefault();
            $.specific_cat_videos('weddingsevents', true, 'gallery-<?php echo $bID; ?>');
        });
    })
</script>
<?php } ?>