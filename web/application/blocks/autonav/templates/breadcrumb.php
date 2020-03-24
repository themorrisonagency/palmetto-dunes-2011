<? defined('C5_EXECUTE') or die("Access Denied.");

$navItems = $controller->getNavItems(true); // Ignore exclude from nav
$c = Page::getCurrentPage();

if (count($navItems) > 0) {
    
    echo '<div class="breadcrumb" role="navigation" aria-label="breadcrumb">'; //opens the top-level menu
    echo '<ul>';
    
	$i=0;
    foreach ($navItems as $ni) {
		$i++;
        if (($ni->isCurrent) || ($i%2==0)) {
            echo '<li class="active">' . $ni->name . '</li>';
        } else {
            echo '<li><a href="' . $ni->url . '" target="' . $ni->target . '">' . $ni->name . '</a>';
        }
		if (!$ni->isCurrent){
			echo '<span class="sep">&nbsp;&nbsp;&gt;&nbsp;</span>';
		}
		echo '</li>';
		
    }
    
    echo '</ul>';
    echo '</div>'; //closes the top-level menu
    
} else if (is_object($c) && $c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item"><?=t('Empty Auto-Nav Block.')?></div>
<? }