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

       <?php if($rows) {

           foreach ($rows as $row) {
			?>
           sliderEntriesContainer.append(_templateSlide({
                img: '<?php echo $row['img'] ?>',
                <?php if(File::getByID($row['img'])) { ?>
                image_url: '<?php echo File::getByID($row['img'])->getThumbnailURL('file_manager_listing');?>',
                <?php } else { ?>
                image_url: '',
               <?php } ?>
				descr: '<?php echo str_replace(array("\t", "\r", "\n"), "", addslashes($row['descr']))?>',
                title: '<?php echo addslashes($row['title']) ?>',
				read_more_link: '<?php echo addslashes($row['readMoreLink']) ?>',
                link_text: '<?php echo addslashes($row['linkText']) ?>',
                article_date: '<?php echo $row['articleDate'] ?>',
                sort_order: '<?php echo $row['sortOrder'] ?>'
            }));
            sliderEntriesContainer.find('.ccm-image-slider-entry:last-child div[data-field=entry-link-page-selector]').concretePageSelector({
                'inputName': 'internalLinkCID[]', 'cID': <?php if ($linkType == 1) { ?><?php echo intval($row['internalLinkCID'])?><?php } else { ?>false<?php } ?>
            });
        <?php }
        }?>

        doSortCount();
        sliderEntriesContainer.find('select[data-field=entry-link-select]').trigger('change');

        $('.ccm-add-image-slider-entry').click(function(){

           var thisModal = $(this).closest('.ui-dialog-content');
            sliderEntriesContainer.append(_templateSlide({
				image_url: '',
                img: '',
                descr: '',
                title: '',
				read_more_link: '',
                link_text: '',
                article_date: '',
                sort_order: ''
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
                'concrete5': {
                    filemanager: <?=$fp->canAccessFileManager()?>,
                    sitemap: <?=$tp->canAccessSitemap()?>,
                    lightbox: true
                }
            });
        });
    });

	function view_all_button(){
		if ($('#display_view_all_button').is(':checked')){
			$('.display_view_all_button_container').show();
		}
		$('#display_view_all_button').click(function(){
			if ($(this).is(':checked')){
				$('.display_view_all_button_container').show();
			} else {
				$('.display_view_all_button_container').hide();
			}
		});
	}

	$(document).ready(function(){
		view_all_button();
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

	.display_view_all_button_container {
		display: none;
	}
</style>

<div class="ccm-image-slider-block-container">
	<div class="form-group">
		<div class="checkbox">
			<label><input type="checkbox" name="displayViewAll" id="display_view_all_button" value="1"  <?php if ($displayViewAll==1) { echo 'checked'; } ?> /> <?php echo t('Display a View All button') ?></label>
		</div>
	</div>
	<div class="display_view_all_button_container well">
		<div class="form-group">
			<label>View All Button Text:</label>
			<input type="text" name="viewAllButtonText" value="<?php echo $viewAllButtonText; ?>" />
		</div>
		<div class="form-group">
			<label>View All Link:</label>
			<input type="text" name="viewAllButtonLink" value="<?php echo $viewAllButtonLink; ?>" />
		</div>
	</div>
</div>

<div class="ccm-image-slider-block-container">
    <span class="btn btn-success ccm-add-image-slider-entry"><?php echo t('Add Entry') ?></span>
    <div class="ccm-image-slider-entries"></div>
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
            <input type="hidden" name="<?php echo $view->field('img')?>[]" class="image-fID" value="<%=img%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Date') ?></label>
            <input type="text" name="<?php echo $view->field('articleDate')?>[]" value="<%=article_date%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Title') ?></label>
            <input type="text" name="<?php echo $view->field('title')?>[]" value="<%=title%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Description') ?></label>
            <div class="redactor-edit-content"></div>
            <textarea style="display: none" class="redactor-content" name="<?php echo $view->field('descr')?>[]"><%=descr%></textarea>
        </div>
        <div class="form-group">
            <label><?php echo t('Read More Link') ?></label>
            <input type="text" name="<?php echo $view->field('readMoreLink')?>[]" value="<%=read_more_link%>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Link Text') ?></label>
            <input type="text" name="<?php echo $view->field('linkText')?>[]" value="<%=link_text%>" />
        </div>

        <input class="ccm-image-slider-entry-sort" type="hidden" name="<?php echo $view->field('sortOrder')?>[]" value="<%=sort_order%>"/>
        <div class="form-group">
            <span class="btn btn-danger ccm-delete-image-slider-entry"><?php echo t('Delete Entry'); ?></span>
        </div>
	</div>
</script>

