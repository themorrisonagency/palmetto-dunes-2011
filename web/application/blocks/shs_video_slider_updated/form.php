<?php  defined('C5_EXECUTE') or die("Access Denied.");

$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
$asset_lib = \Core::make('helper/concrete/asset_library');
?>
<script>
    var CCM_EDITOR_SECURITY_TOKEN = "<?=Loader::helper('validation/token')->generate('editor')?>";
    $(document).ready(function(){
        var ccmReceivingEntry = '';
        var sliderEntriesContainer = $('.ccm-promo-slider-entries');
        var _templateSlide = _.template($('#promoTemplate').html());
        var attachDelete = function($obj) {
            $obj.click(function(){
                var deleteIt = confirm('<?php echo t('Are you sure?') ?>');
                if(deleteIt == true) {
                    $(this).closest('.ccm-promo-slider-entry').remove();
                    doSortCount();
                }
            });
        }

        var attachSortDesc = function($obj) {
            $obj.click(function(){
               var myContainer = $(this).closest($('.ccm-promo-slider-entry'));
               myContainer.insertAfter(myContainer.next('.ccm-promo-slider-entry'));
               doSortCount();
            });
        }

        var attachSortAsc = function($obj) {
            $obj.click(function(){
                var myContainer = $(this).closest($('.ccm-promo-slider-entry'));
                myContainer.insertBefore(myContainer.prev('.ccm-promo-slider-entry'));
                doSortCount();
            });
        }

        var attachFileSelector = function($obj) {
            $obj.each(function() {
                $fID = $(this).attr('data-fid');
                $inputName = $(this).attr('data-input-name');
                if ($fID > 0) {
                    $(this).concreteFileSelector({inputName: $inputName, fID: $fID});
                } else {
                    $(this).concreteFileSelector({inputName: $inputName});
                }
            });
        }

        var attachPageSelector = function($obj) {
            $obj.each(function() {
                $cID = $(this).attr('data-cid');
                $inputName = $(this).attr('data-input-name');
                if ($cID > 0) {
                    $(this).concretePageSelector({inputName: $inputName, cID: $cID});
                } else {
                    $(this).concretePageSelector({inputName: $inputName, cID: false});
                }
            });
        }

        var doSortCount = function(){
            $('.ccm-promo-slider-entry').each(function(index) {
                $(this).find('.ccm-promo-slider-entry-sort').val(index);
                $(this).find('h4 span').html(index + 1);
            });
        };

        $('input[name="autoplaySpeedFriendly"]').change(function(){
            $speed = parseInt($(this).val() * 1000);
            $('input[name="autoplaySpeed"]').val($speed);
        });

        sliderEntriesContainer.on('change', 'select[data-field=entry-link-select]', function() {
            var container = $(this).closest('.ccm-promo-slider-entry');
            switch(parseInt($(this).val())) {
                case 3:
                    container.find('div[data-field=entry-link-page-selector]').hide();
                    container.find('div[data-field=entry-link-url]').hide();
                    container.find('div[data-field=entry-link-type-file]').show();
                    break;
                case 2:
                    container.find('div[data-field=entry-link-page-selector]').hide();
                    container.find('div[data-field=entry-link-url]').show();
                    container.find('div[data-field=entry-link-type-file]').hide();
                    break;
                case 1:
                    container.find('div[data-field=entry-link-url]').hide();
                    container.find('div[data-field=entry-link-page-selector]').show();
                    container.find('div[data-field=entry-link-type-file]').hide();
                    break;
                default:
                    container.find('div[data-field=entry-link-page-selector]').hide();
                    container.find('div[data-field=entry-link-url]').hide();
                    container.find('div[data-field=entry-link-type-file]').hide();
                    break;
            }
        });

       <?php if($rows) {
           foreach ($rows as $row) {
            $linkType = 0;
            if ($row['linkedFileID']) {
                $linkType = 3;
            } elseif ($row['externalLinkURL']) {
                $linkType = 2;
            } elseif ($row['internalLinkCID']) {
                $linkType = 1;
            } ?>
           sliderEntriesContainer.append(_templateSlide({
                fID: '<?php echo $row['fID'] ?>',
                video: '<?php echo $row['video'] ?>',
                video_image: '<?php echo $row['videoImage'] ?>',
				video_image_mobile: '<?php echo $row['videoImageMobile'] ?>',
                link_type: '<?php echo $linkType?>',
                link_url: '<?php echo $row['externalLinkURL'] ?>',
                title: '<?php echo addslashes($row['promoTitle']) ?>',
                description: '<?php echo str_replace(array("\t", "\r", "\n"), "", addslashes($row['promoDescription']))?>',
                button_text: '<?php echo addslashes($row['promoButtonText']) ?>',
                internal_link_cid: '<?php echo $row['internalLinkCID'] ?>',
                linked_file_id: '<?php echo $row['linkedFileID'] ?>',
                sort_order: '<?php echo $row['sortOrder'] ?>'
            }));

        <?php }
        }?>

        doSortCount();
        sliderEntriesContainer.find('select[data-field=entry-link-select]').trigger('change');

        $('.ccm-add-promo-slider-entry').click(function(){
            var thisModal = $(this).closest('.ui-dialog-content');
            sliderEntriesContainer.append(_templateSlide({
                fID: '',
                video: '',
                video_image: '',
				video_image_mobile: '',
                link_type: 0,
                link_url: '',
                title: '',
                description: '',
                button_text: '',
                internal_link_cid: '',
                linked_file_id: '',
                sort_order: ''
            }));
            var newSlide = $('.ccm-promo-slider-entry').last();
            thisModal.scrollTop(newSlide.offset().top);
            newSlide.find('.redactor-content').redactor({
                minHeight: '200',
                buttons: ['bold','italic','deleted','alignment','link','html'],
                'concrete5': {
                    filemanager: <?=$fp->canAccessFileManager()?>,
                    sitemap: <?=$tp->canAccessSitemap()?>,
                    lightbox: true
                }
            });
            attachDelete(newSlide.find('.ccm-delete-promo-slider-entry'));
            attachFileSelector(newSlide.find('.ccm-file-selector'));
            attachPageSelector(newSlide.find('.ccm-page-selector-container'));
            attachSortDesc(newSlide.find('i.fa-sort-desc'));
            attachSortAsc(newSlide.find('i.fa-sort-asc'));
            doSortCount();
        });
        attachDelete($('.ccm-delete-promo-slider-entry'));
        attachSortAsc($('i.fa-sort-asc'));
        attachSortDesc($('i.fa-sort-desc'));
        attachFileSelector($('.ccm-promo-slider-entries .ccm-file-selector'));
        attachPageSelector($('.ccm-page-selector-container'));
        $(function() {  // activate redactors
            $('.redactor-content').redactor({
                minHeight: '200',
                buttons: ['bold','italic','deleted','alignment','link','html'],
                'concrete5': {
                    filemanager: <?=$fp->canAccessFileManager()?>,
                    sitemap: <?=$tp->canAccessSitemap()?>,
                    lightbox: true
                }
            });

            if ($('.ccm-promo-slider-entry').length < 1) {
                $('.ccm-add-promo-slider-entry').trigger('click');
            }
        });
    });
