<?php
defined('C5_EXECUTE') or die("Access Denied.");
$ps = Loader::helper('form/page_selector');
?>
<script type="text/javascript">
var CCM_EDITOR_SECURITY_TOKEN = "<?php echo Loader::helper('validation/token')->generate('editor')?>";
$(document).ready(function() {
    /////// Start Images
    var galleriaImagesContainer = $('#ccm-galleria-images-container');
    var _templateSlide = _.template($('#imageTemplate').html());

    var attachDelete = function($obj) {
        $obj.click(function(){
            var deleteIt = confirm('<?php echo t('Are you sure?') ?>');
            if(deleteIt == true) {
                $(this).closest('.ccm-galleria-image').remove();
                doSortCount();
            }
        });
    }

    var attachSortDesc = function($obj) {
        $obj.click(function(){
           var myContainer = $(this).closest($('.ccm-galleria-image'));
           myContainer.insertAfter(myContainer.next('.ccm-galleria-image'));
           doSortCount();
        });
    }

    var attachSortAsc = function($obj) {
        $obj.click(function(){
            var myContainer = $(this).closest($('.ccm-galleria-image'));
            myContainer.insertBefore(myContainer.prev('.ccm-galleria-image'));
            doSortCount();
        });
    }

    var attachFileManagerLaunch = function($obj) {
        $obj.click(function(){
            var oldLauncher = $(this);
            ConcreteFileManager.launchDialog(function (data) {
                ConcreteFileManager.getFileDetails(data.fID, function(r) {
                    jQuery.fn.dialog.hideLoader();
                    var file = r.files[0];
                    oldLauncher.html(file.resultsThumbnailImg);
                    oldLauncher.next('.image-fID').val(file.fID)
                });
            });
        });
    }

    var doSortCount = function(){
        $('.ccm-galleria-image').each(function(index) {
            $(this).find('.ccm-galleria-image-sort').val(index);
        });
    };

    <?php if($rows) {
       foreach ($rows as $row) { ?>
       galleriaImagesContainer.append(_templateSlide({
            fID: '<?php echo $row['fID'] ?>',
            <?php if(File::getByID($row['fID'])) { ?>
            image_url: '<?php echo File::getByID($row['fID'])->getThumbnailURL('file_manager_listing');?>',
            <?php } else { ?>
            image_url: '',
           <?php } ?>
            title: '<?php echo addslashes($row['title']) ?>',
            description: '<?php echo str_replace(array("\t", "\r", "\n"), "", addslashes($row['description']))?>',
            sort_order: '<?php echo $row['sortOrder'] ?>'
        }));
    <?php }
    }?>

    doSortCount();

    $('.ccm-add-galleria-image').click(function(){
        var thisModal = $(this).closest('.ui-dialog-content');
        galleriaImagesContainer.append(_templateSlide({
            fID: '',
            sort_order: '',
            image_url: '',
            title: '',
            description: ''
        }));
        var newSlide = $('.ccm-galleria-image').last();
        thisModal.scrollTop(newSlide.offset().top);
        attachDelete(newSlide.find('.ccm-delete-galleria-image'));
        attachFileManagerLaunch(newSlide.find('.ccm-pick-galleria-image'));
        attachSortDesc(newSlide.find('i.fa-sort-desc'));
        attachSortAsc(newSlide.find('i.fa-sort-asc'));
        doSortCount();
    });

    attachDelete($('.ccm-delete-galleria-image'));
    attachSortAsc($('i.fa-sort-asc'));
    attachSortDesc($('i.fa-sort-desc'));
    attachFileManagerLaunch($('.ccm-pick-galleria-image'));

    /////// End Images

    /////// Start Videos
    var galleriaVideosContainer = $('#ccm-galleria-videos-container');
    var _videoSlide = _.template($('#videoTemplate').html());

    var attachVideoDelete = function($obj) {
        $obj.click(function(){
            var deleteIt = confirm('<?php echo t('Are you sure?') ?>');
            if(deleteIt == true) {
                $(this).closest('.ccm-galleria-video').remove();
                doSortCount();
            }
        });
    }

    var attachVideoSortDesc = function($obj) {
        $obj.click(function(){
           var myContainer = $(this).closest($('.ccm-galleria-video'));
           myContainer.insertAfter(myContainer.next('.ccm-galleria-video'));
           doSortCount();
        });
    }

    var attachVideoSortAsc = function($obj) {
        $obj.click(function(){
            var myContainer = $(this).closest($('.ccm-galleria-video'));
            myContainer.insertBefore(myContainer.prev('.ccm-galleria-video'));
            doSortCount();
        });
    }
    var doSortVideoCount = function(){
        $('.ccm-galleria-video').each(function(index) {
            $(this).find('.ccm-galleria-video-sort').val(index);
        });
    };

    var cleanVideoLink = function(origLink) {
        // clean up shortened YouTube links
        // i.e. http://youtu.be/l0rAQTIFjv4 => https://youtube.com/watch?v=l0rAQTIFjv4
        var pattern = /https*:\/\/youtu.be\//,
            sub = 'https://youtube.com/watch?v=',
            cleanLink = origLink.replace(pattern, sub);

        return cleanLink;
    };

    $('#ccm-block-form').on('change','input.vid-url', function() {
        var currentVal = $(this).val();
        currentVal = currentVal.trim();
        $(this).val(cleanVideoLink(currentVal));
    });

    <?php 
    $vidCount = 0;
    if($videos) {
       foreach ($videos as $video) { ?>
            galleriaVideosContainer.append(_videoSlide({
                galleriaVideoID: '<?php echo round($video['galleriaVideoID']) ?>',
                videoURL: '<?php echo addslashes($video['videoURL']) ?>',
                title: '<?php echo addslashes($video['title']) ?>',
                description: '<?php echo str_replace(array("\t", "\r", "\n"), "", addslashes($video['description']))?>',
                sort_order: '<?php echo round($video['sortOrder']) ?>',
                vidCount: '<?php echo round($vidCount);?>',
                categoryids: <? echo json_encode(explode(',',$video['categoryids'])) ?>,
                categorynames: <? echo json_encode(explode(',',$video['categorynames'])) ?>
            }));
    <?php $vidCount++;
        }
    }?>
    var vidCount = <?php echo $vidCount; ?>;
    
    doSortVideoCount();

    $('.ccm-add-galleria-video').click(function(){
        var thisModal = $(this).closest('.ui-dialog-content');
        galleriaVideosContainer.append(_videoSlide({
            galleriaVideoID: '',
            videoURL: '',
            title: '',
            description: '',
            sort_order: '',
            vidCount: vidCount,
            categoryids: [],
            categorynames: []
        }));
        
        var newVideo = $('.ccm-galleria-video').last();
        
        if(newVideo.offset().top)
            thisModal.scrollTop(newVideo.offset().top);
        
        attachVideoDelete(newVideo.find('.ccm-delete-galleria-video'));
        attachVideoSortDesc(newVideo.find('i.fa-sort-desc'));
        attachVideoSortAsc(newVideo.find('i.fa-sort-asc'));
        doSortVideoCount();
        vidCount++;
    });

    attachVideoDelete($('.ccm-delete-galleria-video'));
    attachVideoSortDesc($('i.fa-sort-desc'));
    attachVideoSortAsc($('i.fa-sort-asc'));

    /////// End Videos

    $('#galleriaType').change(function() {
        if ($(this).val() == 'custom') {
            $('#custom-images').show();
            $('#fileset-images').hide();
        } else {
            $('#custom-images').hide();
            $('#fileset-images').show();
        }
    }).change();

    $('input[name=useTrigger]').change(function() {
        if ($(this).val() == 1) {
            $('.ccm-page-select').slideDown();
        } else {
            $('.ccm-page-select').slideUp();
        }
    })
});
</script>
<style type="text/css">
.ccm-galleria-block-container .btn-success {
    margin-bottom: 20px;
}

