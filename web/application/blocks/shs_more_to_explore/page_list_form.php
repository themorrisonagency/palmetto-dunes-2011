<?php defined('C5_EXECUTE') or die("Access Denied.");
$c = Page::getCurrentPage();
$form = Loader::helper('form/page_selector');
?>
<div class="row pagelist-form">
    <div class="col-xs-6">

        <input type="hidden" name="pageListToolsDir" value="<?php echo Loader::helper('concrete/urls')->getBlockTypeToolsURL($bt) ?>/"/>

        <fieldset>
            <legend><?php echo t('Settings') ?></legend>
        </fieldset>

        <div class="form-group">
            <label class='control-label'><?php echo t('Number of Pages to Display') ?></label>
            <input type="text" name="num" value="<?php echo $num ?>" class="form-control">
        </div>

        <legend><?php echo t('Location') ?></legend>
        <div class="radio">
            <label>
                <input type="radio" name="cParentID" id="cEverywhereField"
                       value="0" <?php if ($cParentID == 0) { ?> checked<?php } ?> />
                <?php echo t('Everywhere') ?>
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="cParentID" id="cThisPageField"
                       value="<?php echo $c->getCollectionID() ?>" <?php if ($cParentID == $c->getCollectionID() || $cThis) { ?> checked<?php } ?>>
                <?php echo t('Beneath this page') ?>
            </label>
         </div>
        <div class="radio">
            <label>
                <input type="radio" name="cParentID" id="cOtherField"
                       value="OTHER" <?php if ($isOtherPage) { ?> checked<?php } ?>>
                <?php echo t('Beneath another page') ?>
            </label>
        </div>

        <div class="ccm-page-list-page-other" <?php if (!$isOtherPage) { ?> style="display: none" <?php } ?>>

            <div class="form-group">
                <?php echo $form->selectPage('cParentIDValue', $isOtherPage ? $cParentID : false); ?>
            </div>
        </div>

        <div class="ccm-page-list-all-descendents"
             style="<?php echo (!$isOtherPage && !$cThis) ? ' display: none;' : ''; ?>">
            <div class="form-group">
                <div class="checkbox">
                <label>
                    <input type="checkbox" name="includeAllDescendents" id="includeAllDescendents"
                           value="1" <?php echo $includeAllDescendents ? 'checked="checked"' : '' ?> />
                    <?php echo t('Include all child pages') ?>
                </label>
                </div>
            </div>
        </div>

        <legend><?php echo t('Sort') ?></legend>
        <div class="form-group">
            <select name="orderBy" class="form-control">
                <option value="display_asc" <?php if ($orderBy == 'display_asc') { ?> selected <?php } ?>>
                    <?php echo t('Sitemap order') ?>
                </option>
                <option value="chrono_desc" <?php if ($orderBy == 'chrono_desc') { ?> selected <?php } ?>>
                    <?php echo t('Most recent first') ?>
                </option>
                <option value="chrono_asc" <?php if ($orderBy == 'chrono_asc') { ?> selected <?php } ?>>
                    <?php echo t('Earliest first') ?>
                </option>
                <option value="alpha_asc" <?php if ($orderBy == 'alpha_asc') { ?> selected <?php } ?>>
                    <?php echo t('Alphabetical order') ?>
                </option>
                <option value="alpha_desc" <?php if ($orderBy == 'alpha_desc') { ?> selected <?php } ?>>
                    <?php echo t('Reverse alphabetical order') ?>
                </option>
                <option value="random" <?php if ($orderBy == 'random') { ?> selected <?php } ?>>
                    <?php echo t('Random') ?>
                </option>
            </select>
        </div>

        <legend><?php echo t('Output') ?></legend>


        <div class="form-group">
            <label class="control-label"><?php echo t('Title') ?></label>
            <input type="text" class="form-control" name="pageListTitle" value="<?php echo $pageListTitle?>" />
        </div>

        <div class="loader">
            <i class="fa fa-cog fa-spin"></i>
        </div>
    </div>

    <div class="col-xs-6" id="ccm-tab-content-page-list-preview">
        <div class="preview">
            <fieldset>
                <legend><?php echo t('Included Pages') ?></legend>
            </fieldset>
            <div class="render">

            </div>
            <div class="cover"></div>
        </div>
    </div>

</div>

<style type="text/css">
    div.pagelist-form div.loader {
        position: absolute;
        line-height: 34px;
    }

    div.pagelist-form div.cover {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }

    div.pagelist-form div.render .ccm-page-list-title {
        font-size: 12px;
        font-weight: normal;
    }

    div.pagelist-form label.checkbox,
    div.pagelist-form label.radio {
        font-weight: 300;
    }

</style>
<script type="application/javascript">
    Concrete.event.publish('pagelist.edit.open');
    $(function() {
        $('input[name=filterByRelated]').on('change', function() {
            if ($(this).is(':checked')) {
                $('div[data-row=related-topic]').show();
            } else {
                $('div[data-row=related-topic]').hide();
            }
        }).trigger('change');
    });

</script>

