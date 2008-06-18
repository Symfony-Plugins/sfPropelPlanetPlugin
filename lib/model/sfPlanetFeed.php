<?php
/**
 * Subclass for representing a row from the 'sf_feed' table.
 *
 * @package plugins.sfPropelPlanetPlugin.lib.model
 */ 
class sfPlanetFeed extends BasesfPlanetFeed
{
  
  /**
   * Checks if the feed is perempted 
   *
   * @return Boolean
   */
  public function isPerempted()
  {
    return ($this->getLastGrabbedAt(null) + $this->getPeriodicity()) < time();
  }
  
}

$columns_map = array('from' => BasesfPlanetFeedPeer::TITLE,
                     'to'   => BasesfPlanetFeedPeer::SLUG);

sfPropelBehavior::add('sfPlanetFeed', 
                      array('sfPropelActAsSluggableBehavior' => 
                            array('columns'   => $columns_map, 
                                  'separator' => '-', 
                                  'permanent' => true)));