.ccm-galleria-block-container input[type="text"],
.ccm-galleria-block-container textarea,
.ccm-galleria-block-container select {
    display: block;
    width: 100%;
}

#ccm-galleria-images-container, #ccm-galleria-videos-container {
    padding-bottom: 30px;
}

.ccm-pick-galleria-image {
    padding: 15px;
    cursor: pointer;
    background: #dedede;
    border: 1px solid #cdcdcd;
    text-align: center;
    vertical-align: center;
}

.ccm-pick-galleria-image img {
    max-width: 100%;
}

.ccm-galleria-image {
    position: relative;
}

.ccm-galleria-block-container i.fa-sort-asc {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
}

.ccm-galleria-block-container i:hover {
    color: #5cb85c;
}

.ccm-galleria-block-container i.fa-sort-desc {
    position: absolute;
    top: 15px;
    cursor: pointer;
    right: 10px;
}
</style>

<p>
<?php print Loader::helper('concrete/ui')->tabs(array(
	array('form-type', t('Images'), true),
    array('form-videos', t('Videos')),
	array('form-options', t('Options')),
));?>
</p>

<div class="ccm-galleria-block-container">
    <div class="ccm-tab-content" id="ccm-tab-content-form-type">
        <fieldset id="galleriaTypeBox">
    		<legend><?php echo t('Images')?></legend>

            <div class="form-group">
    			<?php echo $form->label('galleriaType', t('Type'))?>
    			<select class="form-control" name="galleriaType" id="galleriaType">
    				<option value="custom" <?php echo $galleriaType=='custom'?'selected="selected"':'';?>><?php echo t('Manual Selection')?></option>
                    <option value="fileset" <?php echo $galleriaType=='fileset'?'selected="selected"':'';?>><?php echo t('Images from a File Set')?></option>
    			</select>
    		</div>

            <div id="custom-images">
                <span class="btn btn-success ccm-add-galleria-image"><?php echo t('Add Image') ?></span>
                <div id="ccm-galleria-images-container">
                </div>
            </div>

            <div id="fileset-images">
                <div class="form-group">
                    <?php echo $form->label('fsID','File Set'); ?>
                    <?php
                    $fs = new FileSet();
                    $fileSets = $fs->getMySets();
                    $sets = array();
                    foreach($fileSets as $fileSet) {
            			$sets[$fileSet->fsID] = $fileSet->fsName;
            		}
                    echo $form->select('fsID', $sets, $fsID);
                    ?>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="ccm-tab-content" id="ccm-tab-content-form-videos">
        <fieldset id="galleriaVideosBox">
            <legend><?php echo t('Videos')?></legend>

            <div id="custom-images">
                <span class="btn btn-success ccm-add-galleria-video"><?php echo t('Add Video') ?></span>
                <div id="ccm-galleria-videos-container">
                </div>
            </div>
        </fieldset>
    </div>

    <div class="ccm-tab-content" id="ccm-tab-content-form-options">
        <fieldset id="galleriaOptionsBox">
            <legend><?php echo t('Options'); ?></legend>
            <div class="form-group">
                <label><?php echo t('Copyright Statement') ?></label>
                <input type="text" name="<?php echo $view->field('copyright')?>" value="<?php echo $copyright; ?>" />
            </div>
            <legend><?php echo t('Open Gallery Site-Wide?'); ?></legend>
            <p>NOTE: Block needs to be added to a global area.</p>
            <div class="radio">
                <label>
                    <input type="radio" name="useTrigger" id="cNoTrigger"
                           value="0" <? if ($useTrigger == 0) { ?> checked<? } ?>>
                    <?= t('No') ?>
                </label>
             </div>
            <div class="radio">
                <label>
                    <input type="radio" name="useTrigger" id="cTrigger"
                           value="1" <? if ($useTrigger == 1) { ?> checked<? } ?>>
                    <?= t('Yes') ?>
                </label>
            </div>

            <div class="ccm-page-select" <? if ($useTrigger == 0) { ?> style="display: none" <? } ?>>

                <div class="form-group">
                    <?= $ps->selectPage('triggerPage', ($useTrigger == '1') ? $triggerPage : false); ?>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<script type="text/template" id="imageTemplate">
    <div class="ccm-galleria-image well">
        <i class="fa fa-sort-desc"></i>
        <i class="fa fa-sort-asc"></i>
        <div class="form-group">
            <label><?php echo t('Image') ?></label>
            <div class="ccm-pick-galleria-image">
                <% if (image_url.length > 0) { %>
                    <img src="<%= image_url %>" />
                <% } else { %>
                    <i class="fa fa-picture-o"></i>
                <% } %>
            </div>
            <input type="hidden" name="<?php echo $view->field('fID')?>[]" class="image-fID" value="<%=fID%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Title') ?></label>
            <input type="text" name="<?php echo $view->field('title')?>[]" value="<%=title%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Description') ?></label>
            <textarea name="<?php echo $view->field('description')?>[]"><%=description%></textarea>
        </div>
        <input class="ccm-galleria-image-sort" type="hidden" name="<?php echo $view->field('sortOrder')?>[]" value="<%=sort_order%>"/>
        <div class="form-group">
            <span class="btn btn-danger ccm-delete-galleria-image"><?php echo t('Delete Image'); ?></span>
        </div>
    </div>
