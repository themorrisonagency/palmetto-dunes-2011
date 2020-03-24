<?php  
defined('C5_EXECUTE') or die("Access Denied."); 
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
?>

<script>
$(document).ready(function(){
        var attachFileManagerLaunch = function($obj) {
            $obj.click(function(){
                var oldLauncher = $(this);
                ConcreteFileManager.launchDialog(function (data) {
                    ConcreteFileManager.getFileDetails(data.fID, function(r) {
                        jQuery.fn.dialog.hideLoader();
                        var file = r.files[0];
                        oldLauncher.html(file.resultsThumbnailImg);
                        oldLauncher.next('.push-img').val(file.fID)
                    });
                });
            });
        }
		attachFileManagerLaunch($('.ccm-pick-slide-image'));
		
	//rich text editor
	$(function() {
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
	
	textarea {
		width: 100%;
		height: 80px;
	}
</style>
	<?php 		
		if ($rows){
			foreach($rows as $row){
				$push_id = $row['id'];
				$push_img = $row['push_img'];
				$push_logo = $row['push_logo'];
				$push_text = $row['push_text'];
				$push_link = $row['push_link'];
			}
		}		
		//var_dump($rows);
	?>
	

        <div class="form-group">
            <label><?php echo t('Push Image') ?></label>
            <div class="ccm-pick-slide-image">				
				<?php if($push_img) { ?>					
				<img src="<?php echo File::getByID($push_img)->getThumbnailURL('file_manager_listing');?>" />
				<?php } else { ?>
				<i class="fa fa-picture-o"></i>
				<?php } ?>				
            </div>
            <input type="hidden" name="push-img" class="push-img" value="<?php echo $push_img; ?>" />
        </div>
        <div class="form-group">
            <label><?php echo t('Push Logo Image') ?></label>
            <div class="ccm-pick-slide-image">
				<?php if($push_logo) { ?>					
				<img src="<?php echo File::getByID($push_logo)->getThumbnailURL('file_manager_listing');?>" />
				<?php } else { ?>
				<i class="fa fa-picture-o"></i>
				<?php } ?>				
            </div>
            <input type="hidden" name="push-logo-img" class="push-img" value="<?php echo $push_logo; ?>" />
        </div>
		<div class="form-group">
			<label><?php echo t('Push Text') ?></label>			
			<textarea class="redactor-content" name="push-text"><?php echo $push_text; ?></textarea>
		</div>

		<fieldset>
			<legend><?= t('Learn More Link') ?></legend>

			<div class="form-group">
				<select name="linkType" data-select="feature-link-type" class="form-control">
					<option
						value="0" <?= (empty($externalLink) && empty($internalLinkCID) ? 'selected="selected"' : '') ?>><?= t(
							'None') ?></option>
					<option
						value="1" <?= (empty($externalLink) && !empty($internalLinkCID) ? 'selected="selected"' : '') ?>><?= t(
							'Another Page') ?></option>
					<option value="2" <?= (!empty($externalLink) ? 'selected="selected"' : '') ?>><?= t(
							'External URL') ?></option>
				</select>
			</div>

			<div data-select-contents="feature-link-type-internal" style="display: none;" class="form-group">
				<?= $form->label('internalLinkCID', t('Choose Page:')) ?>
				<?= Loader::helper('form/page_selector')->selectPage('internalLinkCID', $internalLinkCID); ?>
			</div>

			<div data-select-contents="feature-link-type-external" style="display: none;" class="form-group">
				<?= $form->label('externalLink', t('URL')) ?>
				<?= $form->text('externalLink', $externalLink); ?>
			</div>

		</fieldset>


		
<script type="text/javascript">

    (function () {

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
    }());
</script>


		