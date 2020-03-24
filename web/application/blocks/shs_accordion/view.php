<div class="accordion-list">
	<?php foreach($rows as $item){ ?>
		<div class="accordion-item">
			<div class="accordion-title">
				<h3><?php echo $item['title']; ?></h3>
			</div>
			<div class="accordion-content">
				<?php echo $item['description']; ?>
			</div>			
		</div>	
	<?php } ?>
</div>
<script>
    (function() {
        var list = $('.accordion-list');
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