</script>
<style>

    .ccm-promo-slider-block-container .redactor_editor {
        padding: 20px;
    }
    .ccm-promo-slider-block-container input[type="text"],
    .ccm-promo-slider-block-container textarea {
        display: block;
        width: 100%;
    }
    .ccm-promo-slider-block-container .btn-success {
        margin-bottom: 20px;
    }

    .ccm-promo-slider-entries {
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

    .ccm-promo-slider-entry {
        position: relative;
    }



    .ccm-promo-slider-block-container i.fa-sort-asc {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }

    .ccm-promo-slider-block-container i:hover {
        color: #5cb85c;
    }

    .ccm-promo-slider-block-container i.fa-sort-desc {
        position: absolute;
        top: 15px;
        cursor: pointer;
        right: 10px;
    }

    .ccm-ui .form-inline .radio {
        padding-left: 20px;
    }
    .ccm-ui .form-inline .radio+.radio {
        margin-top: 0;
        margin-left: 20px;
    }
    .ccm-ui .form-inline .radio input[type="radio"] {
        float: left;
        margin-left: -20px;
    }
</style>
<?php
    // Use the UI helper to build the tab selector
    print \Core::make('helper/concrete/ui')->tabs(array(
        array('form-entries', t('Video Slides'), true),
        array('form-options', t('Animation Options'))
    ));
?>
<div class="ccm-promo-slider-block-container">
    <div class="ccm-tab-content" id="ccm-tab-content-form-entries">
        <div class="ccm-promo-slider-block-container">
            <div class="ccm-promo-slider-entries">
                <!-- JS templated slides go here -->
            </div>
        </div>
        <span class="btn btn-success ccm-add-promo-slider-entry"><?php echo t('Add Slide') ?></span>
    </div>

    <div class="ccm-tab-content" id="ccm-tab-content-form-options">
        <!-- Video Slider Options Tab -->
        <div class="form-group">
            <div class="checkbox"><label>
                <?php echo $form->checkbox('muteSound', 1, $muteSound);?>
                <?php echo t('Mute Video Sound')?>
            </label></div>
        </div>
        <div class="form-group">
            <div class="checkbox"><label>
                <?php echo $form->checkbox('loopVideo', 1, $loopVideo);?>
                <?php echo t('Loop Video')?>
            </label></div>
        </div>
        <div class="form-group">
            <label><?php echo t('Transition Duration:') ?></label>
            <input type="range" min="300" max="6000" step="100" name="speed" value="<?=$speed?>" />
        </div>
        <div class="form-group">
            <label class="label-delay"><?php echo t('Transition Delay (in seconds):') ?> <i></i></label>
            <input type="number" min="1" max="20" name="autoplaySpeedFriendly" value="<?php echo $autoplaySpeedFriendly ? $autoplaySpeedFriendly : ($autoplaySpeed * 0.001); ?>" />
            <input type="hidden" name="autoplaySpeed" value="<?=$autoplaySpeed?>" />
        </div>
        <div class="form-inline">
            <span><?php echo t('Animate slider automatically?') ?></span>&nbsp;
            <label class="radio inline">
                <input type="radio" name="autoplay" id="autoplayNo"
                       value="0" <? if ($autoplay == 0) { ?> checked<? } ?>>
                <?= t('No') ?>
            </label>
            <label class="radio inline">
                <input type="radio" name="autoplay" id="autoplayYes"
                       value="1" <? if ($autoplay == 1) { ?> checked<? } ?>>
                <?= t('Yes') ?>
            </label>
        </div>
        <div class="form-inline">
            <span><?php echo t('Display previous/next arrows?') ?></span>&nbsp;
            <label class="radio inline">
                <input type="radio" name="arrows" id="arrowsNo"
                       value="0" <? if ($arrows == 0) { ?> checked<? } ?>>
                <?= t('No') ?>
            </label>
            <label class="radio inline">
                <input type="radio" name="arrows" id="arrowsYes"
                       value="1" <? if ($arrows == 1) { ?> checked<? } ?>>
                <?= t('Yes') ?>
            </label>
        </div>
    </div>
</div>


<script type="text/template" id="promoTemplate">
    <div class="ccm-promo-slider-entry well">
        <i class="fa fa-sort-desc"></i>
        <i class="fa fa-sort-asc"></i>
        <h4>Video Slide #<span><%=Number(sort_order) + 1%></span></h4>
        <div class="form-group">
            <label><?php echo t('Video URL (Vimeo or YouTube)') ?></label>
            <input type="text" name="video[]" value="<%=video%>" placeholder="Example: https://youtu.be/E63E_uh_acE" />
        </div>
        <div class="form-group">
            <label><?php echo t('Fallback Image') ?></label>
            <span class="help-block">Select a fallback image to display if the video does not load.</span>
            <div class="ccm-file-selector" data-fid="<%=video_image%>" data-input-name="videoImage[]" data-file-selector="vfs<%=sort_order%>"></div>
        </div>
        <div class="form-group">
            <label><?php echo t('Fallback Image for Mobile') ?></label>
            <span class="help-block">Select a fallback image to display if the video does not load.</span>
            <div class="ccm-file-selector" data-fid="<%=video_image_mobile%>" data-input-name="videoImageMobile[]" data-file-selector="vfs<%=sort_order%>"></div>
        </div>		
        <div class="form-group">
            <label><?php echo t('Title') ?></label>
            <input type="text" name="<?=$view->field('promoTitle')?>[]" value="<%=title%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Description') ?></label>
            <div class="redactor-edit-content"></div>
            <textarea style="display: none" class="redactor-content" name="<?=$view->field('promoDescription')?>[]"><%=description%></textarea>
        </div>
        <div class="form-group">
            <label><?php echo t('Button Text') ?></label>
            <input type="text" name="<?=$view->field('promoButtonText')?>[]" value="<%=button_text%>" />
        </div>
        <div class="form-group">
           <label><?php echo t('Link') ?></label>
            <select data-field="entry-link-select" name="linkType[]" class="form-control" style="width: 60%;">
                <option value="0" <% if (!link_type) { %>selected<% } %>><?=t('None')?></option>
                <option value="1" <% if (link_type == 1) { %>selected<% } %>><?=t('Another Page')?></option>
                <option value="2" <% if (link_type == 2) { %>selected<% } %>><?=t('External URL')?></option>
                <option value="3" <% if (link_type == 3) { %>selected<% } %>><?=t('File')?></option>
            </select>
        </div>

        <div style="display: none;" data-field="entry-link-url" class="form-group">
            <label><?php echo t('URL') ?></label>
            <input type="text" name="externalLinkURL[]" value="<%=link_url%>" />
        </div>

        <div style="display: none;" data-field="entry-link-page-selector" class="form-group">
            <?= $form->label('internalLinkCID[]', t('Page')) ?>
            <div class="ccm-page-selector-container" data-cid="<%=internal_link_cid%>" data-input-name="internalLinkCID[]"></div>
        </div>

        <div style="display: none;" data-field="entry-link-type-file" class="form-group">
            <?= $form->label('linkedFileID[]', t('File')) ?>
            <div class="ccm-file-selector" data-fid="<%=linked_file_id%>" data-input-name="linkedFileID[]" data-file-selector="fs<%=sort_order%>"></div>
        </div>

        <input class="ccm-promo-slider-entry-sort" type="hidden" name="<?=$view->field('sortOrder')?>[]" value="<%=sort_order%>"/>
        <div class="form-group">
            <span class="btn btn-danger ccm-delete-promo-slider-entry"><?php echo t('Delete Entry'); ?></span>
        </div>
    </div>
</script>
