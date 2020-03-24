<?php  defined('C5_EXECUTE') or die("Access Denied.");

$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
$asset_lib = \Core::make('helper/concrete/asset_library');
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
            $('.ccm-image-slider-entry').each(function(index) {
                $(this).find('.ccm-image-slider-entry-sort').val(index);
                $(this).find('h4 span').html(index + 1);
            });
        };

        sliderEntriesContainer.on('change', 'select[data-field=entry-link-select]', function() {
            var container = $(this).closest('.ccm-image-slider-entry');
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
       ?>
           sliderEntriesContainer.append(_templateSlide({
                title: '<?php echo addslashes($row['title']) ?>',
                description: '<?php echo str_replace(array("\t", "\r", "\n"), "", addslashes($row['description']))?>',
				sort_order: <?php echo $row['sortOrder']; ?>
                
            }));

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
                cID: '',
                description: '',
                link_type: 0,
                button_text: '',
                sort_order: '',
                internal_link_cid: '',
                linked_file_id: ''
            }));
            var newSlide = $('.ccm-image-slider-entry').last();
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
            attachDelete(newSlide.find('.ccm-delete-image-slider-entry'));
            attachFileSelector(newSlide.find('.ccm-file-selector'));
            attachPageSelector(newSlide.find('.ccm-page-selector-container'));
            attachSortDesc(newSlide.find('i.fa-sort-desc'));
            attachSortAsc(newSlide.find('i.fa-sort-asc'));
            doSortCount();
        });
        attachDelete($('.ccm-delete-image-slider-entry'));
        attachSortAsc($('i.fa-sort-asc'));
        attachSortDesc($('i.fa-sort-desc'));
        attachFileSelector($('.ccm-file-selector'));
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

            if ($('.ccm-image-slider-entry').length < 1) {
                $('.ccm-add-image-slider-entry').trigger('click');
            }
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
        array('form-entries', t('Accordion Items'), true)        
    ));
?>
<div class="ccm-promo-slider-block-container">
    <div class="ccm-tab-content" id="ccm-tab-content-form-entries">
        <div class="ccm-image-slider-block-container">
            <div class="ccm-image-slider-entries">

            </div>
        </div>
        <span class="btn btn-success ccm-add-image-slider-entry"><?php echo t('Add Item') ?></span>
    </div>

    <div class="ccm-tab-content" id="ccm-tab-content-form-options">        
       
    </div>
</div>


<script type="text/template" id="imageTemplate">
    <div class="ccm-image-slider-entry well">
        <i class="fa fa-sort-desc"></i>
        <i class="fa fa-sort-asc"></i>
        <h4>Item #<span><%=Number(sort_order) + 1%></span></h4>
        <div class="form-group">
            <label><?php echo t('Title') ?></label>
            <input type="text" name="<?=$view->field('title')?>[]" value="<%=title%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Description') ?></label>
            <div class="redactor-edit-content"></div>
            <textarea style="display: none" class="redactor-content" name="<?=$view->field('description')?>[]"><%=description%></textarea>
        </div>
        <input class="ccm-image-slider-entry-sort" type="hidden" name="<?=$view->field('sortOrder')?>[]" value="<%=sort_order%>"/>
        <div class="form-group">
            <span class="btn btn-danger ccm-delete-image-slider-entry"><?php echo t('Delete Entry'); ?></span>
        </div>
    </div>
</script>
