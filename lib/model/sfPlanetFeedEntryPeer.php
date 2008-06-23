<?php
/**
 * Subclass for performing query and update operations on the 'sf_feed_entry' table.
 *
 * @package plugins.sfPropelPlanetPlugin.lib.model
 */ 
class sfPlanetFeedEntryPeer extends BasesfPlanetFeedEntryPeer
{
  
  /**
   * Deletes entries older than provided timestamp
   *
   * @param  int           $timestamp  The UNIX timestamp
   * @param  sfPlanetFeed  $feed       The feed (optional)
   * @return int                       The number of deleted entries
   */
  public static function doDeleteOlderThan($timestamp, sfPlanetFeed $feed = null)
  {
    $c = new Criteria();
    
    if (!is_null($feed))
    {
      $c->add(self::FEED_ID, $feed->getId());
    }
    
    $c->add(self::PUBLISHED_AT, date('Y-m-d H:i:s'), Criteria::LESS_THAN);
    
    return self::doDelete($c);
  }
  
  /**
   * Generates a Criteria to retrieve active feed(s) entries
   *
   * @param  sfPlanetFeed  $feed  The feed instance (optional)
   * @return Criteria
   */
  public static function getActiveListCriteria(sfPlanetFeed $feed = null)
  {
    $c = new Criteria();
    
    if (!is_null($feed))
    {
      $c->add(self::FEED_ID, $feed->getId());
    }
    
    $c->addJoin(self::FEED_ID, sfPlanetFeedPeer::ID, Criteria::LEFT_JOIN);
    $c->add(sfPlanetFeedPeer::IS_ACTIVE, true);    
    $c->addDescendingOrderByColumn(self::PUBLISHED_AT);
    
    return $c;
  }
  
  /**
   * Search for an existing entry and returns it, or returns a new instance
   * 
   * @param  sfFeedItem
   * @return sfPlanetFeedEntry 
   */
  public static function getOrCreateFromFeedItem(sfFeedItem $item)
  {
    $c = new Criteria();
    $c->add(self::UNIQUE_ID, sprintf('%s = MD5(CONCAT(\'%s\', \'%s\'))',
                                     self::UNIQUE_ID,
                                     date('Y-m-d H:i:s', $item->getPubdate()),
                                     $item->getLink()),
                             Criteria::CUSTOM);
    if (!$entry = self::doSelectOne($c))
    {
      return new sfPlanetFeedEntry();
    }
    else
    {
      return $entry;
    }
  }
  
  /**
   * Creates a new entry from a grabbed feed entry
   *
   * @param  sfFeedItem   $item
   * @param  sfPlanetFeed $feed
   * @return sfPlanetFeedEntry 
   */
  public static function createFromFeedItem(sfFeedItem $item, sfPlanetFeed $feed)
  {
    $entry = self::getOrCreateFromFeedItem($item);
    
    $entry->setFeedId($feed->getId());
    $entry->setTitle($item->getTitle());
    $entry->setLinkUrl($item->getLink());
    $entry->setAuthor($item->getAuthorName());
    
    if ($item->getContent())
    {
      $entry->setContent($item->getContent());
    }
    else
    {
      $entry->setContent($item->getDescription());
    }
    $entry->setPublishedAt($item->getPubdate());
    
    return $entry;
  }
  
}
