<?php
  $addUTM = false;
  $utmURL = '';
  $phone = '844-879-8851';
  if ($current == 'home') { $addUTM = true; $utmURL = '?utm_source=google&utm_medium=organic&utm_campaign=amp&nck=8448798851'; }
  if ($current == 'ppc') { $addUTM = true; $utmURL = '?nck=8669753029&utm_source=google&utm_medium=cpc&utm_campaign=PLM11%20-%20Palmetto%20Dunes%20Resort%20-%20AMP%20-%2090002%20-%2080'; $phone = '866-975-3029'; }
?>
	<header>
		<div class="branding">
		  <a href="/<?php if ($addUTM) echo $utmURL ?>" title="Return to Home Page"><amp-img src="/application/themes/theme_palmetto/images/logo.svg" alt="Palmetto Dunes Oceanfront Resort" width="200" height="45"></amp-img></a>
		</div>
		<a href="tel:<?php echo $phone ?>" class="header-phone"><?php echo $phone ?></a>
		<?php if ($current != 'ppc') { ?>
		<!-- <button on="tap:sidebar.toggle" class="hamburger">&#9776;</button> -->
		<?php } ?>	
	</header>