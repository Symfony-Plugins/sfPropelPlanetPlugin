<?php
/**
 * Task to add a feed to the planet
 *
 */
class sfPlanetFeedAddTask extends sfPlanetBaseTask 
{
  
  /**
   * Task config
   *
   */
  public function configure()
  {
    $this->aliases          = array('planet-feed-add');
    $this->namespace        = 'planet';
    $this->name             = 'feed-add';
    $this->briefDescription = 'Adds a new feed to the planet';
    
    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
      new sfCommandArgument('feed-url', sfCommandArgument::REQUIRED, 'The feed url (rss, atom) to grab and add to the planet'),
    ));
    
    $this->addOptions(array(
      new sfCommandOption('non-activate', 'i', sfCommandOption::PARAMETER_NONE, 'Do not activate the feed after saving it'),
      new sfCommandOption('periodicity', 'p', sfCommandOption::PARAMETER_OPTIONAL, 'Peremption time in seconds', 86400),
      new sfCommandOption('no-grab', 's', sfCommandOption::PARAMETER_NONE, 'Do not grab and store the feed entries'),
      new sfCommandOption('max-entries', 'm', sfCommandOption::PARAMETER_OPTIONAL, 'Max number of entries to grab and store', 0),
      
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
    
    $url = $arguments['feed-url'];
    $this->logSection('add', $url);
    
    try
    {
      $feed = sfFeedPeer::createFromWeb($url);
    }
    catch (Exception $e)
    {
      throw new sfCommandException(sprintf('No feed can be retrieved from url %s', $url));
    }
    
    // Create feed
    if (sfPlanetFeedPeer::feedExists($url))
    {
      
      throw new sfCommandException(sprintf('Feed "%s" already exists in database', $url));
    }
    
    $newFeed = new sfPlanetFeed();
    
    $newFeed->fromArray(array(
      'title'       => $feed->getTitle(),
      'description' => $feed->getDescription(),
      'homepage'    => $feed->getLink(),
      'feed_url'    => $url,
      'is_active'   => !$options['non-activate'],
      'periodicity' => (int)$options['periodicity'],
    ), BasePeer::TYPE_FIELDNAME); 
    
    $newFeed->save();
    
    $this->logSection('feed', sprintf('added feed "%s"', $newFeed->getSlug()));
    
    // Grab entries
    if (!$options['no-grab'])
    {
      $i = 0;
      foreach ($feed->getItems() as $item)
      {
        $this->grabFeedEntry($item, $newFeed);
        $i++;
        
        if ($options['max-entries'] > 0 && $i >= $options['max-entries'])
        {
          break;
        }
      }
      if ($i > 0)
      {
        $this->logSection('result', sprintf('saved %d entries for feed %s', $i, $newFeed->getSlug()));
      }
      else
      {
        $this->logSection('result', 'no entry found');
      }
    }
  }
  
}