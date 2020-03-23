<?php
  $addUTM = false;
  $utmURL = '';
  $phone = '(844) 879-8851';
  if ($current == 'home') { $addUTM = true; $utmURL = '?utm_source=google&utm_medium=organic&utm_campaign=amp&nck=8448798851'; }
  if ($current == 'ppc') { $addUTM = true; $utmURL = '?nck=8669753029&utm_source=google&utm_medium=cpc&utm_campaign=PLM11%20-%20Palmetto%20Dunes%20Resort%20-%20AMP%20-%2090002%20-%2080'; $phone = '(866) 975-3029'; }
?>
  <footer>
    <p>Palmetto Dunes Oceanfront Resort<br><a href="https://www.google.com/maps/place/Palmetto+Dunes+Oceanfront+Resort/@32.177764,-80.726486,17z/data=!3m1!4b1!4m2!3m1!1s0x88fc79f40d10ee35:0xddd6dc64d3cc0ee2<?php if ($addUTM) echo $utmURL ?>" target="_blank">4 Queens Folly Road<br>Hilton Head Island, SC 29928</a></p>
    <p class="phone">Phone: <?php echo $phone ?></p>
    <div class="footer-bottom">
      <div id="footer-links"><span class="copyright">&copy; 2019 Palmetto Dunes Oceanfront Resort</span> <a href="http://www.greenwoodcr.com/<?php if ($addUTM) echo $utmURL ?>" target="_blank"><amp-img class="logo-gw" src="/application/themes/theme_palmetto/images/layout/logo-greenwood.svg" alt="Greenwood" width="168" height="47" layout="fixed"></amp-img></a></div>
        <div id="utility-nav">
          <ul>
            <li><a href="/privacy-policy<?php if ($addUTM) echo $utmURL ?>">Privacy Policy</a></li>
          </ul>
        </div>
      </div>        
  </footer>
  <div class="footerbuttons">
    <a href="https://www.palmettodunes.com/booking/vacation-rentals/hilton-head-vacation-rentals<?php if ($addUTM) echo $utmURL ?>" class="booknowbutton" target="_blank">Book Now</a>
    <a href="/<?php if ($addUTM) echo $utmURL ?>" class="booknowbutton web" target="_blank">Full Website</a>
  </div>
  