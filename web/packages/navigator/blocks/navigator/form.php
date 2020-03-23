<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$pageSelector = Loader::helper('form/page_selector');
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission(); ?>

<style type="text/css">
    .panel-heading { cursor: move; }
    /*.panel-body { display: none; }*/
</style>

<div class="well bg-info">
    <?php  echo t('You can rearrange items if needed.'); ?>
</div>

<div class="items-container">
    
    <!-- DYNAMIC ITEMS WILL GET LOADED INTO HERE -->
    
</div>  

<span class="btn btn-success btn-add-item"><?php  echo t('Add Item') ?></span> 


<!-- THE TEMPLATE WE'LL USE FOR EACH ITEM -->
<script type="text/template" id="item-template">
    <div class="item panel panel-default" data-order="<%=sort%>">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <h5><i class="fa fa-arrows drag-handle"></i>
                    Link <%=parseInt(sort)+1%></h5>
                </div>
                <div class="col-xs-6 text-right">
                    <a href="javascript:deleteItem(<%=sort%>)" class="btn btn-delete-item btn-danger"><?php echo t('Delete')?></a>
                </div>
            </div>
        </div>
        <div class="panel-body form-horizontal">
            <div class="form-group">
                <label class="col-xs-3 control-label" for="linkType<%=sort%>"><?php echo t('Link Type:')?></label>
                <div class="col-xs-9">
                    <select class="form-control linktype-selector" name="linkType[]" id="linkType<%=sort%>" onchange="linktype()">
                        <option value="internal" <%= linkType=='internal' ? 'selected' : '' %>><?php echo t('Internal Link')?></option>
                        <option value="external" <%= linkType=='external' ? 'selected' : '' %>><?php echo t('External Link')?></option>
                    </select>
                </div>
            </div>
            <input class="item-sort" type="hidden" name="<?php  echo $view->field('sort')?>[]" value="<%=sort%>"/>
            <div class="linktype-internal" id="linktype-internal-<%=sort%>">
                
                <div class="form-group">
                    <label class="col-xs-3 control-label"><?php echo t('Select a Page')?></label>
                    <div class="col-xs-9" id="select-page-<%=sort%>">
                        <?php  $this->inc('elements/page_selector.php');?>
                    </div>
                </div>
                
            </div>
            <div class="linktype-external" id="linktype-external-<%=sort%>">
                
                <div class="form-group">
                    <label class="col-xs-3 control-label" for="url<%=sort%>"><?php echo t('External URL:')?></label>
                    <div class="col-xs-9">
                        <input class="form-control" type="text" name="url[]" id="url<%=sort%>" value="<%=url%>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 control-label" for="linkName<%=sort%>"><?php echo t('Link Title:')?></label>
                    <div class="col-xs-9">
                        <input class="form-control" type="text" name="linkName[]" id="linkName<%=sort%>" value="<%=linkName%>">
                    </div>
                </div>
                
            </div>
            
        </div>
    </div><!-- .item -->
</script>


<script type="text/javascript">

//Edit Button
var editItem = function(i){
    $(".item[data-order='"+i+"']").find(".panel-body").toggle();
};
//Delete Button
var deleteItem = function(i) {
    var confirmDelete = confirm('<?php  echo t('Are you sure?') ?>');
    if(confirmDelete == true) {
        $(".item[data-order='"+i+"']").remove();
        indexItems();
    }
};
var linktype = function(){
    $(".items-container .item").each(function(){
       var val = $(this).find(".linktype-selector").val();
       if(val=="internal"){
           $(this).find(".linktype-internal").show();
           $(this).find(".linktype-external").hide();
       }
       if(val=="external"){
           $(this).find(".linktype-internal").hide();
           $(this).find(".linktype-external").show();
       }
       
    });
}

//Index our Items
function indexItems(){
    $('.items-container .item').each(function(i) {
        $(this).find('.item-sort').val(i);
        $(this).attr("data-order",i);
    });
};

$(function(){
    
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
            linkType: '<?php  echo addslashes($item['linkType']) ?>',
           
            //PAGE SELECTOR
            <?php  if($item['pageID']){
                $page = Page::getByID($item['pageID']);
                $pageName = $page->getCollectionName();
            }
            ?>
            pageID: '<?php echo $item['pageID']?>',
            pageName: '<?php echo $pageName?>',
            
            url: '<?php echo $item['url']?>',
            linkName: '<?php echo $item['linkName']?>',
            
            sort: '<?php echo $item['sort'] ?>'
        }));
        <?php  
            }
        }
        ?>    
        
        //Init Index
        indexItems();
        linktype();

        
    //CREATE NEW ITEM
        
        $('.btn-add-item').click(function(){
            
            //Use the template to create a new item.
            var temp = $(".items-container .item").length;
            temp = (temp);
            itemsContainer.append(itemTemplate({
                //vars to pass to the template
                url: '',
                linkType: '',
                                
                //PAGE SELECTOR
                pageID: '',
                pageName: '',
                
                url: 'http://',
                linkName: '',
                
                sort: temp
            }));
            
            var thisModal = $(this).closest('.ui-dialog-content');
            var newItem = $('.items-container .item').last();
            thisModal.scrollTop(newItem.offset().top);
            
            //Init Index
            indexItems();
            linktype();
        });    

});
</script>