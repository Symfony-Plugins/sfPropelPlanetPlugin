<?php
/**
 * sfPropelPlanetPlugin base task class
 *
 */
class sfPlanetBaseTask extends sfBaseTask
{
  
  /**
   * Retrieves an available application name
   *
   * @return string
   */
  protected static function getDefaultAppName()
  {
    return array_pop(scandir(sfConfig::get('sf_apps_dir')));
  }
  
  /**
   * Grab a feed from its Propel representation
   *
   * @param  sfPlanetFeed $feed     The Propel feed object
   * @param  Boolean      $verbose  Log verbosely added feed entries
   * @return int                    The number of grabbed entries
   */
  protected function grabFeedEntries(sfPlanetFeed $feed, $verbose = false)
  {
    $this->logSection('feed', sprintf('Fetching %s', $feed->getTitle()));
      
    try
    {
      $parsed_feed = sfFeedPeer::createFromWeb($feed->getFeedUrl());
    }
    catch (Exception $e)
    {
      $this->logError(sprintf('Unable to fetch feed at "%s": %s', $url, $e->getMessage()));
    }
    
    $m = 0;
    foreach ($parsed_feed->getItems() as $item)
    {
      $this->grabFeedEntry($item, $feed, $verbose);
      $m++;
    }
    
    $this->logSection('entries', ($m > 1 ? $m.' entries' : ($m > 0 ? 'one entry' : 'no entry')) . ' fetched');
    
    $feed->setLastGrabbedAt(time());
    $feed->save();
  }
  
  /**
   * Grab a feed entry and add it to a planet feed
   *
   * @param  sfFeedItem    $item
   * @param  sfPlanetFeed  $feed
   * @param  Boolean       $verbose      
   */
  protected function grabFeedEntry(sfFeedItem $item, sfPlanetFeed $feed, $verbose = false)
  {
    try
    {
      $entry = sfPlanetFeedEntryPeer::createFromFeedItem($item, $feed);
      $entry->save();
      
      if ($verbose)
      {
        $this->logSection('entry', sprintf('Entry "%s" saved', $item->getTitle()));
      }
    }
    catch (PropelException $e)
    {
      if ($verbose)
      {
        $this->logError(sprintf('Error while saving entry "%s": %s', $item->getTitle(), $e->getMessage()));
      }
    }
    catch (Exception $e)
    {
      if ($verbose)
      {
        $this->logError(sprintf('Error while fetching entry "%s": %s', $item->getTitle(), $e->getMessage()));
      }
    }
  }
  
  /**
   * Prints a pretty error in the command line interface
   *
   * @param string $message
   * @param int    $size
   */
  public function logError($message, $size = null)
  {
    return $this->logSection('error', $message, $size, 'ERROR');
  }
  
  /**
   * Implemented here because declared abstract in parent class
   * 
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    return parent::execute($arguments, $options);
  }

}
