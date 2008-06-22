<?php use_helper('I18n', 'Date', 'Text'); ?>

<div id="sf_planet_admin">
  
  <h1><?php echo __('Planet aggregated feeds list') ?></h1>
  
  <?php include_partial('sfPlanetAdmin/menu') ?>
  
  <?php include_partial('sfPlanetAdmin/messages') ?>
  
  <table>
    <thead>
      <tr>
        <th><?php echo __('Website') ?></th>
        <th><?php echo __('Feed url') ?></th>
        <th><?php echo __('Active') ?></th>
        <th><?php echo __('Periodicity') ?></th>
        <th><?php echo __('Last grabbed at') ?></th>
        <th><?php echo __('Entries') ?></th>
        <th><?php echo __('Is outdated') ?></th>
        <th><?php echo __('Actions') ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($sf_planet_feedList as $i => $sf_planet_feed): ?>
      <tr class="<?php echo 0 == $i % 2 ? '' : 'odd' ?>">
        <td><?php echo link_to(truncate_text($sf_planet_feed->getTitle(), 80), $sf_planet_feed->getHomepage()) ?></td>
        <td><?php echo link_to(__('Feed'), $sf_planet_feed->getFeedUrl()) ?></td>
        <td><?php echo var_export($sf_planet_feed->getIsActive(), true) ?></td>
        <td><?php echo $periodicity[$sf_planet_feed->getPeriodicity()] ?></td>
        <td>
          <?php if ($sf_planet_feed->getLastGrabbedAt()): ?>
            <?php echo time_ago_in_words($sf_planet_feed->getLastGrabbedAt(null), time()) ?>
          <?php else: ?>
            <?php echo __('never') ?>
          <?php endif; ?>
        </td>
        <td><?php echo $sf_planet_feed->countsfPlanetFeedEntrys() ?></td>
        <td><?php echo var_export($sf_planet_feed->isOutdated(), true) ?></td>
        <td class="sf_planet_admin_feed_actions">
          <?php echo link_to(__('Edit'), 'sfPlanetAdmin/edit?id='.$sf_planet_feed->getId()) ?> |
          <?php echo link_to(__('Delete'), 'sfPlanetAdmin/delete?id='.$sf_planet_feed->getId(), array('onclick' => 'if(confirm(\'Sure?\')){document.location=this.href;}return false')) ?> |
          <?php echo link_to(__('Fetch'), 'sfPlanetAdmin/fetchFeed?id='.$sf_planet_feed->getId()) ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  
</div>