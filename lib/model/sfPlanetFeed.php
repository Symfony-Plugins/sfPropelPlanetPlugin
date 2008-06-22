<?php
/**
 * Subclass for representing a row from the 'sf_feed' table.
 *
 * @package plugins.sfPropelPlanetPlugin.lib.model
 */ 
class sfPlanetFeed extends BasesfPlanetFeed
{
  
  /**
   * Fetch feed entries
   *
   * @param  int  $max        Maximum number of entries to fetch
   * @return int              Number of entries actually fetched
   * @throws PropelException  If an error occured at saving time
   * @throws Exception        If feed cannot be fetched
   */
  public function fetchEntries($max = null)
  {
    $feed = sfFeedPeer::createFromWeb($this->getFeedUrl());
    
    $numEntries = 0;
    
    foreach ($feed->getItems() as $item)
    {
      $entry = sfPlanetFeedEntryPeer::createFromFeedItem($item, $this);
      $entry->save();
      
      $numEntries++;
      
      if (!is_null($max) && $max > 0 && $numEntries >= $max)
      {
        break;
      }
    }
    
    $this->setLastGrabbedAt(time());
    $this->save();
    
    return $numEntries;
  }
  
  /**
   * Checks if the feed is outdated 
   *
   * @return Boolean
   */
  public function isOutdated()
  {
    return ($this->getLastGrabbedAt(null) + $this->getPeriodicity()) < time();
  }
  
}

// Sluggable behavior
$columns_map = array('from' => BasesfPlanetFeedPeer::TITLE,
                     'to'   => BasesfPlanetFeedPeer::SLUG);

sfPropelBehavior::add('sfPlanetFeed', 
                      array('sfPropelActAsSluggableBehavior' => 
                            array('columns'   => $columns_map, 
                                  'separator' => '-', 
                                  'permanent' => true)));