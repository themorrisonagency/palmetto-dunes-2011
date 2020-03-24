<? defined('C5_EXECUTE') or die("Access Denied.");
/*
The only editable part of this block is the link address
*/
switch ($status) {
	case 0:
		$status="inactive";
		break;
	case 1:
		$status="active";
		break;
}
?>
<div class="fireworks-widget <?php echo $status ?>">
	<a href="<?php echo $linkUrl ?>">
	    Shelter Cove <span>Fireworks</span>
	</a>
</div>