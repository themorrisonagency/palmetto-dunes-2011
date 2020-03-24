<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<?php if ($days[0]['low'] != 0 && $days[0]['high'] != 0 && $days[0]['maxtemp'] != 0 ): ?>
  <?php if (count($days) > 0): ?>
  <section class="weather-current weather">
    <ul>
        <li class="curr-weather">
          <figure class="small-6 columns">
            <img src="<?= $this->getBlockURL(); ?>/img/<?= $days[0]['image']; ?>.svg" alt="<?= $days[0]['summary']; ?>" />
            <figcaption class="condition"><?= $days[0]['summary']; ?></figcaption>
          </figure>
          <p><?= $days[0]['summary']; ?></p>
          <h3 class="current-temp"><?= $days[0]['maxtemp']; ?>º<?= $units; ?></h3>
        </li>
      </ul>
  </section>
  <?php endif ?>
<?php endif ?>