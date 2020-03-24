<?php  defined('C5_EXECUTE') or die("Access Denied.");
$asset_lib = \Core::make('helper/concrete/asset_library');

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

       <?php if($rows) {
           foreach ($rows as $row) {
            $linkType = 0;
            if ($row['linkURL']) {
                $linkType = 2;
            } else if ($row['internalLinkCID']) {
                $linkType = 1;
           } ?>
           sliderEntriesContainer.append(_templateSlide({
                fID: '<?php echo $row['fID'] ?>',
                <?php if(File::getByID($row['fID'])) { ?>
                image_url: '<?php echo File::getByID($row['fID'])->getThumbnailURL('file_manager_listing');?>',
                <?php } else { ?>
                image_url: '',
               <?php } ?>
               tID: '<?php echo $row['tID'] ?>',
               <?php if(File::getByID($row['tID'])) { ?>
                thumbnail_image_url: '<?php echo File::getByID($row['tID'])->getThumbnailURL('file_manager_listing');?>',
                <?php } else { ?>
                thumbnail_image_url: '',
               <?php } ?>
                thumbnail_caption: '<?php echo addslashes($row['thumbnailCaption']) ?>',
                link_url: '<?php echo $row['linkURL'] ?>',
                link_type: '<?php echo $linkType?>',
                title: '<?php echo addslashes($row['title']) ?>',
                description: '<?php echo str_replace(array("\t", "\r", "\n"), "", addslashes($row['description']))?>',
                button_text: '<?php echo addslashes($row['buttonText']) ?>',
                sort_order: '<?php echo $row['sortOrder'] ?>'
            }));
            sliderEntriesContainer.find('.ccm-image-slider-entry:last-child div[data-field=entry-link-page-selector]').concretePageSelector({
                'inputName': 'internalLinkCID[]', 'cID': <? if ($linkType == 1) { ?><?=intval($row['internalLinkCID'])?><? } else { ?>false<? } ?>
            });
        <?php }
        }?>

        doSortCount();
        sliderEntriesContainer.find('select[data-field=entry-link-select]').trigger('change');

        $('.ccm-add-image-slider-entry').click(function(){
           var thisModal = $(this).closest('.ui-dialog-content');
            sliderEntriesContainer.append(_templateSlide({
                fID: '',
                tID: '',
                thumbnail_caption: '',
                title: '',
                link_url: '',
                cID: '',
                description: '',
                link_type: 0,
                button_text: '',
                sort_order: '',
                image_url: '',
                thumbnail_image_url: ''
            }));
            var newSlide = $('.ccm-image-slider-entry').last();
            thisModal.scrollTop(newSlide.offset().top);
            newSlide.find('.redactor-content').redactor({
                minHeight: '200',
                buttons: ['bold','italic','deleted','link','horizontalrule','html'],
                'concrete5': {
                    filemanager: <?=$fp->canAccessFileManager()?>,
                    sitemap: <?=$tp->canAccessSitemap()?>,
                    lightbox: true
                }
            });
            attachDelete(newSlide.find('.ccm-delete-image-slider-entry'));
            attachFileManagerLaunch(newSlide.find('.ccm-pick-slide-image'));
            newSlide.find('div[data-field=entry-link-page-selector-select]').concretePageSelector({
                'inputName': 'internalLinkCID[]'
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
                buttons: ['bold','italic','deleted','link','horizontalrule','html'],
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
        word-wrap: break-word;
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
</style>
<div class="ccm-image-slider-block-container">
    <label class="control-label"><?php echo t('Navigation') ?></label>
    <div class="form-group">
        <div class="radio">
            <label><input type="radio" name="<?=$view->field('navigationType')?>" value="0" <?php echo $navigationType > 0 ? '' : 'checked' ?> /><?php echo t('Arrows') ?></label>
        </div>
    </div>
    <div class="form-group">
        <div class="radio">
            <label><input type="radio" name="<?=$view->field('navigationType')?>" value="1" <?php echo $navigationType > 0 ? 'checked' : '' ?> /><?php echo t('Bullets') ?></label>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?= t('Auto-rotate slides?') ?></label>
        <div class="radio">
            <label>
                <input type="radio" name="autoRotate" value="0" <?= ($autoRotate ? "" : "checked=\"checked\"") ?>/> <?= t('No') ?>
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="autoRotate" value="1" <?= ($autoRotate ? "checked=\"checked\"" : "") ?>/> <?= t('Yes') ?>
            </label>
        </div>
    </div>

    <span class="btn btn-success ccm-add-image-slider-entry"><?php echo t('Add Entry') ?></span>
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
                    <br /><br />
                    <%= image_url %>
                <% } else { %>
                    <i class="fa fa-picture-o"></i>
                <% } %>
            </div>
            <input type="hidden" name="<?=$view->field('fID')?>[]" class="image-fID" value="<%=fID%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Thumbnail Image') ?></label>
            <div class="ccm-pick-slide-image">
                <% if (thumbnail_image_url.length > 0) { %>
                    <img src="<%= thumbnail_image_url %>" />
                    <br /><br />
                    <%= thumbnail_image_url %>
                <% } else { %>
                    <i class="fa fa-picture-o"></i>
                <% } %>
            </div>
            <input type="hidden" name="<?=$view->field('tID')?>[]" class="image-fID" value="<%=tID%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Thumbnail Caption') ?></label>
            <input type="text" name="<?=$view->field('thumbnailCaption')?>[]" value="<%=thumbnail_caption%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Title') ?></label>
            <input type="text" name="<?=$view->field('title')?>[]" value="<%=title%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Description') ?></label>
            <div class="redactor-edit-content"></div>
            <textarea style="display: none" class="redactor-content" name="<?=$view->field('description')?>[]"><%=description%></textarea>
        </div>
        <div class="form-group">
            <label><?php echo t('Button Text') ?></label>
            <input type="text" name="<?=$view->field('buttonText')?>[]" value="<%=button_text%>" />
        </div>
        <div class="form-group">
           <label><?php echo t('Link') ?></label>
            <select data-field="entry-link-select" name="linkType[]" class="form-control" style="width: 60%;">
                <option value="0" <% if (!link_type) { %>selected<% } %>><?=t('None')?></option>
                <option value="1" <% if (link_type == 1) { %>selected<% } %>><?=t('Another Page')?></option>
                <option value="2" <% if (link_type == 2) { %>selected<% } %>><?=t('External URL')?></option>
            </select>
        </div>

        <div style="display: none;" data-field="entry-link-url" class="form-group">
           <label><?php echo t('URL') ?></label>
            <textarea name="linkURL[]"><%=link_url%></textarea>
        </div>

        <div style="display: none;" data-field="entry-link-page-selector" class="form-group">
           <label><?php echo t('Choose Page') ?></label>
            <div data-field="entry-link-page-selector-select"></div>
        </div>

        <input class="ccm-image-slider-entry-sort" type="hidden" name="<?=$view->field('sortOrder')?>[]" value="<%=sort_order%>"/>
        <div class="form-group">
            <span class="btn btn-danger ccm-delete-image-slider-entry"><?php echo t('Delete Entry'); ?></span>
        </div>
    </div>
</script>