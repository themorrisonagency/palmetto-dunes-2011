<?php  defined('C5_EXECUTE') or die("Access Denied.");

$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
?>
<script>
    var CCM_EDITOR_SECURITY_TOKEN = "<?=Loader::helper('validation/token')->generate('editor')?>";
    $(document).ready(function(){
        var ccmReceivingEntry = '';
        var sliderEntriesContainer = $('.ccm-image-slider-entries');
        var _templateSlide = _.template($('#imageTemplate').html());
        var attachDelete = function($obj) {
            $obj.click(function(){
                var deleteIt = confirm('<?php echo t('Are you sure?') ?>');
                if(deleteIt == true) {
                    $(this).closest('.ccm-image-slider-entry').remove();
                    doSortCount();
                }
            });
        }

        var attachSortDesc = function($obj) {
            $obj.click(function(){
               var myContainer = $(this).closest($('.ccm-image-slider-entry'));
               myContainer.insertAfter(myContainer.next('.ccm-image-slider-entry'));
               doSortCount();
            });
        }

        var attachSortAsc = function($obj) {
            $obj.click(function(){
                var myContainer = $(this).closest($('.ccm-image-slider-entry'));
                myContainer.insertBefore(myContainer.prev('.ccm-image-slider-entry'));
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
            $('.ccm-image-slider-entry').each(function(index) {
                $(this).find('.ccm-image-slider-entry-sort').val(index);
            });
        };

        sliderEntriesContainer.on('change', 'select[data-field=entry-link-select]', function() {
            var container = $(this).closest('.ccm-image-slider-entry');
            switch(parseInt($(this).val())) {
                case 2:
                    container.find('div[data-field=entry-link-page-selector]').hide();
                    container.find('div[data-field=entry-link-url]').show();
                    break;
                case 1:
                    container.find('div[data-field=entry-link-url]').hide();
                    container.find('div[data-field=entry-link-page-selector]').show();
                    break;
                default:
                    container.find('div[data-field=entry-link-page-selector]').hide();
                    container.find('div[data-field=entry-link-url]').hide();
                    break;
            }
        });

       <?php if($slides) {
           foreach ($slides as $slide) {
        ?>
           sliderEntriesContainer.append(_templateSlide({
                fID: '<?php echo $slide['img'] ?>',
                <?php if(File::getByID($slide['img'])) { ?>
                image_url: '<?php echo File::getByID($slide['img'])->getThumbnailURL('file_manager_listing');?>',
                <?php } else { ?>
                image_url: '',
               <?php } ?>
                link_url: '<?php echo $slide['link'] ?>',
                title: '<?php echo addslashes($slide['title']) ?>',
                custom_title: '<?php echo addslashes($slide['customTitle']) ?>',
                description: '<?php echo str_replace(array("\t", "\r", "\n"), "", addslashes($slide['descr']))?>',
                sort_order: '<?php echo $slide['sortOrder'] ?>'
            }));
            sliderEntriesContainer.find('.ccm-image-slider-entry:last-child div[data-field=entry-link-page-selector]').concretePageSelector({
                'inputName': 'contentID[]', 'cID': <?=intval($slide['contentID'])?>
            });
        <?php }
        }?>

        doSortCount();
        sliderEntriesContainer.find('select[data-field=entry-link-select]').trigger('change');

        $('.ccm-add-image-slider-entry').click(function(){
           var thisModal = $(this).closest('.ui-dialog-content');
            sliderEntriesContainer.append(_templateSlide({
                fID: '',
                title: '',
                link_url: '',
                contentID: '',
                custom_title: '',
                description: '',
                sort_order: '',
                image_url: ''
            }));
            var newSlide = $('.ccm-image-slider-entry').last();
            thisModal.scrollTop(newSlide.offset().top);
            newSlide.find('.redactor-content').redactor({
                minHeight: '200',
                'concrete5': {
                    filemanager: <?=$fp->canAccessFileManager()?>,
                    sitemap: <?=$tp->canAccessSitemap()?>,
                    lightbox: true
                }
            });
            attachDelete(newSlide.find('.ccm-delete-image-slider-entry'));
            attachFileManagerLaunch(newSlide.find('.ccm-pick-slide-image'));
            newSlide.find('div[data-field=entry-link-page-selector-select]').concretePageSelector({
                'inputName': 'contentID[]'
            });
            attachSortDesc(newSlide.find('i.fa-sort-desc'));
            attachSortAsc(newSlide.find('i.fa-sort-asc'));
            doSortCount();
        });
        attachDelete($('.ccm-delete-image-slider-entry'));
        attachSortAsc($('i.fa-sort-asc'));
        attachSortDesc($('i.fa-sort-desc'));
        attachFileManagerLaunch($('.ccm-pick-slide-image'));
        $(function() {  // activate redactors
            $('.redactor-content').redactor({
                minHeight: '200',
                'concrete5': {
                    filemanager: <?=$fp->canAccessFileManager()?>,
                    sitemap: <?=$tp->canAccessSitemap()?>,
                    lightbox: true
                }
            });
        });
    });
</script>
<style>

    .ccm-image-slider-block-container .redactor_editor {
        padding: 20px;
    }
    .ccm-image-slider-block-container input[type="text"],
    .ccm-image-slider-block-container textarea {
        display: block;
        width: 100%;
    }
    .ccm-image-slider-block-container .btn-success {
        margin-bottom: 20px;
    }

    .ccm-image-slider-entries {
        padding-bottom: 30px;
    }

    .ccm-pick-slide-image {
        padding: 15px;
        cursor: pointer;
        background: #dedede;
        border: 1px solid #cdcdcd;
        text-align: center;
        vertical-align: center;
    }

    .ccm-pick-slide-image img {
        max-width: 100%;
    }

    .ccm-image-slider-entry {
        position: relative;
    }



    .ccm-image-slider-block-container i.fa-sort-asc {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }

    .ccm-image-slider-block-container i:hover {
        color: #5cb85c;
    }

    .ccm-image-slider-block-container i.fa-sort-desc {
        position: absolute;
        top: 15px;
        cursor: pointer;
        right: 10px;
    }
	
	.grey-box {
		border: 1px #ccc solid;
		background: #efefef;
		padding: 15px;
	}
</style>
<div class="ccm-image-slider-block-container">
    <span class="btn btn-success ccm-add-image-slider-entry"><?php echo t('Add Nav Slide') ?></span>
    <div class="ccm-image-slider-entries">

    </div>
</div>

<script type="text/template" id="imageTemplate">
    <div class="ccm-image-slider-entry well">
        <i class="fa fa-sort-desc"></i>
        <i class="fa fa-sort-asc"></i>
        <div class="form-group">
            <label><?php echo t('Image') ?></label>
            <div class="ccm-pick-slide-image">
                <% if (image_url.length > 0) { %>
                    <img src="<%= image_url %>" />
                <% } else { %>
                    <i class="fa fa-picture-o"></i>
                <% } %>
            </div>
            <input type="hidden" name="<?=$view->field('img')?>[]" class="image-fID" value="<%=fID%>" />
        </div>
        <div data-field="entry-link-page-selector" class="form-group">
           <label><?php echo t('Choose Page:') ?></label>
            <div data-field="entry-link-page-selector-select"></div>
        </div>
        <div class="form-group">
            <label><?php echo t('Custom Page Title') ?></label><br/>
            <input type="text" name="<?=$view->field('customTitle')?>[]" value="<%=custom_title%>"/>
        </div>
        <div class="form-group">
            <label><?php echo t('Description') ?></label>
            <div class="redactor-edit-content"></div>
            <textarea style="display: none" class="redactor-content" name="<?=$view->field('descr')?>[]"><%=description%></textarea>
        </div>
        <input class="ccm-image-slider-entry-sort" type="hidden" name="<?=$view->field('sortOrder')?>[]" value="<%=sort_order%>"/>
        <div class="form-group">
            <span class="btn btn-danger ccm-delete-image-slider-entry"><?php echo t('Delete Slide'); ?></span>
        </div>
    </div>
</script>
