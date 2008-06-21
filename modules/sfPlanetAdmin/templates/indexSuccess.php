<?php sfLoader::loadHelpers('I18n'); ?>

<h1>SfPlanetAdmin List</h1>

<table>
  <thead>
    <tr>
      <th>Title</th>
      <th>Homepage</th>
      <th>Feed url</th>
      <th>Is active</th>
      <th>Periodicity</th>
      <th>Last grabbed at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($sf_planet_feedList as $sf_planet_feed): ?>
    <tr>
      <td><?php echo link_to($sf_planet_feed->getTitle(), 'sfPlanetAdmin/edit?id='.$sf_planet_feed->getId()) ?></td>
      <td><?php echo link_to(__('Homepage'), $sf_planet_feed->getHomepage()) ?></td>
      <td><?php echo link_to(__('Feed'), $sf_planet_feed->getFeedUrl()) ?></td>
      <td><?php echo $sf_planet_feed->getIsActive() ?></td>
      <td><?php echo $periodicity[$sf_planet_feed->getPeriodicity()] ?></td>
      <td><?php echo $sf_planet_feed->getLastGrabbedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p><a href="<?php echo url_for('sfPlanetAdmin/edit') ?>">Create</a></p>