</script>

<script type="text/template" id="videoTemplate">
    <div class="ccm-galleria-video well">
        <i class="fa fa-sort-desc"></i>
        <i class="fa fa-sort-asc"></i>
        <div class="form-group">
            <label><?php echo t('Video URL') ?></label>
            <input type="text" class="vid-url" name="video[<%=vidCount%>][<?php echo $view->field('videoURL')?>]" value="<%=videoURL%>" />
            <p class="helper"><small>Youtube and Vimeo only</small></p>
        </div>
        <div class="form-group">
            <label><?php echo t('Title') ?></label>
            <input type="text" name="video[<%=vidCount%>][<?php echo $view->field('title')?>]" value="<%=title%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Description') ?></label>
            <textarea name="video[<%=vidCount%>][<?php echo $view->field('description')?>]"><%=description%></textarea>
        </div>
        <? if($categories) { ?>
        <div class="form-group">
            <label><?php echo t('Categories'); ?></label>
            <ul class="opt-wrapper checkboxList" data-ids="">
                <?
                    if($categories) {
                        foreach($categories as $category) {
                            echo '<li>';
                            echo '<label><input type="checkbox" name="video[<%=vidCount%>][categories]['.$category['treeNodeID'].']" value="1" <% if (_.contains(categoryids,"'.$category['treeNodeID'].'")) { %>checked<% } %>/> '.$category['treeNodeTopicName'].'</label>';
                            echo '</li>';
                        }
                    }
                ?>
            </ul>
        </div>
        <? } ?>

        <input class="ccm-galleria-image-sort" type="hidden" name="video[<%=vidCount%>][<?php echo $view->field('sortOrder')?>]" value="<%=sort_order%>"/>
        <a class="btn btn-danger ccm-delete-galleria-video"><?php echo t('Delete Video'); ?></a>
    </div>
</script>