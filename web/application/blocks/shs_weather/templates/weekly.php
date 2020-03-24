<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<?php if (count($days) > 0): ?>
<article class="small-12 columns">
<section class="weather-weekly weather row">
  <?
    $len = count($days) - 1;
    foreach ($days as $day):
  ?>
  <article class="small-12 medium-2 columns <?php if ($day['id'] == $len) { echo 'end'; } ?>">
    <ul class="row">
      <li class="day small-4 medium-12 columns">
        <h1><?= date('l',$day['date']); ?></h1>
        <h2 class="date"><?= date('F j',$day['date']); ?></h2>
      </li>
      <li class="small-8 medium-12 columns">
        <section class="row">
          <figure class="small-6 medium-12 columns">
            <img src="<?= $this->getBlockURL(); ?>/img/<?= $day['image']; ?>.svg" alt="<?= $day['summary']; ?>" />
            <figcaption class="condition"><?= $day['summary']; ?></figcaption>
          </figure>
          <ul class="temp-range small-6 medium-12 columns">
            <li>
              <h4>High:</h4>
              <h5><?= $day['high']; ?>&deg;<?= $units; ?></h5>
            </li>
            <li>
              <h4>Low:</h4>
              <h5><?= $day['low']; ?>&deg;<?= $units; ?></h5>
            </li>
          </ul>
        </section>  
      </li>
    </ul>
  </article>
<?php endforeach; ?>
</section>
</article>
<?php endif ?>