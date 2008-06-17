<?php
/**
 * Subclass for performing query and update operations on the 'sf_feed' table.
 * 
 * @package plugins.sfPropelPlanetPlugin.lib.model
 */ 
class sfPlanetFeedPeer extends BasesfPlanetFeedPeer
{
  
  /**
   * Checks if a feed url has already been registered
   *
   * @param  string  $url  The feed url
   * @return Boolean
   */
  public static function feedExists($url)
  {
    $c = new Criteria();
    $c->add(self::FEED_URL, $url);
    return (self::doCount($c) > 0);
  }
  
  /**
   * Retrieves perempted feeds
   *
   * @param  Criteria $c
   * @return array|null
   */
  public static function getPerempted(Criteria $c = null)
  {
    $c = $c instanceof Criteria ? $c : new Criteria();
 
    $criterion = $c->getNewCriterion (
      self::LAST_GRABBED_AT, NULL, Criteria::ISNULL
    )->addOr($c->getNewCriterion (
      self::LAST_GRABBED_AT, sprintf('UNIX_TIMESTAMP(%s) <= UNIX_TIMESTAMP() - %s',
                                     self::LAST_GRABBED_AT,
                                     self::PERIODICITY), 
                             Criteria::CUSTOM
    ));
    $c->addAnd($criterion);
    
    return self::doSelect($c);
  }
  
  /**
   * Grab entries from perempted feeds 
   *
   * @param  Criteria $c
   * @return int         Number of entries grabbed
   */
  public static function fetchPerempted(Criteria $c = null)
  {
    $n = 0;
    foreach (self::getPerempted($c) as $feed)
    {
      $parsed_feed = sfFeedPeer::createFromWeb($feed->getFeedUrl());
      $m = 0;
      foreach ($parsed_feed->getItems() as $item)
      {
        $entry = sfPlanetFeedEntryPeer::createFromFeedItem($item, $feed);
        $entry->save();
        $m++;
      }
      $feed->setLastGrabbedAt(time());
      $feed->save();
      $n++;
    }
    return $n;
  }
  
}
