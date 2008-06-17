<?php foreach ($entries as $entry): ?>
  <h2>
    <?php echo link_to($entry->getTitle(), $entry->getLinkUrl()) ?>
    <small>from <?php echo link_to($entry->getsfPlanetFeed()->getTitle(), 
                                   $entry->getsfPlanetFeed()->getHomepage()) ?></small>
  </h2>
  <div>
    <?php echo $entry->getContent() ?>
  </div>
<?php endforeach; ?>