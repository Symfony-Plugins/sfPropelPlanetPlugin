<?php
/**
 * Feeds grabbing task
 *
 */
class sfPlanetFeedGrabTask extends sfPlanetBaseTask
{
  
  /**
   * Task config
   *
   */
  public function configure()
  {
    $this->aliases          = array('planet-feed-grab');
    $this->namespace        = 'planet';
    $this->name             = 'feed-grab';
    $this->briefDescription = 'Grabs last planet feeds entries and store them in the database';
    $this->detailedDescription = <<<EOF
The [planet:grab-entries|INFO] updates perempted feed entries, ones which the 
[periodicity|COMMENT] value has been exceeded.
EOF;
    
    $this->addArguments(array(
      new sfCommandArgument('feed-slug', sfCommandArgument::OPTIONAL, 'The feed slug (if not provided, all active feeds entries will be grabbed)'),
      new sfCommandArgument('application', sfCommandArgument::OPTIONAL, 'The application name', self::getDefaultAppName()),
    ));
    
    $this->addOptions(array(
      new sfCommandOption('force-refresh', 'f', sfCommandOption::PARAMETER_NONE, 'Forces to grab and update all feeds, including those which are not perempted'),
      new sfCommandOption('verbose', 'v', sfCommandOption::PARAMETER_NONE, 'Prints verbose messages during grabbing'),      
    ));
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
    
    if ($slug = $arguments['feed-slug'])
    {
      if (is_null($feed = sfPlanetFeedPeer::retrieveBySlug($slug)))
      {
        throw new sfCommandException(sprintf('Feed "%s" does not exist', $slug));
      }
      
      if ($feed->isPerempted() || $options['force-refresh'])
      {
        $this->grabFeedEntries($feed, $options['verbose']);
      }
      else
      {
        $this->logSection('results', 'feed is up to date');
      }
      
      return;
    }
    
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
      $this->grabFeedEntries($feed, $options['verbose']);
      $n++;
    }
    
    $this->logSection('result', ($n > 1 ? $n.' feeds' : ($n > 0 ? 'one feed' : 'no feed')) . ' fetched');
  }
  
}