<?php
/**
 * Feeds grabbing task
 *
 */
class sfPlanetGrabFeedsTask extends sfBaseTask
{
  
  /**
   * Task config
   *
   */
  public function configure()
  {
    $this->aliases          = array('planet-grab-feeds');
    $this->namespace        = 'planet';
    $this->name             = 'grab-feeds';
    $this->briefDescription = 'Grabs last planet feeds entries and store them in the database';
    
    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
    ));
    
    $this->addOption('force-refresh', 'f', sfCommandOption::PARAMETER_NONE, 'Forces to grab and update all feeds, including those which are not perempted');
  }
  
  /**
   * Task execution
   *
   * @param array $arguments
   * @param array $options
   */
  protected function execute($arguments = array(), $options = array())
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration($arguments['application'], 'cli', true);
    $databaseManager = new sfDatabaseManager($configuration);
    
    $c = new Criteria();
    $c->add(sfPlanetFeedPeer::IS_ACTIVE, true);
    $n = 0;
    
    if ($options['force-refresh'])
    {
      $feeds = sfPlanetFeedPeer::doSelect($c);
    }
    else
    {
      $feeds = sfPlanetFeedPeer::getPerempted($c);
    }
    
    foreach ($feeds as $feed)
    {
      $this->logSection('feed', sprintf('Fetching %s', $feed->getTitle()));
      $parsed_feed = sfFeedPeer::createFromWeb($feed->getFeedUrl());
      $m = 0;
      foreach ($parsed_feed->getItems() as $item)
      {
        $entry = sfPlanetFeedEntryPeer::createFromFeedItem($item, $feed);
        $entry->save();
        $m++;
      }
      $this->logSection('entries', ($m > 1 ? $m.' entries' : ($m > 0 ? 'one entry' : 'no entry')) . ' fetched');
      $feed->setLastGrabbedAt(time());
      $feed->save();
      $n++;
    }
    $this->logSection('result', ($n > 1 ? $n.' feeds' : ($n > 0 ? 'one feed' : 'no feed')) . ' fetched');
  }
  
}