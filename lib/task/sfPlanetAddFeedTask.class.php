<?php
/**
 * Task to add a feed to the planet
 *
 */
class sfPlanetAddFeedTask extends sfBaseTask 
{
  
  /**
   * Task config
   *
   */
  public function configure()
  {
    $this->aliases          = array('planet-add-feeds');
    $this->namespace        = 'planet';
    $this->name             = 'add-feed';
    $this->briefDescription = 'Adds a new feed to the planet';
    
    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
    ));
    
    $this->addOptions(array(
      new sfCommandOption('feed-url', 'u', sfCommandOption::PARAMETER_REQUIRED, 'The feed url to add'),
      new sfCommandOption('active', 'a', sfCommandOption::PARAMETER_NONE, 'Activate the feed'),
      new sfCommandOption('periodicity', 'p', sfCommandOption::PARAMETER_OPTIONAL, 'Periodicity in seconds', 3600),
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
    
    $url = $options['feed-url'];
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
      'is_active'   => $options['active'],
      'periodicity' => $options['periodicity'],
    ), BasePeer::TYPE_FIELDNAME); 
    $newFeed->save();
    $this->logSection('feed', sprintf('added feed "%s"', $feed->getTitle()));
    
    // Grab entries
    if (!$options['no-grab'])
    {
      $i = 0;
      foreach ($feed->getItems() as $item)
      {
        $entry = sfPlanetFeedEntryPeer::createFromFeedItem($item, $newFeed);
        try {
          $entry->save();
          $i++;
          $this->logSection('entry', sprintf('%d. Entry "%s" saved', $i, $item->getTitle()));
        }
        catch (PropelException $e)
        {
          $this->logSection('fail', sprintf('WARNING: unable to store entry "%s"', $item->getTitle()));
        }
        if ($options['max-entries'] > 0 && $i >= $options['max-entries'])
        {
          break;
        }
      }
      if ($i > 0)
      {
        $this->logSection('result', sprintf('successfully saved %d fetched entries', $i));
      }
      else
      {
        $this->logSection('result', 'no entry found');
      }
    }
  }
  
}