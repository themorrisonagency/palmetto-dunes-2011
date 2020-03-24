<? defined('C5_EXECUTE') or die("Access Denied.");
$linkCount = 1;
$faqEntryCount = 1;

/** @var array[] $rows */
$rows = (array) $rows;
?>

<div class='accordion-list unbound'>
    <?php
    if (count($rows)) {
        $i = 1;
        foreach ($rows as $row) {
            ?>
            <div class="accordion-item<? if ('1' == $i) { print ' active'; } ?>">
                <div class="accordion-title">
                    <h3><?php echo $row['title'] ?>&nbsp;</h3>
                </div>
                <div class="accordion-content">
                    <?php echo $row['description'] ?>
                </div>
            </div>
    <?php
            $i++;
        }
    }
    ?>
</div>
<script>
    (function() {
        var list = $('.accordion-list.unbound').removeClass('unbound');
        list.find('div.accordion-title').click(function() {
            var element = $(this).parent(), content = element.children('.accordion-content');

            if (element.hasClass('active')) {
                content.slideUp(function() {
                    element.removeClass('active');
                });
            } else {
                list.find('div.accordion-item.active').children('.accordion-title').click();
                content.slideDown(function() {
                    element.addClass('active');
                });
            }
            return false;
        })
    }());
</script>
