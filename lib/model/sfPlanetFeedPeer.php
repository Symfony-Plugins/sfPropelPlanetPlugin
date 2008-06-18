<?php
/**
 * Subclass for performing query and update operations on the 'sf_feed' table.
 * 
 * @package plugins.sfPropelPlanetPlugin.lib.model
 */ 
class sfPlanetFeedPeer extends BasesfPlanetFeedPeer
{

  /**
   * Retrieves a sfPlanetFeed by its slug
   *
   * @param  string       $url        The feed url
   * @return sfPlanetFeed|null
   */
  public static function retrieveBySlug($slug)
  {
    $c = new Criteria();
    $c->add(self::SLUG, $slug);
    return self::doSelectOne($c);
  }
  
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
      self::LAST_GRABBED_AT, self::getPeremptionClause(), Criteria::CUSTOM
    ));
    $c->addAnd($criterion);
    
    return self::doSelect($c);
  }
  
  /**
   * Generates the SQL peremption clause
   *
   * FIXME:  this works only for mysql
   * @return string
   */
  public static function getPeremptionClause()
  {
    return sprintf('UNIX_TIMESTAMP(%s) <= UNIX_TIMESTAMP() - %s',
                   self::LAST_GRABBED_AT,
                   self::PERIODICITY);
  }
  
  /**
   * Grab entries from perempted feeds 
   *
   * @param  Criteria   $c
   * @return int        Number of entries grabbed
   * @throws Exception
   */
  public static function fetchPerempted(Criteria $c = null)
  {
    $n = 0;
    foreach (self::getPerempted($c) as $feed)
    {
      $parsed_feed = sfFeedPeer::createFromWeb($feed->getFeedUrl());
      
      foreach ($parsed_feed->getItems() as $item)
      {
        $entry = sfPlanetFeedEntryPeer::createFromFeedItem($item, $feed);
        $entry->save();
        $n++;
      }
      
      $feed->setLastGrabbedAt(time());
      $feed->save();
      
      $n++;
    }
    return $n;
  }
  
}
