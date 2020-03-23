<?php
defined('C5_EXECUTE') or die("Access Denied.");

$ak = \Concrete\Core\Attribute\Key\CollectionKey::getByHandle('special_offer_topics');
if (is_object($ak)) {
    $treeID = $ak->getController()->getTopicTreeID();
}
if (!$treeID) {
    $treeID = 0;;
}

$pageSelector = Loader::helper('form/page_selector');
?>

<fieldset>
    <legend><?=t('Filtering')?></legend>

    <div class="form-group">
        <div class="radio">
          <label>
            <?php echo $form->radio('filterBy', "all", $filterBy);?>
            <?php echo t('Show All')?>
          </label>
        </div>
        <div class="radio">
          <label>
            <?php echo $form->radio('filterBy', "topic", $filterBy);?>
            <?php echo t('Filter by Offer Category (Topic)')?>
          </label>
        </div>
        <div class="radio">
          <label>
            <?php echo $form->radio('filterBy', "selected", $filterBy);?>
            <?php echo t('Show Only Selected Offers')?>
          </label>
        </div>
    </div>

    <div class="form-group group-topic" style="<?php if($filterBy!='topic') { echo'display:none'; } ?>">
        <label class="control-label" for="filterByTopicID"><?= t('Select Offer Category (Topic)') ?></label>
        <input type="hidden" name="filterByTopicID" value="<?=$filterByTopicID?>">
        <div id="ccm-block-special-offer-topic-tree-wrapper"></div>
    </div>

    <div class="form-group group-selected" style="<?php if($filterBy!='selected') { echo'display:none'; } ?>">
        <h4><?= t('Selected Offers') ?></h4>
        <div class="items-container">

            <!-- DYNAMIC ITEMS WILL GET LOADED INTO HERE -->

        </div>

        <span class="btn btn-success btn-add-item"><?php  echo t('Add Offer') ?></span>
    </div>
</fieldset>

<!-- THE TEMPLATE WE'LL USE FOR EACH ITEM -->
<script type="text/template" id="item-template">
    <div class="item panel panel-default" id="panel-<%=sort%>" data-order="<%=sort%>" data-pagetype="<%=pageType%>">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <h5><i class="fa fa-arrows drag-handle"></i>
                    <%=parseInt(sort).ordinate(1) %> Offer: <%=pageName%></h5>
                </div>
                <div class="col-xs-6 text-right">
                    <a href="javascript:expandCollapse(<%=sort%>)" class="btn btn-collapse-toggle btn-default"><?php echo t('Expand/Collapse')?></a>
                    <a href="javascript:deleteItem(<%=sort%>)" class="btn btn-sm btn-delete-item btn-danger"><?php echo t('Remove Offer')?></a>
                </div>
            </div>
        </div>
        <div class="panel-body form-horizontal collapse">
            <input class="item-sort" type="hidden" name="<?php  echo $view->field('sort')?>[]" value="<%=sort%>"/>
            <div class="linktype-internal" id="linktype-internal-<%=sort%>">
                
                <div class="form-group">
                    <div class="ccm-page-selector-container" data-cid="<%=pageID%>" data-input-name="pageID[]"></div>
                </div>

            </div>

        </div>
    </div><!-- .item -->
</script>

<style media="screen">
    .ccm-summary-selected-item .alert { padding: 0; }
    .ccm-ui .ccm-page-selector-container { margin: 0 1em; }
</style>

<script type="text/javascript">
    $(function() {
        var toolsURL = '<?php echo Loader::helper('concrete/urls')->getToolsURL('tree/load'); ?>';
        $('#ccm-block-special-offer-topic-tree-wrapper').append($('<div id=ccm-block-special-offer-topic-tree>').ccmtopicstree({
            'treeID': '<?=$treeID?>',
            'chooseNodeInForm': true,
            <? if ($filterByTopicID) { ?>
                'selectNodesByKey': [<?php echo intval($filterByTopicID) ?>],
            <? } ?>
            'onSelect' : function(select, node) {
                if (select) {
                    $('input[name=filterByTopicID]').val(node.data.key);
                } else {
                    $('input[name=filterByTopicID]').val('');
                }
            }
        }));

        $('input[name="filterBy"]').change(function(){
            if ($(this).val() == 'topic') {
                $('.group-topic').show();
                $('.group-selected').hide();
            } else if ($(this).val() == 'selected'){
                $('.group-topic').hide();
                $('.group-selected').show();
            } else {
                $('.group-topic').hide();
                $('.group-selected').hide();
            }
        });
    });

