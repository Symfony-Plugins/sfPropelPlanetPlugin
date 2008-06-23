<?php use_helper('I18n', 'Date') ?>

<div id="sf_planet">

  <?php if (sfConfig::get('app_planet_title')): ?>
  <h1 class="sf_planet_title">
    <?php echo link_to(sfConfig::get('app_planet_title'), '@sf_planet_home') ?>
  </h1>
  <?php endif; ?>
  
  <?php if (sfConfig::get('app_planet_description')): ?>
  <p class="sf_planet_description">
    <?php echo sfConfig::get('app_planet_description') ?>
  </p>
  <?php endif; ?>
  
  <?php if (sfConfig::get('app_planet_display_feeds_list')): ?>
  <div class="sf_planet_feeds">
    <h2><?php echo __('Aggregated feeds') ?></h2>
    <?php include_component('sfPlanet', 'listFeeds', array('currentFeed' => $feed)) ?>
  </div>
  <?php endif; ?>
  
  <h2>
    <?php if (!is_null($feed)): ?>
      <?php echo __('Entries from %site%', 
                    array('%site%' => !is_null($feed->getHomepage()) ? 
                                        link_to($feed->getTitle(), $feed->getHomepage()) :
                                        $feed->getTitle())) ?>
    <?php else: ?>
      <?php echo __('All entries') ?>
    <?php endif; ?>
  </h2>
  
  <div class="sf_planet_list">
    <?php if (isset($pager) && $pager->getNbResults() > 0): ?>
    <?php foreach ($pager->getResults() as $entry): ?>
      <div class="sf_planet_entry">
        <h3>
          <?php if (sfConfig::get('sf_escaping_strategy')): ?>
          <?php echo link_to($entry->getRawValue()->getTitle(), $entry->getLinkUrl()) ?>
          <?php else: ?>
          <?php echo link_to($entry->getTitle(), $entry->getLinkUrl()) ?>
          <?php endif; ?>
        </h3>
        <p class="sf_planet_entry_info">
          <?php if (!is_null($feed)): ?>
            <?php echo __('Published on %date%', array(
              '%date%' => format_date($entry->getPublishedAt(), 'G'),
            )) ?>
          <?php else: ?>
            <?php echo __('Published on %date%, on %feed_link%', array(
              '%date%'       => format_date($entry->getPublishedAt(), 'G'),
              '%feed_link%'  => !is_null($entry->getsfPlanetFeed()->getHomepage()) ?
                                  link_to($entry->getsfPlanetFeed()->getTitle(), 
                                          $entry->getsfPlanetFeed()->getHomepage()):
                                  $entry->getsfPlanetFeed()->getTitle(),
            )) ?>
          <?php endif; ?>
          <?php if ($entry->getAuthor()): ?>
            <?php echo __(' by %author%', array('%author%' => $entry->getAuthor())) ?>
          <?php endif; ?>
        </p>
        <div class="sf_planet_entry_content">
          <?php if (sfConfig::get('sf_escaping_strategy')): ?>
          <?php $content = $entry->getRawValue()->getContent() ?>
          <?php else: ?>
          <?php $content = $entry->getContent() ?>
          <?php endif; ?>
          <?php if (sfConfig::get('app_planet_xss_paranoid', true)): ?>
          <?php sfLoader::loadHelpers('Text'); ?>
          <?php echo simple_format_text(strip_tags($content)) ?>
          <?php else: ?>
          <?php echo $content ?>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
    
    <?php if ($pager->haveToPaginate()): ?>
    <p class="sf_planet_navigation">
      <?php echo pager_navigation($pager, '@sf_planet_home?page=') ?>
    </p>
    <?php endif; ?>
    
    <?php else: ?>
      <p><?php echo __('No entries yet') ?></p>
    <?php endif; ?>
  </div>
  
  <p class="sf_planet_footer">
    <?php echo __('Powered by symfony and sfPropelPlanetPlugin') ?>
  </p>

</div>