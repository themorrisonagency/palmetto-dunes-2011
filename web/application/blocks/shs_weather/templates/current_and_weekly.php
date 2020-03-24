<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>
<?php if (count($days) > 0): ?>
<section class="weather-current weather row">
  <article class="small-12 columns">
    <ul>
      <li class="locale current-date small-12 large-4 columns">
        <h1><?= $location ?></h1>
        <h2><?= date('l, F jS, Y',$days[0]['date']); ?></h2>
      </li>
      <li class="small-7 large-5 columns">
        <figure class="small-6 columns">
          <img src="<?= $this->getBlockURL(); ?>/img/<?= $days[0]['image']; ?>.svg" alt="<?= $days[0]['summary']; ?>" />
          <figcaption class="condition"><?= $days[0]['summary']; ?></figcaption>
        </figure>
        <p><?= $days[0]['summary']; ?></p>
        <h3 class="current-temp"><?= $days[0]['maxtemp']; ?>&deg;<?= $units; ?></h3>
      </li>
      <li class="temp-range small-5 large-3 columns">
        <ul class="temp-range row">
          <li class="small-12 medium-6 columns">
            <h4>High:</h4>
            <h5><?= $days[0]['high']; ?>&deg;<?= $units; ?></h5>
          </li>
          <li class="small-12 medium-6 columns">
            <h4>Low:</h4>
            <h5><?= $days[0]['low']; ?>&deg;<?= $units; ?></h5>
          </li>
        </ul>
      </li>
    </ul>
  </article>
</section>

<section class="weather-weekly weather-weekly-combo weather row">
  <?
    $len = count($days) - 1;
    foreach ($days as $day):
      if ($day['id'] != 0):
  ?>
  <article class="small-12 medium-3 columns <?php if ($day['id'] == $len) { echo 'end'; } ?>">
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
<?php
    endif;
  endforeach;
?>
</section>
<?php endif ?>