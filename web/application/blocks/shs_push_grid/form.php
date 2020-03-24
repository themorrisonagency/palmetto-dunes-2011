<?php 
defined('C5_EXECUTE') or die("Access Denied."); 
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
$ps = Loader::helper('form/page_selector');
?>

<script>
	//sort
	var doSortCount = function(){
			$('.ccm-image-slider-entry').each(function(index) {
			$(this).find('.ccm-image-slider-entry-sort').val(index);
		});
	};

	var attachSortAsc = function($obj) {
		$obj.click(function(){
		var myContainer = $(this).closest($('.ccm-image-slider-entry'));
		myContainer.insertBefore(myContainer.prev('.ccm-image-slider-entry'));
			doSortCount();
		});
	}

        var attachSortDesc = function($obj) {
            $obj.click(function(){
               var myContainer = $(this).closest($('.ccm-image-slider-entry'));
               myContainer.insertAfter(myContainer.next('.ccm-image-slider-entry'));
               doSortCount();
            });
        }	
	
	attachSortAsc($('i.fa-sort-asc'));
	attachSortDesc($('i.fa-sort-desc'));
	
	//rich text editor
	$(function() {
		$('.redactor-content').redactor({
			minHeight: '200',
			buttons: ['formatting','bold','italic','deleted','unorderedlist','orderedlist','alignment','link','horizontalrule','html'],
				'concrete5': {
					filemanager: <?=$fp->canAccessFileManager()?>,
					sitemap: <?=$tp->canAccessSitemap()?>,
					lightbox: true
                }
		});
		
		$('select#grid-selector').on('change', function () {
			$('.ccm-push-grid-edit .push-block').hide();
			var bid = $(this).val();
            $('#'+bid).fadeIn();
        }).trigger('change');
	});
	
	//image picker
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
	attachFileManagerLaunch($('.ccm-pick-slide-image'));
	
	$(document).ready(function(){
        $('select[data-select=feature-link-type]').on('change', function () {
            if ($(this).val() == '0') {
                $('div[data-select-contents=feature-link-type-internal]').hide();
                $('div[data-select-contents=feature-link-type-external]').hide();
            }
            if ($(this).val() == '1') {
                $('div[data-select-contents=feature-link-type-internal]').show();
                $('div[data-select-contents=feature-link-type-external]').hide();
            }
            if ($(this).val() == '2') {
                $('div[data-select-contents=feature-link-type-internal]').hide();
                $('div[data-select-contents=feature-link-type-external]').show();
            }
        }).trigger('change');
	});	
	
	
</script>

<style>
	.ccm-push-grid-edit .form-group label { display: block; }
	.ccm-push-grid-edit select, .ccm-push-grid-edit input[type="text"] { width: 50%; }
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
</style>

<?php

if ($res_grid) {
	$section = array();
	$i=0;
	foreach($res_grid as $grid){
		$i++;
		$section[$i]['title'] = $grid['title'];
		$section[$i]['img_title'] = $grid['img_title'];
		$section[$i]['img'] = $grid['img'];
		$section[$i]['descr'] = $grid['descr'];
		$section[$i]['link_id'] = $grid['link_id'];
		$section[$i]['link_url'] = $grid['link_url'];
		$section[$i]['link_text'] = $grid['link_text'];
	}
}

?>

<!-- section 1 -->
<div class="ccm-push-grid-edit">
	<div class="form-group">
		<select name="gridselector" id="grid-selector">
			<?php
				for ($i=1; $i < 9; $i++) {
					echo '<option value="block' . $i . '">Block ' . $i . ': ' . $section[$i]['img_title'] . '</option>';
				}
			?>
		</select>
	</div>
<?php
	for ($i=1; $i < 9; $i++) { ?>
<div id="block<? print $i; ?>" class="push-block">
	<div class="form-group">
		<label class="control-label"><?php echo t('Title') ?></label>
		<input type="text" name="title<? print $i; ?>" value="<?php if ($section[$i]['title']) { echo $section[$i]['title']; } ?>" />
	</div>	
	<div class="form-group">
		<?php  echo $form->label("img_title[$i]", t("Category Title")); ?>
		<input type="text" name="img_title<? print $i; ?>" value="<?php if ($section[$i]['img_title']) { echo $section[$i]['img_title']; } ?>" />
	</div>
	<div class="form-group">
		<label><?php echo t('Image') ?></label>
		<div class="ccm-pick-slide-image">
			<?php if ($section[$i]['img']){ ?>
				<img src="<?php echo File::getByID($section[$i]['img'])->getThumbnailURL('file_manager_listing');?>" />
			<?php } else { ?>
				<i class="fa fa-picture-o"></i>
			<?php } ?>
		</div>
		<input type="hidden" name="img<? print $i; ?>" class="image-fID" value="<?php if ($section[$i]['img']){ echo $section[$i]['img']; } ?>" />
	</div>
	<div class="form-group">
		<label><?php echo t('Text') ?></label>
		<textarea class="redactor-content" name="descr<? print $i; ?>"><?php if ($section[$i]['descr']) { echo $section[$i]['descr']; } ?></textarea>
	</div>
	
	<!-- link -->
	<fieldset>
		<legend><?= t('Link') ?></legend>

		<div class="form-group">
			<select name="linkType<? print $i; ?>" data-select="feature-link-type" class="form-control">
				<option
					value="0" <?= (empty($section[$i]['link_url']) && empty($section[$i]['link_id']) ? 'selected="selected"' : '') ?>><?= t(
						'None') ?></option>
				<option
					value="1" <?= (empty($section[$i]['link_url']) && !empty($section[$i]['link_id']) ? 'selected="selected"' : '') ?>><?= t(
						'Another Page') ?></option>
				<option value="2" <?= (!empty($section[$i]['link_url']) ? 'selected="selected"' : '') ?>><?= t(
						'External URL') ?></option>
			</select>
		</div>

		<div data-select-contents="feature-link-type-internal" style="display: none;" class="form-group">
			<?= $form->label("link_id$i", t('Choose Page:')) ?>
			<?= Loader::helper('form/page_selector')->selectPage("link_id$i", $section[$i]['link_id']); ?>
		</div>

		<div data-select-contents="feature-link-type-external" style="display: none;" class="form-group">
			<?= $form->label("link_url$i", t('URL')) ?>
			<?= $form->text("link_url$i", $section[$i]['link_url']); ?>
		</div>

	</fieldset>	
	<!-- link -->
	
	<div class="form-group">
		<label><?php echo t('Link Text') ?></label>
		<input type="text" name="link_text<? print $i; ?>" value="<?php if ($section[$i]['link_text']) { echo $section[$i]['link_text']; } ?>" />
	</div>	
	
	<input class="ccm-image-slider-entry-sort" type="hidden" name="order<? print $i; ?>" value="<? print $i; ?>" />
</div>
<?php } ?>
</div>