//Edit Button
var editItem = function(i){
    $(".item[data-order='"+i+"']").find(".panel-body").toggle();
};
//Delete Button
var deleteItem = function(i) {
    var confirmDelete = confirm('<?php  echo t('Are you sure?') ?>');
    if(confirmDelete == true) {
        $("#panel-"+i).remove();
        indexItems();
    }
};
//Expand/Collapse
var expandCollapse = function(i) {
    var $panel = $("#panel-"+i).find(".panel-body");
    if ($panel.is(":visible")) {
        $panel.slideUp();
    } else {
        $panel.slideDown();
    }
};
//Index our Items
function indexItems(){
    $('.items-container .item').each(function(i) {
        $(this).find('.item-sort').val(i);
        $(this).attr("data-order",i);
    });
};
//Page Selectors
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

$(function(){

    Number.prototype.ordinate = function(add){
        var num = this + add,
            numStr = num.toString(),
            last = numStr.slice(-1),
            len = numStr.length,
            ord = '';
        switch (last) {
            case '1':
                ord = numStr.slice(-2) === '11' ? 'th' : 'st';
                break;
            case '2':
                ord = numStr.slice(-2) === '12' ? 'th' : 'nd';
                break;
            case '3':
                ord = numStr.slice(-2) === '13' ? 'th' : 'rd';
                break;
            default:
                ord = 'th';
                break;
        }
        return num.toString() + ord;
    };

    <?php if ($filterByTopicID && $filterByTopicID > 0) { ?>
        //Clean up legacy block data
        $('input[name="filterBy"][value="topic"]').attr('checked',true).trigger('change');
    <?php } ?>


    //DEFINE VARS

        //Define container and items
        var itemsContainer = $('.items-container');
        var itemTemplate = _.template($('#item-template').html());

    //BASIC FUNCTIONS

        //Make items sortable. If we re-sort them, re-index them.
        $(".items-container").sortable({
            handle: ".panel-heading",
            update: function(){
                indexItems();
            }
        });

    //LOAD UP OUR ITEMS

        //for each Item, apply the template.
        <?php
        if($items) {
            foreach ($items as $item) {
        ?>
        itemsContainer.append(itemTemplate({
            //define variables to pass to the template.
            url: '<?php  echo addslashes($item['url']) ?>',

            //PAGE SELECTOR
            <?php  if($item['pageID']){
                $page = Page::getByID($item['pageID']);
                $pageName = $page->getCollectionName();
                $pageType = $page->getPageTypeHandle();
            }
            ?>
            pageID: '<?php echo $item['pageID']?>',
            pageName: <?php echo json_encode($pageName)?>,

            pageType: '<?php echo $pageType?>',

            sort: '<?php echo $item['sort'] ?>'
        }));
        <?php
            }
        }
        ?>

        //Init Index
        indexItems();
        attachPageSelector($('.ccm-ui .ccm-page-selector-container'));


    //CREATE NEW ITEM

        $('.btn-add-item').click(function(){
            //Collapse any open panels
            $(".item .panel-body:visible").slideUp('slow');

            //Use the template to create a new item.
            var temp = $(".items-container .item").length;
            temp = (temp);
            itemsContainer.append(itemTemplate({
                //vars to pass to the template
                url: '',

                //PAGE SELECTOR
                pageID: '',
                pageName: '',
                pageType: '',

                sort: temp
            }));

            var thisModal = $(this).closest('.ui-dialog-content');
            var newItem = $('.items-container .item').last();
            newItem.find('.panel-body').removeClass('collapse').slideDown(400, function(){
                thisModal.scrollTop($('.items-container').height());
            });

            //Init Index
            indexItems();
            attachPageSelector(newItem.find('.ccm-page-selector-container'));
        });

});
</script>