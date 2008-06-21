<?php if (count($feeds) > 0): ?>
  <ul class="sf_planet_feeds_list">
  <?php foreach ($feeds as $feed): ?>
    <?php if (isset($currentFeed) && !is_null($currentFeed) && $currentFeed->getId() == $feed->getId()): ?>
      <li><strong><?php echo $feed->getTitle() ?></strong>
    <?php else: ?>
      <li><?php echo link_to($feed->getTitle(), '@sf_planet_feed_home?slug='.$feed->getSlug()) ?>
    <?php endif; ?>
      [<?php echo link_to(__('feed'), $feed->getFeedUrl()) ?>]
    </li>
  <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p><?php echo __('Currently no feed in the planet') ?></p>
<?php endif; ?>