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
   * Retrieves outdated feeds
   *
   * @param  Criteria $c
   * @return array|null
   */
  public static function getOutdated(Criteria $c = null)
  {
    $c = $c instanceof Criteria ? $c : new Criteria();
 
    $criterion = $c->getNewCriterion (
      self::LAST_GRABBED_AT, NULL, Criteria::ISNULL
    )->addOr($c->getNewCriterion (
      self::LAST_GRABBED_AT, self::getOutOfDateClause(), Criteria::CUSTOM
    ));
    $c->addAnd($criterion);
    
    return self::doSelect($c);
  }
  
  /**
   * Generates the SQL out of date clause
   *
   * FIXME:  this works only for mysql
   * @return string
   */
  public static function getOutOfDateClause()
  {
    return sprintf('UNIX_TIMESTAMP(%s) <= UNIX_TIMESTAMP() - %s',
                   self::LAST_GRABBED_AT,
                   self::PERIODICITY);
  }
  
  /**
   * Grab entries from outdated feeds 
   *
   * @param  Criteria   $c
   * @return int        Number of entries grabbed
   * @throws Exception
   */
  public static function fetchOutdated(Criteria $c = null)
  {
    $n = 0;
    foreach (self::getOutdated($c) as $feed)
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
  
  /**
   * Creates a feed directly from a feed url, using the sfFeed2Plugin tools
   *
   * @param  string  $url          The feed url
   * @param  int     $periodicity  The time for the feed to be outdated (default: one day)
   * @param  Boolean $activate     Activate the feed? (default: true)
   * @return sfPlanetFeed          The created feed
   * @throws Exception             If the feed cannot be fetched
   * @throws PropelException       If an SQL error occured or if feed url already exists
   */
  public static function createFromWeb($url, $periodicity = 86400, $activate = true)
  {
    $feed = sfFeedPeer::createFromWeb($url);
    
    if (self::feedExists($url))
    {
      throw new PropelException(sprintf('Feed "%s" already exists in database', $url));
    }
    
    $newFeed = new sfPlanetFeed();
    
    $newFeed->fromArray(array(
      'title'       => $feed->getTitle(),
      'description' => $feed->getDescription(),
      'homepage'    => $feed->getLink(),
      'feed_url'    => $url,
      'is_active'   => $activate ? true : false,
      'periodicity' => $periodicity,
    ), BasePeer::TYPE_FIELDNAME);
    
    $newFeed->save();
    
    return $newFeed;
  }
  
}
